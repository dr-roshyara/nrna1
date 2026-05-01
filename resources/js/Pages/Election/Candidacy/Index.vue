<template>
  <ElectionLayout>
    <main class="min-h-screen bg-neutral-50 py-8">
      <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb -->
        <nav class="text-sm text-neutral-500 flex items-center gap-2" aria-label="Breadcrumb">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-neutral-700 transition-colors">{{ organisation.name }}</a>
          <span aria-hidden="true">/</span>
          <a :href="route('elections.management', election.slug)" class="hover:text-neutral-700 transition-colors">{{ election.name }}</a>
          <span aria-hidden="true">/</span>
          <span class="text-neutral-700 font-medium">Candidates</span>
        </nav>

        <!-- Header -->
        <SectionCard padding="lg">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Election Management</p>
              <h1 class="text-2xl font-bold text-slate-900">Candidates</h1>
              <p class="text-sm text-slate-500 mt-0.5">
                {{ totalCandidates }} candidate{{ totalCandidates !== 1 ? 's' : '' }} across {{ posts.length }} position{{ posts.length !== 1 ? 's' : '' }}
              </p>
            </div>
            <a
              :href="route('organisations.elections.candidacy.applications', { organisation: organisation.slug, election: election.slug })"
              class="inline-flex items-center gap-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-2 hover:bg-amber-100 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Review Applications
            </a>
          </div>
        </SectionCard>

        <!-- Flash -->
        <div v-if="page.props.flash?.success" role="alert"
          class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4">
          <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-emerald-800">{{ page.props.flash.success }}</p>
        </div>

        <!-- Empty state -->
        <EmptyState
          v-if="posts.length === 0"
          title="No positions defined"
          description="Add positions first from the Posts page, then candidates can be added here."
        >
          <template #icon>
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </template>
        </EmptyState>

        <!-- Posts with candidates -->
        <Card
          v-for="post in posts"
          :key="post.id"
          mode="admin"
          padding="none"
          class="overflow-hidden"
        >
          <!-- Post header -->
          <div class="px-6 py-4 bg-slate-50 border-b border-neutral-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div>
                <h2 class="font-semibold text-slate-900">{{ post.name }}</h2>
                <div class="flex items-center gap-2 mt-1">
                  <span
                    class="text-xs px-2 py-0.5 rounded-full font-medium"
                    :class="post.is_national_wide ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700'"
                  >
                    {{ post.is_national_wide ? 'National' : post.state_name }}
                  </span>
                  <span class="text-xs text-slate-500">{{ post.required_number }} seat{{ post.required_number !== 1 ? 's' : '' }}</span>
                  <span class="text-xs text-slate-400">·</span>
                  <span class="text-xs text-slate-500">{{ post.candidacies.length }} candidate{{ post.candidacies.length !== 1 ? 's' : '' }}</span>
                </div>
              </div>
            </div>
            <!-- Status summary pills -->
            <div class="flex items-center gap-1.5">
              <span v-if="countByStatus(post, 'draft') > 0"
                class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-medium">
                {{ countByStatus(post, 'draft') }} draft
              </span>
              <span v-if="countByStatus(post, 'approved') > 0"
                class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-medium">
                {{ countByStatus(post, 'approved') }} approved
              </span>
            </div>
          </div>

          <!-- Empty post -->
          <div v-if="post.candidacies.length === 0" class="px-6 py-5 text-sm text-slate-400 italic">
            No candidates for this position yet.
          </div>

          <!-- Candidate rows -->
          <div v-else class="divide-y divide-neutral-100">
            <div
              v-for="candidate in post.candidacies"
              :key="candidate.id"
              class="px-6 py-4 flex items-center gap-4"
              :class="candidate.status === 'draft' ? 'bg-orange-50/40' : ''"
            >
              <!-- Photo -->
              <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0 border border-neutral-200 bg-slate-100">
                <img
                  v-if="candidate.image_path_1"
                  :src="`/storage/${candidate.image_path_1}`"
                  :alt="candidate.name"
                  class="w-full h-full object-cover"
                />
                <span v-else class="w-full h-full flex items-center justify-center text-sm font-bold text-slate-400">
                  {{ (candidate.name || '?')[0].toUpperCase() }}
                </span>
              </div>

              <!-- Name + meta -->
              <div class="flex-1 min-w-0">
                <p class="font-medium text-slate-900 truncate">{{ candidate.name }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span
                    class="text-xs font-semibold px-1.5 py-0.5 rounded"
                    :class="statusClass(candidate.status)"
                  >
                    {{ candidate.status }}
                  </span>
                  <span v-if="candidate.from_application" class="text-xs text-amber-600">
                    · from application
                  </span>
                </div>
              </div>

              <!-- Publish button (draft only) -->
              <div class="flex items-center gap-2 flex-shrink-0">
                <ActionButton
                  v-if="candidate.status === 'draft'"
                  variant="success"
                  size="sm"
                  :loading="publishing === candidate.id"
                  @click="publish(post, candidate)"
                >
                  Publish
                </ActionButton>
                <ActionButton
                  v-if="candidate.status === 'approved'"
                  variant="outline"
                  size="sm"
                  @click="unpublish(post, candidate)"
                  :loading="publishing === candidate.id"
                >
                  Unpublish
                </ActionButton>
                <ActionButton
                  variant="danger"
                  size="sm"
                  @click="remove(post, candidate)"
                >
                  Remove
                </ActionButton>
              </div>
            </div>
          </div>
        </Card>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import SectionCard from '@/Components/SectionCard.vue'
import Card from '@/Components/Card.vue'
import ActionButton from '@/Components/ActionButton.vue'
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  election:     { type: Object, required: true },
  posts:        { type: Array,  default: () => [] },
})

const page      = usePage()
const publishing = ref(null)

const totalCandidates = computed(() =>
  props.posts.reduce((sum, p) => sum + p.candidacies.length, 0)
)

function countByStatus(post, status) {
  return post.candidacies.filter(c => c.status === status).length
}

function statusClass(status) {
  return {
    draft:     'bg-orange-100 text-orange-700',
    approved:  'bg-emerald-100 text-emerald-700',
    pending:   'bg-primary-100 text-primary-700',
    rejected:  'bg-danger-100 text-danger-700',
    withdrawn: 'bg-slate-100 text-slate-500',
  }[status] ?? 'bg-slate-100 text-slate-500'
}

function updateUrl(post, candidate) {
  return route('organisations.elections.candidacies.update', {
    organisation: props.organisation.slug,
    election:     props.election.slug,
    post:         post.id,
    candidacy:    candidate.id,
  })
}

function publish(post, candidate) {
  publishing.value = candidate.id
  router.patch(updateUrl(post, candidate), { status: 'approved' }, {
    preserveScroll: true,
    onFinish: () => { publishing.value = null },
  })
}

function unpublish(post, candidate) {
  publishing.value = candidate.id
  router.patch(updateUrl(post, candidate), { status: 'draft' }, {
    preserveScroll: true,
    onFinish: () => { publishing.value = null },
  })
}

function remove(post, candidate) {
  if (!confirm(`Remove "${candidate.name}" from ${post.name}? This cannot be undone.`)) return
  router.delete(
    route('organisations.elections.candidacies.destroy', {
      organisation: props.organisation.slug,
      election:     props.election.slug,
      post:         post.id,
      candidacy:    candidate.id,
    }),
    { preserveScroll: true }
  )
}
</script>

