<template>
    <election-layout>
        <div class="mx-2 mt-4 flex w-full flex-col justify-center p-2">
            <p
                class="my-2 mx-auto bg-blue-50 p-2 text-center text-2xl font-bold text-gray-900"
            >
                Check your email Now
            </p>
            <div class="md:text-center">
                <p>
                    We have sent you an email
                    <span class="font-bold text-red-500">
                        {{ code_duration }}
                    </span>
                    minutes ago, mentioning your voting code. You can use this
                    code for the next
                    <span class="font-bold text-red-500">
                        {{ code_expires_in - code_duration }} minutes
                    </span>
                    to open the voting form. If you don't see email in your
                    mailbox , then please check your Spam mail also.<br />
                </p>
                <p class="py-2">
                    तपाइलाइ हामीले भर्खरै
                    <span class="font-bold text-red-500">
                        {{ code_duration }} मिनेट
                    </span>
                    अघाडी एउटा इमेल पठाएका छाैं। भोटिङ पर्म खोल्नको लागि त्यो
                    कोड लाइ अर्काे
                    <span class="font-bold text-red-500">
                        {{ code_expires_in - code_duration }}
                    </span>
                    मिनेट सम्म प्रयाेग गरिसक्नु पर्ने छ। यो फर्मलाइ एकछिन
                    यत्तिकै राखेर अव यहाँले आफ्नो इमेलमा चेक गर्नुहोस । <br />
                    आफ्नो इमेलमा प्राप्त भएको भोटिङ कोड थाहा पाउनु हाेस र उक्त
                    भोटिङ कोड तलको खाली ठाउंमा भरेर सेन्ड वटन थिच्नु होस ।
                </p>
                <p class="text-sm font-semibold text-red-700">
                    तपाइले आफ्नो मेल वक्समा एनआरएनएको इमेल भेट्टाउनु भएन भने
                    स्पाम मेलमा गएर वसेको हुन सक्छ, त्यसैले स्पाम मेल पनि चेक
                    गर्नुहोस।
                </p>
            </div>
            <div class="m-auto">
                <jet-validation-errors class="mx-auto mb-4 text-center" />
                <!--
                  //   {{name}}
                  //  {{nrna_id}}
                  //    {{state}}
                  -->
            </div>
            <form
                @submit.prevent="submit"
                class="mx-auto mt-10 w-full text-center md:w-2/3"
            >
                <div
                    class="mx-2 flex w-full flex-col justify-center border bg-lime-50 px-2 py-4 shadow"
                >
                    <div class="mb-1 p-2 font-bold text-gray-900">
                        <label for="voting_code" class="p-2 md:p-4">
                            <p
                                class="text-center text-xl font-bold text-gray-900"
                            >
                                First voting code
                            </p>
                        </label>
                        <input
                            class="w-full rounded-lg border border-blue-400 bg-gray-200 p-2 text-xl font-bold font-bold text-gray-900 md:w-1/2 md:px-4 md:py-6"
                            id="voting_id"
                            placeholder="Write fisrt voting code"
                            v-model="form.voting_code"
                        />
                    </div>
                    <div class="my-4 p-2">
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-blue-300 py-4 font-bold text-gray-900 md:w-1/2 md:p-4"
                        >
                            PRESS HERE TO GET VOTING FORM
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
    </election-layout>
</template>
<script>
import { useForm } from "@inertiajs/inertia-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import ElectionLayout from "@/Layouts/ElectionLayout";
export default {
    props: {
        name: String,
        nrna_id: String,
        state: String,
        code_duration: Number,
        code_expires_in: Number,
    },
    setup() {
        const form = useForm({
            voting_code: "",
        });
        // this.$inertia.post(route('candidacy.store'), data);
        function submit() {
            console.log(this.voting_code);
            form.post("/codes");
        }

        return { form, submit };
    },
    components: {
        ElectionLayout,
        JetValidationErrors,
    },
};
</script>
