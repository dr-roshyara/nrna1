# Session-2 Review — S1-F002

## 1 · Source artifact
`session1/S1-F002-ubiquitous-language-instability.md`

## 2 · What Session 1 claims
The corpus's ubiquitous language is unstable: `Rule` is used in **seven** senses, and `Knowledge` itself is recorded as having *"boundaries unclear."*

## 3 · Evidence classification
The sense-count is **FACT about the corpus** if the senses were individuated by a stated criterion — **not verifiable from here** (`S2-F021.1`). *"Instability"* as a diagnosis is **INFERENCE**.

## 4 · Question type
**SEMANTICS**, unambiguously. Not boundary, not mechanism. This matters because `S2-F017` Part 2 shows a *semantics* finding was never brought to bear on a *boundary* count it directly undermines.

## 5 · DDD / architectural altitude
**Vocabulary** — the layer above every DDD category and below none of them. Ubiquitous language is a modelling discipline, not a component.

## 6 · Provenance assessment
Session 1's own measurement. **POSSIBLE INDEPENDENT ARRIVAL.** ⚠ `S2-F005` records that later artifacts' agreement with F002 is **restatement**, not corroboration.

## 7 · Zero-lens assessment
*"`Knowledge` boundaries unclear"* is **UNDETERMINED**, not *absent* and not *contested*. `S2-F006` establishes this is the **buried headline**: a system named for `Knowledge` recording that concept's boundaries as unclear outranks `Rule`'s overload, and the artifact leads with the lesser finding.

## 8 · Contradiction test
None alleged. None found.

## 9 · Convergence test
`S1-F002` is **upstream** of `S2-F017` Part 2 (`consistency`/`coherence` untested for synonymy) and of `S2-F022.2` (row-counting). Those are **applications** of F002's insight, not independent support for it.

## 10 · What survives
Both observations survive. `Knowledge`-boundaries-unclear survives as the more consequential of the two, per `S2-F006`.

## 11 · What is challenged
The **ordering** (`S2-F006`) — the headline is the weaker finding. And *"seven senses"* is exposed to `M-6`: a sense-count and a **site**-count are different quantities, and if two of the seven are one sense in two contexts the number inflates. Not testable from here; recorded as a question, not a defect.

## 12 · Implementation relevance

### Kernel test
*Remove vocabulary stability.* Nothing becomes technically impossible — ambiguity is a **human** failure mode. Models with overloaded terms run correctly and are reasoned about incorrectly. **Fails the Kernel test.**

### Decision
**PRESERVE AS KNOWLEDGE ONLY.**

### Engineering consequence
None. The remedy for an overloaded term is naming discipline in the model, and the remedy for *"boundaries unclear"* is modelling work — neither is software.

⚠ One conditional worth recording, because it is where the finding *would* become buildable: **if `Rule`'s seven senses ever share one persisted field or table**, the overload stops being a vocabulary problem and becomes a schema defect that silently serves seven requirements with one shape. No evidence of that exists. Trigger named; nothing to do now.

### What would be lost by not implementing
Nothing identifiable — which per §15 strongly supports the decision.

### Existing protection
None needed. This is a discipline, not a threat model.

## 13 · Open questions
Is *seven* a count of senses or of sites? · What would settle `Knowledge`'s boundaries — and is that Session 1's question or an adjudication question?

## 14 · Confidence
**High** on the classification and the decision. **Unverified** on the sense-count itself.

## 15 · Final Session-2 assessment
A sound and genuinely important finding whose real value is **diagnostic**: it explains why several later counts in the record are unreliable. Its own implementation consequence is nil, and that is the correct result — the most useful vocabulary finding in the corpus produces no software.
