# Target table of contents (a proposal)

> **Adopted as a reading order with stable section numbers** (the Principal, 2026-10-20, DB-7). No section is renumbered; the parts below say in which order to read the existing sections. Ch. 7 is retired (DB-6), and Ch. 8 is split across Parts III and V (DB-4).

*2026-10-19, written with the whole-book review S0100. Organised by the deliverables of `RESEARCH-PURPOSE.md`: the primitives, the laws, the kernel/application boundary, the open forks, and the kernel's type and existence. Cross-chapter findings from the review (`review/WHOLE-BOOK-S0100/INTEGRATION/REPORT.md`) shaped the grouping.*

## The proposed structure

| Part | Title | What it delivers | Existing sections mapped here | Missing (to write) |
|---|---|---|---|---|
| **I** | The problem and the foundations | the problem, the central question, the corpus's starting model | §0.1–§0.9; §1.8 (as a target identity, P1-1) | a short statement of the composite "valid" predicate (P1-7, I-09) |
| **II** | The primitives | each candidate primitive defined, with its bearer and an independence model or a "not tested" mark | **identity:** §1.10, §3.4, §1.5 · **lifecycle:** §2.11, §3.2, §4.3 · **authority:** §1.2, §1.6, §1.13, §1.14.1–2 · **evidence:** §1.3, §4.1, §8.5 · **relationships:** §3.6, §3.8 · **policy:** §2.0a (what counts as a rule), §2.1–§2.2, §2.9–§2.10 · **history:** §9.8 · **trust (candidate):** §9.9 · **the dimensions:** §3.1, §3.3 | **a primitive table** with bearers and independence models (I-02, M5); the F13 attribute signature; a glossary of qualified terms (M10) |
| **III** | The laws and the derivation chain | the laws between the primitives, each with its premises, and what follows from what | §1.1 (knowledge does not execute; F11, M12), §1.4 (detail does not confer authority; closing criteria), §1.6 (A1 with named readings, M8), §1.7, §1.12, §1.14.3–5, §1.16, §1.17; §2.3–§2.8 (conflict and applicability); §5.4 (the gap operator); §5.5–§5.7; §8.6–§8.11 (verification acts as a law family; DB-4); §9.4 | **the laws of change** (Ch. 4's promise; P3-10, M13); one statement of the producer ≠ verifier family (P5-5, I-16); the chain from primitives to laws as one derivation (BOOK-OUTLINE L43) |
| **IV** | The kernel: its type, existence and boundary | the kernel as a typed object, its required capabilities (Req), the evaluator, and each boundary test | §9.1 (scaffold; to be retyped, P4-1), §9.2, §9.3, §9.5, §9.6 (F22), §9.7 (CE); §1.14.1 (the formal model); §6.5 (BC-7 as the observed record context) | **Req** (T9-01, M1); **CE as a total function** (M2); a kernel type with transition and trace invariants (M3) |
| **V** | Validation | how the theory is tested against the corpus and how the book's own reviews count | §8.1–§8.5 (validation of the book's theory); §4.2; the loop material, homed here until the loop has closed once (Ch. 7 retired, DB-6) | a statement of what independence the book's own reviews have (P5-3, M7; graded and factor-relative, DB-2) |
| **VI** | Applications (outside the kernel, built on it) | what is application by the boundary test, kept apart from the kernel | §6.1–§6.4, §6.6, §6.7; §5.1–§5.3; §2.7 (where AI fits); §1.9, §1.11, §1.15; §3.5, §3.7 | the kernel/application mark on every concept (I-12) |
| **App. A** | Open forks | each fork stated precisely enough to decide | THEORY-MAP forks; §3.9 (F11), §3.10 (F8), §9.6 (F22), §9.7 (F26, F27), §9.8 (F31), §9.9 (F23, F34), §8.9 (F32) | — |
| **App. B** | Rejected and restated concepts | what did not hold, and each restated form with its own ID | every section's "what does not hold"; register rows INVALID as stated with their restated forms (CON-0162–CON-0182) | — |

**Missing chapters that the unread corpus is likely to need** (from the corpus series not yet read in index order; to be confirmed when read):
- the mathematical theory of knowledge states (the S0896/S2377 line on Zero and gaps, read only in part);
- the gap theory as a whole (§5.4 is one section);
- verification as a theory (now spread across Ch. 8);
- the kernel's interface (EXT-010's A, the source of T9-03).

## Renumbering cost (from explicit § citations)

Counted over all chapter files, the theory map, the register, the outline and RESEARCH-PURPOSE: **2,364 explicit § citations** would need rewriting if every section were renumbered. Citations in review folders and git history are not counted, and stay valid as history.

| Most-cited sections | Citations | Most-cited sections | Citations |
|---|---|---|---|
| §1.14 | 261 | §1.6 | 87 |
| §1.16 | 142 | §1.13 | 86 |
| §2.8 | 89 | §1.12 | 82 |
| §1.2 | 72 | §8.6 | 65 |
| §1.17 | 61 | §9.1 | 53 |

**Cost-reducing option:** keep the current numbers as stable identifiers, and publish the target structure as a reading order that maps each part to its sections. Renumbering cost: 0. Readers navigate by the map; citations stay as they are. This is the Professor's recommendation if the Principal adopts the structure.

**Dependency note:** the dependency graph (`tmp/tools/dependency_graph.py`) has one mutual-citation group of 139 nodes. Moving sections between chapters does not change the graph, only the labels.

## What this proposal does not decide

Decided since (the Principal, 2026-10-20): the structure as a reading order (DB-7); Ch. 7 retired (DB-6); Ch. 8 split (DB-4). It still does not decide the order of work (top-down, iterative or bottom-up: a fork inside the Principal's direction, RESEARCH-PURPOSE). All three are the Principal's.
