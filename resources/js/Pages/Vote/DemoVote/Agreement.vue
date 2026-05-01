<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-indigo-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">
          Voting Agreement
        </h1>
        <p class="mt-2 text-yellow-700 bg-yellow-50 px-4 py-2 rounded-sm inline-block">
          🎮 DEMO MODE - This is a test election
        </p>
      </div>

      <!-- Agreement Card -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
          <h2 class="text-2xl font-bold text-white">
            {{ election_name }}
          </h2>
        </div>

        <div class="p-8 space-y-6">
          <!-- Vote Summary -->
          <div class="bg-primary-50 border-l-4 border-primary-500 p-4">
            <p class="text-primary-900">
              You are about to vote for
              <strong class="text-lg">{{ votes_count }} position(s)</strong>.
            </p>
            <p class="text-primary-700 text-sm mt-2">
              You selected candidates in {{ votes_count }} post(s). Please continue to review your choices.
            </p>
          </div>

          <!-- Agreement Terms -->
          <div class="space-y-4">
            <h3 class="text-lg font-semibold text-neutral-900">Understanding Your Vote</h3>

            <div class="space-y-3 text-sm text-neutral-700">
              <div class="flex gap-3">
                <div class="shrink-0 text-indigo-600 font-bold">✓</div>
                <p>Your vote will be recorded anonymously and securely</p>
              </div>
              <div class="flex gap-3">
                <div class="shrink-0 text-indigo-600 font-bold">✓</div>
                <p>You can only vote once in this election</p>
              </div>
              <div class="flex gap-3">
                <div class="shrink-0 text-indigo-600 font-bold">✓</div>
                <p>Your vote cannot be changed after submission</p>
              </div>
              <div class="flex gap-3">
                <div class="shrink-0 text-indigo-600 font-bold">✓</div>
                <p>You will review your selections before final submission</p>
              </div>
              <div class="flex gap-3">
                <div class="shrink-0 text-indigo-600 font-bold">✓</div>
                <p>A verification code will be provided for your records</p>
              </div>
            </div>
          </div>

          <!-- Checkbox Agreement -->
          <div class="pt-6 border-t border-neutral-200">
            <label class="flex items-start gap-3 cursor-pointer">
              <input
                v-model="agree"
                type="checkbox"
                class="mt-1 h-5 w-5 rounded-sm border-neutral-300 text-indigo-600 cursor-pointer"
              />
              <span class="text-neutral-700">
                I understand and accept the voting agreement. I am ready to proceed with my vote.
              </span>
            </label>
            <p v-if="errors.agree" class="mt-2 text-danger-600 text-sm">
              {{ errors.agree }}
            </p>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-4 justify-center">
        <button
          @click="goBack"
          class="px-6 py-2 border border-neutral-300 rounded-lg text-neutral-700 hover:bg-neutral-50 font-medium transition"
        >
          ← Back to Voting
        </button>
        <button
          @click="submitAgreement"
          :disabled="!agree || loading"
          class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="loading" class="inline-block mr-2">⏳</span>
          {{ loading ? 'Processing...' : 'Continue to Review' }}
        </button>
      </div>

      <!-- Info Box -->
      <div class="mt-8 bg-indigo-50 border border-indigo-200 rounded-lg p-4 text-center text-sm text-indigo-900">
        <p>
          💡 This is a <strong>demo election</strong>. You can vote multiple times for testing purposes.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  election_name: String,
  votes_count: Number,
  slug: String,
  useSlugPath: Boolean,
})

const agree = ref(false)
const loading = ref(false)
const errors = ref({})

const goBack = () => {
  router.get(
    route(props.useSlugPath ? 'slug.demo-vote.create' : 'demo-vote.create',
          props.useSlugPath ? { vslug: props.slug } : {})
  )
}

const submitAgreement = () => {
  errors.value = {}

  if (!agree.value) {
    errors.value.agree = 'You must agree to continue'
    return
  }

  loading.value = true

  const form = useForm({
    agree: agree.value,
  })

  const routeName = props.useSlugPath ? 'slug.demo-vote.agreement.submit' : 'demo-vote.agreement.submit'
  const params = props.useSlugPath ? { vslug: props.slug } : {}

  form.post(route(routeName, params), {
    onError: (formErrors) => {
      errors.value = formErrors
      loading.value = false
    },
  })
}
</script>

