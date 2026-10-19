# Manuscript origin and corpus relationship report

| Field | Value |
|---|---|
| Author / clock | Session B, 2026-10-18 07:46 |
| Mode | **Read-only.** No corpus content was opened for this report. No manuscript, identifier, governance record or access rule was changed. |
| Sources used for this report | Session B's own records only: `corpus-access/READ-LOG.tsv`, `SOURCE-REGISTER.md`, manuscript v0.4 §15, the validation reports, `manuscript-v1/`. Also **aggregate counts** (by folder, and by keyword over family file names, with no names or contents displayed) from the earlier filter outputs `CORPUS-ALLOWED.tsv`, `FAMILIES-ALLOWED.tsv` and `FAMILIES-PROFILE.tsv`. Under G-LOG-0146 §6 these outputs are **untrusted for authorizing reads**; they are used here only as inventory metadata. |
| Claim scope | **This is not a corpus-wide audit.** It establishes what manuscript v1 drew on, and what it demonstrably did not. |

## 1. Executive explanation

**What manuscript v1 is.** A mathematical theory of two things:
- what a knowledge system must **hold** (the minimal sufficient state);
- what it must **retain** to stay accountable: recover, verify, contest and correct decisions, under privacy, deletion and storage limits.

**Why it was built this way.**
- **Part II (state)** answers the corpus's own open question from step 285: "find the smallest state that contains everything the kernel must know". It is built from **four brainstorming documents** and **six family summaries** read in October 2026.
- **Part III (records)** was **not** reconstructed from corpus documents. It was **newly derived** in response to the Principal's research mandates and the D9/D10 governance questions, using v1's own mathematics.

**What it establishes.** Internally reviewed theorems (same model family, refute-first, with independent code checks) on canonical state, constrained minimality, recoverability, verifiability, contestability, corrections, privacy, deletion and storage.

**How it relates to the wider theory.**
- It rests on **about 0.4%** of the available corpus: **11 documents** opened, 5 of them only in part, out of 2,734 allowed corpus files plus 2,457 family summaries.
- Large corpus areas are unread (§4), and several major themes of the corpus are absent from v1.
- So v1 is a **component**, a formal core for one layer, **not** a reconstruction of the corpus's theory.

## 2. Inventory: what exists and what Session B was authorized to inspect

| Inventory | Size (aggregate) | Authorized? | Used? |
|---|---|---|---|
| Corpus registry (discovery population, sealed files excluded) | **2,734** allowed files; 33 sealed excluded | listing yes (earlier filter runs, now untrusted); content: only through the gate | counts only |
| — `brainstorming/phase_measure_theory` | 820 | — | **4 opened** |
| — `brainstorming/verification` | 472 | — | **0** |
| — `brainstorming/mathematical_ideas_that_can_be_implemented` | 372 | — | **0** |
| — `reviews/synthesis` | 305 | — | **0** |
| — `brainstorming/kernel` | 157 | — | **0** |
| — `reviews/kernel` | 111 | — | **0** |
| — `research/` (theory v1.1 / v1.2 simulations, kernel reduction) | 69 | — | **0** |
| — other (what_is_knowledgeos_theory, misc, synthesis, classification, …) | 28 | — | **0** |
| — `docs/knowledgeos/KnowledgeOS_Conceptual_Foundation.md` | 1 | **Principal exception, 2026-10-18** | **1 (full)** |
| Session A family summaries (`20-FAMILIES/`) | **2,457** allowed (814 theory-level, 1,086 object, 287 methodological, 45 cross-object, 16 governance; lifecycles: 1,359 dormant, 837 active, 255 contested); 48 withheld | listing yes; content: only through the gate | **6 logged** (5 header/births/rationale reads; 1 logged but **not opened**, see §5) |
| Session A research documents (handoff, Kind-P note r2, decision packet) | 3 | yes | **3** |
| Governance and methodology (P3b log, protocols, prereg, verdict criteria) | about 20 (SOURCE-REGISTER S-*) | yes | used by **v0.4** Part I, **not** by v1 |
| Chronology and version history | `02-FILES.jsonl` registry dates; source-ID order ≠ chronology (v3.5 R1) | registry yes | dates only (inside the filter) |

## 3. Chapter-to-source traceability (manuscript v1)

**Provenance classes.**
- **R:** reconstructed directly from a cited corpus statement.
- **S:** synthesized across sources.
- **N:** newly derived mathematics (Session B; standard methods).
- **P:** prompted by a Principal mandate or governance question, not by a corpus document.
- **I:** inferred or interpretive mapping.
- **U:** not traceable to any source.

| Ch. | Main results | Corpus sources actually read (READ-LOG / SOURCE-REGISTER) | Class |
|---|---|---|---|
| 1 Problem, scope | the two problems; the six sufficiency requirements | **C-285** (step 285 §14, §23) | R (problem statement) + P (Part III scope) |
| 2 Formal model | inputs, decisions, information order, PC records | none directly. IC is motivated by C-285's category separations (S-2/S-3) | N |
| 3 Canonical state | Nerode quotient; uniqueness; decidability; §3.7 corpus mapping | **C-285** ("smallest sufficient state", legality and replay requirements); **C-30** (S-1: "extensional, behavioural sufficiency"); classical literature | S + N; §3.7 is I |
| 4 Constrained state | R2/R4, update/decoder results, E2, anonymity | the motivating question came from family **oq4-adequacy-realization-witness** (T-7: weak decoder, k-anonymity witness) and family **information-sufficiency-compression-model** (T-5/T-6), both header-level, third-hand | N (+ I for motivation) |
| 5 Contracts, gaps, Zero | gap as decision set; preorder; Zero claims | family **knowledgeos-theory-v1-0** (T-2 Gap/Zero, via the family; the gap-theory primary is **unread**); family **kos-theory-v2-rewrite** (T-4 FalseZero) | S (third-hand) + N |
| 6 Approximation, nondeterminism | no free approximation; O5-12; belief-set quotient | **C-38** (S-6…S-8: Φ needs a loss; layered pipeline) as motivation; the rest P (O-5/O-6 mandates) | P + N |
| 7 Records, recoverability | outcome log greatest; D10-1; privacy reduction | none. Prompted by D10 (event recording) and the research mandates | P + N |
| 8 Verification | confusable-pair criterion | none | P + N |
| 9 Multi-step verification | F2-7J (S-joint) | none | P + N |
| 10 Contestability | dominance; separations | none | P + N |
| 11 Corrections, bitemporal | bitemporal records, merge, horizons | motivation only: **C-0901** (S-10 non-monotone evolution); T-4 (REVISE/RETRACT, via family) | P + N (+ I) |
| 12 Privacy, deletion, retention | P4, deletion vs retroactivity | T-7 k-anonymity (family) as remote motivation | P + N |
| 13 Storage | encoding results | none | P + N |
| 14–16 Synthesis, traceability, validation | assembly; architecture map; validation plan | corpus constructs cited as **SRC** only (5 core operations, Gap/Zero) | N + I |

**What this table shows.**
- **Only Ch. 1, 3 and 5 rest substantially on corpus text**, and Ch. 5 only third-hand, through Session A's family summaries.
- **Ch. 7–13, the bulk of v1, are not corpus reconstructions.** They are new mathematics answering governance questions.
- **No result is "U":** everything traces to a read source, a Principal mandate, or a derivation.
- **The Conceptual Foundation document played no part in v1.** It was read only afterwards, for the coverage audit.

## 4. Corpus areas and themes not represented in v1

**By folder:** none of the following has been opened:
- `verification` (472);
- `mathematical_ideas_that_can_be_implemented` (372);
- `reviews/synthesis` (305) and `reviews/kernel` (111);
- `brainstorming/kernel` (157);
- the theory-simulation and kernel-reduction research (69).

The folder names alone indicate verification theory, implementable mathematics, prior syntheses and kernel experiments, all directly relevant to v1's subject.

**By theme.** Keyword counts over the **names** of the 2,457 allowed family summaries are a rough indicator only:

| Keyword | Family names containing it |
|---|---|
| gita | 142 |
| kernel | 110 |
| theory | 99 |
| evidence | 93 |
| invariant | 92 |
| decision | 81 |
| gap | 74 |
| semantic | 66 |
| zero | 62 |
| audit | 49 |
| authority | 47 |
| identity | 43 |
| governance | 43 |
| operation | 43 |
| layer | 42 |
| contract | 42 |
| dimension | 38 |
| provenance | 33 |
| transform | 33 |
| measure | 24 |
| agent | 20 |
| primitive | 17 |
| ontology | 15 |
| unknown | 14 |
| collapse | 11 |
| topolog | 5 |
| privacy | 1 |

Themes heavily present in the corpus but **absent or only partial in v1**:
- the Gita-derived model (142 family names; the v0.4-read document C-0901 is "Gita-inspired");
- invariants;
- semantics and meaning;
- Zero and UNKNOWN;
- dimensions;
- identity and provenance across transformation;
- operations and the five core operations;
- primitives (the eight primitives, C-30);
- layers (the C-38 pipeline);
- measure and probability (the folder `phase_measure_theory` itself; v1 is deterministic only).

**Caution:** name counts say nothing about content or quality. They show where to look, nothing more.

## 5. Contradictions, superseded versions, dependencies and access gaps (from records already held)

| Type | Item | Source |
|---|---|---|
| **Term conflict** | "Zero": neutral or unresolved start (Conceptual Foundation, H-ZERO-001) vs gap = ∅ (Gap theory, T-2; v1 Ch. 5) | coverage audit 0737 |
| **Symbol collision** | "K" has seven independently coined definitions in the corpus (family T-4, group G0759); v1/v0.4's K would be an eighth | v0.4 §15.8 |
| **Competing orderings** | K-first (step 285) vs EC/contract-first (Gap theory); chronology unresolved | v0.4 §15.8 |
| **Version lineage unclear** | theory v1.0 → v1.1 → v1.2 ("FROZEN", T-7) → v2 rewrite (T-4). Which is current is **unknown**, and source-ID order is not chronology | v0.4 §15.7 |
| **Second-hand dependence** | T-1…T-8 are Session A reconstructions ("a handle, not an identity claim"). The primaries S2373, S2763 and S2871 and the gap-theory and v1.1 primaries are **unread** | SOURCE-REGISTER, D5 scope |
| **Read-log inaccuracy** | `20-FAMILIES/knowledgeos-kernel-concept.md` is logged but was **never opened** (no correction line yet). `00-INDEX.md` was logged and corrected. Family reads were partial (header, births, rationale) | READ-LOG; earlier reports |
| **Governance-record conflicts** | X-1…X-13 (branch copies of the log, ARCH authority, RI-1, label counts, stratifications, prereg changes vs Freeze 1, …). These concern the empirical programme, not v1's mathematics | SOURCE-REGISTER |
| **Access gaps** | gate BLOCKED (D1 review, human test, gate act pending); D5 scope = 5 sources; EKS §7.4 and EKS S-1/S-11 (cited by the Conceptual Foundation) unread | G-LOG-0146, G-LOG-0147 draft |
| **Disconnected programme** | v0.4 Part I (Ch. 1–14: the S-Series empirical study, prereg_v2, H1–H5, null models) is **not referenced by v1**. How the formal theory relates to the empirical measurement programme is unstated | manuscript v0.4 vs v1 |

## 6. Prioritized next step

1. **Correct v1's self-description, without touching the mathematics.**
   - v1 should state that it is a formal core: **Part II partially reconstructed** from a handful of sources, **Part III newly derived**.
   - Its corpus basis should be stated (11 documents), together with this report's gaps. *This needs the Principal's approval to edit.*
2. **Plan a structured corpus-coverage pass,** for when the gate passes or by further recorded exceptions.
   - Start with the **family summaries** in the theory-level class (814). Sample by theme from §4, not by convenience, and use a pre-declared sampling protocol.
   - Then the unread folders, in this order:
     - `reviews/synthesis` and `reviews/kernel`, the corpus's own syntheses (cheapest map of the whole);
     - `verification`;
     - `mathematical_ideas…`.
   - Every read goes through the approved filter and is logged.
3. **Only after that pass:** decide the layered structure, the naming (Zero) and the new chapters.

## 7. Decisions for the Principal (separate from findings)

- **Approve** correcting v1's self-description (§6.1).
- **Authorize the coverage pass:** its scope, its order, and whether it waits for the gate or uses recorded exceptions. Reading the synthesis reviews first is recommended.
- **Record** today's exception read in the governance log. **Fix** the read-log entry for `knowledgeos-kernel-concept` (logged, not opened); Session B can append the correction line if authorized.
