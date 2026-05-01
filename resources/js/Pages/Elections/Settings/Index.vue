<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ToggleSwitch from '@/Components/ToggleSwitch.vue'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const page = usePage()

const props = defineProps({
  election: Object,
  organisation: Object,
  hasVotes: Boolean
})

const form = ref({
  ip_restriction_enabled: props.election.ip_restriction_enabled ?? false,
  ip_restriction_max_per_ip: props.election.ip_restriction_max_per_ip ?? 4,
  ip_whitelist: props.election.ip_whitelist ?? [],
  no_vote_option_enabled: props.election.no_vote_option_enabled ?? false,
  no_vote_option_label: props.election.no_vote_option_label ?? 'No vote / Abstain',
  selection_constraint_type: props.election.selection_constraint_type ?? 'maximum',
  selection_constraint_min: props.election.selection_constraint_min ?? null,
  selection_constraint_max: props.election.selection_constraint_max ?? null,
  voter_verification_mode: props.election.voter_verification_mode ?? 'none',
  settings_version: props.election.settings_version ?? 0,
  confirmed_active_changes: false,
  agreed_to_settings: false,
})

const whitelistText = ref((props.election.ip_whitelist ?? []).join('\n'))
const whitelistError = ref('')
const isValidating = ref(false)

const isValidIpOrCidr = (value) => {
  const trimmed = value.trim()
  if (!trimmed) return false

  if (trimmed.includes('/')) {
    const [ip, bits] = trimmed.split('/')
    const bitsNum = parseInt(bits, 10)
    if (isNaN(bitsNum) || bitsNum < 0 || bitsNum > 32) return false
    const parts = ip.split('.')
    if (parts.length !== 4) return false
    return parts.every(p => {
      const n = parseInt(p, 10)
      return !isNaN(n) && n >= 0 && n <= 255
    })
  }

  const parts = trimmed.split('.')
  if (parts.length !== 4) return false
  return parts.every(p => {
    const n = parseInt(p, 10)
    return !isNaN(n) && n >= 0 && n <= 255
  })
}

const validateWhitelist = () => {
  const lines = whitelistText.value.split('\n').filter(l => l.trim())
  const invalid = lines.filter(l => !isValidIpOrCidr(l))
  whitelistError.value = invalid.length ? `Invalid: ${invalid.map(l => l.trim()).join(', ')}` : ''
  form.value.ip_whitelist = lines.map(l => l.trim())
  return !invalid.length
}

const submit = () => {
  if (!validateWhitelist()) return
  isValidating.value = true

  router.patch(route('elections.settings.update', props.election.slug), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      form.value.settings_version++
      isValidating.value = false
    },
    onError: () => {
      isValidating.value = false
    }
  })
}

const selectionConstraintTypes = [
  { value: 'any', label: 'Any number (voters choose freely)', description: 'Open ballot' },
  { value: 'exact', label: 'Exactly N candidates', description: 'Must match count' },
  { value: 'range', label: 'Between min and max candidates', description: 'Flexible range' },
  { value: 'minimum', label: 'At least N candidates', description: 'Minimum required' },
  { value: 'maximum', label: 'At most N candidates', description: 'Maximum allowed' }
]

const verificationModes = [
  { value: 'none', label: 'None (standard)', description: 'No per-voter identity verification. Voters access the ballot with their login credentials only.' },
  { value: 'ip_only', label: 'IP Address Only', description: 'Voter must vote from the pre-verified IP address during the video call.' },
  { value: 'fingerprint_only', label: 'Device Fingerprint Only', description: 'Voter must vote from the pre-verified device. Works across different networks.' },
  { value: 'both', label: 'Both — IP + Device (Strictest)', description: 'Voter must match both the verified IP address and device. Highest security.' }
]

const isShowingNeedsConfirmation = computed(() => {
  return props.election.is_active && props.hasVotes
})

const flashSuccess = computed(() => {
  return page.props.flash?.success || null
})

const flashWarning = computed(() => {
  return page.props.flash?.warning || null
})
</script>

<template>
  <ElectionLayout :election="election" :organisation="organisation">
    <!-- Modern Democratic Design: Bold colors, geometric forms, transparency -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-5xl mx-auto">

        <!-- Header with strategic layout -->
        <div class="mb-12">
          <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
            <div>
              <h1 class="text-4xl font-bold text-slate-900 mb-2 tracking-tight">
                Election Settings
              </h1>
              <p class="text-lg text-slate-600 max-w-xl">
                Configure voting rules and democratic constraints. Every setting affects your voters' experience.
              </p>
            </div>
            <a
              href="/help/election-setup"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Open Election Setup Guide in new window"
              class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-200 transition-all duration-200 whitespace-nowrap group"
            >
              <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Setup Guide</span>
            </a>
          </div>
          <div class="geometric-divider" role="presentation"></div>
        </div>

        <!-- Flash Messages with better a11y -->
        <div v-if="flashSuccess" class="mb-6 p-5 bg-emerald-50 border-l-4 border-emerald-600 rounded-lg flex items-start gap-4 animate-fadeIn" role="status" aria-live="polite" aria-label="Success message">
          <svg class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="font-semibold text-emerald-900">Success</p>
            <p class="text-emerald-800 mt-1">{{ flashSuccess }}</p>
          </div>
        </div>

        <div v-if="flashWarning" class="mb-6 p-5 bg-amber-50 border-l-4 border-amber-600 rounded-lg flex items-start gap-4 animate-fadeIn" role="alert" aria-live="assertive" aria-label="Warning message">
          <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="font-semibold text-amber-900">Attention Required</p>
            <p class="text-amber-800 mt-1">{{ flashWarning }}</p>
          </div>
        </div>

        <!-- Main form with improved semantic structure -->
        <form @submit.prevent="submit" class="space-y-8" novalidate aria-label="Election settings form">
        <!-- 1. Voter Access Control Section -->
        <section class="card-elevated" aria-labelledby="access-heading">
          <div class="section-header">
            <h2 id="access-heading" class="text-2xl font-bold text-slate-900 flex items-center gap-3">
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-teal-100 text-teal-700 font-bold text-lg" aria-hidden="true">1</span>
              Voter Access Control
            </h2>
            <p class="text-slate-600 mt-2 text-lg">Manage how voters can access and submit their ballots.</p>
          </div>

          <div class="space-y-6">
            <!-- IP Restriction Toggle -->
            <fieldset class="flex items-start justify-between gap-4 pb-6 border-b border-slate-200">
              <div class="flex-1">
                <legend class="text-lg font-semibold text-slate-900 mb-2">IP Address Restriction</legend>
                <p class="text-slate-600 text-sm leading-relaxed">
                  Limit the number of votes submitted from any single IP address. Prevents multi-voting from shared networks.
                </p>
              </div>
              <div class="flex-shrink-0">
                <ToggleSwitch
                  v-model="form.ip_restriction_enabled"
                  :aria-label="`IP restriction is ${form.ip_restriction_enabled ? 'enabled' : 'disabled'}`"
                  role="switch"
                  :aria-checked="form.ip_restriction_enabled"
                />
              </div>
            </fieldset>

            <!-- IP Restriction Settings (collapsed when disabled) -->
            <div v-if="form.ip_restriction_enabled" class="space-y-6 pl-6 border-l-4 border-teal-400 bg-teal-50 p-5 rounded-r-lg" role="region" aria-label="IP restriction settings">
              <!-- Max Per IP Input -->
              <div>
                <label for="ip-max-input" class="block text-sm font-semibold text-slate-900 mb-2">
                  Maximum votes per IP address
                </label>
                <div class="flex items-center gap-3">
                  <input
                    id="ip-max-input"
                    v-model.number="form.ip_restriction_max_per_ip"
                    type="number"
                    min="1"
                    max="50"
                    aria-describedby="ip-max-help"
                    class="w-24 px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 font-semibold text-slate-900 hover:border-slate-400 transition-colors"
                  />
                  <span class="text-sm text-slate-600">votes per location</span>
                </div>
                <p id="ip-max-help" class="text-sm text-slate-600 mt-2">
                  Default: 4. Set to 1 for home voting, increase for shared locations (office, community center).
                </p>
              </div>

              <!-- IP Whitelist Section -->
              <fieldset class="border-t border-slate-300 pt-5">
                <legend class="text-sm font-semibold text-slate-900 mb-3">Trusted IP Addresses (optional)</legend>
                <p class="text-sm text-slate-600 mb-4">
                  IPs listed below bypass the vote limit. Use for organizational servers or voting centers.
                </p>
                <textarea
                  v-model="whitelistText"
                  @blur="validateWhitelist"
                  rows="4"
                  placeholder="10.0.0.1&#10;192.168.1.0/24&#10;172.16.0.0/12"
                  aria-describedby="whitelist-help"
                  aria-label="Whitelisted IP addresses"
                  class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 font-mono text-sm bg-white hover:border-slate-400 transition-colors"
                />
                <p v-if="whitelistError" class="text-danger-600 font-semibold text-sm mt-3 flex items-center gap-2" role="alert">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                  {{ whitelistError }}
                </p>
                <p id="whitelist-help" class="text-sm text-slate-600 mt-2">
                  Format: One per line. Supports individual IPs (192.168.1.1) or CIDR ranges (10.0.0.0/8).
                </p>
              </fieldset>
            </div>
          </div>
        </section>

        <!-- 2. Ballot Options Section -->
        <section class="card-elevated" aria-labelledby="ballot-heading">
          <div class="section-header">
            <h2 id="ballot-heading" class="text-2xl font-bold text-slate-900 flex items-center gap-3">
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-amber-100 text-amber-700 font-bold text-lg" aria-hidden="true">2</span>
              Ballot Options
            </h2>
            <p class="text-slate-600 mt-2 text-lg">Define how candidates are selected and voted on.</p>
          </div>

          <div class="space-y-8">
            <!-- No Vote Option Toggle -->
            <fieldset class="flex items-start justify-between gap-4 pb-6 border-b border-slate-200">
              <div class="flex-1">
                <legend class="text-lg font-semibold text-slate-900 mb-2">Allow Abstention</legend>
                <p class="text-slate-600 text-sm leading-relaxed">
                  Let voters explicitly abstain or register no preference on a position. Different from not voting.
                </p>
              </div>
              <div class="flex-shrink-0">
                <ToggleSwitch
                  v-model="form.no_vote_option_enabled"
                  :aria-label="`Abstention option is ${form.no_vote_option_enabled ? 'enabled' : 'disabled'}`"
                  role="switch"
                  :aria-checked="form.no_vote_option_enabled"
                />
              </div>
            </fieldset>

            <!-- No Vote Label (conditional) -->
            <div v-if="form.no_vote_option_enabled" class="space-y-4 pl-6 border-l-4 border-amber-400 bg-amber-50 p-5 rounded-r-lg" role="region" aria-label="Abstention label settings">
              <div>
                <label for="abstention-label" class="block text-sm font-semibold text-slate-900 mb-2">
                  Abstention label
                </label>
                <input
                  id="abstention-label"
                  v-model="form.no_vote_option_label"
                  type="text"
                  maxlength="100"
                  placeholder="No vote / Abstain"
                  aria-describedby="abstention-label-help"
                  class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-amber-500 text-slate-900 hover:border-slate-400 transition-colors"
                />
                <p id="abstention-label-help" class="text-sm text-slate-600 mt-2">
                  Text shown to voters. Example: "Abstain", "No preference", "None of the above"
                </p>
              </div>
            </div>

            <!-- Selection Constraint Type -->
            <fieldset>
              <legend class="text-lg font-semibold text-slate-900 mb-4">Selection Rule</legend>
              <p class="text-slate-600 text-sm mb-4">
                Define how many candidates voters must or may select per position.
              </p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <label
                  v-for="option in selectionConstraintTypes"
                  :key="option.value"
                  class="relative flex items-start p-4 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-400 hover:bg-teal-50 transition-all"
                  :class="form.selection_constraint_type === option.value ? 'border-teal-500 bg-teal-50' : ''"
                >
                  <input
                    v-model="form.selection_constraint_type"
                    type="radio"
                    :value="option.value"
                    class="mt-1 mr-3"
                  />
                  <div>
                    <p class="font-semibold text-slate-900">{{ option.label }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ option.description }}</p>
                  </div>
                </label>
              </div>
            </fieldset>

            <!-- Constraint Min/Max (conditional fields) -->
            <div v-if="['range', 'minimum'].includes(form.selection_constraint_type)" class="space-y-4 pt-4 border-t border-slate-200">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="constraint-min" class="block text-sm font-semibold text-slate-900 mb-2">
                    Minimum candidates
                  </label>
                  <input
                    id="constraint-min"
                    v-model.number="form.selection_constraint_min"
                    type="number"
                    min="0"
                    aria-describedby="constraint-min-help"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 text-slate-900 hover:border-slate-400 transition-colors"
                  />
                  <p id="constraint-min-help" class="text-xs text-slate-600 mt-2">At least this many candidates</p>
                </div>
                <div v-if="form.selection_constraint_type === 'range'">
                  <label for="constraint-max" class="block text-sm font-semibold text-slate-900 mb-2">
                    Maximum candidates
                  </label>
                  <input
                    id="constraint-max"
                    v-model.number="form.selection_constraint_max"
                    type="number"
                    min="1"
                    aria-describedby="constraint-max-help"
                    class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 text-slate-900 hover:border-slate-400 transition-colors"
                  />
                  <p id="constraint-max-help" class="text-xs text-slate-600 mt-2">No more than this many</p>
                </div>
              </div>
            </div>

            <div v-else-if="['exact', 'maximum'].includes(form.selection_constraint_type)" class="space-y-4 pt-4 border-t border-slate-200">
              <div class="w-full md:w-1/2">
                <label for="constraint-exact" class="block text-sm font-semibold text-slate-900 mb-2">
                  {{ form.selection_constraint_type === 'exact' ? 'Exact count' : 'Maximum allowed' }}
                </label>
                <input
                  id="constraint-exact"
                  v-model.number="form.selection_constraint_max"
                  type="number"
                  min="1"
                  :aria-describedby="`constraint-${form.selection_constraint_type}-help`"
                  class="w-full px-4 py-3 border-2 border-slate-300 rounded-lg focus:outline-none focus:border-teal-500 text-slate-900 hover:border-slate-400 transition-colors"
                />
                <p :id="`constraint-${form.selection_constraint_type}-help`" class="text-xs text-slate-600 mt-2">
                  {{ form.selection_constraint_type === 'exact' ? 'Voters must select exactly this many' : 'Voters can select up to this many' }}
                </p>
              </div>
            </div>
          </div>
        </section>

        <!-- 3. Voter Verification Mode Section -->
        <section class="card-elevated" aria-labelledby="verification-heading">
          <div class="section-header">
            <h2 id="verification-heading" class="text-sm font-bold text-slate-900 uppercase tracking-widest flex items-center gap-3">
              <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 text-white font-bold text-sm">3</span>
              Voter Verification Mode
            </h2>
            <p class="text-slate-600 text-sm mt-2">Optional: Require admins to verify each voter's identity via video call before they can vote.</p>
          </div>

          <div class="space-y-4">
            <!-- Verification mode radio group -->
            <fieldset class="space-y-3">
              <legend class="sr-only">Select voter verification mode</legend>
              <div
                v-for="mode in verificationModes"
                :key="mode.value"
                @click="form.voter_verification_mode = mode.value"
                class="p-4 border-2 rounded-lg cursor-pointer transition-all duration-200"
                :class="form.voter_verification_mode === mode.value
                  ? 'border-purple-400 bg-purple-50'
                  : 'border-slate-200 bg-white hover:border-slate-300'"
              >
                <label class="flex items-start gap-3 cursor-pointer">
                  <input
                    :value="mode.value"
                    v-model="form.voter_verification_mode"
                    type="radio"
                    class="mt-1 w-4 h-4 accent-purple-600 cursor-pointer flex-shrink-0"
                  />
                  <div class="flex-1">
                    <p class="font-semibold text-slate-900">{{ mode.label }}</p>
                    <p class="text-sm text-slate-600 mt-1">{{ mode.description }}</p>
                  </div>
                </label>
              </div>
            </fieldset>

            <!-- Info box about verification workflow -->
            <div v-if="form.voter_verification_mode !== 'none'" class="p-4 bg-purple-50 border-l-4 border-purple-600 rounded">
              <p class="text-sm text-purple-900 font-semibold mb-2">Verification Workflow</p>
              <ol class="text-sm text-purple-800 space-y-1 ml-4 list-decimal">
                <li>Open the "Voters" tab for this election</li>
                <li>For each voter, click the "Verify" button</li>
                <li>Conduct a video or audio call to confirm their identity</li>
                <li>Record their IP address and/or device fingerprint</li>
                <li>Save the verification record</li>
              </ol>
            </div>
          </div>
        </section>

        <!-- 4. Settings Audit Trail -->
        <section class="card-elevated bg-slate-50" aria-labelledby="audit-heading">
          <h3 id="audit-heading" class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Change History
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div class="space-y-1">
              <p class="text-slate-600 font-semibold">Current Version</p>
              <p class="text-2xl font-bold text-teal-700">v{{ form.settings_version }}</p>
            </div>
            <div class="space-y-1">
              <p class="text-slate-600 font-semibold">Last Modified</p>
              <p class="text-slate-900 font-mono">
                {{ election.settings_updated_at
                  ? new Date(election.settings_updated_at).toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
                  : 'Never modified'
                }}
              </p>
            </div>
            <div class="space-y-1">
              <p class="text-slate-600 font-semibold">Modified By</p>
              <p class="text-slate-900 font-semibold">
                {{ election.settingsUpdatedBy?.name ?? 'System'  }}
              </p>
            </div>
          </div>
        </section>

        <!-- Election In Progress Warning & Confirmation (if applicable) -->
        <div v-if="isShowingNeedsConfirmation" class="space-y-4">
          <!-- Warning Alert -->
          <div class="card-elevated border-l-4 border-orange-500 bg-orange-50" role="alert">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="text-lg font-bold text-orange-900">Election In Progress</h3>
                <p class="text-orange-800 mt-2 leading-relaxed">
                  This election is active with received votes. Changes will take effect <strong>immediately</strong> and may affect voters currently participating.
                </p>
              </div>
            </div>
          </div>

          <!-- Confirmation Checkbox -->
          <div class="card-elevated border-l-4 border-orange-500 bg-orange-50">
            <fieldset class="p-4 rounded-lg border-2 border-orange-200">
              <legend class="text-sm font-semibold text-slate-900 mb-3">Confirmation required</legend>
              <label class="flex items-center cursor-pointer group">
                <input
                  v-model="form.confirmed_active_changes"
                  type="checkbox"
                  class="w-5 h-5 rounded border-2 border-orange-400 focus:ring-2 focus:ring-orange-500 cursor-pointer"
                  aria-describedby="confirm-help-2"
                />
                <span class="ml-3 text-sm font-semibold text-orange-900 group-hover:text-orange-700">
                  I understand the impact and want to proceed
                </span>
              </label>
              <p id="confirm-help-2" class="text-xs text-orange-700 mt-2 ml-8">
                This change will be applied to all currently voting voters.
              </p>
            </fieldset>
          </div>
        </div>

        <!-- General Agreement Required -->
        <div class="card-elevated border-l-4 border-primary-500 bg-primary-50">
          <fieldset class="space-y-3">
            <legend class="text-sm font-semibold text-slate-900 mb-4">Confirmation Required</legend>
            <label class="flex items-start cursor-pointer group">
              <input
                v-model="form.agreed_to_settings"
                type="checkbox"
                class="w-5 h-5 rounded border-2 border-primary-400 focus:ring-2 focus:ring-blue-500 cursor-pointer mt-1 flex-shrink-0"
                aria-describedby="agree-help"
              />
              <span class="ml-3 text-sm font-semibold text-primary-900 group-hover:text-primary-700">
                I agree to save these election settings and understand they will take effect immediately
              </span>
            </label>
            <p id="agree-help" class="text-xs text-primary-700 ml-8">
              Please review all settings above before confirming. These changes cannot be undone automatically.
            </p>
          </fieldset>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-4 pt-4">
          <button
            type="submit"
            :disabled="!form.agreed_to_settings || !validateWhitelist() || (isShowingNeedsConfirmation && !form.confirmed_active_changes) || isValidating"
            aria-busy="isValidating"
            :aria-label="`Save settings${isValidating ? ' (saving)' : ''}`"
            class="px-8 py-4 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-200 disabled:bg-slate-400 disabled:cursor-not-allowed transition-all duration-200 inline-flex items-center gap-2"
          >
            <svg v-if="isValidating" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>{{ isValidating ? 'Saving...' : 'Save Settings' }}</span>
          </button>
          <a
            :href="route('elections.management', election.slug)"
            class="px-8 py-4 text-slate-700 bg-slate-200 font-semibold rounded-lg hover:bg-slate-300 focus:outline-none focus:ring-4 focus:ring-slate-300 transition-all duration-200"
            aria-label="Go back without saving"
          >
            Cancel
          </a>
        </div>
      </form>
    </div>
    </div>
  </ElectionLayout>
</template>

<style scoped>
/* Animation keyframes */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

/* Design system CSS variables */
:root {
  --color-primary: #0369a1;
  --color-primary-light: #e0f2fe;
  --color-accent: #f59e0b;
  --color-accent-light: #fef3c7;
  --color-success: #059669;
  --color-danger: #dc2626;
  --color-text-primary: #0f172a;
  --color-text-secondary: #475569;
  --color-border: #e2e8f0;
  --color-bg-secondary: #f8fafc;
}

/* Smooth transitions for reduced-motion users */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* Focus styles for keyboard accessibility */
:focus-visible {
  outline: 3px solid var(--color-primary);
  outline-offset: 2px;
}

.geometric-divider {
  position: relative;
  height: 4px;
  background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
  margin: 2rem 0;
  border-radius: 2px;
}

.section-header {
  position: relative;
  padding-bottom: 1.5rem;
  margin-bottom: 2rem;
  border-bottom: 2px solid var(--color-border);
}

.card-elevated {
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 12px;
  padding: 1.75rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease;
}

.card-elevated:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
  border-color: var(--color-primary);
}
</style>

