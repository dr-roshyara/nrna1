<template>
  <section
    aria-labelledby="demo-setup-heading"
    class="mb-8"
  >
    <div
      class="bg-gradient-to-br from-green-50 via-white to-emerald-50 rounded-xl shadow-sm border border-green-200 overflow-hidden hover:shadow-md transition-shadow"
    >
      <div class="px-6 py-8 sm:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div>
            <h3
              id="demo-setup-heading"
              class="text-2xl font-bold text-gray-900 mb-2"
            >
              {{ $t('pages.organization-show.demo.title') }}
            </h3>
            <p class="text-sm text-gray-600 max-w-2xl">
              {{ $t('pages.organization-show.demo.message_intro') }}
            </p>
          </div>

          <!-- Status Badge -->
          <span
            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold w-fit"
            :class="demoStatus.exists
              ? 'bg-green-100 text-green-800 ring-1 ring-green-300'
              : 'bg-gray-100 text-gray-800 ring-1 ring-gray-300'"
            role="status"
            :aria-label="demoStatus.exists ? $t('pages.organization-show.demo.status_setup_complete') : $t('pages.organization-show.demo.status_not_setup')"
          >
            <span
              class="w-2 h-2 rounded-full mr-2"
              :class="demoStatus.exists ? 'bg-green-600' : 'bg-gray-600'"
              aria-hidden="true"
            ></span>
            {{ demoStatus.exists ? $t('pages.organization-show.demo.status_setup_complete') : $t('pages.organization-show.demo.status_not_setup') }}
          </span>
        </div>

        <!-- Stats Grid (if demo exists) -->
        <div v-if="demoStatus.exists" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
          <!-- Posts Stat -->
          <div class="bg-white rounded-lg p-4 border border-blue-100 hover:border-blue-300 transition-colors">
            <div class="text-center">
              <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <p class="text-3xl font-bold text-blue-600 mb-1">{{ demoStatus.posts }}</p>
              <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ $t('pages.organization-show.demo.stats_posts') }}</p>
            </div>
          </div>

          <!-- Candidates Stat -->
          <div class="bg-white rounded-lg p-4 border border-green-100 hover:border-green-300 transition-colors">
            <div class="text-center">
              <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 mb-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <p class="text-3xl font-bold text-green-600 mb-1">{{ demoStatus.candidates }}</p>
              <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ $t('pages.organization-show.demo.stats_candidates') }}</p>
            </div>
          </div>

          <!-- Codes Stat -->
          <div class="bg-white rounded-lg p-4 border border-purple-100 hover:border-purple-300 transition-colors">
            <div class="text-center">
              <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 mb-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <p class="text-3xl font-bold text-purple-600 mb-1">{{ demoStatus.codes }}</p>
              <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ $t('pages.organization-show.demo.stats_codes') }}</p>
            </div>
          </div>

          <!-- Votes Stat -->
          <div class="bg-white rounded-lg p-4 border border-orange-100 hover:border-orange-300 transition-colors">
            <div class="text-center">
              <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 mb-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <p class="text-3xl font-bold text-orange-600 mb-1">{{ demoStatus.votes }}</p>
              <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">{{ $t('pages.organization-show.demo.stats_test_votes') }}</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
          <!-- Start Demo Voting Button (if demo exists) -->
          <a
            v-if="demoStatus.exists"
            href="#"
            class="inline-flex items-center justify-center px-6 py-3 border-2 border-indigo-300 text-sm font-semibold rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-500 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            :aria-label="$t('pages.organization-show.demo.button_test_voting')"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $t('pages.organization-show.demo.button_test_voting') }}
          </a>

          <!-- Setup/Recreate Button -->
          <button
            @click="setupDemo"
            :disabled="loading"
            class="inline-flex items-center justify-center px-6 py-3 border-2 text-sm font-semibold rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :class="demoStatus.exists
              ? 'border-green-300 text-green-600 hover:bg-green-50 hover:border-green-500 focus:ring-green-500'
              : 'border-green-500 bg-green-600 text-white hover:bg-green-700 hover:border-green-700 focus:ring-green-500'"
            :aria-label="demoStatus.exists ? $t('pages.organization-show.demo.button_recreate') : $t('pages.organization-show.demo.button_setup')"
            :aria-busy="loading"
          >
            <svg
              v-if="loading"
              class="animate-spin w-5 h-5 mr-2"
              fill="none"
              viewBox="0 0 24 24"
              aria-hidden="true"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
            {{ demoStatus.exists ? $t('pages.organization-show.demo.button_recreate') : $t('pages.organization-show.demo.button_setup') }}
          </button>
        </div>

        <!-- Success/Error Messages -->
        <div
          v-if="message"
          role="status"
          :aria-live="messageType === 'success' ? 'polite' : 'assertive'"
          :aria-label="`${messageType === 'success' ? 'Success' : 'Error'}: ${message}`"
          class="mb-6"
        >
          <div
            :class="messageType === 'success'
              ? 'bg-green-50 text-green-800 border-green-300'
              : 'bg-red-50 text-red-800 border-red-300'"
            class="p-4 rounded-lg border-l-4 flex items-start gap-3"
          >
            <svg
              :class="messageType === 'success' ? 'text-green-600' : 'text-red-600'"
              class="w-5 h-5 shrink-0 mt-0.5"
              fill="currentColor"
              viewBox="0 0 20 20"
              aria-hidden="true"
            >
              <path
                v-if="messageType === 'success'"
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
              <path
                v-else
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
            </svg>
            <span class="text-sm font-medium">{{ message }}</span>
          </div>
        </div>

        <!-- Info Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3">
          <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
          </svg>
          <div class="text-sm text-blue-900">
            <p class="text-xs opacity-90">
              {{ demoStatus.exists
                ? $t('pages.organization-show.demo.note_isolated')
                : $t('pages.organization-show.demo.note_setup')
              }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'

export default {
  name: 'DemoSetupButton',
  props: {
    organization: {
      type: Object,
      required: true
    },
    demoStatus: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const loading = ref(false)
    const message = ref('')
    const messageType = ref('success')

    const setupDemo = async () => {
      // Confirm for recreate
      if (props.demoStatus.exists) {
        if (!confirm('⚠️ This will DELETE all existing demo data for your organisation. Are you sure?')) {
          return
        }
      }

      loading.value = true
      message.value = ''

      try {
        const response = await axios.post(`/api/organizations/${props.organization.id}/demo-setup`, {
          force: props.demoStatus.exists // Force recreate if exists
        })

        if (response.data.success) {
          messageType.value = 'success'
          message.value = response.data.message

          // Update demo status with new stats
          Object.assign(props.demoStatus, {
            exists: response.data.demoStatus.exists,
            posts: response.data.demoStatus.stats.posts,
            candidates: response.data.demoStatus.stats.candidates,
            codes: response.data.demoStatus.stats.codes,
            votes: response.data.demoStatus.stats.votes,
            election_id: response.data.demoStatus.stats.election_id,
            election_name: response.data.demoStatus.stats.election_name
          })

          // Clear message after 5 seconds
          setTimeout(() => {
            message.value = ''
          }, 5000)

          // Emit update event for parent
          emit('demo-setup-updated', props.demoStatus)
        } else {
          messageType.value = 'error'
          message.value = response.data.message
        }
      } catch (error) {
        messageType.value = 'error'
        message.value = error.response?.data?.message || 'An error occurred. Please try again.'
      } finally {
        loading.value = false
      }
    }

    return {
      loading,
      message,
      messageType,
      setupDemo
    }
  }
}
</script>
