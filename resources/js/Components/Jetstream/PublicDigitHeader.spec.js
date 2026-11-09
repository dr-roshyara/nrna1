// PBDIGIT-338: header menu behaviour and member navigation.
// jsdom has no layout engine, so widths and overflow are checked in headless Chrome, not here.
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { mount } from '@vue/test-utils'
import { reactive, nextTick } from 'vue'

const page = reactive({ url: '/', props: { user: null } })
const navigateHandlers = []
const unsubscribe = vi.fn()

vi.mock('@inertiajs/vue3', () => ({
  usePage: () => page,
  router: {
    post: vi.fn(),
    on: vi.fn((event, handler) => {
      if (event === 'navigate') navigateHandlers.push(handler)
      return unsubscribe
    }),
  },
  Link: { props: ['href'], template: '<a :href="href" @click.prevent><slot /></a>' },
}))

vi.mock('vue-i18n', () => ({
  useI18n: () => ({ t: (key, fallback) => fallback ?? key, locale: { value: 'en' } }),
}))

// Synthetic route state: no real organisation or member data.
let currentParams = {}
let currentName = null
vi.mock('ziggy-js', () => ({
  route: (name, params) => {
    if (name === undefined) {
      return { params: currentParams, current: (n) => n === currentName }
    }
    return `/r/${name}${params ? '/' + Object.values(params).join('/') : ''}`
  },
}))

import PublicDigitHeader from './PublicDigitHeader.vue'

function mountHeader() {
  return mount(PublicDigitHeader, {
    attachTo: document.body,
    global: {
      mocks: { $t: (key, fallback) => fallback ?? key, $page: page },
    },
  })
}

const memberHrefs = (wrapper) => wrapper.findAll('[data-nav="member"]').map((a) => a.attributes('href'))

describe('PublicDigitHeader member navigation', () => {
  beforeEach(() => {
    page.props.user = null
    page.url = '/'
    currentParams = {}
    currentName = null
    navigateHandlers.length = 0
    unsubscribe.mockClear()
  })
  afterEach(() => { document.body.innerHTML = '' })

  it('shows no member links to a guest', () => {
    const w = mountHeader()
    expect(memberHrefs(w)).toEqual([])
  })

  it('shows only "My organisations" when the URL carries no organisation', () => {
    page.props.user = { id: 1, name: 'Test Member', organisation: { slug: 'record-org' } }
    const w = mountHeader()
    expect(memberHrefs(w)).toEqual(['/r/organisations.index'])
  })

  it('adds the organisation pages for the organisation in the URL, not the one on the user record', () => {
    page.props.user = { id: 1, name: 'Test Member', organisation: { slug: 'record-org' } }
    currentParams = { organisation: 'url-org' }
    const w = mountHeader()
    expect(memberHrefs(w)).toEqual([
      '/r/organisations.index',
      '/r/organisations.membership.dashboard/url-org',
      '/r/organisations.elections.index/url-org',
      '/r/organisations.candidacy.create/url-org',
    ])
  })

  it('reads the slug on the organisation home page', () => {
    page.props.user = { id: 1, name: 'Test Member' }
    currentParams = { slug: 'home-org' }
    currentName = 'organisations.show'
    const w = mountHeader()
    expect(memberHrefs(w)).toContain('/r/organisations.elections.index/home-org')
  })

  it('links "My membership" only for the organisation in the URL', async () => {
    page.props.user = { id: 1, name: 'Test Member', organisation: { slug: 'record-org' } }
    const w1 = mountHeader()
    expect(w1.html()).not.toContain('organisations.membership.dashboard')
    currentParams = { organisation: 'url-org' }
    const w2 = mountHeader()
    await w2.find('button[aria-expanded]').trigger('click')
    expect(w2.find('#pd-mobile-menu [href="/r/organisations.membership.dashboard/url-org"]').exists()).toBe(true)
    // Member links never use the organisation on the user record (the demo links still do; pre-existing).
    expect(memberHrefs(w2).some((h) => h.includes('record-org'))).toBe(false)
  })

  it('updates the links after an Inertia visit changes the URL', async () => {
    page.props.user = { id: 1, name: 'Test Member' }
    const w = mountHeader()
    expect(memberHrefs(w)).toHaveLength(1)
    currentParams = { organisation: 'url-org' }
    page.url = '/organisations/url-org'
    await nextTick()
    expect(memberHrefs(w)).toHaveLength(4)
  })
})

describe('PublicDigitHeader phone menu behaviour', () => {
  beforeEach(() => {
    page.props.user = { id: 1, name: 'Test Member' }
    currentParams = {}
    navigateHandlers.length = 0
    unsubscribe.mockClear()
  })
  afterEach(() => { document.body.innerHTML = '' })

  it('links the toggle to the panel it opens', async () => {
    const w = mountHeader()
    const toggle = w.find('button[aria-expanded]')
    expect(toggle.attributes('aria-expanded')).toBe('false')
    await toggle.trigger('click')
    expect(toggle.attributes('aria-expanded')).toBe('true')
    expect(w.find(`#${toggle.attributes('aria-controls')}`).exists()).toBe(true)
  })

  it('closes on Escape and returns focus to the toggle', async () => {
    const w = mountHeader()
    const toggle = w.find('button[aria-expanded]')
    await toggle.trigger('click')
    document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }))
    await nextTick()
    expect(w.find('#pd-mobile-menu').exists()).toBe(false)
    expect(document.activeElement).toBe(toggle.element)
  })

  it('closes on Inertia navigation, which includes browser back and forward', async () => {
    const w = mountHeader()
    await w.find('button[aria-expanded]').trigger('click')
    expect(navigateHandlers).toHaveLength(1)
    navigateHandlers[0]({ detail: { page: {} } })
    await nextTick()
    expect(w.find('#pd-mobile-menu').exists()).toBe(false)
  })

  it('closes when a member link is followed', async () => {
    const w = mountHeader()
    await w.find('button[aria-expanded]').trigger('click')
    await w.find('#pd-mobile-menu [data-nav="member"]').trigger('click')
    expect(w.find('#pd-mobile-menu').exists()).toBe(false)
  })

  it('closes when the window widens to the desktop breakpoint (1024 px)', async () => {
    const w = mountHeader()
    await w.find('button[aria-expanded]').trigger('click')
    window.innerWidth = 1023
    window.dispatchEvent(new Event('resize'))
    await nextTick()
    expect(w.find('#pd-mobile-menu').exists()).toBe(true)
    window.innerWidth = 1024
    window.dispatchEvent(new Event('resize'))
    await nextTick()
    expect(w.find('#pd-mobile-menu').exists()).toBe(false)
  })

  it('removes the navigation listener on unmount', () => {
    const w = mountHeader()
    w.unmount()
    expect(unsubscribe).toHaveBeenCalledTimes(1)
  })
})
