<template>
  <election-layout>
    <div class="m-2 min-h-screen bg-neutral-100 p-2">

      <!-- Header -->
      <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-2">
          <div>
            <h1 class="text-2xl font-bold text-neutral-900">Platform Participants — {{ organisation.name }}</h1>
            <p class="text-sm text-neutral-500 mt-1">
              Everyone with a platform role (owner, admin, commission, voter, member).
              Paid membership status is shown separately.
            </p>
          </div>
          <div class="flex items-center gap-3">
            <a
              :href="`/organisations/${organisation.slug}/members`"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-indigo-300 text-indigo-700 text-sm font-medium hover:bg-indigo-50 transition-colors"
            >
              Paid Members →
            </a>
            <a
              :href="`/organisations/${organisation.slug}`"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back
            </a>
          </div>
        </div>

        <!-- Stats -->
        <div class="mt-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
          <div class="bg-slate-50 rounded border-l-4 border-slate-400 p-3">
            <div class="text-xs text-neutral-500 uppercase tracking-wide font-medium">Total</div>
            <div class="text-2xl font-bold text-slate-700 mt-1">{{ stats.total }}</div>
          </div>
          <div v-for="role in roleOrder" :key="role"
               class="rounded border-l-4 p-3"
               :class="roleStatClass(role)">
            <div class="text-xs uppercase tracking-wide font-medium" :class="roleStatLabelClass(role)">{{ roleLabel(role) }}</div>
            <div class="text-2xl font-bold mt-1" :class="roleStatNumberClass(role)">
              {{ stats.role_counts[role] ?? 0 }}
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="mb-4 bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Name</label>
            <input
              v-model="params.name"
              type="text"
              class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Search by name…"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
            <input
              v-model="params.email"
              type="text"
              class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Search by email…"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Role</label>
            <select
              v-model="params.role"
              class="w-full rounded border border-neutral-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Roles</option>
              <option value="owner">Owner</option>
              <option value="admin">Admin</option>
              <option value="commission">Commission</option>
              <option value="voter">Voter</option>
              <option value="member">Participant (member role)</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="exportParticipants"
              class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors font-medium text-sm"
            >
              Export CSV
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination top -->
      <div class="flex items-center justify-between px-5 py-3 bg-white rounded-t-lg shadow-xs">
        <Link v-if="participants.prev_page_url" :href="participants.prev_page_url"
          class="flex items-center gap-2 text-sm font-medium text-neutral-600 hover:text-primary-600">
          ← Previous
        </Link>
        <div v-else class="invisible text-sm">← Previous</div>
        <span class="text-sm text-neutral-600">
          Page <strong>{{ participants.current_page }}</strong> of <strong>{{ participants.last_page }}</strong>
          &nbsp;({{ participants.total }} records)
        </span>
        <Link v-if="participants.next_page_url" :href="participants.next_page_url"
          class="flex items-center gap-2 text-sm font-medium text-neutral-600 hover:text-primary-600">
          Next →
        </Link>
        <div v-else class="invisible text-sm">Next →</div>
      </div>

      <!-- Table -->
      <div class="bg-white shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-700 text-white text-left">
              <th class="px-4 py-3 font-semibold">Name</th>
              <th class="px-4 py-3 font-semibold">Email</th>
              <th class="px-4 py-3 font-semibold cursor-pointer hover:bg-slate-600" @click="sort('role')">
                Platform Role
                <span v-if="params.field === 'role'">{{ params.direction === 'asc' ? '↑' : '↓' }}</span>
              </th>
              <th class="px-4 py-3 font-semibold">Paid Member</th>
              <th class="px-4 py-3 font-semibold cursor-pointer hover:bg-slate-600" @click="sort('created_at')">
                Joined
                <span v-if="params.field === 'created_at'">{{ params.direction === 'asc' ? '↑' : '↓' }}</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="!participants.data || participants.data.length === 0">
              <td colspan="5" class="px-4 py-12 text-center text-neutral-400">
                No participants found.
              </td>
            </tr>
            <tr
              v-for="(p, index) in participants.data"
              :key="p.id"
              :class="index % 2 === 0 ? 'bg-white' : 'bg-neutral-50'"
              class="hover:bg-slate-50 transition-colors border-b border-neutral-100"
            >
              <td class="px-4 py-3 font-medium text-neutral-900">{{ p.name }}</td>
              <td class="px-4 py-3 text-neutral-600">{{ p.email }}</td>
              <td class="px-4 py-3">
                <span :class="roleClass(p.role)" class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize">
                  {{ roleLabel(p.role) }}
                </span>
              </td>
              <td class="px-4 py-3">
                <span v-if="p.is_paid_member"
                  class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  Active member
                </span>
                <span v-else-if="p.member_status"
                  class="px-2.5 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold capitalize">
                  {{ p.member_status }}
                </span>
                <span v-else class="text-neutral-400 text-xs">No membership</span>
              </td>
              <td class="px-4 py-3 text-neutral-500 text-xs">{{ formatDate(p.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination bottom -->
      <div class="flex items-center justify-between px-5 py-3 bg-white rounded-b-lg shadow-xs">
        <Link v-if="participants.prev_page_url" :href="participants.prev_page_url"
          class="flex items-center gap-2 text-sm font-medium text-neutral-600 hover:text-primary-600">
          ← Previous
        </Link>
        <div v-else class="invisible text-sm">← Previous</div>
        <span class="text-sm text-neutral-500">{{ participants.total }} total participants</span>
        <Link v-if="participants.next_page_url" :href="participants.next_page_url"
          class="flex items-center gap-2 text-sm font-medium text-neutral-600 hover:text-primary-600">
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
    participants: Object,
    organisation: Object,
    filters:      Object,
    stats:        Object,
  },

  data() {
    return {
      roleOrder: ['owner', 'admin', 'commission', 'voter', 'member'],
      params: {
        name:      this.filters?.name      || '',
        email:     this.filters?.email     || '',
        role:      this.filters?.role      || '',
        field:     this.filters?.field     || 'role',
        direction: this.filters?.direction || 'asc',
      },
    }
  },

  watch: {
    params: {
      handler: _.debounce(function () {
        const params = Object.fromEntries(
          Object.entries(this.params).filter(([, v]) => v != null && v !== '')
        )
        this.$inertia.get(`/organisations/${this.organisation.slug}/participants`, params, {
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

    roleLabel(role) {
      // 'member' platform role = basic access, displayed as 'Participant' to avoid
      // confusion with formal paid membership
      return role === 'member' ? 'Participant' : role
    },

    roleClass(role) {
      const map = {
        owner:      'bg-danger-100 text-danger-800',
        admin:      'bg-orange-100 text-orange-800',
        commission: 'bg-primary-100 text-primary-800',
        voter:      'bg-teal-100 text-teal-800',
        member:     'bg-purple-100 text-purple-800',
      }
      return map[role] ?? 'bg-neutral-100 text-neutral-600'
    },

    roleStatClass(role) {
      const map = {
        owner:      'bg-danger-50 border-danger-400',
        admin:      'bg-orange-50 border-orange-400',
        commission: 'bg-primary-50 border-primary-400',
        voter:      'bg-teal-50 border-teal-400',
        member:     'bg-purple-50 border-purple-400',
      }
      return map[role] ?? 'bg-neutral-50 border-neutral-300'
    },

    roleStatLabelClass(role) {
      const map = {
        owner:      'text-danger-600',
        admin:      'text-orange-600',
        commission: 'text-primary-600',
        voter:      'text-teal-600',
        member:     'text-purple-600',
      }
      return map[role] ?? 'text-neutral-500'
    },

    roleStatNumberClass(role) {
      const map = {
        owner:      'text-danger-700',
        admin:      'text-orange-700',
        commission: 'text-primary-700',
        voter:      'text-teal-700',
        member:     'text-purple-700',
      }
      return map[role] ?? 'text-neutral-700'
    },

    formatDate(iso) {
      if (!iso) return '—'
      return new Date(iso).toLocaleDateString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric',
      })
    },

    exportParticipants() {
      const params = new URLSearchParams(
        Object.fromEntries(Object.entries(this.params).filter(([, v]) => v !== ''))
      )
      window.location.href = `/organisations/${this.organisation.slug}/participants/export?${params.toString()}`
    },
  },
}
</script>

