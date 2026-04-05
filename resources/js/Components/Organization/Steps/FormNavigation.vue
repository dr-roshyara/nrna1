<template>
  <div class="flex gap-3 pt-4 border-t-2 border-gray-200 dark:border-gray-700">
    <!-- Back Button -->
    <button
      v-if="canGoPrevious"
      @click="$emit('previous')"
      :disabled="isSubmitting"
      class="px-6 py-3 text-gray-700 dark:text-gray-300 font-semibold
             border-2 border-gray-300 dark:border-gray-600
             hover:bg-gray-100 dark:hover:bg-gray-800/50
             disabled:opacity-50 disabled:cursor-not-allowed
             rounded-lg transition-colors duration-200
             focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500
             dark:focus:ring-offset-gray-900"
      :aria-label="$t('common.back', { fallback: 'Go back' })"
    >
      ← {{ $t('common.back', { fallback: 'Zurück' }) }}
    </button>

    <!-- Spacer -->
    <div class="flex-1"></div>

    <!-- Next/Submit Button -->
    <button
      @click="currentStep === 3 && !showEducation ? $emit('submit') : $emit('next')"
      :disabled="!canGoNext || isSubmitting"
      class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-600/50
             text-white font-semibold rounded-lg
             disabled:opacity-75 disabled:cursor-not-allowed
             transition-all duration-200
             focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
             dark:focus:ring-offset-gray-900
             flex items-center gap-2"
      :aria-label="currentStep === 3 && !showEducation ? $t('common.submit', { fallback: 'Submit' }) : $t('common.next', { fallback: 'Next' })"
    >
      <span v-if="isSubmitting" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
      {{ currentStep === 3 && !showEducation ? $t('common.submit', { fallback: 'Gründen' }) : $t('common.next', { fallback: 'Weiter' }) }}
      →
    </button>
  </div>
</template>

<script setup>
defineProps({
  currentStep: {
    type: Number,
    required: true,
  },
  canGoPrevious: {
    type: Boolean,
    required: true,
  },
  canGoNext: {
    type: Boolean,
    required: true,
  },
  isSubmitting: {
    type: Boolean,
    default: false,
  },
  showEducation: {
    type: Boolean,
    required: true,
  },
});

defineEmits(['previous', 'next', 'submit']);
</script>

<style scoped>
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@media (prefers-reduced-motion: reduce) {
  .animate-spin {
    animation: none;
  }
}
</style>
