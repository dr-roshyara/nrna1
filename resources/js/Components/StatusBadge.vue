<template>
  <span :class="classes" role="status" :aria-label="`Status: ${status}`">
    <span :class="dotClass" aria-hidden="true" />
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true },
  size: { type: String, default: 'sm' }, // sm | md
})

const map = {
  planned:   { label: 'Planned',   classes: 'bg-amber-50 text-amber-700 border-amber-200', dot: 'bg-amber-400' },
  active:    { label: 'Active',    classes: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-400 animate-pulse' },
  completed: { label: 'Completed', classes: 'bg-slate-100 text-slate-600 border-slate-200', dot: 'bg-slate-400' },
  archived:  { label: 'Archived',  classes: 'bg-gray-100 text-gray-400 border-gray-200', dot: 'bg-gray-300' },
}

const config = computed(() => map[props.status] ?? map.archived)
const label = computed(() => config.value.label)

const classes = computed(() => [
  'inline-flex items-center gap-1.5 font-semibold border rounded-full',
  config.value.classes,
  props.size === 'md' ? 'text-sm px-3 py-1' : 'text-xs px-2.5 py-0.5',
])

const dotClass = computed(() => [
  'inline-block rounded-full flex-shrink-0',
  config.value.dot,
  props.size === 'md' ? 'w-2 h-2' : 'w-1.5 h-1.5',
])
</script>
