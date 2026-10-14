# PBDIGIT-286 constitutional IP limit: principal decision record

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Date:** 2026-10-14 · **Basis:** HEAD `0d7316dfd`, read-only verification.

**Revisions:**
- r2: consistency corrections C-1 to C-8.
- **r3:** findings H1–H7, O-2 and O-3 incorporated, and **the Human Arbiter decisions of 2026-10-14 recorded as approved** (§3.2). Earlier text is replaced, not kept.

**Status:**
- **Approved:** R1–R5, R1-P provenance, and the Human Arbiter policy decisions in §3.2.
- **Not approved:**
  - **G-PLAN**, **ADR-A**, **ADR-B**, **ADR-C**;
  - **G-MIG** (migration and backfill), **G-ISO**, **G-AUTH-T**, **G-REL**;
  - the operations verification **O-3**.
- **These decisions are not implementation authorization.** PBDIGIT-286 stays `OPEN`.

**Companions:**
- current state: [`2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md`](2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md) (earlier numbering; §3.1);
- plan (DRAFT r3): [`docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md`](../../plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md);
- developer guide (current behaviour): `developer_guide/election/ip_voting/06_constitutional_limit.md`.

**Labels:**
- `CONFIRMED BY PRINCIPAL`;
- `APPROVED BY HUMAN ARBITER (2026-10-14)`;
- `PENDING ADR`;
- `PENDING OPERATIONAL AUTHORIZATION`;
- `RECOMMENDATION`.

**Evidence:** **[V]** verified in source · **[I]** inference · **[U]** not verified.

**Terms:**
- **System A** = the general per-IP vote limit (`IpVoteLimit`).
- **System B** = the constitutional limit (trust policy).
- **"Voting opens"** = the moment `elections.voting_locked` becomes `true`.
- **"Opened real election"** = `type = real` and `voting_locked = true`.

---

## 1. Confirmed requirements (binding)

| ID | Requirement | Status |
|---|---|---|
| **R1** | System A: the general limit is 10. It stays independent of System B. Existing approved behaviour, including the election-specific override, is preserved; any conflict is reported, not changed. | `CONFIRMED BY PRINCIPAL` |
| **R2** | System B is configured per election by an appropriately authorised Election Committee member. It may be set or changed **before voting opens**, and stays unchanged for the whole voting period once voting opens. Configuration and authorization are enforced **server-side**; a UI restriction alone is insufficient. | `CONFIRMED BY PRINCIPAL` |
| **R3** | System A and System B stay separate. Neither silently changes the other. **Each applicable control may refuse a vote on its own; a vote is accepted only when every applicable control permits it** (§2.7). The base value is not the effective limit for every voter. | `CONFIRMED BY PRINCIPAL` |
| **R4** | A whitelisted IP bypasses both vote-count limits. Whitelisting never bypasses IP binding. Regression tests are required. | `CONFIRMED BY PRINCIPAL` |
| **R5** | No implementation before the plan and the required architecture decisions are approved. | `CONFIRMED BY PRINCIPAL` |

**R1-P, provenance of R1's override** (`APPROVED BY HUMAN ARBITER`): the source is the Human Arbiter's specification "PBDIGIT-278 — Item 8" of 2026-10-13. Its repository record is the evidence README `docs/publicdigit/election/evidence/2026-10-13-pbdigit278-item8-ip-limit/README.md:3` (commit `210496eb8`): "Authority: Human PO specification … an election override wins". There is no separate decision-record document. [V]

**Gates:** plan approval, ADR approval, migration approval and test-environment authorization are **separate gates** (plan §4).

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
- **The override is security policy:** it is locked from `ElectionLifecycle::isLocked()` (false only in Draft, SetupAdministration and SetupNomination; `ElectionLifecycleEngineImpl.php:295-304`), via `SECURITY_POLICY_FIELDS` (`Election.php:63-69`), `ElectionSettingsController::assertSecurityPolicyMutable` (`:148-151`) and the model guard (`Election.php:2329`).

### 2.2 System B [V]
- **Base:** `elections.max_votes_per_ip`, default 6 (migrations `2026_05_26_000000…:14`, `2026_05_27_120000…:22`; fallback `TrustPolicyEvaluator.php:143`).
- **Switched on** when `network_binding_strategy !== 'none'` (`:145`). The values are `none`, `ip_count` and `ip_strict` (`ElectionConstitutionSchema.php:7`); the live policy treats everything except `none` the same.
- **Effective limit by trust level** (`NetworkTrustEvidence.php:65-68`): Unverified `max(1, ⌊N/2⌋)` · Attested `N` · ContinuityVerified `⌊3N/2⌋` · RegistrarAttested `2N`.
- **Every voter is Unverified today** (`TrustPolicyEvaluator.php:178-179`), and ContinuityVerified is never produced (`VerificationAttestationRecord.php:52-60`).
- **Refuses** when votes **>** the effective limit (`NetworkTrustEvidence.php:32`).
- **Counting:** completed votes in this election from the **exact** IP, under the tenant global scope (`TrustLegitimacySecurityVerdict.php:68-71`).
- **Checked at:**
  - code entry, first (`CodeController.php:727`);
  - the vote, at the top of `store()` (`VoteController.php:1491`), inside the cast transaction and **before every lock**.
- **Not configurable:** no request, controller, route or page writes the two columns, and they are not in `$fillable` (`Election.php:121`).
- **Refusals** are recorded only in `election_security_events` (`SecurityEventRecorder.php:56`). Messages are generic (`CodeController.php:751`, `VoteController.php:1524`, `:2056`). The finding reports N instead of the effective limit (`NetworkBindingPolicy.php:58`).

### 2.3 Lifecycle, opening voting and assessment order [V]
- **Two paths set `voting_locked = true`:**
  - **Explicit:** `ElectionManagementController::openVoting` (`:918`, `can:manageSettings`) → `Election::transitionTo` (`:1664`): cache lock `election_transition:{id}` + `DB::transaction`. The side effect `applySideEffectsForOpenVoting` (`:1897`) is a raw update with no condition on the flag and no `settings_version` bump.
  - **Scheduled:** `ProcessElectionAutoTransitions` → `Election::enforceVotingLock` (`:1609`): no cache lock, no transaction.
- **The flag:** `voting_locked` is **NOT NULL, default false** (`2026_04_22_000003…:12`). It is never reset; closing voting keeps it `true` (`:1935-1945`); suspend and resume touch neither it nor `voting_ends_at`.
- **Voting itself:** `canVote` is true **only in VotingActive** (`ElectionLifecycleEngineImpl.php:257-259`), and VotingActive requires `voting_locked` (`:110-112`). **No vote is possible before voting opens.** An election can go straight from SetupNomination to VotingActive.
- **The security assessment runs before the votability check:** `CodeController` calls `verdictFor` (`:718`) before `forEntry` (`:727`), and `VoteController::store` assesses **every** election type, demo included (`:1491`).
- **Existing freeze rules:** security fields lock at `isLocked()`; all settings freeze while VotingActive (`ElectionSettingsController::settingsFrozen`, `:47-50`).
- **Settings save:**
  - an optimistic `settings_version` check;
  - no transaction and no row lock;
  - it bumps `settings_version` in `buildUpdateData` (`:239`).
- **Election IDs are UUID strings** (`Election.php:40-43`: `HasUuids`, `$keyType = 'string'`).
- **Test fixtures:** `ElectionFactory::inVotingState()` sets `voting_locked = true` directly (`database/factories/ElectionFactory.php:180-185`). About 30 test files rely on it or set the flag.
- **Transaction isolation:** `config/database.php` sets none, so the server default applies (PostgreSQL: READ COMMITTED). The production setting is [U] and must be verified (O-3).

### 2.4 Authority and audit [V]
- **Creation:** organisation owners or admins (`ElectionPolicy::create`, `:121-127`).
- **Settings, settings update and opening voting:** `manageSettings`, an active chief or deputy officer of the election (`ElectionPolicy.php:46-53`; `UpdateElectionSettingsRequest::authorize()` `:14-17`). Officer roles are chief, deputy and commissioner.
- **Audit:** `settings_changed` through `ElectionAuditService`, which writes JSONL **files** outside database transactions (`ElectionSettingsController.php:260-269`).
- **UI:** one flag disables all security fields (`Settings/Index.vue:76`).

### 2.5 Snapshot [V]
- **Created at creation:** `security_articles_snapshot` and `constitutional_hash` (`Election.php:2379-2397`).
- **Immutable after creation** (`:2300-2313`).
- **Never read or validated.**
- **Nullable column.**

### 2.6 Verification evidence [V]
- **Storage:** `voter_verifications`, one row per (election, voter); status `active` or `revoked`; method `officer_attestation` or `officer_bulk_attestation`.
- **No expiry and no registrar concept.**
- **Admission** counts any active row (`EloquentVotingEntitlementQuery.php:84-103`), and both methods have the same admission effect (PBDIGIT-213 C4-D2).
- **Method history:** no row older than 2026-10-04 can be bulk.
- **Fail-closed today:** evaluation errors are refused at code entry (`CodeController.php:728-729`) and at the vote (`failCastClosed`).

### 2.7 What "the stricter limit wins" means
**A vote is accepted only when every applicable control permits it.** Each control evaluates its own threshold, counting rule, network grouping and scope. If any control refuses, the vote is refused; no numeric comparison between the two limits takes place. When several would refuse, the first evaluated control (System B, at code entry and at the vote) gives the voter message and the single refusal audit entry.

## 3. Decisions

### 3.1 Identifier mapping
This record uses the Principal's numbering of 2026-10-14. The earlier sheet (current-state review §6) maps as follows:

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
| — | D8-L (r2) |
| — | **D8-L-S, D10-B, R1-P, BL-EMAIL (r3)** |

### 3.2 Human Arbiter decisions: `APPROVED BY HUMAN ARBITER (2026-10-14)`
An approved policy decision **does not** approve the ADR, migration, test or release gate it depends on. Those are listed in the last column.

| ID | Decision (approved option) | Consequences | Evidence needed for technical approval / remaining gate |
|---|---|---|---|
| **D1** (freeze point) | **The freeze takes effect when voting opens** (`voting_locked = true`; F-b). The general security lock and System A's lock stay unchanged. | System B stays editable in ReadyForVoting (R2). | **ADR-A** (protocol, §4.1) · O-3 |
| **D1-V** | **N ∈ [1, 50]** (integer). The effective limit follows the approved scaling (D4): for N = 1…50, Unverified 1…25 and Attested 1…50. | Values outside the range are refused server-side. | P2 tests |
| **D1-UI** | **A separate System B panel with its own freeze indicator** (from `voting_locked`, not from `securityPolicyLocked`). It explains: the base N and the effective-limit rules; that System A and System B are independent; that every applicable control must permit a vote; that the whitelist bypasses count limits but never IP binding; and the state before and after voting opens. **The UI is not a security boundary.** | Server-side protection (§4.1) stays mandatory. | P2, design-check |
| **D11** | **Mandatory.** An authorised committee member **cannot remove System B**: the System B configuration path offers only the base N, the strategy stays `ip_count`, and the dedicated write refuses `none`. Existing strategy behaviour elsewhere (the enforcement meaning of a stored `none`/`ip_strict`, schema values) is **not changed** without an approved ADR. | See §8 A-2 for elections already storing `none`. | ADR-A (write rule) · P2 |
| **D3** | **Option A:** an active individual **and** an active bulk officer attestation both give **Attested**. | With N = 6 and D7: verified voters' networks allow 6, unverified 3. | P5 |
| **D3a** | **Yes:** verification records older than 2026-10-04 keep their attestation meaning, under the same validity rules (active only). | Consistent with admission. | P5 |
| **D3-E** | Missing → **Unverified**; revoked → **Unverified**; evidence-store read failure → **fail closed** (never treated as attested). `revoked` and `registrarId` in the attestation record are never set. | No new denial for revoked voters. | P5 |
| **D3-P** | **A trust-only read port.** The admission path is unchanged; the duplicated "active row exists" predicate is **known technical debt**. | — | P5; admission tests unchanged |
| **D4, D4-N, D7a** | **Policy table approved:** default **N = 6**; Unverified `max(1, ⌊N/2⌋)`; Attested `N`; ContinuityVerified `⌊3N/2⌋`; RegistrarAttested `2N`; floor rounding with minimum 1. **This approves the table, not reachability:** only Unverified and Attested are reachable from the evidence (§2.2, §2.6). `NetworkThresholdInterpreter` is aligned to the minimum of 1. | — | P5, P6 |
| **D6** | **Reuse System A's grouping** (`IpVoteLimit::votesFrom`: IPv4 address, IPv6 /64), counting **only this election's** votes. No cross-election counting. Tenant-context handling for this count is to be specified in **ADR-C** (§4.9). | IPv6 households count together. | ADR-C · P6 |
| **D7** | **`>=`:** with effective limit L, the next vote is refused once **L completed votes** exist. No vote beyond L. Applies to the evaluator, acceptance criteria, refusal, audit evidence and tests. | One vote stricter than today at every level. | P6 |
| **D8** | **S2:** an append-only constitutional-policy record, captured atomically when voting opens. **The authoritative policy source for opened real elections.** No silent fallback to live settings when it is missing or invalid. | Additive table. | **ADR-B** · **G-MIG** |
| **D8-L** | **Option (a):** a one-time, explicitly reviewed **backfill** before the new code becomes active. | — | G-MIG (backfill design) · G-REL |
| **D8-L-S** | **Option (i):** backfill **every election with `voting_locked = true`**. **Invariant: `voting_locked = true` ⇒ the frozen record exists** (for real elections). | "Missing" is then always an integrity failure. | §4.8 · G-MIG |
| **H1** (policy source) | **Real election before opening:** live configuration, no record needed. **Demo election:** the live configuration path, no production record. **Opened real election:** the frozen record, fail closed if it is missing or invalid. | The evaluator must not fail for the first two cases (assessment order, §2.3). | ADR-B · P4 |
| **H2** (fixtures) | **F1:** `ElectionFactory::inVotingState()` may create a **clearly marked test-only** frozen record in the same canonical format. **The fixture is not evidence that the opening protocol works**; the real opening paths are tested on their own (AC-5, AC-6). | One deliberate test change instead of ~30. | ADR-B §fixtures · P3 |
| **H4** (hash) | **Design direction:** `SHA-256(canonical JSON)` with fixed field order and no whitespace: `{"v":1,"election_id":"…","max_votes_per_ip":…,"network_binding_strategy":"…"}`. `v` and `max_votes_per_ip` are JSON integers; `network_binding_strategy` is a JSON string. **`election_id`** is serialised per §8 A-1. | ADR-B specifies the exact serialisation and proves that capture and verification use the same representation. | ADR-B · P3 |
| **D9** | **Option (3):** take the network lock **before** the single authoritative System B assessment. No assessment-before-lock race. | — | **ADR-C** (lock scope, ordering, retry, concurrency proof) · P8 |
| **D10** | **W:** a controlled deployment window (no per-election versioning). | — | G-REL |
| **D10-B** | **Option (i):** application **maintenance mode** during the window, with the operational controls in §5. Pausing the scheduled job alone is **not** sufficient. | Voters are blocked too; none can vote under the §5 condition. | G-REL |
| **AUD-1** | **An after-commit audit entry plus reconciliation.** The frozen database record is the authoritative evidence of a committed freeze. The file entry never claims a freeze before commit. **Reconciliation is owned by plan phase P3** (task T-6). | — | ADR-B · P3 |
| **AUD-2** | **Option (a):** a specific `NetworkLimitReached` refusal **without the numeric threshold**. This is a narrow exception to the generic constitutional-refusal convention (E2-D4), like `IpBindingDenied` (PBDIGIT-247). | No security-sensitive detail shown. | decision-record entry · P7 |
| **AUTH** | **Active chief/deputy officers** via the existing `manageSettings`. Commissioners stay excluded unless a separate governance decision changes this. | — | P2 |
| **BL-EMAIL** | **Approved in principle:** a separate backlog item for the synchronous vote-code email (`SendVoteSavingCode` doesn't implement `ShouldQueue`; sent inside the cast transaction while locks are held). **Outside PBDIGIT-286.** Creating the backlog file is a separate, explicitly scoped task (not done here). | — | separate backlog task |

### 3.3 Approved effect in numbers (System B; votes a network can complete before refusal; D3 = A, D7 = `>=`)

| Voter | Today | After implementation, N = 6 | N = 1 | N = 50 |
|---|---|---|---|---|
| Unverified (missing/revoked evidence) | 4 | **3** | 1 | 25 |
| Attested (active individual, bulk or legacy evidence) | not reachable | **6** | 1 | 50 |

Only IPv6 networks change with D6: addresses in one /64 count together.

## 4. Design to be specified in the ADRs (`PENDING ADR`)

### 4.1 Freeze protocol: ADR-A
**State protection.** System B fields are written **only** by one dedicated method, and **only when N or the strategy differs from the stored value** (**H6**). Unchanged values never trigger it, so later saves of other settings, for example after voting closes, aren't blocked. The method issues:
`UPDATE elections SET max_votes_per_ip = ?, network_binding_strategy = 'ip_count', settings_version = settings_version + 1 WHERE id = ? AND voting_locked = false`
- **0 rows:** the whole settings request is refused and nothing is written. The request is handled in one transaction.
- **D11:** the method never writes `none`.
- **H7:** `settings_version` is bumped **exactly once** per request. When System B changed, the conditional update increments it and the general update (`buildUpdateData`, `ElectionSettingsController.php:239`) doesn't increment again.

**Concurrency protection.** Both opening paths become conditional, transactional updates (`… SET voting_locked = true … WHERE id = ? AND voting_locked = false`). Only the 1-row winner captures (§4.3).
- Under READ COMMITTED, a conditional update that waits on the row lock re-checks `voting_locked` after the other transaction commits.
- So: opening first → the settings write changes 0 rows; settings first → the capture sees the committed values; two openings → exactly one captures.
- **Assumption: O-3.**

**Write-path protection.**
- `Election::saving` refuses any dirty System B field on an existing election (inserts are allowed).
- A database-free fitness test forbids builder `update()`, `updateQuietly()`, `DB::table`/SQL writes of these columns outside the dedicated method. It can't see dynamic or manual access.
- A database trigger is an **option to evaluate** in ADR-A.

**Proof.** A model guard or a single-process test proves state protection only. Concurrency protection needs the two-connection tests (plan P1).

### 4.2 Snapshot strategy (D8 = S2, approved)
- **Kept as is:** the creation snapshot stays immutable (initial constitution).
- **Added:** the frozen record is the enforced policy.
- **Rejected alternatives (kept for the history):** S1 would rewrite the creation snapshot; S3 would leave R2's fixed-policy intent without integrity evidence.

### 4.3 Capture: ADR-B
- **When:** inside the opening transaction, only after the conditional update changed 1 row, from the locked row's values.
- **One record per election:** unique on `election_id`.
- **Where:** next to the existing opening side effects in `Election`; no new service unless ADR-B shows one is needed.
- **Failure:** the transaction rolls back and **voting doesn't open** (explicit path: error to the officer; scheduled path: logged and retried).

### 4.4 Policy source and failure cases: ADR-B (H1, approved direction)
| Case | Source / behaviour |
|---|---|
| Real election, voting not yet opened | live columns (the assessment runs, but no vote is possible) |
| Demo election | the live configuration path; no production record |
| Opened real election | the frozen record only; the hash is recomputed on read |
| Opened real election, record missing | integrity failure: **fail closed**, a forensic event, an alert to operations |
| Opened real election, hash mismatch | same |
| Opened before deployment | backfilled before activation (§4.8), so it is covered by the rows above |

### 4.5 Audit timing: AUD-1 (approved), ADR-B
- **After commit only:** `constitutional_policy_frozen` is written through a callback that runs on commit, so a rolled-back opening never claims a freeze.
- **Reconciliation (task T-6, phase P3):** re-emits missing audit entries from the frozen records.
- **`settings_changed`** includes the System B fields.
- **Refusals** produce an `ip_blocked` entry with `limit_source: constitutional`, the **effective** limit, trust level, step and policy hash. No raw IP or voter identity in the metadata. `election_security_events` stays the separate forensic record.

### 4.6 Test fixtures: ADR-B section (H2 = F1, approved)
- **Test-only capture:** `ElectionFactory::inVotingState()` writes a test-only frozen record (same canonical format, marked as a fixture). It is introduced as its own RED → GREEN step **before** fail-closed enforcement (plan P3, task T-4).
- **Not proof of the protocol:** the real opening protocol has its own tests (AC-5, AC-6).
- **Rejected alternatives:** F2, open through `transitionTo`, is too heavy; F3, per-test helpers, is error-prone.

### 4.7 Hash: ADR-B (H4)
The canonical JSON is described in §3.2 H4. ADR-B fixes:
- the exact serialisation: key order, integer and string encoding, character escaping, UTF-8;
- the `election_id` representation (§8 A-1);
- one shared builder used by capture, backfill, fixture and verification;
- a database-free test of the format.

### 4.8 Backfill: D8-L (a), D8-L-S (i); design before G-MIG
- **Data scope:** every election with `voting_locked = true` (real; demo per ADR-B).
- **Record generation:** values from the live columns at backfill time, marked `captured_by = backfill`, with the same hash builder.
- **Idempotent:** an existing record is skipped and reported, never rewritten.
- **Dry run:** lists what would be written without writing.
- **Reporting inconsistencies:** for example `none`/`ip_strict` stored (see §8 A-2), a null `voting_ends_at`, or a missing snapshot. They are reported, not silently changed.
- **Fail safe:** each election in its own transaction; a failure stops activation (G-REL).
- **Recovery:** the backfilled rows are identifiable (`captured_by = backfill`) and can be removed before activation. After activation they are authoritative and are not removed.
- **Not executed in this task.**

### 4.9 Concurrency: ADR-C (D9 = (3), approved)
- **Sequence:** the cast takes the per-network advisory lock (election + IP group, as `IpVoteLimit::lockKey`) **before** the single assessment. One assessment per attempt (PBDIGIT-240 D-240-1) is kept.
- **ADR-C defines:**
  - **lock scope:** the same IP group as the D6 count;
  - **ordering:** network lock, then the non-blocking per-voter lock;
  - **retry and timeout:** a busy refusal;
  - **same-session re-acquire:** proven by test;
  - **tenant-context handling for the D6 count:** election-scoped, no cross-election counting;
  - **the concurrency proof:** plan P8.

## 5. Rollout: D10 = W, D10-B = (i) (approved), G-REL pending
**Release pre-flight**, all required, evidence recorded:
1. **No election** with `voting_locked = true AND voting_ends_at > now()`. This covers voting, suspended-during-voting and extended windows; suspension doesn't touch the voting flags [V].
2. **The scheduled opening job is paused.**
3. **Maintenance mode is on,** so manual `open_voting` can't run during the deployment. **Pausing the job alone is not sufficient.**
4. **The approved backfill (§4.8) has run** and been verified (dry run reviewed, then executed, then the invariant checked) **before** code that requires frozen records is activated.
5. **The production PostgreSQL isolation level is verified** (O-3, ADR-A assumption).

**Affected states:** elections not yet voting are unaffected materially (no votes yet; all at System B defaults [V/I]). Counting, ResultsPublished and Archived are unaffected (System B gates casting only).

**Rollback:** revert the code. Frozen records stay (the table is additive).

## 6. Remaining gates and checklist
| Gate | Status | Evidence required |
|---|---|---|
| **G-PLAN** | `PENDING` | your approval of plan DRAFT r3 |
| **ADR-A** | `PENDING ADR` | ADR file: §4.1 + H6, H7, D11 write rule, trigger evaluation; O-3 result |
| **ADR-B** | `PENDING ADR` | ADR file: §4.3–4.8, H1, H2 (F1), H4 serialisation incl. A-1, AUD-1 reconciliation |
| **ADR-C** | `PENDING ADR` | ADR file: §4.9 incl. D6 tenant-context handling |
| **AUD-2 decision-record entry** | `PENDING` | E2-D4 exception entry |
| **G-MIG** | `PENDING OPERATIONAL AUTHORIZATION` | migration file + backfill design (§4.8) |
| **G-ISO** | `PENDING OPERATIONAL AUTHORIZATION` | authorization for `pbdigit274_isolated_test` with the identity guard |
| **G-AUTH-T** | `PENDING OPERATIONAL AUTHORIZATION` | separate authorization for `nrna_test` |
| **O-3** | `PENDING OPERATIONAL AUTHORIZATION` | operations confirm the production isolation level before ADR-A |
| **G-REL** | `PENDING OPERATIONAL AUTHORIZATION` | §5 pre-flight evidence |
| **BL-EMAIL** | approved in principle | separate backlog task |

**Never permitted:** `nrna` and any shared database.

## 7. Classification
1. **Human Arbiter decisions, approved:** §3.2.
2. **Architecture decisions pending ADR:** ADR-A, ADR-B, ADR-C, and the AUD-2 decision-record entry.
3. **Technical corrections** (allowed only after their gates): T-1 to T-7 (plan §6).
4. **Operational authorizations pending:** G-PLAN, G-MIG, G-ISO, G-AUTH-T, O-3, G-REL.

## 8. Open ambiguities (for the ADRs; not decided here)
- **A-1. `election_id` in the hash:** the approved wording calls it an integer, but election IDs are **UUID strings** [V] (`Election.php:40-43`). This record serialises it as a JSON string. **ADR-B must confirm the representation**, and the Human Arbiter should confirm the correction.
- **A-2. Elections already storing `none` or `ip_strict`:** D11 forbids configuring `none`, and existing enforcement behaviour stays unchanged without an ADR. The backfill (§4.8) reports any such election and doesn't change it. Whether an opened election with a stored `none` keeps System B off is for **ADR-A/ADR-B** to decide. None is expected, since no write path exists [V/I]; direct database edits are not excluded.

**Traceability:** PBDIGIT-286 · R1–R5 + R1-P · Human Arbiter decisions 2026-10-14 · consistency corrections C-1 to C-8 · findings H1–H7, O-2, O-3 · HEAD `0d7316dfd`.
