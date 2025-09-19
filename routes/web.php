<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;

// Public routes - Display untuk pembicara
Route::get('/', function () {
    return view('display.index');
})->name('display.index');

Route::get('/display', function () {
    return view('display.index');
})->name('display');

// Health check routes
Route::prefix('health')->group(function () {
    Route::get('/', [HealthController::class, 'check'])->name('health.check');
    Route::get('/alive', [HealthController::class, 'alive'])->name('health.alive');
    Route::get('/ready', [HealthController::class, 'ready'])->name('health.ready');
    Route::get('/metrics', [HealthController::class, 'metrics'])->name('health.metrics');
});

Route::get('/timer', function () {
    return view('display.timer');
})->name('display.timer');

Route::get('/message', function () {
    return view('display.message');
})->name('display.message');

// Test route untuk debugging CSS
Route::get('/test-tailwind', function () {
    return view('test-tailwind');
})->name('test.tailwind');

// Debug routes untuk production troubleshooting
Route::post('/debug-form-test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Form submission working correctly',
        'method' => request()->method(),
        'data' => request()->all(),
        'headers' => [
            'csrf' => request()->header('X-CSRF-TOKEN'),
            'ajax' => request()->header('X-Requested-With'),
        ]
    ]);
})->name('debug.form.test');

// Security middleware untuk mencegah sensitive data di URL
Route::middleware(['web'])->group(function () {
    Route::get('/register', function () {
        // Block jika ada sensitive data di URL
        if (request()->has(['password', 'password_confirmation', 'email'])) {
            // Log security incident
            \Log::warning('Security: Sensitive data in URL', [
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Redirect ke clean URL
            return redirect()->route('register')->with('error', 'Invalid request. Please try again.');
        }
        
        return app(\Livewire\Volt\Volt::class)->route('pages.auth.register');
    })->name('register');
    
    Route::get('/login', function () {
        // Block jika ada sensitive data di URL
        if (request()->has(['password', 'email'])) {
            // Log security incident
            \Log::warning('Security: Sensitive data in URL', [
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Redirect ke clean URL
            return redirect()->route('login')->with('error', 'Invalid request. Please try again.');
        }
        
        return app(\Livewire\Volt\Volt::class)->route('pages.auth.login');
    })->name('login');
});

// Admin routes - Protected dengan auth middleware
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect dashboard to admin
    Route::get('/dashboard', function () {
        return redirect()->route('admin.index');
    })->name('dashboard');
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return view('admin.index');
        })->name('index');
        
        Route::get('/monitoring', function () {
            return view('admin.monitoring');
        })->name('monitoring');
        
        Route::get('/timer', function () {
            return view('admin.timer');
        })->name('timer');
        
        Route::get('/messages', function () {
            return view('admin.messages');
        })->name('messages');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
