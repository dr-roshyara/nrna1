C37 STEP 2-3: REVIEW REPORT

Window reviewer W, transition review. Scope: §8.30 (C1–C5), the step-8 file, and the changed theory in `2a532de22` (the C36 settlement). I re-read my saved inventory first.

## Summary (10 lines)
1. **What §8.30 says the sources say is accurate.** The quote check gives 2 found and 0 not found. I checked S0199's relays against S0300, which is on the allowed list (sha256 matches): L379, L424, L438–443, L454 and L474 all match. There is no critical finding.
2. **C2 has the wrong home and counts one measurement twice (W-01).** The 23/30 loss, "density is not a completeness proof" and "preserved BY the loss" are already in the book at §8.19 C1 (CON-0245, from S0136 L134–149). §8.18 C4 (a) concerns the reconciliation test, which is a different site.
3. **C5's attack replaces S0200's "A" with the book's concept (W-02).** S0200's A axis holds status values (approved, proposed, rejected). Its "semantic state" is content, not §1.14's "state". The attack also hits multiplication, which the source never uses, and cites CON-0240 where CON-0241 is meant.
4. **C4's "wrong in both directions" is unfair to S0198 (W-03).** S0198 called F2–F4 annotations, not defects. "Independent" is repeated without the CON-0127 bound.
5. **C1 agrees with the whole-reading rule but does not test it (W-04).** S0199's narrowing of S0198's option 2 is a non sequitur.
6. **The open question on the grant identifier is already answered at §8.19 (W-06).** §8.30 also cites S0300 while saying the plan is not opened (W-07).
7. **The C36 settlement rows match the text they change,** with three residues (W-08) and one loose application of §3.2 (W-09).
8. **New git facts (W-10).** The P3-F1 erratum landed 6 minutes after S0199. A later "re-review ruling" commit exists, outside the window.

**Process independence:** I am a separate session from the Professor's, possibly of the same model family. The independence is of process, not of person.

## Findings

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **W-01** | **C2: wrong home, and one measurement counted again.** §8.19 C1 already holds the same measurement and the same words: "23 of 30 trials", every survivor dense and monotonic, "Density is not a completeness proof. It is preserved BY the loss" [S0136 L149]. It is CON-0245 VALIDATED, with CON-0237's model behind it. S0300 relays it from review `a282d14b` (S0300 L1438), and S0199 relays S0300. That makes three relays of one experiment. §8.18 C4 (a) concerns the *reconciliation* test under a prefix disagreement, not a live lost append. The two share a mechanism (content dropped at a shared seq), not a site. | high | §8.19 L38–46; S0300 L438–443, L1438; §8.18 C4 (a) | Make §8.19 C1 / CON-0245 the primary home and keep C4 (a) as "same mechanism, other site". Replace "now with a reported measurement" with "the same measurement, relayed twice". Correct the step-8 challenge answer. | C2 restated |
| **W-02** | **C5's attack tests the book's concepts, not S0200's.** (a) S0200's A is "how much organizational authority" (L86), with approved, proposed and rejected as its positions (L91–103). These are status values, so S0200's A axis is the book's *state* (§3.1 CON-0003). S0200 merges the two things §1.14 separates while claiming to keep them apart (L46). That is the fair attack. (b) "Semantic state" means content. Its home is §1.4 ("detail does not confer authority"), not §1.14, so "state" is a homonym here. (c) ΔA is undefined on an ordinal scale too, because differences need an interval scale. The conclusion therefore survives on a corrected premise. (d) Multiplication is not used by the source; the section body should say what only the challenge record says: the form is R², and "complex" adds nothing. (e) "Fails the source's own test (L394)" misapplies it. L394 forbids creating a concept from a representation, and S0200 creates none. What S0200 breaks is its own ordering (L398–414), by proposing two kernels and a layering first (L356–388). (f) The home table and L72 say "CON-0240 (§8.18 C3)", but §8.18 C3 is CON-0241. CON-0241 concerns a positional conflict inside one record, not two artifacts that differ in authority. The authority-side home is CE*'s U for conflicting unordered acts (§9.13; §8.18 C3 Int). | high | S0200 L46, L86, L91–103, L356–388, L394–414; §1.4; §3.1; register rows 0240/0241; §8.18 L46–49 | Restate the attack on (a)–(c). Move (d) into the section body. Replace (e) with the ordering breach. Correct the ID and the home in (f). | C5 restated |
| **W-03** | **C4 "wrong in both directions" is unfair, and "independent" is unqualified.** S0198 never called F4 a defect: it conceded that "the baseline already preserves the distinction" (L79) and classed F2–F4 as MINOR annotations. On F1, S0198 underclaimed ("may be true", L44); it did not err. S0199's own judgements are weak in two places: its F3 "hedge" argument (inventory S0199 item 9), and its narrowing of the remedy (W-04). S0199 is independent "per the 7-step rule" by self-declaration only, and was committed 14 minutes after S0198 (`e5f98a6ad` 23:24, `c673de5de` 23:09). | high | S0198 L44, L54–89; S0199 L4, L74, L117; git log; CON-0127 | Restate as: "S0199 strengthened F1 and agreed with S0198's own classification of F4; it is itself unverified on F3 and on the remedy". Qualify "independent: declared, not attested (CON-0127)". | C4 restated |
| **W-04** | **C1: the case agrees with the rule but does not discriminate it.** S0198 allowed "scan-and-classify … with any current-state inventory folded in" (L50). That option would have found S0300's §1, whose heading at L379 announces itself. So S0199's "only viable form" (L74) does not follow. The case supports "classify the parts before claiming they are irrelevant"; it does not support whole reading over a structured skim. The Int's "names the kind of source rather than a guess" is also inverted: the failed guess *was* a kind guess ("a migration plan is largely target-state", S0198 L44). The case shows that a document-level kind label can be wrong for a mixed document. Finally, CON-0120 is VALIDATED for searches; applying it to a reading extends its scope. | normal | S0198 L44, L50; S0199 L74; S0300 L379; CON-0120 scope | State the case as one instance (n = 1) that agrees with v1.4 §3 without testing it. Record the narrowing as a non sequitur. Mark the extension of CON-0120 Int. | C1 restated |
| **W-05** | **C3 misses CON-0132 (owned vs preserved invariant).** The baseline's "Ownership of invariants it mechanically enforces" (S0198 L56; S0199 L82) is exactly CON-0132's *preserve*. "Own" has two senses here, enforcer and domain owner: a homonym. C3 also omits that S0199 rated F2 only PARTIALLY CONFIRMED. | normal | S0199 L82, L97; CON-0132 | Add CON-0132 as the primary home for F2 and record the homonym. | added |
| **W-06** | **§8.30.3 says it is "not stated" whether the duplicate grant identifier is a duplicate act or a reused identifier. The book already answers this:** "Two distinct authority acts … share one identifier" [S0136 L269], at §8.19 (110/109). The series runs 110/109 → 114/113 → 126/125, with one surplus throughout. | normal | §8.19 L74; S0199 L46, L68; S0198 L28 | Point to §8.19 and state that the surplus stays at one. Whether it is the same pair is not shown. | restated |
| **W-07** | **S0300 is used while the section says it is not opened.** §8.30.3 cites "S0300 L1204", which the book read at turn 8. The transition row is actually L1203; L1204 is the grants row. The lineage note says the plan's "file is not opened here". S0199's relay could be checked against the book's own S0300 reading, and I have checked it (summary point 1). S0300 also places P-4 "on the interpreter axis" (L1229, L1237), so the closer home is §8.18 C12 / K7, not only C1/C13. | normal | §8.30 L8, L80; S0300 L1203–1204, L1229, L1237 | Label the S0300 use "(read at turn 8)" and correct the line. Mark the relay "checked against S0300". Add §8.18 C12. | restated |
| **W-08** | **C36 settlement residues.** (a) The §8.29 C5 header still reads "evidence for §9.17 R3 (c) and §8.24 C8" (L73), against the restated "only an analogy". (b) The map's C36 line says "PKS's four **orthogonal** axes", which asserts what C6 now calls unshown. (c) The map line and the index line drop "in the window" from "no separate disposition record". That turns a scoped absence claim into an unscoped one (CON-0120). R3 (inbound_citations): §8.29 C5 is cited only section-level, by §8.30 L17, L20 and L64, the change log and the map. | normal | §8.29 L73; THEORY-MAP L200; 08.00-index L53 | Correct all three. | done |
| **W-09** | **The application of §3.2 to M4's axes is loose.** It uses the orthogonality clause (CON-0009, invalid as stated) rather than the restated test (CON-0162: some in-scope distinction is inexpressible without the axis). The transfer also runs through value names. INTENT-STATUS was rejected because its values duplicate *status and authority*, so by the same reading M4's adoption would collide with M4's *authority* axis, which is not what "overlap that rejected axis" says. | normal | §3.2 table and restated test; §8.29 C6 | Restate: "by S0016's test, adoption's values would fall on M4's authority/status axes. Under CON-0162 it is undecided until a case is shown that needs adoption". | restated |
| **W-10** | **Git facts that C4 lacks.** S0198's commit subject reads "P4 gate OPEN conditional" while its text says "P4 gate remains CLOSED". The P3-F1 erratum (`6034db5c0`, 23:30, "density + one-writer corrected") landed 6 minutes after S0199, applying S0199's minimum and not S0198's option 2. `61219780e` (2026-08-22 03:21, "corrected-P3 re-review ruling") exists outside the window and has not been read, so it is known by subject only. | normal | `git log` of the P3 baseline and of S0198/S0199 | Record these as V facts at subject strength. Contrast with §8.29 C3, but do not claim that a disposition record exists. | recorded |
| **W-11** | **Omissions with theory content.** (a) An UNKNOWN produced by not reading is different from an UNKNOWN produced after the evidence is examined (S0197 L66 vs S0198 L46, "artificial UNKNOWN"). An UNKNOWN is itself an absence claim. (b) The corpus-boundary rule ("admitted by role, with a recorded finding", S0197 L53) is the must-do that keeps C1 from being satisfied trivially by shrinking the corpus to what was read. (c) S0200's two kernels, and EKS/PKS as "applications" (L359–385), are framings for T0-03 and T0-03b. (d) S0200's falsifier ("without losing domain meaning") has no criterion; §1.4 point 2 is the home. (e) The S0197 filename residue is an instance of §8.29 C1 ("a name can assert"). | normal | as cited | One line each, at the named homes. Route (c). | recorded |
| **W-12** | **§8.30 L3 names "the C35 settlement" as changed theory.** It is C36's (`2a532de22`), the same slip as C36 W-11 (d). | normal | §8.30 L3; STEP8 L3 | Correct it. | done |

## Claim verdicts

| Claim | Verdict | Scope / reason |
|---|---|---|
| §8.30 C1 | VALIDATED within scope | the facts are verified in S0300 L379; n = 1; agrees with the rule without testing it (W-04) |
| C2 | PROVISIONAL | the relayed facts hold (V); the "new agreement" is INVALID as stated, being one measurement already in the book (W-01) |
| C3 | VALIDATED within scope | homes incomplete (W-05) |
| C4 | PROVISIONAL | the phase-firewall point holds; "wrong in both directions" is INVALID as stated (W-03) |
| C5 | PROVISIONAL | ΔK is undefined, on a corrected premise; "fails L394" and the CON-0240 citation are INVALID as stated (W-02) |
| C36 rows W-01 to W-11 | match | except W-08 (a)–(c) |
| §8.29 C5 restatement (§1.5, §1.9; an analogy to §9.17) | VALIDATED | agrees with §9.17 L164–177 (detection, assumed attestation, limit (ii)); header residue (W-08 a) |
| Single commit `999ede26b` | right strength in §8.29 C3 | verified at 22:46:03, 8 min 12 s after `db9154664`; overstated in the map and index (W-08 c) |
| §3.2 applied to M4 | PROVISIONAL | W-09 |

## Coverage (my inventory)

| Item | Status |
|---|---|
| S0197 1, 12 (firewall, bookkeeping) | COVERED (C4) |
| S0197 4, 6, 10 (grading; quarantine; DDD not from folders) | MISSING, normal; same line as §8.29 C4 (S0196 L46) |
| S0197 5, 7; S0198 8 (UNKNOWN vs unread; corpus-boundary rule) | MISSING (W-11 a, b) |
| S0197 13 (filename residue) | MISSING (W-11 e) |
| S0198 3, 4 (registry-first platform; knowledge capability NOT BUILT) | COVERED for T0-03; T0-03a not named |
| S0198 6 (durability inversion) | JUSTIFIABLY EXCLUDED if pointed to §8.26 C4 |
| S0198 9–11 (F2–F4) | COVERED; DISTORTED in C4 (W-03); CON-0132 missing (W-05) |
| S0198 12 (8-class relationship vocabulary; kernel at Stage 5) | MISSING, normal |
| S0199 1–5 | COVERED (C1, C2); homes wrong (W-01, W-07) |
| S0199 6 (count series) | partly COVERED (W-06) |
| S0199 9 (F3 "hedge") | relayed, not assessed |
| S0199 11 (narrowed remedy) | MISSING (W-04) |
| S0199 12 (label disagreements at L283 and L291) | partly COVERED; which section labels are right is undecided (the baseline was not read) |
| S0199 13 (independence) | MISSING (W-03) |
| S0200 1, 7, 11 | COVERED |
| S0200 3, 5 | COVERED; DISTORTED (W-02) |
| S0200 9, 12, 13 | MISSING (W-02 e, W-11 c, d) |
| S0200 4, 6, 8, 10 (quadrants; temporal; facet lists; figure) | MISSING, normal; no theory use |

**New topic:** none. **No new fork.**

## Next steps (dependency order)
1. **W-01, W-02:** re-home C2; restate the C5 attack. Done when the step-8 challenge records are corrected.
2. **W-03, W-04, W-05:** restate C4 and C1; add CON-0132.
3. **W-06 to W-12:** pointers, labels and residues; route W-11 (c) to T0-03 and T0-03b.

## Limits
- I did not read the P3 baseline, its erratum, `61219780e`, S0136 or S0137 in full. The §8.19 lines were read as book text.
- From S0300 I read only the grep lines and L1200–1206.
- R1 and R2 are reported, not shown.
- The section-label disagreement between S0198 and S0199 is undecided.

## Appendix
- **Trivial case (C1):** "completeness established" is met vacuously by declaring the corpus to be whatever was read. S0197 L53 is the must-do that blocks this (W-11 b).
- **Lineage:** S0199 → S0300 → review `a282d14b` → S0136 is one line, not four.

## Files opened
- **Review folder C37:** `STEP1-INVENTORY.md`, `BRIEF-W-STEP2.md`, `STEP8-AND-CHECKS.md`.
- **Review folder C36:** `SETTLEMENT.md`, `REVIEW-REPORT.md`.
- **Book, in full:** §8.30; §3.2.
- **Book, in part (sed or grep):** §8.29 (L73–90); §1.5 (L1–20); §1.9 (L55–72); §9.17 (L164–180, grep); §8.18 (L35–57, L83–86, L126–129, L189–193); §8.19 (L38–46, L74–76); §9.13 (L1–30); §1.4 (L1–12); §1.14, §8.26 and §8.28 (grep).
- **Other W files:** register rows CON-0003, 0120, 0127, 0132, 0239, 0240, 0241, 0242; THEORY-MAP (grep); RULEBOOK-v1.4 (grep, L76–80); the TURN8 brief (grep, one line).
- **Through git:** the saved output of `git show 2a532de22`.
- **Corpus beyond the four:** S0300 `docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md`, checked first on `CORPUS-ALLOWED.tsv` (its line 62; sha256 match). I read only the grep lines and L1200–1206.

## Commands run
1. `ls` of the C36 and C37 review folders, `03-methodology`, `06-theory`, chapters 1, 3, 8 and 9, and `tmp/tools`.
2. `git show --stat 2a532de22`; `git show 2a532de22 --` the theory files; `git log --oneline -8`.
3. `git show --stat` of `999ede26b` and `db9154664`; `git log` of the four sources and of the P3 baseline path (subjects only).
4. `wc`, `grep` and `sed` on the book, register, map, rulebook and TURN8 brief lines listed above.
5. `grep` of `CORPUS-ALLOWED.tsv` for S0300; `sha256sum`, `wc`, `grep` and `sed` (L1200–1206) on S0300.
6. `python3 -I corpus_quote_check.py` on §8.30: 2 found, 0 not found.
7. `python3 -I inbound_citations.py` for §8.29 C5, §8.29 C3, §8.29 C6, §8.18 C3, CON-0240 and CON-0241 (plus two runs with bad arguments).

I edited nothing. Nothing was sent outside the machine, and no private person's name is reproduced. I opened none of S0155, S0156, S0157, S0161, S0170 or S0174, no `docs/knowledge_tranfer/*` file, no Ch. 0–2 basis file and no excluded path. No file was refused.
