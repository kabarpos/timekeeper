<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Setting;

class MessageColorSettings extends Component
{
    public $bg_color;
    public $font_color;
    
    public function mount()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->bg_color = $setting->bg_color;
            $this->font_color = $setting->font_color;
        } else {
            $this->bg_color = '#000000';
            $this->font_color = '#ffffff';
        }
    }
    
    public function save()
    {
        $this->validate([
            'bg_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'font_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);
        
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }
        
        $setting->bg_color = $this->bg_color;
        $setting->font_color = $this->font_color;
        $setting->save();
        
        session()->flash('message', 'Pengaturan warna message berhasil disimpan!');
        
        // Broadcast event untuk update display
        $this->dispatch('color-updated');
    }
    
    public function resetToDefault()
    {
        $this->bg_color = '#000000';
        $this->font_color = '#ffffff';
        
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }
        
        $setting->bg_color = $this->bg_color;
        $setting->font_color = $this->font_color;
        $setting->save();
        
        session()->flash('message', 'Pengaturan warna berhasil direset ke default!');
        
        // Broadcast event untuk update display
        $this->dispatch('color-updated');
    }
    
    public function getPreviewStyleProperty()
    {
        return "background-color: {$this->bg_color}; color: {$this->font_color};";
    }
    
    public function render()
    {
        return view('livewire.admin.message-color-settings');
    }
}