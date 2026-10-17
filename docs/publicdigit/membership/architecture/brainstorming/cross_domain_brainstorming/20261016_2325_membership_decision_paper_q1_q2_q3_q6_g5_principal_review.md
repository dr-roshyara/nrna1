# Membership: principal review of the decision paper (Q1, Q2, Q3, Q6, G-5)

**Author:** Session 1 (Architecture and Requirements Authority). **Status:** PROPOSED: a recommendation, not a decision. Nothing here is approved, assigned or authorised.
**Baseline:** branch `election-flow-updated`, HEAD `f54037f96` (2026-10-16).
**How this was produced:** read-only. No repository, database or ticket changes were made while producing the content. The only commands run were `git`, `grep` / `sed`, and `sha256sum -c` on the A1 evidence folder.
**Independence:** the paper under review was written by this same session. This document is a challenge of that reasoning, not independent approval.
**Supersedes:** the Q1 recommendation in the previous decision paper (session output, 2026-10-16). **One recommendation changes: for Q1, deferral is now recommended instead of acceptance** (§B-Q1).

---

## A. Executive recommendation

What the organisation should decide now, in this order:

| # | Decision | Recommendation |
|---|---|---|
| Q3 | Keep the reviewer's original recommendation on file | **Keep it in the official reviews area, clearly labelled "reviewer recommendation, not acceptance", and correct the reference to it** — after a privacy and content check and a placement check |
| Q2 | The lost 10 October test outputs | **Record them honestly as unrecoverable, keep their fingerprints as history, and use the 16 October run (A1) as the evidence for today's code** — without claiming A1 reproduces the old run |
| Q1 | Accept the C-1 correction rules? | **Defer acceptance briefly: authorise a small, test-first fix for V-2 inside the existing C-1 story, then accept with the remaining listed risks.** Deferral costs almost nothing, because everything that depends on C-1 is blocked by other open decisions anyway |
| Q6 | Safe Membership test database | **Commission a dedicated, access-restricted Membership test database; approve the request and name who sends it** |
| G-5 | The older Membership tests that fail | **Once the PO confirms scope and authority, name one engineer for a findings-only check: no fixes** |

**What these decisions authorise:** a documentation change (Q3), a recorded evidence disposition (Q2), one small database-free correction after the PO authorises it (Q1 as recommended), an infrastructure request (Q6), a database-free test investigation (G-5).
**What they do not authorise:** database tests, security tests or fixes, correction storage, Slice 2b, live use or cutover.
**Independent review:** not required to decide Q1, because deferral is safe under either reading of the contract. The Principal Reviewer reviews the fix as part of normal acceptance. If the PO / ARB prefers immediate acceptance (Option A), a focused review is needed first (§B-Q1).
**Main risks and blockers:** four live security risks cannot be tested until the Q6 database exists; whether the Membership tables already exist in production (OQ-01) is unconfirmed.

---

## B. Decision-by-decision paper

### Q3 — Preserve the reviewer's recommendation

1. **Business problem.** An organisation must be able to show *why* it made a decision. The reviewer's C-1 recommendation sits in an untracked working file, and the official decision record cites it under the wrong name. If the file disappears, so does the basis of the decision.
2. **Options.** (a) Store it officially and correct the reference. (b) Annotate the record with the real filename and a fingerprint, without storing the file.
3. **Preferred:** (a).
4. **Why.** Only (a) makes the basis of the decision durable and reviewable. The file is small (171 lines); a heuristic scan found no emails, credentials or personal paths.
5. **Consequences.** Approve: the C-1 verdict can cite a permanent source. Reject or defer: the verdict rests on a file that can vanish. Option (b) leaves a fingerprint that proves *which* text was relied on, but cannot recover it.
6. **Risk removed:** loss of decision basis. **Risk remaining:** the file refers to an attachment ("Eingefügter Text.txt") that is not included, so part of the reviewer's input is not preserved either way.
7. **Closing evidence:** the citation resolves to a tracked file; a status banner reads "reviewer recommendation, not an acceptance"; a person has confirmed the privacy check; the placement script has been run by an authorised session.
8. **Who.** The **PO** decides. An **authorised documentation session** carries it out (unassigned).
9. **Blocked until then:** a fully traceable Q1 record (recommended sequencing, not a mandatory gate).
10. **Proposed record entry:** "Q3: the reviewer recommendation of 2026-10-10 is preserved at ‹path› (sha256 ‹…›), labelled as a recommendation; the citation in the G3 record is corrected. Decided by ‹name›, ‹date›."

### Q2 — Close G-6, the lost test outputs

1. **Business problem.** The test outputs behind the original C-1 assessment were kept in a temporary folder and lost. An open gap that can never close distorts the record. Closing it must not pretend the outputs were recovered.
2. **Options.** The source itself offers two: copy the outputs into the repository (no longer possible) or accept the fingerprints as the reference. A third option is to leave the gap open.
3. **Preferred:** accept the fingerprints as the historical reference, record the outputs as unrecoverable, and cite A1 for today's code.
4. **Why.** It is honest, and it uses an option the source already named.
   - **What A1 proves:** the committed code at `83a059f98` passes the 27 scoped database-free test files (11 runs, all exit 0), covering C-1's acceptance criteria (K-A1 … K-X2), the 15,530-decision comparison against the oracle, and the architecture guards, under today's framework. A later test file was also checked at `9582de324`. The outputs are hashed and the hashes verify.
   - **What A1 does not prove:** the 10 October run on its own commit; database behaviour, HTTP behaviour or real data; the mutation score; the older Membership tests.
   - **Environment differences:** a stricter run mode (no project configuration loaded); Laravel 12 and php-parser 5.9.0; `--no-scripts` used without prior approval. That flag skips Laravel's package-discovery step. A1's test files are plain PHPUnit and don't boot Laravel (A1 README, verified), so the effect is expected to be nil, though not demonstrated.
5. **Consequences.** Approve: C-1 can be decided on existing evidence. Keep open: a permanently unclosable item.
6. **Risk accepted:** no record of the original run's full output.
7. **Proposed wording:** "G-6 closed: the E-RUN outputs of 2026-10-10 (`ce8bf3109`) are unrecoverable; their SHA-256 values in the G3 evidence index remain the historical reference. Evidence for the current code is A1 (`docs/publicdigit/membership/evidence/2026-10-16-a1-evidence-refresh/`, commit `f54037f96`, run at `83a059f98`), which does not reproduce the earlier run. The `--no-scripts` deviation is acknowledged."
8. **Who:** Human **PO / ARB** decides; an authorised session writes the disposition.
9. **Blocked:** nothing hard. Q1 is cleaner after Q2.

### Q1 — Accept or defer C-1 (PBDIGIT-268)

**Business problem.** C-1 lets the organisation set aside a wrong membership decision ("Annul") or mark a record as never true ("Void"). It is built and passes its tests. One gap, V-2, was found after review: when a decision is annulled, the system checks that the *original* decision was a proper decision, but does not check that the *annulment itself* is recorded as a proper decision.

**What the sources say (verified):**
- §3.1 describes the resulting record as `Annul{…, provenance(decision, authority, reason), …}`.
- C-AMEND-1 defers only "the meaning and representation of 'reason', and of the associated authority evidence" to the command slice (C-3). It does **not** defer that the record is a *decision*.
- §14.5 (contract `:244`) assigns "application, concurrency, persistence and tenant-scoping criteria" to C-3 / C-4. Whether the record is a decision is a **kernel** property, not one of those.
- No acceptance criterion (K-A1 … K-X2) tests it. So C-1 meets every written criterion but not every written contract sentence.

**Can the gap be reached today? Not today, but it is not structurally prevented:**
- Only the database loader creates non-decision records in application code (`ProvenanceJson.php:57-58`).
- The loader can only rebuild Admit and End facts (`PostgresMemberRepository.php:259`, `:270`); the database accepts only those two kinds.
- No correction command exists.
- **The protecting guard is weaker than the earlier paper implied.** The architecture test allows all three restricted calls in a shared list of three files (`MembershipDecisionCreationTest.php:38-42`), so `Member.php` or the repository could start creating non-decision records without any test failing.
- **"Not reachable now" therefore does not mean "prevented".**

**Option A — accept with conditions.**
- *Compliance:* meets every K-criterion; knowingly accepts a deviation from §3.1 that sits inside C-1's own (kernel) scope.
- *What the organisation declares:* "the correction rules are accepted although one stated rule is not enforced."
- *Enables:* COR-2 planning — but COR-2 is blocked anyway by BD-REV-ER and D-COR-3.
- *Needs:* a recorded condition with an owner, an enforcement point (C-3 / C-4 entry criteria), and a stronger guard to stop bypass. None of these exists yet.

**Option B — defer until V-2 is fixed and reviewed (recommended).**
- *Compliance:* complies under **both** readings of the contract.
- *What the organisation declares:* nothing yet; acceptance follows a small fix.
- *Blocks:* nothing that is not already blocked. COR-2 waits on other decisions. No mandatory gate (ARB-MEM-01 for cutover, the persistence gates) requires C-1 acceptance as a step.
- *Precedent:* C-1 already had a PO-approved acceptance correction (CP-1; C-1 acceptance review `:204`, `:228`). The fix can be a second correction inside PBDIGIT-268, so no new ticket is needed.

**Strongest counterargument against B.** It delays a formal acceptance the reviewer already recommended and adds a review cycle; the contract never turned this sentence into a criterion, so it can be argued to fall outside acceptance. **Answer:** the delay has no schedule cost today, and accepting a known deviation in the kernel's own scope sets a weaker precedent than a one-guard fix.

**Recommendation: Option B.** Authorise "C-1 acceptance correction 2":
- an additive contract annotation naming the refusal (proposed name `AnnulRequiresDecisionProvenance`) and its position in the guard order;
- RED-first kernel tests: refuse Annul with `rule` provenance and with `attested` provenance; precedence checks; existing behaviour unchanged;
- the comparison against the oracle still shows 0 mismatches over 15,530 decisions, and the G6 fingerprint is unchanged;
- then Principal Reviewer review, then acceptance with the remaining residual risks: the oracle doesn't cover one refusal rule (D-COR-2); equal record times are not exercised; the mutation score was produced by the developer only; the test-runner weaknesses tracked in PBDIGIT-269; storage is not built yet; the `--no-scripts` deviation.

**If the PO / ARB prefers Option A:** first commission a focused Principal Reviewer review. It must answer one question: *is the Annul's decision provenance a C-1 kernel obligation under §3.1, read with §14.3, §14.5 and C-AMEND-1, or an application-boundary obligation for C-3?* Then record the condition as an annotation to the C-3 / C-4 entry criteria, adopted by the PO / ARB (owner to be named). Close it with the same RED / GREEN evidence, and tighten the guard to per-rule allowlists.

**Who decides:** Human **PO / ARB**. The work is done by an authorised developer session, then reviewed by the Principal Reviewer.
**Blocked either way:** correction storage (C-4), correction commands (C-3), database work, live use.
**Proposed record entry:** "Q1: C-1 acceptance deferred pending acceptance correction 2 (V-2: the Annul must carry decision provenance), authorised ‹date›. Acceptance to be reconsidered on its evidence and the Principal Reviewer's review."

### Q6 — Dedicated Membership test database

1. **Business problem.** Membership's database layer and the security investigation must be tested where a mistake cannot touch shared or production data. No such database exists for Membership.
2. **Options.** (a) A dedicated `nrna_test_membership`. (b) Reuse `pbdigit274_isolated_test` under new written approval. Its current approval covers PBDIGIT-274 and PBDIGIT-286 P5 only (reported, not re-verified).
3. **Preferred:** (a).
4. **Why.** It gives clean isolation and its own account, and shares no timing or data with another workstream. Option (b) would need its own approval and still mixes workstreams.
5. **Consequences.** Approve: the critical-path blocker starts to clear. Defer: the database layer, Slice 1b acceptance, Slice 2b, correction storage and the security investigation all stay blocked.
6. **Safeguards required (Part 2 plan, verified):** a permanent identity label on the database; the database ID; a dedicated account that owns only this database and **has no access to `nrna_test`**; server and connection details passed through the environment, never written down; a pre-connect safety check that reuses the PBDIGIT-283 rules.
7. **Sequence (Part 2 plan `:309-310`, `:330-331`):**
   1. D-2: name who sends the request and to whom (OPEN).
   2. Infrastructure provisions the database and removes shared access (I-1). The impact on other `nrna_test` users is assessed, not revoked silently.
   3. Infrastructure supplies the provisioning evidence.
   4. The Principal Reviewer reviews it.
   5. The Human PO / ARB accepts it.
   6. The ARB re-checks ARB-SEC-01. It failed on 2026-10-09 because no such database existed.
   7. Test execution is separately authorised (P2-6).
8. **Who:** ARB, PO and Infrastructure decide; Infrastructure performs the work; the requester (D-2) is unassigned.
9. **Blocked until step 7:** every database test, the security characterisation, migrations.
10. **Proposed record entry:** "Q6: a dedicated Membership test database is approved for request; requester ‹name›, recipient ‹team›. Execution remains subject to evidence review and ARB-SEC-01."

### G-5 — Wider Membership test suite

1. **Business problem.** Are the reported failures real product defects, outdated tests, or environment problems? Nobody knows, and nobody owns the question.
2. **Options.** (a) A bounded, findings-only investigation. (b) Leave it.
3. **Preferred:** (a), assigning an engineer only after the PO confirms scope and authority.
4. **Why.** It is cheap, carries no database risk, and turns an unknown into a classified list.
5. **What is actually known.** The counts — 27 Domain and 29 Application errors (out of 265 and 81 tests) — come from the independent reviewer's 2026-10-10 run (`docs/publicdigit/reviews/2026-10-10-membership-c1-correction-independent-review.md:48, :55, :93`). That review attributes them to constructor drift in older Committee, Fee and Member code ("L-7"). **They have not been re-verified by Session 1, and they predate Laravel 12.**
6. **Investigation bounds:** a named commit and a `git archive` copy; database-free files only, with the selection rule written down; exact commands recorded in advance; `--no-scripts` approved before installation; no fixes of any kind; a report classifying each failure with its evidence and likely cause, plus a follow-up proposal.
7. **Closing evidence:** the stored report and a PO-named follow-up owner.
8. **Who:** the **PO** decides and names the engineer (unassigned).
9. **Blocked:** any fix to those tests.
10. **Proposed record entry:** "G-5: findings-only characterisation authorised; scope ‹…›; engineer ‹name›; no fixes."

---

## C. Evidence and assumptions register

| Claim or question | Source | Status | Support | Uncertainty | Impact |
|---|---|---|---|---|---|
| §3.1: the Annul record carries decision provenance | correction contract §3.1 | verified | text read | — | Q1 |
| C-AMEND-1 defers only "reason" and its authority evidence | contract, C-AMEND-1 C-3 items 3–5 | verified | text read | — | Q1 (B) |
| §14.5 assigns application, concurrency, persistence and tenant criteria to C-3 / C-4 | contract `:244` | verified | text read | does not mention provenance kind | Q1 |
| No K-criterion tests the Annul's own provenance | §14.3; `AnnulVoidTest` | verified | criteria list; earlier conformance reviews | — | Q1 |
| V-2 exists | `Member.php:142-165` | verified | code read | — | Q1 |
| Not reachable today | `ProvenanceJson.php:57-58`; decoder `:259`, `:270`; migration `kind` CHECK | verified | grep and code | future code | Q1 |
| Guard does not prevent bypass from `Member.php` or the repository | `MembershipDecisionCreationTest.php:38-42` | verified | shared allowlist | — | Q1 (A needs stronger guard) |
| A1 results, hashes and commands | A1 evidence folder | verified | `sha256sum -c` OK; exit files `0`; `commands.txt` | the run process itself (not the outputs) | Q2, Q1 |
| `--no-scripts` effect is nil | A1 README | inferred | files don't boot Laravel | not demonstrated | Q2 |
| The 10 October outputs are lost | G3 evidence index; `/tmp` search | verified (not found) | — | other machines | Q2 |
| Reviewer recommendation content | `docs/publicdigit/membership/architecture/brainstorming/20261010_1901_implementation.md:13-17` | verified (untracked) | text read | the missing attachment | Q3 |
| Privacy of that file | heuristic grep | inferred | 0 hits | needs a human check | Q3 |
| Database safeguards and sequence | Part 2 plan `:66-80`, `:309-310`, `:330-331` | verified | text read | — | Q6 |
| ARB-SEC-01 wording; gate FAIL | Human ARB record `:12`; PBDIGIT-266 | verified | text read | — | Q6 |
| `pbdigit274_isolated_test` approval scope | `.claude/CONTEXT-publicdigit.md` | reported, unverified | — | approval records not re-read | Q6 (b) |
| Wider-suite counts and cause | independent review `:48`, `:55`, `:93` | reported, unverified | reviewer text | pre-Laravel 12 | G-5 |
| CP-1 precedent | C-1 acceptance review `:204`, `:228` | verified | text read | — | Q1 (B) |
| Ledger tables in production (OQ-01) | session log 2026-10-16 `:139`; migration list | unresolved | 7 matching files | only Operations can confirm | separate decision |

---

## D. Human decision sheet

| # | Decision | Recommended | Business rationale | If deferred | Authority | Approve / Reject / Defer | Name | Date |
|---|---|---|---|---|---|---|---|---|
| Q3 | Keep the reviewer's recommendation as an official, labelled record and fix its reference | Store officially (after privacy and placement checks) | the decision basis must be durable | the C-1 basis can be lost | PO | ☐ ☐ ☐ | | |
| Q2 | Record the lost 10 October outputs as unrecoverable; use the 16 October run for today's code | Close with the honest wording | a permanent open gap distorts the record | the C-1 decision carries an unclosable gap | PO / ARB | ☐ ☐ ☐ | | |
| Q1 | Accept the correction rules now, or after a small fix for the unchecked annulment record | **Defer; authorise the small fix inside C-1** (alternative: accept with conditions, after a focused review) | complies with the contract under any reading, at no schedule cost | (deferral is the recommendation) | PO / ARB | ☐ ☐ ☐ | | |
| Q6 | Request a dedicated, access-restricted Membership test database | Approve and name the requester | testing must not be able to touch shared or production data | database work and security testing stay blocked | ARB + PO + Infrastructure | ☐ ☐ ☐ | | |
| G-5 | Investigate the older failing Membership tests (findings only) | Approve scope, then name the engineer | separate real defects from stale tests | unknown product health persists | PO | ☐ ☐ ☐ | | |

Approving a row records a decision; it does not by itself authorise implementation work.

---

## E. Next-step execution plan

| Step | Type | Prerequisite | Completion criterion |
|---|---|---|---|
| 1. Route this sheet to the PO / ARB | human decision | none | the five rows are filled in |
| 2. Preserve the reviewer source (Q3) | documentation, authorised session | Q3 approved | citation resolves; banner present; privacy and placement checks recorded |
| 3. Write the G-6 disposition (Q2) | documentation | Q2 approved | wording recorded in the G3 record |
| 4. C-1 acceptance correction 2 (Q1 = B) | implementation, separately authorised | Q1 = B; additive contract annotation adopted | RED / GREEN evidence; 0 mismatches over 15,530 decisions; G6 fingerprint unchanged; database-free run on a committed tree |
| 5. Principal Reviewer review of correction 2, then the C-1 verdict | independent review, then human decision | step 4 | verdict recorded with its residual risks |
| 6. Infrastructure request (Q6) | operational | Q6 approved; requester named (D-2) | provisioning evidence delivered |
| 7. Review of the provisioning evidence, then ARB-SEC-01 re-check | independent review, then ARB | step 6 | gate recorded as passed |
| 8. G-5 investigation | test investigation, authorised | G-5 scope and engineer approved | findings report stored |
| 9. Operations confirms which migrations ran (OQ-01) | operational | none | production migration list on record |

**Read-only work allowed under the current scope:** source reading for security items S-6, S-8, S-9 and S-11; re-deriving the inventory of code that writes to `members`; assembling review packs. None of it changes the repository.

**Must stay blocked:** database tests, security tests and fixes (until step 7 and separate fix authorisation); Slice 2b (until the PostgreSQL verification is accepted and G-1 is decided); correction storage and commands; live writes and cutover (ARB-MEM-01, D-6 / R10).

---

## F. Final quality check

- Every recommendation is sourced or marked provisional.
- Q1 was tested against its strongest counterargument, and the recommendation changed as a result.
- Missing evidence is labelled as missing; no later run is described as reproducing an earlier one.
- Mandatory gates are separated from recommended sequencing (Q3 before Q1 and Q2 before Q1 are recommendations; the database sequence, ARB-SEC-01, ARB-MEM-01 and the Slice 2b gates are mandatory).
- Nothing is approved, assigned or implemented.
- Membership is the only workstream in scope.

**Traceability:** correction contract (`docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`) §3.1, §14.3, §14.5, C-AMEND-1 · G3 dispositions (`docs/publicdigit/reviews/2026-10-10-membership-g3-and-slice-dispositions.md`) · A1 evidence (`docs/publicdigit/membership/evidence/2026-10-16-a1-evidence-refresh/`, `f54037f96`) · Part 2 plan (`docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`) · Human ARB record (`docs/publicdigit/reviews/2026-10-09-membership-human-arb-decision-record.md`) · PBDIGIT-266, PBDIGIT-268.
