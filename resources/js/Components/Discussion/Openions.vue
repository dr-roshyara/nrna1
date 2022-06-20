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
                        v-show="
                            isIconValid(openion.user.profile_icon_photo_path)
                        "
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
                <div class="mr-0" v-if="$page.props.user.id == openion.user.id">
                    <!-- Edit Icon to edit a post  -->
                    <a href="/openion/edit">
                        <svg
                            class="h-4 w-6 text-blue-500"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M12 20h9" />
                            <path
                                d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                            />
                        </svg>
                    </a>
                </div>
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

export default {
    props: {
        user: {
            type: Object,
        },
        openionRoute: {
            type: String,
            default: "/openions",
        },
    },
    watch: {},
    computed: {
        userId() {
            console.log("user");
            console.log(this.user);
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
            console.log(this.openions);
            // console.log("auth:user ");
            // console.log(this.$page.props.user);
            // console.log(this.$page.user.id);
        });
    },
    methods: {
        isIconValid(imagePath) {
            console.log(imagePath);
            if (imagePath == null) {
                return false;
            }
            if (imagePath == undefined) {
                return false;
            }

            // if (imagePath != "") {
            //     return true;
            // }
            return true;
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
            let _difference = _date.getTime() - _today.getTime();
            let _days = Math.ceil(_difference / (1000 * 3600 * 24));
            let _newDate = null;
            console.log(_days);
            if (_days == 0) {
                _newDate =
                    "Today at " + _date.getHours() + ":" + _date.getMinutes();
                return _newDate;
                console.log(_newDate);
            }
            if (_days == 1) {
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
