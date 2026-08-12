# PBDIGIT-69 — The voter-eligibility cache key is tenant-unaware

**Type:** Defect (confirmed at runtime) · **Epic:** `PBDIGIT-EPIC-02` Membership Management · **Created:** 2026-08-12
**Found by:** IERVP `G-1` verification — **while restoring state, not while looking for it**
**Evidence:** [`../reviews/2026-08-12-g1-voter-suspension-enforcement.md`](../reviews/2026-08-12-g1-voter-suspension-enforcement.md) — measured, both directions
**Status:** `OPEN — NOT AUTHORISED FOR IMPLEMENTATION.` **`D-1` below is a business decision.**

| | |
|---|---|
| **Customer impact** | 🔴 **A legitimate voter is told "you are not eligible to vote" for up to 5 minutes — including on requests where everything is correct.** One page load in the wrong organisation context is enough to cause it |
| **Why it resisted diagnosis** | It is **intermittent and self-healing**. It expires on its own, so by the time anyone investigates, the system answers correctly |
| **Severity** | **High** — this is very likely the mechanism behind the original *"I am a voter but it says I am not eligible"* report, and it also **crosses a tenant boundary** in the opposite direction |
| **Confidence** | **High** — measured by isolating the cache from the query (steps 3 vs 4 below) |

---

## The defect

```
cache key  =  user.{user_id}.voter.{election_id}      <-- NO organisation component
the query  =  tenant-scoped by BelongsToTenant        <-- the ANSWER depends on organisation context
ttl        =  300 seconds        store = file
```

`User::isVoterInElection()` (`User.php:315-328`) caches the result of a query whose answer **depends on the tenant context**, under a key that **does not include the tenant context.**

> **So the cache remembers *an* answer and replays it in a context where that answer is wrong.**

## Measured — both directions

### Deny-poisoning — a legitimate voter is refused

```
1. correct org context, cold cache    -> true    (membership row is active)
2. no org context (platform), cold    -> false   (row hidden by the tenant scope)
3. correct org context, WARM cache    -> false   <-- the wrong answer, served to the CORRECT context
4. correct org context, cache cleared -> true    <-- proves it is the cache, not the query
```

**Steps 3 and 4 are the same context and the same row. Only the cache differs.**

### Allow-leaking — the predicate answers across a tenant boundary

```
1. correct org context, cold cache -> true    (cache now warm with true)
2. FOREIGN org context, warm cache -> true    <-- served across the tenant boundary
3. FOREIGN org context, cleared    -> false   <-- the truthful answer
```

**Whether a vote could actually be cast in a foreign organisation is `NOT VERIFIED`.** `VerifyVoterSlugConsistency` and `EnsureRealVoteOrganisation` also sit on those routes and may refuse. **This was not tested and must not be assumed either way.**

---

## Why this predicate matters

`isVoterInElection()` is not a convenience helper. It is **the predicate that protects the ballot** — invoked by `EnsureElectionVoter`, which is on **every** real-election slug route (`slug.code.create` … `slug.vote.submit`), and again by `VoteEligibility:103`.

**The `G-1` measurement established it is the only thing enforcing Chief voter-suspension.** So a cache that can answer it wrongly is a cache that can wrongly deny the franchise, and — for the 300-second window — wrongly affirm it.

---

## Relationship to `PBDIGIT-65`

**`PBDIGIT-65` is the cause; this is what makes it persist.** `PBDIGIT-65` records that tenant context decides eligibility. This ticket records that **the wrong answer then outlives the wrong context**, leaking into subsequent correct-context requests for five minutes.

> **Fixing `PBDIGIT-65` alone would leave a five-minute tail in which the defect still reproduces.** They should be resolved together, or this one first.

---

## 🟡 Business decision required

| # | Question | Why engineering cannot decide it |
|---|---|---|
| **D-1** | **Is voter eligibility a per-organisation fact or a global one?** If a person's voter status in an election is a property of the **election** (elections belong to exactly one organisation), then tenant-scoping this query is arguably wrong in the first place — and the cache key is a symptom, not the disease | Two coherent designs exist: **(a)** the predicate is tenant-scoped and the key must include the tenant; **(b)** the predicate resolves the organisation *from the election* and never consults ambient context. **(b)** would make the whole class of `PBDIGIT-65` defects unreachable. **This is a domain-ownership question, not a caching question** |

**Recorded as options, neither endorsed:**

| Option | Mechanism | Implies |
|---|---|---|
| **A · Include the tenant in the key** | key becomes `user.{u}.voter.{e}.org.{o}` | ambient context stays authoritative; smallest change; **the wrong-context answer is still computed and served, just no longer cached** |
| **B · Resolve the organisation from the election** | drop ambient scoping in this predicate — the election already determines its organisation | **eliminates the defect class** rather than the cache symptom; larger blast radius; needs `D-1` answered |
| **C · Do not cache this predicate** | remove the 300s cache | simplest and safest; costs one indexed query per protected request |

---

## Acceptance criteria — business behaviour

* [ ] **A voter whose membership is valid is never told they are ineligible because of something that happened in a different organisation context.**
* [ ] **An eligibility answer computed for one organisation is never served for another.**
* [ ] **The behaviour is identical on a cold and a warm cache** — a cache may make the answer faster, never different.
* [ ] **A regression test warms the cache in one tenant context and asserts the answer in another** — the current test estate cannot catch this, because a single-context test never observes it.

## Explicit non-goals

* ❌ **Not choosing between A, B and C** — `D-1` decides it.
* ❌ **Not fixing `PBDIGIT-65`** (its own ticket), though the two are related as cause and persistence.
* ❌ **Not touching `ElectionMembership::booted()`** — the **write**-side invalidation is correct and was verified; this defect is on the **read** side.
* ❌ **Not verifying whether a foreign-context vote can actually be cast** — needs its own authorisation.

## Related

* **[`G-1` review](../reviews/2026-08-12-g1-voter-suspension-enforcement.md)** — where this was found, with the raw measurements.
* **`PBDIGIT-65`** — tenant context decides eligibility. **Cause; this is persistence.**
* **`PBDIGIT-68`** `G-1`/`G-5` — why this predicate is the ballot's only suspension enforcement.
* **`PBDIGIT-49`** — voter eligibility has two homes.

---

**Traceability:** `app/Models/User.php:315-328` (`isVoterInElection`, `Cache::remember`), `:334-337` (`invalidateVoterCache`) · `app/Traits/BelongsToTenant.php:44-63` (scope resolves `TenantContext::get() ?? session('current_organisation_id')`, falling back to the **platform** organisation) · `app/Http/Middleware/EnsureElectionVoter.php:37` · `app/Http/Middleware/VoteEligibility.php:103` · `app/Models/ElectionMembership.php:243-270` (write-side invalidation, correct) · `config/election.php` `voter_cache_ttl` = 300 · `config/cache.php` default = `file` · measured 2026-08-12.
