---
artifact: 01-CORPUS-INVENTORY
date: 2026-08-30
status: MEASURED
---

# 01 · Corpus Inventory

All counts measured, not estimated.

## 1. Scale

| Location | Files |
|---|---|
| `brainstorming/phase_measure_theory/` | **527** |
| `brainstorming/kernel/` | 163 |
| `brainstorming/` (top level) | 102 |
| `reviews/` | 191 |
| `architecture/` · `governance/` · `backlog/` | 35 · 9 · 12 |
| **Total under `docs/knowledgeos/`** | **1 737 files · 1 555 `.md` · 15 `.py`** |

## 2. The numbered step sequence

**331 step files. Steps 001 → 271, with 217, 229, 268 absent and several duplicated.**

> **The highest step is `271 — POLICY SEMANTIC MINIMALITY AND ASSESSMENT BOUNDARY`
> (`20260830-204917`). It commissions a Step 272 that does not exist.**
> **The research track was mid-flight when it stopped.** This single fact bears directly on any
> completeness claim and is developed in `14-INDEPENDENT-VERDICT` §1.

**Filename hygiene hazard, measured:** ~30 step files carry raw titles beginning `# Step …` with no
`.md` extension, spaces and `#` in the name. Several exist in both raw-titled and properly-named
form. Any tool globbing `*.md` silently misses them.

## 3. Contamination boundary — measured by timestamp

The verification programme's first artifact is `verification/V0-theory-corpus-map.md`,
**2026-08-29 14:29**.

| Band | Steps | Files | Status |
|---|---|---|---|
| **A · pre-programme** | 001 – 205 | 210 step files | **independent of the verification track** |
| **B · concurrent** | 206 – 271 | **121 step files (37%)** | written *during* the programme |

**Eleven step files in band B explicitly cite verification artifacts.** Step 269 opens:
> *"I read the prompt you supplied **and** cross-checked it against the later verification artifacts
> already present in your corpus … the later verification produced artifacts A–J."*

> **CONSEQUENCE, and it cuts both ways.**
> **Steps 269–271 cannot be used as independent corroboration of any verification finding** — they
> have read it. **But a downstream source that has read a closure claim and still records
> `does not declare closure` is disagreeing, not echoing** — and disagreement downstream is
> *stronger* evidence than agreement downstream. Step 271's negative result is therefore admissible
> against the closure claim, and its positive results are not admissible for it.

## 4. Document classification

| Class | Where | Authority as **declared by the corpus itself** |
|---|---|---|
| **Historical research** | `brainstorming/**` (792 files) | non-authoritative, declared |
| **Candidate theory** | `reviews/synthesis/model/canonical-architecture-v0.2.md` | **AUTHORIZED canonical model** (GN-19), md5-pinned |
| | `reviews/synthesis/final-architecture/FA-1..FA-9` | **RATIFIED** (GN-31), OQ-1…12 open by ruling |
| **Constitution / governance** | `architecture/…Constitution-v1.0` + `analysis/governance-notes.md` | accepted; prose-normative |
| **Verification** | `verification/**` (prior), `verification/independent/**` (this) | claims under test |
| **Implementation** | `docs/knowledge/**` (EKP), `.claude/runtime/workflow/**`, `app/Contexts/**` | running systems |
| **Book production** | `reviews/synthesis/book/`, `book-edition-2/` | derived exposition; **forbidden from exceeding its sources** |
| **Computational witnesses** | `analysis/mathematical-tests/*.py` | *"Testing tool only; nothing here is architecture"* |

## 5. Documents labelled authoritative that are not

Per mandate §1 — *do not assume a document labelled "canonical/final/closed" is authoritative*:

| Document | Label | Actual status |
|---|---|---|
| `…the-complete-mathematical-model.md` | "**Complete** Mathematical Model" | Stratum-1 raw record; superseded by ~120 later steps |
| `# Step 201 — Canonical Vocab…` | "**Canonical** Vocabulary Freeze v0.1" | non-authoritative; vocabulary + relations only, **no theorems, no proofs** |
| `verification/CANONICAL-KNOWLEDGEOS-THEORY.md` | "**Canonical**" | a verifier's reconstruction; **never ratified by any governance act** |
| `verification/THEORY-CLOSURE-AUDIT.md` | "**24/24 … CLOSED**" | the hypothesis this pass tests |
| `model/logical-reconstruction.md` | — | self-declares *"CANDIDATE MODEL — explicitly NOT the final architecture"* ✅ honest |

> **Only two artifacts in the entire corpus carry a real authority act: `canonical-architecture-v0.2`
> (GN-19) and `FA-1…FA-9` (GN-31). Everything else describing "the theory" is non-authoritative,
> including every artifact produced by the verification track.**
