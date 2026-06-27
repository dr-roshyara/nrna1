<template>
  <div class="dropdown-wrapper" :class="wrapperClass">
    <!-- Label -->
    <label
      v-if="label"
      :id="labelId"
      class="dropdown-label"
      :class="{ 'dropdown-label--required': required }"
    >
      {{ label }}
      <span v-if="required" class="dropdown-required-mark" aria-hidden="true">*</span>
    </label>

    <!-- Trigger Button -->
    <button
      ref="triggerRef"
      type="button"
      class="dropdown-trigger"
      :class="{ 'dropdown-trigger--open': open, 'dropdown-trigger--error': error }"
      :aria-haspopup="searchable ? 'combobox' : 'listbox'"
      :aria-expanded="open"
      :aria-controls="listboxId"
      :aria-label="triggerLabel"
      :disabled="disabled"
      @click="toggle"
      @keydown="handleTriggerKeydown"
    >
      <span v-if="selectedLabel" class="dropdown-trigger-text">{{ selectedLabel }}</span>
      <span v-else class="dropdown-trigger-placeholder">{{ placeholder || 'Select…' }}</span>

      <svg
        class="dropdown-chevron"
        :class="{ 'dropdown-chevron--open': open }"
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        aria-hidden="true"
      >
        <polyline points="6 9 12 15 18 9" />
      </svg>
    </button>

    <!-- Search Input (when searchable) -->
    <div
      v-if="open && searchable"
      class="dropdown-search"
      @click.stop
    >
      <svg class="dropdown-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="11" cy="11" r="8" /><path d="M21 21l-4.35-4.35" />
      </svg>
      <input
        ref="searchRef"
        v-model="searchQuery"
        type="text"
        class="dropdown-search-input"
        :placeholder="searchPlaceholder || 'Search…'"
        :aria-controls="listboxId"
        @keydown="handleSearchKeydown"
      />
    </div>

    <!-- Dropdown List -->
    <Transition name="dropdown-fade">
      <div
        v-if="open"
        :id="listboxId"
        ref="listRef"
        class="dropdown-list"
        role="listbox"
        :aria-labelledby="label ? labelId : undefined"
        :aria-activedescendant="activeDescendant"
        @keydown="handleListKeydown"
      >
        <!-- Clear selection option -->
        <div
          v-if="clearable"
          class="dropdown-option dropdown-option--clear"
          role="option"
          :aria-selected="modelValue === null || modelValue === ''"
          @click="select(null)"
        >
          <span class="dropdown-option-text">{{ clearLabel || 'Clear selection' }}</span>
        </div>

        <!-- No results -->
        <div
          v-if="filteredItems.length === 0"
          class="dropdown-option dropdown-option--empty"
          role="option"
          aria-disabled="true"
        >
          {{ emptyLabel || 'No options found' }}
        </div>

        <!-- Options -->
        <div
          v-for="(item, idx) in filteredItems"
          :key="item.value ?? item"
          :ref="el => { if (el && (item.value ?? item) === modelValue) activeOptionRef = el }"
          class="dropdown-option"
          :class="{
            'dropdown-option--selected': (item.value ?? item) === modelValue,
            'dropdown-option--focused': focusedIndex === idx,
            'dropdown-option--disabled': item.disabled
          }"
          role="option"
          :aria-selected="(item.value ?? item) === modelValue"
          :aria-disabled="item.disabled || undefined"
          :data-index="idx"
          @click="selectOption(item)"
          @mouseenter="focusedIndex = idx"
        >
          <!-- Leading slot per item -->
          <slot name="item-leading" :item="item" />

          <span class="dropdown-option-text">{{ item.label ?? item }}</span>

          <!-- Checkmark for selected -->
          <svg
            v-if="(item.value ?? item) === modelValue"
            class="dropdown-check"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            aria-hidden="true"
          >
            <polyline points="20 6 9 17 4 12" />
          </svg>

          <!-- Trailing slot per item -->
          <slot name="item-trailing" :item="item" />
        </div>
      </div>
    </Transition>

    <!-- Error -->
    <p v-if="error" class="dropdown-error" role="alert">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, useId, nextTick } from 'vue'

const props = defineProps({
  modelValue:       { type: [String, Number, null], default: null },
  items:            { type: Array,  default: () => [] },
  label:            { type: String, default: '' },
  placeholder:      { type: String, default: '' },
  error:            { type: String, default: '' },
  disabled:         { type: Boolean, default: false },
  required:         { type: Boolean, default: false },
  searchable:       { type: Boolean, default: false },
  clearable:        { type: Boolean, default: false },
  clearLabel:       { type: String, default: '' },
  searchPlaceholder:{ type: String, default: '' },
  emptyLabel:       { type: String, default: '' },
  size:             { type: String, default: 'md' },   // sm | md | lg
})

const emit = defineEmits(['update:modelValue', 'change'])
const uid = useId()
const listboxId = `dropdown-${uid}`
const labelId = `dropdown-label-${uid}`

const open = ref(false)
const focusedIndex = ref(-1)
const searchQuery = ref('')
const triggerRef = ref(null)
const listRef = ref(null)
const searchRef = ref(null)
const activeOptionRef = ref(null)

// ── Computed ────────────────────────────────
const filteredItems = computed(() => {
  if (!searchQuery.value) return props.items
  const q = searchQuery.value.toLowerCase()
  return props.items.filter(item => {
    const label = (item.label ?? String(item)).toLowerCase()
    return label.includes(q)
  })
})

const selectedItem = computed(() =>
  props.items.find(item => (item.value ?? item) === props.modelValue) ?? null
)

const selectedLabel = computed(() => {
  if (!selectedItem.value) return ''
  return selectedItem.value.label ?? String(selectedItem.value)
})

const triggerLabel = computed(() => {
  return selectedLabel.value
    ? `${props.label}: ${selectedLabel.value}`
    : `Select ${props.label || 'option'}`
})

const activeDescendant = computed(() => {
  if (focusedIndex.value >= 0) {
    return `${listboxId}-option-${focusedIndex.value}`
  }
  return undefined
})

const wrapperClass = computed(() => [
  props.disabled ? 'dropdown-wrapper--disabled' : '',
  props.error    ? 'dropdown-wrapper--error' : '',
])

// ── Toggle ──────────────────────────────────
function toggle() {
  if (props.disabled) return
  open.value = !open.value
  if (open.value) {
    focusedIndex.value = -1
    nextTick(() => {
      if (props.searchable) {
        searchRef.value?.focus()
      } else {
        listRef.value?.focus()
      }
    })
  }
}

function openDropdown() {
  if (!props.disabled && !open.value) {
    open.value = true
    focusedIndex.value = -1
    nextTick(() => {
      if (props.searchable) searchRef.value?.focus()
      else listRef.value?.focus()
    })
  }
}

function closeDropdown() {
  open.value = false
  searchQuery.value = ''
  triggerRef.value?.focus()
}

// ── Selection ───────────────────────────────
function selectOption(item) {
  if (item.disabled) return
  select(item.value ?? item)
}

function select(val) {
  emit('update:modelValue', val)
  emit('change', val)
  closeDropdown()
}

// ── Keyboard ────────────────────────────────
function handleTriggerKeydown(e) {
  if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
    e.preventDefault()
    openDropdown()
  }
}

function handleSearchKeydown(e) {
  if (e.key === 'ArrowDown') {
    e.preventDefault()
    focusedIndex.value = 0
    listRef.value?.focus()
  }
  if (e.key === 'Escape') {
    closeDropdown()
  }
}

function handleListKeydown(e) {
  const max = filteredItems.value.length - 1

  if (e.key === 'ArrowDown') {
    e.preventDefault()
    focusedIndex.value = Math.min(focusedIndex.value + 1, max)
    scrollToFocused()
  }

  if (e.key === 'ArrowUp') {
    e.preventDefault()
    focusedIndex.value = Math.max(focusedIndex.value - 1, 0)
    scrollToFocused()
  }

  if (e.key === 'Enter' || e.key === ' ') {
    e.preventDefault()
    const item = filteredItems.value[focusedIndex.value]
    if (item) selectOption(item)
  }

  if (e.key === 'Escape') {
    closeDropdown()
  }
}

function scrollToFocused() {
  nextTick(() => {
    const el = listRef.value?.querySelector(`[data-index="${focusedIndex.value}"]`)
    el?.scrollIntoView({ block: 'nearest' })
  })
}

// ── Click outside ───────────────────────────
function handleClickOutside(e) {
  if (!open.value) return
  const target = e.target
  if (!triggerRef.value?.contains(target) && !listRef.value?.contains(target)) {
    closeDropdown()
  }
}

onMounted(() => document.addEventListener('mousedown', handleClickOutside))
onUnmounted(() => document.removeEventListener('mousedown', handleClickOutside))

// ── Reset search when menu closes ───────────
watch(open, (val) => { if (!val) searchQuery.value = '' })
</script>

<style scoped>
.dropdown-wrapper {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: var(--space-1, 0.25rem);
}

.dropdown-wrapper--disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* ── Label ─────────────────────────────────── */
.dropdown-label {
  font-size: var(--font-size-sm, 0.875rem);
  font-weight: 500;
  color: var(--color-neutral-800, #27272a);
}

.dropdown-required-mark {
  color: var(--color-danger, #dc2626);
  margin-left: 1px;
}

/* ── Trigger ───────────────────────────────── */
.dropdown-trigger {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-2, 0.5rem);
  width: 100%;
  padding: var(--space-2-5, 0.625rem) var(--space-4, 1rem);
  background: white;
  border: 1px solid var(--color-neutral-300, #d4d4d8);
  border-radius: var(--radius-md, 0.5rem);
  font-family: var(--font-sans, 'DM Sans', sans-serif);
  font-size: var(--font-size-base, 1rem);
  color: var(--color-neutral-900, #18181b);
  cursor: pointer;
  text-align: left;
  transition: all var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
}

.dropdown-trigger:hover {
  border-color: var(--color-neutral-400, #a1a1aa);
}

.dropdown-trigger:focus-visible {
  outline: 2px solid var(--color-primary, #1a2a4a);
  outline-offset: 2px;
}

.dropdown-trigger--open {
  border-color: var(--color-primary, #1a2a4a);
  box-shadow: 0 0 0 3px rgba(26, 42, 74, 0.12);
}

.dropdown-trigger--error {
  border-color: var(--color-danger, #dc2626);
}

.dropdown-trigger-text {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.dropdown-trigger-placeholder {
  color: var(--color-neutral-400, #a1a1aa);
}

.dropdown-chevron {
  flex-shrink: 0;
  transition: transform var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
  color: var(--color-neutral-400, #a1a1aa);
}

.dropdown-chevron--open {
  transform: rotate(180deg);
}

/* ── Search ────────────────────────────────── */
.dropdown-search {
  position: relative;
  padding: var(--space-2, 0.5rem);
  border-bottom: 1px solid var(--color-neutral-200, #e4e4e7);
}

.dropdown-search-icon {
  position: absolute;
  left: var(--space-4, 1rem);
  top: 50%;
  transform: translateY(-50%);
  color: var(--color-neutral-400, #a1a1aa);
  pointer-events: none;
}

.dropdown-search-input {
  width: 100%;
  padding: var(--space-1-5, 0.375rem) var(--space-2, 0.5rem) var(--space-1-5, 0.375rem) var(--space-7, 1.75rem);
  border: 1px solid var(--color-neutral-300, #d4d4d8);
  border-radius: var(--radius-sm, 0.375rem);
  font-size: var(--font-size-sm, 0.875rem);
  font-family: var(--font-sans, 'DM Sans', sans-serif);
  outline: none;
}

.dropdown-search-input:focus {
  border-color: var(--color-primary, #1a2a4a);
  box-shadow: 0 0 0 2px rgba(26, 42, 74, 0.12);
}

/* ── List ──────────────────────────────────── */
.dropdown-list {
  position: absolute;
  top: calc(100% + var(--space-1, 0.25rem));
  left: 0;
  right: 0;
  z-index: 30;
  background: white;
  border: 1px solid var(--color-neutral-200, #e4e4e7);
  border-radius: var(--radius-md, 0.5rem);
  box-shadow: var(--shadow-lg, 0 10px 15px -3px rgba(0,0,0,0.1));
  max-height: 260px;
  overflow-y: auto;
}

/* ── Option ────────────────────────────────── */
.dropdown-option {
  display: flex;
  align-items: center;
  gap: var(--space-2, 0.5rem);
  padding: var(--space-2-5, 0.625rem) var(--space-4, 1rem);
  cursor: pointer;
  font-size: var(--font-size-sm, 0.875rem);
  color: var(--color-neutral-800, #27272a);
  transition: background var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
}

.dropdown-option:hover,
.dropdown-option--focused {
  background: var(--color-neutral-100, #f4f4f5);
}

.dropdown-option--selected {
  background: var(--color-primary-light, #e8ecf4);
  font-weight: 500;
}

.dropdown-option--disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.dropdown-option--empty,
.dropdown-option--clear {
  font-style: italic;
  color: var(--color-neutral-400, #a1a1aa);
}

.dropdown-option-text {
  flex: 1;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.dropdown-check {
  flex-shrink: 0;
  color: var(--color-primary, #1a2a4a);
}

/* ── Error ─────────────────────────────────── */
.dropdown-error {
  font-size: var(--font-size-xs, 0.75rem);
  color: var(--color-danger, #dc2626);
  margin: 0;
}

/* ── Transition ────────────────────────────── */
.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: all var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
}

.dropdown-fade-enter-from,
.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

@media (prefers-reduced-motion: reduce) {
  .dropdown-fade-enter-active,
  .dropdown-fade-leave-active {
    transition: none;
  }
}
</style>
