<template>
  <!-- Modal backdrop -->
  <transition name="modal-fade">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center"
      @click="closeModal"
      :aria-hidden="!isOpen"
    >
      <!-- Modal container -->
      <div
        class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto"
        @click.stop
        role="dialog"
        :aria-labelledby="isEducationView ? 'education-title' : 'form-title'"
        aria-modal="true"
      >
        <!-- Education Overlay View -->
        <div v-if="isEducationView" class="p-8 md:p-10">
          <!-- Header with close button -->
          <div class="flex items-center justify-between mb-8">
            <h2 id="education-title" class="text-3xl font-bold text-gray-900 dark:text-white">
              🏢 {{ $t('organization.education.title', { fallback: 'Was ist eine Organisation?' }) }}
            </h2>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
              :aria-label="$t('common.close', { fallback: 'Close' })"
            >
              ✕
            </button>
          </div>

          <!-- Education content -->
          <div class="space-y-6 mb-8">
            <!-- Concept explanation -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600 p-6 rounded-r-lg">
              <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $t('organization.education.description', {
                  fallback: 'Eine Organisation ist Ihr Verein, Verband oder Ihre Genossenschaft im digitalen Wahlsystem.'
                }) }}
              </p>
            </div>

            <!-- Key points -->
            <div class="space-y-3">
              <div class="flex gap-3">
                <span class="text-xl shrink-0">📋</span>
                <p class="text-gray-700 dark:text-gray-300">
                  {{ $t('organization.education.point_1', {
                    fallback: 'Ihre Mitgliederdaten werden hier verwaltet'
                  }) }}
                </p>
              </div>
              <div class="flex gap-3">
                <span class="text-xl shrink-0">🗳️</span>
                <p class="text-gray-700 dark:text-gray-300">
                  {{ $t('organization.education.point_2', {
                    fallback: 'Alle Wahlen finden innerhalb dieser Organisation statt'
                  }) }}
                </p>
              </div>
              <div class="flex gap-3">
                <span class="text-xl shrink-0">🔒</span>
                <p class="text-gray-700 dark:text-gray-300">
                  {{ $t('organization.education.point_3', {
                    fallback: 'DSGVO-konform auf Servern in Deutschland'
                  }) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Expandable sections -->
          <EducationSection
            :title="$t('organization.education.data_privacy_title', {
              fallback: 'Was passiert mit meinen Daten?'
            })"
            :expanded="expandedSections.dataPrivacy"
            @toggle="toggleSection('dataPrivacy')"
          >
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
              <li class="flex gap-2">
                <span class="text-green-600">✓</span>
                {{ $t('organization.education.data_encrypted', {
                  fallback: 'Verschlüsselte Speicherung in Deutschland'
                }) }}
              </li>
              <li class="flex gap-2">
                <span class="text-green-600">✓</span>
                {{ $t('organization.education.data_no_sharing', {
                  fallback: 'Keine Weitergabe an Dritte'
                }) }}
              </li>
              <li class="flex gap-2">
                <span class="text-green-600">✓</span>
                {{ $t('organization.education.data_deletion', {
                  fallback: 'Löschung nach Wahl gemäß DSGVO Art. 17'
                }) }}
              </li>
              <li class="flex gap-2">
                <span class="text-green-600">✓</span>
                {{ $t('organization.education.data_control', {
                  fallback: 'Sie behalten die Datenhoheit'
                }) }}
              </li>
            </ul>
          </EducationSection>

          <EducationSection
            :title="$t('organization.education.requirements_title', {
              fallback: 'Welche Informationen brauchen wir?'
            })"
            :expanded="expandedSections.requirements"
            @toggle="toggleSection('requirements')"
          >
            <p class="text-gray-700 dark:text-gray-300 mb-4">
              {{ $t('organization.education.requirements_intro', {
                fallback: 'Um Ihre Organisation rechtssicher abzubilden, benötigen wir:'
              }) }}
            </p>
            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
              <li class="flex gap-2">
                <span class="text-blue-600 font-bold">✓</span>
                <span>
                  <strong>{{ $t('organization.education.req_name', { fallback: 'Organisationname' }) }}</strong>
                  — {{ $t('organization.education.req_name_desc', { fallback: 'wie im Vereinsregister' }) }}
                </span>
              </li>
              <li class="flex gap-2">
                <span class="text-blue-600 font-bold">✓</span>
                <span>
                  <strong>{{ $t('organization.education.req_email', { fallback: 'E-Mail-Adresse' }) }}</strong>
                  — {{ $t('organization.education.req_email_desc', { fallback: 'für amtliche Zustellung' }) }}
                </span>
              </li>
              <li class="flex gap-2">
                <span class="text-blue-600 font-bold">✓</span>
                <span>
                  <strong>{{ $t('organization.education.req_address', { fallback: 'Anschrift' }) }}</strong>
                  — {{ $t('organization.education.req_address_desc', { fallback: 'des Vereinssitzes' }) }}
                </span>
              </li>
              <li class="flex gap-2">
                <span class="text-blue-600 font-bold">✓</span>
                <span>
                  <strong>{{ $t('organization.education.req_representative', { fallback: 'Vertreter' }) }}</strong>
                  — {{ $t('organization.education.req_representative_desc', { fallback: 'Name und Funktion' }) }}
                </span>
              </li>
            </ul>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-4">
              ⏱️ {{ $t('organization.education.duration', { fallback: 'Dauer: 3-5 Minuten' }) }}
            </p>
          </EducationSection>

          <!-- CTA Button -->
          <button
            @click="nextStep"
            class="w-full mt-8 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg
                   transition-colors duration-300 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                   dark:focus:ring-offset-gray-900"
          >
            {{ $t('organization.education.start_cta', { fallback: 'Organisation jetzt gründen →' }) }}
          </button>

          <!-- Close hint -->
          <p class="text-center text-gray-500 dark:text-gray-400 text-sm mt-4">
            {{ $t('common.press_esc_to_close', { fallback: 'Drücken Sie ESC zum Schließen' }) }}
          </p>
        </div>

        <!-- Form View -->
        <div v-else class="p-8 md:p-10">
          <!-- Header -->
          <div class="flex items-center justify-between mb-8">
            <div>
              <h2 id="form-title" class="text-3xl font-bold text-gray-900 dark:text-white">
                🏢 {{ $t('organization.form.title', { fallback: 'Organisation gründen' }) }}
              </h2>
              <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ $t('organization.form.step', {
                  current: currentStep,
                  total: 3,
                  fallback: `Schritt ${currentStep} von 3`
                }) }}
              </p>
            </div>
            <button
              @click="closeModal"
              class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
              :aria-label="$t('common.close', { fallback: 'Close' })"
            >
              ✕
            </button>
          </div>

          <!-- Progress bar -->
          <div class="mb-8">
            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
              <div
                class="h-full bg-linear-to-r from-blue-600 to-blue-500 transition-all duration-500"
                :style="{ width: progressPercentage + '%' }"
                role="progressbar"
                :aria-valuenow="progressPercentage"
                aria-valuemin="0"
                aria-valuemax="100"
              ></div>
            </div>
          </div>

          <!-- Form steps -->
          <div class="space-y-6 mb-8">
            <!-- Step 1: Basic Info -->
            <OrganizationStepBasicInfo
              v-if="currentStep === 1"
              :data="formData.basic"
              :errors="validationErrors.basic"
              @update="(field, value) => formData.basic[field] = value"
            />

            <!-- Step 2: Address -->
            <OrganizationStepAddress
              v-if="currentStep === 2"
              :data="formData.address"
              :errors="validationErrors.address"
              @update="(field, value) => formData.address[field] = value"
            />

            <!-- Step 3: Representative -->
            <OrganizationStepRepresentative
              v-if="currentStep === 3"
              :data="formData.representative"
              :acceptance="formData.acceptance"
              :errors="validationErrors.representative"
              @update:representative="(field, value) => formData.representative[field] = value"
              @update:acceptance="(field, value) => formData.acceptance[field] = value"
            />
          </div>

          <!-- Error message -->
          <div v-if="submissionError" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-red-700 dark:text-red-400 text-sm">
              ⚠️ {{ submissionError }}
            </p>
          </div>

          <!-- Navigation buttons -->
          <FormNavigation
            :current-step="currentStep"
            :can-go-previous="canGoPrevious"
            :can-go-next="canGoNext"
            :is-submitting="isSubmitting"
            :show-education="showEducation"
            @previous="previousStep"
            @next="nextStep"
            @submit="submitForm"
          />
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed, inject } from 'vue';
import EducationSection from './Steps/EducationSection.vue';
import OrganizationStepBasicInfo from './Steps/OrganizationStepBasicInfo.vue';
import OrganizationStepAddress from './Steps/OrganizationStepAddress.vue';
import OrganizationStepRepresentative from './Steps/OrganizationStepRepresentative.vue';
import FormNavigation from './Steps/FormNavigation.vue';

// INJECT the composable from parent (Welcome.vue)
// This ensures both components use the SAME state instance
const organizationCreation = inject('organizationCreation');

// Use refs and values directly from the composable
const isModalOpen = organizationCreation.isModalOpen;
const currentStep = organizationCreation.currentStep;
const showEducation = organizationCreation.showEducation;
const formData = organizationCreation.formData;
const expandedSections = organizationCreation.expandedSections;
const validationErrors = organizationCreation.validationErrors;
const isSubmitting = organizationCreation.isSubmitting;
const submissionError = organizationCreation.submissionError;
const progressPercentage = organizationCreation.progressPercentage;
const canGoNext = organizationCreation.canGoNext;
const canGoPrevious = organizationCreation.canGoPrevious;

// Methods
const { closeModal, nextStep, previousStep, submitForm, toggleSection } = organizationCreation;

// Computed for template
const isOpen = computed(() => isModalOpen.value);
const isEducationView = computed(() => showEducation.value);

// Handle ESC key to close modal
const handleKeydown = (e) => {
  if (e.key === 'Escape') {
    closeModal();
  }
};

// Register escape key listener
if (typeof window !== 'undefined') {
  window.addEventListener('keydown', handleKeydown);
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

/* Ensure scroll on long content */
@media (max-height: 600px) {
  div[role="dialog"] {
    max-height: calc(100vh - 2rem);
  }
}

/* High contrast mode support */
@media (prefers-contrast: more) {
  div[role="dialog"] {
    border: 2px solid;
    border-color: currentColor;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .modal-fade-enter-active,
  .modal-fade-leave-active,
  div[role="progressbar"] {
    transition: none !important;
  }
}
</style>
