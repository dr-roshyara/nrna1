<?php

declare(strict_types=1);

namespace Tests\Support\Builders;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

final class UserBuilder
{
    private string $id;
    private string $organisationId;
    private string $name = 'Test User';
    private string $email;
    private string $region = 'Test Region';
    private string $password = 'password';

    public static function forOrganisation(string $organisationId): self
    {
        return new self($organisationId);
    }

    private function __construct(string $organisationId)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->organisationId = $organisationId;
        $this->email = 'testuser-' . substr(Uuid::uuid4()->toString(), 0, 8) . '@test.local';
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

    public function withEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function withRegion(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function persist(): UserModel
    {
        return UserModel::create([
            'id' => $this->id,
            'organisation_id' => $this->organisationId,
            'name' => $this->name,
            'email' => $this->email,
            'region' => $this->region,
            'password' => bcrypt($this->password),
        ]);
    }
}

class UserModel extends Model
{
    protected $table = 'users';
    protected $fillable = ['id', 'organisation_id', 'name', 'email', 'region', 'password'];
    protected $keyType = 'string';
    public $incrementing = false;
}
