<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes - Display untuk pembicara
Route::get('/', function () {
    return view('display.index');
})->name('display.index');

Route::get('/display', function () {
    return view('display.index');
})->name('display');

Route::get('/timer', function () {
    return view('display.timer');
})->name('display.timer');

Route::get('/message', function () {
    return view('display.message');
})->name('display.message');

// Admin routes - Protected dengan auth middleware
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return view('admin.index');
        })->name('index');
        
        Route::get('/timer', function () {
            return view('admin.timer');
        })->name('timer');
        
        Route::get('/messages', function () {
            return view('admin.messages');
        })->name('messages');
        
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
