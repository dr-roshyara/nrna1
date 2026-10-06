/**
 * PBDIGIT-254 — RED: Election Settings shows why a save was refused (PO 2026-10-06:
 * "it should at least show the error why setting can not be changed").
 *
 * Today submit() discards the server's errors (onError only re-enables the button), so a refused save — e.g. the
 * PBDIGIT-244 lock while voting — looks like "the selection is not saved".
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { defineComponent, h, nextTick } from 'vue'

const mockRouter = vi.hoisted(() => ({ post: vi.fn(), get: vi.fn(), patch: vi.fn(), delete: vi.fn(), reload: vi.fn() }))
vi.mock('@inertiajs/vue3', () => ({
  router: mockRouter,
  usePage: () => ({ props: { flash: {}, errors: {} } }),
  Link: { name: 'Link', render: () => null },
}))
vi.stubGlobal('route', vi.fn((name) => `/mocked/${name}`))

import SettingsIndex from '@/Pages/Elections/Settings/Index.vue'

const LayoutStub = defineComponent({ name: 'ElectionLayout', setup: (_, { slots }) => () => h('div', slots.default?.()) })

const LOCKED = 'The security policy cannot be changed once the election is ready for voting.'

const mountPage = ({ locked = false, restriction = false, frozen = false } = {}) => mount(SettingsIndex, {
  props: {
    election: {
      id: 'e-1', slug: 'onf-2', name: 'ONF 2', type: 'real',
      ip_restriction_enabled: restriction, ip_restriction_max_per_ip: 5, ip_whitelist: [],
      no_vote_option_enabled: false, selection_constraint_type: 'any',
      ip_verification_enabled: true, ip_binding_enabled: false, settings_version: 3,
    },
    organisation: { slug: 'namaste', name: 'Namaste' },
    hasVotes: false,
    admission: null,
    securityPolicyLocked: locked,
    settingsFrozen: frozen,
  },
  global: {
    mocks: { route: (name) => `/mocked/${name}`, $page: { props: { flash: {}, errors: {} } } },
    stubs: { ElectionLayout: LayoutStub, VoterAdmissionCard: true, ToggleSwitch: true, Link: true, teleport: true },
  },
})

/** Make the next router.patch answer like the server: call onError(errors) or onSuccess(). */
const serverAnswers = (errors) => mockRouter.patch.mockImplementationOnce((url, data, options) => {
  errors ? options.onError?.(errors) : options.onSuccess?.({ props: {} })
  options.onFinish?.()
})

describe('Election Settings — refused saves are explained (PBDIGIT-254)', () => {
  beforeEach(() => mockRouter.patch.mockReset())

  it('E1: a refused save shows the server message', async () => {
    const wrapper = mountPage()
    serverAnswers({ ip_binding_enabled: LOCKED })

    await wrapper.get('form').trigger('submit')
    await nextTick()

    const box = wrapper.find('[data-testid="settings-save-errors"]')
    expect(box.exists()).toBe(true)
    expect(box.attributes('role')).toBe('alert')
    expect(box.text()).toContain(LOCKED)
  })

  it('E2: a refusal of several fields lists each message', async () => {
    const wrapper = mountPage()
    serverAnswers({
      ip_binding_enabled: 'IP binding requires IP verification to be enabled.',
      settings_version: 'Settings were changed by someone else. Please reload.',
    })

    await wrapper.get('form').trigger('submit')
    await nextTick()

    const text = wrapper.get('[data-testid="settings-save-errors"]').text()
    expect(text).toContain('IP binding requires IP verification to be enabled.')
    expect(text).toContain('Settings were changed by someone else. Please reload.')
  })

  it('E3: a later successful save clears the messages', async () => {
    const wrapper = mountPage()
    serverAnswers({ ip_binding_enabled: LOCKED })
    await wrapper.get('form').trigger('submit')
    await nextTick()

    serverAnswers(null)
    await wrapper.get('form').trigger('submit')
    await nextTick()

    expect(wrapper.find('[data-testid="settings-save-errors"]').exists()).toBe(false)
  })
})

// PO 2026-10-06 ("lock locked fields + show errors"): from Ready for Voting the security-policy fields the server
// refuses (IP Binding, IP restriction, max per IP, whitelist) are shown disabled with a note; others stay editable.
describe('Election Settings — locked security fields (PBDIGIT-254)', () => {
  const LOCK_NOTE = 'Locked while the election is ready for voting or in progress'

  it('K1: when locked, IP Binding, IP restriction, max per IP and whitelist are disabled, with the note', () => {
    const wrapper = mountPage({ locked: true, restriction: true })

    expect(wrapper.get('[data-testid="ip-binding-enabled"]').attributes('disabled')).toBeDefined()
    expect(wrapper.get('[data-testid="ip-binding-disabled"]').attributes('disabled')).toBeDefined()
    expect(wrapper.findAllComponents({ name: 'ToggleSwitch' })[0].attributes('disabled')).toBeDefined()
    expect(wrapper.get('#ip-max-input').attributes('disabled')).toBeDefined()
    expect(wrapper.get('textarea').attributes('disabled')).toBeDefined()
    expect(wrapper.text()).toContain(LOCK_NOTE)
  })

  it('K2: when locked, Voter\'s IP Verification stays changeable (not security policy)', () => {
    const wrapper = mountPage({ locked: true })

    expect(wrapper.get('[data-testid="ip-verification-enabled"]').attributes('disabled')).toBeUndefined()
    expect(wrapper.get('[data-testid="ip-verification-disabled"]').attributes('disabled')).toBeUndefined()
  })

  it('K4: during voting the settings are frozen — Save is disabled, every setting is disabled, and the reason is shown', () => {
    const wrapper = mountPage({ locked: true, frozen: true, restriction: true })

    expect(wrapper.get('button[type="submit"]').attributes('disabled')).toBeDefined()
    expect(wrapper.get('[data-testid="ip-verification-enabled"]').attributes('disabled')).toBeDefined()
    expect(wrapper.get('[data-testid="ip-binding-enabled"]').attributes('disabled')).toBeDefined()
    expect(wrapper.text()).toContain('Election settings cannot be changed while voting is in progress.')
  })

  it('K3: when not locked, the security fields are editable and no lock note is shown', () => {
    const wrapper = mountPage({ locked: false, restriction: true })

    expect(wrapper.get('[data-testid="ip-binding-enabled"]').attributes('disabled')).toBeUndefined()
    expect(wrapper.get('#ip-max-input').attributes('disabled')).toBeUndefined()
    expect(wrapper.text()).not.toContain(LOCK_NOTE)
  })
})
