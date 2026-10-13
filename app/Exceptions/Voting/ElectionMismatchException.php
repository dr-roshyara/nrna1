<?php

namespace App\Exceptions\Voting;

/**
 * Thrown when election data is inconsistent with voter slug, e.g. a real voting link for a demo election on a real
 * route. That is a refused request, not a server error: 403 (Human PO 2026-10-13, restoring the controller's
 * pre-PBDIGIT-273 answer; was 500, see PBDIGIT-275). Web requests get the standard 403 page (bootstrap/app.php).
 */
class ElectionMismatchException extends ConsistencyException
{
    public function getUserMessage(): string
    {
        return 'Election data is inconsistent. Please contact support.';
    }

    public function getHttpCode(): int
    {
        return 403;
    }
}
