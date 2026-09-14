<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const props = defineProps({
  organisation: Object,
  election: Object,
  voters: Object, // paginated: { data, current_page, last_page, total, links }
  stats: Object,  // { total, voted, not_voted, participation_percentage } — whole electorate, not the current page/filter
  filters: Object,
})

const backUrl = computed(() => route('organisations.voter-hub', props.organisation.slug))

const currentSort      = ref(props.filters?.sort ?? 'assigned_at')
const currentDirection = ref(props.filters?.direction ?? 'asc')
const currentStatus    = ref(props.filters?.status ?? '')
const currentVoted     = ref(props.filters?.voted ?? '')

const statusOptions = [
  { value: '',                   label: 'All' },
  { value: 'active',             label: 'Active' },
  { value: 'invited',            label: 'Invited' },
  { value: 'inactive',           label: 'Inactive' },
  { value: 'removed',            label: 'Removed' },
  { value: 'pending_suspension', label: 'Pending Suspension' },
]

const votedOptions = [
  { value: '',           label: 'All voting status' },
  { value: 'voted',       label: 'Voted' },
  { value: 'not_voted',   label: 'Not voted' },
]

const applyFilters = () => {
  router.get(
    route('organisations.elections.voters', {
      organisation: props.organisation.slug,
      election: props.election.slug,
    }),
    {
      sort: currentSort.value,
      direction: currentDirection.value,
      status: currentStatus.value || undefined,
      voted: currentVoted.value || undefined,
    },
    { preserveState: true, preserveScroll: true }
  )
}

const toggleSort = (column) => {
  if (currentSort.value === column) {
    currentDirection.value = currentDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    currentSort.value = column
    currentDirection.value = 'asc'
  }
  applyFilters()
}

// Status display: pending_suspension overrides membership status. The dedicated
// Voted column (not this Status column) is the sole voting-status indicator.
const displayStatus = (voter) => {
  if (voter.suspension_status === 'proposed') {
    return { label: 'Pending Suspension', cls: 'bg-amber-100 text-amber-800 border-amber-200' }
  }
  const map = {
    active:   { label: 'Active',   cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    invited:  { label: 'Invited',  cls: 'bg-primary-50 text-primary-700 border-primary-200' },
    inactive: { label: 'Inactive', cls: 'bg-slate-100 text-slate-600 border-slate-200' },
    removed:  { label: 'Removed',  cls: 'bg-danger-50 text-danger-700 border-danger-200' },
  }
  return map[voter.status] ?? { label: voter.status, cls: 'bg-slate-100 text-slate-600 border-slate-200' }
}

const sortIcon = (column) => {
  if (currentSort.value !== column) return '↕'
  return currentDirection.value === 'asc' ? '↑' : '↓'
}

const goToPage = (url) => {
  if (url) router.get(url, {}, { preserveState: true, preserveScroll: true })
}

// Same date-formatting convention as Organisations/VoterHub.vue's
// formatDate (toLocaleString instead of toLocaleDateString, since exact
// login time is operationally useful here). This page has no i18n/locale
// composable of its own (unlike VoterHub), so 'en-GB' is used directly,
// consistent with this file's own existing plain-English UI text.
// NULL is a distinct, real fact (never logged in) — must read as "Never",
// not an empty/blank cell.
const formatLastLogin = (d) => {
  if (!d) return 'Never'
  return new Date(d).toLocaleString('en-GB', {
    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit',
  })
}
</script>

<template>
  <ElectionLayout>
    <div class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
      <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
          <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4">
              <a :href="backUrl" class="hover:text-slate-700">{{ organisation.name }}</a>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span>{{ election.name }}</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span class="text-slate-700 font-medium">Voters</span>
            </nav>
            <h1 class="text-4xl font-black text-slate-900">Registered Voters</h1>
            <p class="text-lg text-slate-600 mt-2">
              {{ voters.total }} voter{{ voters.total !== 1 ? 's' : '' }} registered
            </p>
          </div>
          <a
            :href="backUrl"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
          </a>
        </div>

        <!-- Participation stats — compact, not a dashboard card: the voter
             table below is the primary thing to operate on this page. -->
        <div
          v-if="stats"
          class="bg-white rounded-xl border border-slate-200 px-5 py-3 mb-4 flex items-center gap-6 flex-wrap text-sm"
          role="group"
          aria-label="Voting participation summary"
        >
          <span>
            <span class="font-bold text-green-700">{{ stats.voted }}</span>
            <span class="text-slate-500"> voted</span>
          </span>
          <span>
            <span class="font-bold text-slate-700">{{ stats.not_voted }}</span>
            <span class="text-slate-500"> not voted</span>
          </span>
          <span>
            <span class="font-bold text-primary-700">{{ stats.participation_percentage }}%</span>
            <span class="text-slate-500"> participation</span>
          </span>
          <span class="text-xs text-slate-400 ml-auto">{{ stats.total }} total voters</span>
        </div>

        <!-- Filter bar -->
        <div class="bg-white rounded-xl border border-slate-200 px-5 py-4 mb-6 flex items-center gap-4 flex-wrap">
          <label for="status-filter" class="text-sm font-medium text-slate-600">Filter by status:</label>
          <select
            id="status-filter"
            v-model="currentStatus"
            @change="applyFilters"
            class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400"
          >
            <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>

          <label for="voted-filter" class="text-sm font-medium text-slate-600">Filter by voting status:</label>
          <select
            id="voted-filter"
            data-testid="voted-filter"
            v-model="currentVoted"
            @change="applyFilters"
            class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400"
          >
            <option v-for="opt in votedOptions" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>

          <span class="text-xs text-slate-400 ml-auto">
            Page {{ voters.current_page }} of {{ voters.last_page }}
          </span>
        </div>

        <!-- Empty state -->
        <div
          v-if="voters.data.length === 0"
          class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200"
        >
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          <p class="text-slate-600 font-semibold">
            {{ currentStatus ? 'No voters match your filter' : 'No voters registered yet' }}
          </p>
          <p class="text-slate-500 text-sm mt-1">
            {{ currentStatus ? 'Try clearing the filter to see all voters.' : 'Voters will appear here once assigned to this election.' }}
          </p>
        </div>

        <!-- Voter table -->
        <div v-else class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
          <!-- overflow-x-auto: four columns no longer reliably fit small
               screens — scroll the table horizontally rather than break the
               page layout (established pattern elsewhere in this app, e.g.
               Election/ReceiptCodes.vue). -->
          <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-100 bg-slate-50">
                <th class="text-left px-6 py-3 font-semibold text-slate-600">
                  <button
                    @click="toggleSort('name')"
                    class="flex items-center gap-1 hover:text-slate-900 transition-colors"
                  >
                    Name <span class="text-slate-400 text-xs">{{ sortIcon('name') }}</span>
                  </button>
                </th>
                <th class="text-left px-6 py-3 font-semibold text-slate-600">
                  <button
                    @click="toggleSort('status')"
                    class="flex items-center gap-1 hover:text-slate-900 transition-colors"
                  >
                    Status <span class="text-slate-400 text-xs">{{ sortIcon('status') }}</span>
                  </button>
                </th>
                <th class="text-left px-6 py-3 font-semibold text-slate-600">Voted</th>
                <th class="text-left px-6 py-3 font-semibold text-slate-600">Last Login</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="voter in voters.data"
                :key="voter.id"
                class="hover:bg-slate-50 transition-colors"
              >
                <td class="px-6 py-3.5 font-medium text-slate-900">
                  {{ voter.name }}
                </td>
                <td class="px-6 py-3.5">
                  <span
                    :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border', displayStatus(voter).cls]"
                  >
                    {{ displayStatus(voter).label }}
                  </span>
                </td>
                <td class="px-6 py-3.5">
                  <span
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border',
                      voter.has_voted
                        ? 'bg-green-100 text-green-800 border-green-200'
                        : 'bg-slate-100 text-slate-600 border-slate-200',
                    ]"
                  >
                    {{ voter.has_voted ? 'Voted' : 'Not voted' }}
                  </span>
                </td>
                <td class="px-6 py-3.5 text-slate-600 whitespace-nowrap">
                  {{ formatLastLogin(voter.last_login_at) }}
                </td>
              </tr>
            </tbody>
          </table>
          </div>

          <!-- Pagination -->
          <div v-if="voters.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t border-slate-100">
            <button
              @click="goToPage(voters.prev_page_url)"
              :disabled="!voters.prev_page_url"
              class="px-4 py-2 text-sm font-medium text-slate-600 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
            >
              Previous
            </button>
            <span class="text-sm text-slate-500">
              Page {{ voters.current_page }} of {{ voters.last_page }}
            </span>
            <button
              @click="goToPage(voters.next_page_url)"
              :disabled="!voters.next_page_url"
              class="px-4 py-2 text-sm font-medium text-slate-600 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
            >
              Next
            </button>
          </div>
        </div>

      </div>
    </div>
  </ElectionLayout>
</template>

