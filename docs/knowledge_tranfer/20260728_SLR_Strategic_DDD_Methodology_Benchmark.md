> **RAW SUPPORTING MATERIAL — NOT A GOVERNED ARTIFACT.** External systematic-literature-review output (Perplexity, commissioned by the PA, 2026-07-28), preserved verbatim for its designated points of use: **(1)** input to the Method Certification Assessment (MCA) — its evidence gradings bear directly on the novelty/maturity questions; **(2)** the **post-M8 formal benchmarking exercise** (PA cadence decision: no literature pause before M6; benchmark after M8, comparing an *executed and reviewed* methodology against published work). Per the artifact-ingestion rule: this file is input; any governed use produces a new assessed artifact. Its claims are evidence-graded by its author, not verified against sources by this repository.

---

# Systematic Literature Review — Strategic DDD Methodology vs State of the Art (Perplexity, 2026-07-28)

## Executive Summary

The literature strongly supports strategic DDD as a way to discover bounded contexts, model context relationships, and align software structure with organizational boundaries, but it does **not** generally define a formal discovery pipeline with certification, configuration control, or method lifecycle governance. Event Storming, Context Mapper, and domain storytelling all support structured discovery, yet they are usually presented as facilitation/modeling techniques rather than as auditable commissions with formal review gates.

The strongest evidence against the proposal as "already established" is that current DDD research still reports methodological gaps: inconsistent understanding of artifacts, need for better application-process support, and lack of standardized evaluation indicators.

## Research-Question Findings (RQ1–RQ10, evidence-graded by the SLR's author)

- **RQ1 (Strategic discovery processes):** structured methods exist but are fragmented (Event Storming — Strongly Supported · Context Mapper — Strongly Supported · Domain Storytelling — Partially Supported · a unified staged pipeline — Weakly Supported).
- **RQ2 (Method vs execution):** the SDM/EOP split is a plausible engineering abstraction; **not a standard DDD separation** (Weakly Supported).
- **RQ3 (Governance):** review gates/certification/configuration control are established in EA/TOGAF/process standards, **largely absent as native DDD constructs** — the governance layer is imported from broader disciplines.
- **RQ4 (Discovery as hypothesis testing):** DDD discovery is mostly consensus/facilitation-driven; explicit falsification and competing hypotheses are **a meaningful extension, not established doctrine** (Weakly Supported).
- **RQ5 (Evidence-based strategic modeling):** emerging — recent empirical DDD work calls for evidence and better evaluation (Partially Supported); full falsifiability as a norm — Weakly Supported.
- **RQ6 (Methods as knowledge assets):** reusable modeling artifacts established (Context Mapper); certification/versioning/lifecycle of *methods themselves* — Weakly Supported in DDD, more common in quality/process management.
- **RQ7 (Process maturity):** CMMI/ISO maturity models established; a DDD-specific method lifecycle (Candidate→…→Institutionalized) has **no direct analogue found**.
- **RQ8 (Knowledge engineering):** governed knowledge assets are normal KE themes; exact equivalents of certified discovery methods in the DDD corpus — Weakly Supported.
- **RQ9 (AI-assisted strategic DDD):** LLM-assisted architecture support is active but early; **AI-backed discovery with formal governance/certification appears ahead of the current literature** (Partially Supported).
- **RQ10 (Novelty, conservative):** *Established:* bounded contexts, context maps, subdomain analysis, workshop discovery, Context Mapper tooling. *Variations:* language-fracture boundary identification; AI as review aid. *Potentially novel combinations:* SDM/EOP separation · discovery as falsifiable commission with competing hypotheses · certification/configuration-control/lifecycle governance applied to strategic DDD methods · DDD discovery + process maturity + KE-style asset governance. *Likely research contributions:* a discovery-evidence/boundary-confidence taxonomy · an auditable boundary-hypothesis-testing workflow · a governance model for AI-assisted DDD reviews · a method maturity model for strategic DDD.

## Comparison Matrix (as graded by the SLR)

| Proposed element | Literature status |
|---|---|
| BC discovery workshops | Established — Strongly supported |
| Context mapping / reverse engineering | Established — Strongly supported |
| SDM vs EOP separation | Uncommon — Weakly supported |
| Governance gates / certification | External to DDD; stronger in EA/process standards |
| Falsification / adversarial discovery | Emerging extension — Weakly supported |
| Evidence-based strategic models | Emerging — Partially supported |
| Method lifecycle / config control | Process-engineering idea — Weakly supported |
| AI-assisted discovery governance | Early research — Partially supported |

## Contradictions and Risks (the SLR's cautions)

- **Over-formalization risk:** governance-heavy process may lose DDD's workshop-driven exploratory character.
- **Certification-bureaucracy risk:** the layer must stay tied to measurable evidence or it adds cost without boundary quality.
- **Boundaries are provisional:** the literature treats them as revisable modeling choices, not validated facts.
- **AI role:** better as review aid than autonomous design authority.

## Recommendations (the SLR's)

Keep the discovery core grounded in established DDD · treat governance as an outer layer borrowed from architecture/process management, not intrinsic DDD · frame falsification as a researchable extension, not existing doctrine · AI as exploratory/review tool with human gates · if published, position as a research program/method proposal, not "the" DDD way.

## Final Assessment (the SLR's)

**Established:** bounded contexts, context maps, subdomain analysis, workshop discovery, reverse-engineering tools. **Engineering practice rather than DDD:** certification, configuration control, review gates, lifecycle governance, maturity modeling. **Original combinations:** SDM/EOP separation, governance-backed discovery commissions, falsifiable boundary testing. **Needs empirical validation:** whether these controls improve boundary quality, speed, stability, alignment. **Should be simplified:** any claim that governance is native to DDD.

---

*Provenance: Perplexity SLR commissioned by the PA (RQ1–RQ10 structure, evidence-graded, challenge-not-confirm framing) · relayed 2026-07-28 · PA cadence ruling attached: execute M6 first; formal benchmark after M8 ("you'll have something many papers don't: operational evidence") · filed per the artifact-ingestion rule as raw supporting material.*

---

# PA Meta-Review of the SLR (2026-07-28 — supporting material, appended)

**Scores:** coverage 9/10 · DDD understanding 8.5/10 · critical thinking 8/10 · research rigor 7.5/10 ("structured narrative review rather than a true SLR") · novelty caution 9/10.

**Endorsed:** governance correctly identified as external to classic DDD · no false "DDD already does this" claims · the real novelty located in the **combination** (Strategic DDD + architecture governance + knowledge engineering + process management + evidence-based discovery), not in any single technique.

**Three gaps identified for the post-M8 benchmark:**
1. **Method ≠ process under-explored** — SDM describes *how discovery should occur*; EOP describes *how one commission executes*; adjacent disciplines (scientific method, quality management, organizational learning) make similar distinctions the SLR did not investigate.
2. **Governance integration patterns unexplored** — the SLR found the *source* of governance concepts (EA/TOGAF/ISO/CMMI/peer review) but not the *patterns* by which other disciplines integrate governance with a technical methodology.
3. **Epistemology omitted (the biggest gap)** — falsification, competing hypotheses, evidence, confidence, and certification are epistemological concepts; the missing research question is *"what constitutes evidence for strategic architectural decisions?"*, drawing on philosophy of science, design science research, evidence-based software engineering, and architectural decision-making.

**Three extension RQs staged for the post-M8 benchmark:** **RQ11** — Evidence-Based Software Engineering (architectural discovery as an evidence-based activity) · **RQ12** — Design Science Research (artifact creation/evaluation/iteration/utility/rigor parallels) · **RQ13** — Knowledge Governance (organizational knowledge, reusable methods, lifecycle governance).

**Canonical positioning statement (adopted for any future external positioning):** *"The contribution appears to be an original synthesis of established ideas from multiple disciplines, rather than a wholly new theory within Domain-Driven Design itself."* The work is best understood as a **cross-disciplinary engineering methodology**: Strategic DDD supplies discovery; EA/process engineering supplies governance; knowledge engineering supplies method lifecycle; evidence-based reasoning supplies validation.

**Research direction ruling:** future literature work broadens into EBSE / DSR / knowledge governance — **not** further Strategic-DDD literature refinement.

---

# PA Research-Framing Refinement (2026-07-28 — supporting material, appended)

**The central research problem, stated:** not "how to do Strategic DDD" but —
> **"How can strategic architectural discovery become a repeatable, evidence-based, and governable engineering activity?"**
DDD is the **application domain**, not the whole contribution. A methodology is evaluated by its primary research contribution, not by listing its ingredients.

**Three deeper questions staged for the post-M8 work:**
1. **What kind of methodology is this?** Classify before comparing — engineering methodology · decision-support methodology · knowledge-governance methodology · design methodology — each carries different evaluation criteria.
2. **What is the unit of evaluation?** DDD literature evaluates *model quality* (contexts, alignment); this methodology evaluates **the quality of the discovery process itself** — which changes what counts as evidence.
3. **What evidence would validate it?** Measurable questions, beyond conceptual comparison: does it improve repeatability? · inter-architect agreement? · reduce unnecessary redesign? · improve decision traceability?

**Research roadmap (four bodies, adopted for the benchmark):** Strategic DDD (domain discovery foundation) · Software Architecture Governance (review processes, decision records) · **Design Science Research** (method construction & evaluation — the likely home discipline) · **Evidence-Based Software Engineering** (validation, reproducibility). Knowledge engineering supports representation/management of methods rather than serving as the central framing.

**Defensible framings (superseding-refinement of the earlier positioning statement — narrower, easier to defend):**
> *"An evidence-based governance methodology for Strategic Domain-Driven Design"* — or — *"A governed engineering methodology for strategic architectural discovery."*
Avoid: "a new theory of DDD."

**The maturation marker (PA):** the discussion has shifted from *"is this still DDD?"* to *"what discipline does this methodology belong to, and how should it be evaluated?"*

---

# PA Final Research-Track Refinements (2026-07-28 — supporting material, appended; the track's closing state)

1. **Concern-ownership framing (adopted):** the methodology *"integrates multiple established bodies of knowledge, each governing a distinct architectural concern"* — Strategic DDD → discovery · architecture governance → decision control · EBSE → evidence evaluation · DSR → method evaluation · knowledge engineering → knowledge lifecycle. **Not the sum of five borrowed disciplines: the integration itself requires architectural design.**
2. **The integration is the contribution:** distinguish *source disciplines* (where concepts originate) from *integration architecture* (how they combine coherently). **Meta-RQ staged above RQ11–13:** *"How should multiple engineering disciplines be integrated into a coherent methodology without losing the integrity of each discipline?"* — a method-engineering question (boundary interactions · conceptual conflicts · integration principles).
3. **"Home discipline" corrected to hypothesis-grade:** Design Science Research is *"a promising candidate framework for constructing and evaluating the methodology — to be assessed during the post-M8 benchmark"* — not an established conclusion.
4. **Terminology freeze from M6 onward:** the MCR→MCA rename was a considered decision; after M6 begins, **no concept renames absent a genuine execution-revealed semantic problem** — vocabulary must not keep evolving while the methodology freezes.
5. **Readiness verdicts (PA):** governance READY · methodology READY FOR EXECUTION · research framing READY FOR EMPIRICAL VALIDATION · academic positioning READY **as working hypothesis, not final claim** — M6–M8 evidence strengthens, revises, or narrows it.
6. **The transition marker:** the dominant question moved from *"what should the methodology look like?"* to *"how will we know whether the methodology works?"* — the shift from conceptual architecture to research methodology; the conceptual phase is mature enough to be exercised rather than refined.
7. **Caution on analogy-grade claims (applies to all supporting material in this file):** statements like "this aligns with provenance research" are informed interpretations unless tied to specific papers/sections — treat sourcing conservatively; the SLR is a structured narrative review, not a true systematic one.
8. **Open classification question staged for the benchmark:** what *kind* of methodology is this (engineering / decision-support / knowledge-governance / design), and — in Strategic-DDD spirit — **what is the bounded context of each M-artifact itself** (e.g., M0: discovery vs terminology vs evidence-collection artifact)? Classification precedes comparison.
