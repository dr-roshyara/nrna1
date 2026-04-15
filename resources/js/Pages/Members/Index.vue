<template>
  <election-layout>
    <div class="m-2 min-h-screen bg-gray-100 p-2">

      <!-- Header -->
      <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Formal Members — {{ organisation.name }}</h1>
            <p class="text-sm text-gray-500 mt-1">
              Only persons with an active membership record are listed here.
              Platform staff, commissioners and voters are managed separately.
            </p>
          </div>
          <div class="flex items-center gap-3">
            <a
              :href="`/organisations/${organisation.slug}/participants`"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors"
            >
              All Participants →
            </a>
            <a
              :href="`/organisations/${organisation.slug}`"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back
            </a>
          </div>
        </div>

        <!-- Stats -->
        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Active Members</div>
            <div class="text-3xl font-bold text-blue-600 mt-1">{{ stats.total_members }}</div>
          </div>
          <div class="bg-orange-50 p-4 rounded border-l-4 border-orange-400">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Expired</div>
            <div class="text-3xl font-bold text-orange-500 mt-1">{{ stats.expired_count }}</div>
          </div>
          <div class="bg-amber-50 p-4 rounded border-l-4 border-amber-500">
            <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Pending Fees (total)</div>
            <div class="text-3xl font-bold text-amber-600 mt-1">€{{ stats.pending_fees.toFixed(2) }}</div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="mb-4 bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
              v-model="params.name"
              type="text"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Search by name…"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              v-model="params.email"
              type="text"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Search by email…"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select
              v-model="params.status"
              class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All</option>
              <option value="active">Active</option>
              <option value="expired">Expired</option>
              <option value="ended">Ended</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="exportMembers"
              class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors font-medium text-sm"
            >
              Export CSV
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination top -->
      <div class="flex items-center justify-between px-5 py-3 bg-white rounded-t-lg shadow-xs">
        <Link v-if="members.prev_page_url" :href="members.prev_page_url"
          class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600">
          ← Previous
        </Link>
        <div v-else class="invisible text-sm">← Previous</div>
        <span class="text-sm text-gray-600">
          Page <strong>{{ members.current_page }}</strong> of <strong>{{ members.last_page }}</strong>
          &nbsp;({{ members.total }} records)
        </span>
        <Link v-if="members.next_page_url" :href="members.next_page_url"
          class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600">
          Next →
        </Link>
        <div v-else class="invisible text-sm">Next →</div>
      </div>

      <!-- Table -->
      <div class="bg-white shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-indigo-700 text-white text-left">
              <th class="px-4 py-3 font-semibold">Name</th>
              <th class="px-4 py-3 font-semibold">Email</th>
              <th class="px-4 py-3 font-semibold cursor-pointer hover:bg-indigo-800" @click="sort('status')">
                Status <span v-if="params.field === 'status'">{{ params.direction === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="px-4 py-3 font-semibold cursor-pointer hover:bg-indigo-800" @click="sort('joined_at')">
                Joined <span v-if="params.field === 'joined_at'">{{ params.direction === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="px-4 py-3 font-semibold cursor-pointer hover:bg-indigo-800" @click="sort('membership_expires_at')">
                Expires <span v-if="params.field === 'membership_expires_at'">{{ params.direction === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="px-4 py-3 font-semibold">Pending Fees</th>
              <th class="px-4 py-3 font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!members.data || members.data.length === 0">
              <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                No formal members found. Members are created when a membership application is approved.
              </td>
            </tr>
            <tr
              v-for="(member, index) in members.data"
              :key="member.id"
              :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
              class="hover:bg-indigo-50 transition-colors border-b border-gray-100"
            >
              <td class="px-4 py-3 font-medium text-gray-900">{{ member.name }}</td>
              <td class="px-4 py-3 text-gray-600">{{ member.email }}</td>
              <td class="px-4 py-3">
                <span :class="statusClass(member.status)" class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize">
                  {{ member.status }}
                </span>
              </td>
              <td class="px-4 py-3 text-gray-600">{{ formatDate(member.joined_at) }}</td>
              <td class="px-4 py-3 text-gray-600" :class="{ 'text-red-600 font-medium': isExpired(member.membership_expires_at) }">
                {{ member.membership_expires_at ? formatDate(member.membership_expires_at) : 'Lifetime' }}
              </td>
              <td class="px-4 py-3">
                <span v-if="member.pending_fees > 0"
                  class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">
                  €{{ member.pending_fees.toFixed(2) }}
                </span>
                <span v-else class="text-gray-400 text-xs">—</span>
              </td>
              <td class="px-4 py-3 flex items-center gap-2">
                <button
                  v-if="member.pending_fees > 0"
                  @click="markAsPaid(member.id, member.name)"
                  class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded transition-colors"
                >
                  ✓ Mark Paid
                </button>
                <a
                  :href="`/organisations/${organisation.slug}/members/${member.id}/fees`"
                  class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline"
                >
                  Fees →
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination bottom -->
      <div class="flex items-center justify-between px-5 py-3 bg-white rounded-b-lg shadow-xs">
        <Link v-if="members.prev_page_url" :href="members.prev_page_url"
          class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600">
          ← Previous
        </Link>
        <div v-else class="invisible text-sm">← Previous</div>
        <span class="text-sm text-gray-500">{{ members.total }} total formal members</span>
        <Link v-if="members.next_page_url" :href="members.next_page_url"
          class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-blue-600">
          Next →
        </Link>
        <div v-else class="invisible text-sm">Next →</div>
      </div>

    </div>
  </election-layout>
</template>

<script>
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import { Link } from '@inertiajs/vue3'
import _ from 'lodash'

export default {
  components: { Link, ElectionLayout },

  props: {
    members:      Object,
    organisation: Object,
    filters:      Object,
    stats:        Object,
  },

  data() {
    return {
      params: {
        name:      this.filters?.name      || '',
        email:     this.filters?.email     || '',
        status:    this.filters?.status    || '',
        field:     this.filters?.field     || 'created_at',
        direction: this.filters?.direction || 'desc',
      },
    }
  },

  watch: {
    params: {
      handler: _.debounce(function () {
        const params = Object.fromEntries(
          Object.entries(this.params).filter(([, v]) => v != null && v !== '')
        )
        this.$inertia.get(`/organisations/${this.organisation.slug}/members`, params, {
          replace: true,
          preserveState: true,
        })
      }, 300),
      deep: true,
    },
  },

  methods: {
    sort(field) {
      if (this.params.field === field) {
        this.params.direction = this.params.direction === 'asc' ? 'desc' : 'asc'
      } else {
        this.params.field = field
        this.params.direction = 'asc'
      }
    },

    statusClass(status) {
      const map = {
        active:  'bg-green-100 text-green-800',
        expired: 'bg-orange-100 text-orange-800',
        ended:   'bg-gray-100 text-gray-600',
        pending: 'bg-yellow-100 text-yellow-800',
      }
      return map[status] ?? 'bg-gray-100 text-gray-600'
    },

    isExpired(expiresAt) {
      if (!expiresAt) return false
      return new Date(expiresAt) < new Date()
    },

    formatDate(date) {
      if (!date) return '—'
      return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
      })
    },

    exportMembers() {
      const params = new URLSearchParams(
        Object.fromEntries(Object.entries(this.params).filter(([, v]) => v !== ''))
      )
      window.location.href = `/organisations/${this.organisation.slug}/members/export?${params.toString()}`
    },

    markAsPaid(memberId, memberName) {
      if (confirm(`Mark ${memberName} as paid?`)) {
        this.$inertia.patch(
          `/organisations/${this.organisation.slug}/members/${memberId}/mark-paid`,
          {},
          {
            preserveScroll: true,
            onSuccess: () => {
              // Page will refresh automatically showing updated status
            },
          }
        )
      }
    },
  },
}
</script>
