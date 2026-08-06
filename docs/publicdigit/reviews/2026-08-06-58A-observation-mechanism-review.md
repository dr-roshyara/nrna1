# Mechanism review — where to observe legacy election-state reads (`PBDIGIT-58A`)

**Date:** 2026-08-06 · **Type:** Design review — **no implementation, no recommendation acted on**
**Commissioned:** Product Owner — *"Review the architectural options for observing legacy attribute reads… Recommend the observation point that best satisfies 58A's requirement of 'zero production readers' while remaining behaviour-preserving. Do not implement yet."*
**Why it deserves a review:** `58A` does not only serve this migration. **It creates an observation mechanism intended for reuse** on `PBDIGIT-45`, `PBDIGIT-49`, and any future legacy-consumer migration.

---

## The gap that triggered this

**The SQL-listener prototype is insufficient, and my own inventory proves it.** `PBDIGIT-48` lists consumers that read the field *after* the model is loaded, with no predicate in any query:

`CommissionDashboardController:28` · `SetupDemoElection:362` · `SetupPublicDemoElection:295` · `ListAllElections:38` · `Demo/DemoVoteController:573, 2461` · `Demo/PublicDemoController:239, 451-454`

> **The Definition of Done is "zero production readers", not "zero SQL predicates".** A mechanism that cannot see `$election->status` cannot certify that target.

## Framework facts established for this review

| Fact | Evidence | Consequence |
|---|---|---|
| **A get-mutator bypasses the cast entirely** | `HasAttributes::transformModelValue():2223-2224` returns early when `hasGetMutator($key)` | **An accessor on a cast field must reproduce the cast**, or callers receive a different type |
| `is_active` **is** cast to `boolean`; `status` and `state` are **not** cast | `Election::getCasts()` | The two fields need **different** treatment — one is a cast field, one is not |
| `Election` defines **6 existing get-mutators** and overrides neither `getAttribute` nor `__get` | `app/Models/Election.php:334,348,365,382,893,1190` | Accessors are idiomatic here; the framework path is intact |
| Custom casts have **house precedent** | `app/Contexts/Membership/Infrastructure/Casts/{MemberStatusCast,MemberIdCast,PersonalInfoCast}.php` | A cast-based observer would not be a novel pattern in this repository |
| 🔴 **The whole Election model is serialised into page props** | `ElectionManagementController:805` — `'election' => $election` | **Attribute-level observation fires on every serialisation**, not only on decisions |
| `DeprecationAccessGuard::checkFieldAccess($field, $context, $mode)` exists, with an `audit_only` mode | `…/Deprecation/DeprecationAccessGuard.php` | **The recording sink already exists.** This review is only about the *interception point* |

## 🔑 The question the serialisation finding forces

**What counts as a "reader"?**

| Reading | Is it a consumer? |
|---|---|
| `if ($election->status === 'active')` — a capability **decides** | ✅ unambiguously |
| `'election' => $election` — the model is **serialised** to the frontend | ⚠️ **it depends on whether the frontend branches on the field** |
| `{$election->status}` in CLI output | ⚠️ display only, but still a reader |

**This matters because a mechanism that reports serialisation as a consumer will produce a list nobody can act on, while one that ignores it may miss a Vue component branching on `election.status`.**

> **Recommendation on the definition, for the Product Owner to confirm:** a reader is **any code path that obtains the value**, and the report must **classify** each hit — `decision` · `serialisation` · `display` — using the call stack. **Serialisation hits are not noise; they are a pointer to a possible frontend consumer**, which is a category the static inventory never covered at all.

## The options

| | Mechanism | Covers predicates | Covers attribute reads | Behaviour-preserving | Reusable | Cost |
|---|---|---|---|---|---|---|
| **1** | **SQL listener** (`DB::listen`) — *prototyped* | ✅ | 🔴 **no** | ✅ **by construction** — cannot alter a query or its result | ✅ any table, any field | low |
| **2** | **Model accessor** (`getStatusAttribute`) | 🔴 no | ✅ | ⚠️ **`status` yes** (no cast to bypass) · 🔴 **`is_active` no** — must reproduce the boolean cast | ⚠️ per field, per model | low |
| **3** | **Custom cast** (`CastsAttributes`) | 🔴 no | ✅ | ✅ **for a cast field** — the observer *is* the cast, so the contract cannot be bypassed · ⚠️ **for `status` it introduces a cast where none existed** | ✅ **house precedent** | low |
| **4** | **Trait on the model** | 🔴 no | ✅ | same risk profile as 2 — a trait is a delivery vehicle, not a different interception point | ✅ | low |
| **5** | **Override `getAttribute` / `__get`** | 🔴 no | ✅ **all fields at once** | ⚠️ intercepts **every** attribute — widest blast radius on the hottest path in Eloquent | ✅ | medium |
| **6** | **Compatibility adapter** (`ElectionReadModel`) | ✅ | ✅ | ✅ | ✅ | 🔴 **useless for observation** — it has **zero external callers**, so consumers would have to be migrated *to* it first. **That is the migration, not the measurement** |
| **7** | **Static analysis** | ✅ | ✅ | ✅ | ✅ | 🔴 **already failed three times** — 520 candidate statements, two false attributions |

## Recommendation

> **A pair: keep the SQL listener (1), and add a per-field attribute interceptor chosen by whether the field is cast — a custom cast for `is_active` (3), an accessor for `status` (2).**

**Why the pair rather than one mechanism:** predicates and attribute reads are genuinely different access paths, and no single interception point covers both without cost. `DB::listen` is free and catches every predicate from every code path — including queues, CLI and packages that no static pass associates with elections. Attribute interception catches the rest.

**Why per-field rather than uniform:** the framework decides this, not taste. `transformModelValue` returns early on a get-mutator, so:

* **`is_active`** → **custom cast.** The observer becomes the cast, so the `boolean` contract is preserved *by construction* rather than by me re-implementing it.
* **`status`** → **accessor.** No cast exists to bypass, so an accessor returning the raw value is exactly behaviour-preserving. **Adding a cast here would introduce a cast surface where none existed** — the smaller change is the accessor.

**Rejected, with reasons:** **(5)** intercepts every attribute on Eloquent's hottest path for a two-field question. **(6)** cannot measure what has not yet been migrated to it. **(7)** has already produced three inventories, each correcting the last.

**Uniformity was the tempting choice and it is the wrong one:** one mechanism for both fields means either reproducing a cast by hand, or adding a cast where none belongs.

## What must be true before implementation

* [ ] **The Product Owner confirms the reader definition** and that serialisation hits are classified rather than suppressed.
* [ ] **A behaviour-preservation test lands first**, asserting that for both fields, with observation **on and off**, the value and its **type** are identical across `null` · `true` · `false` · `'1'` · `'planned'` · `'active'`. **The test is the evidence for "behaviour-preserving" — not the docblock claiming it.**
* [ ] The report classifies each hit as `decision` · `serialisation` · `display`, from the call stack.
* [ ] Observation stays default-off (`voting_security.observe_legacy_election_state`).

## Current state of the working tree

**The SQL listener exists, is wired, and is uncommitted** — `LegacyElectionStateObserver` (renamed from `Probe` on the Product Owner's preference), a default-**false** config flag, and one registration line in `AppServiceProvider`. **It is inert until the flag is set**, and it is left in place pending this review's outcome. **Nothing has been committed and no consumer has been migrated.**

---

**Traceability:** `PBDIGIT-58` §`58A` · `PBDIGIT-48` (the inventory that disproved SQL-only sufficiency) · `HasAttributes::transformModelValue():2218-2241` · `Election::getCasts()` · `app/Models/Election.php:334,348,365,382,893,1190` · `app/Http/Controllers/Election/ElectionManagementController.php:805` · `app/Contexts/Membership/Infrastructure/Casts/` · `app/Application/Election/Deprecation/{DeprecationAccessGuard,DeprecationPolicy,ElectionReadModel}.php` · `docs/pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md`
