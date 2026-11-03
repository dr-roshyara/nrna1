CONSOLIDATION S0353–S0377: REPORT D

**Scope.** I read Ch. 9 §9.18, §9.19, §9.20 and `TODO.md` in full, plus the BOOK-OUTLINE rotation section (L186–231), all in the frozen copy. Commission task 2 (concept verdicts) is empty: none of the SCOPE §2.2 concepts is homed in these sections.

## 1. Assessment
- **Reliability.** Within their stated scopes the three sections are mostly reliable. The proofs in §9.19 check out step by step, except two small gaps (D-08). §9.20's results are honestly labelled as definitional.
- **Largest risk: §9.18's statement of the U case for g1** (g1 is the kernel's evidence-class rule since the Principal's U2 decision). It claims that U appears exactly when the possible resolutions disagree. The rule as written (Kleene evaluation of *ref* and *sup*) does not deliver that.
- **Second risk:** §9.19 still states Q4 as "conditional on G7 / needs F13" in about eight places after U2 decided the question.

## 2. Findings

Each correction below was checked against the source or the book. "R3" lists the lines elsewhere that cite the corrected claim and what happens to them. "Resolved when" is the completion condition.

**D-01 · §9.18 L70, L200–201 · high · defect (unsupported generalisation)**
- *Finding:* the U row says U is "readable when the classes over the possible values of its U inputs disagree", and L200 lists candidate classes as if *ref* and *sup* were independent. Kleene evaluation is sound but not complete. If one underlying atom enters both *ref* and *sup* with opposite signs, U is shown although every resolution agrees, and a candidate is listed that no resolution reaches.
- *Counterexample (argued, Int):* a revocation of producer P's grant has U authority (atom o). Refutation e1 by P is live iff ¬o. Refutation e2 by an authorised Q is withdrawn by P, and the withdrawal counts iff ¬o (T9-16 (b)), so e2 is live iff o. There is no support. Then *ref* = ¬o ∨ o, which Kleene evaluates to U. Every completion gives "refuted", but the reasons list "refuted or not attested".
- *Evidence:* scratch script `kleene.py` gives Kleene U; completions {refuted}; independent completions {not attested, refuted}. That one atom can enter twice in D is my reading, not run on `ce_model`. The book admits the same phenomenon for standing (§10.5 PL-13).
- *Correction:* the U row should read "ref or sup is U under Kleene evaluation (may hold where all resolutions agree)". L200's candidates should be marked as an over-approximation, or computed over the atoms. L201's "only when" is correct as it stands; keep it.
- *R3:* §8.39 L17 ("U with candidates"): candidate wording only. §8.65 L125: unaffected (U stays U). TODO T9-17 and T9-19 list "the U-case statement, for review": this finding is that review.
- *Resolved when:* L70/L200 are restated, or a model check shows no atom can occur with both signs.

**D-02 · §9.19 L95, L132, L149, L168, L170, L193, L215, L223, L338 · high · contradiction (stale)**
- *Finding:* these lines still say "Whether Q4's grade is g1's class is G7, open", "conditional on G7, needing F13", "if G7 places it", "Q4's reference is escalated", "given G7". That contradicts §9.19.9 U2 (a), §9.18 L82 and §9.18 L273. Only L114 carries an R3 note.
- *Evidence:* change log 09.99 L95 shows the last consolidation annotated L114 only.
- *Correction:* an R3 note at each line.
- *R3:* TODO T9-01 (L11) and BOOK-OUTLINE L179 carry the same stale text (correct them too). §9.20 L29: unaffected.
- *Resolved when:* the notes are placed.

**D-03 · §9.19 L167 · normal · defect**
- *Finding:* "Req's core (Q1–Q3, Q5, O1–O4)" against §9.10 L161–162 (core Q1–Q4, O1–O4; Q5 "grounded by chapter must-dos only") and against §9.19's own L364. L167 lists the items statable without T9-02, not the core. This is the defect the R3 note defers to "the next consolidation", which is this one.
- *Correction:* "Q1–Q3, Q5 and O1–O4 are stated without T9-02; of the core only Q4's reference waited on F13 (no longer, U2)".
- *R3:* §9.20 L29, L38, L68: unaffected (Th1 covers both lists).
- *Resolved when:* L167 is restated.

**D-04 · §9.18 L98 (also L205, L209) · normal · attribution**
- *Finding:* "CONFLICTED preserved until governed resolution" is cited as S0237 L209. At source it is L298, outside the cited range L192–236. L209 carries the forbidden collapse "CONFLICTED → Forced TRUE/FALSE".
- *Correction:* cite L209 for the collapse and L298 for the preservation.
- *R3:* §8.35 L79 and §8.65 L124: unaffected.
- *Resolved when:* the line references are corrected.

**D-05 · §9.18 L174, L321 · normal · stale pointer**
- *Finding:* "§10.1 L78" points at the wrong line. The quoted wording is verbatim at §10.1 L82; L78 is "Rivals recorded".
- *R3:* only these two lines.
- *Resolved when:* the pointer reads L82.

**D-06 · §9.18 L263 · normal · stale pointer**
- *Finding:* "recorded for the map (§10.4 R0)": the row is R0′ (§10.4 L53, "added at the G1 settlement, §9.18.7").
- *R3:* none.
- *Resolved when:* the pointer reads R0′.

**D-07 · §9.19 L29; TODO T9-04 · normal · scope lost**
- *Finding:* "Uniqueness holds exactly when the kernels are closed under intersection (T3)" drops T3 (b)'s hypotheses (H-down, H-mono). Counterexample without H-down: 𝒦 = {{a}, {a,b,c}, {a,b,d}} has a unique minimal kernel {a}, but is not closed under intersection. T9-04's "unique iff …, the converse under H-down and H-mono" contradicts itself. The §9.19.8 table states it correctly.
- *Correction:* "if; only if under H-down and H-mono".
- *R3:* §8.63 L19 cites T3 in form: unaffected.
- *Resolved when:* both lines are restated.

**D-08 · §9.19 T9 (ii), T10 · normal · proof table incomplete**
- *Finding:* T9 (ii) has no proof steps. It also holds without T3's hypotheses: by T3 (b) step 1 (finiteness only), every kernel contains the unique minimal K₀, so ⋂𝒦 = K₀ ∈ 𝒦. T10's H-down premise is used by no step.
- *Correction:* add the steps for T9 (ii); mark the unused premises.
- *R3:* §8.63 L19: unaffected.
- *Resolved when:* the table rows are added.

**D-09 · §9.20 L51, L92 (Th4), L116 · normal · unscoped claim**
- *Finding:* "Act time is required", while §9.20.5 (M6) and L48 show that the act times of roots and grants are read by no observer or guard.
- *Correction:* "the act time of the acts CE* orders is required; for roots and grants it is required by D only".
- *R3:* §8.96 L66 and §8.97 L70 use Th4 for order and citation: unaffected.
- *Resolved when:* Th4 is scoped.

**D-10 · §9.20 L47 against L139 · normal · internal contradiction**
- *Finding:* L47 lists "I1 item and kind" among the fields a kernel clause reads. L139 says no admitted kind carries an item's kind (DC-5).
- *Correction:* mark "kind" at L47 as open, with a pointer to L139.
- *R3:* none found.
- *Resolved when:* L47 is marked.

**D-11 · §9.20 L5, L48–51, L102, L157 · normal · undefined terms**
- *Finding:*
  - DC-1–DC-7 are defined only by reference to RESEARCH-QUESTION.md §3, which is outside the book, yet results rest on them.
  - M2, M3, M5 and M6 are used before they are described (M2 and M3 are never described).
  - "Q-quotient" is defined at L5 and never used.
  - "lifting rule" and "aggregation rule" (L157) are undefined.
- *Correction:* a one-line gloss for each.
- *R3:* none found.
- *Resolved when:* the glosses are added.

**D-12 · TODO T9-01, T9-02; §9.20.9 · normal · stale text and omission**
- *Finding:*
  - T9-01 still says "Q4 conditional on G7" (see D-02).
  - T9-02's next action, "the attribute table (F13)", contradicts its own retyped resolution criterion.
  - §9.20.9 (i), the output forms of Q1–Q3 that decide minimality, and (iii), the state for held acts, have no TODO row. I read TODO.md in full; the search "output form" finds homes only in §9.20 and 09.00.
- *Correction:* update the cells; add rows.
- *Resolved when:* the rows exist.

**D-13 · BOOK-OUTLINE L196–207 · normal · omission**
- *Finding:* no row for this consolidation (due at S0375; Ch. 9 is rotated, SCOPE §2.1).
- *Resolved when:* the row is added at settlement.

**D-14 · §9.19 L105; §9.18 L107 · normal · reading order**
- *Finding:* §9.19 L105 uses H-mono, T2, T3 (b) and T4, which are defined only at L236–246. §9.18 L107 uses "W" before L130 introduces it.
- *Correction:* forward pointers.
- *Resolved when:* the pointers are added.

**D-15 · §9.18 L103; §9.20 L102 · normal · question**
- *Finding:* an outside author's name and an eponymous theorem name appear in the book text. If SCOPE §2.4's rule ("their authors are not reproduced") also binds the book, describe them instead. That is the Principal's decision.

## 3. Concept verdicts
None are homed in §9.18–§9.20 (commission task 2).

## 4. Coverage

**Pointers to later chapters (all in Ch. 10):**

| Pointer | Status |
|---|---|
| §10.2.3 (premises (i)–(v), G1–G10, G7 row L220) | holds |
| §10.3.2; §10.3.6 (d2), (d3) | holds |
| §10.4 R5, R6 | holds |
| §10.4 R0 at §9.18 L263 | stale: R0′ (D-06) |
| §10.1 L78 | stale: L82 (D-05) |
| §10.5 PL-12 (a), PL-14 | holds |
| §10.5 L245–247 | holds |
| T10-xx labels | not checked against Ch. 10's TODO (not opened) |

**Ch. 9 TODO rows:**

| Rows | Status |
|---|---|
| T9-01 | open; stale wording (D-12) |
| T9-02 | partially resolved at §9.20; next-action cell stale |
| T9-03, T9-04 | open; homes §9.19.4, §9.19.6, §9.20.8 hold; T9-04 wording (D-07) |
| T9-05, T9-06, T9-08, T9-11 | open; not touched by my sections; whether resolved elsewhere was not checked |
| T9-07 | open; consistent with §9.19 L157 |
| T9-09, T9-10 | resolved; consistent with §9.18 L230 |
| T9-12, T9-16, T9-17, T9-20 | resolved; consistent with §9.18. T9-17's U-case is subject to D-01 |
| T9-13, T9-14, T9-15, T9-18, T9-21 | open; consistent with §9.18, §9.19 and §9.20 |
| T9-19 | resolved; its pointers L45, L76, L107, L243 and L295 all verified. The "decision record addendum to be recorded" could not be checked (file not in my list) |

**Quote and source checks:**
- `corpus_quote_check.py`: §9.18 0/0, §9.19 1 found/0 not found, §9.20 0/0. The tool did not parse §9.18's source quotes, so I checked them by hand.
- S0888 L370–384, S0294 L1029, S0887 L1578, S0896 L815, S0039 L210 and S0164 L2246 hold.
- The S0237 quote is misattributed (D-04).

**Checks:**
- **R1:** 241 rows, 0 flags.
- **R2:** 37 in-scope candidates, all judged (appendix).

## 5. Next steps
1. D-01: restate the U case for g1, or model one atom entering with both signs.
2. D-02, D-03, D-12: the R3 notes and the TODO and outline cells.
3. Pointers and attribution: D-04, D-05, D-06.
4. Scope and definitions: D-07–D-11, D-14.

## 6. Limits
- `ce_model.py`, `req_a_model.py`, `t902_state_model.py` and their runs are not in the copy. I did not open or run them; model results are taken as reported.
- Where TODO rows that my sections do not touch are resolved elsewhere was not checked.

**Summary.**
- §9.18–§9.20 hold within their scopes.
- §9.18's statement of g1's U case overstates what Kleene evaluation delivers (D-01).
- §9.19 still treats Q4 as conditional on G7 in about eight places after U2 (D-02).
- §9.19 L167's core list is the defect the T9-02 turn deferred to this consolidation (D-03).
- Pointer and attribution slips: D-04 to D-06.
- Scope slips in plain-language or result lines: D-07, D-09.
- §9.20 has one internal contradiction (D-10) and several undefined labels (D-11).
- TODO and rotation-table upkeep: D-12, D-13.
- No critical finding. No concept is homed in my sections.
- **Process independence:** I worked alone from the commission and the frozen copy, without other reviewers' reports. Independence here is of process, not of person.

## Appendix A — R2 judgements (scope 09.18–09.20)

| Word(s) | Judgement |
|---|---|
| wclass | a model function name, explained by context at L192; acceptable |
| D-01 | a review-finding label; acceptable |
| output | ordinary word; at §9.20.5 its sense is given by three bullets; acceptable |
| evidence-area | = W, L130; defined |
| store-known | named but its content is not stated (L237); minor |
| whole-entry | contrast at L108–110; defined |
| containable | criterion at L234; defined |
| kernel-read | defined at §9.19 L399 |
| quotient | defined at L5 and L102 |
| appender | defined at §9.19 L160 |
| annulling | defined at §9.18.4 and §9.13 |
| granularity | G, §9.19.4; defined |
| derivable | defined at §9.19 L173 |
| necessity | three senses at §9.19 L223; acceptable |
| merged | merge as in §9.16; acceptable |
| duty | PL-14; acceptable |
| aggregation | undefined at §9.20 L157 (D-11) |
| remember, overall, clear, adequate, refine, combine, withdraw, empirical, addressed, heading, smallest, onto, intersection, strongest, refute, resolve, asked, cost, exclude, once, keep | ordinary words, used in their ordinary sense; none undefined |

## Appendix B — Out of scope (one line each)
- §9.10 L174's R3 note "conditional on G7" is stale since U2.
- §9.10 L33 names outside authors.
- 09.99 L94 cites §9.10 L253 for the R3 note that sits at L251.
- BOOK-OUTLINE L179 "Q4's grading rule (G7, needs F13)" is stale; L183 "Ch. 8 (§8.1–8.45)" is stale.
- §10.5 PL-13 cites §9.13 L265 for "a U at a tie can hold where every resolution agrees"; L265 does not say that.
- THEORY-MAP L134 (F13) has no pointer to §9.20. A search for "9\.20|T9-02|Req/A" found no map line for the T9-02 turn. L452's counts (§8.1–8.81, §9.1–9.19) lag the copy.

## Appendix C — Files opened, commands and searches run

**Process disclosure.** My first calls of `undefined_terms.py --help` and `inbound_citations.py --help` were made without `--root`. Both tools ran on their default root, the working book, and printed whole-book R2 lines and an empty "--help" target. I did not use that output; every later call used `--root <copy>`. Apart from this tool output, I opened none of the files listed in REVIEWER-NOTE item 4. I used no git, no globs, no recursive search and no directory listings. `book_search.py` output names section files across the copy.

**Paths.** Lane = `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018`. Copy = Lane`/06-theory/manuscript/review/CONSOLIDATION-S0353-S0377/BOOK-UNDER-REVIEW`.

**Files opened:**
- Review folder: REVIEWER-NOTE.md, SCOPE.md, COMMISSION-D.md. Also Lane/03-methodology/REVIEWER-INSTRUCTIONS.md.
- Copy, read in full: chapter_09/09.18-evidence-in-the-record.md, 09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md, 09.20-the-state-space.md, TODO.md; BOOK-OUTLINE.md L181–231.
- Copy, lines or greps only:
  - BOOK-OUTLINE.md headings, L44, L179;
  - 06-theory/THEORY-MAP.md L134, L136, L156 and greps;
  - chapter_09: 09.10 (L13–200 lines named above, plus a grep), 09.13 (L26, 48, 52, 57, 81, 94, 104, 110, 265, 290), 09.16 (L76, 79, 81, 93, 184, 192, 319), 09.11 (L33, 73, 96, 129), 09.17 L164, 09.08 L69, 09.06 L191, 09.02 L12, 09.00-index (a grep, no output);
  - chapter_10: 10.01 L78, L81–82 and a grep; 10.02, 10.03, 10.04 (greps); 10.05 (grep, L235–286);
  - chapter_02/02.08 L114; chapter_05/05.06 L70; chapter_01/01.03 L24–28; chapter_03/03.01 L12–19, L60–62;
  - chapter_08: 08.65 L124–125; 08.70 L103; 08.35 L79; 08.96 L66 and a grep; 08.97 L70 and a grep;
  - tmp/tools/book_search.py L1–40.
- Lane tools, heads only: tmp/tools/undefined_terms.py, inbound_citations.py (L1–80), register_home_check.py, corpus_quote_check.py.
- Corpus access: docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (head and grep).
- Corpus sources, phrase greps only, all on the allowed list:
  - S0237 docs/knowledgeos/architecture/20260822-0915-KOS-EP01-P4-Constitutional-Invariant-Map-v1.0.md
  - S0888 …/phase_measure_theory/20260827-181716_step-025a-3-….md
  - S0294 docs/knowledgeos/KnowledgeOS_Character_Definition.md
  - S0887 …/20260827-181510_step-025a-2-….md
  - S0896 …/20260827-183142_step-025d-formal-zero-algebra.md
  - S0039 docs/knowledgeos/2026-08-02-knowledgeos-product-boundary-discovery.md
  - S0164 docs/knowledgeos/brainstorming/20260821-120810-kos-governance-role-cost-optimization.md

**Commands run** (all outputs in /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/cons-s0353-d):
- `book_search.py` (copy), five runs: "§9\.18" "§9\.19"; "PL-14" "PL-12" "Θ_f" "covers" "single-use" "standing"; "law 31" "law 18" "laws" "merge-safe"; "output form" "held act"; "S0039 L210" "bootstrap a product". Also `--help`.
- `register_home_check.py --root <copy>`.
- `undefined_terms.py --root <copy> --scope 09.18-09.20`; also `--help` without root (see disclosure).
- `corpus_quote_check.py` on the three section files of the copy.
- `inbound_citations.py --root <copy>`: "§9.18.5" "§9.18.2" "U-case" "check 38"; "§9.19.3" "§9.19.4" "§9.19.5" "§9.19.7" "§9.19 L167" "§9.19 L95"; "§10.1 L78" "S0237" "§9.19.1" "T3" "T9 (ii)"; "§9.20.3" "§9.20.7" "§9.20.4" "Th4" "T9-01" "T9-02"; "§9.18"; "§9.19"; "§9.20"; "§10.1". Also `--help` without root.
- `wc`, `sed -n`, `grep -n` and `test -f` on the files named above.
- Scratch script `kleene.py`, run with `python3 -I`.
