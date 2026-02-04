<template>
  <div class="select-election-page">
    <!-- Loading State -->
    <div v-if="isLoading" class="loading-container">
      <div class="spinner"></div>
      <p class="loading-text">{{ $t('election.loading') }}</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="error-container">
      <div class="error-message">
        <h3 class="error-title">{{ $t('election.error.title') }}</h3>
        <p class="error-description">{{ error }}</p>
        <button @click="goBack" class="btn-error-action">
          {{ $t('election.actions.back') }}
        </button>
      </div>
    </div>

    <!-- Election Selector -->
    <div v-else-if="elections.length > 0">
      <ElectionSelector
        :elections="elections"
        :initial-selected="initialSelected"
        @select="handleElectionSelected"
        @cancel="goBack"
      />
    </div>

    <!-- No Elections Available -->
    <div v-else class="no-elections-container">
      <div class="no-elections-message">
        <h3 class="no-elections-title">
          {{ $t('election.no_elections.title') }}
        </h3>
        <p class="no-elections-description">
          {{ $t('election.no_elections.description') }}
        </p>
        <p v-if="userRole === 'admin'" class="admin-hint">
          {{ $t('election.no_elections.demo_suggestion') }}
        </p>
        <button @click="goBack" class="btn-go-back">
          {{ $t('election.actions.back') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import ElectionSelector from '@/Components/Election/ElectionSelector.vue'

const router = useRouter()
const { t } = useI18n()

const isLoading = ref(true)
const error = ref(null)
const elections = ref([])
const initialSelected = ref(null)

/**
 * Computed: Get user role from session/auth
 * TODO: Connect to actual auth store
 */
const userRole = computed(() => {
  // Placeholder: would connect to auth service
  return 'user' // or 'admin'
})

/**
 * Lifecycle: Fetch available elections on mount
 */
onMounted(async () => {
  try {
    isLoading.value = true
    error.value = null

    // Fetch elections from backend API
    const response = await fetch('/api/v1/elections', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`)
    }

    const data = await response.json()

    // Validate response structure
    if (!Array.isArray(data.data)) {
      throw new Error('Invalid response format from server')
    }

    elections.value = data.data

    // Try to load initial selection from session/store
    // TODO: Connect to actual session/store
    initialSelected.value = null

  } catch (err) {
    console.error('Error fetching elections:', err)
    error.value = t('election.error.failed_to_load')
  } finally {
    isLoading.value = false
  }
})

/**
 * Handler: Process election selection
 * @param {Object} election - Selected election
 */
const handleElectionSelected = async (election) => {
  try {
    // Send selection to backend to store in session/store
    const response = await fetch('/api/v1/elections/select', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: JSON.stringify({
        election_id: election.id
      })
    })

    if (!response.ok) {
      throw new Error(`Failed to save election selection`)
    }

    // Redirect to voting page
    // Route: /vote/create for voting flow
    await router.push({ name: 'slug.vote.create' })

  } catch (err) {
    console.error('Error selecting election:', err)
    error.value = t('election.error.selection_failed')
  }
}

/**
 * Handler: Navigate back to previous page
 */
const goBack = () => {
  router.back()
}
</script>

<style scoped>
/* Page Container */
.select-election-page {
  @apply min-h-screen bg-gray-50;
}

/* Loading State */
.loading-container {
  @apply fixed inset-0 flex items-center justify-center bg-white;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.spinner {
  @apply w-12 h-12 border-4 border-gray-300 border-t-blue-600 rounded-full;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.loading-text {
  @apply text-gray-600 text-lg m-0;
}

/* Error State */
.error-container {
  @apply fixed inset-0 flex items-center justify-center bg-white px-4;
  display: flex;
  align-items: center;
  justify-content: center;
}

.error-message {
  @apply text-center max-w-md;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
}

.error-title {
  @apply text-2xl font-bold text-red-600 m-0;
}

.error-description {
  @apply text-gray-600 m-0;
}

.btn-error-action {
  @apply px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200;
}

/* No Elections State */
.no-elections-container {
  @apply fixed inset-0 flex items-center justify-center bg-white px-4;
  display: flex;
  align-items: center;
  justify-content: center;
}

.no-elections-message {
  @apply text-center max-w-md;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.no-elections-title {
  @apply text-2xl font-bold text-gray-900 m-0;
}

.no-elections-description {
  @apply text-gray-600 m-0;
}

.admin-hint {
  @apply text-sm text-blue-600 italic m-0;
}

.btn-go-back {
  @apply px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200;
}

/* Responsive */
@media (max-width: 768px) {
  .error-message,
  .no-elections-message {
    @apply px-4;
  }

  .error-title,
  .no-elections-title {
    @apply text-xl;
  }
}
</style>
