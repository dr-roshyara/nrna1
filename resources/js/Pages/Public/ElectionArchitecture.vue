<template>
  <Head>
    <title>{{ t.seo.title }}</title>
    <meta name="description" :content="t.seo.description" />
    <meta name="keywords" :content="t.seo.keywords" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta property="og:title" :content="t.seo.og_title" />
    <meta property="og:description" :content="t.seo.og_description" />
    <meta property="og:image" content="/storage/architecture/architecture-state-machine.png" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="canonical" href="https://publicdigit.com/election-architecture" />
    <link rel="preload" :href="images.stateMachine" as="image" />
  </Head>

  <PublicDigitLayout>
    <div class="bg-white">
      <!-- Language Switcher -->
      <div class="fixed top-4 right-4 z-50 flex gap-2 bg-white rounded-full shadow-lg p-1">
        <button
          v-for="lang in ['en', 'de', 'np']"
          :key="lang"
          @click="setLocale(lang)"
          :class="[
            'px-4 py-2 rounded-full transition-all font-medium text-sm',
            locale === lang
              ? 'bg-blue-600 text-white shadow-md'
              : 'text-gray-600 hover:text-gray-900'
          ]"
        >
          {{ lang.toUpperCase() }}
        </button>
      </div>

      <!-- Hero Section -->
      <section class="relative bg-gradient-to-br from-blue-200 via-blue-300 to-blue-100 text-gray-900 py-20 px-4 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
          <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-400 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto text-center">
          <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight text-gray-900">
            {{ t.hero.title }}
          </h1>
          <p class="text-xl md:text-2xl text-gray-700 mb-12 max-w-2xl mx-auto">
            {{ t.hero.subtitle }}
          </p>
          <div class="flex flex-wrap justify-center gap-4">
            <div class="bg-white/60 backdrop-blur-sm border border-blue-400/30 rounded-lg px-6 py-3 text-sm font-medium text-gray-900">
              ✓ {{ t.hero.trust_badge_1 }}
            </div>
            <div class="bg-white/60 backdrop-blur-sm border border-blue-400/30 rounded-lg px-6 py-3 text-sm font-medium text-gray-900">
              ✓ {{ t.hero.trust_badge_2 }}
            </div>
            <div class="bg-white/60 backdrop-blur-sm border border-blue-400/30 rounded-lg px-6 py-3 text-sm font-medium text-gray-900">
              ✓ {{ t.hero.trust_badge_3 }}
            </div>
          </div>
        </div>
      </section>

      <!-- Breadcrumbs -->
      <nav class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-600">
        <a href="/" class="hover:text-gray-900">{{ t.breadcrumbs.home }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">{{ t.breadcrumbs.election_architecture }}</span>
      </nav>

      <!-- 5-Phase Lifecycle Section -->
      <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ t.phases.title }}</h2>
          <p class="text-xl text-gray-600">{{ t.phases.subtitle }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div
            v-for="(phase, idx) in phases"
            :key="phase.key"
            class="bg-gradient-to-br rounded-lg p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
            :class="{
              'from-blue-500 to-blue-600': phase.color === 'blue',
              'from-green-500 to-green-600': phase.color === 'green',
              'from-purple-500 to-purple-600': phase.color === 'purple',
              'from-orange-500 to-orange-600': phase.color === 'orange',
              'from-emerald-500 to-emerald-600': phase.color === 'emerald',
            }"
          >
            <div class="text-4xl mb-4">{{ phase.icon }}</div>
            <h3 class="text-lg font-bold mb-2">{{ idx + 1 }}. {{ t.phases[phase.key].title }}</h3>
            <p class="text-sm text-white/90">{{ t.phases[phase.key].description }}</p>
          </div>
        </div>
      </section>

      <!-- Architecture Images Section -->
      <section class="max-w-6xl mx-auto px-4 py-16 border-t border-gray-200">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Visual Architecture</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
          <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md">
            <img
              :src="images.stateMachine"
              :alt="t.images.state_machine_alt"
              loading="lazy"
              class="w-full h-auto"
              width="600"
              height="400"
            />
          </div>
          <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md">
            <img
              :src="images.sequenceDiagram"
              :alt="t.images.sequence_diagram_alt"
              loading="lazy"
              class="w-full h-auto"
              width="600"
              height="400"
            />
          </div>
        </div>
      </section>

      <!-- Mermaid Diagram Section -->
      <section class="bg-gray-50 py-16 border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
          <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">State Machine Flow</h2>
          <div class="bg-white rounded-lg p-8 shadow-md overflow-x-auto">
            <pre class="mermaid">
stateDiagram-v2
    [*] --> Administration: Election Created
    Administration --> Nomination: Complete Setup
    Nomination --> Voting: Complete Nominations
    Voting --> Counting: Voting Ends
    Counting --> Results: Publish Results
    Results --> [*]: Archived
            </pre>
          </div>
        </div>
      </section>

      <!-- Security Features Grid -->
      <section class="max-w-6xl mx-auto px-4 py-16 border-t border-gray-200">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Security Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(feature, idx) in t.security_features" :key="idx" class="bg-white rounded-lg p-8 border border-gray-200 hover:border-blue-500 transition-colors">
            <div class="text-4xl mb-4">{{ feature.icon }}</div>
            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ feature.title }}</h3>
            <p class="text-gray-600">{{ feature.description }}</p>
          </div>
        </div>
      </section>

      <!-- Benefits Section -->
      <section class="bg-blue-50 py-16 border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Admins Benefits -->
            <div>
              <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ t.benefits_admin.title }}</h3>
              <ul class="space-y-3">
                <li v-for="(item, idx) in t.benefits_admin.items" :key="idx" class="flex items-start gap-3 text-gray-700">
                  <span class="text-blue-600 font-bold flex-shrink-0">✓</span>
                  <span>{{ item }}</span>
                </li>
              </ul>
            </div>

            <!-- Voter Benefits -->
            <div>
              <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ t.benefits_voter.title }}</h3>
              <ul class="space-y-3">
                <li v-for="(item, idx) in t.benefits_voter.items" :key="idx" class="flex items-start gap-3 text-gray-700">
                  <span class="text-blue-600 font-bold flex-shrink-0">✓</span>
                  <span>{{ item }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section class="max-w-4xl mx-auto px-4 py-16 border-t border-gray-200">
        <h2 class="text-3xl font-bold text-gray-900 mb-12 text-center">Frequently Asked Questions</h2>
        <div class="space-y-4">
          <div v-for="(item, idx) in t.faq" :key="idx" class="border border-gray-200 rounded-lg overflow-hidden hover:border-blue-500 transition-colors">
            <button
              @click="toggleFaq(idx)"
              class="w-full flex justify-between items-center p-6 bg-white hover:bg-gray-50 transition-colors"
            >
              <span class="text-lg font-semibold text-gray-900 text-left">{{ item.question }}</span>
              <span class="text-blue-600 font-bold text-xl flex-shrink-0 ml-4">
                {{ openFaq === idx ? '−' : '+' }}
              </span>
            </button>
            <transition name="expand">
              <div v-show="openFaq === idx" class="border-t border-gray-200 bg-gray-50 p-6">
                <p class="text-gray-700">{{ item.answer }}</p>
              </div>
            </transition>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-20 mt-16 border-t border-gray-200">
        <div class="max-w-4xl mx-auto px-4 text-center">
          <h2 class="text-4xl font-bold mb-4">{{ t.cta.heading }}</h2>
          <p class="text-xl text-blue-100 mb-8">{{ t.cta.subheading }}</p>
          <div class="flex flex-wrap justify-center gap-4">
            <a
              href="#"
              @click.prevent="router.get(route('organisations.elections.create'))"
              class="bg-white text-blue-600 font-bold px-8 py-3 rounded-lg hover:bg-blue-50 transition-colors shadow-lg cursor-pointer"
            >
              {{ t.cta.start_election }}
            </a>
            <a
              href="mailto:sales@publicdigit.com"
              class="border-2 border-white text-white font-bold px-8 py-3 rounded-lg hover:bg-white/10 transition-colors"
            >
              {{ t.cta.contact_sales }}
            </a>
          </div>
        </div>
      </section>

      <!-- Footer Spacing -->
      <div class="h-16"></div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { route } from 'ziggy-js'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import pageEn from '@/locales/pages/ElectionArchitecture/en.json'
import pageDe from '@/locales/pages/ElectionArchitecture/de.json'
import pageNp from '@/locales/pages/ElectionArchitecture/np.json'

const props = defineProps({
  phases: { type: Array, default: () => [] },
  images: { type: Object, default: () => ({}) },
})

const { locale } = useI18n()
const pageData = { en: pageEn, de: pageDe, np: pageNp }
const t = computed(() => pageData[locale.value] ?? pageData.en)

// FAQ accordion state
const openFaq = ref(null)
const toggleFaq = (idx) => {
  openFaq.value = openFaq.value === idx ? null : idx
}

// Language switcher with localStorage persistence
const setLocale = (lang) => {
  locale.value = lang
  localStorage.setItem('preferred_locale', lang)
}

// Load saved locale preference and Mermaid on mount
onMounted(() => {
  const saved = localStorage.getItem('preferred_locale')
  if (saved && ['en', 'de', 'np'].includes(saved)) {
    locale.value = saved
  }

  // Load Mermaid script dynamically
  const script = document.createElement('script')
  script.src = 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js'
  script.defer = true
  script.onload = () => {
    if (window.mermaid) {
      window.mermaid.initialize({
        startOnLoad: true,
        theme: 'base',
        securityLevel: 'loose',
        themeVariables: {
          primaryColor: '#1B2E4B',
          primaryBorderColor: '#D97706',
          lineColor: '#D97706',
        },
      })
      window.mermaid.contentLoaded()
    }
  }
  document.head.appendChild(script)
})

// Structured data (Schema.org)
const structuredData = {
  '@context': 'https://schema.org',
  '@type': 'SoftwareApplication',
  name: 'Public Digit Election System',
  applicationCategory: 'BusinessApplication',
  description: 'Tamper-proof election state machine with immutable audit trails',
  featureList: '5-phase lifecycle, Cryptographic verification, Immutable audit log',
  offers: {
    '@type': 'Offer',
    price: '0',
    priceCurrency: 'USD',
  },
}
</script>

<style scoped>
.expand-enter-active,
.expand-leave-active {
  transition: all 0.3s ease;
}

.expand-enter-from {
  opacity: 0;
  max-height: 0;
  overflow: hidden;
}

.expand-leave-to {
  opacity: 0;
  max-height: 0;
  overflow: hidden;
}
</style>
