# Decision Authority Adoption Package — the seam's issuance wiring

**For:** the **Decision Authority**. **Prepared by:** ARB Chief, 2026-08-04.
**Contains no new evidence and no new analysis.** The engineering evidence and the ARB review are unchanged; this package makes them decidable.

---

## 1. Executive summary

While closing an unrelated coverage gap, engineering observed that **the container resolves an undecorated `CoordinatesAdjudication` for the seam's issuance path**, rather than the transaction-owning `TransactionalAdjudicationService` bound to `AdjudicationService::class`.

**Consequence:** on that path, the determination write and its outbox enqueue are **two independent writes** — the exact pair the decorator exists to make atomic.

**Reachability: LATENT.** No production caller exists, because the authority-decision intake (WP-4D) is unbuilt; and every test supplies a hand-rolled double, so no test traverses the real wiring either.

**The ARB review found the architecture sound and the implementation deviant** — not the reverse.

## 2. Proposed constitutional determinations

| # | Determination | Depends on |
|---|---|---|
| **D-1** | **Architecture remains valid.** ADR-T1, Constitution §Transaction, R-72, R-83 and R-84 stand unamended; no decision is insufficient and none is reopened | Phase 2 |
| **D-2** | **The governance domain is EXECUTION governance**, not architecture governance | Phase 3 |
| **D-3** | **Execution authorization is required before any implementation correction may begin** | Phase 4 |
| **D-4** | **Reachability is LATENT**, and **no severity is assigned** | Phase 3 |

## 3. Traceability — each determination to its support

| | Engineering evidence | Constitutional authority | Governing ADRs | Affected rulings | Implementation scope |
|---|---|---|---|---|---|
| **D-1** | container resolution (executed) · `CoordinatesAdjudication.php:63-69` · the decorator's docblock | Constitution §Transaction — *the application layer defines the boundary* | **ADR-T1** | R-72 · R-83 · R-84 — **all unamended** | none: no change proposed |
| **D-2** | the same wiring evidence | the distinction between *"is the decision correct?"* and *"who may repair realization?"* | — | corrects **R-91**'s *"option-set gap"* to a **domain-routing error** | none |
| **D-3** | `AdjudicationServiceProvider.php:49, 53-68` — the concrete type-hint and the unbound class | engineering may not alter bindings or types without authorization | ADR-T1 | — | **would be:** the adapter's collaborator, or its binding, or a transaction at the seam — **none selected** |
| **D-4** | no production caller (WP-4D unbuilt) · no test traverses the real wiring | — | — | **R-83** — flagged: its three models presuppose atomic issuance | none |

## 4. Explicit non-decisions

**None of the following is decided by adoption, and adoption must not be read as deciding them:**

1. **The remedy.** Interface-vs-concrete type-hint · an explicit container binding · a transaction at the seam — **all remain candidates; none is selected.** Choosing one belongs to the authorized correction.
2. **Whether a repair is authorized.** D-3 states that authorization is *required*; **it does not grant it.** That is a separate execution-governance act.
3. **Severity or priority.** *Latent* describes reachability, not importance.
4. **R-83's crash-model set.** Whether *issued-but-never-announced* becomes a fourth model **depends on the remedy** — a repair restoring atomicity removes the state entirely. Pre-adopting it would decide the remedy.
5. **WP-4D's status.** It remains unauthorized. **Its absence is why the deviation is latent, which is not a reason to accelerate it.**
6. **R-91's substance.** It stays **HELD**, not withdrawn: its Phases 1–3 are undisputed and superseded in **form** only.

## 5. Adoption readiness check

| Boundary | Status |
|---|---|
| Engineering Evidence | ✅ separate artefact, unchanged, ends at *evidence validated* |
| ARB Review | ✅ separate artefact; its determination labelled **PROPOSED** |
| Constitutional Determination | ✅ stated as D-1…D-4, **not adopted** |
| Adoption Decision | ⏳ **outstanding — presented in §6, not requested** |

**Does any determination still rest on engineering judgement rather than ARB review?** **No.** D-1, D-2 and D-3 are ARB acts on submitted evidence. **D-4 rests on a factual absence — no caller exists — not on an engineering opinion about likelihood**, and no severity is attached to it.

## 6. Decision Authority Decision

**Exactly three outcomes are available. No recommendation is offered, and this package requests none** — it PRESENTS the constitutional outcomes. *(Renamed 2026-08-04 from “Requested adoption action”: a package that appears to REQUEST an outcome has already leaned toward one.)*

| | Outcome | Consequence |
|---|---|---|
| **1** | **Adopt** the constitutional review as written | D-1…D-4 become governing. Architecture stands unamended. **A separate execution authorization would still be required before any repair.** R-91 may then be closed as superseded in form |
| **2** | **Request clarification** on specific constitutional findings | Named findings return for ARB re-examination. **No new engineering analysis is implied**, and none may be initiated without a commission |
| **3** | **Reject** the review, with explicit constitutional rationale | The determinations do not take effect. The engineering evidence stands regardless — **rejecting the review does not retract the observation** |

## 7. Traceability

Engineering evidence `2026-08-04-seam-issuance-transaction-boundary-evidence.md` · ARB review `2026-08-04-arb-constitutional-review-seam-transaction-boundary.md` · **R-91 (HELD)** · R-90 (withdrawn, retired) · **ADR-T1** · Constitution §Transaction · **R-72 · R-83 · R-84** · EPIC-004K §11 · §12 · INV-B1 · `AdjudicationServiceProvider.php:49, 53-68` · `CoordinatesAdjudication.php:63-69`.

---

> **Engineering evidence is complete. Constitutional adoption remains with the Decision Authority.**
