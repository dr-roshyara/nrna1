<template>
  <div class="commission-dashboard min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ $t('pages.commission.commissionDashboard.title') }}
        </h1>
        <p class="mt-2 text-gray-600">
          {{ $t('pages.commission.commissionDashboard.subtitle') }}
        </p>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.activeElections') }}
          </div>
          <div class="text-3xl font-bold text-blue-600 mt-2">{{ quickStats?.activeElections || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.votesCast') }}
          </div>
          <div class="text-3xl font-bold text-green-600 mt-2">{{ quickStats?.votesCast || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.pendingVoters') }}
          </div>
          <div class="text-3xl font-bold text-purple-600 mt-2">{{ quickStats?.pendingVoters || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.issuesToReview') }}
          </div>
          <div class="text-3xl font-bold text-orange-600 mt-2">{{ quickStats?.issues || 0 }}</div>
        </div>
      </div>

      <!-- Elections Section -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b">
          <h2 class="text-xl font-bold text-gray-900">
            {{ $t('pages.commission.commissionDashboard.elections.title') }}
          </h2>
        </div>

        <div v-if="elections.length > 0" class="p-6">
          <div class="space-y-4">
            <div
              v-for="election in elections"
              :key="election.id"
              class="border rounded-lg p-6 hover:shadow-lg transition-shadow"
            >
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-lg font-bold text-gray-900">{{ election.title || 'Unnamed Election' }}</h3>
                  <p class="text-gray-600 text-sm">
                    {{ $t(`pages.commission.commissionDashboard.status.${election.status}`) }}
                  </p>
                </div>
                <span
                  :class="
                    election.status === 'active'
                      ? 'px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium'
                      : 'px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium'
                  "
                >
                  {{ $t(`pages.commission.commissionDashboard.status.${election.status}`) }}
                </span>
              </div>

              <div class="flex gap-2">
                <button class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                  {{ $t('pages.commission.commissionDashboard.buttons.monitor') }}
                </button>
                <button class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                  {{ $t('pages.commission.commissionDashboard.buttons.manage') }}
                </button>
                <button class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                  {{ $t('pages.commission.commissionDashboard.buttons.auditLog') }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="p-6 text-center">
          <p class="text-gray-600 mb-4">
            {{ $t('pages.commission.commissionDashboard.elections.empty') }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'

const { t: $t } = useI18n()

defineProps({
  currentRole: String,
  elections: Array,
  quickStats: Object,
})

/**
 * SEO: Prevent Commission Dashboard from being indexed
 * This is a private page for election commissioners only and should not appear in search results
 */
useMeta({ noindex: true, nofollow: true })
</script>

<style scoped>
/* Component styles */
</style>
