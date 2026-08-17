# BC-7 Governed Session Orchestration — Domain Model Proposal

## `KOS-ARCH-BASELINE-003` · Tactical DDD discovery

**Work item:** `KOS-ARCH-BASELINE-003` · **Workflow:** `architecture-domain-model` · **Grant:** `G-KOS-ARCHBASE3-DOMAIN` (AUTHORIZED)
**Session:** `S4-architecture-bc7-domain-model` — **ACTIVE, mutation owner** (START recorded at seq 3, human act 2026-08-17) · **Date:** 2026-08-17

> ## PROPOSAL — returns for PO/ARB decision. It does not self-adopt.
> **Architecture proposes; the PO/ARB decides.** Nothing here amends an accepted model. The accepted inputs — Phase A v1.1 (`40026b12`), the v2 strategic set (`f278dc54`, acceptance `a265e1b7`), ADR-AIP-03 — are **inputs, never overwritten**. No implementation, no code, no component movement, no `workflow_engine` change; the workflow's declared role set excludes `implementation`, so that exclusion is mechanically enforced.
>
> **Attribution disclosure (`INV-ATTR-2`, mandatory):** performed by a **fresh Architecture terminal** (claude-code session, model **Claude Opus 5**) with no prior estate history on this work item. Separation is **Declared, not attestable**. **Prior contact this session, disclosed:** this terminal ran the five commissioned prerequisite checks, **wrote its own seq-3 START into the work-item record** (recording the human act — the practice disclosed and accepted on `KOS-ARCH-BASELINE-002` seq 3), and **re-measured the record estate and `AST-015` at source**. Those measurements are marked `Observed (measured 2026-08-17)`. **`R-34`/P-2 forward constraint: this producer must not independently verify this model.**
>
> **Discovery guard.** The commission's own instruction — *"Do not assume WorkItem is the aggregate root"* — was executed as a real test. §4 evaluates four candidates against identity · lifecycle · invariants · consistency boundary · ownership. §4.6 records where the test **corrected or sharpened** an inherited hypothesis.

---

# 1 · Authority verification (performed before any work)

| # | Prerequisite | Result |
|---|---|---|
| 1 | Work item exists | ✅ `.claude/runtime/workflow/KOS-ARCH-BASELINE-003.json`, schema 1, workflow `architecture-domain-model` |
| 2 | Grant `G-KOS-ARCHBASE3-DOMAIN` | ✅ **AUTHORIZED**, authority *PO/ARB (delivered commission, registered verbatim)*, `humanActRef` present |
| 3 | Human START | ✅ **"START: KOS-ARCH-BASELINE-003."** — PO/ARB, 2026-08-17; recorded at **seq 3** through `AST-015` (`append`, exit 0). Fold now returns `state: ACTIVE`, `mutationOwner: S4-architecture-bc7-domain-model`. Before the act the fold returned `CREATED` / `mutationOwner: null` — the G-3 conjunction (recorded HANDOFF ∧ recorded human START) is satisfied **on the record**, not merely in prose |
| 4 | `KOS-ARCH-BASELINE-002` accepted | ✅ acceptance registration `a265e1b7`; deliverables `f278dc54`; the seven-context model, Context Map v2 and Capability Map v2 are authoritative |
| 5 | ADR-AIP-03 accepted | ✅ **ACCEPTED — Option A**, both consequences taken: `CAP-14` assigned to BC-7 · role-model ownership deferred to ADR-AIP-04 |

# 2 · Context

ADR-AIP-03 recognized BC-7 as a bounded context; `KOS-ARCH-BASELINE-002` placed it in the strategic map with its relationships, its owned/preserved invariant split, and its capability (`CAP-14`). **All of that is strategic.** This work is the phase change: turning an accepted boundary into a **coherent tactical model** — aggregates, entities, value objects, events, policies, invariants, language.

**The subject is a system that already runs.** BC-7 is not a green field: 15 work-item records and 117 recorded transitions existed before this session began. Tactical discovery here is therefore **reconstruction under a modelling lens**, not invention — and every model element below is anchored to a measured fact or explicitly marked as `Proposed`.

## 2.1 Evidence base measured this session

Measured 2026-08-17 **before** this session's own START (which makes the transition total 118):

| Measurement | Value | Method |
|---|---|---|
| Work-item records | **15**, one JSON file each | `ls .claude/runtime/workflow/` |
| Transitions, total | **117** | parsed census over all records |
| Transition types in use | **HANDOFF 34 · REGISTER 33 · START 32 · COMPLETE 16 · STOP 1 · CANCEL 1** | type census |
| Vocabulary declared but **never exercised** | **CONTINUATION 0 · FAIL 0** (`CLOSE` refused by the mechanism — baseline) | census vs. declared vocabulary |
| `humanAct` carried by | **START 32/32** · **COMPLETE 3/16** | field census by type |
| `note` carried by | **COMPLETE 16/16**, no other type | field census by type |
| `recordedBy` values | **governance 83 · human 33 · architecture 1** | value census |
| Grants | **41, every one `AUTHORIZED`** — no other status observed | value census |
| **`mutationOwner` stored in records** | **0 occurrences** — never persisted | grep across all 15 records |
| `mutationOwner` in the mechanism | **4 refs** in `workflow-state.php`, **5** in `session-resolve.php` | source grep |

**The single most consequential measurement is the last pair: mutation ownership exists only as a computation.** It is written nowhere and derived everywhere. This session then observed it appear *live*: the seq-3 START was appended, and the very next fold produced `mutationOwner: S4-architecture-bc7-domain-model` from a record that contains no such field.

---

# 3 · Ubiquitous language

Each term is classified **`Observed`** (measured in records or source), **`Inferred`** (a reading of measured facts), or **`Proposed`** (a modelling term this proposal introduces, and which the PO/ARB may accept, rename, or reject).

| Term | Definition in BC-7 | Class |
|---|---|---|
| **Work Item** | A unit of governed engineering work, identified by a stable id (`KOS-ARCH-BASELINE-003`), whose entire lifecycle and authority record live in exactly one record. **The aggregate root** (§5). | **`Observed`** as a record identity + field; **`Inferred`** as the root |
| **Session Assignment** | A named lane of work *within* a Work Item, created by REGISTER, carrying an immutable `role`, an optional `predecessor`, and a declared `executionContext`. Its state is folded, never stored. | **`Observed`** (33 REGISTERs; role immutability enforced in source, `R8`) |
| **Session** | Used in the record as the *name* of a Session Assignment (`"session"` field). **Not a separate concept from Session Assignment in the model** — §9 records the naming consequence. | **`Observed`** (field); the identification is **`Inferred`** |
| **Role** | A value drawn from the Work Item's declared role set, fixed at creation (`init --roles`), immutable per assignment. **BC-7 stores role *values*; the role *model* is ADR-AIP-04's question and is untouched here.** | **`Observed`** (values, role-set-at-init, immutability); the *semantics* **deferred** |
| **Transition** | An append-only recorded fact that governed movement occurred: `{type, seq, recordedBy, …type-specific fields}`. The ordered log of transitions **is** the Work Item's history. | **`Observed`** (117 measured) |
| **Grant** | The *record* of an authorization: `{grantId, status, authority, scope, humanActRef, registeredBy}`. BC-7 owns the record; it does **not** own the authority the record refers to. | **`Observed`** (41 measured); the ownership split is the accepted v2 finding |
| **Handoff** | The transition that moves mutation ownership from one assignment to another (`from`, `to`), requiring a token. The token's *content* is a reference BC-7 never interprets. | **`Observed`** (34 measured, token required in source) |
| **START** | The transition that activates an assignment, requiring a recorded predecessor HANDOFF **and** a non-empty human act. Activation is the conjunction, never either half. | **`Observed`** (32 measured; conjunction enforced in source) |
| **COMPLETE** | The transition recording that an assignment's work ended. Validated only as to *recorder class*; carries a `note` in every observed case and a human act in only 3 of 16. | **`Observed`** |
| **STOP** | The transition placing an assignment/work item in a **sticky** stopped condition, exitable only by an explicit CONTINUATION. | **`Observed`** (1 measured); stickiness **`Observed`** in source, its exit path **unexercised** |
| **Human Act** | A performative act of the Human PO/ARB that exists **outside the software** as a committed artifact. BC-7 holds only its **registration** — the requirement that one be recorded, and a reference/quotation of it. | **`Observed`** (32 `humanAct` texts, `humanActRef` on every grant); the ownership exclusion is the accepted v2 correction |
| **Mutation Ownership** | The property that **exactly one** assignment may mutate the shared execution context at a time. **A derived projection of the transition log — never a stored attribute.** | **`Observed`** (0 stored / 9 mechanism refs; observed derived live this session) |
| **Fold** | The deterministic computation that derives current state (assignment states, mutation owner, work-item state) from the append-only log. **The fold is the only source of machine-truth.** | **`Observed`** (`fold` command; I-10) |
| **Recorded Occurrence** | *(modelling term)* What BC-7 holds about any act: that it happened, in what order, and with what declared attribution — never whether it was right, authorized, or true. | **`Proposed`** |
| **Declared Attribution** | `recordedBy` and `executionContext` are **claims recorded**, not identities authenticated. BC-7 records declarations and must never be read as attesting them. | **`Observed`** (`INV-ATTR-2`; no code path reads process identity, I-7) |

---

# 4 · Aggregate analysis — four candidates tested

Each candidate is tested on five criteria. The decisive DDD test is the third-and-fourth pair: **an invariant that spans several instances of a candidate proves the candidate is not the root.**

## 4.1 Candidate A — **Work Item Aggregate**

| Criterion | Finding | Class |
|---|---|---|
| **Identity** | `workItem` string, unique, equal to the record filename; validated `[A-Za-z0-9._-]` in source | `Observed` |
| **Lifecycle** | `workItemState ∈ {OPEN, STOPPED}`; folded, not stored | `Observed` |
| **Invariants held** | **I-1** (single mutation owner) · **I-2** (G-3 conjunction) · **I-3** (sticky STOPPED) · **I-4** (transitions ≠ grants) — *every one of them is scoped to a single work item and needs no data from outside it* | `Observed` facts, `Inferred` scoping |
| **Consistency boundary** | Exactly one JSON record; every write and every fold operates on that one file — the transactional unit in practice | `Observed` |
| **Ownership** | The record is simultaneously the unit of write, the unit of fold, and the unit of refusal | `Observed` |

**Result: passes every test.** Critically, **all four invariants are internal to it** — nothing must be read from another work item to enforce them. Per-file isolation realizes cross-work-item independence.

## 4.2 Candidate B — **Assignment Aggregate** *(rejected)*

| Criterion | Finding |
|---|---|
| Identity | Session name — but it is a **free string scoped to the record**, not a globally unique identity; nothing enforces uniqueness across work items (`Observed`; see OQ-4) |
| Lifecycle | Real: CREATED → ACTIVE → HANDED_OFF / STOPPED / COMPLETED (`Observed` via fold) |
| **Invariants — the decisive failure** | **I-1 is an invariant over the *set* of assignments, not over one.** "Exactly one mutation owner" cannot be stated, let alone enforced, from inside a single assignment |
| **Consistency boundary — the second failure** | **HANDOFF is atomic between two assignments** (`from` → `to`). With Assignment as root, every handoff becomes a two-aggregate transaction, and the mechanism's observed refusal of *non-owner* handoffs would require reading another aggregate's state mid-decision |
| Ownership | The record, not the assignment, is what the mechanism reads and writes |

**Rejected.** Two invariants span assignments; a root cannot be smaller than the invariants it must hold. **Assignment is an entity inside the Work Item.**

## 4.3 Candidate C — **Workflow Aggregate** *(rejected)*

| Criterion | Finding |
|---|---|
| Identity | `workflow` is a **classifier** (`architecture-decision`, `architecture-domain-model`, …), shared across many work items, fixed at `init` |
| Lifecycle | **None.** No transition is ever attached to a workflow; no workflow record exists |
| Invariants | None of its own. It *constrains* a work item (it declares the role set at creation) but holds no state to protect |
| Consistency boundary | None — it is a field, not a record |

**Rejected — and reclassified.** Workflow is a **Value Object on the root** (with the declared role set), not an aggregate. It is a *type*, and types are not aggregates.

## 4.4 Candidate D — **Human Act Aggregate** *(rejected — and the rejection is load-bearing)*

| Criterion | Finding |
|---|---|
| Identity | **None inside BC-7.** `humanAct` is free text embedded *within* a transition (32/32 STARTs); it has no id, no addressable existence, no query surface |
| Lifecycle | None inside BC-7. The act's lifecycle — proposed, delivered, committed, superseded — belongs to the knowledge estate and Governance |
| Invariants | BC-7 enforces exactly one thing about it: **that a non-empty one is present at START**. It never validates its content, its author, or its entitlement (`Observed`, source) |
| Consistency boundary | It is *referenced* (`humanActRef`) into artifacts committed elsewhere |

**Rejected, on two independent grounds.** ① **Measured:** it has neither identity nor lifecycle here. ② **Boundary:** the accepted model already established that the act exists outside the software and BC-7 owns only its *registration*. Promoting it to an aggregate root would **duplicate a concept another context owns** — the precise failure `ES-005.4` forbids, and it would drag authority semantics into a context that provably cannot evaluate authority. **Human Act enters the model as a Value Object (`HumanActReference`), never as a root.**

## 4.5 The sharpest tactical question: one aggregate, or two?

The Work Item record contains **two collections that never merge** — `transitions[]` (lifecycle) and `grants[]` (authority). I-4 makes their separation a rule. So: one aggregate with two parts, or **two aggregates sharing an identity**?

**The test — does any *positive* invariant span them?** Measured answer: **no.**

* START validates a recorded handoff and a non-empty human act; **it does not consult grants at all** (`Observed`, source).
* `authorized` compares a scope string against AUTHORIZED grants and **never consults the session** (`Observed`, source).

So the mechanism never requires the two to be consistent with each other. **The consistency boundary is wider than any positive invariant demands** — an honest finding, and one that argues *for* splitting.

**Why the proposal nonetheless keeps one root:**

1. **I-4 is itself a spanning invariant — a negative one.** *"Session Registry ≠ Authority State, never merged"* is a statement **about the relationship between the two**, and the accepted model assigns its ownership to BC-7. An invariant that says *these must remain disjoint* still needs a holder that can see both.
2. **The write boundary is one file** (`Observed`): appending a transition and registering a grant are writes to the same record, with disjoint writer rules enforced within it.
3. Splitting would make I-4 true by construction but would **relocate** the problem — it would require a new stated rule binding two aggregates to one identity, replacing an owned invariant with an unowned coupling.

**Proposed: one root, two invariant-disjoint parts, with I-4 as the root's own separation invariant.** The alternative is recorded in §6 as a genuine rejected option with its trade-off, because the evidence for it is real and the PO/ARB may weigh it differently.

## 4.6 Where the test corrected or sharpened an inherited hypothesis

| Inherited position | This stage's result |
|---|---|
| Work Item is the aggregate root (baseline §7, `Inferred`/medium) | **CONFIRMED and strengthened** — now resting on the invariant-scope test and the handoff-atomicity test, not on persistence shape alone. Confidence raised to `Inferred`/high |
| Mutation ownership is a property of the record | **SHARPENED to a correction of shape: it is a *fold projection*, never stored** (0 occurrences measured; observed derived live). Any model that gives the root a stored `mutationOwner` attribute would be wrong |
| The record's two parts are "two records in one file" | **SHARPENED:** no positive invariant spans them; what binds them is a negative invariant plus a shared write boundary (§4.5) |
| Transition vocabulary as declared | **NARROWED by evidence:** CONTINUATION and FAIL are declared but **never exercised** (0/117). Modelled as vocabulary, flagged as OQ-5 — a domain concept with no observed instance is a claim, not a fact |
| Human Act → owned (original commission candidate) | **Already corrected at v2; confirmed tactically** — no identity, no lifecycle here (§4.4) |

---

# 5 · The proposed model

## 5.1 Aggregate root — `WorkItem`

```
WorkItem                                        [aggregate root]
  identity : WorkItemId                         (Observed)
  workflow : WorkflowType  ─ VO, fixed at creation
  declaredRoles : Set<Role> ─ VO, fixed at creation
  │
  ├── LIFECYCLE PART ──────────────────────────  (never merged with the part below — I-4)
  │     transitions : ordered append-only Transition[]      (Observed: 117)
  │     assignments : SessionAssignment[]                   (entities, created by REGISTER)
  │
  └── AUTHORITY PART ──────────────────────────
        grants : Grant[]                                    (Observed: 41, all AUTHORIZED)

  DERIVED BY FOLD — never stored, never settable:
        workItemState   : OPEN | STOPPED
        assignmentState : per assignment
        mutationOwner   : SessionAssignment | none          (measured: 0 stored)
```

## 5.2 Entities

| Entity | Identity | Notes | Class |
|---|---|---|---|
| **`SessionAssignment`** | session name, **scoped to the Work Item** | created by REGISTER; `role` immutable (`R8`); carries `predecessor` and a **declared** `executionContext`; its state is folded | `Observed`; scoping is `Inferred` (OQ-4) |
| **`Grant`** | `grantId` | carries `status`, `authority`, `scope`, `humanActRef`, `registeredBy`. **Every observed instance is `AUTHORIZED` and none was ever observed changing** — so whether it is an entity with a lifecycle or an immutable record is genuinely open (OQ-6) | `Observed`; lifecycle `Unknown` |

## 5.3 Value objects

| Value object | Why a VO, not an entity | Class |
|---|---|---|
| `WorkItemId` | identity value; charset-constrained in source | `Observed` |
| `WorkflowType`, `Set<Role>` | classifiers fixed at creation; no state of their own (§4.3) | `Observed` |
| `Role` | a value from the declared set; immutable per assignment. **Semantics deferred to ADR-AIP-04** | `Observed` value / deferred meaning |
| `TransitionType` | closed vocabulary; refusal on unknown types | `Observed` |
| `Seq` | ordering value — **sequence, never wall-clock time**; BC-7 holds no clocks | `Observed` |
| `RecordedBy` | **a recorded claim of recorder class, not an authenticated identity** | `Observed` (`INV-ATTR-2`, I-7) |
| `ExecutionContext` | a **declaration** about how a lane must be run; never attested | `Observed` |
| `HumanActReference` | quotation/reference to an act owned outside BC-7 (§4.4) | `Observed` |
| `HandoffToken` + `TokenRef` | a reference *into the knowledge estate*; **BC-7 never dereferences it** | `Observed` |
| `GrantScope` | **an opaque string.** Matched by equality; never parsed, never interpreted (§8.1) | `Observed` |
| `GrantStatus` | `AUTHORIZED` is the only observed member (OQ-6) | `Observed` |
| `CompletionNote` | present on 16/16 COMPLETEs; narrative, never authoritative (I-10) | `Observed` |

## 5.4 Derived projections — *not model state*

`WorkItemState` · `AssignmentState` · **`MutationOwnership`**. These are **fold outputs**. The modelling rule this proposal states plainly: **no derived projection may be stored, set, or accepted as input.** Storing one would create a second truth able to drift from the log — the exact defect I-10 exists to prevent, and the shape of the product-side rule that a derived condition must be computed from recorded facts.

---

# 6 · Rejected alternatives (recorded so they are not silently reinvented)

| # | Alternative | Why rejected | Kept visible because |
|---|---|---|---|
| RA-1 | **Assignment as aggregate root** | I-1 spans assignments; HANDOFF is atomic across two (§4.2) | it is the intuitive choice for anyone reading the fold output first |
| RA-2 | **Workflow as aggregate root** | a classifier with no state or lifecycle (§4.3) | the word *workflow* invites it |
| RA-3 | **Human Act as aggregate root** | no identity/lifecycle in BC-7; would duplicate an externally owned concept (`ES-005.4`) and import authority semantics (§4.4) | it is the most tempting error, because the act is the most *important* thing in the system — importance is not ownership |
| RA-4 | **Split into two aggregates** (`WorkItemLifecycle` + `AuthorityRegister`, shared id) | no positive invariant spans them, so the split is defensible; rejected because I-4 is a **negative spanning invariant** needing a holder, and the write boundary is one record (§4.5) | **the strongest rejected option** — the PO/ARB may legitimately prefer it |
| RA-5 | **Stored `mutationOwner` on the root** | contradicts measurement (0 stored) and would create a drifting second truth (§5.4) | every implementation instinct will reach for it |
| RA-6 | **Modelling CONTINUATION/FAIL as established domain concepts** | declared but never exercised (0/117) — asserting them would state more than the evidence supports | they are in the vocabulary; OQ-5 keeps the question honest |

---

# 7 · Invariants

Ownership follows the accepted v2 split (**owns** = the invariant's *reason* lives in BC-7; **preserves** = enforced on behalf of an owner elsewhere). This stage adds the **tactical** invariants that the model itself must hold.

| # | Invariant | Owns / Preserves | Class |
|---|---|---|---|
| I-1 | Exactly one mutation owner per Work Item | **Owns** | `Observed` |
| I-2 | Activation = recorded HANDOFF **∧** recorded human START | **Owns the conjunction mechanics; preserves its authority premise** | `Observed` (verified live this session at seq 3) |
| I-3 | STOPPED is sticky — exit only by explicit CONTINUATION | **Owns** | `Observed` in source; exit path **unexercised** (OQ-5) |
| I-4 | Lifecycle record ≠ Authority record, never merged | **Owns** — and it is the invariant that holds the aggregate together (§4.5) | `Observed` |
| I-6 | Role immutable per assignment; change = new assignment | **Owns the enforcement**; role *semantics* deferred | `Observed` |
| I-10 | The record outranks prose | **Owns** | `Observed` |
| I-5 | Only Governance writes authority | **Preserves** — mechanically on 2 of 5 transition types; convention on the rest (C-4 standing) | `Observed`; **and note the measured `architecture` recorder (§2.1) — one instance of a non-governance, non-human writer class in the wild** |
| I-7 | Process identity never authorizes | **Preserves** — realized by absence | `Observed` |
| I-8 | Engineering never accepts its own work | **Preserves only** | `Observed` |
| **T-1** | **No derived projection is ever stored or accepted as input** (state, ownership, work-item state are fold outputs) | **Owns** | **`Proposed`** — the tactical form of I-10, grounded in the 0-stored measurement |
| **T-2** | **A reference value is never dereferenced by BC-7** (`tokenRef`, `humanActRef`, `GrantScope` content) | **Owns** | **`Proposed`** — the tactical form of the two accepted boundary sentences (§8) |
| **T-3** | **The transition log is append-only in meaning**: no transition is edited, reordered, or removed; correction is a further transition | **Owns** | **`Proposed`** (`Observed` in practice: `seq` monotone, append-only writer) |
| **T-4** | **Attribution is recorded as a claim, never as an attestation** (`recordedBy`, `executionContext`) | **Owns the recording**; the attestation problem belongs to the attribution model (§8.3) | **`Proposed`** from `Observed` `INV-ATTR-2` |

---

# 8 · Domain events

**A necessary honesty first: in BC-7 these are *recorded facts*, not published events.** No subscriber mechanism exists — nothing loads the records except `AST-015` and its read-only delegate `AST-016` (`Observed`: the mechanism is the dependency root). "Consumers" below are therefore **readers of the log**, never subscribers. Names are the **transition vocabulary as recorded**; any prettier event name would be a modelling invention, and the canonical naming question stays open (OQ-7).

| Event (recorded type) | Producer | Meaning | Consumers (readers) | Evidence |
|---|---|---|---|---|
| **REGISTER** | Governance role | a Session Assignment comes into existence with a fixed role, a declared execution context, and an optional predecessor | fold; resolver; the assigned lane's startup check | `Observed` ×33 |
| **HANDOFF** | current mutation owner (or Governance at origin, `from: null`) | mutation ownership moves; a token referencing the work product accompanies it | fold (ownership); the receiving lane; Governance | `Observed` ×34 |
| **START** | **human act, recorded** (`recordedBy: human` ×32/32) | the assignment becomes ACTIVE — *only* in conjunction with a recorded predecessor HANDOFF | fold (activation, ownership); every downstream reader of lane truth | `Observed` ×32 (+1 this session) |
| **COMPLETE** | Governance or human (writer class validated here) | the assignment's work ended; always carries a `note` (16/16), rarely a human act (3/16) | fold; Governance closure bookkeeping | `Observed` ×16 |
| **STOP** | Governance/human | a **sticky** stopped condition begins | fold; any lane attempting to proceed | `Observed` ×1 |
| **CANCEL** | Governance/human | the work item is cancelled | fold | `Observed` ×1 |
| **CONTINUATION** | *(declared)* | the only exit from sticky STOPPED | fold | **`Declared`, 0 observed** — OQ-5 |
| **FAIL** | *(declared)* | terminal failure of an assignment | fold | **`Declared`, 0 observed** — OQ-5 |

**Two structural observations.** ① **The log is the event store and the aggregate's state at once** — there is no separate projection store, by design (T-1). ② **The asymmetry 33 REGISTER / 32 START / 16 COMPLETE is a measured fact about the estate**, not a defect claim: roughly half of all registered lanes carry no recorded completion. Whether that is a bookkeeping gap or an accurate picture of how the work actually ran is **not this proposal's to judge** — it is recorded as OQ-8 for whoever holds closure discipline.

---

# 9 · Policies — domain and governance, never merged

The commission's separation, applied strictly. **A domain policy answers *"what must always be true of the record?"*; a governance policy answers *"who may decide?"*** BC-7 may hold the first and may only *preserve* the second.

## 9.1 Domain policies — BC-7's own

| # | Policy | Evidence |
|---|---|---|
| DP-1 | A transition is accepted only if its type is in the vocabulary and its required fields are present | `Observed` (refusals, exit 65) |
| DP-2 | START is accepted only with a recorded predecessor HANDOFF **and** a non-empty human act | `Observed` (verified live, seq 3) |
| DP-3 | Only the current mutation owner may hand off | `Observed` |
| DP-4 | A handoff without a token is refused | `Observed` |
| DP-5 | A stopped assignment does not silently continue | `Observed` (sticky in source) |
| DP-6 | State is folded from the log; derived values are never accepted as input | `Observed` + **`Proposed`** as T-1 |
| DP-7 | The record outranks any prose account of it | `Observed` (resolver refuses prose) |

**Every one of these is a *record-consistency* rule.** That is the whole of BC-7's decision-making — and it is the tactical proof of the boundary: a context whose only decisions are about record integrity is not secretly holding authority.

## 9.2 Governance policies — preserved, never owned

| # | Policy | BC-7's contribution |
|---|---|---|
| GP-1 | Authority originates in a human act (`R-34`) | requires a recorded act; refuses a grant without `humanActRef` |
| GP-2 | Only Governance writes authority (I-5) | enforces the writer class on 2 of 5 transition types (C-4 standing, `R-37`-gated) |
| GP-3 | Engineering never accepts its own work (I-8) | makes role separation **recordable and checkable** — never decides it |
| GP-4 | Process identity confers nothing (I-7) | realized by **absence**: no gate reads process identity |

**The line, stated once:** BC-7 can refuse a *malformed* act. It can never refuse an *unauthorized* one — it has no faculty for that question, and this proposal adds none.

---

# 10 · Boundary analysis (tactical resolution)

The strategic seams are accepted; this section states what they mean **as modelling rules** — the form in which a boundary can actually be violated by a model.

## 10.1 BC-7 ↔ Governance — *authority versus recorded occurrence*

**Accepted anchor:** *Governance decides whether an act was authorized; BC-7 answers whether it was recorded.*

| Tactical instrument | Rule the model must obey | Evidence |
|---|---|---|
| **`GrantScope` is an opaque string** | It is compared by equality and **never parsed**. Giving it structure — fields, a grammar, a scope algebra — would import governance semantics into BC-7 and let the model start *interpreting* authority | `Observed`: `authorized` does scope-string equality and never consults the session |
| **`RecordedBy` is a claim** | It must never be modelled as an authenticated actor identity | `Observed` (`INV-ATTR-2`, I-7) |
| **No entitlement predicate exists** | The model must expose no operation of the form *"may X do Y?"* | `Observed`: START validates form, never entitlement |
| **Grants and transitions do not constrain each other** | The model must not add a rule making START consult grants — that would be inventing governance enforcement the accepted boundary excludes | `Observed` (§4.5) |

**The violation to watch for:** any future convenience that lets BC-7 answer *"was this allowed?"*. It can only ever answer *"is this recorded, and in what order?"*

## 10.2 BC-7 ↔ Knowledge Engineering — *movement versus meaning*

**Accepted anchor:** *Knowledge Engineering holds the meaning; BC-7 holds the movement. Each cites the other by identifier and neither interprets the other's content.*

| Tactical instrument | Rule the model must obey | Evidence |
|---|---|---|
| **`TokenRef` / `humanActRef` are reference values** | **T-2: never dereferenced.** BC-7 must not resolve, parse, validate, or fetch what a reference points to | `Observed`: the mechanism reads only its own JSON; it is the dependency root |
| **`CompletionNote` and `humanAct` text are narrative** | Recorded verbatim, never interpreted, never authoritative over the fold | `Observed` (I-10) |
| **Two truth disciplines stay apart** | Knowledge truth is **adjudicated** (a human accepts a meaning); BC-7 truth is **folded** (a deterministic computation). The model must never subject a fold to adjudication, nor a meaning to a fold | accepted v2 finding (T-h/T-i) |

**Recorded tension, unresolved and boundary-confirming:** BC-7's own store is gitignored and has no provenance (C-3) — it obeys neither the estate's versioning nor its adjudication. **That the two stores obey different rules is evidence the boundary is real**; whether BC-7's store *should* adopt provenance is **ADR-C6's `R-37` question — named here, untouched, no mechanism proposed.**

## 10.3 Relation to the accepted attribution model — *relate, do not duplicate* (`ES-005.4`)

The attribution Stage-1 model owns the **claim / evidence / assessment** chain. BC-7's `recordedBy` and `executionContext` are, in that model's vocabulary, **claims** — and `INV-ATTR-2` (separation is *declared, not attestable*) is exactly the reason they cannot be more.

**The seam, stated so nothing is copied:** **BC-7 records the claim; the attribution model adjudicates it.** BC-7 must not grow its own assessment vocabulary, and the attribution model must not acquire a second lifecycle log. Neither concept is redefined here.

---

# 11 · Open questions — carried, not decided

**Carried from the accepted models, untouched by this stage:**

| # | Question |
|---|---|
| OQ-1 | **Role-model ownership — ADR-AIP-04, expressly deferred.** §3/§5.3 record only the *measured shape* (role values from a set fixed at creation, immutable per assignment) as evidence for that future discovery. **Not advanced one inch.** |
| OQ-2 | **The BC-4 relationship** (rules-only context vs policy of the role model) — the BC-7↔BC-4 line stays **dashed** (ADR-C7) |
| OQ-3 | **Future CAP refinement** — `CAP-14` covers BC-7's capability as one row; whether the capability model eventually needs finer grain is not raised here |

**Discovered by this stage (new, and each a genuine question rather than a finding):**

| # | Question | Why it matters |
|---|---|---|
| OQ-4 | **Is a session name globally unique, or unique only within a Work Item?** Nothing enforces either | it decides whether `SessionAssignment`'s identity is local or global — a real modelling consequence |
| OQ-5 | **Are CONTINUATION and FAIL domain concepts, or dead vocabulary?** Declared, never exercised (0/117); I-3's sticky-STOPPED exit path has therefore never run | a concept with no instance cannot be modelled as established without overstating the evidence |
| OQ-6 | **Is `Grant` an entity with a lifecycle, or an immutable record?** All 41 are `AUTHORIZED`; no status change was ever observed. Is revocation a domain concept? | entity-vs-VO for `Grant`, and whether authority can be withdrawn at all |
| OQ-7 | **What is the canonical event/transition vocabulary?** This proposal deliberately used the recorded type names rather than inventing prettier ones | naming is a governance act; an illustration must not become a rule |
| OQ-8 | **Is the 33 REGISTER / 32 START / 16 COMPLETE asymmetry a bookkeeping gap or an accurate record?** | closure discipline — explicitly *not* judged here |
| OQ-9 | **One aggregate or two (RA-4)?** The proposal recommends one, on a negative invariant; the evidence for two is real | the single largest structural choice in the model |
| OQ-10 | **Recorder classes:** one `recordedBy: "architecture"` exists in the estate. Is the recorder class an open or closed set? | bears on I-5/C-4 and on whether `RecordedBy` is a closed vocabulary VO |

**Name-fitness re-test — the answer v2 asked this stage for.** Architecture Landscape v2 §3.6 recorded that *"Governed **Session** Orchestration"* may name the context by one of its entities, and asked the domain-model stage to re-test. **Re-tested and confirmed at tactical resolution:** the aggregate root is the **Work Item**; `SessionAssignment` is an entity within it; and grants and human-act registration are not session concepts at all. **Recommendation unchanged from v2: keep the recognized name** — it is the name ADR-AIP-03 signed, renaming on analysis alone is churn, and a rename is in any case a PO/ARB act. Recorded as **OQ-11**, with the observation now carrying tactical evidence rather than a first impression.

---

# 12 · Confidence summary

| Claim family | Class · confidence |
|---|---|
| Estate censuses; `mutationOwner` never stored; humanAct/note distributions; grant statuses; live G-3 verification at seq 3 | **`Observed`** (measured 2026-08-17) · high |
| Work Item as aggregate root; rejection of B and C; the derived-projection rule (T-1) | **`Inferred`** · **high** — each a single-step reading of a measured fact, with an explicit failing test for the alternatives |
| Rejection of D (Human Act); the two tactical boundary rule-sets (§10) | **`Inferred`** · high — resting on the accepted v2 boundary findings plus this stage's measurements |
| One-vs-two aggregates (§4.5, RA-4); `Grant` as entity | **`Inferred`** · **medium** — flagged for deliberate weighing (OQ-9, OQ-6), not asserted |
| T-2/T-3/T-4 as stated invariants | **`Proposed`** — model rules offered for decision, not measured facts |
| Everything touching role semantics, BC-4, CAP grain, OQ-4…OQ-11 | **`Unknown` / deferred** — carried, not filled |

---

# 13 · What this proposal does not do

No implementation · no code · no class, folder, table, schema or technology · no component movement · no `workflow_engine` change · **no ADR-AIP-04 decision and no role-model ownership decision** · no amendment of Phase A v1.1, the v2 set, or ADR-AIP-03 · no self-verification and no self-acceptance · no new ADR (none proved unavoidable — every decision surfaced here belongs either to the PO/ARB's disposition of this proposal or to an ADR candidate already registered).

**Next actor: independent verification** (`R-34`/P-2 — this producer must not verify this model), then the PO/ARB's decision on the proposal.

**Traceability:** commission `2026-08-17-KOS-ARCH-BASELINE-003-commission.md` · grant `G-KOS-ARCHBASE3-DOMAIN` · START seq 3 (human act 2026-08-17) · accepted Phase A v1.1 `40026b12` (acceptance `378f6eaa`) §§3, 5, 7, 8 · accepted v2 set `f278dc54` (acceptance `a265e1b7`) — Landscape v2 §§3.1–3.6, 4, 5; Context Map v2 R-1…R-8; Capability Map v2 (`CAP-14`, Capability Identity Invariant) · ADR-AIP-03 (accepted, both consequences) · Stage-2 report `f4eb4f76` · `AST-015`/`AST-016` source and record estate (measured 2026-08-17) · `INV-ATTR-2` · `ES-005.4` · `R-34` · `R-37`.
