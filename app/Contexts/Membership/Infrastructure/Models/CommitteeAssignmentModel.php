<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CommitteeAssignmentModel extends Model
{
    protected $table = 'committee_assignments';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'committee_id',
        'member_id',
        'role_path',
        'nomination_type',
        'election_date',
        'term_end_date',
        'joined_date',
        'left_date',
        'is_active',
        'appointed_by_user_id',
        'notes',
        'metadata',
        'organisation_id',
    ];

    protected $casts = [
        'election_date' => 'datetime',
        'term_end_date' => 'datetime',
        'joined_date' => 'datetime',
        'left_date' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function committee(): BelongsTo
    {
        return $this->belongsTo(CommitteeModel::class, 'committee_id', 'id');
    }
}
