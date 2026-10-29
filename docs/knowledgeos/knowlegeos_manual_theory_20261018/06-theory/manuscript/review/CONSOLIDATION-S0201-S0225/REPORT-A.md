CONSOLIDATION S0201–S0225: REPORT A

Reviewer A. Consolidation review (Rulebook v1.4 §12) of the frozen copy `06-theory/manuscript/review/CONSOLIDATION-S0201-S0225/BOOK-UNDER-REVIEW/`. Scope: Ch. 9 `09.00`–`09.12`, `09.99`, `TODO.md`.

## 1. Assessment
- **Reliability.** Inside the scopes they state, §9.1–§9.12 hold up. I checked by hand C1, C3, C6 results 3–5, the §9.11 R4 counterexamples and §9.12 B1–B3, and they are correct. 15 cited source passages also match what the book says they mean.
- **Largest risk.** One condition that is only shown to be sufficient is stated as necessary: the kernel type says act-consuming applications "must share one" history (A-01). The second risk is stale text. The Principal's decisions of 2026-10-19 to 10-29 (DB-1, C1 = constitute, F22 = (C+O), F36, T9-12, T9-16) are applied in some places and not in others. The main case is §9.10 Q1, which contradicts §9.10.6 and §9.12.7 (A-02).
- **No critical finding.**

## 2. Findings
"R3" gives the inbound lines for each correction and what happens to them.

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines → disposition | Resolved when |
|---|---|---|---|---|---|---|---|
| A-01 | §9.1 C6 "Applications" (09.01 L101); §9.11 R1 step 3(iii) and its Result (L78, L82); §9.6.4 step 3 (L127) | Result 5 gives **sufficient** conditions for uniqueness. The text then states condition (iii) as **necessary**: "every application that consumes acts must share one append-only history variable"; "one record is shared". **Counter-model:** split the acts by owner. Application A consumes only acts in P_A and keeps its own history H_A; B does the same with P_B and H_B; a guard refuses any consumption outside a mechanism's own part. Uniqueness holds, yet no single H is shared and (iii) fails. The book's own F22 = (C+O) ("one coordination point per grant", §9.6.6a item 4) has exactly this form. | high | 09.01 L101, L113 (result 5); 09.06 L127; 09.11 L78, L82 | Say: one shared history is sufficient; a split of the act space with one history per part (per grant or owner) is also sufficient; necessity is not shown. | 09.01 L101, 09.06 L127, 09.11 L78/L82, BOOK-OUTLINE L173, THEORY-MAP L155 (F36 row: "needs one shared record") → all **restate the claim; reword** | all five lines say "sufficient", with the alternative named |
| A-02 | §9.10.3 Q1 "Assumptions" (L38) | It says the world must supply "that recorded acts occurred, and that every act was recorded". This contradicts four places. §9.10.6's Q1 row: under A1 recognized only key binding is needed; occurrence only under A1-as-fact. §9.12.7: the same. §9.9.9 item 5: the dependence is "dissolved". §9.15: no CE* value depends on occurrence or completeness. | high | 09.10 L38 vs L214; 09.12 L100–101; 09.09 L126 | Restate as §9.10.6 does: under DB-1, key binding and custody behind the integrity check (and L11); occurrence and completeness only under A1-as-fact. | 22 lines cite "§9.10 Q1" (Ch. 8, 9, 10, EXTERNAL-SOURCES). Filtering them for assumption/world/occurrence returns 0, so none uses this clause → **unaffected** | L38 matches §9.10.6 |
| A-03 | §9.6.4 step 4; CON-0170 | "Applied to A1, KC can never be met: a kernel checks records, not acts" holds only for **A1-as-fact**. Under DB-1 (recognized, as-form), A1 is a transition law that can be checked on records (§9.11 law 2: class T, in C6, C3 yes). The register's "unsatisfiable for A1 as stated" goes stale with it. | normal | 09.06 L129; 09.11 L34 | Scope the sentence to A1-as-fact. | 09.06 L129; register rows CON-0136, CON-0170 → **reword**. Nothing depends on it, since KC is not the decider. | sentence and register row scoped |
| A-04 | §9.7 step 4, result 4; CON-0173 | "Each [of definition, coverage, root, policy values] needs a common, versioned value" overreaches the section itself. Step 3 / F27 says ρ may be a query parameter, and §9.7.9 says agreement needs "only the choices shown to matter". | normal | 09.07 L85–86, L94, L133, L166 | "Each value shown to change answers on admitted records (P, κ, σ, υ; ρ only if it is policy)". | 11 lines cite CON-0173: 10.02 L88, 10.04 L65, 10.05 L246/L318/L326, THEORY-MAP L270 → **restate, wording only**; 09.18 L270 → **unaffected** (analogy only) | register and result 4 scoped |
| A-05 | §9.6, §9.7, §9.10, §9.11, TODO, register | **Stale text after the Principal's decisions:** <br>• §9.6.8 still lists K3 (settled 2026-10-19) and "Run Analysis 1–3 for CE (next turn)" (done in §9.7). <br>• §9.6.6a item 4 says "turns on K4 alone"; the index row for 9.6 still says "Prepared for the Principal" although F22 = (C+O). <br>• §9.10.10 and §9.11.7 list F22 and F36 as undecided; §9.11 R3 itself says F36 is decided. <br>• §9.11 law 15 says "partial for Q1; absent for Q3"; §9.10 L39 and L62 say Q1 exists on D and Q3 has a construction on D. <br>• §9.7.1 ("Not yet … no function") and step 5 do not carry the turn-4 restriction (only result 2 does). <br>• TODO T9-05 is not pointed at §9.11.3, which already types the laws of §9.6–§9.9 (rows 2–3, 7, 9b, 10–11, 25–30). <br>• Register: CON-0138 still says "not yet a total function"; CON-0190 lists I1–I14, the body has I15. | normal | lines cited | Annotate each with the decision and date; update T9-05 and the register rows. | §9.6.8: none; §9.11: 24 section-level lines, all citing classes or R1/R3 → **unaffected** | each item annotated |
| A-06 | §9.10 Q4, O2, O1 | They do not carry T9-12 (c) (only authorised producers' evidence counts) or T9-16 (b) (withdrawal and restoration only by a holder of a grant over the scope), both decided 2026-10-29. §8.38 C1 relies on T9-12 (c) through Q4. | normal | 09.10 L66–75, L114–120; TODO T9-12, T9-16; 08.38 L59 | Q4 observable consequence: "no grade without an **authorised** evidence entry"; O2 scope; the proposed kinds of §9.18 noted as proposed. | inbound to Q4: 08.38 L16/L57, 09.18 L60/L81/L298, 10.02 L218 → **consistent** with the correction | Q4/O2 annotated |
| A-07 | §9.9.2, §9.10 C, against §8.38 C1 | "Reliability" has two homes. §9.9.2 makes Reliability(a, d, t) an input to T-a, and §9.10 C keeps T-a a candidate, with source trust entering T-c, not Q4. §8.38 C1 homes the reliability of a producing process in Q4's grading rule. Neither side refers to the other, and the word also changes object (a process kind vs an individual source). | normal | 09.09 L27; 09.10 L97–98; 08.38 L57, L61; THEORY-MAP L213 | Cross-link the two. State the fork: does Q4's rule read T-a, or does T-c combine them? | THEORY-MAP L213, 08.38 L57/L61 → **add a pointer** | one home or an explicit fork |
| A-08 | Ch. 9 labels | **Label homonyms:** <br>• K1–K5 in §9.6–§9.7 vs §9.15's K1–K7. Only §9.16 L57 tells them apart. <br>• L1–L5 in §9.9 (trust laws) vs §9.15's L1a–L11 (legitimacy assumptions). §9.9.9 item 5 mixes both families. <br>• "D" = Lemma D vs D = the well-formed domain, in the same sentence (09.08 L50, L62; 09.10 L194). <br>• "C1" means a claim number, the parameter C1 = constitute, and Business Use Category C1 (§9.4.1a). <br>• R1–R5 (rivals, §9.10.5) vs R1–R4 (results, §9.11). | normal | lines cited | Qualify each label by its section; rename Lemma D (e.g. "Lemma Hist"). | — (wording only) | labels unique per context |
| A-09 | §9.11.2, class H | "A property violated by some finite prefix" lets in properties that are not safety properties. Example: "never a, and eventually b" is violated by the prefix "a" but is not a safety property. | normal | 09.11 L18 | Use Alpern–Schneider: every run outside P has a finite prefix none of whose extensions is in P. Laws 9b and 24 are unaffected. | BOOK-OUTLINE L173 names the classes only → **unaffected** | definition fixed |
| A-10 | Ch. 9 reading order (SEQ) | The chapter is ordered by turns, and later annotations use objects defined after them: D, Θ_f, CE*, C1 = constitute (§9.13) in 09.07 L131, 09.08 L50, 09.09 L53, 09.10 L39/L62/L174; §9.15's K and L labels in §9.9.9 and §9.10.6; Req = (Q, O) in §9.1 C6 results 7–8. Every use has a pointer, but "nothing used before it is introduced" fails. | normal | lines cited | A forward glossary in 09.00, or turn these annotations into back-pointers. | — | glossary added |
| A-11 | §9.5 C3, C4; §9.6.6 | **Citation and lineage slips:** <br>• §9.5 C3 cites the I-4 row at S0091 **L182**; it is at **L181** (L182 is G-1). <br>• §9.6.6 says "S0234 adds" a quote at S0234 L272 that restates S0091 L192's headline, which §9.5 C3 already uses. It should be marked as a relay (one line of support). <br>• S0113's heading "Three of the five conditions have no detectable state" (L22) disagrees with its body ("only one" detectable, L24; "Four of five rows", L34). The book follows the body, and the source's table supports the body. Not recorded as a source defect. | normal | verified in the corpus (§9) | Fix the line number; add the relay note; add the source defect. | — | done |
| A-12 | §9.6.5 contract; CON-0171 | The register row leaves out four items: fixed fold, declared writer role, durable tracked store, comparable scope. Neither the row nor the text carries F22 = (C+O)'s owner clause. | normal | 09.06 L171–177; register row 172 | Align them. | 09.06 L170 → **restate** | aligned |
| A-13 | 09.00, TODO.md | A blank line (09.00 L28) splits the index table, so the 9.18 row falls outside it. TODO rows T9-12 to T9-17 have 5 cells under a 9-column header: status sits in "Issue type", and T9-16's "done" sits in "Problem". | normal | — | Fix the tables. | — | renders correctly |

## 3. Concept verdicts
**VALIDATED (second, independent verdict)**

| ID | Verdict | Reason |
|---|---|---|
| CON-0099 | VALIDATED | C1's proof checked (∅ is a proper subset of every non-empty M); scope: closed reading, Pres ∧ Min |
| CON-0105 | VALIDATED | The {record} vs {record, grant} counterexample is valid; S0039 L234 and L275 verified |
| CON-0111 | VALIDATED | As a definitional non-identity only. S2834 L2154–2160 verified; the source defect at L2093–2101 confirmed |

**PROVISIONAL concepts: my verdicts**

| ID | Verdict | Reason |
|---|---|---|
| CON-0115 | VALIDATED | Scope: the reported design rule of KOS-ATTR-ARCH-001, stated but not held. S0049 L76 and S0050 L84–90 verified; the no-owner deduction is sound |
| CON-0167 | VALIDATED | Scope: conditional on the derivation being total, which presupposes uniqueness (C2) |
| CON-0169 | VALIDATED | As the book's stated analytical split, not the sources' model; coherent |
| CON-0177 | VALIDATED | A conditional, near-definitional reduction with its premises listed; the proof steps check; scope: CE* on D, Θ_f |
| CON-0117 | PROVISIONAL | One instance, one line of support; the must-do partner is unstated; delivery is mapped to applications |
| CON-0138 | PROVISIONAL | The role is sound; the register text is stale against CE* (A-05) |
| CON-0143 | PROVISIONAL | A stipulation waiting for the Principal; it holds only for a given cut (§9.16) |
| CON-0149 | PROVISIONAL | Hyp; no independence model |
| CON-0160 | PROVISIONAL | The definitional core holds; L3–L5 are Hyp; f is unconstrained |
| CON-0170 | PROVISIONAL | Its A1 clause is wrong under DB-1 (A-03) |
| CON-0171 | PROVISIONAL | The register and the text differ; the F22 = (C+O) owner clause is missing (A-12) |
| CON-0173 | PROVISIONAL | "Each value" overreaches (A-04) |
| CON-0182 | PROVISIONAL | Point 2 is a sketch: the list of "ways out" is not shown to be complete; "candidate axiom" is superseded by §9.16 R7's world-assumption reading |
| CON-0183 | PROVISIONAL | Results 1–8 are sound; the "must share" necessity is a defect (A-01) |
| CON-0188 | PROVISIONAL | Q4 has no reference definition; stale against T9-12 and T9-16 (A-06) |
| CON-0189 | PROVISIONAL | A-01, A-09, and law 15 stale |
| CON-0190 | PROVISIONAL | B1–B3 checked and they hold. "Coincide exactly when endings act per carrier" is shown in one direction only, by example; the register lacks I15 |

**INVALID cores, checked as restated:** CON-0100 → 0183; 0110; 0114 → 0167; 0116; 0118; 0135 → 0169; 0136 → 0170; 0137 → 0171; 0142 → 0173; 0148 → 0177; 0161 → 0182. Each restatement matches the refutation recorded in the book. The two restated forms that need wording changes are CON-0170 (A-03) and CON-0173 (A-04).

## 4. Coverage against the inventories
Only items that bear on §9.1–§9.12 are listed. The windows' own sections belong to reviewer C.

| Item (inventory) | Status | Home |
|---|---|---|
| S0206: grant-id uniqueness, temporal ordering of authority and actor authenticity "have no owner" (C39 #5, L474–497) | **MISSING** (rank 1). Searched "grant-id", "class E", "L474" in §8.31–§8.38 with no hit. This is direct estate evidence for §9.6 K4, CON-0171's identity clause and F26. | — |
| Kernel lists of S0205 (C39 #9, L400–411), S0216 (C42), S0221 and S0222 (C44 #11) | **MISSING** from §9.9.8's comparison of kernel lists (rank 2). §8.34 L125 compares S0212 and S0213 only. | §9.9.8 |
| A kernel made only of prohibitions is trivial (C42 #15; S0205's K = (X, T, I); C40's "prevented transformations") | COVERED | §9.1 C1–C2; §8.32 L139, §8.33 L99, §8.35 L109 |
| S0201 kernel candidates C-1 to C-10 (C38) | COVERED | §8.31 table (L117–119) |
| S0206: authority untimed, 2 of 218 dated; scope compared as a string (C39 #6, #7) | COVERED | §8.31 L74–76; §9.6 L96; §8.32 L9–12 |
| S0224 reliability by source kind and context (C45 #4) | COVERED, home split (A-07) | §8.38 C1 (Q4) |
| S0225's three kernels (C45 #10) | COVERED | §8.38 C5 |
| S0213/S0214 "kernel protects transitions", six dimensions (C41 #8, #14) | COVERED as lists | §8.34 L125; the link to C6's transition invariants is not drawn |

## 5. R2 (`--scope 09.00-09.12,09.99`, run on the copy)
- **Tier A (8):**
  - antitone: glossed in place (09.01 L117) — acceptable.
  - hyperproperty: first used in the index row; defined in §9.11.2 — acceptable.
  - unended: "no ending act" (§1.13) — acceptable.
  - unfaithful: by T-b — acceptable.
  - truster: glossed "relying party x" — acceptable.
  - SE-3: a journal volume in a reference — false positive.
  - answerer: ordinary word — acceptable.
  - forgery: ordinary word — acceptable.
- **Tier C:**
  - start (§9.5): the corpus's START act, homed in §1.14.3. No gloss in §9.5 — minor.
  - registration: defined at §9.6.2 (CR-a) — acceptable.
- **Tier D, technical words:**
  - Glossed or defined in context: context-ward and plane-ward (table labels), mechanism-minimal (C2), single-write-path (I-5 row), trust-weighted, value-returning, branching-time (CTL reference), consumed-act (C6 r5), contract-time (§9.7 step 4), recoverability (= AE), vacuity (F-vac).
  - safety: defined, with the slip in A-09.
  - uniqueness: = U, §1.16.7.
  - constitute: = C1.
  - reliability: a homonym (A-07).
  - trap (09.11 L105): understood, not defined — minor.
- **Tier D, the other ~90 words** (recursive, redundancy, despite, discharged, …, because, keep): ordinary English or defined in context. No defect.

## 6. Coverage of sections
I read 09.00, TODO.md, 09.01–09.12 and 09.99 of the copy in full (about 1,930 lines). I did not read §9.13–§9.18 (reviewer B); I consulted them only by grep where my sections cite them.

**Limits:**
- Source checks were spot checks of 15 passages. Every quote also passed the mechanical check (163 found, 0 not found).
- The coverage misses rest on searches; a search miss is not proof of absence.
- `ce_model` and the kernel models were not re-run.

## Summary
1. No critical finding; 2 high, 11 normal.
2. High A-01: the kernel type turns a sufficient condition (one shared history) into a necessary one. A split of the act space by owner, which is F22 = (C+O)'s own design, is a counter-model.
3. High A-02: §9.10 Q1 still assumes occurrence and completeness, against §9.10.6, §9.12.7, §9.9.9 and §9.15.
4. A-03: the "KC never met for A1" claim fails under DB-1's adopted reading.
5. Stale text after the decisions of 10-19 to 10-29 is spread across §9.6, §9.7, §9.10, §9.11, TODO and the register (A-05, A-06).
6. Label homonyms (K, L, D, C1, R) and forward use of D, Θ_f and CE* break SEQ order in Ch. 9 (A-08, A-10).
7. "Reliability" has two homes, §9.9/§9.10 and §8.38's Q4 (A-07).
8. Concepts: 3 VALIDATED confirmed; 4 PROVISIONAL raised to VALIDATED (0115, 0167, 0169, 0177); 13 stay PROVISIONAL; the 11 INVALID cores are correctly restated.
9. Coverage misses: S0206's owner-less invariants; the newer kernel lists are not compared in §9.9.8.
10. Process independence: I saw no other reviewer's report and no settlement for this consolidation. My independence is of process (separate commission and context), not a claim of personal independence.

## Files opened, and commands and searches run
**Disclosure (REVIEWER-NOTE item 4).** My first tool call ran `undefined_terms.py`, `register_home_check.py` and `inbound_citations.py` with a stray `--help` and **without `--root`**. The tools therefore scanned their default root, which is the working lane: the working chapter files, THEORY-MAP and register. I did not open those files, and I discarded the output (a whole-book R2 list, one R1 line, an empty R3). Every result above comes from runs on the copy with `--root`. `corpus_quote_check.py` reads `W/02-FILES.jsonl` internally for source paths.

**Files opened**
- Consolidation folder: REVIEWER-NOTE.md, SCOPE.md, COMMISSION-A.md (directory listing only otherwise).
- `W/03-methodology/REVIEWER-INSTRUCTIONS.md`.
- Copy, chapter 9: 09.00-index.md, TODO.md, 09.01–09.12, 09.99-change-log.md (in full; through scratch copies in my scratchpad and the harness's saved tool outputs).
- Copy, other parts, by grep or sed:
  - CONCEPT-REGISTER.tsv: header and rows of the scope concepts.
  - THEORY-MAP.md L155, L213, L270–272.
  - BOOK-OUTLINE.md L173.
  - 08.38 L36–62; 08.32 L60–66; greps over 08.31–08.38.
  - Greps over 09.13, 09.15, 09.16, 09.17, 09.18; 10.02, 10.04, 10.05 lines via R3.
- Tools:
  - Headers of `W/tmp/tools/undefined_terms.py` (L1–33) and `corpus_quote_check.py` (L1–12).
  - Argument lines of `register_home_check.py` and `inbound_citations.py`.
  - The copy's `tmp/tools/book_search.py` (run).
- Inventories:
  - C38–C45 `STEP1-INVENTORY.md`: grep for kernel, primitive, §9, evaluator, Req, trust, history, serializ, uniqueness, append-only, refus.
  - C39 inventory L33–58 read.
- Corpus, all on CORPUS-ALLOWED.tsv (looked up by awk; none excluded by SCOPE §2.4):

  | Source | Lines |
  |---|---|
  | S0039 | 270–276 |
  | S0045 | 62–70 |
  | S2834 | 2090–2102, 2150–2162 |
  | S0091 | 180–192 |
  | S0234 | 262–284 |
  | S0128 | 45–50 |
  | S0049 | 40–56, 74–77 |
  | S0050 | 84–91, 104–108, 118–121 |
  | S0051 | 62–65 |
  | S0048 | 49–52, 65–68 |
  | S0113 | 22–44 |

**Commands**
- `ls`, `cat`, `find` on the consolidation folder and the copy; `wc -l` on chapter 9 and the inventories.
- `register_home_check.py --root <copy>`.
- `undefined_terms.py --root <copy> --scope 09.00-09.12,09.99`.
- `corpus_quote_check.py` on 09.0*, 09.10–09.12, 09.99, TODO of the copy.
- `inbound_citations.py --root <copy>` for "§9.10 Q1", CON-0183, CON-0170, CON-0173, CON-0189, "§9.11", "§9.6.8", "§9.5 C3", "§9.9.4", CON-0171, CON-0138, "§9.10 Q4".
- Copy's `book_search.py` for: "kernel primitives", "kernel list", "reliability", "trust by default".
- grep on the copy for: "K5", "§9.10 Q1", "§9.10 Q4", "must share one|one record is shared", "§9.1–9.12 references in 08.31–08.38", "kernel list|primitives proposed|…", "grant-id|class E|474", "S0205", "S0206 L…", "untimed|668|authenticity", "§9.10|O1|O2|Q4" in 09.18, "^| L[0-9]" in 09.15, "Lemma D".

**Scratch outputs** (outside the repository; nothing in the repository was edited):
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r2.txt
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r3.txt
- /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/907.txt through 912.txt and 999.txt
