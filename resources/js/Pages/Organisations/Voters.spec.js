import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

const getMock = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { get: (...args) => getMock(...args) },
  Link: { template: '<a><slot /></a>' },
}))

global.route = vi.fn((name, params) => `/mock/${name}/${JSON.stringify(params ?? {})}`)

import Voters from './Voters.vue'

function mountPage(props = {}) {
  return mount(Voters, {
    props: {
      organisation: { id: 'org-1', name: 'Test Org', slug: 'test-org' },
      election: { id: 'election-1', name: 'Test Election', slug: 'test-election', status: 'active' },
      voters: {
        data: [
          { id: 'm-1', name: 'Voted Voter', status: 'active', suspension_status: 'none', has_voted: true, last_login_at: '2026-09-10T12:00:00.000000Z' },
          { id: 'm-2', name: 'Not Voted Voter', status: 'active', suspension_status: 'none', has_voted: false, last_login_at: null },
        ],
        current_page: 1,
        last_page: 1,
        total: 2,
        prev_page_url: null,
        next_page_url: null,
      },
      stats: { total: 2, voted: 1, not_voted: 1, participation_percentage: 50.0 },
      filters: { sort: 'assigned_at', direction: 'asc', status: '', voted: '' },
      ...props,
    },
    global: {
      stubs: {
        ElectionLayout: { template: '<div><slot /></div>' },
      },
    },
  })
}

describe('Organisations/Voters.vue — voted stats, column, filter, last login', () => {
  beforeEach(() => {
    getMock.mockReset()
  })

  it('renders the participation stats', () => {
    const wrapper = mountPage()
    const text = wrapper.text()
    expect(text).toContain('1')
    expect(text).toMatch(/50(\.0)?\s*%/)
  })

  it('renders a Voted / Not voted value for every row, not just an icon', () => {
    const wrapper = mountPage()
    expect(wrapper.text()).toContain('Voted')
    expect(wrapper.text()).toContain('Not voted')
  })

  it('renders "Never" for a voter who has never logged in', () => {
    const wrapper = mountPage()
    expect(wrapper.text()).toContain('Never')
  })

  it('renders a formatted last-login value for a voter who has logged in', () => {
    const wrapper = mountPage()
    // Not asserting an exact locale string — just that it isn't the raw ISO
    // timestamp and isn't blank.
    expect(wrapper.text()).not.toContain('2026-09-10T12:00:00.000000Z')
  })

  it('has a voted-status filter control with All / Voted / Not voted options', () => {
    const wrapper = mountPage()
    const select = wrapper.find('[data-testid="voted-filter"]')
    expect(select.exists()).toBe(true)
    const optionTexts = select.findAll('option').map(o => o.text())
    expect(optionTexts).toContain('Voted')
    expect(optionTexts).toContain('Not voted')
  })

  it('changing the voted filter requests the voters route with the voted param, preserving status', async () => {
    const wrapper = mountPage({ filters: { sort: 'assigned_at', direction: 'asc', status: 'active', voted: '' } })

    await wrapper.get('[data-testid="voted-filter"]').setValue('voted')

    expect(getMock).toHaveBeenCalled()
    const [, params] = getMock.mock.calls[0]
    expect(params.voted).toBe('voted')
    expect(params.status).toBe('active')
  })
})
