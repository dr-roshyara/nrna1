<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

use App\Contexts\Committee\Application\ReadModel\CommitteeMemberView;

final readonly class CommitteeDashboardDTO
{
    /**
     * @param CommitteeMemberView[] $members
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $code,
        public ?string $type,
        public int $level,
        public ?string $geoReference,
        public string $status,
        public array $members,
    ) {}

    public function toArray(): array
    {
        return [
            'committee' => [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code,
                'type' => $this->type,
                'level' => $this->level,
                'geo_reference' => $this->geoReference,
                'status' => $this->status,
            ],
            'members' => array_map(
                fn (CommitteeMemberView $m) => [
                    'member_id' => $m->memberId,
                    'display_name' => $m->displayName,
                    'status_key' => $m->statusKey,
                    'role_key' => $m->roleKey,
                    'joined_date' => $m->joinedAt?->format('Y-m-d'),
                ],
                $this->members
            ),
        ];
    }
}
