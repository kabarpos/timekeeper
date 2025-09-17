<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timer extends Model
{
    protected $fillable = [
        'duration_seconds',
        'remaining_seconds',
        'status',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_seconds' => 'integer',
        'remaining_seconds' => 'integer'
    ];

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused';
    }

    public function isStopped(): bool
    {
        return $this->status === 'stopped';
    }

    public function getFormattedTimeAttribute(): string
    {
        $minutes = floor($this->remaining_seconds / 60);
        $seconds = $this->remaining_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
