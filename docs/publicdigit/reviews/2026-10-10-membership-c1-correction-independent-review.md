# Membership C-1 acceptance correction (PBDIGIT-268): independent review

**Commission:** the PO's "Independent Review of C-1 Acceptance Correction" (2026-10-10). On the PO's instruction, the review was done by a **fresh-context subagent** that did not author the work. It was briefed with a DDD and requirements-engineering mindset, worked read-only, and could run tests and the sensitivity kit.
**Status:** independent findings for the Principal Reviewer and the Human PO / ARB. **This document does not accept C-1** (H-6).

> **Append-only record.** §1 reproduces the reviewer's report **verbatim**. §2 is the developer's note, kept separate and limited to confirming or contradicting facts. It does not soften findings.

## 1. Independent reviewer's report (verbatim)

### A. Repository state and commits reviewed
Branch `knowelegeos-modelling`, HEAD `d90bf8af9`. Four commits after `5765b9fa3`: `9ae69b7bc`, `fccf5b778`, `e96d12a5d` (tests + kit), `d90bf8af9` (docs); all tagged PBDIGIT-268, all stating "no RED phase". `git diff --stat 5765b9fa3 HEAD -- app/` is **empty**; test-side changes are only `AnnulVoidTest.php` (+86) and `C1PropertyTest.php` (+61). `git status --porcelain -- app tests` was empty before, during and after my runs.

### B. Traceability

| Criterion | Contract | Code | Test | Evidence | Verdict |
|---|---|---|---|---|---|
| K-E1 Annul | §14.3 K-E1, §3.1 resulting fact | `Member.php:117-140`, `accept` :159-167 | `test_an_accepted_annul_carries_its_fact_data` | log built with `fromLog` → the Annul is the only decision; asserts type, id, target, provenance (whole object, `registrar-2` ≠ setup), decidedAt 900, basedOnVersion 1; values are compared against inputs, not guaranteed by immutability; M1/M3/M4 killed | MET |
| K-E1 Void | §3.2 (decidedAt optional) | `Member.php:143-156` | `test_an_accepted_void_carries_its_fact_data` | decidedAt 900 **and** null; attested provenance; M2 killed at :268 | MET |
| Guard 3 < 4/5/6 | C-1 plan "Refusal triggers" | `Member.php:126-137` | `test_decided_at_is_checked_before_…` | all three inputs make both conditions apply (decision-provenance Void; attested live Admit; voided decision-provenance Admit); M5 killed at the first sub-assertion; the other two discriminate by construction | MET |
| Guard 4 < 5 | same | :129-134 | `test_annul_of_void_is_refused_before_the_provenance_check` | the Void is attested and live; M6 killed | MET |
| Guard 5 < 6 | same | :132-137 | `test_provenance_is_checked_before_liveness` | the Admit is attested and voided; M7 killed | MET |
| K-H1 / P-KNOWN | §5, §14.3, §14.4 | `Member.php:78-83` | `test_known_at_equals_the_oracle_and_is_prefix_stable` | (i) reference = the g6 port's own `live` / `resolve` with an independently computed "≤ τ" cut; (ii) the literal §14.4 metamorphic relation; M8 and M9 killed at the intended τ | MET within bounds |

### C. Findings
1. **NON-BLOCKING: the runner can report a false KILLED.** `run.php:94` treats any non-zero exit as KILLED. It does not distinguish assertion `Failures:` from `Errors:`, fatals or patch-induced crashes, which is exactly what the developer hit twice (the M3 setup crash; the M9 non-existent `isBefore`). Guide 05 relies on a human reading the summary.
   - The test filter `'/name$/'` is not start-anchored, and the control does not verify that exactly one test ran. Today every name is unique (I checked).
   - `patch` runs with default fuzz.
   - The porcelain before / after check cannot detect changes to already-dirty files.

   I manually confirmed that all 10 current kills are genuine assertion failures (see E).
2. **NON-BLOCKING: the kit deviates from the approved plan without disclosure.**
   - CP-2 specified `git archive HEAD`; the runner uses `cp -r app tests`, so it tests the working tree, not HEAD.
   - The risk table says "the runner aborts if M0 survives"; it does not abort, it only marks the run failed and continues.

   Neither invalidates today's evidence (the tree was clean), but both are undisclosed changes to an approved design.
3. **INFORMATIONAL: the canary is valid but not the strongest proof.** The bootstrap autoloader is registered with prepend, so it runs before Composer's classmap; Composer's `files` autoload loads only `app/Helpers`. The stronger proof is control-pass then post-patch fail with an unchanged repository, which shows the patched scratch copy is the code under test.
4. **INFORMATIONAL: K-E1 provenance is only partly pinned.**
   - §3.1 says `provenance(decision, authority, reason)`. `Provenance` has no reason field, and the test passes no `AuthorityEvidence`, so a mutant dropping authority evidence would survive. CQ-3 is OPEN, so this is C-3 territory.
   - The Void test is not sensitivity-checked against M3; it shares the `accept` path.
5. **INFORMATIONAL: K-H1 is coupled to production in two places.**
   - The history domain comes from kernel acceptance (`C1Histories::accepted`), mitigated by K-X2 verdict agreement.
   - Relation (ii) compares production with production, which is inherent to a metamorphic relation.
   - Lifetimes are compared on 0..H-1. "Between" τ values do not exist because the clock steps by 1.
6. **INFORMATIONAL: the documentation is accurate.**
   - The story, README row, both reviews, guides 04 / 05 and the plan say AWAITING REVIEW, not accepted.
   - Persistence is described as unverified, F-1 → C-4 (H-3), `VoidFact` is consistent, and no RED is claimed.
   - The README row's "(TDD; …)" is a mild simplification; the story text carries the qualification.
7. **INFORMATIONAL: unrelated test errors in the wider Membership directories.** Running the whole Membership unit directories gives 27 errors in Domain (Committee / Fee / Member) and 29 in Application (Committee / Membership). Those files are untouched in `5765b9fa3..HEAD` (TenantUserId format, handler constructor arity), so they are outside C-1. The documented counts refer only to the `Organisational/` subdirectories.

### D. Commands executed (actual results)
- `run.php` (with `C1_SENSITIVITY_SCRATCH=/tmp/c1rev/kit`): M0–M9 all KILLED, control OK for each; "working tree app/ tests/: unchanged"; exit 0. Each patched scratch test was re-run to read its failure message (E).
- `phpunit … Domain/Organisational`: OK (89 tests, 112487 assertions).
- `phpunit … Application/Organisational`: OK (34 tests, 93 assertions).
- Architecture: Purity OK (5 / 11), Slice1a OK (4 / 4), Slice1b OK (3 / 3), i.e. 12 tests, 18 assertions.
- `phpunit` full `Membership/Domain`: 265 tests, 27 errors; full `Membership/Application`: 81 tests, 29 errors (the unrelated errors in C7).
- **Not run:** Infection (the 92 % / 95 % figure is the developer's claim only), PostgreSQL, PHPStan.
- I could not delete `/tmp/c1rev` (permission denied); it is a /tmp scratch only.

### E. Sensitivity

| M | Intended defect | Control | Patch | Test | Result | Failing assertion |
|---|---|---|---|---|---|---|
| M0 | canary: `Annul::target()` returns its own id | OK | applied | annulling removes period | Failure | periods expected [] but A1 present (3 deprecations = PHP 8.5 vendor diff noise) |
| M1 | decideAnnul drops decidedAt | OK | applied | annul carries data | Failure :248 | decidedAt null |
| M2 | decideVoid drops decidedAt | OK | applied | void carries data | Failure :268 | decidedAt null |
| M3 | basedOnVersion + 1 | OK | applied | annul carries data | Failure :249 | "2 is identical to 1" (no setup crash) |
| M4 | id ↔ target | OK | applied | annul carries data | Failure :245 | 'X1' vs 'A1' |
| M5 | DecidedAt guard moved last | OK | applied | decidedAt order | Failure :278 | expected DecidedAtRequired, got AnnulOfVoidRefused |
| M6 | guards 4 / 5 swapped | OK | applied | annul-of-void order | Failure :293 | got TargetHasNoDecisionProvenance |
| M7 | guards 5 / 6 swapped | OK | applied | provenance order | Failure :303 | got TargetNotLive |
| M8 | knownAt ignores τ | OK | applied | K-H1 | Failure | "(i) τ=1000 after A0": A0 known before it was recorded |
| M9 | ≤ → < | OK | applied | K-H1 | Failure | "(i) τ=1001 after A0": boundary record excluded |

### F. Limitations
- D-COR-2 is not in the independent Python oracle: the developer-written wrapper adds it, and the PHP port mirrors it.
- Equal recordedAt values are not enumerated.
- The evidence is bounded (H = 5, length ≤ 5), not a proof.
- PostgreSQL was not run, so persistence is unverified. F-1 remains an open C-4 entry obligation.
- Infection was not re-run by me. The kit's KILLED label needs human reading of the summary.

### G. Recommendation: **ACCEPT WITH EXPLICIT CONDITIONS**
No BLOCKING findings. Conditions before formal acceptance:
1. The PO / ARB acknowledges the undisclosed kit deviations in C2 (working-tree copy instead of `git archive HEAD`; no abort on a surviving M0). Either record them in acceptance review §11 (append-only), or open a backlog item to make the runner distinguish Failures from Errors, enforce exactly one test, and abort on a surviving M0. Do not extend this story.
2. Restate the C4 gap (authority evidence / reason in provenance) as a C-3 entry criterion alongside the CQ-3 note.
3. Keep F-1 as an explicit C-4 entry obligation and the persistence verification gate on an isolated DB.

Formal acceptance of C-1 belongs to the PR, then the Human PO / ARB (H-6). This review does not accept it.

## 2. Developer's note (facts only, separate from the review)

- **Finding C2 is correct; I did not disclose it.** I implemented the runner with `cp -r app tests` (the working tree) instead of the planned `git archive HEAD`. I chose this so the kit could test *uncommitted* new tests during development. It continues after a surviving canary instead of aborting. Neither change was reported in §11 or in the plan's progress. Disclosed here.
- **Finding C1 is correct.** Distinguishing kills is manual today: guide 05 "How to add a mutant", step 3.
- **Finding C7:** the 27 + 29 errors are in pre-existing Membership code outside `Organisational/`. They match the ~21 known `L-7` constructor-drift failures noted in EPIC-02, now grown. They were not re-investigated here and are not C-1 related.
- **Leftover:** `/tmp/c1rev` (reviewer scratch, /tmp only) could not be removed by the reviewer.
- **No change was made in response to this review.** Applying conditions 1 and 2 needs the PO's authorisation.
