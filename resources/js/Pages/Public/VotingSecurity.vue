<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-50">
    <!-- Header -->
    <PublicDigitHeader />

    <main id="main-content" class="w-full">
      <!-- Hero Section -->
      <section class="w-full px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32">
        <div class="mx-auto max-w-4xl">
          <!-- Hero Content -->
          <div class="space-y-4 text-center sm:space-y-6">
            <h1
              class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-5xl lg:text-6xl"
              tabindex="-1"
            >
              🔐 {{ $t('pages.votingSecurity.hero.title') }}
            </h1>

            <p class="text-base text-gray-600 sm:text-lg md:text-xl">
              {{ $t('pages.votingSecurity.hero.subtitle') }}
            </p>
          </div>
        </div>
      </section>

      <!-- Main Content Section -->
      <section class="w-full px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8">
        <div class="mx-auto max-w-6xl">

          <!-- Architecture Explanation Section -->
          <div class="bg-gradient-to-b from-slate-50 to-slate-100 rounded-2xl p-8 sm:p-12 mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl text-gray-900 font-bold mb-4">
              {{ $t('pages.votingSecurity.architecture.fingerprint_title') }}
            </h2>
            <p class="text-base sm:text-lg text-gray-700 mb-8">
              {{ $t('pages.votingSecurity.architecture.fingerprint_subtitle') }}
            </p>

            <!-- LAYER 1: Your Device -->
            <div class="layer bg-white rounded-xl p-6 sm:p-8 mb-8 border-l-6 border-green-500 shadow-sm flex flex-col sm:flex-row gap-6 transition-transform duration-300 hover:shadow-md">
              <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                1
              </div>
              <div class="flex-grow">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer1.title') }}
                </h3>
                <p class="text-gray-700 mb-4">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer1.description') }}
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                  <p class="text-sm text-gray-700">
                    {{ $t('pages.votingSecurity.architecture.fingerprint_layer1.note') }}
                  </p>
                </div>
                <ul class="space-y-2">
                  <li v-for="item in deviceDataPoints" :key="item" class="flex items-center gap-3 text-gray-700">
                    <span class="text-lg">📱</span>
                    <span>{{ item }}</span>
                  </li>
                </ul>
              </div>
            </div>

            <!-- LAYER 2: Privacy Layer -->
            <div class="layer bg-white rounded-xl p-6 sm:p-8 mb-8 border-l-6 border-purple-500 shadow-sm flex flex-col sm:flex-row gap-6 transition-transform duration-300 hover:shadow-md">
              <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-purple-500 to-violet-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                2
              </div>
              <div class="flex-grow">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer2.title') }}
                </h3>
                <p class="text-gray-700 mb-4">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer2.description') }}
                </p>

                <!-- Coffee Bean Analogy -->
                <div class="bg-purple-50 border-l-4 border-purple-500 p-6 rounded-lg mb-6">
                  <p class="font-semibold text-gray-900 mb-3">
                    {{ $t('pages.votingSecurity.architecture.fingerprint_layer2.one_way_explanation') }}
                  </p>
                  <p class="text-sm text-gray-700 mb-4">
                    {{ $t('pages.votingSecurity.architecture.fingerprint_layer2.analogy') }}
                  </p>
                  <div class="space-y-2 text-sm font-mono bg-white p-3 rounded border border-purple-200">
                    <div class="text-gray-800">{{ $t('pages.votingSecurity.architecture.fingerprint_layer2.analogy_forward') }}</div>
                    <div class="text-red-600">{{ $t('pages.votingSecurity.architecture.fingerprint_layer2.analogy_reverse') }}</div>
                  </div>
                </div>

                <!-- Equation Box -->
                <div class="bg-gray-900 text-white p-6 rounded-lg font-mono text-sm overflow-x-auto mb-6">
                  <pre class="whitespace-pre-wrap">{{ $t('pages.votingSecurity.architecture.fingerprint_layer2.equation') }}</pre>
                </div>

                <!-- Privacy Lock Badge -->
                <div class="flex items-center justify-center py-4 my-6">
                  <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-3 rounded-full text-white font-semibold flex items-center gap-2 shadow-lg">
                    <span class="text-2xl">🔒</span>
                    <span>{{ $t('pages.votingSecurity.architecture.fingerprint_layer2.privacy_lock') }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- CENTERPIECE: Architecture Diagram PNG -->
            <div class="diagram-container my-16 text-center bg-white rounded-2xl p-8 shadow-lg">
              <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">
                {{ $t('pages.votingSecurity.architecture.fingerprint_diagram_title') }}
              </h3>
              <div class="rounded-xl overflow-hidden">
                <img
                  src="/images/security/device_finger_printing_architecture.png"
                  :alt="$t('pages.votingSecurity.architecture.fingerprint_diagram_alt')"
                  class="w-full h-auto"
                >
              </div>
              <p class="mt-6 text-gray-600 italic text-sm sm:text-base max-w-2xl mx-auto">
                {{ $t('pages.votingSecurity.architecture.fingerprint_diagram_caption') }}
              </p>
            </div>

            <!-- LAYER 3: Storage Layer -->
            <div class="layer bg-white rounded-xl p-6 sm:p-8 mb-8 border-l-6 border-red-500 shadow-sm flex flex-col sm:flex-row gap-6 transition-transform duration-300 hover:shadow-md">
              <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-red-500 to-rose-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                3
              </div>
              <div class="flex-grow">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer3.title') }}
                </h3>
                <p class="text-gray-700 mb-6">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer3.description') }}
                </p>

                <!-- Storage Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Stored Items -->
                  <div class="storage-item bg-green-50 rounded-lg p-6 border-2 border-green-200 hover:bg-opacity-75">
                    <h4 class="text-lg font-semibold text-green-700 mb-4">
                      {{ $t('pages.votingSecurity.architecture.fingerprint_layer3.stored_title') }}
                    </h4>
                    <ul class="space-y-3">
                      <li v-for="(item, key) in storedItems" :key="key" class="flex gap-3 text-sm">
                        <span class="text-green-600 font-bold flex-shrink-0 text-lg">✓</span>
                        <span class="text-gray-700">
                          <strong>{{ $t(`pages.votingSecurity.architecture.fingerprint_layer3.stored.${key}`) }}</strong>
                        </span>
                      </li>
                    </ul>
                  </div>

                  <!-- Not Stored Items -->
                  <div class="storage-item bg-red-50 rounded-lg p-6 border-2 border-red-200 hover:bg-opacity-75">
                    <h4 class="text-lg font-semibold text-red-700 mb-4">
                      {{ $t('pages.votingSecurity.architecture.fingerprint_layer3.not_stored_title') }}
                    </h4>
                    <ul class="space-y-3">
                      <li v-for="(item, key) in notStoredItems" :key="key" class="flex gap-3 text-sm">
                        <span class="text-red-600 font-bold flex-shrink-0 text-lg">✗</span>
                        <span class="text-gray-700">
                          {{ $t(`pages.votingSecurity.architecture.fingerprint_layer3.not_stored.${key}`) }}
                        </span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <!-- LAYER 4: Verification Layer -->
            <div class="layer bg-white rounded-xl p-6 sm:p-8 mb-8 border-l-6 border-blue-500 shadow-sm flex flex-col sm:flex-row gap-6 transition-transform duration-300 hover:shadow-md">
              <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                4
              </div>
              <div class="flex-grow">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer4.title') }}
                </h3>
                <p class="text-gray-700 mb-6">
                  {{ $t('pages.votingSecurity.architecture.fingerprint_layer4.description') }}
                </p>

                <!-- Verification Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                  <div v-for="item in verificationItems" :key="item.title" class="verification-item bg-blue-50 rounded-lg p-4 text-center border border-blue-200 hover:-translate-y-1">
                    <div class="text-3xl mb-2">{{ item.icon }}</div>
                    <h5 class="font-semibold text-gray-900 text-sm mb-1">{{ item.title }}</h5>
                    <p class="text-xs text-gray-600">{{ item.description }}</p>
                  </div>
                </div>

                <!-- Key Message -->
                <div class="bg-gradient-to-r from-indigo-100 to-purple-100 border-2 border-indigo-300 rounded-lg p-6 text-center">
                  <p class="text-gray-900 font-semibold text-lg">
                    {{ $t('pages.votingSecurity.architecture.fingerprint_layer4.key_message') }}
                  </p>
                </div>
              </div>
            </div>
          </div>

        <!-- Three Pillars -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 my-12">
          <div v-for="(pillarKey, index) in pillarKeys" :key="pillarKey" class="bg-white rounded-2xl p-6 sm:p-8 text-center hover:shadow-lg transition-shadow duration-300 shadow-sm">
            <div class="text-4xl mb-4">{{ getPillarIcon(index) }}</div>
            <h3 class="text-gray-800 mb-3 text-lg sm:text-xl font-semibold">
              {{ $t(`pages.votingSecurity.pillars.${pillarKey}.title`) }}
            </h3>
            <p class="text-gray-600 text-sm sm:text-base">
              {{ $t(`pages.votingSecurity.pillars.${pillarKey}.description`) }}
            </p>
          </div>
        </div>

        <!-- Data Flow Explanation -->
        <div class="bg-white rounded-2xl p-8 my-12 border-2 border-dashed border-indigo-200 shadow-sm">
          <h3 class="text-gray-800 mb-4 text-xl sm:text-2xl font-semibold">
            📊 {{ $t('pages.votingSecurity.data_store.title') }}
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white p-5 sm:p-6 rounded-xl">
              <h4 class="text-green-600 mb-4 font-semibold text-base sm:text-lg">✅ {{ $t('pages.votingSecurity.data_store.stored_title') }}</h4>
              <ul class="list-none p-0">
                <li v-for="item in storedItems" :key="item" class="mb-3">
                  • 🔒 <strong>{{ $t(`pages.votingSecurity.data_store.stored.${item}`) }}</strong>
                </li>
              </ul>
            </div>

            <div class="bg-white p-5 sm:p-6 rounded-xl">
              <h4 class="text-red-600 mb-4 font-semibold text-base sm:text-lg">❌ {{ $t('pages.votingSecurity.data_store.not_stored_title') }}</h4>
              <ul class="list-none p-0">
                <li v-for="item in notStoredItems" :key="item" class="mb-3">
                  • ❌ <strong>{{ $t(`pages.votingSecurity.data_store.not_stored.${item}`) }}</strong>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Verification Methods -->
        <h2 class="text-2xl sm:text-3xl text-gray-700 mb-6 border-b-4 border-indigo-600 pb-2 inline-block mt-8">
          🔍 {{ $t('pages.votingSecurity.verification.title') }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-12">
          <!-- Receipt Card -->
          <div class="bg-white border-2 border-green-500 rounded-2xl p-6 sm:p-8">
            <div class="inline-block px-3 sm:px-4 py-2 rounded-full font-semibold text-xs sm:text-sm mb-4 bg-green-100 text-green-900">
              🔐 {{ $t('pages.votingSecurity.verification.receipt.badge') }}
            </div>
            <h3 class="text-green-900 mb-4 text-lg sm:text-xl">
              {{ $t('pages.votingSecurity.verification.receipt.title') }}
            </h3>
            <p class="mb-6 text-sm sm:text-base">
              {{ $t('pages.votingSecurity.verification.receipt.description') }}
            </p>
            <div class="bg-green-50 p-4 rounded-lg">
              <code class="text-green-900 text-xs sm:text-sm">{{ $t('pages.votingSecurity.verification.receipt.code_example') }}</code>
            </div>
          </div>

          <!-- Auditor Card -->
          <div class="bg-white border-2 border-amber-500 rounded-2xl p-6 sm:p-8">
            <div class="inline-block px-3 sm:px-4 py-2 rounded-full font-semibold text-xs sm:text-sm mb-4 bg-amber-100 text-amber-900">
              ⚖️ {{ $t('pages.votingSecurity.verification.auditor.badge') }}
            </div>
            <h3 class="text-amber-900 mb-4 text-lg sm:text-xl">
              {{ $t('pages.votingSecurity.verification.auditor.title') }}
            </h3>
            <p class="mb-6 text-sm sm:text-base">
              {{ $t('pages.votingSecurity.verification.auditor.description') }}
            </p>
            <div class="bg-amber-50 p-4 rounded-lg">
              <code class="text-amber-900 text-xs sm:text-sm">{{ $t('pages.votingSecurity.verification.auditor.code_example') }}</code>
            </div>
          </div>
        </div>

        <!-- Device Fingerprinting Deep Dive -->
        <h2 class="text-2xl sm:text-3xl text-gray-700 mb-6 border-b-4 border-indigo-600 pb-2 inline-block mt-8">
          📱 {{ $t('pages.votingSecurity.fingerprint.title') }}
        </h2>

        <div class="bg-white rounded-2xl p-8 my-8 shadow-sm">
          <h3 class="text-gray-800 mb-3 text-lg sm:text-xl font-semibold">{{ $t('pages.votingSecurity.fingerprint.why_title') }}</h3>
          <p class="mb-6">{{ $t('pages.votingSecurity.fingerprint.why_intro') }}</p>

          <ul class="list-none p-0">
            <li v-for="(reason, index) in fingerprintReasons" :key="index" class="mb-3 pl-8 relative">
              <span class="absolute left-0">{{ reason.emoji }}</span>
              <strong>{{ reason.title }}</strong> - {{ reason.description }}
            </li>
          </ul>

          <h3 class="text-gray-800 my-6 text-lg sm:text-xl font-semibold">{{ $t('pages.votingSecurity.fingerprint.how_title') }}</h3>

          <div class="flex flex-wrap gap-4 justify-between items-stretch">
            <div v-for="step in 4" :key="step" class="flex-1 min-w-32 text-center p-4 sm:p-6 bg-white rounded-xl shadow-sm">
              <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center mx-auto mb-3 font-bold">
                {{ step }}
              </div>
              <p class="text-sm sm:text-base">{{ $t(`pages.votingSecurity.fingerprint.steps.step${step}`) }}</p>
              <small class="text-gray-500 text-xs sm:text-sm">{{ $t(`pages.votingSecurity.fingerprint.steps.step${step}_detail`) }}</small>
            </div>
          </div>

          <p class="mt-6 italic text-gray-600 text-sm sm:text-base">
            🔒 {{ $t('pages.votingSecurity.fingerprint.how_note') }}
          </p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 my-12 text-center">
          <div class="p-6 sm:p-8 bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-2xl">
            <div class="text-4xl sm:text-5xl font-bold mb-2">100%</div>
            <div class="text-base sm:text-lg opacity-95">{{ $t('pages.votingSecurity.stats.anonymous') }}</div>
          </div>
          <div class="p-6 sm:p-8 bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-2xl">
            <div class="text-4xl sm:text-5xl font-bold mb-2">🔐</div>
            <div class="text-base sm:text-lg opacity-95">{{ $t('pages.votingSecurity.stats.no_user_id') }}</div>
          </div>
          <div class="p-6 sm:p-8 bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-2xl">
            <div class="text-4xl sm:text-5xl font-bold mb-2">🛡️</div>
            <div class="text-base sm:text-lg opacity-95">{{ $t('pages.votingSecurity.stats.fraud_detection') }}</div>
          </div>
        </div>

        <!-- FAQ -->
        <h2 class="text-2xl sm:text-3xl text-gray-700 mb-6 border-b-4 border-indigo-600 pb-2 inline-block mt-8">
          ❓ {{ $t('pages.votingSecurity.faq_title') }}
        </h2>

        <div class="my-12">
          <div v-for="(item, index) in 5" :key="index" class="border-b border-gray-200 py-4 sm:py-6">
            <div class="font-semibold text-gray-800 mb-2 text-base sm:text-lg">
              Q: {{ $t(`pages.votingSecurity.faq.question${index + 1}.question`) }}
            </div>
            <div class="text-gray-600 pl-4 border-l-4 border-indigo-600 text-sm sm:text-base">
              A: {{ $t(`pages.votingSecurity.faq.question${index + 1}.answer`) }}
            </div>
          </div>
        </div>

        <!-- Trust Badges -->
        <div class="flex flex-wrap justify-center gap-4 sm:gap-6 my-12">
          <div v-for="(badge, index) in badges" :key="index" class="text-center p-4 sm:p-6 bg-white rounded-xl min-w-32 sm:min-w-40">
            <div class="text-3xl sm:text-4xl mb-2">{{ badge.emoji }}</div>
            <div class="text-xs sm:text-sm font-medium">{{ badge.label }}</div>
          </div>
        </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<style scoped>
/* Layer entrance animations */
@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Apply animations to layers */
.layer {
  animation: slideInUp 0.6s ease-out backwards;
}

.layer:nth-child(1) {
  animation-delay: 0.1s;
}

.layer:nth-child(2) {
  animation-delay: 0.2s;
}

.layer:nth-child(3) {
  animation-delay: 0.3s;
}

.layer:nth-child(4) {
  animation-delay: 0.4s;
}

.layer:nth-child(5) {
  animation-delay: 0.5s;
}

/* Diagram container animation */
.diagram-container {
  animation: fadeIn 0.8s ease-out 0.3s backwards;
}

/* Hover effects for verification items */
.verification-item {
  transition: all 0.3s ease;
}

.verification-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

/* Hover effect for storage items */
.storage-item {
  transition: background-color 0.3s ease;
}

.storage-item:hover {
  background-color: rgba(0, 0, 0, 0.02);
}

/* Gradient text for privacy lock badge */
.privacy-lock {
  background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
}

/* Code block styling */
.equation-box {
  background-color: #1f2937;
  color: #f3f4f6;
  overflow-x: auto;
  border-radius: 0.5rem;
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: background-color, border-color, color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Mobile responsive adjustments */
@media (max-width: 640px) {
  .layer {
    flex-direction: column;
  }

  .layer-number {
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
  }

  .verification-grid {
    gap: 0.75rem;
  }

  .storage-grid {
    grid-template-columns: 1fr;
  }
}

/* Animation for layer number circles */
.layer-number {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Focus states for accessibility */
button:focus, a:focus {
  outline: 2px solid #667eea;
  outline-offset: 2px;
}

/* Print styles for accessibility */
@media print {
  .diagram-container {
    page-break-inside: avoid;
  }
}
</style>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
import { useMeta } from '@/composables/useMeta'
import { useI18n } from 'vue-i18n'

// Initialize SEO meta tags
useMeta({ pageKey: 'votingSecurity' })

// Initialize i18n
const { $t } = useI18n()

// Inject Schema.org structured data
onMounted(() => {
  const schemaData = {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Sichere Wahl durchführen - Fünf-Schicht-Sicherheit für Online-Abstimmungen",
    "description": "Sichere Wahl durchführen mit Public Digit. Fünf-Schicht-Sicherheitsarchitektur garantiert vollständige Anonymität, kryptographische Verifizierung und Multi-Tenant-Isolation.",
    "url": window.location.href,
    "mainEntity": {
      "@type": "SoftwareApplication",
      "name": "Public Digit",
      "description": "Sichere digitale Wahlplattform für Organisationen",
      "applicationCategory": "Voting Software",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "EUR",
        "price": "0",
        "description": "Sichere Online-Wahlen für Organisationen"
      },
      "operatingSystem": "Web-based"
    },
    "author": {
      "@type": "Organization",
      "name": "Public Digit",
      "url": "https://publicdigit.com"
    }
  }

  // Create and inject schema script tag
  const existingSchema = document.querySelector('script[type="application/ld+json"]')
  if (existingSchema) existingSchema.remove()

  const schemaScript = document.createElement('script')
  schemaScript.type = 'application/ld+json'
  schemaScript.textContent = JSON.stringify(schemaData)
  document.head.appendChild(schemaScript)
})

const pillarKeys = ref(['anonymity', 'verification', 'isolation'])

// Device data points for Layer 1 - Computed from i18n
const deviceDataPoints = computed(() => [
  $t('pages.votingSecurity.architecture.device_data.ip'),
  $t('pages.votingSecurity.architecture.device_data.user_agent'),
  $t('pages.votingSecurity.architecture.device_data.screen'),
  $t('pages.votingSecurity.architecture.device_data.timezone'),
  $t('pages.votingSecurity.architecture.device_data.language'),
  $t('pages.votingSecurity.architecture.device_data.platform'),
])

// Storage items for Layer 3 (stored)
const storedItems = computed(() => ({
  fingerprint: true,
  metadata: true,
}))

// Storage items for Layer 3 (not stored)
const notStoredItems = computed(() => ({
  raw_ip: true,
  exact_location: true,
  user_id: true,
  raw_browser: true,
  mac_address: true,
}))

// Verification items for Layer 4
const verificationItems = computed(() => [
  {
    icon: '🔄',
    title: $t('pages.votingSecurity.architecture.fingerprint_layer4.same_device'),
    description: $t('pages.votingSecurity.architecture.fingerprint_layer4.same_device_desc'),
  },
  {
    icon: '📊',
    title: $t('pages.votingSecurity.architecture.fingerprint_layer4.vote_limits'),
    description: $t('pages.votingSecurity.architecture.fingerprint_layer4.vote_limits_desc'),
  },
  {
    icon: '🚨',
    title: $t('pages.votingSecurity.architecture.fingerprint_layer4.anomaly'),
    description: $t('pages.votingSecurity.architecture.fingerprint_layer4.anomaly_desc'),
  },
  {
    icon: '📋',
    title: $t('pages.votingSecurity.architecture.fingerprint_layer4.audit'),
    description: $t('pages.votingSecurity.architecture.fingerprint_layer4.audit_desc'),
  },
])

const fingerprintReasons = ref([
  { emoji: '🛡️', title: 'Multiple votes from the same device', description: 'Stops ballot stuffing' },
  { emoji: '🤖', title: 'Automated voting bots', description: 'Ensures real human voters' },
  { emoji: '🔍', title: 'Suspicious patterns', description: 'Protects election integrity' },
])

const badges = ref([
  { emoji: '🔒', label: 'GDPR Compliant' },
  { emoji: '🛡️', label: 'Privacy by Design' },
  { emoji: '🔐', label: 'End-to-End Encryption' },
  { emoji: '🇪🇺', label: 'EU Data Protection' },
])

const getPillarIcon = (index: number) => {
  const icons = ['🔒', '📱', '🔑']
  return icons[index] || '🛡️'
}
</script>
