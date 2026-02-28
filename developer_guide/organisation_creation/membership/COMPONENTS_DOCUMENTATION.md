# Components Documentation

---

## 📑 Table of Contents

1. [Phase 1 Components](#phase-1-components) ✅
2. [Phase 2 Components](#phase-2-components) ✅
3. [Phase 2 Remaining](#phase-2-remaining) 🚧
4. [Phase 3 Components](#phase-3-components) 📋

---

## ✅ Phase 1 Components

### 1. **Show.vue** - Main Dashboard Page

**Location**: `resources/js/Pages/Organizations/Show.vue`

**Purpose**: Main organisation dashboard page that orchestrates all sub-components

**Props**:
```javascript
{
  organisation: {
    type: Object,
    required: true,
    // { id, name, slug, email, created_at, ... }
  },
  stats: {
    type: Object,
    default: () => ({
      members_count: 0,
      active_members_count: 0,
      elections_count: 0,
      active_elections_count: 0,
      completed_elections: 0,
      new_members_30d: 0,
      exited_members_30d: 0
    })
  },
  demoStatus: Object,  // Demo setup status
  canManage: Boolean   // User permission
}
```

**Components Used**:
```
- BreadcrumbSchema (SEO)
- OrganizationHeader (Org info)
- StatsGrid (Metrics)
- ActionButtons (3 main actions)
- DemoSetupButton (Demo setup - conditional)
- SupportSection (Contact info)
```

**Layout**:
```
<ElectionLayout>
  <main>
    <OrganizationHeader />
    <StatsGrid />
    <ActionButtons />
    <DemoSetupButton /> (if canManage)
    <SupportSection />
  </main>
</ElectionLayout>
```

**Key Features**:
- ✅ Semantic HTML with aria-labels
- ✅ Screen reader announcements
- ✅ SEO meta tags (useMeta)
- ✅ Responsive layout
- ✅ No hardcoded strings

**Events**:
```javascript
// Emitted from ActionButtons
@appoint-officer="openOfficerModal"    // Phase 2
@create-election="openElectionWizard"  // Phase 2
```

**Translations Used**:
```
- accessibility.*
- pages.organisation-show.*
```

---

### 2. **OrganizationHeader.vue** - organisation Information

**Location**: `resources/js/Pages/Organizations/Partials/OrganizationHeader.vue`

**Purpose**: Display organisation header with name, email, and creation date

**Props**:
```javascript
{
  organisation: {
    type: Object,
    required: true,
    validator: (org) => org && org.name && org.email
    // { name, email, created_at, ... }
  }
}
```

**Key Features**:
- ✅ organisation type badge
- ✅ Locale-aware date formatting (de, en, np)
- ✅ Email link with mailto
- ✅ Responsive design
- ✅ Accessibility labels

**Date Formatting**:
```javascript
// Uses native Intl.DateTimeFormat
const formatDate = (dateString) => {
  const localeMap = {
    de: 'de-DE',     // German: "19. Februar 2026"
    en: 'en-US',     // English: "February 19, 2026"
    np: 'en-US'      // Nepali: fallback to English
  }
  return date.toLocaleDateString(localeCode, {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
```

**HTML Structure**:
```html
<div>
  <div class="flex gap-2">
    <span class="badge">{{ organisation }}</span>
    <time>{{ Created On: {date} }}</time>
  </div>
  <h1>{{ organisation.name }}</h1>
  <div>
    <a href="mailto:...">{{ organisation.email }}</a>
  </div>
</div>
```

**Translations Used**:
```
- pages.organisation-show.organisation.*
```

---

### 3. **StatsGrid.vue** - Statistics Dashboard

**Location**: `resources/js/Pages/Organizations/Partials/StatsGrid.vue`

**Purpose**: Display organisation metrics in card grid

**Props**:
```javascript
{
  stats: {
    type: Object,
    required: true,
    default: () => ({
      members_count: 0,
      active_members_count: 0,
      elections_count: 0,
      active_elections_count: 0,
      completed_elections: 0,
      new_members_30d: 0,
      exited_members_30d: 0
    })
  }
}
```

**Grid Layout**:
```
Desktop (4 columns):
┌──────────┬──────────┬──────────┬──────────┐
│ Members  │ Active   │ Elections│ Active   │
│          │ Members  │          │ Elections│
└──────────┴──────────┴──────────┴──────────┘
┌──────────┬──────────┐
│ Completed│New 30d   │
│ Elections│ Members  │
└──────────┴──────────┘

Tablet (2 columns):
┌──────────┬──────────┐
│ Members  │ Elections│
├──────────┼──────────┤
│ Active   │ Active   │
├──────────┼──────────┤
│ Completed│ New 30d  │
└──────────┴──────────┘

Mobile (1 column):
┌──────────┐
│ Members  │
├──────────┤
│ Active   │
├──────────┤
│ Elections│
...
└──────────┘
```

**Card Features**:
- ✅ Icon display
- ✅ Metric title + value
- ✅ Color-coded (blue, green, purple, orange)
- ✅ Hover effects
- ✅ Responsive text sizing

**Translations Used**:
```
- pages.organisation-show.stats.*
```

---

### 4. **ActionButtons.vue** - Primary Actions

**Location**: `resources/js/Pages/Organizations/Partials/ActionButtons.vue`

**Purpose**: 3 primary action cards (Import Members, Appoint Officer, Create Election)

**Props**:
```javascript
{
  organisation: {
    type: Object,
    required: false
    // Used to compute import link
  }
}
```

**Computed Properties**:
```javascript
const importMembersLink = computed(() => {
  if (props.organisation?.slug) {
    return `/organizations/${props.organisation.slug}/members/import`
  }
  return '#'
})
```

**Card Structure** (x3):
```html
<Link :href="importMembersLink">
  <div class="icon-box">
    <svg><!-- Icon --></svg>
  </div>
  <h3>{{ Title }}</h3>
  <p>{{ Description }}</p>
  <span class="cta">
    {{ Button Text }} →
  </span>
</Link>
```

**Hover Effects**:
- ✅ Border color change
- ✅ Shadow increase
- ✅ Icon background color change
- ✅ CTA arrow animation
- ✅ Overlay color change

**Events** (Future - Phase 2):
```javascript
emit('appoint-officer')
emit('create-election')
```

**Translations Used**:
```
- pages.organisation-show.actions.*
```

---

### 5. **SupportSection.vue** - Support Information

**Location**: `resources/js/Pages/Organizations/Partials/SupportSection.vue`

**Purpose**: Display support contact information

**Props**:
```javascript
{
  showAdditionalLinks: {
    type: Boolean,
    default: true  // Show handbook/webinar links
  }
}
```

**Computed Properties**:
```javascript
const decodedEmailAddress = computed(() => {
  // Replaces HTML entity &#64; with @
  return t('pages.organisation-show.support.email_address').replace(/&#64;/g, '@')
})

const phoneNumber = computed(() => {
  return t('pages.organisation-show.support.phone_number')
})
```

**Contact Options**:
```
┌─ Email Contact ─────────────────┐
│ 📧 Email Us                      │
│    support@publicdigit.de       │
├─ Phone Contact ─────────────────┤
│ ☎️  Call Us                      │
│    +49 (0) 30 1234567          │
├─ Support Hours ─────────────────┤
│ 🕐 Mon-Fri, 9am-6pm CET        │
├─ Additional Links (optional) ───┤
│ 📖 Handbook                      │
│ 🎥 Book Webinar                  │
└─────────────────────────────────┘
```

**Styling**:
- Gradient background (blue to indigo)
- Left border accent
- Icon+text pairs
- Responsive layout

**Translations Used**:
```
- pages.organisation-show.support.*
```

---

## ✅ Phase 2 Components

### 1. **Members/Import.vue** - Member Import Page

**Location**: `resources/js/Pages/Organizations/Members/Import.vue`

**Route**: `GET /organizations/{slug}/members/import`

**Purpose**: Dedicated page for importing members from CSV/Excel files

**Props**:
```javascript
{
  organisation: {
    type: Object,
    required: true
    // { id, name, slug, email, ... }
  }
}
```

**State Management**:
```javascript
const currentStep = ref('upload')  // upload, preview, success
const preview = ref(null)          // File preview data
const error = ref(null)            // Error message
const isImporting = ref(false)     // Loading state
const validationErrors = ref([])   // Validation errors
const importProgress = ref(0)      // 0-100 progress
```

**Workflow**:
```
Step 1: Upload
├─ File input or drag & drop
├─ File validation
└─ CSV parsing

Step 2: Preview
├─ Display first 10 rows
├─ Show validation errors
└─ Enable/disable import

Step 3: Success
├─ Show confirmation
├─ Display count imported
└─ Back to organisation link
```

**Key Functions**:
```javascript
handleFileSelect(event)      // File input handler
handleFileDrop(event)        // Drag & drop handler
processFile(file)            // Parse and validate
resetFile()                  // Reset to step 1
submitImport()               // POST to API
```

**Composable Integration**:
```javascript
const { parseFile, validateData, submitImport } = useMemberImport(organisation)
```

**Translations Used**:
```
- modals.member_import.* (30+ keys)
- pages.organisation-show.accessibility.*
```

**API Call**:
```javascript
POST /organizations/{slug}/members/import

Request:
{
  "headers": ["Email", "First Name", "Last Name"],
  "rows": [{...}, {...}],
  "fileName": "members.csv"
}

Response:
{
  "success": true,
  "imported_count": 123,
  "skipped_count": 0,
  "message": "..."
}
```

---

### 2. **useMemberImport.js** - Member Import Logic

**Location**: `resources/js/composables/useMemberImport.js`

**Purpose**: File parsing, validation, and API submission logic

**Functions**:

#### **parseFile(file)**
```javascript
// Detect file type and parse
// Returns: { headers, rows }
```

**Implementation**:
```javascript
parseFile(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.onload = (event) => {
      if (file.name.match(/\.csv$/i)) {
        const parsed = parseCSV(event.target.result)
        resolve(parsed)
      }
      // Excel files: try CSV parsing or send raw
    }
    reader.readAsText(file)
  })
}
```

#### **parseCSV(content)**
```javascript
// Parse CSV string into structured data
// Returns: { headers, rows }
```

**Features**:
- Splits by newlines
- First line = headers
- Handles quoted fields
- Escapes double quotes
- Trims whitespace

#### **parseCSVLine(line)**
```javascript
// Parse single CSV line
// Handles: quoted fields, escaped quotes, commas
// Returns: Array of values
```

**Example**:
```javascript
// Input: 'Name,"Email, Inc",Active'
// Output: ['Name', 'Email, Inc', 'Active']
```

#### **validateData(data)**
```javascript
// Comprehensive data validation
// Returns: { valid: boolean, errors: Array }
```

**Validation Checks**:
- ✅ Data not empty
- ✅ Headers present
- ✅ Email column exists
- ✅ Email format (regex)
- ✅ Duplicate emails
- ✅ Per-row validation
- ✅ Required fields

**Error Messages**:
```javascript
// Examples
"Email column required"
"Invalid email in row 5: test@"
"Duplicate email in row 8: jane@example.com"
"Required field 'first_name' missing in row 3"
```

#### **submitImport(importData)**
```javascript
// POST to API with CSRF protection
// Returns: API response
```

**Implementation**:
```javascript
const csrfRequest = useCsrfRequest()
const response = await csrfRequest.post(
  `/organizations/${organisation.slug}/members/import`,
  importData
)
```

---

## 🚧 Phase 2: Remaining Features

### 1. **ElectionOfficerModal.vue** - Officer Appointment

**Location**: `resources/js/Components/organisation/Modals/ElectionOfficerModal.vue`

**To Create**: Complete component with:
- Member selection dropdown
- Officer info display
- Deputy officer selection
- Expiration date picker
- CSRF-protected submission
- Error handling
- Success confirmation

**Translation Keys Ready**: 25+ keys

**Props**:
```javascript
{
  show: Boolean,
  organisation: Object,
  currentOfficer: Object (optional)
}
```

**Events**:
```javascript
emit('close')
emit('appointed', { officer, deputy, expires })
```

---

### 2. **ElectionCreationWizard.vue** - Election Setup

**Location**: `resources/js/Components/organisation/Modals/ElectionCreationWizard.vue`

**To Create**: Multi-step wizard with:
- Step 1: Basic election info (name, type, dates)
- Step 2: Officer confirmation
- Step 3: Candidate setup
- Step 4: Review & create
- Progress bar
- Validation per step
- CSRF protection

**Translation Keys Ready**: 40+ keys

**Props**:
```javascript
{
  show: Boolean,
  organisation: Object
}
```

**Events**:
```javascript
emit('close')
emit('created', { election })
```

---

## 📋 Phase 3: Future Components

### 1. **ComplianceDashboard.vue**
- Officer status display
- Compliance checklist
- BGB requirements
- Action items

### 2. **ActivityFeed.vue**
- Recent events timeline
- Filtering options
- Event details

### 3. **MemberManagementSection.vue**
- Member statistics
- Recent imports
- Region distribution

### 4. **ElectionManagementSection.vue**
- Election count
- Recent elections
- Election types

### 5. **DocumentTemplates.vue**
- Template download links
- Document categories

---

## 🧪 Testing Each Component

### **Unit Tests Pattern**
```javascript
// test/Unit/Components/OrganizationHeader.spec.js

describe('OrganizationHeader', () => {
  it('displays organisation name', () => {
    // Test
  })

  it('formats date according to locale', () => {
    // Test with each locale (de, en, np)
  })

  it('shows email link', () => {
    // Test mailto link
  })
})
```

### **Integration Tests**
```javascript
// test/Integration/Pages/OrganizationShow.spec.js

describe('organisation Show Page', () => {
  it('renders all sections', () => {
    // Mount page and verify all components
  })

  it('displays correct translations', () => {
    // Test all 3 languages
  })
})
```

---

## 📊 Component Dependency Graph

```
Show.vue (Main Page)
├── BreadcrumbSchema
├── OrganizationHeader
├── StatsGrid
├── ActionButtons
│   └── useMemberImport (for link)
├── DemoSetupButton (conditional)
├── SupportSection
├── ElectionOfficerModal (Phase 2) ⏳
└── ElectionCreationWizard (Phase 2) ⏳

Members/Import.vue (Dedicated Page)
├── ElectionLayout
├── BreadcrumbSchema
└── useMemberImport (Composable)

Future Sections:
├── ComplianceDashboard
├── ActivityFeed
├── MemberManagementSection
├── ElectionManagementSection
└── DocumentTemplates
```

---

## ✨ Code Style & Patterns

### **Component Template Pattern**
```vue
<template>
  <section aria-labelledby="heading" class="mb-8">
    <h2 id="heading">{{ $t('key.title') }}</h2>
    <!-- Content -->
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  // With validation
})

const emit = defineEmits(['event'])

// Reactive state
const state = ref(null)

// Computed
const computed = computed(() => {})

// Methods
const handleMethod = () => {}
</script>
```

### **Error Handling**
```javascript
try {
  await action()
} catch (error) {
  error.value = error.message
  console.error('Detailed error:', error)
}
```

### **Loading States**
```vue
<button :disabled="isLoading">
  {{ isLoading ? $t('common.loading') : $t('common.submit') }}
</button>
```

---

**Version**: 1.0.0
**Last Updated**: 2026-02-22
**Maintainer**: Claude Code
