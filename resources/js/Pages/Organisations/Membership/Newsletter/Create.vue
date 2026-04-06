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

      <!-- Recipient preview -->
      <div class="mb-6 rounded-lg bg-purple-50 border border-purple-200 px-4 py-3 text-sm text-purple-800 flex items-center gap-2">
        <UsersIcon class="w-4 h-4 flex-shrink-0" />
        <span v-if="recipientCount !== null">
          {{ t.will_send_to }} <strong>{{ recipientCount }}</strong> {{ t.members }}
        </span>
        <span v-else class="text-purple-400">{{ t.loading_count }}</span>
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
import { ref, computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { UsersIcon } from '@heroicons/vue/24/outline'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import RichTextEditor from '@/Components/Newsletter/RichTextEditor.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
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
    will_send_to: 'This newsletter will be sent to', members: 'active members.',
    loading_count: 'Loading recipient count…',
  },
  de: {
    title: 'Newsletter verfassen', newsletters: 'Newsletter',
    subject: 'Betreff', subject_placeholder: 'z.B. NRNA EU — Juni 2026 Update',
    content: 'Inhalt', content_placeholder: 'Liebes Mitglied,\n\nSchreiben Sie Ihren Newsletter hier…',
    plain_text: 'Nur-Text-Version', plain_text_placeholder: 'Nur-Text-Fallback für E-Mail-Clients ohne HTML.',
    optional: 'optional',
    save_draft: 'Entwurf speichern', saving: 'Speichern…', cancel: 'Abbrechen',
    will_send_to: 'Dieser Newsletter wird an', members: 'aktive Mitglieder gesendet.',
    loading_count: 'Empfängeranzahl wird geladen…',
  },
  np: {
    title: 'न्युजलेटर लेख्नुहोस्', newsletters: 'न्युजलेटर',
    subject: 'विषय', subject_placeholder: 'उदा. NRNA EU — जुन २०२६ अपडेट',
    content: 'सामग्री', content_placeholder: 'प्रिय सदस्य,\n\nयहाँ न्युजलेटर लेख्नुहोस्…',
    plain_text: 'सादा पाठ संस्करण', plain_text_placeholder: 'HTML नदेखाउने इमेल क्लाइन्टका लागि।',
    optional: 'वैकल्पिक',
    save_draft: 'मस्यौदा सुरक्षित गर्नुहोस्', saving: 'सुरक्षित गर्दै…', cancel: 'रद्द गर्नुहोस्',
    will_send_to: 'यो न्युजलेटर', members: 'सक्रिय सदस्यहरूलाई पठाइनेछ।',
    loading_count: 'प्राप्तकर्ता गणना लोड हुँदैछ…',
  },
}

const t = computed(() => translations[locale.value] ?? translations.en)

const form = ref({ subject: '', html_content: '', plain_text: '' })
const errors = ref({})
const submitting = ref(false)
const recipientCount = ref(null)

onMounted(async () => {
  try {
    // We don't have a newsletter ID yet on create — fetch via a temp preview
    // The controller's previewRecipients needs a newsletter ID, so we skip this on create
    // and show a general count instead by calling a lightweight endpoint if available.
    // For now, leave as null (shows "Loading…" then nothing — acceptable for MVP).
  } catch {}
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
