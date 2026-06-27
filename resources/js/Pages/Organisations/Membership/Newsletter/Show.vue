<template>
  <PublicDigitLayout>
    <div class="max-w-4xl mx-auto py-8 px-4">

      <!-- Flash -->
      <div v-if="page.props.flash?.success"
           class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
        <CheckCircleIcon class="w-4 h-4 flex-shrink-0" />
        {{ page.props.flash.success }}
      </div>
      <div v-if="page.props.errors?.state || page.props.errors?.error"
           class="mb-6 rounded-lg bg-danger-50 border border-danger-200 p-4 text-danger-800 text-sm">
        {{ page.props.errors?.state ?? page.props.errors?.error }}
      </div>

      <!-- Header -->
      <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
          <nav class="flex items-center gap-2 text-sm text-slate-500 mb-1">
            <a :href="route('organisations.membership.dashboard', organisation.slug)"
               class="hover:text-purple-600 transition-colors">{{ organisation.name }}</a>
            <span>/</span>
            <a :href="route('organisations.membership.newsletters.index', organisation.slug)"
               class="hover:text-purple-600 transition-colors">{{ t.newsletters }}</a>
            <span>/</span>
            <span class="text-slate-800 font-medium truncate max-w-xs">{{ newsletter.subject }}</span>
          </nav>
          <h1 class="text-xl font-bold text-slate-900 leading-snug">{{ newsletter.subject }}</h1>
          <div class="flex items-center gap-3 mt-2">
            <span :class="statusClass" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold">
              {{ t['status_' + newsletter.status] ?? newsletter.status }}
            </span>
            <span class="text-xs text-slate-400">{{ formatDate(newsletter.created_at) }}</span>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 flex-shrink-0">
          <a v-if="newsletter.status === 'draft'"
             :href="route('organisations.membership.newsletters.edit', [organisation.slug, newsletter.id])"
             class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm
                    font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <PencilSquareIcon class="w-4 h-4" />
            {{ t.edit }}
          </a>
          <button v-if="newsletter.status === 'draft'" @click="sendNewsletter"
                  :disabled="sending"
                  class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold
                         text-white hover:bg-purple-700 transition-colors disabled:opacity-50">
            <PaperAirplaneIcon class="w-4 h-4" />
            {{ sending ? t.sending : t.send }}
          </button>
          <button v-if="['draft','processing'].includes(newsletter.status)" @click="cancelNewsletter"
                  class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm
                         font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <XCircleIcon class="w-4 h-4" />
            {{ t.cancel }}
          </button>
        </div>
      </div>

      <!-- Stats row -->
      <div v-if="newsletter.total_recipients > 0"
           class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
          <p class="text-2xl font-bold text-slate-900">{{ newsletter.total_recipients }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ t.total }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
          <p class="text-2xl font-bold text-green-600">{{ newsletter.sent_count }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ t.sent }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
          <p class="text-2xl font-bold text-danger-500">{{ newsletter.failed_count }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ t.failed }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
          <p class="text-2xl font-bold text-slate-400">{{ newsletter.total_recipients - newsletter.sent_count - newsletter.failed_count }}</p>
          <p class="text-xs text-slate-400 mt-0.5">{{ t.pending }}</p>
        </div>
      </div>

      <!-- Attachments -->
      <div v-if="newsletter.attachments?.length" class="mb-6">
        <h2 class="text-sm font-semibold text-slate-700 mb-3">{{ t.attachments }}</h2>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm divide-y divide-slate-100">
          <div v-for="att in newsletter.attachments" :key="att.id"
               class="flex items-center gap-3 px-4 py-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 :class="attachmentIconBg(att.mime_type)">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-sm font-medium text-slate-800 truncate">{{ att.original_name }}</p>
              <p class="text-xs text-slate-400">{{ formatFileSize(att.size) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Audience Details Card -->
      <div class="rounded-lg bg-white border border-slate-200 shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ t.audience }}</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
          <div>
            <p class="text-sm text-slate-500">{{ t.audience_type }}</p>
            <span :class="audienceBadgeClass(newsletter.audience_type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold mt-1">
              {{ getAudienceLabel(newsletter.audience_type) }}
            </span>
          </div>
          <div v-if="newsletter.audience_meta?.election_id">
            <p class="text-sm text-slate-500">{{ t.election }}</p>
            <p class="font-medium mt-1 text-sm">{{ getElectionName(newsletter.audience_meta.election_id) }}</p>
          </div>
          <div>
            <p class="text-sm text-slate-500">{{ t.total_recipients }}</p>
            <p class="font-medium mt-1 text-sm">{{ newsletter.total_recipients?.toLocaleString() || '—' }}</p>
          </div>
        </div>
      </div>

      <!-- Preview count (draft only) -->
      <div v-if="newsletter.status === 'draft' && recipientCount !== null"
           class="mb-6 rounded-lg bg-purple-50 border border-purple-200 px-4 py-3 text-sm text-purple-800 flex items-center gap-2">
        <UsersIcon class="w-4 h-4 flex-shrink-0" />
        {{ t.will_send_to }} <strong class="mx-1">{{ recipientCount }}</strong> {{ t.members }}
      </div>

      <!-- Progress bar (processing) -->
      <div v-if="newsletter.status === 'processing' && newsletter.total_recipients > 0" class="mb-6">
        <div class="flex justify-between text-xs text-slate-500 mb-1">
          <span>{{ t.progress }}</span>
          <span>{{ progressPct }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-2">
          <div class="bg-purple-500 h-2 rounded-full transition-all" :style="{ width: progressPct + '%' }" />
        </div>
      </div>

      <!-- Audit log -->
      <div v-if="newsletter.audit_logs?.length" class="mb-6">
        <h2 class="text-sm font-semibold text-slate-700 mb-3">{{ t.audit_log }}</h2>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm divide-y divide-slate-100">
          <div v-for="log in newsletter.audit_logs" :key="log.id"
               class="flex items-start gap-3 px-4 py-3">
            <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0"
                 :class="log.action === 'completed' ? 'bg-green-500' : log.action === 'failed' ? 'bg-danger-500' : 'bg-purple-400'" />
            <div class="min-w-0">
              <p class="text-sm text-slate-800 capitalize font-medium">{{ t['action_' + log.action] ?? log.action }}</p>
              <p class="text-xs text-slate-400">{{ formatDate(log.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { CheckCircleIcon, XCircleIcon, PaperAirplaneIcon, UsersIcon, PencilSquareIcon } from '@heroicons/vue/24/outline'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  newsletter:   { type: Object, required: true },
  elections:    { type: Array, default: () => [] },
  stats:        { type: Object, default: () => ({}) },
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
    newsletters: 'Newsletters',
    audience: 'Audience',
    audience_type: 'Type',
    election: 'Election',
    total_recipients: 'Total Recipients',
    attachments: 'Attachments',
    edit: 'Edit Draft',
    send: 'Send to Members', sending: 'Sending…', cancel: 'Cancel Campaign',
    total: 'Total', sent: 'Sent', failed: 'Failed', pending: 'Pending',
    progress: 'Delivery progress',
    will_send_to: 'This newsletter will be sent to', members: 'active members.',
    audit_log: 'Audit Log',
    action_created: 'Created', action_dispatched: 'Dispatched', action_cancelled: 'Cancelled',
    action_completed: 'Completed', action_failed: 'Failed',
    status_draft: 'Draft', status_queued: 'Queued', status_processing: 'Sending',
    status_completed: 'Completed', status_failed: 'Failed', status_cancelled: 'Cancelled',
  },
  de: {
    newsletters: 'Newsletter',
    audience: 'Zielgruppe',
    audience_type: 'Typ',
    election: 'Wahl',
    total_recipients: 'Gesamtempfänger',
    attachments: 'Anhänge',
    edit: 'Entwurf bearbeiten',
    send: 'An Mitglieder senden', sending: 'Wird gesendet…', cancel: 'Kampagne abbrechen',
    total: 'Gesamt', sent: 'Gesendet', failed: 'Fehlgeschlagen', pending: 'Ausstehend',
    progress: 'Zustellungsfortschritt',
    will_send_to: 'Dieser Newsletter wird an', members: 'aktive Mitglieder gesendet.',
    audit_log: 'Audit-Protokoll',
    action_created: 'Erstellt', action_dispatched: 'Versandt', action_cancelled: 'Abgebrochen',
    action_completed: 'Abgeschlossen', action_failed: 'Fehlgeschlagen',
    status_draft: 'Entwurf', status_queued: 'In Warteschlange', status_processing: 'Wird gesendet',
    status_completed: 'Abgeschlossen', status_failed: 'Fehlgeschlagen', status_cancelled: 'Abgebrochen',
  },
  np: {
    newsletters: 'न्युजलेटर',
    audience: 'दर्शक',
    audience_type: 'किसिम',
    election: 'चुनाव',
    total_recipients: 'कुल प्राप्तकर्ता',
    attachments: 'संलग्नकहरू',
    edit: 'मस्यौदा सम्पादन गर्नुहोस्',
    send: 'सदस्यहरूलाई पठाउनुहोस्', sending: 'पठाउँदै…', cancel: 'अभियान रद्द गर्नुहोस्',
    total: 'जम्मा', sent: 'पठाइएको', failed: 'असफल', pending: 'बाँकी',
    progress: 'वितरण प्रगति',
    will_send_to: 'यो न्युजलेटर', members: 'सक्रिय सदस्यहरूलाई पठाइनेछ।',
    audit_log: 'अडिट लग',
    action_created: 'सिर्जना', action_dispatched: 'पठाइयो', action_cancelled: 'रद्द',
    action_completed: 'सम्पन्न', action_failed: 'असफल',
    status_draft: 'मस्यौदा', status_queued: 'पंक्तिमा', status_processing: 'पठाउँदै',
    status_completed: 'सम्पन्न', status_failed: 'असफल', status_cancelled: 'रद्द',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

const sending = ref(false)
const recipientCount = ref(null)

const statusClass = computed(() => ({
  draft:      'bg-slate-100 text-slate-600',
  queued:     'bg-primary-100 text-primary-700',
  processing: 'bg-yellow-100 text-yellow-700',
  completed:  'bg-green-100 text-green-700',
  failed:     'bg-danger-100 text-danger-700',
  cancelled:  'bg-slate-100 text-slate-500',
}[props.newsletter.status] ?? 'bg-slate-100 text-slate-500'))

const progressPct = computed(() => {
  if (!props.newsletter.total_recipients) return 0
  return Math.round((props.newsletter.sent_count + props.newsletter.failed_count) / props.newsletter.total_recipients * 100)
})

const formatFileSize = (bytes) => {
  if (!bytes) return '0 B'
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / 1048576).toFixed(1) + ' MB'
}

const attachmentIconBg = (mime) => {
  if (mime === 'application/pdf')       return 'bg-danger-100 text-danger-600'
  if (mime?.startsWith('image/'))       return 'bg-green-100 text-green-600'
  if (mime?.includes('word'))           return 'bg-primary-100 text-primary-600'
  if (mime?.includes('excel') || mime?.includes('spreadsheet')) return 'bg-emerald-100 text-emerald-600'
  return 'bg-slate-100 text-slate-500'
}

const formatDate = (iso) => {
  if (!iso) return '—'
  return new Date(iso).toLocaleString(undefined, { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

const getAudienceLabel = (type) => {
  return audienceLabels[type] || type
}

const audienceBadgeClass = (type) => {
  const classes = {
    all_members: 'bg-primary-100 text-primary-800',
    members_full: 'bg-green-100 text-green-800',
    members_associate: 'bg-purple-100 text-purple-800',
    members_overdue: 'bg-orange-100 text-orange-800',
    election_voters: 'bg-indigo-100 text-indigo-800',
    election_not_voted: 'bg-yellow-100 text-yellow-800',
    election_voted: 'bg-green-100 text-green-800',
    election_candidates: 'bg-danger-100 text-danger-800',
    election_observers: 'bg-cyan-100 text-cyan-800',
    election_committee: 'bg-rose-100 text-rose-800',
    election_all: 'bg-violet-100 text-violet-800',
    org_participants_staff: 'bg-amber-100 text-amber-800',
    org_participants_guests: 'bg-lime-100 text-lime-800',
    org_admins: 'bg-fuchsia-100 text-fuchsia-800',
  }
  return classes[type] || 'bg-neutral-100 text-neutral-800'
}

const getElectionName = (electionId) => {
  const election = props.elections?.find(e => e.id === electionId)
  return election?.name || electionId
}

onMounted(async () => {
  if (props.newsletter.status !== 'draft') return
  try {
    const res = await fetch(route('organisations.membership.newsletters.preview', [props.organisation.slug, props.newsletter.id]))
    const data = await res.json()
    recipientCount.value = data.count ?? null
  } catch {}
})

const sendNewsletter = () => {
  if (! confirm('Send this newsletter to all active members? This cannot be undone.')) return
  sending.value = true
  router.patch(
    route('organisations.membership.newsletters.send', [props.organisation.slug, props.newsletter.id]),
    {},
    { onFinish: () => { sending.value = false } }
  )
}

const cancelNewsletter = () => {
  if (! confirm('Cancel this campaign? Emails already sent will not be recalled.')) return
  router.patch(route('organisations.membership.newsletters.cancel', [props.organisation.slug, props.newsletter.id]))
}
</script>

