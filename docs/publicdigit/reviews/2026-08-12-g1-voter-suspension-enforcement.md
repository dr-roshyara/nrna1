# G-1 runtime verification — is Chief voter-suspension enforced?

**Type:** Authorised runtime measurement · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Commission:** measure `PBDIGIT-68` `G-1` only. **Do not implement. Do not amend the Constitution. Do not refactor `status`.**
**Authorisation:** Product Owner, 2026-08-12 — *"you are authorized to suspend a voter"*, one controlled voter membership in the development environment.
**Ticket:** [`../backlog/PBDIGIT-68-election-membership-is-a-durable-entitlement.md`](../backlog/PBDIGIT-68-election-membership-is-a-durable-entitlement.md)

---

## Verdict in one line

> **G-1 is half right, and the wrong half was the headline.** A confirmed-suspended voter **is refused the ballot** — but **not** by the gate `PBDIGIT-68` examined, and **not** because anything reads `suspension_status`. The entry path still declares them eligible **and issues them a voting credential.**

| `PBDIGIT-68` claimed | Measured |
|---|---|
| *"A confirmed-suspended voter appears eligible and passes the server-side entry check"* | ✅ **CONFIRMED** — and they are additionally **issued a fresh `VoterSlug`** |
| *"suspension may not block voting"* (the implied conclusion) | ❌ **DISPROVEN** — every real ballot route refuses them, **HTTP 403** |

**The safeguard the ruling relies on does work. My characterisation of it did not.** I measured one predicate and generalised to "the voting path"; the voting path has **two** predicates with **opposite polarity**, and I had only found one.

---

## The two predicates

```
ElectionVotingController  (show():41-44, start():111)     status !== 'removed'    ALLOW unless removed
User::isVoterInElection() (User.php:315-328)              status === 'active'     DENY unless active
```

`confirmSuspension()` sets `status = 'inactive'`. `'inactive'` **is not** `'removed'` → passes the first. `'inactive'` **is not** `'active'` → fails the second.

**`isVoterInElection()` is the predicate that actually protects the ballot,** via `EnsureElectionVoter` middleware, which is on **every** real-election slug route — including `slug.code.create`, the exact target `start()` redirects to, and `slug.vote.submit`.

---

## The measurement — controlled A/B

**Subject:** one IERVP-created test voter on `election-2026-65b26848`, the only election currently `voting_active` / `canVote = TRUE`.
**Only variable changed:** the suspension state of that one membership row. Same voter, same election, same requests.
**Tenant context was set explicitly** to the election's organisation — otherwise `BelongsToTenant` hides the row and the experiment measures `PBDIGIT-65` instead of `G-1`.

### Phase A — control (not suspended)

```
ROW      status=active  suspension_status=none  has_voted=false
PRED-1   ElectionVotingController (status !== 'removed')  => PASS (eligible)
PRED-2   isVoterInElection()      (status === 'active')   => PASS (eligible)
START()  -> /v/{slug}/code/create          VERDICT  voting entry GRANTED (slug issued)
MIDDLEWARE EnsureElectionVoter -> HTTP 200  PASSED-THROUGH
```

### Suspension applied through the real two-actor domain path

```
after propose : status=active   suspension_status=proposed   proposed_by=<proposer>
four-eyes canConfirmSuspension(proposer)  = false   <- same person refused
four-eyes canConfirmSuspension(confirmer) = true    <- second person allowed
after confirm : status=inactive suspension_status=confirmed
```

### Phase B — suspension confirmed

```
ROW      status=inactive  suspension_status=confirmed  has_voted=false
PRED-1   ElectionVotingController (status !== 'removed')  => PASS (eligible)      <-- UNCHANGED
PRED-2   isVoterInElection()      (status === 'active')   => FAIL (refused)       <-- CHANGED
START()  -> /v/{slug}/code/create          VERDICT  voting entry GRANTED (slug issued)  <-- UNCHANGED
MIDDLEWARE EnsureElectionVoter -> HTTP 403  {"message":"You are not eligible to vote in this election."}
```

**One row changed; the two gates moved in opposite directions.**

---

## The seven questions, answered

| # | Question | Answer | Class |
|---|---|---|---|
| 1 | Can the suspended voter **reach the ballot**? | **No.** `EnsureElectionVoter` refuses at the first slug route, before any ballot renders | **OBSERVED AT RUNTIME** |
| 2 | Does the server **reject ballot access**? | **Yes at the slug routes (403). No at the entry point** — `start()` grants entry and issues a credential | **OBSERVED AT RUNTIME** |
| 3 | Does the server **reject vote submission**? | **Yes** — `slug.vote.submit` carries the same middleware. **Not separately exercised**: the voter cannot get past step 1, so submission was never reached | **OBSERVED IN CODE** (route middleware measured); submission itself **NOT VERIFIED** |
| 4 | **Which authority/predicate decides?** | `User::isVoterInElection()` — `role='voter' AND status='active'` — invoked by `EnsureElectionVoter`. **Not the Constitution, not `ElectionLifecycle`, not a policy** | **OBSERVED AT RUNTIME** |
| 5 | Does **`suspension_status` participate**? | **No. Nowhere.** Enforcement is a **side effect** of suspension writing `status='inactive'`. The only reader of `suspension_status` on the voting path is `TrustPolicyEvaluator:105`, which is self-documented *"OBSERVATIONAL evidence, NOT authority"* (`:77`) and whose value is folded into an audit hash and never used for a decision | **OBSERVED AT RUNTIME** |
| 6 | Is `status='inactive'` **distinguishable from "has voted"**? | **At the entry point yes** — `start():116` tests `has_voted` separately and flashes *"You have already voted."* **At the enforcing predicate no** — `isVoterInElection()` reads only `role` and `status`, so a suspended voter and a voter who has voted are the same fact to it, and produce the identical message | **OBSERVED IN CODE**; the suspended half **OBSERVED AT RUNTIME** |
| 7 | Does the behaviour **agree with the approved durable-entitlement model**? | **Partly.** The entitlement is durable (the row persists; only `status` changes) ✅ and the Chief's authority does bite ✅. But the entry surface tells a suspended voter they are **eligible** and hands them a **credential** ❌ — the system's representation of the entitlement contradicts the decision the Chief just made | **OBSERVED AT RUNTIME** |

---

## 🔴 The finding that replaces G-1

**Enforcement is incidental, not intentional — and that makes the obvious repair dangerous.**

Suspension is enforced **only** because it writes `status='inactive'` and one predicate happens to demand `'active'`. No code anywhere expresses *"a suspended voter must not vote."*

> ⚠️ **Therefore `G-5` (the `status` overload) is not merely a modelling blemish — it is currently load-bearing.** The natural clean-up — *"suspension should live in `suspension_status`, so stop overloading `status`"* — would, if `status` were left `'active'`, **silently delete the only enforcement of Chief suspension authority.** The system would still record the suspension, still show it in the admin UI, and quietly let the voter vote.
>
> **This is the single most important consequence of the measurement, and it is a warning to whoever implements `G-5`, not a licence to implement it.**

---

## Corrections to `PBDIGIT-68`

Recorded as corrections because the ticket is already committed.

| Item | Correction |
|---|---|
| **G-1** | **Materially corrected.** *"Suspension may not block voting"* → **suspension does block voting, at `isVoterInElection()`.** What survives is narrower and still real: the **entry surface does not enforce it** — misleading eligibility plus credential issuance |
| **G-6 (four-eyes not enforced)** | ❌ **WITHDRAWN.** Four-eyes **is** enforced — `ElectionVoterController:342` calls `canConfirmSuspension()` before `confirmSuspension()`, and `canConfirmSuspension()` requires `suspension_proposed_by !== $user->name`. **Measured:** proposer refused, second actor allowed. My original statement was true of the *method* and false of the *system* — I inspected the domain method and never checked its caller |
| **Cache staleness** (raised in conversation, never in the ticket) | ❌ **Withdrawn as stated.** `ElectionMembership::booted()` registers `static::saved`/`static::deleted` hooks that forget `user.{id}.voter.{election}`, so a membership **write** does invalidate. **But see the new finding below — a wrong-context *read* poisons the same key** |
| **G-5** | **Escalated** — from "model defect candidate" to **load-bearing**, per the warning above |

**Residual on four-eyes** (observation, not a defect): `canConfirmSuspension()` compares `->name`, not `->id`, so two committee members sharing a display name would defeat it; and the guard sits in the controller while `confirmSuspension()` itself is unguarded, so a future second caller would not inherit the control. **Both are `OBSERVED IN CODE`; neither is an established rule violation.**

---

## 🔴 New finding, outside G-1 — the voter predicate cache is tenant-unaware

Found while restoring state: the restored, active row still answered `false`.

```
cache key  =  user.{user_id}.voter.{election_id}          <-- no organisation component
the query  =  tenant-scoped by BelongsToTenant            <-- answer DEPENDS on organisation context
ttl 300s   ·  store: file
```

**Measured, both directions:**

```
DENY-poisoning
1. correct org, cold cache    -> true    (row is active)
2. no org context, cold cache -> false   (row hidden by tenant scope)
3. correct org, WARM cache    -> false   <-- wrong answer served to the correct context
4. correct org, cache cleared -> true    <-- proves it is the cache, not the query

ALLOW-leaking
1. correct org, cold cache        -> true   (cache now warm with true)
2. FOREIGN org, warm cache        -> true   <-- served across a tenant boundary
3. FOREIGN org, cache cleared     -> false  <-- the truthful answer
```

**Steps 3 vs 4 isolate the cache as the cause: identical context, only cache state differs.**

**Why this matters more than it looks:** it makes `PBDIGIT-65` **persistent and intermittent**. One page load in the wrong organisation context denies a legitimate voter for **five minutes**, including on requests where the context is correct — which is exactly the *"I am a voter but it says I am not eligible"* report that started this investigation, and exactly the shape that resists reproduction.

**The allow-direction crosses a tenant boundary at this predicate.** Whether a vote could actually be cast in a foreign organisation is **NOT VERIFIED** — `VerifyVoterSlugConsistency` and `EnsureRealVoteOrganisation` also sit on those routes and may refuse. **I did not test that, and it should not be assumed either way.**

**Raised as `PBDIGIT-69`.** It is not G-1 and does not belong inside `PBDIGIT-68`.

---

## Boundary — exactly what this probe did

| | |
|---|---|
| **Production code changed** | **None** |
| **Tests, fixtures, migrations, configuration changed** | **None** |
| **Constitution amended** | **No** |
| **Business data mutated** | **One membership row** (IERVP test voter, IERVP test election) — suspended, then **fully restored** |
| **Side effects created and disposed** | Two `VoterSlug` rows from the two `start()` calls — **deleted** (neither had a vote attached); cache entry **cleared** |
| **Verified after restore** | 20 memberships, **all `active` / `none`**; no `suspension_proposed_by` residue; no slug for the subject; cache entry absent; `votes` table unchanged at 1 row (pre-existing, from `PBDIGIT-38`) |
| **No vote was cast** | Confirmed — the subject never passed step 1 |
| **Credentials** | **None created, changed or recorded.** A password change was declined as outside the authorisation; the measurement was performed in-process instead |

### What this probe did NOT establish

* **The full HTTP stack was not exercised.** The controller action and `EnsureElectionVoter` were invoked **in-process** with a real authenticated user and correct tenant context. Route middleware composition was read from `route:list`, **not executed end-to-end.** A browser was not used.
* **The suspension HTTP endpoints were not exercised** — `authorize('manageVoters')` and the controller's preconditions were **read, not run**. Suspension was applied through the real domain methods, producing the authentic end state, and the four-eyes guard was invoked directly.
* **Vote submission was never reached**, so its rejection is inferred from shared route middleware.
* **Only Election-Only mode was measured.** Full Membership remains unreachable (`IERVP-2`, `IERVP-3`).

---

**Traceability:** `app/Models/User.php:315-328` · `app/Http/Middleware/EnsureElectionVoter.php:37,44-54` · `app/Http/Middleware/VoteEligibility.php:54,77-79` · `app/Http/Controllers/ElectionVotingController.php:41-44,111,116` · `app/Models/ElectionMembership.php:164-171,202-224,243-270` · `app/Http/Controllers/ElectionVoterController.php:302-308,342,347` · `app/Application/Election/Security/TrustPolicyEvaluator.php:77,105,123` · `app/Traits/BelongsToTenant.php:44-63` · `config/election.php` (`voter_cache_ttl` = 300) · measured 2026-08-12 on `election-2026-65b26848` (`voting_active`, `canVote=TRUE`).
