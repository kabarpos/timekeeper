<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\On;

class DisplayMessage extends Component
{
    // Hindari properti publik untuk model Eloquent karena menyebabkan error array_merge() saat validasi
    private $message;
    private $setting;
    public $has_active_message = false;
    
    public function mount()
    {
        $this->loadData();
    }
    
    public function getMessage()
    {
        if (!$this->message) {
            $this->message = Message::where('is_active', true)->first();
        }
        return $this->message;
    }
    
    public function getSetting()
    {
        if (!$this->setting) {
            $this->setting = Setting::current();
        }
        return $this->setting;
    }
    
    public function loadData()
    {
        // Refresh data dan clear cache untuk computed properties
        $this->message = Message::where('is_active', true)->first();
        $this->setting = Setting::current();
        $this->has_active_message = $this->message !== null;
        
        // Force refresh computed properties dengan cara yang benar
        if (property_exists($this, 'computedPropertyCache')) {
            $this->computedPropertyCache = [];
        }
        
        // Force re-render untuk memastikan perubahan warna terlihat
        $this->skipRender = false;
    }
    
    #[On('display-updated')]
    public function refreshDisplay()
    {
        $this->loadData();
    }
    
    #[On('settings-updated')]
    public function onSettingsUpdated($data = null)
    {
        if ($data && isset($data['type']) && $data['type'] === 'message_colors') {
            // Clear all cached data untuk message colors
            $this->setting = null;
            $this->message = null;
            
            // Force clear computed property cache
            if (property_exists($this, 'computedPropertyCache')) {
                $this->computedPropertyCache = [];
            }
            
            // Reload data dan force re-render
            $this->loadData();
            $this->skipRender = false;
        } else {
            $this->loadData();
        }
    }

    #[On('message-updated')]
    public function onMessageUpdated()
    {
        $this->loadData();
    }

    #[On('message-activated')]
    public function onMessageActivated($data)
    {
        // Update message data secara real-time
        $this->loadData();
        $this->has_active_message = true;
    }

    #[On('message-deleted')]
    public function onMessageDeleted($data)
    {
        if ($data['was_active']) {
            $this->has_active_message = false;
            $this->message = null;
        }
        $this->loadData();
    }

    #[On('messages-changed')]
    public function onMessagesChanged()
    {
        $this->loadData();
    }

    #[On('message-status-changed')]
    public function onMessageStatusChanged($data)
    {
        // Reload data ketika status message berubah
        $this->loadData();
        
        // Jika message yang sedang aktif dinonaktifkan
        if (!$data['is_active'] && $this->message && $this->message->id == $data['message_id']) {
            $this->has_active_message = false;
            $this->message = null;
        }
    }
    
    #[On('display-mode-changed')]
    public function onDisplayModeChanged($data)
    {
        $setting = $this->getSetting();
        $setting->display_mode = $data['mode'];
        $this->loadData();
    }
    
    public function getBackgroundColorProperty()
    {
        // Prioritas: Warna dari pesan aktif (jika bukan default), lalu fallback ke settings message
        $message = $this->getMessage();
        if ($message && !empty($message->bg_color) && $message->bg_color !== '#000000') {
            return $message->bg_color;
        }
        
        // Fallback ke warna message di settings (bukan timer)
        return $this->getSetting()->bg_color ?? '#000000';
    }
    
    public function getFontColorProperty()
    {
        // Prioritas: Warna dari pesan aktif (jika bukan default), lalu fallback ke settings message
        $message = $this->getMessage();
        if ($message && !empty($message->font_color) && $message->font_color !== '#ffffff') {
            return $message->font_color;
        }
        
        // Fallback ke warna message di settings (bukan timer)
        return $this->getSetting()->font_color ?? '#ffffff';
    }
    
    // Method terpisah untuk timer colors (tidak digunakan di message display)
    public function getTimerBackgroundColorProperty()
    {
        return $this->getSetting()->timer_bg_color ?? '#000000';
    }
    
    public function getTimerFontColorProperty()
    {
        return $this->getSetting()->timer_font_color ?? '#ffffff';
    }
    
    public function getMessageTitleProperty()
    {
        $message = $this->getMessage();
        return $message ? $message->title : 'Tidak Ada Pesan';
    }
    
    public function getMessageContentProperty()
    {
        $message = $this->getMessage();
        if (!$message) {
            return 'Tidak ada pesan aktif untuk ditampilkan.';
        }
        
        return $message->content;
    }
    
    public function getMessageTypeProperty()
    {
        $message = $this->getMessage();
        return $message ? $message->type : 'short';
    }
    
    public function getIsShortMessageProperty()
    {
        $message = $this->getMessage();
        return $message ? $message->isShort() : true;
    }
    
    public function getIsLongMessageProperty()
    {
        $message = $this->getMessage();
        return $message ? $message->isLong() : false;
    }
    
    public function render()
    {
        return view('livewire.display-message', [
            'message' => $this->getMessage(),
            'setting' => $this->getSetting(),
            'background_color' => $this->background_color,
            'font_color' => $this->font_color,
            'message_title' => $this->message_title,
            'message_content' => $this->message_content,
            'message_type' => $this->message_type,
            'is_short_message' => $this->is_short_message,
            'is_long_message' => $this->is_long_message,
            'has_active_message' => $this->has_active_message
        ]);
    }
}
