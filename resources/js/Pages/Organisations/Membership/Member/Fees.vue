<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-slate-50">

      <!-- Header -->
      <div class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
          <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5">
            <a :href="route('organisations.show', organisation.slug)" class="hover:text-purple-600 transition-colors">
              {{ organisation.name }}
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a :href="route('organisations.membership.dashboard', organisation.slug)" class="hover:text-purple-600 transition-colors">
              {{ t.breadcrumb_dashboard }}
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-700 font-medium">{{ t.title }}</span>
          </nav>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-13 h-13 rounded-2xl bg-green-600 flex items-center justify-center shadow-sm p-3">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ t.title }}</h1>
              <p class="text-slate-500 text-sm mt-1">
                {{ memberName }}
                <span class="text-slate-300 mx-1">·</span>
                {{ organisation.name }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <!-- Flash / Error -->
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

        <!-- Fee list -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-800">{{ t.fees_title }}</h2>
            <span v-if="pendingCount > 0"
                  class="inline-flex items-center rounded-full bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-0.5">
              {{ pendingCount }} {{ t.pending_badge }}
            </span>
          </div>

          <div v-if="fees.data && fees.data.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_type }}</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_period }}</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_amount }}</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_due }}</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_status }}</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_paid_at }}</th>
                  <th v-if="canManage" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">{{ t.col_actions }}</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="fee in fees.data" :key="fee.id" class="hover:bg-slate-50 transition-colors">
                  <td class="px-6 py-4 text-sm text-slate-700 whitespace-nowrap">
                    {{ fee.membership_type?.name ?? '—' }}
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                    {{ fee.period_label ?? '—' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-slate-800">
                      {{ parseFloat(fee.amount).toFixed(2) }} {{ fee.currency }}
                    </div>
                    <div v-if="fee.fee_amount_at_time && parseFloat(fee.fee_amount_at_time) !== parseFloat(fee.amount)"
                         class="text-xs text-slate-400">
                      {{ t.snapshot }}: {{ parseFloat(fee.fee_amount_at_time).toFixed(2) }} {{ fee.currency_at_time }}
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm whitespace-nowrap"
                      :class="isOverdue(fee) ? 'text-red-600 font-medium' : 'text-slate-500'">
                    {{ fee.due_date ? formatDate(fee.due_date) : '—' }}
                    <span v-if="isOverdue(fee)" class="ml-1 text-xs">({{ t.overdue }})</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="feeStatusClass(fee.status)"
                          class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium">
                      {{ feeStatusLabel(fee.status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap">
                    {{ fee.paid_at ? formatDate(fee.paid_at) : '—' }}
                  </td>
                  <td v-if="canManage" class="px-6 py-4 whitespace-nowrap text-right">
                    <div v-if="fee.status === 'pending'" class="flex items-center justify-end gap-2">
                      <button
                        type="button"
                        @click="openPayModal(fee)"
                        :disabled="processing[fee.id]"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                      >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ t.pay }}
                      </button>
                      <button
                        type="button"
                        @click="waive(fee)"
                        :disabled="processing[fee.id]"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                      >
                        {{ t.waive }}
                      </button>
                    </div>
                    <span v-else class="text-xs text-slate-400">—</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty state -->
          <div v-else class="py-16 text-center text-slate-400 text-sm">
            <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ t.no_fees }}
          </div>

          <!-- Pagination -->
          <div v-if="fees.last_page > 1"
               class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-sm text-slate-600">
            <span>{{ t.page }} {{ fees.current_page }} / {{ fees.last_page }}</span>
            <div class="flex gap-2">
              <a v-if="fees.prev_page_url" :href="fees.prev_page_url"
                 class="px-3 py-1 rounded border border-slate-300 hover:bg-slate-50">← {{ t.prev }}</a>
              <a v-if="fees.next_page_url" :href="fees.next_page_url"
                 class="px-3 py-1 rounded border border-slate-300 hover:bg-slate-50">{{ t.next }} →</a>
            </div>
          </div>
        </div>

      </div>

      <!-- ── Payment Modal ────────────────────────────────────────────────────── -->
      <div v-if="payModal.show"
           class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
           @click.self="closePayModal">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
          <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-slate-900">{{ t.modal_title }}</h3>
            <button type="button" @click="closePayModal" class="text-slate-400 hover:text-slate-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Fee summary -->
          <div class="mb-5 rounded-xl bg-slate-50 border border-slate-200 p-4">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ t.col_type }}</p>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ payModal.fee?.membership_type?.name ?? '—' }}</p>
              </div>
              <div class="text-right">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ t.col_amount }}</p>
                <p class="text-lg font-bold text-green-700 mt-0.5">
                  {{ parseFloat(payModal.fee?.amount ?? 0).toFixed(2) }} {{ payModal.fee?.currency }}
                </p>
              </div>
            </div>
          </div>

          <!-- Payment form -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.payment_method }} <span class="text-red-500">*</span></label>
              <select v-model="payForm.payment_method"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                <option value="">{{ t.select_method }}</option>
                <option value="cash">{{ t.method_cash }}</option>
                <option value="bank_transfer">{{ t.method_bank }}</option>
                <option value="online">{{ t.method_online }}</option>
                <option value="cheque">{{ t.method_cheque }}</option>
                <option value="other">{{ t.method_other }}</option>
              </select>
              <p v-if="payFormError" class="mt-1 text-xs text-red-600">{{ payFormError }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.payment_reference }}</label>
              <input v-model="payForm.payment_reference" type="text"
                     :placeholder="t.reference_placeholder"
                     class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"/>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 mt-6">
            <button type="button" @click="closePayModal"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">
              {{ t.cancel }}
            </button>
            <button type="button"
                    @click="submitPay"
                    :disabled="submittingPay"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
              <svg v-if="submittingPay" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              {{ submittingPay ? t.recording : t.record_payment }}
            </button>
          </div>
        </div>
      </div>

    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  member:       { type: Object, required: true },
  fees:         { type: Object, required: true },
  canManage:    { type: Boolean, default: false },
})

const page    = usePage()
const { locale } = useI18n()

// ── i18n ──────────────────────────────────────────────────────────────────────

const translations = {
  en: {
    breadcrumb_dashboard: 'Membership', title: 'Member Fees',
    fees_title: 'Fee History', pending_badge: 'pending',
    col_type: 'Type', col_period: 'Period', col_amount: 'Amount',
    col_due: 'Due Date', col_status: 'Status', col_paid_at: 'Paid At', col_actions: 'Actions',
    snapshot: 'Rate at time', overdue: 'overdue',
    pay: 'Record Payment', waive: 'Waive',
    no_fees: 'No fees on record.',
    page: 'Page', prev: 'Prev', next: 'Next',
    // Fee statuses
    status_pending: 'Pending', status_paid: 'Paid', status_waived: 'Waived', status_overdue: 'Overdue',
    // Modal
    modal_title: 'Record Payment',
    payment_method: 'Payment Method', select_method: '— Select method —',
    method_cash: 'Cash', method_bank: 'Bank Transfer', method_online: 'Online Payment',
    method_cheque: 'Cheque', method_other: 'Other',
    payment_reference: 'Reference / Note (optional)',
    reference_placeholder: 'e.g. receipt #123',
    method_required: 'Please select a payment method.',
    cancel: 'Cancel', record_payment: 'Record Payment', recording: 'Recording…',
  },
  de: {
    breadcrumb_dashboard: 'Mitgliedschaft', title: 'Mitgliedsbeiträge',
    fees_title: 'Gebührenhistorie', pending_badge: 'ausstehend',
    col_type: 'Typ', col_period: 'Zeitraum', col_amount: 'Betrag',
    col_due: 'Fälligkeitsdatum', col_status: 'Status', col_paid_at: 'Bezahlt am', col_actions: 'Aktionen',
    snapshot: 'Satz zum Zeitpunkt', overdue: 'überfällig',
    pay: 'Zahlung erfassen', waive: 'Erstatten',
    no_fees: 'Keine Gebühren vorhanden.',
    page: 'Seite', prev: 'Zurück', next: 'Weiter',
    status_pending: 'Ausstehend', status_paid: 'Bezahlt', status_waived: 'Erlassen', status_overdue: 'Überfällig',
    modal_title: 'Zahlung erfassen',
    payment_method: 'Zahlungsmethode', select_method: '— Methode wählen —',
    method_cash: 'Bargeld', method_bank: 'Banküberweisung', method_online: 'Online-Zahlung',
    method_cheque: 'Scheck', method_other: 'Sonstiges',
    payment_reference: 'Referenz / Notiz (optional)',
    reference_placeholder: 'z.B. Quittung #123',
    method_required: 'Bitte wählen Sie eine Zahlungsmethode.',
    cancel: 'Abbrechen', record_payment: 'Zahlung erfassen', recording: 'Wird erfasst…',
  },
  np: {
    breadcrumb_dashboard: 'सदस्यता', title: 'सदस्यता शुल्कहरू',
    fees_title: 'शुल्क इतिहास', pending_badge: 'विचाराधीन',
    col_type: 'प्रकार', col_period: 'अवधि', col_amount: 'रकम',
    col_due: 'भुक्तानी मिति', col_status: 'स्थिति', col_paid_at: 'भुक्तानी भएको', col_actions: 'कार्यहरू',
    snapshot: 'त्यस बेलाको दर', overdue: 'म्याद गुज्रिएको',
    pay: 'भुक्तानी दर्ता', waive: 'माफ गर्नुहोस्',
    no_fees: 'कुनै शुल्क दर्ता छैन।',
    page: 'पृष्ठ', prev: 'अघिल्लो', next: 'अर्को',
    status_pending: 'विचाराधीन', status_paid: 'भुक्तानी भयो', status_waived: 'माफ भयो', status_overdue: 'म्याद गुज्रिएको',
    modal_title: 'भुक्तानी दर्ता गर्नुहोस्',
    payment_method: 'भुक्तानी विधि', select_method: '— विधि छान्नुहोस् —',
    method_cash: 'नगद', method_bank: 'बैंक ट्रान्सफर', method_online: 'अनलाइन भुक्तानी',
    method_cheque: 'चेक', method_other: 'अन्य',
    payment_reference: 'सन्दर्भ / टिप्पणी (वैकल्पिक)',
    reference_placeholder: 'उदाहरण: रसिद #१२३',
    method_required: 'कृपया भुक्तानी विधि छान्नुहोस्।',
    cancel: 'रद्द गर्नुहोस्', record_payment: 'भुक्तानी दर्ता गर्नुहोस्', recording: 'दर्ता गर्दै…',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── Computed ──────────────────────────────────────────────────────────────────

const memberName = computed(() => props.member?.organisationUser?.user?.name ?? props.member?.id ?? '—')

const pendingCount = computed(() =>
  (props.fees.data ?? []).filter(f => f.status === 'pending').length
)

// ── State ─────────────────────────────────────────────────────────────────────

const processing   = reactive({})  // per-fee loading state for waive
const submittingPay = ref(false)
const payFormError  = ref('')

const payModal = reactive({ show: false, fee: null })
const payForm  = reactive({ payment_method: '', payment_reference: '' })

// ── Helpers ───────────────────────────────────────────────────────────────────

const formatDate = (val) => {
  if (!val) return '—'
  return new Date(val).toLocaleDateString(locale.value === 'np' ? 'en-NP' : locale.value, {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}

const isOverdue = (fee) =>
  fee.status === 'pending' && fee.due_date && new Date(fee.due_date) < new Date()

const feeStatusLabel = (status) => {
  const map = { pending: t.value.status_pending, paid: t.value.status_paid, waived: t.value.status_waived, overdue: t.value.status_overdue }
  return map[status] ?? status
}

const feeStatusClass = (status) => {
  const map = {
    pending:  'bg-amber-100 text-amber-700',
    paid:     'bg-green-100 text-green-800',
    waived:   'bg-slate-100 text-slate-600',
    overdue:  'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-slate-100 text-slate-600'
}

// ── Actions ───────────────────────────────────────────────────────────────────

const openPayModal = (fee) => {
  payModal.fee  = fee
  payModal.show = true
  payForm.payment_method    = ''
  payForm.payment_reference = ''
  payFormError.value        = ''
}

const closePayModal = () => { payModal.show = false }

const submitPay = () => {
  payFormError.value = ''
  if (!payForm.payment_method) {
    payFormError.value = t.value.method_required
    return
  }

  submittingPay.value = true
  const fee = payModal.fee

  router.post(
    route('organisations.members.fees.pay', [props.organisation.slug, props.member.id, fee.id]),
    {
      payment_method:    payForm.payment_method,
      payment_reference: payForm.payment_reference || null,
      idempotency_key:   crypto.randomUUID(),
    },
    {
      preserveScroll: true,
      onSuccess: () => { closePayModal() },
      onFinish:  () => { submittingPay.value = false },
    }
  )
}

const waive = (fee) => {
  if (!confirm(t.value.status_waived + '?')) return
  processing[fee.id] = true
  router.post(
    route('organisations.members.fees.waive', [props.organisation.slug, props.member.id, fee.id]),
    {},
    {
      preserveScroll: true,
      onFinish: () => { delete processing[fee.id] },
    }
  )
}
</script>
