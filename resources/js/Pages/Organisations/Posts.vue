<template>
  <ElectionLayout>
    <main class="min-h-screen bg-neutral-50 py-8">
      <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb -->
        <nav class="text-sm text-neutral-500 flex items-center gap-2" aria-label="Breadcrumb">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-neutral-700 transition-colors">{{ organisation.name }}</a>
          <span aria-hidden="true">/</span>
          <span class="hover:text-neutral-700">{{ election.name }}</span>
          <span aria-hidden="true">/</span>
          <span class="text-neutral-700 font-medium">Positions</span>
        </nav>

        <!-- Header -->
        <SectionCard padding="lg">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">{{ election.name }}</p>
              <h1 class="text-2xl font-bold text-slate-900">Positions</h1>
              <p class="text-sm text-slate-500 mt-0.5">
                {{ posts.length }} position{{ posts.length !== 1 ? 's' : '' }} in this election
              </p>
            </div>
          </div>
        </SectionCard>

        <!-- Empty state -->
        <EmptyState
          v-if="posts.length === 0"
          title="No positions defined"
          description="No election positions have been added yet."
        >
          <template #icon>
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </template>
        </EmptyState>

        <!-- Posts list -->
        <div v-else class="space-y-3">
          <Card v-for="post in posts" :key="post.id" padding="none">
            <div class="px-5 py-4 flex items-center gap-4">

              <div
                class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                :class="post.is_national_wide ? 'bg-primary-100' : 'bg-amber-100'"
              >
                <svg
                  class="w-5 h-5"
                  :class="post.is_national_wide ? 'text-primary-600' : 'text-amber-600'"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                >
                  <path v-if="post.is_national_wide" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                  <h3 class="font-semibold text-slate-900">{{ post.name }}</h3>
                  <span v-if="post.nepali_name" class="text-sm text-slate-500">/ {{ post.nepali_name }}</span>
                </div>
                <div class="flex items-center gap-3 mt-1 flex-wrap">
                  <span
                    class="text-xs px-2 py-0.5 rounded-full font-medium"
                    :class="post.is_national_wide ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700'"
                  >
                    {{ post.is_national_wide ? 'National' : post.state_name }}
                  </span>
                  <span class="text-xs text-slate-500">
                    {{ post.required_number }} seat{{ post.required_number !== 1 ? 's' : '' }}
                  </span>
                </div>
              </div>

            </div>
          </Card>
        </div>

        <!-- Apply CTA -->
        <div v-if="election.status === 'active'" class="pt-2">
          <a
            :href="route('organisations.candidacy.create', organisation.slug)"
            class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded-lg px-5 py-3 hover:bg-primary-100 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Apply for a position
          </a>
        </div>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import SectionCard from '@/Components/SectionCard.vue'
import Card from '@/Components/Card.vue'
import EmptyState from '@/Components/EmptyState.vue'

defineProps({
  organisation: { type: Object, required: true },
  election:     { type: Object, required: true },
  posts:        { type: Array,  default: () => [] },
})
</script>

