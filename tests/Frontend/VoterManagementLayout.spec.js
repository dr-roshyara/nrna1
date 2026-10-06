/**
 * PBDIGIT-220 — the outdated left column of Voter Management is removed (PO, 2026-10-04).
 *
 * The column held: election context + stats, "Assign users/members as voters" (member
 * search, bulk assign, assign by user ID) and Export CSV. Adding organisation members as
 * election voters becomes its own story (PBDIGIT-221). Export CSV must not be lost: it
 * moves to the page header actions.
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

const mountPage = ({ fullMembership = false } = {}) => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'setup_administration' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: fullMembership },
    voters: { data: [], links: [], current_page: 1, per_page: 50, from: 0, to: 0, total: 0 },
    stats: { active_voters: 0, eligible_voters: 0, by_status: {} },
    unassignedMembers: [{ id: 'u-9', name: 'Jane Smith', email: 'jane@example.com' }],
    admission: { required: false, available: true, bulkVerifiableCount: 0 },
    identityVerification: {},
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {} } } },
    stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: true, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
  },
})

describe('Voter Management layout (PBDIGIT-220)', () => {
  it('has no left column', () => {
    const wrapper = mountPage()
    expect(wrapper.find('aside').exists()).toBe(false)
    expect(wrapper.find('.voters-sidebar').exists()).toBe(false)
  })

  it.each([false, true])('offers no way to assign organisation members as voters (full membership: %s)', (fullMembership) => {
    const wrapper = mountPage({ fullMembership })
    expect(wrapper.text()).not.toMatch(/assign (users|members) as voters/i)
    expect(wrapper.find('input[placeholder="Search members…"]').exists()).toBe(false)
    expect(wrapper.find('.uuid-form').exists()).toBe(false)
    expect(wrapper.text()).not.toContain('Jane Smith')
  })

  it('keeps Export CSV, now in the page header actions', () => {
    const exportLink = mountPage().find('.voters-page-actions a[href="/mocked/elections.voters.export"]')
    expect(exportLink.exists()).toBe(true)
    expect(exportLink.text()).toContain('Export CSV')
  })

  it('no longer describes the page as assigning voters', () => {
    expect(mountPage().get('.voters-page-subtitle').text()).not.toMatch(/assign/i)
  })

  it('links back to the Election Management page', () => {
    const back = mountPage().get('[data-testid="back-to-election-management"]')
    expect(back.attributes('href')).toBe('/mocked/elections.management')
    expect(back.text()).toContain('Back to Election Management')
  })
})
