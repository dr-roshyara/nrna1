# Situation — Voting Authorization, Security and the Final Vote (as-is, 2026-10-05)

**Date:** 2026-10-05 · **Type:** situation report / architecture discovery (read-only) · **Authority:** none — evidence and recommendation only; nothing authorised, nothing implemented
**Story context:** `PBDIGIT-213` (C4 committed as `9c482db85`) · follow-ups `PBDIGIT-220` (`7ea1dc06f`), `221`, `222` (`ef2af5c3b`)
**Code base:** analysed at commit `622ee91e9` + the then-uncommitted PBDIGIT-213 C4 working tree, since committed as `9c482db85`; the findings concern code that C4 did not change (cast path, codes, votes, IP/device)
**Method:** code trace (routes → middleware → controllers → services → models → migrations) by the author plus three read-only code searches. No file, migration, test or data was changed for this report.

> **Revision 2 (2026-10-05).** Added after the review of `docs/publicdigit/brainstorming/20261005_0615_voter_pre_check_authorization.md` and its follow-up review: anonymity findings A1–A2 (§8a), the three `has_voted` fields (§4a), the code-creation point (§3), D4 upgraded to FACT, D8–D9, and an updated target model (§9) and order of work (§11).

> **Revision 6 (2026-10-05).** PO decision on A5 recorded: severity ranking A1–A5, the two threat models, participation proof vs ballot retrieval, the constitutional invariant "participation knowledge without ballot knowledge" (§8b), and test K split into K1–K9 (§11a).

> **Revision 5 (2026-10-05).** **A5 (FACT): the vote's `vote_hash` can be recomputed from voter-side data plus configuration — a computable voter → ballot link.** Added to §1, §8, §8a, §9b, §11, §11a (test K) and §12 (assessment); the participation-vs-secrecy rule and the anonymity-boundary review checklist recorded (§8b).

> **Revision 4 (2026-10-05).** PO verdicts recorded (§12); anonymity invariant strengthened to cover identifiers, ordering and all observable metadata (§8a, §9b); reason renamed `AlreadyVotedPreCheck`; Phase 1 gains an explicit transaction-atomicity test and a UUID-structure test (§11a).

> **Revision 3 (2026-10-05).** Frozen-position review of `has_voted`: aggregate (not per-row) vote/code invariant, forwarded entitlement reasons and the authoritative "already voted" check, the cast order decided (compare-and-set before the vote insert), A4 (time-ordered vote IDs, FACT), D6 split into F1/F2, the consolidated invariant (§9b) and the Phase 1 scope (§11a).

> **Notation.** **FACT** = read in the code by the author, with citation. **REPORTED** = found by a read-only code search, not re-checked line by line (treat as strong evidence, confirm before relying on it for a change). **INFERENCE** = derived. **RECOMMENDATION** = architectural opinion, needs PO/ARB decision.

---

## 1. The situation in one page

| Question | Today's answer |
|---|---|
| May this voter **participate** in this election? | **Answered correctly** by `VotingEntitlement` (registered → voter → organisation → active → admitted), re-asked **fresh** at the final vote (FACT). |
| Has the voter met the **admission requirements**? | **Answered correctly**: `VoterAdmissionPolicy` + `AdmissionEvidence` → `AdmissionDecision`, ALL-of (FACT). Identity verification is the only requirement. |
| Is **this voting attempt** secure (IP / device)? | **Only partly.** A security decision exists at the final vote under the names *Trust / Legitimacy* (`TrustPolicyEvaluator` → `ConstitutionalLegitimacyDecision`), but the only thing it can block on is **too many votes from one IP** (FACT). Voter↔IP binding and device binding **do not work**. |
| Is the voter's IP trustworthy? | **No.** `trustProxies(at: '*')` lets the client set the IP through `X-Forwarded-For` (REPORTED). Until fixed, any IP binding has no security value. |
| What is `Code.can_vote_now`? | Intended: "the voting-code step is complete". In practice also used as eligibility wording, per-code timer, global end-of-voting switch and page gate (REPORTED). The final `store()` does **not** read it (FACT). |
| Has this voter voted? | `codes.has_voted` (one row per voter and election) is the real record. Two more `has_voted` fields exist; the one on `election_memberships` is **never written**, so the Voters page and newsletter audiences show voters as not having voted (FACT, §4a). |
| Can a vote be linked back to the voter? | **Yes — computably.** The vote's `vote_hash` is SHA-256 of the voter's code id, the election, the voter's plain-text code, the cast second and a configured salt — all kept on the voter side or in config, so the server can recompute it for every voted code and find the voter's ballot exactly (A5, FACT). In addition, the cast writes the voter's code and the vote with the same `now()` timestamps, vote ids are time-ordered, and a log line records the vote id with the IP (A1–A4, FACT, §8a). |

**Bottom line:** admission and standing entitlement are sound. Voting security is fragmented, mostly not enforced, and partly promised by the UI without enforcement. The final vote (`VoteController::store()`) is the right single enforcement point, but has defects of its own (§8). **Ballot secrecy is currently broken by A5** — a deterministic, computable voter → ballot link — and further weakened by correlation paths (A1–A4). These must be fixed before any new security evidence (IP binding) is added.

---

## 2. Concepts as they exist today

```
ADMISSION      VoterAdmissionPolicy + AdmissionEvidence → AdmissionDecision      (ALL-of)          ✅ sound
ENTITLEMENT    VotingEntitlementPolicy::standing()  [+ lifecycle in decide()]     (standing)        ✅ sound
SECURITY       TrustPolicyEvaluator → ConstitutionalLegitimacyDecision → LegitimacyOutcome       ⚠ only IP density blocks
PROCESS        Code: code1 match, can_vote_now, has_agreed_to_vote, timers, has_voted            ⚠ can_vote_now overloaded
CAST           VoteController::store()  (one transaction)                                         ⚠ defects, §8
```

- `VotingEntitlementPolicy::standing()` = standing only; `decide()` additionally ANDs `electionVotable` (lifecycle). Neither knows about security (FACT, `app/Contexts/Elections/Domain/Policies/VotingEntitlementPolicy.php`).
- Terminology in use for security: *Trust* (`TrustPolicyEvaluator`, `NetworkTrustEvidence`, `DeviceTrustContext`), *Legitimacy* (`ConstitutionalLegitimacyDecision`, `LegitimacyOutcome`), *Sovereignty* (divergence telemetry only), *Authorization* (`BallotAuthorizationPolicy`, `AuthorizationCode`, `BallotSession` — **unreferenced**, REPORTED).

---

## 3. The voting flow (real election)

**Route middleware** (`routes/election/electionRoutes.php:497,514,522`, FACT): `voter.slug.verify` → `voter.slug.window` → `voter.slug.consistency` → **`ensure.election.voter`** (entitlement, cached) → `voter.step.order` → `vote.eligibility` (lifecycle `canVote()` on slug requests) → `validate.voting.ip` (shadow mode) → `vote.organisation` → `throttle:10,1`; ballot/vote routes add `voting.active` (fresh lifecycle).

**Steps:**
1. Code create — `CodeController::create`. **The code is created and e-mailed here, on GET `code/create`**: `getOrCreateCode()` (`:123`) notifies `SendFirstVerificationCode` (`:741`, `:779`, `:845`) (FACT). POST `/code` is code *entry*, not creation. Any "no code is issued" check must therefore run before `getOrCreateCode()`.
2. Code entry — `store` → `verifyCode` (usable, match, 30-min expiry, per-IP count `:897-905`) → `markCodeAsVerified` sets `can_vote_now = true` (`:916`) (REPORTED).
3. Agreement — gated on `can_vote_now` (`:433`, `:563`); sets `has_agreed_to_vote` (REPORTED).
4. Ballot — `VoteController::create`; `isUserEligibleToVote()` = "a code with `can_vote_now` exists" (`:165-178`) (REPORTED).
5. First submission — `vote_pre_check` (`can_vote_now`, per-code timer) (REPORTED).
6. **Final vote** — `VoteController::store()` (§8).

Every slug step refuses a non-entitled voter before the controller runs, and revocation after the code step refuses the next step — pinned by `tests/Feature/Election/VotingProcessEntitlementBoundaryTest.php` (41/41) (FACT).

---

## 4. `Code.can_vote_now` — what it really does

| Use | Where (REPORTED) | Intended meaning? |
|---|---|---|
| Set true after code 1 verified | `CodeController::markCodeAsVerified:916` | ✅ yes |
| Gate for agreement pages | `CodeController:433, :563` | ✅ process step |
| "Eligibility" — wording *"You are not registered as a voter for this election."* | `VoteController::isUserEligibleToVote:165-178, :258-259` | ❌ eligibility wording |
| Per-code time window (set false on timeout) | `VoteController::vote_pre_check:2955, :2988` | ❌ timer |
| Global end-of-voting kill switch | `ElectionService::endVotingPeriod:143` | ❌ lifecycle |
| Kept **true** when code 1 is regenerated → `store():295` short-circuits "already verified" and skips verifying the new code | `CodeController::create:149-160` | ❌ possible process defect — confirm |
| Same name on `voter_slugs` (written, never gates) and a non-existent `users.can_vote_now` (`DeligateCodeController:55`, `ElectionPage.vue:257`) | — | ❌ legacy |
| Read in the final `store()` | — | **not read** (FACT) |

Decision on record (PO): `can_vote_now` = "the code-verification step has been completed"; not admission, not identity, not IP, not authorization; **not to be changed** in PBDIGIT-213.

## 4a. "Has voted" — three fields, one of them never written

| Field | Written by | Read by | Status |
|---|---|---|---|
| `codes.has_voted` | `VoteController::markUserAsVoted` in the cast transaction (`:1924-1941`) (FACT) | `store()` re-read, `CodeController` create/store, per-IP counts | **The real record.** `codes` has `unique(election_id, user_id)` (`2026_03_05_000007_create_uuid_codes_table.php:53`, FACT) → one code per voter per election, so `codes.has_voted` = "this voter has voted in this election". |
| `voter_slugs.has_voted` | `store()` after the vote (REPORTED) | — | projection |
| `election_memberships.has_voted` + `voted_at` (`2026_03_22_000001`) | **nobody** — `ElectionMembership::markAsVoted()` has no caller (FACT) | Voters page (`Pages/Elections/Voters/Index.vue:131, 207, 221`); `NewsletterService.php:215, 236` | **Defect (D9):** after a real vote the Voters page still shows the voter as not having voted and still offers verify/suspend actions; "voted / not voted" newsletter audiences are wrong. |

**Agreed classification (PO discussion, 2026-10-05):** `Code.has_voted` is a **process-completion fact**, not an authorization. It never participates in Admission, Entitlement or Security, never substitutes for them, and is never stored as "authorized". It *is* a process fact that the final cast must check — and because it enforces "one voter, one vote", it must be read and set **atomically in the transaction that writes the vote**. It is the one source of truth for "has voted"; the other two fields are projections and never decide.

---

## 5. IP — stored vs displayed vs evaluated vs blocking

| Mechanism | Stored | Displayed | Evaluated | **Blocks a real vote?** |
|---|---|---|---|---|
| Per-IP vote count, trust gate (`NetworkBindingPolicy`; `elections.max_votes_per_ip` default 6 → effective limit 3 as "Unverified") | `codes.client_ip` | — | `VoteController::store:1483-1556` | **Yes**, at final submit (`:1522-1555`, FACT) |
| Per-IP count at code entry (`config('app.max_use_clientIP')`, fallback 7) | `codes.client_ip` | — | `CodeController::verifyCode:897-905` | **Yes**, at code entry (REPORTED) |
| Per-IP count in `vote_post_check` | `codes.client_ip` | — | `VoteController::verify` (GET) | yes in `verify()`; **result ignored in `store()`** (`:1726`, FACT) |
| Election-start IP limit (`elections.ip_restriction_enabled/max_per_ip`) | `voter_slugs.step_1_ip` | `Election/Show.vue` | `ElectionVotingController::resolveIpBlock` | **No** — `step_1_ip` is never written, count always 0 (REPORTED) |
| Voter↔IP binding `users.voting_ip` (`ValidateVotingIp`, `validateVotingIpWithResponse`, `VotingSecurityService`) | **column does not exist** in any active migration (FACT) | `Voter/IndexVoter.vue` | yes, always null | **No**; `ValidateVotingIp` is additionally in shadow mode (`config/voting_security.php:108` `constitutional_mode` = true, FACT) |
| `voter_verifications.verified_ip` | yes | identity UI (audit metadata) | **never at vote time** | No |
| `elections.voter_verification_mode` (none / ip_only / fingerprint_only / both) | yes | Settings: *"Voter must vote from the pre-verified IP address"* | `Election::checksIp()/checksFingerprint()` have **no callers** (FACT) | **No** — UI promises enforcement that does not exist |
| `elections.ip_whitelist` | yes | Settings | `TrustPolicyEvaluator.php:140` `json_decode()` on an array-cast value | **Likely TypeError** when set (REPORTED) |
| `elections.ip_mismatch_action`, `voting_ip_mode` | yes | settings service | not read by enforcement | No (REPORTED) |

**Three overlapping per-IP count mechanisms** with different sources and defaults (7 / 0 / 6) (REPORTED).

### Client-IP reliability (REPORTED)
- `bootstrap/app.php:31` `trustProxies(at: '*')` → `$request->ip()` follows the client-supplied `X-Forwarded-For` unless the edge proxy overwrites it. `app/Http/Middleware/TrustProxies.php` is not registered; `voting_security.trust_proxies` has no effect.
- IPv4-only handling: `'verified_ip' => 'nullable|ipv4'`, IPv4 regexes in `VoterVerificationModal.vue` / `IdentityAttestationDialog.vue`, `ip2long` CIDR matching in `Election::ipInRange`; no IP normalisation anywhere (IPv6 spellings and IPv4-mapped forms compare unequal, also in `TrustEvidencePrivacyPolicy::hashIp`).
- `users.current_ip` is written only at login (`LoginController.php:71`); `voter_slugs.step_N_ip` and `voter_slug_steps.ip_address` are never written.

---

## 6. Device — stored vs enforced (REPORTED)

- `codes.device_fingerprint_hash` / `votes.device_fingerprint_hash` columns exist; nothing writes them.
- No voting page sends `device_fingerprint`.
- `TrustPolicyEvaluator::buildDeviceContext` yields NotRequired / ExactMatch without comparing; `DeviceBindingPolicy` produces no finding; `elections.device_binding_strategy` (default `none`) is not read by the policy.
- **Device binding: never blocks.** Not trustworthy enough to be a domain concept today.

---

## 7. Fresh vs cached

| Decision | Navigation (middleware) | Final vote (`store()`) |
|---|---|---|
| Entitlement (incl. admission) | cached (TTL 300 s; key contains the policy fingerprint; invalidated by `ElectionMembership` / `VoterVerification` model events) | **fresh, uncached, in transaction** (FACT) |
| Lifecycle `canVote()` | fresh | fresh (FACT) |
| Trust / legitimacy (IP density) | — | fresh (FACT) |
| `has_voted` | — | re-read (REPORTED) |

Gap: `EloquentVoterRepository::bulkInsert` writes memberships with `DB::table()->insert`, which fires no model events → no cache invalidation (REPORTED).

---

## 8. The final vote — `VoteController::store()` (`:1463-1917`)

**Order (REPORTED, key lines FACT):** `beginTransaction` → trust/legitimacy gate (deny → rollback) → lifecycle `canVote()` → entitlement fresh (`ensureVoterMembership(..., false, true)`) → load Code → cache lock on the voter slug → `has_voted` re-read → IP helper → code 1 match → vote insert (no `user_id`) → `markUserAsVoted` → commit.

**Defects observed (not fixed; candidates for backlog):**

| # | Finding | Status |
|---|---|---|
| D1 | `vote_post_check()` result `$pre_check` is never read (`:1726`) | FACT |
| D2 | `validateVotingIpWithResponse()` denial returns **without `DB::rollBack()` and without releasing the slug lock** (`:1670-1673`) | FACT (today unreachable: `voting_ip` is always null) |
| D3 | `trackSovereigntyDivergence(?int $userId)` receives UUID strings on the denial branches → likely `TypeError`, not caught by `catch (\Exception)` | signature FACT; runtime effect **unverified** |
| D4 | No database row lock anywhere in the vote path (no `lockForUpdate` / `sharedLock` in `VoteController` or `CodeController`); double-vote protection = `Cache::lock` on the voter slug + `has_voted` re-read. A cache lock fails open if the cache is flushed or not shared between servers | FACT |
| D5 | `has_agreed_to_vote` and the per-code timer are not re-checked in `store()`; `slug.vote.store` is not in `config/election_steps.php`, so step order passes it through | REPORTED |
| D6 | `can_vote_now` kept true on code 1 regeneration (§4): `CodeController::store` then treats the voter as already verified and **skips verifying the new code** (`:149-160`, `:295`). Not to be confused with "an already-voted voter obtains a new code" — both are tested separately (§11a, F1/F2) | REPORTED |
| D7 | `ip_whitelist` `json_decode` TypeError (§5) | REPORTED |
| D8 | Anonymity weaknesses in the cast transaction (§8a, A1–A5); **A5 is a computable voter → ballot link** | FACT |
| D9 | `election_memberships.has_voted` never written (§4a) | FACT |

## 8a. Anonymity of the cast transaction (constitutional invariant: no voter↔vote linkage)

`save_vote()` deliberately sets no `user_id` on the vote (FACT, `VoteController.php:2703-2704`). But a vote can still be **joined** to its voter across stores:

| # | Correlation | Evidence | Status |
|---|---|---|---|
| A1 | **IP**: `save_vote()` logs `vote_id` together with `request()->ip()` and a timestamp to the `voting_audit` channel; `codes.client_ip` stores the same IP per voter | `VoteController.php:2815-2822`; `CodeController.php:770, 919` | FACT |
| A2 | **Time**: in the same request the voter's `codes` row gets `vote_completed_at = now()` and `code_to_save_vote_used_at = now()`, while the vote row gets `cast_at = now()` (and `created_at`). A timestamp join between `codes` (one row per voter) and `votes` links them even without any IP | `VoteController::markUserAsVoted:1924-1941`; `BaseVote.php:219-220` | FACT |
| A3 | Other same-transaction writes with voter-side timestamps: `voter_slugs` status `voted`, receipt code creation, the notification e-mail | `store()` `:1793-1840` | REPORTED — to be audited |
| **A5** | **Computable link (worst).** `save_vote()` sets `vote_hash = sha256(code.id ‖ election.id ‖ code.code_to_open_voting_form ‖ castAt->timestamp ‖ config('app.vote_salt'))`. Every input is kept on the voter side or in config: the voter's own code row (one per voter and election); the code value, stored in plain text on `codes` (compared in upper-case plain text, never cleared after voting — `markUserAsVoted` only marks it unusable); the cast time in whole seconds, the same `now()` second written to `codes.vote_completed_at` (A2); and the configured salt. **Anyone with database read access and the application config can recompute each voted code's hash and match `votes.vote_hash` exactly** (trying ±1 second at most). This is not a correlation risk but a deterministic voter → ballot link that the system can always compute | `VoteController.php:2720-2730` (`save_vote`); `codes.code_to_open_voting_form` (`2026_03_23_170035_add_demo_code_columns_to_codes_table.php`, `app/Models/Code.php:35`); `VoteController::verify_submitted_code:4100`; `markUserAsVoted:1924-1941`; `votes.vote_hash` (`2026_03_05_000011_create_uuid_votes_table.php:16`) | FACT |
| A4 | **Identifiers carry time**: `BaseVote` and `Code` use Laravel `HasUuids`, whose `newUniqueId()` returns `Str::orderedUuid()` — a time-ordered UUID with the creation time encoded in it. Even with `cast_at` and the code timestamps removed, a vote's own id carries its write time, and id order is cast order | `app/Models/BaseVote.php:32`; `app/Models/Code.php:15`; `vendor/laravel/framework/src/Illuminate/Database/Eloquent/Concerns/HasUuids.php:16-18` | FACT |

**Consequence.** The anonymity problem is the **whole cast transaction** — fields, logs, timing, identifiers (A4: random, non-time-ordered ids for votes) **and derived values (A5)** — not one log line. **A5 is the highest priority (#0):** it is a present, exact linkage, not a hardening item.

**A5 — direction (RECOMMENDATION, not adopted, no implementation now):** no ballot-side value may be derivable from anything stored on the voter side or in configuration. For example: a random receipt secret held only by the voter (the server stores only its hash, unlinked to the code), and the code value cleared after use. The existing "view my vote" feature (`VoteController::submit_code_to_view_vote`, `verify_to_show`) depends on the current hash and must be characterized together with A5. Receipts (`ReceiptCode::firstOrCreate` in the cast, the notification e-mail) are examined in the same test (A3). Fixing it is a precondition for adding any new security evidence (IP binding), and it does not depend on the authorization ADR. Any future write of `election_memberships.voted_at` (D9) must not create a new correlatable timestamp.

**Invariant to record (stronger than "no voter identity in the vote row"; PO wording, 2026-10-05):** *No voter-associated record, identifier, timestamp, log entry, notification, ordering information, or other observable metadata may provide a practical correlation path to an anonymous vote.* Identifier generation (A4) is therefore part of the anonymity architecture: if Phase 1 confirms that vote ids reveal ordering or timing, random (v4) or another anonymity-safe identifier strategy becomes a **required architectural change**, not an optimisation.

**Severity (PO, 2026-10-05):**

| ID | Finding | Severity |
|---|---|---|
| **A5** | `vote_hash` deterministically computable from voter-side data and configuration | **Critical — highest priority** |
| A1 | voter-side ↔ ballot logs (vote id + IP) | High |
| A2 | timestamp correlation (code vs vote) | High |
| A3 | receipt / notification linkage | High (to be characterized) |
| A4 | time-ordered vote identifiers | Medium–High |

## 8b. Participation vs ballot secrecy — the anonymity boundary (PO discussion, 2026-10-05)

**Constitutional invariant (PO, proposed for the ADR):** *PublicDigit preserves participation knowledge without preserving ballot knowledge. The system may know that a voter has voted, but it must not possess any deterministic mechanism that maps the voter's identity to the anonymous ballot.* It is not sufficient that `Vote` contains no voter identifier: **no party with access to server-side voter data, logs and the application configuration may be able to derive which anonymous vote belongs to a voter.**

**Rule:** *the system may know **whether** a voter has voted, but must not be able to determine **which ballot** that voter cast.*

**Two threat models — both apply:**

| Threat model | Adversary has | Today |
|---|---|---|
| External observer | the public system, a voter's own credentials or receipts | depends on "view my vote" and receipts (A3, K2–K5) — to be characterized |
| System / database operator | database + application configuration + logs | **link present (A5)**, plus A1, A2, A4 |

A5 matters most under the **operator** model: the application itself can reconstruct the voter → ballot relation. For a constitutional election platform this outweighs merely hiding `user_id` from outsiders.

**Participation proof ≠ ballot retrieval.** *"I can prove that I participated"* is compatible with ballot secrecy; *"I can retrieve the exact ballot I cast"* creates a much stronger identity ↔ ballot link (and, under the external model, a coercion / vote-selling risk). The existing **"view my vote"** feature (`submit_code_to_view_vote`, `verify_to_show`) is of the second kind. It is **not** removed now: Phase 1 first establishes exactly what it exposes and why it exists.

```
IDENTITY / PARTICIPATION                 ║          BALLOT
User → Code → has_voted                  ║          Vote (anonymous)
participation audit (voter-side events)  ║          ballot events (no voter data)
                       ╳ no stored relation, no computable relation, no correlation ╳
```

- **Never** add `user_id`, `code_id`, voter id or membership id to `Vote`.
- **Logs:** a voter-side event (e.g. "voting completed" for a user) and a ballot-side event (e.g. "anonymous ballot accepted") are separate events and **never** share fields (A1).
- **No precise voter-side "voted at" time.** If participation time is needed, design a coarse, privacy-preserving one deliberately.
- **Ballot identifiers** random and not time-correlated (A4); voter-side ids may stay as they are.
- **The boundary is a review concept, not a software class.** It is reviewed whenever someone proposes: a new field on `Vote`, a vote log, a receipt, a notification, a timestamp, an identifier, analytics/telemetry, audit events, tracing, monitoring, or database relations.
- **Cryptographic unlinkability** (blind signatures, anonymous credentials, unlinkable voting tokens) is a possible future, threat-model-driven step — **not now**.

---

## 9. Recommended conceptual boundary (for the ARB — not adopted)

```
ADMISSION      "has the voter met the requirements?"     AdmissionDecision           (exists)
ENTITLEMENT    "may this voter participate?"              VotingEntitlement           (exists; stays STANDING — never absorbs security)
SECURITY       "is THIS attempt acceptable?"              VotingSecurityDecision      (exists as Trust/Legitimacy; ONE authority — rename/restructure only after an ADR)
AUTHORIZATION  one composition, two authoritative boundaries:
  forEntry()   "may this voter enter / continue the voting process?"   = entitlement(fresh, incl. admission once) ∧ lifecycle ∧ security
               used before getOrCreateCode() on GET code/create, and at code entry (REPLACES verifyCode's own IP check)
  forCast()    "may this voter cast NOW?"                              = forEntry() (all fresh) ∧ process facts
               (code match, agreement completed*, has_voted = false) — evaluated in the cast transaction
CAST           begin transaction → forCast() (fresh reads) → compare-and-set on codes.has_voted (exactly 1 row, else deny)
               → insert vote → commit;   any denial or \Throwable → rollback (has_voted returns to false)
```
\* `store()` does not check `has_agreed_to_vote` today (D5) — adding it is a deliberate, tested behaviour change.

### Agreed refinements (review of the brainstorming document, 2026-10-05)

| # | Refinement | Status |
|---|---|---|
| R1 | **The database is the concurrency authority.** Consume `codes.has_voted` with an atomic compare-and-set (`UPDATE … SET has_voted = true WHERE id = ? AND has_voted = false`, exactly one row changed) **before** inserting the vote, in the same transaction. The `UPDATE` holds the row lock until commit: a concurrent second transaction waits, then changes 0 rows and writes nothing; if the vote insert fails, rollback restores `has_voted = false`. (Insert-first would also be safe after rollback but does needless work and complicates the failure path.) The cache lock may stay only as an optimisation | RECOMMENDATION — order decided |
| R2 | **Fail closed.** Any denial *or* exception (`\Throwable`) in the cast path ends in rollback, lock release, no vote and an audited reason | RECOMMENDATION — invariant |
| R3 | **"No code issued"** means the entry check runs before `getOrCreateCode()` on GET `code/create` (§3). A code issued earlier stays harmless because `forCast()` re-checks | RECOMMENDATION |
| R4 | **One security authority** (the existing Trust/Legitimacy decision); fold the three per-IP count mechanisms into it; **no rename or parallel class before an ADR** | PO position |
| R5 | `forEntry()` / `forCast()` return a **derived result** — a reason enum (e.g. `CastAuthorizationReason`) with `Allowed` as the only success, never an `authorized` boolean plus a separate reason (they could contradict); never cached, never stored. The enum **forwards the existing `VotingEntitlementReason`** (`NotRegistered`, `NotVoterRole`, `OrganisationMismatch`, `NotActive`, `NotAdmitted`, `ElectionNotVotable`) instead of collapsing it into one `NotEntitled`, and adds security and process reasons. **`AlreadyVotedPreCheck` from `forCast()` is a pre-check only — the authoritative "already voted" answer is the compare-and-set result (0 rows changed → abort).** Working vocabulary: `Allowed` · `NotRegistered` · `NotVoterRole` · `OrganisationMismatch` · `NotActive` · `NotAdmitted` · `ElectionNotVotable` · `SecurityDenied` · `AlreadyVotedPreCheck`. **No new authorization field on `Code`** (`can_cast`, `is_authorized`, `security_passed`, `vote_authorized`, …) | PO position |
| R6 | A **trustworthy client IP is a hard precondition**: the IP-binding policy must not be switchable on until trusted proxies are fixed and verified, IPs are normalised, and a matching rule exists | RECOMMENDATION |
| R7 | **Security policy locked from ReadyForVoting**, like the admission policy | RECOMMENDATION |
| R8 | **Anonymity first** (§8a): fix A1–A3 before adding any new security evidence | RECOMMENDATION — high priority |
| R9 | **Early checks are advisory.** The same `forEntry()` may warn at ballot entry / first submission; only `forCast()` decides | RECOMMENDATION |
| R10 | `Code` stays a **process-state model** (`can_vote_now`, agreement, `has_voted`); `has_voted` is a process-completion fact and the single source of truth for "has voted" (§4a) | PO position |

### 9b. Consolidated invariant (proposed for the ADR)

> `Code.has_voted` is the authoritative process-completion fact for the voter's unique code in an election; it is not an authorization. Cast authorization is derived fresh and returned as a reason (`Allowed` the only success). In one transaction, after `forCast()` allows, the code is consumed by an atomic compare-and-set (exactly one row false → true) and only then is the vote written; any denial or error rolls both back. **Per election, committed votes equal consumed codes, and every committed vote corresponds to exactly one false → true transition of `Code.has_voted` in the same transaction.** Other `has_voted` representations are projections and never decide. No voter-associated record, identifier, timestamp, log entry, notification, ordering information, or other observable metadata may provide a practical correlation path to an anonymous vote, **and no ballot-side value may be computable from voter-side data or configuration (A5).**

**Why the vote/code invariant is aggregate, not per row:** a vote is deliberately not linked to a code (no `user_id`, no code reference). "Vote exists ⇔ this code's `has_voted`" cannot be checked per code — and must not be checkable, or anonymity is broken. The testable form is: per election, `count(votes) = count(codes WHERE has_voted)`, and no vote is committed without exactly one code moving false → true in the same transaction.

- **RECOMMENDATION:** no new authorization *layer*, aggregate or engine — one application-level composition with the two operations above; never cached, never persisted (no `security_passed` / `is_verified` flags).
- **RECOMMENDATION:** a `VotingSecurityPolicy` value object (election settings: IP binding on/off and mode, IP density limit, device binding), **locked from ReadyForVoting** like the admission policy.
- **RECOMMENDATION — IP binding (PBDIGIT-222):** belongs to **security, not admission**; a per (election, voter) binding of a **server-observed IP from the voter's own request**, confirmed by the Chief/Deputy (audited); evaluated fresh at the final vote; an IP change denies and requires an audited re-bind. Prerequisites: trusted-proxy fix, IP normalisation (IPv6), a matching rule (exact vs prefix), removal of IPv4-only validation.
- Device binding: deferred until an evidence source exists that the client cannot trivially forge.

---

## 10. Open questions (PO / ARB)

1. Name and home of the security decision: extend *Constitutional Trust / Legitimacy*, or rename to *VotingSecurity*?
2. One consolidated security policy, or keep the scattered election columns (`voter_verification_mode`, `network_binding_strategy`, `max_votes_per_ip`, `ip_restriction_*`, `ip_mismatch_action`, `voting_ip_mode`)?
3. IP binding semantics: exact IP, prefix, or "first IP after binding"? Policy for mobile / CGNAT / IPv6 privacy addresses?
4. Where is the voter's IP observed for binding — at login, or by a voter action during the committee call?
5. Lock the security policy from ReadyForVoting?
6. Trusted proxies: ops/infra decision to fix first?
7. Consolidate the three per-IP count mechanisms (touches `PBDIGIT-45`)?
8. What should the Settings page say about `voter_verification_mode` while it is not enforced?
9. Do demo elections fall under `forEntry()` / `forCast()`, or stay outside (they bypass most checks today)?
10. Anonymity fix for A2: coarser timestamps, no voter-side completion timestamps, or decoupled write timing?
11. Should `election_memberships.has_voted` (D9) be written in the cast transaction (anonymity risk, §8a), derived from `codes.has_voted`, or removed?

## 11. Recommended order of work (none authorised)

| Phase | Content | Production change? |
|---|---|---|
| 0 | **Finish PBDIGIT-213 C4:** update guide `04_c4_…` (policy-OFF correction, "Identity verified"); **re-run the C4 regression** (the read model changed after the 89-suite run); commit C4 and PBDIGIT-220 | C4 only |
| 1 | **Characterization tests** — full scope in §11a (A–I): concurrency, code consumption, source of truth, projection gap (D9), anonymity correlation (A1–A4), F1/F2 (D6), final denial (D2, D3), aggregate invariant, ignored `vote_post_check` (D1) | no |
| 2 | **Anonymity correction** — **A5 first** (computable link), then confirmed A1–A4; moved early: live on every real vote, independent of the ADR | yes (own story) |
| 3 | **ADR "Voting authorization boundary"**: §9, R1–R10, answers to §10 | no |
| 4 | **Harden the final boundary**: atomic compare-and-set (R1), fail-closed (R2), confirmed D-items | yes |
| 5 | **Entry authorization** `forEntry()` before `getOrCreateCode()` (R3), reusing the one security authority | yes |
| 6 | **Infrastructure prerequisites**: trusted proxies, IP normalisation, matching rule, IPv6-capable validation | yes (ops + code) |
| 7 | **PBDIGIT-222** — IP binding as a security policy | yes |

Backlog items are needed for the confirmed defects (trusted proxies, D1–D9, `step_N_ip` never written, A1–A5 — A5 with the highest priority).

### 11a. Phase 1 scope — characterization only (no production change)

| # | Area | What the tests establish |
|---|---|---|
| A | Double-vote concurrency | two concurrent casts → exactly one vote |
| B | Code consumption (compare-and-set) | from `has_voted = false`, exactly one transaction can change it to true |
| B2 | Transaction atomicity | the code is consumed but the vote insert fails → complete rollback, `has_voted` stays false; no committed `has_voted` without a vote |
| C | Source of truth | `codes.has_voted` decides; `voter_slugs` / `election_memberships` never decide |
| D | Projection defect | `election_memberships.has_voted` stays stale after a real vote (D9); all consumers identified (Voters page, newsletter audiences) |
| E | Anonymity correlation | none of these allows joining a voter's records to their vote: `votes.cast_at` / `created_at`; code timestamps (`vote_completed_at`, `code_to_save_vote_used_at`); `voter_slugs` timestamps; membership timestamps; IP (`codes.client_ip` vs the `voting_audit` log); receipt identifiers; notification timing; **vote id order (A4)** |
| F1 | Already voted | an already-voted voter cannot obtain a usable code |
| F2 | Code regeneration (D6) | after code 1 is regenerated, the new code must be verified before the voter can proceed |
| G | Final denial | every denial and exception → no vote, no committed `has_voted`, rollback, lock released (D2, D3) |
| H | Aggregate invariant | per election, `count(votes) = count(codes WHERE has_voted)` after successful and failed casts |
| I | Ignored pre-check | the `vote_post_check` result is not used by `store()` (D1) |
| K | **Voter-to-ballot non-derivability** (A5) | Given complete voter-side database data, code data, election data, application configuration and the server-side secrets available to the application, establish whether the system can **deterministically** determine which `Vote` belongs to a voter. Today expected to **confirm** the link (known gap). Sub-tests: **K1** `vote_hash` derivation · **K2** view-my-vote (`submit_code_to_view_vote`, `verify_to_show`) · **K3** receipt generation (`ReceiptCode::firstOrCreate` in the cast) · **K4** receipt lookup · **K5** notification links / e-mail content · **K6** timestamps (A2) · **K7** UUID ordering (A4, see J) · **K8** logs / tracing (A1) · **K9** database identifiers |
| J | UUID structure (A4) | votes created at T1 < T2 get ids A, B: do the ids sort in creation order, and can an observer recover the creation time from them? Established **before** any identifier change |

Characterization tests pin **current** behaviour: where the current behaviour is the defect (e.g. E, D, F2), the test documents it as a known gap (marked as such), so the fix in a later phase turns it into the target assertion.

## 12. PO verdicts (2026-10-05)

| Proposal | Verdict |
|---|---|
| Aggregate vote/code invariant (§9b) | **Approved** |
| Preserve the precise entitlement reasons in the cast reason | **Approved** |
| Compare-and-set before the vote insert (§9, R1) | **Approved — frozen** |
| Atomic rollback of code consumption and vote | **Approved** |
| Ordered-UUID anonymity finding (A4) | **Investigation strongly approved** (Phase 1, test J) |
| Characterization tests (§11a) | **Approved** |
| Fix production code now | **No** |
| Start IP-binding implementation (PBDIGIT-222) | **No** |
| New persisted authorization field | **No** |
| UUID / identifier change | **Not yet** — only after Phase 1 establishes the finding |

The cache lock may be kept if it serves another purpose, but it is **not** the constitutional guarantee against double voting.

### Security assessment (as of revision 5)

| Property | Assessment |
|---|---|
| Voter ↔ ballot **stored** relation | none (no `user_id` / code id on `Vote`) — good |
| Voter ↔ ballot **computable** relation | **present (A5)** — ballot secrecy broken until fixed |
| One vote per voter | strong after the compare-and-set / transaction hardening (R1); today cache lock + re-read (D4) |
| Admission separation | strong |
| Entitlement separation | strong |
| Security / authorization separation | good, being hardened |
| Ballot anonymity | **broken by A5**; correlation paths A1–A4 to remove |
| Operational anonymity (logs, receipts, notifications, timing) | needs the Phase 1 audit |
| Cryptographic unlinkability | not present — future, threat-model decision |
| High-assurance verifiability | future |

**PO decision on A5 (2026-10-05):** approved — record A5, the rating and test K (K1–K9); characterize view-my-vote, receipts / notifications and ordered UUIDs in Phase 1. **Not now:** no change to production behaviour, no random-secret implementation, view-my-vote not removed.

**Priority:** #0 A5 · #1 atomic `has_voted` compare-and-set with anonymous vote creation · #2 complete voter → vote correlation audit (A1–A4) · #3 random, non-time-correlated ballot identifiers — each characterized in Phase 1 before any change.

**Traceability:** PBDIGIT-213 (C2–C4) · ADR_20261004_1549 · `VotingProcessEntitlementBoundaryTest` · PBDIGIT-222 (IP verification, legacy behaviour recorded) · PBDIGIT-45 (votes-per-IP ownership) · PBDIGIT-91 (registered device fingerprint) · brainstorming `20261005_0615_voter_pre_check_authorization.md` and `voter_management/20261005_0616_Claude's architecture review.md` (reviewed 2026-10-05; positions recorded in §9).
