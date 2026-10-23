<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    protected $fillable = ['poll_id', 'option_text'];

    public function poll(): BelongsTo  { return $this->belongsTo(Poll::class); }
    public function votes(): HasMany   { return $this->hasMany(Vote::class); }

    public function voteCount(): int   { return $this->votes()->count(); }

    public function percentage(int $total): float
    {
        return $total > 0 ? round(($this->votes()->count() / $total) * 100, 1) : 0;
    }
}
