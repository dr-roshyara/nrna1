<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

final class CommitteeMembershipApplicationModel extends Model
{
    use BelongsToTenant;

    protected $table = 'committee_membership_applications';

    protected $fillable = [
        'id', 'organisation_id', 'member_id', 'committee_id',
        'reason', 'exception_justification', 'status',
        'submitted_at', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'id'              => 'string',
        'organisation_id' => 'string',
        'member_id'       => 'string',
        'committee_id'    => 'string',
        'reason'          => 'string',
        'status'          => 'string',
        'submitted_at'    => 'datetime',
        'reviewed_at'     => 'datetime',
    ];

    public $timestamps = true;
    public $incrementing = false;
    protected $keyType = 'string';
}
