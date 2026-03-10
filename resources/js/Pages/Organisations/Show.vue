<template>
  <ElectionLayout>
    <!-- Skip to Main Content Link (Accessibility) -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >
      {{ $t('pages.organisation-show.accessibility.skip_to_main') }}
    </a>

    <!-- Accessibility: Screen reader announcement for page load -->
    <div role="status" aria-live="polite" class="sr-only">
      {{ $t('pages.organisation-show.accessibility.page_loaded', { organisation: organisation.name }) }}
    </div>

    <!-- Main Content -->
    <main
      id="main-content"
      role="main"
      :aria-label="$t('pages.organisation-show.accessibility.organization_dashboard', { organisation: organisation.name })"
    >
      <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

          <!-- 1. organisation Header -->
          <section>
            <OrganizationHeader :organisation="organisation" />
          </section>

          <!-- 2. Stats Grid Section -->
          <section>
            <StatsGrid :stats="stats" />
          </section>

          <!-- 3. Quick Actions Section -->
          <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
            <ActionButtons
              :organisation="organisation"
              @appoint-officer="openOfficerModal"
              @create-election="openElectionWizard"
            />
          </section>

          <!-- 4. Demo Results Section (Distinct Card) -->
          <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
            <DemoResultsSection />
          </section>

          <!-- 5. Demo Setup Section - only shown when no demo election exists yet -->
          <section v-if="canManage && !demoStatus?.exists">
            <DemoSetupButton
              :organisation="organisation"
              :demo-status="demoStatus"
            />
          </section>

          <!-- 6. Support Section -->
          <section class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <SupportSection />
          </section>

        </div>
      </div>
    </main>

  </ElectionLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'

import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import OrganizationHeader from './Partials/OrganizationHeader.vue'
import StatsGrid from './Partials/StatsGrid.vue'
import ActionButtons from './Partials/ActionButtons.vue'
import DemoResultsSection from './Partials/DemoResultsSection.vue'
import SupportSection from './Partials/SupportSection.vue'
import DemoSetupButton from './Partials/DemoSetupButton.vue'

const { t } = useI18n()

const props = defineProps({
  organisation: {
    type: Object,
    required: true
  },
  stats: {
    type: Object,
    default: () => ({
      members_count: 0,
      active_members_count: 0,
      elections_count: 0,
      active_elections_count: 0,
      completed_elections: 0,
      new_members_30d: 0,
      exited_members_30d: 0
    })
  },
  demoStatus: Object,
  canManage: Boolean
})

/**
 * Event Handlers for Action Buttons
 * Note: Member import now uses dedicated page (/organisations/{slug}/members/import)
 * Officer and Election handlers will be implemented when modals are added
 */
const openOfficerModal = () => {
  // TODO: Implement officer modal in Phase 2
}

const openElectionWizard = () => {
  // TODO: Implement election wizard in Phase 2
}

/**
 * SEO Meta Tags Management
 *
 * Dynamically sets page-level meta tags based on organisation data.
 * Updates title and description to include organisation name, member count, and election count.
 *
 * Translation keys: 'pages.organisation-show.page_title', 'pages.organisation-show.page_description'
 */
useMeta({
  pageKey: 'organisations.show',
  params: {
    organisation: props.organisation?.name || 'organisation',
    memberCount: props.stats?.members_count || '0',
    electionCount: props.stats?.elections_count || '0'
  }
})
</script>
