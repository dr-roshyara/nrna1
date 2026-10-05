# Code Step — Developer Guide (CodeController, real elections)

**Area:** the entry into the voting journey — `app/Http/Controllers/CodeController.php` (code page, code entry, agreement) on the real slug routes `slug.code.create`, `slug.code.store`, `slug.code.agreement`, `slug.code.agreement.submit`.
**Stories:** PBDIGIT-238 (real-election boundary) · PBDIGIT-239 (entry authorization `forEntry()`) · PBDIGIT-240 (deferred Stage C)
**Decision:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` §3.1 (`forEntry()`), §3.0 (`VotingSecurityDecision` role), I2, I3

---

## The code step today

```
GET  v/{slug}/code/create   resolve context → real-election boundary (403 otherwise)          PBDIGIT-238 R1
                            → tenant binding (election's organisation)
                            → E2 verdict (VotingSecurityVerdictQuery) → forEntry()            PBDIGIT-239
                            → allowed: existing flow (has_voted guard, get-or-create code, e-mail)
POST v/{slug}/code          resolve context → real-election boundary → tenant binding
                            → voting_code validation (size 8)
                            → E2 verdict → forEntry()                                         PBDIGIT-239
                            → allowed: existing flow (code lookup, has_voted guard, verifyCode)
GET/POST agreement          real-election boundary; unconditional has_voted guards            PBDIGIT-238
```

`forEntry()` answers *may this voter enter or continue the voting process now?* — authorization only. Code value, code validity window and the early `has_voted` check are process guards outside it; the authoritative "already voted" is the compare-and-set at the final cast (`final_cast/04`).

## Steps

| File | Story | What it covers |
|---|---|---|
| [01_real_election_boundary.md](./01_real_election_boundary.md) | PBDIGIT-238 R0 / R1 | the responsibility blueprint, the hard real-election boundary, removed demo paths, unconditional participation guards, no plaintext codes in logs |
| [02_entry_authorization_for_entry.md](./02_entry_authorization_for_entry.md) | PBDIGIT-239 Stage A / B | the one Security Verdict Authority, `VotingAuthorization::forEntry()`, the two call sites, fail-closed refusals, the deferred Stage C |

## Commits

| Step | Commits |
|---|---|
| R0 characterization + blueprint | `b90547add` |
| R1 RED · GREEN | `f722520dc` · `7a8407d06` |
| Round 2 blueprint · 2A RED | `ca9629b99` · `430d28742` |
| Stage A · Stage B | `0578091f8` · `d90131361` |
| Stage C deferred (PBDIGIT-240) | `f5d920b80` |

## Tests (synthetic data)

| Suite | Pins |
|---|---|
| `tests/Feature/Voting/CodeControllerRealElectionCharacterizationTest.php` | R0 — today's code-step behaviour (13) |
| `tests/Feature/Voting/CodeControllerRealElectionBoundaryTest.php` | R1 — demo refused (403, no state change), participation never reset, no code in logs (10) |
| `tests/Unit/Contexts/Elections/VotingAuthorizationForEntryTest.php` | `forEntry()` contract (12) |
| `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php` | extracted verdict == inline final-cast verdict (oracle) |
| `tests/Feature/Voting/CodeEntryAuthorizationTest.php` | the two call sites, ordering, tenant, fail closed (12) |

## Not done here (on purpose)

`isUserEligible()` slug shortcut and `users.can_vote` (R3) · `can_vote_now` / regeneration (PBDIGIT-231) · code validity window (R1-F6a) · duplicate IP count in `verifyCode()` (2B, after Q3) · middleware (Q6) · `VoteController` consuming the Security Verdict Authority (PBDIGIT-240) · whitelist defects · demo routing in `DashboardResolver`.
