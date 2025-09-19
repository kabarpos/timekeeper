<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
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

    /**
     * Determine if the message is short based on content length
     * Short messages are considered to be 100 characters or less
     */
    public function isShort()
    {
        return strlen($this->content) <= 100;
    }

    /**
     * Determine if the message is long based on content length
     * Long messages are considered to be more than 100 characters
     */
    public function isLong()
    {
        return strlen($this->content) > 100;
    }
}
