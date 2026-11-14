<?php

declare(strict_types=1);

namespace Tests\Fixtures\Platform\Queue;

use App\Contexts\Membership\Application\Organisational\CoverageKnownAt;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJob;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * TEST ONLY (PBDIGIT-345 task 10, T-7): an E-2 organisation job (routed to its envelope's organisation) that asks C2,
 * resolved from the container with Membership's PRODUCTION bindings, about $askAbout. Answers are kept in $answers;
 * $between (if set) runs between two calls, to change the registry during the operation.
 */
final class C2ProbeJob extends OrganisationJob
{
    /** @var list<\App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageKnownAtAnswer|\Throwable> */
    public static array $answers = [];
    public static ?\Closure $between = null;

    public function __construct(TenantId $routedTo, public readonly string $askAbout, public readonly int $calls = 1)
    {
        parent::__construct($routedTo);
    }

    public function handle(CoverageKnownAt $c2): void
    {
        for ($i = 0; $i < $this->calls; $i++) {
            if ($i > 0 && self::$between !== null) {
                (self::$between)();
            }
            try {
                self::$answers[] = $c2->byMembershipIds(TenantId::fromString($this->askAbout), [MemberId::fromString('M1')], Instant::of(200));
            } catch (\Throwable $e) {
                self::$answers[] = $e;
            }
        }
    }
}
