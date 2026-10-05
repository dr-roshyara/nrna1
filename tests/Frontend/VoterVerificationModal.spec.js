/**
 * PBDIGIT-213 C4-B — the existing Verify modal: IP is audit metadata, never proof of
 * identity (ADR inv. 4), so verification must be possible without an IP address.
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

  it('sends no IP (null) when none is known, rather than a placeholder', async () => {
    const wrapper = mountModal(null)
    await wrapper.get('form').trigger('submit')
    expect(mockRouterPost).toHaveBeenCalledWith(
      '/mocked/elections.voters.verify',
      expect.objectContaining({ user_id: 'voter-1', verified_ip: null }),
      expect.any(Object),
    )
  })
})
