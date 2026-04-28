<template>
  <div class="state-machine-panel">
    <!-- Timeline Header - 3 Column Layout -->
    <div class="timeline-header-3col">
      <!-- Column 1: Title -->
      <div class="header-col col-1">
        <h2 class="timeline-title">{{ t.timeline.title }}</h2>
        <p class="timeline-subtitle">{{ t.timeline.currently_in }} <span class="phase-name-current">{{ currentPhaseLabel }}</span> {{ t.timeline.phase }}</p>
      </div>

      <!-- Column 2: Learn More Link -->
      <div class="header-col col-2">
        <a
          :href="route('tutorials.election-journey')"
          target="_blank"
          rel="noopener noreferrer"
          class="learn-more-link"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>{{ t.timeline.learn_more }}</span>
        </a>
      </div>

      <!-- Column 3: Progress -->
      <div class="header-col col-3">
        <div class="header-progress">
          <div class="progress-number">{{ completedPhasesCount }}/5</div>
          <div class="progress-label">{{ t.timeline.phases_complete }}</div>
        </div>
      </div>
    </div>

    <!-- Overall Progress Bar -->
    <div class="progress-section">
      <div class="progress-bar-container">
        <div class="progress-bar">
          <div class="progress-fill" :style="{ width: progressPercentage + '%' }"></div>
        </div>
      </div>
      <div class="progress-text">{{ Math.round(progressPercentage) }}% {{ t.timeline.progress }}</div>
    </div>

    <!-- Horizontal Timeline -->
    <div class="timeline-wrapper">
      <!-- Connecting Line -->
      <div class="timeline-line"></div>

      <!-- Phase Markers and Details -->
      <div class="timeline-phases">
        <div
          v-for="(phase, index) in phases"
          :key="phase.state"
          class="timeline-phase mx-auto"
          :class="[
            `phase-${phase.state}`,
            {
              'is-current': phase.state === stateMachine.currentState,
              'is-completed': isPhaseCompleted(phase.state),
              'is-upcoming': isPhaseUpcoming(phase.state),
            }
          ]"
        >
          <!-- Phase Marker -->
          <div class="phase-marker">
            <div class="marker-outer">
              <div class="marker-inner">
                <span v-if="isPhaseCompleted(phase.state)" class="marker-check">✓</span>
                <span v-else>{{ index + 1 }}</span>
              </div>
            </div>
            <div v-if="phase.state === stateMachine.currentState" class="marker-pulse"></div>
          </div>

          <!-- Phase Content -->
          <div class="phase-content">
            <!-- Icon and Name -->
            <div class="phase-header">
              <span class="phase-icon">{{ phase.icon }}</span>
              <h3 class="phase-name">{{ getPhaseLabel(phase.state) }}</h3>
            </div>

            <!-- Status Badge -->
            <div class="status-badge" :class="getStatusClass(phase.state)">
              <span v-if="phase.state === stateMachine.currentState" class="status-label">
                <svg class="status-dot" viewBox="0 0 8 8" fill="currentColor">
                  <circle cx="4" cy="4" r="3"/>
                </svg>
                Active
              </span>
              <span v-else-if="isPhaseCompleted(phase.state)" class="status-label">
                <svg class="status-check" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Done
              </span>
              <span v-else class="status-label upcoming">Upcoming</span>
            </div>

            <!-- Metrics -->
            <div v-if="getPhaseMetrics(phase.state)" class="phase-metrics">
              <div v-for="(value, metric) in getPhaseMetrics(phase.state)" :key="metric">
                <span class="metric-label">{{ getMetricLabel(metric) }}:</span>
                <span class="metric-value">{{ value }}</span>
              </div>
            </div>

            <!-- Dates -->
            <div v-if="getPhaseDates(phase.state)" class="phase-dates">
              <div v-if="getPhaseDates(phase.state).start">
                <span class="date-label">Start:</span>
                <span class="date-value">{{ formatDate(getPhaseDates(phase.state).start) }}</span>
              </div>
              <div v-if="getPhaseDates(phase.state).end">
                <span class="date-label">End:</span>
                <span class="date-value">{{ formatDate(getPhaseDates(phase.state).end) }}</span>
              </div>
            </div>

            <!-- Actions -->
            <div v-if="hasActions(phase.state)" class="phase-actions">
              <button
                v-if="canCompletePhase(phase.state)"
                class="action-btn btn-complete y-focus h-auto"
                @click="$emit('phase-completed', phase.state)"
              >
                Complete
              </button>

              <!-- Lock Voting button (voting phase — NEW) -->
              <button
                v-if="canLockVotingPhase(phase.state)"
                class="action-btn y-focus h-auto font-bold px-4 py-2 rounded-lg transition-all duration-200 hover:scale-105 active:scale-95"
                style="background: #f59e0b; color: #fff; border: none; cursor: pointer;"
                @click="$emit('lock-voting')"
              >
                🔒 Lock & Start Voting
              </button>

              <!-- Locked Badge (for locked phases) -->
              <div v-if="isPhaseLockedFromEdit(phase.state)" class="locked-badge">
                <svg class="lock-icon" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <span class="lock-text">{{ getLockReason(phase.state) }}</span>
              </div>

              <!-- Countdown Timer (for active voting phase) -->
              <div v-if="phase.state === 'voting' && isPhaseActive(phase.state)" class="countdown-timer">
                <svg class="timer-icon" viewBox="0 0 24 24" fill="currentColor">
                  <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/>
                  <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"/>
                </svg>
                <span class="timer-label">Voting ends in</span>
                <span class="timer-value">{{ getCountdownTime(phase.state) }}</span>
              </div>

              <!-- Note: Use Timeline page to edit dates -->
              <!-- Dates are read-only here for clarity -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Phase Details Section -->
    <div v-if="selectedPhase" class="phase-details">
      <div class="details-header">
        <h3>{{ getPhaseLabel(selectedPhase.state) }} Details</h3>
        <button class="btn-close" @click="selectedPhase = null">×</button>
      </div>
      <div class="details-content">
        <p>{{ selectedPhase.description }}</p>
        <div v-if="selectedPhase.requirements" class="requirements">
          <h4>Requirements:</h4>
          <ul>
            <li v-for="req in selectedPhase.requirements" :key="req">{{ req }}</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Date modal removed - use Timeline page to edit dates -->
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { route } from 'ziggy-js'
import { useI18n } from 'vue-i18n'
import pageDe from '@/locales/pages/Election/Management/de.json'
import pageEn from '@/locales/pages/Election/Management/en.json'
import pageNp from '@/locales/pages/Election/Management/np.json'

const props = defineProps({
  stateMachine: {
    type: Object,
    required: true,
  },
  election: {
    type: Object,
    required: true,
  },
  organisation: {
    type: Object,
    default: null,
  },
  allowedActions: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['phase-completed', 'lock-voting'])

// Translation
const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.de)

const selectedPhase = ref(null)
const currentTime = ref(new Date())
let countdownInterval = null

// Update countdown timer every second
// Equalize column heights on desktop
const equalizePhaseHeights = () => {
  if (window.innerWidth < 1024) return // Only on desktop

  setTimeout(() => {
    const phases = document.querySelectorAll('.timeline-phase')
    if (phases.length === 0) return

    // Reset heights to auto
    phases.forEach(phase => {
      phase.style.height = 'auto'
    })

    // Get max height
    let maxHeight = 0
    phases.forEach(phase => {
      maxHeight = Math.max(maxHeight, phase.offsetHeight)
    })

    // Set all to max height
    phases.forEach(phase => {
      phase.style.height = maxHeight + 'px'
    })
  }, 100)
}

onMounted(() => {
  countdownInterval = setInterval(() => {
    currentTime.value = new Date()
  }, 1000)

  // Equalize column heights
  equalizePhaseHeights()
  window.addEventListener('resize', equalizePhaseHeights)
})

onUnmounted(() => {
  if (countdownInterval) clearInterval(countdownInterval)
  window.removeEventListener('resize', equalizePhaseHeights)
})

const phases = [
  {
    state: 'administration',
    icon: '⚙️',
    description: 'Setup the election structure including posts, voters, and committee members.',
    requirements: ['At least one post', 'At least one voter', 'Election name and configuration'],
  },
  {
    state: 'nomination',
    icon: '📋',
    description: 'Accept and approve candidate applications.',
    requirements: ['At least one approved candidate', 'No pending candidacies'],
  },
  {
    state: 'voting',
    icon: '🗳️',
    description: 'Members cast their votes in a secure voting window.',
    requirements: ['Voting dates must be set', 'Voting window must be active'],
  },
  {
    state: 'results_pending',
    icon: '⏳',
    description: 'Voting period is complete, awaiting results publication.',
    requirements: ['Voting period must be finished'],
  },
  {
    state: 'results',
    icon: '📊',
    description: 'Results are published and final.',
    requirements: ['Manual publication required'],
  },
]

const getPhaseLabel = (state) => {
  return t.value.states?.[state] || state
}

const currentPhaseLabel = computed(() => {
  return getPhaseLabel(props.stateMachine.currentState)
})

const editingPhaseLabel = computed(() => {
  return getPhaseLabel(editingPhaseState.value)
})

const phaseStates = computed(() => ({
  administration: props.election.administration_completed,
  nomination: props.election.nomination_completed,
  voting: props.election.voting_ends_at && new Date() > new Date(props.election.voting_ends_at),
  results_pending: props.election.results_published_at !== null,
  results: props.election.results_published,
}))

const completedPhasesCount = computed(() => {
  return phases.filter(p => isPhaseCompleted(p.state)).length
})

const progressPercentage = computed(() => {
  return (completedPhasesCount.value / 5) * 100
})

const isPhaseCompleted = (state) => {
  return phaseStates.value[state] ?? false
}

const isPhaseUpcoming = (state) => {
  return !isPhaseCompleted(state) && state !== props.stateMachine.currentState
}

const getStatusClass = (state) => {
  if (state === props.stateMachine.currentState) return 'status-active'
  if (isPhaseCompleted(state)) return 'status-completed'
  return 'status-upcoming'
}

const getPhaseMetrics = (state) => {
  const metrics = {}
  switch (state) {
    case 'administration':
      metrics.posts = props.election.posts_count || 0
      metrics.voters = props.election.voters_count || 0
      break
    case 'nomination':
      metrics.candidates = props.election.candidates_count || 0
      metrics.pending = props.election.pending_candidacies_count || 0
      break
    case 'voting':
      metrics.codes = props.election.voting_codes_count || 0
      metrics.votes = props.election.votes_count || 0
      break
    case 'results_pending':
      metrics.awaiting = 'Publication'
      break
    case 'results':
      metrics.published = 'Yes'
      break
  }
  return Object.keys(metrics).length > 0 ? metrics : null
}

const getMetricLabel = (metric) => {
  const labels = {
    posts: 'Posts',
    voters: 'Voters',
    candidates: 'Candidates',
    pending: 'Pending',
    codes: 'Codes',
    votes: 'Votes',
    awaiting: 'Status',
    published: 'Status',
  }
  return labels[metric] || metric
}

const getPhaseDates = (state) => {
  const dates = {}
  switch (state) {
    case 'administration':
      if (props.election.administration_suggested_start)
        dates.start = props.election.administration_suggested_start
      if (props.election.administration_suggested_end)
        dates.end = props.election.administration_suggested_end
      break
    case 'nomination':
      if (props.election.nomination_suggested_start)
        dates.start = props.election.nomination_suggested_start
      if (props.election.nomination_suggested_end)
        dates.end = props.election.nomination_suggested_end
      break
    case 'voting':
      if (props.election.voting_starts_at)
        dates.start = props.election.voting_starts_at
      if (props.election.voting_ends_at)
        dates.end = props.election.voting_ends_at
      break
    case 'results_pending':
    case 'results':
      if (props.election.results_published_at)
        dates.start = props.election.results_published_at
      break
  }
  return Object.keys(dates).length > 0 ? dates : null
}

const formatDate = (date) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const hasActions = (state) => {
  return canCompletePhase(state) || canUpdateDates(state) || canLockVotingPhase(state)
}

const canCompletePhase = (state) => {
  if (state === 'administration') return props.allowedActions.includes('complete_administration')
  if (state === 'nomination') return props.allowedActions.includes('open_voting')
  return false
}

const canLockVotingPhase = (state) => {
  if (state !== 'voting') return false
  return !props.election.voting_locked && props.allowedActions.includes('lock_voting')
}

const canUpdateDates = (state) => {
  // Matches backend canUpdatePhaseDates() logic
  // Can only update dates for phases that haven't started yet

  switch (state) {
    case 'administration':
      return !props.election.administration_completed

    case 'nomination':
      return !props.election.nomination_completed

    case 'voting':
      // Can update only if voting hasn't started and isn't locked
      return !props.election.voting_locked &&
             (!props.election.voting_starts_at ||
              new Date() < new Date(props.election.voting_starts_at))

    case 'results_pending':
    case 'results':
      // Never editable (final phases)
      return false

    default:
      return false
  }
}

// NEW: Check if phase is locked from editing
const isPhaseLockedFromEdit = (state) => {
  switch (state) {
    case 'administration':
      return props.election.administration_completed
    case 'nomination':
      return props.election.nomination_completed
    case 'voting':
      return props.election.voting_locked ||
             (props.election.voting_starts_at && new Date() >= new Date(props.election.voting_starts_at))
    case 'results_pending':
    case 'results':
      return true // Always locked
    default:
      return false
  }
}

// NEW: Get reason why phase is locked
const getLockReason = (state) => {
  switch (state) {
    case 'administration':
      return 'Admin Locked'
    case 'nomination':
      return 'Nomination Locked'
    case 'voting':
      if (props.election.voting_locked) return 'Voting Closed'
      if (props.election.voting_starts_at && new Date() >= new Date(props.election.voting_starts_at))
        return 'In Progress'
      return 'Locked'
    case 'results_pending':
      return 'Results Pending'
    case 'results':
      return 'Results Published'
    default:
      return 'Locked'
  }
}

// NEW: Check if phase is currently active
const isPhaseActive = (state) => {
  switch (state) {
    case 'voting':
      return props.election.voting_starts_at && props.election.voting_ends_at &&
             new Date() >= new Date(props.election.voting_starts_at) &&
             new Date() < new Date(props.election.voting_ends_at)
    default:
      return state === props.stateMachine.currentState
  }
}

// NEW: Get countdown time until voting ends
const getCountdownTime = (state) => {
  if (state !== 'voting' || !props.election.voting_ends_at) return '--:--:--'

  const endTime = new Date(props.election.voting_ends_at)
  const now = currentTime.value
  const diffMs = endTime - now

  if (diffMs <= 0) return '00:00:00'

  const hours = Math.floor(diffMs / (1000 * 60 * 60))
  const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60))
  const seconds = Math.floor((diffMs % (1000 * 60)) / 1000)

  return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
}

// NEW: Get reason why update is disabled
const getUpdateDisabledReason = (state) => {
  if (isPhaseCompleted(state)) {
    return 'Phase is complete. Dates cannot be edited.'
  }
  if (state === 'voting') {
    if (props.election.voting_locked) {
      return 'Voting is locked. Dates cannot be edited.'
    }
    if (props.election.voting_starts_at && new Date() >= new Date(props.election.voting_starts_at)) {
      return 'Voting has started. Dates cannot be edited.'
    }
  }
  return 'Phase is locked. Dates cannot be edited.'
}

const openDateModal = (state) => {
  editingPhaseState.value = state
  const dates = getPhaseDates(state)

  // Convert dates to datetime-local format (YYYY-MM-DDTHH:mm)
  const formatDateForInput = (dateString) => {
    if (!dateString) return ''
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return ''
    return date.toISOString().slice(0, 16)
  }

  dateForm.value = {
    start: formatDateForInput(dates?.start),
    end: formatDateForInput(dates?.end),
  }
  dateError.value = ''
  showDateModal.value = true
}

const closeModal = () => {
  showDateModal.value = false
  editingPhaseState.value = null
  dateForm.value = { start: '', end: '' }
  dateError.value = ''
}

const saveDates = () => {
  console.log('💾 saveDates called, form:', dateForm.value)

  if (dateForm.value.start && dateForm.value.end && dateForm.value.start >= dateForm.value.end) {
    dateError.value = 'End date must be after start date'
    console.error('❌ Date validation failed: end before start')
    return
  }

  const eventPayload = {
    phase: editingPhaseState.value,
    dates: dateForm.value,
  }

  console.log('🎯 Emitting dates-updated event:', eventPayload)
  emit('dates-updated', eventPayload)
  closeModal()
}
</script>

<style scoped>
:root {
  --color-admin: #3b82f6;
  --color-admin-light: #dbeafe;
  --color-nomination: #10b981;
  --color-nomination-light: #d1fae5;
  --color-voting: #8b5cf6;
  --color-voting-light: #ede9fe;
  --color-pending: #f97316;
  --color-pending-light: #ffedd5;
  --color-results: #eab308;
  --color-results-light: #fef08a;
  --color-text-primary: #1f2937;
  --color-text-secondary: #6b7280;
  --color-border: #e5e7eb;
}

.state-machine-panel {
  background: white;
  border-radius: 16px;
  padding: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
}

@media (min-width: 768px) {
  .state-machine-panel {
    padding: 2rem;
  }
}

.timeline-header-3col {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
  align-items: center;
}

@media (min-width: 768px) {
  .timeline-header-3col {
    grid-template-columns: 1fr auto 1fr;
    gap: 2rem;
    align-items: center;
    margin-bottom: 2.5rem;
  }
}

.header-col {
  display: flex;
  flex-direction: column;
}

.header-col.col-1 {
  grid-column: 1 / -1;
}

@media (min-width: 768px) {
  .header-col.col-1 {
    grid-column: 1;
    text-align: left;
  }

  .header-col.col-2 {
    grid-column: 2;
    text-align: center;
  }

  .header-col.col-3 {
    grid-column: 3;
    text-align: right;
  }
}

.learn-more-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  color: #1e40af;
  font-weight: 600;
  border-radius: 0.5rem;
  text-decoration: none;
  transition: all 0.3s ease;
  white-space: nowrap;
  font-size: 0.95rem;
}

.learn-more-link:hover {
  background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 100%);
  color: #1e3a8a;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
}

.learn-more-link svg {
  width: 1.25rem;
  height: 1.25rem;
  flex-shrink: 0;
}

.timeline-header {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}

@media (min-width: 768px) {
  .timeline-header {
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    margin-bottom: 2.5rem;
  }
}

.timeline-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-text-primary);
  margin: 0 0 0.5rem 0;
}

@media (min-width: 768px) {
  .timeline-title {
    font-size: 1.75rem;
  }
}

.timeline-subtitle {
  font-size: 0.875rem;
  color: var(--color-text-secondary);
  margin: 0;
}

@media (min-width: 768px) {
  .timeline-subtitle {
    font-size: 0.95rem;
  }
}

.phase-name-current {
  font-weight: 600;
  color: var(--color-admin);
}

.header-progress {
  text-align: right;
  padding: 0.75rem 1.5rem;
  background: #f9fafb;
  border-radius: 12px;
  border: 1px solid var(--color-border);
}

.progress-number {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--color-admin);
  line-height: 1;
}

.progress-label {
  font-size: 0.75rem;
  color: var(--color-text-secondary);
  margin-top: 0.25rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-weight: 600;
}

/* Progress Section */
.progress-section {
  margin-bottom: 2.5rem;
}

.progress-bar-container {
  height: 8px;
  background: var(--color-border);
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 0.75rem;
}

.progress-bar {
  height: 100%;
  position: relative;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #3b82f6 0%, #10b981 25%, #8b5cf6 50%, #f97316 75%, #eab308 100%);
  transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 0 12px rgba(59, 130, 246, 0.3);
}

.progress-text {
  font-size: 0.875rem;
  color: var(--color-text-secondary);
  font-weight: 500;
}

/* ========== TIMELINE WRAPPER ========== */
/* Mobile: No scroll */
.timeline-wrapper {
  position: relative;
  padding: 1rem 0;
  overflow-x: visible;
  overflow-y: visible;
  scrollbar-width: thin;
}

/* Tablet: Enable horizontal scroll */
@media (min-width: 768px) {
  .timeline-wrapper {
    padding: 2rem 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
}

/* Desktop: No scroll needed */
@media (min-width: 1024px) {
  .timeline-wrapper {
    overflow-x: visible;
  }
}

/* ========== TIMELINE PHASES - MOBILE FIRST ========== */
.timeline-phases {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  position: relative;
  z-index: 2;
  width: 100%;
}

.timeline-phase {
  width: 100%;
  max-width: none;
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  opacity: 0.65;
  transition: all 0.3s ease;
}

/* Tablet: Horizontal scroll */
@media (min-width: 768px) and (max-width: 1023px) {
  .timeline-phases {
    flex-direction: row;
    min-width: min-content;
    gap: 1rem;
    width: auto;
  }

  .timeline-phase {
    width: auto;
    min-width: 240px;
    max-width: 240px;
    flex: 0 0 auto;
  }
}

/* Desktop: Grid layout */
@media (min-width: 1024px) {
  .timeline-phases {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    grid-auto-flow: row !important;
    gap: 1.5rem;
    justify-items: center;
    width: 100% !important;
    min-width: unset !important;
    flex-direction: unset !important;
  }

  .timeline-phase {
    width: 100%;
    max-width: none;
  }
}

.timeline-wrapper::-webkit-scrollbar {
  height: 6px;
}

.timeline-wrapper::-webkit-scrollbar-track {
  background: var(--color-border);
  border-radius: 3px;
}

.timeline-wrapper::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

/* ========== TIMELINE LINE ========== */
/* Mobile: Hidden */
.timeline-line {
  position: absolute;
  top: 3rem;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #3b82f6 0%, #10b981 20%, #8b5cf6 40%, #f97316 60%, #eab308 80%);
  border-radius: 2px;
  z-index: 1;
  display: none;
}

/* Tablet: Show connecting line */
@media (min-width: 768px) and (max-width: 1023px) {
  .timeline-line {
    display: block;
  }
}

/* Desktop: Hide (grid layout) */
@media (min-width: 1024px) {
  .timeline-line {
    display: none;
  }
}


.timeline-phase.is-current {
  opacity: 1;
  transform: scale(1.08);
}

.timeline-phase.is-completed {
  opacity: 0.85;
}

.timeline-phase:hover {
  opacity: 1;
}

/* Phase Marker */
.phase-marker {
  position: relative;
  margin-bottom: 1.5rem;
}

.marker-outer {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: white;
  border: 3px solid var(--color-border);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  transition: all 0.3s ease;
}

.timeline-phase.phase-administration .marker-outer {
  border-color: var(--color-admin);
  background: linear-gradient(135deg, var(--color-admin) 0%, var(--color-admin-light) 100%);
}

.timeline-phase.phase-nomination .marker-outer {
  border-color: var(--color-nomination);
  background: linear-gradient(135deg, var(--color-nomination) 0%, var(--color-nomination-light) 100%);
}

.timeline-phase.phase-voting .marker-outer {
  border-color: var(--color-voting);
  background: linear-gradient(135deg, var(--color-voting) 0%, var(--color-voting-light) 100%);
}

.timeline-phase.phase-results_pending .marker-outer {
  border-color: var(--color-pending);
  background: linear-gradient(135deg, var(--color-pending) 0%, var(--color-pending-light) 100%);
}

.timeline-phase.phase-results .marker-outer {
  border-color: var(--color-results);
  background: linear-gradient(135deg, var(--color-results) 0%, var(--color-results-light) 100%);
}

.marker-inner {
  font-weight: 700;
  font-size: 1.5rem;
  color: #1f2937;
  line-height: 1;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
}

.timeline-phase.is-completed .marker-inner {
  font-size: 1.25rem;
}

.marker-check {
  display: block;
}

.marker-pulse {
  position: absolute;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  border: 2px solid var(--color-admin);
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: pulse-ring 2s ease-out infinite;
}

@keyframes pulse-ring {
  0% {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
  }
  100% {
    transform: translate(-50%, -50%) scale(1.3);
    opacity: 0;
  }
}

/* Phase Content */
.phase-content {
  flex: 1;
  width: 100%;
}

.phase-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.phase-icon {
  font-size: 2rem;
  line-height: 1;
}

.phase-name {
  font-size: 1rem;
  font-weight: 800;
  color: var(--color-text-primary);
  margin: 0;
  letter-spacing: -0.01em;
}

/* Status Badge */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  margin-bottom: 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}

.status-active {
  background: rgba(59, 130, 246, 0.1);
  color: var(--color-admin);
  animation: pulse-subtle 2s ease-in-out infinite;
}

@keyframes pulse-subtle {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.status-completed {
  background: rgba(16, 185, 129, 0.1);
  color: var(--color-nomination);
}

.status-upcoming {
  background: rgba(107, 114, 128, 0.1);
  color: var(--color-text-secondary);
}

.status-dot,
.status-check {
  width: 0.75rem;
  height: 0.75rem;
}

.status-label {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

/* Metrics */
.phase-metrics {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  margin-bottom: 0.75rem;
  font-size: 0.8rem;
  text-align: center;
}

.phase-metrics > div {
  display: flex;
  flex-direction: row;
  gap: 0.25rem;
  align-items: baseline;
  justify-content: center;
}

.metric-value {
  font-weight: 700;
  color: var(--color-text-primary);
}

.metric-label {
  color: var(--color-text-secondary);
  font-size: 0.75rem;
  font-weight: 700;
}

/* Dates */
.phase-dates {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  padding: 0.75rem;
  background: #f9fafb;
  border-radius: 6px;
  margin-bottom: 0.75rem;
  font-size: 0.75rem;
  text-align: center;
}

.phase-dates > div {
  display: flex;
  flex-direction: row;
  gap: 0.25rem;
  align-items: baseline;
  justify-content: center;
}

.date-label {
  color: var(--color-text-secondary);
  font-weight: 700;
  text-transform: capitalize;
  letter-spacing: 0.5px;
  min-width: 35px;
}

.date-value {
  color: var(--color-text-primary);
  font-weight: 500;
}

/* Actions */
.phase-actions {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.action-btn {
  padding: 0.5rem 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.btn-complete {
  background: #3b82f6;
  color: white;
  font-weight: 700;
  padding: 0.75rem 1.5rem;
  font-size: 0.875rem;
  border: none;
}

.btn-complete:hover {
  background: #2563eb;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
}

.btn-dates {
  background: #6366f1;
  color: white;
  border: none;
  font-weight: 700;
  padding: 0.75rem 1.5rem;
  font-size: 0.875rem;
}

.btn-dates:hover {
  background: #4f46e5;
  box-shadow: 0 4px 6px rgba(99, 102, 241, 0.3);
  transform: translateY(-1px);
}

/* Disabled state for Update Dates button */
.action-btn.disabled {
  background: #d1d5db;
  color: #6b7280;
  cursor: not-allowed;
  opacity: 0.7;
}

.action-btn.disabled:hover {
  background: #d1d5db;
  transform: none;
  box-shadow: none;
}

/* Locked Badge - Prominent visual indicator */
.locked-badge {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.625rem 1rem;
  background: #fee2e2;
  border: 2px solid #ef4444;
  border-radius: 8px;
  color: #991b1b;
  font-weight: 700;
  font-size: 0.875rem;
  animation: badge-appear 0.3s ease-out;
}

.locked-badge .lock-icon {
  width: 16px;
  height: 16px;
  fill: #ef4444;
  flex-shrink: 0;
}

.locked-badge .lock-text {
  display: block;
  letter-spacing: 0.3px;
  text-transform: uppercase;
}

@keyframes badge-appear {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Countdown Timer - For active voting phase */
.countdown-timer {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
  border: 2px solid #f59e0b;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.875rem;
  color: #92400e;
}

.countdown-timer .timer-icon {
  width: 18px;
  height: 18px;
  color: #f59e0b;
  flex-shrink: 0;
  animation: spin-slow 3s linear infinite;
}

.countdown-timer .timer-label {
  display: block;
  font-weight: 600;
  font-size: 0.75rem;
  opacity: 0.85;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.countdown-timer .timer-value {
  display: block;
  font-family: 'Courier New', monospace;
  font-weight: 700;
  font-size: 1rem;
  color: #b45309;
  letter-spacing: 1px;
  animation: pulse-gentle 1.5s ease-in-out infinite;
}

@keyframes spin-slow {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes pulse-gentle {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.85;
  }
}

/* Responsive - Uses mobile-first approach with min-width media queries */

/* Modal Styles */
.date-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.date-modal {
  background: white;
  border-radius: 12px;
  box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid var(--color-border);
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  color: var(--color-text-primary);
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: var(--color-text-secondary);
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--color-text-primary);
  margin-bottom: 0.5rem;
}

.form-input {
  width: 100%;
  padding: 1.25rem;
  border: 1px solid var(--color-border);
  border-radius: 6px;
  font-size: 1.5rem;
  font-family: inherit;
}

.form-input:focus {
  outline: none;
  border-color: var(--color-admin);
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.error-message {
  color: #dc2626;
  font-size: 0.875rem;
  margin-top: 1rem;
  padding: 0.75rem;
  background: #fee2e2;
  border-radius: 6px;
}

.modal-footer {
  display: flex;
  gap: 1rem;
  padding: 1.5rem;
  border-top: 1px solid var(--color-border);
  justify-content: flex-end;
}

.btn {
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.95rem;
}

.btn-primary {
  background: #3b82f6;
  color: white;
  font-weight: 700;
}

.btn-primary:hover {
  background: #2563eb;
  box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
  transform: translateY(-1px);
}

.btn-secondary {
  background: #6b7280;
  color: white;
  font-weight: 700;
  border: none;
}

.btn-secondary:hover {
  background: #4b5563;
  box-shadow: 0 4px 6px rgba(107, 114, 128, 0.3);
  transform: translateY(-1px);
}

/* Phase Details Section */
.phase-details {
  margin-top: 2rem;
  padding: 1.5rem;
  background: #f9fafb;
  border-radius: 12px;
  border: 1px solid var(--color-border);
}

.details-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.details-header h3 {
  margin: 0;
  font-size: 1.125rem;
  color: var(--color-text-primary);
}

.details-content {
  color: var(--color-text-secondary);
  line-height: 1.6;
}

.requirements {
  margin-top: 1rem;
}

.requirements h4 {
  margin: 0 0 0.75rem 0;
  font-size: 0.95rem;
  color: var(--color-text-primary);
}

.requirements ul {
  margin: 0;
  padding-left: 1.5rem;
}

.requirements li {
  margin-bottom: 0.5rem;
}
</style>
