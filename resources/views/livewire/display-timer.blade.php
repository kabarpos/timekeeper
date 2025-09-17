<div 
    class="min-h-screen flex items-center justify-center p-8"
    style="background-color: {{ $background_color }}; color: {{ $font_color }};"
    wire:poll.1000ms="updateTimeDisplay">
    
    <div class="text-center w-full max-w-4xl">
        <!-- Timer Display -->
        <div class="mb-8">
            <div class="text-8xl md:text-9xl lg:text-[12rem] font-mono font-bold leading-none mb-4 transition-all duration-300 {{ $is_warning ? 'animate-pulse text-red-400' : '' }}">
                {{ $formatted_time }}
            </div>
            
            <!-- Status Text -->
            <div class="text-2xl md:text-3xl font-semibold opacity-80 mb-4 transition-all duration-300 {{ $timer && $timer->isRunning() ? 'text-green-400' : '' }}">
                {{ $status_text }}
            </div>
            
            <!-- Warning Indicator -->
            @if($is_warning)
                <div class="animate-bounce">
                    <div class="text-xl md:text-2xl font-bold text-red-500 bg-red-100 bg-opacity-90 text-red-800 rounded-lg px-6 py-3 inline-block shadow-lg border-2 border-red-400">
                        ⚠️ WAKTU HAMPIR HABIS!
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Additional Info -->
        @if($timer && $timer->status !== 'stopped')
            <div class="text-lg md:text-xl opacity-60">
                @if($timer->isRunning())
                    Timer sedang berjalan...
                @elseif($timer->isPaused())
                    Timer dijeda
                @endif
            </div>
        @endif
        
        <!-- Progress Bar (Optional) -->
        @if($timer && $timer->duration_seconds > 0)
            <div class="mt-8 w-full max-w-2xl mx-auto">
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
                <div class="text-sm opacity-60 mt-2">
                    {{ number_format($progress, 1) }}% selesai
                </div>
            </div>
        @endif
    </div>
    
    <!-- Keyboard Shortcuts Info (Hidden by default, can be toggled) -->
    <div class="fixed bottom-4 right-4 text-sm opacity-40 hover:opacity-80 transition-opacity">
        <div class="bg-black bg-opacity-50 rounded-lg p-3">
            <div class="text-xs">
                Tekan F11 untuk fullscreen
            </div>
        </div>
    </div>
    
    <!-- Interactive features script -->
    <script>
        let lastRemainingSeconds = null;
        let hasNotifiedWarning = false;
        let hasNotifiedFinished = false;
        
        // Monitor timer changes for notifications
        document.addEventListener('livewire:init', () => {
            Livewire.on('timer-finished', () => {
                if (!hasNotifiedFinished) {
                    // Browser notification
                    if (Notification.permission === 'granted') {
                        new Notification('⏰ Timer Selesai!', {
                            body: 'Waktu presentasi telah habis.',
                            icon: '/favicon.ico'
                        });
                    }
                    
                    // Visual flash effect
                    document.body.style.animation = 'flash 0.5s ease-in-out 3';
                    setTimeout(() => {
                        document.body.style.animation = '';
                    }, 1500);
                    
                    hasNotifiedFinished = true;
                }
            });
            
            // Reset notification flags when timer starts
            Livewire.on('reset-notifications', () => {
                hasNotifiedWarning = false;
                hasNotifiedFinished = false;
            });
        });
        
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        
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
