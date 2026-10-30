C47 STEP 2-3: REVIEW REPORT

*Window C47, §8.40 (C1–C6), sources S0229–S0231. Transition review, Rulebook v1.4. I re-read my saved inventory first. Its sha256, 9c6f2406…cb81, matches SHA256-STEP1.txt. Process independence only: I did not write §8.40 and saw it only after step 1 was saved. It is not personal independence.*

## Summary (10 lines)
1. §8.40's source citations are accurate: every line range I checked against S0229–S0231 falls inside the cited text. Nothing is critical, and no derivation rests on §8.40.
2. **The C1 gap is read correctly.** §9.13 L166 leaves the headline and the reasons for T and F undefined; L139 and the defect table's row 3 ("reasons for F open") say the same.
3. **C1's list of F cases misses three cases.** It omits an adoption that exists but is not authorised, a queried scope the adoption does not cover, and an adoption or validity that is not yet in force at t. It also overlooks §9.13 R2a, an existing home that already lists the sources of F and labels endings and annulments "refuted".
4. **C1's mapping of S0231's prior absence is wrong as stated.** Prior absence means an absence until a later creation event. Its exact counterpart in the book is "adopted, or valid, only after t", not "no act ever".
5. **C6's main support fails.** It calls "orthogonal, necessary and sufficient" "the model-integrity test the book applies" and cites CLAUDE.md for it. The book's register marks that form, CON-0009, **INVALID**; the restated test is CON-0162, which is PROVISIONAL. S0231 states the classification but applies no test.
6. **C5 repeats C46's W-02 error.** "Retirement has no home" treats a kind that O1 requires but D does not admit as if it had no home.
7. **C2 is right per axis, but its three-way correspondence is wrong.** S0231's UNKNOWN maps to "not attested", not to U (§8.33 C1 already says so). C2 also omits the premise L4 and T9-15.
8. **Destroyed absence is mapped two ways.** C1 maps it to "ceased", C6 to a tombstone. A tombstone keeps the entry's identity and changes no value.
9. **The C46 settlement (03a3ef70a) matches its rows.** One stale g0 label is left in §9.18 L82.

## Findings

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C1's list of F cases is incomplete, and it overlooks an existing home.** F also arises from: an adoption that is present but not authorised (no basis cited; a basis failing its record constants: holder, window, covers); a queried scope the adoption does not cover; and an adoption with t_y > t, or a validity starting after t, which leaves the disjunction empty. §9.13 R2a already lists the sources of F and labels "an effective ending or annulment" as **refuted**, against C1's "ceased" and "voided". **Prior absence** in S0231 has `valid_until: creation_event` (L125–134): an absence until a later creation. Its counterpart is "not yet", not (a) "no act ever". | high | §9.13 L118–129 (Φ, current-authority); §9.13 R2a (≈L208); defect table row 3 (≈L264) | Add R2a to the homes table, and say that C1's labels differ from R2a's "refuted" (which is T10-21 (1)'s naming problem). Add the cases "not authorised" and "scope not covered" (the second matches S0231's own locus, *anuyogin*, L274–277) and "not yet". Map prior absence to "not yet", and give "no act ever" its own closed-world name. Keep "at least". | C1 lists R2a's sources and the three missing cases, and the prior-absence mapping is restated |
| W-02 | **C6, "a value, not a dimension": the test it cites is INVALID in the book.** "orthogonal, necessary and sufficient" is CON-0009, "INVALID … mis-typed". The restated test is CON-0162 (a distinction required on a case set C that cannot be expressed without the dimension; PROVISIONAL). The cited home, "model integrity (CLAUDE.md observation)", is a repository instruction, not the book. S0231 L725–731 asserts "Not a seventh dimension… A dual state" and gives no reasons, so "agreement in method" overreads the source. | high | CONCEPT-REGISTER CON-0009 and CON-0162; §3.2 L7; R3 on CON-0009: 5 concept sites | Cite CON-0162. Either apply it (is any distinction required on the cases that the book expresses only through values such as F, "not attested" and a tombstone, and cannot express without a new dimension?) or say "agreement in conclusion, not in method". | Homes row and C6 restated; CON-0009 no longer cited as the book's test |
| W-03 | **C5, "retirement has no home", treats not-yet-built as absent.** Retirement is a required kind (O1, CON-0188) that D does not admit (§9.13 L52). Ch. 1's standing space also holds *historical* (§1.2 L8, L48). This is the pattern of C46 W-02, which the settlement recorded as a lesson. | high | CON-0188; §9.13 L52; §1.2 L8, L48 | "Retirement: required (O1), not admitted (D); whether it reduces to revocation, or is a transition to *historical*, is open." Apply this to the title, the index row and plain-language item 5. | No "no home" remains for a required kind |
| W-04 | **C2's three-way correspondence is wrong, and it omits a premise and a home.** "U, not attested and refuted … S0231's three-way separation" puts S0231's Unknown at U. But S0231's UNKNOWN "because absence of evidence" (L415–427) is "not attested" (§8.33 C1; §8.36 C4 (iii)), and the book's U, a record that does not decide, has no counterpart in S0231. On the authority axis, the closed-world F rests on L4, transcription completeness (§9.13 L141; R2a), so S0231's warning returns there as an assumption. S0231's "searched, found nothing" is the input T9-15 asks for ("examined, nothing found"). | normal | §8.33 C1 L44–48; §9.18.5 (v) L172–177; §9.15 L155 | State the mapping explicitly: Unknown = not attested; Absent ≈ refuted (of the existence claim); U has none. Add L4 and T9-15. | C2 restated |
| W-05 | **One source kind is mapped two ways.** Destroyed absence is "ceased" in C1 (b) and "removal by tombstone, *Agreement*" in C6. A tombstone keeps the entry's identity and every required field, and changes no value (§9.13 L59–62, check 23), so it is not the non-existence of the item. The agreement holds only for the provenance requirement (removal is an authorised act that the tombstone cites). | normal | §9.13 L59–62; S0231 L156–165 | Keep the C1 mapping. Restate C6 as "agreement on provenance only". | C1 and C6 consistent |
| W-06 | **"Forbidden" and "impossible" are merged, as in the source.** C1 Int calls both "constraints (C6)", and C6 calls forbidden states "agreement in shape". Forbidden is deontic (a norm the kernel prevents or detects, §9.16 R3a); impossible (atyantābhāva, the Kubernetes example, L176–188) is an alethic content constraint, the application's. S0231's "stronger than approval_required" (L640) is asserted, not shown. | normal | inventory S0231 items 9 and 11 | Separate the two: deontic goes to R3a; alethic goes to content. Mark "stronger" as unsupported. | Restated in C1 Int and C6 |
| W-07 | **C1 uses S0231's kinds without recording that the source's own lists disagree.** The lists at L55–60, L355–358 and L772–775 / L802–808 differ. "Unknown" is also listed inside Absent (L59, L358), against L379–389. Plain-language item 1 repeats this ("or nobody knows" as a kind of absence), which contradicts C2. The use is fair only for the two kinds that are stable across all the lists: never existed, and destroyed. | normal | inventory S0231 items 7–8 | Add the caveat. Drop "nobody knows" from the plain-language list of kinds. | C1 and plain-language item 1 restated |
| W-08 | **C3: "owns" has two meanings, and its support is weaker than stated.** T10-16 (b) says *no* context owns the record: it is changed only by an authorised act outside every context. So "KnowledgeOS owns the record of authority … agrees" needs "owns" read as "holds and guards", not "decides". ARCH-INV-1 is a relayed ARB proposal with one line of support (§1.14.2-1), so the "contradicts" conclusion rests on A1 and the roles law (§1.14.3, §10.4 R1), not on ARCH-INV-1. S0229 L200–212 is an illustrative glossary entry. Its "lifecycle, validation" (§1.2; §8.36 C1) is not answered. | normal | §1.14 L7, L278; T10-16 row; §10.1 L70 | Qualify "own", carry the rest of the source's sentence, and record the rival "an illustration, not a claim". | C3 restated |
| W-09 | **C4: a rival reading is untested.** In S0229, "evidence" means realisation (code, logs, production behaviour, L416–449), and CH1-001 sits in a manifestation flow. On that reading it concerns implementation, not well-foundedness, and it does not meet promise (b). Behaviour as evidence has a home outside D (§9.13 L49). | normal | S0229 L406–457 | Add the rival and answer it. | Challenge recorded |
| W-10 | **C6: H-KOS-Abhāva-001 is two statements, not "one candidate in two spellings".** L70 and L817 differ in content, and the later one is stronger (identity, provenance, "never reduced"). | normal | S0231 L68–70, L815–817 | Record it as a change within the source. | Restated |
| W-11 | **Changed theory: one stale label remains.** §9.18 L82 (G7): "g0's inputs (k, s, purpose, g)", whereas g1 is the live rule. L146 also has broken nested italics. W-04's completion condition ("no g0 statement remains unless marked historical") is met except for this label. | low | `grep -n g0` on §9.18 | "g1's inputs (as g0's)". | Fixed |

## Claim verdicts

| Claim | Verdict | Reason · scope |
|---|---|---|
| C1: L166 leaves the reasons for T and F undefined | VALIDATED | L166, L139, row 3 |
| C1: (a), (b), (c) are distinct ways F arises | VALIDATED as "at least" | Φ L120, ρ L82, P L87 |
| C1: S0231's kinds are "the missing reason vocabulary" | PROVISIONAL | W-01, W-07 |
| C1: prior absence = never recognised | INVALID as stated | W-01 |
| C2: evidence axis agrees; authority axis is closed-world | VALIDATED, with the L4 premise added | W-04 |
| C2: "S0231's three-way separation" = U / not attested / refuted | INVALID as stated | §8.33 C1 |
| C3: KnowledgeOS does not own authority | VALIDATED | from A1 and the roles law |
| C3: the record reading "agrees" | PROVISIONAL | W-08 |
| C4 | VALIDATED on the support reading | rival untested (W-09) |
| C5: a check is not acceptance | VALIDATED | §8.36 C1 |
| C5: pipelines are applications | PROVISIONAL | Int; the three boundary tests are not separated |
| C5: retirement has no home | INVALID as stated | W-03 |
| C6: value, not dimension "by the book's test" | INVALID as stated | W-02 |
| C6: removal agreement | PROVISIONAL | W-05 |
| C6: forbidden states | PROVISIONAL | W-06 |
| C6: relays, technology, finality | VALIDATED | prefixes: W-10 |

**Proof-step table for C1's Ded:**

| Step | Justification | Defined? |
|---|---|---|
| F is "refuted or not recognized" | §9.13 R2a; T10-21 (1) | yes |
| (a) no adoption gives F | current-authority L129 | yes |
| (b) revocation, ending or closed validity gives F | L129; P L87–91 | yes |
| (c) annulment voids from the start | Φ L120; ρ L82 | yes |
| outside D is refused; ties and cycles give U | §9.13.2; R2 (d) | yes |
| the three cases cover F | **not claimed** ("at least"); R2a and L129 show further cases | — |

Judgement: the steps are adequate for "at least". For the title's "the vocabulary" the table is inadequate: one premise is missing (completeness of the cases), and it fails.

**Recorded challenges:**
- C1's rival: inadequate. The answer asserts a structural match that fails for (a). The stronger rival is that S0231's Navya-Nyāya parts (the thing absent, the locus, the limitor) fit Q1's (c, x, t) better than its kinds do.
- C3's rival: partly adequate. It misses T10-16 (b)'s "no context owns it".
- C5's rival: adequate; the question is left open honestly.
- C2, C4, C6: no challenges recorded. Testing C6 would have found W-02.

## Coverage against my inventory

| Item | State |
|---|---|
| S0229 items 1, 3, 5 (CH1, CH3), 8, 12; S0230 items 1, 3, 5, 6, 7, 10, 14; S0231 items 2–5, 10, 13 | COVERED |
| S0230 items 8, 13; S0229 item 8 | JUSTIFIABLY EXCLUDED (§8.40.3) |
| S0231 item 6 (the four parts; locus = scope) | MISSING, normal (W-01) |
| S0231 items 7–9, 11 | MISSING, normal (W-06, W-07) |
| S0231 item 1 | DISTORTED (W-10) |
| S0231 items 12, 14, 15 | PARTLY COVERED; low ("Deprecated" bears on C5; "dual" is three-way; contradiction untreated) |
| S0229 item 6 and S0230 item 2 (documents ≠ reality; behaviour as evidence) | MISSING, normal (W-09) |
| S0229 item 13 (no transition criteria: the "skip" cannot be detected; §1.2 makes it checkable) | MISSING, normal, beside C5 |
| S0230 item 4 (origin type × state; "false" only after the fact) | MISSING, normal, beside C4 |
| Cross-source "False" homonym (a mistaken belief in S0230; a truth value in S0231; Q1's F) | MISSING, normal; it bears on T10-21 (1) |
| S0229 items 4, 7, 9–11; S0230 items 9, 11, 12 | MISSING, low (preferences or source defects) |

New fork: none. W-01 and W-04 sharpen T10-21 (1) and T9-15.

## Next steps (dependency order)
1. W-01, then W-07: restate C1. Done when R2a is a home, the cases are complete, prior absence is "not yet", and the caveat is added.
2. W-04: restate C2's mapping, with L4 and T9-15.
3. W-02 and W-05: restate C6, citing CON-0162.
4. W-03: retirement as required but not admitted.
5. W-06, W-08, W-09, W-10.
6. W-11.

## Limits
- I did not check §9.16 R3a, §10.2 G7, §8.38 C8 or §8.35 C7 beyond their citations, and did not read §1.14.3 in full.
- ce_model was not run. The quote check is not mechanical ("(Snnnn Lx)" citations; tool limit). I checked line references against the source text read at step 1.
- I did not review §8.41. R3 shows that §8.41 L63 cites §8.40 C6 (graph-native); my corrections leave that point unchanged.
- R2 in scope (§8.40), 12 tier-D candidates, judged one by one: metaphor, pipeline, lose, revoked, dropped, action, admit, agent, again, separation, rival and start are all ordinary or used in their book sense; no defect. Tier A used in scope: "annulled" and "voided", both defined earlier. "never recognised" and "ceased" are new reason names, proposed and unflagged.

## Files opened
- W/06-theory/manuscript/review/C47-S0229-S0231/BRIEF-W-STEP2.md, STEP1-INVENTORY.md, SHA256-STEP1.txt (cat), STEP8-AND-CHECKS.md
- W/06-theory/manuscript/chapter_08/08.40-typed-absence-is-the-missing-reason-vocabulary-for-F-and-knowledgeos-does-not-own-authority.md
- W/06-theory/manuscript/review/C46-S0226-S0228/SETTLEMENT.md, REVIEW-REPORT.md
- The `git show 03a3ef70a` output (through a saved tool-result file in the harness's project folder)
- W/06-theory/manuscript/chapter_09/09.13-the-evaluator.md (L1–200 read; L200–373 through grep)
- Through sed or grep only: 09.18 (L172–203, g0 lines); 08.33 (L37–48); 08.36 (L80–86); 01.14, 01.13, 01.06 and 01.02 (grep lines); 10.01 and 10.04 (grep); chapter_10/TODO.md (T10-01, T10-15, T10-16, T10-21); 09.15 (L4 lines); CONCEPT-REGISTER.tsv (CON-0009, CON-0162, CON-0188, CON-0141, CON-0221); a grep of the book and THEORY-MAP for "orthogonal"
- The three sources were not re-opened (they were still in context from step 1). No other corpus file was opened.

## Commands run
- `git log --oneline -3` on the C47 folder; `sha256sum` of STEP1-INVENTORY.md against SHA256-STEP1.txt
- `ls` of the C46 folder, the manuscript, chapters 1, 9 and 10, and tmp/tools; `wc -l` of 09.13
- `git show --stat 03a3ef70a`; `git show 03a3ef70a -- chapter_08/ chapter_09/`
- `grep` for g0 in 09.18; T10 rows; CON-0188; reason, 9.13.10 and headline in 09.13; ARCH-INV-1, 1.14.3, 1.13.1, R1 and A1; promise (b); §1.2 created/authoritative; "orthogonal" and "model integrity"; (iii) in 08.36; C1 in 08.33; L4 in 09.15
- `awk` on CONCEPT-REGISTER.tsv
- `python3 -I tmp/tools/undefined_terms.py --scope 08.40` (default root)
- `python3 -I tmp/tools/inbound_citations.py` for "§8.40 C1", "§8.40 C2", "§8.40 C5", "§8.40 C6", "§8.40" and "CON-0009" (default root)
- Nothing was edited, created or committed in the repository, and nothing was sent to an outside service.
