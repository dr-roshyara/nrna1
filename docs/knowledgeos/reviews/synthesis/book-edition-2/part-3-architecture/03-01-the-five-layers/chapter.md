# III.1 · The Five Layers

> **Edition 1 abstract** *(frozen baseline, verbatim)*: KnowledgeOS, as ratified, is not one
> artifact but a discipline of five layers, each with its own kind of authority, plus a body of
> material that sits deliberately outside all of them. — *Full Edition-1 text:
> `../../../book/part-3-architecture/03-01-the-five-layers/chapter.md`.*

## 1 · Why layers at all — *status: fact [FA] with inference [IN] marked*

[FA] Every architecture makes a first cut, and KnowledgeOS's first cut is unusual: it separates
kinds of **authority** before it separates kinds of **function**. The reason is visible in the
system's own history (Part II tells it as method; Part I as events): the programme repeatedly
found that its worst failures were authority-confusions, not function-failures — evidence treated
as permission, generation treated as justification, a policy governing admission while nothing
governed the policy, recommendations quietly becoming rulings. [IN] A functional layering (storage
below logic below interface) would have left every one of those failures expressible. The layering
KnowledgeOS ratified makes them *type errors*: a claim in one layer simply has no standing in
another until an explicit act carries it across.

The five layers, with the kind of authority each holds:

```
L1  CONSTITUTIONAL   prohibitions   "this collapse must never happen"     (ratified law)
L2  FORMAL MODEL     descriptions   "this is the epistemic process"       (authorized model)
L3  REPOSITORY ARCH  boundaries     "this structure makes L1 unbreakable" (proposed realization)
L4  IMPLEMENTATION   behavior       "this code actually runs"             (evidence of practice)
L5  GOVERNANCE       acts           "this ruling made X authoritative"    (the authority itself)
     — and OUTSIDE: the historical corpus: evidence of discovery, authority over nothing
```

## 2 · L1 — the constitutional layer — *status: fact [FA←E]*

[FA] Eleven articles, each a **negation**: Identity (similarity is never identity; context is part
of identity), Dimension (no epistemic dimension collapses into another; no single quality scalar),
Authority (evidence, assessment, and source never constitute authority — authority is *assigned*,
a recorded reference to a human act), Decision (knowledge informs action and never executes it),
Projection (a projection is never its source), Verification (nothing enters knowledge without a
preserved justification path; generation is not justification), Failure-State (failed reasoning is
preserved as an explicit state, never silently discarded), Contradiction (conflicting knowledge
coexists as CONFLICTED until governed resolution), Unknown (UNKNOWN is first-class;
unknown ≠ absent ≠ false), Agency ("who knows?" is always answerable), History (revision never
deletes; supersession is forward-only).

[E] Two properties matter architecturally. First, each article was **failure-tested at selection**:
the ratifying record names, per article, the corruption that opens if it is removed ("Document =
Knowledge", "Knowledge Quality = 0.87", "sensor = expert = AI", …). An article is not a value
statement; it is a guard with a named threat model. The full guard table, as the selection record
carries it [E]:

| Article | Forbidden collapse (failure signature if removed) |
|---|---|
| 1 Identity | Document = Knowledge · vector match = identity · context-free claims · detached truth |
| 2 Dimension | "Knowledge Quality = 0.87" · silent cross-dimension interference |
| 3 Authority | a recorded reference self-authorizes · assessment becomes binding · sensor = expert = AI |
| 4 Decision | observation auto-decides · knowledge executes |
| 5 Projection | projection = source · edits to a view corrupt the record |
| 6 Verification | AI output = reality · unsupported claim = knowledge · hallucination = evidence · black-box inference |
| 7 Failure-State | circular reasoning becomes knowledge · rejected reasoning vanishes |
| 8 Contradiction | premature TRUE/FALSE · the weaker side deleted · disagreement destroyed |
| 9 Unknown | no-evidence = PASS/FALSE · doubt flattened to a lacuna |
| 10 Agency | knowledge exists anonymously · accountability lost |
| 11 History | new truth overwrites old · freshness = truth · history destroyed |

Read the right-hand column slowly: it is a catalogue of familiar system behaviors. Search engines
live on Article 1's forbidden flow; scoring dashboards on Article 2's; "the model said so" on
Articles 3 and 6; autonomous pipelines on Article 4's; every silently-updated wiki on Article 11's.
[IN] The constitution is best understood not as idealism but as a census of the ways real systems
routinely counterfeit knowledge — with each counterfeit individually outlawed.

Second, the articles are **prohibitions, not
mechanisms** — L1 never says how anything works. That is what makes the layer stable: mechanisms
below it may be replaced freely so long as no forbidden flow becomes expressible (the
constitution's own Chapter V draws exactly this line between free engineering evolution and
ratification-gated amendment). Chapter III.9 treats the articles systematically; their selection
history (twenty-nine registered rows compressed to eleven laws in one governed day) is Part I.2's
story.

## 3 · L2 — the formal model — *status: fact [FA]*

[FA] The authorized canonical model v0.2 is a model of **epistemic process**: a Knower who owns a
goal G and a sufficiency standard (IdealState); a contract EC = η(G, IdealState) carrying what
must be known; a knowledge state K_t over eight primitives; a typed gap Zero(K, EC) that says
precisely how the state falls short of the contract; evidence whose combination is governed by the
model's only two computationally tested invariants; a three-status admission ladder whose crossing
into commitment is an authority act (A6); a six-part Decision Contract; a governance concern with
its own algebra; and an action loop that closes back into observation. [FA] L2 *describes*; it
prohibits nothing and runs nothing. Its relation to L1 is conformance (§8), not derivation: the
model was not deduced from the articles — it was built independently (Part I) and then shown not
to contradict them (Part II.4).

[IN] The reader should hold one asymmetry from the start: L1 is *ratified law*; L2 is an
*authorized model* whose elements carry explicit epistemic grades. The grade distribution is worth
a paragraph before any element is taught, because it calibrates trust in everything that follows
[E]: of the model's twelve invariants, exactly **two are computationally TESTED** (both about
evidence combination, both from a single recorded experiment); **eight are READ** — verified
against corpus sources by systematic reading, which establishes that the corpus asserts them and
argues them, not that any experiment confirmed them; and **two are REQUIRED-BY-COHERENCE** — they
entered by ruled repair after a falsification pass showed the model needed them (the ladder's
no-skip axiom, and the governed-policy-change invariant). Concept-level content is graded in the
same spirit: one concept **TESTED** outright (Evidence — the EXP-01 experiment), one
TESTED-within-scope (the knowledge state, exercised by a fifty-attack audit that calls itself "not
a proof"), one COMPOSITION (Authorization), one carried partly as SPECIFIED (the action loop), one
held as a historical-term ruling (Ω), and the rest READ. *(This census was corrected under GN-45
after the hostile pass caught its first version omitting Evidence's TESTED grade — finding AF-F-7.)* [FA] The book states
the grade wherever it teaches the element — that discipline is itself L2 content (invariant I-8:
no architectural assertion without evidence), and Part II.2 teaches the grading system in full.

## 4 · L3 — the repository architecture — *status: fact [FA←E]*

[FA←E] The Reference Architecture v1.0 realizes L1 by construction: **each article maps to one
architectural boundary, one kernel service, and one forbidden flow the design intends to make
structurally impossible** — READ-grade: the impossibility is the PROPOSED design's claim about
itself, untested against the articles by any pass (3C tested the repository against v0.2, not the
RA against L1). *(Qualifier added under GN-45, AF-F-20.)*
Its eleven kernel services are *gates and records, never reasoners* — an Identity Service that
only assigns and preserves identity (never derives it from similarity), a Verification Gate that
is the *only* admission path into knowledge, a Decision Interlock that terminates every knowledge
flow at recommendation, an Authority Service that records assignments and never lets content
self-authorize, a History Service that only appends. Engines (validation, reasoning,
contradiction/debate, fallacy detection, intent classification, and the LLM as a governed
candidate-generator) *propose*; the gates *admit*; nothing in the engine layer can reach the
record directly. [E] L3's own status line matters: it is PROPOSED in its lane — a realization
design, not ratified law — and a later refinement (v1.1) exists on a parallel authorized branch,
deliberately deferred (Chapter IV.3).

## 5 · L4 — the executable implementation — *status: evidence [E], stated at true strength*

[E] What actually runs today, exhaustively: a **session bootstrap** that resolves a process's
lane, role, and authority from the governed record, refuses to conflate six things a process might
mean by "I may act" (identity ≠ role ≠ eligibility ≠ authorization ≠ ownership ≠ continuation),
and **fails closed** — every unresolved fact yields *not authorized*; a read-only presenter of the
adopted operating model; observation-pipeline diagnostics whose doc-comment is practically an L1
quotation ("it checks and reports — it never installs, never repairs"); and their tests, plus one
experiment artifact (the EXP-01 CSV, whose reliability story Chapter III.5 tells). [E] **No L2
formal object is implemented**: there is no Zero engine, no EC deriver, no ladder datatype, no DC
executor. The conformance pass fixed this as finding CF-015, and the ruled conclusion is the only
honest one: substantial evidence for the boundaries and governance principles; implementation-level
correspondence for the formal layer not established. [FA] The book repeats that sentence wherever
a reader might otherwise infer running machinery. Nothing in Part III describes software that
exists, unless it says so.

## 6 · L5 — the governance record — *status: fact [FA←E]*

[FA←E] Authority in KnowledgeOS is not a property of content; it is a property of **acts** — and
L5 is where the acts live. Every promotion in the programme's own history was an explicit recorded
ruling: the model became authoritative by ruling; its repairs were applied by ruling; the final
architecture, the book's design, this book's production — rulings, each identified, each dated,
each with named scope and named exclusions. [E] The lived record even demonstrates the layer's
discipline under stress: an implemented-and-green asset was held out of adoption *solely* because
independent verification had not yet occurred; adoption and authorization were decided as separate
human acts; the one occasion a recommendation was treated as a ruling was caught, corrected, and
preserved as a named error. [IN] L5 is the layer that makes the other four honest: L1 binds
because it was ratified (an L5 act); L2 is authorized (an L5 act); L3 proposes *to* L5; L4's
strongest code is precisely the code that consults L5 before acting.

[E] Because this book is itself an L5-governed artifact, its own chain is a legible worked example
of the layer. The model was authorized by an explicit ruling after a falsification pass; a
conformance pass then tested the repository against it under a pre-approved scope plan; its
findings were dispositioned one by one; a historical-archaeology phase ran under its own
authorization with its own exclusion rules; the final architecture was ratified determination by
determination; the book's design was ratified separately from its production, its production
separately from its acceptance, and its acceptance only after an independent reviewer had found —
and a further ruling had corrected — four defects the producer's own review missed. At no point
did completing work confer authority on that work; at every gate, authority arrived as a dated,
scoped, human act, or the work waited. [IN] That is Article 3 scaled up from claims to programmes:
*content never self-authorizes* — not even the content of the governance process itself.

## 7 · Outside all layers: the historical corpus — *status: fact [FA/H]*

[FA] Six hundred eighty-eight documents of exploration — the measure-theory crisis, the lens
readings, the question series, the 158 steps — stand outside the layer stack entirely. [H/E] The
archaeology established the fact that fixes their status: the corpus contains **not one externally
ratified decision**; every act inside it is author-level exploration (finding AF-009). [FA] So the
corpus is *evidence of discovery* — Part I mines it as history, the evidence-maps cite it with
dates — and *authority over nothing*: no sentence of this book, and no element of the
architecture, is true because the corpus says so. Things became architecture by leaving the
corpus through L5's gates.

[E] It is worth seeing the exit paths concretely, because they differ. The **status ladder**
left the corpus by reconstruction-and-authorization: it was read out of one step document, carried
into the synthesis model, survived a falsification pass (which re-typed one of its statuses), and
was authorized by ruling. The **dependency-first invariant** left by experiment: a recorded
adversarial test gave it the model's strongest grade before any ruling touched it. The **eleven
articles** left by selection: a governed compression of twenty-nine research rows, ratified the
same morning. And some things never left at all: eight competing kernel formulations, a
complex-number knowledge model, an entire measure-theoretic foundation — all still in the corpus,
all still citable as history, none of them architecture. [IN] The corpus is not an archive of the
architecture; it is the architecture's *rejected and accepted drafts*, and the difference between
those two words is always an L5 act.

## 7a · How to read the rest of Part III — *status: editorial*

[EDITORIAL] Each following chapter descends the model from its owner outward — Knower and frame
(III.2), state and world (III.3), the gap that drives inquiry (III.4), evidence (III.5), admission
(III.6), decision and action (III.7), self-governing policy (III.8) — then steps back for the
kernel traditions (III.9) and the consolidated invariants (III.10). Every section of every chapter
opens with a status line; definitions appear in blocks that carry their source grade; a single
running example (an election-result certification) threads the chapters, always in three voices —
conceptual model, architecture, implementation honesty. Where a chapter reaches one of the
architecture's twelve open questions it will say so in place; none is resolved anywhere in this
book, because their openness is part of what was ratified.

## 8 · The inter-layer relations, with their evidence — *status: mixed, per relation*

| Relation | Status |
|---|---|
| L2 does not contradict L1 | [E] tested: the conformance pass found no CONTRADICTS relation anywhere |
| L2 realizes L1 object-by-object | **[U] NOT ESTABLISHED** — an honest gap, held open on purpose (OQ-2 adjacent) |
| L3 realizes L1 | [E] by construction (the RA's article→boundary→service mapping, III.9) |
| L4 realizes fragments of L3/L5 | [E] fragmentarily: the authorization boundary only (CF-007 vs CF-015) — *(row corrected to FA-1's ratified wording under GN-45, AF-F-10)* |
| L5 enacts L1's Articles 3–4 | [E] operationally: separate acts, fail-closed code, no-skip practice |
| corpus → any layer | only via an L5 act; never directly [FA] |

## 9 · A worked placement — one object through five layers — *status: interpretation [IN], marked*

[IN] Take **the AcceptancePolicy** — the object whose mis-layering caused more recorded trouble
than any other. As *content*, a policy draft is L2 material: a knowledge item at rest inside K_t,
discussable and revisable like any claim. As *the governor of admission*, the policy-in-force is
an L2 object with an L1 shadow: Article 3 forbids it to self-authorize, and invariant I-11 forbids
it to change without a governed, versioned decision — which is an L5 act, executed through L3's
governance boundary. If anyone ever implements the admission machinery, the enforcement will be L4
code — and the conformance profile predicts exactly what that code must do first: consult the
record (as the bootstrap already does for authorization). One object; five layers; and the failure
the layering prevents is the programme's most-sighted defect — the policy that governs while
nothing governs it (four independent sightings; Chapter III.8).

[IN] A second, quicker placement sharpens the method. Take **a claim produced by a language
model** — say, a generated summary asserting that a rollback was verified. At L4 it is a string an
engine emitted. At L3 it is a *candidate at the mouth of the Verification Gate*: it cannot touch
the record; it carries captured provenance (prompt, context, model, timestamp) or it is not even a
candidate. At L2 it would enter the ladder as a Candidate and could rise, on evidence, as far as
Accepted — and no further, by any amount of evidence. At L1, three articles have already
constrained every step: generation is not justification (6), the output is not reality (6.5), and
whoever relies on it must remain answerable (10). And at L5, if anyone is to be *bound* by the
claim — if it is to become Committed — a recorded human act must say so (A6). [FA] Nothing in that
chain is hypothetical except the L2/L3 machinery's implementation; the constraints are ratified,
and the one place the chain runs as code today is the authorization step. The example is the
book's whole method in miniature: place the object, name each layer's contribution, and mark what
exists versus what is specified.

## 10 · Conclusion

[FA] The five-layer separation is the architecture's answer to its own history: keep prohibitions,
descriptions, realizations, behavior, and authority in different places, and make every crossing
an explicit act. [U] One relation in the stack is deliberately open — object-level realization of
the constitution by the formal model — and Chapter III.9 ends at exactly that edge. Everything
else in Part III now descends the stack from its owner: the Knower.
