C50 STEP 2-3: REVIEW REPORT

*Window C50. Under review: §8.43 (C1–C6) as corrected at `83d42209a`, its step-8 file, and the changed theory `83d42209a` (the C49 settlement). Transition review. Before starting I re-read my saved inventory; its sha256 (e6c2a11a…772e) matches SHA256-STEP1.txt. Independence is process independence only: I did not write §8.43 and saw it only after my inventory was saved.*

## Summary (10 lines)
1. Nothing in §8.43 is critical. Its line references to the five sources are accurate. Three high findings concern C2, C3 and a missing point in C6.
2. **C3 misnames the reading.** S0243 names no act, so its gate is not the guard on an adoption act (§8.42 C2 reading (ii), which keeps A1). It is a verdict that decides admission itself, the type of reading (i), with (ii)'s four conjunctive parts. Its "authority recorded" step has a rival the draft does not answer: the Authority Service records "a reference to a human act" (S0243 L80).
3. **C2 holds for the articles but not for S0243's path.** S0243 §6 requires every proposal to become an evidence record and every refusal to be kept as REJECTED. So "a gate that admits nothing" satisfies the architecture only if it records every input. The architecture has a must-do on recording (like O2), though none on admission.
4. **C6 does not test S0243's central claim.** That claim is "structurally impossible regardless of implementation". With implementation left unconstrained, the claim reduces to a SHALL on implementations, a forbiddance. The book already has the home for this: forbidden vs impossible (§8.40 C6).
5. **C1's attribution is half wrong.** The closure half ("no new concepts… the corpus is closed") is prescribed by the commission (S0242 L15, L69). Only the "never weaken" half is the product's. A further point is missing: the Constitution's own final rule (S0241 L13) conflicts with V.2 whenever an article is found to overstate its evidence.
6. **C5's identity line reaches the right conclusion on the wrong ground.** Art 1.3 also forbids identity from a perfect match. The real reason there is no conflict is that §9.16 R2 is about entry identity and Art 1 about item identity. Comparing the two repeats C46 W-01 (§8.39 C3 (b)).
7. **Patterns.** "A record's sentence taken for the act" recurs once: S0244 L15 is attributed as the HPA's assessment. "No home": §8.43.3 points Art 4 and Art 5 to treatments that do not exist. No form the register marks INVALID is cited as an agreement.
8. The changed theory `83d42209a` matches every settlement row, with two gaps. W-03's "law 1's relations" never reached §8.43. W-06's restatement was not carried to §8.43 L30.

## Findings

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C3, Ded step 1, calls the gate "the guard reading (ii)", but step 2 shows that no act is involved.** By §8.42 C2, (ii) is "an adoption is refused unless it cites…" and it *keeps* A1. S0243's gate decides admission from the engine's verdict (L138–139), with no act, which makes it a decision function. Step 2 also says "a service, not the holder of a grant", yet in the book a system actor may hold a grant (§8.36 C1; §10.4 R1). The actual defect is that **no act and no grant are named**. The recorded rival is answered without S0243 L80: Authority Service "records authority as an assignment — a reference to a human act", read together with IV.3 (authority assigned later by governance). On that reading admission is not authority, so there is no conflict with Art 3.2 or A1. The defect then becomes a verdict class decided in the kernel (§8.37 C2; §10.2). | high | S0243 L74, L80, L138–142; §8.42 C2 (i)/(ii); §8.36 C1 | Restate the Ded as two readings. **(a)** If admission is standing, the gate is a decision function: it breaks A1 and Art 3.2. **(b)** If admission is an epistemic class and authority is a separate human-act reference (L80, IV.3), A1 is kept, and the defect is a verdict function in the kernel. In either reading the conditions are (ii)'s conjunction and cannot be evaluated on D. "Authority follows the verdict" becomes Int. Re-answer the challenge with L80. R3: §8.44 L22 cites §8.43 C3. | C3 restated; §8.44 L22 checked |
| W-02 | **C2: "a Verification Gate that admits nothing satisfies every row" holds for the table in §3, not for the path in §6.** S0243 says "Every claim… follows exactly this path": PROPOSAL → "evidence record created" (L129–133) and REJECTED → "PRESERVED" (L147). State Preservation "never discards" (L84). A system that records nothing breaks this once any proposal arrives. Art 7.1 ("failed reasoning SHALL be preserved") reads the same way. The book's refusal is not a state change (§9.10), so recording refusals as states is also a difference from the book. | high | S0243 L84, L129–147; S0241 L83–84; CON-0188 (O2) | "The articles require nothing to be accepted. S0243 requires every proposal to be recorded and every refusal to be kept, but nothing to be admitted. The minimal satisfying system records every proposal as REJECTED or HELD, admits none, and answers U. It has an O2-like duty, and no O1, O4 or Q1." | C2 Ded restated |
| W-03 | **Missing: S0243's governing principle, "structurally impossible… regardless of how any engine… is implemented" (L14–16).** Implementation is out of scope, and conformance is only a SHALL (L46: "SHALL conform… SHALL NOT cross"). So the "impossibility" is a deontic forbiddance on implementations, not an alethic impossibility; a store written directly bypasses the gate. The book's home for this is §8.40 C6 (forbidden = prevented at append or detected; impossible = alethic). §8.43 records only self-certification. | high | S0243 L14–16, L46, L68; §8.40 C6 | Add to C6: "forbidden, not impossible. The boundary is an obligation on implementations, prevented or detected (§9.16 R3a), and not shown." | Added |
| W-04 | **C2's premise ("None requires an input to be accepted or a question to be answered") is false as worded, and §8.43 L30 still reads "every law is a prohibition".** Art 10.2 says "'Who knows?' SHALL always be answerable", Art 9.1 makes UNKNOWN a first-class state, and Art 4.2 says "SHALL inform action". All three are met only vacuously, or by constant U, and C2's trivial system omits "answers U". This is R3 residue of C49 W-06. | normal | S0241 L60, L98, L106–107; §8.42 C1 (restated) | "Universal or conditional; none requires that anything exist; the answer clauses are met by a constant-U answerer (Q1)." Plain-language item 2 is to be restated the same way. | C2 and L30 restated |
| W-05 | **C2 Src and plain-language item 2 present S0244 L15 as "the HPA's assessment".** L15 is the record's own sentence ("Its verdict (recorded): …"). Only L11 and L23 are quoted. This is the C48 W-04 / C49 W-04 pattern. C4 handles the same line correctly. | normal | S0244 L11, L15, L23 | "S0244's record of the assessment (the recorder's sentence, L15)". | Restated |
| W-06 | **C1 has three faults.** (a) "The one-way rule is the product's, not the commission's" holds only for "no article SHALL be weakened". "Do not add concepts" (S0242 L15) and "the corpus is closed; adding knowledge would violate the discipline" (L69) are prescribed, nearly word for word in V.3. (b) Missing: the final rule (S0241 L13, "the strongest statement never exceeds the evidence") and V.2 conflict whenever an article is found to overstate its evidence. "Correct an internal inconsistency" then applies, but V.2 forbids the correction. (c) "Weaken" is undefined: it needs an order, for example A stronger than B iff A ⊢ B. A correction can also be incomparable, neither stronger nor weaker. | normal | S0241 L13, L165, L171; S0242 L15, L65, L69 | Restate the Int with (a)–(c). C1's conclusion stands, more strongly. R3: §8.44 L21 cites §8.43 C1. | C1 restated |
| W-07 | **C5, identity: the ground for "no conflict" is wrong.** Art 1.3 forbids identity "including perfect observable match". The book's entry identity, derived from kind and required fields (§9.16 R2, L93), is identity by a full match of fields, so "not from similarity" does not separate them. The real reason is that the objects differ: Art 1 is about knowledge (item) identity, which is open in the book (§8.39 C3 (b); T9-14), while R2 is about entry identity. §8.39's draft made the same comparison and it was corrected (C46 W-01). | normal | S0241 L35; §9.16 L93; §8.39 L73, L132 | "No conflict: different objects. Item identity is open; how Art 1.3 bears on content-derived identity is to be checked once item identity is decided." R3: §8.44 L22. | C5 restated |
| W-08 | **Not carried: identity is assigned only on VALIDATED (S0243 L141).** REJECTED (Art 7), HELD, and challenged claims (Art 8.2, "challenge SHALL NOT destroy identity") then have none. This contradicts S0241 Art 1.1 and Art 8.2 inside the source. In the book, every entry has identity when it is recorded, whatever its standing (§9.16 R2). | normal | S0243 L141–147; S0241 L33, L91 | Add to C3 or C5. | Added |
| W-09 | **§8.43.3 says Art 4 and Art 5 are "already treated in §8.42 C5… and §8.35 C2". §8.42 C5 has neither.** The content is missing, not just the pointer stale. Art 4's home is §8.35 C2 (knowledge-to-action not modelled) and §8.36 C1 (Q1 at the action's cut). Art 5's home is CON-0141 (non-consultation), not CON-0134 (INVALID). Art 1's Relation-001 (S0241 L39) is "required, not admitted" (CON-0188), as the C49 W-03 row directed. | normal | §8.42 C5 (law rows 1, 2, 3, 7–11 only); §8.35 L55; §8.42 L101 | Correct the pointers and add the Relation-001 line. | §8.43.3 restated |
| W-10 | **C6 misses three points.** (a) "Reasoning Engine(s)" is also not on the Ch III engine list, where "reasoning pipelines" is a mechanism. (b) S0243 L122 calls the verdict vocabulary, including REJECTED and CONFLICTED, "not a kernel law", while S0241 Art 7.1 and 8.1 make both constitutional states. This extends §8.42 C6's "REJECTED" homonym to CONFLICTED. (c) S0245's "reasoning pattern representation" (L64) is not on the lists either. | normal | S0241 L128–131; S0243 L101, L122; S0245 L64 | Add to C6. | Added |
| W-11 | **Homonym "admit":** a gate admits a claim (§8.43 L1, L56), while D admits a kind ("required, not admitted", L83). | normal | R2 run, tier D "admit" | Qualify as "admit (gate)" vs "admitted (D)". | Qualified |

## Claim verdicts

| Claim | Verdict | Reason and scope |
|---|---|---|
| C1: the amendment rule is one-way; "inconsistency" does not cover error | VALIDATED | on the text of S0241 V.2–V.3 |
| C1: "not the commission's" | INVALID as stated | closure half prescribed (W-06) |
| C2: for S0241 | VALIDATED | premise restated (W-04) |
| C2: for S0243 | INVALID as stated | §6 recording duty (W-02) |
| C3: admission without a recorded act | VALIDATED | S0243 names no act |
| C3: "the guard reading (ii)" | INVALID as stated | W-01 |
| C3: conflict with Art 3.2 / IV.3 | PROVISIONAL | rival L80 (W-01) |
| C3: UNKNOWN not a branch | VALIDATED | S0243 L90 vs L141–147 |
| C4 | VALIDATED | three senses of "ratify"; act not visible |
| C5: Art 3.2, IV.3, Art 8 per axis, Art 9.2, Art 10, Art 11, supersession | VALIDATED | with the stated qualifications |
| C5: identity | conclusion VALIDATED, ground INVALID | W-07 |
| C6 | VALIDATED, incomplete | W-03, W-10 |

**Proof-step tables**
- **C2.**
  - Step 1: source reading; omits the answer clauses (W-04).
  - Step 2: holds for the §3 table only; premise missing for §6 (W-02).
  - Step 3: §9.10 F-vac, defined.
  - Judgement: inadequate for S0243 (premise missing).
- **C3.**
  - Step 1: (ii) by §8.42 C2 requires an act; S0243 has none, so the step is unjustified.
  - Step 2: A1 and decision C1 are defined; "service, not grant holder" conflicts with §8.36 C1.
  - Step 3: depends on an unstated premise, "admission = standing".
  - Judgement: inadequate (premise missing).

**Recorded challenges**
- C1: adequate in direction. It rests on Rulebook L33 and §12b, which I could not open (Limits). The stronger rival is W-06 (b).
- C3: inadequate; L80 is not answered.
- C4: adequate.
- C2, C5, C6: no challenge recorded. The strongest rival to C5 is Art 1.3's perfect-match clause (W-07).

## Coverage against my inventory

| Items | State |
|---|---|
| S0241 1–4, 7–12, 15; S0242 1, 2, 4, 7, 8; S0243 2, 4–6, 8–10, 11 (b), 13, 14; S0244 1, 3, 4, 6–10; S0245 1, 2, 5, 6, 8, 11, 12; lineage | COVERED |
| S0242 6 (closure prescribed); S0243 3 (ground); S0243 11 (c) (reading) | DISTORTED (W-06, W-07, W-01) |
| S0243 1; S0243 11 (a); S0241 5, 6 and Relation-001; S0243 7 (Reasoning Engine), 12 (state model vs Art 7/8); S0245 13 | MISSING (W-03 high; W-08, W-09, W-10 normal) |
| S0241 13 (two character lists: a second occurrence of the "authority" addition noted at C49); S0241 14 (one row in two articles, already in §8.42 C6); S0245 4 (six-item paraphrase); S0244 3 (recorded in §8.43.3) | JUSTIFIABLY EXCLUDED (low; nothing relies on them) |
| S0241 L29 repeats "independently failure-tested" (§8.42 C6: INVALID at clause level); S0242 5, 9; S0244 5; S0245 7, 9 ("Candidate Mechanism Source"), 10 | MISSING, low |

New fork: none. New topic: none. The homonyms are "admit" (W-11) and CONFLICTED (W-10).

## Changed theory (`83d42209a`)
All C49 settlement rows match the diff:
- W-01 to W-13, the index row and the change log row;
- the R3 changes at §8.43 L22, L59, L81 (formerly L100) and L53.

Two gaps:
- (i) The W-03 row lists §8.43.3 as applied, but only "Art 7: no home" reached it. Law 1's relations did not (W-09).
- (ii) W-06's restatement was carried to C2 L53 but not to plain-language item 2 (L30) (W-04).

## Next steps (in dependency order)
1. W-01, then W-08: C3, then R3 on §8.44 L22.
2. W-02, W-04, W-05: C2 and L30.
3. W-07: C5 identity (R3: §8.44 L22).
4. W-06: C1 (R3: §8.44 L21).
5. W-03, W-10: C6.
6. W-09, W-11: §8.43.3 and the qualifiers.

## Limits
- **Not opened:**
  - Rulebook v1.4 L33 and §12b, because they sit under 03-methodology and the brief bars that beyond REVIEWER-INSTRUCTIONS. C1's "two-way review" is checked only through register withdrawals (CON-0009, CON-0134 INVALID; CON-0099). Those are withdrawals of concepts, not kernel laws weakened.
  - §8.44: I used only the lines inbound_citations printed.
- **Not reopened:** the five sources; I used this session's full step-1 reads.
- **Not run:** ce_model and proof_step_check. W-02 and W-06 (b) are by-hand readings, not model runs.
- **S0217 (B):** seen only through §8.35 L43. Its "MUST provide a governed path for revision" is absent from S0241 Art 11. Whether H-KOS-Revisability-001 and S0241's INV-KOS-Revisability-001 are the same row is not visible (an appendix note to C1).
- **R1:** 241 homes, 1 flag (CON-0133, pre-existing).
- **R2** (--scope 08.43): tier A, 0 terms. I judged each of the 22 tier-D terms. All are ordinary or used in the book's sense, except "admit" (W-11), "weaken/weakened/strengthen" (an order is needed, W-06 (c)) and "govern" (already reported in C6).
- **Process slip:** two `--help` calls to undefined_terms.py and inbound_citations.py ran without `--root`. Their default root resolves to W itself, so the effect is nil. Every later call carried `--root`. register_home_check.py takes no `--root`.

## Files opened
- W/06-theory/manuscript/review/C50-S0241-S0245/: BRIEF-W-STEP2.md, STEP1-INVENTORY.md, SHA256-STEP1.txt, STEP8-AND-CHECKS.md.
- W/06-theory/manuscript/review/C49-S0237-S0240/: SETTLEMENT.md, REVIEW-REPORT.md.
- W/06-theory/manuscript/chapter_08/08.43-an-amendment-that-may-only-strengthen-and-a-gate-that-admits-without-a-recorded-act.md (whole).
- The output of `git show 83d42209a -- 06-theory/manuscript/chapter_08/`, saved by the harness at /home/nab-raj.roshyara@dg-nexolution.de/.claude/projects/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/tool-results/bjxqirapa.txt.
- By grep or sed only:
  - 01.06 (A1);
  - 09.16 (R2, L93);
  - chapter_09/TODO.md (T9-18);
  - 08.35 (L43, C2);
  - 08.36 L38–60;
  - 08.37 C1/C2 headings and L49–60;
  - 08.39 C3;
  - 08.40 C6;
  - 08.42 (projection grep);
  - THEORY-MAP ("decision C1");
  - CONCEPT-REGISTER rows CON-0009, 0099, 0134, 0188.
- R2 output: /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/r2.txt
- No other corpus file was opened.

## Commands run
- `ls` of the C49 and C50 folders, tmp/tools, manuscript, chapter_09 and chapter_01; `git log --oneline -5`; `wc -l`; `cat` of SHA256-STEP1.txt and STEP8-AND-CHECKS.md; `sha256sum` of STEP1-INVENTORY.md.
- `cat SETTLEMENT.md`; `git show --stat 83d42209a`; `git show 83d42209a -- 06-theory/manuscript/chapter_08/`.
- `awk` on CONCEPT-REGISTER.tsv; the greps and seds listed above (chapter folders, the map, the register only).
- `grep` over the three tool scripts for root and scope options.
- `python3 -I tmp/tools/{register_home_check,undefined_terms,inbound_citations}.py --help` (see the process slip).
- `python3 -I tmp/tools/undefined_terms.py --root W --scope 08.43`.
- `python3 -I tmp/tools/inbound_citations.py --root W` for "§8.43 C1" to "§8.43 C6" and "§8.43.3".
- `python3 -I tmp/tools/register_home_check.py`.
- Nothing in the repository was edited, created or committed. Nothing was sent to an outside service.
