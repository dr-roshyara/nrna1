# PBDIGIT-329 (COR-4): independent review brief

**Status:** PREPARED 2026-11-03 ("commit step 8 and prepare the review brief"); **COMMISSIONED by the PO 2026-11-03 ("Commission the COR-4 review").** The report will be recorded verbatim in `2026-11-03-pbdigit329-cor4-independent-review-and-acceptance.md`, with the PO's verdict.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` convention.

## 1. Reviewer and constraints

- **Reviewer:** a fresh general-purpose Claude sub-agent, as for COR-2, PBDIGIT-324 and PBDIGIT-264 Part 2. It has no context from the implementing session beyond this brief; it reads the code, not the implementer's conclusions.
- **Read-only on the repository.** It works in **its own clean worktree** at `acdedf1cd`:
  - `git worktree add --detach <scratch>/rev329 acdedf1cd`, then `composer install --no-scripts`;
  - it removes the worktree at the end and reports that it did.
- **PostgreSQL only through the pinned throwaway container**, run from that worktree:

  ```bash
  MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
    bash scripts/testing/membership-pg.sh [phpunit args]
  ```

  Rules:
  - **no other database**;
  - no `migrate` / `migrate:*`, seeders or artisan against any database;
  - do not read `.env`;
  - do not delete or edit `bootstrap/cache/config.php` in the main tree;
  - do not print credentials.
- **Probes and mutants only in the scratch area** (e.g. a mutated copy loaded ahead of the real class by a prepended autoloader, or edits inside the reviewer's own scratch worktree). Nothing may be left in the main tree.
- **Evidence labels:**
  - **VERIFIED:** ran it, or read it at a cited `file:line`;
  - **INFERRED:** reasoning only;
  - **NOT CHECKED.**
- **The strongest statement must not exceed the evidence.** The implementer's claims in the plan are claims to test, not facts.

## 2. What is under review

- **Story:** `docs/publicdigit/backlog/PBDIGIT-329-membership-correction-c4-persistence.md`.
- **Approved plan, with Q-1…Q-7 and step-by-step evidence in §10:** `docs/plans/20261103-0440-membership-cor4-correction-persistence-tdd-plan.md`.
- **Commits:**

  | Step | Commit | Content |
  |---|---|---|
  | plan | `660fc03a9` | story and plan, approved |
  | 1 | `6da2594b3` | load validation in `Member::fromLog` (V-1, V-14, V-15) |
  | 2 | `5b134f033` | R-1a-6 for Correct (`StorableValidTime`; architecture property test) |
  | 3 | `5572ce595` | repository contract for every kind; strictly increasing `recordedAt` (double) |
  | 4 | `8dfc363b6` | the migration; the column rename carried into the adapter |
  | 5 | `144f1e947` | the adapter for all five kinds; `ConsequencesJson`; `StoredMemberLogInvalid` |
  | 6 | `5304aaab7` | PostgreSQL race tests |
  | 7 | `96d71183e` | reload differential |
  | 8 | `acdedf1cd` | verification, evidence archive, `ConsequencesJsonTest`, guide 14, Slice 1b contract amendment |

- **Code:**
  - `app/Contexts/Membership/Domain/Organisational/Member.php`;
  - `app/Contexts/Membership/Application/Organisational/Ports/StorableValidTime.php`;
  - `app/Contexts/Membership/Infrastructure/Organisational/{PostgresMemberRepository,ConsequencesJson,StoredMemberLogInvalid}.php`;
  - `database/migrations/2026_11_03_120000_extend_org_membership_facts_for_corrections.php`;
  - `tests/Doubles/InMemoryMemberRepository.php`.
- **Tests:**
  - `MemberLogValidationTest`, `StorableValidTimeTest`, `ConsequencesJsonTest`;
  - `MemberRepositoryContract` (double and PostgreSQL);
  - `PostgresCorrection{Schema,Decode,Race}Test`;
  - changed: `MembershipStorableValidTimeCoverageTest`, `MembershipLedgerIsolationTest`, `MembershipPgIsolationTest`, `PostgresMemberRepositoryInvariantsTest` (T6).
- **Evidence:** `docs/publicdigit/membership/evidence/2026-11-03-pbdigit329-cor4/`.
- **Docs:** `developer_guide/membership/14_cor4_correction_persistence.md`; Slice 1b contract, "Amendment (2026-11-03)".

## 3. Authoritative sources

- **Correction contract** `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`: §8 (persistence obligations), §10, §14.5 (C-4 row; C-AMEND-1 F-1).
- **Slice 1a / 1b contracts:** `2026-10-09-membership-slice1b-contract.md` (and its 1a predecessor), for R-1a-1…R-1a-6, IDEM-1…IDEM-4, INV-14.
- **Roadmap verification table** `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md` §3.2: V-1, V-4, V-5, V-13, V-14, V-15; G-A2, G-A3.
- **ACC-260 condition K-2:** `docs/publicdigit/reviews/2026-10-10-membership-g3-and-slice-dispositions.md` §4.
- **The COR-2 acceptance** (`2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md` §4.1): P-2 (absolute representation), ER scope.
- **FP** `2026-10-08-membership-final-proof.md`: §12.4 (option C), §16.10.

## 4. Questions the review must answer

### R-1. Acceptance criteria

For each of plan §6 AC-1…AC-11: met, met in substance, partially met, or not met, with evidence.

### R-2. Correctness against the contract and the approved decisions

- **Load validation (Q-3):**
  - Is every rule in plan §4 enforced? Are any missing that the kernel's `decide*` guarantees structurally?
  - Is "structure only; G\* and time/authority guards not re-run" the right boundary, given R-12 and CFC "no re-execution"?
  - Can any log that `decide*` produces be refused on load? Test against `C2Histories` and the C-1 generators.
- **Encode / decode:**
  - Is every kind's mapping lossless (facts, provenance, `decidedAt`, consequences, including two-period Corrects and open-ended trims)?
  - Is the K-2 defect fully closed (no date accessor before the kind is known)?
- **The schema:**
  - Do the five-branch CHECK, the target FK and the self-target CHECK match the kernel's shapes exactly, neither stricter nor looser?
  - Is `down()` safe (refuses with correction rows; otherwise exact)? Is the release-gate statement correct and consistently recorded?
- **Q-2 (strict `recordedAt`):** is it correct under concurrency? Two appends in flight for the same Member: the `max(recorded_at)` read happens after the version compare-and-set. Show whether ties or decreases are still reachable on PostgreSQL.
- **Q-6:**
  - Is the set of exceptions `load()` wraps right? Could a genuine programming defect be mislabelled as damaged data, or could damaged data escape as something else?
  - Does `StoredMemberLogInvalid`'s message ever carry stored payload (provenance)?
- **Q-7:** are all written dates storable, including trim bounds? Is the architecture property test sound?
- **The double vs PostgreSQL:** any observable difference left?

### R-3. Evidence quality

- **The race tests:**
  - they use a **placeholder** `MembershipAct` (`End`), because the correction acts are COR-3. Is that acceptable, and is it disclosed everywhere it should be?
  - do the properties checked (stored consequences = g2 on the stored prefix; never both a Correct and an End) actually show what AC-7 claims?
  - the ER-off sensitivity mutant: reproduce it if feasible.
- **The reload differential:** PostgreSQL checks every 10th bounded history. Is the sample adequate? Consider a different stride or offset, or a longer history set, time-boxed.
- **Mutation:**
  - the scope excluded `PostgresMemberRepository` (it needs a database). Assess whether the PostgreSQL suite plus the two targeted mutants cover its risk; try a few adapter mutants yourself in the container if feasible;
  - spot-check the survivor classification, including the claim that `Member.php:390` (the revival key) is equivalent even for two-period Corrects.
- **Evidence archive:** do the hashes, counts and claims in plan §10 and the archive README reproduce?

### R-4. Changes to existing tests

Four existing tests were changed. Was each change justified by an approved decision or a reviewed property, and not made to hide a defect?
- **T6:** the expected `recordedAt` moves to `last + 1` because of Q-2;
- **`MembershipLedgerIsolationTest`:** allowlist entry for the new migration;
- **`MembershipPgIsolationTest`:** the reviewed file count, 4 → 7;
- **`MembershipStorableValidTimeCoverageTest`:** a new property test, narrowed once during RED.

### R-5. Scope and boundaries (AC-10)

- No COR-3 handler and no event storage.
- No migration run on any persistent database.
- Run `git diff --stat 660fc03a9^ acdedf1cd -- app database routes config` and report anything outside the plan.

### R-6. Regression

- **Database-free suites:** Membership Domain / Application / Infrastructure Organisational; `tests/Architecture/Membership*Test.php`.
- `tests/Unit/Testing`, in the clean worktree.
- **The full Membership PostgreSQL suite** in the container: at least one run, ideally two.

### R-7. Documentation

- Guide 14 is grounded in the code (no invented APIs).
- The Slice 1b contract amendment matches the migration exactly.
- Plan §10, story status and session log are consistent with the commits.
- Anonymity (ADR-T11) is untouched.

## 5. Report format

- **A. Verdict recommendation:** ACCEPT / ACCEPT WITH CONDITIONS / DO NOT ACCEPT. Give the conditions, and say which ones need code changes.
- **B. AC-1…AC-11 table:** status and evidence.
- **C. Findings:** ID, severity (critical / major / minor / observation), VERIFIED / INFERRED, `file:line`, a reproduction for any defect.
- **D. Decisions for the PO:** anything the PO must decide; recommendations, not decisions.
- **E. Evidence consistency:** counts, hashes, commit references and claims that do or do not reproduce.
- **F. Residual risks:** for the PO's acceptance record.
- **G. Out of scope:** anything found outside COR-4, as candidate backlog items, not fixes.

Also report the commands actually run, the container runs (with their `system_identifier`s), and confirm that the worktree, containers and any scratch artefacts were cleaned up.

## 6. What happens after

The report goes verbatim into the review-and-acceptance record. The implementing session spot-checks it and states what it checked. **The PO decides.** Conditions, if any, become authorised follow-up steps under PBDIGIT-329 or new backlog items. **Engineering never accepts its own work.**
