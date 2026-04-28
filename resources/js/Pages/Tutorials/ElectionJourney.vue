<script setup>
import { useMeta } from '@/composables/useMeta'
import { computed, ref } from 'vue'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

useMeta({
  pageKey: 'tutorials.election-journey',
  url: '/help/election-journey',
  type: 'article',
})

// Locale detection from pathname
const locale = ref(() => {
  const pathname = window.location.pathname
  if (pathname.startsWith('/de')) return 'de'
  if (pathname.startsWith('/np')) return 'np'
  return 'en'
})

// Import locale JSON based on current language
const localeModule = import.meta.glob(
  '../../locales/pages/Tutorials/ElectionJourney/*.json',
  { eager: true }
)

const t = computed(() => {
  const lang = locale.value()
  const key = `../../locales/pages/Tutorials/ElectionJourney/${lang}.json`
  return localeModule[key]?.default
    || localeModule['../../locales/pages/Tutorials/ElectionJourney/en.json'].default
})

const phases = [
  { state: 'draft', name: 'Draft', icon: '📝' },
  { state: 'pending_approval', name: 'Pending Approval', icon: '⏳' },
  { state: 'administration', name: 'Administration', icon: '⚙️' },
  { state: 'nomination', name: 'Nomination', icon: '📋' },
  { state: 'voting', name: 'Voting', icon: '🗳️' },
  { state: 'results_pending', name: 'Results Pending', icon: '📊' },
  { state: 'results', name: 'Results', icon: '✅' },
]
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <!-- PublicDigit Header -->
    <PublicDigitHeader :breadcrumbs="[]" :disable-language-selector="false" />

    <main class="flex-1">
      <!-- Hero Section -->
      <section class="hero-section">
        <div class="hero-background"></div>
        <div class="hero-content">
          <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="hero-title">{{ t.hero.title }}</h1>
            <p class="hero-subtitle">{{ t.hero.subtitle }}</p>
          </div>
        </div>
      </section>

      <!-- Subscription Model Section -->
      <section class="section-subscription">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="section-title">{{ t.subscription.title }}</h2>

          <div class="subscription-cards">
            <!-- Free Plan -->
            <div class="subscription-card free">
              <div class="card-badge">{{ t.subscription.free.badge }}</div>
              <h3 class="card-title">{{ t.subscription.free.title }}</h3>
              <p class="card-description">{{ t.subscription.free.description }}</p>
              <div class="card-feature">
                <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ t.subscription.free.feature }}</span>
              </div>
            </div>

            <!-- Paid Plan -->
            <div class="subscription-card paid">
              <div class="card-badge">{{ t.subscription.paid.badge }}</div>
              <h3 class="card-title">{{ t.subscription.paid.title }}</h3>
              <p class="card-description">{{ t.subscription.paid.description }}</p>
              <div class="card-feature">
                <svg class="feature-icon" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                </svg>
                <span>{{ t.subscription.paid.feature }}</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Timeline Overview Section -->
      <section class="section-timeline-overview">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="section-title">{{ t.timeline.title }}</h2>

          <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="phases-grid">
              <div
                v-for="(phase, index) in phases"
                :key="phase.state"
                class="phase-marker-wrapper"
                :style="{ '--phase-index': index }"
              >
                <div class="phase-marker">
                  <div class="marker-icon">{{ phase.icon }}</div>
                </div>
                <div class="marker-label">
                  <div class="label-name">{{ phase.name }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Phase Details Section -->
      <section class="section-phases">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div
            v-for="(phase, index) in phases"
            :key="phase.state"
            class="phase-card"
            :class="{ 'phase-featured': phase.state === 'nomination' }"
          >
            <!-- Phase Header -->
            <div class="phase-header">
              <div class="header-left">
                <div class="phase-icon-large">{{ phase.icon }}</div>
                <div>
                  <h3 class="phase-name">{{ t.phases[phase.state].name }}</h3>
                  <div class="phase-index">Phase {{ index + 1 }} of 7</div>
                </div>
              </div>
              <div class="phase-number">{{ String(index + 1).padStart(2, '0') }}</div>
            </div>

            <!-- Phase Content -->
            <div class="phase-content">
              <div class="content-block">
                <h4 class="content-label">What happens</h4>
                <p class="content-text">{{ t.phases[phase.state].description }}</p>
              </div>

              <div class="content-block">
                <h4 class="content-label">Who acts</h4>
                <p class="content-text">{{ t.phases[phase.state].who_acts }}</p>
              </div>

              <div v-if="t.phases[phase.state].locked_means" class="content-block warning">
                <h4 class="content-label">When "locked"</h4>
                <p class="content-text">{{ t.phases[phase.state].locked_means }}</p>
              </div>
            </div>

            <!-- Screenshot for Nomination Phase -->
            <div v-if="phase.state === 'nomination'" class="phase-screenshot">
              <img
                src="/images/election-journey/nomination_is_locked.png"
                alt="Nomination Locked badge showing on the Election Journey panel — dates are frozen but Open Voting is still available"
                loading="lazy"
                class="screenshot-image"
              />
              <div class="screenshot-caption">
                <svg class="caption-icon" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
                <p>{{ t.screenshot_caption }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Glossary Section -->
      <section class="section-glossary">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="section-title">{{ t.glossary.title }}</h2>

          <div class="glossary-terms">
            <div
              v-for="(term, key) in t.glossary.terms"
              :key="key"
              class="glossary-card"
            >
              <div class="glossary-term">{{ term.term }}</div>
              <div class="glossary-definition">{{ term.definition }}</div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="section-cta">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 class="cta-title">{{ t.cta.title }}</h2>
          <p class="cta-subtitle">{{ t.cta.subtitle }}</p>
          <a href="/" class="cta-button">{{ t.cta.button }}</a>
        </div>
      </section>
    </main>

    <!-- PublicDigit Footer -->
    <PublicDigitFooter />
  </div>
</template>

<style scoped>
/* Typography System */
:root {
  --font-display: 'Playfair Display', Georgia, serif;
  --font-body: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

  --color-bg: #faf9f7;
  --color-text: #1f2937;
  --color-text-light: #6b7280;
  --color-accent: #06b6d4;
  --color-accent-dark: #0891b2;
  --color-accent-light: #cffafe;
  --color-border: #e5e7eb;
  --color-warning: #f59e0b;
  --color-warning-light: #fef3c7;
}

/* Hero Section */
.hero-section {
  position: relative;
  overflow: hidden;
  padding: 6rem 0;
  background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
}

.hero-background {
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 25% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 75% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  pointer-events: none;
}

.hero-content {
  position: relative;
  z-index: 1;
  color: white;
  animation: slideInDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.hero-title {
  font-family: var(--font-display);
  font-size: clamp(2.5rem, 8vw, 4.5rem);
  font-weight: 700;
  line-height: 1.1;
  margin-bottom: 1rem;
  letter-spacing: -0.02em;
}

.hero-subtitle {
  font-size: clamp(1.125rem, 4vw, 1.5rem);
  font-weight: 300;
  opacity: 0.95;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}

/* Subscription Section */
.section-subscription {
  padding: 6rem 0;
  background: var(--color-bg);
}

.section-title {
  font-family: var(--font-display);
  font-size: clamp(2rem, 6vw, 3rem);
  font-weight: 700;
  text-align: center;
  margin-bottom: 4rem;
  color: var(--color-text);
  letter-spacing: -0.01em;
}

.subscription-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2.5rem;
  margin-bottom: 2rem;
}

.subscription-card {
  position: relative;
  padding: 2.5rem;
  border-radius: 1.5rem;
  border: 2px solid var(--color-border);
  background: white;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

.subscription-card:nth-child(1) { animation-delay: 0.1s; }
.subscription-card:nth-child(2) { animation-delay: 0.2s; }

.subscription-card:hover {
  transform: translateY(-8px);
  border-color: var(--color-accent);
  box-shadow: 0 20px 40px rgba(6, 182, 212, 0.15);
}

.subscription-card.free .card-badge {
  background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
  color: #065f46;
}

.subscription-card.paid .card-badge {
  background: linear-gradient(135deg, #fef08a 0%, #fcd34d 100%);
  color: #78350f;
}

.card-badge {
  display: inline-block;
  padding: 0.375rem 0.875rem;
  border-radius: 0.75rem;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.card-title {
  font-family: var(--font-display);
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: var(--color-text);
}

.card-description {
  color: var(--color-text-light);
  line-height: 1.6;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
}

.card-feature {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: var(--color-accent-dark);
  font-weight: 600;
}

.feature-icon {
  width: 1.5rem;
  height: 1.5rem;
  flex-shrink: 0;
}

/* Timeline Overview */
.section-timeline-overview {
  padding: 6rem 0;
  background: linear-gradient(to bottom, white, rgba(6, 182, 212, 0.02));
}

.timeline-container {
  position: relative;
  padding: 3rem 0;
}

.timeline-line {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--color-accent-light), var(--color-accent), var(--color-accent-light));
  z-index: 0;
}

.phases-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 1.5rem;
  position: relative;
  z-index: 1;
}

.phase-marker-wrapper {
  text-align: center;
  animation: slideInUp 0.6s ease-out forwards;
  opacity: 0;
  animation-delay: calc(var(--phase-index) * 0.1s);
}

.phase-marker {
  width: 80px;
  height: 80px;
  margin: 0 auto 1rem;
  border-radius: 50%;
  border: 3px solid var(--color-border);
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  cursor: pointer;
}

.phase-marker:hover {
  border-color: var(--color-accent);
  transform: scale(1.1);
  box-shadow: 0 12px 24px rgba(6, 182, 212, 0.2);
}

.marker-icon {
  font-size: 2.5rem;
  line-height: 1;
}

.marker-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--color-text);
}

.label-name {
  margin-top: 0.5rem;
}

/* Phase Cards */
.section-phases {
  padding: 4rem 0 6rem;
}

.phase-card {
  background: white;
  border: 2px solid var(--color-border);
  border-radius: 1.5rem;
  padding: 3rem;
  margin-bottom: 3rem;
  transition: all 0.3s ease-out;
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

.phase-card:nth-child(1) { animation-delay: 0.1s; }
.phase-card:nth-child(2) { animation-delay: 0.15s; }
.phase-card:nth-child(3) { animation-delay: 0.2s; }
.phase-card:nth-child(4) { animation-delay: 0.25s; }
.phase-card:nth-child(5) { animation-delay: 0.3s; }
.phase-card:nth-child(6) { animation-delay: 0.35s; }
.phase-card:nth-child(7) { animation-delay: 0.4s; }

.phase-card:hover {
  border-color: var(--color-accent);
  box-shadow: 0 20px 40px rgba(6, 182, 212, 0.12);
}

.phase-card.phase-featured {
  border-color: var(--color-accent);
  background: linear-gradient(135deg, rgba(207, 250, 254, 0.3) 0%, rgba(165, 243, 252, 0.1) 100%);
}

.phase-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 2px solid var(--color-border);
}

.header-left {
  display: flex;
  gap: 1.5rem;
}

.phase-icon-large {
  font-size: 3.5rem;
  line-height: 1;
  flex-shrink: 0;
}

.phase-name {
  font-family: var(--font-display);
  font-size: 2rem;
  font-weight: 700;
  color: var(--color-text);
  margin-bottom: 0.25rem;
}

.phase-index {
  font-size: 0.875rem;
  color: var(--color-text-light);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 600;
}

.phase-number {
  font-family: var(--font-display);
  font-size: 3.5rem;
  font-weight: 700;
  color: var(--color-accent-light);
  line-height: 1;
}

/* Phase Content */
.phase-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.content-block {
  padding: 1.5rem;
  background: var(--color-bg);
  border-radius: 1rem;
  border-left: 4px solid var(--color-accent);
  transition: all 0.3s ease-out;
}

.content-block:hover {
  background: var(--color-accent-light);
}

.content-block.warning {
  border-left-color: var(--color-warning);
  background: var(--color-warning-light);
}

.content-label {
  font-family: var(--font-display);
  font-size: 1rem;
  font-weight: 700;
  color: var(--color-text);
  margin-bottom: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.875rem;
}

.content-text {
  font-size: 0.95rem;
  line-height: 1.6;
  color: var(--color-text-light);
}

/* Phase Screenshot */
.phase-screenshot {
  margin-top: 2rem;
  border-radius: 1.25rem;
  overflow: hidden;
  border: 2px solid var(--color-border);
  background: white;
}

.screenshot-image {
  width: 100%;
  height: auto;
  display: block;
}

.screenshot-caption {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1.5rem;
  background: var(--color-bg);
  border-top: 2px solid var(--color-border);
}

.caption-icon {
  width: 1.5rem;
  height: 1.5rem;
  flex-shrink: 0;
  color: var(--color-accent);
  margin-top: 0.25rem;
}

.screenshot-caption p {
  font-size: 0.875rem;
  line-height: 1.6;
  color: var(--color-text-light);
  margin: 0;
}

/* Glossary Section */
.section-glossary {
  padding: 6rem 0;
  background: linear-gradient(to bottom, white, rgba(6, 182, 212, 0.02));
}

.glossary-terms {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.glossary-card {
  padding: 2rem;
  background: white;
  border: 2px solid var(--color-border);
  border-radius: 1.25rem;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

.glossary-card:nth-child(1) { animation-delay: 0.1s; }
.glossary-card:nth-child(2) { animation-delay: 0.15s; }
.glossary-card:nth-child(3) { animation-delay: 0.2s; }
.glossary-card:nth-child(4) { animation-delay: 0.25s; }

.glossary-card:hover {
  border-color: var(--color-accent);
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(6, 182, 212, 0.15);
}

.glossary-term {
  font-family: var(--font-display);
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-accent-dark);
  margin-bottom: 0.75rem;
}

.glossary-definition {
  font-size: 0.95rem;
  line-height: 1.6;
  color: var(--color-text-light);
}

/* CTA Section */
.section-cta {
  padding: 6rem 0;
  background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
  color: white;
  position: relative;
  overflow: hidden;
}

.section-cta::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 25% 50%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 75% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
  pointer-events: none;
}

.cta-title {
  font-family: var(--font-display);
  font-size: clamp(2rem, 6vw, 3rem);
  font-weight: 700;
  margin-bottom: 1rem;
  position: relative;
  z-index: 1;
  letter-spacing: -0.01em;
}

.cta-subtitle {
  font-size: 1.125rem;
  opacity: 0.9;
  margin-bottom: 2.5rem;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
  position: relative;
  z-index: 1;
  line-height: 1.6;
}

.cta-button {
  display: inline-block;
  padding: 1rem 2rem;
  background: var(--color-accent);
  color: var(--color-text);
  font-weight: 600;
  border-radius: 0.75rem;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  z-index: 1;
  font-size: 1.05rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.cta-button:hover {
  background: var(--color-accent-dark);
  transform: translateY(-2px);
  box-shadow: 0 12px 24px rgba(6, 182, 212, 0.3);
}

/* Animations */
@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .hero-section {
    padding: 4rem 0;
  }

  .hero-title {
    margin-bottom: 0.75rem;
  }

  .section-subscription,
  .section-timeline-overview,
  .section-phases,
  .section-glossary,
  .section-cta {
    padding: 4rem 0;
  }

  .section-title {
    margin-bottom: 2.5rem;
  }

  .phase-card {
    padding: 2rem;
    margin-bottom: 2rem;
  }

  .phase-header {
    flex-direction: column;
    gap: 1.5rem;
  }

  .phase-number {
    font-size: 2.5rem;
  }

  .phase-content {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .timeline-line {
    display: none;
  }

  .phases-grid {
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  }

  .phase-marker {
    width: 70px;
    height: 70px;
  }

  .marker-icon {
    font-size: 2rem;
  }
}

@media (max-width: 480px) {
  .section-title {
    font-size: 1.75rem;
    margin-bottom: 2rem;
  }

  .phase-card {
    padding: 1.5rem;
  }

  .phase-icon-large {
    font-size: 2.5rem;
  }

  .phase-name {
    font-size: 1.5rem;
  }

  .phases-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }

  .phase-marker {
    width: 60px;
    height: 60px;
  }

  .marker-icon {
    font-size: 1.75rem;
  }

  .label-name {
    font-size: 0.75rem;
  }

  .subscription-cards {
    gap: 1.5rem;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  * {
    animation: none !important;
    transition: none !important;
  }
}

a:focus-visible,
button:focus-visible {
  outline: 3px solid var(--color-accent);
  outline-offset: 2px;
}
</style>
