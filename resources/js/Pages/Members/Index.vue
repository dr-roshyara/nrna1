<template>
  <election-layout>
    <div class="m-2 min-h-screen bg-gray-100 p-2">
      <!-- organisation Header -->
      <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-3xl font-bold text-gray-900">
          Members of {{ organisation.name }}
        </h1>
        <p class="text-gray-600 mt-2">Manage organisation members and their roles</p>

        <!-- Stats Grid -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-blue-50 p-4 rounded-sm border-l-4 border-blue-500">
            <div class="text-sm text-gray-600 font-medium">Total Members</div>
            <div class="text-3xl font-bold text-blue-600">{{ stats.total_members }}</div>
          </div>
          <div class="bg-red-50 p-4 rounded-sm border-l-4 border-red-500">
            <div class="text-sm text-gray-600 font-medium">Admins</div>
            <div class="text-3xl font-bold text-red-600">{{ stats.admins_count }}</div>
          </div>
          <div class="bg-green-50 p-4 rounded-sm border-l-4 border-green-500">
            <div class="text-sm text-gray-600 font-medium">Voters</div>
            <div class="text-3xl font-bold text-green-600">{{ stats.voters_count }}</div>
          </div>
        </div>
      </div>

      <!-- Filters Section -->
      <div class="mb-4 bg-white rounded-lg shadow-sm p-4">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Name Search -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Search by Name</label>
            <input
              id="name"
              v-model="params.name"
              type="text"
              class="w-full rounded-sm border border-gray-300 shadow-xs px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Enter name..."
            />
          </div>

          <!-- Email Search -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Search by Email</label>
            <input
              id="email"
              v-model="params.email"
              type="text"
              class="w-full rounded-sm border border-gray-300 shadow-xs px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Enter email..."
            />
          </div>

          <!-- Role Filter -->
          <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Filter by Role</label>
            <select
              id="role"
              v-model="params.role"
              class="w-full rounded-sm border border-gray-300 shadow-xs px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Roles</option>
              <option value="admin">Admin</option>
              <option value="commission">Commission</option>
              <option value="voter">Voter</option>
            </select>
          </div>

          <!-- Export Button -->
          <div class="flex items-end">
            <button
              @click="exportMembers"
              class="w-full bg-green-600 text-white px-4 py-2 rounded-sm hover:bg-green-700 transition-colors font-medium"
            >
              Export CSV
            </button>
          </div>
        </div>
      </div>

      <!-- Top Pagination -->
      <div class="flex items-center justify-between px-5 py-4 bg-white rounded-t-lg shadow-xs">
        <Link
          v-if="members.prev_page_url"
          :href="members.prev_page_url"
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
          Page <span class="font-semibold text-gray-900">{{ members.current_page }}</span> of <span class="font-semibold text-gray-900">{{ members.last_page }}</span>
        </div>

        <Link
          v-if="members.next_page_url"
          :href="members.next_page_url"
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

      <!-- Members Table -->
      <div class="bg-white shadow-sm overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-blue-600 text-white">
              <th
                class="px-4 py-3 text-left text-sm font-bold cursor-pointer hover:bg-blue-700"
                @click="sort('id')"
              >
                <div class="flex items-center gap-2">
                  ID
                  <svg
                    v-if="params.field === 'id' && params.direction === 'desc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                  >
                    <path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/>
                  </svg>
                  <svg
                    v-if="params.field === 'id' && params.direction === 'asc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 8 8"
                  >
                    <path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/>
                  </svg>
                </div>
              </th>
              <th
                class="px-4 py-3 text-left text-sm font-bold cursor-pointer hover:bg-blue-700"
                @click="sort('name')"
              >
                <div class="flex items-center gap-2">
                  Name
                  <svg
                    v-if="params.field === 'name' && params.direction === 'desc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                  >
                    <path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/>
                  </svg>
                  <svg
                    v-if="params.field === 'name' && params.direction === 'asc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 8 8"
                  >
                    <path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/>
                  </svg>
                </div>
              </th>
              <th
                class="px-4 py-3 text-left text-sm font-bold cursor-pointer hover:bg-blue-700"
                @click="sort('email')"
              >
                <div class="flex items-center gap-2">
                  Email
                  <svg
                    v-if="params.field === 'email' && params.direction === 'desc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                  >
                    <path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/>
                  </svg>
                  <svg
                    v-if="params.field === 'email' && params.direction === 'asc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 8 8"
                  >
                    <path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/>
                  </svg>
                </div>
              </th>
              <th class="px-4 py-3 text-left text-sm font-bold">
                Region
              </th>
              <th
                class="px-4 py-3 text-left text-sm font-bold cursor-pointer hover:bg-blue-700"
                @click="sort('role')"
              >
                <div class="flex items-center gap-2">
                  Role
                  <svg
                    v-if="params.field === 'role' && params.direction === 'desc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                  >
                    <path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/>
                  </svg>
                  <svg
                    v-if="params.field === 'role' && params.direction === 'asc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 8 8"
                  >
                    <path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/>
                  </svg>
                </div>
              </th>
              <th
                class="px-4 py-3 text-left text-sm font-bold cursor-pointer hover:bg-blue-700"
                @click="sort('assigned_at')"
              >
                <div class="flex items-center gap-2">
                  Member Since
                  <svg
                    v-if="params.field === 'assigned_at' && params.direction === 'desc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 256 256"
                  >
                    <path d="M229.656 93.643a7.998 7.998 0 0 1-11.313.001L192 67.306v76.688a8 8 0 0 1-16 0V67.308L149.657 93.65a8 8 0 0 1-11.314-11.315l40-40c.03-.029.062-.053.092-.082c.159-.155.321-.305.493-.446c.097-.08.2-.15.301-.226c.11-.08.215-.165.328-.24c.115-.077.234-.144.352-.214c.107-.064.21-.13.32-.19c.117-.062.237-.115.356-.171c.119-.056.234-.115.355-.165c.113-.046.228-.084.342-.125c.133-.048.263-.098.4-.14c.11-.032.22-.056.332-.085c.142-.036.282-.075.427-.104c.122-.024.246-.038.37-.056c.134-.02.267-.045.404-.059c.204-.02.408-.026.612-.03c.058-.002.115-.01.173-.01c.062 0 .12.008.182.01a8 8 0 0 1 .602.03c.14.014.277.04.415.06c.12.018.24.031.359.055c.149.03.293.07.438.107c.107.027.215.05.32.082c.141.043.276.095.413.144c.11.04.22.076.328.12c.126.053.247.114.37.172c.114.054.23.105.34.164c.117.062.228.133.34.2c.112.067.225.13.333.203c.123.082.238.173.355.26c.091.07.186.133.275.206c.194.16.38.328.558.504c.01.01.02.017.028.026l40 39.993a8 8 0 0 1 0 11.314zM48 135.993h71.999a8 8 0 1 0 0-16H48a8 8 0 0 0 0 16zm0-64h55.999a8 8 0 0 0 0-16H48a8 8 0 1 0 0 16zm135.999 112H48a8 8 0 0 0 0 16h135.999a8 8 0 0 0 0-16z" fill="#fdfdfd"/>
                  </svg>
                  <svg
                    v-if="params.field === 'assigned_at' && params.direction === 'asc'"
                    class="h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 8 8"
                  >
                    <path d="M2 0v6H0l2.5 2L5 6H3V0H2zm2 0v1h2V0H4zm0 2v1h3V2H4zm0 2v1h4V4H4z" fill="#fdfdfd"/>
                  </svg>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(member, index) in members.data"
              :key="member.id"
              :class="{
                'bg-gray-200': index % 2 === 0,
                'bg-gray-50': index % 2 !== 0,
              }"
              class="hover:bg-gray-100 transition-colors border-b"
            >
              <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                {{ member.id }}
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                {{ member.name }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-700">
                {{ member.email }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-700">
                {{ member.state || 'N/A' }}
              </td>
              <td class="px-4 py-3 text-sm">
                <span :class="roleClass(member.role)" class="px-3 py-1 rounded-sm text-xs font-medium">
                  {{ member.role }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-700">
                {{ formatDate(member.assigned_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Bottom Pagination -->
      <div class="flex items-center justify-between px-5 py-4 bg-white rounded-b-lg shadow-xs">
        <Link
          v-if="members.prev_page_url"
          :href="members.prev_page_url"
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
          Page <span class="font-semibold text-gray-900">{{ members.current_page }}</span> of <span class="font-semibold text-gray-900">{{ members.last_page }}</span>
        </div>

        <Link
          v-if="members.next_page_url"
          :href="members.next_page_url"
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
import { Link } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import _ from 'lodash'

export default {
  components: { Link, ElectionLayout },

  props: {
    members: Object,
    organisation: Object,
    filters: Object,
    currentUser: Object,
    stats: Object,
  },

  data() {
    return {
      params: {
        name: this.filters?.name || '',
        email: this.filters?.email || '',
        role: this.filters?.role || '',
        field: this.filters?.field || 'assigned_at',
        direction: this.filters?.direction || 'desc',
      }
    }
  },

  watch: {
    params: {
      handler: _.debounce(function() {
        const params = Object.fromEntries(
          Object.entries(this.params).filter(([_, v]) => v != null && v !== '')
        );
        this.$inertia.get(route('members.index'), params, {
          replace: true,
          preserveState: true
        });
      }, 300),
      deep: true
    }
  },

  methods: {
    sort(field) {
      this.params.field = field;
      this.params.direction = this.params.direction === 'desc' ? 'asc' : 'desc';
    },

    roleClass(role) {
      const classes = {
        admin: 'bg-red-100 text-red-800',
        commission: 'bg-blue-100 text-blue-800',
        voter: 'bg-green-100 text-green-800',
      };
      return classes[role] || 'bg-gray-100 text-gray-800';
    },

    formatDate(date) {
      if (!date) return 'N/A';
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      });
    },

    exportMembers() {
      const params = new URLSearchParams(this.params);
      window.location.href = `/members/export?${params.toString()}`;
    }
  }
}
</script>
