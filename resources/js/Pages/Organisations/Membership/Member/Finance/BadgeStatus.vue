<template>
  <span :class="['badge', `badge-${status}`, `size-${size}`]">
    <span class="badge-icon">
      <svg v-if="status === 'paid'" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
      </svg>
      <svg v-else-if="status === 'pending'" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
      </svg>
      <svg v-else-if="status === 'overdue'" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
      </svg>
      <svg v-else-if="status === 'waived'" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
      <svg v-else class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16z" clip-rule="evenodd" />
      </svg>
    </span>
    <span class="badge-text">
      {{ statusLabel }}
    </span>
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true,
    validator: (value) => ['paid', 'pending', 'overdue', 'waived', 'draft'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  translations: {
    type: Object,
    default: () => ({
      paid: 'Paid',
      pending: 'Pending',
      overdue: 'Overdue',
      waived: 'Waived',
      draft: 'Draft'
    })
  }
})

const statusLabel = computed(() => {
  return props.translations[props.status] || props.status.charAt(0).toUpperCase() + props.status.slice(1)
})
</script>

<style scoped>
.badge {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  font-weight: 500;
  border-radius: 0.375rem;
  white-space: nowrap;
}

/* Size variants */
.badge.size-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.badge.size-md {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
}

.badge.size-lg {
  padding: 0.5rem 1rem;
  font-size: 1rem;
}

/* Status variants */
.badge-paid {
  background-color: #dcfce7;
  color: #14532d;
  border: 1px solid #86efac;
}

.badge-pending {
  background-color: #fef3c7;
  color: #78350f;
  border: 1px solid #fcd34d;
}

.badge-overdue {
  background-color: #fef2f2;
  color: #7c2d12;
  border: 1px solid #fecaca;
}

.badge-waived {
  background-color: #f0fdf4;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.badge-draft {
  background-color: #f3f4f6;
  color: #374151;
  border: 1px solid #d1d5db;
}

.badge-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.badge-icon :deep(svg) {
  width: 1em;
  height: 1em;
}

.badge-text {
  font-variant-numeric: tabular-nums;
}
</style>
