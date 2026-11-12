<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\PublishedLanguage;

/**
 * Published Language (C1 / C2; C1/C2 spec K-6): one interval of calendar dates [from, to), as 'YYYY-MM-DD';
 * to = null means open-ended. Consumers compare their business dates against it without interpreting Membership time.
 */
final readonly class CoverageInterval
{
    public function __construct(
        public string $from,
        public ?string $to,
    ) {
    }
}
