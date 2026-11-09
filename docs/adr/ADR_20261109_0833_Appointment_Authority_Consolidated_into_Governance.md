# ADR — Appointment (Authority) consolidated into Governance (BDR-03 v1.2)

| | |
|---|---|
| **Status** | ✅ **ACCEPTED — ruling by the Principal as human ARB Chief, 2026-11-09** ("Yes, I accept the BDR-03 v1.2 act"). Recorded as D-72. Register amended append-only (`docs/architecture/design/Round49-06_Boundary_Decision_Register.md`: Boundary Evolution History row and BDR v1.2 delta) |
| **Class** | Boundary decision: versioned re-issue of BDR-03, as Round 49-06 requires ("changes only by a versioned re-issue (BDR v1.x / v2.0) via ADR") |
| **Supersedes** | BDR-03 v1.0 *as a boundary decision only*. The v1.0 row stays unchanged in the register |
| **Decision drivers** | Working decisions D-4, D-5, D-38, D-39, D-68 (`docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` §8.1, §11.1) |

## Decision

> **Appointment (Authority) is not a separate bounded context. It is a capability within Governance.**
>
> Governance owns the **authoritative facts** about positions, tenures, delegations (AuthorityAssignment) and effective powers. **Mandate events are derived from those facts.** Governance's Appointment capability is responsible for **deriving and publishing** the integration events `MandateGranted` and `MandateRevoked`.
>
> **Authorization** remains a separate context: it consumes mandates and owns the evaluation of whether an actor may perform an operation. It owns no authority facts (BDR-07).

## Context

- **BDR-03 v1.0** (2026-06-26) confirmed "Appointment (Authority)" as **Confirmed BC — Operational**, owning "Mandate truth (lifecycle+status+policy)", with **Medium** evidence sufficiency (`Round49-06_Boundary_Decision_Register.md:16`).
- Later discovery established that authority is held **through positions and their tenures, and through delegations**, all owned by Governance (D-4, D-39), and that a person's **effective powers** on a date are worked out from rule grants, restrictions, recusals and delegation (D-38, D-69).
- Keeping Appointment as its own bounded context would split **one invariant**, a person's effective authority on a date and the mandate derived from it, across two contexts.

## Relationship to the Mandate aggregate question (deliberately not decided here)

- Round 50-01 designs **`AGG — Mandate`** inside the Appointment BC (`Round50-01_Aggregate_Discovery.md:30-36`), as part of Release 1.0, which is **FROZEN** (`Round50-09_Architecture_Decision_Verification.md:38`).
- Round 50-02 leaves its **merge test OPEN** ("NOT finalized — resolve before Mandate implementation", `Round50-02_Aggregate_Review.md:26`; also `:58`).
- Working decision **D-7** (direct, 2026-11-08) proposes **no Mandate aggregate**: authority held through Position and its tenures, mandate events derived. **D-7 is not yet an ARB act.** Superseding Round 50-01 needs its own ADR.
- **This act does not depend on that question.** Whatever the aggregate design, it lies **inside Governance**: either a Mandate aggregate within Governance's Appointment capability (Round 50-01 as frozen), or derivation from Position and tenures (D-7, if later accepted). This ADR therefore makes **no claim** that a Mandate aggregate does or does not exist.

## Consequences

1. **Round 50-05 is not changed.** Its producer "Appointment" reads as Governance's Appointment capability. Event fields (`mandateId · holderId · scope · termRef · grantedAt`; `mandateId · reason · revokedAt`), delivery (at least once, idempotent), ordering (granted before revoked per `mandateId`), replay rules and consumer sets (**Authorization and Audit; not Voting, not Adjudication**) are unchanged.
2. **R-11 and D-68 are not changed:** `termRef` identifies the basis of a mandate, a tenure or a delegation; Governance revokes when the basis or the delegator's supporting powers end.
3. **BDR-07 (Authorization) is not changed.**
4. **Audit** keeps its consumer role for mandate events; its precedence question (X-10) is separate.
5. **Evidence EV-050 and EV-051** are read as evidence for Governance's Appointment capability.
6. **Unaffected:** ratified Membership architecture, Adjudication boundaries (D-67), EPIC-002 contexts.
7. **Not part of this act:** the ADR on Round 50-01 / D-7; the Context Map update (under change control); the Membership Change Request (D-25).

## On acceptance (append-only; the v1.0 table is not edited)

**Boundary Evolution History row:**

| Date | BDR | From → To | Reason |
|------|-----|-----------|--------|
| *(date of ruling)* | BDR-03 | Confirmed BC — Operational → **capability within Governance** → **BDR v1.2** | authority held through Governance positions, tenures and delegations; one invariant for effective authority (this ADR; D-4) |

**BDR v1.2 delta** (after the v1.1 delta):
- **Decision:** Appointment (Authority) is a **capability within Governance**, not a separate bounded context. Governance owns the authoritative facts (positions, tenures, delegations, effective powers) and, through its Appointment capability, derives and publishes `MandateGranted` / `MandateRevoked`.
- **Basis:** this ADR; working decisions D-4, D-5, D-38, D-39, D-68.
- **Unchanged:** Round 50-05 event definitions and consumer sets; BDR-07; the Mandate aggregate question (Round 50-01 / Round 50-02 / D-7) remains for its own ADR.

**Comparison with BDR-03 v1.0:**

| | v1.0 | v1.2 |
|---|---|---|
| Classification | Confirmed BC — Operational | Capability within Governance |
| Owns | "Mandate truth (lifecycle+status+policy)" | Governance owns the authoritative facts; the capability derives and publishes mandate events |
| Mandate events | Produced by Appointment | Produced by Governance's Appointment capability (same events, same consumers) |
| Mandate aggregate | `AGG — Mandate` (Round 50-01) | Not decided here |

## Reversal condition

Revisit only if appointment develops a lifecycle or policy independent of Governance's positions, tenures and delegations (for example, appointment rules owned outside the organisation's governance).
