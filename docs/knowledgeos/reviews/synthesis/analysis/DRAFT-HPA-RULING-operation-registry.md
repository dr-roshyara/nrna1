# DRAFT — HPA RULING: THE CANONICAL OPERATION REGISTRY

> ## ✅ STATUS: **SIGNED — OPTION 1 SELECTED (HPA, 2026-08-31) · RECORDED AS GN-79**
> The HPA selected **Option 1 — COMMISSION THE DERIVATION**. This instrument is no longer a draft;
> it is the record of an executed governance act. The commission it authorizes is issued as
> `analysis/COMMISSION-operation-registry-derivation.md`. Options 2, 3 and 4 were not selected.
> **The operation registry remains NOT ESTABLISHED; Operations and Transformations remain BLOCKED
> until a separate ratification act.**
>
> *(Original draft banner, retained unmodified for the record:)*
> ## ⚠ STATUS: **DRAFT INSTRUMENT · UNSIGNED · CREATES NO AUTHORITY**
> Prepared by the book-production lane at HPA request, 2026-08-31. **This document is not a
> ruling.** It becomes one only when the HPA selects an option and records it in
> `analysis/governance-notes.md`. Nothing here selects an operation set, defines semantics, or
> ratifies anything. Drafting an instrument is not exercising the authority it would carry.

---

## PART A — FINDINGS OF FACT (verified, not asserted)

The following were established by direct search of the **governed surface only** — v0.2
(AUTHORIZED), v0.1, FA-1…FA-9 (RATIFIED, GN-31), and the repository architecture corpus — with
every apparent hit inspected individually.

**A-1.** The ratified and authorized architecture **defines zero operations.** Operation
signatures across the governed corpus: 0. Six apparent name-hits were all false positives
(terminology uses of "merge"; "split" as a noun in the R-1 repair).

**A-2.** **No canonical pre/post-condition specification exists.** `postcondition` occurs zero
times; `precondition` occurs once, in v0.1's concept row describing *Authorization* as a
precondition constraint — not the precondition of any operation. All other pre/post-bearing
repository files carry their own banners: **PROPOSED · NON-AUTHORITATIVE · NOT ADOPTED.**

**A-3.** The canon ratifies **legality constraints over an undefined relation** — I-12 (the ladder
is a covering relation; skipping excluded) and A6 (the Accepted→Committed boundary is crossed by
an authority act, never by evidence). Both quantify over transitions that are never defined.

**A-4.** **Nine capabilities are canonically required; zero operations are canonically defined.**
Of 99 contract cells (9 capabilities × 11 properties), 2 are fully fixed by canon, 9 partially,
**88 empty**.

**A-5.** Candidate operation universes exist **outside** the governed surface, in the verification
lane. There are **at least three, and they do not agree** (a 19-operation universe; a 6-operation
candidate; a 14-forced/18-upper bound with a four-operation undetermined band). Two further
enumerations exist in the corpus with a different membership again.

**A-6.** **The membership question has never been tested.** The necessity criterion that would
discriminate the candidates is stated in the research record and, in that record's own words,
*"the test has never been run by anyone."*

**A-7.** The candidate's own most recent artifact states: *"`𝒪_core` is DECOMPOSED-CLOSED but NOT
MINIMAL — **it must NOT be frozen as-is**… freezing it now would freeze an explicitly unproven
minimality claim."*

**A-8.** **No governance act exists for any candidate.** A six-location search of the authority
record returned zero. Research artifacts carrying "Authority: HPA" (13×), "HPA Ruling" (9×) and
"RATIFIED" (2×) across steps 272–280 have **no corresponding entry in the governance ledger** —
recorded here as a provenance finding, not adjudicated.

**A-9.** Consequence for implementation: an engineer can build a state over the eight ratified
primitives with the twelve invariants as constraints, the policy stratification, the authority
boundary and the two TESTED evidence laws — and **cannot build anything that changes that state**
without inventing semantics.

---

## PART B — THE DECISION

*Select exactly one. Options are drafted in full so the consequence of each is visible.*

### ☑ OPTION 1 — **COMMISSION THE DERIVATION** — **SELECTED (GN-79, 2026-08-31)**

> The HPA commissions the **architecture/theory lane** to produce a canonical operation registry
> for ratification, against the acceptance standard in Part C. The commission is a derivation
> mandate, not a ratification: the deliverable returns for a separate ratification act.
>
> **Scope of the commission.** Determine the membership of the operation set, execute the
> necessity/minimality criterion over the candidates, resolve the two named unreconciled
> operations, and deliver per-operation contracts to the Part-C standard.
>
> **Explicit non-authorizations.** This commission does **not** authorize: adoption of any existing
> candidate by default · promotion of any verification-lane construct into canon · resolution of
> GC-1 · any change to v0.2, the Final Architecture, or the OQ register · any book prose.
>
> **Book consequence.** Chapters V.5 and V.6 remain `IMPLEMENTATION BLOCKER — CANONICAL SEMANTICS
> NOT ESTABLISHED` until the ratification act. The book may describe the blocker and this
> commission; it may not anticipate the outcome.

### ☐ OPTION 2 — **RATIFY A NAMED CANDIDATE**

> The HPA ratifies candidate `________________` as the canonical operation registry.
>
> **The drafting lane records that this option cannot presently be signed on evidence**: no
> candidate has passed the necessity test (A-6), the candidates disagree (A-5), the leading
> candidate's own record forbids freezing it (A-7), and no candidate has a governance act (A-8).
> Signing it would ratify an untested minimality claim.
>
> *If signed regardless, the ruling must state that minimality is **not** claimed, and the registry
> must enter as `RATIFIED — MEMBERSHIP ONLY · MINIMALITY NOT ESTABLISHED`.*

### ☐ OPTION 3 — **RULE THE REGISTRY OPEN AND MARK THE CONTRACT BLOCKED**

> The HPA rules that no canonical operation registry exists, that none is ratified, and that the
> implementation contract is **BLOCKED at Operations and Transformations** until a future act.
> The blocker is recorded as a first-class, citable governance state rather than an absence.
>
> **Book consequence.** V.5/V.6 are written *as the blocker* — its evidence, its owner, and what
> would lift it — with no candidate semantics. Part V ships incomplete and honest.

### ☐ OPTION 4 — **DEFER**

> No decision. The gap stands, unrecorded as a governance state. *The drafting lane notes that
> this is the only option that leaves the record less clear than it is today, since the gap is
> already established by evidence.*

---

## PART C — ACCEPTANCE STANDARD FOR A FUTURE REGISTRY

*Binding on Option 1's deliverable. What must be true before a registry may be ratified.*

**C-1 · Membership.** An enumerated set, with a stated criterion for inclusion, and an explicit
statement of whether the set is claimed **closed**.

**C-2 · The minimality test, executed.** The necessity criterion run over the candidate set, with
its inputs, procedure and outcomes recorded as an artifact. If minimality cannot be shown, the
registry may still be delivered — but it must carry `MINIMALITY NOT ESTABLISHED` and say so.

**C-3 · Reconciliation of the known conflicts.** The two operations recorded as unreconciled
against the executed algebra must be resolved or explicitly excluded with reasons.

**C-4 · Per-operation contract**, for every member — the eleven fields, none left implicit:
name · purpose · input state · preconditions · state transition · postconditions · invariant
obligations · evidence effect · authority effect · replay semantics · failure semantics.

**C-5 · Three prerequisites the contracts depend on**, delivered with them or explicitly deferred:
a **closed invariant register** (obligations have no codomain without it) · **typed rejection
semantics** (failure semantics presuppose the vocabulary) · a **state identity and equality rule**
(postconditions are undecidable without it).

**C-6 · Conformance with ratified constraints.** The registry must preserve, not restate: I-12's
covering relation, A6's authority-crossing asymmetry (an authority act crosses the boundary;
evidence never does), I-11's policy-change route, and I-5/I-6's evidence composition laws.

**C-7 · Provenance.** Every member traceable to its source, with its evidence class. A member
whose source class cannot be named is a proposal, not a member.

**C-8 · Independence.** The deliverable is reviewed by a party that did not author it, before the
ratification act. *(Standing programme rule: engineering supplies evidence and never accepts its
own work.)*

---

## PART D — WHAT THIS RULING DOES NOT DO

It does not resolve **GC-1 / I-11** · does not define Σ, `Q_t`, identity, equality, replay,
measurement or the Evidence object · does not select an aggregation operator (**OQ-3 remains open
by ruling**) · does not move any of **OQ-1…OQ-12** · does not adjudicate the self-attested
authority strings (A-8) · does not modify v0.2, the Final Architecture, Edition 1, Part II, or
Part III · does not authorize any book prose · does not declare the theory complete or incomplete.

---

## PART E — PLACEMENT IN THE PROGRAMME SEQUENCE

This ruling addresses **step 2** of the seven-step sequence, and unblocks steps 3–5:

```
1 canonical state           ── ratified (partial: named, not typed)
2 canonical operations      ── THIS RULING
3 legal transformations     ── blocked behind 2
4 authority / evidence      ── partial; blocked in part behind 2
5 replay semantics          ── blocked behind 2 and behind identity/equality
6 ratify the contract       ── a later, separate act
7 implementation spec + code── only after 6
```

**The book follows this sequence; it does not lead it.** The book may document what is
established, including this blocker. It may not supply the missing contract.

---

## PART F — RECORD

```
HPA RULING — CANONICAL OPERATION REGISTRY
Option selected:   ☑ 1 COMMISSION   ☐ 2 RATIFY   ☐ 3 RULE OPEN/BLOCKED   ☐ 4 DEFER
Conditions:        Part C acceptance standard binding; deliverable returns for a SEPARATE
                   ratification act; no candidate adopted by default.
Date:              2026-08-31
Recorded as:       GN-79  in analysis/governance-notes.md
```

**Until this block is completed and recorded in the ledger, the operation registry remains
NOT ESTABLISHED and Operations/Transformations remain BLOCKED.**
