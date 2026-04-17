<script setup>
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const props = defineProps({
  organisation: Object,
  election: Object,
  voters: Object, // paginated: { data, current_page, last_page, total, links }
  filters: Object,
})

const backUrl = computed(() => route('organisations.voter-hub', props.organisation.slug))

const currentSort      = ref(props.filters?.sort ?? 'assigned_at')
const currentDirection = ref(props.filters?.direction ?? 'asc')
const currentStatus    = ref(props.filters?.status ?? '')

const statusOptions = [
  { value: '',                   label: 'All' },
  { value: 'active',             label: 'Active' },
  { value: 'invited',            label: 'Invited' },
  { value: 'inactive',           label: 'Inactive' },
  { value: 'removed',            label: 'Removed' },
  { value: 'pending_suspension', label: 'Pending Suspension' },
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

// Status display with priority: pending_suspension > voted > active > invited > inactive > removed
const displayStatus = (voter) => {
  if (voter.suspension_status === 'proposed') {
    return { label: 'Pending Suspension', cls: 'bg-amber-100 text-amber-800 border-amber-200' }
  }
  if (voter.has_voted) {
    return { label: 'Voted', cls: 'bg-green-100 text-green-800 border-green-200' }
  }
  const map = {
    active:   { label: 'Active',   cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    invited:  { label: 'Invited',  cls: 'bg-blue-50 text-blue-700 border-blue-200' },
    inactive: { label: 'Inactive', cls: 'bg-slate-100 text-slate-600 border-slate-200' },
    removed:  { label: 'Removed',  cls: 'bg-red-50 text-red-700 border-red-200' },
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

        <!-- Filter bar -->
        <div class="bg-white rounded-xl border border-slate-200 px-5 py-4 mb-6 flex items-center gap-4 flex-wrap">
          <label class="text-sm font-medium text-slate-600">Filter by status:</label>
          <select
            v-model="currentStatus"
            @change="applyFilters"
            class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400"
          >
            <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
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
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr
                v-for="voter in voters.data"
                :key="voter.id"
                class="hover:bg-slate-50 transition-colors"
              >
                <td class="px-6 py-3.5 font-medium text-slate-900 flex items-center gap-2">
                  {{ voter.name }}
                  <svg
                    v-if="voter.has_voted"
                    class="w-4 h-4 text-green-500 flex-shrink-0"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    title="Has voted"
                  >
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </td>
                <td class="px-6 py-3.5">
                  <span
                    :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border', displayStatus(voter).cls]"
                  >
                    {{ displayStatus(voter).label }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>

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
