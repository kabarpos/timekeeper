<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Message Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('display.message') }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    🖥️ Open Message Display
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Message CRUD -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Message Management</h3>
                    @livewire('admin.message-crud')
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button onclick="Livewire.dispatch('create-new-message')" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">➕</div>
                            <div class="font-semibold">New Message</div>
                            <div class="text-sm opacity-90">Buat pesan baru</div>
                        </button>
                        
                        <button onclick="Livewire.dispatch('switch-to-message')" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📺</div>
                            <div class="font-semibold">Show Messages</div>
                            <div class="text-sm opacity-90">Tampilkan pesan di display</div>
                        </button>
                        
                        <button onclick="Livewire.dispatch('switch-to-timer')" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">⏱️</div>
                            <div class="font-semibold">Show Timer</div>
                            <div class="text-sm opacity-90">Kembali ke timer</div>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Display Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Display Settings</h3>
                    @livewire('admin.settings-form')
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