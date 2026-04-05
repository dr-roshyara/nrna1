<template>
  <span :class="badgeClass" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize">
    <span class="sr-only">Status: </span>{{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: { type: String, required: true },
})

const statusMap = {
  draft:        { class: 'bg-slate-100 text-slate-600',   label: 'Draft' },
  submitted:    { class: 'bg-blue-100 text-blue-700',     label: 'Submitted' },
  under_review: { class: 'bg-yellow-100 text-yellow-700', label: 'Under Review' },
  approved:     { class: 'bg-green-100 text-green-800',   label: 'Approved' },
  rejected:     { class: 'bg-red-100 text-red-700',       label: 'Rejected' },
  active:       { class: 'bg-green-100 text-green-800',   label: 'Active' },
  expired:      { class: 'bg-orange-100 text-orange-700', label: 'Expired' },
  ended:        { class: 'bg-slate-100 text-slate-600',   label: 'Ended' },
  pending:      { class: 'bg-yellow-100 text-yellow-700', label: 'Pending' },
}

const entry = computed(() => statusMap[props.status] ?? { class: 'bg-slate-100 text-slate-600', label: props.status })
const badgeClass = computed(() => entry.value.class)
const label      = computed(() => entry.value.label)
</script>
