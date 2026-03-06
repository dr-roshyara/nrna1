<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class VoterSlug extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'election_id',
        'user_id',
        'slug',
        'current_step',
        'status',
    ];

    protected $casts = [
        'current_step' => 'integer',
        'status' => 'string',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class)->withoutGlobalScopes();
    }

    public function election()
    {
        return $this->belongsTo(Election::class)->withoutGlobalScopes();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'voter_slug_id', 'id');
    }

    public function scopeForOrganisation($query, string $organisationId)
    {
        return $query->withoutGlobalScopes()->where('organisation_id', $organisationId);
    }

    public function scopeForElection($query, $election)
    {
        $electionId = is_string($election) ? $election : $election->id;
        return $query->withoutGlobalScopes()->where('election_id', $electionId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVoted($query)
    {
        return $query->where('status', 'voted');
    }

    public function hasVoted(): bool
    {
        return $this->status === 'voted';
    }

    public function markAsVoted(): bool
    {
        return $this->update(['status' => 'voted']);
    }
}
