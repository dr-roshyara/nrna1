# Session-2 Review — S1-F020

## 1 · Source artifact
`session1/S1-F020-adr-kos-kernel-001-knowledgeos-knowledgecore-kernel-separated.md`

## 2 · What Session 1 claims
A drafted ADR (**PROPOSED**, never adopted) separates `KnowledgeOS ⊃ KnowledgeCore ⊃ Kernel`, defines the Kernel **by a question and a derivation rule** rather than a member list, carries an anti-absorption **consumption rule**, treats the falsification as settled context, and names a **four-way vocabulary collision on the word *Kernel* itself**.

## 3 · Evidence class · Question type · Altitude
`P7` **ARCHITECTURE PROPOSAL** — the only one in the corpus. Question types: **BOUNDARY** (altitudes), **METHODOLOGY** (derivation rule), **GOVERNANCE** (consumption rule), **SEMANTICS** (the collision). Altitude: scope clarification, explicitly *"clarifying, not redesigning."*

## 4 · Level 1 — extraction fidelity
**UNVERIFIED.** ✅ Session 1's standing discipline here is its most careful: *"PROPOSED, not adopted… does **not authorize implementation**"*, plus the census **T-7** flag that this document is among three *"at highest risk of being read as architecture."* Repeated in four places. That guard is warranted and I adopt it: **a well-formed ADR inside a research corpus is the artifact most likely to be mistaken for a decision — including by me.**

## 5 · Level 2 — analytical validity

### `.1` The four-way *Kernel* collision explains dissolution mechanism #8 — **and is a possible independent arrival**
The ADR names four competing senses in use: *the wider ecosystem* · *KnowledgeCore as bounded context* · *the constitutional/invariant altitude* · *the smallest executable/authoritative boundary*.

Sort the formulations by sense: `S1-F009`'s *boundary* and `S1-F019`'s *enforcement mechanism of the boundary* are **sense 3 versus sense 4** — the constitutional altitude versus the smallest executable boundary. That is precisely the pair `S2-R-F019.5` recorded as an apparent contradiction it could not score.

⚠ **Independence:** I wrote `S2-R-F019.5` from `S1-F009` + `S1-F019` only, before reading `S1-F020`. The ADR is 2026-08-23; my review 2026-08-28. Different routes to the same structural explanation. **POSSIBLE INDEPENDENT ARRIVAL** — the register's second, and I claim no more.

**Consequence:** several of the eight dissolutions may share one underlying cause. The corpus was not holding eight rival theories of one object; it was using one word for four objects.

### `.2` `KnowledgeCore` is **not** added by the ADR — Session 1 is mistaken here
Session 1: *"the ADR **adds** an explicit named middle layer (`KnowledgeCore`)."*

Verified in law (l. 133): *"**CORE** | **KnowledgeCore** — identity-bearing justified epistemic states and their lifecycle | the only context whose removal changes what the system *is*. **Nothing else is core**."*

**`KnowledgeCore` is already v1.1's core bounded context**, named, defined, and marked as the only core. The ADR restates it; it does not add it. A small correction with a real consequence: the ADR's structural contribution is the **three-altitude separation and the consumption rule**, not the middle layer, which law already had.

### `.3` The consumption rule is the sharpest anti-absorption statement in the corpus — **CONFIRMED**
*"External contexts may produce expressions, interpretations, candidates, evidence, decisions, projections… **Those contexts do not become Kernel responsibilities merely because their outputs are consumed by KnowledgeCore.**"*

✅ Session 1 is right that this *"blocks the mechanism by which every capability list grew."* It names the growth rule directly: *the Kernel uses X, therefore X is the Kernel's*. Every member list in `S1-F003`–`S1-F019` is an instance.

### `.4` Definition by question rather than enumeration — **CONFIRMED as the constructive answer**
*"What is the smallest authoritative boundary that must exist to preserve KnowledgeCore's constitutional invariants?"* plus *"The Kernel SHALL be **derived from** domain invariants; consistency requirements."*

This is the constructive counterpart to `S1-F007`'s god-object diagnosis and `S1-F016`/`F017`'s falsification. ⚠ But note the same limit as `S2-F024.1`: a derivation rule that says *derive from the invariants* still requires knowing **which** invariants, and the ADR does not name them. It is a **better-formed schema**, not yet a criterion — and, unlike F009's, it is honest that derivation remains to be done (*"which invariants does the derivation rule actually yield?"* is Session 1's own open question).

### `.5` It acknowledges the falsification that `S1-F019` ignored — **CONFIRMED**
Written ~30 minutes after F019, and it treats the falsification as settled context. Session 1: *"within one evening the corpus contains both a document that ignores the falsification and a proposal that treats it as established context."* ✅ Recorded, not reconciled — correct.

## 6 · Level 3 — implementation relevance

| Candidate | Verdict |
|---|---|
| Three-altitude separation | **DO NOT IMPLEMENT (already protected)** — `KnowledgeCore` is law (l. 133); §16 supplies the altitudes |
| The **consumption rule** | **PRESERVE AS KNOWLEDGE ONLY**, strong form. It is a **review policy**, not a capability — an instrument whose entire function is to produce `DO NOT IMPLEMENT` verdicts, alongside `S1-F005`'s separation rule and `S1-F011`'s corrected asymmetry. ⚠ Trigger, as at `S2-R-F011`: if a KnowledgeOS layer is ever built, this is fitness-test material |
| Definition-by-derivation | **PRESERVE AS KNOWLEDGE ONLY** — a method, and per `.4` an incomplete one |
| The ADR as a decision | **DO NOT IMPLEMENT** — PROPOSED, unadopted, non-authorising. Adoption is **adjudication. NOT ADJUDICATED.** |

## 7 · Conclusion vs justification
Three-altitude separation: **conclusion survives, novelty claim fails** (`.2`). Consumption rule: **both survive.** Definition-by-question: **conclusion survives; "answers the enumeration failure" is premature** until the invariants are named.

## 8 · Standing hypothesis (§17) — per `X-004`
**CONSISTENT.** *Smallest authoritative **protection** boundary* is preservation vocabulary.

## 9 · Open questions · Confidence · Status
Was the ADR ever ruled on (no adoption record in `kernel/`) · which invariants does the derivation yield · does `KnowledgeCore` here match `S1-F007`'s *Knowledge Context*.
**Confidence:** high on `.1`–`.3`; `.2` verified. Level 1 UNVERIFIED. **Status: OPEN.**

## 10 · Final assessment
The corpus's only architecture proposal, and its most structurally useful artifact: the **four-way collision on *Kernel*** offers a single explanation for disagreements I had been dissolving one mechanism at a time. Session 1's standing discipline around it is exemplary; one factual claim — that the ADR adds `KnowledgeCore` — is wrong, and law already carries it.
