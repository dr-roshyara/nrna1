<template>
    <nrna-layout>
    <div class="flex flex-col">     
                   <!-- <a href="https://www.civiciti.com/ww/nrnagermany" class="m-auto" > 
                      <div class=" flex flex-col h-64 py-2 m-auto text-center px-10 w-full">
                       <p class="w-full text-center text-gray-900 font-bold"> 
                         VOTE  HERE </p> 
                       <img src="/storage/images/ballot.png" width="200" height="200" />
                    </div> 

            </a> -->
         
        <jet-authentication-card class="rounded min-h-screen border-t border-blue-500 mb-2">
            <!-- <div class="p-2 text-sm text-blue-600 font-semibold" >
                <p class="py-2">यहाँको नाम एनआरएनए को भोटिङ लिस्टमा परेन ? केही छैन, यहाँले तलको पेजमा लगइन गरेर भोट हाल्न सक्नु हुन्छ। 
                 </p>
                 <p class="py-2"> You are not included in the voter list? Don't worry, you can follow the instruction below to login and vote there.</p> 
            </div> --> 

            <div class="mb-1"> 
                <jet-validation-errors class="pt-1" />
                <div v-if="status" class=" font-medium text-sm text-green-600">
                {{ status }}
                </div>
            </div> 
             <p class="px-auto mx-auto py-4 my-4
              text-red-800 font-bold text-2xl text-center"> 
                 सदस्य लगइन  </p>
            <!-- next --> 
            
            <div class="text-gray-900 " > 
            आदरणिय दिदी बहिनी तथा दाजुभाइहरु,<br> 
            लगइन मा आफ्नो टेलिफोन नम्बर कन्ट्रीकोड सहित<br> 
            <span class="text-bold"> (तर विना '+' र विना '00') </span> <br> 
            लेख्नु होला । उदाहरणको लागि  जर्मनीको कन्ट्री कोड (49) सहित तलको लग इन नम्बर हेर्नु हुनेछ। <br>
            <span class="text-bold m-2"> लगइन उदाहरणको लागि: </span> 4915164322589 <br>
            <span class="text-bold m-2"> पासवर्ड:</span> 
            यस्को पासवर्ड तपाईंले लिन्क <br/>  
             <inertia-link href="http://127.0.0.1:8000/forgot-password">
            <span class="text-gray-900 font-bold text-sm">  GET YOUR PASSWORD </span> </inertia-link> मा क्लिक गरेर पाउन सक्नु  हुन्छ।<br> 
            </div>     
            <!-- next -->
            <form @submit.prevent="submit" class="mb-4">
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

                <div class="flex items-center justify-end mt-4 ">
                    <inertia-link 
                    v-if="canResetPassword" :href="route('password.request')" class="underline font-bold text-lg text-gray-900 hover:text-gray-900">
                        Get  your password here
                    </inertia-link> 

                    <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </jet-button>
                </div>
            </form>

        </jet-authentication-card>
         </div>
        </nrna-layout>
    
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'
    import NrnaLayout from "@/Layouts/NrnaLayout";  
    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            NrnaLayout 
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
