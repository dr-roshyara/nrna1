<?php

declare(strict_types=1);

namespace App\Contexts\Shared\Infrastructure\Inbox;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * Row gateway for `inbox_events` — the per-consumer dedupe/park store.
 *
 * Owner: Shared Infrastructure
 * Layer: Infrastructure (Eloquent — never crosses the port; handlers see InboxMessage only)
 * Responsibility: persist one (event_id, consumer_context) consumption record + its state
 * Traceability: Blueprint §6/§7 · ADR-T4 · D-03/D-06 · Matrix: Inbox
 *
 * Status model (Blueprint §6, explicit — no implicit initial state):
 * parked (awaiting re-drive) · processed ▣ · dead ▣.
 * Conventions mirror OutboxEvent (HasUuids, update()-based transitions,
 * UPDATED_AT = null — transitions write their own explicit timestamps).
 */
final class InboxEvent extends Model
{
    use HasUuids;

    protected $table = 'inbox_events';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'event_id',
        'consumer_context',
        'event_type',
        'payload',
        'organisation_id',
        'correlation_id',
        'causation_id',
        'status',
        'park_attempts',
        'parked_until',
        'park_deadline',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'json',
        'park_attempts' => 'integer',
        'parked_until' => 'datetime',
        'park_deadline' => 'datetime',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public const UPDATED_AT = null;

    public function markProcessed(): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);
    }

    public function markParked(\DateTimeInterface $parkedUntil, \DateTimeInterface $parkDeadline): void
    {
        $this->update([
            'status' => 'parked',
            'park_attempts' => $this->park_attempts + 1,
            'parked_until' => $parkedUntil,
            'park_deadline' => $parkDeadline,
        ]);
    }

    public function markDead(): void
    {
        $this->update([
            'status' => 'dead',
            'processed_at' => now(),
        ]);
    }

    /**
     * Parked rows whose re-drive time has arrived (Blueprint §7 F4).
     *
     * $asOf defaults to now() (backward-compatible); redrive passes the injected
     * clock's instant so recovery has ONE authoritative time source (R2).
     */
    public function scopeParkedDue($query, ?\DateTimeInterface $asOf = null)
    {
        return $query->where('status', 'parked')
            ->whereNotNull('parked_until')
            ->where('parked_until', '<=', $asOf ?? now());
    }

    public function scopeForTenant($query, string $tenantId)
    {
        return $query->where('organisation_id', $tenantId);
    }
}
