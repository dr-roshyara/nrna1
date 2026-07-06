---
name: Membership Voter Eligibility Fix - TDD Implementation
description: TDD Phase 1 & 2 complete for fixing members invisible in voters dropdown
type: project
originSessionId: be14e6db-faf3-4787-84c8-a39f1f385592
---
## Problem
Members approved via application flow created with `fees_status='unpaid'`. ElectionVoterController query gates on `fees_status IN ('paid','exempt')`, making newly approved members invisible in voters suggestion dropdown. Production issue: "2 members but only 1 in voters dropdown"

## Solution (TDD Complete)

### Phase 1: RED ✅
- Created `MemberControllerMarkPaidTest.php` with 8 tests
- Tests covered: authorization, fee status, fee waiver, org isolation
- All started in RED (failing) state

### Phase 2: GREEN ✅
**Files modified:**
- `app/Http/Controllers/MemberController.php` - Added `$this->authorize()` to markPaid()
- `app/Providers/AuthServiceProvider.php` - Mapped Organisation to MembershipPolicy
- `database/migrations/2026_04_15_140049_backfill_active_members_fees_status_to_exempt.php` - One-time data fix

**Changes:**
1. `markPaid()` now checks authorization via MembershipPolicy::recordFeePayment()
2. Method signature fixed: `(Organisation, Member)` to match route binding order
3. Pending fee rows waived automatically when marking paid
4. AuthServiceProvider maps Organisation → MembershipPolicy for policy resolution

**Result:** All 8 tests passing ✅

### Verified
- VoterDropdownTest (4 tests) still passing - no regression
- Total: 12 tests passing
- Commit: 50ffdaa08 "fix: implement membership fee authorization & voter eligibility fix"

## What's Complete

| Task | Status | File |
|------|--------|------|
| markPaid() authorization | ✅ | MemberController.php |
| Fee waiver logic | ✅ | MemberController.php |
| MemberControllerMarkPaidTest | ✅ | tests/Feature/Membership/ |
| AuthServiceProvider policy mapping | ✅ | app/Providers/ |
| One-time data migration | ✅ | database/migrations/ |
| Regression test verification | ✅ | VoterDropdownTest |

## What's Pending (Phase 3+)

### Phase 3: REFACTOR (optional)
- Extract duplicate authorization checks to helper method
- Consider renaming `markPaid()` to `markExempt()` (documents semantic)

### Phase 4: Additional Test Coverage
- `MemberControllerIndexTest.php` - Test index() authorization
- `OrganisationRoleControllerTest.php` - Full coverage for addMember()
- `ApplicationApprovedToVoterEligibilityTest.php` - End-to-end flow

## Architecture Decision
Used explicit policy-based authorization (`$this->authorize()`) instead of manual checks. This:
- Aligns with existing ElectionPolicy pattern
- Provides consistent permission model
- Makes authorization visible in code
- Enables centralized policy updates

## Key Implementation Details

**MembershipPolicy usage:**
- `recordFeePayment()` requires 'owner' or 'admin' role
- `viewApplications()` allows 'owner', 'admin', 'commission'

**Fee status flow:**
- Application approval: `fees_status='unpaid'` (intentional - requires explicit payment)
- Direct admin add: `fees_status='exempt'` (immediate voter eligibility)
- Mark as paid: waives pending fees + sets exempt (immediate voter eligibility)

**Data migration:**
- Backfills active members with unpaid fees to exempt
- One-directional (no down() needed)
- Fixes production "2 but 1 visible" issue
