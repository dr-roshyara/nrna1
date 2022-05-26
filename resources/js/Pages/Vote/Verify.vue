<template>
    <election-layout>
        <div class="mx-2 flex flex-col md:mx-auto md:flex-row md:space-x-6">
            <div class="mt-5 flex flex-col bg-blue-100 px-2 align-top md:mx-2">
                <div class="mx-auto">
                    <jet-validation-errors class="mx-auto mb-4 text-center" />
                </div>
                <!-- {{vote}} -->
                <!-- {{code_expires_in}} -->
                <div class="flex flex-col px-2">
                    <p
                        class="mx-auto my-2 p-2 text-center text-2xl font-bold text-gray-900"
                    >
                        Check your email & submit the vote conformation code
                    </p>
                    <p>
                        First of all, please check below the vote what you have
                        casted. Finally conform it by inserting the code you got
                        sencod time in your email. We have just sent you an
                        email
                        <span class="font-bold text-red-500">
                            {{ totalDuration }}
                        </span>
                        minutes ago, mentioning your vote conformation code. You
                        can use this code for the next
                        <span class="font-bold text-red-500">
                            {{ code_expires_in - totalDuration }} minutes
                        </span>
                        to save your vote.<br />
                    </p>
                    <p class="my-1 py-2">
                        यहाँले गर्नु भएको मतदान यो पेजमा देखाइएको छ। सवै भन्दा
                        पहिला आफ्नो मतदान लाइ जाँच गर्नुहोस। अनि तपाइलाइ हामीले
                        भर्खरै
                        <span class="font-bold text-red-500">
                            {{ totalDuration }} मिनेट
                        </span>
                        अघाडी एउटा इमेल पठाएका छाैं। आफ्नो मतदान सेभ गर्नको लागि
                        त्यो कोड लाइ अर्काे
                        <span class="font-bold text-red-500">
                            {{ code_expires_in - totalDuration }}
                        </span>
                        मिनेट सम्म प्रयाेग गरिसक्नु पर्ने छ। अब यहाँले प्राप्त
                        गर्नु भएको मतदान पुष्टी कोड हालेर आफ्नो मतदान लाई सेभ
                        गर्न तलको बट्टन थिच्नुहोस्।
                    </p>
                </div>
                <form @submit.prevent="submit" class="align-top">
                    <div class="flex flex-col justify-center px-2">
                        <div class="mb-2 p-4 text-gray-900">
                            <label
                                for="voting_code"
                                class="mb-3 px-4 py-2 text-xl font-bold text-gray-900"
                            >
                                <p class="text-center">
                                    Vote Conformation Code
                                </p>
                            </label>
                            <input
                                class="w-full rounded-lg border border-blue-400 bg-gray-200 px-4 py-6 text-xl font-bold font-bold text-gray-900"
                                id="voting_id"
                                placeholder="PLEASE ENTER HERE YOUR VOTING CODE"
                                v-model="form.voting_code"
                            />
                        </div>
                        <div class="my-4 w-full">
                            <button
                                type="submit"
                                class="w-full rounded-lg bg-blue-300 px-4 py-4 font-extrabold text-gray-900"
                            >
                                PLEASE CLICK HERE & SAVE YOUR VOTE
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
            <div class="mt-5 ml-2 flex w-full flex-col bg-gray-50 px-2">
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
                <!-- Here we display the casted vote   -->
                <vote-display :vote="vote"> </vote-display>
            </div>
        </div>
    </election-layout>
</template>
<script>
import VotedPost from "@/Shared/VotedPost";
import VoteDisplay from "@/Pages/Vote/VoteDisplay";
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import AppLayout from "@/Layouts/AppLayout";
import ElectionLayout from "@/Layouts/ElectionLayout";
export default {
    props: {
        vote: Object,
        totalDuration: Number,
        code_expires_in: Number,
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
    computed: {
        voteSubmitted() {
            return this.vote;
        },
    },
    components: {
        VotedPost,
        VoteDisplay,
        ElectionLayout,
        JetValidationErrors,
    },
    methods: {
        get_json_array() {
            Object.prototype.prettyPrint = function () {
                var jsonLine =
                    /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/gm;
                var replacer = function (match, pIndent, pKey, pVal, pEnd) {
                    var key = '<span class="json-key" style="color: brown">',
                        val = '<span class="json-value" style="color: navy">',
                        str = '<span class="json-string" style="color: olive">',
                        r = pIndent || "";
                    if (pKey)
                        r = r + key + pKey.replace(/[": ]/g, "") + "</span>: ";
                    if (pVal)
                        r = r + (pVal[0] == '"' ? str : val) + pVal + "</span>";
                    return r + (pEnd || "");
                };

                return JSON.stringify(this, null, 3)
                    .replace(/&/g, "&amp;")
                    .replace(/\\"/g, "&quot;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(jsonLine, replacer);
            };
            document.getElementById("planets").innerHTML = this.vote;
        },
    },
};
</script>
<style scoped>
body {
    background: #efefef;
}
pre {
    background-color: ghostwhite;
    border: 1px solid silver;
    padding: 10px 20px;
    margin: 20px;
    border-radius: 4px;
    width: 25%;
    margin-left: auto;
    margin-right: auto;
}
</style>
