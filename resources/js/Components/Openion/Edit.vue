<template>
    <section
        id="modalEditOpenion"
        class="absolute mx-auto h-full w-full rounded-lg border-2 border-green-50 p-2 shadow"
    >
        <!-- {{ openion }} -->
        <form
            @submit.prevent="submit"
            class="mx-auto flex h-full w-full flex-col bg-stone-100 px-2 py-2 opacity-100"
        >
            <!-- first and middle name    -->
            <div class="bg-gray-50 pb-1">
                <input
                    id="title"
                    type="text"
                    class="mt-1 mb-2 block min-h-fit w-full py-2 text-center text-xl font-bold"
                    v-bind:value="openion.title"
                    @change="(e) => (form.body = e.target.value)"
                />
            </div>
            <div class="grow-wrap">
                <textarea
                    id="openionBody"
                    class="grow-wrap w-full border border-gray-100"
                    style="min-height: 300px"
                    placeholder="Your saying"
                    v-bind:value="openion.body"
                    @change="(e) => (form.body = e.target.value)"
                    @input="growWithInput"
                    required
                ></textarea>
            </div>
            <!-- This is for has tag  -->
            <div class="pb-1">
                <input
                    id="hash_tag"
                    type="text"
                    class="mt-1 block w-full"
                    v-bind:value="openion.hash_tag"
                />
            </div>
            <div class="flex flex-row items-center justify-around">
                <jet-button
                    class="mt-1 text-center"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submitModal"
                >
                    <span class="mx-auto max-w-xs py-1"> save</span>
                </jet-button>
                <jet-button
                    class="mt-1 bg-red-300 text-center"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="closeEditModal"
                >
                    <span class="mx-auto max-w-xs py-1"> Cancle</span>
                </jet-button>
            </div>
        </form>
    </section>
</template>
<script>
import JetButton from "@/Jetstream/FormButton";
export default {
    components: {
        JetButton,
    },
    props: {
        authUser: Object,
        isLoggedIn: Boolean,
        openion: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                openion: {},
            }),
        };
    },
    methods: {
        editOpenion(openion) {
            this.form.openion = openion;
            this.form.post(this.route("openion.update"));
        },
        closeEditModal() {
            this.$emit("closeEditModal");
        },
        submitModal() {
            this.$emit("submitModal");
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
<style></style>
