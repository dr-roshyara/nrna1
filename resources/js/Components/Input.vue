<template>
  <div class="input-wrapper" :class="wrapperClass">
    <!-- Label -->
    <label
      v-if="label"
      :for="inputId"
      class="input-label"
      :class="{ 'input-label--required': required }"
    >
      {{ label }}
      <span v-if="required" class="input-required-mark" aria-hidden="true">*</span>
    </label>

    <!-- Input / Select / Textarea -->
    <div class="input-container" :class="containerClass">
      <!-- Leading icon slot -->
      <span v-if="$slots.leading || leadingIcon" class="input-icon input-icon--leading" aria-hidden="true">
        <slot name="leading">
          <span v-html="leadingIcon" />
        </slot>
      </span>

      <input
        v-if="type !== 'textarea' && type !== 'select'"
        :id="inputId"
        :ref="el => inputEl = el"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :required="required"
        :maxlength="maxlength"
        :min="min"
        :max="max"
        :step="step"
        :autocomplete="autocomplete"
        :aria-invalid="!!error || undefined"
        :aria-describedby="describedBy"
        class="input-field"
        @input="onInput"
        @blur="emit('blur', $event)"
        @focus="emit('focus', $event)"
      />

      <textarea
        v-if="type === 'textarea'"
        :id="inputId"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :readonly="readonly"
        :required="required"
        :rows="rows"
        :maxlength="maxlength"
        :aria-invalid="!!error || undefined"
        :aria-describedby="describedBy"
        class="input-field input-field--textarea"
        @input="onInput"
        @blur="emit('blur', $event)"
        @focus="emit('focus', $event)"
      />

      <select
        v-if="type === 'select'"
        :id="inputId"
        :value="modelValue"
        :disabled="disabled"
        :required="required"
        :aria-invalid="!!error || undefined"
        :aria-describedby="describedBy"
        class="input-field input-field--select"
        @change="onSelectChange"
        @blur="emit('blur', $event)"
        @focus="emit('focus', $event)"
      >
        <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
        <option
          v-for="opt in options"
          :key="opt.value ?? opt"
          :value="opt.value ?? opt"
          :disabled="opt.disabled"
        >
          {{ opt.label ?? opt }}
        </option>
      </select>

      <!-- Trailing icon slot -->
      <span v-if="$slots.trailing || trailingIcon" class="input-icon input-icon--trailing" aria-hidden="true">
        <slot name="trailing">
          <span v-html="trailingIcon" />
        </slot>
      </span>
    </div>

    <!-- Hint -->
    <p v-if="hint && !error" :id="hintId" class="input-hint">{{ hint }}</p>

    <!-- Error -->
    <p v-if="error" :id="errorId" class="input-error" role="alert">
      <svg class="input-error-icon" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
      </svg>
      {{ error }}
    </p>

    <!-- Character count -->
    <p v-if="showCount && maxlength" class="input-count">
      {{ String(modelValue ?? '').length }} / {{ maxlength }}
    </p>
  </div>
</template>

<script setup>
import { computed, ref, useId } from 'vue'

const props = defineProps({
  modelValue:   { type: [String, Number], default: '' },
  type:         { type: String,  default: 'text' },  // text | email | number | password | tel | url | textarea | select
  label:        { type: String,  default: '' },
  placeholder:  { type: String,  default: '' },
  hint:         { type: String,  default: '' },
  error:        { type: String,  default: '' },
  disabled:     { type: Boolean, default: false },
  readonly:     { type: Boolean, default: false },
  required:     { type: Boolean, default: false },
  size:         { type: String,  default: 'md' },     // sm | md | lg
  maxlength:    { type: [Number, String], default: null },
  rows:         { type: [Number, String], default: 3 },
  min:          { type: [Number, String], default: null },
  max:          { type: [Number, String], default: null },
  step:         { type: [Number, String], default: null },
  autocomplete: { type: String,  default: 'off' },
  showCount:    { type: Boolean, default: false },
  leadingIcon:  { type: String,  default: '' },
  trailingIcon: { type: String,  default: '' },
  // For select type
  options:      { type: Array,   default: () => [] },
})

const emit = defineEmits(['update:modelValue', 'blur', 'focus'])
const inputEl = ref(null)
const uid = useId()
const inputId = `input-${uid}`
const hintId = `hint-${uid}`
const errorId = `error-${uid}`

const describedBy = computed(() => {
  const ids = []
  if (props.hint)  ids.push(hintId)
  if (props.error) ids.push(errorId)
  return ids.length ? ids.join(' ') : undefined
})

const wrapperClass = computed(() => [
  props.disabled ? 'input-wrapper--disabled' : '',
  props.error    ? 'input-wrapper--error' : '',
])

const sizeMap = {
  sm: 'input-container--sm',
  md: 'input-container--md',
  lg: 'input-container--lg',
}
const containerClass = computed(() => sizeMap[props.size] ?? sizeMap.md)

function onInput(e) {
  emit('update:modelValue', e.target.value)
}

function onSelectChange(e) {
  emit('update:modelValue', e.target.value)
}

// Expose focus method
defineExpose({ focus: () => inputEl.value?.focus() })
</script>

<style scoped>
.input-wrapper {
  display: flex;
  flex-direction: column;
  gap: var(--space-1, 0.25rem);
}

.input-wrapper--disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* ── Label ─────────────────────────────────── */
.input-label {
  font-size: var(--font-size-sm, 0.875rem);
  font-weight: 500;
  color: var(--color-neutral-800, #27272a);
  line-height: var(--leading-tight, 1.25);
}

.input-label--required .input-required-mark {
  color: var(--color-danger, #dc2626);
  margin-left: 1px;
}

/* ── Container ─────────────────────────────── */
.input-container {
  position: relative;
  display: flex;
  align-items: center;
  transition: all var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
}

.input-container--sm .input-field {
  padding: var(--space-1-5, 0.375rem) var(--space-3, 0.75rem);
  font-size: var(--font-size-sm, 0.875rem);
}
.input-container--md .input-field {
  padding: var(--space-2-5, 0.625rem) var(--space-4, 1rem);
  font-size: var(--font-size-base, 1rem);
}
.input-container--lg .input-field {
  padding: var(--space-3-5, 0.875rem) var(--space-5, 1.25rem);
  font-size: var(--font-size-lg, 1.125rem);
}

.input-container:focus-within {
  z-index: 1;
}

/* ── Field ─────────────────────────────────── */
.input-field {
  width: 100%;
  border: 1px solid var(--color-neutral-300, #d4d4d8);
  border-radius: var(--radius-md, 0.5rem);
  background: white;
  color: var(--color-neutral-900, #18181b);
  font-family: var(--font-sans, 'DM Sans', sans-serif);
  transition: all var(--transition-fast, 120ms cubic-bezier(0.4, 0, 0.2, 1));
  outline: none;
  appearance: none;
}

.input-field::placeholder {
  color: var(--color-neutral-400, #a1a1aa);
}

.input-field:focus {
  border-color: var(--color-primary, #1a2a4a);
  box-shadow: 0 0 0 3px rgba(26, 42, 74, 0.12);
}

.input-wrapper--error .input-field {
  border-color: var(--color-danger, #dc2626);
}

.input-wrapper--error .input-field:focus {
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
}

.input-field:disabled {
  background: var(--color-neutral-100, #f4f4f5);
  cursor: not-allowed;
}

/* ── Textarea ──────────────────────────────── */
.input-field--textarea {
  resize: vertical;
  min-height: 80px;
  line-height: var(--leading-relaxed, 1.625);
}

/* ── Select ────────────────────────────────── */
.input-field--select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2371717a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right var(--space-3, 0.75rem) center;
  padding-right: var(--space-10, 2.5rem);
  cursor: pointer;
}

/* ── Icons ─────────────────────────────────── */
.input-icon {
  position: absolute;
  display: flex;
  align-items: center;
  color: var(--color-neutral-400, #a1a1aa);
  pointer-events: none;
}

.input-icon--leading { left: var(--space-3, 0.75rem); }
.input-icon--trailing { right: var(--space-3, 0.75rem); }

.input-container--sm .input-icon--leading + .input-field { padding-left: var(--space-8, 2rem); }
.input-container--md .input-icon--leading + .input-field { padding-left: var(--space-9, 2.25rem); }
.input-container--lg .input-icon--leading + .input-field { padding-left: var(--space-10, 2.5rem); }

/* ── Hint & Error ──────────────────────────── */
.input-hint {
  font-size: var(--font-size-xs, 0.75rem);
  color: var(--color-neutral-500, #71717a);
  margin: 0;
}

.input-error {
  display: flex;
  align-items: center;
  gap: var(--space-1, 0.25rem);
  font-size: var(--font-size-xs, 0.75rem);
  color: var(--color-danger, #dc2626);
  margin: 0;
}

.input-error-icon {
  width: 14px;
  height: 14px;
  flex-shrink: 0;
}

.input-count {
  font-size: var(--font-size-xs, 0.75rem);
  color: var(--color-neutral-400, #a1a1aa);
  text-align: right;
  margin: 0;
}
</style>
