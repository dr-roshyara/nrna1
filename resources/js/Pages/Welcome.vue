<template>
    <div v-if="!loggedIn" class="min-h-screen bg-gray-50">
        <!-- Breadcrumb Schema for SEO -->

        <!-- Header -->
        <PublicDigitHeader />

        <!-- Top-Level Action Grid -->
        <div class="bg-white border-b border-gray-100 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <!-- PRIMARY: Try Demo -->
                    <a :href="route('public-demo.start')"
                       class="group relative flex items-center gap-5 p-6 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl overflow-hidden">
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-400 to-indigo-400 opacity-0 group-hover:opacity-20 transition-opacity"></div>
                        <div class="shrink-0 w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 text-white relative z-10">
                            <div class="font-bold text-xl">{{ $t('pages.welcome.hero.cta_demo') }} →</div>
                            <div class="text-white/80 text-sm mt-0.5">{{ $t('pages.welcome.hero.cta_demo_sub') }}</div>
                        </div>
                        <svg class="w-5 h-5 text-white/70 group-hover:translate-x-1 transition-transform shrink-0 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <!-- SECONDARY: Get Started -->
                    <a href="/register"
                       class="group flex items-center gap-5 p-6 rounded-2xl border-2 border-slate-200 bg-white hover:border-purple-300 hover:bg-purple-50 transition-all shadow-sm hover:shadow-md">
                        <div class="shrink-0 w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                            <svg class="w-7 h-7 text-slate-600 group-hover:text-purple-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-slate-800 text-lg group-hover:text-purple-800 transition-colors">{{ $t('pages.welcome.hero.cta_register') }}</div>
                            <div class="text-slate-500 text-sm mt-0.5">{{ $t('pages.welcome.hero.cta_register_sub') }}</div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:translate-x-1 group-hover:text-purple-500 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <HeroSection :badges="heroBadges" />

        <!-- NGO Features Section -->
        <!-- <NGOFeaturesSection :featureCards="featureCards" :orgTypes="orgTypes" /> -->

        <!-- How It Works Section -->
        <HowItWorksSection :steps="steps" />

        <!-- Security & Compliance Section -->
        <SecurityComplianceSection :cards="securityCards" :certifications="certifications" />

        <!-- Value Proposition Section -->
        <ValuePropositionSection
            :features="valuePropositionFeatures"
            :testimonial="bestTestimonial"
            :orgTypes="valuePropositionOrgTypes"
        />

        <!-- Testimonials Section -->
        <!-- <TestimonialsSection :testimonials="testimonials" /> -->

        <!-- CTA Section -->
        <CTASection :perks="perks" />

        <!-- Footer -->
        <PublicDigitFooter />
    </div>
    <div v-else>
        <Dashboard />
    </div>
</template>

<script>
import Dashboard from "@/pages/Dashboard.vue";
import PublicDigitHeader from "@/components/Jetstream/PublicDigitHeader.vue";
import PublicDigitFooter from "@/components/Jetstream/PublicDigitFooter.vue";

// Import Welcome section components
import HeroSection from "@/components/Welcome/HeroSection.vue";
import NGOFeaturesSection from "@/components/Welcome/NGOFeaturesSection.vue";
import HowItWorksSection from "@/components/Welcome/HowItWorksSection.vue";
import SecurityComplianceSection from "@/components/Welcome/SecurityComplianceSection.vue";
import ValuePropositionSection from "@/components/Welcome/ValuePropositionSection.vue";
import TestimonialsSection from "@/components/Welcome/TestimonialsSection.vue";
import CTASection from "@/components/Welcome/CTASection.vue";
import { useMeta } from "@/composables/useMeta";

// Import Welcome locale files for array data
import welcomeDe from '../locales/pages/Welcome/de.json';
import welcomeEn from '../locales/pages/Welcome/en.json';
import welcomeNp from '../locales/pages/Welcome/np.json';

export default {
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        role: String,
        loggedIn: Boolean,
    },
    components: {
        Dashboard,
        PublicDigitHeader,
        PublicDigitFooter,
        HeroSection,
        NGOFeaturesSection,
        HowItWorksSection,
        SecurityComplianceSection,
        ValuePropositionSection,
        TestimonialsSection,
        CTASection,
    },
    data() {
        return {
            welcomeData: {
                de: welcomeDe,
                en: welcomeEn,
                np: welcomeNp,
            },
        };
    },
    created() {
        /**
         * SEO Meta Tags for Homepage
         *
         * Automatically sets language-aware meta tags based on current locale
         * Reads from 'home' page key in i18n translations:
         * - de.json for German pages
         * - en.json for English pages
         * - np.json for Nepali pages
         */
        useMeta({ pageKey: 'home' });
    },
    computed: {
        currentLocale() {
            return this.$i18n.locale;
        },
        welcome() {
            // Explicitly depend on currentLocale to ensure reactivity
            return this.welcomeData[this.currentLocale] || this.welcomeData.de;
        },
        heroBadges() {
            return this.welcome.hero?.badges || [];
        },
        featureCards() {
            return this.welcome.ngo_features?.cards || [];
        },
        orgTypes() {
            return this.welcome.ngo_features?.org_types || [];
        },
        steps() {
            return this.welcome.how_it_works?.steps || [];
        },
        securityCards() {
            return this.welcome.security?.cards || [];
        },
        certifications() {
            return this.welcome.security?.certifications || [];
        },
        testimonials() {
            return this.welcome.testimonials?.items || [];
        },
        valuePropositionFeatures() {
            return this.welcome.value_proposition?.features || [];
        },
        valuePropositionOrgTypes() {
            return this.welcome.value_proposition?.org_types || [];
        },
        bestTestimonial() {
            return this.welcome.value_proposition?.testimonial || null;
        },
        perks() {
            return this.welcome.cta_section?.perks || [];
        },
    },
};
</script>
