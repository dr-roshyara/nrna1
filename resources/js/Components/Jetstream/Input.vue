<template>
  <input
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="block w-full px-4 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
    v-bind="$attrs"
  />
</template>

<script>
export default {
  name: 'JetInput',
  props: {
    modelValue: String,
  },
  emits: ['update:modelValue'],
}
</script>
