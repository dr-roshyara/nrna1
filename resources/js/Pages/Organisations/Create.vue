<template>
  <PublicDigitLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12 px-4">
      <div class="max-w-2xl mx-auto">

        <!-- Header -->
        <div class="text-center mb-10">
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-purple-100 mb-4">
            <BuildingOffice2Icon class="w-7 h-7 text-purple-600" />
          </div>
          <h1 class="text-2xl font-bold text-slate-900">Create Organisation</h1>
          <p class="text-slate-500 mt-1">Start a new organisation and invite your community</p>
        </div>

        <!-- Success flash -->
        <div v-if="page.props.flash?.success"
             class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center mb-6">
          <p class="text-green-700 font-medium">{{ page.props.flash.success }}</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

          <!-- Validation error summary -->
          <div v-if="Object.keys(page.props.errors ?? {}).length"
               class="mb-6 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
            <ul class="space-y-1">
              <li v-for="(msg, field) in page.props.errors" :key="field">{{ msg }}</li>
            </ul>
          </div>

          <form @submit.prevent="submit" class="space-y-6">

            <!-- Organisation Name -->
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Organisation Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                :class="inputClass"
                placeholder="e.g., NRNA Bavaria, Tech Community Nepal"
                autocomplete="organization"
              />
              <p class="mt-1 text-xs text-slate-400">Minimum 3 characters. A URL slug will be generated automatically.</p>
              <p v-if="page.props.errors?.name" class="mt-1 text-xs text-red-600">{{ page.props.errors.name }}</p>
            </div>

            <!-- Info box -->
            <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-600">
              <p class="font-medium mb-2 text-slate-700">What happens after creation?</p>
              <ul class="space-y-1.5 list-disc list-inside">
                <li>You become the <strong>owner</strong> of this organisation</li>
                <li>Invite members and manage membership applications</li>
                <li>Create elections and manage voters</li>
                <li>Send newsletters to members</li>
              </ul>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-2">
              <Link
                :href="route('dashboard')"
                class="flex-1 text-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="submitting || !form.name.trim()"
                class="flex-1 rounded-xl bg-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                {{ submitting ? 'Creating…' : 'Create Organisation' }}
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import { BuildingOffice2Icon } from '@heroicons/vue/24/outline'

const page = usePage()

const form = ref({ name: '' })
const submitting = ref(false)

const inputClass = 'w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors hover:border-slate-400'

const submit = () => {
  submitting.value = true
  router.post(route('organisations.store'), form.value, {
    preserveScroll: true,
    onFinish: () => { submitting.value = false },
  })
}
</script>
