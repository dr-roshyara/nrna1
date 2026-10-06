/**
 * Capture IP Address — a separate IP capability (PBDIGIT-222 / PO 2026-10-06) — RED.
 *
 * The familiar legacy modal (VoterVerificationModal.vue, retitled "Verify IP Address" in C4) is restored
 * in the IP Address column and retargeted to the IP-only endpoint (proposed: elections.voters.capture-ip).
 * It never calls the identity endpoint and has no identity attestation. Column visibility follows the
 * P-BINDING switch the server sends (proposed prop: ipBinding.active; its source is decision IP-D3).
 * Superseded 2026-10-06 (PO): visibility follows the "Voter's IP Verification" setting (captureIp.active).
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { defineComponent, h } from 'vue'

const mockRouter = vi.hoisted(() => ({ post: vi.fn(), get: vi.fn(), patch: vi.fn(), delete: vi.fn(), reload: vi.fn() }))
vi.mock('@inertiajs/vue3', () => ({
  router: mockRouter,
  usePage: () => ({ props: { auth: { user: { name: 'Chief One' } } } }),
}))
vi.stubGlobal('route', vi.fn((name) => `/mocked/${name}`))

import VotersIndex from '@/Pages/Elections/Voters/Index.vue'
import VoterVerificationModal from '@/Components/Election/VoterVerificationModal.vue'

const LayoutStub = defineComponent({ name: 'PublicDigitLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })
const AttestationDialogStub = defineComponent({
  name: 'IdentityAttestationDialog', props: ['open', 'voter', 'organisationSlug', 'electionSlug', 'reverify'],
  setup: () => () => h('div', { 'data-testid': 'attestation-dialog-stub' }),
})
const IpModalStub = defineComponent({
  name: 'VoterVerificationModal', props: ['show', 'voterId', 'voterCurrentIp', 'existingVerification'], emits: ['close', 'success'],
  setup: () => () => h('div', { 'data-testid': 'ip-modal-stub' }),
})

const voter = {
  id: 'm-1', user_id: 'u-1', status: 'active', has_voted: false, assigned_at: '2026-10-01T10:00:00Z', metadata: {},
  suspension_status: null, user: { id: 'u-1', name: 'Asha Rai', email: 'u-1@example.org', current_ip: '203.0.113.7', updated_at: '2026-10-01T09:00:00Z' },
}

// PO 2026-10-06: the column follows the "Voter's IP Verification" setting (captureIp.active);
// IP Binding (ipBinding.active, PBDIGIT-247) is independent and does not drive it.
const mountPage = (captureIpActive, ipBindingActive = false, ipVerification = {}) => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'setup_administration' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: false },
    voters: { data: [voter], links: [], current_page: 1, per_page: 50, from: 1, to: 1, total: 1 },
    stats: { active_voters: 1, eligible_voters: 1, by_status: { active: 1 } },
    verifications: {},
    admission: { required: false, available: true, bulkVerifiableCount: 0 },
    identityVerification: { 'u-1': 'verification_required' },
    captureIp: { active: captureIpActive },
    ipBinding: { active: ipBindingActive },
    ipVerification,
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {} } } },
    stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: AttestationDialogStub, VoterVerificationModal: IpModalStub, BulkVerifyDialog: true, teleport: true },
  },
})

const headers = (wrapper) => wrapper.findAll('th').map(th => th.text().trim())

describe('Voter Management — Capture IP Address (separate from identity)', () => {
  it('P1: with IP verification enabled, the IP Address column offers Verify IP Address (IP Binding inactive)', () => {
    const wrapper = mountPage(true, false)
    expect(headers(wrapper)).toContain('IP Address')
    expect(wrapper.find('[data-testid="capture-ip"]').exists()).toBe(true)
  })

  it('P2: with IP verification disabled, there is no IP Address column and no IP action', () => {
    const wrapper = mountPage(false)
    expect(headers(wrapper)).not.toContain('IP Address')
    expect(wrapper.find('[data-testid="capture-ip"]').exists()).toBe(false)
  })

  it('P5: IP Binding alone does not show the IP Address column', () => {
    const wrapper = mountPage(false, true)
    expect(headers(wrapper)).not.toContain('IP Address')
    expect(wrapper.find('[data-testid="capture-ip"]').exists()).toBe(false)
  })

  it('P3: Verify IP Address opens the IP modal, not the identity dialog', async () => {
    const wrapper = mountPage(true)
    await wrapper.get('[data-testid="capture-ip"]').trigger('click')

    expect(wrapper.findComponent(IpModalStub).props('show')).toBe(true)
    expect(wrapper.findComponent(AttestationDialogStub).props('open')).toBeFalsy()
  })

  it('P4: the identity action stays independent of IP capture', () => {
    expect(mountPage(true).find('[data-testid="actions-verify-identity"]').exists()).toBe(true)
  })
})

// UI feedback (user report 2026-10-06: "nothing happens — is the IP captured?")
describe('Voter Management — visible IP capture state and feedback', () => {
  const captured = { 'u-1': { ip_address: '203.0.113.7', captured_at: '2026-10-06T18:25:49Z', notes: 'home WiFi' } }

  it('P6: a voter without a capture shows "Not captured" and offers Verify IP Address', () => {
    const wrapper = mountPage(true)
    expect(wrapper.get('[data-testid="ip-capture-state"]').text()).toContain('Not captured')
    expect(wrapper.get('[data-testid="capture-ip"]').text()).toBe('Verify IP Address')
  })

  it('P7: a captured voter shows "Captured" with the capture time and offers Re-capture', () => {
    const wrapper = mountPage(true, false, captured)
    expect(wrapper.get('[data-testid="ip-capture-state"]').text()).toContain('Captured')
    expect(wrapper.get('[data-testid="ip-capture-state"]').text()).toContain('2026')
    expect(wrapper.get('[data-testid="capture-ip"]').text()).toBe('Re-capture IP Address')
  })

  it('P8: the modal receives the existing capture, so it shows "Previously captured"', async () => {
    const wrapper = mountPage(true, false, captured)
    await wrapper.get('[data-testid="capture-ip"]').trigger('click')
    expect(wrapper.findComponent(IpModalStub).props('existingVerification')).toEqual(captured['u-1'])
  })

  it('P9: after a successful capture the row shows a visible confirmation', async () => {
    const wrapper = mountPage(true, false, captured)
    expect(wrapper.find('[data-testid="ip-capture-confirmation"]').exists()).toBe(false)
    await wrapper.get('[data-testid="capture-ip"]').trigger('click')
    wrapper.findComponent(IpModalStub).vm.$emit('success')
    await wrapper.vm.$nextTick()
    expect(wrapper.get('[data-testid="ip-capture-confirmation"]').text()).toContain('IP address captured')
  })
})

describe('VoterVerificationModal — retargeted to the IP-only endpoint', () => {
  beforeEach(() => mockRouter.post.mockClear())

  const mountModal = () => mount(VoterVerificationModal, {
    props: { show: true, organisation: { slug: 'namaste' }, election: { slug: 'onf-2' }, voterId: 'u-1', voterName: 'Asha Rai',
      voterEmail: 'u-1@example.org', voterCurrentIp: '203.0.113.7', voterLastLogin: '2026-10-01T09:00:00Z', existingVerification: null },
    global: { stubs: { teleport: true } },
  })

  it('M1: is titled Verify IP Address, shows the last-login IP and has no identity attestation', () => {
    const wrapper = mountModal()
    expect(wrapper.text()).toContain('Verify IP Address')
    const ipInput = wrapper.find('#verified_ip')
    expect(ipInput.exists() ? ipInput.element.value : wrapper.text()).toContain('203.0.113.7')   // read-only display
    expect(wrapper.find('[data-testid="attest-identity"]').exists()).toBe(false)
  })

  it('M3: with an existing capture it shows "Previously captured" with the IP and offers Re-capture', () => {
    const wrapper = mount(VoterVerificationModal, {
      props: { show: true, organisation: { slug: 'namaste' }, election: { slug: 'onf-2' }, voterId: 'u-1', voterName: 'Asha Rai',
        voterEmail: 'u-1@example.org', voterCurrentIp: '203.0.113.7', voterLastLogin: null,
        existingVerification: { ip_address: '203.0.113.7', captured_at: '2026-10-06T18:25:49Z', notes: 'home WiFi' } },
      global: { stubs: { teleport: true } },
    })
    expect(wrapper.text()).toContain('Previously captured')
    expect(wrapper.text()).toContain('IP: 203.0.113.7')
    expect(wrapper.text()).toContain('Re-capture IP Address')
  })

  it('M2: submits to the IP-only endpoint, never the identity endpoint, and sends no IP / device value', async () => {
    const wrapper = mountModal()
    await wrapper.find('form').trigger('submit')

    expect(mockRouter.post).toHaveBeenCalledTimes(1)
    expect(mockRouter.post.mock.calls[0][0]).toBe('/mocked/elections.voters.capture-ip')
    const payload = mockRouter.post.mock.calls[0][1]
    expect(payload).toHaveProperty('user_id', 'u-1')
    expect(payload).not.toHaveProperty('verified_ip')
    expect(payload).not.toHaveProperty('verified_device_fingerprint_hash')
    expect(payload).not.toHaveProperty('attested')
  })
})
