<template>
    <social-layout>
        <!-- {{ income }} -->
        <div class="flex w-full flex-col">
            <p class="mx-auto py-2 text-2xl">Outcome Sheet</p>

            <outcome-submit :income="outcome"> </outcome-submit>
            <div class="flex flex-col justify-center bg-blue-50">
                <div class="m-auto">
                    <jet-validation-errors class="mx-auto mb-4 text-center" />
                </div>

                <form @submit.prevent="submit" class="mx-auto text-center">
                    <div class="m-2 flex flex-col justify-center px-2">
                        <div
                            class="m-auto mb-2 flex flex-col items-center justify-center space-x-4 p-4 font-bold text-gray-900"
                        >
                            <label for="verified" class="mb-3 px-4 py-2">
                                <p class="tex-xl font-bold text-gray-900">
                                    Verify your SubmissionConfomration
                                </p>
                                <p class="text-gray-800">
                                    Please check the Income sheet what you have
                                    submitted. Finally conform it by clicking on
                                    the submit button. You can go back and
                                    correct your submission.
                                </p>
                            </label>
                        </div>

                        <jet-button
                            class="mx-4 w-full text-center"
                            :class="{ 'opacity-25': processed }"
                            :disabled="processed"
                        >
                            <span class="mx-auto"> Submit </span>
                        </jet-button>
                    </div>
                </form>
            </div>
        </div>
    </social-layout>
</template>

<script>
import SocialLayout from "@/Layouts/SocialLayout";
import JetButton from "@/Jetstream/Button";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import OutcomeSubmit from "@/Pages/Finance/Outcome/OutcomeSubmit.vue";

export default {
    props: {
        outcome: Array,
    },
    components: {
        SocialLayout,
        JetButton,
        JetValidationErrors,
        OutcomeSubmit,
    },

    data() {
        return {
            processed: false,
            form: this.$inertia.form({
                verified: true,
            }),
        };
    },

    methods: {
        submit() {
            this.processed = true;
            this.form.post(this.route("finance.outcome.store"), {
                onFinish: () => this.form.reset(),
            });
        },
    },
};
</script>
