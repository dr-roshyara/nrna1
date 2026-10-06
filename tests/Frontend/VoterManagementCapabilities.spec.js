/**
 * PBDIGIT-251 — RED: Voter Management shows only the actions the server allows in the current lifecycle state.
 *
 * The page is reachable in every state; capabilities { manageVoters, verifyIdentity, captureIp } come from the
 * server. During voting: Verify Identity / Cancel Verification / Verify IP Address are offered; approve,
 * suspend (propose / confirm / cancel), remove and export are not. Contact data (email / telephone) is never
 * editable on this page (PO 2026-10-06).
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

const voters = [
  { id: 'm-1', user_id: 'u-1', status: 'active', has_voted: false, suspension_status: null, metadata: {},
    user: { id: 'u-1', name: 'Asha Rai', email: 'u-1@example.org', current_ip: '203.0.113.7' } },
  { id: 'm-2', user_id: 'u-2', status: 'invited', has_voted: false, suspension_status: null, metadata: {},
    user: { id: 'u-2', name: 'Bina Lama', email: 'u-2@example.org', current_ip: null } },
  { id: 'm-3', user_id: 'u-3', status: 'active', has_voted: false, suspension_status: 'proposed', suspension_proposed_by: 'Someone Else', metadata: {},
    user: { id: 'u-3', name: 'Chandra KC', email: 'u-3@example.org', current_ip: null } },
]

const mountPage = (capabilities) => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'voting_active' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: false },
    voters: { data: voters, links: [], current_page: 1, per_page: 50, from: 1, to: 3, total: 3 },
    stats: { active_voters: 2, eligible_voters: 3, by_status: {} },
    admission: { required: true, available: true, bulkVerifiableCount: 0 },
    identityVerification: { 'u-1': 'verified', 'u-2': 'verification_required', 'u-3': 'verification_required' },
    captureIp: { active: true },
    ipVerification: {},
    capabilities,
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {}, flash: {} } } },
    stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: true, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
  },
})

// Registration actions: setup administration only. Suspension (propose / confirm / cancel) has its own window.
const MANAGE_SELECTORS = ['.act-btn--approve', '.act-btn--remove', 'a[href="/mocked/elections.voters.export"]']
const SUSPENSION_SELECTORS = ['.act-btn--propose', '.act-btn--confirm']

describe('Voter Management — lifecycle capabilities (PBDIGIT-251)', () => {
  it('L6a: during voting, verification and suspension are offered; registration actions are hidden', () => {
    const wrapper = mountPage({ manageVoters: false, suspendVoters: true, verifyIdentity: true, captureIp: true })

    expect(wrapper.find('[data-testid="actions-cancel-verification"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="actions-verify-identity"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="capture-ip"]').exists()).toBe(true)
    for (const selector of SUSPENSION_SELECTORS) {
      expect(wrapper.find(selector).exists(), `${selector} offered during voting (PO: suspension only)`).toBe(true)
    }
    for (const selector of MANAGE_SELECTORS) {
      expect(wrapper.find(selector).exists(), `${selector} hidden while voter management is closed`).toBe(false)
    }
  })

  it('L6b: after voting, neither verification, suspension nor management actions are offered', () => {
    const wrapper = mountPage({ manageVoters: false, suspendVoters: false, verifyIdentity: false, captureIp: false })
    for (const selector of SUSPENSION_SELECTORS) {
      expect(wrapper.find(selector).exists()).toBe(false)
    }

    expect(wrapper.find('[data-testid="actions-cancel-verification"]').exists()).toBe(false)
    expect(wrapper.find('[data-testid="actions-verify-identity"]').exists()).toBe(false)
    expect(wrapper.find('[data-testid="capture-ip"]').exists()).toBe(false)
    expect(wrapper.find('.act-btn--remove').exists()).toBe(false)
  })

  it('L6c: in setup administration everything stays available (unchanged)', () => {
    const wrapper = mountPage({ manageVoters: true, verifyIdentity: true, captureIp: true })

    expect(wrapper.find('.act-btn--remove').exists()).toBe(true)
    expect(wrapper.find('.act-btn--approve').exists()).toBe(true)
    expect(wrapper.find('a[href="/mocked/elections.voters.export"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="actions-verify-identity"]').exists()).toBe(true)
  })

  it('L7: the page offers no email or telephone input', () => {
    const wrapper = mountPage({ manageVoters: true, verifyIdentity: true, captureIp: true })

    expect(wrapper.find('input[type="email"]').exists()).toBe(false)
    expect(wrapper.find('input[type="tel"]').exists()).toBe(false)
    expect(wrapper.find('input[name*="email"], input[name*="phone"], input[name*="telephone"]').exists()).toBe(false)
  })
})
