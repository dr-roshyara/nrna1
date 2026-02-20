<template>
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
      <!-- Header with title and status -->
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-gray-900">
          {{ $t('organizations.demo.title') }}
        </h3>
        <span
          class="px-2 py-1 text-xs font-semibold rounded-full"
          :class="demoStatus.exists ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
        >
          {{ demoStatus.exists
            ? $t('organizations.demo.status_setup_complete')
            : $t('organizations.demo.status_not_setup')
          }}
        </span>
      </div>

      <!-- Introduction message -->
      <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
        <p class="text-sm text-blue-800">
          {{ $t('organizations.demo.message_intro') }}
        </p>
      </div>

      <!-- Stats Cards (if demo exists) -->
      <div v-if="demoStatus.exists" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-3 text-center">
          <div class="text-2xl font-bold text-blue-600">{{ demoStatus.posts }}</div>
          <div class="text-xs text-gray-600">{{ $t('organizations.demo.stats_posts') }}</div>
        </div>
        <div class="bg-green-50 rounded-lg p-3 text-center">
          <div class="text-2xl font-bold text-green-600">{{ demoStatus.candidates }}</div>
          <div class="text-xs text-gray-600">{{ $t('organizations.demo.stats_candidates') }}</div>
        </div>
        <div class="bg-indigo-50 rounded-lg p-3 text-center">
          <div class="text-2xl font-bold text-indigo-600">{{ demoStatus.codes }}</div>
          <div class="text-xs text-gray-600">{{ $t('organizations.demo.stats_codes') }}</div>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 text-center">
          <div class="text-2xl font-bold text-gray-600">{{ demoStatus.votes }}</div>
          <div class="text-xs text-gray-600">{{ $t('organizations.demo.stats_test_votes') }}</div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3">
        <!-- Start Demo Voting Button (if demo exists) -->
        <a
          v-if="demoStatus.exists"
          :href="route('election.demo.start')"
          class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ $t('organizations.demo.button_test_voting') }}
        </a>

        <!-- Setup/Recreate Button -->
        <button
          @click="setupDemo"
          :disabled="loading"
          class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white transition disabled:opacity-50"
          :class="demoStatus.exists ? 'bg-green-600 hover:bg-green-700' : 'bg-green-600 hover:bg-green-700'"
        >
          <svg
            v-if="loading"
            class="animate-spin w-4 h-4 mr-2"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
          </svg>
          {{ demoStatus.exists
            ? $t('organizations.demo.button_recreate')
            : $t('organizations.demo.button_setup')
          }}
        </button>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="message" class="mt-4">
        <div
          :class="messageType === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200'"
          class="p-3 rounded-md border"
        >
          {{ message }}
        </div>
      </div>

      <!-- Info Text -->
      <p class="mt-4 text-xs text-gray-500">
        <span class="font-medium">{{ $t('common.note') }}:</span>
        {{ demoStatus.exists
          ? $t('organizations.demo.note_isolated')
          : $t('organizations.demo.note_setup')
        }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  organization: {
    type: Object,
    required: true
  },
  demoStatus: {
    type: Object,
    required: true
  }
})

const loading = ref(false)
const message = ref('')
const messageType = ref('success')

const setupDemo = async () => {
  // Confirm for recreate with translated message
  if (props.demoStatus.exists) {
    if (!confirm(t('organizations.demo.message_confirm_recreate'))) {
      return
    }
  }

  loading.value = true
  message.value = ''

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

    const response = await window.axios.post(
      `/api/organizations/${props.organization.id}/demo-setup`,
      {
        force: props.demoStatus.exists // Force recreate if exists
      },
      {
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        }
      }
    )

    if (response.data.success) {
      messageType.value = 'success'
      message.value = response.data.message

      // Update demo status with new stats
      props.demoStatus.exists = response.data.demoStatus.exists
      props.demoStatus.posts = response.data.demoStatus.stats.posts
      props.demoStatus.candidates = response.data.demoStatus.stats.candidates
      props.demoStatus.codes = response.data.demoStatus.stats.codes
      props.demoStatus.votes = response.data.demoStatus.stats.votes
      props.demoStatus.election_id = response.data.demoStatus.stats.election_id
      props.demoStatus.election_name = response.data.demoStatus.stats.election_name

      // Clear message after 5 seconds
      setTimeout(() => {
        message.value = ''
      }, 5000)
    } else {
      messageType.value = 'error'
      message.value = response.data.message || t('organizations.demo.message_error')
    }
  } catch (error) {
    messageType.value = 'error'
    message.value = error.response?.data?.message || t('organizations.demo.message_error')
  } finally {
    loading.value = false
  }
}
</script>
