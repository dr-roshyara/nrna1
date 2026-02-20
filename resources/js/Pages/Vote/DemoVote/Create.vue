<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ election_name }}
        </h1>
        <p class="mt-2 text-gray-600">
          <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
            🎮 DEMO ELECTION - Testing Mode
          </span>
        </p>
      </div>

      <!-- Instructions -->
      <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8">
        <h2 class="text-lg font-semibold text-blue-900">How to Vote</h2>
        <ol class="mt-2 space-y-1 text-blue-800 text-sm">
          <li>✓ Select your preferred candidate for each post below</li>
          <li>✓ Review your selections on the next page</li>
          <li>✓ Confirm your agreement to vote</li>
          <li>✓ Verify your votes one final time</li>
          <li>✓ Submit your votes</li>
        </ol>
      </div>

      <!-- Posts and Candidates -->
      <form @submit.prevent="submitVotes" class="space-y-6">
        <div v-for="post in posts" :key="post.id" class="bg-white rounded-lg shadow-md overflow-hidden">
          <!-- Post Header -->
          <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">
              {{ post.name }}
            </h3>
            <p v-if="post.nepali_name" class="text-indigo-100 text-sm">
              {{ post.nepali_name }}
            </p>
            <p class="text-indigo-200 text-sm mt-1">
              Select {{ post.required_number }} candidate(s)
            </p>
          </div>

          <!-- Candidates Grid -->
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="candidate in post.candidates"
                :key="candidate.id"
                class="border-2 rounded-lg p-4 cursor-pointer transition-all"
                :class="isSelected(post.id, candidate.id)
                  ? 'border-indigo-600 bg-indigo-50'
                  : 'border-gray-200 bg-white hover:border-indigo-300'"
                @click="toggleCandidate(post.id, candidate.id)"
              >
                <!-- Candidate Image -->
                <div class="mb-3 h-32 bg-gray-100 rounded-md overflow-hidden">
                  <img
                    v-if="candidate.image_path"
                    :src="candidate.image_path"
                    :alt="candidate.name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="flex items-center justify-center h-full bg-gray-200">
                    <span class="text-gray-400 text-sm">No Image</span>
                  </div>
                </div>

                <!-- Candidate Info -->
                <div class="text-sm">
                  <p class="font-bold text-gray-900">{{ candidate.name }}</p>
                  <p class="text-gray-600">{{ candidate.user_name }}</p>
                  <p v-if="candidate.proposer_name" class="text-xs text-gray-500 mt-1">
                    Proposer: {{ candidate.proposer_name }}
                  </p>
                </div>

                <!-- Selection Indicator -->
                <div v-if="isSelected(post.id, candidate.id)" class="mt-3 flex items-center text-indigo-600">
                  <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  <span class="text-sm font-semibold">Selected</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Errors -->
        <div v-if="errors.votes" class="bg-red-50 border border-red-200 rounded-lg p-4">
          <p class="text-red-800 font-semibold">{{ errors.votes }}</p>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-4 justify-center mt-8">
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition disabled:opacity-50"
          >
            <span v-if="loading" class="inline-block mr-2">⏳</span>
            {{ loading ? 'Processing...' : 'Continue to Review' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'

const props = defineProps({
  posts: Array,
  election_name: String,
  election_id: Number,
  slug: String,
  useSlugPath: Boolean,
})

const loading = ref(false)
const selectedVotes = ref({})
const errors = ref({})

const isSelected = (postId, candidateId) => {
  return selectedVotes.value[postId]?.candidate_id === candidateId
}

const toggleCandidate = (postId, candidateId) => {
  if (isSelected(postId, candidateId)) {
    delete selectedVotes.value[postId]
  } else {
    selectedVotes.value[postId] = { candidate_id: candidateId }
  }
}

const submitVotes = async () => {
  errors.value = {}

  // Validate at least one vote
  if (Object.keys(selectedVotes.value).length === 0) {
    errors.value.votes = 'Please select at least one candidate'
    return
  }

  loading.value = true

  const form = useForm({
    votes: selectedVotes.value,
  })

  const routeName = props.useSlugPath ? 'slug.demo-vote.submit' : 'demo-vote.submit'
  const params = props.useSlugPath ? { vslug: props.slug } : {}

  form.post(route(routeName, params), {
    onError: (errors) => {
      if (errors.votes) {
        errors.value.votes = errors.votes
      }
      loading.value = false
    },
  })
}
</script>
