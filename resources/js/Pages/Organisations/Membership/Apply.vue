<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-slate-50">

      <!-- Hero header -->
      <div class="bg-white border-b border-slate-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

          <!-- Breadcrumb -->
          <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5">
            <a :href="route('organisations.show', organisation.slug)"
               class="hover:text-primary-600 transition-colors">
              {{ organisation.name }}
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-700 font-medium">{{ t.breadcrumb }}</span>
          </nav>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-13 h-13 rounded-2xl bg-primary-600 flex items-center justify-center shadow-sm p-3">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                     M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                     m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ t.page_title }}</h1>
              <p class="text-slate-500 text-sm mt-1">
                {{ t.page_subtitle_prefix }}
                <span class="font-medium text-slate-700">{{ organisation.name }}</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Body -->
      <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

        <!-- Flash success -->
        <div v-if="page.props.flash?.success"
             class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ page.props.flash.success }}
        </div>

        <!-- Global errors -->
        <div v-if="page.props.errors?.error"
             class="mb-6 rounded-lg bg-danger-50 border border-danger-200 p-4 text-danger-800 text-sm flex items-center gap-2">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ page.props.errors.error }}
        </div>

        <!-- No types available -->
        <div v-if="types.length === 0"
             class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
          <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <p class="text-slate-500 text-sm">{{ t.no_types }}</p>
        </div>

        <!-- Type selection + submit -->
        <form v-else @submit.prevent="submit">

          <!-- Step 1: Select a type -->
          <div class="mb-6">
            <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wider mb-3">
              {{ t.step1_label }}
            </h2>

            <div class="space-y-3">
              <button
                v-for="type in types"
                :key="type.id"
                type="button"
                @click="selectedTypeId = type.id"
                class="w-full text-left rounded-xl border-2 p-4 transition-all focus:outline-none focus:ring-2 focus:ring-blue-400"
                :class="selectedTypeId === type.id
                  ? 'border-primary-600 bg-primary-50 shadow-sm'
                  : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-sm'"
              >
                <div class="flex items-start gap-4">
                  <!-- Radio indicator -->
                  <div
                    class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                    :class="selectedTypeId === type.id
                      ? 'border-primary-600 bg-primary-600'
                      : 'border-slate-300 bg-white'"
                  >
                    <div v-if="selectedTypeId === type.id" class="w-2 h-2 rounded-full bg-white"></div>
                  </div>

                  <!-- Type info -->
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                      <p class="text-sm font-semibold text-slate-900">{{ type.name }}</p>
                      <div class="flex items-center gap-2 flex-shrink-0">
                        <!-- Fee badge -->
                        <span class="inline-flex items-center rounded-full bg-primary-100 text-primary-800 text-xs font-semibold px-2.5 py-0.5">
                          {{ formatFee(type.fee_amount, type.fee_currency) }}
                        </span>
                        <!-- Duration badge -->
                        <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-0.5">
                          {{ type.duration_months ? type.duration_months + ' ' + t.months : t.lifetime }}
                        </span>
                      </div>
                    </div>
                    <p v-if="type.description" class="text-xs text-slate-500 mt-1 leading-relaxed">
                      {{ type.description }}
                    </p>
                  </div>
                </div>
              </button>
            </div>

            <p v-if="typeError" class="mt-2 text-xs text-danger-600 flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              {{ typeError }}
            </p>
            <p v-if="page.props.errors?.membership_type_id" class="mt-2 text-xs text-danger-600">
              {{ page.props.errors.membership_type_id }}
            </p>
          </div>

          <!-- Selected summary -->
          <div v-if="selectedType"
               class="mb-6 rounded-xl bg-primary-50 border border-primary-200 p-4">
            <p class="text-xs font-semibold text-primary-700 uppercase tracking-wider mb-2">{{ t.summary_title }}</p>
            <div class="grid grid-cols-3 gap-3 text-sm">
              <div>
                <p class="text-xs text-primary-500 mb-0.5">{{ t.summary_type }}</p>
                <p class="font-semibold text-primary-900">{{ selectedType.name }}</p>
              </div>
              <div>
                <p class="text-xs text-primary-500 mb-0.5">{{ t.summary_fee }}</p>
                <p class="font-semibold text-primary-900">{{ formatFee(selectedType.fee_amount, selectedType.fee_currency) }}</p>
              </div>
              <div>
                <p class="text-xs text-primary-500 mb-0.5">{{ t.summary_duration }}</p>
                <p class="font-semibold text-primary-900">
                  {{ selectedType.duration_months ? selectedType.duration_months + ' ' + t.months : t.lifetime }}
                </p>
              </div>
            </div>
            <p v-if="selectedType.requires_approval" class="mt-3 text-xs text-primary-600 flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              {{ t.requires_approval_note }}
            </p>
          </div>

          <!-- Submit -->
          <div class="flex items-center justify-between">
            <a
              :href="route('organisations.show', organisation.slug)"
              class="text-sm text-slate-500 hover:text-slate-700 underline"
            >{{ t.cancel }}</a>

            <button
              type="submit"
              :disabled="submitting"
              class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <svg v-if="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
              </svg>
              {{ submitting ? t.submitting : t.submit_btn }}
            </button>
          </div>

        </form>
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
  types:        { type: Array,  required: true },
})

const page = usePage()
const { locale } = useI18n()

// ── i18n ──────────────────────────────────────────────────────────────────────

const translations = {
  en: {
    breadcrumb: 'Apply for Membership',
    page_title: 'Apply for Membership',
    page_subtitle_prefix: 'Join ',
    step1_label: 'Select a membership type',
    months: 'months', lifetime: 'Lifetime',
    no_types: 'No membership types are currently available. Please contact the organisation.',
    summary_title: 'Your selection',
    summary_type: 'Membership Type', summary_fee: 'Fee', summary_duration: 'Duration',
    requires_approval_note: 'This membership requires admin review before activation.',
    type_required: 'Please select a membership type.',
    submit_btn: 'Submit Application',
    submitting: 'Submitting…',
    cancel: 'Cancel',
  },
  de: {
    breadcrumb: 'Mitgliedschaft beantragen',
    page_title: 'Mitgliedschaft beantragen',
    page_subtitle_prefix: 'Treten Sie bei: ',
    step1_label: 'Wählen Sie einen Mitgliedschaftstyp',
    months: 'Monate', lifetime: 'Lebenslang',
    no_types: 'Derzeit sind keine Mitgliedschaftstypen verfügbar. Bitte kontaktieren Sie die Organisation.',
    summary_title: 'Ihre Auswahl',
    summary_type: 'Mitgliedschaftstyp', summary_fee: 'Gebühr', summary_duration: 'Dauer',
    requires_approval_note: 'Diese Mitgliedschaft erfordert eine Admin-Prüfung vor der Aktivierung.',
    type_required: 'Bitte wählen Sie einen Mitgliedschaftstyp aus.',
    submit_btn: 'Antrag einreichen',
    submitting: 'Wird eingereicht…',
    cancel: 'Abbrechen',
  },
  np: {
    breadcrumb: 'सदस्यताको लागि आवेदन',
    page_title: 'सदस्यताको लागि आवेदन गर्नुहोस्',
    page_subtitle_prefix: 'सामेल हुनुहोस्: ',
    step1_label: 'सदस्यता प्रकार छान्नुहोस्',
    months: 'महिना', lifetime: 'आजीवन',
    no_types: 'हाल कुनै सदस्यता प्रकार उपलब्ध छैन। संस्थालाई सम्पर्क गर्नुहोस्।',
    summary_title: 'तपाईंको छनौट',
    summary_type: 'सदस्यता प्रकार', summary_fee: 'शुल्क', summary_duration: 'अवधि',
    requires_approval_note: 'यो सदस्यताको लागि सक्रिय हुनु अघि प्रशासक समीक्षा आवश्यक छ।',
    type_required: 'कृपया सदस्यता प्रकार छान्नुहोस्।',
    submit_btn: 'आवेदन पेश गर्नुहोस्',
    submitting: 'पेश गर्दै…',
    cancel: 'रद्द गर्नुहोस्',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

// ── State ─────────────────────────────────────────────────────────────────────

const selectedTypeId = ref(null)
const submitting     = ref(false)
const typeError      = ref('')

// ── Computed ──────────────────────────────────────────────────────────────────

const selectedType = computed(() =>
  props.types.find(ty => ty.id === selectedTypeId.value) ?? null
)

// ── Helpers ───────────────────────────────────────────────────────────────────

const formatFee = (amount, currency) => {
  if (parseFloat(amount) === 0) return 'Free'
  return `${parseFloat(amount).toFixed(2)} ${currency}`
}

// ── Submit ────────────────────────────────────────────────────────────────────

const submit = () => {
  typeError.value = ''

  if (!selectedTypeId.value) {
    typeError.value = t.value.type_required
    return
  }

  submitting.value = true

  router.post(
    route('organisations.membership.apply.store', props.organisation.slug),
    { membership_type_id: selectedTypeId.value, application_data: null },
    {
      preserveScroll: true,
      onFinish: () => { submitting.value = false },
    }
  )
}
</script>

