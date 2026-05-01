<template>
  <ElectionLayout>
    <div class="container mx-auto py-8 px-4">
      <!-- Header -->
      <div class="mb-6">
        <Link
          :href="route('elections.management', election.slug)"
          class="text-primary-600 hover:text-primary-800 inline-flex items-center gap-1 transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Management
        </Link>
        <div class="flex justify-between items-center mt-4">
          <div>
            <h1 class="text-2xl font-bold text-slate-800">Election Timeline</h1>
            <p class="text-slate-500">View all phase dates for {{ election.name }}</p>
          </div>
          <Link
            :href="route('elections.timeline', election.slug)"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 flex items-center gap-2 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
            Edit Timeline
          </Link>
        </div>
      </div>

      <!-- Timeline Cards (Read-Only) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Administration Phase -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-4 bg-primary-50 border-b border-primary-100">
            <div class="flex items-center gap-2">
              <span class="text-2xl">⚙️</span>
              <h2 class="font-semibold text-slate-800">Administration Phase</h2>
            </div>
          </div>
          <div class="p-4 space-y-3">
            <div class="flex justify-between">
              <span class="text-slate-500">Start Date:</span>
              <span class="font-medium">{{ formatDate(election.administration_suggested_start) || 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">End Date:</span>
              <span class="font-medium">{{ formatDate(election.administration_suggested_end) || 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Status:</span>
              <span :class="administrationCompleted ? 'text-green-600' : 'text-yellow-600'">
                {{ administrationCompleted ? 'Completed' : 'Pending' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Nomination Phase -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-4 bg-green-50 border-b border-green-100">
            <div class="flex items-center gap-2">
              <span class="text-2xl">📋</span>
              <h2 class="font-semibold text-slate-800">Nomination Phase</h2>
            </div>
          </div>
          <div class="p-4 space-y-3">
            <div class="flex justify-between">
              <span class="text-slate-500">Start Date:</span>
              <span class="font-medium">{{ formatDate(election.nomination_suggested_start) || 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">End Date:</span>
              <span class="font-medium">{{ formatDate(election.nomination_suggested_end) || 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Status:</span>
              <span :class="nominationCompleted ? 'text-green-600' : 'text-yellow-600'">
                {{ nominationCompleted ? 'Completed' : 'Pending' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Voting Period -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-4 bg-purple-50 border-b border-purple-100">
            <div class="flex items-center gap-2">
              <span class="text-2xl">🗳️</span>
              <h2 class="font-semibold text-slate-800">Voting Period</h2>
            </div>
          </div>
          <div class="p-4 space-y-3">
            <div class="flex justify-between">
              <span class="text-slate-500">Start Date:</span>
              <span class="font-medium">{{ formatDate(election.voting_starts_at) || 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">End Date:</span>
              <span class="font-medium">{{ formatDate(election.voting_ends_at) || 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Status:</span>
              <span :class="isVotingActive ? 'text-green-600' : 'text-slate-500'">
                {{ isVotingActive ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Results Publication -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-4 bg-orange-50 border-b border-orange-100">
            <div class="flex items-center gap-2">
              <span class="text-2xl">📊</span>
              <h2 class="font-semibold text-slate-800">Results Publication</h2>
            </div>
          </div>
          <div class="p-4 space-y-3">
            <div class="flex justify-between">
              <span class="text-slate-500">Publication Date:</span>
              <span class="font-medium">{{ formatDate(election.results_published_at) || 'Not published' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Status:</span>
              <span :class="election.results_published ? 'text-green-600' : 'text-slate-500'">
                {{ election.results_published ? 'Published' : 'Not Published' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Timeline Progress Bar -->
      <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Overall Progress</h3>
        <div class="relative h-4 bg-slate-100 rounded-full overflow-hidden">
          <div
            class="absolute left-0 top-0 h-full bg-gradient-to-r from-blue-500 via-green-500 via-purple-500 to-orange-500 transition-all duration-500"
            :style="{ width: progressPercentage + '%' }"
          ></div>
        </div>
        <div class="flex justify-between mt-3 text-sm text-slate-500">
          <span>Administration</span>
          <span>Nomination</span>
          <span>Voting</span>
          <span>Results</span>
        </div>
      </div>
    </div>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  election: Object,
})

const formatDate = (date) => {
  if (!date) return null
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const administrationCompleted = computed(() => props.election.administration_completed)
const nominationCompleted = computed(() => props.election.nomination_completed)
const isVotingActive = computed(() => {
  const now = new Date()
  const start = props.election.voting_starts_at ? new Date(props.election.voting_starts_at) : null
  const end = props.election.voting_ends_at ? new Date(props.election.voting_ends_at) : null
  return start && end && now >= start && now <= end
})

const progressPercentage = computed(() => {
  let completed = 0
  if (props.election.administration_completed) completed++
  if (props.election.nomination_completed) completed++
  if (isVotingActive.value || (props.election.voting_ends_at && new Date() > new Date(props.election.voting_ends_at))) completed++
  if (props.election.results_published) completed++
  return (completed / 4) * 100
})
</script>

