# Election State Machine — SSOT Architecture Documentation

## 📚 Documentation Index

This folder contains comprehensive documentation for the new Single Source of Truth (SSOT) election state machine architecture.

### Quick Navigation

**New to this system?** Start here:
1. [01_OVERVIEW.md](01_OVERVIEW.md) — Understand the paradigm shift
2. [02_STATE_TRANSITIONS.md](02_STATE_TRANSITIONS.md) — See all 12 states and transitions
3. [03_DEVELOPER_API.md](03_DEVELOPER_API.md) — Learn the API

**Building features?**
- [05_CODE_RECIPES.md](05_CODE_RECIPES.md) — Copy-paste examples for common tasks

**Validating implementation?**
- [07_TEST_VERIFICATION.md](07_TEST_VERIFICATION.md) — Test verification report (24/24 tests passing)

**Something not working?**
- [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md) — Debug guide with solutions

**Migrating old code?**
- [06_MIGRATION_GUIDE.md](06_MIGRATION_GUIDE.md) — Step-by-step migration from old system

---

## 📄 Document Guide

### 01_OVERVIEW.md (Essential Context)
**Read first.** Explains:
- What changed from old to new system
- The 12 SSOT states (progression + suspended)
- Constitutional facts vs. derived state
- Three-layer architecture
- Key principles and patterns
- What NOT to do

**Time to read:** 20 minutes
**Knowledge gained:** Conceptual understanding of SSOT architecture

---

### 02_STATE_TRANSITIONS.md (State Machine Map)
**Complete state diagram and transition rules.**

Includes:
- Full state diagram (ASCII art)
- Allowed transitions for each state
- Preconditions for each transition
- Side effects of transitions
- Automatic state derivation rules
- Special cases and edge cases
- Timeline example of typical election
- Testing patterns
- Debugging tips

**Time to read:** 25 minutes
**Knowledge gained:** Understand entire state machine

---

### 03_DEVELOPER_API.md (API Reference)
**The most referenced document.** Keep it bookmarked.**

Complete API reference including:
- `ElectionLifecycle` facade methods
- State information methods
- Capability checks (canVote, canEdit, etc.)
- Transition management
- Building transitions
- Usage in controllers, policies, Vue components, tests
- Common patterns
- Error handling
- Performance notes

**Time to read:** 30 minutes to skim, reference as needed
**Knowledge gained:** How to use ElectionLifecycle API

---

### 04_TROUBLESHOOTING.md (Debug Guide)
**When something is broken.** Follow the diagnosis steps.**

Covers:
- Quick diagnosis checklist
- 7 common issues with solutions
- Root cause analysis for each
- Debug commands
- When to contact support

Common issues:
1. State machine not responding (500 errors)
2. No buttons showing in management page
3. Stale state column
4. InvalidTransitionException
5. Cannot approve voters / manage settings
6. Voting window not opening
7. Tests failing with state mismatch

**Time to read:** 5 minutes to find your issue, then 10-15 minutes to solve
**Knowledge gained:** How to diagnose and fix state machine issues

---

### 05_CODE_RECIPES.md (Copy-Paste Examples)
**14 production-ready code snippets for common scenarios.**

Recipes for:
- Quick starts (3 examples)
- Controller patterns (3 examples)
- Test patterns (2 examples)
- Policy patterns (1 example)
- View patterns (1 example)
- Utility functions (2 examples)
- Performance optimizations (2 examples)

**Time to spend:** Copy, paste, adapt to your context
**Knowledge gained:** Working examples you can build from

---

### 06_MIGRATION_GUIDE.md (Upgrade Instructions)
**For teams moving from old system to SSOT.**

Includes:
- Overview of what changed
- Migration checklist
- Step-by-step migration
- State name mapping (old → new)
- Common patterns before/after
- Before-and-after code examples
- Database considerations
- Testing migration
- Team training guide
- Success criteria
- Phase 3.2 strict mode info

**Time to spend:** 1-2 hours for team migration
**Knowledge gained:** How to systematically upgrade codebase

---

### 07_TEST_VERIFICATION.md (Verification Report)
**Proof that the state machine works correctly.**

Includes:
- Test coverage summary (24 tests, all passing)
- State derivation verification (10 tests)
- State transition verification (7 tests)
- Capability verification (6 tests)
- Bugs discovered and fixed during testing
- Constitutional fact mapping
- Authorization verification
- Test reliability analysis
- Compliance checklist

**Time to read:** 10 minutes to understand verification scope
**Knowledge gained:** Confidence that system is production-ready

---

## 🎯 Getting Started

### If you have 5 minutes
Read the "Executive Summary" section in [01_OVERVIEW.md](01_OVERVIEW.md)

### If you have 30 minutes
1. Skim [01_OVERVIEW.md](01_OVERVIEW.md)
2. Study the state diagram in [02_STATE_TRANSITIONS.md](02_STATE_TRANSITIONS.md)
3. Bookmark [03_DEVELOPER_API.md](03_DEVELOPER_API.md)

### If you have 2 hours
1. Read [01_OVERVIEW.md](01_OVERVIEW.md) fully
2. Read [02_STATE_TRANSITIONS.md](02_STATE_TRANSITIONS.md) fully
3. Skim [03_DEVELOPER_API.md](03_DEVELOPER_API.md)
4. Copy one example from [05_CODE_RECIPES.md](05_CODE_RECIPES.md) and adapt it

### If you're migrating code from old system
1. Read [06_MIGRATION_GUIDE.md](06_MIGRATION_GUIDE.md) — Migration Checklist
2. For each item, reference the relevant "Before & After" section
3. Use [03_DEVELOPER_API.md](03_DEVELOPER_API.md) for API details
4. Copy examples from [05_CODE_RECIPES.md](05_CODE_RECIPES.md)

---

## 🔑 Key Concepts at a Glance

### The Big Idea
```
Constitutional Facts (voting_starts_at, nomination_completed, etc.)
                        ↓
                    [Engine computes]
                        ↓
                ElectionLifecycleState (voting_active, setup, etc.)
                        ↓
            [Use this, not database column]
```

### The 12 States
**Progression (time-based):**
- `draft` — Initial state
- `submitted_for_approval` — Awaiting platform admin review
- `approved` — Admin approved, ready for setup
- `setup_administration` — Admin phase (posts, voters, chief)
- `setup_nomination` — Nomination phase (candidates)
- `ready_for_voting` — Setup complete, voting not yet started
- `voting_active` — Voting window open
- `counting` — Voting closed, tallying
- `results_published` — Results published (terminal)

**Terminal/Special:**
- `rejected` — Admin rejected (terminal)
- `archived` — Archived/historical (terminal)
- `suspended` — Operational governance pause (checked FIRST)

### The Golden Rule
> Never read from `$election->state` directly.
> 
> Always use: `ElectionLifecycle::of($election)->state()->value`

### The API

```php
// Getting state
$lifecycle = ElectionLifecycle::of($election);
$state = $lifecycle->state();                    // Get current state
$snapshot = $lifecycle->snapshot();              // Get full snapshot

// Checking capabilities
$lifecycle->canVote();                           // Can voting happen?
$lifecycle->canEdit();                           // Can election be edited?
$lifecycle->canPublishResults();                 // Can results be published?
$lifecycle->allowedActions();                    // What transitions allowed?

// Performing transitions
$election->transitionTo(                         // Execute state change
    Transition::manual('open_voting', userId, 'reason')
);
```

---

## 🛠️ Common Tasks

### "How do I check if voting is allowed?"
→ Use `ElectionLifecycle::of($election)->canVote()`
→ Reference: [03_DEVELOPER_API.md](03_DEVELOPER_API.md) — `canVote()` method

### "How do I transition to a new state?"
→ Use `$election->transitionTo(Transition::manual(...))`
→ Reference: [05_CODE_RECIPES.md](05_CODE_RECIPES.md) — Recipe 4 (Safe State Transition)

### "How do I show the right buttons in Vue?"
→ Use `stateMachine.allowedActions`
→ Reference: [05_CODE_RECIPES.md](05_CODE_RECIPES.md) — Recipe 3 (Show Conditional Buttons)

### "How do I write a test that sets up the right state?"
→ Use `ElectionScenarioFactory::votingActive($org)`
→ Reference: [05_CODE_RECIPES.md](05_CODE_RECIPES.md) — Recipe 7 (TDD Pattern)

### "What do I do if voting won't open?"
→ Follow the diagnosis steps in [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md) — Issue 6

### "I'm getting 'InvalidTransitionException'. What's wrong?"
→ Read [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md) — Issue 4

### "How do I migrate old code to use SSOT?"
→ Follow [06_MIGRATION_GUIDE.md](06_MIGRATION_GUIDE.md) step by step

---

## 📋 Architecture Summary

### Three Layers

```
┌─────────────────────────────────────────────────────┐
│ Layer 1: Controllers, Policies, Vue Components      │
│ USE: ElectionLifecycle::of($election)->state()      │
└─────────────────────────────────────────────────────┘
                         ↑
┌─────────────────────────────────────────────────────┐
│ Layer 2: ElectionLifecycle Facade (API)             │
│ ├─ state() → Get state                              │
│ ├─ canVote() → Can voting happen?                   │
│ ├─ allowedActions() → What transitions allowed?     │
│ └─ transitionTo() → Execute transition              │
└─────────────────────────────────────────────────────┘
                         ↑
┌─────────────────────────────────────────────────────┐
│ Layer 3: ElectionLifecycleEngine (Pure PHP)         │
│ ├─ Computes state from constitutional facts         │
│ ├─ No Laravel dependencies                          │
│ └─ 10 derivation rules                              │
└─────────────────────────────────────────────────────┘
                         ↑
                   Constitutional Facts
              (voting_starts_at, nomination_completed, etc.)
```

### File Organization
```
app/Application/Election/
├── Facades/
│   └── ElectionLifecycle.php            ← Use this
├── Services/
│   └── ElectionLifecycleEngineImpl.php   ← Computes state
└── Constitution/
    └── ElectionConstitution.php         ← Transition rules

tests/Support/
└── ElectionScenarioFactory.php          ← Build test scenarios
```

---

## ✅ Quality Assurance

All documentation in this folder:
- ✅ Reviewed by senior engineers
- ✅ Tested with real code examples
- ✅ Updated for Phase 3.1 completion
- ✅ Production-ready
- ✅ Maintained for clarity

---

## 📞 Support

### Before Contacting Support
1. Check [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md) — your issue might be listed
2. Run the debug commands at bottom of [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md)
3. Verify you're using patterns from [05_CODE_RECIPES.md](05_CODE_RECIPES.md)

### When Contacting Support
Provide:
- Election ID
- The exact error message or symptom
- The debug output from troubleshooting guide
- What action user was trying to perform

---

## 🚀 Next Steps

### For Developers
1. **Today:** Read [01_OVERVIEW.md](01_OVERVIEW.md)
2. **This week:** Read [02_STATE_TRANSITIONS.md](02_STATE_TRANSITIONS.md)
3. **Use daily:** Keep [03_DEVELOPER_API.md](03_DEVELOPER_API.md) and [05_CODE_RECIPES.md](05_CODE_RECIPES.md) bookmarked
4. **When needed:** Reference [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md)

### For Teams
1. Run training session using this documentation
2. Setup code review checklist (see [06_MIGRATION_GUIDE.md](06_MIGRATION_GUIDE.md) — Success Criteria)
3. Share [05_CODE_RECIPES.md](05_CODE_RECIPES.md) with team
4. Keep [04_TROUBLESHOOTING.md](04_TROUBLESHOOTING.md) accessible

### For the Codebase
- Phase 3.1: ✅ Complete
- Phase 3.2: Strict mode activation (when all code migrated)
- Phase 3.3: Historical audit log analysis

---

## 📚 Related Resources

**In Codebase:**
- `app/Application/Election/Facades/ElectionLifecycle.php` — Main API
- `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` — State computer
- `app/Domain/Election/Constitution/ElectionConstitution.php` — Transition rules
- `tests/Support/ElectionScenarioFactory.php` — Test fixtures

**Design Documents:**
- `.claude/plans/` — Implementation plans and phase notes
- `CLAUDE.md` — Project guidelines

---

## 📊 Document Statistics

| Document | Pages | Time | Audience |
|----------|-------|------|----------|
| 01_OVERVIEW.md | 6 | 20 min | All |
| 02_STATE_TRANSITIONS.md | 8 | 25 min | Developers |
| 03_DEVELOPER_API.md | 14 | 30 min | Developers |
| 04_TROUBLESHOOTING.md | 8 | 10 min | Developers |
| 05_CODE_RECIPES.md | 12 | 20 min | Developers |
| 06_MIGRATION_GUIDE.md | 10 | 60 min | Teams |
| **Total** | **58** | **165 min** | — |

---

## 🎓 Learning Paths

### Path 1: Quick Understanding (1 hour)
1. [01_OVERVIEW.md](01_OVERVIEW.md) — Executive Summary section only
2. [02_STATE_TRANSITIONS.md](02_STATE_TRANSITIONS.md) — State diagram only
3. [03_DEVELOPER_API.md](03_DEVELOPER_API.md) — API methods section only

### Path 2: Developer Proficiency (3 hours)
1. [01_OVERVIEW.md](01_OVERVIEW.md) — Complete
2. [02_STATE_TRANSITIONS.md](02_STATE_TRANSITIONS.md) — Complete
3. [03_DEVELOPER_API.md](03_DEVELOPER_API.md) — Complete
4. [05_CODE_RECIPES.md](05_CODE_RECIPES.md) — Copy 2-3 recipes and adapt

### Path 3: Team Migration (1 day)
1. All of Path 2
2. [06_MIGRATION_GUIDE.md](06_MIGRATION_GUIDE.md) — Complete
3. Team training session
4. Code review of first 5 changes

### Path 4: Expert Mastery (Full)
1-6. All documents completely
+ Explore source code in `app/Application/Election/`
+ Study test patterns in `tests/Feature/Election/`

---

**Version:** 1.0 (Stable)
**Last Updated:** May 21, 2026
**Status:** Production-Ready
**Maintenance:** Ongoing — Phase 3.1 Complete
