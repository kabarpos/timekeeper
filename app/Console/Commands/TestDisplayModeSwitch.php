<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class TestDisplayModeSwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:display-mode {mode=timer : Mode to switch to (timer|message)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test display mode switching functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mode = $this->argument('mode');
        
        if (!in_array($mode, ['timer', 'message'])) {
            $this->error('Mode harus timer atau message');
            return 1;
        }
        
        $setting = Setting::current();
        $oldMode = $setting->display_mode;
        
        $this->info("Mode saat ini: {$oldMode}");
        $this->info("Mengubah mode ke: {$mode}");
        
        $setting->update(['display_mode' => $mode]);
        
        $this->info("Mode berhasil diubah dari {$oldMode} ke {$mode}");
        $this->info("Halaman display seharusnya auto-reload sekarang.");
        
        return 0;
    }
}
