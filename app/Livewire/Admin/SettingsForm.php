<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;
use App\Exceptions\DatabaseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Traits\HandlesErrors;

class SettingsForm extends Component
{
    use HandlesErrors;
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
        
        // Load 5 recent messages dengan caching dan error handling
        $this->recent_messages = CacheService::remember(
            CacheService::RECENT_MESSAGES_KEY,
            function () {
                return Message::select(['id', 'title', 'content', 'is_active', 'created_at'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }
        );
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
        try {
            $setting = $this->getSetting();
            $setting->update([
                'display_mode' => $mode
            ]);
            
            // Update local property
            $this->display_mode = $mode;
            
            // Clear related caches
            CacheService::clearSettingCaches();
            
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
            
        } catch (QueryException $e) {
            Log::error('Database error saat mengubah mode display', [
                'mode' => $mode,
                'error' => $e->getMessage(),
                'sql' => $e->getSql()
            ]);
            session()->flash('error', 'Gagal mengubah mode display. Silakan coba lagi.');
            
        } catch (\Exception $e) {
            Log::error('Error tidak terduga saat mengubah mode display', [
                'mode' => $mode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
    
    #[On('message-updated')]
    public function refreshMessages()
    {
        // Clear cache ketika ada message yang diupdate
        CacheService::clearMessageCaches();
        $this->loadData();
    }
    
    public function toggleMessageStatus($messageId)
    {
        try {
            $message = Message::findOrFail($messageId);
            $message->update([
                'is_active' => !$message->is_active
            ]);
            
            // Clear cache ketika status message berubah
            CacheService::clearMessageCaches();
            
            // Refresh data untuk update UI
            $this->loadData();
            
            // Broadcast event untuk real-time sync ke display components
            $this->dispatch('message-status-changed', [
                'message_id' => $messageId,
                'is_active' => $message->is_active
            ]);
            
            $status = $message->is_active ? 'diaktifkan' : 'dinonaktifkan';
            session()->flash('message', "Pesan '{$message->title}' berhasil {$status}!");
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Message tidak ditemukan saat toggle status', [
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Pesan tidak ditemukan.');
            
        } catch (QueryException $e) {
            Log::error('Database error saat toggle message status', [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
                'sql' => $e->getSql()
            ]);
            session()->flash('error', 'Gagal mengubah status pesan. Silakan coba lagi.');
            
        } catch (\Exception $e) {
            Log::error('Error tidak terduga saat toggle message status', [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
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
