# Developer Guide Index

## 📚 Complete Documentation Library

Welcome to the Public Digit Developer Guide. This is a comprehensive reference for the Voter Registration Flag System and Demo/Real Election System.

---

## 🐛 Bug Fix Logs

### **BUG_FIXES_20260314.md** — 2026-03-14
Two production bugs fixed in one session:
1. `SyntaxError: Invalid linked format` — vue-i18n crashes when locale JSON files contain `@` or `\u0040` (email addresses in placeholder strings)
2. `403 Invalid voting link` — `EnsureVoterStepOrder` middleware read the raw route string instead of the resolved `VoterSlug` model from `$request->attributes`

**Quick rule:** Never put `@` in any locale JSON value. In slug-route middleware, always use `$request->attributes->get('voter_slug')` — never `$request->route('vslug')`.

---

## 📖 Documentation Files

### 1. **README.md** - Start Here
**Purpose:** Overview and quick start guide
**Read Time:** 10 minutes
**Contains:**
- System overview
- Quick start instructions
- Core concepts explanation
- Implementation timeline
- Documentation structure

**When to Read:**
- First time learning about the systems
- Need quick understanding
- Want high-level overview

---

### 2. **voter-registration-system.md** - Voter Flags in Detail
**Purpose:** Complete guide to voter registration flag system
**Read Time:** 30 minutes
**Contains:**
- Problem and solution
- Database changes (wants_to_vote, voter_registration_at)
- Five user states (customer, pending, approved, suspended, committee)
- Query scopes and methods
- Data migration logic
- Integration points
- Testing strategies
- Performance considerations

**When to Read:**
- Need to understand voter state management
- Implementing voter approval features
- Writing queries on users table
- Troubleshooting voter state issues

**Key Concepts:**
- `wants_to_vote` boolean flag
- Query scopes: customers(), pendingVoters(), approvedVoters()
- State methods: isCustomer(), isPendingVoter(), isApprovedVoter()

---

### 3. **election-system.md** - Elections & Voter Registration
**Purpose:** Complete guide to demo/real election system
**Read Time:** 40 minutes
**Contains:**
- System architecture and philosophy
- Elections table structure and usage
- VoterRegistrations table design
- Election models and relationships
- Voter registration lifecycle
- Common workflows (demo, real, bulk import)
- Query examples
- Seeding and testing
- Metadata field usage
- Performance optimization

**When to Read:**
- Need to understand election system
- Implementing election features
- Creating or managing elections
- Querying registrations
- Understanding voter lifecycle

**Key Concepts:**
- Elections table (demo/real types)
- VoterRegistrations table (no foreign keys)
- Voter registration states: pending, approved, rejected, voted
- One registration per user per election

---

### 4. **database-schema.md** - Complete Schema Reference
**Purpose:** Detailed database schema documentation
**Read Time:** 25 minutes
**Contains:**
- Users table modifications (wants_to_vote, voter_registration_at)
- Elections table complete schema
- VoterRegistrations table complete schema
- Column descriptions and purposes
- Index strategies and rationale
- Relationships diagram
- Data types guide
- Performance considerations
- Size estimations
- Migration order
- Rollback procedures
- Backup/restore procedures
- Schema validation queries

**When to Read:**
- Need to understand exact schema structure
- Writing SQL queries directly
- Performance tuning
- Database design review
- Backup/restore procedures

**Key References:**
- Table structures with all columns
- Index definitions and purposes
- Unique constraints
- Size estimates

---

### 5. **migration-guide.md** - Migration Execution
**Purpose:** Complete guide to running and troubleshooting migrations
**Read Time:** 20 minutes
**Contains:**
- Phase 1 migration (voter flags)
- Phase 2 migrations (elections + registrations)
- Seeding instructions
- Complete migration sequence
- Verification checklist
- Troubleshooting common issues
- Rollback procedures
- Production deployment steps
- Performance impact
- Post-migration documentation

**When to Read:**
- About to run migrations
- Troubleshooting migration failures
- Deploying to production
- Planning rollback strategy
- Verifying migration success

**Common Issues Covered:**
- "Table already exists"
- "Duplicate column"
- "Cannot add foreign key"
- "Index too long"
- "Migration doesn't run"

---

### 6. **query-examples.md** - Practical Query Patterns
**Purpose:** Comprehensive query examples and patterns
**Read Time:** 35 minutes
**Contains:**
- Voter state queries
- Election queries
- Voter registration queries
- Statistics and reports
- Advanced patterns
- Funnel analysis
- Performance tips
- Common query patterns
- Debugging queries
- Performance optimization

**When to Read:**
- Writing queries for features
- Need example of specific query
- Optimizing slow queries
- Building reports or dashboards
- Learning best practices

**Quick Reference:**
- Get users by state
- Check election status
- Get registrations with details
- Calculate statistics
- Filter by criteria

---

### 7. **troubleshooting.md** - Problem Solutions
**Purpose:** Troubleshooting guide for common issues
**Read Time:** 30 minutes
**Contains:**
- 25+ common issues with solutions
- Symptoms, causes, and fixes
- Debug techniques
- Performance debugging
- Database integrity checks
- Memory/performance issues
- Recovery procedures
- Getting help strategies
- Quick reference table

**When to Read:**
- Error occurs
- Unexpected behavior
- Performance issues
- Queries returning wrong results
- Debugging needed

**Coverage:**
- Column not found
- Model/class not found
- Data inconsistencies
- Query problems
- Migration issues

---

### 8. **ARCHITECTURE.md** - Design Decisions
**Purpose:** Architectural decisions and rationale
**Read Time:** 25 minutes
**Contains:**
- Executive summary
- Problem statements
- 8 major design decisions with options analyzed
- Rationale for each decision
- Scalability considerations
- Security considerations
- Testing strategy
- Future enhancements roadmap
- Risk analysis
- Deployment strategy

**When to Read:**
- Need to understand why decisions were made
- Considering changes to architecture
- Evaluating tradeoffs
- Planning future enhancements
- Code review discussion

**Decisions Documented:**
1. Voter intent flag in users table
2. No foreign keys in voter_registrations
3. Query scopes for state management
4. JSON metadata field
5. Separate election records
6. Composite indexes
7. Query scopes for readability
8. Audit trail via columns

---

## 🗺️ How to Navigate

### By Role

**I am a... Developer**
1. Start: README.md (overview)
2. Read: voter-registration-system.md (understand states)
3. Learn: election-system.md (understand workflow)
4. Reference: database-schema.md (schema details)
5. Practice: query-examples.md (write queries)
6. Bookmark: troubleshooting.md (for issues)

**I am a... DevOps/DBA**
1. Start: migration-guide.md (run migrations)
2. Learn: database-schema.md (schema structure)
3. Reference: ARCHITECTURE.md (design decisions)
4. Monitor: query-examples.md (performance patterns)
5. Maintain: troubleshooting.md (database health)

**I am a... Project Manager**
1. Start: README.md (overview)
2. Review: ARCHITECTURE.md (decisions/rationale)
3. Understand: election-system.md (system capability)
4. Plan: README.md (implementation timeline)

**I am a... Code Reviewer**
1. Review: ARCHITECTURE.md (design decisions)
2. Check: database-schema.md (schema compliance)
3. Verify: query-examples.md (best practices)
4. Approve: troubleshooting.md (edge cases)

### By Task

**Setting Up Development Environment**
1. migration-guide.md - Run migrations
2. migration-guide.md - Run seeders
3. troubleshooting.md - Verify success

**Implementing a Feature**
1. voter-registration-system.md or election-system.md (relevant guide)
2. query-examples.md (see similar patterns)
3. troubleshooting.md (debug if needed)

**Debugging an Issue**
1. troubleshooting.md (find issue)
2. query-examples.md (verify queries)
3. database-schema.md (check schema)
4. ARCHITECTURE.md (understand design)

**Writing a Query**
1. query-examples.md (find similar example)
2. election-system.md or voter-registration-system.md (understand data model)
3. voter-registration-system.md or election-system.md (see usage)

**Performance Tuning**
1. query-examples.md (performance tips)
2. database-schema.md (indexes and size)
3. troubleshooting.md (debug performance)

**Deploying to Production**
1. migration-guide.md (deployment steps)
2. database-schema.md (backup procedures)
3. ARCHITECTURE.md (risks and considerations)
4. troubleshooting.md (recovery procedures)

---

## 🔍 Quick Lookup

### Voter Registration System Questions

| Question | File | Section |
|----------|------|---------|
| What are the voter states? | voter-registration-system.md | User States |
| How do I query customers? | voter-registration-system.md | Query Scopes |
| What's the data migration logic? | voter-registration-system.md | Migration Data Logic |
| How do I check user state? | voter-registration-system.md | State Methods |
| What columns were added? | database-schema.md | Users Table Modifications |

### Election System Questions

| Question | File | Section |
|----------|------|---------|
| How do I create an election? | election-system.md | Elections Table |
| What's voter registration lifecycle? | election-system.md | Voter Registration Lifecycle |
| How do I approve a voter? | election-system.md | Workflows |
| What's in VoterRegistration model? | election-system.md | Models |
| How do I get election statistics? | query-examples.md | Statistics & Reports |

### Database Questions

| Question | File | Section |
|----------|------|---------|
| What tables were created? | database-schema.md | Overview |
| What are the indexes? | database-schema.md | Indexes |
| What's the relationship diagram? | database-schema.md | Relationships Diagram |
| How big will the database be? | database-schema.md | Size Estimation |
| How do I backup/restore? | database-schema.md or migration-guide.md | Backup & Restore |

### Query Questions

| Question | File | Section |
|----------|------|---------|
| Get all pending voters | query-examples.md | Voter State Queries |
| Get election statistics | query-examples.md | Statistics & Reports |
| Filter by region/country | query-examples.md | Voter State Queries |
| Optimize slow query | query-examples.md | Performance Tips |
| Find duplicate registrations | query-examples.md | Advanced Patterns |

### Problem Questions

| Question | File | Section |
|----------|------|---------|
| Migration failing | troubleshooting.md | Migration Troubleshooting |
| Column not found | troubleshooting.md | Column issues |
| Slow queries | troubleshooting.md | Performance Debugging |
| Wrong query results | troubleshooting.md | Query problems |
| Data inconsistency | troubleshooting.md | Data integrity |

---

## 📊 System Overview Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    PUBLIC DIGIT                          │
└─────────────────────────────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
        ▼                  ▼                  ▼
   ┌─────────┐      ┌──────────────┐   ┌──────────┐
   │  Users  │      │  Elections   │   │  Voter   │
   │  Table  │      │  Table       │   │  Regist  │
   │         │      │              │   │  rations │
   │ NEW:    │      │ Types:       │   │  Table   │
   │ wants_  │      │ - demo       │   │          │
   │ to_vote │      │ - real       │   │ States:  │
   │         │      │              │   │ - pending│
   └─────────┘      └──────────────┘   │ - approv │
        │                  │              │ - voted │
        └──────────────────┼──────────────┘ - reject│
                           │              │          │
                    ┌──────┴──────┐      └──────────┘
                    │  Voters     │
                    │  Can...     │
                    │- Register   │
                    │- Get Approved
                    │- Vote       │
                    └─────────────┘
```

---

## 🚀 Getting Started (30 Minutes)

**If you have 30 minutes, do this:**

1. Read: README.md (5 min)
2. Skim: voter-registration-system.md (5 min) - focus on "User States"
3. Skim: election-system.md (5 min) - focus on "Voter Registration Lifecycle"
4. Run: migration-guide.md verification section (10 min)
5. Practice: query-examples.md - run one query (5 min)

**After 30 minutes, you'll understand:**
- What problems the systems solve
- How voters are categorized
- How elections work
- How to verify installation
- How to write basic queries

---

## 📚 Complete Learning Path (2 Hours)

For comprehensive understanding:

1. **Start** (5 min): README.md
2. **Learn Voter System** (25 min): voter-registration-system.md
3. **Learn Election System** (30 min): election-system.md
4. **Understand Schema** (20 min): database-schema.md (schema sections)
5. **Practice Queries** (20 min): query-examples.md (try 5 examples)
6. **Review Architecture** (10 min): ARCHITECTURE.md (decisions section)
7. **Know Troubleshooting** (10 min): troubleshooting.md (skim issues)

---

## 🎯 One-Pagers

### Voter Registration Flag System (1 page)

```
WHAT: Boolean flag indicates voter intent
WHERE: users.wants_to_vote column
WHY: Separate customers from voters
STATES:
  - Customer (wants_to_vote=false)
  - Pending Voter (wants_to_vote=true, is_voter=0)
  - Approved Voter (wants_to_vote=true, is_voter=1, can_vote=1)
SCOPES:
  User::customers()
  User::pendingVoters()
  User::approvedVoters()
```

### Election System (1 page)

```
WHAT: Track voter registration per election
WHERE: elections + voter_registrations tables
WHY: Support multiple elections (demo + real)
TABLES:
  elections: configuration for elections
  voter_registrations: voter status per election
STATES: pending → approved → voted (or rejected)
KEY: One registration per user per election
```

---

## 📞 Need Help?

### Before Asking Questions

1. Check troubleshooting.md for your error
2. Search query-examples.md for similar query
3. Read relevant system documentation
4. Check ARCHITECTURE.md for design rationale

### Asking Questions Effectively

**What file/code are you looking at?**
- Share file path: `app/Models/User.php`
- Share code section or line number

**What's the error/issue?**
- Exact error message
- When does it occur?
- Expected vs actual behavior

**What have you tried?**
- Show solutions attempted
- Reference troubleshooting.md items checked

---

## 📝 Contributing to Documentation

If you find:
- ❌ Errors or inaccuracies
- ❓ Unclear explanations
- ⚠️ Missing information
- 💡 Better examples

**Please update** the relevant file and document your change!

---

## 📋 Documentation Version

| Version | Date | Status |
|---------|------|--------|
| 1.0 | 2026-02-03 | ✅ Complete & Production Ready |

---

## 🔗 Related Resources

- **Laravel Documentation:** https://laravel.com/docs
- **Eloquent ORM:** https://laravel.com/docs/eloquent
- **Database Design:** MySQL Best Practices
- **Performance Tuning:** Query Optimization Guide

---

**Last Updated:** 2026-02-03
**Maintained By:** Development Team
**Status:** Complete & Ready for Production ✅
