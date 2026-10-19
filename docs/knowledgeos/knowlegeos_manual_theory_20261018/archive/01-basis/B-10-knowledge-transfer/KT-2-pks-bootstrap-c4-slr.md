# KT-2 — `docs/knowledge_tranfer/`, batch 2: PKS bootstrapping, C4 L1–L4, DDD methodology SLR

| Field | Value |
|---|---|
| Read (full) | `PKS_Bootstrapping_and_Location.md` · `C4 Level 1 -PKS_System_Context_c4.md` · `C4 Level 2 — Container Diagram.md` · `C4 Level 3 — Component View_Knowledge.md` · `C4 Level 4 — Code.md` · `20260728_SLR_Strategic_DDD_Methodology_Benchmark.md` |
| Source character | Bootstrapping and C4 L2–L4: **AI conversational answers**, including one pasted external reviewer (ChatGPT-style) critique. C4 L1: PlantUML only. SLR: **raw external output (Perplexity) plus PA meta-review**, self-labelled *"RAW SUPPORTING MATERIAL — NOT A GOVERNED ARTIFACT"* |
| Labels | [SRC-T] transcript · [SRC-R] raw supporting material · [I] · [?] |

## 1. PKS bootstrapping [SRC-T]

- **Circularity named:** *"AI needs a PKS to build the PKS."* **Resolution:** the **methodology** is cross-product (it belongs in `engineering/` once promoted), while **the PKS** is product-specific (`docs/implementation/pks/`).
- **The PKS is called** *"a product-specific engineering knowledge model and its governed representations"*, a **living asset** that evolves with the project.
- **Reviewer corrections embedded in the file:**
  - *"M0–M8 are Strategic Modeling artifacts produced while constructing the PKS"*, not the PKS itself;
  - *"Promotion should be evidence-driven, not phase-driven"*;
  - *"One instance is a record. Multiple instances are evidence of reusability"*;
  - separate **current facts / expected evolution / future architecture**.

## 2. C4 views of PKS

| Level | Content |
|---|---|
| **L1** | PKS *"Manages governed engineering knowledge for a specific product throughout its lifecycle"*. Actors: Architect · Engineer · Program Authority · Reviewer · **AI Assistant** (*"consumes governed knowledge"*). External: Work Management (read-only reference, DR-5) |
| **L2** | AC-1 Knowledge Assessment (records evidence → evaluates against criteria → issues verdicts; cannot self-issue; cannot author criteria) · AC-2 Knowledge Projection (terminal sink; nothing depends on it) · XD-1 Work Management (external) · AR-1 and AR-2 undefined regions · a **"Knowledge Registry"** container (concepts.yaml…) |
| **L3** | *"NOT a conventional C4 Level 3"*: a **Knowledge Structure View** of *responsibilities*. Observation Collection → Criterion Evaluation → Verdict Issuance; projections to ADR, guide, session log, report, YAML |
| **L4** | *"NOT supplied… would invent architecture"*, yet it lists a future stack (Laravel 12, PostgreSQL, **Neo4j**, GraphQL, outbox) and aggregates |

**[?] Contradictions with governed text (B-05):**

| Transcript claim | Governing record |
|---|---|
| L2 draws *"issuance trigger originates in AR-1"* | AD-1's own C-02 amendment struck that sentence as *"an interpretation presented as a derivation"*, and Q-7 is open (validation report §2 F-3, §4) |
| A "Knowledge Registry" **container** | AP-4 forbids a central identifier registry; whether a registry container is permitted is undetermined |
| L4 lists a technology stack | it contradicts its own "not derivable" statement |

**These transcripts sit below AD-1 and must not be used as architecture.**

## 3. The SLR and the research framing (2026-07-28) [SRC-R]

- **Literature verdict (Perplexity, author-graded):**
  - established: bounded contexts, context maps, workshop discovery;
  - external to DDD: governance gates, certification, configuration control;
  - weakly supported or novel combinations: separating the discovery method from its execution procedure (the "SDM/EOP split"), **discovery as falsifiable hypothesis testing**, method lifecycle and certification.
- **PA central research problem:** **"How can strategic architectural discovery become a repeatable, evidence-based, and governable engineering activity?"**
- **Positioning:** *"an original synthesis of established ideas from multiple disciplines, rather than a wholly new theory within Domain-Driven Design."* *Avoid: "a new theory of DDD."*
- **Biggest gap named: epistemology.** *"What constitutes evidence for strategic architectural decisions?"* Candidate home discipline: **Design Science Research**, at hypothesis grade. Future literature directions: Evidence-Based Software Engineering (EBSE), Design Science Research, knowledge governance.
- **Transition marker:** from *"what should the methodology look like?"* to *"how will we know whether the methodology works?"*

## 4. Interpretation

- **[I]** By 2026-07-28 the programme had **three knowledge-related research lines** running side by side:
  1. RQ-002 (a general knowledge constitution);
  2. PKS (a product engineering-knowledge model, with a strategic DDD methodology);
  3. the KnowledgeOS product charter.

  The SLR adds a fourth: **the epistemology of architectural evidence**. That is the question the later corpus (S-Series, verification, kernel) appears to take up.
- **[I]** The positioning rule *"original synthesis, not a new theory"* is a standard the reconstruction should apply to the corpus theory too.
