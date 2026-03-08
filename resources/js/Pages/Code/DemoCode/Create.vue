<template>
    <election-layout>
        <!-- Workflow Step Indicator - Step 1/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator workflow="VOTING" :currentStep="1" />
            </div>
        </div>

        <div class="mt-4 flex w-full flex-col justify-center">
            <!-- Demo Mode Indicator -->
            <div class="bg-purple-100 border-l-4 border-purple-500 p-4 mb-4 max-w-3xl mx-auto rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <div>
                        <p class="text-purple-900 font-medium">🎮 Demo Election Mode</p>
                        <p class="text-purple-700 text-sm mt-1">Testing the verification workflow - you can test multiple times</p>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="my-4 mx-auto bg-purple-600 text-white p-4 rounded-lg text-center shadow-lg max-w-md">
                <div class="text-3xl mb-2">🎮</div>
                <p class="text-xl font-bold">{{ $t('pages.code-create.header.title') }} (Demo)</p>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 max-w-4xl mx-auto">
                <!-- Code Expired Warning -->
                <div v-if="codeExpired" class="p-4 bg-red-50 rounded-lg border-l-4 border-red-500 mb-4">
                    <p class="text-red-900 font-medium flex items-center">
                        <span class="inline-block w-5 h-5 bg-red-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">⏱</span>
                        {{ $i18n.locale === 'np' ? 'आपको कोड समाप्त भएको छ' : $i18n.locale === 'de' ? 'Ihr Code ist abgelaufen' : 'Your code has expired' }}
                    </p>
                    <p class="text-red-800 text-sm mt-2">
                        {{ $i18n.locale === 'np' ? 'कृपया नई कोड के लिए हमसे संपर्क करें' : $i18n.locale === 'de' ? 'Bitte kontaktieren Sie uns für einen neuen Code' : 'Please contact us for a new code' }}
                    </p>
                </div>

                <!-- Email Status (if sent) -->
                <div v-if="email_sent && has_valid_email" class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500 mb-4">
                    <p class="text-green-900 font-medium flex items-center">
                        <span class="inline-block w-5 h-5 bg-green-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">✓</span>
                        ✅ Verification code sent to your email
                    </p>
                    <p class="text-green-800 text-sm mt-2">
                        Check your email for the 8-character verification code. If you don't see it, please check your spam folder.
                    </p>
                </div>

                <!-- Email Failed - Showing Fallback Code -->
                <div v-else-if="show_code_fallback && !email_sent" class="p-4 bg-amber-50 rounded-lg border-l-4 border-amber-500 mb-4">
                    <p class="text-amber-900 font-medium flex items-center">
                        <span class="inline-block w-5 h-5 bg-amber-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">⚠</span>
                        Email not sent - using fallback code
                    </p>
                    <p class="text-amber-800 text-sm mt-2">
                        The verification code is displayed below. Enter it in the form to continue.
                    </p>
                </div>

                <!-- Instructions -->
                <div v-if="!codeExpired" class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <p class="text-gray-900 font-medium mb-3 flex items-center">
                        <span class="inline-block w-5 h-5 bg-blue-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">!</span>
                        {{ $t('pages.code-create.instructions.nepali_section') }}
                    </p>
                    <p class="text-gray-800 leading-relaxed mb-1">
                        {{ getInstructions() }}
                    </p>
                    <p v-if="$i18n.locale !== 'en'" class="mt-4 text-sm font-semibold text-amber-800 bg-amber-50 p-3 rounded-sm border-l-4 border-amber-400">
                        {{ $t('pages.code-create.instructions.nepali_spam_warning') }}
                    </p>
                </div>
            </div>

            <!-- Validation Errors -->
            <div class="m-auto">
                <jet-validation-errors class="mx-auto mb-4 text-center" />
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="mx-auto mt-6 w-full text-center">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-8 max-w-2xl mx-auto">
                    <!-- Code Input -->
                    <div class="mb-8">
                        <label for="voting_code" class="block mb-6">
                            <div class="flex items-center justify-center mb-2">
                                <span class="text-2xl mr-2">🔑</span>
                                <p class="text-xl font-bold text-gray-900">{{ $t('pages.code-create.form.code_label') }}</p>
                            </div>
                        </label>

                        <div class="relative">
                            <!-- Code Input Field -->
                            <input
                                id="voting_code"
                                type="text"
                                v-model="form.voting_code"
                                class="w-full px-6 py-5 text-3xl font-mono text-center tracking-widest border-3 rounded-2xl focus:ring-4 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 uppercase shadow-md"
                                :class="{
                                    'border-red-300 bg-red-50': form.errors.voting_code,
                                    'border-green-400 bg-green-50': form.voting_code && form.voting_code.length === 8 && !form.errors.voting_code,
                                    'border-gray-300': !form.voting_code || form.voting_code.length !== 8 || !form.errors.voting_code
                                }"
                                :placeholder="$t('pages.code-create.form.code_placeholder')"
                                maxlength="8"
                                autocomplete="off"
                                autofocus
                                @keypress.enter="handleSubmit"
                            />

                            <!-- Character Indicators -->
                            <div class="mt-6 flex justify-center space-x-2">
                                <div v-for="i in 8" :key="i"
                                     class="w-12 h-12 rounded-lg border-2 flex items-center justify-center font-bold text-lg transition-all"
                                     :class="{
                                         'border-purple-500 bg-purple-50': (form.voting_code && form.voting_code.length >= i),
                                         'border-gray-300': !form.voting_code || form.voting_code.length < i
                                     }">
                                    <span v-if="form.voting_code && form.voting_code.length >= i"
                                          class="text-gray-900">
                                        {{ form.voting_code.charAt(i-1) }}
                                    </span>
                                    <span v-else class="text-gray-400">_</span>
                                </div>
                            </div>

                            <!-- Status Indicators -->
                            <div class="mt-6 flex items-center justify-between px-2">
                                <div class="text-sm text-gray-600">
                                    <span v-if="form.voting_code">
                                        {{ form.voting_code.length }}/8 {{ $t('pages.code-create.form.characters_label') }}
                                    </span>
                                    <span v-else>{{ $t('pages.code-create.form.enter_instruction') }}</span>
                                </div>
                                <div v-if="form.voting_code && form.voting_code.length === 8 && !form.errors.voting_code"
                                     class="flex items-center text-green-600 font-semibold">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $t('pages.code-create.form.ready_text') }}
                                </div>
                            </div>

                            <!-- Validation Errors -->
                            <div v-if="form.errors.voting_code" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-700">{{ form.errors.voting_code }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button
                            type="submit"
                            :disabled="!form.voting_code.trim() || form.voting_code.length !== 8 || codeExpired"
                            class="w-full font-bold py-4 px-6 rounded-lg transition-all shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2"
                            :class="{
                                'bg-purple-600 hover:bg-purple-700 text-white cursor-pointer': form.voting_code.length === 8 && !codeExpired,
                                'bg-gray-300 text-gray-500 cursor-not-allowed': form.voting_code.length !== 8 || codeExpired
                            }"
                        >
                            {{ $t('pages.code-create.form.submit_button') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </election-layout>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";
import ElectionLayout from "@/Layouts/ElectionLayout.vue";
import WorkflowStepIndicator from "@/Components/Workflow/WorkflowStepIndicator.vue";

const props = defineProps({
    name: String,
    user_id: String,
    state: String,
    code_duration: Number,
    code_expires_in: Number,
    slug: String,
    useSlugPath: Boolean,
    is_demo: Boolean,
    election_type: String,
    email_sent: {
        type: Boolean,
        default: false
    },
    has_valid_email: {
        type: Boolean,
        default: false
    },
    show_code_fallback: {
        type: Boolean,
        default: false
    },
});

const { t, locale } = useI18n();

const form = useForm({
    voting_code: "",
});

const codeExpired = computed(() => {
    const duration = Number(props.code_duration);
    const expiresIn = Number(props.code_expires_in);
    return duration >= expiresIn;
});

const getInstructions = () => {
    const minutesElapsed = Number(props.code_duration);
    const minutesRemaining = Math.max(0, Number(props.code_expires_in) - minutesElapsed);

    // Format to 1 decimal place for display
    const elapsedFormatted = minutesElapsed.toFixed(1);
    const remainingFormatted = minutesRemaining.toFixed(1);

    if (locale.value === 'np') {
        return `${t('pages.code-create.instructions.nepali_intro')} ${elapsedFormatted} ${t('pages.code-create.instructions.nepali_ago')} ${remainingFormatted} ${t('pages.code-create.instructions.nepali_remaining')}`;
    } else if (locale.value === 'de') {
        return `${t('pages.code-create.instructions.english_intro')} ${elapsedFormatted} ${t('pages.code-create.instructions.english_ago')} ${remainingFormatted} ${t('pages.code-create.instructions.english_remaining')}`;
    } else {
        return `${t('pages.code-create.instructions.english_intro')} ${elapsedFormatted} ${t('pages.code-create.instructions.english_ago')} ${remainingFormatted} ${t('pages.code-create.instructions.english_remaining')}`;
    }
};

const submit = () => {
    console.log(form.voting_code);

    let submitUrl;
    if (props.useSlugPath && props.slug) {
        submitUrl = `/v/${props.slug}/demo-code`;
    } else {
        submitUrl = "/demo/codes";
    }

    console.log('Submitting to DEMO URL:', submitUrl);
    form.post(submitUrl);
};

const handleSubmit = () => {
    if (form.voting_code.trim()) {
        submit();
    }
};
</script>
