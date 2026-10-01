# Constitutional Governance Runtime
## Migration Readiness Review

| | |
|---|---|
| **Date** | 2026-09-30 |
| **Kind** | ⚠ authority: generated. A design baseline and engineering evidence. **It is not a decision and not an authorization.** Nothing in `app/`, `tests/`, `config/`, `routes/` or `database/` was changed. |
| **Builds on** | `2026-09-30-constitutional-runtime-reality-audit.md` (`4afd43481`), treated as evidence and re-verified where load-bearing. **One correction to it is recorded in §11.4.** |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared). Three read-only subagents gathered middleware, controller and test-triage facts. They share this session's identity, so this is **not** independent review. Every load-bearing subagent claim cited below was re-checked from source. |
| **Git at start** | `knowelegeos-modelling` @ `4afd43481`. Uncommitted work belonging to other sessions (UC-4 decision record, a KnowledgeOS corpus file, 4 untracked `docs/publicdigit` files) was left untouched. |
| **Labels** | **[RUN]** executed · **[STATIC]** read from code; consequences deduced, not executed · **[PROBE]** throw-away probe test in the session scratchpad, against `nrna_test` |

---

## 1. Current Reality

The first audit stands on every load-bearing point. The additions below come from this review.

**Participation is decided in at least 17 places, and no single owner exists.**

- **Membership is checked in 5 places, using 4 different predicates.**
  - `EnsureElectionVoter` uses `User::isVoterInElection` (cached, org-matched).
  - `EnsuresVoterMembership` has two paths. The fresh path (`:36-40`) **omits the org predicate** and does not lift the tenant scope.
  - `ElectionVotingController::show/start` accepts `status !== 'removed'`, so **inactive and suspended memberships pass**.
  - `TrustPolicyEvaluator::buildEligibilityEvidence` also checks expiry and `suspension_status`. It is observational only.
  - `ElectionMembership::scopeEligible` exists, but no gate uses it.
- **Lifecycle (`ElectionLifecycle::canVote()`) is used as a gate in 4+ places:** `EnsureVotingActive`, `VoteEligibility`, `VoteController::create/store` and `ElectionVotingController::start`.
  - The lifecycle engine itself is a sound fact-derivation. `VotingActive` requires `voting_locked`, an open window and at least one approved candidate (`ElectionLifecycleEngineImpl.php:111`).
- **There are FOUR per-IP limits, not three.**

  | Source | Counter | Comparison |
  |---|---|---|
  | `CodeController:39,896-905`: `config('app.max_use_clientIP') ?? 7` | `codes.client_ip` | `>=` |
  | `VoteController::vote_post_check`: the same config | `codes.client_ip` | — |
  | `ElectionVotingController::evaluateIpCount`: `elections.ip_restriction_max_per_ip` (admin-editable) | `voter_slugs.step_1_ip` | `>=` |
  | `NetworkBindingPolicy`: `max_votes_per_ip ?? 6` halved by trust level | `codes.client_ip` | `>` 3 |

- **Code state is used as authority.**
  - `codes.can_vote_now` is set by `CodeController::markCodeAsVerified` and read as a grant by `VoteController::isUserEligibleToVote`, `verify_first_submission` and `CodeController::showAgreement`.
  - `voter_slugs.can_vote_now` is also set by `CodeController::create` and `ElectionVotingController::start`.
- **"Already voted" is recorded in 3 places:** `codes.has_voted`, `voter_slugs.has_voted`, `election_memberships.has_voted`. The last is read by `ElectionVotingController`, but no production writer was found [STATIC].

**Defects in the live voting path** (all [STATIC] and confirmed from source)

| # | Defect | Evidence |
|---|---|---|
| X-1 | `store` computes `$pre_check = vote_post_check(...)` and **never reads it**. The IP-count and has-voted post-checks are dead in real elections, while demo enforces them. | `VoteController:1726` (single occurrence) |
| X-2 | `first_submission` sets `vote_submitted = true` **before** `vote_pre_check`, which then returns `""` at once. The time-window, `can_vote_now` and code checks never run on that path. | `:538-545`, `:2944-2947` |
| X-3 | The IP denial inside `store` returns **without `$lock->release()` or `DB::rollBack()`**. The open transaction and the 10 s lock leak. | `:1640-1673` |
| X-4 | Voting codes are compared in plaintext, and both the **expected and submitted codes are logged**. | `verify_submitted_code` `:4087-4150`; `CodeController:881-885` |
| X-5 | `debug_code` sends the plaintext voting code to the UI **whenever the user has no valid email**, in any environment. | `:2426`, `:2442` |
| X-6 | A session flag `selected_election_type === 'demo'` bypasses `VoteEligibility` (lifecycle) and `CodeController::isUserEligible` on **real** routes. The flag is set in 4 places and cleared in 1. | `VoteEligibility:36`; `CodeController:683-700` |
| X-7 | `EnsureRealVoteOrganisation::handleOrganisationMismatch` is defined but never called. The middleware performs no organisation check. | `:109` (definition only) |
| X-8 | Dual-code mode: `config('voting.two_codes_system')` is a string, compared with `=== 1` / `=== true`. | first audit §6 [RUN `Env::get`] |
| X-9 | `User::isEligibleToVote/is_voter` reads a column **no migration creates**. It returns null, so it is always false. The `where('is_voter')` scope would throw an SQL error. | `User.php:111,415,433,463`; no migration |
| X-10 | Whitelist `json_decode(array)` raises a `TypeError`. `trackSovereigntyDivergence(?int $userId)` receives UUIDs. | first audit §6, §10 |
| X-11 | The per-election security law **cannot be authored**: `network_binding_strategy`, `max_votes_per_ip`, `device_binding_strategy`, `ballot_authorization_protocol` and `trust_overlay_active` are neither fillable nor written anywhere in `app/`. Every production election therefore freezes the **DB defaults**. | `Election.php:110-190` (0 matches); mutation-path scan |

---

## 2. Target Architecture

```
Frozen ElectionConstitution (one representation, one hash)
        │
Evidence acquisition (facts only; provenance recorded)
        │
Evaluators (pure: constitution + evidence → Finding{passed, basis, facts})   ← never a decision
        │  complete set, all evaluated
ElectionCapabilityResolver
   └─ registered CapabilityPolicy set for action CAST_VOTE  ← the only producers of CapabilityDecision
        │
CapabilityDecision + full trace ──► persistence of the decision record (replay)
        │
Controller acts on the decision; UI projects it
```

**The target is coherent with the existing domain model.** It needs no new architectural subsystem. The repository already contains:
- the combinator (`ElectionCapabilityResolver`);
- the decision type (`CapabilityDecision` with private constructor and static factories);
- a policy interface (`CapabilityPolicy`);
- two resolver-ready voting policies (`BallotAuthorizationPolicy`, `VoterVerificationPolicy`);
- a typed evidence model and pure evaluators (`Security/Simplified`).

The migration mostly **connects and consolidates** existing parts.

---

## 3. Confirmed Sovereignty Decision

The intended invariant is **not reopened.** One concrete clarification is required, because the repository's existing resolver design forces it:

> **The sovereign boundary is "`ElectionCapabilityResolver` together with the `CapabilityPolicy` implementations registered in it".**

- `CapabilityPolicy::evaluate()` returns a `CapabilityDecision`. The resolver combines those decisions deny-first (`ElectionCapabilityResolver.php:28-74`). So in the existing abstraction the policies *are* the interpretation rules, and the resolver is their combinator.
- Findings-producing **evaluators** (`Simplified/Policies/*`) sit outside that boundary and must never return a `CapabilityDecision`.
- This is consistent with the target. It becomes a *contradiction* only if the invariant is read as "the resolver class alone contains all interpretation". It must not be read that way.

Two properties of the current resolver must change to meet the target. Neither needs a new abstraction.

| Property | Now | Required | Why |
|---|---|---|---|
| Evaluation completeness | Stops at the first deny (`:45-63`). `isShortCircuit()` is true for **every** HardBlock deny, which is the default (`CapabilityDecision.php:68-71`). | Evaluate every policy, and produce the decision from the complete set. | Replay needs the full trace. Otherwise the reported reason depends on layer order: the *same topology* that is a defect in `PolicySequence`. |
| Statelessness | `private ?CapabilityTrace $lastTrace` on a **singleton** (`:22`, `AppServiceProvider:284`) | Return the trace with the decision | A mutable sovereign shares state across evaluations |

`ConstitutionalLegitimacyDecision` then becomes redundant. This is **not** a second sovereignty question: it is the class the migration replaces.

---

## 4. Voting Domain Map

| Concept | Current class / file | Current role | Intended role | Migration required |
|---|---|---|---|---|
| Voter identity | `auth()` user; `VerifyVoterSlug` ownership check | TRANSPORT | TRANSPORT | none |
| Voting link (slug) | `VoterSlug`; vslug binding `RouteServiceProvider:72-129` | TRANSPORT (+ abuse throttle) | TRANSPORT | none |
| Organisation / tenant | `VerifyVoterSlugConsistency:57-82`; `VoteController::getElection:120-135`; `CodeController::getElection` | TRANSPORT, with **3 different "platform org" rules** (`=== 1` vs config vs slug) | TRANSPORT (one rule) | unify (human decision H-6) |
| Membership | 5 sites, 4 predicates (§1) | AUTHORITY, inline | EVIDENCE (`ParticipationEligibilityEvidence`) → one resolver policy | consolidate |
| Lifecycle state | `ElectionLifecycleEngineImpl` | FACT derivation, used as AUTHORITY in 4+ gates | EVIDENCE → the existing `LifecycleCapabilityBaselinePolicy` | wire it; delete the gates |
| Voting window | lifecycle engine (`voting_starts_at/ends_at`); `ValidateVoterSlugWindow` end_date; code `voting_time_in_minutes` (per-code session window) | mixed | election window = lifecycle EVIDENCE; per-code window = BALLOT-PROTOCOL EVIDENCE | separate the two |
| Verification code 1/2 | `CodeController::verifyCode`, `VoteController::verify_submitted_code` | TRANSPORT (credential) + AUTHORITY (`can_vote_now`) | credential check = TRANSPORT/validation; "verified" = EVIDENCE for `BallotAuthorizationPolicy` | reshape `can_vote_now` into a fact |
| Code protocol (single/dual) | `config('voting.two_codes_system')` (global env) | mutable CONFIG | CONSTITUTIONAL LAW (per election) | move to the snapshot |
| Current IP | `request()->ip()`, hashed by `TrustEvidencePrivacyPolicy` | EVIDENCE | EVIDENCE | none |
| Bound/verified IP | `users.voting_ip`, `codes.client_ip`, `voter_slugs.step_1_ip`, `voter_verifications.verified_ip` | 4 competing stores | one EVIDENCE source (H-1) | human decision |
| Votes per IP | 3 counters (§1) | EVIDENCE computed 3 ways | one EVIDENCE counter | consolidate |
| Device fingerprint | request input `device_fingerprint`; registered hash **absent** | EVIDENCE, half-missing | EVIDENCE (current + registered hash) | acquire the registered hash |
| Officer verification | `VoterVerification` (`verified_ip`, `verified_device_fingerprint_hash`, `verified_by/at`) | FACT, captured and **never consumed** | EVIDENCE for `VoterVerificationPolicy` | wire it |
| Already voted | `codes/voter_slugs/election_memberships.has_voted` | AUTHORITY in 6+ branches | EVIDENCE + DB uniqueness invariant | consolidate (H-7) |
| Agreement accepted | `codes.has_agreed_to_vote`, checked only in `create` | validation | EVIDENCE (precondition) | move |
| Trust / trust level | `TrustLevel`, `VerificationAttestationRecord::trustLevel` | INTERPRETATION inside evidence | removed, or an explicit law article (H-3) | redesign |
| Overlays | 7 overlays → `OverlayAggregator` | OBSERVATION (unconsumed) | OBSERVATION recorded in the trace, **not** a CAST_VOTE input (H-8) | decide |
| Security events | `SecurityEventRecorder` → `election_security_events` | audit (sampled) | the decision record (§12) | redesign |
| Capability | `ElectionCapabilityResolver` | AUTHORITY (admin only) | AUTHORITY (all participation) | extend |
| Legitimacy decision | `ConstitutionalLegitimacyDecision` | AUTHORITY (voting) | — | delete |
| Vote persistence | `save_vote`, `markUserAsVoted`, `Vote` (no user_id) | PERSISTENCE | PERSISTENCE (anonymity preserved) | none |
| Ballot selection rules | `validate_candidate_selections` | validation | validation | none |
| Rate limiting | `throttle:10,1`, vslug limiter | SECURITY/ABUSE | SECURITY/ABUSE | none |

---

## 5. Constitutional Law Model

**Current state**

Two representations with **incompatible hashes** [STATIC]:

| Representation | Fields | Hash input |
|---|---|---|
| `Domain/Election/Constitution/ConstitutionalArticlesSnapshot` (persisted) | 7 fields incl. `trust_overlay_priority`; **no `verificationRequired`** | snake_case JSON with `UNESCAPED_SLASHES\|UNICODE` |
| `Domain/Election/Security/Simplified/ElectionConstitutionSnapshot` (evaluator input) | 5 fields incl. `verificationRequired`; no overlay | `ElectionConstitutionHasher`: camelCase JSON with `THROW_ON_ERROR` |

A Simplified hash can **never** equal `elections.constitutional_hash`.

**Required conceptual content** (no implementation). One snapshot and one canonical hash.

| Article | Values | Covers rule |
|---|---|---|
| `verification.required` | bool | 6 |
| `verification.ip_capture` | `none \| at_code_verification \| by_officer` | 1 (H-1) |
| `ballot.protocol` | `single_code \| dual_code` | 2, 3 |
| `ballot.session_minutes` | int | per-code window (today it is config) |
| `network.ip_binding` | `none \| required` | 4 |
| `network.ip_vote_limit` | `none \| int` (**independent** of binding) | 7 |
| `network.whitelist` | list of CIDR, or none | whitelist bypass |
| `device.binding` | `none \| required` | 5 |
| `law_version` | semantic version of the article *schema* | replaces the phase label `'D.2.5'` |

**The network articles must compose.** Today `networkBindingStrategy` holds one value, so `ip_strict` and `ip_count` are mutually exclusive, while historical rules 4 and 7 coexist. `whitelist_only` is named in `NetworkEvidence` but has no evaluator case.

**`trust_overlay_*` should leave the law.** It is an operational overlay, and "priority" is procedural vocabulary (H-8).

**Authoring is a missing capability (X-11).** Law must be set when the election is created, then frozen. Today no path sets it.

---

## 6. Seven Security Rules Mapping

| Rule | Evidence source (target) | Current evaluator | Current authority | Target evaluator | Missing piece |
|---|---|---|---|---|---|
| 1 Snap IP at verification | the IP captured at the step H-1 designates, hashed | none: 4 stores, none designated | none | acquisition (a fact, not an evaluator) | **H-1** which store is canonical |
| 2 Single code | code state (used/unused), from `codes` | `CodeController` / `VoteController` inline, env-driven | controllers | `BallotAuthorizationPolicy` (exists, unwired) | law article; a typed `BallotSession` builder |
| 3 Dual code | code1 used + code2 state + view step | inline; **cannot activate** (X-8) | controllers | `BallotAuthorizationPolicy::evaluateDualCode` | as rule 2, plus fix of X-8 |
| 4 Vote only from the verified IP | current IP hash vs the rule-1 hash | `ValidateVotingIp` (shadow only), `NetworkContinuityObservation` (unconsumed), Simplified `ip_strict` (compares against **session-start IP**) | none effective | Simplified `NetworkBindingPolicy`, comparing against the rule-1 hash | H-1; composable article; registered hash in `NetworkEvidence` |
| 5 Device binding | current fingerprint hash vs registered hash | legacy `DeviceBindingPolicy` fed `ExactMatch` whenever any fingerprint is sent | `PolicySequence` (never fails) | Simplified `DeviceBindingPolicy` | a registered-hash source (officer record, or the first-seen fingerprint: **H-2**); `DeviceEvidence` needs both hashes |
| 6 Officer captures IP + fingerprint | `voter_verifications` row (verified_by/at, ip, fp, not revoked) | legacy `VerificationAttestationPolicy` fed `attested=false`, `required=null→false` | never fires | Simplified `VerificationPolicy` + `VoterVerificationPolicy` | law article `verification.required`; an evidence builder from `VoterVerification`; revocation fact |
| 7 Max votes per IP = N | **one** counter of prior votes from this IP in this election | 4 implementations (§1) | `CodeController`, `ElectionVotingController`, `PolicySequence` | Simplified `NetworkBindingPolicy` `ip_count` | **H-4** one counter source; fix **off-by-one**: `votesFromThisIp <= max` lets a (max+1)th vote through; trust scaling (H-3) |

---

## 7. Middleware Classification

| Middleware | Rejects? | Rejection means "not allowed to vote"? | Class | Before resolver? | Target |
|---|---|---|---|---|---|
| vslug binding (`RouteServiceProvider:72-129`) | 429/404/410 | no (link integrity, brute force) | SECURITY/ABUSE | **keep** | keep |
| `voter.slug.verify` | 404/403 | no (ownership / bearer token) | TRANSPORT | **keep** | keep |
| `voter.slug.window` | 403 | slug expiry: no. **Election `end_date`: yes.** It also **writes** `is_active=false` | TRANSPORT + leak | keep the slug expiry | move the end_date check to lifecycle evidence; delete it here |
| `voter.slug.consistency` | 404/500/redirect | org isolation: no. `vote_completed`: yes | CONTEXT PROVIDER | **keep** the context load and tenant check | `vote_completed` → evidence. `vote_completed` has no writer found (UNKNOWN whether it fires). Unify the platform-org rule |
| `ensure.election.voter` | redirect/403 | **yes** (membership) | FACT PROVIDER that enforces | no | evidence → resolver; delete eventually |
| `voter.step.order` | 403/redirect | no (workflow order) | TRANSPORT | **keep** | keep |
| `vote.eligibility` | redirect | **yes** (lifecycle, legacy eligibility); **session-bypassable** (X-6) | CONSTITUTIONAL AUTHORITY | no | a resolver policy; delete |
| `validate.voting.ip` | only in legacy mode | yes (rule 4) | FACT PROVIDER (shadow by default) | no | evidence; delete |
| `vote.organisation` | redirect | no; does not check organisation (X-7) | CONTEXT PROVIDER (presence) | — | delete (subsumed by consistency) |
| `voting.active` | redirect | **yes** (lifecycle) | CONSTITUTIONAL AUTHORITY (delegating) | no | resolver lifecycle policy; delete |
| `throttle:10,1`, CSRF, session, locale, Inertia | 429/419 | no | SECURITY / TRANSPORT | **keep** | keep |

**Coverage gap:** `code/create` and code store have **no** `voter.slug.window`, `voting.active` or IP middleware. Lifecycle is enforced there only by `VoteEligibility`, which X-6 can bypass. `POST /votes` (`electionRoutes.php:168`) reaches `store` with only `auth, verified, vote.eligibility`, and `getElection` falls back to "first real election" (`VoteController:108-110`).

---

## 8. Controller Classification

| Controller behaviour | Current authority? | Target location | Delete? |
|---|---|---|---|
| `VoteController::store` Trust → `LegitimacyDecision` gate (`:1490-1556`) | **primary** | resolver `CAST_VOTE` | replaced |
| `store` `canVote()` (`:1560`), fresh membership (`:1586`) | yes | resolver policies | yes |
| `store` lock + fresh `has_voted` in lock (`:1640-1666`) | yes | **persistence invariant** (keep: concurrency) plus resolver evidence | keep the lock |
| `store` `validateVotingIpWithResponse` (`:1670-1673`, X-3) | yes | evidence | yes |
| `store` code credential match (`:1685-1715`) | yes | TRANSPORT (credential) | keep, and hash it (X-4) |
| `store` `vote_post_check` result ignored (`:1726`, X-1) | none | — | yes |
| `store` `save_vote`, `markUserAsVoted`, slug `has_voted` | — | persistence | keep |
| `VoteController::create`: `canVote` (`:222`), membership (`:234`), `isUserEligibleToVote` (`can_vote_now`, `:250`), agreement (`:293-302`), IP (`:305`) | yes | resolver (projection of `CAST_VOTE` for the ballot page) | yes, except flow redirects |
| `first_submission`: `has_voted` (`:525`), bypassed `pre_check` (X-2), `verify_first_submission` | yes | resolver + validation | partly |
| `verify`: membership (`:2276`), `vote_post_check` (`:2310`), `second_code_check` (**writes during GET**, `:2330-2360`) | yes | resolver evidence; persistence split out | split |
| `second_submission`, `validateVotingEligibility`, `verifyVoteSubmit` | unrouted | — | **delete (dead)** |
| `ElectionVotingController::show` eligibility/`canVote` prop | projection with its own rule | projection of the resolver | the rule: yes |
| `ElectionVotingController::start` membership, `has_voted`, `canVote`, `resolveIpBlock` | yes | resolver | yes |
| `start` slug creation, `expires_at = now()+30` **hard-coded** | window grant | persistence; minutes from law (`ballot.session_minutes`) | adapt |
| `CodeController::verifyCode` credential, expiry, IP cap (`:874-908`) | yes | credential: transport; expiry: ballot evidence; IP cap: delete | split |
| `CodeController::markCodeAsVerified` sets `can_vote_now=true` | **de-facto grant** | persistence of the fact "code 1 verified" | reshape |
| `CodeController::isUserEligible` (session demo bypass) | yes | resolver | delete |
| `DemoVoteController` | trust logged only | out of scope for CAST_VOTE (H-9) | — |

---

## 9. Policy Boundary

| Policy | Current behaviour | Should become | Why | Authority leakage? |
|---|---|---|---|---|
| `PolicySequence` | ordered; short-circuits at first concern → INSUFFICIENT | **delete** | the resolver becomes the combinator | yes (L-1) |
| legacy `VerificationAttestationPolicy` | finding from a fabricated attestation | delete | superseded by the Simplified `VerificationPolicy` | via `PolicySequence` |
| legacy `NetworkBindingPolicy` | count only, trust-scaled limit | delete | superseded; no binding check | yes (limit semantics) |
| legacy `DeviceBindingPolicy` | always passes (fed `ExactMatch`) | delete | superseded | — |
| Simplified `VerificationPolicy` | pure; `passed` bool | **evaluator (keep)** | correct shape | no |
| Simplified `NetworkBindingPolicy` | pure; one strategy at a time; off-by-one; strict vs session-start IP | **evaluator (redesign)**: composable binding + limit, correct comparator | law model §5 | no |
| Simplified `DeviceBindingPolicy` | pure; relies on precomputed `matchType` | **evaluator (adapt)**: compare current vs registered hash itself | the comparison is the evaluation, not acquisition | no |
| `SimplifiedPolicySequence` | runs all; reason precedence; `Log` facade + `now()` | delete (the resolver replaces it) | impure; a duplicate combinator | reason precedence |
| `BallotAuthorizationPolicy` (`CapabilityPolicy`) | single/dual code decisions | **resolver policy (keep, wire)** | already the right type | no |
| `VoterVerificationPolicy` (`CapabilityPolicy`) | prohibits when required and incomplete | **resolver policy (keep, wire)** | already the right type | no |
| `LifecycleCapabilityBaselinePolicy` | allowed_states | **resolver policy (keep)** | needs a CAST_VOTE entry | no |
| `OverlayCapabilityPolicy` | suspended → shortCircuit | resolver policy (keep) | suspension is lifecycle law | no |
| `EvidenceCapabilityPolicy` | maps legacy trust state; dormant overlay HardBlock | **redesign** as the resolver policy that interprets the evaluator findings (network, device, verification) under the constitution | the single place where findings become a decision | the dormant HardBlock must go |
| `TrustCapabilityPolicy` | unregistered; phantom symbols | delete | dead | — |
| *(new)* membership resolver policy | — | resolver policy over `ParticipationEligibilityEvidence` | replaces 5 gates | — |
| *(new)* already-voted resolver policy | — | resolver policy over one has-voted fact | replaces 6+ branches | — |

The two new rows are the only genuinely new policies. Both replace existing inline code; neither is a new abstraction.

---

## 10. CAST_VOTE Capability Contract (conceptual, not implemented)

**Action vocabulary.** No voting action exists. `ElectionConstitution::RULES` contains lifecycle transitions with target states (`getTargetStateForAction`); `apply_candidacy` is the only voter action. **`cast_vote` must not be added to `RULES`**, because it is not a transition. Where the participation action registry lives is open (H-5). The minimum is a key `cast_vote` with `allowed_states: [voting_active]` and role `voter`, consumed by `LifecycleCapabilityBaselinePolicy`.

**`CapabilityContext` input for CAST_VOTE**

| Field | Type (existing where possible) | Source |
|---|---|---|
| `action` | `'cast_vote'` | fixed |
| `state` | `ElectionLifecycleState` | lifecycle engine (fact) |
| `constitution` | the single frozen snapshot + its hash | `elections.security_articles_snapshot`, **hash-verified on load** |
| `membership` | `ParticipationEligibilityEvidence` (exists) | one query (H-7 predicate) |
| `ballot` | `BallotSession` (exists) | codes row(s) |
| `verification` | `VerificationSession` / `VerificationEvidence` (exist) | `voter_verifications` |
| `network` | `NetworkEvidence` + registered-IP hash + prior-vote count | H-1/H-4 sources |
| `device` | `DeviceEvidence` + registered fingerprint hash | H-2 |
| `alreadyVoted` | bool fact | H-7 |
| `findings` | the complete evaluator findings | evaluators, run by the context builder |
| `evaluatedAt` | injected clock value | `ClockInterface` |

These must be **typed fields**, not `actionMetadata[...]`. Today `BallotAuthorizationPolicy` and `VoterVerificationPolicy` read untyped keys, and `EvidenceCapabilityPolicy` reads `simplified_overlay_signal`.

**Must NOT be passed:** live `Election`/`User` Eloquent models (the current `CapabilityContext` holds both), another resolver's result, a `LegitimacyOutcome`, a trust level, overlay observations as a decision input (H-8), session flags, `config()`/`env()` values, UI state.

**`CapabilityDenialReason` additions** (vocabulary only): `NotAMember`, `AlreadyVoted`, `BallotProtocolUnmet`, `NetworkBindingViolated`, `NetworkLimitReached`, `DeviceBindingViolated`, `VerificationMissing`. The existing `UnmetPrecondition` and `TrustDenied` are too coarse to replay meaningfully.

**Output:** `CapabilityDecision` plus the complete trace (every policy's outcome), returned rather than stored on the resolver.

---

## 11. Characterization-Test Readiness

### 11.1 Defect classes (218 failing tests in 38 real-election voting files) [RUN]

| Class | Count | Nature | Fix scope |
|---|---|---|---|
| (a) fixture schema: `users.is_voter` (101), `posts.post_id` (8) | 109 | columns exist only in `database/migrations_backup_*` | **test only** |
| (b) factory/seed: duplicate `user_organisation_roles` (42), demo election `organisation_id=null` (20), membership without org (8), tenant-scoped `find` (8), `Vote` without org (4), tenant-scoped `update` (2) | 84 | fixtures predate tenancy rules | **test only** |
| (c) `new VoteController()` arity | 4 | constructor now takes 2 dependencies | **test only** (resolve via container) |
| (d) route-name / web-group drift | 2 | intentional production changes | **test only** |
| (e) genuine business-rule failure | **0** | — | — |
| (f) lifecycle "VotingActive not derived" | 19 | fixtures lack `voting_locked` and an approved candidacy, both required since `f2c2cc4ee` (PBDIGIT-64) and `606c362aa` | **test only** [PROBE: adding both yields `voting_active`] |

**Every one of the 218 is a test-infrastructure defect.** None requires a production change.

### 11.2 Production defects the suite does not currently express

X-1…X-11 (§1). Characterization tests must **pin current behaviour, including these defects**, and must not fix them. Each defect is then fixed by its own RED→GREEN slice.

### 11.3 Definition: "characterization complete"

1. All 38 files run with **0 errors**. Every remaining failure is behavioural, and each is listed with its cause.
2. No assertion was changed to match current behaviour unless it is labelled `@characterization` (pinning, not endorsing).
3. **One characterization test per participation gate in §7/§8** (≈25 gates), through the real HTTP route. Each asserts today's accept/reject outcome for its triggering fact.
4. **One test per defect X-1…X-11**, pinning the defective behaviour.
5. The suite runs in a CI job (see §15).

### 11.4 Correction to the first audit

The first audit's **G-18 / P1-0** ("`VotingActive` derivation, behaviour suspect", HIGH) was **wrong**. It is a fixture gap. The lifecycle engine behaves as its recorded rules require. P1-0 should be removed from the roadmap.

---

## 12. Replay Contract

**Minimum decision record** (one per CAST_VOTE evaluation, **every** decision, no sampling):

| Field | Note |
|---|---|
| `decision_id` | random UUID. It is **not** a correlation to the vote row |
| `election_id` | |
| `action` | `cast_vote` |
| `constitution_hash`, `law_version` | from the verified snapshot |
| `evidence` | hashed IP, registered-IP hash, prior-vote count, fingerprint hash, registered fingerprint hash, verification facts (attested, revoked, officer id **hashed**), ballot-session facts, membership facts, lifecycle state, already-voted |
| `evidence_provenance` | per fact: source table and column, acquired-at |
| `findings` | every evaluator finding |
| `observations` | overlay signals (audit only) |
| `decision` + `trace` | outcome, reason, per-policy entries |
| `evaluator_version` | code identity (a version constant or commit) |
| `decided_at` | the injected clock value used |

**Anonymity (ADR-T11 / CLAUDE.md: no voter↔vote linkage).**
- The record may identify the *voter* (participation is not secret).
- It must never contain or be joinable to the `votes` row, a `vote_id` or candidate selections.
- No voting code in any form: today codes are logged in plaintext (X-4). No raw IP, and no raw fingerprint.

**Can the decision be reproduced without querying mutable live state?** **Not today.** Evidence is read from live mutable rows and `env()`, and the decision is never stored. **With the record above: yes.** The resolver becomes a pure function of the record: `(constitution, evidence, findings) → decision`. That is the property the replay test verifies.

---

## 13. Runtime Parity Contract

**Boundary:** a real HTTP request through the full middleware stack against the legacy code path, compared with the same request against the resolver path. Selection is by a per-request shadow evaluation (the resolver evaluates; the legacy path still acts), **not** by two deployments. The existing `constitutional_mode` shadow pattern is the precedent.

**Fixture dimensions:** constitution (law articles) · membership state · lifecycle state · code state (single/dual, used, verified, expired) · verification row · request IP vs bound IP · prior votes from IP · fingerprint vs registered · already voted.

**Minimum viable matrix: 14 critical paths**, not a combinatorial set:
1. Happy path, single code.
2. Happy path, dual code.
3. Not a member.
4. Suspended or expired member.
5. Lifecycle not `voting_active`.
6. Election suspended.
7. Already voted.
8. Code not verified.
9. Dual code, view step missing.
10. IP limit reached: exactly N prior votes (catches the off-by-one).
11. IP binding violated.
12. Whitelisted IP over the limit.
13. Device binding violated.
14. Verification required and missing.

**Divergence record:** persisted, with fields path id, legacy outcome, resolver outcome, law article, and approved-by (a human act) with rationale.
- **Expected divergences are known in advance:** paths 11, 13 and 14 (legacy never enforces them), paths 10 and 12 (limit and whitelist defects), and the session-bypass cases (X-6).
- Each needs an approved-divergence entry **before** deletion. They are corrections, not regressions.

---

## 14. Legacy Deletion Manifest

| Item | Verdict | Why |
|---|---|---|
| `ConstitutionalLegitimacyDecision` | **DELETE** | replaced by the resolver decision |
| `PolicySequence` | **DELETE** | the combinator moves into the resolver |
| `TrustPolicyEvaluator` | **ADAPT → evidence builder** | its hashing and acquisition are reusable; its hard-coded facts and policy execution are not |
| legacy `Policies/{VerificationAttestation,NetworkBinding,DeviceBinding}Policy` | **DELETE** | superseded by the Simplified evaluators |
| `SimplifiedPolicySequence` | **DELETE** | duplicate combinator |
| Simplified evaluators (3) | **KEEP / ADAPT** | §9 |
| `BallotAuthorizationPolicy`, `VoterVerificationPolicy` | **KEEP (wire)** | already resolver policies |
| `EvidenceCapabilityPolicy` | **ADAPT** | becomes the findings interpreter |
| `TrustCapabilityPolicy` | **DELETE** | dead, references phantom symbols |
| `TrustSnapshotAssembler` | **DELETE** after the decision record exists | projection superseded by the record |
| `SnapshotAssembler` | **DELETE** (now) | no callers, broken references |
| `NetworkTrustEvidence::effectiveLimit` | **DELETE**, or MIGRATE into law if H-3 keeps trust scaling | interpretation inside evidence |
| `ValidateVotingIp` | **DELETE** | shadow-only; evidence moves to the builder |
| `ElectionVotingController::resolveIpBlock/evaluateIpCount` | **DELETE** | the 3rd/4th per-IP authority |
| `CodeController` IP cap, `vote_post_check` IP count | **DELETE** | duplicate per-IP authority |
| `EnsureElectionVoter`, `VoteEligibility`, `EnsureVotingActive`, `EnsureRealVoteOrganisation` | **DELETE** | participation authority / no-op |
| `EnsuresVoterMembership` trait gates, `canVote()` controller gates | **DELETE** | duplicate gates |
| `ElectionLifecycle` / engine | **KEEP** | the lifecycle fact source |
| `canVote()` as projection (UI props) | **KEEP**, fed by the resolver | projection |
| `OverlayAggregator` + overlays | **KEEP** as observation (H-8) | not authority |
| `ConstitutionalArticlesSnapshot` vs `ElectionConstitutionSnapshot` | **MIGRATE to one** | two hashes |
| dead `second_submission`, `validateVotingEligibility`, `verifyVoteSubmit` | **DELETE** | unrouted |

---

## 15. CI / Architecture Enforcement

| # | Rule | Mechanism | Notes |
|---|---|---|---|
| 1 | Only the resolver and registered policies produce authority | **PHPStan custom rule** (static calls to `CapabilityDecision::{authorized,prohibited,shortCircuit,grant,deny}` allowed only inside `CapabilityPolicy` implementors) + an architecture test that the registered set equals the allow-list | replaces the vacuous `new CapabilityDecision` check |
| 2 | Canonical zones do not import legacy authority | **deptrac** layers for `app/Domain/Election/Security`, `app/Application/Election/{Security,Capabilities}` | deptrac covers only `app/Contexts/*` today |
| 3 | No mutable settings in canonical zones | PHPStan rule forbidding `config()`, `env()`, `Setting*`, Eloquent model imports in evaluators and resolver policies | |
| 4 | Policies do not consume policy outcomes | deptrac (evaluators must not depend on `CapabilityDecision`); PHPStan: resolver policies receive only `CapabilityContext` | |
| 5 | Snapshots do not evaluate | an architecture test (reflection: no method returning `CapabilityDecision`, no policy dependency) | |
| 6 | Middleware do not derive participation | an architecture test: no middleware in `app/Http/Middleware` calls `canVote()`, membership predicates or `CapabilityDecision` | an allow-list for transport middleware |
| 7 | Forbidden vocabulary | a token-based scanner (`PhpToken`) over canonical zones for `priority`, `strongest`, `recommend`, `escalat`, `influence`, `elevat` in identifiers | not substring greps over comments |
| 8 | Law is immutable | a DB-level integration test (§ H-10) + a runtime hash check on load | |
| 9 | Replay inputs sufficient | an integration test: persisted record → resolver → identical decision, with no DB access permitted during replay | |

All of these must run in a **blocking** CI job. `greenfield-merge-gate` today runs only the Architecture suite plus `app/Contexts/*` tooling, and its Architecture suite currently fails 3 tests.

---

## 16. Migration Dependencies

```
H-decisions (§17) ──────────────────────────────┐
                                                ▼
C-1 fixture repair ─► C-2 gate characterization ─► C-3 defect pins
                                                │
L-1 single law model + authoring + hash check ◄─┘  (needs H-1..H-5, H-10)
        │
E-1 evidence builder (typed, provenance) ◄── H-1, H-2, H-4, H-7
        │
V-1 evaluators redesigned (composable network, off-by-one, device compare)
        │
R-1 resolver: full evaluation + stateless trace + CAST_VOTE policies (typed context)
        │
S-1 shadow evaluation in the voting path + decision record (replay)
        │
P-1 runtime parity (14 paths) + approved divergences
        │
X-fixes for X-1..X-11 (each its own slice; security ones X-3/X-4/X-5/X-6 may go first, see §18)
        │
D-1 switch authority to the resolver ─► D-2 delete legacy (§14) ─► CI rules (§15) ─► v1.0
```

---

## 17. Blocking Questions (human decisions)

| # | Question | Blocks |
|---|---|---|
| H-1 | Which IP is "the verified IP" for rules 1/4: `users.voting_ip`, the IP at code-1 verification (`codes.client_ip`), the officer-captured `verified_ip`, or the slug's `step_1_ip`? | E-1, L-1 |
| H-2 | What is the registered device fingerprint for rule 5: officer-captured, or first-seen at code verification? | E-1 |
| H-3 | Does trust level scale the per-IP limit (today ×½ … ×2) as law, or is the limit exactly N? | L-1, V-1 |
| H-4 | Rule 7 counter: votes per IP counted from which record, and does "max 6" mean the 6th vote is allowed and the 7th refused? | E-1, V-1 |
| H-5 | Where does the participation action registry live (not in `ElectionConstitution::RULES`)? | R-1 |
| H-6 | One "platform organisation" rule (`=== 1`, config id, or slug)? | transport cleanup |
| H-7 | Which record is the single "already voted" fact, and which membership predicate is canonical (expiry? `suspension_status='proposed'`?) | E-1 |
| H-8 | Do overlays ever influence CAST_VOTE, or remain audit-only observations? | R-1 |
| H-9 | Is demo voting in scope for CAST_VOTE, or kept deliberately outside constitutional enforcement? (The CLAUDE.md demo model `organisation_id = NULL` no longer matches the NOT NULL schema.) | R-1, P-1 |
| H-10 | Is ORM-level immutability accepted, or is DB-level protection (trigger or column privilege) required? **Recommendation: DB-level.** The guard is bypassed by `saveQuietly`, `updateQuietly`, `withoutEvents`, query-builder `update()` and `DB::table` [PROBE]. The model itself uses `updateQuietly` in 5 places and `DB::table` in 9. | L-1 |
| H-11 | Existing elections (created with DB-default law): how is their law established? (Freezing defaults retroactively is itself a law decision.) | L-1 |

**Not blocking characterization.** All of H-1…H-11 block constitutional implementation, but **none** blocks C-1…C-3.

---

## 18. Exact Implementation Sequence

1. **C-1 (first task):** repair the voting feature-test fixtures (classes a–d, f, §11.1), test code and factories only, in one story. Done when the 38 files run with 0 errors.
2. **C-2:** characterization tests, one per participation gate (§7/§8), through HTTP.
3. **C-3:** defect pins X-1…X-11.
4. **Security fixes that need no sovereignty decision**, each its own RED→GREEN story, after C-3 pins them: X-3 lock/transaction leak · X-4 plaintext code logging · X-5 `debug_code` exposure · X-6 session demo bypass.

   These are defects under any architecture. They are listed here for the PO to schedule; this review does not authorize them.
5. **H-decisions** recorded (§17).
6. **L-1** single law model, authoring at creation, hash verification on load, DB-level immutability (H-10).
7. **E-1** typed evidence builder with provenance (adapting `TrustPolicyEvaluator`).
8. **V-1** evaluators (adapting the Simplified policies).
9. **R-1** resolver: full evaluation, stateless trace, typed CAST_VOTE context and policies.
10. **S-1** shadow CAST_VOTE evaluation in the voting path + decision record.
11. **P-1** runtime parity over the 14 paths; divergences approved.
12. **D-1** switch the vote decision to the resolver (one release).
13. **D-2** delete per §14 · **CI rules** per §15 · correct documentation claims · **v1.0 freeze**.

---

## 19. Definition of Done

A condition counts as met only if it holds in a CI-run test:
1. Characterization is complete per §11.3.
2. One constitution representation, one hash, verified on load; DB-level immutability.
3. The CAST_VOTE decision is produced only by the resolver, from a typed context. No middleware or controller derives participation (rules 1, 6).
4. The 7 rules are each decided by an evaluator plus a resolver policy under explicit law.
5. Every decision is persisted per §12; replay reproduces it with DB access disabled.
6. The 14 parity paths are green, or approved-divergent.
7. The §14 deletions are done.
8. The §15 rules are enforced in a blocking CI job.

---

## 20. Final Readiness Verdict

> ## **READY FOR CHARACTERIZATION**

**Why not NOT READY:** the characterization blocker has been triaged completely. All 218 failures are test-infrastructure defects with known causes and test-only fixes (§11.1). No human decision blocks C-1…C-3.

**Why not further:** constitutional implementation is blocked by H-1…H-11, above all:
- the unidentified "verified IP" (H-1);
- the rule-7 semantics (H-3/H-4);
- the two incompatible constitution representations;
- the missing law-authoring path (X-11).

---

## 21. Summary (A–J)

**A. What is already correct?**
- The resolver's deny-first combinator and its private-constructor decision type.
- The lifecycle engine as a fact derivation (the `VotingActive` rules work as recorded).
- The resolver-ready `BallotAuthorizationPolicy` and `VoterVerificationPolicy`.
- The pure Simplified evaluators and the typed evidence model.
- Vote anonymity in persistence.
- The transport middleware (slug, ownership, step order, throttle, CSRF).
- The snapshot write and hash at creation.

**B. What is architecturally wrong?**
- Participation is decided in ≥17 places.
- A second sovereign (`ConstitutionalLegitimacyDecision`) exists, and `PolicySequence` is an ordered chain.
- The resolver short-circuits and holds mutable singleton state.
- The context carries live Eloquent models and untyped `actionMetadata`.
- Interpretation lives inside evidence (`effectiveLimit`, precomputed `matchType`).
- Two constitution representations with incompatible hashes.
- Law is read from mutable columns and env.

**C. What is merely incomplete?**
- No CAST_VOTE action.
- Evidence builders for verification, device and registered IP.
- Law authoring.
- The decision record.
- Runtime parity.
- CI coverage of these zones.

**D. What is broken?**
- X-1…X-11: the ignored post-check, the bypassed pre-check, the lock/transaction leak, plaintext code logging, `debug_code` exposure, the session demo bypass, the no-op org middleware, dual-code activation, `is_voter`, the whitelist `TypeError`, the UUID `TypeError`, and unauthorable law.
- Also 218 test failures from fixture drift.

**E. What must be preserved?**
- Vote anonymity (no user↔vote link).
- Transport and abuse middleware.
- The vote-store lock and in-lock has-voted check.
- The credential check.
- The lifecycle engine.
- Tenant isolation.
- The existing resolver abstractions.
- The recorded PBDIGIT-64 and `voting_locked` rules.

**F. What must be migrated?**
- Membership, lifecycle, already-voted, code-verified, IP and device facts → typed evidence.
- The Simplified evaluators → redesigned evaluators.
- `EvidenceCapabilityPolicy` → the findings interpreter.
- `TrustPolicyEvaluator` → the evidence builder.
- The two snapshots → one.
- Code protocol and session minutes → law.

**G. What must eventually be deleted?** §14:
- `ConstitutionalLegitimacyDecision`, `PolicySequence`, the legacy policies, `SimplifiedPolicySequence`.
- `TrustCapabilityPolicy`, `SnapshotAssembler`, `TrustSnapshotAssembler`, `effectiveLimit`.
- `ValidateVotingIp`, `resolveIpBlock`/`evaluateIpCount`, the CodeController IP cap.
- `EnsureElectionVoter`, `VoteEligibility`, `EnsureVotingActive`, `EnsureRealVoteOrganisation`.
- The controller `canVote()` and membership gates.
- The unrouted controller methods.

**H. What must be decided by humans?** H-1…H-11 (§17). The scheduling of the security fixes X-3…X-6 is also the PO's.

**I. What is the FIRST implementation task?** **C-1:** repair the real-election voting feature-test fixtures (test code and factories only; categories a–d and f in §11.1) so that the 38 files run with 0 errors. No production change and no assertion change. It should be one story with a PBDIGIT id (to be created by the PO; this review creates none).

**J. What is the complete dependency-ordered migration sequence?** §18: C-1 → C-2 → C-3 → (X-3/X-4/X-5/X-6 security fixes, PO-scheduled) → H-decisions → L-1 → E-1 → V-1 → R-1 → S-1 → P-1 → D-1 → D-2 → CI rules → documentation correction → v1.0.

---

**Traceability.** The first audit (`4afd43481`). Code:
- `app/Application/Election/Services/ElectionCapabilityResolver.php`
- `app/Application/Election/Capabilities/{CapabilityDecision,CapabilityDenialReason,CapabilityPolicyLayer,CapabilityContext}.php`
- `app/Application/Election/{BallotAuthorization,VoterVerification}/Policies/*`
- `app/Application/Election/Security/Simplified/**`, `app/Domain/Election/Security/Simplified/**`
- `app/Domain/Election/Constitution/{ElectionConstitution,ConstitutionalArticlesSnapshot}.php`
- `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:105-120`
- `app/Http/Controllers/{VoteController,CodeController,ElectionVotingController}.php`
- `app/Http/Middleware/{VoteEligibility,EnsureElectionVoter,EnsureRealVoteOrganisation,ValidateVoterSlugWindow,VerifyVoterSlugConsistency}.php`
- `app/Traits/EnsuresVoterMembership.php`, `app/Models/{Election,User}.php`
- `routes/election/electionRoutes.php:168,490-535`

Commits `f2c2cc4ee`, `606c362aa`. Test triage and probe outputs are in the session scratchpad (not in the repository).
