<template>
  <ElectionLayout>
    <main role="main" class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Flash -->
        <div v-if="$page.props.flash?.success"
             class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-5 py-4 text-sm">
          <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ $page.props.flash.success }}
        </div>

        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <Link :href="`/organisations/${organisation.slug}/membership`"
                  class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm mb-2">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              {{ t.back }}
            </Link>
            <h1 class="text-3xl font-bold text-neutral-900">{{ t.title }}</h1>
            <p class="text-neutral-500 mt-1 text-sm">{{ t.description.replace('{organisation}', organisation.name) }}</p>
          </div>
          <Link :href="`/organisations/${organisation.slug}/membership/participant-invitations`"
                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ t.invite_participant }}
          </Link>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
          <div class="bg-white rounded-lg border border-neutral-200 p-4 text-center">
            <div class="text-2xl font-bold text-primary-600">{{ stats.staff }}</div>
            <div class="text-xs text-neutral-500 mt-1">{{ t.type_staff }}</div>
          </div>
          <div class="bg-white rounded-lg border border-neutral-200 p-4 text-center">
            <div class="text-2xl font-bold text-neutral-600">{{ stats.guests }}</div>
            <div class="text-xs text-neutral-500 mt-1">{{ t.type_guest }}</div>
          </div>
          <div class="bg-white rounded-lg border border-neutral-200 p-4 text-center">
            <div class="text-2xl font-bold text-violet-600">{{ stats.election_committee }}</div>
            <div class="text-xs text-neutral-500 mt-1">{{ t.type_election_committee }}</div>
          </div>
        </div>

        <!-- Type filter tabs -->
        <div class="flex gap-1 mb-5 border-b border-neutral-200">
          <button
            v-for="tab in tabs"
            :key="tab.value"
            @click="activeType = tab.value"
            class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors"
            :class="activeType === tab.value
              ? 'border-primary-600 text-primary-600'
              : 'border-transparent text-neutral-500 hover:text-neutral-700'"
          >
            {{ tab.label }}
          </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-neutral-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_name }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_type }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_role }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_assigned }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_expires }}</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_actions }}</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="filteredParticipants.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-sm text-neutral-400">{{ t.empty }}</td>
              </tr>
              <tr v-for="p in filteredParticipants" :key="p.id" class="hover:bg-neutral-50 transition-colors">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-neutral-900">{{ p.name }}</div>
                  <div class="text-xs text-neutral-400">{{ p.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="typeBadgeClass(p.participant_type)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                    {{ typeLabel(p.participant_type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">{{ p.role || '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">{{ formatDate(p.assigned_at) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span v-if="p.is_expired" class="text-danger-500 font-medium text-xs">{{ t.expired }}</span>
                  <span v-else-if="p.expires_at" class="text-neutral-500 text-xs">{{ formatDate(p.expires_at) }}</span>
                  <span v-else class="text-emerald-600 text-xs">{{ t.active }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <button
                    @click="remove(p.id)"
                    class="text-danger-600 hover:text-danger-700 text-sm font-medium transition-colors"
                  >
                    {{ t.remove }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="participants.last_page > 1" class="mt-6 flex justify-center gap-2">
          <Link
            v-for="link in participants.links"
            :key="link.label"
            :href="link.url ?? '#'"
            :class="[
              'px-3 py-1 text-sm rounded border transition-colors',
              link.active ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-neutral-600 border-neutral-300 hover:bg-neutral-50',
              !link.url ? 'opacity-40 pointer-events-none' : ''
            ]"
            v-html="link.label"
          />
        </div>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

import pageDe from '@/locales/pages/Organisations/Membership/Participants/Index/de.json'
import pageEn from '@/locales/pages/Organisations/Membership/Participants/Index/en.json'
import pageNp from '@/locales/pages/Organisations/Membership/Participants/Index/np.json'

const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

const props = defineProps({
  organisation: { type: Object, required: true },
  participants: { type: Object, required: true },
  filters:      { type: Object, default: () => ({}) },
  stats:        { type: Object, default: () => ({ staff: 0, guests: 0, election_committee: 0 }) },
})

const activeType = ref(props.filters.type ?? 'all')

const tabs = computed(() => [
  { value: 'all',                label: t.value.filter_all },
  { value: 'staff',              label: t.value.filter_staff },
  { value: 'guest',              label: t.value.filter_guest },
  { value: 'election_committee', label: t.value.filter_election_committee },
])

const filteredParticipants = computed(() => {
  const data = props.participants.data ?? []
  if (activeType.value === 'all') return data
  return data.filter(p => p.participant_type === activeType.value)
})

function typeLabel(type) {
  const map = {
    staff:              t.value.type_staff,
    guest:              t.value.type_guest,
    election_committee: t.value.type_election_committee,
  }
  return map[type] ?? type
}

function typeBadgeClass(type) {
  const classes = {
    staff:              'bg-primary-100 text-primary-700',
    guest:              'bg-neutral-100 text-neutral-700',
    election_committee: 'bg-violet-100 text-violet-700',
  }
  return classes[type] ?? 'bg-neutral-100 text-neutral-700'
}

function formatDate(iso) {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString()
}

function remove(id) {
  if (!confirm(t.value.confirm_remove)) return
  router.delete(`/organisations/${props.organisation.slug}/membership/participants/${id}`, {
    preserveScroll: true,
  })
}
</script>

