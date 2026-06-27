<template>
  <PublicDigitLayout>
    <div class="max-w-5xl mx-auto py-8 px-4">

      <!-- Flash -->
      <div v-if="page.props.flash?.success"
           class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ page.props.flash.success }}
      </div>
      <div v-if="page.props.errors?.error"
           class="mb-6 rounded-lg bg-danger-50 border border-danger-200 p-4 text-danger-800 text-sm">
        {{ page.props.errors.error }}
      </div>

      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">{{ t.title }}</h1>
        <p class="text-sm text-neutral-500 mt-1">{{ organisation.name }}</p>
      </div>

      <!-- Status filter tabs -->
      <div class="flex gap-1 mb-5 border-b border-neutral-200">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="filterStatus = tab.value"
          class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors"
          :class="filterStatus === tab.value
            ? 'border-primary-600 text-primary-600'
            : 'border-transparent text-neutral-500 hover:text-neutral-700'"
        >
          {{ tab.label }}
          <span
            v-if="statusCounts[tab.value] !== undefined"
            class="ml-1.5 rounded-full px-1.5 py-0.5 text-xs"
            :class="filterStatus === tab.value ? 'bg-primary-100 text-primary-700' : 'bg-neutral-100 text-neutral-500'"
          >{{ statusCounts[tab.value] }}</span>
        </button>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-neutral-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_applicant }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_type }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_status }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_submitted }}</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_expires }}</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">{{ t.col_actions }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="app in filteredApplications" :key="app.id" class="hover:bg-neutral-50 transition-colors">
              <td class="px-6 py-4">
                <div class="text-sm font-medium text-neutral-900">{{ app.user?.name }}</div>
                <div class="text-xs text-neutral-400">{{ app.user?.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                {{ app.membership_type?.name ?? '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="statusClass(app.status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                  {{ statusLabel(app.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                {{ formatDate(app.submitted_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span :class="isExpiringSoon(app.expires_at) ? 'text-orange-600 font-medium' : 'text-neutral-400'">
                  {{ formatDate(app.expires_at) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                <a
                  :href="route('organisations.membership.applications.show', [organisation.slug, app.id])"
                  class="text-primary-600 hover:text-primary-900 font-medium"
                >{{ t.review }}</a>
              </td>
            </tr>
            <tr v-if="filteredApplications.length === 0">
              <td colspan="6" class="px-6 py-16 text-center text-neutral-400 text-sm">
                {{ t.no_applications }}
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="applications.last_page > 1" class="px-6 py-4 border-t border-neutral-100 flex items-center justify-between text-sm text-neutral-600">
          <span>{{ t.page }} {{ applications.current_page }} / {{ applications.last_page }}</span>
          <div class="flex gap-2">
            <a v-if="applications.prev_page_url" :href="applications.prev_page_url"
               class="px-3 py-1 rounded border border-neutral-300 hover:bg-neutral-50">{{ t.prev }}</a>
            <a v-if="applications.next_page_url" :href="applications.next_page_url"
               class="px-3 py-1 rounded border border-neutral-300 hover:bg-neutral-50">{{ t.next }}</a>
          </div>
        </div>
      </div>

    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  applications: { type: Object, required: true },
})

const page = usePage()
const { locale } = useI18n()

// ── i18n ──────────────────────────────────────────────────────────────────────

const translations = {
  en: {
    title: 'Membership Applications',
    col_applicant: 'Applicant', col_type: 'Type', col_status: 'Status',
    col_submitted: 'Submitted', col_expires: 'Expires', col_actions: 'Actions',
    review: 'Review',
    no_applications: 'No applications found.',
    page: 'Page', prev: '← Prev', next: 'Next →',
    tab_all: 'All', tab_pending: 'Pending', tab_approved: 'Approved',
    tab_rejected: 'Rejected',
    status_draft: 'Draft', status_submitted: 'Submitted',
    status_under_review: 'Under Review', status_approved: 'Approved',
    status_rejected: 'Rejected',
  },
  de: {
    title: 'Mitgliedschaftsanträge',
    col_applicant: 'Antragsteller', col_type: 'Typ', col_status: 'Status',
    col_submitted: 'Eingereicht', col_expires: 'Läuft ab', col_actions: 'Aktionen',
    review: 'Prüfen',
    no_applications: 'Keine Anträge gefunden.',
    page: 'Seite', prev: '← Zurück', next: 'Weiter →',
    tab_all: 'Alle', tab_pending: 'Ausstehend', tab_approved: 'Genehmigt',
    tab_rejected: 'Abgelehnt',
    status_draft: 'Entwurf', status_submitted: 'Eingereicht',
    status_under_review: 'In Prüfung', status_approved: 'Genehmigt',
    status_rejected: 'Abgelehnt',
  },
  np: {
    title: 'सदस्यता आवेदनहरू',
    col_applicant: 'आवेदक', col_type: 'प्रकार', col_status: 'स्थिति',
    col_submitted: 'पेश गरिएको', col_expires: 'म्याद सकिन्छ', col_actions: 'कार्यहरू',
    review: 'समीक्षा',
    no_applications: 'कुनै आवेदन फेला परेन।',
    page: 'पृष्ठ', prev: '← अघिल्लो', next: 'अर्को →',
    tab_all: 'सबै', tab_pending: 'विचाराधीन', tab_approved: 'स्वीकृत',
    tab_rejected: 'अस्वीकृत',
    status_draft: 'मस्यौदा', status_submitted: 'पेश गरिएको',
    status_under_review: 'समीक्षामा', status_approved: 'स्वीकृत',
    status_rejected: 'अस्वीकृत',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── Filter tabs ───────────────────────────────────────────────────────────────

const filterStatus = ref('all')

const tabs = computed(() => [
  { value: 'all',     label: t.value.tab_all },
  { value: 'pending', label: t.value.tab_pending },
  { value: 'approved',label: t.value.tab_approved },
  { value: 'rejected',label: t.value.tab_rejected },
])

const filteredApplications = computed(() => {
  const all = props.applications.data ?? []
  if (filterStatus.value === 'all')     return all
  if (filterStatus.value === 'pending') return all.filter(a => ['submitted','under_review','draft'].includes(a.status))
  if (filterStatus.value === 'approved')return all.filter(a => a.status === 'approved')
  if (filterStatus.value === 'rejected')return all.filter(a => a.status === 'rejected')
  return all
})

const statusCounts = computed(() => {
  const all = props.applications.data ?? []
  return {
    all:      all.length,
    pending:  all.filter(a => ['submitted','under_review','draft'].includes(a.status)).length,
    approved: all.filter(a => a.status === 'approved').length,
    rejected: all.filter(a => a.status === 'rejected').length,
  }
})

// ── Helpers ───────────────────────────────────────────────────────────────────

const statusLabel = (status) => {
  const map = {
    draft: t.value.status_draft, submitted: t.value.status_submitted,
    under_review: t.value.status_under_review, approved: t.value.status_approved,
    rejected: t.value.status_rejected,
  }
  return map[status] ?? status
}

const statusClass = (status) => {
  const map = {
    draft:        'bg-neutral-100 text-neutral-600',
    submitted:    'bg-primary-100 text-primary-700',
    under_review: 'bg-yellow-100 text-yellow-700',
    approved:     'bg-green-100 text-green-800',
    rejected:     'bg-danger-100 text-danger-700',
  }
  return map[status] ?? 'bg-neutral-100 text-neutral-600'
}

const formatDate = (val) => {
  if (!val) return '—'
  return new Date(val).toLocaleDateString(locale.value === 'np' ? 'en-NP' : locale.value, {
    day: '2-digit', month: 'short', year: 'numeric'
  })
}

const isExpiringSoon = (val) => {
  if (!val) return false
  const diff = new Date(val) - new Date()
  return diff > 0 && diff < 7 * 24 * 60 * 60 * 1000 // within 7 days
}
</script>

