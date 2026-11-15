// PBDIGIT-355 (frontend M1): the read-only Reference Geography explorer pages.
// Synthetic page data only (no real geography). Layout is stubbed; jsdom has no layout engine, so widths are not
// measured here: the pages use the responsive PublicDigitLayout and wrap-friendly flex/grid classes.
import { describe, it, expect, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createI18n } from 'vue-i18n'
import en from '@/locales/pages/reference-geography/en.json'
import de from '@/locales/pages/reference-geography/de.json'
import np from '@/locales/pages/reference-geography/np.json'

vi.mock('@inertiajs/vue3', () => ({
  Link: { props: ['href'], template: '<a :href="href"><slot /></a>' },
  Head: { props: ['title'], template: '<span data-head :title="title" />' },
}))

import Explorer from './Explorer.vue'
import Place from './Place.vue'
import KnownAtBanner from '@/Components/ReferenceGeography/KnownAtBanner.vue'

const knownAt = { position: 7, latestUrl: '/geography?date=2020-01-01', explorerUrl: '/geography/k/7?date=2020-01-01' }

function mountPage(component, props, locale = 'en') {
  const i18n = createI18n({
    legacy: false,
    locale,
    fallbackLocale: 'en',
    messages: { en: { pages: { 'reference-geography': en } }, de: { pages: { 'reference-geography': de } }, np: { pages: { 'reference-geography': np } } },
  })
  return mount(component, {
    props,
    global: { plugins: [i18n], stubs: { PublicDigitLayout: { template: '<div data-layout><slot /></div>' } } },
  })
}

const summary = (unitId, label, url = `/geography/k/7/places/${unitId}?date=2020-01-01`) => ({ unitId, label, classifications: {}, url })

describe('Explorer', () => {
  it('shows the empty state when nothing is officially published', () => {
    const w = mountPage(Explorer, { knownAt: { ...knownAt, position: 0 }, validDate: '2020-01-01', language: 'en', places: [], empty: true })
    expect(w.text()).toContain('No official geography has been published yet.')
    expect(w.findAll('[data-place]')).toHaveLength(0)
  })

  it('lists the top-level places as links to their pinned place pages', () => {
    const w = mountPage(Explorer, {
      knownAt, validDate: '2020-01-01', language: 'en', empty: false,
      places: [summary('a', { value: 'Sampleland', kind: 'display', language: 'en' }), summary('b', { value: null, kind: 'none', language: null })],
    })
    const items = w.findAll('[data-place]')
    expect(items).toHaveLength(2)
    expect(items[0].text()).toContain('Sampleland')
    expect(items[0].find('a').attributes('href')).toBe('/geography/k/7/places/a?date=2020-01-01')
    expect(items[1].text()).toContain('No published name')
    expect(w.text()).not.toContain('No official geography has been published yet.')
  })

  it('marks a platform-fallback name and never shows it as a translation', () => {
    const w = mountPage(Explorer, {
      knownAt, validDate: '2020-01-01', language: 'de', empty: false,
      places: [summary('a', { value: 'Onlyland', kind: 'display_fallback', language: 'en' })],
    }, 'de')
    const item = w.find('[data-place]')
    expect(item.text()).toContain('Onlyland')
    expect(item.find('[data-label-note]').text()).toContain('en')
  })

  it('uses the page language (Nepali)', () => {
    const w = mountPage(Explorer, { knownAt, validDate: '2020-01-01', language: 'ne', places: [], empty: true }, 'np')
    expect(w.text()).toContain(np.empty.title)
  })
})

describe('KnownAtBanner', () => {
  it('states the publication position and the valid date, and links to the latest publication', () => {
    const w = mountPage(KnownAtBanner, { knownAt, validDate: '2020-01-01' })
    expect(w.text()).toContain('7')
    expect(w.text()).toContain('2020-01-01')
    expect(w.find('a').attributes('href')).toBe('/geography?date=2020-01-01')
  })
})

describe('Place', () => {
  const place = (over = {}) => ({
    unitId: 'p', validAt: '2020-01-01', exists: true,
    label: { value: 'Bagmati', kind: 'source', language: 'ne' },
    displayName: null,
    sourceNames: [{ source: 'gazette', language: 'ne', type: 'official', value: 'बागमती' }],
    classifications: { admin: 'province' },
    identifiers: { 'iso3166-2': 'NP-P3' },
    disputedKinds: [],
    mergedInto: null,
    parents: [{ scheme: 'admin', ...summary('n', { value: 'Nepal', kind: 'display', language: 'en' }) }],
    children: [
      { scheme: 'admin', ...summary('c1', { value: 'Kathmandu', kind: 'source', language: 'en' }) },
      { scheme: 'electoral', ...summary('c2', { value: 'Constituency 1', kind: 'source', language: 'en' }) },
    ],
    ...over,
  })

  it('shows the profile, attributed source names, parents and children by scheme', () => {
    const w = mountPage(Place, { knownAt, validDate: '2020-01-01', language: 'en', place: place() })
    expect(w.text()).toContain('Bagmati')
    expect(w.text()).toContain('बागमती')
    expect(w.text()).toContain('gazette')
    expect(w.text()).toContain('province')
    expect(w.text()).toContain('NP-P3')
    expect(w.find('[data-parents] a').attributes('href')).toBe('/geography/k/7/places/n?date=2020-01-01')
    const schemes = w.findAll('[data-children-scheme]').map((s) => s.attributes('data-children-scheme'))
    expect(schemes).toEqual(['admin', 'electoral'])
    expect(w.find('[data-back]').attributes('href')).toBe('/geography/k/7?date=2020-01-01')
  })

  it('warns when a published fact is disputed and links a merged place', () => {
    const w = mountPage(Place, { knownAt, validDate: '2020-01-01', language: 'en', place: place({ disputedKinds: ['IDENTIFIER'], mergedInto: { unitId: 'm', url: '/geography/k/7/places/m?date=2020-01-01' } }) })
    expect(w.find('[data-disputed]').text()).toContain('IDENTIFIER')
    expect(w.find('[data-merged] a').attributes('href')).toBe('/geography/k/7/places/m?date=2020-01-01')
  })

  it('says when a place does not exist on the valid date and when it has no children', () => {
    const w = mountPage(Place, { knownAt, validDate: '1990-01-01', language: 'en', place: place({ exists: false, children: [] }) })
    expect(w.find('[data-not-existing]').exists()).toBe(true)
    expect(w.find('[data-no-children]').exists()).toBe(true)
  })

  it('shows the CLDR display name with its release', () => {
    const w = mountPage(Place, { knownAt, validDate: '2020-01-01', language: 'en', place: place({ displayName: { value: 'Bagmati Province', origin: 'CLDR', resolvedLocale: 'en', cldrRelease: '46', fallbackLocale: null } }) })
    expect(w.find('[data-display-name]').text()).toContain('Bagmati Province')
    expect(w.find('[data-display-name]').text()).toContain('46')
  })
})

describe('locales', () => {
  it('have the same keys in en, de and np', () => {
    const keys = (o, p = '') => Object.entries(o).flatMap(([k, v]) => (typeof v === 'object' ? keys(v, `${p}${k}.`) : [`${p}${k}`])).sort()
    expect(keys(de)).toEqual(keys(en))
    expect(keys(np)).toEqual(keys(en))
  })
})
