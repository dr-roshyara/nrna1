<template>
  <div class="min-h-screen bg-gradient-to-b from-green-50 to-emerald-50 py-12 flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Success Card -->
      <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-12 text-center">
          <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
              <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>
          <h1 class="text-3xl font-bold text-white">
            Thank You for Voting!
          </h1>
          <p class="text-green-100 mt-2">
            Your vote has been recorded successfully
          </p>
        </div>

        <!-- Content -->
        <div class="p-8 space-y-6">
          <!-- Election Info -->
          <div class="bg-green-50 border-l-4 border-green-500 p-4">
            <p class="text-green-900 font-semibold">
              {{ election_name }}
            </p>
            <p class="text-green-700 text-sm mt-1">
              Total votes recorded: <strong>{{ votes_count }}</strong>
            </p>
          </div>

          <!-- Demo Mode Notice -->
          <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-900 font-semibold">
              🎮 Demo Election Mode
            </p>
            <p class="text-yellow-800 text-sm mt-1">
              This is a test election. You can vote again for testing purposes.
            </p>
          </div>

          <!-- Vote Summary -->
          <div class="border-t border-gray-200 pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
              Your Votes
            </h2>
            <div class="space-y-3">
              <div
                v-for="(vote, index) in votes"
                :key="index"
                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
              >
                <div>
                  <p class="text-sm text-gray-600">{{ vote.post_name }}</p>
                  <p class="font-semibold text-gray-900">{{ vote.candidate_name }}</p>
                </div>
                <div class="text-xs text-gray-500">
                  {{ vote.submitted_at }}
                </div>
              </div>
            </div>
          </div>

          <!-- Important Info -->
          <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
            <p class="text-blue-900 font-semibold mb-2">
              📋 What Happens Next?
            </p>
            <ul class="space-y-1 text-blue-800 text-sm">
              <li>✓ Your votes are now part of the election records</li>
              <li>✓ Results will be announced after the voting closes</li>
              <li>✓ You will receive a notification when results are available</li>
              <li>✓ All votes are recorded anonymously and securely</li>
            </ul>
          </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-8 py-6 bg-gray-50">
          <button
            @click="goToDashboard"
            class="w-full px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition"
          >
            Return to Dashboard
          </button>
          <button
            @click="voteAgain"
            class="w-full mt-2 px-6 py-2 border border-green-600 text-green-600 hover:bg-green-50 font-bold rounded-lg transition"
          >
            Vote Again (Demo Testing)
          </button>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="mt-8 text-center text-sm text-gray-600">
        <p>
          💡 In real voting, you would only be able to vote once per election.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  name: String,
  election_name: String,
  votes_count: Number,
  votes: Array,
  slug: String,
  useSlugPath: Boolean,
})

const goToDashboard = () => {
  router.get(route('dashboard'))
}

const voteAgain = () => {
  router.get(
    route(props.useSlugPath ? 'slug.demo-vote.create' : 'demo-vote.create',
          props.useSlugPath ? { vslug: props.slug } : {})
  )
}
</script>
