# Consolidation S0278–S0302: stage 1 settlement (a document only)

*2026-11-01. The Professor. Rulebook v1.4 §12. **The copy's files are not edited.** Each remedy is stated against the copy's text (`BOOK-UNDER-REVIEW/`, `d2065a807`) and chosen from the copy and the sources only; nothing here rests on any other text. The consolidation is **not settled**: scoring and stage 2 follow.*

**Reports, saved verbatim with sha256 (run 2, fresh and independent):** A (Sonnet): `REPORT-A.md`, `a231587a…`; B (Opus): `REPORT-B.md`, `37611981…`; C (Opus): `REPORT-C.md`, `8376f249…`; integration (Opus): `INTEGRATION-REPORT.md`, `0b33df12…`.

"V (Professor)" marks evidence the Professor re-checked in the copy or a source. "R" marks evidence accepted on the integration review's reading.

## 0. Measures

| Measure | Value |
|---|---|
| Reviewer A (Sonnet), harness | 857.2 s, 51 tool uses |
| Reviewer B (Opus), harness | 849.4 s, 65 tool uses |
| Reviewer C (Opus), harness | 1,022.5 s, 90 tool uses |
| Integration (Opus), harness | 1,766.7 s, 69 tool uses |
| **Review total (run 2)** | **4,495.8 s (74 min 56 s)**; run 1 (interrupted, discarded) not counted |
| **Defects in the rotated chapters (confirmed)** | **Ch. 2: 17** (A-F1–F17; F16 in part) · **Ch. 3: 17** (B-01–B-17) · integration new: 03.08 L49 (with B-05), 02.06 L11 (low), 03.00 L15 (low) |
| Elsewhere in scope (Ch. 8, map, outline) | C-01 (high), C-02 (+08.20 L247), C-03–C-07; four coverage misses (C: S0294 UM-22 circular admission, epistemic-state vocabularies, dimension sets; S0281 "consumed, not authored"); BOOK-OUTLINE L177 (with A-F1) |
| Findings | 41 checked by the integration: 30 confirmed, 11 modified, 0 rejected (A-F16 rejected in part); no critical; 9 high |
| Concept status change | CON-0001: VALIDATED → PROVISIONAL (B-02, reason modified) |
| Coverage gaps (integration §6) | no architecture view for Ch. 2 or Ch. 3; Ch. 8's SEQ order outside §8.52–§8.61 not assessed; THEORY-MAP L412 counts unverified; anchors into non-allowed sources unchecked; `corpus_quote_check.py` vacuous on Ch. 8's citation format |

## 1. Findings and remedies (stated against the copy)

| I (reviewers) | Sev. | Check | Remedy, against the copy's text |
|---|---|---|---|
| A-F1 | high | **V (Professor):** copy `02.03` L68 "provisional, CON-0192"; `BOOK-OUTLINE.md` L177 "provisional" | "validated at S0201–S0225, within its scope" at 02.03 L68 and BOOK-OUTLINE L177 |
| A-F2 | high | **V (Professor):** copy `02.08` L85 ("some side specified as ∅ → false; otherwise … unknown"); the counterexample {a},{b},⊥ reproduced by the integration (6/125 at n = 3) | "the intersection of the specified sides is empty → false; otherwise, with an unspecified side → unknown"; the L99 check extended to n ≥ 3 |
| A-F3 | high | **V (Professor):** copy `02.08` L87 "UNKNOWN, never CONSISTENT"; `02.09` L59 | "UNKNOWN unless the effects are jointly satisfiable at every point of the possible overlap; never CONSISTENT on the ground of disjointness", at both lines; applied after A-F2 |
| A-F4 | normal | R | The test stated on A′ᵣ over the rule's validity; the condition added at 02.11 L19; equal scope with shorter validity recorded as a waiver reading |
| A-F5 | normal | R | §2.5 ground 2 marked a stated premise (Int/Src); the criterion "jointly binding obligations vs selectors" at 02.05 L10–15 and 02.08 L116–120 |
| A-F6 | normal | R | One sentence at 02.05 L17 linking author = authority to the open question of standing enacted precedence |
| A-F7 | normal | R | 00.05 L10 cited for I-4 at 02.05 L7 and 02.10 L23; the "explicit, visible status" reading labelled Int; whether unlinked rulings breach "visible" stated as undecided; the I-7 breach kept |
| A-F8 | normal | R | 02.10 L20 "Hyp (derived from PK-P4)" |
| A-F9 | normal | R | Qualifiers at first use (rule; binding, three senses); §2.0 L15 as an application of the §1.9 pattern |
| A-F10 | normal | R | 02.10 L24 qualified as A proposes, labelled Int |
| A-F11 | normal | R | 02.11 L19 "exception validity ⊆ rule validity" labelled Int/convention (with A-F4) |
| A-F12 | normal | R | §2.8.6: the dual case (narrowing app(k) broadens applicability; a governed act) |
| A-F13 | normal | R | 02.06 L11 "SC-2, ⊥ (§2.8.3–§2.8.4)" |
| A-F14 | normal | R | 02.05 L23 "in S0034's D-2 table, whose constitution is `Round39-MC`" (Int); register CON-0040 basis checked in stage 2 |
| A-F15 | normal | R | Ch. 2 TODO: rows for the open items in the text; T2-10 status and criterion reconciled; T2-07 status reconciled; T2-05 pointers to §3.1 L40 and §3.9 C4 |
| A-F16 | normal | R (part rejected) | 02.08 L276 "(above)"; 02.10 L42 optionally "§2.8.6–§2.8.7" |
| A-F17 | normal | R | Register CON-0063 meaning aligned with 02.08 L56's claim and its two senses |
| B-01 | high | **V (Professor):** copy `03.01` L17 `[CON-0003 · validated]` | tag → `invalid` |
| B-02 | high | R (S0016 L127–132 checked by B and the integration) | CON-0001 → PROVISIONAL (register; 03.01 L15); reason: the value list does not cover the source's own test rows, and §3.1 L67; 03.00 L15's example tag replaced |
| B-03 | high | **V (Professor):** copy `03.03` L29, L34; S0009 L55 as quoted | "One (runtime asset) fails the test; artifact and projection are questioned on other grounds (Int)" at 03.03 L34, 03.08 L21 (with B-17) and T3-03 |
| B-04 | normal | R | 03.03 L53: drop the nesting clause; restate CON-0011's reason (the definition embedded F8's answer, as CON-0096); register and TODO aligned |
| B-05 | normal | R (modified) | 03.08 L46: witness = artifact → any concept plus projection → its sources, with the premise stated; 03.08 L49 updated |
| B-06 | normal | R | 03.10 L103 "non-empty members"; the check's script recorded (T3-28) |
| B-07 | normal | R | 03.06 L76 and register CON-0258: the clause "G may not name x's own values"; "non-circular" kept only with it |
| B-08 | normal | R | 03.05 L46 and register CON-0260: the modal or the actual sense stated |
| B-09 | normal | R | 03.00: a note that §3.9–§3.10 presuppose §6.1 and §6.3 C1 |
| B-10 | normal | R | 03.03 L11 → Ch. 1 §1.6 |
| B-11 | normal | R | 03.00 L6–7: source list corrected; the CANDIDATE claim qualified (a search miss, not proof) |
| B-12, B-13, B-14, B-17 | normal | R | Ch. 3 TODO rows and 03.08 L21's line range corrected as B proposes |
| B-15 | normal | R | 03.08 L73: sense C labelled Int |
| B-16 | normal | R | 03.09 L111 and register CON-0092: block separated from interrupt |
| C-01 | high | **V (Professor):** copy `08.56` L40 "*The sources settle it: reading (b), the parser decides.*" against L24, L86 of the same section, index L70, map L231, log L83 and `08.58` L57 | 08.56 L40: "*The sources do not say which; L28 ('stores the normalized meaning') fits (b).*" |
| C-02 | normal | R (modified: L247 added) | Each note graded as §8.60 C1 grades it: 08.18 L202, L213, L215; 08.19 L238, L239; 08.20 L247 ("repaired, not closed") |
| C-03 | normal | R | Map L227 "asserted" |
| C-04 | normal | R | 08.59 L3: the C68 R3 marked applied; change log L88 lists §8.59 C2, C5 |
| C-05 | normal | R | 08.60 L36 as C proposes |
| C-06 | normal | R (lines verified by the integration) | Quotations made verbatim or unquoted; line numbers corrected (S0284 L137; S0296 L38; S0241 L138; S0253 L191; S0241 L113) |
| C-07 | normal | R | Index L77: the forward-use note extended to §8.61, with the forward Ch. 8 dependencies listed |
| C coverage (O-1–O-3; S0281) | normal | R (searches by C and the integration) | §8.56 C5: S0294's UM-22 admission rule counted as convergence (L859–914; one lineage, §8.31 C4), the unreconciled epistemic-state vocabularies, the differing dimension sets; §8.52 C4: S0281 "Current State is consumed, not authored" (L103, L221) as an agreement with decision C1. Drafted in stage 2 from the sources |

## 2. R3

The integration report's §2 gives each inbound line a disposition; stage 2 applies "same change" and "different change" as listed (notably 03.08 L49 with B-05; 08.20 L247 with C-02; BOOK-OUTLINE L177 with A-F1).

## 3. Not done in stage 1
- No working-book file is edited (the freeze stands until scoring).
- The coverage gaps of §0 are recorded, not filled.
- Scoring is not the Professor's; the key is not opened by the Professor.
