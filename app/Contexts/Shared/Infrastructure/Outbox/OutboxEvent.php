<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Outbox;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string|null $correlation_id F-PB006-2 provenance (nullable: predates stamping / chain start has no cause)
 * @property string|null $causation_id
 */
final class OutboxEvent extends Model
{
    use HasUuids;

    protected $table = 'outbox_events';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'organisation_id',
        'aggregate_type',
        'aggregate_id',
        'event_type',
        'payload',
        'correlation_id',
        'causation_id',
        'status',
        'attempts',
        'available_at',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'json',
        'available_at' => 'datetime',
        'created_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public const UPDATED_AT = null;

    public function markProcessed(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    public function markFailed(): void
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now(),
        ]);
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function reschedule(\DateTimeInterface $availableAt): void
    {
        $this->update([
            'available_at' => $availableAt,
            'status' => 'pending',
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForTenant($query, string $tenantId)
    {
        return $query->where('organisation_id', $tenantId);
    }
}
