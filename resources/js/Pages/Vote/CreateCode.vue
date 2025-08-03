<template>
    <election-layout>
        <div class="mx-2 mt-4 flex w-full flex-col justify-center p-2">
    <!-- Header - Just better styling -->
    <div class="my-4 mx-auto bg-blue-600 text-white p-4 rounded-lg text-center shadow-lg max-w-md">
        <div class="text-3xl mb-2">📧</div>
        <p class="text-xl font-bold">Check Your Email Now</p>
    </div>

    <!-- Instructions - Better organized -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 max-w-4xl mx-auto">
        <!-- English Instructions -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
            <p class="text-gray-800 leading-relaxed">
                We have sent you an email
                <span class="font-bold text-red-600 bg-red-100 px-2 py-1 rounded">
                    {{ code_duration }} minutes
                </span>
                ago, mentioning your voting code. You can use this code for the next
                <span class="font-bold text-red-600 bg-red-100 px-2 py-1 rounded">
                    {{ code_expires_in - code_duration }} minutes
                </span>
                to open the voting form. If you don't see email in your mailbox, then please check your Spam mail also.
            </p>
        </div>

        <!-- Nepali Instructions -->
        <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
            <p class="text-gray-800 leading-relaxed">
                तपाइलाइ हामीले भर्खरै
                <span class="font-bold text-red-600 bg-red-100 px-2 py-1 rounded">
                    {{ code_duration }} मिनेट
                </span>
                अघाडी एउटा इमेल पठाएका छाैं। भोटिङ पर्म खोल्नको लागि त्यो कोड लाइ अर्काे
                <span class="font-bold text-red-600 bg-red-100 px-2 py-1 rounded">
                    {{ code_expires_in - code_duration }} मिनेट
                </span>
                सम्म प्रयाेग गरिसक्नु पर्ने छ। यो फर्मलाइ एकछिन यत्तिकै राखेर अव यहाँले आफ्नो इमेलमा चेक गर्नुहोस।
            </p>
            <p class="mt-3 text-sm font-semibold text-red-700 bg-red-50 p-2 rounded">
                तपाइले आफ्नो मेल वक्समा एनआरएनएको इमेल भेट्टाउनु भएन भने स्पाम मेलमा गएर वसेको हुन सक्छ, त्यसैले स्पाम मेल पनि चेक गर्नुहोस।
            </p>
        </div>
    </div>

    <!-- Validation Errors -->
    <div class="m-auto">
        <jet-validation-errors class="mx-auto mb-4 text-center" />
    </div>

    <!-- Form - Cleaner styling -->
    <form @submit.prevent="submit" class="mx-auto mt-6 w-full text-center md:w-2/3">
        <div class="mx-2 bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-8">
            <!-- Code Input -->
            <div class="mb-6">
                <label for="voting_code" class="block mb-4">
                    <div class="flex items-center justify-center mb-2">
                        <span class="text-2xl mr-2">🔑</span>
                        <p class="text-xl font-bold text-gray-900">First Voting Code</p>
                    </div>
                </label>
                <input
                    class="w-full md:w-2/3 mx-auto block rounded-lg border-2 border-blue-300 bg-gray-50 p-4 text-xl font-bold text-gray-900 text-center focus:border-blue-500 focus:bg-white transition-all"
                    id="voting_id"
                    placeholder="Enter your voting code"
                    v-model="form.voting_code"
                />
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button
                    type="submit"
                    class="w-full md:w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg transition-colors shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                >
                    🗳️ PRESS HERE TO GET VOTING FORM
                </button>
                <p class="mt-2 text-sm text-gray-600">भोटिङ फर्म प्राप्त गर्न यहाँ थिच्नुहोस्</p>
            </div>

            <!-- Validation Errors -->
            <div class="mx-auto text-center">
                <jet-validation-errors class="mx-auto mb-4 text-center" />
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
