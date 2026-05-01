<template>
  <component
    :is="as"
    :type="as === 'button' ? type : undefined"
    :href="as === 'a' ? href : undefined"
    :disabled="as === 'button' ? (disabled || loading) : undefined"
    :class="classes"
    :aria-busy="loading || undefined"
    :aria-disabled="(disabled || loading) || undefined"
    v-bind="$attrs"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4 shrink-0"
      fill="none"
      viewBox="0 0 24 24"
      aria-hidden="true"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
    </svg>
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  as:       { type: String,  default: 'button' }, // 'button' | 'a'
  href:     { type: String,  default: undefined },
  variant:  { type: String,  default: 'primary' }, // primary | secondary | outline | ghost | danger | accent | success
  size:     { type: String,  default: 'md' },       // sm | md | lg
  loading:  { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  type:     { type: String,  default: 'button' },
})

const variantMap = {
  primary:   'bg-primary-600   hover:bg-primary-700   text-white shadow-sm focus:ring-primary-500',
  secondary: 'bg-neutral-600   hover:bg-neutral-700   text-white shadow-sm focus:ring-neutral-500',
  outline:   'border border-neutral-300 bg-white hover:bg-neutral-50 text-neutral-700 focus:ring-neutral-400',
  ghost:     'hover:bg-neutral-100 text-neutral-700 focus:ring-neutral-400',
  danger:    'bg-danger-600    hover:bg-danger-700    text-white shadow-sm focus:ring-danger-500',
  accent:    'bg-accent-600    hover:bg-accent-700    text-white shadow-sm focus:ring-accent-500',
  warning:   'bg-accent-600    hover:bg-accent-700    text-white shadow-sm focus:ring-accent-500',
  success:   'bg-success-600   hover:bg-success-700   text-white shadow-sm focus:ring-success-500',
}

const sizeMap = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-4 py-2 text-base',
  lg: 'px-6 py-3 text-lg',
}

const classes = computed(() => [
  'inline-flex items-center justify-center font-medium rounded-lg',
  'transition-all duration-150',
  'focus:outline-none focus:ring-2 focus:ring-offset-2',
  'disabled:opacity-50 disabled:cursor-not-allowed',
  variantMap[props.variant] ?? variantMap.primary,
  sizeMap[props.size] ?? sizeMap.md,
  props.loading ? 'cursor-wait' : '',
])
</script>
