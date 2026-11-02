# Membership Slice 2a (PBDIGIT-271): independent review of the committed hardening (2026-11-02)

**Status:** REVIEW REPORT, recorded **verbatim** as returned (§2). It is not an acceptance. The PO's verdict is recorded in §3 and in `2026-10-11-membership-slice2a-reviews.md` §4.

**Reviewer:** a fresh general-purpose Claude sub-agent, commissioned on 2026-11-02 by session `93000efc-ad03-4c1d-9b3f-40966981efc3` on the PO's instruction ("finish W0: review the 2a hardening").
- It had no prior context.
- It was read-only on the repository.
- It could run database-free tests, and do sensitivity mutants only in a scratch worktree outside the repository.

**Reviewed:** commits `834a4f65c` (code and tests) and `14f89d18e` (docs), at HEAD `4a3c1ccd5`. The Membership code and tests are identical to the W0 clean-copy commit `a0d9d16bb`.

**Main claims spot-checked** by the commissioning session:
- `Member.php:92`, `:117`: `requireFiniteDates` is the first statement of each method;
- `:208-213`: it throws `InvalidArgumentException`;
- `MembershipDecisionCreationTest.php:42-43`: the allowlist is a per-file union;
- guide 08 `:61`: the stale named-exclusion text;
- the main tree is unchanged, and no `pr271` worktree remains.

## 1. Commission (summary)

Review H1 / H1-b, H2 and D1–D5; AC-1…AC-10; and V-7…V-12 at HEAD. Distinguish VERIFIED, REPORTED and INFERRED. Do not redesign. Report sections A–G.

## 2. Report (verbatim)

### A. Verdict recommendation for ACC-271: **ACCEPT WITH EXPLICIT RESIDUAL RISKS**

No correctness defect was found in Slice 2a's scope. H1/H1-b are closed at every reachable creation path (∞ effective/decision dates raise `InvalidArgumentException` before any refusal; commands cannot express ∞). The H2 parser scan genuinely detects every form it claims and fails closed on unparsable source — confirmed by mutants in a scratch copy. What remains are test-coverage gaps (precedence pinned for one case only; a multi-file scan mutant survives), a per-file union allowlist broader than the plan/guide describe (= V-10), and documentation drift (stale named-exclusion text, an overstated allowlist claim). None is reachable (the ledger has no binding and no caller outside tests), so all are fit to be recorded or fixed as Slice 2b entry criteria; the PO decides.

### B. Hardening items

**H1 / H1-b — ACCEPTABLE (one minor test gap).**
- VERIFIED: `Member::requireFiniteDates` (`Member.php:208-213`) is the first statement of `decideAdmit` (`:92`) and `decideEnd` (`:117`), i.e. before `DuplicateFactId` (`:93`, `:118`); throws `InvalidArgumentException`, not a refusal.
- VERIFIED no bypass: `Admit::__construct` / `End::__construct` reject ∞ `effectiveAt` themselves (`Admit.php:22-24`, `End.php:20-22`) and store no decision date; `fromLog`/decoder go through those constructors (`PostgresMemberRepository.php:259,270`) and the decision date is not persisted (Q-E1=A); handlers map non-nullable `int` via `TimePoint::of` (`AdmitMemberHandler.php:52-53`; `EndMembershipHandler.php` decide closure) — pinned by `MembershipCommandHandlersTest:262`; only `TimePoint::infinity()` produces ∞.
- VERIFIED by sensitivity: removing both guard calls → exactly the 5 H1 tests fail.
- Gap: precedence is pinned only for (Admit, ∞ decision date) vs `DuplicateFactId` (`FutureDatingTest:114-122`). Mutants surviving the whole Domain suite (117 OK): End guard moved after `DuplicateFactId`/`PeriodNotFound`/`PeriodAlreadyEnded`; Admit effective-date check moved after `DuplicateFactId`.

**H2 — ACCEPTABLE WITH CONDITIONS.**
- VERIFIED: `MembershipSourceScan::creationViolations` (`:94-129`) uses `NameResolver`, lower-cased matching, `self/static/parent` resolution; P1–P23 (23 forbidden forms) and N1–N7 (7 permitted) pass.
- VERIFIED by mutants: swallowing parse errors → 1 failure; dropping `NameResolver` → 19 failures + 2 errors; case-sensitive matching → P7, P22 fail; no `self/static/parent` → 1 failure. Unparsable source throws `RuntimeException` (`:113-117`).
- VERIFIED post-`f311c97c4`: `UNPARSABLE_KNOWN = []` (`MembershipDecisionCreationTest.php:33`); the real-tree scan covers all of `app/` with no exclusion and passes.
- Conditions/gaps (VERIFIED by probe):
  - Per-file union allowlist (`MembershipDecisionCreationTest.php:42`): `Decision::accepted` in `PostgresMemberRepository.php`, `Provenance::attested` in `Member.php`, and `new Admit` in `ProvenanceJson.php` are not reported — but the plan (§4.6/4.7) and guide 08:55 state per-rule allowlists. = V-10.
  - Multi-file mutant survives: a "stop after first non-allowlisted file" mutant passes all 33 tests; no R7/R8 test plants violations in ≥2 files.
  - Dynamic method names (`Decision::{'accepted'}()`) and callable arrays are not detected (callables declared out of scope; dynamic method names not explicitly declared).
  - `new Annul` / `new VoidFact` are not covered (V-3; outside 2a's rule).
  - Scan root is `app/` only; probe of `database/`, `routes/`, `config/` (589 files) is clean today.
  - Minor: `sources()` casts a failed `file_get_contents` to `''` (`MembershipSourceScan.php:44`), so an unreadable file passes silently.

**D1–D5 — ACCEPTABLE WITH CONDITIONS.** REPORTED: D1–D5 are never itemised in any record (`2026-10-11-membership-slice2a-reviews.md:28`), so they cannot be verified item by item; consistency was checked instead. VERIFIED consistent: Slice 0 contract amendment incl. the finite-date precondition (`2026-10-08-…-slice0-semantic-design-contract.md:493-498`); Slice 1a amendment (`2026-10-09-membership-slice1a-contract.md:76-79,106-112`); guide 08:19-26 (invalid input vs refusal; guard order); plan §15 counts (23/7, 22 RED). Drift listed in D (F-4…F-7).

### C. Acceptance criteria

| AC | Status | Evidence |
|---|---|---|
| AC-1 | met | `Member.php:101-103`; `AdmissionBasisTest` (refusal; declaration/rule/attested accepted; precedence); handler builds only `Provenance::decision` (`AdmitMemberHandler.php:54`); handler test `:177` |
| AC-2 | met | `EndKind` private ctor + closed set (`EndKind.php:20-27`), `tryFromString`; `EndKindTest:42`; handler `:189` → `end_kind_not_recognised` |
| AC-3 | met | `Member.php:131-133`; `EndKindTest:54,67`; handler `:200` |
| AC-4 | met (as specified) | `Member.php:104,134`; `FutureDatingTest:38-78`; handler `:211`; `decisionDate` a required parameter/command field. Caveat V-7 |
| AC-5 | **partial (confirmed)** | `InterimRoleAuthorityTest` covers the adapter alone (owner/admin granted, others denied, End always denied with `interim:unresolved:G-1`, evidence fields). No test wires `InterimRoleAuthority` into a handler (grep: only its own test). Denied evidence discarded (`DecideAndAppend.php:48-49`, V-12) |
| AC-6 | met as specified; weak detector | planted + clean real tree (2/2 OK). The literal scan misses a grouped import (`use App\Contexts\Membership\{Domain\Organisational\Member}`), a partially qualified name, a lower-case namespace, and concatenated table names (VERIFIED by probe; V-10) |
| AC-7 | met, with the allowlist caveat | parser matrix 33/33 OK; R8 rule in place; allowlist is a union (B, H2) |
| AC-8 | met | handler tests `:136,149,158,167,222` (IDEM-1…4 + corrected retry after each new refusal) |
| AC-9 | met | C-1 + G6: 2 tests, 5 786 assertions OK; the 15 530 count asserted (`C1ExhaustiveDifferentialTest.php:64`) and the stream hash asserted (`G6…GoldenTest.php:45`); Domain 117, Application 61, Infrastructure 13, the 7 Membership architecture files 97 — all OK. Property/metamorphic tests INFERRED to be within the Domain directory run |
| AC-10 | met, with documentation drift | contract amendments additive; guide 08 exists; tests extend plain `TestCase` (database-free); held files edited, not run (REPORTED) |

### D. Findings

| ID | Severity | Location | Description | Recommended disposition |
|---|---|---|---|---|
| F-1 | minor | `MembershipDecisionCreationTest.php:42` (allowlist) | Per-file union allowlist: each allowlisted file may contain every forbidden creation form; broader than plan §4.6/4.7 and guide 08:55 (= V-10) | 2b entry criterion (per-rule allowlists) |
| F-2 | minor | `FutureDatingTest.php:114-122` | Finite-date precedence pinned only for (Admit, ∞ decision date); two reordering mutants survive | fix in 2a, or record (two small tests) |
| F-3 | minor | `MembershipSourceScan.php:109-126` (no multi-file planted case in `MembershipDecisionCreationTest`) | A "scan only the first file" mutant survives all R7/R8 tests; the real-tree check would then be nearly empty | fix in 2a, or 2b entry criterion (a two-file planted case) |
| F-4 | minor (doc) | `developer_guide/membership/08_…md:61` | States a present-tense named exclusion of `VoterSlugStep.php` and a guard test, both removed by `f311c97c4` | fix (documentation) |
| F-5 | minor (doc) | guide 08:55; plan `:131-137` (§4.6) | Claims `Decision::accepted` "only in Member"; the code allows it in all three allowlisted files | fix the docs or the code (see F-1) |
| F-6 | info (doc) | plan `:82` (§4.1 precedence row) | Does not mention the finite-date precondition (superseded only via the §14 note and the §15 entries) | record or annotate |
| F-7 | info (doc) | plan `:389,394` | Cites php-parser 5.4.0 and its deprecations; HEAD has 5.9.0 and the W0 run shows no deprecations. No note of `f311c97c4` / `PBDIGIT-272`→`PBDIGIT-70` in plan §15 or the story | record (status sync) |
| F-8 | minor | `MembershipLedgerIsolationTest.php:20-27` (R5, literal) | Grouped, partially qualified and case-variant imports evade it (VERIFIED by probe) | 2b entry criterion (already the open architect follow-up) |
| F-9 | info | `MembershipSourceScan.php:44` | An unreadable file becomes `''` and passes silently | record |
| F-10 | info | `AdmitMemberHandler.php:54` | A blank `declarationRef` makes `Provenance` throw `InvalidArgumentException` inside the decide closure (consistent with "invalid input", but no handler test) | record |

### E. V-7…V-12 at HEAD (all still hold; none is a 2a blocker)

- V-7 holds: compared against the caller's date (`Member.php:104,134`); `Admit`/`End` have no decision-date field.
- V-8 holds: `AdmitMemberHandler::memberOf` calls `findByHolder`/`create` (`:62,66`) before the domain decides.
- V-9 holds: `TenantId::fromString($command->tenantId)` and the actor string come straight from the command, in both handlers.
- V-10 holds: union allowlist (X1–X3 probes return `[]`); scan root `['app']` only (`MembershipDecisionCreationTest.php:124`); R5 still literal.
- V-11 holds: `end_kind` nullable text with a presence check only (migration `:48,64-67`); the decoder uses `EndKind::fromString`, which throws (`PostgresMemberRepository.php:274`); `Member::__construct` checks only seq and `recordedAt` (`Member.php:25-35`), no legacy⇔attested check on load.
- V-12 holds: `DecideAndAppend.php:48-49` returns `NotAuthorised` and drops the evidence.

### F. Commands and results

- `git status --short app/ tests/` before and after: identical (only the pre-existing untracked `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php`). HEAD `4a3c1ccd5`.
- `git diff --quiet a0d9d16bb HEAD -- app/Contexts/Membership tests/Architecture tests/Unit/Contexts/Membership`: no change (W0 evidence applies at HEAD).
- PHP 8.5.11; nikic/php-parser v5.9.0 (require-dev).
- Main tree, `vendor/bin/phpunit --do-not-cache-result`: Domain OK (117, 112 565); Application OK (61, 226); Infrastructure OK (13, 41); `MembershipDecisionCreationTest` OK (33, 34); `MembershipLedgerIsolationTest` OK (2, 3); C-1 + G6 OK (2, 5 786); the 7 `tests/Architecture/Membership*Test.php` OK (97, 120), matching W0.
- Deviation (disclosed): one run of `phpunit tests/Architecture --filter Membership` also selected an unrelated Laravel-booting test (`GovernanceRuntime\VoterStrategyConvergenceTest`), outside the authorised paths. The repository's PBDIGIT-283 guard refused to boot it ("nothing was connected"); no database reached, no file changed. Re-run with the explicit file list.
- Read-only probes: a script loading the repo's autoloader, in the commissioning session's scratchpad (`probe.php`; `probe5.php` for R5).
- Sensitivity checks in a detached worktree `/tmp/pr271-sens` at HEAD (vendor copied, `composer dump-autoload --no-scripts`): control `FutureDatingTest` OK (10); M1 guards removed → 5 failures (exactly the H1 tests); M2 End guard moved later → survives (117 OK); M2b Admit effective-date check moved later → survives; M4 parse error swallowed → 1 failure; M5 no `NameResolver` → 19 failures, 2 errors; M6 case-sensitive → 2 failures (P7, P22); M7 no `self/static/parent` → 1 failure; M8 first file only → survives (33 OK). Worktree removed (`git worktree remove --force`); `git worktree list` shows no `pr271` entry.

### G. Residual risks if accepted

1. Not enforced end to end: D-5(b) future dating rests on an unpersisted, caller-supplied date (V-7); tenant and actor come from the command (V-9). Both become high severity once a caller or HTTP exists.
2. A refused admission still creates the Membership (V-8); to be decided before the 2b PostgreSQL binding.
3. Source-level guards only: R7/R8 are a per-file union, scan only `app/`, miss dynamic/callable forms; R5 is a literal scan evaded by grouped or partially qualified imports. Nothing structural stops a future file in an allowlisted path (F-1, F-8, V-10).
4. Thin test pinning: two precedence orders and multi-file scanning are not pinned (F-2, F-3).
5. AC-5 partial: no handler + adapter integration test; denied authority evidence not kept (V-12). End stays fail-closed by design (GOV-END B1 is 2b).
6. Load-time integrity absent: an unknown stored `end_kind`, or a stored legacy End without attested provenance, is not validated (V-11). The PostgreSQL path is unverified (held).
7. Documentation drift (F-4…F-7) to be synced.
8. The 2026-10-11 reviews exist only as reconstructed summaries; D1–D5 cannot be checked item by item.

## 3. PO verdict (2026-11-02)

**ACC-271 (Slice 2a): ACCEPTED WITH EXPLICIT RESIDUAL RISKS** (PO, "A, record it and commit").

**Dispositions:**
- **2b entry criteria:** F-1, F-2, F-3, F-8, and V-7…V-12 (V-7…V-12 as already proposed by the roadmap).
- **Fixed now (docs only, same commit):** F-4, F-5, F-6, F-7.
- **Recorded:** F-9, F-10.
- **Residual risks 1–8** (§2 G) are accepted.

**Scope:** database-free Slice 2a only. No PostgreSQL, wiring, HTTP or End-authority claim.
