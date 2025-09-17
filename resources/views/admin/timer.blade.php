<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Timer Control') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('display.timer') }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    🖥️ Open Timer Display
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Timer Control -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    @livewire('admin.timer-control')
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