<template>
    <election-layout>
        <!-- Workflow Step Indicator - Step 1/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator workflow="VOTING" :currentStep="1" />
            </div>
        </div>

        <div class="mx-2 mt-4 flex w-full flex-col justify-center p-2">
    <!-- IP Mismatch Error Display -->
    <div v-if="$page.props.errors.ip_mismatch" class="bg-amber-50 border-l-4 border-amber-500 p-6 mb-6 rounded-lg shadow-md max-w-3xl mx-auto" role="alert" aria-live="polite">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-bold text-amber-900 mb-2">{{ $t('pages.code-create.errors.ip_mismatch_title') }}</h3>
                <div class="text-sm text-amber-800 whitespace-pre-line">
                    {{ $page.props.errors.ip_mismatch }}
                </div>
            </div>
        </div>
    </div>

    <!-- Header - Just better styling -->
    <div class="my-4 mx-auto bg-blue-600 text-white p-4 rounded-lg text-center shadow-lg max-w-md">
        <div class="text-3xl mb-2">{{ $t('pages.code-create.header.icon') }}</div>
        <p class="text-xl font-bold">{{ $t('pages.code-create.header.title') }}</p>
    </div>

    <!-- Instructions - Better organized (Language-specific) -->
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

        <!-- Instructions for current language only -->
        <div v-if="!codeExpired" class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
            <p class="text-gray-900 font-medium mb-3 flex items-center">
                <span class="inline-block w-5 h-5 bg-blue-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">!</span>
                {{ $t('pages.code-create.instructions.nepali_section') }}
            </p>
            <p class="text-gray-800 leading-relaxed mb-1">
                {{ getInstructions() }}
            </p>
            <p v-if="$i18n.locale !== 'en'" class="mt-4 text-sm font-semibold text-amber-800 bg-amber-50 p-3 rounded border-l-4 border-amber-400">
                {{ $t('pages.code-create.instructions.nepali_spam_warning') }}
            </p>
        </div>
    </div>

    <!-- Validation Errors -->
    <div class="m-auto">
        <jet-validation-errors class="mx-auto mb-4 text-center" />
    </div>

    <!-- Form - Cleaner styling -->
    <form @submit.prevent="submit" class="mx-auto mt-6 w-full text-center md:w-2/3">
        <div class="mx-2 bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-8">
            <!-- Code Input -->
            <div class="mb-6">
                <label for="voting_code" class="block mb-4">
                    <div class="flex items-center justify-center mb-2">
                        <span class="text-2xl mr-2">🔑</span>
                        <p class="text-xl font-bold text-gray-900">{{ $t('pages.code-create.form.code_label') }}</p>
                    </div>
                </label>
                <input
                    class="w-full md:w-2/3 mx-auto block rounded-lg border-2 border-blue-300 bg-gray-50 p-4 text-xl font-bold text-gray-900 text-center transition-all focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 focus:bg-white focus:border-blue-600"
                    id="voting_code"
                    :placeholder="$t('pages.code-create.form.code_placeholder')"
                    v-model="form.voting_code"
                    @keypress.enter="handleSubmit"
                    autocomplete="off"
                />
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button
                    type="submit"
                    :disabled="!form.voting_code.trim() || codeExpired"
                    class="w-full md:w-2/3 font-bold py-4 px-6 rounded-lg transition-all shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2"
                    :class="{
                        'bg-blue-600 hover:bg-blue-700 text-white cursor-pointer': form.voting_code.trim() && !codeExpired,
                        'bg-gray-300 text-gray-500 cursor-not-allowed': !form.voting_code.trim() || codeExpired
                    }"
                >
                    {{ $t('pages.code-create.form.submit_button') }}
                </button>
            </div>

            <!-- Validation Errors -->
            <div class="mx-auto text-center">
                <jet-validation-errors class="mx-auto mb-4 text-center" />
            </div>
        </div>
    </form>
</div>
    </election-layout>
</template>
<script>
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import ElectionLayout from "@/Layouts/ElectionLayout";
import WorkflowStepIndicator from "@/Components/Workflow/WorkflowStepIndicator";
export default {
    props: {
        name: String,
        user_id: String,
        state: String,
        code_duration: Number,
        code_expires_in: Number,
        slug: String, // Add slug prop for slug-based routing
        useSlugPath: Boolean, // Configuration to enable/disable slug paths
    },
    setup(props) {
        const form = useForm({
            voting_code: "",
        });

        function submit() {
            console.log(form.voting_code);

            // Use slug-based route only if both slug exists AND slug path is enabled
            let submitUrl;
            if (props.useSlugPath && props.slug) {
                submitUrl = `/v/${props.slug}/code`;
            } else {
                submitUrl = "/codes";
            }

            console.log('Submitting to URL:', submitUrl, 'useSlugPath:', props.useSlugPath, 'slug:', props.slug);
            form.post(submitUrl);
        }

        return { form, submit };
    },
    computed: {
        codeExpired() {
            return this.code_duration >= this.code_expires_in;
        }
    },
    methods: {
        getInstructions() {
            const locale = this.$i18n.locale;
            const minutesElapsed = this.code_duration;
            const minutesRemaining = Math.max(0, this.code_expires_in - this.code_duration);

            if (locale === 'np') {
                return `${this.$t('pages.code-create.instructions.nepali_intro')} ${minutesElapsed} ${this.$t('pages.code-create.instructions.nepali_ago')} ${minutesRemaining} ${this.$t('pages.code-create.instructions.nepali_remaining')}`;
            } else if (locale === 'de') {
                return `${this.$t('pages.code-create.instructions.english_intro')} ${minutesElapsed} ${this.$t('pages.code-create.instructions.english_ago')} ${minutesRemaining} ${this.$t('pages.code-create.instructions.english_remaining')}`;
            } else {
                // Default to English
                return `${this.$t('pages.code-create.instructions.english_intro')} ${minutesElapsed} ${this.$t('pages.code-create.instructions.english_ago')} ${minutesRemaining} ${this.$t('pages.code-create.instructions.english_remaining')}`;
            }
        },
        handleSubmit() {
            if (this.form.voting_code.trim()) {
                this.submit();
            }
        }
    },
    components: {
        ElectionLayout,
        JetValidationErrors,
        WorkflowStepIndicator,
    },
};
</script>
