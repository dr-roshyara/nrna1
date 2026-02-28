# Three-Role Dashboard System - Quick Start Guide

## ✅ Current Status: READY FOR TESTING

### What's Been Completed

1. **✅ Database Schema**
   - Migration: `database/migrations/2026_02_07_131712_create_role_system_tables.php`
   - Tables: `organisations`, `user_organization_roles`, `election_commission_members`

2. **✅ Backend Models & Controllers**
   - `organisation` model with relationships
   - `User` model extended with role methods
   - `CheckUserRole` middleware
   - Controllers: RoleSelection, AdminDashboard, CommissionDashboard, VoterDashboard

3. **✅ Routes** (registered in `routes/web.php`)
   - `/dashboard/roles` → RoleSelectionController (entry point after login)
   - `/dashboard/admin` → AdminDashboardController (requires admin role)
   - `/dashboard/commission` → CommissionDashboardController (requires commission role)
   - `/vote` → VoterDashboardController (requires voter role)

4. **✅ Frontend Components** (all translation-ready)
   - `RoleSelection/Index.vue` → Role card selection (UPDATED)
   - `Admin/Dashboard.vue` → organisation management
   - `Commission/Dashboard.vue` → Election monitoring
   - `Vote/Dashboard.vue` → Voter interface

5. **✅ Translation Files** (3 languages: English, German, Nepali)
   - `locales/pages/RoleSelection/` → en.json, de.json, np.json
   - `locales/pages/Admin/` → en.json, de.json, np.json
   - `locales/pages/Commission/` → en.json, de.json, np.json
   - `locales/pages/Vote/Dashboard/` → en.json, de.json, np.json

6. **✅ Configuration**
   - Login redirect: `RouteServiceProvider::HOME` → `/dashboard/roles`
   - i18n setup: All translations registered in `resources/js/i18n.js`

---

## 🚀 Getting Started: 3 Quick Steps

### Step 1: Build Frontend Assets
```bash
# Development build with hot reload
npm run dev

# OR production build
npm run build
```

### Step 2: Clear Laravel Caches
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

### Step 3: Start Development Server
```bash
# Terminal 1
php artisan serve

# Terminal 2 (keep running)
npm run dev
```

---

## 🧪 Testing the System

### Manual Test Checklist

**Login & Role Selection:**
- [ ] Visit `http://localhost:8000/login`
- [ ] Login with valid credentials
- [ ] ✅ Redirected to `/dashboard/roles` (role selection)
- [ ] See 1-3 role cards (depending on assigned roles)
- [ ] Click role card → redirected to correct dashboard

**Admin Dashboard:**
- [ ] If you have admin role, see organisations & elections stats
- [ ] All text in correct language (de/en/np)
- [ ] Navigate back to role selection → same dashboard

**Commission Dashboard:**
- [ ] If you have commission role, see elections & vote stats
- [ ] All text translated
- [ ] Navigate back to role selection

**Voter Dashboard:**
- [ ] If you have voter role, see pending votes
- [ ] Voting history table visible
- [ ] All text translated

**Translation Testing:**
- [ ] Language selector works (if implemented)
- [ ] All text displays correctly in German/English/Nepali
- [ ] No missing translation keys (check console)

### Feature Test with PHPUnit
```bash
# Run feature tests (when created)
php artisan test tests/Feature/RoleSystemTest.php
```

---

## 🔌 Architecture Summary

```
User Login Flow:
Login → /dashboard/roles (RoleSelection) 
        → Select role → /dashboard/admin
                     → /dashboard/commission
                     → /vote (voter dashboard)
```

**Key Components:**
- **Middleware**: `CheckUserRole` validates role access
- **Session**: `session('dashboard_role')` stores current selection
- **Database**: `user_organization_roles` table manages role assignments
- **Translation**: Vue i18n with German/English/Nepali support

---

## 📝 Database Seeding (Optional)

If you need test data with roles:

```php
// database/seeders/RoleSystemSeeder.php
php artisan db:seed --class=RoleSystemSeeder
```

---

## ⚙️ Configuration Files Modified

1. `app/Providers/RouteServiceProvider.php` → HOME = '/dashboard/roles'
2. `routes/web.php` → Added role-based dashboard routes
3. `resources/js/i18n.js` → Registered new translations
4. `app/Http/Kernel.php` → CheckUserRole middleware

---

## 🎯 What Each Role Can Do

| Role | Features | Access Path |
|------|----------|-------------|
| **Admin** | Manage organisations, create elections, system config | `/dashboard/admin` |
| **Commission** | Monitor elections, manage voters, view audit logs | `/dashboard/commission` |
| **Voter** | Cast votes, view election results, voting history | `/vote` |

---

## 🌐 Language Support

All components support 3 languages:
- 🇩🇪 **German** (de) - Default
- 🇺🇸 **English** (en) - International
- 🇳🇵 **Nepali** (np) - Local

Change locale in user preferences (when implemented).

---

## 🐛 Troubleshooting

### Issue: Still redirects to `/election` after login
**Fix**: Clear Laravel cache
```bash
php artisan config:clear
php artisan route:clear
```

### Issue: RoleSelection page blank
**Fix**: Run frontend build
```bash
npm run dev
```

### Issue: Translation keys showing (`pages.role-selection.roleSelection.welcome`)
**Fix**: Check i18n.js has all imports
```bash
grep -n "roleSelectionDe" resources/js/i18n.js
```

### Issue: User has no roles showing
**Fix**: Assign roles via database or seeder
```bash
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->organizationRoles()->attach(1, ['role' => 'admin']);
```

---

## 📚 Full Documentation

For detailed architecture information, see:
- `developer_guide/THREE_ROLE_DASHBOARD_SYSTEM.md` - Complete guide
- `developer_guide/ARCHITECTURE_DIAGRAM.md` - Visual diagrams

---

## ✨ Next Steps

1. ✅ Run `npm run dev` to build frontend
2. ✅ Start `php artisan serve`
3. ✅ Test login → role selection flow
4. ✅ Verify translations work in all languages
5. 🔄 Create RoleSystemSeeder for test data (optional)
6. 🔄 Write feature tests for the complete flow

---

**Status**: Development Ready
**Last Updated**: 2026-02-07
**Languages**: German (de), English (en), Nepali (np)
**All components translation-ready** ✅
