<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <ElectionHeader :isLoggedIn="false" :locale="$page.props.locale" />

        <!-- Registration Section -->
        <section class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 md:px-6 lg:px-8">
                <div class="max-w-2xl mx-auto">
                    <!-- Section Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                            {{ $t('pages.auth.register.title') }}
                        </h1>
                        <p class="text-xl text-gray-600">
                            {{ $t('pages.auth.register.subtitle') }}
                        </p>
                    </div>

                    <!-- Registration Card -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 md:p-12">
                        <jet-validation-errors class="mb-6" />

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- First and Middle Name -->
                            <div>
                                <jet-label for="firstname" :value="$t('pages.auth.register.fields.firstName.label')" class="font-bold text-base md:text-lg" />
                                <jet-input
                                    id="firstname"
                                    type="text"
                                    class="mt-2 block w-full"
                                    v-model="form.firstName"
                                    :placeholder="$t('pages.auth.register.fields.firstName.placeholder')"
                                    required
                                    autofocus
                                    autocomplete="given-name"
                                />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <jet-label for="lastName" :value="$t('pages.auth.register.fields.lastName.label')" class="font-bold text-base md:text-lg" />
                                <jet-input
                                    id="lastName"
                                    type="text"
                                    class="mt-2 block w-full"
                                    v-model="form.lastName"
                                    :placeholder="$t('pages.auth.register.fields.lastName.placeholder')"
                                    required
                                    autocomplete="family-name"
                                />
                            </div>

                            <!-- Region Selection -->
                            <div>
                                <jet-label for="region" :value="$t('pages.auth.register.fields.region.label')" class="font-bold text-base md:text-lg" />
                                <div class="mt-2 relative rounded-lg border border-gray-300 bg-white overflow-hidden">
                                    <select
                                        name="region"
                                        id="region"
                                        v-model="form.region"
                                        class="block w-full px-4 py-3 pr-10 text-gray-900 placeholder-gray-400 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                        <option value="">{{ $t('pages.auth.register.fields.region.placeholder') }}</option>
                                        <option value="Europe">{{ $t('pages.auth.register.fields.region.options.europe') }}</option>
                                        <option value="America">{{ $t('pages.auth.register.fields.region.options.america') }}</option>
                                        <option value="Africa">{{ $t('pages.auth.register.fields.region.options.africa') }}</option>
                                        <option value="Asia Pacific">{{ $t('pages.auth.register.fields.region.options.asia_pacific') }}</option>
                                        <option value="Middle East Asia">{{ $t('pages.auth.register.fields.region.options.middle_east_asia') }}</option>
                                        <option value="Oceania">{{ $t('pages.auth.register.fields.region.options.oceania') }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.894.553l.448.894a1 1 0 001.342 1.342l.894-.448a1 1 0 01.894.553v2a1 1 0 01-1.894.553l-.448-.894a1 1 0 00-1.342-1.342l-.894.448A1 1 0 0110 5v-2zm0 10a1 1 0 01.894.553l.448.894a1 1 0 001.342 1.342l.894-.448a1 1 0 01.894.553v2a1 1 0 01-1.894.553l-.448-.894a1 1 0 00-1.342-1.342l-.894.448A1 1 0 0110 15v-2zm-7-5a1 1 0 01.894.553l.448.894a1 1 0 001.342 1.342l.894-.448a1 1 0 01.894.553h2a1 1 0 01-1.894.553l-.448-.894a1 1 0 00-1.342-1.342l-.894.448A1 1 0 013 10V8zm14 0a1 1 0 01.894.553l.448.894a1 1 0 001.342 1.342l.894-.448a1 1 0 01.894.553v2a1 1 0 01-1.894.553l-.448-.894a1 1 0 00-1.342-1.342l-.894.448a1 1 0 01-.894-.553v-2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <jet-label for="email" :value="$t('pages.auth.register.fields.email.label')" class="font-bold text-base md:text-lg" />
                                <jet-input
                                    id="email"
                                    type="email"
                                    class="mt-2 block w-full"
                                    v-model="form.email"
                                    :placeholder="$t('pages.auth.register.fields.email.placeholder')"
                                    required
                                    autocomplete="email"
                                />
                                <p class="text-xs text-gray-500 mt-2">{{ $t('pages.auth.register.fields.email.help') }}</p>
                            </div>

                            <!-- Password -->
                            <div>
                                <jet-label for="password" :value="$t('pages.auth.register.fields.password.label')" class="font-bold text-base md:text-lg" />
                                <p class="text-xs text-gray-600 mt-1 mb-2">
                                    <span class="text-red-500 font-medium">{{ $t('pages.auth.register.fields.password.help') }}</span>
                                    <br>
                                    {{ $t('pages.auth.register.fields.password.requirements') }}
                                </p>
                                <jet-input
                                    id="password"
                                    type="password"
                                    class="mt-2 block w-full"
                                    v-model="form.password"
                                    :placeholder="$t('pages.auth.register.fields.password.placeholder')"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <jet-label for="password_confirmation" :value="$t('pages.auth.register.fields.password_confirmation.label')" class="font-bold text-base md:text-lg" />
                                <p class="text-xs text-gray-600 mt-1 mb-2">
                                    {{ $t('pages.auth.register.fields.password_confirmation.help') }}
                                </p>
                                <jet-input
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-2 block w-full"
                                    v-model="form.password_confirmation"
                                    :placeholder="$t('pages.auth.register.fields.password_confirmation.placeholder')"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <!-- Terms and Privacy Policy -->
                            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="pt-6 border-t-2 border-gray-300">
                                <div class="bg-linear-to-br from-blue-50 to-indigo-50 rounded-lg p-6 md:p-8 border-2 border-blue-200">
                                    <jet-label for="terms" class="cursor-pointer">
                                        <div class="flex items-start gap-4">
                                            <!-- Large Checkbox -->
                                            <div class="shrink-0">
                                                <jet-checkbox
                                                    name="terms"
                                                    id="terms"
                                                    v-model:checked="form.terms"
                                                    required
                                                    class="w-6 h-6 cursor-pointer"
                                                    style="transform: scale(1.5); transform-origin: left;"
                                                />
                                            </div>

                                            <!-- Agreement Text -->
                                            <div class="grow">
                                                <p class="text-base md:text-lg font-semibold text-gray-900 mb-2">
                                                    {{ $t('pages.auth.register.messages.agreement') }}
                                                </p>
                                                <p class="text-sm md:text-base text-gray-700 leading-relaxed">
                                                    <span class="text-gray-700">{{ $t('pages.auth.register.messages.i_agree') }}</span>
                                                    <a
                                                        target="_blank"
                                                        :href="route('terms.show')"
                                                        class="text-blue-600 hover:text-blue-800 font-bold underline hover:no-underline transition"
                                                    >
                                                        {{ $t('pages.auth.register.links.terms') }}
                                                    </a>
                                                    <span class="text-gray-700">{{ $t('pages.auth.register.messages.and') }}</span>
                                                    <a
                                                        target="_blank"
                                                        :href="route('policy.show')"
                                                        class="text-blue-600 hover:text-blue-800 font-bold underline hover:no-underline transition"
                                                    >
                                                        {{ $t('pages.auth.register.links.privacy') }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </jet-label>

                                    <!-- Visual Indicator -->
                                    <div v-if="form.terms" class="mt-4 flex items-center text-green-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium text-sm">{{ $t('pages.auth.register.messages.agreement_accepted') }}</span>
                                    </div>

                                    <!-- Error Indicator -->
                                    <div v-else-if="form.errors && form.errors.terms" class="mt-4 flex items-center text-red-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-medium text-sm">{{ $t('pages.auth.register.messages.agreement_required') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <inertia-link
                                    :href="route('login')"
                                    class="text-sm text-gray-600 hover:text-gray-900 transition"
                                >
                                    {{ $t('pages.auth.register.links.already_registered') }}
                                    <span class="font-medium text-blue-600">{{ $t('pages.auth.register.links.go_to_login') }}</span>
                                </inertia-link>

                                <jet-button
                                    :class="{ 'opacity-50': form.processing }"
                                    :disabled="form.processing"
                                    class="w-full sm:w-auto"
                                >
                                    <span v-if="!form.processing">{{ $t('pages.auth.register.buttons.register') }}</span>
                                    <span v-else>{{ $t('pages.auth.register.buttons.registering') }}</span>
                                </jet-button>
                            </div>
                        </form>
                    </div>

                    <!-- Trust Badge -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            <svg class="inline-block w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $t('pages.welcome.hero.security_badge_title') }} — {{ $t('pages.welcome.hero.security_badge_subtitle') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <PublicDigitFooter />
    </div>
</template>

<script>
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Components/Jetstream/PublicDigitFooter.vue";
import JetButton from "@/Components/Jetstream/Button.vue";
import JetInput from "@/Components/Jetstream/Input.vue";
import JetCheckbox from "@/Components/Jetstream/Checkbox.vue";
import JetLabel from "@/Components/Jetstream/Label.vue";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";

export default {
    components: {
        ElectionHeader,
        PublicDigitFooter,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
    },

    data() {
        return {
            form: this.$inertia.form({
                firstName: "",
                lastName: "",
                email: "",
                password: "",
                password_confirmation: "",
                terms: false,
                region: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("register"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
            });
        },
    },
};
</script>

<style scoped>
/* Accessibility focus styles */
a:focus,
button:focus {
  outline: 2px solid #2563eb;
  outline-offset: 2px;
}

input:focus,
select:focus {
  outline: none;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* High contrast mode */
@media (prefers-contrast: high) {
  .text-gray-900,
  .text-gray-700 {
    color: #000000 !important;
  }

  .text-gray-600,
  .text-gray-500 {
    color: #000000 !important;
  }

  .bg-white {
    background: #ffffff !important;
    border: 2px solid #000000 !important;
  }

  input,
  select {
    border: 2px solid #000000 !important;
  }

  a {
    text-decoration: underline !important;
  }
}
</style>
