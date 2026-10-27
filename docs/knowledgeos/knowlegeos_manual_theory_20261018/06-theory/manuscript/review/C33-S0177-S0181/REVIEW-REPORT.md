C33 STEP 2-3: REVIEW REPORT

Window C33 (§8.26, S0177–S0181) and the changed theory `167e5a7b8` (model v18). Reviewer W (Opus). I re-read my saved inventory first.

**Correction to my step-1 inventory.** My "n+1" note was wrong: the viewer's extra line is empty, as you checked. No line reference in the inventory changes.

## Summary
1. The completion of decision `7ecf5a024` closes the fork and stale cases **inside one serialised kernel (K6)**. Across applications that are merged, it only detects a fork, and the claim "every accepted entry is a member" is false there (probe P1b). This is the high finding W-01.
2. Checks 1–28 really are unchanged. v17 and v18 output differ only in the label of line 29.6, and the committed run file matches my run.
3. Check 30 is sensitive to five of its six mechanisms. The chain-reach clause is never exercised (W-04).
4. The completion adds D-level clauses that the decision record does not state. With them, a write that bypasses the kernel can put the whole record outside D (W-02).
5. "Last entry" survives at §9.17 L64 and L158 as an unmarked quote. That 2026-10-25 quote is also not the record's wording (W-05).
6. §8.26's lineage is wrong for S0178. Three of the window's inputs are not on the allowed list: 204018 and 123902 (both described at length) and 104802. Only 104802 is acknowledged (W-06).
7. C3 is too general, and it misses a real contradiction inside S0179 (DC-2) (W-07).
8. C2 is now verified against S0154 itself. C1 holds for its strongest cases but not for D1.

## Findings

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | Two statements are unscoped: "a fork and a stale designation are refused" (§9.17 R3 (c) 1; §9.13.2) and "every entry the kernel accepted into the old store … all of them are members" (R3 (c); CON-0264). Both hold only under one serialising kernel for all appends to the carrier. The book already draws this line for grants: G4 is "K6 within an application; K4 across applications". | high (critical if the consolidation finds more than one kernel may append to one carrier: CON-0264 and K2-w rest on it) | My probe (scratchpad `probe.py`, v18 functions, the model's own `merge`). **P1:** two applications each append a designation of C1; both are accepted at their own append; the merged record is outside D ("a fork"). So the fork is detected, not refused. **P1b:** application A designates C1 citing x1, while application B's kernel accepts x2 (citing x1) into C1. The merged record is **in D**, and x2 is **not** a member. | Scope both statements to "within one serialised kernel (K6)". Across applications, state: fork = detected (outside D); a concurrent accepted entry = classed foreign, the safe direction CON-0264's closing-list route already admits. Or require one coordination point per carrier for designations (a K4 analogue). | a stated scope, plus a check of the P1b case |
| W-02 | The record states none of the D clauses "one chain, at most one designation per store, no unknown store". With them, a bypass write decides membership of D. **P2:** a bypass entry labelled with store C9 puts the record outside D. **P3:** a bypass designation of C1 is reported as "a fork". The record's limit sends bypass entries to CON-0243 instead. | high | `in_D` L306–323. P2 and P3 outputs. | List the clauses as the Professor's additions. State that a bypass entry with an unknown store label, or of kind designate, takes the record out of D, as any ill-formed entry does. Decide whether that is intended. Correct the "fork" wording for bypass designations. | stated and labelled |
| W-03 | §9.13.2 says D checks that the closed entries are "maximal". The code checks only that they do not reach one another. Maximality in the store is checked only at append. **P4:** a record built directly, whose designation omits the maximal entry x2, is in D, while the guard refuses the same designation. | normal | `in_D` L292; P4. Combined with W-01, a merged record can be in D with an accepted maximal entry omitted. | Say "maximal among themselves; maximal in the store only at append". | text fixed |
| W-04 | Check 30 does not test the chain-reach clause. | normal | Mutants M1–M5 (no carrier-in-force guard; maximality not limited to accepted entries; store set by the caller; no fork clause; no unknown-store clause) each fail exactly their own sub-check. M6 (chain-reach clause removed) passes every assertion. | For guarded appends the clause is derivable: the predecessor designation lies in its own carrier, so it is reached through that carrier's maximal entries. State it as derived, or add a case built directly. Note also that the merge-order sub-check only guards against a positional reading. | either done |
| W-05 | "Last entry" is still presented as current at §9.17 L64 and L158: the 2026-10-25 quote "(the switch cites the old store's last entry, CON-0251)" carries no supersession mark. v18 deleted v17's note "These replace the 'last entry' reading". That quote is also not the record's text. The record reads: "a recordable kind of entry in D, appended as the first entry of the new store and citing the old store's last entry; order across a designation comes from that citation (CON-0251), not from recording time" (decision record, line 428 at HEAD). §8.19 L236 keeps the 2026-10-25 sentence, and the 2026-10-27 update appended to it does not say it replaces it. | normal | `grep` results; `git show HEAD:` of the record | Mark the quote as superseded at L64 and L158. Quote the record, or name the source of the other wording. Say "replaces" at §8.19 L236. | done |
| W-06 | §8.26's lineage is wrong and incomplete. **S0148 is not an input of S0178.** S0148 is `reviews/2026-08-21-track2-phase1-adoption-evidence.md`. S0178's own inputs are 204018, S0149, S0151, S0160 and S0172, plus S0162 in its body. Three inputs are not on the allowed list: 104802 (S0177 L20), 204018 (S0178 L16, L30–35, L79–86, L140–143; S0179 D1, D3, P2) and 123902 (S0181 L16, L27–29; S0179 P1, P7, C7, C15, DC-6, DC-7). §8.26 acknowledges only S0177 L20, and calls 123902 "not yet read" (as does §8.25). C1 cites S0181 L27–29, which quotes 123902. | high | `grep` of CORPUS-ALLOWED.tsv by timestamp: 204018, 123902 and 104802 have no row | Correct the lineage. Record 204018 and 123902 as **exposed through summaries** for the hold-out test (as was done for S0155). C1 still stands, because it attacks the tag, not the content. | lineage fixed; exposure recorded |
| W-07 | **C3 is INVALID as stated**, for two reasons. (a) Counterexample: a self-enacting artifact. A decision record's own entry *is* the act, for example the book's decision record, "yes record them". Its status is the act. (b) C3 says there is "no contradiction to resolve", but S0179 has one. DC-2 says "execute the approved plan" (L205). The same document says the plan is PROPOSED (L206) and "PROPOSED, NOT executed" (L115). The act decided D1–D3 (B′, R-CONFLICT, placement), not the plan. | high | S0179 L115, L205–206; S0177 L55–61 | Restate C3: (i) "an artifact that is not itself the record of the act"; (ii) add "an act decides only what it names; a summary must not transfer its status to an artifact the act did not name" (DC-2). | restated |
| W-08 | C1's answer to the rival reading is adequate for P1, S0181 L27 and S0180 L38–41. It is inadequate for D1. Stronger rival: S0179 counts the corpus as part of the repository, so "the corpus holds position X" is a current fact with direct repository evidence. D7 and D8 are similar if they are read as relayed rulings. S0178 has no criterion of its own, so "does the same" must say "by S0179's criterion". The cleanest instance is missing: S0177 L108, "[ESTABLISHED] … (candidate)". | normal | S0179 L19, L33, L39–40 | Scope C1 to these instances. Mark D1 as ambiguous under the rival. Add S0177 L108. | done |
| W-09 | C2 is verified against the source. S0154 describes one LCOM4 engine with a binding per language for analysing source code (L60–64, L100–117, L156–166, L371–381). Nothing in it binds KnowledgeOS components to each other. S0181's statement that Python is an "analysis-side choice" (L61–62) also omits the act/adviser split: the act names no language (S0154 L578–580). | normal | S0154, read in full | Drop the caveat "S0154 was not reopened". Add the omission. | done |
| W-10 | C5 calls S0180 L78–81 part of "the current state". Those lines are tagged [PATTERN][PROPOSED]. | normal | S0180 L74–81 | Treat L38–41 (reported current state) and L78–81 (a proposal) apart. | done |
| W-11 | C6's "homonym" is partly distorted. The book's kernel is a specified mechanism (it appends and refuses; it has a guard; K6), not only a theoretical core. So it is one concept at two statuses: specified, and not built. There is also a third use, a proposed architecture boundary of primitives and ports (S0179 P10, DC-6; S0181 L37–39). S0179's negation (L119–122) has no source; it argues from absence. | normal | §9.13.2; §9.17 ("not the estate's record") | Restate it as "not built, consistent with the book's kernel being a specification", and record the third sense. | done |

## Answers to the brief's questions on the changed theory

| Question | Answer |
|---|---|
| Does the completion close the fork and stale cases? | Yes, inside one serialised kernel (check 30; M1 kills it). Across applications it detects only, and the membership claim fails (W-01). |
| Does it add a condition the record does not imply? | **Carrier in force:** implied by "change of carrier" and K2-w; a fair interpretation. **Kernel-set store:** a technical addition that the record does not mention; correctly scoped against R1. **Maximality over accepted entries:** narrows the record's own definition ("every entry no other entry of that store cites"). It is justified by the record's limit ("not seen by this check"), so it should be labelled as an interpretation. **The three D clauses:** additions (W-02). |
| Are checks 1–28 unchanged? | Yes, verified: the outputs of v17 and v18 are identical apart from the line 29.6 label, and the committed run file is byte-identical to my run. |
| Do the settlement rows match the text? | W-01, W-03, W-04 and W-07 match. W-02 is partial: §8.19 L236 keeps the old sentence, with an update appended. W-05 matches for the quotes of `7ecf5a024`: §9.17 R3 (c), §9.13.2, §8.22 C7, TODO T8-04 and CON-0257 match the record word for word. It does not hold for the 2026-10-25 quote (W-05). The map (L193) and the §8.23 T8-04 line paraphrase the decision and drop "that adopts it explicitly"; they are not marked as quotes, so this is acceptable, but noted. |

## Claim verdicts

| Claim | Verdict | Reason and scope |
|---|---|---|
| §8.26 C1 | VALIDATED (P1, S0181 L27, S0180 L38–41); PROVISIONAL (D1, D7, D8) | the rival "corpus = repository" is not answered for D1 |
| C2 | VALIDATED | checked against S0154 itself |
| C3 | INVALID as stated | self-enacting artifact; DC-2 (W-07) |
| C4 | VALIDATED | reported, one line with S0160 and S0164 |
| C5 | PROVISIONAL | mixes a proposal into the reported current state (W-10) |
| C6 | PROVISIONAL | "not a contradiction" holds; "homonym" needs restating (W-11) |
| v18 completion (§9.17 R3 (c), §9.13.2, CON-0264) | PROVISIONAL | sound within K6; across applications W-01 and W-02 |

## Coverage of my inventory

| Item | Status |
|---|---|
| Inversion of authority and durability (S0177-1) | COVERED (C4) |
| Graded decision status (S0177-4) | COVERED (C3); DC-2 MISSING (W-07) |
| Status tags breaking their own criterion (S0179-1/2) | COVERED (C1); S0177 L108 MISSING |
| Warn-only checker vs "a warning script is monitoring, not enforcement" (S0177-12) | MISSING (normal): by §8.24 C11 the G-1 checker is monitoring |
| R-CONFLICT, "never resolved by choosing a side" (S0177-3) | MISSING in the chapters (found only in old reviews and the outline). It now bears on v18: a fork goes outside D and no side is chosen, which is consistent |
| Three-way split: source / evidence / decision record (S0177-2) | MISSING (normal) |
| Grant counts 99→114 vs "20 live" (S0178-10; S0177-15) | MISSING: source inconsistency, unreconciled |
| UNKNOWN ≠ DOES NOT APPLY ≠ EMPTY SCOPE (S0178-9) | COVERED (§8.25 C4) |
| DRAFT missing from the lifecycle; unstable attribution (S0178-7, -11) | MISSING: source defects |
| S0181 says adapters exist as components; S0179's component list names none | MISSING (normal) |
| S0180 T4: an outbox alongside the synchronous log, "two write paths" | MISSING. The answer is in the book (§8.22 C9: a stream not designated as the carrier is a projection), but no line applies it |
| S0180 dangling pointer "[07] §T2"; attribution drift; DC-6 justified by assertion; `_misc/` treated as first-class sources | MISSING: source defects; recording only |
| C2 against S0154 | now COVERED and verified |
| S0179 "reconciliation, not a synthesis" vs footer "Synthesis of …" | COVERED (§8.26.3) |

New topic for the map: **multi-kernel and merge scope of the designation rule** (W-01).

**Process independence:** this review is independent of the Professor's process, not by a different person. I wrote step 1 blind, before seeing any of the Professor's work.

## Limits
- I did not open the C32 REVIEW-REPORT, any chapter outside the diffs, or CON rows outside the diff.
- I did not check R1, R2, R3 or the quote check with the Professor's tools.
- The probes are small constructed cases: a passing or failing probe is not a proof.

## Files opened
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C33-S0177-S0181/BRIEF-W-STEP2.md`
- `…/review/C33-S0177-S0181/STEP1-INVENTORY.md`
- `…/review/C33-S0177-S0181/STEP8-AND-CHECKS.md`
- `…/review/C32-S0171-S0176/SETTLEMENT.md`
- `…/06-theory/manuscript/chapter_08/08.26-a-summary-is-not-evidence-and-an-artifacts-own-status-is-not-the-act.md`
- `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/20260818-213516-lcom4-multi-language-binding.md` (S0154; checked on CORPUS-ALLOWED.tsv first, row 1534)
- the harness-saved output of `git show 167e5a7b8` for the theory files
- earlier in this run, for step 1: the five sources and REVIEWER-INSTRUCTIONS.md

No file was refused.

## Commands run
All read-only.

**Git:**
- `git show --stat 167e5a7b8`, `git show --stat f0fb39c29` and `git show --stat 7ecf5a024`
- `git show 167e5a7b8` for the register, the map, chapters 8 and 9, and `ce_model.py`
- `git show 7ecf5a024` (the decision-record diff)
- `git show HEAD:` of the decision record, grepped for "recordable kind / last entry / designation"
- `git show 167e5a7b8^:…/ce_model.py` (saved to the scratchpad as v17)

**Greps and line reads in the tools and the book:**
- `grep` and `sed` over `tmp/tools/ce_model.py` (store, Record, `merge`, `in_D`, helpers)
- `grep` in chapter_09 for K6 and in the book for "carrier in force / last entry"
- `grep` for R-CONFLICT, the three-way split, warn-only and outbox over `06-theory/manuscript`. **Disclosure:** this grep also returned file names and matching lines from review folders outside C30–C33 (C8, CONSOLIDATION-S0036-S0065, CONSOLIDATION-S0131-S0150, WHOLE-BOOK-S0100, T1-F11, TT3). I opened none of them.
- `grep` of `§8.22` for S0154's path

**Allowed-list checks:** `grep` of `CORPUS-ALLOWED.tsv` for 17 timestamps, for S0148/S0150, for "design-patterns / product-architecture-v2 / delegation-map", and a row count; `wc -l` on S0154.

**Computation (scratchpad only; no repository file changed):**
- copies of v17 and v18; `python3 -I` runs of both
- `diff` of their outputs, and `diff` against `ce_model-run-20261027-v18.txt`
- `probe.py` (P1, P1b, P2, P3, P4)
- `mut.py`, building six mutants (M1–M6), and a run of each

Nothing was sent to any outside service.
