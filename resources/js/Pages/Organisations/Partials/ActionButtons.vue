<template>
  <section
    aria-labelledby="actions-heading"
    class="mb-8"
  >
    <!-- Section Header -->
    <div class="mb-6 flex items-start gap-3">
      <div class="flex-shrink-0 mt-0.5 p-2 bg-amber-50 rounded-lg">
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
      </div>
      <div>
        <h2
          id="actions-heading"
          class="text-xl font-semibold text-gray-900 mb-1"
        >
          {{ $t('pages.organisation-show.actions.title') }}
        </h2>
        <p class="text-sm text-gray-500">
          {{ $t('pages.organisation-show.quick_actions.subtitle') }}
        </p>
      </div>
    </div>

    <!-- 4-Column Grid of Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

      <!-- Settings: Organization Configuration (Foundation) -->
      <Link
        v-if="canManage"
        :href="settingsLink"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-left border-2 border-gray-200 hover:border-rose-400 focus-within:ring-2 focus-within:ring-rose-500 focus-within:ring-offset-2 focus-within:rounded-xl overflow-hidden"
        :aria-label="$t('common.settings', 'Settings')"
      >
        <!-- Hover gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-rose-50/0 to-rose-100/0 group-hover:from-rose-50/60 group-hover:to-rose-100/30 transition-all duration-300 pointer-events-none rounded-xl" />

        <div class="relative p-6 flex flex-col flex-1">
          <!-- Badge + Icon row -->
          <div class="flex items-start justify-between mb-5">
            <div class="inline-flex p-3 bg-gradient-to-br from-rose-100 to-rose-50 rounded-xl group-hover:from-rose-200 group-hover:to-rose-100 transition-all">
              <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-full">
              {{ $t('common.foundation', 'Foundation') }}
            </span>
          </div>

          <!-- Content -->
          <h3 class="text-base font-bold text-gray-900 mb-2">
            {{ $t('common.settings', 'Organization Settings') }}
          </h3>
          <p class="text-sm text-gray-500 leading-relaxed flex-1">
            Configure voter eligibility, membership modes, and core election settings
          </p>
        </div>

        <!-- Full-width action button -->
        <div class="relative px-6 pb-5">
          <span class="flex items-center justify-center w-full gap-2 bg-rose-600 group-hover:bg-rose-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            Configure
          </span>
        </div>
      </Link>

      <!-- Step 1: Import Members -->
      <Link
        :href="importMembersLink"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 border-2 border-gray-200 hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 focus-within:rounded-xl overflow-hidden"
        :aria-label="$t('pages.organisation-show.actions.import_members')"
      >
        <!-- Hover gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-100/0 group-hover:from-blue-50/60 group-hover:to-blue-100/30 transition-all duration-300 pointer-events-none rounded-xl" />

        <div class="relative p-6 flex flex-col flex-1">
          <!-- Step badge + Icon row -->
          <div class="flex items-start justify-between mb-5">
            <div class="inline-flex p-3 bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl group-hover:from-blue-200 group-hover:to-blue-100 transition-all">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full">
              {{ $t('pages.organisation-show.quick_actions.step_1') }}
            </span>
          </div>

          <!-- Content -->
          <h3 class="text-base font-bold text-gray-900 mb-2">
            {{ $t('pages.organisation-show.actions.import_members') }}
          </h3>
          <p class="text-sm text-gray-500 leading-relaxed flex-1">
            {{ $t('pages.organisation-show.actions.import_members_desc') }}
          </p>
        </div>

        <!-- Full-width action button -->
        <div class="relative px-6 pb-5">
          <span class="flex items-center justify-center w-full gap-2 bg-blue-600 group-hover:bg-blue-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            {{ $t('pages.organisation-show.actions.button_import') }}
          </span>
        </div>
      </Link>

      <!-- Step 2: Appoint Election Officer -->
      <button
        type="button"
        @click="$emit('appoint-officer')"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-left border-2 border-gray-200 hover:border-amber-400 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:rounded-xl overflow-hidden"
        :aria-label="$t('pages.organisation-show.actions.appoint_officer')"
      >
        <!-- Hover gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/0 to-amber-100/0 group-hover:from-amber-50/60 group-hover:to-amber-100/30 transition-all duration-300 pointer-events-none rounded-xl" />

        <div class="relative p-6 flex flex-col flex-1">
          <!-- Step badge + Icon row -->
          <div class="flex items-start justify-between mb-5">
            <div class="inline-flex p-3 bg-gradient-to-br from-amber-100 to-amber-50 rounded-xl group-hover:from-amber-200 group-hover:to-amber-100 transition-all">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full">
              {{ $t('pages.organisation-show.quick_actions.step_2') }}
            </span>
          </div>

          <!-- Content -->
          <h3 class="text-base font-bold text-gray-900 mb-2">
            {{ $t('pages.organisation-show.actions.appoint_officer') }}
          </h3>
          <p class="text-sm text-gray-500 leading-relaxed flex-1">
            {{ $t('pages.organisation-show.actions.appoint_officer_desc') }}
          </p>

          <!-- Legal note -->
          <div class="mt-4 flex items-center gap-1.5 text-xs text-amber-700 bg-amber-50 rounded-lg px-3 py-2">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $t('pages.organisation-show.quick_actions.legal_note') }}</span>
          </div>
        </div>

        <!-- Full-width action button -->
        <div class="relative px-6 pb-5">
          <span class="flex items-center justify-center w-full gap-2 bg-amber-500 group-hover:bg-amber-600 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            {{ $t('pages.organisation-show.actions.button_appoint') }}
          </span>
        </div>
      </button>

      <!-- Step 3: Create Election (owner/admin only) -->
      <Link
        v-if="canCreateElection"
        :href="createElectionLink"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-left border-2 border-gray-200 hover:border-green-400 focus-within:ring-2 focus-within:ring-green-500 focus-within:ring-offset-2 focus-within:rounded-xl overflow-hidden"
        :aria-label="$t('pages.organisation-show.actions.create_election')"
      >
        <!-- Hover gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-50/0 to-green-100/0 group-hover:from-green-50/60 group-hover:to-green-100/30 transition-all duration-300 pointer-events-none rounded-xl" />

        <div class="relative p-6 flex flex-col flex-1">
          <!-- Step badge + Icon row -->
          <div class="flex items-start justify-between mb-5">
            <div class="inline-flex p-3 bg-gradient-to-br from-green-100 to-green-50 rounded-xl group-hover:from-green-200 group-hover:to-green-100 transition-all">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 border border-green-200 px-2.5 py-1 rounded-full">
              {{ $t('pages.organisation-show.quick_actions.step_3') }}
            </span>
          </div>

          <!-- Content -->
          <h3 class="text-base font-bold text-gray-900 mb-2">
            {{ $t('pages.organisation-show.actions.create_election') }}
          </h3>
          <p class="text-sm text-gray-500 leading-relaxed flex-1">
            {{ $t('pages.organisation-show.actions.create_election_desc') }}
          </p>
        </div>

        <!-- Full-width action button -->
        <div class="relative px-6 pb-5">
          <span class="flex items-center justify-center w-full gap-2 bg-green-600 group-hover:bg-green-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $t('pages.organisation-show.actions.button_create') }}
          </span>
        </div>
      </Link>

      <!-- Step 4: Invite Members (owner/admin only) -->
      <Link
        v-if="canManage"
        :href="inviteMembersLink"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-left border-2 border-gray-200 hover:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:rounded-xl overflow-hidden"
        :aria-label="$t('pages.organisation-show.actions.invite_members')"
      >
        <!-- Hover gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/0 to-indigo-100/0 group-hover:from-indigo-50/60 group-hover:to-indigo-100/30 transition-all duration-300 pointer-events-none rounded-xl" />

        <div class="relative p-6 flex flex-col flex-1">
          <!-- Step badge + Icon row -->
          <div class="flex items-start justify-between mb-5">
            <div class="inline-flex p-3 bg-gradient-to-br from-indigo-100 to-indigo-50 rounded-xl group-hover:from-indigo-200 group-hover:to-indigo-100 transition-all">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-full">
              {{ $t('pages.organisation-show.quick_actions.step_4') }}
            </span>
          </div>

          <!-- Content -->
          <h3 class="text-base font-bold text-gray-900 mb-2">
            {{ $t('pages.organisation-show.actions.invite_members') }}
          </h3>
          <p class="text-sm text-gray-500 leading-relaxed flex-1">
            {{ $t('pages.organisation-show.actions.invite_members_desc') }}
          </p>
        </div>

        <!-- Full-width action button -->
        <div class="relative px-6 pb-5">
          <span class="flex items-center justify-center w-full gap-2 bg-indigo-600 group-hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            {{ $t('pages.organisation-show.actions.button_invite') }}
          </span>
        </div>
      </Link>
      <!-- Step 5: Manage Membership -->
      <Link
        v-if="canManage"
        :href="manageMembershipLink"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-left border-2 border-gray-200 hover:border-purple-400 focus-within:ring-2 focus-within:ring-purple-500 focus-within:ring-offset-2 focus-within:rounded-xl overflow-hidden"
        aria-label="Manage Membership"
      >
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50/0 to-purple-100/0 group-hover:from-purple-50/60 group-hover:to-purple-100/30 transition-all duration-300 pointer-events-none rounded-xl" />
        <div class="relative p-6 flex flex-col flex-1">
          <div class="flex items-start justify-between mb-5">
            <div class="inline-flex p-3 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl group-hover:from-purple-200 group-hover:to-purple-100 transition-all">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <span class="text-xs font-semibold text-purple-600 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-full">
              Step 5
            </span>
          </div>
          <h3 class="text-base font-bold text-gray-900 mb-2">Manage Membership</h3>
          <p class="text-sm text-gray-500 leading-relaxed flex-1">Review applications, track fees, and manage member renewals</p>
        </div>
        <div class="relative px-6 pb-5">
          <span class="flex items-center justify-center w-full gap-2 bg-purple-600 group-hover:bg-purple-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Manage Membership
          </span>
        </div>
      </Link>
    </div>

    <!-- Progress indicator strip -->
    <div class="mt-5 flex items-center gap-3 px-1">
      <span class="text-xs text-gray-400 font-medium">{{ $t('pages.organisation-show.quick_actions.progress_label') }}</span>
      <div class="flex items-center gap-1.5">
        <div class="w-6 h-1.5 bg-blue-400 rounded-full" />
        <div class="w-6 h-1.5 bg-amber-400 rounded-full" />
        <div class="w-6 h-1.5 bg-green-400 rounded-full" />
        <div class="w-6 h-1.5 bg-indigo-400 rounded-full" />
      </div>
      <span class="text-xs text-gray-400">1 → 2 → 3 → 4</span>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  organisation: {
    type: Object,
    required: false
  },
  canManage: Boolean,
  canCreateElection: Boolean,
})

defineEmits(['appoint-officer'])

/**
 * Compute the link to the settings page
 */
const settingsLink = computed(() => {
  if (props.organisation?.slug) {
    return `/organisations/${props.organisation.slug}/settings`
  }
  return '#'
})

/**
 * Compute the link to the member import page
 */
const importMembersLink = computed(() => {
  if (props.organisation?.slug) {
    return `/organisations/${props.organisation.slug}/members/import`
  }
  return '#'
})

/**
 * Compute the link to the create election page
 */
const createElectionLink = computed(() => {
  if (props.organisation?.slug) {
    return `/organisations/${props.organisation.slug}/elections/create`
  }
  return '#'
})

/**
 * Compute the link to the invite members page
 */
const inviteMembersLink = computed(() => {
  if (props.organisation?.slug) {
    return `/organisations/${props.organisation.slug}/members/invite`
  }
  return '#'
})

/**
 * Compute the link to the membership dashboard
 */
const manageMembershipLink = computed(() => {
  if (props.organisation?.slug) {
    return `/organisations/${props.organisation.slug}/membership`
  }
  return '#'
})
</script>
