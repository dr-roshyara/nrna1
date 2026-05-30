<template>
  <div class="progress-root" :class="wrapperClass" role="progressbar" v-bind="progressAttrs">
    <!-- Label + Value row -->
    <div v-if="showLabel || label" class="progress-header">
      <span v-if="label" class="progress-label">{{ label }}</span>
      <span class="progress-value">{{ displayValue }}</span>
    </div>

    <!-- Track -->
    <div class="progress-track" :class="sizeClass">
      <div
        class="progress-fill"
        :class="[colorClass, { 'progress-fill--animated': animated }]"
        :style="{ width: percent + '%' }"
      >
        <!-- Indeterminate bar -->
        <div v-if="indeterminate" class="progress-indeterminate" />
      </div>
    </div>

    <!-- Bottom row -->
    <div v-if="showCount && max" class="progress-footer">
      <span>{{ current }} / {{ max }}</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  value:          { type: Number, default: 0 },
  max:            { type: Number, default: 100 },
  size:           { type: String, default: 'md' },     // sm | md | lg
  variant:        { type: String, default: 'primary' }, // primary | success | warning | danger | accent
  label:          { type: String, default: '' },
  showLabel:      { type: Boolean, default: true },
  showCount:      { type: Boolean, default: false },
  animated:       { type: Boolean, default: true },
  indeterminate:  { type: Boolean, default: false },
  format:         { type: Function, default: null },   // custom formatter (percent: number) => string
})

const percent = computed(() => {
  if (props.indeterminate) return 100
  if (props.max <= 0) return 0
  return Math.min(100, Math.round((props.value / props.max) * 100))
})

const current = computed(() => props.value)
const displayValue = computed(() => {
  if (props.indeterminate) return '…'
  if (props.format) return props.format(percent.value)
  return `${percent.value}%`
})

const progressAttrs = computed(() => ({
  'aria-valuenow': props.indeterminate ? undefined : props.value,
  'aria-valuemin': 0,
  'aria-valuemax': props.max,
  'aria-label': props.label || 'Progress',
}))

const wrapperClass = computed(() => [
  props.indeterminate ? 'progress-root--indeterminate' : '',
])

const sizeMap = {
  sm: 'progress-track--sm',
  md: 'progress-track--md',
  lg: 'progress-track--lg',
}
const sizeClass = computed(() => sizeMap[props.size] ?? sizeMap.md)

const colorMap = {
  primary: 'progress-fill--primary',
  success: 'progress-fill--success',
  warning: 'progress-fill--warning',
  danger:  'progress-fill--danger',
  accent:  'progress-fill--accent',
}
const colorClass = computed(() => colorMap[props.variant] ?? colorMap.primary)
</script>

<style scoped>
.progress-root {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: var(--space-1, 0.25rem);
}

/* ── Header ────────────────────────────────── */
.progress-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: var(--space-2, 0.5rem);
}

.progress-label {
  font-size: var(--font-size-sm, 0.875rem);
  font-weight: 500;
  color: var(--color-neutral-700, #3f3f46);
}

.progress-value {
  font-size: var(--font-size-sm, 0.875rem);
  font-weight: 600;
  color: var(--color-neutral-600, #52525b);
  font-variant-numeric: tabular-nums;
}

/* ── Track ─────────────────────────────────── */
.progress-track {
  width: 100%;
  background: var(--color-neutral-200, #e4e4e7);
  border-radius: var(--radius-full, 9999px);
  overflow: hidden;
  position: relative;
}

.progress-track--sm { height: 6px; }
.progress-track--md { height: 10px; }
.progress-track--lg { height: 16px; }

/* ── Fill ──────────────────────────────────── */
.progress-fill {
  height: 100%;
  border-radius: var(--radius-full, 9999px);
  transition: width var(--transition-normal, 200ms cubic-bezier(0.4, 0, 0.2, 1));
  position: relative;
}

.progress-fill--primary { background: var(--color-primary, #1a2a4a); }
.progress-fill--success { background: var(--color-success, #059669); }
.progress-fill--warning { background: var(--color-warning, #d97706); }
.progress-fill--danger  { background: var(--color-danger, #dc2626); }
.progress-fill--accent  { background: var(--color-accent, #c4952a); }

.progress-fill--animated {
  background-image: linear-gradient(
    45deg,
    rgba(255, 255, 255, 0.15) 25%,
    transparent 25%,
    transparent 50%,
    rgba(255, 255, 255, 0.15) 50%,
    rgba(255, 255, 255, 0.15) 75%,
    transparent 75%,
    transparent
  );
  background-size: 1rem 1rem;
  animation: progress-stripes 1s linear infinite;
}

/* ── Indeterminate ─────────────────────────── */
.progress-indeterminate {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    transparent 0%,
    rgba(255, 255, 255, 0.4) 50%,
    transparent 100%
  );
  animation: progress-shimmer 1.5s ease-in-out infinite;
}

.progress-root--indeterminate .progress-fill {
  width: 100% !important;
  animation: progress-indeterminate 2s ease-in-out infinite;
}

/* ── Footer ────────────────────────────────── */
.progress-footer {
  font-size: var(--font-size-xs, 0.75rem);
  color: var(--color-neutral-400, #a1a1aa);
  text-align: right;
}

/* ── Keyframes ─────────────────────────────── */
@keyframes progress-stripes {
  from { background-position: 1rem 0; }
  to   { background-position: 0 0; }
}

@keyframes progress-shimmer {
  0%   { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

@keyframes progress-indeterminate {
  0%   { transform: scaleX(0.2); transform-origin: left; }
  25%  { transform: scaleX(0.8); transform-origin: left; }
  50%  { transform: scaleX(0.2); transform-origin: right; }
  75%  { transform: scaleX(0.8); transform-origin: right; }
  100% { transform: scaleX(0.2); transform-origin: left; }
}

@media (prefers-reduced-motion: reduce) {
  .progress-fill {
    transition: none;
  }
  .progress-fill--animated {
    animation: none;
  }
  .progress-indeterminate {
    animation: none;
  }
  .progress-root--indeterminate .progress-fill {
    animation: none;
  }
}
</style>
