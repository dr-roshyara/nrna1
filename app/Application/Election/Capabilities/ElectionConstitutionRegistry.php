<?php

namespace App\Application\Election\Capabilities;

use App\Domain\Election\Constitution\ElectionConstitution;

final class ElectionConstitutionRegistry
{
    public function getAllActions(): array
    {
        return array_keys(ElectionConstitution::RULES);
    }

    public function getActionMetadata(string $action): array
    {
        return ElectionConstitution::RULES[$action] ?? [];
    }
}
