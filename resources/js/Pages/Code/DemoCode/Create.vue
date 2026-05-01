<template>
    <election-layout>
        <!-- Workflow Step Indicator - Step 1/5 -->
        <div class="w-full bg-gradient-to-br from-gray-50 to-blue-50 py-6 md:py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <WorkflowStepIndicator :currentStep="1" />
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

            <!-- Header — hidden for public demo (code display box is sufficient) -->
            <div v-if="!is_public_demo" class="my-4 mx-auto bg-purple-600 text-white p-4 rounded-lg text-center shadow-lg max-w-md">
                <div class="text-3xl mb-2">🎮</div>
                <p class="text-xl font-bold">{{ $t('pages.code-create.header.title') }} (Demo)</p>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6 max-w-4xl mx-auto">
                <!-- Code Expired Warning -->
                <div v-if="codeExpired" class="p-4 bg-danger-50 rounded-lg border-l-4 border-danger-500 mb-4">
                    <p class="text-danger-900 font-medium flex items-center">
                        <span class="inline-block w-5 h-5 bg-danger-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">⏱</span>
                        {{ $i18n.locale === 'np' ? 'आपको कोड समाप्त भएको छ' : $i18n.locale === 'de' ? 'Ihr Code ist abgelaufen' : 'Your code has expired' }}
                    </p>
                    <p class="text-danger-800 text-sm mt-2">
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

                <!-- Public Demo: Code displayed prominently -->
                <div v-if="is_public_demo && verification_code" class="p-5 bg-green-50 rounded-lg border-l-4 border-green-500 mb-4">
                    <p class="text-green-900 font-semibold flex items-center mb-3">
                        <span class="inline-block w-5 h-5 bg-green-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">✓</span>
                        {{ $t('pages.code-create.public_demo.code_display_label') }}
                    </p>
                    <div class="text-center flex flex-col sm:flex-row items-center justify-center gap-4">
                        <span class="inline-block bg-white border-2 border-green-400 rounded-xl px-8 py-4 text-4xl font-mono font-bold tracking-widest text-green-800 shadow-sm select-all">
                            {{ verification_code }}
                        </span>
                        <button
                            @click="copyCodeToClipboard"
                            :class="codeCopied ? 'bg-green-500 text-white shadow-lg' : 'bg-white text-green-600 hover:bg-green-50 hover:shadow-md'"
                            class="font-bold py-3 px-6 rounded-lg transition-all flex items-center justify-center gap-2 border-2 border-green-400 hover:border-green-500 whitespace-nowrap"
                            title="Copy code to clipboard"
                        >
                            <svg v-if="!codeCopied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="hidden sm:inline">{{ codeCopied ? 'Copied!' : 'Copy' }}</span>
                        </button>
                    </div>
                    <p class="text-green-700 text-sm mt-3 text-center">
                        {{ $t('pages.code-create.public_demo.code_hint') }}
                    </p>
                </div>

                <!-- Email Failed - Showing Fallback Code -->
                <div v-else-if="show_code_fallback && !email_sent && !is_public_demo" class="p-4 bg-amber-50 rounded-lg border-l-4 border-amber-500 mb-4">
                    <p class="text-amber-900 font-medium flex items-center">
                        <span class="inline-block w-5 h-5 bg-amber-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">⚠</span>
                        Email not sent - using fallback code
                    </p>
                    <p class="text-amber-800 text-sm mt-2">
                        The verification code is displayed below. Enter it in the form to continue.
                    </p>
                    <div v-if="verification_code" class="text-center mt-3">
                        <span class="inline-block bg-white border-2 border-amber-400 rounded-xl px-6 py-3 text-3xl font-mono font-bold tracking-widest text-amber-800 shadow-sm select-all">
                            {{ verification_code }}
                        </span>
                    </div>
                </div>

                <!-- Instructions: Public Demo -->
                <div v-if="is_public_demo && !codeExpired" class="p-4 bg-primary-50 rounded-lg border-l-4 border-primary-500">
                    <p class="text-neutral-900 font-medium mb-2 flex items-center">
                        <span class="inline-block w-5 h-5 bg-primary-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">!</span>
                        {{ $t('pages.code-create.public_demo.instructions_title') }}
                    </p>
                    <p class="text-neutral-800 leading-relaxed">
                        {{ $t('pages.code-create.public_demo.instructions_body') }}
                    </p>
                </div>

                <!-- How It Works — BOLD CTA Card -->
                <div v-if="is_public_demo" class="mt-6">
                    <a :href="route('public-demo.guide')" class="block group">
                        <div class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 border-3 border-amber-300 rounded-3xl p-8 md:p-10 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden">
                            <!-- Animated background accent -->
                            <div class="absolute -top-20 -right-20 w-40 h-40 bg-amber-200 opacity-20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-500"></div>
                            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-200 opacity-15 rounded-full blur-2xl"></div>

                            <!-- Content wrapper -->
                            <div class="relative z-10">
                                <!-- Badge -->
                                <div class="inline-block mb-4">
                                    <span class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest shadow-lg">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        {{ $t('pages.code-create.public_demo.how_it_works.badge_text') }}
                                    </span>
                                </div>

                                <!-- Main content -->
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                                    <div class="flex-1">
                                        <h3 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-2 leading-tight">
                                            ❓ {{ $t('pages.code-create.public_demo.how_it_works.title') }}
                                        </h3>
                                        <p class="text-neutral-700 text-lg leading-relaxed mb-4 max-w-lg">
                                            {{ $t('pages.code-create.public_demo.how_it_works.description') }}
                                        </p>

                                        <!-- CTA Button inside card -->
                                        <div class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl transform group-hover:scale-105 transition-all duration-200">
                                            <span class="text-lg">{{ $t('pages.code-create.public_demo.how_it_works.cta_text') }}</span>
                                            <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Animated icon on right -->
                                    <div class="hidden md:flex flex-shrink-0">
                                        <div class="relative w-24 h-24">
                                            <!-- Rotating outer ring -->
                                            <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full opacity-20 animate-spin" style="animation-duration: 3s;"></div>
                                            <!-- Main icon circle -->
                                            <div class="absolute inset-2 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                <svg class="w-12 h-12 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="animation-duration: 2s;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Border glow effect on hover -->
                            <div class="absolute inset-0 rounded-3xl border-3 border-amber-300 opacity-0 group-hover:opacity-100 group-hover:shadow-2xl group-hover:shadow-amber-200 transition-all duration-300" style="box-shadow: 0 0 30px rgba(217, 119, 6, 0.3) inset;"></div>
                        </div>
                    </a>
                </div>

                <!-- Instructions: Normal Demo (email-based) -->
                <div v-else-if="!is_public_demo && !codeExpired" class="p-4 bg-primary-50 rounded-lg border-l-4 border-primary-500">
                    <p class="text-neutral-900 font-medium mb-3 flex items-center">
                        <span class="inline-block w-5 h-5 bg-primary-600 text-white rounded-full text-xs leading-5 mr-2 flex items-center justify-center">!</span>
                        {{ $t('pages.code-create.instructions.nepali_section') }}
                    </p>
                    <p class="text-neutral-800 leading-relaxed mb-1">
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
                <div class="bg-white rounded-lg shadow-lg border border-neutral-200 px-6 py-8 max-w-2xl mx-auto">
                    <!-- Code Input -->
                    <div class="mb-8">
                        <label for="voting_code" class="block mb-6">
                            <div class="flex items-center justify-center mb-2">
                                <span class="text-2xl mr-2">🔑</span>
                                <p class="text-xl font-bold text-neutral-900">{{ $t('pages.code-create.form.code_label') }}</p>
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
                                    'border-danger-300 bg-danger-50': form.errors.voting_code,
                                    'border-green-400 bg-green-50': form.voting_code && form.voting_code.length === 8 && !form.errors.voting_code,
                                    'border-neutral-300': !form.voting_code || form.voting_code.length !== 8 || !form.errors.voting_code
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
                                         'border-neutral-300': !form.voting_code || form.voting_code.length < i
                                     }">
                                    <span v-if="form.voting_code && form.voting_code.length >= i"
                                          class="text-neutral-900">
                                        {{ form.voting_code.charAt(i-1) }}
                                    </span>
                                    <span v-else class="text-neutral-400">_</span>
                                </div>
                            </div>

                            <!-- Status Indicators -->
                            <div class="mt-6 flex items-center justify-between px-2">
                                <div class="text-sm text-neutral-600">
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
                            <div v-if="form.errors.voting_code" class="mt-4 p-3 bg-danger-50 border border-danger-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-danger-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-danger-700">{{ form.errors.voting_code }}</span>
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
                                'bg-neutral-300 text-neutral-500 cursor-not-allowed': form.voting_code.length !== 8 || codeExpired
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
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";
import JetValidationErrors from "@/Components/Jetstream/ValidationErrors.vue";
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
    verification_code: {
        type: String,
        default: null
    },
    is_public_demo: {
        type: Boolean,
        default: false
    },
});

const { t, locale } = useI18n();

const form = useForm({
    voting_code: "",
});

const codeCopied = ref(false);

const copyCodeToClipboard = () => {
    if (!props.verification_code) return;

    navigator.clipboard.writeText(props.verification_code)
        .then(() => {
            codeCopied.value = true;
            setTimeout(() => {
                codeCopied.value = false;
            }, 2000);
        })
        .catch(() => {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = props.verification_code;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            codeCopied.value = true;
            setTimeout(() => {
                codeCopied.value = false;
            }, 2000);
        });
};

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
    let submitUrl;
    if (props.is_public_demo && props.slug) {
        submitUrl = `/public-demo/${props.slug}/code`;
    } else if (props.useSlugPath && props.slug) {
        submitUrl = `/v/${props.slug}/demo-code`;
    } else {
        submitUrl = "/demo/codes";
    }

    form.post(submitUrl);
};

const handleSubmit = () => {
    if (form.voting_code.trim()) {
        submit();
    }
};
</script>

