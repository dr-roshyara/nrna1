<template>
  <div class="voting-layout">
    <!-- Header with Election Badge -->
    <header class="voting-header">
      <div class="header-content">
        <h1 class="header-title">{{ pageTitle }}</h1>
        <ElectionTypeBadge
          v-if="election"
          :election-type="election.type"
          size="sm"
          :show-tooltip="true"
          class="header-badge"
          :aria-label="electionTypeLabel"
        />
      </div>
    </header>

    <!-- Demo Mode Notice with proper ARIA -->
    <div
      v-if="election && election.type === 'demo'"
      class="demo-notice"
      role="alert"
      :aria-live="'polite'"
      :aria-label="$t('election.demo_mode_notice.aria_label')"
    >
      <div class="notice-content">
        <!-- Accessible warning icon -->
        <svg
          class="notice-icon"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          aria-hidden="true"
        >
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
        </svg>
        <div class="notice-text">
          <strong>{{ $t('election.demo_mode_notice.title') }}</strong>
          <p>{{ $t('election.demo_mode_notice.message') }}</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="voting-main">
      <slot />
    </main>

    <!-- Footer with semantic HTML -->
    <footer class="voting-footer">
      <div class="footer-content">
        <div v-if="election" class="election-info">
          <span class="info-label">{{ $t('election.election_details.name') }}:</span>
          <span class="info-value">{{ election.name }}</span>
        </div>
        <div
          v-if="currentStep"
          class="step-indicator"
          :aria-label="$t('election.current_step_label', { step: currentStep, total: totalSteps })"
        >
          <span
            class="step-number"
            :aria-current="'step'"
          >
            {{ currentStep }}
          </span>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ElectionTypeBadge from './ElectionTypeBadge.vue'

const { t } = useI18n()

const props = defineProps({
  /**
   * Current election object
   * @type {Object}
   */
  election: {
    type: Object,
    default: null,
    validator: (election) => {
      if (!election) return true // Allow null
      return (
        election.id &&
        typeof election.id === 'number' &&
        election.name &&
        typeof election.name === 'string' &&
        election.type &&
        ['demo', 'real'].includes(election.type)
      )
    }
  },
  /**
   * Page title/heading
   * @type {String}
   */
  pageTitle: {
    type: String,
    default: 'Voting'
  },
  /**
   * Current step in voting process (for UI indication)
   * @type {Number|String}
   */
  currentStep: {
    type: [Number, String],
    default: null
  },
  /**
   * Total number of steps in voting process
   * @type {Number}
   */
  totalSteps: {
    type: Number,
    default: 5
  }
})

/**
 * Computed: Check if in demo mode
 */
const isDemoMode = computed(() => {
  return props.election && props.election.type === 'demo'
})

/**
 * Computed: Election type label for screen readers
 */
const electionTypeLabel = computed(() => {
  if (!props.election) return ''
  return props.election.type === 'demo'
    ? t('election.types.demo_full')
    : t('election.types.real_full')
})
</script>

<style scoped>
/* Layout Container */
.voting-layout {
  @apply min-h-screen flex flex-col bg-gray-50;
  display: flex;
  flex-direction: column;
}

/* Header */
.voting-header {
  @apply sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm;
}

.header-content {
  @apply max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4;
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.header-title {
  @apply text-2xl font-bold text-gray-900 m-0;
}

.header-badge {
  flex-shrink: 0;
}

/* Demo Notice */
.demo-notice {
  @apply bg-yellow-50 border-b border-yellow-200 px-6 py-3;
}

.notice-content {
  @apply max-w-7xl mx-auto flex items-start gap-3;
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.notice-icon {
  @apply text-yellow-600 flex-shrink-0;
  width: 1.5rem;
  height: 1.5rem;
  flex-shrink: 0;
}

.notice-text {
  @apply flex-1;
}

.notice-text strong {
  @apply block text-sm font-semibold text-yellow-900;
  display: block;
  margin-bottom: 0.25rem;
}

.notice-text p {
  @apply text-sm text-yellow-800 m-0;
}

/* Main Content */
.voting-main {
  @apply flex-1 max-w-7xl mx-auto w-full px-6 py-8;
  display: flex;
  flex: 1;
  width: 100%;
}

/* Footer */
.voting-footer {
  @apply bg-white border-t border-gray-200 mt-8;
}

.footer-content {
  @apply max-w-7xl mx-auto px-6 py-4 flex items-center justify-between;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.election-info {
  @apply text-sm text-gray-600;
  display: flex;
  gap: 0.5rem;
}

.info-label {
  @apply font-medium text-gray-900;
}

.info-value {
  @apply text-gray-700;
}

.step-indicator {
  @apply text-sm text-gray-500;
}

.step-number {
  @apply inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-medium;
}

/* Keyboard Focus Visibility */
.step-number:focus-visible {
  @apply outline-2 outline-offset-2 outline-blue-600;
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    @apply px-4 py-3 flex-col items-start;
    flex-direction: column;
    align-items: flex-start;
  }

  .header-title {
    @apply text-xl;
  }

  .demo-notice {
    @apply px-4 py-2;
  }

  .notice-content {
    @apply px-0;
  }

  .notice-icon {
    width: 1.25rem;
    height: 1.25rem;
  }

  .notice-text strong {
    @apply text-xs;
  }

  .notice-text p {
    @apply text-xs;
  }

  .voting-main {
    @apply px-4 py-4;
  }

  .footer-content {
    @apply px-4 py-3 flex-col items-start gap-2;
    flex-direction: column;
    align-items: flex-start;
  }

  .election-info {
    @apply text-xs;
  }
}

@media (max-width: 480px) {
  .header-title {
    @apply text-lg;
  }

  .voting-main {
    @apply px-3 py-3;
  }

  .footer-content {
    @apply px-3 py-2;
  }

  .notice-icon {
    width: 1.1rem;
    height: 1.1rem;
  }
}

/* High Contrast Mode Support */
@media (prefers-contrast: more) {
  .demo-notice {
    @apply border-2 border-yellow-600;
  }

  .notice-text strong {
    @apply font-bold;
  }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>
