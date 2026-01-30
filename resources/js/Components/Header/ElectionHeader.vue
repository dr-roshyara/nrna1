<template>
  <header class="sticky top-0 z-40 bg-gradient-to-r from-blue-900 to-blue-700 text-white shadow-lg">
    <div class="container mx-auto px-4 md:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-4">

        <!-- Logo and Branding -->
        <div class="flex items-center space-x-3 flex-1">
          <img src="/images/logo-2.png" alt="DemocracyOS" class="w-12 h-12 md:w-14 md:h-14 object-contain" />
          <div class="flex flex-col">
            <h1 class="text-lg md:text-xl font-bold leading-tight">
              {{ $t('platform.name') }}
              <span class="text-sm font-normal text-blue-200">
                {{ $t('platform.tagline') }}
              </span>
            </h1>
          </div>
        </div>

        <!-- Navigation (Optional) -->
        <nav class="hidden md:flex space-x-6">
          <a href="/" class="text-white font-medium hover:text-blue-200 transition-colors">
            Home
          </a>
          <a href="#about" class="text-white font-medium hover:text-blue-200 transition-colors">
            About
          </a>
          <a href="#faq" class="text-white font-medium hover:text-blue-200 transition-colors">
            FAQ
          </a>
        </nav>

        <!-- Language & Auth -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 w-full md:w-auto">

          <!-- Language Selector -->
          <div class="relative">
            <select
              v-model="currentLocale"
              @change="switchLanguage"
              class="appearance-none bg-white/10 text-white border border-white/30 rounded-lg px-4 py-2 pr-10 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all cursor-pointer"
              :aria-label="$t('common.select_language')"
            >
              <option value="de" class="bg-blue-900 text-white">🇩🇪 Deutsch</option>
              <option value="en" class="bg-blue-900 text-white">🇬🇧 English</option>
              <option value="np" class="bg-blue-900 text-white">🇳🇵 नेपाली</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-white">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </div>
          </div>

          <!-- Auth Links -->
          <div class="flex space-x-3">
            <a
              v-if="!isLoggedIn"
              :href="route('login')"
              class="inline-flex items-center px-4 py-2 bg-white text-blue-900 font-semibold rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-colors"
            >
              {{ $t('navigation.login') }}
            </a>
            <a
              v-if="isLoggedIn"
              :href="route('logout')"
              class="inline-flex items-center px-4 py-2 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition-colors"
            >
              {{ $t('navigation.logout') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'ElectionHeader',

  props: {
    isLoggedIn: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      currentLocale: this.$i18n?.locale || 'de',
    };
  },

  methods: {
    switchLanguage() {
      if (this.$i18n) {
        this.$i18n.locale = this.currentLocale;
        localStorage.setItem('preferred_locale', this.currentLocale);
      }
    },
  },

  watch: {
    '$i18n.locale'(newLocale) {
      this.currentLocale = newLocale;
    },
  },
};
</script>

<style scoped>
/* Fix select dropdown color in Firefox */
select option {
  background-color: #1a365d;
  color: white;
}
</style>
