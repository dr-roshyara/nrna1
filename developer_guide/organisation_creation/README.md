# organisation Creation Flow - Developer Guide

## Overview

The organisation Creation Flow is a **progressive disclosure modal** that guides new users through creating their organisation in Public Digit. The implementation follows **Design Thinking methodology** with emphasis on user education, accessibility, and legal compliance.

**Design Pattern:** Progressive Disclosure (Education → Form)
**Architecture:** Vue 3 + Composable + DDD principles
**Accessibility:** WCAG 2.1 AA compliant
**Languages:** German (de), English (en), Nepali (np)

---

## Table of Contents

1. [Architecture](#architecture)
2. [Component Structure](#component-structure)
3. [State Management](#state-management)
4. [Form Validation](#form-validation)
5. [Localization](#localization)
6. [Accessibility](#accessibility)
7. [API Contract](#api-contract)
8. [Analytics](#analytics)
9. [Testing](#testing)
10. [Troubleshooting](#troubleshooting)

---

## Architecture

### Flow Diagram

```
User clicks "Create organisation" card
         ↓
OrganizationCreateModal opens
         ↓
    ┌─────────────────────┐
    │ Education Overlay   │
    │ (What/Why/How)      │
    └─────────────────────┘
         ↓
    User clicks "Start"
         ↓
    ┌─────────────────────┐
    │ Step 1: Basic Info  │
    │ (Name, Email)       │
    └─────────────────────┘
         ↓
    ┌─────────────────────┐
    │ Step 2: Address     │
    │ (Street, City, ZIP) │
    └─────────────────────┘
         ↓
    ┌─────────────────────┐
    │ Step 3: Representative │
    │ (Name, Role, Email) │
    │ + Acceptance        │
    └─────────────────────┘
         ↓
    POST /api/organizations
         ↓
    Success Confirmation
```

### Design Principles

| Principle | Implementation |
|-----------|-----------------|
| **Progressive Disclosure** | Education overlay shown first, form hidden until user chooses to proceed |
| **User Anxiety Reduction** | FAQ sections explain what data is needed and why |
| **Legal Safety** | All required fields validated for German legal compliance |
| **Accessibility First** | WCAG 2.1 AA from ground up, not as an afterthought |
| **Mobile-Friendly** | Touch targets ≥44px, responsive layout, keyboard-only navigation |
| **Error Prevention** | Real-time validation with helpful, actionable error messages |

---

## Component Structure

### File organisation

```
resources/js/
├── Composables/
│   └── useOrganizationCreation.js       # State management
│
├── Components/organisation/
│   ├── OrganizationCreateModal.vue      # Main modal (education + form view)
│   │
│   └── Steps/
│       ├── EducationSection.vue          # Expandable FAQ sections
│       ├── FormInput.vue                 # Reusable input component
│       ├── OrganizationStepBasicInfo.vue # Step 1
│       ├── OrganizationStepAddress.vue   # Step 2
│       ├── OrganizationStepRepresentative.vue # Step 3
│       └── FormNavigation.vue            # Navigation buttons
│
└── Pages/Dashboard/
    └── Welcome.vue                       # Integration point
```

### Component Responsibility Matrix

| Component | Responsibility |
|-----------|-----------------|
| **OrganizationCreateModal** | Modal container, view state (education vs form), ESC key handling |
| **EducationSection** | Expandable accordion for FAQ, smooth animations |
| **FormInput** | Single input field with validation, error display, accessibility |
| **OrganizationStepBasicInfo** | Name & email form step, step-specific validation |
| **OrganizationStepAddress** | Address form step, German ZIP validation |
| **OrganizationStepRepresentative** | Representative data + acceptance checkboxes |
| **FormNavigation** | Back/Next/Submit buttons, loading state |
| **useOrganizationCreation** | All state, validation, submission, analytics |

---

## State Management

### Composable: `useOrganizationCreation`

The composable manages all state for the organisation creation flow using Vue 3 Composition API patterns.

#### Reactive State

```javascript
// Step tracking
const currentStep = ref(0)      // 0: education, 1-3: form steps
const isModalOpen = ref(false)  // Modal visibility
const showEducation = ref(true) // Show education overlay

// Form data (progressive collection)
const formData = reactive({
  basic: { name: '', email: '' },
  address: { street: '', city: '', zip: '', country: 'DE' },
  representative: { name: '', role: '', email: '' },
  acceptance: { gdpr: false, terms: false }
})

// Validation errors per step
const validationErrors = reactive({
  basic: {},
  address: {},
  representative: {}
})

// UI state
const isSubmitting = ref(false)
const submissionError = ref(null)
const expandedSections = reactive({
  dataPrivacy: false,
  requirements: false
})
```

#### Key Methods

| Method | Purpose | Returns |
|--------|---------|---------|
| `openModal()` | Open modal and reset to education | void |
| `closeModal()` | Close modal and reset all state | void |
| `nextStep()` | Validate and move to next step | boolean |
| `previousStep()` | Move to previous step | boolean |
| `validateStep(step)` | Validate specific form step | boolean |
| `submitForm()` | Submit to API | Promise<result/false> |
| `toggleSection(section)` | Toggle FAQ section expansion | void |

#### Computed Properties

```javascript
isFormStep           // true if on form steps (not education)
canGoNext            // true if current step is valid
canGoPrevious        // true if not on first step
progressPercentage   // 0-100 for progress bar
```

### Data Flow

```
User Input
    ↓
FormInput component emits
    ↓
Step component updates formData
    ↓
Composable validates
    ↓
Error messages display
    ↓
User corrects
    ↓
Form submits
```

---

## Form Validation

### Validation Rules by Step

#### Step 1: Basic Information

| Field | Rule | Error Message |
|-------|------|---------------|
| **Name** | Required, non-empty | "Organisationname ist erforderlich" |
| **Email** | Required, valid email format | "Ungültige E-Mail-Adresse" |

#### Step 2: Address

| Field | Rule | Error Message |
|-------|------|---------------|
| **Street** | Required, non-empty | "Straße und Hausnummer erforderlich" |
| **City** | Required, non-empty | "Ort ist erforderlich" |
| **ZIP** | Required, 5 German digits (^\d{5}$) | "Ungültige deutsche Postleitzahl" |
| **Country** | Pre-filled DE (disabled) | N/A |

#### Step 3: Representative

| Field | Rule | Error Message |
|-------|------|---------------|
| **Name** | Required, non-empty | "Name erforderlich" |
| **Role** | Required, non-empty | "Funktion erforderlich" |
| **Email** | Optional, valid if provided | Auto-filled from org email |
| **GDPR Acceptance** | Must be true | "DSGVO-Zustimmung erforderlich" |
| **Terms Acceptance** | Must be true | "Nutzungsbedingungen erforderlich" |

### Validation Implementation

```javascript
// In useOrganizationCreation.js
const validateStep = (step) => {
  const errors = validationErrors[getStepKey(step)];

  // Clear previous errors
  errors = {};

  switch (step) {
    case 1: // Validate basic info
      if (!formData.basic.name?.trim()) {
        errors.name = t('organisation.form.name_error')
      }
      // ... more validations
      break;
    // ... other steps
  }

  return Object.keys(errors).length === 0;
}
```

### Error Display

Errors are shown in real-time below each field:

```vue
<FormInput
  :error="errors.name"
  @update:modelValue="formData.basic.name = $event"
/>

<!-- Renders: -->
<!-- ⚠️ Organisationname ist erforderlich -->
```

---

## Localization

### Translation Keys Structure

All text is localized using vue-i18n with hierarchical key structure:

```
organisation.education.*      # Education overlay content
organisation.form.*           # Form step content
organisation.form.step_1_*    # Step 1 specific
organisation.form.step_2_*    # Step 2 specific
organisation.form.step_3_*    # Step 3 specific
common.*                      # Shared buttons/labels
```

### Language Files

- **German:** `resources/js/locales/pages/Dashboard/welcome/de.json`
- **English:** `resources/js/locales/pages/Dashboard/welcome/en.json`
- **Nepali:** `resources/js/locales/pages/Dashboard/welcome/np.json`

### Using Translations in Components

```vue
<!-- In templates, use $t() with fallback -->
<h2>{{ $t('organisation.form.title', { fallback: 'Create organisation' }) }}</h2>

<!-- In JavaScript, use composable or i18n directly -->
const errorMsg = this.$t('organisation.form.name_error')

<!-- With dynamic content -->
<p>{{ $t('organisation.form.step', { current: currentStep, total: 3 }) }}</p>
```

### Adding New Translations

1. Add key-value pair to all three language files:
   ```json
   {
     "organisation": {
       "form": {
         "new_key": "German text"
       }
     }
   }
   ```

2. Use in component:
   ```vue
   {{ $t('organisation.form.new_key', { fallback: 'English text' }) }}
   ```

3. Test in all three languages during development.

---

## Accessibility

### WCAG 2.1 AA Compliance Checklist

#### Keyboard Navigation

- [x] All interactive elements keyboard accessible (Tab key)
- [x] Focus order is logical (left-to-right, top-to-bottom)
- [x] ESC key closes modal
- [x] Enter/Space toggle accordion sections
- [x] Form submission with Enter key

#### Screen Readers

- [x] Semantic HTML (fieldset, legend, label)
- [x] ARIA labels on all inputs (`aria-label`)
- [x] ARIA descriptions for errors (`aria-describedby`)
- [x] ARIA expanded on toggles (`aria-expanded`)
- [x] Form validity announced (`aria-invalid`)
- [x] Progress bar announced (`role="progressbar"`, `aria-valuenow`)
- [x] Modal announced (`role="dialog"`, `aria-modal="true"`)

#### Color & Contrast

- [x] Minimum 4.5:1 contrast ratio for text
- [x] Error messages not color-only (include icon: ⚠️)
- [x] Dark mode support with proper contrast
- [x] High contrast mode support (border fallbacks)

#### Motion

- [x] Accordion animations respect `prefers-reduced-motion`
- [x] Form transitions disabled in reduced motion mode
- [x] Progress bar transitions disabled in reduced motion mode
- [x] Loading spinner disabled in reduced motion mode

#### Touch & Mobile

- [x] Touch targets minimum 44x44px
- [x] Readable without horizontal scroll
- [x] Text can be resized without loss of functionality
- [x] Form inputs have proper label associations

### Testing Accessibility

```bash
# Test with keyboard only (no mouse)
# Tab through all elements
# Verify focus is visible
# Test ESC key closes modal

# Test with screen reader (NVDA on Windows, VoiceOver on Mac)
# Verify all labels are announced
# Verify error messages are announced
# Verify form submission confirmation

# Test with browser accessibility checker
# Chrome DevTools: Lighthouse → Accessibility
# Should score 90+
```

### Example: Accessible Form Input

```vue
<template>
  <div class="space-y-2">
    <!-- Label associated with input -->
    <label :for="id" class="font-semibold">
      {{ label }}
      <span v-if="required" aria-label="required">*</span>
    </label>

    <!-- Input with accessibility attributes -->
    <input
      :id="id"
      :value="modelValue"
      :aria-invalid="!!error"
      :aria-describedby="error ? `${id}-error` : undefined"
      @input="$emit('update:modelValue', $event.target.value)"
    />

    <!-- Error message linked to input -->
    <p v-if="error" :id="`${id}-error`" role="alert">
      ⚠️ {{ error }}
    </p>
  </div>
</template>
```

---

## API Contract

### Request: POST /api/organizations

**Endpoint:** `POST /api/organizations`

**Authentication:** Bearer token (Sanctum)

**Request Body:**

```json
{
  "name": "Turnverein München 1860 e.V.",
  "email": "vorstand@tv-muenchen.de",
  "address": {
    "street": "Münchner Straße 1",
    "city": "München",
    "zip": "80331",
    "country": "DE"
  },
  "representative": {
    "name": "Dr. Thomas Schmidt",
    "role": "1. Vorsitzender",
    "email": "t.schmidt@tv-muenchen.de"
  },
  "accept_gdpr": true,
  "accept_terms": true
}
```

**Validation (Server-side):**

- Name: 2-255 characters
- Email: Valid email format
- Address fields: 2-255 characters each
- ZIP: Exactly 5 digits
- Country: Must be "DE"
- Acceptances: Must be true

### Response: 200 OK

```json
{
  "id": 1234,
  "name": "Turnverein München 1860 e.V.",
  "slug": "turnverein-muenchen-1860",
  "email": "vorstand@tv-muenchen.de",
  "status": "pending_verification",
  "created_at": "2025-02-11T10:30:00Z",
  "verification_email_sent_to": "vorstand@tv-muenchen.de",
  "next_steps": [
    "Verify organisation email",
    "Add first members",
    "Create first election"
  ]
}
```

### Response: 422 Validation Error

```json
{
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required"],
    "email": ["The email must be a valid email address"],
    "address.zip": ["The zip must be 5 digits"]
  }
}
```

### Response: 409 Conflict

```json
{
  "message": "organisation with this name already exists",
  "error_code": "organization_exists"
}
```

### Response: 500 Server Error

```json
{
  "message": "Failed to create organisation",
  "error_code": "internal_error"
}
```

---

## Analytics

### Events Tracked

All events use Google Analytics 4 format.

| Event | Trigger | Data |
|-------|---------|------|
| `organization_creation_started` | Modal opens | `event_category: 'onboarding'` |
| `organization_education_viewed` | FAQ section expands | `section: 'dataPrivacy' \| 'requirements'` |
| `organization_step_completed` | Step validated | `step: 'Grunddaten' \| 'Adresse' \| 'Vertreter'` |
| `organization_created` | Form submitted successfully | `organization_name: string` |
| `organization_creation_error` | Submission fails | `error_message: string` |

### Tracking Implementation

```javascript
// In composable
const trackOrganizationCreationStarted = () => {
  if (window.gtag) {
    window.gtag('event', 'organization_creation_started', {
      event_category: 'onboarding',
      timestamp: new Date().toISOString()
    });
  }
};

// In component
const trackStepCompleted = (step) => {
  window.gtag?.('event', 'organization_step_completed', {
    event_category: 'onboarding',
    step: stepTitles[step]
  });
};
```

### Analytics Dashboard Queries

```sql
-- How many users start the flow?
SELECT COUNT(DISTINCT user_id) as started
FROM events
WHERE event_name = 'organization_creation_started'

-- How many complete each step?
SELECT step, COUNT(*) as count
FROM events
WHERE event_name = 'organization_step_completed'
GROUP BY step

-- Conversion rate
SELECT
  COUNT(DISTINCT user_id) as completed /
  (SELECT COUNT(DISTINCT user_id) FROM events
   WHERE event_name = 'organization_creation_started') as conversion_rate
FROM events
WHERE event_name = 'organization_created'

-- Most common errors
SELECT error_message, COUNT(*) as count
FROM events
WHERE event_name = 'organization_creation_error'
GROUP BY error_message
ORDER BY count DESC
```

---

## Testing

### Unit Tests (Jest)

```javascript
// tests/Unit/Composables/useOrganizationCreation.test.js
describe('useOrganizationCreation', () => {
  it('initializes with modal closed', () => {
    const { isModalOpen } = useOrganizationCreation();
    expect(isModalOpen.value).toBe(false);
  });

  it('opens modal and shows education overlay', () => {
    const { isModalOpen, showEducation, openModal } = useOrganizationCreation();
    openModal();
    expect(isModalOpen.value).toBe(true);
    expect(showEducation.value).toBe(true);
  });

  it('validates email format', () => {
    const { formData, validateStep } = useOrganizationCreation();
    formData.basic.email = 'invalid-email';
    const isValid = validateStep(1);
    expect(isValid).toBe(false);
  });

  it('validates German ZIP code', () => {
    const { formData, validateStep } = useOrganizationCreation();
    formData.address.zip = '1234'; // Only 4 digits
    const isValid = validateStep(2);
    expect(isValid).toBe(false);
  });

  it('requires acceptance of GDPR and terms', () => {
    const { formData, validateStep } = useOrganizationCreation();
    formData.representative.name = 'John Doe';
    formData.representative.role = 'Chair';
    formData.acceptance.gdpr = false;
    const isValid = validateStep(3);
    expect(isValid).toBe(false);
  });

  it('submits form with valid data', async () => {
    const { formData, submitForm } = useOrganizationCreation();
    // Fill all fields...
    const result = await submitForm();
    expect(result).toHaveProperty('id');
  });
});
```

### Component Tests (Vue Test Utils)

```javascript
// tests/Unit/Components/organisation/OrganizationCreateModal.test.js
describe('OrganizationCreateModal', () => {
  it('shows education overlay by default', () => {
    const wrapper = mount(OrganizationCreateModal);
    expect(wrapper.find('[id="education-title"]').exists()).toBe(true);
  });

  it('expands FAQ section on click', async () => {
    const wrapper = mount(EducationSection, {
      props: { title: 'Test', expanded: false }
    });
    await wrapper.find('button').trigger('click');
    expect(wrapper.emitted('toggle')).toBeTruthy();
  });

  it('displays validation errors', async () => {
    const wrapper = mount(OrganizationStepBasicInfo, {
      props: { data: { name: '', email: '' }, errors: { name: 'Required' } }
    });
    expect(wrapper.text()).toContain('Required');
  });

  it('disables form submission with errors', async () => {
    const wrapper = mount(FormNavigation, {
      props: { canGoNext: false }
    });
    const submitBtn = wrapper.find('button:nth-of-type(2)');
    expect(submitBtn.attributes('disabled')).toBeDefined();
  });
});
```

### E2E Tests (Cypress)

```javascript
// cypress/e2e/organisation-creation.cy.js
describe('organisation Creation Flow', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/dashboard');
  });

  it('completes full organisation creation flow', () => {
    // Click create organisation card
    cy.contains('Organisation erstellen').click();
    cy.get('[role="dialog"]').should('be.visible');

    // Read education overlay
    cy.contains('Was ist eine Organisation?').should('be.visible');
    cy.get('[aria-expanded="false"]').first().click();
    cy.contains('Verschlüsselte Speicherung').should('be.visible');

    // Start form
    cy.contains('Organisation jetzt gründen').click();
    cy.get('[id*="org-name"]').should('be.visible');

    // Step 1: Basic info
    cy.get('[id="org-name"]').type('Test Verein e.V.');
    cy.get('[id="org-email"]').type('test@verein.de');
    cy.contains('Weiter').click();

    // Step 2: Address
    cy.get('[id="org-street"]').type('Main Street 1');
    cy.get('[id="org-city"]').type('Munich');
    cy.get('[id="org-zip"]').type('80331');
    cy.contains('Weiter').click();

    // Step 3: Representative
    cy.get('[id="rep-name"]').type('Dr. Test');
    cy.get('[id="rep-role"]').type('Vorsitzender');
    cy.get('[id="accept-gdpr"]').check();
    cy.get('[id="accept-terms"]').check();
    cy.contains('Gründen').click();

    // Verify success
    cy.contains('Bestätigungsemail').should('be.visible');
    cy.get('[role="dialog"]').should('not.exist');
  });

  it('prevents submission with invalid data', () => {
    cy.contains('Organisation erstellen').click();
    cy.contains('Organisation jetzt gründen').click();

    // Leave fields empty
    cy.contains('Weiter').click();
    cy.contains('ist erforderlich').should('be.visible');
  });

  it('shows error message on server error', () => {
    cy.intercept('POST', '/api/organizations', {
      statusCode: 500,
      body: { message: 'Internal error' }
    });

    // Fill and submit form...
    cy.contains('Internal error').should('be.visible');
  });
});
```

---

## Troubleshooting

### Common Issues

#### Issue: Modal doesn't open

**Symptoms:** Clicking "Create organisation" does nothing

**Causes:**
- Composable not initialized in Welcome.vue data()
- Modal component not imported/registered
- Event handler calling wrong method

**Solution:**
```javascript
// In Welcome.vue
data() {
  return {
    organizationCreation: useOrganizationCreation() // ✓ Initialize
  }
}

methods: {
  handleActionClick(cardData) {
    if (cardData.cardId === 'create_organization') {
      this.organizationCreation.openModal() // ✓ Call correct method
    }
  }
}
```

#### Issue: Form validation always fails

**Symptoms:** "Next" button disabled even with valid data

**Causes:**
- Validation function checking wrong field
- Data not bound correctly to form inputs
- Reactive state not updated

**Solution:**
```vue
<!-- ✓ Correct: Two-way binding -->
<input
  :value="data.name"
  @input="$emit('update', 'name', $event.target.value)"
/>

<!-- ✗ Wrong: Not updating parent data -->
<input
  v-model="tempName"
/>
```

#### Issue: Translations missing

**Symptoms:** `[organisation.form.title]` displayed instead of text

**Causes:**
- Translation key not added to all three language files
- Key hierarchy mismatch (organisation.form.title vs organisation.form.title_text)
- i18n not initialized

**Solution:**
```javascript
// Add to ALL three files: de.json, en.json, np.json
{
  "organisation": {
    "form": {
      "title": "German text here"
    }
  }
}

// Use in component with fallback
{{ $t('organisation.form.title', { fallback: 'Create organisation' }) }}
```

#### Issue: Accessibility test fails

**Symptoms:** Lighthouse or axe-core reports violations

**Common violations:**
- Missing label associations (use `for` attribute)
- Insufficient color contrast (4.5:1 ratio)
- Missing ARIA labels
- Focus not visible on inputs

**Solution:**
```vue
<!-- ✓ Correct accessibility -->
<label for="org-name">organisation Name</label>
<input id="org-name" aria-invalid="false" />

<!-- ✗ Inaccessible -->
<p>organisation Name</p>
<input />
```

#### Issue: Form data lost on back navigation

**Symptoms:** Going back to Step 1, then forward to Step 2 clears data

**Cause:** Form data not persisted between steps

**Solution:** Data is already persistent in composable reactive state. Check that:
- You're not calling `resetForm()` unintentionally
- Back button calls `previousStep()` not `closeModal()`
- Form inputs are correctly bound to composable data

#### Issue: API submission fails silently

**Symptoms:** Submit button shows loading spinner but nothing happens

**Causes:**
- CSRF token missing
- API endpoint not implemented
- Response format doesn't match expected schema
- Network error not logged

**Solution:**
```javascript
// Ensure CSRF token is in request headers
const response = await fetch('/api/organizations', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
  },
  body: JSON.stringify(payload)
});

// Log errors for debugging
if (!response.ok) {
  console.error('API Error:', await response.json());
}
```

### Debug Mode

Enable debug logging:

```javascript
// In useOrganizationCreation.js
const DEBUG = true; // Set to false in production

const log = (msg, data) => {
  if (DEBUG) {
    console.log(`[OrganizationCreation] ${msg}`, data);
  }
};

// Use throughout composable
log('Form submitted', formData.value);
log('Validation failed', validationErrors.value);
log('API response', result);
```

### Performance Considerations

**Modal performance:**
- ~50KB component bundle (with all steps)
- Single instance in DOM (reused)
- No virtual scrolling needed (form is short)

**Optimization tips:**
- Lazy-load modal component: `defineAsyncComponent()`
- Debounce email validation (300ms)
- Use `computed` for validation state

```javascript
// Lazy load modal
const OrganizationCreateModal = defineAsyncComponent(() =>
  import('@/Components/organisation/OrganizationCreateModal.vue')
)

// Debounce email validation
const validateEmail = debounce((email) => {
  formData.basic.email = email;
  validateStep(1);
}, 300);
```

---

## References

- [Vue 3 Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Aria Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [Google Analytics 4](https://developers.google.com/analytics/devguides/collection/ga4)

---

**Last Updated:** February 11, 2025
**Version:** 1.0.0
**Status:** Production Ready
