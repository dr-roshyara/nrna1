# `KOS-SESSION-BOOTSTRAP-001-CORRECTION-001` — PO/ARB ruling **REGISTERED**

**Registered by:** Governance — `claude-code-session:b64828fe` *(self-declared; identity resolved mechanically and disclosed in the verification registration)*
**Act:** PO/ARB decision 2026-08-22 · **Governance records; it decides nothing here.**

---

## 1 · The ruling, as delivered

| Item | Status |
|---|---|
| **V-1 · V-3 · V-5** | ✅ **COMMISSIONED FOR CORRECTION** |
| **V-2 · V-4 · V-6** | ⏸ **NOT COMMISSIONED** |
| **V-7** | ⏸ **OBSERVATION / FOLLOW-UP** |
| **V-8** | 🔵 **SEPARATE GOVERNANCE DETERMINATION REQUIRED** |
| **`AST-017` adoption** | ⛔ **NOT YET** |
| **Migration** | ⛔ **UNAFFECTED / NOT AUTHORIZED** |
| **`EKS-07`** | ⏳ future architecture exploration |

> ### **The governing principle, recorded verbatim: *"repair the three demonstrated defects without reopening the architecture that already passed independent verification."***

## 2 · The three defects, as the ruling states them

| # | Requirement |
|---|---|
| **V-1** | `recorded_human_start_act` **must not be inferred merely from `state != CREATED`** |
| **V-3** | ambiguity reporting **must list the actual matching candidates**, not all candidates |
| **V-5** | harness instructions and the emitted bootstrap schema **must use the same field name** |

## 3 · Strict scope

**ALLOWED:** fix V-1 · fix V-3 · fix V-5 · **add regression tests** · update affected documentation/pointers · **preserve `AST-015` delegation** · **preserve the V-3 bounded raw-read boundary** · **preserve provider independence** · **preserve fail-closed ambiguity.**

**FORBIDDEN:** redesign `AST-017` · change `AST-015` · change identity/authority rules · ⛔ **change `a8ce5a39` → `AMBIGUOUS`** · implement `EKS-07` · wire `SESSION_START` · any migration work.

⭐ **The four "preserve" clauses are the properties independent verification proved.** A correction that fixes the three defects while degrading any of them would be a net regression, not a repair — which is why they are scope conditions and not advice.

## 4 · V-8 — not decided by implementation fiat

**The determination to be commissioned separately, verbatim:**

> *"Does `KOS-SESSION-BOOTSTRAP-001` itself require registration as a governed work item, or is advisory technical verification without a registered lane an accepted special case for platform/bootstrap work?"*

⛔ **The workflow record is NOT created automatically**, on the ruling's own ground: *doing so would silently resolve V-8 by implementation rather than by PO/ARB authority.* ✅ **Governance has created none.**

## 5 · ⭐ GOVERNANCE FLAG — the ruling contains a circularity that must be resolved before authoring

**The ruling requires:** *"The next Architecture actor should be freshly appointed by PO/ARB, **with a new lane and human START**."*

**But a lane exists only inside a workflow record, and `KOS-SESSION-BOOTSTRAP-001` has none** — confirmed: `.claude/runtime/workflow/` holds no such record. **Creating one to host the lane would decide V-8** — the very thing §4 forbids.

```
correction needs a lane  →  lane needs a workflow record
   →  creating the record decides V-8  →  V-8 must not be decided by fiat
```

⛔ **Governance does not choose the exit.** Two are available and each is defensible:

| | Exit |
|---|---|
| **(a)** | **Decide V-8 first.** If it rules "register the work item", the record is created **by authority** and the correction gets its lane and human START as the ruling intends |
| **(b)** | **Run the correction as a PO/ARB-directed act without a lane** — the pattern `C-10`'s `D1`–`D5` already used, which touched no transitions. The ruling's *"new lane and human START"* clause would then be **satisfied differently and that must be recorded**, not silently dropped |

⚠️ **What must not happen: the correction beginning without either exit chosen** — that would resolve V-8 by omission, which is the same defect as resolving it by fiat.

## 6 · Actor constraints — appointment is PO/ARB's

**Barred from authoring:**

| Process | Ground |
|---|---|
| `a8ce5a39` | **producer** of the implementation being corrected |
| `8a525719` · `d1612e03` | **verifier** *(both named in the ruling; `d1612e03` is recorded as delivered — Governance has not independently established its role)* |
| `b64828fe` | Governance |

**Required:** freshly appointed by PO/ARB · must not verify or accept its own correction · **a fresh independent re-verification remains mandatory before adoption.**

## 7 · Durability prerequisite — registered as a condition of adoption

> **The independent verification artifact must be committed by its producer before the correction is considered ready for final adoption.**

⛔ **Governance does not commit another process's artifact** — consistent with the durability lesson already recorded on `KOS-AIP-GOV-STATE-DURABILITY`, where the same condition became a migration prerequisite. **Producer: `8a525719`**, stating its producing process in the commit message.

## 8 · Not decided by this registration

⛔ V-8 · the exit from §5's circularity · the authoring actor · V-2/V-4/V-6 inclusion · V-7 disposition · **`AST-017` adoption** · migration · `EKS-07`. **No workflow record created. No lane registered. No START. Nothing corrected, verified or accepted.**

**Next actor: PO/ARB** — resolve §5, commission the V-8 determination, appoint the correction actor.

**Traceability:** PO/ARB ruling 2026-08-22 · verification registration `2026-08-22-KOS-SESSION-BOOTSTRAP-001-independent-verification-registration.md` · verification artifact (`8a525719`, **untracked**) · `.claude/runtime/workflow/` census · `C-9` (`init` forks silently) · `C-10` `D1`–`D5` directed-act precedent · `R-34`/`P-2` · `G-3` · `EKS-07`
