# PBDIGIT completeness audit: every remaining PublicDigit TODO has exactly one story

| | |
|---|---|
| **Date** | 2026-10-01 |
| **Kind** | ⚠ authority: generated. A backlog-integrity audit. **It authorises nothing, implements nothing and changes no existing ticket's scope.** |
| **Trigger** | External review: the Push B verification left `ENG-013`/`014`/`015` as engineering items only, so PublicDigit delivery work existed with no `PBDIGIT-XXX` story |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |
| **Numbering** | The highest ID in use was `PBDIGIT-158`, verified by `git grep -o "PBDIGIT-[0-9]*"` over `docs/` and `.claude/`. **New stories: `PBDIGIT-159` … `PBDIGIT-194` (36).** `PBDIGIT-52`…`57` remain unused gaps, deliberately not back-filled. |

## 1. Method

1. **Collect the TODO universe** from every source the review named:
   - the engineering backlog `docs/implementation/backlog/BACKLOG.md` (ENG, Debt, Parked, NEXT ACTION, epic table);
   - the ARB rulings register `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-30…R-100);
   - `docs/implementation/PROGRAM_STATUS.md` (four decision gates);
   - the EPIC-004 roadmap (WP-1…WP-8);
   - the Election OperatingCore track (`.claude/CONTEXT-publicdigit.md`);
   - the product backlog `docs/publicdigit/backlog/` (PBDIGIT-00…158, the six epics, the product gaps P-1…P-6, the parked method candidates);
   - the Push B verification (`docs/implementation/2026-10-01-push-b-status-verification.md`).
2. **Deduplicate.** One problem gets one story, even when it carries two source IDs. Example: `ENG-012` and `F-7C-6` are the same risky-notice signal, so they become one story, `PBDIGIT-162`.
3. **Classify each item:**
   - **DONE**;
   - **EXISTING** — an existing story already owns it;
   - **PARTIAL**;
   - **OPEN / BLOCKED / DEFERRED / HELD** (ticketed now);
   - **NEW** — the problem was not yet ticketed;
   - **EXCLUDED** — not PublicDigit delivery. Every exclusion states its reason.
4. **Re-verify where it was cheap,** on 2026-10-01: `AD-006`, `F-7C-1`, `F-7C-3`, `F-7C-4`, `ENG-002` and P-6 were re-checked against the tree; the WP statuses against the register.

**Linkage rule.** A new story names its source ID, and the source row keeps its text. The story is the **delivery ticket**; the source is **where the problem was found**. For example, `PBDIGIT-159` is linked to `ENG-015`, whose evidence is `docs/implementation/2026-10-01-push-b-status-verification.md`.

## 2. Result

| Class | Count |
|---|---|
| DONE (nothing to ticket) | 20 source items (each WP counted once) |
| EXISTING (owned by an existing story) | 8 source items, plus the 153 existing IDs `PBDIGIT-00`…`158` themselves (the unused gaps `52`…`57` excluded; `69` is used twice) |
| Ticketed now (`PBDIGIT-159`…`194`) | **36 stories, covering 41 source items.** Merges: `ENG-012` + `F-7C-6`; `F-7C-4` + `F-7C-5`; two health-table rows split across `159`/`173` |
| EXCLUDED (not PublicDigit delivery, reason stated) | 9 rows |

> **⚠️ Superseded in part (2026-10-01, `2026-10-01-pbdigit-story-integrity-review.md`; then the closure audit `2026-10-01-pbdigit-story-integrity-final.md` added `205`…`207` in §5.9):** an expanded sweep (inline code markers, skipped tests, the documentation corpus, `Architecture_Debt_Backlog.md`, the WP-6 closure package) found **10 more obligations with no story**. They are now `PBDIGIT-195`…`204` (§5.8). The sentence below is kept as written; it was **false by 10**.

**Every remaining PublicDigit delivery TODO found in these sources now has exactly one `PBDIGIT-XXX` story.** The claim is bounded by the sources listed in §1. Inline `TODO` comments in `app/` were **not** swept: that would be a separate code audit, and they are not backlog items.

## 3. The TODO universe, classified

### 3.1 Engineering backlog (`BACKLOG.md`)

| Source | Item | Class | Story / reason |
|---|---|---|---|
| `ENG-001` | Process Learning System | EXCLUDED | Engineering-methodology work. The methodology is **frozen** (2026-08-01: "no protocol refinement … unless PublicDigit implementation exposes a genuine deficiency"). It is not product delivery. |
| `ENG-002` | Shared Infrastructure PHPStan alignment | NEW | `PBDIGIT-163` (re-verified: Shared is outside the gate; 18 findings in the two named models) |
| `ENG-003` | `*Ref` VO naming sweep | NEW | `PBDIGIT-194` |
| `ENG-004` | Mutation Ratchet 1 | NEW | `PBDIGIT-164` |
| `ENG-005` | Stream B documentation consolidation | NEW (dormant) | `PBDIGIT-170`: trigger-based; ARB did not activate it |
| `ENG-006` | Engineering-platform developer guide | NEW | `PBDIGIT-168` |
| `ENG-007` | `dev-guide-reminder.sh` area mapping | EXCLUDED | AI-assistant session tooling (`.claude/scripts/`). It ships nothing to the product and no PublicDigit test depends on it. |
| `ENG-008` | Link-repair confidence policy into config | EXCLUDED | KnowledgeOS documentation tooling, **trigger-gated** (needs a second consumer, none exists) |
| `ENG-009` | Move `repository-migrations.yaml` into KnowledgeOS | EXCLUDED | KnowledgeOS placement, **blocked on OQ-5** |
| `ENG-010` | Indexes promise never-written documents | NEW | `PBDIGIT-169` (the PublicDigit documents) |
| `ENG-011` | Link validation for every documentation root | EXCLUDED | Tooling of the Engineering Knowledge Platform (`knowledge-lint`). The PublicDigit-side outcome is `PBDIGIT-169`. |
| `ENG-012` | Risky-notice increase | NEW (merged) | `PBDIGIT-162`, with `F-7C-6` and F-WP6R-1. Plan authorised by R-95. |
| `ENG-013` | Merge gate vs RED-first increment | NEW | `PBDIGIT-160` (decision) |
| `ENG-014` | 22 PHPStan errors | NEW | `PBDIGIT-161` |
| `ENG-015` | Health table claims a green gate | NEW | `PBDIGIT-159` (widened: the epic table and NEXT ACTION are stale too) |
| `AD-006` | FeeTestFactory `TenantId` mismatch | NEW | `PBDIGIT-165` (re-verified: 5 of 5 fail) |
| `F-1` | Deptrac install | DONE | PB-007 7A. The missing binary in this workspace is part of `PBDIGIT-159`. |
| `F-2` | Infection coverage driver | DONE | PB-007 7C / F-7D-2 |
| `F-7C-1` | Test class name mismatch (dead tests) | NEW | `PBDIGIT-166` (re-verified) |
| `F-7C-2` | `ElectionUser.php` does not parse | DONE | Repaired by the PO 2026-08-12 (`PBDIGIT-70`) |
| `F-7C-3` | `VoterSlugStep.php` does not parse | EXISTING | `PBDIGIT-70` (re-verified: still unparseable) |
| `F-7C-4` | `ValidateVotingIpTest` fails | NEW (merged) | `PBDIGIT-167` (re-verified: 1 failure), aligned with `PBDIGIT-120` |
| `F-7C-5` | Legacy-context test failures | NEW | `PBDIGIT-167` (the remainder after 76–78, 139, 157 and 165) |
| `F-7C-6` | 57 risky Feature tests | NEW (merged) | `PBDIGIT-162` |
| Parked | 38D-02 Capability Relationships | EXCLUDED | An ARB-owned governance-track architecture artifact that waits for the 38D-01 digest (`.claude/memory/`). A ticket would make an observation into architecture. **Returned to governance.** |
| Parked | Domain Model Catalogue | NEW | `PBDIGIT-171` |
| Parked | Root `CLAUDE.md` tech-table refresh | DONE | Refreshed 2026-07-11 (`.claude/MEMORY.md:485`). Re-verified against `composer.json` and `package.json` today: Laravel ^11, PHP ^8.2, Inertia 2, Vue 3, Sanctum ^4, Spatie ^6, PostgreSQL (`phpunit.xml`). The parked line is stale, which is part of `PBDIGIT-159`. |
| NEXT ACTION | EPIC-002 Phase 1 literature review | DONE | `docs/implementation/EPIC-002_Literature_Review.md` and 13 sibling EPIC-002 documents exist. The stale pointer is `PBDIGIT-159`. |
| Health row | "first real CI run pending push" · "deployment doc still missing" | NEW | `PBDIGIT-159` (status) · `PBDIGIT-173` (deployment) |

### 3.2 Programme roadmap (EPIC-004 WP-1…WP-8, ARB register, PROGRAM_STATUS)

| Source | Item | Class | Story / reason |
|---|---|---|---|
| EPIC-001 | Greenfield Core, PB-001…007 | DONE | Formally closed (ARB 2026-07-11) |
| EPIC-002 | Strategic discovery | DONE (widened) | Context map, 5 ratified BCs (ARB 2026-07-25) |
| EPIC-003 (as used) | Tactical DDD entry assessment | DONE | Complete 2026-07-25 |
| WP-1, WP-2, WP-3A, WP-4A, WP-4B, WP-5, WP-6, WP-7 | | DONE | Register: R-67, R-69, R-87, R-43/R-55, R-48/R-59/R-66, plus the WP-1, WP-2 and WP-5 plan acceptances |
| WP-3B | Provenance-chain removal half | NEW (BLOCKED) | `PBDIGIT-174` |
| WP-4C-1 D3/D4 | Catalog entry + publication call site | NEW (HELD) | `PBDIGIT-175` |
| WP-4C-2 | Q1–Q4 Domain Owner answers | NEW (BLOCKED) | `PBDIGIT-176` |
| WP-4D | Authority-decision intake | NEW (not authorised) | `PBDIGIT-177` |
| R-91 | Transaction boundary on the issuance path | NEW (unauthorised) | `PBDIGIT-178` |
| R-82 / PM-6 | Issuance confirmation, unallocated | NEW | `PBDIGIT-179` |
| R-95 / ER-08 | `enforceHorizon()` isolation | NEW (plan authorised) | `PBDIGIT-180` |
| R-75 | `ChallengeRaised` publication | NEW (BLOCKED) | `PBDIGIT-181` |
| WP-8 | End-to-end operational validation | NEW (DEFERRED, R-79) | `PBDIGIT-182` |
| R-96 | COL-5a mechanism, owner vacated | EXCLUDED | "Governance must name a home". A story would invent an owner. **Returned to governance** (the operating loop's Phase 3 rule). |
| R-99 | Verification Framework v1.x has no spec document | EXCLUDED | A verification-architecture governance artifact, not delivery |
| Epic numbering | EPIC-003/004 reused without a ruling | NEW (decision) | `PBDIGIT-189` |
| Voting · Appointment · Read Models · legacy migration epics | Not opened | NEW (epic stories) | `PBDIGIT-190` · `191` · `192` · `193` |

### 3.3 Election OperatingCore track

| Source | Item | Class | Story / reason |
|---|---|---|---|
| EM-IMPL-001 | Domain core first increment | DONE | Accepted, baselined 2026-08-17 |
| EM-IMPL-002 GREEN-1…4 | | DONE | `d2a0fe7c1`…`4651a3e76` |
| H-2 | | DONE | `29b8051b6`; re-run OK |
| EM-DOM-001 Act B | | DONE | `216552aa7`, `b54074900` |
| GREEN-5 (UC-4) | | NEW (decision pending) | `PBDIGIT-183` |
| GREEN-6 (UC-5) | | NEW (BLOCKED, `Q-UC5`) | `PBDIGIT-184` |
| GREEN-7 (UQ-1…4) | | NEW (HELD) | `PBDIGIT-185` |
| ADR-1 domain slice + normalization slice, `BND-1` | | NEW (BLOCKED) | `PBDIGIT-186` |
| EM-DOM-001 Acts C/D | | NEW (not authorised) | `PBDIGIT-187` |
| Stale pin docblock | | NEW | `PBDIGIT-188` |
| `GREEN-5` label collision · EM-GOV-063 · C-5 items N-3…N-6 | | EXISTING | `PBDIGIT-73` · `PBDIGIT-72` · `PBDIGIT-69` (operating-core file) |

### 3.4 Product backlog (`docs/publicdigit/backlog/`)

| Source | Class | Note |
|---|---|---|
| `PBDIGIT-00`…`24`, the six epics | EXISTING | `PBDIGIT-00` ran (a negative result, owned by 35–41). `01`…`24` are `IMPLEMENTED — NOT VERIFIED`; `22` is `PARTIAL`. |
| `PBDIGIT-25`…`75` | EXISTING | Status as listed in the README. The closed ones (27, 28, 30, 38, 61, 62, …) need nothing. |
| `PBDIGIT-76`…`157` | EXISTING | The constitutional-runtime backlog (82 tickets) |
| `PBDIGIT-158` | EXISTING | The issuance marker shift |
| P-1 no end-to-end run | EXISTING | `PBDIGIT-00` → `PBDIGIT-36` |
| P-2 counting has no domain model | EXISTING (**PARTIAL**, corrected C-6) | `PBDIGIT-19` verifies the count. The missing tally model belongs to the Voting epic **`PBDIGIT-190`**. |
| P-3 `unpublish_results` not constitutional | EXISTING | `PBDIGIT-60` |
| P-4 voter-journey vs lifecycle vocabulary | NEW | `PBDIGIT-172` |
| P-5 voter routes defined twice, `submit_seleccted` | EXISTING | `PBDIGIT-17` (EPIC-05 "Known findings"). Re-verified: `routes/election/electionRoutes.php:159-161` |
| P-6 no deployment documentation | NEW (PARTIAL) | `PBDIGIT-173`. Files exist but are unverified; `.env.example` says mysql. |
| C-1…C-17, OQ-M1, OQ-M2, OQ-2 (parked method candidates) | EXCLUDED | Review-method governance under the method freeze. Adopting any of them is a PO act ("one Product Owner sentence"), not delivery. |

## 4. Integrity findings (recorded, not new stories)

- **The `PBDIGIT-69` ID collision** (two different stories) is owned by `PBDIGIT-134`. Not duplicated.
- **The README index omitted 8 existing stories:** `PBDIGIT-60`, `61`, `62`, `71`, `72`, `73`, `74`, `75` had files but no row. **Repaired in this commission** (index rows only, taken from each file's own title and status line). Index maintenance is not a TODO.
- **`.claude/CLAUDE.md` focus line "WP-7C → WP-8 → EPIC-005" is stale.** WP-7C was accepted (R-66), WP-8 is deferred (R-79) and EPIC-005 is ambiguous. It is a binding instruction file, so it is **not edited here**. It is recorded in `PBDIGIT-189`'s acceptance as a human edit.
- **`docs/implementation/review/2026-08-05-programme-state-assessment.md`** misstates WP-5/6/7 (the register contradicts it). It is a historical document. If a correction is wanted, it belongs to `PBDIGIT-130`'s class of corrections, but it is **not added to `PBDIGIT-130`'s scope** here.

## 5. New stories — specifications

Each story keeps its source ID as **linkage**, and the source item keeps its own text. **The PBDIGIT story is the delivery ticket. The source row stays the record of where the problem was found.** No story is authorised for implementation. Every story needs PO authorisation, one at a time, and every code story follows RED → GREEN → independent verification → developer guide.

### 5.1 Merge-gate and verification integrity (Push B verification sequence)

#### PBDIGIT-159 — Programme status integrity: the health table, the epic table and the NEXT ACTION describe July 2026, not today
| | |
|---|---|
| **Linkage** | `ENG-015` (`docs/implementation/backlog/BACKLOG.md`) · evidence `docs/implementation/2026-10-01-push-b-status-verification.md` |
| **Status** | 🔴 OPEN — not authorised · **first in sequence** (no dependencies) |
| **Business problem** | Status documents steer what gets worked on next. Today they say the opposite of reality, and a stale narrative ("PB-003 ~20 %, PB-004…007 0 %") was already in circulation because of it. |
| **Verified facts** | **(1)** "Program Health" says `composer merge-gate` PASS · GreenfieldCore 179✔/0F · Architecture 146✔/1 skip. Re-run 2026-10-01: Architecture **3 failures** · Deptrac **not runnable** · PHPStan **22 errors** · GreenfieldCore **15 errors / 2 failures / 112 risky**. **(2)** The epic table says EPIC-002…006 are "not opened", and NEXT ACTION says "EPIC-002 Phase 1 literature review". But `docs/implementation/EPIC-002_*.md` (literature review, strategic discovery, context map …), `EPIC-003_Tactical_DDD_Entry_Assessment.md` and `EPIC-004*` (Adjudication) exist, and the programme has since run the WP-1…WP-8 roadmap (§4 of this audit). **(3)** Deptrac: `composer.json` requires `qossmic/deptrac-shim ^1.0` and `composer.lock` contains it, but `vendor/qossmic/deptrac-shim/deptrac` is absent. The dev dependencies are not installed in this workspace. |
| **Scope** | Re-synchronise the **mutable** status rows only (ES-004.3): Program Health with a dated run and the exact commands; the epic table and NEXT ACTION against the recorded rulings; the production-readiness row ("first real CI run pending push"). Document the workspace prerequisite (`composer install` with dev dependencies) for running the gate. |
| **Out of scope** | Fixing any failing step · rewriting history or rulings · deciding the next epic (that is an ARB act). |
| **Acceptance** | Every health row states a measured result with its date and command. The epic table and NEXT ACTION cite the ruling that sets each value. A fresh reader cannot derive "EPIC-002 not opened" or "merge gate PASS" from the file. |

#### PBDIGIT-160 — The merge gate cannot pass while an authorised RED-first increment is open (decision)
| | |
|---|---|
| **Linkage** | `ENG-013` · EM-IMPL-002 RED suite `1f4b4c5f3` · `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-decision-record.md` |
| **Status** | 🟡 **DECISION REQUIRED (ARB)**. Engineering does not choose. |
| **Business problem** | A gate that is red by design can no longer show a real regression. `PBDIGIT-158` (a legal timestamp corrupted on save) sits in the same red output as 16 intentional RED tests. |
| **Verified facts** | `composer merge-gate` step 4 runs `--testsuite=GreenfieldCore`, which includes `tests/Unit/Contexts/Election` (`phpunit.xml:33-43`). **16 of the 17** errors and failures are the EM-IMPL-002 RED suite: `QueryServicesRedTest` ×5, `ReportPeriodExpiryHandlerRedTest` ×4, `RecordCommitteeConstitutionHandlerRedTest` ×3, `RefusalTaxonomyRedTest` ×2, `HistoryKindAssignmentRedTest` ×1, `AbsentAggregateReferenceRedTest` ×1. GREEN-5/6/7 are held by governance. |
| **Options (from evidence, none chosen)** | (a) a separate `RedPending` suite, excluded from the gate until its GREEN · (b) a `@group red-pending` exclusion · (c) a pinned expected-RED manifest that fails the gate when an unlisted test fails **or** a listed test unexpectedly passes. |
| **Acceptance** | The decision is recorded. The gate is green on the current tree apart from the decided treatment, and it still fails on a seeded regression. |
| **Sequence** | After `PBDIGIT-159` and `PBDIGIT-158`. It unblocks `PBDIGIT-161`'s dependent part. |

#### PBDIGIT-161 — Greenfield PHPStan regression: 22 errors in Election OperatingCore
| | |
|---|---|
| **Linkage** | `ENG-014` · `phpstan-greenfield.neon` · ADR-1 Rule-8 gate `docs/publicdigit/implementation/2026-08-18-EM-IMPL-002-rule8-gate-adr1.md` |
| **Status** | 🔴 OPEN. 2 of the findings can be fixed now (with authorisation); 20 are bound to blocked or held work. |
| **Verified facts** | Merge-gate step 3 (level max) reports 22 errors, all under `app/Contexts/Election/{Application,Domain}/OperatingCore`. PB-007 had recorded it as PASS. **6** are null-dereferences in `FillCommitteeSeatHandler.php:107-140`: the ADR-1 absent-aggregate finding, whose normalization slice is **BLOCKED** at its Rule-8 gate. **14** are "property never read" in the held GREEN-5/6/7 stubs. **2** are independent of the held work: `Domain/OperatingCore/Event/AppointmentsAwaited.php:26` (a strict comparison that is always false) and `Domain/OperatingCore/Recovery/RecoveryProcess.php:92` (`$intervals` type mismatch). **⚠️ Correction C-1 (story integrity review 2026-10-01, `2026-10-01-pbdigit-story-integrity-review.md` §8): both are type-level findings, and no behavioural defect is established.** The first is a defensive guard that PHPStan proves unreachable from its docblock type; the second is an array-shape mismatch. |
| **Scope** | The 2 independent findings, each RED → GREEN. The domain is frozen at the EM-IMPL-001 baseline, so each needs its own authorisation. List the other 20 against their owning slices. |
| **Out of scope** | A baseline or ignore file · lowering the level · touching the blocked or held handlers. |
| **Acceptance** | The 2 findings are resolved. The 20 are listed with owners. The gate treatment follows `PBDIGIT-160`. |
| **Sequence** | After `PBDIGIT-160`. |

#### PBDIGIT-162 — Risky notices in `GreenfieldCore` have grown 57 → 112 with an unestablished cause
| | |
|---|---|
| **Linkage** | `ENG-012` (101 → 105 during WP-7B-R1) **and** `F-7C-6` (57 risky in PB-007 7C). They are merged because they concern **the same signal**: two IDs, one problem. |
| **Status** | 🔴 OPEN — verification hygiene |
| **Business problem** | A count that moves for unknown reasons cannot tell a new defect from an execution-order artefact. The most sensitive signal of the gate is the one nobody can read. |
| **Verified facts** | GreenfieldCore 2026-10-01: **112 risky**. PB-007 recorded 57, WP-7B-R1 recorded 101 → 105, while adding 4 tests that are not among the risky entries. The house pattern is *"Test code or tested code removed error handlers other than its own"*. `F-7C-6` grep-verified that no `set_error_handler`/`restore_*` calls exist in `app/` or `tests/`. |
| **Scope** | Establish the cause (execution order, Laravel teardown vs PHPUnit 11 handler bookkeeping, or a specific test), then either remove it or pin the count with a documented reason. |
| **Acceptance** | Each risky entry is attributed to a cause. The count is either 0 or a pinned, justified number that the gate checks. |

#### PBDIGIT-163 — Shared Infrastructure (Outbox/Inbox) is outside static analysis and fails PHPStan max
| | |
|---|---|
| **Linkage** | `ENG-002` (opened from the PB-003-C2 finding; ER-03) |
| **Status** | 🔴 OPEN — `Designed` since July, never started |
| **Verified facts** | `phpstan-greenfield.neon` analyses only `Contestation`, `Adjudication` and `Election`. **`app/Contexts/Shared` is not in the gate**, although the outbox/inbox carry every cross-context event. ENG-002 recorded OutboxEvent 7 + InboxEvent 11 findings, and a 2026-10-01 re-run of those two files gives **18**. A standalone level-max run over `app/Contexts/Shared/Infrastructure` (no project config, so indicative only) reports **120** errors: `OutboxEventProcessor` 43, `RedriveParkedInboxEvents` 33, `OutboxWriter` 12, `InboxEvent` 11, `InboxExecutionEngine` 9, `OutboxEvent` 7, `Inbox` 4, `OutboxRepository` 1. |
| **Scope** | Bring all Shared Infrastructure Eloquent models and services to PHPStan max **together** (ENG-002's rule), then add `app/Contexts/Shared` to the greenfield PHPStan paths. |
| **Out of scope** | Behaviour changes in the messaging pipeline. |
| **Acceptance** | `app/Contexts/Shared` is in `phpstan-greenfield.neon` and the gate is clean. `CorrelationLoop`, outbox and inbox tests are unchanged and green. |

#### PBDIGIT-164 — First mutation-score ratchet on the correction loop (MSI 50 %)
| | |
|---|---|
| **Linkage** | `ENG-004` · A-3 baseline-then-ratchet policy · F-7D-2 (IDD §2e) |
| **Status** | 🔴 OPEN — not started |
| **Verified facts (recorded baseline)** | MSI 50 % · mutation code coverage 77 % · covered-code MSI 65 % · 224 escaped · 184 uncovered. Escapes concentrate in `ChallengeOutboxAdapter`, the mappers and hydrators, and `InboxExecutionEngine`: the boundary that carries every correction. The validated execution model is 1-thread, about 1 h 17 min. |
| **Scope** | Per-thread test databases via Infection `TEST_TOKEN` (the enabling sub-item), then coverage of the uncovered mutants, then hunting the escapees. Raise `min-msi` only at completion; never lower it. |
| **Acceptance** | A measured MSI above 50 % from a valid run. `min-msi` is raised to the measured value. |
| **Sequence** | After `PBDIGIT-160`. A red gate makes the Infection initial run meaningless (it stops on defect). |

### 5.2 Test estate outside the greenfield core

#### PBDIGIT-165 — Finance tests crash: Membership and Shared each define their own `TenantId`
| | |
|---|---|
| **Linkage** | `AD-006` (BACKLOG.md Debt — "FeeTestFactory TenantId mismatch", owner Membership, Open) |
| **Status** | 🔴 OPEN. Re-verified 2026-10-01. |
| **Verified facts** | `tests/Feature/Finance/FeePaidProjectionTest.php`: **5 of 5 fail before any assertion** with `TypeError: Fee::create(): Argument #3 ($tenantId) must be of type App\Contexts\Shared\Domain\ValueObjects\TenantId, App\Contexts\Membership\Domain\ValueObjects\TenantId given`, raised from `tests/Support/Factories/FeeTestFactory.php:74`. |
| **Business problem** | The fee-paid projection feeds membership standing. It has **no working test**, so a regression in who counts as a paid member would not be seen. ~~Two tenant-identity types also mean a tenant boundary can be crossed by type confusion.~~ **⚠️ Correction C-2 (story integrity review 2026-10-01, `2026-10-01-pbdigit-story-integrity-review.md` §8): that sentence is withdrawn.** Production already passes the Shared `TenantId` (`ApproveMembershipApplication.php:57` via `ApproveMembershipApplicationCommand`). Only the test factory uses the Membership type: **test-fixture drift**. `Architecture_Debt_Backlog.md` AD-006 also lists `tests/Feature/Membership/OutboxIntegrationTest.php`, re-run 2026-10-01: 3 failed. |
| **Scope** | Repair `FeeTestFactory` to pass the Shared `TenantId` that production uses. **(Corrected C-2: a canonical-type decision is not needed for the repair.)** Whether Membership should keep its own `TenantId` value object at all is a separate modelling question, out of scope here. |
| **Acceptance** | `FeePaidProjectionTest` and `OutboxIntegrationTest` pass with their assertions unchanged. |

#### PBDIGIT-166 — A test class name does not match its file, so its tests never run
| | |
|---|---|
| **Linkage** | `F-7C-1` (PB-007 IDD §2c-ii) |
| **Status** | 🔴 OPEN. Re-verified 2026-10-01. |
| **Verified facts** | `tests/Unit/Domain/Election/Security/OverlaySignalCategoryTest.php:8` declares `class OverlayInfluenceTest`. PHPUnit cannot load it, so its tests are dead in every run, and the load warning aborts any run with `failOnWarning`. |
| **Scope** | Decide whether the tests are still valid (the overlay-signal category is the subject of `PBDIGIT-97`/H-8). Then rename the class, or retire the file with a recorded reason. |
| **Acceptance** | No suite-load warning. The tests either run and pass, or are retired with a disposition. |

#### PBDIGIT-167 — Broken unit and feature tests in the Membership, Elections, Geography and Governance contexts, and in legacy middleware
| | |
|---|---|
| **Linkage** | `F-7C-5` (PB-007 IDD §2c-ii: 997 tests, 69 errors, 5 failures in one broad run) · `F-7C-4` (`ValidateVotingIpTest::it_blocks_request_when_ip_does_not_match`: **re-verified 2026-10-01, still fails**. The middleware returns `Response`, the test expects `RedirectResponse`) |
| **Status** | 🔴 OPEN — **re-measurement required**. The July figure has not been re-run. |
| **Re-measured (F-3 (final closure audit 2026-10-01, `2026-10-01-pbdigit-story-integrity-final.md` §7))** | The full `Unit` + `Feature` run gives **81 failing files / 362 failing tests** in this story's named contexts (Membership, Elections, Geography, Governance, Committee), against July's 69 errors + 5 failures. The scope is unchanged; only the size is. Failing files **outside** these contexts and outside every other story are `PBDIGIT-208`. |
| **Verified facts (recorded)** | Stale constructor signatures (e.g. `AssignMemberToCommitteeHandler` takes 6 arguments, its tests pass 5) and older `Elections` handler tests. These contexts are outside `GreenfieldCore`, so **no gate notices them**. |
| **Also linked (C-3 (story integrity review 2026-10-01, `2026-10-01-pbdigit-story-integrity-review.md` §8))** | `Architecture_Debt_Backlog.md` **AD-008**: `CommitteeAssociationRepositoryTest` ×11 and `CommitteeMembershipApplicationTest` ×6. Re-run 2026-10-01: `CommitteeAssociationRepositoryTest` **11 failed**, *"Member does not exist … Create Member record first"* (fixture drift). |
| **Not duplicated** | Voting feature fixtures → `PBDIGIT-76`/`77`/`78` · capability and suspension suites → `PBDIGIT-139` · election-management feature suites → `PBDIGIT-157` · Finance → `PBDIGIT-165`. **This story is the remainder only.** |
| **Scope** | Re-run per context. Classify each failure (test drift, fixture drift, obsolete, or production defect) **before** any repair. `F-7C-4`'s disposition must agree with `PBDIGIT-120`, which deletes `ValidateVotingIp`: do not repair a test for middleware that is scheduled for deletion without recording why. Repair test drift. Raise any production defect as its own story. |
| **Acceptance** | A per-context table of failures, each with a classification and a disposition. Every failure is repaired, retired with a reason, or re-raised as its own story. |

### 5.3 Engineering platform and documentation

#### PBDIGIT-168 — The engineering platform (gate scripts, pre-push, CI role gate) has no developer guide
| | |
|---|---|
| **Linkage** | `ENG-006` (documentation debt; EG-001…EG-003, 2026-07-26) |
| **Status** | 🔴 OPEN. The scheduled home "EG-005 Engineering Platform Qualification" has not been opened. |
| **Verified facts (recorded)** | `scripts/lib/config-guard.sh`, four gate scripts, `.husky/pre-push`, `scripts/verify.sh`, `role-permission-verification.yml` and `PermissionSeeder.php` shipped without a guide. `scripts/README.md` is a hazard note (the jq impostor), not a guide. |
| **Scope** | One developer guide covering: fail-closed config parsing (`require_int`) · the jq authenticity hazard · why the role gate runs in CI · gate-chain reliability · the workspace prerequisite from `PBDIGIT-159`. |
| **Acceptance** | The guide exists under `developer_guide/`, grounded in committed code, with a traceability line. |

#### PBDIGIT-169 — Documentation indexes promise 30 documents that were never written
| | |
|---|---|
| **Linkage** | `ENG-010` (ARB classification 2026-08-01: an integrity defect, with debt as its consequence) |
| **Status** | 🔴 OPEN |
| **Verified facts (recorded 2026-08-01)** | 47 references to 30 distinct targets in 21 source files, each with **no git history at its path** and no file of that name anywhere. Concentrations: `…/statemachine/05_COMMON_PATTERNS.md` ×8 and `…/02_API_REFERENCE.md` ×7 from one index · 6 in `docs/architecture/members-forum-context/` · 5 in `developer_guide/`. |
| **Scope** | For each PublicDigit target: write it, or remove the promise from the index with a dated note. **The tooling side (validating every root) is `ENG-011`, which stays engineering-platform work (§6).** |
| **Acceptance** | 0 references to never-written PublicDigit documents. Each disposition is recorded. |

#### PBDIGIT-170 — Programme documentation consolidation (Stream B), trigger-based
| | |
|---|---|
| **Linkage** | `ENG-005` (ARB 2026-07-11: the rule was adopted as an Engineering Standard; the sweep is decoupled, Low priority, and **trigger-based**) |
| **Status** | ⏸️ **DORMANT — opens only on a trigger**: a duplication-caused mistake · onboarding difficulty · a contradiction · slow navigation · maintenance cost |
| **Trigger evidence already present** | `PBDIGIT-159` records a contradiction (the health and epic tables vs reality). **Whether that fires the trigger is a PO/ARB call. Engineering does not activate it.** |
| **Scope (when triggered)** | Apply the documentation rule (IDD = implementation decisions · ADR = architecture · retrospective = lessons · CONTEXT = current state): refresh the stale C4 views, remove superseded progress tables, de-duplicate rulings. No redesign. |
| **Acceptance** | Each duplicated ruling has one home, and the other copies point to it. |

#### PBDIGIT-171 — Domain Model Catalogue: the July catalogue is stale and owned by no one

> **⚠️ Correction C-4 (story integrity review 2026-10-01, `2026-10-01-pbdigit-story-integrity-review.md` §8).** The original title said "recommended, never written". That is wrong: a catalogue section exists in `architecture_legacy/audit_system/DDD_Completeness_And_Domain_Model_Catalogue.md` (aggregates, value objects, repositories, events; generated 2026-07-06). It predates the Election OperatingCore, the Membership governance work and the Adjudication process manager. **The story is to refresh it and give it an owner, not to write it.**
| | |
|---|---|
| **Linkage** | BACKLOG.md "Parked — docs candidates" · `.claude/memory/ddd_program_state.md:1237` ("recommended by audit doc — NOT yet created") |
| **Status** | ⬜ OPEN — Low |
| **Business problem** | There is no single place that lists the bounded contexts, their aggregates, value objects, events and owners as they are **implemented**. Every discovery commission re-derives it (the Canonical Discovery phase of the operating loop). |
| **Scope** | A generated or hand-maintained catalogue of what exists in `app/Contexts/*`, with links to the ADRs. Run doc-placement first. It must not invent concepts. |
| **Acceptance** | Every aggregate, VO and event in `app/Contexts/*/Domain` is listed with its context and owner, and the listing is checked against the tree. |

#### PBDIGIT-194 — Reference value objects (`*Ref`) were never reviewed for consistent naming and shape
| | |
|---|---|
| **Linkage** | `ENG-003` (opened from the PB-004 step-1 ARB review, 2026-07-08; ER-03) · ADR-UL-01 (`TargetRef` → `ContestedOutcomeRef`) |
| **Status** | ⬜ OPEN — Low · **sweep only, no behaviour change** |
| **Verified facts** | Five reference VOs exist today: `Adjudication/Domain/Determination/{ChallengeRef, ContestedOutcomeRef, EvidenceEnvelopeRef}` and `Contestation/Domain/Challenge/{ContestedOutcomeRef, RaiserStandingRef}`. `ContestedOutcomeRef` exists **twice**, one per context. That is intended (ADR-T16: each context reconstructs its own VO from primitives), and the sweep must not merge them. |
| **Scope** | Compare the names, factories (`fromString` / `toString`), validation and equality across the five, and record each inconsistency. Renames go through the ubiquitous-language process (an ADR-UL entry), not this story. |
| **Acceptance** | A comparison table. Each inconsistency is either fixed under an ADR-UL entry or accepted with a reason. |

### 5.4 Product gaps carried across epics (README "Product gaps" P-1…P-6)

#### PBDIGIT-172 — The voter journey and the election lifecycle use disjoint vocabularies
| | |
|---|---|
| **Linkage** | README "Product gaps" **P-4** (`EPIC-05`/`06`) · source review `docs/publicdigit/reviews/2026-08-05-election-process-review-phase1.md` |
| **Status** | 🔴 OPEN — **discovery first**. The P-4 one-line record has never been expanded. |
| **Not duplicated** | `PBDIGIT-155` covers the **admin** action vocabulary (RULES / `ElectionAction` enum / `ElectionActions.ts`). `PBDIGIT-48`/`58` cover the election **state** representations. P-4 is the gap between the **voter-step** vocabulary (`voter_slugs.current_step` 1–5, code / agreement / vote / verify / complete) and the **lifecycle** vocabulary (`voting_active`, `counting` …). |
| **Scope** | Map every voter-step term to the lifecycle term it depends on. Record where a step is gated by a state it cannot name. Hand any contract question to the PO. |
| **Acceptance** | A mapping table with evidence. Each mismatch is raised as a story or dismissed with a reason. |

#### PBDIGIT-173 — Deployment documentation: the P-6 claim "none exists" is no longer literally true, and nothing verifies what exists
| | |
|---|---|
| **Linkage** | README "Product gaps" **P-6** · BACKLOG Program Health "Production readiness 🟡 — deployment doc still missing" |
| **Status** | 🔴 OPEN — **verification** |
| **Verified facts** | Files exist: `developer_guide/DEPLOY_TO_PRODUCTION.md`, `DEPLOYMENT_CHECKLIST.md` and `DEPLOYMENT_QUICK_START.md` (last touched 2026-08-01), and `docs/architecture/c4/06_Deployment.md` (2026-07-06). **Their currency has not been checked.** Contradiction found: `.env.example:15` says `DB_CONNECTION=mysql`, but the platform and the test suite run on PostgreSQL (`phpunit.xml:67` `pgsql`; root `CLAUDE.md` stack table). |
| **Scope** | Walk one deployment document against the current stack (Laravel 11, PHP 8.2, PostgreSQL, queue workers for outbox/inbox, scheduler for WP-6 timers). Name one canonical deployment guide. Correct `.env.example`. |
| **Acceptance** | One deployment guide verified by a dry run, with its date. The other files point to it. `.env.example` matches the supported database. |

### 5.5 Correction loop — Adjudication / Contestation programme remainder (EPIC-004 roadmap WP-1…WP-8)

**Source of every status below:** the ARB rulings register `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-30…R-100; no PublicDigit WP ruling after R-100, 2026-08-04) and `docs/implementation/PROGRAM_STATUS.md` ("The four decision gates", A–D). **Closed and not ticketed:** WP-1, WP-2, WP-3A, WP-4A, WP-4B, WP-5, WP-6 (+ remediation), WP-7 (7A/7B/7B-R1/7C), the WP-4 engineering commission (R-98), Engineering Verification Framework v1.x (R-99, effective).

#### PBDIGIT-174 — WP-3B: the determination issuance still mints a fresh provenance chain instead of continuing the challenge's
| | |
|---|---|
| **Linkage** | Roadmap WP-3 → **WP-3B** · R-67 ("WP-3B remains DEFERRED") · `2e75fc69e` ("WP-3B closure verification FAILS") · R-71 (critical path: "dispose of WP-3B") · `86e2b9fe2` (conversation question) |
| **Status** | ⛔ **BLOCKED — ownership never ruled** (stay in 3B, move to 4B, or amend) |
| **Problem** | ADR-T21 requires that one challenge's correction loop be one provenance chain. The "add" half landed under WP-5 (chain-start mint at raise). The "remove" half did not: `EventProvenance::start()` is still called in `CoordinatesAdjudication::issueDetermination()`, with an allowlist entry in `CorrelationIdMintingTest`. So a `DeterminationIssued` starts a **new** conversation, and an auditor cannot follow one challenge from raise to correction by correlation id alone. |
| **Blocking questions (ARB)** | (1) Is `DeterminationIssued` in the same conversation as `ChallengeRouted` (`86e2b9fe2`, R-87)? (2) Which work package owns the removal? (3) Which provenance carrier brings the consumed provenance to the issuance call? |
| **Acceptance (when unblocked)** | `issueDetermination()` continues the consumed provenance. The allowlist entry is removed. A test follows one correlation id from raise to `ChallengeResolved`. |

#### PBDIGIT-175 — WP-4C-1 D3/D4: `AdjudicationFailureDeclared` is not in the Event Catalog and is never published
| | |
|---|---|
| **Linkage** | WP-4C-1 (R-88, R-89, R-93: D1 + D2 accepted) · R-97 (D3 and D4 HELD) · PROGRAM_STATUS gate **D** |
| **Status** | ⏸️ **HELD — Board rulings E1** (is the Event Catalog frozen?) **and E2** (is provenance constitutional?) |
| **Problem** | The failure event exists as a type (D1/D2), but **D3** (the catalog entry) and **D4** (the publication call site) are not built. A failed adjudication is therefore never announced, and Contestation cannot react to it. The failure-declared path of the loop does not run end to end. |
| **Acceptance (when released)** | The catalog lists the event with its producer. One production call site publishes it inside the transactional boundary. An integration test shows Contestation consuming it. |

#### PBDIGIT-176 — WP-4C-2: the Contestation Domain Owner has not answered Q1–Q4
| | |
|---|---|
| **Linkage** | R-88, R-90, R-97, R-98, R-100 · PROGRAM_STATUS gates **A** and **B** |
| **Status** | ⛔ **BLOCKED — business decision.** Engineering and the ARB may **not** answer it (R-90). |
| **Problem** | What Contestation must do after an adjudication fails is undecided, so that part of the correction loop has no specified behaviour. Discovery is CLOSED (R-98). |
| **Sequence (binding, R-100)** | Domain Owner answers Q1–Q4 → engineering records them verbatim, classifies and verifies them → the ARB authorises a **new** WP-4C-2 Tactical DDD Commission → tactical modelling → implementation stories. **This story tracks the first step only.** It does not authorise modelling. |
| **Acceptance** | Q1–Q4 answered by the Domain Owner, recorded verbatim, with a verification report. |

#### PBDIGIT-177 — WP-4D: no authority can deliver an adjudication decision (intake port + interim adapter unbuilt)
| | |
|---|---|
| **Linkage** | Roadmap WP-4D · R-68 · R-72 ("expressly NOT AUTHORIZED") · R-73 (`receiveRulingDecision()` must widen to carry Jurisdiction) · R-87, R-89, R-91 |
| **Status** | ⛔ **NOT AUTHORISED**, and it **must follow `PBDIGIT-178`**. Building it would make R-91's unrepaired path reachable. |
| **Problem** | The adjudication process can open, assemble and wait for a decision, but **no production caller can hand it a decision**. In production the loop therefore cannot conclude. Today only tests drive it, with hand-rolled doubles. |
| **Scope (when authorised)** | The authority-decision intake **port**, an interim administrative adapter, and the Jurisdiction-carrying widening of `receiveRulingDecision()` (R-73). |
| **Acceptance** | An authorised administrator records a decision through HTTP. The decision reaches `receiveRulingDecision()` and produces `DeterminationIssued` through the decorated transactional service. A test traverses the **real** container wiring. |

#### PBDIGIT-178 — The issuance path writes the determination and the outbox row in two separate transactions (R-91)
| | |
|---|---|
| **Linkage** | R-91 (Event D review: decision sound, realisation fails on one path) · R-92 (review adopted; **repair not authorised**) · PROGRAM_STATUS "Transaction-boundary repair — UNAUTHORIZED — wire no production caller ahead of it" (Decision Authority, D-1…D-4) |
| **Status** | ⛔ **UNAUTHORISED — Decision Authority.** Reachability: **LATENT** (no production caller until `PBDIGIT-177`). |
| **Problem (R-91, validated by container resolution)** | `RequestsDeterminationIssuance` resolves to `CoordinatorIssuanceRequest`, which type-hints the **concrete final** `CoordinatesAdjudication`. That class is bound nowhere outside the `AdjudicationService` decorator closure, so the container auto-wires a **second, undecorated instance**. On that path the determination write and the outbox enqueue are two independent writes: a crash between them loses the event that drives the election correction. |
| **Acceptance (when authorised)** | Both writes commit or roll back together on every path. A test resolves the real container binding and fails if the undecorated instance is ever injected. |
| **Related** | `PBDIGIT-158` is on the same issuance path (the marker's two-hour shift). R-82 fixes the marker's **meaning** as "a request was made", not "confirmed". |

#### PBDIGIT-179 — PM-6's issuance-confirmation half is allocated to no work package
| | |
|---|---|
| **Linkage** | R-76 (excluded from WP-4B) · R-82 ("does not allocate PM-6's confirmation half — that remains OPEN") · R-83/R-84 |
| **Status** | ⬜ **OPEN — unallocated.** Needs an allocation ruling before any design. |
| **Problem** | Nothing observes whether a requested determination was actually **written**. `issuance_requested_at` records only that a request was made (R-82), so crash models A and B cannot be told apart, and recovery relies on redrive alone. |
| **Acceptance** | An allocation ruling names the owning work package. The confirmation is then specified there, not in this story. |

#### PBDIGIT-180 — `enforceHorizon()` is not isolated per process (ER-08)
| | |
|---|---|
| **Linkage** | ER-08 · R-95 (**planning authorised, implementation NOT**) · PROGRAM_STATUS gate **C** |
| **Status** | 🟡 **PLAN AUTHORISED — implementation not authorised** |
| **Problem** | R-95 records a defect boundary: `enforceHorizon()` (the adjudication-horizon timer, WP-6) lacks per-process isolation, outside R-81's scope. Concurrent horizon enforcement could act on a process it should not. **⚠️ Observation C-5 (story integrity review 2026-10-01, `2026-10-01-pbdigit-story-integrity-review.md` §8): today nothing calls `enforceHorizon()` in production at all** (no command, no schedule). That gap is `PBDIGIT-198`. |
| **Scope** | Prepare the EP-01 plan (authorised). Implementation follows only after the plan is reviewed and gate C is passed. |
| **Acceptance** | Plan reviewed. After authorisation: a concurrency test shows each process expires exactly once. |

#### PBDIGIT-181 — `ChallengeRaised` publication: the adopted path (R-75) is annotated as prohibited by accepted architecture
| | |
|---|---|
| **Linkage** | R-75 (adoption + ⛔ annotation 2026-08-03) · R-86, R-87 · ADR-UL-01, ADR-T16 |
| **Status** | ⛔ **BLOCKED — contradictory governance record.** The decision and its annotation cannot both be executed. |
| **Problem** | Adjudication needs the `ContestedOutcomeRef`. R-75 adopted "Contestation publishes `ChallengeRaised`", but `ChallengeOutboxAdapter` maps only Routed/Adjudicated/Resolved. Publication, a hydrator, catalog visibility and a consumer are all unbuilt, and the annotation says the adopted path is prohibited. The work is **allocated to no work package**. |
| **Acceptance** | The ARB resolves the contradiction and allocates the work. Delivery stories follow from that ruling. |

#### PBDIGIT-182 — WP-8: the correction loop has never been validated end to end over the real path
| | |
|---|---|
| **Linkage** | Roadmap **WP-8** · R-79 (DEFERRED pending closure of §WP-4; "no implementation work shall commence") · R-97 (reconfirmed) |
| **Status** | ⏸️ **DEFERRED.** Planning allowed, implementation forbidden. |
| **Problem** | No test or run has exercised raise → route → APM → authority decision → issue → correct → resolve, or the failure-declared path, over the real wiring. `CorrectionLoopIntegrationTest` seeds the head and stubs the authority. The roadmap's own phase Definition of Done ("CI green on the merge gate") is also red today (`PBDIGIT-159`/`160`). |
| **Depends on** | §WP-4 closure: `PBDIGIT-175`, `176`, `177`, `178`, then `174`. Gate green: `PBDIGIT-160`. |
| **Acceptance** | The roadmap's WP-8 definition, with triple qualification of every new element. |

### 5.6 Election OperatingCore (EM-IMPL-002 / EM-DOM-001) — held slices

**Existing, not duplicated:** `PBDIGIT-69` (operating-core post-verification items N-3…N-6) · `PBDIGIT-72` (EM-GOV-063 terminal consequence unreachable) · `PBDIGIT-73` (the `GREEN-5` label collision). The decision brief is `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-scoping-decision-brief.md`, with its audit corrections in `…-decision-record.md` §13.

#### PBDIGIT-183 — GREEN-5: report-period expiry (UC-4) is pinned RED and unimplemented
| | |
|---|---|
| **Linkage** | EM-IMPL-002 · `ReportPeriodExpiryHandler.php:33` (pending guard) · `ReportPeriodExpiryHandlerRedTest` ×4 · `PBDIGIT-73` |
| **Status** | ⛔ **DECISION PENDING (PO/ARB, Option A/B/C)**: may UC-4 assume that an established `AcceptanceGateDecision` exists? |
| **Problem** | When a committee's report period runs out, nothing happens: the handler throws `BadMethodCallException`. An election waiting on a committee report can wait for ever. |
| **Acceptance (after the decision and authorisation)** | The 4 existing REDs pass unchanged. Independent verification follows. STOP. |

#### PBDIGIT-184 — GREEN-6: recording a committee's constitution (UC-5) is pinned RED and unimplemented
| | |
|---|---|
| **Linkage** | EM-IMPL-002 · `RecordCommitteeConstitutionHandlerRedTest` ×3 · guide 02 `Q-UC5` STOP ("at GREEN-6") |
| **Status** | ⛔ **BLOCKED — the `Q-UC5` question is unanswered**, and the story follows `PBDIGIT-183` |
| **Acceptance** | The 3 REDs pass unchanged after `Q-UC5` is answered and the slice is authorised. |

#### PBDIGIT-185 — GREEN-7: the four operating-core query services (UQ-1…UQ-4) are pinned RED and unimplemented
| | |
|---|---|
| **Linkage** | EM-IMPL-002 · `QueryServicesRedTest` ×5 · stubs `GateIntervalStateQuery`, `ProgressionEligibilityQuery`, `ClockReadingQuery`, `RecoveryPeriodStatusQuery` (also 6 of `PBDIGIT-161`'s 14 "property never read") · `docs/publicdigit/reviews/2026-09-30-GREEN-7-query-feasibility-audit.md` |
| **Status** | ⛔ **HELD** — after `PBDIGIT-184` |
| **Problem** | Officers cannot ask where an election stands in its operating core (gate interval, progression eligibility, clock, recovery period). Those reads exist only as stubs. |
| **Open item** | The 3 cross-cutting REDs (`RefusalTaxonomyRedTest` ×2, `HistoryKindAssignmentRedTest` ×1) have **no recorded owning slice**. Assigning them is part of `PBDIGIT-160`'s decision record, not this story. |
| **Acceptance** | The 5 REDs pass unchanged after authorisation. The 6 related PHPStan findings disappear. |

#### PBDIGIT-186 — ADR-1 absent-aggregate normalization: the required domain slice does not exist, so the Rule-8 gate is BLOCKED
| | |
|---|---|
| **Linkage** | ADR-1 (DECIDED 2026-08-18) §6(b)/(c) · Rule-8 gate `docs/publicdigit/implementation/2026-08-18-EM-IMPL-002-rule8-gate-adr1.md` (BLOCKED) · `BND-1` (OPEN) · pin `AbsentAggregateReferenceRedTest` (1 failure) · `PBDIGIT-161` (6 null-dereferences in `FillCommitteeSeatHandler.php:107-140`) |
| **Status** | ⛔ **BLOCKED.** The gate's sequence: authorise a DOMAIN slice → domain RED / implementation / verification → normalization slice (UC-1/2/3) → pin GREEN. |
| **Problem** | When a referenced aggregate is absent, seat filling dereferences `null` instead of applying ADR-1's governed absence semantics. The behaviour is decided (ADR-1) but unrealised. |
| **Scope** | **Two slices in one story, on purpose.** The normalization slice cannot start without the domain slice, and neither has another consumer. (a) The domain slice that supplies the contract §6(b) requires. (b) The §6(c) normalization of UC-1/UC-2/UC-3. |
| **Acceptance** | The pin passes unchanged. The 6 PHPStan null-dereferences disappear. `BND-1` is either ruled first, or recorded as not needed, by the ARB. |

#### PBDIGIT-187 — EM-DOM-001 Acts C and D (operational-status adapter and caller) are not authorised
| | |
|---|---|
| **Linkage** | EM-DOM-001 (Phase 1 accepted as analysis; **Act B COMPLETE** `216552aa7`, `b54074900`, 2026-08-19) · roadmap steer "operational-status B → C → D as a separate track" (CONTEXT-publicdigit) |
| **Status** | ⬜ **OPEN — not authorised** |
| **Problem** | The operational-status domain model (Act B) exists, but no adapter (Act C) and no caller (Act D) use it. Production does not yet benefit from it. |
| **Acceptance** | Act C, then Act D, each authorised, RED → GREEN → verified, with a developer guide. |

#### PBDIGIT-188 — A binding RED pin's docblock says the ADR "decision block is blank"; it has been decided since 2026-08-18
| | |
|---|---|
| **Linkage** | `AbsentAggregateReferenceRedTest` docblock · CONTEXT-publicdigit 2026-09-30 ("recommended as a separate one-line doc fix … the pin is binding and out of scope here") |
| **Status** | ⬜ OPEN — trivial, **comment only** |
| **Problem** | A reader of the pin is told that ADR-1 is undecided, which invites someone to "decide" what is already decided. |
| **Scope** | Comment text only. **No assertion, name or behaviour changes.** It touches a test file, so it needs PO authorisation as an edit to `tests/`. |
| **Acceptance** | The docblock cites ADR-1 as DECIDED (2026-08-18) and the Rule-8 gate as BLOCKED. `git diff` shows comment lines only. |

### 5.7 Roadmap epics not yet opened

#### PBDIGIT-189 — Reconcile the programme epic numbering: EPIC-003/004 were reused, EPIC-005/006 are undefined
| | |
|---|---|
| **Linkage** | BACKLOG.md epic table + renumbering note (`e51178f73`, 2026-07-10) · `PublicDigit_Engineering_Protocol_Proposal.md:6` (PROPOSED, implies EPIC-005 = Evidence …) · `.claude/CLAUDE.md` "WP-7C → WP-8 → EPIC-005" |
| **Status** | 🟡 **DECISION REQUIRED (ARB)** |
| **Verified facts** | The only formal numbering is 002 Evidence · 003 Voting · 004 Appointment · 005 Read Models · 006 legacy migration. In practice EPIC-002 became whole-domain strategic discovery (5 ratified BCs, ARB 2026-07-25), EPIC-003 became the "Tactical DDD Entry Assessment" (complete 2026-07-25) and EPIC-004 became Adjudication tactical (WP-1…WP-8). **No ruling records this.** Voting, Appointment, Read Models and the legacy migration therefore have **no epic number that anyone agrees on**. The CLAUDE.md focus line is stale too: WP-7C was accepted (R-66), WP-8 is deferred (R-79) and "EPIC-005" is ambiguous. |
| **Why a story** | `PBDIGIT-190`…`193` cannot be opened under a number that two documents define differently. |
| **Acceptance** | An ARB ruling fixes the epic numbering. The epic table, `PBDIGIT-159`'s NEXT ACTION and the CLAUDE.md focus line (a human edit) cite it. |

#### PBDIGIT-190 — Roadmap epic: Voting migration and verifiability (formerly EPIC-003)
| | |
|---|---|
| **Linkage** | BACKLOG.md epic row "Voting (ballot casting, anonymous storage, tally — migration + verifiability)" · `PBDIGIT-EPIC-05` |
| **Status** | ⬜ **NOT OPENED**. Its number depends on `PBDIGIT-189`. |
| **Relation to the constitutional runtime** | Track A2 (`PBDIGIT-76`…`121`, `137`) migrates **who may vote**. This epic is **ballot casting, anonymous storage and tally** in the greenfield core. **It must not reopen A2's scope.** Its entry criterion is A2's authority switch (`PBDIGIT-117`) and the v1.0 freeze (`PBDIGIT-132`), unless the ARB rules otherwise. |
| **Acceptance (for opening)** | Problem statement → strategic discovery → ARB authorisation, the EPIC-002 pattern. Delivery stories are then written from that discovery and are not invented here. |

#### PBDIGIT-191 — Roadmap epic: Appointment / Governance — delegates, mandates, authority chains (formerly EPIC-004)
| | |
|---|---|
| **Linkage** | BACKLOG.md epic row · the `Committee` and `Governance` contexts exist in `app/Contexts/` |
| **Status** | ⬜ **NOT OPENED** (after Voting, per the ARB order) |
| **Acceptance (for opening)** | As `PBDIGIT-190`. |

#### PBDIGIT-192 — Roadmap epic: Read Models and Public Transparency (formerly EPIC-005)
| | |
|---|---|
| **Linkage** | BACKLOG.md epic row · product gaps P-2/P-3 (count and publication: `PBDIGIT-19`, `PBDIGIT-60`) |
| **Status** | ⬜ **NOT OPENED** |
| **Acceptance (for opening)** | As `PBDIGIT-190`. **Results publication and visibility stay in `PBDIGIT-60`.** This epic is the public read side. |

#### PBDIGIT-193 — Roadmap epic: Election/Lifecycle migration of the legacy operational contexts (EPIC-006)
| | |
|---|---|
| **Linkage** | BACKLOG.md epic row ("sequenced at the retrospective") · `PBDIGIT-58` (legacy election-state migration) · Track A1 (`PBDIGIT-139`…`157`) |
| **Status** | ⬜ **NOT OPENED** |
| **Boundary** | `PBDIGIT-58` and Track A1 already own the **state and governance** migration. This epic is the remainder: moving the legacy `app/Domain/Election`, `app/Http/Controllers/Election` and `app/Contexts/Elections` code into the greenfield structure. Its scope must be cut against those stories at opening. |
| **Acceptance (for opening)** | As `PBDIGIT-190`. |

### 5.8 Added by the story integrity review (2026-10-01): obligations the first sweep missed

Source: `2026-10-01-pbdigit-story-integrity-review.md` §4.1. Each was confirmed in code. None is authorised.

#### PBDIGIT-195 — Creating a committee through the canonical endpoint returns 201 and persists nothing
| | |
|---|---|
| **Linkage** | Inline sweep: `app/Contexts/Membership/Infrastructure/Repositories/CommitteeRepository.php:16` ("TODO: Implement persistence") |
| **Status** | 🔴 OPEN — defect, confirmed by reading the code. No runtime walk yet. |
| **Problem** | `POST /organisations/{slug}/committees/api/create-canonical` (`routes/organisations.php:393-395`, labelled "Governance Matrix + Event Sourcing") → `CreateCommitteeController::store` → `CreateCommitteeHandler`, which calls `$this->repository->save($committee)` and then publishes its events. `AppServiceProvider.php:47-50` binds that port to `Infrastructure\Repositories\CommitteeRepository`: **`save()` is empty and `get()` throws "Not implemented yet"**. The released event `CommitteeEstablished` has **no listener** anywhere in `app/`, so nothing persists it either. The caller receives `201 {committeeId}` for a committee that cannot be read back. |
| **Aggravating facts** | Two different interfaces are both named `CommitteeRepositoryPort` (`Application\Committee\Ports\…` → the stub; `Infrastructure\Persistence\…` → `EloquentCommitteeRepository`, `RepositoryServiceProvider.php:24`). `CreateCommitteeApiTest` (4 passing) asserts only HTTP status, never persistence. **Mitigation:** no frontend calls the endpoint (`git grep create-canonical resources/` → none). |
| **Runtime-verified (F-1 (final closure audit 2026-10-01, `2026-10-01-pbdigit-story-integrity-final.md` §4))** | Probe: `201` + id, and **0 rows** in all 10 committee tables (including `constitutional_committees`) and in 7 event/outbox/audit tables. **Framing correction:** `docs/publicdigit/adr/ADR_COMMITTEE_CREATION_DUAL_PATH.md` (ACCEPTED, 2026-05-13) makes this the **parallel-validation path, "testable but not exposed to users"**. Real committees use the persisting legacy `CommitteeManagementController::store()`. So the defect is a false success on a validation path, and it defeats the ADR's own purpose. It is not lost customer committees. |
| **Scope** | RED: a feature test asserting that the committee exists after a 201. GREEN: bind the handler to a persisting adapter, or retire the endpoint. **Which one is a PO call:** is the ADR's migration (Phase 2 → 4) still intended?. |
| **Acceptance** | A 201 means the committee is persisted and readable. The test asserts the database state, not only the status. |

#### PBDIGIT-196 — No one can file a challenge: the correction loop has no product entry point
| | |
|---|---|
| **Linkage** | `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §1 finding 1: *"The entry point that lets a real actor file a challenge (UI/API) is product work outside this roadmap — flagged, not designed."* · WP-5 (raise → admit → route application services, ACCEPTED) |
| **Status** | 🔴 OPEN — **product capability gap**, owned by no story until now |
| **Problem** | The Contestation core can raise, admit and route a challenge, and the whole correction loop behind it is built. But `git grep -i challenge routes/` returns nothing: **no route, page or API lets a voter, candidate or officer contest a result.** In production the capability is unreachable. |
| **Business question first (PO)** | Who may file a challenge (standing), against what, in which window, and through which channel. The architecture's standing VO (`RaiserStandingRef`) exists, but the product contract does not. |
| **Acceptance** | An entitled actor files a challenge through the product, and it reaches `CoordinatesContestation`'s raise path. A non-entitled actor is refused. A feature test covers both. |
| **Sequence** | After the PO's contract decision. Its production use also depends on the loop being able to conclude (`PBDIGIT-177`, `178`). |

#### PBDIGIT-197 — No determination ever becomes final: the finality evaluator was never built
| | |
|---|---|
| **Linkage** | Roadmap §0 scope item **(f)** "the finality evaluator (temporal policy; `finalize()` gains its caller)" and WP-6 row · WP-6 closure `engineering/verification/reports/2026-08-01-wp6-closure-wp7-transition.md` §2: *"Finality evaluator (§142) — Outside WP-6 scope — Integration Contract Gap"* · EPIC-004K §Q-2 |
| **Status** | ⛔ **BLOCKED — integration contract gap** (ARB) |
| **Problem** | `Determination::finalize()` (`Adjudication/Domain/Determination/Determination.php:119`) has **no caller** in `app/`. The roadmap's rule *"window-closed ∧ unchallenged → `finalize()`"* is unimplemented, so every determination stays non-final indefinitely. Nothing downstream can rely on finality. |
| **Acceptance (when unblocked)** | A scheduled policy finalizes exactly the determinations whose window has closed unchallenged, through the transactional boundary, and emits no event (as ruled). It is tested at the window boundary. |

#### PBDIGIT-198 — The adjudication horizon is never enforced in production: nothing calls `enforceHorizon()`
| | |
|---|---|
| **Linkage** | WP-6 plan `.claude/plans/WP-6-temporal-machinery.md:84` ("Scheduled command(s) to drive the above", roadmap "timer execution") and `:164` (*"Inert today (nothing calls it — WP-6 delivers the scheduler)"*) · WP-6 closure §2 (the three scoped deliverables do **not** include the scheduler) · related `PBDIGIT-180` (ER-08 isolation) |
| **Status** | 🔴 OPEN — **gap confirmed**. Whether the scheduler was deliberately descoped is **UNKNOWN**: no record found. |
| **Problem** | `AdjudicationProcessManager::enforceHorizon()` (`:377`) and `EloquentAdjudicationProcessStore::dueForHorizon()` exist and are MAD-aware (WP-6). But no console command, schedule entry (`routes/console.php`) or job calls them. **An adjudication that never receives a decision never expires**, and `AdjudicationExpired` is never announced in production. |
| **Check before work** | R-91's *"wire no production caller ahead of the repair"* concerns the **issuance** seam. The ARB must confirm that a horizon trigger is outside it, because expiry may interact with redrive. |
| **Acceptance** | A scheduled trigger expires due processes exactly once (with `PBDIGIT-180`'s isolation), and an operational test shows the expiry announcement in the outbox. |

#### PBDIGIT-199 — WP-6B: Contestation never reacts to `AdjudicationExpired`
| | |
|---|---|
| **Linkage** | WP-6 closure §2: *"Contestation consumer of `AdjudicationExpired` — Deferred by authority — Decision C ⇒ WP-6B"* · R-89 annotation ("announcing does not require the consumer to exist") |
| **Status** | ⏸️ **DEFERRED by authority (Decision C)** |
| **Problem** | Even once `PBDIGIT-198` makes expiry happen, the challenge stays open: `git grep AdjudicationExpired app/Contexts/Contestation` → none. The disposition of an expired adjudication is Contestation's decision, and nothing implements it. |
| **Acceptance (when released)** | A Contestation inbox handler disposes of the challenge per the ruled policy, with an integration test from `AdjudicationExpired` to the challenge state. |

#### PBDIGIT-200 — Evidence-demand deadlines: the roadmap requires them, and the domain has no `Demand` concept
| | |
|---|---|
| **Linkage** | Roadmap §0 scope item **(e)** "demand deadlines" and WP-6 row · WP-6 closure §2: *"Evidence-demand deadlines (§80/PM-3) — Outside WP-6 scope — Domain Modelling Gap; no `Demand` concept exists"* |
| **Status** | ⛔ **BLOCKED — domain modelling gap.** This needs modelling (a Tactical DDD commission, the R-100 rule), not code. |
| **Problem** | An adjudication can ask for evidence, but nothing bounds how long a party may take, so a process can wait on evidence for ever (until the horizon, which itself never runs, see 198). |
| **Acceptance** | Modelling produces the `Demand` concept under an authorised commission. Delivery stories are then written from it. **This story tracks the gap only.** |

#### PBDIGIT-201 — Starting the vote still hides a valid voter's membership behind the ambient tenant (the third site of `PBDIGIT-65`)
| | |
|---|---|
| **Linkage** | Finding **B-2**, `docs/publicdigit/reviews/2026-08-15-session3-implementation-record-and-outstanding-work.md` §4.1 (*"the third ambient-scoped lookup … runtime-reproduced. Not repaired: outside the two confirmed violations the grant scoped"*), with Session 1's reproduction `16e4a51a` · `PBDIGIT-65` (sites 1–2 repaired, grant `f6bb5504`) |
| **Status** | 🔴 OPEN — **defect, re-verified in code 2026-10-01.** Not authorised. |
| **Problem** | `ElectionVotingController::start()` (`app/Http/Controllers/ElectionVotingController.php:113-115`) reads `$user->electionMemberships()->where('election_id', …)->first()` **with the `tenant` global scope still applied**. The repaired sibling at `:40-44` uses `withoutGlobalScope('tenant')` plus an `organisation_id` predicate. **A validly admitted voter whose session points at another organisation is redirected away, at the step that issues voting credentials.** |
| **Not duplicated** | `PBDIGIT-65` was scoped to two sites. `PBDIGIT-118`/`121` will eventually replace or delete controller gates, but neither names this defect. |
| **Acceptance** | RED: a feature test in which a voter with an active membership, but a session in another organisation, reaches `start()` and is admitted. GREEN: the `:40-44` pattern applied, with the predicate semantics unchanged. |

#### PBDIGIT-202 — Full Membership voter import always fails: `ElectionMembership::bulkAssignVoters()` does not exist
| | |
|---|---|
| **Linkage** | Session-3 record §4.3 (*"the broken `ElectionMembership::bulkAssignVoters()` import path, which is a guaranteed exception"*, deliberately out of scope then) |
| **Status** | 🔴 OPEN — **defect, re-verified in code 2026-10-01** |
| **Problem** | `POST …/voters/import` (`routes/organisations.php:267`) → `VoterImportController::import` → `VoterImportService::import()`, which on the **Full Membership** path calls `ElectionMembership::bulkAssignVoters(...)` (`app/Services/VoterImportService.php:134`). **No such method exists in `app/`.** The other bulk path, `BulkAssignVotersHandler`, is a separate class used by `ElectionVoterController`. An officer importing voters for a Full Membership election therefore gets a 500 every time. The Election-Only path (`importElectionOnly`) is unaffected. |
| **Acceptance** | RED: a feature test that imports a valid file for a Full Membership election. GREEN: the import uses an existing, tested assignment path, and the test asserts the created memberships. |

#### PBDIGIT-203 — Voter import integrity: swallowed row errors, non-atomic writes, no assignment audit
| | |
|---|---|
| **Linkage** | Finding **B-6**, session-3 record §4.1: boundary-plan **Slices 2–4** (*"import integrity (row errors are silently swallowed; writes are non-atomic; no `assigned_by`/`assigned_at` audit), route/organisation coherence, invitation delivery observability"*) |
| **Status** | ⛔ **NOT AUTHORISED.** The record says the *"conditional EP-01 grant needs re-confirmation"* (PO). |
| **Problem** | An officer can believe an import succeeded when some rows failed. A failure mid-file can leave a partial electorate. Nothing records who assigned a voter, or when, which matters in a dispute about who was eligible. |
| **Runtime evidence (F-2 (final closure audit 2026-10-01, `2026-10-01-pbdigit-story-integrity-final.md` §4))** | `CsvVoterImportTest::admin_can_import_voters_via_csv` passes on `assertRedirect()`, yet a probe shows **0 memberships created** and only the flash *"0 users created, 0 already existing"*, with no row error. The test sends a bare `email` CSV; election-only mode expects `firstname;lastname;email`. **So the swallowed-errors defect is live, and the test conceals it.** |
| **Scope** | The boundary plan's Slices 2–4, as already planned. **This story only gives them a product ID.** It must not widen the plan. |
| **Acceptance** | Per the boundary plan. Minimum: every rejected row is reported, the import is all-or-nothing, and every assignment carries an actor and an instant. |

#### PBDIGIT-204 — ADR-2 restoration provenance: the domain cannot say why an election was restored
| | |
|---|---|
| **Linkage** | ADR-2 Rule-8 gate `docs/publicdigit/reviews/2026-08-18-EM-IMPL-002-rule8-gate-adr2.md` (Q1–Q5, all 🔴) · Act-B design map `…-act-b-domain-design-map.md:268` (DEP-6…DEP-12, A-9) · `PBDIGIT-72` (owns only the EM-GOV-063 consumer of this gap) · `PBDIGIT-186` (owns ADR-1 only) |
| **Status** | ⛔ **BLOCKED** — a domain slice is required first (the ADR-2 Rule-8 gate). Partially unblocked per `8ffa4328c`, with GREEN-5 blocked by D3. |
| **Problem** | ADR-2 rules that a restoration carries its causal origin. The domain has no `RestorationOrigin` or equivalent (Q2). `RecoveryProcess` carries no origin (Q1). `ElectionRestored::$returnsToGate` is non-nullable, so the no-`RecoveryProcess` path cannot be represented (Q3). Today the Application layer constructs the causal claim at the only site in the repository: `FillCommitteeSeatHandler.php:140 → :156` (`$decision->gate()`). **An auditor cannot tell why an election resumed.** |
| **Acceptance (when unblocked)** | The domain slice the ADR-2 gate requires, then the application change. The gate's Q1–Q5 are answered "yes" by the code, and verified independently. |

### 5.9 Added by the final closure audit (2026-10-01)

Source: `2026-10-01-pbdigit-story-integrity-final.md` §5 (205–207) and §7 (208). Each was confirmed in code or by a test run. None is authorised.

#### PBDIGIT-205 — A raised challenge can be admitted but never dismissed or put under investigation
| | |
|---|---|
| **Linkage** | ADR-T20 (ARB domain decision: `Raised→Admitted→Investigating→Routed→Adjudicated→Resolved`) · WP-5 plan `.claude/plans/WP-5-raise-path.md:39,75` (wired `raise`/`admit`/`route` only; `dismiss` and `beginInvestigation` listed as existing aggregate behaviour) · `Challenge.php` docblock: *"Optional investigation phase (modeled per ADR-T20; trigger driven later)"* |
| **Status** | ⬜ **OPEN — allocated to no work package** (needs an allocation, as for `PBDIGIT-179`) |
| **Problem** | `Challenge::dismiss()` and `Challenge::beginInvestigation()` have **no caller anywhere in `app/`**, and `CoordinatesContestation` exposes only `raise`, `admit` and `route` (`:69`, `:86`, `:95`). An inadmissible challenge can therefore never be dismissed: it stays *Raised* for ever, or is admitted against the officer's judgement. The investigation phase the ARB modelled cannot be entered. |
| **Depends on** | `PBDIGIT-196` (who files and who decides admissibility is the same product contract) |
| **Acceptance** | An authorised officer dismisses a raised challenge with a reason, or opens an investigation, through an application service. Each emits its ruled event. Feature tests cover both, and cover the refusal of each from the wrong state. |

#### PBDIGIT-206 — A challenge never lapses: the ruled timeout has no trigger
| | |
|---|---|
| **Linkage** | `Challenge::lapse()` docblock (*"System-initiated timeout. Allowed only before a decision. Per Round 50-07 v1.2 the Lapsed timeout emits NO domain event … Audit observes it via the decision-window timeout log"*) · ADR-T20 · same temporal class as `PBDIGIT-198` |
| **Status** | ⬜ **OPEN — allocated to no work package** |
| **Problem** | `lapse()` has no caller: no command, schedule or job (`git grep` over `app/`, `routes/console.php`). A challenge left undecided past its window stays open indefinitely, and the decision-window timeout log the ruling relies on is never written. |
| **Acceptance** | A scheduled trigger lapses exactly the challenges whose window passed undecided, writes the timeout log entry, and emits no event (as ruled). It is tested at the window boundary. |

#### PBDIGIT-207 — 99 write-path feature tests assert only a success status, so they cannot detect a lost business outcome
| | |
|---|---|
| **Linkage** | Closure audit §6.3 (heuristic over `tests/Feature`: 851 tests that POST/PUT/PATCH/DELETE, 101 assert only a success status). **Two were probed, and both conceal a real failure:** `CreateCommitteeApiTest::test_creates_committee_successfully` (201, nothing persisted → `PBDIGIT-195`) and `CsvVoterImportTest::admin_can_import_voters_via_csv` (redirect, **0 memberships created**, no error shown → `PBDIGIT-203`). The candidate list is in the closure audit, appendix A. |
| **Status** | 🔴 OPEN — **verification obligation (class V)**. Not authorised. |
| **Business problem** | **HTTP success is not business success.** For an election platform, "request accepted" and "the eligible-voter list, the committee or the fee now exists" are different facts. 2 of 2 probed candidates hid a defect. |
| **Scope** | Triage the 99 remaining candidates with the same probe: run the request, then inspect the persisted state. Each candidate is classified as **legitimate** (preview or negative cases that must persist nothing), **concealing** (each concealment raised as its own story) or **fixture drift**. Strengthen each kept assertion to the business outcome. **No production change in this story.** |
| **Acceptance** | Every candidate is classified. Every concealment found has its own story. Every kept test asserts its business outcome (rows, state, events) as well as the status. |

#### PBDIGIT-208 — 1,345 failing tests in 213 files belong to no story: the legacy test estate outside the greenfield core
| | |
|---|---|
| **Linkage** | Closure audit §7: the full `Unit` + `Feature` run of 2026-10-01 (`vendor/bin/phpunit --testsuite=Unit,Feature` against `nrna_test`): **6,762 tests · 1,589 errors · 376 failures · 3,921 risky**, so **1,965 failing tests in 335 files**. After mapping every failing file to its owning story (76, 77, 78, 139, 157, 158, 160/183–186, 165, 166, 167), **213 files / 1,345 tests remain unowned**. |
| **Status** | 🔴 OPEN — **verification obligation (class V)**. Not authorised. **Classify before any repair.** |
| **Business problem** | This is the test estate that would detect a regression in the voting journey, demo elections, election administration, organisations, tenancy and authentication. **It is red, and no gate runs it** (the merge gate runs only `Architecture` + `GreenfieldCore`). A product regression in those areas is invisible today. |
| **Evidence: not order artefacts** | 10 failing files re-run in isolation: **9 fail identically** (e.g. `VoteSubmissionWorkflowTest` 43/43, `TenantIsolationTest` 33/33, `RealVoteEnforcementTest` 23/23). Only `PublicApplicationApprovalTest` was order-dependent. Dominant causes are schema and fixture drift: 263 "column does not exist" · 190 NOT NULL violations · 112 "invalid input syntax for type uuid" · 99 "unrecognized configuration parameter" · 256 unique violations (part fixture, part order). |
| **Areas (tests / files)** | voting & demo journey 690 / 103 · election administration 206 / 43 · organisations & tenancy 200 / 26 · authentication & users 137 / 20 · other 112 / 21. The file list is in the closure audit, appendix B. |
| **Relation to existing stories (PO scope question)** | The C-1 stories `76`/`77`/`78` repair **named** voting files (their baseline: 218 failing tests). **The other 690 voting & demo failures here are the same kind of drift in files C-1 does not name.** The PO may widen C-1 instead of keeping that part here. **Until then, 208 owns them, so no failing file has two owners or none.** |
| **Scope** | (1) Per area, classify each failing test: fixture drift · schema drift · obsolete (tests a retired path) · order-dependent · **production defect** (each raised as its own story). (2) Per area, a repair or retirement plan. **No production change in this story.** (3) The 3,921 risky notices of the full run are recorded per area, alongside `PBDIGIT-162` (which owns the gate suite's risky signal). |
| **Acceptance** | Every one of the 1,345 failing tests is classified. Every production defect found has its own story. Each area is either green or retired with a recorded reason. A decision on running the estate in CI is put to `PBDIGIT-123`'s owner. |

## 6. Recommended order of the new stories

The order follows dependency and cost. It authorises nothing; each story still needs PO authorisation, one at a time.

| Step | Story | Why here |
|---|---|---|
| 1 | `PBDIGIT-159` status integrity | Costs nothing, and stops stale status steering work |
| 2 | `PBDIGIT-158` (existing) issuance marker | A correctness defect in persisted legal time, independent of everything else |
| 3 | `PBDIGIT-160` gate vs RED-first (**ARB decision**) | Until it is decided, a red gate hides regressions |
| 4 | `PBDIGIT-161` PHPStan (the 2 independent findings) | After the gate treatment is decided |
| 5 | `PBDIGIT-162` risky notices (plan authorised, R-95) | Makes the gate's most sensitive signal readable |
| 6 | `PBDIGIT-165`, `166`, `167` test estate | Restores the tests outside the greenfield core. Classify before repairing. |
| 7 | `PBDIGIT-163` Shared PHPStan, then `PBDIGIT-164` mutation ratchet | 164 needs a green gate (Infection stops on defect) |
| 8 | Decisions, in parallel: `PBDIGIT-189` epic numbering · `176` Q1–Q4 · `175` E1/E2 · `181` R-75 · `183` A/B/C · `184` `Q-UC5` · `179` PM-6 allocation | Human acts. Engineering prepares evidence only. |
| 9 | `PBDIGIT-178` → `PBDIGIT-177` → `PBDIGIT-174` → `PBDIGIT-182` | The correction loop's production head. The order is binding: 178 before 177 (R-91). |
| 10 | `PBDIGIT-183` → `184` → `185`; `186`; `187`; `188` | OperatingCore, as each decision lands |
| 11 | `PBDIGIT-168`, `169`, `171`, `172`, `173`, `194`; `170` (only on a trigger) | Documentation and product discovery, any time |
| 12 | `PBDIGIT-190`…`193` epics | Open only after `PBDIGIT-189`. Voting opens after A2's authority switch `PBDIGIT-117` and the v1.0 freeze `PBDIGIT-132`. |

**The constitutional-runtime tracks are unchanged.** A1 starts at `PBDIGIT-139` part A, A2 at `PBDIGIT-76`, B at `PBDIGIT-133` ([execution map](../implementation/2026-10-01-constitutional-runtime-execution-map.md)). The new stories do not enter that dependency graph, except where §5 names a link: `167` ↔ `120`, `190` after `117`/`132`, `193` cut against `58` and Track A1.

---

**Traceability:**
- **Sources:**
  - `docs/implementation/backlog/BACKLOG.md`
  - `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-67…R-100)
  - `docs/implementation/PROGRAM_STATUS.md`
  - `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md`
  - `.claude/CONTEXT-publicdigit.md`
  - `docs/publicdigit/backlog/README.md` and the story files
  - `docs/implementation/2026-10-01-push-b-status-verification.md`
- **Re-verification commands (2026-10-01):**
  - `php artisan test --env=testing tests/Feature/Finance/FeePaidProjectionTest.php` (5 failed)
  - `php artisan test --env=testing tests/Unit/Middleware/ValidateVotingIpTest.php` (1 failed)
  - `php -l app/Http/Middleware/VoterSlugStep.php` (parse error)
  - `vendor/bin/phpstan analyse --level=max app/Contexts/Shared/Infrastructure` (120, indicative)
  - `git grep` for the `EventProvenance::start` sites, the `*Ref` VOs and `submit_seleccted`
- **Index:** `docs/publicdigit/backlog/README.md` rows `PBDIGIT-159`…`194`.
