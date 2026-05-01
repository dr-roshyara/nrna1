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
          <span class="text-neutral-700 font-medium">Positions</span>
        </nav>

        <!-- Page Header -->
        <SectionCard padding="lg">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1">Election Management</p>
              <h1 class="text-2xl font-bold text-slate-900">Positions</h1>
              <p class="text-sm text-slate-500 mt-0.5">
                {{ posts.length }} position{{ posts.length !== 1 ? 's' : '' }}
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
          class="flex items-center gap-3 bg-danger-50 border border-danger-200 rounded-xl px-5 py-4"
        >
          <svg class="w-5 h-5 text-danger-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p class="text-sm font-medium text-danger-800">{{ page.props.flash.error }}</p>
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
                  :class="post.is_national_wide ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700'"
                >
                  {{ post.is_national_wide ? 'National' : post.state_name }}
                </span>
                <span class="text-xs text-slate-500">Select {{ post.required_number }}</span>
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
        </Card>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ActionButton from '@/Components/ActionButton.vue'
import SectionCard from '@/Components/SectionCard.vue'
import Card from '@/Components/Card.vue'
import EmptyState from '@/Components/EmptyState.vue'
import PostForm from './Partials/PostForm.vue'

const props = defineProps({
  election:     { type: Object, required: true },
  organisation: { type: Object, required: true },
  posts:        { type: Array,  default: () => [] },
})

const page = usePage()

// ── Active form error isolation ───────────────────────────────────────────────
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
  if (!confirm(`Delete "${post.name}"? This cannot be undone.`)) return
  router.delete(
    route('organisations.elections.posts.destroy', { organisation: props.organisation.slug, election: props.election.slug, post: post.id }),
    { preserveScroll: true }
  )
}
</script>

