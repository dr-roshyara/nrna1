<template>
    <social-layout>
        <jet-authentication-card class="mx-auto mb-6 w-full">
            <jet-validation-errors class="mb-4" />
            <p
                class="my-2 mx-auto p-2 text-center text-2xl font-bold text-gray-900"
            >
                Expenditure Formula
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
                <!-- next -->
                <div class="mt-4">
                    <jet-label for="deligate_fee" value="Deligate Fee" />
                    <jet-input
                        id="deligate_fee"
                        type="decimal"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.deligate_fee"
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

                <!-- salaray -->
                <div class="mt-4">
                    <jet-label for="salaray" value="Salaray" />
                    <jet-input
                        id="salaray"
                        type="salaray"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.salaray"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="rent" value="Rent" />
                    <jet-input
                        id="rent"
                        type="rent"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.rent"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="event_cost" value="Event Cost" />
                    <jet-input
                        id="event_cost"
                        type="event_cost"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.event_cost"
                    />
                </div>
                <!-- // -->
                <div class="mt-4">
                    <jet-label for="software" value="Software" />
                    <jet-input
                        id="software"
                        type="software"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.software"
                    />
                </div>

                <!-- // -->
                <div class="mt-4">
                    <jet-label for="communication" value="Communication" />
                    <jet-input
                        id="communication"
                        type="communication"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.communication"
                    />
                </div>
                <!-- next   -->
                <div class="mt-4">
                    <jet-label for="office_cost" value="Office cost" />
                    <jet-input
                        id="office_cost"
                        type="office_cost"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.office_cost"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="postage" value="Postage" />
                    <jet-input
                        id="postage"
                        type="postage"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.postage"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="bank_charge" value="Bank charge" />
                    <jet-input
                        id="bank_charge"
                        type="bank_charge"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.bank_charge"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="election_cost" value="Election cost" />
                    <jet-input
                        id="election_cost"
                        type="election_cost"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.election_cost"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="equipment" value="Equipment cost" />
                    <jet-input
                        id="equipment"
                        type="equipment"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.equipment"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="vechicle" value="Vechicle cost" />
                    <jet-input
                        id="vechicle"
                        type="vechicle"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.vechicle"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="website" value="Website cost" />
                    <jet-input
                        id="website"
                        type="website"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.website"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label
                        for="consulting_charge"
                        value="Consulting charge"
                    />
                    <jet-input
                        id="consulting_charge"
                        type="consulting_charge"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.consulting_charge"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="training_chrage" value="Training charge" />
                    <jet-input
                        id="training_chrage"
                        type="training_chrage"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.training_chrage"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label
                        for="insurance_charge"
                        value="Insurance charge"
                    />
                    <jet-input
                        id="insurance_charge"
                        type="insurance_charge"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.insurance_charge"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label
                        for="guest_invitation"
                        value="Guest invitation cost"
                    />
                    <jet-input
                        id="guest_invitation"
                        type="guest_invitation"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.guest_invitation"
                    />
                </div>

                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="tax_charge" value="Tax charges" />
                    <jet-input
                        id="tax_charge"
                        type="tax_charge"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.tax_charge"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="drink" value="Drinks" />
                    <jet-input
                        id="drink"
                        type="drink"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.drink"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="food" value="Food" />
                    <jet-input
                        id="food"
                        type="food"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.food"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="investment" value="Investment" />
                    <jet-input
                        id="investment"
                        type="investment"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.investment"
                    />
                </div>
                <!-- next  -->
                <div class="mt-4">
                    <jet-label for="other_expense" value="Other expenses" />
                    <jet-input
                        id="other_expense"
                        type="other_expense"
                        class="mt-1 block w-full border border-blue-100"
                        v-model="form.other_expense"
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
                deligate_fee: 0.0,
                sponser_fee: 0.0,
                donation: 0.0,
                salary: 0.0,
                rent: 0.0,
                software: 0.0,
                communication: 0.0,
                office_cost: 0.0,
                postage: 0.0,
                bank_charge: 0.0,
                election_cost: 0.0,
                equipment: 0.0,
                vechicle: 0.0,
                website: 0.0,
                consulting_charge: 0.0,
                training_charge: 0.0,
                insurance_charge: 0.0,
                guest_invitation: 0.0,
                tax_charge: 0.0,
                drink: 0.0,
                food: 0.0,
                event_cost: 0.0,
                investment: 0.0,
                other_expense: 0.0,
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("finance.outcome.submit"), {
                onFinish: () => this.form.reset(),
            });
        },
    },
};
</script>
