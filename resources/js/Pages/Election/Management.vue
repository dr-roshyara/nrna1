<template>
  <ElectionLayout>
    <!-- Skip link -->
    <a
      href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
    >Skip to main content</a>

    <main id="main-content" class="min-h-screen bg-slate-50 py-8">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <header class="bg-white rounded-2xl border border-slate-200 shadow-sm px-8 py-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Election Management</p>
              <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ election.name }}</h1>
              <p class="text-sm text-slate-500 mt-0.5">चुनाव व्यवस्थापन</p>
            </div>
            <StatusBadge :status="election.status" size="md" />
          </div>
        </header>

        <!-- Flash Messages -->
        <div
          v-if="page.props.flash?.success"
          role="alert"
          class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-emerald-800">{{ page.props.flash.success }}</p>
        </div>
        <div
          v-if="page.props.flash?.error"
          role="alert"
          class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-red-800">{{ page.props.flash.error }}</p>
        </div>

        <!-- ── ACTIVATION BANNER ───────────────────────────────── -->
        <SectionCard v-if="election.status === 'planned'" variant="warning" padding="lg">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <h2 class="text-base font-semibold text-amber-900">Ready to Activate?</h2>
                <p class="text-sm text-amber-700 mt-0.5">
                  Once activated, the status changes to <strong>Active</strong> and the voting period opens.
                </p>
              </div>
            </div>
            <ActionButton
              variant="warning"
              size="md"
              :loading="isActivating"
              class="sm:flex-shrink-0 w-full sm:w-auto"
              @click="activateElection"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              {{ isActivating ? 'Activating…' : 'Activate Election' }}
            </ActionButton>
          </div>
        </SectionCard>

        <!-- ── CURRENT STATUS ──────────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-base font-semibold text-slate-800">वर्तमान स्थिति | Current Status</h2>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Election System -->
            <div
              class="rounded-xl border p-5 flex items-center gap-4"
              :class="election.is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200'"
            >
              <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                :class="election.is_active ? 'bg-emerald-100' : 'bg-slate-100'"
              >
                <svg
                  class="w-5 h-5"
                  :class="election.is_active ? 'text-emerald-600' : 'text-slate-400'"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                >
                  <path v-if="election.is_active" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase tracking-wide" :class="election.is_active ? 'text-emerald-600' : 'text-slate-400'">चुनाव प्रणाली</p>
                <p class="text-sm font-semibold mt-0.5" :class="election.is_active ? 'text-emerald-800' : 'text-slate-600'">
                  {{ election.is_active ? 'सक्रिय | Active' : 'निष्क्रिय | Inactive' }}
                </p>
              </div>
            </div>

            <!-- Results Status -->
            <div
              class="rounded-xl border p-5 flex items-center gap-4"
              :class="election.results_published ? 'bg-blue-50 border-blue-200' : 'bg-slate-50 border-slate-200'"
            >
              <div
                class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                :class="election.results_published ? 'bg-blue-100' : 'bg-slate-100'"
              >
                <svg
                  class="w-5 h-5"
                  :class="election.results_published ? 'text-blue-600' : 'text-slate-400'"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold uppercase tracking-wide" :class="election.results_published ? 'text-blue-600' : 'text-slate-400'">चुनाव परिणाम</p>
                <p class="text-sm font-semibold mt-0.5" :class="election.results_published ? 'text-blue-800' : 'text-slate-600'">
                  {{ election.results_published ? 'प्रकाशित | Published' : 'अप्रकाशित | Unpublished' }}
                </p>
              </div>
            </div>
          </div>
        </SectionCard>

        <!-- ── VOTING STATISTICS ───────────────────────────────── -->
        <SectionCard v-if="stats && Object.keys(stats).length" padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-800">मतदान तथ्यांक | Voting Statistics</h2>
          </div>

          <!-- Summary Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
            <div class="rounded-xl bg-violet-50 border border-violet-200 p-5">
              <p class="text-xs font-semibold text-violet-500 uppercase tracking-wide">Total Members</p>
              <p class="text-3xl font-bold text-violet-800 mt-1">{{ stats.total_memberships ?? 0 }}</p>
              <p class="text-xs text-violet-500 mt-0.5">Registered</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-5">
              <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wide">Active Voters</p>
              <p class="text-3xl font-bold text-emerald-800 mt-1">{{ stats.active_voters ?? 0 }}</p>
              <p class="text-xs text-emerald-500 mt-0.5">Approved to vote</p>
            </div>
            <div class="rounded-xl bg-blue-50 border border-blue-200 p-5">
              <p class="text-xs font-semibold text-blue-500 uppercase tracking-wide">Eligible Voters</p>
              <p class="text-3xl font-bold text-blue-800 mt-1">{{ stats.eligible_voters ?? 0 }}</p>
              <p class="text-xs text-blue-500 mt-0.5">Not yet expired</p>
            </div>
          </div>

          <!-- Status Breakdown -->
          <div v-if="stats.by_status" class="rounded-xl bg-slate-50 border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">सदस्यता स्थिति | Membership Breakdown</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
              <div class="text-center">
                <p class="text-2xl font-bold text-emerald-700">{{ stats.by_status.active ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Active</p>
              </div>
              <div class="text-center">
                <p class="text-2xl font-bold text-amber-600">{{ stats.by_status.invited ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Invited</p>
              </div>
              <div class="text-center">
                <p class="text-2xl font-bold text-slate-400">{{ stats.by_status.inactive ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Inactive</p>
              </div>
              <div class="text-center">
                <p class="text-2xl font-bold text-red-500">{{ stats.by_status.removed ?? 0 }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Removed</p>
              </div>
            </div>
          </div>

          <!-- Empty state: no voters -->
          <EmptyState
            v-if="!stats.total_memberships"
            title="No voters assigned"
            description="Add members to this election to track voter statistics."
          >
            <template #icon>
              <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </template>
          </EmptyState>
        </SectionCard>

        <!-- ── VOTING PERIOD CONTROL ───────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div
              class="w-10 h-10 rounded-xl flex items-center justify-center"
              :class="isVotingActive ? 'bg-emerald-100' : 'bg-slate-100'"
            >
              <svg
                class="w-5 h-5"
                :class="isVotingActive ? 'text-emerald-600' : 'text-slate-400'"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <h2 class="text-base font-semibold text-slate-800">मतदान नियन्त्रण | Voting Control</h2>
              <p class="text-xs mt-0.5" :class="isVotingActive ? 'text-emerald-600 font-medium' : 'text-slate-400'">
                {{ isVotingActive ? 'Voting is currently active' : 'Voting is currently inactive' }}
              </p>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <ActionButton
              v-if="!isVotingActive"
              variant="success"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="openVoting"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              मतदान सुरु गर्नुहोस् | Open Voting
            </ActionButton>

            <ActionButton
              v-if="isVotingActive"
              variant="danger"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="closeVoting"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
              </svg>
              मतदान समाप्त गर्नुहोस् | Close Voting
            </ActionButton>
          </div>
        </SectionCard>

        <!-- ── VOTER MANAGEMENT ────────────────────────────────── -->
        <SectionCard padding="lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <h2 class="text-base font-semibold text-slate-800">मतदाता व्यवस्थापन | Voter Management</h2>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-center">
              <p class="text-2xl font-bold text-slate-700">{{ stats.total_memberships ?? 0 }}</p>
              <p class="text-xs text-slate-500 mt-0.5">कुल दर्ता | Total</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-center">
              <p class="text-2xl font-bold text-emerald-700">{{ stats.active_voters ?? 0 }}</p>
              <p class="text-xs text-slate-500 mt-0.5">स्वीकृत | Approved</p>
            </div>
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-center">
              <p class="text-2xl font-bold text-amber-600">{{ stats.by_status?.inactive ?? 0 }}</p>
              <p class="text-xs text-slate-500 mt-0.5">निलम्बित | Suspended</p>
            </div>
          </div>

          <ActionButton variant="outline" size="md" :href="voterListUrl" class="w-full sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            मतदाता सूची व्यवस्थापन | Manage Voter List
          </ActionButton>
        </SectionCard>

        <!-- ── RESULT MANAGEMENT ───────────────────────────────── -->
        <SectionCard v-if="canPublish" padding="lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <h2 class="text-base font-semibold text-slate-800">परिणाम व्यवस्थापन | Result Management</h2>
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <ActionButton
              v-if="!election.results_published"
              variant="success"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="publishResults"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8 8-8-8"/>
              </svg>
              परिणाम प्रकाशित गर्नुहोस् | Publish Results
            </ActionButton>

            <ActionButton
              v-if="election.results_published"
              variant="outline"
              size="md"
              :loading="isLoading"
              class="w-full sm:w-auto"
              @click="unpublishResults"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
              </svg>
              परिणाम लुकाउनुहोस् | Unpublish Results
            </ActionButton>
          </div>
        </SectionCard>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import ActionButton from '@/Components/ActionButton.vue'
import SectionCard from '@/Components/SectionCard.vue'
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
  election:   { type: Object, required: true },
  stats:      { type: Object, default: () => ({}) },
  canPublish: { type: Boolean, default: false },
})

const page = usePage()
const isLoading    = ref(false)
const isActivating = ref(false)

const isVotingActive = computed(() => props.election.status === 'active')

const voterListUrl = computed(() =>
  route('elections.voters.index', {
    organisation: props.election.organisation?.slug,
    election:     props.election.id,
  })
)

const activateElection = () => {
  if (!confirm('Are you sure you want to activate this election? The status will change to active and voting can begin.')) return
  isActivating.value = true
  router.post(route('elections.activate', { election: props.election.id }), {}, {
    preserveScroll: true,
    onFinish: () => { isActivating.value = false },
  })
}

const publishResults = () => {
  if (!confirm('Are you sure you want to publish the election results? This will make them available to all voters.')) return
  isLoading.value = true
  router.post(route('elections.publish', { election: props.election.id }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}

const unpublishResults = () => {
  if (!confirm('Are you sure you want to unpublish the election results?')) return
  isLoading.value = true
  router.post(route('elections.unpublish', { election: props.election.id }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}

const openVoting = () => {
  if (!confirm('Are you sure you want to open the voting period? Voters will be able to cast their votes.')) return
  isLoading.value = true
  router.post(route('elections.open-voting', { election: props.election.id }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}

const closeVoting = () => {
  if (!confirm('Are you sure you want to close the voting period? No new votes will be accepted.')) return
  isLoading.value = true
  router.post(route('elections.close-voting', { election: props.election.id }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
</script>
