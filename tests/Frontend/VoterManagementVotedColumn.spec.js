/**
 * PBDIGIT-252 — RED: the Voter Management Voted column renders an unambiguous Yes / No (PO 2026-10-06).
 *
 * has_voted comes from codes.has_voted (server, the participation authority). Yes when it is true; No otherwise —
 * No is the fail-safe display when no completed participation exists (false, missing or null).
 * The same value hides the row actions of a voter who has voted.
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

const row = (id, hasVoted) => ({
  id: `m-${id}`, user_id: `u-${id}`, status: 'active', suspension_status: null, metadata: {}, has_voted: hasVoted,
  user: { id: `u-${id}`, name: `Voter ${id}`, email: `u-${id}@example.org`, current_ip: null },
})

const mountPage = () => mount(VotersIndex, {
  props: {
    election: { id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real', state: 'voting_active' },
    organisation: { slug: 'namaste', name: 'Namaste', uses_full_membership: false },
    voters: { data: [row('a', true), row('b', false), row('c', undefined), row('d', null)], links: [], current_page: 1, per_page: 50, from: 1, to: 4, total: 4 },
    stats: { active_voters: 4, eligible_voters: 4, by_status: {} },
    admission: { required: false, available: true, bulkVerifiableCount: 0 },
    identityVerification: {},
    capabilities: { manageVoters: false, suspendVoters: true, verifyIdentity: true, captureIp: true },
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { errors: {}, flash: {} } } },
    stubs: { PublicDigitLayout: LayoutStub, IdentityAttestationDialog: true, VoterVerificationModal: true, BulkVerifyDialog: true, teleport: true },
  },
})

describe('Voter Management — Voted column (PBDIGIT-252)', () => {
  it('V-UI1: shows Yes for a voter who has voted and No otherwise (No is the fail-safe)', () => {
    const states = mountPage().findAll('[data-testid="voted-state"]').map(c => c.text().trim())
    expect(states).toEqual(['Yes', 'No', 'No', 'No'])
  })

  it('V-UI2: a voter who has voted gets no row actions; the others keep them', () => {
    const rows = mountPage().findAll('tbody tr')
    expect(rows[0].find('[data-testid="actions-verify-identity"]').exists()).toBe(false)
    expect(rows[1].find('[data-testid="actions-verify-identity"]').exists()).toBe(true)
  })
})
