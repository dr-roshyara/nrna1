# Translation-First Strategy Developer Guide

## Overview

This guide explains how to implement translations using a **Translation-First Strategy** in the Public Digit platform. Translation-first means creating locale files BEFORE building components, ensuring your UI is multi-language ready from the start.

**Core Principle:** All user-facing text should be extracted into locale files first, then referenced in components.

---

## Table of Contents

1. [Architecture](#architecture)
2. [File Structure](#file-structure)
3. [Step-by-Step Workflow](#step-by-step-workflow)
4. [Common Patterns](#common-patterns)
5. [Best Practices](#best-practices)
6. [Troubleshooting](#troubleshooting)
7. [Examples](#examples)

---

## Architecture

### Translation System Stack

```
Vue Components ($t())
        ↓
vue-i18n Plugin (i18n.js)
        ↓
Locale Files (JSON)
    ├── Core Translations (de.json, en.json, np.json)
    └── Page-Specific Translations (Election/, Auth/, etc.)
```

### Current Setup

- **Plugin**: `resources/js/i18n.js`
- **Core Locales**: `resources/js/locales/{language}.json`
- **Page Locales**: `resources/js/locales/pages/{PageName}/{language}.json`
- **Supported Languages**: English (en), German (de), Nepali (np)

### How It Works

```javascript
// In i18n.js, page translations are merged like this:
const messages = {
  de: {
    ...de,  // Core translations
    pages: {
      election: electionDe,     // Page-specific translations
      auth: authDe,
      welcome: welcomeDe,
      // ...
    }
  },
  // ... en and np follow the same pattern
}
```

Components access translations:
```vue
{{ $t('pages.election.voting_page.title') }}
```

---

## File Structure

### Directory Layout

```
resources/js/locales/
├── de.json                          # Core German translations
├── en.json                          # Core English translations
├── np.json                          # Core Nepali translations
└── pages/
    ├── Election/
    │   ├── de.json                  # German (no "pages" wrapper)
    │   ├── en.json                  # English (no "pages" wrapper)
    │   └── np.json                  # Nepali (no "pages" wrapper)
    ├── Auth/
    │   ├── de.json
    │   ├── en.json
    │   └── np.json
    ├── Welcome/
    │   ├── de.json
    │   ├── en.json
    │   └── np.json
    └── ... (other pages)
```

### File Naming Convention

✅ **DO:**
- Use PascalCase for folder names: `Election/`, `Auth/`, `Welcome/`
- Use lowercase for filenames: `de.json`, `en.json`, `np.json`
- Use kebab-case for translation keys: `voting_page`, `demo_badge`, `election_card`

❌ **DON'T:**
- Mix naming conventions
- Add `pages/election` wrapper in page-specific files (handled by i18n.js)
- Use camelCase for keys that contain multiple words

---

## Step-by-Step Workflow

### Phase 1: Create Locale Files (Translation First)

#### Step 1.1: Identify Content Sections

Before writing code, list all text your component will display:

```
ElectionPage Component
├── Header: "Active Election - Voting Page"
├── Status Badge: "Voting is currently active"
├── Time Remaining: "Time remaining: X minutes"
├── Election Details Section
│   ├── Label: "Election Details"
│   ├── Type: "Election Type"
│   ├── Period: "Voting Period"
│   └── Description: "Description"
├── Voting Instructions
│   ├── Step 1: "Enter your verification code"
│   ├── Step 2: "Read and agree to terms"
│   └── ... (5 steps)
└── Buttons: "Start Voting Now", "Contact Support"
```

#### Step 1.2: Create Locale File Structure

Create `resources/js/locales/pages/Election/en.json`:

```json
{
  "title": "Active Election - Voting Page",
  "status_active": "Voting is currently active",
  "time_remaining": "Time remaining",
  "details": "Election Details",
  "election_type": "Election Type",
  "voting_period": "Voting Period",
  "description": "Description",
  "voting_instructions": "Voting Instructions",
  "step1": "Enter your verification code",
  "step2": "Read and agree to the election terms",
  "step3": "Select your candidates",
  "step4": "Review your selections",
  "step5": "Submit your vote",
  "start_voting": "Start Voting Now",
  "need_help": "Need Help?",
  "help_desc": "If you have questions, contact support.",
  "contact_support": "Contact Support",
  "important_rules": "Important Rules",
  "rule_one_vote": "You can vote once only",
  "rule_no_change": "Votes cannot be changed after submission"
}
```

#### Step 1.3: Translate to Other Languages

Create German version (`de.json`):
```json
{
  "title": "Aktive Wahl - Abstimmungsseite",
  "status_active": "Die Abstimmung läuft gerade",
  "time_remaining": "Verbleibende Zeit",
  "details": "Wahldetails",
  "election_type": "Wahltyp",
  "voting_period": "Abstimmungszeitraum",
  "description": "Beschreibung",
  "voting_instructions": "Abstimmungsanleitung",
  "step1": "Geben Sie Ihren Verifizierungscode ein",
  "step2": "Lesen Sie die Wahlbedingungen und stimmen Sie ihnen zu",
  "step3": "Wählen Sie Ihre Kandidaten",
  "step4": "Überprüfen Sie Ihre Auswahl",
  "step5": "Reichen Sie Ihre Stimme ein",
  "start_voting": "Jetzt abstimmen",
  "need_help": "Benötigen Sie Hilfe?",
  "help_desc": "Wenn Sie Fragen haben, wenden Sie sich an den Support.",
  "contact_support": "Kontakt zum Support",
  "important_rules": "Wichtige Regeln",
  "rule_one_vote": "Sie können nur einmal abstimmen",
  "rule_no_change": "Stimmen können nach der Einreichung nicht geändert werden"
}
```

Create Nepali version (`np.json`):
```json
{
  "title": "सक्रिय निर्वाचन - मतदान पृष्ठ",
  "status_active": "मतदान वर्तमानमा सक्रिय छ",
  "time_remaining": "बाकी समय",
  "details": "निर्वाचन विवरण",
  "election_type": "निर्वाचन प्रकार",
  "voting_period": "मतदान अवधि",
  "description": "विवरण",
  "voting_instructions": "मतदान निर्देशन",
  "step1": "आफ्नो सत्यापन कोड दर्ज गर्नुहोस्",
  "step2": "निर्वाचन शर्तहरू पढ्नुहोस् र सहमत हुनुहोस्",
  "step3": "आफ्नो उम्मेदवारहरू चुनुहोस्",
  "step4": "आफ्नो चयनहरू समीक्षा गर्नुहोस्",
  "step5": "आफ्नो मत जमा गर्नुहोस्",
  "start_voting": "अहिले मतदान सुरु गर्नुहोस्",
  "need_help": "सहायता चाहिएको छ?",
  "help_desc": "यदि तपाईलाई प्रश्न छ भने समर्थन संपर्क गर्नुहोस्।",
  "contact_support": "समर्थन संपर्क गर्नुहोस्",
  "important_rules": "महत्वपूर्ण नियमहरू",
  "rule_one_vote": "तपाई केवल एक पटक मतदान गर्न सक्नुहुन्छ",
  "rule_no_change": "जमा गरेपछि मतहरू परिवर्तन गर्न सकिँदैन"
}
```

### Phase 2: Register Translations in i18n.js

#### Step 2.1: Import Translations

Edit `resources/js/i18n.js`:

```javascript
// At the top, add imports for your page translations
import electionDe from './locales/pages/Election/de.json';
import electionEn from './locales/pages/Election/en.json';
import electionNp from './locales/pages/Election/np.json';
```

#### Step 2.2: Add to Messages Object

In the `messages` object (around line 50), add your translations to each language:

```javascript
const messages = {
  de: {
    ...de,  // Core translations
    pages: {
      'voting-start': votingStartDe,
      'voting-election': votingElectionDe,
      pricing: pricingDe,
      welcome: welcomeDe,
      auth: authDe,
      election: electionDe,  // ← ADD THIS
    },
  },
  en: {
    ...en,
    pages: {
      'voting-start': votingStartEn,
      'voting-election': votingElectionEn,
      pricing: pricingEn,
      welcome: welcomeEn,
      auth: authEn,
      election: electionEn,  // ← ADD THIS
    },
  },
  np: {
    ...np,
    pages: {
      'voting-start': votingStartNp,
      'voting-election': votingElectionNp,
      pricing: pricingNp,
      welcome: welcomeNp,
      auth: authNp,
      election: electionNp,  // ← ADD THIS
    },
  },
};
```

**IMPORTANT:** Do NOT wrap page translations in `pages` and page name again in the locale files. The i18n.js already does this!

### Phase 3: Use Translations in Components

#### Step 3.1: Reference Translations in Templates

In your Vue component (`ElectionPage.vue`):

```vue
<template>
  <div class="election-page">
    <h1>{{ $t('pages.election.title') }}</h1>

    <div class="status">
      ✅ {{ $t('pages.election.status_active') }}
    </div>

    <div class="time-remaining">
      {{ $t('pages.election.time_remaining') }}: X minutes
    </div>

    <section class="details">
      <h3>{{ $t('pages.election.details') }}</h3>
      <p><strong>{{ $t('pages.election.election_type') }}:</strong> Real Election</p>
      <p><strong>{{ $t('pages.election.voting_period') }}:</strong> Jan 10 - Jan 12</p>
      <p>{{ $t('pages.election.description') }}</p>
    </section>

    <section class="instructions">
      <h3>{{ $t('pages.election.voting_instructions') }}</h3>
      <ol>
        <li>{{ $t('pages.election.step1') }}</li>
        <li>{{ $t('pages.election.step2') }}</li>
        <li>{{ $t('pages.election.step3') }}</li>
        <li>{{ $t('pages.election.step4') }}</li>
        <li>{{ $t('pages.election.step5') }}</li>
      </ol>
    </section>

    <section class="rules">
      <h3>{{ $t('pages.election.important_rules') }}</h3>
      <ul>
        <li>{{ $t('pages.election.rule_one_vote') }}</li>
        <li>{{ $t('pages.election.rule_no_change') }}</li>
      </ul>
    </section>

    <button class="btn-primary">
      {{ $t('pages.election.start_voting') }}
    </button>

    <div class="help-section">
      <h4>{{ $t('pages.election.need_help') }}</h4>
      <p>{{ $t('pages.election.help_desc') }}</p>
      <a href="/support">{{ $t('pages.election.contact_support') }}</a>
    </div>
  </div>
</template>
```

#### Step 3.2: Use Translations in Script

For dynamic text in JavaScript:

```javascript
export default {
  data() {
    return {
      stepLabels: [
        this.$t('pages.election.step1'),
        this.$t('pages.election.step2'),
        this.$t('pages.election.step3'),
        this.$t('pages.election.step4'),
        this.$t('pages.election.step5'),
      ]
    }
  },
  computed: {
    electionDetails() {
      return {
        type: this.$t('pages.election.election_type'),
        period: this.$t('pages.election.voting_period'),
      }
    }
  },
  methods: {
    showSuccessMessage() {
      alert(this.$t('pages.election.messages.selected'));
    }
  }
}
```

### Phase 4: Build and Test

```bash
# Build Vue assets
npm run build

# Clear caches
php artisan config:clear
php artisan cache:clear

# Test in browser
# Open http://localhost:8000/election/
# Switch languages and verify translations display correctly
```

---

## Common Patterns

### Pattern 1: Nested Sections

For related translations, use nested objects:

```json
{
  "election_card": {
    "active": "Active",
    "inactive": "Inactive",
    "voting_ends": "Voting ends: {date}",
    "voting_starts": "Voting starts: {date}"
  },
  "demo_badge": {
    "text": "DEMO",
    "tooltip": "Demo Election - Safe for testing",
    "description": "This is a demo election..."
  }
}
```

Access with dot notation:
```vue
{{ $t('pages.election.election_card.active') }}
{{ $t('pages.election.demo_badge.tooltip') }}
```

### Pattern 2: Parameterized Translations

For text with dynamic values:

```json
{
  "voting_ends": "Voting ends: {date}",
  "current_step_label": "Step {step} of {total}",
  "status": "Status: {status}"
}
```

Use in components:
```vue
{{ $t('pages.election.voting_ends', { date: '2024-12-10' }) }}
{{ $t('pages.election.current_step_label', { step: 2, total: 5 }) }}
```

### Pattern 3: Pluralization

For plural forms:

```json
{
  "votes": "You have {count} vote | You have {count} votes"
}
```

Use:
```vue
{{ $tc('pages.election.votes', voteCount) }}
```

### Pattern 4: Core vs Page-Specific Translations

**Core translations** (used everywhere):
```json
// resources/js/locales/en.json
{
  "common": {
    "back": "Back",
    "next": "Next",
    "cancel": "Cancel",
    "submit": "Submit"
  }
}
```

**Page-specific translations** (used only in one feature):
```json
// resources/js/locales/pages/Election/en.json
{
  "title": "Election Selection",
  "voting_page": { ... }
}
```

Access:
```vue
{{ $t('common.back') }}           <!-- Core translation -->
{{ $t('pages.election.title') }}  <!-- Page translation -->
```

---

## Best Practices

### 1. Translation Keys Should Be Semantic

✅ **DO:**
```json
{
  "voting_page": {
    "status_active": "Voting is currently active",
    "rule_one_vote": "You can vote once only"
  }
}
```

❌ **DON'T:**
```json
{
  "label1": "Voting is currently active",
  "rule1": "You can vote once only"
}
```

### 2. Keep Keys Hierarchical

✅ **DO:**
```
pages.election.voting_page.title
pages.election.voting_page.status_active
pages.election.voting_instructions.step1
```

❌ **DON'T:**
```
election_voting_page_title
election_status_active
```

### 3. Use Consistent Terminology

Create a glossary for key terms:

```
"election" = निर्वाचन (Nepali), Wahl (German)
"voter" = मतदाता (Nepali), Wähler (German)
"voting" = मतदान (Nepali), Abstimmung (German)
"candidate" = उम्मेदवार (Nepali), Kandidat (German)
```

### 4. Write for Global Audiences

✅ **DO:**
```json
{
  "date_format": "January 10, 2024",
  "time_zone": "UTC"
}
```

❌ **DON'T:**
```json
{
  "date_format": "10/01/2024",  <!-- Ambiguous -->
  "time_zone": "IST"            <!-- Assumes India -->
}
```

### 5. Extract ALL User-Facing Text

Even error messages, button labels, and tooltips should be translated:

```json
{
  "messages": {
    "success": "Election selected successfully",
    "error": "Failed to select election",
    "required": "Please select an election to continue"
  },
  "errors": {
    "not_eligible": "You are not eligible to vote",
    "already_voted": "You have already voted",
    "voting_not_started": "Voting has not started yet"
  }
}
```

### 6. Handle HTML Content Carefully

For HTML in translations:

```javascript
// In component
<div v-html="$t('pages.election.help_desc_html')"></div>
```

```json
{
  "help_desc_html": "Click <a href=\"#\">here</a> for help"
}
```

**WARNING:** Only use `v-html` for trusted content. Never use it with user input.

### 7. Document Translation Context

Add comments in translation files for translators:

```json
{
  "verification_code": "Verification Code",
  "step1": "Enter your verification code",
  "_step1_context": "This is asking the user to enter a 6-digit code sent to their email",

  "submit": "Submit",
  "_submit_context": "Button label - appears on form submission button"
}
```

### 8. Never Hardcode Translations

❌ **DON'T:**
```javascript
export default {
  data() {
    return {
      title: "Active Election - Voting Page"
    }
  }
}
```

✅ **DO:**
```vue
<h1>{{ $t('pages.election.title') }}</h1>
```

---

## Troubleshooting

### Problem: Translations Show as Placeholders

**Symptom:** Pages show `pages.election.voting_page.title` instead of actual text

**Solutions:**

1. **Check i18n.js imports:**
   ```javascript
   // Verify imports exist
   import electionDe from './locales/pages/Election/de.json';
   import electionEn from './locales/pages/Election/en.json';
   import electionNp from './locales/pages/Election/np.json';
   ```

2. **Check i18n.js registration:**
   ```javascript
   // Verify in messages object
   pages: {
     election: electionDe,  // ✓ Present?
   }
   ```

3. **Rebuild assets:**
   ```bash
   npm run build
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Hard refresh browser:**
   - Clear browser cache (Ctrl+Shift+Del)
   - Reload page (F5 or Ctrl+R)

### Problem: Wrong Locale File Structure

**Symptom:** Translations nested too deeply

**Wrong:**
```json
{
  "pages": {
    "election": {
      "title": "..."
    }
  }
}
```

**Correct:**
```json
{
  "title": "...",
  "voting_page": { ... }
}
```

The `pages.election` wrapper is added by i18n.js, not the file!

### Problem: Missing Translations in One Language

**Symptom:** German works, English shows placeholders

**Solution:** Check all three language files exist:
```
resources/js/locales/pages/Election/
├── de.json  ✓
├── en.json  ✓
└── np.json  ✓
```

### Problem: Can't Find Translation File

**Symptom:** `Cannot find module` error during build

**Check:**
1. File path is correct (case-sensitive on Linux/Mac)
2. File exists and has valid JSON
3. Path in i18n.js import matches actual file location

```javascript
// ✓ Correct
import electionDe from './locales/pages/Election/de.json';

// ✗ Wrong (file is in pages/election, not Election)
import electionDe from './locales/pages/election/de.json';
```

---

## Examples

### Complete Example: Auth Page

#### Step 1: Create locale files

**`resources/js/locales/pages/Auth/en.json`:**
```json
{
  "login": {
    "title": "Sign In",
    "email": "Email Address",
    "email_placeholder": "your@email.com",
    "password": "Password",
    "password_placeholder": "Enter password",
    "remember": "Remember me",
    "forgot_password": "Forgot your password?",
    "submit": "Sign In",
    "no_account": "Don't have an account?",
    "sign_up": "Sign Up"
  },
  "register": {
    "title": "Create Account",
    "name": "Full Name",
    "email": "Email Address",
    "password": "Password",
    "confirm_password": "Confirm Password",
    "agree_terms": "I agree to the Terms & Conditions",
    "submit": "Create Account",
    "have_account": "Already have an account?",
    "sign_in": "Sign In"
  },
  "messages": {
    "email_required": "Email is required",
    "password_required": "Password is required",
    "invalid_email": "Email is not valid",
    "password_too_short": "Password must be at least 8 characters",
    "passwords_not_match": "Passwords do not match"
  }
}
```

**`resources/js/locales/pages/Auth/de.json`:**
```json
{
  "login": {
    "title": "Anmelden",
    "email": "E-Mail-Adresse",
    "email_placeholder": "ihre@email.com",
    "password": "Passwort",
    "password_placeholder": "Passwort eingeben",
    "remember": "Anmelden merken",
    "forgot_password": "Passwort vergessen?",
    "submit": "Anmelden",
    "no_account": "Haben Sie noch kein Konto?",
    "sign_up": "Registrieren"
  },
  "register": {
    "title": "Konto erstellen",
    "name": "Vollständiger Name",
    "email": "E-Mail-Adresse",
    "password": "Passwort",
    "confirm_password": "Passwort bestätigen",
    "agree_terms": "Ich akzeptiere die Allgemeinen Geschäftsbedingungen",
    "submit": "Konto erstellen",
    "have_account": "Sie haben bereits ein Konto?",
    "sign_in": "Anmelden"
  },
  "messages": {
    "email_required": "E-Mail ist erforderlich",
    "password_required": "Passwort ist erforderlich",
    "invalid_email": "E-Mail ist ungültig",
    "password_too_short": "Passwort muss mindestens 8 Zeichen lang sein",
    "passwords_not_match": "Passwörter stimmen nicht überein"
  }
}
```

**`resources/js/locales/pages/Auth/np.json`:**
```json
{
  "login": {
    "title": "साइन इन गर्नुहोस्",
    "email": "ईमेल ठेगाना",
    "email_placeholder": "तपाई@email.com",
    "password": "पासवर्ड",
    "password_placeholder": "पासवर्ड दर्ज गर्नुहोस्",
    "remember": "मलाई याद राख्नुहोस्",
    "forgot_password": "पासवर्ड भुल्नुभयो?",
    "submit": "साइन इन गर्नुहोस्",
    "no_account": "खाता छैन?",
    "sign_up": "साइन अप गर्नुहोस्"
  },
  "register": {
    "title": "खाता बनाउनुहोस्",
    "name": "पूरा नाम",
    "email": "ईमेल ठेगाना",
    "password": "पासवर्ड",
    "confirm_password": "पासवर्ड पुष्टि गर्नुहोस्",
    "agree_terms": "मैले शर्तहरू र शर्तहरू स्वीकार गर्छु",
    "submit": "खाता बनाउनुहोस्",
    "have_account": "पहिले नै खाता छ?",
    "sign_in": "साइन इन गर्नुहोस्"
  },
  "messages": {
    "email_required": "ईमेल आवश्यक छ",
    "password_required": "पासवर्ड आवश्यक छ",
    "invalid_email": "ईमेल वैध छैन",
    "password_too_short": "पासवर्ड कम्तीमा 8 वर्ण हुनु पर्छ",
    "passwords_not_match": "पासवर्ड मेल खाइरहेको छैन"
  }
}
```

#### Step 2: Register in i18n.js

```javascript
import authDe from './locales/pages/Auth/de.json';
import authEn from './locales/pages/Auth/en.json';
import authNp from './locales/pages/Auth/np.json';

const messages = {
  de: {
    ...de,
    pages: {
      // ... other pages
      auth: authDe,  // ← Add this
    },
  },
  en: {
    ...en,
    pages: {
      // ... other pages
      auth: authEn,  // ← Add this
    },
  },
  np: {
    ...np,
    pages: {
      // ... other pages
      auth: authNp,  // ← Add this
    },
  },
};
```

#### Step 3: Use in LoginComponent.vue

```vue
<template>
  <div class="auth-container">
    <form @submit.prevent="handleLogin">
      <h1>{{ $t('pages.auth.login.title') }}</h1>

      <div class="form-group">
        <label for="email">{{ $t('pages.auth.login.email') }}</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          :placeholder="$t('pages.auth.login.email_placeholder')"
          required
        />
        <span v-if="errors.email" class="error">
          {{ $t('pages.auth.messages.invalid_email') }}
        </span>
      </div>

      <div class="form-group">
        <label for="password">{{ $t('pages.auth.login.password') }}</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          :placeholder="$t('pages.auth.login.password_placeholder')"
          required
        />
      </div>

      <div class="form-checkbox">
        <input
          id="remember"
          v-model="form.remember"
          type="checkbox"
        />
        <label for="remember">{{ $t('pages.auth.login.remember') }}</label>
      </div>

      <button type="submit" class="btn-primary">
        {{ $t('pages.auth.login.submit') }}
      </button>

      <div class="form-footer">
        <p>
          {{ $t('pages.auth.login.no_account') }}
          <router-link to="/register">
            {{ $t('pages.auth.login.sign_up') }}
          </router-link>
        </p>
        <router-link to="/forgot-password">
          {{ $t('pages.auth.login.forgot_password') }}
        </router-link>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'LoginComponent',
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false,
      },
      errors: {
        email: false,
      }
    }
  },
  methods: {
    handleLogin() {
      if (!this.validateForm()) {
        return;
      }
      // Login logic...
    },
    validateForm() {
      if (!this.form.email) {
        this.errors.email = true;
        this.$toast.error(this.$t('pages.auth.messages.email_required'));
        return false;
      }
      if (!this.form.password) {
        this.$toast.error(this.$t('pages.auth.messages.password_required'));
        return false;
      }
      return true;
    }
  }
}
</script>
```

---

## Migration Checklist

When migrating an existing component to translation-first:

- [ ] Identify all user-facing text
- [ ] Create locale files for all 3 languages (en, de, np)
- [ ] Add imports to i18n.js
- [ ] Register translations in messages object
- [ ] Replace all hardcoded strings with `$t()` calls
- [ ] Test with `npm run build`
- [ ] Clear caches (`config:clear`, `cache:clear`)
- [ ] Test each language in browser
- [ ] Verify translations with native speakers if possible

---

## Quick Reference

### Adding a New Page Feature

1. **Create locale files:**
   ```bash
   touch resources/js/locales/pages/YourPage/{en,de,np}.json
   ```

2. **Add to i18n.js (lines 2-4, around line 25, and in messages object):**
   ```javascript
   import yourPageDe from './locales/pages/YourPage/de.json';
   import yourPageEn from './locales/pages/YourPage/en.json';
   import yourPageNp from './locales/pages/YourPage/np.json';

   // In messages object:
   pages: {
     yourPage: yourPageDe,  // for each language
   }
   ```

3. **Use in component:**
   ```vue
   {{ $t('pages.yourPage.keyName') }}
   ```

4. **Rebuild:**
   ```bash
   npm run build && php artisan config:clear && php artisan cache:clear
   ```

### Translating a Key

1. Find the key in `resources/js/locales/pages/PageName/en.json`
2. Add the German translation in `de.json`
3. Add the Nepali translation in `np.json`
4. Rebuild: `npm run build`

### Accessing Different Languages

In components:
```javascript
// All languages are available via $i18n
this.$i18n.locale  // Current locale (de, en, np)
this.$i18n.t()     // Same as $t()
```

---

## Resources

- [Vue i18n Documentation](https://vue-i18n.intlify.dev/)
- [JSON Format Specification](https://www.json.org/)
- [Translation Best Practices](https://en.wikipedia.org/wiki/Internationalization_and_localization)

---

## Version History

| Date | Author | Change |
|------|--------|--------|
| 2026-02-05 | Claude | Initial guide created |

---

## Questions?

For questions about translations, refer to this guide or ask in the development team Slack channel.
