<template>
  <ElectionLayout>
    <!-- Skip to Main Content -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >{{ $t('pages.organisation-show.accessibility.skip_to_main') }}</a>

    <div role="status" aria-live="polite" class="sr-only">
      {{ $t('pages.organisation-show.accessibility.page_loaded', { organisation: organisation.name }) }}
    </div>

    <!-- Flash Message -->
    <div v-if="page.props.flash?.success" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ page.props.flash.success }}
    </div>

    <main id="main-content" role="main" :aria-label="$t('pages.organisation-show.accessibility.organization_dashboard', { organisation: organisation.name })">
      <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

          <!-- ① ORGANISATION HEADER — everyone -->
          <section>
            <OrganizationHeader :organisation="organisation" />
          </section>

          <!-- ② ROLE CONTEXT BANNER — subtle indicator for officers -->
          <div
            v-if="isOfficer"
            class="flex items-center gap-3 px-5 py-3 rounded-xl border"
            :class="{
              'bg-emerald-50 border-emerald-200 text-emerald-800': isChief,
              'bg-blue-50 border-blue-200 text-blue-800': isDeputy,
              'bg-slate-50 border-slate-200 text-slate-700': isCommissioner,
            }"
          >
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span class="text-sm font-medium">
              You are signed in as
              <span class="font-bold capitalize">{{ isChief ? 'Election Chief' : isDeputy ? 'Election Deputy' : 'Election Commissioner' }}</span>
              for this organisation.
            </span>
          </div>

          <!-- ③ STATS GRID — everyone -->
          <section>
            <StatsGrid :stats="stats" />
          </section>

          <!-- ④ QUICK ACTIONS — owner/admin only -->
          <section v-if="canManage" class="bg-white rounded-2xl shadow-sm p-8 border border-slate-200">
            <ActionButtons
              :organisation="organisation"
              :can-manage="canManage"
              :can-create-election="canCreateElection"
              @appoint-officer="openOfficerModal"
            />
          </section>

          <!-- ⑤ DEMO RESULTS — everyone -->
          <section class="bg-white rounded-2xl shadow-sm p-8 border border-slate-200">
            <DemoResultsSection />
          </section>

          <!-- ⑥ DEMO SETUP — owner/admin only, when no demo exists -->
          <section v-if="canManage && !demoStatus?.exists">
            <DemoSetupButton :organisation="organisation" :demo-status="demoStatus" />
          </section>

          <!-- ⑦ ACTIVE ELECTION NOTICE — visible to voters (non-admin, non-officer) -->
          <section v-if="!canManage && !isOfficer && activeElections.length > 0">
            <div class="rounded-2xl overflow-hidden border border-blue-200 shadow-sm">
              <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
                <div class="flex items-center gap-3 text-white">
                  <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-lg font-bold">Active Election{{ activeElections.length !== 1 ? 's' : '' }}</h2>
                    <p class="text-blue-100 text-sm">Voting is currently open</p>
                  </div>
                </div>
              </div>
              <div class="bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                  v-for="election in activeElections"
                  :key="election.id"
                  class="rounded-xl border border-blue-100 bg-blue-50 p-4 flex items-center justify-between gap-3"
                >
                  <div>
                    <p class="text-sm font-semibold text-slate-800">{{ election.name }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }}</p>
                  </div>
                  <StatusBadge :status="election.status" size="sm" />
                </div>
              </div>
            </div>
          </section>

          <!-- ⑧ ELECTIONS — everyone sees, actions gated by role -->
          <section>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <!-- Header -->
              <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Elections</h2>
                    <p v-if="elections.length > 0" class="text-xs text-slate-500">{{ elections.length }} election{{ elections.length !== 1 ? 's' : '' }}</p>
                  </div>
                </div>
                <!-- New Election — owner/admin only -->
                <a
                  v-if="canCreateElection"
                  :href="route('organisations.elections.create', organisation.slug)"
                  class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  New Election
                </a>
              </div>

              <!-- Card Grid -->
              <div v-if="elections.length > 0" class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <ElectionCard
                  v-for="election in elections"
                  :key="election.id"
                  :election="election"
                  :activating-id="activatingId"
                  :can-activate="canActivateElection && election.status === 'planned'"
                  :can-manage="canManage || isChief || isDeputy"
                  :is-readonly="isCommissioner || (!canManage && !isOfficer)"
                  @activate="activateElection"
                />
              </div>

              <!-- Empty State -->
              <div v-else class="p-6">
                <EmptyState
                  title="No elections yet"
                  :description="canCreateElection ? 'Create your first election to get started.' : 'Check back later for upcoming elections.'"
                >
                  <template #icon>
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                  </template>
                  <template v-if="canCreateElection" #action>
                    <a
                      :href="route('organisations.elections.create', organisation.slug)"
                      class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                      Create First Election
                    </a>
                  </template>
                </EmptyState>
              </div>
            </div>
          </section>

          <!-- ⑨ OFFICER MANAGEMENT — owner/admin only -->
          <section v-if="canManage">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Election Officers</h2>
                    <p class="text-xs text-slate-500">Manage who can oversee elections</p>
                  </div>
                </div>
                <a
                  :href="route('organisations.election-officers.index', organisation.slug)"
                  class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors"
                  aria-label="Manage election officers"
                >Manage →</a>
              </div>
              <div class="px-8 py-5">
                <div v-if="officers && officers.length > 0" class="flex flex-wrap gap-2">
                  <div
                    v-for="officer in officers.slice(0, 6)"
                    :key="officer.id"
                    class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-sm"
                  >
                    <div class="w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-xs font-bold text-slate-600">
                      {{ officer.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="font-medium text-slate-700">{{ officer.user_name }}</span>
                    <span
                      class="text-xs font-semibold px-1.5 py-0.5 rounded"
                      :class="{
                        'bg-emerald-100 text-emerald-700': officer.role === 'chief',
                        'bg-blue-100 text-blue-700': officer.role === 'deputy',
                        'bg-slate-100 text-slate-600': officer.role === 'commissioner',
                      }"
                    >{{ officer.role }}</span>
                  </div>
                  <div v-if="officers.length > 6" class="flex items-center px-3 py-1.5 text-sm text-slate-500">
                    +{{ officers.length - 6 }} more
                  </div>
                </div>
                <EmptyState
                  v-else
                  title="No officers appointed"
                  description="Appoint chiefs and deputies to manage your elections."
                >
                  <template #icon>
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                  </template>
                  <template #action>
                    <a
                      :href="route('organisations.election-officers.index', organisation.slug)"
                      class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg transition-colors"
                    >Appoint First Officer</a>
                  </template>
                </EmptyState>
              </div>
            </div>
          </section>

          <!-- ⑩ VOTER MANAGEMENT — chief & deputy only (and owner/admin) -->
          <section v-if="canManageVoters || canManage">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Voter Management</h2>
                    <p class="text-xs text-slate-500">Approve or suspend election voters</p>
                  </div>
                </div>
                <a
                  v-if="elections.length > 0"
                  :href="route('elections.voters.index', { organisation: organisation.slug, election: elections[0].id })"
                  class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors"
                >Manage →</a>
              </div>
              <div class="px-8 py-5">
                <div class="grid grid-cols-3 gap-4">
                  <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center">
                    <p class="text-2xl font-bold text-slate-700">{{ stats?.members_count ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Total Members</p>
                  </div>
                  <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-700">{{ stats?.active_members_count ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Active</p>
                  </div>
                  <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600">{{ stats?.active_elections_count ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Live Elections</p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ⑪ RESULTS MANAGEMENT — chief only (and owner/admin) -->
          <section v-if="(canPublishResults || canManage) && completedElections.length > 0">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
              <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                  <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                </div>
                <div>
                  <h2 class="text-base font-semibold text-slate-800">Results Management</h2>
                  <p class="text-xs text-slate-500">Publish or review completed election results</p>
                </div>
              </div>
              <div class="px-8 py-5 flex flex-wrap gap-3">
                <a
                  v-for="election in completedElections"
                  :key="election.id"
                  :href="`/elections/${election.id}/management`"
                  class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg border transition-colors"
                  :class="election.results_published
                    ? 'border-emerald-300 text-emerald-700 hover:bg-emerald-50'
                    : 'border-blue-300 text-blue-700 hover:bg-blue-50'"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  {{ election.name }}
                  <span class="text-xs opacity-70">{{ election.results_published ? '· Published' : '· Unpublished' }}</span>
                </a>
              </div>
            </div>
          </section>

          <!-- ⑫ SUPPORT SECTION — everyone -->
          <section class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-200">
            <SupportSection />
          </section>

        </div>
      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'

import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import OrganizationHeader from './Partials/OrganizationHeader.vue'
import StatsGrid from './Partials/StatsGrid.vue'
import ActionButtons from './Partials/ActionButtons.vue'
import DemoResultsSection from './Partials/DemoResultsSection.vue'
import SupportSection from './Partials/SupportSection.vue'
import DemoSetupButton from './Partials/DemoSetupButton.vue'
import ElectionCard from './Partials/ElectionCard.vue'
import SectionCard from '@/Components/SectionCard.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import EmptyState from '@/Components/EmptyState.vue'

const { t } = useI18n()

const props = defineProps({
  organisation:        { type: Object, required: true },
  stats:               { type: Object, default: () => ({}) },
  demoStatus:          Object,
  canManage:           Boolean,
  canCreateElection:   Boolean,
  canActivateElection: Boolean,
  canManageVoters:     Boolean,
  canPublishResults:   Boolean,
  userRole:            String,
  isOfficer:           Boolean,
  isChief:             Boolean,
  isDeputy:            Boolean,
  isCommissioner:      Boolean,
  officers:            { type: Array, default: () => [] },
  orgMembers:          { type: Array, default: () => [] },
  elections:           { type: Array, default: () => [] },
})

const page = usePage()
const activatingId = ref(null)

const activeElections    = computed(() => props.elections.filter(e => e.status === 'active'))
const completedElections = computed(() => props.elections.filter(e => e.status === 'completed'))

const formatDate = (d) => d ? d.slice(0, 10) : '—'

const activateElection = (electionId) => {
  if (!confirm('Activate this election? Status will change to active.')) return
  activatingId.value = electionId
  router.post(route('elections.activate', { election: electionId }), {}, {
    preserveScroll: true,
    onFinish: () => { activatingId.value = null },
  })
}

const openOfficerModal = () => {
  router.visit(route('organisations.election-officers.index', props.organisation.slug))
}

useMeta({
  pageKey: 'organisations.show',
  params: {
    organisation: props.organisation?.name || 'organisation',
    memberCount:  props.stats?.members_count || '0',
    electionCount: props.stats?.elections_count || '0',
  },
})
</script>
