# 08: Slice 2a — Admit / End command rules

**Story:** `PBDIGIT-271` · **Plan:** `docs/plans/20261011-0010-membership-slice2-command-rules-plan.md` (revision 5) · **Decisions:** D-1 … D-5 (decision record in `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`), S2-A … S2-G (plan §12) · **Contracts:** Slice 0 and Slice 1a amendments of 2026-10-11 · **Evidence:** `docs/publicdigit/membership/evidence/2026-10-11-slice2a/`, hardening `…/2026-10-11-slice2a-hardening/`

## Purpose

Slice 2a turns the ratified business decisions into enforced rules on the existing Admit / End decisions and commands, **without a database**. The new ledger stays non-authoritative: no container binding, no HTTP / UI, no caller outside tests (Slice 2b and the D-6 / R10 cutover are separate, held gates).

## The rules

| Rule | Where | Refusal |
|---|---|---|
| **D-1:** every admission has exactly one recognised basis. A decision needs a declaration reference; `rule` (statutory) and `attested` (migration) are bases in themselves | `Member::decideAdmit` | `admission_basis_missing` |
| **D-3:** ending kinds are a closed set (`resignation`, `exclusion`, `death`, `legacy`); no other value can be constructed | `EndKind` | unknown kind → `end_kind_not_recognised` (End handler) |
| **D-3:** `legacy` is reserved for attested migration history | `Member::decideEnd` | `legacy_end_requires_attested_history` |
| **D-5:** the caller supplies the decision date; the effective date may not lie after it; backdating stays subject to the existing invariants | `decideAdmit` / `decideEnd` (required `TimePoint $decisionDate`) | `effective_date_after_decision_date` |
| **D-4 (A1):** interim authority. Admit = owner / admin (mirrors the legacy `approveApplication`); **every End denied** until G-1 | `Infrastructure/Organisational/InterimRoleAuthority` | `NotAuthorised` |

**Invalid input is not a refusal (hardening H1, 2026-10-11).** `decideAdmit` / `decideEnd` first reject an infinite effective date or decision date with `InvalidArgumentException` (`Member::requireFiniteDates`), before any refusal. A decision date is a calendar date, and an Admit / End takes effect at a finite time; `TimePoint` keeps ∞ only for open-ended terms. The commands carry plain `int`s, so this cannot be reached through them. Three kinds of "no", kept apart:
- **invalid input** → an exception; nothing decided, appended or remembered;
- **a valid command refused by a business rule** → a domain `RefusalReason`;
- **a technical persistence refusal** → a port outcome, such as `ValidTimeNotStorable` (R-1a-6).

**Guard order** (decides which reason is reported when several rules fail):
- **Admit:** duplicate fact id → invalid initial term → admission basis → decision date → G\*.
- **End:** duplicate fact id → period not found → period already ended → end not after start → legacy → decision date.

## How it works

```php
// Member::decideAdmit — after the structural guards, before G*
if ($provenance->kind() === ProvenanceKind::Decision && $provenance->declarationRef() === null) {
    return Decision::refused(RefusalReason::AdmissionBasisMissing);
}
if ($effectiveAt->isAfter($decisionDate)) {
    return Decision::refused(RefusalReason::EffectiveDateAfterDecisionDate);
}
```

```php
// EndMembershipHandler — inside the decide step, so IDEM-1's re-check and IDEM-4 still apply
$kind = EndKind::tryFromString($command->endKind);
if ($kind === null) {
    return Decision::refused(RefusalReason::EndKindNotRecognised);
}
```

**Commands:** `AdmitMember` / `EndMembership` take a required `int $decisionDate` right after `effectiveAt`. It is in the fingerprint. "Today" is only a UI default, computed in the organisation's time zone; the domain never sees a clock or a time zone. The decision date is validated, **not stored** (Q-E1 = A; follow-up AUD-1 decides audit evidence later).

## Fitness tests (source-architecture guards, not runtime security)

| Test | Rule | How detection is proven |
|---|---|---|
| `tests/Architecture/MembershipLedgerIsolationTest` | R5: nothing outside Membership references the new ledger (allowlist: the migration creating it) | planted dependencies in fixture strings are reported exactly |
| `tests/Architecture/MembershipDecisionCreationTest` | R7: `Decision::accepted` only in `Member`; `new Admit` / `new End` only in `Member` and the rehydration decoder. R8: `Provenance::rule` / `::attested` only in `ProvenanceJson` | a planted matrix: 23 written forms detected, 7 permitted forms not reported, the allowlist respected, a parse failure fails the scan |

Shared scanner: `tests/Architecture/Support/MembershipSourceScan` (pure; no Laravel boot).
- `violations()` is a literal text scan; R5 uses it, because table names live in strings.
- `creationViolations()` (hardening H2) parses with nikic/php-parser and its `NameResolver`, so R7 / R8 see through imports, aliases, grouped imports, qualified and `namespace\` names, `self::` / `static::` / `parent::`, whitespace and PHP's case-insensitivity, and ignore comments and strings.
- **Not seen (by design):** dynamic class names (`new $c`, `$c::accepted()`), callable arrays / strings (`[Decision::class, 'accepted']`, `call_user_func`), reflection, `class_alias`, `eval`, `unserialize`, code outside `app/`; for R5, table names built at runtime.
- **Named exclusion:** `app/Http/Middleware/VoterSlugStep.php` is not valid PHP (`PBDIGIT-272`). A guard test fails as soon as it parses, so the exclusion is removed then.

## How to extend

- **Migration (Slice 13) or a statutory-admission capability (after LQ-1):** add its provenance-construction file to the R8 allowlist in its own reviewed, authorised change. Never widen the ordinary `AdmitMember` command.
- **A new ending kind:** a PO / legal decision first; then the `EndKind` constant and its tests.
- **Ending authority:** only after Governance decides G-1; then replace the End branch of the interim adapter (or the whole adapter, under DS-4).

## Testing

```bash
vendor/bin/phpunit tests/Unit/Contexts/Membership/Domain/Organisational
vendor/bin/phpunit tests/Unit/Contexts/Membership/Application/Organisational
vendor/bin/phpunit tests/Unit/Contexts/Membership/Infrastructure/Organisational
vendor/bin/phpunit tests/Architecture/MembershipLedgerIsolationTest.php tests/Architecture/MembershipDecisionCreationTest.php
```

New tests: `AdmissionBasisTest`, `EndKindTest`, `FutureDatingTest` (Domain); handler tests for D-1 / D-3 / D-5 / IDEM-4; `InterimRoleAuthorityTest`; the two fitness tests.

## Pitfalls

- **Fixtures must satisfy D-1 and D-5.** Admission fixtures carry `decl-fixture` / `decl-property`; history generators use a far decision date (`MemberHistory::decisionDate()` = 1 000 000, translated by the shift in `DualHistory`). Without them, C-1 diverges at once: 5 depth-1 mismatches, as recorded in the evidence. That shows the comparison catches a kernel change the oracle does not share.
- **Never "fix" C-1 or G6 expectations.** The G6 hash covers only the independent oracle's output; the C-1 count (15 530) depends only on accept / refuse outcomes.
- **The infrastructure vocabulary rule** (`MembershipSlice1bPersistenceBoundaryTest`) forbids some context words in infrastructure *code* (comments are stripped): keep them out of identifiers and strings.
- **Held PostgreSQL test files** were edited for the new signatures only; they run in Part 2, not before.
- **Retries must resend the same decision date.** The decision date is part of the idempotency fingerprint. A retry under the same key must carry the original value. A client that recomputes a "today" default on a later day sends a different command, and gets `IdempotencyKeyConflict` (IDEM-2, unchanged) instead of its original result. Store the decision date with the pending request, next to its key.
- **The creation-path scan fails on a file it cannot parse.** Fix the file, or, only with a PO decision and a backlog item, add it to `UNPARSABLE_KNOWN`. Never make the scan skip parse failures.

**Traceability:** D-1 … D-5 · S2-A … S2-G · R1–R8 · AC-1 … AC-10 (plan §10) · hardening H1 / H1-b / H2 (plan rev. 5, §15) · `PBDIGIT-272`. **Status:** implemented and hardened; committed 2026-10-11 (`834a4f65c`, `14f89d18e`), not pushed; PostgreSQL unverified (held); not formally accepted.
