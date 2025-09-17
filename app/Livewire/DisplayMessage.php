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
        $this->message = Message::where('is_active', true)->first();
        $this->setting = Setting::current();
        $this->has_active_message = $this->message !== null;
    }
    
    #[On('display-updated')]
    public function refreshDisplay()
    {
        $this->loadData();
    }
    
    #[On('settings-updated')]
    public function onSettingsUpdated($data = null)
    {
        if ($data) {
            // Update settings secara real-time
            $setting = $this->getSetting();
            $setting->bg_color = $data['bg_color'];
            $setting->font_color = $data['font_color'];
            $setting->display_mode = $data['display_mode'];
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

    #[On('display-mode-changed')]
    public function onDisplayModeChanged($data)
    {
        $setting = $this->getSetting();
        $setting->display_mode = $data['mode'];
        $this->loadData();
    }
    
    public function getBackgroundColorProperty()
    {
        $message = $this->getMessage();
        if ($message) {
            return $message->bg_color;
        }
        
        return $this->getSetting()->bg_color;
    }
    
    public function getFontColorProperty()
    {
        $message = $this->getMessage();
        if ($message) {
            return $message->font_color;
        }
        
        return $this->getSetting()->font_color;
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
