<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'bg_color',
        'font_color',
        'timer_bg_color',
        'timer_font_color',
        'display_mode'
    ];

    public static function current()
    {
        return static::first() ?? static::create([
            'bg_color' => '#000000',
            'font_color' => '#ffffff',
            'timer_bg_color' => '#000000',
            'timer_font_color' => '#ffffff',
            'display_mode' => 'timer'
        ]);
    }

    public function isTimerMode(): bool
    {
        return $this->display_mode === 'timer';
    }

    public function isMessageMode(): bool
    {
        return $this->display_mode === 'message';
    }

    public function switchToTimer()
    {
        $this->update(['display_mode' => 'timer']);
    }

    public function switchToMessage()
    {
        $this->update(['display_mode' => 'message']);
    }
}
