# OPERATION CONTRACTS — the eleven fields, per candidate member

> ## ⛔ STATUS BLOCK — NOTHING IN THIS FILE IS RATIFIED
>
> **Authority:** HPA ruling **GN-79** / commission **GN-80** (2026-08-31), ARCHITECTURE/THEORY
> lane. **Task 7 of the commission.**
>
> **No contract below is canon.** Nothing here selects a registry, adopts a candidate, or defines
> an operation for the programme. Each block records **what a contract for this operation would
> have to say**, filled only where the ratified surface or an executed result supplies the value,
> and marked **`NORMATIVE DECISION REQUIRED`** — with the choice named and left unmade —
> everywhere else.
>
> **The set of operations contracted here is the UNION of every operation that appeared in some
> minimal witness of some mandatory capability** (`MINIMALITY-RESULT.md` §2). It is **not** a
> registry and **not** a recommendation of membership. Where two operations were shown
> effect-equivalent, one block covers the class and names every member.
>
> `DERIVED` · `FORMALLY SHOWN` · `EMPIRICALLY TESTED` · `PROPOSED` ·
> `NORMATIVE DECISION REQUIRED` · `RATIFIED` (used only of pre-existing constraints).

---

## 0 · How the fields were filled, and what could not be filled

The commission's eleven fields (T-4) plus provenance (C-7):

`name · purpose · input state · preconditions · state transition · postconditions ·
invariant obligations · evidence effect · authority effect · replay semantics ·
failure semantics` (+ `provenance / evidence class`).

### 0.1 Three fields cannot be canonically filled for ANY operation `FORMALLY SHOWN`

| field | why it cannot be filled | consequence in every block below |
|---|---|---|
| **invariant obligations** | The invariant register is **not closed**. The canon asserts I-1…I-12 and FA-1's additions and **nowhere asserts these are all of them**; step 232 §232.39 independently proposes a *different*, five-element minimal register. `TRANSFORMATION-CONTRACT-GAP` §C-3: *"'Preserves I-n' presupposes that the set of I-n is closed."* | Each block lists the obligations **against the enumerated ratified invariants only**, prefixed `(open register)`. The field is **partial by construction**, not by omission. |
| **failure semantics** | The canon defines **no rejection vocabulary**. The corpus's only typed rejection (`TRANSFORMATION-CANONICAL-MODEL` §1) has **three** kinds and cannot express the evidence-admissibility refusal step 232 §232.21 requires, nor an I-12 legality refusal. No corpus document states (i) the state after a rejection, (ii) whether a rejection is recorded in history, or (iii) whether a rejected operation is a no-op or an exception. | Each block names its refusals in the **model-local five-kind** vocabulary (`structural · legality · policy · authority · evidence`), marked `PROPOSED`, and carries the three unanswered questions as a single `NORMATIVE DECISION REQUIRED`. |
| **postconditions** (partly) | A postcondition is a predicate over the resulting state, and **state equality is undefined in the entire corpus** — step 257 §257.37, in the corpus's own voice: *"Identity is not fully specified, Equality is not specified, EpistemicStatus is not frozen… **the correct next move is not to select a kernel**."* | Postconditions are stated as **field-level** assertions, which are checkable without a state-equality rule. Any postcondition of the form `K' = f(K)` is marked `NORMATIVE DECISION REQUIRED`. |

**These are the commission's C-5 prerequisites, and the executed evidence is that they block the
CONTRACTS while not blocking the MINIMALITY TEST.** Both facts are reported; neither substitutes
for the other.

### 0.2 Contract-completion rate

**23 operation blocks** covering **24 operations** (C-23 carries two). Each block carries all
**eleven** commission fields **plus provenance** — verified mechanically: *23 blocks, 0 blocks with
a missing field*. That is **253** commission cells and **276** rows in total. Marker counts,
also mechanical:

| marker | occurrences | meaning |
|---|---|---|
| `RATIFIED-SOURCED` | **27** | the value comes from the ratified/authorized surface |
| `EXECUTED` | **24** | the value comes from a run recorded in `exec/` |
| `PROPOSED` | **12** | model-local, defensible, explicitly not canonical |
| **`⚠ NDR`** / `NORMATIVE DECISION REQUIRED` | **69** | a named choice, deliberately left unmade |

**Every one of the 253 commission cells carries content**; none is silently blank. What varies is
the *authority* of the content:

- **Two fields are open for EVERY operation** — `invariant obligations` (no closed register) and
  `failure semantics` (no rejection vocabulary). That is **46 cells** open for a reason that is not
  operation-specific, and it accounts for the bulk of the `⚠ NDR` count in §1.1–§1.2.
- The remaining `⚠ NDR` markers are **operation-specific** and each is named in place — the
  granularity choice at C-01, `Authorize`'s missing precondition at C-03, `Reject`'s four-way
  typing at C-12, the resolution return-rung at C-15, the DC conjunction at C-18, replay-in-the-
  world at C-19, and the postcondition/equality dependency at C-20.

**The honest summary: no operation has a complete canonical contract, and none can have one until
P-8/P-9/P-10 of `MINIMALITY-RESULT.md` §5.3 are settled.** What this file establishes is that
**51 values** across the blocks are now marked `RATIFIED-SOURCED` (27) or `EXECUTED` (24) —
where the governed baseline had almost none:
`analysis/OPERATION-CONTRACT-GAP.md` §B records **2 cells fully fixed, 9 partial and 88 empty**
across its nine capabilities. `DERIVED`.

### 0.3 What is deliberately NOT contracted

| operation | why no contract is offered |
|---|---|
| **`Split`** | **Violates I-12** (executed: it creates items directly at `Supported`/`Accepted`/`Conflicted`, entering above the first rung). Also **lossy on ℛ** (executed counterexample; `Split(Merge(K₁,K₂)) ≠ (K₁,K₂)`), **forced by no non-collapse law**, and *"needs a semantic-preservation invariant the corpus never supplies."* Writing a contract would require choosing the repair. **`NORMATIVE DECISION REQUIRED`** — the three repairs (products enter at `Candidate`; products inherit and I-12 is amended; `Split` excluded) are **not interchangeable**, and the second would amend a ratified invariant. |
| **`Merge`, `Revise`, `Supersede`, `Withdraw`, `Reintroduce`, `Derive`, `Refine`, `Remove`/`Retract`, `Trace`, `Query`, `Compare`, `Equal`, `Identity`, `Explain`, `Evaluate`, `LineageQuery`, `ProvenanceQuery`, `ExplainRevision`, `SupersessionHistory`, `Validate`, `AssertPolicy`, `noop`, `AdmitEvidence`** | **No mandatory capability's witness contains them** (`MINIMALITY-RESULT.md` §2). Their absence is **not** a claim of unnecessity — it is the statement that **no ratified law or flow node quantifies over them**, so no contract can be derived from the ratified surface. Contracting them would be authorship. |
| **`ChangePolicy` as the corpus states it** | It has **no signature, no authority guard and no runtime** anywhere in the corpus. The block in §2 is written for the **I-11-routed** capability, and says so. |
| **Adjudication of `CONFLICTED`** | The corpus has **no such operation** — *"ABSENT, no signature at all"*, confirmed three times. Art. 8 nonetheless ratifies that a CONFLICTED item is held *until a governed resolution*. The `Resolve` block in §2 is therefore a contract for a capability whose **only** named realizer (272A's `O_E` `Resolve`) carries no definition in the corpus. Flagged in place. |

---

## 1 · The three prerequisites, as delivered

Per commission **C-5**, each is either delivered or deferred, and the consequence is stated.

### 1.1 Closed invariant register — **DEFERRED** `NORMATIVE DECISION REQUIRED`

What was used, in the absence of closure: the enumerated ratified set
`I-1 … I-12` (v0.2 §4, AUTHORIZED) plus FA-1 §2 / D-FA-1's additions (REJECTED terminal-preserved,
CONFLICTED governed-suspension, `UNKNOWN ≠ ABSENT ≠ FALSE`), plus the DC interlock and the seven
non-collapse distinctions. **The canon does not assert that this is all of them, and a competing
five-element register exists (232 §232.39).**

**Named choice, not made:** *is the invariant register closed at I-1…I-12 + FA-1's additions, and
if not, what act closes it?* Until answered, every `invariant obligations` field has an **open
codomain**, and no operation can be certified as invariant-preserving — only as
*not-observed-to-violate the enumerated invariants*, which is what §2 reports.

### 1.2 Typed rejection semantics — **DEFERRED; a PROPOSED vocabulary supplied** `PROPOSED`

Five kinds were needed to run the test. Each is justified from a ratified constraint or a corpus
requirement, and **none is canonical**:

| kind | refuses because | derived from |
|---|---|---|
| `REJECTED(structural)` | referent absent, malformed, dangling endpoint, ill-formed proposition | `TRANSFORMATION-CANONICAL-MODEL` §1 (executed) |
| `REJECTED(legality)` | the transition would break the covering relation | **I-12** — a kind the corpus's three-kind vocabulary cannot express |
| `REJECTED(policy)` | no in-force AcceptancePolicy, or the policy refuses | `TRANSFORMATION-CANONICAL-MODEL` §1 |
| `REJECTED(authority)` | no authority act; not routed through governance | `TRANSFORMATION-CANONICAL-MODEL` §1 · **A6** · **I-11** |
| `REJECTED(evidence)` | an evidence dependency is unresolved | step 232 §232.21 `E(T) ⊨ ReqEvidence(T,P)` — a kind the three-kind vocabulary cannot express |

**Named choices, not made:** *(a) how many rejection kinds, and which?* *(b) is the state after a
rejection the input state, or something else?* *(c) is a rejection appended to history?* *(d) is a
rejected operation a no-op or an exception?* The corpus answers none of (b), (c), (d), and
`gap-discovery/09-TRANSFORMATION-GAP` records the consequence: *"without failure semantics, `T` is
a **partial function presented as total**, and `replay` cannot reproduce histories containing
refusals."*

The model's own answers, used throughout §2 and marked `PROPOSED`: **(b) the input state
unchanged · (c) NOT appended · (d) a total function returning a typed outcome, never an
exception.** Answer (c) is in direct tension with
`TRANSFORMATION-THEORY.md`'s `History_{t+1} = History_t ⌢ ⟨o, policy, authority, actor,
wallclock, Outcome⟩`, which *does* record rejections — **an unreconciled difference, reported.**

### 1.3 State identity and equality rule — **DEFERRED; structural equality used** `PROPOSED`

Used: **structural equality** — two states are equal iff every field is equal as a set/tuple
(`exec/rm.py`, `eq_structural`; commitment **M1**). This is the reading
`TRANSFORMATION-CANONICAL-MODEL` §2 implies when it explains idempotence (*"`assert` and `relate`
are idempotent because `𝒜` and `ℛ` are sets"*), and it is what makes the search's state
deduplication decidable.

**It is not canonical and the corpus does not agree.** One lane declares equality **CLOSED** with
five defined notions (object · structural · semantic · observational · history, with
`history ⊊ structural ⊊ semantic`); another declares it **UNCONSTRUCTED**: *"the same five states
fall into 2, 3, 5 or 5 equivalence classes… `K₁ = K₂` has no truth value in the theory as it
stands"*, and *"a relation defined by `∀T ∈ 𝒯` where `𝒯` has no extension is **not yet a
relation**."* Step 260 additionally requires the equality to be a **congruence** and finds two of
four candidates are not.

**Named choice, not made:** *which equality is canonical, and is it a congruence for the
registry's operations?* Consequence: every `replay semantics` field below is conditional on that
answer, and the algebraic laws the corpus states (commutativity, associativity, idempotence, the
join-semilattice) remain **unverifiable as written** — they are equations between states.

**Note the ordering trap.** This is the deepest of the three, and it is **circular with the
registry**: an equality quantified over `∀T ∈ 𝒯` needs `𝒯`; a `𝒯` whose contracts have
postconditions needs the equality. `MINIMALITY-RESULT.md` §6 reports how the test was made
executable without breaking the circle — by testing **reachability**, which needs only a
decidable state comparison, not a canonical one.

---

## 2 · The contracts

Each block is one **effect-equivalence class** (`MINIMALITY-RESULT.md` §1). `RATIFIED-SOURCED`
marks a field whose value comes from the ratified surface; `EXECUTED` from a run;
`PROPOSED` from the model; `⚠ NDR` = `NORMATIVE DECISION REQUIRED`.

---

### C-01 · `Promote` ≡ `Transform` — advance one rung of the admission ladder

| field | value |
|---|---|
| **name** | `Promote` (259.7/259.8) ≡ `Transform` (256.2/256.7/256.20, 249, 250, 232) — **effect-equivalent under the ratified guards** `EXECUTED` |
| **purpose** | move an item to the **immediate successor** rung of the admission ladder. The **only** realizer found for `A1` (`Candidate → Supported`) other than a three-operation detour through `CONFLICTED` `EXECUTED` |
| **input state** | `K` containing item `i`, with `status(i) ∈ {Candidate, Supported}` |
| **preconditions** | `i ∈ K` · `status(i) ∈ LADDER` · `next_rung(status(i))` exists · **for the `Accepted` rung: an in-force AcceptancePolicy** `RATIFIED-SOURCED` (v0.2 *Determination*) · for the `Supported` rung: a computed evidence grade `PROPOSED` — **⚠ NDR: is the `Candidate → Supported` rung gated on evidence at all? The ratified surface states no gate for it.** |
| **state transition** | `status(i) := next_rung(status(i))`. Nothing else changes. |
| **postconditions** | `status(i)` is the immediate successor of its input value · `i` is still present · `committed(i)` unchanged · no other item changes |
| **invariant obligations** | (open register) **I-12** — guaranteed by construction, the successor is computed, never supplied · **A6** — cannot set `committed`, so it cannot cross the boundary · **I-2** confers no authority. `EXECUTED`: 20 790 applications, zero I-12 violations for this class |
| **evidence effect** | **none.** Reads a grade if the `Supported` gate is adopted; writes no evidence `PROPOSED` |
| **authority effect** | **none.** ⚠ NDR: 257 §257.6 says `Proposed → Accepted` *"may require authority"*; the ratified surface routes the `Accepted` rung through **policy**, not authority. **Which governs the second rung — policy, authority, or both?** |
| **replay semantics** | deterministic; **not idempotent** (a second application advances again). Conditional on §1.3 |
| **failure semantics** | `REJECTED(structural)` absent item · `REJECTED(legality)` already at the top rung, or off-ladder · `REJECTED(policy)` no in-force policy for the `Accepted` rung · `REJECTED(evidence)` no grade `PROPOSED`. ⚠ NDR: §1.2 (b)(c)(d) |
| **provenance** | `Promote`: 259.7/.8, **CORPUS-NAMED, no signature anywhere**. `Transform`: `K × Rule → K'`, **CORPUS-NAMED + SIGNED**, and *forced by no non-collapse law* |

> **⚠ NDR — the granularity decision, in its sharpest form.** `Promote`/`Transform` covers both
> rungs; `Determine`/`Accept` (C-02) covers only the second. **One coarse operation or two fine
> ones?** Both coverings are minimal (`MINIMALITY-RESULT.md` §4). The canon fixes neither.

---

### C-02 · `Determine` ≡ `Accept` — the Determination transition

| field | value |
|---|---|
| **name** | `Determine` (forced by `Determination ≠ Decision`, 19 corpus occurrences; **absent from §256.2 and §259.7**) ≡ `Accept` (250 §250.8, verdict `UNRESOLVED`) `EXECUTED` |
| **purpose** | the `Supported → Accepted` transition **under AcceptancePolicy** — the canon's *Determination* |
| **input state** | `K` containing `i` with `status(i) = Supported` |
| **preconditions** | `i ∈ K` · `status(i) = Supported` **exactly** `RATIFIED-SOURCED` (I-12) · **an in-force AcceptancePolicy exists** `RATIFIED-SOURCED` |
| **state transition** | `status(i) := Accepted` |
| **postconditions** | `status(i) = Accepted` · `committed(i)` unchanged (**false**) · the policy record unchanged |
| **invariant obligations** | (open register) **I-12** · **A6** (no route to `committed`) · **I-11** (does not touch the in-force policy, only reads it) |
| **evidence effect** | **none.** ⚠ NDR: *what does the AcceptancePolicy read?* The canon states the policy determines *"what may be admitted as accepted knowledge (≠ what evidence supports)"* and **defines no predicate**. `COMPUTABILITY-MATRIX` on the sibling case: *"the function is defined; its parameter is not."* |
| **authority effect** | **none** — this is the evidence/policy side of the A6 boundary, by construction |
| **replay semantics** | deterministic; **idempotent** (a second application is refused by the precondition) |
| **failure semantics** | `REJECTED(legality)` not at `Supported` · `REJECTED(policy)` no in-force policy · `REJECTED(structural)` absent item. ⚠ NDR: §1.2 |
| **provenance** | `Determine`: **DERIVED-by-forcing-table** (`oderive`'s hand-built `FORCES`; see `OPERATION-REGISTRY-DERIVATION.md` §0) + the ratified *Determination* concept row. `Accept`: **CORPUS-NAMED**, `Accept: Assessment × Authority × Policy → Decision`, self-marked `UNRESOLVED` — note that signature makes it a **decision producer**, not a state transition, which is a **third** reading of the same capability |

---

### C-03 · `Authorize` — record an authority act

| field | value |
|---|---|
| **name** | `Authorize` — `Actor × Action × Policy → Decision` (259.7), `Authorize(a,o,π)` (272A `O_G`), `Authorize(α,π,t,K) → {true,false}` (277 §277.25) |
| **purpose** | record that authority has been exercised over a target. **The most load-bearing operation in the test: the only operation appearing in the witness of three separate capabilities (`A3`, `A4`, `A10`)** `EXECUTED` |
| **input state** | any `K` |
| **preconditions** | **⚠ NDR — the field the canon most conspicuously leaves empty.** v0.1: *"Authorization — precondition constraint filled via governance, **never a processing step**"* (the single occurrence of the word "precondition" in the whole governed surface). **Who may authorize, over what scope, and on whose warrant, is undefined**; `NEXT-FOUNDATIONAL-GAP-AUDIT` G-P1 records the same hole for policy. The model's precondition is **`true`**, which is certainly wrong as canon and is marked so. |
| **state transition** | `auth_acts := auth_acts ∪ {(actor, target)}` |
| **postconditions** | the act is recorded · **no item's status changes** · **no item becomes committed** `EXECUTED` |
| **invariant obligations** | (open register) **A6/I-4** — `Authorize` records authority and does **not** itself commit; the separation is what makes A6 checkable · **I-2** — authorizing is not proposing · **I-3** `SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction` |
| **evidence effect** | **none — and this is the ratified core of A6.** `EXECUTED`: with 44 independent supporting evidence units present, no operation produced `Committed`; authority is not derivable from evidence |
| **authority effect** | **this operation *is* the authority effect.** ⚠ NDR: is the act **external input** (`K0` KA6: *"authority acts are external inputs"*) or a **derivable** state transition? The corpus's A6-inertness theorem is proven **from** the external-input assumption, so this cannot be settled inside the model |
| **replay semantics** | deterministic; **idempotent** (set semantics). ⚠ NDR: **is replaying an authority act legitimate?** Replaying a recorded authorization re-confers authority without a fresh act — the corpus does not address this, and it is the sharpest replay question in the registry |
| **failure semantics** | in the model, **never refuses**. ⚠ NDR: it must refuse when the actor lacks authority — which requires the undefined precondition. The corpus's own verdict vocabulary for this (`{NoAuthority, AuthorityConflict, ScopeOrJurisdictionExceeded, PrecedenceResolved}`) is **one of four mutually unreconciled failure-code registries** |
| **provenance** | **CORPUS-NAMED + SIGNED** — 259.7, 272A `O_G`, 277 §277.25. Recorded as *"formal only — runtime absent"* |

---

### C-04 · `Commit` — cross the A6 boundary

| field | value |
|---|---|
| **name** | `Commit` — 250 §250.3 (`Commit = OPEN`); the **capability** is ratified via v0.2 R-3 (`Committed` re-typed a decision-boundary status) and A6 |
| **purpose** | attach `Committed` to an **Accepted** item **by an authority act** |
| **input state** | `K` containing `i` with `status(i) = Accepted`, and a recorded authority act on `i` |
| **preconditions** | `i ∈ K` · `status(i) = Accepted` **exactly** `RATIFIED-SOURCED` (A6: the boundary is crossed *from* Accepted) · **∃ an authority act targeting `i`** `RATIFIED-SOURCED` (A6/I-4) |
| **state transition** | `committed(i) := true`. `status(i)` is **unchanged** — `Committed` is a boundary status attached *to* an Accepted item, not a fourth rung `RATIFIED-SOURCED` (v0.2 R-3) |
| **postconditions** | `committed(i)` · `status(i) = Accepted` still · nothing else changes |
| **invariant obligations** | (open register) **A6/I-4** — the guard consults the authority log and **never** the evidence set; `EXECUTED`, 0 crossings under 44 evidence units · **I-12** — does not move on the ladder, so the covering relation is untouched · **I-3** |
| **evidence effect** | **none, and structurally none.** This is the one contract field the governed record already fixes: `OPERATION-CONTRACT-GAP` §B, *"canon: none — evidence cannot cross the boundary (A6)"* `RATIFIED-SOURCED` |
| **authority effect** | **consumes** an authority act. ⚠ NDR: **is the act consumed or merely required?** If it persists, one authorization commits unboundedly many items; if it is consumed, the log is not append-only. The canon does not say. |
| **replay semantics** | deterministic; **idempotent**. ⚠ NDR: inherits C-03's replay question — replay reproduces the commit only if the authority act is replayable |
| **failure semantics** | `REJECTED(legality)` not `Accepted` · `REJECTED(authority)` no authority act · `REJECTED(structural)` absent item. `EXECUTED`: the adversarial probe `CommitByEvidence` returns `REJECTED(authority)` on every argument |
| **provenance** | **RC — ratified-boundary-forced.** The *capability* is ratified; the *operation* is corpus-named only in 250, where its verdict is `OPEN`. **⚠ Adjacent finding:** the one corpus rule that states `Commit`'s conditions — step 025a-2 §36, five conjuncts, **no authority conjunct**, `SufficientSupport` evidence-derived — **crosses A6 when executed** (`OPERATION-REGISTRY-DERIVATION.md` §3.2a). **Recommended EXCLUDED, with that reason.** |

---

### C-05 · `Approve` — the governed, versioned approval decision

| field | value |
|---|---|
| **name** | `Approve` — `Candidate × Authority → ApprovedCandidate` (256.19), also 277/276 `O_G` |
| **purpose** | record the **governed approval decision** that I-11 requires before an in-force policy may change |
| **input state** | `K` containing a policy record `p`, with an authority act targeting `p` |
| **preconditions** | `p ∈ pols(K)` · **∃ an authority act targeting `p`** `RATIFIED-SOURCED` (I-11 requires the approval to be *governed*) |
| **state transition** | `approvals := approvals ∪ {(p.id, p.version)}` — the approval is **version-bound** `RATIFIED-SOURCED` (I-11: *"governed, **versioned** approval decision"*) |
| **postconditions** | the approval is recorded for exactly the version approved · **the policy itself is unchanged** — approving is not enacting |
| **invariant obligations** | (open register) **I-11** — this is half of the only legal route; the other half is C-06 · **I-2** |
| **evidence effect** | **none.** ⚠ NDR: does approval require evidence? The DC 6-tuple has an `Evidence` slot; whether a policy-change decision must fill it is undecided |
| **authority effect** | **requires** an authority act; **confers none** |
| **replay semantics** | deterministic; idempotent. ⚠ NDR: same question as C-03 |
| **failure semantics** | `REJECTED(structural)` no such policy · `REJECTED(authority)` no authority act. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED + SIGNED** (256.19). Its 256.19 signature is `Candidate × Authority → ApprovedCandidate` — over *candidates*, not policies; using it for the policy route is a **reading**, marked as such |

---

### C-06 · `EnactPolicyVersion` ≡ `ChangePolicy` — bring a new policy version into force

| field | value |
|---|---|
| **name** | `EnactPolicyVersion` (RC, from v0.2 §3's stratification loop) ≡ `ChangePolicy` (277/276 `O_G`) `EXECUTED` |
| **purpose** | move the in-force policy from version *n* to *n+1*. **The only legal route to a change of what governs admission** |
| **input state** | `K` with policy `p` at version *n*, and a recorded approval `(p.id, n)` |
| **preconditions** | `p ∈ pols(K)` · **`(p.id, p.version) ∈ approvals`** `RATIFIED-SOURCED` (I-11) |
| **state transition** | `p := Pol(p.id, n+1, in_force=true)` |
| **postconditions** | version *n+1* is in force · the approval that authorised it is on record · **⚠ NDR: is version *n* retained?** The executed runtime is append-only (*"never overwritten"*; supersession with *"old RETAINED"*), which the model does **not** reproduce — an unreconciled difference, reported |
| **invariant obligations** | (open register) **I-11** — guaranteed by construction: the guard reads the approval set · **v0.2 R-1** the policy-as-content / policy-in-force stratification |
| **evidence effect** | **none** |
| **authority effect** | **none directly** — authority enters through C-05's approval. This two-step split (`Authorize → Approve → Enact`) is what makes I-11's *"governed, versioned"* checkable; a one-step `ChangePolicy` cannot be checked, which is why the corpus's own `ChangePolicy` — **no signature, no authority guard, no runtime** — is unusable as stated |
| **replay semantics** | deterministic; **not idempotent** (each application increments). ⚠ NDR: replaying policy enactment re-applies a governance act |
| **failure semantics** | `REJECTED(structural)` no such policy · `REJECTED(authority)` no matching approval. `EXECUTED`: the adversarial probe `RevisePolicyDirect` returns `REJECTED(authority)`; operations that changed the in-force record off-route: **0** |
| **provenance** | `EnactPolicyVersion`: **RC — ratified-flow-forced**. `ChangePolicy`: **CORPUS-NAMED** (277/276), classed governance-**external**, no signature |

> **⚠ NDR — the binding this contract cannot supply.** The ratified text never states whether an
> in-force policy version **references a content item in `K_t`**, and if so whether that item is
> frozen while the version is in force. Executed: `Assess`, `Reject` and `Withdraw` all mutate
> policy-as-content while a same-id policy is in force — legal under R-1 read literally, yet if
> the two are bound this silently changes what governs admission. **Adjacent to GC-1, and not
> GC-1**: it does not ask which policy-loop termination stands. The ratified termination (I-11 +
> R-1) is the one applied here, per `ES-005.4`; GC-1 is untouched.

---

### C-07 · `Assess` — recompute the epistemic grade from linked evidence

| field | value |
|---|---|
| **name** | `Assess` — `K × X → Assessment` (259.7, 250), `Evidence × Context → Σ` (272A §272A.21) |
| **purpose** | compute an item's epistemic standing from its linked evidence, honouring **I-5** and **I-6**. Together with C-08, the **only** realizer of `A5` `EXECUTED` |
| **input state** | `K` containing `i` with a set of linked evidence ids, all resolvable in `ev(K)` |
| **preconditions** | `i ∈ K` · every linked evidence id resolves in `ev(K)` |
| **state transition** | `grade(i) := { source(u) : u ∈ resolved(linked(i)), polarity(u) = supports }` where `resolved` **excludes** any unit whose dependency is unresolved `RATIFIED-SOURCED` (I-6: resolution precedes aggregation) |
| **postconditions** | `grade(i)` is a set of **independent source classes** · adding a duplicate leaves it unchanged (**I-5**) · adding an independent unit strictly enlarges it (**I-5**) · a unit with an unresolved dependency is absent from it (**I-6**) · **no status changes** |
| **invariant obligations** | (open register) **I-5** and **I-6** — the **only TESTED invariant pair**, and here **EXECUTED** again: `{e1}→['s1']`; `+e1dup→['s1']`; `+e3→['s1','s3']`; `+e4dep→['s1','s3']` · **`Validate ≢ Transform`** (256.32) — but note `Assess` **does** write to `K` in this contract, which contradicts `KNOWLEDGE-STATE-ALGEBRA` §3's *"`validate` and `assess` are the only two operations that do not change `K`"*. **⚠ NDR: is the grade STORED in `K` or RECOMPUTED ON READ?** `TRANSFORMATION-CANONICAL-MODEL` §2 says recomputed (*"`Σ` recomputed on read"*); step 232 §232.9 stores it (`σ` is a component of `𝔎`). **The two positions are incompatible and both are in the corpus.** |
| **evidence effect** | **reads** evidence; **writes none.** **No aggregation operator was selected** — the grade is a *set*, so I-5/I-6 hold structurally and **OQ-3 stays open by ruling** `EXECUTED` |
| **authority effect** | **none** |
| **replay semantics** | deterministic; **idempotent** |
| **failure semantics** | `REJECTED(structural)` absent item or dangling evidence id. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED + SIGNED.** ⚠ **Σ-dependence, surfaced not relied on:** 272A gives `Assess`'s codomain as **Σ**, and Σ is **not ratified and not agreed** — 272B derives four values, `DECISION-SIGMA-EPISTEMIC-STATUS` derives three, 275 derives `(D,S)`, Q14's five-dimensional form was **REFUTED**, and `handoff/03` records the author's code returning four while the author's prose said three. This contract **avoids Σ** by using a set of source classes. `Assess` is also one of exactly three operations the corpus records as needing Σ's **grades** (with `Qualify` and `Compare`). |

---

### C-08 · `LinkEvidence` ≡ `Support` — attach an evidence unit to an item

| field | value |
|---|---|
| **name** | `LinkEvidence` (277 `𝒯_candidate`, 276 `O_E`) ≡ `Support` (272A `O_E`, 277) `EXECUTED` |
| **purpose** | put an evidence unit into an item's evidence set, so C-07 can compose it |
| **input state** | `K` containing `i` and evidence unit `e` |
| **preconditions** | `i ∈ K` · `e ∈ ev(K)` · for `Support`, `polarity(e) = supports` |
| **state transition** | `linked(i) := linked(i) ∪ {e}`; `grade(i) := ⊥` (invalidated, to be recomputed by C-07) |
| **postconditions** | `e ∈ linked(i)` · `grade(i)` is unset · **no status changes** · **no item becomes committed** |
| **invariant obligations** | (open register) **A6** — attaching evidence can never commit; `EXECUTED` · **I-6** — attaching does not aggregate; that is C-07's job, and the separation is what makes *"dependency resolution precedes aggregation"* enforceable rather than aspirational |
| **evidence effect** | **this operation is the evidence effect** |
| **authority effect** | **none** |
| **replay semantics** | deterministic; **idempotent** (set semantics) |
| **failure semantics** | `REJECTED(structural)` absent item or unknown unit. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED, and one of the two named UNRECONCILED operations.** `handoff/02`: *"`LinkEvidence` would **MUTATE an assertion**, contradicting `ReplayAssertion`'s immutability invariant. Unreconciled."* **⚠ NDR — T-3 reconciliation, named not made.** Three routes: (a) evidence is a **field fixed at assertion creation**, so `LinkEvidence` is excluded and new evidence produces a **new** assertion plus a relation (the executed algebra's position); (b) evidence is a **mutable field**, so the immutability invariant is amended; (c) evidence links live in **ℛ**, not in the assertion, so nothing is mutated and `Support ⊆ P × E` becomes a relation, as 277 §277.14 in fact proposes. **Route (c) is the only one that satisfies both sources, and choosing it is still a normative act** — and it would remove `LinkEvidence` from the state operations, changing `A5`'s witness. The consequence for minimality is reported in `MINIMALITY-RESULT.md` §5. |

---

### C-09 · `Qualify` — turn a source observation into evidence

| field | value |
|---|---|
| **name** | `Qualify` — `Qualify(o,c,π) → e` (272A `O_E`), `Qualification: O × C × Π ⇀ E ∪ {⊥}` (277 §277.15) |
| **purpose** | the `Observation → Evidence` transition. With C-10, the **only** realizer of `A7` |
| **input state** | `K` containing a source observation `o` |
| **preconditions** | `o ∈ obs(K)` · **⚠ NDR: the qualification predicate.** The canon defines none; `THEORY-GAP-REGISTER` TG-14 is blunt — *"`Qualify` has **NO BODY**"*, BLOCKING. The model's predicate (an observation qualifies to the evidence unit sharing its source class) is a **placeholder** and is marked so |
| **state transition** | `ev := ev ∪ {qualified units}`; `qualified := qualified ∪ {(o, e)}` — the qualification **act** is recorded separately from its product, so `SourceObs ≠ SemanticObs` remains observable in the state `RATIFIED-SOURCED` |
| **postconditions** | an evidence unit exists · the act linking it to its observation is on record · the observation is **not** consumed |
| **invariant obligations** | (open register) `Observation ≠ Evidence` and `SourceObservation ≠ SemanticObservation` — the non-collapse distinctions this operation exists to make traversable · **I-6** — the produced unit carries its dependency structure |
| **evidence effect** | **produces** evidence. It is the only operation in the contracted set that does so from an observation |
| **authority effect** | **⚠ NDR: none, or policy-bound?** 277 §277.3 marks `Qualify` *"Requires policy: **Yes**"* and its core-candidacy *"Conditional"*; 272A marks it *"REQUIRED / POLICY-DEPENDENT"*. So the corpus says the qualification predicate is **policy-parametric** while leaving `Policy` formally undefined — `FINAL-THEORY-GAP-REGISTER` G-2, **BLOCKING** |
| **replay semantics** | deterministic **given a fixed policy**; **idempotent**. ⚠ NDR: replay under a *different* in-force policy version yields different evidence — and nothing records which version was in force |
| **failure semantics** | `REJECTED(structural)` unknown observation · `REJECTED(policy)` the source does not qualify (277's `⊥` branch). ⚠ NDR: §1.2 |
| **provenance** | **DERIVED-by-forcing-table** (`oderive`) + **CORPUS-NAMED** (272A/277/276). One of three operations the corpus records as needing Σ's grades. **Forced by a law repeated 20 times, and absent from both §256.2 and §259.7.** |

---

### C-10 · `Observe` — record a source observation

| field | value |
|---|---|
| **name** | `Observe` — 250 §250.5, `Observe: World × Context → Observation`, verdict *"`Observe` = upstream epistemic operation"*, status `PLAUSIBLE / NEEDS CORPUS CLOSURE` |
| **purpose** | bring a source observation into the state, and thereby **recognise its dimension**. Realizer of `A7` (with C-09) and one of two realizers of `A11` |
| **input state** | any `K` |
| **preconditions** | none in the model. ⚠ NDR: 250 explicitly declines to place `Observe` in `𝒯_K` — *"`Observe` should not automatically be classified as a Knowledge-State transformation"*. **Is observing an operation on `K`, or an event upstream of it?** The ratified flow shows *partial observation* crossing the epistemic boundary and does not settle the typing |
| **state transition** | `obs := obs ∪ {o}`; `dims := dims ∪ {dim(o)}` |
| **postconditions** | the observation is on record · its dimension is recognised · **no item is created, and no value is asserted** — which is exactly what keeps `UNKNOWN` distinct from `ABSENT` `RATIFIED-SOURCED` |
| **invariant obligations** | (open register) **I-7** `X_t ≠ Observed(X_t)` — the operation records an observation, never the world · **Art. 9 / I-9** `UNKNOWN ≠ ABSENT ≠ FALSE` |
| **evidence effect** | **none** — an observation is not evidence until C-09. The separation is the ratified `SourceObs ≠ SemanticObs` |
| **authority effect** | **none** |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(structural)` unknown observation identifier. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED** (250) + **RC** (the ratified flow's *partial observation*). Named by **one** source in the live universe |

---

### C-11 · `RecognizeDimension` — recognise a dimension without asserting a value

| field | value |
|---|---|
| **name** | `RecognizeDimension` — **not corpus-named as an operation.** Derived from the **forced structure** `⟨D_t⟩` (`02-OPERATION-UNIVERSE.md` §3, forced by `UNKNOWN ≠ ABSENT`) |
| **purpose** | make `A11` reachable: a dimension is in `D_t` while its value is unknown, distinguishably from the dimension being absent |
| **input state** | any `K` |
| **preconditions** | none |
| **state transition** | `dims := dims ∪ {d}` |
| **postconditions** | `d ∈ dims` · **no item exists on `d`** — so `UNKNOWN(d)` and `ABSENT(d)` are different states `EXECUTED` |
| **invariant obligations** | (open register) **Art. 9 / I-9** · **I-9**'s prohibition on Boolean collapse |
| **evidence effect** | none | 
| **authority effect** | none |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(structural)` unknown dimension. ⚠ NDR: §1.2 |
| **provenance** | **PROPOSED.** Under C-7 (*"a member whose source class cannot be named is a proposal, not a member"*) this is a **proposal, not a member**: its source is a forced *structure*, and `02-OPERATION-UNIVERSE.md` says so explicitly — *"not an operation — a STRUCTURE"*. **⚠ NDR: does the registry need an operation that populates `D_t`, or is `D_t` populated only as a side effect of `Observe`?** The executed result is that `Observe` alone suffices for `A11`, so **this proposal is eliminable** — reported in `MINIMALITY-RESULT.md` §4. **A further registered defect the contract must carry:** `Q_t` *"collapses Zero-A′ (recognised dimension, unasked) with Zero-B (dimension not conceived)"*, while the corpus boxes `UnknownValue(D) ≠ UnknownDimension(D)` as a distinction that *"must never collapse"*. |

---

### C-12 · `Reject` — record inadmissibility without deletion

| field | value |
|---|---|
| **name** | `Reject` — `K × X × Reason → K'`, `Status(x): Proposed → Rejected` (256.11); also 259.7, 277/276 `O_G`, 249, 250, 232 |
| **purpose** | reach the ratified `REJECTED` state. **The ONLY realizer of `A8` in the entire candidate universe** `EXECUTED` |
| **input state** | `K` containing `i` |
| **preconditions** | `i ∈ K` · `¬committed(i)` `PROPOSED` · **⚠ NDR — and this one is load-bearing: `status(i)` must be constrained.** As 256.11 states it there is **no guard on the source status**, and the executed check found the consequence: `Conflicted → Rejected` **with no governed act**, violating **I-12** and **Art. 8** |
| **state transition** | `status(i) := Rejected`. The item is **retained** |
| **postconditions** | `status(i) = Rejected` · `i ∈ K` still — *"terminal, never deleted"* `RATIFIED-SOURCED` (Art. 7) · `EXECUTED`: 0 Art. 7 violations |
| **invariant obligations** | (open register) **Art. 7** — satisfied · **`Reject ≠ Remove`** (256.11) — satisfied · **I-12** — **VIOLATED** from `Conflicted` · **Art. 8** — **VIOLATED** from `Conflicted` |
| **evidence effect** | none |
| **authority effect** | **⚠ NDR — the four-way reading.** 256.11 makes `Reject` an **epistemic disposition** (no authority); 277 §277.3 and 276 `O_G` make it a **governance transition, External** (authority required); 250 §250.9 makes it `Reject: Assessment × Authority × Policy → Decision`, a **decision producer**; 249 §249.13 records it as *"listed but effectively abandoned."* **The readings differ in whether Art. 8 binds it — and that is exactly the violation above.** |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(structural)` absent item · `REJECTED(authority)` already committed. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED** by seven sources — the best-corroborated operation in the contracted set — **and it is the one whose ratified-consistency check fails.** Corroboration is not correctness. |

> **⚠ NDR of the first order.** `A8` (reach `REJECTED`, item preserved) is **RATIFIED-FORCED**, and
> its **only** realizer in 57 candidate names is an operation that **violates two ratified
> constraints as specified**. Either `Reject` is re-specified with a source-status guard — a
> normative act — or `A8` has **no consistent realizer**. This is not resolved here.
> Consequence for the registry: reported in `MINIMALITY-RESULT.md` §5.

---

### C-13 · `MarkConflict` — governed suspension

| field | value |
|---|---|
| **name** | `MarkConflict` — **not corpus-named.** RC, from FA-1 §2 / D-FA-1's *"CONFLICTED (governed suspension)"* |
| **purpose** | reach the ratified `CONFLICTED` state **by a governed act**, as distinct from reaching it by detected contradiction (C-14) |
| **input state** | `K` containing `i` with `status(i) ∈ LADDER`, and an authority act on `i` |
| **preconditions** | `i ∈ K` · `status(i) ∈ LADDER` (reachable from any pre-boundary status) `RATIFIED-SOURCED` · **∃ authority act on `i`** `RATIFIED-SOURCED` (Art. 8: *governed* suspension) |
| **state transition** | `status(i) := Conflicted` |
| **postconditions** | `status(i) = Conflicted` · `i` retained · a governed act is on record |
| **invariant obligations** | (open register) **Art. 8** · **I-12** — FA-1 permits reaching a non-admission state from any pre-boundary rung, so this is not a skip |
| **evidence effect** | none |
| **authority effect** | **requires** an authority act |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(legality)` off-ladder · `REJECTED(authority)` no governed act · `REJECTED(structural)` absent item |
| **provenance** | **RC — ratified-state-forced.** ⚠ NDR: **does the registry need a governed route to CONFLICTED at all, or is CONFLICTED reachable only by detection?** FA-1 calls it a *governed suspension*, which reads as a governed act; the corpus supplies only the detection route (C-14). Executed: this operation is **eliminable** for `A9` (four other witnesses exist) but is **required together with `Resolve`** for the A1 bypass reported in `MINIMALITY-RESULT.md` §3. |

---

### C-14 · `DetectContradiction` — reach CONFLICTED from evidence or relation

| field | value |
|---|---|
| **name** | `DetectContradiction` — `Contradicts(a,b,K) → {true,false}` (272A §272A.8); `contradicts` in the executed 9-op harness |
| **purpose** | reach `CONFLICTED` when the state itself carries an incompatibility |
| **input state** | `K` containing `i` with both supporting and refuting linked evidence, **or** an incident `contradicts` relation |
| **preconditions** | `i ∈ K` · `status(i) ∈ LADDER` · (∃ supporting **and** ∃ refuting linked unit) **or** ∃ an incident `contradicts` edge |
| **state transition** | `status(i) := Conflicted` |
| **postconditions** | as C-13, without the authority record |
| **invariant obligations** | (open register) **Art. 8** · **I-12** · **`Contradiction ≠ EpistemicStatus`** (272A §272A.8) — **⚠ this contract is in tension with that law**: it writes contradiction **into** the status field. 272A's own position is that `DetectContradiction` *"does not imply `Conflict ∈ Σ` as a primitive field."* FA-1, however, **ratifies `CONFLICTED` as a state**. The tension is between a research law and a ratified decision; the ratified one is applied, and the tension is reported. |
| **evidence effect** | **reads** evidence and relations; writes none |
| **authority effect** | **none — and that is the objection to it.** Art. 8 calls CONFLICTED a *governed* suspension; a purely detection-driven route reaches it **with no governed act**. ⚠ NDR: **may CONFLICTED be entered without governance?** |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(legality)` off-ladder · `REJECTED(evidence)` no contradiction present · `REJECTED(structural)` absent item |
| **provenance** | **CORPUS-NAMED** by 272A only (n=1 in the live universe), plus the executed harness under the name `contradicts`. Registered defect it must carry: *"an operation whose subject matter the state cannot describe"* — `ℛ` is a three-field triple, so *"A₁ contradicts A₂"* **cannot be evidenced, dated, superseded or contested**. And separately: `Σ₀ ⊥ ℛ` — two assertions in an explicit `contradicts` edge both read `Supported`. |

---

### C-15 · `Resolve` — the governed exit from CONFLICTED

| field | value |
|---|---|
| **name** | `Resolve` — 272A `O_E`, *"unresolved epistemic problem can be resolved"*. **No signature anywhere.** |
| **purpose** | leave `CONFLICTED` by a governed act. **The only realizer of `A10`** `EXECUTED` |
| **input state** | `K` containing `i` with `status(i) = Conflicted`, and an authority act on `i` |
| **preconditions** | `status(i) = Conflicted` `RATIFIED-SOURCED` · **∃ authority act on `i`** `RATIFIED-SOURCED` (Art. 8) |
| **state transition** | `status(i) := ` **⚠ NDR — WHICH RUNG?** The model returns `Supported`. **FA-1 does not state the return rung of a governed resolution**, and the choice is consequential: returning to `Supported` creates an **I-12 bypass** — `Candidate → Conflicted → Supported` reaches `Supported` without traversing the covering relation. Executed and reported in `MINIMALITY-RESULT.md` §3. The candidate answers — return to the rung held before suspension · return to `Candidate` · return to a rung the resolving authority names · make resolution terminal like `REJECTED` — are **not interchangeable**. |
| **postconditions** | `status(i) ∉ {Conflicted}` · `i` retained · a governed act is on record |
| **invariant obligations** | (open register) **Art. 8** — satisfied · **I-12** — **conditionally violated**, depending on the ⚠ NDR above |
| **evidence effect** | none in the model. ⚠ NDR: does resolution require the conflict's evidential basis to change? |
| **authority effect** | **requires** an authority act |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(legality)` not `Conflicted` · `REJECTED(authority)` no governed act |
| **provenance** | **CORPUS-NAMED by one source, with no definition.** And the corpus separately records that **adjudication of `CONFLICTED` is ABSENT — *"no signature at all"*, re-confirmed three times: there is no corpus operation that exits the state.** So `A10`, a ratified capability, has **no corpus operation to realise it**, and this block is a contract for a capability rather than for an operation. Also registered: `Resolve` carries *"two outcome vocabularies in one file, mapping undefined"*, and 272A itself lists *"`Resolve` minimality"* as open, speculating `Resolve = Assess + Decide + Update`. |

---

### C-16 · `ComputeZero` — the typed gap

| field | value |
|---|---|
| **name** | `ComputeZero` — **not corpus-named as an operation.** RC, from the ratified object `Zero(K, EC)` with `EC = η(G, IdealState)` (v0.2 R-2) |
| **purpose** | compute the discrepancy between state and requirement, **retaining its type**. The **only** realizer of `A6c` |
| **input state** | any `K`, plus `EC` |
| **preconditions** | `EC` is available. ⚠ NDR: `EC = η(G, IdealState)` and **η is unsynthesised** — `OQ-1` is open by ruling (η-totality / G-residual). So this operation's second argument is a **ratified signature with an unsynthesised body** |
| **state transition** | `zero := zero ∪ {(EC, t)}` with `t ∈ {unknown, conflicting, missing, invalid, satisfied}` |
| **postconditions** | a gap record exists · its value is drawn from the four-way typology, **never Boolean** `RATIFIED-SOURCED` (I-9) · `EXECUTED`: 0 I-9 violations |
| **invariant obligations** | (open register) **I-9** — *"Zero's four-way non-satisfaction typology must not collapse to Boolean"* — the single obligation this operation exists to discharge |
| **evidence effect** | **reads** state; writes none |
| **authority effect** | **none.** The Zero function is on the computational side of the ratified boundary |
| **replay semantics** | deterministic **given `EC`**; idempotent. ⚠ NDR: `EC` derives from the Knower's goal, which may change; replay then needs the `EC` in force at the time, and nothing records it |
| **failure semantics** | **never refuses in the model.** ⚠ NDR: what is the gap type when `EC` itself is unavailable? A fifth type, or a rejection? |
| **provenance** | **RC — ratified-object-forced.** The *function* is ratified; **no enumeration names an operation for it.** |

---

### C-17 · `Propose` — the authority-free selector

| field | value |
|---|---|
| **name** | `Propose` — the ratified flow's `Proposal (selector, no authority)` node (025g; naming RESERVED per GN-04) |
| **purpose** | record the next epistemic action **without conferring authority**. The only realizer of `A12` |
| **input state** | `K` with a computed gap |
| **preconditions** | `zero(K) ≠ ∅` `PROPOSED` — the flow puts Proposal downstream of Zero. ⚠ NDR: is a proposal admissible with no computed gap? |
| **state transition** | `proposals := proposals ∪ {(action, ⊥)}` — the authority slot is **⊥ by construction** `RATIFIED-SOURCED` (I-2) |
| **postconditions** | a proposal exists · its authority is **absent** · **no decision exists** — `proposal ≠ decision` remains observable `RATIFIED-SOURCED` · `EXECUTED`: 0 I-2 violations |
| **invariant obligations** | (open register) **I-2** — *"the selector holds no authority"* — discharged structurally, the field cannot be filled · **`proposal ≠ decision`** · **I-3** |
| **evidence effect** | none |
| **authority effect** | **none, and structurally none** |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(structural)` no gap computed. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED as an OBJECT, not as an operation** (025g; v0.2 §3 flow node). The naming is **RESERVED** by GN-04, so even the name is not free. |

---

### C-18 · `Decide` — admissibility against the DC 6-tuple

| field | value |
|---|---|
| **name** | `Decide` — the ratified object `DC(d) = (Pre, Inv, Auth, Post, Temporal, Evidence)` (042) |
| **purpose** | record a decision as admissible only if the decision contract is satisfied. The only realizer of `A13` |
| **input state** | `K` with the six slots satisfiable |
| **preconditions** | **⚠ NDR — the whole field.** The canon supplies the **six slot names** and **no conjunction over them**: `OPERATION-CONTRACT-GAP` C-8 records that *"no ratified conjunction over exactly those six exists"* and that layer-2 candidates have **mismatched arity**. The model's conjunction (`Pre` = a proposal exists · `Inv` = a passing validation · `Auth` = an authority act · `Post` = a target gap state · `Temporal` = a non-empty history · `Evidence` = a computed grade) is **PROPOSED and is mine**. |
| **state transition** | `decisions := decisions ∪ {(d, admissible)}` |
| **postconditions** | the decision is on record · **no item's status changes** — deciding is not admitting `RATIFIED-SOURCED` (I-3) |
| **invariant obligations** | (open register) **I-3** `SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction` · **`decision ≠ authorization`** — the `Auth` slot is *filled from* governance, never *produced* here `RATIFIED-SOURCED` (v0.1: *"a precondition constraint filled via governance, never a processing step"*) · **I-2** |
| **evidence effect** | **reads** the `Evidence` slot; writes none |
| **authority effect** | **requires** `Auth` to be filled; **confers none** |
| **replay semantics** | deterministic; idempotent. ⚠ NDR: the `Temporal` slot makes replay-time and decision-time differ, and nothing states which governs |
| **failure semantics** | `REJECTED(policy)` any slot unfilled. **⚠ NDR: the six kinds of refusal.** A single rejection kind for six distinct slot failures is precisely the conflation `TRANSFORMATION-THEORY` warns against, and it needs a **six-way** vocabulary that no corpus register supplies — the corpus has **four mutually unreconciled failure-code registries** |
| **provenance** | **RC — ratified-object-forced; conjunction PROPOSED.** |

---

### C-19 · `Act` — authorized action produces a new observation

| field | value |
|---|---|
| **name** | `Act` — v0.2 §3's action loop (`025z`, `056`) |
| **purpose** | close the loop: an authorized decision yields a new observation. Only realizer of `A14` |
| **input state** | `K` with an admissible decision |
| **preconditions** | `decisions(K) ≠ ∅`. ⚠ NDR: must the decision also be *authorized*, distinctly from admissible? `authorization ≠ execution` is a ratified non-collapse distinction, and this contract does not honour it — it is the model's simplification |
| **state transition** | `obs := obs ∪ {o}` with `caused_by(o) = d` |
| **postconditions** | a new observation exists, tagged with its cause |
| **invariant obligations** | (open register) **`authorization ≠ execution`** — **NOT DISCHARGED**, see preconditions · **I-7** `X_t ≠ Observed(X_t)` — the action's result reaches the state only as an observation |
| **evidence effect** | produces an **observation**, not evidence. C-09 is still required |
| **authority effect** | consumes an authorized decision. ⚠ NDR: consumed or persistent? |
| **replay semantics** | ⚠ NDR: **replaying an action re-executes it in the world.** This is the sharpest safety question in the registry, and no corpus document addresses it |
| **failure semantics** | `REJECTED(authority)` no decision |
| **provenance** | **CORPUS-NAMED**, and **the far side is OPEN BY RULING — `OQ-4`.** Capability graded **PROPOSED** in `OPERATION-REGISTRY-DERIVATION.md` §2.2 for that reason. This contract may not be treated as filling OQ-4. |

---

### C-20 · `Replay` — fold history back to state

| field | value |
|---|---|
| **name** | `Replay` — `Replay(K₀,H,t) → K_t` (272A `O_H`), `Replay(K₀,H) = fold(T,K₀,H)` (277 §277.16), `Replay: ℍ × K₀ → K_n` (250, 249) |
| **purpose** | reconstruct state from history. Only realizer of `A15` |
| **input state** | a history `H` and an initial state |
| **preconditions** | `H` is well-ordered (`KNOWLEDGE-STATE-ALGEBRA` §2). ⚠ NDR: **no document defines the ordering**, whether it is total, or how the concurrent branches `merge: 𝕂 × 𝕂 ⇀ 𝕂` implies are linearised |
| **state transition** | recompute; the model records only the outcome |
| **postconditions** | **⚠ NDR — the whole field.** *"Replay reproduces the state"* is an **equation between states**, and state equality is undefined (§1.3). This postcondition is **undecidable until that is settled** — precisely `TRANSFORMATION-CONTRACT-GAP` §C-2 |
| **invariant obligations** | (open register) `State ≠ History` · historical monotonicity (232 §232.26) · *"HistoricalState is not silently destroyed"* (232 Law 5) |
| **evidence effect** | none directly |
| **authority effect** | ⚠ NDR: `KNOWLEDGE-STATE-ALGEBRA` §2 marks `Auth` and `Gov` for `replay` as *"replays"* — i.e. it re-applies recorded authority. **Whether replay re-confers authority, or must be re-authorized, is undecided**, and it is the same question as C-03's and C-19's |
| **replay semantics** | **EXECUTED, and negative: `replay` is deterministic and ORDER-DEPENDENT** (executed counterexample in the corpus). Also: **no document identifies which pairs of operations fail to commute** — and that pairwise commutation table is exactly what a replay-determinism guarantee needs |
| **failure semantics** | ⚠ NDR: **replay cannot reproduce a history containing refusals** unless rejections are recorded — §1.2(c), and the corpus's two positions on it are unreconciled |
| **provenance** | **CORPUS-NAMED** by seven sources, **no signature in 259.7**, and its status is **disputed three ways**: REQUIRED (272A) · **derived fold** (277) · OPEN, equality-dependent (249). **The capability is graded PROPOSED** — `State ≠ History` is a corpus law, **not** in the ratified surface. If 277's derivation `Replay = fold(T, K₀, H)` is adopted, `Replay ∉ 𝒯_primitive` and `A15` needs no member at all. |

---

### C-21 · `Assert` ≡ `Add` ≡ `Create` — admit an item at the first rung

| field | value |
|---|---|
| **name** | `Assert` (272A `O_S`, 277) ≡ `Add` (256.2, 232.6, 249, 250) ≡ `Create` (250 §250.4) `EXECUTED` |
| **purpose** | bring a proposition into `K_t` at the **first** rung. Appears in an `A9` witness; **not necessary for any capability** in the executed result |
| **input state** | `K` with the item's dimension recognised, and no item of that identity present |
| **preconditions** | `dim ∈ dims(K)` — no assertion into an unrecognised dimension `PROPOSED` · the identity is unused. ⚠ NDR: 256.4 boxes *"`Add` requires a definition of identity/equality"* — **so this operation's precondition is blocked on §1.3** |
| **state transition** | a new item at `status = Candidate`, `committed = false`, no evidence, no grade |
| **postconditions** | the item exists at `Candidate` — **never above it** `RATIFIED-SOURCED` (I-12) · `EXECUTED`: 0 violations for this class |
| **invariant obligations** | (open register) **I-12** — entry only at the first rung · **A6** — cannot create a committed item · **`Proposition ≠ Assertion`** — asserting is an act distinct from the proposition |
| **evidence effect** | none |
| **authority effect** | none |
| **replay semantics** | deterministic; **idempotent** — set semantics, *"re-executing a history is safe"* (executed) |
| **failure semantics** | `REJECTED(structural)` unrecognised dimension, or identity already in use. ⚠ NDR: §1.2 |
| **provenance** | **CORPUS-NAMED** by five sources under three names, and `249` marks `Add` **ESTABLISHED** while `250` marks `Create` merely `PLAUSIBLE` and explicitly warns `Create ≠ necessarily Add`. **The three names collapse to one effect in this model; whether they collapse in the theory is a ⚠ NDR** — and note `algD`'s `create` is a *fourth* thing entirely (produce the empty state). |

---

### C-22 · `Infer` — derive a new item from existing ones

| field | value |
|---|---|
| **name** | `Infer` — `Infer(K,p₁,…,pₙ) → p_new` (272A §272A.4.4); `Inference: Evidence × Context → Assertion` (250 §250.6) |
| **purpose** | produce a derived item **plus its lineage edge**. Appears in an `A9` witness; **not necessary** |
| **input state** | `K` containing a source item |
| **preconditions** | the source item exists; the target identity is unused |
| **state transition** | a new item at `Candidate`, plus a `derived_from` edge |
| **postconditions** | the derived item exists **at the first rung** `RATIFIED-SOURCED` (I-12) · its lineage edge exists |
| **invariant obligations** | (open register) **I-12** · **`Provenance ≠ Lineage`** — the edge records derivation, not provenance |
| **evidence effect** | none. ⚠ NDR: does an inferred item inherit its premises' evidence, or does it get its own? |
| **authority effect** | none |
| **replay semantics** | deterministic; idempotent |
| **failure semantics** | `REJECTED(structural)` |
| **provenance** | **CORPUS-NAMED** by 272A (core) and 250 (`UNRESOLVED`), **absent from 277 entirely** — one of the three-way disagreements. 250 refuses the typing outright: *"`Infer` should not yet be assigned `K → K`."* |

---

### C-23 · `Relate` and `Refute` — the two remaining witness members

| field | `Relate` | `Refute` |
|---|---|---|
| **name** | `Relate` — the executed algebra's edge constructor (`algD`, `algE`, `handoff/02`) | `Refute` — 272A `O_E`, 277 §277.14 |
| **purpose** | add an `ℛ` edge of a given type. Appears in three `A9` witnesses | attach a **refuting** evidence unit, enabling C-14 |
| **input state** | `K` containing both endpoints | `K` containing the item |
| **preconditions** | both endpoints present · **acyclicity for the DAG families** `RATIFIED-SOURCED`-adjacent (executed: *"acyclicity of `supersedes` is an INVARIANT of `K`, not an optional check"*) | the item exists |
| **state transition** | `rels := rels ∪ {(a,b,ty)}` | a refuting unit is added and linked; the grade is invalidated |
| **postconditions** | the edge exists; the DAG families stay acyclic | the refuting unit is linked |
| **invariant obligations** | (open register) acyclicity of `ℛ_sup`/`ℛ_ref`/`ℛ_der`; **`supports` is NOT transitive** — *"evidential support cannot be propagated along chains; any implementation that does so is unsound"*; **`contradicts` is a TOLERANCE relation, never an equivalence** — *"there are no contradiction classes"* | **I-5/I-6** — a refuting unit composes under the same laws as a supporting one |
| **evidence effect** | none | **produces and links** refuting evidence |
| **authority effect** | ⚠ NDR: `algD` marks `relate` **authority- and governance-dependent by edge type** (`supersedes` requires authority; `refines` does not). **A single `Relate` with type-dependent authority is a different operation from a family of typed edge constructors** — and `algD`'s own twelve list `supersede`, `refine` and `resolve` as separate operations while `algE` models all three as `relate(type=…)`. Unreconciled | none |
| **replay semantics** | deterministic; idempotent; **but the acyclicity guard makes it ORDER-DEPENDENT** — the same edge is admissible in one order and `REJECTED(structural): cycle` in another (executed). **This is the identified source of replay's order-dependence** | deterministic; idempotent |
| **failure semantics** | `REJECTED(structural)` dangling endpoint or cycle | `REJECTED(structural)` |
| **provenance** | **EXECUTED-LANE** — named in the executed algebra and by `handoff/02` as the general case 277's `Supersede` covers only partially. **C-7 note:** its source class is the executed lane, not a step enumeration | **CORPUS-NAMED** (272A, 277, 276). 277 §277.14 reassigns its state-changing content to `LinkEvidence`, treating `Refute ⊆ P × E` as a **relation** — so under 277 this operation may not be a state operation at all |

---

## 3 · What this file does not do

No operation is named as canon · no registry is proposed · no granularity is chosen · no
`⚠ NDR` is resolved · `GC-1` is untouched · `OQ-1…OQ-12` are unmoved, and `OQ-3` (aggregation
operator) and `OQ-4` (action semantics) are explicitly left open · Σ is not defined and is used
nowhere in the state core · `Q_t` is not defined · identity, equality and replay are used only
model-locally and are marked so · no ratified artifact was modified · nothing here is
**RATIFIED**, and nothing here is described as "validated."
