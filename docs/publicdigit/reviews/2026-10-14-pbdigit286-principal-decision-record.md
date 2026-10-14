# PBDIGIT-286 constitutional IP limit: principal decision record

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Date:** 2026-10-14 · **Basis:** HEAD `87f76d4e9`, read-only verification · **Revision:** r2. Consistency corrections C-1 to C-8, authorised by the Principal on 2026-10-14; the r1 text is replaced, not kept.
**Status:** **RECORD OF REQUIREMENTS AND OPEN DECISIONS.** It approves nothing beyond R1–R5, which the Principal confirmed. Every design statement below is a **recommendation** unless it is labelled `CONFIRMED BY PRINCIPAL`. PBDIGIT-286 stays `OPEN`.

**Companions:**
- current state: [`2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md`](2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md);
- plan (DRAFT): [`docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md`](../../plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md);
- backlog: `docs/publicdigit/backlog/PBDIGIT-286-one-visible-ip-limit.md`.

**Labels:**
- `CONFIRMED BY PRINCIPAL`;
- `EXISTING BEHAVIOR — NOT YET APPROVED`;
- `RECOMMENDATION`;
- `OPEN — PRINCIPAL DECISION REQUIRED`.

**Evidence:** **[V]** verified in source · **[I]** inference · **[U]** not verified.

**Terms:**
- **System A** = the general per-IP vote limit (`IpVoteLimit`).
- **System B** = the constitutional limit (trust policy).
- **"Voting opens"** = the moment `elections.voting_locked` becomes `true` (§2.3).

---

## 1. Confirmed requirements (binding)

| ID | Requirement | Status |
|---|---|---|
| **R1** | System A: the general limit is 10. It stays independent of System B. Existing approved behaviour, including the election-specific override, is preserved; any conflict is reported, not changed. | `CONFIRMED BY PRINCIPAL` |
| **R2** | System B is configured per election by an appropriately authorised Election Committee member. It may be set or changed **before voting opens**, and stays unchanged for the whole voting period once voting opens. Configuration and authorization are enforced **server-side**; a UI restriction alone is insufficient. | `CONFIRMED BY PRINCIPAL` |
| **R3** | System A and System B stay separate. Neither silently changes the other. **Each applicable control may refuse a vote on its own; a vote is accepted only when every applicable control permits it** (§2.7). The base value is not the effective limit for every voter. | `CONFIRMED BY PRINCIPAL` |
| **R4** | A whitelisted IP bypasses both vote-count limits. Whitelisting never bypasses IP binding. Regression tests are required. | `CONFIRMED BY PRINCIPAL` |
| **R5** | No implementation before the plan and the required architecture decisions are approved. | `CONFIRMED BY PRINCIPAL` |

**Not approved by these requirements:** the System B default of 6 and the trust-scaling rules (D4, D4-N, D7a).

**Gates:** plan approval, ADR approval, migration approval, the individual decisions and test-environment authorization are **separate gates** (Principal, 2026-10-14; plan §4).

## 2. Verified current behaviour

### 2.1 System A [V]
- **Default:** `IpVoteLimit::DEFAULT_LIMIT = 10` (`app/Services/Voting/IpVoteLimit.php:31`).
- **How the limit is chosen** (`limitFor`, `:53-64`):
  1. a whitelisted IP has no limit;
  2. otherwise, if the election override is on (`ip_restriction_enabled` + `ip_restriction_max_per_ip`, 1–50, `UpdateElectionSettingsRequest.php:26`), that value;
  3. otherwise `MAX_USE_IP_ADDRESS`;
  4. otherwise 10.
- **Counting:** completed votes in **this election** (`election_id`), by IP group (IPv4 address; IPv6 /64). It ignores the tenant context (`withoutGlobalScopes`, `votesFrom` `:70`).
- **Refuses** when votes ≥ limit (`:154`).
- **Checked at** start, code entry, verification, and the vote, under a per-network PostgreSQL advisory lock (`castRefusal`, `:98`; lock `:120-138`).
- **The override is security policy:**
  - it is in `Election::SECURITY_POLICY_FIELDS` (`Election.php:63-69`);
  - it is locked from `ElectionLifecycle::isLocked()`, which is false only for Draft, SetupAdministration and SetupNomination (`ElectionLifecycleEngineImpl.php:295-304`);
  - the lock is applied by `ElectionSettingsController::assertSecurityPolicyMutable` (`:148-151`) and the model guard (`Election.php:2329`).

**R1 conflict check:** none. The override is part of the PBDIGIT-278 item 8 behaviour specified by the Principal on 2026-10-13.

### 2.2 System B [V]
- **Base:** `elections.max_votes_per_ip`, default 6 (migrations `2026_05_26_000000…:14`, `2026_05_27_120000…:22`; fallback `TrustPolicyEvaluator.php:143`).
- **Switched on** when `network_binding_strategy !== 'none'` (`:145`). The values are `none`, `ip_count` and `ip_strict` (`ElectionConstitutionSchema.php:7`); the live policy treats everything except `none` the same.
- **Effective limit by trust level** (`NetworkTrustEvidence.php:65-68`): Unverified `max(1, ⌊N/2⌋)` · Attested `N` · ContinuityVerified `⌊3N/2⌋` · RegistrarAttested `2N`.
- **Every voter is Unverified today:** the evaluator hard-codes `attested = false` and `registrarId = null` (`TrustPolicyEvaluator.php:178-179`), and ContinuityVerified is never produced (`VerificationAttestationRecord.php:52-60`).
- **Refuses** when votes **>** the effective limit (`NetworkTrustEvidence.php:32`).
- **Counting:** completed votes in this election from the **exact** IP, under the tenant global scope (`TrustLegitimacySecurityVerdict.php:68-71`; `Code` uses `BelongsToTenant`). The voter population is the same as System A's (one election = one tenant); only the grouping and the scope's robustness differ.
- **Checked at:**
  - code entry, first (`CodeController.php:727`);
  - the vote, at the top of `store()` (`VoteController.php:1491`), inside the cast transaction and **before every lock**.
- **Not configurable:** no request, controller, route or page reads or writes the two columns, and they are not in `$fillable` (`Election.php:121`). **Every existing election is therefore at the column defaults.** [V/I: no write path; direct database edits not excluded]
- **Refusals** are recorded only in the forensic table `election_security_events` (`SecurityEventRecorder.php:56`; no user ID, unkeyed IP hash). There is no admin-facing audit entry.
- **Generic voter message:** `CodeController.php:751`, `VoteController.php:1524`, `:2056`.
- **The finding reports N, not the effective limit** (`NetworkBindingPolicy.php:58`).

### 2.3 Lifecycle and opening voting [V]
- **Two paths set `voting_locked = true`:**
  - **Explicit:** `ElectionManagementController::openVoting` (`:918`, route `/open-voting`, `can:manageSettings`, `routes/election/electionRoutes.php:290`) → `Election::transitionTo` (`:1664`). It runs under the cache lock `election_transition:{id}` (block 5 s) and in a `DB::transaction`. The side effect is `applySideEffectsForOpenVoting` (`:1897`, a raw `DB::table` update with **no condition on the current flag**, no model events, no `settings_version` bump). It also sets `nomination_completed = true`.
  - **Scheduled:** `ProcessElectionAutoTransitions` → `Election::enforceVotingLock` (`:1609`, a model `update`). It takes **no cache lock and no transaction**.
- **The flag never resets:** `voting_locked` is never set back to `false` in `app`, and closing voting keeps it `true` (`:1935-1945`). Suspend and resume don't touch `voting_locked` or `voting_ends_at`.
- **VotingActive is derived:** `voting_locked` + window open + approved candidates (`ElectionLifecycleEngineImpl.php:110-112`). ReadyForVoting is also derived (`:118-122`) with no event. **An election can go straight from SetupNomination to VotingActive.**
- **Existing freeze rules:**
  - security fields lock at `isLocked()` (from ReadyForVoting, and in SubmittedForApproval/Approved/Rejected);
  - **all** settings freeze while VotingActive (`ElectionSettingsController::settingsFrozen`, `:47-50`).
- **Concurrency gap:** the settings save (`:73-134`) has an optimistic `settings_version` check, but no transaction and no row lock. A save that starts before opening can commit after it.
- **Transaction isolation:** `config/database.php` sets none, so the server default applies. PostgreSQL's default is READ COMMITTED; the production server setting is [U].

### 2.4 Authority and audit [V]
- **Creation:** elections are created by organisation owners or admins (`ElectionPolicy::create`, `:121-127`).
- **Settings, settings update and opening voting:** require `manageSettings`, an **active chief or deputy** officer of the election (`ElectionPolicy.php:46-53`; `UpdateElectionSettingsRequest::authorize()` `:14-17`; route `can:` on `/open-voting`). Officer roles are chief, deputy and commissioner (`…create_election_officers_table.php:22`).
- **Settings changes** are audited as `settings_changed` (category `committee`) through `ElectionAuditService` (`ElectionSettingsController.php:260-269`), which writes **JSONL files outside the database transaction**.
- **UI:** one flag disables all security fields, `securityFieldsDisabled = securityPolicyLocked || settingsFrozen` (`Settings/Index.vue:76`).

### 2.5 Snapshot [V]
- **Created at creation:** `security_articles_snapshot` and `constitutional_hash`, version `D.2.5` (`Election.php:2379-2397`).
- **Immutable after creation** (`:2300-2313`).
- **Never read or validated** anywhere in `app`.
- **Nullable column** (`2026_05_26_000002…:13`). Whether elections without a snapshot exist is [U].

### 2.6 Verification evidence [V]
- **Storage:** `voter_verifications`, one row per (election, voter) (`…create_voter_verifications_table.php:27`).
- **Status:** `active` (default) or `revoked`.
- **Method:** `officer_attestation` or `officer_bulk_attestation`.
- **No expiry and no registrar concept.**
- **Re-verification overwrites the row** (`EloquentIdentityEvidenceStore.php:29`).
- **Admission is separate:** both methods have the same **admission** effect (PBDIGIT-213 C4-D2, `VerificationMethod.php`). Admission counts any active row (`EloquentVotingEntitlementQuery.php:84-103`).
- **Method history:** the method column dates from `110387f63` (2026-10-04) and bulk from `9c482db85` (2026-10-05), so no legacy row can be bulk.
- **Fail-closed today:** an evaluation error refuses at code entry (`CodeController.php:728-729`) and at the vote (outer `catch` → `failCastClosed`).

### 2.7 What "the stricter limit wins" means [V for today's code; R3 for the rule]
**A vote is accepted only when every applicable control permits it.** Each control evaluates its own threshold, counting rule, network grouping and scope:
- System A: per IP group, ≥;
- System B: per exact IP (unless D6 changes it), >, today.

If any control refuses, the vote is refused. No numeric comparison between the two limits takes place. When several would refuse, the **first evaluated** control (System B, at code entry and at the vote) gives the voter message and the single refusal audit entry. **D6 (grouping) stays open;** neither system's grouping changes without approval.

## 3. Decisions

### 3.1 Identifier mapping
Two numberings exist. **This record uses the Principal's numbering of 2026-10-14.** The earlier sheet (current-state review §6) maps as follows:

| Earlier | This record |
|---|---|
| D1 | D1 |
| D2 (voter message) | AUD-2 |
| D3, D3a | D3, D3a |
| D4 (revoked/missing) | D3-E |
| D5 (interpreter minimum) | D7a |
| D6 (default 6) | D4-N |
| D7 | D7 |
| D7a (deployment) | D10 |
| D8 (IPv6) | D6 |
| D9 | D9 |
| D10 (admin panel) | D1-UI |
| D11 | D11 |
| Q-P1 | D3-P |
| Auth-1 | AUTH-T |
| — | **D8-L (new in r2: legacy elections)** |

### 3.2 Decision table

| ID | Question | Status | Options | Recommendation (not approved) | Evidence |
|---|---|---|---|---|---|
| **D1 / C1** | Freeze point for System B | Requirement `CONFIRMED` (R2). Design `RECOMMENDATION` (ADR-A) | **F-a:** add to `SECURITY_POLICY_FIELDS` (locks from ReadyForVoting, earlier than R2 allows). **F-b:** dedicated freeze on `voting_locked = true`, with the §4.1 protocol. | **F-b.** The general lock and the System A lock stay unchanged. | §2.3 |
| **D1-UI** | How the admin interface shows the two limits | `RECOMMENDATION` | A separate System B panel with **its own freeze flag** (derived from `voting_locked`, separate from `securityPolicyLocked`) showing base N, the effective limits for the trust levels that occur, the §2.7 rule, and whitelist/IP-binding notes. The UI is not a security boundary. | as stated | §2.4 |
| **D1-V** | Validation range for N | `OPEN — PRINCIPAL DECISION REQUIRED` | 1–50, or another range | 1–50 | none exists today |
| **D2** | System A default and override | `CONFIRMED BY PRINCIPAL` (R1; override per PBDIGIT-278 item 8) | — | no change | §2.1 |
| **D3** | Trust level of active individual vs bulk officer evidence | `OPEN — PRINCIPAL DECISION REQUIRED` | **A:** both Attested. **B:** individual Attested, bulk Unverified. **C:** neither (both Unverified). | **A** | §2.6 |
| **D3a** | Do legacy verification records (before 2026-10-04) count as attestation? | `OPEN — PRINCIPAL DECISION REQUIRED` | yes / no | yes, consistent with admission | §2.6 |
| **D3-E** | Missing, revoked or unreadable evidence | `RECOMMENDATION` | — | Missing or revoked → Unverified. Never set `revoked = true` (CRITICAL denial in `VerificationAttestationPolicy`) or `registrarId` (`RegistrarAttestationElevation` signal). Store error → fail closed, as today. | §2.6 |
| **D3-P** | Evidence read path | `RECOMMENDATION` | **(i)** a trust-only read port; admission untouched. **(ii)** a shared port that admission also adopts. | **(i).** The duplicated predicate is recorded as known debt. | §2.6 |
| **D4** | Approval of the scaling table | `EXISTING BEHAVIOR — NOT YET APPROVED` | approve / amend | approve | §2.2 |
| **D4-N** | Default base N = 6 | `EXISTING BEHAVIOR — NOT YET APPROVED` | keep / change | keep | §2.2 |
| **D6** | Does System B count through System A's grouping (`IpVoteLimit::votesFrom`)? | `OPEN — PRINCIPAL DECISION REQUIRED` | reuse (IPv6 /64, tenant context ignored) / keep exact and tenant-scoped | reuse | §2.1, §2.2 |
| **D7** | Effective limit L: refuse the vote that would **reach beyond** L (`>=` on completed votes: L votes allowed), or only after L is **exceeded** (`>`: L + 1 allowed)? | `OPEN — PRINCIPAL DECISION REQUIRED` | `>=` / `>` | `>=` | §2.2 |
| **D7a** | Minimum and rounding | `EXISTING BEHAVIOR — NOT YET APPROVED` | floor with minimum 1 (live) / other | Approve floor with minimum 1. Align `NetworkThresholdInterpreter.php:27` (no runtime caller). | §2.2 |
| **D8** | Policy integrity at opening (S1 / S2 / S3) | `OPEN — PRINCIPAL DECISION REQUIRED` (ADR-B) | see §4.2 | **S2** | §2.5 |
| **D8-L** | Elections already opened before the new mechanism is deployed | `OPEN — PRINCIPAL DECISION REQUIRED` | **(a)** an approved one-time backfill of the frozen record from the live values at deployment, marked as backfilled, run before the new code is active. **(b)** a versioned compatibility policy (legacy elections keep the old source and semantics). **(c)** another explicitly approved strategy. | none chosen; trade-offs in §4.4 | §2.3 |
| **D9** | Concurrency at the vote | `OPEN — PRINCIPAL DECISION REQUIRED` (ADR-C) | **(1)** accept and document; **(2)** second assessment under the lock (breaks PBDIGIT-240 D-240-1); **(3)** take the network lock before the single assessment | **(3)**, valid only once the plan's concurrency tests pass | §2.2; `VoteController.php:1480-1941` |
| **D10** | Rollout to existing elections | `OPEN — PRINCIPAL DECISION REQUIRED` | **W:** deployment window under the §5 condition. **V:** per-election semantic versioning (ADR). | **W** | §5 |
| **D11** | May System B be disabled (`network_binding_strategy = none`) before voting opens, and which strategies may the committee choose? Base N stays editable before opening per R2. | `OPEN — PRINCIPAL DECISION REQUIRED` | mandatory (`ip_count` only) / switchable before opening | mandatory | §2.2 |
| **AUTH** | Who configures System B | `RECOMMENDATION` | reuse `manageSettings` (active chief/deputy); commissioners excluded | as stated; confirm that it matches "appropriately authorised" | §2.4 |
| **AUD-1** | Audit of configuration and freeze | `RECOMMENDATION` | — | `settings_changed` includes System B fields; a `constitutional_policy_frozen` entry written **only after commit** (§4.5) | §2.4 |
| **AUD-2** | Voter message and refusal audit | `OPEN — PRINCIPAL DECISION REQUIRED` | **(a)** a distinct `NetworkLimitReached` verdict (narrow E2-D4 exception) without a number; **(b)** the generic message stays | **(a)**, plus an `ip_blocked` entry with `limit_source: constitutional`, the **effective** limit, trust level, step and policy hash; no IP or voter identity in metadata; forensic events stay separate | §2.2 |
| **AUTH-T** | Run and adjust the `nrna_test` tests | `OPEN — PRINCIPAL DECISION REQUIRED` | authorize / not | authorize | plan §6 |

### 3.3 Effects in numbers (System B, N = 6; votes a network can complete before refusal) [I, from the formulas]

| Voter | Today (`>`) | D7 = `>=` only | D3 = A only | D3 = A + D7 = `>=` |
|---|---|---|---|---|
| Unverified | 4 | 3 | 4 | 3 |
| Attested | — (unreachable) | 6 | 7 | 6 |

**With N = 1:** today 2, with `>=` 1.

**What changes for whom:**
- **D6 = reuse** only changes IPv6 networks: addresses within one /64 now count together.
- **D7a** has no runtime effect.

## 4. Freeze, capture and integrity design (recommendations)

### 4.1 Freeze protocol (D1 F-b; ADR-A)
Three separate protections:

**(1) State protection: no change after voting has opened.**
- System B fields are written **only** through one dedicated persistence method, called by the settings save. That method issues a **conditional update**:
  `UPDATE elections SET max_votes_per_ip = ?, network_binding_strategy = ?, settings_version = settings_version + 1 WHERE id = ? AND voting_locked = false`
- **1 row changed:** the write succeeded.
- **0 rows changed:** voting has opened. The whole settings request is refused (as with `SecurityPolicyLocked` today) and nothing is written.

**(2) Concurrency protection: no race with opening.** Both opening paths become conditional, transactional writes:
- **Explicit path:** inside the existing `transitionTo` transaction, `applySideEffectsForOpenVoting` adds `AND voting_locked = false` to its update.
- **Scheduled path:** `enforceVotingLock` gets a transaction and the same conditional update.
- **Capture:** only the transaction that changed **1 row** captures the policy (§4.3), reading the row it has just locked.

Why this serialises, under PostgreSQL READ COMMITTED (the default; production setting [U]):
- **Opening commits first:** a concurrent conditional settings update waits on the row lock, re-checks `voting_locked = false` after the commit, and changes 0 rows.
- **Settings commit first:** the opening update waits, then proceeds, and its capture sees the committed values.
- **Two openings (manual + scheduled):** exactly one changes 1 row and captures; the other changes 0 rows and captures nothing.

**(3) Write-path protection: no bypass.**
- **Model saves:** `Election::saving` refuses **any** dirty System B field on an existing election, regardless of state. Creation (insert) stays allowed.
- **Eloquent builder `update()`, `updateQuietly()`, raw `DB::table`, `DB::statement`/SQL:** these skip model events. A database-free **fitness test** (in the style of `ClientIpHasOneSourceTest`) fails if any code outside the dedicated method writes these columns.
  - **Its limit:** dynamic column names and manual database access are not caught.
- **A database trigger** that refuses changes when `voting_locked = true` is an **ADR-A option to evaluate**, not a requirement.

**What proves what:** a model guard or a single-process test proves state protection only. Concurrency protection is proven only by a two-connection test (plan P1).

### 4.2 Snapshot options (D8)

| Criterion | **S1** re-take the creation snapshot at opening | **S2** append-only frozen policy record at opening (recommended) | **S3** frozen live columns only |
|---|---|---|---|
| Domain consistency | breaks "immutable after creation" (`Election.php:2300-2313`) | keeps it. Creation snapshot = initial constitution; frozen record = the enforced policy. | the snapshot doesn't describe the enforced values |
| Historical audit | creation values lost | both kept | none |
| Atomic with opening | possible | yes: captured in the opening transaction by the 1-row winner (§4.1) | n/a |
| Existing elections | rewrites history | needs **D8-L** | n/a |
| Migration and rollback | no migration; can't be undone | additive migration. Code rollback is safe while the table stays; don't roll back the table. | none |
| Enforcement and hash | — | during voting, enforcement reads only the record and recomputes its hash (§4.4) | no integrity check |
| Testability | medium | high | high, but doesn't meet R2's fixed-policy intent |
| ADR | yes | yes (ADR-B) + migration approval | no |

**S2 is a recommendation, not a decision.** No migration exists before G-MIG.

### 4.3 Capture in both opening paths (if D8 = S2)
1. The capture runs inside the opening transaction, **only** after the conditional update changed 1 row. It copies N, the strategy and their hash from the locked row.
2. **At most one record per election** (unique on `election_id`); a second opening attempt changes 0 rows and never reaches the capture.
3. **It sits next to the existing side effects in `Election`.** No new service unless ADR-B shows one is needed.

### 4.4 Missing or invalid frozen record (if D8 = S2). There is no silent fallback to live columns.

| Case | Behaviour (recommendation) |
|---|---|
| **New election, capture fails while opening** | The opening transaction rolls back; **voting does not open**. Explicit path: error to the officer. Scheduled path: logged and retried on the next run. |
| **Opened after deployment, record missing** | Integrity failure: **fail closed** (votes refused through the existing fail-closed paths, §2.6), a forensic security event, and an alert to operations. |
| **Hash does not match the content** | Same as the previous row. |
| **Legacy (opened before deployment)** | **D8-L, open.** **(a) Backfill:** afterwards "missing" always means an integrity failure; needs a reviewed backfill and must run before the new code is active. **(b) Versioning:** keeps two policy sources and two code paths during the transition. **(c) Another strategy:** to be approved. |

### 4.5 Audit timing (AUD-1)
- **`ElectionAuditService` writes files, not database rows,** so a file entry written inside a transaction would survive a rollback.
- **`constitutional_policy_frozen` is written only after commit** (through a callback that runs on commit). A rolled-back opening therefore never claims a freeze.
- **The frozen record itself is the authoritative evidence.** If the process fails between commit and the file write, a reconciliation step re-emits the missing audit entries from the frozen records. This is a recommendation for ADR-B.

## 5. Rollout and compatibility (D10)
**Code changes affect existing elections even when their configuration is frozen [I]:**
- D3 (evidence wiring);
- D6 (grouping);
- D7 (boundary);
- AUD-2 (messages);
- D9 (lock order);
- D8 (policy source).

**Proposed deployment condition (option W), precise and checkable:**
> At deployment time, **no election has `voting_locked = true` and `voting_ends_at > now()`**, and the scheduled opening job is paused for the duration of the deployment.

| State | Covered by the condition? | Affected by a code change? |
|---|---|---|
| Draft → SetupNomination, ReadyForVoting | not voting | Not materially. No votes yet; all are at System B defaults [V/I]; the new rules apply from the first vote. The paused job prevents an opening during deployment. |
| VotingActive | excluded (voting_locked, window open) | **yes**, so it must be absent |
| Suspended during voting | excluded (`voting_locked` stays true and suspension doesn't change `voting_ends_at` [V]) | **yes**, so it must be absent |
| Window extended (`extendVotingEndTime`) | excluded while `voting_ends_at > now()` | **yes**, so it must be absent |
| Counting, ResultsPublished, Archived; voting closed | included (allowed) | no [I]: System B only gates casting |

**Assumptions to verify:**
- clock and timezone of `voting_ends_at`;
- that operations can pause the scheduled job;
- that no election is mid-transition during deployment.

**Pre-flight:** a read-only query by operations, not by this session.

**Option V (per-election versioning):** the frozen record carries a semantics version, and elections opened earlier keep the old rules. It needs an ADR and two code paths. It interacts with D8-L (b).

**Rollback criteria (W):** revert the code. Frozen records stay (the table is additive). No data migration to undo.

## 6. Decision checklist (Principal input required)

| # | Question | Options | Recommended | Blocks while unresolved |
|---|---|---|---|---|
| 1 | **D1 / ADR-A:** freeze at "voting opens" with the §4.1 conditional-update protocol; general lock unchanged | F-b / F-a | F-b | P1, P2 |
| 2 | **D8 / ADR-B:** frozen policy record, additive migration | S2 / S1 / S3 | S2 | P3, P4 |
| 3 | **D8-L:** legacy elections | backfill / versioning / other | none chosen | P4, release |
| 4 | **D9 / ADR-C:** network lock before the single assessment | (3) / (1) / (2) | (3) | P8 |
| 5 | **D3:** trust level for individual and bulk evidence | A / B / C | A | P5 |
| 6 | **D3a:** legacy verification records count | yes / no | yes | P5 |
| 7 | **D4, D4-N, D7a:** scaling table, N = 6, floor with minimum 1 | approve / amend | approve | P5, P6 |
| 8 | **D7:** `>=` (L allowed) or `>` (L + 1 allowed) | `>=` / `>` | `>=` | P6 |
| 9 | **D6:** reuse System A grouping | reuse / keep | reuse | P6 |
| 10 | **D1-V:** range for N | 1–50 / other | 1–50 | P2 |
| 11 | **D11:** System B mandatory, or switchable before opening | mandatory / switchable | mandatory | P2 |
| 12 | **AUTH:** chief/deputy only | confirm / amend | confirm | P2 |
| 13 | **AUD-2:** distinct verdict and wording | (a) / (b) | (a) | P7 |
| 14 | **D10:** rollout | W / V | W | release |
| 15 | **AUTH-T:** `nrna_test` test runs | authorize / not | authorize | those tests |
| 16 | **Confirm the recommendations D1-UI, D3-E, D3-P, AUD-1** (recommendations are not approvals) | confirm / amend each | confirm | P2 (D1-UI), P5 (D3-E, D3-P), P7 (AUD-1) |

**Traceability:** PBDIGIT-286 · R1–R5 (Principal, 2026-10-14) · consistency corrections C-1 to C-8 (Principal authorization 2026-10-14) · HEAD `87f76d4e9`.
