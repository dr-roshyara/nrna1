<template>
  <fieldset class="space-y-6">
    <legend class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      {{ $t('organization.form.step_2_title', { fallback: 'Registeradresse' }) }}
    </legend>

    <!-- Street -->
    <FormInput
      id="org-street"
      :label="$t('organization.form.street_label', { fallback: 'Straße und Hausnummer' })"
      :model-value="data.street"
      :placeholder="$t('organization.form.street_placeholder', { fallback: 'Münchner Straße 1' })"
      :helper="$t('organization.form.street_helper', { fallback: 'Registeradresse des Vereins' })"
      :error="errors.street"
      required
      @update:model-value="$emit('update', 'street', $event)"
    />

    <!-- City -->
    <FormInput
      id="org-city"
      :label="$t('organization.form.city_label', { fallback: 'Ort' })"
      :model-value="data.city"
      :placeholder="$t('organization.form.city_placeholder', { fallback: 'München' })"
      :error="errors.city"
      required
      @update:model-value="$emit('update', 'city', $event)"
    />

    <!-- Postal Code -->
    <FormInput
      id="org-zip"
      :label="$t('organization.form.zip_label', { fallback: 'Postleitzahl' })"
      :model-value="data.zip"
      :placeholder="$t('organization.form.zip_placeholder', { fallback: '80331' })"
      :helper="$t('organization.form.zip_helper', { fallback: '5-stellige deutsche PLZ' })"
      :error="errors.zip"
      required
      @update:model-value="$emit('update', 'zip', $event)"
    />

    <!-- Country (pre-filled) -->
    <div class="space-y-2">
      <label for="org-country" class="block text-sm font-semibold text-gray-900 dark:text-white">
        {{ $t('organization.form.country_label', { fallback: 'Land' }) }}
      </label>
      <select
        id="org-country"
        :value="data.country"
        disabled
        class="w-full px-4 py-2.5 rounded-lg border-2 bg-gray-100 dark:bg-gray-800
               border-gray-300 dark:border-gray-600
               text-gray-600 dark:text-gray-400 cursor-not-allowed opacity-50"
      >
        <option value="DE">{{ $t('organization.form.country_germany', { fallback: 'Deutschland' }) }}</option>
      </select>
      <p class="text-xs text-gray-600 dark:text-gray-400">
        {{ $t('organization.form.country_info', {
          fallback: 'Public Digit ist derzeit auf Deutschland ausgerichtet'
        }) }}
      </p>
    </div>

    <!-- Info box -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600 p-4 rounded-r-lg">
      <p class="text-sm text-gray-700 dark:text-gray-300">
        <strong>{{ $t('organization.form.legal_info_title', { fallback: 'Warum diese Adresse?' }) }}</strong>
        {{ $t('organization.form.step_2_info', {
          fallback: 'Diese Adresse wird für rechtliche Zustellungen und die Kommunikation mit Behörden verwendet.'
        }) }}
      </p>
    </div>
  </fieldset>
</template>

<script setup>
defineProps({
  data: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
});

defineEmits(['update']);
</script>

<script>
import FormInput from './FormInput.vue';

export default {
  components: { FormInput },
};
</script>
