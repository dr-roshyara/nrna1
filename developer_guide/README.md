# Developer Guide: Verifiable Anonymity Voting System

Welcome to the Public Digit voting platform developer documentation. This guide provides comprehensive information about the Verifiable Anonymity voting system implementation.

## 📚 Documentation Structure

Navigate through the following sections to understand the system architecture, implementation details, and usage patterns.

### 1. **[Architecture Overview](./01-overview.md)**
   - High-level introduction to the system
   - Key design principles
   - System components and relationships
   - Data flow visualization

   **Start here if:** You're new to the project and want to understand the big picture.

### 2. **[Verifiable Anonymity Concept](./02-verifiable-anonymity.md)**
   - The problem: How do you make voting both anonymous AND verifiable?
   - The solution: SHA256 vote_hash cryptography
   - How verification works without exposing voter identity
   - Security guarantees and attack surface analysis

   **Start here if:** You want to understand the core architectural pattern and cryptographic approach.

### 3. **[Schema Changes Documentation](./03-schema-changes.md)**
   - Database migrations and field changes
   - Evolution from old to new schema
   - Migration rationale and breaking changes
   - Backward compatibility notes

   **Start here if:** You need to understand database schema changes and migration strategy.

### 4. **[Implementation Guide](./04-implementation-guide.md)**
   - Test-Driven Development (TDD) methodology
   - Phase 1: Red (Write Tests)
   - Phase 2: Green (Implement Code)
   - Phase 3: Refactor (Code Quality)
   - Critical bug fixes and commit history

   **Start here if:** You're implementing new features or maintaining existing code.

### 5. **[API Reference](./05-api-reference.md)**
   - API endpoint changes and migrations
   - Request payload changes
   - Response format changes with examples
   - Backward compatibility considerations

   **Start here if:** You're working on frontend integration or API consumption.

### 6. **[Testing Guide](./06-testing-guide.md)**
   - Test suite overview and organization
   - How to run tests (unit, feature, integration)
   - Test database setup and teardown
   - Writing new tests for Verifiable Anonymity
   - Test coverage goals and reporting

   **Start here if:** You need to write or run tests.

### 7. **[Troubleshooting Guide](./07-troubleshooting.md)**
   - Common errors and solutions
   - Test database issues
   - Migration problems
   - Debug strategies and log analysis

   **Start here if:** You encounter errors or unexpected behavior.

### 8. **[Login Flow & Post-Authentication Routing](./08-login-flow.md)**
   - LoginResponse orchestration
   - DashboardResolver decision logic
   - Circular dependency fix (Spatie middleware mismatch)
   - CheckUserRole middleware validation
   - Role detection priority and caching
   - Comprehensive logging and diagnostics

   **Start here if:** You need to understand how users are routed after login or are debugging redirect issues.

---

## 🎯 Quick Start by Role

### For Backend Developers
1. Read [Architecture Overview](./01-overview.md)
2. Read [Verifiable Anonymity Concept](./02-verifiable-anonymity.md)
3. Study [Implementation Guide](./04-implementation-guide.md)
4. Reference [Testing Guide](./06-testing-guide.md)
5. Study [Login Flow & Routing](./08-login-flow.md) (for authentication changes)

### For Frontend Developers
1. Skim [Architecture Overview](./01-overview.md)
2. Read [API Reference](./05-api-reference.md)
3. Understand [Verifiable Anonymity Concept](./02-verifiable-anonymity.md) (for data structures)

### For DevOps/Database Administrators
1. Read [Schema Changes Documentation](./03-schema-changes.md)
2. Review [Testing Guide](./06-testing-guide.md) (database setup)
3. Reference [Troubleshooting Guide](./07-troubleshooting.md)

### For New Team Members
1. Start with [Architecture Overview](./01-overview.md)
2. Read [Verifiable Anonymity Concept](./02-verifiable-anonymity.md)
3. Review [Implementation Guide](./04-implementation-guide.md)
4. Study [Schema Changes Documentation](./03-schema-changes.md)
5. Practice with [Testing Guide](./06-testing-guide.md)

---

## 🔑 Key Concepts at a Glance

### Verifiable Anonymity
A voting system where:
- Voters can verify their vote was recorded correctly
- Results remain completely anonymous
- No voter-vote linkage is possible
- Audit trails are cryptographically secure

### The Core Innovation: vote_hash
```
vote_hash = SHA256(user_id + election_id + code + timestamp)
```

**Key Properties:**
- Uniquely identifies a voter's participation (using user_id)
- Prevents vote tampering (cryptographic proof)
- Never stored or exposed in the votes table
- Allows voter verification WITHOUT exposing vote choices

### Data Isolation
```
Codes Table        → Contains user_id (proves participation)
   ↓
Votes Table        → NO user_id (anonymous voting)
   ↓
Results Table      → Aggregated data (public results)
```

---

## ✅ Verification Checklist for Contributors

Before submitting code, verify:

- [ ] Tests are written FIRST (TDD)
- [ ] All tests pass (`php artisan test`)
- [ ] No user_id in votes table queries
- [ ] vote_hash is generated before vote creation
- [ ] Results use candidate_id (not candidacy_id)
- [ ] Models inherit from BaseVote/BaseResult
- [ ] Organisation_id is scoped correctly
- [ ] Documentation is updated

---

## 🚀 Getting Started Commands

### Run All Tests
```bash
php artisan test --testsuite=Feature
```

### Run Specific Test Class
```bash
php artisan test --filter=VoteStorageTest
```

### Run with Coverage Report
```bash
php artisan test --coverage
```

### Reset Test Database
```bash
php artisan migrate:fresh --env=testing
```

---

## 📖 Additional Resources

### Architecture Documents
- `architecture/election/election_architecture/20260301_1015_no_user_id_in_votes.md` - Verifiable Anonymity deep dive

### Key Models
- `app/Models/BaseVote.php` - Abstract vote model with verification logic
- `app/Models/BaseResult.php` - Abstract result model with aggregation
- `app/Models/Code.php` - Voter code with tracking

### Controllers
- `app/Http/Controllers/VoteController.php` - Real vote handling
- `app/Http/Controllers/DemoVoteController.php` - Demo vote handling

### Tests
- `tests/Feature/VoteStorageTest.php` - Vote schema validation
- `tests/Feature/ResultCalculationTest.php` - Result aggregation

---

## 🤝 Contributing Guidelines

1. **Always write tests first** (TDD methodology)
2. **Verify vote anonymity** - No user_id in votes table
3. **Use vote_hash for verification** - Never use voting_code
4. **Test with multiple organisations** - Ensure tenant isolation
5. **Update documentation** - Keep this guide current

---

## ❓ Questions or Issues?

1. Check [Troubleshooting Guide](./07-troubleshooting.md)
2. Review relevant test files
3. Consult architecture documents
4. Ask the team lead

---

## 📊 Project Status

| Component | Status | Completion |
|-----------|--------|------------|
| Multi-tenancy Foundation | ✅ Complete | 100% |
| Verifiable Anonymity | ✅ Complete | 100% |
| Vote Schema | ✅ Complete | 100% |
| Result Aggregation | ✅ Complete | 100% |
| API Endpoints | ✅ Complete | 100% |
| Test Coverage | ✅ Complete | 94.2% |
| Documentation | ✅ Complete | 100% |

---

## 🔄 Document Navigation

All documents are cross-linked for easy navigation between related topics.

---

**Built with:** Laravel 11, Verifiable Anonymity, and a commitment to democratic integrity.

**Last Updated:** March 1, 2026
**Status:** Active - Production Ready
