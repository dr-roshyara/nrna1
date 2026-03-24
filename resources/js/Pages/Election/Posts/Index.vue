<template>
  <ElectionLayout>
    <main class="min-h-screen bg-neutral-50 py-8">
      <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb -->
        <nav class="text-sm text-neutral-500 flex items-center gap-2" aria-label="Breadcrumb">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-neutral-700 transition-colors">
            {{ organisation.name }}
          </a>
          <span aria-hidden="true">/</span>
          <a :href="route('elections.management', election.slug)" class="hover:text-neutral-700 transition-colors">
            {{ election.name }}
          </a>
          <span aria-hidden="true">/</span>
          <span class="text-neutral-700 font-medium">Posts &amp; Candidates</span>
        </nav>

        <!-- Page Header -->
        <SectionCard padding="lg">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Election Management</p>
              <h1 class="text-2xl font-bold text-slate-900">Posts &amp; Candidates</h1>
              <p class="text-sm text-slate-500 mt-0.5">
                {{ posts.length }} position{{ posts.length !== 1 ? 's' : '' }} ·
                {{ totalCandidates }} candidate{{ totalCandidates !== 1 ? 's' : '' }}
              </p>
            </div>
            <ActionButton variant="primary" size="md" @click="openAddPost">
              + Add Position
            </ActionButton>
          </div>
        </SectionCard>

        <!-- Flash Messages -->
        <div
          v-if="page.props.flash?.success"
          role="alert"
          class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-emerald-800">{{ page.props.flash.success }}</p>
        </div>
        <div
          v-if="page.props.flash?.error"
          role="alert"
          class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-red-800">{{ page.props.flash.error }}</p>
        </div>

        <!-- Add Post Form -->
        <Card v-if="showAddPost" mode="admin" padding="lg" class="border-2 !border-primary-200">
          <h2 class="font-semibold text-slate-900 mb-4">New Position</h2>
          <PostForm
            :form="addPostForm"
            :is-submitting="isAddingPost"
            :errors="activeFormErrors('addPost')"
            @submit="submitAddPost"
            @cancel="closeAddPost"
          />
        </Card>

        <!-- Empty State -->
        <EmptyState
          v-if="posts.length === 0"
          title="No positions yet"
          description="Add positions (President, Vice President, etc.) for this election."
        >
          <template #icon>
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </template>
        </EmptyState>

        <!-- Posts List -->
        <Card
          v-for="post in posts"
          :key="post.id"
          mode="admin"
          padding="none"
          class="overflow-hidden"
        >
          <!-- Post Header -->
          <div class="px-6 py-4 flex items-start justify-between bg-slate-50 border-b border-neutral-200">
            <div>
              <div class="flex items-center gap-3">
                <span class="text-xs font-mono text-neutral-400 w-5 text-right" aria-hidden="true">{{ post.position_order ?? '—' }}</span>
                <div>
                  <h3 class="font-semibold text-slate-900">{{ post.name }}</h3>
                  <p v-if="post.nepali_name" class="text-sm text-slate-500">{{ post.nepali_name }}</p>
                </div>
              </div>
              <div class="flex flex-wrap items-center gap-2 mt-2 ml-8">
                <span
                  class="text-xs px-2 py-0.5 rounded-full font-medium"
                  :class="post.is_national_wide ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'"
                >
                  {{ post.is_national_wide ? 'National' : post.state_name }}
                </span>
                <span class="text-xs text-slate-500">Select {{ post.required_number }}</span>
                <span class="text-xs text-slate-400">{{ post.candidacies.length }} candidate(s)</span>
              </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
              <ActionButton
                variant="outline"
                size="sm"
                :aria-label="`Edit ${post.name}`"
                @click="startEditPost(post)"
              >Edit</ActionButton>
              <ActionButton
                variant="danger"
                size="sm"
                :aria-label="`Delete ${post.name}`"
                @click="deletePost(post)"
              >Delete</ActionButton>
            </div>
          </div>

          <!-- Edit Post Form -->
          <div v-if="editingPostId === post.id" class="px-6 py-4 bg-primary-50 border-b border-primary-100">
            <PostForm
              :form="editPostForm"
              :is-submitting="isEditingPost"
              :errors="activeFormErrors('editPost')"
              edit-mode
              @submit="submitEditPost(post)"
              @cancel="editingPostId = null"
            />
          </div>

          <!-- Candidates Section -->
          <div class="px-6 py-4">
            <p v-if="post.candidacies.length === 0" class="text-sm text-neutral-400 italic mb-3">No candidates yet.</p>

            <div
              v-for="(cand, idx) in post.candidacies"
              :key="cand.id"
              class="flex items-start justify-between py-3 border-b border-neutral-100 last:border-0"
            >
              <div class="flex items-start gap-3">
                <!-- Photo thumbnail -->
                <img
                  v-if="cand.image_path_1"
                  :src="`/storage/${cand.image_path_1}`"
                  :alt="`Photo of ${cand.name}`"
                  class="w-10 h-10 rounded-lg object-cover border border-neutral-200 flex-shrink-0"
                />
                <div
                  v-else
                  class="w-10 h-10 rounded-lg bg-neutral-100 flex items-center justify-center flex-shrink-0"
                  aria-hidden="true"
                >
                  <svg class="w-5 h-5 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="text-xs font-mono text-neutral-300 w-4 text-right" aria-hidden="true">{{ idx + 1 }}</span>
                    <p class="text-sm font-medium text-slate-800">{{ cand.name }}</p>
                  </div>
                  <p v-if="cand.description" class="text-xs text-neutral-500 mt-0.5 line-clamp-1 ml-6">{{ cand.description }}</p>
                </div>
              </div>
              <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="statusClass(cand.status)">
                  {{ cand.status }}
                </span>
                <ActionButton
                  variant="outline"
                  size="sm"
                  :aria-label="`Edit candidate ${cand.name}`"
                  @click="startEditCand(post, cand)"
                >Edit</ActionButton>
                <ActionButton
                  variant="danger"
                  size="sm"
                  :aria-label="`Remove candidate ${cand.name}`"
                  @click="deleteCand(post, cand)"
                >Remove</ActionButton>
              </div>
            </div>

            <!-- Edit Candidate Form -->
            <div
              v-if="editingCandKey === `${post.id}-${editingCandId}`"
              class="mt-4 p-4 bg-neutral-50 rounded-xl border border-neutral-200"
            >
              <h4 class="text-sm font-semibold text-slate-700 mb-3">Edit Candidate</h4>
              <CandidateForm
                :form="editCandForm"
                :is-submitting="isEditingCand"
                :errors="activeFormErrors('editCand')"
                :existing-images="editCandExistingImages"
                edit-mode
                @submit="submitEditCand(post)"
                @cancel="editingCandKey = null"
              />
            </div>

            <!-- Add Candidate Form -->
            <div
              v-if="addingCandPostId === post.id"
              class="mt-4 p-4 bg-primary-50 rounded-xl border border-primary-100"
            >
              <h4 class="text-sm font-semibold text-slate-700 mb-3">Add Candidate</h4>
              <CandidateForm
                :form="addCandForm"
                :is-submitting="isAddingCand"
                :errors="activeFormErrors('addCand')"
                @submit="submitAddCand(post)"
                @cancel="addingCandPostId = null"
              />
            </div>

            <ActionButton
              v-if="addingCandPostId !== post.id"
              variant="outline"
              size="sm"
              class="mt-3"
              :aria-label="`Add candidate to ${post.name}`"
              @click="startAddCand(post)"
            >
              + Add Candidate
            </ActionButton>
          </div>
        </Card>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import ActionButton from '@/Components/ActionButton.vue'
import SectionCard from '@/Components/SectionCard.vue'
import Card from '@/Components/Card.vue'
import EmptyState from '@/Components/EmptyState.vue'
import PostForm from './Partials/PostForm.vue'
import CandidateForm from './Partials/CandidateForm.vue'

const props = defineProps({
  election:     { type: Object, required: true },
  organisation: { type: Object, required: true },
  posts:        { type: Array,  default: () => [] },
})

const page = usePage()

const totalCandidates = computed(() =>
  props.posts.reduce((sum, p) => sum + p.candidacies.length, 0)
)

// ── Active form error isolation ───────────────────────────────────────────────
// Inertia's errors are global per page visit. By tracking which form triggered
// the last submission, we route errors to only that form — not every visible form.
const activeForm = ref(null)

function activeFormErrors(formName) {
  return activeForm.value === formName ? (page.props.errors ?? {}) : {}
}

// ── Add Post ──────────────────────────────────────────────────────────────────
const showAddPost  = ref(false)
const isAddingPost = ref(false)
const addPostForm  = ref(emptyPostForm())

function emptyPostForm() {
  return { name: '', nepali_name: '', is_national_wide: true, state_name: '', required_number: 1, position_order: 0 }
}

function openAddPost() {
  editingPostId.value = null
  showAddPost.value   = true
  activeForm.value    = 'addPost'
}

function closeAddPost() {
  showAddPost.value = false
  activeForm.value  = null
}

function submitAddPost() {
  activeForm.value   = 'addPost'
  isAddingPost.value = true
  router.post(
    route('organisations.elections.posts.store', { organisation: props.organisation.slug, election: props.election.slug }),
    addPostForm.value,
    {
      preserveScroll: true,
      onSuccess: () => { showAddPost.value = false; addPostForm.value = emptyPostForm() },
      onFinish:  () => { isAddingPost.value = false },
    }
  )
}

// ── Edit Post ─────────────────────────────────────────────────────────────────
const editingPostId = ref(null)
const isEditingPost = ref(false)
const editPostForm  = ref({})

function startEditPost(post) {
  showAddPost.value   = false
  editingPostId.value = post.id
  editPostForm.value  = { ...post }
  activeForm.value    = 'editPost'
}

function submitEditPost(post) {
  activeForm.value    = 'editPost'
  isEditingPost.value = true
  router.patch(
    route('organisations.elections.posts.update', { organisation: props.organisation.slug, election: props.election.slug, post: post.id }),
    editPostForm.value,
    {
      preserveScroll: true,
      onSuccess: () => { editingPostId.value = null },
      onFinish:  () => { isEditingPost.value = false },
    }
  )
}

// ── Delete Post ───────────────────────────────────────────────────────────────
function deletePost(post) {
  if (!confirm(`Delete "${post.name}"? Remove all candidates first if any exist.`)) return
  router.delete(
    route('organisations.elections.posts.destroy', { organisation: props.organisation.slug, election: props.election.slug, post: post.id }),
    { preserveScroll: true }
  )
}

// ── Add Candidate ─────────────────────────────────────────────────────────────
const addingCandPostId = ref(null)
const isAddingCand     = ref(false)
const addCandForm      = ref(emptyCandForm())

function emptyCandForm() {
  return { user_id: '', name: '', description: '', position_order: 0, image_1: null, image_2: null, image_3: null }
}

function startAddCand(post) {
  editingCandKey.value   = null
  addingCandPostId.value = post.id
  addCandForm.value      = emptyCandForm()
  activeForm.value       = 'addCand'
}

function submitAddCand(post) {
  activeForm.value   = 'addCand'
  isAddingCand.value = true
  router.post(
    route('organisations.elections.candidacies.store', { organisation: props.organisation.slug, election: props.election.slug, post: post.id }),
    addCandForm.value,
    {
      preserveScroll: true,
      onSuccess: () => { addingCandPostId.value = null; addCandForm.value = emptyCandForm() },
      onFinish:  () => { isAddingCand.value = false },
    }
  )
}

// ── Edit Candidate ────────────────────────────────────────────────────────────
const editingCandKey         = ref(null)
const editingCandId          = ref(null)
const isEditingCand          = ref(false)
const editCandForm           = ref({})
const editCandExistingImages = ref([null, null, null])

function startEditCand(post, cand) {
  addingCandPostId.value       = null
  editingCandId.value          = cand.id
  editingCandKey.value         = `${post.id}-${cand.id}`
  editCandExistingImages.value = [cand.image_path_1 ?? null, cand.image_path_2 ?? null, cand.image_path_3 ?? null]
  editCandForm.value = {
    name:           cand.name,
    description:    cand.description,
    status:         cand.status,
    position_order: cand.position_order,
    image_1: null,
    image_2: null,
    image_3: null,
  }
  activeForm.value = 'editCand'
}

function submitEditCand(post) {
  activeForm.value    = 'editCand'
  isEditingCand.value = true

  const hasFiles = editCandForm.value.image_1 || editCandForm.value.image_2 || editCandForm.value.image_3
  const url = route('organisations.elections.candidacies.update', {
    organisation: props.organisation.slug,
    election:     props.election.id,
    post:         post.id,
    candidacy:    editingCandId.value,
  })
  const opts = {
    preserveScroll: true,
    onSuccess: () => { editingCandKey.value = null },
    onFinish:  () => { isEditingCand.value = false },
  }

  // Browsers cannot send multipart/form-data via PATCH — use POST + _method spoofing
  if (hasFiles) {
    router.post(url, { ...editCandForm.value, _method: 'PATCH' }, opts)
  } else {
    router.patch(url, editCandForm.value, opts)
  }
}

// ── Delete Candidate ──────────────────────────────────────────────────────────
function deleteCand(post, cand) {
  if (!confirm(`Remove "${cand.name}" from this position?`)) return
  router.delete(
    route('organisations.elections.candidacies.destroy', {
      organisation: props.organisation.slug,
      election:     props.election.id,
      post:         post.id,
      candidacy:    cand.id,
    }),
    { preserveScroll: true }
  )
}

// ── Status badge colour ───────────────────────────────────────────────────────
function statusClass(status) {
  const map = {
    approved:  'bg-emerald-100 text-emerald-700',
    pending:   'bg-amber-100   text-amber-700',
    rejected:  'bg-red-100     text-red-700',
    withdrawn: 'bg-neutral-100 text-neutral-500',
  }
  return map[status] ?? 'bg-neutral-100 text-neutral-500'
}
</script>
