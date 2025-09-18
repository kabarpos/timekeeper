<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;

class SettingsForm extends Component
{
    public $display_mode;
    public $recent_messages;
    
    // Tidak menggunakan properti publik untuk Model karena akan conflict dengan validasi
    private $setting;
    
    public function mount()
    {
        $this->setting = Setting::current();
        $this->loadData();
    }
    
    private function loadData()
    {
        // Load current display mode
        $setting = $this->getSetting();
        $this->display_mode = $setting->display_mode;
        
        // Load 5 recent messages
        $this->recent_messages = Message::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function getSetting()
    {
        if (!$this->setting) {
            $this->setting = Setting::current();
        }
        return $this->setting;
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
        
        // Update local property
        $this->display_mode = $mode;
        
        // Broadcast events untuk real-time sync ke semua komponen
        $this->dispatch('display-mode-changed', ['mode' => $mode]);
        
        // Kirim event global untuk auto-reload halaman display
        $this->js('window.dispatchEvent(new CustomEvent("display-mode-changed", { detail: { mode: "' . $mode . '" } }))');
        
        $this->dispatch('settings-updated', [
            'display_mode' => $mode
        ])->to('display-timer');
        
        $this->dispatch('settings-updated', [
            'display_mode' => $mode
        ])->to('display-message');
        
        session()->flash('message', 'Mode display berhasil diubah ke ' . ucfirst($mode) . '!');
    }
    
    #[On('message-updated')]
    public function refreshMessages()
    {
        $this->loadData();
    }
    
    public function toggleMessageStatus($messageId)
    {
        $message = Message::findOrFail($messageId);
        $message->update([
            'is_active' => !$message->is_active
        ]);
        
        // Refresh data untuk update UI
        $this->loadData();
        
        // Broadcast event untuk real-time sync ke display components
        $this->dispatch('message-status-changed', [
            'message_id' => $messageId,
            'is_active' => $message->is_active
        ]);
        
        $status = $message->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "Pesan '{$message->title}' berhasil {$status}!");
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
