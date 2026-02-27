<template>
  <div class="voter-dashboard min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ $t('pages.vote-dashboard.voterDashboard.title') }}
        </h1>
        <p class="mt-2 text-gray-600">
          {{ $t('pages.vote-dashboard.voterDashboard.subtitle') }}
        </p>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.vote-dashboard.voterDashboard.stats.pendingVotes') }}
          </div>
          <div class="text-3xl font-bold text-orange-600 mt-2">{{ quickStats?.pending || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.vote-dashboard.voterDashboard.stats.castVotes') }}
          </div>
          <div class="text-3xl font-bold text-green-600 mt-2">{{ quickStats?.cast || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.vote-dashboard.voterDashboard.stats.completedElections') }}
          </div>
          <div class="text-3xl font-bold text-blue-600 mt-2">{{ quickStats?.completed ? 1 : 0 }}</div>
        </div>
      </div>

      <!-- Active Elections Section -->
      <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4 border-b">
          <h2 class="text-xl font-bold text-gray-900">
            {{ $t('pages.vote-dashboard.voterDashboard.elections.title') }}
          </h2>
        </div>

        <div v-if="activeElections.length > 0" class="p-6">
          <div class="space-y-4">
            <div
              v-for="election in activeElections"
              :key="election.id"
              class="border rounded-lg p-6 hover:shadow-lg transition-shadow"
            >
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-lg font-bold text-gray-900">{{ election.title || 'Unnamed Election' }}</h3>
                  <p class="text-gray-600 text-sm">
                    {{ $t(`pages.vote-dashboard.voterDashboard.elections.status.${election.status}`) }}
                  </p>
                </div>
                <span
                  :class="
                    election.can_vote
                      ? 'px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium'
                      : 'px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium'
                  "
                >
                  {{
                    election.can_vote
                      ? $t('pages.vote-dashboard.voterDashboard.elections.status.approved')
                      : $t('pages.vote-dashboard.voterDashboard.elections.status.voted')
                  }}
                </span>
              </div>

              <div class="flex gap-2">
                <button
                  v-if="election.can_vote"
                  class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  {{ $t('pages.vote-dashboard.voterDashboard.buttons.vote') }}
                </button>
                <button
                  v-else
                  class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors"
                >
                  {{ $t('pages.vote-dashboard.voterDashboard.buttons.verifyVote') }}
                </button>
                <button class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                  {{ $t('pages.vote-dashboard.voterDashboard.buttons.viewResults') }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="p-6 text-center">
          <p class="text-gray-600 mb-4">
            {{ $t('pages.vote-dashboard.voterDashboard.elections.empty') }}
          </p>
        </div>
      </div>

      <!-- Voting History Section -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b">
          <h2 class="text-xl font-bold text-gray-900">
            {{ $t('pages.vote-dashboard.voterDashboard.votingHistory.title') }}
          </h2>
        </div>

        <div v-if="votingHistory.length > 0" class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">
                  {{ $t('pages.vote-dashboard.voterDashboard.votingHistory.date') }}
                </th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">
                  {{ $t('pages.vote-dashboard.voterDashboard.votingHistory.election') }}
                </th>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">
                  {{ $t('pages.vote-dashboard.voterDashboard.votingHistory.type') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="vote in votingHistory" :key="vote.id" class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">{{ vote.date }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ vote.election }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ vote.type }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="p-6 text-center">
          <p class="text-gray-600 mb-4">
            {{ $t('pages.vote-dashboard.voterDashboard.votingHistory.empty') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { useI18n } from 'vue-i18n'

const { t: $t } = useI18n()

defineProps({
  activeElections: Array,
  votingHistory: Array,
  quickStats: Object,
})
</script>

<style scoped>
/* Component styles */
</style>
