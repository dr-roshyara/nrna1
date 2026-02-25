<template>
  <a :href="href" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out" :class="active ? 'border-blue-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'">
    <slot />
  </a>
</template>

<script>
export default {
  name: 'JetNavLink',
  props: {
    href: String,
    active: Boolean,
  },
}
</script>
