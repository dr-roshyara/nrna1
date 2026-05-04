<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\DTOs;

final readonly class CommitteeDashboardDTO
{
    /**
     * @param AssignmentDTO[] $assignments
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $code,
        public string $type,
        public int $level,
        public ?string $geoReference,
        public string $status,
        public array $assignments,
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
            'assignments' => array_map(
                fn (AssignmentDTO $a) => [
                    'id' => $a->id,
                    'member_id' => $a->memberId,
                    'member_name' => $a->memberName,
                    'role_label' => $a->roleLabel,
                    'role_path' => $a->rolePath,
                    'joined_date' => $a->joinedDate->format('Y-m-d'),
                ],
                $this->assignments
            ),
        ];
    }
}
