<template>
    <nrna-layout>
        <app-layout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            </template>

            <div class="mt-1">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div
                        class="overflow-hidden bg-white p-8 shadow-xl sm:rounded-lg"
                    >
                        <!-- <welcome /> -->
                        <!-- <p> Here we are </p>  -->
                        <div class="w-full">
                            <a href="code/create">
                                <div
                                    class="my-4 mx-0 flex w-full flex-wrap justify-center rounded-lg border-2 bg-blue-300 p-8 hover:bg-blue-100"
                                >
                                    <img
                                        src="/storage/images/ballot.png"
                                        alt="Vote Here"
                                        class="my-4 h-auto w-20"
                                    />
                                    <div
                                        class="w-full text-center text-base font-bold md:text-2xl"
                                    >
                                        <p class="mx-auto mb-1">Vote Here</p>
                                        <p class="mx-auto mb-2 py-2">
                                            नमुना मतदान गर्न यहाँ क्लिक
                                            गर्नुहोस।
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- Election post    -->
                        <div class="flex w-full flex-wrap">
                            <div class="w-full pl-0 md:w-1/2 md:pl-2">
                                <a href="posts/index">
                                    <div
                                        class="my-4 ml-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:ml-2"
                                    >
                                        <img
                                            src="/storage/images/list.png"
                                            alt="Candidacy Form"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>List of Candidacy Posts</p>
                                            <p class="mx-auto mb-2 py-2">
                                                उमेद्वारीका पदहरू
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <!-- Here comes the second  -->
                            <div class="w-full pl-0 md:w-1/2 md:pl-2">
                                <a href="election/result">
                                    <div
                                        class="my-4 ml-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:ml-2"
                                    >
                                        <img
                                            src="/storage/images/result.png"
                                            alt="Election Result"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>Election Result</p>
                                            <p>मत परिणाम</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- end of elction posts  -->

                        <!-- Here starts teh candidacy list  -->
                        <div class="flex w-full flex-wrap">
                            <div class="w-full md:w-1/2">
                                <a href="candidacies/index">
                                    <div
                                        class="my-4 mr-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:mr-2"
                                    >
                                        <img
                                            src="/storage/images/list.png"
                                            alt="Candidacy List"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>Candidacy List</p>
                                            <p class="mx-auto mb-2 py-2">
                                                उमेद्ववारहरूको नामावली
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="w-full pl-0 md:w-1/2 md:pl-2">
                                <a href="candidacy/create">
                                    <div
                                        class="my-4 ml-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:ml-2"
                                    >
                                        <img
                                            src="/storage/images/form.png"
                                            alt="Candidacy Form"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>Candidacy Form</p>
                                            <p class="mx-auto mb-2 py-2">
                                                उमेदवारी फराम
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="flex w-full flex-wrap">
                            <div class="w-full md:w-1/2">
                                <a href="election/committee">
                                    <div
                                        class="my-4 mr-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:mr-2"
                                    >
                                        <img
                                            src="/storage/images/committee.jpg"
                                            alt="Election Committee"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>Election Committee</p>
                                            <p class="mx-auto mb-2 py-2">
                                                निर्वाचन कमिटी
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="flex w-full flex-wrap">
                            <div class="w-full md:w-1/2">
                                <a href="voters/index">
                                    <div
                                        class="my-4 mr-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:mr-2"
                                    >
                                        <img
                                            src="/storage/images/list.png"
                                            alt="Voter List"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>Voter List</p>
                                            <p class="mx-auto mb-2 py-2">
                                                मतदाताहरूको नामावली
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="w-full pl-0 md:w-1/2 md:pl-2">
                                <a href="vote/verify_to_show">
                                    <div
                                        class="my-4 ml-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:ml-2"
                                    >
                                        <img
                                            src="/storage/images/ballot.png"
                                            alt="Your Vote"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>Your Vote</p>
                                            <p class="mx-auto mb-2 py-2">
                                                तपाइको मत
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="flex w-full flex-wrap">
                            <div class="w-full md:w-1/2">
                                <a href="#">
                                    <div
                                        class="my-4 mr-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:mr-2"
                                    >
                                        <img
                                            src="/storage/images/information.png"
                                            alt="Information"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            <p>General Information</p>
                                            <p class="mx-auto mb-2 py-2"></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="w-full md:w-1/2">
                                <a href="users/index">
                                    <div
                                        class="my-4 mr-0 flex w-full flex-wrap justify-center rounded-lg border-2 p-8 hover:bg-blue-100 md:mr-2"
                                    >
                                        <img
                                            src="/storage/images/list.png"
                                            alt="NRNA Members List"
                                            class="my-4 h-auto w-20"
                                        />
                                        <div
                                            class="w-full text-center text-base font-bold md:text-2xl"
                                        >
                                            NRNA Member's List
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="hidden">
                            <ol class="text-bold p-4 text-lg">
                                <li class="mb-2 bg-blue-500 py-2 text-gray-900">
                                    2.
                                    <a href="users/index">Go to Voter List</a>
                                </li>
                                <li class="mb-2 bg-blue-500 py-2 text-gray-900">
                                    3.
                                    <a href="candidacy/create">
                                        Go to Candidacy Form</a
                                    >
                                </li>
                                <li>
                                    4.
                                    <a href="candidacies/index"
                                        >Go to Candidacy List</a
                                    >
                                </li>
                                <li class="mb-2 bg-blue-500 py-2 text-gray-900">
                                    5.
                                    <a href="messages/index"> Go to SMS List</a>
                                </li>
                                <li>
                                    6.
                                    <a href="election/committee">
                                        Go to Election committee
                                    </a>
                                </li>
                                <li>
                                    7.
                                    <a href="election/result">
                                        Go to Election Result</a
                                    >
                                </li>
                                <li>
                                    8.
                                    <a href="vote/show">
                                        Go to Your Vote Whta you have given
                                    </a>
                                </li>
                                <li>
                                    9.
                                    <a href="votes/index">
                                        Go to List of All votes</a
                                    >
                                </li>
                                <li>
                                    10.
                                    <a href=""> Go to General Information </a>
                                </li>
                                <li>
                                    11.
                                    <a href="user/profile">
                                        Go To Your personal profile
                                    </a>
                                </li>
                                <li>
                                    12.
                                    <a href="election/index">
                                        Election menefesto profile
                                    </a>
                                </li>
                                <li>
                                    13.
                                    <a href="finance/index">
                                        Election Fianance Expenditure
                                    </a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </app-layout>
    </nrna-layout>
</template>

<script>
import NrnaLayout from "@/Layouts/NrnaLayout";
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";

export default {
    components: {
        NrnaLayout,
        AppLayout,
        Welcome,
    },
};
</script>
