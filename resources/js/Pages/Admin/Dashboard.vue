<template>
  <div class="admin-dashboard min-h-screen bg-slate-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ $t('pages.admin.adminDashboard.title') }}
        </h1>
        <p class="mt-2 text-gray-600">
          {{ $t('pages.admin.adminDashboard.subtitle') }}
        </p>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.admin.adminDashboard.stats.totalElections') }}
          </div>
          <div class="text-3xl font-bold text-blue-600 mt-2">{{ quickStats?.totalElections || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.admin.adminDashboard.stats.activeElections') }}
          </div>
          <div class="text-3xl font-bold text-green-600 mt-2">{{ quickStats?.activeElections || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.admin.adminDashboard.stats.totalVoters') }}
          </div>
          <div class="text-3xl font-bold text-purple-600 mt-2">{{ quickStats?.totalVoters || 0 }}</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="text-gray-600 text-sm font-medium">
            {{ $t('pages.admin.adminDashboard.stats.participationRate') }}
          </div>
          <div class="text-3xl font-bold text-orange-600 mt-2">{{ quickStats?.participationRate || 0 }}%</div>
        </div>
      </div>

      <!-- Organizations Section -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b">
          <h2 class="text-xl font-bold text-gray-900">
            {{ $t('pages.admin.adminDashboard.organisations.title') }}
          </h2>
        </div>

        <div v-if="organisations.length > 0" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="org in organisations"
              :key="org.id"
              class="border rounded-lg p-6 hover:shadow-lg transition-shadow"
            >
              <h3 class="text-lg font-bold text-gray-900 mb-2">{{ org.name }}</h3>
              <p class="text-gray-600 text-sm mb-4">{{ org.type }}</p>
              <button
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
              >
                {{ $t('pages.admin.adminDashboard.organisations.manageButton') }}
              </button>
            </div>
          </div>
        </div>

        <div v-else class="p-6 text-center">
          <p class="text-gray-600 mb-4">{{ $t('pages.admin.adminDashboard.organisations.empty') }}</p>
          <button
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
          >
            {{ $t('pages.admin.adminDashboard.organisations.createButton') }}
          </button>
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
  organisations: Array,
  quickStats: Object,
})

/**
 * SEO: Prevent Admin Dashboard from being indexed
 * This is a private page and should not appear in search results
 */
useMeta({ noindex: true, nofollow: true })
</script>

<style scoped>
/* Component styles */
</style>
