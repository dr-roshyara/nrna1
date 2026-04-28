<template>
  <ElectionLayout>
    <main id="main-content" class="min-h-screen bg-slate-100 py-8">
      <div class="mx-auto w-full max-w-3xl px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Page Header -->
        <Card mode="admin" padding="lg" class="rounded-2xl">
          <div class="flex items-center gap-3 mb-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Submit for Approval</p>
          </div>
          <h1 class="text-2xl font-bold text-slate-900">{{ election.name }}</h1>
          <p class="text-slate-600 mt-2">Review and submit this election for processing</p>
        </Card>

        <!-- Approval Workflow Info -->
        <Card mode="admin" padding="lg" class="rounded-2xl border-2 border-blue-200 bg-blue-50">
          <div class="flex gap-4">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <h3 class="text-lg font-bold text-blue-900 mb-2">Approval Workflow</h3>
              <div v-if="election.expected_voter_count > 40" class="space-y-2">
                <p class="text-blue-800 font-medium">
                  ⭐ PAID ELECTION ({{ election.expected_voter_count }} expected voters)
                </p>
                <p class="text-blue-700 text-sm leading-relaxed">
                  Elections with more than 40 expected voters require platform administrator review and approval.
                  This typically takes 1-5 business days. You will receive an email notification when approved or rejected.
                </p>
              </div>
              <div v-else class="space-y-2">
                <p class="text-blue-800 font-medium">
                  ✓ FREE ELECTION ({{ election.expected_voter_count }} expected voters)
                </p>
                <p class="text-blue-700 text-sm leading-relaxed">
                  Elections with 40 or fewer expected voters are automatically approved.
                  Your election will move to the setup phase immediately upon submission.
                </p>
              </div>
            </div>
          </div>
        </Card>

        <!-- Submission Prerequisites -->
        <Card mode="admin" padding="lg" class="rounded-2xl">
          <h2 class="text-lg font-bold text-slate-900 mb-4">Submission Requirements</h2>
          <p class="text-slate-700 mb-4 text-sm">Verify these two items before submitting for approval:</p>

          <div class="space-y-3">
            <!-- Election Name -->
            <div class="flex items-start gap-4 p-4 rounded-lg border-2 border-green-200 bg-green-50">
              <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <div class="flex-1">
                <p class="text-green-900 font-semibold">Election Name</p>
                <p class="text-green-700 text-sm">{{ election.name }}</p>
              </div>
            </div>

            <!-- Expected Voter Count -->
            <div class="flex items-start gap-4 p-4 rounded-lg border-2 transition-colors" :class="election.expected_voter_count > 0 ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'">
              <svg v-if="election.expected_voter_count > 0" class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              <svg v-else class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
              <div class="flex-1">
                <p :class="election.expected_voter_count > 0 ? 'text-green-900 font-semibold' : 'text-red-900 font-semibold'">
                  Expected Voter Count
                </p>
                <p :class="election.expected_voter_count > 0 ? 'text-green-700 text-sm' : 'text-red-700 text-sm'">
                  {{ election.expected_voter_count }} voter{{ election.expected_voter_count !== 1 ? 's' : '' }} expected
                  <span v-if="!isReady"> — At least 1 voter is required</span>
                </p>
              </div>
            </div>
          </div>

          <!-- Setup Timing Info -->
          <div class="mt-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
            <div class="flex gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-sm text-blue-700">
                <p class="font-semibold">Posts, candidates, and voters are configured during the <strong>administration phase</strong> — after this submission is approved.</p>
              </div>
            </div>
          </div>
        </Card>

        <!-- Actions -->
        <div class="flex gap-3 justify-end">
          <Link
            :href="route('organisations.elections.management', { organisation: organisation.slug, election: election.slug })"
            class="px-6 py-3 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 font-semibold transition-colors"
          >
            Back to Management
          </Link>
          <button
            v-if="isReady"
            @click="submitForApproval"
            :disabled="isLoading"
            class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold transition-colors disabled:cursor-not-allowed flex items-center gap-2"
          >
            <span v-if="isLoading" class="inline-block animate-spin">⟳</span>
            {{ isLoading ? 'Submitting...' : 'Submit for Approval' }}
          </button>
          <button
            v-else
            disabled
            class="px-6 py-3 rounded-lg bg-gray-300 text-gray-600 font-semibold cursor-not-allowed"
            :title="!isDraft ? 'Election already submitted for approval' : 'Expected voter count is required before submitting'"
          >
            {{ !isDraft ? 'Already Submitted' : 'Set Expected Voter Count First' }}
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

// Redirect if election is not in draft state (already submitted)
watch(() => props.election?.state, (state) => {
  if (state && state !== 'draft') {
    router.visit(route('organisations.elections.management', {
      organisation: props.organisation.slug,
      election: props.election.slug
    }))
  }
}, { immediate: true })

const isLoading = ref(false)

const isDraft = computed(() => props.election?.state === 'draft')

const hasVoterCount = computed(() => props.election?.expected_voter_count > 0)

const isReady = computed(() => isDraft.value && hasVoterCount.value)

const submitForApproval = () => {
  if (!isReady.value) return

  isLoading.value = true
  router.post(
    route('elections.submit-for-approval', { election: props.election.slug }),
    {},
    {
      onSuccess: () => {
        router.visit(route('organisations.elections.management', { organisation: props.organisation.slug, election: props.election.slug }))
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
