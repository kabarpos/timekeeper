<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\Validate;

class MessageCrud extends Component
{
    #[Validate('required|string|max:255')]
    public $title = '';
    
    #[Validate('required|string')]
    public $content = '';
    
    #[Validate('required|in:short,long')]
    public $type = 'short';
    
    #[Validate('required|string|regex:/^#[0-9A-Fa-f]{6}$/')]
    public $bg_color = '#000000';
    
    #[Validate('required|string|regex:/^#[0-9A-Fa-f]{6}$/')]
    public $font_color = '#ffffff';
    
    public $editing_id = null;
    
    // Tidak menggunakan properti publik untuk Collection karena akan conflict dengan validasi
    // Gunakan method untuk mengambil messages
    
    public function mount()
    {
        // Tidak perlu load messages di mount karena akan diambil di render
    }
    
    public function getAllMessages()
    {
        return Message::orderBy('created_at', 'desc')->get();
    }
    
    public function save()
    {
        $this->validate();
        
        if ($this->editing_id) {
            // Update existing message
            $message = Message::find($this->editing_id);
            $message->update([
                'title' => $this->title,
                'content' => $this->content,
                'type' => $this->type,
                'bg_color' => $this->bg_color,
                'font_color' => $this->font_color
            ]);
            
            session()->flash('message', 'Pesan berhasil diperbarui!');
        } else {
            // Create new message
            Message::create([
                'title' => $this->title,
                'content' => $this->content,
                'type' => $this->type,
                'bg_color' => $this->bg_color,
                'font_color' => $this->font_color,
                'is_active' => false
            ]);
            
            session()->flash('message', 'Pesan berhasil dibuat!');
        }
        
        $this->resetForm();
        
        // Broadcast events untuk real-time sync
        $this->dispatch('message-updated')->to('display-message');
        $this->dispatch('messages-changed')->to('display-message');
    }
    
    public function edit($id)
    {
        $message = Message::find($id);
        
        $this->editing_id = $id;
        $this->title = $message->title;
        $this->content = $message->content;
        $this->type = $message->type;
        $this->bg_color = $message->bg_color;
        $this->font_color = $message->font_color;
    }
    
    public function delete($id)
    {
        $message = Message::find($id);
        $wasActive = $message->is_active;
        
        $message->delete();
        
        // Broadcast events untuk real-time sync
        $this->dispatch('message-deleted', ['was_active' => $wasActive])->to('display-message');
        $this->dispatch('messages-changed')->to('display-message');
        
        session()->flash('message', 'Pesan berhasil dihapus!');
    }
    
    public function activate($id)
    {
        // Deactivate all messages first
        Message::query()->update(['is_active' => false]);
        
        // Activate selected message
        $activeMessage = Message::find($id);
        $activeMessage->update(['is_active' => true]);
        
        // Broadcast events untuk real-time sync
        $this->dispatch('message-activated', [
            'message_id' => $id,
            'title' => $activeMessage->title,
            'content' => $activeMessage->content,
            'type' => $activeMessage->type,
            'bg_color' => $activeMessage->bg_color,
            'font_color' => $activeMessage->font_color
        ])->to('display-message');
        $this->dispatch('messages-changed')->to('display-message');
        
        session()->flash('message', 'Pesan berhasil diaktifkan!');
    }
    
    public function resetForm()
    {
        $this->editing_id = null;
        $this->title = '';
        $this->content = '';
        $this->type = 'short';
        $this->bg_color = '#000000';
        $this->font_color = '#ffffff';
    }
    
    public function render()
    {
        return view('livewire.admin.message-crud', [
            'messages' => $this->getAllMessages()
        ]);
    }
}
