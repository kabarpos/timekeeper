<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 relative overflow-hidden py-12">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Navigation -->
            <div class="mb-8">
                <div class="p-6 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-[8px_8px_16px_#3b82f6/30] mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-blue-600 bg-clip-text text-transparent">
                                Timer Control
                            </h2>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.index') }}" class="group p-3 rounded-xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[8px_8px_16px_#bebebe,-8px_-8px_16px_#ffffff] hover:shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30 transition-all duration-300 transform hover:scale-105">
                                <div class="flex items-center space-x-2 text-slate-700 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span class="text-sm">Dashboard</span>
                                </div>
                            </a>
                            <a href="{{ route('display.timer') }}" target="_blank" class="group p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-[8px_8px_16px_#3b82f6/30] hover:shadow-[12px_12px_24px_#3b82f6/40] transition-all duration-300 transform hover:scale-105">
                                <div class="flex items-center space-x-2 font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm">Open Display</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Timer Control - 2 columns -->
                <div class="lg:col-span-1">
                    @livewire('admin.timer-control')
                </div>
                
                <!-- Timer Settings - 1 column -->
                <div class="lg:col-span-1">
                    @livewire('admin.timer-settings')
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Keyboard shortcuts untuk admin
        document.addEventListener('keydown', function(e) {
            // Hanya aktif jika tidak sedang mengetik di input
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            switch(e.key) {
                case ' ': // Spacebar - Start/Pause
                    e.preventDefault();
                    Livewire.dispatch('toggle-timer');
                    break;
                case 'r': // R - Reset
                case 'R':
                    e.preventDefault();
                    if (confirm('Reset timer?')) {
                        Livewire.dispatch('reset-timer');
                    }
                    break;
                case 'm': // M - Switch to message
                case 'M':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-message');
                    break;
                case 't': // T - Switch to timer
                case 'T':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-timer');
                    break;
            }
        });
    </script>
</x-app-layout>