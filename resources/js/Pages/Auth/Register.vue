<template>
    <nrna-layout>
        <jet-authentication-card class="mb-6">
            <jet-validation-errors class="mb-4" />
            <p
                class="my-2 mx-auto p-2 text-center text-2xl font-bold text-gray-900"
            >
                User Registration
            </p>
            <form @submit.prevent="submit">
                <!-- first and middle name    -->
                <div class="pb-1">
                    <jet-label for="firstname" value="First  and Middle Name" />
                    <jet-input
                        id="firstname"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.firstName"
                        required
                        autofocus
                        autocomplete="firstName"
                    />
                </div>
                <!-- Last name  -->
                <div class="mt-4">
                    <jet-label for="lastName" value="Last Name" />
                    <jet-input
                        id="lastName"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.lastName"
                        required
                        autofocus
                        autocomplete="lastName"
                    />
                </div>
                <!-- here starts the region selection  -->
                <div class="mt-4">
                    <jet-label for="region" value="Region" />
                    <div
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                        <select
                            name="region"
                            id="region"
                            v-model="form.region"
                            class="focus:shadow-outline block w-full appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 leading-tight shadow hover:border-gray-500 focus:outline-none"
                        >
                            <option>Europe</option>
                            <option>America</option>
                            <option>Africa</option>
                            <!-- <option>Asia  </option> -->
                            <option>Asia Pacific</option>
                            <option>Middle East Asia</option>
                            <option>Oceania</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                        >
                            <svg
                                class="h-4 w-4 fill-current"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- next -->
                <div class="mt-4">
                    <jet-label for="email" value="Email" />
                    <jet-input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />
                </div>

                <div class="mt-4">
                    <div class="flex flex-wrap space-x-2">
                        <jet-label for="password" value="Password" />
                        <span class="text-sm text-red-500">
                            (Please choose a password with minimum 8
                            characters)</span
                        ><br />
                        <span class="text-sm text-gray-700">
                            कम्तिमा आठ अक्षर भएको कुनै एक नयाँ पासवर्ड तलको
                            कोठाभित्र भर्नुहोला। साथै भरेको पासवर्डलाइ संझेर
                            राख्नु होला । यहाँ भरेकाे पासवर्ड विना यहाँले लगइन
                            गर्न सक्नु हुने छैन। </span
                        ><br />
                    </div>
                    <jet-input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div class="mt-4">
                    <jet-label
                        for="password_confirmation"
                        value="Confirm Password"
                    />
                    <div flex flex-wrap>
                        <span> Please retype your password. </span>
                        <span class="text-sm text-gray-700">
                            माथि भरेकाे पासवर्ड पुनः लेख्नुहोस।
                        </span>
                    </div>
                    <jet-input
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div
                    class="mt-4"
                    v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                >
                    <jet-label for="terms">
                        <div class="flex items-center">
                            <jet-checkbox
                                name="terms"
                                id="terms"
                                v-model:checked="form.terms"
                            />

                            <div class="ml-2">
                                I agree to the
                                <a
                                    target="_blank"
                                    :href="route('terms.show')"
                                    class="text-sm text-gray-600 underline hover:text-gray-900"
                                    >Terms of Service</a
                                >
                                and
                                <a
                                    target="_blank"
                                    :href="route('policy.show')"
                                    class="text-sm text-gray-600 underline hover:text-gray-900"
                                    >Privacy Policy</a
                                >
                            </div>
                        </div>
                    </jet-label>
                    <div class="py-1 text-sm text-gray-600">
                        कृपया माथिकाे सानो चेकबक्समा क्लिक गरी एनआरएनए नमूना
                        निर्वाचनमा भाग लिन सहमति जनाउनु होला।
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <inertia-link
                        :href="route('login')"
                        class="text-sm text-gray-600 underline hover:text-gray-900"
                    >
                        Already registered? Please go to Log in.
                    </inertia-link>

                    <jet-button
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Register
                    </jet-button>
                </div>
            </form>
        </jet-authentication-card>
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/LoginLayout";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    components: {
        NrnaLayout,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
    },

    data() {
        return {
            form: this.$inertia.form({
                firstName: "",
                lastName: "",
                email: "",
                password: "",
                password_confirmation: "",
                terms: false,
                region: "",
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("register"), {
                onFinish: () =>
                    this.form.reset("password", "password_confirmation"),
            });
        },
    },
};
</script>
