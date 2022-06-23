<template>
    <section
        id="modalEditOpenion"
        class="absolute mx-auto mb-2 h-full w-full rounded-lg border-2 border-green-100 shadow-lg"
    >
        <!-- {{ openion }} -->
        <form
            @submit.prevent="submit"
            class="mx-auto mb-2 flex h-full w-full flex-col rounded border-2 border-blue-200 bg-stone-100 p-2 py-4 opacity-100 shadow ring ring-lime-100 ring-offset-2"
        >
            <!-- first and middle name    -->
            <div class="bg-gray-50 pb-1">
                <input
                    type="text"
                    class="mt-1 mb-2 block min-h-fit w-full border border-gray-200 py-2 text-center text-xl font-bold"
                    id="title"
                    name="title"
                    placeholder="Title of your saying"
                    v-bind:value="openion.title"
                />
                <!-- <input
                    id="title"
                    type="text"
                    class="mt-1 mb-2 block min-h-fit w-full py-2 text-center text-xl font-bold"
                    v-bind:value="openion.title"
                    placeholder="Title of your saying"
                    @change="(e) => (form.body = e.target.value)"
                /> -->
            </div>
            <div class="grow-wrap">
                <textarea
                    id="openionBody"
                    type="text"
                    class="grow-wrap w-full break-all border border-gray-200"
                    style="min-height: 45vh"
                    placeholder="Your saying"
                    v-bind:value="br2nl(openion.body)"
                    name="body"
                    required
                ></textarea>
                <!-- @change="growWithInput" -->
                <!-- @change="(e) => (form.body = e.target.value)" -->
            </div>
            <!-- This is for has tag  -->
            <div class="pb-2">
                <input
                    id="hash_tag"
                    class="mt-1 block w-full border border-gray-200"
                    type="text"
                    placeholder="Hash tags comma seperated"
                    :value="openion.hash_tag"
                    name="hash_tag"
                />
                <!-- <input
                    id="hash_tag"
                    type="text"
                    class="mt-1 block w-full"
                    v-bind:value="openion.hash_tag"
                    placeholder="Hash tags comma seperated"
                /> -->
            </div>
            <div class="mt-2 flex flex-row items-center justify-around">
                <jet-button
                    type="submit"
                    class="mt-1 bg-blue-500 text-center"
                    :class="{ 'opacity-25': form.processing }"
                >
                    <span class="mx-auto max-w-xs py-1 px-4"> save</span>
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
import { reactive } from "@vue/reactivity";
import { Inertia } from "@inertiajs/inertia";
import { watchEffect } from "@vue/runtime-core";
import { getCurrentInstance } from "vue";
export default {
    components: {
        JetButton,
    },
    props: {
        authUser: Object,
        openion: Array,
        isLoggedIn: Boolean,
    },
    setup(props, context) {
        // console.log(props);
        const form = reactive({
            openion: props.openion,
            authUser: props.authUser,
        });
        function submit(event) {
            /**define emits inside setup
             *
             */
            // const { emit } = getCurrentInstance();
            const { title, body, hash_tag } = Object.fromEntries(
                new FormData(event.target)
            );
            (form.openion.title = title), (form.openion.body = body);
            form.openion.hash_tag = hash_tag;
            // console.log(form);
            context.emit("closeEditModal");
            Inertia.post("/openion/update", form);
        }
        // watchEffect(() => console.log(form));
        return { form, submit };
    },

    data() {
        return {
            form: this.$inertia.form({
                openion: {
                    id: "",
                    title: "",
                    body: "",
                    hash_tag: "",
                },
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
            console.log(this.form);
            this.$emit("submitModal");
        },
        growWithInput(e) {
            this.form.body = e.target.value;
            const growers = document.querySelectorAll(".grow-wrap");
            growers.forEach((grower) => {
                const textarea = grower.querySelector("textarea");
                textarea.addEventListener("input", () => {
                    grower.dataset.replicatedValue = textarea.value;
                });
            });
        },
        decodeHtml(str) {
            var map = {
                "&amp;": "&",
                "&lt;": " <",
                "&gt;": "> ",
                "&quot;": '"',
                "&#039;": "'",
            };
            return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function (m) {
                return map[m];
            });
        },
        br2nl(str) {
            // let _str = this.decodeHtml(str);
            let _str = str.replace(/^\s+|\s+$/gm, "");
            _str = this.decodeHtml(_str);
            // $breaks = array("<br />", "<br>", "<br/>");
            // _str = str_ireplace($breaks, "\r\n", _str);
            _str = _str.replace("<br>", "\r\n");
            _str = _str.replace("<br/ >", "\r\n");
            _str = _str.replace("<br />", "\r\n");
            _str = _str.replace("<br/>", "\r\n");
            _str = _str.replace("<br/ >", "\r\n");
            _str = _str.replace(" <br/ > ", "\r\n");
            return _str;
        },
    },
};
</script>
<style></style>
