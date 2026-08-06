# PBDIGIT-39 — An unset config value blocks every voter, reported as a rate limit

**Type:** Defect (fail-closed on missing config) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` part (b)

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | 🔴 **Every voter is refused at the verification step** and told they have already voted too many times — with the number missing from the sentence |
| **Severity** | High. It presents as a policy decision, so an operator would look for the wrong cause |

---

## What was observed

Step 4 (`GET /v/{slug}/demo-vote/verify`) redirected to `/dashboard` for a voter who had **never voted**, logging:

```
Vote verification post-check failed
  → "Voting Limit Exceeded — There are already more than  votes cast from your IP address"
```

**Note the gap between "than" and "votes".** The limit interpolated into the message is empty, which is the tell.

## Root cause — `0 >= null` is true in PHP

```php
// app/Http/Controllers/Demo/DemoVoteController.php:3397
$max_use_clientIP = config('app.max_use_clientIP');      // -> NULL

// app/Helpers/helpers.php:113
if ($times_use_cleintIP >= $max_use_clientIP) {          // 0 >= null  ->  TRUE
```

`config/app.php:149` is `'max_use_clientIP' => env('MAX_USE_IP_ADDRESS')` — **no default**. With the variable unset the config is `null`, and PHP coerces `null` to `0`, so a voter with **zero** prior votes exceeds the limit.

**Proved by causation, not inference:** setting `MAX_USE_IP_ADDRESS=25` changed step 4 from a redirect to a rendered `Vote/DemoVote/Verify` page. Nothing else was changed between the two runs.

## A second, independent bug in the same comparison

```php
// app/Helpers/helpers.php:112-113
// if($times_use_cleintIP>$max_use_clientIP){     <- commented out
if($times_use_cleintIP >=$max_use_clientIP){      <- live
```

Someone changed `>` to `>=`. **With a configured limit of *n*, the *n*-th vote is refused rather than the *(n+1)*-th** — an off-by-one in a rule an operator would state as "at most n votes per IP". The commented-out line is the version matching that sentence.

**Both bugs sit in the same `if`, and they are separate**: one is a missing default, one is a boundary error. Fixing either alone leaves the other.

## Additional related gap — malformed payloads return 500

`DemoVoteController` validates selections as `nullable|array` (`:842-843`), then `sanitize_selection()` (`:1254`) indexes each element as an array — `$selection['no_vote'] = true` at `:1271`. A payload of bare candidate IDs throws `TypeError: Cannot access offset of type string on string` → **500**.

**Honest attribution: this was triggered by a malformed request of my own, not by the product's own frontend**, which sends `[{post_id, post_name, no_vote, candidates:[…]}]` (`resources/js/Pages/Vote/DemoVote/Create.vue:1061`). **A 500 where a 422 belongs is still a real robustness gap** on the most security-sensitive endpoint in the product.

## Acceptance criteria

* [ ] `config('app.max_use_clientIP')` has an **explicit, safe default**, and "unset" cannot mean "block everyone".
* [ ] Decide and record what the safe default *is*. **`0` is not safe** — `ElectionVotingController:226` already uses `config('app.max_use_clientIP', 0)`, which is equally blocking. **A missing IP limit should mean "no limit" or "fail loudly at boot", never "silently refuse every voter".**
* [ ] The boundary matches the stated rule: a limit of *n* permits *n* votes.
* [ ] The refusal message never renders an empty number; if the limit is unknown, the message must say so.
* [ ] Vote-selection payloads are validated structurally, returning **422** with field errors rather than 500.
* [ ] A test covers "limit unset" and "limit reached" as distinct cases.

## Related architectural debt — already known, not this story's to fix

`app/Services/Constitutional/DivergenceObserver.php:145,165` already records that **`check_ip_address` queries the global `codes` table — a multi-tenant violation (H.3)**. The demo caller passes `'demo_codes'`, so the table is parameterised, but the observation stands and is recorded there. **This story is about the comparison, not the tenancy.**

---

**Traceability:** `PBDIGIT-00` §Result (b) · `app/Helpers/helpers.php:92-113` · `app/Http/Controllers/Demo/DemoVoteController.php:3395-3402,842-843,1254-1271` · `config/app.php:149` · `app/Http/Controllers/ElectionVotingController.php:211-226` · `resources/js/Pages/Vote/DemoVote/Create.vue:1061` · `app/Services/Constitutional/DivergenceObserver.php:145,165`
