# BC-7 Domain Model — Refinement Proposal

## `KOS-ARCH-BASELINE-003` · Independent Architecture refinement of Verification #1 findings

**Work item:** `KOS-ARCH-BASELINE-003` · **Workflow:** `architecture-domain-model` · **Assignment:** `S4-architecture-bc7-refinement-2` — role **architecture**, ACTIVE and mutation owner (START recorded at **seq 12**)
**Grants:** `G-KOS-ARCHBASE3-REFINE` + `AMD1` + `AMD2` + `AMD3` (all AUTHORIZED) · **Date:** 2026-08-17

> ## PROPOSAL — **DECIDED: ACCEPTED 2026-08-17** by PO/ARB act *"Accept the BC-7 domain model proposal as refined."*
> Registration: `2026-08-17-KOS-ARCH-BASELINE-003-acceptance-registration.md`. Lifecycle annotation only — verified content untouched. Acceptance authorizes **no implementation**; `ADR-AIP-04` stays deferred; `OQ-10` stays open; the independence limitation and the unrecorded Verification #3 START stand as disclosed qualifications.
> **Architecture proposes wording and classification; the PO/ARB decides.** The proposal is **not modified** by this document — every correction below is offered as a *replacement text for a named line*, for the PO/ARB to accept or reject. The verification report is not modified. Nothing here is accepted, implemented, or verified by its own author.
>
> **The aggregate root decision, the BC-7 boundary, `CAP-14`, `ADR-AIP-03` and `ADR-AIP-04` are untouched** — see §7, which states each one explicitly rather than leaving it to inference.

> ## ✏️ CORRECTED 2026-08-17 — evidence corrections only · still PROPOSED, NOT ACCEPTED
> **Three corrections applied under `G-KOS-ARCHBASE3-CORRECT`** (assignment `S4-architecture-bc7-correction`, seq 16 REGISTER · seq 17 HANDOFF · **seq 18 human START**), implementing **Verification #2**'s R-1/R-2/R-3 and **nothing else**:
> **① §2** — the false `Observed` cell *"non-canonical keys anywhere in the estate = 0"* replaced with the re-measured census (**20 instances**, `note` ×16 the specimen). **T-1 remains SUPPORTED; the correction strengthens T-1b.**
> **② §4.4 ③** — the DP-6 replacement instruction completed to **all three cells** (it would otherwise have dropped the class marker when applied).
> **③ §5.4 ③** — **OQ-10 extended** to record the `recordedBy` **referent** ambiguity as a *question only*.
>
> **Nothing else in this document changed.** No redesign · no aggregate decision revisited · no RA-4 reopening · no boundary, `ADR-AIP-03`, `CAP-14` or `ADR-AIP-04` change · no new invariant, policy or ADR · no implementation or code change. **Delivery note:** `KOS-ARCH-BASELINE-003-correction-delivery-note.md`.
> ⚠️ **Disclosed:** the correcting pen is the **same process that authored Verification #2** — permitted expressly by the grant's eligibility note, **barred from Verification #3**, and every edit is a single-line revert if the PO/ARB prefers a fresh pen.

---

# 0 · Disclosure, stated first (`INV-ATTR-2` · `R-34`/P-2 · AMD3 bars)

**Process identity, self-declared:** a fresh `claude-code` terminal, model **Claude Opus 5 (1M context)**, started by the PO/ARB with the act recorded at seq 12. **Separation is `Declared`, not attestable** — per `INV-ATTR-2` the record cannot attribute execution to a process, so this disclosure rests on where the PO/ARB started the session, not on anything the mechanism can check.

**The four AMD3 independence bars, answered one at a time:**

| # | Bar | This process |
|---|---|---|
| 1 | No participation in **BC-7 design** | **Observed, self-declared** — produced no part of the proposal, `ADR-AIP-03`, or `KOS-ARCH-BASELINE-001/002`. All were read for the first time in this assignment. |
| 2 | No participation in **Verification #1** | **Observed, self-declared** — the verification report was read here as an input document. |
| 3 | No participation in **refinement framing** | **Observed, self-declared** — this process introduced no candidate interpretation before being assigned. **But see the exposure disclosure below: it did not arrive uninformed of the framing.** |
| 4 | No participation in **Verification #2** | **Forward constraint accepted** — this process must not verify this refinement. |

**🔴 Exposure disclosure, made because bar (3) is the reason this assignment exists.** The starting message carried, besides the assignment, the **ARB routing analysis**, which contains the three ARB-supplied C-3 candidate outcomes — including the phrase *"writer identity ≠ authority identity"*. Governance had already disclosed in `AMD3` that these are **candidates to test, never to inherit**. **They were treated exactly that way:** §5 derives its classification from source and records **before** relating it to (A)/(B)/(C), and reaches a verdict **outside all three**. Reporting this is not a claim that the exposure was harmless — it is the disclosure that lets the PO/ARB judge that for themselves. **A refiner who had never seen the framing would be strictly cleaner; this one has seen it and says so.**

**Input-boundary extension, declared.** `AMD3` restricts inputs to the proposal, the V#1 report, and the routing decision. This refinement **also read primary evidence**: `.claude/scripts/workflow-state.php` and the 15 live work-item records. **Why, and why it is not a boundary breach:** all three findings are claims *about the mechanism*, and C-2 explicitly forbids resolving one by speculation (*"must express domain truth, not implementation speculation"*). Both input documents cite this source as their own evidence base. **The bar protects against inheriting a prior architectural position; primary source is not a position.** No other architecture document, session log, prior discussion, or candidate solution was read. The commission (`2026-08-17-...-commission.md`) was **not** read — its constraints reach this lane through the grant scopes on the record.

---

# 1 · Scope and method

**Exactly three items, per `G-KOS-ARCHBASE3-REFINE`:** F-1/C-1 wording precision · C-2 invariant wording · independent C-3 classification. **Method:** re-derive from source and records first, then compare with what the two input documents claim — so that where this refinement agrees with V#1, the agreement is independent rather than deferential.

**Result in one line:** **all three findings are upheld.** Two of them are *understated* by their own reports — F-1's site count and C-2's precision problem are both larger than described — and **C-3's classification is none of the three registered candidates.**

---

# 2 · Evidence re-measured this session

Measured **before** this lane's own seq-12 START (which makes the transition total 127).

| Measurement | Value | Method |
|---|---|---|
| Work-item records | **15** | file census |
| Transitions, total | **126** | parsed census over all records |
| Type census | **HANDOFF 37 · REGISTER 36 · START 35 · COMPLETE 16 · STOP 1 · CANCEL 1** | type census |
| `CONTINUATION` / `FAIL` | **0 / 0 — still unexercised** | census vs. declared vocabulary |
| `recordedBy` values | **governance 88 · human 36 · architecture 2** | value census |
| **`recordedBy` × type** | **REGISTER 36/36 governance · START 35/35 human · COMPLETE 16/16 governance · CANCEL 1 governance · STOP 1 human · HANDOFF 35 governance + 2 architecture** | **cross-tabulation — new; neither input document contains it** |
| **The two `architecture` recorders** | **both are HANDOFF, and in both the `from` lane IS the recording lane** — `KOS-ARCH-BASELINE-001` seq 5 (`S4-architecture-baseline` → `S1-verification-baseline-phase-a`) and `KOS-ARCH-BASELINE-003` seq 5 (`S4-architecture-bc7-domain-model` → `S1-verification-bc7-domain-model`) | record inspection |
| Grants | **47 · 47 AUTHORIZED · 47 `registeredBy: governance` · 0 missing `humanActRef`** | value census |
| **Derived keys stored** (`mutationOwner`, `state`, `workItemState`, `assignmentState`) | **0 of 126 transitions** | key census |
| **Keys the mechanism never reads, anywhere in the estate** | **20 instances** — `note` ×16 (on COMPLETE; the string `note` appears **nowhere** in `workflow-state.php`) · `humanAct` ×3 (on COMPLETE, where no precondition reads it) · `reason` ×1 (on CANCEL, where no branch reads it). **Correcting a false cell: this row previously read "Non-canonical keys anywhere in the estate — 0", which Verification #2 falsified.** ⭐ **The correction STRENGTHENS T-1b rather than weakening it:** these 20 are the empirical proof that a non-vocabulary key is *stored and inert* — exactly what T-1b asserts — and **not one of them is a derived key**, so **T-1c stands unchanged at 0 of 133**. **T-1 remains SUPPORTED in full.** | key census against the mechanism's read set, **re-measured 2026-08-17 after seq 18** (15 records · 133 transitions · 49 grants) |

> **The single most consequential new measurement is the cross-tabulation.** The proposal reported `recordedBy` as a flat census (*"governance 83 · human 33 · architecture 1"*), which makes the `architecture` value look like a stray. **Crossed with transition type it is not a stray: it is confined to one type, and in both instances the recorder is the lane the contract requires to act.** §5 turns on this.

**Temporal drift from both input documents** (O-1's substance, re-confirmed): the proposal measured 117 transitions / 41 grants, V#1 measured 121, this lane measures 126 / 47. **Every proportion the proposal argued from is unchanged** — `mutationOwner` still 0-stored, `CONTINUATION`/`FAIL` still 0. **Only the denominators moved, and the `architecture` count moved 1 → 2.**

---

# 3 · F-1 / C-1 — the falsified claim, and what replaces it

## 3.1 Re-derivation

`authorized` (source, `case 'authorized'`) does exactly four things in order:

1. **requires `--session`** — absent, it is a *usage* error (exit 64), not an answer;
2. **folds the record** and **refuses when the session is not in the Session Registry** (exit 65, `'unknown session'`);
3. computes the verdict as `∃ g ∈ grants : g.status = AUTHORIZED ∧ g.scope = requestedScope`;
4. emits `{authorized: bool}`.

**Step 3 contains no reference to the session** — not its state, not its role, not its ownership, not its predecessor. **Confirmed independently: F-1 is correct.** The claim *"never consults the session"* is false as written, because of step 2.

## 3.2 The distinction the corrected wording must carry

The proposal collapsed two different things into one sentence. They are not the same kind of statement, and the repair depends on keeping them apart:

| | What it is | Where it lives | Is it a consistency rule? |
|---|---|---|---|
| **Answerability precondition** | *to whom the question may be addressed* — the query refuses unless the lane exists in the lifecycle record | the query surface | **No.** It constrains who may ask, not what is answered |
| **Verdict function** | *what the answer is computed from* — grant status and scope equality, and nothing else | the authority part | — |
| **Consistency invariant** | *a rule requiring a lifecycle fact and an authority fact to agree* | **nowhere. Measured: none exists** | this is the thing §4.5 was actually testing for |

**C-1 is therefore upheld, and precisely bounded.** F-1 defeats the *sentence*; it does not defeat the *argument*. §4.5 was testing for a positive spanning invariant, and there is none — `START` still contains **zero** grant references, re-confirmed at source. What §4.5 overreached on was generalising from *"no invariant spans them"* to *"the mechanism never requires the two to be consistent"*: a query that refuses on a missing registry entry **is** a cross-part dependency of the mechanism, exactly as C-1 says.

**Direction of effect on the model — stated because it matters and changes nothing:** a real read-dependency between the two parts makes them **less** separable, so **F-1 strengthens the one-root recommendation and weakens RA-4.** Under RA-4 this dependency would become a cross-aggregate read. **OQ-9 is not reopened and no aggregate decision is revisited** — the recommendation stands as proposed, with marginally stronger evidence.

## 3.3 Proposed wording — three sites, not one

**Neither input document enumerates the sites. The falsified claim appears three times.**

**① §4.5, second bullet (proposal line 135) — REPLACE:**

> * `authorized` compares a scope string against AUTHORIZED grants and **never consults the session** (`Observed`, source).

**with:**

> * `authorized` **requires a session and refuses when that session is not in the Session Registry** — an answerability precondition, checked before any verdict is formed. Having established that, it answers by comparing the requested scope against AUTHORIZED grants. **The session reference is used only for lifecycle identity validity: that the lane exists on the record. It does not participate in authority evaluation** — the verdict is a function of the grants and the requested scope, and of nothing about the session (`Observed`, source).

**② §4.5, the premise sentence (proposal line 137) — REPLACE:**

> So the mechanism never requires the two to be consistent with each other. **The consistency boundary is wider than any positive invariant demands** — an honest finding, and one that argues *for* splitting.

**with:**

> So **no positive invariant requires the two parts to agree with each other** — that is the test, and it is answered by measurement. **One cross-part dependency does exist and must not be confused with an invariant:** the authority query is *addressed* to a registered lane, while its *verdict* is computed without reference to that lane. **A precondition on who may ask is not a consistency rule about what is answered.** **The consistency boundary is therefore still wider than any positive invariant demands** — an honest finding that argues *for* splitting — **while the read-dependency argues against it**, since a split would turn one query into a cross-aggregate read.

**③ §10.1, the `GrantScope` evidence cell (proposal line 307) — REPLACE:**

> `Observed`: `authorized` does scope-string equality and never consults the session

**with:**

> `Observed`: `authorized` decides by scope-string equality alone; the session is checked for existence, never read into the verdict

**What is preserved verbatim, and must be:** **Governance owns authority meaning.** BC-7 matches a scope by **equality on an opaque string** and never parses it; whether a grant should exist, and what its scope *means*, is Governance's and is not touched by this correction. **Equality-matching is not interpretation** — this refinement adds nothing to what BC-7 may conclude about authority, and §10.1's rule ("the model must expose no operation of the form *may X do Y?*") stands unchanged.

---

# 4 · C-2 — T-1's precision

## 4.1 Re-derivation: what the mechanism actually does with a submitted derived value

C-2 asks whether the mechanism *refuses* a submitted `mutationOwner` / `state` key. **Measured answer: it does not — and it does not need to.** Three separate protection mechanisms exist in the source, and "refusal of a derived key" is not one of them:

| Mode | Where it operates | Measured behaviour |
|---|---|---|
| **Refusal** | `assertTransitionAllowed`, the `grant` writer/field checks | refuses on **type, required fields, ownership, stickiness, writer class**. **There is no key whitelist:** `append` decodes the submitted JSON and, once the preconditions pass, stores the object as given. **A submitted `mutationOwner` key would be persisted, not refused.** |
| **Overwrite** | `append` sets `seq = count(transitions)+1`; `grant` sets `registeredBy = 'governance'` unconditionally | **two mechanism-assigned values are demonstrably neutralised on input** — a submitted value is replaced, not rejected. This is the *only* demonstrated neutralisation path in the source. |
| **Non-consultation** | `foldSessions` | the fold reads **only** the fields it is defined over (`type`, `session`, `role`, `predecessor`, `executionContext`, `from`, `to`). **It never reads a derived key from a record.** A stray `mutationOwner` would be inert data with no path to truth. |

**And the load-bearing fact, which neither input document states:** **`assertTransitionAllowed` folds the record as its first act**, then checks the submitted transition against the *computed* state. **The party submitting a transition therefore never supplies the state its transition is judged against.** That is not a measurement of practice — it is a structural property of the mechanism, and it is the strongest form the invariant can take.

## 4.2 C-2 is upheld, and it is larger than reported

V#1 says T-1's "accepted as input" half is undemonstrated. **Confirmed — and understated in two ways:**

1. **It is not merely undemonstrated; as an absolute it is false.** A derived key *is* accepted as input, in the sense of being stored. What is guaranteed is that it is never **consulted**. "Ever accepted as input" and "never authoritative" are different claims, and only the second is true.
2. **The defect appears at three sites, not one** — T-1 (line 240), §5.4 (line 208), **and DP-6** (line 279, *"derived values are never accepted as input"*). **DP-6 is not named in the verification report.**

## 4.3 The three-way distinction C-2 asks for

| Category | What it is | **Producer** | Standing in the domain |
|---|---|---|---|
| **Recorded fact** *(authoritative state)* | the transition log and the grant register — the whole of what BC-7 stores | the writer of the transition or grant | **the only truth BC-7 holds**, and the sole input to the fold |
| **Derived state** | `mutationOwner`, assignment `state`, `workItemState` | **the fold, exclusively** | authoritative **as a computation over** recorded fact, **never as data**. It has no stored form and no second producer |
| **Validation input** | the state a precondition is checked against | **the fold, recomputed at check time** | **never supplied by the party being validated** |

**The domain truth, in one sentence:** **BC-7 stores only what happened; everything about where things now stand is a computation; and the party recording an act never supplies the standing against which the act is judged.** That is the tactical form of I-10 (*the record outranks prose*) — one truth, one producer, and no self-supplied premise.

## 4.4 Proposed wording

**① T-1 (proposal line 240) — REPLACE** the invariant text and class with:

> | **T-1** | **Derived state has exactly one producer — the fold.** `mutationOwner`, assignment `state` and `workItemState` are produced only by folding the recorded log; no other producer exists, and no recorded value may stand in their place. This holds in three parts, which are of different strengths and are stated separately rather than merged into an absolute: **T-1a — a submitted transition never supplies the state against which it is validated** (the mechanism folds the record itself before every precondition check). **T-1b — a derived value carried on a submitted transition has no standing:** the fold reads only the fields it is defined over, so such a value is inert data, never truth. **T-1c — a writer obligation, not a refusal: no derived projection is written into a record.** *Measured: 0 of 126 transitions across 15 records carry a derived key, and no non-canonical key appears anywhere in the estate.* **The mechanism does not refuse such a key — there is no key whitelist. The invariant holds by non-consultation (T-1b), not by refusal at the boundary.** | **Owns** | **T-1a, T-1b `Observed`** (structural, in source) · **T-1c `Proposed`** — an obligation on writers, `Observed` in practice at 0/126 |

**② §5.4 (proposal line 208) — REPLACE:**

> The modelling rule this proposal states plainly: **no derived projection may be stored, set, or accepted as input.**

**with:**

> The modelling rule this proposal states plainly: **a derived projection has one producer and no stored form.** It is never *set*, and never *consulted* if present — the mechanism computes it, and a recorded value bearing its name has no standing (T-1a/T-1b). **The rule is not that such a value is refused at the boundary; it is that it can never become truth** (T-1c).

**③ DP-6 (proposal line 279) — REPLACE the COMPLETE row (all three cells; corrected per Verification #2 R-2, which found this instruction quoted and replaced only two of the row's three cells, so that applying it literally would have dropped the class marker):**

> | DP-6 | State is folded from the log; derived values are never accepted as input | `Observed` + **`Proposed`** as T-1 |

**with:**

> | DP-6 | State is folded from the log; **a derived value is never consulted and never authoritative — the fold is its only producer** | **`Observed`** (T-1a/T-1b — structural, in source) + **`Proposed`** (T-1c — the writer obligation), **as T-1** |

**Note on the third cell, so the PO/ARB can see it is derived rather than invented:** the class value is not a new decision — it is the mechanical consequence of T-1's own restatement in ① above, which splits the invariant into two structurally `Observed` parts (T-1a/T-1b) and one `Proposed` writer obligation (T-1c). DP-6 mirrors T-1 and its class cell follows T-1's; **no policy, invariant or domain rule is created, changed or added here.**

**Why this is a strengthening, not a weakening.** The absolute form rested on a census (0 stored) that a single future write could falsify. The restated form rests on **the fold's definition** — which no write can falsify — and confines the census-based claim to the one part (T-1c) that genuinely is a practice measurement. **The invariant now says less than it did, and everything it says is true.**

---

# 5 · C-3 — independent classification

**The question, as `AMD2` restated it and as it is answered here:** not *"an architecture writer wrote a transition"* but **which bounded context owns the ability to produce a lifecycle fact?**

## 5.1 The evidence, derived before any classification is considered

| # | Fact | Source |
|---|---|---|
| E-1 | The two `architecture` recorders are **both HANDOFF**. No `architecture` recorder appears on REGISTER, START, COMPLETE, STOP, CANCEL, **or on any grant**. | cross-tabulation, §2 |
| E-2 | In **both** instances the recording lane **is the `from` lane** — the current mutation owner handing over its own finished product. | record inspection, §2 |
| E-3 | **DP-3 makes the current mutation owner the required actor for a handoff:** the mechanism refuses `only the current mutation owner can hand off (Inv C)`. **In both instances the recorder and the contractually required actor are the same lane.** | source, `case 'HANDOFF'` |
| E-4 | **A HANDOFF confers nothing.** The fold sets `owner = null` on HANDOFF; ownership passes **only at START**. | source, `foldSessions` — `$owner = null; // held for the successor; ownership passes only at START` |
| E-5 | **START is 35/35 `recordedBy: human`** and refuses without a non-empty human act **and** a recorded predecessor handoff (I-2 / G-3 conjunction). | cross-tabulation + source |
| E-6 | **The authority record has exactly one writer, refused at the boundary:** `grant` refuses any `--writer-role` other than `governance`. **Measured: 47/47 grants `registeredBy: governance`; no other writer has ever been recorded.** | source + census |
| E-7 | On every transition type **except COMPLETE and CONTINUATION**, the only constraint on `recordedBy` is **non-emptiness**. The recorder class of a HANDOFF is unconstrained **by design, not by omission of enforcement**. | source, `assertTransitionAllowed` |
| E-8 | **The two enforced recorder checks are not I-5's.** The mechanism attributes them verbatim to other rules: COMPLETE → `'closure is a governance act (G-1)'`; CONTINUATION → `'no other exit from STOPPED exists (Inv E)'`. | source, refusal messages |

## 5.2 The classification

> ## Verdict: **(D) — a mis-scoped invariant statement.** Not a violation, not a missing exception, and not primarily an unclear ownership model. **The defect is in the proposal's I-5 row, not in the invariant, not in the mechanism, and not in the model.**

**D.1 — The observation and the invariant live in different parts of the aggregate.** I-5 governs writes to the **Authority part** (the grant register). The observation is a write to the **Lifecycle part** (a transition). **I-4 says these two are never merged. Measuring I-5 against a lifecycle transition is itself the conflation I-4 forbids** — committed in the proposal's prose, and nowhere in the mechanism. Where I-5 actually applies, enforcement is total and exceptionless (E-6).

**D.2 — The instance is not merely permitted; it is the *required* actor.** DP-3 says only the current mutation owner may hand off, and in both instances the recorder *is* that owner (E-2, E-3). **A governance-recorded handoff of a producer's finished work would be the less faithful attribution of the two.** The observed pattern — producer finishes, records its own handoff, human starts the successor — is the contract working, not leaking.

**D.3 — The model already predicts the observation.** **T-4: attribution is recorded as a claim, never as an attestation.** `architecture` is exactly as much a claim as `governance`; the mechanism authenticates neither (I-7: process identity never authorizes, *realized by absence*). **An instance that the model predicts is not a counter-example to it.** The I-5 row called it *"one instance of a non-governance, non-human writer class in the wild"* — the phrase *in the wild* imports a sense of escape that the evidence does not support.

**D.4 — The instance could not have manufactured authority or activation even in principle.** A HANDOFF passes no ownership (E-4); activation requires a human act at START (E-5); and the authority register was unreachable from it (E-6). **The path from "an architecture lane recorded a transition" to "authority was written by a non-Governance actor" does not exist in the mechanism.**

**D.5 — Answering `AMD2`'s real question.** **BC-7 owns the *rules under which a lifecycle fact is admitted*** — DP-1…DP-7, its entire decision surface. **It does not own, and cannot know, who produced one:** T-4 and I-7 place producer identity outside what BC-7 can attest. **So no bounded context in the accepted model owns "the ability to produce a lifecycle fact."** That ability is a property of *access to the mechanism* — an operational and governance concern, not a domain-model one. **That is the architectural classification, and it is deliberately the end of Architecture's part:** whether lifecycle recorder classes *should* be constrained is Governance's decision, and this refinement does not make it.

## 5.3 The three registered candidates, tested rather than inherited

Each is stated at its strongest before being answered.

| | Candidate | Verdict | Reasoning |
|---|---|---|---|
| **(A)** | **The invariant is wrong** — e.g. architecture may perform a lifecycle handoff because handoff is lifecycle, not authority | **Rejected as a conclusion; its embedded reason is correct** | The clause *"handoff is lifecycle, not authority"* is exactly right and is D.1. But the conclusion does not follow: **I-5 is not wrong, it is being applied where it does not reach.** I-5 is *preserved* from Governance, and this refinement proposes **no change to it whatsoever**. Adopting (A) would amend a Governance-owned invariant to fix an error in an Architecture-owned sentence. |
| **(B)** | **The invariant is right, the mechanism is wrong** — only Governance may record lifecycle transitions, and the mechanism fails to enforce it | **Rejected** | There is no such rule to fail to enforce. The recorder class of a HANDOFF is unconstrained by design (E-7), and the two checks that do exist belong to G-1 and Inv E, not I-5 (E-8). **Adopting (B) would create a rule that has never existed and would contradict DP-3 in both observed instances** — the required actor would become a forbidden recorder. |
| **(C)** | **A missing distinction** — `recordedBy ≠ authorityOwner`: writer identity and authority identity are different concepts | **True as a statement; rejected as the diagnosis** | The distinction is real and important — **and it is not missing.** The model already holds it, twice: **T-4** (attribution is a claim, never an attestation) and **I-7** (process identity never authorizes). **What is missing is not the distinction but its application in the I-5 row**, which reasons as though `recordedBy` carried authority weight that T-4 explicitly denies it. (C) would have the model add what it already has. |

**Why the verdict is (D) and not a blend:** (A), (B) and (C) each presuppose that *something in the model or mechanism must change*. **Nothing must.** The mechanism is conformant, I-5 is correct, T-4 and I-7 already carry the distinction, and the one artefact that is wrong is a table cell in a proposal that has not yet been accepted. **The cheapest true repair is the correct one.**

## 5.4 Proposed wording

**① The I-5 row (proposal line 237) — REPLACE:**

> | I-5 | Only Governance writes authority | **Preserves** — mechanically on 2 of 5 transition types; convention on the rest (C-4 standing) | `Observed`; **and note the measured `architecture` recorder (§2.1) — one instance of a non-governance, non-human writer class in the wild** |

**with:**

> | I-5 | Only Governance writes authority | **Preserves — and it is enforced totally where the invariant applies.** The Authority part has exactly one writer, refused at the boundary for any other role. *Measured: **47/47** grants `registeredBy: governance`, **47/47** carrying a `humanActRef`; no other writer class has ever been recorded.* **The recorder-class checks on COMPLETE and CONTINUATION are not I-5's** — the mechanism attributes them to **G-1** (*closure is a governance act*) and **Inv E** (*the only exit from STOPPED*) respectively. | `Observed` |

**② The `recordedBy` census note (§2.1, proposal line 45) — REPLACE** the flat census line with a cross-tabulated one, and attach the reading:

> | `recordedBy` × transition type | **REGISTER 36/36 governance · START 35/35 human · COMPLETE 16/16 governance · CANCEL 1 governance · STOP 1 human · HANDOFF 35 governance + 2 architecture** | cross-tabulation |
>
> **The two `architecture` recorders are confined to HANDOFF, and in both the recording lane is the `from` lane — the current mutation owner handing over its own finished product, which is precisely the actor DP-3 requires.** This is the contract working, not a writer class escaping it: a HANDOFF confers nothing (ownership passes only at START, which is 35/35 human-recorded under the I-2 conjunction), and the authority register is unreachable from a transition. **Per T-4, `architecture` is a recorded claim exactly as `governance` is; the mechanism attests neither.** The open question this *does* raise — whether `RecordedBy` is a closed vocabulary and whether lifecycle recorder classes should be constrained at all — **is Governance's, and is carried at OQ-10.**

**③ OQ-10 (proposal line 354) — REPLACE:**

> | OQ-10 | **Recorder classes:** one `recordedBy: "architecture"` exists in the estate. Is the recorder class an open or closed set? | bears on I-5/C-4 and on whether `RecordedBy` is a closed vocabulary VO |

**with:**

> | OQ-10 | **Recorder classes — a Governance question, stated at its correct scope.** `recordedBy` is constrained only on COMPLETE and CONTINUATION (and there by G-1 and Inv E, not by I-5); on every other type the sole requirement is non-emptiness. **Two `architecture`-recorded HANDOFFs exist, both contract-conformant** (§2.1). **Should the recorder class of a lifecycle transition be constrained at all, and is `RecordedBy` a closed vocabulary?** **⭐ AND, added per Verification #2 R-3 — does `recordedBy` carry ONE referent?** *Observed:* on a **START** it names the act's **origin** (the human), while Governance performs the registration; on **REGISTER / COMPLETE / CANCEL** it names the **acting role**; on the two `architecture` HANDOFFs it names the **performing lane's role**. *Evidence: **6 of 37 STARTs** are `recordedBy: human` while their own `humanAct` text states that **Governance** recorded the START (e.g. `KOS-ARCH-BASELINE-001` seq 3: "Registered by Governance … registering a START is a GOVERNANCE act"), so a reader asking "who registered this transition?" is answered wrongly by the field on **37 of 37** STARTs.* **The question is recorded, not answered.** | It decides whether `RecordedBy` is a closed-vocabulary VO or an open value — **and it is the residual of C-3. Architecture's classification is that no invariant is breached; whether the invariant set should be extended is Governance's decision, not the domain model's.** **The referent limb matters for the same reason C-3 arose: a field whose referent shifts by transition type is what invites an invariant to be measured against the wrong part of the aggregate.** ⛔ **No solution is proposed here — no rename, no new model, no ADR, no vocabulary decision.** Verification #2 declined to propose wording on the ground that it would be modelling; **this correction inherits that restraint verbatim.** |

---

# 6 · Two observations arising, offered without a proposed change

Recorded because they were found while re-deriving the three findings. **Neither is a finding against the model, and neither requires a wording change** — they are surfaced for the PO/ARB rather than left in a working note.

**N-1 · The `identity` query reads both parts of the aggregate in one call — and neither input document raises it.** `identity` answers per-session with lifecycle attributes (`role`, `predecessor`, `state`, `executionContext`) **and** an `authorizationLinkage` drawn from the grant register. **Two precise facts:** the linkage is the **last** AUTHORIZED `grantId` in the record, computed by a loop that overwrites — so it is a **work-item-scoped value presented inside a session-scoped answer**, and it does not vary with the session asked. The source itself names the constraint it is honouring: *"links to, never contains, authority (Inv B/R6)"*. **This is a second, stronger cross-part read than the one F-1 found**, and it points the same way: the two parts are read together at the query surface, inside one root. **It confirms §4.5's conclusion and adds nothing to its argument.** V#1's traceability cites this source range but raises no finding on it. **Recorded as an observation; no model change proposed; OQ-9 not reopened.**

**N-2 · Confirming O-1's substance at a third measurement point.** 117 → 121 → 126 transitions and 41 → 47 grants across the three lanes. **Every load-bearing proportion is unchanged** (0 stored derived keys; `CONTINUATION`/`FAIL` still 0/126; all grants AUTHORIZED). **The one count that moved qualitatively is the `architecture` recorder: 1 → 2 — which is why §5 could read it as a pattern rather than an anomaly.** The proposal's censuses should carry their measurement date rather than be restated as current, since they will drift again.

---

# 7 · Decisions unchanged — stated explicitly, not left to inference

| Decision | Status after this refinement |
|---|---|
| **`WorkItem` is the aggregate root** | **UNCHANGED.** Not re-tested, not re-argued, not weakened. F-1 marginally *strengthens* it (§3.2). |
| **The BC-7 strategic boundary** | **UNCHANGED.** BC-7 owns workflow lifecycle and recorded orchestration history; **Governance owns authorization policy and authority meaning** — preserved verbatim in §3.3. |
| **`ADR-AIP-03`** | **UNCHANGED and not reopened.** Not read as a decision input; not cited as amendable. |
| **`ADR-AIP-04`** | **UNCHANGED.** No role-model decision made or implied; `Role` semantics remain deferred. |
| **`CAP-14`** | **UNCHANGED.** Not touched, not re-scoped, not refined. |
| **Rejections RA-1…RA-6** | **UNCHANGED.** RA-4 in particular is neither adopted nor re-opened; OQ-9 stands as the proposal left it. |
| **Invariants I-1…I-10, T-2, T-3, T-4** | **UNCHANGED.** T-4 and I-7 are *cited* in §5 as already holding the distinction C-3 probes — **cited, not amended.** |
| **I-5 itself** | **UNCHANGED.** §5.4 rewrites the proposal's *row about* I-5; the invariant's text and its Governance ownership are untouched. |
| **T-1** | **Restated for precision, same invariant, same owner** (§4.4) — narrower, and true throughout. |
| **The strategic model, Phase A v1.1, the v2 set** | **UNCHANGED — inputs, never overwritten.** |
| **Implementation architecture** | **UNCHANGED — none exists and none is proposed.** |

---

# 8 · What this refinement does not do

No BC-7 redesign · **no alternative aggregate model** · no strategic-boundary modification · no reopening of `ADR-AIP-03` · no `CAP-14` change · no `ADR-AIP-04` decision and no role-ownership decision · **no modification of the proposal or of the verification report** — every correction is a proposed replacement text awaiting the PO/ARB · no implementation, no code, no `workflow_engine` change · **no acceptance and no self-verification.**

**One record mutation was performed and is disclosed:** the **seq-12 START** of this lane, recording the PO/ARB's human act verbatim, written by this lane per the practice disclosed and accepted at `KOS-ARCH-BASELINE-002` seq 3 and `KOS-ARCH-BASELINE-003` seq 3. **This lane does not complete its own assignment (G-1) and does not hand itself off without the PO/ARB's act.**

**Next actor: independent Verification #2** — which, per `R-34`/P-2 and `AMD3` bar (4), **must not be this process** — then the PO/ARB decision on the proposal as refined.

---

# 9 · Open questions remaining

**Carried from the proposal, untouched by this refinement:** OQ-1 (role-model ownership, `ADR-AIP-04`) · OQ-2 (BC-4 relationship) · OQ-3 (CAP grain) · OQ-4 (session-name uniqueness) · OQ-5 (`CONTINUATION`/`FAIL` — still 0/126) · OQ-6 (`Grant` entity or immutable record — still 47/47 AUTHORIZED, still no observed status change) · OQ-7 (canonical event vocabulary) · OQ-8 (REGISTER/START/COMPLETE asymmetry — now 36/35/16) · OQ-9 (one aggregate or two) · OQ-11 (context name fitness).

**Sharpened by this refinement:**

| # | Question | Change |
|---|---|---|
| **OQ-10** | **Should the recorder class of a lifecycle transition be constrained at all, and is `RecordedBy` a closed vocabulary?** | **Rescoped and handed on.** It is no longer *"is this instance a violation?"* — §5 answers no. It is now a Governance question about whether the invariant set should be **extended**, put after Architecture's classification exactly as `AMD2` sequenced it. |

**Raised by this refinement, and genuinely open:**

| # | Question | Why it matters |
|---|---|---|
| **OQ-12** | **Should invariants carry an explicit enforcement class** — *refused · overwritten · not-consulted · writer obligation*? T-1's defect was that an obligation and a structural guarantee were merged into one absolute (§4.1). Several other invariants may merge the same way. | It would make the difference between *"the mechanism prevents this"* and *"no one has done this"* visible at the point of statement rather than discoverable only by re-deriving from source. **A modelling-convention question for the PO/ARB — deliberately not applied beyond T-1 here.** |
| **OQ-13** | **Is `authorizationLinkage` a modelling element or a query convenience?** It presents a work-item-scoped value inside a session-scoped answer (N-1). | It bears on whether the aggregate's query surface is part of the model. **Raised, not answered — answering it would be modelling, which this lane may not do.** |

---

**Traceability:** grants `G-KOS-ARCHBASE3-REFINE` + `AMD1` (three eligibility bars) + `AMD2` (C-3 framing, three ARB candidates *to test, not inherit*) + `AMD3` (eligibility rejection, four bars, exhaustive input list) · record `KOS-ARCH-BASELINE-003` seq 10 (REGISTER of this lane), seq 11 (HANDOFF from the superseded lane, recorded as the PO's routing decision expressed mechanically), **seq 12 (this lane's human START)** · inputs: `KOS-ARCH-BASELINE-003-bc7-domain-model-proposal.md` · `KOS-ARCH-BASELINE-003-verification-report.md` (F-1, C-1, C-2, C-3, O-1, O-2) · the ARB routing decision as delivered · **primary evidence, declared as an input-boundary extension:** `.claude/scripts/workflow-state.php` (`foldSessions`, `assertTransitionAllowed`, `append`, `grant`, `identity`, `authorized`) and the 15 live work-item records / 126 transitions / 47 grants, measured 2026-08-17 before seq 12 · `INV-ATTR-2` · `R-34` · `R-37` · `ES-005.4` · invariants I-2, I-4, I-5, I-7, I-10, T-1, T-4 · policies DP-3, DP-6, GP-1, GP-2, G-1, Inv C, Inv E.

---

> **Independent architecture refinement. Not verification. Not acceptance. The PO/ARB decision follows Verification #2.**
