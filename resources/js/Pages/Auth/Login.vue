<template>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Skip to main content link for keyboard users -->
        <a href="#main-content" class="skip-to-main">
            {{ $t('accessibility.skip_to_main') }}
        </a>

        <!-- Header -->
        <ElectionHeader
            :isLoggedIn="false"
            :locale="$page.props.locale"
            :disable-language-selector="true"
            role="banner"
            aria-label="Site header"
        />

        <!-- Main Content -->
        <main 
            id="main-content" 
            class="flex-1 flex items-center justify-center py-8 sm:py-12 md:py-16 px-4 sm:px-6"
            role="main"
            aria-labelledby="page-heading"
        >
            <div class="w-full max-w-md mx-auto">
                <!-- Screen reader only heading -->
                <h1 id="page-heading" class="sr-only">
                    {{ $t('pages.auth.login.heading') }}
                </h1>

                <!-- Visual Heading (for sighted users) -->
                <div class="text-center mb-8 md:mb-10" aria-hidden="true">
                    <p class="text-sm font-semibold text-blue-600 uppercase tracking-wide mb-2">
                        {{ $t('pages.auth.login.welcome') }}
                    </p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">
                        {{ $t('pages.auth.login.heading') }}
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600">
                        {{ $t('pages.auth.login.subtitle') }}
                    </p>
                </div>

                <!-- Login Card -->
                <div 
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8"
                    role="region"
                    aria-label="Login form"
                >
                    <!-- Status Messages - Enhanced for screen readers -->
                    <div 
                        v-if="status" 
                        class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-lg"
                        role="status"
                        aria-live="polite"
                    >
                        <p class="text-sm font-medium text-green-800 flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ status }}</span>
                        </p>
                    </div>

                    <!-- Validation Errors - Enhanced accessibility -->
                    <div 
                        v-if="$page.props.errors && Object.keys($page.props.errors).length > 0"
                        class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-lg"
                        role="alert"
                        aria-live="assertive"
                    >
                        <p class="text-sm font-medium text-red-800 mb-2" id="error-heading">
                            {{ $t('pages.auth.login.errors.heading') }}
                        </p>
                        <jet-validation-errors 
                            class="text-sm"
                            :aria-labelledby="'error-heading'"
                        />
                    </div>

                    <!-- Login Form -->
                    <form @submit.prevent="submit" novalidate>
                        <div class="space-y-6">
                            <!-- Email Field - Enhanced accessibility -->
                            <div>
                                <jet-label 
                                    for="email" 
                                    :value="$t('pages.auth.login.fields.email.label')" 
                                    class="text-base font-semibold text-gray-700"
                                />
                                <div class="mt-2">
                                    <jet-input
                                        id="email"
                                        type="email"
                                        class="w-full text-base p-4 border-2 border-gray-300 rounded-lg focus:border-blue-600 focus:ring-4 focus:ring-blue-200 transition"
                                        v-model="form.email"
                                        :placeholder="$t('pages.auth.login.fields.email.placeholder')"
                                        :aria-required="true"
                                        :aria-invalid="form.errors.email ? 'true' : 'false'"
                                        :aria-describedby="form.errors.email ? 'email-error' : undefined"
                                        required
                                        autofocus
                                        autocomplete="email"
                                        @input="clearError('email')"
                                    />
                                    <p 
                                        v-if="form.errors.email" 
                                        id="email-error" 
                                        class="mt-2 text-sm text-red-600 flex items-center gap-1"
                                        role="alert"
                                    >
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ form.errors.email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Password Field - Enhanced accessibility -->
                            <div>
                                <jet-label 
                                    for="password" 
                                    :value="$t('pages.auth.login.fields.password.label')" 
                                    class="text-base font-semibold text-gray-700"
                                />
                                <div class="mt-2">
                                    <div class="relative">
                                        <jet-input
                                            id="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            class="w-full text-base p-4 border-2 border-gray-300 rounded-lg focus:border-blue-600 focus:ring-4 focus:ring-blue-200 transition pr-12"
                                            v-model="form.password"
                                            :placeholder="$t('pages.auth.login.fields.password.placeholder')"
                                            :aria-required="true"
                                            :aria-invalid="form.errors.password ? 'true' : 'false'"
                                            :aria-describedby="form.errors.password ? 'password-error' : undefined"
                                            required
                                            autocomplete="current-password"
                                            @input="clearError('password')"
                                        />
                                        <button
                                            type="button"
                                            @click="togglePasswordVisibility"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg"
                                            :aria-label="showPassword ? $t('accessibility.hide_password') : $t('accessibility.show_password')"
                                        >
                                            <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p 
                                        v-if="form.errors.password" 
                                        id="password-error" 
                                        class="mt-2 text-sm text-red-600 flex items-center gap-1"
                                        role="alert"
                                    >
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ form.errors.password }}
                                    </p>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password - Mobile friendly layout -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                                <label class="flex items-center min-h-[44px] cursor-pointer">
                                    <jet-checkbox
                                        name="remember"
                                        v-model:checked="form.remember"
                                        class="w-5 h-5"
                                        :aria-label="$t('pages.auth.login.fields.remember.label')"
                                    />
                                    <span class="ml-3 text-base text-gray-700 select-none">
                                        {{ $t('pages.auth.login.fields.remember.label') }}
                                    </span>
                                </label>

                                <InertiaLink
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-base font-medium text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg py-2 px-4 -ml-4 sm:ml-0 text-center sm:text-left"
                                    :aria-label="$t('pages.auth.login.links.forgot_password_aria')"
                                >
                                    {{ $t('pages.auth.login.links.forgot_password') }}
                                </InertiaLink>
                            </div>

                            <!-- Submit Button - Enhanced touch target -->
                            <div class="pt-4">
                                <jet-button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full min-h-[56px] text-base sm:text-lg font-bold bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed transition rounded-xl shadow-lg"
                                    :aria-busy="form.processing ? 'true' : 'false'"
                                >
                                    <span class="flex items-center justify-center gap-3">
                                        <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span v-if="!form.processing">{{ $t('pages.auth.login.buttons.login') }}</span>
                                        <span v-else>{{ $t('pages.auth.login.buttons.logging_in') }}</span>
                                    </span>
                                </jet-button>
                            </div>
                        </div>
                    </form>

                    <!-- Registration Section - Enhanced mobile layout -->
                    <div v-if="canRegister" class="mt-8 pt-8 border-t-2 border-gray-200">
                        <div class="text-center space-y-4">
                            <p class="text-base text-gray-700">
                                {{ $t('pages.auth.login.messages.register_prompt') }}
                            </p>
                            <InertiaLink
                                :href="route('register')"
                                class="inline-flex items-center justify-center w-full sm:w-auto min-h-[56px] px-8 py-4 border-2 border-blue-600 text-base font-bold rounded-xl text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-300 transition shadow-md"
                                :aria-label="$t('pages.auth.login.links.register_aria')"
                            >
                                {{ $t('pages.auth.login.links.register') }}
                            </InertiaLink>
                        </div>
                    </div>
                </div>

                <!-- Trust Badge - Enhanced accessibility -->
                <div class="mt-8 text-center" role="contentinfo" aria-label="Security information">
                    <p class="text-sm text-gray-600 flex items-center justify-center gap-2 flex-wrap">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $t('pages.auth.login.security') }}</span>
                    </p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <PublicDigitFooter role="contentinfo" aria-label="Site footer" />
    </div>
</template>

<script>
import { Link as InertiaLink } from '@inertiajs/vue3';
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";
import JetButton from "@/Components/Jetstream/Button.vue";
import JetInput from "@/Components/Jetstream/Input.vue";
import JetCheckbox from "@/Components/Jetstream/Checkbox.vue";
import JetLabel from "@/Components/Jetstream/Label.vue";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";

export default {
    components: {
        InertiaLink,
        ElectionHeader,
        PublicDigitFooter,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
    },

    props: {
        canResetPassword: {
            type: Boolean,
            default: true
        },
        status: {
            type: String,
            default: null
        },
        canLogin: {
            type: Boolean,
            default: true
        },
        canRegister: {
            type: Boolean,
            default: true
        },
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
                remember: false,
            }),
            showPassword: false,
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => this.form.reset("password"),
                    preserveScroll: true,
                });
        },

        togglePasswordVisibility() {
            this.showPassword = !this.showPassword;
        },

        clearError(field) {
            if (this.form.errors[field]) {
                this.form.errors[field] = null;
            }
        },
    },

    mounted() {
        // Announce page to screen readers
        document.title = this.$t('pages.auth.login.page_title');
    },
};
</script>

<style scoped>
/* Skip to main content link - visible only when focused */
.skip-to-main {
    position: absolute;
    left: -9999px;
    top: 0;
    width: 1px;
    height: 1px;
    overflow: hidden;
    background: white;
    color: #1e40af;
    padding: 1rem;
    text-decoration: none;
    font-weight: bold;
    z-index: 9999;
    border: 2px solid #1e40af;
}

.skip-to-main:focus {
    left: 1rem;
    top: 1rem;
    width: auto;
    height: auto;
    outline: none;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.5);
}

/* Enhanced focus styles for better visibility */
:focus {
    outline: none;
}

:focus-visible {
    outline: 3px solid #2563eb;
    outline-offset: 2px;
    border-radius: 4px;
}

/* Better touch targets for mobile */
button, 
a, 
input[type="checkbox"] + span,
.jet-button {
    min-height: 44px;
    min-width: 44px;
}

/* Improved form field contrast */
input, 
select, 
textarea {
    background-color: #ffffff;
    color: #1f2937;
    font-size: 16px !important; /* Prevent zoom on iOS */
}

/* Reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .text-gray-900,
    .text-gray-800,
    .text-gray-700,
    .text-gray-600,
    .text-gray-500 {
        color: #000000 !important;
    }

    .bg-white,
    .bg-gray-50 {
        background: #ffffff !important;
    }

    input,
    select,
    textarea,
    .border-gray-200,
    .border-gray-300 {
        border: 2px solid #000000 !important;
    }

    a:not([class*="button"]) {
        text-decoration: underline !important;
        color: #0000ee !important;
    }

    a:visited:not([class*="button"]) {
        color: #551a8b !important;
    }

    button,
    .jet-button {
        border: 2px solid #000000 !important;
    }

    /* Ensure all focus states are highly visible */
    :focus-visible {
        outline: 4px solid #000000 !important;
        outline-offset: 4px !important;
        box-shadow: none !important;
    }
}

/* Large text mode support */
@media (min-width: 200%) {
    html {
        font-size: 200%;
    }
    
    .container {
        max-width: 100%;
    }
}

/* Ensure proper spacing on very small screens */
@media (max-width: 360px) {
    .p-6 {
        padding: 1rem;
    }
    
    .text-3xl {
        font-size: 1.75rem;
    }
}
</style>