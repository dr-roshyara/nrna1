CONSOLIDATION S0278–S0302: REPORT A

(Reviewer A. Object: Ch. 2 `02.00`, `02.00a`, `02.01`–`02.11`, `02.99`, `TODO.md` of the frozen copy, 924 lines. W = docs/knowledgeos/knowlegeos_manual_theory_20261018. C = W/06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/BOOK-UNDER-REVIEW.)

## 0. Process disclosure (read first)
- **I read most of Ch. 2 from the WORKING tree, not the copy.** One of my `cd` commands used `cd W/06-theory/manuscript/chapter_02 2>/dev/null || cd <copy>`. The first path exists, so it succeeded; `pwd` printed the working path.
  - From that call on I read these from the working tree: `02.00-index` L24–40, `02.00a`, `02.01`–`02.11`, `02.99`, `TODO.md`.
  - Later greps in `chapter_01/03/05/08/09` (06-theory/manuscript) were also run there.
- Remedy: afterwards I re-ran fixed-string greps on the copy for every passage I cite in the findings and in the TODO and SEQ rows.
  - Each appears in the copy at the same line number (copy: 924 lines in total).
  - The cross-chapter targets I cite (CON-0192 `[validated]` at 09.14 L47; the 09.10 L87 citation of §2.3 (e); 03.09 C4; 03.01 L40; 05.04 C4; 03.06 L119; 08.08 L20; 01.08 L36) also exist in the copy.
  - I did not compare files.
  - I cannot certify that passages I did not re-grep are identical in the copy.
  - A re-read of Ch. 2 from the copy is the safe repair.
- **The first `book_search.py --help` call and the first `undefined_terms.py --help` call ran without `--root`.** `book_search.py` ran from the copy's tool and searched the copy. `undefined_terms.py` ran on the default root, i.e. the working book. I saw the first 20 lines of its whole-book list and used nothing from them. I also ran `corpus_quote_check.py --help`; it failed on a file named `--help` and opened nothing.
- Every tool run listed in the closing commands section (those with `--root`) used `--root C`.
- The tools ran from W/tmp/tools. The copy's own `tmp/tools` holds only `book_search.py`.
- **No corpus file outside the allowed list was opened.**
  - The Ch. 2 sources RM, RS, SC, SD, EV, CF, AB, IM, IA, PC, DE, SI, AMS, V3, GA and GKC live under `docs/knowledge_tranfer/`, which SCOPE §2.4 forbids.
  - So **every anchor to them is NOT CHECKED**. That is most of the chapter's `[RM L…]`, `[RS L…]` and `[SC L…]` anchors.
  - The repository code and config claims (`design-rules.json`, `design-check.sh`, `config/app.php`, `docs/knowledge/schema` counts) are also NOT CHECKED. They are not on the allowed list.

## 1. Summary
- No critical finding. Two high: a stale concept status in §2.3, and a wrong set-overlap rule in §2.8.4. Both are verified (A1, A2).
- The core derivations survive:
  - §2.3 (d) set-wise conflict;
  - the §2.6 over-approximating partition;
  - the §2.8.4 containment rule. I independently recomputed its check: 625 pairs, steps 1–3 give 0 mismatches against supervaluation, steps 1–2 give 70.
- All 10 S-source quotes the tool could find match (0 not found). I read about 35 anchors to S0003, S0005, S0007, S0008, S0014, S0015, S0020, S0021, S0022, S0024, S0025, S0034, S0043 and S0721 by hand. All support the text. S0034 L174 is a qualifier the text omits (A8).
- Concept verdicts: CON-0056 and CON-0063 VALIDATED; CON-0034, 0040, 0055, 0061, 0062 INVALID cores restated consistently; CON-0082 and 0083 PROVISIONAL.
- The Ch. 3-homed concepts of SCOPE §2.2 are not covered by me (reviewer B).
- Largest risk: Ded rules whose scope or label is stated more strongly than they hold (A2, A4, A3), and "Resolved" markers whose own criteria are unmet (A6, A7).
- SEQ order of Ch. 2 is acceptable; forward uses are mostly marked. One unmarked use: A9.
- Process independence only (separate agent, no other reports seen). I did not touch `00-access` or the sealed or hold-out files. For the working-tree reads see section 0.

## 2. Findings
| ID | § | Finding | Sev | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| A1 | 2.3 L68 | Concept status stale: "provisional, CON-0192". The register has CON-0192 VALIDATED (reviewed CONS-S0201-S0225), and §9.14 R1 carries `[CON-0192 · validated]` (L47). | high | register row; `09.14` L47; `02.03` L68 | "validated, CON-0192" | CON-0192 inbound: 09.00 L24, 09.14 L47, register L193, THEORY-MAP L413, BOOK-OUTLINE L177 (none says provisional; no change); 02.03 L68 is the only stale line (change) | the label reads validated |
| A2 | 2.8.4 L85 | Overlap rule for a set of rules is wrong against its own stated semantics. The rule reads: "all specified → test; some side ∅ → false; otherwise unknown". Counterexample (one dimension, V={a,b}): X₁={a}, X₂={b}, X₃=⊥. Every completion of X₃ gives an empty intersection, so supervaluation says FALSE. The rule says UNKNOWN. | high | `cons-a-setoverlap.py` (scratchpad): 6 of 125 three-side cases mismatch, all of this form. The book's 625-case check (L99) covers pairs and containment only, so it did not see this. The error is on the safe side, never a false TRUE, but it violates the book's own §2.3 (e) (decidable cases must be decided). | Add: "or the specified sides already have empty intersection → false". Extend the check to n ≥ 3. | 02.09 L59 (keep; add the exception); 02.08 L87 (keep, "unknown overlap" then means only the remaining cases); 02.06 L13 (unaffected) | the rule is corrected and the check covers n = 3 |
| A3 | 2.3 (e) L77 | "Every case decidable in the restricted language must be decided" cannot be met by a resource-bounded analyser. The same section places the problem in Σ₂ (L73), and a time-out is "UNKNOWN" (E6b). | normal | `02.03` L73, L77; `02.08` L258 | "…decided within a declared resource bound; beyond it, TIMEOUT (E6b)" | 09.10 L87 (cites (e); keep, sync wording); 02.08 L264, 02.06 L15 (keep) | bound stated |
| A4 | 2.8.2 L29 | "At equality the exception would suspend the whole rule, a repeal by another name." Counterexample: a whole-scope exception with bounded validity (a freeze) is a time-limited suspension, not a repeal, and §2.11 requires exception validity ⊆ rule validity. The condition is a design convention (Int), not Ded. | normal | `02.08` L29; `02.11` L19 | Label it Int/convention, or say "unbounded validity ⇒ repeal". | 02.11 L19, 02.08 L189 (keep) | label corrected |
| A5 | 2.11 L25, 2.8.3 L56 | The §2.0 convention is "rule = adopted norm". Yet L25 speaks of "a rule … in force by date but not yet adopted", and L56 of "a deferred rule … not adopted". Both must read "norm". CON-0063's register meaning ("leaves its question silent") is also broader than the derived claim ("cannot bind at t"). | normal | `02.00a` L44–48; `02.11` L25; `02.08` L56; register CON-0063 | "norm" in both places; align the register meaning | CON-0063 inbound, 13 lines (10.01 L60, 10.02 L143, 10.03 L136, 10.04 L63, 10.05 L292, THEORY-MAP L58, BOOK-OUTLINE L36 all use "cannot bind at t"; keep; change only the register L64 meaning) | wording aligned |
| A6 | TODO T2-10 | Status "Resolved", but the row's next action ("find the rule's record") and resolution criteria ("the record and its standing") are unmet. Closed by derivation, which changes the question. | normal | `TODO` L33 | Reopen the sub-item "ES-005.4's governing record", or rewrite the criteria. | TODO only (keep) | criteria and status agree |
| A7 | TODO SEQ-0001 | "Resolved" with the criterion "a pointer from §2.10". §2.10 has no pointer to Ch. 1 §1.5 (the "runtime adapter" text at L40 and L42; grep for "1.5" finds nothing in `02.10`). | normal | `TODO` L37; `02.10` L40, L42 | Add "(Ch. 1 §1.5)" at 02.10 L40, or reopen. | TODO only | pointer added |
| A8 | 2.5 L23–28 | The "by the authority of the issuing source" reading omits S0034 L174: `Round39-MC` was adopted by sponsor authority, not the ARB, and whether D-1 authorises D-2 is "UNRESOLVED… the model's most consequential open question". That bears on the standing of the source of the override. | normal | S0034 L174, L246 (read) | Add as a qualifier to the Int at L28 and to T2-08. | CON-0040 inbound: register, TODO T2-08; 03.06 L119 (keep) | qualifier added |
| A9 | 2.6 L11 | Uses "SC-2" and "⊥" with no pointer. They are first defined in §2.8.3–§2.8.4, two sections later. | normal | `02.06` L11 | "(§2.8.3–§2.8.4)" | none | pointer added |
| A10 | 2.00 L20 | The audit-label note covers L-, H-, R-, E- and C- labels only. The consolidation labels A-nn, B-nn, M-n and "integration D1" appear dozens of times in Ch. 2 and are not explained. Their record is outside the book. | normal | `02.00` L20; R2 tier B | One sentence in L20. | none | sentence added |
| A11 | 2.8.8 / 2.3 | T2-03 (ruled absence) says "revisit at consolidation", so this is its turn. §2.8.8 L284 recommends "a variant of E1" without testing the rival E7. The R-42 ruling "the registry governs platform assets only" scopes an obligation by subject kind, which is E7's governed not-applicable structure. (Int) | normal | `02.08` L284; `TODO` T2-03 | State the rival and the deciding test: does the ruling attach to a subject kind (E7) or to an item (E1)? | TODO T2-03 (keep open) | rival tested |
| A12 | various | Small stale or label points. (a) 02.08 L276: "(below)" points to text above (L206, L270). (b) 02.10 L42 says §2.8.6 and L44 says §2.8.7 for the blocking gate (register CON-0055 basis also says §2.8.6). (c) 02.03 L53 "Final definition" is superseded by (d); call it "Intermediate". (d) 02.99 rows L23–31 have two cells (reason column missing), and L27 is out of date order. | normal | read; L276, L42, L44, L53 | as stated | register CON-0055 (change §2.8.6 to §2.8.7 or "§2.8.6, §2.8.7") | done |

Not findings, stated for the record:
- (d)'s counterexample to the global form, the pairwise counterexample, and the claim that the two forms agree when no effect depends on context (checked by hand: S co-applies somewhere ⇒ every subset co-applies there).
- The monotone claim.
- §2.6's re-run on sets.
- §2.3 (a)'s decidability list.
- The Σ₂ claim.
- The containment rule.

## 3. Concept verdicts (Ch. 2-homed; Ch. 3-homed ones are for reviewer B)
| ID | Verdict | Reason |
|---|---|---|
| CON-0056 maturity ≠ enforcement | VALIDATED (second verdict) | S0024 L84 and its table. Rows 6 and 8 (L2) have method and implementation and are marked not enforcing. One non-implication instance suffices. Scope: one assessment, one author line. "Enforcing" means no enforcement without invocation (L88). The levels are defined only in part. |
| CON-0063 deferred rule cannot bind at t | VALIDATED (second verdict) | Valid within the §2.11 necessary condition (adopted ∧ t ∈ validity), by derivation. That condition rests on Track-B RM-3, which is ruled but governs nothing. The two-sense dichotomy of "deferred" is assumed, not derived. See A5. |
| CON-0034 | INVALID, core restated consistently | Text and register agree: catalog quoted only by example, RO-1 undecided, binding ≠ operating (S0020 L100 checked). |
| CON-0040 | INVALID, restated consistently | Meaning holds for one constitution; "extent = region" is Int; wider reading noted. Add A8. |
| CON-0055 | INVALID, restated consistently | Counts 19/22/16 match S0024 L74–78 and S0043 L48–52; kinds are by effect, counts by mechanism. |
| CON-0061 | INVALID, restated consistently | No deferral record read; S0025 L144–147 quote matches. |
| CON-0062 | INVALID, restated consistently | S0025 L72, L143 quotes match; the scope-by-actor possibility is kept. |
| CON-0082 E9 | PROVISIONAL | S0721 L453 supports the distinction. It is not shown to be a kind of nothing (evidence ≠ ∅). The test that decides is open (05.04 L86–88). |
| CON-0083 E10 | PROVISIONAL | The logical distinction holds. The dimension set and the licence "no known gap" are open (05.04 L91–94). S0721 L294–330 not read in full. |

## 4. Coverage (task 3)
| Item | Status | Home |
|---|---|---|
| T2-01 decisions' conflict | open | no definition found (searches for "decision conflict" and "T2-01" find none). Ch. 9 hits are a label clash (below). |
| T2-02 NOT_APPLICABLE | open | 02.09 L61–65; 05.04 L85 (E7 ≠ E2 only) |
| T2-03 ruled absence | open | A11 |
| T2-04 | pointer valid | 03.01 L40 (mapping exists, unreviewed by me) |
| T2-05 | open | 08.08 L20, L91, L99 ("same subject; not yet in a section") |
| T2-06 | resolved (criterion met) | 02.09 L76 |
| T2-07 | open | 03.06 L26, L119 |
| T2-08 | open (narrowed) | 02.05 L29; 03.06 L119 |
| T2-09 | open | 02.11 L32 |
| T2-10 | marked Resolved, criteria unmet | A6 |
| T2-11 | open | no resolving source found by search |
| T2-12 | open | no resolving source found by search |
| T2-13 | open | no set-level finding term found by search; 02.04 L40 states the gap |
| SEQ-0001 | marked Resolved, pointer missing | A7 |
| SEQ-0002 | open | 02.10 L30 only |
| SEQ-0003 to SEQ-0006 | resolved by the revisions they name | 02.10 L42; 02.04 L48–53; 02.08 L56 |

Pointers from Ch. 2 to later chapters:

| Pointer | Status |
|---|---|
| §3.9 C4 | exists (03.09 L104) |
| §3.2 meta-model test | exists (03.02 L11) |
| §3.6 chain | exists (03.06 L21) |
| §5.4 C4 | exists (05.04 L81, L86–94) |
| §9.13 CE\* | exists |
| §9.14 R1, R5, v4 | exist; A1 |
| Ch. 1 §1.17.5 | exists |

SEQ order of Ch. 2 is acceptable. Forward uses of exception (§2.11), binding strength (§2.9), SC-2 (§2.8.3) and E6 (§2.8.8) are mostly pointed. The exception is A9. Reordering is not needed.

## 5. R2 judgement (`--scope 02`)
| Word | Judgement |
|---|---|
| realisability | forward use at 02.03 L68, defined at §9.14.2, no pointer. Add one. |
| alternation | standard; glossed by ∃∀ at 02.03 L73. Acceptable. |
| voided, recasting | ordinary words. Acceptable. |
| subtree | defined at 02.08 L151. Acceptable. |
| initio | "ab initio" glossed "from the start" (02.05 L21). Acceptable. |
| C-05, C-06, H-02, H-04 | explained at 02.00 L20. Acceptable. |
| B-20 (and A-nn, B-nn, M-n) | NOT explained. See A10. |
| DP-1 | an example of DP-n, defined at 02.09 L76. Acceptable. |
| thumb, land, compose, coherence, polynomial-time, design-policy, author-assigned | idiom or defined at point of use. Acceptable. |
| Tier D | common words (the whole tier); none changes meaning. |
| Not on the tool's list, my own candidates | OQ-24, PK-P4, PK-P7 and "runtime adapter" are undefined in Ch. 2 (no pointer; `book_search` finds OQ-24 only in 01.14, 01.17, 02.09; 02.99). Ask for pointers (A7 for runtime adapter). |

## 6. Coverage of sections
- Read in full: `02.00`, `02.00a`, `02.01`–`02.11`, `02.99`, `TODO.md`. But see section 0 on which tree most reads came from.
- Not checked: all `docs/knowledge_tranfer` anchors; the code and config claims; the book's own tool runs (`conflict_minimality_check.py`, `containment_check.py` were not run; I recomputed the containment and overlap checks independently).
- Mechanical checks:
  - R1: no flag in my sections (the two flags are CON-0003 and CON-0133, Ch. 3 and Ch. 6).
  - Quote check: 10 found, 0 not found.
- Out-of-scope one-liners:
  - Ch. 9 (09.13: 11 lines; also 09.15, 09.16) reuses T1-nn and T2-nn for C19 review items. This is a homonym with Ch. 2 TODO IDs.
  - 02.99 is closed per RK-1, so the consolidation S0131–S0150 changes are not logged there (BASELINE-REVISIONS was not opened).

## 7. Files opened and commands run
Opened (working-tree reads marked W):
- Folder files: `REVIEWER-NOTE.md`, `SCOPE.md`, `COMMISSION-A.md`; `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy: `C/06-theory/manuscript/chapter_02/*` (first pass: `02.00-index` L1–40 via a persisted output, `02.03`–`02.07` partly). W: the full passes of `02.00`–`02.11`, `02.99`, `TODO.md`.
- Copy: `C/06-theory/CONCEPT-REGISTER.tsv`.
- Copy greps and reads (cross-chapter): `chapter_09/09.14`, `09.10`, `09.13`; `chapter_03/03.01`, `03.09`; `chapter_05/05.04`; `chapter_03/03.06`; `chapter_08/08.08`; `chapter_01/01.08`; `C/06-theory/THEORY-MAP.md`; `BOOK-OUTLINE.md` (through `inbound_citations.py`).
- W (working tree, by mistake; line reads and greps): `06-theory/manuscript/chapter_01/01.14`, `01.17`, `chapter_00/00.05`, `00.06`, `chapter_03/03.01`, `03.02`, `03.06`, `03.08`, `03.09`, `03.10`, `chapter_05/05.04`, `chapter_09/09.13`, `09.15`, `09.16`, `chapter_08/08.08`, `chapter_01/TODO.md`.
- Corpus, allowed list only (line reads): S0003, S0005, S0007, S0008, S0014, S0015, S0020, S0021, S0022, S0024, S0025, S0034, S0043, S0721 (L449–456 and one grep over L296–330), `KnowledgeOS_Architecture_Fitness_Assessment` (S0024) lines 44–98.
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (lookups of S-IDs).

Commands:
- `cat`, `sed`, `grep -F` and `find` over the files above.
- `wc -l`.
- `python3 -I tmp/tools/register_home_check.py --root C`.
- `python3 -I tmp/tools/undefined_terms.py --root C --scope 02`.
- `python3 -I tmp/tools/corpus_quote_check.py C/.../chapter_02/02.0*.md 02.1*.md`.
- `python3 -I tmp/tools/inbound_citations.py --root C` for: "provisional, CON-0192", "Overlap of a set of rules", CON-0063, §2.8.4, §2.3 (e), §2.8.2, T2-10, SEQ-0001, §2.6, §2.3 (d), CON-0192.
- `python3 -I C/tmp/tools/book_search.py` for: --help, realisab, T2-01, T2-02, T2-03, T2-08, T2-13, "ab initio", NOT_APPLICABLE, "decision conflict", "minimal conflicting set", SEQ-0002, T2-09, T2-12, T2-11, OQ-24, PK-P7.
- Scratch scripts: `cons-a-check.py` and `cons-a-setoverlap.py`, both under `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/`.
- Tools run without `--root` by mistake:
  - `undefined_terms.py --help` (default root, i.e. the working book);
  - `book_search.py --help`, which ran from the copy's own copy of the tool;
  - `corpus_quote_check.py --help` (failed, opened nothing).
- Item 4 files of the note: the working `chapter_*` files of chapters 0–3, 5, 8 and 9 were opened by mistake as described in section 0. I did not open `BASELINE-REVISIONS.md`, the working `THEORY-MAP.md`, the working `CONCEPT-REGISTER.tsv`, the working `BOOK-OUTLINE.md`, old review folders, anything in `00-access/`, or git history of the manuscript.
