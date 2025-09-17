<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-manrope font-semibold text-xl text-brand-primary leading-tight">
                {{ __('Display Settings') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.index') }}" class="bg-brand-primary hover:bg-brand-secondary text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('display.index') }}" target="_blank" class="bg-brand-primary hover:bg-brand-secondary text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    🖥️ Open Main Display
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Neumorphic Display Links -->
            <div class="mb-12">
                <div class="p-8 rounded-3xl bg-gradient-to-br from-white/90 to-slate-50/90 backdrop-blur-sm shadow-[20px_20px_60px_#bebebe,-20px_-20px_60px_#ffffff] border border-white/30 hover:shadow-[25px_25px_70px_#bebebe,-25px_-25px_70px_#ffffff] transition-all duration-500">
                    <div class="flex items-center mb-6">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 shadow-[8px_8px_16px_#06b6d4/30] mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-slate-700 to-cyan-600 bg-clip-text text-transparent">Display Links</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('display.index') }}" target="_blank" class="group p-6 rounded-2xl bg-gradient-to-br from-slate-600 to-gray-700 text-white shadow-[12px_12px_24px_#475569/30] hover:shadow-[16px_16px_32px_#475569/40] transform hover:scale-105 transition-all duration-300">
                            <div class="text-3xl mb-3">🖥️</div>
                            <div class="font-manrope font-bold text-lg mb-2">Main Display</div>
                            <div class="text-sm opacity-90">Display utama (auto switch)</div>
                        </a>
                        
                        <a href="{{ route('display.timer') }}" target="_blank" class="group p-6 rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-[12px_12px_24px_#f97316/30] hover:shadow-[16px_16px_32px_#f97316/40] transform hover:scale-105 transition-all duration-300">
                            <div class="text-3xl mb-3">⏰</div>
                            <div class="font-manrope font-bold text-lg mb-2">Timer Only</div>
                            <div class="text-sm opacity-90">Hanya tampilan timer</div>
                        </a>
                        
                        <a href="{{ route('display.message') }}" target="_blank" class="group p-6 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-[12px_12px_24px_#8b5cf6/30] hover:shadow-[16px_16px_32px_#8b5cf6/40] transform hover:scale-105 transition-all duration-300">
                            <div class="text-3xl mb-3">📝</div>
                            <div class="font-manrope font-bold text-lg mb-2">Message Only</div>
                            <div class="text-sm opacity-90">Hanya tampilan pesan</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="bg-brand-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-brand-light">
                <div class="p-6 text-brand-primary">
                    @livewire('admin.settings-form')
                </div>
            </div>
            

        </div>
    </div>
    
    <script>
        // Keyboard shortcuts untuk settings
        document.addEventListener('keydown', function(e) {
            // Hanya aktif jika tidak sedang mengetik di input
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            switch(e.key) {
                case 't': // T - Switch to timer
                case 'T':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-timer');
                    break;
                case 'm': // M - Switch to message
                case 'M':
                    e.preventDefault();
                    Livewire.dispatch('switch-to-message');
                    break;
                case 'r': // R - Reset to default
                case 'R':
                    e.preventDefault();
                    if (confirm('Reset settings to default?')) {
                        Livewire.dispatch('reset-to-default');
                    }
                    break;
            }
        });
    </script>
</x-app-layout>