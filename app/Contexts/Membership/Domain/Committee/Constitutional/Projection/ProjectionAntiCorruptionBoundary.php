<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Projection;

/**
 * Marker interface — explicitly separates projection read models from replay domain objects.
 * Classes implementing this boundary MUST NOT be used inside replay logic (GovernanceReplayService,
 * GovernanceArchaeologyRecord). Violation collapses the replay/projection separation.
 */
interface ProjectionAntiCorruptionBoundary {}
