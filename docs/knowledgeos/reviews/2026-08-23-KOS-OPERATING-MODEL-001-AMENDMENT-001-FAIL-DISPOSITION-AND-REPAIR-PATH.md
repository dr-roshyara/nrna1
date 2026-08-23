# `KOS-OPERATING-MODEL-001-AMENDMENT-001` — **FAIL analysis + governed REPAIR PATH** (`REPAIR-001`)

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Subject:** `AST-019` / `ActivateCommissionedFreshSession`
**Document type:** Governance analysis of the FAIL verdict + the scoped repair commission + the Architecture-gate determination. **ANALYSIS AND SCOPING ONLY — no transition, no code change.**
**Date:** 2026-08-23 · **Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; `P-3` responsibility, not a workflow role; holds no lane)*
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ⛔ **No repair is performed here, and none may be performed in the verification lane.** `AST-019` remains **IMPLEMENTED · VERIFICATION COMPLETED WITH RESULT FAIL · NOT ADOPTED · NOT AUTHORIZED.**

---

## 1 · Authoritative state

`KOS-OPERATING-MODEL-001-AMENDMENT-001` — **`STOPPED`** · `mutationOwner: 84c0f6f6` · lane `verification` = `STOPPED` · `grants: []` · **4 transitions** (`REGISTER`/`HANDOFF`/`START`/`STOP` seq 1–4; `seq 4` `recordedBy: verification`, carrying the FAIL verdict).

`AST-018 next-actor` → **`WORK ITEM STOPPED`**: *"Nothing may proceed until a person decides to continue it — the system will not restart it on its own."* `OPTIONS: 1. CONTINUE · 2. LEAVE_STOPPED`.

**The item is sticky-`STOPPED` (`Inv E`). A human `CONTINUATION` is the first gate of any repair.**

## 2 · The blocking defect, independently confirmed at the source

`F-1` is **CONFIRMED** by direct source inspection, not accepted on the verifier's testimony:

- `analyze()` returns exactly `['ready','identity','role','commissionSource','independentOf','humanAct']` (lines 377–385) — **no `fold` key**.
- `writeActivation()` line 401: `$fold = $a['fold'];` → undefined key → `null`; line 402 `$owner = $fold['mutationOwner'] ?? null` → **`$owner` is unconditionally `null`**.
- `$owner` feeds **both** `REGISTER.predecessor` and `HANDOFF.from`.

`F-3` confirmed (caller tests `$written['ok']` at 620; the failure path returns the `refusal()` shape, which has no `ok`). `F-4` confirmed (`'transitionWritten' => true` hard-coded at 200).

**Why this is the right severity.** The failure mode is not a wrong answer — it is a **permanent orphan `REGISTER` in an append-only store**, written *after* `check` reported `READY`. `ASD-001` already established that **there is no un-`REGISTER`**: the log is append-only, role is immutable (`R8`), and no rollback edge exists (`R1`). Probability is low today (the verifier measured: never armed across 23 production records, because `GO-13`/`GO-21` refuse every other non-null-owner case) but the arming condition is **one `FAIL`/`CANCEL` of an owning lane away**, and this estate already uses those edges. **Low probability · permanent consequence · currently mitigated only by the fact that `AST-019` is NOT AUTHORIZED.** The §38 hold is doing real work; it is the mitigation, and it must stay until re-verification passes.

## 3 · ⚠️ Correction to the report's grouping — load-bearing for scope

The commission groups `F-2/F-3/F-4` as **"same root cause"** as `F-1`. **Precisely, they are not, and the difference changes the acceptance criteria.**

| Finding | Root cause | Relationship to `F-1` |
|---|---|---|
| `F-1` | `analyze()` omits `fold`; `writeActivation()` reads it | — |
| `F-3` | the failure path returns the `refusal()` shape, lacking `ok` | **independent defect**, same function family |
| `F-4` | `transitionWritten` is a hard-coded literal, not derived | **independent defect**, same function family |
| `F-2` | *symptom*: two PHP warnings on STDOUT under `display_errors=On` | **`Undefined array key "fold"` (401) is `F-1`'s; `Undefined array key "ok"` (620) is `F-3`'s** |

**Consequence, and it is the reason this correction is recorded:** the report's recommendation for `F-2` — *"Fix `F-1` (removes both)"* — **is not accurate.** Fixing `F-1` removes the `401` warning and makes `INCOMPLETE_SEQUENCE` rarer, but the `620` warning still fires on **every genuine partial write**, which remains a real path (`AST-015` can still legitimately refuse a `HANDOFF`). **A slice that fixed only `F-1` and declared `F-2` closed would ship a still-broken `--json` contract on the partial-write path.**

**Therefore `F-2` closes only when BOTH `F-1` and `F-3` are fixed**, and the acceptance criterion must be stated as *a clean STDOUT/STDERR assertion*, not as a corollary of `F-1`.

**The correct justification for grouping F-1/F-2/F-3/F-4 into one slice is a shared REPAIR LOCUS, not a shared root cause** — all of them live in `writeActivation()`, its `incompleteSequence()` return, and the caller's `ok` test. Grouping is sound for locus economy and reviewability. Calling it one root cause would license the wrong acceptance criteria, which is exactly the *"never let one category silently become another"* failure this estate guards against.

## 4 · Scope of `REPAIR-001`

**IN SCOPE — exactly four findings and one test gap, one file plus its contract test:**

| Item | Change (recommended, not prescribed — the implementer designs it) |
|---|---|
| `F-1` | return the fold from `analyze()` (e.g. `'fold' => $fold`) so `$owner` is the real `mutationOwner` |
| `F-3` | failure path returns `['ok' => false, 'payload' => …, 'exit' => …]`, or the caller tests `!empty($written['ok'])` |
| `F-4` | derive it: `'transitionWritten' => $written !== []` |
| `F-2` | **verified, not assumed**: assert a clean STDOUT *and* STDERR under `display_errors=On`, on the success path **and** the partial-write path |
| `O-1` | **RED first** — a contract test that reaches the write path with a **live non-null `mutationOwner`**, asserting `REGISTER.predecessor === owner` **and** `HANDOFF.from === owner`. Must **fail on today's code** before any fix lands. |

**Files touchable:** `.claude/scripts/activate-commissioned-fresh-session.php` and its contract test **only**.

**OUT OF SCOPE — explicitly:**

- **`F-5`** (`KOS_MECHANISM_PATH` redirects the sole writer). Different locus (`mechanismPath()`, line 91) and **a genuine architecture question**, not a correctness repair: whether an env-var override of the sole-writer seam is acceptable, and why `AST-017`/`AST-018` use hard constants while `AST-019` does not. **Requires an architecture decision; must NOT be silently "fixed" inside a correctness slice.** Remains OPEN.
- Any change to **`AST-015` / `AST-016` / `AST-017` / `AST-018`** or `operating-model.php`.
- **The adopted `KOS-OPERATING-MODEL-001` layers `L1`+`L2`+`L3`** — `ADOPTED` and `AUTHORIZED`; **byte-integrity is an acceptance criterion, not a hope.** The parent item is **not reopened**.
- Adoption or authorization of `AST-019` (`§38` — PO/ARB, not automatic, and not reached by a repair).
- `ASD-001` remedy · `Q-1` · `Q-2` · `F-5`/`REVIEW_INDEPENDENCE_POLICY §22` — all still OPEN, none disposed by this record.
- Any new capability, engine, or identity mechanism (`ES-005.4`).

## 5 · Architecture-gate determination — **NOT required for `REPAIR-001`**

**Determination: `F-1`/`F-2`/`F-3`/`F-4` do NOT require Architecture approval before implementation. `F-5` does.**

Grounds:

1. **These are conformance repairs, not design changes.** The recorded architecture *already* says the activation derives `predecessor`/`from` from the fold's current owner — `ASD-001`'s own analysis credits `AST-018 appoint` with exactly that property, and `AST-019`'s docblock and contract promise it. **The code fails to do what the approved design says.** Restoring conformance introduces no boundary change, no new concept, no ownership change, no context-map change, and no CLI-contract change.
2. **The standing rule is satisfied without a new decision.** *"Finding → Architecture Decision → RED → GREEN → Certification"* requires that a decision **exist**, not that a fresh one be minted: the decision here is `AMENDMENT-001` itself, already approved (EP-01, 2026-08-22), plus the pinned `GO-01..GO-25` contract. `REPAIR-001` implements that decision; it does not amend it.
3. **Stewardship default (Phase 5).** *"Implementation is the default — only a NO carrying implementation evidence of insufficiency opens an ADR/ARB discussion."* No architectural NO exists for F-1..F-4. One **does** exist for `F-5` — the verifier produced implementation evidence that the sole-writer guarantee is not literally true.
4. **`F-5` is the genuine exception** and is excluded from this slice for that reason.

**What IS required before implementation — three gates, none of them Architecture:**

| Gate | Why |
|---|---|
| **Human `CONTINUATION`** | the item is sticky-`STOPPED` (`Inv E`); `AST-018` says only a person may continue it |
| **EP-01 plan, approved** | project standing rule: plan → **explicit human approval of the plan** → implement only the approved plan |
| **PO/ARB authorization of the repair slice** | `AST-019` is under a §38 hold; a repair is a new authorized slice, not a continuation of the producer's original mandate |

**One governance act, not an architecture act:** extending the pinned contract beyond `GO-25` (the `O-1` test) updates the registry's `Contract GO-01..GO-25` note. That is documentation/governance upkeep.

## 6 · The governed repair sequence

**The repair must NOT occur in the verification lane** — and the mechanisms agree with the instruction: that lane is `STOPPED`, and `R-34`/`EP-02` separates the actor that supplies evidence from the actor that repairs. A **fresh `implementation` lane** is required.

```
1  human CONTINUATION on KOS-OPERATING-MODEL-001-AMENDMENT-001   (exits sticky STOPPED, Inv E)
2  fresh implementation candidate session declares its own identity → STOP
        (declaration-only prompt; the human never supplies the UUID)
3  Governance appoints THROUGH THE ENGINE:
        php .claude/scripts/next-actor-orchestration.php appoint \
            KOS-OPERATING-MODEL-001-AMENDMENT-001 \
            --role=implementation --candidate=<declared id> --human-act='<verbatim>'
        → writes REGISTER (predecessor = current owner) + HANDOFF (from = current owner)
        ⚠️ NOT hand-composed AST-015 appends — that was ASD-001
4  human START (G-3)
5  EP-01 plan → approved → RED (O-1 test fails on today's code) → GREEN (F-1, F-3, F-4)
        → F-2 asserted clean on both paths → full GO suite + WorkflowEngine regression
        → byte-integrity of L1/L2/L3 and AST-015/016/017/018 re-proven
6  STOP  (implementation reports IMPLEMENTED, never VERIFIED — R-34/EP-02)
7  re-verification by a process that is NOT 84c0f6f6 (first verifier),
        NOT 1899d8bf (producer), NOT 5928b9f9 (this governance process)
8  PO/ARB adoption + authorization decisions — separate, and NOT automatic
```

**Step 3 is where the `ASD-001` lesson is spent.** The appointment for the repair slice must go through `AST-018 appoint`, which records the eligibility conclusion *into the lane itself* — the property whose absence produced `ASD-001`, and, per the verifier's `O-3`, the same property `F-1` shows `AST-019` failing to deliver.

## 7 · `ASD-001` — what the verifier's independent assessment settles, and what it does not

Reported faithfully because it is not favourable to this process, and because part of it is exculpatory:

- **Settled (favourable):** the verifier assessed `ASD-001` independently and found it does **not** invalidate the lane or the verdict — the transitions are formally valid, the human act genuine, and independence re-established from primary evidence. Further: **using `AST-019` would have produced an identical record** (owner was `null`, first lane), so the bypass **concealed nothing** about `F-1`; and `AST-019` could not have repaired `ASD-001` either (its `V5a`/`GO-07` guard refuses an identity already holding a lane, the same shape as `AST-018`'s).
- **Not settled (unfavourable):** `O-3` finds `ASD-001` and `F-1` are **the same defect class from opposite directions** — a Governance process hand-derived the ownership facts; `AST-019` attempts to derive them and silently does not. **Two independent instances of the appointment/activation path being wrong about ownership provenance is corroboration, not coincidence.**
- **The `ASD-001` remedy remains the PO/ARB's** and is untouched here.

## 8 · Next actor

```yaml
session_completion:
  status: FAIL analysed · repair scoped · Architecture gate determined — NO transition, NO code change
  completed_work: F-1 independently confirmed at source (analyze() 377-385 vs
                  writeActivation() 401-402) · F-3/F-4 confirmed · grouping
                  corrected (shared LOCUS, not shared root cause; F-2 needs F-1
                  AND F-3) · REPAIR-001 scoped · F-5 excluded as an architecture
                  question · Architecture approval determined NOT required for
                  F-1..F-4 · governed sequence prepared
  evidence: fold (STOPPED, 4 transitions) · AST-018 next-actor WORK ITEM STOPPED ·
            source inspection · verification report §6.3/§12/§14/§15
  open_items: ASD-001 remedy · Q-1 · Q-2 · F-5 (architecture) · O-1 contract gap ·
              REVIEW_INDEPENDENCE_POLICY §22

next_actor:
  recommended_role: human            # PO/ARB
  reason: The item is sticky-STOPPED; AST-018 states only a person may continue it.
          A repair is a NEW authorized slice under the §38 hold, and EP-01 requires
          the plan be approved before implementation. Governance can scope the
          repair (done here) but neither authorizes it nor executes it.
  blocking_condition: human CONTINUATION, then PO/ARB authorization of REPAIR-001,
                      then an approved EP-01 plan.

authorization:
  current_session_can_continue: false   # capability, NOT authorization (F1)
  authorized_to_act: false              # this process holds no lane
  requires_human_decision: true
```

**Traceability:** independent verification `…-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` (verdict FAIL, `seq 4`) · `ASD-001` `…-APPOINTMENT-SEQUENCING-DEFECT-001.md` · activation `…-VERIFIER-ACTIVATION-84c0f6f6.md` · declaration `…-VERIFIER-CANDIDATE-DECLARATION-84c0f6f6.md` · producer identity `…-PRODUCER-IDENTITY-registration.md` · `AST-019` `98575324` · source `activate-commissioned-fresh-session.php:91,200,377-385,401-402,620` · `next-actor-orchestration.php:365,373,380-430` · parent adoption `9502168a` + authorization `e89b3b41` · `Inv B/C/E`, `R1`, `R8` · `G-3` · `§38` · `EP-01`/`EP-02` · `R-34` · `ES-002.1/.2` · `ES-005.4` · `ES-006.1`
