# Membership correction slice C-1 (Annul / Void, kernel): implementation review

**Story:** `PBDIGIT-268` · **Plan:** `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` (approved in principle by the PO with conditions P-1, P-2, P-3 and constraints 1–7) · **Contract:** `2026-10-09-membership-correction-slice-semantic-contract-draft.md` (APPROVED by the PO 2026-10-10) · **Date:** 2026-10-10
**Author:** Developer (Claude). **Status: evidence for review. Engineering does not accept its own work (EP-02).** Acceptance belongs to the Principal Reviewer and the PO.

## 1. Did we implement the approved plan? (EP-02)

| Step | Plan | Done | Evidence (commit) |
|---|---|---|---|
| 0 | baseline | yes | unit (domain + application) **93 tests / 5768 assertions**; architecture **12 / 18** (recorded before any production edit) |
| 1 | refactor P-1, `EffectiveDatedFact` | yes | `f7c251354`; counts **identical** after the refactor (93 / 5768, 12 / 18) |
| 2 | Annul / Void, liveness (K-A1, K-V1) | yes | RED `4da3cc3d8` → GREEN `b206cc5ad` |
| 3 | `TargetHasNoDecisionProvenance` (K-A2, T11) | yes | RED `b3823606a` → GREEN `218ce489e` |
| 4 | `AnnulOfVoidRefused` (K-A3) | yes | RED `9c1b5cc39` → GREEN `189395019` |
| 5 | `DecidedAtRequired` + no state change (K-A4, P-2) | yes | RED `a960e651d` → GREEN `5c92f14e5` |
| 6 | `TargetNotLive`, `TargetNotFound`, DuplicateFactId first | yes | RED `3ebcdc0e8` → GREEN `f1bcb55b3` |
| 7 | restoration (K-R1, K-R2) | yes, **no RED** (see §3) | `0cf2d5f11` |
| 8 | INV-9 refusals T8–T10 (K-G1, K-R3) | yes, **no RED** | `f3292cb8e` |
| 9 | P-KNOWN (K-H1) | yes, **no RED** | `4997cdb32` |
| 10 | PHP port of g6 + golden fixture | yes | RED `78d0c17f0` → GREEN `289a169aa` |
| 11 | exhaustive differential (K-X2) | yes | `5f9c40864` |
| 12 | properties (§13.5, §14.4) | yes | `1e85fbfc0` |
| 13 | regression (K-X1) | yes | §4 |
| 14 | mutation measurement | yes | §5 |
| 15 | guide, review, story update, STOP | yes | `developer_guide/membership/04_correction_c1_annul_void.md`; this document |

**No deviation from the approved scope.** Only `app/Contexts/Membership/Domain/Organisational/` changed in production code. There were no adapter, schema, database, legacy, cutover or live-write changes (constraints 5, 6).

## 2. Production change (kernel only)

- **New:** `EffectiveDatedFact`, `ReversalFact`, `Annul`, `VoidFact`.
- **Changed:**
  - `Fact` (`effectiveAt()` moved out; P-1);
  - `Admit` and `End` (implement `EffectiveDatedFact`);
  - `Member` (`decideAnnul`, `decideVoid`, private `fact()`);
  - `Resolution` (stage 1 `live()`, `isLive()`);
  - `RefusalReason` (5 cases, the PO-approved names).
- **Guard order:** as the plan's refusal-trigger table (`Member.php`, `decideAnnul` / `decideVoid`).

**Deviation to report: the class name `VoidFact`.** The ratified fact kind is **Void**, but PHP rejects `Void` as a class name (fatal error: *"Cannot use 'Void' as a class name as it is reserved"*). The class is therefore `VoidFact`, and its docblock states that it is the fact kind Void. The domain vocabulary is unchanged; this is a language constraint, not a model change. **PO / PR: please confirm or name an alternative.**

## 3. TDD record (constraint 3)

- **Steps 2–6 and 10:** each failing run was recorded before its production change, and each failure was for the stated reason (undefined class or constant, or a wrong refusal). The RED test commits precede their GREEN commits.
- **Steps 7, 8, 9, 11 and 12 had no RED phase.** They verify behaviour that earlier GREEN steps already implemented:
  - the full liveness rule (step 2);
  - G\* for every fact kind (Slice 0);
  - prefix-based `knownAt` (Slice 0).
  This is stated in each commit message.

  Instead, the tests were shown to be **sensitive**. A temporary production mutant was introduced, the tests were run, and the mutant was reverted; `git diff` of `app/` was empty afterwards each time.

  | Step | Mutant | Result |
  |---|---|---|
  | 7 | a reversed reversal still counts | restoration test fails |
  | 8 | Annul bypasses G\* | T8 and T10 fail |
  | 9 | `knownAt` ignores τ | P-KNOWN fails |
  | 11 | Void without `TargetNotLive` | differential reports mismatches |
  | 12 | as step 7 | 3 of 6 properties fail |

## 4. Regression (constraint 7: actual commands, actual outcomes)

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/
#   OK (83 tests, 39308 assertions)
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Application/Organisational/
#   OK (34 tests, 93 assertions)
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/MembershipSemanticKernelPurityTest.php \
  tests/Architecture/MembershipSlice1aBoundaryTest.php tests/Architecture/MembershipSlice1bPersistenceBoundaryTest.php
#   OK (12 tests, 18 assertions)
```

- **Totals:** 117 unit tests (93 before C-1, plus 24 new) and 12 architecture tests, all green.
- **Not run:** the PostgreSQL feature suite. The adapter is untouched, and the shared test DB carries the PBDIGIT-266 caveat (ARB-SEC-01). So **no claim is made about the feature suite**.

## 5. Mutation measurement (step 14)

- **Command:** Infection 0.29.10 with pcov. The configuration is in the scratchpad only (as in audit §B.3); tests are the Membership domain suite.

  ```bash
  php vendor/bin/infection --configuration=<scratch>/infection.json5 --threads=8 --no-progress \
    --filter=Member.php,Resolution.php,Annul.php,VoidFact.php \
    --initial-tests-php-options="-d pcov.enabled=1 -d pcov.directory=app/Contexts/Membership/Domain/Organisational"
  ```

- **Scope:** `Member.php`, `Resolution.php`, `Annul.php`, `VoidFact.php`, plus `ResolvedMember.php`, which the `Member.php` filter also matches.
- **Result:** 129 mutants, 119 killed, 5 escaped, 5 uncovered. **MSI 92 %**, mutation code coverage 96 %, **covered-code MSI 95 %**.

| Survivor | Classification |
|---|---|
| `Resolution.php:74` `= true → = false` (C-1) | **equivalent**: `isset()` is true for `false` |
| `Resolution.php:70` `continue → break` (C-1), reported uncovered | **coverage artefact**: killed when run directly (10 kernel tests fail; production reverted). Same pcov effect as audit §B.3 `Resolution.php:29` |
| `Resolution.php:29` `continue → break`, uncovered | pre-existing; known artefact (audit §B.3) |
| `Resolution.php:124` throw removed, uncovered | pre-existing; unreachable before consequences (C-2) |
| `Member.php:26 / 28 / 31` `fromLog` validation | pre-existing test gap TG (audit R-1a-3) |
| `Member.php:39`, `:82` `array_values` unwrapped | equivalent: the variadic and the prefix filter already yield list keys |
| `ResolvedMember.php:74` nullsafe | pre-existing, outside C-1 |

**C-1 code has no killable surviving mutant.**

## 6. Oracle evidence (constraint 4: evidence, not the contract)

- **Golden:** `g6_c1_golden.py` runs the **unchanged** Python oracle, restricted to C-1 (no Correct; D-COR-2 after the structural guards).
  - Bounds: H = 5, L ≤ 5, giving **15530 decisions**.
  - The fixture records the per-depth counts, the stream SHA-256, the depth-3 listing and the oracle's SHA-256.
  - The PHP port reproduces all three.
- **Differential:** the kernel equals the port on verdict, refusal reason and per-period lifetime for all 15530 decisions (5781 acceptances). The run takes about 0.3 s.
- **Properties:** they reduce correlated error (§13.5). Each is computed without the production liveness pass:
  - deletion counterfactual;
  - P-EQ;
  - P-COMM;
  - P-REV-V, P-REV-A and P-APPEAL;
  - the closed form from the recursive §14.2 definition.
- **Port omission:** the port omits consequences and INV-COV because neither can arise in C-1. The golden equality confirms this within bounds.
- **Strength of claim:** **verified within bounds, not a proof.**

## 7. Findings for the PR / PO

| # | Class | Finding | Recommendation |
|---|---|---|---|
| **F-1** | Engineering (typing) · C-4 dependency | `PostgresMemberRepository::encode(Fact $fact)` (line 218) calls `$fact->effectiveAt()`, which `Fact` no longer declares (P-1). An **ad-hoc** PHPStan run (level 2, scratch config) reports `method.notFound`. **No project gate covers it:** `phpstan-greenfield.neon` analyses only Contestation, Adjudication and Election. **There is no runtime effect:** no production code outside the kernel creates or decides an Annul / Void (grep of `app/` and `routes/`), so `encode()` receives only Admit / End. This is the risk the plan anticipated. Under P-1 **no adapter change was made** | Handle in C-4, where persisting Annul / Void needs `encode()` changes anyway. Alternatively the PO may separately authorise the one-line narrowing to `EffectiveDatedFact` now. **Decision requested** |
| F-2 | Engineering (naming) | `VoidFact` instead of `Void` (§2) | confirm |
| F-3 | Engineering (test) | P-COMM is tested under the weaker premise "both orders accepted" | this implies the contract's statement under its stronger independence premise; no action |
| F-4 | Documentation | the PBDIGIT-268 row in `docs/publicdigit/backlog/README.md` is still pending; that file has other sessions' uncommitted edits | add when the file is free, or authorise a targeted row commit |

## 8. Out of scope (unchanged)

C-2 (Correct, consequences), C-3 (application, authority, events), C-4 (persistence), legacy, cutover and live writes. Also BD-REV-ER, D-COR-1 / 3 / 4, CQ-3, CQ-9 and D-COR-5 (B).

## 9. Session completion

```yaml
session_completion:
  status: C-1 implemented per the approved plan; awaiting review
  completed_work: plan steps 0–15 (PBDIGIT-268), commits f7c251354 … 1e85fbfc0 plus the docs commit
  evidence: §3–§6 of this review
  open_items: F-1 (decision), F-2 (confirm), F-4 (index row)
next_actor:
  recommended_role: Principal Reviewer, then PO
  reason: EP-02, engineering never accepts its own work
  blocking_condition: none for review; C-2 / C-3 / C-4 need separate authorisation
authorization:
  current_session_can_continue: yes (capability only)
  authorized_to_act: no further implementation without a PR / PO decision
  requires_human_decision: F-1, F-2, acceptance of C-1
```

## Addendum (2026-10-10, after the acceptance review; append-only, the text above is unchanged)

Source: `2026-10-10-membership-c1-acceptance-review.md`; PO decision H-5.

1. **K-E1 was omitted from §1 and §3.** The criterion had no test at the time of this review. It is now evidenced by `AnnulVoidTest::test_an_accepted_annul_carries_its_fact_data` and `…_void_carries_its_fact_data` (commit `9ae69b7bc`).
2. **"No deviation from the approved scope" (§1) is qualified.** The scope was respected. However:
   - the step 2 GREEN (`b206cc5ad`) was broader than its RED;
   - K-R1, K-R2, K-R3, K-G1 and K-H1 therefore had no RED phase.

   The PO accepted this as documented (H-2).
3. **K-H1 (§6) was example-only.** It is now a bounded property against the g6 port (commit `e96d12a5d`).
4. **Some "nothing appended" assertions in `AnnulVoidTest` are always true** (`Member` is immutable). They are not evidence. The evidence is the refused `Decision` plus the unchanged resolution.
5. **D-COR-2 has no independent-oracle coverage.** The g6 oracle has no `TargetNotLive`; the rule is added by the developer-written wrapper.
