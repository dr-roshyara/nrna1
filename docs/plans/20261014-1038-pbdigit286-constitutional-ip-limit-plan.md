# PBDIGIT-286 constitutional IP limit: implementation plan

**Status: r3, APPROVED (G-PLAN) by the Principal on 2026-10-14 at `914b773aa`. The approval authorises P0 only** (drafting ADR-A, ADR-B, ADR-C and the AUD-2 entry). The Human Arbiter's policy decisions of 2026-10-14 are **approved** (record §3.2), but that approves **no** ADR, migration, backfill, database access, test run or deployment. Each has its own gate (§4).

**Revisions:**
- r2: C-1 to C-8.
- **r3:** H1–H7, O-2, O-3, and the approved Human Arbiter decisions.

Created 2026-10-14 at the Principal's request, before approval.

**Decision record:** [`docs/publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md`](../publicdigit/reviews/2026-10-14-pbdigit286-principal-decision-record.md) r3. **Current state:** [`…-constitutional-ip-limit-current-state.md`](../publicdigit/reviews/2026-10-14-pbdigit286-constitutional-ip-limit-current-state.md) (earlier numbering; record §3.1). **Current-behaviour guide:** `developer_guide/election/ip_voting/06_constitutional_limit.md`. **Supersedes:** none.

**Terms:**
- **System A** = `IpVoteLimit`.
- **System B** = the constitutional limit.
- **"Voting opens"** = `voting_locked` becomes `true`.
- **"Opened real election"** = `type = real` and `voting_locked = true`.

---

## 1. Objective
Make System B a visible, per-election, committee-configured, **mandatory** rule that is frozen when voting opens, captured in an immutable record, and enforced consistently and safely under concurrency, while it stays separate from System A (R1–R5).

## 2. Background
- **Hidden and effectively 3:** every voter is Unverified.
- **One extra vote:** `>`.
- **Exact-IP counting.**
- **Unprotected at the vote:** assessed before any lock.
- **No configuration path, no freeze.**
- **No admin-facing audit.**

Evidence: record §2.

## 3. Scope
**In scope:**
- **Configuration:** N ∈ [1, 50], strategy fixed to `ip_count` (D1-V, D11).
- **Authorization:** chief/deputy (AUTH).
- **Separate UI panel** (D1-UI).
- **Freeze when voting opens** (D1, ADR-A).
- **Frozen policy record** (D8, ADR-B) and **backfill** (D8-L, D8-L-S).
- **Policy-source rules** (H1); **fixture strategy F1** (H2); **canonical hash** (H4).
- **Evidence wiring** (D3, D3a, D3-E, D3-P); **scaling and boundary** (D4, D4-N, D7a, D7); **grouping** (D6).
- **Verdict, message and audit** (AUD-1, AUD-2).
- **Concurrency** (D9, ADR-C).
- **Rollout** (D10, D10-B).
- **Regression protection; documentation.**

**Non-goals:**
- **No changes to System A,** to its override or to its lock.
- **No change to admission** (D3-P debt recorded).
- **No new trust levels.**
- **Enforcement of an already stored `none`/`ip_strict` stays unchanged** without an ADR (record §8 A-2).
- **Not in this ticket:**
  - the unkeyed IP hash in `election_security_events` (PBDIGIT-288 item 6);
  - the synchronous vote-code email (**BL-EMAIL**, a separate backlog task);
  - reopening PBDIGIT-278, 285, 287 or 288.

## 4. Gates
Each gate is separate. **Satisfying one doesn't satisfy another.**

| Gate | What it approves | Required evidence | Blocked until satisfied |
|---|---|---|---|
| **G-PLAN** | this plan (EP-01) | the Principal's written approval of r3 | everything |
| **O-3** | production PostgreSQL isolation verified (READ COMMITTED) | operations report | G-ADR-A |
| **G-ADR-A** | freeze protocol (record §4.1) incl. H6, H7, the D11 write rule, the trigger evaluation. **Status: BLOCKED until O-3** (A-2 and the ADR-A-T rule decided inside it on 2026-10-14) | approved ADR file | P1, P2 |
| **G-ADR-B** | frozen record, capture, policy source (H1), fixtures (F1), hash (H4, A-1), reconciliation (AUD-1), backfill design. **Status: APPROVED CONDITIONALLY 2026-10-14** (record §3.4) | approved ADR file ✅ (conditions in record §3.4) | P3, P4, R: **still also need G-MIG, G-ISO** |
| **G-ADR-C** | lock before the single assessment, lock scope, tenant-context handling for the D6 count. **Status: APPROVED CONDITIONALLY 2026-10-14** (record §3.4) | approved ADR file ✅ (mandatory proofs, ADR-C §6) | P6 (count change), P8: **still also need G-ISO** and phase authorisation |
| **G-AUD2** | decision-record entry: the narrow E2-D4 exception | entry approved | P7 |
| **G-MIG** | frozen-record migration **and** the backfill design (record §4.8), **plus the trigger migration if ADR-A-T requires it** (decision rule, record §3.4) | the Principal's approval of the migration file and backfill design | P3, R |
| **G-ISO** | tests on `pbdigit274_isolated_test` with the identity guard. **Status: AUTHORISED for P5 only** (Principal, 2026-10-14 18:54 UTC; §10). Every other phase needs its own authorisation | the Principal's authorization | every isolated-database step |
| **G-AUTH-T** | any use of `nrna_test` | separate authorization | the `nrna_test` tests in §6 |
| **G-REL** | deployment | record §5 pre-flight evidence (**any-election** empty-end-time block passed); a statement that **PBDIGIT-289** is fixed or accepted as a documented known risk, with the scheduled-transition behaviour reviewed (ADR-B §3.6b); critical-alert routing | phase R and release |

**Never permitted:** `nrna` or any shared database.

## 5. Requirements traceability and acceptance criteria
Approved decisions are cited by ID (record §3.2). **"Gates" lists what still blocks the criterion.**

| AC | Traces to | Business rule | Components (proposed) | Validation / authorization | Test layer | Evidence | Gates |
|---|---|---|---|---|---|---|---|
| AC-1 | R2; D1-V, D11 | System B configured per election; mandatory | request rules; dedicated System B write method; `Settings/Index.vue` | N integer in [1, 50]; strategy fixed to `ip_count`; `none` refused | feature (isolated) | value persisted per election; two elections differ; `none` and out-of-range refused | G-ADR-A, G-ISO |
| AC-2 | R2; AUTH | only active chief/deputy configure | existing `UpdateElectionSettingsRequest::authorize()` → `manageSettings` | commissioner, org admin, voter → 403 | feature (isolated) | allow/deny matrix | G-ISO |
| AC-3 | R2; H6, H7 | changeable before voting opens; written only on change; one version bump | conditional update (record §4.1) | `voting_locked = false` | feature (isolated) | accepted in Draft, setup and ReadyForVoting; `settings_version` +1 exactly; an unchanged System B value doesn't trigger the write | G-ADR-A, G-ISO |
| AC-3a | R2; F9 (approved 2026-10-14) | **atomic optimistic check:** a settings update is accepted only if the stored `settings_version` still equals the version the request carries; every **accepted** update increments it **exactly once**; a **stale** request changes nothing | the general settings update made conditional (`… WHERE id = ? AND settings_version = ?`), in the same transaction as the System B write; 0 rows → refuse | the existing message "Settings were modified by another user. Please reload and try again."; the transaction rolls back (no field of the stale request, System B included, persists); no `settings_changed` entry | feature + two-connection (isolated) | (1) a stale version → refused, nothing changed; (2) **two concurrent saves with the same version → exactly one accepted, the other refused, `settings_version` +1 once**; (3) an accepted save → +1 exactly | G-ADR-A, G-ISO |
| AC-4 | R2 | **state:** refused once voting opens, on every path | 0-row refusal; model guard; fitness test | server-side; the UI is not a boundary | feature + model (isolated) + fitness (database-free) | HTTP refused; direct model save refused; value unchanged; **after voting closes, other settings still save** (H6) | G-ADR-A, G-ISO |
| AC-5 | R2; D1 | **concurrency:** no settings write after opening; both opening paths behave the same, including SetupNomination → VotingActive | conditional updates in `applySideEffectsForOpenVoting` and a transactional `enforceVotingLock` | — | two-connection (isolated) | both orders shown; one opening wins | G-ADR-A, O-3, G-ISO |
| AC-6 | R2; D8, D8-L, D8-L-S, H1, H4 | the policy fixed at opening is the policy enforced; invariant `voting_locked ⇒ record` (real) | frozen record, capture, hash builder, evaluator policy-source rule | missing/mismatch → fail closed; before opening and demo → live | integration (isolated) + database-free (hash) | one record per opening; capture failure → voting stays closed; mismatch → refusal + forensic event + alert; pre-opening and demo assessments don't fail | G-ADR-B, G-MIG, G-ISO |
| AC-7 | R1, R3 | System A and System B independent | `IpVoteLimit` unchanged | — | isolated | cross-change tests | G-ISO |
| AC-8 | R3 | accepted only if every applicable control permits it | System B evaluated first | — | isolated | System B alone refuses; System A alone refuses; both → one refusal, one audit entry (System B) | G-ISO |
| AC-9 | R4 | whitelist exempts both; IP binding still applies | unchanged | — | isolated (regression) | as stated | G-ISO |
| AC-10 | R3; D3, D3a, D3-E, D3-P, D4, D4-N, D7a | the effective limit follows the approved table | trust-only evidence port; evaluator; `NetworkTrustEvidence` | missing/revoked → Unverified; store error → fail closed; `revoked`/`registrarId` never set | database-free + isolated | individual, bulk and legacy active → Attested; missing/revoked → Unverified and not denied; admission tests unchanged | G-ISO |
| AC-11 | R2, R3; D7, D6 | refused once L completed votes exist; counted by IP group within this election | comparison `>=`; count via `IpVoteLimit::votesFrom` | — | database-free + isolated | one below / at / one above, N = 1, 6 and 50, Unverified and Attested; IPv6 /64 | G-ADR-C (count/tenant), G-ISO |
| AC-12 | R2, R3; D9 | simultaneous votes can't exceed System B | network lock before the single assessment | timeout → safe refusal | two-connection (isolated) | P8 tests incl. same-session re-acquire | G-ADR-C, G-ISO |
| AC-13 | R2; AUD-1, AUD-2 | auditable configuration, freeze and refusals; clear message without a number | `settings_changed` incl. System B; `constitutional_policy_frozen` after commit + reconciliation; `NetworkLimitReached`; `ip_blocked` constitutional with the **effective** limit | no raw IP or voter identity | database-free + isolated | entries present; none after a rolled-back opening; reconciliation re-emits a missing entry | G-ADR-B, G-AUD2, G-ISO |
| AC-14 | R5; D10, D10-B, D8-L, D8-L-S | existing elections handled deliberately | record §5 pre-flight; backfill command | — | operational + isolated (backfill) | pre-flight evidence; dry-run report; invariant verified | G-MIG, G-REL |
| AC-15 | R1, R4; H2 | earlier behaviour preserved | F1 fixture before enforcement | — | regression | suites in P9 green after the fixture step | G-ISO (+ G-AUTH-T for `nrna_test`) |

## 6. Test-first phases
Every coding step runs **RED → GREEN → REFACTOR → REGRESSION → REVIEW**, and stops for review before the next phase.

**Tests:**
- **Database-free tests** use plain PHPUnit (or the dead-socket base).
- **Database tests** run only on `pbdigit274_isolated_test` with the identity guard, after G-ISO.
- **Two-connection race tests** use the `lock_timeout` technique (as in `IpVoteLimitAtCastTest`).

| Phase | Prerequisites | RED (first failing test) | Minimal GREEN | Further tests | Database | Evidence to continue |
|---|---|---|---|---|---|---|
| **P0** | G-PLAN | — | ADR-A, ADR-B, ADR-C and the AUD-2 entry drafted for approval (O-3 is needed for ADR-A's **approval**, not its drafting) | — | — | approved ADRs |
| **P1 Freeze** (model and database level only, G-A4) | G-ADR-A, G-ISO | **State:** a direct model save of a System B field on an existing election throws; value unchanged | model guard; dedicated conditional-update method (no `settings_version` change, G-A2); **both opening paths conditional and transactional** (`applySideEffectsForOpenVoting`, `enforceVotingLock`; G-A3); **A-2** (explicit + auto-opening refuse a stored `none`; the after-end lock reports it, G-A1) | **Write paths:** fitness test (its scanner proven able to fail). **Concurrency:** two-connection, both orders (opening first → 0 rows; settings first → the stored value is the committed one); manual + scheduled opening → one winner. **A-2:** explicit and auto-opening refused for `none`; `ip_strict` like `ip_count`; the after-end lock proceeds and reports. System A lock unchanged. Deliberate change (F10, approved 2026-10-14): `VotingSecurityVerdictAdapterTest:181,231` from `forceFill(...)->save()` to `DB::table('elections')->…->update(['network_binding_strategy' => 'none'])` + `->fresh()`, **preserving their meaning** (`a04`: a stored `none` disables the density rule; `e1`: assessment equivalence). Verifying it needs G-AUTH-T. | isolated + database-free | runs (sequence: readiness review P1.1–P1.6) |
| **P2 Configuration** | P1, G-ADR-A, G-ISO | an active chief sets N = 12 before opening; persisted | request rules (N integer 1–50; strategy not offered; `none` refused); one transaction; `settings_changed` includes the fields; separate Vue panel with its own freeze indicator and the D1-UI explanations (design-check) | commissioner/org admin/voter → 403; N = 0 and 51 refused; ReadyForVoting editable; System A fields still locked from `isLocked()`; **T-1 (H6, moved from P1 by G-A4):** after opening, saving other settings with unchanged System B values succeeds; **T-2 (H7, G-A4):** `settings_version` +1 exactly, from the controller's absolute assignment only (G-A2); **F9 / AC-3a (approved 2026-10-14):** the general update becomes conditional on the request's `settings_version`. A stale request is refused, the whole request rolls back, and the existing message is shown. Two concurrent saves with the same version: exactly one accepted, version +1 once (two-connection test) | isolated | runs + `npm run design-check` |
| **P3 Capture** | P1, G-ADR-B, G-MIG, G-ISO | `open_voting` creates exactly one frozen record whose hash matches the canonical format | migration (additive); immutable model; **T-5 (H4)** shared hash builder; capture by the 1-row winner, **added to** the conditional opening paths from P1 (G-A3: P3 adds only the capture); `captured_by` = `opening` / `scheduled` / **`scheduled_after_end`** (the after-end lock, G-A1 clarification) | capture failure → voting stays closed (both paths); manual + scheduled together → one record; update/delete refused; **T-4 (H2, F1):** `inVotingState()` writes a marked test-only record, its own RED → GREEN, **before P4**; **T-6 (AUD-1):** after-commit freeze audit + reconciliation command | isolated + database-free (hash) | runs; full isolated suite green after T-4 |
| **P4 Policy source** | P3, G-ADR-B, G-ISO | an opened real election with a record whose N differs from the live column is evaluated with the record's N | **T-3 (H1):** evaluator source rule (opened real → record; before opening and demo → live) | missing record → refusal + forensic event + alert; hash mismatch → same; pre-opening real and demo assessments don't fail; **F10 follow-up:** `VotingSecurityVerdictAdapterTest` `a04`/`e1` set the **frozen record's** strategy through the F1 test helper (the live column is no longer read for opened real elections), keeping their meaning | isolated | runs |
| **P5 Evidence** | G-ISO | an active **individual** verification yields Attested | trust-only read port + adapter + evaluator wiring (D3-P) | active **bulk** → Attested (D3 = A); active legacy row → Attested (D3a); missing/revoked → Unverified, no denial (D3-E); store error → refusal; admission suite unchanged | database-free (port fake) + isolated | runs |
| **P6 Thresholds and grouping** | G-ISO; G-ADR-C for the count change | with effective limit L and **L completed votes**, the next voter is refused (D7 `>=`) | comparison `>=`; interpreter minimum 1 (D7a); count via `IpVoteLimit::votesFrom` (D6), election-scoped | one below / at / one above, N = 1, 6, 50, Unverified and Attested; IPv6 /64 | database-free + isolated | runs |
| **P7 Verdict and audit** | G-AUD2, G-ISO | a System B refusal yields `NetworkLimitReached` with wording that contains no number | verdict, reasons, messages; `ip_blocked` constitutional with the **effective** limit; the finding reports the effective limit | no IP or identity in metadata; forensic events unchanged; AC-8 single entry | database-free + isolated | runs |
| **P8 Concurrency** | G-ADR-C, G-ISO | connection 2 holds the network lock; a vote from that network is refused as busy, no vote stored, before the assessment | lock before the single assessment in `VoteController::store` | same-session re-acquire proven; whitelisted takes no lock; System B stricter under a held lock | isolated | runs |
| **P9 Regression and docs** | P1–P8 | — | — | `IpVoteLimitTest`, `IpVoteGroupTest`, `IpVoteLimitAtCastTest`, `IpRefusalAuditTest`, `IpVoteLimitFlowTest`, `TrustedProxyTest`, `ClientIpHasOneSourceTest`, `ElectionIpWhitelistTest`, `ResultsAccuracyTest`, full isolated suite; `nrna_test` tests only after G-AUTH-T | isolated (+ `nrna_test` after G-AUTH-T) | evidence folder; guide `developer_guide/election/ip_voting/06_constitutional_limit.md` **updated from current behaviour to the implemented design**; backlog progress |
| **R Release** | P9, G-MIG, G-REL | **T-7:** backfill command test on the isolated database: idempotent, dry run, inconsistency report (for example a stored `none`) | backfill command (record §4.8) | invariant check: every election with `voting_locked = true` has a record | isolated | dry-run output; pre-flight evidence (record §5) |

**Existing tests that change deliberately:**
- **Database-free:** `NetworkTrustEvidenceTest` (P6, D7), `NetworkBindingPolicyTest` (P6, P7), `NetworkThresholdInterpreterTest:72` (P6, D7a).
- **Isolated:** `IpVoteLimitFlowTest` constitutional message (P7); every voting fixture through F1 (P3 T-4).
- **`nrna_test`, after G-AUTH-T only:** `SecurityWhitelistPolicyTest` (above the limit: 4 becomes 3), `CodeEntryAuthorizationTest`, `VotingSecurityVerdictAdapterTest`, `SecurityPolicySurfaceCharacterizationTest`, `ConstitutionalImmutabilityTest`, `TrustPolicyEvaluatorTest`, `SecurityEventRecorderTest`.

## 7. Security and concurrency requirements
- **Authorization** is server-side (`manageSettings`). The UI is never a boundary.
- **Freeze** (record §4.1): state, concurrency and write-path protection. Concurrency is claimed only after the P1 two-connection tests.
- **Mandatory System B** (D11): the configuration path can't write `none`.
- **Integrity** (D8): the frozen record is immutable; its hash is verified on read with one shared builder; missing or mismatching records fail closed. No fallback to live settings for opened real elections. Live settings apply only before opening and for demo elections (H1).
- **Concurrency at the vote** (D9): claimed only after P8.
- **Audit:** after commit, with reconciliation (AUD-1). No raw IP or voter identity in metadata. Forensic and admin records stay separate.

## 8. Risks
| Risk | Mitigation |
|---|---|
| Code changes alter running elections | D10 = W with the record §5 pre-flight |
| Manual opening during deployment | D10-B maintenance mode (not just pausing the job) |
| Legacy opened elections without a record | backfill (D8-L-S) before activation; invariant check |
| Fixtures break under fail-closed enforcement | F1 (T-4) before P4 |
| Fixture mistaken for protocol proof | AC-5/AC-6 test the real opening paths |
| The production isolation level isn't READ COMMITTED | O-3 before G-ADR-A |
| Dynamic or manual writes bypass the fitness test | ADR-A trigger option |
| Stored `none`/`ip_strict` on an opened election | A-2 (refuse opening) and A-2b (historical, conditional) approved 2026-10-14; the backfill reports every case |
| The scheduled transition locks voting without a voting window (PBDIGIT-289) | known interaction, not this ticket's scope; the empty-end-time pre-flight blocks it at release; G-REL states fixed or accepted risk |
| `election_id` serialisation in the hash | ADR-B (record §8 A-1) |
| The same-session advisory re-acquire is unproven | P8 test before any claim |
| The synchronous email lengthens the lock hold | BL-EMAIL, recorded as an extension of backlog PBDIGIT-282 (`7eb57cf15`) |
| A file audit entry is lost after commit | reconciliation (T-6) |
| **P5 released without P6** (P5 review F1, 2026-10-14). P5 raises an attested voter's L to N while the refusal is still `votesFromThisIp > L` (`NetworkTrustEvidence::exceedsLimit`), so an attested network completes **N + 1** (7 at N = 6, against the approved 6); unverified stays at 4 (approved 3) | **P5 and P6 are released together.** P5 may be committed but must not reach a deployable branch or a release before P6 (D7 `>=`, under its own approval and the G-ADR-C proofs). G-REL checks it |

## 9. Task checklist
- [x] G-PLAN (2026-10-14, at `914b773aa`)
- [ ] O-3 (operations)
- [x] G-ADR-B (approved conditionally 2026-10-14) · [x] G-ADR-C (approved conditionally 2026-10-14)
- [ ] G-ADR-A (blocked until O-3) · [ ] ADR-A-T operations confirmation · [ ] G-AUD2 (§9 wording pending)
- [ ] G-MIG (migration + backfill design)
- [x] G-ISO **for P5 only** (Principal, 2026-10-14 18:54 UTC) · [ ] G-ISO for any other phase · [ ] G-AUTH-T (optional)
- [ ] P1 … P9 (each with evidence and a developer-guide step)
- [ ] R: backfill, pre-flight → G-REL
- [ ] BL-EMAIL backlog item (separate task)

## 10. Progress
- **2026-10-14:**
  - r1 drafted;
  - r2: C-1 to C-8;
  - r3: H1–H7, O-2, O-3, and the Human Arbiter decisions recorded.
- **2026-10-14:**
  - **G-PLAN approved** (P0 only); A-1 confirmed (UUID as a JSON string).
  - **P0:** ADR-A, ADR-B and ADR-C drafted in `docs/publicdigit/adr/ADR_20261014_1138_*`; the AUD-2 entry drafted in record §9.
  - A-2 proposed in ADR-A §3.5 and ADR-B §3.3, §3.6.
- **2026-10-14, recorded** (record §3.4):
  - A-2 approved;
  - A-2b approved with conditions;
  - the empty-end-time rule approved (any election);
  - G-ADR-B and G-ADR-C approved conditionally;
  - the ADR-A-T decision rule approved (condition not satisfied);
  - G-AUD2 pending;
  - G-ADR-A blocked (O-3).
- **No code.**

- **2026-10-14, G-ISO + P5 (authorised by the Principal; nothing else):**
  - trust-only read port `VoterAttestationEvidence` + adapter `DatabaseVoterAttestationEvidence` (same predicate as admission; admission code unchanged, D3-P); `TrustPolicyEvaluator::buildAttestationRecord()` asks it (`registrarId`, `revoked` never set, D3-E); binding in `AppServiceProvider`;
  - database-free `TrustPolicyAttestationEvidenceTest`: RED 6 errors + 1 failure → GREEN 7/17;
  - isolated `VoterAttestationEvidenceTest` (G-ISO, guard confirmed `pbdigit274_isolated_test`): 7/8, exit 0; individual, bulk and legacy active → Attested; revoked/missing → Unverified; end to end through the evaluator;
  - **finding:** `elections.voter_verification_required` doesn't exist, so real attestation changes only the trust level (System B limit), not the verification finding;
  - **not run:** the admission suite (default suite on `nrna_test`, not authorised; admission code has no diff). Evidence `docs/publicdigit/election/evidence/2026-10-14-pbdigit286-p5-evidence/`; guide `developer_guide/election/ip_voting/07`. **Uncommitted, awaiting review.**
  - **Chronology:** the Principal authorised "G-ISO and the P5 Evidence phase only" at 2026-10-14 18:54 UTC; the isolated run was 19:01–19:01:10 UTC (21:01 local, evidence `01`). The authorisation does not approve O-3, G-ADR-A, G-MIG, G-AUD2, G-REL or any later phase.
- **2026-10-14, independent review of P5:** PASS WITH FINDINGS (F1–F8). **Corrections at the Principal's instruction:**
  - **F1:** P5 must not be released without P6 (§8 risk). Today's `>` lets a network complete L + 1: with P5, 7 attested / 4 unverified at N = 6; after P6 (`>=`), 6 / 3. **P6 is not implemented and the comparison is unchanged.**
  - **F2:** numbers corrected in guide 07 and the database-free test's docblock.
  - **F4:** database-free test that the real adapter throws on a read failure (dead socket); isolated case that statuses other than exactly `active` don't count. The predicate stays `status = 'active'`.
  - **F5:** raw database-free output saved (evidence `02`–`05`); the 3 deprecations recorded.
  - **F6:** G-ISO (P5 only) recorded here (§4, §9) and in the decision record.
  - **F7:** guide 06 and the index point to guide 07.
  - **Runs:** database-free P5 test 8/18, exit 0; regression `TrustPolicyEvaluatorNetworkRestrictionTest` 5/8, exit 0; `LaravelBootIsGuardedTest` 18/30, exit 0. isolated F4 run 8/12, 3 deprecations, exit 0, guard confirmed `pbdigit274_isolated_test` (evidence `06`; a first attempt was denied by the tool's permission classifier and executed nothing). **Not run:** `TrustPolicyEvaluatorTest` and `TrustPolicyEvaluatorD4Test` (they extend `Tests\TestCase`, not database-free); the admission suite and the `nrna_test` feature tests (G-AUTH-T).

- **2026-10-15, second independent review of P5:** PASS WITH FINDINGS. Documentation findings N1, N2, N5, N6 and F7 corrected (session log 2026-10-15). **P5 is not accepted.** Open for the Principal:
  - the P5+P6 release control (today a process gate only);
  - N7: the verification read runs on every evaluation, including demo and unrestricted elections;
  - F3;
  - the evidence gaps (README "Not run").
  - The P5/P6 counts above are System B only; System A can refuse earlier.

- **2026-10-15, P6 boundary step** (the Principal's instruction "finish the IP vote-limit architecture"; database-free only):
  - **D7:** `NetworkTrustEvidence::exceedsLimit` now uses `>=`. Verified first: `votesFromThisIp` counts completed votes without the current voter (`TrustLegitimacySecurityVerdict.php:68-71`).
  - **D7a:** `NetworkThresholdInterpreter` Initial = `max(1, ⌊N/2⌋)`; it has no production caller.
  - **Tests:** AC-11 boundaries (one below / at / one above, N = 1, 6, 50, Unverified and Attested), and P5 with P6 through `NetworkBindingPolicy`.
  - **Runs:** RED 12 failures across 4 files → GREEN, plus database-free regression (24 files, exit 0). Evidence `docs/publicdigit/election/evidence/2026-10-15-pbdigit286-p6-evidence/`; guide 08.
  - **Open in P6:** the D6 count step (`IpVoteLimit::votesFrom`, ADR-C §3.2), which needs **G-ISO for P6**.
  - **Not run:** the evaluator tests, `IpVoteLimitFlowTest`, the caller-level failure tests (G-ISO for P6), and the `nrna_test` tests (G-AUTH-T).
  - **Status (then):** uncommitted, not accepted. Committed later in `2f998b8ae` (see below).

- **2026-10-15, P6 D6 + caller evidence** (G-ISO for P6 items 1–4, authorised by the Principal; `pbdigit274_isolated_test` only, guard confirmed on every run):
  - **D6:** `TrustLegitimacySecurityVerdict::assess` counts through `IpVoteLimit::votesFrom` (ADR-C §3.2). Root cause confirmed: the old query was tenant-scoped (0 with no tenant context) and exact-IP.
  - **D6 run:** RED 4 failures, all the defect (`04b`) → GREEN 5/14 (`05`). A first RED attempt (`04a`) was invalid: the test observed the security event, which is sampled. It now observes the decision; the assertions were unchanged.
  - **Gap 4 closed:** `VerificationStoreFailureTest` 4/25. Code entry refuses generically; the real vote stores nothing and doesn't consume participation; the demo vote rolls back. A demo fixture fix is recorded (`06a`/`06b`).
  - **Gaps 2 and 6 closed:** `TrustPolicyEvaluatorTest` 5/9, `TrustPolicyEvaluatorD4Test` 3/7, `IpVoteLimitFlowTest` 6/47.
  - **Regression:** `IpVoteLimitAtCastTest` 8/34, `VoterAttestationEvidenceTest` 8/12; database-free architecture + IP set 28 files, exit 0.
  - **Still open (then):** gap 1 (mutation proof); gap 3 and the `nrna_test` P6 tests (G-AUTH-T); P7, P8, G-REL; ARB acceptance.

- **2026-10-15, P5 + P6 committed `2f998b8ae` (not pushed); gaps closed:**
  - mutation proof M1–M3, each restored with a matching hash;
  - the plan's `nrna_test` P6 tests run on the isolated database instead: 7 of 8 pass; `SecurityEventRecorderTest` fails, probably PBDIGIT-42 (code reading, not runtime-confirmed);
  - the unreadable-IP test passes (evidence `28`).
  - **Status:** awaiting the Principal's formal P5/P6 acceptance. Limitations: the full isolated suite and `nrna_test` not run; production enforcement unverified. Release not approved.

## 11. Open questions
**Human Arbiter policy decisions:** none open (record §3.2).

**Remaining:**
- the gates in §4;
- the ambiguities A-1 (`election_id` representation) and A-2 (stored `none`/`ip_strict`), for the ADRs (record §8).

## 12. Stop conditions
- **Stop** before any phase whose gates are unsatisfied.
- **Stop and re-plan** if implementation contradicts this plan (EP-01).
- **Stop** if any test would need a database other than `pbdigit274_isolated_test`, or `nrna_test` without G-AUTH-T.
- **Stop** if O-3 shows an isolation level other than READ COMMITTED.

## 13. Next actions
1. ~~The Principal reviews plan r3 → G-PLAN~~ (done 2026-10-14).
2. Operations: O-3.
3. ~~Draft ADR-A, ADR-B, ADR-C and the AUD-2 entry (P0)~~ (drafted 2026-10-14). G-ADR-B and G-ADR-C approved conditionally. **Open:** G-ADR-A (after O-3), the ADR-A-T operations confirmation, G-AUD2.
4. **P1 starts only when all of these hold:** O-3 reviewed · G-ADR-A approved · G-ISO authorised for P1 (the 2026-10-14 authorisation covers P5 only) · the Principal's explicit P1 authorisation. **Before G-ISO:** the test-database safety review (PBDIGIT-283) is re-confirmed.

**Traceability:** PBDIGIT-286 · R1–R5 + R1-P · Human Arbiter decisions 2026-10-14 · decision record r3 · HEAD `0d7316dfd`.
