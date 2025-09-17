<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
        'type',
        'bg_color',
        'font_color'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeShort($query)
    {
        return $query->where('type', 'short');
    }

    public function scopeLong($query)
    {
        return $query->where('type', 'long');
    }

    public function isShort(): bool
    {
        return $this->type === 'short';
    }

    public function isLong(): bool
    {
        return $this->type === 'long';
    }
}
