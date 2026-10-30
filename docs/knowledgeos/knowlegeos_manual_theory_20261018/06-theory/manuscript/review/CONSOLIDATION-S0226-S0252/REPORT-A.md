CONSOLIDATION S0226–S0252: REPORT A

Reviewer A. Object: the frozen copy `.../review/CONSOLIDATION-S0226-S0252/BOOK-UNDER-REVIEW/` only. Scope: Ch. 10 `10.00`–`10.05`, `10.99` and `TODO.md`, all read in full (SCOPE §2.6). Mode: consolidation review (REVIEWER-INSTRUCTIONS; Rulebook v1.4 §12).

## 1. Assessment (3 lines)
Within its stated scope, Ch. 10 is careful. It labels everything S0, keeps its rivals, and its corpus citations hold up: the about 30 cited lines I checked all support what the text says. The main risk is **incomplete application of later decisions**. The class rule g1 (T9-17 (b)), the G1-turn gap statuses, T10-16 (b) and the 23-row map count are applied in some places and stale in others. As a result, §10.2.3's definitions and §10.3's "five exclusive relations" now contradict settled text elsewhere. Nothing is critical, because nothing is built on S0.

## 2. Findings (ranked)

| ID | Section | Finding | Sev. | Evidence | Correction | R3 lines (disposition) | Resolved when |
|---|---|---|---|---|---|---|---|
| A-01 | §10.2.3 L195–196 | **Defect.** refuted(k, t) still reads "whatever support of k is recorded" (g0's wording). With contested(k, t) on the next line, an item with a live refutation and a live support is **both** refuted and contested. That contradicts g1 (refuted iff ref = T and sup = F, §9.18.5 L196–197; §9.18.2 L66). Change log L31 says this was changed to "refuted only with no live support"; the text was not changed. | high | copy §10.2.3 L195–198; §9.18.5 L192–201; 10.99 L31 | refuted: "…not withdrawn…, **and no support of k is live**". U cases as §9.18.5 g1. | §9.18 L24, L92, L131, L179, L204, L321: unaffected (they cite §10.2.3 for premises and gaps, not the precedence). §10.4 R4, §10.5.3 W row: consistent with g1 already. 10.99 L31: claims a change not made. | §10.2.3's four classes are pairwise exclusive and match g1 on check 38's cases |
| A-02 | §10.2.3 gap table L210–225; §10.5.3 W row | **Contradiction between sections.** Ch. 10 still gives duty 2 (readable "from the record alone") as "not shown… until G1, G2 and G4–G9 are closed". §9.18.2 L72–86 says: G2 and G4 decided; G5, G8 and G9 closed in the model; G6 closed under (v); readable from "the record and stated inputs". | high | copy §9.18.2 L62, L72–86 | Add §9.18.2's state column or a pointer. Restate the result as conditional on G1 adoption, G3, G7 and G10. | §9.18 L72, L92: consistent once updated. THEORY-MAP L390 ("plus G2–G10"): stale, same fix (out of my scope; B's). | both sections give the same gap states |
| A-03 | §10.3.2 L63–75 vs §10.3.3 | **Definition defect.** The relations are declared "mutually exclusive per (candidate, invariant)", but the table gives one pair two relations: "acting under ended authority" is owned by 1 (row A) and "1 observes"; law 26 is owned by 2 and "2 observes". A self-enforcing owner has no relation of its own: "owns" absorbs enforcement. This merges the owner and enforcer dimensions again, the merge §10.3.5 says was refuted. | high (T10-08 would register these as concepts) | copy §10.3.3 L104, L113; §10.3.5 L237 | Use two orthogonal fields: owner status {owns, shared, contested, none} and enforcement mode per candidate {prevents, observes, respects, assumes}. "Enforces for another" = prevents ∧ ¬owns. | THEORY-MAP L391 ("five relations"): update with it. §9.18 L321: reference only. §10.5 PL-14 L277: unaffected (uses the enforcer definition, which is unchanged). | no table cell needs two relations |
| A-04 | §10.3.7 L342; PL-1 L119 vs §10.2 L5, §10.3.6 L263, L274, L288, §10.4 L15, PL-0 L76 | **Contradiction on the ground of the only admitted context.** §10.3.7 and PL-1 say the kind assignment "is now the element's act, not a stipulation". The other places say it is stipulated and "no recorded act fixes it". No such act is recorded: R-a cannot be applied (§10.3.2 L58), and T10-16 (b) fixes only who may change the list. §10.3.6 L263 and L288 ("by construction", "standing's schema") are not annotated as superseded. | high | the cited lines | "The assignment is the element's to make, by an authorised act outside every area. No such act is recorded, so it remains stipulated ((p2))." Annotate L263 and L288. | THEORY-MAP L392 ("standing's remove, annul and designate reach every kind"): stale since T10-16 (b) (B's scope). §9.18 L51: unaffected. | one statement, used in every section |
| A-05 | §10.2.2 grid L62–73 | **Arithmetic.** From the grid's own cells, rows 6, 7 and 8 sum to 5–6, 2–5 and 2–4. The grid prints 4–5, 2–4 and 2–3. So "under β… 1, 2 and 5 do, and 4 may" leaves out 6, which may also reach all six. | normal (admission is now decided by §10.3.6) | recomputed (command 13) | Correct the three cells; add "and 6 may", or give a reason it cannot. | §10.2.4 L233 (candidate 6 "between the readings"): recheck. No inbound lines outside Ch. 10. | cells and sums agree |
| A-06 | §10.1.2 L37–41 | The two class tests use different predicates. "Generic" is "a solution not made for KnowledgeOS can serve". "Specific" is "no solution made without KnowledgeOS's model can serve". So the classes are not exclusive "by construction". Counterexample: a solution built on KnowledgeOS's model for another product (PKS, S0196) would make an area both generic and specific. | normal | L37, L41 | Define generic as ¬specific, with the boundary proviso. | none found outside Ch. 10 | wording aligned |
| A-07 | §10.1.2 ceilings, rows 1, 5, 7 | Rows 1, 5 and 7 show S1. After A-18/B-09, §10.2 gives candidate 1 "S0 or S1, undetermined" and candidate 4 (rows 5, 7) "S1 not shown". | normal | §10.2 L97, L147 | Use §10.2's ceilings. | — | consistent |
| A-08 | index; §10.3.6; §10.4; TODO; §10.2 | **Stale lines:** index L8 ("none admitted"), L9 ("one question (T10-05)", resolved), L10 ("21 relationships"; §10.4 L6/L132 give 23, 16 conditional); §10.3.6 L291 "doubtful and pending" (the change log L27 says it was removed; L321 says not admitted today); §10.4.6 L166 "now 16 of 21"; §10.4.5 L155 "owner depends on T10-16" (decided); §10.2.3 L208 "E part of D's record or a second record is exactly G1" (T10-10 (1); §9.18.2 L51); TODO T10-07 still lists Q2, Q5, O1 and O3 as unplaced (§10.3.3 placed them); TODO T10-20, §10.2 L100 and §10.2.3 L225 list three classes (now four plus U). | normal | the cited lines | Update each. | THEORY-MAP L393 ("owner open, T10-16"): stale (B's scope) | — |
| A-09 | §10.4 R0, R0′ | R0 is typed "conforms", which §10.4.0 L24 defines as "shows Y's values unaltered"; areas do not show the record's values. R0 and R0′ also form an **unconditional two-way dependency** (S ↔ record element) that §10.4.3 and §10.4.5 do not discuss: they weigh only R6 and R12. | normal | L24, L52–53, L108, L140 | Give R0 a type that matches what it does, and record the cycle under the layering risk. | §9.18 L263 (R0′ origin): unaffected | — |
| A-10 | §10.3.7 L328; PL-0 L77 (also §9.18 L3) | **Attribution, unverified.** T10-16 (b) is cited as `fe2cbe971`, addendum 10:46:12Z. §10.3.6 L300 gives the same commit for T10-10 at 10:00:44Z, and T10-16 was raised only at the later SD5 settlement (10.99 L26). The TODO cites the addendum without a commit. I could not check this, because the decision record is not in my file list. | normal | the cited lines | State the commit for addendum 10:46:12Z once, and use it everywhere. | 10.00 L10; TODO L21 | the record confirms it |
| A-11 | PL-1 L102–113 | Under "B. Standing's language", PL-1 lists "the ten admitted kinds", including removal and designation. The split table (L61) places those two in the record's language. | normal | L61, L103 | List eight kinds under B and two under A. | — | — |
| A-12 | §10.2.3 L195; premise (iv) | The liveness proviso leaves out T9-12 (c) (only authorised producers count) and annulment, both in §9.18.5 L194's "live". Premise (iv) is still shown as unmet. | normal | §9.18.5 L194 | Use §9.18.5's definition of live. | — | — |
| A-13 | R2 | "sluice" (10.02 L100) is undefined and used once. "part 2" (TODO T10-15, T10-21) is undefined. | normal | R2 output | Define them, or replace them. | — | — |

**Out of scope, one line each.** THEORY-MAP L390–393 are stale as noted above (B's scope). The quote tool finds no quotes in Ch. 10 because of its citation style ("Sxxxx Lnn, in meaning"), so quotes were checked by hand.

## 3. Concept verdicts
| ID | Verdict | Reason |
|---|---|---|
| — | none in scope | SCOPE §2.2: no register row has its home in Ch. 10. The concepts Ch. 10 cites wait for their own chapter's rotation. A check of tag status against the register matched for all 27 tagged IDs (command 14). |

## 4. Coverage
My sections are Ch. 10. The windows' own sections, §8.39–§8.45, are B's. Below are the inventory items that bear on Ch. 10, found by searching all seven inventories and reading C48 in full.

| Item | Status | Home |
|---|---|---|
| C46 S0226: CONFLICTED stays until new evidence | COVERED (as "contested"; the book rejects "until evidence") | §10.2.3; §8.39 |
| C46 S0227/S0228: VALID/CONFLICTED/SUPERSEDED/REFUTED states | JUSTIFIABLY EXCLUDED for supersession (not admitted, G10) | §10.2.3 G10 |
| C47: KnowledgeClaim type {observed, inferred, assumed} | COVERED | §10.2 G7 row |
| C48 S0234: invariant asymmetry (declared vs enforced) | COVERED in concept (enforcer column; "held by convention"), not cited | §10.3.2–3 |
| C48 S0234: role ≠ bounded context ≠ capability | COVERED | §10.3.6 L293; §10.4.0 |
| C48 S0234: unowned capabilities; C-5 and C-19 not bounded contexts | COVERED through S0091, the same lineage | §10.3.1; §10.3.3 F |
| C48 S0234 E-1: a dense sequence does not prove completeness | COVERED (condition "numbers never reused", K6) | PL-3 |
| C50: means of acquisition preserved (sensor ≠ expert ≠ AI) | COVERED | §10.2 G7 row |
| C50 Art 8: resolution of a conflict is forward-only | MISSING (low): a resolving act's effect on earlier cuts is not stated | §10.2.3 (ii), G5 |
| C51 S0248: known/unknown/assumed/rejected/conflicted | JUSTIFIABLY EXCLUDED (state vocabulary; the G7 mapping is open) | G7 |

Ranked misses: only the C50 forward-only point, which is low.

## 5. R2 (`--scope 10`; every candidate)
| Word | Judgement |
|---|---|
| sluice | defect (A-13) |
| appender | defined at 10.05 L31; the tool is case-sensitive |
| SD4 | review label; fine in an index, though SD1–SD5 are never glossed |
| model-checked, single-owned, preventable, unended, hypothetical | clear from context, or defined in Ch. 9 (unended: §9.10 L60) |
| upstream, participant, falsifier, refinement | defined in place (10.04 L14, L23; 10.03 L195–202) |
| receiving, discrepancy, assess, learn, overstated, solution, unassigned, rejection, around, hand, instrument, specific, provided, receive, warning, procedure, duty, derive, necessity, strongest, frozen, discipline, claimed, compute, gain, answered, narrow, beyond, critical, impossible, discovery, eight, gaps, violation, apply, family, plus, qualified, admit, together, merged, edge, agent, ground, remain, above, agree, keep | ordinary English; no defect ("specific" is defined at 10.01 L41; see A-06) |
| also used in scope, first used elsewhere (annulling, mover, unforgeable, A-/B-/F- IDs) | background, not judged |

## 6. Coverage of sections
| Section | Read |
|---|---|
| 10.00, 10.01, 10.02, 10.03, 10.04, 10.05, 10.99, TODO | in full |
| §9.18 (copy) | the cited passages only |
| §9.13 (copy) | 9 cited lines only |

**SEQ, Ch. 10.** The sections follow the order of the turns, as the index states. Forward uses in §10.1 L70 and §10.2 L5 ((p2), the record element, T10-16, §10.3.7) are annotated as decided since, so they are acceptable, but the index rows are stale (A-08).

## Summary
1. Ch. 10 was read in full. The corpus citations I checked by hand (about 30 lines in 17 sources) support their claims. All 27 concept tags match the register.
2. Three high findings come from later decisions applied only in part: g1 vs the §10.2.3 definitions (A-01), the G1-turn gap states (A-02), and the ground of standing's admission (A-04).
3. One high definitional finding: the five "exclusive" relations of §10.3 merge two dimensions (A-03).
4. One arithmetic error in the S0150 grid (A-05). The conclusion it feeds has since been superseded.
5. Normal findings: class-test predicates, ceilings, stale counts and status lines, R0 typing and the S ↔ element cycle, a commit attribution, the PL-1 kind list, the liveness proviso, two undefined words.
6. No concept verdicts are due in my scope. Coverage of window items bearing on Ch. 10 is good; one low miss.
7. Not checked: §9.x claims beyond the lines listed; S0149, S0168, S0175, S0178, S0198, S0199, S0237, S0365, S0392, S0612, S0888 and S0991 as cited; the model runs (reported, not re-run). Inventories other than C48 were searched, not read in full.

Process independence: I did not write the reviewed work and had no access to other reviewers' reports. This is process independence, not personal independence.

## Files opened
- `.../06-theory/manuscript/review/CONSOLIDATION-S0226-S0252/REVIEWER-NOTE.md`, `SCOPE.md`, `COMMISSION-A.md`. The folder listing showed COMMISSION-B.md and INTEGRATION-COMMISSION.md by name; neither was opened.
- `.../knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md`
- Copy `06-theory/manuscript/chapter_10/`: 10.00-index.md, 10.01-subdomains.md, 10.02-bounded-contexts.md, 10.03-ownership-of-invariants.md, 10.04-context-map.md, 10.05-published-language.md, 10.99-change-log.md, TODO.md
- Copy `chapter_09/09.18-evidence-in-the-record.md`: L1–21, L24, L47–86, L92, L131, L179, L185–212, L246–264, L284–306
- Copy `chapter_09/09.13-the-evaluator.md`: L34, 39, 46, 48, 52, 59, 62, 166, 327
- Copy `06-theory/THEORY-MAP.md` L390–393; copy `CONCEPT-REGISTER.tsv` (row lookups); copy `chapter_09/` listing. Grep output also showed snippets of copy files in Ch. 0, 1, 3, 6, 8 and 9.
- `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (head; lookups)
- Corpus, allowed list only, by line range: S0035 L28–31, L70–73, L178; S0036 L90–93, L136–144, L171–174; S0039 L145–148; S0150 L583–593; S0033 L29, L83–86, L128–131; S0034 L20–33, L109–121; S0069 L21, L40–70; S0066 L92–99; S0162 L23, L60–72, L458; S0196 L248, L474, L598; S0028 L110; S0062 L125; S0091 L70, L181, L188; S0171 L23; S0151 L415–417; S0633 L5, L538; S1040 L116, L155–164
- `review/C48-S0232-S0236/STEP1-INVENTORY.md` (in full). C46, C47, C49, C50, C51, C52 STEP1-INVENTORY.md were searched by grep only.
- Tools in `tmp/tools/` (run). Argument-parsing lines read by grep in undefined_terms.py, inbound_citations.py, corpus_quote_check.py; undefined_terms.py L6–12 read.
- Harness-saved outputs of my own commands (tool-results/bz4prz3ih.txt, bxup9v4fj.txt); scratchpad r2.txt, r3.txt, r3b.txt.
- No file in REVIEWER-NOTE item 4 was reached: no working chapter files, no working map, register or outline, no BASELINE-REVISIONS, nothing in 00-access/, no git history.

## Commands and searches run
1. `ls -la` of the review folder; `cat` REVIEWER-NOTE.md.
2. `cat` SCOPE.md and COMMISSION-A.md; `cat` REVIEWER-INSTRUCTIONS.md.
3. `find` on the copy (depth 3); `wc -l` of chapter_10.
4. `cat -n` of 10.00, 10.99, TODO and 10.01; 10.02; 10.03; 10.04; 10.05 (two parts).
5. `grep` for contested, headings and g1 in copy 09.18; `ls` of copy chapter_09.
6. Tool calls with `--root <copy> --help`: register_home_check (it ran R1), undefined_terms (it ran unscoped), inbound_citations, corpus_quote_check (error: no --root support).
7. `grep` argv/scope handling in the three tools.
8. `undefined_terms.py --root <copy> --scope 10` (tail, then saved to r2.txt; marker grep; L1–64 shown).
9. `register_home_check.py --root <copy>` (R1: 1 information flag, CON-0133, outside scope); `corpus_quote_check.py <copy chapter_10/*.md>` (0 quotes found in every file).
10. Allowed-list lookups for 29 S-IDs (`grep -c`, `awk`); corpus line prints (four batches).
11. `wc -l` of the seven inventories; `grep -i` of the inventories for DDD terms, then for conflict, role, capability and source-kind terms; `cat -n` of C48.
12. Copy `book_search.py`: --help, S0234, density, "sequence number reused", contested, sluice.
13. Python recomputation of the §10.2.2 grid sums.
14. Tag status vs register (grep and awk); register status and home lookups for 14 untagged IDs.
15. `sed` of the §9.18 passages and §9.13 lines listed above.
16. `inbound_citations.py --root <copy>` for §10.2.3, §10.3.2, §10.3.6, §10.3.7, §10.2.2, §10.1.2, §10.4, PL-1, T10-16, T10-20 (r3.txt), and for §10.1, §10.2, §10.3, §10.5 (r3b.txt); filters of those files.
17. `grep -rn` and `grep -rno` over copy 06-theory for subsection citations outside Ch. 10; `sed` of THEORY-MAP L390–393 and 09.18 inbound lines.

Nothing in the repository was edited, and no text was sent to any outside service.
