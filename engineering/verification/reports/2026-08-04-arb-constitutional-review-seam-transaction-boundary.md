# ARB Constitutional Review — the seam's issuance wiring

**Reviewing:** `2026-08-04-seam-issuance-transaction-boundary-evidence.md` (Event D engineering evidence).
**Conducted by:** ARB Chief, 2026-08-04. **This is a review, not a ruling** — its determination requires adoption by the Decision Authority.
**Separation observed:** engineering submitted evidence and ended there. **The determination below was not present in the engineering artefact**, and R-91 — which contained it — is **HELD** for exactly that reason.

---

## Phase 1 — Engineering evidence validated

Reviewed: runtime observation · container resolution · dependency graph · execution path · documented architectural intent. **No remedy discussed.**

| Claim | Submitted as | Review finding |
|---|---|---|
| `AdjudicationService::class` resolves to `TransactionalAdjudicationService` | EVIDENCE | ✅ **correctly classified** — executed, output recorded |
| `RequestsDeterminationIssuance::class` resolves to `CoordinatorIssuanceRequest` holding a bare `CoordinatesAdjudication` | EVIDENCE | ✅ **correctly classified** — executed |
| `CoordinatesAdjudication` performs two writes with no enclosing transaction | EVIDENCE | ✅ **correctly classified** — readable at `CoordinatesAdjudication.php:63-69` |
| The decorator's purpose is atomicity of those two writes | EVIDENCE | ✅ **correctly classified** — its own docblock |
| No production path reaches the seam today | EVIDENCE | ✅ **correctly classified** — WP-4D unbuilt; no caller exists |
| The two writes are therefore not atomic on that path | **CONSEQUENCE** | ✅ **correctly classified** — and correctly *not* labelled evidence, since no execution lost an event |
| A determination could persist without its event | INFERENCE | ✅ **correctly classified** |
| R-84's reconcile would misread that state | INFERENCE | ✅ **correctly classified** |

**Determination: the evidence is valid and every claim is classified at the strength it carries.** The mechanism — a concrete type-hint plus an unbound class, yielding an auto-wired second instance — is confirmed.

## Phase 2 — Constitutional compatibility

> **Does the implementation faithfully realize the accepted decisions?**

| Decision | Faithfully realized? |
|---|---|
| **ADR-T1** — one aggregate per transaction | ⚠️ **on the `AdjudicationService` path, yes. On the seam's path, NO** |
| **Constitution §Transaction** — the application layer defines the boundary | ⚠️ same split: the boundary is *defined* and, on this path, *not applied* |
| **R-72** (WP-4B authorized) | ✅ unaffected — the seam was authorized and built as specified |
| **R-83** (crash models A/B/C) | ⚠️ **realized, but its premise is narrower than assumed.** All three models presuppose atomic issuance |
| **R-84** (§12 reconcile) | ⚠️ realized as specified; its *discriminator* could be misled by a state R-83 did not contemplate |
| **EPIC-004K §11 · §12** | ✅ unaffected |

**Determination: no accepted decision is INSUFFICIENT. One is UNREALIZED on one path.** Nothing in ADR-T1 or §Transaction fails to say what is needed — the implementation does not do what they say, on a path neither anticipated.

**No decision is reopened**, because the evidence demonstrates deviation, not inadequacy.

## Phase 3 — Governance domain

**Selected: EXECUTION GOVERNANCE.**

**Reasoning.** Architecture governance answers *"is the decision still correct?"* — and here it demonstrably is. Execution governance answers *"who is authorized to repair realization?"* — which is the live question.

**This corrects the engineering finding it replaces.** R-91 recorded an *"option-set gap"* in the architecture-governance outcomes. **There was no gap: the case simply belongs to a different domain, and Phase 3 exists to route it.** A framework that offers *no action · clarification · reopen* for architecture decisions is complete for architecture decisions.

## Phase 4 — Constitutional determination

> ## **ARCHITECTURE REMAINS VALID.**
>
> ADR-T1, Constitution §Transaction, R-72, R-83 and R-84 stand **unamended**. No architectural decision is insufficient, and none is reopened.
>
> ## **EXECUTION AUTHORIZATION IS REQUIRED BEFORE ANY IMPLEMENTATION CORRECTION MAY BEGIN.**
>
> The deviation is **LATENT** (no production caller; WP-4D unbuilt) and **no severity is assigned**. **No implementation is authorized by this review.**

**Two consequences recorded, and deliberately not decided:**

1. **The remedy is not chosen.** Interface-vs-concrete type-hint · an explicit container binding · a transaction at the seam — all remain candidate mechanisms. **Selecting one is part of the authorized correction, not part of this review.**
2. **R-83's crash-model set is not extended.** Whether *issued-but-never-announced* becomes a fourth model **depends on the remedy** — a repair that restores atomicity removes the state entirely. **Pre-adopting the model would decide the repair.**

## What this review does not do

- **Does not authorize implementation**, planning, or tactical design.
- **Does not amend any ADR or ruling.**
- **Does not assign severity or priority.**
- **Does not accelerate WP-4D**, whose absence is why the deviation is latent rather than active.
- **Does not itself constitute a ruling** — it is a review, and its determination awaits adoption.

## Traceability

Event D evidence `2026-08-04-seam-issuance-transaction-boundary-evidence.md` · **R-91 (HELD)** · R-90 (withdrawn, number retired — contrast recorded) · **ADR-T1** · Constitution §Transaction · **R-72 · R-83 · R-84** · EPIC-004K §11 · §12 · INV-B1 · `AdjudicationServiceProvider.php:49, 53-68` · `CoordinatesAdjudication.php:63-69`.
