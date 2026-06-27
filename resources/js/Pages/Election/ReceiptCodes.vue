<template>
  <PublicDigitLayout>
    <main class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto">
        <!-- Header Section -->
        <div class="mb-12">
          <!-- Title Section -->
          <div class="space-y-3 mb-6">
            <div class="inline-flex items-center gap-3 mb-4">
              <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900">Verification Codes</h1>
            <p class="text-lg text-slate-600 mt-2">
              <span class="font-semibold text-slate-900">{{ election.name }}</span>
            </p>
            <p class="text-sm text-slate-500">Published on {{ published_at }}</p>
          </div>

          <!-- Navigation Links (Responsive) -->
          <div class="flex flex-col sm:flex-row gap-3">
            <!-- Verify Vote Button -->
            <a
              href="/vote/verify_to_show"
              class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4"/>
              </svg>
              Verify Vote
            </a>

            <!-- Back to Voter Hub Button -->
            <Link
              :href="route('organisations.voter-hub', organisation.slug)"
              class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold text-sm rounded-lg transition-colors duration-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
              Back
            </Link>
          </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Privacy Notice -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 p-6">
              <div class="absolute inset-0 opacity-5">
                <svg class="absolute w-32 h-32 -top-8 -right-8" fill="currentColor" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="50" opacity="0.1"/>
                </svg>
              </div>
              <div class="relative flex gap-4">
                <div class="flex-shrink-0 w-6 h-6 mt-1">
                  <svg class="w-full h-full text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="font-bold text-green-900 mb-2">Your Privacy is Protected</h3>
                  <ul class="text-sm text-green-800 space-y-1">
                    <li>✓ Receipt codes are randomized and not linked to voting time</li>
                    <li>✓ No one can see which code belongs to you</li>
                    <li>✓ Find your receipt code from your confirmation email</li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Receipt Codes Table -->
            <div class="rounded-2xl shadow-lg overflow-hidden border border-slate-200">
              <div v-if="receipt_codes.length > 0" class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gradient-to-r from-slate-700 to-slate-800 text-white">
                    <tr>
                      <th class="px-6 py-4 text-left text-sm font-semibold">#</th>
                      <th class="px-6 py-4 text-left text-sm font-semibold">Receipt Code</th>
                      <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-200">
                    <tr
                      v-for="(item, idx) in receipt_codes"
                      :key="item.serial"
                      class="hover:bg-slate-50 transition-colors duration-200 last:border-b-0 group"
                      :class="idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/50'"
                      @mouseenter="hoveredSerial = item.serial"
                      @mouseleave="hoveredSerial = null"
                    >
                      <td class="px-6 py-4 text-sm font-semibold text-slate-900 w-12">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-700">
                          {{ item.serial }}
                        </div>
                      </td>
                      <td class="px-6 py-4">
                        <code class="text-sm font-mono text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg break-all">
                          {{ item.code }}
                        </code>
                      </td>
                      <td class="px-6 py-4 text-center">
                        <!-- Show checkmark after copy -->
                        <div v-if="copiedSerial === item.serial" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-100 rounded-full animate-pulse">
                          <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                          </svg>
                          <span class="text-sm font-semibold text-emerald-700">Copied!</span>
                        </div>
                        <!-- Show copy button on hover -->
                        <button
                          v-else-if="hoveredSerial === item.serial"
                          @click="copyToClipboard(item.code, item.serial)"
                          class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 rounded-lg transition-colors duration-150 font-semibold text-sm"
                          :title="`Copy ${item.code}`"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                          </svg>
                          Copy
                        </button>
                        <!-- Show status when not hovering or copying -->
                        <div v-else>
                          <div v-if="item.is_reverified" class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span class="text-sm font-semibold text-green-700">Verified</span>
                          </div>
                          <div v-else class="text-slate-300 text-sm">—</div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Empty State -->
              <div v-else class="text-center py-16 px-6">
                <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-slate-900 mb-1">No Receipt Codes Yet</h3>
                <p class="text-slate-600">No votes have been cast in this election.</p>
              </div>
            </div>
          </div>

          <!-- Sidebar Stats -->
          <div class="lg:col-span-1">
            <!-- Verification Statistics -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 sticky top-24">
              <h3 class="text-lg font-bold text-slate-900 mb-6">Verification Summary</h3>

              <div class="space-y-6">
                <!-- Verified Count -->
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200">
                  <div class="text-3xl font-black text-green-600 mb-2">{{ reverified_count }}</div>
                  <div class="text-sm font-semibold text-green-900">Verified Votes</div>
                  <div class="text-xs text-green-700 mt-1">of {{ total_votes }} total</div>
                </div>

                <!-- Progress Bar -->
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="font-medium text-slate-600">Verification Rate</span>
                    <span class="font-bold text-slate-900">{{ total_votes > 0 ? Math.round((reverified_count / total_votes) * 100) : 0 }}%</span>
                  </div>
                  <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                    <div
                      class="bg-gradient-to-r from-green-500 to-emerald-600 h-full rounded-full transition-all duration-500"
                      :style="{ width: total_votes > 0 ? `${(reverified_count / total_votes) * 100}%` : '0%' }"
                    ></div>
                  </div>
                </div>

                <!-- Total Votes -->
                <div class="pt-4 border-t border-slate-200">
                  <div class="text-sm text-slate-600 mb-2">Total Votes Cast</div>
                  <div class="text-2xl font-black text-slate-900">{{ total_votes }}</div>
                </div>

                <!-- Last Updated -->
                <div class="text-xs text-slate-500 text-center pt-4 border-t border-slate-200">
                  Updated {{ last_updated }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Help Section -->
        <div class="rounded-2xl bg-gradient-to-r from-slate-100 to-slate-50 border border-slate-200 p-8">
          <h2 class="text-xl font-bold text-slate-900 mb-6">How Verification Works</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex gap-4">
              <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">1</div>
              <div>
                <h4 class="font-semibold text-slate-900 mb-1">Find Your Code</h4>
                <p class="text-sm text-slate-600">Check your confirmation email for your unique receipt code.</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">2</div>
              <div>
                <h4 class="font-semibold text-slate-900 mb-1">Locate Here</h4>
                <p class="text-sm text-slate-600">Search for your code in this randomized list above.</p>
              </div>
            </div>
            <div class="flex gap-4">
              <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">3</div>
              <div>
                <h4 class="font-semibold text-slate-900 mb-1">Confirm Vote</h4>
                <p class="text-sm text-slate-600">Click the button on your vote to mark it as verified.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </PublicDigitLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue';

const hoveredSerial = ref(null);
const copiedSerial = ref(null);

const copyToClipboard = (code, serial) => {
  navigator.clipboard.writeText(code).then(() => {
    copiedSerial.value = serial;
    setTimeout(() => {
      copiedSerial.value = null;
      hoveredSerial.value = null;
    }, 2000);
  });
};

defineProps({
  election: {
    type: Object,
    required: true
  },
  organisation: {
    type: Object,
    required: true
  },
  receipt_codes: {
    type: Array,
    required: true
  },
  total_votes: {
    type: Number,
    required: true
  },
  reverified_count: {
    type: Number,
    required: true
  },
  published_at: {
    type: String,
    required: true
  },
  last_updated: {
    type: String,
    required: true
  }
});
</script>

