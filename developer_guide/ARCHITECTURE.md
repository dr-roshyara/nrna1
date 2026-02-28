# Architecture Decisions & Design Document

## Executive Summary

This document records the architectural decisions made for implementing the Voter Registration Flag System and Demo/Real Election System. It explains the rationale behind design choices, tradeoffs, and future considerations.

---

## System Context

### Problem Statement

1. **Voter/Customer Confusion**
   - Non-voters (customers) appeared in voter approval lists
   - System couldn't distinguish intent from role
   - No audit trail for voter registration

2. **No Election Distinction**
   - System couldn't differentiate demo elections from real elections
   - No multi-election support
   - Couldn't track voter status per election

### Solution Goals

1. **Voter Registration Flag System**
   - Separate customers from voters
   - Track voter intent
   - Enable efficient querying

2. **Election System**
   - Support multiple elections
   - Track voter status per election
   - Maintain complete audit trail
   - Enable flexible configurations

---

## Decision 1: Add Voter Intent Flag to Users Table

### Decision

Add `wants_to_vote` boolean column to `users` table (rather than creating separate table).

### Options Considered

#### Option A: Column in Users Table (CHOSEN)
```php
// Minimal schema change
ALTER TABLE users ADD wants_to_vote BOOLEAN DEFAULT false;

// Simple queries
User::where('wants_to_vote', true)->get();

// Efficient indexing
INDEX idx_wants_voter (wants_to_vote, is_voter)
```

**Pros:**
- Simple and direct
- No migration complexity
- Efficient single-table queries
- Low performance overhead
- Easy to understand

**Cons:**
- Adds column to users table (minor)
- Mixes concerns (minimal)

#### Option B: Separate VoterIntent Table
```php
// Complex schema
CREATE TABLE voter_intents (
    id, user_id, wants_to_vote, registered_at
)

// Complex queries
User::with('voterIntent')
    ->where('voterIntent.wants_to_vote', true)
    ->get();
```

**Pros:**
- Perfect normalization
- Completely separate concern

**Cons:**
- Extra join for every query
- Over-engineered for simple flag
- Performance penalty
- Migration complexity

#### Option C: New User Type Enum
```php
// Replace is_voter, can_vote with user_type enum
ENUM('customer', 'pending_voter', 'approved_voter', 'committee', ...)
```

**Pros:**
- Explicit states

**Cons:**
- Loses existing data structure
- Breaking change to schema
- Unclear semantics

### Rationale

**Chosen Option A** because:
- Problem is simple: flag presence
- Solution should be simple
- Existing is_voter/can_vote structure works well
- Adding one column is pragmatic
- Queries remain efficient
- No migration complexity

---

## Decision 2: No Foreign Keys in VoterRegistrations Table

### Decision

VoterRegistrations table has NO foreign key constraints on user_id and election_id.

### Options Considered

#### Option A: No Foreign Keys (CHOSEN)
```sql
CREATE TABLE voter_registrations (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,      -- NO CONSTRAINT
    election_id BIGINT,  -- NO CONSTRAINT
    ...
);
```

**Pros:**
- Multi-database support
- Independent table evolution
- Selective data import/export
- No cascade delete complexity

**Cons:**
- Manual orphan cleanup needed
- No database-level enforcement

#### Option B: Full Foreign Keys
```sql
CREATE TABLE voter_registrations (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    election_id BIGINT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON CASCADE DELETE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON CASCADE DELETE
);
```

**Pros:**
- Database enforces referential integrity
- Automatic cascade delete

**Cons:**
- Couples to users/elections tables
- Problems if moving data between databases
- Migration deadlocks possible
- Can't selectively delete

#### Option C: Partial Foreign Keys
```sql
-- Only election_id has FK
FOREIGN KEY (election_id) REFERENCES elections(id)
-- user_id left unconstrained
```

**Pros:**
- Partial integrity

**Cons:**
- Confusing mixed approach
- Still couples to elections table

### Rationale

**Chosen Option A** because:
- Future multi-database architecture
- Elections could move to landlord DB
- Users stay on tenant DB
- Registrations need independence
- Can validate in application layer
- Manual cleanup acceptable trade-off

---

## Decision 3: Voter States as Scopes, Not Enum

### Decision

Represent voter states as query scopes and methods, not as ENUM column.

### Options Considered

#### Option A: Scopes & Methods (CHOSEN)
```php
// In User model
User::customers()->get()
User::pendingVoters()->get()
User::approvedVoters()->get()

// Methods
$user->isCustomer()
$user->isPendingVoter()
$user->getVoterState()
```

**Pros:**
- Flexible - can change logic without migration
- Type-safe - computed properties
- Single source of truth
- Efficient queries

**Cons:**
- Logic exists in code, not DB
- Less explicit

#### Option B: Voter_Status Enum Column
```sql
ALTER TABLE users ADD voter_status
  ENUM('customer', 'pending_voter', 'approved_voter', 'suspended_voter', 'committee_member')
```

**Pros:**
- Explicit state at DB level
- Database constraints
- Clear enumeration

**Cons:**
- Redundant with existing columns
- Requires update on every state change
- Migration needed if states change
- Duplicate data (wants_to_vote + voter_status)

#### Option C: VoterState Lookup Table
```sql
CREATE TABLE voter_states (
    id, name, label, rules, ...
)
```

**Pros:**
- Configurable states

**Cons:**
- Over-engineered
- Adds complexity
- Slow joins

### Rationale

**Chosen Option A** because:
- States are derived from existing columns
- No redundant data
- Can compute states anywhere
- Flexible for future changes
- Queries are efficient with scopes
- Application-level logic is cleaner

---

## Decision 4: Metadata Field as JSON

### Decision

Store additional registration context in JSON metadata field.

### Options Considered

#### Option A: JSON Metadata Field (CHOSEN)
```sql
ALTER TABLE voter_registrations ADD metadata JSON;

-- Usage:
$registration->metadata = [
    'ip_address' => '192.168.1.1',
    'browser' => 'Chrome',
    'device' => 'desktop'
];
```

**Pros:**
- Flexible schema
- Can add fields without migration
- Searchable with JSON_EXTRACT
- Type-safe in Eloquent with casts

**Cons:**
- Unstructured data
- Requires JSON_EXTRACT for queries

#### Option B: Dedicated Columns
```sql
ALTER TABLE voter_registrations ADD ip_address VARCHAR(15);
ALTER TABLE voter_registrations ADD browser VARCHAR(100);
ALTER TABLE voter_registrations ADD device VARCHAR(20);
```

**Pros:**
- Structured, queryable
- Type safety

**Cons:**
- Migration needed for each field
- Fixed schema
- Table bloat

#### Option C: Separate MetadataTable
```sql
CREATE TABLE registration_metadata (
    id, registration_id, key, value
);
```

**Pros:**
- Fully flexible

**Cons:**
- Extra joins needed
- Over-normalized
- Complex queries

### Rationale

**Chosen Option A** because:
- Registration context varies by deployment
- IP, browser, device may not always needed
- Can query when needed with JSON_EXTRACT
- Zero migration overhead
- Supports future extensibility
- Eloquent handles JSON well

---

## Decision 5: Separate Election Records (Not Election Versions)

### Decision

Store demo and real elections as separate records in elections table, not as versions.

### Options Considered

#### Option A: Separate Election Records (CHOSEN)
```php
// Two distinct elections
Election::where('slug', 'demo-election')->first()
Election::where('slug', 'real-election')->first()

// Can have multiple real elections
Election::where('type', 'real')->get()
```

**Pros:**
- Multiple elections of each type
- Independent configurations
- Clear separation
- Scalable for future elections

**Cons:**
- Requires seeding
- More records

#### Option B: Single Election with Mode Flag
```sql
ALTER TABLE elections ADD mode ENUM('demo', 'real');

-- Single record, switch mode
UPDATE elections SET mode = 'real' WHERE id = 1;
```

**Pros:**
- Single record
- Simple schema

**Cons:**
- Can't have demo + real simultaneously
- Switching breaks users' state
- Not scalable

#### Option C: Election Snapshots/Versions
```sql
CREATE TABLE election_versions (
    id, election_id, version, type, ...
)
```

**Pros:**
- History preserved

**Cons:**
- Complex query logic
- Over-engineered for current needs

### Rationale

**Chosen Option A** because:
- Future-proof: can have multiple elections
- Supports concurrent demo + real testing
- Clear separation of concerns
- Scalable architecture
- Straightforward queries

---

## Decision 6: Indexed Composite Keys

### Decision

Use composite indexes rather than separate single-column indexes for performance.

### Index Strategy

```sql
-- Composite indexes chosen:
INDEX idx_user_type (user_id, election_type)
INDEX idx_election_status (election_id, status)
INDEX idx_type_status (election_type, status)

-- Not separate single-column indexes
-- Index (user_id), Index(election_type) [inefficient]
```

### Rationale

**Chosen Composites** because:
- Query patterns are known and specific
- Composite indexes cover all common queries
- Reduces index overhead
- Fewer indexes to maintain
- Better query plan selection

---

## Decision 7: Query Scopes for Code Readability

### Decision

Implement scopes for common queries rather than writing where clauses inline.

```php
// ✅ CHOSEN
User::pendingVoters()->get()

// ❌ Instead of inline
User::where('wants_to_vote', true)
    ->where('is_voter', 0)
    ->where('can_vote', 0)
    ->get()
```

### Rationale

- Self-documenting code
- Single source of truth
- Reusable across codebase
- Easy to test
- Easier to modify logic

---

## Decision 8: Audit Trail via explicit columns

### Decision

Track approvals with named columns (approved_by, approved_at) rather than audit log table.

### Options Considered

#### Option A: Explicit Columns (CHOSEN)
```sql
ALTER TABLE voter_registrations ADD approved_by VARCHAR(255);
ALTER TABLE voter_registrations ADD approved_at DATETIME;
ALTER TABLE voter_registrations ADD rejected_by VARCHAR(255);
ALTER TABLE voter_registrations ADD rejection_reason TEXT;
```

**Pros:**
- Simple queries
- Direct access
- No joins needed
- Sufficient for use case

**Cons:**
- Not full audit trail (only current state)

#### Option B: Audit Log Table
```sql
CREATE TABLE voter_registration_audits (
    id, registration_id, action, approved_by, changed_at
)
```

**Pros:**
- Complete history
- Can see all state changes

**Cons:**
- Extra table and joins
- Over-engineered for current needs
- More storage

### Rationale

**Chosen Option A** because:
- Current needs only require approval info
- Simple and direct
- No query complexity
- Can add audit table later if needed

---

## Scalability Considerations

### Current Capacity

With current schema:
- 1 million users: ~2 GB
- 10 million registrations: ~30 GB
- All indexes: ~45 GB

Sufficient for mid-sized organisation.

### Future Scaling

If needed to scale further:

1. **Archive old elections**
   ```sql
   -- Move old registrations to archive table
   CREATE TABLE voter_registrations_archive LIKE voter_registrations;
   INSERT INTO voter_registrations_archive
   SELECT * FROM voter_registrations
   WHERE election_id IN (SELECT id FROM elections WHERE ended_at < DATE_SUB(NOW(), INTERVAL 2 YEAR));
   ```

2. **Partition by election**
   ```sql
   -- Partition registrations by election_id
   ALTER TABLE voter_registrations
   PARTITION BY RANGE (election_id) (...)
   ```

3. **Read replicas**
   - Reporting queries hit read replicas
   - Writes still go to primary

4. **Archive table for history**
   - Keep active elections in main table
   - Move completed to archive

---

## Security Considerations

### Data Protection

**Voter Registration Data:**
- Contains personally identifiable information (email, name)
- Should have encrypted backups
- Access limited to committee members
- No public API exposure

**Metadata Field:**
- May contain IP addresses
- Should comply with privacy regulations
- Consider anonymization for storage

**Recommendations:**
- Enable audit logging on database
- Use row-level security if available
- Encrypt sensitive fields
- Implement access control at application level

### Integrity Protection

**Voter Authentication:**
- Prevent unauthorized vote submission
- Validate voter status before vote acceptance
- One vote per voter enforced at code and DB level

**Approval Authority:**
- Only committee members can approve
- Track who approved voter
- Prevent privilege escalation

---

## Testing Strategy

### Unit Tests

```php
// Test voter states
public function test_user_state_transitions()
public function test_voter_scopes()
public function test_election_queries()
```

### Integration Tests

```php
// Test full workflows
public function test_demo_election_workflow()
public function test_voter_registration_and_approval()
public function test_concurrent_elections()
```

### Performance Tests

```php
// Test with realistic data volumes
public function test_query_performance_with_large_dataset()
public function test_index_usage()
```

---

## Future Enhancements

### Phase 3: Advanced Features

1. **Voter Categories**
   - Student voters
   - Alumni voters
   - Faculty voters
   - Different approval workflows

2. **Election Phases**
   - Registration phase
   - Voting phase
   - Results phase
   - State transitions

3. **Bulk Operations**
   - Import voters from CSV
   - Bulk approve/reject
   - Export results

4. **Advanced Analytics**
   - Voter turnout by category
   - Registration trends
   - Vote patterns

### Phase 4: Platform Integration

1. **Multi-tenancy**
   - Each organisation has own elections
   - Voter registrations per tenant
   - Isolated results

2. **API Integration**
   - OAuth voter registration
   - Real-time results feed
   - Webhook notifications

3. **Reporting Dashboard**
   - Live election statistics
   - Voter engagement metrics
   - Trend analysis

---

## Risk Analysis

### Risk 1: Data Inconsistency

**Scenario:** wants_to_vote doesn't match is_voter/can_vote

**Mitigation:**
- Data migration logic in migration file
- Validation methods in User model
- Regular integrity checks
- Clear documentation of valid states

### Risk 2: Orphaned Registrations

**Scenario:** User or election deleted, registrations remain

**Mitigation:**
- No foreign keys by design (allows cleanup)
- Application-level cascade delete
- Audit of orphaned data monthly
- Cleanup script available

### Risk 3: Vote Fraud

**Scenario:** Voter votes multiple times or unauthorized

**Mitigation:**
- One registration per user/election (unique constraint)
- Status validation before vote acceptance
- IP tracking in metadata
- Committee audit trail

### Risk 4: Performance Degradation

**Scenario:** Queries slow with large dataset

**Mitigation:**
- Composite indexes designed for common queries
- Query scopes encourage efficient patterns
- Pagination built-in
- Monitoring recommendations in docs

---

## Documentation Strategy

### Code Documentation

- Inline comments explain complex logic
- Docblocks on all public methods
- README in each component

### User Documentation

- Developer guide (this directory)
- Migration guide for deployment
- Troubleshooting guide for issues
- Query examples for common tasks

### Architecture Documentation

- This document
- Decision rationale
- Future roadmap
- Scaling considerations

---

## Deployment Strategy

### Development

```
1. Local development with migrate:refresh
2. Test all scopes and methods
3. Verify data consistency
```

### Staging

```
1. Backup production
2. Apply migrations
3. Run seeder
4. Smoke test all queries
5. Load test with realistic volume
```

### Production

```
1. Backup database
2. Deploy code
3. Run migrations (with --step for safety)
4. Seed elections
5. Monitor for errors
6. Verify via tinker
7. Send notification to team
```

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-02-03 | Initial architecture: Voter flags + Election system |

---

## References

- Laravel Architecture Best Practices
- Database Design Principles
- Security Considerations
- Performance Optimization
- Migration Guidelines

---

## Glossary

| Term | Definition |
|------|-----------|
| Voter | User intending to participate in elections |
| Customer | User with account but not voting |
| Election | Event where voting occurs (demo or real) |
| Registration | User's entry into specific election |
| Approval | Committee member authorizes voter |
| Metadata | Additional context stored as JSON |
| Scope | Query builder method for common filters |
| Audit Trail | Historical record of state changes |

---

## Maintainer Notes

For future developers working with this system:

1. **Read voter-registration-system.md first** - Understand voter states
2. **Understand schema** - See database-schema.md for complete picture
3. **Query examples** - Look at query-examples.md before writing queries
4. **Test thoroughly** - This system manages voting integrity
5. **Keep documentation updated** - When making changes
6. **Maintain backward compatibility** - If possible
7. **Document decisions** - Update ARCHITECTURE.md when adding features

---

**Document Status:** Complete
**Last Updated:** 2026-02-03
**Approved By:** Development Team
