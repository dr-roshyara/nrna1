# PBDIGIT-245 — Step-4 security refusal renders missing error view and degrades to generic system error

**Type:** Defect — error handling / refusal semantics · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Q3 characterization C4 — `tests/Feature/Voting/SecurityPolicySurfaceCharacterizationTest.php` (`e7160076b`); Q3 proposal `docs/publicdigit/reviews/2026-10-05-q3-security-policy-surface.md` §9
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-233` (D10) found the same missing view and was resolved **for the final cast only** (Phase 4 E3 took `vote_post_check()` off the real final-cast path). The step-4 path was not covered. On 2026-10-05 its occurrence was first noted in PBDIGIT-233 (`1334fabdc`); by PO decision it is owned **here** as a dedicated item, and PBDIGIT-233 points to this story.

| | |
|---|---|
| **Status** | 🟠 `OPEN` — not authorised. **Do not fix in Q3 / 2B work.** |
| **Observed behaviour (FACT [D], synthetic data)** | On the real-election step-4 path, `VoteController::verify()` calls `vote_post_check()`. When its IP-density refusal fires (with `MAX_USE_IP_ADDRESS` unset, `0 >= null` makes it fire for every fresh voter): **(1)** the security rule fires (log `Security: IP rate limit exceeded (election-scoped)`); **(2)** the refusal tries to render `view('components.error_message')`; **(3)** the view does not exist (`resources/views/components/error_message.blade.php` missing [S]); **(4)** a `\Throwable` is raised; **(5)** `verify()`'s catch-all catches it; **(6)** the voter receives the generic error *"System error during verification. Please try again or contact support."* (`system`) and is redirected to the dashboard. No refusal reason reaches the voter. |
| **Failure chain** | `vote_post_check()` → IP-limit refusal → render `components.error_message` → missing view → `\Throwable` → `verify()` catch-all → generic `system` error → dashboard |
| **Scope of the defect [S]** | Every refusal branch of `vote_post_check()` renders the same missing view (`VoteController.php` ≈3180, ≈3220, ≈3246, ≈3259: code missing, IP limit, already voted, vote missing), so all of them degrade the same way at step 4. The IP case is the one proven by C4. Demo copies (`DemoVoteController` ≈3390–3426) are out of scope. |
| **Desired behaviour** | **Not decided by this item.** The correct refusal semantics must be decided and implemented separately. This item does not assume whether the solution is a new view, an existing standardized error component, an outcome-specific refusal response, or another mechanism. |
| **Relationship to 2B-4** | **This defect is discovered by the Q3 / C4 characterization but is NOT part of 2B-4's architectural scope.** 2B-4 concerns retiring the duplicate step-4 IP-density policy evaluation (Q3-D2). The missing error rendering is a separate error-handling concern, and it affects the non-IP refusals too. |
| **Evidence** | `SecurityPolicySurfaceCharacterizationTest::test_c4_characterizes_current_defect_step_4_refuses_the_voter_when_max_use_ip_address_is_unset`. It stays as is ("CURRENT DEFECT / CHARACTERIZATION ONLY") until this item is implemented, then gets replaced, not kept green. |
| **Out of scope** | the IP rule itself (2B-4), `MAX_USE_IP_ADDRESS` (Q3-D3), the verification flow, demo copies |

**Traceability:** Q3 proposal §9, §16 · characterization C4 · PBDIGIT-233 (D10, final cast resolved) · Q3-D2.
