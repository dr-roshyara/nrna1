<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 to-slate-100">
    <PublicDigitHeader :breadcrumbs="breadcrumbs" />

    <div class="flex-1 max-w-6xl w-full mx-auto px-6 py-12">

      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-slate-900 mb-3">{{ $t('pages.tutorial-hub.title') }}</h1>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto">
          {{ $t('pages.tutorial-hub.subtitle') }}
        </p>
      </div>

      <!-- Categories -->
      <div class="space-y-10">

        <!-- Getting Started -->
        <section>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white text-sm font-bold">1</div>
            <h2 class="text-xl font-bold text-slate-800">{{ $t('pages.tutorial-hub.sections.getting_started') }}</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.create_organisation.title')"
              :description="$t('pages.tutorial-hub.cards.create_organisation.description')"
              icon="🏛️"
              href="/organisation-create-tutorial"
              :badge="$t('pages.tutorial-hub.cards.create_organisation.badge')"
              badge-color="success"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.governance_levels.title')"
              :description="$t('pages.tutorial-hub.cards.governance_levels.description')"
              icon="📐"
              href="/governance-levels"
              :badge="$t('pages.tutorial-hub.cards.governance_levels.badge')"
              badge-color="primary"
            />
          </div>
        </section>

        <!-- Elections -->
        <section>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg bg-accent-600 flex items-center justify-center text-white text-sm font-bold">2</div>
            <h2 class="text-xl font-bold text-slate-800">{{ $t('pages.tutorial-hub.sections.elections') }}</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.election_journey.title')"
              :description="$t('pages.tutorial-hub.cards.election_journey.description')"
              icon="🗳️"
              href="/help/election-journey"
              :badge="$t('pages.tutorial-hub.cards.election_journey.badge')"
              badge-color="primary"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.election_setup.title')"
              :description="$t('pages.tutorial-hub.cards.election_setup.description')"
              icon="⚙️"
              href="/help/election-setup"
              :badge="$t('pages.tutorial-hub.cards.election_setup.badge')"
              badge-color="neutral"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.voter_import.title')"
              :description="$t('pages.tutorial-hub.cards.voter_import.description')"
              icon="📥"
              href="/voter-import-tutorial"
              :badge="$t('pages.tutorial-hub.cards.voter_import.badge')"
              badge-color="neutral"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.voter_verification.title')"
              :description="$t('pages.tutorial-hub.cards.voter_verification.description')"
              icon="✅"
              href="/help/voters-verification_guide"
              :badge="$t('pages.tutorial-hub.cards.voter_verification.badge')"
              badge-color="success"
            />
          </div>
        </section>

        <!-- Committees -->
        <section>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg bg-green-600 flex items-center justify-center text-white text-sm font-bold">3</div>
            <h2 class="text-xl font-bold text-slate-800">{{ $t('pages.tutorial-hub.sections.committees') }}</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.committee_management.title')"
              :description="$t('pages.tutorial-hub.cards.committee_management.description')"
              icon="👥"
              href="/create-committee-tutorial"
              :badge="$t('pages.tutorial-hub.cards.committee_management.badge')"
              badge-color="primary"
            />
          </div>
        </section>

        <!-- Membership -->
        <section>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center text-white text-sm font-bold">4</div>
            <h2 class="text-xl font-bold text-slate-800">{{ $t('pages.tutorial-hub.sections.membership') }}</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.membership_modes.title')"
              :description="$t('pages.tutorial-hub.cards.membership_modes.description')"
              icon="🪪"
              href="/help/membership-modes"
              :badge="$t('pages.tutorial-hub.cards.membership_modes.badge')"
              badge-color="neutral"
            />
          </div>
        </section>

        <!-- Security & Architecture -->
        <section>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white text-sm font-bold">5</div>
            <h2 class="text-xl font-bold text-slate-800">{{ $t('pages.tutorial-hub.sections.security_architecture') }}</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.election_architecture.title')"
              :description="$t('pages.tutorial-hub.cards.election_architecture.description')"
              icon="🏗️"
              href="/election-architecture"
              :badge="$t('pages.tutorial-hub.cards.election_architecture.badge')"
              badge-color="primary"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.election_security.title')"
              :description="$t('pages.tutorial-hub.cards.election_security.description')"
              icon="🔐"
              href="/election-security"
              :badge="$t('pages.tutorial-hub.cards.election_security.badge')"
              badge-color="neutral"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.platform_security.title')"
              :description="$t('pages.tutorial-hub.cards.platform_security.description')"
              icon="🛡️"
              href="/security"
              :badge="$t('pages.tutorial-hub.cards.platform_security.badge')"
              badge-color="success"
            />
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.voting_security.title')"
              :description="$t('pages.tutorial-hub.cards.voting_security.description')"
              icon="🔒"
              href="/voting/security"
              :badge="$t('pages.tutorial-hub.cards.voting_security.badge')"
              badge-color="neutral"
            />
          </div>
        </section>

        <!-- Try It Live -->
        <section>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white text-sm font-bold">6</div>
            <h2 class="text-xl font-bold text-slate-800">{{ $t('pages.tutorial-hub.sections.try_it_live') }}</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <TutorialCard
              :title="$t('pages.tutorial-hub.cards.public_demo.title')"
              :description="$t('pages.tutorial-hub.cards.public_demo.description')"
              icon="🚀"
              href="/public-demo/guide"
              :badge="$t('pages.tutorial-hub.cards.public_demo.badge')"
              badge-color="accent"
            />
          </div>
        </section>

      </div>
    </div>

    <PublicDigitFooter />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

const { t } = useI18n()

const breadcrumbs = computed(() => [
  { label: t('pages.tutorial-hub.breadcrumb'), href: '/tutorial' },
])
</script>

<script lang="ts">
// TutorialCard defined inline so the hub page is self-contained
import { defineComponent, h } from 'vue'
import { useI18n } from 'vue-i18n'

const badgeClasses: Record<string, string> = {
  primary: 'bg-primary-100 text-primary-700',
  success: 'bg-green-100 text-green-700',
  neutral: 'bg-slate-100 text-slate-600',
  accent:  'bg-amber-100 text-amber-700',
}

export const TutorialCard = defineComponent({
  name: 'TutorialCard',
  props: {
    title:       { type: String, required: true },
    description: { type: String, required: true },
    icon:        { type: String, required: true },
    href:        { type: String, required: true },
    badge:       { type: String, required: true },
    badgeColor:  { type: String as () => keyof typeof badgeClasses, default: 'neutral' },
  },
  setup(props) {
    const { t } = useI18n()
    return () =>
      h('a', { href: props.href, class: 'group block bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md hover:border-primary-300 transition-all duration-200' }, [
        h('div', { class: 'flex items-start justify-between mb-3' }, [
          h('span', { class: 'text-3xl' }, props.icon),
          h('span', { class: `text-xs font-semibold px-2 py-1 rounded-full ${badgeClasses[props.badgeColor]}` }, props.badge),
        ]),
        h('h3', { class: 'text-base font-bold text-slate-900 mb-1 group-hover:text-primary-700 transition-colors' }, props.title),
        h('p',  { class: 'text-sm text-slate-500 leading-relaxed' }, props.description),
        h('div', { class: 'mt-4 text-xs font-semibold text-primary-600 group-hover:underline' }, t('pages.tutorial-hub.read_guide')),
      ])
  },
})

export default {}
</script>
