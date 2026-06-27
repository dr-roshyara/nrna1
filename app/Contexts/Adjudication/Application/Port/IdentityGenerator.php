<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Port;

/**
 * Application port hiding the identifier-generation strategy from the domain.
 * The repository's nextIdentity() delegates here; UUID (or any scheme) is an
 * infrastructure adapter — the domain never couples to a specific mechanism.
 */
interface IdentityGenerator
{
    public function next(): string;
}
