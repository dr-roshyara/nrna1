<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\Identity\Application\ActingContextRef;
use App\Contexts\Identity\Domain\PlatformContext;
use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Domain\PersonReference;

/**
 * Adapter onto Identity's published service (D-225): the 'reference-geography' context reference of the signed-in
 * person, translated into Geography's own value. The only place Geography touches Identity (IG-P8).
 */
final class IdentityActingPerson implements ActingPerson
{
    public function __construct(private readonly ActingContextRef $identity)
    {
    }

    public function current(): PersonReference
    {
        $answer = $this->identity->for(PlatformContext::ReferenceGeography);
        if (!$answer->isGranted()) {
            throw new ActingPersonRefused($answer->refusal()?->value ?? 'unknown');
        }

        return PersonReference::fromString($answer->ref()->value());
    }
}
