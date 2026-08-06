# PBDIGIT-39 — The IP vote limit is applied to demo elections, and has no safe default for real ones

**Type:** Defect (business-rule violation + fail-closed config) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06 · **Rewritten 2026-08-06** after the Product Owner stated the rule
**Found by:** `PBDIGIT-00` part (b)

| | |
|---|---|
| **Status** | ✅ **Defect A (demo exemption) FIXED AND VERIFIED 2026-08-06** — authorised by the Product Owner (*"if this ip restriction is used in Demo, then we need to remove it"*). ⬜ **Defect B (real-election default + off-by-one) still OPEN** |
| **Customer impact** | **Demo:** a customer evaluating the product is refused after voting once — *and told they have already voted too many times*. **Real:** wherever `MAX_USE_IP_ADDRESS` is unset, **every** voter is refused |

---

## 📜 The business rule (Product Owner, 2026-08-06)

> **`MAX_USE_IP_ADDRESS` applies only to REAL elections, not to DEMO.** A voter may vote **multiple times** in a demo election; in a real election they may not.

**This rule is already written in the repository** — `routes/election/electionRoutes.php:538-539`:

```
// Demo elections have the SAME workflow as real voting:
// - No IP restrictions (allows testing from same IP)
// - Allows multiple test votes
```

**So the intent was documented and the code does the opposite.**

## Defect A · Demo elections are IP-limited — they must not be

| Site | What it does |
|---|---|
| `app/Http/Controllers/Demo/DemoVoteController.php:3395-3398` | reads `config('app.max_use_clientIP')` and calls `check_ip_address($ip, $max, 'demo_codes')` — **an IP restriction on the demo flow** |
| `app/Http/Controllers/VoteController.php:3079-3089` | **already branches on demo** (`$election->type === 'demo' ? DemoCode : Code`) to pick the table — **then applies the limit anyway** |

**The second site is the more revealing one:** someone recognised demo needed different handling, changed *which table is counted*, and did not change *whether the limit applies at all*. **A demo-aware branch that still enforces a real-election rule reads as correct at a glance.**

**Required behaviour:** for `$election->type === 'demo'`, the IP check is **skipped entirely** — not given a high limit, not counted against a different table. *(A high limit is still a limit, and it would resurface as a support ticket at the worst moment: during a customer evaluation.)*

## Defect B · On the real path, a missing limit refuses everyone

**Independent of Defect A, and it applies where the limit legitimately belongs.**

```php
// VoteController:3089 and helpers.php:113
if ($votesFromIP >= $max_use_clientIP)     // $max is null when unset
```

`config/app.php:149` is `'max_use_clientIP' => env('MAX_USE_IP_ADDRESS')` — **no default**. Unset ⇒ `null`; PHP coerces `null` to `0`; so a voter with **zero** prior votes satisfies `0 >= null` and is refused.

**The user-facing message is worse than the bug:** *"There are already more than&nbsp;&nbsp;votes cast from your IP address"* — **the number renders empty**, so an operator debugging it is told nothing.

**Proved by causation, not inference:** setting `MAX_USE_IP_ADDRESS=25` turned step 4 of the journey from a redirect into a rendered page, with nothing else changed.

### Defect B2 · An off-by-one in the same comparison

```php
// app/Helpers/helpers.php:112-113
// if($times_use_cleintIP>$max_use_clientIP){     <- commented out
if($times_use_cleintIP >=$max_use_clientIP){      <- live
```

`>` was changed to `>=`, so **a configured limit of *n* refuses the *n*-th vote**, not the *(n+1)*-th. The commented-out line matches how an operator would state the rule ("at most n votes per IP"). **Two bugs in one `if`, and they are independent** — fixing either alone leaves the other.

## ⚠️ Correction to `PBDIGIT-00`'s record

`PBDIGIT-00` §(b) lists `MAX_USE_IP_ADDRESS` among *"environment additions needed in `.env.testing`"*, alongside `APP_KEY` and `MAIL_MAILER`.

**Under this business rule that was wrong.** `APP_KEY` and `MAIL_MAILER` were genuine environment gaps. **`MAX_USE_IP_ADDRESS` was not** — the walk exercised a **demo** election, which should never have consulted an IP limit at all. **Setting it masked Defect A instead of filling an environment gap**, and it is only because the Product Owner stated the rule that the difference is visible.

**The lesson, recorded because it will recur:** *making a blocked test pass is not the same as establishing that it should have passed.* The env var was treated as missing configuration when it was evidence of a defect.

## Related — three IP-limit implementations, none authoritative

| Location | Shape |
|---|---|
| `helpers.php:92` `check_ip_address()` | global count, table passed as an argument |
| `VoteController:3079-3089` | inline, election-scoped, demo-aware table selection |
| `ElectionVotingController:211-226` | layered, with `config('app.max_use_clientIP', 0)` as a "global fallback" — **and `0` is equally blocking** |

**Same shape as `PBDIGIT-35`:** several mechanisms answer one question and none is declared authoritative. **Deciding which owns "how many votes may come from one IP" should precede editing any of them** — otherwise the fix picks a winner by accident.

⚠️ `DivergenceObserver.php:145,165` already records that `check_ip_address` queries the **global** `codes` table — a multi-tenant violation (H.3). **Known debt, not this story's to fix.**

## ✅ OUTCOME — Defect A fixed and verified 2026-08-06

**Two sites changed, and the second one mattered more than it looked:**

| Site | Change |
|---|---|
| `DemoVoteController::vote_post_check` | the IP check **removed** — demo consults no limit at all |
| `VoteController::vote_post_check` | demo **exempted before** both the election-scoped check and the global fallback |

⚠️ **A correction to my own first attempt.** My initial `VoteController` edit kept the existing condition `isset($election) && $election->type === 'demo'`. **`$election` is not a parameter of that method**, so that expression could never be true — the "demo-aware" table selection it guarded had always been unreachable, and my edit faithfully preserved a no-op. Demo-ness is now derived from **`$code instanceof DemoCode`**, which is genuinely in scope: `VoteController:2330` selects `DemoCode` for demo elections, so the information was on `$code` all along.

### Verification — the rule tested in both halves, with the limit UNSET

**`MAX_USE_IP_ADDRESS` was removed from the environment entirely** — the exact state that previously refused every voter.

| | Result |
|---|---|
| **First demo vote** | ✅ accepted — `demo_votes = 1`, `demo_results = 2` |
| **Second demo vote, same IP** | ✅ accepted — **`demo_votes: 1 → 2`** |

**The second test was constructed so it could not pass for the wrong reason.** `check_ip_address()` counted `demo_codes WHERE client_ip = X AND has_voted`, so the run **kept** that evidence rather than clearing it: 1 row with `has_voted = true` and `client_ip = 127.0.0.1` was on record when the second vote was cast. Step 4 rendered `Vote/DemoVote/Verify` (200) where it previously returned *"Voting Limit Exceeded"*.

**Not changed:** real elections still enforce the limit, and **Defect B is untouched** — an unset limit still refuses every voter in a *real* election. That is a separate decision (what "safe default" means) and was not authorised here.

## Acceptance criteria

* [ ] **Demo elections skip the IP check entirely** — verified by voting twice in a demo election from one IP.
* [ ] **Real elections still enforce it** — verified by exceeding the limit and being refused.
* [ ] `config('app.max_use_clientIP')` has an explicit, safe default; **"unset" must never mean "refuse everyone"**. Decide and record what safe means — **`0` is not it** (`ElectionVotingController:226` already defaults to `0`).
* [ ] A limit of *n* permits *n* votes.
* [ ] The refusal message never renders an empty number.
* [ ] Which mechanism owns the rule is decided and recorded; the others defer to it.
* [ ] Tests cover: **demo (unlimited)** · real under the limit · real at the limit · **limit unset**.

---

**Traceability:** Product Owner rule, 2026-08-06 · `routes/election/electionRoutes.php:538-539` (the rule, already documented) · `app/Http/Controllers/Demo/DemoVoteController.php:3395-3398` · `app/Http/Controllers/VoteController.php:3079-3089,3104` · `app/Helpers/helpers.php:92-113` · `config/app.php:149` · `app/Http/Controllers/ElectionVotingController.php:211-226` · `app/Services/Constitutional/DivergenceObserver.php:145,165` · `PBDIGIT-00` §Result (b) + §Environment *(corrected here)* · `PBDIGIT-35` (same "no authoritative mechanism" shape)
