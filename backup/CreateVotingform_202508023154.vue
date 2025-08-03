<template>
  <div id="all_vote_window">
    <div
      v-for="post in posts"
      :key="post.post_id"
      class="mb-8 border border-blue-300 rounded-xl shadow bg-white"
    >
      <!-- Post title and instruction -->
      <div class="text-xl font-bold text-center text-gray-900 my-2">
        <span>{{ post.name }}</span>
        <span class="ml-2 text-gray-500 text-base">({{ post.nepali_name }})</span>
      </div>
      <div class="text-center mb-2">
        <span>
          Please choose <span class="font-bold">{{ post.required_number }}</span>
          candidate(s) for this post.
        </span>
        <br>
        <span>
          कृपया <span class="font-bold">{{ post.required_number }}</span>
          जना लाई <span class="font-bold">{{ post.nepali_name }}</span> चुन्नुहोस्।
        </span>
      </div>

      <!-- Candidate List -->
      <div class="md:flex md:flex-wrap md:justify-between md:px-4 py-4">
        <div
          v-for="candidate in sortedCandidates(post.candidates)"
          :key="candidate.candidacy_id"
          class="flex flex-col justify-center p-4 mb-2 text-center border border-gray-100 rounded"
        >
          <ShowCandidate
            :candidacy_image_path="getCandidateImagePath(candidate.image_path_1)"
            :post_name="post.name"
            :post_nepali_name="post.nepali_name"
            :candidacy_name="candidate.user.name"
          />

          <!-- Voting checkbox -->
          <div class="px-2 py-2">
            <input
              type="checkbox"
              :id="candidate.candidacy_id"
              :name="post.name"
              :value="candidate.candidacy_id"
              class="p-6 rounded border-gray-900 border-2 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              v-model="selected[post.post_id]"
              @change="() => handleSelectionChange(post)"
              :disabled="candidate.disabled"
            />
          </div>
        </div>
      </div>

      <!-- Summary of selected candidates -->
      <div class="mb-4 p-2 text-center mx-auto" v-if="selected[post.post_id]?.length">
        You have selected
        <span class="font-bold text-indigo-600">
          {{ findSelectedNames(post) }}
        </span>
        as <span class="font-bold text-lg text-gray-900">{{ post.name }}</span>
        ({{ post.nepali_name }}) of NRNA!
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import ShowCandidate from '@/Shared/ShowCandidate.vue'

// Props: Posts (categories) structure from backend
const props = defineProps({
  posts: { type: Array, default: () => [] }
})

// Track selection per post
const selected = ref({})

// --------- Helper Functions ---------

// Return `/storage/images/filename.jpg`
function getCandidateImagePath(imageFile) {
  // Adjust this if you store full paths in DB
  return imageFile ? `/storage/images/${imageFile}` : '/images/placeholder.png'
}

// Sort candidates alphabetically by user name
function sortedCandidates(candidates) {
  return [...candidates].sort((a, b) => {
    const nameA = a.user?.name?.toLowerCase() || ''
    const nameB = b.user?.name?.toLowerCase() || ''
    return nameA.localeCompare(nameB) || a.candidacy_id.localeCompare(b.candidacy_id)
  })
}

// Limit selection per post
function handleSelectionChange(post) {
  const sel = selected.value[post.post_id] || []
  const limit = post.required_number
  post.candidates.forEach(c => { c.disabled = false })
  if (sel.length >= limit) {
    post.candidates.forEach(c => {
      if (!sel.includes(c.candidacy_id)) c.disabled = true
    })
  }
}

// Get a display string of selected candidate names for a post
function findSelectedNames(post) {
  const sel = selected.value[post.post_id] || []
  return post.candidates
    .filter(c => sel.includes(c.candidacy_id))
    .map(c => c.user.name)
    .join(', ')
}

// --------- Initialize empty arrays for each post ---------
props.posts.forEach(post => {
  if (!selected.value[post.post_id]) selected.value[post.post_id] = []
})
</script>
