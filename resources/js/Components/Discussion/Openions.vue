<template>
    <div class="mx-auto mt-4 w-full py-2">
        <div
            v-for="openion in openions"
            class="my-2 rounded-md border p-2 shadow-md"
        >
            <!-- {{ openions }} -->
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

            <!-- {{ openion }} -->
        </div>
    </div>
</template>
<script>
import axios from "axios";
import ActionOnMessage from "@/Shared/ActionOnMessage.vue";

export default {
    components: {
        ActionOnMessage,
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
    },
};
</script>
