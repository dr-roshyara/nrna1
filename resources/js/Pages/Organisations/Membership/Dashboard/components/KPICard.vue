<template>
  <a :href="href"
     :aria-label="`${label}: ${value}`"
     class="group block bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg hover:border-purple-200 transition-all focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
    <div class="flex items-start justify-between gap-3">
      <div class="flex-1 min-w-0">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider truncate" aria-hidden="true">{{ label }}</p>
        <p class="text-2xl font-bold text-slate-800 mt-1" aria-hidden="true">{{ value }}</p>
      </div>
      <div v-if="icon" class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" :class="iconBgClass" aria-hidden="true">
        <component :is="icon" class="w-5 h-5" :class="iconColorClass" />
      </div>
    </div>
    <div v-if="trend" class="mt-3 flex items-center gap-1">
      <span class="inline-flex items-center gap-1 text-xs font-medium px-1.5 py-0.5 rounded-full" :class="trendBadgeClass">
        <span aria-hidden="true">{{ trend === 'warning' ? '⚠' : trend === 'danger' ? '!' : '↑' }}</span>
        {{ trendLabel }}
      </span>
    </div>
    <p class="mt-2 text-xs text-slate-400 group-hover:text-purple-500 transition-colors" aria-hidden="true">
      {{ linkLabel }} →
    </p>
  </a>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label:     { type: String, required: true },
  value:     { type: [String, Number], required: true },
  href:      { type: String, required: true },
  icon:      { type: Object, default: null },
  color:     { type: String, default: 'purple' },
  trend:     { type: String, default: null },   // 'warning' | 'danger' | null
  trendLabel:{ type: String, default: '' },
  linkLabel: { type: String, default: 'View' },
})

const colorMap = {
  purple: { bg: 'bg-purple-100', text: 'text-purple-600' },
  blue:   { bg: 'bg-primary-100',   text: 'text-primary-600' },
  amber:  { bg: 'bg-amber-100',  text: 'text-amber-600' },
  orange: { bg: 'bg-orange-100', text: 'text-orange-600' },
  green:  { bg: 'bg-green-100',  text: 'text-green-600' },
}

const iconBgClass   = computed(() => colorMap[props.color]?.bg   ?? 'bg-slate-100')
const iconColorClass= computed(() => colorMap[props.color]?.text ?? 'text-slate-600')

const trendBadgeClass = computed(() => {
  if (props.trend === 'danger')  return 'bg-danger-100 text-danger-700'
  if (props.trend === 'warning') return 'bg-amber-100 text-amber-700'
  return 'bg-green-100 text-green-700'
})
</script>

