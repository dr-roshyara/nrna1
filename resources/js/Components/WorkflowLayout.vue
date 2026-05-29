<template>
  <div
    role="main"
    :aria-label="domain ? `${domain} workflow` : 'workflow'"
    class="min-h-screen bg-neutral-50"
  >
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

      <!-- Header zone -->
      <div class="mb-8">
        <slot name="header">
          <h1 v-if="title" class="text-3xl font-bold text-neutral-900">{{ title }}</h1>
          <p v-if="subtitle" class="mt-2 text-lg text-neutral-600">{{ subtitle }}</p>
        </slot>
      </div>

      <!-- Progress zone (only rendered when currentStep is provided) -->
      <div v-if="currentStep" class="mb-8">
        <WorkflowStepIndicator
          :currentStep="currentStep"
          :totalSteps="totalSteps"
          :stepLabels="stepLabels"
        />
      </div>

      <!-- Content zone -->
      <div class="bg-white rounded-xl border border-neutral-200 shadow-sm">
        <div class="p-6 sm:p-8">
          <slot />
        </div>

        <!-- Feedback zone (errors, warnings, success messages) -->
        <div v-if="$slots.feedback" class="px-6 sm:px-8 pb-4">
          <slot name="feedback" />
        </div>

        <!-- Actions zone (navigation buttons) -->
        <div
          v-if="$slots.actions"
          class="px-6 sm:px-8 py-5 border-t border-neutral-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3"
        >
          <slot name="actions" />
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'

defineProps({
  title:       { type: String, default: undefined },
  subtitle:    { type: String, default: undefined },
  currentStep: { type: Number, default: undefined },
  totalSteps:  { type: Number, default: 5 },
  stepLabels:  { type: Array,  default: undefined },
  // Open-ended string — any domain works: 'voting', 'election', 'membership',
  // 'ngо-board', 'regional-assembly', etc. Never validated or branched on.
  domain:      { type: String, default: undefined },
})
</script>
