<template>
    <section class="mx-auto max-w-xl">
        <form @submit.prevent="submit" class="flex flex-col justify-center">
            <!-- first and middle name    -->
            <div class="pb-1">
                <jet-input
                    id="title"
                    type="text"
                    class="mt-1 block w-full text-center text-xl font-bold"
                    v-model="form.title"
                    autofocus
                    placeholder="Title of your saying"
                    autocomplete="title"
                />
            </div>
            <div class="grow-wrap pb-1">
                <textarea
                    class="border border-gray-100"
                    name="text"
                    id="text"
                    placeholder="Your saying"
                    v-model="form.body"
                    required
                    @input="growWithInput"
                ></textarea>
            </div>
            <jet-button
                class="mt-1 text-center"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                <span class="mx-auto"> Post your openion</span>
            </jet-button>
        </form>
    </section>
</template>
<script>
import NrnaLayout from "@/Layouts/LoginLayout";
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    components: {
        NrnaLayout,
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
                title: "",
                body: "",
                processing: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("openions.store"), {
                // onFinish: () =>
                //     this.form.reset("password", "password_confirmation"),
            });
        },
        growWithInput() {
            // this.parentNode.dataset.replicatedValue = this.value;
            //start
            const growers = document.querySelectorAll(".grow-wrap");
            growers.forEach((grower) => {
                const textarea = grower.querySelector("textarea");
                textarea.addEventListener("input", () => {
                    grower.dataset.replicatedValue = textarea.value;
                });
            });
        },
    },
};
</script>
<style scoped>
.grow-wrap {
    /* easy way to plop the elements on top of each other and have them both sized based on the tallest one's height */
    display: grid;
}
.grow-wrap::after {
    /* Note the weird space! Needed to preventy jumpy behavior */
    content: attr(data-replicated-value) " ";

    /* This is how textarea text behaves */
    white-space: pre-wrap;

    /* Hidden from view, clicks, and screen readers */
    visibility: hidden;
}
.grow-wrap > textarea {
    /* You could leave this, but after a user resizes, then it ruins the auto sizing */
    resize: none;

    /* Firefox shows scrollbar on growth, you can hide like this. */
    overflow: hidden;
}
.grow-wrap > textarea,
.grow-wrap::after {
    /* Identical styling required!! */
    border: 1px solid rgb(145, 192, 240);
    padding: 0.5rem;
    margin-top: 0.5rem;
    font: inherit;

    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1),
        0 4px 6px -4px rgb(0 0 0 / 0.1);

    /* Place on top of each other */
    grid-area: 1 / 1 / 2 / 2;
}
textarea:focus {
    background: rgb(253, 248, 248);
    border-color: rgb(165 180 252);
}
body {
    margin: 2rem;
    font: 1rem/1.4 system-ui, sans-serif;
}

label {
    display: block;
}
</style>
