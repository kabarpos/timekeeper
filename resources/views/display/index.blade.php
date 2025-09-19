<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TimeKeeper Display</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    <div id="display-container" class="min-h-screen">
        @php
            $setting = App\Models\Setting::current();
        @endphp
        
        @if($setting->display_mode === 'timer')
            @livewire('display-timer')
        @else
            @livewire('display-message')
        @endif
    </div>
    
    @livewireScripts
    
    <script>
        // Polling untuk memeriksa perubahan display mode - optimized interval
        let currentMode = '{{ $setting->display_mode }}';
        let displayModePollingInterval = 5000; // 5 seconds untuk responsivitas yang lebih baik
        let displayModeErrors = 0;
        
        function checkDisplayMode() {
            fetch('/api/current-display-mode')
                .then(response => response.json())
                .then(data => {
                    displayModeErrors = 0; // Reset error counter
                    if (data.mode !== currentMode) {
                        console.log('Display mode changed from', currentMode, 'to', data.mode);
                        currentMode = data.mode;
                        updateDisplay(data.mode);
                    }
                })
                .catch(error => {
                    displayModeErrors++;
                    console.log('Display mode polling error:', error);
                    // Reduce frequency on errors
                    if (displayModeErrors > 3) {
                        displayModePollingInterval = 15000; // 15 seconds on repeated errors
                    }
                });
        }
        
        // Initial check
        checkDisplayMode();
        
        // Set up polling
        setInterval(checkDisplayMode, displayModePollingInterval);
        
        // Listen untuk display mode changes dan refresh halaman
        document.addEventListener('livewire:init', () => {
            Livewire.on('display-mode-changed', (event) => {
                // Refresh halaman untuk menampilkan komponen yang benar
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            });
        });
        
        // Fungsi untuk update display berdasarkan mode
        function updateDisplay(mode) {
            // Reload halaman untuk memuat komponen yang sesuai dengan mode baru
            window.location.reload();
        }
        
        // Listen untuk global custom event dari admin
        window.addEventListener('display-mode-changed', (event) => {
            // Display mode changed event
            currentMode = event.detail.mode;
            updateDisplay(currentMode);
        });
        
        // Listen for force reload events via polling - optimized interval
        let lastForceReloadCheck = Date.now();
        let pollingInterval = 15000; // 15 seconds default
        let consecutiveErrors = 0;
        
        function checkForceReload() {
            fetch('/api/force-reload-status')
                .then(response => response.json())
                .then(data => {
                    consecutiveErrors = 0; // Reset error counter
                    pollingInterval = 15000; // Reset to normal interval
                    
                    if (data.timestamp > lastForceReloadCheck) {
                        lastForceReloadCheck = data.timestamp;
                        window.location.reload();
                    }
                })
                .catch(error => {
                    consecutiveErrors++;
                    // Exponential backoff on errors, max 60 seconds
                    pollingInterval = Math.min(60000, pollingInterval * 1.5);
                });
        }
        
        // Initial check
        checkForceReload();
        
        // Set up adaptive polling
        setInterval(checkForceReload, pollingInterval);
        
        // Fullscreen toggle dengan F11
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F11') {
                e.preventDefault();
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }
        });
    </script>
</body>
</html>