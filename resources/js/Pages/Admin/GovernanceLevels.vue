<template>
  <PublicDigitLayout>
    <div class="governance-levels-page max-w-7xl mx-auto px-4 py-8">
      <!-- Page Header -->
      <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
          <div>
            <h1 class="text-2xl font-bold text-neutral-900">{{ $t('pages.governance-levels.title') }}</h1>
            <p class="mt-1 text-sm text-neutral-500">{{ $t('pages.governance-levels.description') }}</p>
          </div>
          <Link href="/governance-levels" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-primary-700 bg-primary-50 rounded-lg hover:bg-primary-100 hover:text-primary-800 transition-colors border border-primary-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            {{ $t('pages.governance-levels.help_text') }}
          </Link>
        </div>
        <div class="flex items-center gap-3">
          <Button variant="outline" size="sm" @click="fetchLevels" :disabled="loading">
            {{ $t('pages.governance-levels.actions.refresh') }}
          </Button>
          <Button variant="primary" size="sm" @click="openAddForm">
            {{ $t('pages.governance-levels.actions.add') }}
          </Button>
        </div>
      </div>

      <!-- Governance Navigation Tabs -->
      <div class="mb-8 border-b border-gray-200">
        <div class="flex gap-8">
          <Link
            :href="`/organisations/${organisation.slug}/committees`"
            class="px-4 py-3 text-base font-semibold text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 transition-colors"
          >
            Committees
          </Link>
          <a
            href="#"
            class="px-4 py-3 text-base font-semibold text-primary-600 border-b-2 border-primary-600"
          >
            Governance Levels
          </a>
          <Link
            :href="`/organisations/${organisation.slug}/geo/units`"
            class="px-4 py-3 text-base font-semibold text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 transition-colors"
          >
            Geographic Units
          </Link>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading && levels.length === 0" class="text-center py-16">
        <div class="flex justify-center">
          <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
        </div>
        <p class="mt-4 text-sm text-neutral-500">{{ $t('pages.governance-levels.messages.loading') }}</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-danger-50 mb-4">
          <svg class="w-8 h-8 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <p class="text-neutral-700 font-medium">{{ error }}</p>
        <Button variant="outline" size="sm" class="mt-4" @click="fetchLevels">
          {{ $t('pages.governance-levels.actions.refresh') }}
        </Button>
      </div>

      <!-- Empty State (only when add form is NOT open) -->
      <Card v-else-if="levels.length === 0 && !showAddForm" padding="lg" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 mb-4">
          <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <p class="text-neutral-700 font-medium">{{ $t('pages.governance-levels.messages.empty') }}</p>
      </Card>

      <!-- Levels Table -->
      <Card v-else padding="none">
        <table class="min-w-full divide-y divide-neutral-200">
          <thead>
            <tr class="bg-neutral-50">
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-16">{{ $t('pages.governance-levels.fields.level') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('pages.governance-levels.fields.committee_name') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('pages.governance-levels.fields.committee_code') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('pages.governance-levels.fields.geo_name') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('pages.governance-levels.fields.geo_code') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('pages.governance-levels.fields.geo_parent_code') }}</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-500 uppercase tracking-wider w-20">{{ $t('pages.governance-levels.fields.is_active') }}</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-500 uppercase tracking-wider w-32">{{ $t('pages.governance-levels.fields.actions') }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-neutral-100">
            <tr v-for="(level, index) in levels" :key="level.id"
                class="hover:bg-neutral-100 transition-colors"
                :class="[index % 2 === 0 ? 'bg-white' : 'bg-neutral-50', { 'opacity-60': !level.is_active }]"
            >
              <!-- Level Number -->
              <td class="px-4 py-3 text-sm font-mono font-semibold text-neutral-900">
                {{ level.level }}
              </td>

              <!-- Committee Name (editable) -->
              <td class="px-4 py-3">
                <input
                  v-if="editingId === level.id"
                  v-model="editForm.committee_name"
                  class="w-full px-2 py-1 text-sm border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                  :class="{ 'border-danger-300': editErrors.committee_name }"
                />
                <span v-else class="text-sm text-neutral-900">{{ level.committee_name }}</span>
              </td>

              <!-- Committee Code (editable) -->
              <td class="px-4 py-3">
                <input
                  v-if="editingId === level.id"
                  v-model="editForm.committee_code"
                  class="w-full px-2 py-1 text-sm font-mono border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                />
                <span v-else class="text-sm font-mono text-neutral-700">{{ level.committee_code }}</span>
              </td>

              <!-- Geo Name (editable) -->
              <td class="px-4 py-3">
                <input
                  v-if="editingId === level.id"
                  v-model="editForm.geo_name"
                  class="w-full px-2 py-1 text-sm border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                />
                <span v-else class="text-sm text-neutral-700">{{ level.geo_name }}</span>
              </td>

              <!-- Geo Code (editable) -->
              <td class="px-4 py-3">
                <input
                  v-if="editingId === level.id"
                  v-model="editForm.geo_code"
                  class="w-full px-2 py-1 text-sm font-mono border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                />
                <span v-else class="text-sm font-mono text-neutral-500">{{ level.geo_code }}</span>
              </td>

              <!-- Parent Geo Code (editable) -->
              <td class="px-4 py-3">
                <select
                  v-if="editingId === level.id"
                  v-model="editForm.geo_parent_code"
                  class="w-full px-2 py-1 text-sm border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                >
                  <option value="">{{ $t('pages.governance-levels.placeholders.geo_parent_code') }}</option>
                  <option v-for="g in geoOptions" :key="g.geo_code" :value="g.geo_code">
                    {{ g.geo_name }} ({{ g.geo_code }})
                  </option>
                </select>
                <span v-else class="text-sm text-neutral-500">{{ level.geo_parent_code || '—' }}</span>
              </td>

              <!-- Active Toggle -->
              <td class="px-4 py-3 text-center">
                <input
                  v-if="editingId === level.id"
                  type="checkbox"
                  v-model="editForm.is_active"
                  class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500"
                />
                <span v-else :class="level.is_active ? 'text-success-600' : 'text-neutral-300'" class="text-lg">●</span>
              </td>

              <!-- Actions -->
              <td class="px-4 py-3 text-right whitespace-nowrap">
                <template v-if="editingId === level.id">
                  <Button variant="primary" size="sm" @click="saveLevel(level.id)" :loading="saving">
                    {{ $t('pages.governance-levels.actions.save') }}
                  </Button>
                  <Button variant="ghost" size="sm" class="ml-1" @click="cancelEdit">
                    {{ $t('pages.governance-levels.actions.cancel') }}
                  </Button>
                </template>
                <template v-else>
                  <Button variant="ghost" size="sm" @click="startEdit(level)">
                    {{ $t('pages.governance-levels.actions.edit') }}
                  </Button>
                  <Button variant="ghost" size="sm" class="ml-1 text-danger-600 hover:text-danger-700" @click="confirmDelete(level)">
                    {{ $t('pages.governance-levels.actions.delete') }}
                  </Button>
                </template>
              </td>
            </tr>

            <!-- New Level Row -->
            <tr v-if="showAddForm" class="bg-primary-50/50">
              <td class="px-4 py-3">
                <input
                  type="number"
                  v-model.number="newForm.level"
                  class="w-16 px-2 py-1 text-sm font-mono border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300"
                  min="0"
                  max="10"
                />
              </td>
              <td class="px-4 py-3">
                <input v-model="newForm.committee_name" class="w-full px-2 py-1 text-sm border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300" :placeholder="$t('pages.governance-levels.placeholders.committee_name')" />
              </td>
              <td class="px-4 py-3">
                <input v-model="newForm.committee_code" class="w-full px-2 py-1 text-sm font-mono border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300" :placeholder="$t('pages.governance-levels.placeholders.committee_code')" />
              </td>
              <td class="px-4 py-3">
                <input v-model="newForm.geo_name" class="w-full px-2 py-1 text-sm border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300" :placeholder="$t('pages.governance-levels.placeholders.geo_name')" />
              </td>
              <td class="px-4 py-3">
                <input v-model="newForm.geo_code" class="w-full px-2 py-1 text-sm font-mono border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300" :placeholder="$t('pages.governance-levels.placeholders.geo_code')" />
              </td>
              <td class="px-4 py-3">
                <select v-model="newForm.geo_parent_code" class="w-full px-2 py-1 text-sm border border-primary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300">
                  <option value="">{{ $t('pages.governance-levels.placeholders.geo_parent_code') }}</option>
                  <option v-for="g in geoOptions" :key="g.geo_code" :value="g.geo_code">{{ g.geo_name }} ({{ g.geo_code }})</option>
                </select>
              </td>
              <td class="px-4 py-3 text-center">
                <input type="checkbox" v-model="newForm.is_active" class="w-4 h-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500" />
              </td>
              <td class="px-4 py-3 text-right">
                <Button variant="primary" size="sm" @click="createLevel" :loading="saving">
                  {{ $t('pages.governance-levels.actions.create') }}
                </Button>
                <Button variant="ghost" size="sm" class="ml-1" @click="cancelAdd">
                  {{ $t('pages.governance-levels.actions.cancel') }}
                </Button>
              </td>
            </tr>
          </tbody>
        </table>
      </Card>

      <!-- Toast Notification -->
      <transition name="fade">
        <div v-if="toast.show" class="fixed bottom-6 right-6 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-50"
          :class="toast.type === 'success' ? 'bg-success-600' : 'bg-danger-600'"
        >
          {{ toast.message }}
        </div>
      </transition>
    </div>
  </PublicDigitLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'
import { Link } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import type { GovernanceLevelDefinition, GovernanceLevelFormData } from '@/types/governance.types'

const { t } = useI18n()

const props = defineProps<{
  organisation: {
    id: string;
    slug: string;
    name: string;
  }
}>()

const apiBase = computed(() => `/organisations/${props.organisation.slug}/governance/levels/api`)

const levels = ref<GovernanceLevelDefinition[]>([])
const loading = ref(false)
const saving = ref(false)
const error = ref<string | null>(null)
const editingId = ref<number | null>(null)
const showAddForm = ref(false)
const editErrors = ref<Record<string, boolean>>({})

const defaultForm = (): GovernanceLevelFormData => ({
  level: 0,
  committee_name: '',
  committee_code: '',
  geo_name: '',
  geo_code: '',
  geo_parent_code: null,
  description: null,
  is_active: true,
  sort_order: 0,
})

const editForm = ref<GovernanceLevelFormData>(defaultForm())
const newForm = ref<GovernanceLevelFormData>(defaultForm())

const toast = ref<{ show: boolean; message: string; type: string }>({
  show: false,
  message: '',
  type: 'success',
})

const geoOptions = computed(() => {
  return levels.value
    .filter(l => l.geo_code && l.geo_name)
    .map(l => ({ geo_code: l.geo_code, geo_name: l.geo_name }))
})

function showToast(message: string, type: 'success' | 'error' = 'success') {
  toast.value = { show: true, message, type }
  setTimeout(() => { toast.value.show = false }, 4000)
}

async function fetchLevels() {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get(apiBase.value)
    levels.value = response.data
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load governance levels'
  } finally {
    loading.value = false
  }
}

function startEdit(level: GovernanceLevelDefinition) {
  editingId.value = level.id
  editForm.value = {
    level: level.level,
    committee_name: level.committee_name,
    committee_code: level.committee_code,
    geo_name: level.geo_name,
    geo_code: level.geo_code,
    geo_parent_code: level.geo_parent_code,
    description: level.description,
    is_active: level.is_active,
    sort_order: level.sort_order,
  }
  editErrors.value = {}
}

function cancelEdit() {
  editingId.value = null
  editForm.value = defaultForm()
  editErrors.value = {}
}

async function saveLevel(id: number) {
  saving.value = true
  editErrors.value = {}
  try {
    await axios.put(`${apiBase.value}/${id}`, editForm.value)
    showToast(t('pages.governance-levels.messages.update_success'))
    editingId.value = null
    await fetchLevels()
  } catch (err: any) {
    if (err.response?.status === 422 && err.response.data?.errors) {
      const fields = err.response.data.errors
      Object.keys(fields).forEach(key => { editErrors.value[key] = true })
    }
    showToast(err.response?.data?.message || t('pages.governance-levels.messages.error'), 'error')
  } finally {
    saving.value = false
  }
}

function openAddForm() {
  showAddForm.value = true
  newForm.value = defaultForm()
  // Suggest next level number
  if (levels.value.length > 0) {
    newForm.value.level = Math.max(...levels.value.map(l => l.level)) + 1
  }
}

function cancelAdd() {
  showAddForm.value = false
  newForm.value = defaultForm()
}

async function createLevel() {
  saving.value = true
  try {
    await axios.post(apiBase.value, newForm.value)
    showToast(t('pages.governance-levels.messages.create_success'))
    showAddForm.value = false
    newForm.value = defaultForm()
    await fetchLevels()
  } catch (err: any) {
    showToast(err.response?.data?.message || t('pages.governance-levels.messages.error'), 'error')
  } finally {
    saving.value = false
  }
}

function confirmDelete(level: GovernanceLevelDefinition) {
  const confirmText = t('pages.governance-levels.actions.confirm_delete_text')
  if (!confirm(`${level.committee_name} (Level ${level.level}): ${confirmText}`)) return

  saving.value = true
  axios.delete(`${apiBase.value}/${level.id}`)
    .then(() => {
      showToast(t('pages.governance-levels.messages.delete_success'))
      fetchLevels()
    })
    .catch(() => {
      showToast(t('pages.governance-levels.messages.error'), 'error')
    })
    .finally(() => {
      saving.value = false
    })
}

onMounted(() => {
  fetchLevels()
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
