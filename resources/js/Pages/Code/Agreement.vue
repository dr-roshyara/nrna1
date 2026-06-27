<template>
    <election-layout>
        <!-- Accessibility Announcement -->
        <div class="sr-only" aria-live="polite" aria-label="Page announcement">
            {{ $t('pages.code-agreement.accessibility.page_loaded') }}
        </div>

        <!-- Workflow Step Indicator - Step 2/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator :currentStep="2" />
            </div>
        </div>

        <div class="min-h-screen bg-gradient-to-br from-blue-100 via-white to-indigo-100 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Page Title Section - Dashboard Style -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-neutral-900 mb-4">
                        {{ $t('pages.code-agreement.header.title') }}
                    </h1>
                    <p class="text-xl text-neutral-700 mb-2">
                        {{ formatMessage($t('pages.code-agreement.header.subtitle'), { name: user_name }) }}
                    </p>
                    <div class="w-24 h-1 bg-primary-600 mx-auto rounded-full"></div>
                </div>

                <!-- Info Cards - Voter & Time Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto mb-12">
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-lg mr-4 shrink-0">
                                <span class="text-green-600 text-2xl">👤</span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-neutral-600 font-medium uppercase tracking-wide">{{ $t('pages.code-agreement.header.info_voter') }}</p>
                                <p class="font-bold text-neutral-900 text-lg">{{ user_name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-primary-200">
                        <div class="flex items-center">
                            <div class="bg-primary-100 p-3 rounded-lg mr-4 shrink-0">
                                <span class="text-primary-600 text-2xl">⏰</span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-neutral-600 font-medium uppercase tracking-wide">{{ $t('pages.code-agreement.header.info_time') }}</p>
                                <p class="font-bold text-neutral-900 text-lg">{{ $t('pages.code-agreement.header.info_time_value', { minutes: votingTime }) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IP Mismatch Error -->
                <div v-if="$page.props.errors.ip_mismatch" class="max-w-4xl mx-auto bg-amber-50 border-l-4 border-amber-500 p-5 mb-6 rounded-lg shadow-md" role="alert" aria-live="polite">
                    <div class="flex">
                        <div class="shrink-0 mt-1">
                            <svg class="h-6 w-6 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-amber-900">{{ $t('pages.code-agreement.errors.ip_mismatch_title') }}</h3>
                            <p class="text-sm text-amber-800 mt-1">{{ $page.props.errors.ip_mismatch }}</p>
                        </div>
                    </div>
                </div>

                <!-- Time Limit Warning -->
                <div class="max-w-4xl mx-auto bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-400 p-5 mb-8 rounded-lg shadow-md" role="region" :aria-label="$t('pages.code-agreement.accessibility.terms_announcement')">
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <div class="bg-amber-100 p-3 rounded-lg">
                                <span class="text-amber-600 text-2xl">⏳</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-amber-900 mb-2 text-lg">{{ $t('pages.code-agreement.voting_time_info.title') }}</h4>
                            <p class="text-amber-800 font-medium mb-1">
                                {{ $t('pages.code-agreement.voting_time_info.message', { minutes: votingTime }) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions Form -->
                <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-6 px-8">
                        <h2 class="text-2xl font-bold">{{ $t('pages.code-agreement.terms_and_conditions.section_title') }}</h2>
                        <p class="text-sm opacity-90 mt-2">{{ $t('pages.code-agreement.terms_and_conditions.section_subtitle') }}</p>
                    </div>

                    <form method="POST" :action="submitUrl" class="p-8 space-y-6" :aria-label="$t('pages.code-agreement.accessibility.form_title')">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" :value="csrfToken" />

                        <!-- Terms Content -->
                        <div>
                            <p class="text-neutral-700 font-medium mb-4">
                                {{ $t('pages.code-agreement.terms_and_conditions.intro_text') }}
                            </p>

                            <div class="bg-primary-50 border-l-4 border-primary-500 p-5 rounded-r-lg">
                                <h4 class="font-bold text-primary-900 mb-4 text-lg">{{ $t('pages.code-agreement.terms_and_conditions.key_conditions') }}</h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start text-neutral-800">
                                        <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                                        <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_1') }}</span>
                                    </li>
                                    <li class="flex items-start text-neutral-800">
                                        <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                                        <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_2', { minutes: votingTime }) }}</span>
                                    </li>
                                    <li class="flex items-start text-neutral-800">
                                        <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                                        <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_3') }}</span>
                                    </li>
                                    <li class="flex items-start text-neutral-800">
                                        <span class="text-green-600 font-bold mr-3 shrink-0 mt-1">✓</span>
                                        <span class="text-base">{{ $t('pages.code-agreement.terms_and_conditions.condition_4') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Large, Accessible Checkbox - IMPORTANT FOR ELDERLY USERS -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-primary-300 rounded-xl p-8 my-8">
                            <div class="flex items-start">
                                <!-- Large checkbox - Much bigger for accessibility -->
                                <div class="shrink-0 pt-1">
                                    <input
                                        type="checkbox"
                                        id="agreement"
                                        name="agreement"
                                        v-model="form.agreement"
                                        value="on"
                                        class="w-10 h-10 text-primary-600 border-3 border-neutral-400 rounded-lg focus:ring-4 focus:ring-blue-400 focus:ring-offset-2 cursor-pointer transition-all"
                                        :aria-label="$t('pages.code-agreement.agreement_required.aria_label')"
                                        @change="announceCheckboxStatus"
                                    />
                                </div>

                                <!-- Label with bilingual text -->
                                <div class="ml-5 grow">
                                    <label for="agreement" class="cursor-pointer block">
                                        <div class="text-xl font-bold text-neutral-900 mb-2 leading-tight">
                                            {{ $t('pages.code-agreement.agreement_required.checkbox_label') }}
                                        </div>
                                        <div class="text-lg text-neutral-700 leading-relaxed">
                                            {{ getNepaliCheckboxLabel() }}
                                        </div>
                                    </label>

                                    <!-- Real-time feedback when checked -->
                                    <div v-if="form.agreement" class="mt-4 p-4 bg-green-50 border-2 border-green-300 rounded-lg flex items-center">
                                        <span class="text-green-600 text-2xl mr-3">✓</span>
                                        <span class="text-green-800 font-semibold text-lg">
                                            {{ $t('pages.code-agreement.agreement_required.ready_message') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Error message if not agreed -->
                            <div v-if="$page.props.errors.agreement" class="mt-4 p-4 bg-danger-50 border-l-4 border-danger-500 rounded-sm text-danger-700 font-medium" role="alert">
                                {{ $page.props.errors.agreement }}
                            </div>
                        </div>

                        <!-- Submit Button - Professional and Accessible -->
                        <div class="space-y-4">
                            <button
                                type="submit"
                                :disabled="!form.agreement"
                                class="w-full py-5 px-8 rounded-xl font-bold text-xl transition-all duration-200 shadow-lg focus:outline-none focus:ring-4 focus:ring-offset-2"
                                :class="{
                                    'bg-gradient-to-r from-green-600 to-emerald-700 text-white hover:from-green-700 hover:to-emerald-800 cursor-pointer focus:ring-green-300': form.agreement,
                                    'bg-neutral-300 text-neutral-500 cursor-not-allowed': !form.agreement
                                }"
                                :aria-label="form.agreement ? $t('pages.code-agreement.submit_button.aria_label_enabled') : $t('pages.code-agreement.submit_button.aria_label_disabled')"
                            >
                                <div class="flex items-center justify-center">
                                    <span class="mr-3 text-2xl">{{ $t('pages.code-agreement.submit_button.icon') }}</span>
                                    <div class="text-left">
                                        <div class="text-xl">{{ $t('pages.code-agreement.submit_button.text') }}</div>
                                        <div class="text-sm opacity-90">{{ getSubmitButtonNepali() }}</div>
                                    </div>
                                </div>
                            </button>

                            <!-- Help text -->
                            <p class="text-center text-sm text-neutral-600 mt-4">
                                {{ $t('pages.code-agreement.submit_button.help_text') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </election-layout>
</template>

<script>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import WorkflowStepIndicator from '@/Components/Workflow/WorkflowStepIndicator.vue'

export default {
    components: {
        ElectionLayout,
        WorkflowStepIndicator,
    },

    props: {
        user_name: String,
        voting_time_minutes: {
            type: Number,
            default: 30  // Default 30 minutes if not provided
        },
        slug: String,
        useSlugPath: Boolean,
    },

    data() {
        return {
            displayTime: 30  // Will be updated from props in created()
        }
    },

    computed: {
        agreementItems() {
            const items = this.$t('pages.code-agreement.terms_and_conditions.items');
            return Array.isArray(items) ? items : [];
        },

        // Computed property ensures voting time is always a valid number
        votingTime() {
            const time = this.voting_time_minutes || this.displayTime || 30;
            return parseInt(time) || 30;
        },

        // Dynamic submit URL using route() helper with slug parameter
        submitUrl() {
            return this.route('slug.code.agreement.submit', { vslug: this.slug });
        }
    },

    created() {
        // Set the display time from props
        if (this.voting_time_minutes && this.voting_time_minutes > 0) {
            this.displayTime = parseInt(this.voting_time_minutes);
        }
    },

    setup(props) {
        const form = useForm({
            agreement: false,
        })

        // Get CSRF token from Inertia props (replaces meta tag approach)
        const csrfToken = usePage().props.csrf_token || ''

        // Helper function to format message with placeholders
        const formatMessage = (message, params = {}) => {
            let formatted = message;
            Object.entries(params).forEach(([key, value]) => {
                formatted = formatted.replace(`{${key}}`, value);
            });
            return formatted;
        };

        return {
            form,
            csrfToken,
            formatMessage,
        }
    },

    methods: {
        getNepaliCheckboxLabel() {
            // Get the current locale
            const locale = this.$i18n.locale;
            // Only show bilingual label if in Nepali locale, otherwise show English only
            if (locale === 'np') {
                return this.$t('pages.code-agreement.agreement_required.checkbox_label');
            }
            return '';
        },

        getSubmitButtonNepali() {
            const locale = this.$i18n.locale;
            return locale === 'np' ? this.$t('pages.code-agreement.submit_button.text') : '';
        },

        announceCheckboxStatus() {
            if (this.form.agreement) {
                this.$nextTick(() => {
                    const announcement = document.createElement('div');
                    announcement.setAttribute('role', 'status');
                    announcement.setAttribute('aria-live', 'polite');
                    announcement.className = 'sr-only';
                    announcement.textContent = this.$t('pages.code-agreement.accessibility.checkbox_checked');
                    document.body.appendChild(announcement);
                    setTimeout(() => {
                        if (document.body.contains(announcement)) {
                            document.body.removeChild(announcement);
                        }
                    }, 2000);
                });
            }
        },

        // Wrapper for Ziggy route() helper
        route(name, params) {
            return window.route ? window.route(name, params) : '#';
        }
    }
}
</script>

