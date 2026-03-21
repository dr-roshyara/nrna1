<template>
  <component
    :is="href ? 'a' : 'button'"
    :href="href"
    :type="href ? undefined : type"
    :disabled="disabled || loading"
    :class="classes"
    v-bind="$attrs"
  >
    <svg v-if="loading" class="animate-spin -ml-0.5 flex-shrink-0" :class="iconSize" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'primary' }, // primary | success | warning | danger | outline | ghost
  size:    { type: String, default: 'md' },       // sm | md | lg
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  href:    { type: String, default: null },
  type:    { type: String, default: 'button' },
})

const variantMap = {
  primary: 'bg-blue-600 hover:bg-blue-700 text-white border-transparent focus-visible:ring-blue-500',
  success: 'bg-emerald-600 hover:bg-emerald-700 text-white border-transparent focus-visible:ring-emerald-500',
  warning: 'bg-amber-500 hover:bg-amber-600 text-white border-transparent focus-visible:ring-amber-400',
  danger:  'bg-red-600 hover:bg-red-700 text-white border-transparent focus-visible:ring-red-500',
  outline: 'bg-white hover:bg-gray-50 text-gray-700 border-gray-300 focus-visible:ring-blue-500',
  ghost:   'bg-transparent hover:bg-gray-100 text-gray-600 border-transparent focus-visible:ring-gray-400',
}

const sizeMap = {
  sm: 'text-xs px-3 py-1.5 gap-1.5',
  md: 'text-sm px-4 py-2 gap-2',
  lg: 'text-base px-5 py-2.5 gap-2.5',
}

const iconSize = computed(() => ({
  sm: 'w-3.5 h-3.5',
  md: 'w-4 h-4',
  lg: 'w-5 h-5',
}[props.size]))

const classes = computed(() => [
  'inline-flex items-center justify-center font-semibold border rounded-lg transition-colors duration-150',
  'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
  variantMap[props.variant] ?? variantMap.primary,
  sizeMap[props.size] ?? sizeMap.md,
  (props.disabled || props.loading) ? 'opacity-50 cursor-not-allowed pointer-events-none' : 'cursor-pointer',
])
</script>
