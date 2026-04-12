<template>
  <PublicDigitLayout>
    <div class="max-w-3xl mx-auto py-8 px-4">

      <!-- Flash -->
      <div v-if="page.props.flash?.success"
           class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ page.props.flash.success }}
      </div>
      <div v-if="page.props.errors?.error"
           class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
        {{ page.props.errors.error }}
      </div>

      <!-- Back link -->
      <a
        :href="route('organisations.membership.applications.index', organisation.slug)"
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-6"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        {{ t.back }}
      </a>

      <!-- Public application badge -->
      <div v-if="isPublicApp" class="mb-4 inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
        </svg>
        {{ t.public_badge }}
      </div>

      <!-- Status banner -->
      <div
        class="rounded-lg p-4 mb-6 flex items-center gap-3 text-sm font-medium"
        :class="statusBannerClass"
      >
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path v-if="application.status === 'approved'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          <path v-else-if="application.status === 'rejected'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ statusLabel(application.status) }}</span>
        <span v-if="application.reviewed_at" class="ml-auto text-xs font-normal opacity-75">
          {{ t.reviewed_by }} {{ application.reviewer?.name ?? '—' }}
          {{ t.on }} {{ formatDate(application.reviewed_at) }}
        </span>
      </div>

      <!-- Application detail card -->
      <div class="bg-white rounded-xl shadow divide-y divide-gray-100">

        <!-- Applicant -->
        <div class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.section_applicant }}</h2>
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-lg font-bold flex-shrink-0">
              {{ isPublicApp
                  ? initials((application.application_data?.first_name ?? '') + ' ' + (application.application_data?.last_name ?? ''))
                  : initials(application.user?.name) }}
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-900">
                {{ isPublicApp
                    ? ((application.application_data?.first_name ?? '') + ' ' + (application.application_data?.last_name ?? '')).trim()
                    : application.user?.name }}
              </p>
              <p class="text-sm text-gray-500">
                {{ isPublicApp ? application.applicant_email : application.user?.email }}
              </p>
            </div>
          </div>
        </div>

        <!-- Public application details panel -->
        <div v-if="isPublicApp" class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.section_public_details }}</h2>
          <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div v-if="application.application_data?.telephone_number">
              <dt class="text-xs text-gray-400">Phone</dt>
              <dd class="text-sm font-medium text-gray-800 mt-0.5">{{ application.application_data.telephone_number }}</dd>
            </div>
            <div v-if="application.application_data?.education_level">
              <dt class="text-xs text-gray-400">Education</dt>
              <dd class="text-sm font-medium text-gray-800 mt-0.5">{{ application.application_data.education_level }}</dd>
            </div>
            <div v-if="application.application_data?.profession">
              <dt class="text-xs text-gray-400">Profession</dt>
              <dd class="text-sm font-medium text-gray-800 mt-0.5">{{ application.application_data.profession }}</dd>
            </div>
            <div v-if="application.application_data?.city">
              <dt class="text-xs text-gray-400">City</dt>
              <dd class="text-sm font-medium text-gray-800 mt-0.5">{{ application.application_data.city }}</dd>
            </div>
            <div v-if="application.application_data?.country">
              <dt class="text-xs text-gray-400">Country</dt>
              <dd class="text-sm font-medium text-gray-800 mt-0.5">{{ application.application_data.country }}</dd>
            </div>
            <div v-if="application.application_data?.message" class="sm:col-span-2">
              <dt class="text-xs text-gray-400">Message</dt>
              <dd class="text-sm font-medium text-gray-800 mt-0.5 whitespace-pre-wrap">{{ application.application_data.message }}</dd>
            </div>
          </dl>
        </div>

        <!-- Membership type -->
        <div class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.section_type }}</h2>
          <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
            <div>
              <p class="text-xs text-gray-400 mb-0.5">{{ t.type_name }}</p>
              <p class="text-sm font-medium text-gray-800">{{ application.membership_type?.name ?? '—' }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-0.5">{{ t.type_fee }}</p>
              <p class="text-sm font-medium text-gray-800">
                {{ application.membership_type?.fee_amount }} {{ application.membership_type?.fee_currency }}
              </p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-0.5">{{ t.type_duration }}</p>
              <p class="text-sm font-medium text-gray-800">
                {{ application.membership_type?.duration_months
                    ? application.membership_type.duration_months + ' ' + t.months
                    : t.lifetime }}
              </p>
            </div>
          </div>
        </div>

        <!-- Application dates -->
        <div class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.section_dates }}</h2>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-400 mb-0.5">{{ t.submitted_at }}</p>
              <p class="text-sm font-medium text-gray-800">{{ formatDate(application.submitted_at) }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-0.5">{{ t.expires_at }}</p>
              <p class="text-sm font-medium" :class="isExpired ? 'text-red-600' : 'text-gray-800'">
                {{ formatDate(application.expires_at) }}
                <span v-if="isExpired" class="ml-1 text-xs">({{ t.expired }})</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Application data (custom fields) -->
        <div v-if="application.application_data && Object.keys(application.application_data).length > 0" class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.section_data }}</h2>
          <dl class="space-y-2">
            <div v-for="(value, key) in application.application_data" :key="key" class="flex gap-4">
              <dt class="text-sm text-gray-500 w-36 flex-shrink-0 capitalize">{{ key.replace(/_/g, ' ') }}</dt>
              <dd class="text-sm font-medium text-gray-800">{{ value }}</dd>
            </div>
          </dl>
        </div>

        <!-- Rejection reason (if rejected) -->
        <div v-if="application.status === 'rejected' && application.rejection_reason" class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.rejection_reason }}</h2>
          <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ application.rejection_reason }}</p>
        </div>

        <!-- Notes -->
        <div v-if="application.notes" class="px-6 py-5">
          <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ t.notes }}</h2>
          <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ application.notes }}</p>
        </div>

      </div>

      <!-- ── Action panel (only for pending applications) ── -->
      <div v-if="isPending" class="mt-6 bg-white rounded-xl shadow p-6 space-y-5">
        <h2 class="text-base font-semibold text-gray-900">{{ t.action_title }}</h2>

        <!-- Membership type selector (required for public applications) -->
        <div v-if="isPublicApp" class="p-4 rounded-lg border border-amber-200 bg-amber-50 space-y-2">
          <label class="text-sm font-medium text-amber-900 block">{{ t.select_type_label }} <span class="text-red-500">*</span></label>
          <select
            v-model="selectedTypeId"
            class="w-full rounded-md border border-amber-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white"
          >
            <option value="">{{ t.select_type_placeholder }}</option>
            <option v-for="type in types" :key="type.id" :value="type.id">
              {{ type.name }} — {{ type.fee_amount }} {{ type.fee_currency }}
            </option>
          </select>
          <p v-if="page.props.errors?.membership_type_id" class="text-xs text-red-700">{{ page.props.errors.membership_type_id }}</p>
        </div>

        <!-- Approve -->
        <div class="flex items-center justify-between p-4 rounded-lg border border-green-200 bg-green-50">
          <div>
            <p class="text-sm font-medium text-green-900">{{ t.approve_label }}</p>
            <p class="text-xs text-green-700 mt-0.5">{{ t.approve_hint }}</p>
          </div>
          <button
            @click="approve"
            :disabled="approving || rejecting || (isPublicApp && !selectedTypeId)"
            class="ml-4 flex-shrink-0 inline-flex items-center gap-2 rounded-md bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="approving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ approving ? t.approving : t.approve_btn }}
          </button>
        </div>

        <!-- Reject -->
        <div class="p-4 rounded-lg border border-red-200 bg-red-50 space-y-3">
          <div>
            <p class="text-sm font-medium text-red-900">{{ t.reject_label }}</p>
            <p class="text-xs text-red-700 mt-0.5">{{ t.reject_hint }}</p>
          </div>
          <textarea
            v-model="rejectionReason"
            rows="3"
            :placeholder="t.rejection_reason_placeholder"
            class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"
            :class="rejectError ? 'border-red-400' : 'border-red-200'"
          />
          <p v-if="rejectError" class="text-xs text-red-700">{{ rejectError }}</p>
          <div class="flex justify-end">
            <button
              @click="reject"
              :disabled="approving || rejecting"
              class="inline-flex items-center gap-2 rounded-md bg-red-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="rejecting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
              {{ rejecting ? t.rejecting : t.reject_btn }}
            </button>
          </div>
        </div>
      </div>

    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  application:  { type: Object, required: true },
  types:        { type: Array, default: () => [] },
})

const page = usePage()
const { locale } = useI18n()

// ── i18n ──────────────────────────────────────────────────────────────────────

const translations = {
  en: {
    back: 'Back to Applications',
    public_badge: 'Public Application',
    section_public_details: 'Submitted Details',
    section_applicant: 'Applicant', section_type: 'Membership Type',
    select_type_label: 'Assign Membership Type',
    select_type_placeholder: '— select a type —',
    select_type_required: 'Please select a membership type before approving.',
    section_dates: 'Timeline', section_data: 'Application Data',
    type_name: 'Type', type_fee: 'Fee', type_duration: 'Duration',
    months: 'months', lifetime: 'Lifetime',
    submitted_at: 'Submitted', expires_at: 'Application Expires',
    expired: 'Expired', rejection_reason: 'Rejection Reason', notes: 'Notes',
    reviewed_by: 'Reviewed by', on: 'on',
    action_title: 'Review Decision',
    approve_label: 'Approve Application',
    approve_hint: 'Creates member record, organisation role, and a pending fee.',
    approve_btn: 'Approve', approving: 'Approving…',
    reject_label: 'Reject Application',
    reject_hint: 'The applicant will be notified with your reason.',
    reject_btn: 'Reject', rejecting: 'Rejecting…',
    rejection_reason_placeholder: 'Provide a clear reason for rejection (required)…',
    rejection_reason_required: 'Please enter a rejection reason.',
    status_draft: 'Draft', status_submitted: 'Submitted',
    status_under_review: 'Under Review', status_approved: 'Approved',
    status_rejected: 'Rejected',
  },
  de: {
    back: 'Zurück zu den Anträgen',
    public_badge: 'Öffentlicher Antrag',
    section_public_details: 'Eingereichte Details',
    section_applicant: 'Antragsteller', section_type: 'Mitgliedschaftstyp',
    select_type_label: 'Mitgliedschaftstyp zuweisen',
    select_type_placeholder: '— Typ auswählen —',
    select_type_required: 'Bitte wählen Sie einen Mitgliedschaftstyp vor der Genehmigung.',
    section_dates: 'Zeitplan', section_data: 'Antragsdaten',
    type_name: 'Typ', type_fee: 'Gebühr', type_duration: 'Dauer',
    months: 'Monate', lifetime: 'Lebenslang',
    submitted_at: 'Eingereicht', expires_at: 'Antrag läuft ab',
    expired: 'Abgelaufen', rejection_reason: 'Ablehnungsgrund', notes: 'Notizen',
    reviewed_by: 'Geprüft von', on: 'am',
    action_title: 'Entscheidung treffen',
    approve_label: 'Antrag genehmigen',
    approve_hint: 'Erstellt Mitgliedsdatensatz, Organisationsrolle und eine ausstehende Gebühr.',
    approve_btn: 'Genehmigen', approving: 'Wird genehmigt…',
    reject_label: 'Antrag ablehnen',
    reject_hint: 'Der Antragsteller wird mit Ihrem Grund benachrichtigt.',
    reject_btn: 'Ablehnen', rejecting: 'Wird abgelehnt…',
    rejection_reason_placeholder: 'Geben Sie einen klaren Ablehnungsgrund an (erforderlich)…',
    rejection_reason_required: 'Bitte geben Sie einen Ablehnungsgrund ein.',
    status_draft: 'Entwurf', status_submitted: 'Eingereicht',
    status_under_review: 'In Prüfung', status_approved: 'Genehmigt',
    status_rejected: 'Abgelehnt',
  },
  np: {
    back: 'आवेदनहरूमा फर्कनुहोस्',
    public_badge: 'सार्वजनिक आवेदन',
    section_public_details: 'पेश गरिएका विवरणहरू',
    section_applicant: 'आवेदक', section_type: 'सदस्यता प्रकार',
    select_type_label: 'सदस्यता प्रकार तोक्नुहोस्',
    select_type_placeholder: '— प्रकार छान्नुहोस् —',
    select_type_required: 'स्वीकृति दिनुअघि कृपया सदस्यता प्रकार छान्नुहोस्।',
    section_dates: 'समयरेखा', section_data: 'आवेदन डेटा',
    type_name: 'प्रकार', type_fee: 'शुल्क', type_duration: 'अवधि',
    months: 'महिना', lifetime: 'आजीवन',
    submitted_at: 'पेश गरिएको', expires_at: 'आवेदन म्याद',
    expired: 'म्याद सकियो', rejection_reason: 'अस्वीकृतिको कारण', notes: 'नोटहरू',
    reviewed_by: 'समीक्षक', on: 'मा',
    action_title: 'समीक्षा निर्णय',
    approve_label: 'आवेदन स्वीकृत गर्नुहोस्',
    approve_hint: 'सदस्य रेकर्ड, संस्था भूमिका, र विचाराधीन शुल्क बनाउँछ।',
    approve_btn: 'स्वीकृत गर्नुहोस्', approving: 'स्वीकृत गर्दै…',
    reject_label: 'आवेदन अस्वीकार गर्नुहोस्',
    reject_hint: 'आवेदकलाई तपाईंको कारण सहित सूचित गरिनेछ।',
    reject_btn: 'अस्वीकार गर्नुहोस्', rejecting: 'अस्वीकार गर्दै…',
    rejection_reason_placeholder: 'अस्वीकृतिको स्पष्ट कारण लेख्नुहोस् (आवश्यक)…',
    rejection_reason_required: 'कृपया अस्वीकृतिको कारण लेख्नुहोस्।',
    status_draft: 'मस्यौदा', status_submitted: 'पेश गरिएको',
    status_under_review: 'समीक्षामा', status_approved: 'स्वीकृत',
    status_rejected: 'अस्वीकृत',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── State ─────────────────────────────────────────────────────────────────────

const rejectionReason = ref('')
const rejectError     = ref('')
const approving       = ref(false)
const rejecting       = ref(false)
const selectedTypeId  = ref(props.application.membership_type_id ?? '')

// ── Computed ──────────────────────────────────────────────────────────────────

const isPending = computed(() =>
  ['submitted', 'under_review', 'draft'].includes(props.application.status)
)

const isPublicApp = computed(() => props.application.source === 'public')

const isExpired = computed(() => {
  if (!props.application.expires_at) return false
  return new Date(props.application.expires_at) < new Date()
})

const statusLabel = (status) => {
  const map = {
    draft: t.value.status_draft, submitted: t.value.status_submitted,
    under_review: t.value.status_under_review, approved: t.value.status_approved,
    rejected: t.value.status_rejected,
  }
  return map[status] ?? status
}

const statusBannerClass = computed(() => {
  const map = {
    draft:        'bg-gray-50 border border-gray-200 text-gray-700',
    submitted:    'bg-blue-50 border border-blue-200 text-blue-800',
    under_review: 'bg-yellow-50 border border-yellow-200 text-yellow-800',
    approved:     'bg-green-50 border border-green-200 text-green-800',
    rejected:     'bg-red-50 border border-red-200 text-red-800',
  }
  return map[props.application.status] ?? 'bg-gray-50 border border-gray-200 text-gray-700'
})

// ── Helpers ───────────────────────────────────────────────────────────────────

const initials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
}

const formatDate = (val) => {
  if (!val) return '—'
  return new Date(val).toLocaleDateString(locale.value === 'np' ? 'en-NP' : locale.value, {
    day: '2-digit', month: 'short', year: 'numeric'
  })
}

// ── Actions ───────────────────────────────────────────────────────────────────

const approve = () => {
  approving.value = true
  const payload = isPublicApp.value ? { membership_type_id: selectedTypeId.value } : {}
  router.patch(
    route('organisations.membership.applications.approve', [props.organisation.slug, props.application.id]),
    payload,
    {
      preserveScroll: true,
      onFinish: () => { approving.value = false },
    }
  )
}

const reject = () => {
  rejectError.value = ''
  if (!rejectionReason.value.trim()) {
    rejectError.value = t.value.rejection_reason_required
    return
  }
  rejecting.value = true
  router.patch(
    route('organisations.membership.applications.reject', [props.organisation.slug, props.application.id]),
    { rejection_reason: rejectionReason.value.trim() },
    {
      preserveScroll: true,
      onFinish: () => { rejecting.value = false },
    }
  )
}
</script>
