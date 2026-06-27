<template>
  <div class="editorial-tutorial min-h-screen" style="background-color: #faf9f6; background-image: url('data:image/svg+xml,%3Csvg width=%2220%27 height=%2720%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 seed=%272%27 /%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 fill=%27%23faf9f6%27 filter=%27url(%23noise)%27 opacity=%270.03%27 /%3E%3C/svg%3E')">
    <PublicDigitHeader />

    <!-- Main Content -->
    <main class="py-12 lg:py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
          <!-- Sticky TOC Sidebar -->
          <aside class="lg:col-span-1">
            <div class="sticky top-24 bg-white rounded-sm p-8 border-l-4 transition-all duration-300 hover:shadow-lg" style="border-left-color: #d4a847; background: linear-gradient(135deg, #ffffff 0%, #fef9f3 100%)">
              <h3 class="text-xs font-bold uppercase tracking-widest mb-6" style="color: #1a1a2e; letter-spacing: 0.15em">{{ t.toc?.label || 'Contents' }}</h3>
              <nav class="space-y-3">
                <template v-if="t.toc?.items">
                  <a
                    v-for="(item, idx) in t.toc.items"
                    :key="item.id"
                    :href="`#${item.id}`"
                    class="toc-link block text-sm transition-all duration-300 py-2 pl-4 border-l-2 relative"
                    :style="{ borderColor: '#e0e0e0', color: '#4a5568' }"
                    @mouseenter="$event.target.style.borderColor = '#d4a847'; $event.target.style.color = '#1a1a2e'; $event.target.style.paddingLeft = '1.25rem'"
                    @mouseleave="$event.target.style.borderColor = '#e0e0e0'; $event.target.style.color = '#4a5568'; $event.target.style.paddingLeft = '1rem'"
                  >
                    <span class="text-xs font-bold uppercase tracking-wide" style="color: #d4a847">{{ String(idx + 1).padStart(2, '0') }}</span>
                    <br />
                    <span class="text-xs leading-tight mt-1 block">{{ item.label }}</span>
                  </a>
                </template>
              </nav>
            </div>
          </aside>

          <!-- Content Sections -->
          <div class="lg:col-span-3">
            <!-- Page Header -->
            <div class="mb-16">
              <h1 class="text-6xl lg:text-7xl font-serif font-normal mb-6" style="color: #1a1a2e; line-height: 1.1; letter-spacing: -0.02em">
                {{ t.page?.title || 'Voter Management Guide' }}
              </h1>
              <div class="w-16 h-1 mb-6" style="background: linear-gradient(90deg, #d4a847 0%, #0f766e 100%)"></div>
              <p class="text-xl" style="color: #4a5568; line-height: 1.7">{{ t.page?.subtitle }}</p>
              <div v-if="t.meta?.read_time" class="mt-6 flex items-center gap-3 text-sm">
                <span class="text-xs font-bold uppercase tracking-widest" style="color: #d4a847">{{ t.meta.badge_label || '⏱️' }}</span>
                <span style="color: #4a5568">{{ t.meta.read_time }}</span>
              </div>
            </div>

            <!-- SECTION 1: Overview -->
            <section v-if="t.section_overview" :id="`overview`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(244, 242, 235, 0.8) 0%, rgba(255, 255, 255, 0.4) 100%); padding: 4rem; border-top: 3px solid #d4a847; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #d4a847 0%, #c9992d 100%); color: white; box-shadow: 0 4px 20px rgba(212, 168, 71, 0.3)">①</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_overview.heading }}</h2>
              <div class="space-y-4" style="color: #4a5568">
                <p class="text-lg leading-relaxed whitespace-pre-line">{{ t.section_overview.body }}</p>
              </div>
            </section>

            <!-- SECTION 2: Before You Begin -->
            <section v-if="t.section_prerequisites" :id="`before-you-begin`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(254, 247, 243, 0.6) 100%); padding: 4rem; border-top: 3px solid #0f766e; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f5c 100%); color: white; box-shadow: 0 4px 20px rgba(15, 118, 110, 0.3)">②</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_prerequisites.heading }}</h2>
              <p class="text-lg mb-8" style="color: #4a5568">{{ t.section_prerequisites.intro }}</p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="(step, i) in t.section_prerequisites.steps" :key="i" class="p-6 rounded-sm transition-all duration-300 hover:shadow-md" style="border-left: 4px solid #d4a847; background: white">
                  <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold" style="background: #d4a847; color: white; flex-shrink: 0">{{ i + 1 }}</div>
                    <div>
                      <h4 class="font-semibold mb-2" style="color: #1a1a2e">{{ step.title }}</h4>
                      <p class="text-sm" style="color: #4a5568; line-height: 1.6">{{ step.body }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- SECTION 3: Adding Members as Voters -->
            <section v-if="t.section_add_voters" :id="`add-voters`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(244, 242, 235, 0.8) 0%, rgba(255, 255, 255, 0.4) 100%); padding: 4rem; border-top: 3px solid #d4a847; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #d4a847 0%, #c9992d 100%); color: white; box-shadow: 0 4px 20px rgba(212, 168, 71, 0.3)">③</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_add_voters.heading }}</h2>

              <div class="mb-10">
                <h3 class="text-sm font-bold uppercase tracking-widest mb-6" style="color: #d4a847">Step by Step</h3>
                <ol class="space-y-4">
                  <li v-for="(step, i) in t.section_add_voters.steps" :key="i" class="flex gap-6 pb-6 border-b" style="border-color: #e0e0e0">
                    <span class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full font-semibold text-lg flex-shrink-0" style="background: #f5f2eb; color: #d4a847">{{ i + 1 }}</span>
                    <div>
                      <h4 class="font-semibold mb-2 text-lg" style="color: #1a1a2e">{{ step.title }}</h4>
                      <p style="color: #4a5568; line-height: 1.7">{{ step.body }}</p>
                    </div>
                  </li>
                </ol>
              </div>

              <div class="p-8 rounded-sm" style="background: linear-gradient(135deg, #fef3c7 0%, #fef9e7 100%); border-left: 4px solid #d4a847">
                <h4 class="font-semibold mb-4 text-lg" style="color: #1a1a2e">{{ t.section_add_voters.eligibility_heading }}</h4>
                <ul class="space-y-3">
                  <li v-for="(item, i) in t.section_add_voters.eligibility_items" :key="i" class="flex items-start gap-3 text-sm" style="color: #4a5568">
                    <span class="flex-shrink-0 mt-1 w-5 h-5 flex items-center justify-center rounded-full font-bold text-xs flex-shrink-0" style="background: #d4a847; color: white">✓</span>
                    <span>{{ item }}</span>
                  </li>
                </ul>
              </div>
            </section>

            <!-- SECTION 4: Voter Verification Modes -->
            <section v-if="t.section_verification_modes" :id="`verification-modes`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(254, 247, 243, 0.6) 100%); padding: 4rem; border-top: 3px solid #0f766e; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f5c 100%); color: white; box-shadow: 0 4px 20px rgba(15, 118, 110, 0.3)">④</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_verification_modes.heading }}</h2>
              <p class="text-lg mb-10" style="color: #4a5568; line-height: 1.7">{{ t.section_verification_modes.body }}</p>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="(mode, i) in t.section_verification_modes.modes" :key="i" class="p-6 rounded-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1" style="background: white; border-top: 4px solid #d4a847">
                  <h3 class="font-serif text-2xl font-normal mb-3" style="color: #1a1a2e">{{ mode.name }}</h3>
                  <p class="text-sm mb-4" style="color: #4a5568; line-height: 1.7">{{ mode.description }}</p>
                  <div class="pt-4 border-t" style="border-color: #e0e0e0">
                    <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #d4a847">Use Case</p>
                    <p class="text-sm" style="color: #4a5568; line-height: 1.6">{{ mode.use_case }}</p>
                  </div>
                </div>
              </div>
            </section>

            <!-- SECTION 5: Video Call Workflow -->
            <section v-if="t.section_video_call" :id="`video-call-workflow`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(244, 242, 235, 0.8) 0%, rgba(255, 255, 255, 0.4) 100%); padding: 4rem; border-top: 3px solid #d4a847; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #d4a847 0%, #c9992d 100%); color: white; box-shadow: 0 4px 20px rgba(212, 168, 71, 0.3)">⑤</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_video_call.heading }}</h2>
              <div class="space-y-4">
                <ol class="space-y-4">
                  <li v-for="(step, i) in t.section_video_call.steps" :key="i" class="flex gap-6 py-4">
                    <span class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full font-semibold text-lg flex-shrink-0" style="background: #d4a847; color: white">{{ i + 1 }}</span>
                    <span style="color: #4a5568; line-height: 1.7">{{ step }}</span>
                  </li>
                </ol>
              </div>
            </section>

            <!-- SECTION 6: IP Capture -->
            <section v-if="t.section_ip_capture" :id="`ip-capture`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(254, 247, 243, 0.6) 100%); padding: 4rem; border-top: 3px solid #0f766e; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f5c 100%); color: white; box-shadow: 0 4px 20px rgba(15, 118, 110, 0.3)">⑥</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_ip_capture.heading }}</h2>
              <div class="space-y-4 mb-8" style="color: #4a5568">
                <p class="text-lg leading-relaxed whitespace-pre-line">{{ t.section_ip_capture.body }}</p>
              </div>

              <div class="p-8 rounded-sm" style="background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); border-left: 4px solid #0f766e">
                <h4 class="font-semibold mb-4 text-lg" style="color: #1a1a2e">{{ t.section_ip_capture.how_header || 'How it works:' }}</h4>
                <ul class="space-y-3">
                  <li v-for="(item, i) in t.section_ip_capture.how_it_works" :key="i" class="flex items-start gap-3 text-sm" style="color: #4a5568">
                    <span class="flex-shrink-0 mt-1 w-5 h-5 flex items-center justify-center rounded-full text-xs flex-shrink-0" style="background: #0f766e; color: white">•</span>
                    <span style="line-height: 1.6">{{ item }}</span>
                  </li>
                </ul>
              </div>
            </section>

            <!-- SECTION 7: Voting Enforcement -->
            <section v-if="t.section_enforcement" :id="`voting-enforcement`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(244, 242, 235, 0.8) 0%, rgba(255, 255, 255, 0.4) 100%); padding: 4rem; border-top: 3px solid #d4a847; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #d4a847 0%, #c9992d 100%); color: white; box-shadow: 0 4px 20px rgba(212, 168, 71, 0.3)">⑦</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_enforcement.heading }}</h2>
              <div class="space-y-4">
                <div v-for="(mode, i) in t.section_enforcement.modes" :key="i" class="p-6 rounded-sm transition-all duration-300 hover:shadow-md" style="background: white; border-left: 4px solid #d4a847">
                  <h4 class="font-semibold text-lg mb-2" style="color: #1a1a2e">{{ mode.name }}</h4>
                  <p class="text-sm" style="color: #4a5568; line-height: 1.7">{{ mode.behavior }}</p>
                </div>
              </div>
            </section>

            <!-- SECTION 8: Common Scenarios -->
            <section v-if="t.section_scenarios" :id="`scenarios`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(254, 247, 243, 0.6) 100%); padding: 4rem; border-top: 3px solid #0f766e; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #0f766e 0%, #0d5f5c 100%); color: white; box-shadow: 0 4px 20px rgba(15, 118, 110, 0.3)">⑧</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_scenarios.heading }}</h2>
              <div class="space-y-6">
                <div v-for="(scenario, i) in t.section_scenarios.items" :key="i" class="p-8 rounded-sm transition-all duration-300 hover:shadow-lg" style="background: white; border-left: 4px solid #d4a847">
                  <h4 class="font-serif text-2xl font-normal mb-3" style="color: #1a1a2e">{{ scenario.title }}</h4>
                  <p class="text-sm mb-6 italic" style="color: #4a5568">{{ scenario.setup }}</p>
                  <ol class="space-y-3">
                    <li v-for="(step, j) in scenario.steps" :key="j" class="flex gap-4 text-sm" style="color: #4a5568">
                      <span class="font-semibold flex-shrink-0" style="color: #d4a847">{{ j + 1 }}.</span>
                      <span style="line-height: 1.6">{{ step }}</span>
                    </li>
                  </ol>
                </div>
              </div>
            </section>

            <!-- SECTION 9: FAQ -->
            <section v-if="t.section_faq" :id="`faq`" class="mb-20 scroll-mt-20 editorial-section" style="background: linear-gradient(135deg, rgba(244, 242, 235, 0.8) 0%, rgba(255, 255, 255, 0.4) 100%); padding: 4rem; border-top: 3px solid #d4a847; position: relative">
              <div class="absolute -left-8 top-0 flex items-center justify-center w-16 h-16 rounded-full font-serif text-4xl font-bold" style="background: linear-gradient(135deg, #d4a847 0%, #c9992d 100%); color: white; box-shadow: 0 4px 20px rgba(212, 168, 71, 0.3)">⑨</div>
              <h2 class="text-4xl font-serif font-normal mb-6 mt-4" style="color: #1a1a2e; letter-spacing: -0.01em">{{ t.section_faq.heading }}</h2>
              <div class="space-y-3">
                <details v-for="(item, i) in t.section_faq.items" :key="i" class="group p-6 rounded-sm transition-all duration-300 cursor-pointer" style="background: white; border-left: 4px solid #0f766e">
                  <summary class="font-semibold text-lg flex items-start justify-between" style="color: #1a1a2e">
                    <span>{{ item.question }}</span>
                    <span class="text-lg transition-transform duration-300 group-open:rotate-180" style="color: #0f766e">›</span>
                  </summary>
                  <div class="px-0 py-4 text-sm" style="color: #4a5568; line-height: 1.7; border-top: 1px solid #e0e0e0; margin-top: 1rem">
                    {{ item.answer }}
                  </div>
                </details>
              </div>
            </section>

            <!-- Back Navigation -->
            <div v-if="t.back_text" class="mt-16 pt-8 border-t-2" style="border-color: #d4a847">
              <a href="/organisations" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest transition-all duration-300 hover:gap-3" style="color: #d4a847">
                ← {{ t.back_text }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </main>

    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useMeta } from '@/composables/useMeta'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

// SEO Meta Tags
useMeta({
  pageKey: 'tutorials.voters-management',
  url: '/help/voters-guide',
  type: 'article'
})

// i18n Loading
const localeModule = import.meta.glob(
  '../../locales/pages/Tutorials/VotersManagement/*.json',
  { eager: true }
)

const locale = ref(() => {
  const pathname = window.location.pathname
  if (pathname.startsWith('/de')) return 'de'
  if (pathname.startsWith('/np')) return 'np'
  return 'en'
})

const t = computed(() => {
  const lang = locale.value()
  const key = `../../locales/pages/Tutorials/VotersManagement/${lang}.json`
  return localeModule[key]?.default
    || localeModule['../../locales/pages/Tutorials/VotersManagement/en.json'].default
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@400;500;600;700&display=swap');

/* Root typography */
.editorial-tutorial {
  font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  color: #1a1a2e;
}

/* Section styling */
.editorial-section {
  animation: fadeInUp 0.6s ease-out backwards;
  scroll-margin-top: 6rem;
}

.editorial-section:nth-child(1) { animation-delay: 0.1s; }
.editorial-section:nth-child(2) { animation-delay: 0.2s; }
.editorial-section:nth-child(3) { animation-delay: 0.3s; }
.editorial-section:nth-child(4) { animation-delay: 0.4s; }
.editorial-section:nth-child(5) { animation-delay: 0.5s; }

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Typography */
h1, h2, h3, h4 {
  font-family: 'DM Serif Display', Georgia, serif;
  font-weight: 400;
  letter-spacing: -0.02em;
  line-height: 1.1;
}

h1 {
  font-size: clamp(2.5rem, 8vw, 4rem);
}

h2 {
  font-size: clamp(1.75rem, 5vw, 2.5rem);
}

/* Links */
a[href^="#"] {
  text-decoration: none;
  transition: all 0.3s ease;
}

a[href^="#"]:hover {
  text-decoration: underline;
}

/* TOC Link styling */
.toc-link {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.toc-link:hover {
  transform: translateX(4px);
}

/* Accordion */
details {
  transition: all 0.3s ease;
}

details[open] {
  background-color: rgba(255, 255, 255, 0.8) !important;
}

details summary::-webkit-details-marker {
  display: none;
}

details summary {
  outline: none;
}

/* Section number circles */
.editorial-section > div:first-child {
  animation: slideIn 0.6s ease-out backwards;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-40px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Responsive */
@media (max-width: 1024px) {
  aside {
    display: none;
  }

  .editorial-section {
    padding: 2rem !important;
  }

  .editorial-section > div:first-child {
    position: static;
    margin-bottom: 1rem;
    width: auto;
    left: auto;
  }

  h1 {
    font-size: clamp(1.75rem, 6vw, 2.5rem);
  }
}

/* Print styles */
@media print {
  .editorial-section {
    page-break-inside: avoid;
    box-shadow: none;
  }
}
</style>
