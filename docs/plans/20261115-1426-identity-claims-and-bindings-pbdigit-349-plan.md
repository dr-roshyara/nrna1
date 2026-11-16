# PBDIGIT-349: Identity claims and bindings. Readiness assessment and implementation plan (EP-03 and EP-01)

| | |
|---|---|
| **Status** | **APPROVED for steps I-1 … I-4 by the Principal, 2026-11-15** (development and test only; strict TDD; each step on a separate branch for the Principal to merge). **Q-1 = authorise I-1 … I-4; Q-2 = L1 (a stored reference table); Q-3 = the existing `users` row is the Account (transitional).** Steps I-5 … I-8 stay proposed. Q-4 (B-T1 / B-T2) and Q-5 (target database) remain open. *The answers are to be entered in the decision record by the recorder; this plan doesn't assign decision numbers, because a parallel session is appending to the record.*<br>**Superseded in part by D-241 (2026-11-15, R-9 Option B):** the register authorises **I-1 … I-3 only**, for development and test. **I-4 is not authorised**; it needs a separate Principal decision after I-3 is accepted. The text above is kept as the history of the original approval. |
| **Session** | B. Documentation-only worktree `../nrna1-349`, branch `identity/pbdigit-349-readiness`, from `d85fa0abe` |
| **Design (binding)** | `docs/publicdigit/architecture/reviews/2026-11-11-i-tdd-02-identity-claims-and-bindings-tactical-design.md`, revision 12. **Verified on 2026-11-15:** the file's SHA-256 is `2b5f6e92…` (the accepted text `b6d54798…` plus the D-200 status note), the only change D-200 records |
| **Decisions** | D-113, D-148, D-164, D-166, D-167, D-178, D-181, D-193 (X-2, X-3), D-194, D-195, D-196 (A1 `manage-claims`), D-197 (no lock, throttling only), D-198 (B2), D-199 (14 days, maximum 30), **D-200 (conditions)**, D-202, D-203, D-208 (Governance completion; X-3 closed at design level), D-225, D-226 |

## 1. Objective (business language)

**The flow:**
1. A person registers once on PublicDigit.
2. An organisation that has recorded the person gives them a **claim code**.
3. Redeeming the code **binds** the person's login to the organisation's record (`PersonRef`).

**What a binding does and does not do:**
- **It grants no membership, office, vote, authority or access.** The organisation's own contexts decide those.
- **Platform checks the current binding** whenever someone enters an organisation.

**Today nobody can enter a registry (ledger) organisation:** `NoIdentityBindingsYet` answers "not bound" for everyone (`app/Contexts/Platform/Infrastructure/NoIdentityBindingsYet.php`). This story replaces it. That unblocks frontend M2 (organisation entry), the member's own pages and the installable app.

## 2. Readiness verdict: **PARTIALLY READY**

- **Ready to plan and to build test-first** (development and test only): the Identity core (references, claims, redemption, bindings, R-1, R-2) and Platform's adapter.
- **Not ready to release.** Three structural facts limit what is usable in production:
  1. **Ordinary claims have no producer yet.**
     - An ordinary claim needs a local entry E in the organisation's people register (Organisation slice S4, not started).
     - It also needs a Governance `manage-claims` answer (the Governance slice, not started; Membership's AuthorityCheck port is unbound in production, so S2 is fail-closed).
     - **So ordinary claims are refused in production by construction**, exactly as Membership acts are today.
  2. **The bootstrap claim is the only production route to a first binding.** It is gated by **B-T1 / B-T2**: per D-200, either the risk is accepted for the organisation being set up, or setup waits. That gate depends on the open D-7.
  3. **Legal questions** (retention; `PersonRef` classification; resignation effective time) **block release, not building.**

## 3. Dependency and gate matrix

**Status vocabulary:**
- PROVEN: complete, with evidence;
- IMPL: implemented, not proven;
- DESIGN: accepted at design level only;
- OPEN: an open decision;
- BLOCKED: waiting on another context;
- NOT-INV: not investigated here.

| Dependency / gate | Accepted requirement | Repository evidence (2026-11-15) | Status | Consequence |
|---|---|---|---|---|
| D-194 `end-membership` act kinds | act kinds `record-resignation`, `exclude-member`, `record-death` in Governance | defined by the D-208 addition note; Membership has `ResignMembershipHandler` and `RecordDeathHandler` behind its own `AuthorityCheckPort` (S2-3, commit `cc731ec8d`); **no production Governance producer** | DESIGN (Governance); IMPL (Membership's side) | no effect on Identity's build. Self-service resignation needs R-1 (this story) |
| Governance X-3 | decision-log contract (GD-11, GD-12), per-part evidence | D-208 (3): closed at design level, still a build gate; Governance completion GC-1 … GC-17 not executed | DESIGN | none for Identity; **`manage-claims` answers need the Governance slice** |
| `manage-claims` (D-196) | the answer passed by value; Identity checks correspondence (§10.1) | ratified in the thin slice (D-200 (3)); **no implementation** | DESIGN, BLOCKED (Governance slice) | **ordinary claims refused in production until Governance exists** (fail closed) |
| P-I2 `PersonRef` issuance (INV-REF-2) | idempotent on (O, E), stored (L1) or derived (L2) | not implemented. Only the G-0 tables `identity_persons`, `identity_account_persons` and context references (D-226) | OPEN (tactical, Identity) | **decision Q-2** (§6) |
| D-7 (I-TDD-02 §19): initial contacts' Account references | Platform obtains them without e-mail matching | no Platform design resolves it (found only in the I-TDD-02 revisions and the status record `:290`) | OPEN | D-179 notices to initial contacts unavailable; detection of a wrong registrar relies on people outside PublicDigit (§9.5) |
| B-T1 / B-T2 | after D-7; until then a bootstrap claim only if the risk is explicitly accepted for that organisation | no decision; nothing implemented | OPEN | **bootstrap claims refused in production unless a recorded per-organisation risk acceptance exists** (step I-6 builds that record) |
| Legal: retention | audit and ended claim rows | [LQ] | OPEN | blocks the cleanup job's retention value; the test uses an injected period |
| Legal: `PersonRef` classification | pseudonymous personal data? | [LQ] | OPEN | blocks release |
| Legal: resignation effective time | D-194 | [LQ] | OPEN | Membership's concern; no Identity effect |
| PI-1 … PI-16 | see §20 of the design | none executed | NOT EXECUTED | mapped in §5 |
| PI-17a | correspondence tests | none | NOT EXECUTED | step I-2 |
| PI-17b | security assessment (integrity beyond the process boundary) | none | NOT EXECUTED | a **finding**, written separately before release; never a pass |
| PI-18 … PI-48 | concurrency, expiry, bootstrap and marker tests | none | NOT EXECUTED | mapped in §5 |
| Target database | gates must pass "on the target database" | the isolated PostgreSQL 15 cluster exists (`scripts/testing/membership-pg.sh`); the production PostgreSQL version isn't recorded here | NOT-INV | **Q-5:** confirm the production major version before release |
| Identity and Platform guards | Hexagonal ratchet A-1 … A-5; Platform isolation ISO-1 … ISO-10 | `tests/Architecture/ArchitectureRatchetTest.php` (passes, run on 2026-11-15 for M1) | PROVEN (as guards) | new code must not raise the baseline |
| E-1 and the `IdentityBindings` port | `isBound(accountId, organisation)`; production binding `NoIdentityBindingsYet` | `app/Contexts/Platform/Application/Ports/IdentityBindings.php`; `SelectOrganisationController`; PBDIGIT-345 T-1 and T-15 use a test double | IMPL (Platform); the Identity adapter is missing | step I-4; re-run T-1 and T-15 with the real adapter |
| Account model | design §5 Account / PendingRegistration / RegistrationCompletion; §18 "existing `users` → one Account + one Person; migration ADR later" | today **an Account is the legacy `users` row** (`PostgresAccountDirectory`, `LaravelCurrentAccount`), with the G-0 lazy Person (D-226 Q-G-2, a transitional mapping) | OPEN (no ADR) | **decision Q-3** (§6) |

## 4. Proposed implementation steps (each one: RED → GREEN → mutants → guide → one commit `(PBDIGIT-349)`)

**Common to every step:**
- the central Identity database only; no step spans an organisation database (§14);
- consumer-owned ports;
- the isolated PostgreSQL cluster for every lock and expiry test;
- a guide in `developer_guide/identity/` (next number after the G-0 guide);
- administrator notes only for migrations.

| Step | Business purpose | Design | Model | Ports and adapters | Persistence | Main tests (PI) |
|---|---|---|---|---|---|---|
| **I-1 References** | an organisation's recorded person gets one opaque `PersonRef` | §4 IssuedReference, §12 C-I1 | `IssuedReference` (R, O, issuedAt); INV-REF-1 and -2 | `IssueReference(O, E)` use case, called in-process by the organisation context (ISO-1) | central `identity_issued_references`, unique (O, E) and unique R (**L1, if Q-2 = L1**) | idempotent re-issue; one O per R; never derived from e-mail |
| **I-2 Ordinary claims** | an empowered officer invites a recorded person to bind | §5, §6.1–6.3, §10.1, D-196, D-197, D-199 | `Claim`, `ClaimSlot`, `ConsumedAuthorityRequest`; window 14 days default, maximum 30 (capped and recorded) | `IssueClaim`, `ReplaceClaim`, `RevokeClaim`; consumer-owned `AuthorityEvidence` value (six fields + basis); `SessionActorRef` from R-1 | claims (code hash only), slots, consumed requests; **no Governance answer content** | PI-7, 12, 15 (issue and revoke half), 17a, 18, 20, 21, 23, 24, 27, 32, 35; **production: no Governance producer, so refused** |
| **I-3 Redemption and binding (signed in)** | the person binds their login by entering the code | §6.2 redemption row, §14 "redeem while signed in", §12 C-I3 and C-I6, INV-ID-6/9, INV-BND-1/2/3 | `Binding`; the shared redemption service (the only path) | `RedeemClaim` (signed in); outbox `ClaimRedeemed` and `BindingRefused` → the organisation's inbox; throttling per code and per client | bindings, unique (O, R) and (O, PersonId); outbox rows | PI-1, 2, 3, 8, 9, 13, 15 (redeem half), 22, 28, 29, 34, 36, 44, 48 |
| **I-4 R-1 and organisation entry** | a signed-in person acts as themselves inside an organisation | §7, D-178 L-10, ISO-3 | — | `ActingRef(session, O) → R \| NotBound \| Ambiguous \| Unavailable`; **Identity's adapter for Platform's `IdentityBindings`**, replacing `NoIdentityBindingsYet` in `PlatformServiceProvider` (a Platform file: a one-line binding change); the organisation-picker query (own bindings only) | — | PI-4; **PBDIGIT-345 T-1 and T-15 re-run with the real adapter**; never the legacy user-organisation tables (D-160) |
| **I-5 R-2 for Platform** | Platform can approve contacts by their bound Account | §8, D-193 X-2 | — | `BoundAccountFor(O, R, platform-contact)`; Platform-only service identity | — | PI-5; **X-2 proven** |
| **I-6 Bootstrap claim** | the founding registrar becomes the first bound person | §9, §6.2 bootstrap rows, INV-CLM-2/4/5/6, D-198, D-199 (14 days) | `BootstrapSlot` with the permanent `redeemedAt` marker | `IssueBootstrapClaim(O, R, foundingDecisionRef, bootstrapOpId)`, provisioning only, in `provisioning`; **a recorded per-organisation B-T1 risk acceptance is required, else refused** (D-200 (4)) | the slot with its marker; claim kind `bootstrap` | PI-6, 19, 25, 26, 30, 31, 33, 37, 38, 39, 40, 41, 42, 43, 45, 46, 47 |
| **I-7 Claim-first registration** | a person without a login registers with the code in hand | §14 registration rows, cases 1–6 | `PendingRegistration`, `RegistrationCompletion` (24 hours) | joins the existing registration flow (**legacy auth; depends on Q-3**) | pending registrations; completions with a 24-hour cleanup | PI-10, 11, 14, 42 (the registration path) |
| **I-8 Release evidence** | — | §9.4, §10.1 | — | — | — | **PI-16** (the founding-document upload path: Platform's) and **PI-17b** (a written finding) |

**Order and use:**
- **I-1 → I-2 → I-3 → I-4 is the minimum** that lets a test-bound person enter an organisation in development (M2 can then be built against it).
- **I-6 is the first production-usable route,** subject to B-T1.
- I-5 and I-7 can follow in either order.

## 5. PI-gate traceability (planned; no gate is passed by this plan)

| Gates | Step | Kind of evidence | Status |
|---|---|---|---|
| PI-1, 2, 3, 9, 13, 22, 28, 29, 34, 36, 44, 48 | I-3 | concurrency and expiry tests on the isolated cluster | NOT EXECUTED |
| PI-4 | I-4 | R-1 tests; T-1 and T-15 re-run | NOT EXECUTED |
| PI-5 | I-5 | R-2 tests | NOT EXECUTED |
| PI-6, 19, 25, 26, 30, 31, 33, 37–43, 45–47 | I-6 | bootstrap, lock-order and marker tests, including statement-order inspection | NOT EXECUTED |
| PI-7, 12, 17a, 18, 20, 21, 23, 24, 27, 32, 35 | I-2 | correspondence, idempotency, slot and expiry tests | NOT EXECUTED |
| PI-8 | I-2, I-3 | neutral errors; a log scan for codes and addresses | NOT EXECUTED |
| PI-10, 11, 14 | I-7 | registration failure injection; 24-hour completion | NOT EXECUTED |
| PI-15 | I-2 and I-3 | replace or revoke vs redeem, both orders | NOT EXECUTED |
| PI-16 | I-8 (Platform) | an upload-path inspection with evidence | NOT EXECUTED |
| PI-17b | I-8 | a written security finding | NOT DONE |

## 6. Decisions for the Principal (plain language)

| # | Question | Options and consequences | Recommendation | Blocks |
|---|---|---|---|---|
| **Q-1** | **Authorise implementation** of steps I-1 … I-4 (development and test only)? | **yes:** the core is built and proven; nothing changes in production, because ordinary claims stay refused without Governance. **no:** organisation entry stays impossible | **yes, I-1 … I-4 now**; I-5 … I-8 after review | implementation |
| **Q-2** | **How a `PersonRef` is issued (P-I2)** | **L1, stored:** a central table (O, E) → R, simple and easy to audit, with uniqueness enforced by the database. **L2, derived:** R computed from (O, E) with a secret key, nothing stored, but key custody becomes critical and a key change breaks every reference | **L1** | I-1 |
| **Q-3** | **What an "Account" is in release 1** | **(a)** the existing `users` row is the Account, as G-0 already treats it, behind Identity's adapters; no data migration; claim-first registration (I-7) joins the existing sign-up. **(b)** a new Identity Account store now, which needs a migration ADR and touches every login | **(a)**, recorded as transitional (as D-226 Q-G-2) | I-3, I-7 |
| **Q-4** | **B-T1 / B-T2 (the registrar-address risk)** | **decide later, after D-7** (D-200 says so). Until then I-6 refuses any bootstrap claim without a recorded per-organisation risk acceptance | **don't decide now**; build I-6 with the acceptance record | release of bootstrap only |
| **Q-5** | **Target database version** for the gates | confirm production's PostgreSQL major version, or run the gates on 15 (the current cluster) and repeat them on the target before release | **confirm before release** | release |

The legal questions (retention, `PersonRef` classification, resignation time) go to counsel and block release only.

## 7. Risks and release blockers

- **Production stays unusable for ordinary members** until Organisation S4 (the register) and the Governance slice exist. This story enables the mechanism, not the members.
- **The bootstrap route depends on accepting the address risk,** and wrong-registrar detection is weak until D-7.
- **Identity becomes security-critical,** so PI-17b must be done before release.
- **The legacy registration flow (Q-3 (a))** is reused. Its known defects (PBDIGIT-341, accounts resolved by matching e-mail) must never be used for binding, and the design forbids it (INV-ID-3).

## 7a. Progress

- 2026-11-15: **I-1 DONE.**
  - **Built:** `OrganisationPersonRef`, `LocalEntryRef`, `IssueReference`, the port `IssuedReferences`, `PostgresIssuedReferences`, and the central migration `identity_issued_references` (with an immutability trigger).
  - **Tests:** RED, then GREEN; Identity unit 12 and Identity PostgreSQL 16 passing.
  - **Mutants:** K and L killed; J equivalent (the database enforces INV-REF-2), recorded.
  - **Checks:** the architecture guards (42) and the full isolated cluster (469) pass.
  - Guide `developer_guide/identity/02_…`.
- 2026-11-15: **I-2 DONE.**
  - **Built:** the issue, replace and revoke use cases; the `ClaimCode` and `ClaimWindow` values (D-199); `AuthorityEvidence` with its §10.1 check; `PostgresClaims` (the `ClaimSlot` → claim-row lock order, one `clock_timestamp()` sample after the locks, idempotency after the lock, single-use authority requests, content-free audit).
  - **Migration:** claims, slots, consumed requests, bindings (created for the unbound check; written by I-3; immutable), audit.
  - **The R-1 seam:** `SessionActorRefs`. **Production `NoActingRefYet` refuses** until I-4.
  - **Tests:** unit 17; Identity PostgreSQL 33 (17 new, including forked races PI-18 ×2, PI-35, PI-23); architecture guards 52; full isolated cluster 486 (run before a port-method rename that only touched Identity, after which the Identity suites and guards were rerun).
  - **Mutants:** 8, all killed. M2 needed a new test, because PI-18 on a first issuance is also serialised by the slot insert's unique-key wait.
  - **Deviations recorded:**
    - **TDD order:** the value classes were written before the RED run, so the unit rules test was green at RED.
    - **Guard:** the purity guard flagged a port method named `now()`; it was renamed `currentInstant()`.
  - Guide `developer_guide/identity/03_…`.
  - **PI gates proven for I-2:** 7, 12, 17a, 18, 20, 21, 23, 24, 27, 32 (by the lock design plus 23 and 27), 35. **PI-15 is half done:** the replace and revoke half here; redemption in I-3.
- 2026-11-15: **I-3 IMPLEMENTED, NOT ACCEPTED** (worktree `../nrna1-349-i3`, branch `identity/pbdigit-349-i3`, commit `fcaba62ca`; unmerged). *Corrected wording: this entry first said "DONE"; the review found claims that went beyond the evidence (see the corrections entry below).*
  - **Built:** `RedeemClaim`, the one redemption path `PostgresClaims::redeem` (`ClaimSlot` → claim row → one `t_check`), the binding, `ClaimRedeemed` / `BindingRefused` with hydrators, and throttling per code and per client (`config/identity.php`; values [TU], proposals).
  - **Two schema findings, fixed:**
    1. I-2's foreign keys to claim rows would have blocked the §15 cleanup. They were dropped.
    2. The shared `outbox_events` has a foreign key to the legacy `organisations` table, so it can't carry registry organisations. `identity_outbox_events` was added: "Identity's outbox" (multi-database design §13.3).
  - **Tests:** strictly RED first (11 tests). GREEN: Identity PostgreSQL 44, unit 17, guards 52, the event-registry guard in the isolated cluster 2, full cluster 504.
  - **Mutants:** N1, N3, N4 and N6 were applied **together in one run**, which failed. That does not show each one is killed on its own (corrected by review R-7; individual runs are in the corrections entry).
  - **Test corrections after RED, recorded:** the outbox table name; `jsonb` key order.
  - **PI gates with evidence for I-3 (corrected wording, R-7):** 1, 2, 3, 8, 9, 13, 22, 28, 44; **PI-15** only for the orders tested (redeem-then-revoke; after the corrections also replace-first and redeem-then-replace), not every replacement/revocation interleaving; **PI-34** one forked interleaving only, **no load test**; **PI-48** for **ordinary claims only** (bootstrap claims are I-6).
  - **Open:**
    - the relay of Identity's outbox to organisation inboxes (needs Organisation S4's consumer);
    - confirmation of the throttling values;
    - PI-29 (issuance first, then the waiting redemption of the expired old code) is covered by the expiry logic but has no dedicated race test. *(Closed by the corrections below.)*
  - Guide `developer_guide/identity/04_…`.
- 2026-11-15: **I-3 review corrections: IMPLEMENTED, AWAITING PRINCIPAL REVIEW** (same worktree and branch; not merged, not pushed; I-3 remains **not accepted**).
  - **R-1:** a racing INV-BND-2 conflict (unique key `identity_bindings_organisation_id_person_id_key`) now ends as a reported `PersonAlreadyBound` (BindingRefused + audit). The whole transaction is rolled back and decided **once more** in a fresh transaction. Only that constraint is retried, and only once; anything else stays `IdentityUnavailable`.
  - **R-2:** client keys are stored only as HMAC-SHA-256 under a server secret. Expired windows are deleted on every recorded failure, and the code key must be a SHA-256 hash. No secret of at least 16 characters → every redemption is refused as unavailable (fail closed).
  - **R-3:** migration `2026_11_15_630000` is **forward-only**: `down()` throws and changes nothing.
  - **R-4:** the database-free guard `IdentityBindingSingleWriterTest` has four rules and planted fixtures, and its limits are stated.
  - **R-6 (Principal):** `PersonAlreadyBound` counts as a failed attempt.
  - **F-3:** `pg_constraint` proof.
  - **PI-29:** dedicated race test, plus two PI-15 order tests.
  - **R-7 / R-8:** wording, comments and the `BindingRefused` aggregate (now the claim, as for `ClaimRedeemed`).
  - **R-9:** a proposed decision entry only, not inserted. *Update 2026-11-15: the Principal approved Option B; recorded as **D-241** in the Identity worktree's register copy (see the R-9 entry below).*
  - **TDD deviations (recorded, not strict test-first):**
    - **R-4:** the new single-writer guard was **green on its first run**, so there was no genuine initial RED. Its ability to fail was shown only afterwards, by mutant M-R4 (evidence `01b`, `03-mutant-R4`).
    - **R-8:** the `BindingRefused` aggregate change was made **before** its test. RED was shown afterwards by restoring the old argument (evidence `01e`).
  - **Tests:** Identity unit 17; RedeemClaimTest 20; guard 2; event-registry guard 2; full isolated cluster **513 tests, 17,980 assertions, exit 0** (evidence `07`).
    - *Correction 4:* this line first said 17,958 assertions. That was an **earlier** full run; **17,980** is the **final** full isolated run, after the review-driven assertions.
    - The read-only audit of 2026-11-15 reran **targeted Identity suites only** (unit 17, guard 2, RedeemClaimTest 20). It did **not** rerun the full isolated cluster.
    - *Correction 5 (2026-11-16; supersedes the "final 17,980" wording above):* **513 tests passed with zero failures and zero skipped tests** in every full run. **The assertion count varies between runs.**
      - Six observed runs gave 17,953; 17,958; 17,980; 17,937; 17,920; 17,947.
      - **Cause, established:** two runs with per-test JUnit logs differ in exactly one test, `PostgresClosedThroughRaceTest::test_a_reported_closed_through_is_never_contradicted_by_a_later_commit` (Membership, PBDIGIT-343; 280 vs 307 assertions).
      - It is a concurrency stress test: its readers loop with random sleeps and assert once per reported boundary, so the count depends on process scheduling, by design. It keeps guards that fail if nothing really interleaved.
      - **All 65 Identity tests made identical assertion counts** in both runs, and no test made zero assertions. Evidence: `11-assertion-count-variation-2026-11-16.txt` and `12-erratum-assertion-count-2026-11-16.md`.
  - **Mutants, each run on its own:** M-R1, M-R2a, M-R2b, M-R3, M-R4, M-R6, N1, N3 and M-R8 were all killed. N4 and N6 were **not** re-run individually.
  - Evidence: `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i3-corrections/`.
  - **Unratified:** the throttle values 5 / 10 / 900 s ([TU]).
- 2026-11-15: **R-9 decided: Option B, recorded as D-241** (development and test only).
  - **Authorised:** I-1, I-2 and I-3. **I-4 is NOT authorised**; it needs a separate Principal decision after I-3 is accepted.
  - **The record says openly** that I-1 and I-2 were merged before the entry existed. It is not backdated.
  - **Where it is recorded:** in the Identity worktree's copy of the register, which was first brought level with the canonical copy (D-240, Membership Q-5, byte-identical). It enters the canonical register only when this branch is committed and merged, so the number must be re-verified then.
  - **Documentation:** R-9 erratum file added to the evidence (the original proposal unchanged; manifest now 26 files); guide 04 explains how the R-1 conflict is recognised (message text).
  - **Still open:** the I-3 commit (withheld), I-3 acceptance, the merge, and I-4.

## 8. Next actions

*(Updated 2026-11-16. The original actions, "the Principal answers Q-1, Q-2 and Q-3" and "on approval: step I-1 …", are done: Q-1 … Q-3 answered on 2026-11-15, I-1 and I-2 merged.)*

1. **Principal: the I-3 commit decision.** It is a separate decision and is currently withheld.
2. **Then, as separate decisions:** I-3 acceptance, and I-3 merge.
3. **At merge:** re-verify D-241's number against the canonical register and enter D-241 there, adding its canonical recording date. Its effect runs from the Principal's approval of 2026-11-15 (clause (8), Principal's choice "I").
4. **I-4 only after a separate Principal decision following I-3 acceptance** (D-241 (2)). I-5 … I-8 stay proposed; Q-4 (B-T1 / B-T2) and Q-5 (target database) remain open.

## Traceability
EP-03 readiness and EP-01 plan for PBDIGIT-349 · design revision 12 (D-200) · supersedes nothing.
