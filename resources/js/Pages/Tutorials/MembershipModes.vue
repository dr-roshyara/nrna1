<script setup>
import { useMeta } from '@/composables/useMeta'
import { computed, ref } from 'vue'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

useMeta({
  pageKey: 'tutorials.membership-modes',
  url: '/help/membership-modes',
  type: 'article',
  title: 'Membership Modes Guide',
  description: 'Learn about Full Membership and Election-Only modes for your organisation'
})

const activeSection = ref('overview')
</script>

<template>
  <div class="min-h-screen flex flex-col bg-slate-200">
    <!-- PublicDigit Header with breadcrumbs -->
    <PublicDigitHeader
      :breadcrumbs="[
        { label: 'Home', url: '/' },
        { label: 'Help & Guides', url: null },
        { label: 'Membership Modes', url: null }
      ]"
      :disable-language-selector="false"
    />

    <div class="flex-1">
      <!-- Hero Section - Dark Luxury Editorial Design -->
      <header class="relative bg-slate-900 text-white overflow-hidden py-20 md:py-32">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 opacity-5">
          <div class="absolute top-0 right-0 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 left-0 w-72 h-72 bg-teal-400 rounded-full blur-3xl"></div>
        </div>

        <!-- Accent line -->
        <div class="absolute top-0 left-0 h-1 bg-gradient-to-r from-blue-500 via-teal-500 to-transparent w-1/3"></div>

        <!-- Content -->
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Text Content -->
            <div class="space-y-6 animate-fade-in">
              <h1 class="text-5xl md:text-7xl font-bold leading-tight tracking-tight">
                <span class="block text-slate-100">Membership</span>
                <span class="block bg-gradient-to-r from-blue-400 via-teal-400 to-blue-400 text-transparent bg-clip-text">Modes</span>
              </h1>
              <p class="text-lg md:text-xl text-slate-300 max-w-xl font-light leading-relaxed">
                Understand the two approaches to voter eligibility and choose what works best for your organisation's structure and needs.
              </p>
            </div>

            <!-- Right: Visual Accent -->
            <div class="hidden lg:flex justify-end items-center">
              <div class="relative w-full h-full min-h-96">
                <!-- Geometric shapes representing the two modes -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-500/20 to-blue-600/10 rounded-2xl border border-primary-400/30 backdrop-blur-sm" style="animation: float 6s ease-in-out infinite;"></div>
                <div class="absolute bottom-8 right-32 w-48 h-48 bg-gradient-to-br from-teal-500/20 to-teal-600/10 rounded-2xl border border-teal-400/30 backdrop-blur-sm" style="animation: float 8s ease-in-out infinite; animation-delay: 1s;"></div>
                <div class="absolute top-1/2 right-12 w-32 h-32 border-2 border-slate-600/50 rounded-full" style="animation: rotate 20s linear infinite;"></div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Side Navigation -->
          <nav class="hidden lg:block">
            <div class="sticky top-8 bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
              <h3 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-widest">On this page</h3>
              <ul class="space-y-2">
                <li>
                  <a href="#overview" @click="activeSection = 'overview'" class="text-sm text-slate-600 hover:text-gold hover:font-semibold transition-colors">
                    What Are Membership Modes?
                  </a>
                </li>
                <li>
                  <a href="#full" @click="activeSection = 'full'" class="text-sm text-slate-600 hover:text-gold hover:font-semibold transition-colors">
                    Full Membership Mode
                  </a>
                </li>
                <li>
                  <a href="#election" @click="activeSection = 'election'" class="text-sm text-slate-600 hover:text-gold hover:font-semibold transition-colors">
                    Election-Only Mode
                  </a>
                </li>
                <li>
                  <a href="#transitions" @click="activeSection = 'transitions'" class="text-sm text-slate-600 hover:text-gold hover:font-semibold transition-colors">
                    Mode Transitions
                  </a>
                </li>
                <li>
                  <a href="#comparison" @click="activeSection = 'comparison'" class="text-sm text-slate-600 hover:text-gold hover:font-semibold transition-colors">
                    Feature Comparison
                  </a>
                </li>
              </ul>
            </div>
          </nav>

          <!-- Content -->
          <div class="lg:col-span-3 space-y-16">
            <!-- Section 1: Overview -->
            <section id="overview" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-slate-900 mb-6">What Are Membership Modes?</h2>
              <p class="text-lg text-slate-700 mb-6 leading-relaxed">
                Your organisation can configure how voters are eligible to participate in elections. This fundamental setting affects who can vote, what data is tracked, and how your election operates.
              </p>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Full Membership Card -->
                <div class="p-6 bg-primary-50 border-2 border-primary-200 rounded-xl">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-primary-200">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <h3 class="text-xl font-bold text-primary-900">Full Membership</h3>
                  </div>
                  <p class="text-primary-800">Formal member tracking with fees, membership types, and expiration dates</p>
                </div>

                <!-- Election-Only Card -->
                <div class="p-6 bg-emerald-50 border-2 border-emerald-200 rounded-xl">
                  <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-emerald-200">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.656" />
                        </svg>
                      </div>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900">Election-Only</h3>
                  </div>
                  <p class="text-emerald-800">Simple approach: any organisation user can vote</p>
                </div>
              </div>

              <!-- Decision Tree -->
              <div class="p-6 bg-slate-100 border border-slate-300 rounded-xl">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Decision Tree</h3>
                <div class="space-y-3 text-slate-700">
                  <p><strong>❓ Do you track formal memberships?</strong></p>
                  <div class="pl-6 space-y-2">
                    <p><strong>✅ YES</strong> → Choose <span class="text-primary-600 font-semibold">"Full Membership"</span></p>
                    <ul class="text-sm text-slate-600 space-y-1 pl-4">
                      <li>• Voters must be members</li>
                      <li>• Track fees and membership types</li>
                      <li>• Membership applications required</li>
                    </ul>
                  </div>
                  <div class="pl-6 space-y-2">
                    <p><strong>❌ NO</strong> → Choose <span class="text-emerald-600 font-semibold">"Election-Only"</span></p>
                    <ul class="text-sm text-slate-600 space-y-1 pl-4">
                      <li>• Any registered user can vote</li>
                      <li>• Import voters via CSV</li>
                      <li>• No membership validation</li>
                    </ul>
                  </div>
                </div>
              </div>
            </section>

            <!-- Section 2: Full Membership Mode -->
            <section id="full" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-slate-900 mb-6">Full Membership Mode</h2>
              <p class="text-lg text-slate-700 mb-6 leading-relaxed">
                Best for organisations with formal membership structures that need to track fees, membership types, and renewal dates.
              </p>

              <h3 class="text-2xl font-bold text-slate-900 mb-4">User Journey</h3>
              <div class="space-y-3 mb-8">
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">1</span></div>
                  <div><p class="font-semibold text-slate-900">Create Organisation</p><p class="text-sm text-slate-600">Select "Full Membership" mode</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">2</span></div>
                  <div><p class="font-semibold text-slate-900">Add Members</p><p class="text-sm text-slate-600">Add member records manually or via application</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">3</span></div>
                  <div><p class="font-semibold text-slate-900">Mark Fees as Paid</p><p class="text-sm text-slate-600">Update member fee status (paid/exempt)</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">4</span></div>
                  <div><p class="font-semibold text-slate-900">Create Election</p><p class="text-sm text-slate-600">Set up election and configuration</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">5</span></div>
                  <div><p class="font-semibold text-slate-900">Assign Voters</p><p class="text-sm text-slate-600">Only paid members appear in dropdown</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-primary-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-bold">6</span></div>
                  <div><p class="font-semibold text-slate-900">Voting</p><p class="text-sm text-slate-600">Only assigned, paid members can vote</p></div>
                </div>
              </div>

              <h3 class="text-2xl font-bold text-slate-900 mb-4">Use Cases</h3>
              <div class="overflow-x-auto mb-8">
                <table class="w-full text-sm border-collapse">
                  <thead>
                    <tr class="bg-primary-100">
                      <th class="border border-primary-300 px-4 py-2 text-left font-semibold text-primary-900">Scenario</th>
                      <th class="border border-primary-300 px-4 py-2 text-left font-semibold text-primary-900">Why</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="hover:bg-primary-50">
                      <td class="border border-primary-200 px-4 py-2">Verein/Club with annual fees</td>
                      <td class="border border-primary-200 px-4 py-2">Track paid members, only they can vote</td>
                    </tr>
                    <tr class="hover:bg-primary-50">
                      <td class="border border-primary-200 px-4 py-2">NGO with formal membership</td>
                      <td class="border border-primary-200 px-4 py-2">Membership types, voting rights by tier</td>
                    </tr>
                    <tr class="hover:bg-primary-50">
                      <td class="border border-primary-200 px-4 py-2">Diaspora organisation</td>
                      <td class="border border-primary-200 px-4 py-2">Verify members, fee collection</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>

            <!-- Section 3: Election-Only Mode -->
            <section id="election" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-slate-900 mb-6">Election-Only Mode</h2>
              <p class="text-lg text-slate-700 mb-6 leading-relaxed">
                Best for organisations that want to run elections without formal membership management. Any user registered in your organisation can vote.
              </p>

              <h3 class="text-2xl font-bold text-slate-900 mb-4">User Journey</h3>
              <div class="space-y-3 mb-8">
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-emerald-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-100 text-emerald-700 font-bold">1</span></div>
                  <div><p class="font-semibold text-slate-900">Create Organisation</p><p class="text-sm text-slate-600">Select "Election-Only" mode</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-emerald-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-100 text-emerald-700 font-bold">2</span></div>
                  <div><p class="font-semibold text-slate-900">Create Election</p><p class="text-sm text-slate-600">Set up election and configuration</p></div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-emerald-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-100 text-emerald-700 font-bold">3</span></div>
                  <div>
                    <p class="font-semibold text-slate-900">Add Voters (Three Options)</p>
                    <ul class="text-sm text-slate-600 mt-1 space-y-1">
                      <li>• <strong>Dropdown:</strong> Select any registered user</li>
                      <li>• <strong>CSV Import:</strong> Upload email list</li>
                      <li>• <strong>UUID:</strong> Paste user ID directly</li>
                    </ul>
                  </div>
                </div>
                <div class="flex gap-4 p-4 bg-slate-50 border-l-4 border-emerald-500 rounded">
                  <div class="flex-shrink-0"><span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-100 text-emerald-700 font-bold">4</span></div>
                  <div><p class="font-semibold text-slate-900">Voting</p><p class="text-sm text-slate-600">All assigned users can vote immediately</p></div>
                </div>
              </div>

              <h3 class="text-2xl font-bold text-slate-900 mb-4">Use Cases</h3>
              <div class="overflow-x-auto mb-8">
                <table class="w-full text-sm border-collapse">
                  <thead>
                    <tr class="bg-emerald-100">
                      <th class="border border-emerald-300 px-4 py-2 text-left font-semibold text-emerald-900">Scenario</th>
                      <th class="border border-emerald-300 px-4 py-2 text-left font-semibold text-emerald-900">Why</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="hover:bg-emerald-50">
                      <td class="border border-emerald-200 px-4 py-2">One-time event voting</td>
                      <td class="border border-emerald-200 px-4 py-2">No membership tracking needed</td>
                    </tr>
                    <tr class="hover:bg-emerald-50">
                      <td class="border border-emerald-200 px-4 py-2">Internal staff election</td>
                      <td class="border border-emerald-200 px-4 py-2">All staff automatically eligible</td>
                    </tr>
                    <tr class="hover:bg-emerald-50">
                      <td class="border border-emerald-200 px-4 py-2">Quick poll among users</td>
                      <td class="border border-emerald-200 px-4 py-2">Simple, fast setup</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>

            <!-- Section 4: Mode Transitions -->
            <section id="transitions" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-slate-900 mb-6">What Happens When You Switch Modes?</h2>

              <div class="mb-12">
                <h3 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                  <span class="text-primary-600">Full Membership</span>
                  <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                  <span class="text-emerald-600">Election-Only</span>
                </h3>

                <div class="space-y-3 mb-6">
                  <div class="p-4 bg-slate-100 border-l-4 border-amber-500 rounded">
                    <p class="text-sm text-slate-700"><strong>✓ Member records preserved:</strong> All existing member data stays in the system (not deleted)</p>
                  </div>
                  <div class="p-4 bg-slate-100 border-l-4 border-green-500 rounded">
                    <p class="text-sm text-slate-700"><strong>✓ All org users eligible:</strong> Any registered organisation user can now vote (not just members)</p>
                  </div>
                  <div class="p-4 bg-slate-100 border-l-4 border-amber-500 rounded">
                    <p class="text-sm text-slate-700"><strong>⚠ Membership checks removed:</strong> Fees, membership types, and expiration dates are no longer checked</p>
                  </div>
                  <div class="p-4 bg-slate-100 border-l-4 border-primary-500 rounded">
                    <p class="text-sm text-slate-700"><strong>✓ Elections unaffected:</strong> Existing elections continue to work normally</p>
                  </div>
                </div>

                <div class="p-4 bg-rose-50 border-2 border-rose-300 rounded-lg">
                  <p class="text-sm text-rose-900"><strong>⚠️ Important:</strong> Once switched to Election-Only, ensure you're comfortable with <strong>all organisation users</strong> being able to vote, regardless of member status.</p>
                </div>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                  <span class="text-emerald-600">Election-Only</span>
                  <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                  <span class="text-primary-600">Full Membership</span>
                </h3>

                <div class="space-y-3 mb-6">
                  <div class="p-4 bg-slate-100 border-l-4 border-amber-500 rounded">
                    <p class="text-sm text-slate-700"><strong>⚠ Member records required:</strong> Voters must have active member records to vote in future elections</p>
                  </div>
                  <div class="p-4 bg-slate-100 border-l-4 border-amber-500 rounded">
                    <p class="text-sm text-slate-700"><strong>⚠ Import members first:</strong> You'll need to import or create member records before elections can run</p>
                  </div>
                  <div class="p-4 bg-slate-100 border-l-4 border-primary-500 rounded">
                    <p class="text-sm text-slate-700"><strong>✓ Previous users not auto-imported:</strong> Users from Election-Only elections must be manually added as members</p>
                  </div>
                  <div class="p-4 bg-slate-100 border-l-4 border-green-500 rounded">
                    <p class="text-sm text-slate-700"><strong>✓ Existing elections preserved:</strong> Previous election results are unaffected</p>
                  </div>
                </div>

                <div class="p-4 bg-amber-50 border-2 border-amber-300 rounded-lg">
                  <p class="text-sm text-amber-900"><strong>⚠️ Action required:</strong> Before switching, plan how you'll manage member records, fees, and membership types for your organisation.</p>
                </div>
              </div>
            </section>

            <!-- Section 5: Feature Comparison -->
            <section id="comparison" class="scroll-mt-8">
              <h2 class="text-4xl font-bold text-slate-900 mb-6">Feature Comparison</h2>
              <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                  <thead>
                    <tr class="bg-slate-200">
                      <th class="border border-slate-300 px-4 py-3 text-left font-bold text-slate-900">Feature</th>
                      <th class="border border-slate-300 px-4 py-3 text-center font-bold text-primary-900 bg-primary-50">Full Membership</th>
                      <th class="border border-slate-300 px-4 py-3 text-center font-bold text-emerald-900 bg-emerald-50">Election-Only</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-200">
                    <tr class="hover:bg-slate-50">
                      <td class="border border-slate-300 px-4 py-3 font-semibold text-slate-900">Member records required</td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">✓</span></td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-danger-100 text-danger-700 font-bold text-xs">✕</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="border border-slate-300 px-4 py-3 font-semibold text-slate-900">Fee tracking</td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">✓</span></td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-danger-100 text-danger-700 font-bold text-xs">✕</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="border border-slate-300 px-4 py-3 font-semibold text-slate-900">Membership types</td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">✓</span></td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-danger-100 text-danger-700 font-bold text-xs">✕</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="border border-slate-300 px-4 py-3 font-semibold text-slate-900">Expiration dates</td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">✓</span></td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-danger-100 text-danger-700 font-bold text-xs">✕</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="border border-slate-300 px-4 py-3 font-semibold text-slate-900">Quick setup</td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-danger-100 text-danger-700 font-bold text-xs">✕</span></td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">✓</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                      <td class="border border-slate-300 px-4 py-3 font-semibold text-slate-900">Broad participation</td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-danger-100 text-danger-700 font-bold text-xs">✕</span></td>
                      <td class="border border-slate-300 px-4 py-3 text-center"><span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-green-100 text-green-700 font-bold text-xs">✓</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>

            <!-- CTA Section -->
            <section class="mt-16 p-8 bg-gradient-to-r from-amber-50 to-gold/10 border-2 border-gold rounded-xl">
              <h2 class="text-2xl font-bold text-slate-900 mb-4">Ready to Configure Your Organisation?</h2>
              <p class="text-slate-700 mb-6">Visit your organisation settings to choose the membership mode that works best for you.</p>
              <a href="/organisations" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-bold rounded-lg hover:from-amber-700 hover:to-amber-800 transition-all">
                Go to Organisation Settings
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
              </a>
            </section>
          </div>
        </div>
      </main>
    </div>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<style scoped>
.text-gold {
  color: #b5862b;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}

@keyframes rotate {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

