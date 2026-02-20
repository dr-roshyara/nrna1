<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
          Verify Your Vote
        </h1>
        <p class="mt-2 text-gray-600">
          Please review your selections before final submission
        </p>
        <p class="mt-1 text-yellow-700 bg-yellow-50 px-4 py-2 rounded inline-block">
          🎮 DEMO MODE
        </p>
      </div>

      <!-- Verification Summary -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
          <h2 class="text-2xl font-bold text-white">
            {{ election_name }}
          </h2>
          <p class="text-indigo-100 text-sm mt-1">
            Total Votes: <strong>{{ total_votes }}</strong>
          </p>
        </div>

        <div class="divide-y">
          <div
            v-for="(vote, index) in selected_votes"
            :key="index"
            class="p-6 hover:bg-indigo-50 transition"
          >
            <div class="flex gap-6 items-center">
              <!-- Candidate Image -->
              <div class="flex-shrink-0">
                <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100">
                  <img
                    v-if="vote.candidate_image"
                    :src="vote.candidate_image"
                    :alt="vote.candidate_name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="flex items-center justify-center h-full">
                    <span class="text-gray-400 text-xs">No Image</span>
                  </div>
                </div>
              </div>

              <!-- Vote Info -->
              <div class="flex-grow">
                <p class="text-sm text-gray-600 mb-1">Position:</p>
                <p class="text-xl font-bold text-gray-900 mb-3">
                  {{ vote.post_name }}
                </p>
                <p class="text-sm text-gray-600 mb-1">Candidate:</p>
                <p class="text-lg font-semibold text-indigo-600">
                  {{ vote.candidate_name }}
                </p>
              </div>

              <!-- Checkmark -->
              <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Warnings -->
      <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8">
        <p class="text-amber-900 font-semibold">⚠️ Important Reminder</p>
        <ul class="mt-2 space-y-1 text-amber-800 text-sm">
          <li>✓ Once you submit, your vote CANNOT be changed</li>
          <li>✓ Your vote will be recorded permanently</li>
          <li>✓ You will receive a confirmation code</li>
        </ul>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <button
          @click="goBack"
          class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition order-2 sm:order-1"
        >
          ← Change My Votes
        </button>
        <button
          @click="submitVotes"
          :disabled="loading"
          class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed order-1 sm:order-2"
        >
          <span v-if="loading" class="inline-block mr-2">⏳</span>
          {{ loading ? 'Submitting...' : 'Submit My Vote' }}
        </button>
      </div>

      <!-- Info -->
      <div class="mt-8 text-center text-sm text-gray-600">
        <p>
          Your vote will be submitted securely and anonymously
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  election_name: String,
  selected_votes: Array,
  total_votes: Number,
  slug: String,
  useSlugPath: Boolean,
})

const loading = ref(false)

const goBack = () => {
  router.get(
    route(props.useSlugPath ? 'slug.demo-vote.create' : 'demo-vote.create',
          props.useSlugPath ? { vslug: props.slug } : {})
  )
}

const submitVotes = () => {
  loading.value = true

  const form = useForm({
    confirmed: true,
  })

  const routeName = props.useSlugPath ? 'slug.demo-vote.store' : 'demo-vote.store'
  const params = props.useSlugPath ? { vslug: props.slug } : {}

  form.post(route(routeName, params), {
    onError: () => {
      loading.value = false
    },
  })
}
</script>
