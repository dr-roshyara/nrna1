# PBDIGIT-286 constitutional IP limit: current state (snapshot 2026-10-14)

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Kind:** state snapshot, from read-only code inspection. **Nothing here is a decision.** PBDIGIT-286 stays `OPEN`; no implementation is authorised.

**Basis:**
- branch `knowelegeos-modelling`, HEAD `87f76d4e9`;
- 39 database-free pinning tests run on 2026-10-14, all passing (§4). No database-backed test was run.

**Labels:**
- **[V]** verified from source or test evidence;
- **[I]** inference;
- **[U]** not verified.

**Related:**
- backlog `docs/publicdigit/backlog/PBDIGIT-286-one-visible-ip-limit.md`;
- developer guides `developer_guide/election/ip_voting/00–05`;
- an untracked investigation by another session at `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261014_0925_pbdigit286_constitutional_ip_limit_investigation.md` (corrections in §5).

---

## 1. Work state

| Layer | State |
|---|---|
| **PBDIGIT-286 code** | **None, committed or uncommitted.** The only commit is the backlog item `bec1005b6` (2026-10-13). [V] |
| **Related, committed, not reopened** | PBDIGIT-278 item 8 (`7b3d15583`, `348c314fc`) · PBDIGIT-285 (`e3ea8b0b5`, `2a92285ae`) · PBDIGIT-288 items 1–5 (`6aeece4d4`, `197505f8d`, `8dd041fc8`, `7cb27176d`) · PBDIGIT-287 (`bb06acb4d`, `f16eb8ba7`) [V] |
| **Uncommitted, related** | **The backlog file:** the PO's instructions were pasted inside its status table, which breaks it. **The investigation document** is untracked and filed under *membership*. Both are left unchanged. [V] |
| **Binding decisions (Human PO)** | The two per-IP limits stay **separate**; the stricter one wins at code entry; no merging; no silent removal of trust-level scaling; no invented verification semantics; no implementation before approval. |
| **Proposed, not approved** | D1–D11, D7a, Q-P1 and Auth-1 (§6). The PO described D2, D4, D5, D6, D8 and D10 as previously accepted, but no record of that approval exists in the repository [V]. |

## 2. The two per-IP limits today

| | Per-IP vote limit (`IpVoteLimit`) | Constitutional limit (trust policy) |
|---|---|---|
| Base value | election setting `ip_restriction_max_per_ip` when the "IP restriction" switch is on, else `MAX_USE_IP_ADDRESS`, else 10 | `elections.max_votes_per_ip`, default 6 (migrations `2026_05_26_000000…:14`, `2026_05_27_120000…:22`; fallback `TrustPolicyEvaluator.php:143`) [V] |
| Switched on by | always, for real elections | `network_binding_strategy !== 'none'` (`TrustPolicyEvaluator.php:145`, default `ip_count`). Not the admin switch. [V] |
| Scaling | none | by the voter's trust level (`NetworkTrustEvidence.php:65-68`): Unverified max(1, ⌊N/2⌋), Attested N, ContinuityVerified ⌊3N/2⌋, RegistrarAttested 2N [V] |
| Refuses when | votes ≥ limit (`IpVoteLimit.php:154`) | votes > limit (`NetworkTrustEvidence.php:32`) [V] |
| Count | completed votes from the IP group (IPv6 per /64), unscoped (`withoutGlobalScopes`) | completed votes from the **exact** IP, **tenant-scoped** (`TrustLegitimacySecurityVerdict.php:68-71`; `Code` uses `BelongsToTenant`) [V] |
| Checked at | start, code entry (`CodeController.php:923`), verification, the vote (under the per-network lock, `VoteController.php:1812`) | code entry, **first** (`CodeController.php:727`); the vote, at the top of `store()` (`VoteController.php:1491`), inside the cast transaction but **before every lock** [V] |
| Whitelist | exempts | exempts (`TrustPolicyEvaluator.php:47`) [V] |
| IP binding | independent; never bypassed by the whitelist (`NetworkBindingPolicy.php:31`) | same [V] |
| Voter message | "The maximum of N vote(s) from your network has been reached." | "…based on constitutional verification. Please contact the election committee." (`CodeController.php:751`, `VoteController.php:1524`, `:2056`) [V] |
| Admin audit | `ip_blocked`, with `limit_source` and the applied limit | **none.** Only the forensic `election_security_events` row (§3.5) [V] |
| Admin visibility | settings page | **none.** No request, controller, route or page reads or writes the two columns; they are not in `Election::$fillable` (`Election.php:121`) [V] |

**Effective result today [V]:**
- every voter is Unverified, so the constitutional limit is ⌊6/2⌋ = 3;
- with `>`, a network can complete **4** votes, and the 5th voter is refused at code entry or at the vote;
- the per-IP limit (default 10) is never reached first.

## 3. Findings

### 3.1 Verification evidence is never read
- **What the evaluator hard-codes:** `TrustPolicyEvaluator::buildAttestationRecord()` sets `attested = false` (`:178`), `registrarId = null` (`:179`) and `revoked: false` (`:189`).
- **The broken requirement flag:** `required` reads `voter_verification_required` (`:177`). No migration creates that column, so it is always false. [V]
- **The trust level** comes from `VerificationAttestationRecord::trustLevel()` (`:52-60`). ContinuityVerified is never produced. [V]

**The available evidence is `voter_verifications` (PBDIGIT-213) [V]:**
- one row per (election, voter) (`unique` at `2026_04_12_000003…:27`);
- `status` `active` (default) or `revoked`; reasons `suspension_confirmed` and `revoked_by_officer`;
- verifier role Chief or Deputy Chief;
- method `officer_attestation` or `officer_bulk_attestation`;
- no expiry and no registrar concept;
- re-verification overwrites the row (`EloquentIdentityEvidenceStore.php:29`);
- the only current writer is `EloquentIdentityEvidenceStore`.

**The method rule (`VerificationMethod.php`, PBDIGIT-213 C4-D2):** both methods have the same admission effect, and the method "is never an input of the entitlement decision". That rule covers admission, not trust level. [V]

**History of the method field:**
- The `method` column dates from 2026-10-04 (`110387f63`); "Verify All" (bulk) from 2026-10-05 (`9c482db85`). **No row older than the column can be a bulk attestation.** [V]
- Older rows come from the April 2026 IP/device verification feature. Admission counts them [V]. Whether they amount to officer *identity* verification is a business question [I].

**No read port for "current active evidence" exists [V]:**
- `IdentityEvidenceStore` only writes;
- `EloquentVotingEntitlementQuery::isAdmitted` (`:84-103`) is private and depends on the admission policy;
- `EloquentVoterRegistrationQuery` and `EloquentAdmissionImpactQuery` each query the table inline.

**Two traps for any wiring [V]:**
- Setting `revoked = true` triggers the CRITICAL `attestation_revoked` denial in `VerificationAttestationPolicy`.
- Setting `registrarId` triggers the `RegistrarAttestationElevation` overlay's "evidence inconsistent" signal.

### 3.2 Two scaling tables
- **The live table** is `NetworkTrustEvidence.php:65-68`.
- **The second table,** `NetworkThresholdInterpreter.php:27`, has no minimum: N = 1 gives 0, pinned in `NetworkThresholdInterpreterTest.php:72`.
- **The interpreter has no runtime caller:** searching `app`, `routes`, `bootstrap` and `config` finds only its definition and two comments. Only its unit test exercises it. [V]

### 3.3 Snapshot and immutability
- **The snapshot is built at creation:** `static::created` (`Election.php:2379-2397`) builds `security_articles_snapshot` and `constitutional_hash` from the live columns.
- **The guard is narrow:** `:2300-2313` protects only `security_articles_snapshot`, `constitutional_hash` and `security_articles_version`.
- **Enforcement reads the live column** (`TrustPolicyEvaluator.php:143`), not the snapshot. Nothing writes the live column today, so the two can't diverge yet. [V]
- **Not locked during voting:** `SECURITY_POLICY_FIELDS` (`Election.php:63-69`), which is locked from ReadyForVoting on (`:2329`), includes neither `max_votes_per_ip` nor `network_binding_strategy`. [V]
- **The snapshot column is nullable** (`2026_05_26_000002…:13`). Whether elections without a snapshot exist is [U].
- **Per-election rule versioning is possible:** `security_articles_version` exists with default `D.2.5` (`…:16`). [V]
- **Allowed strategy values:** `network_binding_strategy` accepts `none`, `ip_count` and `ip_strict` (`ElectionConstitutionSchema.php:7`). The live policy treats everything except `none` the same. [V]

### 3.4 Election creation and settings
- **Creation:** `ElectionManagementController::store` (`:124-157`, route `organisations.elections.store`) validates no security field. **The creation form cannot carry the limit today.** [V]
- **Settings:** `UpdateElectionSettingsRequest.php:26` validates only `ip_restriction_max_per_ip` (1–50). The page says "Default: 4" (`Settings/Index.vue:257`) and that listed IPs "bypass the vote limit" (`:263`), without naming the second limit. [V]
- **`max_votes_per_ip` has no validation anywhere.** The "1–100" range in the backlog item could not be found. [V]

### 3.5 Refusal recording
- **The forensic record:** every evaluation writes an `election_security_events` row (`SecurityEventRecorder.php:56`) with `trust_denied`, reason `network_limit_exceeded`, votes from the IP and an IP hash.
  - **What it leaves out:** no user ID (`voter_slug_id = null`).
  - **The IP hash is unkeyed:** `sha256(ip . electionId)` (`TrustEvidencePrivacyPolicy.php:9`), which can be reversed by brute force (already PBDIGIT-288 item 6).
- **No admin visibility:** no controller or page reads the table, and there is no `ip_blocked` entry. [V]
- **The finding misreports the limit:** it records `limit_for_trust_level = N`, not the effective limit (`NetworkBindingPolicy.php:58`). [V]

### 3.6 Concurrency at the vote
The step order in `VoteController::store` [V]:
1. transaction (`:1480`);
2. **the single assessment, with no lock** (`:1491`);
3. constitutional gate (`:1515`);
4. lifecycle and membership checks (membership refusal rolls back, `EnsuresVoterMembership.php`);
5. **per-voter lock** (`:1641`, `Cache::lock(...)->get()`, non-blocking; cache store from config, default `file`; production store [U]);
6. `forCast` (`:1661`) and ballot validation;
7. **per-network lock** in `IpVoteLimit::castRefusal` (`:1812`; `pg_advisory_xact_lock`, blocking with `lock_timeout` 5000 ms, `IpVoteLimit.php:120-138`);
8. participation update and vote insert;
9. vote-code email (`:1925`), **sent synchronously**: `SendVoteSavingCode` imports but doesn't implement `ShouldQueue` (`SendVoteSavingCode.php:6,10`);
10. `DB::commit()` (`:1941`).

**Consequence [I]:** when the constitutional limit is the stricter one, voters on one network voting at the same moment can all pass step 2 and exceed it, up to the per-IP limit, which step 7 protects.

## 4. Test status

**Run on 2026-10-14, database-free, all passing [V]:**
- `NetworkTrustEvidenceTest` 11;
- `NetworkBindingPolicyTest` 7;
- `NetworkThresholdInterpreterTest` 9;
- `TrustPolicyEvaluatorNetworkRestrictionTest` 5;
- `VerificationAttestationPolicyTest` 7.

**Not run (they use `Tests\TestCase` on `nrna_test`, which needs separate authorization):**
- `SecurityWhitelistPolicyTest`;
- `CodeEntryAuthorizationTest`;
- `VotingSecurityVerdictAdapterTest`;
- `SecurityPolicySurfaceCharacterizationTest` (C2 pins "the settings limit does not change the authority");
- `ConstitutionalImmutabilityTest`;
- `TrustPolicyEvaluatorTest`;
- `SecurityEventRecorderTest`.

**Isolated suite:** `IpVoteLimitFlowTest::test_the_constitutional_trust_limit_stays_separate_and_can_be_stricter_at_code_entry` pins the current generic message (last run 2026-10-13, passing).

## 5. Corrections to the untracked investigation document
1. The vote's assessment runs **inside** the cast transaction, but before every lock. It is not "outside the transaction".
2. Constitutional refusals **are** persisted in `election_security_events`; they are just not visible to admins. Not "only application logs".
3. Tests **were** run: the 39 above.
4. Older rows **cannot** be bulk attestations (§3.1). The open question is their meaning, not their method.

## 6. Open decisions (none approved)

| ID | Question | Status |
|---|---|---|
| D1 | When the limit may be set (creation only / until ready-for-voting with re-snapshot, ADR), and whether enforcement reads the snapshot | approval required |
| — | Readiness lock: add both columns to `SECURITY_POLICY_FIELDS` | recommendation |
| D2 | A distinct `NetworkLimitReached` verdict with wording that has no number (narrow E2-D4 exception, like PBDIGIT-247) | accepted per PO statement; confirm |
| D3 | Trust mapping of active individual and bulk verification (A equal / B individual only / C none). RegistrarAttested is not representable. | approval required |
| D3a | Whether rows before October count as attested | approval required |
| D4 | Revoked or missing evidence counts as Unverified; `revoked` never set | accepted per PO statement; confirm |
| D5 | Align the interpreter to the minimum of 1 (no runtime effect) | accepted per PO statement; confirm |
| D6 | Keep the default 6 and show effective limits | accepted per PO statement; confirm |
| D7 | Threshold `>=` vs `>`: aligning makes every trust level exactly one vote stricter | approval required |
| D7a | Deployment changes running elections: deploy only when no election is ready for voting, or version the rules per election (ADR) | approval required |
| D8 | Constitutional count via `IpVoteLimit::votesFrom` (IPv6 /64), dropping the tenant scope for this count | accepted per PO statement; confirm the scope point |
| D9 | Concurrency at the vote: (1) accept and document; (2) second assessment under the lock (breaks PBDIGIT-240 D-240-1, ADR); (3) take the network lock before the single assessment (ADR; keeps one assessment) | approval required |
| D10 | A separate admin panel explaining both limits, the whitelist and IP binding | accepted per PO statement; confirm |
| D11 | Whether admins may switch the constitutional limit off | approval required |
| Q-P1 | Admission adopts a shared evidence read, or keeps its own predicate | approval required |
| Auth-1 | Run and adjust the `nrna_test` tests listed in §4 | approval required |

**Effect of D3 and D7 on how many votes a network can complete (N = 6) [I, from the formulas in §2]:**

| Voter | Today | D7 only | D3 only | D3 + D7 |
|---|---|---|---|---|
| Unverified | 4 | 3 | 4 | 3 |
| Attested (if D3 maps it there) | not reachable | 6 | 7 | 6 |

**With N = 1:** today 2, after D7 1.

## 7. Risks and unverified points
- **R1 [I]:** D3, D7 and D8 apply to every election from the moment they are deployed, including running ones.
- **R2 [I]:** under D9 option 1, the constitutional limit is not guaranteed under concurrency.
- **R3 [V cause, I impact]:** the synchronous email lengthens how long the network lock is held. This is existing behaviour and outside this ticket.
- **R4 [U]:** behaviour of the tenant-scoped constitutional count without a tenant context.
- **R5 [U]:** the production cache store for the per-voter lock.
- **R6 [U]:** whether elections without a snapshot exist.
- **R7 [U]:** PostgreSQL same-session re-acquire of the advisory lock inside one transaction (needed by D9 option 3) is untested here.

**Traceability:** PBDIGIT-286 · snapshot of 2026-10-14 · HEAD `87f76d4e9` · read-only; no code, tests, configuration or backlog files changed.
