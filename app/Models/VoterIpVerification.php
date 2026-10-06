<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * VoterIpVerification — a voter's captured IP address for one election (PBDIGIT-222).
 *
 * IP evidence only: separately bounded from identity evidence (VoterVerification). It never
 * creates admission evidence and is not read by VotingEntitlement. Display / evidence only —
 * enforcement belongs to P-BINDING (PBDIGIT-247).
 */
class VoterIpVerification extends Model
{
    use HasUuids;

    protected $fillable = [
        'election_id',
        'user_id',
        'organisation_id',
        'ip_address',
        'captured_by',
        'captured_at',
        'notes',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
    ];
}
