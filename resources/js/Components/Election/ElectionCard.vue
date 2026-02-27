<template>
  <div :class="['election-card', { 'is-selected': isSelected }]">
    <!-- Card Header -->
    <div class="card-header">
      <h3 class="election-name">{{ election.name }}</h3>
      <ElectionTypeBadge
        :election-type="election.type"
        size="sm"
        :show-tooltip="false"
      />
    </div>

    <!-- Card Body -->
    <div class="card-body">
      <!-- Description -->
      <p class="election-description">{{ election.description }}</p>

      <!-- Metadata -->
      <div class="election-meta">
        <!-- Status Badge -->
        <span :class="['status-badge', statusClass]">
          {{ statusText }}
        </span>

        <!-- Voting Dates (if available) -->
        <span v-if="votingDatesText" class="voting-dates">
          {{ votingDatesText }}
        </span>
      </div>

      <!-- Eligibility Info -->
      <div v-if="showEligibility" class="eligibility-info">
        <p class="eligibility-text">
          {{ eligibilityText }}
        </p>
      </div>
    </div>

    <!-- Card Footer -->
    <div class="card-footer">
      <button
        v-if="!isSelected"
        @click="onSelect"
        :disabled="!election.is_active"
        class="btn-select"
      >
        {{ $t('election.actions.select') }}
      </button>
      <span v-else class="badge-current-selection">
        ✓ {{ $t('election.election_card.current_selection') }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ElectionTypeBadge from './ElectionTypeBadge.vue'

const props = defineProps({
  /**
   * Election object from backend
   * @type {Object}
   * @required
   */
  election: {
    type: Object,
    required: true,
    validator: (election) => {
      // Strict validation of required properties
      return (
        election.id &&
        typeof election.id === 'number' &&
        election.name &&
        typeof election.name === 'string' &&
        election.type &&
        typeof election.type === 'string' &&
        ['demo', 'real'].includes(election.type) &&
        election.is_active !== undefined &&
        typeof election.is_active === 'boolean'
      )
    }
  },
  /**
   * Whether this election is currently selected
   * @type {Boolean}
   * @default false
   */
  isSelected: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['select'])

const { t } = useI18n()

/**
 * Format date string to locale format
 * @param {string} dateString - ISO date string
 * @returns {string} - Formatted date
 */
const formatDate = (dateString) => {
  if (!dateString) return null

  try {
    const date = new Date(dateString)
    return date.toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    })
  } catch (error) {
    console.warn('Invalid date format:', dateString)
    return null
  }
}

/**
 * Computed: Formatted voting dates with null safety
 * Uses start_date and end_date from database schema
 */
const votingDatesText = computed(() => {
  // Check if both dates exist
  if (!props.election.start_date || !props.election.end_date) {
    return t('election.election_card.no_date_range')
  }

  const startDate = formatDate(props.election.start_date)
  const endDate = formatDate(props.election.end_date)

  // Handle format errors
  if (!startDate || !endDate) {
    return t('election.election_card.no_date_range')
  }

  return t('election.election_card.voting_period', {
    start: startDate,
    end: endDate
  })
})

/**
 * Computed: Election status (Active/Inactive)
 */
const statusText = computed(() => {
  return props.election.is_active
    ? t('election.election_card.active')
    : t('election.election_card.inactive')
})

const statusClass = computed(() => {
  return props.election.is_active ? 'status-active' : 'status-inactive'
})

/**
 * Computed: Eligibility explanation based on election type
 */
const eligibilityText = computed(() => {
  return props.election.type === 'demo'
    ? t('election.eligibility.demo')
    : t('election.eligibility.real')
})

const showEligibility = computed(() => true)

/**
 * Handler: Emit select event for parent component
 */
const onSelect = () => {
  if (props.election.is_active) {
    emit('select', props.election)
  }
}
</script>

<style scoped>
@reference "../../../css/app.css";

.election-card {
  @apply bg-white border border-gray-200 rounded-lg shadow-xs hover:shadow-md transition-shadow duration-200 overflow-hidden;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.election-card.is-selected {
  @apply border-blue-500 shadow-md bg-blue-50;
}

/* Card Header */
.card-header {
  @apply bg-gray-50 px-6 py-4 border-b border-gray-200;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.election-name {
  @apply text-lg font-semibold text-gray-900 m-0;
  flex: 1;
}

/* Card Body */
.card-body {
  @apply px-6 py-4 flex-1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.election-description {
  @apply text-sm text-gray-700 m-0 line-clamp-2;
}

/* Metadata */
.election-meta {
  @apply flex flex-wrap gap-3 items-center;
}

.status-badge {
  @apply inline-block px-3 py-1 rounded-full text-xs font-medium;
}

.status-active {
  @apply bg-green-100 text-green-800;
}

.status-inactive {
  @apply bg-gray-100 text-gray-600;
}

.voting-dates {
  @apply text-xs text-gray-500;
}

/* Eligibility Info */
.eligibility-info {
  @apply bg-blue-50 border border-blue-100 rounded px-3 py-2;
}

.eligibility-text {
  @apply text-xs text-blue-900 m-0;
}

/* Card Footer */
.card-footer {
  @apply px-6 py-4 border-t border-gray-200 bg-gray-50;
}

.btn-select {
  @apply w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed;
}

.badge-current-selection {
  @apply block text-center text-sm font-medium text-green-700;
}

/* Responsive */
@media (max-width: 640px) {
  .card-header {
    @apply px-4 py-3;
    flex-direction: column;
  }

  .card-body {
    @apply px-4 py-3;
  }

  .card-footer {
    @apply px-4 py-3;
  }

  .election-name {
    @apply text-base;
  }
}
</style>
