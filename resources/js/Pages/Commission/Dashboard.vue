<template>
  <div class="commission-dashboard min-h-screen bg-neutral-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-neutral-900">
          {{ $t('pages.commission.commissionDashboard.title') }}
        </h1>
        <p class="mt-2 text-neutral-600">
          {{ $t('pages.commission.commissionDashboard.subtitle') }}
        </p>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <Card>
          <div class="text-neutral-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.activeElections') }}
          </div>
          <div class="text-3xl font-bold text-primary-600 mt-2">{{ quickStats?.activeElections || 0 }}</div>
        </Card>
        <Card>
          <div class="text-neutral-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.votesCast') }}
          </div>
          <div class="text-3xl font-bold text-success-600 mt-2">{{ quickStats?.votesCast || 0 }}</div>
        </Card>
        <Card>
          <div class="text-neutral-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.pendingVoters') }}
          </div>
          <div class="text-3xl font-bold text-warning-600 mt-2">{{ quickStats?.pendingVoters || 0 }}</div>
        </Card>
        <Card>
          <div class="text-neutral-600 text-sm font-medium">
            {{ $t('pages.commission.commissionDashboard.stats.issuesToReview') }}
          </div>
          <div class="text-3xl font-bold text-danger-600 mt-2">{{ quickStats?.issues || 0 }}</div>
        </Card>
      </div>

      <!-- Elections Section -->
      <Card padding="none">
        <div class="px-6 py-4 border-b border-neutral-200">
          <h2 class="text-xl font-bold text-neutral-900">
            {{ $t('pages.commission.commissionDashboard.elections.title') }}
          </h2>
        </div>

        <div v-if="elections.length > 0" class="p-6">
          <div class="space-y-4">
            <div
              v-for="election in elections"
              :key="election.id"
              class="border border-neutral-200 rounded-lg p-6 hover:shadow-lg transition-shadow"
            >
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-lg font-bold text-neutral-900">{{ election.title || 'Unnamed Election' }}</h3>
                  <p class="text-neutral-600 text-sm">
                    {{ $t(`pages.commission.commissionDashboard.status.${election.status}`) }}
                  </p>
                </div>
                <span
                  :class="[
                    'px-3 py-1 rounded-full text-sm font-medium',
                    election.status === 'active'
                      ? 'bg-success-100 text-success-800'
                      : 'bg-neutral-100 text-neutral-800'
                  ]"
                >
                  {{ $t(`pages.commission.commissionDashboard.status.${election.status}`) }}
                </span>
              </div>

              <div class="flex gap-2">
                <Button class="flex-1" variant="primary" size="md">
                  {{ $t('pages.commission.commissionDashboard.buttons.monitor') }}
                </Button>
                <Button class="flex-1" variant="secondary" size="md">
                  {{ $t('pages.commission.commissionDashboard.buttons.manage') }}
                </Button>
                <Button class="flex-1" variant="outline" size="md">
                  {{ $t('pages.commission.commissionDashboard.buttons.auditLog') }}
                </Button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="p-6 text-center">
          <p class="text-neutral-600 mb-4">
            {{ $t('pages.commission.commissionDashboard.elections.empty') }}
          </p>
        </div>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'
import Button from '@/Components/Button.vue'
import Card from '@/Components/Card.vue'

const { t: $t } = useI18n()

defineProps({
  currentRole: String,
  elections: Array,
  quickStats: Object,
})

useMeta({ noindex: true, nofollow: true })
</script>

<style scoped>
/* Component styles */
</style>
