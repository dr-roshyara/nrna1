<?php

namespace Tests\Unit\Contexts\Elections;

use App\Contexts\Elections\Domain\ValueObjects\AdmissionEvidence;
use App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement;
use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-213 C2 — VoterAdmissionPolicy: WHAT a registered voter must satisfy.
 *
 * ADR_20261004_1549 §2.2 inv. 5/11: the policy is election-owned, admission is
 * derived (policy + evidence), semantics are ALL-of, and an empty policy means
 * "no additional admission requirement" (C2-D1: OFF is the default).
 *
 * Pure domain test: no database, no container, no factories.
 */
final class VoterAdmissionPolicyTest extends TestCase
{
    public function test_none_requires_nothing(): void
    {
        $policy = VoterAdmissionPolicy::none();

        $this->assertTrue($policy->isEmpty());
        $this->assertSame([], $policy->requirements());
        $this->assertFalse($policy->requires(AdmissionRequirement::OfficerIdentityVerification));
    }

    public function test_an_empty_policy_admits_without_any_evidence(): void
    {
        $decision = VoterAdmissionPolicy::none()->isSatisfiedBy(AdmissionEvidence::none());

        $this->assertTrue($decision->isAdmitted());
        $this->assertSame([], $decision->missing());
    }

    public function test_identity_verification_without_evidence_is_not_admitted(): void
    {
        $policy = VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification);

        $decision = $policy->isSatisfiedBy(AdmissionEvidence::none());

        $this->assertFalse($decision->isAdmitted());
        $this->assertSame([AdmissionRequirement::OfficerIdentityVerification], $decision->missing());
    }

    public function test_identity_verification_with_matching_evidence_is_admitted(): void
    {
        $policy   = VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification);
        $evidence = AdmissionEvidence::satisfying(AdmissionRequirement::OfficerIdentityVerification);

        $decision = $policy->isSatisfiedBy($evidence);

        $this->assertTrue($decision->isAdmitted());
        $this->assertSame([], $decision->missing());
    }

    /** @return array<string, array{mixed}> */
    public static function absentPersistedValues(): array
    {
        return [
            'null (legacy row)'      => [null],
            'empty array'            => [[]],
            'empty requires list'    => [['requires' => []]],
        ];
    }

    /** @dataProvider absentPersistedValues */
    public function test_a_missing_or_empty_persisted_policy_means_no_requirement(mixed $persisted): void
    {
        $this->assertTrue(VoterAdmissionPolicy::fromPersistence($persisted)->isEmpty());
    }

    public function test_persisted_identity_verification_is_read_back(): void
    {
        $policy = VoterAdmissionPolicy::fromPersistence(['requires' => ['OFFICER_IDENTITY_VERIFICATION']]);

        $this->assertTrue($policy->requires(AdmissionRequirement::OfficerIdentityVerification));
    }

    public function test_an_unknown_requirement_code_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        VoterAdmissionPolicy::fromPersistence(['requires' => ['WHATSAPP_OTP']]);
    }

    public function test_duplicate_requirements_collapse(): void
    {
        $policy = VoterAdmissionPolicy::fromPersistence(
            ['requires' => ['OFFICER_IDENTITY_VERIFICATION', 'OFFICER_IDENTITY_VERIFICATION']]
        );

        $this->assertCount(1, $policy->requirements());
    }

    public function test_persistence_form_is_deterministic(): void
    {
        $this->assertSame(['requires' => []], VoterAdmissionPolicy::none()->toPersistence());
        $this->assertSame(
            ['requires' => ['OFFICER_IDENTITY_VERIFICATION']],
            VoterAdmissionPolicy::requiring(
                AdmissionRequirement::OfficerIdentityVerification,
                AdmissionRequirement::OfficerIdentityVerification,
            )->toPersistence()
        );
    }

    public function test_round_trip_preserves_the_policy(): void
    {
        $policy = VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification);

        $this->assertTrue($policy->equals(VoterAdmissionPolicy::fromPersistence($policy->toPersistence())));
    }

    public function test_equality_is_by_requirements(): void
    {
        $this->assertTrue(VoterAdmissionPolicy::none()->equals(VoterAdmissionPolicy::fromPersistence(null)));
        $this->assertFalse(VoterAdmissionPolicy::none()->equals(
            VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification)
        ));
    }
}
