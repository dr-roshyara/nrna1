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

          <!-- Start Date & Time -->
          <div class="mb-4">
            <label for="start_datetime" class="block text-sm font-medium text-gray-700 mb-1">
              {{ t.fields.start.section }} <span class="text-red-500">*</span>
            </label>
            <input
              id="start_datetime"
              v-model="form.start_datetime"
              type="datetime-local"
              class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="errors.start_date ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="errors.start_date" class="mt-1 text-sm text-red-600">{{ errors.start_date }}</p>
          </div>

          <!-- End Date & Time -->
          <div class="mb-8">
            <label for="end_datetime" class="block text-sm font-medium text-gray-700 mb-1">
              {{ t.fields.end.section }} <span class="text-red-500">*</span>
            </label>
            <input
              id="end_datetime"
              v-model="form.end_datetime"
              type="datetime-local"
              class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="errors.end_date ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="errors.end_date" class="mt-1 text-sm text-red-600">{{ errors.end_date }}</p>
            <p v-if="clientDateError" class="mt-1 text-sm text-red-600">{{ clientDateError }}</p>
          </div>

          <!-- STATE MACHINE PHASE DATES SECTION -->
          <div class="border-t pt-8 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Election Phases Timeline</h3>
            <p class="text-sm text-gray-500 mb-6">Define when each phase of the election takes place</p>

            <!-- Administration Phase -->
            <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
              <h4 class="text-sm font-semibold text-blue-900 mb-4">⚙️ Administration Phase</h4>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-gray-600 font-medium mb-1">Start Date & Time</label>
                  <input
                    v-model="form.administration_suggested_start"
                    type="datetime-local"
                    class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="errors.administration_suggested_start ? 'border-red-400' : 'border-gray-300'"
                  />
                  <p v-if="errors.administration_suggested_start" class="mt-1 text-xs text-red-600">{{ errors.administration_suggested_start }}</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 font-medium mb-1">End Date & Time</label>
                  <input
                    v-model="form.administration_suggested_end"
                    type="datetime-local"
                    class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :class="errors.administration_suggested_end ? 'border-red-400' : 'border-gray-300'"
                  />
                  <p v-if="errors.administration_suggested_end" class="mt-1 text-xs text-red-600">{{ errors.administration_suggested_end }}</p>
                </div>
              </div>
            </div>

            <!-- Nomination Phase -->
            <div class="mb-8 p-4 bg-green-50 rounded-lg border border-green-200">
              <h4 class="text-sm font-semibold text-green-900 mb-4">📋 Nomination Phase</h4>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-gray-600 font-medium mb-1">Start Date & Time</label>
                  <input
                    v-model="form.nomination_suggested_start"
                    type="datetime-local"
                    class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    :class="errors.nomination_suggested_start ? 'border-red-400' : 'border-gray-300'"
                  />
                  <p v-if="errors.nomination_suggested_start" class="mt-1 text-xs text-red-600">{{ errors.nomination_suggested_start }}</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 font-medium mb-1">End Date & Time</label>
                  <input
                    v-model="form.nomination_suggested_end"
                    type="datetime-local"
                    class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                    :class="errors.nomination_suggested_end ? 'border-red-400' : 'border-gray-300'"
                  />
                  <p v-if="errors.nomination_suggested_end" class="mt-1 text-xs text-red-600">{{ errors.nomination_suggested_end }}</p>
                </div>
              </div>
            </div>

            <!-- Voting Phase -->
            <div class="mb-8 p-4 bg-purple-50 rounded-lg border border-purple-200">
              <h4 class="text-sm font-semibold text-purple-900 mb-4">🗳️ Voting Phase</h4>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-gray-600 font-medium mb-1">Voting Starts</label>
                  <input
                    v-model="form.voting_starts_at"
                    type="datetime-local"
                    class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                    :class="errors.voting_starts_at ? 'border-red-400' : 'border-gray-300'"
                  />
                  <p v-if="errors.voting_starts_at" class="mt-1 text-xs text-red-600">{{ errors.voting_starts_at }}</p>
                </div>
                <div>
                  <label class="block text-xs text-gray-600 font-medium mb-1">Voting Ends</label>
                  <input
                    v-model="form.voting_ends_at"
                    type="datetime-local"
                    class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                    :class="errors.voting_ends_at ? 'border-red-400' : 'border-gray-300'"
                  />
                  <p v-if="errors.voting_ends_at" class="mt-1 text-xs text-red-600">{{ errors.voting_ends_at }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Phase Dates Error -->
          <div v-if="phasesDatesError" class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800 text-sm">
            {{ phasesDatesError }}
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
              :disabled="isLoading || !!clientDateError || !!phasesDatesError"
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
  start_datetime: '',
  end_datetime: '',
  administration_suggested_start: '',
  administration_suggested_end: '',
  nomination_suggested_start: '',
  nomination_suggested_end: '',
  voting_starts_at: '',
  voting_ends_at: '',
})

// Client-side: end datetime must be after start datetime
const clientDateError = computed(() => {
  if (!form.start_datetime || !form.end_datetime) return null
  const start = new Date(form.start_datetime)
  const end   = new Date(form.end_datetime)
  if (end <= start) return t.value.validation.end_after_start
  return null
})

// Client-side: phase dates validation
const phasesDatesError = computed(() => {
  if (!form.administration_suggested_start || !form.administration_suggested_end ||
      !form.nomination_suggested_start || !form.nomination_suggested_end ||
      !form.voting_starts_at || !form.voting_ends_at) {
    return null
  }

  const adminStart = new Date(form.administration_suggested_start)
  const adminEnd = new Date(form.administration_suggested_end)
  const nomStart = new Date(form.nomination_suggested_start)
  const nomEnd = new Date(form.nomination_suggested_end)
  const votStart = new Date(form.voting_starts_at)
  const votEnd = new Date(form.voting_ends_at)

  // Admin phase: end must be after start
  if (adminEnd <= adminStart) return 'Administration phase end must be after start'

  // Nomination phase: end must be after start
  if (nomEnd <= nomStart) return 'Nomination phase end must be after start'

  // Voting phase: end must be after start
  if (votEnd <= votStart) return 'Voting phase end must be after start'

  // Chronological order: admin → nomination → voting
  if (nomStart < adminEnd) return 'Nomination phase must start after administration ends'
  if (votStart < nomEnd) return 'Voting phase must start after nomination ends'

  // Election envelope validation (if both are set)
  if (form.start_datetime && form.end_datetime) {
    const elecStart = new Date(form.start_datetime)
    const elecEnd = new Date(form.end_datetime)

    if (adminStart < elecStart) return 'Administration cannot start before election start'
    if (votEnd > elecEnd) return 'Voting cannot end after election end'
  }

  return null
})

const submit = () => {
  if (clientDateError.value || phasesDatesError.value) return
  isLoading.value = true
  router.post(
    route('organisations.elections.store', props.organisation.slug),
    {
      name:        form.name,
      description: form.description,
      start_datetime: form.start_datetime,
      end_datetime: form.end_datetime,
      administration_suggested_start: form.administration_suggested_start,
      administration_suggested_end: form.administration_suggested_end,
      nomination_suggested_start: form.nomination_suggested_start,
      nomination_suggested_end: form.nomination_suggested_end,
      voting_starts_at: form.voting_starts_at,
      voting_ends_at: form.voting_ends_at,
    },
    {
      preserveScroll: true,
      onFinish: () => { isLoading.value = false },
    }
  )
}
</script>
