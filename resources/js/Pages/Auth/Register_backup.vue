<template>
    <nrna-layout>
    <jet-authentication-card class="mb-6">
        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
          <!-- add name    -->
           <div>
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" 
                class="mt-1 block w-full" 
                v-model="form.name" 
                required autofocus autocomplete="name" />
            </div>
         <!-- Add the other coulmns -->
             <div>
                <jet-label for="first_name" value="Firstname" />
                <jet-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
            </div>
            <!-- next -->
             <div>
                <jet-label for="middle_name" value="Middlename" />
                <jet-input id="middle_name" type="text" class="mt-1 block w-full" v-model="form.middle_name" required autofocus autocomplete="middle_name" />
            </div>
 
            <!-- next -->
             <div>
                <jet-label for="last_name" value="Lastname" />
                <jet-input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus autocomplete="last_name" />
            </div>
             <!-- next -->
              <!-- next -->
             <div>
                <jet-label for="gender" value="Gender" />
                <jet-input id="gender" type="text" class="mt-1 block w-full" v-model="form.gender" required autofocus autocomplete="gender" />
            </div>
             <!-- next -->
             <div>
                <jet-label for="telephone" value="Telephone" />
                <jet-input id="telephone" type="text" class="mt-1 block w-full" v-model="form.telephone" required autofocus autocomplete="telephone" />
            </div>
               <!-- next -->
             <div>
                <jet-label for="country" value="Country" />
                <jet-input id="Country" type="text" class="mt-1 block w-full" v-model="form.country" required autofocus autocomplete="country" />
            </div>
                      <!-- next -->
             <div>
                <jet-label for="state" value="State" />
                <jet-input id="state" type="text" class="mt-1 block w-full" v-model="form.state" required autofocus autocomplete="state" />
            </div>
            <!-- next -->
              <div>
                <jet-label for="street" value="Street" />
                <jet-input id="street" type="text" class="mt-1 block w-full" v-model="form.street" required autofocus autocomplete="street" />
            </div>
                <!-- next -->
            <div>
                <jet-label for="housenumber" value="House number" />
                <jet-input id="housenumber" type="text" class="mt-1 block w-full" v-model="form.housenumber" required autofocus autocomplete="housenumber" />
            </div>
        
            <!-- next -->
                <div>
                <jet-label for="postalcode" value="Postalcode" />
                <jet-input id="postalcode" type="text" class="mt-1 block w-full" v-model="form.postalcode" required autofocus autocomplete="postalcode" />
            </div>
            <!-- next -->
                <div>
                <jet-label for="city" value="City" />
                <jet-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" required autofocus autocomplete="city" />
            </div>
              <!-- next -->
                <div>
                <jet-label for="nrna_id" value="Membership Id" />
                <jet-input id="nrna_id" type="text" class="mt-1 block w-full" v-model="form.nrna_id" required autofocus autocomplete="nrna_id" />
            </div>
           
            <!-- next -->
            <div class="mt-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Password" />
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
                    first_name: '',
                    middle_name: '',  
                    last_name: '', 
                    gender: '', 
                    telephone: '',
                    nrna_id: '',
                    country: '',
                    state: '',
                    street : '',
                    housenumber : '',
                    postalcode: '',
                    city: '',                                      
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
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
