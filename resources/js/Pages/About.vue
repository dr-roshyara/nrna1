<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb Schema for SEO -->

    <!-- Header -->
    <ElectionHeader :isLoggedIn="false" :locale="$page.props.locale" />

    <!-- Hero Section -->
    <section class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center">
          <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
            {{ $t('about.title') }}
          </h1>
          <p class="text-xl text-gray-600">
            {{ $t('about.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-16 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
          <!-- Mission -->
          <div class="bg-white p-8 rounded-lg shadow-xs border border-gray-200">
            <div class="text-3xl mb-4">🎯</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
              {{ $t('about.mission_title') }}
            </h2>
            <p class="text-gray-600 leading-relaxed">
              {{ $t('about.mission_text') }}
            </p>
          </div>

          <!-- Vision -->
          <div class="bg-white p-8 rounded-lg shadow-xs border border-gray-200">
            <div class="text-3xl mb-4">🚀</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
              {{ $t('about.vision_title') }}
            </h2>
            <p class="text-gray-600 leading-relaxed">
              {{ $t('about.vision_text') }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Why Public Digit Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
          {{ $t('about.why_public_digit_title') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(feature, index) in whyPublicDigitFeatures" :key="index" class="bg-gray-50 p-6 rounded-lg border border-gray-200 hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-3">{{ feature.title }}</h3>
            <p class="text-gray-600">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">
          {{ $t('about.how_it_works_title') }}
        </h2>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-8 rounded-sm">
          <p class="text-gray-700 leading-relaxed text-lg">
            {{ $t('about.how_it_works_text') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Security Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12">
          {{ $t('about.security_title') }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
          <div v-for="(feature, index) in securityFeaturesList" :key="index" class="flex items-start">
            <svg class="h-6 w-6 text-green-500 mt-1 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="text-gray-700">{{ feature }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Supported Organizations Section -->
    <section class="py-16 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-12">
          {{ $t('about.supported_organizations_title') }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="(org, index) in supportedOrgsList" :key="index" class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200 text-center">
            <p class="text-gray-800 font-medium">{{ org }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Technology Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">
          {{ $t('about.technology_title') }}
        </h2>
        <div class="bg-gray-50 p-8 rounded-lg border border-gray-200">
          <p class="text-gray-700 leading-relaxed text-lg">
            {{ $t('about.technology_text') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="py-16 sm:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">
          {{ $t('about.team_title') }}
        </h2>
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-lg border border-blue-200">
          <p class="text-gray-700 leading-relaxed text-lg">
            {{ $t('about.team_text') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            {{ $t('about.contact_title') }}
          </h2>
          <p class="text-lg text-gray-600 mb-8">
            {{ $t('about.contact_text') }}
          </p>
          <a :href="`mailto:${supportEmail}`" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            {{ supportEmail }}
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script>
import ElectionHeader from '@/Components/Header/ElectionHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
import { useMeta } from '@/composables/useMeta'

// Import locale files for About page
import aboutDe from '@/locales/pages/about/de.json'
import aboutEn from '@/locales/pages/about/en.json'
import aboutNp from '@/locales/pages/about/np.json'

export default {
  name: 'About',
  components: {
    ElectionHeader,
    PublicDigitFooter,
  },

  data() {
    return {
      aboutData: {
        de: aboutDe,
        en: aboutEn,
        np: aboutNp,
      },
    }
  },

  computed: {
    /**
     * Get current locale from vue-i18n
     */
    currentLocale() {
      return this.$i18n.locale
    },

    /**
     * Get about data for current locale
     * Falls back to English if locale not found
     */
    aboutContent() {
      return this.aboutData[this.currentLocale]?.about || this.aboutData.en?.about || {}
    },

    /**
     * Extract "Why Public Digit?" features array
     */
    whyPublicDigitFeatures() {
      return this.aboutContent.why_public_digit || []
    },

    /**
     * Extract security features array
     */
    securityFeaturesList() {
      return this.aboutContent.security_features || []
    },

    /**
     * Extract supported organizations array
     */
    supportedOrgsList() {
      return this.aboutContent.supported_organizations || []
    },

    /**
     * Get decoded support email address
     */
    supportEmail() {
      const email = this.$t('support.email_address')
      return email.replace(/&#64;/g, '@')
    },
  },

  created() {
    /**
     * SEO Meta Tags for About Page
     * Sets meta tags dynamically based on locale
     */
    useMeta({ pageKey: 'about' })
  },
}
</script>
