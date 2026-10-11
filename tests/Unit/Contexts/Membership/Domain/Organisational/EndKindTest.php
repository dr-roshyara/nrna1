<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational;

use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * D-3 = E1 (PO decision 2026-10-11): a closed set of ending acts — resignation, exclusion, death;
 * `legacy` only for attested migration history. Non-payment is a ground for exclusion, not an act;
 * term expiry is derived. PBDIGIT-271, Slice 2a step 3.
 */
final class EndKindTest extends TestCase
{
    /** @return array<string, array{string}> */
    public static function recognisedKinds(): array
    {
        return ['resignation' => ['resignation'], 'exclusion' => ['exclusion'], 'death' => ['death'], 'legacy' => ['legacy']];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('recognisedKinds')]
    public function test_the_recognised_kinds_construct(string $kind): void
    {
        $this->assertSame($kind, EndKind::fromString($kind)->value());
        $this->assertSame($kind, EndKind::tryFromString($kind)?->value());
    }

    /** @return array<string, array{string}> */
    public static function unrecognisedKinds(): array
    {
        return ['expiry' => ['expiry'], 'non_payment' => ['non_payment'], 'arbitrary' => ['k'], 'case' => ['Resignation']];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('unrecognisedKinds')]
    public function test_an_unrecognised_kind_cannot_exist_in_the_domain(string $kind): void
    {
        $this->assertNull(EndKind::tryFromString($kind));
        $failure = null;
        try {
            EndKind::fromString($kind);
        } catch (\InvalidArgumentException $e) {
            $failure = $e;
        }
        $this->assertInstanceOf(\InvalidArgumentException::class, $failure, "'$kind' must not construct");
    }

    public function test_a_legacy_end_requires_attested_history(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 1));
        $legacy = EndKind::fromString('legacy');

        $decided = $h->member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), MemberHistory::decisionDate(), $legacy, MemberHistory::provenance());
        $attested = $h->member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), MemberHistory::decisionDate(), $legacy, Provenance::attested('legacy-members', 'import-1'));

        $this->assertSame('legacy_end_requires_attested_history', $decided->isAccepted() ? null : $decided->refusal()->value);
        $this->assertTrue($attested->isAccepted());
    }

    public function test_structural_end_guards_take_precedence_over_the_legacy_guard(): void
    {
        // Plan §4.1: … EndNotAfterPeriodStart → LegacyEndRequiresAttestedHistory → …
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 5));

        $d = $h->member->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A1'), TimePoint::of(5), MemberHistory::decisionDate(), EndKind::fromString('legacy'), MemberHistory::provenance());

        $this->assertSame('end_not_after_period_start', $d->refusal()->value);
    }
}
