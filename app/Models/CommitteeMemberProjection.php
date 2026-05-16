<?php

declare(strict_types=1);

namespace App\Models;

use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use Illuminate\Database\Eloquent\Model;

/**
 * CommitteeMemberProjection
 *
 * Read-model for committee membership (UI-optimized)
 *
 * CQRS invariants:
 * - Written ONLY by event listeners (MemberAssignedToCommittee, MemberRemovedFromCommittee)
 * - Never written by API or command handlers
 * - Idempotent via (committee_id, member_id) unique constraint
 * - Tenant-scoped for isolation
 */
class CommitteeMemberProjection extends Model
{
    protected $table = 'committee_member_projection';

    protected $fillable = [
        'id',
        'tenant_id',
        'committee_id',
        'member_id',
        'member_name',
        'member_email',
        'role',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'role' => CommitteeRole::class,
    ];

    protected $keyType = 'string';
    public $incrementing = false;
}
