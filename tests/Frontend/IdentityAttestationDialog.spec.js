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

  it('sends the last-login IP only as optional metadata, null when unknown', async () => {
    const wrapper = mountDialog({ voter: { ...VOTER, lastLoginIp: '—' } })
    await wrapper.get('[data-testid="attest-identity"]').setValue(true)
    await wrapper.get('[data-testid="confirm-identity"]').trigger('click')

    expect(mockRouterPost.mock.calls[0][1].verified_ip).toBeNull()
  })

  it('keeps IP and device data in a collapsed section marked audit only, not identity proof', () => {
    const wrapper = mountDialog()
    const technical = wrapper.get('[data-testid="technical-details"]')

    expect(technical.element.tagName).toBe('DETAILS')
    expect(technical.attributes('open')).toBeUndefined()
    expect(technical.text()).toContain('Technical details — audit only, not identity proof')
    expect(technical.text()).toContain('203.0.113.7')
  })

  it('shows no IP or device data outside the technical section', () => {
    const wrapper = mountDialog()
    const outside = wrapper.text().replace(wrapper.get('[data-testid="technical-details"]').text(), '')

    expect(outside).not.toContain('203.0.113.7')
    expect(outside).not.toMatch(/\bIP\b/)
    expect(outside).not.toMatch(/fingerprint/i)
  })

  it('can be cancelled without submitting', async () => {
    const wrapper = mountDialog()
    await wrapper.get('[data-testid="cancel-identity"]').trigger('click')

    expect(wrapper.emitted('close')).toHaveLength(1)
    expect(mockRouterPost).not.toHaveBeenCalled()
  })
})
