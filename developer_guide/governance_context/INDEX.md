# Governance Context Documentation Index

Welcome to the Governance Context developer guide. This directory contains comprehensive documentation for the committee membership and governance system.

## 📚 Documentation Files

### 1. **README.md** - Main Overview
Start here for a complete understanding of the governance context architecture.

**Contents:**
- Architecture overview (CQRS pattern, event-driven)
- Component descriptions and responsibilities
- Phase 1 implementation details (roles)
- Complete data flow walkthrough
- Usage examples (API, programmatic, Vue)
- Role semantics (current & planned)
- Testing guide
- Extending the system
- Key invariants to preserve
- Troubleshooting

**Read this if:** You're new to the governance context, need to understand architecture, or want the big picture.

**Time to read:** 20-30 minutes

### 2. **QUICK_REFERENCE.md** - Developer Cheat Sheet
Fast lookup for file locations, API endpoints, database schemas, and common tasks.

**Contents:**
- File locations (all critical files)
- Database schema (committee_member_projection)
- API endpoints (POST/GET/DELETE)
- CommitteeRole enum values
- Event registration
- Service container bindings
- Unit tests and how to run them
- Vue component props
- Common database queries
- Migration checklist
- Debugging tips

**Read this if:** You need to find something quickly, implement a feature, or debug an issue.

**Time to read:** 5-10 minutes (lookup as needed)

### 3. **PHASE_2_ROADMAP.md** - Future Development Plan
Detailed plan for Phase 2 (temporal lifecycle) and beyond.

**Contents:**
- Phase 1 summary (what's done)
- Phase 2 vision (what's next)
- Implementation plan with code examples:
  - Database schema (temporal states)
  - Domain events (suspend, terminate)
  - Aggregate methods
  - Event listeners
  - API endpoints
  - Query service extensions
  - Vue component updates
  - Test examples
- Rollout strategy
- Breaking changes analysis
- Effort estimation
- Stakeholder questions

**Read this if:** You're planning Phase 2 development, extending the system, or want to understand the roadmap.

**Time to read:** 30-40 minutes

---

## 🗂️ Quick Navigation

### By Role

**👨‍💻 I'm a Backend Developer**
1. Read: README.md → "Architecture Layers" section
2. Reference: QUICK_REFERENCE.md → "File Locations" section
3. Implement: Follow QUICK_REFERENCE.md examples
4. Test: Run `php artisan test tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php`

**🎨 I'm a Frontend Developer**
1. Read: README.md → "Phase 1: Role-Based Member Assignment" section
2. Reference: QUICK_REFERENCE.md → "Frontend Component Props" section
3. Implement: Update `CommitteeMemberManager.vue`
4. Test: Manual testing in browser at `/organisations/{slug}/committees`

**🏗️ I'm an Architect**
1. Read: README.md (full document)
2. Review: PHASE_2_ROADMAP.md for extension strategy
3. Validate: Key Invariants section in README.md

**🧪 I'm a QA Engineer**
1. Read: README.md → "Testing" section
2. Reference: QUICK_REFERENCE.md → "API Endpoints" section
3. Test: Manual workflows in Chrome/Firefox
4. Verify: Database queries in QUICK_REFERENCE.md

---

### By Task

**I want to...**

| Task | Read | Reference |
|------|------|-----------|
| Add a member to a committee | README.md "Usage Examples" | QUICK_REFERENCE.md "Common Tasks" |
| Implement role-based voting | PHASE_2_ROADMAP.md | README.md "Role Semantics" |
| Debug role not appearing | README.md "Troubleshooting" | QUICK_REFERENCE.md "Debugging" |
| Understand the event flow | README.md "Data Flow" | QUICK_REFERENCE.md "Data Flow Summary" |
| Extend the API | QUICK_REFERENCE.md "API Endpoints" | README.md "Extending" |
| Write tests | README.md "Testing" | QUICK_REFERENCE.md "Unit Tests" |
| Deploy to production | QUICK_REFERENCE.md "Migration Checklist" | README.md "Key Invariants" |

---

## 🔑 Key Concepts

### CQRS (Command Query Responsibility Segregation)
- **Command Side:** Write operations (add member, suspend, terminate)
- **Query Side:** Read operations (list members, filter by status)
- Files: Controller writes → Domain → Events → Listener → Projection table ← Query service reads

### Event-Driven Architecture
- Domain aggregates record events (what happened)
- Listeners consume events (react to changes)
- Projections maintain denormalized data (optimized for reads)
- Event sourcing provides audit trail

### Denormalization Pattern
- Read model (`committee_member_projection`) stores pre-computed data
- No complex joins needed for queries
- Listener updates projection when domain events fire
- Trade-off: eventual consistency (milliseconds) for speed

---

## 📊 File Structure

```
developer_guide/governance_context/
├── INDEX.md                    ← You are here
├── README.md                   ← Main documentation
├── QUICK_REFERENCE.md          ← Lookup reference
└── PHASE_2_ROADMAP.md          ← Future planning
```

## 🔗 Related Code

### Critical Source Files

**Domain Layer** (Business Logic)
- `app/Contexts/Governance/Domain/Committee/Committee.php`
- `app/Contexts/Governance/Domain/Committee/Enums/CommitteeRole.php`
- `app/Contexts/Governance/Domain/Committee/Events/MemberAssignedToCommittee.php`
- `app/Contexts/Governance/Domain/Committee/CommitteeRepositoryInterface.php`

**Infrastructure Layer** (Persistence & Events)
- `app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php`
- `app/Contexts/Governance/Infrastructure/Projection/CommitteeMemberProjectionListener.php`
- `app/Contexts/Governance/Infrastructure/Providers/GovernanceServiceProvider.php`

**Application Layer** (Query & API)
- `app/Contexts/Governance/Application/Queries/CommitteeMemberQueryService.php`
- `app/Http/Controllers/Api/Governance/CommitteeMemberController.php`

**Frontend**
- `resources/js/Components/CommitteeMemberManager.vue`

**Tests**
- `tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php`

---

## 🚀 Getting Started (5-Minute Quickstart)

1. **Understand the architecture** (2 min)
   - Read README.md: "Architecture Layers" section
   - Look at the ASCII diagram showing data flow

2. **Find the files you need** (1 min)
   - Use QUICK_REFERENCE.md: "File Locations" table

3. **See the API contract** (1 min)
   - QUICK_REFERENCE.md: "API Endpoints" section

4. **Test in your browser** (1 min)
   - Navigate to `/organisations/{slug}/committees`
   - Select a committee
   - Try adding a member with a role

5. **Run the tests** (Keep reading below)

---

## 🧪 Running Tests

### All Governance Tests
```bash
php artisan test tests/Unit/Governance/Domain/CommitteeRoleAssignmentTest.php --testdox
```

### Watch Mode (Auto-run on save)
```bash
php artisan test tests/Unit/Governance/Domain/ --watch
```

### Expected Output
```
✓ test_it_assigns_member_with_role
✓ test_duplicate_member_assignment_is_rejected
✓ test_member_assignment_preserves_role_type
✓ test_all_role_types_are_assignable

4 tests passed
```

---

## ❓ FAQ

**Q: Where's the database migration?**
A: See QUICK_REFERENCE.md "Migration Checklist" section

**Q: How do I know what's Phase 1 vs Phase 2?**
A: README.md "Phase 1: Role-Based Member Assignment" marks Phase 1 with ✅. Phase 2 features marked ❌.

**Q: Can I add custom roles?**
A: Currently no—roles are enum (type-safe). See PHASE_2_ROADMAP.md for extensibility plan.

**Q: What happens if a listener fails?**
A: Event dispatch will fail and transaction rolls back. See README.md "Troubleshooting" section.

**Q: How is this different from just storing roles in a column?**
A: This uses events to track *when/why* changes happened (audit trail) and ensures consistency across all layers.

---

## 📞 Support

### Found a bug?
1. Check README.md "Troubleshooting" section
2. Review QUICK_REFERENCE.md "Debugging" section
3. Verify migrations are applied (QUICK_REFERENCE.md "Migration Checklist")

### Need to extend it?
1. Read PHASE_2_ROADMAP.md for the extension pattern
2. Follow the 8-step implementation plan
3. Add tests first (TDD approach)

### Have questions?
1. Check the FAQ above
2. Search relevant sections in README.md
3. Look at code examples in QUICK_REFERENCE.md

---

## 📋 Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-05-16 | Phase 1 complete: Role-based member assignment |
| TBD | TBD | Phase 2: Temporal lifecycle (suspended, terminated) |
| TBD | TBD | Phase 3: Membership history & audit queries |

---

## 🎯 Next Steps

1. **You're reading this:** ✅ You're in the right place
2. **Choose your path:**
   - Backend? → README.md "Architecture Layers"
   - Frontend? → README.md "Frontend" section
   - Architecture? → PHASE_2_ROADMAP.md
3. **Implement your feature**
4. **Run tests:** `php artisan test`
5. **Deploy confidently**

---

## 📄 License & Attribution

Part of the Public Digit Voting Platform governance subsystem.

- **Architecture Pattern:** DDD (Domain-Driven Design) + CQRS
- **Developed:** 2026-05-16
- **Status:** Phase 1 complete, Phase 2 planned

---

**Happy coding! 🚀**

For questions or improvements to this guide, see the README.md contact section.

