# Developer Guide

Documentation and best practices for the Public Digit platform.

---

## Contents

### 📚 TRANSLATION_FIRST_STRATEGY.md

**Comprehensive guide to implementing translations using the Translation-First Strategy.**

- How the i18n translation system works
- File structure and organization
- Complete step-by-step workflow (4 phases)
- Common translation patterns and best practices
- Real-world examples and migration checklist

### ✅ TRANSLATION_CHECKLIST.md

**Quick reference checklist for implementing translations.**

- Pre-development checklist
- Phase-by-phase implementation checklist
- Build and deployment checklist
- Issue quick fixes and common problems
- Team workflow and code review checklist

### 🔧 TRANSLATION_TROUBLESHOOTING.md

**Debug guide for when translations aren't working.**

- 8 major issues with root causes and fixes
- Build errors and import problems
- Language-specific issues
- Performance troubleshooting
- Debug commands reference

---

## Quick Start (5 Minutes)

### For a New Feature

1. Create locale files: `resources/js/locales/pages/YourPage/{en,de,np}.json`
2. Update `resources/js/i18n.js` with imports and registration
3. Add `$t()` calls to your component
4. Build: `npm run build && php artisan config:clear && php artisan cache:clear`
5. Test in browser with hard refresh (Ctrl+Shift+R)

### When Troubleshooting

1. Describe your symptom in TRANSLATION_TROUBLESHOOTING.md
2. Find the matching "Issue #" section
3. Follow the diagnosis steps
4. Apply the fix and rebuild

---

## Key Principles

1. **Translation First** - Create locale files BEFORE writing components
2. **Three Languages Always** - English (en), German (de), Nepali (np)
3. **No Hardcoded Text** - All user-facing text in JSON files
4. **Semantic Keys** - `voting_page.title`, not `label1`
5. **No Double Wrapping** - Locale files don't include "pages" wrapper
6. **Rebuild After Changes** - `npm run build` then clear caches

---

## Essential Commands

```bash
# Build frontend assets
npm run build

# Clear Laravel caches
php artisan config:clear && php artisan cache:clear

# Build and clear (combined)
npm run build && php artisan config:clear && php artisan cache:clear

# Validate JSON
node -e "console.log(JSON.parse(require('fs').readFileSync('resources/js/locales/pages/Election/en.json')))"
```

---

## Common Mistakes

| ❌ Mistake | ✅ Solution |
|-----------|-----------|
| Forgetting to build after changes | Run `npm run build` |
| Adding "pages" wrapper in locale file | Don't - i18n.js adds it automatically |
| Using single quotes in JSON | Use double quotes: `"key"` not `'key'` |
| Trailing comma in JSON | Remove comma before closing brace |
| Not clearing browser cache | Hard refresh: Ctrl+Shift+R |
| Hardcoding text in component | Use `{{ $t('pages.page.key') }}` |

---

For detailed information, see the individual guide files.
=======
# Public Digit Welcome Page - Developer Guide

## Overview

This guide documents the complete implementation of the **Public Digit Welcome Page** - a GDPR-compliant, multi-language, diaspora-focused dashboard for the digital democracy platform.

## 📁 Documentation Structure

1. **[ARCHITECTURE.md](./ARCHITECTURE.md)** - System design, patterns, and layers
2. **[SERVICES.md](./SERVICES.md)** - Backend service documentation
3. **[COMPONENTS.md](./COMPONENTS.md)** - Vue component reference
4. **[THREE_ROLE_SYSTEM.md](./THREE_ROLE_SYSTEM.md)** - Role-based access and rendering
5. **[GDPR_COMPLIANCE.md](./GDPR_COMPLIANCE.md)** - Data protection and privacy
6. **[TESTING.md](./TESTING.md)** - Testing strategies and examples
7. **[DEPLOYMENT.md](./DEPLOYMENT.md)** - Production deployment guide

## 🚀 Quick Start

### Access the Welcome Page
```
Route: /dashboard/welcome
URL: http://localhost:8000/dashboard/welcome
Middleware: auth (requires authenticated user)
```

### Test Different User Roles
```bash
# Find users with different roles
php artisan tinker
> $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
> $voter = User::whereHas('roles', fn($q) => $q->where('name', 'voter'))->first();
> $commission = User::whereHas('roles', fn($q) => $q->where('name', 'commission'))->first();
```

## 📊 System Architecture Overview

```
DashboardController (HTTP Request Handler)
    ↓
UserStateBuilder (Factory Pattern)
    ├─ RoleDetectionService
    ├─ ConfidenceCalculator
    ├─ OnboardingTracker
    └─ ActionService
    ↓
UserStateData (DTO - Immutable)
    ↓
TrustSignalService (GDPR Compliance)
    ↓
ContentBlockPipeline (Registry Pattern)
    ├─ RoleBasedActionBlock
    ├─ OrganizationStatusBlock
    └─ PendingActionsBlock
    ↓
Inertia Response
    ↓
Vue Welcome Page
    ├─ PersonalizedHeader
    ├─ QuickStartGrid / QuickStartCard
    ├─ OrganizationStatusBlock
    ├─ PendingActionsBlock
    └─ HelpWidget
```

## 🔐 Security & Compliance

- **GDPR Article 32 Compliant** - Data protection by design
- **DSGVO §26 Compliant** - Political opinion protection for diaspora voters
- **Pseudonymization** - User IDs are hashed before transmission
- **Minimal Data Transmission** - Only necessary data sent to frontend
- **Consent Verification** - GDPR consent checked before rendering
- **Data Hosting Transparency** - All data hosted in Germany

## 🌍 Multi-Language Support

Translations available in:
- **German (de)** - Primary market
- **English (en)** - International users
- **Nepali (np)** - Diaspora support

Translation files:
```
resources/js/locales/pages/Welcome/
├── de.json
├── en.json
└── np.json
```

## 👥 Three-Role System

The platform supports three distinct user roles with specific capabilities:

1. **Admin** - Organization management, election setup
2. **Commission** - Election oversight and monitoring
3. **Voter** - Participant in elections

Each role sees different:
- Action cards
- Trust signals
- Pending actions
- UI complexity levels

See [THREE_ROLE_SYSTEM.md](./THREE_ROLE_SYSTEM.md) for details.

## 🧪 Testing

### Quick Health Check
```bash
cd /path/to/nrna-eu
php artisan route:list | grep dashboard.welcome
```

### Test Three-Role System
```bash
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\$roleService = app(\App\Services\Dashboard\RoleDetectionService::class);
\$user = \App\Models\User::first();

echo 'Roles: ' . implode(', ', \$roleService->getDashboardRoles(\$user)->toArray()) . \"\n\";
echo 'Primary: ' . \$roleService->getPrimaryRole(\$user) . \"\n\";
"
```

## 📋 Development Workflow

### 1. Make Changes to Services
Edit files in `app/Services/Dashboard/`

### 2. Test Service Changes
```bash
php artisan tinker
> app(\App\Services\Dashboard\RoleDetectionService::class)
```

### 3. Update Vue Components
Edit files in `resources/js/Components/Dashboard/`

### 4. Update Translations
Edit files in `resources/js/locales/pages/Welcome/`

### 5. Verify Route
```bash
php artisan route:list | grep welcome
```

## 🐛 Troubleshooting

### Route Not Found
```
Error: 404 Not Found /dashboard/welcome

Solution: Verify route is registered
php artisan route:list | grep dashboard.welcome
```

### Missing Component
```
Error: Component not found

Solution: Check component paths in Welcome.vue
resources/js/Pages/Dashboard/Welcome.vue
```

### Translation Missing
```
Error: Translation key not found

Solution: Add key to de.json, en.json, np.json
resources/js/locales/pages/Welcome/
```

### GDPR Consent Redirect
```
Error: Redirected to /consent/required

Solution: User hasn't consented to GDPR
Set gdpr_consent_accepted_at in database
```

## 📚 Key Files Reference

### Backend
- `app/Http/Controllers/DashboardController.php` - Main controller
- `app/Services/Dashboard/UserStateBuilder.php` - Factory/Orchestrator
- `app/Services/Dashboard/RoleDetectionService.php` - Role detection
- `app/Services/Dashboard/TrustSignalService.php` - GDPR signals
- `app/Services/Dashboard/ContentBlockPipeline.php` - Block registry
- `app/DataTransferObjects/UserStateData.php` - Immutable DTO

### Frontend
- `resources/js/Pages/Dashboard/Welcome.vue` - Main page
- `resources/js/Components/Dashboard/PersonalizedHeader.vue` - Header
- `resources/js/Components/Dashboard/QuickStartGrid.vue` - Grid layout
- `resources/js/Components/Dashboard/QuickStartCard.vue` - Card component
- `resources/js/Components/Dashboard/HelpWidget.vue` - Help menu

### Translations
- `resources/js/locales/pages/Welcome/de.json` - German
- `resources/js/locales/pages/Welcome/en.json` - English
- `resources/js/locales/pages/Welcome/np.json` - Nepali

## 🔍 Code Patterns Used

### 1. **Factory Pattern**
UserStateBuilder orchestrates multiple services to build user state.

### 2. **Registry Pattern**
ContentBlockPipeline registers and processes content blocks dynamically.

### 3. **Immutable DTOs**
UserStateData encapsulates state without business logic.

### 4. **Service Layer**
Each service has single responsibility (role detection, confidence scoring, etc).

### 5. **Dependency Injection**
All services injected via Laravel container.

### 6. **Component-Based Architecture**
Vue components are isolated and reusable.

## 📈 Performance Considerations

### Database Queries
- Eager loading prevents N+1 queries
- ~3-5 queries per page load (optimized from 15)

### Caching
- Trust signals can be cached with Redis
- Role detection results can be cached per user

### Frontend
- Vue components are lazy-loaded
- CSS is scoped to prevent conflicts
- Responsive design uses CSS Grid

## 🚨 Important Security Notes

1. **Never expose user email directly** - Use safe_display_name
2. **Pseudonymize identifiers** - Use getPseudonymizedId()
3. **Validate GDPR consent** - Check before rendering sensitive data
4. **Scope database queries** - Always include tenant_id or user_id filters
5. **Sanitize translations** - All user-facing text goes through translation system

## 📞 Support & Questions

For questions about:
- **Architecture** → See ARCHITECTURE.md
- **Services** → See SERVICES.md
- **Components** → See COMPONENTS.md
- **GDPR** → See GDPR_COMPLIANCE.md
- **Testing** → See TESTING.md
- **Deployment** → See DEPLOYMENT.md

---

**Version:** 1.0
**Last Updated:** 2026-02-10
**Status:** Production Ready ✅
