/**
 * Identity-verification lifecycle and its separation from IP / network security — RED
 * (proposal docs/publicdigit/reviews/2026-10-05-identity-verification-lifecycle-and-email-proposal.md).
 *
 * Gap 1 — lifecycle: NOT_VERIFIED —Verify Identity→ VERIFIED —Cancel Verification→ NOT_VERIFIED.
 *   A verified voter's action is "Cancel Verification" (not "Re-verify Identity"); it calls the existing
 *   revoke endpoint (elections.voters.verification.revoke), which keeps the evidence row and its history.
 * Gap 2 — separation: the identity dialog shows and sends no IP / device data (Admission ≠ Security Policy).
 *   NOTE: this reverses the PBDIGIT-213 C4 decision (2026-10-04) that kept IP / device as audit-only
 *   metadata in a collapsed section — decision ID-D2 is open; IdentityAttestationDialog.spec.js tests
 *   68–94 pin the current design and would be replaced at GREEN.
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
import IdentityAttestationDialog from '@/Components/Election/IdentityAttestationDialog.vue'

const LayoutStub = defineComponent({ name: 'PublicDigitLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })
const AttestationDialogStub = defineComponent({
  name: 'IdentityAttestationDialog',
  props: ['open', 'voter', 'organisationSlug', 'electionSlug', 'reverify'],
  setup: () => () => h('div', { 'data-testid': 'attestation-dialog-stub' }),
})

const membership = (id, userId, name) => ({
  id, user_id: userId, status: 'active', has_voted: false, assigned_at: '2026-10-01T10:00:00Z', metadata: {},
  suspension_status: null,
  user: { id: userId, name, email: `${userId}@example.org`, current_ip: '203.0.113.7', updated_at: '2026-10-01T09:00:00Z' },
})

const UNVERIFIED = membership('m-1', 'u-1', 'Asha Rai')
const VERIFIED = membership('m-2', 'u-2', 'Bikash Thapa')
const CANCELLED = membership('m-3', 'u-3', 'Chandra Lama')

const mountPage = () => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'setup_administration' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: false },
    voters: { data: [UNVERIFIED, VERIFIED, CANCELLED], links: [], current_page: 1, per_page: 50, from: 1, to: 3, total: 3 },
    stats: { active_voters: 3, eligible_voters: 3, by_status: { active: 3 } },
    verifications: { 'u-2': { id: 'v-2' } },
    admission: { required: true, available: true, bulkVerifiableCount: 0 },
    identityVerification: { 'u-1': 'verification_required', 'u-2': 'verified', 'u-3': 'reverification_required' },
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {} } } },
    stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: AttestationDialogStub, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
  },
})

const row = (wrapper, name) => wrapper.findAll('tr').find(tr => tr.text().includes(name))

describe('Gap 1 — identity verification lifecycle in Voter Management', () => {
  beforeEach(() => Object.values(mockRouter).forEach(fn => fn.mockClear()))

  it('L1: an unverified voter offers Verify Identity', () => {
    expect(row(mountPage(), 'Asha Rai').text()).toContain('Verify Identity')
  })

  it('L2: a verified voter offers Cancel Verification, not Re-verify Identity', () => {
    const text = row(mountPage(), 'Bikash Thapa').text()
    expect(text).toContain('Cancel Verification')
    expect(text).not.toContain('Re-verify Identity')
  })

  it('L3: Cancel Verification asks for confirmation first (ID-D1) — nothing is revoked yet', async () => {
    const wrapper = mountPage()
    const cancel = row(wrapper, 'Bikash Thapa').findAll('button').find(b => b.text().includes('Cancel Verification'))
    expect(cancel, 'Cancel Verification button').toBeTruthy()

    await cancel.trigger('click')

    expect(wrapper.find('[data-testid="confirm-cancel-verification"]').exists(), 'confirmation step').toBe(true)
    expect(mockRouter.delete).not.toHaveBeenCalled()
    expect(wrapper.findComponent(AttestationDialogStub).props('open')).toBeFalsy()
  })

  it('L3b: confirming calls the existing revoke endpoint for that voter\'s evidence', async () => {
    const wrapper = mountPage()
    await row(wrapper, 'Bikash Thapa').findAll('button').find(b => b.text().includes('Cancel Verification')).trigger('click')
    await wrapper.get('[data-testid="confirm-cancel-verification"]').trigger('click')

    expect(mockRouter.delete).toHaveBeenCalledTimes(1)
    expect(mockRouter.delete.mock.calls[0][0]).toBe('/mocked/elections.voters.verification.revoke')
    expect(vi.mocked(route)).toHaveBeenCalledWith('elections.voters.verification.revoke',
      expect.objectContaining({ organisation: 'namaste', election: 'onf-2', verification: 'v-2' }))
  })

  it('L5: policy OFF hides the Identity Verification column but Verify / Cancel stay available (C4)', () => {
    const wrapper = mount(VotersIndex, {
      props: { ...mountPage().props(), admission: { required: false, available: true, bulkVerifiableCount: 0 } },
      global: {
        mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {} } } },
        stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: AttestationDialogStub, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
      },
    })
    expect(wrapper.findAll('th').map(th => th.text().trim())).not.toContain('Identity Verification')
    expect(row(wrapper, 'Asha Rai').text()).toContain('Verify Identity')
    expect(row(wrapper, 'Bikash Thapa').text()).toContain('Cancel Verification')
  })

  it('L6: policy ON shows the Identity Verification column', () => {
    expect(mountPage().findAll('th').map(th => th.text().trim())).toContain('Identity Verification')
  })

  it('L4: after cancellation the voter offers Verify Identity again (not verified)', () => {
    const text = row(mountPage(), 'Chandra Lama').text()
    expect(text).toContain('Verify Identity')
    expect(text).not.toContain('Cancel Verification')
  })
})

// Email Verification (PBDIGIT-249) and IP Address (PBDIGIT-247) columns: RED contract of those stories,
// skipped here so the identity suite (PBDIGIT-248) can go GREEN; enabled by the owning story.
describe.skip('Columns (PO 2026-10-05: COL-D1 — each column only while its policy is active) — PBDIGIT-249 / PBDIGIT-247', () => {
  // Proposed prop contract (to be fixed at GREEN): admission.required (identity, existing),
  // admission.emailRequired (EMAIL_VERIFICATION), ipBinding.active (P-BINDING, Security Policy).
  const headersWith = (admission, ipBinding) => {
    const wrapper = mount(VotersIndex, {
      props: { ...mountPage().props(), admission: { available: true, bulkVerifiableCount: 0, ...admission }, ipBinding },
      global: {
        mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {} } } },
        stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: AttestationDialogStub, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
      },
    })
    return wrapper.findAll('th').map(th => th.text().trim())
  }

  it('C1: with all three policies active, Identity Verification, Email Verification and IP Address are separate columns', () => {
    const headers = headersWith({ required: true, emailRequired: true }, { active: true })
    expect(headers).toContain('Identity Verification')
    expect(headers).toContain('Email Verification')
    expect(headers).toContain('IP Address')
  })

  it('C2: with no policy active, none of the three columns is shown', () => {
    const headers = headersWith({ required: false, emailRequired: false }, { active: false })
    expect(headers).not.toContain('Identity Verification')
    expect(headers).not.toContain('Email Verification')
    expect(headers).not.toContain('IP Address')
  })

  it('C3: each column follows only its own policy', () => {
    const headers = headersWith({ required: false, emailRequired: true }, { active: false })
    expect(headers).not.toContain('Identity Verification')
    expect(headers).toContain('Email Verification')
    expect(headers).not.toContain('IP Address')
  })
})

describe('Gap 2 — the identity dialog is not an IP / network dialog', () => {
  const mockPost = vi.fn()
  const mountDialog = () => mount(IdentityAttestationDialog, {
    props: { open: true, voter: { id: 'u-1', name: 'Asha Rai', email: 'asha@example.org', lastLoginIp: '203.0.113.7' },
      organisationSlug: 'namaste', electionSlug: 'onf-2', reverify: false },
    global: { stubs: { teleport: true } },
  })

  beforeEach(() => { mockRouter.post.mockClear(); mockPost.mockClear() })

  it('S1: shows no IP address and no device-fingerprint input', () => {
    const wrapper = mountDialog()
    expect(wrapper.text()).not.toContain('203.0.113.7')
    expect(wrapper.text().toLowerCase()).not.toContain('ip address')
    expect(wrapper.find('#identity-attestation-fingerprint').exists()).toBe(false)
  })

  it('S2: submits identity attestation without IP or device fields', async () => {
    const wrapper = mountDialog()
    await wrapper.get('[data-testid="attest-identity"]').setValue(true)
    await wrapper.get('[data-testid="confirm-identity"]').trigger('click')

    expect(mockRouter.post).toHaveBeenCalledTimes(1)
    const payload = mockRouter.post.mock.calls[0][1]
    expect(payload).not.toHaveProperty('verified_ip')
    expect(payload).not.toHaveProperty('verified_device_fingerprint_hash')
    expect(payload).not.toHaveProperty('verified_device_components')
  })
})
