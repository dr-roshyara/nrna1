# Election — Developer Guide

**Audience:** engineers working in the Election bounded context (greenfield Core, with Contestation + Adjudication).

Election owns the **`Election`** aggregate — the part of an election that **reacts** to a binding `Determination` and **decides** how the election is corrected. Election is a *downstream* context in the correction loop: it consumes the restricted integration event **`DeterminationIssued`** (payload schema version 2) and, when the ruling is binding, records **`ElectionCorrectionApplied`**. It never provisions elections and never reverses history.

## Guides (per step)
| Guide | Covers |
|-------|--------|
| [`01_election_reaction.md`](./01_election_reaction.md) | The Election **reaction slice** (PB-004 Step 3 + 4A.1): `DeterminationIssuedReactionHandler` → `Election::applyDetermination()` → `ElectionCorrectionApplied`, the `ElectionCorrectionPolicy`, the tenant-free repository boundary, the three business rulings (schema-v1 incompatibility · unknown election · cross-organisation), and `appliedAt` as the application timestamp (injected clock). |
| [`02_infrastructure_existence_and_reaction_store.md`](./02_infrastructure_existence_and_reaction_store.md) | The **Infrastructure slice** (PB-004 Step 4A.3): the composite `ElectionRepository` reconstructing the aggregate from **existence** (legacy `elections` via a read-only ACL, Strangler) **+** **reaction state** (greenfield idempotency ledger); the `ElectionExistencePort` seam; and the business-absence-≠-infrastructure-failure rule. |
| [`03_messaging_integration.md`](./03_messaging_integration.md) | The **Messaging slice** (PB-004 Step 4B): the `ReactionOutboxAdapter` (writes `ElectionCorrectionApplied` to the shared outbox, atomically via the inbox transaction), the inbox-registry wiring for `DeterminationIssuedReactionHandler`, and the `ElectionCorrectionAppliedHydrator` (payload↔domain event; schema-version rejection). |
| [`06_evidence_preservation_durations.md`](./06_evidence_preservation_durations.md) | **WP-7 slice 7A (Policy 2 · R-47):** the **Evidence Preservation durations** — Election's **own consumer-side port** for Contestation Window, MAD and Legal Safety Margin (R-44: the *invariant* "MAD has one home" was binding; "import Adjudication's port" was a substitutable mechanism that TP-1 forbids), a **fail-closed** config adapter mirroring `ConfiguredAdjudicationDurations` exactly, and the **C-1 fitness guard** that tests its own detector against AP-1's real shape. **Inert by design — the EPW value object is 7B, the deletion guard 7C.** |
| [`07_evidence_preservation_window.md`](./07_evidence_preservation_window.md) | **WP-7 slice 7B (Policy 2 - R-58):** the **`EvidencePreservationWindow`** value object -- `CW + MAD + LSM` from the anchor, private constructor plus named factory, **non-positive terms rejected and never clamped** -- and the application collaborator that resolves the three durations through **Election's own port** and **fails closed when an election has no anchor** (a window that requires an anchor can never observe its absence). **Inert by design: nothing consumes it; the deletion guard is 7C.** |

**Step 4C — Architecture Qualification (no new guide; qualification only):** Election joined `tests/Architecture/GreenfieldCoreArchitectureTest` (`CONTEXTS` + event-ownership) once it became a complete hexagonal context, and passes every existing greenfield rule (hexagonal completeness · domain-has-no-infrastructure-imports · event ownership · readonly events · anonymity AT-Q7). This proves conformance; it introduced no production code.

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
