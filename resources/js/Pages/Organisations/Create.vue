<template>
  <PublicDigitLayout>
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-white focus:p-2 focus:rounded focus:shadow-lg">
      Skip to main content
    </a>

    <main id="main-content" role="main" tabindex="-1">
      <div class="min-h-screen bg-gradient-to-br from-neutral-50 via-primary-50/20 to-neutral-50 py-12 px-4">
        <div class="max-w-2xl mx-auto">

          <!-- Header -->
          <div class="mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-lg bg-primary-100 mb-4" aria-hidden="true">
              <BuildingOffice2Icon class="w-7 h-7 text-primary-600" />
            </div>
            <h1 id="page-title" class="text-3xl font-bold text-neutral-900">Create Organisation</h1>
            <p class="text-neutral-600 mt-2 text-sm">Set up your organisation and start managing elections</p>
          </div>

          <!-- Success state -->
          <div v-if="page.props.flash?.success"
               ref="successMessage"
               role="status"
               aria-live="polite"
               tabindex="-1"
               class="bg-success-50 border border-success-200 rounded-lg p-4 mb-6">
            <p class="text-success-700 font-medium">{{ page.props.flash.success }}</p>
          </div>

          <!-- Form card -->
          <div class="bg-white rounded-xl shadow-sm border border-neutral-200 hover:shadow-md transition-shadow duration-300 p-6 md:p-8">

            <!-- Error summary -->
            <div v-if="Object.keys(page.props.errors ?? {}).length"
                 role="alert"
                 aria-live="assertive"
                 class="mb-6 bg-danger-50 border border-danger-200 rounded-lg px-4 py-3 text-sm">
              <p class="font-semibold text-danger-700 mb-2">⚠️ Please correct the following errors:</p>
              <ul class="space-y-1">
                <li v-for="(msg, field) in page.props.errors" :key="field" class="text-danger-600 text-xs">{{ msg }}</li>
              </ul>
            </div>

            <form @submit.prevent="submit" novalidate class="space-y-6" aria-labelledby="page-title" enctype="multipart/form-data">

              <!-- Organisation Name -->
              <div>
                <div class="flex justify-between items-center mb-2">
                  <label for="organisation-name" class="text-sm font-semibold text-neutral-700">
                    Organisation Name
                    <span class="text-danger-500" aria-hidden="true">*</span>
                    <span class="sr-only">required</span>
                  </label>
                  <span class="text-xs text-neutral-500" aria-live="polite">{{ form.name.length }}/255</span>
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
                <p id="name-hint" class="mt-2 text-xs text-neutral-500">
                  Minimum 3 characters. A URL slug will be generated automatically.
                </p>
                <p v-if="page.props.errors?.name" role="alert" class="mt-1 text-xs text-danger-600">
                  {{ page.props.errors.name }}
                </p>
              </div>

              <!-- Email -->
              <div>
                <label for="organisation-email" class="block text-sm font-semibold text-neutral-700 mb-2">
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
                <p v-if="page.props.errors?.email" role="alert" class="mt-1 text-xs text-danger-600">
                  {{ page.props.errors.email }}
                </p>
              </div>

              <!-- Representative -->
              <div>
                <label for="organisation-representative" class="block text-sm font-semibold text-neutral-700 mb-2">
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
                <p class="mt-2 text-xs text-neutral-500">The primary contact or head of the organisation.</p>
                <p v-if="page.props.errors?.representative" role="alert" class="mt-1 text-xs text-danger-600">
                  {{ page.props.errors.representative }}
                </p>
              </div>

              <!-- Languages -->
              <div>
                <fieldset>
                  <legend class="text-sm font-semibold text-neutral-700 mb-3">Organisation Languages</legend>
                  <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <label
                      v-for="lang in availableLanguages"
                      :key="lang.code"
                      class="flex items-center gap-2 rounded-lg border px-3 py-2.5 cursor-pointer transition-all duration-200"
                      :class="form.languages.includes(lang.code)
                        ? 'border-primary-300 bg-primary-50'
                        : 'border-neutral-200 hover:border-neutral-300'"
                    >
                      <input
                        type="checkbox"
                        :value="lang.code"
                        v-model="form.languages"
                        class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500"
                      />
                      <span class="text-sm text-neutral-700 font-medium">{{ lang.flag }} {{ lang.label }}</span>
                    </label>
                  </div>
                  <p v-if="page.props.errors?.languages" role="alert" class="mt-2 text-xs text-danger-600">
                    {{ page.props.errors.languages }}
                  </p>
                </fieldset>
              </div>

              <!-- Logo Upload -->
              <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-2">Organisation Logo</label>
                <div
                  class="relative flex flex-col items-center justify-center rounded-lg border-2 border-dashed px-6 py-8 transition-all duration-300"
                  :class="logoPreview ? 'border-primary-300 bg-primary-50' : 'border-neutral-300 bg-neutral-50 hover:border-neutral-400 hover:bg-neutral-50'"
                >
                  <!-- Preview -->
                  <img
                    v-if="logoPreview"
                    :src="logoPreview"
                    alt="Logo preview"
                    class="w-24 h-24 object-contain rounded-lg mb-3"
                  />
                  <BuildingOffice2Icon v-else class="w-10 h-10 text-neutral-300 mb-3" />

                  <p class="text-sm text-neutral-700 font-medium mb-1">
                    <span v-if="logoPreview">Change logo</span>
                    <span v-else>Upload logo</span>
                  </p>
                  <p class="text-xs text-neutral-500">PNG, JPG, SVG, WebP — max 2 MB</p>

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
                  class="mt-2 text-xs text-danger-600 hover:text-danger-700 focus:outline-none focus:underline font-medium transition-colors"
                >
                  Remove logo
                </button>
                <p v-if="page.props.errors?.logo" role="alert" class="mt-1 text-xs text-danger-600">
                  {{ page.props.errors.logo }}
                </p>
              </div>

              <!-- Info box -->
              <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 text-sm" role="note" aria-label="What happens after creating an organisation">
                <div class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm6 0a1 1 0 11-2 0 1 1 0 012 0zm-6 4a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                  </svg>
                  <div class="flex-1">
                    <p class="font-semibold text-primary-900 mb-2">What happens next?</p>
                    <ul class="space-y-1.5 text-primary-700 text-xs">
                      <li class="flex items-start gap-2"><span class="text-primary-600 mt-0.5">✓</span><span>You become the owner of this organisation</span></li>
                      <li class="flex items-start gap-2"><span class="text-primary-600 mt-0.5">✓</span><span>Invite members and manage applications</span></li>
                      <li class="flex items-start gap-2"><span class="text-primary-600 mt-0.5">✓</span><span>Create elections and manage voters</span></li>
                      <li class="flex items-start gap-2"><span class="text-primary-600 mt-0.5">✓</span><span>Send newsletters to members</span></li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Membership System Configuration -->
              <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                <fieldset>
                  <legend class="text-sm font-semibold text-neutral-900 mb-3">Membership System</legend>
                  <p class="text-xs text-neutral-600 mb-4">
                    Choose how voters are eligible for elections in this organisation.
                  </p>
                  <div class="space-y-3">
                    <label class="flex items-start gap-3 p-3 border border-neutral-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200"
                           :class="form.uses_full_membership ? 'border-primary-300 bg-primary-50' : ''">
                      <input
                        type="radio"
                        v-model="form.uses_full_membership"
                        :value="true"
                        class="mt-1 border-neutral-300 text-primary-600 focus:ring-primary-500"
                      />
                      <div class="flex-1">
                        <span class="block font-medium text-neutral-900">Full Membership</span>
                        <span class="block text-xs text-neutral-600 mt-1">
                          Voters must be formal members with paid fees. Best for organisations with membership tracking.
                        </span>
                      </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 border border-neutral-200 rounded-lg hover:bg-white cursor-pointer transition-all duration-200"
                           :class="!form.uses_full_membership ? 'border-accent-300 bg-accent-50' : ''">
                      <input
                        type="radio"
                        v-model="form.uses_full_membership"
                        :value="false"
                        class="mt-1 border-neutral-300 text-primary-600 focus:ring-primary-500"
                      />
                      <div class="flex-1">
                        <span class="block font-medium text-neutral-900">Election-Only</span>
                        <span class="block text-xs text-neutral-600 mt-1">
                          Any registered user can vote. Best for simple elections without membership tracking.
                        </span>
                      </div>
                    </label>
                  </div>
                </fieldset>
              </div>


              <!-- Actions -->
              <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4 border-t border-neutral-100">
                <Link
                  :href="route('dashboard')"
                  aria-label="Cancel and return to dashboard"
                  class="sm:flex-1 text-center rounded-lg border border-neutral-300 px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-300"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="submitting || form.name.trim().length < 3"
                  :aria-busy="submitting"
                  class="sm:flex-1 rounded-lg bg-gradient-to-r from-primary-600 to-primary-700 px-4 py-2.5 text-sm font-semibold text-white hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
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
  'w-full rounded-lg border px-3 py-2.5 text-sm text-neutral-900',
  'transition-all duration-300 focus:outline-none focus:ring-2 focus:border-transparent',
  page.props.errors?.[field]
    ? 'border-danger-300 bg-danger-50 focus:ring-danger-500'
    : 'border-neutral-300 bg-white hover:border-neutral-400 focus:ring-primary-500',
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

<style scoped>
/* Smooth entrance animations */
@keyframes slideUpFade {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

main {
  animation: slideUpFade 0.5s ease-out;
}

/* Refined focus states */
input:focus-visible,
button:focus-visible,
a:focus-visible {
  outline: 2px solid rgb(37, 99, 235);
  outline-offset: 2px;
}

/* Smooth transitions for interactive elements */
button:not(:disabled),
a {
  transition: all 0.3s ease;
}
</style>
