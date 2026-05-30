<template>
  <Teleport to="body">
    <!-- Backdrop -->
    <Transition name="fade">
      <div
        v-if="open"
        class="modal-backdrop"
        @click="handleBackdropClick"
        @keydown.escape="handleEscape"
        aria-hidden="true"
      />
    </Transition>

    <!-- Modal Panel -->
    <Transition name="modal-slide">
      <div
        v-if="open"
        ref="panelRef"
        class="modal-container"
        role="dialog"
        :aria-modal="true"
        :aria-labelledby="titleId"
        :aria-describedby="descriptionId"
        @keydown.escape="handleEscape"
      >
        <div class="modal-panel" :class="[sizeClass, variantClass]">
          <!-- Header -->
          <div class="modal-header">
            <div class="modal-header-left">
              <!-- Variant icon -->
              <span v-if="variantIcon" class="modal-variant-icon" :class="variantIconClass" aria-hidden="true">
                {{ variantIcon }}
              </span>
              <h2 :id="titleId" class="modal-title">
                <slot name="title">{{ title }}</slot>
              </h2>
            </div>
            <button
              v-if="closable"
              type="button"
              class="modal-close"
              :aria-label="closeLabel"
              @click="emit('close')"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="modal-body">
            <slot />
          </div>

          <!-- Footer (actions) -->
          <div v-if="$slots.footer" class="modal-footer">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, ref, watch, nextTick } from 'vue'

const props = defineProps({
  open:        { type: Boolean, default: false },
  title:       { type: String,  default: '' },
  size:        { type: String,  default: 'md' },    // sm | md | lg | xl | full
  variant:     { type: String,  default: 'default' }, // default | danger | success | warning
  closable:    { type: Boolean, default: true },
  closeOnBackdrop: { type: Boolean, default: true },
  closeLabel:  { type: String,  default: 'Close modal' },
  titleId:     { type: String,  default: 'modal-title' },
  descriptionId: { type: String, default: '' },
})

const emit = defineEmits(['close'])
const panelRef = ref(null)

// ── Size classes ─────────────────────────────
const sizeMap = {
  sm:   'max-w-sm',
  md:   'max-w-md',
  lg:   'max-w-lg',
  xl:   'max-w-xl',
  full: 'max-w-4xl',
}
const sizeClass = computed(() => sizeMap[props.size] ?? sizeMap.md)

// ── Variant styling ─────────────────────────
const variantMap = {
  default: '',
  danger:  'modal-variant--danger',
  success: 'modal-variant--success',
  warning: 'modal-variant--warning',
}
const variantClass = computed(() => variantMap[props.variant] ?? variantMap.default)

const variantIconMap = {
  danger:  '⚠️',
  success: '✅',
  warning: '⚠️',
}
const variantIcon = computed(() => variantIconMap[props.variant] ?? null)

const variantIconClass = computed(() => {
  if (props.variant === 'danger')  return 'text-danger-600'
  if (props.variant === 'success') return 'text-success-600'
  if (props.variant === 'warning') return 'text-warning-600'
  return ''
})

// ── Body scroll lock ────────────────────────
watch(() => props.open, (isOpen) => {
  if (isOpen) {
    document.body.style.overflow = 'hidden'
    nextTick(() => panelRef.value?.focus())
  } else {
    document.body.style.overflow = ''
  }
})

// ── Escape key ──────────────────────────────
function handleEscape(e) {
  if (e.key === 'Escape' && props.closable) {
    emit('close')
  }
}

// ── Backdrop click ──────────────────────────
function handleBackdropClick() {
  if (props.closeOnBackdrop) {
    emit('close')
  }
}
</script>

<style scoped>
/* ── Transitions ───────────────────────────── */
.fade-enter-active,
.fade-leave-active {
  transition: opacity var(--transition-normal, 200ms cubic-bezier(0.4, 0, 0.2, 1));
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-slide-enter-active,
.modal-slide-leave-active {
  transition: all var(--transition-normal, 200ms cubic-bezier(0.4, 0, 0.2, 1));
}
.modal-slide-enter-from,
.modal-slide-leave-to {
  transform: translateY(16px);
  opacity: 0;
}

/* ── Backdrop ──────────────────────────────── */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 29, 58, 0.6);
  backdrop-filter: blur(2px);
  z-index: 40;
}

/* ── Container ─────────────────────────────── */
.modal-container {
  position: fixed;
  inset: 0;
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: var(--space-4, 1rem);
}

/* ── Panel ─────────────────────────────────── */
.modal-panel {
  width: 100%;
  background: white;
  border-radius: var(--radius-lg, 0.75rem);
  box-shadow: var(--shadow-xl, 0 20px 25px -5px rgba(0,0,0,0.1));
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  position: relative;
}

/* ── Variants ──────────────────────────────── */
.modal-variant--danger {
  box-shadow: 0 0 0 2px var(--color-danger, #dc2626),
              var(--shadow-xl, 0 20px 25px -5px rgba(0,0,0,0.1));
}
.modal-variant--success {
  box-shadow: 0 0 0 2px var(--color-success, #059669),
              var(--shadow-xl, 0 20px 25px -5px rgba(0,0,0,0.1));
}
.modal-variant--warning {
  box-shadow: 0 0 0 2px var(--color-warning, #d97706),
              var(--shadow-xl, 0 20px 25px -5px rgba(0,0,0,0.1));
}

/* ── Header ────────────────────────────────── */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--space-5, 1.25rem) var(--space-6, 1.5rem);
  border-bottom: 1px solid var(--color-neutral-200, #e4e4e7);
  flex-shrink: 0;
}

.modal-header-left {
  display: flex;
  align-items: center;
  gap: var(--space-3, 0.75rem);
}

.modal-variant-icon {
  font-size: 1.25rem;
  line-height: 1;
}

.modal-title {
  font-family: var(--font-display, Georgia, serif);
  font-size: var(--font-size-xl, 1.25rem);
  font-weight: 600;
  color: var(--color-neutral-900, #18181b);
  margin: 0;
  line-height: var(--leading-tight, 1.25);
}

.modal-close {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--color-neutral-400, #a1a1aa);
  padding: var(--space-1, 0.25rem);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--radius-sm, 0.375rem);
  transition: all var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
  flex-shrink: 0;
}

.modal-close:hover {
  background: var(--color-neutral-100, #f4f4f5);
  color: var(--color-neutral-700, #3f3f46);
}

.modal-close:focus-visible {
  outline: 2px solid var(--color-primary, #1a2a4a);
  outline-offset: 2px;
}

/* ── Body ──────────────────────────────────── */
.modal-body {
  flex: 1;
  overflow-y: auto;
  padding: var(--space-6, 1.5rem);
  font-size: var(--font-size-sm, 0.875rem);
  color: var(--color-neutral-700, #3f3f46);
  line-height: var(--leading-relaxed, 1.625);
}

/* ── Footer ────────────────────────────────── */
.modal-footer {
  display: flex;
  gap: var(--space-3, 0.75rem);
  justify-content: flex-end;
  padding: var(--space-4, 1rem) var(--space-6, 1.5rem);
  border-top: 1px solid var(--color-neutral-200, #e4e4e7);
  flex-shrink: 0;
}

/* ── Reduced motion ────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .fade-enter-active,
  .fade-leave-active,
  .modal-slide-enter-active,
  .modal-slide-leave-active {
    transition: none;
  }
}
</style>
