<script setup>
import { useMeta } from '@/composables/useMeta'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

useMeta({
  pageKey: 'tutorials.ballot-preview-guide',
  url: '/help/ballot-preview-guide',
  type: 'article',
  title: 'Ballot Preview Guide',
  description: 'See exactly how your ballot will look before voting opens — a read-only preview, no vote is ever recorded.'
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-neutral-100">
    <PublicDigitHeader
      :breadcrumbs="[
        { label: 'Home', url: '/' },
        { label: 'Help & Guides', url: '/tutorial' },
        { label: 'Ballot Preview', url: null }
      ]"
    />

    <div class="flex-1">
      <!-- Hero -->
      <header class="relative bg-neutral-900 text-white overflow-hidden py-20 md:py-28">
        <div class="absolute inset-0 opacity-5">
          <div class="absolute top-0 right-0 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
        </div>
        <div class="absolute top-0 left-0 h-1 bg-gradient-to-r from-primary-500 via-accent-500 to-transparent w-1/3"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <span class="inline-flex items-center gap-2 bg-primary-500/20 text-primary-200 border border-primary-400/40 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-widest mb-6">
            🔍 New
          </span>
          <h1 class="text-5xl md:text-6xl font-bold leading-tight tracking-tight mb-6">
            Ballot Preview
          </h1>
          <p class="text-lg md:text-xl text-neutral-300 max-w-2xl font-light leading-relaxed">
            See exactly how your ballot will look — the same posts, the same candidates, the same layout —
            before voting even opens. Nothing you do here is ever recorded as a vote.
          </p>
        </div>
      </header>

      <!-- Content -->
      <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Side nav -->
          <nav class="hidden lg:block">
            <div class="sticky top-8 bg-white rounded-xl border border-neutral-200 p-6 shadow-sm">
              <h3 class="text-sm font-bold text-neutral-900 mb-4 uppercase tracking-widest">On this page</h3>
              <ul class="space-y-2">
                <li><a href="#overview" class="text-sm text-neutral-600 hover:text-primary-700 hover:font-semibold transition-colors">What is Ballot Preview?</a></li>
                <li><a href="#access" class="text-sm text-neutral-600 hover:text-primary-700 hover:font-semibold transition-colors">Who can see it, and when</a></li>
                <li><a href="#how" class="text-sm text-neutral-600 hover:text-primary-700 hover:font-semibold transition-colors">How to open it</a></li>
                <li><a href="#not" class="text-sm text-neutral-600 hover:text-primary-700 hover:font-semibold transition-colors">What it does not do</a></li>
              </ul>
            </div>
          </nav>

          <div class="lg:col-span-3 space-y-16">
            <!-- Overview -->
            <section id="overview" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-neutral-900 mb-6">What is Ballot Preview?</h2>
              <p class="text-lg text-neutral-700 mb-6 leading-relaxed">
                Casting a real vote is intentionally locked behind a full identity-verification pipeline, so
                nobody can see the real ballot until the exact moment they're allowed to vote. Ballot Preview
                gives everyone a way to see what that ballot will look like ahead of time — the same national
                and regional posts, the same candidates, the same candidate-selection interaction — without any
                of that verification pipeline, and without any way to submit a vote from it.
              </p>
              <div class="p-6 bg-primary-50 border-2 border-primary-200 rounded-xl">
                <p class="text-primary-900">
                  <strong>In one sentence:</strong> Ballot Preview is a read-only, interactive-but-non-submittable
                  view of the real ballot — click candidates to see the selection experience, but nothing you
                  select here is ever saved or counted.
                </p>
              </div>
            </section>

            <!-- Access -->
            <section id="access" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-neutral-900 mb-6">Who can see it, and when</h2>

              <h3 class="text-2xl font-bold text-neutral-900 mb-4">Availability window</h3>
              <p class="text-neutral-700 mb-6 leading-relaxed">
                Ballot Preview becomes available once an election's setup and candidate nomination are
                complete — as soon as candidates are finalized and a voting window is scheduled — and it
                stays available for as long as voting is open. It disappears once voting closes and results
                are being counted.
              </p>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="p-5 bg-neutral-50 border border-neutral-200 rounded-xl text-center">
                  <p class="text-sm font-semibold text-neutral-500 mb-1">Before setup is complete</p>
                  <p class="text-danger-700 font-bold">Not available</p>
                </div>
                <div class="p-5 bg-success-50 border-2 border-success-300 rounded-xl text-center">
                  <p class="text-sm font-semibold text-success-700 mb-1">Ready to vote — through — voting open</p>
                  <p class="text-success-800 font-bold">Available</p>
                </div>
                <div class="p-5 bg-neutral-50 border border-neutral-200 rounded-xl text-center">
                  <p class="text-sm font-semibold text-neutral-500 mb-1">Once counting begins</p>
                  <p class="text-danger-700 font-bold">Not available</p>
                </div>
              </div>

              <h3 class="text-2xl font-bold text-neutral-900 mb-4">Who can open the link</h3>
              <p class="text-neutral-700 mb-4 leading-relaxed">
                Ballot Preview uses one shared link per election — not a personal voting link. Anyone with
                access to that election can open it:
              </p>
              <ul class="space-y-2 text-neutral-700 list-disc list-inside mb-2">
                <li>Organisation owners, admins, and commission members</li>
                <li>Anyone with an active voter membership in that election</li>
              </ul>
              <p class="text-sm text-neutral-500">
                A person with no role in the organisation and no membership in the election cannot open it.
              </p>
            </section>

            <!-- How -->
            <section id="how" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-neutral-900 mb-6">How to open it</h2>
              <div class="space-y-3">
                <div class="flex gap-4 p-4 bg-neutral-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">1</span></div>
                  <div><p class="font-semibold text-neutral-900">Go to Voter Hub</p><p class="text-sm text-neutral-600">Open your organisation's Voter Hub page.</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-neutral-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">2</span></div>
                  <div><p class="font-semibold text-neutral-900">Find the election</p><p class="text-sm text-neutral-600">Look at that election's Quick Actions.</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-neutral-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">3</span></div>
                  <div><p class="font-semibold text-neutral-900">Click "Preview Ballot"</p><p class="text-sm text-neutral-600">This tile only appears while the preview is available for that election.</p></div>
                </div>
              </div>
            </section>

            <!-- Not -->
            <section id="not" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-neutral-900 mb-6">What it does not do</h2>
              <div class="p-6 bg-neutral-50 border border-neutral-300 rounded-xl">
                <ul class="space-y-3 text-neutral-700">
                  <li class="flex gap-3"><span class="text-danger-600 font-bold">✕</span> It never submits, saves, or counts a vote — there is no "Submit" button, and no way to reach one.</li>
                  <li class="flex gap-3"><span class="text-danger-600 font-bold">✕</span> It never issues you a personal voting code or session — opening the preview does not start your real voting flow.</li>
                  <li class="flex gap-3"><span class="text-danger-600 font-bold">✕</span> It never touches any in-progress real ballot you may already be filling out elsewhere.</li>
                </ul>
              </div>
              <p class="text-neutral-700 mt-6 leading-relaxed">
                When you're ready to cast your actual vote, use your own personal voting link from your
                verification email or SMS — not this preview page.
              </p>
            </section>
          </div>
        </div>
      </main>
    </div>

    <PublicDigitFooter />
  </div>
</template>
