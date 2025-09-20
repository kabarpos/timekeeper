<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Test - TimeKeeper</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .test-result { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">🔍 Production Test - TimeKeeper</h1>
        
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">📊 System Status</h2>
            
            <div class="test-result info">
                <strong>✅ Server Status:</strong> Running on {{ request()->getHost() }}:{{ request()->getPort() }}
            </div>
            
            <div class="test-result info">
                <strong>✅ Laravel Version:</strong> {{ app()->version() }}
            </div>
            
            <div class="test-result info">
                <strong>✅ Environment:</strong> {{ app()->environment() }}
            </div>
            
            <div class="test-result info">
                <strong>✅ Database Connection:</strong> 
                @php
                    try {
                        $userCount = \App\Models\User::count();
                        echo "Connected - {$userCount} users in database";
                    } catch (Exception $e) {
                        echo "ERROR: " . $e->getMessage();
                    }
                @endphp
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">🔐 Authentication Routes Test</h2>
            
            <div class="test-result success">
                <strong>✅ Register Route:</strong> 
                <a href="{{ route('register') }}" class="text-blue-600 underline" target="_blank">{{ route('register') }}</a>
            </div>
            
            <div class="test-result success">
                <strong>✅ Login Route:</strong> 
                <a href="{{ route('login') }}" class="text-blue-600 underline" target="_blank">{{ route('login') }}</a>
            </div>
            
            @auth
                <div class="test-result success">
                    <strong>✅ User Authenticated:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
                </div>
                
                <div class="test-result info">
                    <strong>🏠 Admin Dashboard:</strong> 
                    <a href="{{ route('admin.index') }}" class="text-blue-600 underline">Go to Dashboard</a>
                </div>
            @else
                <div class="test-result info">
                    <strong>👤 User Status:</strong> Not authenticated (Guest)
                </div>
            @endauth
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">🎯 Livewire Status</h2>
            
            <div class="test-result info">
                <strong>📦 Livewire Scripts:</strong> Loading...
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const livewireStatus = document.getElementById('livewire-status');
                        
                        setTimeout(() => {
                            if (typeof Livewire !== 'undefined') {
                                livewireStatus.innerHTML = '<span class="text-green-600">✅ Livewire Loaded Successfully</span>';
                                livewireStatus.className = 'test-result success';
                            } else {
                                livewireStatus.innerHTML = '<span class="text-red-600">❌ Livewire NOT Loaded</span>';
                                livewireStatus.className = 'test-result error';
                            }
                        }, 1000);
                    });
                </script>
                <div id="livewire-status" class="test-result info">Checking Livewire...</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">🧪 Quick Tests</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border p-4 rounded">
                    <h3 class="font-semibold mb-2">Register Test</h3>
                    <p class="text-sm text-gray-600 mb-2">Test form register dengan data dummy</p>
                    <a href="{{ route('register') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Test Register
                    </a>
                </div>
                
                <div class="border p-4 rounded">
                    <h3 class="font-semibold mb-2">Login Test</h3>
                    <p class="text-sm text-gray-600 mb-2">Test form login dengan user existing</p>
                    <a href="{{ route('login') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Test Login
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">🔧 Debug Information</h2>
            
            <div class="text-sm">
                <div class="test-result info">
                    <strong>Request URL:</strong> {{ request()->fullUrl() }}
                </div>
                
                <div class="test-result info">
                    <strong>User Agent:</strong> {{ request()->userAgent() }}
                </div>
                
                <div class="test-result info">
                    <strong>Session ID:</strong> {{ session()->getId() }}
                </div>
                
                <div class="test-result info">
                    <strong>CSRF Token:</strong> {{ csrf_token() }}
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    
    <script>
        // Additional JavaScript checks
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔍 Production Test Page Loaded');
            console.log('✅ jQuery available:', typeof $ !== 'undefined');
            console.log('✅ Livewire available:', typeof Livewire !== 'undefined');
            
            // Check for any JavaScript errors
            window.addEventListener('error', function(e) {
                console.error('❌ JavaScript Error:', e.error);
            });
        });
    </script>
</body>
</html>