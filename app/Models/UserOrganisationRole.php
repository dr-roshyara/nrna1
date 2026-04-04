<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOrganisationRole extends Model
{
    use HasUuids;

    // ── Role constants ────────────────────────────────────────────────────────
    public const ROLE_OWNER      = 'owner';
    public const ROLE_ADMIN      = 'admin';
    public const ROLE_COMMISSION = 'commission';
    public const ROLE_VOTER      = 'voter';
    public const ROLE_MEMBER     = 'member';   // default role; read-only org access

    public const ROLES = [
        self::ROLE_OWNER,
        self::ROLE_ADMIN,
        self::ROLE_COMMISSION,
        self::ROLE_VOTER,
        self::ROLE_MEMBER,
    ];

    /** Higher = more permissions */
    public const ROLE_HIERARCHY = [
        self::ROLE_OWNER      => 100,
        self::ROLE_ADMIN      => 80,
        self::ROLE_COMMISSION => 60,
        self::ROLE_VOTER      => 40,
        self::ROLE_MEMBER     => 20,
    ];

    // ── Eloquent config ───────────────────────────────────────────────────────
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'user_organisation_roles';

    protected $fillable = [
        'id',
        'user_id',
        'organisation_id',
        'role',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /** True if this role outranks $targetRole in the hierarchy. */
    public function canManage(string $targetRole): bool
    {
        $mine   = self::ROLE_HIERARCHY[$this->role]   ?? 0;
        $theirs = self::ROLE_HIERARCHY[$targetRole]   ?? 0;

        return $mine > $theirs;
    }
}
