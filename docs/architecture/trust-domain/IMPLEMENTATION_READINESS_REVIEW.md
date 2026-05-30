# Implementation Readiness Review

**Status:** Execution planning (not analysis)  
**Date:** May 30, 2026  
**Scope:** T-001, T-002, T-003 readiness assessment

---

## T-001: Domain Events for Trust Attestation

### 1. Exact Files Affected

**Create:**
- `app/Domain/Trust/Events/IdentityAttestedEvent.php`
- `app/Domain/Trust/Events/VerificationRevokedEvent.php`
- `app/Domain/Trust/Events/VerificationExpiredEvent.php`
- `app/Listeners/IdentityAttestedHandler.php`
- `app/Listeners/VerificationRevokedHandler.php`

**Modify:**
- `app/Http/Controllers/Verification/VoterVerificationController.php` (add event publishing)
- `app/Providers/EventServiceProvider.php` (register listeners)
- `app/Models/VoterVerification.php` (add event methods)

### 2. Existing Classes Involved

**VoterVerificationController:**
- Location: `app/Http/Controllers/Verification/VoterVerificationController.php`
- Current responsibility: Stores/revokes verification
- Change: Add event emission after state changes
- Complexity: Low (2-3 lines per action)

**VoterVerification Model:**
- Location: `app/Models/VoterVerification.php`
- Current fields: verified_by, verified_at, revoked_by, revoked_at, active, notes
- Change: Add methods to emit events
- Complexity: Low (no schema changes)

**EventServiceProvider:**
- Location: `app/Providers/EventServiceProvider.php`
- Current responsibility: Registers listeners
- Change: Add event-listener mappings
- Complexity: Low (config only)

### 3. Database Changes Required

**NONE**

No schema changes. Existing VoterVerification table sufficient.

### 4. API Changes Required

**POST /verification (store):**
- Current response: `{ id, verified_by, verified_at, active }`
- Change: No API change (events internal)
- Impact: None

**POST /verification/{id}/revoke:**
- Current response: `{ revoked_by, revoked_at, active }`
- Change: No API change
- Impact: None

### 5. Frontend Changes Required

**NONE**

Events are internal domain logic. No UI changes.

### 6. Test Impact

**New tests required:**
- Test that IdentityAttestedEvent emitted on verification create
- Test that VerificationRevokedEvent emitted on revocation
- Test event handlers process correctly
- Estimated: 4-6 test cases (2-3h)

**Existing tests:**
- VoterVerificationController tests still pass (just adds event assertions)
- No breaking changes to existing test suite

### 7. Migration Strategy

**No migration needed** (no schema changes)

**Deployment strategy:**
1. Deploy code with event emission
2. Events begin emitting immediately
3. Listeners begin processing immediately
4. Rollback: Remove event emission (revert code)

**Zero-downtime:** Yes. Events are additive.

### 8. Risk Assessment

**Risk Level:** LOW

**Why low:**
- No database changes
- No API changes
- No frontend changes
- Purely additive (existing code path unchanged)
- Easy to test
- Easy to roll back

**What could go wrong:**
- Event listeners throw exceptions → wrap in try/catch, log, continue
- Event bus misconfiguration → discovered in dev/staging
- Listener performance → monitor in staging

**Mitigation:**
- Add error handling to listeners
- Test in staging before production
- Monitor listener execution time

---

## T-002: Explicit Trust Levels

### 1. Exact Files Affected

**Create:**
- `database/migrations/2026_05_XX_add_trust_level_to_voter_verifications.php`

**Modify:**
- `app/Models/VoterVerification.php` (add trust_level field + enum cast)
- `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php` (check trust levels)
- `app/Http/Controllers/Verification/VoterVerificationController.php` (UI form + validation)
- Database schema (add column)

### 2. Existing Classes Involved

**VoterVerification Model:**
- Location: `app/Models/VoterVerification.php`
- Current: `active` boolean
- Change: Add `trust_level` enum (provisionally_trusted, officer_verified, organization_verified, high_assurance, revoked)
- Complexity: Medium (schema + enum + backfill logic)

**VoterVerificationPolicy:**
- Location: `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php`
- Current: Check `active` boolean
- Change: Check `trust_level` meets minimum requirement
- Complexity: Medium (need min_trust_level concept first — requires T-003)

**VoterVerificationController:**
- Location: `app/Http/Controllers/Verification/VoterVerificationController.php`
- Current: Officer clicks "verify"
- Change: Officer selects trust level from dropdown
- Complexity: Medium (form changes, validation)

### 3. Database Changes Required

**HIGH IMPACT**

```sql
ALTER TABLE voter_verifications 
  ADD COLUMN trust_level VARCHAR(50) DEFAULT 'officer_verified' AFTER active;

-- Backfill existing rows
UPDATE voter_verifications 
  SET trust_level = 'officer_verified' 
  WHERE active = true AND trust_level IS NULL;

UPDATE voter_verifications 
  SET trust_level = 'revoked' 
  WHERE revoked_at IS NOT NULL;
```

**Considerations:**
- Table has VoterVerification records (possibly thousands)
- Migration must backfill without locking table too long
- Use batching for large tables
- Test on staging with production-size data

### 4. API Changes Required

**POST /verification (store):**
- Current: `{ verified_by, verified_at }`
- New: `{ trust_level, trust_level_assigned_at }`
- Impact: Minor (additive field)

**GET /verification:**
- New field: `trust_level`
- Impact: Clients may not use it immediately

### 5. Frontend Changes Required

**Verification form:**
- Add dropdown: Trust Level (Provisionally Trusted, Officer Verified, Organization Verified, High Assurance)
- Add help text explaining each level
- Location: `resources/views/verification/` (or Vue component)
- Complexity: Low (simple dropdown)

### 6. Test Impact

**New tests:**
- Test each trust level value is assignable
- Test trust level persists correctly
- Test VoterVerificationPolicy checks trust level
- Test backfill logic (existing active → officer_verified)
- Estimated: 8-10 test cases (3-4h)

**Existing tests:**
- May need updates if tests check `active` field
- Search for "active" in VoterVerification tests and update

### 7. Migration Strategy

**Database migration:**
1. Add column with default value (safe)
2. Backfill existing rows (run in batches to avoid locking)
3. Deploy code that reads trust_level
4. Gradually migrate system to assign explicit levels

**Deployment:**
1. Run migration in staging, verify performance
2. Run migration in production (early morning, low traffic)
3. Monitor query performance (index may be needed)
4. Deploy code changes

**Rollback:**
- Remove trust_level column
- Revert to checking `active` boolean

**Risk:** MEDIUM (schema change on production table)

### 8. Risk Assessment

**Risk Level:** MEDIUM

**Why medium:**
- Production schema change (requires downtime management)
- Backfill logic could fail (needs testing)
- May affect query performance (need index)
- Rollback requires schema reversion

**What could go wrong:**
- Large table migration locks tables → use batching
- Trust level values inconsistent → validate in tests
- Policy changes break existing logic → test thoroughly
- Index missing → queries slow → add index proactively

**Mitigation:**
- Test migration on staging with production-size data
- Use online migration tool if available (pt-online-schema-change)
- Add index on trust_level column
- Comprehensive test coverage
- Staged rollout if possible

**Dependency Risk:** Depends on T-003 for policy enforcement

---

## T-003: Trust Policy Configuration

### 1. Exact Files Affected

**Create:**
- `app/Models/TrustPolicy.php`
- `database/migrations/2026_05_XX_create_trust_policies_table.php`
- `app/Http/Controllers/Admin/TrustPolicyController.php`
- `resources/views/admin/trust-policies/` (create, edit, index)

**Modify:**
- `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php` (check policy)
- Database schema (new table)

### 2. Existing Classes Involved

**VoterVerificationPolicy:**
- Location: `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php`
- Current: Check `active` boolean
- Change: Check trust_level meets TrustPolicy requirement
- Complexity: Medium (add policy lookup)

**Election Model:**
- Location: `app/Models/Election.php`
- Change: Add relationship to TrustPolicy
- Complexity: Low (belongsTo relationship)

**New: TrustPolicy Model:**
- New class
- Fields: organisation_id, election_id, process (voting/candidacy/delegation), min_trust_level, required_evidence_types, verification_expiry_days, re_verification_required
- Complexity: Medium (multi-field, relationships)

### 3. Database Changes Required

**HIGH IMPACT**

```sql
CREATE TABLE trust_policies (
  id BIGINT PRIMARY KEY,
  organisation_id BIGINT NOT NULL,
  election_id BIGINT NULLABLE,
  process VARCHAR(50) NOT NULL, -- voting, candidacy, delegation
  min_trust_level VARCHAR(50) NOT NULL, -- provisionally_trusted, officer_verified, etc.
  required_evidence_types JSON,
  verification_expiry_days INT NULLABLE,
  re_verification_required BOOLEAN DEFAULT FALSE,
  created_at, updated_at
);

ALTER TABLE elections 
  ADD COLUMN trust_policy_id BIGINT NULLABLE;
```

**Considerations:**
- New table (safe, no existing data)
- Foreign key to organisations and elections
- JSON column for flexible evidence types

### 4. API Changes Required

**GET /elections/{id}:**
- New field: `trust_policy` (nested object with requirements)
- Impact: Clients need update to display requirements

**POST /trust-policies:**
- New endpoint (admin only)
- Create/update/delete policies

### 5. Frontend Changes Required

**Admin panel:**
- New section: Trust Policies
- Form to create/edit policy per election
- Fields: min_trust_level (dropdown), evidence_types (checkboxes), expiry_days (number)
- Complexity: Medium (new forms + validation)

**Election page:**
- Display requirement: "This election requires Officer Verified or higher"
- Complexity: Low (display text)

### 6. Test Impact

**New tests:**
- Test TrustPolicy creation
- Test trust level requirement enforced
- Test evidence type requirements
- Test policy applies to correct elections
- Estimated: 10-12 test cases (4-5h)

**Existing tests:**
- VoterVerificationPolicy tests need update to mock TrustPolicy

### 7. Migration Strategy

**Database:**
1. Create trust_policies table (safe)
2. Add trust_policy_id to elections (safe, nullable)
3. Seed default policies (one per organization if needed)

**Code:**
1. Deploy TrustPolicy model and controller
2. Deploy VoterVerificationPolicy changes
3. Admin can configure policies
4. System uses policies immediately

**Rollback:**
- Drop trust_policies table
- Revert VoterVerificationPolicy logic

**Risk:** MEDIUM (requires policy configuration before use)

### 8. Risk Assessment

**Risk Level:** MEDIUM

**Why medium:**
- New table (safe)
- New dependencies (VoterVerificationPolicy depends on TrustPolicy)
- Requires admin setup (if no policy, verification may fail)
- Affects eligibility logic

**What could go wrong:**
- No policies configured → elections may reject all voters
- Policy logic broken → test thoroughly
- Complex queries (need performance test)
- Admin misconfig → validation needed

**Mitigation:**
- Create sensible defaults (all orgs get basic policy)
- Admin form validation
- Performance test policy lookups
- Comprehensive tests
- Documentation for admins

**Dependency:** Requires T-002 (explicit trust levels)

---

## Summary Comparison

| Aspect | T-001 | T-002 | T-003 |
|--------|-------|-------|-------|
| **Risk** | LOW | MEDIUM | MEDIUM |
| **Effort** | 6-8h | 12-16h | 14-18h |
| **DB Changes** | None | HIGH | HIGH |
| **API Changes** | None | Minor | Moderate |
| **Frontend Changes** | None | Low | Medium |
| **Dependencies** | None | None | T-002 |
| **Blockers** | None | None | T-002 (trust levels) |
| **Can Rollback** | Yes (code only) | Yes (schema revert) | Yes (table drop) |

---

## Recommendation

**Start with T-001: Domain Events**

**Rationale:**

1. **Lowest Risk** — Only code changes, no schema, no API, no frontend
2. **Highest Leverage** — Foundation for everything else:
   - T-002 will emit events
   - T-003 can react to events
   - T-005 (expiry) needs events
   - T-006 (governance) needs events
3. **Fastest Win** — 6-8 hours, delivered before T-002/T-003 start
4. **Independent** — Can be done in parallel with other work
5. **Zero Deployment Risk** — Additive, easy to roll back

**After T-001:**
- Proceed to **T-002** (trust levels schema)
- Then proceed to **T-003** (trust policy configuration)

This sequence provides:
- Foundation (T-001) for everything downstream
- Schema upgrade (T-002) that enables multiple downstream features
- Policy framework (T-003) that unifies governance

---

**Next Action:** Implement T-001 Domain Events

**Estimated Timeline:**
- T-001: 1 week (design events, implement, test, deploy)
- T-002: 2 weeks (schema, backfill, test, deploy)
- T-003: 2 weeks (admin UI, policy, test, deploy)

---

**Last Updated:** May 30, 2026  
**Related:** TRUST_DOMAIN_BACKLOG.md
