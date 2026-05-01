<template>
  <Head>
    <title>Election Security & State Machine Technology | Public Digit</title>
    <meta name="description" content="Learn how our tamper-proof state machine guarantees election integrity through immutable audit trails, cryptographic verification, and verifiable results." />
    <meta name="keywords" content="election security, state machine, tamper-proof, audit trail, cryptographic, voting integrity" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="Election Security Through State Machine Technology" />
    <meta property="og:description" content="Tamper-proof state machine with immutable audit trails and cryptographic verification." />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="canonical" href="https://publicdigit.com/election-security" />
  </Head>

  <PublicDigitLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
      <!-- Hero Section -->
      <div class="relative overflow-hidden bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
        <div class="absolute inset-0 opacity-10">
          <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-400 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 py-20 relative z-10">
          <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-2 mb-6">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
              <span class="text-sm font-medium">Verified & Secure</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
              Election Integrity Through<br>
              <span class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                State Machine Technology
              </span>
            </h1>
            <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
              Every election on our platform is secured by a tamper-proof state machine that guarantees transparency,
              immutability, and verifiable results.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="#how-it-works" class="px-6 py-3 bg-white text-primary-900 font-semibold rounded-lg hover:shadow-lg transition">
                How It Works
              </a>
              <a href="mailto:sales@publicdigit.com" class="px-6 py-3 border border-white/30 text-white font-semibold rounded-lg hover:bg-white/10 transition">
                Start an Election
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Trust Badges -->
      <div class="bg-white border-b border-slate-200 py-8">
        <div class="container mx-auto px-4">
          <div class="flex flex-wrap justify-center gap-8 md:gap-16">
            <div v-for="badge in trustBadges" :key="badge" class="flex items-center gap-3">
              <svg class="w-8 h-8 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
              <span class="text-slate-600 font-medium">{{ badge }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- State Machine Visualization -->
      <div id="how-it-works" class="py-16 bg-white">
        <div class="container mx-auto px-4">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">The 5-Phase Election Lifecycle</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              Our state machine ensures elections progress through 5 distinct, auditable phases —
              never skipping steps, never moving backward.
            </p>
          </div>

          <!-- Interactive Phase Diagram -->
          <div class="relative py-12 overflow-x-auto">
            <div class="min-w-[800px] md:min-w-full">
              <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div
                  v-for="(phase, idx) in phases"
                  :key="phase.key"
                  class="w-full md:w-1/5 text-center group cursor-pointer"
                  @click="selectedPhase = phase.key"
                >
                  <div class="relative">
                    <div
                      class="w-20 h-20 mx-auto rounded-full flex items-center justify-center group-hover:scale-110 transition-transform ring-2"
                      :class="[phase.bg, selectedPhase === phase.key ? 'ring-blue-500 ring-offset-2' : 'ring-transparent']"
                    >
                      <span class="text-3xl">{{ phase.icon }}</span>
                    </div>
                    <div v-if="idx < phases.length - 1" class="absolute -right-10 top-10 hidden md:block">
                      <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </div>
                  </div>
                  <h3 class="font-bold mt-4 text-slate-800">{{ idx + 1 }}. {{ phase.label }}</h3>
                  <p class="text-sm text-slate-500 mt-1">{{ phase.sub }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Phase Details Panel -->
          <div class="mt-8 bg-slate-50 rounded-xl p-6 max-w-2xl mx-auto">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" :class="phaseDetail.iconBg">
                <span class="text-2xl">{{ phaseDetail.icon }}</span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-slate-800">{{ phaseDetail.title }}</h3>
                <p class="text-slate-600 mt-1">{{ phaseDetail.description }}</p>
                <ul class="mt-3 space-y-1">
                  <li v-for="feature in phaseDetail.features" :key="feature" class="flex items-center gap-2 text-sm text-slate-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ feature }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Security Features -->
      <div class="py-16 bg-gradient-to-br from-slate-50 to-white">
        <div class="container mx-auto px-4">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Tamper-Proof Security Features</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              Our state machine architecture provides multiple layers of security and verification.
            </p>
          </div>

          <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-800 mb-2">Immutable State Log</h3>
              <p class="text-slate-600">
                Every state change is recorded in an immutable log that cannot be modified or deleted.
                Complete audit trail for legal verification.
              </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-800 mb-2">Cryptographic Hashes</h3>
              <p class="text-slate-600">
                Each vote and state transition is cryptographically hashed, ensuring tamper detection
                and mathematical verification of election integrity.
              </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
              <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-800 mb-2">Complete Audit Trail</h3>
              <p class="text-slate-600">
                Every action is logged with timestamp, user identity, and IP address. Full transparency
                for election administrators and auditors.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Auto-Transition Architecture Diagram -->
      <div class="py-16 bg-white border-t border-slate-200">
        <div class="container mx-auto px-4">
          <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Automated State Transition Architecture</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              Our hourly cron job automatically advances elections through phases using a
              cache-locked, audit-logged process — ensuring zero manual intervention is required.
            </p>
          </div>
          <div class="bg-slate-50 rounded-xl overflow-hidden shadow-md max-w-2xl mx-auto">
            <img
              src="/storage/architecture/state_machine_architect.png"
              alt="Auto-transition state machine flowchart showing hourly cron job processing elections through administration and nomination phases"
              loading="lazy"
              class="w-full h-auto"
            />
          </div>
        </div>
      </div>

      <!-- Comparison Table -->
      <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Why State Machine Security Matters</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              Traditional systems vs. Our tamper-proof state machine
            </p>
          </div>

          <div class="overflow-x-auto max-w-4xl mx-auto">
            <table class="w-full border-collapse rounded-xl overflow-hidden shadow-sm">
              <thead>
                <tr class="border-b-2 border-slate-200 bg-slate-50">
                  <th class="text-left py-4 px-6 text-slate-600 font-semibold">Feature</th>
                  <th class="text-left py-4 px-6 text-slate-400 font-medium">Traditional Systems</th>
                  <th class="text-left py-4 px-6 text-primary-600 font-semibold">Our State Machine</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="row in comparisonRows" :key="row.feature" class="hover:bg-slate-50 transition">
                  <td class="py-4 px-6 font-medium text-slate-800">{{ row.feature }}</td>
                  <td class="py-4 px-6 text-slate-500">{{ row.traditional }}</td>
                  <td class="py-4 px-6 text-green-600 font-medium">✓ {{ row.stateMachine }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- FAQ -->
      <div class="py-16 bg-slate-50">
        <div class="container mx-auto px-4">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Frequently Asked Questions</h2>
          </div>

          <div class="max-w-3xl mx-auto space-y-4">
            <div v-for="(faq, idx) in faqs" :key="idx" class="bg-white rounded-xl border border-slate-200 overflow-hidden">
              <button
                @click="toggleFaq(idx)"
                class="w-full text-left px-6 py-4 font-semibold text-slate-800 flex justify-between items-center hover:bg-slate-50 transition"
              >
                {{ faq.question }}
                <svg
                  class="w-5 h-5 text-slate-400 transition-transform flex-shrink-0 ml-4"
                  :class="{ 'rotate-180': openFaq === idx }"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              <transition name="expand">
                <div v-show="openFaq === idx" class="px-6 pb-4 text-slate-600 border-t border-slate-100 pt-4">
                  {{ faq.answer }}
                </div>
              </transition>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div class="py-16 bg-gradient-to-r from-blue-900 to-indigo-900 text-white">
        <div class="container mx-auto px-4 text-center">
          <h2 class="text-3xl font-bold mb-4">Ready to Run a Secure Election?</h2>
          <p class="text-xl text-primary-200 mb-8 max-w-2xl mx-auto">
            Join organizations that trust our tamper-proof state machine technology.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="mailto:sales@publicdigit.com" class="px-8 py-3 bg-white text-primary-900 font-semibold rounded-lg hover:shadow-lg transition">
              Contact Sales
            </a>
            <a href="mailto:demo@publicdigit.com" class="px-8 py-3 border border-white/30 text-white font-semibold rounded-lg hover:bg-white/10 transition">
              Request Demo
            </a>
          </div>
        </div>
      </div>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const selectedPhase = ref('administration')
const openFaq = ref(null)

const trustBadges = [
  'Tamper-Proof Audit Trail',
  'Immutable State Transitions',
  'Cryptographic Verification',
  'Verifiable Results',
]

const phases = [
  { key: 'administration',  icon: '⚙️', bg: 'bg-primary-100',   label: 'Administration', sub: 'Setup & Configuration' },
  { key: 'nomination',      icon: '📋', bg: 'bg-green-100',  label: 'Nomination',     sub: 'Candidate Applications' },
  { key: 'voting',          icon: '🗳️', bg: 'bg-purple-100', label: 'Voting',         sub: 'Secure Vote Casting' },
  { key: 'results_pending', icon: '⏳', bg: 'bg-orange-100', label: 'Counting',       sub: 'Verification & Tally' },
  { key: 'results',         icon: '📊', bg: 'bg-yellow-100', label: 'Results',        sub: 'Final Publication' },
]

const phaseDetails = {
  administration: {
    icon: '⚙️', iconBg: 'bg-primary-100',
    title: 'Administration Phase',
    description: 'Election setup and configuration period. Election officers configure posts, import voters, and form the election committee.',
    features: [
      'Create and configure election positions',
      'Import voter lists from CSV',
      'Form election committee',
      'Set election timeline and rules',
    ],
  },
  nomination: {
    icon: '📋', iconBg: 'bg-green-100',
    title: 'Nomination Phase',
    description: 'Candidate application and approval period. Candidates apply and election officers approve or reject applications.',
    features: [
      'Candidates submit applications',
      'Review and approve/reject candidates',
      'View candidate profiles',
      'Automatic transition after grace period',
    ],
  },
  voting: {
    icon: '🗳️', iconBg: 'bg-purple-100',
    title: 'Voting Phase',
    description: 'Secure voting period. Registered voters cast their votes anonymously.',
    features: [
      'Anonymous vote casting',
      'Cryptographic vote receipts',
      'Real-time verification',
      'Voting period strictly enforced',
    ],
  },
  results_pending: {
    icon: '⏳', iconBg: 'bg-orange-100',
    title: 'Counting Phase',
    description: 'Vote counting and verification period. Results are tabulated and verified before publication.',
    features: [
      'Automatic vote counting',
      'Verification of vote integrity',
      'Audit trail review',
      'Ready for publication',
    ],
  },
  results: {
    icon: '📊', iconBg: 'bg-yellow-100',
    title: 'Results Phase',
    description: 'Final results published. Winners are announced and results are available for public viewing.',
    features: [
      'Official results publication',
      'Downloadable vote receipts',
      'Public verification',
      'Permanent audit record',
    ],
  },
}

const phaseDetail = computed(() => phaseDetails[selectedPhase.value] ?? phaseDetails.administration)

const comparisonRows = [
  { feature: 'Audit Trail',       traditional: 'Basic logging, can be modified',    stateMachine: 'Immutable, cryptographically sealed' },
  { feature: 'State Changes',     traditional: 'Can be reversed or skipped',        stateMachine: 'One-way, verifiable transitions' },
  { feature: 'Vote Integrity',    traditional: 'Changes possible after voting',     stateMachine: 'Locked once voting starts' },
  { feature: 'Verifiability',     traditional: 'Limited transparency',              stateMachine: 'Complete public verification' },
  { feature: 'Legal Compliance',  traditional: 'May not meet requirements',         stateMachine: 'Designed for legal standards' },
]

const faqs = [
  {
    question: 'What is a state machine and why is it important for elections?',
    answer: 'A state machine ensures elections progress through predefined phases in a strict, verifiable order. It prevents skipping steps, moving backward, or making unauthorized changes. This guarantees election integrity and provides a complete audit trail.',
  },
  {
    question: 'Can election results be changed after publication?',
    answer: 'No. Once results are published, the state machine locks them permanently. All changes are logged immutably, and any attempt to modify results would break the cryptographic verification chain.',
  },
  {
    question: 'How do I know my vote was counted correctly?',
    answer: 'You receive a unique cryptographic receipt after voting. You can use this receipt to verify your vote in the public results page. The receipt proves your vote was recorded without revealing how you voted.',
  },
  {
    question: 'What happens if an election officer tries to bypass a phase?',
    answer: 'The state machine prevents bypassing phases. Each phase has strict prerequisites that must be met before advancing. All attempts are logged in the immutable audit trail.',
  },
  {
    question: 'Is the system legally compliant for official elections?',
    answer: 'Yes. Our state machine architecture is designed to meet legal requirements for election integrity, including immutable audit trails, cryptographic verification, and complete transparency.',
  },
]

const toggleFaq = (idx) => {
  openFaq.value = openFaq.value === idx ? null : idx
}
</script>

<style scoped>
.expand-enter-active,
.expand-leave-active {
  transition: all 0.3s ease;
}
.expand-enter-from,
.expand-leave-to {
  opacity: 0;
  max-height: 0;
  overflow: hidden;
}
</style>

