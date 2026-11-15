<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Why a decision was refused. Every case names one contract rule.
 */
enum RefusalReason: string
{
    /** Guard: a fact id must be unique within the Member (premise of contract Theorem 1). */
    case DuplicateFactId = 'duplicate_fact_id';

    /** Guard (contract §17): an initial term must end after the admission. */
    case InvalidInitialTerm = 'invalid_initial_term';

    /** Guard (contract §18): an End targets a period of this Member. */
    case PeriodNotFound = 'period_not_found';

    /** Guard (contract §18): an End targets an open period; changing an end date is Correct(End), Slice 7. */
    case PeriodAlreadyEnded = 'period_already_ended';

    /** Guard (contract §18, C-S0-1): an End must take effect after the period start. */
    case EndNotAfterPeriodStart = 'end_not_after_period_start';

    /** INV-9 (BD-I5): membership periods of one Member never overlap. */
    case OverlappingMembershipPeriods = 'overlapping_membership_periods';

    /** INV-EP: a period's lifetime is one contiguous episode. */
    case PeriodLifetimeNotContiguous = 'period_lifetime_not_contiguous';

    /** INV-COV: consequences may move the owner of an instant, never remove membership coverage. */
    case MembershipCoverageLost = 'membership_coverage_lost';

    /** Guard (PO condition P-3): an Annul / Void targets a fact recorded on this Member. */
    case TargetNotFound = 'target_not_found';

    /** Guard (D-COR-2, P-3): an Annul / Void targets a live fact. Fact liveness, not period activity. */
    case TargetNotLive = 'target_not_live';

    /** Guard (PO condition P-2): an Annul carries its decision instant; the current time is never substituted. */
    case DecidedAtRequired = 'decided_at_required';

    /** Guard (correction contract N-3): a Void cannot be annulled; it is restored by Void(Void). */
    case AnnulOfVoidRefused = 'annul_of_void_refused';

    /** Guard (correction contract §3.1, R-2): Annul applies only to facts with decision provenance. */
    case TargetHasNoDecisionProvenance = 'target_has_no_decision_provenance';

    /** Guard (correction contract §3.1, BR-9a; V-2): the Annul itself is a decision and carries decision provenance. */
    case AnnulRequiresDecisionProvenance = 'annul_requires_decision_provenance';

    /** Guard (D-1 = C1): an Admit decided by an actor carries a declaration reference (one recognised basis). */
    case AdmissionBasisMissing = 'admission_basis_missing';

    /** Command boundary (D-3 = E1): the requested ending act is not one of the closed set. */
    case EndKindNotRecognised = 'end_kind_not_recognised';

    /**
     * Command boundary (D-208 §2, C-1b; D-238): an exclusion is pushed by Governance with its Resolution part (S2-6);
     * no office exercises `exclude-member`, so it is never requested by pull.
     */
    case ExclusionIsPushOnly = 'exclusion_is_push_only';

    /** Command boundary (D-208 §2, GC-4; S2-3b): a death is recorded only with a cited evidence reference. */
    case DeathEvidenceRequired = 'death_evidence_required';

    /** Guard (D-201; S2-4a): a scheduled resignation's date is after its declaration (the same day is "resign now"). */
    case ScheduledDateNotAfterDeclaration = 'scheduled_date_not_after_declaration';

    /** Guard (M-TDD-01 §5.2; S2-4a): one scheduled resignation declaration per period. */
    case ScheduledResignationAlreadyPending = 'scheduled_resignation_already_pending';

    /** Guard (S2-4a): a declaration id is used once per member. */
    case DuplicateDeclarationId = 'duplicate_declaration_id';

    /** Guard (M-TDD-01 §5.2; S2-4a): only a `scheduled` declaration can be withdrawn, superseded or executed. */
    case DeclarationNotScheduled = 'declaration_not_scheduled';

    /** Guard (D-201, D-223; S2-4a): withdrawal is refused once the effective date has started. */
    case WithdrawalAfterEffectiveDate = 'withdrawal_after_effective_date';

    /** Guard (D-205 (1), D-223; MI-15c): a scheduled resignation is never recorded before its effective date. */
    case ExecutionBeforeEffectiveDate = 'execution_before_effective_date';

    /** Guard (D-3 = E1): a `legacy` End is reserved for attested migration history. */
    case LegacyEndRequiresAttestedHistory = 'legacy_end_requires_attested_history';

    /** Guard (D-5(b)): an Admit or End may not take effect after its caller-supplied decision date. */
    case EffectiveDateAfterDecisionDate = 'effective_date_after_decision_date';

    /** Guard (correction contract §10a Correct (3), N-3): an Annul or a Void carries no date to correct. */
    case CorrectionOfAnnulOrVoidRefused = 'correction_of_annul_or_void_refused';

    /** Guard (correction contract §10a Correct (4); P-1): the target, or the root of its chain, is voided. */
    case CorrectOfVoidedFact = 'correct_of_voided_fact';

    /** Guard (P-3): the corrected date equals the target's current value. */
    case ValueUnchanged = 'value_unchanged';

    /** Guard (BD-REV-ER = refuse): a correction may not leave a live admission with an empty lifetime. */
    case PeriodAbsorbed = 'period_absorbed';

    /** Guard (D-177 §4, row 6): no period of this Member has started by the renewal's effective date. */
    case NoPeriodAt = 'no_period_at';

    /**
     * Guard (D-177 §4, row 5): the period that had started by the effective date is formally ended at it; a new
     * Admit is needed. Distinct from PeriodAlreadyEnded, which refuses a second End on a period.
     */
    case PeriodEnded = 'period_ended';

    /** Guard (D-177 §4): a renewed term must end after it starts. */
    case EmptySegment = 'empty_segment';

    /** Command boundary (D-163, D-177 §3): a release-1 admission states its term, finite or open-ended. */
    case AdmissionTermRequired = 'admission_term_required';

    /** Command boundary (D-177 §4): a renewal states its term, an end date or open-ended; never assumed unlimited. */
    case RenewalTermRequired = 'renewal_term_required';

    /** Guard (D-177 §4, §16 C-2; ratified REQ-MEM-SUSPENSION): Suspend and Restore act only while the period is in force. */
    case NotInForce = 'not_in_force';

    /** Guard (D-177 §4): a restriction must end after it starts. */
    case EmptyRestriction = 'empty_restriction';

    /** Guard (D-177 §4): a Restore names a live Suspend fact of this Member. */
    case NotSuspended = 'not_suspended';

    /** Guard (D-177 §4, row 14): a suspension has at most one live Restore. */
    case AlreadyRestored = 'already_restored';

    /** Guard (D-177 §4, row 12): a Restore takes effect strictly inside the restriction it ends. */
    case OutsideRestriction = 'outside_restriction';

    /** Guard (S2-5 not yet built): D-177 row 14 lets a Correct change a Suspend's or Restore's date; refused until then. */
    case CorrectionOfSuspendOrRestoreNotSupported = 'correction_of_suspend_or_restore_not_supported';

    /**
     * Guard (PBDIGIT-354 Q-2, open): D-177 row 20 lets a Correct change a Renew's `until`, but no accepted text gives
     * the dating rule for a corrected `until`. Refused until decided, so the kernel stays total and fail-closed.
     */
    case CorrectionOfRenewNotSupported = 'correction_of_renew_not_supported';
}
