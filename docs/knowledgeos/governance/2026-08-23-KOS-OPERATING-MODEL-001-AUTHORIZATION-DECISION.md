# `KOS-OPERATING-MODEL-001` — **AUTHORIZATION DECISION** · ✅ **AUTHORIZED for future use** (three adopted layers) · `AST-019` **held NOT ADOPTED / NOT AUTHORIZED**

**Work item:** `KOS-OPERATING-MODEL-001` (FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL) · **Component:** `CMP-004` (workflow_engine)
**Document type:** the **PO/ARB authorization decision** — §38's fourth state, decided separately from adoption. This is the authoritative record of that human authority act.
**Date:** 2026-08-23
**Recorded by:** `claude-code-session:cf621832-ac8e-4f06-83d3-03b989a0b4b5` (role `governance`) in disclosed **GOVERNANCE-RECORDING** capacity, on the `CONTINUATION` at seq 17 which names this act
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **Recording is not deciding.** Both halves of the decision below are the human's own words. Governance returned the authorization question rather than inferring it from adoption; this record closes it.

---

## 1 · The decision (verbatim)

> **"Authorize the three adopted layers of KOS-OPERATING-MODEL-001 for future use. Keep AST-019 / AMENDMENT-001 not adopted and not authorized pending independent verification."**
> — PO/ARB, in-session, 2026-08-23

Two parts, both recorded: an **authorization** and a **preservation**.

---

## 2 · Part one — AUTHORIZED for future use

| Layer | Artifact | State |
|---|---|---|
| **L1** | `…/2026-08-22-KOS-OPERATING-MODEL-001-final-operating-model.md` — the 40-section operating model | ✅ **ADOPTED · AUTHORIZED** |
| **L2** | `.claude/scripts/operating-model.php` — the read-only presenter | ✅ **ADOPTED · AUTHORIZED** |
| **L3** | `tests/Unit/Platform/WorkflowEngine/OperatingModelContractTest.php` | ✅ **ADOPTED · AUTHORIZED** |

**What authorization means here, stated no more strongly than the act supports:** the three layers are authorized **as the operating model for future governed work** — the human-facing operating layer over the existing workflow estate (`AST-015` · `AST-017` · `AST-018`), which it consumes and never replaces (§26–§28). All four §38 states are now positive for these three layers, and they were reached in order, by separate acts, with independent verification between implementation and adoption.

**What it does not silently carry:**

- **No mechanism change.** `AST-015` remains the single workflow authority; authorization grants the model no write path, no new transition type, and no authority the record does not already express.
- **No grant on the workflow record.** The engine's `grants` vocabulary is *lane write-authority*, not *asset authorization*. Recording this authorization there would invent semantics the mechanism does not have (`ES-005.4`), so `grants` remains `[]`. **The authoritative home of this authorization is this document.**
- **No change to standing runtime instructions.** Binding the authorized model into `.claude/CLAUDE.md` or any other standing instruction set is a **separate governance act the human has not directed** — and `CLAUDE.md` additionally carries a FROZEN-methodology notice. Flagged, not performed.

---

## 3 · Part two — `AST-019` held, by the same act

| Item | State | Held because |
|---|---|---|
| **`AST-019` / `ActivateCommissionedFreshSession`** (`.claude/scripts/activate-commissioned-fresh-session.php`, `AMENDMENT-001`) | ⛔ **NOT ADOPTED · NOT AUTHORIZED · NOT VERIFIED** | The human's express instruction: *"pending independent verification."* Implemented (25 tests / 270 assertions GREEN) but never independently verified and outside the verification report that the adoption rested on |

**Before that verification can be commissioned, finding F-3 must be settled:** `AST-019` was implemented with **no registered lane** on this work item, and its producer identity is recorded **nowhere by UUID** — the completion report says only *"this session's runtime `CLAUDE_CODE_SESSION_ID` — not a registered lane on this work item."* The producer bar (`R-34`/`EP-02`) is therefore **not mechanically enforceable**: a candidate verifier cannot demonstrate that it is not the producer. Recording that identity is a prerequisite Governance step, not a formality.

---

## 4 · State after this decision

```
KOS-OPERATING-MODEL-001 (L1+L2+L3)   IMPLEMENTED · VERIFIED · REVIEWED · ADOPTED · AUTHORIZED for future use
AST-019 / AMENDMENT-001              IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED   (held, pending verification)
```

§38 — the four states are never collapsed. They are now all positive for the three layers **because four separate acts said so**, not because one implied the next.

---

## 5 · Open items this decision does not close

| # | Item | Next step |
|---|---|---|
| **F-3** | `AST-019` producer identity unrecorded → producer bar unenforceable | Governance records the identity, **then** an independent verifier may be commissioned |
| **F-4** | §8's delegated `appoint` path is not the path the estate takes — its auto-generated `executionContext` cannot carry the estate's disclosure standard, so lanes are hand-composed (including every lane on this work item) | Give `appoint` a disclosure passthrough, or sanction hand-composition explicitly |
| **F-5** | `REVIEW_INDEPENDENCE_POLICY` (§22) remains a placeholder, now with observed cost (the `77b85fa3` episode) | PO/ARB — whether to author the policy. **Now more pointed:** the model is authorized *with* its independence policy still absent |
| **F-6 · F-7** | §29 **CASE 6** is not machine-rendered under the estate's closure discipline (proven on a record copy: `STOP` short-circuits; `COMPLETE` falsely reports a waiting implementation actor, since predecessors stay permanently `HANDED_OFF`) | Follow-up against `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` — the cascade ordering and the estate's `STOP`-vs-`COMPLETE` closure discipline |
| **F-8** | The workflow engine has **no vocabulary for `ADOPTED` or `AUTHORIZED`** — both live only in governance documents, so no mechanism can answer *"is this authorized?"* | Observation for a future increment; **not** repaired here, since inventing a state would breach §26/§37 |

**Nothing was repaired opportunistically while recording this decision** (§37, `ER-08`).

---

**Traceability:** PO/ARB authorization decision 2026-08-23 (verbatim, above) · adoption decision `…-2026-08-23-KOS-OPERATING-MODEL-001-ADOPTION-DECISION.md` (commit `9502168a`) · Governance adoption review `…-GOVERNANCE-ADOPTION-REVIEW.md` (result PASS, commit `7764b6e0`) · independent verification `…-2026-08-22-…-INDEPENDENT-VERIFICATION.md` (`fc59bb0a`) · verbatim 40-section commission · implementation commit `39e953dd` · workflow record seq 11–18 · operating model §25–§28, §37–§40 · `AST-015` Inv E · `G-1`/`G-3` · `R-34`/`EP-02` · `ES-004.3` · `ES-005.4` · placement `scripts/doc-placement.php` → `docs/knowledgeos` (exit 0)
