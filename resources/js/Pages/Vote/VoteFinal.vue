<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div class="flex flex-col">
        <!-- {{vote}} -->

        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
                class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
            >
                <div
                    class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
                >
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 font-bold text-gray-900">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium font-bold uppercase tracking-wider text-gray-900"
                                >
                                    <span class="ml-10"> Name</span>
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium font-bold uppercase tracking-wider text-gray-900"
                                >
                                    Designation
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium font-bold uppercase tracking-wider text-gray-900"
                                >
                                    Regions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template
                                v-for="(candidate, vtIndex) in vote"
                                :key="vtIndex"
                            >
                                <tr
                                    v-if="JSON.parse(candidate).candidates"
                                    v-for="(candiVec, canIndx) in JSON.parse(
                                        candidate
                                    ).candidates"
                                    :key="canIndx"
                                >
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-12 w-16 flex-shrink-0"
                                            >
                                                <img
                                                    class="h-16 w-16 rounded-full"
                                                    :src="
                                                        full_image_name(
                                                            candiVec.image_path_1
                                                        )
                                                    "
                                                    alt="Candidate Election Nrna"
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-bold text-gray-900"
                                                >
                                                    {{ candiVec.user.name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    Candidacy Id:
                                                    {{ candiVec.candidacy_id }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    User Id:
                                                    {{ candiVec.user_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-left"
                                    >
                                        <!-- second column  -->
                                        <div class="text-sm text-gray-900">
                                            {{ post_name(candidate) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            post ID: {{ post_id(candidate) }}
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-left"
                                    >
                                        <!-- {{candiVote.user.region }}  -->
                                        {{ region(candidate) }}
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        vote: Object,
    },
    computed: {},
    data() {
        return {
            image_path: "/storage/images/",
            //   imagename: this.image_path+ this.candidacy_image_path
        };
    },
    methods: {
        full_image_name(imagename) {
            return this.image_path + imagename;
        },
        post_name(candidate) {
            return JSON.parse(candidate).post_name;
        },

        post_id(candidate) {
            return JSON.parse(candidate).post_id;
        },
        region(candidate) {
            return JSON.parse(candidate).region;
        },
        full_image_name(imagename) {
            // let imagename =JSON.parse(candiVec).image_path_1;
            return this.image_path + imagename;
        },
    },
};
</script>
