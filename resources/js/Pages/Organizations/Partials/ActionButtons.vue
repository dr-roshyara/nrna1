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
        class="group relative bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-6 text-left border border-gray-200 hover:border-blue-300"
        :aria-label="$t('pages.organization-show.actions.import_members')"
      >
        <!-- Icon -->
        <div class="mb-4">
          <div class="inline-flex p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
          {{ $t('pages.organization-show.actions.import_members') }}
        </h3>
        <p class="text-sm text-gray-600 mb-4">
          {{ $t('pages.organization-show.actions.import_members_desc') }}
        </p>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-medium text-blue-600 group-hover:text-blue-700">
          {{ $t('pages.organization-show.actions.button_import') }}
          <svg class="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-blue-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity -z-10" aria-hidden="true" />
      </Link>

      <!-- Appoint Election Officer Card -->
      <button
        type="button"
        @click="$emit('appoint-officer')"
        class="group relative bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-6 text-left border border-gray-200 hover:border-purple-300"
        :aria-label="$t('pages.organization-show.actions.appoint_officer')"
      >
        <!-- Icon -->
        <div class="mb-4">
          <div class="inline-flex p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
          {{ $t('pages.organization-show.actions.appoint_officer') }}
        </h3>
        <p class="text-sm text-gray-600 mb-4">
          {{ $t('pages.organization-show.actions.appoint_officer_desc') }}
        </p>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-medium text-purple-600 group-hover:text-purple-700">
          {{ $t('pages.organization-show.actions.button_appoint') }}
          <svg class="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-purple-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity -z-10" aria-hidden="true" />
      </button>

      <!-- Create Election Card -->
      <button
        type="button"
        @click="$emit('create-election')"
        class="group relative bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-6 text-left border border-gray-200 hover:border-green-300"
        :aria-label="$t('pages.organization-show.actions.create_election')"
      >
        <!-- Icon -->
        <div class="mb-4">
          <div class="inline-flex p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0A5 5 0 017 12z" />
            </svg>
          </div>
        </div>

        <!-- Content -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
          {{ $t('pages.organization-show.actions.create_election') }}
        </h3>
        <p class="text-sm text-gray-600 mb-4">
          {{ $t('pages.organization-show.actions.create_election_desc') }}
        </p>

        <!-- CTA Button -->
        <span class="inline-flex items-center text-sm font-medium text-green-600 group-hover:text-green-700">
          {{ $t('pages.organization-show.actions.button_create') }}
          <svg class="ml-2 w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </span>

        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-green-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity -z-10" aria-hidden="true" />
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
