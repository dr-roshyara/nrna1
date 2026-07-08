# Election — Developer Guide

**Audience:** engineers working in the Election bounded context (greenfield Core, with Contestation + Adjudication).

Election owns the **`Election`** aggregate — the part of an election that **reacts** to a binding `Determination` and **decides** how the election is corrected. Election is a *downstream* context in the correction loop: it consumes the restricted integration event **`DeterminationIssued`** (payload schema version 2) and, when the ruling is binding, records **`ElectionCorrectionApplied`**. It never provisions elections and never reverses history.

## Guides (per step)
| Guide | Covers |
|-------|--------|
| [`01_election_reaction.md`](./01_election_reaction.md) | The Election **reaction slice** (PB-004 GREEN): `DeterminationIssuedReactionHandler` → `Election::applyDetermination()` → `ElectionCorrectionApplied`, plus the `ElectionCorrectionPolicy`, the tenant-free repository boundary, and the three business rulings (schema-v1 incompatibility · unknown election · cross-organisation). |

## Authoritative architecture
- Ubiquitous language: `docs/adr/ADR-UL-01-ContestedOutcome.md`.
- Published language consumed: `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` + `docs/implementation/Event_Registry.md`.
- Correction loop: ADR-T8 (forward-only, five causally-linked transactions — never a saga) · Canonical Event Catalog 50-05 (`ElectionCorrectionApplied`) · D-02 (Dismissed ⇒ no correction).

## Invariants to preserve
- **Forward-only (ADR-T8/T11):** the Election is never rolled back and votes are never un-cast or exposed. The only correction is `ContainedOnly`; the aggregate exposes no reverse/rescind operation.
- **Anonymity (ADR-T11):** `ElectionCorrectionApplied` carries only Election-owned identity — no `ChallengeId` (a Contestation concept), no voter↔vote linkage.
- **Bounded-context autonomy (ADR-T16):** Election reconstructs its **own** local `ElectionId`/`DeterminationId` from wire strings — it does not import Contestation's or Adjudication's.
- **Tenant-free domain (ADR-T16):** the aggregate holds no `OrganisationId`; organisation scope is applied at the repository/infrastructure boundary, so a cross-organisation determination resolves to "unknown".
- **Idempotent:** one correction per determination — across message re-delivery *and* across reload (semantic duplicate).
