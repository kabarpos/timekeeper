<x-app-layout>
    <!-- Modern Background with Gradient -->
    <div class="min-h-screen  bg-gray-100 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-3xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-clock text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 mb-1">Timer Management</h1>
                                <p class="text-gray-600">Kelola timer dan pengaturan tampilan dengan mudah</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/80 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                                <i class="fas fa-arrow-left text-sm"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('display.timer') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-external-link-alt text-sm"></i>
                                Open Display
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Timer Settings - Sidebar -->
                <div class="xl:col-span-1">
                    <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-3xl p-6 sticky top-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-cog text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Pengaturan Timer</h3>
                                <p class="text-sm text-gray-600">Atur durasi dan tampilan</p>
                            </div>
                        </div>
                        @livewire('admin.timer-settings')
                    </div>
                </div>

                <!-- Timer Control - Main Content -->
                <div class="xl:col-span-2">
                    <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-3xl p-8">
                       
                        @livewire('admin.timer-control')
                    </div>
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