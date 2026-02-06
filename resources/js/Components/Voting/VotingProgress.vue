<template>
  <div class="mb-8 text-center">
    <div class="inline-flex items-center justify-center w-full max-w-md mx-auto mb-4">
      <!-- Progress Bar -->
      <div class="flex-1 h-1 bg-gray-200 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-green-500 via-emerald-500 to-green-500"
             :style="{ width: progressPercentage + '%' }"></div>
      </div>
      <!-- Step Indicator -->
      <div class="mx-4 px-4 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full text-sm font-medium">
        Step {{ currentStep }}/5
      </div>
      <!-- Right progress bar -->
      <div class="flex-1 h-1 bg-gray-200 rounded-full"></div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  /**
   * Current step in the voting process (1-5)
   * @type {Number}
   */
  currentStep: {
    type: Number,
    required: true,
    validator: (value) => value >= 1 && value <= 5
  }
})

/**
 * Computed: Calculate progress percentage based on current step
 * Step 1: 20% (1/5)
 * Step 2: 40% (2/5)
 * Step 3: 60% (3/5)
 * Step 4: 80% (4/5)
 * Step 5: 100% (5/5)
 */
const progressPercentage = computed(() => {
  return (props.currentStep / 5) * 100
})
</script>

<style scoped>
/* Ensure smooth progress bar animation */
.h-full {
  transition: width 0.3s ease-in-out;
}
</style>
