<template>
  <ElectionLayout>

    <!-- Skip to main content (Barrierefreiheit) -->
    <a href="#main-content"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:bg-white focus:text-primary-700 focus:font-semibold focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
      {{ t.a11y.skip_to_content }}
    </a>

    <!-- Flash Message -->
    <div
      v-if="page.props.flash?.success"
      role="alert"
      aria-live="polite"
      class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl flex items-center gap-2"
    >
      <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
      </svg>
      {{ page.props.flash.success }}
    </div>

    <main id="main-content" class="min-h-screen bg-slate-50" tabindex="-1">

      <!-- ── PAGE HERO ─────────────────────────────────────── -->
      <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

          <!-- Breadcrumb -->
          <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm text-slate-400 mb-5">
            <a :href="route('organisations.show', organisation.slug)"
               class="hover:text-primary-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-400 focus:rounded">
              {{ organisation.name }}
            </a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-700 font-medium" aria-current="page">{{ t.breadcrumb }}</span>
          </nav>

          <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-4">
              <!-- Icon -->
              <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-primary-600 flex items-center justify-center shadow-sm" aria-hidden="true">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                       M9 5a2 2 0 002 2h2a2 2 0 002-2
                       M9 5a2 2 0 012-2h2a2 2 0 012 2
                       m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div>
                <h1 class="text-2xl font-bold text-slate-900 leading-tight">{{ t.page_title }}</h1>
                <p class="text-slate-500 text-sm mt-1 max-w-xl">{{ t.page_subtitle }}</p>
              </div>
            </div>
            <a
              :href="route('organisations.show', organisation.slug)"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors flex-shrink-0"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              {{ organisation.name }}
            </a>
          </div>
        </div>
      </header>

      <!-- ── CONTENT ─────────────────────────────────────────── -->
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

        <!-- ── SECTION 1: QUICK ACTIONS ─────────────────────── -->
        <section aria-labelledby="actions-heading">
          <h2 id="actions-heading" class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">
            {{ t.sections.actions }}
          </h2>

          <!-- Empty: no active elections, no published results → just demo -->
          <div v-if="activeElections.length === 0 && publishedElections.length === 0"
               class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <!-- Demo Result always visible -->
            <ActionCard
              :href="route('demo-result.index')"
              accent="emerald"
              :label="t.nav.demo_results"
              :description="t.nav.demo_results_sub"
              icon="chart"
              :index="0"
            />
          </div>

          <!-- Active election state -->
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- Apply for Candidacy — only if user can apply to at least one election -->
            <ActionCard
              v-if="electionsWithCandidacy.length > 0"
              :href="route('organisations.candidacy.create', organisation.slug)"
              accent="amber"
              :label="t.nav.apply_candidacy"
              :description="t.nav.apply_candidacy_sub"
              icon="plus"
              :index="0"
            />

            <!-- My Applications -->
            <ActionCard
              v-if="activeElections.length > 0 || myApplications.length > 0"
              :href="route('organisations.candidacy.list', organisation.slug)"
              accent="primary"
              :label="t.nav.my_applications"
              :description="t.nav.my_applications_sub"
              icon="shield"
              :index="1"
            />

            <!-- Verify Vote -->
            <ActionCard
              href="/vote/verify_to_show"
              accent="blue"
              label="Verify Vote"
              description="View and verify your submitted vote"
              icon="check"
              :index="2"
            />

            <!-- Demo Result -->
            <ActionCard
              :href="route('demo-result.index')"
              accent="emerald"
              :label="t.nav.demo_results"
              :description="t.nav.demo_results_sub"
              icon="chart"
              :index="3"
            />
          </div>
        </section>

        <!-- ── SECTION 1.5: PUBLISHED ELECTION RESULTS ──────── -->
        <section v-if="publishedElections.length > 0" aria-labelledby="results-heading" class="space-y-6">
          <h2 id="results-heading" class="text-xs font-bold uppercase tracking-widest text-slate-400">
            {{ publishedElections.length === 1 ? 'Election Result Available' : 'Published Election Results' }}
          </h2>

          <!-- Results Grid: Bold, Striking Design -->
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div
              v-for="(election, idx) in publishedElections"
              :key="'published-' + election.id"
              class="result-card group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2"
              :style="{ animationDelay: `${idx * 120}ms` }"
            >
              <!-- Animated gradient overlay -->
              <div class="absolute inset-0 bg-gradient-to-tr from-black/0 via-transparent to-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>

              <!-- Subtle grid pattern background -->
              <div class="absolute inset-0 opacity-5 group-hover:opacity-10 transition-opacity duration-300"
                   style="backgroundImage: 'linear-gradient(45deg, rgba(255,255,255,0.1) 1px, transparent 1px)', 'linear-gradient(-45deg, rgba(255,255,255,0.1) 1px, transparent 1px)'; backgroundSize: '20px 20px';"
                   aria-hidden="true"></div>

              <!-- Content -->
              <div class="relative z-10 flex flex-col h-full">
                <!-- Header with icon -->
                <div class="flex items-start justify-between mb-6">
                  <div class="flex-1">
                    <p class="text-white/70 text-xs font-semibold uppercase tracking-wider mb-2">Election Complete</p>
                    <h3 class="text-white font-black text-xl md:text-2xl leading-tight">
                      {{ election.name }}
                    </h3>
                  </div>
                  <!-- Checkmark Icon -->
                  <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center group-hover:bg-white/30 transition-colors duration-300" aria-hidden="true">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                </div>

                <!-- Spacer -->
                <div class="flex-grow"></div>

                <!-- Dual CTA: Results + Receipt Codes -->
                <div class="pt-6 border-t border-white/20 group-hover:border-white/40 transition-colors duration-300 space-y-2 flex flex-col gap-2">
                  <!-- View Results Button -->
                  <a
                    :href="route('result.index', { election: election.slug })"
                    class="inline-flex items-center justify-center gap-2 w-full bg-primary-500/80 hover:bg-primary-400 text-white font-extrabold text-base py-3 px-4 rounded-lg transition-all duration-300 border border-primary-400/50 hover:border-primary-300 shadow-md hover:shadow-lg hover:scale-105"
                    :aria-label="`View results for ${election.name}`"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    View Results
                  </a>

                  <!-- View Receipt Codes Button -->
                  <a
                    :href="route('organisations.election.receipt-codes', { organisation: organisation.slug, election: election.slug })"
                    class="inline-flex items-center justify-center gap-2 w-full bg-green-600/80 hover:bg-green-500 text-white font-extrabold text-base py-3 px-4 rounded-lg transition-all duration-300 border border-green-500/50 hover:border-green-400 shadow-md hover:shadow-lg hover:scale-105"
                    :aria-label="`View receipt codes for ${election.name}`"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Receipt Codes
                  </a>
                </div>
              </div>

              <!-- Hover accent line -->
              <div class="absolute bottom-0 left-0 h-1 bg-white/80 w-0 group-hover:w-full transition-all duration-500" aria-hidden="true"></div>
            </div>
          </div>
        </section>

        <!-- ── SECTION 2: ACTIVE ELECTIONS ─────────────────── -->
        <section aria-labelledby="elections-heading">
          <h2 id="elections-heading" class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">
            {{ t.active_elections.title }}
          </h2>

          <!-- Empty state -->
          <div v-if="activeElections.length === 0"
               role="status"
               class="flex flex-col items-center justify-center text-center py-16 rounded-2xl border-2 border-dashed border-slate-200 bg-white">
            <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-4" aria-hidden="true">
              <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <p class="font-semibold text-slate-600 mb-1">{{ t.active_elections.empty_title }}</p>
            <p class="text-sm text-slate-400 max-w-xs">{{ t.active_elections.empty_desc }}</p>
          </div>

          <!-- Election cards grid -->
          <div v-else
               role="list"
               class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

            <article
              v-for="(election, idx) in activeElections"
              :key="election.id"
              role="listitem"
              :aria-labelledby="`election-title-${election.id}`"
              class="election-card bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 focus-within:ring-2 focus-within:ring-primary-400 focus-within:ring-offset-2"
              :style="{ animationDelay: `${idx * 80}ms` }"
            >
              <!-- Coloured top bar + status -->
              <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-4 flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <h3 :id="`election-title-${election.id}`"
                      class="font-bold text-white text-base leading-snug truncate">
                    {{ election.name }}
                  </h3>
                  <p v-if="election.description"
                     class="text-primary-200 text-xs mt-0.5 line-clamp-1">
                    {{ election.description }}
                  </p>
                </div>
                <!-- Status badge -->
                <span
                  :class="statusBadgeClass(election.id)"
                  class="flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold"
                  :aria-label="`${t.a11y.status_label}: ${statusLabel(election.id)}`"
                >
                  <!-- voted dot -->
                  <span v-if="voterStatus(election.id) === 'voted'"
                        class="w-1.5 h-1.5 rounded-full bg-current" aria-hidden="true"/>
                  {{ statusLabel(election.id) }}
                </span>
              </div>

              <!-- Body -->
              <div class="px-5 py-4 space-y-4">

                <!-- Dates row -->
                <div v-if="election.start_date || election.end_date"
                     class="flex items-center gap-1.5 text-xs text-slate-400">
                  <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <time :datetime="election.start_date">{{ formatDate(election.start_date) }}</time>
                  <span v-if="election.end_date" aria-hidden="true">–</span>
                  <time v-if="election.end_date" :datetime="election.end_date">{{ formatDate(election.end_date) }}</time>
                </div>

                <!-- Positions table -->
                <div v-if="election.posts?.length">
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">
                    {{ t.active_elections.positions_label }}
                  </p>
                  <ul class="divide-y divide-slate-100" role="list">
                    <li
                      v-for="post in election.posts"
                      :key="post.id"
                      class="flex items-center justify-between py-1.5 text-sm gap-2"
                    >
                      <span class="text-slate-700 truncate font-medium">{{ post.name }}</span>
                      <div class="flex items-center gap-1.5 flex-shrink-0">
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-semibold"
                              :class="post.is_national_wide
                                ? 'bg-primary-50 text-primary-600'
                                : 'bg-amber-50 text-amber-600'">
                          {{ post.is_national_wide ? t.active_elections.national : (post.state_name || t.active_elections.regional) }}
                        </span>
                        <span class="text-[10px] text-slate-400">
                          {{ post.required_number }}
                          {{ post.required_number !== 1 ? t.active_elections.seats : t.active_elections.seat }}
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>

                <!-- Per-election secondary actions -->
                <!-- Per-election action buttons -->
                <div v-if="voterStatus(election.id) !== 'ineligible' || isOfficer"
                     class="border-t border-slate-100 pt-4 space-y-2">
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Quick Actions</p>
                  <div class="grid grid-cols-1 gap-2">

                    <!-- Positions -->
                    <a
                      :href="route('organisations.elections.positions', { organisation: organisation.slug, election: election.slug })"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-slate-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-1"
                      :aria-label="`View positions for ${election.name}`"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-slate-800">{{ t.nav.positions }}</span>
                        <span class="block text-xs text-slate-500">View all election posts</span>
                      </span>
                      <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- Candidates List -->
                    <a
                      :href="route('organisations.elections.candidates', { organisation: organisation.slug, election: election.slug })"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-purple-200 bg-purple-50 hover:bg-white hover:border-purple-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-1"
                      :aria-label="`View candidates for ${election.name}`"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-purple-800">Candidates</span>
                        <span class="block text-xs text-purple-600">View positions & candidates</span>
                      </span>
                      <svg class="w-4 h-4 text-purple-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- Voters List — all election members -->
                    <a
                      :href="route('organisations.elections.voters', { organisation: organisation.slug, election: election.slug })"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-teal-200 bg-teal-50 hover:bg-white hover:border-teal-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-1"
                      :aria-label="`View voters for ${election.name}`"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-teal-800">Voters</span>
                        <span class="block text-xs text-teal-600">View registered voters</span>
                      </span>
                      <svg class="w-4 h-4 text-teal-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- Verify Vote -->
                    <a
                      href="/vote/verify_to_show"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-primary-200 bg-primary-50 hover:bg-white hover:border-primary-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1"
                      aria-label="Verify your submitted vote"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-primary-800">Verify Vote</span>
                        <span class="block text-xs text-primary-600">View and verify your vote</span>
                      </span>
                      <svg class="w-4 h-4 text-primary-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- View Receipt Codes — only if results are published -->
                    <a
                      v-if="election.results_published_at"
                      :href="route('organisations.election.receipt-codes', { organisation: organisation.slug, election: election.slug })"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-green-200 bg-green-50 hover:bg-white hover:border-green-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1"
                      :aria-label="`View verification codes for ${election.name}`"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-green-800">Receipt Codes</span>
                        <span class="block text-xs text-green-600">Verify all voter codes</span>
                      </span>
                      <svg class="w-4 h-4 text-green-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- Import Voters — officers only -->
                    <a
                      v-if="isOfficer"
                      :href="route('elections.voters.import.create', { organisation: organisation.slug, election: election.slug })"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-teal-200 bg-teal-50 hover:bg-white hover:border-teal-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-1"
                      :aria-label="`Import voters for ${election.name}`"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-teal-800">Import Voters</span>
                        <span class="block text-xs text-teal-600">Bulk upload from CSV / Excel</span>
                      </span>
                      <svg class="w-4 h-4 text-teal-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- Apply for Candidacy — only if not yet applied -->
                    <a
                      v-if="!appliedElectionIds.includes(election.id)"
                      :href="route('organisations.elections.candidacy.apply', { organisation: organisation.slug, election: election.slug })"
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-amber-200 bg-amber-50 hover:bg-white hover:border-amber-300 hover:shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1"
                      :aria-label="`Apply for candidacy in ${election.name}`"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-amber-800">{{ t.nav.apply_candidacy }}</span>
                        <span class="block text-xs text-amber-600">Submit your nomination</span>
                      </span>
                      <svg class="w-4 h-4 text-amber-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>

                    <!-- Applied badge -->
                    <div
                      v-else
                      class="flex items-center gap-3 px-4 py-3 rounded-xl border border-emerald-200 bg-emerald-50"
                      role="status"
                    >
                      <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center" aria-hidden="true">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                      </span>
                      <span class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-emerald-800">Application Submitted</span>
                        <span class="block text-xs text-emerald-600">Awaiting commission review</span>
                      </span>
                    </div>

                  </div>
                </div>

                <!-- CTA -->
                <div class="pt-1">
                  <!-- Results Published: Show Receipt Codes CTA -->
                  <a
                    v-if="election.results_published_at"
                    :href="route('organisations.election.receipt-codes', { organisation: organisation.slug, election: election.slug })"
                    class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
                    :aria-label="`View receipt codes for ${election.name}`"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    View Receipt Codes
                  </a>

                  <!-- Still Active: Vote or Status -->
                  <a
                    v-else-if="voterStatus(election.id) === 'eligible'"
                    :href="route('elections.show', { slug: election.slug })"
                    class="flex items-center justify-center gap-2 w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2"
                    :aria-label="`${t.active_elections.vote_now} — ${election.name}`"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                           M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2
                           m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/>
                    </svg>
                    {{ t.active_elections.vote_now }}
                  </a>

                  <div
                    v-else-if="voterStatus(election.id) === 'voted' && !election.results_published_at"
                    class="flex items-center justify-center gap-2 w-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold py-2.5 rounded-xl"
                    role="status"
                    :aria-label="t.active_elections.vote_submitted"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ t.active_elections.vote_submitted }}
                  </div>

                  <div
                    v-else
                    class="flex items-center justify-center gap-2 w-full bg-slate-50 border border-slate-200 text-slate-400 text-sm py-2.5 rounded-xl cursor-not-allowed"
                    role="status"
                    :aria-label="t.status.ineligible"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/>
                    </svg>
                    {{ t.status.ineligible }}
                  </div>
                </div>
              </div>
            </article>
          </div>
        </section>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { computed, h, resolveComponent } from 'vue'
import { useI18n } from 'vue-i18n'
import { usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

import pageDe from '@/locales/pages/Organisations/VoterHub/de.json'
import pageEn from '@/locales/pages/Organisations/VoterHub/en.json'
import pageNp from '@/locales/pages/Organisations/VoterHub/np.json'

const { locale } = useI18n()
const pageData   = { de: pageDe, en: pageEn, np: pageNp }
const t          = computed(() => pageData[locale.value] ?? pageData.de)

const props = defineProps({
  organisation:       { type: Object,  required: true },
  activeElections:    { type: Array,   default: () => [] },
  publishedElections: { type: Array,   default: () => [] },
  voterMemberships:   { type: Object,  default: () => ({}) },
  myApplications:     { type: Array,   default: () => [] },
  isOfficer:          { type: Boolean, default: false },
  appliedElectionIds: { type: Array,   default: () => [] },
})

// Elections this user can access (voter OR officer)
const accessibleElections = computed(() =>
  props.activeElections.filter(e => {
    if (props.isOfficer) return true
    const m = props.voterMemberships[e.id]
    return m && m.status === 'active'
  })
)

// Elections where user can still apply (accessible + not yet applied)
const electionsWithCandidacy = computed(() =>
  accessibleElections.value.filter(e => !props.appliedElectionIds.includes(e.id))
)

const page = usePage()

// ── helpers ──────────────────────────────────────────────────────────────
function voterStatus(electionId) {
  const m = props.voterMemberships[electionId]
  if (!m) return 'ineligible'
  if (m.has_voted) return 'voted'
  if (m.status === 'active') return 'eligible'
  return 'ineligible'
}

function statusLabel(electionId) {
  const s = voterStatus(electionId)
  if (s === 'eligible') return t.value.status.eligible
  if (s === 'voted')    return t.value.status.voted
  return t.value.status.ineligible
}

function statusBadgeClass(electionId) {
  const s = voterStatus(electionId)
  if (s === 'eligible') return 'bg-white/20 text-white'
  if (s === 'voted')    return 'bg-emerald-100 text-emerald-700'
  return 'bg-white/10 text-white/60'
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString(locale.value === 'de' ? 'de-DE' : locale.value === 'np' ? 'ne-NP' : 'en-GB', {
    day: '2-digit', month: 'short', year: 'numeric',
  })
}
</script>

<!-- ── ActionCard: inline functional component ──────────────────────────── -->
<script>
// ActionCard defined separately so it can be used as a component in template
export const ActionCard = {
  name: 'ActionCard',
  props: {
    href:        { type: String,  required: true },
    accent:      { type: String,  default: 'primary' },  // primary | amber | blue | violet | emerald
    label:       { type: String,  required: true },
    description: { type: String,  default: '' },
    icon:        { type: String,  default: 'arrow' },    // plus | shield | list | chart | arrow
    index:       { type: Number,  default: 0 },
  },
  setup(props) {
    const accentMap = {
      primary: { border: 'border-l-primary-500', bg: 'bg-primary-50',  icon: 'text-primary-600', hover: 'hover:border-primary-300 hover:bg-primary-50/80' },
      amber:   { border: 'border-l-amber-500',   bg: 'bg-amber-50',    icon: 'text-amber-600',   hover: 'hover:border-amber-300   hover:bg-amber-50/80'   },
      blue:    { border: 'border-l-blue-500',     bg: 'bg-primary-50',     icon: 'text-primary-600',    hover: 'hover:border-primary-300    hover:bg-primary-50/80'    },
      violet:  { border: 'border-l-violet-500',   bg: 'bg-violet-50',   icon: 'text-violet-600',  hover: 'hover:border-violet-300  hover:bg-violet-50/80'  },
      emerald: { border: 'border-l-emerald-500',  bg: 'bg-emerald-50',  icon: 'text-emerald-600', hover: 'hover:border-emerald-300 hover:bg-emerald-50/80' },
    }
    const style = accentMap[props.accent] ?? accentMap.primary

    const icons = {
      plus:   'M12 4v16m8-8H4',
      shield: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
      list:   'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
      chart:  'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
      arrow:  'M14 5l7 7m0 0l-7 7m7-7H3',
    }

    return () => h('a', {
      href: props.href,
      class: [
        'group action-card flex items-start gap-4 p-5 bg-white rounded-2xl',
        'border border-slate-200 border-l-4',
        style.border, style.hover,
        'transition-all duration-200 hover:shadow-md',
        'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-400',
      ].join(' '),
      style: { animationDelay: `${props.index * 60}ms` },
    }, [
      // Icon box
      h('div', {
        class: `flex-shrink-0 w-10 h-10 rounded-xl ${style.bg} flex items-center justify-center group-hover:scale-110 transition-transform duration-200`,
        'aria-hidden': 'true',
      }, [
        h('svg', { class: `w-5 h-5 ${style.icon}`, fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
          h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: icons[props.icon] ?? icons.arrow }),
        ]),
      ]),
      // Text
      h('div', { class: 'min-w-0' }, [
        h('p', { class: 'font-semibold text-slate-900 text-sm leading-snug' }, props.label),
        h('p', { class: 'text-xs text-slate-500 mt-0.5 line-clamp-2' }, props.description),
      ]),
      // Chevron
      h('svg', {
        class: 'flex-shrink-0 ml-auto w-4 h-4 text-slate-300 group-hover:text-slate-500 group-hover:translate-x-0.5 transition-all duration-200 mt-0.5',
        fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', 'aria-hidden': 'true',
      }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 5l7 7-7 7' }),
      ]),
    ])
  },
}

export default { components: { ActionCard } }
</script>

<style scoped>
/* Staggered entrance */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to   { opacity: 1; transform: translateY(0); }
}

@keyframes slideUpReveal {
  from {
    opacity: 0;
    transform: translateY(24px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.action-card,
.election-card,
.result-card {
  animation: slideUpReveal 0.48s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

.result-card {
  /* Glowing effect on hover */
  position: relative;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.result-card:hover {
  box-shadow: 0 20px 40px -8px rgba(16, 185, 129, 0.4);
}

.result-card:focus-visible {
  outline: 3px solid rgba(255, 255, 255, 0.5);
  outline-offset: 2px;
}

/* High-contrast focus ring for WCAG 2.1 AA */
:focus-visible {
  outline: 3px solid #4f46e5;
  outline-offset: 3px;
}
</style>

