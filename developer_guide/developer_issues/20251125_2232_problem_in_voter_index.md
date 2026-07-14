Here are professional prompt engineering instructions for Claude CLI to resolve the "Cannot read properties of null" error:

## Claude CLI Prompt Engineering Instructions

### **Primary Prompt Structure:**
```
CONTEXT: Laravel 9 + Inertia.js + Vue.js 3 application with Spatie permissions
TASK: Fix "Cannot read properties of null (reading 'name')" error in Voter Management Interface
ERROR LOCATION: resources/js/Pages/Voter/IndexVoter.vue
ERROR TYPE: Vue.js runtime error - null property access

TECHNICAL SPECIFICATIONS:
- Framework: Laravel 9 with Inertia.js
- Frontend: Vue.js 3 with Composition API
- Data: Paginated voter data from Laravel controller
- UI: Custom table component with query builder

REQUIRED FIXES:
1. Implement safe property access patterns
2. Add comprehensive null checking
3. Maintain existing functionality
4. Preserve TypeScript-like safety in JavaScript

IMPLEMENTATION GUIDELINES:
```

### **Detailed Technical Instructions:**

#### **1. Safe Property Access Pattern**
```javascript
// REPLACE: voter.name
// WITH: voter?.name || 'Default Value'

// REPLACE: voter.property
// WITH: voter?.property ?? 'Fallback Value'

// REPLACE: voters.data.length
// WITH: voters?.data?.length || 0
```

#### **2. Template Guard Clauses**
```vue
<!-- ADD before property access -->
<template v-if="voter && voter.name">
{{ voter.name }}
</template>
<template v-else>
  Unknown Voter
</template>

<!-- OR use conditional rendering -->
<div v-if="voters?.data">
  <!-- Safe to access voters.data here -->
</div>
```

#### **3. Data Validation Method**
```javascript
// ADD to methods section
getSafeVoterProperty(voter, property, defaultValue = 'N/A') {
  return voter?.[property] ?? defaultValue;
}

// USAGE: {{ getSafeVoterProperty(voter, 'name', 'Unknown Voter') }}
```

#### **4. Comprehensive Error Prevention**
```javascript
// IMPLEMENT in computed properties
const safeVoters = computed(() => {
  return props.voters?.data?.filter(voter => voter != null) || [];
});

// IMPLEMENT in template loops
v-for="voter in safeVoters"
:key="voter?.id || index"
```

### **Specific File Modification Instructions:**

#### **For IndexVoter.vue:**
```
FILE: resources/js/Pages/Voter/IndexVoter.vue

MODIFICATIONS REQUIRED:

1. TEMPLATE SECTION:
   - Wrap table in: <div v-if="voters?.data">
   - Replace all {{ voter.property }} with {{ voter?.property || 'default' }}
   - Add :key="voter?.id || index" to v-for loops
   - Add v-if="voter" guard to table rows

2. SCRIPT SECTION:
   - Add safe access methods
   - Implement data validation
   - Add error boundary handling

3. PROPS VALIDATION:
   - Add default values for optional props
   - Implement prop type validation
```

#### **For Controller (Backend Safety):**
```
FILE: app/Http/Controllers/VoterlistController.php

MODIFICATIONS:
- Add data transformation to ensure field presence
- Implement null field default values
- Add database query validation
```

### **Testing Requirements:**
```
TEST SCENARIOS:
1. Empty voter list
2. Voter with null name field
3. Malformed voter data
4. Pagination edge cases
5. Network error states

VALIDATION CRITERIA:
- No console errors
- Graceful fallback displays
- Maintained functionality
- Preserved user experience
```

### **Error Prevention Strategy:**
```
DEFENSIVE PROGRAMMING APPROACH:

1. LAYER 1: Backend Data Sanitization
   - Ensure database queries return consistent data
   - Transform null values to defaults

2. LAYER 2: Frontend Prop Validation
   - Validate incoming data structure
   - Set sensible default values

3. LAYER 3: Template Safety
   - Use optional chaining (?.)
   - Implement null coalescing (??)
   - Add conditional rendering guards

4. LAYER 4: Error Boundaries
   - Catch runtime errors gracefully
   - Provide user-friendly fallbacks
```

### **Complete Implementation Prompt:**
```
IMPLEMENT the following changes to fix null property access errors:

PHASE 1: TEMPLATE SAFETY
- Scan template for all {{ voter.property }} instances
- Replace with safe access: {{ voter?.property || 'Default' }}
- Add v-if guards for voter existence
- Implement conditional rendering for optional fields

PHASE 2: SCRIPT ENHANCEMENT
- Add data validation methods
- Implement computed properties with null checks
- Add error handling for API calls
- Create safe data access utilities

PHASE 3: BACKEND CONSISTENCY
- Ensure controller returns consistent data structure
- Add default values for null database fields
- Validate data before sending to frontend

PHASE 4: TESTING
- Verify all null scenarios handled
- Test empty states
- Validate error boundaries
- Confirm user experience maintained

DELIVERABLE: Error-free Voter Management interface with comprehensive null safety.
```

### **Quality Assurance Checklist:**
```
✅ All voter.property accesses use optional chaining
✅ Template has appropriate v-if guards
✅ Methods handle null/undefined inputs
✅ Computed properties include safety checks
✅ Backend provides consistent data structure
✅ Error states display user-friendly messages
✅ Functionality preserved across all scenarios
✅ No console errors in development tools
```

This prompt engineering approach ensures Claude CLI understands the technical context, required fixes, implementation strategy, and quality standards for resolving the null property access error comprehensively.