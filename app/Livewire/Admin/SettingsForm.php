<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

class SettingsForm extends Component
{
    #[Validate('required|string|regex:/^#[0-9A-Fa-f]{6}$/')]
    public $bg_color = '#000000';
    
    #[Validate('required|string|regex:/^#[0-9A-Fa-f]{6}$/')]
    public $font_color = '#ffffff';
    
    // Tidak menggunakan properti publik untuk Model karena akan conflict dengan validasi
    private $setting;
    
    public function mount()
    {
        $this->setting = Setting::current();
        $this->bg_color = $this->setting->bg_color;
        $this->font_color = $this->setting->font_color;
    }
    
    private function getSetting()
    {
        if (!$this->setting) {
            $this->setting = Setting::current();
        }
        return $this->setting;
    }
    
    public function save()
    {
        $this->validate();
        
        $this->getSetting()->update([
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color
        ]);
        
        // Broadcast events untuk real-time sync
        $this->dispatch('settings-updated', [
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color
        ])->to('display-timer');
        
        $this->dispatch('settings-updated', [
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color
        ])->to('display-message');
        
        session()->flash('message', 'Pengaturan warna berhasil disimpan!');
    }
    
    public function resetToDefault()
    {
        $this->bg_color = '#000000';
        $this->font_color = '#ffffff';
        
        $this->getSetting()->update([
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color
        ]);
        
        // Broadcast events untuk real-time sync
        $this->dispatch('settings-updated', [
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color
        ])->to('display-timer');
        
        $this->dispatch('settings-updated', [
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color
        ])->to('display-message');
        
        session()->flash('message', 'Pengaturan warna berhasil direset ke default!');
    }
    
    #[On('switch-to-timer')]
    public function switchToTimer()
    {
        $this->switchMode('timer');
    }
    
    #[On('switch-to-message')]
    public function switchToMessage()
    {
        $this->switchMode('message');
    }
    
    public function switchMode($mode)
    {
        $setting = $this->getSetting();
        $setting->update([
            'display_mode' => $mode
        ]);
        
        // Broadcast events untuk real-time sync ke semua komponen
        $this->dispatch('display-mode-changed', ['mode' => $mode]);
        
        // Kirim event global untuk auto-reload halaman display
        $this->js('window.dispatchEvent(new CustomEvent("display-mode-changed", { detail: { mode: "' . $mode . '" } }))');
        
        $this->dispatch('settings-updated', [
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color,
            'display_mode' => $mode
        ])->to('display-timer');
        
        $this->dispatch('settings-updated', [
            'bg_color' => $this->bg_color,
            'font_color' => $this->font_color,
            'display_mode' => $mode
        ])->to('display-message');
        
        session()->flash('message', 'Mode display berhasil diubah ke ' . ucfirst($mode) . '!');
    }
    
    public function getPreviewStyleProperty()
    {
        return "background-color: {$this->bg_color}; color: {$this->font_color};";
    }
    
    public function render()
    {
        $setting = $this->getSetting();
        return view('livewire.admin.settings-form', [
            'setting' => $setting,
            'display_mode' => $setting->display_mode
        ]);
    }
}
