<template>
  <AppLayout>
    <Head title="Committee Dashboard" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h1 class="text-3xl font-bold text-gray-900">
                  {{ committee.name }}
                </h1>
                <p class="mt-2 text-gray-600">
                  <span class="inline-block bg-gray-100 px-3 py-1 rounded-full text-sm font-medium">
                    {{ formatCommitteeType(committee.type) }}
                  </span>
                </p>
              </div>
              <div class="text-right">
                <p class="text-gray-600">Code: <strong>{{ committee.code }}</strong></p>
                <p class="text-gray-600 mt-2">Status: <strong class="text-green-600">{{ committee.status }}</strong></p>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <!-- Committee Info Card -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Committee Info</h3>
            <div class="space-y-3 text-sm">
              <div>
                <p class="text-gray-600">Type</p>
                <p class="font-medium">{{ formatCommitteeType(committee.type) }}</p>
              </div>
              <div>
                <p class="text-gray-600">Level</p>
                <p class="font-medium">{{ committee.level }}</p>
              </div>
              <div v-if="committee.geo_reference">
                <p class="text-gray-600">Geography</p>
                <p class="font-medium">{{ committee.geo_reference }}</p>
              </div>
              <div>
                <p class="text-gray-600">Formed</p>
                <p class="font-medium">{{ formatDate(committee.formation_date) }}</p>
              </div>
            </div>
          </div>

          <!-- Active Members Card -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Active Members</h3>
            <div class="text-center py-4">
              <p class="text-4xl font-bold text-blue-600">{{ assignments.length }}</p>
              <p class="text-gray-600 mt-2">Active Office Bearers</p>
            </div>
          </div>

          <!-- Sub-Committees Card -->
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sub-Committees</h3>
            <div class="text-center py-4">
              <p class="text-4xl font-bold text-green-600">{{ sub_committees.length }}</p>
              <p class="text-gray-600 mt-2">Regional Committees</p>
            </div>
          </div>
        </div>

        <!-- Active Assignments Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Active Office Bearers</h3>
          </div>
          <div v-if="assignments.length > 0" class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left font-medium text-gray-700">Member</th>
                  <th class="px-6 py-3 text-left font-medium text-gray-700">Role</th>
                  <th class="px-6 py-3 text-left font-medium text-gray-700">Joined</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="assignment in assignments" :key="assignment.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4">{{ assignment.member_name }}</td>
                  <td class="px-6 py-4">{{ formatRole(assignment.role) }}</td>
                  <td class="px-6 py-4">{{ formatDate(assignment.joined_date) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="p-6 text-center text-gray-500">
            No active assignments yet
          </div>
        </div>

        <!-- Sub-Committees List -->
        <div v-if="sub_committees.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Regional Committees</h3>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="subCommittee in sub_committees"
                :key="subCommittee.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition"
              >
                <h4 class="font-semibold text-gray-900">{{ subCommittee.name }}</h4>
                <p class="text-sm text-gray-600 mt-1">{{ formatCommitteeType(subCommittee.type) }}</p>
                <p class="text-sm text-gray-500 mt-2">Code: {{ subCommittee.code }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  committee: {
    type: Object,
    required: true,
  },
  sub_committees: {
    type: Array,
    default: () => [],
  },
  assignments: {
    type: Array,
    default: () => [],
  },
});

const formatCommitteeType = (type) => {
  const types = {
    central: 'Central Committee',
    province: 'Province Committee',
    district: 'District Committee',
    ward: 'Ward Committee',
    youth: 'Youth Wing',
    women: "Women's Wing",
    student: 'Student Wing',
  };
  return types[type] || type;
};

const formatRole = (role) => {
  const roles = {
    chairperson: 'Chairperson',
    vice_chairperson: 'Vice Chairperson',
    secretary: 'Secretary',
    treasurer: 'Treasurer',
    coordinator: 'Coordinator',
    member: 'Member',
  };
  return roles[role] || role;
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};
</script>

<style scoped>
/* Component styles here */
</style>
