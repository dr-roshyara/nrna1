<template>
  <div class="min-h-screen flex flex-col bg-neutral-50">
    <!-- Header -->
    <PublicDigitHeader />

    <!-- Main Content -->
    <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-neutral-900 mb-2">
            {{ $t('pages.election.select_election.heading') }}
          </h1>
          <p class="text-lg text-neutral-600">
            {{ $t('pages.election.select_election.subtitle') }}
          </p>
          <p class="text-neutral-500 mt-4">
            {{ $t('pages.election.select_election.instructions') }}
          </p>
        </div>

        <!-- Elections Grid -->
        <div v-if="activeElections.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-1">
          <div
            v-for="election in activeElections"
            :key="election.id"
            class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden border-l-4"
            :class="getElectionBorderClass(election)"
          >
            <!-- Election Card Header -->
            <div class="px-6 py-4 border-b border-neutral-200 bg-neutral-50">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h2 class="text-2xl font-bold text-neutral-900">
                    {{ election.name }}
                  </h2>
                  <div class="flex items-center gap-3 mt-2 flex-wrap">
                    <!-- Election Type Badge -->
                    <span
                      :aria-label="`${$t('pages.election.election_card.type')}: ${getElectionType(election)}`"
                      class="inline-flex px-3 py-1 rounded-full text-sm font-semibold"
                      :class="getElectionTypeClass(election)"
                    >
                      {{ getElectionType(election) }}
                    </span>
                    <!-- Status Badge -->
                    <span
                      :aria-label="`${$t('pages.election.election_card.status')}: ${$t('pages.election.election_card.active')}`"
                      class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800"
                    >
                      ✅ {{ $t('pages.election.election_card.active') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Election Card Body -->
            <div class="px-6 py-6 space-y-4">
              <!-- Description -->
              <div v-if="election.description" class="prose prose-sm max-w-none">
                <p class="text-neutral-700">
                  {{ election.description }}
                </p>
              </div>

              <!-- Election Details -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4 border-t border-b border-neutral-200">
                <!-- Voting Period -->
                <div>
                  <p class="text-sm text-neutral-600 font-semibold">
                    {{ $t('pages.election.select_election.voting_period') }}
                  </p>
                  <p class="text-neutral-900 mt-1 text-sm">
                    {{ formatDate(election.start_date) }} <br>
                    <span class="text-xs text-neutral-600">{{ $t('pages.election.election_card.voting_ends') }}</span><br>
                    <span class="font-semibold">{{ formatDate(election.end_date) }}</span>
                  </p>
                </div>

                <!-- Candidates (if available) -->
                <div>
                  <p class="text-sm text-neutral-600 font-semibold">
                    {{ $t('pages.election.select_election.candidates') }}
                  </p>
                  <p class="text-neutral-900 mt-1 text-lg">
                    {{ getCandidateCount(election) }}
                  </p>
                </div>
              </div>

              <!-- Election Info -->
              <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                <p class="text-sm text-primary-900">
                  <strong>{{ $t('pages.election.select_election.learn_more') }}</strong>
                </p>
                <p class="text-sm text-primary-700 mt-2">
                  {{ getElectionInfo(election) }}
                </p>
              </div>

              <!-- Action Button -->
              <div class="pt-4">
                <button
                  @click="selectElection(election)"
                  :aria-label="`${$t('pages.election.select_election.select_button')}: ${election.name}`"
                  class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {{ $t('pages.election.select_election.select_button') }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- No Elections Message -->
        <div
          v-else
          role="alert"
          class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center"
        >
          <p class="text-lg text-yellow-900 font-semibold">
            {{ $t('pages.election.select_election.no_elections') }}
          </p>
          <p class="text-yellow-700 mt-2">
            {{ $t('pages.election.messages.selection_required') }}
          </p>
          <InertiaLink
            href="/dashboard"
            class="mt-4 inline-block bg-neutral-600 hover:bg-neutral-700 text-white font-bold py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
          >
            {{ $t('pages.election.actions.back') }}
          </InertiaLink>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link as InertiaLink, usePage } from '@inertiajs/vue3'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

const props = defineProps({
  activeElections: {
    type: Array,
    default: () => [],
    description: 'Array of active elections passed from controller'
  },
  authUser: {
    type: Object,
    default: null
  }
})

const page = usePage()

/**
 * Get CSS class for election border based on type
 */
const getElectionBorderClass = (election) => {
  return election.type === 'demo' ? 'border-orange-400' : 'border-primary-400'
}

/**
 * Get election type display text with emoji
 */
const getElectionType = (election) => {
  const locale = page.props.locale || 'en'

  if (election.type === 'demo') {
    const typeText = locale === 'np' ? 'डेमो' : locale === 'de' ? 'Demo' : 'Demo'
    return `🧪 ${typeText}`
  }
  const typeText = locale === 'np' ? 'आधिकारिक' : locale === 'de' ? 'Offiziell' : 'Official'
  return `🗳️ ${typeText}`
}

/**
 * Get CSS class for election type badge
 */
const getElectionTypeClass = (election) => {
  return election.type === 'demo'
    ? 'bg-orange-100 text-orange-800'
    : 'bg-primary-100 text-primary-800'
}

/**
 * Format date for display based on locale
 */
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'

  try {
    const date = new Date(dateString)
    const locale = page.props.locale || 'en'

    // Select locale code for Intl.DateTimeFormat
    const localeCode = locale === 'np' ? 'ne-NP' : locale === 'de' ? 'de-DE' : 'en-US'

    return date.toLocaleDateString(localeCode, {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      timeZoneName: 'short'
    })
  } catch (e) {
    return dateString
  }
}

/**
 * Get candidate count or placeholder
 */
const getCandidateCount = (election) => {
  if (election.candidates_count !== undefined) {
    return election.candidates_count
  }
  return '—'
}

/**
 * Get election info text based on type and locale
 */
const getElectionInfo = (election) => {
  const locale = page.props.locale || 'en'

  if (election.type === 'demo') {
    if (locale === 'np') {
      return 'यो एक डेमो निर्वाचन हो। सबै प्रयोगकर्ताहरू परीक्षण गर्न मतदान गर्न सक्छन्। आफ्नो मत आधिकारिक परिणामहरूमा गणना गरिने छैन।'
    } else if (locale === 'de') {
      return 'Dies ist eine Demo-Wahl. Alle Benutzer können abstimmen, um das System zu testen. Ihre Stimmen werden nicht in den offiziellen Ergebnissen gezählt.'
    }
    return 'This is a demo election. All users can vote to test the system. Your votes will not be counted in official results.'
  }

  if (locale === 'np') {
    return 'यो एक आधिकारिक निर्वाचन हो। केवल योग्य मतदाताहरू मतदान अवधिमा मतदान गर्न सक्छन्।'
  } else if (locale === 'de') {
    return 'Dies ist eine offizielle Wahl. Nur berechtigte Wähler können während des Abstimmungszeitraums abstimmen.'
  }
  return 'This is an official election. Only eligible voters can participate during the voting period.'
}

/**
 * Select an election and redirect to voting code entry page
 */
const selectElection = (election) => {
  // Navigate to the code entry page for the selected election
  // Using window.location for a full page redirect to ensure server-side logic processes the selection
  window.location.href = `/code/create/${election.slug}`
}
</script>

<style scoped>
/* Ensure focus states are visible for accessibility */
button:focus-visible,
a:focus-visible {
  outline: 2px solid currentColor;
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: more) {
  .border-l-4 {
    border-left-width: 6px;
  }

  button {
    border: 1px solid currentColor;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
</style>

