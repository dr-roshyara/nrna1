<?php

declare(strict_types=1);

namespace Tests\Support\Builders;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

/**
 * OrganisationBuilder — Creates valid Organisation aggregates
 *
 * Root aggregate in multi-tenant system.
 * Must be persisted before creating child aggregates (Member, Fee).
 */
final class OrganisationBuilder
{
    private string $id;
    private string $name = 'Test Organisation';
    private string $slug;
    private string $type = 'tenant';

    public static function new(): self
    {
        return new self();
    }

    private function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        // Generate slug from UUID (must be unique)
        $this->slug = 'test-org-' . substr(Uuid::uuid4()->toString(), 0, 8);
    }

    public function withId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function withType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Persist organisation to database
     * Required: All child aggregates (Member, Fee) need this organisation to exist.
     */
    public function persist(): OrganisationModel
    {
        return OrganisationModel::create([
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
        ]);
    }

    public function getTenantId(): TenantId
    {
        return TenantId::fromString($this->id);
    }
}

/**
 * Temporary model wrapper for organisation persistence
 * In production: maps to actual organisations table
 */
class OrganisationModel extends Model
{
    protected $table = 'organisations';
    protected $fillable = ['id', 'name', 'slug', 'type'];
    protected $keyType = 'string';
    public $incrementing = false;
}
