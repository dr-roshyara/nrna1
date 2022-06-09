<template>
    <profile-layout>
        <main class="min-h-screen">
            <div>
                <!-- {{ user }} -->
                <profile-image :user="user" :isLoggedIn="isLoggedIn">
                </profile-image>
            </div>
            <div relative class="grid h-40 place-items-center">
                <!-- Upload icon  -->
                <div class="absolute grid h-40 w-full place-items-center">
                    <icon-upload
                        @icon-uploaded="incon_uploaded"
                        image_type="icon"
                        class="absolute bottom-0 grid w-96 place-items-center rounded-full"
                    >
                    </icon-upload>
                </div>
            </div>
            <jet-authentication-card class="mb-6">
                <jet-validation-errors class="mb-4" />
                <form @submit.prevent="submit">
                    <!-- name prefex  -->

                    <!-- name prefex  -->
                    <div class="mt-4">
                        <jet-label
                            for="name_prefex"
                            value="Name Prefex (optional)"
                        />
                        <div
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <select
                                name="name_prefex"
                                id="name_prefex"
                                v-model="form.name_prefex"
                                class="focus:shadow-outline block w-full appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 leading-tight shadow hover:border-gray-500 focus:outline-none"
                            >
                                <option value="Ms">Ms</option>
                                <option value="Miss">Miss</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Mr">Mr.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Atty">Attorney (Atty)</option>
                                <option value="Hon">Honorable (Hon)</option>
                                <option value="Prof. Dr.">
                                    Professor (Prof. Dr.)
                                </option>
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

                    <!-- first  name    -->
                    <div class="mt-4 pb-1">
                        <jet-label for="firstName" value="First  Name" />
                        <jet-input
                            id="firstName"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.firstName"
                            required
                            autofocus
                            autocomplete="firstName"
                        />
                    </div>
                    <!-- Middle name  -->
                    <div class="mt-4">
                        <jet-label for="middleName" value="Middle Name" />
                        <jet-input
                            id="middleName"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.middleName"
                            autofocus
                            autocomplete="middleName"
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
                    <!-- gender information  -->
                    <div class="mt-4">
                        <jet-label for="gender" value="Gender (optional)" />
                        <div
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <select
                                name="gender"
                                id="gender"
                                v-model="form.gender"
                                class="focus:shadow-outline block w-full appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 leading-tight shadow hover:border-gray-500 focus:outline-none"
                            >
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
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
                    <!-- email  -->
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
                    <!-- country information  -->

                    <div class="mt-4">
                        <jet-label for="country" value="Country" />
                        <jet-input
                            id="country"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.country"
                            required
                            autofocus
                            autocomplete="country"
                        />
                    </div>
                    <!-- state -->
                    <div class="mt-4">
                        <jet-label for="state" value="State(optional)" />
                        <jet-input
                            id="state"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.state"
                            autofocus
                            autocomplete="state"
                        />
                    </div>
                    <!-- city information  -->
                    <div class="mt-4">
                        <jet-label for="city" value="City" />
                        <jet-input
                            id="city"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.city"
                            required
                            autofocus
                            autocomplete="city"
                        />
                    </div>
                    <!-- street information  -->
                    <div class="mt-4">
                        <jet-label for="street" value="Street (optional)" />
                        <jet-input
                            id="street"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.street"
                            autofocus
                            autocomplete="street"
                        />
                    </div>
                    <!-- house information  -->
                    <div class="mt-4">
                        <jet-label
                            for="housenumber"
                            value="House number(optional)"
                        />
                        <jet-input
                            id="housenumber"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.housenumber"
                            autofocus
                            autocomplete="housenumber"
                        />
                    </div>
                    <!-- postal code -->
                    <div class="mt-4">
                        <jet-label
                            for="postalcode"
                            value="Postal code (optional)"
                        />
                        <jet-input
                            id="postalcode"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.postalcode"
                            autofocus
                            autocomplete="postalcode"
                        />
                    </div>
                    <!-- designation  -->
                    <div class="mt-4">
                        <jet-label
                            for="designation"
                            value="Designation/पद (optional)"
                        />
                        <jet-input
                            id="designation"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.designation"
                            autofocus
                            autocomplete="designation"
                        />
                    </div>
                    <!-- Update Button  -->
                    <div class="mt-4 flex items-center justify-end">
                        <jet-button
                            class="ml-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Update
                        </jet-button>
                    </div>
                </form>
            </jet-authentication-card>
            <a
                v-show="clickUserProfile"
                id="clickUserProfile"
                :href="route('user.show', $page.props.user.user_id)"
            >
                user profile
            </a>
        </main>
    </profile-layout>
</template>
<script>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import ProfileImage from "@/Components/Profile/ProfileImage.vue";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import IconUpload from "@/Components/Upload/IconUpload.vue";
export default {
    props: {
        user: Array,
        isLoggedIn: Boolean,
    },
    components: {
        ProfileLayout,
        ProfileImage,
        JetAuthenticationCard,
        JetValidationErrors,
        JetLabel,
        JetCheckbox,
        JetInput,
        JetButton,
        IconUpload,
    },
    data() {
        return {
            clickUserProfile: false,
            form: this.$inertia.form({
                _method: "PUT",
                firstName: this.user.first_name,
                middleName: this.user.middle_name,
                lastName: this.user.last_name,
                name_prefex: this.user.name_prefex,
                email: this.user.email,
                region: this.user.region,
                gender: this.user.gender,
                country: this.user.country,
                state: this.user.state,
                city: this.user.city,
                street: this.user.street,
                postalcode: this.user.postalcode,
                housenumber: this.user.housenumber,
                designation: this.user.designation,

                // old_password:'',
                // password: '',
                // password_confirmation: '',
                terms: false,
            }),
        };
    },
    methods: {
        submit() {
            this.$inertia.put(
                this.route("user.update", { id: this.user.id }),
                this.form,
                {
                    //    log("success");
                    // onFinish: () => this.form.reset('password', 'password_confirmation'),
                }
            );
        },
        incon_uploaded() {
            // console.log(this.user.user_id);
            // console.log(this.user);
            this.clickUserProfile = true;
            var el = document.getElementById("clickUserProfile");
            el.click();
            // console.log(this.route("user.show", this.user.user_id));
        },
    },
};
</script>
