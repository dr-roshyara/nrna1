# 📝 **Claude Code Prompt Instructions**

Copy and paste this prompt into Claude Code:

---

## **Task: Fix Modal Display Issue in Production**

### **File to Update:**
`resources/js/Components/Organisation/OrganisationCreateModal.vue`

### **Current Problem:**
The modal opens in production (Digital Ocean) but the form fields are not visible. The modal works correctly in local development. This appears to be a CSS/rendering issue specific to the production environment.

### **Required Changes:**

#### **1. Add Teleport Wrapper**
Wrap the entire modal content with Vue's Teleport to ensure it renders at the body level:

```vue
<template>
  <Teleport to="body">
    <!-- All existing modal content -->
  </Teleport>
</template>
```

#### **2. Fix Event Listener Memory Leak**
Replace the current event listener implementation:

**Current (problematic):**
```javascript
if (typeof window !== 'undefined') {
  window.addEventListener('keydown', handleKeydown);
}
```

**Updated (with proper lifecycle):**
```javascript
import { onMounted, onUnmounted } from 'vue';

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
});
```

#### **3. Add Fallbacks for Injected Values**
Add safety checks for the injected composable:

```javascript
// After inject
const organizationCreation = inject('organizationCreation');

// Add fallbacks
const isModalOpen = computed(() => organizationCreation?.isModalOpen?.value ?? false);
const currentStep = computed(() => organizationCreation?.currentStep?.value ?? 1);
const showEducation = computed(() => organizationCreation?.showEducation?.value ?? true);
const formData = computed(() => organizationCreation?.formData?.value ?? { 
  basic: {}, 
  address: {}, 
  representative: {}, 
  acceptance: {} 
});

// Methods with fallbacks
const closeModal = organizationCreation?.closeModal || (() => {});
const nextStep = organizationCreation?.nextStep || (() => {});
// ... etc for all methods
```

#### **4. Add Debug Information (Temporary)**
Add a debug section to help identify the issue:

```vue
<!-- Add after progress bar, inside form view -->
<div class="mb-4 p-2 bg-yellow-100 text-xs" v-if="process.env.NODE_ENV === 'production'">
  <p>Step: {{ currentStep }}, FormData exists: {{ !!formData }}</p>
  <p>Has basic: {{ !!formData?.basic }}</p>
  <p>Has address: {{ !!formData?.address }}</p>
  <p>Has representative: {{ !!formData?.representative }}</p>
</div>
```

#### **5. Add Console Logging for Debugging**
```javascript
// In script setup, after inject
console.log('🔍 Modal - organizationCreation injected:', organizationCreation);
console.log('🔍 Modal - formData:', organizationCreation?.formData?.value);

// Watch modal open state
watch(isModalOpen, (val) => {
  console.log('🔍 Modal open:', val);
  if (val) {
    console.log('🔍 Current step:', currentStep.value);
    console.log('🔍 Form data:', formData.value);
  }
});
```

#### **6. Ensure Z-Index is High Enough**
Update the backdrop z-index to ensure it's above all other elements:

```vue
<div
  v-if="isOpen"
  class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4"
>
```

#### **7. Add Explicit Heights to Step Containers**
Ensure each step has a minimum height:

```vue
<div class="space-y-6 mb-8 min-h-[400px]">
  <!-- step content -->
</div>
```

### **Testing Instructions:**

After making these changes:

1. **Build the assets:**
   ```bash
   npm run build
   ```

2. **Clear Laravel cache:**
   ```bash
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Test in production:**
   - Open Chrome DevTools (F12)
   - Click to open modal
   - Check Console tab for debug logs
   - Check Elements tab to see if modal HTML is rendered
   - Check Network tab for any failed asset loads

4. **If still not working:**
   - Take a screenshot of the Elements panel with modal open
   - Copy any console errors
   - Share the debug info from the yellow debug box

### **Expected Outcome:**
- Modal should open with all form fields visible
- Debug logs should show in console
- Step navigation should work
- Form inputs should be interactable

---

**Please implement these changes and test the modal in production.**

# 🔍 **Modal Code Review - Why Form Fields Aren't Visible**

After reviewing your modal code, I've identified several potential issues that could cause the form fields not to appear in production:

## 🚨 **Critical Issues Found**

### **1. Missing Teleport**
**Your modal is rendered in place, not teleported to body.** This is the #1 cause of modal issues in production!

```vue
<!-- ❌ Current: Modal rendered in component tree -->
<div v-if="isOpen" class="fixed inset-0">
  <!-- content -->
</div>

<!-- ✅ Should be teleported to body -->
<template>
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0">
      <!-- content -->
    </div>
  </Teleport>
</template>
```

### **2. Event Listener Memory Leak**
You're adding a global event listener but never removing it:

```javascript
// ❌ Missing cleanup
if (typeof window !== 'undefined') {
  window.addEventListener('keydown', handleKeydown); // Never removed!
}

// ✅ Add cleanup
onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
});
```

### **3. Conditional Rendering Issue**
The form fields might be conditionally rendered based on values that aren't properly initialized:

```vue
<!-- Step components might not be receiving proper props -->
<OrganizationStepBasicInfo
  v-if="currentStep === 1"
  :data="formData.basic"  <!-- Could be undefined if not initialized -->
  :errors="validationErrors.basic"
  @update="(field, value) => formData.basic[field] = value"
/>
```

## 🛠️ **Complete Fixed Modal Code**

Here's the corrected version of your modal:

```vue
<template>
  <Teleport to="body">
    <transition name="modal-fade">
      <div
        v-if="isOpen"
        class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4"
        @click="closeModal"
        :aria-hidden="!isOpen"
      >
        <!-- Modal container - stop click propagation -->
        <div
          class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative"
          @click.stop
          role="dialog"
          :aria-labelledby="isEducationView ? 'education-title' : 'form-title'"
          aria-modal="true"
        >
          <!-- Close button at top right (visible in both views) -->
          <button
            @click="closeModal"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors z-10 p-2 bg-white dark:bg-gray-800 rounded-full shadow-md"
            :aria-label="$t('common.close', { fallback: 'Close' })"
          >
            <span class="text-xl">✕</span>
          </button>

          <!-- Education Overlay View -->
          <div v-if="isEducationView" class="p-8 md:p-10">
            <h2 id="education-title" class="text-3xl font-bold text-gray-900 dark:text-white mb-8 pr-8">
              🏢 {{ $t('organisation.education.title', { fallback: 'Was ist eine Organisation?' }) }}
            </h2>

            <!-- Education content (rest remains same) -->
            <div class="space-y-6 mb-8">
              <!-- ... your existing education content ... -->
            </div>

            <button
              @click="nextStep"
              class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg
                     transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              {{ $t('organisation.education.start_cta', { fallback: 'Organisation jetzt gründen →' }) }}
            </button>
          </div>

          <!-- Form View -->
          <div v-else class="p-8 md:p-10">
            <h2 id="form-title" class="text-3xl font-bold text-gray-900 dark:text-white mb-2 pr-8">
              🏢 {{ $t('organisation.form.title', { fallback: 'Organisation gründen' }) }}
            </h2>
            
            <!-- Step indicator -->
            <p class="text-gray-600 dark:text-gray-400 mb-6">
              {{ $t('organisation.form.step', {
                current: currentStep,
                total: 3,
                fallback: `Schritt ${currentStep} von 3`
              }) }}
            </p>

            <!-- Progress bar -->
            <div class="mb-8">
              <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div
                  class="h-full bg-gradient-to-r from-blue-600 to-blue-500 transition-all duration-500"
                  :style="{ width: progressPercentage + '%' }"
                  role="progressbar"
                  :aria-valuenow="progressPercentage"
                  aria-valuemin="0"
                  aria-valuemax="100"
                ></div>
              </div>
            </div>

            <!-- Debug info - REMOVE AFTER TESTING -->
            <div class="mb-4 p-2 bg-yellow-100 text-xs">
              <p>Step: {{ currentStep }}, FormData basic exists: {{ !!formData?.basic }}</p>
              <p>FormData keys: {{ formData ? Object.keys(formData).join(', ') : 'none' }}</p>
            </div>

            <!-- Form steps with fallback content -->
            <div class="space-y-6 mb-8 min-h-[300px]">
              <!-- Step 1: Basic Info -->
              <div v-if="currentStep === 1">
                <template v-if="formData?.basic">
                  <OrganizationStepBasicInfo
                    :data="formData.basic"
                    :errors="validationErrors?.basic || {}"
                    @update="(field, value) => formData.basic[field] = value"
                  />
                </template>
                <div v-else class="p-4 bg-red-100 text-red-700 rounded">
                  ⚠️ Form data not initialized properly
                </div>
              </div>

              <!-- Step 2: Address -->
              <div v-if="currentStep === 2">
                <template v-if="formData?.address">
                  <OrganizationStepAddress
                    :data="formData.address"
                    :errors="validationErrors?.address || {}"
                    @update="(field, value) => formData.address[field] = value"
                  />
                </template>
              </div>

              <!-- Step 3: Representative -->
              <div v-if="currentStep === 3">
                <template v-if="formData?.representative">
                  <OrganizationStepRepresentative
                    :data="formData.representative"
                    :acceptance="formData.acceptance"
                    :errors="validationErrors?.representative || {}"
                    @update:representative="(field, value) => formData.representative[field] = value"
                    @update:acceptance="(field, value) => formData.acceptance[field] = value"
                  />
                </template>
              </div>
            </div>

            <!-- Error message -->
            <div v-if="submissionError" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
              <p class="text-red-700 dark:text-red-400 text-sm">⚠️ {{ submissionError }}</p>
            </div>

            <!-- Navigation buttons -->
            <FormNavigation
              :current-step="currentStep"
              :can-go-previous="canGoPrevious"
              :can-go-next="canGoNext"
              :is-submitting="isSubmitting"
              :show-education="showEducation"
              @previous="previousStep"
              @next="nextStep"
              @submit="submitForm"
            />
          </div>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup>
import { computed, inject, onMounted, onUnmounted, watch } from 'vue';
import EducationSection from './Steps/EducationSection.vue';
import OrganizationStepBasicInfo from './Steps/OrganizationStepBasicInfo.vue';
import OrganizationStepAddress from './Steps/OrganizationStepAddress.vue';
import OrganizationStepRepresentative from './Steps/OrganizationStepRepresentative.vue';
import FormNavigation from './Steps/FormNavigation.vue';

// INJECT the composable from parent
const organizationCreation = inject('organizationCreation');

// Debug: Check if injection worked
console.log('Modal injected organizationCreation:', organizationCreation);

// Use refs and values directly from the composable with fallbacks
const isModalOpen = organizationCreation?.isModalOpen || ref(false);
const currentStep = organizationCreation?.currentStep || ref(1);
const showEducation = organizationCreation?.showEducation || ref(true);
const formData = organizationCreation?.formData || ref({ basic: {}, address: {}, representative: {}, acceptance: {} });
const expandedSections = organizationCreation?.expandedSections || ref({});
const validationErrors = organizationCreation?.validationErrors || ref({});
const isSubmitting = organizationCreation?.isSubmitting || ref(false);
const submissionError = organizationCreation?.submissionError || ref(null);
const progressPercentage = organizationCreation?.progressPercentage || ref(0);
const canGoNext = organizationCreation?.canGoNext || ref(false);
const canGoPrevious = organizationCreation?.canGoPrevious || ref(false);

// Methods with fallbacks
const closeModal = organizationCreation?.closeModal || (() => {});
const nextStep = organizationCreation?.nextStep || (() => {});
const previousStep = organizationCreation?.previousStep || (() => {});
const submitForm = organizationCreation?.submitForm || (() => {});
const toggleSection = organizationCreation?.toggleSection || (() => {});

// Computed for template
const isOpen = computed(() => isModalOpen?.value || false);
const isEducationView = computed(() => showEducation?.value || false);

// Handle ESC key to close modal
const handleKeydown = (e) => {
  if (e.key === 'Escape' && isOpen.value) {
    closeModal();
  }
};

// Add/remove event listeners properly
onMounted(() => {
  console.log('Modal mounted');
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  console.log('Modal unmounted');
  window.removeEventListener('keydown', handleKeydown);
});

// Watch for modal open state
watch(isOpen, (val) => {
  console.log('Modal open state changed:', val);
  if (val) {
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

/* Ensure scroll on long content */
@media (max-height: 600px) {
  div[role="dialog"] {
    max-height: calc(100vh - 2rem);
  }
}

/* High contrast mode support */
@media (prefers-contrast: more) {
  div[role="dialog"] {
    border: 2px solid;
    border-color: currentColor;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .modal-fade-enter-active,
  .modal-fade-leave-active,
  div[role="progressbar"] {
    transition: none !important;
  }
}
</style>
```

## 📋 **Quick Checklist to Debug**

Add this temporary debug to see what's happening:

```javascript
// In your WelcomeDashboard.vue where you provide the composable
provide('organizationCreation', organizationCreation);
console.log('Provided organizationCreation:', organizationCreation);
console.log('Form data at provide:', organizationCreation.formData.value);
```

Then in your modal, add:
```javascript
// At the top of script setup
console.log('Modal received organizationCreation:', organizationCreation);
console.log('Form data in modal:', organizationCreation?.formData?.value);
```

## 🎯 **Most Likely Fixes**

1. **Add Teleport** - This is almost certainly the main issue
2. **Fix event listener cleanup** - Memory leaks can cause weird behavior
3. **Add fallbacks for injected values** - Prevents undefined errors
4. **Add debug logging** - To see what's actually happening

Try these fixes and let me know if the form fields appear!