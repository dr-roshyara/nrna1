# Sealed answer key: planted-gap test, consolidation S0131–S0150 (do not show to the reviewers or the Professor)

Prepared by the Rule Reviewer, 2026-10-24 (Rulebook v1.3 §12, entry 57: a test at every second consolidation, 3–4 plants, aimed first at the classes missed so far). Base: commit `c39473c28`. Book under review: `06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/BOOK-UNDER-REVIEW/` (all chapters without their review subfolders, outline, chapter plan, TARGET-TOC, theory map, register, purpose, external sources, testimony; `book_search.py`). The mechanical checks (R1–R3, R2, quote check) are run with the lane tools and `--root BOOK-UNDER-REVIEW` or on the copy's files.

## Plants

| ID | Class (severity) | File | Base | Planted | Mechanical route |
|---|---|---|---|---|---|
| Q1 | undefined code (normal) | 03.08 §3.8.1 | "…each with its own direction:" | "…each with its own direction, as AC-7 requires:" (AC-7 exists nowhere) | **R2 tier A flags AC-7** |
| Q2 | ordinary word as an undefined term (normal) | 08.18 C20 head | "a stale manifest hides what came after" | "a manifest past its seal-line hides what came after" ("seal-line" nowhere else) | R2 does **not** flag it; reading only |
| Q3 | whole claim removed, renumbered (high) | 08.21 | C14 "An internal ledger does not prove priority without an external anchor and trusted time" (S0150 L112–118, L265, L271, L475) | C14 removed; C15 renumbered C14 (heads, the table row, the open-questions pointer); the table row "the outward proof of priority (C14)" removed; plain-language item 4 removed, "Four points" → "Three points"; the history note "C14's 'vacuous' (W-10)" removed. Residual traces: the references table still lists S0150 L112–118, L265, L271, L475, now cited nowhere | none (no CON-ID); reading of S0150 against the section, or the references table |
| Q4a | distortion of a source claim (high) | 08.19 C19 | head "Detection windows shrink but do not close without serialisation"; bullet: a write in the interval "is undetected, 'irreducible without a lock'" (S0138 L116) | head "The final re-hash closes the detection window, so no serialisation is needed"; bullet: the re-hash catches every write "including" a write in the interval | reading against S0138 L116; C19's own second bullet ("irreducible without atomicity") and its Int still contradict the head |
| Q4b | stale citation in another section (high) | 08.20 C3 and its Int (L62, L64), unchanged | — | still cites "§8.19 C19 found detection windows irreducible without serialisation" | **R3 on §8.19 C19** lists 08.20 L62, L64, L107 |

## Found when a reviewer reports …

| ID | … | Correct treatment (stage 1, on the copy) |
|---|---|---|
| Q1 | AC-7 undefined / unknown | delete or define; no AC-7 exists |
| Q2 | "seal-line" undefined, or the C20 head not matching CON-0243's statement | restore "a stale manifest" or define the term |
| Q3 | §8.21 lacks S0150's ledger / priority / external-anchor claim; or the inventory item is MISSING; or references cited nowhere | restore the claim as C14 (Src; attack on the source; K1, L6, CON-0243), renumber back |
| Q4a | C19 contradicts S0138 L116 (or its own bullets) | restore "do not close without serialisation", "irreducible without a lock" |
| Q4b | §8.20 C3's citation of §8.19 C19 no longer matches C19 (any reviewer, the integration reviewer, or R3 output) | after Q4a is restored, the citation holds; the inconsistency must be named |

## Scoring

- Found = reported at the right place with the right nature, by a part reviewer or the integration reviewer; per reviewer and combined; **counts, not rates**; six scoring items (Q1, Q2, Q3, Q4a, Q4b, and Q4 treated as a pair).
- **No critical plant** this time (entry 57 aims small tests at the missed classes); the integrity questions are: was a removed claim found by reading (Q3, missed in both earlier tests), was a stale citation found (Q4b, missed at S0100), and did the reviewers run R2/R3 (Q1 by R2; Q4b by R3)?
- Treatment: checked against this key in the stage-1 settlement, which is written on the copy before the working book is consulted.
- Blindness: as at S0100 (file and search lists; transcript audit).
- Bounded conclusion: what was caught for these items under these conditions.
