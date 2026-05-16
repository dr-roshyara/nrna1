<?php

namespace Database\Factories;

use App\Contexts\Membership\Infrastructure\Models\CommitteeAssignmentModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommitteeAssignmentFactory extends Factory
{
    protected $model = CommitteeAssignmentModel::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::ulid(),
            'organisation_id' => null,
            'committee_id' => null,
            'member_id' => 'TEST-MEMBER-' . Str::random(5),
            'role_path' => '1',
            'is_active' => true,
            'left_date' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'left_date' => now(),
        ]);
    }

    public function forCommittee($committeeId): static
    {
        return $this->state(fn (array $attributes) => [
            'committee_id' => $committeeId,
        ]);
    }

    public function forOrganisation($organisationId): static
    {
        return $this->state(fn (array $attributes) => [
            'organisation_id' => $organisationId,
        ]);
    }

    public function forMember($memberId): static
    {
        return $this->state(fn (array $attributes) => [
            'member_id' => $memberId,
        ]);
    }
}
