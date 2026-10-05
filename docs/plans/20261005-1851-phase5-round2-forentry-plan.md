# Plan — Phase 5 Round 2: Entry Authorization Boundary (`forEntry()`) at the CodeController (PBDIGIT-239)

## Context

R0/R1 (PBDIGIT-238, committed `b90547add` · `f722520dc` · `7a8407d06`) gave `CodeController` a hard real-election boundary, but the **entry into the voting journey** still has no authoritative decision: eligibility is spread over cached middleware (`ensure.election.voter` → `standingOf()`), `vote.eligibility` (lifecycle, skipped when the session flag `selected_election_type = demo` is set — F-C2), `isUserEligible()` (voter-slug shortcut) and IP counting inside `verifyCode()`. A code is created and e-mailed (`getOrCreateCode()`) before any fresh decision.

The approved ADR `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` §3.1 already defines the answer: **`VotingAuthorization::forEntry()`** — *"may this voter enter or continue the voting process now?"* — composed of **E1 `VotingEntitlement.decide()`** (standing incl. admission/identity + lifecycle, I2) and **E2 the `VotingSecurityDecision` role** fulfilled by the existing Trust → Legitimacy chain (I3, no parallel engine), called at `code/create` **before** `getOrCreateCode()` and at code entry. No `PreVotingGate` / `PreVotingDecision` is introduced; "pre-voting gate" is only the business name of `forEntry()`.

**Outcome of this plan:** first a documentation-only blueprint (then STOP); afterwards, each separately approved: 2A RED → 2A GREEN (E1+E2 at the two call sites), an E2-adapter migration slice for `VoteController::store()`, and 2B (IP consolidation) after the Q3 decision.

**PO decisions (2026-10-05):** E2 = **Option 1 — one Security Verdict Authority, consumed by both `forEntry()` (CodeController) and `forCast()` (VoteController)**, implemented **staged**: **Stage A** extract the existing chain into one adapter (behaviour-preserving) · **Stage B** CodeController uses it via `forEntry()` · **Stage C** `VoteController::store()` consumes the same authority — behaviour-preserving consolidation, **not** a reopening of Phase 4 (no redesign of `store()`, `forCast()`, CAS, `SecurityVerdict`, `TrustPolicyEvaluator`, IP rules, anonymity; no VE-01) · `verifyCode()`'s own IP count is removed only in **slice 2B, after Q3**.

**Review amendments (principal architect, 2026-10-05 — binding for the blueprint):**
1. **E2 is a fact from the one authority.** `VotingAuthorization` is the authorization decision boundary; `VotingSecurityVerdictQuery` is the **only** provider of the E2 fact. Passing `SecurityVerdict` into `forEntry()` (as `forCast()` already does) creates no second security authority, and **callers may not manufacture a verdict** — they obtain it only from the port. `forEntry()` = E1 `decide()` + E2 verdict from that port; it is never "E1-only".
2. **`store()` ordering:** context resolution → **basic non-domain input validation** (`voting_code` required, size 8) → `forEntry()` → Code lookup / verification. The boundary precedes Code access, not syntax validation (no security evaluation / event for malformed input).
3. **Earlier IP-density refusal is a temporal relocation of an existing rule** — same Trust → Legitimacy policy and threshold, now applied at entry as well; **not a new security rule**. The RED test states this explicitly so a changed result is not read as an accidental regression.

## Facts the design rests on (read in source)

- `VotingAuthorization` (`app/Contexts/Elections/Application/Services/VotingAuthorization.php`): `forCast(CastAttempt): CastDecision`; entitlement `decide()` once → security verdict **passed in as a fact** (`SecurityVerdict`) → process; no container binding — `VoteController.php:1675` does `new VotingAuthorization(app(EloquentVotingEntitlementQuery::class))`.
- `VotingEntitlementQuery` (Ports): `standingOf()`, `decide()` (= standing ∧ `ElectionLifecycle::canVote()`); container binding `CachedVotingEntitlementQuery` caches only `standingOf`, **`decide()` is always fresh**.
- E2 today: evidence assembled **inline** in `VoteController::store()` 1486–1512 (registered-IP hash from `users.voting_ip` — column missing, always null, PBDIGIT-234; density = `codes` with `client_ip` + `has_voted` in the election), `TrustPolicyEvaluator::evaluate()` (container singleton) → `ConstitutionalLegitimacyDecision::decide()` → private `securityVerdictOf()` (1985–1993: Allowed→Allowed, Denied→Denied, Deferred/Investigate→ReviewRequired). Only `NetworkBindingPolicy` can deny in practice: `votesFromThisIp > max(1, max_votes_per_ip/2)` when `network_binding_strategy !== 'none'` and IP not whitelisted; defaults `ip_count`, 6 → **limit 3 for unverified voters**. `evaluate()` also writes a security event (`SecurityEventRecorder`).
- Other IP authorities: `CodeController::verifyCode()` (`>= config('app.max_use_clientIP') ?? 7`), `vote_post_check` (demo only now; fails closed when env unset), `ElectionVotingController::resolveIpBlock` (counts `step_1_ip`, never written — VE-15), `ValidateVotingIp` middleware + `validateVotingIpWithResponse()` (`users.voting_ip` missing → pass).
- Unit-test pattern: `tests/Unit/Contexts/Elections/VotingAuthorizationForCastTest.php` (plain PHPUnit, anonymous fake `VotingEntitlementQuery` recording calls, all-passing attempt fixture, exact vocabulary assertions).

## Step 1 — Blueprint (documentation only; this is what runs after approval, then STOP)

Create `docs/publicdigit/reviews/2026-10-05-phase5-round2-forentry-blueprint.md` (placement `docs/publicdigit`, exit 0) titled **"Phase 5 Round 2 — Entry Authorization Boundary (`forEntry()`)"** with exactly the required sections:

1. Purpose & scope · 2. ADR alignment (§3.0, §3.1, §3.2, I2, I3, I6; Q2 resolved = deny) · 3. Canonical contract · 4. Entry authorization matrix (E1, E2) · 5. Current implementation mapping (middleware, CodeController, `verifyCode()`, `vote.eligibility`, Trust → Legitimacy) · 6. Authority classification (authoritative / legacy / duplicate / process validation / prohibited as second authority) · 7. IP consolidation (VE-07, C1, F4, VE-15; **PBDIGIT-234 not reintroduced**; **PBDIGIT-235 is unrelated to IP**) · 8. Open decisions (Q1, Q3, Q6, Q7/PBDIGIT-231, R1-F6a, naming) · 9. Call-site model · 10. RED test strategy · 11. Non-goals. Plus a separate section **"Process guards outside authorization"** (code validity window, early `has_voted`, final already-voted = CAS, `ValidatedBallot` = VE-01). Terminology: **E1 — Voting Entitlement, E2 — Voting Security Decision**; no "G1–G7".

Also: story `docs/publicdigit/backlog/PBDIGIT-239-entry-authorization-forentry-at-code-step.md` + README row (existing-story search: PBDIGIT-214/217 entry paths without entitlement check, VE-07, C1, F-C2) · plan copy `docs/plans/20261005-<HHMM>-phase5-round2-forentry-plan.md`. **No commit without approval. STOP.**

### Contract the blueprint fixes (for later gates)

```
VotingAuthorization::forEntry(EntryAttempt $attempt): EntryAuthorization
EntryAttempt        (DTO, final readonly)  voterId, electionId, security: SecurityVerdict   — verdict passed in as a fact, like CastAttempt
EntryAuthorization  (DTO)                  reason: EntryAuthorizationReason · isAllowed()
EntryAuthorizationReason (Domain enum, 9)  Allowed · NotRegistered · NotVoterRole · OrganisationMismatch · NotActive
                                           · NotAdmitted · ElectionNotVotable · SecurityDenied · SecurityReviewRequired
                                           fromEntitlement(); values identical to CastDecisionReason's authorization subset
Order: E1 decide() exactly once (never standingOf) → E2 verdict. First failure wins. No process reasons, never "already voted".
```

**E2 adapter (Q1 decides only name/home; rename allowed, no new engine):** port `app/Contexts/Elections/Application/Ports/VotingSecurityVerdictQuery.php` (`verdictFor(SecurityAttemptContext): SecurityVerdict`; context = userId, electionId, client IP, device fingerprint, session id) + adapter `app/Contexts/Elections/Infrastructure/Security/TrustLegitimacySecurityVerdict.php` moving — not rewriting — the inline assembly from `VoteController::store()` 1486–1512 and the mapping of `securityVerdictOf()`; it reuses `TrustPolicyEvaluator` and `ConstitutionalLegitimacyDecision`. Registered-IP evidence keeps today's behaviour (null; PBDIGIT-234 not reintroduced).

**CodeController call sites:** `create()` — right after context resolution (after `getElection()`'s real boundary), **before any Code read, session write, `getOrCreateCode()`, notification**; `store()` — after context resolution **and basic non-domain input validation**, before Code lookup or any code verification (amendment 2). Agreement routes: no call in 2A (ADR: optional advisory; Q6). Denial → `redirectToDashboard()` with a per-reason message; any `\Throwable` from E1/E2 → fail closed (generic refusal, no Code access, never HTTP 500). Middleware, `isUserEligible()`, `verifyCode()` IP count: unchanged in 2A (retire only after replacement is proven: `isUserEligible` → R3; IP count → 2B).

## Later gates (each needs its own approval; listed so the blueprint can plan them)

Sequence: **Step 1 blueprint → review → STOP** · **2A RED** (extraction characterization + both consumers' contracts) → review → STOP · **2A GREEN** = Stage A (extract) + Stage B (CodeController) → review → STOP · **Stage C** VoteController migration, separate commit, behaviour-preserving → review · **2B** after Q3. 2A GREEN and Stage C are never combined.

- **2A RED** (tests only) — additionally an **extraction characterization**: the adapter, given the same election / user / IP / density facts, yields exactly the verdict the inline `VoteController::store()` path yields today (Allowed / Denied over the limit / ReviewRequired mapping), so Stage A and Stage C can be proven behaviour-preserving; `tests/Unit/Contexts/Elections/VotingAuthorizationForEntryTest.php` (forCast pattern: Allowed only success, entitlement reasons forwarded by name+value, `decide()` exactly once and never `standingOf`, Denied→SecurityDenied, ReviewRequired→SecurityReviewRequired, entitlement before security, exact 9-case vocabulary, no process reasons) · `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php` (mapping, density over limit → Denied, strategy `none` / whitelisted → Allowed) · `tests/Feature/Voting/CodeEntryAuthorizationTest.php` (fresh `decide()` closes F-C2: session flag `demo` + non-votable real election → `create`/`store` refused, no Code; 4 voted codes from the request IP → refused **before** a Code is created/e-mailed — labelled in the test as *intentional temporal relocation of the existing Trust → Legitimacy rule, same policy and threshold, not a new rule* (amendment 3); allowed path unchanged; throwing E2 adapter → refusal without Code, no 500). No production change.
- **2A GREEN**: DTOs + enum + `forEntry()` + port/adapter + two CodeController call sites; R0 characterization (13), R1 boundary (10), Phase 4 suites and the per-test baseline unchanged except the decided changes.
- **E2 migration slice** (separate commit): `VoteController::store()` uses the adapter instead of the inline assembly; behaviour-preserving, proven by the existing Phase 4 suites (`CastDecisionEnforcementTest`, `AtomicParticipationConsumptionTest`, `CastFailClosedTest`, Phase 1 `FinalDenialCharacterizationTest`) unchanged.
- **2B** (after Q3): remove `verifyCode()`'s IP count; decide fate of `vote_post_check`/`resolveIpBlock`/`ValidateVotingIp` IP paths.

## Non-goals

No VE-01 · no `PreVotingGate`/`PreVotingDecision` · no second authorization or security engine · no IP-to-voter binding, no PBDIGIT-234 migration · no code-validity decision before R1-F6a · no regeneration decision before Q7 · no final-cast semantic change (the migration slice is behaviour-preserving only) · no middleware change before Q6 · no demo work.

## Risks

`evaluate()` writes a security event per code-step request (new volume; voter-side only, no ballot, I8 respected — to be stated in the blueprint) · E2 at entry blocks the 4th voter per IP earlier than today's code step (same rule already applies at the final cast — moving it earlier, not tightening it) · touching frozen `VoteController::store()` is limited to the migration slice with full Phase 4 regression.

## Verification

Step 1: blueprint covers all 11 sections + process-guard section, every claim traced to file:line; story + README row; `git status` shows only docs. Later gates: RED fails for the stated reasons with no production diff; GREEN passes new tests with R0/R1/Phase 4 suites and the per-test baseline (`r0_baseline.sh`) unchanged except the decided changes.
