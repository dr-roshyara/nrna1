/**
 * PBDIGIT-213 C4-A — "Voter Admission" card on the election settings page.
 * Display + intent only; the server (C2 handler + model guard) decides.
 * Run: npx vitest run tests/Frontend/VoterAdmissionCard.spec.js
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'

const mockRouterPatch = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { patch: (...args) => mockRouterPatch(...args) },
}))
vi.stubGlobal('route', vi.fn((name, params) => `/mocked/${name}/${typeof params === 'string' ? params : (params?.election ?? '')}`))

import VoterAdmissionCard from '@/Components/Election/VoterAdmissionCard.vue'

const HELP = 'When enabled, every voter must have identity verification before they can vote.'   // PO wording, 2026-10-04
const LOCKED = 'The admission policy cannot be changed once the election is ready for voting.'

function mountCard(admission = {}) {
  return mount(VoterAdmissionCard, {
    props: {
      electionSlug: 'onf-2',
      admission: { identityVerificationRequired: false, isLocked: false, available: true, affectedVoters: 5, ...admission },
    },
  })
}

describe('VoterAdmissionCard', () => {
  beforeEach(() => mockRouterPatch.mockClear())

  it('shows the business wording, never technical terms', () => {
    const text = mountCard().text()
    expect(text).toContain('Voter Admission')
    expect(text).toContain('Identity verification required')
    expect(text).toContain(HELP)
    expect(text).not.toMatch(/AdmissionRequirement|OFFICER_IDENTITY_VERIFICATION|gate/i)
  })

  it('reflects the current policy in the toggle', () => {
    expect(mountCard({ identityVerificationRequired: true }).get('[data-testid="identity-verification-toggle"]').attributes('aria-checked')).toBe('true')
    expect(mountCard({ identityVerificationRequired: false }).get('[data-testid="identity-verification-toggle"]').attributes('aria-checked')).toBe('false')
  })

  it('announces the impact of turning verification ON', async () => {
    const wrapper = mountCard({ identityVerificationRequired: false, affectedVoters: 5 })
    await wrapper.get('[data-testid="identity-verification-toggle"]').trigger('click')
    expect(wrapper.text()).toContain('5 registered voters currently require identity verification.')
  })

  it('announces the impact of turning verification OFF', async () => {
    const wrapper = mountCard({ identityVerificationRequired: true, affectedVoters: 3 })
    await wrapper.get('[data-testid="identity-verification-toggle"]').trigger('click')
    expect(wrapper.text()).toContain('3 registered voters would no longer need verification to vote.')
  })

  it('saves the admission policy through the dedicated endpoint', async () => {
    const wrapper = mountCard({ identityVerificationRequired: false })
    await wrapper.get('[data-testid="identity-verification-toggle"]').trigger('click')
    await wrapper.get('[data-testid="save-admission-policy"]').trigger('click')
    expect(mockRouterPatch).toHaveBeenCalledWith(
      '/mocked/elections.admission-policy.update/onf-2',
      { identity_verification_required: true },
      expect.any(Object),
    )
  })

  it('shows a locked policy as locked and disables changes', () => {
    const wrapper = mountCard({ identityVerificationRequired: true, isLocked: true })
    expect(wrapper.text()).toContain('ON — LOCKED')
    expect(wrapper.text()).toContain(LOCKED)
    expect(wrapper.get('[data-testid="identity-verification-toggle"]').attributes('disabled')).toBeDefined()
    expect(wrapper.get('[data-testid="save-admission-policy"]').attributes('disabled')).toBeDefined()
  })

  it('shows OFF — LOCKED for a locked election without the requirement', () => {
    expect(mountCard({ identityVerificationRequired: false, isLocked: true }).text()).toContain('OFF — LOCKED')
  })

  it('renders nothing when voter admission is not available for the election', () => {
    expect(mountCard({ available: false }).find('[data-testid="voter-admission-card"]').exists()).toBe(false)
  })
})
