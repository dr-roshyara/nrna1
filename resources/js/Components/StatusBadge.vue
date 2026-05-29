<template>
  <span :class="classes" role="status" :aria-label="`Status: ${displayLabel}`">
    <span :class="dotClass" aria-hidden="true" />
    {{ displayLabel }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true },
  size:   { type: String, default: 'sm' },   // sm | md
  label:  { type: String, default: undefined }, // overrides default label; required for unknown statuses
})

// Election lifecycle statuses (canonical election domain)
const defaultStatuses = {
  draft:                  { label: 'Draft',              classes: 'bg-slate-50 text-slate-700 border-slate-200',     dot: 'bg-slate-400' },
  submitted_for_approval: { label: 'Pending Approval',   classes: 'bg-amber-50 text-amber-700 border-amber-200',     dot: 'bg-amber-400' },
  approved:               { label: 'Approved',           classes: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-400' },
  rejected:               { label: 'Rejected',           classes: 'bg-red-50 text-red-700 border-red-200',           dot: 'bg-red-400' },
  setup_administration:   { label: 'Setting Up',         classes: 'bg-blue-50 text-blue-700 border-blue-200',        dot: 'bg-blue-400' },
  setup_nomination:       { label: 'Nominations Open',   classes: 'bg-indigo-50 text-indigo-700 border-indigo-200',  dot: 'bg-indigo-400' },
  ready_for_voting:       { label: 'Ready for Voting',   classes: 'bg-purple-50 text-purple-700 border-purple-200',  dot: 'bg-purple-400' },
  voting_active:          { label: 'Voting Active',      classes: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-400 animate-pulse' },
  counting:               { label: 'Counting',           classes: 'bg-orange-50 text-orange-700 border-orange-200',  dot: 'bg-orange-400' },
  results_published:      { label: 'Results Published',  classes: 'bg-green-50 text-green-700 border-green-200',     dot: 'bg-green-400' },
  archived:               { label: 'Archived',           classes: 'bg-gray-100 text-gray-400 border-gray-200',       dot: 'bg-gray-300' },
  suspended:              { label: 'Suspended',          classes: 'bg-red-50 text-red-700 border-red-200',           dot: 'bg-red-500 animate-pulse' },

  // General statuses (voter/member/participation domain)
  voted:                  { label: 'Voted',              classes: 'bg-green-50 text-green-700 border-green-200',     dot: 'bg-green-400' },
  verified:               { label: 'Verified',           classes: 'bg-green-50 text-green-700 border-green-200',     dot: 'bg-green-400' },
  active:                 { label: 'Active',             classes: 'bg-primary-50 text-primary-700 border-primary-200', dot: 'bg-primary-400' },
  pending:                { label: 'Pending',            classes: 'bg-amber-50 text-amber-700 border-amber-200',     dot: 'bg-amber-400' },
  inactive:               { label: 'Inactive',           classes: 'bg-neutral-100 text-neutral-500 border-neutral-200', dot: 'bg-neutral-400' },
  warning:                { label: 'Warning',            classes: 'bg-amber-50 text-amber-700 border-amber-200',     dot: 'bg-amber-400' },
}

// Unknown statuses render as neutral with caller-supplied label.
// This allows custom org statuses without editing this file:
//   <StatusBadge status="regional-delegate" label="Regional Delegate" />
const config = computed(() =>
  defaultStatuses[props.status] ?? {
    label:   props.label ?? props.status,
    classes: 'bg-neutral-100 text-neutral-600 border-neutral-200',
    dot:     'bg-neutral-400',
  }
)

const displayLabel = computed(() => props.label ?? config.value.label)

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
