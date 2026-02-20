<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 py-12 flex items-center justify-center">
    <div class="w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Demo Mode Badge -->
      <div class="text-center mb-8">
        <div class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
          🎮 DEMO ELECTION - Testing Mode
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-8 text-center">
          <h1 class="text-2xl font-bold text-white">
            {{ election_name }}
          </h1>
          <p class="text-indigo-100 text-sm mt-2">
            Verification Step 1 of 5
          </p>
        </div>

        <!-- Content -->
        <div class="p-8 space-y-6">
          <!-- Instructions -->
          <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
            <p class="text-blue-900 font-semibold">
              📋 How to Verify Your Code
            </p>
            <ol class="mt-2 space-y-1 text-blue-800 text-sm">
              <li>1. Copy the verification code below</li>
              <li>2. Paste it into the input field</li>
              <li>3. Click "Verify Code" to continue</li>
            </ol>
          </div>

          <!-- Code Display -->
          <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border-2 border-indigo-200 rounded-lg p-6 text-center">
            <p class="text-gray-600 text-sm mb-3">
              Your Verification Code:
            </p>
            <div
              class="font-mono text-5xl font-bold text-indigo-600 tracking-widest mb-4 select-all cursor-pointer hover:text-indigo-700 transition"
              @click="copyCode"
            >
              {{ verification_code }}
            </div>
            <p class="text-xs text-gray-500">
              (Click to copy or use the copy button below)
            </p>
          </div>

          <!-- Time Remaining -->
          <div class="bg-amber-50 border-l-4 border-amber-500 p-4">
            <p class="text-amber-900 text-sm">
              ⏱️ Code expires in: <strong>{{ code_expires_in }} minutes</strong>
            </p>
            <p class="text-amber-700 text-xs mt-1">
              Time elapsed: {{ code_duration }} minute(s)
            </p>
          </div>

          <!-- Copy Button -->
          <button
            @click="copyCode"
            class="w-full px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 font-semibold rounded-lg transition flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            {{ copied ? 'Copied!' : 'Copy Code' }}
          </button>

          <!-- Code Entry Form -->
          <form @submit.prevent="submitCode" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-2">
                Enter Verification Code:
              </label>
              <input
                v-model="enteredCode"
                type="text"
                placeholder="XXXXXX"
                maxlength="6"
                class="w-full px-4 py-3 text-center text-2xl font-mono font-bold uppercase tracking-widest border-2 border-gray-300 rounded-lg focus:border-indigo-600 focus:outline-none transition"
                :class="{ 'border-red-500 focus:border-red-500': errors.voting_code }"
              />
              <p v-if="errors.voting_code" class="mt-2 text-red-600 text-sm font-semibold">
                ❌ {{ errors.voting_code }}
              </p>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="loading || !enteredCode"
              class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
            >
              <span v-if="loading" class="inline-block">⏳</span>
              {{ loading ? 'Verifying...' : 'Verify Code' }}
            </button>
          </form>

          <!-- Demo Info -->
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center text-sm text-green-900">
            <p>
              💡 In this demo, both the code and answer are shown.
            </p>
            <p class="text-xs mt-1">
              In real voting, the code would be sent via email.
            </p>
          </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-8 py-4 bg-gray-50 text-center">
          <p class="text-xs text-gray-600">
            Step 1 of 5: Code Verification
          </p>
        </div>
      </div>

      <!-- Additional Info -->
      <div class="mt-6 text-center">
        <button
          @click="resetForm"
          class="text-indigo-600 hover:text-indigo-700 text-sm font-medium"
        >
          Need a new code? ↻
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  name: String,
  user_id: String,
  election_name: String,
  verification_code: String,
  code_duration: Number,
  code_expires_in: Number,
  slug: String,
  useSlugPath: Boolean,
})

const enteredCode = ref('')
const loading = ref(false)
const errors = ref({})
const copied = ref(false)

const copyCode = () => {
  navigator.clipboard.writeText(props.verification_code)
  copied.value = true
  setTimeout(() => {
    copied.value = false
  }, 2000)
}

const submitCode = () => {
  errors.value = {}
  loading.value = true

  const form = useForm({
    voting_code: enteredCode.value.toUpperCase(),
  })

  const routeName = props.useSlugPath ? 'slug.demo-code.store' : 'demo-code.store'
  const params = props.useSlugPath ? { vslug: props.slug } : {}

  form.post(route(routeName, params), {
    onError: (formErrors) => {
      errors.value = formErrors
      loading.value = false
    },
  })
}

const resetForm = () => {
  enteredCode.value = ''
  errors.value = {}
  Inertia.get(
    route(props.useSlugPath ? 'slug.demo-code.create' : 'demo-code.create',
          props.useSlugPath ? { vslug: props.slug } : {})
  )
}
</script>
