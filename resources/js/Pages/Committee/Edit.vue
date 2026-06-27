<template>
  <PublicDigitLayout>
    <!-- Content -->
    <div class="flex-1 container mx-auto max-w-2xl py-12 px-4">
      <div class="bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $t('pages.committee.edit.title') }}</h1>
        <p class="text-gray-600 mb-8">Update committee details</p>
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Committee Name -->
          <div>
            <label for="name" class="block text-sm font-medium mb-1">
              {{ $t('pages.committee.edit.form.name') }}
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
              required
            />
            <span v-if="errors.name" class="text-red-600 text-sm mt-1">
              {{ errors.name }}
            </span>
          </div>

          <!-- Committee Status -->
          <div>
            <label for="status" class="block text-sm font-medium mb-1">
              {{ $t('pages.committee.edit.form.status') }}
            </label>
            <select
              id="status"
              v-model="form.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary"
              required
            >
              <option value="active">{{ $t('pages.committee.edit.form.status_active') }}</option>
              <option value="inactive">{{ $t('pages.committee.edit.form.status_inactive') }}</option>
            </select>
            <span v-if="errors.status" class="text-red-600 text-sm mt-1">
              {{ errors.status }}
            </span>
          </div>

          <!-- Geographic Reference -->
          <div>
            <label class="block text-sm font-medium mb-1">
              {{ $t('pages.committee.edit.form.geography') || 'Geographic Reference' }}
            </label>
            <GeographyCascader
              v-model="form.geo_reference"
              :organisation-slug="organisationSlug"
            />
            <span v-if="errors.geo_reference" class="text-red-600 text-sm mt-1">
              {{ errors.geo_reference }}
            </span>
          </div>

          <!-- Form Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="submit"
              :disabled="loading"
              class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark disabled:opacity-50"
            >
              {{ loading ? 'Updating...' : $t('pages.committee.edit.button_update') }}
            </button>
            <button
              type="button"
              @click="handleCancel"
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
            >
              {{ $t('pages.committee.edit.button_cancel') }}
            </button>
          </div>
        </form>
      </div>
    </div>

  </PublicDigitLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue';
import GeographyCascader from '@/Components/Geography/GeographyCascader.vue';

const props = defineProps({
  organisation: Object,
  committee: Object,
  organisationSlug: String,
});

const form = ref({
  name: props.committee.name || '',
  status: props.committee.status || 'active',
  geo_reference: props.committee.geo_reference || '',
});

const errors = ref({});
const loading = ref(false);

const handleSubmit = () => {
  loading.value = true;
  errors.value = {};

  // Submit via Inertia 2.0
  router.patch(
    route('committees.update', {
      organisation: props.organisation.slug,
      committee: props.committee.slug,
    }),
    form.value,
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        loading.value = false;
      },
      onError: (serverErrors) => {
        loading.value = false;
        errors.value = serverErrors;
      },
    }
  );
};

const handleCancel = () => {
  router.visit(
    route('committee.dashboard', {
      organisation: props.organisation.slug,
      committee: props.committee.slug
    })
  );
};
</script>
