<template>
  <section
    aria-labelledby="actions-heading"
    class="mb-8"
  >
    <div class="mb-6">
      <h2
        id="actions-heading"
        class="text-xl font-semibold text-gray-900 mb-2"
      >
        {{ $t('pages.organization-show.actions.title') }}
      </h2>
      <p class="text-gray-600">
        {{ $t('pages.organization-show.actions.description') }}
      </p>
    </div>

    <!-- 3-Column Grid of Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Import Members Card (Link to dedicated page) -->
      <Link
        :href="importMembersLink"
        class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 text-left border-2 border-gray-200 hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 focus-within:rounded-xl"
        :aria-label="$t('pages.organization-show.actions.import_members')"
      >
        <!-- Icon -->
        <div class="mb-6">
          <div class="inline-flex p-4 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl group-hover:from-blue-200 group-hover:to-blue-100 transition-all">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          {{ $t('pages.organization-show.actions.import_members') }}
        </h3>
        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
          {{ $t('pages.organization-show.actions.import_members_desc') }}
        </p>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-semibold text-blue-600 group-hover:text-blue-700 group-hover:translate-x-1 transition-transform">
          {{ $t('pages.organization-show.actions.button_import') }}
          <svg class="ml-2 w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

      </Link>

      <!-- Appoint Election Officer Card -->
      <button
        type="button"
        @click="$emit('appoint-officer')"
        class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 text-left border-2 border-gray-200 hover:border-purple-400 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:rounded-xl"
        :aria-label="$t('pages.organization-show.actions.appoint_officer')"
      >
        <!-- Icon -->
        <div class="mb-6">
          <div class="inline-flex p-4 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl group-hover:from-purple-200 group-hover:to-purple-100 transition-all">
            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          {{ $t('pages.organization-show.actions.appoint_officer') }}
        </h3>
        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
          {{ $t('pages.organization-show.actions.appoint_officer_desc') }}
        </p>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-semibold text-purple-600 group-hover:text-purple-700 group-hover:translate-x-1 transition-transform">
          {{ $t('pages.organization-show.actions.button_appoint') }}
          <svg class="ml-2 w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

      </button>

      <!-- Create Election Card -->
      <button
        type="button"
        @click="$emit('create-election')"
        class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 text-left border-2 border-gray-200 hover:border-green-400 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:rounded-xl"
        :aria-label="$t('pages.organization-show.actions.create_election')"
      >
        <!-- Icon -->
        <div class="mb-6">
          <div class="inline-flex p-4 bg-gradient-to-br from-green-100 to-green-50 rounded-xl group-hover:from-green-200 group-hover:to-green-100 transition-all">
            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-bold text-gray-900 mb-3">
          {{ $t('pages.organization-show.actions.create_election') }}
        </h3>
        <p class="text-sm text-gray-600 mb-6 leading-relaxed">
          {{ $t('pages.organization-show.actions.create_election_desc') }}
        </p>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-semibold text-green-600 group-hover:text-green-700 group-hover:translate-x-1 transition-transform">
          {{ $t('pages.organization-show.actions.button_create') }}
          <svg class="ml-2 w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

      </button>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  organization: {
    type: Object,
    required: false
  }
})

defineEmits(['appoint-officer', 'create-election'])

/**
 * Compute the link to the member import page
 */
const importMembersLink = computed(() => {
  if (props.organization?.slug) {
    return `/organizations/${props.organization.slug}/members/import`
  }
  return '#'
})
</script>
