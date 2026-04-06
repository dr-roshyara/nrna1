<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12 px-4">
      <div class="max-w-2xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-10">
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-purple-100 mb-4">
            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-slate-900">{{ t.title }}</h1>
          <p class="text-slate-500 mt-1">{{ organisation.name }}</p>
        </div>

        <!-- Success state -->
        <div v-if="page.props.flash?.success"
             class="bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>
          <h2 class="text-lg font-semibold text-green-800 mb-2">{{ t.success_title }}</h2>
          <p class="text-green-700 text-sm">{{ page.props.flash.success }}</p>
        </div>

        <!-- Form -->
        <form v-else @submit.prevent="submit"
              class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">

          <!-- Error summary -->
          <div v-if="Object.keys(page.props.errors ?? {}).length"
               class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
            <ul class="space-y-1">
              <li v-for="(msg, field) in page.props.errors" :key="field">{{ msg }}</li>
            </ul>
          </div>

          <!-- First / Last name -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                {{ t.first_name }} <span class="text-red-500">*</span>
              </label>
              <input v-model="form.first_name" type="text" required
                     :class="inputClass('first_name')"
                     :placeholder="t.first_name" />
              <p v-if="page.props.errors?.first_name" class="mt-1 text-xs text-red-600">{{ page.props.errors.first_name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                {{ t.last_name }} <span class="text-red-500">*</span>
              </label>
              <input v-model="form.last_name" type="text" required
                     :class="inputClass('last_name')"
                     :placeholder="t.last_name" />
              <p v-if="page.props.errors?.last_name" class="mt-1 text-xs text-red-600">{{ page.props.errors.last_name }}</p>
            </div>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
              {{ t.email }} <span class="text-red-500">*</span>
            </label>
            <input v-model="form.email" type="email" required
                   :class="inputClass('email')"
                   :placeholder="t.email" />
            <p v-if="page.props.errors?.email" class="mt-1 text-xs text-red-600">{{ page.props.errors.email }}</p>
          </div>

          <!-- Telephone -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.telephone }}</label>
            <input v-model="form.telephone_number" type="tel"
                   :class="inputClass('telephone_number')"
                   :placeholder="t.telephone_placeholder" />
          </div>

          <!-- Profession / Education -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.profession }}</label>
              <input v-model="form.profession" type="text"
                     :class="inputClass('profession')"
                     :placeholder="t.profession_placeholder" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.education_level }}</label>
              <select v-model="form.education_level" :class="inputClass('education_level')">
                <option value="">{{ t.select_education }}</option>
                <option v-for="level in educationLevels" :key="level" :value="level">{{ level }}</option>
              </select>
            </div>
          </div>

          <!-- City / Country -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.city }}</label>
              <input v-model="form.city" type="text"
                     :class="inputClass('city')"
                     :placeholder="t.city" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.country }}</label>
              <input v-model="form.country" type="text"
                     :class="inputClass('country')"
                     :placeholder="t.country" />
            </div>
          </div>

          <!-- Message -->
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">{{ t.message }}</label>
            <textarea v-model="form.message" rows="4"
                      :class="inputClass('message')"
                      :placeholder="t.message_placeholder" />
          </div>

          <!-- Honeypot (hidden from real users) -->
          <input type="text" name="website" v-model="form.website"
                 style="display:none; position:absolute; left:-9999px"
                 tabindex="-1" autocomplete="off" />

          <!-- Submit -->
          <button type="submit" :disabled="submitting"
                  class="w-full rounded-xl bg-purple-600 px-6 py-3 text-sm font-semibold text-white
                         hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
            <span v-if="submitting">{{ t.submitting }}</span>
            <span v-else>{{ t.submit }}</span>
          </button>
        </form>

      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
})

const page      = usePage()
const { locale } = useI18n()

const translations = {
  en: {
    title: 'Apply for Membership',
    first_name: 'First Name', last_name: 'Last Name',
    email: 'Email Address', telephone: 'Telephone', telephone_placeholder: '+49 123 456789',
    profession: 'Profession', profession_placeholder: 'e.g. Engineer, Teacher',
    education_level: 'Education Level', select_education: 'Select level…',
    city: 'City', country: 'Country',
    message: 'Message', message_placeholder: 'Anything you would like us to know? (optional)',
    submit: 'Submit Application', submitting: 'Submitting…',
    success_title: 'Application Received!',
  },
  de: {
    title: 'Mitgliedschaft beantragen',
    first_name: 'Vorname', last_name: 'Nachname',
    email: 'E-Mail-Adresse', telephone: 'Telefon', telephone_placeholder: '+49 123 456789',
    profession: 'Beruf', profession_placeholder: 'z.B. Ingenieur, Lehrer',
    education_level: 'Bildungsabschluss', select_education: 'Bitte wählen…',
    city: 'Stadt', country: 'Land',
    message: 'Nachricht', message_placeholder: 'Möchten Sie uns etwas mitteilen? (optional)',
    submit: 'Antrag einreichen', submitting: 'Wird eingereicht…',
    success_title: 'Antrag erhalten!',
  },
  np: {
    title: 'सदस्यताको लागि आवेदन दिनुहोस्',
    first_name: 'पहिलो नाम', last_name: 'थर',
    email: 'इमेल ठेगाना', telephone: 'टेलिफोन', telephone_placeholder: '+977 980 0000000',
    profession: 'पेशा', profession_placeholder: 'जस्तै: इन्जिनियर, शिक्षक',
    education_level: 'शिक्षा स्तर', select_education: 'छान्नुहोस्…',
    city: 'शहर', country: 'देश',
    message: 'सन्देश', message_placeholder: 'केही थप जानकारी दिन चाहनुहुन्छ? (वैकल्पिक)',
    submit: 'आवेदन पेश गर्नुहोस्', submitting: 'पेश गर्दै…',
    success_title: 'आवेदन प्राप्त भयो!',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

const educationLevels = [
  'Primary School',
  'Secondary School',
  "Bachelor's Degree",
  "Master's Degree",
  'PhD/Doctorate',
  'Other',
]

const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  telephone_number: '',
  profession: '',
  education_level: '',
  city: '',
  country: '',
  message: '',
  website: '', // honeypot
})

const submitting = ref(false)

const inputClass = (field) => {
  const base = 'w-full rounded-lg border px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors'
  const hasError = page.props.errors?.[field]
  return base + (hasError ? ' border-red-400 bg-red-50' : ' border-slate-300 bg-white hover:border-slate-400')
}

const submit = () => {
  submitting.value = true
  router.post(
    route('organisations.join.store', props.organisation.slug),
    form.value,
    {
      preserveScroll: true,
      onFinish: () => { submitting.value = false },
    }
  )
}
</script>
