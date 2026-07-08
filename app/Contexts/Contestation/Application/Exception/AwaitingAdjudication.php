<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Application\Exception;

use RuntimeException;

/**
 * Application business condition: a correction arrived for a Challenge that is not yet
 * adjudicated (no Challenge carries this determination). Temporally premature, NOT
 * invalid — the causal predecessor (`DeterminationIssued` → adjudication) has not landed.
 * The inbox translator maps it to a park + re-drive.
 */
final class AwaitingAdjudication extends RuntimeException
{
}
