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
        // Auto refresh setiap 1 detik untuk timer
        setInterval(function() {
            Livewire.dispatch('display-updated');
        }, 1000);
        
        // Listen for force reload events via polling
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
        }, 1000);
        
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