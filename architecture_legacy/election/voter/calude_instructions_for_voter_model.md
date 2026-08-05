# 📋 **PROFESSIONAL PROMPT INSTRUCTION FOR CLAUDE CODE CLI**

```markdown
# Task: Multi-Election Voter Management System Implementation

## Phase 1: Architecture Compatibility Audit 🔍

Before implementing any code, perform a thorough audit of the existing system architecture against the approved final architecture document.

### Audit Requirements:

1. **Database Schema Analysis**
   - Examine current `users`, `organisations`, and `elections` tables
   - Check if `elections` table has `(id, organisation_id)` composite unique key
   - Verify existence and structure of `user_organisation_roles` pivot table
   - Compare with the required schema in the final architecture

2. **Security & Middleware Assessment**
   - Review all existing middleware that protects organisation routes
   - Check authentication/authorization patterns (Policies? Gates? Middleware?)
   - Verify how current system validates organisation membership
   - Identify any security gaps that could affect the new election membership system

3. **Codebase Compatibility Scan**
   - Search for existing election-related controllers, models, and services
   - Check for any existing voter management logic
   - Review queue/job configuration for background processing
   - Examine caching implementation (tags, drivers, patterns)

4. **Route & Controller Analysis**
   - Map all existing organisation-scoped routes
   - Check parameter naming conventions (slug vs id)
   - Review API response formats for consistency

### Deliverable:

Create a detailed compatibility report (`COMPATIBILITY_REPORT.md`) that:
- ✅ Lists all compatible elements
- ❌ Identifies mismatches or missing components
- ⚠️ Highlights potential risks or conflicts
- 📝 Provides specific remediation steps for each issue found
- 🔐 Security assessment summary

**If ANY incompatibility is found, STOP and present the report with remediation plan.**

---

## Phase 2: Implementation (Only if Compatible) 🚀

If and only if the audit confirms full compatibility, proceed with implementation following these strict guidelines:

### Implementation Sequence:

1. **Database Migrations** (in order)
   ```bash
   # Create migration to add composite unique key to elections
   php artisan make:migration add_composite_unique_to_elections
   
   # Create migration for election_memberships table
   php artisan make:migration create_election_memberships_table
   ```

2. **Model Creation**
   - Create `ElectionMembership.php` model with all relationships, scopes, and safety methods
   - Update `User.php` with new relationships (electionMemberships, elections, voterElections)
   - Update `Election.php` with new relationships (memberships, voters, eligibleVoters)

3. **Service Layer**
   - Create `ElectionVoterService.php` with bulk operations
   - Implement caching strategy with proper tags
   - Add logging for audit trail

4. **Data Migration Script**
   - Create command to migrate existing `is_voter` data
   - Include transaction protection
   - Add rollback capability

5. **Integrity Monitoring**
   - Create `ValidateElectionMemberships` command
   - Schedule it in kernel (daily recommended)

6. **Testing** (Write tests FIRST, then implementation)
   ```php
   tests/Unit/Models/ElectionMembershipTest.php
   tests/Feature/ElectionVoterManagementTest.php
   tests/Feature/ElectionMembershipIntegrityTest.php
   ```

### Quality Requirements:

- ✅ All database constraints must be at DB level (not just Laravel)
- ✅ Every model relationship must be properly defined with foreign keys
- ✅ All bulk operations must use transactions
- ✅ Cache invalidation must happen on all relevant events
- ✅ No N+1 queries in any list operations
- ✅ All user-facing operations must be authorized (Policies)
- ✅ 100% test coverage for integrity-critical paths

### Verification Steps:

After implementation, run these verification commands and include output:
```bash
# Verify database constraints
php artisan db:show --tables=election_memberships

# Test integrity with invalid data (should fail)
php artisan tinker --execute="ElectionMembership::create(['user_id'=>'invalid', 'election_id'=>'invalid'])"

# Run integrity check
php artisan elections:validate-memberships

# Run test suite
php artisan test tests/Feature/ElectionVoterManagementTest.php
```

### Rollback Plan:

If any step fails, provide clear rollback instructions:
```bash
# Rollback migrations to previous state
php artisan migrate:rollback --step=2

# Clear any cached data
php artisan cache:clear --tags=election-memberships
```

---

## Communication Protocol:

1. **Start each response with status**: `[AUDIT]`, `[COMPATIBLE]`, `[INCOMPATIBLE]`, or `[IMPLEMENTING]`
2. **For audit findings**: Use bullet points with clear ✅/❌ indicators
3. **For blockers**: Bold the blocking issue and explain why it's critical
4. **For implementation**: Show each file being created/modified with diffs
5. **Always ask for confirmation** before destructive operations

---

## Example Response Format:

```
[AUDIT] Starting architecture compatibility audit...

✅ Users table: Compatible (uses UUIDs)
✅ Organisations table: Compatible
✅ user_organisation_roles: Exists with correct structure
❌ Elections table: Missing composite unique key (id, organisation_id)
⚠️ Middleware: Organisation access uses Policies (good), but elections not protected

[INCOMPATIBLE] Blocking issues found:
1. Missing composite key on elections table - required for foreign key constraint
2. No election membership policies exist

Remediation plan:
1. Add migration to create composite unique key on elections
2. Create ElectionPolicy before proceeding

Proceed with remediation? (yes/no)
```

---

## Final Success Criteria:

- [ ] All 15+ tests passing
- [ ] Database constraints prevent invalid data
- [ ] Cached voter counts update on changes
- [ ] Migration from is_voter works without data loss
- [ ] API endpoints return consistent JSON
- [ ] Documentation updated

Begin Phase 1 audit now.
```  