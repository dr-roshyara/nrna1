/**
 * TDD Design System Component Tests
 *
 * Tests for Phase 2 core component library:
 * Modal, Input, Dropdown, ProgressBar, VerificationSeal
 *
 * Run: npm test -- tests/Frontend/DesignSystemComponents.spec.js
 *
 * @group frontend
 * @group design-system
 */

import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'

import Modal           from '@/Components/Modal.vue'
import Input           from '@/Components/Input.vue'
import Dropdown        from '@/Components/Dropdown.vue'
import ProgressBar     from '@/Components/ProgressBar.vue'
import VerificationSeal from '@/Components/VerificationSeal.vue'

// ====================================================================
// Modal.vue Tests
// ====================================================================
describe('Modal.vue', () => {
  let wrapper
  const mountModal = (props = {}, slots = {}) => {
    wrapper = mount(Modal, {
      props: { open: true, title: 'Test', ...props },
      slots,
      attachTo: document.body,
    })
    return wrapper
  }

  beforeEach(() => {
    document.body.innerHTML = ''
  })

  afterEach(() => {
    wrapper?.unmount()
    document.body.innerHTML = ''
  })

  // ── Rendering ──────────────────────────────────────────────────────

  it('renders nothing when open is false', () => {
    wrapper = mount(Modal, { props: { open: false, title: 'Test' } })
    expect(document.body.querySelector('[role="dialog"]')).toBeNull()
  })

  it('renders dialog when open is true', () => {
    mountModal()
    const dialog = document.body.querySelector('[role="dialog"]')
    expect(dialog).not.toBeNull()
    expect(dialog.getAttribute('aria-modal')).toBe('true')
    expect(dialog.getAttribute('aria-labelledby')).toBe('modal-title')
  })

  it('renders title text', () => {
    mountModal({ title: 'Confirm Election' })
    expect(document.body.textContent).toContain('Confirm Election')
  })

  it('renders slot content in body', () => {
    mountModal({}, { default: '<p class="modal-body-content">Body content</p>' })
    expect(document.body.querySelector('.modal-body-content')).not.toBeNull()
    expect(document.body.textContent).toContain('Body content')
  })

  it('renders footer slot', () => {
    mountModal({}, { footer: '<button class="modal-footer-btn">Confirm</button>' })
    expect(document.body.querySelector('.modal-footer-btn')).not.toBeNull()
  })

  it('renders title slot overrides title prop', () => {
    mountModal({ title: 'Prop Title' }, { title: '<span class="custom-title">Slot Title</span>' })
    expect(document.body.querySelector('.custom-title')).not.toBeNull()
    expect(document.body.textContent).not.toContain('Prop Title')
  })

  // ── Size variants ──────────────────────────────────────────────────

  it('applies sm size class', () => {
    mountModal({ size: 'sm' })
    expect(document.body.querySelector('.max-w-sm')).not.toBeNull()
  })

  it('applies md size class by default', () => {
    mountModal()
    expect(document.body.querySelector('.max-w-md')).not.toBeNull()
  })

  it('applies lg size class', () => {
    mountModal({ size: 'lg' })
    expect(document.body.querySelector('.max-w-lg')).not.toBeNull()
  })

  it('applies xl size class', () => {
    mountModal({ size: 'xl' })
    expect(document.body.querySelector('.max-w-xl')).not.toBeNull()
  })

  it('applies full size class', () => {
    mountModal({ size: 'full' })
    expect(document.body.querySelector('.max-w-4xl')).not.toBeNull()
  })

  // ── Variants ───────────────────────────────────────────────────────

  it('renders default variant without variant class', () => {
    mountModal({ variant: 'default' })
    expect(document.body.querySelector('.modal-variant--danger')).toBeNull()
  })

  it('renders danger variant with warning icon', () => {
    mountModal({ variant: 'danger' })
    expect(document.body.querySelector('.modal-variant--danger')).not.toBeNull()
    expect(document.body.textContent).toContain('⚠')
  })

  it('renders success variant with checkmark icon', () => {
    mountModal({ variant: 'success' })
    expect(document.body.querySelector('.modal-variant--success')).not.toBeNull()
    expect(document.body.textContent).toContain('✅')
  })

  it('renders warning variant', () => {
    mountModal({ variant: 'warning' })
    expect(document.body.querySelector('.modal-variant--warning')).not.toBeNull()
    expect(document.body.textContent).toContain('⚠')
  })

  // ── Close button ───────────────────────────────────────────────────

  it('shows close button by default', () => {
    mountModal()
    expect(document.body.querySelector('.modal-close')).not.toBeNull()
  })

  it('hides close button when closable is false', () => {
    mountModal({ closable: false })
    expect(document.body.querySelector('.modal-close')).toBeNull()
  })

  it('emits close when close button is clicked', async () => {
    mountModal()
    await document.body.querySelector('.modal-close').click()
    expect(wrapper.emitted('close')).toBeTruthy()
  })

  it('emits close on ESC keydown', async () => {
    mountModal()
    const dialog = document.body.querySelector('.modal-container')
    dialog.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }))
    expect(wrapper.emitted('close')).toBeTruthy()
  })

  it('does not emit close on ESC when closable is false', async () => {
    mountModal({ closable: false })
    const dialog = document.body.querySelector('.modal-container')
    dialog.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }))
    expect(wrapper.emitted('close')).toBeFalsy()
  })

  it('does not emit close on backdrop click when closeOnBackdrop is false', async () => {
    mountModal({ closeOnBackdrop: false })
    document.body.querySelector('.modal-backdrop')?.click()
    expect(wrapper.emitted('close')).toBeFalsy()
  })

  // ── Body scroll lock ───────────────────────────────────────────────

  it('locks body scroll when open', async () => {
    wrapper = mount(Modal, {
      props: { open: false, title: 'Test' },
      attachTo: document.body,
    })
    expect(document.body.style.overflow).not.toBe('hidden')
    await wrapper.setProps({ open: true })
    expect(document.body.style.overflow).toBe('hidden')
  })

  it('unlocks body scroll when closed', async () => {
    wrapper = mount(Modal, {
      props: { open: false, title: 'Test' },
      attachTo: document.body,
    })
    await wrapper.setProps({ open: true })
    expect(document.body.style.overflow).toBe('hidden')
    await wrapper.setProps({ open: false })
    expect(document.body.style.overflow).toBe('')
  })

  // ── Accessibility ──────────────────────────────────────────────────

  it('uses custom aria-labelledby', () => {
    mountModal({ titleId: 'custom-modal-title' })
    const dialog = document.body.querySelector('[role="dialog"]')
    expect(dialog.getAttribute('aria-labelledby')).toBe('custom-modal-title')
  })

  it('uses aria-describedby when provided', () => {
    mountModal({ descriptionId: 'modal-desc' })
    const dialog = document.body.querySelector('[role="dialog"]')
    expect(dialog.getAttribute('aria-describedby')).toBe('modal-desc')
  })

  it('close button has accessible label', () => {
    mountModal({ closeLabel: 'Schließen' })
    expect(document.body.querySelector('.modal-close').getAttribute('aria-label')).toBe('Schließen')
  })

  it('close button has default label', () => {
    mountModal()
    expect(document.body.querySelector('.modal-close').getAttribute('aria-label')).toBe('Close modal')
  })
})

// ====================================================================
// Input.vue Tests
// ====================================================================
describe('Input.vue', () => {
  // ── Rendering ──────────────────────────────────────────────────────

  it('renders a text input by default', () => {
    const wrapper = mount(Input, { props: { modelValue: '' } })
    expect(wrapper.find('input').exists()).toBe(true)
    expect(wrapper.find('input').attributes('type')).toBe('text')
  })

  it('renders label text', () => {
    const wrapper = mount(Input, { props: { modelValue: '', label: 'Email' } })
    expect(wrapper.text()).toContain('Email')
  })

  it('renders placeholder text', () => {
    const wrapper = mount(Input, { props: { modelValue: '', placeholder: 'Enter email' } })
    expect(wrapper.find('input').attributes('placeholder')).toBe('Enter email')
  })

  it('shows required marker when required', () => {
    const wrapper = mount(Input, { props: { modelValue: '', label: 'Name', required: true } })
    expect(wrapper.find('.input-required-mark').exists()).toBe(true)
  })

  // ── Types ──────────────────────────────────────────────────────────

  it('renders email type input', () => {
    const wrapper = mount(Input, { props: { modelValue: '', type: 'email' } })
    expect(wrapper.find('input').attributes('type')).toBe('email')
  })

  it('renders number type input', () => {
    const wrapper = mount(Input, { props: { modelValue: '', type: 'number' } })
    expect(wrapper.find('input').attributes('type')).toBe('number')
  })

  it('renders password type input', () => {
    const wrapper = mount(Input, { props: { modelValue: '', type: 'password' } })
    expect(wrapper.find('input').attributes('type')).toBe('password')
  })

  it('renders textarea when type is textarea', () => {
    const wrapper = mount(Input, { props: { modelValue: '', type: 'textarea' } })
    expect(wrapper.find('textarea').exists()).toBe(true)
    expect(wrapper.find('input').exists()).toBe(false)
  })

  it('renders select when type is select', () => {
    const options = [{ value: 'a', label: 'Option A' }, { value: 'b', label: 'Option B' }]
    const wrapper = mount(Input, { props: { modelValue: '', type: 'select', options } })
    expect(wrapper.find('select').exists()).toBe(true)
    expect(wrapper.find('input').exists()).toBe(false)
    expect(wrapper.findAll('option')).toHaveLength(2)
  })

  it('renders select options as strings when no value/label keys', () => {
    const options = ['Apple', 'Banana', 'Cherry']
    const wrapper = mount(Input, { props: { modelValue: '', type: 'select', options } })
    expect(wrapper.findAll('option')).toHaveLength(3)
  })

  // ── v-model ────────────────────────────────────────────────────────

  it('emits update:modelValue on input', async () => {
    const wrapper = mount(Input, { props: { modelValue: '' } })
    await wrapper.find('input').setValue('test@example.com')
    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')[0][0]).toBe('test@example.com')
  })

  it('emits update:modelValue on textarea input', async () => {
    const wrapper = mount(Input, { props: { modelValue: '', type: 'textarea' } })
    await wrapper.find('textarea').setValue('Long text')
    expect(wrapper.emitted('update:modelValue')[0][0]).toBe('Long text')
  })

  it('emits update:modelValue on select change', async () => {
    const options = ['Option A', 'Option B']
    const wrapper = mount(Input, { props: { modelValue: '', type: 'select', options } })
    await wrapper.find('select').setValue('Option B')
    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
  })

  // ── Error & Hint ───────────────────────────────────────────────────

  it('shows hint text when no error', () => {
    const wrapper = mount(Input, { props: { modelValue: '', hint: 'Helper text' } })
    expect(wrapper.text()).toContain('Helper text')
  })

  it('shows error text and hides hint', () => {
    const wrapper = mount(Input, { props: { modelValue: '', hint: 'Helper', error: 'Error message' } })
    expect(wrapper.text()).not.toContain('Helper')
    expect(wrapper.text()).toContain('Error message')
  })

  it('marks input as aria-invalid when error exists', () => {
    const wrapper = mount(Input, { props: { modelValue: '', error: 'Required' } })
    expect(wrapper.find('input').attributes('aria-invalid')).toBe('true')
  })

  it('does not set aria-invalid when no error', () => {
    const wrapper = mount(Input, { props: { modelValue: '' } })
    expect(wrapper.find('input').attributes('aria-invalid')).toBeUndefined()
  })

  it('wires aria-describedby for hint', () => {
    const wrapper = mount(Input, { props: { modelValue: '', hint: 'Helper' } })
    const input = wrapper.find('input')
    const describedby = input.attributes('aria-describedby')
    expect(describedby).toBeTruthy()
    expect(describedby).toContain('hint-')
  })

  // ── Disabled ───────────────────────────────────────────────────────

  it('disables input when disabled prop is true', () => {
    const wrapper = mount(Input, { props: { modelValue: '', disabled: true } })
    expect(wrapper.find('input').attributes('disabled')).toBeDefined()
  })

  it('applies disabled class to wrapper', () => {
    const wrapper = mount(Input, { props: { modelValue: '', disabled: true } })
    expect(wrapper.find('.input-wrapper--disabled').exists()).toBe(true)
  })

  // ── Size ───────────────────────────────────────────────────────────

  it('defaults to md size', () => {
    const wrapper = mount(Input, { props: { modelValue: '' } })
    expect(wrapper.find('.input-container--md').exists()).toBe(true)
  })

  it('applies sm size class', () => {
    const wrapper = mount(Input, { props: { modelValue: '', size: 'sm' } })
    expect(wrapper.find('.input-container--sm').exists()).toBe(true)
  })

  it('applies lg size class', () => {
    const wrapper = mount(Input, { props: { modelValue: '', size: 'lg' } })
    expect(wrapper.find('.input-container--lg').exists()).toBe(true)
  })

  // ── Events ─────────────────────────────────────────────────────────

  it('emits blur event', async () => {
    const wrapper = mount(Input, { props: { modelValue: '' } })
    await wrapper.find('input').trigger('blur')
    expect(wrapper.emitted('blur')).toBeTruthy()
  })

  it('emits focus event', async () => {
    const wrapper = mount(Input, { props: { modelValue: '' } })
    await wrapper.find('input').trigger('focus')
    expect(wrapper.emitted('focus')).toBeTruthy()
  })
})

// ====================================================================
// Dropdown.vue Tests
// ====================================================================
describe('Dropdown.vue', () => {
  const items = [
    { value: 'opt1', label: 'Option 1' },
    { value: 'opt2', label: 'Option 2' },
    { value: 'opt3', label: 'Option 3' },
  ]

  // ── Rendering ──────────────────────────────────────────────────────

  it('renders with placeholder when no value selected', () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items, placeholder: 'Select…' } })
    expect(wrapper.text()).toContain('Select…')
  })

  it('renders selected item label', () => {
    const wrapper = mount(Dropdown, { props: { modelValue: 'opt2', items } })
    expect(wrapper.text()).toContain('Option 2')
  })

  it('shows label when provided', () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items, label: 'Election' } })
    expect(wrapper.text()).toContain('Election')
  })

  // ── Toggle ─────────────────────────────────────────────────────────

  it('shows list when trigger is clicked', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    expect(wrapper.find('[role="listbox"]').exists()).toBe(false)
    await wrapper.find('button').trigger('click')
    expect(wrapper.find('[role="listbox"]').exists()).toBe(true)
  })

  it('hides list when trigger is clicked twice', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('click')
    expect(wrapper.find('[role="listbox"]').exists()).toBe(true)
    await wrapper.find('button').trigger('click')
    expect(wrapper.find('[role="listbox"]').exists()).toBe(false)
  })

  it('renders all items in the list', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('click')
    const options = wrapper.findAll('[role="option"]')
    expect(options).toHaveLength(3)
  })

  // ── Selection ──────────────────────────────────────────────────────

  it('emits update:modelValue when item is clicked', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('click')
    await wrapper.findAll('[role="option"]')[1].trigger('click')
    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')[0][0]).toBe('opt2')
  })

  it('closes list after selection', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('click')
    await wrapper.findAll('[role="option"]')[0].trigger('click')
    expect(wrapper.find('[role="listbox"]').exists()).toBe(false)
  })

  it('marks selected option with aria-selected', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: 'opt2', items } })
    await wrapper.find('button').trigger('click')
    const options = wrapper.findAll('[role="option"]')
    expect(options[1].attributes('aria-selected')).toBe('true')
    expect(options[0].attributes('aria-selected')).toBe('false')
  })

  // ── Disabled ───────────────────────────────────────────────────────

  it('does not open when disabled', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items, disabled: true } })
    await wrapper.find('button').trigger('click')
    expect(wrapper.find('[role="listbox"]').exists()).toBe(false)
  })

  it('shows disabled option as non-interactive', async () => {
    const itemsWithDisabled = [
      { value: 'a', label: 'Active' },
      { value: 'b', label: 'Disabled', disabled: true },
    ]
    const wrapper = mount(Dropdown, { props: { modelValue: null, items: itemsWithDisabled } })
    await wrapper.find('button').trigger('click')
    const options = wrapper.findAll('[role="option"]')
    expect(options[1].attributes('aria-disabled')).toBe('true')
  })

  // ── Search ─────────────────────────────────────────────────────────

  it('shows search input when searchable is true', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items, searchable: true } })
    await wrapper.find('button').trigger('click')
    expect(wrapper.find('.dropdown-search-input').exists()).toBe(true)
  })

  it('filters items by search query', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items, searchable: true } })
    await wrapper.find('button').trigger('click')
    const searchInput = wrapper.find('.dropdown-search-input')
    await searchInput.setValue('Option 2')
    const options = wrapper.findAll('[role="option"]')
    expect(options).toHaveLength(1)
    expect(options[0].text()).toContain('Option 2')
  })

  // ── Keyboard ───────────────────────────────────────────────────────

  it('opens on ArrowDown key', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('keydown', { key: 'ArrowDown' })
    expect(wrapper.find('[role="listbox"]').exists()).toBe(true)
  })

  it('closes on Escape key', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('click')
    await wrapper.find('[role="listbox"]').trigger('keydown', { key: 'Escape' })
    expect(wrapper.find('[role="listbox"]').exists()).toBe(false)
  })

  // ── Clearable ──────────────────────────────────────────────────────

  it('shows clear option when clearable is true', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: 'opt1', items, clearable: true } })
    await wrapper.find('button').trigger('click')
    expect(wrapper.text()).toContain('Clear selection')
  })

  it('emits null when clear is clicked', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: 'opt1', items, clearable: true } })
    await wrapper.find('button').trigger('click')
    const clearOption = wrapper.findAll('[role="option"]')[0]
    expect(clearOption.text()).toContain('Clear')
    await clearOption.trigger('click')
    expect(wrapper.emitted('update:modelValue')[0][0]).toBeNull()
  })

  // ── Accessibility ──────────────────────────────────────────────────

  it('trigger has aria-haspopup listbox', () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    expect(wrapper.find('button').attributes('aria-haspopup')).toBe('listbox')
  })

  it('trigger has aria-expanded when open', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items } })
    await wrapper.find('button').trigger('click')
    expect(wrapper.find('button').attributes('aria-expanded')).toBe('true')
  })

  it('listbox has aria-labelledby when label exists', async () => {
    const wrapper = mount(Dropdown, { props: { modelValue: null, items, label: 'Role' } })
    await wrapper.find('button').trigger('click')
    const listbox = wrapper.find('[role="listbox"]')
    expect(listbox.attributes('aria-labelledby')).toBeTruthy()
    expect(listbox.attributes('aria-labelledby')).toContain('dropdown-label-')
  })
})

// ====================================================================
// ProgressBar.vue Tests
// ====================================================================
describe('ProgressBar.vue', () => {
  // ── Rendering ──────────────────────────────────────────────────────

  it('renders progressbar role', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50 } })
    expect(wrapper.find('[role="progressbar"]').exists()).toBe(true)
  })

  it('shows percentage label by default', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50 } })
    expect(wrapper.text()).toContain('50%')
  })

  it('shows custom label if provided', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, label: 'Uploading…' } })
    expect(wrapper.text()).toContain('Uploading…')
  })

  // ── Accessibility ──────────────────────────────────────────────────

  it('sets aria-valuenow', () => {
    const wrapper = mount(ProgressBar, { props: { value: 75 } })
    expect(wrapper.find('[role="progressbar"]').attributes('aria-valuenow')).toBe('75')
  })

  it('sets aria-valuemin to 0', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50 } })
    expect(wrapper.find('[role="progressbar"]').attributes('aria-valuemin')).toBe('0')
  })

  it('sets aria-valuemax to 100 by default', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50 } })
    expect(wrapper.find('[role="progressbar"]').attributes('aria-valuemax')).toBe('100')
  })

  it('sets aria-valuemax to custom max', () => {
    const wrapper = mount(ProgressBar, { props: { value: 5, max: 10 } })
    expect(wrapper.find('[role="progressbar"]').attributes('aria-valuemax')).toBe('10')
  })

  it('sets aria-label when label provided', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, label: 'Voting progress' } })
    expect(wrapper.find('[role="progressbar"]').attributes('aria-label')).toBe('Voting progress')
  })

  // ── Value calculation ──────────────────────────────────────────────

  it('shows 0% for value 0', () => {
    const wrapper = mount(ProgressBar, { props: { value: 0 } })
    expect(wrapper.text()).toContain('0%')
  })

  it('shows 100% for value equal to max', () => {
    const wrapper = mount(ProgressBar, { props: { value: 100 } })
    expect(wrapper.text()).toContain('100%')
  })

  it('clamps at 100% for over-max value', () => {
    const wrapper = mount(ProgressBar, { props: { value: 150 } })
    expect(wrapper.text()).toContain('100%')
  })

  // ── Variants ───────────────────────────────────────────────────────

  it('renders primary variant by default', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50 } })
    expect(wrapper.find('.progress-fill--primary').exists()).toBe(true)
  })

  it('renders success variant', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, variant: 'success' } })
    expect(wrapper.find('.progress-fill--success').exists()).toBe(true)
  })

  it('renders warning variant', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, variant: 'warning' } })
    expect(wrapper.find('.progress-fill--warning').exists()).toBe(true)
  })

  it('renders danger variant', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, variant: 'danger' } })
    expect(wrapper.find('.progress-fill--danger').exists()).toBe(true)
  })

  it('renders accent variant', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, variant: 'accent' } })
    expect(wrapper.find('.progress-fill--accent').exists()).toBe(true)
  })

  // ── Sizes ──────────────────────────────────────────────────────────

  it('renders md size by default', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50 } })
    expect(wrapper.find('.progress-track--md').exists()).toBe(true)
  })

  it('renders sm size', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, size: 'sm' } })
    expect(wrapper.find('.progress-track--sm').exists()).toBe(true)
  })

  it('renders lg size', () => {
    const wrapper = mount(ProgressBar, { props: { value: 50, size: 'lg' } })
    expect(wrapper.find('.progress-track--lg').exists()).toBe(true)
  })

  // ── Indeterminate ──────────────────────────────────────────────────

  it('shows ellipsis when indeterminate', () => {
    const wrapper = mount(ProgressBar, { props: { value: 0, indeterminate: true } })
    expect(wrapper.text()).toContain('…')
  })

  it('does not set aria-valuenow when indeterminate', () => {
    const wrapper = mount(ProgressBar, { props: { value: 0, indeterminate: true } })
    expect(wrapper.find('[role="progressbar"]').attributes('aria-valuenow')).toBeUndefined()
  })

  // ── Format ─────────────────────────────────────────────────────────

  it('uses custom formatter function', () => {
    const customFormat = (pct) => `${pct} Prozent`
    const wrapper = mount(ProgressBar, { props: { value: 67, format: customFormat } })
    expect(wrapper.text()).toContain('67 Prozent')
  })

  it('shows count when showCount is true', () => {
    const wrapper = mount(ProgressBar, { props: { value: 30, max: 100, showCount: true } })
    expect(wrapper.text()).toContain('30')
    expect(wrapper.text()).toContain('100')
  })
})

// ====================================================================
// VerificationSeal.vue Tests
// ====================================================================
describe('VerificationSeal.vue', () => {
  // ── Rendering ──────────────────────────────────────────────────────

  it('renders with idle status by default', () => {
    const wrapper = mount(VerificationSeal)
    expect(wrapper.find('.seal-root--idle').exists()).toBe(true)
    expect(wrapper.find('svg').exists()).toBe(true)
  })

  it('renders verified status', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'verified' } })
    expect(wrapper.find('.seal-root--verified').exists()).toBe(true)
  })

  it('renders pending status', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'pending' } })
    expect(wrapper.find('.seal-root--pending').exists()).toBe(true)
  })

  it('renders warning status', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'warning' } })
    expect(wrapper.find('.seal-root--warning').exists()).toBe(true)
  })

  it('renders a shield SVG path', () => {
    const wrapper = mount(VerificationSeal)
    expect(wrapper.find('svg path').exists()).toBe(true)
  })

  // ── Label ──────────────────────────────────────────────────────────

  it('does not show label by default', () => {
    const wrapper = mount(VerificationSeal)
    expect(wrapper.find('.seal-label').exists()).toBe(false)
  })

  it('shows label text when provided', () => {
    const wrapper = mount(VerificationSeal, { props: { label: 'Verified' } })
    expect(wrapper.find('.seal-label').exists()).toBe(true)
    expect(wrapper.text()).toContain('Verified')
  })

  // ── Sizes ──────────────────────────────────────────────────────────

  it('renders md size by default', () => {
    const wrapper = mount(VerificationSeal)
    expect(wrapper.find('.seal-root--md').exists()).toBe(true)
  })

  it('renders sm size', () => {
    const wrapper = mount(VerificationSeal, { props: { size: 'sm' } })
    expect(wrapper.find('.seal-root--sm').exists()).toBe(true)
  })

  it('renders lg size', () => {
    const wrapper = mount(VerificationSeal, { props: { size: 'lg' } })
    expect(wrapper.find('.seal-root--lg').exists()).toBe(true)
  })

  // ── Checkmark ──────────────────────────────────────────────────────

  it('renders checkmark path when verified', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'verified' } })
    expect(wrapper.find('.seal-checkmark').exists()).toBe(true)
  })

  it('does not render checkmark when not verified', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'warning' } })
    expect(wrapper.find('.seal-checkmark').exists()).toBe(false)
  })

  // ── Accessibility ──────────────────────────────────────────────────

  it('has role status', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'verified' } })
    expect(wrapper.attributes('role')).toBe('status')
  })

  it('has default aria-label based on status', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'verified' } })
    expect(wrapper.attributes('aria-label')).toBe('Status: verified')
  })

  it('overrides aria-label when label provided', () => {
    const wrapper = mount(VerificationSeal, { props: { status: 'verified', label: 'Stimme abgegeben' } })
    expect(wrapper.attributes('aria-label')).toBe('Stimme abgegeben')
  })
})

// ====================================================================
// Barrel Export Tests
// ====================================================================
describe('index.js barrel export', () => {
  it('exports all 10 design system components', async () => {
    const exports = await import('@/Components/index.js')
    expect(exports.Button).toBeDefined()
    expect(exports.Card).toBeDefined()
    expect(exports.Modal).toBeDefined()
    expect(exports.Input).toBeDefined()
    expect(exports.Dropdown).toBeDefined()
    expect(exports.ProgressBar).toBeDefined()
    expect(exports.StatusBadge).toBeDefined()
    expect(exports.VerificationSeal).toBeDefined()
    expect(exports.EmptyState).toBeDefined()
    expect(exports.ToggleSwitch).toBeDefined()
    expect(Object.keys(exports)).toHaveLength(10)
  })
})
