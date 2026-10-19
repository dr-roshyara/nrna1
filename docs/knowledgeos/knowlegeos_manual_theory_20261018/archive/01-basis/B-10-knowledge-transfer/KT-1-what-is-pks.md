# KT-1 — `docs/knowledge_tranfer/`, batch 1: "What is PKS" (v0, v1, progress)

| Field | Value |
|---|---|
| Read (full) | `what_is_pks_v0.md` (no date in name) · `20260728_1710_what_is_pks_v1.md` · `20260729_1812_pks_progress.md` |
| **Source character** | **AI-generated conversational answers pasted as files** (*"Yes — Now I Fully Understand"*, *"Yes — absolutely"*). Their claims (*"complete, governed, certified"*) are an assistant's summaries, **not** governing records. Evidential weight: [TRANSCRIPT], below ADRs, registers and the governing artifacts they cite |
| Labels | [SRC-T] stated in the transcript · [I] interpretation · [?] open |

## 1. How the PKS definition evolved (inside these files)

| Stage | Definition [SRC-T] |
|---|---|
| v0, first answer | *"The PKS is the instruction manual for AI documentation"*: which documents (ADR, decision log, guide, session log, CONTEXT, MEMORY, plan, verification report, retrospective), when, where, how structured, how verified |
| v0, **"Corrected Understanding"** | *"too narrow"*. **"The PKS is a knowledge system that models engineering knowledge, governance, evidence, and their relationships. One of its primary capabilities is providing AI with deterministic guidance about which engineering artifacts to produce…"* |
| v1 (2026-07-28) | same definition; *"Documentation guidance is a derived service built on top of the knowledge model, not the defining purpose"*; *"The PKS is not software — it is knowledge specifications (YAML + Markdown)"* |

## 2. Core content [SRC-T]

- **Three layers:**
  - **Knowledge Model** (semantics: concepts, relationships, lifecycle, evidence);
  - **Representation** (YAML and Markdown);
  - **Presentation** (ADR, guide, session log, report).
  
  *"The Knowledge is the primary thing. The Presentation is derived."* *"Documents are projections of knowledge."*
- **The 17 validated operating concepts (G-1…G-17):**

  | Concept | Definition |
  |---|---|
  | Decision | *selection among alternatives under explicit trade-offs* |
  | Rule | *binding behavioral norm with a single canonical home* |
  | Invariant | *must always hold; violation is a defect* |
  | Ruling | |
  | Finding | |
  | Question | *an owned, routed unknown whose lifecycle outlives its carrier* |
  | Term | |
  | Contract | |
  | Model Element | |
  | Observation | *a factually captured evidence entry* |
  | Risk | |
  | Candidate | *probationary knowledge item* |
  | Work Item | |
  | Guide Step | |
  | Verdict | *the outcome of evaluating evidence against a criterion* |
  | Exception Record | |
  | Charter Grant | *authorization permitting a named next activity* |
- **Projection map:** Decision → ADR · Observation → Session Log · Verdict → Verification Report · Finding + Risk → Retrospective · Term → Glossary · Rule → Policy · Contract → API spec.
- **Models:** Knowledge · Governance · Evidence · Artifact · Process; AI Guidance Services are derived from them.
- **Programme (all dated 2026-07-27/28 in the files):** Phase I Strategic Discovery → M0…M8 Strategic Modeling (UL glossary, concept register, collision resolutions, conformance kind, identity and lifecycle with **3 identity modes** and a **3-class lifecycle** — pre-authority → authoritative → terminal — domain classification, bounded contexts, context map) → AD-1 → C4 → RET-1 → KBI-1.
  - Certification: *"PROVISIONALLY CERTIFIED"*.
  - Operational evidence: *"SUPPORTED BY ONE EXECUTION LINEAGE"*.
  - *"The one thing we cannot self-supply: an execution by a second lineage, or external review."*
- **Principles:** Knowledge before documentation · Evidence before architecture · Governance before implementation (*"AI surfaces governance questions; it does not resolve them"*) · Reversibility · Separation of concerns (*"knowledge ≠ documentation; domain ≠ capability; analysis ≠ design"*) · *"Silence is an architectural decision. Undefined remains undefined. Absent remains absent."*

## 3. Interpretation

- **[I] PKS restates the RQ-002 core at concept level.** The constitution's *Knowledge Claim with natures* (B-08) becomes a typed set of **17 knowledge concepts**, and documents are *projections*: compare RQ-002's "documentation is merely one possible representation" and PKS DR-1/DR-2 (B-05).
- **[?] Which "PKS"?** The files say "Product Knowledge System", but its content is **engineering** knowledge (decisions, rulings, findings, verdicts). That fits ES-006's "Project Knowledge" only loosely, which deepens the term collision recorded in B-07.
- **[?] Status inflation:** the transcripts state "complete" and "certified" in a congratulatory register. The governing artifacts (M4, M6, AD-1, MCA/CDR) must be read for the actual status. AD-1 is cited in B-05 as *"Medium-High, one corpus"*.
