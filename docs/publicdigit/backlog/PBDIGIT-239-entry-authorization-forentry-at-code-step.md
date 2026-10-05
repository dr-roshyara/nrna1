# PBDIGIT-239 — Entry authorization `forEntry()` at the code step (Phase 5 Round 2)

**Type:** Architecture implementation (voting process, entry authorization) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** PBDIGIT-238 R0 / R1 (CodeController responsibility discovery) and the real-election controller review (F-C2, F4); Phase 5 charter Round 2
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-214` / `PBDIGIT-217` (entry paths issue voting sessions without an entitlement check) describe the symptom on other entry paths, not the ADR boundary at the code step; VE-07 / C1 (multiple IP-density authorities) and VE-15 are audit findings without a story; `PBDIGIT-238` stops at the real-election boundary and explicitly defers authorization. No existing story implements ADR §3.1 `forEntry()`.

| | |
|---|---|
| **Status** | 🟡 **BLUEPRINT** — `docs/publicdigit/reviews/2026-10-05-phase5-round2-forentry-blueprint.md` for review · RED not authorised |
| **Finding (FACT [S])** | At the code step a code is created and e-mailed (`getOrCreateCode()`) before any fresh authorization; eligibility is spread over cached `standingOf()` middleware, a lifecycle check that is skipped with the session flag `selected_election_type = demo` (F-C2), the voter-slug shortcut in `isUserEligible()`, and a duplicate IP count in `verifyCode()` (F4). The Trust → Legitimacy security verdict exists only inline in `VoteController::store()`. |
| **Target (ADR §3.1)** | `VotingAuthorization::forEntry(EntryAttempt): EntryAuthorization` = **E1** `VotingEntitlementQuery::decide()` (standing incl. admission + lifecycle) → **E2** `SecurityVerdict` from **one** Security Verdict Authority (`VotingSecurityVerdictQuery` adapter over the existing chain); called in `create()` before `getOrCreateCode()` and in `store()` after basic input validation, before Code lookup |
| **PO decisions (2026-10-05)** | Option 1 — one Security Verdict Authority for `forEntry()` and `forCast()`, staged A extract → B CodeController → C VoteController (separate, behaviour-preserving) · `verifyCode()` IP count removed only in 2B after Q3 · review amendments: E2 verdict is an authoritative fact from the port only; `store()` validates input before E2; the earlier IP-density refusal is a temporal relocation of an existing rule |
| **Out of scope** | VE-01 · `PreVotingGate` / `PreVotingDecision` · new security engine · IP-to-voter binding (PBDIGIT-234) · code validity (R1-F6a) · regeneration (Q7 / PBDIGIT-231) · middleware (Q6) · demo · final-cast semantics |
| **Gates** | Blueprint → 2A RED → 2A GREEN (Stage A + B) → Stage C (separate commit) → 2B after Q3 — each with its own approval |

**Traceability:** ADR_20261005_0950 §3.0–§3.5, I2, I3, I6 · plan `docs/plans/20261005-1851-phase5-round2-forentry-plan.md` · PBDIGIT-238 · audit VE-07, VE-15, F-C2, C1, F4.
