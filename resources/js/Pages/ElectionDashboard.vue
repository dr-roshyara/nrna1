<template>
    <nrna-layout>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8"
                >
                    <!-- <welcome /> -->
                    <!-- <p> Here we are </p>  -->
                    <div class="w-full">
                        <a href="code/create"> 
                            <div
                                class="
                                    w-full
                                    p-8
                                    my-4
                                    mx-0
                                    border-2
                                    rounded-lg
                                    hover:bg-blue-100
                                    flex flex-wrap
                                    justify-center
                                    bg-blue-300
                                "
                            >
                                <img
                                    src='/storage/images/ballot.png'
                                    alt="Vote Here"
                                    class="w-20 h-auto my-4"
                                />
                                <div
                                    class="
                                        w-full
                                        text-center
                                        font-bold
                                        text-base
                                        md:text-2xl
                                    "
                                >
                                    Vote Here
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Election post    -->
                      <div class="w-full flex flex-wrap">
                      
                        <div class="w-full md:w-1/2 pl-0 md:pl-2">
                            <a href="posts/index">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        ml-0
                                        md:ml-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/form.png"
                                        alt="Candidacy Form"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                         List of Candidacy Posts 
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                     <!-- end of elction posts  -->
                     
                     <!-- Here starts teh candidacy list  -->
                    <div class="w-full flex flex-wrap">
                        <div class="w-full md:w-1/2">
                            <a href="candidacies/index">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        mr-0
                                        md:mr-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src='/storage/images/list.png'
                                        alt="Candidacy List"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        Candidacy List
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 pl-0 md:pl-2">
                            <a href="candidacy/create">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        ml-0
                                        md:ml-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/form.png"
                                        alt="Candidacy Form"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        Candidacy Form
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-full flex flex-wrap">
                        <div class="w-full md:w-1/2">
                            <a href="election/committee">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        mr-0
                                        md:mr-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/committee.jpg"
                                        alt="Election Committee"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        Election Committee
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 pl-0 md:pl-2">
                            <a href="election/result">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        ml-0
                                        md:ml-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/result.png"
                                        alt="Election Result"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        Election Result
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-full flex flex-wrap">
                        <div class="w-full md:w-1/2">
                            <a href="voters/index">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        mr-0
                                        md:mr-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/list.png"
                                        alt="Voter List"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        Voter List
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-full md:w-1/2 pl-0 md:pl-2">
                            <a href="vote/show">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        ml-0
                                        md:ml-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/ballot.png"
                                        alt="Your Vote"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        Your Vote
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="w-full flex flex-wrap">
                        <div class="w-full md:w-1/2">
                            <a href="#">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        mr-0
                                        md:mr-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/information.png"
                                        alt="Information"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                       class="
                                         w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                    "
                                    > 
                                        General Information
                                    </div>
                                </div>
                            </a>
                        </div>
                           <div class="w-full md:w-1/2">
                            <a href="users/index">
                                <div
                                    class="
                                        w-full
                                        p-8
                                        my-4
                                        mr-0
                                        md:mr-2
                                        border-2
                                        rounded-lg
                                        hover:bg-blue-100
                                        flex flex-wrap
                                        justify-center
                                    "
                                >
                                    <img
                                        src="/storage/images/list.png"
                                        alt="NRNA Members List"
                                        class="w-20 h-auto my-4"
                                    />
                                    <div
                                        class="
                                            w-full
                                            text-center
                                            font-bold
                                            text-base
                                            md:text-2xl
                                        "
                                    >
                                        NRNA Member's  List
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="hidden">
                        <ol class="p-4 text-bold text-lg">
                            <li class="py-2 mb-2 text-gray-900 bg-blue-500">
                                2. <a href="users/index">Go to Voter List</a>
                            </li>
                            <li class="py-2 mb-2 text-gray-900 bg-blue-500">
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
                            <li class="py-2 mb-2 text-gray-900 bg-blue-500">
                                5. <a href="messages/index"> Go to SMS List</a>
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
                                10. <a href=""> Go to General Information </a>
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
import NrnaLayout from '@/Layouts/NrnaLayout'
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
