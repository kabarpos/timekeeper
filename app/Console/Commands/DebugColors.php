<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\Message;

class DebugColors extends Command
{
    protected $signature = 'debug:colors';
    protected $description = 'Debug color settings and active messages';

    public function handle()
    {
        $this->info('=== DEBUG COLOR SETTINGS ===');
        
        // Check settings
        $setting = Setting::first();
        if ($setting) {
            $this->info('Settings found:');
            $this->line('- bg_color: ' . $setting->bg_color);
            $this->line('- font_color: ' . $setting->font_color);
            $this->line('- timer_bg_color: ' . $setting->timer_bg_color);
            $this->line('- timer_font_color: ' . $setting->timer_font_color);
            $this->line('- display_mode: ' . $setting->display_mode);
        } else {
            $this->error('No settings found in database!');
        }
        
        $this->info('');
        $this->info('=== ACTIVE MESSAGES ===');
        
        // Check active messages
        $activeMessage = Message::where('is_active', true)->first();
        if ($activeMessage) {
            $this->info('Active message found:');
            $this->line('- ID: ' . $activeMessage->id);
            $this->line('- Title: ' . $activeMessage->title);
            $this->line('- bg_color: ' . ($activeMessage->bg_color ?? 'NULL'));
            $this->line('- font_color: ' . ($activeMessage->font_color ?? 'NULL'));
            $this->line('- is_active: ' . ($activeMessage->is_active ? 'true' : 'false'));
        } else {
            $this->warn('No active message found');
        }
        
        $this->info('');
        $this->info('=== ALL MESSAGES ===');
        $messages = Message::all();
        foreach ($messages as $message) {
            $this->line("ID {$message->id}: {$message->title} (active: " . ($message->is_active ? 'YES' : 'NO') . ")");
        }
        
        return 0;
    }
}
