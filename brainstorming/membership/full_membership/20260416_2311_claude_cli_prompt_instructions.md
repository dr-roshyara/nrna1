## Claude Code CLI Prompt Instructions

```
You are a senior full-stack developer conducting a comprehensive audit of the Public Digit platform. We need to analyze the current implementation state before planning additional features.

## Context

We have been developing a multi-tenant Laravel/Vue3/Inertia election platform with dual membership modes:
- **Election-Only Mode**: Direct voter import, no membership validation
- **Full Membership Mode**: Formal members, fee tracking, voting rights

## Your Task: Comprehensive Implementation Audit

Perform a thorough analysis of what has been implemented versus what was planned.

### Phase 1: Code Discovery

Explore the codebase and document the current state:

```bash
# 1. Check existing models related to membership
ls -la app/Models/ | grep -E "Member|Membership|Subscription|Payment|Fee|Election"

# 2. Check existing controllers
ls -la app/Http/Controllers/ | grep -E "Member|Finance|Subscription|Election"

# 3. Check existing services
ls -la app/Services/ | grep -E "Member|Voter|Eligibility|Subscription|Payment"

# 4. Check existing migrations
ls -la database/migrations/ | grep -E "member|subscription|payment|fee|voter"

# 5. Check existing Vue components
find resources/js/Pages -name "*.vue" | grep -E "Member|Finance|Election|Dashboard"
```

### Phase 2: Document Current State

Create a comprehensive inventory:

**1. Database Schema Inventory**
```
Table: members
- What columns exist?
- What indexes?
- What foreign keys?

Table: membership_types
- What columns exist?
- What are the default types?

Table: membership_fees
- Exists? What columns?

Table: membership_payments
- Exists? What columns?

Table: subscriptions
- Exists? What columns?

Table: voter_invitations
- Exists? What columns?

Table: organisations
- uses_full_membership column exists?
- language column exists?
```

**2. Model Inventory**
```
App\Models\Member
- What relationships exist?
- What helper methods exist? (isVoterEligible, etc.)
- What scopes exist?

App\Models\MembershipType
- Exists? Complete?

App\Models\MembershipFee
- Exists? Complete?

App\Models\MembershipPayment
- Exists? Complete?

App\Models\Subscription
- Exists? Complete?

App\Models\VoterInvitation
- Exists? Complete?
```

**3. Service Inventory**
```
App\Services\VoterEligibilityService
- Exists? What methods?
- Handles both modes?

App\Services\VoterImportService
- Exists? What methods?
- Has previewElectionOnly() and importElectionOnly()?

App\Services\SubscriptionService
- Exists? Complete?

App\Services\PaymentService
- Exists? Complete?
```

**4. Controller Inventory**
```
ElectionVoterController
- index(), store(), bulkStore() - using VoterEligibilityService?

VoterImportController
- create(), preview(), import(), template() - mode-aware?

VoterInvitationController
- showSetPassword(), setPassword() - complete?

OrganisationSettingsController
- index(), updateMembershipMode() - complete?

MemberController
- index(), markPaid() - authorization complete?

FinanceController
- dashboard() - exists?
```

**5. Frontend Inventory**
```
Organisations/Settings/Index.vue
- Mode toggle working?
- Member count display?

Elections/Voters/Import.vue
- Mode-aware UI?
- Shows new/existing badges?
- Invitation count displayed?

Election/Show.vue
- Organisation button added?
- IP restriction UI complete?

Auth/SetPassword.vue
- Complete? Working?

Finance/Dashboard.vue
- Exists? What components?

Admin/Dashboard.vue
- Exists? What widgets?
```

**6. Routes Inventory**
```bash
php artisan route:list | grep -E "member|finance|subscription|voter|election"
```

**7. Tests Inventory**
```bash
# Count and categorize existing tests
find tests/Feature -name "*Test.php" | xargs wc -l | tail -1

# Check specific test files
ls -la tests/Feature/Membership/
ls -la tests/Feature/Voter/
ls -la tests/Feature/Election/
```

### Phase 3: Gap Analysis

Compare current state against the planned architecture:

| Feature | Planned | Implemented | Status |
|---------|---------|-------------|--------|
| Dual-mode membership (uses_full_membership) | ✅ | ? | |
| VoterEligibilityService | ✅ | ? | |
| Election-only CSV import with auto-registration | ✅ | ? | |
| VoterInvitation with email | ✅ | ? | |
| Organisation settings UI toggle | ✅ | ? | |
| Member.fees_status management | ✅ | ? | |
| MemberController.markPaid() | ✅ | ? | |
| MembershipType model | ✅ | ? | |
| MembershipFee model | ❌ | ? | |
| MembershipPayment model | ❌ | ? | |
| Subscription model (monthly) | ❌ | ? | |
| Finance dashboard | ❌ | ? | |
| Committee roles (finance_officer, etc.) | ❌ | ? | |
| Audit logging | ❌ | ? | |

### Phase 4: Integration Status

Document how components are wired together:

```
Voter Eligibility Flow:
User → VoterEligibilityService → Member (if full) / OrganisationUser (if election-only) → Boolean

Voter Import Flow (Election-Only):
CSV → VoterImportService::importElectionOnly() → User created → OrganisationUser → ElectionMembership → VoterInvitation → SendVoterInvitation Job → Email

Voter Import Flow (Full Membership):
CSV → VoterImportService::import() → Member validation → ElectionMembership
```

### Phase 5: Known Issues

Document any known issues from logs or previous sessions:

```
1. Foreign key constraint issue on election_memberships (resolved?)
2. Email template component issue (resolved with HTML template?)
3. CSV import tests passing/failing count
4. Any pending migrations
```

## Output Format

Provide a structured report with:

1. **Executive Summary** (1 paragraph)
2. **What Is Complete** (checklist with ✅)
3. **What Is Partially Complete** (checklist with 🟡)
4. **What Is Missing** (checklist with ❌)
5. **Database Schema Status** (table of tables and columns)
6. **API/Route Status** (list of implemented routes)
7. **Frontend Status** (list of implemented pages/components)
8. **Test Coverage Summary** (passing/failing counts)
9. **Critical Issues to Address**
10. **Recommended Next Steps** (prioritized list)

## Context from Previous Sessions

We have implemented:
- Dual-mode membership with `uses_full_membership` flag
- Election-only voter import with auto-registration
- VoterInvitation system with email
- Organisation settings page with mode toggle
- MemberController.markPaid() with authorization
- VoterEligibilityService for central eligibility logic
- Multiple test suites (42+ tests passing)

We have discussed but NOT implemented:
- Monthly subscription system
- Finance dashboard
- Membership committee roles
- MembershipFee and MembershipPayment models
- Full membership fee tracking

## Questions to Answer

1. Is the `uses_full_membership` flag working correctly in ElectionVoterController?
2. Does VoterImportService properly branch between modes?
3. Are voter invitations being sent correctly?
4. What database tables exist for fees/payments?
5. What frontend dashboards actually exist?
6. What routes are registered for finance/committee functions?
7. What is the current test coverage percentage?

**Om Gam Ganapataye Namah** 🪔🐘
```