<template>
  <div class="mb-8 text-center">
    <div class="inline-flex items-center justify-center w-full max-w-md mx-auto mb-4">
      <!-- Progress Bar -->
      <div class="flex-1 h-1 bg-gray-200 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-green-500 via-emerald-500 to-green-500"
             :style="{ width: progressPercentage + '%' }"></div>
      </div>
      <!-- Step Indicator -->
      <div class="mx-4 px-4 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full text-sm font-medium whitespace-nowrap">
        {{ $t('workflow.step', { current: currentStep, total: totalSteps }, `Step ${currentStep}/${totalSteps}`) }}
      </div>
      <!-- Right progress bar -->
      <div class="flex-1 h-1 bg-gray-200 rounded-full"></div>
    </div>

    <!-- Optional: Step Title -->
    <div v-if="stepTitle" class="text-sm text-gray-600 mt-2">
      {{ stepTitle }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { getWorkflow, calculateProgress, getStepTranslation } from '@/Config/WorkflowSteps'

const { locale } = useI18n()

const props = defineProps({
  /**
   * Name of the workflow (e.g., 'VOTING', 'DELEGATE_VOTING', 'FINANCE_INCOME')
   * @type {String}
   */
  workflow: {
    type: String,
    required: true
  },

  /**
   * Current step in the workflow (1-N)
   * @type {Number}
   */
  currentStep: {
    type: Number,
    required: true
  },

  /**
   * Show step title/name
   * @type {Boolean}
   */
  showTitle: {
    type: Boolean,
    default: true
  }
})

/**
 * Computed: Get workflow configuration
 */
const workflowConfig = computed(() => {
  return getWorkflow(props.workflow)
})

/**
 * Computed: Total steps in the workflow
 */
const totalSteps = computed(() => {
  return workflowConfig.value?.totalSteps || 0
})

/**
 * Computed: Calculate progress percentage
 */
const progressPercentage = computed(() => {
  return calculateProgress(props.workflow, props.currentStep)
})

/**
 * Computed: Get step title/name in current locale
 */
const stepTitle = computed(() => {
  if (!props.showTitle || !workflowConfig.value) return ''
  return getStepTranslation(props.workflow, props.currentStep, locale.value)
})

/**
 * Validate workflow and step
 */
if (!workflowConfig.value) {
  console.warn(`[WorkflowProgress] Unknown workflow: ${props.workflow}`)
}

if (props.currentStep > totalSteps.value) {
  console.warn(
    `[WorkflowProgress] Step ${props.currentStep} exceeds total steps (${totalSteps.value}) for workflow ${props.workflow}`
  )
}
</script>

<style scoped>
/* Ensure smooth progress bar animation */
.h-full {
  transition: width 0.3s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .whitespace-nowrap {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
  }
}
</style>
