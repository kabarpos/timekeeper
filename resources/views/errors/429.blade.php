<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Sedang Sibuk - TimeKeeper</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-xl p-8 text-center border border-gray-200">
        <div class="mb-6">
            <div class="mx-auto w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Sistem Sedang Sibuk</h1>
            <p class="text-gray-600 leading-relaxed">Maaf, sistem sedang mengalami banyak permintaan. Mohon tunggu sebentar dan coba lagi.</p>
        </div>
        
        <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
            <p class="text-sm text-gray-700">
                <strong class="text-blue-800">Coba lagi dalam:</strong> 
                <span id="countdown" class="font-mono text-xl text-blue-600 font-bold">{{ $retry_after ?? 30 }}</span> 
                <span class="text-blue-800">detik</span>
            </p>
        
        <div class="space-y-3">
            <button onclick="window.location.reload()" 
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-md hover:shadow-lg"
                    id="refresh-btn" disabled>
                <span class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Muat Ulang Halaman
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
        </div>
    </div>

    <script>
        let countdown = {{ $retry_after ?? 30 }};
        const countdownElement = document.getElementById('countdown');
        const refreshBtn = document.getElementById('refresh-btn');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                refreshBtn.disabled = false;
                refreshBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                refreshBtn.classList.add('animate-pulse');
                countdownElement.textContent = '0';
                countdownElement.parentElement.innerHTML = '<span class="text-green-600 font-semibold">✓ Siap untuk dicoba lagi!</span>';
                
                // Auto refresh setelah countdown selesai
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        }, 1000);
        
        // Disable refresh button initially
        refreshBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Add some visual feedback
        refreshBtn.addEventListener('click', function() {
            if (!this.disabled) {
                this.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memuat...</span>';
            }
        });
    </script>
</body>
</html>