<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class OrganisationUser extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'organisation_id',
        'user_id',
        'role',
        'status',
        'joined_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Upward relationships (identity hierarchy)
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Downward relationships (hierarchy)
     */
    public function member()
    {
        return $this->hasOne(Member::class, 'organisation_user_id');
    }
}
