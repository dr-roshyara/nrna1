# PBDIGIT-286 constitutional IP limit: principal decision record

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Date:** 2026-10-14 · **Basis:** HEAD `0d7316dfd`, read-only verification.

**Revisions:**
- r2: consistency corrections C-1 to C-8.
- **r3:** findings H1–H7, O-2 and O-3 incorporated, and **the Human Arbiter decisions of 2026-10-14 recorded as approved** (§3.2). Earlier text is replaced, not kept.

**Status:**
- **Approved:** R1–R5, R1-P provenance, and the Human Arbiter policy decisions in §3.2.
- **G-PLAN approved** (Principal, 2026-10-14, plan r3 at `914b773aa`; P0 only).
- **A-1 confirmed** (§8).
- **Recorded 2026-10-14 (§3.4):**
  - A-2 approved;
  - A-2b approved with conditions;
  - the empty-end-time release rule approved (any election);
  - **G-ADR-B approved conditionally**;
  - **G-ADR-C approved conditionally**;
  - the **ADR-A-T decision rule** approved (its operational condition is **not** satisfied).
- **Not approved:**
  - **ADR-A / G-ADR-A** (blocked until O-3);
  - the **AUD-2 entry** (G-AUD2 pending, §9);
  - **G-MIG** (migration and backfill), **G-ISO**, **G-AUTH-T**, **G-REL**;
  - **P1**.
- **Authorised later the same day (status annotation added after the run at the Principal's instruction, P5 review F6; the "Not approved" list above is the decision-time text, kept unchanged; it was briefly rewritten in place and restored from `HEAD` on 2026-10-15, second review N2):**
  - **G-ISO and the P5 Evidence phase only** (Principal, 2026-10-14 18:54 UTC). Conditions: target exclusively `pbdigit274_isolated_test` with the identity guard verified before each run; never `nrna_test`, the development database or MariaDB; only G-ISO/P5 tests; no P1–P4 or P6–P8. It does **not** approve O-3, G-ADR-A, G-MIG, G-AUD2, G-REL or any later phase.
  - **Evidence of the authorised run:** `docs/publicdigit/election/evidence/2026-10-14-pbdigit286-p5-evidence/01-isolated-run.txt` (19:01 UTC; guard confirmed `pbdigit274_isolated_test`; 7 tests, 8 assertions, 3 deprecations, exit 0).
  - **P5 is not accepted** and must not be released without P6 (plan §8, review F1).
- **Blocked:** the operations verification **O-3**.
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

### 3.4 Principal decisions on the ADR review (recorded 2026-10-14)
Recorded at the Principal's explicit instruction. **None of these is implementation authorization.**

| ID | Decision | Status | Where recorded |
|---|---|---|---|
| **A-2** | Refuse to open a not-yet-opened election while its System B strategy is `none`; applies to the explicit `open_voting` transition and the scheduled **auto-opening** (`ProcessElectionAutoTransitions.php:112`). The after-end lock follows G-A1. | **APPROVED** | ADR-A §3.5 |
| **G-A1** | A-2 governs **opening voting** (explicit `open_voting`, scheduled auto-opening). The scheduled **lock after an ended, never-opened window** (`ProcessElectionAutoTransitions.php:124-139`) is a different lifecycle action: **not** refused by A-2; it proceeds, **reports** a stored `none`, and its frozen record is captured with **`captured_by = scheduled_after_end`** (clarification approved 2026-10-14) | **APPROVED** (P1 readiness review; clarified 2026-10-14) | ADR-A §3.2, §3.5; ADR-B §3.1, §3.3 |
| **G-A2** | The conditional System B update **doesn't** touch `settings_version`; the controller's existing absolute assignment (`ElectionSettingsController.php:239`) stays the only writer (H7: exactly +1) | **APPROVED** | ADR-A §3.1, I-A5 |
| **G-A3** | P1 makes **both** opening paths conditional and transactional; P3 only **adds the capture** to them | **APPROVED** | plan §6 |
| **G-A4** | T-1 (H6) and T-2 (H7) move from P1 to **P2** (controller behaviour); P1 stays at model and database level | **APPROVED** | plan §6 |
| **G-A2 (re-confirmed)** | Kept as committed in `ac2930264`; not reopened (Principal, 2026-10-14) | **APPROVED** | ADR-A §3.1 |
| **F9** | P2 makes the settings save's optimistic check **atomic**: the general update is conditional on `settings_version = <the version the request carries>`. A **stale request** (0 rows) is refused with the existing message ("Settings were modified by another user. Please reload and try again."), and nothing of the request persists. **Exactly one** `settings_version` increment per **accepted** update. | **APPROVED** (to be implemented in P2; not implemented) | plan §6 P2, AC-3a |
| **F10** | P1 changes `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php:181,231` from `forceFill(['network_binding_strategy' => 'none'])->save()` to a raw `DB::table('elections')` update plus `->fresh()`, **preserving their meaning** (a stored `none` on a voting-active election disables the density rule; assessment equivalence). **P4 follow-up:** once opened real elections read the frozen record, these tests set the **record's** strategy through the F1 test helper | **APPROVED** | plan §6 P1, P4 |
| **A-2b** | Preserve historical `none`/`ip_strict` **only** for elections **demonstrably closed before deployment**, and **only** when all documented safety conditions (1–3) hold; not an approval of `none` | **APPROVED WITH CONDITIONS** | ADR-B §3.6a |
| **Empty-end-time release rule** | Block activation for **any** election with `voting_locked = true AND (voting_ends_at IS NULL OR voting_ends_at > now())`; individual sign-off. **No narrowing to real elections** without evidence E-D1 to E-D5 **and** a separate Principal decision | **APPROVED** | ADR-B §3.6b; §5 item 1 |
| **G-ADR-B** | Frozen-policy-record design, subject to A-2, A-2b and the empty-end-time rule; every fail-closed and release requirement intact | **APPROVED CONDITIONALLY** | ADR-B status |
| **G-ADR-C** | Lock before the single assessment. **All** concurrency proofs and failure-handling requirements mandatory; the narrow `lockNetworkForCast` entry point (no wider refactor); a failed refusal-record write logged **critically and alertably** while the voter still gets the busy response | **APPROVED CONDITIONALLY** | ADR-C status, §3.1a, §8 |
| **ADR-A-T** | Defer the database trigger **only if** operations confirms the documented controls over direct production database writes; otherwise required and included in the migration design | **DECISION RULE APPROVED**. Condition **not satisfied** | ADR-A §3.3 |
| **G-AUD2** | Exact text in §9 | **PENDING**: separate confirmation needed | §9 |
| **G-ADR-A** | ADR-A | **BLOCKED** until O-3 | ADR-A status |

**Known interaction with PBDIGIT-289** (recorded, not PBDIGIT-286 scope):
- **The gap:** the scheduled job can set `voting_locked` without a voting window (`ProcessElectionAutoTransitions.php:80-118`).
- **At release:** the empty-end-time pre-flight must pass.
- **G-REL must state** whether PBDIGIT-289 is fixed or accepted as a documented known risk, with the scheduled-transition behaviour reviewed (ADR-B §3.6b).

## 4. Design to be specified in the ADRs (`PENDING ADR`)

### 4.1 Freeze protocol: ADR-A
**State protection.** System B fields are written **only** by one dedicated method, and **only when N or the strategy differs from the stored value** (**H6**). Unchanged values never trigger it, so later saves of other settings, for example after voting closes, aren't blocked. The method issues:
`UPDATE elections SET max_votes_per_ip = ?, network_binding_strategy = 'ip_count' WHERE id = ? AND voting_locked = false` (no `settings_version` change, G-A2)
- **0 rows:** the whole settings request is refused and nothing is written. The request is handled in one transaction.
- **D11:** the method never writes `none`.
- **H7:** `settings_version` is bumped **exactly once** per request, by the controller's existing absolute assignment (`buildUpdateData`, `ElectionSettingsController.php:239`), the only writer. The conditional System B update doesn't touch it (G-A2, approved 2026-10-14).

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
- **Reporting inconsistencies:** for example `none`/`ip_strict` stored (see §8 A-2), a null `voting_ends_at` (**blocks activation**, ADR-B r2 §3.6b), or a missing snapshot. They are reported, not silently changed.
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
1. **No election** with `voting_locked = true AND (voting_ends_at IS NULL OR voting_ends_at > now())`. This covers voting, suspended-during-voting and extended windows; suspension doesn't touch the voting flags [V]. **Null-safe since 2026-10-14 (ADR-B r2 §3.6b):** the earlier condition missed null end times, and `hasVotingEnded()` treats null as "not ended". An opened election with a null `voting_ends_at` **blocks activation** until the Principal signs it off individually, with operations evidence per election.
2. **The scheduled opening job is paused.**
3. **Maintenance mode is on,** so manual `open_voting` can't run during the deployment. **Pausing the job alone is not sufficient.**
4. **The approved backfill (§4.8) has run** and been verified (dry run reviewed, then executed, then the invariant checked) **before** code that requires frozen records is activated.
5. **The production PostgreSQL isolation level is verified** (O-3, ADR-A assumption).

**Affected states:** elections not yet voting are unaffected materially (no votes yet; all at System B defaults [V/I]). Counting, ResultsPublished and Archived are unaffected (System B gates casting only).

**Rollback:** revert the code. Frozen records stay (the table is additive).

## 6. Remaining gates and checklist
| Gate | Status | Evidence required |
|---|---|---|
| **G-PLAN** | **APPROVED** (2026-10-14, plan r3 at `914b773aa`) | Principal approval; authorises P0 only |
| **ADR-A** | **BLOCKED**: `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Limit_Freeze_Protocol.md` (A-2 and the ADR-A-T rule decided inside it; the ADR itself unapproved) | O-3 result, then approval; ADR-A-T operations confirmation |
| **ADR-B** | **APPROVED CONDITIONALLY** 2026-10-14: `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Policy_Frozen_Record.md` | conditions in §3.4; implementation still needs G-MIG, G-ISO, G-REL |
| **ADR-C** | **APPROVED CONDITIONALLY** 2026-10-14: `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Limit_Cast_Concurrency.md` | mandatory proofs §6; implementation still needs G-ISO and P8 authorisation |
| **AUD-2 decision-record entry** | `PENDING`: **draft text** in §9 | approval, then the annotation is applied to the E2-D4 record |
| **G-MIG** | `PENDING OPERATIONAL AUTHORIZATION` | migration file + backfill design (§4.8) |
| **G-ISO** | **AUTHORISED for P5 only** (Principal, 2026-10-14 18:54 UTC; see Status). `PENDING OPERATIONAL AUTHORIZATION` for every other phase | authorization for `pbdigit274_isolated_test` with the identity guard |
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
- **A-1. `election_id` in the hash: RESOLVED** (Principal, 2026-10-14). `election_id` is a UUID string and is serialised as a **JSON string** in the canonical JSON. ADR-B §3.2 specifies the exact representation (lowercase hyphenated UUID) and the single builder used by capture, backfill, fixtures and verification.
- **A-2. Elections already storing `none` or `ip_strict`: RESOLVED 2026-10-14** (A-2 approved; A-2b approved with conditions; §3.4).
  - **ADR-A §3.5:** `ip_strict` is valid (same meaning as `ip_count`); a stored `none` **blocks opening** until System B is configured.
  - **ADR-B §3.3, §3.6a:** capture stores the value as stored. The backfill **preserves historical** `none`/`ip_strict` only for elections demonstrably closed before deployment, under all safety conditions. **This is not an approval of `none`** (A-2b approved with conditions).
- **Decisions from the ADR review (r2), recorded 2026-10-14 (§3.4):**
  - **ADR-A-T:** decision rule approved; the operations condition is **not** satisfied;
  - **ADR-C §8 item 3:** decided (critical, alertable log; the voter still gets the busy response);
  - **empty `voting_ends_at`:** blocks activation for any election (ADR-B §3.6b; §5 item 1).

## 9. Draft AUD-2 decision-record entry (pending G-AUD2; not applied)
**On approval,** this row is appended under the E2-D4 row (after the PBDIGIT-247 annotation) in `docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md` §2c, following the precedent of 2026-10-06:

> | ↳ *Annotation 2026-10-14 (PBDIGIT-286, Human Arbiter AUD-2)* | **Narrow supersession for one further reason:** a System B (constitutional network limit) refusal is reported as `SecurityVerdict::NetworkLimitReached` → `EntryAuthorizationReason` / `CastDecisionReason::NetworkLimitReached`. The voter is told that the maximum number of votes from their network has been reached for this election and to contact the election committee. **No numeric threshold, trust level or IP is shown.** It is a denial like `Denied`; it is never recorded next to ballot data (I8 unaffected). Every other security detail stays in the chain's own logging. Decision record: `docs/publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md` §3.2 AUD-2; plan `docs/plans/20261014-1038-pbdigit286-constitutional-ip-limit-plan.md` P7. |

**Proposed voter wording** (code entry and the vote): *"The maximum number of votes from your network has been reached for this election. Please contact the election committee."*

**Traceability:** PBDIGIT-286 · R1–R5 + R1-P · Human Arbiter decisions 2026-10-14 · consistency corrections C-1 to C-8 · findings H1–H7, O-2, O-3 · HEAD `0d7316dfd`.
