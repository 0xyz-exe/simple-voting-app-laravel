<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected $fillable = ['question', 'description', 'is_active', 'allow_multiple', 'ends_at'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'allow_multiple' => 'boolean', 'ends_at' => 'datetime'];
    }

    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function options(): HasMany   { return $this->hasMany(PollOption::class); }
    public function votes(): HasMany     { return $this->hasMany(Vote::class); }

    public function isOpen(): bool
    {
        return $this->is_active && (!$this->ends_at || $this->ends_at->isFuture());
    }

    public function hasVoted(int $userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function totalVotes(): int
    {
        return $this->votes()->count();
    }
}
