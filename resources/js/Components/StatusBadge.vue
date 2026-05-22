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
  draft:                  { label: 'Draft',                classes: 'bg-slate-50 text-slate-700 border-slate-200', dot: 'bg-slate-400' },
  submitted_for_approval: { label: 'Pending Approval',     classes: 'bg-amber-50 text-amber-700 border-amber-200', dot: 'bg-amber-400' },
  approved:               { label: 'Approved',             classes: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-400' },
  rejected:               { label: 'Rejected',             classes: 'bg-red-50 text-red-700 border-red-200', dot: 'bg-red-400' },
  setup:                  { label: 'Setup',                classes: 'bg-blue-50 text-blue-700 border-blue-200', dot: 'bg-blue-400' },
  ready_for_voting:       { label: 'Ready for Voting',     classes: 'bg-purple-50 text-purple-700 border-purple-200', dot: 'bg-purple-400' },
  voting_active:          { label: 'Voting Active',        classes: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-400 animate-pulse' },
  counting:               { label: 'Counting',             classes: 'bg-orange-50 text-orange-700 border-orange-200', dot: 'bg-orange-400' },
  results_published:      { label: 'Results Published',    classes: 'bg-green-50 text-green-700 border-green-200', dot: 'bg-green-400' },
  archived:               { label: 'Archived',             classes: 'bg-gray-100 text-gray-400 border-gray-200', dot: 'bg-gray-300' },
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
