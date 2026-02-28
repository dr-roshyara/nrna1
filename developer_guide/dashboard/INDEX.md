# Three-Role Dashboard System - Complete Documentation Index

## 📚 Documentation Files

### [README.md](./README.md) - **START HERE**
Quick overview of the system, key components, and quick reference tables.
- System overview
- Quick dashboard reference
- Key components list
- File structure
- Next steps for development

**Read this first** to understand what the system does.

---

### [ARCHITECTURE.md](./ARCHITECTURE.md) - System Design
Deep dive into the architecture, design decisions, and data flow.
- Architecture flowchart (mermaid diagram)
- Data flow: role detection process
- Role sources and priority
- Middleware architecture
- Database schema overview
- Controller responsibilities
- Caching strategy
- Security considerations

**Read this** to understand HOW the system works.

---

### [IMPLEMENTATION.md](./IMPLEMENTATION.md) - Code Locations & Details
Exact code locations, implementation details, and code patterns.
- Code locations quick reference
- Implementation details for each component
- Step-by-step: adding a user to a role
- API endpoints (to be implemented)
- Debugging tips
- Performance optimization strategies

**Read this** when implementing changes or debugging.

---

### [USER_JOURNEYS.md](./USER_JOURNEYS.md) - Real-World Scenarios
Complete user journey examples showing different user types.
- Journey 1: New customer onboarding (Anna)
- Journey 2: Multi-role user switching (Marcus)
- Journey 3: Legacy user migration (Otto)
- Journey 4: Admin batch operations (Sarah)
- Journey 5: Error handling scenarios
- Summary: All paths to dashboards

**Read this** to understand user flows and test scenarios.

---

### [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md) - Database Structure
Complete database schema documentation with examples.
- Table schemas (users, organizations, user_organization_roles, elections, election_commission_members)
- Column explanations
- Constraints and indexes
- Example data
- Query patterns
- Performance considerations
- Migration strategy
- Monitoring queries

**Read this** to understand the database structure.

---

### [EXTENDING.md](./EXTENDING.md) - Adding New Features
Step-by-step guide to extending the system with new dashboards/roles.
- Adding a new dashboard/role (example: Analyst)
- Step-by-step implementation
- Database design
- Controller creation
- Vue component creation
- Translation setup
- Route registration
- Testing the new role
- Advanced patterns

**Read this** when adding new roles or dashboards.

---

## 🎯 Quick Navigation by Task

### I want to...

#### **Understand the system**
→ Read: [README.md](./README.md) → [ARCHITECTURE.md](./ARCHITECTURE.md)

#### **Debug a problem**
→ Read: [IMPLEMENTATION.md](./IMPLEMENTATION.md) → [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md)

#### **Add a new role**
→ Read: [EXTENDING.md](./EXTENDING.md)

#### **Test user flows**
→ Read: [USER_JOURNEYS.md](./USER_JOURNEYS.md)

#### **Write database queries**
→ Read: [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md) → Query Patterns section

#### **Find code locations**
→ Read: [IMPLEMENTATION.md](./IMPLEMENTATION.md) → Code Locations section

#### **Understand role detection**
→ Read: [ARCHITECTURE.md](./ARCHITECTURE.md) → Data Flow section

#### **Performance tune**
→ Read: [IMPLEMENTATION.md](./IMPLEMENTATION.md) → Performance Optimization section

#### **Set up new environment**
→ Read: [README.md](./README.md) → [IMPLEMENTATION.md](./IMPLEMENTATION.md)

---

## 📋 Architecture Overview

```
┌─────────────────────────────────────────┐
│        User Logs In                     │
└────────┬────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│   LoginResponse (Post-Login Router)     │
│   - Check first-time user               │
│   - Get dashboard roles                 │
│   - Route based on role count           │
└────────┬────────────────────────────────┘
         │
    ┌────┴────┬────────────┬──────────┐
    │          │            │          │
    ▼          ▼            ▼          ▼
First-Time  Multi-Role   Single-Role  Legacy
User        User         User         User
    │          │            │          │
    ▼          ▼            ▼          ▼
Welcome    Role Sel.    Dashboard   Dashboard
Dashboard   Page       (Direct)     (Compat.)
```

---

## 🔑 Key Concepts

### Three Dashboard Roles

| Role | Purpose | Dashboard | Route |
|------|---------|-----------|-------|
| **Admin** | Manage organizations & elections | Admin Dashboard | `/dashboard/admin` |
| **Commission** | Monitor elections & votes | Commission Dashboard | `/dashboard/commission` |
| **Voter** | Participate in elections | Voter Dashboard | `/vote` |

### Role Sources

1. **New System** (Priority)
   - `user_organization_roles` table
   - `election_commission_members` table

2. **Legacy System** (Fallback)
   - Spatie roles (`admin`, `election_officer`)
   - User flags (`is_voter`, `is_committee_member`)

### User Types

| Type | Characteristics | Path |
|------|-----------------|------|
| New Customer | No orgs, no roles, account < 7 days | → Welcome Dashboard |
| Multi-Role User | 2+ distinct dashboard roles | → Role Selection |
| Single-Role User | Exactly 1 dashboard role | → Specific Dashboard |
| Legacy User | Has old roles/flags | → Backward compatible |

---

## 🛠️ Key Technologies

- **Backend:** Laravel 12, PHP 8+
- **Frontend:** Vue 3 Composition API (with Options API fallback)
- **Database:** MySQL/PostgreSQL
- **Internationalization:** Vue-i18n (EN/DE/NP)
- **UI Framework:** Tailwind CSS
- **State Management:** Inertia.js + Laravel sessions

---

## 📁 File organisation

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── LoginResponse.php
│   │   ├── RoleSelectionController.php
│   │   ├── AdminDashboardController.php
│   │   ├── CommissionDashboardController.php
│   │   ├── VoterDashboardController.php
│   │   └── WelcomeDashboardController.php
│   └── Middleware/
│       └── CheckUserRole.php
└── Models/
    ├── User.php (extended)
    └── organisation.php (new)

resources/js/
├── Pages/
│   ├── Welcome/Dashboard.vue
│   ├── RoleSelection/Index.vue
│   ├── Admin/Dashboard.vue
│   ├── Commission/Dashboard.vue
│   └── Vote/Dashboard.vue
└── locales/pages/
    ├── Welcome/Dashboard/ (EN/DE/NP)
    ├── RoleSelection/ (EN/DE/NP)
    ├── Admin/ (EN/DE/NP)
    ├── Commission/ (EN/DE/NP)
    └── Vote/Dashboard/ (EN/DE/NP)

routes/
└── web.php (dashboard routes)

database/migrations/
└── 2026_02_07_131712_create_role_system_tables.php

developerguide/dashboard/
├── README.md (this index points here)
├── ARCHITECTURE.md
├── IMPLEMENTATION.md
├── USER_JOURNEYS.md
├── DATABASE_SCHEMA.md
├── EXTENDING.md
└── INDEX.md (this file)
```

---

## 🚀 Getting Started for New Developers

### Day 1: Understand the System
1. Read [README.md](./README.md)
2. Read [ARCHITECTURE.md](./ARCHITECTURE.md)
3. Review the mermaid flowchart in ARCHITECTURE.md
4. Look at the file structure in README.md

### Day 2: Understand Code Implementation
1. Read [IMPLEMENTATION.md](./IMPLEMENTATION.md)
2. Review code locations
3. Look at LoginResponse.php implementation
4. Review a controller (e.g., RoleSelectionController.php)

### Day 3: Understand User Experience
1. Read [USER_JOURNEYS.md](./USER_JOURNEYS.md)
2. Go through Journey 1 (New Customer) completely
3. Go through Journey 2 (Multi-Role User) completely
4. Trace through the flowchart with these journeys

### Day 4: Understand Database
1. Read [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md)
2. Review table structures
3. Study the example data
4. Look at query patterns

### Day 5: Extend the System
1. Read [EXTENDING.md](./EXTENDING.md)
2. Follow the "Adding Analyst Role" example
3. Create a test role in your development environment
4. Test the complete flow

---

## 🐛 Common Issues & Solutions

### Issue: User gets stuck on `/dashboard/roles`
**Solution:** Check `User::getDashboardRoles()` - cache might be stale
```php
Cache::forget("user_{$userId}_dashboard_roles");
```

### Issue: First-time user not redirected to welcome
**Solution:** Check `LoginResponse::isFirstTimeUser()` - verify all conditions:
- Account < 7 days old
- No organisation roles
- No commission membership
- No legacy roles

### Issue: Role-based access not working
**Solution:** Verify `CheckUserRole` middleware is registered in `app/Http/Kernel.php`

### Issue: Translations not showing
**Solution:** Clear webpack cache and rebuild:
```bash
rm -rf node_modules/.cache && npm run dev
```

---

## 📞 Support & Questions

For questions about specific topics:

| Topic | File | Section |
|-------|------|---------|
| "How does the system decide which dashboard to show?" | ARCHITECTURE.md | Data Flow |
| "Where is role detection code?" | IMPLEMENTATION.md | Dashboard Role Detection |
| "What routes exist?" | IMPLEMENTATION.md | Routes |
| "How do I add a new role?" | EXTENDING.md | Complete guide |
| "What's in the database?" | DATABASE_SCHEMA.md | Table Schemas |
| "How do users switch roles?" | USER_JOURNEYS.md | Journey 2 |
| "Is the system backward compatible?" | USER_JOURNEYS.md | Journey 3 |
| "How do I debug a login issue?" | IMPLEMENTATION.md | Debugging Tips |

---

## ✅ Verification Checklist

To verify the system is working correctly:

- [ ] New users see welcome dashboard
- [ ] Multi-role users see role selection
- [ ] Single-role users go directly to dashboard
- [ ] Role switching works within session
- [ ] Legacy users still work
- [ ] Translations show in EN/DE/NP
- [ ] Middleware prevents unauthorized access
- [ ] Cache clears on role changes
- [ ] All links work in dashboards
- [ ] Responsive design works on mobile

---

## 📊 Documentation Statistics

- **Total Pages:** 6 markdown files
- **Total Words:** ~12,000+
- **Code Examples:** 100+
- **Diagrams:** 5+ (mermaid)
- **Tables:** 30+
- **User Journey Examples:** 5 complete scenarios

---

## 🔄 Documentation Maintenance

This documentation should be updated when:
- New roles are added
- Database schema changes
- Controllers are modified
- New frontend components are created
- API endpoints are added
- Security changes are made

---

**Last Updated:** February 7, 2026
**System Version:** Phase 2 (Welcome Dashboard + Enhanced Design)
**Status:** Production Ready ✅
