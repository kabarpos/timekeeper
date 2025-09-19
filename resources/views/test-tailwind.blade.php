<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Tailwind CSS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Test Tailwind CSS</h1>
        <p class="text-gray-600 mb-6">Jika Anda dapat melihat styling ini dengan benar, maka Tailwind CSS sudah ter-load.</p>
        
        <div class="space-y-4">
            <div class="bg-red-500 text-white p-4 rounded">
                Background merah dengan teks putih
            </div>
            <div class="bg-green-500 text-white p-4 rounded">
                Background hijau dengan teks putih
            </div>
            <div class="bg-blue-500 text-white p-4 rounded">
                Background biru dengan teks putih
            </div>
        </div>
        
        <button class="mt-6 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-300">
            Button dengan hover effect
        </button>
    </div>
</body>
</html>