<template>
  <ElectionLayout>
    <!-- Flash Messages -->
    <div v-if="page.props.flash?.success" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2">
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ page.props.flash.success }}
    </div>

    <main class="py-10 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-slate-700 transition-colors">{{ organisation.name }}</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          <span class="text-slate-900 font-medium">Voter Hub</span>
        </nav>

        <!-- Header -->
        <SectionCard>
          <template #header>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-slate-900">Voter Hub</h1>
                <p class="text-sm text-slate-500">Your member portal — vote, apply for candidacy, and track your activity</p>
              </div>
            </div>
          </template>
        </SectionCard>

        <!-- Quick Navigation Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <!-- Apply for Candidacy -->
          <a :href="route('organisations.candidacy.create', organisation.slug)"
            class="group block rounded-2xl border border-slate-200 bg-white p-6 hover:shadow-md hover:border-primary-300 transition-all"
          >
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3 group-hover:bg-amber-200 transition-colors">
              <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">Apply for Candidacy</h3>
            <p class="text-sm text-slate-500">Submit your nomination for an election post</p>
          </a>

          <!-- My Applications -->
          <a :href="route('organisations.candidacy.list', organisation.slug)"
            class="group block rounded-2xl border border-slate-200 bg-white p-6 hover:shadow-md hover:border-primary-300 transition-all"
          >
            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center mb-3 group-hover:bg-primary-200 transition-colors">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">My Applications</h3>
            <p class="text-sm text-slate-500">Track the status of your candidacy submissions</p>
          </a>

          <!-- View Positions — one card per active election -->
          <a
            v-for="election in activeElections"
            :key="election.id"
            :href="route('organisations.elections.positions', { organisation: organisation.slug, election: election.slug })"
            class="group block rounded-2xl border border-slate-200 bg-white p-6 hover:shadow-md hover:border-primary-300 transition-all"
          >
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">Positions</h3>
            <p class="text-sm text-slate-500">{{ election.name }}</p>
          </a>
          <!-- See Real Result — one card per active election -->
          <a
            v-for="election in activeElections"
            :key="'result-' + election.id"
            :href="route('result.index', { election: election.slug })"
            class="group block rounded-2xl border border-slate-200 bg-white p-6 hover:shadow-md hover:border-violet-300 transition-all"
          >
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3 group-hover:bg-violet-200 transition-colors">
              <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">See Real Result</h3>
            <p class="text-sm text-slate-500">{{ election.name }}</p>
          </a>

          <!-- See Demo Result -->
          <a :href="route('demo-result.index')"
            class="group block rounded-2xl border border-slate-200 bg-white p-6 hover:shadow-md hover:border-emerald-300 transition-all"
          >
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3 group-hover:bg-emerald-200 transition-colors">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <h3 class="font-semibold text-slate-900 mb-1">See Demo Result</h3>
            <p class="text-sm text-slate-500">View the demo election results</p>
          </a>
        </div>

        <!-- Active Elections -->
        <section>
          <h2 class="text-lg font-semibold text-slate-800 mb-4">Active Elections</h2>

          <EmptyState v-if="activeElections.length === 0"
            title="No active elections"
            description="There are currently no active elections in this organisation."
          />

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="election in activeElections" :key="election.id"
              class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow"
            >
              <!-- Card Header -->
              <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-4">
                <h3 class="font-semibold text-white text-base leading-tight">{{ election.name }}</h3>
                <p v-if="election.description" class="text-primary-100 text-xs mt-1 line-clamp-2">{{ election.description }}</p>
              </div>

              <!-- Card Body -->
              <div class="px-5 py-4 space-y-4">
                <!-- Dates -->
                <div v-if="election.start_date || election.end_date" class="flex items-center gap-1.5 text-xs text-slate-500">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <span>{{ formatDate(election.start_date) }}<span v-if="election.end_date"> – {{ formatDate(election.end_date) }}</span></span>
                </div>

                <!-- Voter Status Badge -->
                <div>
                  <span :class="statusBadgeClass(election.id)" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium">
                    {{ statusLabel(election.id) }}
                  </span>
                </div>

                <!-- Posts / Positions -->
                <div v-if="election.posts && election.posts.length > 0">
                  <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Positions</p>
                  <ul class="space-y-1">
                    <li v-for="post in election.posts" :key="post.id" class="flex items-center justify-between text-sm">
                      <span class="text-slate-700">{{ post.name }}</span>
                      <span class="text-xs text-slate-400">
                        {{ post.is_national_wide ? 'National' : post.state_name || 'Regional' }}
                        · {{ post.required_number }} seat{{ post.required_number !== 1 ? 's' : '' }}
                      </span>
                    </li>
                  </ul>
                </div>

                <!-- Vote Action -->
                <div class="pt-1">
                  <a v-if="voterStatus(election.id) === 'eligible'"
                    :href="route('elections.show', { slug: election.slug })"
                    class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 rounded-lg transition-colors"
                  >
                    Vote Now
                  </a>
                  <div v-else-if="voterStatus(election.id) === 'voted'" class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Vote submitted
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { usePage } from '@inertiajs/vue3'
import SectionCard from '@/Components/SectionCard.vue'
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
  organisation:     { type: Object, required: true },
  activeElections:  { type: Array,  default: () => [] },
  voterMemberships: { type: Object, default: () => ({}) },
  myApplications:   { type: Array,  default: () => [] },
})

const page = usePage()

function voterStatus(electionId) {
  const m = props.voterMemberships[electionId]
  if (!m) return 'ineligible'
  if (m.has_voted) return 'voted'
  if (m.status === 'active') return 'eligible'
  return 'ineligible'
}

function statusLabel(electionId) {
  const s = voterStatus(electionId)
  if (s === 'eligible') return 'Eligible to vote'
  if (s === 'voted')    return 'Voted'
  return 'Not eligible'
}

function statusBadgeClass(electionId) {
  const s = voterStatus(electionId)
  if (s === 'eligible') return 'bg-emerald-100 text-emerald-700'
  if (s === 'voted')    return 'bg-blue-100 text-blue-700'
  return 'bg-slate-100 text-slate-600'
}

function formatDate(d) {
  return d ? d.slice(0, 10) : '—'
}
</script>
