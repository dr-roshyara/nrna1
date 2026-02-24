<template>
  <ElectionLayout>
    <!-- Breadcrumb Schema for SEO -->
    <BreadcrumbSchema />

    <!-- Accessibility: Screen reader announcement for page load -->
    <div role="status" aria-live="polite" class="sr-only">
      {{ $t('pages.organization-show.accessibility.page_loaded', { organization: organization.name }) }}
    </div>

    <!-- Main Content -->
    <main role="main" :aria-label="$t('pages.organization-show.accessibility.organization_dashboard', { organization: organization.name })">
      <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

          <!-- 1. Organization Header -->
          <OrganizationHeader :organization="organization" />

          <!-- 2. Stats Grid -->
          <StatsGrid :stats="stats" />

          <!-- 3. Primary Action Buttons (3 Cards) -->
          <ActionButtons
            :organization="organization"
            @appoint-officer="openOfficerModal"
            @create-election="openElectionWizard"
          />

          <!-- 4. Demo Results Section -->
          <DemoResultsSection />

          <!-- 5. Demo Setup Section (conditional) -->
          <div v-if="canManage" class="mb-8">
            <DemoSetupButton
              :organization="organization"
              :demo-status="demoStatus"
            />
          </div>

          <!-- 6. Support Section -->
          <SupportSection />

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
import BreadcrumbSchema from '@/Components/BreadcrumbSchema.vue'
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
