# Phase 5B — Transition-Language Census and the K-1 Provenance Summary

## Part 1 — Transition-language census, classified A–E on a defined sample (earliest occurrence per marker, `PRIMARY`-tier only)

**Selection rule, fixed before inspection**: for each marker with a non-zero `PRIMARY`-tier hit
count, the single earliest-sequence occurrence was read (title + per-file record) — a reproducible,
non-convenience selection rule (not necessarily the most "interesting" hit, deliberately). This is a
**sample, not a census**, of content-level classification; the hit *counts* themselves (§
`01_population-and-method.md`) are the census.

| Marker | `PRIMARY` hits (census) | Earliest seq (sample) | Title | Classification (A–E) |
|---|---:|---:|---|---|
| "the previous Kernel" | 0 | — | — | **No occurrence — negative finding, see Part 3.** |
| "what actually is knowledge" | 0 | — | — | **No occurrence — negative finding, see Part 3.** |
| "from EKS" | 2 | 0078 | "How to Integrate Current EKS into KnowledgeOS Kernel (revised, after reading 0077)" | **A — explicitly evidenced transition** (the title itself states the derivation; already corroborated by seq 0075's parallel title) |
| "replaces" | 46 | 0058 | "KnowledgeOS DDD Architecture v3.0 (self-correction of 0057)" | **A — explicitly evidenced transition** (self-declared as a same-thread correction of the immediately preceding file) |
| "motivated by" | 62 | 0085 | "KnowledgeOS consolidation review — kernel boundary filter" | **B — strong provenance inference** (a consolidation/review document by its own title; the specific motivating relationship was not independently re-verified against raw prose in this phase — recorded at Level 3, not Level 1) |
| "derived from" | 153 | 0154 | "Round 6: Perplexity CONFIRMED in-text; exhaustive 5-layer domain-discovery pass..." | **B — strong provenance inference** (part of the already-documented, `refines`-linked multi-round AI cycle, Phase 4 §C; the specific "derived from" usage in this file was not itself raw-source-verified in this phase) |
| "reformulates" | 52 | 0199 | "Williamson's second book... mined for theory-comparison" | **C — chronological association / external-source reformulation**, not an internal corpus lineage claim — the "reformulation" here is of an external book's content, not of a prior corpus object |
| "supersedes" | 161 | 0047 | "AI Engineering Lifecycle — Five Responsibilities & Governance" (the earliest `engineering_knowledgeos`-primary file in the whole corpus) | **D — mere lexical presence, no lineage claim supported** — as the very first file in its own lineage, there is nothing prior for it to supersede; the term's presence most plausibly reflects a structured-field or template usage, not a substantive supersession claim (not independently confirmed against raw prose in this phase — recorded as a disclosed uncertainty, not asserted as fact) |

**Discipline note**: this six-row sample is not generalized to the full marker populations (46–563
hits each) — it establishes only that at least one instance of category A, B, C, and D each exists
within the sampled seqs; it does not estimate what fraction of each marker's full population falls
into which category.

## Part 2 — The K-1 provenance summary (full investigation in `02_kernel-lineage.md`, Objects 1–2)

**Restated concisely, not re-derived**: the literal string "K-1" occurs in 22 `PRIMARY`-tier files
(a complete-population, mechanical count). Raw-source verification (this phase) establishes: (a) the
"KnowledgeAggregate"/"ConflictRecord" content traces to seq 0167 (`ADR-KOS-KERNEL-001`) in raw prose;
(b) the "VerificationPort" component and the "K-1" label itself first appear together at seq 0196, but
**"VerificationPort" is not found in seq 0196's own raw `.md` source** — only in its governed per-file
YAML synthesis, meaning the commonly-repeated three-part gloss is itself a compounding, cross-file
synthesis artifact, not a single clean quotation; (c) seq 1008 (`phase_measure_theory/knowledgeos_
kernel/research/`) reuses the bare label "K-1" for a structurally unrelated construct ("`K_t`, 8
primitives, ratified") with no demonstrated connection to (a)/(b) — assessed as a likely homonym
(Level 4, moderate-to-high confidence), formally recorded as `UNRESOLVED` per this phase's own
non-negotiable default.

## Part 3 — Negative findings: literal phrases from the authorization's own example list, not found anywhere

**"The previous Kernel" and "what actually is knowledge" occur in zero `PRIMARY`-tier per-file
records** (complete-population mechanical search). This is directly relevant to Final Output G (in
`06_verification-and-completion-report.md`): **MD-007's own framing of the C1→C2 hypothesis — "what
actually is knowledge?" — is not a literal quotation from any corpus source record found by this
phase's own search.** It is MD-007's own interpretive gloss on the corpus's trajectory, written by
whoever authored that decision-log entry, not a phrase the corpus itself uses. This does not mean the
underlying *question* never arises in the corpus in different words (Part 2 of
`03_c1-c2-lineage-evidence.md` shows extensive engagement with "what is Knowledge" via other phrasing)
— it means the *specific literal framing* MD-007 used to pose the C1→C2 hypothesis is MD-007's own
construction, worth knowing before treating that framing as if the corpus stated it directly.
