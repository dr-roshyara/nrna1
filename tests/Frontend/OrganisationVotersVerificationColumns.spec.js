/**
 * PBDIGIT-250 — read-only voter list: verification state columns (PO 2026-10-06) — RED.
 *
 * Organisations/Voters.vue shows two READ-ONLY columns, each only when its policy is on:
 *   Identity Verification     verificationColumns.identity  →  row.identity_state
 *   IP Address Verification   verificationColumns.ip        →  row.ip_state
 * States only: no IP address, no action buttons (this page is visible to every voter of the election).
 */
import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'

vi.mock('@inertiajs/vue3', () => ({
  router: { get: vi.fn() },
  Link: { template: '<a><slot /></a>' },
}))
globalThis.route = vi.fn((name) => `/mock/${name}`)

import Voters from '@/Pages/Organisations/Voters.vue'

const row = (id, name, identity_state, ip_state) => ({
  id, name, status: 'active', suspension_status: 'none', has_voted: false, last_login_at: null, identity_state, ip_state,
})

const mountPage = ({ identity = false, ip = false } = {}) => mount(Voters, {
  props: {
    organisation: { id: 'org-1', name: 'Test Org', slug: 'test-org' },
    election: { id: 'e-1', name: 'ONF 2', slug: 'onf-2', status: 'active' },
    voters: {
      data: [
        row('m-1', 'Asha Rai', identity ? 'verified' : null, ip ? 'verified' : null),
        row('m-2', 'Bina Lama', identity ? 'verification_required' : null, ip ? 'not_verified' : null),
        row('m-3', 'Chandra KC', identity ? 'reverification_required' : null, ip ? 'not_verified' : null),
      ],
      current_page: 1, last_page: 1, total: 3, prev_page_url: null, next_page_url: null,
    },
    stats: { total: 3, voted: 0, not_voted: 3, participation_percentage: 0 },
    filters: { sort: 'assigned_at', direction: 'asc', status: '', voted: '' },
    verificationColumns: { identity, ip },
  },
  global: { stubs: { ElectionLayout: { template: '<div><slot /></div>' } } },
})

const headers = (wrapper) => wrapper.findAll('th').map(th => th.text().trim())
const cells = (wrapper, testid) => wrapper.findAll(`[data-testid="${testid}"]`).map(c => c.text().trim())

describe('Organisations/Voters.vue — verification state columns (read-only)', () => {
  it('C1: both policies off — neither column is shown', () => {
    const wrapper = mountPage()
    expect(headers(wrapper)).not.toContain('Identity Verification')
    expect(headers(wrapper)).not.toContain('IP Address Verification')
    expect(wrapper.find('[data-testid="identity-state"]').exists()).toBe(false)
    expect(wrapper.find('[data-testid="ip-state"]').exists()).toBe(false)
  })

  it('C2: identity required — the Identity Verification column shows each evidence state', () => {
    const wrapper = mountPage({ identity: true })
    expect(headers(wrapper)).toContain('Identity Verification')
    expect(headers(wrapper)).not.toContain('IP Address Verification')
    expect(cells(wrapper, 'identity-state')).toEqual(['Verified', 'Verification required', 'Re-verification required'])
  })

  it('C3: IP verification on — the IP Address Verification column shows Verified / Not verified', () => {
    const wrapper = mountPage({ ip: true })
    expect(headers(wrapper)).toContain('IP Address Verification')
    expect(headers(wrapper)).not.toContain('Identity Verification')
    expect(cells(wrapper, 'ip-state')).toEqual(['Verified', 'Not verified', 'Not verified'])
  })

  it('C4: the columns are read-only — no buttons in them and no IP address shown', () => {
    const wrapper = mountPage({ identity: true, ip: true })
    for (const cell of wrapper.findAll('[data-testid="identity-state"], [data-testid="ip-state"]')) {
      expect(cell.find('button').exists()).toBe(false)
      expect(cell.find('a').exists()).toBe(false)
    }
    expect(wrapper.text()).not.toMatch(/\b\d{1,3}(\.\d{1,3}){3}\b/)
  })
})
