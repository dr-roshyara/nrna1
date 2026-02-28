# Translate-First Architecture Guide

## Overview

The **Translate-First Architecture** is a design pattern that separates business logic from presentation concerns, specifically around internationalization (i18n).

### Core Principle

> **Backend provides WHICH content should be shown. Frontend provides HOW to translate and render it.**

---

## Problem & Solution

### Without Translate-First (❌ WRONG)

```php
// PHP Service - Hard-coded text
class TrustSignalService {
    public function getSignals() {
        return [
            'message' => 'DSGVO-konform seit 2024',  // ← Hard-coded German!
            'tooltip' => 'Alle Daten werden DSGVO-konform verarbeitet'
        ];
    }
}
```

**Problems:**
- Backend tied to specific language (German)
- To support English/Nepali, must modify PHP code
- Text is scattered across multiple services
- Hard to maintain centralized translation
- Difficult to change translations without deployment

### With Translate-First (✅ CORRECT)

```php
// PHP Service - Translation keys
class TrustSignalService {
    public function getSignals() {
        return [
            'message_key' => 'trust_signals.compliance.message',
            'tooltip_key' => 'trust_signals.compliance.tooltip'
        ];
    }
}
```

```json
// resources/js/locales/pages/Dashboard/trust_signals/de.json
{
  "trust_signals": {
    "compliance": {
      "message": "DSGVO-konform seit 2024",
      "tooltip": "Alle Daten werden DSGVO-konform verarbeitet"
    }
  }
}
```

```vue
<!-- Vue Template - Translate at presentation layer -->
<div class="signal">
  <p>{{ $t(signal.message_key) }}</p>
  <span :title="$t(signal.tooltip_key)">ℹ️</span>
</div>
```

**Benefits:**
- Backend is language-agnostic
- All text in centralized JSON files
- Easy to add new languages (just add new JSON)
- Frontend responsible for translation
- Can update text without code changes

---

## Implementation Pattern

### Step 1: Backend Service Returns Translation Keys

```php
// app/Services/Dashboard/TrustSignalService.php

class TrustSignalService {
    public function getSignalsForUser(UserStateData $userState): array
    {
        return [
            [
                'id' => 'compliance',
                'type' => 'compliance',
                'icon' => '✓',
                // ✅ Return translation KEY, not translated TEXT
                'message_key' => 'trust_signals.compliance.message',
                'tooltip_key' => 'trust_signals.compliance.tooltip',
                'priority' => 1,
            ]
        ];
    }
}
```

**Key Points:**
- All text values have `_key` suffix
- Keys follow path notation: `section.subsection.field`
- Service focuses on WHAT to show, not HOW to translate

### Step 2: Create Translation Files

```
resources/js/locales/pages/Dashboard/trust_signals/
├── de.json     ← German translations
├── en.json     ← English translations
└── np.json     ← Nepali translations
```

```json
// de.json
{
  "trust_signals": {
    "compliance": {
      "message": "DSGVO-konform seit 2024",
      "tooltip": "Alle Daten werden DSGVO-konform verarbeitet"
    },
    "security": {
      "message": "Daten geschützt in Frankfurt",
      "tooltip": "Ihre Organisationsdaten werden sicher in Deutschland gehostet"
    }
  }
}
```

```json
// en.json - Same structure, English text
{
  "trust_signals": {
    "compliance": {
      "message": "GDPR compliant since 2024",
      "tooltip": "All data is processed in compliance with GDPR"
    },
    "security": {
      "message": "Data protected in Frankfurt",
      "tooltip": "Your organisation data is securely hosted in Germany"
    }
  }
}
```

**Important:**
- Same structure in all language files
- All languages must have ALL keys
- Grouping by domain (trust_signals) makes files manageable

### Step 3: Frontend Imports & Uses Translation Keys

```javascript
// resources/js/Pages/Dashboard/Welcome.vue

import trustSignalsDe from '@/locales/pages/Dashboard/trust_signals/de.json';
import trustSignalsEn from '@/locales/pages/Dashboard/trust_signals/en.json';
import trustSignalsNp from '@/locales/pages/Dashboard/trust_signals/np.json';

export default {
  data() {
    return {
      trustSignalsData: {
        de: trustSignalsDe,
        en: trustSignalsEn,
        np: trustSignalsNp,
      },
      signals: [], // Populated from backend
    };
  },

  computed: {
    currentLocale() {
      return this.$i18n.locale;
    },

    trustSignals() {
      return this.trustSignalsData[this.currentLocale];
    },
  },

  mounted() {
    // Get signals from backend (returns translation keys)
    this.signals = await fetch('/api/trust-signals').then(r => r.json());
  },
}
```

### Step 4: Render Using Translation Keys

```vue
<template>
  <div class="trust-signals">
    <div v-for="signal in signals" :key="signal.id" class="signal">
      <!-- Translate message using the key -->
      <p class="message">{{ $t(signal.message_key) }}</p>

      <!-- Translate tooltip using the key -->
      <div class="icon" :title="$t(signal.tooltip_key)">
        {{ signal.icon }}
      </div>
    </div>
  </div>
</template>
```

---

## Real Example: Trust Signal Service

### Before (Hard-Coded Text)

```php
class TrustSignalService {
    public function getComplianceSignal(): array {
        return [
            'type' => 'compliance',
            'icon' => '✓',
            'message' => 'DSGVO-konform seit 2024',      // ← Hard-coded!
            'tooltip' => 'Alle Daten werden DSGVO-konform verarbeitet',
        ];
    }
}
```

### After (Translation Keys)

```php
class TrustSignalService {
    public function getComplianceSignal(): array {
        return [
            'id' => 'compliance',
            'type' => 'compliance',
            'icon' => '✓',
            'message_key' => 'trust_signals.compliance.message',      // ← Key!
            'tooltip_key' => 'trust_signals.compliance.tooltip',      // ← Key!
        ];
    }
}
```

### Translation File (de.json)

```json
{
  "trust_signals": {
    "compliance": {
      "message": "DSGVO-konform seit 2024",
      "tooltip": "Alle Daten werden DSGVO-konform verarbeitet"
    },
    "security": {
      "message": "Daten geschützt in Frankfurt",
      "tooltip": "Ihre Organisationsdaten werden sicher in Deutschland gehostet"
    },
    "audit": {
      "message": "Audit-Trail verfügbar",
      "tooltip": "Vollständige Protokollierung aller Aktionen für Compliance"
    },
    "encryption": {
      "message": "Ihre Stimme verschlüsselt",
      "tooltip": "Jede Abstimmung ist vollständig verschlüsselt und anonym"
    }
  }
}
```

### Vue Template

```vue
<template>
  <div class="trust-signals">
    <div v-for="signal in userSignals" :key="signal.id">
      <span class="icon">{{ signal.icon }}</span>
      <p class="message">{{ $t(signal.message_key) }}</p>
      <span class="tooltip" :title="$t(signal.tooltip_key)">ℹ️</span>
    </div>
  </div>
</template>

<script>
import trustSignalsDe from '@/locales/pages/Dashboard/trust_signals/de.json';
import trustSignalsEn from '@/locales/pages/Dashboard/trust_signals/en.json';
import trustSignalsNp from '@/locales/pages/Dashboard/trust_signals/np.json';

export default {
  data() {
    return {
      trustSignalsData: { de: trustSignalsDe, en: trustSignalsEn, np: trustSignalsNp },
      userSignals: [],
    };
  },

  async mounted() {
    const response = await fetch('/api/dashboard/trust-signals');
    this.userSignals = await response.json();
  },
}
</script>
```

---

## Design Benefits

### 1. Separation of Concerns

| Layer | Responsibility |
|-------|-----------------|
| **Backend** | Determine WHICH signals to show (logic) |
| **Frontend** | TRANSLATE and RENDER signals (presentation) |
| **i18n** | STORE translations (data) |

### 2. Easy Language Addition

To add French:
1. Create `resources/js/locales/pages/Dashboard/trust_signals/fr.json`
2. Copy structure from German, translate text
3. Update component to import French JSON
4. No backend changes needed!

### 3. Simple Text Updates

To update a message:
1. Edit the JSON file
2. Clear webpack cache
3. Restart dev server
4. Done! No backend deployment needed

### 4. Backend Independence

Backend doesn't care about:
- Languages
- Translation frameworks
- Text content
- UI presentation

Just returns structured data with translation keys.

### 5. Testability

```php
// Easy to test: just check correct key is returned
public function test_compliance_signal_has_correct_key() {
    $service = new TrustSignalService();
    $signal = $service->getComplianceSignal();

    $this->assertEquals('trust_signals.compliance.message', $signal['message_key']);
    $this->assertEquals('trust_signals.compliance.tooltip', $signal['tooltip_key']);
}
```

---

## Best Practices

### ✅ DO:

1. **Return translation keys from backend**
   ```php
   return ['message_key' => 'trust_signals.compliance.message'];
   ```

2. **Use nested JSON structure**
   ```json
   {
     "domain": {
       "feature": {
         "field": "value"
       }
     }
   }
   ```

3. **Use consistent naming**
   - Backend keys: `message_key`, `tooltip_key`, `title_key`
   - Translation keys: `trust_signals.compliance.message`

4. **Create separate JSON files per domain**
   - `trust_signals/de.json`
   - `actions/de.json`
   - `forms/de.json`

5. **Add `id` field for v-for keys**
   ```php
   ['id' => 'compliance', 'message_key' => '...']
   ```

### ❌ DON'T:

1. **Don't return translated text from backend**
   ```php
   // WRONG
   return ['message' => $translator->get('trust_signals.compliance.message')];
   ```

2. **Don't use flat JSON structure**
   ```json
   // WRONG
   {
     "trust_signals_compliance_message": "text",
     "trust_signals_compliance_tooltip": "text"
   }
   ```

3. **Don't mix translation keys and text**
   ```php
   // WRONG
   return [
     'message_key' => 'trust_signals.compliance.message',
     'tooltip' => 'Hard-coded text'  // ← Inconsistent!
   ];
   ```

4. **Don't forget to update all languages**
   If you add a new key to `de.json`, add it to `en.json` and `np.json`

---

## Migration Guide

### Converting Existing Service

**Step 1: Identify all hard-coded text**
```php
// Before
'message' => 'DSGVO-konform seit 2024'
```

**Step 2: Create translation key**
```
trust_signals.compliance.message
```

**Step 3: Update service**
```php
// After
'message_key' => 'trust_signals.compliance.message'
```

**Step 4: Add to translation files**
```json
// de.json, en.json, np.json
{
  "trust_signals": {
    "compliance": {
      "message": "[translated text for this language]"
    }
  }
}
```

**Step 5: Update frontend to use keys**
```vue
<!-- Before -->
<p>{{ signal.message }}</p>

<!-- After -->
<p>{{ $t(signal.message_key) }}</p>
```

---

## Structure Examples

### Simple Structure

```json
{
  "trust_signals": {
    "compliance": {
      "message": "DSGVO-konform"
    }
  }
}
```

Key: `trust_signals.compliance.message`

### Complex Structure

```json
{
  "trust_signals": {
    "compliance": {
      "message": "DSGVO-konform",
      "tooltip": "All data processed...",
      "action": "View compliance"
    },
    "security": {
      "message": "Data protected",
      "tooltip": "Hosted in Germany",
      "action": "View hosting"
    }
  }
}
```

Keys:
- `trust_signals.compliance.message`
- `trust_signals.compliance.tooltip`
- `trust_signals.security.message`

---

## Testing Translate-First Services

### Unit Test: Service Returns Correct Keys

```php
public function test_getSignalsForUser_returns_translation_keys()
{
    $userState = new UserStateData(['roles' => ['admin']]);
    $service = new TrustSignalService();

    $signals = $service->getSignalsForUser($userState);

    // Should have translation keys, not text
    foreach ($signals as $signal) {
        $this->assertStringContainsString('_key', array_keys($signal));
        $this->assertArrayHasKey('message_key', $signal);
        $this->assertArrayHasKey('tooltip_key', $signal);

        // Keys should match expected pattern
        $this->assertStringStartsWith('trust_signals.', $signal['message_key']);
    }
}
```

### Integration Test: Frontend Can Translate

```javascript
// In Vue component test
it('renders trust signals with translated text', async () => {
    const signals = [
        { id: 'compliance', message_key: 'trust_signals.compliance.message' }
    ];

    const wrapper = mount(TrustSignals, {
        props: { signals },
        global: { plugins: [i18n] }
    });

    // Should translate the key
    expect(wrapper.text()).toContain('DSGVO-konform');
});
```

---

## Changelog

### Files Modified
- **app/Services/Dashboard/TrustSignalService.php** - Converted to return translation keys
- Created **resources/js/locales/pages/Dashboard/trust_signals/de.json** - German translations
- Created **resources/js/locales/pages/Dashboard/trust_signals/en.json** - English translations
- Created **resources/js/locales/pages/Dashboard/trust_signals/np.json** - Nepali translations

### Key Changes
- All `'message'` fields → `'message_key'`
- All `'tooltip'` fields → `'tooltip_key'`
- Added `'id'` fields for unique identification
- Service is now language-agnostic

---

## Related Documentation

- [Translation Rendering Guide](./v-for-rendering.md)
- [Quick Reference](./QUICK_REFERENCE.md)
- [Vue I18n Documentation](https://vue-i18n.intlify.dev/)
- [Public Digit Architecture](../architecture.md)

---

## FAQ

### Q: Why use translation keys instead of `$i18n.t()` in PHP?

**A:** PHP backend shouldn't know about i18n library specifics. Translation is a frontend concern. Backend returns data, frontend translates.

### Q: What if translation key doesn't exist?

**A:** vue-i18n returns the key itself (fallback), so you'll see `trust_signals.compliance.message` in the template. This helps identify missing translations during development.

### Q: Can I use backend translation?

**A:** You can, but it breaks separation of concerns. If you need backend-generated messages, return the raw content and handle translation in the frontend.

### Q: How do I lazy-load translation files?

**A:** Use dynamic imports in components:
```javascript
const trustSignals = await import('@/locales/pages/Dashboard/trust_signals/de.json');
```

### Q: What about missing languages?

**A:** Always create all three language files (de, en, np) at the same time. Use fallback to German if locale not found.

---

**Last Updated:** 2026-02-11
**Maintained by:** Public Digit Development Team
