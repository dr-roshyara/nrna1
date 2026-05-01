<template>
  <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-6">Election Timeline Settings</h2>

    <form @submit.prevent="saveTimeline" class="space-y-6">
      <!-- Administration Phase -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
            <span class="text-xl">⚙️</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Administration Phase</h3>
            <p class="text-sm text-slate-500">Setup period for posts, voters, and committee</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
            <input
              type="datetime-local"
              v-model="form.administration_suggested_start"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p v-if="errors.administration_suggested_start" class="text-danger-500 text-sm mt-1">
              {{ errors.administration_suggested_start }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
            <input
              type="datetime-local"
              v-model="form.administration_suggested_end"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p v-if="errors.administration_suggested_end" class="text-danger-500 text-sm mt-1">
              {{ errors.administration_suggested_end }}
            </p>
          </div>
        </div>
      </div>

      <!-- Nomination Phase -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
            <span class="text-xl">📋</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Nomination Phase</h3>
            <p class="text-sm text-slate-500">Candidate application and approval period</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
            <input
              type="datetime-local"
              v-model="form.nomination_suggested_start"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p v-if="errors.nomination_suggested_start" class="text-danger-500 text-sm mt-1">
              {{ errors.nomination_suggested_start }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
            <input
              type="datetime-local"
              v-model="form.nomination_suggested_end"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p v-if="errors.nomination_suggested_end" class="text-danger-500 text-sm mt-1">
              {{ errors.nomination_suggested_end }}
            </p>
          </div>
        </div>
      </div>

      <!-- Voting Period -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
            <span class="text-xl">🗳️</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Voting Period</h3>
            <p class="text-sm text-slate-500">When members cast their votes</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Start Date</label>
            <input
              type="datetime-local"
              v-model="form.voting_starts_at"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p v-if="errors.voting_starts_at" class="text-danger-500 text-sm mt-1">
              {{ errors.voting_starts_at }}
            </p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">End Date</label>
            <input
              type="datetime-local"
              v-model="form.voting_ends_at"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p v-if="errors.voting_ends_at" class="text-danger-500 text-sm mt-1">
              {{ errors.voting_ends_at }}
            </p>
          </div>
        </div>
      </div>

      <!-- Results Publication -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
            <span class="text-xl">📊</span>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Results Publication</h3>
            <p class="text-sm text-slate-500">When results become visible to voters</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Publication Date</label>
            <input
              type="datetime-local"
              v-model="form.results_published_at"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-primary-500"
            />
            <p class="text-xs text-slate-500 mt-1">Leave empty to publish manually</p>
            <p v-if="errors.results_published_at" class="text-danger-500 text-sm mt-1">
              {{ errors.results_published_at }}
            </p>
          </div>
        </div>
      </div>

      <!-- Auto-transition Settings -->
      <div class="border-b border-slate-200 pb-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-slate-800">Auto-Transition</h3>
            <p class="text-sm text-slate-500">Automatic phase transitions after grace period</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Allow Auto-Transition Toggle -->
          <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-200">
            <div>
              <label class="font-medium text-slate-700">Allow Auto-Transition</label>
              <p class="text-xs text-slate-500">Automatically transition phases after grace period</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="form.allow_auto_transition" class="sr-only peer">
              <div class="w-11 h-6 bg-neutral-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
          </div>

          <!-- Grace Period Days -->
          <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
            <label class="block font-medium text-slate-700 mb-1">Grace Period (Days)</label>
            <input
              type="number"
              v-model.number="form.auto_transition_grace_days"
              min="0"
              max="30"
              class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
            <p class="text-xs text-slate-500 mt-1">Days after suggested end date before auto-transition (0-30)</p>
            <p v-if="errors.auto_transition_grace_days" class="text-danger-500 text-sm mt-1">
              {{ errors.auto_transition_grace_days }}
            </p>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end pt-4 border-t border-slate-200">
        <button
          type="submit"
          :disabled="isSaving"
          class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 flex items-center gap-2 transition-colors"
        >
          <svg
            v-if="isSaving"
            class="w-4 h-4 animate-spin"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          {{ isSaving ? 'Saving...' : 'Save Timeline' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  election: {
    type: Object,
    required: true
  },
  organisation: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['form-changed', 'save-success'])

const isSaving = ref(false)
const errors = ref({})

// Format date for datetime-local input: convert "2026-03-10 14:30:00" to "2026-03-10T14:30"
const formatDateForInput = (dateString) => {
  if (!dateString) return ''

  // If already in datetime-local format, return as-is (trimmed to minutes)
  if (typeof dateString === 'string' && dateString.includes('T')) {
    return dateString.substring(0, 16)
  }

  // Handle Laravel datetime format: "2026-04-22 00:57:00"
  if (typeof dateString === 'string' && dateString.includes(' ')) {
    const [datePart, timePart] = dateString.split(' ')
    const [year, month, day] = datePart.split('-')
    const [hours, minutes] = timePart.split(':')
    return `${year}-${month}-${day}T${hours}:${minutes}`
  }

  // Fallback: try to parse as Date and format
  const date = new Date(dateString)
  if (isNaN(date.getTime())) return ''

  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')

  return `${year}-${month}-${day}T${hours}:${minutes}`
}

const form = ref({
  administration_suggested_start: formatDateForInput(props.election.administration_suggested_start),
  administration_suggested_end: formatDateForInput(props.election.administration_suggested_end),
  nomination_suggested_start: formatDateForInput(props.election.nomination_suggested_start),
  nomination_suggested_end: formatDateForInput(props.election.nomination_suggested_end),
  voting_starts_at: formatDateForInput(props.election.voting_starts_at),
  voting_ends_at: formatDateForInput(props.election.voting_ends_at),
  results_published_at: formatDateForInput(props.election.results_published_at),
  allow_auto_transition: props.election.allow_auto_transition ?? true,
  auto_transition_grace_days: props.election.auto_transition_grace_days ?? 7,
})

// Track form changes
watch(() => form.value, () => {
  emit('form-changed')
}, { deep: true })

const saveTimeline = () => {
  isSaving.value = true
  errors.value = {}

  const payload = {
    administration_suggested_start: form.value.administration_suggested_start,
    administration_suggested_end: form.value.administration_suggested_end,
    nomination_suggested_start: form.value.nomination_suggested_start,
    nomination_suggested_end: form.value.nomination_suggested_end,
    voting_starts_at: form.value.voting_starts_at,
    voting_ends_at: form.value.voting_ends_at,
    results_published_at: form.value.results_published_at,
    allow_auto_transition: form.value.allow_auto_transition,
    auto_transition_grace_days: form.value.auto_transition_grace_days,
  }

  router.patch(
    route('elections.update-timeline', props.election.slug),
    payload,
    {
      preserveScroll: true,
      onError: (pageErrors) => {
        errors.value = pageErrors
        isSaving.value = false
      },
      onSuccess: () => {
        emit('save-success')
        isSaving.value = false
      },
      onFinish: () => {
        isSaving.value = false
      }
    }
  )
}
</script>

