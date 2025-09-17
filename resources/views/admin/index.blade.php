<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard - TimeKeeper') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.timer') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">⏱️</div>
                            <div class="font-semibold">Timer Control</div>
                            <div class="text-sm opacity-90">Kontrol waktu presentasi</div>
                        </a>
                        
                        <a href="{{ route('admin.messages') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">💬</div>
                            <div class="font-semibold">Messages</div>
                            <div class="text-sm opacity-90">Kelola pesan display</div>
                        </a>
                        
                        <a href="{{ route('admin.settings') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">⚙️</div>
                            <div class="font-semibold">Settings</div>
                            <div class="text-sm opacity-90">Pengaturan tampilan</div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Display Mode Control -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Display Mode Control</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button onclick="switchDisplayMode('timer')" class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-lg text-center transition-colors group">
                            <div class="text-3xl mb-2">⏱️</div>
                            <div class="font-semibold text-lg">Timer Mode</div>
                            <div class="text-sm opacity-90">Tampilkan countdown timer</div>
                            <div class="mt-2 text-xs opacity-75">Tekan 'T' untuk shortcut</div>
                        </button>
                        
                        <button onclick="switchDisplayMode('message')" class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-lg text-center transition-colors group">
                            <div class="text-3xl mb-2">💬</div>
                            <div class="font-semibold text-lg">Message Mode</div>
                            <div class="text-sm opacity-90">Tampilkan pesan aktif</div>
                            <div class="mt-2 text-xs opacity-75">Tekan 'M' untuk shortcut</div>
                        </button>
                    </div>
                    
                    <!-- Current Mode Indicator -->
                    <div class="mt-4 p-3 bg-gray-100 rounded-lg">
                        <div class="text-sm text-gray-600">Mode Aktif Saat Ini:</div>
                        <div id="current-mode-display" class="font-semibold text-lg text-gray-800">Loading...</div>
                    </div>
                </div>
            </div>
            
            <!-- Display Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Display Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('display.index') }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">🖥️</div>
                            <div class="font-semibold">Main Display</div>
                            <div class="text-sm opacity-90">Display utama (auto switch)</div>
                        </a>
                        
                        <a href="{{ route('display.timer') }}" target="_blank" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">⏰</div>
                            <div class="font-semibold">Timer Only</div>
                            <div class="text-sm opacity-90">Hanya tampilan timer</div>
                        </a>
                        
                        <a href="{{ route('display.message') }}" target="_blank" class="bg-teal-500 hover:bg-teal-600 text-white p-4 rounded-lg text-center transition-colors">
                            <div class="text-2xl mb-2">📝</div>
                            <div class="font-semibold">Message Only</div>
                            <div class="text-sm opacity-90">Hanya tampilan pesan</div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Current Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Current Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @livewire('admin.timer-control')
                        </div>
                        <div>
                            @livewire('admin.settings-form')
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
        

        
        // Show mode change notification
        function showModeChangeNotification(mode) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity';
            notification.textContent = `Switched to ${mode === 'timer' ? 'Timer' : 'Message'} Mode`;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
        
        // Load current mode on page load
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
</x-app-layout>