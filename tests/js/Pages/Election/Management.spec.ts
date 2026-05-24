import { describe, it, expect, vi, beforeEach } from 'vitest'
import { shallowMount } from '@vue/test-utils'
import Management from '@/Pages/Election/Management.vue'

// Stub global route() helper used throughout template and script
vi.stubGlobal('route', vi.fn((name: string, params?: Record<string, any>) => {
  return `/mocked/${name}/${params?.election || params?.organisation || ''}`
}))

// Mock Inertia
const mockRouterPost = vi.fn()
const mockRouterPatch = vi.fn()
const mockRouterVisit = vi.fn()
const mockRouterReload = vi.fn()

vi.mock('@inertiajs/vue3', () => ({
  router: {
    post: (...args: any[]) => mockRouterPost(...args),
    patch: (...args: any[]) => mockRouterPatch(...args),
    visit: (...args: any[]) => mockRouterVisit(...args),
    reload: (...args: any[]) => mockRouterReload(...args),
  },
  usePage: vi.fn(() => ({
    props: {
      flash: {},
    },
  })),
}))

// Mock ElectionLayout to prevent CSS background-image URL resolution failure
vi.mock('@/Layouts/ElectionLayout.vue', () => ({
  default: { template: '<div><slot /></div>' },
}))

// Mock vue-i18n
vi.mock('vue-i18n', () => ({
  useI18n: () => ({
    locale: { value: 'en' },
  }),
}))

const defaultCapabilities = {
  submit_for_approval: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  approve: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  reject: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  auto_submit: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  begin_setup: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  revise_and_resubmit: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  complete_administration: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  complete_nomination: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  open_voting: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  close_voting: { allowed: true, denial_reason: null, denial_detail: null },
  publish_results: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  archive: { allowed: false, denial_reason: 'wrong_state', denial_detail: null },
  suspend: { allowed: true, denial_reason: null, denial_detail: null },
  resume: { allowed: false, denial_reason: 'not_suspended', denial_detail: null },
}

function makeStateMachine(overrides: Record<string, any> = {}): Record<string, any> {
  const mergedCapabilities = {
    ...defaultCapabilities,
    ...(overrides.capabilities || {}),
  }
  return {
    currentState: 'voting_active',
    ...overrides,
    capabilities: mergedCapabilities,
  }
}

function mountManagement(overrides: Record<string, any> = {}) {
  return shallowMount(Management, {
    props: {
      election: { id: 1, name: 'Test Election', slug: 'test-election' },
      stateMachine: makeStateMachine(),
      ...overrides,
    },
    global: {
      mocks: {
        route: vi.fn((name: string, params?: Record<string, any>) => {
          return `/mocked/${name}/${params?.election || params?.organisation || ''}`
        }),
      },
      stubs: {
        ElectionLayout: { template: '<div><slot /></div>' },
        Card: { template: '<div><slot /></div>' },
        StatusBadge: true,
        ActionButton: { template: '<button><slot /></button>' },
        SectionCard: { template: '<div><slot /></div>' },
        EmptyState: { template: '<div><slot /></div>' },
        StateMachinePanel: true,
        StateBadge: true,
        StateProgress: true,
        Button: { template: '<button><slot /></button>' },
        Teleport: { template: '<div><slot /></div>' },
      },
    },
  })
}

describe('Management.vue — Suspend UI', () => {
  beforeEach(() => {
    vi.clearAllMocks()
  })

  it('shows suspend button when canSuspend is true', () => {
    const wrapper = mountManagement()
    const suspendButton = wrapper.find('[data-testid="suspend-button"]')
    expect(suspendButton.exists()).toBe(true)
  })

  it('hides suspend button when canSuspend is false', () => {
    const wrapper = mountManagement({
      stateMachine: makeStateMachine({
        capabilities: {
          suspend: { allowed: false, denial_reason: 'not_authorized', denial_detail: null },
        },
      }),
    })
    const suspendButton = wrapper.find('[data-testid="suspend-button"]')
    expect(suspendButton.exists()).toBe(false)
  })

  it('opens governance modal on suspend button click', async () => {
    const wrapper = mountManagement()
    await wrapper.find('[data-testid="suspend-button"]').trigger('click')
    expect(wrapper.find('[data-testid="suspend-modal"]').exists()).toBe(true)
  })

  it('calls suspend route with reason from modal', async () => {
    const wrapper = mountManagement()

    // Open modal
    await wrapper.find('[data-testid="suspend-button"]').trigger('click')

    // Fill in reason
    const reasonTextarea = wrapper.find('[data-testid="suspend-reason"]')
    await reasonTextarea.setValue('Election integrity concerns require immediate suspension')

    // Select category
    const categorySelect = wrapper.find('[data-testid="suspend-category"]')
    await categorySelect.setValue('investigation')

    // Click confirm
    await wrapper.find('[data-testid="suspend-confirm"]').trigger('click')

    // Assert router.post was called with suspend route and form data
    expect(mockRouterPost).toHaveBeenCalledWith(
      expect.stringContaining('elections.suspend'),
      expect.objectContaining({
        reason: 'Election integrity concerns require immediate suspension',
        suspension_category: 'investigation',
      }),
      expect.objectContaining({ preserveScroll: true }),
    )
  })
})
