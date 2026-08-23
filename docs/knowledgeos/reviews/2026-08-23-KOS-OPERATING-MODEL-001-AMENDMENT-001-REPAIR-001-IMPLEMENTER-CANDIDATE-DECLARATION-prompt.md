# `REPAIR-001` — implementer **candidate-declaration-only** prompt · **PHASE 1 of 2**

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Role considered:** `implementation` (fresh) · **Subject:** `AST-019`
**Document type:** declaration-only kickoff prompt. **⚠️ This is NOT the repair commission.** The repair commission is **Phase 2** and issues only *after* appointment, human `START`, and an approved `EP-01` plan.
**Originally recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; NOT the appointee — barred from implementing the slice it scoped)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ### 🔁 `AMENDMENT-001` — 2026-08-23 · amended **in place**, no new artifact
>
> **Amended by:** `claude-code-session:930c65a4-ff37-4a8a-b165-24150606b539` *(governance-recording; `P-3`; holds no lane; **itself barred from implementing** — it recorded the selection decision)*
> **Authority:** PO/ARB selection decision 2026-08-23 — `…-AMENDMENT-001-REPAIR-001-IMPLEMENTER-SELECTION-DECISION.md`
>
> | # | What changed | Why |
> |---|---|---|
> | **A** | Bars are now **participation-first**; UUIDs demoted to a *non-exhaustive aid*; **`e40f3fd0…` added** | The scoping process restarted under a new runtime id — observation `O-5` |
> | **B** | The former note *"the producer `1899d8bf` is not barred"* is **replaced** by the two-statement form in step 2c: the **rule** is unchanged, but the **PO/ARB has excluded it for this slice** | PO/ARB Decision 3 |
> | **C** | Return format extended: `REQUESTED ROLE` · `REPAIR GRANT` · `NON-ACTIONS` · explicit `STOP` | PO/ARB Decision 4 (same shape as the verification pattern) |
>
> **Unchanged and still binding:** step 0 *declare before you orient* · the read-nothing-of-the-subject boundary · the `O-4` `next-actor` warning · the deliberate withholding of the commission · the terminal `STOP`. The pre-amendment text is recoverable in git history.

> **Why this prompt is deliberately thin.** `84c0f6f6` was handed the *full commission* at first start and, following it in order, read ~120 lines of the subject before declaring — recorded as its disclosed §4 ordering deviation, and traced to **prompt selection, not process error**. This prompt exists so that cannot recur. **Step 0 is first for that reason.**

---

## Candidate declaration — `REPAIR-001` implementer *(Phase 1)*

You are a **candidate** implementation actor. You are **not appointed**, **not registered**, and **not authorized**.

**0. Declare before you orient.** Before reading any source file, any test, any report, or any workflow record, do steps 1–2. **Do not read `.claude/scripts/activate-commissioned-fresh-session.php` or its contract test yet.** If you have already read either, **say so in your declaration** — disclosure costs nothing; a concealed deviation costs the appointment.

**1. Determine your process identity** from `CLAUDE_CODE_SESSION_ID` — from the runtime **only**. Never from this prompt, never a CLI argument, never copied from a document. **Nobody will supply it to you; discovering it is your act.**

**2. Assess your independence. Read 2a before 2b — the order is the point.**

**2a — the actual test is PARTICIPATION, not identity.**
> **A different UUID is necessary but NOT sufficient evidence of independence.** A restarted process can report a new `CLAUDE_CODE_SESSION_ID` while retaining the same working context — so a clean-looking identifier proves nothing on its own (observation `O-5`).

So do not ask *"is my id on the list?"*. Ask: **have I, in this conversation or any context I carry, participated in `AST-019`, in this work item, or in this estate's handling of either?** Have I authored, scoped, reviewed, verified, analysed, or advised on it? **Disclose every such contact in full, even partial, even indirect, even if it makes you ineligible.** Only you can see this; the mechanism cannot.

**2b — the identifier list, which is an AID and is NOT exhaustive.**
Confirm you are **not**, and do not carry the context of:
- **`84c0f6f6-795e-4c89-a382-733f2c7b7caf`** — the independent verifier. **It must not repair what it verified** (`R-34`/`EP-02`).
- **`5928b9f9-b4d5-46e9-8c71-c295dace18f8`** — the Governance process that scoped this slice.
- **`e40f3fd0…`** — **the same participant as `5928b9f9`, under a new runtime id after a restart.** It authored `ASD-001`, scoped `REPAIR-001`, recorded the `CONTINUATION` and the grant, and wrote this prompt. **Barred on participation.** *(Only the leading 8 characters are on record; the full id was never written to the estate. If your id begins with `e40f3fd0`, treat yourself as barred and say so.)*
- **`930c65a4-ff37-4a8a-b165-24150606b539`** — the Governance process that recorded the selection decision and amended this prompt.
- **PO/ARB.**

**2c — the producer, stated precisely so you neither over-bar nor over-permit yourself.**
`1899d8bf-2688-4bf3-9787-b4114ddaeec8` — the original `AST-019` producer. **Two statements, both true:**
- **The rule:** it is **NOT categorically barred** from implementing. Repairing one's own code is ordinary engineering, and no rule bars it.
- **The decision for this slice:** the **PO/ARB has excluded it** — *"not the cleanest separation for this defect, because the repair is correcting its own implementation."*

**If you are that process, say so plainly and report `NOT ELIGIBLE` for this slice**, citing the PO/ARB decision and **not** a rule — the distinction is deliberate and must survive into your declaration. It also bars you from the later re-verification.

**3. Read ONLY the authoritative work-item state** — nothing else:
```
php .claude/scripts/workflow-state.php fold KOS-OPERATING-MODEL-001-AMENDMENT-001
php .claude/scripts/session-bootstrap.php --work-item=KOS-OPERATING-MODEL-001-AMENDMENT-001 \
    --process-label=<your bare session id> --role=implementation
```
*(the label is the **bare** id — a `claude-code-session:` prefix never matches. Expect `UNRESOLVED · operable: false`: you hold no lane yet. **Absence is not permission**, and this is the correct reading, not a problem to solve.)*

⚠️ **The fold prints the grant's full scope text.** Report the grant's **identifier and status only**. **Do not begin analysing its scope, planning against it, or looking up the source locations it names** — that is Phase 2, and it is not yours yet.

**4. Do not** create a workflow record · **do not** `REGISTER` yourself · **do not** `HANDOFF` · **do not** `START` · **do not** `CONTINUATION`.
**5. Do not** modify any file — not `AST-019`, not its tests, not the adopted layers, not the workflow record.
**6. Do not** invoke `AST-019` (`activate` or `check`) against a real work item.
**7. Do not** begin the repair. **Authorization to repair is not granted by this prompt and cannot be inferred from it.**

**What you should know about the state you are joining** (facts, so you are not surprised — not an invitation to act):
the work item is `OPEN` with an `AUTHORIZED` grant `G-REPAIR-001`; the only registered lane is the verifier's, `ACTIVE` **only** as a mechanical consequence of the continuation that reopened the item, so `AST-018 next-actor` will tell you *"no new actor is needed"* — **that advice is wrong in this state** (observation `O-4`). Your appointment is the intended next act.

### Return exactly this, then stop

```
PROCESS IDENTITY:        (from CLAUDE_CODE_SESSION_ID, runtime only)
REQUESTED ROLE:          implementation
INDEPENDENCE:            (against 2a AND 2b — participation first; any prior reading disclosed)
PRIOR PARTICIPATION:     (in full — this work item, AST-019, anywhere in this estate; "none" only if none)
ELIGIBILITY:             ELIGIBLE / NOT ELIGIBLE   (+ ground: rule, or PO/ARB decision — name which)
WORK ITEM STATE:         (as the fold reports it)
REPAIR GRANT:            (identifier + status only, from the fold — NOT its scope)
CURRENT AUTHORIZATION:   NOT AUTHORIZED
NON-ACTIONS:             (what you did not do: no register, no handoff, no start, no file
                          modified, no AST-019 invocation, no repair begun, subject unread)
STOP
```

Then **STOP.** Governance verifies your eligibility, appoints your declared identity via `AST-018 appoint --role=implementation --candidate=<your declared id> --human-act='<verbatim>'` *(never hand-composed appends — that was `ASD-001`)*, a person records the `START`, an `EP-01` plan is written and **explicitly approved** — and **only then** does **Phase 2**, the repair commission, reach you.

> ⚠️ **Correction `C-1` (2026-08-24, recorded after this prompt was consumed).** The line above says *"a person records the `START`"*. **That is wrong: `AST-018 appoint` writes the `START` itself** (`next-actor-orchestration.php:430–447`, `recordedBy: 'human'`, carrying the supplied `--human-act`), so appointment and activation happen in **one** act — finding `O-6`. The Governance process that wrote this prompt had read `:380–430` and stopped one block short. Corrected here rather than silently; the declaration this prompt produced is unaffected, and the text is left in place because the record is append-only. Full correction: `…-REPAIR-001-IMPLEMENTER-APPOINTMENT-84e5c1f7.md` §4.

**Do not ask for Phase 2, and do not reconstruct it from the fold.** A declaration that arrives with a plan attached has already broken the boundary this prompt exists to protect.

---

**Traceability:** grant `G-REPAIR-001` (`AUTHORIZED`) · PO/ARB acts 2026-08-23 · `…-REPAIR-001-AUTHORIZATION.md` · `…-REPAIR-001-IMPLEMENTER-SELECTION-DECISION.md` (`AMENDMENT-001` authority) · `…-OBSERVATION-O-5-process-identity-continuity.md` · `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (FAIL) · `…-AMENDMENT-001-FAIL-DISPOSITION-AND-REPAIR-PATH.md` · `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` (`ASD-001`) · `84c0f6f6` declaration §4 (the deviation this prompt prevents) · `O-4` · `INV-ATTR-1/2` · `G-3` · `R-34`/`EP-02` · `EP-01` · `ES-004.3` · `ES-006.1`
