<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Identity;

use App\Contexts\Identity\Application\AuthorityEvidence;
use App\Contexts\Identity\Application\AuthorityEvidenceCheck;
use App\Contexts\Identity\Application\ClaimRefusal;
use App\Contexts\Identity\Domain\ClaimCode;
use App\Contexts\Identity\Domain\ClaimWindow;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-349 step I-2 (I-TDD-02 §5, §6.3, §10.1; D-196, D-199): the pure rules of ordinary claims.
 * - window: the organisation's rules set it; silent → 14 days, labelled platform default (D-199); more than 30 →
 *   capped at 30 and the cap recorded;
 * - a code is shown once and only its hash is stored (INV-ID-7); the hash ignores case and separators;
 * - authority evidence (the complete Governance answer, by value) is accepted only if it corresponds to this call:
 *   organisation, actor = the session's own R (R-1), act kind manage-claims, subject, Authorised, evaluatedFor =
 *   the act's date, checked within this request. Identity never re-evaluates competence (PI-17a).
 */
final class ClaimRulesTest extends TestCase
{
    private const O = '11111111-1111-4111-8111-111111111111';

    public function test_the_claim_window_follows_d_199(): void
    {
        $silent = ClaimWindow::fromRules(null);
        $this->assertSame([14, 'platform-default', false], [$silent->days, $silent->source, $silent->capped]);
        $rules = ClaimWindow::fromRules(20);
        $this->assertSame([20, 'rules', false], [$rules->days, $rules->source, $rules->capped]);
        $long = ClaimWindow::fromRules(45);
        $this->assertSame([30, 'rules', true], [$long->days, $long->source, $long->capped], 'capped at the platform maximum, and recorded');
        $this->expectException(\InvalidArgumentException::class);
        ClaimWindow::fromRules(0);
    }

    public function test_a_code_is_normalised_before_hashing_and_the_hash_is_not_the_code(): void
    {
        $code = ClaimCode::fromString('ABCDE-FGHJK-MNPQR-STVWX');
        $this->assertSame($code->hash(), ClaimCode::fromString('abcde fghjk mnpqr stvwx')->hash(), 'case and separators do not matter');
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $code->hash());
        $this->assertStringNotContainsString('ABCDE', $code->hash());
        $this->expectException(\InvalidArgumentException::class);
        ClaimCode::fromString('too-short');
    }

    private static function ref(string $c): OrganisationPersonRef
    {
        return OrganisationPersonRef::fromString(str_repeat($c, 32));
    }

    /** @param array<string, mixed> $over */
    private static function evidence(array $over = []): AuthorityEvidence
    {
        $v = $over + [
            'organisation' => self::O, 'actor' => str_repeat('a', 32), 'actKind' => 'manage-claims',
            'subject' => ['entryRef' => 'e-1', 'personRef' => str_repeat('b', 32), 'issuanceId' => 'iss-1'],
            'requestId' => 'req-1', 'outcome' => 'authorised', 'evaluatedFor' => '2026-11-15',
            'checkedAt' => new \DateTimeImmutable('2026-11-15 10:00:01'), 'competenceKind' => 'asserted', 'basis' => ['ruleRef' => 'art. 7'],
        ];

        return new AuthorityEvidence(TenantId::fromString($v['organisation']), OrganisationPersonRef::fromString($v['actor']), $v['actKind'],
            $v['subject'], $v['requestId'], $v['outcome'], $v['evaluatedFor'], $v['checkedAt'], $v['competenceKind'], $v['basis']);
    }

    private static function check(AuthorityEvidence $e): ?ClaimRefusal
    {
        return (new AuthorityEvidenceCheck())->refusal($e, TenantId::fromString(self::O), self::ref('a'), 'manage-claims',
            ['entryRef' => 'e-1', 'personRef' => str_repeat('b', 32), 'issuanceId' => 'iss-1'],
            new \DateTimeImmutable('2026-11-15 10:00:00'), new \DateTimeImmutable('2026-11-15 10:00:02'));
    }

    public function test_corresponding_evidence_is_accepted(): void
    {
        $this->assertNull(self::check(self::evidence()));
    }

    public function test_evidence_that_does_not_correspond_is_refused_with_its_reason(): void
    {
        $cases = [
            'another organisation' => [['organisation' => '22222222-2222-4222-8222-222222222222'], ClaimRefusal::EvidenceOrganisation],
            'another officer (not the session)' => [['actor' => str_repeat('c', 32)], ClaimRefusal::EvidenceActor],
            'another act' => [['actKind' => 'send-newsletter'], ClaimRefusal::EvidenceAct],
            'another subject' => [['subject' => ['entryRef' => 'e-2', 'personRef' => str_repeat('b', 32), 'issuanceId' => 'iss-1']], ClaimRefusal::EvidenceSubject],
            'not authorised' => [['outcome' => 'not-authorised'], ClaimRefusal::EvidenceOutcome],
            'undetermined is not authorised' => [['outcome' => 'undetermined'], ClaimRefusal::EvidenceOutcome],
            'evaluated for another date' => [['evaluatedFor' => '2026-11-14'], ClaimRefusal::EvidenceDate],
            'checked before this request' => [['checkedAt' => new \DateTimeImmutable('2026-11-15 09:59:59')], ClaimRefusal::EvidenceStale],
            'checked in the future' => [['checkedAt' => new \DateTimeImmutable('2026-11-15 10:00:03')], ClaimRefusal::EvidenceStale],
        ];
        foreach ($cases as $name => [$over, $reason]) {
            $this->assertSame($reason, self::check(self::evidence($over)), $name);
        }
    }

    public function test_every_refusal_has_one_neutral_public_message(): void
    {
        $messages = array_unique(array_map(static fn (ClaimRefusal $r): string => $r->publicMessage(), ClaimRefusal::cases()));
        $this->assertCount(1, $messages, 'one message for all claim failures (I-TDD-02 §12)');
    }
}
