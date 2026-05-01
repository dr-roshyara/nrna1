<script setup>
import { useMeta } from '@/composables/useMeta'
import { computed, onMounted, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
import { CheckCircleIcon, HandThumbDownIcon, HandThumbUpIcon, MagnifyingGlassIcon, PrinterIcon, XMarkIcon } from '@heroicons/vue/24/outline'

defineProps({
  organisation: Object,
  usesFullMembership: Boolean,
})

const { locale } = useI18n()

// Load locale JSON for this page
const localeModule = import.meta.glob('../../locales/pages/NewsletterGuide/*.json', { eager: true })
const t = computed(() => {
  const key = `../../locales/pages/NewsletterGuide/${locale.value}.json`
  return localeModule[key]?.default || localeModule['../../locales/pages/NewsletterGuide/en.json'].default
})

// Set up meta tags
useMeta({
  pageKey: 'newsletter-guide',
  url: `/newsletter-guide`,
  type: 'article',
  seoTitle: computed(() => t.value?.seo?.title),
  seoDescription: computed(() => t.value?.seo?.description),
  seoKeywords: computed(() => t.value?.seo?.keywords),
  seoRobots: computed(() => t.value?.seo?.robots),
})

// Search functionality
const searchQuery = ref('')
const showSearch = ref(false)
const sections = [
  'who_can_use',
  'lifecycle',
  'compose',
  'send',
  'election_committee',
  'unsubscribe',
  'faq'
]

const searchResults = computed(() => {
  if (!searchQuery.value.trim() || !t.value) return []

  const q = searchQuery.value.toLowerCase()
  const results = []

  sections.forEach(sectionKey => {
    const section = t.value[sectionKey]
    if (!section) return

    // Search in title
    if (section.title?.toLowerCase().includes(q)) {
      results.push({
        id: sectionKey,
        title: section.title,
        type: 'section'
      })
    }

    // Search in description
    if (section.description?.toLowerCase().includes(q)) {
      results.push({
        id: sectionKey,
        title: section.title,
        snippet: section.description.substring(0, 100) + '...',
        type: 'section'
      })
    }

    // Search in FAQ items
    if (section.items && Array.isArray(section.items)) {
      section.items.forEach((item, idx) => {
        if (item.q?.toLowerCase().includes(q) || item.a?.toLowerCase().includes(q)) {
          results.push({
            id: `faq-${idx}`,
            title: item.q,
            type: 'faq'
          })
        }
      })
    }

    // Search in steps
    if (section.steps && Array.isArray(section.steps)) {
      section.steps.forEach((step, idx) => {
        if (step.heading?.toLowerCase().includes(q) || step.body?.toLowerCase().includes(q)) {
          results.push({
            id: `step-${idx}`,
            title: step.heading,
            type: 'step'
          })
        }
      })
    }
  })

  return results.slice(0, 10)
})

const scrollToSection = (sectionId) => {
  const element = document.getElementById(sectionId)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' })
    showSearch.value = false
    searchQuery.value = ''
  }
}

// Keyboard shortcuts
onMounted(() => {
  const handleKeydown = (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault()
      showSearch.value = !showSearch.value
      if (showSearch.value) {
        setTimeout(() => {
          const input = document.getElementById('search-input')
          if (input) input.focus()
        }, 0)
      }
    }
    if (e.key === 'Escape' && showSearch.value) {
      showSearch.value = false
    }
  }

  document.addEventListener('keydown', handleKeydown)
  return () => document.removeEventListener('keydown', handleKeydown)
})

// Scroll-spy ToC
const activeSection = ref('who_can_use')
onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          activeSection.value = entry.target.id
        }
      })
    },
    { threshold: 0.3 }
  )

  sections.forEach((sectionId) => {
    const element = document.getElementById(sectionId)
    if (element) observer.observe(element)
  })

  return () => observer.disconnect()
})

// Interactive demo
const demoAudienceType = ref('all_members')
const demoCount = computed(() => {
  return t.value?.demo?.audience_types?.[demoAudienceType.value]?.mock_count || 0
})

// Feedback
const feedbackGiven = ref(null)
const feedbackText = ref('')
const showFeedbackForm = ref(false)
const feedbackSubmitted = ref(false)

const submitFeedback = () => {
  if (feedbackGiven.value !== null) {
    console.log('track:guide_feedback', {
      helpful: feedbackGiven.value,
      text: feedbackText.value,
      timestamp: new Date().toISOString()
    })
    feedbackSubmitted.value = true
    setTimeout(() => {
      feedbackGiven.value = null
      feedbackText.value = ''
      feedbackSubmitted.value = false
      showFeedbackForm.value = false
    }, 2000)
  }
}

// Track page view
onMounted(() => {
  console.log('track:page_view', 'newsletter-guide', locale.value)
})

// Back button handler
const goBack = () => {
  window.history.back()
}
</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Skip navigation -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-0 focus:left-0 focus:z-50 focus:p-4 focus:bg-amber-900 focus:text-white focus:rounded">
      {{ t?.skip_nav }}
    </a>

    <PublicDigitHeader />

    <!-- Back button -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
      <button
        @click="goBack"
        type="button"
        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Newsletters
      </button>
    </div>

    <!-- Hero section -->
    <section class="relative py-16 px-4 sm:px-6 lg:px-8 border-b border-amber-200">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">{{ t?.meta?.badge_label }}</span>
          <span class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-xs font-medium rounded-full">{{ t?.meta?.read_time }}</span>
        </div>

        <h1 class="text-4xl md:text-5xl font-serif text-amber-900 mb-4">{{ t?.hero?.title }}</h1>
        <p class="text-xl text-amber-800 mb-4">{{ t?.hero?.subtitle }}</p>
        <p class="text-base text-neutral-700 leading-relaxed max-w-3xl">{{ t?.hero?.description }}</p>
      </div>
    </section>

    <!-- Main content grid -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8 py-12 px-4 sm:px-6 lg:px-8">
      <!-- Sticky ToC sidebar -->
      <aside class="lg:col-span-1">
        <nav aria-label="Table of contents" class="sticky top-6 bg-white border border-amber-200 rounded-lg p-6 space-y-3">
          <h2 class="text-sm font-bold text-amber-900 uppercase tracking-wide">{{ t?.toc?.heading }}</h2>
          <ul class="space-y-2 text-sm">
            <li v-for="(label, key) in t?.toc?.items" :key="key">
              <button
                @click="scrollToSection(key)"
                :class="[
                  'w-full text-left px-3 py-2 rounded transition-colors',
                  activeSection === key
                    ? 'bg-amber-100 text-amber-900 font-semibold border-l-2 border-amber-700'
                    : 'text-neutral-700 hover:bg-neutral-100'
                ]"
              >
                {{ label }}
              </button>
            </li>
          </ul>

          <!-- Search box -->
          <div class="pt-4 border-t border-neutral-200">
            <div class="relative">
              <input
                id="search-input"
                v-model="searchQuery"
                type="text"
                :placeholder="t?.search?.placeholder"
                class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
              />
              <MagnifyingGlassIcon class="absolute right-3 top-2.5 w-4 h-4 text-neutral-400" />
            </div>

            <!-- Search results -->
            <div v-if="showSearch || searchQuery" class="absolute top-full left-0 right-0 mt-2 bg-white border border-neutral-300 rounded-lg shadow-lg z-10 max-h-64 overflow-y-auto">
              <div v-if="searchResults.length === 0" class="p-4 text-sm text-neutral-600">
                {{ t?.search?.no_results }}
              </div>
              <ul v-else class="divide-y">
                <li v-for="result in searchResults" :key="result.id">
                  <button
                    @click="scrollToSection(result.id)"
                    class="w-full text-left px-4 py-3 hover:bg-amber-50 transition-colors"
                  >
                    <div class="font-medium text-sm text-amber-900">{{ result.title }}</div>
                    <div v-if="result.snippet" class="text-xs text-neutral-600 mt-1">{{ result.snippet }}</div>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </aside>

      <!-- Main content -->
      <main id="main-content" class="lg:col-span-3 space-y-16">
        <!-- Who Can Use -->
        <section id="who_can_use" aria-labelledby="who_can_use-heading" class="scroll-mt-8">
          <h2 id="who_can_use-heading" class="text-3xl font-serif text-amber-900 mb-6">{{ t?.who_can_use?.title }}</h2>
          <p class="text-neutral-700 mb-8">{{ t?.who_can_use?.description }}</p>

          <div class="grid md:grid-cols-3 gap-6">
            <div v-for="role in t?.who_can_use?.roles" :key="role.name" class="border border-amber-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
              <h3 class="font-semibold text-amber-900 mb-2">{{ role.name }}</h3>
              <p class="text-sm text-neutral-700">{{ role.description }}</p>
            </div>
          </div>
        </section>

        <!-- Lifecycle -->
        <section id="lifecycle" aria-labelledby="lifecycle-heading" class="scroll-mt-8">
          <h2 id="lifecycle-heading" class="text-3xl font-serif text-amber-900 mb-6">{{ t?.lifecycle?.title }}</h2>
          <p class="text-neutral-700 mb-8">{{ t?.lifecycle?.description }}</p>

          <div class="space-y-4">
            <div v-for="(stage, key) in t?.lifecycle?.stages" :key="key" class="border-l-4 border-amber-600 pl-6 py-4">
              <h3 class="font-semibold text-lg text-amber-900">{{ stage.label }}</h3>
              <p class="text-neutral-700 mt-2">{{ stage.description }}</p>
            </div>
          </div>
        </section>

        <!-- Compose Steps -->
        <section id="compose" aria-labelledby="compose-heading" class="scroll-mt-8">
          <h2 id="compose-heading" class="text-3xl font-serif text-amber-900 mb-6">{{ t?.compose?.title }}</h2>
          <p class="text-neutral-700 mb-8">{{ t?.compose?.intro }}</p>

          <div class="space-y-8">
            <div v-for="step in t?.compose?.steps" :key="step.number" class="border border-amber-200 rounded-lg p-6 hover:shadow-md transition-shadow">
              <div class="flex gap-4">
                <div class="flex-shrink-0">
                  <div class="flex items-center justify-center h-10 w-10 rounded-full bg-amber-600 text-white font-bold text-lg">
                    {{ step.number }}
                  </div>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-lg text-amber-900 mb-2">{{ step.heading }}</h3>
                  <p class="text-neutral-700 whitespace-pre-wrap mb-3">{{ step.body }}</p>
                  <div v-if="step.tip" class="bg-primary-50 border-l-4 border-primary-400 p-3 text-sm text-primary-900">
                    <strong>💡 Tip:</strong> {{ step.tip }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Send & Monitor Steps -->
        <section id="send" aria-labelledby="send-heading" class="scroll-mt-8">
          <h2 id="send-heading" class="text-3xl font-serif text-amber-900 mb-6">{{ t?.send?.title }}</h2>
          <p class="text-neutral-700 mb-8">{{ t?.send?.intro }}</p>

          <div v-if="t?.send?.warning" class="bg-danger-50 border-l-4 border-danger-500 p-4 mb-8 text-danger-900 text-sm">
            <strong>⚠️ Important:</strong> {{ t.send.warning }}
          </div>

          <div class="space-y-8">
            <div v-for="step in t?.send?.steps" :key="step.number" class="border border-amber-200 rounded-lg p-6 hover:shadow-md transition-shadow">
              <div class="flex gap-4">
                <div class="flex-shrink-0">
                  <div class="flex items-center justify-center h-10 w-10 rounded-full bg-amber-600 text-white font-bold text-lg">
                    {{ step.number }}
                  </div>
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold text-lg text-amber-900 mb-2">{{ step.heading }}</h3>
                  <p class="text-neutral-700 whitespace-pre-wrap mb-3">{{ step.body }}</p>
                  <div v-if="step.tip" class="bg-primary-50 border-l-4 border-primary-400 p-3 text-sm text-primary-900">
                    <strong>💡 Tip:</strong> {{ step.tip }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Election Committee (conditional) -->
        <section v-if="usesFullMembership !== false" id="election_committee" aria-labelledby="election_committee-heading" class="scroll-mt-8">
          <h2 id="election_committee-heading" class="text-3xl font-serif text-amber-900 mb-6">{{ t?.election_committee?.title }}</h2>
          <p class="text-neutral-700 mb-8">{{ t?.election_committee?.description }}</p>

          <div class="space-y-6">
            <div v-for="useCase in t?.election_committee?.use_cases" :key="useCase.heading" class="border border-primary-200 rounded-lg p-6 bg-primary-50">
              <h3 class="font-semibold text-lg text-primary-900 mb-2">{{ useCase.heading }}</h3>
              <p class="text-neutral-700 mb-3">{{ useCase.description }}</p>
              <div class="bg-white rounded p-3 text-sm text-neutral-600 font-mono">{{ useCase.example }}</div>
            </div>
          </div>
        </section>

        <!-- Interactive Demo -->
        <section class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg p-8">
          <h2 class="text-2xl font-serif text-amber-900 mb-2">{{ t?.demo?.title }}</h2>
          <p class="text-neutral-700 mb-6">{{ t?.demo?.subtitle }}</p>

          <label class="block mb-6">
            <span class="text-sm font-semibold text-amber-900 mb-2 block">{{ t?.demo?.select_label }}</span>
            <select v-model="demoAudienceType" class="w-full px-4 py-2 border border-amber-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
              <option v-for="(audience, key) in t?.demo?.audience_types" :key="key" :value="key">
                {{ audience.label }}
              </option>
            </select>
          </label>

          <div class="bg-white rounded-lg p-6 border border-amber-200">
            <div class="text-center">
              <div class="text-4xl font-bold text-amber-600 mb-2">{{ demoCount }}</div>
              <p class="text-neutral-700">{{ t?.demo?.will_send_to?.replace('{count}', demoCount) }}</p>
            </div>
          </div>
        </section>

        <!-- Unsubscribe -->
        <section id="unsubscribe" aria-labelledby="unsubscribe-heading" class="scroll-mt-8">
          <h2 id="unsubscribe-heading" class="text-3xl font-serif text-amber-900 mb-6">{{ t?.unsubscribe?.title }}</h2>
          <p class="text-neutral-700 mb-6">{{ t?.unsubscribe?.description }}</p>

          <div class="space-y-4">
            <div v-if="t?.unsubscribe?.automatic_note" class="bg-neutral-50 border-l-4 border-neutral-400 p-4 text-neutral-900 text-sm">
              {{ t.unsubscribe.automatic_note }}
            </div>
            <div v-if="t?.unsubscribe?.bounce_note" class="bg-neutral-50 border-l-4 border-neutral-400 p-4 text-neutral-900 text-sm">
              {{ t.unsubscribe.bounce_note }}
            </div>
            <div v-if="t?.unsubscribe?.hard_bounce_note" class="bg-neutral-50 border-l-4 border-neutral-400 p-4 text-neutral-900 text-sm">
              {{ t.unsubscribe.hard_bounce_note }}
            </div>
            <div v-if="t?.unsubscribe?.resubscribe_note" class="bg-neutral-50 border-l-4 border-neutral-400 p-4 text-neutral-900 text-sm">
              {{ t.unsubscribe.resubscribe_note }}
            </div>
          </div>
        </section>

        <!-- FAQ -->
        <section id="faq" aria-labelledby="faq-heading" class="scroll-mt-8">
          <h2 id="faq-heading" class="text-3xl font-serif text-amber-900 mb-8">{{ t?.faq?.title }}</h2>

          <div class="space-y-3">
            <details v-for="(item, idx) in t?.faq?.items" :key="idx" class="border border-amber-200 rounded-lg">
              <summary class="px-6 py-4 font-semibold text-amber-900 cursor-pointer hover:bg-amber-50 transition-colors">
                {{ item.q }}
              </summary>
              <div class="px-6 py-4 border-t border-amber-200 bg-amber-50 text-neutral-700">
                {{ item.a }}
              </div>
            </details>
          </div>
        </section>

        <!-- Feedback Widget -->
        <section class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-8">
          <h3 class="text-xl font-semibold text-purple-900 mb-4">{{ t?.feedback?.prompt }}</h3>

          <div v-if="!feedbackSubmitted" class="space-y-4">
            <div class="flex gap-3">
              <button
                @click="feedbackGiven = true; showFeedbackForm = true"
                :class="[
                  'flex-1 px-4 py-2 rounded-lg font-medium transition-all',
                  feedbackGiven === true
                    ? 'bg-green-600 text-white'
                    : 'bg-green-100 text-green-900 hover:bg-green-200'
                ]"
              >
                <HandThumbUpIcon class="w-5 h-5 inline-block mr-2" />
                {{ t?.feedback?.yes }}
              </button>
              <button
                @click="feedbackGiven = false; showFeedbackForm = true"
                :class="[
                  'flex-1 px-4 py-2 rounded-lg font-medium transition-all',
                  feedbackGiven === false
                    ? 'bg-danger-600 text-white'
                    : 'bg-danger-100 text-danger-900 hover:bg-danger-200'
                ]"
              >
                <HandThumbDownIcon class="w-5 h-5 inline-block mr-2" />
                {{ t?.feedback?.no }}
              </button>
            </div>

            <textarea
              v-if="showFeedbackForm && feedbackGiven === false"
              v-model="feedbackText"
              :placeholder="t?.feedback?.textarea_placeholder"
              class="w-full px-4 py-2 border border-neutral-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
              rows="3"
            ></textarea>

            <button
              v-if="showFeedbackForm"
              @click="submitFeedback"
              class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors"
            >
              {{ t?.feedback?.submit }}
            </button>
          </div>

          <div v-else class="text-center text-green-700 font-medium">
            ✓ {{ t?.feedback?.thank_you }}
          </div>
        </section>

        <!-- Print button -->
        <div class="flex justify-center mb-8">
          <button
            @click="window.print()"
            class="inline-flex items-center gap-2 px-6 py-3 bg-neutral-200 text-neutral-900 rounded-lg font-medium hover:bg-neutral-300 transition-colors no-print"
          >
            <PrinterIcon class="w-5 h-5" />
            {{ t?.print?.button }}
          </button>
        </div>

        <!-- Last updated -->
        <div class="text-center text-sm text-neutral-600 py-6">
          {{ t?.last_updated?.label }}: {{ t?.last_updated?.date }}
        </div>

        <!-- CTA -->
        <section class="bg-gradient-to-r from-amber-600 to-yellow-600 rounded-lg p-8 text-white text-center">
          <h2 class="text-2xl font-serif mb-4">{{ t?.cta?.title }}</h2>
          <a
            v-if="organisation"
            :href="route('organisations.membership.newsletters.index', organisation.slug)"
            class="inline-block px-8 py-3 bg-white text-amber-700 rounded-lg font-semibold hover:bg-neutral-100 transition-colors"
          >
            {{ t?.cta?.with_org?.replace('{org}', organisation.name) }}
          </a>
          <p v-else class="text-amber-100 mb-4">{{ t?.cta?.login_prompt }}</p>
        </section>
      </main>
    </div>

    <PublicDigitFooter />
  </div>
</template>

<style scoped>
@media print {
  .no-print {
    display: none;
  }

  .sticky {
    position: static;
  }

  main {
    grid-column: 1 / -1;
  }
}

::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

