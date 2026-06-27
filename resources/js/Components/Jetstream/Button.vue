<!-- @deprecated — use Button.vue instead -->
<script setup>
import Button from '../Button.vue'

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
})
</script>
<template><Button :type="type" variant="primary" v-bind="$attrs"><slot /></Button></template>
