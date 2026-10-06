/**
 * PBDIGIT-222 — "Voter's IP Verification" setting on the election Settings page (PO 2026-10-06) — RED.
 *
 * The legacy "Voter Verification Mode" section (none / ip_only / fingerprint_only / both) mixed identity,
 * IP evidence and device concerns. It is replaced by a dedicated Enabled / Disabled setting
 * (ip_verification_enabled) that controls Capture-IP availability only. The page no longer sends the
 * legacy voter_verification_mode (non-authoritative; kept server-side for backward compatibility).
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { defineComponent, h } from 'vue'

const mockRouter = vi.hoisted(() => ({ post: vi.fn(), get: vi.fn(), patch: vi.fn(), delete: vi.fn(), reload: vi.fn() }))
vi.mock('@inertiajs/vue3', () => ({
  router: mockRouter,
  usePage: () => ({ props: { flash: {}, errors: {} } }),
  Link: { name: 'Link', render: () => null },   // the real ElectionLayout is stubbed; its imports still load
}))
vi.stubGlobal('route', vi.fn((name) => `/mocked/${name}`))

import SettingsIndex from '@/Pages/Elections/Settings/Index.vue'

const LayoutStub = defineComponent({ name: 'ElectionLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })

const mountPage = (ipVerificationEnabled) => mount(SettingsIndex, {
  props: {
    election: {
      id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real',
      ip_restriction_enabled: false, ip_restriction_max_per_ip: 5, ip_whitelist: [],
      no_vote_option_enabled: false, selection_constraint_type: 'any',
      voter_verification_mode: 'none', ip_verification_enabled: ipVerificationEnabled, settings_version: 3,
    },
    organisation: { slug: 'namaste', name: 'Namaste' },
    hasVotes: false,
    admission: null,
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { flash: {}, errors: {} } } },
    stubs: { ElectionLayout: LayoutStub, VoterAdmissionCard: true, ToggleSwitch: true, Link: true, teleport: true },
  },
})

describe("Election Settings — Voter's IP Verification", () => {
  beforeEach(() => mockRouter.patch.mockClear())

  it("S-UI1: shows Voter's IP Verification with Disabled / Enabled and the clarification text", () => {
    const wrapper = mountPage(false)
    const text = wrapper.text()

    expect(text).toContain("Voter's IP Verification")
    expect(text).toContain("Allow authorised election officers to verify and record a voter's IP address before voting.")
    expect(wrapper.find('[data-testid="ip-verification-disabled"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="ip-verification-enabled"]').exists()).toBe(true)
    expect(text).toContain('IP verification evidence is stored separately from identity verification.')
    expect(text).toContain('Enabling IP verification does not by itself enforce IP binding for voting.')
  })

  it('S-UI2: no legacy Voter Verification Mode and no device / IP-only options', () => {
    const text = mountPage(false).text()

    expect(text).not.toContain('Voter Verification Mode')
    expect(text).not.toMatch(/Device Fingerprint/i)
    expect(text).not.toContain('IP Address Only')
  })

  it('S-UI3: the stored setting is preselected', () => {
    expect(mountPage(true).get('[data-testid="ip-verification-enabled"]').element.checked).toBe(true)
    expect(mountPage(false).get('[data-testid="ip-verification-disabled"]').element.checked).toBe(true)
  })

  it('S-UI4: submit sends ip_verification_enabled and never voter_verification_mode', async () => {
    const wrapper = mountPage(false)
    await wrapper.get('[data-testid="ip-verification-enabled"]').setValue(true)
    await wrapper.get('form').trigger('submit')

    expect(mockRouter.patch).toHaveBeenCalledTimes(1)
    const [url, payload] = mockRouter.patch.mock.calls[0]
    expect(url).toBe('/mocked/elections.settings.update')
    expect(payload).toHaveProperty('ip_verification_enabled', true)
    expect(payload).not.toHaveProperty('voter_verification_mode')
  })
})
