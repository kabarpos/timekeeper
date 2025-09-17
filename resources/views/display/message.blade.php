<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TimeKeeper - Message Display</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    <div id="message-display" class="min-h-screen">
        @livewire('display-message')
    </div>
    
    @livewireScripts
    
    <script>
        // Auto refresh setiap 10 detik untuk message
        setInterval(function() {
            Livewire.dispatch('refresh-display');
        }, 10000);
        
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