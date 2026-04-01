<template>
  <PublicDigitLayout>
    <div class="max-w-2xl mx-auto py-8 px-4">

      <!-- Flash success -->
      <div
        v-if="page.props.flash?.success"
        class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm"
      >
        {{ page.props.flash.success }}
      </div>

      <div class="bg-white rounded-lg shadow p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ t.title }}</h1>
        <p class="text-gray-500 text-sm mb-8">
          {{ t.organisation_label }}: <span class="font-medium text-gray-700">{{ organisation.name }}</span>
        </p>

        <form @submit.prevent="submit" novalidate>

          <!-- Name -->
          <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              {{ t.fields.name.label }} <span class="text-red-500">*</span>
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              maxlength="255"
              :placeholder="t.fields.name.placeholder"
              class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="errors.name ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <!-- Description -->
          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
              {{ t.fields.description.label }}
              <span class="text-gray-400 font-normal">{{ t.fields.description.optional }}</span>
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              maxlength="5000"
              :placeholder="t.fields.description.placeholder"
              class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="errors.description ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
          </div>

          <!-- Start Date + Time -->
          <div class="mb-4">
            <p class="text-sm font-medium text-gray-700 mb-2">
              {{ t.fields.start.section }} <span class="text-red-500">*</span>
            </p>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label for="start_date" class="block text-xs text-gray-500 mb-1">
                  {{ t.fields.start.date_label }}
                </label>
                <input
                  id="start_date"
                  v-model="form.start_date"
                  type="date"
                  :min="today"
                  class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="errors.start_date ? 'border-red-400' : 'border-gray-300'"
                />
              </div>
              <div>
                <label for="start_time" class="block text-xs text-gray-500 mb-1">
                  {{ t.fields.start.time_label }}
                </label>
                <input
                  id="start_time"
                  v-model="form.start_time"
                  type="time"
                  class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="errors.start_date ? 'border-red-400' : 'border-gray-300'"
                />
              </div>
            </div>
            <p v-if="errors.start_date" class="mt-1 text-sm text-red-600">{{ errors.start_date }}</p>
          </div>

          <!-- End Date + Time -->
          <div class="mb-8">
            <p class="text-sm font-medium text-gray-700 mb-2">
              {{ t.fields.end.section }} <span class="text-red-500">*</span>
            </p>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label for="end_date" class="block text-xs text-gray-500 mb-1">
                  {{ t.fields.end.date_label }}
                </label>
                <input
                  id="end_date"
                  v-model="form.end_date"
                  type="date"
                  :min="form.start_date || today"
                  class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="errors.end_date ? 'border-red-400' : 'border-gray-300'"
                />
              </div>
              <div>
                <label for="end_time" class="block text-xs text-gray-500 mb-1">
                  {{ t.fields.end.time_label }}
                </label>
                <input
                  id="end_time"
                  v-model="form.end_time"
                  type="time"
                  class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                  :class="errors.end_date ? 'border-red-400' : 'border-gray-300'"
                />
              </div>
            </div>
            <p v-if="errors.end_date" class="mt-1 text-sm text-red-600">{{ errors.end_date }}</p>
            <p v-if="clientDateError" class="mt-1 text-sm text-red-600">{{ clientDateError }}</p>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-between">
            <a
              :href="route('organisations.show', organisation.slug)"
              class="text-sm text-gray-500 hover:text-gray-700 underline"
            >
              {{ t.actions.cancel }}
            </a>
            <button
              type="submit"
              :disabled="isLoading || !!clientDateError"
              class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isLoading ? t.actions.submitting : t.actions.submit }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

import pageDe from '@/locales/pages/Organisations/Elections/Create/de.json'
import pageEn from '@/locales/pages/Organisations/Elections/Create/en.json'
import pageNp from '@/locales/pages/Organisations/Elections/Create/np.json'

const props = defineProps({
  organisation: { type: Object, required: true },
})

const page      = usePage()
const errors    = computed(() => page.props.errors ?? {})
const isLoading = ref(false)

// Translation
const { locale } = useI18n()
const pageData = { de: pageDe, en: pageEn, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.de)

// Today's date in YYYY-MM-DD for the min attribute
const today = new Date().toISOString().split('T')[0]

const form = reactive({
  name:        '',
  description: '',
  start_date:  '',
  start_time:  '08:00',
  end_date:    '',
  end_time:    '20:00',
})

// Client-side: end datetime must be after start datetime
const clientDateError = computed(() => {
  if (!form.start_date || !form.end_date) return null
  const start = new Date(`${form.start_date}T${form.start_time || '00:00'}`)
  const end   = new Date(`${form.end_date}T${form.end_time   || '23:59'}`)
  if (end <= start) return t.value.validation.end_after_start
  return null
})

const submit = () => {
  if (clientDateError.value) return
  isLoading.value = true
  router.post(
    route('organisations.elections.store', props.organisation.slug),
    {
      name:        form.name,
      description: form.description,
      start_date:  `${form.start_date} ${form.start_time || '00:00'}:00`,
      end_date:    `${form.end_date} ${form.end_time || '23:59'}:00`,
    },
    {
      preserveScroll: true,
      onFinish: () => { isLoading.value = false },
    }
  )
}
</script>
