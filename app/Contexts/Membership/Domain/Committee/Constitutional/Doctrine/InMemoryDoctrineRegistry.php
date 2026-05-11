<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

final class InMemoryDoctrineRegistry implements DoctrineRegistryInterface
{
    /** @var DoctrineArtifact[] */
    private array $artifacts = [];

    public function register(DoctrineArtifact $artifact): void
    {
        $this->artifacts[$artifact->version] = $artifact;
    }

    public function resolveVersion(string $doctrineVersion): ?object
    {
        return $this->artifacts[$doctrineVersion] ?? null;
    }

    public function findEffectiveAt(string $scope, \DateTimeImmutable $at): ?DoctrineArtifact
    {
        foreach ($this->artifacts as $artifact) {
            if ($artifact->constitutionalScope->value === $scope && $artifact->isEffectiveAt($at)) {
                return $artifact;
            }
        }
        return null;
    }
}
