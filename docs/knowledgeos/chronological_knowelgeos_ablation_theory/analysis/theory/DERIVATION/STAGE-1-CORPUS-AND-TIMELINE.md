# Chronological derivation of the KnowledgeOS theory, stage 1: the corpus and its timeline

**Draft r0, 2026-10-30. Working document; not ratified; not reviewed.** This is stage 1 of the whole-theory derivation the human asked for: read the corpus from start to end, in time order, and derive the theory as it develops. Stage 1 fixes *what* is read and *in what order*. It derives no theory yet.

**Data:** `stage1-timeline.json` (one row per corpus file: F-id, path, date from the filename, date of the first git add, character count).

---

## 1.1 What the corpus is

The F-series population is the frozen reading list `docs/knowledgeos/20260925_1206_list_of_files_to_read.log`. It has **1,523 entries** (F-LOG-0002; `F-MANIFEST.jsonl`).

| measure | value |
|---|---|
| entries | 1,523 |
| with content | 1,508 (15 empty or unresolvable, 1 binary) |
| distinct contents (sha256) | 1,385 (123 exact duplicate copies) |
| distinct prose (`.md`) | **1,332 files, 29.4 million characters** |
| machine-generated data (`.jsonl`, `.tsv`) | 8 files, 54.9 million characters, from an earlier reconstruction (§1.5) |
| code | 38 `.py` files, 0.27 million characters |

The distinct prose amounts to roughly 7 million words. **The subject is KnowledgeOS itself:** what knowledge is, what the kernel of a knowledge system is, and its formal mathematics, philosophy and architecture.

| collection | files | content |
|---|---|---|
| `knowledgeos_theory_research/corpus/knowledge_os_with_lina_puran` | 757 | research conversation records |
| `knowledgeos_theory_research/corpus` (others) | ~293 | numbered research notes (kernel tuple (K, S, M, I, A), state space, gap, probability) |
| `theory-extraction` | 172 | an earlier reconstruction and audit programme (§1.5) |
| `phase_measure_theory` | 71 | the measure and phase formalisation, with "lens" studies |
| `mathematical_ideas_that_can_be_implemented` | 63 | implementable mathematics |
| `backlog` (EKS-01 … EKS-51) | 57 | engineering-knowledge-system issues |
| `philosophical_questions` | 27 | Hegel, Deleuze and others |
| `kernel` | 24 | the kernel and its "lenses" (Shannon, Chalmers, Åström, Cavell, …) |
| `brainstorming/corpus`, `synthesis`, `falsification`, misc. | ~27 | meta and synthesis notes |

## 1.2 Dating

- **1,221 files** are dated by a date in the filename.
- **257 more** are dated by their first git commit.
- **45 cannot be dated** (templates, stage stubs, broken names).
- No file's git date is earlier than its filename date, so the two sources never conflict.

**The frozen reading list is not in time order.** It begins with F3082, a philosophical file of 2026-09-23. Reading in list order is therefore not chronological reading. The derivation will use date order.

## 1.3 The timeline: seven phases

The phases are read off the date-by-folder activity table (computed). Their boundaries are approximate.

| phase | dates | dominant material | what the theory is doing (from titles only; not yet read) |
|---|---|---|---|
| **P0 Engineering origin** | 08-16 → 08-24 | EKS backlog, architecture baseline | problems in a governed engineering knowledge system (distribution, placement, lifecycle, activation) |
| **P1 Kernel lenses** | 08-24 → 08-28 | `kernel` | what knowledge is: "agent memory is not KnowledgeOS", Shannon–Weaver, consciousness as an analogy, "knowledge as a measurable construct" |
| **P2 Formalisation** | 08-25 → 09-06 | `phase_measure_theory` (+ synthesis and falsification on 08-28) | "knowledge is probably not the kernel object", requirements, satisfaction, Zero, epistemic contracts, lenses (Zero, Lord, Krishna, Atman) |
| **P3 Implementable mathematics** | 09-01 → 09-11 | `mathematical_ideas_that_can_be_implemented` | mathematics turned into implementable structures |
| **P4 First reconstruction** | 09-02 → 09-11 | `theory-extraction` | a chronological reconstruction of P1–P3, and its audits |
| **P5 Research corpus** | 09-11 → 09-20 | `knowledgeos_theory_research` (290 notes, then 757 conversation records) | the bulk: the kernel tuple, state space, gap, probability, a long dialogue |
| **P6 Philosophical turn** | 09-23 → 09-25 | `philosophical_questions` | Hegel, Deleuze and others, applied to the theory |
| *(09-30)* | 09-30 | `brainstorming/corpus`, the list file | the set-up of the F-series itself |

**Volume by period:**
- 08-16 to 09-06: about 160 files.
- 09-07 to 09-11: about 390 files.
- 09-13 to 09-20: about 1,050 files.
- 09-23 onwards: about 65 files.

## 1.4 What was actually read, and by whom

| effort | population | deep-read coverage | output |
|---|---|---|---|
| **F-series** (this lane, from 2026-09-25) | the 1,523-file list | **1 file** (F3082, READ-COMPLETE); 1,522 registered and identified, never opened (`F-SERIES-STATE.jsonl`) | — |
| **F-series R1–R7** (2026-09-26 → 10-17) | **a different source:** the platform rulings register `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (86 lines, 2026-07-10 → 08-04), plus session logs to S0815 and one commit (R3 item G01) | the register and targeted spans | a theory of **norm-making in governed engineering** (H-D vs H-B, R4 r3.1), its DDD derivation, the KC work |
| **Earlier reconstruction** (`theory-extraction/reconstruction`, 2026-09-02 → 09-11) | its own queue of 5,998 entries (2,099 readable; 3,887 firewalled) | about 1.5 % complete or substantial (its own ledger, `08-COVERAGE-LEDGER.md`) | `TheoryState(t₀ … t₆)`, 2026-08-25 → 08-28 in detail; 9,830 symbol birth points; a definition-evolution registry; the verdict "v1.2 cannot be closed; the block is not mathematical" |

**Consequence.** The theory the F-series has derived so far (R4, R7, KC) is a theory of a **narrow slice**: how rulings make norms in the engineering programme. It is **not** the theory the 1,523-file corpus develops, which is the KnowledgeOS theory of knowledge, kernel, gap and state. **No one has yet read the corpus from start to end.** The earlier reconstruction got furthest. Its chronicle ends on 2026-08-28, at about the start of phase P2.

## 1.5 Prior assets the derivation will reuse (not trust blindly)

- **`theory-extraction/reconstruction/07-THEORYSTATE-CHRONICLE.md`:** `TheoryState(t₀ … t₆)`, the earliest verified states. t₀ (2026-08-25) is the requirements apparatus. t₂ (08-27) is the turning point where Sat, ℛ(P), the epistemic contract, Coverage, Ready and Gap appear. t₃ brings Zero, which "is not a metric".
- **`03-BIRTH-POINTS.tsv`, `05-DEFINITION-EVOLUTION-REGISTRY.tsv` and `04-LINEAGE-EDGES.tsv`:** where each symbol first appears, and how its definition changes.
- **`reconciliation/` and `resolution/`:** about 59 object families, 0 merges, an 11-cycle in the dependency closure, and "v1.2 cannot be closed".
- **`theory-extraction/elements/`:** KOS-T element cards (K, δ, eq-sem, Qualify, Σ, Π, Zero, I-register).
- **The F-series R1–R7 results:** to be placed where their subject (governed norm-making) enters the timeline, as one validated sub-theory.

## 1.6 Reading plan for stages 2–9

1. **Order:** by date, then by time within the day. Duplicates are read once (by sha256). The machine-generated `.jsonl`/`.tsv` files are used as indexes, not read as prose.
2. **Unit:** one phase window at a time (P0, P1, …). Each window produces three things:
   - a **`TheoryState(t)` entry**: objects, definitions, relations, invariants, contradictions, branches, and status (conceptual / formal / operational);
   - a **change log**: births, changes, falsifications and corrections, with file:line evidence;
   - **corrections**: where a later text shows an earlier claim wrong, the derivation says so, and says what replaces it.
3. **Reuse:** for P1–P2, start from the earlier `TheoryState(t₀ … t₆)` and verify it against the sources. Do not re-derive it blind.
4. **Size:** about 29 million characters of prose. P5 (about 1,050 files) is the bulk and will take several windows. Thin or administrative files (templates, stage stubs, duplicates) are logged and skipped.
5. **Output:** one growing document, `DERIVATION/THEORY-CHRONOLOGICAL.md`, extended stage by stage. No governance apparatus; one review at the end, or where a step is contested.

## 1.7 One decision for the human

**Is the subject of the derivation the KnowledgeOS theory that the 1,523-file corpus develops?** Recommended: yes, and it is what "whole theory, start to end" implies. The alternative is the norm-making theory the F-series has derived from the rulings register.

The recommendation is the first. The rulings-register theory (R4/R7) then enters as a validated sub-theory at the point in the timeline where governed engineering appears (P0 and the EKS backlog), not as the whole.

## 1.8 Limits of this stage

- Every phase description comes from **titles and dates only**. Nothing has been read yet; reading starts in stage 2.
- The phase boundaries are approximate and will be revised as files are read.
- Filename dates may be the date of a conversation rather than of the file. The two sources agree wherever both exist.
- This stage was produced by a Claude-family analyst (Λ7).
