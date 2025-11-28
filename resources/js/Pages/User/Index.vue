<template>
   <election-layout>
        <div class="m-2 min-h-screen bg-gray-100 p-2">
            <div class="mx-auto w-full text-center">
                <!-- Top Pagination -->
                <div class="flex items-center justify-between px-5 py-4">
                    <Link
                        v-if="users.prev_page_url"
                        :href="users.prev_page_url"
                        class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                    >
                        <svg class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Previous</span>
                    </Link>
                    <div v-else class="invisible flex items-center gap-2 text-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>Previous</span>
                    </div>

                    <div class="text-sm text-gray-600">
                        Page <span class="font-semibold text-gray-900">{{ users.current_page }}</span> of <span class="font-semibold text-gray-900">{{ users.last_page }}</span>
                    </div>

                    <Link
                        v-if="users.next_page_url"
                        :href="users.next_page_url"
                        class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                    >
                        <span>Next</span>
                        <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </Link>
                    <div v-else class="invisible flex items-center gap-2 text-sm">
                        <span>Next</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>

                <!--  Here comes the filtering  -->
                <div class="flex flex-row">
                    <div class="m-1 p-2">
                        <label for="name">Search by Name</label>
                        <input
                            id="name"
                            type="text"
                            v-model="params.name"
                            class="ml-2 rounded border bg-blue-200 px-2 py-1 text-sm"
                        />
                    </div>
                    <!-- next -->
                    <!-- <div class="p-2 m-1 " >
                        <label for="search" >Search by Familyname</label>
                        <input  id="search" type="text" v-model="params.search"
                        class="ml-2 px-2 py-1 text-sm bg-blue-200 rounded border">

                    </div> -->
                    <!-- next -->
                    <div class="m-1 p-2">
                        <label for="nrna_id">Search by Membership ID</label>
                        <input
                            id="nrna_id"
                            type="text"
                            v-model="params.nrna_id"
                            class="ml-2 rounded border bg-blue-200 px-2 py-1 text-sm"
                        />
                    </div>
                </div>

                <!-- Bulk actions -->
                <div v-if="selectedUsers.length > 0 && currentUser?.is_committee_member == 1" class="mb-4 rounded bg-yellow-100 border border-yellow-400 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-yellow-800">
                            {{ selectedUsers.length }} user(s) selected
                        </span>
                        <button
                            @click="bulkAddAsVoter"
                            class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
                        >
                            Add Selected as Voters
                        </button>
                    </div>
                </div>

                <div class="table w-full p-2">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-blue-600 p-2 text-white">
                                <!-- Checkbox column header -->
                                <th class="mb-1 px-2 py-2 text-left text-sm font-bold">
                                    <input
                                        v-if="currentUser?.is_committee_member == 1"
                                        type="checkbox"
                                        @change="toggleSelectAll"
                                        :checked="allSelected"
                                        class="rounded"
                                    />
                                </th>
                                <th
                                    class="mb-1 px-2 py-2 text-left text-sm font-bold"
                                >
                                    <span
                                        class="flex flex-row space-x-2"
                                        @click="sort('id')"
                                        >Nr
                                        <svg
                                            v-if="
                                                (params.field === 'id') &
                                                (params.direction === 'desc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1.5em"
                                            height="1.5em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 256 256"
                                        >
                                            <path
                                                d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                        <svg
                                            v-if="
                                                (params.field === 'id') &
                                                (params.direction === 'asc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1em"
                                            height="1em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 8 8"
                                        >
                                            <path
                                                d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                    </span>
                                </th>
                                <th
                                    class="mb-1 px-2 py-2 text-left text-sm font-bold"
                                >
                                    <span
                                        class="flex flex-row space-x-2"
                                        @click="sort('nrna_id')"
                                    >
                                        Membership ID
                                        <svg
                                            v-if="
                                                (params.field === 'nrna_id') &
                                                (params.direction === 'desc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1.5em"
                                            height="1.5em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 256 256"
                                        >
                                            <path
                                                d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                        <svg
                                            v-if="
                                                (params.field === 'nrna_id') &
                                                (params.direction === 'asc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1em"
                                            height="1em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 8 8"
                                        >
                                            <path
                                                d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                    </span>
                                </th>
                                <!-- next -->
                                <th
                                    class="mb-1 px-2 py-2 text-left text-sm font-bold"
                                >
                                    <span
                                        class="flex flex-row space-x-2"
                                        @click="sort('name')"
                                    >
                                        Name
                                        <svg
                                            v-if="
                                                (params.field === 'name') &
                                                (params.direction === 'desc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1.5em"
                                            height="1.5em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 256 256"
                                        >
                                            <path
                                                d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                        <svg
                                            v-if="
                                                (params.field === 'name') &
                                                (params.direction === 'asc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1em"
                                            height="1em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 8 8"
                                        >
                                            <path
                                                d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                    </span>
                                </th>
                                <!-- next -->
                                <th
                                    v-if="false"
                                    class="mb-1 px-2 py-2 text-left text-sm font-bold"
                                >
                                    <span
                                        class="flex flex-row space-x-2"
                                        @click="sort('last_name')"
                                    >
                                        Lastname
                                        <svg
                                            v-if="
                                                (params.field === 'last_name') &
                                                (params.direction === 'desc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1.5em"
                                            height="1.5em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 256 256"
                                        >
                                            <path
                                                d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                        <svg
                                            v-if="
                                                (params.field === 'last_name') &
                                                (params.direction === 'asc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1em"
                                            height="1em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 8 8"
                                        >
                                            <path
                                                d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                    </span>
                                </th>
                                <!-- Region column -->
                                <th
                                    class="mb-1 px-2 py-2 text-left text-sm font-bold"
                                >
                                    <span
                                        class="flex flex-row space-x-2"
                                        @click="sort('state')"
                                    >
                                        Region
                                        <svg
                                            v-if="
                                                (params.field === 'state') &
                                                (params.direction === 'desc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1.5em"
                                            height="1.5em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 256 256"
                                        >
                                            <path
                                                d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                        <svg
                                            v-if="
                                                (params.field === 'state') &
                                                (params.direction === 'asc')
                                            "
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            aria-hidden="true"
                                            focusable="false"
                                            width="1em"
                                            height="1em"
                                            style="
                                                -ms-transform: rotate(360deg);
                                                -webkit-transform: rotate(
                                                    360deg
                                                );
                                                transform: rotate(360deg);
                                            "
                                            preserveAspectRatio="xMidYMid meet"
                                            viewBox="0 0 8 8"
                                        >
                                            <path
                                                d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z"
                                                fill="#fdfdfd"
                                            />
                                        </svg>
                                    </span>
                                </th>
                                <!-- next -->
                                <!-- <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('telephone')"> Telephone
                         <svg  v-if="params.field==='telephone' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/></svg>
                         <svg v-if="params.field==='telephone' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/></svg>

                        </span></th>  -->
                                <!-- Action column -->
                                <th
                                    class="mb-1 px-2 py-2 text-left text-sm font-bold"
                                >
                                    Action
                                </th>
                                <!-- next -->
                                <!-- <th class="px-2 py-2 mb-1 text-left text-sm font-bold"> <span class=" flex flex-row space-x-2" @click="sort('created_at')">Created at
                        <svg  v-if="params.field==='created_at' & params.direction==='desc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/></svg>
                         <svg v-if="params.field==='created_at' & params.direction==='asc'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 8 8"><path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/></svg>

                        </span> </th> -->
                                <!-- <th class="px-2 py-2 mb-1 text-left text-sm font-bold">
                            <span class=" flex flex-row space-x-2" >Send SMS Code

                        </span> </th>                      -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(user, index) in users.data"
                                :key="index"
                                :class="{
                                    'my-6 bg-gray-200 py-2 ': index % 2 === 0,
                                    'my-6 bg-gray-50 py-2': index % 2 > 0,
                                }"
                            >
                                <!-- Checkbox for each user -->
                                <th class="m-4 px-2 py-4 text-left text-sm font-semibold">
                                    <input
                                        v-if="currentUser?.is_committee_member == 1 && user.is_voter != 1"
                                        type="checkbox"
                                        v-model="selectedUsers"
                                        :value="user.id"
                                        class="rounded"
                                    />
                                </th>
                                <th
                                    class="m-4 px-2 py-4 text-left text-sm font-semibold"
                                >
                                    {{ user.id }}
                                </th>
                                <th
                                    class="m-4 px-2 py-4 text-left text-sm font-semibold"
                                >
                                    {{ user.nrna_id }}
                                </th>
                                <th
                                    class="m-4 px-2 py-4 text-left text-sm font-semibold"
                                >
                                    {{ user.name }}
                                </th>
                                <!-- <th class="px-2 py-4 m-4 text-left text-sm font-semibold"> {{user.region}} </th> -->
                                <!-- <th class="px-2 py-4 m-4 text-left text-sm font-semibold"> {{user.telephone}} </th> -->
                                <th
                                    class="m-4 px-2 py-4 text-left text-sm font-semibold"
                                >
                                    {{ user.region }}
                                </th>
                                <!-- Action column -->
                                <th
                                    class="m-4 px-2 py-4 text-left text-sm font-semibold"
                                >
                                    <!-- Button appears for ALL users, but only visible if current logged-in user is committee member -->
                                    <button
                                        v-if="currentUser?.is_committee_member == 1"
                                        :class="{
                                            'rounded px-3 py-1 text-white': true,
                                            'bg-blue-500 hover:bg-blue-600 cursor-pointer': user.is_voter != 1,
                                            'bg-gray-400 cursor-not-allowed': user.is_voter == 1
                                        }"
                                        :disabled="user.is_voter == 1"
                                        @click="user.is_voter != 1 ? showAddVoterButton(user.id) : null"
                                    >
                                        {{ user.is_voter == 1 ? 'Already Voter' : 'Add as Voter' }}
                                    </button>
                                </th>
                                <!-- <th class="px-2 py-4 m-4 text-left text-sm font-semibold"> {{user.created_at}} </th> -->
                                <!-- <th class="p-2 m-2 text-left text-sm font-semibold ">
                           <sendmessage v-bind:vtelephone="user.telephone"> </sendmessage>
                        </th>   -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bottom Pagination -->
            <div class="flex items-center justify-between px-5 py-4">
                <Link
                    v-if="users.prev_page_url"
                    :href="users.prev_page_url"
                    class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                >
                    <svg class="h-5 w-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Previous</span>
                </Link>
                <div v-else class="invisible flex items-center gap-2 text-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Previous</span>
                </div>

                <div class="text-sm text-gray-600">
                    Page <span class="font-semibold text-gray-900">{{ users.current_page }}</span> of <span class="font-semibold text-gray-900">{{ users.last_page }}</span>
                </div>

                <Link
                    v-if="users.next_page_url"
                    :href="users.next_page_url"
                    class="group flex items-center gap-2 text-sm font-medium text-gray-600 transition-colors hover:text-blue-600"
                >
                    <span>Next</span>
                    <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </Link>
                <div v-else class="invisible flex items-center gap-2 text-sm">
                    <span>Next</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </election-layout>
</template>
<script>
import ElectionLayout from "@/Layouts/ElectionLayout";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/inertia-vue3";
import Sendmessage from "@/Pages/Message/Sendmessage";
import _ from "lodash";

export default {
    props: {
        users: Object,
        filters: Object,
        currentUser: Object,
        // from: String,
        // name: String,
    },
    data() {
        return {
            term: "",
            selectedUsers: [],
            params: {
                search: this.filters?.search || "",
                name: this.filters?.name || "",
                nrna_id: this.filters?.nrna_id || "",
                field: this.filters?.field || "",
                direction: this.filters?.direction || "",
            },
        };
    },
    computed: {
        allSelected() {
            const eligibleUsers = this.users.data.filter(user => user.is_voter != 1);
            return eligibleUsers.length > 0 && this.selectedUsers.length === eligibleUsers.length;
        },
        eligibleUsers() {
            return this.users.data.filter(user => user.is_voter != 1);
        }
    },
    watch: {
        params: {
            handler: _.debounce(function() {
                let params = _.cloneDeep(this.params);
                Object.keys(params).forEach((key) => {
                    if (params[key] == "" || params[key] == null) {
                        delete params[key];
                    }
                });
                // the above thing can be done by using the following one line
                //let params =pickBy(this.params);
                this.$inertia.get(route("users.index"), params, {
                    replace: true,
                    preserveState: true,
                });
            }, 300),
            deep: true,
        },
    },
    methods: {
        // search(){
        //     this.$inertia.replace(this.$route('users.index',{term: this.term}));
        // }
        searching: _.throttle(function () {
            this.$inertia.replace(route("users.index", { term: this.term }));
        }, 200),
        sort(field) {
            this.params.field = field;
            console.log(this.params.direction);
            if (this.params.direction === "desc") {
                this.params.direction = "asc";
            } else {
                this.params.direction = "desc";
            }
            //this.params.direction = this.params.direction === 'asc'  ?  'desc' :  'asc';
        },
        showAddVoterButton(userId) {
            console.log('Add as Voter clicked for user:', userId);

            // Make a POST request to add the user as voter
            this.$inertia.post(route('users.addAsVoter', userId), {}, {
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    // The page will refresh automatically with the updated user data
                    console.log('User added as voter successfully');
                },
                onError: (errors) => {
                    console.error('Error adding user as voter:', errors);
                }
            });
        },
        toggleSelectAll() {
            if (this.allSelected) {
                this.selectedUsers = [];
            } else {
                this.selectedUsers = this.eligibleUsers.map(user => user.id);
            }
        },
        bulkAddAsVoter() {
            if (this.selectedUsers.length === 0) {
                alert('Please select users to add as voters.');
                return;
            }

            if (confirm(`Are you sure you want to add ${this.selectedUsers.length} selected committee members as voters?`)) {
                this.$inertia.post(route('users.bulkAddAsVoter'), {
                    user_ids: this.selectedUsers
                }, {
                    preserveState: false,
                    preserveScroll: true,
                    onSuccess: () => {
                        this.selectedUsers = [];
                        console.log('Users added as voters successfully');
                    },
                    onError: (errors) => {
                        console.error('Error adding users as voters:', errors);
                    }
                });
            }
        },
    },
    components: {
        Sendmessage,
        ElectionLayout,
        Link
    },
};
</script>
