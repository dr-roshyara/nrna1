/**
 * PBDIGIT-213 C4-B — Identity Verification cell in the voter-management table.
 * The state comes from the server (display read-model); the cell only renders it.
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import IdentityVerificationCell from '@/Components/Election/IdentityVerificationCell.vue'

const cell = (state) => mount(IdentityVerificationCell, { props: { state } })

describe('IdentityVerificationCell', () => {
  it('offers Verify Identity when verification is required', async () => {
    const wrapper = cell('verification_required')
    const button = wrapper.get('[data-testid="verify-identity"]')
    expect(button.text()).toBe('Verify Identity')
    await button.trigger('click')
    expect(wrapper.emitted('verify')).toHaveLength(1)
  })

  it('flags re-verification after the evidence was revoked', async () => {
    const wrapper = cell('reverification_required')
    expect(wrapper.text()).toContain('Re-verification required')
    await wrapper.get('[data-testid="verify-identity"]').trigger('click')
    expect(wrapper.emitted('verify')).toHaveLength(1)
  })

  it('shows a verified voter as verified', () => {
    const wrapper = cell('verified')
    expect(wrapper.text()).toContain('Verified ✓')
    expect(wrapper.find('[data-testid="verify-identity"]').exists()).toBe(false)
  })

  it('shows "Not required" when the election requires no identity verification', () => {
    const wrapper = cell('not_required')
    expect(wrapper.text()).toContain('Not required')
    expect(wrapper.find('[data-testid="verify-identity"]').exists()).toBe(false)
  })
})
