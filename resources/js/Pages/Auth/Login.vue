<template>
    <nrna-layout :canRegister="canRegister" :canLogin="canLogin">
        <div class="flex flex-col">
            <jet-authentication-card
                class="mb-2 min-h-screen rounded border-t border-blue-500"
            >
                <!-- <div class="p-2 text-sm text-blue-600 font-semibold" >
                <p class="py-2">यहाँको नाम एनआरएनए को भोटिङ लिस्टमा परेन ? केही छैन, यहाँले तलको पेजमा लगइन गरेर भोट हाल्न सक्नु हुन्छ।
                 </p>
                 <p class="py-2"> You are not included in the voter list? Don't worry, you can follow the instruction below to login and vote there.</p>
            </div> -->

                <div class="mb-1">
                    <jet-validation-errors class="pt-1" />
                    <div
                        v-if="status"
                        class="text-sm font-medium text-green-600"
                    >
                        {{ status }}
                    </div>
                </div>
                <p
                    class="px-auto mx-auto my-4 py-4 text-center text-2xl font-bold text-red-800"
                >
                    Login (सदस्य लगइन)
                </p>
                <!-- next -->

                <!-- <div class="text-gray-900 " >
            आदरणिय दिदी बहिनी तथा दाजुभाइहरु,<br>
            लगइन मा आफ्नो टेलिफोन नम्बर कन्ट्रीकोड सहित<br>
            <span class="text-bold"> (तर विना '+' र विना '00') </span> <br>
            लेख्नु होला । उदाहरणको लागि  जर्मनीको कन्ट्री कोड (49) सहित तलको लग इन नम्बर हेर्नु हुनेछ। <br>
            <span class="text-bold m-2"> लगइन उदाहरणको लागि: </span> 4915164322589 <br>
            <span class="text-bold m-2"> पासवर्ड:</span>
            यस्को पासवर्ड तपाईंले लिन्क <br/>
             <Link href="http://127.0.0.1:8000/forgot-password">
            <span class="text-gray-900 font-bold text-sm">  GET YOUR PASSWORD </span> </Link> मा क्लिक गरेर पाउन सक्नु  हुन्छ।<br>
            </div>
                 -->
                <!-- next -->
                <form @submit.prevent="submit" class="mb-4">
                    <!--
                <div>
                    <jet-label for="email" value="Email" />
                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
                </div>
                -->
                    <!--next -->
                    <div class="text-bold my-4 text-xl text-gray-900">
                        <jet-label for="email" value="Email" />

                        <!-- <jet-input id="telephone" type="text"
                    class="mt-1 block w-full"
                    placeholder="4915164322589"
                    v-model="form.telephone" required autofocus />
                     -->
                        <jet-input
                            id="email"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="roshyara@gmail.com"
                            v-model="form.email"
                            required
                            autofocus
                        />
                    </div>
                    <!--next -->

                    <div class="my-4">
                        <jet-label for="password" value="Password" />
                        <jet-input
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="mt-4 block">
                        <label class="flex items-center">
                            <jet-checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />
                            <span class="ml-2 text-sm text-gray-900"
                                >Remember me</span
                            >
                        </label>
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="font-bold text-gray-900 underline hover:text-gray-900"
                        >
                            Get your password here
                        </Link>

                        <jet-button
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Log in
                        </jet-button>
                    </div>
                </form>
                <div
                    v-if="canRegister"
                    class="m-2 flex flex-col justify-center"
                >
                    <p>
                        If you are not registered yet, please get registered
                        first.
                    </p>
                    <Link
                        :href="route('register')"
                        class="m-auto w-1/2 bg-gray-600 px-6 py-1 text-center text-sm font-bold text-white"
                    >
                        Register
                    </Link>
                    <p class="my-2 py-1 font-bold text-red-500">
                        यदि तपाईंले पहिलो पल्ट यो वेवसाइट खोल्नु भाको हो
                        भने,सबैभन्दा पहिले
                        <Link
                            :href="route('register')"
                            class="px-2 font-bold text-gray-700"
                        >
                            यो रजिस्टर लिन्कमा क्लिक गरेर
                        </Link>
                        आफुलाई रजिस्टर गर्नुहोला।
                    </p>
                </div>
                <div class="mt-4 flex items-center justify-end">
                    <a href="/login/google">
                        <img
                            src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
                        />
                    </a>
                </div>
            </jet-authentication-card>
        </div>
    </nrna-layout>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import NrnaLayout from "@/Layouts/LoginLayout";
import { Link } from "@inertiajs/inertia-vue3";
export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        NrnaLayout,
        Link,
    },

    props: {
        imagename: String,
        canResetPassword: Boolean,
        status: String,
        canLogin: Boolean,
        canRegister: Boolean,
    },

    data() {
        return {
            form: this.$inertia.form({
                email: "",
                telephone: "",
                password: "",
                remember: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    remember: this.form.remember ? "on" : "",
                }))
                .post(this.route("login"), {
                    onFinish: () => this.form.reset("password"),
                });
        },
    },
};
</script>
<style scoped>
.text-gray-900 {
    color: #1a202c;
    color: rgba(26, 32, 44, var(--tw-text-opacity));
}
.my-4 {
    margin: 1rem;
}
.text-bold {
    font-weight: bold;
}
</style>
