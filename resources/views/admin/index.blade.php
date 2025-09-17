<x-app-layout>


    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 relative overflow-hidden py-12">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Neumorphic Quick Actions -->
            <div class="mb-12">
                <div class="p-8 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30 hover:shadow-[25px_25px_70px_#bebebe,-25px_-25px_70px_#ffffff] transition-all duration-500">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-[8px_8px_16px_#3b82f6/30] mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-blue-600 bg-clip-text text-transparent">Quick Actions</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('admin.timer') }}" class="group p-6 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-[12px_12px_24px_#3b82f6/30] hover:shadow-[16px_16px_32px_#3b82f6/40] transform hover:scale-105 transition-all duration-300">
                            <div class="text-3xl mb-3">⏱️</div>
                            <div class="font-manrope font-bold text-lg mb-2">Timer Control</div>
                            <div class="text-sm opacity-90">Kontrol waktu presentasi</div>
                        </a>
                        
                        <a href="{{ route('admin.messages') }}" class="group p-6 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-[12px_12px_24px_#a855f7/30] hover:shadow-[16px_16px_32px_#a855f7/40] transform hover:scale-105 transition-all duration-300">
                            <div class="text-3xl mb-3">💬</div>
                            <div class="font-manrope font-bold text-lg mb-2">Messages</div>
                            <div class="text-sm opacity-90">Kelola pesan display</div>
                        </a>
                        
                        <a href="{{ route('admin.settings') }}" class="group p-6 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-[12px_12px_24px_#10b981/30] hover:shadow-[16px_16px_32px_#10b981/40] transform hover:scale-105 transition-all duration-300">
                            <div class="text-3xl mb-3">⚙️</div>
                            <div class="font-manrope font-bold text-lg mb-2">Settings</div>
                            <div class="text-sm opacity-90">Pengaturan tampilan</div>
                        </a>
                    </div>
                </div>
            </div>
            

            

            
            <!-- Neumorphic Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-stretch">

                <!-- Message Settings Card -->
                <div class="group h-full">
                    <div class="h-full p-8 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30 hover:shadow-[25px_25px_70px_#bebebe,-25px_-25px_70px_#ffffff] transition-all duration-500 transform hover:scale-[1.02] flex flex-col">
                        <div class="flex items-center mb-6">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-[8px_8px_16px_#a855f7/30] mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-purple-600 bg-clip-text text-transparent">
                                Display Mode
                            </h3>
                        </div>
                        <div class="flex-grow">
                            @livewire('admin.settings-form')
                        </div>
                    </div>
                </div>

                <!-- Timer Control Card -->
                <div class="group h-full">
                    <div class="h-full p-8 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30 hover:shadow-[25px_25px_70px_#bebebe,-25px_-25px_70px_#ffffff] transition-all duration-500 transform hover:scale-[1.02] flex flex-col">
                        <div class="flex items-center mb-6">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-[8px_8px_16px_#3b82f6/30] mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-blue-600 bg-clip-text text-transparent">
                                Timer Control
                            </h3>
                        </div>
                        <div class="flex-grow">
                            @livewire('admin.timer-control')
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    
    <script>
        // Function to switch display mode
        function switchDisplayMode(mode) {
            // Dispatch Livewire event to change mode
            if (mode === 'timer') {
                Livewire.dispatch('switch-to-timer');
            } else if (mode === 'message') {
                Livewire.dispatch('switch-to-message');
            }
            
            // Update current mode display
            updateCurrentModeDisplay(mode);
            
            // Show feedback
            showModeChangeNotification(mode);
        }
        
        // Update current mode indicator
        function updateCurrentModeDisplay(mode) {
            const display = document.getElementById('current-mode-display');
            if (display) {
                display.textContent = mode === 'timer' ? 'Timer Mode' : 'Message Mode';
                display.className = mode === 'timer' 
                    ? 'font-semibold text-lg text-blue-600'
                    : 'font-semibold text-lg text-green-600';
            }
        }
        

        
        // Enhanced mode change notification with Neumorphic design
        function showModeChangeNotification(mode) {
            // Create notification element with advanced styling
            const notification = document.createElement('div');
            notification.className = 'fixed top-8 right-8 p-6 rounded-2xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-md shadow-[16px_16px_32px_#bebebe,-16px_-16px_32px_#ffffff] border border-white/30 z-50 transform translate-x-full opacity-0 transition-all duration-500 ease-out';
            
            const icon = mode === 'timer' ? '⏱️' : '💬';
            const modeText = mode === 'timer' ? 'Timer' : 'Message';
            
            notification.innerHTML = `
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-[6px_6px_12px_#3b82f6/30] animate-pulse">
                        <span class="text-2xl">${icon}</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">Mode Switched!</h4>
                        <p class="text-slate-600 font-medium">Now displaying: ${modeText}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 100);
            
            // Animate out after 4 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 500);
            }, 4000);
        }
        
        // Enhanced page loading with animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading animation to cards
            const cards = document.querySelectorAll('.group');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
            
            // Add floating animation to action buttons
            const floatingButtons = document.querySelectorAll('.fixed .p-3');
            floatingButtons.forEach((btn, index) => {
                btn.style.animation = `float ${2 + index * 0.5}s ease-in-out infinite`;
            });
            
            // Fetch current mode from server
            fetch('/api/current-display-mode')
                .then(response => response.json())
                .then(data => {
                    updateCurrentModeDisplay(data.mode);
                })
                .catch(error => {
                    console.error('Error fetching current mode:', error);
                    // Fallback to timer mode if fetch fails
                    updateCurrentModeDisplay('timer');
                });
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Only activate if not typing in input fields
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            switch(e.key.toLowerCase()) {
                case 't': // T - Switch to timer
                    e.preventDefault();
                    switchDisplayMode('timer');
                    break;
                case 'm': // M - Switch to message
                    e.preventDefault();
                    switchDisplayMode('message');
                    break;
            }
        });
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
                50% { box-shadow: 0 0 30px rgba(59, 130, 246, 0.6); }
            }
            
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
            
            .shimmer {
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
                background-size: 200% 100%;
                animation: shimmer 2s infinite;
            }
            
            .hover-glow:hover {
                animation: pulse-glow 2s infinite;
            }
            
            /* Enhanced transitions for all interactive elements */
            button, a, .group {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Smooth scroll behavior */
            html {
                scroll-behavior: smooth;
            }
            
            /* Loading skeleton animation */
            .skeleton {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: shimmer 1.5s infinite;
            }
        `;
        document.head.appendChild(style);
        
        // Add interactive sound effects (optional)
        function playClickSound() {
            // Create a subtle click sound using Web Audio API
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        }
        
        // Add click sound to all buttons
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.closest('button') || e.target.closest('a')) {
                try {
                    playClickSound();
                } catch (error) {
                    // Silently fail if audio context is not available
                }
            }
        });
        
        // Add hover effects to cards
        document.querySelectorAll('.group').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02) translateY(-5px)';
                this.classList.add('hover-glow');
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateY(0)';
                this.classList.remove('hover-glow');
            });
        });
    </script>
</x-app-layout>