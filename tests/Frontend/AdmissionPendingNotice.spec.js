/** PBDIGIT-213 C4-D — the voter learns why they cannot vote yet (display only). */
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import AdmissionPendingNotice from '@/Components/Election/AdmissionPendingNotice.vue'

describe('AdmissionPendingNotice', () => {
  it('tells the voter that the Chief or Deputy Chief must verify their identity first', () => {
    expect(mount(AdmissionPendingNotice).text())
      .toContain('Your identity must be verified by the Chief or Deputy Chief of the election before you can vote.')
  })
})
