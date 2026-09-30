# Candidate Patterns (from the pilot, F0001–F0005)

A pattern is not a theory. The "Alt." line on each pattern is its strongest competing reading.

---

## P1 — Evidence ceiling: a claim may not be stronger than its evidence

- **Observations:** O2, O9, O11, O15, O19, O30. **Sources:** all five files.
- **What they have in common:** a status or claim-strength value is bounded above by an evidence measure. The measure can be an instance count (n), a source grade ([FETCHED]/[CANONICAL]) or a verdict (INCONCLUSIVE ≠ PASS).
- **Formal reading:** for each proposition p, strength(p) ≤ g(evidence(p)), where g is monotone.
- **Uncertain:**
  - g is never specified. The thresholds are conventions: n≥2 for promotion, while the literature's reuse "rule of three" is stricter (F0002 L25).
  - The corpus breaks its own ceiling. "Method demonstrated portable" appears at n=1 (F0001 L257). The counts 17 vs 19 and "4 evidence gaps" vs the 3 listed are asserted without checking.
- **Alt.:** a writing style (caution) rather than a structural rule. What separates the two: does anything *enforce* the ceiling? O32 suggests nothing does.

---

## P2 — Two independent axes: evidence (provenance) vs authority (standing)

- **Observations:** O3, O12, O13, O14, O19, O20, O34. **Sources:** F0001, F0002, F0003, F0005.
- **What they have in common:**
  - Evidence alone never makes something authoritative: generated work is capped at Candidate, projections need attestation, and "engineering never accepts its own work".
  - Authority does not require evidence: a canonical map that has never been consulted, and a canonical rule the literature only partly supports.
  - So the axes are independent **in both directions**.
- **Formal reading:**
  - Two modal operators. `E p` means "p is evidenced to degree e"; `A p` means "p is ruled by an authority".
  - The corpus forbids the inference E → A. It observes A without E.
  - Promotion *rules* then add a coupling: A-promotion *requires* a minimum E (ES-006.1). Evidence is **necessary** for promotion but **not sufficient**.
- **Uncertain:**
  - F0001 does not use the provenance × standing vocabulary.
  - The corpus uses "authority" in two senses: as the product in "authority = provenance × standing" (F0002), and as one of the four partial orders (F0003). That is a possible homonym.
- **Alt.:** an ordinary separation of duties (four-eyes principle), not an epistemological structure.

---

## P3 — Knowledge state changes only through typed events; documents are projections

- **Observations:** O21, O25, O26, O27, O28, O31. **Sources:** F0003, F0004, F0005.
- **What they have in common:**
  - A state (a row, a bucket) changes only when an admissible event of a named type occurs: evidence lands, or an authority rules. It does not change on a schedule or by re-reasoning.
  - Demotion is also an event.
  - Views are derived and regenerable.
- **Formal reading:**
  - An event-sourced state machine: `state = fold(transition, events)`. The dashboard is `project(state)`.
  - DDD: a knowledge-item aggregate with the invariant "standing changes only through an AuthorityRuled event".
- **Uncertain:** whether modelling or review is an admissible event (C7).
- **Alt.:** version-control discipline described in DDD language.

---

## P4 — Artifact = method applied to a binding, plus evidence traces

- **Observations:** O4, O5, O34, and F0005 §7–9. **Sources:** F0001 and F0005 only. The pattern is **absent** from F0002–F0004.
- **What they have in common:** an artifact is split into a reusable part, a product-specific parameter and a product-specific history.
- **Formal reading** (λ-calculus / compiler):
  - METHOD is a **closed term**: it has no free variables bound to product constants.
  - A Tier-2 artifact has a product *constant* where a *parameter* belongs. SD-1 hard-codes "Certified Domain Knowledge Release v1.0".
  - Extraction is **λ-abstraction over the binding**, i.e. "replace SD-1 by a generic input contract".
  - A Tier-3 artifact is a term interleaved with its execution traces ("case law").
  - Why grep fails (O5): whether an occurrence is *bound* or a *constant* is a structural property, not a lexical one.
  - "KnowledgeOS creates PKS" then reads as a higher-order function: `KnowledgeOS : Method → Binding → PKS-frame`.
- **Uncertain:**
  - Whether the pattern is central or a local tool for the extraction question. Its disappearance from the three 2026-08-03 files counts against centrality: the 53-concern inventory in F0003 does not list it.
- **Alt.:** a documentation-hygiene rule (keep examples separate from rules).

---

## P5 — Realization levels: named → specified → implemented → exercised → independently replicated → enforced

- **Observations:** O6, O28, O32, O36, O37, and "designed ≠ demonstrated" (F0002 L25). **Sources:** all five files.
- **What they have in common:**
  - A function can exist at several levels of reality.
  - Most of the corpus's *corrections* separate two adjacent levels that had been merged: method vs bootstrap, capability vs runtime enforcement, specification vs implementation, informal vs formal vs canon-level traversal.
  - Names are also policed for implying a level that has not been reached ("Capability Runtime").
- **Formal reading:** a chain (or partial order) of realization, where each claim must name its level. It may be the "realization" order among F0003's four partial orders. That is an INTERPRETATION; the definition is not in the pilot corpus.
- **Uncertain:** whether "enforced" belongs on the same chain as "exercised", or is a separate axis (enforcement vs adoption).
- **Alt.:** simply the normal maturity levels L0–L4 (F0005).

---

## P6 — Self-application (reflexivity) and overclaim-then-correct

- **Observations:** O9, O10, O18, O26, O36, O38. **Sources:** all five files.
- **What they have in common:** the corpus applies its own rules to its own documents, and it repeatedly overclaims, then corrects by annotation, keeping both the claim and the correction.
- **Formal reading:** an append-only log in which corrections are new events, not edits. That is consistent with P3.
- **Uncertain:** whether the correction rate is a quality signal (active falsification) or a symptom (claims written faster than they are checked).
- **Statistician's note:** F0003 says "the falsification record is as long as the candidate record — the discovery was real, not confirmatory". The number of rejections is **not** a measure of how severe the tests were. The rejected claims were not pre-registered as predictions, so the ratio could reflect straw claims. This needs testing.

---

## P7 — The distinctive claims sit at n≈0; the operating claims sit at n≥1

- **Observations:** O7, O28, O38, and F0003 H-5, "the platform's distinctive bet". **Sources:** all five files.
- **What they have in common:**
  - Every edge that makes KnowledgeOS *different* from a rulebook is at or near zero: `generates → PKS`, canon-level evidence → platform, and a second product.
  - What is evidenced — governance, verification, guided engineering — is what a disciplined single-product programme would also have.
- **Uncertain:** the pattern may just reflect the programme's stage (bootstrapping).

---

## P8 — Inductive governance has no base case

- **Observations:** O8, O35, and F0005 L159. **Sources:** F0001 and F0005.
- **What they have in common:** a decision is admissible only if prior governed evidence or decisions exist ("never idea → ADR → implementation"). So the first decision cannot be admissible.
- **Formal reading:**
  - If the admissible set D is defined inductively *without an axiom*, its least fixed point is **∅**.
  - F0005's proposal adds an axiom schema: the stakeholder need is admissible at day zero, and the first decision is Provisional.
  - This is the classic bootstrapping or constitution problem: the first rule-maker is itself ungoverned.
- **Alt.:** Genesis is out of scope. F0005 itself calls "not the platform's responsibility" a legitimate answer.
