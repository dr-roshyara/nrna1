# 09: V-2: an Annul must itself carry decision provenance

**Story:** `PBDIGIT-268` (C-1). The fix was authorised by the PO on 2026-11-02 inside this story; story draft MEM-K1.
**Contract:** `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` §3.1, amendment **C-AMEND-2** (additive, adopted by the PO 2026-11-02).

## Purpose

An Annul sets aside an earlier **decision**. The Annul is itself a decision: §3.1's resulting fact is `Annul{factId, target, provenance(decision, authority, reason), decidedAt}` (BR-9a).

**The gap (V-2):**
- Before this fix, `decideAnnul` checked only the **target's** provenance.
- So an Annul with `rule` or `attested` provenance was accepted.
- Such an Annul has no deciding actor, so it could never be appealed (an Annul of an Annul needs a decision target).

## Where it fits

- Domain layer, pure PHP: `App\Contexts\Membership\Domain\Organisational`.
- No application, infrastructure or schema change. Annul is still kernel-only (C-3 and C-4 are not built).

## Key files

| File | Change |
|---|---|
| `app/Contexts/Membership/Domain/Organisational/RefusalReason.php` | new case `AnnulRequiresDecisionProvenance` (`'annul_requires_decision_provenance'`) |
| `app/Contexts/Membership/Domain/Organisational/Member.php` | one guard in `decideAnnul` |
| `tests/Unit/Contexts/Membership/Domain/Organisational/AnnulVoidTest.php` | three tests (below) |

## Design decisions (PO, 2026-11-02, as proposed in MEM-K1)

1. **Refusal name:** `AnnulRequiresDecisionProvenance`.
2. **Guard position:**
   - after `DuplicateFactId`, `TargetNotFound` and `DecidedAtRequired`;
   - before the target rules (`AnnulOfVoidRefused`, `TargetHasNoDecisionProvenance`, `TargetNotLive`).
   - The new guard is about the command itself, so it fails before anything about the target is judged.
3. **Void is out of scope:** the contract places no provenance restriction on the Void itself.

## How it works

```php
if ($decidedAt === null) {
    return Decision::refused(RefusalReason::DecidedAtRequired);
}
// V-2 (CC §3.1, BR-9a): the Annul is itself a decision; rule or attested provenance cannot set a decision aside.
if ($provenance->kind() !== ProvenanceKind::Decision) {
    return Decision::refused(RefusalReason::AnnulRequiresDecisionProvenance);
}
if ($targetFact instanceof VoidFact) {
    return Decision::refused(RefusalReason::AnnulOfVoidRefused);
}
```

The full guard order is in guide 04 ("Guard order").

## Testing

**Run (database-free):**
```bash
vendor/bin/phpunit --do-not-cache-result tests/Unit/Contexts/Membership/Domain/Organisational/AnnulVoidTest.php
```

| Test | Shows |
|---|---|
| `test_an_annul_without_decision_provenance_is_refused_and_changes_nothing` | `attested` and `rule` Annuls are refused with the new reason; version, log and resolution unchanged |
| `test_an_annul_with_decision_provenance_is_still_accepted` | characterisation: a decision Annul behaves as before (K-A1) |
| `test_annul_provenance_guard_order` | the three earlier guards win; the new guard wins over `AnnulOfVoidRefused`, `TargetNotLive` and `TargetHasNoDecisionProvenance` |

**RED was recorded before GREEN (2026-11-02):**
1. First the reason did not exist.
2. Then, with the reason present but no guard, both behavioural tests failed: the Annuls were accepted.

**Regression after GREEN:**
- the Membership Domain suite: 117 tests, including `C1ExhaustiveDifferentialTest` and `G6CorrectionOracleGoldenTest`, unchanged because the oracle uses decision provenance throughout;
- Application (61) and Infrastructure (13) suites;
- all seven `tests/Architecture/Membership*` tests.

## Pitfalls

- **Do not confuse the two provenance checks.** `TargetHasNoDecisionProvenance` is about the **target**; `AnnulRequiresDecisionProvenance` is about the **Annul**. A test meant for the target rule must give the Annul decision provenance (the `MemberHistory::annul` default does).
- When C-3 adds an Annul command, the handler must pass the caller's decision provenance through unchanged. It must not build `rule` or `attested` provenance for an Annul.

## Traceability

V-2 (`…/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md` §3) → MEM-K1 (`…_2257_membership_tdd_backlog_story_drafts.md:57`) → PO authorisation 2026-11-02 (dispositions record §1) → C-AMEND-2 → this guide.
