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
        // Polling untuk memeriksa perubahan display mode setiap 10 detik (dikurangi frekuensi)
        let currentMode = '{{ $setting->display_mode }}';
        setInterval(function() {
            fetch('/api/current-display-mode')
                .then(response => response.json())
                .then(data => {
                    if (data.mode !== currentMode) {
                        console.log('Display mode changed from', currentMode, 'to', data.mode);
                        currentMode = data.mode;
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error checking display mode:', error);
                });
        }, 10000); // Dikurangi dari 5000ms ke 10000ms
        
        // Listen untuk display mode changes dan refresh halaman
        document.addEventListener('livewire:init', () => {
            Livewire.on('display-mode-changed', (event) => {
                // Refresh halaman untuk menampilkan komponen yang benar
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            });
        });
        
        // Listen untuk global custom event dari admin
        window.addEventListener('display-mode-changed', (event) => {
            console.log('Display mode changed to:', event.detail.mode);
            // Refresh halaman untuk menampilkan komponen yang benar
            setTimeout(() => {
                window.location.reload();
            }, 100);
        });
        
        // Listen for force reload events via polling - dikurangi frekuensi
        let lastForceReloadCheck = Date.now();
        setInterval(function() {
            fetch('/api/force-reload-status')
                .then(response => response.json())
                .then(data => {
                    if (data.timestamp > lastForceReloadCheck) {
                        console.log('Force reload triggered from admin');
                        lastForceReloadCheck = data.timestamp;
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error checking force reload status:', error);
                });
        }, 5000); // Dikurangi dari 3000ms ke 5000ms
        
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