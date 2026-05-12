<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\DTOs;

use App\Contexts\Geography\Domain\Entities\GeoAdministrativeUnit;
use App\Contexts\Geography\Domain\Services\GeographyLevelClassifier;

final class GeoUnitResponseMapper
{
    public function __construct(
        private readonly GeographyLevelClassifier $classifier,
    ) {}

    public function fromEntity(GeoAdministrativeUnit $unit, ?\DateTimeImmutable $now = null): GeoUnitResponse
    {
        $now ??= new \DateTimeImmutable('now');

        return new GeoUnitResponse(
            id: $unit->getId()->toInt(),
            countryCode: $unit->getCountryCode()->toString(),
            adminLevel: $unit->getLevel()->toInt(),
            adminType: $this->classifier->resolve($unit->getLevel()->toInt()),
            parentId: $unit->getParentId()?->toInt(),
            code: $unit->getOfficialCode()?->toString(),
            name: $unit->getName()->toArray(),
            isActive: $unit->isActiveAt($now),
            validFrom: $unit->getValidFrom()?->format(\DateTimeInterface::ATOM),
            validTo: $unit->getValidTo()?->format(\DateTimeInterface::ATOM),
        );
    }
}
