<template>
  <ElectionLayout>
    <!-- Skip to Main Content Link (Accessibility) -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >
      {{ $t('pages.organization-show.accessibility.skip_to_main') }}
    </a>

    <!-- Accessibility: Screen reader announcement for page load -->
    <div role="status" aria-live="polite" class="sr-only">
      {{ $t('pages.organization-show.accessibility.page_loaded', { organization: organization.name }) }}
    </div>

    <!-- Main Content -->
    <main
      id="main-content"
      role="main"
      :aria-label="$t('pages.organization-show.accessibility.organization_dashboard', { organization: organization.name })"
    >
      <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

          <!-- 1. Organization Header -->
          <section>
            <OrganizationHeader :organization="organization" />
          </section>

          <!-- 2. Stats Grid Section -->
          <section>
            <StatsGrid :stats="stats" />
          </section>

          <!-- 3. Quick Actions Section -->
          <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
            <ActionButtons
              :organization="organization"
              @appoint-officer="openOfficerModal"
              @create-election="openElectionWizard"
            />
          </section>

          <!-- 4. Demo Results Section (Distinct Card) -->
          <section class="bg-white rounded-xl shadow-sm p-8 border border-gray-200">
            <DemoResultsSection />
          </section>

          <!-- 5. Demo Setup Section (conditional - Distinct Card) -->
          <section v-if="canManage">
            <DemoSetupButton
              :organization="organization"
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
  organization: {
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
 * Note: Member import now uses dedicated page (/organizations/{slug}/members/import)
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
 * Dynamically sets page-level meta tags based on organization data.
 * Updates title and description to include organization name, member count, and election count.
 *
 * Translation keys: 'pages.organization-show.page_title', 'pages.organization-show.page_description'
 */
useMeta({
  pageKey: 'organizations.show',
  params: {
    organization: props.organization?.name || 'Organization',
    memberCount: props.stats?.members_count || '0',
    electionCount: props.stats?.elections_count || '0'
  }
})
</script>
