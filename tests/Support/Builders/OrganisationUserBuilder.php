<?php

declare(strict_types=1);

namespace Tests\Support\Builders;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

final class OrganisationUserBuilder
{
    private string $id;
    private string $organisationId;
    private string $userId;
    private string $role = 'member';
    private string $status = 'active';

    public static function new(string $organisationId, string $userId): self
    {
        return new self($organisationId, $userId);
    }

    private function __construct(string $organisationId, string $userId)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->organisationId = $organisationId;
        $this->userId = $userId;
    }

    public function withRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function withStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function persist(): OrganisationUserModel
    {
        return OrganisationUserModel::create([
            'id' => $this->id,
            'organisation_id' => $this->organisationId,
            'user_id' => $this->userId,
            'role' => $this->role,
            'status' => $this->status,
        ]);
    }
}

class OrganisationUserModel extends Model
{
    protected $table = 'organisation_users';
    protected $fillable = ['id', 'organisation_id', 'user_id', 'role', 'status'];
    protected $keyType = 'string';
    public $incrementing = false;
}
