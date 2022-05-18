<template>
<profile-layout>
  <main class="min-h-screen">
      <jet-authentication-card class="mb-6">
          <jet-validation-errors class="mb-4" />
          <form @submit.prevent="submit">
                <!-- first and middle name    -->
                <div class="pb-1">
                    <jet-label for="firstName" value="First  Name" />
                    <jet-input id="firstName" type="text"
                    class="mt-1 block w-full"
                    v-model="form.firstName"
                    required autofocus autocomplete="firstName" />
                </div>
                 <!-- Middle name  -->
                <div class="mt-4">
                <jet-label for="middleName" value="Middle Name" />
                <jet-input id="middleName" type="text"
                class="mt-1 block w-full"
                v-model="form.middleName"
                required autofocus autocomplete="middleName" />
                </div>
                <!-- Last name  -->
                <div class="mt-4">
                    <jet-label for="lastName" value="Last Name" />
                    <jet-input id="lastName" type="text"
                    class="mt-1 block w-full"
                    v-model="form.lastName"
                    required autofocus autocomplete="lastName" />
                </div>
                    <!-- here starts the region selection  -->
                <div class="mt-4">
                    <jet-label for="region" value="Region" />
                    <div class="border-gray-300 focus:border-indigo-300 focus:ring
                        focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" >
                        <select name ="region" id="region"
                            v-model="form.region"
                            class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                            <option>Europe </option>
                            <option>America </option>
                            <option>Africa  </option>
                            <!-- <option>Asia  </option> -->
                            <option>Asia Pacific</option>
                            <option>Middle East Asia</option>
                            <option>Oceania </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <!-- email  -->
                <div class="mt-4">
                    <jet-label for="email" value="Email" />
                    <jet-input id="email" type="email"
                    class="mt-1 block w-full" v-model="form.email" required />
                </div>

                  <!-- Update Button  -->
                 <div class="flex items-center justify-end mt-4">
                    <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update
                    </jet-button>
                </div>
            </form>
      </jet-authentication-card>
    {{user}}
    </main>

</profile-layout>

</template>
<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue"
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
 import JetValidationErrors from '@/Jetstream/ValidationErrors'
   import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
export default{
   props:{
       'user':Array,
       'isLoggedIn':Boolean
   },
   components:{
       ProfileLayout,
       JetAuthenticationCard,
       JetValidationErrors,
       JetLabel,
       JetCheckbox,
       JetInput,
       JetButton

   },
   data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    firstName: this.user.first_name,
                    middleName: this.user.middle_name,
                    lastName:   this.user.last_name,
                    email: this.user.email,
                    region: this.user.region,
                    old_password:'',
                    password: '',
                    password_confirmation: '',
                    terms: false,

                })
            }
        },
    methods: {
            submit() {
                this.$inertia.put(this.route('user.update',
                {id: this.user.id}),
                this.form,
                {
                //    log("success");
                   // onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
    }
}
</script>
