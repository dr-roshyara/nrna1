<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
    <!-- Election Header -->
    <election-header />

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-1">
      <!-- Screen Reader Announcements -->
      <div role="status" aria-live="polite" aria-atomic="true" class="sr-only">
        {{ pageLoadedAnnouncement }}
      </div>

      <!-- Breadcrumb Navigation -->
      <nav aria-label="Breadcrumb" class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
          <li>
            <Link href="/dashboard" class="hover:text-gray-700 dark:hover:text-gray-200">
              {{ $t('navigation.dashboard') }}
            </Link>
          </li>
          <li class="text-gray-300 dark:text-gray-600">/</li>
          <li>
            <Link :href="`/organisations/${organisation.slug}`" class="hover:text-gray-700 dark:hover:text-gray-200">
              {{ organisation.name }}
            </Link>
          </li>
          <li class="text-gray-300 dark:text-gray-600">/</li>
          <li class="text-gray-700 dark:text-gray-300" aria-current="page">
            {{ $t('pages.organisation-voters.title') }}
          </li>
        </ol>
      </nav>

      <!-- Header Section -->
      <header class="mb-8 sm:mb-12">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">
          {{ $t('pages.organisation-voters.title') }}
        </h1>

        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
          {{ $t('pages.organisation-voters.description', { organisation: organisation.name }) }}
        </p>

        <!-- Commission Member Notice -->
        <div v-if="isCommissionMember" class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
          <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
              <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
            </svg>
            <div class="text-sm text-blue-900 dark:text-blue-200">
              <p class="font-medium mb-1">{{ $t('pages.organisation-voters.committee_access.title') }}</p>
              <p class="text-xs opacity-90">{{ $t('pages.organisation-voters.committee_access.description') }}</p>
            </div>
          </div>
        </div>
      </header>

      <!-- Flash Messages -->
      <div v-if="$page.props.flash?.success" class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
        <div class="flex gap-3">
          <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <div class="text-sm text-green-900 dark:text-green-200">
            {{ $page.props.flash.success }}
          </div>
        </div>
      </div>

      <!-- Stats Dashboard -->
      <section
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 sm:mb-12"
        aria-label="Voter statistics"
      >
        <stat-card
          icon="users"
          :value="stats.total"
          :label="$t('pages.organisation-voters.stats.total_voters')"
          :aria-label="`Total voters: ${stats.total}`"
        />
        <stat-card
          icon="check-circle"
          :value="stats.approved"
          :label="$t('pages.organisation-voters.stats.approved_voters')"
          :aria-label="`Approved voters: ${stats.approved}`"
        />
        <stat-card
          icon="clock"
          :value="stats.pending"
          :label="$t('pages.organisation-voters.stats.pending_approval')"
          :aria-label="`Pending approval: ${stats.pending}`"
        />
        <stat-card
          icon="vote"
          :value="stats.voted"
          :label="$t('pages.organisation-voters.stats.already_voted')"
          :aria-label="`Already voted: ${stats.voted}`"
        />
      </section>

      <!-- Filters Section -->
      <section class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
          {{ $t('pages.organisation-voters.filters.title') }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Search -->
          <div>
            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ $t('pages.organisation-voters.filters.search') }}
            </label>
            <input
              id="search"
              v-model="searchQuery"
              type="text"
              :placeholder="$t('pages.organisation-voters.filters.search_placeholder')"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              @input="updateFilters"
            />
          </div>

          <!-- Status Filter -->
          <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ $t('pages.organisation-voters.filters.status') }}
            </label>
            <select
              id="status"
              v-model="statusFilter"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
              @change="updateFilters"
            >
              <option value="">{{ $t('pages.organisation-voters.filters.all_statuses') }}</option>
              <option value="approved">{{ $t('pages.organisation-voters.status.approved') }}</option>
              <option value="pending">{{ $t('pages.organisation-voters.status.pending') }}</option>
              <option value="voted">{{ $t('pages.organisation-voters.status.voted') }}</option>
            </select>
          </div>

          <!-- Clear Filters -->
          <div class="flex items-end">
            <button
              @click="clearFilters"
              class="w-full px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg font-medium transition-colors"
              :aria-label="$t('pages.organisation-voters.filters.clear_aria')"
            >
              {{ $t('pages.organisation-voters.filters.clear') }}
            </button>
          </div>
        </div>
      </section>

      <!-- Voters Table -->
      <section
        class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden"
        role="region"
        aria-label="Voters table"
      >
        <!-- Table Controls -->
        <div v-if="isCommissionMember" class="border-b border-gray-200 dark:border-gray-700 p-4">
          <div class="flex flex-wrap gap-2">
            <button
              @click="selectAll"
              :disabled="!voters.data || voters.data.length === 0"
              class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
              :aria-label="$t('pages.organisation-voters.actions.select_all_aria')"
            >
              <input
                type="checkbox"
                :checked="allSelected"
                @change="selectAll"
                class="w-4 h-4"
                :aria-label="$t('pages.organisation-voters.actions.select_all')"
              />
              {{ $t('pages.organisation-voters.actions.select_all') }}
            </button>

            <button
              v-if="selectedCount > 0"
              @click="bulkApprove"
              :disabled="isProcessing"
              class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
              :aria-label="$t('pages.organisation-voters.actions.bulk_approve_aria', { count: selectedCount })"
            >
              <svg v-if="!isProcessing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span v-if="isProcessing" class="sr-only">{{ $t('pages.organisation-voters.actions.processing') }}</span>
              {{ $t('pages.organisation-voters.actions.bulk_approve', { count: selectedCount }) }}
            </button>

            <button
              v-if="selectedCount > 0"
              @click="bulkSuspend"
              :disabled="isProcessing"
              class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
              :aria-label="$t('pages.organisation-voters.actions.bulk_suspend_aria', { count: selectedCount })"
            >
              <svg v-if="!isProcessing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <span v-if="isProcessing" class="sr-only">{{ $t('pages.organisation-voters.actions.processing') }}</span>
              {{ $t('pages.organisation-voters.actions.bulk_suspend', { count: selectedCount }) }}
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full">
            <caption class="sr-only">{{ $t('pages.organisation-voters.table.caption', { organisation: organisation.name }) }}</caption>
            <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
              <tr>
                <th v-if="isCommissionMember" scope="col" class="px-6 py-3 text-left">
                  <span class="sr-only">{{ $t('pages.organisation-voters.table.select') }}</span>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('pages.organisation-voters.table.sn') }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('pages.organisation-voters.table.name') }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('pages.organisation-voters.table.email') }}
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('pages.organisation-voters.table.status') }}
                </th>
                <th v-if="isCommissionMember" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ $t('pages.organisation-voters.table.actions') }}
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="(voter, index) in voters.data" :key="voter.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td v-if="isCommissionMember" class="px-6 py-4 whitespace-nowrap">
                  <input
                    type="checkbox"
                    :checked="selectedVoters.includes(voter.id)"
                    @change="toggleVoter(voter.id)"
                    class="w-4 h-4"
                    :aria-label="$t('pages.organisation-voters.table.select_voter_aria', { name: voter.name })"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                  {{ index + 1 + (voters.current_page - 1) * voters.per_page }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                  {{ voter.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                  {{ voter.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    v-if="voter.approvedBy"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                  >
                    {{ $t('pages.organisation-voters.status.approved') }}
                  </span>
                  <span
                    v-else-if="voter.has_voted"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                  >
                    {{ $t('pages.organisation-voters.status.voted') }}
                  </span>
                  <span
                    v-else
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200"
                  >
                    {{ $t('pages.organisation-voters.status.pending') }}
                  </span>
                </td>
                <td v-if="isCommissionMember" class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <button
                    v-if="!voter.approvedBy"
                    @click="approveVoter(voter)"
                    :disabled="isProcessing"
                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
                    :aria-label="$t('pages.organisation-voters.actions.approve_aria', { name: voter.name })"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="hidden sm:inline">{{ $t('pages.organisation-voters.actions.approve') }}</span>
                  </button>

                  <button
                    v-if="voter.approvedBy && !voter.has_voted"
                    @click="suspendVoter(voter)"
                    :disabled="isProcessing"
                    class="inline-flex items-center gap-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed min-h-[44px]"
                    :aria-label="$t('pages.organisation-voters.actions.suspend_aria', { name: voter.name })"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="hidden sm:inline">{{ $t('pages.organisation-voters.actions.suspend') }}</span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="!voters.data || voters.data.length === 0" class="text-center py-12 px-6">
          <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <p class="text-lg text-gray-500 dark:text-gray-400 mb-2">{{ $t('pages.organisation-voters.empty.title') }}</p>
          <p class="text-sm text-gray-400 dark:text-gray-500">{{ $t('pages.organisation-voters.empty.description') }}</p>
        </div>

        <!-- Pagination -->
        <div v-if="voters.last_page > 1" class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 flex items-center justify-between sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link
              v-if="voters.current_page > 1"
              :href="`${pagePath}?page=${voters.current_page - 1}`"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              {{ $t('pagination.previous') }}
            </Link>
            <span v-else class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-500 bg-white dark:bg-gray-700 cursor-not-allowed">
              {{ $t('pagination.previous') }}
            </span>

            <Link
              v-if="voters.current_page < voters.last_page"
              :href="`${pagePath}?page=${voters.current_page + 1}`"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
            >
              {{ $t('pagination.next') }}
            </Link>
            <span v-else class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-500 bg-white dark:bg-gray-700 cursor-not-allowed">
              {{ $t('pagination.next') }}
            </span>
          </div>

          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                {{ $t('pagination.showing') }}
                <span class="font-medium">{{ (voters.current_page - 1) * voters.per_page + 1 }}</span>
                {{ $t('pagination.to') }}
                <span class="font-medium">{{ Math.min(voters.current_page * voters.per_page, voters.total) }}</span>
                {{ $t('pagination.of') }}
                <span class="font-medium">{{ voters.total }}</span>
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-xs -space-x-px" aria-label="Pagination">
                <Link
                  v-if="voters.current_page > 1"
                  :href="`${pagePath}?page=1`"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600"
                  aria-label="First page"
                >
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L13.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>

                <Link
                  v-if="voters.current_page > 1"
                  :href="`${pagePath}?page=${voters.current_page - 1}`"
                  class="relative inline-flex items-center px-2 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600"
                  aria-label="Previous page"
                >
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>

                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300">
                  {{ $t('pagination.page', { current: voters.current_page, total: voters.last_page }) }}
                </span>

                <Link
                  v-if="voters.current_page < voters.last_page"
                  :href="`${pagePath}?page=${voters.current_page + 1}`"
                  class="relative inline-flex items-center px-2 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600"
                  aria-label="Next page"
                >
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>

                <Link
                  v-if="voters.current_page < voters.last_page"
                  :href="`${pagePath}?page=${voters.last_page}`"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600"
                  aria-label="Last page"
                >
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M11.293 14.707a1 1 0 010-1.414L14.586 10l-3.293-3.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </Link>
              </nav>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Public Digit Footer -->
    <public-digit-footer />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import ElectionHeader from '@/Components/Header/ElectionHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
import StatCard from '@/Components/StatCard.vue'
import { Link } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
  organisation: {
    type: Object,
    required: true
  },
  voters: {
    type: Object,
    required: true
  },
  stats: {
    type: Object,
    required: true
  },
  isCommissionMember: {
    type: Boolean,
    default: false
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const searchQuery = ref(props.filters.search || '')
const statusFilter = ref(props.filters.status || '')
const selectedVoters = ref([])
const isProcessing = ref(false)

const pageLoadedAnnouncement = computed(() => {
  return i18n.t('pages.organisation-voters.accessibility.page_loaded', {
    organisation: props.organisation.name,
    count: props.voters.total
  })
})

const pagePath = computed(() => {
  return `/organisations/${props.organisation.slug}/voters`
})

const selectedCount = computed(() => selectedVoters.value.length)

const allSelected = computed(() => {
  return props.voters.data && selectedVoters.value.length === props.voters.data.length
})

const updateFilters = () => {
  const params = new URLSearchParams()
  if (searchQuery.value) params.append('search', searchQuery.value)
  if (statusFilter.value) params.append('status', statusFilter.value)

  router.get(`${pagePath.value}?${params.toString()}`, {}, {
    replace: true,
    preserveScroll: true
  })
}

const clearFilters = () => {
  searchQuery.value = ''
  statusFilter.value = ''
  router.get(pagePath.value, {}, {
    replace: true,
    preserveScroll: true
  })
}

const selectAll = () => {
  if (allSelected.value) {
    selectedVoters.value = []
  } else {
    selectedVoters.value = props.voters.data.map(v => v.id)
  }
}

const toggleVoter = (voterId) => {
  const index = selectedVoters.value.indexOf(voterId)
  if (index > -1) {
    selectedVoters.value.splice(index, 1)
  } else {
    selectedVoters.value.push(voterId)
  }
}

const approveVoter = async (voter) => {
  if (confirm(i18n.t('pages.organisation-voters.confirm.approve'))) {
    isProcessing.value = true
    router.post(`${pagePath.value}/${voter.id}/approve`, {}, {
      onFinish: () => {
        isProcessing.value = false
        selectedVoters.value = []
      }
    })
  }
}

const suspendVoter = async (voter) => {
  if (confirm(i18n.t('pages.organisation-voters.confirm.suspend'))) {
    isProcessing.value = true
    router.post(`${pagePath.value}/${voter.id}/suspend`, {}, {
      onFinish: () => {
        isProcessing.value = false
        selectedVoters.value = []
      }
    })
  }
}

const bulkApprove = async () => {
  if (confirm(i18n.t('pages.organisation-voters.confirm.bulk_approve', { count: selectedCount.value }))) {
    isProcessing.value = true
    router.post(`${pagePath.value}/bulk-approve`, {
      voter_ids: selectedVoters.value
    }, {
      onFinish: () => {
        isProcessing.value = false
        selectedVoters.value = []
      }
    })
  }
}

const bulkSuspend = async () => {
  if (confirm(i18n.t('pages.organisation-voters.confirm.bulk_suspend', { count: selectedCount.value }))) {
    isProcessing.value = true
    router.post(`${pagePath.value}/bulk-suspend`, {
      voter_ids: selectedVoters.value
    }, {
      onFinish: () => {
        isProcessing.value = false
        selectedVoters.value = []
      }
    })
  }
}
</script>

<style scoped>
/* Accessibility: Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* High contrast mode support */
@media (prefers-contrast: more) {
  button:focus {
    outline: 3px solid currentColor;
    outline-offset: 2px;
  }
}
</style>
