<template>
   
    <jet-authentication-card>
     
        <template #logo>
            <jet-authentication-card-logo />
           
        </template>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
         <!-- next -->
         <div class=" my-2 text-gray-900 " > 
        आदरणिय दिदी बहिनी तथा दाजुभाइहरु,<br> 
        लगइन मा आफ्नो टेलिफोन नम्बर कन्ट्रीकोड सहित<br> 
         <span class="text-bold"> (तर विना '+' र विना '00') </span> <br> 
         लेख्नु होला उदाहरणको लागि  जर्मनीको कन्ट्री कोड (49) सहित तलको लग इन नम्बर हेर्नु हुनेछ। <br>
        <span class="text-bold m-2"> लगइन: </span> 4915164322589 <br>
        <span class="text-bold m-2"> पासवर्ड:</span> यो तपाईंहरुले एसएमएस मार्फत पाउनु भएको छ।<br> 
         </div>    
        <!-- next -->
        <form @submit.prevent="submit">
            <!-- 
            <div>
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
            </div>
            -->
            <!--next --> 
            <div class=" my-4 text-bold text-gray-900 text-xl ">
                <jet-label for="telephone" value="Telephone (टेलिफोन नम्बर)"  /> 
                <jet-input id="telephone" type="text" class="mt-1 block w-full"  placeholder="4915164322589"
                v-model="form.telephone" required autofocus />
            </div>
            <!--next --> 
            
            <div class="my-4">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-900">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </jet-button>
            </div>
        </form>

    </jet-authentication-card>
  
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        props: {
            imagename: String,
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    telephone: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    }
</script>
<style scoped>
    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }
    .my-4{
        margin: 1rem;
    }
    .text-bold{
         font-weight: bold;
    }
    
</style>
