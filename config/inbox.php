<?php

declare(strict_types=1);

/**
 * Inbox park/re-drive tuning (PB-003 · Blueprint §13 · D-05 pattern).
 *
 * The retry POLICY (bounded park → re-drive → dead) is architectural and frozen;
 * these NUMBERS are operational and tunable without an ADR.
 */
return [
    // How long a parked message waits before the next re-drive attempt.
    'park_retry_minutes' => (int) env('INBOX_PARK_RETRY_MINUTES', 5),

    // Total time a message may remain parked before it is dead-lettered (§7 F4).
    'park_deadline_minutes' => (int) env('INBOX_PARK_DEADLINE_MINUTES', 60),
];
