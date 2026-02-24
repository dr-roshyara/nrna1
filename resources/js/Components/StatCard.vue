<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden"
    :aria-label="ariaLabel"
  >
    <div class="p-4 sm:p-6">
      <div class="flex items-baseline justify-between">
        <div class="flex-1">
          <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">
            {{ label }}
          </p>
          <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mt-2 sm:mt-3">
            {{ value }}
          </p>
        </div>
        <div v-if="icon" class="ml-4">
          <!-- Users Icon -->
          <svg v-if="icon === 'users'" class="w-12 h-12 sm:w-14 sm:h-14 text-indigo-100 dark:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 4.354V3m0 1.354a4 4 0 110 8.048m0-8.048v1.354m0-1.354A4.353 4.353 0 0018.335 9c.462 0 .926.067 1.381.195m-8.716 2.156l.881-.88a4.5 4.5 0 10-6.364 6.364l.881-.88A4.5 4.5 0 0013.5 15.5m6.364-6.364l.881.881A4.5 4.5 0 0120 9a4.5 4.5 0 00-6.364-6.364l-.881.881A4.5 4.5 0 008.5 8.5" />
          </svg>

          <!-- Clipboard List Icon -->
          <svg v-else-if="icon === 'clipboard-list'" class="w-12 h-12 sm:w-14 sm:h-14 text-indigo-100 dark:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>

          <!-- Calendar Icon -->
          <svg v-else-if="icon === 'calendar'" class="w-12 h-12 sm:w-14 sm:h-14 text-indigo-100 dark:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>

          <!-- Shield Check Icon -->
          <svg v-else-if="icon === 'shield-check'" class="w-12 h-12 sm:w-14 sm:h-14 text-indigo-100 dark:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7.784-4.817a8 8 0 01.946 11.26M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>

          <!-- Check Circle Icon -->
          <svg v-else-if="icon === 'check'" class="w-12 h-12 sm:w-14 sm:h-14 text-indigo-100 dark:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>

          <!-- Chart Bar Icon (default) -->
          <svg v-else class="w-12 h-12 sm:w-14 sm:h-14 text-indigo-100 dark:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  icon: {
    type: String,
    default: 'chart'
  },
  ariaLabel: {
    type: String,
    default: ''
  }
});

const ariaLabel = computed(() => {
  return props.ariaLabel || `${props.label}: ${props.value}`;
});
</script>
