<template>
    <div v-if="!loggedIn" class="min-h-screen bg-gray-50">
        <!-- Breadcrumb Schema for SEO -->
        <BreadcrumbSchema />

        <!-- Header -->
        <ElectionHeader :isLoggedIn="false" :locale="$page.props.locale" />

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
import Dashboard from "@/Pages/Dashboard";
import ElectionHeader from "@/Components/Header/ElectionHeader.vue";
import PublicDigitFooter from "@/Jetstream/PublicDigitFooter.vue";
import BreadcrumbSchema from "@/Components/BreadcrumbSchema.vue";

// Import Welcome section components
import HeroSection from "@/Components/Welcome/HeroSection.vue";
import NGOFeaturesSection from "@/Components/Welcome/NGOFeaturesSection.vue";
import HowItWorksSection from "@/Components/Welcome/HowItWorksSection.vue";
import SecurityComplianceSection from "@/Components/Welcome/SecurityComplianceSection.vue";
import ValuePropositionSection from "@/Components/Welcome/ValuePropositionSection.vue";
import TestimonialsSection from "@/Components/Welcome/TestimonialsSection.vue";
import CTASection from "@/Components/Welcome/CTASection.vue";
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
        ElectionHeader,
        PublicDigitFooter,
        BreadcrumbSchema,
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
