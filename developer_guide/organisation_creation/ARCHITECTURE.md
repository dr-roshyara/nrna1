# Architecture Decision Record - organisation Creation Flow

## Summary

This document records the architectural decisions made for the organisation Creation modal flow, including design patterns, state management choices, and accessibility-first approach.

---

## ADR-001: Progressive Disclosure Pattern

**Status:** ✅ Accepted

**Context:**
First-time users are anxious about entering correct data for organisation creation. They don't understand what an "organisation" is in this system, why they need one, or what information they must provide.

**Decision:**
Implement **progressive disclosure pattern**: Education overlay shown first, form steps hidden until user chooses to proceed.

**Architecture:**
```
Modal State Tree:
├── isModalOpen: boolean
├── showEducation: boolean  ← Controls view
├── currentStep: 0 (education) | 1-3 (form steps)
└── formData
```

**Advantages:**
- Reduces cognitive load (learn first, then act)
- Decreases user anxiety (explains what data is needed and why)
- Increases form completion rate (users understand the process)
- Accessible: Can navigate forward/backward with full context

**Disadvantages:**
- Slightly more clicks to reach form
- More code maintenance (two view states)

**Alternatives Considered:**
1. **Inline tooltip approach** - Too many floating tooltips, cluttered
2. **Multi-step wizard only** - Users fill form without understanding it
3. **Separate information page** - Users must navigate to different page, friction

---

## ADR-002: Composable State Management

**Status:** ✅ Accepted

**Context:**
Form state is complex: multiple steps, validation, submission, error handling, analytics. Need centralized state that's reusable across components.

**Decision:**
Use Vue 3 Composition API composable (`useOrganizationCreation`) as single source of truth.

**Architecture:**
```
useOrganizationCreation() composable
├── Reactive State
│   ├── currentStep, isModalOpen, showEducation
│   ├── formData (name, email, address, representative)
│   ├── validationErrors (per step)
│   └── UI state (isSubmitting, submissionError)
│
├── Methods
│   ├── openModal(), closeModal()
│   ├── nextStep(), previousStep()
│   ├── validateStep(step), submitForm()
│   └── toggleSection(section)
│
└── Analytics
    ├── trackOrganizationCreationStarted()
    ├── trackStepCompleted()
    ├── trackOrganizationCreated()
    └── trackOrganizationCreationError()
```

**Advantages:**
- Single source of truth (no prop drilling)
- Reusable across multiple components
- Testable in isolation (pure JS logic)
- Scalable (easy to add new steps or validation)
- Type-safe with TypeScript (when migrated)

**Disadvantages:**
- Composable must be initialized in parent (Welcome.vue data())
- Slightly more boilerplate than direct prop passing

**Alternatives Considered:**
1. **Vuex/Pinia store** - Overkill for single modal, unnecessary global state
2. **Parent prop drilling** - Non-scalable, harder to test
3. **Provide/Inject** - Works but less explicit than composable

---

## ADR-003: Component Responsibility Separation

**Status:** ✅ Accepted

**Context:**
Modal has complex logic (education overlay + 3-step form). Need clear separation of concerns.

**Decision:**
Split responsibilities across focused components:
- **OrganizationCreateModal** - Container, view state, modal behavior
- **Step components** - Form fields, step-specific layout
- **FormInput** - Reusable input primitive
- **EducationSection** - Expandable FAQ sections
- **FormNavigation** - Navigation button controls

**Architecture Diagram:**
```
OrganizationCreateModal (Modal container)
├── v-if="showEducation"
│   ├── EducationSection (FAQ 1)
│   ├── EducationSection (FAQ 2)
│   └── FormNavigation (Start CTA)
│
└── v-else
    ├── OrganizationStepBasicInfo
    │   └── FormInput × 2
    ├── OrganizationStepAddress
    │   └── FormInput × 3
    ├── OrganizationStepRepresentative
    │   └── FormInput × 3 + Checkbox × 2
    └── FormNavigation (Back/Next/Submit)
```

**Advantages:**
- Single responsibility principle (each component does one thing)
- Reusable components (FormInput used across all steps)
- Easy testing (can test each component independently)
- Easy to modify step without touching modal logic

**Disadvantages:**
- More files to maintain
- Prop passing between components (but minimal with composable)

---

## ADR-004: Client-Side Validation with Server-Side Enforcement

**Status:** ✅ Accepted

**Context:**
Need fast user feedback (client-side) AND legal safety (server-side verification).

**Decision:**
Implement **defense in depth**:
- Client validates for UX (quick feedback, error prevention)
- Server validates for security (no malicious bypasses)

**Architecture:**
```
User Input
    ↓
FormInput emits change
    ↓
Composable updates formData
    ↓
Component shows error (if invalid)
    ↓
Form submit disabled
    ↓
User corrects data
    ↓
Form submit allowed
    ↓
POST /api/organizations
    ↓
Server validates again (independent)
    ↓
Server returns 422 if invalid
    ↓
Client displays error message
```

**Validation Rules (Both Client & Server):**

| Field | Rule | Why |
|-------|------|-----|
| Name | 2-255 chars | Legal requirement |
| Email | Valid format | RFC 5322 |
| ZIP | Exactly 5 digits | German format only |
| Acceptances | Must be true | Legal requirement |

**Advantages:**
- Fast UX (no server round-trip for validation)
- Secure (server validates independently)
- Error messages available offline
- Scalable validation rules (same in both places)

**Disadvantages:**
- Validation code duplication (client + server)
- Must keep rules in sync

---

## ADR-005: Multi-Language First Design

**Status:** ✅ Accepted

**Context:**
Platform supports German (de), English (en), and Nepali (np). Modal must be fully localized from day one.

**Decision:**
Use vue-i18n with hierarchical key structure. All strings moved to language files.

**Architecture:**
```
resources/js/locales/pages/Dashboard/welcome/
├── de.json (German)
├── en.json (English)
└── np.json (Nepali)

Keys:
organisation.education.*
organisation.form.*
organisation.form.step_1_*
organisation.form.step_2_*
organisation.form.step_3_*
common.*
```

**Usage Pattern:**
```vue
<!-- In templates with fallback -->
{{ $t('organisation.form.title', { fallback: 'Create organisation' }) }}

<!-- In script -->
const errorMsg = this.$t('organisation.form.error')
```

**Advantages:**
- Easy to add new languages
- Strings not scattered through code
- Translation teams can work independently
- Easy to maintain consistency

**Disadvantages:**
- Cannot extract strings to separate i18n files (using embedded keys)
- Fallbacks not ideal for missing translations

---

## ADR-006: Accessibility as Mandatory Requirement

**Status:** ✅ Accepted

**Context:**
German association board members (target users) have varied abilities and ages (45-65). WCAG 2.1 AA is legal requirement for German government platforms.

**Decision:**
Build accessibility in from ground up, not as afterthought. Follow WCAG 2.1 AA guidelines in every component.

**Accessibility Checklist:**

| Category | Implementation |
|----------|-----------------|
| **Keyboard** | Tab order, ESC closes, Enter submits, Space toggles |
| **Screen Readers** | Semantic HTML, ARIA labels, landmark regions |
| **Color** | 4.5:1 contrast, not color-only, high contrast fallback |
| **Motion** | Respect prefers-reduced-motion, no seizure-inducing animations |
| **Touch** | 44×44px minimum targets, readable without zoom |

**Example: Accessible Input**
```vue
<label :for="id">{{ label }} <span aria-label="required">*</span></label>
<input
  :id="id"
  :aria-invalid="!!error"
  :aria-describedby="error ? `${id}-error` : null"
/>
<span v-if="error" :id="`${id}-error`" role="alert">⚠️ {{ error }}</span>
```

**Advantages:**
- Legal compliance (WCAG 2.1 AA)
- Broader user base (elderly, disabled users)
- Better for everyone (clearer UI, better contrast)
- Future-proof (new guidelines won't break existing code)

**Disadvantages:**
- Slightly more HTML/CSS code
- Need testing with assistive technology

---

## ADR-007: API Design - Request Structure

**Status:** ✅ Accepted

**Context:**
Need clear API contract for frontend/backend teams to work in parallel.

**Decision:**
Structured JSON request with flat but organized fields.

**API Endpoint:** `POST /api/organizations`

**Request Body:**
```json
{
  "name": "organisation Name",
  "email": "contact@org.de",
  "address": {
    "street": "Street 1",
    "city": "City",
    "zip": "12345",
    "country": "DE"
  },
  "representative": {
    "name": "Rep Name",
    "role": "Chair",
    "email": "rep@org.de"  // optional
  },
  "accept_gdpr": true,
  "accept_terms": true
}
```

**Advantages:**
- Clear separation of concerns (address is sub-object)
- Nested structure matches form steps
- Easy to validate with Laravel validation rules
- Easy to extend (add sub-fields without breaking API)

**Disadvantages:**
- Slightly more complex than flat structure

---

## ADR-008: Error Handling Strategy

**Status:** ✅ Accepted

**Context:**
Modal can fail at multiple points: validation errors, network errors, server errors. Need consistent error handling.

**Decision:**
Three-layer error handling:

**Layer 1: Client Validation**
```javascript
if (!formData.basic.name?.trim()) {
  validationErrors.basic.name = "Required"
  return false; // Prevent submission
}
```

**Layer 2: Network/Connection**
```javascript
try {
  const response = await fetch('/api/organizations', {...});
  if (!response.ok) {
    submissionError.value = "Network error"
  }
} catch (error) {
  submissionError.value = error.message
}
```

**Layer 3: Server Validation**
```javascript
// Server returns 422:
{
  "errors": {
    "name": ["Name already exists"],
    "email": ["Invalid format"]
  }
}
```

**Advantages:**
- Fail gracefully at each layer
- User-friendly error messages
- Can retry after fixing issues
- Analytics tracks error type

**Disadvantages:**
- Error messages at multiple levels (can be confusing)

---

## ADR-009: Analytics Events Design

**Status:** ✅ Accepted

**Context:**
Need to measure: completion rate, dropout points, common errors, and user engagement.

**Decision:**
Track key events using Google Analytics 4 format.

**Events:**
| Event | Trigger | Purpose |
|-------|---------|---------|
| `organization_creation_started` | Modal opens | Measure funnel entry |
| `organization_education_viewed` | FAQ section expands | Measure user confusion |
| `organization_step_completed` | Step validated | Measure dropout points |
| `organization_created` | Form submitted | Measure completion |
| `organization_creation_error` | Submission fails | Debug error patterns |

**Implementation:**
```javascript
if (window.gtag) {
  window.gtag('event', 'organization_created', {
    event_category: 'onboarding',
    organization_name: formData.basic.name,
    timestamp: new Date().toISOString()
  });
}
```

**Advantages:**
- Measurable funnel (entry → completion)
- Debug information (error patterns)
- Optimize UX (see where users drop off)

---

## ADR-010: Form Data Persistence Strategy

**Status:** ✅ Accepted

**Context:**
User might navigate back and forth between steps. Should form data persist?

**Decision:**
**Yes, persist in memory** (not localStorage). Once modal closes, data clears.

**Rationale:**
- Users expect to navigate back without losing data
- Clearing on close prevents stale data in new creation
- Memory persistence is simpler than localStorage

**Implementation:**
```javascript
const previousStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--; // Data stays in formData reactive object
    return true;
  }
}

const closeModal = () => {
  isModalOpen.value = false;
  resetForm(); // Clear all data
}
```

---

## Technology Stack Decisions

| Technology | Choice | Rationale |
|-----------|--------|-----------|
| Framework | Vue 3 | Reactive, composition API, progressive enhancement |
| State | Composable | Lightweight, reusable, testable |
| Styling | Tailwind CSS | Utility-first, dark mode support, accessibility |
| HTTP Client | Fetch API | Native, no dependencies, good error handling |
| Localization | vue-i18n | Multi-language support, standard Vue plugin |
| Validation | Custom JS | Simple rules, full control, no dependencies |
| Analytics | Google Analytics 4 | Standard, easy integration, good dashboards |

---

## Future Enhancements

### Phase 2 Planned Features

1. **Vereinsregister API Integration**
   - Auto-lookup organisation by registration number
   - Pre-fill name and address
   - Verify legal existence

2. **organisation Type Presets**
   - e.V. (Eingetragener Verein)
   - GmbH (Gesellschaft mit beschränkter Haftung)
   - gGmbH (gemeinnützige GmbH)
   - Pre-set validation rules per type

3. **Saved Drafts**
   - Save partially-filled form to localStorage
   - Resume later
   - Notification on draft availability

4. **Real-Time Availability Check**
   - Check if organisation name already exists
   - Suggest alternatives
   - Real-time feedback

5. **Document Upload**
   - Upload organisation charter (Satzung)
   - Upload registration certificate (Auszug)
   - Verify legal compliance

---

## Design Patterns Used

### Pattern 1: Progressive Disclosure
**Why:** Reduce cognitive load, teach before action

### Pattern 2: Composable State Management
**Why:** Single source of truth, reusable logic, testable

### Pattern 3: Component Composition
**Why:** Separation of concerns, reusability, testability

### Pattern 4: Defense in Depth Validation
**Why:** Fast UX + security

### Pattern 5: Graceful Degradation
**Why:** Works without JavaScript (basic structure), enhanced with JS

---

**Document Version:** 1.0
**Last Updated:** February 11, 2025
**Author:** Architecture Team
