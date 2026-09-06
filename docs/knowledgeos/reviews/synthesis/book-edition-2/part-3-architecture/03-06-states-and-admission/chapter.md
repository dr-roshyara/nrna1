# III.6 · States and Admission

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: How does anything become knowledge
> here? Through a three-status admission ladder governed by an explicit policy, with one boundary
> that no amount of evidence can cross on its own. — *Full Edition-1 text:
> `../../../book/part-3-architecture/03-06-states-and-admission/chapter.md`.*

## 1 · Two questions, and this chapter's discipline — *status: fact [FA] + method [M]*

[FA] A proposition inside K_t always has two coordinates: **how far admitted** (the ladder's
question) and **in what condition held** (rejected? contested? unknown? — the disposition
question). The ratified architecture answers the first with a three-status ladder and a governed
boundary, and the second with an adjacent layer of preserved states (D-FA-1). [M] The source that
built the ladder is richer than the ratified summary on BOTH axes, and that difference is a new
recorded production finding (PF-6). As in III.4, the chapter therefore keeps three levels visibly
apart — SOURCE MODEL [E] → RATIFIED SYNTHESIS [FA, verbatim] → EDITION-2 EXPLANATION [IN/M] — and
repairs nothing.

## 2 · The source model — a branched system, finally multidimensional — *status: source evidence [E]; PF-6 core*

[E — SOURCE MODEL] Step 008 begins with four statuses in a chain, and immediately branches:

```
Candidate → Supported → Accepted → Committed
                 │            
                 ├──→ Contested
                 ├──→ Rejected
Candidate ──────────→ Unresolved
```

— "these are not simply levels of 'confidence'; they represent different **domain states**." Its
formal acceptance function has SIX outcomes — `α_ρ : EA × P × C → {Candidate, Supported, Accepted,
Rejected, Contested, Unresolved}` — and the source warns that "the output is not necessarily a
single linear state": **Supported + Contested** is a legitimate combination. By §10 the source
goes further and recommends a **multidimensional status**:

```
Ω_A = ( SupportStatus, AcceptanceStatus, CommitmentStatus, ContestStatus )
```

with worked instances — e.g. Support: Strong · Acceptance: Accepted · Commitment: Committed ·
Contest: **Active** — "this looks strange initially, but it accurately represents real
organizational knowledge." §11's example makes the strange case concrete: a board formally accepts
a proposition while an architect still disputes its rationale — *"institutionally accepted while
still being epistemically contested… exactly why epistemic status and governance status should not
be collapsed."* And §16 boxes two inequalities this book will reuse constantly:
**NotAccepted ≠ Rejected** and **Unresolved ≠ Rejected** — insufficiency is not refutation.
*(One register note: the source names its status tuple Ω_A — a reused symbol with no relation to
the historical Ω of Part I.4; recorded as a hazard, imported by this book nowhere.)*

## 3 · The ratified synthesis — quoted, then the compression shown — *status: fact [FA] + method [M]*

[FA — RATIFIED, verbatim] The ratified concept row: *"**Epistemic statuses** —
`Candidate → Supported → Accepted → Committed`"* (READ, 008); amended by ruling R-3: *"the
epistemic ladder is **three-valued**: `Candidate → Supported → Accepted`. **`Committed` is a
decision-boundary status** attached to an Accepted item by authority"*, with A6 as the explicit
crossing rule; and by R-4: transitions form a covering relation, skipping formally excluded
(I-12). [M — the compression, without judgment] What the ratified wording carries forward is the
linear spine. What it does not name: the branches (Contested, Rejected, Unresolved), the
six-outcome acceptance function, and the source's own final recommendation of a four-dimensional
status. Three honest observations. First, the R-3 re-typing is SUPPORTED by the source itself —
§11's refusal to collapse epistemic and governance status is precisely the boundary the ruling
formalized; the ruled repair made explicit what the source argued. Second, the ratified
architecture did not lose the branch *content*: the Final Architecture's layered state model
(D-FA-1) reinstates REJECTED and CONFLICTED as adjacent preserved states — inherited, at ruling
time, from the constitutional side. Third — the PF-6 nuance — the inheritance story now has two
streams: the constitutional articles (7–9) AND the formal source's own branches; the archaeology's
finding that the two vocabularies were never *merged* stands, but "the ladder era lacked failure
states" would be false, and this book does not say it. Whether the ratified model should ever be
enriched toward Ω_A's dimensionality is PF-6's pending disposition — **not decided here.**

## 4 · The ladder's three statuses — *status: definitions (explain source content [E] under ratified form [FA])*

> **Definition — Candidate** · *Semantics (source):* "a proposition KnowledgeOS has reason to
> represent but does not yet have sufficient basis to accept" — the source's own example is an
> LLM extraction (`Candidate(P) = True`, `Accepted(P) = False`), "particularly important for
> AI-generated knowledge." *Scope:* existence-with-standing-to-be-evaluated; nothing more.
> *Grade:* [E] 008 §3 / [FA]. *Relations:* entry point of the ladder; the Verification Gate's
> mouth at L3. *Example:* "result R is correct," the moment the commission files its tally.
> *Limitation:* what suffices to become a candidate at all (vs noise) is policy-side, unmodeled.

> **Definition — Supported** · *Notation (source):*
> `Supported_ρ(P) = Assessment_ρ(P) ⊨ SupportCondition_ρ`. *Semantics:* "the evidence provides a
> defensible basis for the proposition under the selected assessment policy" — with the boxed
> warning `Supported(P) ⇏ Accepted(P)`. *Scope:* evidential standing only, policy-indexed (the ρ
> is III.5's policy-indexing, met again). *Grade:* [E] 008 §4 / [FA]. *Relations:* consumes
> III.5's assessment; the branch point for Contested/Rejected in the source model. *Example:* R
> after the independent tally comparison completes. *Limitation:* the SupportCondition's content
> is policy, not law.

> **Definition — Accepted** · *Notation (source):* `Accept_ρ(P, EA) → AcceptedAssertion`, subject
> to the acceptance policy. *Semantics:* admission as knowledge under the in-force policy — and
> the source immediately demonstrates **policy dependence**: under ρ₁ ("one authoritative current
> source suffices") P is Accepted while under ρ₂ ("two independent observations") the same
> evidence leaves it not — "this is not a mathematical contradiction; it is policy dependence."
> *Scope:* epistemic admission only — the ladder's top; NOT commitment. *Grade:* [E] 008 §5 /
> [FA]. *Relations:* produced by Determination; the only status from which the boundary can be
> crossed. *Example:* R accepted once the certification policy's conditions are met.
> *Limitation:* acceptance is revisable (the source's later machinery and the model's history
> discipline both insist nothing here is truth — §8 of the source: "truth remains outside the
> state machine").

## 5 · Determination and the policy that governs it — *status: definition [FA/E] + forward pointer*

> **Definition — Determination** · *Semantics:* the `Supported → Accepted` transition, performed
> under the in-force AcceptancePolicy — the ratified model's name (hosted from the corpus's
> warrant-establishment language) for the act of admission. *Grade:* [FA] (2D-2 ↦ 008).
> *Relations:* it is the ladder's only policy-gated rung; at L3 it is the Verification Gate's
> decision. *Example:* §10. *Limitation:* the transition CALCULUS — what exactly moves a given P —
> is policy content, and no general calculus is established (§9).

> **Definition — AcceptancePolicy ρ_A** · *Semantics (source):* the formal object specifying
> admission conditions — the source's own examples: evidence requirements (`N_independent ≥ 2`),
> source authority floors, temporal bounds (`Age(e) ≤ T_max`), conflict requirements
> (`ActiveConflict(P) = False`), human approval, statistical thresholds (`Pr(P|E) ≥ 0.95`) —
> "these are **examples of policy, not universal laws**." *Scope:* what may be admitted, as
> distinct from what evidence supports (the ratified row's exact distinction). *Grade:* [E] 008
> §12 / [FA], with the R-1 amendment: the policy is itself a **governed, versioned object** —
> policy-as-content vs policy-in-force, no in-force change without a governed decision (I-11;
> Chapter III.8 owns that machinery). *Example:* the certification policy of the running example.
> *Limitation:* policy composition/conflict resolution is later-work territory.

## 6 · The boundary — Committed, and A6 — *status: definition [FA/E] + ruled history [R]*

> **Definition — Committed** · *Semantics (source):* willingness to RELY — "the Knower/domain
> actor is willing to rely upon the proposition for a defined purpose or action," with
> `Accepted(P) ⇏ Committed(P)` boxed, and commitment **purpose-dependent** (`Committed(P,
> Migration)` — one may be committed for one purpose and not another). *The authority clause
> (source §21, quoted):* "**Authority is part of commitment, not evidence.** A manager's authority
> does not make evidence more truthful. It determines what the organization is permitted to commit
> to." *Scope:* a decision-boundary status attached to an Accepted item by authority — NOT a
> fourth epistemic rung (R-3). *Grade:* [E] 008 §§6–7, 21 / [FA]/[R]. *Relations:* the crossing is
> A6's act; its machinery is III.7's Decision Contract; its record is an L5 act. *Example:* §10.
> *Limitation:* commitment semantics beyond reliance-for-purpose (e.g. revocation) are unmodeled.

[FA/R] **A6** — *authority determines commitment, not evidential truth* — is invariant I-4, and
the reader has now seen its full pedigree: argued in the source (§21), pre-echoed by the source's
refusal to collapse epistemic and governance status (§11), violated *structurally* by the first
synthesis (which stacked Committed as a fourth rung — finding F-3), and repaired by explicit
ruling (R-3) into the boundary form the architecture now carries. [E] The source even states the
boundary's AI-safety face (§22): an LLM may produce a Candidate, even a Supported item under
proper assessment — but `LLMOutput ⇏ OrganizationalCommitment`; "a human or governed process may
be required… an extremely important KnowledgeOS safety property." That sentence, written days
before the constitutional layer's Article 6, is the same law approached from the formal side.

## 7 · Order and the no-skip rule — *status: fact [FA/R] + honest source accounting [E]*

[FA] I-12: transitions follow the covering relation — `Candidate ⋖ Supported ⋖ Accepted`, and
`Accepted ⋖ Committed` across the boundary under A6; skipping is formally excluded. [E — what the
source does and does not establish] 008 §23 defines the staged transitions —
`(EA, P, ρ_A) → AcceptanceOutcome`, then `(AcceptanceOutcome, Authority, Purpose) →
CommitmentOutcome`, boxed as "Evidence → Assessment → Acceptance → Commitment is formally
distinct" — which gives the ORDER. What the source nowhere states is a general no-skip AXIOM; the
falsification pass named that absence (F-4), and I-12 entered by ruled repair, graded
REQUIRED-BY-COHERENCE. Taught exactly so: the order is source-established; the axiom is
synthesis-ruled. [E] §24 adds the state-integration reading: acceptance and commitment are domain
EVENTS on the knowledge state (`K_t —AssertionAccepted→ K_{t+1}`), and "the underlying assertion
does not change truth value; its **institutional status** changes" — the ladder is
institutional machinery, not a truth thermometer (§8 of the source says it flatly: truth remains
outside the state machine).

## 8 · The adjacent states — two inheritance streams — *status: fact [FA] + source evidence [E]; PF-6 taught*

[FA] The ratified Final Architecture layers preserved non-admission states beside the untouched
ladder (D-FA-1): **REJECTED** — failed reasoning preserved as an explicit state, never silently
discarded (constitutional Article 7); **CONFLICTED** — contradictory knowledge coexisting until
governed resolution, the conflict record surviving it (Article 8); and the evidence-layer triple
**UNKNOWN ≠ ABSENT ≠ FALSE** (Article 9). [E — the PF-6 nuance, stated precisely] That layering
was ruled as an inheritance from the constitutional side — and the source verification behind this
chapter shows the formal side had *its own* branch states all along: Rejected and Contested as
Supported-branches, Unresolved as a Candidate-branch, with `NotAccepted ≠ Rejected` and
`Unresolved ≠ Rejected` boxed. Two streams, one lesson, arrived at independently — which is this
programme's strongest pattern of confidence everywhere it appears. The correspondence, laid flat
without merging: source *Rejected* ↔ constitutional REJECTED (same refusal to let failure vanish);
source *Contested* ↔ constitutional CONFLICTED (with a shade of difference — Contested marks live
dispute about an accepted item, CONFLICTED marks suspended contradiction; the ratified model keeps
CONFLICTED and the shade is recorded, not resolved); source *Unresolved* ↔ the UNKNOWN family
(insufficiency is not refutation). [FA] The interaction semantics between ladder and CONFLICTED
remain deliberately coarse — a recorded consequence of D-FA-1, taught AS coarse; refining it is
future architecture work, not book work.

## 9 · What remains open in the transition machinery — *status: open [U] + source evidence [E]*

[U] **The transition calculus is NOT ESTABLISHED**: no general rule says what quantum of
assessment moves a specific P from Supported to Accepted — deliberately, because that content is
policy (§5's blocks), and policies are examples, not laws. What WOULD close it: a ratified policy
language plus per-domain policy instances. [E] The source's own §26 asks the structural version —
"the acceptance lattice question": is the status space a lattice, what are joins/meets of
combined statuses (Supported+Contested)? — and leaves it as a question; §27's mathematical
structure is exploratory. Recorded at source strength; no lattice theory is invented here. [U]
Whether the multidimensional Ω_A should supersede the linear ladder is PF-6's pending governance
matter, not an open architecture question of the ratified model — the ratified model is
internally coherent as ruled (its completeness is exactly what the open under-specifications
qualify — wording corrected under GN-54, MV-F-19).

## 10 · The running example — three journeys — *status: illustration [IN]/[EDITORIAL]; never evidence*

*(Stage 5. CONCEPTUAL MODEL, on III.3's K_t, after III.4's gap vector and III.5's evidence
episode.)* **Journey 1 — the ladder, cleanly.** "Result R is correct" enters as **Candidate** when
the commission's tally file lands. The independent comparison completes; under the certification
policy's assessment condition, R becomes **Supported** (`Assessment ⊨ SupportCondition`).
Determination runs against the in-force policy — which requires, say, reconciliation complete
(r₁ Satisfied) AND no active conflict on custody — and stalls: `ActiveConflict = True` via r₃. R
stays Supported. The custody conflict is adjudicated (a governed resolution; the conflict record
survives); Determination re-runs; R is **Accepted**. Note what did NOT happen: no amount of
additional tally copies (III.5's forty-one) would have moved R one rung — the ladder listens to
the policy, not to volume. **Journey 2 — the boundary.** The returning officer, satisfied the
contract is met, signs the certification decision: `Committed(R, PublishCertification)` — an
authority act with a purpose parameter, recorded, crossing A6's line. Had the officer lacked
authority (III.7's `Auth` unfilled), R would remain Accepted forever — evidence cannot cross.
**Journey 3 — the branches.** The rival tally proposition R′ ("result R′ is correct," from a
separate, contested wire chain — distinct from III.5's syndicated recount reports; disambiguated
for example-continuity under GN-45, AF-F-18) reaches Supported on its face, is Contested by the commission's file,
fails Determination on the conflict condition, and — after adjudication finds its chain
derivative — is **Rejected**: preserved with its reasoning, never deleted (Article 7), and
distinct from merely NotAccepted. *(ARCHITECTURE: Journey 1's Determination is the Verification
Gate deciding; Journey 3's preservation is State Preservation; the conflict lived in the
Contradiction Registry. IMPLEMENTATION-HONESTY: none of this executes — the one running cousin is
the authorization boundary's fail-closed resolution in the session bootstrap, which is Journey
2's shape in code, for sessions rather than certifications.)*

## 11 · The lived case — the ladder in the governance record — *status: evidence [E]*

[E] The conformance pass found the boundary and the no-skip discipline OPERATING in the
repository's own governance: an implemented-and-green asset (AST-019, 25 tests passing) was held
**NOT ADOPTED** solely because independent verification had not occurred — no skipping — and the
operating model's four states were "reached in order, by separate acts," with adoption and
authorization decided as SEPARATE human acts and the record refusing to let one imply the other
("§38 forbids collapsing the two"). [IN] Map it: implemented ≈ Candidate; verified ≈ Supported;
adopted ≈ Accepted; authorized-for-use ≈ Committed — an analogy, marked as one (the vocabularies
differ; the correspondence is structural). The architecture's admission discipline is not
aspirational: its shape already governs the very programme that produced it.

## 12 · Realization — L3 and L4 — *status: fact [FA/E]*

[FA←E] At L3: the **Verification Gate** is Determination's architectural home — "the only
admission path into knowledge"; engines (validation, reasoning, the LLM) PROPOSE verdicts, the
gate DECIDES admission. One vocabulary distinction matters here and the conformance record makes
it precisely: the L3 **verdict vocabulary** (VALIDATED / QUESTIONABLE / REJECTED / CONFLICTED) is
"an engine product, not a kernel law" — engine outputs at the gate's mouth, NOT the epistemic
ladder; this book never conflates the two vocabularies (the Edition-1 correction D-1 is the
recorded lesson). **State Preservation** holds REJECTED; the **Contradiction Registry** holds
CONFLICTED. [E — L4] Nothing of the ladder executes: no status datatype, no Determination engine,
no policy evaluator. The one implemented relative is the session bootstrap's fail-closed
authorization resolution — boundary machinery, not ladder machinery. Stated once more because
this chapter is where the temptation is greatest: **a diagram of the state space is a diagram of
ratified concepts, not of software.**

## 13 · Limitations and open questions — *status: register [U]*

- **Transition calculus: NOT ESTABLISHED** (§9) — policy-content by design; closing act named.
- **Ladder↔CONFLICTED interaction: deliberately coarse** (D-FA-1's recorded consequence).
- **Source lattice question (§26) and Ω_A dimensionality (PF-6): source-level / pending
  disposition** — recorded, untouched.
- **Contested-vs-CONFLICTED shade** (§8): recorded, unresolved, unmerged.
- **No governed OQ is owned by this chapter**; A6 and no-skip's grades (READ / REQUIRED-BY-
  COHERENCE) are restated wherever taught.

## 14 · Conclusion — *status: fact [FA], summary*

[FA] Admission in KnowledgeOS is a short ladder with a hard ceiling: three statuses earned from
evidence under a governed, versioned policy — and one further status that no evidence can confer,
because it is not an epistemic fact at all but an act of authority taken for a purpose. Around the
ladder, the architecture preserves what lesser systems delete: the rejected, the contested, the
unknown. The source built this system branched and finally recommended it multidimensional; the
ratified model carries its linear spine with the branches re-homed as adjacent states; the
difference is a recorded finding awaiting governance, not a secret. What crosses the boundary, and
under what contract, is the next chapter's whole subject.
