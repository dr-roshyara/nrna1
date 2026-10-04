# 01 — C1: One Voting-Entitlement Decision, Scoped by the Election

**Slice:** `PBDIGIT-213` C1 · **ADR:** `ADR_20261004_1549` §2.2 invariants 8, 10, and §2.4

---

## Purpose

Before C1, five places in the code each answered *"may this user vote in this election?"* with their own query:

| Site | Problem |
|---|---|
| `User::isVoterInElection()` | its own query and its own `v2` cache |
| `EnsuresVoterMembership`, uncached branch (used by `vote.store` and `vote.verify`) | **no organisation check**, and it kept the ambient tenant scope, so under the wrong ambient tenant a legitimate voter was **denied** at the ballot |
| `ElectionVotingController@show` | reported `inactive` and `invited` voters as eligible |
| `ElectionVotingController@start` | blocked only `removed`, so an **inactive voter received a voting credential** |
| `EnsureElectionVoter`, `VoteEligibility` | went through `User::isVoterInElection()` |

After C1, every site asks **one port**, and none of them restates the rule.

## Where it fits

```
Callers: User::isVoterInElection · EnsuresVoterMembership · ElectionVotingController · (middleware via User)
                │
                ▼
Application port   App\Contexts\Elections\Application\Ports\VotingEntitlementQuery
                │      standingOf(userId, electionId)   decide(userId, electionId)
                ▼
Infrastructure     CachedVotingEntitlementQuery  ──►  EloquentVotingEntitlementQuery
                   (standing cached, v3 key)          (one query; scope from the election)
                                                         │ builds
                                                         ▼
Domain             VotingEntitlementContext ──► VotingEntitlementPolicy ──► VotingEntitlementReason
```

## Key files

| File | Layer |
|---|---|
| `app/Contexts/Elections/Domain/ValueObjects/VotingEntitlementContext.php` | Domain: readonly facts (registered, role, status, organisation match) |
| `app/Contexts/Elections/Domain/ValueObjects/VotingEntitlementReason.php` | Domain: the single outcome (`Entitled` or one denial reason) |
| `app/Contexts/Elections/Domain/Policies/VotingEntitlementPolicy.php` | Domain: `standing()` and `decide()` |
| `app/Contexts/Elections/Application/Ports/VotingEntitlementQuery.php` | Application port |
| `app/Contexts/Elections/Infrastructure/Queries/EloquentVotingEntitlementQuery.php` | Infrastructure: uncached and authoritative |
| `app/Contexts/Elections/Infrastructure/Queries/CachedVotingEntitlementQuery.php` | Infrastructure: caches standing only |
| `app/Contexts/Elections/Infrastructure/Queries/VotingEntitlementCacheKey.php` | the single cache-key name |
| `app/Providers/AppServiceProvider.php` | binds the port to the cached decorator |

## How it works

### The decision (pure domain)

```php
// VotingEntitlementPolicy::standing() — first failing condition wins
if (! $context->isRegistered)          return VotingEntitlementReason::NotRegistered;
if ($context->role !== 'voter')        return VotingEntitlementReason::NotVoterRole;
if (! $context->organisationMatches)   return VotingEntitlementReason::OrganisationMismatch;
if ($context->status !== 'active')     return VotingEntitlementReason::NotActive;
return VotingEntitlementReason::Entitled;

// decide() = standing, then the election lifecycle
return $electionVotable ? VotingEntitlementReason::Entitled : VotingEntitlementReason::ElectionNotVotable;
```

`VotingEntitlementPolicy` **composes** its inputs and owns none of them. It knows nothing about ballots, `has_voted`, codes, IP or device rules, or the suspension workflow.

### Standing vs decision

| Method | Answers | Used for |
|---|---|---|
| `standingOf()` | Is this person an entitled voter **in this election**? (registration, role, organisation, active) | `isVoterInElection`, the middleware, the in-transaction check, `show()`'s `isEligible`, `start()` |
| `decide()` | Standing **and** is the election votable now? | `show()`'s `canVote` |

`show()` keeps these separate on purpose:

```php
$entitlement = app(VotingEntitlementQuery::class);
$isEligible  = $entitlement->standingOf($user->id, $election->id)->isEntitled();             // standing
$hasVoted    = $this->membershipHasVoted($user, $election);                                   // ballot-side fact
$canVote     = $entitlement->decide($user->id, $election->id)->isEntitled() && ! $hasVoted;  // decision
$lifecycle   = ElectionLifecycle::of($election);   // display props and audit log only
```

An active voter whose election is not open yet is still `isEligible = true`. `Election/Show.vue` relies on this for its *"registered, not yet open"* and *"period ended"* states.

### Scope comes from the election (the security correction)

```php
$election   = Election::withoutGlobalScope('tenant')->find($electionId);          // SoftDeletes kept
$membership = ElectionMembership::withoutGlobalScope('tenant')
    ->where('election_id', $election->id)->where('user_id', $userId)
    ->first(['role', 'status', 'organisation_id']);
// organisationMatches = $membership->organisation_id === $election->organisation_id
```

- Only the `tenant` scope is removed. The **election** decides which organisation is required, and the ambient `TenantContext` or session never does (PBDIGIT-65/69).
- `users.organisation_id` is **never** consulted. Belonging to the organisation is not the same as being a voter in one of its elections.
- A membership claiming a different organisation from its election cannot be stored, because a composite FK `(election_id, organisation_id) → elections(id, organisation_id)` prevents it. The policy still returns `OrganisationMismatch` as defence in depth.

### Cache

- `CachedVotingEntitlementQuery` caches **standing only**, under `user.{u}.voter.v3.{e}` (`VotingEntitlementCacheKey::for()`), with TTL `config('election.voter_cache_ttl', 300)`.
- `decide()` is never cached: the lifecycle is always evaluated fresh.
- **Invalidation:** `ElectionMembership::booted()` saved/deleted hooks and `User::invalidateVoterCache()` both forget the key.
- **Authoritative path:** `EnsuresVoterMembership` with `$useCache = false` (`vote.store`, `vote.verify`) resolves `EloquentVotingEntitlementQuery` directly, so no cached entry can authorise a ballot.
- The old `voter.v2` key is no longer read anywhere. Old entries are unreachable and expire with the TTL.

## How to use or extend

- **Need "is this user a voter in election X?"** Call `app(VotingEntitlementQuery::class)->standingOf(...)` or `$user->isVoterInElection(...)`. **Never** write `->where('role', 'voter')->where('status', 'active')` again. `tests/Architecture/VotingEntitlementSinglePointTest` fails if you do this at an entitlement site.
- **Need a new entitlement condition?** Add it to `VotingEntitlementContext` and `VotingEntitlementPolicy` with a new `VotingEntitlementReason` case, and add a row to the unit table test. C2 does exactly this for admission.

## Testing

| Test | What it proves |
|---|---|
| `tests/Unit/Contexts/Elections/VotingEntitlementPolicyTest.php` | each condition yields exactly one reason in a fixed order; lifecycle last |
| `tests/Feature/Election/VotingEntitlementUnificationTest.php` | port answers; ambient org B neither denies at the in-transaction check nor at `start()`; inactive/invited/removed/soft-deleted denied at every site; other election and org member without membership → `NotRegistered`; composite FK; `show()` and `start()` agree |
| `tests/Architecture/VotingEntitlementSinglePointTest.php` | no entitlement site restates the predicate; `User::isVoterInElection` delegates; the domain imports no `Illuminate` |

```bash
php artisan test --env=testing tests/Unit/Contexts/Elections/VotingEntitlementPolicyTest.php \
  tests/Feature/Election/VotingEntitlementUnificationTest.php tests/Architecture/VotingEntitlementSinglePointTest.php
```

## Pitfalls

- **Don't rebuild standing from `decide()`.** `ElectionNotVotable` does not mean "eligible"; ask `standingOf()`.
- **`has_voted` is not entitlement.** It is a ballot-side fact and is kept outside the decision (anonymity, ADR-T11). Note that `ElectionMembership::markAsVoted()` currently has no callers, which is a known gap outside this story.
- **Don't resolve the cached port in the in-transaction check.** Use `EloquentVotingEntitlementQuery`.
- **Fixtures:** don't `$org->users()->attach()` a factory user (`UserFactory` already creates the role row), and use `ElectionScenarioFactory::votingActive()` for a votable election.

**Traceability:** `PBDIGIT-213` C1 · `ADR_20261004_1549` §2.2 (inv. 8, 10), §2.4 · PBDIGIT-65/69 · ADR-002 clarification (i).
