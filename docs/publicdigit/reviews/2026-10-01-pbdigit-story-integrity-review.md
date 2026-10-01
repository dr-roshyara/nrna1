# PBDIGIT story integrity and completeness review

| | |
|---|---|
| **Date** | 2026-10-01 |
| **Kind** | ⚠ authority: generated. A read-only integrity review of the PBDIGIT inventory. **It authorises nothing, implements nothing, and decides no product or governance question.** |
| **Reviews** | [`2026-10-01-pbdigit-completeness-audit.md`](2026-10-01-pbdigit-completeness-audit.md) (`PBDIGIT-159`…`194`), `PBDIGIT-158`, the README index and the source → story mappings |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared). **Independence caveat:** this is the same process that wrote the audit under review. It re-derived the claims from the repository and found errors in its own audit (§8). A fresh process would be stronger evidence. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |
| **Read-only evidence runs (2026-10-01)** | Tests run with `php artisan test --env=testing` against the test database, never the development database: `FeePaidProjectionTest` 5 failed · `OutboxIntegrationTest` 3 failed · `CommitteeAssociationRepositoryTest` 11 failed · `CreateCommitteeApiTest` 4 passed · `CanonicalizationEquivalenceTest` 5 passed · `ValidateVotingIpTest` 1 failed. **No file under `app/`, `tests/`, `routes/` or `config/` was modified. No dependency was installed.** |

## 1. Executive verdict

**PASS WITH CORRECTIONS.**

- **The 36 stories hold up.** Every story's source problem exists and is still open. No story duplicates an existing PBDIGIT story. Factual errors were found in 5 stories and corrected (§8).
- **The audit's completeness claim did not hold.** The expanded sweep found **10 PublicDigit delivery obligations with no story**, each re-checked in code (§4.1), and one source the audit never read: `docs/implementation/Architecture_Debt_Backlog.md` (AD-006…AD-010). The 10 are now `PBDIGIT-195`…`204`. Three of them are live product defects today: `201` (a valid voter refused at `start()`), `202` (Full Membership import always fails) and `195` (a committee created by API is not saved).
- **One decision blocks a clean inventory:** **10 of the 36 stories are internal engineering or documentation work**, and the product backlog's own rule says such work does not belong there (§3.1, decision **D-1**). They are left in place, marked, and await the PO's ruling. Nothing was downgraded silently.

## 2. Coverage

```text
Existing PBDIGIT IDs reviewed (00–158, gaps 52–57 excluded):  153
New stories reviewed:                                          36  (PBDIGIT-159…194)
Source items reviewed:                                         41 (audit) + 61 inline code markers + 47 skipped/incomplete tests
                                                               + 5 Architecture Debt items + WP-6 closure register
                                                               + documentation sweep (485 docs, 903 pairs, 158 hand-checked, §4.4)
DONE:                                                          20 (audit) + 1 (CanonicalizationEquivalence whitespace gap, now passing)
Already covered by an existing story:                          8 (audit), of which 2 are PARTIAL (P-2, P-5)
New valid stories (of the 36):                                 36 exist and are open · 26 product / verification / decision-gate · 10 engineering or documentation, pending decision D-1
Duplicates:                                                    0 duplicate stories · 1 duplicate ID (PBDIGIT-69, owned by PBDIGIT-134)
Missing stories found:                                         10 → PBDIGIT-195…204
Excluded:                                                      9 (audit) — all confirmed legitimate (§7)
Recorded, not ticketed (decision or drift, §4.2/§4.4):         21 inline markers · 7 governance groups · 36–41 EM-OPEN questions · 3 pending D-1
Governance decisions required:                                 D-1 (product-backlog scope rule) + 7 decision-gate stories
                                                               (160, 176, 179, 181, 183, 189, 197) + those inside BLOCKED stories
```

## 3. Story-by-story integrity (`PBDIGIT-159`…`194`)

### 3.1 The class question (decision D-1)

The product backlog states its own scope rule. `docs/publicdigit/backlog/README.md`:6 says *"Engineering debt lives there; what a customer can or cannot do lives here."* §"Product gaps" adds: *"Architecture debt is deliberately excluded from this backlog … It raises the cost of changing the software, not of using it."* **Precedent contradicts the rule.** `PBDIGIT-123`…`133` and `156` (CI jobs, scanner fixes, documentation corrections) are already PBDIGIT stories. So the repository holds two incompatible rules, and the review classifies each story without choosing between them:

| Class | Meaning | Rule status |
|---|---|---|
| **P** | Product: what a customer, voter or officer can or cannot do, or a product defect | In scope under both rules |
| **V** | Verification: the ability to prove product behaviour (a gate, a failing or dead test of product code) | In scope by precedent (123, 133, 156) |
| **G** | A decision gate: the deliverable is a recorded human decision that unblocks product stories | In scope by precedent (the H decision tickets 90–100, 138, 141, 150) |
| **E** | Internal engineering or documentation with no product or verification outcome | **Out of scope under the README rule; in scope under the precedent → D-1** |

**D-1 (PO): do E-class items get PBDIGIT stories?** If yes, amend the README's two sentences; the 10 E stories stay. If no, those 10 become "engineering link" rows: the `ENG`/`AD` record stays the work item, and the PBDIGIT ID is retired with a note, not reused. **Engineering does not choose.** The requesting review's instruction ("Do not force engineering concerns into product stories merely to achieve a count") points to *no*. The user's earlier instruction (every TODO as a PBDIGIT story) points to *yes*.

### 3.2 The table

Columns: does the problem exist (**Exists**), is it still **Open**, its **Class**, whether it duplicates another story (**Dup**), and whether its scope is correct (**Scope**).

| Story | Source | Exists | Open | Class | Dup | Scope | Action |
|---|---|---|---|---|---|---|---|
| 159 | ENG-015 | ✅ re-run | ✅ | E (status docs) | no | ✅ | **KEEP if D-1 = yes**, else engineering link. Precedent: `PBDIGIT-130` |
| 160 | ENG-013 | ✅ 16 of 17 RED | ✅ | G | no | ✅ | **REQUIRES DECISION** (ARB) |
| 161 | ENG-014 | ✅ 22 errors | ✅ | V | **partial**: 20 of 22 resolve inside 183–186 | ⚠️ | **Corrected** (§8 C-1): the 2 "genuine" findings are type-level only. Its remaining own scope is small. Recommend **DOWNGRADE TO ENGINEERING LINK** after D-1. |
| 162 | ENG-012 + F-7C-6 + F-WP6R-1 | ✅ 112 risky | ✅ | V | no | ✅ one signal | **KEEP**. R-95 authorises planning only. |
| 163 | ENG-002 | ✅ 18 / 120 | ✅ | E (type-level) | no | ✅ | **D-1** |
| 164 | ENG-004 | recorded baseline | ✅ | E | no | ✅ | **D-1** |
| 165 | AD-006 | ✅ 5/5 + 3/3 fail | ✅ | V | no | ⚠️ overstated | **Corrected** (§8 C-2): test-fixture drift only, production unaffected; `OutboxIntegrationTest` added. **KEEP**. |
| 166 | F-7C-1 | ✅ | ✅ | V | no | ✅ | **KEEP** |
| 167 | F-7C-4 + F-7C-5 (+ AD-008) | ✅ 11 + 1 reproduced | ✅ | V | ⚠️ F-7C-4 is also bound to `PBDIGIT-120` | ✅ | **Corrected** (§8 C-3): AD-008 linked. **KEEP**. F-7C-4: see §3.3. |
| 168 | ENG-006 | ✅ no guide | ✅ | E | no | ✅ | **D-1** |
| 169 | ENG-010 | recorded 2026-08-01, **not re-run** | probably | E | no | ✅ | **D-1**. Re-measure before any work. |
| 170 | ENG-005 | ✅ | dormant | E | no | — | **D-1**. Recommend engineering link either way: a dormant, unactivated item adds noise to a delivery backlog. |
| 171 | Parked docs | ⚠️ **partly false** | partly | E | no | ⚠️ | **Corrected** (§8 C-4): a July catalogue exists. **D-1** |
| 172 | P-4 | ✅ (README) | ✅ | P (discovery) | no; distinct from 155, 48/58 | ✅ | **KEEP** |
| 173 | P-6 + health row | ✅ | ✅ | P (production readiness) | no | ✅ | **KEEP** |
| 174 | WP-3B | ✅ `CoordinatesAdjudication.php:76`, allowlist `CorrelationIdMintingTest.php:52` | ✅ | P (audit trail) | no | ✅ | **KEEP** (BLOCKED) |
| 175 | WP-4C-1 D3/D4, R-97 | ✅ | ✅ HELD | P | no | ✅ | **KEEP** |
| 176 | WP-4C-2, R-90/R-100 | ✅ | ✅ | G | no | ✅ | **REQUIRES DECISION** (Domain Owner) |
| 177 | WP-4D, R-72/R-73 | ✅ `receiveRulingDecision()` has no caller in `app/` or `routes/` | ✅ | P | no | ✅ | **KEEP** |
| 178 | R-91/R-92 | ✅ (register) | ✅ | P (data integrity) | no; related to 158 | ✅ | **KEEP** |
| 179 | PM-6, R-82 | ✅ | ✅ | G (allocation) | no | ✅ | **REQUIRES DECISION**. No owner was invented. |
| 180 | ER-08, R-95 | ✅ (register) | ✅ | P/V | no | ✅ | **KEEP**. **Corrected** (§8 C-5): `enforceHorizon()` has no production caller at all → new `PBDIGIT-198` |
| 181 | R-75 + annotation | ✅ | ✅ | G | no | ✅ | **REQUIRES DECISION** (ARB) |
| 182 | WP-8, R-79 | ✅ | ✅ DEFERRED | V | no | ✅ | **KEEP**. Dependencies now include 195…200 where they apply (§4.1). |
| 183 | GREEN-5 | ✅ `ReportPeriodExpiryHandler.php:33` | ✅ | P | no; 73 is the label finding | ✅ | **KEEP** (decision pending) |
| 184 | GREEN-6 | ✅ `RecordCommitteeConstitutionHandler.php:29`; `Q-UC5` in guide 02 | ✅ | P | no | ✅ | **KEEP** |
| 185 | GREEN-7 | ✅ four query guards | ✅ | P | no | ✅ | **KEEP** |
| 186 | ADR-1 / Rule-8 / BND-1 | ✅ pin fails | ✅ | P | no | ✅ two slices on purpose (one cannot start without the other) | **KEEP** |
| 187 | EM-DOM-001 C/D | ✅ (CONTEXT, `216552aa7`) | ✅ | P | no | ✅ | **KEEP** |
| 188 | pin docblock | ✅ `AbsentAggregateReferenceRedTest.php:39` | ✅ | E | **belongs with 186** (same pin) | — | Recommend **MERGE into 186**, or engineering link (D-1) |
| 189 | epic table | ✅ (§10 below) | ✅ | G | no | ✅ | **REQUIRES DECISION** (ARB) |
| 190 | epic: Voting | ✅ not opened | ✅ | P (epic) | no; related to `PBDIGIT-EPIC-05` (product journey epic, a different kind) | not yet specified, by design | **KEEP** |
| 191 | epic: Appointment | ✅ | ✅ | P (epic) | no | as 190 | **KEEP** |
| 192 | epic: Read Models | ✅ | ✅ | P (epic) | no; 60 keeps publication | as 190 | **KEEP** |
| 193 | epic: legacy migration | ✅ | ✅ | P (epic) | boundary with 58 and A1 is stated | as 190 | **KEEP** |
| 194 | ENG-003 | ✅ 5 `*Ref` VOs | ✅ | E | no | ✅ | **D-1** |

**Tally:** 26 P/V/G (KEEP or REQUIRES DECISION) · 10 E pending D-1 (159, 161's residue, 163, 164, 168, 169, 170, 171, 188, 194; 188 also has a merge recommendation) · 0 MARK DONE · 0 REPLACE. **No story's source was invented, and no ownership was invented.**

### 3.3 "One problem = one story": the two merges

| Merge | Verdict | Evidence |
|---|---|---|
| `ENG-012` + `F-7C-6` → 162 | ✅ **One problem.** The same symptom ("removed error handlers other than its own") on the same suite, measured at three dates (57 → 101/105 → 112), and **one acceptance outcome** (each entry attributed; the count pinned or 0). F-WP6R-1 (99) is the same signal. | PB-007 IDD §2c-ii; BACKLOG ENG-012; this run |
| `F-7C-4` + `F-7C-5` → 167 | ⚠️ **One problem class, two fates.** Both are legacy tests failing outside the gate, and one triage table answers both. But F-7C-4 tests `ValidateVotingIp`, which `PBDIGIT-120` deletes. **Its real owner is 120.** | `ValidateVotingIpTest` 1 failure; `PBDIGIT-120` scope |

**Proposed (not applied):** re-link F-7C-4 → `PBDIGIT-120` as EXISTING, and keep 167 for F-7C-5 + AD-008. That makes 167 a single-owner story. It is a scope change to an existing ticket, so it needs the PO's approval.

**Split check.** No story among 159…194 carries two **independent business outcomes**. `PBDIGIT-186` carries two slices deliberately (the second cannot start without the first, and neither has another consumer), as its own text explains. `PBDIGIT-173` also corrects `.env.example`. That is a sub-task of "one verified deployment guide", not a second outcome.

## 4. Missing TODOs: the expanded sweep

Three sweeps ran:
- the inline markers in `app/`, `routes/`, `config/`, `database/` and `resources/js` (§4.2);
- the skipped and incomplete tests in `tests/` (§4.3);
- a scripted documentation sweep of `docs/publicdigit/` and PublicDigit files in `docs/implementation/` and `docs/plans/` (§4.4).

The documentation sweep covered 485 documents: 1,598 marker lines, then 903 (document, ID) pairs. After a strict pass, 158 rows were checked by hand against newer documents. **Every defect below was re-checked in code by this review.** KnowledgeOS material was excluded throughout.

### 4.1 Confirmed delivery obligations with no story → `PBDIGIT-195`…`204`

| New | Found by | What | Evidence (re-verified 2026-10-01) |
|---|---|---|---|
| `PBDIGIT-195` | inline sweep | Canonical committee creation returns 201 and persists nothing | `CommitteeRepository.php:16` empty `save()`; bound at `AppServiceProvider.php:47-50`; no `CommitteeEstablished` listener; `routes/organisations.php:393-395` |
| `PBDIGIT-196` | roadmap read | No product entry point to file a challenge | roadmap §1 finding 1; `git grep -i challenge routes/` → 0 |
| `PBDIGIT-197` | WP-6 closure read | No determination ever becomes final | `Determination::finalize()` has 0 callers; closure §2 "Integration Contract Gap" |
| `PBDIGIT-198` | `enforceHorizon()` check | The adjudication horizon is never enforced in production | 0 callers, no command or schedule; WP-6 plan `:84`, `:164` |
| `PBDIGIT-199` | WP-6 closure read | WP-6B: Contestation never reacts to `AdjudicationExpired` | closure §2 "Deferred by authority — Decision C"; 0 Contestation references |
| `PBDIGIT-200` | WP-6 closure read | Evidence-demand deadlines need a `Demand` concept that does not exist | closure §2 "Domain Modelling Gap"; roadmap §0(e) |
| `PBDIGIT-201` | documentation sweep (B-2) | `start()` still hides a valid voter's membership behind the ambient tenant | `ElectionVotingController.php:113-115` vs the repaired `:40-44` |
| `PBDIGIT-202` | documentation sweep | Full Membership voter import always fails | `VoterImportService.php:134` calls `ElectionMembership::bulkAssignVoters()`, defined nowhere; route `organisations.php:267` |
| `PBDIGIT-203` | documentation sweep (B-6) | Import integrity: swallowed row errors, non-atomic writes, no assignment audit | session-3 record §4.1 (boundary-plan Slices 2–4) |
| `PBDIGIT-204` | documentation sweep (ADR-2) | The domain cannot say why an election was restored | ADR-2 Rule-8 gate Q1–Q5 🔴; `FillCommitteeSeatHandler.php:140→156` |

**So the audit's claim "every remaining PublicDigit delivery TODO has exactly one story" was false by 10.** Four of the misses are correction-loop obligations sitting in documents the audit read only through the ARB register (the WP-6 closure package). Four sat in a session record the audit did not read. Two were in code.

### 4.2 Inline code markers (61: 57 `TODO`/`FIXME`/`TBD`, 4 `@todo`)

> **⚠️ Correction (closure audit, `2026-10-01-pbdigit-story-integrity-final.md` §6.1):** the true count is **60**. One hit was `XXX` in a phone placeholder (`VoteDenied.vue:98`), and `GeographyAntiCorruptionLayer` has 10 markers, not 11. The table below is kept as written.

| Class | Count | Items | Disposition |
|---|---|---|---|
| **Delivery defect** | 1 | `Membership/Infrastructure/Repositories/CommitteeRepository.php:16` | → `PBDIGIT-195` |
| **Business decision required before any story** | 21 | `ConstitutionalTransitionGuard.php:260` (the paid-plan payment check is a stub that returns `true`. Routing to manual approval still works through `requiresApproval()`, but the platform admin's `capacity_eligibility` precondition always passes; the threshold itself is the open `EM-OPEN-019`) · committee-wing eligibility (`Central`/`Student`/`Women`/`Youth`WingStructure ×6, and the `Member.php` placeholders ×8: age, gender, duration and student status are "MVP: accept all") · `NepalGeographySeeder.php` ×4 (reference data covers part of Nepal only) · `TenantUserProvisioningService.php:203,207` (no welcome email) | **Recorded, not ticketed.** Each needs the PO to say the rule is a requirement before its absence is a defect (the README's governing rule). Questions for the PO: Q-a billing in scope? · Q-b wing criteria binding? · Q-c geography coverage target? · Q-d welcome email required? |
| **Unreachable code** (route loading commented out at `MembershipServiceProvider.php:586`) | 6 | Mobile `MemberController:108`, Mobile/Desktop `RegisterMemberRequest` ×3, `MobileMemberRegistrationService:82`, `DesktopMemberResource:135` | Not delivery. Their disposition depends on whether the Membership HTTP layer is revived. |
| **Dead or obsolete** | 5 | `CreateMemberRequest:45` (no references) · `MemberDirectoryRepository:13` (no references) · `Shared/Outbox/OutboxRepository.php:11,17` (sole consumer `MemberDirectoryProjectionWorker` is referenced nowhere) · `Models/ElectionMembership.php:25` ("create ElectionPolicy": `app/Policies/ElectionPolicy.php` exists) | Not delivery |
| **Engineering refactor** | 22 | `GeographyAntiCorruptionLayer` ×11 (delegates to the working legacy service) · `EloquentGeoUnitRepository:82` · `GovernanceGeoUnitQueryService:24` · Membership jobs "Phase 3C" ×7 · `geo_level` diagonal seed ×2 (`GovernanceMatrix:40`, `CreateCommitteeController:28`) | Not delivery |
| **Low, not consumed by the UI** | 3 | `MyCommitteesQueryService:52,64,65` (`joinedDate`/`roleInCommittee` null; rejected applications are indistinguishable from none). `git grep joinedDate resources/js` → 0 | Not ticketed. Re-raise if a page consumes them. |
| **Governance** | 2 | `Domain/Election/Enum/VoterSourceStrategy.php:34,141` (authority names "TBD via governance review") | Governance |
| **Product research** | 1 | `Services/GaneshStandardFormula.php:113` (validate thresholds empirically) | Not delivery |

### 4.3 Skipped and incomplete tests (47) and test `TODO`s (11)

| Group | Count | Disposition |
|---|---|---|
| `VoterVerificationTest` ×5, "voting enforcement endpoint not yet implemented" | 5 | **Covered.** Officer-verification enforcement is A2 evidence + evaluator (`PBDIGIT-106`, `108`), and the inert-rule finding is recorded in the 76–157 backlog §1 |
| `IpEvidenceTopologyEquivalenceTest` 4.1–4.10 incomplete | 10 | **Covered** by A2 E-1 / S-1 (`105`–`108`, `114`). Scenario-level coverage is not verified. |
| `DeviceDuplicateDetectionTest` ×3, "Implement DeviceFingerprintService first" | 3 | **Covered:** H-2 / `PBDIGIT-91`, `106` |
| `CommitteeAssociationRepositoryTest` suspension/termination audit ×2 | 2 | **Partial.** The domain enforces actor and reason (`CommitteeAssociation::suspend/terminate`). The repository-level pins sit in the file AD-008 lists → `PBDIGIT-167` |
| `CanonicalizationEquivalenceTest` "IP normalization gap" | 1 | **DONE.** The test now passes (the whitespace gap is closed) |
| Architecture skips for missing directories, DB-driver skips, rate-limit and mail-simulation skips | 26 | Engineering or environment. Not delivery. |
| `ContextBoundaryFitnessTest` "after P12" ×2, `OverlaySemanticsTest:120` dual-axis, `GovernanceLevelControllerTest` Vitest | 4 | Engineering. `OverlaySemanticsTest` relates to `PBDIGIT-150` (H-14) |

### 4.4 Documentation sweep: unresolved, unowned, and not delivery

These are recorded so they cannot be mistaken for ticketed work. **None becomes a story**, because each is a decision or a documentation-drift item.

| # | Finding | Where | Class |
|---|---|---|---|
| G-a | `BND-3`: the overlay's aggregate and persistence boundary, explicitly deferred (D4) | `2026-08-18-EM-DOM-001-inbound-findings-verification.md:102` | Governance (ARB) |
| G-b | Start-of-voting governance gate: Decisions 1–6 (G-1/G-2, G-3, G-6 cron authority, EM-OPEN-021, EM-OPEN-025, R-3); the decision log is blank | `2026-09-13-decision-request-start-of-voting-governance-gate.md:39-160` | Governance (PO/ARB). Thematically near `PBDIGIT-64`/`67`, which do not cite it |
| G-c | In which lifecycle phases may suspension, removal and reinstatement happen? | `2026-09-16-decision-request-election-membership-governance-lifecycle-window.md` | Governance (PO) |
| G-d | Schedule-correction questions SCB-3, -4, -7.1, -7.2, -8 (+EM-OPEN-006), -9(a)(b)(c) | `2026-08-16-governance-ruling-schedule-correction-boundaries.md:117` | Governance |
| G-e | Suspension-credential questions EM-OPEN-006/007/008 (Q-E1, Q-E2, BR-1.5/1.6); `PBDIGIT-68` cites the other BR-1.x items only | `ELECTION_MANIFESTO.md:439-441` | Governance (PO) |
| G-f | Adjudication Q-2: Contestation/retention windows (Q-1 answered) | `EPIC-004A_Adjudication_Tactical_Discovery.md:27` | Probably governance (UNKNOWN) |
| G-g | **36 `EM-OPEN-*` questions are open with 🔴 in the question cell** (019, 024–031, 033–037, 043, 045, 046, 048, 052, 053, 055, 061, 063, 066, 068, 071, 075–078, 081, 082, 088, 089, 093, 094). **41 rows carry 🔴 in some column** (adding 001, 042, 049, 069 and 109, where it may mark a blocked dependency rather than an open question). The difference is not resolved here. **No PBDIGIT story cites any `EM-OPEN` number.** Only 001 (through BR-1.12 → 68) and the unmarked 002–005, 009, 011, 013 (→ 68) and 018 (→ 50) are covered through their underlying IDs. | `ELECTION_MANIFESTO.md` §9 | Governance: the manifesto is their register. **Recommendation:** stories that depend on an `EM-OPEN` question should cite it by number (traceability, not new scope). |
| E-a | **Manifesto drift:** EM-OPEN-001/002/004 were resolved by the 2026-09-16 decision but are still shown open. The related implementation `c48aa33c0` was committed with no story. | `2026-09-16-election-membership-suspension-removal-governance-decision.md:4` vs `ELECTION_MANIFESTO.md:434-437` | Documentation drift (E). **Pending D-1.** It is a stale-status repair like 159. |
| E-b | B-5: the Slice-1 entitlement pins were never executed (blocked on canonical Manifesto rule IDs) | session-3 record `:121` | V, blocked on governance (Manifesto IDs). **Pending D-1.** |
| E-c | `Architecture_Debt_Backlog.md` AD-007, AD-009 (re-verified: `EventRegistryCompletenessTest` lists 3 produced types; the outbox adapters write at least 7: `ChallengeRouted`, `ChallengeAdjudicated`, `ChallengeResolved`, `DeterminationIssued`, `AdjudicationExpired`, `ElectionCorrectionApplied`, `FeePaid`), AD-010 | `docs/implementation/Architecture_Debt_Backlog.md` "Open items" | V-class engineering ("Governance Verification Drift"). **Pending D-1.** **Also an integrity finding: `BACKLOG.md`'s Debt table lists only AD-006**, so this file's open items are invisible from the programme backlog. |

**Resolved later (checked, not ticketed):** CF-3 · Q-TEN-2 · N-1/N-2, OBS-1 · G-2a · G-7 · BND-2 · V-7 (`ProcessElectionAutoTransitions.php:68` now uses `Transition::automatic`) · EM-BRQ S-01…S-20 · CG-1/CG-2 (probably settled by D-ENT-1).


## 5. Duplicate and numbering problems

| Check | Result |
|---|---|
| **A. Duplicate IDs** | **One:** `PBDIGIT-69` is claimed by `PBDIGIT-69-voter-eligibility-cache-is-tenant-unaware.md` **and** `PBDIGIT-69-operating-core-post-verification-items.md`. Owned by `PBDIGIT-134`, which is **not renumbered here** (renumbering is 134's scope). |
| **B. Existing stories missing from the README** | **`PBDIGIT-69` (operating-core file)** has no row; only the cache file has one. **Repaired:** row added, flagged as the colliding ID. (`PBDIGIT-60`, `61`, `62`, `71`…`75` were repaired by the audit.) `PBDIGIT-00` is a README section, not a row, by design. |
| **C. Orphan README entries** | **None.** Every `](PBDIGIT-…md)` link resolves to a file, and every README row ID has a home (a file, an epic heading, or a spec in the 76–157 backlog or the audit). |
| **D. Number gaps** | **`52`…`57` only.** They are deliberate and recorded in the 76–157 backlog §1. Not renumbered. |
| **E. Highest ID** | **`PBDIGIT-194`** at review start, verified by a script over files, epic headings, both backlog specs and the README. **After this review: `PBDIGIT-204`.** `PBDIGIT-6569` in the 76–157 backlog is a test-class string (`Pbdigit6569ReplayTest`), not a story ID, and is recorded there as such. |
| Double homes | **None.** No ID has both an individual file and a spec in a collection document. |
| Namespace hazard (observation) | Programme epics `EPIC-002…006` and product epics `PBDIGIT-EPIC-01…06` coexist. "EPIC-05" (Voting, product) and "EPIC-005" (Read Models or Evidence, programme) differ by one zero. `PBDIGIT-189`'s ruling should address it. |

## 6. Source → PBDIGIT traceability

Each story's chain (story → source → authority → evidence → acceptance) was checked. **Every one of the 36 has a named source and a testable acceptance.**

| Weak link | Stories | Note |
|---|---|---|
| Source dated, not re-run | 164 (recorded MSI baseline), 169 (2026-08-01 link scan), 167 (F-7C-5 July count) | **Flagged.** Each story's scope already starts with re-measurement. |
| Authority is a register entry, not code | 175, 176, 178, 179, 180, 181, 182 | **Correct by nature.** They are governance-blocked. The register lines are cited, and the code facts were spot-checked (no caller for `receiveRulingDecision()` or `enforceHorizon()`; `EventProvenance::start` at `CoordinatesAdjudication.php:76`). |
| Source contradicted by code | 171 (a catalogue exists), 165 (production unaffected), 161 ("genuine" overstated) | **Corrected** (§8). |
| Acceptance needs a decision first | 160, 176, 179, 181, 183, 184, 189 | **Correct.** The acceptance is the recorded decision. |
| Ownership invented | none | 179 and 181 explicitly ask for allocation rather than assume it |

**Source → story map** (additions to the audit §3, used for the BACKLOG mapping):

| Source | Story |
|---|---|
| `Architecture_Debt_Backlog.md` AD-006 (`FeePaidProjectionTest` + `OutboxIntegrationTest`) | `PBDIGIT-165` |
| AD-008 (`CommitteeAssociationRepositoryTest` ×11, `CommitteeMembershipApplicationTest` ×6) | `PBDIGIT-167` |
| AD-007 (relay failure × single-transaction harness), AD-009 (event-registry completeness checks 3 of ≥7 produced types; re-verified), AD-010 (no general handler-registration assertion) | **No story:** V-class engineering, pending **D-1**. They are the "Governance Verification Drift" class. If D-1 = yes, they become V stories like 162. |
| P-2 | `PBDIGIT-19` (verify the count) **+ `PBDIGIT-190`** (tally model). **Corrected:** the audit said 19 alone |
| P-5 | `PBDIGIT-17` (precedence) **+ `PBDIGIT-135`** (route loading). **PARTIAL:** disposal of the four legacy redirect closures (`routes/election/electionRoutes.php:151-165`, including `submit_seleccted`) is owned by no story. The disposal depends on 17/135's findings, so no story is created yet. |
| Architecture-suite failures (3) | `ConstitutionalBoundaryTest` → `PBDIGIT-156`. `ElectionLifecycleStateConsistencyTest` and `ElectionStateMachineConsistencyTest` → `PBDIGIT-123` ("triage of the 3 failing Architecture tests"). **Note:** AD-002/AD-003 fixed these same two tests on 2026-07-06, so they have **regressed**. 123 should know this. |
| WP-6 closure "outside scope" items | `PBDIGIT-197` (finality), `PBDIGIT-199` (WP-6B), `PBDIGIT-200` (demand deadlines) |
| Roadmap "entry point … product work outside this roadmap" | `PBDIGIT-196` |
| Session-3 record B-2 · §4.3 `bulkAssignVoters` · B-6 | `PBDIGIT-201` · `PBDIGIT-202` · `PBDIGIT-203` |
| ADR-2 Rule-8 gate (Q1–Q5, DEP-6…12, A-9) | `PBDIGIT-204` |

## 7. Governance-owned exclusions: verified

| Excluded item | Legitimate? | Basis |
|---|---|---|
| `ENG-001` Process Learning System | ✅ | The methodology is frozen (`.claude/CLAUDE.md`, 2026-08-01). No product or verification outcome. |
| `ENG-007` reminder-hook mapping | ✅ | `.claude/scripts/`: assistant tooling only |
| `ENG-008`, `ENG-009`, `ENG-011` | ✅ | KnowledgeOS / knowledge-platform tooling; `ENG-009` blocked on OQ-5 |
| 38D-02 Capability Relationships | ✅ | An ARB governance-track artifact, waiting on the 38D-01 digest. No delivery owner or outcome exists. |
| COL-5a mechanism (R-96) | ✅ — **and escalate** | The register says *"owner vacated … governance must name a home"*. A story would invent the owner the ruling says is missing. **The vacancy itself is a governance action item.** |
| R-99 "v1.x has no spec document" | ✅ | A verification-architecture governance artifact; the closure is effective |
| C-1…C-17, OQ-M1, OQ-M2, OQ-2 | ✅ | Review-method adoption is a PO act under the method freeze (README) |
| WP-6 closure §3 register (AT-EVT-001, DC-1, H-1…H-3, A-1/A-2/A-4, B-1…B-6/C-1…C-5) | ✅ — **newly recorded** | Each has destination "ARB ruling" or "charter correction" in `engineering/verification/reports/2026-08-01-wp6-closure-wp7-transition.md` §3, and its own text says *"none is an implementation defect"*. **DC-1 (invalid-MAD never decided)** is the nearest to delivery, but the code fails closed. |
| `ELECTION_MANIFESTO.md` open questions (`EM-OPEN-*`) | ✅ — **newly recorded** | Business-rule questions for the PO. The manifesto is their register. A question is not a delivery story, and stories cite them where they bind. |

**The rule was applied as written.** No unresolved governance matter became a story unless a delivery obligation with an owner and an acceptance outcome existed. `PBDIGIT-197`…`200` qualify because the **approved roadmap scope** (§0 items e and f) is the obligation and the closing slice named the gap.

## 8. Corrections made (factual, non-architectural)

| # | Where | Was | Now | Evidence |
|---|---|---|---|---|
| C-1 | audit `PBDIGIT-161` | "2 genuine domain findings" | **Type-level findings; no behavioural defect established.** `AppointmentsAwaited:26` is a defensive guard that PHPStan proves unreachable from its docblock type. `RecoveryProcess:92` is an array-shape mismatch. | source read |
| C-2 | audit `PBDIGIT-165` | "two tenant identities … crossed by type confusion"; "first decide which `TenantId` is canonical" | **Test-factory drift only.** Production (`ApproveMembershipApplication.php:57`) already passes the Shared `TenantId` through `ApproveMembershipApplicationCommand`. `OutboxIntegrationTest` (×3) added. The canonical-type decision is not needed for the repair. | test runs; source read |
| C-3 | audit `PBDIGIT-167` | F-7C-4 + F-7C-5 | **+ AD-008** (`Architecture_Debt_Backlog.md`). Reproduced: `CommitteeAssociationRepositoryTest` 11 failed with *"Member does not exist"* (fixture drift) | test run |
| C-4 | audit `PBDIGIT-171` | "recommended, never written" | **A catalogue section exists:** `architecture_legacy/audit_system/DDD_Completeness_And_Domain_Model_Catalogue.md` (aggregates, VOs, repositories, events; generated 2026-07-06). It predates the OperatingCore, Membership-governance and Adjudication-APM work. The story becomes *"refresh and own"*, not *"write"*. | file read |
| C-5 | audit `PBDIGIT-180` | isolation only | **+ observation:** `enforceHorizon()` has **no production caller** (no command, no schedule) → `PBDIGIT-198` | `git grep` |
| C-6 | audit §3.4 P-2 | → `PBDIGIT-19` | → `PBDIGIT-19` + `PBDIGIT-190` | §6 |
| C-7 | README | `PBDIGIT-69` operating-core file unindexed | row added (collision flagged → 134) | §5 |
| C-8 | BACKLOG mapping table | no AD-007…010 | AD-006/AD-008 mapped; AD-007/009/010 listed as pending D-1 | §6 |

**Not changed (they need a decision):** the D-1 reclassification of 10 stories · the F-7C-4 → 120 re-link · the 188 → 186 merge · `.claude/CLAUDE.md`'s stale focus line.

## 9. Implementation readiness (nothing is authorised)

| Readiness | Stories |
|---|---|
| **Specified, unblocked: may be proposed for authorisation** | **201 · 202** (live product defects, small, RED-first) · 158 · 195 · 159* · 162 (plan authorised by R-95) · 165 · 166 · 167 · 172 · 173 · 196 (after its business-contract question, see its spec) · 163* · 164* (after 160) · 168* · 169* · 171* · 194* — *\*subject to D-1* |
| **Decision required first** | 160 (ARB) · 176 (Domain Owner) · 179 (allocation) · 181 (ARB) · 183 (PO/ARB A/B/C) · 184 (`Q-UC5`) · 189 (ARB) · 197 (integration contract) · **D-1** (PO) |
| **Blocked or held by recorded governance** | 174 · 175 · 177 (after 178) · 178 · 180 (implementation) · 182 (R-79) · 185 · 186 · 187 · 198 (R-91 check) · 199 (Decision C) · 200 (modelling gap) · 203 (grant re-confirmation) · 204 (ADR-2 domain slice) |
| **Not yet sufficiently specified (by design)** | 190 · 191 · 192 · 193 (open by discovery, the EPIC-002 pattern) · 170 (dormant) · 161 (residue after 183–186) |

## 10. EPIC numbering (`PBDIGIT-189`): re-verified

| Epic | Formal record | Actual use | Verdict |
|---|---|---|---|
| EPIC-002 | Evidence discovery (`e51178f73`, BACKLOG:30) | Whole-domain strategic discovery, 14 `EPIC-002_*.md` docs; ARB 2026-07-25 context map | Done, but its **scope differs** from the record |
| EPIC-003 | Voting | `EPIC-003_Tactical_DDD_Entry_Assessment.md` (complete 2026-07-25) | **Reused**, no ruling found |
| EPIC-004 | Appointment / Governance | Adjudication tactical (`EPIC-004*`, WP-1…8) | **Reused**, no ruling found |
| EPIC-005 | Read Models | `PublicDigit_Engineering_Protocol_Proposal.md:6` (PROPOSED) implies Evidence; `.claude/CLAUDE.md` names it as the focus | **Ambiguous** |
| EPIC-006 | legacy migration | not opened | as recorded |

**`PBDIGIT-189` correctly represents this as a decision.** The evidence is insufficient to decide, so the status stays **UNKNOWN / DECISION REQUIRED**, and no numbering was proposed.

## 11. `PBDIGIT-00`: its relationship to the inventory

**`PBDIGIT-00` is a verification umbrella that has run once and is now historical evidence, plus a standing release rule.** It is not a delivery story.

- **It ran** (2026-08-06), and its negative result became `PBDIGIT-35`…`41`. `PBDIGIT-38` "is the gate that replaced it" (README) and is FIXED & VERIFIED.
- **Its standing rule** ("no story may be marked `VERIFIED`" until the journey completes) is **ambiguous today.** The voting journey completes since `PBDIGIT-38`, but the README does not say the rule was lifted. **That is a PO question. It is recorded, not answered.**
- **Its journey does not include the correction loop.** The Level-0 journey ends at *Audit → Archive* and has no *challenge a result* step. So `PBDIGIT-174`…`182` and `196`…`200` sit **outside** what `PBDIGIT-00` can ever verify. Their end-to-end verification is `PBDIGIT-182` (WP-8), and the product entry point is `PBDIGIT-196`. **Observation for the PO:** the Level-0 journey may need a *Challenge → Determination → Correction* segment. That is a product-definition change and is not made here.
- **No new story depends on `PBDIGIT-00`.** `PBDIGIT-190` (Voting epic) will inherit its journey as the acceptance baseline.

## 12. Stories requiring a human decision (summary)

| Decision | Who | Unblocks |
|---|---|---|
| **D-1:** do engineering and documentation items get PBDIGIT stories? (README:6 rule vs the 123–133 precedent) | PO | Reclassification of 159, 161 (residue), 163, 164, 168, 169, 170, 171, 188, 194; and whether AD-007/009/010, manifesto drift and B-5 get stories |
| Gate vs the open RED-first suite | ARB | 160 → 161, 164 |
| Q1–Q4 | Contestation Domain Owner | 176 |
| PM-6 allocation | ARB | 179 |
| R-75 contradiction | ARB | 181 |
| UC-4 Option A/B/C | PO/ARB | 183 |
| `Q-UC5` | PO | 184 |
| Epic numbering | ARB | 189 → 190…193 |
| Finality integration contract | ARB | 197 |
| Horizon trigger outside R-91? | ARB | 198 |
| Challenge filing contract (who, against what, when) | PO | 196 |
| Canonical committee endpoint: live capability or retire? | PO | 195 |
| Import Slices 2–4 grant re-confirmation | PO | 203 |
| Re-link F-7C-4 → 120 · merge 188 → 186 | PO | 167, 188 |
| Is `PBDIGIT-00`'s "no story `VERIFIED`" rule lifted? | PO | marking any story VERIFIED |
| Should the Level-0 journey gain a *challenge* segment? | PO | product definition |
| Q-a…Q-d (billing, wing criteria, geography coverage, welcome email) | PO | whether those code markers become stories |

## 13. Recommended next step

**Do not start implementation from this inventory until D-1 is answered.** It changes what the backlog *is*, and so what "complete" means. If D-1 is answered, the smallest evidence-rich first candidates are the two live product defects, `PBDIGIT-201` and `PBDIGIT-202`: each is a single site, already reproducible, and RED-first. The constitutional-runtime track starts (`139` part A, `76`, `133`) are unchanged. **The choice is the PO's.**

---

**Traceability:**
- **Reviewed:** `2026-10-01-pbdigit-completeness-audit.md` · `docs/publicdigit/backlog/README.md` and every story file · `docs/implementation/backlog/BACKLOG.md` · `docs/implementation/Architecture_Debt_Backlog.md` · `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` · `docs/implementation/PROGRAM_STATUS.md` · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` · `.claude/plans/WP-6-temporal-machinery.md` · `engineering/verification/reports/2026-08-01-wp6-closure-wp7-transition.md` · `docs/publicdigit/reviews/2026-08-15-session3-implementation-record-and-outstanding-work.md` · `…-rule8-gate-adr2.md` · `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`
- **Method:**
  - read-only sweeps: `git grep` for code markers;
  - a scripted documentation scan by a read-only agent, whose one stray scratch file was moved out of the tree (`git status` matches the session start);
  - the test runs listed in the header.
- **Stories added:** `PBDIGIT-195`…`204`, specified in the audit document §5.8.
