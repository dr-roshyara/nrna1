<template>
    <nrna-layout>
        <div class="mt-6 text-center">
            <div v-if="has_voted" class="m-auto bg-blue-200 py-4 text-center">
                <p class="m-auto text-sm font-bold text-blue-700">
                    Congratulation {{ user_name }}!
                </p>
                <p class="m-auto">
                    Thank You for your vote. If you want to check your vote
                    again, please submit the
                    <span class="text-lg font-bold text-gray-900">
                        code to check your vote.</span
                    >
                    Please keep your passowrd very secret. Also please do not
                    ask others to show their vote.
                </p>
                <p>
                    यहाँले मतदान गर्नु भएकोमा धेरै धन्यवाद। आफ्नो मतलाई गोप्य
                    राख्नु यहाँको कर्तब्य हो । यसैले कृपया आफ्नो पासवर्ड अरुलाई
                    नदिनु होला ।
                </p>
            </div>

            <!-- Here comes the result   -->
            <div v-if="verify_final_vote">
                <div v-if="vote.no_vote_option" class="mx-auto mt-10 p-2">
                    <p class="text-xl font-bold text-gray-900">
                        You have used
                        <span class="text-red-600"> your right to reject </span>
                        all candidates.
                    </p>
                </div>
                <div v-else class="flex flex-col justify-center py-4 px-6">
                    <!--  here we put the candidate -->
                    <p
                        class="mx-auto px-2 py-2 text-xl font-bold text-gray-900"
                    >
                        Your Vote
                    </p>

                    <vote-final :vote="vote"> </vote-final>
                </div>
            </div>
            <!-- end of list -->
        </div>
    </nrna-layout>
</template>
<script>
import NrnaLayout from "@/Layouts/ElectionLayout.vue";
import VoteFinal from "@/Pages/Vote/VoteFinal";

export default {
    components: {
        NrnaLayout,
        VoteFinal,
    },
    props: {
        vote: Object,
        has_voted: Boolean,
        verify_final_vote: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
