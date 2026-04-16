<template>
  <PublicDigitLayout :organisation="organisation">
    <!-- Page Header -->
    <div class="space-y-8">
      <!-- Stats Bar - Animated Counters -->
      <div class="grid grid-cols-3 gap-6">
        <StatCard
          :value="stats.outstanding_total"
          :label="t('stats_outstanding')"
          icon="alert-circle"
          color="amber"
          :currency="true"
        />
        <StatCard
          :value="stats.paid_this_month"
          :label="t('stats_paid_month')"
          icon="check-circle"
          color="green"
          :currency="true"
        />
        <StatCard
          :value="stats.overdue_count"
          :label="t('stats_overdue')"
          icon="clock"
          color="red"
          :currency="false"
        />
      </div>

      <!-- Member Header Card -->
      <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <h1 class="text-3xl font-serif font-bold text-gray-900 mb-2">
              {{ member.organisationUser?.user?.name || 'Member' }}
            </h1>
            <p class="text-gray-600 font-mono text-sm">
              {{ member.organisationUser?.user?.email || '—' }}
            </p>
            <div class="mt-4 flex gap-3">
              <BadgeStatus :status="member.fees_status" />
              <span v-if="member.membershipType" class="inline-block px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded border border-blue-200">
                {{ member.membershipType.name }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Outstanding Fees Panel - Amber Accent -->
      <div class="border-l-2 border-amber-400 bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
        <h2 class="text-xl font-serif font-bold text-gray-900 mb-6 flex items-center gap-2">
          <span class="inline-block w-2 h-2 rounded-full bg-amber-400"></span>
          {{ t('outstanding_fees') }}
        </h2>

        <div v-if="outstandingFees.length === 0" class="text-center py-8">
          <p class="text-gray-500">{{ t('no_outstanding') }}</p>
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="fee in outstandingFees"
            :key="fee.id"
            class="flex items-center justify-between p-4 bg-gray-50 rounded border border-gray-100 hover:bg-gray-100 transition-colors"
          >
            <div class="flex-1">
              <p class="text-sm text-gray-600 font-mono">
                {{ fee.period_label || '—' }}
              </p>
              <p class="text-xs text-gray-500 mt-1">
                {{ formatDate(fee.due_date) }} •
                <BadgeStatus :status="fee.status" size="sm" />
              </p>
            </div>
            <div class="text-right">
              <p class="text-lg font-mono font-bold text-gray-900">
                {{ formatCurrency(fee.amount, fee.currency) }}
              </p>
              <button
                @click="openPaymentDrawer(fee)"
                class="mt-2 px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors"
              >
                {{ t('record_payment') }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment History Panel -->
      <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
        <h2 class="text-xl font-serif font-bold text-gray-900 mb-6">
          {{ t('payment_history') }}
        </h2>

        <div v-if="paymentHistory.length === 0" class="text-center py-8">
          <p class="text-gray-500">{{ t('no_history') }}</p>
        </div>

        <div v-else class="space-y-2">
          <div
            v-for="payment in paymentHistory"
            :key="payment.id"
            class="flex items-center justify-between p-4 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors"
          >
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-3 mb-2">
                <p class="text-sm text-gray-600 font-mono">
                  {{ payment.fee?.period_label || '—' }}
                </p>
                <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-700 text-xs rounded-full font-mono">
                  {{ t(`method_${payment.payment_method}`) || payment.payment_method }}
                </span>
                <span v-if="payment.income_id" class="inline-block px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded font-mono">
                  {{ t('income_linked') }}
                </span>
              </div>
              <p class="text-xs text-gray-500">
                {{ formatDate(payment.paid_at) }}
              </p>
            </div>
            <p class="text-lg font-mono font-bold text-gray-900 ml-4 flex-shrink-0">
              {{ formatCurrency(payment.amount, payment.currency) }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Drawer - Right-side slide panel -->
    <transition
      enter-active-class="transition-transform duration-300"
      leave-active-class="transition-transform duration-300"
      enter-from-class="translate-x-full"
      leave-to-class="translate-x-full"
    >
      <div
        v-if="showPaymentDrawer"
        class="fixed inset-y-0 right-0 w-96 bg-white border-l border-gray-200 shadow-2xl z-50 p-8 overflow-y-auto"
      >
        <div class="flex items-center justify-between mb-8">
          <h3 class="text-2xl font-serif font-bold text-gray-900">
            {{ t('confirm_payment') }}
          </h3>
          <button
            @click="closePaymentDrawer"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <span class="text-2xl">×</span>
          </button>
        </div>

        <form @submit.prevent="submitPayment" class="space-y-6">
          <!-- Selected Fee Info -->
          <div v-if="selectedFee" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-2">{{ selectedFee.period_label }}</p>
            <p class="text-2xl font-mono font-bold text-gray-900">
              {{ formatCurrency(selectedFee.amount, selectedFee.currency) }}
            </p>
          </div>

          <!-- Amount -->
          <div>
            <label class="block text-sm font-mono text-gray-700 mb-2">
              {{ t('field_amount') }} *
            </label>
            <input
              v-model.number="form.amount"
              type="number"
              step="0.01"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded font-mono text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
              :placeholder="selectedFee ? formatCurrency(selectedFee.amount, 'EUR').replace('EUR', '').trim() : '0.00'"
            />
          </div>

          <!-- Payment Method -->
          <div>
            <label class="block text-sm font-mono text-gray-700 mb-2">
              {{ t('field_method') }} *
            </label>
            <select
              v-model="form.payment_method"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded font-mono text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
            >
              <option value="">— Select —</option>
              <option value="bank_transfer">{{ t('method_bank_transfer') }}</option>
              <option value="cash">{{ t('method_cash') }}</option>
              <option value="card">{{ t('method_card') }}</option>
              <option value="cheque">{{ t('method_cheque') }}</option>
              <option value="online">{{ t('method_online') }}</option>
            </select>
          </div>

          <!-- Reference -->
          <div>
            <label class="block text-sm font-mono text-gray-700 mb-2">
              {{ t('field_reference') }}
            </label>
            <input
              v-model="form.payment_reference"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded font-mono text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
              placeholder="REF-001"
            />
          </div>

          <!-- Errors -->
          <div v-if="form.errors && Object.keys(form.errors).length > 0" class="bg-red-50 border border-red-200 rounded-lg p-4">
            <ul class="list-disc list-inside space-y-1">
              <li v-for="(error, field) in form.errors" :key="field" class="text-sm text-red-700">
                {{ error[0] || error }}
              </li>
            </ul>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full px-6 py-3 bg-blue-600 text-white font-serif font-bold rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
          >
            {{ form.processing ? 'Processing...' : t('confirm_payment') }}
          </button>

          <button
            type="button"
            @click="closePaymentDrawer"
            class="w-full px-6 py-2 border border-gray-300 text-gray-700 font-mono rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
        </form>
      </div>
    </transition>

    <!-- Overlay for drawer -->
    <transition
      enter-active-class="transition-opacity duration-300"
      leave-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      leave-to-class="opacity-0"
    >
      <div
        v-if="showPaymentDrawer"
        @click="closePaymentDrawer"
        class="fixed inset-0 bg-black/20 z-40"
      />
    </transition>

    <!-- Styles -->
    <style scoped>
      @import url('https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500;600&display=swap');

      :root {
        --bg: #fafaf9;
        --ink: #111827;
        --muted: #6b7280;
        --surface: #ffffff;
        --border: #e5e7eb;
        --accent: #1d4ed8;
        --amber: #f59e0b;
      }

      h1, h2, h3 {
        font-family: 'Instrument Serif', serif;
        letter-spacing: -0.02em;
      }

      .font-mono {
        font-family: 'JetBrains Mono', monospace;
      }

      /* Animated Counter */
      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .animate-slide-up {
        animation: slideUp 0.6s ease-out forwards;
      }

      /* Ripple Effect for successful payment */
      @keyframes ripple {
        0% {
          transform: scale(0);
          opacity: 1;
        }
        100% {
          transform: scale(4);
          opacity: 0;
        }
      }

      .ripple::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        background: #10b981;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: ripple 0.6s ease-out;
      }
    </style>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import StatCard from './Finance/StatCard.vue'
import BadgeStatus from './Finance/BadgeStatus.vue'

const props = defineProps({
  organisation: Object,
  member: Object,
  outstandingFees: Array,
  paymentHistory: Array,
  stats: Object,
})

const showPaymentDrawer = ref(false)
const selectedFee = ref(null)
const form = ref({
  amount: null,
  payment_method: '',
  payment_reference: '',
  processing: false,
  errors: {},
})

// Translations
const translations = {
  en: {
    page_title: 'Member Finance',
    stats_outstanding: 'Outstanding Total',
    stats_paid_month: 'Paid This Month',
    stats_overdue: 'Overdue Count',
    outstanding_fees: 'Outstanding Fees',
    no_outstanding: 'No outstanding fees',
    payment_history: 'Payment History',
    no_history: 'No payment history yet',
    record_payment: 'Record Payment',
    field_amount: 'Amount',
    field_method: 'Payment Method',
    field_reference: 'Reference',
    method_bank_transfer: 'Bank Transfer',
    method_cash: 'Cash',
    method_card: 'Card',
    method_cheque: 'Cheque',
    method_online: 'Online',
    confirm_payment: 'Confirm Payment',
    payment_recorded: 'Payment recorded successfully',
    income_linked: 'Income linked',
  },
  de: {
    page_title: 'Mitglied Finanzen',
    stats_outstanding: 'Ausstehend Gesamt',
    stats_paid_month: 'Diesen Monat bezahlt',
    stats_overdue: 'Überfällig Anzahl',
    outstanding_fees: 'Ausstehende Gebühren',
    no_outstanding: 'Keine ausstehenden Gebühren',
    payment_history: 'Zahlungsverlauf',
    no_history: 'Noch kein Zahlungsverlauf',
    record_payment: 'Zahlung erfassen',
    field_amount: 'Betrag',
    field_method: 'Zahlungsmethode',
    field_reference: 'Referenz',
    method_bank_transfer: 'Banküberweisung',
    method_cash: 'Bargeld',
    method_card: 'Karte',
    method_cheque: 'Scheck',
    method_online: 'Online',
    confirm_payment: 'Zahlung bestätigen',
    payment_recorded: 'Zahlung erfolgreich erfasst',
    income_linked: 'Einkommen verlinkt',
  },
  np: {
    page_title: 'सदस्य वित्त',
    stats_outstanding: 'बकाया कुल',
    stats_paid_month: 'यस महिना भुक्तानी',
    stats_overdue: 'अतिक्रमण गणना',
    outstanding_fees: 'बकाया शुल्क',
    no_outstanding: 'कोई बकाया शुल्क नहीं',
    payment_history: 'भुक्तानी इतिहास',
    no_history: 'अभी भुक्तानी इतिहास नहीं',
    record_payment: 'भुक्तानी रिकर्ड गर्नुहोस्',
    field_amount: 'रकम',
    field_method: 'भुक्तानी विधि',
    field_reference: 'संदर्भ',
    method_bank_transfer: 'बैंक हस्तान्तरण',
    method_cash: 'नगद',
    method_card: 'कार्ड',
    method_cheque: 'चेक',
    method_online: 'अनलाइन',
    confirm_payment: 'भुक्तानी पुष्टि गर्नुहोस्',
    payment_recorded: 'भुक्तानी सफलतापूर्वक रिकर्ड गरियो',
    income_linked: 'आय जोडिएको',
  },
}

// Get current language (default to 'en')
const currentLang = ref(localStorage.getItem('locale') || 'en')

const t = (key) => {
  return translations[currentLang.value]?.[key] || translations.en[key] || key
}

// Format currency with Commit Mono
const formatCurrency = (amount, currency = 'EUR') => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currency,
  }).format(amount)
}

// Format date
const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

// Open payment drawer
const openPaymentDrawer = (fee) => {
  selectedFee.value = fee
  form.value.amount = fee.amount
  form.value.payment_method = ''
  form.value.payment_reference = ''
  form.value.errors = {}
  showPaymentDrawer.value = true
}

// Close payment drawer
const closePaymentDrawer = () => {
  showPaymentDrawer.value = false
  setTimeout(() => {
    selectedFee.value = null
  }, 300)
}

// Submit payment
const submitPayment = () => {
  form.value.processing = true

  router.post(
    route('organisations.members.fees.pay', {
      organisation: props.organisation.slug,
      member: props.member.id,
      fee: selectedFee.value.id,
    }),
    {
      amount: form.value.amount,
      payment_method: form.value.payment_method,
      payment_reference: form.value.payment_reference,
    },
    {
      onSuccess: () => {
        closePaymentDrawer()
        form.value.processing = false
      },
      onError: (errors) => {
        form.value.errors = errors
        form.value.processing = false
      },
    }
  )
}
</script>
