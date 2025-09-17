<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-manrope font-semibold text-xl text-brand-primary leading-tight">
                {{ __('Message Management') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.index') }}" class="bg-brand-primary hover:bg-brand-secondary text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('display.message') }}" target="_blank" class="bg-brand-primary hover:bg-brand-secondary text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    🖥️ Open Message Display
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Message Color Settings -->
            <div class="mb-12">
                <div class="p-8 rounded-3xl bg-gradient-to-br from-white/80 to-slate-50/80 backdrop-blur-sm shadow-[12px_12px_24px_#bebebe,-12px_-12px_24px_#ffffff] border border-white/30">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 shadow-[8px_8px_16px_#a855f7/30] mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-purple-600 bg-clip-text text-transparent">Pengaturan Warna Message</h3>
                    </div>
                    @livewire('admin.message-color-settings')
                </div>
            </div>

            <!-- Message CRUD -->
            <div class="bg-brand-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-brand-light">
                <div class="p-6 text-brand-primary">
                    @livewire('admin.message-crud')
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