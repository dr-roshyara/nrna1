<template>
  <fieldset class="space-y-6">
    <legend class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      {{ $t('organization.form.step_3_title', { fallback: 'Vertreter und Bestätigung' }) }}
    </legend>

    <!-- Representative Name -->
    <FormInput
      id="rep-name"
      :label="$t('organization.form.rep_name_label', { fallback: 'Name des Vorstandsvorsitzenden' })"
      :model-value="data.name"
      :placeholder="$t('organization.form.rep_name_placeholder', { fallback: 'Dr. Thomas Schmidt' })"
      :error="errors.name"
      required
      @update:model-value="$emit('update:representative', 'name', $event)"
    />

    <!-- Representative Role/Function -->
    <FormInput
      id="rep-role"
      :label="$t('organization.form.rep_role_label', { fallback: 'Funktion' })"
      :model-value="data.role"
      :placeholder="$t('organization.form.rep_role_placeholder', { fallback: '1. Vorsitzender/in' })"
      :helper="$t('organization.form.rep_role_helper', { fallback: 'z.B. Vorsitzender, Geschäftsführer, etc.' })"
      :error="errors.role"
      required
      @update:model-value="$emit('update:representative', 'role', $event)"
    />

    <!-- Representative Email (hidden if is_self = true) -->
    <FormInput
      v-if="!data.is_self"
      id="rep-email"
      type="email"
      :label="$t('organization.form.rep_email_label', { fallback: 'E-Mail des Vertreters' })"
      :model-value="data.email"
      :placeholder="$t('organization.form.rep_email_placeholder', { fallback: 't.schmidt@tv-muenchen.de' })"
      :helper="$t('organization.form.rep_email_helper', { fallback: 'Falls abweichend von der Organisationsadresse' })"
      :error="errors.email"
      required
      @update:model-value="$emit('update:representative', 'email', $event)"
    />

    <!-- I am the representative checkbox -->
    <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
      <input
        id="is-self-representative"
        type="checkbox"
        :checked="data.is_self"
        @change="(e) => {
          $emit('update:representative', 'is_self', e.target.checked);
          if (e.target.checked) {
            $emit('update:representative', 'email', '');
          }
        }"
        class="w-5 h-5 mt-0.5 text-blue-600 dark:text-blue-500 rounded border-gray-300 dark:border-gray-600
               focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
      />
      <div class="flex-1">
        <label for="is-self-representative" class="block text-sm font-medium text-gray-900 dark:text-white">
          {{ $t('organization.form.is_self_representative', {
            fallback: 'Ich bin der Vertreter bzw. die Vertreterin'
          }) }}
        </label>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
          {{ $t('organization.form.is_self_representative_help', {
            fallback: 'Aktivieren Sie dies, wenn Sie selbst der Vertreter sind. Dann wird keine separate E-Mail versendet.'
          }) }}
        </p>
      </div>
    </div>

    <!-- Acceptance Checkboxes -->
    <fieldset class="space-y-4 border-t-2 border-gray-200 dark:border-gray-700 pt-6">
      <legend class="text-base font-semibold text-gray-900 dark:text-white mb-4">
        {{ $t('organization.form.acceptance_legend', { fallback: 'Bestätigungen' }) }}
      </legend>

      <!-- GDPR Acceptance -->
      <div class="flex items-start gap-3">
        <input
          id="accept-gdpr"
          type="checkbox"
          :checked="acceptance.gdpr"
          :aria-describedby="`accept-gdpr-error ${errors.gdpr ? 'accept-gdpr-error' : ''}`"
          :aria-invalid="!!errors.gdpr"
          @change="$emit('update:acceptance', 'gdpr', $event.target.checked)"
          class="w-5 h-5 mt-1 rounded border-2 border-gray-300 dark:border-gray-600
                 text-blue-600 dark:text-blue-500
                 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                 dark:focus:ring-offset-gray-900
                 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed
                 aria-invalid:border-red-500"
        />
        <div class="flex-1">
          <label for="accept-gdpr" class="text-sm text-gray-700 dark:text-gray-300">
            {{ $t('organization.form.gdpr_acceptance', {
              fallback: 'Ich akzeptiere die DSGVO-konforme Verarbeitung meiner Daten'
            }) }}
          </label>
          <p v-if="errors.gdpr" id="accept-gdpr-error" class="text-xs text-red-600 dark:text-red-400 mt-1">
            ⚠️ {{ errors.gdpr }}
          </p>
        </div>
      </div>

      <!-- Terms Acceptance -->
      <div class="flex items-start gap-3">
        <input
          id="accept-terms"
          type="checkbox"
          :checked="acceptance.terms"
          :aria-describedby="`accept-terms-error ${errors.terms ? 'accept-terms-error' : ''}`"
          :aria-invalid="!!errors.terms"
          @change="$emit('update:acceptance', 'terms', $event.target.checked)"
          class="w-5 h-5 mt-1 rounded border-2 border-gray-300 dark:border-gray-600
                 text-blue-600 dark:text-blue-500
                 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                 dark:focus:ring-offset-gray-900
                 disabled:bg-gray-100 dark:disabled:bg-gray-900 disabled:cursor-not-allowed
                 aria-invalid:border-red-500"
        />
        <div class="flex-1">
          <label for="accept-terms" class="text-sm text-gray-700 dark:text-gray-300">
            {{ $t('organization.form.terms_acceptance', {
              fallback: 'Ich akzeptiere die Nutzungsbedingungen von Public Digit'
            }) }}
            <a href="/terms" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">
              {{ $t('common.read_terms', { fallback: 'Bedingungen lesen' }) }}
            </a>
          </label>
          <p v-if="errors.terms" id="accept-terms-error" class="text-xs text-red-600 dark:text-red-400 mt-1">
            ⚠️ {{ errors.terms }}
          </p>
        </div>
      </div>
    </fieldset>

    <!-- Info box -->
    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-600 p-4 rounded-r-lg">
      <p class="text-sm text-gray-700 dark:text-gray-300">
        <strong>{{ $t('organization.form.final_info_title', { fallback: 'Fast fertig!' }) }}</strong>
        {{ $t('organization.form.step_3_info', {
          fallback: 'Nach der Bestätigung erhalten Sie eine Bestätigungsemail. Sie können dann Mitglieder einladen und Ihre erste Wahl erstellen.'
        }) }}
      </p>
    </div>
  </fieldset>
</template>

<script setup>
import FormInput from './FormInput.vue';

defineProps({
  data: {
    type: Object,
    required: true,
  },
  acceptance: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    default: () => ({}),
  },
});

defineEmits(['update:representative', 'update:acceptance']);
</script>
