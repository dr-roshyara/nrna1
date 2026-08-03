# Adjudication — Developer Guide

**Audience:** engineers working in the Adjudication bounded context (greenfield Core, with Contestation).

Adjudication owns the **`Determination`** aggregate — the binding ruling on a contested outcome (`Draft → Issued → Final`). It is the sole aggregate written when a determination is issued (Challenge is read-only then, ADR-T14); it publishes the **`DeterminationIssued`** integration event (restricted; consumed cross-context by Election). It carries **no voter↔vote linkage** (ADR-T11): evidence is referenced by hash, the contested outcome by reference.

## Guides (per step)
| Guide | Covers |
|-------|--------|
| [`01_determination_contested_outcome_schema_v2.md`](./01_determination_contested_outcome_schema_v2.md) | The `Determination` carrying a **`ContestedOutcomeRef`** (Adjudication's own local VO, ADR-T16) and emitting it on **`DeterminationIssued` payload schema version 2** — additive, backward-compatible (ADR-PL-01). |
| [`02_determination_evidence_set_schema_v3.md`](./02_determination_evidence_set_schema_v3.md) | **WP-1 (ADR-T22):** the **`EvidenceSet`** VO — the considered-evidence set fixed at issuance (R-4-expanded/INV-4 rider) — carried on **payload schema version 3**; hydrator window shifts to **(v3, v2), v1 retired**. |
| [`03_adjudication_process_manager_core.md`](./03_adjudication_process_manager_core.md) | **WP-2 (EPIC-004K §§3/5/6/11):** the **Adjudication Process Manager** — the loop's HEAD (ADR-T8): six business states, §6 guards, conclude-time atomic fixation (PM-5), active-scoped uniqueness (partial unique index, INV-B1 mirrored), and its durable store (not a domain repository). |
| [`04_challenge_routed_consumption.md`](./04_challenge_routed_consumption.md) | **WP-4 (ADR-T21 / PB-006):** consuming Contestation's published **`ChallengeRouted`** so the loop's head actually fires — one `InboxHandler` + consumer-side registration, plus five **defended absences** (no translator per G-2, no `RoutedTo` VO, no cross-context Domain or Infrastructure import, no minting). |
| [`05_adjudication_horizon_and_expiry.md`](./05_adjudication_horizon_and_expiry.md) | **WP-6 (Q-2 · Policy 4 · EPIC-004K section 197):** the **MAD-aware horizon** (durations behind a port, because Q-2 owns the duration and the APM only enforces it), the **expiry announcement** as published language beginning a NEW conversation, and **late decision vs redelivered decision** — plus three keystones that assert *absences*: a timer concludes nothing. |
| [`06_conclude_to_issue_seam.md`](./06_conclude_to_issue_seam.md) 🔶 **v1 — stable content only** | **WP-4B (EPIC-004K §11 · R-72..R-76):** the **conclude→issue seam** — the conclusion and the issuance as **two transactions** (ADR-T1), the **six retained issuance facts** and why each is retained rather than derived, a **timestamp instead of a seventh status**, **fail-closed** on any absent input, and one seam shared by the live and redrive paths. **Crash-window recovery semantics are under ARB review (Q1–Q5) and deliberately NOT specified here** — v2 completes them once the Board rules. |

## Authoritative architecture
- Ubiquitous language: `docs/adr/ADR-UL-01-ContestedOutcome.md`.
- Published language / versioning: `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` + `docs/implementation/Event_Registry.md` (decision tree).
- Aggregate boundary: Round 50-07 v1.2 · ADR-T14 (Challenge read-only) · ADR-T5 (event versioning).

## Invariants to preserve
- **Anonymity (ADR-T11):** no voter↔vote linkage; the contested-outcome reference is a Result/Determination id, never a vote/voter id.
- **Bounded-context autonomy (ADR-T16):** Adjudication reconstructs its **own** local VOs from wire strings — it does not import Contestation's.
- **Issued once; injected time; illegal transitions throw without mutation.**
