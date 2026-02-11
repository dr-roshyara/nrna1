# Vue Components Documentation

## Overview

All Vue components located in `resources/js/Components/Dashboard/`

**Performance & Safety First:**
- All components include defensive array checks
- Props have safe defaults (never required for optional data)
- Computed properties validate data types before use
- No silent failures — explicit fallbacks

## Welcome.vue

**File:** `resources/js/Pages/Dashboard/Welcome.vue`
**Type:** Page component
**Route:** `/dashboard/welcome`

### Props Received (With Safe Defaults)
```javascript
{
  user: {
    display_name,
    identifier,      // Pseudonymized (SHA-256 hash)
    timezone,
    cultural_context,
    preferred_language
  },
  userState: {       // Required - always provided by controller
    roles,           // Array (may be empty)
    primary_role,    // string (e.g., 'admin', 'voter', 'guest')
    composite_state, // string (e.g., 'admin_with_elections')
    confidence_score,// 0-100
    onboarding_step, // 1-5
    ui_mode,         // 'simplified'|'standard'|'advanced'
    available_actions,
    primary_action,
    is_new_user,     // boolean
    has_multiple_roles, // boolean
    requires_gdpr_review  // boolean
  },
  trustSignals: [],        // Array (optional, defaults to empty)
  contentBlocks: [],       // Array (optional, defaults to empty)
  compliance: {            // Required
    gdpr_article_32_compliant,
    dsgvo_compliant,
    data_protection_officer_email,
    supervisory_authority
  }
}
```

### Defensive Rendering Pattern
All optional arrays are validated before use:
```javascript
// ✅ CORRECT: Check array before calling methods
if (Array.isArray(this.contentBlocks) && this.contentBlocks.length > 0) {
  // render blocks
}

// ❌ WRONG: Assumes contentBlocks is always an array
this.contentBlocks.some(block => block.type === 'actions')
```

### Rendered Components
- PersonalizedHeader (greeting + trust badges)
- QuickStartGrid (action cards) - with array safety checks
- OrganizationStatusBlock (if applicable) - rendered conditionally
- PendingActionsBlock (if applicable) - with safe pending_actions access
- HelpWidget (sticky help)

---

## PersonalizedHeader.vue

**File:** `resources/js/Components/Dashboard/PersonalizedHeader.vue`
**Type:** Component

### Props
```javascript
{
  user: {
    name: String,
    last_login_at: String (ISO)
  },
  organizationName: String (optional),
  userState: {
    roles: Array
  },
  trustSignals: Array
}
```

### Features
- Personalized greeting with wave animation
- Displays organization name
- Shows user role(s)
- Last login time (formatted relative)
- Up to 3 trust badges

### Responsive
- Desktop: Full layout
- Mobile: Compact layout

---

## QuickStartCard.vue

**File:** `resources/js/Components/Dashboard/QuickStartCard.vue`
**Type:** Component

### Props
```javascript
{
  id: String (required),
  icon: String (emoji),
  title: String (required),
  description: String (required),
  ctaText: String,
  isPrimary: Boolean,
  size: String ('small'|'medium'|'large'),
  meta: String (optional)
}
```

### Features
- Responsive design (fits all screen sizes)
- Primary/secondary styling
- Hover effects with animations
- Touch-friendly (48px+ minimum)
- ARIA labels for accessibility
- Meta information display

### Emits
```javascript
@click="emit('click', cardId)"
```

---

## QuickStartGrid.vue

**File:** `resources/js/Components/Dashboard/QuickStartGrid.vue`
**Type:** Component

### Props
```javascript
{
  cards: Array (required),
  title: String (optional),
  subtitle: String (optional),
  columns: Number (1-4)
}
```

### Responsive Breakpoints
- Desktop (>1024px): 3 columns
- Tablet (768-1024px): 2 columns
- Mobile (<768px): 1 column

### Features
- Auto-fit responsive grid
- Centered header with title/subtitle
- Passes events to parent
- Safe iteration with v-for with :key binding
- Handles empty cards array gracefully

### Safe Usage Pattern
```vue
<!-- ✅ CORRECT: Check array exists before rendering -->
<QuickStartGrid
  v-if="Array.isArray(actionCards) && actionCards.length > 0"
  :cards="actionCards"
/>

<!-- ❌ WRONG: Undefined behavior if cards is null -->
<QuickStartGrid :cards="actionCards" />
```

---

## OrganizationStatusBlock.vue

**File:** `resources/js/Components/Dashboard/OrganizationStatusBlock.vue`
**Type:** Component

### Props
```javascript
{
  userState: Object (required),
  onboardingStep: Number (1-5, required)
}
```

### Displays
- Step title and description
- Progress bar (0-100%)
- 4-item checklist with completion status
- Step icon (emoji)
- Primary action CTA button

### Features
- Color-coded progress
- Visual step indicators
- Responsive layout

---

## PendingActionsBlock.vue

**File:** `resources/js/Components/Dashboard/PendingActionsBlock.vue`
**Type:** Component

### Props
```javascript
{
  pendingActions: Array (required)
}
```

### Action Types
- pending_votes (🗳️)
- onboarding_step (🎯)
- gdpr_consent (🛡️)
- email_verification (✉️)
- org_setup_incomplete (🏢)

### Features
- Displays count badge
- Color-coded by action type
- Temporary dismissal (10-second timeout)
- CTA button for each action
- Auto-restore dismissed items

### Emits
```javascript
@action-clicked="handleAction"
@action-dismissed="handleDismiss"
```

---

## HelpWidget.vue

**File:** `resources/js/Components/Dashboard/HelpWidget.vue`
**Type:** Component

### Props
```javascript
{
  sticky: Boolean (default: true),
  position: String ('bottom-right'|'bottom-left'|'top-right'|'top-left')
}
```

### Features
- Sticky help button (56px circle)
- Expands to show menu
- 4 help options:
  1. Live request (💬)
  2. Contact support (📞)
  3. Documentation (📚)
  4. Book training (📅)
- Mobile slide-up behavior
- Backdrop dismissal on mobile

### Emits
```javascript
@action="emit('action', actionType)"
```

---

## Styling

All components use **scoped CSS** with:

### CSS Variables
```css
--color-primary-500
--color-primary-600
--color-gray-50 to --color-gray-900
--color-blue-50 to --color-blue-700
--color-green-50 to --color-green-700
etc.
```

### Responsive Design
```css
/* Mobile first */
@media (min-width: 768px) { /* Tablet */ }
@media (min-width: 1024px) { /* Desktop */ }
```

### Accessibility
- ARIA labels
- Keyboard navigation
- Focus rings
- Color contrast (WCAG AA)
- Min 48px touch targets

---

## Animations

### Transitions
- Fade: 0.2s ease (opacity)
- Slide: 0.3s ease (transform)
- Scale: 0.2s ease (transform)

### Hover Effects
- Button scale: 1.1x
- Card lift: translateY(-2px)
- Arrow animation: translateX(2px)

---

## Testing Components

### Mounting Example
```javascript
import { mount } from '@vue/test-utils'
import QuickStartCard from '@/Components/Dashboard/QuickStartCard.vue'

it('renders card with correct data', () => {
  const wrapper = mount(QuickStartCard, {
    props: {
      id: 'test',
      title: 'Test',
      description: 'Test description',
      icon: '🏢'
    }
  })
  expect(wrapper.find('.card-title').text()).toBe('Test')
})
```

---

## Component Hierarchy

```
Welcome.vue (Page)
├── PersonalizedHeader.vue
├── QuickStartGrid.vue
│   └── QuickStartCard.vue (x3)
├── OrganizationStatusBlock.vue
├── PendingActionsBlock.vue
│   └── Action items (rendered)
└── HelpWidget.vue
```

---

## Internationalization (i18n)

All text uses translation keys:

```vue
{{ $t('dashboard.quick_start_title') }}
{{ $t('help.live_request') }}
{{ $t('common.help') }}
```

Translation files:
- `resources/js/locales/pages/Welcome/de.json`
- `resources/js/locales/pages/Welcome/en.json`
- `resources/js/locales/pages/Welcome/np.json`
