<template>
  <div class="election-selector-overlay" @click.self="onCancel">
    <div class="election-selector-modal">
      <!-- Header -->
      <div class="selector-header">
        <h2 class="selector-title">{{ $t('election.title') }}</h2>
        <p class="selector-subtitle">{{ $t('election.subtitle') }}</p>
        <button
          @click="onCancel"
          class="btn-close"
          :aria-label="$t('election.actions.cancel')"
        >
          ✕
        </button>
      </div>

      <!-- Search Box -->
      <div class="search-container">
        <input
          v-model="searchQuery"
          type="text"
          :placeholder="$t('election.selector.search_placeholder')"
          class="search-input"
          aria-label="Search elections"
        />
      </div>

      <!-- Elections Grid -->
      <div v-if="filteredElections.length > 0" class="elections-grid">
        <ElectionCard
          v-for="election in filteredElections"
          :key="election.id"
          :election="election"
          :is-selected="selectedElection?.id === election.id"
          @select="selectElection"
        />
      </div>

      <!-- No Results Message -->
      <div v-else class="no-results">
        <p class="no-results-text">
          {{ $t('election.selector.no_elections') }}
        </p>
      </div>

      <!-- Footer Actions -->
      <div class="selector-footer">
        <button
          @click="onCancel"
          class="btn-secondary"
        >
          {{ $t('election.actions.cancel') }}
        </button>
        <button
          @click="confirmSelection"
          :disabled="!selectedElection"
          class="btn-primary"
        >
          {{ $t('election.actions.confirm') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ElectionCard from './ElectionCard.vue'

const { t } = useI18n()

const props = defineProps({
  /**
   * Array of election objects to display
   * @type {Array<Object>}
   * @required
   */
  elections: {
    type: Array,
    required: true,
    validator: (elections) => {
      // Validate that all elections have required properties
      return Array.isArray(elections) && elections.every(election =>
        election &&
        typeof election === 'object' &&
        election.id &&
        typeof election.id === 'number' &&
        election.name &&
        typeof election.name === 'string' &&
        election.type &&
        ['demo', 'real'].includes(election.type) &&
        election.is_active !== undefined &&
        typeof election.is_active === 'boolean'
      )
    }
  },
  /**
   * Initially selected election (optional)
   * @type {Object}
   */
  initialSelected: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['select', 'cancel'])

const searchQuery = ref('')
const selectedElection = ref(props.initialSelected)

/**
 * Computed: Filter elections by search query (case-insensitive)
 */
const filteredElections = computed(() => {
  if (!searchQuery.value.trim()) {
    return props.elections
  }

  const query = searchQuery.value.toLowerCase()
  return props.elections.filter(election =>
    election.name.toLowerCase().includes(query) ||
    election.description?.toLowerCase().includes(query) ||
    false
  )
})

/**
 * Handler: Select an election
 * @param {Object} election - Election object from ElectionCard
 */
const selectElection = (election) => {
  if (election && election.is_active) {
    selectedElection.value = election
  }
}

/**
 * Handler: Confirm selection and emit to parent
 */
const confirmSelection = () => {
  if (selectedElection.value) {
    emit('select', selectedElection.value)
  }
}

/**
 * Handler: Cancel and notify parent
 */
const onCancel = () => {
  emit('cancel')
}
</script>

<style scoped>
@reference "../../../css/app.css";

/* Overlay */
.election-selector-overlay {
  @apply fixed inset-0 bg-black/50 flex items-center justify-center z-50;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Modal Container */
.election-selector-modal {
  @apply bg-white rounded-2xl shadow-2xl w-full max-w-4xl;
  display: flex;
  flex-direction: column;
  max-height: 90vh;
  overflow: hidden;
}

/* Header */
.selector-header {
  @apply relative px-6 py-6 border-b border-gray-200;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.selector-title {
  @apply text-2xl font-bold text-gray-900 m-0;
}

.selector-subtitle {
  @apply text-gray-600 text-sm m-0;
}

.btn-close {
  @apply absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl leading-none bg-transparent border-none cursor-pointer;
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
}

.btn-close:hover {
  @apply text-gray-700;
}

/* Search Box */
.search-container {
  @apply px-6 py-4 border-b border-gray-200 bg-gray-50;
}

.search-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-transparent;
  font-size: 0.95rem;
  transition: all 0.2s ease;
}

.search-input::placeholder {
  @apply text-gray-500;
}

/* Elections Grid */
.elections-grid {
  @apply flex-1 overflow-y-auto px-6 py-4;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
}

/* No Results */
.no-results {
  @apply flex-1 flex items-center justify-center px-6 py-12;
  display: flex;
  align-items: center;
  justify-content: center;
}

.no-results-text {
  @apply text-center text-gray-500 text-lg m-0;
}

/* Footer */
.selector-footer {
  @apply px-6 py-4 border-t border-gray-200 bg-gray-50;
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

/* Buttons */
.btn-primary {
  @apply px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed;
}

.btn-secondary {
  @apply px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors duration-200;
}

/* Responsive */
@media (max-width: 1024px) {
  .elections-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  }
}

@media (max-width: 768px) {
  .election-selector-modal {
    @apply w-11/12 max-w-2xl;
    max-height: 95vh;
  }

  .selector-header {
    @apply px-4 py-4;
  }

  .search-container {
    @apply px-4 py-3;
  }

  .elections-grid {
    @apply px-4 py-3;
    grid-template-columns: 1fr;
  }

  .selector-footer {
    @apply px-4 py-3;
    flex-direction: column-reverse;
  }

  .btn-primary,
  .btn-secondary {
    @apply w-full;
  }

  .selector-title {
    @apply text-xl;
  }

  .selector-subtitle {
    @apply text-xs;
  }
}

@media (max-width: 480px) {
  .election-selector-overlay {
    @apply bg-black/70;
  }

  .election-selector-modal {
    @apply w-full max-w-none rounded-t-2xl;
    max-height: 95vh;
  }

  .selector-header {
    @apply px-3 py-3;
  }

  .search-container {
    @apply px-3 py-2;
  }

  .elections-grid {
    @apply px-3 py-2;
  }

  .selector-footer {
    @apply px-3 py-2;
  }
}
</style>
