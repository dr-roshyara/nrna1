# Model Relationships Documentation Index

Complete documentation for Phase A: Core Model Relationships Implementation

**Status:** ✅ COMPLETE (38 tests / 73 assertions)
**Last Updated:** 2026-03-06

---

## Quick Start

### For New Developers
1. Start with [CHEAT_SHEET.md](CHEAT_SHEET.md) - Quick reference
2. Read [README.md](README.md) - Full overview
3. Reference [RELATIONSHIP_PATTERNS.md](RELATIONSHIP_PATTERNS.md) - Pattern examples

### For Test Writers
1. [TESTING_GUIDE.md](TESTING_GUIDE.md) - TDD patterns and assertions
2. [README.md](README.md#Testing-Patterns) - Testing best practices
3. Look at existing tests: `tests/Unit/Models/*.php`

### For Architects
1. [ARCHITECTURE_DECISIONS.md](ARCHITECTURE_DECISIONS.md) - Design rationale
2. [README.md](README.md#Architecture-Overview) - System design
3. [RELATIONSHIP_PATTERNS.md](RELATIONSHIP_PATTERNS.md#Pattern-6-Query-Scopes) - Pattern decisions

---

## Documentation Files

### 📘 [README.md](README.md) - MAIN GUIDE
**Complete reference for Phase A model relationships**

| Section | Purpose |
|---------|---------|
| Architecture Overview | System design and principles |
| Core Patterns | 6 fundamental relationship patterns |
| Model Reference | Detailed documentation for each model |
| Usage Examples | Real-world usage scenarios |
| Testing Patterns | TDD-first approach |
| Common Pitfalls | What NOT to do |
| Extending Models | How to add new models |
| Best Practices | Recommended approaches |

**When to use:** Starting point, comprehensive reference

---

### 🔗 [RELATIONSHIP_PATTERNS.md](RELATIONSHIP_PATTERNS.md) - PATTERNS REFERENCE
**Quick reference for 10 core relationship patterns**

| Pattern | Example |
|---------|---------|
| BelongsTo | Post → Organisation |
| HasMany | Election → Posts |
| BelongsToMany | Organisation ↔ Users |
| HasManyThrough | Election → Candidacies (via Posts) |
| Query Scope | `Post::forOrganisation()` |
| Constrained Relationships | `post->approvedCandidacies` |
| Eager Loading | `with('organisation')` |
| One-Way Relationships | VoterSlug → Vote (not Vote → VoterSlug) |
| Nullable Relationships | Optional `user_id` on Candidacy |
| Decision Tree | How to choose relationship type |

**When to use:** Looking up a specific pattern, copying pattern examples

---

### 🧪 [TESTING_GUIDE.md](TESTING_GUIDE.md) - TESTING PATTERNS
**Comprehensive testing guide for relationships**

| Topic | Coverage |
|-------|----------|
| TDD Workflow | Write test → Implement → Verify → Commit |
| Testing Relationship Types | BelongsTo, HasMany, BelongsToMany, HasManyThrough |
| Global Scopes in Tests | Session context, withoutGlobalScopes() |
| Testing Query Scopes | Filtering verification |
| Testing Constraints | Approved/pending candidacies |
| Testing Uniqueness | Unique constraints |
| Running Tests | Commands and filters |
| Test Data Factories | Creating test data |
| Assertion Reference | Collection, object, relationship assertions |
| Common Patterns | Arrange-Act-Assert, isolation, constraints |
| Debugging Tests | Print SQL, use assertions |

**When to use:** Writing tests, debugging test failures, learning TDD

---

### 🏗️ [ARCHITECTURE_DECISIONS.md](ARCHITECTURE_DECISIONS.md) - ARCHITECTURE & RATIONALE
**Design decisions and their justification**

| Decision | Section |
|----------|---------|
| UUID Primary Keys | Why not auto-increment integers |
| Explicit Pivot Models | Why not implicit Laravel pivots |
| HasManyThrough Pattern | Database normalization rationale |
| Vote Anonymity | Legal and security requirements |
| Session-Based Tenant Context | Tenant isolation approach |
| withoutGlobalScopes() | Explicit scope handling |
| Filtered Relationships | Separate methods vs filters |
| Organisation Types | Platform vs tenant distinction |
| Soft Deletes | Audit trail and recovery |
| Demo Tables | Demo vs real data isolation |
| Nullable Foreign Keys | Optional relationships |
| Named Scopes | Code reusability |
| Future Considerations | Scaling, regulatory, performance |

**When to use:** Understanding WHY design decisions were made, evaluating changes

---

### ⚡ [CHEAT_SHEET.md](CHEAT_SHEET.md) - QUICK REFERENCE
**One-page reference for common tasks**

| Section | Content |
|---------|---------|
| Model Hierarchy | Visual structure |
| The Six Core Models | Table of all models |
| Creating Models | Code examples |
| Accessing Relationships | Common queries |
| Querying with Scopes | Scope examples |
| Filtering | Additional constraints |
| Global Scopes | Handling in tests vs production |
| Common Queries | Real-world examples |
| Type Checking | Status checks |
| Status Transitions | Updating candidacy status |
| Testing Helpers | Creating test data |
| Performance Tips | Eager loading, optimization |
| Common Mistakes | What NOT to do |
| File Locations | Where files are located |
| Running Tests | Test commands |
| Key Rules | Critical guidelines |

**When to use:** Quick lookup, copy-paste examples, during development

---

## The Six Core Models

### 1. Organisation
```php
// Root of tenancy hierarchy
// type: 'platform' or 'tenant'
Organisation::factory()->platform()->create();
Organisation::factory()->tenant()->create();
```
**Relationships:** elections, posts, users, userOrganisationRoles
**Tests:** 8 passing

---

### 2. Election
```php
// Election event in organisation
Election::factory()->forOrganisation($org)->create();
```
**Relationships:** organisation, posts, candidacies (hasManyThrough), voterRegistrations, codes
**Tests:** 8 passing

---

### 3. Post
```php
// Position/seat in an election
Post::create(['organisation_id' => $org->id, 'election_id' => $election->id, ...]);
```
**Relationships:** organisation, election, candidacies, approvedCandidacies
**Scopes:** forOrganisation(), forElection()
**Tests:** 5 passing

---

### 4. Candidacy
```php
// Candidate for a post
Candidacy::create(['organisation_id' => $org->id, 'post_id' => $post->id, 'user_id' => $user->id, ...]);
```
**Relationships:** organisation, post, user
**Access Election via:** $candidacy->post->election (NOT directly)
**Scopes:** forOrganisation(), approved(), pending()
**Status:** approved, pending, rejected, withdrawn
**Tests:** 7 passing

---

### 5. User
```php
// Person in system
User::create(['organisation_id' => $org->id, 'name' => '...', ...]);
```
**Relationships:** organisation, organisations, organisationRoles, candidacies
**NO Relationships:** votes, results (vote anonymity enforced)
**Tests:** 6 passing

---

### 6. UserOrganisationRole
```php
// User's role in organisation (pivot)
UserOrganisationRole::create(['user_id' => $user->id, 'organisation_id' => $org->id, 'role' => 'admin']);
```
**Relationships:** user, organisation
**Unique Constraint:** (user_id, organisation_id)
**Roles:** admin, member, voter, commission
**Tests:** 4 passing

---

## Test Coverage

### All Tests Passing ✅

```
OrganisationTest           8 tests
UserTest                   6 tests
UserOrganisationRoleTest   4 tests
ElectionTest               8 tests
PostTest                   5 tests
CandidacyTest              7 tests
─────────────────────────────────
Total                     38 tests / 73 assertions
```

### Run All Tests
```bash
php artisan test tests/Unit/Models/
```

---

## Critical Rules

### ❌ FORBIDDEN
- Creating `User::votes()` relationship (breaks vote anonymity)
- Creating `Vote::user()` relationship (breaks vote anonymity)
- Storing `user_id` in votes table
- Querying across multiple organisations
- Using global state for tenant context (always explicit)
- Forgetting `withoutGlobalScopes()` in relationships

### ✅ REQUIRED
- Include `organisation_id` in all tenant-scoped models
- Add `withoutGlobalScopes()` to all relationship methods loading tenant-scoped models
- Use query scopes for common filters
- Write tests BEFORE implementation (TDD)
- Test with multiple organisations
- Document vote anonymity enforcement

---

## Common Tasks

### "I need to create a relationship"
1. Read: [RELATIONSHIP_PATTERNS.md](RELATIONSHIP_PATTERNS.md)
2. Choose pattern from decision tree
3. Look at similar model in codebase
4. Write test first (TDD)
5. Implement relationship
6. Verify test passes

### "I'm writing a test"
1. Read: [TESTING_GUIDE.md](TESTING_GUIDE.md)
2. Use Arrange-Act-Assert pattern
3. Handle global scopes with `withoutGlobalScopes()`
4. Include `RefreshDatabase` trait
5. Run: `php artisan test tests/Unit/Models/YourTest.php`

### "I need to query data"
1. Check: [CHEAT_SHEET.md](CHEAT_SHEET.md#Common-Queries)
2. Use scope methods: `Post::forOrganisation($id)`
3. Add constraints: `.where('is_national_wide', true)`
4. Eager load: `.with('organisation')`
5. Execute: `.get()` or `.first()`

### "I need to understand a design decision"
1. Read: [ARCHITECTURE_DECISIONS.md](ARCHITECTURE_DECISIONS.md)
2. Look up decision number
3. Understand: What was chosen, why, trade-offs
4. See: Future considerations if you need to change it

### "I'm getting empty results"
1. **In tests:** Use `withoutGlobalScopes()` or scope methods
2. **In production:** Set session context: `session(['current_organisation_id' => $id])`
3. **In relationships:** Add `->withoutGlobalScopes()` to relationship method
4. **Debug:** Check [CHEAT_SHEET.md#Common-Mistakes](CHEAT_SHEET.md#Common-Mistakes)

---

## Directory Structure

```
developer_guide/model_relationship/
├── INDEX.md                          ← You are here
├── README.md                          Main guide (comprehensive)
├── RELATIONSHIP_PATTERNS.md           Pattern reference
├── TESTING_GUIDE.md                   Testing patterns
├── ARCHITECTURE_DECISIONS.md          Design rationale
└── CHEAT_SHEET.md                     Quick reference

app/Models/
├── Organisation.php
├── User.php
├── UserOrganisationRole.php
├── Election.php
├── Post.php
└── Candidacy.php

tests/Unit/Models/
├── OrganisationTest.php
├── UserTest.php
├── UserOrganisationRoleTest.php
├── ElectionTest.php
├── PostTest.php
└── CandidacyTest.php
```

---

## Phase Overview

### What is Phase A?
Implementation of core 6 model relationships using TDD-first approach with UUID multi-tenancy.

### What's Included?
✅ Organisation model with tenant/platform types
✅ User model with role-based access
✅ UserOrganisationRole explicit pivot model
✅ Election model with scoped queries
✅ Post model with filtering scopes
✅ Candidacy model with status management
✅ 38 comprehensive tests (73 assertions)
✅ Vote anonymity enforcement (no User→Vote relationships)
✅ Correct database normalization (Elections → Posts → Candidacies)
✅ Global scope handling with BelongsToTenant
✅ Complete documentation (5 guides)

### What's NOT Included?
🚫 Phase B (Voting Models): Code, VoterSlug, Vote, Result
🚫 Phase C: Vote tallying and results
🚫 Phase D: Admin dashboards
🚫 Phase E: Reporting and export

### Next Steps
- Phase B: Implement voting models (Code, VoterSlug, Vote, Result)
- Add vote anonymity tests
- Implement voting workflow

---

## Key Metrics

| Metric | Value |
|--------|-------|
| Models Implemented | 6 |
| Relationships | 20+ |
| Tests Written | 38 |
| Assertions | 73 |
| Pass Rate | 100% |
| Documentation Pages | 5 |
| Code Examples | 100+ |
| Pitfalls Covered | 10+ |
| Patterns Documented | 12 |

---

## Contributing

When adding new models or relationships:

1. **Read** [ARCHITECTURE_DECISIONS.md](ARCHITECTURE_DECISIONS.md) to understand principles
2. **Follow** patterns from [RELATIONSHIP_PATTERNS.md](RELATIONSHIP_PATTERNS.md)
3. **Write tests first** using [TESTING_GUIDE.md](TESTING_GUIDE.md)
4. **Include `organisation_id`** in all tenant-scoped models
5. **Add `withoutGlobalScopes()`** to relationship methods
6. **Document** relationships in model file
7. **Update** this index when done

---

## Support

- **Questions?** Check [CHEAT_SHEET.md](CHEAT_SHEET.md) first
- **Pattern help?** See [RELATIONSHIP_PATTERNS.md](RELATIONSHIP_PATTERNS.md)
- **Testing help?** See [TESTING_GUIDE.md](TESTING_GUIDE.md)
- **Design questions?** See [ARCHITECTURE_DECISIONS.md](ARCHITECTURE_DECISIONS.md)
- **Comprehensive reference?** See [README.md](README.md)

---

**Status:** ✅ Phase A Complete
**Next:** Phase B (Voting Models)
**Repository:** Public Digit Multi-Tenant Voting Platform
