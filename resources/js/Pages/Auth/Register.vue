<template>
    <nrna-layout>
    <jet-authentication-card class="mb-6">
        <jet-validation-errors class="mb-4" />
           <p class="text-gray-900 font-bold text-2xl p-2 my-2 mx-auto text-center"> User Registration </p> 
        <form @submit.prevent="submit">
          <!-- add name    -->
           <div>
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" 
                class="mt-1 block w-full" 
                v-model="form.name" 
                required autofocus autocomplete="name" />
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
                <option>Asia  </option>
                <option>Asia Pacific</option>
                 <option>Middle East Asia</option>
                 <option>Oceania </option>
             </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
            </div>
              </div>

            <!-- next -->
            <div class="mt-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
            </div>

            <div class="mt-4">
            <div class="flex flex-wrap space-x-2">
            <jet-label for="password" value="Password"  /> 
                <span class="text-red-500 text-sm" >(min 8 characters)</span> 
             </div> 
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
            </div>
 
            <div class="mt-4">
                <jet-label for="password_confirmation" value="Confirm Password" />
                <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                <jet-label for="terms">
                    <div class="flex items-center">
                        <jet-checkbox name="terms" id="terms" v-model:checked="form.terms" />

                        <div class="ml-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                        </div>
                    </div>
                </jet-label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
        </nrna-layout>
</template>

<script>
    import NrnaLayout from '@/Layouts/NrnaLayout'
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            NrnaLayout,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        data() {
            return {
                form: this.$inertia.form({
                                                       
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                    'region': ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
