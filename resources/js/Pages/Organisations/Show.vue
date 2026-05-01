<template>
  <div class="org-page min-h-screen flex flex-col">
    <PublicDigitHeader />
    <div class="flex-1">

    <a href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded-lg focus:shadow-lg"
    >{{ $t('pages.organisation-show.accessibility.skip_to_main') }}</a>
    <div role="status" aria-live="polite" class="sr-only">
      {{ $t('pages.organisation-show.accessibility.page_loaded', { organisation: organisation.name }) }}
    </div>

    <!-- Flash -->
    <div v-if="page.props.flash?.success"
      class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ page.props.flash.success }}
    </div>

    <main id="main-content" role="main">

      <!-- ═══════════════════════════════════════════════════
           ZONE 1 · ORGANISATION AT A GLANCE
           White zone — header + stats
      ═══════════════════════════════════════════════════ -->
      <div class="zone zone--glance">
        <div class="zone__inner">
          <div class="zone__label">
            <span class="zone__label-dot zone__label-dot--slate"></span>
            Organisation at a Glance
          </div>

          <!-- Officer role banner -->
          <div v-if="isOfficer"
            class="officer-banner mb-5"
            :class="{
              'officer-banner--chief': isChief,
              'officer-banner--deputy': isDeputy,
              'officer-banner--commissioner': isCommissioner,
            }"
          >
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span class="text-sm font-medium">
              You are <strong class="capitalize">{{ isChief ? 'Election Chief' : isDeputy ? 'Election Deputy' : 'Election Commissioner' }}</strong>
              <template v-if="officerElectionNames.length > 0"> for <strong>{{ officerElectionNames.join(', ') }}</strong>.</template>
              <template v-else> for this organisation.</template>
            </span>
          </div>

          <OrganizationHeader :organisation="organisation" />

          <div class="mt-6">
            <StatsGrid :stats="stats" :organisation-slug="organisation.slug" />
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════
           ZONE 2 · MEMBER PORTALS
           Soft blue zone — Voter Hub + Election Commission
      ═══════════════════════════════════════════════════ -->
      <div class="zone zone--portals">
        <div class="zone__inner">
          <div class="zone__label">
            <span class="zone__label-dot zone__label-dot--blue"></span>
            Member Portals
          </div>

          <div class="portal-grid">
            <!-- Voter Hub -->
            <a :href="route('organisations.voter-hub', organisation.slug)" class="portal-card portal-card--voter">
              <div class="portal-card__icon-wrap portal-card__icon-wrap--voter">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div class="portal-card__body">
                <h3 class="portal-card__title">Voter Hub</h3>
                <p class="portal-card__desc">View active elections and your voting status</p>
              </div>
              <div class="portal-card__arrow">→</div>
            </a>

            <!-- Election Commission — officers & admins only -->
            <a v-if="canManage || isOfficer"
               :href="route('organisations.election-commission', organisation.slug)"
               class="portal-card portal-card--commission"
            >
              <div class="portal-card__icon-wrap portal-card__icon-wrap--commission">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <div class="portal-card__body">
                <h3 class="portal-card__title">Election Commission</h3>
                <p class="portal-card__desc">Manage elections, voters, and candidates</p>
              </div>
              <div class="portal-card__arrow">→</div>
            </a>

            <!-- Membership Dashboard -->
            <a :href="`/organisations/${organisation.slug}/membership`" class="portal-card portal-card--membership">
              <div class="portal-card__icon-wrap portal-card__icon-wrap--membership">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="portal-card__body">
                <h3 class="portal-card__title">Membership</h3>
                <p class="portal-card__desc">Manage your membership, fees, and renewals</p>
              </div>
              <div class="portal-card__arrow">→</div>
            </a>

            <!-- Organisation Roles — owner & admin only -->
            <a v-if="canManage"
               :href="route('organisations.membership.roles.index', organisation.slug)"
               class="portal-card portal-card--roles"
            >
              <div class="portal-card__icon-wrap portal-card__icon-wrap--roles">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div class="portal-card__body">
                <h3 class="portal-card__title">Organisation Roles</h3>
                <p class="portal-card__desc">View roles and add role-holders as members</p>
              </div>
              <div class="portal-card__arrow">→</div>
            </a>
          </div>
        </div>
      </div>


      <!-- ═══════════════════════════════════════════════════
           ZONE 3 · LIVE VOTING  (only when elections open)
           Emerald accent zone
      ═══════════════════════════════════════════════════ -->
      <div v-if="activeElections.length > 0" class="zone zone--live-wrap">
        <div class="zone__inner zone__inner--live">
          <div class="zone__label zone__label--light">
            <span class="zone__label-dot zone__label-dot--green"></span>
            Live Voting
          </div>

          <div class="live-grid">
            <div
              v-for="election in activeElections"
              :key="election.id"
              class="live-card"
              :class="{
                'live-card--voted':      voterStatus(election.id) === 'voted',
                'live-card--eligible':   voterStatus(election.id) === 'eligible',
                'live-card--ineligible': voterStatus(election.id) === 'ineligible',
              }"
            >
              <div class="live-card__pulse" aria-hidden="true"></div>
              <p class="live-card__name">{{ election.name }}</p>
              <p class="live-card__dates">{{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }}</p>
              <div class="live-card__actions">
                <span v-if="voterStatus(election.id) === 'voted'"
                  class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-100 px-3 py-1.5 rounded-full">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  Vote Cast
                </span>
                <span v-else-if="voterStatus(election.id) === 'ineligible'"
                  class="inline-flex items-center gap-1.5 text-xs font-medium text-white/50 bg-white/10 px-3 py-1.5 rounded-full">
                  Not a voter
                </span>
                <Button v-else as="a" :href="route('elections.show', election.slug)" variant="success" size="sm">
                  <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                  Vote Now
                </Button>
                <a v-if="canManage || isOfficer" :href="route('elections.show', election.slug)"
                  class="text-xs font-medium text-white/60 hover:text-white underline underline-offset-2 transition-colors">
                  View →
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════
           ZONE 4 · ADMINISTRATION
           Slate-tinted zone — elections list, officers, voters, results
           Only shown to admins / officers
      ═══════════════════════════════════════════════════ -->
      <div v-if="canManage || isOfficer || canManageVoters" class="zone zone--admin">
        <div class="zone__inner">
          <div class="zone__label">
            <span class="zone__label-dot zone__label-dot--amber"></span>
            Administration
          </div>

          <div class="admin-stack">

            <!-- Quick Actions -->
            <Card v-if="canManage" mode="admin" padding="lg">
              <ActionButtons
                :organisation="organisation"
                :can-manage="canManage"
                :can-create-election="canCreateElection"
                @appoint-officer="openOfficerModal"
              />
            </Card>

            <!-- Elections List -->
            <Card mode="admin" padding="none" class="overflow-hidden">
              <div class="admin-card-header">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Elections</h2>
                    <p v-if="elections.length > 0" class="text-xs text-slate-500">{{ elections.length }} election{{ elections.length !== 1 ? 's' : '' }}</p>
                  </div>
                </div>
                <Button v-if="canCreateElection" as="a" :href="route('organisations.elections.create', organisation.slug)" variant="primary" size="sm">
                  <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  New Election
                </Button>
              </div>
              <div v-if="elections.length > 0" class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <ElectionCard
                  v-for="election in elections" :key="election.id"
                  :election="election" :activating-id="activatingId"
                  :can-activate="canActivateElection && election.status === 'planned'"
                  :can-manage="canManage || isChief || isDeputy"
                  :is-readonly="isCommissioner || (!canManage && !isOfficer)"
                  @activate="activateElection"
                />
              </div>
              <div v-else class="p-6">
                <EmptyState title="No elections yet" :description="canCreateElection ? 'Create your first election to get started.' : 'Check back later for upcoming elections.'">
                  <template #icon><svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></template>
                  <template v-if="canCreateElection" #action>
                    <Button as="a" :href="route('organisations.elections.create', organisation.slug)" variant="primary">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                      Create First Election
                    </Button>
                  </template>
                </EmptyState>
              </div>
            </Card>

            <!-- Officer Management -->
            <Card v-if="canManage" mode="admin" padding="none" class="overflow-hidden">
              <div class="admin-card-header">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Election Officers</h2>
                    <p class="text-xs text-slate-500">Manage who can oversee elections</p>
                  </div>
                </div>
                <a :href="route('organisations.election-officers.index', organisation.slug)"
                  class="text-sm font-semibold text-primary-600 hover:text-primary-800 hover:underline transition-colors">Manage →</a>
              </div>
              <div class="px-8 py-5">
                <div v-if="officers && officers.length > 0" class="flex flex-wrap gap-2">
                  <div v-for="officer in officers.slice(0, 6)" :key="officer.id"
                    class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-sm">
                    <div class="w-6 h-6 rounded-full bg-slate-300 flex items-center justify-center text-xs font-bold text-slate-600">
                      {{ officer.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="font-medium text-slate-700">{{ officer.user_name }}</span>
                    <span class="text-xs font-semibold px-1.5 py-0.5 rounded"
                      :class="{ 'bg-emerald-100 text-emerald-700': officer.role === 'chief', 'bg-primary-100 text-primary-700': officer.role === 'deputy', 'bg-slate-100 text-slate-600': officer.role === 'commissioner' }">
                      {{ officer.role }}
                    </span>
                  </div>
                  <div v-if="officers.length > 6" class="flex items-center px-3 py-1.5 text-sm text-slate-500">+{{ officers.length - 6 }} more</div>
                </div>
                <EmptyState v-else title="No officers appointed" description="Appoint chiefs and deputies to manage your elections.">
                  <template #icon><svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></template>
                  <template #action>
                    <Button as="a" :href="route('organisations.election-officers.index', organisation.slug)" variant="secondary">Appoint First Officer</Button>
                  </template>
                </EmptyState>
              </div>
            </Card>

            <!-- Voter Management -->
            <Card v-if="canManageVoters || canManage" mode="admin" padding="none" class="overflow-hidden">
              <div class="admin-card-header">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Voter Management</h2>
                    <p class="text-xs text-slate-500">Approve or suspend election voters</p>
                  </div>
                </div>
                <div v-if="elections.length > 0" class="flex flex-col items-end gap-1">
                  <a v-for="election in elections" :key="election.id"
                    :href="route('organisations.elections.voters', { organisation: organisation.slug, election: election.slug })"
                    class="text-sm font-semibold text-primary-600 hover:text-primary-800 hover:underline transition-colors">
                    {{ election.name }} →
                  </a>
                </div>
              </div>
              <div class="px-8 py-5">
                <div class="grid grid-cols-3 gap-4">
                  <a
                    :href="`/organisations/${organisation.slug}/members`"
                    class="block rounded-xl bg-slate-50 border border-slate-200 p-4 text-center hover:bg-slate-100 hover:border-slate-400 hover:shadow-sm transition-all duration-200 group no-underline"
                  >
                    <p class="text-2xl font-bold text-slate-700 group-hover:text-slate-900">{{ stats?.members_count ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-0.5 group-hover:text-slate-700">Total Members</p>
                  </a>
                  <a
                    :href="`/organisations/${organisation.slug}/members`"
                    class="block rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center hover:bg-emerald-100 hover:border-emerald-400 hover:shadow-sm transition-all duration-200 group no-underline"
                  >
                    <p class="text-2xl font-bold text-emerald-700 group-hover:text-emerald-900">{{ stats?.active_members_count ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-0.5 group-hover:text-slate-700">Active</p>
                  </a>
                  <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600">{{ stats?.active_elections_count ?? 0 }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Live Elections</p>
                  </div>
                </div>
              </div>
            </Card>

            <!-- Demo Setup -->
            <DemoSetupButton v-if="canManage && !demoStatus?.exists" :organisation="organisation" :demo-status="demoStatus" />

            <!-- Results Management -->
            <Card v-if="(canPublishResults || canManage) && completedElections.length > 0" mode="admin" padding="none" class="overflow-hidden">
              <div class="admin-card-header">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                  </div>
                  <div>
                    <h2 class="text-base font-semibold text-slate-800">Results Management</h2>
                    <p class="text-xs text-slate-500">Publish or review completed election results</p>
                  </div>
                </div>
              </div>
              <div class="px-8 py-5 flex flex-wrap gap-3">
                <a v-for="election in completedElections" :key="election.id"
                  :href="`/elections/${election.slug}/management`"
                  class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg border transition-colors"
                  :class="election.results_published ? 'border-emerald-300 text-emerald-700 hover:bg-emerald-50' : 'border-primary-300 text-primary-700 hover:bg-primary-50'">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  {{ election.name }}
                  <span class="text-xs opacity-70">{{ election.results_published ? '· Published' : '· Unpublished' }}</span>
                </a>
              </div>
            </Card>

            <!-- Demo Results -->
            <Card mode="admin" padding="lg">
              <DemoResultsSection />
            </Card>

          </div><!-- /admin-stack -->
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════
           ZONE 5 · SUPPORT
      ═══════════════════════════════════════════════════ -->
      <div class="zone zone--support">
        <div class="zone__inner">
          <Card mode="admin" padding="none" class="overflow-hidden">
            <SupportSection />
          </Card>
        </div>
      </div>

    </main>
    </div>
    <PublicDigitFooter class="px-4" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { useMeta } from '@/composables/useMeta'

import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
import OrganizationHeader from './Partials/OrganizationHeader.vue'
import StatsGrid from './Partials/StatsGrid.vue'
import ActionButtons from './Partials/ActionButtons.vue'
import DemoResultsSection from './Partials/DemoResultsSection.vue'
import SupportSection from './Partials/SupportSection.vue'
import DemoSetupButton from './Partials/DemoSetupButton.vue'
import ElectionCard from './Partials/ElectionCard.vue'
import Button from '@/Components/Button.vue'
import Card from '@/Components/Card.vue'
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
  isCommissioner:       Boolean,
  officerElectionNames: { type: Array, default: () => [] },
  officers:             { type: Array, default: () => [] },
  orgMembers:          { type: Array, default: () => [] },
  elections:           { type: Array, default: () => [] },
  voterMemberships:    { type: Object, default: () => ({}) },
})

const page = usePage()
const activatingId = ref(null)

const activeElections    = computed(() => props.elections.filter(e => e.status === 'active'))
const completedElections = computed(() => props.elections.filter(e => e.status === 'completed'))

const voterStatus = (electionId) => {
  const m = props.voterMemberships[electionId]
  if (!m || m.status === 'removed') return 'ineligible'
  if (m.has_voted) return 'voted'
  if (m.role === 'voter' && m.status === 'active') return 'eligible'
  return 'ineligible'
}

const formatDate = (d) => d ? d.slice(0, 10) : '—'

const activateElection = (electionSlug) => {
  if (!confirm('Activate this election? Status will change to active.')) return
  activatingId.value = electionSlug
  router.post(route('elections.activate', { election: electionSlug }), {}, {
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

<style scoped>
/* ─── Zone system ────────────────────────────────────────── */
.zone {
  padding: 3rem 1rem;
}
.zone__inner {
  max-width: 72rem;
  margin: 0 auto;
}

/* Zone label strip */
.zone__label {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.6875rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #64748b;
  margin-bottom: 1.75rem;
  padding: 0.3rem 0.85rem;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 999px;
}
.zone__label--light {
  color: rgba(255,255,255,0.85);
  background: rgba(255,255,255,0.12);
  border-color: rgba(255,255,255,0.2);
}
.zone__label-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  flex-shrink: 0;
}
.zone__label-dot--slate  { background: #64748b; }
.zone__label-dot--blue   { background: #3b82f6; }
.zone__label-dot--green  { background: #10b981; }
.zone__label-dot--amber  { background: #f59e0b; }

/* ─── Zone backgrounds ───────────────────────────────────── */
.zone--glance {
  background: #ffffff;
  border-bottom: 1px solid #f1f5f9;
}
.zone--portals {
  background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%);
  border-bottom: 1px solid #dbeafe;
}
.zone--live-wrap {
  background: #f0fdf4;
  border-bottom: 1px solid #d1fae5;
  padding: 3rem 1rem;
}
.zone__inner--live {
  background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
  border-radius: 1.5rem;
  padding: 2.5rem 2rem;
  box-shadow: 0 8px 32px rgba(6,78,59,0.18);
}
.zone--admin {
  background: #e2e8f0;
  border-bottom: 1px solid #cbd5e1;
}
.zone--support {
  background: #f1f5f9;
}

/* ─── Portal cards (Zone 2) ──────────────────────────────── */
.portal-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.25rem;
}
@media (min-width: 640px) {
  .portal-grid { grid-template-columns: repeat(2, 1fr); }
}

.portal-card {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  padding: 1.5rem;
  border-radius: 1.25rem;
  border: 2px solid transparent;
  background: #fff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
  text-decoration: none;
  transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}
.portal-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.portal-card--membership:hover { border-color: #7c3aed; }
.portal-card--voter:hover   { border-color: #3b82f6; }
.portal-card--commission:hover { border-color: #10b981; }
.portal-card--roles:hover      { border-color: #7c3aed; }

.portal-card__icon-wrap {
  width: 3rem;
  height: 3rem;
  border-radius: 0.875rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.portal-card__icon-wrap--membership { background: #ede9fe; color: #7c3aed; }
.portal-card__icon-wrap--voter      { background: #dbeafe; color: #2563eb; }
.portal-card__icon-wrap--commission { background: #d1fae5; color: #059669; }
.portal-card__icon-wrap--roles      { background: #ede9fe; color: #7c3aed; }

.portal-card__body { flex: 1; min-width: 0; }
.portal-card__title {
  font-size: 1.0625rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.2rem;
}
.portal-card__desc {
  font-size: 0.8125rem;
  color: #64748b;
  line-height: 1.4;
}
.portal-card__arrow {
  font-size: 1.25rem;
  color: #94a3b8;
  flex-shrink: 0;
  transition: transform 0.15s ease, color 0.15s ease;
}
.portal-card:hover .portal-card__arrow {
  transform: translateX(3px);
  color: #475569;
}

/* ─── Live cards (Zone 3) ────────────────────────────────── */
.live-grid {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.25rem;
}

.live-card {
  position: relative;
  width: 100%;
  max-width: 36rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 0.5rem;
  padding: 2rem 2.5rem;
  border-radius: 1.25rem;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  backdrop-filter: blur(6px);
  transition: background 0.2s;
}
.live-card:hover { background: rgba(255,255,255,0.14); }
.live-card--voted   { border-color: rgba(110,231,183,0.5); }
.live-card--eligible { border-color: rgba(167,243,208,0.6); }

/* Pulsing live dot */
.live-card__pulse {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #34d399;
  box-shadow: 0 0 0 0 rgba(52,211,153,0.6);
  animation: pulse-ring 1.8s ease-out infinite;
  margin-bottom: 0.25rem;
}
@keyframes pulse-ring {
  0%   { box-shadow: 0 0 0 0 rgba(52,211,153,0.6); }
  70%  { box-shadow: 0 0 0 10px rgba(52,211,153,0); }
  100% { box-shadow: 0 0 0 0 rgba(52,211,153,0); }
}

.live-card__name {
  font-size: 1.25rem;
  font-weight: 700;
  color: #ffffff;
  line-height: 1.3;
}
.live-card__dates {
  font-size: 0.8125rem;
  color: rgba(255,255,255,0.55);
  margin-bottom: 0.5rem;
}
.live-card__actions {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

/* ─── Admin zone ─────────────────────────────────────────── */
.admin-stack { display: flex; flex-direction: column; gap: 1.5rem; }

.admin-card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 2rem;
  border-bottom: 1px solid #f1f5f9;
  background: #ffffff;
}

/* ─── Officer banner ─────────────────────────────────────── */
.officer-banner {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.625rem 1rem;
  border-radius: 0.625rem;
  font-size: 0.875rem;
}
.officer-banner--chief       { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.officer-banner--deputy      { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.officer-banner--commissioner { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
</style>

