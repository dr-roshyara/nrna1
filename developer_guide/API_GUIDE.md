# 📚 API Architecture Developer Guide

**Complete documentation on the API architecture, debugging journey, and best practices.**

---

## 📖 Documentation Files

Navigate to `developer_guide/api/` directory and read these in order:

### **Start Here**
1. **[README.md](./api/README.md)** — Quick start, navigation guide, checklist
2. **[SUMMARY.md](./api/SUMMARY.md)** — High-level overview of what was built and why

### **Architecture Deep Dives**
3. **[ARCHITECTURE.md](./api/ARCHITECTURE.md)** — Complete system design, layers, patterns, request flow
4. **[MULTI_TENANCY.md](./api/MULTI_TENANCY.md)** — Header-based tenant isolation implementation

### **Development Guides**
5. **[BEST_PRACTICES.md](./api/BEST_PRACTICES.md)** — Coding guidelines, patterns, conventions
6. **[DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md)** — All 7 issues we fixed, root causes, solutions

---

## 🎯 Quick Navigation

### "How do I...?"

**Add a new API endpoint?**
→ Read [README.md](./api/README.md) "Quick Start: Adding a New API Endpoint"

**Understand the architecture?**
→ Read [ARCHITECTURE.md](./api/ARCHITECTURE.md) "Architectural Layers"

**Debug an issue?**
→ Read [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md) "Debugging Checklist"

**Implement multi-tenancy correctly?**
→ Read [MULTI_TENANCY.md](./api/MULTI_TENANCY.md)

**Follow best practices?**
→ Read [BEST_PRACTICES.md](./api/BEST_PRACTICES.md)

---

## 📊 Issues Fixed (with Debugging Documentation)

We encountered and **completely documented** 7 major issues:

| # | Issue | Root Cause | Solution | Doc |
|---|-------|-----------|----------|-----|
| 1 | HTML instead of JSON (302) | Inertia intercepting routes | Separate routes in bootstrap/app.php | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-1) |
| 2 | Missing X-Org-ID header | Tenant ID resolved to null | Pass tenant via props/URL | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-2) |
| 3 | CSRF token mismatch (419) | API in web group, CSRF enabled | Exempt /api/v1/* from CSRF | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-3) |
| 4 | 401 before tenant set | Auth before tenant.api middleware | Reorder middleware | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-4) |
| 5 | 404 on valid routes | Nested prefix groups | Remove prefix from routes/api_v1.php | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-5) |
| 6 | User not found | Member ID ≠ User ID | Resolve Member → User | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-6) |
| 7 | Column doesn't exist | Used tenant_id, table has organisation_id | Fix column name | [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md#issue-7) |

---

## 🏗️ Architecture at a Glance

```
HTTP Request
    ↓
Route Matching (routes/api_v1.php)
    ↓
Middleware Stack (web → json.api → tenant.api → auth → verified)
    ├─ Tenant context extracted and validated
    └─ User authentication verified
    ↓
Controller (Thin HTTP Adapter)
    ├─ Validate request format
    ├─ Extract tenant context
    └─ Create Command/Query
    ↓
Application Layer (Commands & Handlers)
    ├─ Load aggregate
    ├─ Orchestrate domain logic
    └─ Persist via repository
    ↓
Domain Layer (Pure PHP)
    ├─ Validate business rules
    ├─ Modify aggregate state
    └─ Generate domain events
    ↓
Infrastructure Layer
    ├─ Save to database
    ├─ Dispatch events
    └─ Update projections
    ↓
JSON Response
    └─ Return to client
```

---

## ✅ Complete Checklist for Adding Endpoints

1. **Define routes in `routes/api_v1.php`** (no prefix nesting)
2. **Create HTTP controller** (thin adapter layer)
3. **Create Command/Handler** (if write operation)
4. **Create QueryService** (if read operation)
5. **Add domain logic** (in aggregate or value objects)
6. **Add tests** (unit + integration)
7. **Add event listeners** (if side effects needed)
8. **Update documentation**
9. **Clear caches** before testing

---

## 🔧 Key Technologies & Patterns

| Aspect | Implementation |
|--------|----------------|
| **Architecture** | Hexagonal (Ports & Adapters) |
| **Domain** | Domain-Driven Design (DDD) |
| **Query/Write** | CQRS Light |
| **Framework** | Laravel 11 |
| **Frontend** | Vue 3 + Inertia 2.0 |
| **Multi-Tenancy** | Header-Based, Request-Scoped |
| **Validation** | Type-Safe Value Objects |
| **Events** | Domain Events + Event Listeners |
| **Testing** | PHPUnit + Feature Tests |

---

## 📁 File Structure

```
developer_guide/
└── api/
    ├── README.md              ← Start here
    ├── SUMMARY.md             ← Overview
    ├── ARCHITECTURE.md        ← Deep design
    ├── MULTI_TENANCY.md       ← Tenant isolation
    ├── BEST_PRACTICES.md      ← Guidelines
    ├── DEBUGGING_GUIDE.md     ← Issues & fixes
    └── (other guides)
```

---

## 🚀 For New Team Members

**Day 1:** Read [README.md](./api/README.md) + [SUMMARY.md](./api/SUMMARY.md)

**Day 2:** Read [ARCHITECTURE.md](./api/ARCHITECTURE.md) + [BEST_PRACTICES.md](./api/BEST_PRACTICES.md)

**Day 3:** Try adding a simple GET endpoint following [README.md](./api/README.md) Quick Start

**Day 4+:** Reference [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md) when you hit issues

---

## 🔍 Debugging Quick Links

**Route issues?** → [Issue #5: Route Prefix Duplication](./api/DEBUGGING_GUIDE.md#issue-5-route-prefix-duplication)

**HTML responses?** → [Issue #1: HTML Response Instead of JSON](./api/DEBUGGING_GUIDE.md#issue-1-html-response-instead-of-json-302-redirect)

**Auth failing?** → [Issue #4: Middleware Ordering](./api/DEBUGGING_GUIDE.md#issue-4-middleware-ordering---tenant-context-after-auth)

**CSRF errors?** → [Issue #3: CSRF Token Validation](./api/DEBUGGING_GUIDE.md#issue-3-csrf-token-validation-on-post-apiv1)

**Tenant issues?** → [MULTI_TENANCY.md Debugging Section](./api/MULTI_TENANCY.md#debugging-tenant-issues)

---

## 💡 Key Concepts Explained

### Hexagonal Architecture
- Controllers are adapters at system boundary
- Domain layer has zero framework dependencies
- Infrastructure layer handles persistence
- Clean separation of concerns

### CQRS Light
- Reads: Direct database queries via QueryService
- Writes: Full command/handler with domain logic
- Simpler than full CQRS, still powerful

### Domain-Driven Design
- Aggregates enforce business rules
- Value Objects provide type safety
- Domain events capture important occurrences
- Repository pattern for persistence

### Multi-Tenancy
- Every request declares tenant via header
- Tenant context set early in middleware
- All queries automatically scoped
- User verified to have org access

---

## 📝 Example: Adding a Member to Committee

This is what happens under the hood:

1. **Vue Component sends request:**
   ```javascript
   fetch('/api/v1/governance/committees/cmte-123/members', {
     method: 'POST',
     headers: { 'X-Organisation-ID': 'org-456' },
     body: JSON.stringify({ memberId: 'member-789', role: 'chair' })
   })
   ```

2. **Route matched in routes/api_v1.php:**
   ```php
   Route::post('/governance/committees/{committeeId}/members', 
       [CommitteeMemberController::class, 'store'])
   ```

3. **Middleware pipeline executes:**
   - web: Session starts
   - json.api: JSON response enforced
   - tenant.api: TenantContext::set('org-456')
   - auth: User verified authenticated
   - verified: Email verified

4. **Controller.store() runs:**
   - Gets tenant from TenantContext
   - Validates request
   - Creates AddCommitteeMemberCommand
   - Delegates to handler

5. **Handler.handle() runs:**
   - Loads Committee aggregate from repository
   - Calls domain method: addMember()
   - Saves aggregate to repository

6. **Committee.addMember() runs:**
   - Validates: member not already assigned
   - Validates: committee not full
   - Adds member to collection
   - Records MemberAssignedToCommittee event

7. **Repository.save() runs:**
   - Saves Committee to database
   - Dispatches events to listeners

8. **Event listeners process:**
   - CommitteeMemberProjectionListener updates projections
   - NotificationListener queues email
   - CacheListener invalidates cache

9. **Response sent:**
   ```json
   {
     "success": true,
     "message": "Member assignment command accepted"
   }
   ```

10. **Vue component:**
    - Shows success message
    - Refreshes member list
    - New member appears

---

## ✨ What Makes This Architecture Good

✅ **Testable** — Pure domain layer has zero framework dependencies  
✅ **Maintainable** — Clear separation of concerns  
✅ **Scalable** — Can add caching, queuing, events easily  
✅ **Debuggable** — Issues have clear root causes  
✅ **Extensible** — Adding features is straightforward  
✅ **Documentable** — Architecture is self-evident  

---

## 🆘 Need Help?

1. **Can't find something?** → Use Ctrl+F to search documentation
2. **Route not working?** → Check [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md) Issue #1 or #5
3. **Getting errors?** → Check "Common Error Messages & Fixes" section in [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md)
4. **Stuck on design?** → Read the relevant issue in [DEBUGGING_GUIDE.md](./api/DEBUGGING_GUIDE.md)

---

## 📞 Documentation Quality

All guides include:
- ✅ Real code examples (correct & incorrect)
- ✅ Detailed troubleshooting sections
- ✅ Complete debugging checklists
- ✅ Testing strategies
- ✅ Performance tips
- ✅ Security considerations

---

**Last Updated:** 2026-05-17  
**Status:** ✅ Complete & Production Ready  
**Version:** 1.0  

**Created with:** Clean Architecture, Domain-Driven Design, and meticulous documentation.

---

### 👉 **[Start with README.md](./api/README.md)**
