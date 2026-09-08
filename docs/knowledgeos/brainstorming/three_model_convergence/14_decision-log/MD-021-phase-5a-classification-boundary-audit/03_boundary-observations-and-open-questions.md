# Phase 5A — Boundary Observations, Sample Findings, and Open Questions

## The defined systematic sample (n=10), per `00_index.md`'s pre-declared design

**Population**: the 292 `meta_research`-tagged, `kernel_content: true` main-corpus rows remaining
after removing the 19 files already examined in full in
`02_kernel-candidate-typing-and-completeness.md` (306 total `meta_research`∩`kernel_content=true`
rows, per Census A, minus 14 of the 19 already-examined files that fall in this same stratum).
**Strata**: none further applied beyond the population definition itself (a single stratum, given the
modest population size). **Unit of analysis**: file. **Selection procedure, fixed before
inspection**: systematic sampling at a fixed interval of 29 (292/10), starting at offset 5 —
`remaining[5::29][:10]` — never a convenience selection. **Sample obtained**: seq 0123, 0906, 0942,
0991, 1021, 1050, 1079, 1108, 2245, 2285.

**Titles read (Level 3, on this defined sample only — not generalized to the full 292-row
population beyond what is stated)**:

| Seq | Title (truncated) |
|---|---|
| 0123 | Gödel numbering: Knowledge Identity Numbers and the reflection/self-reference architecture |
| 0906 | Step 276 (final) — Foundational Gap Reconciliation and Closure Audit |
| 0942 | HPA Comprehensive Synthesis: Missing Architecture and Theory of KnowledgeOS — Derived From Gita Philosophy |
| 0991 | e_equality.py — the actual executable script for Reviewer B's E3/E4/E5/E6 mandate, discovering a fourth corpus equality [relation] |
| 1021 | Governance Handoff: Step-285 Ratification — a concrete K-CANONICAL-DECISION proposal ready for governance |
| 1050 | t289_bootstrap_v2.py — a self-correcting revision of a dependency graph |
| 1079 | Closure Analysis (Step 291 mandate) |
| 1108 | State Vocabulary vs. Fields, the Operator Contract, and SIX Collisions in One Table |
| 2245 | Gītā Chapter 5 HPA — Ātma as Bounded Knower-Locus, Not Knowledge |
| 2285 | Kernel Simulation Across Epistemic Situations Coherence Test |

**Findings from this sample, reported at the sample's own scope — not extrapolated as a population
estimate**: this 10-file sample independently confirms the same pattern found in the census-complete
19-file stratum — substantial, on-topic, often executable (seq 0991, 1050 are named `.py` scripts, not
prose) KnowledgeOS-Kernel/epistemic-architecture research sitting under `meta_research`, including a
formal governance-ready proposal (seq 1021's "K-CANONICAL-DECISION") and a glyph-collision analysis
(seq 1108) structurally similar in kind to the corpus-integrity work this reconstruction's own
verification lane has separately performed. **This sample does not, and is not claimed to, establish
what fraction of the full 292-row (or 306-row) population shows this pattern** — a defensible
population-level estimate would require either a full census of this specific question (not
attempted here, given Phase 5A's own bounded scope) or a larger, still-properly-designed sample; this
10-file sample is reported as a **qualitative confirmation that the pattern recurs beyond the
citation-driven 19-file stratum**, nothing stronger.

## A specific boundary observation: Gita-content classified `meta_research`, outside Model A's own evidence population

**Seq 2245 ("Gītā Chapter 5 HPA — Ātma as Bounded Knower-Locus") and seq 0942 ("HPA... Derived From
Gita Philosophy") are both classified `meta_research`, not `gita`.** Census C (`01_corpus-wide-
census.md`) already showed 173 `meta_research`-tagged rows carry `gita_content: true` corpus-wide —
this sample confirms concretely that at least some of those rows are substantively Gita-content
analyses (a "Chapter 5 HPA," structurally identical in kind to the Gita-chapter analyses Model A's own
Phase-1 evidence base treated as primary evidence at seq 0427–0808).

**This is reported strictly as a boundary observation about the corpus's classification, not as a
finding about Model A.** Model A's own frozen artifacts (`02_model-a_gita/`) are not reopened,
reinterpreted, or extended by this observation — Phase 5A has no authorization to touch Model A, and
does not. The observation is: **a population of Gita-content-bearing files exists, classified outside
both Model A's own evidence-population rule (`initial_primary == gita`) and Model C1's (`==
engineering_knowledgeos`)** — a fact about the corpus's classification completeness that any future,
separately-authorized phase revisiting either Model A's or Model C1's evidence population would need
to account for. **No action is taken on this observation here.**

## Classification inconsistencies and ambiguities found (Level 3, disclosed, not resolved)

1. **The `phase_measure_theory/knowledgeos_kernel/` subdirectory (237 files)** sits almost entirely
   outside every current evidence population (Model A/B/C1/C2), classified predominantly
   `meta_research`/`cross_model` despite substantial Kernel-formalization and Gita-lens content
   (§ `02_kernel-candidate-typing-and-completeness.md`, Part 3).
2. **The "K-1" label recurs across at least two independent sub-threads** (seq 0165/0167's own
   `kernel/`-directory K-1, and seq 1008's `phase_measure_theory/knowledgeos_kernel/`-directory
   reference to "K-1" within a five-candidate consequence matrix) — whether these are the same
   referent or a coincidental reuse of the same label is genuinely unresolved from this phase's own
   evidence, and is exactly the DDD homonym-vs-shared-reference distinction the Phase-5 planning
   analysis anticipated (§2c).
3. **The math lane's `kernel_content` field is inconsistently typed** (boolean for most primaries,
   free-text for `KR-SIM`) — a schema-level finding, not a content finding, recorded in the census.
4. **306 `meta_research` rows carry `kernel_content: true`**, a population roughly 43% the size of
   Model C1's own 719-row main-corpus population, entirely unexamined by any prior phase.

None of these four findings is, or implies, a reclassification. Each is recorded with its own
provenance (source seq, source path where applicable) and evidence level, per `00_index.md`'s
three-level discipline.

## Open questions (left explicitly open by this phase)

**OQ-1.** Is the "K-1" label at seq 1008 the same referent as seq 0165/0167's K-1, or a distinct,
coincidentally-named construct? Not determined here.

**OQ-2.** What is the true relationship between `phase_measure_theory/` (root), `phase_measure_
theory/knowledgeos_kernel/`, `phase_measure_theory/external_research/`, `phase_measure_theory/
how_to_combine/`, and the separate `kernel/` directory Phase 4 already examined? Four distinct
subdirectory paths are now confirmed to exist under `phase_measure_theory/` alone; their full
relationship is not mapped by this phase (a natural candidate for the `kernel/`-vs-`phase_measure_
theory/` chronology question the Phase-5 planning document already named as unresolved, now shown to
be more structurally complex than a simple two-directory comparison).

**OQ-3.** What fraction of the full 306-row `meta_research`∩`kernel_content=true` population (beyond
the 19+10=29 files this phase actually opened) shows the same pattern? Not determined — would require
either a full census-level content pass (a larger undertaking than Phase 5A's own bounded scope) or a
larger, still-properly-designed sample, and is named here as a candidate input to any future,
separately-authorized phase.

**OQ-4.** Does the 237-file `phase_measure_theory/knowledgeos_kernel/` subdirectory, taken as a whole,
constitute evidence for a genuinely distinct research programme, an extension of Model C1's own
`kernel/`-directory work, or something else? Explicitly out of Phase 5A's own scope (this is
Candidate B/C territory) — named here only as a question a future phase would need to address, not
answered.

**OQ-5.** How many of the 173 `meta_research`-tagged, `gita_content=true` rows (Census C) are, like
seq 2245/0942, substantive Gita-chapter analyses comparable in kind to Model A's own evidence? Not
determined at census or sample scale here — flagged for a future, separately-authorized phase; **not
a basis for reopening Model A**, which remains frozen.
