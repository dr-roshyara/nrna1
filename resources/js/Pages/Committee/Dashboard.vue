<template>
  <AppLayout>
    <Head title="Committee Dashboard" />

    <div class="min-h-screen bg-gradient-to-br from-neutral-50 via-primary-50/20 to-neutral-50 py-8 sm:py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-8">
          <div class="flex items-center gap-3 mb-6">
            <div class="h-12 w-12 rounded-lg bg-primary-100 flex items-center justify-center">
              <svg class="h-7 w-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-neutral-900">{{ committee.name }}</h1>
              <p class="text-sm text-neutral-600 mt-1">Committee Administration</p>
            </div>
          </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6 border-b border-neutral-200">
          <div class="flex gap-6">
            <button
              @click="activeTab = 'overview'"
              :class="[
                'pb-4 font-semibold text-sm transition-all duration-200 border-b-2',
                activeTab === 'overview'
                  ? 'text-primary-600 border-primary-600'
                  : 'text-neutral-600 border-transparent hover:text-neutral-900'
              ]"
            >
              Overview
            </button>
            <button
              @click="activeTab = 'members'"
              :class="[
                'pb-4 font-semibold text-sm transition-all duration-200 border-b-2',
                activeTab === 'members'
                  ? 'text-primary-600 border-primary-600'
                  : 'text-neutral-600 border-transparent hover:text-neutral-900'
              ]"
            >
              Members
            </button>
          </div>
        </div>

        <!-- Overview Tab -->
        <div v-show="activeTab === 'overview'" class="space-y-8">
          <!-- Quick Stats -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg border border-neutral-200 p-6">
              <p class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Committee Code</p>
              <p class="text-2xl font-bold text-neutral-900 font-mono mt-2">{{ committee.code }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-6">
              <p class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Type</p>
              <p class="text-2xl font-bold text-primary-600 mt-2">{{ formatCommitteeType(committee.type) }}</p>
            </div>
            <div class="bg-white rounded-lg border border-neutral-200 p-6">
              <p class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Formed</p>
              <p class="text-2xl font-bold text-neutral-900 mt-2">{{ formatDate(committee.formation_date) }}</p>
            </div>
          </div>

          <!-- Members Card -->
          <div class="bg-white rounded-lg border border-neutral-200 p-8">
            <h3 class="text-lg font-bold text-neutral-900 mb-6">Active Members</h3>
            <p class="text-4xl font-black text-primary-600">{{ members.length }}</p>
            <p class="text-sm text-neutral-600 mt-2">Active office bearers</p>
          </div>

          <!-- Sub-Committees Card -->
          <div v-if="sub_committees.length > 0" class="bg-white rounded-lg border border-neutral-200 p-8">
            <h3 class="text-lg font-bold text-neutral-900 mb-6">Regional Committees</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="subCommittee in sub_committees"
                :key="subCommittee.id"
                class="rounded-lg border border-neutral-200 p-4 hover:border-primary-300 transition-colors"
              >
                <h4 class="font-bold text-neutral-900">{{ subCommittee.name }}</h4>
                <p class="text-sm text-neutral-600 mt-1">{{ formatCommitteeType(subCommittee.type) }}</p>
                <p class="text-xs text-neutral-500 font-mono mt-2">{{ subCommittee.code }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Members Tab -->
        <div v-show="activeTab === 'members'">
          <CommitteeMemberManager
            :committee-id="committee.id"
            :organisation-id="organisationId"
          />
        </div>

      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CommitteeMemberManager from '@/Components/CommitteeMemberManager.vue';

const props = defineProps({
  committee: {
    type: Object,
    required: true,
  },
  sub_committees: {
    type: Array,
    default: () => [],
  },
  members: {
    type: Array,
    default: () => [],
  },
  organisationId: {
    type: String,
    required: true,
  },
});

const activeTab = ref('overview');

const formatCommitteeType = (type) => {
  const types = {
    central: 'Central Committee',
    province: 'Province Committee',
    district: 'District Committee',
    ward: 'Ward Committee',
    youth_wing: 'Youth Wing',
    women_wing: "Women's Wing",
    student_wing: 'Student Wing',
    diaspora: 'Diaspora Wing',
    geographic: 'Geographic Committee',
  };
  return types[type] || type;
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
/* Smooth transitions for tab switching */
div[v-show] {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
