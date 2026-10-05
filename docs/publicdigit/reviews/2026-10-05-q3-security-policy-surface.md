# Q3 — The Security Policy Surface of PublicDigit (discovery and design proposal)

**Date:** 2026-10-05 · **Status:** **REVIEWED (PO / ARB 2026-10-05): Q3-D1, D2, D3, D6 decided (below); D4, D5, D7 open.** One characterization RED authorised (§16); **no implementation authorised; production behaviour unchanged.**

## 0 · Architectural decisions (PO / ARB 2026-10-05)

| ID | Decision |
|---|---|
| **Q3-D1 — authoritative policy fields** | **APPROVED.** The election-scoped Security Policy model is based on the **constitutional policy fields** `max_votes_per_ip`, `network_binding_strategy`, `device_binding_strategy`, `ip_whitelist`. The legacy settings fields `ip_restriction_enabled`, `ip_restriction_max_per_ip`, `voter_verification_mode` must **not** become a second, competing security-policy model; they may be mapped, retired or otherwise reconciled later in a dedicated 2B story. Q3 establishes **one** conceptual policy source. *Finding preserved:* the settings page currently exposes fields the authority does not use. |
| **Q3-D2 — evaluation lifecycle** | **APPROVED.** Security Policy evaluation occurs **only** at `VotingAuthorization::forEntry()` and at the final-cast `VotingAuthorization::forCast()`. No independent Security Policy evaluation at `CodeController::verifyCode()`, `VoteController::verify()` (step 4), middleware or the start-page IP block. **This does not remove process validation** (code validity, step order, participation state, CAS stay process / authorization concerns). Rule: *no duplicate Security Policy evaluation outside the Security Verdict Authority.* |
| **Q3-D3 — `MAX_USE_IP_ADDRESS`** | **APPROVED.** Not an authoritative runtime Security Policy source. It may remain temporarily as a migration / creation-time default; after policy creation it must not override the election's frozen Security Policy. No runtime change authorised in this step. |
| **Q3-D6 — registered-network and device binding** | **Not retired.** P-BINDING and P-DEVICE are classified as **candidate Security Policies — currently inert — implementation deferred.** No `users.voting_ip`, no migration, no device-binding wiring, no middleware change, no PBDIGIT-234 change; the storage / identity decision stays separate. |
| **Q3-D7 — tenant scope of the density count** | **OPEN.** Not changed in this step. **2B must resolve the scope question before retiring the cross-tenant IP counts** (#3, #5). |
| Q3-D4, Q3-D5 | Open (start page; whitelist semantics). |
**Placement:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Repository state:** HEAD `62c45b272` (PBDIGIT-240 resolved)
**Author:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Evidence and recommendations only; every decision is the PO's / ARB's.
**Evidence labels:** [S] read in source at HEAD · [D] committed test · [I] inference from [S] / [D].

---

## 1 · Objective

Determine:
- which security policies PublicDigit really has;
- where each one is configured;
- who evaluates it, and when;
- how each should end up under the security architecture.

That defines the boundary I3 needs ("One security authority; no IP / device check outside it"). PBDIGIT-240 made I3 true for the final-cast security assessment only. This document prepares 2B and does not perform it.

**Scope rule (PO):** not every check that uses an IP address is a security policy, and not every security policy belongs in `forCast()`.

## 2 · Current security architecture (what exists)

```
                  Security Verdict Authority  (TrustLegitimacySecurityVerdict)
   evidence ──► TrustPolicyEvaluator ──► ConstitutionalLegitimacyDecision ──► SecurityAssessment / SecurityVerdict
   (density count, registered-IP hash,     PolicySequence: NetworkBindingPolicy,   (four-valued outcome → three-valued verdict)
    fingerprint, session)                  DeviceBindingPolicy, VerificationAttestationPolicy …
                                           Overlays (observation only): IpVelocity, SuspiciousActivity, DeviceAnomaly …
        consumed by:  forEntry()  (CodeController create / store — code step)       [PBDIGIT-239]
                      VoteController::store() (final cast, one assessment)          [PBDIGIT-240]

   OUTSIDE the authority (own rules):  #1 resolveIpBlock · #3 verifyCode IP count · #4 validate.voting.ip middleware
                                       · #5 vote_post_check IP count (step 4) · #7 validateVotingIpWithResponse (final cast)
```

## 3 · Security mechanism inventory (voting path of a real election, in order)

| # | Mechanism (file :: method) | Lifecycle point | Rule [S] | Data it counts / reads | Scope | Tests | Classification | Verdict |
|---|---|---|---|---|---|---|---|---|
| **1** | `ElectionVotingController::show` / `start` → `resolveIpBlock()` → `evaluateIpCount()` | election page (display) / **start voting** (blocking redirect) | whitelisted → allow; else `ip_restriction_enabled` → limit `ip_restriction_max_per_ip`; else global `config('app.max_use_clientIP')` (int, 0 = off); **blocked when voted count ≥ limit** | `voter_slugs` with `step_1_ip = ip` and `has_voted` | election | `ElectionIpRestrictionTest` (8): **fixtures write `step_1_ip` directly** | Security policy (density) as an **entry** control, but its evidence is never produced: **no code in `app/` writes `step_1_ip`** (VE-15), so in production the count is always 0 and the block never fires [I] | ⚫ dead in production; 🔴 a parallel interpretation of `ip_whitelist` |
| **2** | `VotingAuthorization::forEntry()` ← Security Verdict Authority | code step (`slug.code.create` / `slug.code.store`) | Trust → Legitimacy: density `votes > effectiveLimit(trustLevel)`, Unverified = `max(1, intdiv(max_votes_per_ip, 2))` = **3** by default, off when `network_binding_strategy = 'none'`; whitelist bypass | `codes.client_ip` + `has_voted`, **tenant-scoped** | election (+ tenant) | `CodeEntryAuthorizationTest`, `VotingSecurityVerdictAdapterTest` | **Security assessment** (authority) | 🟢 |
| **3** | `CodeController::verifyCode()` (called from `store()` ≈323) | code step, after `forEntry()`, at code verification | **refused when voted count ≥ `config('app.max_use_clientIP') ?? 7`** | `codes.client_ip` + `has_voted`, **`withoutGlobalScopes()`** (all tenants) | election, cross-tenant | `CodeControllerRealElectionCharacterizationTest` (limit set to 2) | **The same policy as #2** (votes per IP in this election) with a **different threshold** (≥ 7 vs > 3), **different scope** and **different configuration** (env vs election) | 🔴 parallel authority |
| **4** | middleware `validate.voting.ip` (`ValidateVotingIp`) on `v/{vslug}` agreement + vote routes (**not** on the code-step routes) | agreement, ballot, verify, final cast — before the controller | if `config('voting_security.control_ip_address')` and `users.voting_ip` set and ≠ request IP: `constitutional_mode` (default **true**) → divergence record only; else `ip_mismatch_action` (`block`; `warn` also redirects) | `users.voting_ip`: **column does not exist** (PBDIGIT-234) → always `null` → always passes | user | `ValidateVotingIp`-related suites (6 files mention it) | Registered-IP binding (policy), **inert twice over**: missing column + shadow mode | ⚫ inert; 🔴 a third copy of registered-IP binding |
| **5** | `VoteController::verify()` (step 4) → `vote_post_check()` ≈3239 | **step 4 (verify), real path**; demo skips the IP block | **refused when voted count ≥ `config('app.max_use_clientIP')`, with no default**: env unset → `null` → `0 >= null` is true → **every voter refused** (Phase 1 finding) | `codes.client_ip` + `has_voted`, `withoutGlobalScopes()` | election, cross-tenant | Phase 1 fixtures pin the env to 7 (`CastFixtures::makeVotingElection`) | Same policy as #2 / #3, a third threshold source | 🔴 parallel authority, plus an env-dependent fail-everyone defect |
| **6** | `VoteController::store()` → `VotingSecurityAssessmentQuery::assess()` | final cast | the authority (as #2) | as #2 | as #2 | `SecurityAssessmentConsolidationTest`, Phase 4 suites | Security assessment | 🟢 (PBDIGIT-240) |
| **7** | `VoteController::store()` ≈1736 `validateVotingIpWithResponse()` | final cast, after the lock, before the CAS | `users.voting_ip` ≠ request IP → `Vote/VoteDenied` | `users.voting_ip` (missing → always passes) | user | `FinalCastExitSafetyTest` Exit B (in-memory double) | Registered-IP binding, inert | ⚫ inert; 🔴 copy of #4 |
| 8 | Authority internals: `NetworkBindingPolicy` whitelist; `NetworkTrustEvidence::isWhitelisted()` | inside #2 / #6 | whitelist bypass of the density rule | `elections.ip_whitelist` via `json_decode($election->ip_whitelist)` on an **array cast** → `TypeError`; also compares the **hashed** current IP with **raw** whitelist entries | election | `VotingSecurityVerdictAdapterTest` known-defect tests | Security policy (whitelist): **broken in the authority** | 🔴 broken |
| 9 | Authority internals: `DeviceBindingPolicy` / `DeviceTrustContext` | inside #2 / #6 | always satisfied: a submitted fingerprint is treated as `ExactMatch` with `registeredFingerprintHash = null`; no fingerprint → `NotRequired` | `device_binding_strategy` is **not read** by the policy (snapshot only) | election | — | Device binding: **inert** | ⚫ inert |
| 10 | Authority overlays (`IpVelocityOverlay`, `SuspiciousActivityOverlay`, `DeviceAnomalyOverlay`, …) | inside #2 / #6 | observation signals; never short-circuit (evaluator comment) | evidence | — | overlay unit tests | Security evidence / observation | 🟢 (not a policy) |
| — | route `throttle:10,1` on the vote group | agreement → final cast | request-rate limit | requests | per client | — | Infrastructure abuse protection, not an election security policy | out of the Q3 surface |

**Not on the real voting path (checked):** `VotingSecurityService` (the IP helpers `canVoteFromIp` / `getIpMatchStatus` read `users.voting_ip`; slug issuance uses `canIssueVotingSlug`, no IP rule found) · `ElectionSettingsService` (maps `max_use_ip_address`, `control_ip_address`, `ip_whitelist`, `voting_ip_mode`; **no consumer** found outside a cache-clear) · `DemoVoteController` (demo, out of scope).

## 4 · IP security inventory: distinct policies vs repeated implementations

| Conceptual policy | Implementations [S] | Configuration used | Equivalent? |
|---|---|---|---|
| **P-DENSITY: participation per network (votes per IP per election)** | #2 / #6 authority (> trust-level limit, default 3, tenant-scoped) · #3 `verifyCode` (≥ env ?? 7, cross-tenant) · #5 `vote_post_check` (≥ env, no default, cross-tenant) · #1 `resolveIpBlock` (≥ `ip_restriction_max_per_ip` or env, counts `step_1_ip`) | `max_votes_per_ip` + `network_binding_strategy` · `MAX_USE_IP_ADDRESS` · `ip_restriction_*` | **Same conceptual policy, four implementations, three configuration sources, three thresholds, two scopes, two evidence sources.** Proven by the code of each, not by name |
| **P-WHITELIST: trusted networks bypass the density rule** | #8 authority (broken) · #1 `resolveIpBlock` (`Election::isIpWhitelisted`, raw CIDR, works) | `elections.ip_whitelist` | same setting, two interpretations; only the dead one works |
| **P-BINDING: voter bound to a registered IP** | #4 middleware · #7 `validateVotingIpWithResponse` · authority evidence (`registeredIpHash` → `satisfiesNetworkAttestation`) | `users.voting_ip` (missing) · `voting_security.*` · `voter_verification_mode` (no reader) | three implementations, all inert (no column) |
| **P-DEVICE: voter bound to a device** | #9 authority (inert) | `device_binding_strategy` (snapshot only), `voter_verification_mode` (no reader) | one implementation, inert |
| Suspicious-IP / velocity detection | #10 overlays | — | observation, not a refusal policy |
| Request rate limiting | `throttle:10,1` | route | infrastructure |

## 5 · Configuration inventory

| Setting | Persisted in | Default | Who can change it, and when | Runtime reader(s) | Lifecycle lock | Authoritative? |
|---|---|---|---|---|---|---|
| `max_votes_per_ip` | `elections` | 6 | **no application write path found**; snapshotted into `ConstitutionalArticlesSnapshot` at creation | authority (#2 / #6) | none needed in practice (no writer); no guard | **yes, de facto** (the authority applies it) |
| `network_binding_strategy` | `elections` | `ip_count` | as above | authority | as above | **yes, de facto** |
| `device_binding_strategy` | `elections` | `none` | as above | **none at runtime** (snapshot only) | — | inert |
| `ip_whitelist` | `elections` (json, array cast) | null | settings page (Chief / Deputy) | authority (**broken**: `TypeError`) · `resolveIpBlock` (works, dead path) | **locked from ReadyForVoting (PBDIGIT-244)** | contested |
| `ip_restriction_enabled` | `elections` | false | settings page | `resolveIpBlock` only (dead path) | locked (PBDIGIT-244) | **not applied by the authority** |
| `ip_restriction_max_per_ip` | `elections` | 4 | settings page | `resolveIpBlock` only | locked (PBDIGIT-244) | **not applied by the authority** |
| `voter_verification_mode` | `elections` | `none` | settings page | **none** (helpers `requiresVoterVerification` / `checksIp` / `checksFingerprint` have no callers) | locked (PBDIGIT-244) | inert |
| `voting_ip_mode` | `elections` | `strict` | — | `ElectionSettingsService` only (no consumer) | — | dead |
| `app.max_use_clientIP` | env `MAX_USE_IP_ADDRESS` | **none** | deployment | #3 (`?? 7`) · #5 (no default) · #1 global fallback (`(int)`, 0 = off) · `check_ip_address` | — | **parallel global authority** |
| `voting_security.control_ip_address`, `ip_mismatch_action`, `constitutional_mode`, `ip_validation_mode` | env / config | 1, `block`, **true**, `strict` | deployment | #4 middleware; `VotingSecurityService` (reporting) | — | inert (no `voting_ip`) |
| `users.voting_ip` | **no column** (PBDIGIT-234) | — | `BulkApproveVoters`, `ElectionProcessController` write it via `update()` (would fail or no-op) | #4, #7, authority evidence, `VotingSecurityService` | — | non-existent |

### Q3 architectural finding: **yes**, an administrator configures policy A while the authority enforces policy B

1. **The settings page shows the wrong knobs.** It edits `ip_restriction_enabled` / `ip_restriction_max_per_ip`, which only the dead `resolveIpBlock` reads. The authority applies `max_votes_per_ip` / `network_binding_strategy`, which no screen edits. PBDIGIT-244 therefore locks fields that the authority does not use.
2. **Known defect: a configured whitelist currently causes fail-closed refusal (instead of easing the density rule).** A non-empty `ip_whitelist` (allowed before ReadyForVoting) makes the authority throw a `TypeError` [D, known-defect test]. Both consumers turn any `\Throwable` into a refusal (fail closed): `forEntry()` at the code step and `store()` at the final cast. So configuring a whitelist **blocks voting for that election** instead of exempting networks [I from S + D].
3. **The deployment environment overrides the election's policy.**
   - An election whose constitution disables the density rule (`network_binding_strategy = 'none'`) is still limited by `MAX_USE_IP_ADDRESS` at code verification (#3) and at step 4 (#5).
   - With default settings, the authority denies at > 3 before #3 (≥ 7) can apply, so #3 only bites when the authority's rule is off or the voter is trusted [I].
   - If `MAX_USE_IP_ADDRESS` is unset, step 4 refuses every voter (#5).
4. **`voter_verification_mode` changes nothing.** The page offers it (`ip_only`, `fingerprint_only`, `both`), but nothing reads it at runtime.

## 6 · Authority mapping (current)

| Policy | Who evaluates today | Count |
|---|---|---|
| P-DENSITY | authority (#2 / #6), `verifyCode` (#3), `vote_post_check` (#5), `resolveIpBlock` (#1, dead) | **4** |
| P-WHITELIST | authority (#8, broken), `resolveIpBlock` (#1, dead) | 2 |
| P-BINDING | middleware (#4), `validateVotingIpWithResponse` (#7), authority evidence | 3 (all inert) |
| P-DEVICE | authority (#9, inert) | 1 |

## 7 · Lifecycle mapping

| Stage | Today's security evaluation |
|---|---|
| Election page / start voting | #1 (dead) |
| Code creation / code entry | #2 authority (`forEntry`) |
| Code verification | #3 (parallel, env) |
| Agreement, ballot entry, verify page | #4 middleware (inert) |
| Step 4 verify (submit) | #5 (parallel, env; fail-everyone if unset) |
| Final cast | #6 authority (one assessment) · #7 (inert, fails closed since PBDIGIT-243) |
| Post-cast receipt / verification | none (out of the Q3 surface; anonymity rules apply) |
| Administrative | settings page writes `ip_restriction_*`, `ip_whitelist`, `voter_verification_mode` (locked from ReadyForVoting) |

## 8 · Duplicate / parallel authorities (proven)

- **P-DENSITY:** #2/#6 vs #3 vs #5, plus #1. All count voted participation from the request IP in the election, with different comparisons (`>` vs `≥`), limits (trust-level 3 / 4 / 6 vs env 7 / unset), scopes (tenant vs `withoutGlobalScopes`) and evidence tables (`codes` vs `voter_slugs.step_1_ip`) [S].
- **P-WHITELIST:** #8 vs #1, two interpretations of one column (hashed and broken vs raw CIDR) [S].
- **P-BINDING:** #4, #7 and the authority's `registeredIpHash` [S].

## 9 · Dead, inert and broken controls

- **⚫ dead:**
  - #1 `resolveIpBlock` counts an unwritten column;
  - `voting_ip_mode`;
  - `ElectionSettingsService` (no consumer);
  - `device_binding_strategy` at runtime.
- **⚫ inert:**
  - #4 middleware, #7 helper and the authority's binding evidence (missing `users.voting_ip`);
  - #9 device binding;
  - `voter_verification_mode`.
- **🔴 broken:**
  - #8 whitelist in the authority (`TypeError`; hashed vs raw);
  - #5 env-unset refusal;
  - #4 `warn` mode, which redirects like `block` [S];
  - #5 refusals cannot be rendered: `components.error_message` does not exist, so every step-4 `vote_post_check()` refusal throws and `verify()` answers with a generic `system` error [D, C4]. Owned by the dedicated item **PBDIGIT-245** (separate error-handling concern, not part of 2B-4).
- **Test realism:** `ElectionIpRestrictionTest` passes on fixtures that write `step_1_ip`, a state production never produces.

## 10 · Proposed Security Policy Surface (Q3 boundary)

**A. Definition.**
> A **PublicDigit Security Policy** is an **election-scoped rule about the circumstances of a voting attempt**: the network, the device, the participation density from that network. It answers *"may this attempt proceed under this election's security rules?"*, never *"who may vote"* (admission / entitlement) or *"is this step of the process valid"* (process validation). It is configured once per election, immutable from ReadyForVoting (I9), and evaluated only by the Security Verdict Authority into a `SecurityAssessment` / `SecurityVerdict`.

**B. Inside the boundary:**
- **P-DENSITY** (participation per network);
- **P-WHITELIST** (networks exempt from P-DENSITY);
- **P-BINDING** (registered-network binding) — *candidate Security Policy, currently inert, implementation deferred (Q3-D6)*;
- **P-DEVICE** (device binding) — *candidate Security Policy, currently inert, implementation deferred (Q3-D6)*.

**C. Outside the boundary, and why:**

| Item | Category | Why it is not a security policy |
|---|---|---|
| Admission and identity verification | Admission | PBDIGIT-213; it is about who the voter is |
| Entitlement / lifecycle | Authorization | `VotingEntitlement`, `forEntry` / `forCast` E1 |
| Code validity, agreement, step order, already-voted pre-check, CAS | Process validation / participation | about where the process stands, not the attempt's circumstances |
| `throttle:10,1` | Infrastructure abuse protection | request rate, not an election rule; stays route middleware |
| Overlays | Security evidence | observation, never a refusal by itself |
| Receipt / post-cast verification | anonymity domain | governed by I8 / I10 |

**D. One authoritative source:**
- **The election's policy columns, frozen at ReadyForVoting.** Which columns (the constitutional `max_votes_per_ip` / `network_binding_strategy` / `device_binding_strategy`, or the settings `ip_restriction_*` / `voter_verification_mode`) is **decision Q3-D1**. `ip_whitelist` is shared.
- **The deployment environment (`MAX_USE_IP_ADDRESS`, `voting_security.*`) stops being a policy source.** At most it supplies a creation-time default (Q3-D3).

**E. Who evaluates:** the Security Verdict Authority only, for every policy in B. All other components consume its verdict or assessment.

**F. When (lifecycle stages):**

| Stage | Recommendation |
|---|---|
| Code entry (`forEntry`) | authority, as today |
| Final cast | authority, as today |
| Election start (#1) | either consume the authority's verdict or show nothing (Q3-D4) |
| Code verification (#3), step 4 (#5) | no separate evaluation; the authority already decides at entry and at the cast (Q3-D2) |

**G. Failure semantics:**
- Inside the authority, unchanged: Allowed → proceed. Denied → refusal. Deferred / Investigate → ReviewRequired → refusal with the outcome-specific message (D.0.3a). Any exception → fail-closed procedural refusal.
- Outside the authority: only process validations, with procedural refusals.

**H. I3 globally:** see §13.

## 11 · Proposed authoritative ownership

| Concern | Owner |
|---|---|
| Policy configuration (the chosen columns + `ip_whitelist`) | Election (settings), locked by the I9 model guard + application refusal (PBDIGIT-244, list extended per Q3-D1) |
| Evidence assembly (density count, registered IP, fingerprint) | Security Verdict Authority adapter |
| Evaluation (policies, resolver) | security context (`TrustPolicyEvaluator`, `ConstitutionalLegitimacyDecision`) |
| Verdict consumption | `VotingAuthorization` (`forEntry` / `forCast`), and the start page if Q3-D4 keeps a check there |
| Telemetry (audit, divergence) | consumers as observers (D-240-2) |

## 12 · Proposed 2B consolidation map (stories derived from the evidence)

```
CURRENT mechanism → current authority / lifecycle            ⇒  TARGET policy → source → evaluator → enforcement point
#8 whitelist in authority (broken)                            ⇒  P-WHITELIST → ip_whitelist → authority (fixed) → forEntry / forCast
#3 verifyCode IP count (env, ≥7, cross-tenant)               ⇒  P-DENSITY → election policy → authority (already at forEntry) → retire #3
#5 vote_post_check IP count (env, no default)                 ⇒  P-DENSITY → authority at the cast → retire #5 (step 4 keeps process checks)
#1 resolveIpBlock (dead count, ip_restriction_*)              ⇒  P-DENSITY / P-WHITELIST → authority verdict or nothing → start page
#4 middleware, #7 helper, registeredIpHash (no column)        ⇒  P-BINDING → decide keep (evidence in authority only) or retire all three
settings ip_restriction_* / voter_verification_mode           ⇒  the one policy configuration (Q3-D1), PBDIGIT-244 list adjusted
```

| Story | Content | Depends on | Independently testable by |
|---|---|---|---|
| **2B-1 Whitelist in the authority** | fix the `TypeError` and the hashed-vs-raw comparison inside the authority (no new policy) | Q3-D5 (whitelist semantics: CIDR on raw IP vs hashed list) | the existing known-defect tests become the RED, replaced by a working whitelist contract |
| **2B-2 One policy configuration** | map the admin settings to the fields the authority reads (or the reverse); retire the unused ones; extend / adjust `SECURITY_POLICY_FIELDS` | **Q3-D1** | settings → authority verdict tests; the PBDIGIT-244 suite |
| **2B-3 Retire the code-verification IP count (#3)** | remove the env-based count; the authority at `forEntry` is the only density rule | Q3-D2, Q3-D3 | RED: `network_binding_strategy = none` + 7 voted codes still refused today |
| **2B-4 Retire the step-4 IP count (#5)** | remove it from `vote_post_check`; the other step-4 process checks stay | Q3-D2, Q3-D3 | RED: env unset → every voter refused today |
| **2B-5 Election start page (#1)** | consume the authority's verdict, or remove the dead check and its display | Q3-D4 | tests without `step_1_ip` fixtures |
| **2B-6 Registered-network binding (#4, #7, evidence)** | keep as authority evidence only, or retire all three | **Q3-D6**, PBDIGIT-234 | middleware / helper / evidence characterization |
| **2B-7 Device binding / `voter_verification_mode`** | retire, or wire into P-DEVICE / P-BINDING configuration | Q3-D6 | — |
| **2B-8 I3 fitness closure** | extend P1 / P1b to middleware and helpers; remove all pinned debt | all of the above | the property tests with an empty allow-list |

## 13 · I3 interpretation: when "I3 GREEN for the whole voting path" may be declared

All of the following must hold, each proven by a test:

1. **One evaluator.** Every policy in the Q3 surface is evaluated **only** by the Security Verdict Authority. P1 / P1b-style properties cover controllers, middleware and helpers, with **no** pinned exception.
2. **One configuration.** Each policy has exactly **one** configuration source, election-scoped and locked from ReadyForVoting (I9 covers exactly that set).
3. **Every enforcement point consumes the authority.** Every voting-path point that applies a security rule (entry, cast, and the start page if kept) uses the authority's verdict or assessment, and none re-evaluates it.
4. **No dead copies.** Inert or dead security mechanisms are retired, or explicitly classified as outside the surface. None remains as a dormant parallel rule.
5. **No other policy source.** The deployment environment is no security-policy source on the voting path.

Today: 1 holds only for the final cast and the code step. 2–5 do not hold.

## 14 · Open human decisions

*Status 2026-10-05: D1, D2, D3, D6 decided — see §0. D4, D5, D7 remain open. The rows below keep the questions as asked.*

| ID | Decision |
|---|---|
| **Q3-D1** | Which fields **are** the policy? (a) the constitutional fields (`max_votes_per_ip`, `network_binding_strategy`, `device_binding_strategy`), made editable before ReadyForVoting; or (b) the settings fields (`ip_restriction_*`, `voter_verification_mode`), mapped into the authority; or (c) another mapping. Then retire the losers |
| **Q3-D2** | May a security policy be evaluated at stages other than entry and the cast (code verification, step 4, start page)? The recommendation is no: entry + cast only |
| **Q3-D3** | Fate of `MAX_USE_IP_ADDRESS`: retire as a policy, or keep as a creation-time default only |
| **Q3-D4** | Start page (#1): consume the verdict (and show "remaining votes"), or drop the check |
| **Q3-D5** | Whitelist semantics: raw CIDR ranges evaluated inside the authority (privacy implications of raw IPs there), or pre-hashed exact entries |
| **Q3-D6** | Registered-network and device binding: keep as policies (then PBDIGIT-234 must decide the storage), or retire `#4`, `#7`, the binding evidence and `voter_verification_mode` |
| Q3-D7 | Tenant scope of the density count (tenant-scoped in the authority vs cross-tenant in #3 / #5). It is a known transitional dependency; this document does not change it |

## 15 · Explicit non-goals

- No implementation.
- No change to: configuration, migrations, `users.voting_ip`, tests, PBDIGIT-240, PBDIGIT-244.
- Not addressed: anonymity A1 / A5 / A6 / A8, D6, PBDIGIT-242, D4.1, demo elections, `throttle`.

## 16 · Recommended RED characterization plan for 2B

**Before any 2B story, one characterization RED (tests only, synthetic data) pins today's conflicts.** Each 2B story then starts from known behaviour.

> **These tests characterize defects and current behaviour. They do NOT define the desired security contract.** Each one is replaced (not "kept green") by the 2B story that resolves it. **Authorised (PO 2026-10-05):** `tests/Feature/Voting/SecurityPolicySurfaceCharacterizationTest.php`, C1–C6 below.

1. **Known-defect characterization: a configured whitelist currently causes fail-closed refusal.** A non-empty `ip_whitelist` makes the authority throw; the final cast refuses, fail-closed. A characterization of the defect, not a fix.
2. **Settings have no effect:** changing `ip_restriction_enabled` / `ip_restriction_max_per_ip` before ReadyForVoting changes no authority verdict.
3. **The environment overrides the election:** `network_binding_strategy = 'none'` plus 7 voted codes from the IP → `verifyCode` still refuses (#3).
4. **Step 4 fails everyone when the environment is unset:** `MAX_USE_IP_ADDRESS` unset → step 4 refuses a fresh voter (#5).
5. **The start check never fires:** with no `step_1_ip` written by production, `resolveIpBlock` never blocks, even above the configured limit.
6. **Binding is inert:** a voter with no `voting_ip` passes #4 and #7 whatever the IP (characterizes PBDIGIT-234 dependence).

**Status:** Q3-D1, D2, D3, D6 decided; the characterization suite above is authorised as one RED step, then STOP. 2B stays unauthorised.

**Traceability:** ADR_20261005_0950 I3, I9 · PBDIGIT-239 / 240 / 243 / 244 / 234 · completion matrix §I3, §I9 · PBDIGIT-240 proposal §Q5, §Q12 · VE-15 · Phase 1 findings (env-unset refusal).
