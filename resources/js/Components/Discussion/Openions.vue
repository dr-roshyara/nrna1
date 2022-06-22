<template>
    <div class="mt-4 w-full p-2">
        <div
            v-for="(openion, openionKey) in openions"
            :key="openionKey"
            class="my-2 rounded-md border p-2 shadow-md"
        >
            key: {{ openionKey }}
            <div
                class="flex flex-row justify-between border-b border-slate-100 pt-1 font-bold"
            >
                <div class="flex flex-row items-center justify-start">
                    <div
                        v-if="isIconValid(openion.user.profile_icon_photo_path)"
                    >
                        <img
                            :src="openion.user.profile_icon_photo_path"
                            :alt="openion.user.name"
                            height="40"
                            width="40"
                            class="mr-2 rounded-full"
                        />
                    </div>
                    <div class="grid grid-cols-1">
                        <a
                            class="text-blue-800"
                            :href="'/user/' + openion.user.user_id"
                            >{{ openion.user.name }} says ..</a
                        >

                        <span class="text-sm tracking-tighter text-gray-600"
                            >{{ getUpdatedAt(openion.updated_at) }}
                        </span>
                    </div>
                </div>
                <action-on-message
                    :user="openion.user"
                    :userLoggedIn="canEdit(openion.user.id)"
                    @editOpenion="editOpenion(openion, openionKey)"
                    @deleteOpenion="deleteOpenion(openion)"
                ></action-on-message>
            </div>
            <p class="px-1 pb-1 text-center font-bold text-blue-800">
                {{ openion.title }}
            </p>
            <div class="mb-4 pb-2" v-html="openion.body"></div>
            <p
                v-if="openion.hash_tag"
                class="bottom-0 ml-0 text-sm font-bold tracking-tighter text-teal-800"
            >
                #{{ openion.hash_tag }}
            </p>

            <div
                :id="openionKey + '_' + openion.id"
                ref="openionKey+' '+ openion.id"
                class="translate-y-50 relative top-0 bottom-0 left-0 right-0 hidden min-h-screen bg-slate-300"
                v-show="show_edit_modal"
            >
                <openion-edit
                    :openion="form.openion"
                    @closeEditModal="closeEditModal"
                    @submitModal="submitModal"
                    class="mx-auto h-full w-full bg-blue-200"
                ></openion-edit>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import ActionOnMessage from "@/Shared/ActionOnMessage.vue";
import OpenionEdit from "@/Components/Openion/Edit.vue";
export default {
    components: {
        ActionOnMessage,
        OpenionEdit,
    },
    props: {
        user: {
            type: Object,
        },
        authUser: {
            type: Array,
        },
        userLoggedIn: {
            type: Boolean,
            default: false,
        },
        openionRoute: {
            type: String,
            default: "/openions",
        },
    },
    watch: {},
    computed: {
        userId() {
            // console.log("user");
            // console.log(this.user);
            return this.user.id;
        },
    },

    data() {
        return {
            openions: {},
            bgUrlValid: true,
            iconUrlValid: true,
            show_edit_modal: false,
            form: this.$inertia.form({
                openion: {},
                openionKey: "",
            }),
        };
    },
    mounted() {
        // const token = localStorage.getItem("test_token");
        // const headers = {
        //     headers: {
        //         Authorization: `Bearer ${token}`,
        //         Accept: "application/json",
        //     },
        // };
        axios.get(this.openionRoute, {}).then((response) => {
            // console.log("response");
            // console.log(response);
            this.openions = response.data;
            // // console.log("user");
            // console.log(this.openions);
            // console.log("auth:user ");
            // console.log(this.$page.props.user);
            // console.log(this.$page.user.id);
        });
    },
    methods: {
        canEdit(userId) {
            let canEdit = false;
            if (this.userLoggedIn) {
                canEdit = true;
                return true;
            } else {
                // console.log(this.authUser);
                if (this.authUser != undefined) {
                    if (this.authUser.id === userId) {
                        canEdit = true;
                    }
                }
            }
            return canEdit;
        },
        isIconValid(imagePath) {
            // console.log(imagePath);
            if (typeof imagePath === "undefined") {
                return false;
            } else {
                if (imagePath != "") {
                    return true;
                }
            }
            if (imagePath === null) {
                return false;
            }
            if (imagePath === undefined) {
                return false;
            }

            return false;
        },
        onBgImageError() {
            this.bgUrlValid = false;
        },
        onIconImageError() {
            this.iconUrlValid = false;
        },
        getUpdatedAt(date) {
            let _date = new Date(date);
            let _today = new Date();
            let _yesterday = new Date();
            _yesterday.setDate(_today.getDate() - 1);
            let _difference = _date.getTime() - _today.getTime();
            let _days = Math.ceil(_difference / (1000 * 3600 * 24));
            let _newDate = null;
            // console.log(_days);
            if (_date.toDateString() == _today.toDateString()) {
                _newDate =
                    "Today at " + _date.getHours() + ":" + _date.getMinutes();
                return _newDate;
                // console.log(_newDate);
            }
            if (_date.toDateString() == _yesterday.toDateString()) {
                _newDate =
                    "Yesterday at " +
                    _date.getHours() +
                    ":" +
                    _date.getMinutes();
                return _newDate;
            } else {
                _newDate = _date.toDateString();

                return _newDate;
            }

            return null;
        },
        deleteOpenion(openion) {
            this.form.openion = openion;
            this.form.post(this.route("openion.destroy"));
        },
        editOpenion(openion, openionKey) {
            // console.log(openion);
            // console.log(openionKey);
            this.form.openion = openion;
            this.form.openionKey = openionKey;
            this.form.post(this.route("openion.edit"), {
                preserveScroll: true,
                resetOnSuccess: false,
                onSuccess: (response) => this.showEditModal(response),
            });
        },
        showEditModal(response) {
            console.log(response.props.message);

            let _messgae = response.props.message;
            if (_messgae == "validation_success") {
                // console.log("test");
                let element_id =
                    this.form.openionKey + "_" + this.form.openion.id;
                console.log("element : " + element_id);
                let element = document.getElementById(element_id);
                element.classList.remove("hidden");
                element.classList.add("block");
                this.show_edit_modal = true;
                // this.$refs.$input.focus();
                element.focus();
                element.scrollIntoView({
                    behavior: "auto",
                    block: "center",
                    inline: "center",
                });
                // const element = document.getElementById("middle");
                const elementRect = element.getBoundingClientRect();
                const absoluteElementTop = elementRect.top + window.pageYOffset;
                const middle = absoluteElementTop - window.innerHeight / 2;
                window.scrollTo(0, middle);
            }
            // console.log(openion);
        },
        closeEditModal() {
            this.show_edit_modal = false;
        },
        submitModal() {
            alert("We are working on it");
            // this.form.post(this.route("openion.edit"), {
            //     preserveScroll: true,
            //     resetOnSuccess: false,
            //     onSuccess: (response) => this.showEditModal(response),
            // });
        },
        showModal(openionKey) {
            console.log(openionKey);
            // console.log(openion);
            if (!this.show_edit_modal) {
                return false;
            }
            if (this.show_edit_modal) {
                if (openionKey == this.form.openionKey) {
                    return true;
                }
            }
            return false;
        },
    },
};
</script>
