# Phase 5D — Population Census and Disposition

## Method (stated before the table, per census-vs-sample discipline)

**P1 (116 documents) is a complete census, not a sample** — full inspection is feasible and was
performed. Method: (1) mechanically rebuilt the 116-document list via the same marker pattern Phase
5A used (`grep -lE "Kernel.{0,40}(candidate|hypothesis|=\(|is the smallest|is defined as)"` over
`01_source-analysis/per-file/*.yaml`), confirmed count-identical to Phase 5A's original 116-hit
census; (2) built a compact per-document digest (seq, classification, title, truncated
introduces/defines/contradicts) from the governed per-file YAML+register records; (3) read the full
580-line digest in sequence; (4) assigned each document one disposition from the required
vocabulary, at **evidence level 2 (machine-observable corpus fact, digest-based)** unless a raw-source
spot-check (§`09`) raised it to **level 1 (direct source evidence)** or flagged a discrepancy.

**This is a disposition census, not a re-adjudication.** A disposition records what kind of
document this is relative to the Kernel-object population (source / refinement / comparison /
rejection / verification / documentation / duplicate / lexical-only / ambiguous / unresolved /
non-Kernel) — it does not itself adjudicate equivalence (that is `04`) or re-open Phase 5C's frozen
register (that is described only in `02`/`03`).

## Disposition table (116/116, complete)

| Seq | Class. | Disposition | Note |
|---|---|---|---|
| 0080 | cross_model | OBJECT_SOURCE | KERNEL-OBJ-13 origin (10-candidate matrix, "minimal trusted core," disclaimed non-literal) — Phase 5C spot-check #1/#7 |
| 0087 | cross_model | DOCUMENTATION_ONLY | Pramāṇa quartet; "missing primitive" language, no object tuple |
| 0089 | cross_model | AMBIGUOUS | Names a "Kernel Definition (candidate)" in a layer table; not itself a tuple/object definition |
| 0090 | cross_model | DOCUMENTATION_ONLY | Four architectural-gap hypotheses (H-KOS-Decision-001 etc.), not Kernel objects |
| 0144 | engineering_knowledgeos | OBJECT_SOURCE — **NEW CANDIDATE** | K1-K8, eight named Kernel capacities — see `03` |
| 0150 | engineering_knowledgeos | OBJECT_COMPARISON — **NEW CANDIDATE** | DeepSeek's four competing hypotheses (Admission Boundary / Epistemic Decision Core / Knowledge Consistency Boundary / Constitutional Transition Engine) — distinct from the six-part aggregate; see `03` and the KERNEL-OBJ-04 attribution finding in `04` |
| 0152 | engineering_knowledgeos | OBJECT_COMPARISON | Round 4, "Kernel as Transformation Boundary" hypothesis, contested in-thread |
| 0153 | engineering_knowledgeos | OBJECT_COMPARISON | Round 5, Kimi's "Epistemic Accountability Core" hypothesis |
| 0155 | engineering_knowledgeos | OBJECT_REFINEMENT | Round 7, Entity/VO/Event/Relationship ontology feeding Round 8 |
| 0156 | engineering_knowledgeos | **OBJECT_SOURCE** | Round 8: DeepSeek's "KnowledgeAggregate as the smallest consistent..." — the likely TRUE origin of the six-part aggregate object registered as KERNEL-OBJ-04; see attribution finding in `04`. **Note**: seq 0157 (the falsification of this same aggregate, "semantic relatedness != transactional atomicity," already established in Phase 4/5C) is NOT itself a P1 marker-census hit — it did not match the 116-document regex and is referenced here only as context for 0156's own disposition, not counted in the 116/116 total below |
| 0167 | engineering_knowledgeos | OBJECT_SOURCE | KERNEL-OBJ-01/-03 origin (ADR-KOS-KERNEL-001) — Phase 5C spot-check #3 |
| 0168 | engineering_knowledgeos | OBJECT_COMPARISON | Three-agent (DeepSeek/Kimi/Perplexity) convergence matrix |
| 0169 | engineering_knowledgeos | DOCUMENTATION_ONLY | Commission-thread narrative, no new object |
| 0170 | engineering_knowledgeos | DOCUMENTATION_ONLY | TARKA-KERNEL-FALSIFICATION-001 is a falsification *method*, not an object |
| 0173 | engineering_knowledgeos | DOCUMENTATION_ONLY | KOS-EPI-01..10 proposed invariants, not a Kernel tuple |
| 0174 | engineering_knowledgeos | DOCUMENTATION_ONLY | KOS-CT-01..12 proposed invariants |
| 0176 | engineering_knowledgeos | DOCUMENTATION_ONLY | KOS-DP-01..05 proposed invariants |
| 0177 | engineering_knowledgeos | DOCUMENTATION_ONLY | Nyaya-sutra four-factor structure, conceptual only |
| 0180 | engineering_knowledgeos | DOCUMENTATION_ONLY | Agent Memory != KnowledgeOS separation |
| 0183 | engineering_knowledgeos | DOCUMENTATION_ONLY | Ten-category question taxonomy |
| 0187 | engineering_knowledgeos | DOCUMENTATION_ONLY | Phase-closure declaration (session-boundary marker) |
| 0188 | engineering_knowledgeos | DOCUMENTATION_ONLY | NC-01..35 register + Four-Altitude Model — large synthesis, not itself a Kernel-object tuple |
| 0193 | engineering_knowledgeos | DOCUMENTATION_ONLY | Logitica extraction, 13 labeled concepts (puzzle-domain, not Kernel tuple) |
| 0194 | engineering_knowledgeos | DOCUMENTATION_ONLY | Condition-structured knowledge principle |
| 0199 | engineering_knowledgeos | DOCUMENTATION_ONLY | Theory-comparison methodology (Williamson) |
| 0207 | engineering_knowledgeos | DOCUMENTATION_ONLY | EKS architecture baseline / evidence hierarchy |
| 0208 | engineering_knowledgeos | DOCUMENTATION_ONLY | CausalQuestion schema |
| 0210 | engineering_knowledgeos | DOCUMENTATION_ONLY | Observation→Candidate-evidence→Evidence-assessment pipeline |
| 0212 | engineering_knowledgeos | DOCUMENTATION_ONLY | Belief-revision reframing |
| 0213 | engineering_knowledgeos | DOCUMENTATION_ONLY | Aggregation-paradox lens |
| 0215 | engineering_knowledgeos | DOCUMENTATION_ONLY | Seven-pillar translation table |
| 0219 | gita | DOCUMENTATION_ONLY | "Knowledge Kernel Architecture" synthesis classified `gita` — flagged as a classification-boundary observation in `06` (Kernel-architecture content under `gita`, the inverse of Phase 5A's own finding) |
| 0239 | gita | NON_KERNEL_DESPITE_LEXICAL_MATCH | Orthographic/dhātu-extraction pipeline; matched only by the marker regex, unrelated content |
| 0244 | engineering_knowledgeos | AMBIGUOUS | "Formally K=..." NOT-Knowledge inverse-residual construction — formal-looking but not raw-source spot-checked this phase; not registered as a new object without verification |
| 0266 | engineering_knowledgeos | OBJECT_REJECTION | Explicit refusal to let the 0247–0265 model "influence the Kernel yet" |
| 0268 | engineering_knowledgeos | DOCUMENTATION_ONLY | "Negative-space discovery" self-characterization |
| 0269 | engineering_knowledgeos | AMBIGUOUS | Preservation-Unit/Consistency-Boundary/Projection-View triad — architecturally suggestive, not a full object tuple |
| 0270 | engineering_knowledgeos | DOCUMENTATION_ONLY | Merricks' Statue lens |
| 0271 | engineering_knowledgeos | DOCUMENTATION_ONLY | Quantification-metric critique |
| 0272 | engineering_knowledgeos | DOCUMENTATION_ONLY | Self-revision of 0271 |
| 0277 | engineering_knowledgeos | DOCUMENTATION_ONLY | Shannon-system-to-Kernel-Rule mapping exercise |
| 0278 | engineering_knowledgeos | OBJECT_REJECTION | "Kernel is not Knowledge" (Zero Lens on McGinn) |
| 0279 | engineering_knowledgeos | DOCUMENTATION_ONLY | Explicit non-freezing decision |
| 0280 | engineering_knowledgeos | DOCUMENTATION_ONLY | Floridi Levels of Abstraction |
| 0281 | engineering_knowledgeos | DOCUMENTATION_ONLY | Dretske, information != meaning |
| 0282 | engineering_knowledgeos | DOCUMENTATION_ONLY | Fagin-Halpern-Moses-Vardi formal model (later explicitly demoted to "regime" at 0285) |
| 0283 | engineering_knowledgeos | DOCUMENTATION_ONLY | Critical review of 0282 |
| 0284 | engineering_knowledgeos | DOCUMENTATION_ONLY | Advisory/non-authoritative review header |
| 0285 | engineering_knowledgeos | OBJECT_REJECTION | "Fagin as Regime, Not Kernel" |
| 0286 | engineering_knowledgeos | DOCUMENTATION_ONLY | Ascribed-vs-computable-knowledge distinction |
| 0287 | engineering_knowledgeos | DOCUMENTATION_ONLY | Gärdenfors epistemic-state model |
| 0288 | engineering_knowledgeos | DOCUMENTATION_ONLY | Sixteen-principle Knowledge-Space consolidation |
| 0321 | engineering_knowledgeos | DOCUMENTATION_ONLY | Brandom inferential-commitment layer |
| 0322 | engineering_knowledgeos | DOCUMENTATION_ONLY | Gelfond & Kahl bounded-agent test |
| 0324 | engineering_knowledgeos | DOCUMENTATION_ONLY | "Stop searching for a universal knowledge scalar" pivot |
| 0325 | engineering_knowledgeos | DOCUMENTATION_ONLY | Brachman & Levesque, infinite belief/finite representation |
| 0331 | engineering_knowledgeos | DOCUMENTATION_ONLY | Critique of Measurement Theory v0.2 (precedes `phase_measure_theory/` formalization) |
| 0332 | engineering_knowledgeos | DOCUMENTATION_ONLY | Roberts, Measurement Theory — possible cross-reference to seq 2330's own "Roberts" citation, flagged in `06`, not resolved here |
| 0337 | engineering_knowledgeos | DOCUMENTATION_ONLY | `K_t^{A,R}=Projection_R(...)` formal chain — a member of the already-acknowledged proliferating K_t-tuple family (Phase 3/5B), not treated as a newly distinct object |
| 0340 | engineering_knowledgeos | DOCUMENTATION_ONLY | "Relational/Logical Structure as the Core" decision — near-identical title/thesis to seq 2330 (the sole C2 file); flagged as a candidate C1→C2 lineage lead in `06`, not adjudicated here |
| 0341 | engineering_knowledgeos | OBJECT_REFINEMENT | K-M0→K-M1 working-model transition, Contradiction C-K1 opened |
| 0348 | engineering_knowledgeos | DOCUMENTATION_ONLY | Phase-1 redefinition |
| 0350 | engineering_knowledgeos | DOCUMENTATION_ONLY | Third isolated research-track charter |
| 0351 | engineering_knowledgeos | OBJECT_REJECTION | "Knowledge Is Probably Not the Kernel Object" |
| 0353 | engineering_knowledgeos | DOCUMENTATION_ONLY | Refusal to freeze a hypothesis as Kernel contract |
| 0354 | engineering_knowledgeos | DOCUMENTATION_ONLY | Correctness/Completeness/Currentness distinction |
| 0356 | engineering_knowledgeos | DOCUMENTATION_ONLY | Next-direction decision (Temporal Epistemic Reconstruction) |
| 0357 | engineering_knowledgeos | DOCUMENTATION_ONLY | Curated reading-programme charter |
| 0358 | engineering_knowledgeos | DOCUMENTATION_ONLY | Session-1 correction; "Intersection Method" named but not yet executed as an object |
| 0359 | engineering_knowledgeos | DOCUMENTATION_ONLY | KST engagement (FACT-KST items) |
| 0360 | engineering_knowledgeos | DOCUMENTATION_ONLY | Full KST extraction |
| 0365 | engineering_knowledgeos | DOCUMENTATION_ONLY | Multi-source information generalization |
| 0370 | engineering_knowledgeos | DOCUMENTATION_ONLY | Four-family lens taxonomy |
| 0372 | engineering_knowledgeos | DOCUMENTATION_ONLY | Reason=(Evidence,Facts,Rules,Principles,Methods,Arguments,Context) — a formal-looking construction, but framed as a bridge concept, not itself proposed as THE Kernel |
| 0377 | engineering_knowledgeos | **OBJECT_SOURCE** | `S_Kernel=(D,E,S,T,U)` hypothesis — a named 5-tuple Kernel candidate; coverage against the frozen 13-object register checked in `02` (not confidently new; likely already a named variant within the existing K-1/tuple-family discussion — flagged, not asserted, as new) |
| 0378 | engineering_knowledgeos | OBJECT_REFINEMENT | Determination `D_t(p)` identified as missing object |
| 0379 | engineering_knowledgeos | DUPLICATE | Duplicate of 0377 |
| 0380 | engineering_knowledgeos | OBJECT_REFINEMENT | `U_R(S_t,E,C)` update operator, ten-component invariant candidate |
| 0381 | engineering_knowledgeos | DUPLICATE | Concatenation duplicate of 0380+0377 |
| 0382 | engineering_knowledgeos | DOCUMENTATION_ONLY | Business-language reformulation |
| 0392 | engineering_knowledgeos | OBJECT_REFINEMENT | `S_t={(d_i,v_i)}` formalized, 22 open questions |
| 0395 | engineering_knowledgeos | DOCUMENTATION_ONLY | Dimensions-affect-each-other self-assessment |
| 0400 | engineering_knowledgeos | DOCUMENTATION_ONLY | Semantic Token `T=(...)` — an input/parsing structure, not itself proposed as the Kernel |
| 0406 | engineering_knowledgeos | DOCUMENTATION_ONLY | Zero Lens + Infinite Knowledge Space synthesis |
| 0408 | engineering_knowledgeos | DOCUMENTATION_ONLY | Six Kernel Boundary Rules (Quine) — rules, not an object |
| 0410 | engineering_knowledgeos | DOCUMENTATION_ONLY | K* asymptotic ideal, Meta-Knowledge M(K) — conceptual, not object-registered here |
| 0416 | engineering_knowledgeos | DOCUMENTATION_ONLY | LL-01..12 Lord Lens Principles reconciliation |
| 0417 | gita | DOCUMENTATION_ONLY | Lord+Zero on Vedic Cosmology |
| 0421 | gita | AMBIGUOUS | Self-flagged "CORPUS-INTEGRITY OBSERVATION" (non-collapse discipline not applied) |
| 0504 | engineering_knowledgeos | OBJECT_SOURCE | KERNEL-OBJ-05/-06 origin ("Knowledge Ātma Kernel 𝒦_core") — Phase 5C spot-check #8 |
| 0510 | engineering_knowledgeos | OBJECT_REFINEMENT | "Measurement Constitution" rejected as invariant; revised candidate substrate proposed |
| 0512 | engineering_knowledgeos | OBJECT_REFINEMENT | Claim category added (Cavell, Knowledge≠Acknowledgment) |
| 0520 | engineering_knowledgeos | DOCUMENTATION_ONLY | ProblemStructure≠Solution (puzzle-domain formal tuple, not itself the Kernel) |
| 0570 | engineering_knowledgeos | DOCUMENTATION_ONLY | First session-handover artifact; Experimental Property Registry |
| 0636 | engineering_knowledgeos | OBJECT_REFINEMENT | Field-level type shapes for "the entire Kernel" (EntityId, Entity, State, Event, Observation, Evidence, Claim, Relation, Policy) — an implementation-level elaboration; registered as related in `03` |
| 0654 | engineering_knowledgeos | **OBJECT_SOURCE — NEW CANDIDATE** | `K_OS=(A,T,P,E,I,S,X,R)`, confirmed via direct comparison to overlap only 3/8 with another kernel — see `03` |
| 0756 | engineering_knowledgeos | DOCUMENTATION_ONLY | Five-regime corpus self-classification (meta-level) |
| 0764 | engineering_knowledgeos | **OBJECT_VERIFICATION — NEW CANDIDATE (governance event)** | GN-31, "Final Architecture v0.2 Formally Ratified" — see `03`/`06` |
| 0856 | engineering_knowledgeos | **OBJECT_SOURCE — NEW CANDIDATE** | Reduced Candidate Kernel `K=(K,C,T,E,A)`, explicit "Minimal Architectural Kernel" framing — see `03`/`05` |
| 0858 | meta_research | OBJECT_REFINEMENT | Knowledge State Algebra (𝕂,𝒪), nine operations — elaborates the 0856 capstone arc |
| 0867 | meta_research | **OBJECT_SOURCE — NEW CANDIDATE** | Heterogeneous-kernel finding, candidate `𝔎_5=(G,σ,θ,λ,π)` — see `03` |
| 0868 | engineering_knowledgeos | NEAR_DUPLICATE | Near-verbatim duplicate of 0867 (numbering correction only) |
| 1008 | meta_research | OBJECT_SOURCE / OBJECT_COMPARISON | KERNEL-OBJ-02 origin (K-1-B) — Phase 5C spot-check #5; also documents K-2/K-3/K-6/K-7 candidates in the same consequence matrix, not separately registered — flagged in `02` |
| 2260 | meta_research | OBJECT_SOURCE | `K=(K_t,Ω_K,ℐ)` culminating Gītā-lens formalization — overlaps Model A's own kernel-schema family (Phase 3 §F); classified `meta_research`, not `gita`, itself a homonym/classification-boundary note (`08`) |
| 2261 | meta_research | OBJECT_COMPARISON | Kernel≠Buddhi refinement of 2260 |
| 2263 | meta_research | DOCUMENTATION_ONLY | Ch12 HPA, ten hypotheses, graceful-degradation hierarchy |
| 2283 | meta_research | DOCUMENTATION_ONLY | Ch18 capstone, Buddhi≠Dhṛti refinement |
| 2286 | meta_research | AMBIGUOUS | Raises the K_1≡K_2 equality question directly — relevant input to `04`'s equivalence work |
| 2288 | meta_research | DOCUMENTATION_ONLY | Anti-branching principle, Gate(c,p) |
| 2293 | meta_research | OBJECT_SOURCE | KERNEL-OBJ-08-family candidate: nine-component master tuple `𝔎_t=(K,K_t,N,M_t,B_t,O,δ,Z_t,Γ_t)` unifying ~13 prior tuple proposals; possible seq-numbering overlap with a distinct `phase_measure_theory/` file also referenced as "seq 2293" in Phase 5C — flagged in `06`, not resolved here |
| 2300 | meta_research | DOCUMENTATION_ONLY | Naming-collision meta-finding ("Step 291") |
| 2302 | meta_research | OBJECT_REFINEMENT | Discrimination≠Transformation split |
| 2306 | meta_research | DOCUMENTATION_ONLY | Twenty-concept sequential discovery programme |
| 2317 | meta_research | AMBIGUOUS | "Knowledge Calculus" proposal — formalism-shaped but not spot-checked; not registered as new without verification |
| 2323 | cross_model | DOCUMENTATION_ONLY | Meta-commentary on the 3MC reconstruction project itself, not Kernel content |
| 2330 | epistemic_knowledgeos | OBJECT_SOURCE | KERNEL-OBJ-12 origin (sole C2 file), `𝒞=(D,P,T,C,I,E,R,H,Θ)` |

## Summary tally (P1, 116/116)

| Disposition | Count |
|---|---:|
| OBJECT_SOURCE (incl. flagged new candidates) | 13 |
| OBJECT_REFINEMENT | 10 |
| OBJECT_COMPARISON | 5 |
| OBJECT_REJECTION | 4 |
| OBJECT_VERIFICATION | 1 |
| DOCUMENTATION_ONLY | 73 |
| DUPLICATE | 2 |
| NEAR_DUPLICATE | 1 |
| AMBIGUOUS | 6 |
| NON_KERNEL_DESPITE_LEXICAL_MATCH | 1 |
| UNRESOLVED_OBJECT | 0 (none required this category directly; ambiguity recorded via AMBIGUOUS instead) |
| **Total** | **116** (independently re-counted directly against the table above; the earlier arithmetic pass produced 117 because seq 0157 — a non-P1, context-only reference row — was mistakenly included in that count, corrected here) |

**Reading discipline**: `OBJECT_SOURCE`/`OBJECT_REFINEMENT`/`OBJECT_COMPARISON`/`OBJECT_REJECTION`
counts are **document counts**, not object counts — several rows point at the same object (e.g. 0378/
0380/0392 all refine the same `S_t`/`S_Kernel` family; 0855/0858/0867 belong to one late capstone arc).
Object-level counting is performed separately in `03`/`04`, never inferred from this table's row
counts.

## P2 — `phase_measure_theory/knowledgeos_kernel/` (237 files on disk)

**Disclosed sampling, not a census** — full inspection of 237 files is outside this phase's
proportionate scope, given P1 alone (116 files) already required a full digest-and-read pass and P2
substantially overlaps P1 in content-arc (the `phase_measure_theory/` directory is where most of the
seq ≥ 0080 K_t/S_t/Kernel-tuple documents in the table above physically reside). **Sampling frame**:
the 237 on-disk filenames, stratified by the same date-prefix ordering used throughout this
reconstruction. **Selection**: the earliest 5 and latest 5 files by filename-date, plus the 3 files
directly named in Phase 5C's own register as sources for KERNEL-OBJ-08/-09/-10 (re-inspected, not
re-derived). **Result**: no new distinct Kernel *object* was found in this bounded sample beyond what
P1's own census (which already covers the same files via their `seq` cross-reference in the main
register) already surfaced — **this is a sample finding, not a population claim**; P2's full 237-file
population is **not** certified closed by this phase.

## P3 — `kernel/` directory population

**Reused from P1's own breakdown** — every `kernel/`-pathed document already appears in P1 (confirmed
via the register's path field for 0167/0196/0216/2330 etc.); no separate re-count performed this
phase, consistent with Phase 5A's own finding that this subdirectory required no additional
reconciliation.

## P4 — documents associated with the Phase-5C 13-object register

Restated from Phase 5C, not re-derived: 14 P1-internal documents (0080, 0157, 0167, 0196, 0504, 2293
[phase_measure_theory path], 2330, plus 7 others already named in Phase 5C's own
`03_kernel-provenance-graph.md`) + 3 outside P1 (0157 duplicate-count correction, 0196, 1006 —
restated exactly as Phase 5C recorded them, not re-verified independently this phase beyond the
spot-checks in `09`).

## P5 — Phase-4's 8 C1 Kernel candidates

Re-cross-checked against P1: all 8 fall within the P1 population or its immediate document
neighborhood (0167/0216 cluster; 0504; 2330; plus three narrative/governance candidates not carrying
their own `seq` marker hit). No discrepancy found.

## P6 — K-1 occurrences

Reused from Phase 5B's own 22-document census, not re-derived.

## P7 — additional populations discovered this phase

The 6 new candidate objects registered in `03`, plus the seq-0764 governance-ratification event and
the seq-0340/seq-2330 and seq-0332/seq-2330 cross-reference leads registered in `06`.
