<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="flex items-center gap-3">
    <!-- Large, accessible toggle button -->
    <button
      type="button"
      role="switch"
      :aria-checked="modelValue"
      :aria-label="modelValue ? 'Enabled (click to disable)' : 'Disabled (click to enable)'"
      @click="emit('update:modelValue', !modelValue)"
      @keydown.space.prevent="emit('update:modelValue', !modelValue)"
      class="relative inline-flex h-10 w-20 flex-shrink-0 items-center rounded-full transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-offset-2 cursor-pointer group"
      :class="modelValue
        ? 'bg-gradient-to-r from-teal-500 to-teal-600 focus:ring-teal-300 shadow-lg'
        : 'bg-gradient-to-r from-slate-300 to-slate-400 focus:ring-slate-300 shadow-md'"
    >
      <!-- Toggle circle with enhanced animation -->
      <span
        class="inline-block h-8 w-8 transform rounded-full bg-white shadow-lg transition-all duration-300 flex items-center justify-center"
        :class="modelValue ? 'translate-x-10' : 'translate-x-1'"
      >
        <!-- Icon inside toggle -->
        <svg
          v-if="modelValue"
          class="w-5 h-5 text-teal-600 transition-all"
          fill="currentColor"
          viewBox="0 0 20 20"
          aria-hidden="true"
        >
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <svg
          v-else
          class="w-5 h-5 text-slate-500 transition-all"
          fill="currentColor"
          viewBox="0 0 20 20"
          aria-hidden="true"
        >
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </span>

      <!-- Background animation on hover -->
      <span
        class="absolute inset-0 rounded-full transition-opacity duration-300 opacity-0 group-hover:opacity-20"
        :class="modelValue ? 'bg-white' : 'bg-slate-800'"
        aria-hidden="true"
      />
    </button>

    <!-- Status text label for clarity -->
    <span
      class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg font-bold text-sm transition-colors duration-300"
      :class="modelValue
        ? 'bg-teal-50 text-teal-900'
        : 'bg-slate-100 text-slate-700'"
    >
      <span
        class="w-2.5 h-2.5 rounded-full transition-all"
        :class="modelValue ? 'bg-teal-600' : 'bg-slate-500'"
        aria-hidden="true"
      ></span>
      {{ modelValue ? 'Enabled' : 'Disabled' }}
    </span>
  </div>
</template>

<style scoped>
/* Enhanced focus styles for keyboard navigation */
button:focus-visible {
  outline: 3px solid #0369a1;
  outline-offset: 2px;
}

/* Smooth transitions */
button {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effect */
button:hover:not(:disabled) {
  transform: scale(1.02);
}

/* Active state */
button:active:not(:disabled) {
  transform: scale(0.98);
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  button,
  span {
    transition: none !important;
  }
}

/* High contrast mode support */
@media (prefers-contrast: more) {
  button {
    border: 2px solid currentColor;
  }
}
</style>
