<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberImportJob extends Model
{
    use HasUuids;

    protected $fillable = [
        'organisation_id',
        'initiated_by',
        'file_path',
        'original_filename',
        'status',
        'total_rows',
        'processed_rows',
        'imported_count',
        'skipped_count',
        'error_log',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'error_log'      => 'array',
        'started_at'     => 'datetime',
        'completed_at'   => 'datetime',
        'total_rows'     => 'integer',
        'processed_rows' => 'integer',
        'imported_count' => 'integer',
        'skipped_count'  => 'integer',
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function getProgressAttribute(): int
    {
        if ($this->total_rows === 0) {
            return 0;
        }
        return (int) round(($this->processed_rows / $this->total_rows) * 100);
    }

    public function markFailed(string $reason): void
    {
        $this->update([
            'status'       => 'failed',
            'error_log'    => array_merge($this->error_log ?? [], [['message' => $reason]]),
            'completed_at' => now(),
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);
    }
}
