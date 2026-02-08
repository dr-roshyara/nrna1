<template>
    <election-layout>
        <!-- Email Verification Content -->
        <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-8 md:py-12">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <!-- Card Container -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <!-- Header Section -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 md:px-8 py-8 md:py-10 text-center">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                            {{ $t('pages.verify-email.title') }}
                        </h1>
                        <p class="text-blue-100 text-sm md:text-base">
                            {{ $t('pages.verify-email.check_email_now') }}
                        </p>
                    </div>

                    <!-- Content Section -->
                    <div class="px-6 md:px-8 py-8 md:py-10">
                        <!-- Email Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="bg-blue-100 p-4 rounded-full">
                                <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Main Message -->
                        <div class="space-y-4 text-gray-700 mb-8">
                            <!-- Thanks for Signup -->
                            <p class="text-base md:text-lg">
                                <span class="font-semibold text-gray-900">
                                    {{ $t('pages.verify-email.thanks_for_signup') }}
                                </span>
                            </p>

                            <!-- Verification Instructions -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 md:p-5">
                                <p class="text-sm md:text-base text-gray-700 leading-relaxed">
                                    {{ $t('pages.verify-email.verify_instruction') }}
                                </p>
                            </div>

                            <!-- Resend Instructions -->
                            <p class="text-sm md:text-base text-gray-600 italic">
                                {{ $t('pages.verify-email.resend_instruction') }}
                            </p>
                        </div>

                        <!-- Verification Link Sent Message -->
                        <div
                            v-if="verificationLinkSent"
                            class="mb-8 p-4 md:p-5 bg-green-50 border-l-4 border-green-500 rounded-lg"
                            role="alert"
                        >
                            <div class="flex items-start">
                                <svg class="w-5 h-5 md:w-6 md:h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <p class="ml-3 text-sm md:text-base font-medium text-green-800">
                                    {{ $t('pages.verify-email.verification_link_sent') }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <form @submit.prevent="submit" class="space-y-3">
                            <jet-button
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                                class="w-full"
                            >
                                <span v-if="!form.processing">
                                    {{ $t('pages.verify-email.resend_button') }}
                                </span>
                                <span v-else class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ $t('pages.verify-email.resend_button') }}
                                </span>
                            </jet-button>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="w-full px-4 py-3 text-sm md:text-base font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors duration-200"
                            >
                                {{ $t('pages.verify-email.logout_button') }}
                            </Link>
                        </form>
                    </div>

                    <!-- Footer Info -->
                    <div class="bg-gray-50 px-6 md:px-8 py-4 border-t border-gray-200">
                        <p class="text-xs md:text-sm text-gray-600 text-center">
                            📧 {{ $t('pages.verify-email.check_email_now') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </election-layout>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import { Link } from "@inertiajs/inertia-vue3";
import ElectionLayout from "@/Layouts/ElectionLayout.vue";

export default {
    components: {
        JetButton,
        Link,
        ElectionLayout,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form(),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("verification.send"));
        },
    },

    computed: {
        verificationLinkSent() {
            return this.status === "verification-link-sent";
        },
    },
};
</script>
