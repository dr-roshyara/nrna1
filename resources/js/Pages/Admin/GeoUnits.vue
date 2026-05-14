<template>
  <PublicDigitLayout>
    <div class="geo-units-page max-w-7xl mx-auto px-4 py-8">
      <!-- Page Header -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-2xl font-bold text-neutral-900">{{ $t('pages.geo-units.title') }}</h1>
          <p class="mt-1 text-sm text-neutral-500">{{ $t('pages.geo-units.description') }}</p>
        </div>
        <div class="flex items-center gap-3">
          <Button variant="outline" size="sm" @click="fetchUnits" :disabled="loading">
            {{ $t('pages.geo-units.actions.refresh') }}
          </Button>
          <Button
            variant="primary"
            size="sm"
            @click="showCreateModal = true"
            class="gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ $t('pages.geo-units.actions.add') || 'Add Unit' }}
          </Button>
        </div>
      </div>

      <!-- Governance Navigation Tabs -->
      <div class="mb-8 border-b border-gray-200">
        <div class="flex gap-8">
          <a
            :href="`/organisations/${organisation.slug}/committees`"
            class="px-4 py-3 text-base font-semibold text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 transition-colors"
          >
            Committees
          </a>
          <a
            :href="`/organisations/${organisation.slug}/governance/levels`"
            class="px-4 py-3 text-base font-semibold text-gray-600 hover:text-gray-900 hover:border-b-2 hover:border-gray-300 transition-colors"
          >
            Governance Levels
          </a>
          <a
            href="#"
            class="px-4 py-3 text-base font-semibold text-primary-600 border-b-2 border-primary-600"
          >
            Geographic Units
          </a>
        </div>
      </div>

      <!-- Filter Bar -->
      <Card padding="sm" class="mb-6">
        <div class="flex flex-wrap items-center gap-4">
          <div class="flex-1 min-w-[200px]">
            <input
              v-model="filters.search"
              type="text"
              :placeholder="$t('pages.geo-units.filter.placeholder')"
              class="w-full px-3 py-2 text-sm border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              @input="debouncedFilter"
            />
          </div>
          <select
            v-model="filters.level"
            class="px-3 py-2 text-sm border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white"
            @change="applyFilters"
          >
            <option value="">{{ $t('pages.geo-units.filter.level') }}</option>
            <option v-for="l in availableLevels" :key="l" :value="l">
              {{ $t('pages.geo-units.fields.level') }} {{ l }}
            </option>
          </select>
          <select
            v-model="filters.country"
            class="px-3 py-2 text-sm border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white"
            @change="applyFilters"
          >
            <option value="">{{ $t('pages.geo-units.filter.country') }}</option>
            <option v-for="c in availableCountries" :key="c" :value="c">{{ c }}</option>
          </select>
          <Button v-if="hasActiveFilters" variant="ghost" size="sm" @click="clearFilters">
            {{ $t('pages.geo-units.actions.refresh') }}
          </Button>
        </div>
      </Card>

      <!-- Breadcrumb -->
      <div v-if="breadcrumb.length > 0" class="flex items-center gap-2 mb-4 text-sm text-neutral-500 flex-wrap">
        <template v-for="(crumb, i) in breadcrumb" :key="crumb.id">
          <span v-if="i > 0" class="text-neutral-300">/</span>
          <button
            class="hover:text-primary-600 transition-colors focus:outline-none"
            @click="breadcrumbNavigate(crumb)"
          >
            <span :class="i === breadcrumb.length - 1 ? 'font-semibold text-neutral-900' : ''">
              {{ crumb.name || crumb.code }}
            </span>
          </button>
        </template>
      </div>

      <!-- Tree Controls -->
      <div v-if="!loading && treeUnits.length > 0" class="flex items-center gap-3 mb-4">
        <Button variant="ghost" size="sm" @click="expandAll">
          {{ $t('pages.geo-units.actions.expand_all') }}
        </Button>
        <Button variant="ghost" size="sm" @click="collapseAll">
          {{ $t('pages.geo-units.actions.collapse_all') }}
        </Button>
      </div>

      <!-- Loading State -->
      <div v-if="loading && treeUnits.length === 0" class="text-center py-16">
        <div class="flex justify-center">
          <div class="w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
        </div>
        <p class="mt-4 text-sm text-neutral-500">{{ $t('pages.geo-units.messages.loading') }}</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-danger-50 mb-4">
          <svg class="w-8 h-8 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <p class="text-neutral-700 font-medium">{{ error }}</p>
        <Button variant="outline" size="sm" class="mt-4" @click="fetchUnits">
          {{ $t('pages.geo-units.actions.refresh') }}
        </Button>
      </div>

      <!-- Empty State -->
      <Card v-else-if="treeUnits.length === 0" padding="lg" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 mb-4">
          <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <p class="text-neutral-700 font-medium">
          {{ hasActiveFilters ? $t('pages.geo-units.messages.empty_filtered') : $t('pages.geo-units.messages.empty') }}
        </p>
      </Card>

      <!-- Tree Table -->
      <Card v-else padding="none">
        <table class="min-w-full divide-y divide-neutral-200">
          <thead>
            <tr class="bg-neutral-50">
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">{{ $t('pages.geo-units.fields.name') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('pages.geo-units.fields.code') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-16">{{ $t('pages.geo-units.fields.level') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('pages.geo-units.fields.type') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-32">{{ $t('pages.geo-units.fields.governance_level') }}</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-28">{{ $t('pages.geo-units.fields.committee') }}</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-neutral-500 uppercase tracking-wider w-16">{{ $t('pages.geo-units.fields.is_active') }}</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-neutral-500 uppercase tracking-wider w-24">{{ $t('pages.geo-units.fields.actions') }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-neutral-100">
            <GeoUnitRow
              v-for="row in displayRows"
              :key="row.id"
              :unit="row"
              :expanded="expandedIds.has(row.id)"
              :depth="row.depth"
              :has-children="row.children_count > 0"
              @toggle="toggleExpand(row.id)"
              @view="viewUnit(row)"
            />
          </tbody>
        </table>
      </Card>

      <!-- Toast Notification -->
      <transition name="fade">
        <div
          v-if="toast.show"
          class="fixed bottom-6 right-6 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium z-50"
          :class="toast.type === 'success' ? 'bg-success-600' : 'bg-danger-600'"
        >
          {{ toast.message }}
        </div>
      </transition>

      <!-- Create Geo Unit Modal -->
      <GeoUnitCreateModal
        :is-open="showCreateModal"
        :all-units="allUnitsFlat"
        @close="showCreateModal = false"
        @submit="handleCreateUnit"
      />
    </div>
  </PublicDigitLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import GeoUnitRow from './GeoUnitRow.vue'
import GeoUnitCreateModal from './GeoUnitCreateModal.vue'
import type { GovernanceGeoUnit, GeoUnitBreadcrumb } from '@/types/geo.types'

const props = defineProps<{
  organisation: {
    id: string;
    slug: string;
    name: string;
  }
}>()

const apiBase = computed(() => `/organisations/${props.organisation.slug}/geo/units/api`)

const treeUnits = ref<GovernanceGeoUnit[]>([])
const allUnitsFlat = ref<GovernanceGeoUnit[]>([])
const loading = ref(false)
const error = ref<string | null>(null)
const expandedIds = ref<Set<number>>(new Set())
const breadcrumb = ref<GeoUnitBreadcrumb[]>([])
const showCreateModal = ref(false)

const filters = ref({
  search: '',
  level: '',
  country: '',
})

let debounceTimer: ReturnType<typeof setTimeout> | null = null

const toast = ref<{ show: boolean; message: string; type: string }>({
  show: false,
  message: '',
  type: 'success',
})

const availableLevels = computed(() => {
  const levels = new Set(treeUnits.value.map(u => u.admin_level))
  return Array.from(levels).sort((a, b) => a - b)
})

const availableCountries = computed(() => {
  const countries = new Set(treeUnits.value.map(u => u.country_code).filter(Boolean))
  return Array.from(countries).sort()
})

const hasActiveFilters = computed(() => {
  return filters.value.search !== '' || filters.value.level !== '' || filters.value.country !== ''
})

/**
 * Filter units based on expand/collapse state.
 * Since the API returns data sorted by path (parents before children),
 * we can process in O(n): track which ancestors are expanded,
 * and only show units whose parent chain is fully expanded.
 */
const displayRows = computed(() => {
  const visibleAncestors = new Set<number>()

  return treeUnits.value.filter(unit => {
    const parentId = unit.parent_id

    // Root units (null parent) are always visible
    if (parentId === null) {
      visibleAncestors.add(unit.id)
      return true
    }

    // If parent is not visible, hide this unit
    if (!visibleAncestors.has(parentId)) {
      return false
    }

    // If this unit is expanded, its children become visible
    if (expandedIds.value.has(unit.id)) {
      visibleAncestors.add(unit.id)
    }

    return true
  })
})

function showToast(message: string, type: 'success' | 'error' = 'success') {
  toast.value = { show: true, message, type }
  setTimeout(() => { toast.value.show = false }, 4000)
}

function toggleExpand(id: number) {
  const next = new Set(expandedIds.value)
  if (next.has(id)) {
    next.delete(id)
  } else {
    next.add(id)
  }
  expandedIds.value = next
}

function expandAll() {
  const all = new Set<number>()
  for (const unit of treeUnits.value) {
    if (unit.children_count > 0) {
      all.add(unit.id)
    }
  }
  expandedIds.value = all
}

function collapseAll() {
  expandedIds.value = new Set()
}

async function viewUnit(unit: GovernanceGeoUnit) {
  try {
    const response = await axios.get(`${apiBase.value}/${unit.id}`)
    breadcrumb.value = response.data.data?.breadcrumb || []
  } catch {
    showToast('Failed to load unit details', 'error')
  }
}

function breadcrumbNavigate(_crumb: GeoUnitBreadcrumb) {
  // Scroll to unit in tree — expand ancestors to make it visible
}

function debouncedFilter() {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    applyFilters()
  }, 300)
}

async function applyFilters() {
  loading.value = true
  error.value = null
  const params: Record<string, string> = {}
  if (filters.value.search) params.search = filters.value.search
  if (filters.value.level) params.level = filters.value.level
  if (filters.value.country) params.country = filters.value.country

  try {
    const response = await axios.get(`${apiBase.value}/tree`, { params })
    treeUnits.value = response.data.data || []
    breadcrumb.value = []
    expandedIds.value = new Set()

    // Keep flat list updated for modal (without filters)
    const flatResponse = await axios.get(`${apiBase.value}/`)
    allUnitsFlat.value = flatResponse.data.data || []
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load geographic units'
    treeUnits.value = []
    allUnitsFlat.value = []
  } finally {
    loading.value = false
  }
}

function clearFilters() {
  filters.value = { search: '', level: '', country: '' }
  applyFilters()
}

async function fetchUnits() {
  loading.value = true
  error.value = null
  try {
    const response = await axios.get(`${apiBase.value}/tree`)
    treeUnits.value = response.data.data || []
    expandedIds.value = new Set()

    // Also fetch a flat list of ALL units for the modal's parent selector
    const flatResponse = await axios.get(`${apiBase.value}/`)
    allUnitsFlat.value = flatResponse.data.data || []
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load geographic units'
    treeUnits.value = []
    allUnitsFlat.value = []
  } finally {
    loading.value = false
  }
}

async function handleCreateUnit(formData: any) {
  try {
    await axios.post(`${apiBase.value}/`, formData)
    showToast('Geographic unit created successfully!', 'success')
    showCreateModal.value = false
    await fetchUnits()
  } catch (err: any) {
    const message = err.response?.data?.message || 'Failed to create geographic unit'
    showToast(message, 'error')
  }
}

onMounted(() => {
  fetchUnits()
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
