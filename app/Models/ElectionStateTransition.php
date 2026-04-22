<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionStateTransition extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $table = 'election_state_transitions';

    protected $fillable = [
        'election_id',
        'from_state',
        'to_state',
        'trigger',
        'actor_id',
        'reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(function ($model) {
            throw new \RuntimeException('ElectionStateTransition records are immutable and cannot be updated.');
        });

        static::deleting(function ($model) {
            throw new \RuntimeException('ElectionStateTransition records cannot be deleted.');
        });

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class)->withoutGlobalScopes();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }
}
