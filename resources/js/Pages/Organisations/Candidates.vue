<script setup>
import { computed } from 'vue'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  organisation: Object,
  election: Object,
  posts: Array,
})

const backUrl = computed(() => route('organisations.voter-hub', props.organisation.slug))
</script>

<template>
  <ElectionLayout>
    <div class="min-h-screen bg-slate-50 p-4 sm:p-6 lg:p-8">
      <!-- Header -->
      <div class="max-w-6xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
          <div>
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4">
              <a :href="backUrl" class="hover:text-slate-700">{{ organisation.name }}</a>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span>{{ election.name }}</span>
            </nav>
            <h1 class="text-4xl font-black text-slate-900">Candidates & Positions</h1>
            <p class="text-lg text-slate-600 mt-2">View all positions and running candidates</p>
          </div>
          <a
            :href="backUrl"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
          </a>
        </div>

        <!-- Empty state -->
        <div v-if="posts.length === 0" class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
          <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          <p class="text-slate-600 font-semibold">No candidates yet</p>
          <p class="text-slate-500 text-sm mt-1">Candidates will appear here once they register</p>
        </div>

        <!-- Posts and Candidates Grid -->
        <div v-else class="space-y-10">
          <div v-for="post in posts" :key="post.id" class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <!-- Post Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-6 md:px-8 text-white">
              <div class="flex items-start justify-between gap-4 mb-3">
                <div>
                  <h2 class="text-2xl md:text-3xl font-black">{{ post.name }}</h2>
                  <p v-if="post.nepali_name" class="text-primary-200 mt-1">{{ post.nepali_name }}</p>
                </div>
                <div class="flex flex-wrap gap-2 justify-end">
                  <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                    {{ post.required_number }} {{ post.required_number === 1 ? 'seat' : 'seats' }}
                  </span>
                  <span v-if="post.is_national_wide" class="inline-block px-3 py-1 bg-primary-400/30 backdrop-blur rounded-full text-sm font-semibold">
                    National
                  </span>
                  <span v-else-if="post.state_name" class="inline-block px-3 py-1 bg-amber-400/30 backdrop-blur rounded-full text-sm font-semibold">
                    {{ post.state_name }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Candidates Grid -->
            <div class="p-6 md:p-8">
              <div v-if="post.candidacies.length === 0" class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-slate-600 font-medium">No candidates registered yet</p>
              </div>

              <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                  v-for="candidate in post.candidacies"
                  :key="candidate.id"
                  class="group rounded-xl border border-slate-200 overflow-hidden hover:border-primary-400 hover:shadow-md transition-all"
                >
                  <!-- Image -->
                  <div class="h-48 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden flex items-center justify-center">
                    <img
                      v-if="candidate.image_path_1"
                      :src="`/storage/${candidate.image_path_1}`"
                      :alt="candidate.name"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                    />
                    <svg v-else class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>

                  <!-- Info -->
                  <div class="p-4">
                    <h3 class="font-bold text-slate-900 text-lg truncate">{{ candidate.name }}</h3>
                    <p v-if="candidate.description" class="text-slate-600 text-sm mt-2 line-clamp-3">
                      {{ candidate.description }}
                    </p>
                    <div v-else class="text-slate-400 text-sm mt-2 italic">No description provided</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </ElectionLayout>
</template>

