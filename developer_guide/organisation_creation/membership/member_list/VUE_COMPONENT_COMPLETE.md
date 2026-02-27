# Members/Index.vue - Complete Component Code

**File**: `resources/js/Pages/Members/Index.vue`
**Lines**: ~650 (estimated)
**Status**: Ready to implement

---

## Complete Vue Component

```vue
<template>
  <election-layout>
    <div class="min-h-screen bg-gray-100 p-2 md:p-4">

      <!-- Organization Header -->
      <div class="mb-6 bg-white rounded-lg shadow-xs p-4 md:p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
          <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
              {{ organization.name }} - Members
            </h1>
            <p class="text-gray-600 mt-1">
              Manage and view organization members
            </p>
          </div>
          <Link
            :href="`/organizations/${organization.slug}`"
            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Organization
          </Link>
        </div>

        <!-- Statistics Cards -->
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="text-sm text-gray-600 mb-1">Total Members</div>
            <div class="text-2xl font-bold text-blue-600">{{ stats.total_members }}</div>
          </div>
          <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="text-sm text-gray-600 mb-1">Admins</div>
            <div class="text-2xl font-bold text-red-600">{{ stats.admins_count }}</div>
          </div>
          <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-sm text-gray-600 mb-1">Commission</div>
            <div class="text-2xl font-bold text-purple-600">{{ stats.commission_count }}</div>
          </div>
          <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-sm text-gray-600 mb-1">Voters</div>
            <div class="text-2xl font-bold text-green-600">{{ stats.voters_count }}</div>
          </div>
        </div>
      </div>

      <!-- Filters Section -->
      <div class="mb-4 bg-white rounded-lg shadow-xs p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Name Search -->
          <div>
            <label for="name-search" class="block text-sm font-medium text-gray-700 mb-2">
              Search by Name
            </label>
            <input
              id="name-search"
              v-model="params.name"
              type="text"
              class="w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500"
              placeholder="Enter name..."
            />
          </div>

          <!-- Email Search -->
          <div>
            <label for="email-search" class="block text-sm font-medium text-gray-700 mb-2">
              Search by Email
            </label>
            <input
              id="email-search"
              v-model="params.email"
              type="text"
              class="w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500"
              placeholder="Enter email..."
            />
          </div>

          <!-- Role Filter -->
          <div>
            <label for="role-filter" class="block text-sm font-medium text-gray-700 mb-2">
              Filter by Role
            </label>
            <select
              id="role-filter"
              v-model="params.role"
              class="w-full rounded-md border-gray-300 shadow-xs focus:border-blue-500 focus:ring-blue-500"
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
              class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center"
              :disabled="members.total === 0"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              Export CSV
            </button>
          </div>
        </div>

        <!-- Active Filters Display -->
        <div v-if="hasActiveFilters" class="mt-4 flex items-center gap-2 flex-wrap">
          <span class="text-sm text-gray-600">Active filters:</span>
          <span v-if="params.name" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-sm text-sm">
            Name: {{ params.name }}
            <button @click="params.name = ''" class="ml-2 hover:text-blue-900">×</button>
          </span>
          <span v-if="params.email" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-sm text-sm">
            Email: {{ params.email }}
            <button @click="params.email = ''" class="ml-2 hover:text-blue-900">×</button>
          </span>
          <span v-if="params.role" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-sm text-sm">
            Role: {{ params.role }}
            <button @click="params.role = ''" class="ml-2 hover:text-blue-900">×</button>
          </span>
          <button
            @click="clearFilters"
            class="text-sm text-blue-600 hover:text-blue-700 underline"
          >
            Clear all
          </button>
        </div>
      </div>

      <!-- Pagination Top -->
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
          Page <span class="font-semibold text-gray-900">{{ members.current_page }}</span>
          of <span class="font-semibold text-gray-900">{{ members.last_page }}</span>
          <span class="mx-2">|</span>
          <span class="font-semibold text-gray-900">{{ members.total }}</span> total members
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
      <div class="bg-white shadow-xs overflow-x-auto">
        <table class="w-full table-auto">
          <thead>
            <tr class="bg-blue-600 text-white">
              <th class="px-4 py-3 text-left">
                <button @click="sort('id')" class="flex items-center gap-2 hover:text-blue-100">
                  <span>ID</span>
                  <SortIcon :active="params.field === 'id'" :direction="params.direction" />
                </button>
              </th>
              <th class="px-4 py-3 text-left">
                <button @click="sort('name')" class="flex items-center gap-2 hover:text-blue-100">
                  <span>Name</span>
                  <SortIcon :active="params.field === 'name'" :direction="params.direction" />
                </button>
              </th>
              <th class="px-4 py-3 text-left">
                <button @click="sort('email')" class="flex items-center gap-2 hover:text-blue-100">
                  <span>Email</span>
                  <SortIcon :active="params.field === 'email'" :direction="params.direction" />
                </button>
              </th>
              <th class="px-4 py-3 text-left">
                <span>Region</span>
              </th>
              <th class="px-4 py-3 text-left">
                <button @click="sort('role')" class="flex items-center gap-2 hover:text-blue-100">
                  <span>Role</span>
                  <SortIcon :active="params.field === 'role'" :direction="params.direction" />
                </button>
              </th>
              <th class="px-4 py-3 text-left">
                <button @click="sort('assigned_at')" class="flex items-center gap-2 hover:text-blue-100">
                  <span>Member Since</span>
                  <SortIcon :active="params.field === 'assigned_at'" :direction="params.direction" />
                </button>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="member in members.data"
              :key="member.id"
              class="border-b border-gray-200 hover:bg-gray-50 transition-colors"
            >
              <td class="px-4 py-3 text-sm text-gray-900">
                {{ member.id }}
              </td>
              <td class="px-4 py-3">
                <div class="font-medium text-gray-900">{{ member.name }}</div>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">
                {{ member.email }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">
                {{ member.state || 'N/A' }}
              </td>
              <td class="px-4 py-3">
                <span :class="roleClass(member.role)" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                  {{ roleLabel(member.role) }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">
                {{ formatDate(member.assigned_at) }}
              </td>
            </tr>
            <!-- Empty State -->
            <tr v-if="members.data.length === 0">
              <td colspan="6" class="px-4 py-12 text-center">
                <div class="text-gray-400">
                  <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">No members found</h3>
                  <p class="text-gray-500">
                    {{ hasActiveFilters ? 'Try adjusting your filters' : 'No members have been added to this organization yet' }}
                  </p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Bottom -->
      <div class="flex items-center justify-between px-5 py-4 bg-white rounded-b-lg shadow-xs">
        <!-- Same as top pagination -->
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
          Page <span class="font-semibold text-gray-900">{{ members.current_page }}</span>
          of <span class="font-semibold text-gray-900">{{ members.last_page }}</span>
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
import { Link } from '@inertiajs/vue3-vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import _ from 'lodash'

// Sort Icon Component (inline)
const SortIcon = {
  props: {
    active: Boolean,
    direction: String
  },
  template: `
    <svg v-if="active" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
      <path v-if="direction === 'asc'" fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
      <path v-else fill-rule="evenodd" d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
    </svg>
    <svg v-else class="w-4 h-4 opacity-30" fill="currentColor" viewBox="0 0 20 20">
      <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
    </svg>
  `
}

export default {
  components: {
    Link,
    ElectionLayout,
    SortIcon
  },

  props: {
    members: {
      type: Object,
      required: true
    },
    organization: {
      type: Object,
      required: true
    },
    filters: {
      type: Object,
      default: () => ({})
    },
    currentUser: {
      type: Object,
      required: true
    },
    stats: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      params: {
        name: this.filters?.name || '',
        email: this.filters?.email || '',
        role: this.filters?.role || '',
        field: this.filters?.field || 'assigned_at',
        direction: this.filters?.direction || 'desc'
      }
    }
  },

  computed: {
    hasActiveFilters() {
      return !!(this.params.name || this.params.email || this.params.role)
    }
  },

  watch: {
    params: {
      handler: _.debounce(function() {
        // Filter out empty values
        const params = Object.fromEntries(
          Object.entries(this.params).filter(([_, v]) => v != null && v !== '')
        )

        // Navigate with Inertia
        this.$inertia.get('/members/index', params, {
          replace: true,
          preserveState: true,
          preserveScroll: true
        })
      }, 300),
      deep: true
    }
  },

  methods: {
    sort(field) {
      // If clicking same field, toggle direction
      if (this.params.field === field) {
        this.params.direction = this.params.direction === 'desc' ? 'asc' : 'desc'
      } else {
        // New field, default to ascending
        this.params.field = field
        this.params.direction = 'asc'
      }
    },

    roleClass(role) {
      const classes = {
        admin: 'bg-red-100 text-red-800 border border-red-200',
        commission: 'bg-purple-100 text-purple-800 border border-purple-200',
        voter: 'bg-green-100 text-green-800 border border-green-200'
      }
      return classes[role] || 'bg-gray-100 text-gray-800 border border-gray-200'
    },

    roleLabel(role) {
      const labels = {
        admin: 'Admin',
        commission: 'Commission',
        voter: 'Voter'
      }
      return labels[role] || role
    },

    formatDate(date) {
      if (!date) return 'N/A'
      return new Date(date).toLocaleDateString('de-DE', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },

    clearFilters() {
      this.params.name = ''
      this.params.email = ''
      this.params.role = ''
    },

    exportMembers() {
      // Build query string from current filters
      const query = new URLSearchParams(this.params).toString()
      window.location.href = `/members/export?${query}`
    }
  }
}
</script>
```

---

## Key Features Implemented

### 1. Organization Context
- Shows organization name in header
- Link back to organization dashboard
- Clear context for users

### 2. Statistics Dashboard
- Total members count
- Role-based counts (admin, commission, voters)
- Color-coded cards for quick visualization

### 3. Advanced Filtering
- Name search with debounce
- Email search
- Role dropdown filter
- Active filters display with clear buttons

### 4. Sortable Table
- Click column headers to sort
- Visual indicators for active sort
- Supports: ID, Name, Email, Role, Member Since

### 5. Role Display
- Color-coded badges
- Clear labels
- Visual differentiation

### 6. Responsive Design
- Mobile-friendly layout
- Grid system for stats
- Responsive table

### 7. Export Functionality
- CSV export button
- Includes current filters
- Disabled when no members

### 8. Empty State
- Helpful message when no members
- Different messages for filtered vs empty org

### 9. Pagination
- Top and bottom controls
- Page counter
- Total count display

---

## Improvements Over User/Index.vue

1. ✅ Organization header and context
2. ✅ Statistics dashboard
3. ✅ Email search field
4. ✅ Role filter dropdown
5. ✅ Role column with badges
6. ✅ Member since column
7. ✅ Active filters display
8. ✅ Clear filters functionality
9. ✅ Better empty state
10. ✅ Export button
11. ✅ Responsive design
12. ✅ Inline SortIcon component
13. ✅ Better sort logic (asc on first click)
14. ✅ Total count in pagination
15. ✅ Preserve scroll on filter change

---

## Usage

```bash
# Create directory
mkdir -p resources/js/Pages/Members

# Create file
touch resources/js/Pages/Members/Index.vue

# Copy the code above into the file
```

---

**Status**: Ready to implement
**Lines**: ~650
**Dependencies**: Inertia.js, Vue 3, Lodash, Tailwind CSS
