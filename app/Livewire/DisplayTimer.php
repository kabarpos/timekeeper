<?php

namespace App\Livewire;

use App\Models\Timer;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\On;

class DisplayTimer extends Component
{
    // Hindari properti publik untuk model Eloquent karena menyebabkan error array_merge() saat validasi
    private $timer;
    private $setting;
    public $minutes = 0;
    public $seconds = 0;
    public $is_warning = false;
    public $formatted_time = '00:00';
    public $status_text = 'Timer Siap';
    public $background_color = '#000000';
    public $font_color = '#ffffff';
    
    public function mount()
    {
        $this->loadData();
        if ($this->getTimer()) {
            $this->updateTimeDisplay();
        }
    }
    
    private function getTimer()
    {
        if (!$this->timer) {
            $this->timer = Timer::latest()->first();
            if (!$this->timer) {
                $this->timer = Timer::create([
                    'duration_seconds' => 300,
                    'remaining_seconds' => 300,
                    'status' => 'stopped'
                ]);
            }
        }
        return $this->timer;
    }
    
    private function getSetting()
    {
        if (!$this->setting) {
            $this->setting = Setting::first() ?? Setting::create([
                'bg_color' => '#000000',
                'font_color' => '#ffffff',
                'display_mode' => 'timer'
            ]);
        }
        return $this->setting;
    }
    
    public function loadData()
    {
        $this->timer = Timer::latest()->first();
        $this->setting = Setting::current();
        
        if (!$this->timer) {
            $this->timer = Timer::create([
                'duration_seconds' => 300,
                'remaining_seconds' => 300,
                'status' => 'stopped'
            ]);
        }
        
        // Load colors from settings
        if ($this->setting) {
            $this->background_color = $this->setting->bg_color ?? '#000000';
            $this->font_color = $this->setting->font_color ?? '#ffffff';
        }
        
        $this->updateTimeDisplay();
    }
    
    public function updateTimeDisplay()
    {
        $timer = $this->getTimer();
        if (!$timer) {
            $this->minutes = 0;
            $this->seconds = 0;
            $this->is_warning = false;
            $this->formatted_time = '00:00';
            $this->status_text = 'Timer Siap';
            return;
        }
        
        // Jika timer running, hitung remaining_seconds berdasarkan waktu yang telah berlalu
        if ($timer->isRunning() && $timer->started_at) {
            // Gunakan timestamp untuk perhitungan yang lebih akurat
            $startTimestamp = $timer->started_at->timestamp;
            $currentTimestamp = now()->timestamp;
            
            // Hitung elapsed dalam detik, pastikan tidak negatif
            $elapsed = max(0, $currentTimestamp - $startTimestamp);
            
            // Hitung remaining time dengan memastikan tidak melebihi duration
            $newRemaining = max(0, $timer->duration_seconds - $elapsed);
            
            // Jika waktu habis, stop timer dan update database
            if ($newRemaining <= 0) {
                $timer->update([
                    'status' => 'stopped',
                    'remaining_seconds' => 0,
                    'ended_at' => now()
                ]);
                
                // Broadcast timer finished to frontend
                $this->dispatch('timer-finished');
                
                // Also broadcast to admin panel
                $this->dispatch('timer-finished')->to('admin.timer-control');
            }
            
            // Set remaining untuk display tanpa update database
            $timer->remaining_seconds = $newRemaining;
        }
        // Jika timer paused atau stopped, gunakan remaining_seconds yang tersimpan
        elseif ($timer->isPaused() || $timer->isStopped()) {
            // Tidak perlu update remaining_seconds, gunakan nilai yang tersimpan
        }
        
        $remaining = $timer->remaining_seconds;
        $this->minutes = floor($remaining / 60);
        $this->seconds = $remaining % 60;
        
        // Format time display
        $this->formatted_time = sprintf('%02d:%02d', $this->minutes, $this->seconds);
        
        // Set status text based on timer state
        if ($timer->isRunning()) {
            $this->status_text = 'Berjalan';
        } elseif ($timer->isPaused()) {
            $this->status_text = 'Dijeda';
        } elseif ($timer->isStopped()) {
            if ($remaining <= 0) {
                $this->status_text = 'Waktu Habis!';
            } else {
                $this->status_text = 'Timer Siap';
            }
        }
        
        // Warning state when less than 1 minute
        $this->is_warning = $remaining <= 60 && $remaining > 0;
    }
    
    #[On('display-updated')]
    public function refreshDisplay()
    {
        // Force refresh from database
        $this->timer = null;
        $this->setting = null;
        $this->loadData();
    }
    
    #[On('timer-started')]
    public function onTimerStarted()
    {
        $this->loadData();
        $this->updateTimeDisplay();
        
        // Reset notification flags via JavaScript
        $this->dispatch('reset-notifications');
    }

    #[On('timer-paused')]
    public function onTimerPaused()
    {
        $this->loadData();
        $this->updateTimeDisplay();
    }

    #[On('timer-reset')]
    public function onTimerReset()
    {
        $this->loadData();
        $this->updateTimeDisplay();
        
        // Reset notification flags via JavaScript
        $this->dispatch('reset-notifications');
    }

    #[On('timer-finished')]
    public function onTimerFinished()
    {
        $this->loadData();
        $this->updateTimeDisplay();
    }

    #[On('timer-status-changed')]
    public function onTimerStatusChanged($data)
    {
        // Refresh timer data from database
        $this->timer = null; // Force refresh
        $this->loadData();
    }

    #[On('timer-duration-set')]
    public function onTimerDurationSet()
    {
        $this->loadData();
        $this->updateTimeDisplay();
    }

    #[On('settings-updated')]
    public function onSettingsUpdated($data)
    {
        // Refresh setting data from database
        $this->setting = null; // Force refresh
        $this->loadData();
    }

    #[On('display-mode-changed')]
    public function onDisplayModeChanged($data)
    {
        $setting = $this->getSetting();
        $setting->display_mode = $data['mode'];
        $this->loadData();
    }
    
    public function getFormattedTimeProperty()
    {
        return sprintf('%02d.%02d', $this->minutes, $this->seconds);
    }
    
    public function getStatusTextProperty()
    {
        $timer = $this->getTimer();
        if (!$timer) {
            return 'Tidak Diketahui';
        }
        
        return match($timer->status) {
            'running' => 'Berjalan',
            'paused' => 'Dijeda',
            'stopped' => 'Berhenti',
            default => 'Tidak Diketahui'
        };
    }
    
    public function getBackgroundColorProperty()
    {
        $timer = $this->getTimer();
        if ($this->is_warning && $timer->isRunning()) {
            return '#dc2626'; // Red warning color
        }
        
        return $this->getSetting()->bg_color;
    }
    
    public function getFontColorProperty()
    {
        return $this->getSetting()->font_color;
    }
    
    public function render()
    {
        return view('livewire.display-timer', [
            'timer' => $this->getTimer(),
            'setting' => $this->getSetting(),
            'formatted_time' => $this->formatted_time,
            'status_text' => $this->status_text,
            'background_color' => $this->background_color,
            'font_color' => $this->font_color
        ]);
    }
}
