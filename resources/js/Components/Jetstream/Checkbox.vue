<template>
  <input
    type="checkbox"
    :checked="modelValue"
    @input="$emit('update:modelValue', $event.target.checked)"
    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
    v-bind="$attrs"
  />
</template>

<script>
export default {
  name: 'JetCheckbox',
  props: {
    modelValue: Boolean,
  },
  emits: ['update:modelValue'],
}
</script>
