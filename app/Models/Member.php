<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class Member extends Model
{
    use HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'organisation_id',
        'organisation_user_id',
        'membership_number',
        'status',
        'joined_at',
        'membership_expires_at',
        'last_renewed_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'membership_expires_at' => 'datetime',
        'last_renewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Upward relationships (identity hierarchy)
     */
    public function organisationUser()
    {
        return $this->belongsTo(OrganisationUser::class);
    }

    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            OrganisationUser::class,
            'id',
            'id',
            'organisation_user_id',
            'user_id'
        );
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Downward relationships (hierarchy)
     */
    public function voters()
    {
        return $this->hasMany(Voter::class, 'member_id');
    }
}
