<template>
  <fieldset class="space-y-6">
    <legend class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      {{ $t('organisation.form.step_1_title', { fallback: 'Grunddaten' }) }}
    </legend>

    <!-- organisation Name -->
    <FormInput
      id="org-name"
      :label="$t('organisation.form.name_label', { fallback: 'Organisationname' })"
      :model-value="data.name"
      :placeholder="$t('organisation.form.name_placeholder', { fallback: 'z.B. Turnverein München 1860 e.V.' })"
      :helper="$t('organisation.form.name_helper', { fallback: 'exakt wie im Vereinsregister' })"
      :error="errors.name"
      required
      @update:model-value="$emit('update', 'name', $event)"
    />

    <!-- Email for official communication -->
    <FormInput
      id="org-email"
      type="email"
      :label="$t('organisation.form.email_label', { fallback: 'E-Mail für Zustellungen' })"
      :model-value="data.email"
      :placeholder="$t('organisation.form.email_placeholder', { fallback: 'vorstand@tv-muenchen.de' })"
      :helper="$t('organisation.form.email_helper', { fallback: 'Rechtlich bindende Kommunikation' })"
      :error="errors.email"
      required
      @update:model-value="$emit('update', 'email', $event)"
    />

    <!-- Info box -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600 p-4 rounded-r-lg">
      <p class="text-sm text-gray-700 dark:text-gray-300">
        <strong>{{ $t('organisation.form.info_title', { fallback: 'Was kommt als nächstes?' }) }}</strong>
        {{ $t('organisation.form.step_1_info', {
          fallback: 'Nach Angabe dieser Daten werden wir die Adresse Ihres Vereinssitzes und den Vorstandsvorsitzenden erfragen.'
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
