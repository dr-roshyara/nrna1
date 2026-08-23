# `REPAIR-001` — governance determination: **is independent re-verification binding?**

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Subject:** `AST-019` · **Grant:** `G-REPAIR-001` (`AUTHORIZED`) · **Repair commit:** `d8a5ee93`
**Date:** 2026-08-24 · **Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; `P-3`; holds no lane; `authorized_to_act: false`)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ⛔ **This record changes no status.** `IMPLEMENTED` is **not** advanced to `VERIFIED` here, and `ADOPTED`/`AUTHORIZED` are not claimed. No transition was written.

---

## 1 · Authoritative state (from the record, not from summaries)

| Fact | Value |
|---|---|
| `workItemState` | **`OPEN`** |
| `mutationOwner` | `84e5c1f7-bd51-42fd-83fc-1b58c7d90dd3` |
| implementation lane | `84e5c1f7…` — **`ACTIVE`** *(see §2: its `STOP` is unrecorded)* |
| verification lane | `84c0f6f6…` — `HANDED_OFF` |
| transitions | **8** (`REGISTER`·`HANDOFF`·`START`·`STOP`·`CONTINUATION`·`REGISTER`·`HANDOFF`·`START`) |
| grant | `G-REPAIR-001` · **`AUTHORIZED`** · `registeredBy: governance` |
| previous verdict | **FAIL** (`seq 4`, `recordedBy: verification`) — blocking `F-1`; recorded as history, **not reopened, not rewritten** |
| repair commit | `d8a5ee93` · 2026-08-24 01:29 · `activate-commissioned-fresh-session.php` (+22/−5) · contract test (+293) · EP-01 plan · dev guide · CONTEXT · session log |
| `AST-019` status | **IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED** |

**Governance scope-compliance check (NOT a verification, and deliberately shallow).** `d8a5ee93` touches **no** `AST-015`/`016`/`017`/`018`, **no** `operating-model.php`, **no** `L1`/`L2`/`L3`. The change sits where the authorization said it must, and `GO-26`…`GO-30` exist in the contract test. **Whether the findings are *correctly* fixed is the verifier's question, not mine** — checking that myself would be Governance performing the verification it is commissioning.

## 2 · ⚠️ The actual next governed act comes *before* any verifier: the implementation `STOP` is unrecorded

The repair is committed in git, but **the authoritative record does not say the implementation finished** — lane `84e5c1f7` is still `ACTIVE` and no `STOP` exists. Every prior lane on this estate closed itself: parent `seq 4` `STOP recordedBy: implementation`, this item's `seq 4` `STOP recordedBy: verification`.

**Consequence if skipped:** `AST-018 appoint` *would* still work (it has no active-lane refusal, and would `HANDOFF` from `84e5c1f7`), but the implementer's **IMPLEMENTED report and evidence would never enter the record** — completion would exist only as a git commit. That is exactly the fact/record separation this estate protects.

**Canonical chain:** implementation `STOP` (reports IMPLEMENTED, never VERIFIED — `R-34`/`EP-02`) → item becomes `STOPPED` (`Inv E`) → human `CONTINUATION` → `AST-018 appoint` the re-verifier.

**Whose act:** `84e5c1f7`'s own. If that session is unavailable, Governance can record the `STOP` on the human's direction citing `d8a5ee93` as the evidence — a disclosed fallback, not the default.

## 3 · Is independent re-verification binding? **YES** — and here is the source, quoted

**Primary governing source — the PO/ARB's own §38 authorization act, verbatim** (`docs/knowledgeos/governance/2026-08-23-KOS-OPERATING-MODEL-001-AUTHORIZATION-DECISION.md:15`):

> **"Authorize the three adopted layers of KOS-OPERATING-MODEL-001 for future use. Keep AST-019 / AMENDMENT-001 not adopted and not authorized pending independent verification."**

That is a **recorded human condition precedent**, not a custom and not a Governance preference. It has never been lifted.

**Why the existing verification does not discharge it.** One independent verification exists and it returned **FAIL** on the **pre-repair** code. `d8a5ee93` then changed that code (+22/−5) and added 293 lines of contract test. **A verification of superseded code cannot be the verification of the code that replaced it** — and a FAIL verdict has never, in any reading, satisfied a condition phrased *"pending independent verification"* for the purpose of adoption. The condition survives the repair; the repair is what makes it answerable.

**Supporting sources (each adds something, none is the whole basis):**

| Source | What it contributes |
|---|---|
| Grant `G-REPAIR-001`, registered scope | *"RE-VERIFICATION after the repair **must be performed by** a process that is NOT 84c0f6f6 …, NOT 1899d8bf …, NOT 5928b9f9 …"* — a recorded constraint on **who**, which presupposes that it happens. **On its own it governs the actor, not the requirement**; the requirement comes from §38 above. |
| `R-34`/`EP-02` | Engineering supplies evidence and **never accepts its own work**. This is why `84e5c1f7`'s `STOP` must say **IMPLEMENTED**, never **VERIFIED**. It bars self-certification; it does not by itself mandate a fresh lane. |
| `§38` four-state model | `VERIFIED` is a distinct state reached only by verification evidence. Definitional, not an extra rule. |

**The substantive, case-specific reason — the part that is not procedure.** `F-1` was **invisible to the entire 25-test contract suite** (`O-1`: no test reached the write path with a non-null `mutationOwner`). The tests that now purport to prove that blind spot is closed — `GO-26`…`GO-30` — were **written by the same process that wrote the fix.** So the only present evidence that the blindness is cured comes from the party whose work it certifies. **That is why this particular repair needs independent eyes**, and it would be true even if no rule existed.

**⚠️ Explicitly NOT generalized.** This determination does **not** establish that *every* implementation requires fresh verification. It rests on **one named condition attached to one named asset** (`AST-019`, by the PO/ARB's own act) plus the fact that this repair remediates a **blocking FAIL** whose defect the existing suite could not see. Absent those, the conclusion does not follow. **No new requirement is invented here.**

## 4 · If re-verification proceeds — the actor rules that actually apply

**Barred** — and for stated reasons, not by reflex:

| Identity | Why barred |
|---|---|
| `84e5c1f7-bd51-42fd-83fc-1b58c7d90dd3` | the `REPAIR-001` implementer — wrote both the fix and the tests that certify it (`R-34`/`EP-02`) |
| `84c0f6f6-795e-4c89-a382-733f2c7b7caf` | the first verifier — authored the findings under remediation |
| `1899d8bf-2688-4bf3-9787-b4114ddaeec8` | the `AST-019` producer (producer bar, `F-3` settled) |
| `5928b9f9-b4d5-46e9-8c71-c295dace18f8` | this Governance process — scoped `REPAIR-001` and authored the findings-grouping correction |

**`fresh runtime UUID ≠ absence of prior participation`.** A new `CLAUDE_CODE_SESSION_ID` proves only that the id is new. Two recorded facts must be applied together:

- **A subagent reports the PARENT's `CLAUDE_CODE_SESSION_ID`** (probe 2026-08-22) — so detection of a non-fresh process is by **identity collision**, and collision with any row above is disqualifying.
- **`CLAUDE_CODE_CHILD_SESSION=1` is NON-DISCRIMINATING** (`O-5`, tested 2026-08-24: this Governance session, a main interactive session, carries it too). It must be **disclosed and assessed**, and it **neither disqualifies nor certifies**.
- Prior participation is therefore established from **primary evidence** — `git grep`, `git log --all --grep`, `grep -rl` over `.claude/runtime/`, `.claude/sessions/`, `docs/`, and the registered session keys of all 23 records — **not** from the novelty of the UUID.

**Declaration-only prompt prepared:** `…-REPAIR-001-RE-VERIFIER-CANDIDATE-DECLARATION-prompt.md`. It withholds the commission, orders identity and participation **before** orientation, and forbids reading the repair subject before declaring.

**Appointment, if it proceeds:** `AST-018 appoint --role=verification --candidate=<declared id> --exclude=<the four bars> --human-act='<verbatim>'`. **Nothing hand-composed** (`ASD-001`). Note `O-6`: `appoint` writes `REGISTER` + `HANDOFF` + **`START`** from that one human act — appointment and activation are fused, so authorizing the appointment activates the lane.

## 5 · Scope — unchanged, nothing added

Not reopened: `KOS-OPERATING-MODEL-001` (L1+L2+L3 `ADOPTED` · `AUTHORIZED`). Not modified: `L1`/`L2`/`L3`, `AST-015`/`016`/`017`/`018`. Not addressed: **`F-5`** · **`ASD-001`** · **`O-4`** · **`O-6`** · **`Q-1`** · **`Q-2`** · **§22** — each remains OPEN and each needs its own human commission. This record commissions none of them.

## 6 · Next actor

```yaml
session_completion:
  status: determination recorded — no transition, no status change, no verdict
  completed_work: state re-grounded from the record · implementation STOP found
                  UNRECORDED (the real next act) · re-verification determined
                  BINDING with the governing source quoted · non-generalization
                  stated · actor bars derived · scope-compliance check on
                  d8a5ee93 · declaration-only re-verifier prompt prepared
  evidence: fold (8 transitions, implementation ACTIVE, G-REPAIR-001 AUTHORIZED) ·
            AUTHORIZATION-DECISION.md:15 verbatim PO/ARB condition · grant scope
            clause · seq 4 FAIL reason · d8a5ee93 file list
  open_items: implementation STOP unrecorded · F-5 · ASD-001 · O-4 · O-6 · Q-1 ·
              Q-2 · §22

next_actor:
  recommended_role: implementation      # 84e5c1f7 — to record its own STOP
  reason: The lane is still ACTIVE and the record does not yet say the repair is
          finished. Its STOP reports IMPLEMENTED (never VERIFIED, R-34/EP-02) and
          is the act that puts the completion evidence into the record. Only then
          does the canonical chain reach a re-verifier.
  blocking_condition: implementation STOP, then human CONTINUATION (Inv E), then a
                      declared fresh candidate, then AST-018 appoint.

authorization:
  current_session_can_continue: false   # capability, NOT authorization (F1)
  authorized_to_act: false
  requires_human_decision: true         # re-verify or not; then adoption (§38)
```

**Traceability:** PO/ARB order 2026-08-24 (9 sections) · `AUTHORIZATION-DECISION.md:15` (the binding condition) · `ADOPTION-DECISION.md:40` · grant `G-REPAIR-001` registered scope · `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL, `seq 4`) · `…-FAIL-DISPOSITION-AND-REPAIR-PATH.md` · `…-REPAIR-001-AUTHORIZATION.md` · `…-REPAIR-001-IMPLEMENTER-APPOINTMENT-84e5c1f7.md` (`C-1`, `O-6`) · repair commit `d8a5ee93` · `Inv E` · `G-3` · `§38` · `R-34`/`EP-02` · `ES-004.3` · `ES-006.1`
