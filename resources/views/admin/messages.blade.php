<x-app-layout>
    <!-- Modern Background with Gradient -->
    <div class="min-h-screen bg-gray-100 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-3xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-comments text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 mb-1">Messages Management</h1>
                                <p class="text-gray-600">Kelola pesan dan pengaturan tampilan dengan mudah</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/80 border border-gray-200 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm">
                                <i class="fas fa-arrow-left text-sm"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('display.index') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-violet-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-violet-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-external-link-alt text-sm"></i>
                                Open Display
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Message Color Settings - Sidebar -->
                <div class="xl:col-span-1">
                    <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-3xl p-6 sticky top-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-palette text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Pengaturan Warna</h3>
                                <p class="text-sm text-gray-600">Atur tampilan pesan</p>
                            </div>
                        </div>
                        @livewire('admin.message-color-settings')
                    </div>
                </div>

                <!-- Message Management - Main Content -->
                <div class="xl:col-span-2">
                    <div class="bg-white/80 backdrop-blur-sm border border-gray-200/50 shadow-xl rounded-3xl p-8">
                
                        @livewire('admin.message-crud')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Keyboard shortcuts untuk message management
        document.addEventListener('keydown', function(e) {
            // Hanya aktif jika tidak sedang mengetik di input
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            switch(e.key) {
                case 'n': // N - New message
                case 'N':
                    e.preventDefault();
                    Livewire.dispatch('create-new-message');
                    break;
                case 'm': // M - Switch to message display
                case 'M':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-message');
                    break;
                case 't': // T - Switch to timer display
                case 'T':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-timer');
                    break;
            }
        });
    </script>
</x-app-layout>