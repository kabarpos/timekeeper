<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get current display mode
Route::get('/current-display-mode', function () {
    $setting = Setting::current();
    return response()->json([
        'mode' => $setting->display_mode,
        'bg_color' => $setting->bg_color,
        'font_color' => $setting->font_color
    ]);
});

Route::middleware(['web'])->post('/force-reload-display', function () {
    // Store timestamp in cache for polling mechanism
    Cache::put('force_reload_timestamp', time(), 60);
    
    // Also broadcast event for future WebSocket implementation
    event(new App\Events\DisplayForceReload());
    
    return response()->json([
        'success' => true,
        'message' => 'Display page reload triggered'
    ])->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'POST')
      ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN');
});

// Get current timer status
Route::get('/current-timer-status', function () {
    $timer = \App\Models\Timer::latest()->first();
    
    if (!$timer) {
        return response()->json([
            'status' => 'no_timer',
            'message' => 'No timer found'
        ]);
    }
    
    // Hitung remaining_seconds real-time jika timer sedang berjalan
    $remaining_seconds = $timer->remaining_seconds;
    if ($timer->isRunning() && $timer->started_at) {
        $startTimestamp = $timer->started_at->timestamp;
        $currentTimestamp = now()->timestamp;
        $elapsed = max(0, $currentTimestamp - $startTimestamp);
        $remaining_seconds = max(0, $timer->duration_seconds - $elapsed);
    }
    
    // Format waktu
    $minutes = floor($remaining_seconds / 60);
    $seconds = $remaining_seconds % 60;
    $formatted_time = sprintf('%02d:%02d', $minutes, $seconds);
    
    return response()->json([
        'id' => $timer->id,
        'status' => $timer->status,
        'duration_seconds' => $timer->duration_seconds,
        'remaining_seconds' => $remaining_seconds,
        'formatted_time' => $formatted_time,
        'started_at' => $timer->started_at,
        'ended_at' => $timer->ended_at
    ]);
});

// Force reload status endpoint for polling
Route::get('/force-reload-status', function () {
    $timestamp = Cache::get('force_reload_timestamp', 0);
    
    return response()->json([
        'timestamp' => $timestamp * 1000 // Convert to milliseconds for JavaScript
    ])->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET')
      ->header('Access-Control-Allow-Headers', 'Content-Type');
});