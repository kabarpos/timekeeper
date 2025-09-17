<?php

namespace App\Livewire\Admin;

use App\Models\Timer;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\On;

class TimerControl extends Component
{
    public $duration_minutes = 5;
    
    // Tidak menggunakan properti publik untuk Model karena akan conflict dengan validasi
    private $timer;
    private $current_timer;

    public function mount()
    {
        $this->current_timer = Timer::latest()->first();
        if (!$this->current_timer) {
            $this->current_timer = Timer::create([
                'duration_seconds' => 300, // 5 minutes default
                'remaining_seconds' => 300,
                'status' => 'stopped'
            ]);
        }
    }
    
    private function getCurrentTimer()
    {
        if (!$this->current_timer) {
            $this->current_timer = Timer::latest()->first();
            if (!$this->current_timer) {
                $this->current_timer = Timer::create([
                    'duration_seconds' => 300, // 5 minutes default
                    'remaining_seconds' => 300,
                    'status' => 'stopped'
                ]);
            }
        }
        return $this->current_timer;
    }

    public function startTimer()
    {
        $timer = $this->getCurrentTimer();
        
        // Jika timer baru dimulai (bukan resume dari pause)
        if ($timer->status === 'stopped') {
            $timer->update([
                'status' => 'running',
                'started_at' => now()
            ]);
        } else {
            // Jika resume dari pause, sesuaikan started_at berdasarkan remaining_seconds
            // Hitung berapa detik yang sudah berlalu dari durasi awal
            $elapsedSeconds = $timer->duration_seconds - $timer->remaining_seconds;
            $timer->update([
                'status' => 'running',
                'started_at' => now()->subSeconds($elapsedSeconds)
            ]);
        }
        
        // Broadcast to display components
        $this->dispatch('timer-started')->to('display-timer');
        $this->dispatch('timer-status-changed', [
            'status' => 'running',
            'remaining_seconds' => $timer->remaining_seconds,
            'duration_seconds' => $timer->duration_seconds
        ])->to('display-timer');
        
        session()->flash('success', 'Timer dimulai!');
    }

    public function pauseTimer()
    {
        $timer = $this->getCurrentTimer();
        
        // Jika timer sedang running, hitung remaining_seconds berdasarkan waktu yang telah berlalu
        if ($timer->isRunning() && $timer->started_at) {
            $elapsed = now()->diffInSeconds($timer->started_at);
            $newRemaining = max(0, $timer->duration_seconds - $elapsed);
            
            $timer->update([
                'status' => 'paused',
                'remaining_seconds' => $newRemaining
            ]);
        } else {
            $timer->update([
                'status' => 'paused'
            ]);
        }
        
        // Broadcast events untuk real-time sync
        $this->dispatch('timer-paused')->to('display-timer');
        $this->dispatch('timer-status-changed', [
            'status' => 'paused',
            'remaining_seconds' => $timer->remaining_seconds,
            'duration_seconds' => $timer->duration_seconds
        ])->to('display-timer');
        
        session()->flash('success', 'Timer berhasil dijeda!');
    }

    public function resetTimer()
    {
        $timer = $this->getCurrentTimer();
        $timer->update([
            'remaining_seconds' => $timer->duration_seconds,
            'status' => 'stopped',
            'started_at' => null,
            'ended_at' => null
        ]);
        
        // Broadcast events untuk real-time sync
        $this->dispatch('timer-reset')->to('display-timer');
        $this->dispatch('timer-status-changed', [
            'status' => 'stopped',
            'remaining_seconds' => $timer->remaining_seconds,
            'duration_seconds' => $timer->duration_seconds
        ])->to('display-timer');
        
        session()->flash('success', 'Timer berhasil direset!');
    }

    public function setDuration()
    {
        $duration_seconds = $this->duration_minutes * 60;
        
        $timer = $this->getCurrentTimer();
        $timer->update([
            'duration_seconds' => $duration_seconds,
            'remaining_seconds' => $duration_seconds,
            'status' => 'stopped',
            'started_at' => null,
            'ended_at' => null
        ]);
        
        // Broadcast events untuk real-time sync
        $this->dispatch('timer-duration-set')->to('display-timer');
        $this->dispatch('timer-status-changed', [
            'status' => 'stopped',
            'remaining_seconds' => $duration_seconds,
            'duration_seconds' => $duration_seconds
        ])->to('display-timer');
        
        session()->flash('success', "Durasi timer berhasil diset ke {$this->duration_minutes} menit!");
    }





    public function render()
    {
        return view('livewire.admin.timer-control', [
            'timer' => $this->getCurrentTimer()
        ]);
    }
}
