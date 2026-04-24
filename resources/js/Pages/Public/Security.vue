<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    <!-- Header -->
    <PublicDigitHeader />

    <!-- Main Content with Skip Link Support -->
    <main id="main-content" class="w-full">
      <!-- Hero Section - Mobile First -->
      <section class="w-full px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32">
        <div class="mx-auto max-w-4xl">
          <!-- CEO Tags - Accessible Badge Design -->
          <div class="mb-8 flex flex-wrap justify-center gap-2 sm:gap-3">
            <span
              class="inline-flex items-center rounded-full bg-blue-100 px-3 py-2 text-xs font-semibold text-blue-700 sm:px-4 sm:py-2 sm:text-sm"
              aria-label="Secure Online Voting certification badge"
            >
              🔐 Secure Online Voting
            </span>
            <span
              class="inline-flex items-center rounded-full bg-green-100 px-3 py-2 text-xs font-semibold text-green-700 sm:px-4 sm:py-2 sm:text-sm"
              aria-label="Sichere online wahlen für Vereine certification"
            >
              ✅ Sichere online wahlen für Vereine
            </span>
          </div>

          <!-- Hero Content -->
          <div class="space-y-4 text-center sm:space-y-6">
            <h1
              class="text-3xl font-bold leading-tight text-gray-900 sm:text-4xl md:text-5xl lg:text-6xl"
              tabindex="-1"
            >
              {{ $t('pages.security.hero.title') }}
            </h1>

            <p class="text-base text-gray-600 sm:text-lg md:text-xl">
              {{ $t('pages.security.hero.subtitle') }}
            </p>

            <p class="text-base font-semibold text-blue-600 sm:text-lg md:text-xl">
              {{ $t('pages.security.hero.promise') }}
            </p>

            <!-- CTA Buttons - Touch Friendly -->
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center sm:gap-4">
              <a
                v-for="button in ctaButtons"
                :key="button.id"
                :href="button.href"
                :class="[
                  'inline-flex items-center justify-center rounded-lg px-6 py-3 font-semibold transition duration-200 sm:px-8 sm:py-3',
                  'focus:outline-none focus:ring-2 focus:ring-offset-2',
                  button.variant === 'primary'
                    ? 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 active:bg-blue-800'
                    : 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-400 active:bg-gray-400',
                ]"
                :aria-label="`${button.label} - ${button.href}`"
              >
                {{ button.label }}
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Five-Layer Architecture Section -->
      <section
        class="w-full bg-blue-50 px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32"
        aria-labelledby="security-architecture-heading"
      >
        <div class="mx-auto max-w-6xl">
          <!-- Section Header -->
          <div class="mb-8 text-center sm:mb-12 md:mb-16">
            <h2
              id="security-architecture-heading"
              class="text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl"
            >
              {{ $t('pages.security.architecture.title') || 'Security Architecture' }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 sm:mt-4 sm:text-base">
              {{ $t('pages.security.architecture.subtitle') || 'Five independent layers protect every vote' }}
            </p>
          </div>

          <!-- Architecture Image - Responsive with Loading State -->
          <div class="mb-8 sm:mb-12 md:mb-16">
            <figure class="overflow-hidden rounded-lg shadow-lg">
              <img
                :src="images.layerArchitecture"
                :alt="$t('pages.security.architecture.image_alt') || 'Five-layer security architecture diagram showing request validation, temporal validation, tenant isolation, business logic, and data persistence layers'"
                class="h-auto w-full object-contain"
                loading="lazy"
              />
              <figcaption class="sr-only">
                {{ $t('pages.security.architecture.image_alt') || 'Five-layer security architecture diagram' }}
              </figcaption>
            </figure>
          </div>

          <!-- Layer Cards - Mobile First Grid -->
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:gap-3">
            <!-- Layer 1: Request Validation -->
            <article
              class="flex flex-col overflow-hidden rounded-lg bg-white p-4 shadow transition duration-200 hover:shadow-lg sm:p-5 md:p-6"
              aria-labelledby="layer-1-title"
            >
              <div
                class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-100"
                aria-hidden="true"
              >
                <span class="text-lg font-bold text-blue-600">1</span>
              </div>
              <h3 id="layer-1-title" class="mb-2 text-base font-semibold text-gray-900 sm:text-lg">
                {{ $t(`pages.security.layers.layer1.title`) }}
              </h3>
              <p class="mb-3 text-xs text-gray-600 sm:text-sm md:mb-4">
                {{ $t(`pages.security.layers.layer1.description`) }}
              </p>
              <!-- Customer-Friendly Explanation -->
              <div class="border-t border-blue-200 pt-3 sm:pt-4">
                <p class="text-xs font-medium text-blue-700">
                  <span aria-hidden="true">🔍</span>
                  <strong>{{ $t('pages.security.what_means') || 'What this means:' }}</strong>
                  {{ $t('pages.security.layers.layer1.explanation') || 'We check that your voting link is real, belongs to you, and hasn\'t been stolen or deactivated.' }}
                </p>
              </div>
            </article>

            <!-- Layer 2: Temporal Validation -->
            <article
              class="flex flex-col overflow-hidden rounded-lg bg-white p-4 shadow transition duration-200 hover:shadow-lg sm:p-5 md:p-6"
              aria-labelledby="layer-2-title"
            >
              <div
                class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-100"
                aria-hidden="true"
              >
                <span class="text-lg font-bold text-green-600">2</span>
              </div>
              <h3 id="layer-2-title" class="mb-2 text-base font-semibold text-gray-900 sm:text-lg">
                {{ $t(`pages.security.layers.layer2.title`) }}
              </h3>
              <p class="mb-3 text-xs text-gray-600 sm:text-sm md:mb-4">
                {{ $t(`pages.security.layers.layer2.description`) }}
              </p>
              <!-- Customer-Friendly Explanation -->
              <div class="border-t border-green-200 pt-3 sm:pt-4">
                <p class="text-xs font-medium text-green-700">
                  <span aria-hidden="true">⏰</span>
                  <strong>{{ $t('pages.security.what_means') || 'What this means:' }}</strong>
                  {{ $t('pages.security.layers.layer2.explanation') || 'Your voting session automatically expires after 24 hours, and votes are only accepted when the election is officially open.' }}
                </p>
              </div>
            </article>

            <!-- Layer 3: Golden Rule (Tenant Isolation) -->
            <article
              class="flex flex-col overflow-hidden rounded-lg bg-white p-4 shadow transition duration-200 hover:shadow-lg sm:p-5 md:p-6"
              aria-labelledby="layer-3-title"
            >
              <div
                class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-purple-100"
                aria-hidden="true"
              >
                <span class="text-lg font-bold text-purple-600">3</span>
              </div>
              <h3 id="layer-3-title" class="mb-2 text-base font-semibold text-gray-900 sm:text-lg">
                {{ $t(`pages.security.layers.layer3.title`) }}
              </h3>
              <p class="mb-3 text-xs text-gray-600 sm:text-sm md:mb-4">
                {{ $t(`pages.security.layers.layer3.description`) }}
              </p>
              <!-- Customer-Friendly Explanation -->
              <div class="border-t border-purple-200 pt-3 sm:pt-4">
                <p class="text-xs font-medium text-purple-700">
                  <span aria-hidden="true">🏢</span>
                  <strong>{{ $t('pages.security.what_means') || 'What this means:' }}</strong>
                  {{ $t('pages.security.layers.layer3.explanation') || 'You can only vote in elections from your own organization. Other organizations\' elections are completely invisible to you.' }}
                </p>
              </div>
            </article>

            <!-- Layer 4: Business Logic -->
            <article
              class="flex flex-col overflow-hidden rounded-lg bg-white p-4 shadow transition duration-200 hover:shadow-lg sm:p-5 md:p-6"
              aria-labelledby="layer-4-title"
            >
              <div
                class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-amber-100"
                aria-hidden="true"
              >
                <span class="text-lg font-bold text-amber-600">4</span>
              </div>
              <h3 id="layer-4-title" class="mb-2 text-base font-semibold text-gray-900 sm:text-lg">
                {{ $t(`pages.security.layers.layer4.title`) }}
              </h3>
              <p class="mb-3 text-xs text-gray-600 sm:text-sm md:mb-4">
                {{ $t(`pages.security.layers.layer4.description`) }}
              </p>
              <!-- Customer-Friendly Explanation -->
              <div class="border-t border-amber-200 pt-3 sm:pt-4">
                <p class="text-xs font-medium text-amber-700">
                  <span aria-hidden="true">✅</span>
                  <strong>{{ $t('pages.security.what_means') || 'What this means:' }}</strong>
                  {{ $t('pages.security.layers.layer4.explanation') || 'The system ensures you can only vote once, your code is valid, and you\'re selecting real candidates.' }}
                </p>
              </div>
            </article>

            <!-- Layer 5: Anonymity & Cryptography -->
            <article
              class="flex flex-col overflow-hidden rounded-lg bg-white p-4 shadow transition duration-200 hover:shadow-lg sm:p-5 md:p-6"
              aria-labelledby="layer-5-title"
            >
              <div
                class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-100"
                aria-hidden="true"
              >
                <span class="text-lg font-bold text-red-600">5</span>
              </div>
              <h3 id="layer-5-title" class="mb-2 text-base font-semibold text-gray-900 sm:text-lg">
                {{ $t(`pages.security.layers.layer5.title`) }}
              </h3>
              <p class="mb-3 text-xs text-gray-600 sm:text-sm md:mb-4">
                {{ $t(`pages.security.layers.layer5.description`) }}
              </p>
              <!-- Customer-Friendly Explanation -->
              <div class="border-t border-red-200 pt-3 sm:pt-4">
                <p class="text-xs font-medium text-red-700">
                  <span aria-hidden="true">🔒</span>
                  <strong>{{ $t('pages.security.what_means') || 'What this means:' }}</strong>
                  {{ $t('pages.security.layers.layer5.explanation') || 'We literally cannot see how you voted. Your vote is stored with no connection to your identity, but you can still verify it was counted.' }}
                </p>
              </div>
            </article>
          </div>

          <!-- Summary Statement - Accessible Info Box -->
          <div class="mt-8 overflow-hidden rounded-lg bg-white p-5 shadow sm:mt-12 md:mt-16 md:p-8">
            <div class="max-w-3xl">
              <p class="text-sm text-gray-700 sm:text-base md:text-lg">
                <span class="font-bold text-blue-600">{{ $t('pages.security.bottom_line') || 'The bottom line:' }}</span>
                {{ $t('pages.security.summary') || 'Five layers of security mean your vote is protected at every step. Even if one layer were compromised, the other four still protect you.' }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Security Pillars Section - Mobile First Grid -->
      <section
        class="w-full px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32"
        aria-labelledby="security-pillars-heading"
      >
        <div class="mx-auto max-w-6xl">
          <h2
            id="security-pillars-heading"
            class="mb-8 text-center text-2xl font-bold text-gray-900 sm:mb-12 sm:text-3xl md:mb-16 md:text-4xl"
          >
            {{ $t('pages.security.pillars_title') || 'Security Pillars' }}
          </h2>

          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
            <!-- Anonymity Pillar -->
            <article
              class="overflow-hidden rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 p-6 sm:p-8"
              aria-labelledby="pillar-anonymity-title"
            >
              <div class="mb-6 flex justify-center">
                <div
                  class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-blue-600 sm:h-20 sm:w-20"
                  aria-hidden="true"
                >
                  <span class="text-2xl sm:text-4xl">🔒</span>
                </div>
              </div>
              <h3 id="pillar-anonymity-title" class="mb-4 text-center text-lg font-bold text-gray-900 sm:text-xl">
                {{ $t(`pages.security.pillars.anonymity.title`) }}
              </h3>
              <p class="mb-4 text-center text-sm text-gray-700 sm:text-base">
                {{ $t(`pages.security.pillars.anonymity.description`) }}
              </p>
              <p class="text-center text-xs font-medium text-blue-700 sm:text-sm">
                ✅ {{ $t('pages.security.pillars.anonymity.tagline') || 'Even we don\'t know how you voted' }}
              </p>
            </article>

            <!-- Verification Pillar -->
            <article
              class="overflow-hidden rounded-lg bg-gradient-to-br from-green-50 to-green-100 p-6 sm:p-8"
              aria-labelledby="pillar-verification-title"
            >
              <div class="mb-6 flex justify-center">
                <div
                  class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-green-600 sm:h-20 sm:w-20"
                  aria-hidden="true"
                >
                  <span class="text-2xl sm:text-4xl">✅</span>
                </div>
              </div>
              <h3 id="pillar-verification-title" class="mb-4 text-center text-lg font-bold text-gray-900 sm:text-xl">
                {{ $t(`pages.security.pillars.verification.title`) }}
              </h3>
              <p class="mb-4 text-center text-sm text-gray-700 sm:text-base">
                {{ $t(`pages.security.pillars.verification.description`) }}
              </p>
              <p class="text-center text-xs font-medium text-green-700 sm:text-sm">
                🔍 {{ $t('pages.security.pillars.verification.tagline') || 'You can verify without revealing your vote' }}
              </p>
            </article>

            <!-- Isolation Pillar -->
            <article
              class="overflow-hidden rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 p-6 sm:p-8"
              aria-labelledby="pillar-isolation-title"
            >
              <div class="mb-6 flex justify-center">
                <div
                  class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-purple-600 sm:h-20 sm:w-20"
                  aria-hidden="true"
                >
                  <span class="text-2xl sm:text-4xl">🔐</span>
                </div>
              </div>
              <h3 id="pillar-isolation-title" class="mb-4 text-center text-lg font-bold text-gray-900 sm:text-xl">
                {{ $t(`pages.security.pillars.isolation.title`) }}
              </h3>
              <p class="mb-4 text-center text-sm text-gray-700 sm:text-base">
                {{ $t(`pages.security.pillars.isolation.description`) }}
              </p>
              <p class="text-center text-xs font-medium text-purple-700 sm:text-sm">
                🏢 {{ $t('pages.security.pillars.isolation.tagline') || 'Your data stays with your organization' }}
              </p>
            </article>
          </div>
        </div>
      </section>

      <!-- Election State Machine Section - New -->
      <section
        class="w-full bg-gradient-to-b from-indigo-50 via-blue-50 to-indigo-50 px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32 border-t-4 border-b-4 border-indigo-200"
        aria-labelledby="state-machine-heading"
      >
        <div class="mx-auto max-w-6xl">
          <!-- Section Header -->
          <div class="mb-8 text-center sm:mb-12 md:mb-16">
            <div class="mb-6 inline-flex items-center rounded-full bg-indigo-100 px-4 py-2">
              <span class="text-sm font-semibold text-indigo-700">🔐 Advanced Security Architecture</span>
            </div>
            <h2
              id="state-machine-heading"
              class="text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl"
            >
              Election State Machine
            </h2>
            <p class="mt-4 text-base text-gray-600 sm:text-lg max-w-2xl mx-auto">
              Behind every secure election is a tamper-proof state machine that enforces a rigid progression through five distinct phases, preventing manipulation and ensuring complete verifiability.
            </p>
          </div>

          <!-- State Machine Flow - Visual Diagram Alternative -->
          <div class="mb-12 rounded-xl bg-white shadow-lg overflow-hidden">
            <div class="p-6 sm:p-8">
              <div class="grid grid-cols-1 md:grid-cols-5 gap-2 md:gap-0">
                <!-- Phase 1 -->
                <div class="flex flex-col items-center relative">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                    1
                  </div>
                  <p class="mt-3 text-xs sm:text-sm font-semibold text-gray-900 text-center">
                    Administration
                  </p>
                  <p class="text-xs text-gray-600 text-center">Setup</p>
                  <div v-if="locale === 'en'" class="absolute -right-3 md:right-1/2 md:translate-x-1/2 top-1/4 hidden md:block">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </div>
                </div>

                <!-- Phase 2 -->
                <div class="flex flex-col items-center relative">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-green-400 to-green-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                    2
                  </div>
                  <p class="mt-3 text-xs sm:text-sm font-semibold text-gray-900 text-center">
                    Nomination
                  </p>
                  <p class="text-xs text-gray-600 text-center">Candidates</p>
                  <div v-if="locale === 'en'" class="absolute -right-3 md:right-1/2 md:translate-x-1/2 top-1/4 hidden md:block">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </div>
                </div>

                <!-- Phase 3 -->
                <div class="flex flex-col items-center relative">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                    3
                  </div>
                  <p class="mt-3 text-xs sm:text-sm font-semibold text-gray-900 text-center">
                    Voting
                  </p>
                  <p class="text-xs text-gray-600 text-center">Secure</p>
                  <div v-if="locale === 'en'" class="absolute -right-3 md:right-1/2 md:translate-x-1/2 top-1/4 hidden md:block">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </div>
                </div>

                <!-- Phase 4 -->
                <div class="flex flex-col items-center relative">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                    4
                  </div>
                  <p class="mt-3 text-xs sm:text-sm font-semibold text-gray-900 text-center">
                    Counting
                  </p>
                  <p class="text-xs text-gray-600 text-center">Verify</p>
                  <div v-if="locale === 'en'" class="absolute -right-3 md:right-1/2 md:translate-x-1/2 top-1/4 hidden md:block">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </div>
                </div>

                <!-- Phase 5 -->
                <div class="flex flex-col items-center">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-red-400 to-red-500 flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg">
                    5
                  </div>
                  <p class="mt-3 text-xs sm:text-sm font-semibold text-gray-900 text-center">
                    Results
                  </p>
                  <p class="text-xs text-gray-600 text-center">Published</p>
                </div>
              </div>

              <!-- Locked Transition Info -->
              <div class="mt-8 pt-6 border-t border-indigo-100">
                <p class="text-xs sm:text-sm text-gray-600 text-center">
                  <span class="font-semibold text-indigo-700">✓ One-way progression:</span> Each phase locks automatically. No phase can be skipped or reversed.
                </p>
              </div>
            </div>
          </div>

          <!-- State Machine Benefits - 3 Cards -->
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Immutable Audit Trail -->
            <article class="overflow-hidden rounded-lg bg-white p-6 sm:p-8 shadow hover:shadow-lg transition duration-200">
              <div class="mb-4 flex justify-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-indigo-100">
                  <span class="text-2xl">📜</span>
                </div>
              </div>
              <h3 class="mb-3 text-center text-lg font-bold text-gray-900 sm:text-xl">
                Immutable Audit Trail
              </h3>
              <p class="text-center text-sm text-gray-700 sm:text-base">
                Every state transition is recorded cryptographically and cannot be altered, modified, or deleted — even by system administrators.
              </p>
            </article>

            <!-- Tamper-Proof Verification -->
            <article class="overflow-hidden rounded-lg bg-white p-6 sm:p-8 shadow hover:shadow-lg transition duration-200">
              <div class="mb-4 flex justify-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-green-100">
                  <span class="text-2xl">🔒</span>
                </div>
              </div>
              <h3 class="mb-3 text-center text-lg font-bold text-gray-900 sm:text-xl">
                Tamper-Proof Design
              </h3>
              <p class="text-center text-sm text-gray-700 sm:text-base">
                Cryptographic hashes detect any attempt to tamper with votes or election state. Mathematical verification proves integrity without revealing details.
              </p>
            </article>

            <!-- Legal Compliance -->
            <article class="overflow-hidden rounded-lg bg-white p-6 sm:p-8 shadow hover:shadow-lg transition duration-200">
              <div class="mb-4 flex justify-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-purple-100">
                  <span class="text-2xl">⚖️</span>
                </div>
              </div>
              <h3 class="mb-3 text-center text-lg font-bold text-gray-900 sm:text-xl">
                Legal Compliance Ready
              </h3>
              <p class="text-center text-sm text-gray-700 sm:text-base">
                Designed to meet election integrity requirements. Complete transparency for auditors while maintaining voter anonymity.
              </p>
            </article>
          </div>

          <!-- CTA to Election Security Page -->
          <div class="mt-12 flex justify-center">
            <a
              :href="route('public.election-security')"
              class="inline-flex items-center gap-3 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-3 sm:px-8 sm:py-4 font-semibold text-white hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200"
            >
              <span>Learn About State Machine Security</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      </section>

      <!-- Voting Journey Section -->
      <section
        class="w-full bg-gray-50 px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32"
        aria-labelledby="voting-journey-heading"
      >
        <div class="mx-auto max-w-6xl">
          <div class="mb-8 text-center sm:mb-12 md:mb-16">
            <h2
              id="voting-journey-heading"
              class="text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl"
            >
              {{ $t('pages.security.journey_title') || 'Voting Journey' }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 sm:mt-4 sm:text-base">
              {{ $t('pages.security.journey_subtitle') || 'Complete protection through five secure steps' }}
            </p>
          </div>

          <!-- Journey Image -->
          <figure class="mb-8 overflow-hidden rounded-lg shadow-lg sm:mb-12">
            <img
              :src="images.votingJourney"
              :alt="$t('pages.security.journey_image_alt') || 'Voting journey flowchart showing five security checkpoints'"
              class="h-auto  max-h-[1200px]  w-full object-contain"
              loading="lazy"
            />
            <figcaption class="sr-only">
              {{ $t('pages.security.journey_image_alt') || 'Voting journey flowchart' }}
            </figcaption>
          </figure>

          <!-- Journey Steps - Mobile Friendly -->
          <div class="grid grid-cols-1 gap-3 text-center sm:grid-cols-2 md:grid-cols-5 md:gap-2">
            <div class="rounded bg-white p-3 shadow-sm sm:p-4">
              <span class="block text-xs font-bold text-blue-600 sm:text-sm">
                {{ $t('pages.security.step1') || '1. Identity Check' }}
              </span>
              <span class="block text-xs text-gray-600 sm:text-sm">
                {{ $t('pages.security.step1_desc') || 'Your link is valid' }}
              </span>
            </div>
            <div class="rounded bg-white p-3 shadow-sm sm:p-4">
              <span class="block text-xs font-bold text-green-600 sm:text-sm">
                {{ $t('pages.security.step2') || '2. Timing Check' }}
              </span>
              <span class="block text-xs text-gray-600 sm:text-sm">
                {{ $t('pages.security.step2_desc') || 'Session not expired' }}
              </span>
            </div>
            <div class="rounded bg-white p-3 shadow-sm sm:p-4">
              <span class="block text-xs font-bold text-purple-600 sm:text-sm">
                {{ $t('pages.security.step3') || '3. Organization Check' }}
              </span>
              <span class="block text-xs text-gray-600 sm:text-sm">
                {{ $t('pages.security.step3_desc') || 'Right election' }}
              </span>
            </div>
            <div class="rounded bg-white p-3 shadow-sm sm:p-4">
              <span class="block text-xs font-bold text-amber-600 sm:text-sm">
                {{ $t('pages.security.step4') || '4. Rules Check' }}
              </span>
              <span class="block text-xs text-gray-600 sm:text-sm">
                {{ $t('pages.security.step4_desc') || 'Can vote' }}
              </span>
            </div>
            <div class="rounded bg-white p-3 shadow-sm sm:p-4">
              <span class="block text-xs font-bold text-red-600 sm:text-sm">
                {{ $t('pages.security.step5') || '5. Storage' }}
              </span>
              <span class="block text-xs text-gray-600 sm:text-sm">
                {{ $t('pages.security.step5_desc') || 'Anonymous but verifiable' }}
              </span>
            </div>
          </div>
        </div>
      </section>

      <!-- Trust Badges Section -->
      <section
        class="w-full px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32"
        aria-labelledby="trust-badges-heading"
      >
        <div class="mx-auto max-w-6xl">
          <h2
            id="trust-badges-heading"
            class="mb-8 text-center text-2xl font-bold text-gray-900 sm:mb-12 sm:text-3xl md:mb-16 md:text-4xl"
          >
            {{ $t('pages.security.trust_title') || 'Why Trust Us' }}
          </h2>

          <!-- Trust Badges Grid - Mobile First 1-2 columns -->
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
            <!-- Security Tests Badge -->
            <article
              class="overflow-hidden rounded-lg bg-blue-50 p-6 text-center transition hover:shadow-md sm:p-8"
              aria-labelledby="badge-security-tests"
            >
              <div id="badge-security-tests" class="mb-2 text-3xl font-bold text-blue-600 sm:text-4xl">
                {{ badges.securityTests }}
              </div>
              <p class="text-xs font-semibold text-gray-700 sm:text-sm">
                {{ $t('pages.security.badges.security_tests') }}
              </p>
              <p class="mt-2 text-xs text-gray-600">
                {{ $t('pages.security.badges.security_tests_desc') || 'Every security feature tested' }}
              </p>
            </article>

            <!-- Anonymity Badge -->
            <article
              class="overflow-hidden rounded-lg bg-green-50 p-6 text-center transition hover:shadow-md sm:p-8"
              aria-labelledby="badge-anonymity"
            >
              <div id="badge-anonymity" class="mb-2 text-3xl font-bold text-green-600 sm:text-4xl">
                {{ badges.anonymity }}
              </div>
              <p class="text-xs font-semibold text-gray-700 sm:text-sm">
                {{ $t('pages.security.badges.anonymity') }}
              </p>
              <p class="mt-2 text-xs text-gray-600">
                {{ $t('pages.security.badges.anonymity_desc') || 'No voter ID stored with votes' }}
              </p>
            </article>

            <!-- Protection Badge -->
            <article
              class="overflow-hidden rounded-lg bg-purple-50 p-6 text-center transition hover:shadow-md sm:p-8"
              aria-labelledby="badge-protection"
            >
              <div id="badge-protection" class="mb-2 text-3xl font-bold text-purple-600 sm:text-4xl">
                {{ badges.protection }}
              </div>
              <p class="text-xs font-semibold text-gray-700 sm:text-sm">
                {{ $t('pages.security.badges.protection') }}
              </p>
              <p class="mt-2 text-xs text-gray-600">
                {{ $t('pages.security.badges.protection_desc') || 'Independent security layers' }}
              </p>
            </article>

            <!-- Coverage Badge -->
            <article
              class="overflow-hidden rounded-lg bg-orange-50 p-6 text-center transition hover:shadow-md sm:p-8"
              aria-labelledby="badge-coverage"
            >
              <div id="badge-coverage" class="mb-2 text-3xl font-bold text-orange-600 sm:text-4xl">
                {{ badges.coverage }}
              </div>
              <p class="text-xs font-semibold text-gray-700 sm:text-sm">
                {{ $t('pages.security.badges.coverage') }}
              </p>
              <p class="mt-2 text-xs text-gray-600">
                {{ $t('pages.security.badges.coverage_desc') || 'Industry-leading test coverage' }}
              </p>
            </article>
          </div>
        </div>
      </section>

      <!-- FAQ Section - Accessible Details/Summary -->
      <section
        class="w-full bg-gray-50 px-4 py-12 sm:px-6 sm:py-16 md:py-24 lg:px-8 lg:py-32"
        aria-labelledby="faq-heading"
      >
        <div class="mx-auto max-w-3xl">
          <h2
            id="faq-heading"
            class="mb-8 text-center text-2xl font-bold text-gray-900 sm:mb-12 sm:text-3xl md:mb-16 md:text-4xl"
          >
            {{ $t('pages.security.faq_title') || 'Frequently Asked Questions' }}
          </h2>

          <div class="space-y-3 sm:space-y-4">
            <details
              v-for="item in faqItems"
              :key="item.id"
              class="group overflow-hidden rounded-lg bg-white shadow transition hover:shadow-md"
            >
              <summary
                class="flex cursor-pointer select-none items-center gap-4 px-4 py-4 sm:px-6 sm:py-5 hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                :aria-expanded="false"
              >
                <span class="flex-1 text-sm font-semibold text-gray-900 sm:text-base">
                  {{ $t(`pages.security.faq.${item.id}.question`) }}
                </span>
                <!-- Chevron Icon for Accessibility -->
                <svg
                  class="h-5 w-5 flex-shrink-0 text-gray-500 transition group-open:rotate-180 sm:h-6 sm:w-6"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
              </summary>

              <!-- FAQ Answer -->
              <div class="border-t border-gray-100 px-4 py-4 sm:px-6 sm:py-5">
                <p class="text-sm leading-relaxed text-gray-700 sm:text-base">
                  {{ $t(`pages.security.faq.${item.id}.answer`) }}
                </p>
              </div>
            </details>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import { route } from 'ziggy-js';
import { useI18n } from 'vue-i18n';
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue';
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue';
import { useMeta } from '@/composables/useMeta';

const { locale } = useI18n();

// Set SEO meta tags for this page
useMeta({ pageKey: 'security' });

defineProps({
  hero: {
    type: Object,
    required: true,
  },
  layers: {
    type: Array,
    required: true,
  },
  pillars: {
    type: Array,
    required: true,
  },
  badges: {
    type: Object,
    required: true,
  },
  faqItems: {
    type: Array,
    required: true,
  },
  images: {
    type: Object,
    required: true,
  },
  ctaButtons: {
    type: Array,
    required: true,
  },
});
</script>
