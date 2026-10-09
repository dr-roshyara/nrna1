# Plan: Membership Slice 0, TDD implementation (PBDIGIT-258)

**Status:** 🟢 APPROVED by the PO's instruction of 2026-10-09 (Slice 0 only) · **Created:** 2026-10-09 · **Story:** `PBDIGIT-258`
**Governing documents:** the Slice 0 contract (`docs/publicdigit/reviews/2026-10-08-membership-slice0-semantic-design-contract.md`), EP-01 Rev 1, the ratified architecture.
**Session-bootstrap observation:**
- `php .claude/scripts/session-bootstrap.php` reports UNRESOLVABLE / UNRESOLVED.
- Its records are KnowledgeOS work items only (`.claude/runtime/workflow/KOS-*`); PublicDigit stories are not tracked there.
- **Authority for this work:** the PO's explicit instruction. No other process identity is adopted.

## Objective

Implement the Slice 0 semantic kernel exactly as contracted, RED before GREEN, with independent verification.

## Design decisions (from the contract; implementation choices marked)

- **Namespace:** `App\Contexts\Membership\Domain\Organisational`. This is EP-01 §5 (collision C-1 / C-2 with the legacy `Member` and `Membership*`).
- **Time:** `TimePoint` (valid time; an integer ordinal or ∞) and `Instant` (decided / recorded; an integer ordinal) are **separate types**, so the coordinates cannot be collapsed. Granularity stays agnostic (EP-Q6). *Implementation choice.*
- **Decisions:** `Member::decideAdmit(...)` / `decideEnd(...)` return `Decision` (Accepted(fact, basedOnVersion) | Refused(reason)). Ids are supplied by the caller; there is no clock or randomness.
- **The lifetime representation is an `IntervalSet`,** so that INV-EP and INV-COV are real checks rather than type tautologies. *Implementation choice.*
- **Resolution totality on logs that are unreachable through `decide`:**
  - a second End on a period: the latest by seq wins;
  - an End on an unknown period: ignored.

  *Implementation choice; noted in the review.*
- **Guard `DuplicateFactId`:** Theorem 1 presupposes unique FactIds. *A contract clarification, recorded as a deviation note.*
- **No generic frameworks:** no AggregateRoot, event bus, specification framework or state machine.

## Task checklist (RED → GREEN per behaviour)

- [ ] interval algebra (`Interval`, `IntervalSet`), with `TimePoint` / `Instant`
- [ ] identity and provenance value objects
- [ ] Admit decision + identity + term cases A / B / C
- [ ] End decision (C-S0-1), with INV-2 and INV-3
- [ ] Resolution predicates, re-entry, gap preservation
- [ ] InvariantSet (INV-EP, INV-9, INV-COV) + G\* ordering
- [ ] KnownAt
- [ ] architecture purity + non-circularity test
- [ ] independent pointwise oracle + exhaustive bounded test + differential counts
- [ ] seeded property tests + metamorphic tests
- [ ] developer guide `developer_guide/membership/` + implementation review

## Progress

- 2026-10-09: plan created.

## Risks

- Contract ambiguity discovered during TDD: STOP and classify (instruction §28).
- Over-building: the minimality audit in the review.

## Next actions

Implement per the checklist; STOP after the implementation review. Slice 1a is not started.
