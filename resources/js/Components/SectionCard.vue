<template>
  <div :class="wrapperClass">
    <div v-if="title || $slots.actions" class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div v-if="$slots.icon" :class="iconWrapClass">
          <slot name="icon" />
        </div>
        <div>
          <h2 class="text-lg font-semibold text-slate-800">{{ title }}</h2>
          <p v-if="subtitle" class="text-sm text-slate-500 mt-0.5">{{ subtitle }}</p>
        </div>
      </div>
      <div v-if="$slots.actions" class="flex items-center gap-2">
        <slot name="actions" />
      </div>
    </div>
    <slot />
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title:    { type: String, default: '' },
  subtitle: { type: String, default: '' },
  variant:  { type: String, default: 'default' }, // default | warning | success | info
  padding:  { type: String, default: 'lg' },       // sm | md | lg
})

const variantMap = {
  default: 'bg-white border-slate-200',
  warning: 'bg-amber-50 border-amber-200',
  success: 'bg-emerald-50 border-emerald-200',
  info:    'bg-blue-50 border-blue-200',
}

const iconVariantMap = {
  default: 'bg-slate-100 text-slate-500',
  warning: 'bg-amber-100 text-amber-600',
  success: 'bg-emerald-100 text-emerald-600',
  info:    'bg-blue-100 text-blue-600',
}

const paddingMap = {
  sm: 'p-4',
  md: 'p-6',
  lg: 'p-8',
}

const wrapperClass = computed(() => [
  'rounded-2xl border shadow-sm',
  variantMap[props.variant] ?? variantMap.default,
  paddingMap[props.padding] ?? paddingMap.lg,
])

const iconWrapClass = computed(() => [
  'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0',
  iconVariantMap[props.variant] ?? iconVariantMap.default,
])
</script>
