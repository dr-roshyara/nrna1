/**
 * PBDIGIT-247 — RED: the IP Binding choice on the election Settings page (PO 2026-10-06).
 *
 * Inside "Voter's IP Verification": IP Binding Disabled / Enabled. Binding needs IP verification, so the
 * Enabled option is unavailable while IP verification is Disabled. The payload carries ip_binding_enabled.
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { defineComponent, h } from 'vue'

const mockRouter = vi.hoisted(() => ({ post: vi.fn(), get: vi.fn(), patch: vi.fn(), delete: vi.fn(), reload: vi.fn() }))
vi.mock('@inertiajs/vue3', () => ({
  router: mockRouter,
  usePage: () => ({ props: { flash: {}, errors: {} } }),
  Link: { name: 'Link', render: () => null },
}))
vi.stubGlobal('route', vi.fn((name) => `/mocked/${name}`))

import SettingsIndex from '@/Pages/Elections/Settings/Index.vue'

const LayoutStub = defineComponent({ name: 'ElectionLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })

const mountPage = ({ verification = true, binding = false } = {}) => mount(SettingsIndex, {
  props: {
    election: {
      id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real',
      ip_restriction_enabled: false, ip_restriction_max_per_ip: 5, ip_whitelist: [],
      no_vote_option_enabled: false, selection_constraint_type: 'any',
      ip_verification_enabled: verification, ip_binding_enabled: binding, settings_version: 3,
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

describe('Election Settings — IP Binding', () => {
  beforeEach(() => mockRouter.patch.mockClear())

  it('B-UI1: offers IP Binding Disabled / Enabled with the binding explanation', () => {
    const wrapper = mountPage()
    expect(wrapper.find('[data-testid="ip-binding-disabled"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="ip-binding-enabled"]').exists()).toBe(true)
    expect(wrapper.text()).toContain('Voters can only get a voting code and vote from their verified IP address')
  })

  it('B-UI2: IP Binding cannot be enabled while IP verification is disabled', () => {
    const wrapper = mountPage({ verification: false })
    expect(wrapper.get('[data-testid="ip-binding-enabled"]').attributes('disabled')).toBeDefined()
  })

  it('B-UI3: the stored binding is preselected', () => {
    expect(mountPage({ binding: true }).get('[data-testid="ip-binding-enabled"]').element.checked).toBe(true)
    expect(mountPage({ binding: false }).get('[data-testid="ip-binding-disabled"]').element.checked).toBe(true)
  })

  it('B-UI4: submit sends ip_binding_enabled', async () => {
    const wrapper = mountPage()
    await wrapper.get('[data-testid="ip-binding-enabled"]').setValue(true)
    await wrapper.get('form').trigger('submit')

    expect(mockRouter.patch).toHaveBeenCalledTimes(1)
    expect(mockRouter.patch.mock.calls[0][1]).toHaveProperty('ip_binding_enabled', true)
  })
})
