# PBDIGIT-286 constitutional IP limit: implementation plan

**Status: DRAFT r2, awaiting EP-01 approval.** This plan authorises nothing. Approving it (G-PLAN) **does not** approve any ADR, migration, open decision, database access, test run or deployment. Each of those has its own gate (§4).

**Revision:** r2 applies consistency corrections C-1 to C-8 (Principal authorization 2026-10-14). Created 2026-10-14 at the Principal's request, before approval; marked DRAFT so that creating it is not mistaken for approval.

**Decision record:** [`docs/publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md`](../publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md) r2 (R1–R5, verified behaviour, decision IDs, §4 design). **Current state:** [`…/2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md`](../publicdigit/reviews/2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md) (uses the earlier numbering; mapping in record §3.1). **Supersedes:** none.

**Terms:**
- **System A** = the general per-IP vote limit (`IpVoteLimit`).
- **System B** = the constitutional limit (trust policy).
- **"Voting opens"** = `elections.voting_locked` becomes `true`, by the `open_voting` action or the scheduled job.

**Notation:**
- **"[cond. Dx]"** marks a statement that holds only if decision Dx is approved as recommended. The alternative expectation is given beside it.
- Everything else traces to R1–R5.

---

## 1. Objective
Make System B a visible, per-election, committee-configured rule that is frozen when voting opens and enforced consistently, while it stays separate from System A (R1–R5).

## 2. Background
- **Hidden and effectively 3:** System B halves the base 6 for every voter.
- **One extra vote:** it uses `>`.
- **Exact-IP counting.**
- **Unprotected at the vote:** it runs before any lock.
- **No configuration path and no freeze.**
- **No admin-facing audit** of its refusals.

Evidence and line references are in record §2.

## 3. Scope
**In scope:**
- System B configuration with server-side authorization;
- the freeze when voting opens (record §4.1);
- the policy capture and integrity [cond. D8];
- legacy handling [cond. D8-L];
- evidence wiring [cond. D3/D3a];
- boundary and grouping [cond. D7/D7a/D6];
- verdict, message and audit [cond. AUD-2];
- concurrency [cond. D9];
- regression protection;
- documentation.

**Non-goals:**
- **No changes to System A,** to its override or to its lock (`SECURITY_POLICY_FIELDS`, `isLocked()`).
- **No change to voter admission** (PBDIGIT-213).
- **No new trust levels.** No registrar or continuity evidence.
- **Not in this ticket:**
  - the unkeyed IP hash in `election_security_events` (PBDIGIT-288 item 6);
  - the synchronous vote-code email (separate backlog item);
  - reopening PBDIGIT-278, 285, 287 or 288;
  - retiring the unused simplified policy path.

## 4. Gates
Each gate is separate. **Satisfying one doesn't satisfy another.**

| Gate | What it approves | Required evidence | Blocked until it's satisfied |
|---|---|---|---|
| **G-PLAN** | this plan (EP-01) | the Principal's written approval of this revision | everything |
| **G-ADR-A** | the freeze protocol (record §4.1), including the trigger evaluation | approved ADR file | P1, P2 |
| **G-ADR-B** | the frozen policy record (record §4.2–4.5), if D8 = S2 | approved ADR file | P3, P4 |
| **G-ADR-C** | lock before the single assessment, if D9 = (3) | approved ADR file | P8 |
| **G-MIG** | the specific migration for the frozen record | the Principal's approval of the migration file | P3 |
| **G-DEC** | each open decision (record §6) | entry in the record marked `CONFIRMED BY PRINCIPAL` | the phases that depend on it (§6) |
| **G-ISO** | running tests on `pbdigit274_isolated_test` with the identity guard | the Principal's authorization | every isolated-database RED/GREEN step |
| **G-AUTH-T** | any use of `nrna_test` | separate authorization (AUTH-T) | the `nrna_test` tests listed in §6 |
| **G-REL** | deployment, per D10 (and D8-L) | approved rollout + pre-flight result from operations | release |

**Never permitted:** `nrna` or any shared or development database.

## 5. Requirements traceability and acceptance criteria

| AC | Traces to | Business rule | Responsibility | Components (proposed) | Validation / authorization | Test layer | Evidence | Gates |
|---|---|---|---|---|---|---|---|---|
| AC-1 | R2; D1-V, D11 | System B configured per election | Elections (settings) | `UpdateElectionSettingsRequest`, `ElectionSettingsController`, dedicated System B write method, `Settings/Index.vue` | N within the range [cond. D1-V: 1–50]; strategy field present only if D11 = switchable [cond. D11]; otherwise only N | feature (isolated) | value persisted per election; two elections differ | G-ADR-A, G-DEC(D1-V, D11), G-ISO |
| AC-2 | R2; AUTH | only an authorised committee member configures | Elections (`ElectionPolicy`) | existing `UpdateElectionSettingsRequest::authorize()` → `manageSettings` | active chief/deputy allowed; commissioner, org admin, voter → 403 [cond. AUTH] | feature (isolated) | allow/deny matrix | G-DEC(AUTH), G-ISO |
| AC-3 | R2 | changeable before voting opens | Elections | conditional update (record §4.1 (1)) | `voting_locked = false` | feature (isolated) | accepted in Draft, setup and ReadyForVoting; `settings_version` bumped | G-ADR-A, G-ISO |
| AC-4 | R2 | **state:** refused once voting opens, on every path | Elections (model invariant) | 0-row refusal; `Election::saving` guard; fitness test against builder/`updateQuietly`/raw writes | server-side; the UI is not a security boundary | feature + model (isolated) + fitness (database-free) | HTTP refused; direct model save refused; value unchanged; fitness test green | G-ADR-A, G-ISO |
| AC-5 | R2 | **concurrency:** no settings write after opening; both opening paths behave the same; covers SetupNomination → VotingActive | Elections (lifecycle) | conditional updates in `applySideEffectsForOpenVoting` and a transactional `enforceVotingLock` | — | integration, two connections (isolated) | shown in both orders (record §4.1 (2)); one opening wins | G-ADR-A, G-ISO |
| AC-6 | R2; **D8, D8-L** | the policy fixed at opening is the policy enforced | Elections + Security | [cond. D8 = S2] frozen record, capture in both opening paths, evaluator reads only the record | hash recomputed; missing/mismatch → fail closed (record §4.4); legacy per D8-L | integration (isolated) | one record per opening; capture failure → voting stays closed; mismatch → refusal + event | G-ADR-B, G-MIG, G-DEC(D8, D8-L), G-ISO |
| AC-7 | R1, R3 | System A and System B independent | Security / Voting | `IpVoteLimit` unchanged | changing one leaves the other's values and decisions unchanged | isolated | cross-change tests | G-ISO |
| AC-8 | R3 | a vote is accepted only if every applicable control permits it; each uses its own counting and grouping | Security / Voting | unchanged ordering (System B first) | — | isolated | System B alone refuses; System A alone refuses; both would refuse → one refusal, one audit entry (from System B) | G-ISO |
| AC-9 | R4 | whitelist exempts both; IP binding still applies | Security | unchanged | — | isolated (regression) | whitelisted network passes both; IP binding still refuses | G-ISO |
| AC-10 | R3; D3, D3a, D4, D7, D7a | the effective limit follows the approved trust rules | Security (trust policy) | trust-only evidence port [cond. D3-P], `TrustPolicyEvaluator`, `NetworkTrustEvidence` | missing/revoked → Unverified; store error → fail closed; `revoked`/`registrarId` never set | unit + isolated | boundary tests per level (§6 P5, P6) | G-DEC(D3, D3a, D4, D7, D7a), G-ISO |
| AC-11 | R2, R3; **D9** | simultaneous votes can't exceed the applicable limit | Voting | [cond. D9 = (3)] lock before the single assessment. If D9 = (1): the documented limitation instead. | lock timeout → safe refusal | integration, two connections (isolated) | §6 P8 | G-ADR-C, G-DEC(D9), G-ISO |
| AC-12 | R2; AUD-1, AUD-2 | refusals and configuration are auditable; clear voter message | Elections audit + Voting | `settings_changed` with System B fields; `constitutional_policy_frozen` **after commit**; [cond. AUD-2 = (a)] `NetworkLimitReached` + `ip_blocked` constitutional with the **effective** limit | no raw IP or voter identity in metadata | isolated + unit | entries present; none after a rolled-back opening | G-DEC(AUD-1, AUD-2), G-ISO |
| AC-13 | R5; D10, D8-L | existing elections handled deliberately | Operations | rollout per D10 (record §5) | — | operational checklist | pre-flight result; job paused; rollback criteria met | G-DEC(D10, D8-L), G-REL |
| AC-14 | R1, R4 | earlier approved behaviour preserved | all | — | — | regression | the suites in §6 P9 green | G-ISO |

## 6. Test-first phases
Every coding step runs **RED → GREEN → REFACTOR → REGRESSION → REVIEW**, and stops for review before the next phase.

**Tests:**
- **Database-free tests** use plain PHPUnit (or the dead-socket base).
- **Database tests** run only on `pbdigit274_isolated_test` with the identity guard, after G-ISO.
- **Two-connection race tests** use the `lock_timeout` technique (as in `IpVoteLimitAtCastTest`). One process can't wait on itself.

| Phase | Prerequisites (gates) | RED (first failing test) | Minimal GREEN | Further tests | Database | Evidence to continue |
|---|---|---|---|---|---|---|
| **P0** | G-PLAN | — | ADR-A/B/C drafted; decisions recorded | — | — | approved ADRs (G-ADR-*) |
| **P1 Freeze** | G-ADR-A, G-ISO | **State:** after `voting_locked = true`, a direct model save of `max_votes_per_ip` throws and the value is unchanged | `Election::saving` refuses dirty System B fields on existing elections; dedicated conditional-update method | **Concurrency:** connection 2 holds an opening transaction (row locked, flag set, uncommitted); connection 1's conditional settings update hits `lock_timeout` (proves it waited); after commit, a retry changes 0 rows → refusal. Reverse order: settings committed first → opening proceeds and the stored value is the committed new value (capture is tested in P3, cond. D8). **Write paths:** fitness test fails on builder/`updateQuietly`/raw writes of System B columns outside the method. System A lock unchanged. | isolated + database-free | runs for state, concurrency and write path |
| **P2 Configuration** | P1, G-DEC(D1-V, D11, AUTH, D1-UI), G-ISO | a chief saves N before opening; it persists and `settings_version` is bumped | request rules; the whole settings save in one transaction calling the dedicated method; 0 rows → whole request refused; `settings_changed` includes the fields; Vue panel with **its own freeze flag** from `voting_locked` (design-check) | commissioner/org admin/voter → 403 [cond. AUTH]; range [cond. D1-V]; strategy field only [cond. D11 = switchable]; ReadyForVoting still editable; System A fields still locked from `isLocked()` | isolated | runs + `npm run design-check` |
| **P3 Capture** | P1, G-ADR-B, G-MIG, G-DEC(D8), G-ISO [cond. D8 = S2] | `open_voting` creates exactly one frozen record with the locked row's values and hash | migration (additive); immutable model; capture by the 1-row winner in both opening paths; `enforceVotingLock` made transactional and conditional | capture failure → voting stays closed (both paths); concurrent manual + scheduled opening → one record; update/delete of a record refused; freeze audit only after commit | isolated | runs |
| **P4 Enforcement source** | P3, G-DEC(D8-L), G-ISO | during voting, the evaluator uses the frozen N, not the live column | evaluator reads only the record; recomputes the hash | missing record → refusal + forensic event + alert; mismatch → same; legacy per D8-L: (a) backfill (separate, reviewed step under G-MIG/G-REL) or (b) versioned path | isolated | runs |
| **P5 Evidence** | G-DEC(D3, D3a, D4, D3-E, D3-P), G-ISO | an active **individual** verification yields Attested [cond. D3 = A or B; if D3 = C: Unverified] | trust-only read port + adapter + evaluator wiring [cond. D3-P] | bulk → Attested [cond. D3 = A] / Unverified [D3 = B, C]; legacy row → Attested [cond. D3a = yes] / Unverified [no]; missing/revoked → Unverified and **no denial**; store error → refusal; **admission tests unchanged** | database-free (port fake) + isolated | runs; admission suite unchanged |
| **P6 Thresholds** | G-DEC(D7, D7a, D6), G-ISO | with effective limit L and **L completed votes** from the network, the next voter is **refused** [cond. D7 = `>=`]; if D7 = `>`, refused only with **L + 1** completed votes | comparison per D7; interpreter minimum 1 [cond. D7a]; count via `IpVoteLimit::votesFrom` [cond. D6 = reuse; if keep: exact IP, tenant-scoped] | one below / at / one above for each reachable level, N = 1 and N = 6; IPv6 /64 [cond. D6 = reuse] | database-free + isolated | runs |
| **P7 Verdict and audit** | G-DEC(AUD-1, AUD-2), G-ISO | a System B refusal yields `NetworkLimitReached` with the agreed wording [cond. AUD-2 = (a); if (b): generic message kept, audit only] | verdict, reasons, messages; `ip_blocked` constitutional with the **effective** limit; finding reports the effective limit | no IP or identity in metadata; forensic events unchanged; AC-8 single-entry rule | database-free + isolated | runs |
| **P8 Concurrency** | G-ADR-C, G-DEC(D9), G-ISO [cond. D9 = (3)] | connection 2 holds the network lock; a vote from that network is refused as busy, with no vote stored, **before** the assessment runs | take the lock before the single assessment in `VoteController::store` | **same-session re-acquire proven by test** (no claim before); whitelisted takes no lock; System B stricter under a held lock. If D9 = (1): a test that documents the limitation instead. | isolated | runs |
| **P9 Regression and docs** | all previous phases | — | — | `IpVoteLimitTest`, `IpVoteGroupTest`, `IpVoteLimitAtCastTest`, `IpRefusalAuditTest`, `IpVoteLimitFlowTest`, `TrustedProxyTest`, `ClientIpHasOneSourceTest`, `ElectionIpWhitelistTest`, `ResultsAccuracyTest`, full isolated suite; `nrna_test` tests only after G-AUTH-T | isolated (+ `nrna_test` after G-AUTH-T) | evidence folder; developer guide `developer_guide/election/ip_voting/06_constitutional_limit.md`; backlog progress |

**Existing tests that change deliberately (each conditional on its decision):**
- **Database-free:**
  - `NetworkTrustEvidenceTest` (P6, cond. D7);
  - `NetworkBindingPolicyTest` (P6 cond. D7; P7);
  - `NetworkThresholdInterpreterTest:72` (P6, cond. D7a).
- **Isolated:** `IpVoteLimitFlowTest` constitutional message (P7, cond. AUD-2 = (a)).
- **`nrna_test`, after G-AUTH-T only:**
  - `SecurityWhitelistPolicyTest`;
  - `CodeEntryAuthorizationTest`;
  - `VotingSecurityVerdictAdapterTest`;
  - `SecurityPolicySurfaceCharacterizationTest`;
  - `ConstitutionalImmutabilityTest`;
  - `TrustPolicyEvaluatorTest`;
  - `SecurityEventRecorderTest`.

## 7. Security and concurrency requirements
- **Authorization** is server-side (`UpdateElectionSettingsRequest::authorize()` → `manageSettings`; `can:manageSettings` on `/open-voting`). UI flags only reflect it.
- **Freeze** (record §4.1):
  - state protection by the conditional update and the model guard;
  - concurrency protection by the conditional updates in both opening paths;
  - write-path protection by the fitness test, with the trigger as an ADR-A option.

  Concurrency is claimed only after the P1 two-connection tests pass.
- **Integrity** [cond. D8 = S2]: the record is immutable and its hash is checked when read. Missing or mismatching records fail closed. **No fallback to live columns.**
- **Concurrency at the vote** [cond. D9 = (3)]: claimed only after the P8 tests pass.
- **Audit:** the freeze entry is written only after commit; the frozen record is the authoritative evidence. No raw IP or voter identity in metadata. Forensic and admin records stay separate.

## 8. Risks
| Risk | Mitigation |
|---|---|
| Code changes alter running elections | D10 condition (record §5) or versioning |
| Elections opened before deployment have no frozen record | D8-L (open); no silent fallback |
| Dynamic or manual writes bypass the fitness test | ADR-A trigger option |
| The production database isolation is not READ COMMITTED | verify before G-ADR-A [U] |
| The scheduled opening job has no transaction today | P3 makes it transactional and conditional |
| The same-session advisory lock re-acquire is unproven | P8 test before any claim |
| The synchronous email lengthens the network-lock hold | separate backlog item; measure in P8 |
| A file audit entry is lost after commit | reconciliation from frozen records (record §4.5) |

## 9. Task checklist
- [ ] G-PLAN
- [ ] Decisions (record §6, 16 items) → G-DEC
- [ ] ADR-A, ADR-B, ADR-C → G-ADR-*; migration → G-MIG
- [ ] G-ISO; G-AUTH-T (optional)
- [ ] P1 … P9 (each with evidence and a developer-guide step)
- [ ] D10 / D8-L rollout → G-REL

## 10. Progress
- **2026-10-14:** r1 drafted.
- **2026-10-14:** r2: consistency corrections C-1 to C-8. No code. Awaiting EP-01 approval.

## 11. Open questions
See record §6 (16 items, including the new D8-L and the recommendations that still need confirmation).

## 12. Stop conditions
- **Stop** before any phase whose gates (§4, §6) are unsatisfied.
- **Stop and re-plan** if implementation contradicts this plan (EP-01: never change direction silently).
- **Stop** if any test would need a database other than `pbdigit274_isolated_test`, or `nrna_test` without G-AUTH-T.
- **Stop** if the production isolation level turns out not to be READ COMMITTED before ADR-A is approved.

## 13. Next actions
1. The Principal reviews record r2 and this plan r2.
2. On G-PLAN: draft ADR-A/B/C (P0) for approval. No phase starts before its gates.

**Traceability:** PBDIGIT-286 · R1–R5 · decision record r2 (2026-10-14) · HEAD `87f76d4e9`.
