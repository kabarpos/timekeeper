<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan Server - TimeKeeper</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-xl p-8 text-center border border-gray-200">
        <div class="mb-6">
            <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Terjadi Kesalahan Server</h1>
            <p class="text-gray-600 leading-relaxed">Maaf, terjadi kesalahan internal pada server. Tim kami telah diberitahu dan sedang memperbaikinya.</p>
        </div>
        
        <div class="bg-red-50 rounded-lg p-4 mb-6 border border-red-200">
            <p class="text-sm text-red-700">
                <strong class="text-red-800">Kode Error:</strong> 
                <span class="font-mono text-lg text-red-600 font-bold">500</span>
            </p>
            <p class="text-xs text-red-600 mt-1">Internal Server Error</p>
        </div>
        
        <div class="space-y-3">
            <button onclick="window.location.reload()" 
                    class="w-full bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-all duration-200 font-medium shadow-md hover:shadow-lg">
                <span class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Coba Lagi
                </span>
            </button>
            
            <a href="{{ url('/') }}" 
               class="block w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium border border-gray-300">
                <span class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Kembali ke Beranda
                </span>
            </a>
            
            @if(config('app.debug'))
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-left">
                <p class="text-xs text-yellow-800 font-semibold mb-1">Debug Info:</p>
                <p class="text-xs text-yellow-700 font-mono">{{ $exception->getMessage() ?? 'No exception message available' }}</p>
            </div>
            @endif
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Jika masalah terus berlanjut, silakan hubungi administrator sistem.
            </p>
        </div>
    </div>

    <script>
        // Add some visual feedback for the retry button
        document.querySelector('button[onclick="window.location.reload()"]').addEventListener('click', function() {
            this.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memuat Ulang...</span>';
        });
        
        // Auto-hide debug info after 10 seconds in production
        @if(config('app.debug'))
        setTimeout(() => {
            const debugInfo = document.querySelector('.bg-yellow-50');
            if (debugInfo) {
                debugInfo.style.transition = 'opacity 0.5s ease-out';
                debugInfo.style.opacity = '0';
                setTimeout(() => debugInfo.remove(), 500);
            }
        }, 10000);
        @endif
    </script>
</body>
</html>