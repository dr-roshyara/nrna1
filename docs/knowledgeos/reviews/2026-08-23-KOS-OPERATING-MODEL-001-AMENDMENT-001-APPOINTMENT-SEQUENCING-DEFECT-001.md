# `KOS-OPERATING-MODEL-001-AMENDMENT-001` — **APPOINTMENT SEQUENCING DEFECT `ASD-001`** + recovery determination

**Work item:** `KOS-OPERATING-MODEL-001-AMENDMENT-001` · **Subject of the verification:** `AST-019`
**Document type:** governance defect record + recovery determination. **ANALYSIS ONLY — no transition was written by this record.**
**Date:** 2026-08-23
**Recorded by:** Governance Engineer — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(disclosed governance-recording capacity; `P-3` responsibility, not a workflow role)*
**Author of the defect:** **the same process.** This record is self-incriminating by design; `R-34`/`EP-02` bars me from ruling on my own remedy, so §6 is a **recommendation to the PO/ARB**, not a decision.
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0)

> ⛔ **No transition · no CANCEL · no STOP · no re-appointment · no AST-019 invocation · no AST-018/019 modification · no verification · no verdict.** The refusal reported by the human is **correct and is not bypassed.**

---

## 1 · Authoritative state, re-grounded from the record (not from conversation)

`php .claude/scripts/workflow-state.php fold KOS-OPERATING-MODEL-001-AMENDMENT-001`

| Field | Value |
|---|---|
| `workItemState` | `OPEN` |
| `roles` | `governance`, `architecture`, `implementation`, `verification` |
| registered sessions | **exactly one** — `84c0f6f6-795e-4c89-a382-733f2c7b7caf`, role `verification`, state **`ACTIVE`**, `predecessor: null` |
| `mutationOwner` | `84c0f6f6-795e-4c89-a382-733f2c7b7caf` |
| `grants` | `[]` |
| `transitions` | **3** |

**Transition sequence, verbatim from the record:**

| seq | type | `recordedBy` | key fields |
|---|---|---|---|
| 1 | `REGISTER` | **`governance`** | `session=84c0f6f6…` · `role=verification` · `predecessor=null` |
| 2 | `HANDOFF` | **`governance`** | `from=null` (bootstrap) · `to=84c0f6f6…` · `token=T-KOS-OPM-001-AMD-001-VER` |
| 3 | `START` | **`human`** | `humanAct` = the PO/ARB's verbatim activation direction of 2026-08-23 |

**AST-018 `next-actor` (read-only):** `LANE ACTIVE` — *"The verification actor currently holds this work and simply continues."*

### When and how `84c0f6f6` became a workflow participant

**At `seq 1`, by a `REGISTER` recorded by `governance` — i.e. by THIS process, on the PO/ARB's activation direction.** Not by its own act, and not by its declaration. The record itself carries the proof: `seq 1` is `recordedBy: governance`, and its `executionContext` cites the activation record and the human direction.

---

## 2 · Was the candidate declaration converted into workflow participation? **NO — proven, not asserted**

**The candidate declaration created nothing.** Three independent pieces of evidence:

1. `84c0f6f6`'s own declaration (`…-VERIFIER-CANDIDATE-DECLARATION-84c0f6f6.md`) records the fold it observed: `sessions: []` · `mutationOwner: null` · `transitions: []` · `grants: []`, and states its non-actions explicitly.
2. **This process's own pre-flight fold, immediately before `seq 1`:** `transitions: 0` · `sessions: []` · `mutationOwner: null`. The item was virgin *after* the declaration existed.
3. `seq 1` is `recordedBy: governance`. Had the candidate self-registered, the record would attribute it to the candidate. It does not — and the candidate's §3 records that it deliberately refused both self-binding via `AST-019` **and** a hand-composed `AST-015 append`.

**The `§5` process rule was never violated by the candidate. It was violated by Governance.**

| Concept | Held? | Where it stands |
|---|---|---|
| **CANDIDATE DECLARATION** — read-only, writes nothing | ✅ **HELD** | `84c0f6f6` declared and stopped, writing nothing |
| **APPOINTMENT** — the governed act that validates eligibility and creates the lane | ❌ **BYPASSED** | see §3 |
| **REGISTERED LANE** | ⚠️ exists, created out of band | `seq 1` |
| **ACTIVE AUTHORIZATION** | ⚠️ genuine: `START` carries a real recorded human act | `seq 3` |

---

## 3 · Root cause — `ASD-001`: the appointment engine was bypassed

**`AST-018 appoint` (UC2 `AppointReviewer`) is the canonical appointment mechanism — the registry names it *"the ONLY writing command."*** It was **not used.** Instead this process hand-composed the three `AST-015 append` transitions directly.

**Why the error was made (cause, not excuse).** The direction said *"using the canonical Governance appointment path… Do not use AST-019 itself to activate its own verifier."* I read "canonical path" as *the canonical first-lane transition pattern* — parent `KOS-OPERATING-MODEL-001` seq 1→2→3, which is itself hand-composed governance-recording — and treated avoiding `AST-019` as the operative constraint. **The correct referent was `AST-018 appoint`.** The parent's precedent made the wrong reading look right; it does not make it right.

**What was lost by bypassing it.** `AST-018 appoint` (`:380–430`) does not merely write the same transitions — it **records the eligibility check as a governed act**: it evaluates `eligibilityOf(candidate, fold, exclude)`, refuses if the candidate is not independent, and then writes the eligibility conclusion **into the `executionContext` itself** (`'INDEPENDENCE: ' . $eligibility['because']`). It also derives `predecessor`/`from` from the fold's current owner rather than from an author's judgement.

**Consequence — the refusal the human hit.** `AST-018 appoint` refuses at `next-actor-orchestration.php:365`:

```
if (isset($fold['sessions'][$candidate]))
    → NOT_ELIGIBLE · "independence — the candidate already holds a lane on this work item"
```

This is a **pre-appointment guard**, and it is **distinct from the substantive independence check** at `:373` (`eligibilityOf`, honouring `--exclude` bars). In `AST-018`'s intended sequence, `appoint` is *what creates the lane*, so any pre-existing lane necessarily means prior participation — a sound inference **that my out-of-band `REGISTER` falsified**.

> **Therefore: the refusal is CORRECT as a re-appointment guard, and it is NOT a finding that `84c0f6f6` lacks independence.** Conflating the two would be the real error. `AST-018` is behaving exactly as designed; it is refusing to appoint a reviewer into a lane that already exists.

**This is a misuse/sequencing error of already-implemented mechanisms — NOT a missing capability.** `AST-018` remains the canonical appointment mechanism; `AST-019` remains the runtime-binding capability. **No new capability, engine, or identity mechanism is proposed or needed** (§6 of the order, and `ES-005.4`).

### What is NOT defective

| Property | Status | Evidence |
|---|---|---|
| Formal validity of the three transitions | ✅ every `AST-015` precondition passed (`exit 0` ×3) | `Inv B` predecessor present · `Inv D` token+tokenRef · `Inv C` bootstrap `from=null` valid while no owner existed · `Inv F`/`G-3` conjunction satisfied |
| Human authority | ✅ **genuine, not manufactured** | `seq 3` `humanAct` is the PO/ARB's verbatim order to activate; `recordedBy: human` |
| Substantive independence of `84c0f6f6` | ✅ intact | PASS on all 16 identity bars · **not** the producer `1899d8bf` · four independent searches (`git grep`, `grep -rl` over `.claude/runtime/`, `.claude/sessions/`, `docs/`, `git log --all --grep`) → **no hits anywhere in the estate** |
| Attributability (`N-16`) | ✅ proven | `AST-017` → `RESOLVED · MATCH · verification · authorized_to_act=true · operable=true`; producer id mentioned in prose does **not** falsely resolve (`UNRESOLVED`) |
| Candidate conduct | ✅ exemplary | declared, disclosed a deviation unprompted, refused two self-binding shortcuts, refused to rule on its own admissibility |

**What IS defective is exactly one thing: the appointment's provenance.** The eligibility facts were established (by the candidate's declaration and this process's reading) but were **never recorded by the mechanism whose job that is.**

---

## 4 · Mechanically available recovery paths (both feasible — stated before either is preferred)

There is **no un-`REGISTER`**: the log is append-only, role is immutable (`R8`), and there is no rollback edge (`R1` no `CLAIM_OWNERSHIP`, `R8` no `ROLE_CHANGE`). The governed edges that touch a session's state are `STOP` · `COMPLETE` · `FAIL` · `CANCEL`.

**Path A — proceed with `84c0f6f6`, defect documented.** No further transition. The lane is `ACTIVE` and `authorized_to_act=true`; **no appointment is required for an actor already appointed and started by a recorded human act.** The `NOT_ELIGIBLE` blocks only a *redundant second* appointment, which is not needed. `ASD-001` is disclosed in the verification record so the verdict is weighed knowing its lane's provenance.

**Path B — abandon `84c0f6f6`, appoint a fresh candidate through the engine.** Mechanically confirmed feasible:
`CANCEL` `84c0f6f6` (→ `CANCELLED`; history preserved, nothing erased) → `AST-018 appoint --work-item=… --role=verification --candidate=<fresh declared id> --human-act='…'`, which writes `REGISTER` (`predecessor` = current owner) + `HANDOFF` (`from` = current owner, permitted by `Inv C`) → **human `START`** (`AST-018` deliberately does **not** write `START`; `G-3` stays human).
Two costs, stated plainly: `84c0f6f6` becomes a **17th barred identity** for a defect **it did not cause**, and `CANCEL` does not clear `mutationOwner` (only `COMPLETE` does), so the cancelled lane remains the handoff predecessor — legal, but it leaves a cancelled owner in the chain.

---

## 5 · Recovery determination — **recommend `A`**, on grounds, not convenience

| Ground | |
|---|---|
| **The gate's substance is satisfied** | Every fact `AST-018`'s independence check exists to establish is true of `84c0f6f6` and is independently corroborated. The guard fired on lane *existence*, not on any independence defect. |
| **`B` destroys a valid lane to repair a document trail** | It consumes a clean identity and improves **no** independence fact. Trading a bounded, documented defect for a permanent cost is not a repair. |
| **House treatment for this class** | The standing rule is *reviews record deviations; implementation repairs them in a later authorized slice* — record and proceed, not re-run. |
| **Precedent** | The parent item's own `seq 1–3` was created by this same hand-composed governance-recording pattern, and the PO/ARB ruled governance-recording by the appointed actor **non-disqualifying**. That precedent supports the lane's **validity**; it does **not** excuse skipping `AST-018`. |

**Two conditions on `A`, both binding:**

1. **`ASD-001` must be disclosed in the verification record itself** — not only here — so the PO/ARB weighs the verdict knowing its lane's provenance.
2. **`A` requires resuming session `84c0f6f6` specifically.** The lane is bound to that runtime identity; a *new* session cannot inherit it. **If `84c0f6f6` cannot be resumed, `A` is impossible and `B` is forced** — not preferred, forced.

**When `B` becomes the right answer instead:** if the PO/ARB judges that verifying a *governed-activation* capability demands a lane whose own activation was itself flawlessly governed. That is a legitimate position — the irony is real, and it is a judgement about the credibility of the eventual verdict, which is the PO/ARB's to make, not mine. **`R-34`/`EP-02`: the author of the defect does not rule on its remedy.**

---

## 6 · Process rule — established and preserved (`§5` of the order)

> **A CANDIDATE DECLARATION is read-only. It MUST NOT perform `REGISTER`, `HANDOFF`, `START`, or `CONTINUATION`.**
> **Only the governed appointment process — `AST-018 appoint` — creates the verification lane.**
> **The human never supplies the UUID. The fresh candidate discovers its own identity. Governance then uses that declared identity as the `--candidate` input to the canonical appointment path.**

**Corollary added by `ASD-001`, which is the part that actually failed:** *Governance itself must appoint **through the appointment engine**. A hand-composed `AST-015 append` reproduces the transitions but not the recorded eligibility check, and it silently falsifies the assumption on which `AST-018`'s own independence guard rests.*

**Status:** `ASD-001` **RECORDED · NOT PROMOTED** (`ES-006.1`, single occurrence; the methodology is FROZEN — this is a misuse finding, not a protocol proposal).

---

## 7 · State after this record — unchanged

`KOS-OPERATING-MODEL-001-AMENDMENT-001`: `OPEN` · one lane `84c0f6f6` `verification` **ACTIVE** · `mutationOwner=84c0f6f6` · `grants: []` · **3 transitions, byte-unchanged by this record**.
`AST-019`: **IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED** · source and tests unmodified.
`AST-015`/`016`/`017`/`018`: unmodified. Parent `KOS-OPERATING-MODEL-001` (L1+L2+L3): **ADOPTED · AUTHORIZED**, `STOPPED`, not reopened.
Open and undecided: `ASD-001` remedy (`A`/`B`) · `Q-1` refinement-#2 tension · `Q-2` declaration not machine-readable · `F-5`/`REVIEW_INDEPENDENCE_POLICY` §22.

## 8 · Next actor

```yaml
session_completion:
  status: ANALYSIS COMPLETE — no transition written; refusal not bypassed
  completed_work: state re-grounded from the record · candidate-declaration
                  hypothesis DISPROVEN with evidence · root cause ASD-001 proven
                  (AST-018 bypassed) · both recovery paths mechanically confirmed
                  · A recommended on grounds · process rule + corollary recorded
  evidence: fold (3 transitions, recordedBy governance/governance/human) ·
            AST-018 next-actor LANE ACTIVE · refusal site :365 vs :373 ·
            appoint write sequence :380-430 · candidate declaration · pre-flight
            fold transitions=0
  open_items: ASD-001 remedy is the PO/ARB's (author cannot rule) · Q-1 · Q-2 · F-5

next_actor:
  recommended_role: human            # PO/ARB disposition of ASD-001
  reason: R-34/EP-02 — the process that caused the defect cannot choose its own
          remedy. Both paths are governed and available; the choice turns on how
          much the verdict's credibility depends on its lane's provenance.
  blocking_condition: A PO/ARB choice of A or B. Under A, whether session
                      84c0f6f6 can be resumed is a hard precondition.

authorization:
  current_session_can_continue: false   # capability, NOT authorization (F1)
  authorized_to_act: false              # this process holds no lane
  requires_human_decision: true
```

**Traceability:** PO/ARB order 2026-08-23 (verbatim, 8 sections) · `…-AMENDMENT-001-VERIFIER-ACTIVATION-84c0f6f6.md` (the defective act, `2189afb0`) · `…-AMENDMENT-001-VERIFIER-CANDIDATE-DECLARATION-84c0f6f6.md` · `…-AMENDMENT-001-VERIFIER-APPOINTMENT-registration.md` (`807fe1ce`) · `…-AMENDMENT-001-PRODUCER-IDENTITY-registration.md` (`59c5c676`) · `next-actor-orchestration.php:365` / `:373` / `:380–430` · `workflow-state.php` transition set · `registry.yaml` AST-018/AST-019 · `ES-005.4` · `ES-006.1` · `Inv B/C/D/F` · `G-3` · `N-11`/`N-16` · `P-3` · `R-1`/`R-8` · `R-34`/`EP-02`
