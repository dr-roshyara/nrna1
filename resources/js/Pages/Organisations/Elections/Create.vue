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
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Create New Election</h1>
        <p class="text-gray-500 text-sm mb-8">
          Organisation: <span class="font-medium text-gray-700">{{ organisation.name }}</span>
        </p>

        <form @submit.prevent="submit" novalidate>

          <!-- Name -->
          <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              Election Name <span class="text-red-500">*</span>
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              maxlength="255"
              placeholder="e.g. General Election 2026"
              class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="errors.name ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <!-- Description -->
          <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
              Description <span class="text-gray-400 font-normal">(optional)</span>
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              maxlength="5000"
              placeholder="Brief description of the election..."
              class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="errors.description ? 'border-red-400' : 'border-gray-300'"
            />
            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
              <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                Start Date <span class="text-red-500">*</span>
              </label>
              <input
                id="start_date"
                v-model="form.start_date"
                type="date"
                class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="errors.start_date ? 'border-red-400' : 'border-gray-300'"
              />
              <p v-if="errors.start_date" class="mt-1 text-sm text-red-600">{{ errors.start_date }}</p>
            </div>

            <div>
              <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                End Date <span class="text-red-500">*</span>
              </label>
              <input
                id="end_date"
                v-model="form.end_date"
                type="date"
                class="w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                :class="errors.end_date ? 'border-red-400' : 'border-gray-300'"
              />
              <p v-if="errors.end_date" class="mt-1 text-sm text-red-600">{{ errors.end_date }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-between">
            <a
              :href="route('organisations.show', organisation.slug)"
              class="text-sm text-gray-500 hover:text-gray-700 underline"
            >
              Cancel
            </a>
            <button
              type="submit"
              :disabled="isLoading"
              class="rounded-md bg-blue-600 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isLoading ? 'Creating…' : 'Create Election' }}
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
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
})

const page    = usePage()
const errors  = computed(() => page.props.errors ?? {})
const isLoading = ref(false)

const form = reactive({
  name:        '',
  description: '',
  start_date:  '',
  end_date:    '',
})

const submit = () => {
  isLoading.value = true
  router.post(
    route('organisations.elections.store', props.organisation.slug),
    { ...form },
    {
      preserveScroll: true,
      onFinish: () => { isLoading.value = false },
    }
  )
}
</script>
