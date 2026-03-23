<template>
  <div :class="classes" v-bind="$attrs">
    <slot />
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  mode:    { type: String,  default: 'default' }, // default | editorial | admin
  variant: { type: String,  default: 'default' }, // default | primary | success | warning | danger
  padding: { type: String,  default: 'md' },      // none | sm | md | lg
  hover:   { type: Boolean, default: false },
})

const modeMap = {
  editorial: 'font-serif bg-amber-50 border border-amber-200 shadow-md',
  admin:     'font-sans  bg-white    border border-neutral-200 shadow-sm',
  default:   'bg-white               border border-neutral-200 shadow-sm',
}

const variantMap = {
  default: '',
  primary: 'bg-primary-50  !border-primary-200',
  success: 'bg-green-50    !border-green-200',
  warning: 'bg-amber-50    !border-amber-200',
  danger:  'bg-red-50      !border-red-200',
}

const paddingMap = {
  none: 'p-0',
  sm:   'p-4',
  md:   'p-6',
  lg:   'p-8',
}

const classes = computed(() => [
  'rounded-xl transition-all duration-200',
  modeMap[props.mode]       ?? modeMap.default,
  variantMap[props.variant] ?? '',
  paddingMap[props.padding] ?? paddingMap.md,
  props.hover ? 'hover:shadow-lg hover:-translate-y-0.5 cursor-pointer' : '',
])
</script>
