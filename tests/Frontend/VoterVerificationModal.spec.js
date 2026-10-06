/**
 * PBDIGIT-213 C4-B — the existing Verify modal: IP is audit metadata, never proof of
 * identity (ADR inv. 4), so verification must be possible without an IP address.
 * PBDIGIT-222: the modal is now the dedicated Capture-IP modal (elections.voters.capture-ip).
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'

const mockRouterPost = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { post: (...args) => mockRouterPost(...args) },
}))
vi.stubGlobal('route', vi.fn((name) => `/mocked/${name}`))

import VoterVerificationModal from '@/Components/Election/VoterVerificationModal.vue'

const mountModal = (voterCurrentIp) => mount(VoterVerificationModal, {
  props: {
    show: true,
    voterId: 'voter-1',
    voterName: 'Ram Sharma',
    voterCurrentIp,
    organisation: { slug: 'namaste' },
    election: { slug: 'onf-2' },
  },
  global: { stubs: { teleport: true } },
})

describe('VoterVerificationModal — IP optional', () => {
  beforeEach(() => mockRouterPost.mockClear())

  it('allows verification without an IP address', () => {
    const submit = mountModal(null).get('button[type="submit"]')
    expect(submit.attributes('disabled')).toBeUndefined()
  })

  it('is titled Verify IP Address and never presents itself as identity verification', () => {
    // PO 2026-10-04: this is the legacy IP / device action; identity attestation is
    // IdentityAttestationDialog. The two must not share a name.
    const wrapper = mountModal('203.0.113.7')
    expect(wrapper.get('.modal-title').text()).toBe('Verify IP Address')
    expect(wrapper.text()).not.toMatch(/identity/i)
  })

  // PBDIGIT-222 (PO-approved replacement, 2026-10-06): the old contract — post to the identity endpoint
  // with verified_ip: null — is superseded. The modal is IP capture only; the server derives the IP.
  it('submits only {user_id, notes} to the IP-capture endpoint — never the identity endpoint, no client IP or device data', async () => {
    const wrapper = mountModal(null)
    await wrapper.get('form').trigger('submit')

    expect(mockRouterPost).toHaveBeenCalledTimes(1)
    const [url, payload] = mockRouterPost.mock.calls[0]
    expect(url).toBe('/mocked/elections.voters.capture-ip')
    expect(url).not.toBe('/mocked/elections.voters.verify')
    expect(Object.keys(payload).sort()).toEqual(['notes', 'user_id'])
    expect(payload.user_id).toBe('voter-1')
    expect(payload).not.toHaveProperty('verified_ip')
    expect(payload).not.toHaveProperty('ip_address')
    expect(payload).not.toHaveProperty('verified_device_fingerprint_hash')
    expect(payload).not.toHaveProperty('verified_device_components')
    expect(payload).not.toHaveProperty('attested')
  })
})
