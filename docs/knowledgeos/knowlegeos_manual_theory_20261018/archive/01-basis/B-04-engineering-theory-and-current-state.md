# B-04 — Engineering developer guide: the Engineering Theory Reference and the Current-State Baseline

| Field | Value |
|---|---|
| Read (full) | `engineering/developer_guide/AI_Engineering_Platform_Engineering_Theory_Reference.md` (330 lines, "the *why*") · `engineering/developer_guide/AI_Engineering_Platform_Architecture_Baseline_Current_State.md` (805 lines, "the *what*") |
| Date | both commissioned 2026-07-27 by the Decision Authority; both DRAFT, `authority: generated`, "adopts nothing" |
| Standing | engineering background, **not** KnowledgeOS corpus |
| Labels | [SRC] stated · [I] interpretation · [?] open |

## 1. Engineering Theory Reference (the platform's "why")

| # | Finding [SRC] | Anchor |
|---|---|---|
| T-1 | The governance corpus **names almost none of the canonical software-engineering laws** (a few law-family terms appear once or as false matches) **and embodies most of them**: of about 20 examined, 14 are embodied as controls, 2 are explicit (AIP-05 Separation of Duties, AIP-06 Least Authority), 1 is inverted by design (Postel), 1 is confirmed only empirically (Hofstadter) | §1 |
| T-2 | The grounding is **"empirical and home-grown"**: rules were derived from lived incidents (the claude-flow autopsy, the fabricated-metrics finding, the session-log overwrite, the TDD breach) | §1 |
| T-3 | **Usage rule:** *"Engineering laws justify principles. Principles justify governance. Governance justifies architecture. Architecture constrains implementation. Laws never directly govern implementation."* | §2 |
| T-4 | Laws are **"empirical regularities and design heuristics, not theorems"**; they corroborate and do not command | §2 |
| T-5 | **Goodhart** is the most thoroughly embodied law: the rejected "truth score", the stop on persisting scores, the metric freeze ("the measurement system must stay simpler than the decisions it supports") | §4.7 |
| T-6 | **Popper:** falsifiability (a recorded RED run), attempt-to-reject, four-outcome verdicts (CONFIRMED / FALSIFIED / INCONCLUSIVE / EMERGENT) | §4.15 |
| T-7 | **Chesterton extended to absence:** "the absence of an architectural element is a decision, not a default" (the ASP principle) | §4.17 |
| T-8 | **No SE law found for:** AIP-11 append-only history (its grounding is audit-ledger practice, *"the election-audit principle"*) and AIP-10 assertion integrity (scientific-record practice: *"decisions are performative acts of the authority, documents are constative records of them"*) | §5 |
| T-9 | **Where the platform is ahead of the canon:** the Authority Boundary as an **unconstructible state**; standing **epistemic-status labels** (comparable to GRADE in medicine); **"No is the healthy answer"** as a health signal | §6 |
| T-10 | **Home-grown candidate laws:** Architectural Evolution; the **rejection-path law** ("every architectural concept must have an explicit path by which it can be rejected", rated *substantially novel*); measurement law; capability-cost law; axiom chain "No Product Need → … → No Automation"; first-class-element law | §7 |
| T-11 | **Reserved slot:** "Principle = research question"; whether a theory layer should exist is *deferred and evidence-gated*; "no X needed" is a legitimate outcome | §8 |

## 2. Current-State Baseline (the platform's "what", as of 2026-07-27)

| # | Finding [SRC] | Anchor |
|---|---|---|
| S-1 | *"an engineering governance architecture, not a software system"*: no orchestrator, workflow engine or state machine | §1 |
| S-2 | Inferred vision: *"make AI-assisted engineering deterministic, auditable, reproducible, traceable, and maintainable — without ever transferring engineering authority to the AI"* | §3 |
| S-3 | **Dominant quality: epistemic discipline**, rigorously separating what is decided, proposed, candidate, hypothesis and observed. **Dominant fragility:** the evidence-producing instrument (gate runner C3/AST-010) was never built | §1, §20 |
| S-4 | **Specified vs built:** constitution complete but PROPOSED; 1 of 7 Tier-1 gates; 0 of 17 fitness functions; own verdict *"FUNCTIONALLY STABLE — CONSTITUTIONALLY INCOMPLETE"* | §1, §20 |
| S-5 | **Five coexisting status vocabularies that never collapse:** adoption · rule status · candidacy · verdicts · authority | §17.1 |
| S-6 | **Self-caught violations** in all four role directions (Specification, Execution, Verification, Decision Authority) | §12.4 |
| S-7 | **A/B/C/D classification:** the default class is *observation*; praise, suggestion and inference cannot create governance | §8.1 |
| S-8 | **Knowledge storage and promotion ladder:** Research → Pilot → Qualification → Standard; "no X needed" is a success outcome | §9 |
| S-9 | **Surviving context resets is a first-class concern:** "evidence beats memory"; exactly one next action; runtime memory is hints only | §9.4 |

## 3. Interpretation and open points

- **[I]** T-8 and T-9 locate the parts that are **distinctive** rather than borrowed from software-engineering canon:
  - authority as an unconstructible state;
  - epistemic labels;
  - documents as constative records of performative decisions;
  - append-only history from audit practice.

  These are exactly the candidate KnowledgeOS cores (Proposal ≠ Decision, Claim ≠ Truth, Evidence ≠ Knowledge, provenance, FalseZero), so H-B1 gains a specific form: **H-B1′**. *KnowledgeOS generalizes the platform's distinctive epistemic mechanisms, not its software-engineering laws.*
- **[I]** S-3's fragility (an evidence model specified but not executable) **may be the problem KnowledgeOS was meant to solve.** This is a hypothesis only; it needs the charter and vision.
- **[?]** Is the KnowledgeOS theory the "Engineering Theory" layer of T-11 / B-03 K-7, a product-level successor to it, or unrelated? To be decided from the corpus.
