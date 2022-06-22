<template>
    {{ openion }}
    <section
        class="mx-auto w-full rounded-lg border-2 border-red-50 bg-slate-50 p-2 shadow"
    >
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
            <div class="grow-wrap">
                <textarea
                    class="grow-wrap border border-gray-100"
                    name="text"
                    id="text"
                    placeholder="Your saying"
                    v-model="form.body"
                    required
                    @input="growWithInput"
                ></textarea>
            </div>
            <!-- This is for has tag  -->
            <div class="pb-1">
                <jet-input
                    id="hash_tag"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.hash_tag"
                    autofocus
                    placeholder="Put hash tags here"
                    autocomplete="hash_tag"
                />
            </div>
            <div class="grid grid-cols-2 items-center">
                <img
                    :src="user.profile_icon_photo_path"
                    :alt="user.name"
                    height="50"
                    width="50"
                    class="rounded-full"
                />
                <jet-button
                    class="mt-1 text-center"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <span class="mx-auto max-w-xs py-1">
                        Post your openion</span
                    >
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
    },
};
</script>
