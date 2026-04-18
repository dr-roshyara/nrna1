<template>
  <PublicDigitLayout>
    <div class="max-w-5xl mx-auto py-8 px-4">

      <!-- Flash -->
      <div v-if="page.props.flash?.success"
           class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
        <CheckCircleIcon class="w-4 h-4 flex-shrink-0" />
        {{ page.props.flash.success }}
      </div>
      <div v-if="page.props.errors?.state || page.props.errors?.error"
           class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
        {{ page.props.errors?.state ?? page.props.errors?.error }}
      </div>

      <!-- Header -->
      <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <nav class="flex items-center gap-2 text-sm text-slate-500 mb-1">
            <a :href="route('organisations.membership.dashboard', organisation.slug)"
               class="hover:text-purple-600 transition-colors">{{ organisation.name }}</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">{{ t.title }}</span>
          </nav>
          <h1 class="text-2xl font-bold text-slate-900">{{ t.title }}</h1>
        </div>
        <div class="flex items-center gap-2">
          <a :href="route('organisations.membership.newsletters.create', organisation.slug)"
             class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-purple-700 transition-colors">
            <PencilSquareIcon class="w-4 h-4" />
            {{ t.compose }}
          </a>
          <a :href="route('guides.newsletter-guide', organisation.slug)"
             target="_blank"
             rel="noopener noreferrer"
             class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-4 py-2.5 text-amber-700 text-sm font-medium hover:bg-amber-100 transition-colors">
            <BookOpenIcon class="w-4 h-4" />
            {{ t.guide_button }}
          </a>
        </div>
      </div>

      <!-- Empty state -->
      <div v-if="!newsletters.data.length"
           class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
        <EnvelopeOpenIcon class="mx-auto w-12 h-12 text-slate-300 mb-4" />
        <h3 class="text-base font-semibold text-slate-700 mb-1">{{ t.empty_title }}</h3>
        <p class="text-sm text-slate-400 mb-6">{{ t.empty_desc }}</p>
        <a :href="route('organisations.membership.newsletters.create', organisation.slug)"
           class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700 transition-colors">
          <PencilSquareIcon class="w-4 h-4" />
          {{ t.compose }}
        </a>
      </div>

      <!-- Newsletter list -->
      <div v-else class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
          <thead class="bg-slate-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ t.col_subject }}</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ t.col_audience }}</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ t.col_status }}</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ t.col_recipients }}</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ t.col_date }}</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="nl in newsletters.data" :key="nl.id" class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-4">
                <p class="text-sm font-medium text-slate-900 truncate max-w-xs">{{ nl.subject }}</p>
              </td>
              <td class="px-5 py-4">
                <span :class="audienceBadgeClass(nl.audience_type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold whitespace-nowrap">
                  {{ getAudienceLabel(nl.audience_type) }}
                </span>
              </td>
              <td class="px-5 py-4">
                <span :class="statusClass(nl.status)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold">
                  {{ t['status_' + nl.status] ?? nl.status }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">
                <span v-if="nl.total_recipients > 0">
                  {{ nl.sent_count }} / {{ nl.total_recipients }}
                </span>
                <span v-else class="text-slate-400">—</span>
              </td>
              <td class="px-5 py-4 text-sm text-slate-500">
                {{ formatDate(nl.created_at) }}
              </td>
              <td class="px-5 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a :href="route('organisations.membership.newsletters.show', [organisation.slug, nl.id])"
                     class="text-xs font-medium text-purple-600 hover:text-purple-800">{{ t.view }}</a>
                  <a v-if="nl.status === 'draft'"
                     :href="route('organisations.membership.newsletters.edit', [organisation.slug, nl.id])"
                     class="text-xs font-medium text-slate-600 hover:text-slate-800">{{ t.edit }}</a>
                  <form v-if="nl.status === 'draft'"
                        @submit.prevent="deleteDraft(nl.id)"
                        class="inline">
                    <button type="submit"
                            class="text-xs font-medium text-red-500 hover:text-red-700">{{ t.delete }}</button>
                  </form>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="newsletters.last_page > 1"
             class="border-t border-slate-200 px-5 py-3 flex items-center justify-between text-sm text-slate-500">
          <span>{{ t.page }} {{ newsletters.current_page }} / {{ newsletters.last_page }}</span>
          <div class="flex gap-2">
            <a v-if="newsletters.prev_page_url" :href="newsletters.prev_page_url"
               class="px-3 py-1 rounded border border-slate-200 hover:bg-slate-50">←</a>
            <a v-if="newsletters.next_page_url" :href="newsletters.next_page_url"
               class="px-3 py-1 rounded border border-slate-200 hover:bg-slate-50">→</a>
          </div>
        </div>
      </div>

    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { BookOpenIcon, CheckCircleIcon, EnvelopeOpenIcon, PencilSquareIcon } from '@heroicons/vue/24/outline'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  newsletters:  { type: Object, required: true }, // paginated
})

const page       = usePage()
const { locale } = useI18n()

const audienceLabels = {
  all_members: 'All Members',
  members_full: 'Full Members',
  members_associate: 'Associate Members',
  members_overdue: 'Members with Overdue Fees',
  election_voters: 'Election Voters',
  election_not_voted: 'Voters Who Haven\'t Voted',
  election_voted: 'Voters Who Already Voted',
  election_candidates: 'Candidates',
  election_observers: 'Observers',
  election_committee: 'Election Committee',
  election_all: 'All Election Participants',
  org_participants_staff: 'Staff',
  org_participants_guests: 'Guests',
  org_admins: 'Organisation Admins',
}

const translations = {
  en: {
    title: 'Newsletters', compose: 'Compose', guide_button: 'User Guide',
    empty_title: 'No newsletters yet', empty_desc: 'Compose your first newsletter to send to all active members.',
    col_subject: 'Subject', col_audience: 'Audience', col_status: 'Status', col_recipients: 'Sent / Total', col_date: 'Date',
    view: 'View', edit: 'Edit', delete: 'Delete', page: 'Page',
    status_draft: 'Draft', status_queued: 'Queued', status_processing: 'Sending',
    status_completed: 'Completed', status_failed: 'Failed', status_cancelled: 'Cancelled',
  },
  de: {
    title: 'Newsletter', compose: 'Verfassen', guide_button: 'Benutzerhandbuch',
    empty_title: 'Noch keine Newsletter', empty_desc: 'Verfassen Sie Ihren ersten Newsletter für alle aktiven Mitglieder.',
    col_subject: 'Betreff', col_audience: 'Zielgruppe', col_status: 'Status', col_recipients: 'Gesendet / Gesamt', col_date: 'Datum',
    view: 'Ansehen', edit: 'Bearbeiten', delete: 'Löschen', page: 'Seite',
    status_draft: 'Entwurf', status_queued: 'In Warteschlange', status_processing: 'Wird gesendet',
    status_completed: 'Abgeschlossen', status_failed: 'Fehlgeschlagen', status_cancelled: 'Abgebrochen',
  },
  np: {
    title: 'न्युजलेटर', compose: 'लेख्नुहोस्', guide_button: 'प्रयोगकर्ता गाइड',
    empty_title: 'अहिलेसम्म कुनै न्युजलेटर छैन', empty_desc: 'सबै सक्रिय सदस्यहरूलाई पठाउन पहिलो न्युजलेटर लेख्नुहोस्।',
    col_subject: 'विषय', col_audience: 'दर्शक', col_status: 'स्थिति', col_recipients: 'पठाइएको / जम्मा', col_date: 'मिति',
    view: 'हेर्नुहोस्', edit: 'सम्पादन', delete: 'मेटाउनुहोस्', page: 'पृष्ठ',
    status_draft: 'मस्यौदा', status_queued: 'पंक्तिमा', status_processing: 'पठाउँदै',
    status_completed: 'सम्पन्न', status_failed: 'असफल', status_cancelled: 'रद्द',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

const statusClass = (status) => ({
  draft:      'bg-slate-100 text-slate-600',
  queued:     'bg-blue-100 text-blue-700',
  processing: 'bg-yellow-100 text-yellow-700',
  completed:  'bg-green-100 text-green-700',
  failed:     'bg-red-100 text-red-700',
  cancelled:  'bg-slate-100 text-slate-500',
}[status] ?? 'bg-slate-100 text-slate-500')

const getAudienceLabel = (type) => {
  return audienceLabels[type] || type
}

const audienceBadgeClass = (type) => {
  const classes = {
    all_members: 'bg-blue-100 text-blue-800',
    members_full: 'bg-green-100 text-green-800',
    members_associate: 'bg-purple-100 text-purple-800',
    members_overdue: 'bg-orange-100 text-orange-800',
    election_voters: 'bg-indigo-100 text-indigo-800',
    election_not_voted: 'bg-yellow-100 text-yellow-800',
    election_voted: 'bg-green-100 text-green-800',
    election_candidates: 'bg-red-100 text-red-800',
    election_observers: 'bg-cyan-100 text-cyan-800',
    election_committee: 'bg-rose-100 text-rose-800',
    election_all: 'bg-violet-100 text-violet-800',
    org_participants_staff: 'bg-amber-100 text-amber-800',
    org_participants_guests: 'bg-lime-100 text-lime-800',
    org_admins: 'bg-fuchsia-100 text-fuchsia-800',
  }
  return classes[type] || 'bg-gray-100 text-gray-800'
}

const formatDate = (iso) => {
  if (!iso) return '—'
  return new Date(iso).toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: 'numeric' })
}

const deleteDraft = (id) => {
  if (! confirm('Delete this draft?')) return
  router.delete(route('organisations.membership.newsletters.destroy', [props.organisation.slug, id]))
}
</script>
