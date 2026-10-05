/**
 * PBDIGIT-213 C4-C — "Verify All" bulk-attestation dialog (PO decisions C4-D1…D4).
 * The server recomputes the candidates and refuses a changed count; the dialog only
 * collects the explicit attestation and the count the officer saw.
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'

const mockRouterPost = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { post: (...args) => mockRouterPost(...args) },
}))
vi.stubGlobal('route', vi.fn((name, params) => `/mocked/${name}/${params?.organisation ?? ''}/${params?.election ?? ''}`))

import BulkVerifyDialog from '@/Components/Election/BulkVerifyDialog.vue'

const STATEMENT = "I confirm that the identities of these voters have already been verified according to the organisation's voter-registration process."

const mountDialog = () => mount(BulkVerifyDialog, {
  props: { open: true, count: 247, organisationSlug: 'namaste', electionSlug: 'onf-2' },
  global: { stubs: { teleport: true } },
})

describe('BulkVerifyDialog', () => {
  beforeEach(() => mockRouterPost.mockClear())

  it('shows the exact number of voters and the attestation statement', () => {
    const text = mountDialog().text()
    expect(text).toContain('Verify 247 voters?')
    expect(text).toContain(STATEMENT)
  })

  it('cannot be confirmed until the attestation is checked', async () => {
    const wrapper = mountDialog()
    // Query the button afresh after each change: the teleported subtree is re-created
    // on re-render, so an earlier reference would point at a detached node.
    const confirmButton = () => wrapper.get('[data-testid="confirm-verify-all"]')
    expect(confirmButton().attributes('disabled')).toBeDefined()

    await wrapper.get('[data-testid="attest-checkbox"]').setValue(true)
    expect(confirmButton().attributes('disabled')).toBeUndefined()
  })

  it('submits the attestation and the expected count to the bulk endpoint', async () => {
    const wrapper = mountDialog()
    await wrapper.get('[data-testid="attest-checkbox"]').setValue(true)
    await wrapper.get('[data-testid="confirm-verify-all"]').trigger('click')

    expect(mockRouterPost).toHaveBeenCalledWith(
      '/mocked/elections.voters.verify-all/namaste/onf-2',
      { attested: true, expected_count: 247 },
      expect.any(Object),
    )
  })

  it('can be cancelled', async () => {
    const wrapper = mountDialog()
    await wrapper.get('[data-testid="cancel-verify-all"]').trigger('click')
    expect(wrapper.emitted('close')).toHaveLength(1)
    expect(mockRouterPost).not.toHaveBeenCalled()
  })
})
