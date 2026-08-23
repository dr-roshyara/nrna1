# `KOS-OPERATING-MODEL-001-AMENDMENT-001` — **VERIFIER APPOINTMENT registration** (AST-019)

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Role appointed:** `verification` (fresh, independent) · **Subject:** `AST-019` / `ActivateCommissionedFreshSession`
**Document type:** Governance **registration** of the PO/ARB's appointment act — **not** an activation
**Date:** 2026-08-23
**Recorded by:** `claude-code-session:77b85fa3-074e-4e5c-a494-f11d2c128595` — *disclosed **GOVERNANCE-RECORDING** capacity for this act only. Recording ≠ appointing · verifying · accepting · adopting · authorizing.*
**Placement derived:** `scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> **PO/ARB act, verbatim:** *"Appoint a fresh independent verifier for KOS-OPERATING-MODEL-001-AMENDMENT-001 / AST-019."*
>
> **⛔ The appointment names a ROLE, not a process.** It deliberately contains **no session identifier**, and none was invented. **Appointee identity: NOT DECLARED** — it comes from the runtime of the fresh session the PO/ARB starts (`INV-ATTR-1/2`, `G-2`). **No lane was registered. No handoff. No start. No verification.**

---

## 1 · The appointment

| Fact | Value |
|---|---|
| Appointing authority | PO/ARB (human) |
| Role | `verification` — fresh, independent |
| Work item | `KOS-OPERATING-MODEL-001-AMENDMENT-001` (opened 2026-08-23; `OPEN`, unassigned) |
| Subject under verification | `AST-019` (`.claude/scripts/activate-commissioned-fresh-session.php`) + `ActivateCommissionedFreshSessionContractTest` (GO-01..GO-25) |
| **Appointee identity** | **NOT DECLARED** — the fresh session declares its own from `CLAUDE_CODE_SESSION_ID` |
| This recording process | **NOT the appointee** — `77b85fa3` is barred (§3) |

**Why this appointment is now possible, when it was not yesterday.** The producer bar had nothing to exclude: AST-019's builder was recorded nowhere, so "independent verification" was an unfalsifiable claim (finding **F-3**). The producer is now on record — `1899d8bf-2688-4bf3-9787-b4114ddaeec8`, attributed from provenance — which is the precondition the authorization decision named: *"Governance records the identity, **then** an independent verifier may be commissioned."* That condition is satisfied.

---

## 2 · Candidate-declaration prompt, preserved

`docs/knowledgeos/reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-VERIFIER-CANDIDATE-DECLARATION-prompt.md` — preserved verbatim for the fresh session to consume on first start. The PO/ARB starts a **new session** and pastes it. **A subagent will not do:** a subagent reports its parent's `CLAUDE_CODE_SESSION_ID` (empirical probe, 2026-08-22), so it *is* the parent and fails the independence bar.

**One instruction in that prompt is new, and it exists because of a recorded failure.** Step 0 — **"declare before you orient"** — tells the candidate to establish identity and check the bars *before* reading anything about the subject. The `77b85fa3` episode is the evidence: that candidate oriented first and declared second, its own reading became prior participation, and because `REVIEW_INDEPENDENCE_POLICY` (§22) is a deliberate placeholder nobody could rule whether the participation disqualified it. The session was spent and the question is still open (`…-CANDIDATE-DECLARATION-77b85fa3.md`). **Ordering the steps costs nothing and removes the failure mode without deciding the open policy question** — which stays open.

---

## 3 · The bar carried into the prompt (16 identities)

| Identity | Ground |
|---|---|
| `1899d8bf` | **the producer** — R-34/EP-02 |
| `cf621832` | parent adoption reviewer — re-executed AST-019's 25 tests |
| `77b85fa3` | recorded the producer identity; authored the prompt and this registration |
| `fc59bb0a` · `259c1966` | parent verifier · parent implementation producer |
| `b51dba91` · `5928b9f9` · `d31ea60f` · `d89af2f5` | prior governance-recording processes on this material |
| `5c0e13c1` · `8a525719` · `8deac5de` · `d1612e03` · `b64828fe` · `7c2690ae` | prior actors carried from the parent commission |
| PO/ARB | human authority — never the verifier |

**Observation (one occurrence, not promoted — `ES-006.1`):** sixteen barred identities means the eligible pool is nearly exhausted. The "genuinely fresh session" requirement is now load-bearing rather than ceremonial — it is the only remaining way to field a verifier at all.

---

## 4 · Commissioned scope (binding when the lane is activated, not now)

- **Independent re-execution** of GO-01..GO-25 (`tests/Unit/Platform/WorkflowEngine/ActivateCommissionedFreshSessionContractTest.php`) and the full `WorkflowEngine` regression — **reported numbers are not evidence**.
- **Source inspection** against AST-019's own claims: sole-writer through AST-015 (no direct record write, no store-path knowledge), never writes `CONTINUATION` (Inv E / GO-21), identity from the environment only, never appoints, fail-closed `V1`–`V10`.
- **The central question.** `AST-019` has **never written a transition in production** — every lane in this estate was hand-composed in governance-recording capacity (finding **F-4**). Its principal write path is exercised **only by its own test suite**. Whether that path is trustworthy is the substance of this commission.
- **The two observed refusals** — `CONFLICTING_ASSIGNMENT` over an active lane (GO-13) and `NOT POSSIBLE` on a stopped item (GO-21). Both were correct; confirm they are correct **for the right reasons**.
- **Byte-integrity of the adopted layers** — L1 document, L2 `operating-model.php`, L3 `OperatingModelContractTest` unchanged. They are adopted and authorized: verified **untouched**, never re-verified, never modified.
- **Four-state reporting** — `IMPLEMENTED` / `VERIFIED` / `ADOPTED` / `AUTHORIZED` kept distinct. The verifier may conclude `VERIFIED`. It may **never** conclude `ADOPTED` or `AUTHORIZED`, and never accepts its own work (`R-34`/`EP-02`).

**Not commissioned:** modifying AST-019 or its tests · modifying the adopted layers · modifying AST-015/016/017/018 · reopening `KOS-OPERATING-MODEL-001` · invoking `activate` against a real work item (use read-only `check` and hermetic fixtures) · adoption · authorization · `EKS-07`.

---

## 5 · Sequence from here

```
PO/ARB starts a NEW session, pastes the candidate-declaration prompt
    → the session declares its own runtime identity + checks the 16 bars
    → declares candidacy → STOP
        ─── everything below is a LATER governed act ───
    → Governance REGISTERs the DECLARED identity to the verification lane
    → human START (G-3 — never automated, never fabricated)
    → verification runs → STOP
    → Governance review → PO/ARB adoption decision (never automatic)
```

**Note on the lane shape.** This work item has no predecessor lane to hand off from — it is `OPEN` with no sessions, so the verification lane is the first. The producer never held a lane (that absence *is* finding F-3), and the producer bar is now enforced by **record**, not by lane topology.

---

## 6 · Non-actions

`No REGISTER` · `No HANDOFF` · `No START` · `No CONTINUATION` · `No grant` · **no lane on either work item** · no verification · no verdict · no adoption · no authorization · no self-appointment · no appointee identity invented or pre-registered · **no change to AST-019's source or tests** · **no change to the adopted L1/L2/L3** · no change to AST-015/016/017/018 · `KOS-OPERATING-MODEL-001` **not reopened** · no `EKS-07`.

**State after this record:** `KOS-OPERATING-MODEL-001` **ADOPTED · AUTHORIZED** (unchanged) · `AST-019` **IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED** (unchanged) · `KOS-OPERATING-MODEL-001-AMENDMENT-001` **OPEN, unassigned, verifier appointed but not activated**.

---

**Traceability:** producer-identity registration `…-AMENDMENT-001-PRODUCER-IDENTITY-registration.md` (F-3 settled) · candidate-declaration prompt `…-AMENDMENT-001-VERIFIER-CANDIDATE-DECLARATION-prompt.md` · authorization decision `…-KOS-OPERATING-MODEL-001-AUTHORIZATION-DECISION.md` (AST-019 held, F-3 gate) · Governance adoption review F-2/F-3/F-4/F-6 · candidate-declaration precedent `…-CANDIDATE-DECLARATION-77b85fa3.md` · parent verifier-appointment precedent `…-KOS-OPERATING-MODEL-001-VERIFIER-APPOINTMENT-registration.md` · `R-34`/`EP-02` · `INV-ATTR-1/2`, `G-2` · `G-3` · `ES-004.2`/`ES-004.3` · `ES-006.1`
