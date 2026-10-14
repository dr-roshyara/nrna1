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
| **G-ISO** | tests on `pbdigit274_isolated_test` with the identity guard | the Principal's authorization | every isolated-database step |
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
| **P1 Freeze** | G-ADR-A, G-ISO | **State:** a direct model save of a System B field on an existing election throws; value unchanged | model guard; dedicated conditional-update method | **Concurrency:** two-connection, both orders (opening first → 0 rows; settings first → the stored value is the committed one). **Write paths:** fitness test. **T-1 (H6):** after opening, saving other settings with unchanged System B values succeeds. **T-2 (H7):** `settings_version` +1 exactly. System A lock unchanged. | isolated + database-free | runs |
| **P2 Configuration** | P1, G-ADR-A, G-ISO | an active chief sets N = 12 before opening; persisted | request rules (N integer 1–50; strategy not offered; `none` refused); one transaction; `settings_changed` includes the fields; separate Vue panel with its own freeze indicator and the D1-UI explanations (design-check) | commissioner/org admin/voter → 403; N = 0 and 51 refused; ReadyForVoting editable; System A fields still locked from `isLocked()` | isolated | runs + `npm run design-check` |
| **P3 Capture** | P1, G-ADR-B, G-MIG, G-ISO | `open_voting` creates exactly one frozen record whose hash matches the canonical format | migration (additive); immutable model; **T-5 (H4)** shared hash builder; capture by the 1-row winner in both opening paths; `enforceVotingLock` transactional and conditional | capture failure → voting stays closed (both paths); manual + scheduled together → one record; update/delete refused; **T-4 (H2, F1):** `inVotingState()` writes a marked test-only record, its own RED → GREEN, **before P4**; **T-6 (AUD-1):** after-commit freeze audit + reconciliation command | isolated + database-free (hash) | runs; full isolated suite green after T-4 |
| **P4 Policy source** | P3, G-ADR-B, G-ISO | an opened real election with a record whose N differs from the live column is evaluated with the record's N | **T-3 (H1):** evaluator source rule (opened real → record; before opening and demo → live) | missing record → refusal + forensic event + alert; hash mismatch → same; pre-opening real and demo assessments don't fail | isolated | runs |
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

## 9. Task checklist
- [x] G-PLAN (2026-10-14, at `914b773aa`)
- [ ] O-3 (operations)
- [x] G-ADR-B (approved conditionally 2026-10-14) · [x] G-ADR-C (approved conditionally 2026-10-14)
- [ ] G-ADR-A (blocked until O-3) · [ ] ADR-A-T operations confirmation · [ ] G-AUD2 (§9 wording pending)
- [ ] G-MIG (migration + backfill design)
- [ ] G-ISO; G-AUTH-T (optional)
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
4. **P1 starts only when all of these hold:** O-3 reviewed · G-ADR-A approved · G-ISO authorised · the Principal's explicit P1 authorisation. **Before G-ISO:** the test-database safety review (PBDIGIT-283) is re-confirmed.

**Traceability:** PBDIGIT-286 · R1–R5 + R1-P · Human Arbiter decisions 2026-10-14 · decision record r3 · HEAD `0d7316dfd`.
