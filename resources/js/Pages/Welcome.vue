<template>
    <!-- <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.user" href="/dashboard" class="text-sm text-gray-700 underline">
                Dashboard
            </inertia-link>

            <template v-else>
                <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                    Log in
                </inertia-link>

                <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Register
                </inertia-link>
               
            </template>
        </div> 
        -->
           <div v-if="!loggedIn" >      
           <login canResetPassword="true"> </login>                    
            </div>
            <div v-else>
                <dashboard> </dashboard>
            </div>

        
</template>

<script>
    import Login from '@/Pages/Auth/Login'
     import Dashboard from '@/Pages/Dashboard'
    export default {
        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
            role: String,
            loggedIn: Boolean 
        },
        components:{
            Login,
            Dashboard
        }
    }
</script>
