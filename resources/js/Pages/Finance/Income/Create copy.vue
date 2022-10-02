<template>
    <login-layout>
        <div class="mt-10 flex min-h-screen flex-col justify-center">
            <form @submit.prevent="submit" class="mx-auto text-center">
                <div class="m-2 flex flex-col justify-center px-2">
                    <div
                        class="m-auto mb-2 flex flex-col items-center justify-center space-x-4 p-4 font-bold text-gray-900"
                    >
                        <label for="voting_code" class="mb-3 px-4 py-2">
                            <p>Your voting code is: 1234</p>
                            <p>Please enter your voting Code.</p>
                            <p>
                                यहाँलाई पाउनु भएको यो भोटिङ कोड तलको खाली ठाउमा
                                भरेर वटन थिच्नु होला ।
                            </p>
                        </label>
                        <input
                            class="w-96 rounded-lg border border-blue-400 bg-gray-200 px-4 py-6 text-xl font-bold font-bold text-gray-900"
                            id="voting_id"
                            placeholder="PLEASE ENTER HERE YOUR VOTING CODE"
                            v-model="form.voting_code"
                        />
                    </div>
                    <div class="mx-auto my-4 w-full">
                        <button
                            type="submit"
                            class="m-2 mx-auto w-96 rounded-lg bg-blue-300 px-2 py-4 font-bold text-gray-900"
                        >
                            SEND CODE TO GET VOTING FORM
                        </button>
                    </div>
                    <div class="mx-auto text-center">
                        <jet-validation-errors
                            class="mx-auto mb-4 text-center"
                        />
                    </div>
                </div>
            </form>
        </div>
    </login-layout>
</template>
<script>
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

import LoginLayout from "@/Layouts/LoginLayout";
export default {
    components: {
        LoginLayout,

        JetValidationErrors,
    },
    props: {
        name: String,
        nrna_id: String,
        state: String,
    },
    setup() {
        const form = useForm({
            voting_code: "",
        });
        // this.$inertia.post(route('candidacy.store'), data);
        function submit() {
            // console.log(this.voting_code);
            form.post("/deligatecodes");
        }

        return { form, submit };
    },
};
</script>
