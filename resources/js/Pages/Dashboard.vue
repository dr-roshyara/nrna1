<template>
<nrna-layout>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <welcome />
                </div>
            </div>
        </div> 
    </app-layout>
</nrna-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
  import NrnaLayout from '../Layouts/NrnaLayout.vue'

    export default {
        props:{
            imagename: ''
        },
        components: {
            AppLayout,
            Welcome,
            NrnaLayout,
        },
    }
</script>
