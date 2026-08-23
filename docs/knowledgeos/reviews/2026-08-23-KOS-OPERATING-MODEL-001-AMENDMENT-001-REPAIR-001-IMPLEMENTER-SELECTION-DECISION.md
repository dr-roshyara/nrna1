# `REPAIR-001` — **implementer selection decision** · fresh session · two-phase kickoff

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Subject:** `AST-019` · **Grant:** `G-REPAIR-001` (`AUTHORIZED`)
**Document type:** record of a PO/ARB selection decision, its two consequences for the kickoff instrument, and one filed observation
**Date:** 2026-08-23
**Recorded by:** Governance-recording process — `claude-code-session:930c65a4-ff37-4a8a-b165-24150606b539`
*(self-declared, not attestable — `INV-ATTR-1/2`, `G-2`. `P-3` responsibility; **holds no lane**; bootstrap `UNRESOLVED · operable: false`. **This process is itself now barred from implementing `REPAIR-001`** — see §6.)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ⛔ **Nothing was appointed, started, adopted or authorized by this record, and no code or test was touched.** `AST-019` remains **IMPLEMENTED · VERIFICATION COMPLETED WITH RESULT FAIL · NOT ADOPTED · NOT AUTHORIZED**. Fold re-read before writing: `workItemState: OPEN` · `mutationOwner: 84c0f6f6-…` · one lane (`verification`, `ACTIVE`) · `grants: 1 · G-REPAIR-001 · AUTHORIZED`. **Unchanged after writing.**

---

## 1 · The human act (`G-3`), operative text

> *"I would **not** appoint that session as the REPAIR-001 implementer. … Choose: **Fresh implementer session.** Do **not** use `e40f3fd0` for implementation. Also do **not** use `1899d8bf` by default just because its literal identity is not currently barred. The original producer implementing its own repair is technically possible under the current mechanism, but it is not the cleanest separation for this defect because the repair is correcting its own implementation. … Do **not** put a giant REPAIR-001 technical commission into the candidate-declaration prompt. Use the same two-phase pattern that we established for verification. … **2. Start a genuinely fresh implementer session.** And the fresh session should discover its new ID itself; you do not provide it."*

PO/ARB, in-session 2026-08-23. **This is a selection and instrument decision. It grants no new authority, changes no grant, and writes no transition** — `G-REPAIR-001` already carries the authorization, and the appointment gate remains unsatisfied.

## 2 · Decision 1 — the implementer is a **genuinely fresh session**

Selected: **a fresh implementation candidate**, previously unparticipating in this work item, in `AST-019`, and in this estate's handling of either.

This **narrows** `…-REPAIR-001-AUTHORIZATION.md` §6, which had recorded a fresh session as *"preferred, not required."* **It is now the decision.** §6's *reasoning* is not reopened or contradicted — it correctly held that no rule categorically bars self-repair; the PO/ARB has simply exercised the choice that reasoning left open. **A permission that exists is not an instruction to use it.**

## 3 · Decision 2 — `e40f3fd0…` must **not** implement

**Barred.** Not on identity — on **participation**. That process authored `ASD-001`, scoped `REPAIR-001`, recorded the `CONTINUATION` (`seq 5`) and the grant `G-REPAIR-001`, and wrote the candidate-declaration prompt. Appointing it would **collapse the separation between Governance analysis and Implementation** — the same separation `R-34`/`EP-02` exists to hold.

**The mechanism would not have caught this.** `AST-018 appoint` might well have accepted `e40f3fd0…`: it appears on no bar list, and `5928b9f9` — the identifier under which all of that work is signed — is a *different string*. The bar is real; the mechanical check would have passed. **That gap is filed separately as `O-5`** (§5).

## 4 · Decision 3 — `1899d8bf…` is **excluded by decision**, not by rule

Two statements, both true, and they must not be merged:

| | |
|---|---|
| **The rule** | **`1899d8bf-2688-4bf3-9787-b4114ddaeec8` is NOT categorically barred from implementing.** Repairing one's own code is ordinary engineering. `…-REPAIR-001-AUTHORIZATION.md` §6 stands **unamended** on this point. |
| **The decision for this slice** | **It must not be used for `REPAIR-001`.** *"Not the cleanest separation for this defect, because the repair is correcting its own implementation."* |

Recorded this way on purpose: **a selection decision must not be laundered into a rule.** If the exclusion were written as a bar, the estate would silently acquire a general prohibition on self-repair that nobody decided and no evidence supports — and the eligible pool would shrink for no governed reason. It is one PO/ARB choice, on one slice, for a stated reason.

## 5 · Decision 4 — `O-5` filed as a separate governance observation

On the PO/ARB's explicit direction, the restart phenomenon is recorded on its own:
**`…-AMENDMENT-001-OBSERVATION-O-5-process-identity-continuity.md`** — *process context continuity ≠ workflow session identity continuity*.

Its two load-bearing lines, reproduced here only as pointers:
- **Independence must be assessed on prior participation, not on identity difference.** A different UUID is necessary but not sufficient.
- **The safe rule:** *a restarted process is a new candidate until Governance explicitly establishes its participation.* The remedy of treating UUIDs as permanent identities is **explicitly rejected as dangerous.**

**RECORDED · NOT PROMOTED** (`ES-006.1`, n=1; methodology **FROZEN**). No mechanism change is proposed by it or by this record.

## 6 · Decision 5 — the kickoff instrument is **two-phase**, and the existing prompt is amended

**Phase 1 — candidate declaration only.** The fresh session receives identity, independence, non-actions and `STOP`. **No technical scope.**
**Phase 2 — the repair commission.** Issued **only after** the lane is appointed and `START`ed and an `EP-01` plan is approved.

*Why: `84c0f6f6` was handed the full commission at first start, followed it in order, and read ~120 lines of the subject before declaring — its disclosed §4 ordering deviation, traced to **prompt selection, not process error.* The two-phase pattern is the standing fix, established for verification and now bound for implementation.*

**`…-REPAIR-001-IMPLEMENTER-CANDIDATE-DECLARATION-prompt.md` was already declaration-only** and already carried *"step 0 — declare before you orient."* It is **amended in place** (`AMENDMENT-001`, no new artifact) on three points, each a direct consequence of §2–§5:

| # | Change | Cause |
|---|---|---|
| **A** | Bars restated as **participation-first**, with UUIDs demoted to a *non-exhaustive aid*; `e40f3fd0…` added | Decision 2 · `O-5` |
| **B** | The *"the producer is not barred"* note **replaced** by the §4 two-statement form — rule preserved, slice exclusion stated | Decision 3 |
| **C** | Return format extended to the PO/ARB's shape: `REQUESTED ROLE` · `REPAIR GRANT` *(identifier + status from the fold only — **not** its scope)* · `NON-ACTIONS` · explicit `STOP` | Decision 4 |

**Amended, not annotated** — deliberately, and this is a departure from the annotate-above precedent used for the preserved PO/ARB reviewer prompt on 2026-08-22. Grounds: this instrument is **unconsumed** (no process has run it) and was authored by Governance, not by the PO/ARB, so it is a *live instrument* rather than *historical text*; and change **B** removes a sentence that now **contradicts** Decision 3 — a candidate reading a bar notice above a body that still says the opposite is worse off than one reading a single corrected instruction. The original text remains recoverable in git history, and the amendment is enumerated in the prompt's own header.

**What is NOT changed in the prompt:** step 0 *declare before you orient* · the read-nothing-of-the-subject boundary · the `O-4` `next-actor` mis-advice warning · the withholding of the commission · the terminal `STOP`.

## 7 · The sequence, unchanged except where §2–§6 bite

```
Governance
    ↓
fresh implementation candidate                      ← Decision 1 (not e40f3fd0, not 1899d8bf)
    ↓
candidate declares runtime identity BEFORE reading subject   ← Phase 1 only
    ↓
Governance verifies eligibility
    ↓
AST-018 appoint --role=implementation --candidate=<declared id> --human-act='<verbatim>'
    ↓                                                 (NOT hand-composed appends — that was ASD-001)
human START (G-3)
    ↓
EP-01 plan → explicit human approval OF THE PLAN
    ↓
RED (O-1 fails on today's code)
    ↓
GREEN (F-1, F-3, F-4) · F-2 asserted clean on success AND partial-write paths
    ↓
regression: full GO suite + WorkflowEngine · byte-integrity of L1/L2/L3 and AST-015/016/017/018 re-proven
    ↓
STOP — reporting IMPLEMENTED, never VERIFIED (R-34/EP-02)
    ↓
fresh verifier   ← barred: 84c0f6f6 · 1899d8bf · 5928b9f9/e40f3fd0 · 930c65a4 · the REPAIR-001 implementer
```

**Gate status:** human `CONTINUATION` ✅ (`seq 5`) · slice authorization ✅ (`G-REPAIR-001`) · **candidate declaration ⬜ the next act** · appointment ⬜ · human `START` ⬜ · approved `EP-01` plan ⬜.

**`F-5` remains HELD** for a separate Architecture decision and must not be fixed, mitigated, or documented away inside `REPAIR-001`. **`ASD-001` remains preserved.** **The verification history is neither reopened nor rewritten.**

## 8 · Bars now standing, consolidated

| Role | Barred | Ground |
|---|---|---|
| **`REPAIR-001` implementer** | `84c0f6f6…` *(verifier — must not repair what it verified)* · `5928b9f9…`/`e40f3fd0…` *(scoped the slice; **one participant, two identifiers** — `O-5`)* · `930c65a4…` *(this process — recorded the selection decision and amended the instrument)* · PO/ARB | `R-34`/`EP-02`; participation, not identity |
| | `1899d8bf…` — **excluded by PO/ARB decision for this slice; NOT categorically barred** | §4 |
| **Re-verification afterwards** | all of the above **plus** whoever implements `REPAIR-001` | independence from the work under check |

**This process added itself to the implementer bar the moment it recorded §2–§6.** Governance-recording is not a lane, but it is participation.

## 9 · Next actor

```yaml
session_completion:
  status: implementer SELECTION decided (fresh session) · e40f3fd0 barred ·
          1899d8bf excluded by decision · two-phase kickoff bound · Phase-1 prompt
          amended in place · O-5 filed · NO appointment, NO transition, NO code change
  completed_work: PO/ARB selection decision recorded · O-5 observation filed
                  separately as directed · declaration-only prompt amended on three
                  points (participation-first bars · producer-exclusion form ·
                  PO/ARB return shape) · bars consolidated · self-bar declared
  evidence: fold re-read before and after (OPEN · mutationOwner 84c0f6f6 · 1 lane ·
            G-REPAIR-001 AUTHORIZED — identical) · bootstrap for 930c65a4 =
            UNRESOLVED/operable:false · grep: e40f3fd0 = 0 hits, 5928b9f9 = 15
            files, 930c65a4 = 0 · doc-placement exit 0
  open_items: F-5 (Architecture decision) · ASD-001 remedy · O-4 · O-5 (n=1, not
              promoted) · REVIEW_INDEPENDENCE_POLICY §22 placeholder · Q-1 · Q-2

next_actor:
  recommended_role: human
  reason: An actor identity cannot be invented (INV-ATTR-1/2); AST-018 refuses to
          appoint without a candidate because "a fresh actor has no identity until
          its own process reports one". The PO/ARB's own instruction is decisive —
          "the fresh session should discover its new ID itself; you do not provide
          it." So the person must start a genuinely fresh session and paste the
          Phase-1 prompt. No Claude process can perform this act for them.
  blocking_condition: a fresh implementation session declaring its own runtime
                      identity and prior participation, then Governance eligibility
                      check, then AST-018 appoint, then human START, then an
                      approved EP-01 plan. Only then does Phase 2 issue.

authorization:
  current_session_can_continue: false   # capability, NOT authorization (F1)
  authorized_to_act: false              # holds no lane; bootstrap UNRESOLVED
  requires_human_decision: true
```

---

**Traceability:** PO/ARB act 2026-08-23 (§1) · `…-REPAIR-001-AUTHORIZATION.md` §6 *(narrowed by Decision 1, unamended on self-repair)* · `…-REPAIR-001-IMPLEMENTER-CANDIDATE-DECLARATION-prompt.md` *(amended in place, `AMENDMENT-001`)* · `…-OBSERVATION-O-5-process-identity-continuity.md` · `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL, `seq 4`; `O-1`…`O-3`) · `…-FAIL-DISPOSITION-AND-REPAIR-PATH.md` · `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` (`ASD-001`) · `…-VERIFIER-CANDIDATE-DECLARATION-84c0f6f6.md` §4 *(the ordering deviation the two-phase pattern prevents)* · `AST-015` `seq 5` · grant `G-REPAIR-001` · `AST-018 appoint` · `INV-ATTR-1/2` · `Inv E`/`Inv H` · `G-2`/`G-3` · `§38` · `EP-01`/`EP-02` · `R-34` · `ES-004.3` · `ES-005.4` · `ES-006.1`
