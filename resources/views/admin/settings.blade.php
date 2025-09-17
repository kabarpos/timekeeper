<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Display Settings') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    ← Back to Dashboard
                </a>
                <a href="{{ route('display.index') }}" target="_blank" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    🖥️ Open Main Display
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Settings Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Display Configuration</h3>
                    @livewire('admin.settings-form')
                </div>
            </div>
            
            <!-- Color Presets -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Color Presets</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button onclick="applyColorPreset('#000000', '#ffffff')" class="bg-black text-white p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Classic</div>
                            <div class="text-sm opacity-90">Black & White</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#1e40af', '#ffffff')" class="bg-blue-700 text-white p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Professional</div>
                            <div class="text-sm opacity-90">Blue & White</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#dc2626', '#ffffff')" class="bg-red-600 text-white p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Alert</div>
                            <div class="text-sm opacity-90">Red & White</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#059669', '#ffffff')" class="bg-green-600 text-white p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Success</div>
                            <div class="text-sm opacity-90">Green & White</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#7c3aed', '#ffffff')" class="bg-purple-600 text-white p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Creative</div>
                            <div class="text-sm opacity-90">Purple & White</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#ea580c', '#ffffff')" class="bg-orange-600 text-white p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Energy</div>
                            <div class="text-sm opacity-90">Orange & White</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#ffffff', '#000000')" class="bg-white text-black border-2 border-gray-300 p-4 rounded-lg text-center transition-colors hover:bg-gray-50">
                            <div class="font-semibold">Light</div>
                            <div class="text-sm opacity-70">White & Black</div>
                        </button>
                        
                        <button onclick="applyColorPreset('#374151', '#f9fafb')" class="bg-gray-700 text-gray-100 p-4 rounded-lg text-center transition-colors hover:opacity-80">
                            <div class="font-semibold">Modern</div>
                            <div class="text-sm opacity-90">Gray & Light</div>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Display Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Display Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('display.index') }}" target="_blank" class="bg-gray-600 hover:bg-gray-700 text-white p-6 rounded-lg text-center transition-colors block">
                            <div class="text-3xl mb-2">🖥️</div>
                            <div class="font-semibold text-lg">Main Display</div>
                            <div class="text-sm opacity-90">Display utama (auto switch)</div>
                        </a>
                        
                        <a href="{{ route('display.index') }}?mode=timer" target="_blank" class="bg-orange-500 hover:bg-orange-600 text-white p-6 rounded-lg text-center transition-colors block">
                            <div class="text-3xl mb-2">⏱️</div>
                            <div class="font-semibold text-lg">Timer Only</div>
                            <div class="text-sm opacity-90">Hanya tampilan timer</div>
                        </a>
                        
                        <a href="{{ route('display.index') }}?mode=message" target="_blank" class="bg-teal-500 hover:bg-teal-600 text-white p-6 rounded-lg text-center transition-colors block">
                            <div class="text-3xl mb-2">💬</div>
                            <div class="font-semibold text-lg">Message Only</div>
                            <div class="text-sm opacity-90">Hanya tampilan pesan</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function applyColorPreset(bgColor, fontColor) {
            Livewire.dispatch('apply-color-preset', {
                bg_color: bgColor,
                font_color: fontColor
            });
        }
        
        // Keyboard shortcuts untuk settings
        document.addEventListener('keydown', function(e) {
            // Hanya aktif jika tidak sedang mengetik di input
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            switch(e.key) {
                case '1': // 1 - Classic preset
                    e.preventDefault();
                    applyColorPreset('#000000', '#ffffff');
                    break;
                case '2': // 2 - Professional preset
                    e.preventDefault();
                    applyColorPreset('#1e40af', '#ffffff');
                    break;
                case '3': // 3 - Alert preset
                    e.preventDefault();
                    applyColorPreset('#dc2626', '#ffffff');
                    break;
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