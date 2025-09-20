<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;

class ActiveMessageWidget extends Component
{
    public $activeMessage;
    public $backgroundColor = '#1f2937';
    public $fontColor = '#ffffff';

    public function mount()
    {
        $this->loadActiveMessage();
        $this->loadDisplaySettings();
    }

    public function loadActiveMessage()
    {
        $this->activeMessage = Cache::remember('active_message', 60, function () {
            return Message::where('is_active', true)->first();
        });
    }

    public function loadDisplaySettings()
    {
        $settings = Cache::remember('message_display_settings', 300, function () {
            return Setting::whereIn('key', ['message_background_color', 'message_font_color'])
                ->pluck('value', 'key')
                ->toArray();
        });

        $this->backgroundColor = $settings['message_background_color'] ?? '#1f2937';
        $this->fontColor = $settings['message_font_color'] ?? '#ffffff';
    }

    public function deactivateMessage()
    {
        if ($this->activeMessage) {
            $this->activeMessage->update(['is_active' => false]);
            
            // Clear cache
            Cache::forget('active_message');
            
            // Reload data
            $this->loadActiveMessage();
            
            // Dispatch event untuk update komponen lain
            $this->dispatch('message-deactivated');
            
            // Show success message
            session()->flash('success', 'Pesan berhasil dinonaktifkan');
        }
    }

    public function refreshMessage()
    {
        // Clear cache dan reload data
        Cache::forget('active_message');
        Cache::forget('message_display_settings');
        
        $this->loadActiveMessage();
        $this->loadDisplaySettings();
        
        session()->flash('success', 'Data pesan berhasil diperbarui');
    }

    #[On('message-activated')]
    public function onMessageActivated()
    {
        Cache::forget('active_message');
        $this->loadActiveMessage();
    }

    #[On('message-deactivated')]
    public function onMessageDeactivated()
    {
        Cache::forget('active_message');
        $this->loadActiveMessage();
    }

    #[On('message-updated')]
    public function onMessageUpdated()
    {
        Cache::forget('active_message');
        $this->loadActiveMessage();
    }

    #[On('settings-updated')]
    public function onSettingsUpdated()
    {
        Cache::forget('message_display_settings');
        $this->loadDisplaySettings();
    }

    public function render()
    {
        return view('livewire.admin.active-message-widget');
    }
}