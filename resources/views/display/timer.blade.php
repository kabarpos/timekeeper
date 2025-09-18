<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TimeKeeper - Timer Display</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-manrope">
    <div id="timer-display" class="min-h-screen">
        @livewire('display-timer')
    </div>
    
    @livewireScripts
    
    <script>
        window.addEventListener('force-reload', function(event) {
            // Force reload triggered from admin
            location.reload();
        });
        // Listen for force reload events via polling - dikurangi frekuensi
        let lastForceReloadCheck = Date.now();
        setInterval(function() {
            fetch('/api/force-reload-status')
                .then(response => response.json())
                .then(data => {
                    if (data.timestamp > lastForceReloadCheck) {
                        lastForceReloadCheck = data.timestamp;
                        window.location.reload();
                    }
                })
                .catch(error => {
                    // Error checking force reload status
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