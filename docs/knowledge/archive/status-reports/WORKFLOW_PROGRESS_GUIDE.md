# Workflow Progress System - State Machine Implementation Guide

## Overview

The application now includes a **state-machine-driven progress indicator system** that works across all multi-step workflows (voting, delegate voting, finance, etc.).

## Architecture

### Components & Files

1. **WorkflowSteps.js** - State machine configuration
   - Defines all workflows with their steps
   - Contains step translations for all languages (EN, DE, NP)
   - Location: `resources/js/Config/WorkflowSteps.js`

2. **WorkflowProgress.vue** - Generic progress component
   - Renders progress bar and step counter
   - Uses state machine configuration
   - Supports all workflows dynamically
   - Location: `resources/js/Components/Workflow/WorkflowProgress.vue`

3. **Translation Keys**
   - Generic workflow step translation: `workflow.step`
   - Specific step names defined in WorkflowSteps.js
   - Updated in: `en.json`, `de.json`, `np.json`

## Defined Workflows

### 1. VOTING (5 steps)
**Voting workflow for regular voters**

| Step | Name | Route | Translation |
|------|------|-------|-------------|
| 1 | Code Creation | `slug.code.create` | "Code Creation" / "Code-Erstellung" / "कोड सृजना" |
| 2 | Agreement | `slug.code.agreement` | "Accept Agreement" / "Vereinbarung akzeptieren" / "सहमति स्वीकार गर्नुहोस्" |
| 3 | Ballot Selection | `slug.vote.create` | "Cast Your Vote" / "Stimme Abgeben" / "मतदान गर्नुहोस्" |
| 4 | Verification | `slug.vote.verify` | "Verify Your Vote" / "Stimme Überprüfen" / "आफ्नो मत पुष्टि गर्नुहोस्" |
| 5 | Completion | `slug.vote.complete` | "Thank You" / "Vielen Dank" / "धन्यवाद" |

**Usage Example:**
```vue
<WorkflowProgress workflow="VOTING" :currentStep="3" showTitle />
```

### 2. DELEGATE_VOTING (4 steps)
**Delegate voting workflow**

| Step | Name | Route | Translation |
|------|------|-------|-------------|
| 1 | Code Generation | `deligatecode.create` | "Generate Code" / "Code generieren" / "कोड सृजना गर्नुहोस्" |
| 2 | Candidate Selection | `deligatevote.create` | "Select Candidates" / "Kandidaten auswählen" / "उम्मेदवारहरू छनौट गर्नुहोस्" |
| 3 | Verification | `deligatevote.verifiy` | "Verify Votes" / "Stimmen überprüfen" / "मतहरू पुष्टि गर्नुहोस्" |
| 4 | Submission | `deligatevote.store` | "Submit" / "Einreichen" / "जमा गर्नुहोस्" |

**Usage Example:**
```vue
<WorkflowProgress workflow="DELEGATE_VOTING" :currentStep="2" showTitle />
```

### 3. FINANCE_INCOME (3 steps)
**Finance income submission workflow**

| Step | Name | Route | Translation |
|------|------|-------|-------------|
| 1 | Create Form | `finance.income.create` | "Enter Income Details" / "Einnahmendaten eingeben" / "आय विवरण प्रविष्ट गर्नुहोस्" |
| 2 | Verification | `finance.income.verify` | "Verify Information" / "Informationen überprüfen" / "जानकारी पुष्टि गर्नुहोस्" |
| 3 | Submit | `finance.income.store` | "Submit Income" / "Einnahmen einreichen" / "आय जमा गर्नुहोस्" |

**Usage Example:**
```vue
<WorkflowProgress workflow="FINANCE_INCOME" :currentStep="1" showTitle />
```

### 4. FINANCE_OUTCOME (3 steps)
**Finance outcome (expense) submission workflow**

| Step | Name | Route | Translation |
|------|------|-------|-------------|
| 1 | Create Form | `finance.outcome.create` | "Enter Expense Details" / "Ausgabendaten eingeben" / "खर्च विवरण प्रविष्ट गर्नुहोस्" |
| 2 | Verification | `finance.outcome.verify` | "Verify Information" / "Informationen überprüfen" / "जानकारी पुष्टि गर्नुहोस्" |
| 3 | Submit | `finance.outcome.store` | "Submit Expenses" / "Ausgaben einreichen" / "खर्चहरू जमा गर्नुहोस्" |

**Usage Example:**
```vue
<WorkflowProgress workflow="FINANCE_OUTCOME" :currentStep="3" />
```

## How to Use

### Basic Implementation

1. **Import the component:**
```vue
<script setup>
import WorkflowProgress from '@/Components/Workflow/WorkflowProgress'
</script>
```

2. **Add to template:**
```vue
<template>
  <div>
    <!-- Your page content -->
    <WorkflowProgress
      workflow="VOTING"
      :currentStep="currentStepNumber"
      showTitle
    />
  </div>
</template>
```

### Props

| Prop | Type | Required | Default | Description |
|------|------|----------|---------|-------------|
| `workflow` | String | Yes | - | Workflow name (e.g., 'VOTING', 'DELEGATE_VOTING') |
| `currentStep` | Number | Yes | - | Current step number (1-N) |
| `showTitle` | Boolean | No | true | Show/hide step title/name |

### Features

✅ **Multi-Language Support**
- Automatically detects current locale (en, de, np)
- Shows step names in current language
- Shows progress counter in current language

✅ **Dynamic Progress Bar**
- Automatically calculates percentage based on total steps
- Smooth animations when step changes
- Responsive design (mobile-friendly)

✅ **Flexible Workflow Definition**
- Add new workflows by editing `WorkflowSteps.js`
- Each workflow independently configured
- Easy to extend with new steps

✅ **Validation & Warnings**
- Console warnings for unknown workflows
- Warnings for invalid step numbers
- Helpful debug information

## Adding New Workflows

To add a new workflow to the system:

### 1. Edit `WorkflowSteps.js`

```javascript
FINANCE_REPORTS: {
  name: 'finance_reports',
  displayName: 'Finance Reports Workflow',
  totalSteps: 4,
  steps: [
    {
      step: 1,
      name: 'Report Creation',
      route: 'finance.reports.create',
      description: 'Create financial report',
      translations: {
        en: 'Create Report',
        de: 'Bericht erstellen',
        np: 'रिपोर्ट सृजना गर्नुहोस्'
      }
    },
    // ... additional steps
  ]
}
```

### 2. Use the new workflow

```vue
<WorkflowProgress workflow="FINANCE_REPORTS" :currentStep="1" showTitle />
```

## Current Implementation Status

### ✅ Completed
- [x] State machine configuration (WorkflowSteps.js)
- [x] Generic WorkflowProgress component
- [x] Translation keys added
- [x] 4 workflows defined:
  - Voting (5 steps)
  - Delegate Voting (4 steps)
  - Finance Income (3 steps)
  - Finance Outcome (3 steps)
- [x] Build tested & verified

### 🚀 Ready to Deploy

The following pages can now use the new state machine-driven progress:

**Voting Workflow:**
- `Code/CreateCode.vue` - Update from VotingProgress to WorkflowProgress
- `Code/Agreement.vue` - Update from VotingProgress to WorkflowProgress
- `Vote/Create.vue` - Update from VotingProgress to WorkflowProgress
- `Vote/Verify.vue` - Update from VotingProgress to WorkflowProgress
- `Thankyou/Thankyou.vue` - Update from VotingProgress to WorkflowProgress

**Finance Workflows:**
- `Finance/Income/Create.vue` - Add WorkflowProgress (Step 1)
- `Finance/Income/Verify.vue` - Add WorkflowProgress (Step 2)
- `Finance/Outcome/Create.vue` - Add WorkflowProgress (Step 1)
- `Finance/Outcome/Verify.vue` - Add WorkflowProgress (Step 2)

**Delegate Workflow:**
- `DeligateVote/CreateDeligateCode.vue` - Add WorkflowProgress (Step 1)
- `DeligateVote/CreateDeligateVote.vue` - Add WorkflowProgress (Step 2)
- `DeligateVote/VerifyDeligateVote.vue` - Add WorkflowProgress (Step 3)

## Migration Guide: From VotingProgress to WorkflowProgress

### Old Implementation (VotingProgress)
```vue
<script setup>
import VotingProgress from '@/Components/Voting/VotingProgress'
</script>

<template>
  <VotingProgress :currentStep="4" />
</template>
```

### New Implementation (WorkflowProgress)
```vue
<script setup>
import WorkflowProgress from '@/Components/Workflow/WorkflowProgress'
</script>

<template>
  <WorkflowProgress
    workflow="VOTING"
    :currentStep="4"
    showTitle
  />
</template>
```

## Benefits of State Machine Approach

1. **Single Source of Truth** - All workflow definitions in one place
2. **Easy to Maintain** - Add/update workflows without touching components
3. **Scalable** - Support any number of workflows and steps
4. **Type-Safe** - Validation and console warnings
5. **Multi-Language** - All workflows support EN, DE, NP
6. **Responsive** - Works on all devices
7. **Consistent** - Same look and feel across all workflows

## Translation Key Reference

**Generic Workflow Progress:**
```
workflow.step = "Step {current}/{total}"
workflow.step (de) = "Schritt {current}/{total}"
workflow.step (np) = "चरण {current}/{total}"
```

**Specific Workflow Steps:**
See WorkflowSteps.js for all step-specific translations in each workflow.

---

**Last Updated:** February 6, 2026
**Status:** Production Ready ✅
