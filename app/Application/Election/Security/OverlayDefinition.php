<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlayStratification;

readonly class OverlayDefinition
{
    public function __construct(
        public string $identifier,
        public string $overlayClass,              // FQCN — container resolves, registry NEVER instantiates
        public OverlayStratification $stratification,
        public array $capableInfluences,          // OverlayInfluence[] this overlay may produce
        public bool $requiresRegistrarActivation,
        public bool $federationAware,
        public int $stratificationOrder,         // evaluation traversal order within stratification
    ) {}
}
