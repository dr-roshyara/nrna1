<template>
  <AppLayout>
    <Head title="Committee Dashboard" />

    <div class="min-h-screen bg-gradient-to-br from-neutral-50 via-primary-50/20 to-neutral-50 py-8 sm:py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Navigation Tabs -->
        <div class="mb-8 flex gap-2 border-b border-neutral-200">
          <button
            @click="activeTab = 'overview'"
            :class="[
              'relative px-6 py-4 font-semibold text-sm transition-all duration-300 flex items-center gap-2',
              activeTab === 'overview'
                ? 'text-primary-600'
                : 'text-neutral-600 hover:text-neutral-900'
            ]"
          >
            <svg :class="['w-5 h-5', activeTab === 'overview' ? 'text-primary-600' : 'text-neutral-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Overview
            <transition name="slideDown">
              <div v-if="activeTab === 'overview'" class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-600 to-primary-400 rounded-t-full"></div>
            </transition>
          </button>

          <button
            @click="activeTab = 'members'"
            :class="[
              'relative px-6 py-4 font-semibold text-sm transition-all duration-300 flex items-center gap-2',
              activeTab === 'members'
                ? 'text-primary-600'
                : 'text-neutral-600 hover:text-neutral-900'
            ]"
          >
            <svg :class="['w-5 h-5', activeTab === 'members' ? 'text-primary-600' : 'text-neutral-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 20a3 3 0 10-6 0h6z" />
            </svg>
            Members
            <transition name="slideDown">
              <div v-if="activeTab === 'members'" class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-600 to-primary-400 rounded-t-full"></div>
            </transition>
          </button>
        </div>

        <!-- Overview Tab Content -->
        <template v-if="activeTab === 'overview'">

        <!-- Hero Header Section -->
        <article class="bg-white rounded-xl border border-neutral-200 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden mb-8">
          <header class="px-8 py-8 border-b border-primary-100 bg-gradient-to-br from-primary-50 via-primary-50 to-white">
            <div class="flex items-start justify-between gap-6">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                  <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-100">
                    <svg class="h-7 w-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                  </div>
                  <div>
                    <h1 class="text-4xl font-bold text-neutral-900">{{ committee.name }}</h1>
                    <p class="text-sm text-neutral-600 mt-1">Committee Administration Dashboard</p>
                  </div>
                </div>
              </div>
              <div class="text-right space-y-3 flex-shrink-0">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-4 py-2">
                  <span class="inline-block h-2 w-2 rounded-full bg-success-500"></span>
                  <span class="text-xs font-semibold text-primary-700 uppercase">{{ committee.status || 'Active' }}</span>
                </div>
                <a
                  href="/tutorial"
                  target="_blank"
                  class="block bg-primary-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition-all duration-300 text-center text-sm"
                >
                  📚 Tutorial
                </a>
              </div>
            </div>
          </header>

          <!-- Quick Stats Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 px-8 py-8">
            <div class="space-y-2">
              <div class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Committee Code</div>
              <div class="text-2xl font-black text-neutral-900 font-mono">{{ committee.code }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Type</div>
              <div class="text-lg font-bold text-primary-600">{{ formatCommitteeType(committee.type) }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-xs font-semibold uppercase tracking-wider text-neutral-600">Formed</div>
              <div class="text-lg font-bold text-neutral-900">{{ formatDate(committee.formation_date) }}</div>
            </div>
          </div>
        </article>

        <!-- Main Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Committee Info Card -->
          <div class="bg-white rounded-lg border border-neutral-200 p-6 hover:border-primary-300 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-sm font-semibold uppercase tracking-wider text-neutral-600">Details</h3>
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="space-y-4">
              <div>
                <p class="text-xs text-neutral-600 font-medium uppercase">Level</p>
                <p class="text-lg font-bold text-neutral-900 mt-1">{{ committee.level || 'Central' }}</p>
              </div>
              <div v-if="committee.geo_reference">
                <p class="text-xs text-neutral-600 font-medium uppercase">Geography</p>
                <p class="text-lg font-bold text-primary-600 mt-1">{{ committee.geo_reference }}</p>
              </div>
            </div>
          </div>

          <!-- Active Members Card -->
          <div class="bg-gradient-to-br from-primary-50 to-white rounded-lg border border-primary-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-sm font-semibold uppercase tracking-wider text-neutral-600">Members</h3>
              <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div class="text-center">
              <p class="text-5xl font-black text-primary-600 mb-1">{{ members.length }}</p>
              <p class="text-sm text-neutral-600 font-medium">Active Office Bearers</p>
            </div>
          </div>

          <!-- Sub-Committees Card -->
          <div class="bg-gradient-to-br from-accent-50 to-white rounded-lg border border-accent-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-sm font-semibold uppercase tracking-wider text-neutral-600">Sub-Committees</h3>
              <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
            </div>
            <div class="text-center">
              <p class="text-5xl font-black text-accent-600 mb-1">{{ sub_committees.length }}</p>
              <p class="text-sm text-neutral-600 font-medium">Regional Committees</p>
            </div>
          </div>
        </div>

        <!-- Active Members Table -->
        <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-sm mb-8">
          <div class="px-8 py-6 border-b border-neutral-100 flex items-center gap-3">
            <div class="h-1 w-12 bg-gradient-to-r from-primary-600 to-primary-400 rounded-full"></div>
            <h3 class="text-lg font-bold text-neutral-900">Active Office Bearers</h3>
          </div>
          <div v-if="members.length > 0" class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-neutral-50 border-b border-neutral-100">
                <tr>
                  <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Member</th>
                  <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Role</th>
                  <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Status</th>
                  <th class="px-8 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600">Joined Date</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-neutral-100">
                <tr v-for="member in members" :key="member.member_id" class="hover:bg-neutral-50 transition-colors duration-200">
                  <td class="px-8 py-4 font-medium text-neutral-900">{{ member.display_name }}</td>
                  <td class="px-8 py-4">
                    <span class="inline-flex items-center rounded-full border border-primary-200 bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700">
                      {{ $t(member.role_key) }}
                    </span>
                  </td>
                  <td class="px-8 py-4">
                    <span class="inline-flex items-center rounded-full border border-success-200 bg-success-50 px-3 py-1 text-xs font-semibold text-success-700">
                      {{ $t(member.status_key) }}
                    </span>
                  </td>
                  <td class="px-8 py-4 text-neutral-600">{{ formatDate(member.joined_date) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="flex flex-col items-center justify-center px-8 py-16">
            <svg class="w-12 h-12 text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            <p class="text-neutral-500 font-medium">No active office bearers assigned yet</p>
          </div>
        </div>

        <!-- Sub-Committees List -->
        <div v-if="sub_committees.length > 0" class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-sm">
          <div class="px-8 py-6 border-b border-neutral-100 flex items-center gap-3">
            <div class="h-1 w-12 bg-gradient-to-r from-accent-600 to-accent-400 rounded-full"></div>
            <h3 class="text-lg font-bold text-neutral-900">Regional Committees</h3>
          </div>
          <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
              <div
                v-for="subCommittee in sub_committees"
                :key="subCommittee.id"
                class="rounded-lg border border-neutral-200 p-5 hover:border-accent-300 hover:shadow-md transition-all duration-300 group"
              >
                <div class="flex items-start justify-between mb-3">
                  <h4 class="font-bold text-neutral-900 text-base">{{ subCommittee.name }}</h4>
                  <svg class="w-5 h-5 text-accent-400 group-hover:text-accent-600 transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v10.5A2.25 2.25 0 005.75 16.5h8.5a2.25 2.25 0 002.25-2.25V9.5M10.5 1.5v5h5M10.5 1.5L15.5 6.5" /></svg>
                </div>
                <p class="text-sm text-neutral-600 mb-3">{{ formatCommitteeType(subCommittee.type) }}</p>
                <div class="pt-3 border-t border-neutral-100">
                  <p class="text-xs text-neutral-600 font-mono">{{ subCommittee.code }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        </template>

        <!-- Members Tab Content -->
        <template v-if="activeTab === 'members'">
          <div class="animate-fade-in">
            <CommitteeMemberManager
              :committee-id="committee.id"
              :organisation-id="organisationId"
            />
          </div>
        </template>
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
    youth: 'Youth Wing',
    women: "Women's Wing",
    student: 'Student Wing',
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
/* Smooth entrance animations */
@keyframes slideUpFade {
  from {
    opacity: 0;
    transform: translateY(16px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

article {
  animation: slideUpFade 0.5s ease-out;
}

.grid > div {
  animation: slideUpFade 0.6s ease-out forwards;
  opacity: 0;
}

.grid > div:nth-child(1) {
  animation-delay: 0.1s;
}

.grid > div:nth-child(2) {
  animation-delay: 0.2s;
}

.grid > div:nth-child(3) {
  animation-delay: 0.3s;
}

/* Tab Navigation Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

.slideDown-enter-active,
.slideDown-leave-active {
  transition: all 0.3s ease-out;
}

.slideDown-enter-from {
  opacity: 0;
  transform: translateY(-4px);
}

.slideDown-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}

/* Table row hover animations */
tbody tr {
  transition: background-color 0.2s ease;
}

/* Refined focus states */
a:focus-visible {
  outline: 2px solid rgb(245, 158, 11);
  outline-offset: 2px;
}
</style>
