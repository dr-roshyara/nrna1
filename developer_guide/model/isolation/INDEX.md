# 📚 Organisation Isolation Developer Guide - Index

Welcome to the complete developer guide for organisation isolation in Public Digit!

---

## 🚀 Quick Navigation

### For Different Roles

**👤 Frontend Developer**
- Start: [Quick Reference](QUICK_REFERENCE.md) - Basic rules (2 min)
- Then: [Testing Patterns](TESTING_PATTERNS.md) - How to test (10 min)

**⚙️ Backend Developer**
- Start: [README](README.md) - Complete overview (15 min)
- Then: [Migration Guide](MIGRATION_GUIDE.md) - Adding isolation to new models (20 min)
- Reference: [Quick Reference](QUICK_REFERENCE.md) - Commands and patterns

**🧪 QA/Test Engineer**
- Start: [Testing Patterns](TESTING_PATTERNS.md) - Test setup and patterns (20 min)
- Reference: [Troubleshooting](TROUBLESHOOTING.md) - Common issues (as needed)

**🔍 Architect/Tech Lead**
- Read: [README](README.md) - How it works (15 min)
- Review: `architecture/model/isolate_organisations/20260307_1137_isolation_architecture.md`

---

## 📖 Complete Guide Structure

```
developer_guide/model/isolation/
├── README.md                    ← Start here for comprehensive overview
├── QUICK_REFERENCE.md           ← Cheat sheet for developers
├── MIGRATION_GUIDE.md           ← Adding isolation to new models
├── TESTING_PATTERNS.md          ← Testing isolation (with examples)
├── TROUBLESHOOTING.md           ← Common issues and solutions
└── INDEX.md                     ← This file
```

---

## 📋 What Each Guide Covers

### [README.md](README.md) - Complete Developer Guide
**Read this first if you're new to organisation isolation.**

- ✅ Overview and quick start
- ✅ How it works (4-layer architecture)
- ✅ Using the BelongsToTenant trait
- ✅ Working with models (queries, relationships)
- ✅ Testing isolation
- ✅ Common patterns
- ✅ Troubleshooting basics
- ✅ Performance tips
- ✅ Checklist for adding isolation

**Time:** 20-30 minutes
**Best for:** Understanding the complete picture

### [QUICK_REFERENCE.md](QUICK_REFERENCE.md) - Cheat Sheet
**Keep this open while coding.**

- ✅ Basic rules (4 critical rules)
- ✅ Common commands
- ✅ Testing patterns (setup, assertions)
- ✅ Middleware flow
- ✅ Model definition checklist
- ✅ Error messages
- ✅ Architecture layers overview
- ✅ File locations

**Time:** 5 minutes
**Best for:** Quick lookups while coding

### [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) - Step-by-Step Implementation
**Follow this when adding isolation to an existing model.**

- ✅ Step-by-step instructions
- ✅ What to add to models
- ✅ What to change in migrations
- ✅ How to update relationships
- ✅ Writing tests
- ✅ Updating controllers/services
- ✅ Backfilling existing data
- ✅ Verification checklist
- ✅ Troubleshooting migration issues
- ✅ Common pitfalls

**Time:** 30-45 minutes
**Best for:** Adding isolation to new models

### [TESTING_PATTERNS.md](TESTING_PATTERNS.md) - Testing Guide
**Reference this when writing isolation tests.**

- ✅ Basic test setup
- ✅ 10 common test patterns
- ✅ Testing edge cases
- ✅ Test helper functions
- ✅ Complete test class example
- ✅ Running tests
- ✅ Assertions reference
- ✅ Tips and tricks

**Time:** 20 minutes
**Best for:** Writing comprehensive tests

### [TROUBLESHOOTING.md](TROUBLESHOOTING.md) - Problem Solving
**Consult this when something isn't working.**

- ✅ 10 common issues with solutions
- ✅ Diagnosis steps for each issue
- ✅ FAQ (10 common questions)
- ✅ Debugging techniques
- ✅ Getting help

**Time:** 5-15 minutes (per issue)
**Best for:** Solving specific problems

---

## 🎯 Common Scenarios

### Scenario 1: "I just started working on this codebase"

1. Read: [README.md](README.md) → Overview section (5 min)
2. Skim: [Quick Reference](QUICK_REFERENCE.md) → Basic Rules (2 min)
3. Reference: [Quick Reference](QUICK_REFERENCE.md) while coding

**Total:** 7 minutes to get started

### Scenario 2: "I need to add isolation to a new model"

1. Read: [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) → Step 1-7
2. Follow: Step-by-step instructions
3. Write tests: [TESTING_PATTERNS.md](TESTING_PATTERNS.md)
4. Debug issues: [TROUBLESHOOTING.md](TROUBLESHOOTING.md) if needed

**Total:** 45 minutes to complete

### Scenario 3: "I'm writing tests for isolated models"

1. Read: [TESTING_PATTERNS.md](TESTING_PATTERNS.md) → Basic Test Setup
2. Copy: A pattern that matches your scenario
3. Adapt: To your specific model
4. Reference: Assertions Reference section

**Total:** 15 minutes

### Scenario 4: "Something isn't working"

1. Find: The symptom in [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
2. Follow: Diagnosis steps
3. Apply: Solution
4. Verify: Using Quick Reference

**Total:** 5-10 minutes

### Scenario 5: "I want to understand the architecture"

1. Read: [README.md](README.md) → How It Works section
2. Review: [README.md](README.md) → 4-Layer Architecture diagram
3. Deep dive: `architecture/model/isolate_organisations/20260307_1137_isolation_architecture.md`

**Total:** 20 minutes

---

## 🔑 Key Concepts

### The Three-Minute Version

**Organisation isolation** means every model automatically filters by the current organisation via a trait called `BelongsToTenant`.

```php
// This automatically ONLY returns current org's elections:
Election::all();

// No manual WHERE clause needed - the trait handles it!
```

### The Five-Minute Version

1. **Middleware** extracts organisation from URL and sets session context
2. **BelongsToTenant trait** reads session and adds `WHERE organisation_id = ?` to all queries
3. **Model auto-fill** sets `organisation_id` when creating records
4. **Database** has foreign keys and indexes as last resort

Result: No cross-organisation data leaks, automatic filtering on every query.

### The Ten-Minute Version

Read the "How It Works" section in [README.md](README.md).

---

## ✅ Pre-Implementation Checklist

Before writing code, verify you have:

- [ ] Read [README.md](README.md) - Overview section
- [ ] Understand the 4-layer architecture
- [ ] Know your model needs isolation
- [ ] Have the migration file ready
- [ ] Have a test plan

---

## 📚 Related Documentation

**Architecture Overview:**
- `architecture/model/isolate_organisations/20260307_1137_isolation_architecture.md` - Visual diagrams

**Implementation Reference:**
- `architecture/model/isolate_organisations/20260307_1137_how_to_isolate_organisations.md` - Technical details

**Working Tests:**
- `tests/Feature/OrganisationIsolationTest.php` - 13 comprehensive tests

**Source Code:**
- `app/Traits/BelongsToTenant.php` - The core trait
- `app/Http/Middleware/EnsureOrganisationMember.php` - Middleware
- `app/Services/TenantContext.php` - Stateful service

---

## 🆘 Getting Help

1. **Quick answer:** Check [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
2. **Issue not working:** Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md)
3. **Confused:** Re-read the "How It Works" section in [README.md](README.md)
4. **Stuck:** Look at working test examples in `tests/Feature/OrganisationIsolationTest.php`

---

## 📝 File Summary

| File | Purpose | Read Time | Best For |
|------|---------|-----------|----------|
| [README.md](README.md) | Complete guide | 20-30 min | Learning the full system |
| [QUICK_REFERENCE.md](QUICK_REFERENCE.md) | Cheat sheet | 5 min | Quick lookups |
| [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) | Add isolation | 30-45 min | Implementing new models |
| [TESTING_PATTERNS.md](TESTING_PATTERNS.md) | Test examples | 20 min | Writing tests |
| [TROUBLESHOOTING.md](TROUBLESHOOTING.md) | Problem solving | 5-15 min | Fixing issues |

---

## 🎓 Learning Path

### Beginner (30 minutes)
1. Read: README.md - Quick Start section
2. Skim: QUICK_REFERENCE.md
3. Practice: Write a simple query

### Intermediate (60 minutes)
1. Read: README.md - Complete
2. Read: MIGRATION_GUIDE.md - Steps 1-3
3. Practice: Add isolation to a small model

### Advanced (90 minutes)
1. Read: All guides
2. Study: Source code (BelongsToTenant.php)
3. Practice: Complex isolation scenarios

---

## 🚀 Next Steps

1. **Start with your role:** Find your role above and follow the path
2. **Bookmark this page:** You'll come back to it
3. **Save QUICK_REFERENCE.md:** Keep it open while coding
4. **Join Slack/Docs:** Ask questions in team channel

---

## Version & Maintenance

- **Last Updated:** 2026-03-07
- **Covers:** BelongsToTenant v1.0 with N+1 cache fix
- **Authors:** Architecture Team
- **Status:** ✅ Production Ready

---

## Contributing to This Guide

Found an error? Have a better explanation? Contribute by:
1. Opening an issue on GitHub
2. Submitting a pull request with improvements
3. Adding your own test examples

---

**Happy coding! 🎉**
