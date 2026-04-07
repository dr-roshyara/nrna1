<template>
  <PublicDigitLayout>
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-white focus:p-2 focus:rounded focus:shadow-lg">
      Skip to main content
    </a>

    <main id="main-content" role="main" tabindex="-1">
      <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12 px-4">
        <div class="max-w-2xl mx-auto">

          <!-- Header -->
          <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-purple-100 mb-4" aria-hidden="true">
              <BuildingOffice2Icon class="w-7 h-7 text-purple-600" />
            </div>
            <h1 id="page-title" class="text-2xl font-bold text-slate-900">Create Organisation</h1>
            <p class="text-slate-500 mt-1">Start a new organisation and invite your community</p>
          </div>

          <!-- Success state -->
          <div v-if="page.props.flash?.success"
               ref="successMessage"
               role="status"
               aria-live="polite"
               tabindex="-1"
               class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center mb-6">
            <p class="text-green-700 font-medium">{{ page.props.flash.success }}</p>
          </div>

          <!-- Form card -->
          <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">

            <!-- Error summary -->
            <div v-if="Object.keys(page.props.errors ?? {}).length"
                 role="alert"
                 aria-live="assertive"
                 class="mb-6 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm">
              <p class="font-semibold text-red-800 mb-2">Please correct the following errors:</p>
              <ul class="space-y-1">
                <li v-for="(msg, field) in page.props.errors" :key="field">
                  <a href="#"
                     @click.prevent="focusField(field)"
                     class="text-red-700 hover:underline focus:outline-none focus:ring-2 focus:ring-red-500 rounded">
                    {{ msg }}
                  </a>
                </li>
              </ul>
            </div>

            <form @submit.prevent="submit" novalidate class="space-y-6" aria-labelledby="page-title">

              <!-- Organisation Name -->
              <div>
                <div class="flex justify-between items-center mb-1">
                  <label for="organisation-name" class="text-sm font-medium text-slate-700">
                    Organisation Name
                    <span class="text-red-500" aria-hidden="true">*</span>
                    <span class="sr-only">required</span>
                  </label>
                  <span class="text-xs text-slate-400" aria-live="polite">{{ form.name.length }}/255</span>
                </div>

                <input
                  id="organisation-name"
                  v-model="form.name"
                  type="text"
                  required
                  maxlength="255"
                  aria-required="true"
                  :aria-invalid="!!page.props.errors?.name"
                  aria-describedby="name-hint name-error"
                  :class="[
                    'w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900',
                    'transition-colors focus:outline-none focus:ring-2 focus:border-transparent',
                    page.props.errors?.name
                      ? 'border-red-400 bg-red-50 focus:ring-red-500'
                      : 'border-slate-300 bg-white hover:border-slate-400 focus:ring-purple-500',
                  ]"
                  placeholder="e.g., NRNA Bavaria, Tech Community Nepal"
                  autocomplete="organization"
                />

                <p id="name-hint" class="mt-1 text-xs text-slate-500">
                  Minimum 3 characters. A URL slug will be generated automatically.
                </p>
                <p v-if="page.props.errors?.name"
                   id="name-error"
                   role="alert"
                   class="mt-1 text-xs text-red-600">
                  {{ page.props.errors.name }}
                </p>
              </div>

              <!-- Info box -->
              <div class="bg-slate-50 rounded-xl p-4 text-sm" role="note" aria-label="What happens after creating an organisation">
                <p class="font-medium mb-2 text-slate-700">What happens after creation?</p>
                <ul class="space-y-1.5 list-disc list-inside text-slate-600">
                  <li>You become the <strong>owner</strong> of this organisation</li>
                  <li>Invite members and manage membership applications</li>
                  <li>Create elections and manage voters</li>
                  <li>Send newsletters to members</li>
                </ul>
              </div>

              <!-- Actions -->
              <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                <Link
                  :href="route('dashboard')"
                  aria-label="Cancel and return to dashboard"
                  class="sm:flex-1 text-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="submitting || form.name.trim().length < 3"
                  :aria-busy="submitting"
                  class="sm:flex-1 rounded-xl bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  <span v-if="submitting" class="inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <span aria-hidden="true">Creating…</span>
                    <span class="sr-only">Processing your request, please wait</span>
                  </span>
                  <span v-else>Create Organisation</span>
                </button>
              </div>

            </form>
          </div>

        </div>
      </div>
    </main>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import { BuildingOffice2Icon } from '@heroicons/vue/24/outline'

const page = usePage()
const form = ref({ name: '' })
const submitting = ref(false)
const successMessage = ref(null)

const focusField = (field) => {
  const el = document.getElementById('organisation-name')
  if (el) {
    el.focus()
    el.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

const submit = () => {
  if (form.value.name.trim().length < 3) return
  submitting.value = true
  router.post(route('organisations.store'), form.value, {
    preserveScroll: true,
    onSuccess: async () => {
      await nextTick()
      successMessage.value?.focus()
    },
    onFinish: () => { submitting.value = false },
  })
}
</script>
