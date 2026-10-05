# Phase 5 Round 2 — Entry Authorization Boundary (`forEntry()`)

**Date:** 2026-10-05 · **Story:** `PBDIGIT-239` · **Status:** 🟡 **BLUEPRINT — for review. Documentation only:** no production code, no test, no migration. RED not authorised.
**Decision basis:** ADR `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (APPROVED, frozen) · Phase 5 charter `docs/plans/20261005-1442-phase5-discovery-charter-plan.md` (Round 2 = entry authorization, `forEntry()` as candidate) · R0 blueprint `2026-10-05-codecontroller-refactoring-blueprint-v1.md` · plan `docs/plans/20261005-1851-phase5-round2-forentry-plan.md`.
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering proposes; the PO / ARB decides.
**Evidence labels:** [S] read in source by this session · [S\*] read by a review sub-agent, spot-checked where marked · [I] inference.

---

## 1. Purpose & scope

Give the **entry into the voting journey** — the code step in `CodeController` — one authoritative, fresh decision: *"may this voter enter or continue the voting process now?"* (ADR §3.1). Today a code is created and e-mailed (`getOrCreateCode()`) before any fresh decision, and eligibility is spread over cached middleware, a session-flag-dependent lifecycle check, a voter-slug shortcut and an IP count inside code verification (§5).

**In scope:** the `forEntry()` contract; the E2 Security Verdict Authority extracted from the final cast and shared by both consumers; the two `CodeController` call sites (`create()`, `store()`); the RED strategy. **Out of scope:** §11.

**PO decisions (2026-10-05):** Option 1 — **one Security Verdict Authority**, consumed by `forEntry()` (CodeController) and `forCast()` (VoteController), staged **A** extract → **B** CodeController → **C** VoteController (separate, behaviour-preserving; not a reopening of Phase 4) · `verifyCode()`'s own IP count is removed only in slice **2B after Q3**.

## 2. ADR alignment

| ADR element | How this design follows it |
|---|---|
| §3.0 three concepts | `forEntry()` = **authorization only**; process validation (code value, code step, code validity) and atomic participation consumption (CAS) stay outside it (§3a) |
| §3.0 `VotingSecurityDecision` = a **role** | fulfilled by the existing Trust → Legitimacy chain; the extracted adapter (§3) wraps it — **no new security engine**, Q1 may only rename / relocate |
| §3.1 `forEntry()` | composition E1 → E2, first failure wins; call sites GET `code/create` **before** `getOrCreateCode()` and POST code entry; not used for the final decision |
| §3.2 `forCast()` | unchanged; receives the same E2 fact from the same authority after Stage C |
| §3.3 reasons | `EntryAuthorizationReason` = the authorization subset of the reason family (`Allowed` only success) |
| §3.5 fresh vs cached | navigation guards may stay cached (`ensure.election.voter`); `forEntry()` is **fresh** (`decide()` is uncached even in the cached binding [S\*]) |
| I2 | admission evaluated once — **inside** `VotingEntitlement.decide()`; never restated |
| I3 | one security authority — the IP count in `verifyCode()` is a duplicate to be retired (2B), not composed |
| I6 | "already voted" is decided only by the CAS at the final cast; `forEntry()` never decides it |
| Q2 | resolved: `Deferred` / `Investigate` → deny (`SecurityReviewRequired`) |
| Q4 | settled for this path by PBDIGIT-238 R1: CodeController serves real elections only (403 otherwise) |

## 3. Canonical `forEntry()` contract

```
VotingAuthorization::forEntry(EntryAttempt $attempt): EntryAuthorization

EntryAttempt            final readonly DTO   voterId, electionId, security: SecurityVerdict
EntryAuthorization      DTO                  reason: EntryAuthorizationReason · isAllowed()
EntryAuthorizationReason  Domain enum (9)    Allowed · NotRegistered · NotVoterRole · OrganisationMismatch · NotActive
                                             · NotAdmitted · ElectionNotVotable · SecurityDenied · SecurityReviewRequired
                                             fromEntitlement() — values identical to CastDecisionReason's authorization subset

Composition (fixed order, first failure wins):
  E1  VotingEntitlementQuery::decide(voterId, electionId)   — exactly once, never standingOf()
  E2  attempt->security                                      — Denied → SecurityDenied · ReviewRequired → SecurityReviewRequired
  → Allowed
```

**E2 is an authoritative fact, not a caller's opinion (review amendment 1).** `VotingAuthorization` is the *authorization decision boundary*; `VotingSecurityVerdictQuery` is the **only** provider of the E2 fact. Passing the `SecurityVerdict` in — exactly as `forCast()` already does with `CastAttempt::$security` [S] — keeps `VotingAuthorization` a pure, stateless composition and creates **no second security authority**. Callers **must not manufacture** a verdict; they obtain it only from the port. `forEntry()` is therefore never "E1-only".

**The one Security Verdict Authority (Stage A):**

```
VotingSecurityVerdictQuery            Application port   verdictFor(SecurityAttemptContext): SecurityVerdict
SecurityAttemptContext                DTO                userId, electionId, client IP, device fingerprint, session id
TrustLegitimacySecurityVerdict        Infrastructure     MOVES (does not rewrite) today's inline assembly from
                                      adapter            VoteController::store() 1486–1512 and the mapping of
                                                         securityVerdictOf() 1985–1993 [S*]; reuses TrustPolicyEvaluator
                                                         (container singleton) and ConstitutionalLegitimacyDecision
```

Names / homes are provisional pending **Q1** (rename / relocation only). Registered-IP evidence keeps today's value (`users.voting_ip` has no column → always null) — **PBDIGIT-234 is not reintroduced**.

## 3a. Process guards outside authorization

| Guard | Nature | Where | Status |
|---|---|---|---|
| Code validity window | process validation | `verifyCode()` expiry; `create()` / `getOrCreateCode()` regeneration | **R1-F6a** decision open — not touched |
| Code value / usable / code step | process validation | `verifyCode()`, `can_vote_now` (code-verification state, not authorization) | unchanged; R4 semantic API later |
| Early `has_voted` | early process guard, **non-authoritative** | four guards in CodeController (unconditional since R1) | unchanged |
| Final already-voted | **authoritative** | CAS `codes.has_voted false → true` at the final cast (Phase 4 E4) | unchanged |
| Ballot validity | `ValidatedBallot` | final cast, VE-01 / PBDIGIT-237 | separate story |

## 4. Entry authorization matrix

| Component | Question | Authority | Reasons | Fresh? | Today at the code step |
|---|---|---|---|---|---|
| **E1 — Voting Entitlement** | registered, voter role, organisation, active, admitted (identity verification per policy), election votable now? | `VotingEntitlementQuery::decide()` = standing (incl. admission, I2) ∧ `ElectionLifecycle::canVote()` [S\*] | NotRegistered · NotVoterRole · OrganisationMismatch · NotActive · NotAdmitted · ElectionNotVotable | yes | only as cached **standing** (`ensure.election.voter`) + lifecycle in `vote.eligibility` (skipped with the demo session flag — F-C2); **no fresh `decide()`** |
| **E2 — Voting Security Decision** | is this attempt acceptable under the election's security policy? | Trust → Legitimacy chain → `SecurityVerdict` via the one adapter | SecurityDenied · SecurityReviewRequired | yes | **not evaluated**; a different, duplicate IP count runs inside `verifyCode()` |

## 5. Current implementation mapping

| Site | What it decides today | Classification (§6) |
|---|---|---|
| `voter.slug.verify` / `voter.slug.consistency` | slug owner / active; election + organisation consistency; sets `election` [S] | context — keep |
| `ensure.election.voter` (`EnsureElectionVoter`) | `isVoterInElection()` → **cached `standingOf()`**; demo bypass [S] | navigation guard (legacy-acceptable, §3.5); Q6 decides delegation |
| `vote.eligibility` (`VoteEligibility`) | early return when `session('selected_election_type') === 'demo'`; with a slug: `ElectionLifecycle::canVote()` [S] | navigation guard with a **defect** (F-C2); E1 supersedes it at the code step |
| `vote.organisation` | organisation security [S\*] | context — keep |
| `CodeController::getElection()` | context + **real-election boundary** (R1) [S] | keep (context) |
| `CodeController::isUserEligible()` | demo bypass removed (R1); slug → true; `users.can_vote` [S] | legacy — superseded by E1; retire in R3 |
| `CodeController::create()` | session write (65), Code read (75), `getOrCreateCode()` + e-mail **without fresh decision** [S] | call site #1 |
| `CodeController::store()` | validation (249) → Code lookup (268) → `verifyCode()` [S] | call site #2 |
| `CodeController::verifyCode()` 855–860 | IP count: `codes` with `client_ip` + `has_voted` in the election `>= config('app.max_use_clientIP') ?? 7` [S] | **duplicate security authority** (I3) — retire in 2B |
| `VoteController::store()` 1486–1568 | Trust → Legitimacy chain inline; primary gate; verdict into `forCast()` [S\*] | authoritative — to be extracted (A) and consumed (C) |
| Trust chain policies | `NetworkBindingPolicy` denies when votes from IP `> max(1, max_votes_per_ip / 2)` for unverified voters and `network_binding_strategy !== 'none'` and IP not whitelisted; defaults `ip_count`, 6 → **3** [S\*, defaults S]; `DeviceBindingPolicy` never denies; `evaluate()` writes a security event [S\*] | authoritative (E2) |

## 6. Authority classification

| Class | Members |
|---|---|
| **Authoritative** | `VotingEntitlementQuery::decide()` (E1) · Trust → Legitimacy chain via the one adapter (E2) · CAS for participation (final cast) |
| **Legacy (kept, superseded)** | `isUserEligible()` slug shortcut and `users.can_vote` (→ R3) · cached `standingOf()` in `ensure.election.voter` (navigation, Q6) |
| **Duplicate (to retire)** | IP count in `verifyCode()` (2B) · IP paths in `vote_post_check` (demo only now), `resolveIpBlock` (VE-15), `ValidateVotingIp` (reads a missing column) — fate decided in 2B |
| **Process validation** | §3a |
| **Prohibited from becoming a second authority** | any `PreVotingGate` / `PreVotingDecision` · a new IP or security engine · `Code.can_vote_now` as authorization · voter-slug presence as entitlement · a caller-built `SecurityVerdict` |

## 7. IP consolidation

| Item | Position |
|---|---|
| **VE-07 / C1** — multiple independent IP-density authorities | consolidate onto E2; 2A **adds** E2 at entry, 2B **removes** the duplicates after **Q3** |
| **F4** — `verifyCode()` has its own IP count | replaced, not composed (ADR §3.1); kept in 2A because with `network_binding_strategy = 'none'` or a whitelisted IP it is today the only code-step IP block |
| **VE-15** — election-start IP limit never blocks (`step_1_ip` never written) | duplicate path; decided in 2B |
| Thresholds differ | `verifyCode()`: `>= 7` (env, default 7) · E2: `> 3` for unverified voters (`elections.max_votes_per_ip` 6 halved) — after 2A the E2 rule is the binding one at entry, as it already is at the final cast |
| **Temporal relocation (review amendment 3)** | refusing the 4th voter per IP at entry is an **intentional temporal relocation of an existing rule** — same Trust → Legitimacy policy and threshold that already refuse at the final cast — **not a new security rule** |
| **PBDIGIT-234** | **not reintroduced** — no `users.voting_ip` column, no IP-to-voter binding (Phase 7 blocked, anonymity) |
| **PBDIGIT-235** | **unrelated to IP** — it concerns a soft-deleted Code still being usable |

## 8. Open decisions

| # | Decision | Blocks |
|---|---|---|
| **Q1** | name / home of the security authority (rename / relocate only) | naming in Stage A (provisional names used) |
| **Q3** | security-policy boundary — which settings form one policy, which threshold is authoritative | **2B** (removal of `verifyCode()`'s count) |
| **Q6** | do `ensure.election.voter` / `vote.eligibility` delegate to `VotingAuthorization`? | middleware changes (none in 2A) |
| **Q7 / PBDIGIT-231** | regeneration keeps `can_vote_now` | code-step process semantics (not 2A) |
| **R1-F6a** | code validity window as a precondition of casting | process guards (not 2A) |
| **Naming** | "pre-voting gate" = business name of `forEntry()`; ADR names kept (`EntryAuthorization`, `EntryAuthorizationReason`) unless the ADR is amended | none — recorded |

## 9. Call-site model (Stage B)

```
create()   resolve context (getUser, getElection: real boundary R1, voter slug)
           → verdict = VotingSecurityVerdictQuery::verdictFor(context)        (the only E2 source)
           → forEntry(EntryAttempt(voter, election, verdict))
           → denied: redirectToDashboard(reason message) — no session write, no Code read, no getOrCreateCode(), no e-mail
             [CORRECTION 2026-10-05, after tenant decision (a): "no session write" no longer holds — the tenant binding
              runs before the verdict, so on denial the election's organisation may already be bound as tenant.
              Still true: no Code access, no code creation / regeneration, no e-mail.]
           → allowed: unchanged flow (session write 65, Code read 75, has_voted guard, getOrCreateCode() …)

store()    resolve context → basic non-domain input validation (voting_code required, size 8)   (review amendment 2)
           → verdict → forEntry()
           → denied: redirectToDashboard(reason message) — no Code lookup, no verification
           → allowed: unchanged flow (Code lookup 268, has_voted guard, verifyCode() …)

any \Throwable from E1 / E2  → fail closed: generic refusal, no Code access, never HTTP 500
agreement routes             → no call in 2A (ADR §3.1: optional advisory; Q6)
VoteController::store()      → Stage C: same adapter replaces the inline assembly; forCast() unchanged
```

`VotingAuthorization` is constructed as in `VoteController.php:1675` (`new VotingAuthorization(app(EloquentVotingEntitlementQuery::class))`) or resolved by the container — both give a fresh `decide()`. Security events written by `evaluate()` at entry are voter-side, contain no ballot data and no code value (I8).

## 10. RED test strategy (2A RED — not authorised yet; no production change during RED)

| Suite | Contracts |
|---|---|
| `tests/Unit/Contexts/Elections/VotingAuthorizationForEntryTest.php` (pattern of `VotingAuthorizationForCastTest`: plain PHPUnit, recording fake port) | `Allowed` only success · each entitlement reason forwarded by name and value · `decide()` exactly once, never `standingOf()` · Denied → SecurityDenied · ReviewRequired → SecurityReviewRequired · entitlement before security · exact 9-case vocabulary · no process reason, no "already voted" |
| `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php` | **extraction characterization:** given the same election / user / IP / density facts the adapter yields exactly today's inline verdict (Allowed · Denied over the limit · Deferred / Investigate → ReviewRequired) · `network_binding_strategy = 'none'` → Allowed · whitelisted IP → Allowed |
| `tests/Feature/Voting/CodeEntryAuthorizationTest.php` (call sites) | **E1 delegation:** session flag `demo` + non-votable real election → `create` / `store` refused, no Code (closes F-C2 at the code step) · **E2:** 4 voted codes from the request IP → refused **before** a Code is created / e-mailed — *labelled as intentional temporal relocation of the existing rule* · **fail closed:** a throwing E2 adapter → refusal, no Code, no 500 · **ordering:** malformed `voting_code` → validation error with no security evaluation · allowed path unchanged |
| Regression proving `verifyCode()` does not yet lose its IP role | R0 `r0_11` (IP limit via `verifyCode()`) stays green in 2A; the "no longer owns the IP decision" contract is written in **2B RED** |
| Unchanged suites | R0 characterization (13) · R1 boundary (10) · Phase 4 (`CastDecisionEnforcementTest`, `AtomicParticipationConsumptionTest`, `CastFailClosedTest`, Phase 1 `FinalDenialCharacterizationTest`) · per-test baseline |

Sequence: **2A RED → review → STOP · 2A GREEN (Stage A + B) → review → STOP · Stage C (separate commit, behaviour-preserving) → review · 2B after Q3.** 2A GREEN and Stage C are never combined.

## 11. Explicit non-goals

No VE-01 · no `PreVotingGate` / `PreVotingDecision` · no second authorization or security engine · no IP-to-voter binding, no PBDIGIT-234 migration · no code-validity decision before R1-F6a · no regeneration decision before Q7 · no final-cast semantic change (Stage C is behaviour-preserving only; no change to `forCast()`, CAS, `SecurityVerdict`, `TrustPolicyEvaluator`) · no middleware change before Q6 · no demo work · no anonymity change.

## Risks

Security events per code-step request (new volume; voter-side only) · the 4th voter per IP is refused at entry instead of at the final cast (relocation, §7) · Stage C touches frozen `VoteController::store()` — limited to replacing the inline assembly with the same logic behind the port, proven by the Phase 4 suites.

**Traceability:** ADR_20261005_0950 §3.0–§3.5, I2, I3, I6, Q1–Q7 · Phase 5 charter (Round 2) · PBDIGIT-238 R0 blueprint / R1 · audit VE-07, VE-15, F-C2, C1, F4 · PBDIGIT-214, 217, 231, 234, 235, 237.
