<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Views;

use App\Contexts\Membership\Domain\Committee\Committee;

final readonly class CommitteeDashboardView
{
    public function __construct(
        private Committee $committee,
        private array $subCommittees
    ) {}

    private function resolveLevel(): int
    {
        return match ($this->committee->type()->value()) {
            'central'  => 1,
            'province' => 2,
            'district' => 3,
            'ward'     => 4,
            default    => 5,
        };
    }

    public function toArray(): array
    {
        return [
            'committee' => [
                'id'             => $this->committee->getId()->value(),
                'name'           => $this->committee->getName()->value(),
                'code'           => $this->committee->code(),
                'type'           => $this->committee->type()->value(),
                'level'          => $this->resolveLevel(),
                'geo_reference'  => $this->committee->getOperationalGeoReference()?->value(),
                'status'         => $this->committee->getStatus()->value(),
                'formation_date' => null,
            ],
            'sub_committees' => array_map(
                fn (Committee $c) => [
                    'id' => $c->getId()->value(),
                    'name' => $c->getName()->value(),
                    'code' => $c->code(),
                    'type' => $c->type()->value(),
                ],
                $this->subCommittees
            ),
            'assignments' => [],
        ];
    }
}
