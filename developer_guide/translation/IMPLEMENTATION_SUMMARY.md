# Translate-First Architecture: Implementation Summary

## What Was Done

The TrustSignalService has been refactored to implement a **translate-first architecture** where the backend returns translation keys instead of hard-coded text.

---

## Files Modified

### 1. Backend Service
**File:** `app/Services/Dashboard/TrustSignalService.php`

**Changes:**
- Replaced all hard-coded German text with translation keys
- Added `id` field to each signal for unique identification
- Changed `'message'` → `'message_key'`
- Changed `'tooltip'` → `'tooltip_key'`
- Added detailed documentation explaining the pattern

**Example:**
```php
// Before
return [
    'message' => 'DSGVO-konform seit 2024',
    'tooltip' => 'Alle Daten werden DSGVO-konform verarbeitet'
];

// After
return [
    'id' => 'compliance',
    'message_key' => 'trust_signals.compliance.message',
    'tooltip_key' => 'trust_signals.compliance.tooltip'
];
```

**Methods Updated:**
- `getComplianceSignal()`
- `getRoleSpecificSignals()`
  - Admin signals (security, audit)
  - Voter signals (encryption, verification)
  - Commission signals (transparency)
- `getStateSpecificSignals()`
  - New user signals (support)
  - Setup in progress signals (data_protection)
  - Setup complete signals (ready)
- `getTrustBadge()`
  - Maximum/High/Medium/Low/Minimal levels

---

### 2. German Translation File
**File:** `resources/js/locales/pages/Dashboard/trust_signals/de.json`

**Content:**
```json
{
  "trust_signals": {
    "compliance": { "message": "...", "tooltip": "..." },
    "security": { "message": "...", "tooltip": "..." },
    "audit": { "message": "...", "tooltip": "..." },
    "encryption": { "message": "...", "tooltip": "..." },
    "verification": { "message": "...", "tooltip": "..." },
    "transparency": { "message": "...", "tooltip": "..." },
    "support": { "message": "...", "tooltip": "..." },
    "data_protection": { "message": "...", "tooltip": "..." },
    "ready": { "message": "...", "tooltip": "..." },
    "badge": {
      "maximum": { "message": "..." },
      "high": { "message": "..." },
      "medium": { "message": "..." },
      "low": { "message": "..." },
      "minimal": { "message": "..." }
    }
  }
}
```

---

### 3. English Translation File
**File:** `resources/js/locales/pages/Dashboard/trust_signals/en.json`

**Content:** Same structure as German, with English translations

---

### 4. Nepali Translation File
**File:** `resources/js/locales/pages/Dashboard/trust_signals/np.json`

**Content:** Same structure as German, with Nepali translations

---

### 5. Documentation
**File:** `developer_guide/translation/translate-first-architecture.md`

**Content:**
- Overview of translate-first architecture
- Problem & solution explanation
- Implementation pattern (4 steps)
- Real example with code
- Design benefits
- Best practices (DO's and DON'Ts)
- Migration guide
- Testing approach
- FAQ

---

## Translation Keys Structure

All signals use consistent naming pattern:

```
trust_signals.{signal_type}.{field}

Examples:
- trust_signals.compliance.message
- trust_signals.compliance.tooltip
- trust_signals.security.message
- trust_signals.badge.maximum.message
```

---

## How It Works: Frontend Integration

### 1. Component Imports Translation Files

```javascript
import trustSignalsDe from '@/locales/pages/Dashboard/trust_signals/de.json';
import trustSignalsEn from '@/locales/pages/Dashboard/trust_signals/en.json';
import trustSignalsNp from '@/locales/pages/Dashboard/trust_signals/np.json';
```

### 2. Component Receives Signals from Backend

```javascript
async mounted() {
  const response = await fetch('/api/dashboard/trust-signals');
  // Returns signals with translation keys:
  // { id: 'compliance', message_key: 'trust_signals.compliance.message', ... }
  this.signals = await response.json();
}
```

### 3. Component Translates Using Keys

```vue
<div v-for="signal in signals" :key="signal.id">
  <p>{{ $t(signal.message_key) }}</p>
  <span :title="$t(signal.tooltip_key)">ℹ️</span>
</div>
```

### 4. When User Switches Language

```javascript
// User switches language
this.$i18n.locale = 'en';

// All $t() calls automatically re-evaluate
// Component re-renders with English text
```

---

## Key Signals Implemented

### Always Shown
- **Compliance** - "DSGVO-konform seit 2024"

### Admin-Only Signals
- **Security** - "Daten geschützt in Frankfurt"
- **Audit** - "Audit-Trail verfügbar"

### Voter-Only Signals
- **Encryption** - "Ihre Stimme verschlüsselt"
- **Verification** - "Stimmabgabe verifizierbar"

### Commission-Only Signals
- **Transparency** - "Vollständige Transparenz"

### New User Signals
- **Support** - "Deutsches Support-Team"

### Setup In Progress Signals
- **Data Protection** - "Mitgliederdaten geschützt"

### Setup Complete Signals
- **Ready** - "Bereit für Wahlen"

### Trust Badges (5 Levels)
- **Maximum** - "Vollständig vertrauenswürdig"
- **High** - "Hohe Sicherheit"
- **Medium** - "DSGVO-konform"
- **Low** - "Grundsicherheit"
- **Minimal** - "Keine Bewertung"

---

## Benefits of This Approach

### For Backend Developers
✅ Service is language-agnostic
✅ Can focus on business logic without translation concerns
✅ Easy to test (just verify keys are returned)
✅ No dependency on i18n library

### For Frontend Developers
✅ All text in centralized, organized JSON files
✅ Easy to find and update translations
✅ Can add new language by just creating new JSON file
✅ Dynamic language switching works automatically
✅ Follows Vue best practices with computed properties

### For Project Management
✅ Easier to outsource translation work (just edit JSON)
✅ Can update text without backend deployment
✅ Consistent across all services
✅ Cleaner separation of concerns

---

## Adding New Languages

To add a new language (e.g., French):

1. **Create new translation file:**
   ```
   resources/js/locales/pages/Dashboard/trust_signals/fr.json
   ```

2. **Copy structure and translate:**
   ```json
   {
     "trust_signals": {
       "compliance": {
         "message": "Conforme au RGPD depuis 2024",
         "tooltip": "Toutes les données sont traitées conformément au RGPD"
       },
       // ... all other keys ...
     }
   }
   ```

3. **Import in frontend component:**
   ```javascript
   import trustSignalsFr from '@/locales/pages/Dashboard/trust_signals/fr.json';
   ```

4. **Add to data mapping:**
   ```javascript
   trustSignalsData: {
     de: trustSignalsDe,
     en: trustSignalsEn,
     np: trustSignalsNp,
     fr: trustSignalsFr  // ← New language
   }
   ```

**No backend changes needed!**

---

## Updating Text Without Code Changes

To update a message:

1. Edit the JSON file: `resources/js/locales/pages/Dashboard/trust_signals/de.json`
2. Change the text in all three language files
3. Clear webpack cache: `rm -rf public/js node_modules/.cache`
4. Restart dev server: `npm run dev`
5. Hard refresh browser: `Ctrl+Shift+R`

**No PHP code deployment needed!**

---

## Testing the Implementation

### Check That Service Returns Keys

```bash
# Call API endpoint
curl http://localhost:8000/api/dashboard/trust-signals

# Should see translation keys in response:
[
  {
    "id": "compliance",
    "message_key": "trust_signals.compliance.message",
    "tooltip_key": "trust_signals.compliance.tooltip"
  }
]
```

### Verify Frontend Translates Keys

```javascript
// In browser console
console.log(this.$t('trust_signals.compliance.message'))
// Should output: "DSGVO-konform seit 2024" (or translated version)
```

### Test Language Switching

```javascript
// In browser console
this.$i18n.locale = 'en'  // Switch to English
// Trust signals should now show English text

this.$i18n.locale = 'de'  // Back to German
// Should show German text again
```

---

## Migration Path for Other Services

The same pattern can be applied to other services:

1. **ActionService** - Action card messages and descriptions
2. **OnboardingService** - Step titles and descriptions
3. **ComplianceService** - Compliance requirement texts
4. **HelpService** - Help widget messages

Simply:
1. Replace hard-coded text with translation keys
2. Create translation files with the text
3. Frontend uses `$t()` to translate
4. Done!

---

## Directory Structure

```
app/Services/
└── Dashboard/
    ├── TrustSignalService.php              ✅ Refactored
    ├── ActionService.php                   ⏳ Next to refactor
    ├── ConfidenceCalculator.php
    ├── ContentBlockPipeline.php
    └── UserStateBuilder.php

resources/js/locales/pages/Dashboard/
├── welcome/
│   ├── de.json
│   ├── en.json
│   └── np.json
└── trust_signals/                          ✅ NEW
    ├── de.json
    ├── en.json
    └── np.json

developer_guide/translation/
├── README.md
├── QUICK_REFERENCE.md
├── v-for-rendering.md
└── translate-first-architecture.md         ✅ NEW
```

---

## Checkpoints

- [x] **TrustSignalService.php** - All methods return translation keys
- [x] **German translations** - All 9 signals + 5 badge levels
- [x] **English translations** - Complete with all keys
- [x] **Nepali translations** - Complete with all keys
- [x] **Documentation** - Full guide with examples
- [ ] **Frontend integration** - Update Dashboard Welcome component to use keys
- [ ] **API endpoint** - Create `/api/dashboard/trust-signals` endpoint
- [ ] **Testing** - Verify frontend correctly translates keys
- [ ] **Deployment** - Clear webpack cache, hard refresh browser

---

## Next Steps

### 1. Create API Endpoint

```php
// routes/api.php
Route::get('/dashboard/trust-signals', [DashboardController::class, 'getTrustSignals']);
```

```php
// app/Http/Controllers/DashboardController.php
public function getTrustSignals(TrustSignalService $service)
{
    $userState = auth()->user()->getUserState(); // Get current user state
    return $service->getSignalsForUser($userState);
}
```

### 2. Update Frontend Component

```vue
<!-- resources/js/Pages/Dashboard/Welcome.vue -->
<script>
import trustSignalsDe from '@/locales/pages/Dashboard/trust_signals/de.json';
import trustSignalsEn from '@/locales/pages/Dashboard/trust_signals/en.json';
import trustSignalsNp from '@/locales/pages/Dashboard/trust_signals/np.json';

export default {
  data() {
    return {
      trustSignalsData: { de: trustSignalsDe, en: trustSignalsEn, np: trustSignalsNp },
      signals: [],
    };
  },

  async mounted() {
    const response = await fetch('/api/dashboard/trust-signals');
    this.signals = await response.json();
  },
}
</script>

<template>
  <div class="trust-signals">
    <div v-for="signal in signals" :key="signal.id" class="signal">
      <span class="icon">{{ signal.icon }}</span>
      <p class="message">{{ $t(signal.message_key) }}</p>
      <span class="tooltip" :title="$t(signal.tooltip_key)">ℹ️</span>
    </div>
  </div>
</template>
```

### 3. Test All Languages

- [ ] Switch to German - verify text is German
- [ ] Switch to English - verify text is English
- [ ] Switch to Nepali - verify text is Nepali

### 4. Refactor Other Services

- ActionService (action cards)
- OnboardingService (onboarding steps)
- ComplianceService (compliance messages)

---

## Reference

- **Service:** `app/Services/Dashboard/TrustSignalService.php`
- **Translations:** `resources/js/locales/pages/Dashboard/trust_signals/`
- **Guide:** `developer_guide/translation/translate-first-architecture.md`
- **Quick Reference:** `developer_guide/translation/QUICK_REFERENCE.md`

---

**Implementation Date:** 2026-02-11
**Status:** ✅ Backend Refactored, ⏳ Frontend Integration Pending
**Maintained by:** Public Digit Development Team
