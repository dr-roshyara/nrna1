# Brainstorming Corpus — Coordination-Level Index

**Artifact type:** classification / corpus-wide index
**Status:** RESEARCH · NON-AUTHORITATIVE · decides nothing
**Scope:** the **whole** brainstorming corpus, all three locations
**Closes:** the gap recorded in `README.md` and `kernel/00_CENSUS.md` §7 — *"the 99 root-level
documents have never been censused"*
**Method:** filename-derived subject family (these files are descriptively named, and the names were
authored from content) plus a concept-density matrix. **Not** a full read — same limits as the kernel
census: subject is high-confidence, role and K-level are preliminary.

---

## 1 · The corpus has three locations, not one

| Location | Docs | What it is | Census |
|---|---:|---|---|
| `brainstorming/` root | **99** | the **pre-kernel** programme: lenses, EKS/PKS baselines, governance, semantic compiler, epistemic core | **this file** |
| `brainstorming/kernel/` | **151** | the kernel-discovery corpus | `kernel/00_CENSUS.md` + 3 maps |
| `brainstorming/_misc/` | **7** | pre-existing, earliest material (2026-08-19 → 08-21) incl. one `.png` | none |
| **total** | **257** | | |

The kernel corpus is **not** a subset of the root corpus — they are disjoint sets of files.

---

## 2 · Chronology — the root corpus predates the kernel corpus

| Day | Docs | |
|---|---:|---|
| 2026-08-01 → 08-18 | 4 | scattered origins |
| 2026-08-19 | 12 | roles, DDD verification, product architecture |
| 2026-08-20 | 3 | |
| 2026-08-21 | 9 | EKS→KnowledgeOS transition thinking |
| **2026-08-22** | **60** | ⚠ **the single heaviest day in the entire programme** |
| 2026-08-23 | 8 | lens consolidation; the handover into `kernel/` |
| 2026-08-24 | 2 | (one is a cross-folder duplicate) |

Root corpus spans **2026-08-01 → 08-24**; kernel corpus **08-22 → 08-25**. The overlap is narrow: the
root corpus is largely **antecedent** to the kernel work, which makes it the more likely home of
original project thinking — the scarcest class found in the kernel census (only 6 documents there).

**⚠ 60 documents in one day** warrants provenance caution of its own: that rate is
capture-driven, and the kernel corpus showed an 8.5% duplicate rate under similar conditions.

---

## 3 · Subject families

| Family | Docs | Notes |
|---|---:|---|
| **LENS / PHILOSOPHY** | **39** | Sanskrit/Pāṇinian, Nyāya, Navya-Nyāya, Tarka, Vyāpti, Hetvābhāsa, Kashmiri Shaivism, Śiva–Śakti, chakra, Yoga-Sūtra, Gödel, topological, wisdom. **The dominant family — 39% of the root corpus** |
| **KERNEL / DDD** | 12 | boundary, aggregate, DDD interrogation, candidate-set architecture |
| **EPISTEMIC CORE** | 11 | Zero concept, truth-seeking machine, character definition/synthesis, negative epistemology, constitutional engine |
| **SEMANTIC COMPILER / SNF** | 7 | compiler architecture, SNF measurement, benchmarks, expression↔meaning invariance |
| **GOVERNANCE / ROLES** | 6 | role separation, delegation, cost optimization, session model |
| **EVENT / STATE ARCH** | 5 | event-driven refinement, state durability |
| **ENGINEERING** | 5 | LCOM4, Spring-DI principle, design patterns, business-translator |
| **EKS / PKS BASELINE** | 5 | what EKS is today, how to change EKS into a Kernel |
| **MATH / FORMAL** | 4 | where mathematics belongs, complex numbers, classical Indian logic |
| **PRODUCT / BUSINESS** | 3 | product architecture, IPO/market, election-only mode |
| unclassified | 1 | external research review |

**The largest single document in the programme is here:** `20260821-120810-kos-governance-role-cost-optimization` at **217 KB** (evidence 121, identity 28) — larger than the kernel corpus's biggest (`233040`, 235 KB is comparable; both dwarf everything else).

---

## 4 · Cross-location findings

**F-1 · The `_misc/` folder was never censused and contains the earliest material.**
Seven items from 2026-08-19 → 08-21, including `20260819-224159-linux-analogy-kernel-os-model.md`
(33 KB) — a **kernel/OS analogy document that predates the entire kernel corpus** and is not
referenced by it. Also holds the only image in the corpus (`20260821-150830-diagram.png`).

**F-2 · Two dating conventions coexist at root** — 31 files `YYYYMMDD-HHMM`, 66 `YYYYMMDD-HHMMSS`,
and one underscore form (`20260823_1239_working_state.md`). **Not corrected:** adding seconds to an
`HHMM` name would fabricate precision the record does not have. Recorded as a known irregularity.

**F-3 · One confirmed cross-folder duplicate** — `20260824-145631` (root) is byte-identical to
`kernel/20260824-151311` (topological Åström extraction). Root copy carries the `-duplicate` suffix.

**F-4 · The `kernel/` handover point is visible in the root corpus.** `20260823-103255`, `104148`,
`104251`, `105200` sit at root, and are precisely the four documents the **adjudication workbook
cites as evidence for `W:F-CM-1a`** — the `AMBIGUOUS` state question. So *formal adjudication already
depends on root-corpus documents*, which until now had no census at all. That dependency is the
strongest argument for this index existing.

**F-5 · The lens system is a root-corpus asset, not a kernel-corpus one.** 39 of the 99 root
documents are lens/philosophy, including `20260823-205735-knowledgeos-lens-system-twenty-six-lenses-consolidated`.
The kernel corpus *uses* the lenses but does not *define* them — the definitions live here.
This means **kernel-corpus conclusions drawn "through a lens" trace to a document outside that corpus.**

---

## 5 · Kernel relevance — preliminary

Not scored per document (that would need the same read Phase D owes the kernel corpus). Ranked by
family:

- **Likely K3/K4:** `KERNEL/DDD` (12) · `EPISTEMIC CORE` (11) · `SEMANTIC COMPILER/SNF` (7 — bears on
  ⟨C-1⟩ and `W:OQ-2`) · the four `W:F-CM-1a` evidence documents named in F-4
- **Likely K1/K2:** `LENS/PHILOSOPHY` (39, but it defines the instruments) · `EKS/PKS BASELINE` (5) ·
  `GOVERNANCE/ROLES` (6) · `EVENT/STATE` (5) · `MATH/FORMAL` (4)
- **Likely K0:** `ENGINEERING` (5) · `PRODUCT/BUSINESS` (3)

**Estimate: ~30 of 99 root documents are plausibly K3/K4** — comparable to the kernel corpus's
38 of 151. A corpus-wide Kernel subset is therefore closer to **~68 documents**, not 38.

---

## 6 · What this index did not do

No full read · no per-document K-score · no provenance/generation-mode field (the kernel census's
signal detection has not been run here) · no contradiction mapping across locations · `_misc/`
uncensused · nothing promoted, nothing adjudicated, nothing written to KnowledgeOS law.

**Next candidates, in value order:** (1) provenance pass over the 99, to find the *original project
thinking* the kernel census found so little of; (2) K-scoring the ~30 likely-relevant root documents;
(3) a 7-document `_misc/` census.
