<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Port;

/**
 * Application port hiding the identifier-generation strategy from the domain.
 *
 * Contestation-LOCAL by necessity: Adjudication owns an identical port, but a
 * consumer/producer may never import another context's Application or
 * Infrastructure code (Cross-Context Integration Contract R-1/R-2, enforced by
 * Deptrac). The PATTERN is reused; the class cannot be.
 *
 * WP-5 needs it because the routing act MINTS the integration conversation
 * origin inside the Application layer, where facades are banned (house Rule 2).
 */
interface IdentityGenerator
{
    public function next(): string;
}
