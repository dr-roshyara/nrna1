C95 REVIEW REPORT

Window C95: §8.88 (draft), against S0364. Reviewer W, transition review, step 2. The review is independent in process, not in person. I re-read the saved inventory before starting: its sha256 `6c82c14e…9f6c` matches SHA256-STEP1.txt.

## Summary (at most 10 lines)
1. C3 and C4 hold within their scope. Every source quote and line reference I checked by hand matches. No proposed R3 is needed for other sections, and none exists.
2. Three things go wrong.
   - **C1** says the book has no state that moves. The book does have lifecycle state: §1.2 L19, §1.14, F6 and T0-07, the last two open. What the book separates is lifecycle from standing.
   - **C2 (iii)** maps RECONCILED, a relation between two claims, onto g1's "contested", which is the evidence balance on one item.
   - **C5** says (B)'s supersession question (L948) "is answered in the book". It is not: supersession is not admitted to D (§9.13 L52), CON-0175's fork is open, and T9-14 (item and version) is open.
3. The draft leaves out the book's strongest result for this source: a single lifecycle forces either evidence by decree or decisions that wait on evidence (§1.6 L46). It also misses two homes:
   - the dispute flag (§8.14 C4, CON-0211);
   - "Rejected is an outcome, not a state" (§1.6 L51, T0-07).
4. Three places present an open fork as settled (Q7): C1 (F6, T0-07), C2 (i) (G7), C5 (CON-0175, T9-14).

## Findings
Severity order. No finding is critical: §8.88 introduces no concept and nothing else builds on it yet.

| W-nn | Severity | Claim | Evidence | Correction | R3 lines |
|---|---|---|---|---|---|
| W-01 | high (defect) | C1 and plain-language item 1: "In the book a claim has no stored state that moves"; "(A)'s machine has no counterpart in D" | **True only inside D.** D admits no lifecycle kinds, and supersession and suspension are not admitted (§9.13 L52). **The book does have lifecycles:** "no lifecycle may contain a direct edge from *created* to *authoritative*", an edge that is always act-labelled (§1.2 L19). It has "state (V3's lifecycle state) is not authority" (§1.14 title; §8.32 C2), the governance-act progression moved by acts (CON-0019, VALIDATED, §1.6 L41), and F6 "standing and lifecycle split apart?" (THEORY-MAP L128, open; Ch. 3 §3.1 L55, L79) | Restate C1 in two parts. (a) Within D, no admitted kind is a transition of a claim's epistemic state; the displayed class is read at a cut (§9.18.2). (b) Outside D, the book separates lifecycle from standing (§1.2, §1.14) and holds F6 and T0-07 open. (A)'s machine merges the two. Restrict plain-language item 1 to standing | 08.88 L19, L31; 08.00-index L104; also §8.87 C4 (08.88 L3, L11, L31; 08.99 L125; THEORY-MAP L262), for the consolidation, not this window |
| W-02 | high (omission) | C1: what the book actually says against one lifecycle machine | §1.6 L46 (S0018 L121): "A single lifecycle would force one engine onto all four, 'requiring either that evidence be grantable by decree, or that decisions wait on evidence that may never come'". S0364's single machine mixes transitions triggered by evidence (L135–136, L131) with acts (L129 withdrawal; L125 admission) | Add this as C1's main home. *The defect in (A)'s machine is that it puts evidential changes and act changes into one engine,* not that it has states | 08.88 L11, L31 |
| W-03 | high (defect) | C1 rival answer (§8.88.4): triggers and preconditions show a stored machine | In the book, acts have preconditions too: conditions on a valid act, scope and time (§1.6 L31). The source also says "Every transition must be recorded in history" (L177), and (B) says "derive state from events" (L886). So preconditions do not separate a stored state from a function of the record | Judge the rival as surviving and the answer inadequate. Keep C1 restricted to D (W-01) | 08.88 L75–76 |
| W-04 | high (defect) | C2 (iii): RECONCILED, read as a relationship (L720), maps to the book's "contested" | (1) g1's "contested" is support and refutation of **one item** for a purpose (§9.18.2 table). (2) RECONCILED is the **resolution** between **two claims**. (3) §8.84 C3 (W-10) already found that a comparison of two claims is "a different object" from g1. (4) The book has no kind that relates two claims, and item and version are open (T9-14; §9.18 G3, G10) | Withdraw (iii). In the book (B)'s relation reading has no counterpart; it is open under T9-14. Restate "the book has the answer for these three" as "for two" | 08.88 L38; index L104 ("agreement") |
| W-05 | high (defect) | C2 (i): INSUFFICIENT_EVIDENCE "corresponds" to a grade from the evidence | (A) defines it as "some evidence but not enough" (L65), a threshold. g1 has no threshold: one surely-live support gives "supported" (§9.18.2). Only the route "invalidated or removed" (L136) leads to "not attested", through annulment or withdrawal. The mapping of g1's vocabulary is open (§9.18 G7), and §8.87 C4 (settled) already says "the mapping … to g1 is Int and is not in the book" | Label it Int. The agreement is with (B)'s reading (L695): no change of standing. It does not extend to a correspondence of classes. State that G7 is open | 08.88 L20, L38 |
| W-06 | high (omission) | C2: CONTESTED as a state | The book's home is §8.14 C4 and CON-0211 (PROVISIONAL): "DISPUTED is a FLAG over the lifecycle state, not a terminal state". As a mark it changes no value. As a suspension it changes standing, and the raiser's authority must be evaluated. (A)'s CONTESTED is entered by a challenge "raised" (L127, L207), which is a dispute, not g1's evidence balance | Add CON-0211 as the home for CONTESTED. Name the homonym: "contested" (g1 class), "contested" (S0364 state) and "disputed" (flag) | 08.88 L12, L38 |
| W-07 | high (defect; Q7) | C5: "(B)'s question (L948) is answered in the book in its second form: a further act, with the earlier entries kept" | Supersession is "Not admitted" to D (§9.13 L52). CON-0175 (PROVISIONAL) says supersession is recognized only when recorded, and "whether the record constitutes it stays the open fork of §1.13.1-2". Version and identity are T9-14, open. §1.6 L41 places supersession in the governance-act progression only | Restate: the book holds supersession to be a further act that is recognized only when recorded (A1; CON-0175). Its kind, its version and its identity are open. (B)'s question is open in the book, not answered | 08.88 L23, L60 |
| W-08 | high (defect, homonym) | C5: (A)'s withdrawal is mapped to §9.18.4's withdrawal of reliance | (A): "Claimant voluntarily retracts" a claim, with "Claimant authorized" (L45, L129, L227). §9.18.4: ewdr withdraws reliance on an **evidence entry** for one **purpose**. Under T9-16 (b) a grant holder does it, and "the producer has no special right" (§9.18 L79). The object, the actor and the scope all differ | Name the homonym "withdrawal (claimant's retraction)" versus "withdrawal of reliance (ewdr)". A claimant's retraction has no admitted kind in D. Its nearest homes are CON-0214 (a) and (b) and S0888's "retraction does not delete" (§9.18.4 L118) | 08.88 L23, L61 |
| W-09 | normal (defect) | Plain-language item 5 and C5: "nothing leaves the record" | §9.18.7: removal by tombstone "drops content and keeps every required field", through an authorized act. §9.15 G3: "removed only by a recorded act, by tombstone" | Restate as "no entry leaves without trace; content leaves only by an authorized removal, with a tombstone" | 08.88 L23, L61 |
| W-10 | normal (defect) | C5: "(A)'s threshold makes evidence grant" (L131) | (A)'s evidence table reads SUPERSEDED as "no longer the best justified" (L67), an evidential status, which §1.6 L46 allows evidence to move. L128 requires that the new claim "passes constitutional evaluation and has stronger evidence", which is the two-key form | Scope the finding to L43 and L131, where standing is replaced with no act. Note L128 as agreeing with the two keys | 08.88 L60 |
| W-11 | normal (omission) | C4: "adjudication is the evaluation of the act" | The book has two adjudications. One is the append guard, before the append, for single-use uses, transfers, designation, removal and W entries (G8). The other is Φ's evaluation over the record, after the append. For most kinds the book's order is record, then evaluate, the reverse of (B)'s order: adjudicate, apply, record (L832–842) | State both senses and the order | 08.88 L22, L52 |
| W-12 | normal (defect, home) | C4: "every entry passes the kernel's append rules (§9.15 K1, the exclusive write path)" | K1 is an assumption about the write path, not an evaluation of each entry. Checks at append exist only for the guarded kinds (§9.13 L46–48; G8 for W). The sentence "holds for appends and not for standing acts in particular" is unclear | Re-home to D's clauses, the guards and G8. Restate: (A)'s L347 holds if "constitutional evaluation" means "passes D's and the guards' tests". It fails if it means "is a standing act evaluated by Φ", because evidence entries change classes (§9.18.4) | 08.88 L53 |
| W-13 | normal (omission) | C4 on (A)'s premise L347 | (A) contradicts itself: WITHDRAWN is "MAYBE" (L336) next to "All transitions … require" (L347) | Add it as a source defect, since it supports (B)'s doubt | 08.88 L53, L67 |
| W-14 | normal (defect) | C3 and plain-language item 3: "the book closes [the gap] by doing both in one step" | §8.86 C4 (settled) gives the scope "(a single-use grant, a designation)". K6 is an assumption, met by testing. Across applications only K4 holds, and K1 records "writes between a check and an act … not covered". The transitions S0364 lists have no admitted kind (§9.13 L52) | Restore §8.86 C4's scope and the conditions K6, K4 and K1 | 08.88 L21, L45 |
| W-15 | normal (source defect, mis-stated) | §8.88.3: "RECONCILED is 'stable' (L161) and reached from SUPERSEDED (L158)" | Being both stable and reachable is no defect. The contradiction is L81 "RECONCILED ←→ SUPERSEDED" against L156–161, where RECONCILED has no exit. L82 places WITHDRAWN after RECONCILED, but only ADMITTED→WITHDRAWN exists (L129) | Correct the defect statement | 08.88 L67 |
| W-16 | normal (omission) | Trivial-case and "must-do" check on (A)'s machine | Absorbing and stuck states: RECONCILED has no exit, so it cannot be contested again. INSUFFICIENT_EVIDENCE exits only to ADMITTED. CONTESTED has no withdrawal path. The machine is never total over its own triggers | Add as a coverage note, not a claim | 08.88 §8.88.3 |

Unranked omissions, normal:
- (A)'s REJECTED "never enters KnowledgeOS" (L199), yet it is a state of the machine. Its home is §1.6 L51 (S0013, "Rejected is an outcome, not a state"; T0-07). Add it to C2, since (B) asks the same question (L1009).
- History recording moves from the Kernel (L483) to the aggregate (L759) without comment.
- (A) settles admission as "The Resolution" (L376), and (B) downgrades it to PROMISING (L1064).
- The event `KnowledgeCreated` is renamed `KnowledgeAdmitted` (L193). §8.87 C4 used KnowledgeCreated.

## Verdicts per claim
| Claim | Verdict | Reason, scope |
|---|---|---|
| C1 | PROVISIONAL | Right within D. Overstated for the book as a whole (W-01). Its main home is missing (W-02) and its rival answer is inadequate (W-03) |
| C2 | (i) PROVISIONAL; (ii) VALIDATED; (iii) INVALID as stated | (i) G7 is open and S0364 uses a threshold (W-05). (ii) "Derive state from events" agrees with C1 = constitute. (iii) Wrong object (W-04). The dispute home is missing (W-06) |
| C3 | VALIDATED within §8.86 C4's scope | It repeats a settled result. The scope and conditions must be restored (W-14). The "not pure" finding stands (L481–483, L573) |
| C4 | Application = recording: VALIDATED under C1 = constitute. Treatment of L347: PROVISIONAL | Wrong home and unclear wording (W-12). Two senses of adjudication (W-11) |
| C5 | Supersession: PROVISIONAL, with "(B)'s question answered" INVALID. Withdrawal: PROVISIONAL | W-07 (open forks), W-10 (scope), W-08 (homonym), W-09 (tombstone) |

## Coverage against the step-1 inventory
| Status | Items |
|---|---|
| COVERED | 1 (central claim), 3 (definition), 4 (the three "only"s against re-admission), 9 (scenarios A–D), 10 (not pure), 11 (B's classes), 12 (five responsibilities), 15 (no enum), 17 (K-02), 20 (F-1…F-5 reported, not shown) |
| DISTORTED | 7 (wrong defect stated, W-15); 14 (the L948 question claimed as answered, W-07) |
| MISSING | 2 (downgrade from resolved to PROMISING); 6 (absorbing and stuck states, W-16); 8 (L336 against L347, W-13); 13 (history owner moved); 18 (REJECTED as an outcome; the rename; the logic of SUPERSEDED→ADMITTED; confidence absent from the split); 19 (empty citation markers; a source defect the theory does not rely on) |
| JUSTIFIABLY EXCLUDED | 16 (the ten questions; confidence is homed at §8.87 C4); the dropped states of item 5 (low value) |

**Losses of theory:** items 13 and 18, with REJECTED tied to T0-07, and the omitted §1.6 L46 and CON-0211 homes (W-02, W-06). Each one changes what the book can say against S0364.

**New topic or fork:** none outside the map. S0364 is further evidence on the open F6 and T0-07 (one list of states, or standing and lifecycle split apart). It also bears on T9-14 and on whether a supersession kind is admitted to D.

## Checks
- **Quote check, by hand:** I compared every S0364 quote in C1–C5 and every line reference in the lineage and coverage notes against S0364. All match in text and in line. I also checked S0363 L982 and L1101, opened for checking only. The book quotes check out: §1.6 L41 "a further act (supersession)", and "not attested" in §9.18.
- **R2:** `undefined_terms.py --root <scratch> --no-corpus --scope 08.88` gives 30 in-scope candidates in tier D, none in tiers A–C. It found more than the Professor's list because I ran it with `--no-corpus` to avoid reading other corpus files. The words that are not ordinary English:
  - "machine" and "reconciled": source terms, acceptable;
  - "weight": a source term;
  - "displayed": §9.18's term;
  - "adjudication": a homonym (W-11);
  - "apply" and "applying": the two senses are deliberately merged in C4, so a reader needs that stated;
  - unflagged but needing a qualifier: "state" (W-01), "contested" (W-06), "withdrawal" (W-08).

  The rest are ordinary English: jobs, noticing, parenthesi(s), proposing, life, enum, earn, leaving, really, advice, lets, precede, serialised, arrive, draw, evaluated, answered, reply, create, adviser, leave, plain.
- **R3:** `inbound_citations.py --root <scratch>` for "§8.88 C1"–"C5" and "§8.88": 0 citing lines outside §8.88. The index row 08.00-index L104 names the file and must follow any restatement. For W-01's effect on §8.87 C4, the citing lines are 08.88 L3, L11, L31; 08.99 L125; THEORY-MAP L262.

## Limits and deviations
- **Tool root:** my first tool call (`undefined_terms.py --help`) ran without `--root`. It ran from the scratch copy, so its default root was the scratch copy. It then failed trying to open `CORPUS-ALLOWED.tsv` under the scratch parent, which does not exist. No repository file was read by it. Every later call used `--root`.
- **Not run:** the scripted quote check (`corpus_quote_check.py`), because it reads the corpus outside my allowed list; I did the quote check by hand. `proof_step_check.py`, because §8.88 claims no Ded.
- **Not read whole:** §9.13, §9.15 and §1.14 (targeted lines only). I did not open §1.13.1-2 or §8.14 beyond the CON-0211 lines, or the Ch. 3 §3.1 text beyond the grep lines.
- **Not checked:** commit hashes (no git, by rule).

## Files opened
- `.../06-theory/manuscript/review/C95-S0364/BRIEF-W-STEP1.md`
- `.../06-theory/manuscript/review/C95-S0364/BRIEF-W-STEP2.md`
- `.../06-theory/manuscript/review/C95-S0364/STEP8-AND-CHECKS.md`
- `.../06-theory/manuscript/review/C95-S0364/SHA256-STEP1.txt`
- `.../06-theory/manuscript/review/C95-S0364/STEP1-INVENTORY.md` (sha256 only)
- `.../03-methodology/REVIEWER-INSTRUCTIONS.md`
- S0364: `docs/knowledgeos/brainstorming/kernel/20260823-111647-epistemic-lifecycle-domain-discovery-admission-as-first-transition.md` (step 1, whole)
- S0363, for checking only (L978–984, L1096–1104): `docs/knowledgeos/brainstorming/kernel/20260823-110950-kernel-domain-level-brainstorming-admission-hypotheses.md`
- `.../chapter_08/08.88-…md` (whole)
- `.../chapter_08/08.00-index.md` (rows 103–104)
- `.../chapter_08/08.87-…md` (C4)
- `.../chapter_08/08.86-…md` (C4, grep)
- `.../chapter_08/08.84-…md` (head, C3–C4)
- `.../chapter_08/08.14-…md` (grep)
- `.../chapter_08/08.32-…md` (grep)
- `.../chapter_09/09.18-evidence-in-the-record.md` (§9.18.2, §9.18.4, §9.18.6–7, grep)
- `.../chapter_09/09.13-the-evaluator.md` (L40–56, grep)
- `.../chapter_09/09.15-the-trust-boundary.md` (grep)
- `.../chapter_09/09.1[3-9]` (grep "supersed")
- `.../chapter_01/01.06-nothing-but-an-act-confers.md` (whole)
- `.../chapter_01/01.14-…md` (L1–12)
- `.../chapter_01/01.02-…md` (grep)
- `.../chapter_03/03.01-dimensions.md` (grep)
- `.../chapter_00/TODO.md` (L32)
- `.../06-theory/CONCEPT-REGISTER.tsv` (grep, rows)
- `.../06-theory/THEORY-MAP.md` (L7, grep)
- `CORPUS-ALLOWED.tsv` (targeted rows S0364 and S0363 only)
- Scratch copies under `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/rev-c95-wren/root/`, and outputs `c95-w-r2.txt`, `c95-w-r3.txt`, `c95-w-r3b.txt` in that folder.

## Commands and searches run
1. `ls` of the C95 folder, `06-theory/manuscript/` (top level) and `tmp/tools/`; `cat SHA256-STEP1.txt`; `sha256sum STEP1-INVENTORY.md`.
2. `cat -n` of §8.88; `grep` "8.88|8.87" in 08.00-index.
3. `grep` and `sed` on 09.18 (headings, (c2), withdraw, not attested, T9-17, at a cut; L47–75, L105–124, L213–264).
4. `grep` C4 and `awk` C4 on 08.87; `awk` C4 and `grep` pure/stale on 08.86; `head` and `awk` C3–C4 on 08.84.
5. `cat -n` of 01.06; `sed` L40–56 on 09.13; `grep` G4/K1/K4-k/K6 on 09.15.
6. `grep` constitute/CON-0243/0263/0019/0214/reconcil/lifecycle/state machine on CONCEPT-REGISTER; `awk` rows CON-0003, 0019, 0175, 0210, 0211, 0244.
7. `grep` on 03.01 and 08.14; `grep` "Not admitted", "supersed" and T9-14 on 09.13–09.19; `grep` on THEORY-MAP (L7, lifecycle, T0-07); `grep` T0-07 in chapter_00/01 TODO.md; `sed` 01.14 L1–12; `grep` 01.02 F6/lifecycle; `grep` 08.32 lifecycle.
8. `awk` S0363 row in CORPUS-ALLOWED.tsv; `wc` and `sed` on S0363 (L978–984, L1096–1104).
9. `grep -l S0364` over chapter_*/*.md (depth 1), the register and the map; `grep -c` §8.88.
10. Copied the book files, the register, the map, the purpose, the outline and `tmp/tools` to scratch (`cp`, `find -maxdepth 1`).
11. `python3 -I undefined_terms.py --help` (no `--root`; see Limits); `python3 -I undefined_terms.py --root <scratch> --no-corpus --scope 08.88`.
12. `grep` REPO/root/argparse in the scratch tool copies.
13. `python3 -I inbound_citations.py --root <scratch>` for "§8.88 C1".."C5", "§8.88"; then for "§8.87 C4", "§8.86 C4", "§8.84 C3".

No git commands. Nothing refused. Nothing edited in the repository.
