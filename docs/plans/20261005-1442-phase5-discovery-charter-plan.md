# Phase 5 — Voting Entry & Pre-Cast Authorization Discovery: Charter

**Status:** ✅ **APPROVED by the PO (2026-10-05).** Charter only: no code, no test, no migration, no configuration change (`MAX_USE_IP_ADDRESS` untouched). Round 1 may start after the docs commit; implementation only after a separate decision and authorisation per round.
**Roadmap:** `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md` — Phase 5. The ADR §3.1 `forEntry()` design is examined in Round 2 as a **candidate**; this charter does not decide that it is the solution.
**Inherited baseline:** ADR `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (approved, frozen) · Phase 4 plan `docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md` (complete, frozen) · Phase 4 audit `docs/publicdigit/reviews/2026-10-05-phase4-e1-e4-architecture-audit.md`

---

## 1. Objective

Bring the voting **journey before the final cast** — code creation, code entry, agreement, ballot, the step-4 confirmation page — under the authority model Phase 4 established for the final cast, so that no step holds an independent, competing security or eligibility authority, and every step fails closed with a reason.

Phase 5 is **discovery first**: each topic produces evidence and an architectural decision before any production change.

## 2. Authority model inherited from Phase 4 (fixed — not reopened)

```
Voting Entitlement   VotingEntitlementQuery::decide()  (standing ∧ lifecycle; admission inside)
        ↓
Security             Trust → Legitimacy chain           (the ONE security authority, ADR I3)
        ↓
Cast decision        VotingAuthorization::forCast()     (final cast only)
        ↓
Consumption          compare-and-set on codes.has_voted (the ONE "already voted")
        ↓
Anonymous ballot     Vote
```

ADR guidance that already bears on Phase 5 (frozen text, quoted for orientation — not a decision of this charter):
- §3.1: `forEntry()` = authorization only; used at GET `code/create` (before `getOrCreateCode()`) and at code entry (**replacing** `CodeController::verifyCode`'s own IP count, F4); "optionally as an **advisory** check at agreement / ballot pages (UX only)"; "**not used for** the final decision".
- §3.5: navigation / advisory checks may be cached; `forEntry()` at code create / entry is fresh.
- §8 Phase 5: no silent change to demo behaviour (Q4).

## 3. Scope

**In scope (discovery):**
1. **Round 1 — C1, the step-4 gate** (`VoteController::verify()` → `vote_post_check()`), §4.
2. Round 2 — entry authorization: whether and where an entry decision is needed at `code/create` and code entry (ADR §3.1 `forEntry()` as the candidate design); the duplicate IP count in `CodeController` (F4).
3. Round 3 — triage of the deferred items in §6 that touch the entry path: decide per item *Phase 5 / later phase / no action*.

**Out of scope:**
- Phase 4 code (`store()`, `forCast()`, the compare-and-set) — frozen; reopening needs a superseding decision.
- A5 / A6 and every anonymity-model question — Phase 2.0 (Model A / B).
- IP binding implementation, trusted proxies, IPv6 (Phase 6 / 7; Phase 7 blocked).
- Receipt / view-my-vote semantics.
- Schema changes, persisted authorization flags, a new security engine.
- Demo-election behaviour (Q4) unless a round explicitly asks for a demo decision.

## 4. Discovery Round 1 — C1: the step-4 confirmation gate

### Known facts (from the Phase 4 audit — to be re-verified in the round)
- `verify()` (`VoteController.php`, step 4, GET `slug.vote.verify`) calls `vote_post_check($auth_user, $code, $vote_data, $voterSlug)` and **reads** its result (unlike the former `store()` call): an `error_message` redirects to the dashboard; `return_to` redirects.
- `vote_post_check()` contains: no-code check · **IP-density count against `config('app.max_use_clientIP')`** (no default; demo exempt) · `has_voted` check · missing-ballot check — and every error branch renders the non-existent `components.error_message` view, i.e. throws.
- `verify()` wraps its body in `catch (\Exception)` → redirect to the dashboard. With `MAX_USE_IP_ADDRESS` unset (`0 >= null` is true) a real voter is therefore sent back to the dashboard at step 4 and **never reaches the final cast** (D10, now located at step 4).
- `CodeController` reads the same setting **with** a default (`config('app.max_use_clientIP') ?? 7`); `vote_post_check()` does not.
- The Trust → Legitimacy chain already enforces IP density at the final cast (election `max_votes_per_ip`, `NetworkBindingPolicy`).

### Questions to answer
1. **Role of step 4:** should the confirmation page hold **any** independent security / eligibility authority, or be a pure preparation / confirmation step whose authoritative decision happens at the final cast (ADR §3.1 "advisory … UX only")?
2. **Each `vote_post_check()` responsibility at step 4:** classify (authorization · process validation · already-voted · ballot/session · IP/security · demo) and map to the inherited authority — keep as advisory, delegate, or drop?
3. **IP density at step 4:** is a config-based count outside the Trust → Legitimacy chain a violation of I3 here too (as P4-D1 decided for the final cast)? If an advisory check is wanted, which authority answers it?
4. **Operational contract:** what must happen when `MAX_USE_IP_ADDRESS` is unset — and is that setting still needed anywhere once the step-4 and code-entry questions are answered?
5. **Failure semantics:** should step-4 refusals be reasoned (like E3) rather than exception-driven redirects?
6. **Other `vote_post_check()` callers** (`DemoVoteController`, `DeligateVoteController`): in or out of Round 1? (Demo: Q4.)
7. **Relation to `forEntry()`:** is step 4 a `forEntry()` advisory call site, or outside `forEntry()` altogether?

### Evidence required before any decision
- Complete read of `verify()` and every path into it (routes, middleware stack, redirects from `first_submission()`), with line references.
- Characterization tests on **synthetic data** of today's step-4 behaviour: config set / unset; IP count below / at / above the limit; already voted; missing ballot; missing code; demo — each recording the observable outcome (page, redirect, error, log), **without changing production code**.
- Inventory of every reader of `app.max_use_clientIP` / `MAX_USE_IP_ADDRESS`.
- Anonymity check of every proposed change against the interim 7-point rule.

### Output of Round 1
A discovery report + an architectural decision proposal (ADR amendment or decision record) answering Q1–Q7, for PO / ARB review. **No production change in Round 1.**

## 5. Rounds 2 and 3 (outline — refined after Round 1)

- **Round 2 — `forEntry()`:** where it is called; relation to the middleware projection and to Phase 4's `forCast()`; replacement of the `CodeController` IP count (F4); PBDIGIT-214 (`voter.restart` issues a slug without an entitlement check); PBDIGIT-231 / Q7 (`can_vote_now` kept on code regeneration); Q6 (middleware placement).
- **Round 3 — deferred-item triage:** §6.

## 6. Deferred backlog inherited (triage, not commitment)

| Item | Proposed Phase 5 treatment |
|---|---|
| **C1 / D10** step-4 IP density | **Round 1** |
| PBDIGIT-214, 231, Q6, Q7 | Round 2 (entry path) |
| PBDIGIT-234 dormant IP binding (`users.voting_ip` missing) | triage — likely Phase 6 / 7 security design |
| PBDIGIT-235 soft-deleted Code still usable | triage — code-lifecycle semantics decision |
| PBDIGIT-218 legacy `POST /votes` | triage — close or retire the route |
| F10 real-only guards not fail-closed for a future election type | triage — election-type semantics |
| F11 / F12 post-commit / receipt consistency | **not Phase 5** unless explicitly approved — receipt design (Phase 2.0-adjacent) |
| A5 / A6 | **not Phase 5** — Phase 2.0 anonymity decision |
| R2a architecture / fitness test for `forCast()` | triage — decide whether to add with Phase 5 fitness tests |
| F1 #2 pre-lock entitlement revalidation | candidate simplification — only with a decision; Phase 4 stays frozen |

## 7. Rules for every round

- Business → DDD → architecture decision → RED → GREEN (standing rule); discovery precedes decision; decision precedes tests.
- Read-only discovery; characterization on **synthetic data** in the testing database only; never the development database.
- **Approval gate before any production change**, per round and per edit group; RED and GREEN each stop for review.
- Interim anonymity rule binding; no new voter ↔ ballot correlation path.
- Findings outside the round become backlog items, never silent scope growth.
- Engineering supplies evidence and recommendations; decisions are the PO's / ARB's.

## 8. PO decisions (2026-10-05)

1. **Charter: APPROVED** (objective, scope, exclusions, rounds).
2. **Round 1 scope: `VoteController::verify()` and its actual call chain only.** The Demo / Deligate `vote_post_check()` copies are **out** (Q4 keeps demo separate); if discovery shows the shared implementation is an architectural issue, it gets a separate decision.
3. **`MAX_USE_IP_ADDRESS`: verification only** — ops checks every environment that can run a live election and reports `configured → value` · `not configured → risk` · `unknown → verification incomplete`. **No change.** Not a C1 fix.

**Round 1 decision outcomes to choose from (after evidence):** KEEP as advisory · DELEGATE to an existing authority · REMOVE as obsolete · RESTRUCTURE behind a new approved boundary. No RED / GREEN in Round 1.

## Progress
- 2026-10-05: charter drafted (no code); PO approved it with decisions §8; wording aligned (Phase 5 = discovery, `forEntry()` a Round 2 candidate).

## Next actions
Docs commit → Round 1 discovery: evidence report (`verify()` → routes / middleware → `first_submission()` → `vote_post_check()` → every branch; characterization on synthetic data: config set / unset, IP below / equal / above the limit, already voted, missing ballot, missing code, demo — redirects, errors, logs; inventory of every `app.max_use_clientIP` reader) → architecture decision proposal → PO / ARB approval → only then implementation. Ops: `MAX_USE_IP_ADDRESS` verification report.
