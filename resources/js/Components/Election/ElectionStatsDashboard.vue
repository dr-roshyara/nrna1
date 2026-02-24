<template>
  <div class="stats-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
      <h2 class="dashboard-title">{{ $t('election.dashboard.title') }}</h2>
      <p class="dashboard-subtitle">{{ $t('election.dashboard.statistics') }}</p>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="loading-container">
      <div class="spinner"></div>
      <p class="loading-text">{{ $t('election.loading') }}</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="error-container">
      <p class="error-message">{{ error }}</p>
      <button @click="loadStats" class="btn-retry">
        {{ $t('election.actions.next') }}
      </button>
    </div>

    <!-- Stats Content -->
    <div v-else class="stats-grid">
      <!-- Real Elections Stats -->
      <div class="stats-card real-elections">
        <div class="card-header">
          <h3 class="card-title">{{ $t('election.dashboard.real_elections') }}</h3>
          <ElectionTypeBadge
            election-type="real"
            size="sm"
            :show-tooltip="false"
          />
        </div>

        <div class="stats-list">
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.total_votes') }}</span>
            <span class="stat-value">{{ realStats.totalVotes }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.total_codes') }}</span>
            <span class="stat-value">{{ realStats.totalCodes }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.verified_codes') }}</span>
            <span class="stat-value">{{ realStats.verifiedCodes }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.turnout_percentage') }}</span>
            <span class="stat-value">{{ realStats.turnoutPercentage }}%</span>
          </div>
        </div>

        <div class="card-actions">
          <button class="btn-action">
            {{ $t('election.dashboard.actions.view_results') }}
          </button>
        </div>
      </div>

      <!-- Demo Elections Stats -->
      <div class="stats-card demo-elections">
        <div class="card-header">
          <h3 class="card-title">{{ $t('election.dashboard.demo_elections') }}</h3>
          <ElectionTypeBadge
            election-type="demo"
            size="sm"
            :show-tooltip="false"
          />
        </div>

        <div class="stats-list">
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.demo_votes') }}</span>
            <span class="stat-value">{{ demoStats.totalVotes }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.total_codes') }}</span>
            <span class="stat-value">{{ demoStats.totalCodes }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.verified_codes') }}</span>
            <span class="stat-value">{{ demoStats.verifiedCodes }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">{{ $t('election.dashboard.stats.turnout_percentage') }}</span>
            <span class="stat-value">{{ demoStats.turnoutPercentage }}%</span>
          </div>
        </div>

        <div class="card-actions">
          <button
            @click="showCleanupDialog = true"
            class="btn-action btn-danger"
          >
            {{ $t('election.dashboard.actions.cleanup_demo') }}
          </button>
          <button
            @click="showResetDialog = true"
            class="btn-action btn-danger"
          >
            {{ $t('election.dashboard.actions.reset_demo') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Cleanup Dialog -->
    <div v-if="showCleanupDialog" class="dialog-overlay" @click.self="showCleanupDialog = false">
      <div class="dialog-modal">
        <h3 class="dialog-title">{{ $t('election.dashboard.cleanup.title') }}</h3>
        <p class="dialog-message">{{ $t('election.dashboard.cleanup.message') }}</p>
        <p class="dialog-confirm">{{ $t('election.dashboard.cleanup.confirm') }}</p>

        <div class="dialog-actions">
          <button
            @click="showCleanupDialog = false"
            class="btn-secondary"
          >
            {{ $t('election.actions.cancel') }}
          </button>
          <button
            @click="cleanupDemo"
            :disabled="isProcessing"
            class="btn-danger"
          >
            {{ isProcessing ? $t('election.loading') : $t('election.dashboard.cleanup.title') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Reset Dialog -->
    <div v-if="showResetDialog" class="dialog-overlay" @click.self="showResetDialog = false">
      <div class="dialog-modal">
        <h3 class="dialog-title">{{ $t('election.dashboard.reset.title') }}</h3>
        <p class="dialog-message">{{ $t('election.dashboard.reset.message') }}</p>

        <div class="dialog-input">
          <input
            v-model="resetConfirmText"
            type="text"
            placeholder="RESET"
            class="confirm-input"
          />
        </div>

        <p class="dialog-confirm">{{ $t('election.dashboard.reset.confirm') }}</p>

        <div class="dialog-actions">
          <button
            @click="showResetDialog = false"
            class="btn-secondary"
          >
            {{ $t('election.actions.cancel') }}
          </button>
          <button
            @click="resetDemo"
            :disabled="resetConfirmText !== 'RESET' || isProcessing"
            class="btn-danger"
          >
            {{ isProcessing ? $t('election.loading') : $t('election.dashboard.reset.title') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import ElectionTypeBadge from './ElectionTypeBadge.vue'

const { t } = useI18n()

const isLoading = ref(true)
const error = ref(null)
const isProcessing = ref(false)
const showCleanupDialog = ref(false)
const showResetDialog = ref(false)
const resetConfirmText = ref('')

const realStats = reactive({
  totalVotes: 0,
  totalCodes: 0,
  verifiedCodes: 0,
  turnoutPercentage: 0
})

const demoStats = reactive({
  totalVotes: 0,
  totalCodes: 0,
  verifiedCodes: 0,
  turnoutPercentage: 0
})

/**
 * Lifecycle: Load statistics on mount
 */
onMounted(async () => {
  await loadStats()
})

/**
 * Handler: Load election statistics from API
 */
const loadStats = async () => {
  try {
    isLoading.value = true
    error.value = null

    const response = await fetch('/api/v1/elections/statistics', {
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

    // Populate real statistics
    if (data.real) {
      realStats.totalVotes = data.real.total_votes ?? 0
      realStats.totalCodes = data.real.total_codes ?? 0
      realStats.verifiedCodes = data.real.verified_codes ?? 0
      realStats.turnoutPercentage = calculateTurnout(
        data.real.total_votes,
        data.real.total_codes
      )
    }

    // Populate demo statistics
    if (data.demo) {
      demoStats.totalVotes = data.demo.total_votes ?? 0
      demoStats.totalCodes = data.demo.total_codes ?? 0
      demoStats.verifiedCodes = data.demo.verified_codes ?? 0
      demoStats.turnoutPercentage = calculateTurnout(
        data.demo.total_votes,
        data.demo.total_codes
      )
    }

  } catch (err) {
    console.error('Error loading statistics:', err)
    error.value = t('election.error.failed_to_load')
  } finally {
    isLoading.value = false
  }
}

/**
 * Helper: Calculate turnout percentage
 * @param {number} votes - Number of votes cast
 * @param {number} codes - Total codes sent
 * @returns {number} Percentage (0-100)
 */
const calculateTurnout = (votes, codes) => {
  if (!codes || codes === 0) return 0
  return Math.round((votes / codes) * 100)
}

/**
 * Handler: Clean up demo data older than 30 days
 */
const cleanupDemo = async () => {
  try {
    isProcessing.value = true

    const response = await fetch('/api/v1/elections/demo/cleanup', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': route().current() ? usePage().props.csrf_token : ''
      }
    })

    if (!response.ok) {
      throw new Error('Failed to cleanup demo data')
    }

    showCleanupDialog.value = false
    await loadStats() // Refresh statistics

  } catch (err) {
    console.error('Error cleaning up demo data:', err)
    error.value = t('election.dashboard.cleanup.error')
  } finally {
    isProcessing.value = false
  }
}

/**
 * Handler: Reset all demo election data
 */
const resetDemo = async () => {
  try {
    isProcessing.value = true

    const response = await fetch('/api/v1/elections/demo/reset', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': route().current() ? usePage().props.csrf_token : ''
      }
    })

    if (!response.ok) {
      throw new Error('Failed to reset demo data')
    }

    showResetDialog.value = false
    resetConfirmText.value = ''
    await loadStats() // Refresh statistics

  } catch (err) {
    console.error('Error resetting demo data:', err)
    error.value = t('election.dashboard.reset.error')
  } finally {
    isProcessing.value = false
  }
}
</script>

<style scoped>
/* Dashboard Container */
.stats-dashboard {
  @apply bg-gray-50 min-h-screen p-6;
}

/* Header */
.dashboard-header {
  @apply max-w-7xl mx-auto mb-8;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.dashboard-title {
  @apply text-3xl font-bold text-gray-900 m-0;
}

.dashboard-subtitle {
  @apply text-gray-600 m-0;
}

/* Loading State */
.loading-container {
  @apply flex items-center justify-center py-12;
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
  @apply text-gray-600 m-0;
}

/* Error State */
.error-container {
  @apply max-w-7xl mx-auto bg-red-50 border border-red-200 rounded-lg p-6;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: flex-start;
}

.error-message {
  @apply text-red-800 m-0;
}

.btn-retry {
  @apply px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700;
}

/* Stats Grid */
.stats-grid {
  @apply max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

/* Stats Card */
.stats-card {
  @apply bg-white rounded-lg shadow-md border border-gray-200 p-6;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.card-header {
  @apply flex items-center justify-between pb-4 border-b border-gray-200;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
}

.card-title {
  @apply text-xl font-semibold text-gray-900 m-0;
}

/* Stats List */
.stats-list {
  @apply space-y-4;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.stat-item {
  @apply flex items-center justify-between p-3 bg-gray-50 rounded;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem;
  background-color: #f9fafb;
  border-radius: 0.5rem;
}

.stat-label {
  @apply text-sm text-gray-600;
}

.stat-value {
  @apply text-2xl font-bold text-gray-900;
}

/* Card Actions */
.card-actions {
  @apply flex gap-2 pt-4 border-t border-gray-200;
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.btn-action {
  @apply px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors;
  flex: 1;
  min-width: 150px;
}

.btn-action.btn-danger {
  @apply bg-red-600 hover:bg-red-700;
}

/* Dialog */
.dialog-overlay {
  @apply fixed inset-0 bg-black/50 flex items-center justify-center z-50;
  display: flex;
  align-items: center;
  justify-content: center;
}

.dialog-modal {
  @apply bg-white rounded-lg shadow-2xl p-6 max-w-md w-11/12;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.dialog-title {
  @apply text-xl font-bold text-gray-900 m-0;
}

.dialog-message {
  @apply text-gray-600 m-0;
}

.dialog-confirm {
  @apply text-sm text-red-600 font-medium m-0;
}

.dialog-input {
  @apply border border-gray-300 rounded-lg p-2;
}

.confirm-input {
  @apply w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500;
}

.dialog-actions {
  @apply flex gap-2 pt-4 border-t border-gray-200;
  display: flex;
  gap: 0.5rem;
  justify-content: flex-end;
}

.btn-secondary {
  @apply px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded hover:bg-gray-300;
}

.btn-danger {
  @apply px-4 py-2 bg-red-600 text-white font-medium rounded hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
  .stats-dashboard {
    @apply p-4;
  }

  .dashboard-title {
    @apply text-2xl;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .stats-card {
    @apply p-4;
  }

  .card-actions {
    flex-direction: column;
  }

  .btn-action {
    @apply w-full;
  }
}
</style>
