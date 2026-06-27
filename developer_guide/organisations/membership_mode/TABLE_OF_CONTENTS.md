# Membership Mode System — Complete Developer Guide

## Quick Navigation

### For Quick Understanding
1. **[README.md](./README.md)** — Start here! Overview of the system, architecture, and core concepts
2. **[MEMBERSHIP_MODES.md](./MEMBERSHIP_MODES.md)** — Compare Full Membership vs Election-Only modes

### For Implementation
1. **[MEMBERSHIP_IMPORT.md](./MEMBERSHIP_IMPORT.md)** — How to import members/voters from CSV/Excel
2. **[API_INTEGRATION.md](./API_INTEGRATION.md)** — Programmatic integration examples

### For Operations
1. **[OPERATIONS_GUIDE.md](./OPERATIONS_GUIDE.md)** — Day-to-day tasks, troubleshooting, maintenance

---

## Document Overview

### README.md
**Purpose:** Foundational architecture and design overview

**Covers:**
- System architecture with single boolean flag design
- Database schema overview
- Core components (VoterEligibilityService, VoterImportService)
- API endpoints reference
- Frontend implementation details
- Common development tasks with code examples
- Testing guide
- Troubleshooting common issues

**Best for:** Understanding the overall system design and structure

---

### MEMBERSHIP_MODES.md
**Purpose:** Detailed comparison of the two membership modes

**Covers:**
- Full Membership Mode (formal member tracking with fees)
- Election-Only Mode (any org user can vote)
- Side-by-side comparison tables
- Decision matrix (when to use each mode)
- Migration strategies between modes
- Use case examples for each mode
- Database structure for each mode
- Query examples for each mode

**Best for:** Deciding which mode to use, understanding the differences, planning migration

---

### MEMBERSHIP_IMPORT.md
**Purpose:** Complete guide to importing members and voters

**Covers:**
- Import architecture and workflow
- CSV format requirements (different for each mode)
- Two-phase approach (preview → confirm)
- Mode-specific validation rules
- Error handling and error types
- Common import tasks with examples
- API endpoints for import
- Best practices and troubleshooting
- File format examples

**Best for:** Setting up and executing member/voter imports, handling import errors

---

### OPERATIONS_GUIDE.md
**Purpose:** Day-to-day operational tasks and maintenance

**Covers:**
- Operational tasks (add voters, remove voters, view voters)
- Full Membership Mode specific tasks (update fees, extend membership, set type)
- Election setup for both modes
- Monitoring metrics and health checks
- Regular maintenance tasks
- Troubleshooting common issues
- Performance optimization tips
- Indexing and caching strategies

**Best for:** Managing elections, handling member maintenance, troubleshooting problems

---

### API_INTEGRATION.md
**Purpose:** Programmatic integration with the membership system

**Covers:**
- Authentication and authorization
- Check voter eligibility (single and detailed)
- Get unassigned eligible voters
- Assign single or multiple voters
- Import voters (preview and confirm)
- Manage members (create, update, get)
- Query examples for common scenarios
- Error handling patterns
- Complete integration examples
- Rate limiting information

**Best for:** Building integrations, automating processes, external system connections

---

## Common Workflows

### Setup New Organisation

**Scenario:** Create a new organisation and configure for elections

1. Read **README.md** — Understand the system
2. Read **MEMBERSHIP_MODES.md** — Decide which mode (Full vs Election-Only)
3. Read **OPERATIONS_GUIDE.md** — Setting up elections section
4. Follow the setup steps in **API_INTEGRATION.md** → Programmatic Election Setup example

### Import Members/Voters

**Scenario:** Bulk import a list of members or voters from CSV

1. Read **MEMBERSHIP_IMPORT.md** → CSV Format section (mode-specific)
2. Prepare your CSV file according to the format
3. Follow steps in **MEMBERSHIP_IMPORT.md** → Common Tasks section
4. Use API from **API_INTEGRATION.md** → Import Voters section if programmatic

### Switch from Full Membership to Election-Only

**Scenario:** Existing Full Membership organisation wants to open elections to all users

1. Read **MEMBERSHIP_MODES.md** → Migration Between Modes section
2. Follow the steps in **OPERATIONS_GUIDE.md** → Mode Change section
3. Test with a non-critical election first

### Debug User Not Eligible

**Scenario:** A user should be eligible but isn't

1. Read **OPERATIONS_GUIDE.md** → Troubleshooting section
2. Use the diagnosis code to identify the issue
3. Apply the fix listed in the troubleshooting table
4. Re-check eligibility using **API_INTEGRATION.md** → Check Single User Eligibility

### Integrate External Member System

**Scenario:** You have an external database of members to sync

1. Read **API_INTEGRATION.md** → Complete Examples → Import from External System
2. Adapt the example to your data source
3. Test with a small batch first
4. Follow **MEMBERSHIP_IMPORT.md** → Best Practices

---

## Architecture at a Glance

```
organisations
├── uses_full_membership: boolean
│   ├── true (default) → Full Membership Mode
│   │   ├── Requires: members + org_users
│   │   ├── Checks: fees_status, expiration
│   │   └── Validates: membership_type rights
│   │
│   └── false → Election-Only Mode
│       ├── Requires: org_users only
│       ├── Checks: org membership status only
│       └── Simpler validation
│
├── VoterEligibilityService
│   ├── isEligibleVoter(org, user) → bool
│   └── unassignedEligibleQuery(org) → QueryBuilder
│
└── VoterImportService
    ├── preview(file, election) → array with validation
    └── import(file, election, user) → array with results
```

---

## Key Concepts

### Single Boolean Flag Design
Rather than complex type hierarchies, a single `uses_full_membership` boolean controls which eligibility logic applies:
- `true` → Check members table + fees status
- `false` → Check organisation_users only

**Benefit:** Simple, maintainable, easy to migrate between modes

### Mode-Aware Services
`VoterEligibilityService` and `VoterImportService` handle BOTH modes internally:

```php
// Same call, different behavior based on org.uses_full_membership
$eligible = $service->isEligibleVoter($org, $user);
```

### Two-Phase Import
Imports use preview → confirm pattern:
1. User uploads file
2. System previews and validates (no data saved)
3. User reviews errors
4. User confirms
5. System imports (creates records)

---

## Technology Stack

- **Database:** PostgreSQL/MySQL with composite indexes
- **ORM:** Laravel Eloquent
- **Service Layer:** VoterEligibilityService, VoterImportService
- **Frontend:** Vue 3 + Inertia.js
- **File Parsing:** CSV/Excel support
- **Caching:** Redis for voter count caches

---

## Common Tables

### Full Membership Mode

```sql
members
├── id UUID (PK)
├── user_id UUID (FK)
├── organisation_id UUID (FK)
├── status enum (active, inactive, removed)
├── fees_status enum (paid, exempt, pending)
├── membership_type_id UUID (FK)
├── membership_expires_at datetime
└── ...

membership_types
├── id UUID (PK)
├── organisation_id UUID (FK)
├── name string (Standard, Premium, etc.)
├── grants_voting_rights boolean
└── ...
```

### Election-Only Mode

```sql
organisation_users
├── id UUID (PK)
├── organisation_id UUID (FK)
├── user_id UUID (FK)
├── status enum (active, inactive, removed)
└── ...
```

---

## Query Patterns

### Full Membership: Check Eligibility
```php
$eligible = Member::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->exists();
```

### Election-Only: Check Eligibility
```php
$eligible = OrganisationUser::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->where('status', 'active')
    ->exists();
```

---

## Decision Table: Full Membership vs Election-Only

| Need | Full Membership | Election-Only |
|------|-----------------|---------------|
| Track membership fees | ✅ Yes | ❌ No |
| Formal member registry | ✅ Yes | ❌ No |
| Membership tiers/types | ✅ Yes | ❌ No |
| Fee expiration dates | ✅ Yes | ❌ No |
| Quick setup | ❌ Moderate | ✅ Simple |
| Broad participation | ❌ Limited | ✅ All org users |
| Audit membership history | ✅ Detailed | ❌ Basic |

---

## Troubleshooting Quick Links

| Problem | Solution |
|---------|----------|
| User shows ineligible but should be eligible | [OPERATIONS_GUIDE.md](./OPERATIONS_GUIDE.md#issue-user-shows-as-ineligible-but-should-be-eligible) |
| Import file rejected with errors | [OPERATIONS_GUIDE.md](./OPERATIONS_GUIDE.md#issue-import-file-rejected-with-validation-errors) |
| Voters suddenly became ineligible | [OPERATIONS_GUIDE.md](./OPERATIONS_GUIDE.md#issue-sudden-ineligibility-of-voters) |
| Mode change broke elections | [OPERATIONS_GUIDE.md](./OPERATIONS_GUIDE.md#issue-mode-change-breaks-existing-elections) |
| CSV validation errors | [MEMBERSHIP_IMPORT.md](./MEMBERSHIP_IMPORT.md#error-handling) |
| Query performance slow | [OPERATIONS_GUIDE.md](./OPERATIONS_GUIDE.md#performance-optimization) |

---

## Code Examples by Topic

### Eligibility Checking
- [Single user](./API_INTEGRATION.md#check-single-user-eligibility)
- [Detailed reasons](./API_INTEGRATION.md#check-why-user-is-ineligible)
- [Unassigned eligible voters](./API_INTEGRATION.md#get-unassigned-eligible-voters)

### Voter Assignment
- [Single voter](./API_INTEGRATION.md#assign-single-voter)
- [Multiple voters](./API_INTEGRATION.md#assign-multiple-voters)
- [From external system](./API_INTEGRATION.md#example-1-import-members-from-external-system)

### Import Operations
- [Preview import](./API_INTEGRATION.md#preview-import)
- [Confirm and import](./API_INTEGRATION.md#confirm-and-import)
- [Full Membership import](./MEMBERSHIP_IMPORT.md#task-1-import-members-for-full-membership-elections)
- [Election-Only import](./MEMBERSHIP_IMPORT.md#task-2-import-voters-for-election-only-elections)

### Member Management
- [Create member](./API_INTEGRATION.md#create-member)
- [Update member](./API_INTEGRATION.md#update-member)
- [Update fees status](./OPERATIONS_GUIDE.md#task-update-member-fees-status)
- [Extend membership](./OPERATIONS_GUIDE.md#task-extend-membership-expiration)

### Queries
- [All eligible voters](./API_INTEGRATION.md#get-all-eligible-voters-in-organisation)
- [By membership type](./API_INTEGRATION.md#get-voters-by-membership-type)
- [Expired memberships](./API_INTEGRATION.md#get-voters-with-expired-memberships)
- [Participation rate](./API_INTEGRATION.md#get-election-participation-rate)

---

## Performance Considerations

### Indexing
- [Full Membership indexes](./OPERATIONS_GUIDE.md#indexing-strategy)
- [Election-Only indexes](./OPERATIONS_GUIDE.md#indexing-strategy)

### Query Optimization
- [Eager loading examples](./OPERATIONS_GUIDE.md#query-optimization)
- [N+1 problem solutions](./OPERATIONS_GUIDE.md#query-optimization)

### Caching
- [Cache strategies](./OPERATIONS_GUIDE.md#caching-strategy)
- [Cache invalidation](./OPERATIONS_GUIDE.md#caching-strategy)

---

## Testing

### Unit Tests
- Eligibility service tests
- Import service tests
- Member model validations

### Integration Tests
- End-to-end import workflow
- Election setup with voters
- Permission authorization

### Performance Tests
- Query performance with large datasets
- Import performance with 10k+ records

---

## API Reference Quick Links

### Authentication
- [Session auth](./API_INTEGRATION.md#session-authentication-web)
- [Token auth](./API_INTEGRATION.md#token-authentication-api)
- [Authorization checks](./API_INTEGRATION.md#authorization)

### Eligibility Endpoints
- `GET /api/organisations/{id}/voters/eligible` — Check eligibility
- `GET /api/organisations/{id}/elections/{id}/voters/unassigned` — Get unassigned eligible

### Voter Management Endpoints
- `POST /organisations/{slug}/elections/{id}/voters` — Assign single voter
- `POST /organisations/{slug}/elections/{id}/voters/bulk` — Assign multiple voters
- `DELETE /organisations/{slug}/elections/{id}/voters/{id}` — Remove voter

### Import Endpoints
- `POST /organisations/{slug}/elections/{id}/voters/import?preview=true` — Preview import
- `POST /organisations/{slug}/elections/{id}/voters/import` — Confirm and import

### Member Management (Full Membership Mode)
- `POST /organisations/{slug}/members` — Create member
- `PATCH /organisations/{slug}/members/{id}` — Update member
- `GET /organisations/{slug}/members/{id}` — Get member

---

## Related Systems

### Authentication & Authorization
- User registration and login (see user guide)
- Role-based access control (UserOrganisationRole)
- Policy authorization (MembershipPolicy)

### Elections
- Election configuration and management
- Voting workflow (5-step process)
- Vote anonymity and verification

### Audit & Logging
- Audit trail of membership changes
- Import activity logging
- User action tracking

---

## Version History

**v1.0** (Current)
- Full Membership Mode fully implemented
- Election-Only Mode fully implemented
- CSV/Excel import support
- API integration examples
- Complete documentation

---

## Support & Feedback

For questions or feedback about this guide:
1. Check the relevant document above
2. See troubleshooting section in that document
3. Check code examples in API_INTEGRATION.md
4. Review OPERATIONS_GUIDE.md for operational issues

---

**Happy coding! 🚀**

