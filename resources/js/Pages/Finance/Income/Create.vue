<template>
    <nrna-layout>
        <jet-authentication-card class="mb-6">
            <jet-validation-errors class="mb-4" />
            <p
                class="my-2 mx-auto p-2 text-center text-2xl font-bold text-gray-900"
            >
                Income Formula
            </p>
            <form @submit.prevent="submit">
                <!-- first and middle name    -->
                <div class="pb-1">
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
                <!-- Last name  -->
                <!-- here starts the region selection  -->
                <div class="mt-4">
                    <jet-label for="committee_name" value="Committee Name" />
                    <div
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                        <select
                            name="committee_name"
                            id="committee_name"
                            v-model="form.committee_name"
                            class="focus:shadow-outline block w-full appearance-none rounded border border-gray-400 bg-white px-4 py-2 pr-8 leading-tight shadow hover:border-gray-500 focus:outline-none"
                        >
                            <option>ICC</option>
                            <option>NCC</option>
                            <option>Regional Committee</option>
                            <option>Departmental Committee</option>
                            <!-- <option>Asia  </option> -->
                            <option>LCC</option>
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
                    <jet-label for="period_from" value="Report Period To" />
                    <jet-input
                        id="period_from"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.period_from"
                        required
                    />
                </div>
                <!-- report to  -->
                <!-- next -->
                <div class="mt-4">
                    <jet-label for="period_to" value="Report Period from" />
                    <jet-input
                        id="period_to"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.period_to"
                        required
                    />
                </div>
                <!-- membership fee  -->
                <!-- next -->
                <div class="mt-4">
                    <jet-label for="membership_fee" value="Membership Fee" />
                    <jet-input
                        id="membership_fee"
                        type="decimal"
                        class="mt-1 block w-full"
                        v-model="form.membership_fee"
                        required
                    />
                </div>
                <!-- next -->
                <div class="mt-4">
                    <jet-label for="nomination_fee" value="Nomination Fee" />
                    <jet-input
                        id="nomination_fee"
                        type="decimal"
                        class="mt-1 block w-full"
                        v-model="form.nomination_fee"
                        required
                    />
                </div>

                <div class="mx-auto mt-4 w-full">
                    <jet-button
                        class="mx-4 w-full text-center"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        <span class="mx-auto"> Submit </span>
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
                country: "",
                committee_name: "",
                period_from: "",
                period_to: "",
                membership_fee: 0.0,
                nomination_fee: 0.0,
                deligate_fee: 0.0,
                sponser_fee: 0.0,
                donation: 0.0,
                levy: 0.0,
                event_contribution: 0.0,
                event_income: 0.0,
                business_income: 0.0,
                deligate_contribution: 0.0,
                other_incomes: 0.0,
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
