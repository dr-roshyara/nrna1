<template>
  <div class="min-h-screen flex flex-col bg-slate-200">
    <!-- Header -->
    <PublicDigitHeader :disableLanguageSelector="false" />

    <!-- Main Content -->
    <main class="flex-1 py-12 md:py-16 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto">
        <!-- Information & Guidance Panel -->
        <div class="mb-12 animate-fade-in">
          <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-lg">
            <!-- Tab Navigation -->
            <div class="flex border-b border-slate-200 bg-slate-50">
              <button
                v-for="tab in infoTabs"
                :key="tab.id"
                @click="activeInfoTab = tab.id"
                :class="
                  activeInfoTab === tab.id
                    ? 'border-b-2 border-amber-600 text-amber-600 bg-white'
                    : 'text-slate-600 hover:text-slate-900'
                "
                class="flex-1 px-6 py-4 text-center font-semibold text-sm transition-all duration-200 border-b-2 border-transparent"
              >
                <span class="flex items-center justify-center gap-2">
                  <span v-html="tab.icon" class="w-5 h-5"></span>
                  {{ tab.label }}
                </span>
              </button>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
              <!-- Tab 1: What are Membership Modes? -->
              <div v-show="activeInfoTab === 'what'" class="animate-fade-in">
                <h3 class="text-xl font-bold text-slate-900 mb-4">What are Membership Modes?</h3>
                <p class="text-slate-700 mb-6 leading-relaxed">
                  Your organisation can configure how voters are eligible to participate in elections. This fundamental setting affects who can vote, what data is tracked, and how your election operates.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="p-4 bg-primary-50 border border-primary-200 rounded-lg">
                    <h4 class="font-semibold text-primary-900 mb-2">Full Membership Mode</h4>
                    <p class="text-sm text-primary-800">Formal member tracking with fees, membership types, and expiration dates</p>
                  </div>
                  <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <h4 class="font-semibold text-emerald-900 mb-2">Election-Only Mode</h4>
                    <p class="text-sm text-emerald-800">Simple approach: any organisation user can vote</p>
                  </div>
                </div>
              </div>

              <!-- Tab 2: Full Membership Mode -->
              <div v-show="activeInfoTab === 'full'" class="animate-fade-in">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Full Membership Mode</h3>
                <p class="text-slate-700 mb-6 leading-relaxed">
                  Best for organisations with formal membership structures that need to track fees, membership types, and renewal dates.
                </p>

                <div class="space-y-4 mb-6">
                  <div class="flex gap-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-primary-100">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">Member Records Required</h4>
                      <p class="text-slate-600 text-sm">Every voter must have an active member record in your system</p>
                    </div>
                  </div>

                  <div class="flex gap-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-primary-100">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">Fees & Status Tracking</h4>
                      <p class="text-slate-600 text-sm">Track paid, exempt, or pending fees for each member</p>
                    </div>
                  </div>

                  <div class="flex gap-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-primary-100">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">Membership Types & Expiration</h4>
                      <p class="text-slate-600 text-sm">Define membership tiers and track renewal dates</p>
                    </div>
                  </div>
                </div>

                <div class="p-4 bg-primary-50 border border-primary-200 rounded-lg">
                  <p class="text-sm text-primary-900"><strong>Use this if:</strong> You have formal membership structures, charge fees, have membership tiers, or need detailed member auditing.</p>
                </div>
              </div>

              <!-- Tab 3: Election-Only Mode -->
              <div v-show="activeInfoTab === 'election'" class="animate-fade-in">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Election-Only Mode</h3>
                <p class="text-slate-700 mb-6 leading-relaxed">
                  Best for organisations that want to run elections without formal membership management. Any user registered in your organisation can vote.
                </p>

                <div class="space-y-4 mb-6">
                  <div class="flex gap-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-emerald-100">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">Simple Setup</h4>
                      <p class="text-slate-600 text-sm">No member records needed - organisation users automatically eligible</p>
                    </div>
                  </div>

                  <div class="flex gap-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-emerald-100">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">No Fees or Status</h4>
                      <p class="text-slate-600 text-sm">No membership fees, status tracking, or renewal management</p>
                    </div>
                  </div>

                  <div class="flex gap-4">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-emerald-100">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div>
                      <h4 class="font-semibold text-slate-900">Broad Participation</h4>
                      <p class="text-slate-600 text-sm">Open elections to all registered organisation users</p>
                    </div>
                  </div>
                </div>

                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                  <p class="text-sm text-emerald-900"><strong>Use this if:</strong> You want simple elections without membership overhead, want all users to participate, or prefer no fee tracking.</p>
                </div>
              </div>

              <!-- Tab 4: Mode Transitions -->
              <div v-show="activeInfoTab === 'transitions'" class="animate-fade-in">
                <h3 class="text-xl font-bold text-slate-900 mb-6">What Happens When You Switch Modes?</h3>

                <!-- Switching from Full to Election-Only -->
                <div class="mb-8">
                  <h4 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="text-rose-600">Full Membership</span>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    <span class="text-emerald-600">Election-Only</span>
                  </h4>

                  <div class="space-y-3 mb-4">
                    <div class="p-3 bg-slate-100 border-l-4 border-amber-500 rounded">
                      <p class="text-sm text-slate-700"><strong>✓ Member records preserved:</strong> All existing member data stays in the system (not deleted)</p>
                    </div>
                    <div class="p-3 bg-slate-100 border-l-4 border-green-500 rounded">
                      <p class="text-sm text-slate-700"><strong>✓ All org users eligible:</strong> Any registered organisation user can now vote (not just members)</p>
                    </div>
                    <div class="p-3 bg-slate-100 border-l-4 border-amber-500 rounded">
                      <p class="text-sm text-slate-700"><strong>⚠ Membership checks removed:</strong> Fees, membership types, and expiration dates are no longer checked</p>
                    </div>
                    <div class="p-3 bg-slate-100 border-l-4 border-primary-500 rounded">
                      <p class="text-sm text-slate-700"><strong>✓ Elections unaffected:</strong> Existing elections continue to work normally</p>
                    </div>
                  </div>

                  <div class="p-4 bg-rose-50 border border-rose-200 rounded-lg">
                    <p class="text-sm text-rose-900"><strong>⚠ Important:</strong> Once switched to Election-Only, ensure you're comfortable with <strong>all organisation users</strong> being able to vote, regardless of member status.</p>
                  </div>
                </div>

                <!-- Switching from Election-Only to Full -->
                <div>
                  <h4 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="text-emerald-600">Election-Only</span>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    <span class="text-rose-600">Full Membership</span>
                  </h4>

                  <div class="space-y-3 mb-4">
                    <div class="p-3 bg-slate-100 border-l-4 border-amber-500 rounded">
                      <p class="text-sm text-slate-700"><strong>⚠ Member records required:</strong> Voters must have active member records to vote in future elections</p>
                    </div>
                    <div class="p-3 bg-slate-100 border-l-4 border-amber-500 rounded">
                      <p class="text-sm text-slate-700"><strong>⚠ Import members first:</strong> You'll need to import or create member records before elections can run</p>
                    </div>
                    <div class="p-3 bg-slate-100 border-l-4 border-primary-500 rounded">
                      <p class="text-sm text-slate-700"><strong>✓ Previous users not auto-imported:</strong> Users from Election-Only elections must be manually added as members</p>
                    </div>
                    <div class="p-3 bg-slate-100 border-l-4 border-green-500 rounded">
                      <p class="text-sm text-slate-700"><strong>✓ Existing elections preserved:</strong> Previous election results are unaffected</p>
                    </div>
                  </div>

                  <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-900"><strong>⚠ Action required:</strong> Before switching, plan how you'll manage member records, fees, and membership types for your organisation.</p>
                  </div>
                </div>
              </div>

              <!-- Info Panel Footer with Learn More Link -->
              <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between">
                <p class="text-sm text-slate-600">Need more details about membership modes?</p>
                <a
                  href="/help/membership-modes"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors duration-200"
                >
                  <span>Learn More</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Page Title Section -->
        <div class="mb-12 animate-fade-in">
          <div class="inline-flex items-center gap-2 mb-4 px-3 py-1 bg-gold/20 border border-gold/40 rounded-full">
            <svg class="w-4 h-4 text-gold" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10.5 1.5H19a.5.5 0 01.5.5v8a.5.5 0 01-.5.5h-8.5V19a.5.5 0 01-.5.5H1a.5.5 0 01-.5-.5v-8a.5.5 0 01.5-.5H9V2a.5.5 0 01.5-.5z"/>
            </svg>
            <span class="text-xs font-semibold text-gold uppercase tracking-wider">Settings</span>
          </div>
          <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-3 leading-tight">
            {{ organisation.name }}
          </h1>
          <p class="text-lg text-slate-600 max-w-2xl">
            Configure how voters are eligible to participate in elections
          </p>
        </div>

        <!-- Language Preference Card -->
        <div class="bg-white border border-gold/30 rounded-2xl overflow-hidden shadow-xl mb-8 animate-fade-in-up" style="animation-delay: 30ms">
          <!-- Card Header -->
          <div class="px-6 md:px-8 py-6 md:py-8 border-b border-gold/20 bg-gradient-to-r from-slate-50 to-transparent">
            <div>
              <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">Default Language</h2>
              <p class="text-slate-600">Set the default language for your organisation. Users will still be able to change their preference.</p>
            </div>
          </div>

          <!-- Card Content -->
          <div class="px-6 md:px-8 py-8 space-y-6">
            <!-- Language Selector -->
            <div class="space-y-3">
              <label for="default-language" class="block text-sm font-semibold text-slate-900">
                Default Language
              </label>
              <select
                id="default-language"
                v-model="langForm.default_language"
                class="w-full px-4 py-3 border border-slate-300 rounded-lg shadow-sm focus:border-gold focus:ring-2 focus:ring-gold/20 text-slate-900 bg-white"
              >
                <option :value="null">Auto-detect (Browser/Geo-location)</option>
                <option value="de">Deutsch (German)</option>
                <option value="en">English</option>
                <option value="np">नेपाली (Nepali)</option>
              </select>
              <p class="text-sm text-slate-600 mt-2">
                When set, all users in this organisation will initially see the interface in this language.
                They can always change it in the language switcher.
              </p>
            </div>

            <!-- Info Box -->
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
              <p class="text-sm text-amber-900">
                <strong>Note:</strong> This setting applies to new sessions. Existing users' preferences are preserved unless they clear their settings.
              </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
              <button
                type="button"
                @click="resetLangForm"
                :disabled="langForm.processing"
                class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 border border-slate-300 rounded-lg hover:bg-slate-200 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-slate-200 disabled:opacity-50 transition-all duration-200"
              >
                Cancel
              </button>
              <button
                type="button"
                @click="submitLang"
                :disabled="!hasLangChanges || langForm.processing"
                class="px-8 py-3 rounded-xl font-bold text-sm uppercase tracking-widest transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-200 disabled:cursor-not-allowed"
                :class="
                  !hasLangChanges || langForm.processing
                    ? 'bg-slate-300 text-slate-500'
                    : 'bg-gradient-to-br from-amber-600 via-amber-700 to-amber-800 text-white shadow-xl shadow-amber-700/40 hover:shadow-2xl hover:shadow-amber-700/50 hover:-translate-y-0.5 focus:ring-amber-600'
                "
              >
                <span class="flex items-center justify-center gap-2.5">
                  <svg v-if="!langForm.processing" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  <svg v-else class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ langForm.processing ? 'Saving...' : 'Save Language' }}
                </span>
              </button>
            </div>
          </div>
        </div>

        <!-- Main Settings Card -->
        <div class="bg-white border border-gold/30 rounded-2xl overflow-hidden shadow-xl mb-8 animate-fade-in-up" style="animation-delay: 50ms">
          <!-- Card Header -->
          <div class="px-6 md:px-8 py-6 md:py-8 border-b border-gold/20 bg-gradient-to-r from-slate-50 to-transparent">
            <div class="flex items-start justify-between">
              <div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">Membership System</h2>
                <p class="text-slate-600">Choose how voters are eligible for elections in this organisation</p>
              </div>
              <!-- Current Mode Badge -->
              <div class="flex-shrink-0">
                <div
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-sm border"
                  :class="
                    organisation.uses_full_membership
                      ? 'bg-primary-100 border-primary-300 text-primary-900'
                      : 'bg-emerald-100 border-emerald-300 text-emerald-900'
                  "
                >
                  <span
                    class="inline-block w-2.5 h-2.5 rounded-full"
                    :class="
                      organisation.uses_full_membership
                        ? 'bg-primary-600'
                        : 'bg-emerald-600'
                    "
                  ></span>
                  <span>{{ organisation.uses_full_membership ? 'Full Membership' : 'Election-Only' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Card Content -->
          <div class="px-6 md:px-8 py-8 space-y-8">
            <!-- Toggle Section -->
            <div class="space-y-4">
              <div class="group relative flex items-start gap-6 p-6 border border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all duration-300 cursor-pointer">
                <div class="flex-1 min-w-0">
                  <h3 class="text-lg font-semibold text-slate-900 group-hover:text-gold transition-colors mb-2">
                    Require Full Membership
                  </h3>
                  <p class="text-slate-700 text-sm leading-relaxed mb-4">
                    When enabled, voters must be active members with paid or exempt fees. Recommended for organisations with formal membership tracking.
                  </p>

                  <!-- Current Status Indicator -->
                  <div v-if="organisation.uses_full_membership" class="inline-flex items-center gap-2 text-xs font-medium text-primary-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Currently enabled</span>
                  </div>
                </div>

                <!-- Toggle Switch -->
                <div class="flex-shrink-0 pt-1">
                  <button
                    type="button"
                    @click="toggleMode"
                    :disabled="form.processing"
                    :aria-label="`${form.uses_full_membership ? 'Disable' : 'Enable'} full membership requirement`"
                    class="relative inline-flex flex-shrink-0 h-7 w-14 border-2 rounded-full cursor-pointer transition-all ease-in-out duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="
                      form.uses_full_membership
                        ? 'bg-gradient-to-r from-blue-600 to-blue-500 border-primary-600 shadow-lg shadow-blue-500/50'
                        : 'bg-slate-300 border-slate-400'
                    "
                  >
                    <span
                      aria-hidden="true"
                      class="pointer-events-none inline-block h-6 w-6 rounded-full bg-white shadow-md transform ring-0 transition ease-in-out duration-300"
                      :class="
                        form.uses_full_membership
                          ? 'translate-x-7'
                          : 'translate-x-0.5'
                      "
                    />
                  </button>
                </div>
              </div>
            </div>

            <!-- Warning Alert for Mode Change -->
            <Transition
              enter-active-class="transition ease-out duration-300"
              enter-from-class="opacity-0 translate-y-4 scale-95"
              enter-to-class="opacity-100 translate-y-0 scale-100"
              leave-active-class="transition ease-in duration-200"
              leave-from-class="opacity-100 translate-y-0 scale-100"
              leave-to-class="opacity-0 translate-y-4 scale-95"
            >
              <div v-if="showWarning" class="p-5 border border-amber-300 bg-amber-50 rounded-xl">
                <div class="flex gap-4">
                  <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-sm font-bold text-amber-900 mb-3">Confirm Mode Change</h3>
                    <p class="text-sm text-amber-800 mb-4 leading-relaxed">
                      This organisation has <strong>{{ memberCount }} active member{{ memberCount !== 1 ? 's' : '' }}</strong>.
                      Switching to election-only mode will allow <strong>any registered user</strong> to vote, bypassing membership requirements.
                      This action cannot be undone without manual intervention.
                    </p>
                    <label class="flex items-center gap-3 cursor-pointer group">
                      <input
                        type="checkbox"
                        v-model="form.confirm_mode_change"
                        class="h-5 w-5 border-amber-300 rounded-lg text-amber-600 focus:ring-2 focus:ring-amber-400 cursor-pointer transition-all"
                      />
                      <span class="text-sm text-amber-900 font-medium group-hover:text-amber-950">I understand and want to proceed with this change</span>
                    </label>
                  </div>
                </div>
              </div>
            </Transition>

            <!-- Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="p-5 border border-primary-200 bg-primary-50 rounded-xl">
                <p class="text-xs font-semibold text-primary-700 uppercase tracking-wider mb-2">Active Members</p>
                <p class="text-3xl font-bold text-primary-900">{{ memberCount }}</p>
                <p class="text-xs text-primary-600 mt-2">Members eligible to vote</p>
              </div>
              <div class="p-5 border border-gold/40 bg-gold/5 rounded-xl">
                <p class="text-xs font-semibold text-gold uppercase tracking-wider mb-2">Current Configuration</p>
                <p class="text-3xl font-bold text-gold">{{ organisation.uses_full_membership ? 'Restricted' : 'Open' }}</p>
                <p class="text-xs text-gold mt-2">{{ organisation.uses_full_membership ? 'Membership required' : 'All users eligible' }}</p>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
              <button
                type="button"
                @click="resetForm"
                :disabled="form.processing"
                class="px-6 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 border border-slate-300 rounded-lg hover:bg-slate-200 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-slate-200 disabled:opacity-50 transition-all duration-200"
              >
                Cancel
              </button>
              <button
                type="button"
                @click="submit"
                :disabled="!hasChanges || form.processing || (showWarning && !form.confirm_mode_change)"
                :aria-label="form.processing ? 'Saving changes' : 'Save changes'"
                class="group relative px-8 py-3 overflow-hidden rounded-xl font-bold text-sm uppercase tracking-widest transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-200 disabled:cursor-not-allowed"
                :class="
                  !hasChanges || form.processing || (showWarning && !form.confirm_mode_change)
                    ? 'bg-slate-300 text-slate-500'
                    : 'bg-gradient-to-br from-amber-600 via-amber-700 to-amber-800 text-white shadow-xl shadow-amber-700/40 hover:shadow-2xl hover:shadow-amber-700/50 hover:-translate-y-0.5 focus:ring-amber-600'
                "
              >
                <!-- Animated background gradient (appears on hover) -->
                <div v-if="hasChanges && !form.processing && !(showWarning && !form.confirm_mode_change)"
                     class="absolute inset-0 bg-gradient-to-r from-gold/0 via-white/20 to-gold/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <!-- Shimmer effect on hover -->
                <div v-if="hasChanges && !form.processing && !(showWarning && !form.confirm_mode_change)"
                     class="absolute inset-0 -top-1 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-0 group-hover:opacity-100 group-hover:animate-pulse"></div>

                <!-- Button Content -->
                <span class="relative flex items-center justify-center gap-2.5">
                  <!-- Checkmark Icon -->
                  <svg v-if="!form.processing"
                       class="w-5 h-5 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12"
                       :class="hasChanges && !form.processing && !(showWarning && !form.confirm_mode_change) ? 'opacity-100' : 'opacity-70'"
                       fill="currentColor"
                       viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>

                  <!-- Loading Spinner -->
                  <svg v-else
                       class="w-5 h-5 animate-spin origin-center"
                       fill="none"
                       viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>

                  <!-- Text -->
                  <span class="transition-all duration-300">
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                  </span>
                </span>
              </button>
            </div>
          </div>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 animate-fade-in-up" style="animation-delay: 100ms">
          <!-- Full Membership Card -->
          <div class="bg-white border border-primary-200 rounded-xl p-6 hover:border-primary-300 hover:shadow-md transition-all duration-300">
            <div class="flex items-start gap-3 mb-4">
              <div class="p-2 bg-primary-100 rounded-lg">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">Full Membership Mode</h3>
            </div>
            <ul class="space-y-3">
              <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-slate-700">Voters must have active memberships</span>
              </li>
              <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-slate-700">Membership fees must be paid or exempt</span>
              </li>
              <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-primary-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-slate-700">Best for formal membership tracking</span>
              </li>
            </ul>
          </div>

          <!-- Election-Only Card -->
          <div class="bg-white border border-emerald-200 rounded-xl p-6 hover:border-emerald-300 hover:shadow-md transition-all duration-300">
            <div class="flex items-start gap-3 mb-4">
              <div class="p-2 bg-emerald-100 rounded-lg">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1m2-1v2.5M3 7l2 1M3 7l2-1m-2 1v2.5" />
                </svg>
              </div>
              <h3 class="text-lg font-bold text-slate-900">Election-Only Mode</h3>
            </div>
            <ul class="space-y-3">
              <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-slate-700">Any registered user can vote</span>
              </li>
              <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-slate-700">No membership fees required</span>
              </li>
              <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-slate-700">Quick setup without membership</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useMeta } from '@/composables/useMeta';
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue';
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue';

const props = defineProps({
  organisation: {
    type: Object,
    required: true,
  },
  memberCount: {
    type: Number,
    required: true,
  },
});

useMeta({
  pageKey: 'organisations.settings',
  params: {
    organisationName: props.organisation.name,
    memberCount: props.memberCount
  }
});

// Info panel tabs state
const activeInfoTab = ref('what');

const infoTabs = [
  {
    id: 'what',
    label: 'Overview',
    icon: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd" /></svg>'
  },
  {
    id: 'full',
    label: 'Full Membership',
    icon: '<svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" /></svg>'
  },
  {
    id: 'election',
    label: 'Election-Only',
    icon: '<svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 10a6 6 0 016 6v2H3v-2a6 6 0 016-6zM13 16h6v2h-6v-2z" /></svg>'
  },
  {
    id: 'transitions',
    label: 'Mode Transitions',
    icon: '<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>'
  },
];

const form = useForm({
  uses_full_membership: props.organisation.uses_full_membership,
  confirm_mode_change: false,
});

const langForm = useForm({
  default_language: props.organisation.default_language,
});

const hasChanges = computed(() => {
  return form.uses_full_membership !== props.organisation.uses_full_membership;
});

const hasLangChanges = computed(() => {
  return langForm.default_language !== props.organisation.default_language;
});

const showWarning = computed(() => {
  return (
    props.organisation.uses_full_membership &&
    !form.uses_full_membership &&
    props.memberCount > 0
  );
});

const toggleMode = () => {
  if (form.processing) return;
  form.uses_full_membership = !form.uses_full_membership;
};

const resetForm = () => {
  form.reset();
  form.clearErrors();
};

const submit = () => {
  form.patch(
    route('organisations.settings.update-membership-mode', props.organisation.slug),
    {
      preserveScroll: true,
      onSuccess: () => {
        form.confirm_mode_change = false;
      },
    }
  );
};

const resetLangForm = () => {
  langForm.reset();
  langForm.clearErrors();
};

const submitLang = () => {
  langForm.patch(
    route('organisations.settings.update-language', props.organisation.slug),
    {
      preserveScroll: true,
    }
  );
};
</script>

<style scoped>
/* ============================================
   GOLD ACCENT TOKENS
   ============================================ */
:root {
  --gold: #b5862b;
  --gold-dark: #92400e;
  --gold-light: #d4a84b;
}

.text-gold { color: var(--gold); }
.text-gold-dark { color: var(--gold-dark); }
.bg-gold { background-color: var(--gold); }
.border-gold { border-color: var(--gold); }

/* Fractional opacity */
.border-gold\/20 { border-color: rgba(181, 134, 43, 0.2); }
.border-gold\/30 { border-color: rgba(181, 134, 43, 0.3); }
.bg-gold\/10 { background-color: rgba(181, 134, 43, 0.1); }
.text-gold\/70 { color: rgba(181, 134, 43, 0.7); }

/* Focus states */
.focus\:ring-gold:focus {
  --tw-ring-color: rgba(181, 134, 43, 0.6);
}

/* ============================================
   PAGE ANIMATIONS
   ============================================ */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out forwards;
}

.animate-fade-in-up {
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

.group-hover\:animate-shimmer:hover {
  animation: shimmer 0.6s ease-in-out infinite;
}

/* ============================================
   SMOOTH TRANSITIONS
   ============================================ */
input[type="checkbox"],
button {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* ============================================
   ACCESSIBILITY
   ============================================ */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* ============================================
   DARK MODE OPTIMIZED
   ============================================ */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: transparent;
}

::-webkit-scrollbar-thumb {
  background: rgba(181, 134, 43, 0.3);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(181, 134, 43, 0.5);
}
</style>

