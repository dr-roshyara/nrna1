<template>
  <div class="voting-layout">
    <!-- Election Header with Logo, Language, Auth -->
    <ElectionHeader :isLoggedIn="false" />

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
          <strong>{{ $t('pages.election.demo_mode_notice.title') }}</strong>
          <p>{{ $t('pages.election.demo_mode_notice.message') }}</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="voting-main">
      <slot />
    </main>

    <!-- PublicDigit Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ElectionTypeBadge from './ElectionTypeBadge.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
import ElectionHeader from '@/Components/Header/ElectionHeader.vue'

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
    ? t('pages.election.types.demo_full')
    : t('pages.election.types.real_full')
})
</script>

<style scoped>
/* Layout Container */
.voting-layout {
  @apply min-h-screen flex flex-col bg-gray-50;
  display: flex;
  flex-direction: column;
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
  @apply flex-1 w-full px-6 py-8;
  display: block;
  flex: 1;
}


/* Responsive */
@media (max-width: 768px) {
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
}

@media (max-width: 480px) {
  .header-title {
    @apply text-lg;
  }

  .voting-main {
    @apply px-3 py-3;
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
