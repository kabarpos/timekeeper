<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;

class DebugMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:messages {--create-test : Create test message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug messages and create test data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DEBUG MESSAGES ===');
        
        // Show all messages
        $messages = Message::all();
        $this->info('Total messages: ' . $messages->count());
        
        if ($messages->count() > 0) {
            $this->table(
                ['ID', 'Title', 'Type', 'Active', 'Content Preview'],
                $messages->map(function ($message) {
                    return [
                        $message->id,
                        $message->title ?: 'No Title',
                        $message->type,
                        $message->is_active ? 'Yes' : 'No',
                        substr($message->content, 0, 50) . '...'
                    ];
                })
            );
        } else {
            $this->warn('No messages found in database.');
        }
        
        // Show active message
        $activeMessage = Message::where('is_active', true)->first();
        if ($activeMessage) {
            $this->info('Active message: ' . $activeMessage->title);
        } else {
            $this->warn('No active message found.');
        }
        
        // Create test message if requested
        if ($this->option('create-test')) {
            $this->createTestMessage();
        }
        
        return 0;
    }
    
    private function createTestMessage()
    {
        // Deactivate all messages first
        Message::query()->update(['is_active' => false]);
        
        $message = Message::create([
            'title' => 'Pesan Test',
            'content' => 'Ini adalah pesan test untuk debugging. Pesan ini dibuat otomatis oleh sistem.',
            'type' => 'short',
            'is_active' => true,
            'bg_color' => '#1f2937',
            'font_color' => '#ffffff'
        ]);
        
        $this->info('Test message created with ID: ' . $message->id);
        $this->info('Message is now active and should appear on display page.');
    }
}
