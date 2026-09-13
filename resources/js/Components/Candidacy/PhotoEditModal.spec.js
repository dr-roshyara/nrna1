import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

const postMock = vi.fn()
vi.mock('@inertiajs/vue3', () => ({
  router: { post: (...args) => postMock(...args) },
}))

// vue-advanced-cropper's real Cropper relies on canvas/ResizeObserver, which
// jsdom doesn't implement. Stub it so these tests verify OUR component's
// contract (props passed in, save/cancel behavior) rather than the library's
// internals. Anything returned from setup() here is exposed on the component's
// public instance, so a template ref in PhotoEditModal can call getResult().
vi.mock('vue-advanced-cropper', () => ({
  Cropper: {
    name: 'CropperStub',
    props: ['src', 'stencilProps'],
    template: '<div class="cropper-stub" />',
    setup() {
      return {
        getResult: () => ({
          canvas: {
            toBlob: (cb) => cb(new Blob(['fake-cropped-bytes'], { type: 'image/jpeg' })),
          },
        }),
      }
    },
  },
}))

import PhotoEditModal from './PhotoEditModal.vue'

describe('PhotoEditModal', () => {
  beforeEach(() => {
    postMock.mockReset()
    global.URL.createObjectURL = vi.fn(() => 'blob:fake-object-url')
    global.URL.revokeObjectURL = vi.fn()
  })

  function mountModal(props = {}) {
    return mount(PhotoEditModal, {
      props: {
        photoUrl: '/storage/candidacies/org-1/existing.jpg',
        updateUrl: '/organisations/org-1/elections/election-1/candidates/candidacy-1/photo',
        candidateName: 'Jane Candidate',
        ...props,
      },
    })
  }

  it('loads the existing photo into the cropper when one is provided', () => {
    const wrapper = mountModal({ photoUrl: '/storage/existing.jpg' })
    const cropper = wrapper.findComponent({ name: 'CropperStub' })
    expect(cropper.exists()).toBe(true)
    expect(cropper.props('src')).toBe('/storage/existing.jpg')
    expect(cropper.props('stencilProps')).toEqual(expect.objectContaining({ aspectRatio: 1 }))
  })

  it('does not render the cropper when there is no existing photo and none has been chosen yet', () => {
    const wrapper = mountModal({ photoUrl: null })
    expect(wrapper.findComponent({ name: 'CropperStub' }).exists()).toBe(false)
  })

  it('loads a newly selected file into the cropper', async () => {
    const wrapper = mountModal({ photoUrl: null })
    const file = new File(['fake-bytes'], 'new-photo.jpg', { type: 'image/jpeg' })
    const input = wrapper.get('input[type="file"]')

    Object.defineProperty(input.element, 'files', { value: [file] })
    await input.trigger('change')

    const cropper = wrapper.findComponent({ name: 'CropperStub' })
    expect(cropper.exists()).toBe(true)
    expect(cropper.props('src')).toBe('blob:fake-object-url')
  })

  it('emits close and makes no request when cancelled', async () => {
    const wrapper = mountModal()
    await wrapper.get('[data-testid="cancel-button"]').trigger('click')

    expect(postMock).not.toHaveBeenCalled()
    expect(wrapper.emitted('close')).toBeTruthy()
  })

  it('submits the cropped image to the update endpoint on save', async () => {
    const wrapper = mountModal({ updateUrl: '/the/update/url' })
    await wrapper.get('[data-testid="save-button"]').trigger('click')
    await vi.waitFor(() => expect(postMock).toHaveBeenCalledTimes(1))

    const [url, formData, options] = postMock.mock.calls[0]
    expect(url).toBe('/the/update/url')
    expect(formData).toBeInstanceOf(FormData)
    expect(formData.get('_method')).toBe('patch')
    expect(formData.get('photo')).toBeInstanceOf(Blob)
    expect(options.forceFormData).toBe(true)
  })

  it('emits updated when the save request succeeds', async () => {
    const wrapper = mountModal()
    await wrapper.get('[data-testid="save-button"]').trigger('click')
    await vi.waitFor(() => expect(postMock).toHaveBeenCalledTimes(1))

    const options = postMock.mock.calls[0][2]
    options.onSuccess()
    await wrapper.vm.$nextTick()

    expect(wrapper.emitted('updated')).toBeTruthy()
  })

  it('shows the backend error message when the save request fails, and does not emit updated', async () => {
    const wrapper = mountModal()
    await wrapper.get('[data-testid="save-button"]').trigger('click')
    await vi.waitFor(() => expect(postMock).toHaveBeenCalledTimes(1))

    const options = postMock.mock.calls[0][2]
    options.onError({ photo: 'The uploaded file must be an image.' })
    await wrapper.vm.$nextTick()

    expect(wrapper.text()).toContain('The uploaded file must be an image.')
    expect(wrapper.emitted('updated')).toBeFalsy()
  })
})
