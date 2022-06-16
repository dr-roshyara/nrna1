<template>
    <div class="mx-auto mt-2 max-w-xl py-2">
        <div
            v-for="openion in openions"
            class="relative my-2 rounded-md border p-2 shadow-md"
        >
            <!-- {{ openions }} -->
            <div
                class="flex flex-row justify-between border-b border-slate-100 pt-1 font-bold"
            >
                <div>
                    <a
                        class="text-blue-800"
                        :href="'/user/' + openion.user.user_id"
                        >{{ openion.user.name }}</a
                    >
                    says...
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
                class="absolute bottom-0 ml-0 text-sm font-bold tracking-tighter text-teal-800"
            >
                #{{ openion.hash_tag }}
            </p>

            <!-- {{ openion }} -->
        </div>
    </div>
</template>
<script>
export default {
    props: {
        openionRoute: {
            type: String,
            default: "user.openions",
        },
    },
    data() {
        return {
            openions: {},
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
        axios.get(this.route(this.openionRoute)).then((response) => {
            this.openions = response.data;
            // console.log("user");
            // console.log(this.openions);
            // console.log("auth:user ");
            // console.log(this.$page.props.user);
            // console.log(this.$page.user.id);
        });
    },
    methods: {},
};
</script>
