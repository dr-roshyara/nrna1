# `BelongsToTenant` family audit — **Decision-B commission, evidence only**

**Commission:** Product Owner / ARB · Session 1 · **audit only — nothing repaired, no repair surface recommended, no implementation authorized by anything in this document**
**Date:** 2026-08-13 · **Authority applied:** **Decision A (CLOSED)** — *voting-time entitlement belongs to the Election context; ambient organisation/tenant context is a forbidden dependency for it* · **Decision B (DECIDED)** — *family-wide audit first, then repair*
**Baseline:** `SD-1` = 1,376 frozen · `L3` = 240 *(unchanged — audit is not classification)*

---

## 1 · Executive summary

> **41 true consumers. ONE demonstrated Decision-A violation (runtime-proven). TWO further tenant-free-key/tenant-dependent-answer cache sites (static). ONE constitutional predicate with a half-scoped hybrid query.**
>
> **The family's dominant pattern is the finding: the trait is UNCONDITIONAL, and correctness on the voting path depends on per-site hand-placed `withoutGlobalScopes()` bypasses — 375 of them repo-wide. The sites that remembered are compliant; the sites that forgot are the defects.** *(Fact, not a repair recommendation.)*

## 2 · Enumeration — and the near-miss that shaped it

**Trait:** `app/Traits/BelongsToTenant.php` — global scope `where organisation_id = TenantContext::get() ?? session('current_organisation_id')` (`:46`), with a platform-org fallback for null/0.

**⛔ Enumeration correction, disclosed:** my first pattern (`use BelongsToTenant`) found **24** consumers — **and failed its own sanity check: `ElectionMembership`, the proven-defective consumer, was absent** (it imports the trait in a multi-trait list). Corrected pattern over all 62 mentioning files: **42 matches, of which 1 is not code** — `app/Http/Middleware/VoterSlugStep.php` is **a Markdown document stored with a `.php` extension** (begins `# Multi-Tenancy Isolation…`). **TRUE CONSUMERS: 41.** *(Thirteenth incident; same family — a pattern that misses list-form imports.)*

**Also found:** `app/Models/Traits/ScopedByOrganisation.php` — a wrapper trait with **ZERO consumers**. Dead code; repository-hygiene note only.

## 3 · Consumer inventory, classified

**Class A · voting-time entitlement (Decision A applies) — 9 models:** `ElectionMembership` · `VoterSlug` · `VoterSlugStep` · `Code` · `BaseVote` (→`Vote`) · `Voter` · `DemoVoterSlug` · `DemoVoterSlugStep` · `DemoCode`

**Class B · admission-time / roster — 5:** `OrganisationUser` · `Member` · `MembershipApplication` · `MemberImportJob` · `VoterRegistration` — *the admission GATE itself is unaffected (`EloquentVoterEligibilityQueryService` uses `DB::table()`, no model, no scope — CF-3); scoped reads of these models elsewhere are class-B concerns, not Decision-A ones*

**Class C · other Election concerns — 8:** `Election` · `Candidacy` · `Post` · `BaseResult` · `DeligateCandidacy` · `DeligatePost` · `DeligateVote` · `DemoCandidacy` · `DemoPost` *(9 listed; Demo pair counted here)*

**Class D · other bounded contexts — 10:** Adjudication ×2 (`AdjudicationProcessModel` · `DeterminationModel`) · Contestation ×1 (`ChallengeModel`) · `ElectionAppliedDeterminationModel` · Membership context ×6 (`CommitteeModel` · `CommitteeAssociationModel` · `CommitteeMembershipApplicationModel` · `ApplicationContextModel` · `FeeContextModel` · `MemberContextModel`) — 🔴 **Decision A is NOT forced onto these.** Their tenancy semantics belong to their own contexts and were not adjudicated here

**Class E · organisation-content infrastructure — 9:** `Calendar` · `Event` · `Image` · `Income` · `Contribution` · `Upload` · `Message` · `PointsLedger` · *(plus `OrganisationUser` dual-listed with B)* — **ambient tenant scoping is plausibly the CORRECT semantics for org-content features; no violation asserted**

**Class F · unknown/anomalous — 2:** the misfiled Markdown "middleware" · the dead `ScopedByOrganisation` trait

## 4 · Decision-A compliance matrix — the voting path, site by site

**The Decision-A test applied per site:** *"does this operation determine whether this person is entitled to vote in THIS election at voting time?"*

| Site | Query | Scope state | Verdict |
|---|---|---|---|
| **`User::isVoterInElection()`** (`User.php:315`) — called by `EnsureElectionVoter` on the live stack | `electionMemberships()` → **scoped** | 🔴 **no bypass** | 🔴 **VIOLATION — runtime-PROVEN (P6 A/B: false in wrong tenant; cache replays it into the correct tenant)** |
| **`ElectionVotingController:37-44`** (`isEligible`/`canVote` projection) | same relation → **scoped** | 🔴 no bypass | 🔴 **VIOLATION — the original `PBDIGIT-65` site, verbatim unchanged** |
| `Election::resolveRouteBinding` (`:70-90`) | `withoutGlobalScopes()` | ✅ bypassed | compliant-by-bypass |
| `VerifyVoterSlug` slug lookup (`:38,47`) | `VoterSlug::withoutGlobalScopes()` · `DemoVoterSlug::withoutGlobalScopes()` | ✅ bypassed | compliant-by-bypass |
| `ElectionMembership::election()` (`:89`) | `->withoutGlobalScopes()` | ✅ bypassed | compliant-by-bypass |
| `EM-VOT-002` predicates (guard `:188` · engine `:193`) | `Election::candidacies()` — **`withoutGlobalScopes()` at the relation** (`:479`) | ✅ bypassed | ✅ **compliant — the adopted invariant's enforcement is NOT tenant-dependent** *(verified statically; the P0 tests confirm behaviour)* |
| `VoteController` (21 bypass calls) · other flow controllers | per-site | ⚠️ **NOT individually audited** | **NOT ESTABLISHED per site** |

> **FACT (not a recommendation): compliance on this path is achieved only where a developer individually remembered `withoutGlobalScopes()`. There are 375 such calls in `app/`. The two violating sites are the ones without it.**

## 5 · Cache/context contamination findings

**Pattern: tenant-DEPENDENT answer cached under a tenant-FREE key.**

| Site | Key | Query | Verdict |
|---|---|---|---|
| `User::isVoterInElection` | `user.{id}.voter.{election_id}` · TTL 300 | scoped | 🔴 **VIOLATION — runtime-proven (P6 step 2, deny-poisoning)**. `booted()` write-hooks (`ElectionMembership:243`) clear on save/delete but **cannot prevent wrong-context READS** |
| `Election::getVoterCountAttribute` (`:336`) | `election.{id}.voter_count` | `membershipVoters()` → `memberships()` → **scoped, no bypass** | ⚠️ **POTENTIAL — same pattern, STATIC evidence only; not runtime-reproduced** |
| `Election::getCandidatesCountAttribute` / `getPendingCandidaciesCountAttribute` | `election.{id}.…` | `Candidacy::withoutGlobalScopes()` | ✅ clean — **adjacent accessors in the same file differ**, which is the inconsistency in miniature |
| `User::getDashboardRoles` (`:1084`) | `user_{id}_dashboard_roles` · TTL 3600 | `DB::table()` — unscoped | ✅ pattern-clean *(answer is tenant-independent)* |

## 6 · A constitutional predicate rides the scope — outside Decision A, recorded for governance

`ConstitutionalTransitionGuard`'s **`has_voters`** precondition is a **hybrid**:

```php
'has_voters' => $election->voters()->withoutGlobalScopes()->exists()        // branch 1: bypassed
             || $election->memberships()->where('role','voter')…->exists(); // branch 2: SCOPED
```

**Consequence (static):** for an election whose voters exist **only** as `ElectionMembership` rows (the current admission path), `has_voters` — and therefore `complete_administration` — **can flip with ambient tenant context.** **This is a lifecycle-transition concern, NOT voting-time entitlement, so Decision A does not govern it. Reported as a separate potential finding; NOT runtime-reproduced.**

## 7 · Failure-mode capability assessment

| Mode | Established? |
|---|---|
| **False denial** (valid voter refused) | 🔴 **PROVEN** — P6 steps 1–2 |
| **Cross-tenant transport of a wrong answer** | 🔴 **PROVEN** — P6 step 2 (cache carries a wrong-context answer into the correct context) |
| **False admission** (ineligible voter admitted) | **NOT ESTABLISHED** — the scope only ever *narrows* result sets, so the plausible direction is denial; the cache could in principle replay a stale TRUE after revocation *(write-hooks mitigate; residual window NOT measured)* |
| **Cross-election contamination** | **NOT OBSERVED** — every audited query also filters `election_id`; the scope adds a wrong org filter, it does not remove the election filter |
| Stale/poisoned cache | 🔴 **PROVEN** (deny direction) · **NOT ESTABLISHED** (admit direction) |

## 8 · Blast radius

| Population | Size |
|---|---:|
| True trait consumers | **41** |
| Decision-A-relevant (class A) models | **9** |
| **Demonstrated Decision-A violations** | **2 sites, 1 mechanism** (`isVoterInElection` · `ElectionVotingController:37-44`) — both feed the SAME live voting stack |
| Potential same-pattern cache sites | **1** (`voter_count`) |
| Potential constitutional-predicate exposure | **1** (`has_voters` branch 2) |
| Sites protected only by hand-placed bypasses | **375 calls repo-wide — per-site correctness NOT individually audited** |
| Non-Election consumers explicitly OUTSIDE Decision A | classes D + E — **19 models** |

## 9 · Unknowns / limitations

Per-site audit of the 375 bypass calls **not performed** (inventory + counts only) · `voter_count` and `has_voters` findings are **static, not runtime-reproduced** · class-D contexts' intended tenancy semantics **not adjudicated** · the demo flow's class-A models not runtime-tested · my earlier probe left orphan rows in `nrna_test` (cleanup query used a wrong column; rows are erased by any future `migrate:fresh`).

## 10 · Recommended next GOVERNANCE questions — not repairs

1. **Is the trait's unconditional application to election-keyed models itself the family defect, or are the two unbypassed sites?** *(This is the ticket's original three-way question, now with family-wide evidence attached — still not engineering's to answer.)*
2. Should the **`has_voters` hybrid** be ruled on together with the voting-time repair, since it shares the mechanism but not the Decision-A boundary?
3. Do the **class-D contexts** adopt tenant scoping deliberately? (Their own governance, not this programme's.)
4. Cache policy: is *"tenant-dependent answer ⇒ tenant-qualified key"* worth adopting as a standing engineering rule? *(Two proven/potential instances now exist — ES-006.1's two-instance threshold is met for a PKS observation, not for promotion.)*

---

**AUDIT COMPLETE · STOPPING**
**The commissioned question answered: TWO sites demonstrably violate Decision A (one mechanism, runtime-proven), ONE cache site and ONE constitutional predicate share the pattern on static evidence, and the family-wide blast radius is 9 Decision-A-relevant models of 41 consumers, with 19 explicitly outside Decision A.**
**How to fix them is NOT answered here. Nothing repaired · no production, test, fixture, configuration, Constitution, schema or migration change · `SD-1` = 1,376 · `L3` = 240 · `EM-OPEN-021` untouched.**

**Traceability:** `BelongsToTenant.php:46` · corrected enumeration (41 of 62 mentioning files; misfiled doc `app/Http/Middleware/VoterSlugStep.php`; dead trait `ScopedByOrganisation`) · `User.php:315-328` · `ElectionVotingController:37-44` · `Election.php:70-90,307-317,336-390,470-480,497-500` · `VerifyVoterSlug:38,47` · `ElectionMembership.php:32,89,243-248` · `ConstitutionalTransitionGuard:178-200` · P6 runtime A/B (`fc86049f`) · Decision A/B record (Session 2) · `PBDIGIT-65`/`69` tickets

---

## 11 · Corrections — appended after Principal Architect review, not silently applied

**Both errors below are mine. The original text above is left standing so the discrepancy remains visible; this section is the corrected record.**

1. **Class-C arithmetic:** the heading says **8**; the list contains **9 names** (`Election` · `Candidacy` · `Post` · `BaseResult` · `DeligateCandidacy` · `DeligatePost` · `DeligateVote` · `DemoCandidacy` · `DemoPost`). **9 is correct.** Additionally, the class totals must **not** be summed to 41, because `OrganisationUser` is deliberately dual-listed (B and E). **The 41 true-consumer total is independently derived from the corrected enumeration and is unaffected by either point.**

2. **One summary sentence overstated the evidence.** §1 says *"the sites that remembered are compliant."* **Too strong.** What §4 actually establishes is: **the individually audited bypass sites are compliant-by-bypass; the other ~370 `withoutGlobalScopes()` call-sites were counted, not audited.** **375 bypass calls ≠ 375 proven-correct sites** — a bypass can itself be wrong (too broad, wrong model, wrong layer). The accurate statement is: *"compliance was demonstrated only at the audited sites; the bypass count measures how widely correctness depends on per-site developer memory, not how often it was achieved."*

**Nothing else in the report changes. The core findings — 41 consumers · 2 proven Decision-A violations sharing 1 mechanism · 1 potential cache site · 1 constitutional-predicate hybrid · 19 models outside Decision A — stand as written.**
