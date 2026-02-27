<template>
  <div
    :class="['election-badge', badgeClasses]"
    :title="tooltipText"
    :aria-label="ariaLabel"
  >
    <span class="badge-text">{{ badgeText }}</span>
    <div v-if="showTooltip" class="badge-tooltip">
      {{ tooltipText }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  electionType: {
    type: String,
    required: true,
    validator: (value) => ['demo', 'real'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  showTooltip: {
    type: Boolean,
    default: true
  }
})

const { t } = useI18n()

const badgeText = computed(() => {
  return t(`election.${props.electionType}_badge.text`)
})

const tooltipText = computed(() => {
  return t(`election.${props.electionType}_badge.tooltip`)
})

const badgeClasses = computed(() => ({
  'badge-demo': props.electionType === 'demo',
  'badge-real': props.electionType === 'real',
  'badge-sm': props.size === 'sm',
  'badge-md': props.size === 'md',
  'badge-lg': props.size === 'lg'
}))

const ariaLabel = computed(() => {
  return `${badgeText.value} - ${tooltipText.value}`
})
</script>

<style scoped>
@reference "../../../css/app.css";

.election-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.375rem 0.75rem;
  border-radius: 0.375rem;
  font-weight: 600;
  font-size: 0.875rem;
  position: relative;
  white-space: nowrap;
  user-select: none;
  transition: all 0.2s ease;
  border: 1px solid;
}

.badge-demo {
  @apply bg-blue-100 text-blue-800 border-blue-300;
}

.badge-demo:hover {
  @apply bg-blue-200 border-blue-400;
}

.badge-real {
  @apply bg-green-100 text-green-800 border-green-300;
}

.badge-real:hover {
  @apply bg-green-200 border-green-400;
}

.badge-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.badge-md {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
}

.badge-lg {
  padding: 0.5rem 1rem;
  font-size: 1rem;
}

.badge-text {
  font-weight: 700;
  letter-spacing: 0.05em;
}

.badge-tooltip {
  position: absolute;
  bottom: calc(100% + 0.5rem);
  left: 50%;
  transform: translateX(-50%);
  background-color: rgba(0, 0, 0, 0.9);
  color: white;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 500;
  white-space: nowrap;
  z-index: 50;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.2s ease;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.election-badge:hover .badge-tooltip {
  opacity: 1;
}

.badge-tooltip::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 4px solid rgba(0, 0, 0, 0.9);
}

@media (max-width: 640px) {
  .election-badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }

  .badge-tooltip {
    font-size: 0.7rem;
    padding: 0.375rem 0.5rem;
  }
}
</style>
