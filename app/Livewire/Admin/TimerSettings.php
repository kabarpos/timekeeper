<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;
use App\Models\Timer;
use Livewire\Attributes\On;

class TimerSettings extends Component
{
    public $duration_minutes = 5;
    public $timer_bg_color = '#000000';
    public $timer_font_color = '#ffffff';
    
    public function mount()
    {
        $this->loadData();
    }
    
    public function loadData()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->timer_bg_color = $setting->timer_bg_color ?? '#000000';
            $this->timer_font_color = $setting->timer_font_color ?? '#ffffff';
        }
        
        $timer = Timer::latest()->first();
        if ($timer) {
            $this->duration_minutes = intval($timer->duration_seconds / 60);
        }
    }
    
    public function save()
    {
        $this->validate([
            'duration_minutes' => 'required|integer|min:1|max:180',
            'timer_bg_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'timer_font_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);
        
        // Update setting
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }
        
        $setting->timer_bg_color = $this->timer_bg_color;
        $setting->timer_font_color = $this->timer_font_color;
        $setting->save();
        
        // Update timer duration
        $duration_seconds = $this->duration_minutes * 60;
        $timer = Timer::latest()->first();
        
        if (!$timer) {
            Timer::create([
                'duration_seconds' => $duration_seconds,
                'remaining_seconds' => $duration_seconds,
                'status' => 'stopped'
            ]);
        } else {
            $timer->update([
                'duration_seconds' => $duration_seconds,
                'remaining_seconds' => $duration_seconds
            ]);
        }
        
        session()->flash('message', 'Pengaturan timer berhasil disimpan!');
        
        // Broadcast events
        $this->dispatch('timer-updated');
        $this->dispatch('color-updated');
        $this->dispatch('timer-settings-updated')->to('admin.timer-control');
    }
    
    public function resetToDefault()
    {
        $this->duration_minutes = 5;
        $this->timer_bg_color = '#000000';
        $this->timer_font_color = '#ffffff';
        
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }
        
        $setting->timer_bg_color = $this->timer_bg_color;
        $setting->timer_font_color = $this->timer_font_color;
        $setting->save();
        
        // Reset timer
        $duration_seconds = $this->duration_minutes * 60;
        $timer = Timer::latest()->first();
        
        if (!$timer) {
            Timer::create([
                'duration_seconds' => $duration_seconds,
                'remaining_seconds' => $duration_seconds,
                'status' => 'stopped'
            ]);
        } else {
            $timer->update([
                'duration_seconds' => $duration_seconds,
                'remaining_seconds' => $duration_seconds
            ]);
        }
        
        session()->flash('message', 'Pengaturan timer berhasil direset ke default!');
        
        // Broadcast events
        $this->dispatch('timer-updated');
        $this->dispatch('color-updated');
    }
    
    public function getPreviewStyleProperty()
    {
        return "background-color: {$this->timer_bg_color}; color: {$this->timer_font_color};";
    }
    
    #[On('timer-updated')]
    public function refreshTimer()
    {
        $this->loadData();
    }
    
    public function render()
    {
        return view('livewire.admin.timer-settings');
    }
}