# Eligibility Failure Investigation — `VoterNotEligibleException` cluster

**Date:** 2026-08-07 · **Status:** INVESTIGATION ONLY — **no code, test or fixture changed**
**Commissioned by:** the Product Owner, as the highest-risk cluster of the Election Failure Estate Assessment (`1267f7dc`)

---

## ⚠️ First correction: it is **8 tests, not 16**

**My assessment overcounted.** The 16 were exception *mentions* — PHPUnit prints each failure twice (the `FAILED` header line and the `➜ throw` source line). The actual figures:

| Measure | Count |
|---|---|
| `FAILED …VoterNotEligibleException` lines | **8** |
| `➜ throw new VoterNotEligibleException` lines | **8** |
| **Distinct failing tests** | **8** |

**One test class, one throw site** — `Tests\Feature\Election\ElectionMembershipPersistenceTest` → `AssignVoterHandler.php:58`. The cluster is far narrower than reported, and correcting it *before* recommending work matters more than the recommendation itself.

## 1. Executive conclusion

**These are fixture failures, not a production eligibility defect. The production rule is correct and is doing exactly what the business asked of it.**

The organisation factory defaults to `uses_full_membership => true`, so `VoterSourceStrategy::fromOrganisation()` selects **full-membership mode**. That mode requires the user to be an **active member with paid or exempt fees**. The fixture creates an `OrganisationUser` and **no `members` row at all**, so both required attributes are `null` and eligibility is correctly refused.

**No evidence of a valid voter being rejected in production was found.** This is the opposite outcome to `PBDIGIT-62`, and it was established by tracing rather than assumed.

## 2. The call path, end to end

```
AssignVoterHandler::handle()                                     :58  throws
  └── VoterEligibilityPolicy::isEligible()                       (interface, Elections Domain)
        └── EloquentVoterEligibilityQueryService                 (bound in AppServiceProvider:141)
              ├── buildFullMembershipContext()   ← organisation_users + members
              └── FullMembershipPolicy::decideForContext()
```

```php
return $context->mode->isMembershipRegistry()
    && $context->isActive
    && ! $context->isDeleted
    && in_array($context->membershipStatus, ['active'], true)   // ← from members row
    && in_array($context->feesStatus, ['paid', 'exempt'], true); // ← from members row
```

## 3. Per-test classification

All 8 share one fixture shape and one failure mechanism, so they classify identically.

| Tests | Fixture validity | Eligibility rule | Authority | Classification | Risk | Recommendation |
|---|---|---|---|---|---|---|
| 8 × `ElectionMembershipPersistenceTest` (voter-assignment paths) | ❌ **incomplete** — creates `OrganisationUser`, never a `members` row | ✅ **valid and correctly applied** | `EloquentVoterEligibilityQueryService` + `FullMembershipPolicy` | **A — invalid/stale fixture** | 🟢 **GREEN** | Add an active member with `fees_status` `paid`/`exempt`, **or** build the organisation with `uses_full_membership => false` if the tests intend election-only mode. **Which of the two is a test-intent question, not a code question** |

**A trap for whoever repairs this:** other fixtures in the repository create members with `fees_status => 'unpaid'` (e.g. the newsletter suites). Under this rule such a member is **ineligible**. A repair that copies an existing member fixture will fail for a second, different reason.

## 4. Authority finding — **partially resolved**

**Within the Elections context, assignment-time eligibility has a single, coherent authority**: the `VoterEligibilityPolicy` interface (Domain), bound to `EloquentVoterEligibilityQueryService` (Infrastructure), which builds an `EligibilityContext` and delegates to one of two domain policies selected by `VoterSourceStrategy`. That is a clean ports-and-adapters arrangement, and **nothing in this cluster contradicts it**.

**What remains ambiguous is unchanged by this investigation:** `PBDIGIT-49`/MB-5 record `VoterEligibilityService` / `EligibilityEvaluator` / `EligibilitySnapshot` as competing homes for eligibility **at voting time**. This cluster only exercises **assignment time**.

> **Recorded, not resolved:** whether assignment-time and voting-time eligibility are one authority or two is a **live architectural question**, and this evidence neither settles nor weakens it. **Engineering must not decide it** (`R-34`).

## 5. Production defects

**None found in this cluster.** The single production observation worth recording is not a defect: `FullMembershipPolicy` couples voting eligibility to **fees status**. That is a real business rule with real consequences — *a member in arrears cannot be assigned as a voter* — and it deserves Product Owner awareness, not repair.

## 6. Test/fixture debt

All 8 tests are repairable by fixture alone, once the intended mode is confirmed. **They protect a valid invariant and must be kept.**

## 7. Tests that must be preserved

**All 8.** They protect *only eligible voters may be assigned to an election* — an invariant that is valid, active, and correctly implemented. **Not one asserts a retired representation.**

## 8. Proposed next work

| Category | Item |
|---|---|
| **Product Owner decision** | **None required to proceed.** One item for awareness: fees status gates voter assignment in full-membership mode |
| **Test-intent clarification** | Do these tests mean to exercise **full-membership** or **election-only** mode? This decides the repair shape |
| **Engineering repair (authorisable now)** | Complete the fixtures — active member, `fees_status` paid/exempt — and re-run. Low risk, no production change |
| **New regression tests** | None. Coverage exists; only its setup is wrong |

## STOP

Investigation complete. **Nothing was modified.** The cluster is smaller than reported (8, not 16), lower-risk than feared (fixture, not defect), and the highest-risk suspect in the estate is now **eliminated on evidence**.

**The 22 `false is true` unknowns are now the highest-risk unexamined cluster.**
