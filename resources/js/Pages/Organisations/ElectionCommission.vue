<template>
  <ElectionLayout>
    <!-- Flash Message -->
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
          <span class="text-slate-900 font-medium">Election Commission</span>
        </nav>

        <!-- Header with stats -->
        <SectionCard>
          <template #header>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-slate-900">Election Commission</h1>
                <p class="text-sm text-slate-500">Manage elections, voters, and candidates</p>
              </div>
            </div>
          </template>

          <!-- Stats grid -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
            <div class="text-center p-4 bg-slate-50 rounded-xl">
              <div class="text-2xl font-bold text-slate-900">{{ stats.elections_count }}</div>
              <div class="text-xs text-slate-500 mt-1">Elections</div>
            </div>
            <div class="text-center p-4 bg-emerald-50 rounded-xl">
              <div class="text-2xl font-bold text-emerald-700">{{ stats.active_elections }}</div>
              <div class="text-xs text-slate-500 mt-1">Active</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-xl">
              <div class="text-2xl font-bold text-blue-700">{{ stats.total_voters }}</div>
              <div class="text-xs text-slate-500 mt-1">Active Voters</div>
            </div>
            <div class="text-center p-4 bg-slate-50 rounded-xl">
              <div class="text-2xl font-bold text-slate-900">{{ stats.officers_count }}</div>
              <div class="text-xs text-slate-500 mt-1">Officers</div>
            </div>
          </div>
        </SectionCard>

        <!-- Elections list -->
        <section>
          <h2 class="text-lg font-semibold text-slate-800 mb-4">Elections</h2>

          <EmptyState v-if="elections.length === 0"
            title="No elections yet"
            description="Create an election to get started."
          />

          <div v-else class="space-y-4">
            <div v-for="election in elections" :key="election.id"
              class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden"
            >
              <!-- Election header bar -->
              <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-base font-bold text-slate-900">{{ election.name }}</h3>
                <StatusBadge :status="election.status" />
              </div>

              <!-- Action buttons -->
              <div class="px-6 py-5 flex flex-wrap gap-3">
                <a :href="route('elections.management', { election: election.slug })"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                  Election Management
                </a>
                <a :href="route('elections.voters.index', { organisation: organisation.slug, election: election.slug })"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  Voter Management
                </a>
                <a :href="route('organisations.elections.posts.index', { organisation: organisation.slug, election: election.slug })"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  Posts &amp; Candidates
                </a>
                <a :href="route('organisations.elections.candidacy.applications', { organisation: organisation.slug, election: election.slug })"
                  class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Candidacy Applications
                </a>
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
import StatusBadge from '@/Components/StatusBadge.vue'
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  elections:    { type: Array,  default: () => [] },
  stats:        { type: Object, default: () => ({}) },
  canManage:    Boolean,
  isChief:      Boolean,
  isDeputy:     Boolean,
})

const page = usePage()
</script>
