# PBDIGIT-40 — The voting completion page has never worked

**Type:** Defect · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` part (b)

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | **A reachable route returns 500. It is NOT on the voting happy path** |
| **Severity** | **Low** — downgraded 2026-08-06 (was Medium) |

> ⚠️ **CORRECTION, 2026-08-06 — this story's original claim was wrong.** It said *"The last thing a voter sees after voting is a 500 error page."* **It is not.** After `PBDIGIT-38` was fixed and a vote was cast successfully, the final submission was observed redirecting to **`/v/{slug}/demo-vote/verify-show`**, which renders `Vote/DemoVote/VerifyVotingCode` with **HTTP 200**. **`thank-you` is not on the redirect path.**
>
> **Cause of the error:** the original claim was inferred from *"this route 500s"* plus *"it is named thank-you"*. **Route naming is not evidence of flow position** — the flow had never been walked when the claim was written, so the position was assumed. It has now been walked.
>
> **What remains true:** the route exists, is reachable by URL, and returns 500. So the defect is real — it is just **not** the end of the journey.

---

## What is true

```php
// app/Http/Controllers/Demo/DemoVoteController.php:3142
public function thankyou(){
       return Inertia::render('Thankyou/Thankyou', [
             'vote' =>$vote,          // <- $vote is NEVER DEFINED
            //  'name'=>auth()->user()->name,
            //  'nrna_id'=>auth()->user()->nrna_id,
            //  'state' =>auth()->user()->state
    ]);
```

`$vote` is undefined in the method's scope. Every request produces:

```
ErrorException: Undefined variable $vote at DemoVoteController.php:3144  ->  HTTP 500
```

**Observed on both `GET /demo/vote/thank-you` and `GET /v/{slug}/demo-vote/thank-you`.**

**The route reaches this method**: it is declared as `[DemoVoteController::class, 'thankYou']` (capital Y), and PHP method names are case-insensitive, so `thankYou` resolves to `thankyou`. **There is no second, working implementation** — the middleware chain was observed passing cleanly right up to the exception.

## What this tells us beyond the bug

**This method has never been executed successfully.** Three commented-out lines beside the fault suggest it was mid-edit when it was committed, and nothing since has run it — which is consistent with `PBDIGIT-36`: no test reaches step 5.

## Acceptance criteria

* [ ] The completion page renders.
* [ ] Decide what it should show. **The commented-out lines reference `auth()->user()->nrna_id` and `->state`; `nrna_id` is legacy naming and `state` overlaps the retired voter columns of `PBDIGIT-35`** — so do not simply uncomment them.
* [ ] 🔒 **Whatever it shows must not link a voter to their vote** (ADR-T11). A page that renders "your vote" immediately after saving is exactly where anonymity is most easily broken — `'vote' => $vote` was heading in that direction, and that question must be answered before the variable is defined.
* [ ] A test asserts step 5 returns 200 after a completed vote — which requires **`PBDIGIT-38`** first, since no vote can currently be saved.

**Ordering:** `PBDIGIT-38` → `PBDIGIT-40`. There is no point rendering a completion page for a vote that was never stored.

---

**Traceability:** `PBDIGIT-00` §Result (b) · `app/Http/Controllers/Demo/DemoVoteController.php:3142-3148` · `routes/election/electionRoutes.php` (`demo-vote.thank-you`, `slug.demo-vote.thank-you`) · `PBDIGIT-38` · `PBDIGIT-36` · `PBDIGIT-35` · ADR-T11
