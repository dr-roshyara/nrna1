# EPIC-002 Candidate Bounded Context Discovery Report

**Kind:** discovery artifact — Strategic DDD Phase 1. **Authority:** generated; never authoritative without ARB review. **This report is not an architectural decision.**
**ARB ruling authorizing this report:** the Cross-Disciplinary Evidence Consolidation Report and the Strategic Domain Discovery Report are both accepted. No new literature, no external assumptions, no repository knowledge outside those two artifacts (and their own inputs, `EPIC-002_Literature_Review.md` / `EPIC-002_Concept_Register.md`) influenced this analysis.
**Explicitly out of scope for this document (per instruction):** aggregates, entities, value objects, repositories, APIs, events, application services, infrastructure, packages, microservices, deployment diagrams, database schemas, IDDs, tactical DDD, a Context Map, a Relationship Map, partnership/ACL/Shared-Kernel/Published-Language patterns, team topology. Every candidate below is presented with its own confidence and counterarguments — **none is assumed correct.**

---

## 1. Responsibility Cohesion Analysis

Examining the five discovered responsibilities (R1 gather, R2 adjudicate, R3 custody, R4 contemporaneity, R5 separability) for what always co-occurs, what depends on what, and what evolves independently:

- **R2 depends on R1** in every discipline examined — adjudication never occurs without prior evidence collection. This dependency is one-directional and never observed reversed.
- **R2 and D1 are inseparable** — no source discusses adjudication without also discussing whether it could be automated; this is the tightest, most consistently co-occurring pair in the whole evidence base (11 families).
- **R3 and D3 are the same question from two angles** — "maintain custody" and "is custody preserved/displaced/split" are not two responsibilities but one contested responsibility whose very necessity is disputed (T1).
- **R4 feeds R2 but is not shown to require the same actor.** Every discipline that discusses both (AL/CL's Chenery doctrine, ES's commit-before-sample) treats record-fixing as occurring at or near the original act, while adjudication (courts, panels, verification processes) frequently occurs later and via a different kind of actor. This is a **volatility difference**: R4 changes per-transaction; R2 changes at a policy/procedural level. Per standard cohesion heuristics, responsibilities that change at different rates and via different actors are candidates for separate treatment rather than fusion — **this is an inference drawn from the discovered evidence, not itself sourced from any single paper.**
- **R5 is cross-cutting, not a discrete activity.** No discipline describes "separability" as something a distinct actor *does*; every source frames it as a property that must hold (or is shown to fail) within whatever activity handles evidence representation — it attaches to R1, R2, or R3 rather than standing alone.

**Conclusion (cohesion only, not a BC decision):** R1 and R2 are the two most independently well-formed responsibility groupings. R3/D3 forms a cohesive-but-internally-contested pair. R4 sits in a dependency relationship with R2 without clear evidence that they share an actor. R5 is not a standalone responsibility grouping at all.

## 2. Language Boundary Analysis

The ubiquitous language discovered previously shows **"evidence" itself is semantically overloaded** across responsibility groupings — a classic bounded-context signal (Evans: the same word meaning different things in different contexts marks a boundary):

- In **collection** (R1): evidence = raw, heterogeneous input material.
- In **adjudication** (R2): evidence = the material being weighed toward a determination.
- In **custody** (R3): evidence = a physical/digital object requiring an unbroken possession trail.
- In **contemporaneity** (R4): evidence = a record, fixed at the moment of creation.

Three further semantic discontinuities stand out:

- **"Custody" vs. "self-verification"** (T1) are two entirely different vocabularies — one of persons/objects/possession, one of mathematics/public verifiability — competing to describe the same underlying concern (undetected-tampering prevention). This is one of the strongest boundary signals in the whole discovery: it may indicate two genuinely different capability models, not one capability with two implementations.
- **"Adjudication/correction"** may itself split semantically: resolving a *content* dispute (what does the evidence show — courts, Hot Spots, declare-failure) reads differently from resolving an *authority-validity* question (has this record/instrument expired — the legislative sunset-clause candidate). The evidence does not force this split, but the language itself is not obviously unified.
- **"Contemporaneity"** splits between *record* (an evidentiary fact, ES/AL sense) and *model* (a narrative/design-time artifact, EventStorming/DDD sense) — flagged as unresolved in the Strategic Domain Discovery Report, and worth naming here explicitly as a candidate boundary signal rather than a settled single concept.

## 3. Capability Cluster Evaluation

Revisiting Clusters A–E from the Strategic Domain Discovery Report against the question: cohesive capability, multiple capabilities, accidental grouping, or implementation concern?

| Cluster | Evaluation | Reasoning |
|---|---|---|
| A — Collection & Aggregation | **Cohesive capability** | R1/K4 always co-occur across ES/PR/TE/DDD; no evidence supports further splitting |
| B — Adjudication & Correction | **Possibly multiple capabilities** | Language analysis (§2) and the sunset-clause D1 question suggest content-adjudication and authority-validity/expiry may be distinct; evidence does not force the split, but does not foreclose it either |
| C — Custody & Integrity | **Accidental grouping masking two competing alternatives**, not one stable capability | The literature disagrees on whether a custodial actor is even necessary (T1); better read as two alternative candidate capabilities than one |
| D — Contemporaneity & Record-Fixing | **Cohesive but dependent, not fused with B** | Volatility/actor-difference argument (§1) supports treating this as its own candidate feeding Cluster B, rather than part of it |
| E — Substrate/Argument Separation | **Implementation-adjacent cross-cutting concern, not a standalone capability** | No discipline frames separability as an activity performed independently; it attaches to whichever capability handles evidence representation |

## 4. Decision Boundary Analysis

- **D1** (automatic vs. discretionary adjudication) requires R2 and R1 as inputs; if the sunset-clause/authority-validity reading is in scope, it may also implicate R4. This decision boundary is exactly where Cluster B's candidate split (§3) would need to land.
- **D2** (retrospective reconstruction vs. contemporaneity violation) requires R4, and depends on whether R2 treats a reconstruction as legitimate input — revealing a genuine dependency between the Contemporaneity and Adjudication candidates without collapsing them.
- **D3** (custody preserved/displaced/split) requires only R3, in either of the two alternative readings from Cluster C — this decision *is* the boundary separating those two alternative candidates.

## 5. Domain Tension Assessment

| Tension | What it suggests |
|---|---|
| T1 — Custody vs. self-verification | Two **alternative candidate capabilities** (custodial vs. self-verifying integrity-preservation), not one capability with variants |
| T2 — Evidence/argument separability vs. collapse under compromise | A **cross-cutting constraint/quality attribute**, not a standalone capability (consistent with Cluster E's reclassification) |
| T3 — Automation vs. adjudication | A genuine **candidate capability-split signal** within Adjudication (content vs. authority-validity) |
| T4 — Contemporaneity ambiguity | A **collaboration/dependency boundary** between record-fixing and adjudication — coordinated, but not identical, capabilities |
| T5 — Plurality vs. operationalization | **Unresolved domain knowledge / measurement maturity gap**, not a boundary signal in itself |

## 6. Candidate Bounded Context Catalogue

*Every candidate below includes evidence, confidence, and at least one genuine counterargument. None is assumed correct.*

### Candidate BC-1 — Evidence Collection & Aggregation
- **Business purpose:** assembling heterogeneous evidentiary material from multiple non-interchangeable sources into a usable body of evidence.
- **Core responsibilities:** R1.
- **Ubiquitous language:** "Evidence (plural)," "evidence-family," "raw material."
- **Decisions it owns:** none directly — a notable finding in itself.
- **Capabilities:** C1.
- **Constraints:** K4 (operationalization gap — a documented maturity risk, not merely a hypothetical one).
- **Dependencies:** its output is consumed by BC-3 (Adjudication) and, on the custodial reading, by BC-2.
- **Evidence supporting existence:** 8 independent evidence families (P1) — the broadest base in the program.
- **Confidence:** HIGH cohesion; **LOW distinctiveness as a decision-owner**, since it owns no discovered decision.
- **Alternative interpretation / counterargument:** owning no decision is a real argument that this is an upstream, shared, implementation-adjacent concern rather than its own bounded context — not dismissed here, left as an open question for the ARB.

### Candidate BC-2 — Custodial Integrity Preservation
- **Business purpose:** maintaining an attributable, tamper-evident possession trail over evidentiary material so undetected tampering can be identified.
- **Core responsibilities:** R3 (custodial reading).
- **Ubiquitous language:** "Custody," "custody log," "custody-as-precondition," "detection vs. prevention."
- **Decisions it owns:** D3, on the custody-is-preserved reading.
- **Capabilities:** C3.
- **Constraints:** K3.
- **Dependencies:** consumes BC-1's output.
- **Evidence:** 3 families (ES, DF, and the custody-retained reading of E2E-VV — ElectionGuard's own spec/pilot).
- **Confidence:** MEDIUM — internally cohesive, but its necessity as a capability is directly disputed by BC-2-Alt below.
- **Alternative interpretation / counterargument:** see BC-2-Alt — this is not a footnote but a genuinely competing candidate.

### Candidate BC-2-Alt — Self-Verifying Integrity (alternative to BC-2, not additive)
- **Business purpose:** achieving the same undetected-tampering-prevention goal without a named custodial actor, via public/cryptographic self-verification.
- **Core responsibilities:** R3 (self-verification reading).
- **Ubiquitous language:** "public self-verification," "self-verifying," directly opposed to "custody."
- **Decisions it owns:** D3, on the custody-is-displaced reading.
- **Evidence:** the Ali & Murray academic framing (E2E-VV) — a genuine, surviving partial counterexample candidate; weakened by two refuted stronger versions (VMV/Selene; Helios-bulletin-board) and by hybrid deployed systems (STAR-Vote, Wombat) that retain paper custody as backup.
- **Confidence:** LOW/CONTESTED — this is T1 itself, presented as a candidate rather than resolved.
- **Relationship to BC-2:** these two candidates cannot both be adopted for the same evidentiary stream as currently understood — the literature has not resolved which (if either, or both for different streams) applies. **This choice is left open for the ARB, not pre-decided here.**

### Candidate BC-3 — Adjudication & Determination
- **Business purpose:** resolving contradictory, incomplete, or contested evidence into an authoritative determination.
- **Core responsibilities:** R2.
- **Ubiquitous language:** "Adjudication," "knowledge crunching," "declare-failure," "remediation," "Hot Spots."
- **Decisions it owns:** D1.
- **Capabilities:** C2.
- **Constraints:** K1.
- **Dependencies:** consumes BC-1's output; consumes BC-4's output (record) as its evidentiary basis.
- **Evidence:** 11 independent families — the strongest, most robustly cohesive candidate in the entire program.
- **Confidence:** HIGH cohesion, HIGH distinctiveness (clearly owns a decision).
- **Alternative interpretation / counterargument:** see BC-3-Alt.

### Candidate BC-3-Alt — Authority Validity / Expiry (possible split from BC-3, not confirmed)
- **Business purpose:** determining whether a legal/procedural instrument or record remains valid or has lapsed, as distinct from resolving a factual dispute about evidence content.
- **Evidence:** the single, narrow, jurisdiction-specific legislative sunset/expiry candidate (Australia's Legislative Instruments Act) — explicitly uncategorized in the underlying research.
- **Confidence:** LOW — this is the more speculative of the alternatives offered; resting on one narrow candidate is a real weakness, stated plainly rather than smoothed over.
- **Counterargument against splitting:** could simply be a specialized case of BC-3 rather than a separate capability — the evidence is too thin to force a decision either way.

### Candidate BC-4 — Contemporaneous Record-Fixing
- **Business purpose:** fixing a record, or its justification, at the time of the underlying act — prior to and independent of any later adjudication.
- **Core responsibilities:** R4.
- **Ubiquitous language:** "Contemporaneity," "commit-before-sample," "administrative record."
- **Decisions it owns:** D2.
- **Capabilities:** C4.
- **Constraints:** none directly catalogued beyond T4's own ambiguity.
- **Dependencies:** feeds BC-3.
- **Evidence:** 3 independent families — the narrowest positive base of any candidate, and the most internally ambiguous (T4 was attacked from both directions and resolved in neither).
- **Confidence:** MEDIUM cohesion, LOW settledness.
- **Alternative interpretation / counterargument:** could be collapsed into BC-3 as a precondition/input-quality gate rather than standing alone, since it owns only one decision and no capability clearly independent of feeding BC-3 — a real counterargument, not dismissed.

### Rejected (not proposed) — Evidence/Argument Separability
Per §3's reclassification, this is **not** proposed as a candidate bounded context. It is carried forward as a cross-cutting constraint (K2/T2) applying to however BC-1, BC-2/BC-2-Alt, and BC-4 are eventually realized — an explicit "accidental grouping / implementation concern" outcome, as the instructions anticipated could occur.

## 7. Confidence Assessment

| Candidate | Cohesion | Decision-ownership | Evidence breadth | Overall confidence |
|---|---|---|---|---|
| BC-1 Collection | High | None (owns no decision) | 8 families | Medium — cohesive but may not be its own context |
| BC-2 Custodial Integrity | Medium | D3 (one reading) | 3 families | Low/contested — competes with BC-2-Alt |
| BC-2-Alt Self-Verifying Integrity | Medium | D3 (other reading) | 1 family, partially refuted | Low/contested |
| BC-3 Adjudication | High | D1 | 11 families | High — strongest candidate in the program |
| BC-3-Alt Authority Validity | Low | D1 (narrow reading) | 1 family, uncategorized | Low — most speculative candidate |
| BC-4 Contemporaneous Record-Fixing | Medium | D2 | 3 families | Low/Medium — real counterargument for absorption into BC-3 |

## 8. Alternative Domain Models

*(Whole-landscape alternatives, not per-candidate footnotes — none preferred here.)*

- **Model A — Minimal (2 contexts):** everything upstream of adjudication (Collection + Contemporaneity + Custody, however resolved) merges into one "Evidentiary Record" context; Adjudication stands alone as the only genuinely decision-owning context. Rationale: R1/R4/R3 all feed R2 sequentially, and none of them independently owns a decision as strong as D1. Counter-evidence: T1's internal contest (custody vs. self-verification) is a genuine design dispute, not merely preparatory plumbing — folding it silently into one context would hide a real architectural choice.
- **Model B — Maximal (6–7 contexts):** every responsibility/decision becomes its own candidate — Collection, Custodial Integrity, Self-Verifying Integrity, Content-Adjudication, Authority-Validity/Expiry, Contemporaneous Record-Fixing, plus Separability carried as an explicit cross-cutting context rather than a mere constraint. Rationale: the language-boundary analysis (§2) shows real semantic discontinuity across nearly every pairing. Counter-evidence: several of these (Authority-Validity especially) rest on a single narrow, uncategorized literature candidate — over-fragmenting on thin evidence risks the opposite failure mode from Model A.
- **Model C — The catalogue in §6 (4 primary candidates + 2 contested alternative pairs):** sits between A and B — adopts cohesion-based groupings the relationship analysis already supports, while keeping the two genuinely contested tensions (custody-vs-self-verification; adjudication-vs-authority-validity) as explicit, unresolved alternative pairs rather than pre-resolving them toward either extreme.

**No model is recommended over another here.** Each is traceable to the same underlying evidence; they differ in how aggressively they resolve the program's two most contested tensions (T1, T3).

## 9. Strategic DDD Summary

- Six candidate bounded contexts (or context-pairs) discovered: Collection (BC-1), Custodial Integrity / Self-Verifying Integrity (BC-2 / BC-2-Alt, competing), Adjudication / Authority-Validity (BC-3 / BC-3-Alt, possible split), and Contemporaneous Record-Fixing (BC-4).
- One candidate — Evidence/Argument Separability — was evaluated and **not** proposed as a bounded context; carried forward as a cross-cutting constraint instead.
- BC-3 (Adjudication) is the strongest, most evidence-backed, most decision-owning candidate in the entire program. BC-1 (Collection) is broad in evidence but weak in decision-ownership — a genuine open question about its status as a bounded context at all. BC-2/BC-2-Alt and BC-3-Alt rest on the program's most contested and thinnest evidence respectively.
- Three whole-landscape alternative models (§8) were presented without a recommended choice — the evidence supports more than one defensible decomposition, and choosing between them is an ARB judgment this report does not make.
- **No Context Map, relationship pattern (Partnership/ACL/Shared Kernel/Published Language), tactical DDD, or implementation guidance appears anywhere in this document.**

---

**Stop condition:** this report is a discovery artifact, not an architectural decision. **STOP.** Wait for explicit ARB evaluation of the candidates, alternatives, and models above. Context Map, relationship-pattern decisions, tactical DDD, and Implementation Design belong to the next phase, only after the ARB approves or rejects these candidates.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md`, `EPIC-002_Strategic_Domain_Discovery.md` (and, transitively, `EPIC-002_Literature_Review.md` / `EPIC-002_Concept_Register.md`) · No new sources consulted.*
