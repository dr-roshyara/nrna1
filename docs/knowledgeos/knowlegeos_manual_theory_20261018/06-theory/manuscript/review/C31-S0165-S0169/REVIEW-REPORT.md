C31 STEP 2-3: REVIEW REPORT

Reviewer W (Opus), transition review, Rulebook v1.4. Before comparing, I re-read my saved inventory (STEP1-INVENTORY.md). Labels used below:
- **V**: verified by me; the command is listed at the end.
- **R**: read in the text.
- **[I]**: my interpretation.

Process independence: same model family as the Professor, a different session, and a blind step 1. This is process independence, not personal independence.

## Summary (10 lines)
1. **Critical, and partly my own earlier error.** The C30 settlement (§9.17 R3 (c) L159, T9-09 (a), CON-0264) says that citing every maximal entry "would remove the premise and keep merge-order independence". That is false as stated (V, probe):
   - if D checks "every maximal" against position, two merge orders give in_D False and True;
   - if membership is simply defined as the down-closure, merge independence holds, but D cannot verify the "every maximal" obligation, and an accepted entry left uncited becomes foreign.
   - My own C30 W-01 correction made this overclaim. It is a defect found after a review passed. Because the Principal has since ruled on T9-09, it must be fixed before C32 applies that ruling.
2. All 14 C30 settlement rows match the text they claim to change (V by diff for W-01–W-07 and W-11; R for §8.23). Two residues remain: the W-01 row misstates what I proposed, and the reader-position premise has no owner (W-03).
3. §8.24 is sound as recorded agreement, but four claims need repair:
   - **C7 (the one deduction)** holds only in a weaker form: a carrier change *requires* a designation entry; it does not *become* one. Its S0166 attribution and its Principal label are also wrong.
   - **C4** misses S0165's stronger tension: the Governance session decides "May this work exist?".
   - **C1** has the wrong home.
   - **C9** collapses S0168's two-level identity into one.
4. The lineage is under-joined. S0166 (22:09) belongs to C30's S0160 thread (22:08). The "same morning" link between S0165 and S0168 rests on medium-confidence file times; S0165's own IDs say 2026-08-14.
5. S0169 = S0163 L16–352 is confirmed (V, 337 identical lines). C12 is right. Its "337 of 338" is a slip.

## Findings

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **The T9-09 (a) alternative is presented as premise-free, and it is not.** "Cite every maximal entry; membership = down-closure" is said to "remove the premise and keep merge-order independence". D has only two ways to apply it. (i) It checks "cites every maximal entry *before it*": that reads position again. A new-carrier entry y that does not cite d is concurrent with d, and in some merge orders it counts as an uncited maximal entry. (ii) Membership is defined as the down-closure: merge-independent, but "every maximal" is then an obligation on the designating actor that no part of D checks (an invariant with no owner). An omitted x2 that the kernel accepted becomes foreign, against CON-0264's membership by acceptance. Either way a premise is needed: every entry appended after a designation cites it, at least transitively; or the obligation is owned outside D. Missing premise, F1/F3: the Principal's ruling on T9-09 rests on this text. | **critical** | **V**: alt_probe.py over all citation-respecting orders of {r,g,x1,x2,d,y}. With (i), in_D takes both False and True. With (ii), one member set over all orders; d omitting x2 gives {r,g,x1}. **R**: §9.17 L159; TODO T9-09; CON-0264 cell (grep "maximal entr": these three, plus the C30 report and settlement). | Restate L159, T9-09 and CON-0264: both options carry a premise. Option (a1) is the append order kept by readers. Option (a2) is "every later entry transitively cites the designation", or "membership is defined by the designation's citations, with the completeness obligation owned by the designating act". Model-check it with merges before C32 applies the ruling. | text restated; a ce_model check covering ≥2 merge orders with a designation and a non-citing later entry |
| W-02 | **C7 overreaches, and its attributions are wrong.** Proof steps: (1) "rebinding changes the carrier" contradicts (3)–(4): by R1 a rebinding changes only where appends go, not the carrier. (2) "By the Principal's decision… every change of carrier is a designation entry": the Principal's words are "a designation becomes a recordable kind in D". The universal statement is K2-d, the Professor's PROVISIONAL presupposition (R1 L64 still carries the Principal's name; the W-06 split was not applied there). (4) "K2-w sends appends…": K2-w is a requirement, not a mechanism. (5) "is a governed act": the effect of an F or U designation is unruled at `68b90a77e` (T9-09 (b)). §8.24.1's "An adviser classes a change of storage provider as technical" is DISTORTED: S0166 L1015–1038 classes the *reasoning engine* change as technical, and storage only by implication (L888–901). S0166 also puts changes to the "Evidence Authority Model" on the governance path, which is close to C7's own view. | high | **R**: S0166 L1019–1038, L888–901; §9.17 R1 L64, R3 (c) L157; §9.13.2; T9-09 (b). The proof-step table is in the appendix. | Restate C7 as: "for the store that carries the record, rebinding the kernel's appends without a designation entry is a K2-w failure (R1; K2-d; K2-w). A carrier change therefore needs a designation entry, appended as the new carrier's first append, with no append between the rebinding and that entry (K6). Its authorization is evaluated like any act's; the effect of an F or U designation is open (T9-09 (b))." Also: exclude the initial binding (the root designation, L11) from scope; mark the S0166 reading [I]; label step 2 as K2-d; split R1 L64's label. | C7, §8.24.1 item 3 and §8.24's title restated; R1 L64 label split |
| W-03 | **R3 (c)'s reader premise has no owner, and the kernel assumptions do not carry it.** "A reader that re-merges entries must keep each designation's position" is a new obligation on readers. The kernel cannot bind readers that resolve nothing (§9.17 R2 table). It is not in K2-r ("a cut named by its entries", which fixes no order) and not in §9.15. D's merge-order independence (check 18b) now holds only for records without designations, and this is stated nowhere in §9.15. The settlement's "Not established" line says so, but the book does not. | high | **R**: §9.17 L159, L90, L121; settlement L32; §9.15 K2 cell. **V**: ce_model.py L209 (`rec.pos[closes] == k-1`). | Add the premise to K2-r, or as a stated assumption in §9.15, with "no owner in the kernel". Scope check 18b and the K2 cell's "the evaluator reads no position" to records without designation entries. | §9.15 and §9.17 R2 state it |
| W-04 | **C4 misses S0165's stronger tension.** L674 does read as a homonym once L675 is taken into account, and the challenge is adequate for that line. But S0165 also has the AI Governance session decide "Governance determines whether and under what authority work may proceed" (L26), "Governance A — Intake / Authorization: 'May this work exist?'" (L233–235), "Is the work legitimate?" (L392), and "May this proceed, under what authority" (L525). Under §1.17 L30, a mechanism that decides breaks ARCH-INV-1. S0165 elsewhere has S2 register human acts (L399, L703), so the source is mixed, not merely homonymous. | high | **R**: S0165 L26, L233–243, L392–399, L524–529, L703; §1.17 L30 | Keep the homonym reading for L674. Record the Governance-A lines as an open tension: does "determine" mean checking the record for a human act, or deciding? Do not class the whole of S0165 as agreeing with ARCH-INV-1. | C4 restated; tension recorded in §8.24.3 |
| W-05 | **C1's home is wrong.** CON-0239 concerns readers' diverging resolutions of *the record*. A session that takes its role from its terminal is not resolving the record at all: it claims standing from a source other than the record. That is ARCH-INV-1 or §1.17's "information without authority", and self-activation is §1.14.3-2 (C2's home). The CON-0239 link is an analogy, not an instance. | normal | **R**: CON-0239 text; §1.17 L30; §1.14.3-2. **V**: R3 shows 36 concept-level lines cite CON-0239. None needs to change, because §8.24 C1 is not cited yet. | "Analogous to CON-0239; an instance of standing derived from recorded acts (§1.14.2-1, §1.14.3-2)" | C1 restated |
| W-06 | **C9 collapses S0168's two-level identity.** S0168 separates "Evidence ID, stable logical identity" from "Evidence version, immutable" and "content hash" (L898–921), and leaves "Are hashes enough for semantic equivalence?" open (L966). C9 reads only "same entity iff content unchanged". The entry-versus-carrier qualification is right. | normal | **R**: S0168 L898–921, L950, L966 | Add the logical-versus-version level as evidence for T9-08 (f) / CON-0240's two levels | C9 amended |
| W-07 | **The lineage is under-joined.** (a) S0166 (22:09) builds on "your new Evidence Boundary idea" (L333), EKS-05 (L562) and "the durability architecture" (L665–675). That is C30's S0160 (22:08) thread. So S0160, S0162, S0163, S0166, S0167 and S0169 are one line, and C6's agreement with §8.23 C9 is not independent. (b) "S0165 (10:48, the same morning)" rests on a medium-confidence modification time. The allowed list dates S0165 to 2026-08-14 (row 1426), and its assignment IDs carry 2026-08-14 (L116–117). | normal | **R**: front matter; CORPUS-ALLOWED rows 1426, 1514; S0166 L333, L562, L665 | Join the lines; drop "same morning"; date S0165 by the index | lineage paragraph restated |
| W-08 | **The settlement's W-01 row misstates the correction.** C30 W-01 proposed "cites every maximal entry **and** down-closure", not down-closure of the single cited last entry. So "Not accepted: the reviewer's citation down-closure… loses x2" answers a position nobody held. No harm to the theory, since the combined form is recorded. Also: the CON-0264 cell cites CON-0264 inside its own definition. | normal | **R**: C30 report L29; settlement L11; register row 265 | Correct the row's wording at the next settlement; drop the self-citation | rows corrected |
| W-09 | **A count slip in C12.** The lineage paragraph says 337 identical lines; C12 says "337 of 338 body lines". | normal | **V**: S0169 L16–352 == S0163 L16–352, 337 lines, no mismatch | Say "337 lines, identical" | C12 corrected |
| W-10 | **C8's agreement is scoped to writes, but S0167's layering covers reads too.** Applications call `queryKnowledge()` (L1416–1422), so a read that bypasses the kernel is a K2-r matter, not K1 or K2-w. | normal | **R**: S0167 L1412–1422 | Add K2-r | C8 amended |

## Claim verdicts (§8.24; no new concept)

| Claim | Verdict | Reason |
|---|---|---|
| C2, C3, C5, C10, C11 | VALIDATED | agreement within the estate's lineage, correctly scoped (n = 1 where stated) |
| C6 | VALIDATED | tensions correctly recorded. S0167 L1976–1985 sides with S0166's third part (runtime = Kotlin + Spring DI); record it |
| C8 | VALIDATED, scope note | W-10 |
| C12 | VALIDATED | duplicate verified (W-09 is a slip only) |
| C1 | PROVISIONAL | W-05 |
| C4 | PROVISIONAL | W-04 |
| C7 | PROVISIONAL; INVALID as stated | W-02. The weaker form ("requires a designation") is valid from R1, K2-d and K2-w |
| C9 | PROVISIONAL | W-06 |
| C30 settlement (`68b90a77e`) | rows match; R3 (c) PROVISIONAL with defects | W-01, W-03, W-08 |

**R3 (c), question by question.** The stated premise and check 28's scope match probe P1 and W-05; nothing more is claimed for the *applied* rule. The overclaim is in the alternative (W-01) and in the missing ownership and scoping (W-03).

## Coverage against my inventory (gaps only; all other items COVERED or JUSTIFIABLY EXCLUDED)

| Item | Status |
|---|---|
| S0165-5 (Governance A/B/C), S0165-1 (conditional activation) | MISSING (W-04) |
| S0165-3, trivial case: no START means nothing happens (a must-never rule with no must-do) | MISSING, normal |
| S0165-8 (scoped qualification) | MISSING, normal (relates to §8.15) |
| S0166-9 (two approval paths) | DISTORTED (W-02) |
| S0166-11 (ADR proposed, deferred, re-proposed: a change over time) | MISSING, normal |
| S0167-13 (knowledge object vs knowledge product); S0167-8 (lifecycle states drift); S0167-9 (governance in the open kernel vs in the paid edition) | MISSING, route to T0-03 |
| S0167-6 ("Git for knowledge", event sourcing) | MISSING, evidence for §8.23 C2 |
| S0168-3, -11 (roles as an operating model; role→context and service→capability anti-patterns; role assignment mistaken for authorization, L165) | MISSING, evidence for C3 and §1.14 |
| S0168-10 (ten-criterion bounded-context threshold) | MISSING: a third admission test beside CON-0085 and §8.21 C15 |
| S0168-12 (assurance levels; self-certification), S0168-14 (distributed stewardship versus one owner) | MISSING, normal |
| S0168-13 | DISTORTED (W-06) |
| S0167-12 (roadmap arithmetic), S0169 moat tension, S0169 timelines, SUSE listing | JUSTIFIABLY EXCLUDED (business; source defects, not relied on) |
| five-actor vs six-role taxonomy | COVERED (recorded only); correctly not raised |

**New fork:** the premise of T9-09 (a2) (W-01). **Open tension:** the Governance session deciding authorization (W-04).

**Running measure:** 10 findings: 1 critical, 3 high, 6 normal. Of these, 1 is a defect found after a review passed: W-01, in my own C30 correction.

## Appendix: C7 proof-step table

| Step | Justification | Objects defined in the book? | Judgement |
|---|---|---|---|
| 1 rebinding changes the carrier | none; contradicts R1 (the carrier is fixed by designation) | "binding": no (S0166's term; it equals the location part of a resolution, §9.17.2) | invalid; replace with "changes where appends go" |
| 2 every change of carrier is a designation entry | K2-d (CON-0261, PROVISIONAL), labelled as the Principal's | yes | premise mislabelled |
| 3 the carrier is fixed only by designation | R1 | yes | valid |
| 4 rebinding without designation: appends diverge from the designated carrier | K2-w (as a requirement) + step 1 restated | yes | valid once restated: a K2-w failure |
| 5 "is a governed act of kind designate" | steps 2–4 | "governed" depends on T9-09 (b), unruled | overreach. What follows: "requires a designation entry" |

Judgement: **inadequate as recorded, because a premise is missing.** The Professor's falsifier ("no carrier change without a designation entry exists, by R1") holds only under K2-d.

## Files opened
- W/06-theory/manuscript/review/C31-S0165-S0169/STEP1-INVENTORY.md, BRIEF-W-STEP2.md, STEP8-AND-CHECKS.md
- W/06-theory/manuscript/chapter_08/08.24-…md (whole), 08.23-…md (whole)
- W/06-theory/manuscript/chapter_09/09.17-…md (whole)
- W/06-theory/manuscript/review/C30-S0159-S0164/SETTLEMENT.md, REVIEW-REPORT.md
- By grep only: CONCEPT-REGISTER.tsv (CON-0239); chapter_01 (ARCH-INV-1, §1.14.3-2 and §1.17 L30 lines); 09.15 (K2 cell); tmp/tools/ce_model.py (designation lines); docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv (rows for S0163 and S0165–S0169; S0170 not checked or opened)
- Corpus, by script only (no text displayed): S0169, and S0163 (`brainstorming/_misc/20260819-225858-kos-ipo-stock-market-path.md`, on the allowed list, row 1513). The sha256 of the five window sources match the allowed list.
- No file was refused. No Ch. 0–2 basis file, sealed, hold-out, quarantine or chronological-read file was opened.

## Commands run
- `git show --stat 68b90a77e`; `git log --oneline -6`; `git show 68b90a77e` (word-diff) for chapter_09, the register, the chapter 0/8 TODO files, THEORY-MAP, 08.19, 08.22 and 08.00; `git diff 68b90a77e HEAD --stat`
- `ls` of the scratchpad and of W/tmp/tools
- `grep` in ce_model.py, 09.15, the register, chapter_01 and the allowed list; `grep -rn "maximal entr"` in W/06-theory
- `python3 -I -c` (S0169 vs S0163 difflib/equality); `sha256sum` of the five sources
- `python3 -I scratchpad/c31/alt_probe.py` (my probe of the T9-09 alternative), at /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c31/alt_probe.py
- `python3 -I tmp/tools/inbound_citations.py "T9-09" "§9.17 R3" "§8.24 C7" "§8.24 C1" "§8.24 C4"`. The tool found no home for T9-09 or §9.17 R3, so the W-01 citing lines were listed by grep instead. §8.24 C1 gave 36 concept-level CON-0239 lines, none of which needs to change.
- Nothing was edited in the repository. Nothing was sent to an outside service.
