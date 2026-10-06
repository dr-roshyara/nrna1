/**
 * PBDIGIT-253 — RED: Voter Management "Voted" filter, next to "All statuses" (PO 2026-10-06).
 *
 * A select All / Voted / Not voted sends ?voted=voted|not_voted. Filtering must stay on THIS page
 * (elections.voters.index, /voters/manage) — today applyFilters() navigates to the read-only list
 * (organisations.elections.voters), which is a defect fixed in the same story.
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

const LayoutStub = defineComponent({ name: 'PublicDigitLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })

const mountPage = (filters = {}) => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'voting_active' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: false },
    voters: { data: [], links: [], current_page: 1, per_page: 50, from: 0, to: 0, total: 0 },
    stats: { active_voters: 0, eligible_voters: 0, by_status: {} },
    admission: { required: false, available: true, bulkVerifiableCount: 0 },
    identityVerification: {},
    filters,
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {}, flash: {} } } },
    stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: true, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
  },
})

describe('Voter Management — Voted filter (PBDIGIT-253)', () => {
  beforeEach(() => mockRouter.get.mockClear())

  it('F-UI1: offers All / Voted / Not voted next to the status filter', () => {
    const select = mountPage().find('[data-testid="voted-filter"]')
    expect(select.exists()).toBe(true)
    expect(select.findAll('option').map(o => [o.attributes('value'), o.text().trim()]))
      .toEqual([['', 'All'], ['voted', 'Voted'], ['not_voted', 'Not voted']])
  })

  it('F-UI2: choosing Voted reloads THIS page with voted=voted', async () => {
    await mountPage().get('[data-testid="voted-filter"]').setValue('voted')

    expect(mockRouter.get).toHaveBeenCalledTimes(1)
    const [url, params] = mockRouter.get.mock.calls[0]
    expect(url).toBe('/mocked/elections.voters.index')
    expect(params).toMatchObject({ voted: 'voted' })
  })

  it('F-UI3: the status filter also stays on this page (no longer the read-only list)', async () => {
    await mountPage().get('select.filter-select').setValue('active')

    expect(mockRouter.get.mock.calls[0][0]).toBe('/mocked/elections.voters.index')
  })

  it('F-UI4: the active voted filter is preselected from the server', () => {
    expect(mountPage({ voted: 'not_voted' }).get('[data-testid="voted-filter"]').element.value).toBe('not_voted')
  })
})
