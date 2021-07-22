<template>
    <input type="checkbox" 
        id="forValue" :value="value" v-model="proxyChecked" 
           class="p-6 rounded border-gray-800 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
</template>

<script>
export default {
    emits: ['update:checked'],

    props: {
        forValue: String,
        checked: {
            type: Array, 
            // default: false,
        },
        value: {
            default: null,
        },
    },

    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },

            set(val) {
                this.$emit("update:checked", val);
                //  this.$emit(val);
            },
        },
    },
}
</script>
