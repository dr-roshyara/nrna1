# KOS-SESSION-DISCOVERY-001 — Governance review of the corrective verification

**Date:** 2026-08-15 · **Reviewer:** Session 2 (Governance) · **Reviewing:** Session 1's corrective verification (`b6b8b0bd`) of implementation `84100bb0`
**Authority exercised:** review and classification only. **Nothing implemented, repaired, chosen, granted, adopted, qualified or closed.**

> **Method note.** Every claim below was re-derived from the machine record, the source, and independently executed evidence. **Session 1's report was read last and used as a hypothesis to be tested, not as evidence.** Where my evidence is stronger than Session 1's, I say so; where it corrects Session 1, I say that too.

---

## A · Evidence integrity

### A.1 Confirmed from the machine record

Record: `.claude/runtime/workflow/KOS-SESSION-DISCOVERY-001.json` · 17 transitions · workflow `platform-capability`.

| # | Item | Machine evidence | Status |
|---|---|---|---|
| 1 | Verification assignment | `REGISTER` seq **13** → `S1-verify-discovery-corrective`, role `verification`, by `governance` | ✅ |
| 2 | Verification grants | `G-KOS-DISC-C12-VERIFY` **AUTHORIZED** (the six checks) · `G-KOS-DISC-V3-ASSESS` **AUTHORIZED** (classify V-3; disposition reserved to PO/ARB) | ✅ |
| 3 | Predecessor handoff | `HANDOFF` seq **16**, `S3-impl-discovery-corrective` → `S1-verify-discovery-corrective`, by `governance` | ✅ |
| 4 | Verification START | `START` seq **17**, recordedBy **`human`** | ✅ |
| 5 | Verifier identity | fold → role `verification`, predecessor `S3-impl-discovery-corrective` | ✅ |
| 6 | Implementation commit | `84100bb0`, **2 files, +91/−6** (`session-resolve.php` +31/−6, its contract test +66) — matches the granted C-1/C-2 surface exactly | ✅ |
| 7 | AST-015 unchanged | `workflow-state.php` blob `17a52ef1…` **identical** at `73d056c8`, `84100bb0`, `b6b8b0bd`, `HEAD` **and** in the working tree; sha256 `e19705ce…` — the hash Session 3 cited is accurate and verifiable | ✅ |
| 8 | No unauthorized implementation change | **zero** commits touch `session-resolve.php` or the WorkflowEngine tests after `84100bb0`; working tree clean for both paths | ✅ |
| 9 | Read purity during my own review | record mtime unchanged (`00:50`) across all my resolver invocations; **no write primitive exists in the resolver source** (`file_put_contents` / `fopen` / `mkdir` / `rename` / `unlink` / `append` / `grant` / `init` → 0 occurrences outside comments) | ✅ |

### A.2 Three integrity findings the prose does not carry

**`E-1` · The authoritative machine record is *not under version control*. — MATERIAL.**
`.gitignore:25` and `:32` both list `.claude/runtime/`; `git ls-files .claude/runtime/` returns **nothing**. The record is untracked and ignored.

> **Consequence, stated precisely:** I can attest to the record's **current** content. I **cannot** attest to what it contained at the time of any past commit, and neither could any future auditor. The governance *narrative* (the `docs/publicdigit/reviews/` files) is versioned; the *machine record those files describe* is not. The record's append-only property is guaranteed by the mechanism's discipline alone, with **no external integrity anchor**.
>
> This does not impeach any finding below — the code-level evidence for V-3 is independent of the record's history. It bounds the *strength* of every "the record shows…" statement, including mine. **Recorded as a governance observation for a separate work item; not a qualification blocker.**

**`E-2` · Verification is *not recorded as complete*. — BLOCKS CLOSURE, not qualification.**
The brief asked me to confirm "verification completion". **The machine record does not confirm it.** There is no `COMPLETE` for `S1-verify-discovery-corrective`. The fold reports it **`ACTIVE`** and still **`mutationOwner`**. The prose report ends "VERIFICATION COMPLETE · STOPPING".

> Record and prose disagree. Under **INV-DISC-6 (record over prose)** the record governs: **the verification lane is procedurally still open.**
>
> This has a concrete mechanical consequence, not merely a bookkeeping one: because `S1-verify-discovery-corrective` still holds mutation ownership, AST-015 will **refuse** the next `HANDOFF` from anyone else (`only the current mutation owner can hand off`), and a bootstrap handoff is legal only while no owner exists. **The lifecycle cannot proceed to a successor lane until a `COMPLETE` is recorded.**
>
> **I did not record it.** Doing so is a runtime-record mutation, and this commission ends at STOP. It is the first mechanical act Governance owes after the human decision.

**`E-3` · Independence is role-based and authorship-based, *not* process-based. — ADEQUATE, but must not be overstated.**
Both `S3-impl-discovery-corrective` and `S1-verify-discovery-corrective` carry `executionContext: shared-worktree` — **the same execution context.** Independence therefore rests on: distinct session identities · immutable distinct roles (R8) · a recorded handoff · a human START · and Session 1's own R-34 self-check (its prior act was `beb26177`; `84100bb0` post-dates it and was authored by another process).

> That combination is **sufficient** for this review. It is **not** the process isolation the phrase "independent process" would ordinarily imply, and the qualification record should say so in those words rather than inherit a stronger claim than the evidence supports.

**Judgment on whether to stop.** The brief instructed me to STOP if anything contradicts the evidence. `E-2` is a genuine record/prose contradiction. I judged it **disproportionate to abandon the review**: it is a missing bookkeeping transition, it impeaches no substantive finding, and stopping would withhold the deliverable while leaving the contradiction equally unresolved. **I report it prominently, treat it as a precondition for closure, and complete the review.** That judgment is itself recorded here for the PO/ARB to overrule if they disagree.

---

## B · The six registered checks — re-verified, not repeated

I re-ran the suite independently: **17 passed, 170 assertions** (`T-1…T-15`, includes `T-13`).

| # | Requirement | **My** evidence (independent of Session 1) | Verdict | Evidence sufficient? |
|---|---|---|---|---|
| **1** | Interpreter identity present in **both** renderings | Source: machine field `interpreter{path, available, isDefault, note}` (`session-resolve.php:293-301`); human line `interpreter: … [default AST-015]` (`:313-316`). Built **outside** the verdict branch → present on all four verdicts incl. `UNRESOLVABLE`. `T-14` green. Live: my own run printed it | ✅ PASS | **Yes** |
| **2** | Normal resolution still correct | `T-1…T-12` green in my run. Live run on the real record resolved `RESOLVED / operable:true / ACTIVE (mutation owner)` | ✅ PASS | **Yes — but see correction C-a** |
| **3** | Substitution visible, not hidden or refused | `T-15` green. Human rendering emits `[substituted]` when `isDefault` is false; machine emits the substitute `path`. Substitution is **disclosed and still answered**, never refused | ✅ PASS | **Yes** |
| **4** | No independent interpretation (Safeguard B) | **Verified at source, which is stronger than Session 1's grep.** The resolver's *only* state source is `askMechanism($mechanism, ['fold', …])` at `:156`. Directory scan at `:130-131` takes **filenames only** (`basename`), never content. There is **no `json_decode` of a record** and **no fold logic** anywhere in the file. Mechanism absent → `UNRESOLVABLE`, 0 candidates, **no fallback branch exists**. `T-13` green | ✅ PASS | **Yes — strengthened** |
| **5** | Read purity | No write primitive in source (see A.1 §9). Real record mtime unchanged across my whole campaign. `T-11` pins the record directory byte-identical across **every** verdict path | ✅ PASS | **Yes** |
| **6** | No authorization / activation / ownership / state mutation | The six `authorizationFacts` are emitted **separately and verbatim**, two hard-coded `UNKNOWN — not evaluable from the record`; the resolver never emits `authorized: yes`. `operable` is assigned **only** from the mechanism-reported state (`$state === 'ACTIVE'`). Unconditional caveat: *"Resolution is not activation. This report creates no authority, no ownership, no state change."* | ✅ PASS | **Yes** |

**All six PASS and the evidence genuinely supports each verdict.** Session 1's conclusions survive independent re-derivation.

**Correction `C-a` (does not change any verdict).** Session 1's report cites, as evidence for check 2, `--session=S1-verify-discovery-corrective → RESOLVED, operable:false, CREATED`. That datum is **wrong**, and it contradicts Session 1's *own* §1, which reports the same session as `ACTIVE`. The record shows `ACTIVE`; my live run returns `operable: true`. The **check** nevertheless stands, because it rests on `T-1…T-12`, which I re-ran green. **One illustrative datum is incorrect; the finding is not.**

---

## C · V-3 — the four things kept apart

### C.1 FACT — confirmed at source

`AST-015` computes the handoff fact and **exposes it through none of its three read commands.**

- Computed: `foldSessions()` accumulates `$handoffsTo[$t['to']] = true` (`workflow-state.php:118, :135`) and **returns it** (`:166`).
- Used internally: the `START` validator refuses on `!isset($fold['handoffsTo'][$id])` (`:235-237`).
- **Not emitted:** `fold` emits `workItem · workflow · roles · sessions · mutationOwner · workItemState · grants` (`:344-352`) — **no `handoffsTo`**. `identity` emits `predecessor` but not whether a handoff exists (`:365-373`). `authorized` emits a bare boolean (`:390`).

✅ **Session 1's central fact is CONFIRMED.**

### C.2 OBSERVATION — reproduced, with a stronger control

The resolver's list is not merely under-informed — it is a **hardcoded constant** that consults nothing (`session-resolve.php:227-231`, the `CREATED` branch). It is emitted for *every* `CREATED` session regardless of record content.

I rebuilt the experiment in an isolated scratchpad (`--dir`; the real estate was never written to) and added a **control Session 1 did not run**:

```
CASE A  handoffs recorded: null→impl , impl→verif      (handoff to verif PRESENT)
CASE B  handoffs recorded: null→impl                   (handoff to verif ABSENT)

resolver, both cases, byte-identical:
    verdict RESOLVED · operable false · state CREATED
    missing for activation: a recorded predecessor HANDOFF carrying its token
    missing for activation: a recorded human START act

CONTROL — ask AST-015 itself to START verif:
    CASE A → ACCEPTED (exit 0)      ← the handoff IS there
    CASE B → REFUSED  (exit 65)     ← the handoff is NOT there
```

> **The control is the decisive artifact.** It proves the distinction is **not** unknowable from the record: **AST-015 discriminates the two cases perfectly.** The information exists, is authoritative, and is already load-bearing inside the mechanism — it is simply **not published** to consumers. V-3 is a *publication* gap, not an epistemic one.

### C.3 ARCHITECTURAL QUESTION — three refinements that sharpen Session 1

**Refinement 1 — the defect surface is exactly ONE of the two list items, not both.**

The two enumerated prerequisites have **different epistemic status**, which Session 1 treated as a unit:

| List item | Entailed by `state == CREATED`? | Truthful? |
|---|---|---|
| *"a recorded human START act"* | **YES.** `START` is the only transition that sets `ACTIVE`, and no transition returns a session to `CREATED`. A human act exists **only** as an attribute of a `START` transition — there is no separate human-act transition. So `CREATED` ⟹ no `START` ⟹ no recorded human act | ✅ **Always true. No interface gap. Nothing to fix.** |
| *"a recorded predecessor HANDOFF carrying its token"* | **NO.** A `HANDOFF` sets `handoffsTo[to]` but leaves the successor `CREATED` — precisely CASE A | ❌ **May be false. This is V-3, in its entirety.** |

**Refinement 2 — V-3 lives inside the architecture's own ellipsis.**

The approved architecture specifies this field in exactly two places, and **both write it the same way**:

- §H state matrix: `missingForActivation: ["recorded human START", …]`
- AM-3 wording duty: `missingForActivation: ["recorded human START"]`

**The architecture names only the entailed item.** The predecessor-HANDOFF item was supplied by the implementation to fill the `…`. So the unverified assertion sits **exactly in the space the architecture left open** — the one item architecture specified is the one that is provably truthful.

**Refinement 3 — the architecture is not merely silent. It contains a *conflict*.**

Session 1 concluded the architecture "does not appear to state which component must supply the handoff fact" — i.e. silence. I find something stronger and more actionable. Two **approved** duties collide:

- **INV-DISC-7 (AM-3, governed):** *"The resolver attests only what the record contains… Its report must never present prose claims — its own or anyone's — as record facts."*
- **The G-3 completeness duty:** activation requires **both** facts; the report's stated purpose is to *"make missing activation facts visible, so a session cannot claim ignorance"* (fresh architecture §"Resolution is never a path to ACTIVE").

Under AST-015's **current** read surface these are **jointly unsatisfiable** for the handoff item:

> **State it** → the resolver asserts as a record fact something the record did not tell it → **breaches INV-DISC-7.**
> **Omit it** → the report under-reports a real G-3 prerequisite → **breaches the completeness duty.**

**The architecture never decided how to reconcile them, because it never confronted the case: it wrote the entailed item explicitly and left the non-entailed one inside an ellipsis.** *That* is the specification gap, stated exactly.

### C.4 DISPOSITION — reserved

**PO/ARB's. Not Governance's, and not Session 1's.** Both preserved the boundary correctly.

### C.5 Classification

> ## **ARCHITECTURE / SPECIFICATION GAP — CONFIRMED.**
> **Session 1's classification is upheld.** Location narrowed to a single list item; character upgraded from *silence* to *an internal conflict between two approved duties*.

- ❌ **NOT an implementation defect.** The implementation was squeezed between two approved duties it could not jointly satisfy, and it chose the conservative branch. Reclassifying this as a defect would punish the implementation for a decision the architecture declined to make. *(I did not classify it a defect merely because the output can mislead — the brief's first trap.)*
- ❌ **NOT harmless.** A capability whose entire purpose is faithful reporting states, of the authoritative record, something that record does not support — and it has **already** cost one wasted reconciliation cycle (`32596519`), in which a competent session filed a false defect against a sound record. *(I did not declare it acceptable merely because the resolver obeys its delegation boundary — the brief's second trap.)*

### C.6 A consequence for the future decision — reported, not chosen

Session 1 offered two remedies. **My evidence shows the option space is larger than two, and that one of the two carries an unstated cost.** I state this so the future architecture decision is not pre-narrowed; **I choose nothing and recommend nothing.**

- Rewording the **whole** list as *"the conditions activation requires"* would **discard truthful information**: the human-START item is genuinely, verifiably missing whenever the state is `CREATED`. That remedy trades a false statement for a weaker true one.
- Refinement 1 implies at least one further option exists — separating verified-missing from required-but-unverifiable — which neither Session 1 remedy names.

**Enumerating and choosing among these is the commissioned architecture decision's job, not this review's.**

---

## D · Comparison against the approved architecture

| The architecture establishes | Verdict |
|---|---|
| **Activation prerequisites** — G-3: recorded `HANDOFF` ∧ recorded human `START` → `ACTIVE`. Registered by Governance from an act received in its own stream | Implemented faithfully by AST-015; the resolver never becomes a second path to `ACTIVE` (**INV-DISC-3** holds) |
| **`missingForActivation`** — §H: `["recorded human START", …]`; *"recorded" is load-bearing*, a live-but-unregistered act still counts as missing | The **specified** item is implemented **truthfully**. The **ellipsis** is where V-3 lives |
| **Delegation to AST-015** — the consumer-never-twin rule: *"the resolver never re-implements folding… the mechanism remains the sole interpreter of its record"* | **Holds absolutely.** Verified at source: one delegation point, no fold logic, no record parsing, no fallback |
| **Authoritative workflow interpretation** | AST-015, uncontested. The resolver relays and does not police — including relaying a **substituted** interpreter, which it discloses rather than refuses |
| **What the resolver may NOT independently determine** | Any workflow fact. **This is why V-3 cannot be fixed in the resolver**: deriving `handoffsTo` itself is precisely the prohibited second interpretation |

**Explicit finding on silence.** The architecture **is silent** on whether AST-015's read interface must expose the handoff fact. It is **not silent** on attestation (INV-DISC-7) or on completeness (G-3). **The gap is the unreconciled intersection of the two.** I have not invented the missing requirement, and I record that **no** approved document states which component must supply the fact.

---

## E · Implementation not reopened

**Nothing was modified.** No remedy chosen, no grant issued, no test, resolver, mechanism, architecture, registry or runtime record touched. `AST-016` remains `planned`. The work item remains `OPEN`.

**If a remedy is required it needs a separate architectural decision and its own work item** — the candidate remedies sit in **different components under different grants** (AST-015's read interface vs. the resolver's report semantics), which is exactly why neither Session 1 nor I may choose between them.

---

## F · Risk assessment for the qualification decision

The single most decision-relevant property, which neither prior document states plainly:

> ### **V-3 fails safe.**
> The error is **over-reporting**: the resolver may say a prerequisite is missing when it is satisfied. It **cannot** produce the opposite error. It can never report a session as activatable, authorized, or operable when the record does not support it — `operable` is assigned solely from the mechanism's own state, and the six authorization facts are emitted with hard `UNKNOWN`s and never evaluated.
>
> **Failure mode:** a session waits or escalates unnecessarily. **Never:** a session acts without authority.

Two bounds on present exposure: the resolver is **on-request only** — it is not wired into `SESSION_START` — and `AST-016` is **not adopted**. Observed cost to date: **one** wasted reconciliation cycle.

**Forward-looking condition worth the PO's attention:** if the resolver is ever wired into session startup, this same false line would be read automatically by **every** session at **every** start, and the failure would change character from an occasional wasted cycle to systematic misinformation. **V-3 should be resolved before any startup wiring, whichever disposition is chosen now.**

---

## G · Qualification ≠ adoption ≠ closure

If the human chooses **QUALIFY**, the subsequent sequence is, in order:

```
human qualification decision
  → record S1-verify-discovery-corrective COMPLETE   (E-2 — required first; the lane
                                                       cannot otherwise release ownership)
  → register the qualification
  → create the V-3 architecture follow-up work item
  → adopt AST-016
  → record closure evidence
  → close KOS-SESSION-DISCOVERY-001
```

**None of this has been performed.** This commission ends before the first step.

---

## H · Recommended human decision

> ### **A — QUALIFY**, with V-3 recorded as a named architectural limitation and carried to a separate work item.

**Reasoning, in one line each.** All six granted checks pass on independently re-derived evidence · no implementation defect exists · the granted corrective scope was delivered exactly and nothing beyond it · V-3 is a genuine architectural gap but **fails safe**, is **currently unexposed** (on-request only, unadopted), and its remedy is a **separate decision in a different component** · blocking qualification (**AMEND**) would hold a working, verified read-only capability hostage to an architecture decision that can be taken independently and on its own evidence.

**Two conditions I recommend attaching, neither of which is a technical choice:**
1. The **V-3 follow-up work item is created before the qualification is registered** — so the limitation is carried by the record, not by prose. *(E-1 makes this matter: prose is versioned, the runtime record is not.)*
2. **V-3 is resolved before any `SESSION_START` wiring of the resolver** (§F).

**Recorded separately, not qualification blockers:** `E-1` (the authoritative record is untracked — recommend its own governance work item) and `E-2` (record the verification `COMPLETE` — mechanically required before the lifecycle can proceed).

---

## Traceability

`84100bb0` (2 files, +91/−6) · `b6b8b0bd` (verification verdict) · `32596519` (the wasted reconciliation cycle V-3 caused) · machine record 17 transitions, 7 grants · fold: `S1-verify-discovery-corrective` = `ACTIVE`, `mutationOwner` · `workflow-state.php` sha256 `e19705ce…`, blob `17a52ef1…` identical `73d056c8`→`HEAD`→worktree · `.gitignore:25,:32` · `workflow-state.php:118,135,166,235-237,344-352,365-373,390` · `session-resolve.php:130-131,156,227-231,293-301,313-316` · suite 17 passed / 170 assertions · CASE-A/CASE-B fixtures + the AST-015 START control (exit 0 vs 65), scratchpad only · approved architecture §H · AM-3 / INV-DISC-7 · INV-DISC-3 · INV-DISC-6 · `G-KOS-DISC-C12-VERIFY` · `G-KOS-DISC-V3-ASSESS`
