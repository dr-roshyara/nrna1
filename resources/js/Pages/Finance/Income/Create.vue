<template>
    <social-layout>
        <div class="mx-auto flex max-w-md flex-col sm:w-full">
            <jet-validation-errors class="mb-4" />
            <p
                class="my-2 mx-auto p-2 text-center text-2xl font-bold text-gray-900"
            >
                Income Formula
            </p>
            <form @submit.prevent="submit">
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
                        class="mt-1 block w-full border border-blue-100"
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
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.nomination_fee"
                        required
                    />
                </div>
                <!-- sponser fee  -->
                <div class="mt-4">
                    <jet-label for="sponser_fee" value="Sponser Fee" />
                    <jet-input
                        id="sponser_fee"
                        type="decimal"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.sponser_fee"
                        required
                    />
                </div>
                <!-- sponser fee  -->
                <div class="mt-4">
                    <jet-label for="donation" value="Donation" />
                    <jet-input
                        id="donation"
                        type="decimal"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.donation"
                    />
                </div>

                <!-- Levy -->
                <div class="mt-4">
                    <jet-label for="levy" value="Levy" />
                    <jet-input
                        id="levy"
                        type="levy"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.levy"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label
                        for="interest_income"
                        value="Income from Interest"
                    />
                    <jet-input
                        id="interest_income"
                        type="interest_income"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.interest_income"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="event_fee" value="Event Fee" />
                    <jet-input
                        id="event_fee"
                        type="event_fee"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.event_fee"
                    />
                </div>
                <!-- // -->
                <div class="mt-4">
                    <jet-label
                        for="event_contribution"
                        value="Event contribution"
                    />
                    <jet-input
                        id="event_contribution"
                        type="event_contribution"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.event_contribution"
                    />
                </div>

                <!-- // -->
                <div class="mt-4">
                    <jet-label for="event_income" value="Event sale" />
                    <jet-input
                        id="event_income"
                        type="event_income"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.event_income"
                    />
                </div>
                <!-- next   -->
                <div class="mt-4">
                    <jet-label for="deligate_fee" value="Deligate fee" />
                    <jet-input
                        id="deligate_fee"
                        type="deligate_fee"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.deligate_fee"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label
                        for="deligate_contribution"
                        value="Deligate contribution"
                    />
                    <jet-input
                        id="deligate_contribution"
                        type="deligate_contribution"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.deligate_contribution"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label
                        for="business_income"
                        value="Income from Business"
                    />
                    <jet-input
                        id="business_income"
                        type="business_income"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.business_income"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="other_incomes" value="Other incomes" />
                    <jet-input
                        id="other_incomes"
                        type="other_incomes"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.other_incomes"
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
        </div>
    </social-layout>
</template>

<script>
import SocialLayout from "@/Layouts/SocialLayout";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    components: {
        SocialLayout,
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
                interest_income: 0.0,
                event_fee: 0.0,
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
            this.form.post(this.route("finance.income.submit"), {
                onFinish: () => this.form.reset(),
            });
        },
    },
};
</script>
