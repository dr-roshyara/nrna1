<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribution extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'user_id',
        'title',
        'description',
        'track',
        'status',
        'effort_units',
        'team_skills',
        'is_recurring',
        'outcome_bonus',
        'calculated_points',
        'proof_type',
        'proof_path',
        'verifier_notes',
        'verified_by',
        'verified_at',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'team_skills'   => 'array',
        'is_recurring'  => 'boolean',
        'verified_at'   => 'datetime',
        'approved_at'   => 'datetime',
    ];

    public function contributor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(PointsLedger::class);
    }
}
