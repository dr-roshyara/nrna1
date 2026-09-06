# III.4 · Zero — the Gap That Drives Inquiry

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: `Zero(K, EC)` is the model's engine
> of motion: the typed discrepancy between what the knowledge state holds and what the
> EpistemicContract requires. — *Full Edition-1 text:
> `../../../book/part-3-architecture/03-04-zero-the-gap-that-drives-inquiry/chapter.md`.*

## 1 · Why Zero exists — *status: fact [FA] + source evidence [E]*

[FA] A knowledge system without a gap function is a warehouse: it can hold, admit, and preserve,
but it has no reason to *do* anything. Zero is the model's answer to "why act?": the computed
discrepancy between what the frame requires (III.2's contract) and what the state holds (III.3's
K_t). Inquiry, in this model, is literally powered by that distance.

[E] The source document that built Zero opens with a correction that sets this whole chapter's
tone — **"Zero is not one difference"** — and a three-line table that shows why:

```
Rollback verified          unknown
Firewall permitted         conflicting
Architecture approval      missing
```

One goal; three requirements; three *different kinds* of shortfall, each demanding a different
response. Everything else in the source — the status set, the vector, the refusal to be a number —
unfolds from that opening insight. [IN] The reader should hold it as the chapter's test: any
account of Zero that cannot distinguish those three lines has already failed.

## 2 · What Zero is — *status: definition (explains existing authority [FA] + source evidence [E])*

> **Definition — Zero**
> *Notation (ratified):* **`Zero(K, EC)`** — the canonical signature, adopted by ruling (GN-19
> R-2); *historical signature* `Zero(K, G, EC)` retained as lineage.
> *Notation (source):* `Zero(K_t, G, EC_G) → StructuredGapSet`.
> *Semantics (the source's own verdict, quoted):* Zero is *"a directed, purpose-relative,
> structured epistemic discrepancy operator"* — **directed** (it measures from the state toward
> the contract, never symmetrically), **purpose-relative** (a gap exists only relative to an owned
> frame), **structured** (its output is a set of typed items, never a number).
> *Scope:* gap DETECTION only — Zero reports how each requirement stands; it does not decide,
> plan, resolve, or act (§11).
> *Evidence/grade:* READ (025d), with the current signature RULED; the per-item structure and
> status set are [E — source-specified] at the depth §§5–7 teach.
> *Relations:* consumes K_t and EC; its output is what the Proposal selector reads (III.7); its
> statuses are consumed downstream by admission reasoning (III.6).
> *Example:* §§13–14. *Limitation:* several semantic properties are open — §16.

## 3 · Three levels, kept apart — *status: method [M]; the PF-1 discipline*

[M] This chapter teaches an object whose source description and ratified description differ in
richness, and the difference is itself a recorded production finding (PF-1). To keep every
sentence honest, the chapter uses three explicit levels:

```
SOURCE MODEL           what 025d actually constructs (nine-status vector, per-item structure,
                       two Zeros, contract as (R,Γ))                        — grade [E]
RATIFIED SYNTHESIS     what v0.2 carries, quoted verbatim at its strength    — grade [FA]
EDITION-2 EXPLANATION  this book's teaching layer: mappings, examples,
                       and the compression made visible                     — grade [IN]/[EDITORIAL]
```

**The rule (GN-42):** the explanation may show the compression; it may not repair it, judge it, or
promote the source model into ratified architecture. Whether the ratified summary should ever be
enriched toward the source is a governance disposition that has not happened.

## 4 · Zero's inputs — the contract side — *status: source evidence [E] + fact [FA]; PF-5 taught*

[E — SOURCE MODEL] The source derives requirements from the goal: `R_G = {r₁, …, r_n}` — its own
worked list, for a system-migration goal: CurrentVersionKnown, TargetVersionKnown, BackupVerified,
RollbackVerified, NetworkRequirementsKnown, SecurityRequirementsSatisfied,
GovernanceApprovalObtained. Immediately follows the sentence this book has already met twice, in
its original home: **"Zero cannot be computed from the goal alone."** The source then defines the
contract as a **pair**: `EC_G = (R_G, Γ_G)` — required conditions *plus the rules by which
sufficiency is judged* ("what must be established + how sufficiency is judged").

[FA — RATIFIED SYNTHESIS] The ratified row reads: *EC — "goal-derived requirement set: what must
be known or satisfied."* [M — EDITION-2] The Γ half (sufficiency rules) is thus present in the
source and compressed out of the ratified one-liner — production finding PF-5(a), taught here and
repaired nowhere. Nothing in the ratified model contradicts the pair reading; the model simply
summarizes. [FA] Where the goal itself went is settled by ruling and taught in III.2: the ratified
signature drops G as a direct argument (no evidenced residual once EC carries requirement
content); the historical ternary form survives as lineage.

## 5 · Zero's output — a structured item, not a mark — *status: source evidence [E]*

[E — SOURCE MODEL] The source's first shorthand — `Zero = R_G \ Satisfied(K, R_G)` (the
unsatisfied remainder) — is immediately rejected by its own author as "only a shorthand," because
**the real result must retain WHY each requirement is not satisfied.** The genuine output is a set
of items of the form:

```
Zero-item = { Requirement, Status, Evidence, Reason, Dependencies }
```

with the source's own conceptual example:

```
Requirement:        RollbackVerified
Status:             Unknown
SupportingEvidence: none
BlockingReason:     no successful restore test
RequiredAction:     perform restore test
DecisionCriticality high
```

[IN] Note what the item carries beyond a status: its *evidence link* (III.5's store plugs in
here), its *reason* (auditability — the gap explains itself), its *dependencies* (§16's watch
item), and a *criticality* (why §9's refusal to sum gaps matters). The item is where "retains
why" — the ratified row's phrase — lives concretely.

## 6 · The satisfaction statuses — the source's full set — *status: source evidence [E]; PF-1 taught*

[E — SOURCE MODEL] For each requirement r, the source evaluates `Sat(K_t, r)` and proposes the
status set:

> **𝒮 = { Satisfied, PartiallySatisfied, Unknown, Insufficient, Conflicted, Stale, Invalid,
> Prohibited, NotApplicable }** — nine values, "already much richer than Boolean logic."

The source's argument for the richness (§25D.5, reconstructable in one breath): suppose
`Sat(K, r) = False` — that single bit could mean *we know it fails*, *we don't know*, *evidence
conflicts*, *evidence is stale*, *evidence was invalidated*, or *governance prohibits it* — and
these are "operationally very different," so **`Sat ≠ Boolean`** (boxed in the source). Zero is
then the **vector** `Zero(K_t, G, EC_G) = {(rᵢ, Zᵢ)}` — one typed status per requirement.

## 7 · Missing versus Unknown — the source's finest cut — *status: source evidence [E]*

[E — SOURCE MODEL] The source separates two cases that a careless model merges. **Case A:**
`Evidence(r) = ∅` — nothing is known either way: status **Unknown**. **Case B:** the requirement
is an *expected governed artifact or action* — an approval that must exist and does not: status
**Missing**. An absent approval is not an epistemic blank; it is a determinate institutional fact.
[IN] The cut matters operationally (Unknown calls for inquiry; Missing calls for an act) and
philosophically: it is this model's local echo of the constitutional Unknown-law's triple
(unknown ≠ absent ≠ false), arrived at from the requirements side.

## 8 · The ratified four-arm synthesis — and the compression, made visible — *status: fact [FA] + method [M]; PF-1's core*

[FA — RATIFIED SYNTHESIS, verbatim] The ratified concept row reads: *"**Zero / Z_t** —
`Zero(K,G,EC)` — typed discrepancy (unknown/conflicting/missing/invalid), retains why"* (grade
READ, source 025d; signature since simplified to `Zero(K, EC)` by ruling, lineage retained).

[M — EDITION-2, the compression shown without judgment] Four named arms summarize nine source
statuses. The mapping, laid flat:

| Ratified arm | Source statuses it most plausibly gathers | Source statuses left unnamed by the summary |
|---|---|---|
| unknown | Unknown | |
| conflicting | Conflicted | |
| missing | Missing | |
| invalid | Invalid | |
| — | | Satisfied, PartiallySatisfied, Insufficient, Stale, Prohibited, NotApplicable |

Three honest observations, none of which is a repair. First, the four ratified arms are all
*gap*-arms — the summary names kinds of shortfall, and a Zero output naturally omits Satisfied and
NotApplicable; to that extent the compression is a natural projection. Second, three genuine gap
kinds — **Insufficient** (evidence exists but does not meet Γ's bar), **Stale** (evidence aged out
of validity), **Prohibited** (governance forbids) — and the graded **PartiallySatisfied** have no
named arm in the ratified summary; a reader of the ratified row alone would not know the source
distinguishes them. Third, the ratified CONCEPT row never claims the source contained *exactly four* statuses — but
ratified invariant I-9's own wording does say "four-way," which places the compression inside a
ratified invariant's text (registered as finding AF-F-9 by the hostile pass; corrected here under
GN-45 from this paragraph's earlier, false "nothing anywhere records…" version). [M] Whether the
ratified summary should ever be enriched toward the nine-status source is **PF-1's pending
governance disposition — explicitly not decided here.** Until then: the ratified four-arm wording
is the architecture; the nine-status set is what its cited source establishes; and this book
teaches both at exactly those strengths.

## 9 · Never a number — *status: source evidence [E] + fact [FA]; derivation RECONSTRUCTABLE*

[E — RECONSTRUCTABLE, the source's own argument] Could Zero be summarized as a count? The source
runs the case: `Z_knowledge = 5` informational gaps, `Z_governance = 1` missing approval — "can we
say Zero = 6? **No.**" The six items are not comparable: one missing approval can block everything
while five informational gaps block nothing. Hence the boxed source law: **"Zero is primarily a
structured object, not a scalar"** — and its later formal echo, the verdict already quoted in §2
("not a scalar distance… should not initially be modeled as a metric"). [FA] The ratified model
carries the same commitment twice: invariant **I-9** — ratified wording, exactly: *"Zero's
four-way non-satisfaction typology must not collapse to Boolean"* (the word "four-way" places §8's
compression inside the invariant's own ratified text — finding AF-F-9, pending disposition; the
citation's earlier paraphrase elided exactly that word and was corrected under GN-45) — and, one
layer up, the constitutional Dimension article (no single score
replaces epistemic structure). [E] The source is careful on the flip side too: *derived metrics
are permitted* (counts, per-category tallies, dashboards) so long as the structured object remains
the primary form the system reasons over — a metric may summarize Zero; it may never BE Zero.

## 10 · Zero and Evidence — *status: fact [FA] + source evidence [E]*

[FA/E] Zero consumes III.5's world through the per-item Evidence field, and the two chapters'
vocabularies interlock precisely: an item's status is **Conflicted** exactly when the evidence
store holds preserved support-for and support-against (criterion E's (S⁺,S⁻) shape); **Stale**
when temporal validity (criterion H) has lapsed — with the same insistence that stale evidence
remains historically valuable; **Invalid** when qualification failed retroactively; **Unknown**
when the store is empty for r; **Insufficient** when items exist but Γ's bar is unmet — the
existence-versus-sufficiency distinction of III.5 §3, now with its formal home. Two source
boundaries complete the picture. **Orchestration, not inference** (§25D.13): Zero does not
evaluate requirements itself — it routes each to the appropriate reasoning model (rules,
statistics, authority) and aggregates the returned statuses; Zero is a coordinator over Layer-3
inference, not a fifth aggregation operator. And **no invented gaps** (§25D.28): if the contract
does not require ContainerCPUArchitecture, Zero must not report it missing merely because an LLM
finds it useful — *"Zero compares against declared/derived requirements, not against arbitrary AI
expectations"*, which the source calls "one of the strongest governance properties we have
discovered." [IN] That property is why Zero, unusually for a gap detector, is *governable*: its
scope of complaint is exactly the owned contract, no more.

## 11 · Zero, the selector, and the decision boundary — *status: fact [FA] + source evidence [E]*

[FA] Zero's output is read by the **Proposal selector** (III.7): from `(K_t, Z_t, G)` the selector
proposes the next epistemic action — and holds no authority. [E — SOURCE MODEL] The source draws
the same line in its own idiom, in a section literally titled "Zero must not become" the selector
(the source uses the selector's historical lens-name — Part I.5 tells that naming story):
Zero says *"rollback verification is missing"*; the selector says *"run a restore test."* Boxed:
**Zero = gap detection; [the selector] = gap-resolution planning.** "This separation should be
preserved" — and the ratified model preserved it as invariant I-2. [FA] Downstream, Zero's
statuses inform admission reasoning (a Conflicted item is exactly what III.6's CONFLICTED
state holds in suspension) and the Decision Contract's Evidence component (III.7) — but Zero
itself never admits, never decides, never commits: A6's boundary is untouched by any amount of
gap. [E — SOURCE MODEL, PF-5(b)] One more source structure did not survive into the ratified
model: §25D.14 distinguishes **operational Zero** `Z_W = Distance(W_t, W*)` (distance of the WORLD
from a target state) from **epistemic Zero** `Z_K = Distance(K_t, K*_EC)` (distance of KNOWLEDGE
from the contract) — "fundamentally different." The ratified model carries only the epistemic one;
the operational twin lives on the action side of the boundary, where the architecture is
explicitly open (OQ-4). Taught as source content; promoted nowhere.

## 12 · One name, three referents — the register — *status: fact [FA/R] + historical [H]*

[FA/R] The ratified terminology register (D-FA-3) binds this book: unqualified **Zero** means the
L2 gap operator of this chapter, and nothing else. The other two bearers of the name: **Z-KOS-001**
— the repository side's ratified *meta-principle* ("not a kernel object, not an epistemic status,
not implementation"; the neutral-reference attitude behind the constitutional boundaries), which
is NOT this chapter's object and is never merged with it; and the **absence-lens Zero** of the
early corpus — historical only, dated, told in Part I.2. [H] Chronology, fixed by the archaeology
so hindsight cannot invert it: the meta-principle and the absence-lens are the older senses
(2026-08-22); the computable gap operator is the youngest (2026-08-27). [IN] What the three share
is a refusal — treating not-knowing as neither nothing nor falsehood — and that shared refusal,
not any shared definition, is why one word kept being chosen. Kinship acknowledged; identity
denied; register enforced.

## 13 · Worked example 1 — a straightforward gap — *status: illustration [IN]/[EDITORIAL]; never evidence*

*(Running example, stage 3. CONCEPTUAL MODEL:)* The certification contract's r₂ — *an independent
recount reproduces the tally* — after III.5's evidence episode: the store holds exactly one
genuinely independent item (the commission's signed tally file), and Γ for r₂ demands
reproduction *within tolerance*, which the file alone does not yet establish (its figures await
comparison). Zero emits one item:

```
Requirement: r₂ IndependentReproduction   Status: Insufficient
Evidence: {commission tally file}          Reason: no completed comparison against official tally
Dependencies: —                            Criticality: high
```

One requirement, one status, one self-explaining reason, one obvious next action for the selector
to propose. *(ARCHITECTURE:* nothing here touches a gate — Zero is L2 reasoning over the record.
*IMPLEMENTATION:* no Zero engine exists; this item was produced by hand, on paper — §15.)

## 14 · Worked example 2 — why the vector matters — *status: illustration [IN]/[EDITORIAL]*

*(Running example, stage 3 continued — the full contract.)* The whole certification contract at
one moment, as a Zero vector:

```
r₁ CountReconciliation      Satisfied        (all districts reconciled)
r₂ IndependentReproduction  Insufficient     (§13's item)
r₃ CustodyContinuous        Conflicted       (transport log vs. the anomaly report — S⁺ and S⁻ both live)
r₄ AnomalyDispositions      Missing          (two filed anomalies carry no disposition record — expected
                                              governed artifacts that do not exist)
r₅ SoftwareAttested         Stale            (attestation predates a patch; historically valuable, no longer current)
```

[IN] Now run the two collapses this chapter forbids, and watch what each destroys. **Collapse to
Boolean:** "certification not ready: False on 4 of 5" — true, useless, and indistinguishable from
any other failing election. **Collapse to a scalar:** "readiness 0.62" — worse than useless: r₄
is a governance blocker an official must act on, r₂ is one comparison away from closure, r₅ needs
a re-run, r₃ needs adjudication — a single number ranks none of this, and averaging lets the
blocking Missing hide behind the nearly-done Insufficient. The vector, by contrast, IS the work
plan's raw material — which is exactly why the next object in the model is a selector that reads
it, and why criticality lives per-item, never in a sum. *(ARCHITECTURE:* r₃'s Conflicted item is
what the Contradiction Registry would hold in suspension; r₄'s Missing is Article-3 territory —
an act, not a fact, is absent. *IMPLEMENTATION:* none — next section.)*

## 15 · Realization: L3 and L4, strictly separated from the concept — *status: fact [FA/E]*

[FA] Zero is an **L2 concept**, and the reader should notice what the repository architecture does
NOT contain: RA v1.0 has no "Zero service" among its eleven kernel services — deliberately. The
repository side's ratified position (for its own Zero-sense, Z-KOS-001) is that placing Zero in
the architecture as a component would be a category error; and for the L2 gap operator, the
constituent functions land in existing structure: requirement evaluation routes through engines
(the §10 orchestration), conflicted items surface in the Contradiction Registry, and the
statuses' evidence links live in Evidence Records behind the Verification Gate. [E — L4, at true
strength] Nothing of this chapter is executable today: no Zero engine, no Sat evaluator, no
status vector datatype. The source contains a computational sketch (§25D.37) and falsification
tests (§25D.38) — specifications and arguments, not software. The two worked examples above were
computed by the author of this chapter, by hand, as illustrations. Any sentence anywhere implying
a running Zero would exceed the evidence, and none is written.

## 16 · Limitations and open questions — *status: register [U] + source-level watch items*

- **OQ-1 (governed, owned by III.2, touching this chapter):** the ratified signature `Zero(K,EC)`
  is evidence-conservative, not proven minimal; a residual role for G inside the gap is neither
  established nor excluded. [E] This chapter adds the finding that deepens OQ-1's roots: the
  source ITSELF left the contract's construction unresolved — §25D.39 asks "how do we construct
  the Epistemic Contract itself?", names **ContractDerivation** as the missing mechanism, and
  lists where requirements actually come from (constitution, policy, architecture standards,
  ADRs, risk models, human instruction, law, convention). η's non-construction is the synthesis's
  honest reflection of a source-level opening, not an oversight.
- **Semantic properties NOT ESTABLISHED:** completeness of the nine-status set (the source says
  "initially I propose" — it is a proposal, not a closure); minimality of the four ratified arms;
  equivalence conditions between statuses; any metric or order structure on Zero outputs (the
  source explicitly deferred metrization). Taught as open; no mathematics invented.
- **Source-level watch item — RequirementGraph (§25D.40):** requirements can depend on one another
  (r₃ ⇒ r₁ ∧ r₂), and a dependency-blind Zero reports one chain as three gaps. The requirement-side
  mirror of III.5's I-6. Recorded at source strength; ungoverned; no new OQ invented.
- **PF-1 / PF-5:** the compression findings this chapter teaches — pending disposition, repaired
  nowhere.

## 17 · Conclusion — *status: fact [FA], summary*

[FA] Zero is the model's reason to move: a directed, purpose-relative, structured discrepancy —
per requirement, typed, evidence-linked, self-explaining, and constitutionally forbidden to
collapse into a number. Its source built it richer than its ratified summary says; its ratified
summary is quoted here at exactly its strength; and the difference between them is a recorded
finding awaiting a governance decision, not a secret this book resolves. What Zero hands onward is
a vector of named shortfalls — and the model's next question, taken up after the state chapter, is
who may do what about them, with whose authority.
