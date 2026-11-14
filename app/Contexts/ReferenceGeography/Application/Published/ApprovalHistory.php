<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * The D-90 approval status of one revision publication AS KNOWN AT a knowledge position (G-6c-6; design revision 3
 * §10). Published language: strings and ints only, never persons. The same (publication, knownAt) always reads the same.
 *
 * status: approved | approval-defective | confirmed | corrected | finding-closed
 *   - corrected: a published correction (correctedAt) replaced all of its facts (full coverage);
 *   - otherwise the latest finding decides: open → approval-defective; confirmed → confirmed; closed → finding-closed;
 *   - no finding → approved.
 * events: every finding, its resolution and the correction, in position order; every episode kept.
 */
final readonly class ApprovalHistory
{
    /** @param list<array{position: int, event: string}> $events event: finding | confirmation | closure | correction */
    public function __construct(
        public int $publication,
        public int $knownAt,
        public string $status,
        public ?int $correctedAt,
        public array $events,
    ) {
    }
}
