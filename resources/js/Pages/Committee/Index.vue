<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 to-slate-100">
    <!-- Public Header -->
    <PublicDigitHeader />

    <!-- Content -->
    <div class="flex-1 py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">Committees</h1>
              <p class="mt-2 text-gray-600">Manage committees at all organizational levels</p>
            </div>
            <a
              :href="`/organisations/${organisationSlug}/committees/create`"
              class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create Committee
            </a>
          </div>
        </div>

        <!-- Governance Navigation Tabs -->
        <div class="mb-8 border-b border-gray-200">
          <div class="flex gap-8">
            <a
              href="#"
              class="px-4 py-3 text-base font-semibold text-primary-600 border-b-2 border-primary-600"
            >
              Committees
            </a>
            <a
              :href="`/organisations/${organisationSlug}/governance/levels`"
              class="px-4 py-3 text-base font-semibold text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 transition-colors"
            >
              Governance Levels
            </a>
            <a
              :href="`/organisations/${organisationSlug}/geo/units`"
              class="px-4 py-3 text-base font-semibold text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 transition-colors"
            >
              Geographic Units
            </a>
          </div>
        </div>

        <!-- Committee Levels Grid (Dynamic from Governance Levels) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div
            v-for="level in governanceLevels"
            :key="level.level"
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
          >
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
              <h2 class="text-xl font-bold text-gray-900">{{ level.committee_name }}</h2>
              <p class="text-sm text-gray-600 mt-1">{{ level.geo_name }} governance</p>
            </div>
            <div class="p-6">
              <CommitteeList
                :committees="committees[level.level] || []"
                :level="String(level.level)"
                :organisation-slug="organisationSlug"
              />
            </div>
          </div>
        </div>

        <!-- Back Link -->
        <div class="mt-8 text-center">
          <a
            :href="`/organisations/${organisationSlug}`"
            class="text-primary-600 hover:text-primary-700 font-medium"
          >
            ← Back to Organisation Dashboard
          </a>
        </div>
      </div>
    </div>

    <!-- Public Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script setup>
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue';
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue';
import CommitteeList from '@/Components/Committee/CommitteeList.vue';

const props = defineProps({
  committees: {
    type: Object,
    required: true,
  },
  governanceLevels: {
    type: Array,
    required: true,
  },
  organisationSlug: {
    type: String,
    required: true,
  },
});
</script>

<style scoped>
/* Component styles here */
</style>
