<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TimeKeeper - Message Display</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-manrope">
    <div id="message-display" class="min-h-screen">
        @livewire('display-message')
    </div>
    
    @livewireScripts
    
    <script>
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