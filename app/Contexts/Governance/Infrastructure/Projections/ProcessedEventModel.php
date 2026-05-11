<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Projections;

use Illuminate\Database\Eloquent\Model;

final class ProcessedEventModel extends Model
{
    protected $table = 'processed_events';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_id',
        'event_type',
        'committee_id',
        'processed_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'processed_at' => 'datetime',
    ];
}
