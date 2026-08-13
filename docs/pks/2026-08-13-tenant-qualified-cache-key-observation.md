# PKS Observation — tenant-dependent answer requires a tenant-qualified cache key

**Type:** PKS **OBSERVATION** — *not a standard, not promoted, not an architectural rule, not authorization for any change.*
**Date:** 2026-08-13 · **Recorded by:** Session 2 (governance stream) · **Maturity:** observation · **n = 2 instances, one repository**
**Status discipline (`ES-006.1`):** two instances meet the threshold for *recording an observation*; **promotion requires operational evidence plus governance decision — Human-decides.** The Principal Architect's review (2026-08-13) explicitly endorsed recording and explicitly declined promotion. Until promoted, this document must not be cited as binding.

---

## The observation

> **When a cached value's computation depends on ambient tenant/organisation context, but its cache key does not include that context, the cache transports answers across contexts — poisoning reads in contexts that would have computed a different answer.**

```
tenant-DEPENDENT answer  +  tenant-FREE cache key  =  context contamination
```

The write-path corollary, also measured: write-hook invalidation (`saved`/`deleted` → `Cache::forget`) addresses stale-after-WRITE only; **it cannot prevent poisoning created by a READ performed in the wrong context.**

## The two instances (evidence)

| # | Site | Key | Dependency | Evidence strength |
|---|---|---|---|---|
| 1 | `User::isVoterInElection()` (`User.php:315-328`) | `user.{id}.voter.{election_id}`, TTL 300 | query runs through `BelongsToTenant`'s global scope (`TenantContext ?? session`) | 🔴 **runtime-PROVEN** — P6 controlled A/B (`fc86049f`): wrong-context FALSE replayed into the correct context; cleared cache in the correct context → TRUE. Write-hooks present (`ElectionMembership:243-248`) and measured insufficient against read-poisoning |
| 2 | `Election::getVoterCountAttribute` (`Election.php:336`) | `election.{id}.voter_count` | `membershipVoters()` → scoped relation, no bypass | ⚠️ **STATIC only** — same pattern; not runtime-reproduced (family audit `9cf441fc` §5) |

**Contrast case in the same file:** `Election::getCandidatesCountAttribute` uses `Candidacy::withoutGlobalScopes()` — pattern-clean. Adjacent accessors differing is the inconsistency in miniature (audit §5).

## Scope and non-claims

- This records a **pattern**, not a defect census: only the two instances above are evidenced; no repo-wide sweep of cache keys was performed.
- It does **not** claim every tenant-free key is wrong — a key may be tenant-free because its **answer** is tenant-independent (measured counter-example: `User::getDashboardRoles` computes via unscoped `DB::table()`; pattern-clean).
- It authorizes **no repair**: instance 1 falls under the open 65/69 disposition (Option A candidate grant, unsigned); instance 2 is explicitly **outside** that candidate grant per the Principal-Architect recommendation (static evidence only).
- If ever promoted, the owning question per `ES-005.4`/`ES-001.1` is which **existing** standard it extends — never a new standalone rule.

**Traceability:** P6 (`fc86049f`) · family audit + §11 corrections (`9cf441fc`, `67ace629`) · 65/69 disposition package §4.3 · `PBDIGIT-69` ticket (G-1 measurement) · Decision A (CLOSED 2026-08-13) · `ES-006.1`.
