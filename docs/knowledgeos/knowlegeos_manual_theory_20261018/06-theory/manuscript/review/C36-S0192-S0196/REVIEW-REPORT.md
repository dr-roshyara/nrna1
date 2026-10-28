C36 STEP 2-3: REVIEW REPORT

Window reviewer W, transition review. Scope: §8.29 (C1–C7), the step-8 file, and the changed theory in `028a24599` (the C35 settlement). I re-read my saved inventory first.

## Summary (10 lines)
1. **What §8.29 says the sources say is accurate.** The quote check gives 4 found and 0 not found. The weak points are four interpretations: C4, C5, C3 and the C1 relay.
2. **C4 undoes a just-settled correction (W-01).** It calls PKS "a second system of the estate", although C35 W-03 left that open under T0-03b. The window also holds T0-03b evidence that §8.29 does not route.
3. **C5 overstates the book's design (W-04).** §9.17 detects a bypass under an assumed premise; it does not close it. The heredoc case is an analogy to §9.17; its real home is §1.5's four-layer runtime model.
4. **C3, verified in git (W-06).** S0195 and the erratum it asks for were committed together, eight minutes after the baseline, and the erratum is labelled "HPA-authorized". No separate disposition record exists in the window.
5. **C1 relays an independence claim (W-02).** It repeats S0193's "not independence failures" without the CON-0127 bound.
6. **§8.29.3 misreads S0192 L118 (W-03)** and contradicts the settled §8.28 C3 (C35 W-07).
7. **The C35 settlement matches its rows,** apart from three small slips (W-11). "No new model version" is verified: only the docstring and one label changed, and the output is otherwise identical.
8. **W-04 of C35 (a reasoned exclusion) is adequate as a deferral,** but only if T0-03b is fed. C36 does not feed it.
9. **§8.28 C8's two senses of "durable" are right** against S0188 L630 and S0190 L176.
10. **No critical finding.**

**Process independence:** I am a separate session from the Professor's, possibly of the same model family. The independence is of process, not of person.

## Findings

| W | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| **W-01** | **C4: "found in a second system of the estate (PKS, after EKS)" presupposes the question T0-03b leaves open.** The window also holds unrouted evidence on that question: (a) S0196 L548 lists `.claude/scripts/workflow-state.php` and `session-resolve.php`, the gate mechanism S0192 and S0193 read, as PKS's "governance runtime integration"; (b) §1.9 L68 locates ARCH-INV-1's only compiled guard in `Verdict.php` and `Assessment.php`, and S0196 L349 places these files in PKS's `Shared/Domain`; (c) the `feat(knowledgeos)` labels on PKS's own code (S0196 X-06, U-04); (d) S0195's Q5, "two implementations … or two fundamentally different systems" (L78). | high | S0196 L349, L548, L747; §1.9 L68; S0195 L78; §8.28 C1; T0-03b | Restate as "in the PKS corpus (whether PKS is a second system is open, T0-03b)". Route (a)–(d) to T0-03b. | C4 restated; T0-03b row extended |
| **W-02** | **C1 relays "NOT independence failures" and "both processes measured clean" (S0193 L122) unqualified.** The measurement is 0 occurrences of a self-declared identifier that the source itself calls "NOT attestable" (S0193 L20, L33; S0192 L21, L26). An empty record cannot establish independence. | high | CON-0127; §8.27 C3; §8.24 C10 | Add: "independent on the record as far as a search can show: declared, not attested (CON-0127)". | C1 qualified |
| **W-03** | **§8.29.3: "the eligible reviewer set again grows by one name".** The source says a name is added "to the exclusion set" and that "ten processes now carry a role" (S0192 L118). The settled §8.28 C3 says that what grows is the set of processes with a role, not the excluded set. S0192 is also by the same process as S0190 (S0192 L13), and L118 restates S0190 L174 (read). It is the same line, not a new occurrence. | high | S0192 L13, L118; S0190 L174; §8.28 C3 (W-07) | Restate: "the source repeats S0190 L174 (same process); see §8.28 C3, CON-0151/F32". | restated |
| **W-04** | **C5's link to §9.17 overclaims.** The challenge answer says the book's design "closes" the route "structurally", and the Int says "Only the kernel appends". In fact §9.17 R3 (c) detects a bypass (fail-closed, the record leaves D). It states "The attestation is assumed unforgeable; the model … does not show it", and for owners the check rests on a declared application (C35 W-02). The estate case is also different in kind: a governed *file* written through another tool, past a permission adapter. Unless that file is the record's carrier, it is not an entry, so the link is an analogy. The closer home is §1.5's four-layer runtime model (S0031), the same chain S0196 L494 relays, together with §1.9's census of enforcement modes. | high | §9.17 R3 (c) premise and limit (ii); §1.5 L11–13; S0196 L494 | Restate C5's Int as an analogy: "the book detects such writes, under an assumed attestation; a tool-scoped gate checks a route". Re-home to §1.5 and §1.9. Correct the challenge record. | restated |
| **W-05** | **C3 states the erratum's explanation as fact:** "The baseline had counted the three permission blocks". S0195 offers it only as a conditional ("If '1 block' was meant structurally", L92). Verified in git: the reviewed text `db9154664` reads "1 allow / 1 ask / 1 deny block present" at L409, but L639 and L730 state counts ("has 1 allow/1 ask/1 deny"). The explanation is plausible, not established. In addition, the 19/22/8 shown in S0196 as read is S0195's re-measurement (S0196 L11, L759), so the two sources show one measurement, not two. | normal | S0195 L92; `git show db9154664:…` L409, L639, L730 | Attribute the explanation: "the erratum's reading; S0195 leaves it conditional". Count the re-measurement once. | restated |
| **W-06** | **C3's open question (is S0195 the human's act or the session's report?) has a factual answer in the record.** Verified: `999ede26b` (22:46) commits S0195 together with the S0196 erratum, eight minutes after `db9154664` (22:37). The commit subject reads "P3 gate OPEN", and S0196 L11 labels the erratum "HPA-authorized". S0195 itself calls the review a PROPOSED finding that the HPA's disposition supersedes (L120, L130). So the recommendation and its application are one commit, and no separate disposition record exists. The draft's "the record of the disposition is the act (§8.10)" has nothing in the window to point to. | high | `git show --stat 999ede26b`, `db9154664`; S0195 L5, L120, L130; S0196 L11 | Record as fact: "no disposition record in the window; the review and its correction landed in one commit". Instance for §8.28 C5 (a recommendation does not become a decision by itself) and §8.26 C3. | restated |
| **W-07** | **C2 fits CON-0120, but the challenge answer places the scope loss wrongly.** It says the scope was lost "as relayed onward". The scope loss is inside S0194: from five directories at L42 to "in this repository" at L122 D. That passage stays hedged and orders "verify independently", and the verification worked (S0196 measured the code). This is §8.04 C3's second pattern (a verified narrow claim generalised). C2 also misses a stronger instance: "PKS evidence never names EKS" (S0196 X-01, U-02) rests on a grep of four artifacts (S0195 L36), yet is stated without that scope. | normal | S0194 L42, L122; §8.04 C3; S0195 L36; S0196 L722, L742 | Cite L122 and say the self-correction worked. Add the X-01 instance. | restated |
| **W-08** | **C6 does not apply §3.2's dimension test to the M4 axes.** The four axes are the PKS model's relayed claim (S0196 L253), with no pair given that shows non-dependence (compare §3.1, where "the orthogonality proof" shows nothing). The corpus's own test rejected INTENT-STATUS (proposed · ratified · operational) because "Ratified" and "approved" are the same axis (§3.2, S0016 L54). M4's "adoption" and "maturity" overlap that rejected axis, so two corpus models conflict. On the other side, M4's authority ⊥ status agrees with §3.1's reason for marking CON-0003 invalid (standing mixed with lifecycle). Finally, "Model integrity (an observation, not promoted)" is a repository-rule label, not a book category. | normal | §3.1; §3.2 table; S0196 L252–256 | Record the conflict with the rejected INTENT-STATUS axis and the agreement with CON-0003. State that the orthogonality is unshown. Drop the repository label. | restated |
| **W-09** | **C1: home and balance.** S0192 L6 supports "a name can assert an act" almost verbatim. The closest homes, though, are §8.25 C7 (a status tag is a claim) and §8.15 C11 (a name is not a criterion); §8.26 C3 deals with an existing artifact's status line. C1 also quotes S0193's "cheapest cure" without S0193 L120, "I recommend neither". | normal | S0192 L6; S0193 L120–122; §8.25 C7; §8.26 C3 | Make §8.25 C7 the primary home and keep §8.26 C3 as secondary. Quote both of S0193's sentences. | restated |
| **W-10** | **Coverage omissions in S0196 that already have book homes:** (a) X-02, "not software" versus the capability layer: §1.5 already gives the same answer (PHP as "capabilities acting on knowledge"); (b) Risk, Question and Exception record left unallocated (L276, L632) agree with §1.15.2, while PKS keeps Candidate as Core (L248) against §1.15.2's finding 1 ("Candidate is a status"); (c) Conformance as a derived assessment, Policy as a projection pattern, and no Requirement or Constraint primitive (L225, L245–247) are §3.2's derived-attribute discipline; (d) L619's claim that the validator "actually restricts its output" overreaches §1.9 L68 ("one runtime guard on one code path"); (e) documented purpose is classed **A** (L109, L149) against the source's own L38 and L46, which C4 cites as the rule. | normal | as listed | One line each, at the named homes. | recorded |
| **W-11** | **Small slips:** (a) the settlement says W-03 was "Routed to T0-03b", while §8.28 C1 still says "Routed to T0-03"; (b) the map says "v20's owner check is constant along a chain", but it is the owner that is constant, not the check; (c) (c′) qualifies detection ("if the mover declares its own application") but not "prevented, within any kernel that holds the record"; R3 (c) limit (ii) covers it, (c′) does not; (d) the §8.29 header names "the C34 settlement" as changed theory, but the change is C35's (`028a24599`). | normal | `git show 028a24599`; §8.29 L3 | Correct each. | done |

## Claim verdicts

| Claim | Verdict | Scope / reason |
|---|---|---|
| §8.29 C1 | VALIDATED within scope | two refusals from one line; the name point is supported by S0192 L6. The independence sub-claim is not validated (W-02) |
| C2 | VALIDATED | an instance of CON-0120 (W-07: placement and a second instance) |
| C3 | PROVISIONAL | the re-measurement holds; the explanation is the erratum's (W-05); the disposition question has a record answer (W-06) |
| C4 | PROVISIONAL | the source reports hold; "a second system" is INVALID as stated (W-01) |
| C5 | PROVISIONAL | the estate fact holds (n = 1, relayed at tier 5); "closes structurally" is INVALID as stated (W-04) |
| C6 | PROVISIONAL | the homonym point holds; the axes are relayed and untested, and they conflict with §3.2 (W-08) |
| C7 | VALIDATED within scope | a relay, one line; CAP-002 deferred |
| C35 settlement rows | match | except W-11 (a)–(c) |
| W-04 "reasoned exclusion" | adequate as a deferral | T0-03b has a completion condition. The gap is that C36's evidence is not routed to it (W-01). The reason given (B-10) was accepted on reading, R |
| v20 limits; no new model version | VALIDATED | both limits are stated in R3 (c), (c′), the docstring, check 32 and T9-11; the re-run is identical to the saved run; the only difference from the parent is the label line |
| §8.28 C8 restated | VALIDATED | S0188 L630 ("rather than … ephemeral conversation state") and S0190 L176 (version control) read |

## Coverage (my inventory)

| Item | Status |
|---|---|
| Commission ≠ START; "recorded" carries the weight; no self-registration or self-start; absent deliverable | COVERED (C1) |
| 0-occurrence measurement resting on a non-attestable identity | MISSING (W-02) |
| Safety without liveness (S0192 item 12) | COVERED by §8.28 C3 / CON-0151 (C35 W-07); §8.29.3 DISTORTED (W-03) |
| Durability gap (S0192 L120, S0193 L124) | JUSTIFIABLY EXCLUDED as the same line as S0190 L176 (§8.28 C8); say so |
| S0194's lexical vs semantic distinction; contamination vector | COVERED (C6; §8.29.3) |
| S0194's "documentation-only" claim | COVERED (C2; W-07) |
| Evidence hierarchies, 10 tiers vs 8 | MISSING (normal: the commissioned scale changed without a record; C5's "its own scale" is right to localise) |
| S0194 L148, teleological final principle | MISSING (normal) |
| S0195 re-measurement; reviewer identity | COVERED (C3); DISTORTED in part (W-05, W-06) |
| S0195's weak negatives (four-document grep) | MISSING (W-07) |
| S0196 headline identity; X-02; U-02/X-01 | DISTORTED (C4) / MISSING for T0-03b (W-01, W-10a) |
| Conformance Core-while-Absent; ZERO-INDEPENDENT | COVERED (C4) |
| M4 axes; recorded human decision | COVERED, test missing (W-08) |
| M2/M3 concept economy; Risk, Question and Exception record; Candidate as Core | MISSING (W-10 b, c) |
| Closed verdict vocabulary vs §1.9 | MISSING (W-10 d) |
| Documented purpose classed A (L109, L149) | MISSING (W-10 e) |
| G-10 curable/uncurable; Cohesion count; X-08 | COVERED (§8.29.3) |
| L452 points to X-04 (should be X-03); L710 diagram inverts the Shared rule | source defects; no theory use |
| Gate scripts listed as PKS integration (L548) | MISSING (W-01 a) |

**New topic:** none. The PKS/EKS identity evidence belongs to T0-03b. **No new fork.**

## Next steps (dependency order)
1. **W-01:** restate C4 and route (a)–(d) to T0-03b. Done when the T0-03b row cites them.
2. **W-02, W-03, W-06:** qualify C1; restate §8.29.3; record the single commit as fact.
3. **W-04, W-08:** restate C5 as an analogy and re-home it to §1.5 and §1.9; apply §3.2 to the M4 axes.
4. **W-05, W-07, W-09, W-10, W-11:** attributions, instances and slips.

## Limits
- I did not read §1.14, §8.10, §8.15, §8.17, §9.17 outside the diff region, §8.30 (beyond its first 12 lines), BOOK-OUTLINE or consolidation B-10.
- I did not open `.gitignore`, so §8.28 C8's "every runtime governance record" rests on S0190 L176.
- I did not run R1 or R2 myself; the step-8 values are reported, not shown.
- `inbound_citations.py` for §8.29 C1–C6: all hits are §8.30 L17, L20, L64 and the map L368. Their own content was not checked.
- For W-05 I read an earlier version of S0196 through git (`db9154664`). This is the same allowed source path at an earlier commit, and I record it here as an access point.

## Appendix
- **Trivial case (C1).** A gate that refuses everything satisfies every "must-never" rule in S0192 and S0193 while no review is ever done. The book holds the required "must-do" under CON-0151/F32 (C35). §8.29 needs only a pointer to it.
- **Lineage.** S0194 was committed in the same commit as the first version of S0196 (`db9154664`). Its precedence is therefore stated, not shown by the record. S0192 is the sequel of S0190 by the same process. S0196 L494 and §1.5's S0031 describe the same adapter chain; whether they share an origin is not checked.

## Files opened
- **Review folder C36:** `review/C36-S0192-S0196/BRIEF-W-STEP2.md`, `STEP1-INVENTORY.md`, `STEP8-AND-CHECKS.md`.
- **Chapter 8:** `chapter_08/08.29-….md`.
- **Review folder C35:** `review/C35-S0187-S0191/SETTLEMENT.md`, `REVIEW-REPORT.md`.
- **Chapter 3:** `chapter_03/03.01-dimensions.md` (L1–60), `03.02-derived-attributes.md`.
- **Partial reads by awk, grep or sed:**
  - §8.26 C3, §8.25 C7, §8.04 C3, §8.24 C8–C11;
  - §1.9 (L59–68), §1.14 (ARCH-INV-1 lines), §1.15 (L1–40), §1.5 (L9–13), §1.13 (L46–71), §8.30 (L1–12);
  - Ch. 0 TODO (PKS lines);
  - register rows CON-0120, 0127, 0151, 0217, 0242, 0260;
  - the headers of `corpus_quote_check.py` and `inbound_citations.py`.
- **Corpus files beyond the five, checked on `CORPUS-ALLOWED.tsv` first (rows 12 and 1548):**
  - S0188 `docs/knowledgeos/brainstorming/20260821-2033-what-eks-is-today.md`, L622–636 and L1010–1016;
  - S0190 `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-REVIEWER-ELIGIBILITY-DECLARATION-8a525719.md`, L170–180.
- **Through git:** `028a24599` (stat and diffs of the theory files, `ce_model.py` and its run file); `028a24599^:ce_model.py`; S0196's log; `db9154664:` S0196 (grep); stats of `999ede26b` and `db9154664`.
- **Tool output:** the saved tool-output file for `git show 028a24599`.

## Commands run
1. `git show --stat 028a24599`; `git show 028a24599 --` the map, Ch. 0 TODO, Ch. 8, Ch. 9, `ce_model.py` and its run file.
2. Extracted v20 before and after the settlement with `git show` into the scratchpad. `cmp` against the working copy; ran both with `python3 -I`; `diff` of the runs against the saved run and against each other; `diff` of the code.
3. `ls` of `06-theory`, `manuscript`, `tmp/tools`, and chapters 1, 3 and 8. `wc -l`; `grep`, `awk` and `sed` on the book files and register rows listed above; `grep` of `CORPUS-ALLOWED.tsv` for S0188 and S0190; `sed` on those two sources.
4. `python3 -I corpus_quote_check.py` on §8.29 (4 found, 0 not found). `python3 -I inbound_citations.py` for §8.29 C1–C6 and §8.28 C1.
5. `git log --oneline --` S0196; `git show db9154664:`S0196 piped to `grep`; `git show --stat` of `999ede26b` and `db9154664`.

No file outside the scratchpad was edited. Nothing was sent outside the machine. No private person's name is reproduced. I opened none of S0155, S0156, S0157, S0161, S0170 or S0174, no `docs/knowledge_tranfer/*` file, no Ch. 0–2 basis file and no excluded path. No file was refused.
