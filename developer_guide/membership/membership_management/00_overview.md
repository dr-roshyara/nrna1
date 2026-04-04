# Membership Management System — Developer Guide

## Overview

This guide documents the complete Membership Management System built for the Public Digit multi-tenant election platform. It covers the full implementation from architecture decisions through to production-ready code, following strict TDD (Red → Green → Refactor) across five phases.

---

## What This System Does

The Membership Management System allows organisations (tenants) to:

| Feature | Description |
|---------|-------------|
| **Membership Types** | Define tiers (Annual, Lifetime, Student, etc.) with fees, durations, and approval workflows |
| **Membership Applications** | Accept applications from users, review and approve/reject with audit trail |
| **Membership Fees** | Track fee payments with immutable snapshots, idempotent payment recording |
| **Membership Renewals** | Admin-initiated and self-service renewal within configurable windows |
| **Expiry Processing** | Daily job auto-rejects stale applications, marks overdue fees |
| **Election Eligibility** | Integrates with existing `ElectionMembership` to enforce member-level eligibility |

---

## System Context in the Platform

```
┌─────────────────────────────────────────────────────────────┐
│                     ORGANISATION (Tenant)                    │
│                                                              │
│  ┌──────────────┐    ┌───────────────┐    ┌──────────────┐  │
│  │ Membership   │    │ Elections     │    │ Members      │  │
│  │ Types        │───▶│               │◀───│              │  │
│  └──────────────┘    └───────────────┘    └──────────────┘  │
│         │                    │                   │           │
│         ▼                    ▼                   ▼           │
│  ┌──────────────┐    ┌───────────────┐    ┌──────────────┐  │
│  │ Applications │    │ Election      │    │ Membership   │  │
│  │ (workflow)   │    │ Memberships   │    │ Fees &       │  │
│  └──────────────┘    │ (eligibility) │    │ Renewals     │  │
│                      └───────────────┘    └──────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

The system sits between the identity layer (`user_organisation_roles`, `organisation_users`) and the election layer (`election_memberships`). A user becomes an election-eligible voter only after their membership application is approved and their `Member` record has an active, non-expired status.

---

## Identity Hierarchy

```
User
 └── UserOrganisationRole   (role: owner | admin | commission | voter | member)
      └── OrganisationUser  (status: active | suspended)
           └── Member       (status: active | expired | suspended | ended)
                ├── MembershipFee     (payments & snapshots)
                ├── MembershipRenewal (renewal history)
                └── ElectionMembership (election participation)
```

---

## Directory Structure

```
app/
├── Console/Commands/
│   └── ProcessMembershipExpiryCommand.php
├── Contracts/
│   └── PaymentGateway.php
│   └── Contracts/PaymentIntent.php
│   └── Contracts/PaymentResult.php
│   └── Contracts/RefundResult.php
├── Events/Membership/
│   ├── MembershipApplicationApproved.php
│   ├── MembershipApplicationRejected.php
│   ├── MembershipFeePaid.php
│   └── MembershipRenewed.php
├── Exceptions/
│   └── ApplicationAlreadyProcessedException.php
├── Http/Controllers/Membership/
│   ├── MembershipApplicationController.php
│   ├── MembershipFeeController.php
│   ├── MembershipRenewalController.php
│   └── MembershipTypeController.php
├── Models/
│   ├── Member.php              (extended)
│   ├── MembershipApplication.php
│   ├── MembershipFee.php
│   ├── MembershipRenewal.php
│   └── MembershipType.php
├── Policies/
│   └── MembershipPolicy.php
└── Services/
    └── ManualPaymentGateway.php

config/
└── membership.php

database/migrations/
├── 2026_04_03_155706_create_membership_types_table.php
├── 2026_04_03_155707_create_membership_fees_table.php
├── 2026_04_03_155708_create_membership_renewals_table.php
├── 2026_04_03_155709_add_ended_fields_to_members_table.php
├── 2026_04_03_155710_create_membership_applications_table.php
└── 2026_04_03_155711_add_ended_status_to_members_table.php

routes/
└── organisations.php   (membership routes added)

tests/
├── Feature/Membership/
│   ├── MembershipApplicationTest.php
│   ├── MembershipFeeTest.php
│   ├── MembershipRenewalTest.php
│   └── MembershipTypeTest.php
└── Unit/
    ├── Jobs/
    │   └── ProcessMembershipExpiryJobTest.php
    ├── Models/
    │   ├── ElectionMembershipTest.php  (scopeEligible extended)
    │   ├── MembershipApplicationTest.php
    │   ├── MembershipFeeTest.php
    │   ├── MembershipRenewalTest.php
    │   ├── MembershipTypeTest.php
    │   └── MemberTest.php
    └── Policies/
        └── MembershipPolicyTest.php
```

---

## Phase Summary

| Phase | Focus | Tests | Key Deliverables |
|-------|-------|-------|-----------------|
| [Phase 0](./01_phase0_foundation.md) | Foundation — policy, config, contracts | 25 | `MembershipPolicy`, `config/membership.php`, `PaymentGateway` |
| [Phase 1](./02_phase1_data_layer.md) | Data layer — migrations and models | 28 | 4 migrations, 4 models, `Member` extended |
| [Phase 2](./03_phase2_application_workflow.md) | Application workflow | 14 | Controller, routes, events, Vue pages |
| [Phase 3](./04_phase3_fees_renewals.md) | Fees and renewals | 22 | Fee controller, renewal controller, Vue pages |
| [Phase 4](./05_phase4_types_jobs_eligibility.md) | Types, jobs, eligibility | 46 | Type controller, expiry command, `scopeEligible` fix |

**Total: 135 tests, 254 assertions, 0 failures**

---

## Running the Tests

```bash
# All membership tests
php artisan test tests/Unit/Policies/MembershipPolicyTest.php \
  tests/Unit/Models/MembershipTypeTest.php \
  tests/Unit/Models/MembershipApplicationTest.php \
  tests/Unit/Models/MembershipFeeTest.php \
  tests/Unit/Models/MembershipRenewalTest.php \
  tests/Unit/Models/MemberTest.php \
  tests/Unit/Models/ElectionMembershipTest.php \
  tests/Unit/Jobs/ProcessMembershipExpiryJobTest.php \
  tests/Feature/Membership/ \
  --no-coverage

# Feature tests only
php artisan test tests/Feature/Membership/ --no-coverage

# Unit tests only
php artisan test tests/Unit/Models/Membership* tests/Unit/Models/MemberTest.php --no-coverage
```

---

## Key Architectural Decisions

### 1. Tenant Isolation Pattern
Every model uses the `BelongsToTenant` global scope which appends `WHERE organisation_id = session('current_organisation_id')` to every query. The `ensure.organisation` middleware sets this session key for protected routes.

The public `/apply` route runs **without** `ensure.organisation`. Controllers on that route must bypass global scopes explicitly:

```php
// CORRECT — explicit organisation_id + withoutGlobalScopes()
OrganisationUser::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where('user_id', $user->id)
    ->exists();

// WRONG — session not set on public route, returns empty result
OrganisationUser::where('user_id', $user->id)->exists();
```

### 2. Optimistic Locking
`membership_applications` has a `lock_version int default 0` column. Approval increments it atomically. If two admins click "Approve" simultaneously, the second one gets `ApplicationAlreadyProcessedException` because the `WHERE lock_version = ?` clause matches 0 rows.

### 3. Fee Snapshots
When a fee is created (on approval or renewal), the type's current `fee_amount` and `fee_currency` are copied into `fee_amount_at_time` and `currency_at_time`. The type's fee may change later — the historical record is frozen.

### 4. Self-Renewal Window
Members can self-renew up to 90 days after their membership expires (configurable via `membership.self_renewal_window_days`). Outside this window, only an admin can renew. Lifetime members (`membership_expires_at = null`) cannot be renewed at all.
