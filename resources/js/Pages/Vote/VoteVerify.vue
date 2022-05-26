<template>
    <nrna-layout>
        <div
            class="ites-center flex flex-col md:mx-auto md:flex-row md:space-x-6"
        >
            <div class="flex flex-col justify-center bg-blue-100">
                {{ vote }}
                <div class="m-auto">
                    <jet-validation-errors class="mx-auto mb-4 text-center" />
                </div>

                <form @submit.prevent="submit" class="mx-auto text-center">
                    <div class="m-2 flex flex-col justify-center px-2">
                        <div
                            class="m-auto mb-2 flex flex-col items-center justify-center space-x-4 p-4 font-bold text-gray-900"
                        >
                            <label for="voting_code" class="mb-3 px-4 py-2">
                                <p>Vote Conformation Code</p>
                                <p>
                                    Please check the vote what you have casted.
                                    Finally conform it by inserting the code you
                                    got sencod time.
                                </p>
                                <p>
                                    यहाँले गर्नु भएको मतदान यो पेजमा देखाइएको छ।
                                    साथी यहाँको एसएमएसमा पनि पठाइएको छ। अब
                                    यहाँले गर्नु भएको मतदान अली त्यो एसएमएस मा
                                    पठाइएको पुस्टी कोड हालेर आफ्नो मतदान लाई सेभ
                                    गर्न बत्तन थिच्नुहोस्।
                                </p>
                            </label>
                            <input
                                class="w-auto rounded-lg border border-blue-400 bg-gray-200 px-4 py-6 text-xl font-bold font-bold text-gray-900"
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
                                SEND CODE TO save your vote
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
            <!-- next  -->
            <div class="mt-6 ml-2 flex w-full flex-col bg-gray-50 px-2">
                <p class="mx-auto p-2 text-xl font-bold text-gray-900">
                    Your vote
                </p>

                <div v-if="no_vote_option" class="text-center">
                    You have selected a
                    <span class="font-bold text-red-800"> VOTE FOR NO ONE </span
                    >option .<br />
                    <span class="font-bold text-gray-900">
                        Please conform it
                    </span>
                </div>
                <div v-else class="mx-auto w-full">
                    <voted-post v-bind:candidate="icc_member"></voted-post>
                    <voted-post v-bind:candidate="president"></voted-post>
                    <voted-post v-bind:candidate="vp"></voted-post>
                    <voted-post
                        v-bind:candidate="general_secretary"
                    ></voted-post>
                    <voted-post v-bind:candidate="secretary"></voted-post>
                    <voted-post v-bind:candidate="treasure"></voted-post>
                    <voted-post v-bind:candidate="w_coordinator"></voted-post>
                    <voted-post v-bind:candidate="y_coordinator"></voted-post>
                    <voted-post
                        v-bind:candidate="cult_coordinator"
                    ></voted-post>
                    <voted-post
                        v-bind:candidate="child_coordinator"
                    ></voted-post>
                    <voted-post
                        v-bind:candidate="studt_coordinator"
                    ></voted-post>
                    <voted-post v-bind:candidate="member_berlin"></voted-post>
                    <voted-post v-bind:candidate="member_hamburg"></voted-post>
                    <voted-post v-bind:candidate="member_nsachsen"></voted-post>
                    <voted-post v-bind:candidate="member_nrw"></voted-post>
                    <voted-post v-bind:candidate="member_hessen"></voted-post>
                    <voted-post
                        v-bind:candidate="member_rhein_pfalz"
                    ></voted-post>
                    <voted-post v-bind:candidate="member_bayern"></voted-post>
                </div>
            </div>
        </div>
    </nrna-layout>
</template>
<script>
import VotedPost from "@/Shared/VotedPost";
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import AppLayout from "@/Layouts/AppLayout";
import NrnaLayout from "@/Layouts/ElectionLayout";
export default {
    props: {
        vote: Object,
    },
    setup() {
        const form = useForm({
            voting_code: "",
            // vote: this.voteSubmitted
        });

        // this.$inertia.post(route('candidacy.store'), data);
        function submit() {
            // console.log(this.voting_code);
            form.post("/votes");
        }

        return { form, submit };
    },
    data() {
        return {
            icc_member: this.vote[0],
            president: this.vote[1],
            vp: this.vote[2],
            wwp: this.vote[3],
            general_secretary: this.vote[4],
            secretary: this.vote[5],
            treasure: this.vote[6],
            w_coordinator: this.vote[7],
            y_coordinator: this.vote[8],
            cult_coordinator: this.vote[9],
            child_coordinator: this.vote[10],
            studt_coordinator: this.vote[11],
            member_berlin: this.vote[12],
            member_hamburg: this.vote[13],
            member_nsachsen: this.vote[14],
            member_nrw: this.vote[15],
            member_hessen: this.vote[16],
            member_rhein_pfalz: this.vote[17],
            member_bayern: this.vote[18],
            no_vote_option: this.vote[19],
        };
    },
    computed: {
        voteSubmitted() {
            return this.vote;
        },
    },
    components: {
        VotedPost,
        NrnaLayout,
        AppLayout,
        JetValidationErrors,
    },
};
</script>
