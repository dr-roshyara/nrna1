<template>
  <div class="mb-12 max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between">
      <!-- Step Nodes -->
      <template v-for="(step, index) in totalSteps" :key="`step-${index}`">
        <!-- Step Circle -->
        <div class="flex flex-col items-center flex-1">
          <!-- Circle Button -->
          <div
            :class="getStepClasses(step)"
            class="w-12 h-12 rounded-full text-white flex items-center justify-center font-bold text-lg shadow-md transition-all duration-300"
          >
            {{ step }}
          </div>
          <!-- Step Title -->
          <span :class="getStepTextClasses(step)" class="text-sm mt-3 font-semibold text-center max-w-[100px]">
            {{ getStepTitle(step) }}
          </span>
        </div>

        <!-- Connecting Line (not after last step) -->
        <div v-if="index < totalSteps - 1" :class="getLineClasses(step)" class="flex-1 h-1 mx-2 mt-4 transition-all duration-300"></div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { getWorkflow, getStepTranslation } from '@/Config/WorkflowSteps'

const { locale } = useI18n()

const props = defineProps({
  /**
   * Name of the workflow (e.g., 'VOTING', 'DELEGATE_VOTING')
   * @type {String}
   */
  workflow: {
    type: String,
    required: true
  },

  /**
   * Current step number (1-N)
   * @type {Number}
   */
  currentStep: {
    type: Number,
    required: true
  }
})

/**
 * Computed: Get workflow configuration
 */
const workflowConfig = computed(() => {
  return getWorkflow(props.workflow)
})

/**
 * Computed: Total steps in workflow
 */
const totalSteps = computed(() => {
  const steps = []
  if (workflowConfig.value) {
    for (let i = 1; i <= workflowConfig.value.totalSteps; i++) {
      steps.push(i)
    }
  }
  return steps
})

/**
 * Determine if a step is completed, current, or pending
 */
function getStepStatus(step) {
  if (step < props.currentStep) return 'completed'
  if (step === props.currentStep) return 'current'
  return 'pending'
}

/**
 * Get CSS classes for step circle based on status
 */
function getStepClasses(step) {
  const status = getStepStatus(step)

  switch (status) {
    case 'completed':
      return 'bg-green-600 hover:bg-green-700'
    case 'current':
      return 'bg-blue-600 hover:bg-blue-700 ring-4 ring-blue-200'
    case 'pending':
      return 'bg-gray-300 text-gray-600 hover:bg-gray-400'
    default:
      return 'bg-gray-300'
  }
}

/**
 * Get CSS classes for step title text based on status
 */
function getStepTextClasses(step) {
  const status = getStepStatus(step)

  switch (status) {
    case 'completed':
      return 'text-green-600'
    case 'current':
      return 'text-blue-600'
    case 'pending':
      return 'text-gray-500'
    default:
      return 'text-gray-500'
  }
}

/**
 * Get CSS classes for connecting line based on status
 */
function getLineClasses(step) {
  const nextStatus = getStepStatus(step + 1)

  // If current step has passed this line, make it green
  if (step < props.currentStep) {
    return 'bg-green-200'
  }

  // If we're on current step or before pending, make it gray
  return 'bg-gray-200'
}

/**
 * Get step title/name in current locale
 */
function getStepTitle(step) {
  if (!workflowConfig.value) return `Step ${step}`
  return getStepTranslation(props.workflow, step, locale.value)
}

/**
 * Validate workflow and step on mount
 */
if (!workflowConfig.value) {
  console.warn(`[WorkflowStepIndicator] Unknown workflow: ${props.workflow}`)
}

if (props.currentStep > totalSteps.value.length) {
  console.warn(
    `[WorkflowStepIndicator] Step ${props.currentStep} exceeds total steps (${totalSteps.value.length}) for workflow ${props.workflow}`
  )
}
</script>

<style scoped>
/* Smooth transitions for step status changes */
.transition-all {
  transition: all 0.3s ease-in-out;
}

/* Responsive adjustments for mobile */
@media (max-width: 768px) {
  :deep(.flex-1) {
    min-width: 40px;
  }

  .w-12 {
    width: 40px;
    height: 40px;
    font-size: 14px;
  }

  .text-sm {
    font-size: 11px;
  }

  .max-w-\[100px\] {
    max-width: 70px;
  }
}

@media (max-width: 640px) {
  .w-12 {
    width: 32px;
    height: 32px;
    font-size: 12px;
  }

  .text-sm {
    font-size: 10px;
  }

  .max-w-\[100px\] {
    max-width: 60px;
  }

  .h-1 {
    display: none;
  }
}
</style>
