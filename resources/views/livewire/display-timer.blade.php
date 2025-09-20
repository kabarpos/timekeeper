<div 
    class="min-h-screen flex items-center justify-center p-8 font-manrope"
    style="background-color: {{ $background_color }}; color: {{ $font_color }};"
    @if($timer && $timer->isRunning()) wire:poll.1000ms="updateTimeDisplay" @endif>
    
    <div class="text-center w-full">

         <!-- Additional Info -->
        @if($timer && $timer->status !== 'stopped')
            <div class="text-7xl font-manrope uppercase">
                @if($timer->isRunning())
                    Sisa Waktu
                @elseif($timer->isPaused())
                    Timer dijeda
                @endif
            </div>
        @endif

        <!-- Timer Display -->
        <div class="mb-8">
            <div class="text-8xl lg:text-[36rem] font-manrope font-bold leading-none mb-4 transition-all duration-300 {{ $is_warning ? 'animate-pulse text-red-400' : '' }}">
                {{ $formatted_time }}
            </div>
            

            
            <!-- Warning Indicator -->
            @if($is_warning)
                <div class="animate-bounce">
                    <div class="text-3xl font-manrope font-bold text-red-500 bg-red-100 bg-opacity-90 text-red-800 rounded-lg px-6 py-3 inline-block shadow-lg border-2 border-red-400">
                        ⚠️ WAKTU HAMPIR HABIS!
                    </div>
                </div>
            @endif
        </div>
        
       
        
        <!-- Progress Bar (Optional) -->
        @if($timer && $timer->duration_seconds > 0)
            <div class="mt-2 w-full max-w-2xl mx-auto">
                <div class="bg-white bg-opacity-20 rounded-full h-3">
                    @php
                        $progress = $timer->duration_seconds > 0 
                            ? (($timer->duration_seconds - $timer->remaining_seconds) / $timer->duration_seconds) * 100 
                            : 0;
                        $progress = max(0, min(100, $progress));
                    @endphp
                    <div 
                        class="h-3 rounded-full transition-all duration-1000 ease-linear
                            {{ $is_warning ? 'bg-red-500' : 'bg-white bg-opacity-60' }}"
                        style="width: {{ $progress }}%">
                    </div>
                </div>
                <div class="text-sm font-manrope opacity-60 mt-0">
                    {{ number_format($progress, 1) }}% selesai
                </div>
            </div>
        @endif
    </div>
    
    
    
    <!-- Interactive features script -->
    <script>
        let lastRemainingSeconds = null;
        let hasNotifiedWarning = false;
        let hasNotifiedFinished = false;
        let clientSideTimer = null;
        let isTimerRunning = false;
        let clientRemainingSeconds = 0;
        let lastSyncTime = null;
        
        // Smooth countdown function
        function updateClientTimer() {
            if (!isTimerRunning || clientRemainingSeconds <= 0) {
                return;
            }
            
            clientRemainingSeconds--;
            
            // Update display
            const minutes = Math.floor(clientRemainingSeconds / 60);
            const seconds = clientRemainingSeconds % 60;
            const formattedTime = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
            
            // Update timer display
            const timerElement = document.querySelector('.text-8xl');
            if (timerElement) {
                timerElement.textContent = formattedTime;
            }
            
            // Update progress bar
            const progressBar = document.querySelector('.h-3.rounded-full:not(.bg-white)');
            if (progressBar && {{ $timer ? $timer->duration_seconds : 0 }} > 0) {
                const progress = (({{ $timer ? $timer->duration_seconds : 0 }} - clientRemainingSeconds) / {{ $timer ? $timer->duration_seconds : 0 }}) * 100;
                progressBar.style.width = Math.max(0, Math.min(100, progress)) + '%';
            }
            
            // Update progress percentage
            const progressText = document.querySelector('.text-sm.opacity-60');
            if (progressText && {{ $timer ? $timer->duration_seconds : 0 }} > 0) {
                const progress = (({{ $timer ? $timer->duration_seconds : 0 }} - clientRemainingSeconds) / {{ $timer ? $timer->duration_seconds : 0 }}) * 100;
                progressText.textContent = progress.toFixed(1) + '% selesai';
            }
            
            // Warning state
            const timerDisplay = document.querySelector('.text-8xl');
            if (clientRemainingSeconds <= 60 && clientRemainingSeconds > 0) {
                if (timerDisplay) {
                    timerDisplay.classList.add('animate-pulse', 'text-red-400');
                }
            } else {
                if (timerDisplay) {
                    timerDisplay.classList.remove('animate-pulse', 'text-red-400');
                }
            }
            
            // Timer finished
            if (clientRemainingSeconds <= 0) {
                stopClientTimer();
                if (!hasNotifiedFinished) {
                    // Visual flash effect only (no annoying popup)
                    document.body.style.animation = 'flash 0.5s ease-in-out 3';
                    setTimeout(() => {
                        document.body.style.animation = '';
                    }, 1500);
                    
                    hasNotifiedFinished = true;
                }
            }
        }
        
        function startClientTimer(remainingSeconds) {
            stopClientTimer();
            clientRemainingSeconds = remainingSeconds;
            isTimerRunning = true;
            lastSyncTime = Date.now();
            clientSideTimer = setInterval(updateClientTimer, 1000);
        }
        
        function stopClientTimer() {
            if (clientSideTimer) {
                clearInterval(clientSideTimer);
                clientSideTimer = null;
            }
            isTimerRunning = false;
        }
        
        function syncWithServer(serverRemainingSeconds, serverStatus) {
            if (serverStatus === 'running') {
                // Sync client timer with server
                const timeDiff = lastSyncTime ? (Date.now() - lastSyncTime) / 1000 : 0;
                const expectedClientSeconds = Math.max(0, clientRemainingSeconds - Math.floor(timeDiff));
                const serverClientDiff = Math.abs(expectedClientSeconds - serverRemainingSeconds);
                
                // Resync if difference is more than 2 seconds
                if (serverClientDiff > 2 || !isTimerRunning) {
                    startClientTimer(serverRemainingSeconds);
                }
            } else {
                stopClientTimer();
                clientRemainingSeconds = serverRemainingSeconds;
            }
        }
        
        // Timer status polling variables
        let timerPollingInterval = null;
        let lastKnownStatus = '{{ $timer ? $timer->status : "stopped" }}';
        let lastKnownRemaining = {{ $timer ? $timer->remaining_seconds : 0 }};
        let pollingFrequency = 2000; // 2 seconds
        
        // Function to poll timer status from API
        function pollTimerStatus() {
            fetch('/api/current-timer-status')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'no_timer') {
                        return;
                    }
                    
                    // Check if status changed
                    if (data.status !== lastKnownStatus || Math.abs(data.remaining_seconds - lastKnownRemaining) > 2) {
                        lastKnownStatus = data.status;
                        lastKnownRemaining = data.remaining_seconds;
                        
                        // Update display based on new status
                        if (data.status === 'running') {
                            startClientTimer(data.remaining_seconds);
                        } else if (data.status === 'paused' || data.status === 'stopped') {
                            stopClientTimer();
                            clientRemainingSeconds = data.remaining_seconds;
                            
                            // Update display immediately
                            const minutes = Math.floor(data.remaining_seconds / 60);
                            const seconds = data.remaining_seconds % 60;
                            const formattedTime = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                            
                            const timerElement = document.querySelector('.text-8xl');
                            if (timerElement) {
                                timerElement.textContent = formattedTime;
                            }
                            
                            // Update status text
                            const statusElement = document.querySelector('.text-2xl.font-semibold');
                            if (statusElement) {
                                if (data.status === 'paused') {
                                    statusElement.textContent = 'Dijeda';
                                } else if (data.status === 'stopped') {
                                    statusElement.textContent = data.remaining_seconds <= 0 ? 'Waktu Habis!' : 'Timer Siap';
                                }
                            }
                        }
                        
                        // Trigger Livewire refresh to sync server state
                        Livewire.dispatch('refresh');
                    }
                })
                .catch(error => {
                    console.log('Timer polling error:', error);
                });
        }
        
        // Start timer status polling
        function startTimerPolling() {
            if (timerPollingInterval) {
                clearInterval(timerPollingInterval);
            }
            timerPollingInterval = setInterval(pollTimerStatus, pollingFrequency);
        }
        
        // Stop timer status polling
        function stopTimerPolling() {
            if (timerPollingInterval) {
                clearInterval(timerPollingInterval);
                timerPollingInterval = null;
            }
        }

        // Monitor timer changes for notifications
        document.addEventListener('livewire:init', () => {
            // Initialize client timer based on server state
            @if($timer && $timer->isRunning())
                startClientTimer({{ $timer->remaining_seconds }});
            @endif
            
            // Start polling for timer status changes
            startTimerPolling();
            
            Livewire.on('timer-finished', () => {
                stopClientTimer();
            });
            
            // Reset notification flags when timer starts
            Livewire.on('reset-notifications', () => {
                hasNotifiedWarning = false;
                hasNotifiedFinished = false;
            });
        });
        
        // Sync with server every Livewire update
        document.addEventListener('livewire:updated', () => {
            @if($timer)
                syncWithServer({{ $timer->remaining_seconds }}, '{{ $timer->status }}');
            @else
                stopClientTimer();
            @endif
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // F11 untuk fullscreen
            if (e.key === 'F11') {
                e.preventDefault();
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }
        });
    </script>
    
    <!-- CSS Animations -->
    <style>
        @keyframes flash {
            0%, 100% { background-color: inherit; }
            50% { background-color: rgba(239, 68, 68, 0.3); }
        }
    </style>
</div>
