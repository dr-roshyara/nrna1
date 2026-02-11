# Translations Documentation

## Overview

The welcome page supports three languages:

| Language | Code | File | Primary Market |
|----------|------|------|-----------------|
| **German** | `de` | `de.json` | Germany (primary) |
| **English** | `en` | `en.json` | International |
| **Nepali** | `np` | `np.json` | Nepal diaspora |

All translations are Vue i18n keys in JSON files.

## File Locations

```
resources/js/locales/
└── pages/
    └── Welcome/
        ├── de.json
        ├── en.json
        └── np.json
```

## Language Selection

Language is selected via **user's timezone preference**:

```php
// In DashboardController
$language = match($user->timezone) {
    'Europe/Berlin' => 'de',
    'Asia/Kathmandu' => 'np',
    default => 'en'
};

// Set Vue i18n locale
session(['locale' => $language]);
```

Alternatively, users can manually select language in settings.

---

## Translation File Structure

Each translation file follows this hierarchy:

```json
{
  "page": {
    "title": "...",
    "description": "..."
  },
  "header": {
    "greeting": "...",
    "greeting_morning": "...",
    "greeting_afternoon": "..."
  },
  "cards": {
    "create_organization": {
      "title": "...",
      "description": "..."
    }
  }
}
```

---

## German Translation (de.json)

### File Structure

```json
{
  "dashboard": {
    "welcome_title": "Willkommen in der Public Digit Plattform",
    "welcome_subtitle": "Ihre Stimme zählt"
  },
  "header": {
    "greeting": "Hallo {name}!",
    "greeting_morning": "Guten Morgen, {name}!",
    "greeting_afternoon": "Guten Nachmittag, {name}!",
    "greeting_evening": "Guten Abend, {name}!",
    "last_login": "Letzte Anmeldung: {time}",
    "role_admin": "Administrator",
    "role_commission": "Kommissionsmitglied",
    "role_voter": "Wähler"
  },
  "trust_signals": {
    "compliance": "DSGVO-konform",
    "security": "Daten geschützt",
    "encryption": "Stimme verschlüsselt",
    "audit": "Audit-Trail vorhanden",
    "diaspora_notice": "Ihre Stimme ist anonym und sicher"
  },
  "cards": {
    "create_organization": {
      "title": "Organisation erstellen",
      "description": "Gründen Sie Ihre erste Wählerorganisation",
      "cta": "Organisation erstellen"
    },
    "add_members": {
      "title": "Mitglieder hinzufügen",
      "description": "Laden Sie Mitglieder zu Ihrer Organisation ein",
      "cta": "Mitglieder verwalten"
    },
    "create_election": {
      "title": "Wahl erstellen",
      "description": "Starten Sie eine neue Wahl für Ihre Organisation",
      "cta": "Wahl erstellen"
    },
    "cast_vote": {
      "title": "Abstimmen",
      "description": "Nehmen Sie an einer laufenden Wahl teil",
      "cta": "Abstimmen"
    },
    "manage_election": {
      "title": "Wahl verwalten",
      "description": "Überwachen Sie den Wahlfortschritt",
      "cta": "Wahl überwachen"
    }
  },
  "onboarding": {
    "step_1_title": "Neue Benutzer",
    "step_1_description": "Willkommen bei Public Digit",
    "step_2_title": "Organisation erstellt",
    "step_2_description": "Fügen Sie mindestens ein Mitglied hinzu",
    "step_3_title": "Mitglieder hinzugefügt",
    "step_3_description": "Erstellen Sie Ihre erste Wahl",
    "step_4_title": "Wahl erstellt",
    "step_4_description": "Mindestens 2 Wähler anmelden",
    "step_5_title": "Setup abgeschlossen",
    "step_5_description": "Sie sind bereit für die Produktion",
    "progress": "{current}/{total}"
  },
  "pending_actions": {
    "pending_votes": "Sie haben {count} offene Abstimmungen",
    "onboarding_step": "Nächster Onboarding-Schritt: {step}",
    "gdpr_consent": "GDPR-Zustimmung erforderlich",
    "email_verification": "E-Mail-Bestätigung erforderlich",
    "org_setup_incomplete": "Organisation ist nicht vollständig eingerichtet"
  },
  "help": {
    "title": "Hilfe benötigt?",
    "live_request": "Live-Anfrage senden",
    "contact_support": "Support kontaktieren",
    "documentation": "Dokumentation",
    "book_training": "Training buchen",
    "contact_support_email": "support@publicdigit.de",
    "contact_support_phone": "+49 (0) 30 123456"
  },
  "compliance": {
    "gdpr_compliant": "DSGVO-konform",
    "political_opinion_protected": "Politische Meinung geschützt",
    "data_hosted_germany": "Daten gehostet in Deutschland",
    "no_third_party_sharing": "Keine Weitergabe an Dritte",
    "encryption_end_to_end": "End-to-End-Verschlüsselung",
    "audit_trail": "Audit-Trail verfügbar",
    "transparent": "Transparenzbericht erhältlich"
  }
}
```

### Key Translation Patterns

**Greeting with Time of Day:**
```json
"greeting_morning": "Guten Morgen, {name}!",
"greeting_afternoon": "Guten Nachmittag, {name}!",
"greeting_evening": "Guten Abend, {name}!"
```

**Plural Forms:**
```json
"pending_votes": "Sie haben {count} offene Abstimmungen"
```

Used in Vue as:
```vue
{{ $t('pending_actions.pending_votes', { count: 5 }) }}
```

**Institutional Language:**
- Use formal "Sie" (not informal "du")
- Use gender-neutral language (e.g., "Mitglieder" not "Mitglieder/innen")

---

## English Translation (en.json)

### File Structure

```json
{
  "dashboard": {
    "welcome_title": "Welcome to Public Digit",
    "welcome_subtitle": "Your voice counts"
  },
  "header": {
    "greeting": "Hello {name}!",
    "greeting_morning": "Good morning, {name}!",
    "greeting_afternoon": "Good afternoon, {name}!",
    "greeting_evening": "Good evening, {name}!",
    "last_login": "Last login: {time}",
    "role_admin": "Administrator",
    "role_commission": "Committee Member",
    "role_voter": "Voter"
  },
  "trust_signals": {
    "compliance": "GDPR compliant",
    "security": "Data protected",
    "encryption": "Vote encrypted",
    "audit": "Audit trail available",
    "diaspora_notice": "Your vote is anonymous and secure"
  },
  "cards": {
    "create_organization": {
      "title": "Create Organization",
      "description": "Establish your first voter organization",
      "cta": "Create Organization"
    },
    "add_members": {
      "title": "Add Members",
      "description": "Invite members to your organization",
      "cta": "Manage Members"
    },
    "create_election": {
      "title": "Create Election",
      "description": "Start a new election for your organization",
      "cta": "Create Election"
    },
    "cast_vote": {
      "title": "Cast Vote",
      "description": "Participate in an ongoing election",
      "cta": "Vote Now"
    },
    "manage_election": {
      "title": "Manage Election",
      "description": "Monitor election progress",
      "cta": "Monitor Election"
    }
  },
  "onboarding": {
    "step_1_title": "New User",
    "step_1_description": "Welcome to Public Digit",
    "step_2_title": "Organization Created",
    "step_2_description": "Add at least one member",
    "step_3_title": "Members Added",
    "step_3_description": "Create your first election",
    "step_4_title": "Election Created",
    "step_4_description": "At least 2 voters registered",
    "step_5_title": "Setup Complete",
    "step_5_description": "You are ready for production",
    "progress": "{current}/{total}"
  },
  "pending_actions": {
    "pending_votes": "You have {count} pending votes",
    "onboarding_step": "Next onboarding step: {step}",
    "gdpr_consent": "GDPR consent required",
    "email_verification": "Email verification required",
    "org_setup_incomplete": "Organization setup incomplete"
  },
  "help": {
    "title": "Need Help?",
    "live_request": "Send Live Request",
    "contact_support": "Contact Support",
    "documentation": "Documentation",
    "book_training": "Book Training",
    "contact_support_email": "support@publicdigit.eu",
    "contact_support_phone": "+49 (0) 30 123456"
  },
  "compliance": {
    "gdpr_compliant": "GDPR compliant",
    "political_opinion_protected": "Political opinion protected",
    "data_hosted_germany": "Data hosted in Germany",
    "no_third_party_sharing": "No third-party sharing",
    "encryption_end_to_end": "End-to-end encryption",
    "audit_trail": "Audit trail available",
    "transparent": "Transparency report available"
  }
}
```

---

## Nepali Translation (np.json)

### File Structure

```json
{
  "dashboard": {
    "welcome_title": "सार्वजनिक अंकमा स्वागत छ",
    "welcome_subtitle": "तपाईंको मत महत्वपूर्ण छ"
  },
  "header": {
    "greeting": "नमस्कार {name}!",
    "greeting_morning": "शुभ प्रभात {name}!",
    "greeting_afternoon": "शुभ दोपहर {name}!",
    "greeting_evening": "शुभ संध्या {name}!",
    "last_login": "अन्तिम लगइन: {time}",
    "role_admin": "व्यवस्थापक",
    "role_commission": "समिति सदस्य",
    "role_voter": "मतदाता"
  },
  "trust_signals": {
    "compliance": "डेटा संरक्षण अनुपालनशील",
    "security": "डेटा सुरक्षित",
    "encryption": "मत एनक्रिप्ट गरिएको",
    "audit": "अडिट ट्रेल उपलब्ध",
    "diaspora_notice": "तपाईंको मत अनाम र सुरक्षित छ"
  },
  "cards": {
    "create_organization": {
      "title": "संस्था बनाउनुहोस्",
      "description": "आफ्नो पहिलो मतदान संस्था स्थापना गर्नुहोस्",
      "cta": "संस्था बनाउनुहोस्"
    },
    "add_members": {
      "title": "सदस्य थप्नुहोस्",
      "description": "आफ्नो संस्थामा सदस्यहरूलाई आमन्त्रण गर्नुहोस्",
      "cta": "सदस्य व्यवस्थापन गर्नुहोस्"
    },
    "create_election": {
      "title": "निर्वाचन बनाउनुहोस्",
      "description": "आफ्नो संस्थाको लागि नयाँ निर्वाचन सुरु गर्नुहोस्",
      "cta": "निर्वाचन बनाउनुहोस्"
    },
    "cast_vote": {
      "title": "मत दिनुहोस्",
      "description": "चलिरहेको निर्वाचनमा भाग लिनुहोस्",
      "cta": "अब मत दिनुहोस्"
    },
    "manage_election": {
      "title": "निर्वाचन व्यवस्थापन गर्नुहोस्",
      "description": "निर्वाचन प्रगति अनुगमन गर्नुहोस्",
      "cta": "निर्वाचन अनुगमन गर्नुहोस्"
    }
  },
  "onboarding": {
    "step_1_title": "नयाँ प्रयोगकर्ता",
    "step_1_description": "सार्वजनिक अंकमा स्वागत छ",
    "step_2_title": "संस्था बनिसकेको",
    "step_2_description": "कम्तीमा एक सदस्य थप्नुहोस्",
    "step_3_title": "सदस्य थपिसकेको",
    "step_3_description": "आफ्नो पहिलो निर्वाचन बनाउनुहोस्",
    "step_4_title": "निर्वाचन बनिसकेको",
    "step_4_description": "कम्तीमा २ मतदाता दर्ता गर्नुहोस्",
    "step_5_title": "सेटअप पूरा भयो",
    "step_5_description": "तपाई उत्पादनको लागि तयार हुनुहुन्छ",
    "progress": "{current}/{total}"
  },
  "pending_actions": {
    "pending_votes": "तपाईंसँग {count} पेन्डिङ मतहरू छन्",
    "onboarding_step": "अगिल्लो अनबोर्डिङ चरण: {step}",
    "gdpr_consent": "GDPR अनुमति आवश्यक",
    "email_verification": "ईमेल सत्यापन आवश्यक",
    "org_setup_incomplete": "संस्था सेटअप अधूरो"
  },
  "help": {
    "title": "सहायता चाहिन्छ?",
    "live_request": "लाइभ अनुरोध पठाउनुहोस्",
    "contact_support": "समर्थन सम्पर्क गर्नुहोस्",
    "documentation": "दस्तावेजीकरण",
    "book_training": "प्रशिक्षण बुक गर्नुहोस्",
    "contact_support_email": "support@publicdigit.eu",
    "contact_support_phone": "+49 (0) 30 123456"
  },
  "compliance": {
    "gdpr_compliant": "डेटा संरक्षण अनुपालनशील",
    "political_opinion_protected": "राजनीतिक मत संरक्षित",
    "data_hosted_germany": "डेटा जर्मनीमा होस्ट गरिएको",
    "no_third_party_sharing": "तेस्रो पक्षको साथ कुनै साझेदारी नहीं",
    "encryption_end_to_end": "अन्त-अन्त एनक्रिप्शन",
    "audit_trail": "अडिट ट्रेल उपलब्ध",
    "transparent": "पारदर्शिता रिपोर्ट उपलब्ध"
  }
}
```

---

## Using Translations in Vue Components

### Basic Usage

```vue
<template>
  <h1>{{ $t('dashboard.welcome_title') }}</h1>
</template>
```

**Output (German):** `Willkommen in der Public Digit Plattform`

### With Parameters

```vue
<template>
  <p>{{ $t('header.greeting', { name: user.display_name }) }}</p>
</template>
```

**Output (German):** `Hallo Max Müller!`

### Plural Forms

```vue
<template>
  <p>{{ $t('pending_actions.pending_votes', { count: 3 }) }}</p>
</template>
```

**Output (German):** `Sie haben 3 offene Abstimmungen`

### Time-Based Greeting

```vue
<script setup>
const getGreeting = () => {
  const hour = new Date().getHours();

  if (hour < 12) return $t('header.greeting_morning', { name: user.display_name });
  if (hour < 17) return $t('header.greeting_afternoon', { name: user.display_name });
  return $t('header.greeting_evening', { name: user.display_name });
};
</script>

<template>
  <h2>{{ getGreeting() }}</h2>
</template>
```

---

## Adding a New Language

### Step 1: Create Translation File

Create new file: `resources/js/locales/pages/Welcome/{code}.json`

Example for Spanish (es):
```json
{
  "dashboard": {
    "welcome_title": "Bienvenido a Público Dígito",
    "welcome_subtitle": "Tu voz cuenta"
  },
  // ... rest of translations
}
```

### Step 2: Update Language Selector

In `DashboardController`:
```php
$language = match($user->timezone) {
    'Europe/Berlin' => 'de',
    'Asia/Kathmandu' => 'np',
    'Europe/Madrid' => 'es',  // Add this line
    default => 'en'
};
```

### Step 3: Register in i18n Config

In `resources/js/config/i18n.js`:
```javascript
const messages = {
  de: require('@/locales/pages/Welcome/de.json'),
  en: require('@/locales/pages/Welcome/en.json'),
  np: require('@/locales/pages/Welcome/np.json'),
  es: require('@/locales/pages/Welcome/es.json'),  // Add this line
};
```

### Step 4: Test

```bash
# Set user timezone to test language
php artisan tinker
> User::find(1)->update(['timezone' => 'Europe/Madrid'])

# Visit dashboard
# Should now display Spanish text
```

---

## Translation Guidelines

### Key Naming

Use hierarchical, descriptive names:

```
✅ GOOD: pending_actions.pending_votes
❌ BAD: pending_votes
❌ BAD: pend_votes_msg
```

### Content Guidelines

**German:**
- Use formal "Sie" (not informal "du")
- Use institutional language
- Keep sentences concise
- Use active voice

**English:**
- Simple, direct language
- American English spelling (color, not colour)
- Professional tone

**Nepali:**
- Use formal Nepali (Nepali-official formal register)
- Diaspora-appropriate terminology
- Gender-neutral forms

### Parameter Naming

Keep parameter names consistent:

```
{name}      → User's display name
{count}     → Numeric count
{step}      → Step number or name
{time}      → Time/date string
{email}     → Email address
```

---

## Extracting Strings for Translation

### Find All Untranslated Strings

```bash
# Search for hardcoded strings (should use i18n instead)
grep -r "Welcome" resources/js/Components/Dashboard/ --include="*.vue"
```

### Missing Translation Keys

If you encounter missing keys in Vue:

```vue
<!-- In template -->
{{ $t('header.missing_key') }}

<!-- Will render: header.missing_key (highlighted in red in dev)
```

### Creating Translation Report

Generate list of all used translation keys:

```javascript
// In browser console
const keys = Object.keys(localStorage)
  .filter(k => k.startsWith('i18n:'))
  .map(k => k.replace('i18n:', ''));
console.table(keys);
```

---

## Testing Translations

### Unit Test Example

```php
public function testGermanGreeting()
{
    $user = User::factory()->create([
        'display_name' => 'Max Müller',
        'timezone' => 'Europe/Berlin'
    ]);

    $response = $this->actingAs($user)->get('/dashboard/welcome');

    // Should include German locale
    $response->assertSee('Willkommen');
}

public function testNepaliGreeting()
{
    $user = User::factory()->create([
        'display_name' => 'राम शर्मा',
        'timezone' => 'Asia/Kathmandu'
    ]);

    $response = $this->actingAs($user)->get('/dashboard/welcome');

    // Should include Nepali locale
    $response->assertSee('स्वागत');
}
```

### Manual Testing Checklist

- [ ] German greeting displays correctly
- [ ] English greeting displays correctly
- [ ] Nepali greeting displays correctly
- [ ] Parameters interpolate correctly (names, counts)
- [ ] Time-based greetings work (morning/afternoon/evening)
- [ ] No missing translation keys in console
- [ ] All card titles/descriptions translate
- [ ] Onboarding steps translate
- [ ] Trust signals translate
- [ ] Help menu translates
- [ ] Mobile view translates correctly

---

## Common Translation Issues

### Issue 1: Missing Translation Key

**Problem:** Page shows `dashboard.welcome_title` instead of actual text

**Solution:** Add key to all three translation files

```json
{
  "dashboard": {
    "welcome_title": "..."
  }
}
```

### Issue 2: Parameter Not Interpolating

**Problem:** Greeting shows "Hello {name}!" instead of "Hello Max!"

**Solution:** Ensure parameter passed to $t():

```vue
<!-- WRONG -->
<p>{{ $t('header.greeting') }}</p>

<!-- CORRECT -->
<p>{{ $t('header.greeting', { name: user.display_name }) }}</p>
```

### Issue 3: Nepali Characters Not Displaying

**Problem:** Nepali text shows as boxes or question marks

**Solution:** Ensure UTF-8 encoding in JSON file:

```json
{
  "header": {
    "greeting": "नमस्कार {name}!"
  }
}
```

Verify file encoding: File should be saved as **UTF-8 without BOM**

### Issue 4: Right-to-Left Languages

**Problem:** If adding Arabic/Urdu, text doesn't align correctly

**Solution:** Add dir attribute to html:

```vue
<div :dir="locale === 'ar' ? 'rtl' : 'ltr'">
  {{ $t('...') }}
</div>
```

---

## Maintenance

### Translation Audit (Monthly)

1. Check for outdated keys in old branches
2. Verify all three languages have same keys
3. Review for typos and consistency
4. Update if legal/compliance language changes

### Backup Strategy

Store translations in version control:

```bash
git add resources/js/locales/pages/Welcome/
git commit -m "Update translations"
```

---

## Summary

Translation management ensures:

1. **Multi-language support** - German, English, Nepali
2. **Timezone-based selection** - User's preferred language
3. **Consistency** - All UI uses i18n keys
4. **Maintainability** - Centralized translation files
5. **Accessibility** - Clear, native language for diaspora

Never hardcode strings — always use `$t()` for internationalization.
