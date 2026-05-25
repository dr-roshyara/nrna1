<template>
  <ElectionLayout>
    <main id="main-content" class="min-h-screen bg-slate-100 py-8">
      <div class="mx-auto w-full max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <Card mode="admin" padding="lg" class="rounded-2xl">
          <div class="flex items-center gap-3 mb-2">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">{{ $t('pages.election-submit-for-approval.title') }}</p>
          </div>
          <h1 class="text-2xl font-bold text-slate-900">{{ election.name }}</h1>
          <p class="text-slate-600 mt-2">{{ $t('pages.election-submit-for-approval.subtitle') }}</p>
        </Card>

        <!-- Approval Workflow Info -->
        <Card mode="admin" padding="lg" class="rounded-2xl border-2 border-primary-200 bg-primary-50">
          <div class="flex gap-4">
            <svg class="w-6 h-6 text-primary-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <h3 class="text-lg font-bold text-primary-900 mb-2">{{ $t('pages.election-submit-for-approval.approval_workflow.title') }}</h3>
              <div v-if="election.expected_voter_count > 40" class="space-y-2">
                <p class="text-primary-800 font-medium">
                  {{ $t('pages.election-submit-for-approval.approval_workflow.paid_election_label', { count: election.expected_voter_count }) }}
                </p>
                <p class="text-primary-700 text-sm leading-relaxed">
                  {{ $t('pages.election-submit-for-approval.approval_workflow.paid_election_description') }}
                </p>
              </div>
              <div v-else class="space-y-2">
                <p class="text-primary-800 font-medium">
                  {{ $t('pages.election-submit-for-approval.approval_workflow.free_election_label', { count: election.expected_voter_count }) }}
                </p>
                <p class="text-primary-700 text-sm leading-relaxed">
                  {{ $t('pages.election-submit-for-approval.approval_workflow.free_election_description') }}
                </p>
              </div>
            </div>
          </div>
        </Card>

        <!-- Submission Prerequisites -->
        <Card mode="admin" padding="lg" class="rounded-2xl">
          <h2 class="text-lg font-bold text-slate-900 mb-4">{{ $t('pages.election-submit-for-approval.submission_requirements.title') }}</h2>
          <p class="text-slate-700 mb-4 text-sm">{{ $t('pages.election-submit-for-approval.submission_requirements.subtitle') }}</p>

          <div class="space-y-3">
            <!-- Election Name -->
            <div class="flex items-start gap-4 p-4 rounded-lg border-2 border-green-200 bg-green-50">
              <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <div class="flex-1">
                <p class="text-green-900 font-semibold">{{ $t('pages.election-submit-for-approval.submission_requirements.election_name') }}</p>
                <p class="text-green-700 text-sm">{{ election.name }}</p>
              </div>
            </div>

            <!-- Expected Voter Count -->
            <div class="flex items-start gap-4 p-4 rounded-lg border-2 transition-colors" :class="election.expected_voter_count > 0 ? 'border-green-200 bg-green-50' : 'border-danger-200 bg-danger-50'">
              <svg v-if="election.expected_voter_count > 0" class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <svg v-else class="w-6 h-6 text-danger-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <div class="flex-1">
                <p :class="election.expected_voter_count > 0 ? 'text-green-900 font-semibold' : 'text-danger-900 font-semibold'">
                  {{ $t('pages.election-submit-for-approval.submission_requirements.expected_voter_count') }}
                </p>
                <p :class="election.expected_voter_count > 0 ? 'text-green-700 text-sm' : 'text-danger-700 text-sm'">
                  {{ election.expected_voter_count }} {{ election.expected_voter_count !== 1 ? $t('pages.election-submit-for-approval.submission_requirements.voter_plural') : $t('pages.election-submit-for-approval.submission_requirements.voter_singular') }} {{ $t('pages.election-submit-for-approval.submission_requirements.voter_count_expected') }}
                  <span v-if="!isReady"> — {{ $t('pages.election-submit-for-approval.submission_requirements.voter_count_error') }}</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Setup Timing Info -->
          <div class="mt-6 p-4 rounded-lg bg-primary-50 border border-primary-200">
            <div class="flex gap-3">
              <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-sm text-primary-700">
                <p class="font-semibold">{{ $t('pages.election-submit-for-approval.setup_timing_info') }}</p>
              </div>
            </div>
          </div>
        </Card>

        <!-- Actions -->
        <div class="flex gap-3 justify-end">
          <Link
            :href="route('elections.management', { election: election.slug })"
            class="px-6 py-3 rounded-lg border border-neutral-300 bg-white text-neutral-700 hover:bg-neutral-50 font-semibold transition-colors"
          >
            {{ $t('pages.election-submit-for-approval.actions.back_to_management') }}
          </Link>
          <button
            v-if="isReady"
            @click="submitForApproval"
            :disabled="isLoading"
            class="px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 disabled:bg-primary-400 text-white font-semibold transition-colors disabled:cursor-not-allowed flex items-center gap-2"
          >
            <span v-if="isLoading" class="inline-block animate-spin">⟳</span>
            {{ isLoading ? $t('pages.election-submit-for-approval.actions.submitting') : (election.expected_voter_count > 40 ? $t('pages.election-submit-for-approval.actions.submit_for_approval') : $t('pages.election-submit-for-approval.actions.submit_auto_approved')) }}
          </button>
          <button
            v-else
            disabled
            class="px-6 py-3 rounded-lg bg-neutral-300 text-neutral-600 font-semibold cursor-not-allowed"
            :title="!isDraft ? $t('pages.election-submit-for-approval.button_titles.already_submitted') : $t('pages.election-submit-for-approval.button_titles.set_voter_count_first')"
          >
            {{ !isDraft ? $t('pages.election-submit-for-approval.actions.already_submitted') : $t('pages.election-submit-for-approval.actions.set_voter_count_first') }}
          </button>
        </div>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  election: Object,
  organisation: Object,
})

// Redirect only if state changes FROM draft (meaning submission completed)
// The onSuccess callback already handles the redirect after submission
watch(() => props.election?.state, (state, oldState) => {
  if (oldState === 'draft' && state && state !== 'draft') {
    router.visit(route('elections.management', {
      election: props.election.slug
    }))
  }
})

const isLoading = ref(false)

const isDraft = computed(() => props.election?.state === 'draft')

const hasVoterCount = computed(() => props.election?.expected_voter_count > 0)

// Backend decides auto vs manual based on voter count
const isReady = computed(() => isDraft.value && hasVoterCount.value)

const submitForApproval = () => {
  if (!isReady.value) return

  isLoading.value = true
  router.post(
    route('elections.submit-for-approval', { election: props.election.slug }),
    {},
    {
      onSuccess: () => {
        router.visit(route('elections.management', { election: props.election.slug }))
      },
      onError: () => {
        isLoading.value = false
      },
      onFinish: () => {
        isLoading.value = false
      },
    }
  )
}
</script>

