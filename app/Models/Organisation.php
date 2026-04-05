<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'slug',
        'type',
        'is_default',
        'address',
        'representative',
        'settings',
        'languages',
        'logo',
    ];

    protected $casts = [
        'address' => 'array',
        'representative' => 'array',
        'settings' => 'array',
        'languages' => 'array',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    /**
     * Resolve {election:slug} bindings nested under {organisation:slug} without
     * the BelongsToTenant global scope, which requires session context that is
     * only set later (by ensure.organisation middleware, after SubstituteBindings).
     * Controllers must still validate organisation_id ownership explicitly.
     */
    public function resolveChildRouteBinding($childType, $value, $field = null)
    {
        if (strtolower($childType) === 'election') {
            return Election::withoutGlobalScopes()
                ->where('slug', $value)
                ->first();
        }

        return parent::resolveChildRouteBinding($childType, $value, $field);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'organisation_id', 'id')
                    ->withoutGlobalScopes();
    }

    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    public function commissionMembers()
    {
        return $this->users()->wherePivot('role', 'commission');
    }

    public function voters()
    {
        return $this->users()->wherePivot('role', 'voter');
    }

    public function roles()
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    // ── Participant relationships (staff / guest / election_committee) ─────────

    public function participants()
    {
        return $this->hasMany(OrganisationParticipant::class);
    }

    public function staff()
    {
        return $this->hasMany(OrganisationParticipant::class)
                    ->where('participant_type', 'staff');
    }

    public function guests()
    {
        return $this->hasMany(OrganisationParticipant::class)
                    ->where('participant_type', 'guest');
    }

    public function electionCommittee()
    {
        return $this->hasMany(OrganisationParticipant::class)
                    ->where('participant_type', 'election_committee');
    }

    public function userOrganisationRoles()
    {
        return $this->hasMany(UserOrganisationRole::class);
    }

    /**
     * Check if organisation is platform type
     */
    public function isPlatform(): bool
    {
        return $this->type === 'platform';
    }

    /**
     * Check if organisation is tenant type
     */
    public function isTenant(): bool
    {
        return $this->type === 'tenant';
    }

    /**
     * Get the default platform organisation
     */
    public static function getDefaultPlatform(): ?self
    {
        return static::where('type', 'platform')
                     ->where('is_default', true)
                     ->first();
    }
}
