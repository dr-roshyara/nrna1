<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-gradient-to-br from-neutral-50 to-accent-50 py-12 px-4">
      <div class="max-w-2xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-10">
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-accent-100 mb-4">
            <svg class="w-7 h-7 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-neutral-900">{{ t.title }}</h1>
          <p class="text-neutral-500 mt-1">{{ organisation.name }}</p>
        </div>

        <!-- Success state -->
        <Card v-if="page.props.flash?.success" variant="success" padding="lg" class="text-center">
          <VerificationSeal status="verified" size="lg" class="mx-auto mb-4" />
          <h2 class="text-lg font-semibold text-success-600 mb-2">{{ t.success_title }}</h2>
          <p class="text-success-700 text-sm">{{ page.props.flash.success }}</p>
        </Card>

        <!-- Form -->
        <form v-else @submit.prevent="submit"
              class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 space-y-6">

          <!-- Error summary -->
          <div v-if="Object.keys(page.props.errors ?? {}).length"
               class="bg-danger-bg border border-danger-200 rounded-lg px-4 py-3 text-sm text-danger-700" role="alert">
            <ul class="space-y-1">
              <li v-for="(msg, field) in page.props.errors" :key="field">{{ msg }}</li>
            </ul>
          </div>

          <!-- First / Last name -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Input
              v-model="form.first_name"
              type="text"
              :label="t.first_name"
              :required="true"
              :placeholder="t.first_name"
              :error="page.props.errors?.first_name"
            />
            <Input
              v-model="form.last_name"
              type="text"
              :label="t.last_name"
              :required="true"
              :placeholder="t.last_name"
              :error="page.props.errors?.last_name"
            />
          </div>

          <!-- Email -->
          <Input
            v-model="form.email"
            type="email"
            :label="t.email"
            :required="true"
            :placeholder="t.email"
            :error="page.props.errors?.email"
          />

          <!-- Telephone -->
          <Input
            v-model="form.telephone_number"
            type="tel"
            :label="t.telephone"
            :placeholder="t.telephone_placeholder"
          />

          <!-- Profession / Education -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Input
              v-model="form.profession"
              type="text"
              :label="t.profession"
              :placeholder="t.profession_placeholder"
            />
            <div>
              <Input
                v-model="form.education_level"
                type="select"
                :label="t.education_level"
                :placeholder="t.select_education"
                :options="educationLevels"
              />
            </div>
          </div>

          <!-- City / Country -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Input
              v-model="form.city"
              type="text"
              :label="t.city"
              :placeholder="t.city"
            />
            <Input
              v-model="form.country"
              type="text"
              :label="t.country"
              :placeholder="t.country"
            />
          </div>

          <!-- Message -->
          <Input
            v-model="form.message"
            type="textarea"
            :label="t.message"
            :placeholder="t.message_placeholder"
            :rows="4"
          />

          <!-- Honeypot (hidden from real users) -->
          <input type="text" name="website" v-model="form.website"
                 style="display:none; position:absolute; left:-9999px"
                 tabindex="-1" autocomplete="off" />

          <!-- Submit -->
          <Button
            type="submit"
            variant="accent"
            size="lg"
            class="w-full"
            :loading="submitting"
            :disabled="submitting"
          >
            {{ submitting ? t.submitting : t.submit }}
          </Button>
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
import { Button, Card, Input, VerificationSeal } from '@/Components'

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
