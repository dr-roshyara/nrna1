C103 REVIEW REPORT

Window C103, §8.96 (draft) with its index row, STEP8-AND-CHECKS, and the R3 note on §8.95's lineage. Reviewer W. Transition review, steps 2–3. I re-read my saved step-1 inventory first. Independence here is process independence, not personal independence.

## 1. Assessment
- §8.96 is reliable within D for C4 and C5, and for the conclusion of C1.
- The largest risks:
  - **(i)** The word "admission" is used in one sense in C2 and in invariant 5 of C3, but in another sense in C6 and in §8.95 C4. As a result, the body of §8.96, its plain language and its index row give three different verdicts on invariant 5.
  - **(ii)** C6 answers a different Zero case from the one (B) asks.
  - **(iii)** C1 and C4 leave out the consistency boundaries the book has already decided (§8.91 C1; CON-0167, VALIDATED), which are the strongest answer to the aggregate-root question.

## 2. Findings (ranked)

| W-nn | Severity | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|
| W-01 | high | C3, invariant 5; C2 "at admission" | §8.96 L54 says invariant 5 has "no counterpart" (sense (c)). Plain language L26 and the index row (08.00 L112) say it *conflicts*. C6 (L79) reads (A)'s contract as sense (a), accepting an entry. §8.95 C4 splits admission into senses (a), (b) and (c), and THEORY-MAP L270 records that split. Under sense (a), "admission only if contract satisfied" agrees with the book: D's clauses, the guards and G8 at append, otherwise refusal (§9.18 L179). | Split invariant 5 by sense: (a) agreement; (b) and (c) no sufficiency gate. Split C2 the same way. Under (a) the kernel assigns no item identifier: an entry names its item, and registration is not admitted (§9.13 L52), so for (a) there is no counterpart. Under (b) the conflict stands (§8.92 C4). Align L26 and the index row with the body. | §8.96 L26, L43, L54; 08.00 L112 |
| W-02 | high | C6, (B)'s Zero question | (B)'s case: identity present; evidence reference, justification, confidence and state absent (S0372 L813–817). §8.96 answers a different case: "an item with a governed identifier **and entries** but no adoption" (L80). Q1's scope is "every k known to the record at s" (§9.10 L35), and registration is not admitted (§9.13 L52). An identifier with no entry is therefore outside Q1's and Q2's scope, and cannot be told from no claim (§8.91 C6, first Ded). Apart from adoptions, the entries about an item are evidence entries, which (B)'s case excludes. | Within D, (B)'s case has no representation; it collapses into "no claim", as a refusal does. The item without standing covers a different case: one with evidence entries and no adoption. Restate the challenge answer at L96–97 to match. | §8.96 L29, L80, L96–97; 08.00 L112 |
| W-03 | high (omission) | C1 aggregate root; C4 per-identity boundary | C1 rejects the aggregate root as "an act, not a container", which is the *owner* question. Whether KnowledgeClaim is a consistency boundary is a separate question, and the book has already decided it: one serialization point per grant and per held act (K4), and the designation guard in the same kernel step (K6; §8.91 C1). None of these is per claim. CON-0167 (VALIDATED) holds that "a totally derived read rule cannot justify a state boundary". The citation of §9.20 Th2 for "the item is not a component of the state" is wrong: Th2 refutes per-item value vectors V (§9.20 L85–86). | Add §8.91 C1, K4, K6 and CON-0167 to C1 and C4. Keep "owner" and "aggregate root" as two questions. For C4, argue that the guards read across items (§9.20 Th1 step 6), so a per-identity stream cannot be the boundary. Replace the Th2 citation with Th2's actual content. | §8.96 L36, L64, L95 |
| W-04 | normal | C3, invariant owners; C6 L163 | Invariant 1 is K1, an *assumption*, "not refutable from the record alone" (§9.15 L74); the kernel does not enforce it. Item identity is "**Open** for items" (§9.20.7 L138), and no kind of D enforces I-0. | Invariant 1: agreement in content; held as assumption K1, not as a check. Invariant 2 for items: agreement with I-0 as a premise, owner open (T9-14). In C6, limit "the kernel checks and evaluates by them" to D's clauses. | §8.96 L50–51, L79 |
| W-05 | normal | Lineage; R3 on §8.95 | S0371 also restates (B): S0371 L773 ≈ S0372 L588 (no other match among S0366, S0367, S0369, S0370); in meaning, L836 ≈ L744 and the scenario-based round ≈ L1087. S0371 therefore summarizes the whole of S0372. An 11-second gap between file saves shows the order of saving, not of writing. (B) also takes from S0370: L350 (S0370 L69, L464), L352 (paraphrase of S0370 L475), and L293 explicitly names "the previous one", S0370's falsification. | Add these to the §8.96 lineage and to the R3 on §8.95's list of adviser restatements. Correct THEORY-MAP L270 ("states the adviser's own formulations"), which the R3 makes stale. | §8.95 L5; THEORY-MAP L270; §8.96 L9 |
| W-06 | normal | C5 scope | Plain language L28: "exactly what the book records". An evid entry relates a producer's claim to an item; E1 has no field (§9.18 L110), as C5 itself says (L72). Since 2026-10-31, "D" names two domains: D_S for standing and D_W for evidence (§9.18 L107). (B)'s many-to-many test is S0369 (D)'s Scenario G again (§8.93 C5), from the same adviser. | Say "the book records the item end of the link; E1 is outside". Qualify D_S/D_W in C5. Cite §8.93 C5 and count the test as one line. | §8.96 L28, L72 |
| W-07 | normal | §8.96.3, open question 7 | "entries are not deleted (K1)" is wrong. An authorized recorded removal tombstones an entry (§9.13 removal; §9.20 L96; §8.92 L43). | Say "removed only by a recorded act, by tombstone". | §8.96 L88 |
| W-08 | normal (omission) | C3, trivial case | All ten invariants are prohibitions. A kernel that refuses everything satisfies all of them: 3 and 5 hold vacuously, and 10 holds by refusing. §8.92 C1 and §8.94 C5 ran this test on the same line of sources. | Add the trivial case. | §8.96 L47–58 |
| W-09 | normal | C6 L163, its ground | (A) grounds the postulates in the [IT] lens. §8.92 C6 and §8.51 C1 show that the theorem is not a proof here. | Agreement in conclusion only; the book's ground is K1–K7 and the decided rules. | §8.96 L79 |
| W-10 | normal | Invariant 10, the fork observation | This is a second instance of the observation in §8.91 C6 that refusals are not recordable (bearing on T0-07 and G8). | Record it as a second occurrence. Whether to open the fork is the Principal's decision. | §8.96 L57 |

## 3. Verdicts
- **C1: PROVISIONAL.** The conclusion holds within D for the owner reading. The aggregate-root part rests on a miscited Th2 and does not use the boundary argument (W-03).
- **C2: PROVISIONAL.** The admission homonym is not split (W-01).
- **C3: PROVISIONAL.** Invariant 5 gets inconsistent verdicts, and the owners of invariants 1 and 2 are overstated (W-01, W-04, W-08).
- **C4: VALIDATED** within D, under H2 and D's citation rule (formulation choices, Th4). The finding "no evidence-grounding invariant for standing" holds. The challenge answer is adequate for the state and inadequate for the boundary (W-03).
- **C5: VALIDATED** within D_W and D_S, for the lifecycle of the link (evid, ewdr, erst, annulment; sink property (c1)–(c2), where (c3) is Int). The plain language overstates it (W-06).
- **C6:**
  - postulates: VALIDATED in conclusion (W-09);
  - candidate: VALIDATED;
  - Zero answer: **INVALID as stated** (W-02). The challenge answer is inadequate.
- **R3 on §8.95's lineage: VALIDATED** for what it lists: S0372 L42, L46, L99, L234 and L273 match S0371 L216–217, L375–382, L626 and L664, and they are unique among the allowed sources I checked. It is incomplete (W-05).

**Proof step, C3 invariant 10 (Ded):**

| Step | Content | Justification |
|---|---|---|
| 1 | A refusal leaves ρ unchanged | §9.20 Th1 step 7 |
| 2 | G8's reasons go to the caller and are not appended | §9.18 G8 |
| 3 | Equal records give equal answers | Th1 step 8 |

Every object used is defined. Adequate: this is §8.91 C6's derivation, restated.

## 4. Coverage against my inventory
- **Covered:** A.1, A.3, A.5, A.6, A.9, A.10, A.13, A.14; B.1, B.2, B.5, B.7, B.8, B.13, B.14.
- **Distorted:** B.12 (W-02).
- **Missing:**
  - the invariant owners (W-04);
  - the trivial case (W-08);
  - S0371's restatement of (B) (W-05);
  - from the appendix: A.8 L95, B.15, A.11, A.7, A.16.
- **Justifiably excluded:** B.3, B.4, B.6, B.9, B.16 (directions with no results).

No new concept. No new fork; one second occurrence (W-10).

## 5. Next steps
1. Fix W-01 and W-02 in the body, the plain language and the index row. Done when L26, L54, L80, L96 and 08.00 L112 agree.
2. Add W-03's homes to C1 and C4.
3. Apply W-05 to §8.95 L5 and THEORY-MAP L270.
4. Apply W-04 and W-06 to W-10.

## 6. Limits and checks
- **R2** (`undefined_terms.py --root <scratch> --no-corpus --scope 08.96`): tier A 0 and tiers B–C none in scope. There are 29 tier-D words; I judged each one:
  - ordinary English, or the source's own labels in quotes (for example "evidence-grounding", "sufficiency-at-admission", "representation-agnosticism", "immutability", "many-to-many", "duty", "unenforceable", "minimum", "sourcing"): none needs a definition;
  - "admission-sufficiency" is the book's term (§8.90 C4; §8.95 C4 (c)).

  Defined terms are outside R2's reach; the homonym that matters, "admission", is W-01.
- **Quote check by hand** (my script `c103-w-qc.py`): 28 of 28 S0372 quotes with line references in the "S0372" bullets match. I checked the lineage lines in S0371, S0370 and S0367 L36 directly; all match.
- **R3** (`inbound_citations.py`): the §8.96 claims are cited only from §8.95 L5. "§8.95 lineage" has no tool hits; its citing lines were found by reading (THEORY-MAP L270). §8.91 C6 has 18 citing lines; none needs to change.
- S0368 was not checked (not allowed), so the uniqueness of the lineage matches holds only among the allowed sources.
- **Access breach, mine:** one grep used the glob `20260823-1*.md` in `docs/knowledgeos/brainstorming/kernel/`. It matched two files that are not allowed: `20260823-112155-perplexity-kernel-domain-responsibility-brainstorming.md` and `20260823-113645-deepseek-consistency-boundary-domain-discovery.md`. Four matching lines from them were displayed to me. I did not rely on them.

## Appendix (lower priority)
- **A.8 / L95:** "retracted because false vs superseded" has a counterpart in the book: a refuting entry against annulment and withdrawal (§9.18 L115–121; CON-0214).
- **B.15 / L962:** answerable within D (an authorized recorded act; K4/K6; §8.89 C4), not only a method direction.
- **Source defects:**
  - L176 (irreversible) against L136;
  - the second row of the L249 table;
  - L85 against L199 and L16 against L18 (classification hardens without argument);
  - L842 misquotes L8.
- **C2:** S0369 L1479 also reads identity as a relation (svarūpa-sambandha).
- **B scenarios 18–19:** content-derived identity makes them idempotent (§9.18 L91, O2).
- §8.91 C6 cites §9.10 L37 for the Q1 scope; the scope is at L35 (a stale line reference outside this window).

## Files opened (read, sed or awk extract)
- `.../C103-S0372/BRIEF-W-STEP2.md`, `STEP1-INVENTORY.md`, `COPY-LIST-STEP2.txt`, `STEP8-AND-CHECKS.md`
- `.../chapter_08/08.96-...md` (whole); `08.00-index.md` (grep for the row)
- Extracts:
  - `08.95` L1–80;
  - `08.92` L29–83;
  - `08.90` C1; `08.91` C1, C4, C6; `08.93` C1, C2, C5; `08.87` C2, C4; `08.94` C5, C6;
  - `09.20` L60–119, L150–158;
  - `09.18` L107–122, L179, L213–232;
  - `09.15` L63–80; `09.13` L46–56, L129; `01.10` L35; `09.16` L93; `09.10` L35, L37, L46.
- Searched (grep):
  - `08.89`;
  - `CONCEPT-REGISTER.tsv`;
  - `THEORY-MAP.md` (also read L7 and L268–272).
- Corpus:
  - S0371: L135–141, 150–155, 212–219, 372–384, 622–628, 660–666, plus greps;
  - S0370: L1–12, 464–536, plus greps;
  - S0366, S0367, S0369, S0372: greps only;
  - `CORPUS-ALLOWED.tsv`: grep for the six paths.
- Scratch copies of `undefined_terms.py` and `inbound_citations.py` (grep and sed only).

## Copied
Each file in `COPY-LIST-STEP2.txt` (257 files), by its own path, with the brief's `while read` loop, into `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/w-c103/root/`. Nothing else was copied.

## Commands
- `wc`, `grep` and `sed`/`awk` on the files above;
- the copy loop;
- `grep -F` of the S0366–S0372 paths in `CORPUS-ALLOWED.tsv`;
- phrase greps over S0366, S0367, S0369, S0370, S0371 and S0372 by explicit filename, plus the one glob grep reported above;
- `python3 -I undefined_terms.py --help` (failed, then not reused);
- `python3 -I undefined_terms.py --root <scratch>/…/knowlegeos_manual_theory_20261018 --no-corpus --scope 08.96` (output in `c103-w-r2.txt`);
- `python3 -I inbound_citations.py --root <same> "§8.96 C1" … "§8.96 C6" "§8.96 lineage" "§8.95 lineage" "§8.91 C6"` (output in `c103-w-r3.txt`);
- `python3 -I c103-w-qc.py <§8.96> <scratch>/c103-w-S0372.txt`.

All scratch files are in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/w-c103/` (the `.txt` outputs were written one level above `root/`).

No git commands were run, and nothing in the repository was edited.
