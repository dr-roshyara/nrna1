# KOS-ARCH-BASELINE-001 — Phase A independent verification AUTHORIZED

**2026-08-16 · Session 2 (Governance)** · **Grant registered under the EXISTING assignment. No new assignment, no new START.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #18
 Responsibility : governance
 Operator       : Session 2 (Governance)              [declared]
 Approver       : PO/ARB — authorization 2026-08-16    [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · The act, registered verbatim

> **"RECORD: Authorize the independent verification of KOS-ARCH-BASELINE-001 Phase A under the existing verification assignment."**

**Both premises checked before acting, and both hold:**

| Premise | Finding |
|---|---|
| **Phase A was delivered** | ✅ `2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md` exists — **produced by another process.** Option A worked: this lane declined to reconstruct the architecture it authored, and a separate process did it |
| **An existing verification assignment** | ✅ seq **4** `S1-verification-baseline-phase-a`, handoff at seq **5**, human START at seq **6**. **ACTIVE.** *"Under the existing assignment"* is exactly right — **no new assignment was created** |

## 2 · 🔴 What was actually missing — and it is a live instance of two gaps under verification elsewhere

**The verification lane was `ACTIVE` and `STARTED` — with no verification grant in existence.**

Its `authorizationLinkage` read **`G-KOS-ARCHBASE-A`**: the **Phase A reconstruction** grant, whose scope is *"reconstruct what KnowledgeOS actually is today"*. **A verification lane was pointing at a reconstruction authority and appearing authorized.**

> **This is `G-3` demonstrated in production** — *"`START` does not consult grants; activation and authorization are fully decoupled, and only convention keeps them together."* The mechanism let a verification session start with no verification authority, and reported it operable.
>
> **And it is `D-2` demonstrated too** — `authorizationLinkage` simply reports the most recent `AUTHORIZED` grant, so the lane *looked* correctly authorized while pointing at the wrong scope. **`AST-016` says this in every report: `is the grant holder: UNKNOWN — grants carry no session/role linkage`. Here that gap had a concrete consequence.**

**Registering `G-KOS-ARCHBASE-A-VERIFY` corrects the scope.** Verified after: linkage now resolves to the verification grant, and `authorized` against its scope returns `true`.

**Reported honestly, not smoothed over:** the authority arrived **after** activation. **The PO/ARB's act legitimises the scope; it does not retroactively make the sequence correct.** Anything the lane did between seq 6 and now was done without a verification-scoped grant.

## 3 · Scope of the authorized verification

**Verify the Phase A baseline** — attempting falsification, not confirmation:

| | Check |
|---|---|
| **a** | Every significant conclusion carries `Observed`/`Declared`/`Inferred`/`Unknown`, **honestly** — in particular that nothing marked `Observed` rests only on a document assertion |
| **b** | ⭐ **The central rule held** — **no boundary inferred from a folder or namespace name.** Test by **re-deriving a sample of boundary claims from evidence** |
| **c** | The evidence hierarchy was respected — declared → wiring → runtime → code → directory structure |
| **d** | The self-authorship disclosure is present, and elements the Governance handover declared self-authored are **not presented as independently validated** |
| **e** | ⭐ **Section G (architecture unknowns) is present and substantive** — *a baseline reporting no unknowns has almost certainly inferred something it could not observe*, so **a thin or empty §G is itself a finding** |
| **f** | **No target-architecture work crept in** — it reconstructs what exists, not what should exist |
| **g** | Whether the **Option A separation condition** was met — **and if that cannot be established from the record, say so rather than assume it** |

**Excluded:** repair · modifying the baseline or any `KOS-ARCH-BASELINE-001` artifact · designing or proposing target architecture · Phase B or C · **accepting the baseline — acceptance is the PO/ARB act that FOLLOWS this verification** · qualification, adoption or closure · Election (`A-8`) · self-certification · completing its own assignment.

## 4 · What was NOT done

**No new assignment · no new START · no verification performed · the baseline untouched · `G-KOS-ARCHBASE-A` untouched · Phase B/C still unauthorized · nothing accepted.**

**Note on `(g)`:** whether the separation held is **not establishable from the record** — no person axis, `recordedBy` unvalidated, one git identity. **Governance did not assert that it held.** The verifier is asked to determine what *can* be shown and to state the limit plainly, which is the honest form of that question.

---

*Technical references: PO/ARB authorization 2026-08-16 (§1 verbatim) · `KOS-ARCH-BASELINE-001` seq 4–6 (existing assignment, handoff, human START) · new grant `G-KOS-ARCHBASE-A-VERIFY` · Phase A deliverable `2026-08-15-…-phase-a-current-architecture-baseline.md` · commission `§6.2` (deliverable shape incl. §G unknowns) · `G-3` and `D-2` (live instance, §2) · `A-8` · `INV-ATTR-2`.*
