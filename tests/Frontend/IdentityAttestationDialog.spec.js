/**
 * PBDIGIT-213 C4 correction, Slice 2 — the individual identity-admission dialog.
 *
 * The officer attests the voter's IDENTITY. This creates OFFICER_IDENTITY_VERIFICATION
 * evidence (the same requirement that Verify All satisfies). IP and device data are
 * technical audit metadata only: they must never appear to be the identity criterion.
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'

const mockRouterPost = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { post: (...args) => mockRouterPost(...args) },
}))
vi.stubGlobal('route', vi.fn((name, params) => `/mocked/${name}/${params?.organisation ?? ''}/${params?.election ?? ''}`))

import IdentityAttestationDialog from '@/Components/Election/IdentityAttestationDialog.vue'

const STATEMENT = "I confirm that this voter's identity has been verified according to the organisation's voter-registration process."

const VOTER = { id: 'u-1', name: 'Asha Rai', email: 'asha@example.org', lastLoginIp: '203.0.113.7' }

const mountDialog = (props = {}) => mount(IdentityAttestationDialog, {
  props: { open: true, voter: VOTER, organisationSlug: 'namaste', electionSlug: 'onf-2', reverify: false, ...props },
  global: { stubs: { teleport: true } },
})

describe('IdentityAttestationDialog', () => {
  beforeEach(() => mockRouterPost.mockClear())

  it('is titled Verify Identity and names the voter', () => {
    const text = mountDialog().text()
    expect(text).toContain('Verify Identity')
    expect(text).toContain('Asha Rai')
    expect(text).toContain('asha@example.org')
  })

  it('is titled Re-verify Identity for a re-verification', () => {
    expect(mountDialog({ reverify: true }).text()).toContain('Re-verify Identity')
  })

  it('shows the identity attestation statement', () => {
    expect(mountDialog().text()).toContain(STATEMENT)
  })

  it('cannot be confirmed until the attestation is checked', async () => {
    const wrapper = mountDialog()
    // Re-query after each change: the teleported subtree may be re-created on re-render.
    const confirmButton = () => wrapper.get('[data-testid="confirm-identity"]')
    expect(confirmButton().attributes('disabled')).toBeDefined()

    await wrapper.get('[data-testid="attest-identity"]').setValue(true)
    expect(confirmButton().attributes('disabled')).toBeUndefined()
  })

  it('submits the attestation to the individual verification endpoint', async () => {
    const wrapper = mountDialog()
    await wrapper.get('[data-testid="attest-identity"]').setValue(true)
    await wrapper.get('[data-testid="confirm-identity"]').trigger('click')

    expect(mockRouterPost).toHaveBeenCalledWith(
      '/mocked/elections.voters.verify/namaste/onf-2',
      expect.objectContaining({ user_id: 'u-1', attested: true }),
      expect.any(Object),
    )
  })

  // PBDIGIT-248 (ID-D2, PO 2026-10-05) supersedes the C4 "technical details — audit only" section:
  // identity verification carries no IP / device data at all (detailed contract: VoterIdentityLifecycle.spec.js S1/S2).
  it('carries no IP or device data — no technical section, nothing sent', async () => {
    const wrapper = mountDialog()
    expect(wrapper.find('[data-testid="technical-details"]').exists()).toBe(false)
    expect(wrapper.text()).not.toMatch(/\bIP\b|fingerprint/i)

    await wrapper.get('[data-testid="attest-identity"]').setValue(true)
    await wrapper.get('[data-testid="confirm-identity"]').trigger('click')
    expect(mockRouterPost.mock.calls[0][1]).toEqual({ user_id: 'u-1', attested: true, notes: null })
  })

  it('can be cancelled without submitting', async () => {
    const wrapper = mountDialog()
    await wrapper.get('[data-testid="cancel-identity"]').trigger('click')

    expect(wrapper.emitted('close')).toHaveLength(1)
    expect(mockRouterPost).not.toHaveBeenCalled()
  })
})
