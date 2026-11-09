# ADR — Supersede Round 50-01 `AGG — Mandate`: authority held through Position, tenure and AuthorityAssignment

| | |
|---|---|
| **Status** | ✅ **ACCEPTED — ruling by the Principal as human ARB Chief, 2026-11-09** ("Yes, I accept superseding the Mandate aggregate"). Recorded as D-74. Supersession notes appended to Round 50-01 and Round 50-02; their original text is unchanged |
| **Class** | Tactical boundary decision: supersedes one aggregate in the frozen Release 1.0 (`docs/architecture/design/Round50-09_Architecture_Decision_Verification.md:38`) |
| **Formalises** | Working decision **D-7** (direct, 2026-11-08: "Yes, I accept the Mandate merge test resolution"), discovery record §8.1 |
| **Depends on** | D-72 (BDR-03 v1.2: Appointment within Governance), D-68 (mandate basis: tenure or delegation), D-38, D-39 |

## Decision

> **There is no `Mandate` aggregate.** Authority is held through **Position (with its tenures and restrictions)** and **AuthorityAssignment (delegation)**, both Governance aggregates. **`MandateGranted` and `MandateRevoked` are integration events derived from them** by Governance's Appointment capability; their definitions in Round 50-05 are unchanged.
>
> The **Round 50-02 merge test** ("does `Mandate` own Committee Membership…", OPEN) is **closed**: neither. Committee composition is a derived read model (composition of an organ on a date); Election committees are Election's (R-6c).

## Context

- Round 50-01 designs `AGG — Mandate` in the Appointment BC with root `Mandate` holding `DelegationStatus`, events `MandateGranted` / `MandateRevoked`, `GrantMandate` / `RevokeMandate` services (`Round50-01_Aggregate_Discovery.md:30-36`).
- Round 50-02 leaves its merge test **OPEN — "NOT finalized — resolve before Mandate implementation"** (`Round50-02_Aggregate_Review.md:26, 58`).
- Since then: Appointment is a capability within Governance (D-72); a mandate's basis is a tenure or a delegation (D-68); powers come from rule grants with restrictions and delegation (D-38, D-69).
- A `Mandate` aggregate would hold a **second lifecycle** of the same authority already held by Position (tenure start, end, restriction) and AuthorityAssignment (delegation start, end, revocation): two sources of truth for one fact.

## Consequences

1. **Round 50-05 unchanged:** event fields, delivery, ordering ("granted before revoked per `mandateId`"), replay rules and consumers (Authorization, Audit; not Voting, Adjudication).
2. `mandateId` identifies **basis × powers version** (model document §20.3). Its "lifecycle" is the basis's lifecycle; there is no `REVOKED → ACTIVE` transition to guard because a re-grant is a new `mandateId` (the fitness test in Round 50-02 is satisfied structurally).
3. `GrantMandate` / `RevokeMandate` become **derivations** inside Governance, triggered by tenure, delegation, restriction and rule-version changes, including the revocation cascade.
4. Authorization (BDR-07) unchanged: it consumes mandates and owns no authority facts.
5. Round 50-01 and Round 50-02 keep their original text; a supersession note is appended to each on acceptance.

## On acceptance (append-only)

- Round 50-01, after `AGG — Mandate`: "**Superseded** (this ADR, D-7, date): no Mandate aggregate; authority through Position and AuthorityAssignment; events derived."
- Round 50-02, after the merge test: "**Closed** (this ADR): neither; composition is a read model; Election committees are Election's."

## Reversal condition

Revisit only if a mandate acquires a lifecycle or policy that is not a function of its basis (for example, mandates granted by an external authority not modelled as a Governance position or delegation).
