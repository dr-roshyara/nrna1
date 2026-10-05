/**
 * PBDIGIT-213 C4 — Voter Management: identity-verification EVIDENCE vs the admission POLICY
 * (PO correction, 2026-10-04).
 *
 *  - Election Settings ("Verify Identity: Yes/No") = the Voter Admission Policy:
 *    is identity verification REQUIRED? → `admission.required`.
 *  - Voter Management records EVIDENCE: has this voter's identity been verified?
 *    → `identityVerification[user_id]` (verified · verification_required · reverification_required),
 *    independent of the policy.
 *
 *  - Evidence operations (Verify Identity / Re-verify Identity / Verify All) are available
 *    whether the policy is ON or OFF. Policy OFF means "not required", not "cannot be recorded".
 *  - The Identity Verification column is a policy-oriented status view: shown only while required.
 *  - The name badge says "Identity verified" — one requirement's evidence, NOT the final
 *    admission decision (that stays the server's AdmissionDecision / VotingEntitlement).
 *  - No "Verify IP Address" action (IP verification is untouched legacy, PBDIGIT-222).
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import { defineComponent, h } from 'vue'

vi.mock('@inertiajs/vue3', () => ({
  router: { post: vi.fn(), get: vi.fn(), patch: vi.fn(), delete: vi.fn(), reload: vi.fn() },
  usePage: () => ({ props: { auth: { user: { name: 'Chief One' } } } }),
}))
vi.stubGlobal('route', vi.fn((name) => `/mocked/${name}`))

import VotersIndex from '@/Pages/Elections/Voters/Index.vue'

const LayoutStub = defineComponent({ name: 'PublicDigitLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })

const AttestationDialogStub = defineComponent({
  name: 'IdentityAttestationDialog',
  props: ['open', 'voter', 'organisationSlug', 'electionSlug', 'reverify'],
  setup: () => () => h('div', { 'data-testid': 'attestation-dialog-stub' }),
})

const IpModalStub = defineComponent({
  name: 'VoterVerificationModal',
  props: ['show'],
  setup: () => () => h('div', { 'data-testid': 'ip-modal-stub' }),
})

const membership = (id, userId, name) => ({
  id,
  user_id: userId,
  status: 'active',
  has_voted: false,
  assigned_at: '2026-10-01T10:00:00Z',
  metadata: {},
  suspension_status: null,
  user: { id: userId, name, email: `${userId}@example.org`, current_ip: '203.0.113.7', updated_at: '2026-10-01T09:00:00Z' },
})

const NO_EVIDENCE = membership('m-1', 'u-1', 'Asha Rai')
const VERIFIED = membership('m-2', 'u-2', 'Bikash Thapa')
const REVOKED = membership('m-3', 'u-3', 'Chandra Lama')
const SOURCE_GUARD = membership('m-4', 'u-4', 'Dawa Sherpa')

// The evidence state is the same whatever the policy (it comes from the evidence).
const EVIDENCE_STATES = {
  'u-1': 'verification_required',
  'u-2': 'verified',
  'u-3': 'reverification_required',
  'u-4': 'verification_required',
}

const mountPage = ({ policyOn, bulkVerifiableCount = 1 }) => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'setup_administration' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: false },
    voters: { data: [NO_EVIDENCE, VERIFIED, REVOKED, SOURCE_GUARD], links: [], current_page: 1, per_page: 50, from: 1, to: 4, total: 4 },
    stats: { active_voters: 4, eligible_voters: 4, by_status: { active: 4 } },
    // Source guard: the generic map lists u-4, but the identity evidence state does not
    // count u-4 as verified. The page must read identityVerification only.
    verifications: { 'u-2': { id: 'v-2' }, 'u-4': { id: 'v-4' } },
    admission: { required: policyOn, available: true, bulkVerifiableCount },
    identityVerification: EVIDENCE_STATES,
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {} } } },
    stubs: {
      PublicDigitLayout: LayoutStub,
      IdentityAttestationDialog: AttestationDialogStub,
      VoterVerificationModal: IpModalStub,
      BulkVerifyDialog: true,
      teleport: true,
    },
  },
})

const row = (wrapper, name) => wrapper.findAll('tr').find(tr => tr.text().includes(name))
const identityButton = (wrapper, name) => row(wrapper, name).find('[data-testid="actions-verify-identity"]')
const nameBadge = (wrapper, name) => row(wrapper, name).find('.verification-badge')
const dialog = (wrapper) => wrapper.findComponent(AttestationDialogStub)
const headers = (wrapper) => wrapper.findAll('th').map(th => th.text())

describe('Policy OFF — evidence can still be recorded', () => {
  it('A: no evidence → Verify Identity is available; no identity badge', () => {
    const wrapper = mountPage({ policyOn: false })
    expect(identityButton(wrapper, 'Asha Rai').text()).toBe('Verify Identity')
    expect(nameBadge(wrapper, 'Asha Rai').exists()).toBe(false)
  })

  // PBDIGIT-248 (ID-D1) supersedes "Re-verify Identity": a verified voter offers Cancel Verification.
  it('B: active evidence → Cancel Verification (no Re-verify), and the identity badge is shown', () => {
    const wrapper = mountPage({ policyOn: false })
    expect(row(wrapper, 'Bikash Thapa').find('[data-testid="actions-cancel-verification"]').text()).toBe('Cancel Verification')
    expect(row(wrapper, 'Bikash Thapa').text()).not.toContain('Re-verify Identity')
    expect(nameBadge(wrapper, 'Bikash Thapa').exists()).toBe(true)
  })

  it('C: revoked evidence → Verify Identity is available and opens the dialog in re-verify mode', async () => {
    const wrapper = mountPage({ policyOn: false })
    await identityButton(wrapper, 'Chandra Lama').trigger('click')

    expect(dialog(wrapper).props('open')).toBe(true)
    expect(dialog(wrapper).props('voter')).toMatchObject({ id: 'u-3' })
    expect(dialog(wrapper).props('reverify')).toBe(true)
  })

  it('hides the policy-oriented Identity Verification column', () => {
    expect(headers(mountPage({ policyOn: false }))).not.toContain('Identity Verification')
  })

  it('G: Verify All is available when eligible voters exist', () => {
    expect(mountPage({ policyOn: false }).find('[data-testid="open-verify-all"]').exists()).toBe(true)
  })

  it('G: Verify All is not offered when no voter is eligible', () => {
    expect(mountPage({ policyOn: false, bulkVerifiableCount: 0 }).find('[data-testid="open-verify-all"]').exists()).toBe(false)
  })
})

describe('Policy ON', () => {
  it('D: no evidence → the Identity Verification column is visible and Verify Identity is available', () => {
    const wrapper = mountPage({ policyOn: true })
    expect(headers(wrapper)).toContain('Identity Verification')
    expect(identityButton(wrapper, 'Asha Rai').text()).toBe('Verify Identity')
    expect(row(wrapper, 'Asha Rai').find('[data-testid="verify-identity"]').exists()).toBe(true)
  })

  it('E: active evidence → verified state visible and Cancel Verification available (PBDIGIT-248)', () => {
    const wrapper = mountPage({ policyOn: true })
    expect(row(wrapper, 'Bikash Thapa').text()).toContain('Verified ✓')
    expect(row(wrapper, 'Bikash Thapa').find('[data-testid="actions-cancel-verification"]').text()).toBe('Cancel Verification')
  })

  it('F: revoked evidence → re-verification required, dialog in re-verify mode', async () => {
    const wrapper = mountPage({ policyOn: true })
    expect(row(wrapper, 'Chandra Lama').text()).toContain('Re-verification required')

    await identityButton(wrapper, 'Chandra Lama').trigger('click')
    expect(dialog(wrapper).props('reverify')).toBe(true)
  })

  it('both identity entry points open the same dialog', async () => {
    const wrapper = mountPage({ policyOn: true })
    await row(wrapper, 'Asha Rai').get('[data-testid="verify-identity"]').trigger('click')
    expect(dialog(wrapper).props('voter')).toMatchObject({ id: 'u-1' })
    expect(dialog(wrapper).props('reverify')).toBe(false)
  })

  it('H: Verify All is available when eligible voters exist (unchanged)', () => {
    expect(mountPage({ policyOn: true }).find('[data-testid="open-verify-all"]').exists()).toBe(true)
  })
})

describe('The identity badge is honest about what it means', () => {
  it.each([true, false])('reads "Identity verified" and says it is not the complete admission decision (policy %s)', (policyOn) => {
    const badge = nameBadge(mountPage({ policyOn }), 'Bikash Thapa')
    expect(badge.text()).toBe('Identity verified')
    expect(badge.attributes('title')).toMatch(/not the complete admission decision/i)
  })

  it.each([true, false])('comes from the identity evidence state only, never the generic verifications map (policy %s)', (policyOn) => {
    const wrapper = mountPage({ policyOn })
    expect(nameBadge(wrapper, 'Dawa Sherpa').exists()).toBe(false)
    expect(identityButton(wrapper, 'Dawa Sherpa').text()).toBe('Verify Identity')
  })
})

describe('No IP verification in this increment', () => {
  it.each([true, false])('has no Verify IP Address action and no IP modal (policy %s)', (policyOn) => {
    const wrapper = mountPage({ policyOn })
    expect(wrapper.find('[data-testid="actions-verify-ip"]').exists()).toBe(false)
    expect(wrapper.findComponent(IpModalStub).exists()).toBe(false)
    wrapper.findAll('button').forEach(b => expect(`${b.text()} ${b.attributes('title') ?? ''}`).not.toMatch(/\bIP\b|device/i))
  })
})
