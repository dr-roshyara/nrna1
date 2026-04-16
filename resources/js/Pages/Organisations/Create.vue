<template>
  <PublicDigitLayout>
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-white focus:p-2 focus:rounded focus:shadow-lg">
      Skip to main content
    </a>

    <main id="main-content" role="main" tabindex="-1">
      <div class="min-h-screen bg-gradient-to-br from-purple-100 via-indigo-50 to-slate-100 py-12 px-4">
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
          <div class="bg-gradient-to-b from-white to-slate-50 rounded-2xl shadow-md border border-purple-100 p-6 md:p-8">

            <!-- Error summary -->
            <div v-if="Object.keys(page.props.errors ?? {}).length"
                 role="alert"
                 aria-live="assertive"
                 class="mb-6 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm">
              <p class="font-semibold text-red-800 mb-2">Please correct the following errors:</p>
              <ul class="space-y-1">
                <li v-for="(msg, field) in page.props.errors" :key="field" class="text-red-700">{{ msg }}</li>
              </ul>
            </div>

            <form @submit.prevent="submit" novalidate class="space-y-6" aria-labelledby="page-title" enctype="multipart/form-data">

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
                  :class="inputClass('name')"
                  placeholder="e.g., NRNA Bavaria, Tech Community Nepal"
                  autocomplete="organization"
                />
                <p id="name-hint" class="mt-1 text-xs text-slate-500">
                  Minimum 3 characters. A URL slug will be generated automatically.
                </p>
                <p v-if="page.props.errors?.name" role="alert" class="mt-1 text-xs text-red-600">
                  {{ page.props.errors.name }}
                </p>
              </div>

              <!-- Email -->
              <div>
                <label for="organisation-email" class="block text-sm font-medium text-slate-700 mb-1">
                  Contact Email
                </label>
                <input
                  id="organisation-email"
                  v-model="form.email"
                  type="email"
                  maxlength="255"
                  :aria-invalid="!!page.props.errors?.email"
                  :class="inputClass('email')"
                  placeholder="contact@organisation.org"
                  autocomplete="email"
                />
                <p v-if="page.props.errors?.email" role="alert" class="mt-1 text-xs text-red-600">
                  {{ page.props.errors.email }}
                </p>
              </div>

              <!-- Representative -->
              <div>
                <label for="organisation-representative" class="block text-sm font-medium text-slate-700 mb-1">
                  Representative Name
                </label>
                <input
                  id="organisation-representative"
                  v-model="form.representative"
                  type="text"
                  maxlength="255"
                  :aria-invalid="!!page.props.errors?.representative"
                  :class="inputClass('representative')"
                  placeholder="e.g., Dr. Jane Smith"
                />
                <p class="mt-1 text-xs text-slate-500">The primary contact or head of the organisation.</p>
                <p v-if="page.props.errors?.representative" role="alert" class="mt-1 text-xs text-red-600">
                  {{ page.props.errors.representative }}
                </p>
              </div>

              <!-- Languages -->
              <div>
                <fieldset>
                  <legend class="text-sm font-medium text-slate-700 mb-2">Organisation Languages</legend>
                  <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <label
                      v-for="lang in availableLanguages"
                      :key="lang.code"
                      class="flex items-center gap-2 rounded-lg border px-3 py-2 cursor-pointer transition-colors"
                      :class="form.languages.includes(lang.code)
                        ? 'border-purple-400 bg-purple-50'
                        : 'border-slate-200 hover:border-slate-300'"
                    >
                      <input
                        type="checkbox"
                        :value="lang.code"
                        v-model="form.languages"
                        class="rounded border-slate-300 text-purple-600 focus:ring-purple-500"
                      />
                      <span class="text-sm text-slate-700">{{ lang.flag }} {{ lang.label }}</span>
                    </label>
                  </div>
                  <p v-if="page.props.errors?.languages" role="alert" class="mt-1 text-xs text-red-600">
                    {{ page.props.errors.languages }}
                  </p>
                </fieldset>
              </div>

              <!-- Logo Upload -->
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Organisation Logo</label>
                <div
                  class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed px-6 py-8 transition-colors"
                  :class="logoPreview ? 'border-purple-300 bg-purple-50' : 'border-slate-300 bg-slate-50 hover:border-slate-400'"
                >
                  <!-- Preview -->
                  <img
                    v-if="logoPreview"
                    :src="logoPreview"
                    alt="Logo preview"
                    class="w-24 h-24 object-contain rounded-lg mb-3"
                  />
                  <BuildingOffice2Icon v-else class="w-10 h-10 text-slate-300 mb-3" />

                  <p class="text-sm text-slate-600 mb-1">
                    <span v-if="logoPreview">Change logo</span>
                    <span v-else>Upload logo</span>
                  </p>
                  <p class="text-xs text-slate-400">PNG, JPG, SVG, WebP — max 2 MB</p>

                  <input
                    ref="logoInput"
                    type="file"
                    accept="image/jpeg,image/png,image/gif,image/svg+xml,image/webp"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    @change="onLogoChange"
                  />
                </div>
                <button
                  v-if="logoPreview"
                  type="button"
                  @click="clearLogo"
                  class="mt-2 text-xs text-red-600 hover:text-red-700 focus:outline-none focus:underline"
                >
                  Remove logo
                </button>
                <p v-if="page.props.errors?.logo" role="alert" class="mt-1 text-xs text-red-600">
                  {{ page.props.errors.logo }}
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

              <!-- Membership System Configuration -->
              <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                <fieldset>
                  <legend class="text-sm font-semibold text-slate-900 mb-3">Membership System</legend>
                  <p class="text-xs text-slate-600 mb-4">
                    Choose how voters are eligible for elections in this organisation.
                  </p>
                  <div class="space-y-3">
                    <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg hover:bg-white cursor-pointer transition"
                           :class="form.uses_full_membership ? 'border-purple-300 bg-purple-50' : ''">
                      <input
                        type="radio"
                        v-model="form.uses_full_membership"
                        :value="true"
                        class="mt-1 border-slate-300 text-purple-600 focus:ring-purple-500"
                      />
                      <div class="flex-1">
                        <span class="block font-medium text-slate-900">Full Membership</span>
                        <span class="block text-xs text-slate-600 mt-1">
                          Voters must be formal members with paid fees. Best for organisations with membership tracking.
                        </span>
                      </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-lg hover:bg-white cursor-pointer transition"
                           :class="!form.uses_full_membership ? 'border-green-300 bg-green-50' : ''">
                      <input
                        type="radio"
                        v-model="form.uses_full_membership"
                        :value="false"
                        class="mt-1 border-slate-300 text-purple-600 focus:ring-purple-500"
                      />
                      <div class="flex-1">
                        <span class="block font-medium text-slate-900">Election-Only</span>
                        <span class="block text-xs text-slate-600 mt-1">
                          Any registered user can vote. Best for simple elections without membership tracking.
                        </span>
                      </div>
                    </label>
                  </div>
                </fieldset>
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
const form = ref({
  name: '',
  email: '',
  representative: '',
  languages: [],
  logo: null,
  uses_full_membership: true,
})
const submitting = ref(false)
const successMessage = ref(null)
const logoPreview = ref(null)
const logoInput = ref(null)

const availableLanguages = [
  { code: 'en', label: 'English', flag: '🇬🇧' },
  { code: 'de', label: 'Deutsch', flag: '🇩🇪' },
  { code: 'np', label: 'नेपाली',  flag: '🇳🇵' },
]

const inputClass = (field) => [
  'w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900',
  'transition-colors focus:outline-none focus:ring-2 focus:border-transparent',
  page.props.errors?.[field]
    ? 'border-red-400 bg-red-50 focus:ring-red-500'
    : 'border-slate-300 bg-white hover:border-slate-400 focus:ring-purple-500',
]

const onLogoChange = (e) => {
  const file = e.target.files?.[0]
  if (!file) return
  form.value.logo = file
  logoPreview.value = URL.createObjectURL(file)
}

const clearLogo = () => {
  form.value.logo = null
  logoPreview.value = null
  if (logoInput.value) logoInput.value.value = ''
}

const submit = () => {
  if (form.value.name.trim().length < 3) return
  submitting.value = true

  const data = new FormData()
  data.append('name', form.value.name)
  if (form.value.email) data.append('email', form.value.email)
  if (form.value.representative) data.append('representative', form.value.representative)
  form.value.languages.forEach(lang => data.append('languages[]', lang))
  if (form.value.logo) data.append('logo', form.value.logo)
  data.append('uses_full_membership', form.value.uses_full_membership ? '1' : '0')

  router.post(route('organisations.store'), data, {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: async () => {
      await nextTick()
      successMessage.value?.focus()
    },
    onFinish: () => { submitting.value = false },
  })
}
</script>
