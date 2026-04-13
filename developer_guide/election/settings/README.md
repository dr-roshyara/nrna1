# Election Settings — Complete Guide Index

This folder contains comprehensive documentation for the election settings feature, including voter verification, constraint enforcement, and admin configuration.

---

## 📚 Documentation Structure

### 1. **`DEVELOPER_GUIDE.md`** — Complete Technical Reference
**For:** Backend developers, system architects  
**Coverage:** Database schema, models, controllers, validation rules, tests, performance

**Sections:**
- Architecture (domain model, concurrency control, authorization)
- Database schema with migrations
- Key files (models, controllers, routes, factories)
- Test suite (9 passing tests)
- Development workflow (how to add new settings)
- Common tasks (enable IP restrictions, validate counts)
- Performance considerations
- Known limitations & future work
- Troubleshooting

**Status:** Phase 1 (Core) + Phase 2 (Voter Verification) documented

---

### 2. **`01-no-vote-and-constraints-enforcement.md`** — NEW: Voting Workflow Enforcement
**For:** Developers implementing voting UI, backend validation, frontend gating  
**Coverage:** Complete pipeline from admin settings → backend validation → frontend UI

**Sections:**
- **Context:** The problem we solved (before/after)
- **Architecture:** Data flow diagram and design decisions
- **Implementation Layers:**
  - Layer 1: Backend settings exposure (caching strategy)
  - Layer 2: Backend validation (per-election, security checks)
  - Layer 3: Frontend UI gating (PostSection, constraint hints)
- **Constraint Types Reference:** All 5 types with examples
- **Testing Strategy:** 3 new tests (exposure, security, enforcement)
- **Debugging & Troubleshooting:** Common issues and solutions
- **Performance:** Database, caching, frontend impact
- **Future Enhancements:** Per-post, conditional, templates
- **Implementation Checklist:** Step-by-step for similar patterns

**Status:** Complete & Tested ✅ (Phase 1.5)

**Files Modified:**
- `app/Http/Controllers/VoteController.php` — Cache settings, validate per-election
- `resources/js/Pages/Vote/CreateVotingPage.vue` — Constraint hints, props flow
- `resources/js/Pages/Vote/components/PostSection.vue` — UI gating, dynamic labels
- `tests/Feature/VoteControllerTest.php` — 3 new tests

---

### 3. **`USER_GUIDE.md`** — Election Officer Instructions
**For:** Election officers, administrative users  
**Coverage:** How to use the settings UI, understand options, make decisions

**Sections:**
- How to configure settings
- Explanation of each setting (with examples)
- Verification modes and use cases
- Common scenarios (small club, remote team, diaspora org)
- FAQ

**Status:** User-facing documentation

---

### 4. **`how_to_verify_voter.md`** — Voter Verification Workflow
**For:** Election officers conducting video call verifications  
**Coverage:** Step-by-step instructions for verifying voter identity

**Sections:**
- Prerequisites (what to check before verifying)
- Video call workflow
- Recording IP address and device fingerprint
- Handling re-verification and disputes
- FAQ

**Status:** User-facing documentation

---

## 🎯 Quick Navigation by Role

### 👨‍💻 Backend Developer
Start here → `DEVELOPER_GUIDE.md` (Phase 1 + Phase 2 architecture)  
Then → `01-no-vote-and-constraints-enforcement.md` (voting workflow)

### 🎨 Frontend Developer
Start here → `01-no-vote-and-constraints-enforcement.md` (Layer 3: Frontend UI)  
Reference → `DEVELOPER_GUIDE.md` (settings data structure)

### 🔐 Security/QA Engineer
Start here → `01-no-vote-and-constraints-enforcement.md` (security checks)  
Reference → `DEVELOPER_GUIDE.md` (authorization policy)

### 📊 Election Officer
Start here → `USER_GUIDE.md` (settings overview)  
Then → `how_to_verify_voter.md` (voter verification workflow)

---

## 📋 Feature Phases

### Phase 1: Settings Storage & Admin UI ✅
- IP restriction (CIDR support)
- No-vote option
- Selection constraints (any, exact, minimum, maximum, range)
- Optimistic locking
- Active election guard
- Audit trail
- **Documentation:** `DEVELOPER_GUIDE.md` — "Overview" through "Common Tasks"

### Phase 1.5: Voting Workflow Enforcement ✅ (NEW)
- Backend settings exposure via Inertia props
- Per-election server-side validation
- Frontend UI gating (hide options when disabled)
- Constraint hint banner for voters
- Security: immediate rejection of bypasses
- **Documentation:** `01-no-vote-and-constraints-enforcement.md`

### Phase 2: Voter Verification (Partial)
- Admin endpoints for saving/revoking verification
- Database schema (voter_verifications)
- Model relationships
- Authorization checks
- **Status:** Admin endpoints working, enforcement TBD
- **Documentation:** `DEVELOPER_GUIDE.md` — "Phase 2" section

### Phase 2.2: Verification Enforcement (Deferred)
- Voting enforcement in VoteController
- Client-side fingerprint capture
- Voter dashboard UI
- Enforcement tests

### Phase 2.3: Advanced Verification (Future)
- Bulk import
- Phone-based verification
- Email verification
- Multi-factor verification
- Verification history

---

## 🧪 Test Coverage

### Phase 1 Tests
- ✅ 9/9 tests passing (`ElectionSettingsTest.php`)
- Coverage: Settings CRUD, access control, IP restriction, ballot options

### Phase 1.5 Tests
- ✅ 3/3 tests passing (`VoteControllerTest.php`)
- Coverage: Settings exposure, no-vote security, constraint enforcement

### Phase 2 Tests
- ✅ 5/5 admin tests passing
- ⏳ 5/5 enforcement tests skipped (Phase 2.2 — TBD)

**Total:** 17/17 passing tests

---

## 🔑 Key Concepts

### Settings Exposure Pattern
```
Admin saves setting → Cache with TTL 5min → Auto-invalidate on change
                   → Expose to Inertia props → Frontend receives settings
```

### Validation Pattern
```
Voter submits data → Server extracts per-election settings
                  → Security check (bypass detection) → Throws if detected
                  → Constraint validation → Constraint-aware error messages
```

### UI Gating Pattern
```
Receive setting from backend → v-if="settingEnabled" → Show/hide UI element
                             → Use dynamic labels → Display constraint rules
```

### Constraint Types
| Type | Rule | Example |
|------|------|---------|
| `any` | ≥1 | "Pick any eligible person" |
| `exact` | ===N | "Vote for exactly 5 board members" |
| `minimum` | ≥N | "Select at least 2 nominees" |
| `maximum` | ≤N | "Select up to 3 preferences" |
| `range` | N≤count≤M | "Select 2–4 positions" |

---

## 🚀 Getting Started

### For New Features
1. Read `DEVELOPER_GUIDE.md` — "Development Workflow" section
2. Follow the "Adding a New Setting" example
3. Write test first (TDD)
4. Implement in model, controller, view
5. Add to validation rules

### For Troubleshooting
1. Check the specific guide's "Troubleshooting" section
2. Use debug checklist to verify settings
3. Check cache invalidation
4. Verify authorization policy
5. Review test cases for expected behavior

### For Extending Functionality
1. Review "Future Work" section in relevant guide
2. Consider adding helper methods to Election model
3. Update tests alongside implementation
4. Document changes in appropriate guide

---

## 📖 Reading Recommendations

**"I want to understand the big picture"**
→ Read: `DEVELOPER_GUIDE.md` — "Overview" + "Architecture" sections

**"I need to implement no-vote enforcement"**
→ Read: `01-no-vote-and-constraints-enforcement.md` — "Implementation Layers" section

**"Settings aren't working — help!"**
→ Read: `01-no-vote-and-constraints-enforcement.md` — "Debugging" section

**"I need to add a new setting to elections"**
→ Read: `DEVELOPER_GUIDE.md` — "Development Workflow" section

**"How do I verify voters?"**
→ Read: `how_to_verify_voter.md` (step-by-step guide)

**"What constraints are available?"**
→ Read: `01-no-vote-and-constraints-enforcement.md` — "Constraint Types Reference"

---

## 🔗 Related Folders

- **`../election_management/`** — Election creation, officer assignment
- **`../real_election/`** — Real election voting workflow
- **`../demo_election/`** — Demo election testing
- **`../voter/`** — Voter slug, step tracking, verification
- **`../result/`** — Vote results and reporting

---

## 📝 Document Status

| Document | Status | Last Updated | Coverage |
|----------|--------|--------------|----------|
| `DEVELOPER_GUIDE.md` | Complete | Phase 1 + Phase 2 docs | Core settings, verification endpoints |
| `01-no-vote-and-constraints-enforcement.md` | Complete ✅ | Phase 1.5 (NEW) | Full voting workflow enforcement |
| `USER_GUIDE.md` | Complete | For election officers | Settings UI, verification modes |
| `how_to_verify_voter.md` | Complete | For election officers | Voter verification workflow |

---

## 💡 Best Practices

### When Adding Settings
- [ ] Test with setting enabled AND disabled
- [ ] Provide sensible defaults
- [ ] Document in USER_GUIDE.md
- [ ] Add to DEVELOPER_GUIDE.md "Common Tasks" section
- [ ] Include in troubleshooting if applicable

### When Implementing Enforcement
- [ ] Write test first (TDD)
- [ ] Validate on backend (security-first)
- [ ] Gate UI on frontend (UX)
- [ ] Show rules to user (prevent confusion)
- [ ] Log bypass attempts (audit trail)

### When Troubleshooting
- [ ] Check database values
- [ ] Verify cache invalidation
- [ ] Test with fresh browser session
- [ ] Review authorization policy
- [ ] Run relevant test suite

---

## 🤝 Contributing

When updating these guides:
1. Keep the structure (Sections, Examples, Code blocks)
2. Update "Last Updated" timestamp
3. Add to appropriate document
4. Link to related documents
5. Include before/after examples
6. Add test cases for new features

---

**Questions?** Refer to the appropriate guide or check the "Troubleshooting" section.
