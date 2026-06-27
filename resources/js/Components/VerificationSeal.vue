<template>
  <div
    class="seal-root"
    :class="[sizeClass, statusClass, { 'seal-root--animated': animate }]"
    role="status"
    :aria-label="ariaLabel"
  >
    <!-- SVG Shield -->
    <svg
      class="seal-shield"
      viewBox="0 0 48 48"
      fill="none"
      :aria-hidden="true"
    >
      <!-- Shield body -->
      <path
        d="M24 2L4 10v12c0 12.5 8.5 24 20 28 11.5-4 20-15.5 20-28V10L24 2z"
        :fill="shieldFill"
        :stroke="shieldStroke"
        stroke-width="1.5"
      />
      <!-- Checkmark -->
      <path
        v-if="status === 'verified'"
        d="M16 24l6 6 10-10"
        stroke="white"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="seal-checkmark"
      />
      <!-- Hourglass/exclamation for pending -->
      <text
        v-if="status === 'pending'"
        x="24"
        y="30"
        text-anchor="middle"
        fill="white"
        font-size="16"
        font-weight="bold"
      >?</text>
      <!-- Exclamation for warning -->
      <text
        v-if="status === 'warning'"
        x="24"
        y="30"
        text-anchor="middle"
        fill="white"
        font-size="16"
        font-weight="bold"
      >!</text>
    </svg>

    <!-- Label -->
    <span v-if="label" class="seal-label">{{ label }}</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status:  { type: String, default: 'idle' },     // idle | verified | pending | warning
  size:    { type: String, default: 'md' },         // sm | md | lg
  label:   { type: String, default: '' },
  animate: { type: Boolean, default: true },
})

const statusMap = {
  idle:     { fill: '#e4e4e7', stroke: '#a1a1aa' },
  verified: { fill: '#059669', stroke: '#047857' },
  pending:  { fill: '#d97706', stroke: '#b45309' },
  warning:  { fill: '#dc2626', stroke: '#b91c1c' },
}

const shieldFill = computed(() => statusMap[props.status]?.fill ?? statusMap.idle.fill)
const shieldStroke = computed(() => statusMap[props.status]?.stroke ?? statusMap.idle.stroke)

const sizeMap = {
  sm: 'seal-root--sm',
  md: 'seal-root--md',
  lg: 'seal-root--lg',
}
const sizeClass = computed(() => sizeMap[props.size] ?? sizeMap.md)

const statusClass = computed(() => `seal-root--${props.status}`)

const ariaLabel = computed(() => {
  if (props.label) return props.label
  return `Status: ${props.status}`
})
</script>

<style scoped>
.seal-root {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-1, 0.25rem);
}

.seal-shield {
  display: block;
}

.seal-root--sm .seal-shield { width: 32px; height: 32px; }
.seal-root--md .seal-shield { width: 48px; height: 48px; }
.seal-root--lg .seal-shield { width: 64px; height: 64px; }

/* ── Label ─────────────────────────────────── */
.seal-label {
  font-size: var(--font-size-xs, 0.75rem);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.seal-root--idle     .seal-label { color: var(--color-neutral-400, #a1a1aa); }
.seal-root--verified .seal-label { color: var(--color-success, #059669); }
.seal-root--pending  .seal-label { color: var(--color-warning, #d97706); }
.seal-root--warning  .seal-label { color: var(--color-danger, #dc2626); }

/* ── Verified animation ────────────────────── */
.seal-checkmark {
  stroke-dasharray: 30;
  stroke-dashoffset: 30;
}

.seal-root--animated.seal-root--verified .seal-checkmark {
  animation: seal-draw 0.4s var(--ease-out, cubic-bezier(0, 0, 0.2, 1)) forwards;
}

@keyframes seal-draw {
  to { stroke-dashoffset: 0; }
}

/* ── Pending pulse ─────────────────────────── */
.seal-root--pending.seal-root--animated .seal-shield {
  animation: seal-pulse 2s ease-in-out infinite;
}

@keyframes seal-pulse {
  0%, 100% { opacity: 1; }
  50%      { opacity: 0.7; }
}

/* ── Idle glow (hover) ─────────────────────── */
.seal-root--idle { cursor: default; }
.seal-root--idle:hover .seal-shield {
  filter: drop-shadow(0 0 4px rgba(161, 161, 170, 0.4));
  transition: filter var(--transition-normal, 200ms cubic-bezier(0.4, 0, 0.2, 1));
}

/* ── Verified glow ─────────────────────────── */
.seal-root--verified .seal-shield {
  filter: drop-shadow(0 0 8px rgba(5, 150, 105, 0.3));
}

/* ── Reduced motion ────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .seal-checkmark,
  .seal-shield {
    animation: none !important;
  }
  .seal-root--animated.seal-root--verified .seal-checkmark {
    animation: none;
    stroke-dashoffset: 0;
  }
}
</style>
