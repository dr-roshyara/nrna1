# `KOS-OPERATING-MODEL-001` — **ADOPTION DECISION** · ✅ **ADOPTED** (three verified layers) · `AUTHORIZED` **NOT DECIDED**

**Work item:** `KOS-OPERATING-MODEL-001` (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL) · **Component:** `CMP-004` (workflow_engine)
**Document type:** the **PO/ARB adoption decision** — the human authority act of operating model §25, recorded by Governance. This is the authoritative record of the decision itself.
**Date:** 2026-08-23
**Recorded by:** `claude-code-session:cf621832-ac8e-4f06-83d3-03b989a0b4b5` (role `governance`, the reviewing lane) in disclosed **GOVERNANCE-RECORDING** capacity, on the `CONTINUATION` at seq 15 which names this act
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **Recording is not deciding.** The decision below is the PO/ARB's, made in their own words. This document records it; it does not create it, extend it, or infer anything the human did not say.

---

## 1 · The decision (verbatim)

> **"Accept and adopt the three verified layers"**
> — PO/ARB, in-session, 2026-08-23

This is **§29 CASE 6 · option 1 (Accept / Adopt)**, taken after the Governance adoption review presented the decision with result `PASS`, and **bounded exactly as review finding F-2 recommended**.

---

## 2 · What is ADOPTED

| Layer | Artifact | State |
|---|---|---|
| **L1** | `docs/knowledgeos/governance/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md` — the 40-section operating model | ✅ **ADOPTED** |
| **L2** | `.claude/scripts/operating-model.php` — the read-only presenter | ✅ **ADOPTED** |
| **L3** | `tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php` — 43 `test_om_*` | ✅ **ADOPTED** |

**Evidence the decision rests on:** independent verification by `fc59bb0a` (`…-INDEPENDENT-VERIFICATION.md`, VERIFIED all three layers) · Governance adoption review by `cf621832` (`…-GOVERNANCE-ADOPTION-REVIEW.md`, §23 result **PASS**, all five §21 criteria established from re-executed evidence: 43/409 contract tests, 147/1577 full regression, canonical assets byte-unchanged, read-only purity, G-3 unfabricable).

---

## 3 · What is explicitly NOT adopted, and NOT decided

| Item | State | Why |
|---|---|---|
| **`AST-019` / `ActivateCommissionedFreshSession`** (`.claude/scripts/activate-commissioned-fresh-session.php`, `AMENDMENT-001`) | ⛔ **NOT ADOPTED · NOT VERIFIED** | The fourth asset on this work item. Implemented (25 tests / 270 assertions GREEN) but never independently verified and outside the verification report. The human's decision names **"the three verified layers"**; AST-019 is not among them. Its independent verification remains a separate governed step — see F-3 below |
| **The fourth state `AUTHORIZED for future use`** | ⚠️ **NOT DECIDED** | The human said *adopt*; they said nothing about authorization for future use. §38 forbids collapsing the two, so **no authorization is recorded or implied here.** Governance has put the question back to the human separately. **Until it is answered, nothing in this decision authorizes the operating model as the binding operating layer for future work** |
| `REVIEW_INDEPENDENCE_POLICY` (§22) | unchanged **placeholder** | Deliberate. Not authored, not invented, not ruled on by this decision |
| `EKS-07` · migration · autonomous session creation · automatic actor replacement · automatic adoption | unchanged — **not reopened** | §37 |

---

## 4 · Open findings carried forward (adoption does not close them)

| # | Finding | Owner of the next step |
|---|---|---|
| **F-1** | L1 status-annotation staleness | **CLOSED by this act** — the L1 status line is synchronized per `ES-004.3` (additive; the 40 sections remain byte-identical) |
| **F-3** | `AST-019` was implemented with **no registered lane**, and its producer identity is recorded **nowhere by UUID** — so the producer bar (`R-34`/`EP-02`) for its future verification is **not mechanically enforceable**. The model also has no lane-shape for a human-authorized amendment slice on a stopped item | Governance — record the producer identity **before** commissioning AST-019's verification |
| **F-4** | §8's delegated mechanics path (AST-018 `appoint`) is not the path the estate takes; every lane here, including the reviewer's own, was hand-composed because `appoint`'s auto-generated `executionContext` cannot carry the estate's disclosure standard | Governance/Engineering — either give `appoint` a disclosure passthrough, or sanction hand-composition explicitly |
| **F-5** | `REVIEW_INDEPENDENCE_POLICY` remains a placeholder, now with observed cost (the `77b85fa3` episode) | PO/ARB — whether to author the policy |
| **F-6 · F-7** | **§29 CASE 6 — the adoption presentation this very decision exercised — is not machine-rendered under the estate's closure discipline.** Verified empirically on a **copy** of the record: closing the governance lane with `STOP` short-circuits to `WORK_ITEM_STOPPED`; closing it with `COMPLETE` instead makes the renderer state *"The implementation actor is assigned and waiting"*, because predecessor lanes stay permanently `HANDED_OFF`. CASE 6 is reachable only if **every** lane is `COMPLETED` — which is what `test_om_25`'s fixture builds and what the governed flow never produces. The tests are honest about the rendering **given** the `DECIDE` token; they do not pin that a real multi-lane record can produce that token | Follow-up against `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` (the cascade's ordering + the estate's closure discipline), **not** a defect in the adopted layers. This decision was therefore presented in prose by the Governance Engineer, exactly as §25 requires of a person-facing act |

**None of these was repaired opportunistically during this recording act** (§37, `ER-08`): no canonical asset beyond the L1 status line was touched, and the record was never re-opened to make tooling render more nicely.

---

## 5 · State after this decision

```
KOS-OPERATING-MODEL-001   IMPLEMENTED · VERIFIED · REVIEWED · ADOPTED (L1+L2+L3) · AUTHORIZED: NOT DECIDED
AST-019 / AMENDMENT-001   IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED
```

§38 — the four states are never collapsed. **ADOPTED ≠ AUTHORIZED**, and this record keeps them apart.

---

**Traceability:** PO/ARB adoption decision 2026-08-23 (verbatim, above) · Governance adoption review `…-2026-08-23-KOS-OPERATING-MODEL-001-GOVERNANCE-ADOPTION-REVIEW.md` (result PASS, findings F-1…F-6) · independent verification `…-2026-08-22-…-INDEPENDENT-VERIFICATION.md` (`fc59bb0a`) · verbatim 40-section commission (`…-implementation-prompt.md`) · implementation commit `39e953dd` · review commit `7764b6e0` · workflow record seq 11–16 (`REGISTER` · `HANDOFF` · human `START` · `STOP` · human `CONTINUATION` · `STOP`) · operating model §21–§25, §37–§39 · `AST-015` Inv E · `G-1`/`G-3` · `R-34`/`EP-02` · `ES-004.2`/`ES-004.3` · `ES-005.4` · placement `scripts/doc-placement.php` → `docs/knowledgeos` (exit 0)
