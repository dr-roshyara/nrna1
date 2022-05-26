<template>
    <nrna-layout>
        <div class="mt-6 text-center">
            <div
                v-if="has_voted"
                class="m-auto w-full bg-blue-200 py-4 text-center"
            >
                <p class="m-auto text-xl font-bold text-blue-700">
                    Congratulation {{ user_name }}!
                </p>
                <div class="mx-auto mb-4 w-full py-4 md:w-2/3">
                    <p class="m-auto">
                        Thank You for your vote. If you want to check your vote
                        again, please submit the
                        <span class="text-lg font-bold text-gray-900">
                            code to check your vote.</span
                        >
                        Please keep your passowrd very secret. Also please do
                        not ask others to show their vote.
                    </p>
                    <p>
                        यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई
                        गोप्य राख्नु यहाँको कर्तब्य हो । यसैले कृपया आफ्नो
                        पासवर्ड अरुलाई नदिनु होला ।
                    </p>
                </div>
                <jet-validation-errors class="mx-auto mb-4 text-center" />
            </div>
            <div
                class="m-auto my-2 flex w-full flex-col border py-2 shadow md:w-2/3"
            >
                <div
                    class="mx-auto my-2 p-2 text-center text-2xl font-bold text-gray-900"
                >
                    <p>Check your email & submit the code to check your vote</p>
                </div>

                <form @submit.prevent="submit" class="text-center align-top">
                    <div class="my-2 flex flex-col justify-center px-2">
                        <div
                            class="mb-2 flex flex-col justify-center p-4 font-bold text-gray-900"
                        >
                            <label for="voting_code" class="mb-3 px-4 py-2">
                                <p>Code to check your vote</p>
                            </label>
                            <input
                                class="mx-auto w-full rounded-lg border border-blue-400 bg-gray-200 px-4 py-6 font-bold font-bold text-gray-900 md:w-2/3"
                                id="voting_id"
                                placeholder="ENTER HERE YOUR  CODE TO CHECK THE VOTE"
                                v-model="form.voting_code"
                            />
                        </div>
                        <div class="my-4 w-full">
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-blue-300 px-4 py-4 font-extrabold text-gray-900 md:w-2/3"
                            >
                                PLEASE CLICK HERE & GET YOUR VOTE
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
        </div>
    </nrna-layout>
</template>
<script>
import NrnaLayout from "@/Layouts/ElectionLayout.vue";
import VoteFinal from "@/Pages/Vote/VoteFinal";
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
export default {
    components: {
        NrnaLayout,
        VoteFinal,
        JetValidationErrors,
    },
    props: {
        vote: Object,
        has_voted: Boolean,
        user_name: String,
    },
    setup() {
        const form = useForm({
            voting_code: "",
            // vote: this.voteSubmitted
        });

        // this.$inertia.post(route('candidacy.store'), data);
        function submit() {
            // console.log(this.voting_code);
            form.post(
                "/verify_final_vote",
                {
                    preserveScroll: true,
                },
                {
                    resetOnSuccess: true,
                }
            );
        }

        return { form, submit };
    },
    data() {
        return {
            //     has_voted: (this.vote)
        };
    },
    computed: {
        user_has_voted() {
            //     console.log(this.vote);
            // return isEmpty(this.vote)
            return true;
        },
    },
};
</script>
