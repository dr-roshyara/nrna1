# Decision Brief — R1-F6a: the code-verification validity window at the final cast

**Date:** 2026-10-05 · **Status:** 🟡 **FOR PO / ARB DECISION** — decision preparation only; no production, test, configuration or architecture change
**Origin:** Phase 5 Round 1 discovery `docs/publicdigit/reviews/2026-10-05-phase5-round1-step4-gate-discovery.md` (finding R1-F6, see its Addendum 1) · Phase 5 charter `docs/plans/20261005-1442-phase5-discovery-charter-plan.md`
**Frozen inputs:** ADR `ADR_20261005_0950_Voting_Authorization_Boundary.md` · Phase 4 plan and decision record (E2-D3: exactly 14 `CastDecisionReason` cases) · Phase 4 audit
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies facts, options and a recommendation; **the decision is the PO's / ARB's.**

> **Principle for this brief:** the current technical behaviour is evidence, **not** the business requirement. Nothing below decides a rule by describing what the code happens to do.

---

## 1. The question

> **Is successful verification of the voting code a time-bounded precondition for casting a vote, and must that precondition be enforced authoritatively at the final cast regardless of how the request reaches it?**

This is a question about a **domain precondition of a valid vote** — not about whether step 4 should check a clock.

```
code verification → verification timestamp + validity duration → [valid at the time of the cast?]
  → VotingAuthorization::forCast() → CastDecision → atomic participation consumption → anonymous Vote
```

### Verification states (domain vocabulary for this decision)

| State | Condition | Domain meaning |
|---|---|---|
| **Code not verified** | `used_at = null` | the code has not been successfully verified |
| **Code verification expired** | `used_at + voting_time_in_minutes < now` | verified, but the validity window has elapsed |
| **Code verification valid** | `used_at + voting_time_in_minutes >= now` | verified and within the window |

## 2. Facts established from the code (no interpretation)

| # | Fact | Evidence |
|---|---|---|
| F1 | The clock is `codes.code_to_open_voting_form_used_at`, set when the code is entered successfully | `CodeController.php:918` (`'code_to_open_voting_form_used_at' => now()`) |
| F2 | The duration is `codes.voting_time_in_minutes`, written at code verification from `config('voting.time_in_minutes')` = `env('VOTING_TIME_IN_MINUTES', 30)` | `CodeController.php:40`, `:771`; `config/voting.php` |
| F3 | `used_at = null` is evaluated as **now** — `Carbon::parse(null)` → zero minutes elapsed → **not expired** | `vote_pre_check()` (`VoteController.php:3067ff`), `second_code_check()` (`:3245ff`) |
| F4 | Two different clocks exist: the **slug window** (`voter_slugs.expires_at`, created now + 30 min) and the **code window** (F1 + F2) | `VoterSlugService.php:422`; `config/voting.php` ("Applies to BOTH") |
| F5 | The slug window **is** enforced on the final POST (`voter.slug.window` → expired slug refused) but **slides**: `getOrCreateActiveSlug()` extends an active slug by 30 min on re-entry | `ValidateVoterSlugWindow.php:37`; `VoterSlugService.php:178–186` (called from `routes/election/electionRoutes.php:87`) |
| F6 | **Code regeneration** (`CodeController::create()`) sets a new code value, `used_at = null`, `vote_submitted = false`, and **keeps** `can_vote_now` ("Preserve verified status") | `CodeController.php:149–160` — PBDIGIT-231 |
| F7 | The code window is enforced **only** in the step journey: step 3 (`vote_pre_check()` → redirect) and step 4 (`second_code_check()` → resets code flags on GET, redirect) | `VoteController.php:551`, `:2469` |
| F8 | The final cast (`store()` / `forCast()`) does **not** evaluate the code window | `VotingAuthorization.php`; `store()` |
| F9 | `slug.vote.store` is not in the step map, so the final POST does not depend on steps 3 / 4 | `config/election_steps.php` |
| F10 | **Direct final POST bypasses the code window** — probe (synthetic data): code used 45 min ago, window 30 → vote stored, `has_voted = true` | Round 1 re-audit, case A |
| F11 | A successful cast cannot be repeated: a retry hits `AlreadyVotedPreCheck` / the conditional update changes zero rows | Phase 4 E4 (R4-1…R4-4) |

### Derived risk (static-code inference — not dynamically characterized)

| # | Derived risk | Derived from |
|---|---|---|
| F12 | After a code is **regenerated** but before the new code is entered, a direct final POST with the **new** code value would find `can_vote_now = 1`, `used_at = null` and a matching code — nothing at the final cast would refuse it. **Inferred from the code path; no probe has run this case.** | F3, F6, F8; PBDIGIT-231 |

## 3. Questions for the PO / ARB

0. **Should both invalid states — code not verified, and code verification expired — prevent final casting?** (§1)
1. Is the code-verification window a **business validity condition** for casting a vote?
2. What event **starts** the window?
3. Which timestamp is **authoritative**?
4. What is the intended **duration** (fixed, per election, platform setting)?
5. What does `used_at = null` **mean**: not verified · freshly verified · expired?
6. Does **regenerating** a code restart the window?
7. Does **re-entering** the voting flow (slug extension, F5) restart it?
8. What happens when the window **expires** (refusal, where does the voter go)?
9. Must the same rule apply to a **direct final POST**?
10. What happens to the **step-3 / step-4 checks** if the final cast becomes authoritative?
11. Must **PBDIGIT-231** be resolved before, with, or after this rule?

## 4. Options

| Option | Rule | Consequences |
|---|---|---|
| **A — final-cast validity precondition** | A vote is valid only if the code is verified and the verification is within `voting_time_in_minutes`; enforced authoritatively at the final cast (the Phase 4 cast decision) | One rule, any request path. The decision-reason vocabulary (frozen E2-D3, 14 cases) would be **amended by an explicit decision record**; whether by **one or two** new reasons (not verified / expired) is derived from the PO's answer to Q0 / Q5 / Q8 — not fixed here. Phase 4 code otherwise stays frozen. |
| **B — no fixed code window** | Only the slug window bounds a voting session | Removes a rule rather than enforcing it; since the slug window slides (F5), there is then **no fixed time limit** after code verification. The step-3 / step-4 code-window checks become inconsistent. |
| **C — one clock** | Unify the clocks: define **one** authoritative voting-session validity window and use it consistently across the journey and the final cast | Changes what "session" means (re-entry, recovery, PBDIGIT-214 / 215 interplay). The enforcement mechanism (middleware, the cast decision or other) is an engineering decision **after** the business option is chosen. |
| **A′ — fold into an existing fact** | Make `codeStepCompleted` mean "code step completed **and** within the window" | No vocabulary change, but **overloads one fact with two meanings** (model-integrity rule) and loses the distinct reason for the voter. Not recommended. |

## 5. Recommendation (engineering — not a decision)

**Option A**, with these semantics for the PO / ARB to confirm or amend:

| Q | Recommended answer | Reason |
|---|---|---|
| 0 | **Yes — both invalid states prevent casting**, and they stay **distinct**: *code not verified* ≠ *code verification expired* | Different business states; collapsing them would hide a distinction the cast decision's explicit reasons are designed to keep |
| 1 | **Yes** — a validity condition | The window exists in the journey today (F7); a rule that only the UI enforces is not a rule (F9, F10) |
| 2 / 3 | Successful code entry; `codes.code_to_open_voting_form_used_at` | F1 — the only timestamp written by the verification itself |
| 4 | `codes.voting_time_in_minutes` as written at verification (platform default 30) | F2; keeps the value fixed per verification, independent of later config changes |
| 5 | **No verification timestamp → code not verified → refuse** (fail-closed). This is **not** "expired". | Treating null as "now" (F3) is the gap behind F12 |
| 6 | **Regenerating a code must not create a new verification timestamp. A new validity window starts only after the regenerated code has been successfully verified.** | Follows from 2 / 3 and 5; closes the derived risk F12 |
| 7 | **No** — re-entering the flow does not restart the code window | The slug window slides (F5); the code window must not |
| 8 | Refuse with a reason that names the state — *code not verified* or *code verification expired* (no vote, nothing consumed — E1 / E4 unchanged); the voter returns to code entry | Consistent with E3 refusals; the exact reason vocabulary (one or two new reasons) is derived after the decision |
| 9 | **Yes** — the final cast is the authority; any path | Phase 4 authority model |
| 10 | **If the final cast becomes authoritative, steps 3 / 4 must no longer be an independent authority for this rule.** Engineering then determines whether their checks remain as advisory UX / pre-flight checks or are removed (with the C1 / step-4 decision) | Keeps the principle; leaves the mechanism to the follow-up |
| 11 | Decide **together** with PBDIGIT-231. With answer 5 the final cast would no longer accept a regenerated-but-unverified code (F12); PBDIGIT-231's code-entry side remains its own story | Removes the dangerous part of 231 at the authority without redesigning code entry |

## 6. What deciding Option A would and would not change

- **Would change (after a separate RED → GREEN authorisation):** the cast-attempt facts (verification timestamp and window), the decision-reason vocabulary (E2-D3 amended by decision record — one or two new reasons, per Q0 / Q5 / Q8), `store()` (reads the two code facts), the refusal messages, tests (new contracts; E2 vocabulary test updated by that decision).
- **Would not change:** the conditional update (E4), transaction and lock handling (E1), entitlement / security authorities, schema, demo behaviour (Q4).
- **Anonymity:** no new voter↔ballot persistence or correlation mechanism is introduced by this proposal, based on the current interim anonymity rule (voter-side timestamps are only read; nothing new is written or logged with a vote id).

## 7. Out of scope of this brief

R1-F6b (two-code mode — not implemented for real elections; design debt) · C1 / IP density (R1-F1, R1-F2) · R1-F7 `debug_code` · R1-F8 GET writes · A5 / A6 / A1 (Phase 2.0) · `MAX_USE_IP_ADDRESS` operations check.

## 8. Requested decision

Approve, amend or reject §5 (answers 0–11). On approval: engineering derives the exact reason vocabulary from the answers; a decision record amends E2-D3; then RED tests are derived and stop for review — no implementation before that.

## Revision history
- rev 1 (2026-10-05): first draft.
- rev 2 (2026-10-05, review): question reframed as a time-bounded domain precondition; verification-state vocabulary added (not verified / expired / valid) and Q0; F12 re-labelled as a static-code inference; Q5 / Q8 distinguish *not verified* from *expired*, and the reason count is no longer fixed ("14 → 15" removed); Q6 reworded (no new timestamp on regeneration); Option C stated as a business option without mechanism; Q10 limited to the principle; anonymity statement weakened to the evidenced claim.
