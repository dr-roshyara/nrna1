<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header with Language Selector -->
        <ElectionHeader />

        <!-- Main Content -->
        <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <jet-authentication-card>
                <template #logo>
                    <jet-authentication-card-logo />
                </template>

                <!-- Email Verification Content -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $t('pages.verify-email.title') }}
                    </h2>

                    <div class="space-y-4 text-sm text-gray-600">
                        <!-- Check Email Now -->
                        <p>
                            <span class="font-semibold text-gray-900">
                                {{ $t('pages.verify-email.check_email_now') }}
                            </span>
                        </p>

                        <!-- Thanks for Signup -->
                        <p>
                            {{ $t('pages.verify-email.thanks_for_signup') }}
                        </p>

                        <!-- Verification Instructions -->
                        <p>
                            {{ $t('pages.verify-email.verify_instruction') }}
                            {{ $t('pages.verify-email.resend_instruction') }}
                        </p>
                    </div>
                </div>

                <!-- Verification Link Sent Message -->
                <div
                    v-if="verificationLinkSent"
                    class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg"
                >
                    <p class="text-sm font-medium text-green-800">
                        {{ $t('pages.verify-email.verification_link_sent') }}
                    </p>
                </div>

                <!-- Actions -->
                <form @submit.prevent="submit">
                    <div class="mt-6 flex items-center justify-between gap-4">
                        <jet-button
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            class="flex-1"
                        >
                            {{ $t('pages.verify-email.resend_button') }}
                        </jet-button>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                        >
                            {{ $t('pages.verify-email.logout_button') }}
                        </Link>
                    </div>
                </form>
            </jet-authentication-card>
        </div>
    </div>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import { Link } from "@inertiajs/inertia-vue3";
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        Link,
        ElectionHeader,
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
