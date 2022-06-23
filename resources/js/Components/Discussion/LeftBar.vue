<template>
    <div class="px-1">
        <ul>
            <li>
                <!-- <li class="mb-4 block text-lg font-bold">
                <a href="/dashboard" title="NRNA Europe"> Home</a>
            </li> -->
                <!-- <li class="mb-4 block text-lg font-bold">
                <a href="/dashboard" title="NRNA Europe"> profile</a>
            </li> -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- {{ $props.user }} -->
                    <jet-nav-link :href="route('dashboard')">
                        <span class="p-2 px-4 text-lg font-bold">
                            Home<br />
                            ड्यासबोर्ड
                        </span>
                    </jet-nav-link>
                </div>
            </li>
            <!-- organisation structure  -->
            <li>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <!-- {{ $props.user }} -->
                    <jet-nav-link :href="route('dashboard')">
                        <span class="p-2 px-4 text-lg font-bold">
                            Organisational Structure<br />
                            संस्थागत संरचना
                        </span>
                    </jet-nav-link>
                </div>
            </li>
            <!-- personal page  -->
            <li>
                <div v-if="$page.props.user" class="sm:-my-px sm:ml-10 sm:flex">
                    <!-- {{ $props.user }} -->
                    <jet-nav-link
                        :href="
                            route('user.show', {
                                profile: $page.props.user.user_id,
                            })
                        "
                    >
                        <span class="p-2 px-4 text-lg font-bold">
                            Personal Profile<br />
                            व्यक्तिगत बिवरण
                        </span>
                    </jet-nav-link>
                </div>
            </li>
            <li>
                <div v-if="$page.props.user" class="sm:-my-px sm:ml-10 sm:flex">
                    <!-- {{ $props.user }} -->
                    <jet-nav-link :href="route('election.dashboard')">
                        <span class="p-2 px-4 text-lg font-bold">
                            Election Dashboard<br />
                            निर्वाचन पाना
                        </span>
                    </jet-nav-link>
                </div>
            </li>

            <li>
                <div v-if="$page.props.user" class="sm:-my-px sm:ml-10 sm:flex">
                    <!-- {{ $props.user }} -->
                    <jet-nav-link :href="route('activities')">
                        <span class="p-2 px-4 text-lg font-bold">
                            NRNA Activities <br />
                            संस्थाका गतिबिधीहरू
                        </span>
                    </jet-nav-link>
                </div>
            </li>

            <li>
                <div v-if="$page.props.user" class="sm:-my-px sm:ml-10 sm:flex">
                    <!-- {{ $props.user }} -->
                    <jet-nav-link :href="route('calendar')">
                        <span class="p-2 px-4 text-lg font-bold">
                            Coming Activities <br />
                            आगामी कार्ययोजनाहरू
                        </span>
                    </jet-nav-link>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import JetNavLink from "@/Jetstream/NavLink";
export default {
    components: {
        JetNavLink,
    },
    props: {},
};
</script>
