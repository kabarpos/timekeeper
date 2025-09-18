<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Navigation -->
            <div class="mb-6">
                <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-x-3">
                            <div class="flex-shrink-0 size-10 bg-blue-600 text-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-comments text-sm"></i>
                            </div>
                            <div class="grow">
                                <h2 class="text-lg font-semibold text-gray-900">Messages Management</h2>
                                <p class="text-sm text-gray-500">Kelola pesan dan pengaturan tampilan</p>
                            </div>
                        </div>
                        <div class="flex gap-x-2">
                            <a href="{{ route('admin.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50">
                                <i class="fas fa-arrow-left text-xs"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('display.index') }}" target="_blank" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                                <i class="fas fa-external-link-alt text-xs"></i>
                                Open Display
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Message Color Settings - Sidebar -->
                <div class="xl:col-span-1">
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6 sticky top-6">
                        <div class="flex items-center gap-x-3 mb-6">
                            <div class="flex-shrink-0 size-8 bg-purple-600 text-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-palette text-sm"></i>
                            </div>
                            <div class="grow">
                                <h3 class="text-lg font-semibold text-gray-900">Pengaturan Warna</h3>
                                <p class="text-xs text-gray-500">Atur tampilan pesan</p>
                            </div>
                        </div>
                        @livewire('admin.message-color-settings')
                    </div>
                </div>

                <!-- Message Management - Main Content -->
                <div class="xl:col-span-2">
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
                        <div class="flex items-center gap-x-3 mb-6">
                            <div class="flex-shrink-0 size-8 bg-green-600 text-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-sm"></i>
                            </div>
                            <div class="grow">
                                <h3 class="text-lg font-semibold text-gray-900">Message Management</h3>
                                <p class="text-xs text-gray-500">Kelola konten pesan</p>
                            </div>
                        </div>
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