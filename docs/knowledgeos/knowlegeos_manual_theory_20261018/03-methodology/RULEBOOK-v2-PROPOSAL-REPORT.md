# Rulebook v2: proposal report

| Field | Value |
|---|---|
| Status | **PROPOSAL (revision 2), for the Principal's decision.** Nothing here is in force. `RULEBOOK-v1.3.md`, `REVIEWER-INSTRUCTIONS.md` and `METHOD-VERSIONS.md` are unchanged |
| Author | the Rule Reviewer session, 2026-10-19 |
| The proposed manual | `RULEBOOK-v2-PROPOSED.md`, revision 1 (251 lines, 4,368 words; revision 0 had 211 lines, 3,442 words) |
| Principle | simplify administration, not reasoning; reduce duplication, not safeguards; keep chronological completeness and conceptual depth |

Every statement below is marked as one of three kinds:
- **[V] verified:** checked in the files named, at commit `fc8d9071f`;
- **[A] assumption:** an estimate or a reading not checked;
- **[R] recommendation:** my proposal; the Principal decides.

---

## 1. Summary

1. **[V]** The operating rules are spread over **at least eight places**: `RULEBOOK-v1.3.md` (272 lines), `REVIEWER-INSTRUCTIONS.md` (154), `AMENDMENT-32a` (50), `TRIAL-T2` (21; still the stated conditions of topic turns), `METHOD-VERSIONS.md` (104 lines, 60+ entries), `PRINCIPAL-DECISION-RECORD-RULES-20261018.md` (297), `00-access/ACCESS-RECORD.md` (390), and the archived rule texts in `archive/03-methodology/` (CV-1, SQ-1, ML-1, RK-1, RV-1).
2. **[V]** Some active obligations live **only** in a trial file or an archived rule: for example the reviewer access entry before every review (T2 item 8–9), the strength scan (T2 item 5; v1.3 §12 refers to it for topic turns only, yet it is run every window), SEQ-gap IDs (SQ-1, archived), `same_subject_as` (CV-1 §2a, archived).
3. **[V]** Four internal inconsistencies exist (V-3 to V-6 below). None has caused a theory defect that I found; they cost reading time and invite misreading.
4. **[R]** v2 states each requirement once, keeps every safeguard, moves history out of the rules, and adds the requested items: a provenance/status evidence model, a coverage disposition, question-driven theory turns, a narrow critical-problem trigger, and a three-part completion report.
5. **[A]** The administrative saving is **modest: about 10–15 % of the Professor's time per window, not more.** The review time is deliberately unchanged. A limited trial (§9) should measure it before adoption.

## 2. Verified findings on the current rules

| ID | Finding | Evidence |
|---|---|---|
| V-1 | Obligations in force are spread over eight places, two of them a trial file and the archive | §1 above; `03-methodology/`, `archive/03-methodology/` |
| V-2 | Review mechanics are stated twice: v1.3 §6 (six questions, six assessment points, division of labour) and `REVIEWER-INSTRUCTIONS.md`. v1.3 §6 itself says the instructions apply where they differ | `RULEBOOK-v1.3.md` L116 |
| V-3 | **Window size stated two ways:** §7 "up to five sources per turn"; §12a "5 to 8" | L149; L263 |
| V-4 | **Severity scale not used as written:** the instructions say critical/high/normal; the reports of C14, C15 and C16 use major/minor (C13 still used high/normal) | `grep` of `review/C13…C16/REVIEW-REPORT.md`: C14 9 major + 3 minor; C15 11 + 6; C16 9 + 5 |
| V-5 | **The register numbers are not unique:** two entries numbered 24 (corrected by 24-corr), two numbered 38 (11:20:49Z and 11:33:42Z, not corrected); entries 26a/26b stand after 27 | `METHOD-VERSIONS.md` |
| V-6 | **Two revision homes:** D-R5 keeps the L-4 continuity rule (`BASELINE-REVISIONS.md`), but that file was last appended at BRANCH-01; since then revisions go to `NN.99-change-log.md` (v1.3 §4) and the old text into the review folder (C16: `01.12-OLD-before-integration.md`) | `git log -- BASELINE-REVISIONS.md`; C16 folder |
| V-7 | **The skim safeguard has never run:** the read log has 102 `full` rows and **0 `skim` rows**, so "the reviewer reads one skimmed source blind" has had nothing to check | `READ-LOG-CHRONO-20261018.tsv`, column `depth` |
| V-8 | **Branches are dormant:** 4 `branch-consult` rows, one branch (BRANCH-01); theory turns (TT1–TT3) and added sources have taken over the out-of-order reading | read log; `review/` |
| V-9 | **Four out-of-order mechanisms** with overlapping conditions: added sources (§12a), topic turns (§12, by T2 conditions), theory turns (§12b, "§12a conditions"), branches (§11) | `RULEBOOK-v1.3.md` |
| V-10 | **History inside the rules:** §8 (dropped since v1), §9 (two closed items), the T3 paragraph in §12a, the C10 evidence quote in §12, "first theory turn: F22" in §12b (done) | `RULEBOOK-v1.3.md` |
| V-11 | **The planted-gap isolation lessons are not in any rule;** they are only in `PLANTED-GAP-RESULT.md` "Lessons for the next test" | `review/CONSOLIDATION-S0036-S0065/PLANTED-GAP-RESULT.md` |
| V-12 | **`book_search.py` has no option to exclude files** (`--help` is treated as a query), so the isolation lesson cannot be applied by the tool as it stands | run of `book_search.py --help` |
| V-13 | **No Professor-side rule for computed results:** the evidence rule for scripts and computations applies to the reviewer's own claims only ("Working rules"); the Professor's rule (§2 step 2) says findings are verified in the source text, not that a script and its output go to the reviewer | `REVIEWER-INSTRUCTIONS.md` L137; `RULEBOOK-v1.3.md` L26 |
| V-14 | **Reading other repository files is not authorized:** D-C1 excludes "any file not on the allowed list". The only exception granted so far is the brainstorm folder, classed external (2026-10-19T11:26:43Z) | decision record L79–82; `EXTERNAL-SOURCES.md` |
| V-15 | Per window, eight records are written: `STEP1-INVENTORY`, `STEP8-AND-SCAN`, `REVIEW-REPORT`, `SETTLEMENT`, an access entry with a closing line, a change-log row, a theory-map block, register rows; plus session log, CONTEXT, commit | `review/C16-S0087-S0091/`; `ACCESS-RECORD.md` tail; `sessions/2026-10-19.md` |
| V-16 | State at this commit: reading position S0091; register 152 concepts (40 VALIDATED, 32 PROVISIONAL, 80 INVALID) | read log; `CONCEPT-REGISTER.tsv` |

## 3. Inventory and mapping: every current obligation and where it goes

*Classes: **A** active · **H** historical (trial, closed item, evidence quote) · **O** obsolete (superseded) · **E** explanatory · **D** duplicated. Actions: **retained** (same content) · **merged** (stated once with another) · **moved** (to another home, content kept) · **superseded** (replaced by a newer rule) · **proposed removal** (needs the Principal's yes). Nothing listed here disappears without an entry.*

### 3.1 Rulebook v1.3

| v1.3 | Obligation | Class | v2 home | Action | Rationale |
|---|---|---|---|---|---|
| §1 | purpose; meaning must match | A | §1.1 | retained | — |
| §1 | five "what matters" questions; authorship rule | A | §1.2–1.3 | retained | — |
| §2.1 | triage then read, index order, theory map in mind | A/D | §3.1–3.2 | merged | duplicated §3 |
| §2.2 | validate by reasoning; computation and ML | A | §5.1 | retained | — |
| §2.3 | existing home; book search; top 5 hits | A | §5.2 | retained | — |
| §2.4 | references at the end | A | §6.1 | merged | part of section order |
| §2.5 | close_source.py and what it checks | A | §2.1, §5.5 | retained | — |
| §2.6 | might not be valid → text + TODO | A | §7 (table, TODO row) | merged | one home for open items |
| §2.7 | update theory map | A | §5.6 | retained | — |
| §2.8 | dependent-chapters check; unverified marking | A | §7 (dependency row), §8.2.4 | merged | the check goes in the brief, the marking in §7 |
| §2.9 | previous settlement's changes reviewed | A | §8.2.2 | retained | — |
| §3 | index order; scan whole; full/skim; reviewer reads one skimmed; no-new is information | A | §3.1, 3.2, 3.5 | retained | V-7: never exercised, kept |
| §4 | section order; What does not hold | A | §6.1 | retained | — |
| §4 | generalisation; no rate without random sample | A | §4.3 | retained | — |
| §4 | chapter folders; split of Ch. 0–2 | A + H | §6.2 | retained; split moved to history | the split is done (byte check passed) |
| §4 | two-step rewrite, all sub-points | A | §6.3 | retained | — |
| §4 | BOOK-OUTLINE | A | §6.4 | retained | — |
| §4 | labels Src/Ded/Int/Hyp; no claim stronger than evidence; no revision notes in text | A | §4.1, §6.2 | retained; Repo added | §4 of this report |
| §4 | claim-scoping | A/D | §4.3 | merged with generalisation and strength scan | one "claim strength" block |
| §4 | quotes with anchors | A | §4.3 | retained | — |
| §4 | external concepts (all points) | A | §4.2, §2.2 | retained | — |
| §4 | trivial cases for formal models | A/D | §4.3 | retained (also in reviewer instructions, reviewer side) | — |
| §5 | forks: classify, keep open | A | §7 (fork row) | merged | one disposition table |
| §6 intro | reviewer works under the instructions | E | §8 intro | retained | — |
| §6.1 | blind reading + one skimmed | A/D | instructions step 1; §3.2 | moved | V-2 |
| §6.2 | before/new brief; whole map | A | §8.2.1 | retained | — |
| §6.3 | six questions; "not applicable"; assessment list; division of labour | A/D | instructions steps 2–3 | **moved** | V-2; check in §3.3 that each is there |
| §6.4 | findings stand unless disproved; process independence | A/D | §8.5; instructions | merged | — |
| §6.5 | theory-only settlement turn | A | §8.5 | retained | — |
| §7 | allowed files only | A | §2.1 | retained | — |
| §7 | **up to five per turn** | O | §3.3 | superseded | V-3: §12a already set 5–8 |
| §7 | Principal decides; forward only | A | §1.4 | retained | — |
| §8 | dropped since v1 | H | report only | moved to history | V-10 |
| §9 | open items (both closed) | H | — | moved to history | V-10 |
| §10 | theory summary, depths, new-concept counts | A | §11.1 | retained, extended | three-part report |
| §10 | closing checklist; commit with checks | A | §11.2–3 | retained | — |
| §11 | branches: when, snapshot, logging, status, predictions, search, two counts, enforcement | A (dormant, V-8) | §9.3, §7, §12 pointer to 32a | merged into theory turns | the mechanics and scripts are unchanged |
| §11 | "only when an open question blocks the current chapter" | A | §9.1 "question ready" | superseded | the question-driven trigger covers it; Q2 |
| §11 | one branch at a time; ~10 sources | A | §9.3 (≤ 8 sources) | **proposed change** | one limit for all theory turns; Q2 |
| §12 | chronological reading for coverage | A | §3.1 | retained | — |
| §12 | topic turns under T2 conditions | A/D | §9 | merged into theory turns | V-9; each T2 condition mapped in §3.2 |
| §12 | consolidation every 25; two reviewers; compare both/one | A | §8.1, §8.5 | retained | — |
| §12 | planted-gap test (all points) | A | §8.3 | retained; isolation added | V-11 |
| §12 | C10 evidence quote | H | METHOD-VERSIONS entry 48 | moved to history | already there |
| §12b | cadence "after every second window" | A | §9.1, §9.5 | **superseded** by question-driven turns + report line | requirement 1 of the brief |
| §12b | what a turn delivers (a/b/c); how (8 sources, topic-full, two reviewers); reserved questions | A | §9.2–9.4 | retained | — |
| §12b | first theory turn F22 | H | — | moved to history | done (TT1) |
| §12c | whole-book review: when, scope, split, two per part, planted gap, integration review, result | A | §8.1, §8.3, §8.4 | retained | — |
| §12c | TARGET-TOC | A | §9.6 | retained | — |
| §12d | dependency graph (all points) | A | §8.4 | retained | — |
| §12a | window 5 + ≤3; T3 closed; 10 only for series | A + H | §3.3 | retained; T3 evidence to history | — |
| §12a | added source conditions; earliest and latest | A | §3.4 | retained | — |

### 3.2 Obligations outside the rulebook

| Source | Obligation | Class | v2 home | Action |
|---|---|---|---|---|
| D-C1 conditions 1–7 | allowed scope; exclusions; protections; TODO not assumption; residual risk | A | §2.1, §7 | retained (scope list stays in the access record) |
| AR-11 / T2 items 8–9 | reviewer access entry before every review | A | §2.4 | **moved into the rules** (was only in a trial file) |
| T2 item 2 | sources by a rule fixed before reading | A | §3.4 / §9.3 logged search | merged |
| T2 item 3 | sources already read included | A | §9.3 | retained |
| T2 item 4 | fork open in first draft | A | §9.3 | retained |
| T2 item 5 | strength scan | A | §4.3, §8.2.5 | **moved into the rules**, for every review (current practice, V-15) |
| T2 item 6 | coverage pass before writing | A/D | §5.2 (book search + top 5) | merged; Q6 if the Principal wants it kept separately |
| T2 item 7 | reviewer commissioned once, step 2 sent once | A | instructions ("deliver once"); §8.5 independence line | merged |
| CV-1 (21, 22, 32a) | CON-IDs; three statuses; only the reviewer validates; `same_subject_as` | A | §7 | retained |
| SQ-1 | defined before used; SEQ-NNNN gaps in TODO | A | §6.5, §7, §8.2.6 | retained; made checkable |
| ML-1, ML-2, 2a, 2b | chapter folders; review folders per chapter; all-chapter reviews in `manuscript/review/` | A | §6.2; folder practice | retained as practice (no change) |
| D-R5 / L-4 | continuity: previous and new text, reason, impact | A | §6.2 change log; old text in review folder | merged; Q3 on `BASELINE-REVISIONS.md` |
| 24b | forward only, no back-fill | A | §1.4 | retained |
| RK-1 | at most two sources per turn | O | — | superseded (by v1.3 already) |
| RK-1 | one home per fact | A | the whole of v2 | retained as principle |
| M-04 v1.0, AR-1…12, L-0a…L-15 | earlier method | H | — | history (since v1.1 header) |
| Amendment 32a/32b | branch mechanics and enforcement | A | §9.3, §12 pointer | retained unchanged |
| PT-001 | testimony apart from corpus | A | §4.1, §4.2 | **moved into the rules** (was practice) |
| Brainstorm folder = external | direction, not evidence | A | §2.2, §4.1 | retained |
| Repository CLAUDE.md | session log, CONTEXT | A | §11.4 | retained, de-duplicated |

### 3.3 Where v1.3 §6 (moved) lives in `REVIEWER-INSTRUCTIONS.md`

| v1.3 §6 point | In the instructions |
|---|---|
| Q1 matches what came before, in order | step 3 "Consistency and order" |
| Q2–Q4 mathematical, statistical, computational validity | step 3 "Mathematics and statistics"; computation section |
| Q5 explained more clearly | **not stated** → [R] add one line to step 3: "Definition: … and is the explanation clear enough to apply it?" |
| Q6 coverage, from independent examination | steps 1–2 |
| "not applicable, with a reason" for Q2–Q4 | **not stated** → [R] add to step 3 |
| six assessment points | steps 2–3, verdicts, report |
| division of labour (no omission log for the Professor) | implicit → [R] keep one line in the instructions' Role |

**[R]** So moving §6 needs **three short additions to `REVIEWER-INSTRUCTIONS.md`**, plus three from v2 (§3.4 below). They are proposed, not applied.

### 3.4 Proposed additions to `REVIEWER-INSTRUCTIONS.md` (not applied)

1. Q5 clarity line; "not applicable, with a reason"; the division-of-labour line (§3.3).
2. **Severity critical** uses the v2 §10 definition, and the scale is written as critical / high / normal in every report (V-4).
3. **Undefined terms:** read the brief's undefined-term list; report each term used without a definition in the book.
4. **Computed results:** read the cited script and output; check that the model's objects are distinct and the result's dependence on pool and granularity is stated.
5. **Planted-gap isolation:** do not open the original files of sections under test; do not compare files; say so if you did.

## 4. The evidence model

**Problem [V]:** the labels mix two questions. Src and Ext say *where* evidence comes from; Ded, Int and Hyp say *how strong* the claim is. Nothing in v1.3 covers repository records (code, configuration, records outside the allowed list), and D-C1 does not authorize reading them (V-14).

**Proposal [R]** (v2 §4):
- **two axes:** provenance (corpus · repository · external · Principal testimony · own reasoning) and epistemic status (Src · Ext · Repo · Ded · Int · Hyp);
- **no relabelling:** the existing labels keep their meaning; Repo is the only new label;
- **corpus first:** a claim about what the corpus says rests only on corpus anchors; other provenance corroborates, challenges, sharpens or fills a gap, and is labelled as such;
- **implementation vs intent:** repository evidence shows what is built; the corpus often shows what was meant. A divergence is recorded as a finding or a fork, never as a correction of the source;
- **computed results** cite the script and the output (V-13).

**Not decided by this proposal:** whether repository records may be read at all, and which paths (Q1). Until the Principal decides, v2 §2.2 allows none.

## 5. Concept coverage

**[V]** The existing records already hold every disposition that is needed: register (concept status), theory map (forks), chapter TODOs (open questions, SEQ gaps), step-8 lines and the graph (dependencies), and the reviewer's COVERED/MISSING/… marks (coverage).

**Gap [V]:** the reviewer marks blind-inventory items MISSING or DISTORTED, but there is no rule that each such item receives a recorded outcome. The settlement records findings, and most MISSING items become findings, but a low-ranked item in an appendix can drop without a trace.

**Proposal [R]** (v2 §7): **no new register and no lifecycle.** One table says which record holds each kind of item. At each settlement, every blind-inventory item not COVERED gets one disposition: added (§) · fork · TODO · excluded (reason) · deferred (to which turn). The count of dispositions is the concept-coverage line of the end report.

## 6. The three requirements

| Requirement | v2 | Safeguard against misuse |
|---|---|---|
| **Theory turns by question, no quota** | §9.1: a turn opens when a question is ready (a "missing" outline entry or an open fork, with enough evidence or a logged search); the question and its completion condition are named first | §9.5: each window report says whether a question is ready, and why none was taken if it has waited two windows. A report line, not a quota |
| **Critical foundational problem** | §10: narrow definition (primitive/axiom; a reviewed VALIDATED concept with dependants; a law or derivation step; a chapter conclusion), evidence and downstream list; the next turn investigates it; dependent work stops | reading position preserved by the depth rules already enforced in `close_source.py`; excluded: local errors, form, source defects, omissions that change no result |
| **Completion reporting in three parts** | §11.1: source coverage · concept coverage · theory maturity per deliverable (not built · sketch · PROVISIONAL · VALIDATED) | never merged into one figure |

## 7. Workflow simplification, and where each control is preserved

| Removed or merged | Unique information | Control function | Preserved in |
|---|---|---|---|
| v1.3 §6 review mechanics | six questions, assessment list | the reviewer does what was asked | `REVIEWER-INSTRUCTIONS.md` + three lines (§3.3) |
| history paragraphs in §8, §9, §12, §12a, §12b | dates, evidence | why a rule exists | `METHOD-VERSIONS.md` entries 28–53; this report |
| topic turns, branches, theory turns as three rules | conditions of each | out-of-order reading never moves the position; logged search; review | v2 §3.4, §9.3; enforced by `close_source.py`, `branch_search.py` (unchanged) |
| access record closing line | "inventory saved; settled"; commissions and messages | evidence of independence order | settlement independence line (§8.5). **The opening entry, written before the reviewer starts, stays** (it proves the scope came first) |
| session-log paragraph per window | findings, measures | a day's narrative | one line + settlement and commit. The settlement and the commit already hold the content |
| CONTEXT update per window | — | current state | only when direction or state changes (as the repository checklist already says) |
| `STEP8-AND-SCAN.md` | book search, step 8, strength scan | the reviewer gets the dependencies | renamed `BRIEF.md`, same content + undefined-term list |
| T2 as a live rule file | topic-turn conditions | — | v2 §9.3; T2 stays as history |

**Kept unchanged on purpose [R]:** the blind inventory and the hand-back before comparison; one or two reviewers per review as now; the consolidation every 25; the whole-book review and its integration review; the planted-gap tests; step 8 and step 9; the dependency graph; quote checks; the read log; commits.

**Reconstruction test [R]:** from v2's records one can still rebuild, per window: what was read and how (read log); what the reviewer saw first and when (access entry + saved inventory + settlement independence line); what was found and done (review report + settlement); what changed in the book (change log + commit diff); what was validated by whom (register `reviewed` column + settlement).

## 8. Regression against known failures

| Failure | What happened [V] | v1.3 safeguard | v2 safeguard | Does it help? | Remaining risk |
|---|---|---|---|---|---|
| **Kernel modelling error** | "minimal kernel not unique" came from a toy model where `grant2` was the same function as `grant`; found as critical in C7 (`review/C7…/REVIEW-REPORT.md` finding 1) | trivial-case test (added later, entry 40); independence models in the instructions | §4.2 computed results: script and output go to the reviewer; objects asserted distinct; pool and granularity stated; instructions line 4 | **[A] yes for this failure:** the reviewer of C7 found it by reading the code; v2 makes that reading required. The trivial-case test alone would **not** have caught it (a duplicate passes every trivial case) | a model can be wrong in its choice of pool or cut, not only in its code: that is a modelling judgment that review can only challenge |
| **"lane", "BC-7" undefined** | the Professor left both undefined in §5.5 C6 and §6.5 as a test (C11 settlement L79); **neither** consolidation reviewer named them (PLANTED-GAP-RESULT L22) | instructions: "uses a concept before it is defined"; ubiquitous language | §6.5 defined-before-used; §8.2.6 **undefined-term list by script** in the brief; instructions line 3 | **[A] partly.** Codes like BC-7, I-4 are easy to list by pattern. Ordinary words used as jargon ("lane") are hard; a frequency comparison of book terms against the register and glossary gives candidates, not certainty | lowercase jargon; the script does not exist yet; untested (the trial should plant one of each) |
| **Settlement-introduced defects** | T3's settlement introduced 3 defects that no reviewer saw until the consolidation (entry 51) | step 9 (entry 51) | §8.2.2, unchanged | **[V] the rule is applied:** the C16 brief listed the C15 and TT3 changes. **[A]** whether it now catches such defects is not yet measured | the last settlement before a whole-book review is checked only by that review; a settlement of a critical investigation (§10) is checked by the next brief, as any other |
| **Compromised planted-gap test** | originals reachable; reviewer B diffed; `book_search.py` hit the originals; omission scores not blind (PLANTED-GAP-RESULT) | none in the rules (V-11) | §8.3 isolation; instructions line 5; tool option needed (V-12) | **[A] partly.** It removes the accidental route (search) and the open route (diff). Reviewers keep shell access, so it rests on process independence and on the reviewer reporting a breach | a reviewer that reads an original without saying so cannot be detected by the rules; **Q5** asks whether the originals should be moved out of reach for the test's duration |

**[A] New risks from v2 itself:**
- **Question-driven turns may come later than every second window.** If the Professor never finds a question "ready", theory work could lag reading. §9.5 makes that visible; the trial measures it.
- **Moving §6 into the instructions** risks a reviewer not seeing a point that used to be in the brief. §3.3 lists each point; three need adding.
- **Renaming `STEP8-AND-SCAN` to `BRIEF`:** **[V]** no script in `tmp/tools/` refers to the name (`grep STEP8`: no match).

## 9. Efficiency estimate and limited trial

### 9.1 Estimate

| Item | Basis | Estimate |
|---|---|---|
| rule text the Professor must hold | v1.3 4,135 words → v2 revision 1 4,368 words (**+6 %**; revision 0 was −17 %); but no reading of T2, the archive or the register for live obligations, and the review mechanics of v1.3 §6 are no longer stated twice | **[V]** the word counts; **[A]** the effect on time. Revision 1 buys precision (terms, acceptance contract, four claim questions), not brevity |
| records per window | the closing line, session-log paragraph and CONTEXT update go; one line added (disposition table) | **[A]** 1–2 minutes of ~8–10 Professor minutes |
| theory turns | fewer forced turns where no question is ready, but possibly more urgent ones (§10) | **[A]** not predictable; could go either way |
| review time | unchanged (C16: 549 s harness) | **[V]** for C16 |
| overall | — | **[A] about 10–15 % of Professor time per window; about 5–8 % of total wall time**; over the ~2,640 remaining sources (≈ 530 windows at about 15 min) that is roughly **8–20 hours of about 250** |

**Assumptions:** Professor time per window ~8–10 min and review ~6–9 min (my earlier timing, not a measurement of every window); administrative share ~25 % of Professor time (an estimate from the artifact list, V-15, not measured); window size stays 5–8. **The Professor's own time is not recorded today**, so the estimate cannot be verified; v2 §8.5 adds that field.

**Plainly:** v2 mainly buys **clarity and fewer misreadings**, not speed. The large time costs are reading and review, which the principle says not to cut.

### 9.2 Proposed trial [R]

- **When:** after the whole-book review at S0100 has been settled under v1.3 (it is already prepared under v1.3; changing rules during it would mix two regimes).
- **Scope:** two reading windows (S0101 onward) and the theory turn that v2 §9 opens, if one is ready. v2 is the working text; v1.3 remains the reference for anything v2 does not say.
- **Before the trial:** the undefined-term script and the `book_search.py` exclude option exist and are tested on a known case (BC-7 in the pre-§6.5.2 text).
- **Plants (by the Rule Reviewer, key sealed), one per known failure class:** one undefined code and one undefined ordinary word used as jargon (a concept missed in reading); one unsupported generalisation; one downstream claim left unassessed after a change; one defect introduced in a settlement change. **The test shows how the rule handles each case, not only that the rule mentions it.**

**Acceptance criteria, in three separate groups** (revision 2). Efficiency never makes up for a failure in group A.

*A. Research integrity (any failure: the trial fails, return to v1.3):*
1. **the plant designated critical is found and handled correctly**, whatever the total score;
2. at least 4 of the 5 plants are found;
3. **every missed plant is analysed:** why it was missed, and which rule should have caught it;
4. **finding is not resolving:** for each found plant, the settlement shows the correct treatment (the claim restated, downgraded or moved; dependants reassessed), checked against the sealed key by the Rule Reviewer;
5. no defect is found later that a v1.3 safeguard removed in v2 would have caught.

*B. Process compliance (a failure is corrected and the window repeated once; a second failure ends the trial):*

6. the closing checklist shows every v2 obligation done or excused with a reason;
7. every blind-inventory item not COVERED has a disposition, and the top-five hits are each marked same / extends / different (the merged coverage pass, Q6);
8. the end report has its three parts;
9. every finding carries a severity on the one scale, with a one-line rationale.

*C. Operational efficiency (measured, never estimated; informs the decision, does not decide it):*

10. Professor harness time and reviewer harness time per window are recorded;
11. the administrative records take less time than in C16.

**Diagnostic, not a target:** findings per window in C13–C16 were 14–17. A window well below that range is asked one question in the settlement: were the sources cleaner, or did coverage fall? The blind inventory and its dispositions answer it. The count alone decides nothing.

**Decision:** the Principal decides on the evidence: adopt, revise and trial again, or return to v1.3. The trial gives v2 no standing authority. If only C fails, v2 is clearer but not faster; the Principal decides whether that suffices.

## 10. Open questions for the Principal

| Q | Question | My recommendation |
|---|---|---|
| Q1 | May the Professor read **repository records outside the allowed list** as evidence (Repo)? | **ANSWERED** (Principal, 2026-10-19): **yes**; scope **implementation only** (`app/`, `config/`, `routes/`, `database/migrations/`, `tests/`, `scripts/`; excluded: `docs/knowledgeos/` outside the allowed list, sealed/hold-out, secrets, `*.sql`, `*.csv`, logs, `storage/`, `vendor/`, `node_modules/`); **applies with v2 only** (trial or adoption), not under v1.3. Recorded in the decision record; v2 §2.2 |
| Q2 | **Branches:** merge into theory turns (one limit of 8 sources, the same trigger), keeping the snapshot and prediction mechanics of 32a/32b? | yes. One mechanism, **two sizes**: a passage check (`branch-consult`, a few passages, for a small factual question) and a full theory turn (whole sources, a deliverable). Both name the question first, record reused vs new evidence, keep snapshots and predictions, and never move the position. More than 8 sources only with the Principal's approval. BRANCH-01's records stay as they are |
| Q3 | **One revision home:** is `BASELINE-REVISIONS.md` closed as history, with `NN.99-change-log.md` plus the old text in the review folder serving the L-4 continuity rule? | yes. **Reconstruction test [V], 2026-10-19:** for the three integrations so far (§1.12 in C16, §1.13 in C17, §1.14 in C18) the old text is kept in the review folder (`01.1x-OLD-before-integration.md`), and the change-log row gives the change, the reason and the review findings; the settlement holds the dependency reassessment. The change log holds chapter revisions only, not evidence or theory decisions |
| Q4 | **Severity scale:** require critical / high / normal (reports since C14 use major/minor)? | yes; one scale, all three levels defined in v2 §10.1, each finding with a one-line rationale. Critical triggers investigation and reassessment of dependants, never their automatic invalidation |
| Q5 | **Planted-gap isolation:** is the commission instruction plus the tool option enough, or should the originals of tested sections be moved out of the reviewers' reach for the test's duration? | instruction + tool exclusion **+ a statement in each report** that the isolation procedure was followed, or how it was breached. Isolation is a control under test, not a proven safeguard. Moving the originals out of reach for the test's duration if a breach recurs, or from the start if the Professor pauses during the review |
| Q6 | The T2 "coverage pass before writing": merged into the book search and top-5 reading, or kept as its own step? | merged |
| Q7 | **Approval form:** approve v2 as a trial (§9.2) first, then adopt; or adopt directly? | trial first |
| Q8 | The register's duplicate entry 38 (V-5): add a correction entry like 24-corr? | yes, at the same time as the v2 entry |

## 11. Recommendation

**[R]** Approve **Rulebook v2 for the limited trial in §9.2**, to start after the S0100 whole-book review is settled, together with the answers to Q1–Q6 and the proposed additions to `REVIEWER-INSTRUCTIONS.md` (§3.4). Adopt v2 as the operating rules only if the acceptance criteria hold. Until then, v1.3 and the instructions stay in force unchanged.

## 12. What this report did not do

- **Did not change** `RULEBOOK-v1.3.md`, `REVIEWER-INSTRUCTIONS.md`, `METHOD-VERSIONS.md`, any amendment, trial or record.
- **Did not delete or move** any historical file.
- **Did not read** sealed or hold-out material, or any corpus text.
- **Did not verify:** the Professor's per-window time; whether step 9 has caught a defect since it was introduced; whether reports C14–C16 kept the main part under ~1,200 words (their total length is 2,145–3,202 words including the appendix).

## 13. Revision 1 (2026-10-19): the architecture review's refinements

The Principal asked whether I agree with a further architecture review, then said "yes revise the RULEBOOK". This revision applies the points I agreed with, to the proposal only.

### 13.1 Verified in v1.3 [V]

| ID | Finding | Evidence |
|---|---|---|
| V-17 | **The generalisation rule lets support change the reasoning type:** "Only then may it be written as Ded or as a law" after "a second, independent source or a proof" | `RULEBOOK-v1.3.md` L60 |
| V-18 | **"Theory turn" has two meanings:** §6.5 "one theory-only turn: settle the findings"; §12b a scheduled investigation | L144; L210–224 |
| V-19 | **Earlier topic sources are only "checked against the book"** when index order reaches them; a misreading already in the book would be confirmed, not found | L194, L269 |
| V-20 | "No new claims" in the two-step rewrite has no route for a needed substantive correction | L72 |

### 13.2 What changed in the proposal

| Review point | v2 revision 1 | Verdict |
|---|---|---|
| 1 coverage of earlier topic sources | §3.1: re-read blind in the window that reaches it; disposition against the source; predictions scored | adopted |
| 2 terminology | terms table at the top; a settlement is not a theory turn | adopted |
| 3 claim metadata | §4.1: four questions (reasoning type, provenance, review status, authority), none implying another; only the first two in the prose | adopted, kept light |
| 4 acceptance contract | §8.5: six conditions; open items with role, reason, next action; settled ≠ valid; commit ≠ correct | adopted |
| 5 "no new claims" | §6.3: editorial · evidence integration · substantive (marked and reviewed as new theory) | adopted |
| 6 blind | terms table and §8.2 sequence; before its inventory, the reviewer sees only the sources, `RESEARCH-PURPOSE.md` and its instructions | adopted, **without** "background context" from the Professor's work |
| 7 graph vs manual check | §8.2.4: one sentence on what each finds | already in v1.3 §12d; restated once |
| 8 generalisation | §4.3: restricted · empirical generalisation · derivation · conjecture; strength and scope justified by evidence and reasoning | adopted (fixes V-17) |
| three levels of progress | §11.1: corpus · theory · book | adopted, replacing revision 0's split |
| four purposes per rule | header of v2; table 13.3 | adopted |
| failure-class tests | §9.2 of this report: five plants | adopted |
| seven research states of a claim | — | **not adopted:** the multi-stage lifecycle you ruled out; three review statuses plus the reasoning type cover it |
| "the Rule Reviewer tests evidence" | — | **not adopted:** the independent reviewers test; the Rule Reviewer keeps the rules and prepares tests |
| "544 lines" | — | factual error in the review: v1.3 has 272 lines |

### 13.3 Which purpose each v2 section serves

| v2 § | Discover | Validity | Uncertainty | Communicate |
|---|---|---|---|---|
| 1 purpose and authority | | ✓ | | ✓ |
| 2 access | ✓ | ✓ | | |
| 3 reading | ✓ | | | |
| 4 evidence and claims | | ✓ | ✓ | ✓ |
| 5 cycle | ✓ | ✓ | | |
| 6 writing | | | ✓ | ✓ |
| 7 dispositions | ✓ | | ✓ | |
| 8 review, settlement | ✓ | ✓ | ✓ | |
| 9 theory turns | ✓ | ✓ | | |
| 10 critical problems | | ✓ | ✓ | |
| 11 end of turn | | | ✓ | ✓ |

No section serves none. **[A]** The administrative parts (§2.4 access entry, §11.3 commit, §11.4 session log) serve validity only indirectly, through reconstructability; they are kept for that reason (§7).

### 13.4 Still not changed

`RULEBOOK-v1.3.md`, `REVIEWER-INSTRUCTIONS.md`, `METHOD-VERSIONS.md`. The proposed additions to the instructions (§3.4) now also need: the blind sequence (v2 §8.2) and re-reading earlier topic sources blind (v2 §3.1).

## 14. Revision 2 (2026-10-19): a second architecture review

**[V] Its limits, as it states them:** it reviewed this report only, not `RULEBOOK-v2-PROPOSED.md` or the repository. Its "verified" points are therefore my verified points, not independently rechecked.

| Point | Done | Verdict |
|---|---|---|
| trial outcomes in three groups (integrity · compliance · efficiency); efficiency cannot make up for integrity | §9.2 | adopted |
| a critical plant must be found, whatever the score; missed plants analysed; finding ≠ resolving | §9.2 A1–A4 | adopted |
| C13–C16 findings range as a diagnostic, not a target | §9.2 | adopted (the earlier wording read like a target) |
| Q2: one mechanism, different sizes | §10 Q2 | adopted; v2 §9.3 already allowed passage consultation |
| Q3: test reconstruction before closing the old file | §10 Q3 | **tested [V]**: passes for the three integrations so far |
| Q4: define all three levels, rationale per finding | v2 §10.1; §10 Q4 | adopted (high and normal were defined only in the instructions) |
| Q5: attestation of isolation | §10 Q5 | adopted |
| Q6: merged, but verified in the trial | §9.2 B7 | adopted |
| Q8: correction with lineage, by timestamp | §10 Q8 | already the proposal (like 24-corr) |
| the seven invariants and five domain concepts | — | agreed; each is already a v2 rule (§3.1, §4.2, §4.1, §7, §8.4, §8.5, §1.4). Not added as a separate list, to avoid stating them twice |

**[V] State at this revision:** the reading position is S0100; review C18 (S0097–S0100) has started. **The whole-book review is next**, under v1.3. Its planted-gap test is mine to prepare (§12c); I will apply the isolation lessons to it under v1.3, since they are lessons of a past test, not a v2 rule.
