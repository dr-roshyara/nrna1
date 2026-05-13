<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class CommitteeModel extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $table = 'committees';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * Get the route key for implicit model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'id',
        'organisation_id',
        'name',
        'slug',
        'code',
        'type',
        'level',
        'operational_geo',
        'operational_geo_reference',
        'operational_geo_cache',
        'geo_cache_version',
        'geo_cache_updated_at',
        'parent_committee_id',
        'formation_date',
        'term_end_date',
        'status',
        'max_members',
        'region_code',
        'country_code',
        'geo_unit_id',
        'canonical_geo_id',
        // Governance snapshot fields (Phase C - immutable temporal identity)
        'created_from_structure_id',
        'snapshot_level_index',
        'snapshot_level_name',
        'snapshot_level_code',
        'snapshot_geo_policy',
        'snapshot_geo_scope',
        'snapshot_structure_version',
        'snapshot_taken_at',
    ];

    protected $casts = [
        'operational_geo_cache' => 'json',
        'formation_date' => 'date',
        'term_end_date' => 'date',
        'geo_cache_updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (CommitteeModel $committee) {
            if (!$committee->slug) {
                $committee->slug = self::generateUniqueSlug(
                    $committee->name,
                    $committee->organisation_id
                );
            }
        });
    }

    public static function generateUniqueSlug(string $name, string $organisationId, ?string $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (self::query()
            ->where('organisation_id', $organisationId)
            ->where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CommitteeAssignmentModel::class, 'committee_id', 'id');
    }
}
