<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb Schema for SEO -->
    <BreadcrumbSchema />

    <!-- Header -->
    <ElectionHeader :isLoggedIn="false" :locale="$page.props.locale" />

    <!-- Hero Section -->
    <section class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center">
          <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
            {{ $t('faq.title') }}
          </h1>
          <p class="text-xl text-gray-600">
            {{ $t('faq.subtitle') }}
          </p>
        </div>
      </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="$t('common.search')"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
      </div>
    </section>

    <!-- Category Filter -->
    <section class="py-8 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-2 justify-center">
          <button
            @click="selectedCategory = null"
            :class="[
              'px-4 py-2 rounded-full font-semibold transition',
              selectedCategory === null
                ? 'bg-blue-600 text-white'
                : 'bg-white text-gray-700 border border-gray-300 hover:border-blue-500'
            ]"
          >
            All Categories
          </button>
          <button
            v-for="category in uniqueCategories"
            :key="category"
            @click="selectedCategory = category"
            :class="[
              'px-4 py-2 rounded-full font-semibold transition',
              selectedCategory === category
                ? 'bg-blue-600 text-white'
                : 'bg-white text-gray-700 border border-gray-300 hover:border-blue-500'
            ]"
          >
            {{ category }}
          </button>
        </div>
      </div>
    </section>

    <!-- FAQ Content Section -->
    <section class="py-16 sm:py-24">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- No Results Message -->
        <div
          v-if="filteredQuestions.length === 0"
          class="text-center py-12"
        >
          <p class="text-gray-600 text-lg">
            No questions found matching your search or filter.
          </p>
        </div>

        <!-- FAQ Accordion -->
        <div v-else class="space-y-4">
          <div
            v-for="(item, index) in filteredQuestions"
            :key="item.id"
            class="border border-gray-200 rounded-lg overflow-hidden bg-white hover:shadow-md transition"
          >
            <!-- Question Button -->
            <button
              @click="toggleItem(item.id)"
              class="w-full px-6 py-4 flex items-center justify-between bg-white hover:bg-gray-50 transition text-left"
            >
              <div class="flex items-start gap-4">
                <span class="text-blue-600 font-bold text-lg flex-shrink-0">
                  Q{{ index + 1 }}
                </span>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">
                    {{ item.question }}
                  </h3>
                  <span class="text-sm text-gray-500 mt-1 inline-block">
                    {{ item.category }}
                  </span>
                </div>
              </div>
              <svg
                :class="[
                  'h-6 w-6 text-gray-500 flex-shrink-0 transition transform',
                  expandedItems.includes(item.id) ? 'rotate-180' : ''
                ]"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 14l-7 7m0 0l-7-7m7 7V3"
                />
              </svg>
            </button>

            <!-- Answer Content -->
            <div
              v-show="expandedItems.includes(item.id)"
              class="px-6 py-4 bg-gray-50 border-t border-gray-200"
            >
              <p class="text-gray-700 leading-relaxed">
                {{ item.answer }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-24 bg-blue-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
          {{ $t('cta.schedule_demo') }}
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
          Still have questions? Our team is ready to help you get started with secure digital voting.
        </p>
        <a
          href="mailto:support@publicdigit.com"
          class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition"
        >
          {{ $t('support.email_address') }}
        </a>
      </div>
    </section>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script>
import ElectionHeader from '@/Components/Header/ElectionHeader.vue'
import PublicDigitFooter from '@/Jetstream/PublicDigitFooter.vue'
import BreadcrumbSchema from '@/Components/BreadcrumbSchema.vue'
import { useMeta } from '@/composables/useMeta'

// Import locale files for FAQ data
import faqDe from '@/locales/de.json'
import faqEn from '@/locales/en.json'
import faqNp from '@/locales/np.json'

export default {
  name: 'FAQ',
  components: {
    ElectionHeader,
    PublicDigitFooter,
    BreadcrumbSchema,
  },

  data() {
    return {
      faqData: {
        de: faqDe,
        en: faqEn,
        np: faqNp,
      },
      expandedItems: [],
      searchQuery: '',
      selectedCategory: null,
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
     * Get FAQ questions for current locale
     */
    faqQuestions() {
      return this.faqData[this.currentLocale]?.faq?.questions || []
    },

    /**
     * Get unique categories from FAQ questions
     */
    uniqueCategories() {
      const categories = new Set()
      this.faqQuestions.forEach(q => {
        if (q.category) {
          categories.add(q.category)
        }
      })
      return Array.from(categories).sort()
    },

    /**
     * Filter questions by search query and category
     */
    filteredQuestions() {
      let filtered = this.faqQuestions

      // Filter by category
      if (this.selectedCategory) {
        filtered = filtered.filter(q => q.category === this.selectedCategory)
      }

      // Filter by search query
      if (this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase()
        filtered = filtered.filter(q =>
          q.question.toLowerCase().includes(query) ||
          q.answer.toLowerCase().includes(query)
        )
      }

      return filtered
    },
  },

  methods: {
    /**
     * Toggle FAQ item expansion
     */
    toggleItem(itemId) {
      const index = this.expandedItems.indexOf(itemId)
      if (index > -1) {
        this.expandedItems.splice(index, 1)
      } else {
        this.expandedItems.push(itemId)
      }
    },
  },

  created() {
    /**
     * SEO Meta Tags for FAQ Page
     * Sets meta tags dynamically based on locale
     */
    useMeta({ pageKey: 'faq' })
  },
}
</script>

<style scoped>
/* Optional: Add smooth transitions for accordion */
.space-y-4 > div {
  transition: all 0.2s ease-in-out;
}
</style>
