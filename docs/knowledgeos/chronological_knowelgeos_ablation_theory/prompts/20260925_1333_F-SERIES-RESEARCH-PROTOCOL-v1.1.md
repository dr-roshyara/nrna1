# F-SERIES RESEARCH PROTOCOL — v1.1

**Status: FROZEN FOR REVIEW (F-LOG-0004). Not executable on any F-file until the human/architect review is recorded
(F-LOG-0005). Supersedes `20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md` (kept unchanged, frozen by F-LOG-0002).**
**Written:** 2026-09-25 13:33 · HEAD at writing `e2441a975` · lane folder
`docs/knowledgeos/chronological_knowelgeos_ablation_theory/` (the only writable location).
**Companions:** per-file runbook `prompts/20260925_1333_F-SERIES-AGENT-CONTRACT-v1.1.md` · the fourteen contracts
`prompts/contracts-v1.1/C01…C14`.

> **The Claude session is not the state. The repository artifacts are the state.**
> **READ-COMPLETE ≠ CONTENT-COMPLETE ≠ RESEARCH-COMPLETE.**
> **No substantive source content may be discarded merely because it is not currently recognized as relevant to an
> existing KnowledgeOS theory.**
> **SOURCE → OBSERVATION → STRUCTURE → HYPOTHESIS → TEST → SUPPORTED / UNSUPPORTED / UNDETERMINED.**

---

## §F-0 What changed from v1.0, and why

| Change | Human source (2026-09-25) |
|---|---|
| F-Series is redefined as a **research-discovery programme** (§F-1), not a per-file application of P3b | "Design F-Series as a research-oriented corpus discovery programme, using S-Series' verified reading, provenance, audit and methodological controls as the foundation." |
| A gated **Complete Content Extraction** layer before any reconstruction or interpretation (§F-8A, C02, C03) | "please strongly follow this suggestion" (content-preservation instruction) |
| **Three output levels** L1 / L2 / L3 (§F-1A) | the research-oriented instruction |
| **Lens checklist** per file; statistical/ML only at checkpoints (C04–C09) | human choice "Checklist, where warranted" |
| **Cross-file discovery** both incremental per file and at checkpoints (C05) | human choice "Both" |
| **Hypotheses pre-registered per file, tested only at checkpoints** (C10, C11) | human choice "Pre-register per file, test at checkpoints" |
| **Fourteen contracts** (C01–C14) | the research-oriented instruction |
| Pacing: v1.1 is frozen and reviewed **before** F3082 continues | human choice "Freeze v1.1 first, review" |

Unchanged from v1.0 and still binding: §F-0 governing sources, §F-2 population and order, rulings RL-01…RL-10
(F-LOG-0002), the reading-integrity mechanism, the isolation rule, identity and registration (F-P0 is **not** re-run;
the manifest `90cba97f…` and the state ledger stay valid). v1.0 sections not restated here apply as written in v1.0.

---

## §F-1 Purpose

> **An exploratory, exhaustive, evidence-preserving research programme for discovering concepts, definitions,
> mathematical structures, mechanisms, relationships, hypotheses and potential theoretical contributions contained in
> the F-corpus.**

The highest-level output of F-Series is:

> **"These are the research discoveries supported by the F-corpus, these are the hypotheses they generate, these are
> the tests performed, and this is the remaining uncertainty."**

F-Series never concludes "this is the KnowledgeOS theory". Theory synthesis is a separate, later stage outside this
protocol (C14). Canonicalization is deferred; theory adoption is never automatic.

## §F-1A Three output levels (never collapsed)

| Level | Question | Artifacts | Epistemic classes | Inherits |
|---|---|---|---|---|
| **L1 Source preservation** | What exactly does the file say? | `UNITS`, `CONTENT-INVENTORY`, `UNIT-DISPOSITIONS`, `CATEGORY-CHECK`, `files`, `contributions`, `index-proposals` | `SOURCE` | v3.5 P1 + XC (layer A of P3B §1B) |
| **L2 Analytical discovery** | What structure, relationship, mathematical idea, logical rule or mechanism can we identify from it? | `ANALYSIS`, `ANALYSIS-CHECKLIST`, `CROSS-FILE`, checkpoint analyses | `RESEARCH-OBSERVATION`, `DOMAIN-INTERPRETATION`, `EXTERNAL-THEORY-COMPARISON` | P3B layer B |
| **L3 Research hypothesis** | What could this imply for KnowledgeOS, and how could we test it? | `research` (pre-registered), checkpoint tests and findings | `RESEARCH-SUGGESTION`, `HYPOTHESIS`, `THEORY-CANDIDATE` | P3B layer C |

Rules:
1. L2 and L3 never write into an L1 field, and nothing at L1 is phrased as an interpretation (P3B §1B rule 1).
2. Every L2 record points to the L1 inventory items it rests on. Every L3 record points to L1 and/or L2 evidence.
3. L1 never depends on L2 or L3.
4. A file-level summary (`files.summary`, page digests) is written **after** exhaustive extraction and never replaces it.

---

## §F-2 Population and order — unchanged from v1.0

1,523 F-IDs, in the line order of the committed list (`abc0a9153`). The sequential gate applies: F-ID *n* enters
`READING` only when every earlier F-ID is `AUDITED`. No look-ahead: evidence may cite only this and earlier AUDITED
F-IDs.

---

## §F-3 Inheritance and extension

### §F-3.1 S-inheritance / F-extension matrix (component level; requested for the v1.1 review)

| Component | S-Series | F-Series v1.1 | Treatment |
|---|---|---|---|
| Reading integrity | RC3 paged reader (G-LOG-0050); pages, hashes, coverage | the same mechanism: page model compiled from the committed, sha256-pinned blob `b7e7fc856`; plus supplementary page digests (RL-08) | **REUSED** + F-specific digest |
| Provenance | v3.5 R6–R11; P3B §18 | the same principles; chain F-ID → file → page → unit → inventory item → contribution → analysis → hypothesis (C12) | **INHERITED-UNCHANGED**, chain extended |
| Audit | v3.5 A8 self-audit; P3B §21 | per-file mechanical audit always; independent fresh-agent audit on the first text F-ID and every fifth; checkpoint audits (C13) | **ADAPTED** (F audit) |
| Content extraction | not primary (P1 captures contributions) | exhaustive, unit-covered, category-checked inventory before reconstruction (C02, C03) | **F-SPECIFIC** |
| Term / definition registry | labels as handles (v3.5 R5) | a definition record per definition (term, verbatim source definition, context, location, related terms, examples, qualifications); labels stay handles | **F-SPECIFIC** (R5 kept) |
| Research lenses | targeted, per label (P3B §9A, §9C) | a per-file lens checklist: structural, mathematical, logical, DDD; statistical/ML at checkpoints (C04, C06–C09) | **F-SPECIFIC** |
| Cross-file discovery | P3 reconciliation and P3B §9E passes over the S population | a research objective: incremental per file (no look-ahead) + checkpoint corpus passes; no identity decisions (C05) | **F-SPECIFIC** (v3.5 R5, R12 kept) |
| Statistical / ML | S5-specific controls, hold-out H-19 | checkpoint research only, pre-registered when used as a test; exploratory descriptive statistics labelled as such (C09) | **F-SPECIFIC** |
| Hypothesis generation | P3B §13 register, layer C | a Level-3 record per hypothesis, pre-registered at birth (C10) | **ADAPTED** from P3B §13, §13.10a |
| Hypothesis testing | P3B §13.10, §13.10a; S5c | tests only at checkpoints; A–D searches with population, method, completeness, bound; outcomes SUPPORTED / UNSUPPORTED / UNDETERMINED (C11) | **ADAPTED** |
| State machine | P3b label states; S5 run states | an independent F state: per F-ID event ledger + checkpoint states (C13) | **F-SPECIFIC** |
| Ledgers | S ledgers | `F-SERIES-STATE.jsonl`, `F-READ-INTEGRITY.jsonl`, `ledger/F####/`, checkpoints `checkpoints/CP-##/` | **F-SPECIFIC** |
| Governance | `P3B-GOVERNANCE-LOG.md`, §26 | `F-GOVERNANCE-LOG.md`; versioned protocol files; human decisions only (C14) | **F-SPECIFIC**, same discipline |
| Canonicalization / theory adoption | deferred (v3.5 P5–P7, GATE) | deferred; never automatic; a separate later synthesis stage | **INHERITED-UNCHANGED** |

**Invariant of this matrix:** no row changes an S-Series artifact. Every F-SPECIFIC or ADAPTED row is an F execution rule,
recorded in F change control; S methodology stays as frozen.

### §F-3.2 Rule-level matrix

The v1.0 matrix (v1.0 §F-3 rows 1–30, with RL-03/RL-04/RL-08 applied) remains in force. v1.1 adds:

| # | Requirement | S source | F treatment | Why |
|---|---|---|---|---|
| 31 | Exhaustive content inventory with unit coverage, dispositions, category checklist | — (strengthens v3.5 R0) | F-SPECIFIC | R0 says "preserve", but gives no way to check that preservation happened; the gate makes it checkable |
| 32 | Every inventory item carried by ≥ 1 Phase-1 contribution | v3.5 R0, R3 | F-SPECIFIC gate | makes "nothing dropped in reconstruction" a mechanical property |
| 33 | Level-2 kinds (OBSERVATION, STRUCTURE, GAP, INTERNAL-TENSION, CORRECTNESS-FINDING, SCHEMA-LIMITATION, METHODOLOGICAL-DEFICIENCY) | P3B §13.2 layer-B kinds | ADAPTED — `STRUCTURE` (a structure identified in the source) is Level 2; `INTERNAL-TENSION` and `CORRECTNESS-FINDING` added | the human chain puts STRUCTURE before HYPOTHESIS; a proposed structure *for KnowledgeOS* remains a Level-3 `STRUCTURE-CANDIDATE` |
| 34 | Level-3 kinds (SUGGESTION-RESEARCH, SUGGESTION-METHOD, HYPOTHESIS, STRUCTURE-CANDIDATE) | P3B §13.2 layer-C kinds | INHERITED-UNCHANGED | — |
| 35 | Pre-registration at birth | P3B §13.10a | ADAPTED — fields `population_rule, temporal_scope, selection_rule, comparison_rule, stopping_rule, prediction, registered_after_f`, outcome vocabulary fixed | per-file birth, checkpoint testing |
| 36 | Relation hints for cross-file candidates | v3.5 A11 relationship set | ADAPTED — the set is used as a *hint* only, plus `CONTRADICTION`, `ANALOGY`; identity is never decided | v3.5 R5: identity belongs to reconciliation |
| 37 | Checkpoints every N AUDITED text F-IDs | — | F-SPECIFIC — N proposed = 50 (FD-11) | cross-file passes, statistics and tests need accumulated evidence |

---

## §F-6 State machine (v1.1)

```
REGISTERED → RESOLVED → IDENTIFIED → READING → READ-COMPLETE → CONTENT-EXTRACTED → RECONSTRUCTED → ANALYZED → RESEARCHED → AUDITED
exceptions (each then audited → AUDITED | AUDIT-FAILED):
  REGISTERED → RESOLUTION-FAILED | FIREWALL-LIMITED        RESOLVED → EMPTY | BINARY | NON-TEXT | SELF-CITATION-EXCLUDED
  IDENTIFIED → EXACT-DUPLICATE (RL-03)                     READING → READ-PARTIAL | READ-FAILED
  READ-COMPLETE → PLACEHOLDER | CONTENT-EXTRACTION-UNRESOLVED
  CONTENT-EXTRACTED → RECONSTRUCTION-UNRESOLVED            RECONSTRUCTED → ANALYSIS-UNRESOLVED
  ANALYZED → RESEARCH-UNRESOLVED
AUDIT-FAILED → re-enter at READING | CONTENT-EXTRACTED | RECONSTRUCTED | ANALYZED | RESEARCHED | AUDITED
```

| Transition | Evidence re-derived at the moment of transition (`scripts/f_transition.py`) |
|---|---|
| → CONTENT-EXTRACTED | a READ-COMPLETE event for this run; `UNITS.jsonl` equals the deterministic segmentation; every non-markup unit is covered by an item or dispositioned; math/code units and definition-cue units are covered by items (never dispositioned); every item's verbatim quote lies inside its covered units; definition/theorem/reference fields are complete; `CATEGORY-CHECK.json` counts match for all 26 categories |
| → RECONSTRUCTED | a CONTENT-EXTRACTED event for this run; the inventory still holds; XC records are valid; every contribution cites inventory items; **every inventory item is carried by ≥ 1 contribution** |
| → ANALYZED | Level 1 still valid; the lens checklist is complete (APPLIED needs ≥ 1 record for that lens; only STATISTICAL-ML may be DEFERRED-TO-CHECKPOINT); every analysis record cites this file's inventory items; every cross-file candidate is dispositioned; RELATED needs a relation hint and quotes on both sides (the other side from that file's audited ledger) |
| → RESEARCHED | Level-3 kinds only; HYPOTHESIS / STRUCTURE-CANDIDATE carry falsification, validation question, competing hypotheses, disconfirmation search and a **pre-registration block**; **no outcome** field |
| other transitions | as v1.0 §F-6 |

Checkpoint states (C13): `CP-## PLANNED → OPEN → RUN → AUDITED`, recorded in `checkpoints/CP-##/STATE.jsonl`.

---

## §F-8 Per-file lifecycle (v1.1)

| Step | Artifact → gate | Contract |
|---|---|---|
| A Resolve · B Identify | manifest (F-P0, done) | C01, C12 |
| C Read | READ-LOG, PAGE-DIGESTS → **READ-COMPLETE** | C01 |
| D1 Complete content extraction | UNITS, CONTENT-INVENTORY, UNIT-DISPOSITIONS, CATEGORY-CHECK → **CONTENT-EXTRACTED** | C02, C03 |
| D2 Structural reconstruction (XC, inherited) | files, contributions (with `inventory_refs`), index-proposals → **RECONSTRUCTED** | C04 (+ XC) |
| E1 Analysis (L2): lens checklist + incremental cross-file | ANALYSIS, ANALYSIS-CHECKLIST, CROSS-FILE → **ANALYZED** | C04–C09 |
| E2 Hypothesis generation (L3), pre-registered | research → **RESEARCHED** | C10 |
| G Audit | AUDIT(.json, -LOG), INDEPENDENT-AUDIT → **AUDITED** | C13 |

**§F-8A Content-completeness rule.** A file receives `CONTENT-EXTRACTED` only after the extractor has systematically
checked the entire file, unit by unit, against all 26 content categories. Summarising or selecting "important
concepts" before extraction is forbidden. The original file stays the authoritative source; the inventory stores
structured records with quotes and spans, not a copy of the corpus.

**§F-8B Checkpoints.** After every N AUDITED text F-IDs (FD-11, proposed N = 50), and at the end of the list, a
checkpoint runs:
1. the corpus-level cross-file pass (C05);
2. the statistical/ML analyses (C09);
3. the tests of the hypotheses registered so far (C11);
4. a checkpoint audit (C13).

Checkpoint scripts are specified in the contracts and are **implemented and tested before CP-01**; until then no
checkpoint can run (recorded limitation, §F-15).

---

## §F-11 Isolation — unchanged (v1.0 §F-11, as frozen by F-LOG-0002)

## §F-12 Execution gate (v1.1)

1. F3082 stays at `READ-COMPLETE` (run `FR-F3082-001`, integrity verified). Its reading remains valid under v1.1
   because reading rules are unchanged. The draft Phase-1 records written before v1.1 are parked in
   `ledger/F3082/_draft-v1.0/`: they are not evidence and are not consulted.
2. After the review (F-LOG-0005), F3082 continues at step D1 under v1.1, then independent audit, then a report to the
   human. F3083 is not opened without a further human go-ahead.
3. No parallel reading, no production-scale continuation, until the human authorizes it.

## §F-14 Open decisions for the v1.1 review

| Id | Decision | Proposed default |
|---|---|---|
| FD-11 | checkpoint interval N | 50 AUDITED text F-IDs, plus the end of the list |
| FD-12 | the S lane withdrew the reader's stdout-redirect refusal at `52fbe3c3f` (G-LOG-0052: the harness captures stdout into a file; the refusal cannot detect pipes). F stays pinned to `b7e7fc856` (refusal on; reads use `… \| cat`). Re-pin to the withdrawal, or keep? | re-pin to `52fbe3c3f` after verifying that the page model is byte-identical, recorded as a REUSED update; the integrity proof (pages + hashes + coverage + digests) is unchanged either way |
| FD-13 | statistical/ML methods admitted at checkpoints (C09) | the list in C09 §3; anything else needs a protocol version |
| FD-14 | independent audit cadence under the larger per-file workload | unchanged: first text F-ID and every fifth; every checkpoint |

## §F-15 Known limitations (in addition to v1.0 §F-15)

- **Unit coverage is a completeness floor, not a proof of understanding.** A unit can be covered by a thin item. The
  checks that bite are the definition-cue rule, the math/code rule, the category checklist and the independent audit's
  own inventory comparison.
- The definition-cue detector is lexical (`is defined as`, `we call/define`, `denotes`, Definition/Theorem/… headings,
  `**term** is/are/means`). A definition phrased otherwise relies on the unit rule and the audit.
- Checkpoint scripts (C05 corpus pass, C09, C11, C13 checkpoint audit) are specified, not yet implemented.
- Per-file cost rises substantially (the inventory is exhaustive); throughput is lower than v1.0 by design.
