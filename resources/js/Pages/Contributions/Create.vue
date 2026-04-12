<template>
  <PublicDigitLayout>
    <div class="max-w-3xl mx-auto px-4 py-8">

      <!-- Header -->
      <div class="mb-8">
        <Link :href="route('organisations.contributions.index', organisation.slug)"
              class="text-purple-600 hover:text-purple-700 text-sm mb-2 inline-flex items-center gap-1">
          &larr; Back to My Contributions
        </Link>
        <h1 class="text-2xl font-bold text-slate-900 mt-2">Log Your Contribution</h1>
        <p class="text-slate-500 mt-1">Share how you're making an impact</p>
      </div>

      <!-- Weekly Cap Indicator -->
      <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-sm text-blue-800">Weekly Micro-track Points</span>
          </div>
          <span class="font-bold text-blue-900">{{ weeklyPointsUsed }} / {{ weeklyCap }} used</span>
        </div>
        <div class="mt-2 w-full bg-blue-200 rounded-full h-1.5">
          <div class="bg-blue-600 h-1.5 rounded-full transition-all"
               :style="{ width: weeklyProgressPercent + '%' }"></div>
        </div>
        <p class="text-xs text-blue-600 mt-2" v-if="weeklyPointsRemaining < 30">
          Only {{ weeklyPointsRemaining }} points remaining this week!
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-6">

        <!-- Title -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Title <span class="text-red-500">*</span>
          </label>
          <input type="text" v-model="form.title" required maxlength="255"
                 class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                 placeholder="e.g., Weekly Math Tutoring for 5 Students">
          <p v-if="errors.title" class="text-xs text-red-600 mt-1">{{ errors.title }}</p>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">
            Description <span class="text-red-500">*</span>
          </label>
          <textarea v-model="form.description" rows="4" required
                    class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="What did you do? Who did it help? How many hours?"></textarea>
          <p v-if="errors.description" class="text-xs text-red-600 mt-1">{{ errors.description }}</p>
        </div>

        <!-- Track Selection (3 cards) -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Contribution Scale</label>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <button type="button" v-for="track in tracks" :key="track.value"
                    @click="form.track = track.value"
                    :class="[
                      'p-4 rounded-xl border-2 text-left transition-all',
                      form.track === track.value
                        ? 'border-purple-500 bg-purple-50'
                        : 'border-slate-200 hover:border-purple-300'
                    ]">
              <div class="font-bold text-lg">{{ track.icon }} {{ track.label }}</div>
              <div class="text-xs text-slate-500 mt-1">{{ track.description }}</div>
              <div class="text-xs text-purple-600 mt-2 font-medium">{{ track.points_range }}</div>
            </button>
          </div>
        </div>

        <!-- Effort Hours (Slider + Input) -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">
            Effort Hours <span class="text-red-500">*</span>
          </label>
          <div class="flex items-center gap-4">
            <input type="range" v-model.number="form.effort_units" min="1" max="40" step="1"
                   class="flex-1 accent-purple-600">
            <input type="number" v-model.number="form.effort_units" min="1" max="40"
                   class="w-20 border border-slate-300 rounded-lg px-3 py-2 text-center">
          </div>
          <p class="text-xs text-slate-400 mt-1">Estimated hours spent on this activity</p>
        </div>

        <!-- Team Skills (with synergy tooltip) -->
        <div>
          <div class="flex items-center gap-2 mb-2">
            <label class="text-sm font-medium text-slate-700">Team Skills (optional)</label>
            <div class="group relative">
              <span class="cursor-help text-xs border rounded-full w-4 h-4 inline-flex items-center justify-center">?</span>
              <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-slate-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-10">
                Unique skills trigger synergy bonus!<br>
                1 skill: 1.0x | 2 skills: 1.2x | 3+ skills: 1.5x (combined max 2.0x)
              </div>
            </div>
          </div>
          <div class="flex flex-wrap gap-2">
            <button type="button" v-for="skill in availableSkills" :key="skill"
                    @click="toggleSkill(skill)"
                    :class="[
                      'px-3 py-1.5 rounded-full text-sm transition-all',
                      form.team_skills.includes(skill)
                        ? 'bg-purple-600 text-white'
                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                    ]">
              {{ skill }}
            </button>
          </div>
          <p class="text-xs text-slate-400 mt-2">Add skills of everyone who helped</p>
        </div>

        <!-- Proof Type -->
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-2">Proof Type</label>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
            <button type="button" v-for="proof in proofTypes" :key="proof.value"
                    @click="form.proof_type = proof.value"
                    :class="[
                      'px-3 py-2 rounded-lg text-sm transition-all',
                      form.proof_type === proof.value
                        ? 'bg-purple-600 text-white'
                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                    ]">
              {{ proof.label }}
              <span class="text-xs block">{{ proof.multiplier }}x</span>
            </button>
          </div>
          <p class="text-xs text-slate-400 mt-2">Higher proof type = higher points multiplier</p>
        </div>

        <!-- Recurring Toggle -->
        <label class="flex items-center gap-3 cursor-pointer">
          <input type="checkbox" v-model="form.is_recurring" class="w-5 h-5 accent-purple-600">
          <span class="text-sm text-slate-700">This is a recurring activity (weekly/monthly)</span>
          <span class="text-xs text-green-600">+20% sustainability bonus</span>
        </label>

        <!-- Live Points Preview Card -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
          <h3 class="font-bold text-slate-800 mb-3">Estimated Points Preview</h3>
          <div class="text-3xl font-bold text-purple-700 mb-4">{{ estimatedPoints }} points</div>
          <div class="text-xs text-slate-600 space-y-1">
            <div class="flex justify-between">
              <span>Base: {{ form.effort_units }} hrs x {{ trackConfig.base_rate }}</span>
              <span>{{ basePoints }} pts</span>
            </div>
            <div class="flex justify-between" v-if="tierBonus > 0">
              <span>{{ form.track }} tier bonus</span>
              <span>+{{ tierBonus }} pts</span>
            </div>
            <div class="flex justify-between" v-if="synergyMultiplier > 1">
              <span>Team synergy ({{ uniqueSkillCount }} unique skills)</span>
              <span>x{{ synergyMultiplier }}</span>
            </div>
            <div class="flex justify-between">
              <span>Proof type ({{ currentProofLabel }})</span>
              <span>x{{ currentProofMultiplier }}</span>
            </div>
            <div class="flex justify-between" v-if="form.is_recurring">
              <span>Sustainability (recurring)</span>
              <span>x1.2</span>
            </div>
            <div class="border-t border-purple-200 pt-2 mt-2 font-semibold flex justify-between"
                 v-if="form.track === 'micro' && rawPoints > estimatedPoints">
              <span>Weekly cap applied</span>
              <span>capped to {{ estimatedPoints }} pts</span>
            </div>
            <div class="border-t border-purple-200 pt-2 mt-2 font-semibold flex justify-between">
              <span>Total (floored)</span>
              <span>= {{ estimatedPoints }} pts</span>
            </div>
          </div>
          <p class="text-xs text-purple-600 mt-3">Tip: Add more skills or stronger proof for higher points!</p>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3 pt-4">
          <button type="submit" :disabled="submitting"
                  class="flex-1 bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 disabled:opacity-50 transition-colors">
            {{ submitting ? 'Submitting...' : 'Submit for Review' }}
          </button>
          <Link :href="route('organisations.contributions.index', organisation.slug)"
                class="px-6 py-3 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors">
            Cancel
          </Link>
        </div>

        <!-- Info Note -->
        <div class="text-center text-xs text-slate-400 pt-4">
          <p>Contributions are reviewed by admins before points are awarded.</p>
          <p>You can track the status in "My Contributions".</p>
        </div>

      </form>
    </div>
  </PublicDigitLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  weeklyPoints: { type: Number, default: 0 },
  weeklyCap: { type: Number, default: 100 },
})

// ── Track config mirroring GaneshStandardFormula::TRACK_CONFIG ──────────────
const TRACK_CONFIG = {
  micro:    { base_rate: 10, tier_bonus: 0,   min_base: 0,   weekly_cap: 100 },
  standard: { base_rate: 10, tier_bonus: 50,  min_base: 31,  weekly_cap: null },
  major:    { base_rate: 10, tier_bonus: 200, min_base: 201, weekly_cap: null },
}

// ── Form State ──────────────────────────────────────────────────────────────
const form = ref({
  title: '',
  description: '',
  track: 'micro',
  effort_units: 2,
  proof_type: 'self_report',
  team_skills: [],
  is_recurring: false,
  outcome_bonus: 0,
})

const errors = ref({})
const submitting = ref(false)

// ── Options ─────────────────────────────────────────────────────────────────
const tracks = [
  { value: 'micro', label: 'Micro', icon: '\u26A1', description: 'Quick actions, honor system', points_range: '\u2264 30 pts' },
  { value: 'standard', label: 'Standard', icon: '\uD83D\uDCCC', description: 'With photo/document proof', points_range: '31-200 pts' },
  { value: 'major', label: 'Major', icon: '\uD83C\uDFC6', description: 'Large projects, institutional proof', points_range: '201+ pts' },
]

const proofTypes = [
  { value: 'self_report',           label: 'Self-report',           multiplier: 0.5 },
  { value: 'photo',                 label: 'Photo',                 multiplier: 0.7 },
  { value: 'document',              label: 'Document',              multiplier: 0.8 },
  { value: 'third_party',           label: 'Third-party',           multiplier: 1.0 },
  { value: 'community_attestation', label: 'Community Attestation', multiplier: 1.1 },
  { value: 'institutional',         label: 'Institutional',         multiplier: 1.2 },
]

const availableSkills = [
  'Teaching', 'Healthcare', 'Engineering', 'Legal', 'Finance',
  'Marketing', 'Translation', 'Event Planning', 'Design', 'Coding',
]

// ── Computed Points Preview (mirrors GaneshStandardFormula::calculate) ──────
const trackConfig = computed(() => TRACK_CONFIG[form.value.track])

// Mirrors GaneshStandardFormula::calculateEffectiveEffort()
const DIMINISHING_RETURNS_THRESHOLD = 20
const effectiveEffort = computed(() => {
  const h = form.value.effort_units
  if (h <= DIMINISHING_RETURNS_THRESHOLD) return h
  const extra = h - DIMINISHING_RETURNS_THRESHOLD
  return DIMINISHING_RETURNS_THRESHOLD + Math.log(extra + 1) * 5
})

const basePoints = computed(() => effectiveEffort.value * trackConfig.value.base_rate)

const tierBonus = computed(() => {
  const config = trackConfig.value
  return basePoints.value >= config.min_base ? config.tier_bonus : 0
})

const subtotal = computed(() => basePoints.value + tierBonus.value)

const uniqueSkillCount = computed(() => new Set(form.value.team_skills).size)

const synergyMultiplier = computed(() => {
  const count = uniqueSkillCount.value
  if (count >= 3) return 1.5
  if (count >= 2) return 1.2
  return 1.0
})

const currentProofType = computed(() =>
  proofTypes.find(p => p.value === form.value.proof_type) || proofTypes[0]
)
const currentProofMultiplier = computed(() => currentProofType.value.multiplier)
const currentProofLabel = computed(() => currentProofType.value.label)

const sustainabilityMultiplier = computed(() => form.value.is_recurring ? 1.2 : 1.0)

const MAX_COMBINED_MULTIPLIER = 2.0

const rawPoints = computed(() => {
  const rawMultiplier = synergyMultiplier.value * currentProofMultiplier.value * sustainabilityMultiplier.value
  const cappedMultiplier = Math.min(rawMultiplier, MAX_COMBINED_MULTIPLIER)
  return Math.floor(subtotal.value * cappedMultiplier) + form.value.outcome_bonus
})

const weeklyPointsRemaining = computed(() => Math.max(0, props.weeklyCap - props.weeklyPoints))

const estimatedPoints = computed(() => {
  const cap = trackConfig.value.weekly_cap
  if (cap !== null) {
    const remaining = Math.max(0, cap - props.weeklyPoints)
    return Math.min(rawPoints.value, remaining)
  }
  return rawPoints.value
})

const weeklyPointsUsed = computed(() => props.weeklyPoints)
const weeklyProgressPercent = computed(() => Math.min(100, (props.weeklyPoints / props.weeklyCap) * 100))

// ── Methods ─────────────────────────────────────────────────────────────────
const toggleSkill = (skill) => {
  const idx = form.value.team_skills.indexOf(skill)
  if (idx >= 0) {
    form.value.team_skills.splice(idx, 1)
  } else {
    form.value.team_skills.push(skill)
  }
}

const submit = () => {
  submitting.value = true
  errors.value = {}

  router.post(route('organisations.contributions.store', props.organisation.slug), form.value, {
    preserveScroll: true,
    onError: (err) => {
      errors.value = err
      submitting.value = false
    },
    onFinish: () => {
      submitting.value = false
    },
  })
}
</script>
