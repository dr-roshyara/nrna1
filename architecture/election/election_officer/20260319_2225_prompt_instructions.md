# 📋 **CLAUDE CLI PROMPT INSTRUCTIONS: Election Officer Module**

## **Professional Prompt Engineering Guide**

---

# 🎯 **META-PROMPT: Complete Election Officer Module Development**

```
You are a Senior Laravel/Vue.js Architect with 15+ years of experience building election systems. 
Your task is to implement the complete Election Officer module following the architecture defined 
in the knowledge transfer document.

Follow these principles:
1. TEST-DRIVEN DEVELOPMENT: Write tests FIRST (RED), then implementation (GREEN)
2. SECURITY-FIRST: Always consider authorization, validation, and audit trails
3. CLEAN CODE: Follow PSR-12, Vue style guide, and component reusability
4. COMPREHENSIVE DOCUMENTATION: Add PHPDoc, JSDoc, and inline comments
5. BACKWARD COMPATIBILITY: Don't break existing 51 tests

You have access to:
- The existing codebase with 51 passing tests
- Knowledge transfer document with complete architecture
- You can read any file, run commands, and make edits

Always confirm before making destructive changes.
```

---

# 📂 **PHASE 1: DATABASE LAYER (TDD)**

## **Prompt 1.1: Create Migration Tests First**

```
First, create test files to verify the database structure:

1. Create tests/Unit/Models/ElectionOfficerTest.php
2. Create tests/Feature/ElectionOfficerManagementTest.php

The tests should verify:
- Table exists with correct columns
- Foreign key constraints work
- Unique constraints prevent duplicates
- Relationships load correctly
- Scopes filter correctly

Write the tests FIRST (they will fail RED). Then I'll create migrations.
```

## **Prompt 1.2: Create Migrations**

```
Now create three migrations:

1. php artisan make:migration create_election_officers_table
2. php artisan make:migration create_officer_action_logs_table
3. php artisan make:migration create_officer_invitations_table

Use the schema from the knowledge transfer document:
- UUID primary keys
- Proper foreign keys with cascading deletes
- JSON columns for permissions and metadata
- Indexes on frequently queried columns
- SoftDeletes where appropriate

After creating, run the tests to ensure they start failing (RED).
```

---

# 🧩 **PHASE 2: MODEL LAYER (TDD)**

## **Prompt 2.1: Create Models with Tests**

```
Now create the ElectionOfficer model with comprehensive tests:

1. php artisan make:model ElectionOfficer

Requirements:
- Use HasUuids, SoftDeletes traits
- Define all relationships (user, organisation, election, appointer)
- Create scopes: active(), forElection(), chief(), deputy()
- Add methods: isActive(), canManageElection(), accept(), resign()
- Add booted() method for cache invalidation

Write the tests FIRST in the existing test file, then implement.
```

## **Prompt 2.2: Create Supporting Models**

```
Now create the supporting models:

1. php artisan make:model OfficerActionLog
2. php artisan make:model OfficerInvitation

For OfficerActionLog:
- Relationships to officer and polymorphic resource
- Method to log action with before/after state
- Scope for filtering by action type

For OfficerInvitation:
- Generate unique token on creation
- Scope for pending/expired
- Method to send invitation email
- Method to accept and create officer

Write tests for each model before implementation.
```

---

# 🔒 **PHASE 3: AUTHORIZATION LAYER (TDD)**

## **Prompt 3.1: Create Policy Tests**

```
Add policy tests to ElectionOfficerManagementTest.php:

public function test_admin_can_appoint_officer()
public function test_chief_officer_can_appoint_deputy()
public function test_commissioner_cannot_appoint_officers()
public function test_user_can_accept_own_appointment()
public function test_user_cannot_accept_others_appointment()

These should fail RED first.
```

## **Prompt 3.2: Create Policy**

```
Now create the ElectionOfficerPolicy:

php artisan make:policy ElectionOfficerPolicy --model=ElectionOfficer

Methods needed:
- viewAny() - Organisation members can view list
- appoint() - Admin or chief officer can appoint
- manage() - Admin or chief can edit/remove
- accept() - Only the invited user can accept

Register in AuthServiceProvider.php
```

## **Prompt 3.3: Create Middleware**

```
Create EnsureElectionOfficer middleware:

php artisan make:middleware EnsureElectionOfficer

Implementation:
- Check if user is authenticated
- Check if user is officer for the election (if election param exists)
- Handle JSON and web responses differently
- Add logging for security events

Register in Kernel.php as 'ensure.officer'

Add tests for middleware in a new test file.
```

---

# 🚦 **PHASE 4: CONTROLLER LAYER (TDD)**

## **Prompt 4.1: Create Controller Tests**

```
Add controller tests to ElectionOfficerManagementTest.php:

public function test_index_displays_officers_list()
public function test_create_shows_appointment_form()
public function test_store_creates_pending_officer()
public function test_send_invite_creates_invitation()
public function test_accept_invite_creates_active_officer()
public function test_destroy_removes_officer()
public function test_activity_logs_are_accessible()

All tests should fail RED first.
```

## **Prompt 4.2: Create Controller**

```
Now create the ElectionOfficerController:

php artisan make:controller ElectionOfficerController

Methods to implement:
- index() - List officers with stats
- create() - Show appointment form
- store() - Create pending officer or invitation
- showInvite() - Show invitation form
- sendInvite() - Send invitation email
- acceptInvite() - Accept via token
- accept() - Officer accepts appointment
- destroy() - Remove officer
- activity() - View officer action logs

Use Form Requests for validation.
```

---

# 🎨 **PHASE 5: FRONTEND LAYER (TDD for Vue)**

## **Prompt 5.1: Create Vue Component Structure**

```
Create the Vue component structure:

resources/js/Pages/Organisations/ElectionOfficers/
  ├── Index.vue
  ├── Create.vue
  ├── Show.vue
  └── Partials/
      ├── OfficerCard.vue
      ├── AppointmentModal.vue
      ├── InvitationForm.vue
      └── ActivityLog.vue

resources/js/Components/ElectionOfficers/
  ├── RoleBadge.vue
  ├── StatusBadge.vue
  └── PermissionMatrix.vue

Write basic component shells first.
```

## **Prompt 5.2: Implement Index.vue**

```
Implement Index.vue with:

- Stats cards (total, active, chief, pending)
- Officers table with sorting
- Role badges (chief: purple, deputy: blue, commissioner: gray)
- Status badges (active: green, pending: yellow, resigned: red)
- Action buttons based on permissions
- Link to create new officer
- Activity log section

Add loading states and error handling.
```

## **Prompt 5.3: Implement Appointment Modal**

```
Create AppointmentModal.vue with:

- Member selection dropdown (searchable)
- Role selection with radio buttons
- Role descriptions and permission presets
- Election scope selector (all elections or specific)
- Term end date picker
- Custom permission toggles (expandable)
- Form validation with error display
- Loading state during submission

Use the existing Modal component.
```

## **Prompt 5.4: Implement Invitation Flow**

```
Create InvitationForm.vue and related components:

- Email input with validation
- Role selection
- Custom message field
- Preview card showing what user will see
- Success state with copy invitation link button
- Resend invitation option

Add email template for OfficerInvitationMail.
```

---

# 📊 **PHASE 6: BUSINESS RULES (TDD)**

## **Prompt 6.1: Add Conflict Detection Tests**

```
Add business rule tests:

public function test_officer_cannot_be_voter_in_same_election()
public function test_chief_resignation_triggers_succession()
public function test_certification_requires_quorum()
public function test_officer_cannot_manage_own_election_as_voter()

Implement business logic in models and controllers.
```

## **Prompt 6.2: Implement Succession Logic**

```
Add succession handling:

1. Add hierarchy_level and succession_order to ElectionOfficer model
2. Create method findSuccessor() for chief officers
3. Add event/observer for status changes to 'resigned'
4. Implement automatic promotion of deputy with lowest succession_order
5. Log succession events to officer_action_logs

Add comprehensive tests for all scenarios.
```

## **Prompt 6.3: Implement Quorum Checks**

```
Add quorum requirements:

1. Add config/elections.php with quorum settings
2. Create method meetsQuorumForCertification() on Election model
3. Add check before allowing result certification
4. Display quorum status in officer dashboard
5. Log quorum failures to security log

Test with different officer counts.
```

---

# 📝 **PHASE 7: AUDIT & LOGGING (TDD)**

## **Prompt 7.1: Create Audit Trait**

```
Create a reusable trait for officer action logging:

app/Traits/LogsOfficerActions.php

Methods:
- logAction($action, $resource, $before = null, $after = null)
- getActionLogsFor($resource)
- scopeWithRecentActivity()

Use in ElectionOfficer model and controllers.
```

## **Prompt 7.2: Implement Activity Logging**

```
Add logging to all officer actions:

- Create officer → log 'appointed'
- Accept officer → log 'accepted'
- Remove officer → log 'removed'
- Certify results → log 'certified'
- Update permissions → log 'permissions_updated'

Store IP address, user agent, and before/after state.
```

## **Prompt 7.3: Create Activity Log Viewer**

```
Add activity log view to officer dashboard:

- Filter by action type
- Filter by date range
- Filter by resource type
- Expandable details showing before/after
- Export to CSV
- Real-time updates via polling
```

---

# 🧪 **PHASE 8: TESTING & VALIDATION**

## **Prompt 8.1: Run All Tests**

```
Now run the complete test suite:

php artisan test tests/Unit/Models/ElectionOfficerTest.php
php artisan test tests/Feature/ElectionOfficerManagementTest.php
php artisan test tests/Feature/Middleware/EnsureElectionOfficerTest.php

Ensure all tests pass (GREEN). Fix any failures.
```

## **Prompt 8.2: Regression Testing**

```
Verify no regressions in existing system:

php artisan test tests/Feature/ElectionVoterManagementTest.php
php artisan test tests/Feature/Middleware/EnsureElectionVoterTest.php
php artisan test tests/Feature/Integration/VotingMembershipIntegrationTest.php
php artisan test tests/Unit/Models/ElectionMembershipTest.php

All 51 existing tests must still pass.
```

## **Prompt 8.3: Manual Testing Scenarios**

```
Create a manual testing checklist:

1. Appoint chief officer as admin
2. Chief appoints deputy
3. Deputy accepts invitation
4. Deputy attempts to certify results (should fail)
5. Chief certifies results (should succeed with quorum)
6. Chief resigns, verify deputy auto-promoted
7. Check audit logs for all actions
8. Verify officer cannot vote in their election
```

---

# 📚 **PHASE 9: DOCUMENTATION**

## **Prompt 9.1: Update API Documentation**

```
Update API documentation:

- Add OpenAPI/Swagger annotations for new endpoints
- Document request/response formats
- Add examples for all officer operations
- Document error codes and messages
```

## **Prompt 9.2: Create User Guide**

```
Create user guide for election officers:

- How to appoint officers
- Role responsibilities
- Permission matrix
- Certification process
- Audit log interpretation
- Troubleshooting common issues
```

## **Prompt 9.3: Update Developer Guide**

```
Update developer guide with:

- Database schema diagrams
- Class relationships
- Security considerations
- Testing strategy
- Deployment checklist
- Monitoring and alerts
```

---

# 🚀 **PHASE 10: DEPLOYMENT PREPARATION**

## **Prompt 10.1: Create Deployment Checklist**

```
Create deployment checklist:

[ ] Run all tests (should be 60+ tests now)
[ ] Verify migration order (no conflicts)
[ ] Check config files for new settings
[ ] Test on staging environment
[ ] Verify queue worker running for scheduled jobs
[ ] Check log channels configured
[ ] Test email templates
[ ] Verify rate limiting on new endpoints
[ ] Security scan for new routes
[ ] Performance testing with 1000+ officers
```

## **Prompt 10.2: Create Rollback Plan**

```
Document rollback procedure:

1. php artisan migrate:rollback --step=3
2. Remove config changes
3. Clear cache: php artisan cache:clear
4. Restore from backup if needed
5. Notify stakeholders
```

---

# 🏁 **FINAL PROMPT: Complete System Verification**

```
Run final verification:

1. All tests passing (existing 51 + new officer tests)
2. Manual testing checklist complete
3. Documentation updated
4. Deployment checklist verified
5. Rollback plan documented

The Election Officer module is now complete and ready for production deployment!
```

---

# 📊 **PROGRESS TRACKING TEMPLATE**

```
## Current Phase: [Phase Number/Name]
## Tests Written: X/XX
## Tests Passing: X/XX
## Next Task: [Next prompt to run]

Blockers:
- [List any blockers]

Questions:
- [List questions for architect]

Completed Today:
- [List completed items]
```

---

# 🎯 **SUCCESS CRITERIA**

```
✅ All 51 original tests still pass
✅ 20+ new officer tests pass
✅ Officer can be appointed via UI
✅ Invitation emails work
✅ Succession logic works
✅ Quorum requirements enforced
✅ Audit trail complete
✅ Documentation updated
✅ No security regressions
✅ Deployment ready
```

---

**Use these prompts sequentially. Each prompt builds on the previous. Always run tests between phases.** 🚀