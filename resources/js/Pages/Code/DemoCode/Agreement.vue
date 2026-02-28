<template>
  <election-layout>
    <!-- Workflow Step Indicator - Step 2/5 -->
    <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <WorkflowStepIndicator workflow="VOTING" :currentStep="2" />
      </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-indigo-100 py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Page Title Section -->
        <div class="text-center mb-12">
          <h1 class="text-4xl font-bold text-gray-900 mb-4">
            {{ $t('pages.code-agreement.header.title') }}
          </h1>
          <p class="text-xl text-gray-700 mb-2">
            {{ formatMessage($t('pages.code-agreement.header.subtitle'), { name: user_name }) }}
          </p>
          <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
        </div>

        <!-- Demo Mode Indicator -->
        <div class="max-w-4xl mx-auto bg-purple-50 border-2 border-purple-300 rounded-lg p-4 mb-8">
          <div class="flex items-center">
            <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <p class="text-purple-900 font-semibold">🎮 Demo Election Mode</p>
              <p class="text-purple-700 text-sm mt-1">You're testing the demo voting system. You can vote multiple times.</p>
            </div>
          </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto mb-12">
          <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200">
            <div class="flex items-center">
              <div class="bg-green-100 p-3 rounded-lg mr-4 shrink-0">
                <span class="text-green-600 text-2xl">👤</span>
              </div>
              <div class="text-left">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">{{ $t('pages.code-agreement.header.info_voter') }}</p>
                <p class="font-bold text-gray-900 text-lg">{{ user_name }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-blue-200">
            <div class="flex items-center">
              <div class="bg-blue-100 p-3 rounded-lg mr-4 shrink-0">
                <span class="text-blue-600 text-2xl">⏰</span>
              </div>
              <div class="text-left">
                <p class="text-sm text-gray-600 font-medium uppercase tracking-wide">{{ $t('pages.code-agreement.header.info_time') }}</p>
                <p class="font-bold text-gray-900 text-lg">{{ $t('pages.code-agreement.header.info_time_value', { minutes: votingTime }) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Terms and Conditions Form -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-6 px-8">
            <h2 class="text-2xl font-bold">{{ $t('pages.code-agreement.terms_and_conditions.section_title') }}</h2>
            <p class="text-sm opacity-90 mt-2">{{ $t('pages.code-agreement.terms_and_conditions.section_subtitle') }}</p>
          </div>

          <form @submit.prevent="submitAgreement" class="p-8 space-y-6">
            <!-- Terms Content -->
            <div>
              <p class="text-gray-700 font-medium mb-4">
                {{ $t('pages.code-agreement.terms_and_conditions.intro_text') }}
              </p>

              <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-r-lg">
                <h4 class="font-bold text-blue-900 mb-4 text-lg">{{ $t('pages.code-agreement.terms_and_conditions.key_conditions') }}</h4>
                <ul class="space-y-3">
                  <li class="flex items-start text-gray-800">
                    <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                    <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_1') }}</span>
                  </li>
                  <li class="flex items-start text-gray-800">
                    <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                    <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_2', { minutes: votingTime }) }}</span>
                  </li>
                  <li class="flex items-start text-gray-800">
                    <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                    <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_3') }}</span>
                  </li>
                  <li class="flex items-start text-gray-800">
                    <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                    <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_4') }}</span>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Checkbox Agreement -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-xl p-8 my-8">
              <div class="flex items-start">
                <!-- Large checkbox -->
                <div class="shrink-0 pt-1">
                  <input
                    type="checkbox"
                    id="agreement"
                    v-model="form.agreement"
                    class="w-10 h-10 text-blue-600 border-3 border-gray-400 rounded-lg focus:ring-4 focus:ring-blue-400 focus:ring-offset-2 cursor-pointer transition-all"
                  />
                </div>

                <!-- Label -->
                <div class="ml-5 grow">
                  <label for="agreement" class="cursor-pointer block">
                    <div class="text-xl font-bold text-gray-900 mb-2 leading-tight">
                      {{ $t('pages.code-agreement.agreement_required.checkbox_label') }}
                    </div>
                  </label>

                  <!-- Ready message -->
                  <div v-if="form.agreement" class="mt-4 p-4 bg-green-50 border-2 border-green-300 rounded-lg flex items-center">
                    <span class="text-green-600 text-2xl mr-3">✓</span>
                    <span class="text-green-800 font-semibold text-lg">
                      {{ $t('pages.code-agreement.agreement_required.ready_message') }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Error message -->
              <div v-if="errors.agreement" class="mt-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-sm text-red-700 font-medium" role="alert">
                {{ errors.agreement }}
              </div>
            </div>

            <!-- Submit Button -->
            <div class="space-y-4">
              <button
                type="submit"
                :disabled="!form.agreement || loading"
                class="w-full py-5 px-8 rounded-xl font-bold text-xl transition-all duration-200 shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2"
                :class="{
                  'bg-gradient-to-r from-green-600 to-emerald-700 text-white hover:from-green-700 hover:to-emerald-800 cursor-pointer focus:ring-green-300': form.agreement && !loading,
                  'bg-gray-300 text-gray-500 cursor-not-allowed': !form.agreement || loading
                }"
              >
                <div class="flex items-center justify-center">
                  <span v-if="loading" class="inline-block mr-3">⏳</span>
                  <span v-else class="mr-3 text-2xl">{{ $t('pages.code-agreement.submit_button.icon') }}</span>
                  <span>{{ loading ? 'Processing...' : $t('pages.code-agreement.submit_button.text') }}</span>
                </div>
              </button>

              <!-- Help text -->
              <p class="text-center text-sm text-gray-600 mt-4">
                {{ $t('pages.code-agreement.submit_button.help_text') }}
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </election-layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'

const props = defineProps({
    user_name: String,
    voting_time_minutes: {
        type: Number,
        default: 30
    },
    slug: String,
    useSlugPath: Boolean,
    is_demo: Boolean,
});

const displayTime = ref(30);
const loading = ref(false);
const errors = ref({});

const form = useForm({
    agreement: false,
});

const votingTime = computed(() => {
    const time = props.voting_time_minutes || displayTime.value || 30;
    return parseInt(time) || 30;
});

onMounted(() => {
    if (props.voting_time_minutes && props.voting_time_minutes > 0) {
        displayTime.value = parseInt(props.voting_time_minutes);
    }
});

const formatMessage = (message, params = {}) => {
    let formatted = message;
    Object.entries(params).forEach(([key, value]) => {
        formatted = formatted.replace(`{${key}}`, value);
    });
    return formatted;
};

const submitAgreement = () => {
    errors.value = {};

    if (!form.agreement) {
        errors.value.agreement = 'You must agree to proceed.';
        return;
    }

    loading.value = true;

    const routeName = props.useSlugPath ? 'slug.demo-code.agreement.submit' : 'demo-code.agreement.submit';
    const params = props.useSlugPath ? { vslug: props.slug } : {};

    form.post(route(routeName, params), {
        onError: (formErrors) => {
            errors.value = formErrors;
            loading.value = false;
        },
    });
};
</script>
