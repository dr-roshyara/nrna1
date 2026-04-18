<template>
  <PublicDigitLayout>
    <div class="max-w-5xl mx-auto py-8 px-4">

      <!-- Flash / errors -->
      <div v-if="page.props.errors?.error"
           class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
        {{ page.props.errors.error }}
      </div>

      <!-- Header -->
      <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-1">
          <a :href="route('organisations.membership.dashboard', organisation.slug)"
             class="hover:text-purple-600 transition-colors">{{ organisation.name }}</a>
          <span>/</span>
          <a :href="route('organisations.membership.newsletters.index', organisation.slug)"
             class="hover:text-purple-600 transition-colors">{{ t.newsletters }}</a>
          <span>/</span>
          <span class="text-slate-800 font-medium">{{ t.title }}</span>
        </nav>
        <h1 class="text-2xl font-bold text-slate-900">{{ t.title }}</h1>
      </div>

      <!-- Audience Type Selector -->
      <div class="mb-6 rounded-lg bg-white border border-slate-200 shadow-sm p-4">
        <label class="block text-sm font-semibold text-slate-700 mb-3">
          {{ t.audience_type || 'Audience Type' }} <span class="text-red-500">*</span>
        </label>
        <select v-model="form.audience_type" @change="onAudienceChange"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900
                       focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
          <option v-for="type in audienceTypes" :key="type" :value="type">
            {{ audienceLabels[type] || type }}
          </option>
        </select>
      </div>

      <!-- Election Selector (for election-based audiences) -->
      <div v-if="isElectionAudience" class="mb-6 rounded-lg bg-white border border-slate-200 shadow-sm p-4">
        <label class="block text-sm font-semibold text-slate-700 mb-3">
          {{ t.election || 'Election' }} <span class="text-red-500">*</span>
        </label>
        <select v-model="form.audience_meta.election_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900
                       focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
          <option value="">-- Choose an election --</option>
          <option v-for="election in elections" :key="election.id" :value="election.id">
            {{ election.name }} ({{ election.status }})
          </option>
        </select>
      </div>

      <!-- Recipient preview -->
      <div class="mb-6 rounded-lg bg-purple-50 border border-purple-200 px-4 py-3 text-sm text-purple-800 flex items-center gap-2">
        <UsersIcon class="w-4 h-4 flex-shrink-0" />
        <span v-if="recipientCount !== null">
          {{ t.will_send_to }} <strong>{{ recipientCount.toLocaleString() }}</strong> {{ t.members }}
        </span>
        <span v-else class="text-purple-400">{{ t.loading_count }}</span>
      </div>

      <!-- Recipient Sample Preview -->
      <div v-if="previewSample.length > 0" class="mb-6 rounded-lg bg-white border border-slate-200 shadow-sm p-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-3">{{ t.sample_recipients || 'Sample Recipients' }}</h3>
        <div class="space-y-2">
          <div v-for="(recipient, i) in previewSample" :key="i" class="flex items-center justify-between bg-slate-50 px-3 py-2 rounded">
            <div>
              <p class="text-sm font-medium text-slate-900">{{ recipient.name }}</p>
              <p class="text-xs text-slate-500">{{ recipient.email }}</p>
            </div>
          </div>
        </div>
        <p class="mt-2 text-xs text-slate-500">{{ t.showing }} 5 {{ t.of }} {{ recipientCount.toLocaleString() }} {{ t.recipients }}</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">

        <!-- Subject -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            {{ t.subject }} <span class="text-red-500">*</span>
          </label>
          <input v-model="form.subject" type="text" required maxlength="255"
                 class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900
                        focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                        placeholder:text-slate-400"
                 :placeholder="t.subject_placeholder" />
          <p v-if="errors.subject" class="mt-1 text-xs text-red-600">{{ errors.subject }}</p>
        </div>

        <!-- HTML Content — rich text editor -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            {{ t.content }} <span class="text-red-500">*</span>
          </label>
          <RichTextEditor v-model="form.html_content" :placeholder="t.content_placeholder" />
          <p v-if="errors.html_content" class="mt-1 text-xs text-red-600">{{ errors.html_content }}</p>
        </div>

        <!-- Plain text (optional) -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            {{ t.plain_text }} <span class="text-xs font-normal text-slate-400">({{ t.optional }})</span>
          </label>
          <textarea v-model="form.plain_text" rows="5"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900
                           focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent
                           placeholder:text-slate-400 resize-y"
                    :placeholder="t.plain_text_placeholder" />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
          <a :href="route('organisations.membership.newsletters.index', organisation.slug)"
             class="text-sm text-slate-500 hover:text-slate-700">{{ t.cancel }}</a>
          <button type="submit" :disabled="submitting"
                  class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-semibold
                         text-white shadow-sm hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            <span v-if="submitting">{{ t.saving }}</span>
            <span v-else>{{ t.save_draft }}</span>
          </button>
        </div>
      </form>

    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { UsersIcon } from '@heroicons/vue/24/outline'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import RichTextEditor from '@/Components/Newsletter/RichTextEditor.vue'
import axios from 'axios'

const props = defineProps({
  organisation: { type: Object, required: true },
  elections: { type: Array, default: () => [] },
  audienceTypes: { type: Array, default: () => ['all_members'] },
  audienceLabels: { type: Object, default: () => {} },
})

const page       = usePage()
const { locale } = useI18n()

const translations = {
  en: {
    title: 'Compose Newsletter', newsletters: 'Newsletters',
    subject: 'Subject', subject_placeholder: 'e.g. NRNA EU — June 2026 Update',
    content: 'Content', content_placeholder: 'Dear Member,\n\nWrite your newsletter here…',
    plain_text: 'Plain Text Version', plain_text_placeholder: 'Plain text fallback for email clients that do not render HTML.',
    optional: 'optional',
    save_draft: 'Save Draft', saving: 'Saving…', cancel: 'Cancel',
    audience_type: 'Audience Type', election: 'Election',
    will_send_to: 'This newsletter will be sent to', members: 'recipients.',
    loading_count: 'Loading recipient count…',
    sample_recipients: 'Sample Recipients', showing: 'Showing', of: 'of', recipients: 'recipients',
  },
  de: {
    title: 'Newsletter verfassen', newsletters: 'Newsletter',
    subject: 'Betreff', subject_placeholder: 'z.B. NRNA EU — Juni 2026 Update',
    content: 'Inhalt', content_placeholder: 'Liebes Mitglied,\n\nSchreiben Sie Ihren Newsletter hier…',
    plain_text: 'Nur-Text-Version', plain_text_placeholder: 'Nur-Text-Fallback für E-Mail-Clients ohne HTML.',
    optional: 'optional',
    save_draft: 'Entwurf speichern', saving: 'Speichern…', cancel: 'Abbrechen',
    audience_type: 'Zielgruppe', election: 'Wahl',
    will_send_to: 'Dieser Newsletter wird an', members: 'Empfänger versendet.',
    loading_count: 'Empfängeranzahl wird geladen…',
    sample_recipients: 'Beispielempfänger', showing: 'Zeige', of: 'von', recipients: 'Empfänger',
  },
  np: {
    title: 'न्युजलेटर लेख्नुहोस्', newsletters: 'न्युजलेटर',
    subject: 'विषय', subject_placeholder: 'उदा. NRNA EU — जुन २०२६ अपडेट',
    content: 'सामग्री', content_placeholder: 'प्रिय सदस्य,\n\nयहाँ न्युजलेटर लेख्नुहोस्…',
    plain_text: 'सादा पाठ संस्करण', plain_text_placeholder: 'HTML नदेखाउने इमेल क्लाइन्टका लागि।',
    optional: 'वैकल्पिक',
    save_draft: 'मस्यौदा सुरक्षित गर्नुहोस्', saving: 'सुरक्षित गर्दै…', cancel: 'रद्द गर्नुहोस्',
    audience_type: 'दर्शक', election: 'चुनाव',
    will_send_to: 'यो न्युजलेटर', members: 'प्राप्तकर्तालाई पठाइनेछ।',
    loading_count: 'प्राप्तकर्ता गणना लोड हुँदैछ…',
    sample_recipients: 'नमुना प्राप्तकर्ता', showing: 'देखाइँदै', of: 'को', recipients: 'प्राप्तकर्ता',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

const form = ref({
  subject: '',
  html_content: '',
  plain_text: '',
  audience_type: 'all_members',
  audience_meta: { election_id: null },
})
const errors = ref({})
const submitting = ref(false)
const recipientCount = ref(null)
const previewSample = ref([])
const loadingPreview = ref(false)

const isElectionAudience = computed(() => {
  const electionTypes = [
    'election_voters',
    'election_not_voted',
    'election_voted',
    'election_candidates',
    'election_observers',
    'election_committee',
    'election_all',
  ]
  return electionTypes.includes(form.value.audience_type)
})

const loadPreview = async () => {
  if (!form.value.audience_type) return

  loadingPreview.value = true
  try {
    const response = await axios.post(
      route('organisations.membership.newsletters.previewCount', props.organisation.slug),
      {
        audience_type: form.value.audience_type,
        audience_meta: form.value.audience_meta,
      }
    )
    recipientCount.value = response.data.count
    previewSample.value = response.data.sample || []
  } catch (error) {
    console.error('Preview failed:', error)
    recipientCount.value = 0
    previewSample.value = []
  } finally {
    loadingPreview.value = false
  }
}

const onAudienceChange = () => {
  // Reset election selection when audience type changes
  if (!isElectionAudience.value) {
    form.value.audience_meta.election_id = null
  }
  loadPreview()
}

// Watch for election changes
watch(
  () => form.value.audience_meta.election_id,
  () => {
    if (isElectionAudience.value && form.value.audience_meta.election_id) {
      loadPreview()
    }
  }
)

onMounted(() => {
  // Load initial preview count
  loadPreview()
})

const submit = () => {
  submitting.value = true
  errors.value = {}
  router.post(
    route('organisations.membership.newsletters.store', props.organisation.slug),
    form.value,
    {
      onError: (e) => { errors.value = e; submitting.value = false },
      onFinish: () => { submitting.value = false },
    }
  )
}
</script>
