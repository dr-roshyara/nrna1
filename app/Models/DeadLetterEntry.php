<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * DeadLetterEntry — Failed operation tracking
 *
 * Stores failed rows/chunks from bulk operations for later retry/resolution.
 * Enables graceful failure handling without losing data.
 *
 * Used by:
 * - BulkAssignVotersHandler: writes failed chunks on exception
 * - Admin DLQ dashboard: view + retry failed operations
 *
 * Granularity: Per-row (or per-chunk, depending on handler implementation).
 * This allows selective retry of just the problematic rows.
 */
class DeadLetterEntry extends Model
{
    use HasUuids;

    protected $table = 'dead_letter_queue';

    protected $fillable = [
        'queue_name',
        'payload',
        'error_message',
        'error_class',
        'organisation_id',
        'election_id',
        'failed_at',
        'retried_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'failed_at' => 'datetime',
        'retried_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $entry) {
            $entry->failed_at ??= now();
        });
    }
}
