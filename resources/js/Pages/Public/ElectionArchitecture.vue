<template>
  <Head>
    <title>{{ $t('pages.election-architecture.seo.title') }}</title>
    <meta name="description" :content="$t('pages.election-architecture.seo.description')" />
    <meta name="keywords" :content="$t('pages.election-architecture.seo.keywords')" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta property="og:title" :content="$t('pages.election-architecture.seo.og_title')" />
    <meta property="og:description" :content="$t('pages.election-architecture.seo.og_description')" />
    <meta property="og:image" content="/storage/architecture/architecture-state-machine.png" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="canonical" href="https://publicdigit.com/election-architecture" />
    <link rel="preload" :href="images.stateMachine" as="image" />
  </Head>

  <PublicDigitLayout>
    <div class="bg-white">
      <!-- Hero Section -->
      <section class="relative bg-gradient-to-br from-primary-200 via-primary-300 to-primary-100 text-neutral-900 py-20 px-4 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
          <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-500 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto text-center">
          <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight text-neutral-900">
            {{ $t('pages.election-architecture.hero.title') }}
          </h1>
          <p class="text-xl md:text-2xl text-neutral-700 mb-12 max-w-2xl mx-auto">
            {{ $t('pages.election-architecture.hero.subtitle') }}
          </p>
          <div class="flex flex-wrap justify-center gap-4">
            <div class="bg-white/60 backdrop-blur-sm border border-primary-400/30 rounded-lg px-6 py-3 text-sm font-medium text-neutral-900">
              ✓ {{ $t('pages.election-architecture.hero.trust_badge_1') }}
            </div>
            <div class="bg-white/60 backdrop-blur-sm border border-primary-400/30 rounded-lg px-6 py-3 text-sm font-medium text-neutral-900">
              ✓ {{ $t('pages.election-architecture.hero.trust_badge_2') }}
            </div>
            <div class="bg-white/60 backdrop-blur-sm border border-primary-400/30 rounded-lg px-6 py-3 text-sm font-medium text-neutral-900">
              ✓ {{ $t('pages.election-architecture.hero.trust_badge_3') }}
            </div>
          </div>
        </div>
      </section>

      <!-- Breadcrumbs -->
      <nav class="max-w-6xl mx-auto px-4 py-6 text-sm text-neutral-600">
        <a href="/" class="hover:text-neutral-900">{{ $t('pages.election-architecture.breadcrumbs.home') }}</a>
        <span class="mx-2">/</span>
        <span class="text-neutral-900 font-medium">{{ $t('pages.election-architecture.breadcrumbs.election_architecture') }}</span>
      </nav>

      <!-- 5-Phase Lifecycle Section -->
      <section class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-neutral-900 mb-4">{{ $t('pages.election-architecture.phases.title') }}</h2>
          <p class="text-xl text-neutral-600">{{ $t('pages.election-architecture.phases.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <div
            v-for="(phase, idx) in phases"
            :key="phase.key"
            class="bg-gradient-to-br rounded-lg p-6 text-white shadow-lg hover:shadow-xl transition-shadow"
            :class="{
              'from-primary-500 to-primary-600': phase.color === 'blue',
              'from-success-500 to-success-600': phase.color === 'green',
              'from-accent-500 to-accent-600': phase.color === 'purple',
              'from-warning-500 to-warning-600': phase.color === 'orange',
              'from-success-600 to-success-700': phase.color === 'emerald',
            }"
          >
            <div class="text-4xl mb-4">{{ phase.icon }}</div>
            <h3 class="text-lg font-bold mb-2">{{ idx + 1 }}. {{ $t('pages.election-architecture.phases.' + phase.key + '.title') }}</h3>
            <p class="text-sm text-white/90">{{ $t('pages.election-architecture.phases.' + phase.key + '.description') }}</p>
          </div>
        </div>
      </section>

      <!-- Architecture Images Section -->
      <section class="max-w-6xl mx-auto px-4 py-16 border-t border-neutral-200">
        <h2 class="text-3xl font-bold text-neutral-900 mb-8 text-center">
          {{ $t('pages.election-architecture.visual_architecture_title') }}
        </h2>

        <div class="space-y-8">
          <div class="bg-neutral-50 rounded-lg overflow-hidden shadow-md">
            <img
              :src="images.stateMachine"
              :alt="$t('pages.election-architecture.images.state_machine_alt')"
              loading="lazy"
              class="w-full h-auto"
              width="800"
              height="600"
            />
          </div>
          <div class="bg-neutral-50 rounded-lg overflow-hidden shadow-md">
            <img
              :src="images.sequenceDiagram"
              :alt="$t('pages.election-architecture.images.sequence_diagram_alt')"
              loading="lazy"
              class="w-full h-auto"
              width="800"
              height="600"
            />
          </div>
        </div>
      </section>

      <!-- Security Features Grid -->
      <section class="max-w-6xl mx-auto px-4 py-16 border-t border-neutral-200">
        <h2 class="text-3xl font-bold text-neutral-900 mb-12 text-center">
          {{ $t('pages.election-architecture.security_features_title') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(feature, idx) in securityFeatures" :key="idx" class="bg-white rounded-lg p-8 border border-neutral-200 hover:border-primary-500 transition-colors">
            <div class="text-4xl mb-4">{{ feature.icon }}</div>
            <h3 class="text-xl font-bold text-neutral-900 mb-3">{{ feature.title }}</h3>
            <p class="text-neutral-600">{{ feature.description }}</p>
          </div>
        </div>
      </section>

      <!-- Benefits Section -->
      <section class="bg-primary-50 py-16 border-t border-neutral-200">
        <div class="max-w-6xl mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Admins Benefits -->
            <div>
              <h3 class="text-2xl font-bold text-neutral-900 mb-6">{{ $t('pages.election-architecture.benefits_admin.title') }}</h3>
              <ul class="space-y-3">
                <li v-for="(item, idx) in adminBenefits" :key="idx" class="flex items-start gap-3 text-neutral-700">
                  <span class="text-primary-600 font-bold flex-shrink-0">✓</span>
                  <span>{{ item }}</span>
                </li>
              </ul>
            </div>

            <!-- Voter Benefits -->
            <div>
              <h3 class="text-2xl font-bold text-neutral-900 mb-6">{{ $t('pages.election-architecture.benefits_voter.title') }}</h3>
              <ul class="space-y-3">
                <li v-for="(item, idx) in voterBenefits" :key="idx" class="flex items-start gap-3 text-neutral-700">
                  <span class="text-primary-600 font-bold flex-shrink-0">✓</span>
                  <span>{{ item }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section class="max-w-4xl mx-auto px-4 py-16 border-t border-neutral-200">
        <h2 class="text-3xl font-bold text-neutral-900 mb-12 text-center">
          {{ $t('pages.election-architecture.faq_title') }}
        </h2>
        <div class="space-y-4">
          <div v-for="(item, idx) in faqItems" :key="idx" class="border border-neutral-200 rounded-lg overflow-hidden hover:border-primary-500 transition-colors">
            <Button
              variant="ghost"
              @click="toggleFaq(idx)"
              class="w-full flex justify-between items-center p-6 rounded-none"
            >
              <span class="text-lg font-semibold text-neutral-900 text-left">{{ item.question }}</span>
              <span class="text-primary-600 font-bold text-xl flex-shrink-0 ml-4">
                {{ openFaq === idx ? '−' : '+' }}
              </span>
            </Button>
            <transition name="expand">
              <div v-show="openFaq === idx" class="border-t border-neutral-200 bg-neutral-50 p-6">
                <p class="text-neutral-700">{{ item.answer }}</p>
              </div>
            </transition>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="relative bg-gradient-to-br from-primary-200 via-primary-300 to-primary-100 text-neutral-900 py-20 mt-16 border-t border-neutral-200 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
          <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-500 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
          <h2 class="text-4xl font-bold mb-4 text-neutral-900">{{ $t('pages.election-architecture.cta.heading') }}</h2>
          <p class="text-xl text-neutral-700 mb-8">{{ $t('pages.election-architecture.cta.subheading') }}</p>
          <div class="flex flex-wrap justify-center gap-4">
            <a
              href="#"
              @click.prevent="router.get(route('organisations.elections.create'))"
              class="bg-white text-primary-600 font-bold px-8 py-3 rounded-lg hover:bg-primary-50 transition-colors shadow-lg cursor-pointer"
            >
              {{ $t('pages.election-architecture.cta.start_election') }}
            </a>
            <a
              href="mailto:sales@publicdigit.com"
              class="border-2 border-neutral-900 text-neutral-900 font-bold px-8 py-3 rounded-lg hover:bg-neutral-900/10 transition-colors"
            >
              {{ $t('pages.election-architecture.cta.contact_sales') }}
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
import Button from '@/Components/Button.vue'

const props = defineProps({
  phases: { type: Array, default: () => [] },
  images: { type: Object, default: () => ({}) },
})

const { locale, tm } = useI18n()

// Arrays sourced from locale — reactive to language changes
const securityFeatures = computed(() => tm('pages.election-architecture.security_features'))
const faqItems         = computed(() => tm('pages.election-architecture.faq'))
const adminBenefits    = computed(() => tm('pages.election-architecture.benefits_admin.items'))
const voterBenefits    = computed(() => tm('pages.election-architecture.benefits_voter.items'))

// FAQ accordion state
const openFaq = ref(null)
const toggleFaq = (idx) => {
  openFaq.value = openFaq.value === idx ? null : idx
}

// Language switcher with localStorage persistence
const setLocale = (lang) => {
  locale.value = lang
  // Persist locale preference for consistent navigation experience
  const date = new Date()
  date.setFullYear(date.getFullYear() + 1)
  document.cookie = `locale=${lang}; expires=${date.toUTCString()}; path=/`
}

onMounted(() => {
  // Restore locale from cookie if saved
  const cookies = document.cookie.split('; ').reduce((acc, c) => {
    const [key, val] = c.split('=')
    acc[key] = val
    return acc
  }, {})
  const saved = cookies['locale']
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
