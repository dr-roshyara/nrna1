<template>
  <span :class="badgeClasses" class="state-badge inline-flex items-center gap-1.5">
    <span class="state-icon" :aria-label="label">{{ icon }}</span>
    <span class="state-name">{{ label }}</span>
  </span>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  state: {
    type: String,
    required: true,
    validator: (v) => [
      'draft',
      'submitted_for_approval',
      'approved',
      'rejected',
      'setup',
      'ready_for_voting',
      'voting_active',
      'counting',
      'results_published',
      'archived',
    ].includes(v),
  },
  size: {
    type: String,
    default: 'md',
    validator: (v) => ['sm', 'md', 'lg'].includes(v),
  },
})

const { t } = useI18n()

const stateConfig = {
  draft: { icon: '📝', color: 'slate', messageKey: 'states.draft' },
  submitted_for_approval: { icon: '⏳', color: 'amber', messageKey: 'states.submitted_for_approval' },
  approved: { icon: '✅', color: 'emerald', messageKey: 'states.approved' },
  rejected: { icon: '❌', color: 'red', messageKey: 'states.rejected' },
  setup: { icon: '⚙️', color: 'blue', messageKey: 'states.setup' },
  ready_for_voting: { icon: '📋', color: 'purple', messageKey: 'states.ready_for_voting' },
  voting_active: { icon: '🗳️', color: 'emerald', messageKey: 'states.voting_active' },
  counting: { icon: '📊', color: 'orange', messageKey: 'states.counting' },
  results_published: { icon: '✅', color: 'green', messageKey: 'states.results_published' },
  archived: { icon: '📦', color: 'gray', messageKey: 'states.archived' },
}

const colorClasses = {
  slate: 'bg-slate-100 text-slate-800',
  amber: 'bg-amber-100 text-amber-800',
  blue: 'bg-blue-100 text-blue-800',
  purple: 'bg-purple-100 text-purple-800',
  emerald: 'bg-emerald-100 text-emerald-800',
  orange: 'bg-orange-100 text-orange-800',
  green: 'bg-green-100 text-green-800',
  red: 'bg-red-100 text-red-800',
  gray: 'bg-gray-100 text-gray-800',
}

const sizeClasses = {
  sm: 'px-2 py-1 text-xs font-semibold',
  md: 'px-3 py-1.5 text-sm font-semibold',
  lg: 'px-4 py-2 text-base font-semibold',
}

const icon = computed(() => stateConfig[props.state]?.icon)

const label = computed(() => {
  const config = stateConfig[props.state]
  if (!config) return props.state
  return t(config.messageKey, config.messageKey.split('.')[1] || '')
})

const badgeClasses = computed(() => {
  const config = stateConfig[props.state]
  const colorClass = colorClasses[config?.color || 'slate']
  const sizeClass = sizeClasses[props.size]
  return `${colorClass} ${sizeClass} rounded-full font-medium transition-all duration-200 inline-flex items-center gap-1.5`
})
</script>

<style scoped>
.state-badge {
  white-space: nowrap;
}

.state-icon {
  display: inline-block;
  font-size: 1.1em;
  line-height: 1;
}
</style>
