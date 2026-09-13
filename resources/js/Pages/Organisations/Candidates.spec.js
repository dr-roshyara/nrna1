import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

const reloadMock = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { reload: (...args) => reloadMock(...args) },
  Link: { template: '<a><slot /></a>' },
}))

global.route = vi.fn((name, params) => `/mock/${name}/${JSON.stringify(params ?? {})}`)

import Candidates from './Candidates.vue'

const basePost = {
  id: 'post-1',
  name: 'President',
  nepali_name: null,
  is_national_wide: true,
  state_name: null,
  required_number: 1,
  candidacies: [
    { id: 'candidacy-1', name: 'Jane Candidate', description: null, image_path_1: null, image_path_2: null, image_path_3: null, position_order: 0 },
  ],
}

function mountPage(props = {}) {
  return mount(Candidates, {
    props: {
      organisation: { id: 'org-1', name: 'Test Org', slug: 'test-org' },
      election: { id: 'election-1', name: 'Test Election', slug: 'test-election', status: 'active' },
      posts: [basePost],
      can_edit_candidate_photos: false,
      ...props,
    },
    global: {
      stubs: {
        ElectionLayout: { template: '<div><slot /></div>' },
        PhotoEditModal: { name: 'PhotoEditModal', template: '<div class="photo-edit-modal-stub" />', props: ['photoUrl', 'updateUrl', 'candidateName'], emits: ['close', 'updated'] },
      },
    },
  })
}

describe('Organisations/Candidates.vue — photo edit affordance', () => {
  beforeEach(() => {
    reloadMock.mockReset()
  })

  it('shows the Edit Photo affordance for each candidate when can_edit_candidate_photos is true', () => {
    const wrapper = mountPage({ can_edit_candidate_photos: true })
    expect(wrapper.find('[data-testid="edit-photo-button"]').exists()).toBe(true)
  })

  it('does not show the Edit Photo affordance when can_edit_candidate_photos is false', () => {
    const wrapper = mountPage({ can_edit_candidate_photos: false })
    expect(wrapper.find('[data-testid="edit-photo-button"]').exists()).toBe(false)
  })

  it('opens the photo edit modal for the clicked candidate', async () => {
    const wrapper = mountPage({ can_edit_candidate_photos: true })
    expect(wrapper.findComponent({ name: 'PhotoEditModal' }).exists()).toBe(false)

    await wrapper.get('[data-testid="edit-photo-button"]').trigger('click')

    const modal = wrapper.findComponent({ name: 'PhotoEditModal' })
    expect(modal.exists()).toBe(true)
    expect(modal.props('candidateName')).toBe('Jane Candidate')
  })

  it('reloads only the posts data when the modal emits updated, and closes the modal', async () => {
    const wrapper = mountPage({ can_edit_candidate_photos: true })
    await wrapper.get('[data-testid="edit-photo-button"]').trigger('click')

    await wrapper.findComponent({ name: 'PhotoEditModal' }).vm.$emit('updated')

    expect(reloadMock).toHaveBeenCalledWith(expect.objectContaining({ only: ['posts'] }))
    expect(wrapper.findComponent({ name: 'PhotoEditModal' }).exists()).toBe(false)
  })

  it('closes the modal without reloading when the modal emits close', async () => {
    const wrapper = mountPage({ can_edit_candidate_photos: true })
    await wrapper.get('[data-testid="edit-photo-button"]').trigger('click')

    await wrapper.findComponent({ name: 'PhotoEditModal' }).vm.$emit('close')

    expect(reloadMock).not.toHaveBeenCalled()
    expect(wrapper.findComponent({ name: 'PhotoEditModal' }).exists()).toBe(false)
  })
})
