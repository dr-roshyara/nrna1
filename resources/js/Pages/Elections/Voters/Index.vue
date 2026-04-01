<template>
  <div class="voters-shell">
    <PublicDigitHeader />

    <div class="voters-body">

      <!-- ═══════════════════════════════════════════════════════════
           COMMAND SIDEBAR — election context + assign actions
      ═══════════════════════════════════════════════════════════ -->
      <aside class="voters-sidebar">

        <!-- Election identity -->
        <div class="sidebar-section">
          <p class="sidebar-eyebrow">
            <a :href="route('organisations.show', organisation.slug)" class="sidebar-back">
              ← {{ organisation.name }}
            </a>
          </p>
          <h1 class="sidebar-title">{{ election.name }}</h1>
          <div class="sidebar-badges">
            <span class="badge-status" :class="`badge-${election.status}`">
              {{ election.status }}
            </span>
            <span class="badge-type">{{ election.type }}</span>
          </div>
        </div>

        <!-- Divider -->
        <div class="sidebar-rule"></div>

        <!-- Stats register -->
        <div class="sidebar-section">
          <p class="sidebar-label">VOTER REGISTER</p>
          <div class="stat-stack">
            <div class="stat-row">
              <span class="stat-num stat-blue">{{ stats.active_voters ?? 0 }}</span>
              <span class="stat-desc">Active</span>
            </div>
            <div class="stat-row">
              <span class="stat-num stat-amber">{{ stats.eligible_voters ?? 0 }}</span>
              <span class="stat-desc">Eligible</span>
            </div>
            <div class="stat-row">
              <span class="stat-num stat-muted">{{ stats.by_status?.inactive ?? 0 }}</span>
              <span class="stat-desc">Suspended</span>
            </div>
            <div class="stat-row">
              <span class="stat-num stat-dim">{{ stats.by_status?.removed ?? 0 }}</span>
              <span class="stat-desc">Removed</span>
            </div>
          </div>
        </div>

        <div class="sidebar-rule"></div>

        <!-- Assign from members -->
        <div class="sidebar-section sidebar-section--grow">
          <p class="sidebar-label">ASSIGN VOTERS</p>

          <div v-if="unassignedMembers.length > 0">
            <input
              v-model="memberSearch"
              type="text"
              placeholder="Search members…"
              class="sidebar-input"
            />

            <div class="member-list">
              <label
                v-for="member in filteredMembers"
                :key="member.id"
                class="member-row"
                :class="{ 'member-row--checked': selectedMemberIds.includes(member.id) }"
              >
                <input
                  type="checkbox"
                  :value="member.id"
                  v-model="selectedMemberIds"
                  class="member-check"
                />
                <div class="member-avatar">{{ member.name.charAt(0).toUpperCase() }}</div>
                <div class="member-info">
                  <p class="member-name">{{ member.name }}</p>
                  <p class="member-email">{{ member.email }}</p>
                </div>
              </label>
              <p v-if="filteredMembers.length === 0" class="member-empty">No members found</p>
            </div>

            <button
              @click="bulkAssign"
              :disabled="selectedMemberIds.length === 0 || assigning"
              class="btn-assign"
            >
              <span v-if="assigning">Assigning…</span>
              <span v-else>
                Assign
                <span v-if="selectedMemberIds.length > 0" class="btn-count">{{ selectedMemberIds.length }}</span>
              </span>
            </button>
          </div>

          <p v-else class="sidebar-empty">All members assigned</p>

          <!-- UUID fallback -->
          <details class="uuid-details">
            <summary class="uuid-summary">Assign by User ID</summary>
            <form @submit.prevent="assignSingle" class="uuid-form">
              <input
                v-model="assignUserId"
                type="text"
                placeholder="Paste UUID…"
                class="sidebar-input sidebar-input--mono"
              />
              <p v-if="$page.props.errors?.user_id" class="uuid-error">{{ $page.props.errors.user_id }}</p>
              <button type="submit" :disabled="!assignUserId.trim() || assigning" class="btn-uuid">Assign</button>
            </form>
          </details>
        </div>

        <!-- Export at bottom -->
        <div class="sidebar-section">
          <a :href="exportUrl" class="btn-export">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export CSV
          </a>
        </div>

      </aside>

      <!-- ═══════════════════════════════════════════════════════════
           REGISTER TABLE — the official voter list
      ═══════════════════════════════════════════════════════════ -->
      <main class="voters-main">

        <!-- Flash -->
        <div v-if="$page.props.flash?.success" class="flash flash--ok">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash?.error" class="flash flash--err">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          {{ $page.props.flash.error }}
        </div>
        <div v-if="$page.props.flash?.bulk_result" class="flash flash--info">
          Bulk assign complete —
          <strong>{{ $page.props.flash.bulk_result.success }}</strong> added,
          <strong>{{ $page.props.flash.bulk_result.already_existing }}</strong> already assigned,
          <strong>{{ $page.props.flash.bulk_result.invalid }}</strong> skipped.
        </div>

        <!-- Filter bar -->
        <div class="filter-bar">
          <div class="filter-search">
            <svg class="filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
              v-model="searchQuery"
              @input="debouncedFilter"
              type="text"
              placeholder="Search by name or email…"
              class="filter-input"
            />
          </div>
          <select v-model="statusFilter" @change="applyFilters" class="filter-select">
            <option value="">All statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Suspended</option>
            <option value="removed">Removed</option>
          </select>
          <select v-model="perPage" @change="applyFilters" class="filter-select filter-select--narrow">
            <option :value="25">25 / page</option>
            <option :value="50">50 / page</option>
            <option :value="100">100 / page</option>
          </select>
          <button v-if="searchQuery || statusFilter" @click="clearFilters" class="filter-clear">Clear</button>
          <span v-if="voters.total" class="filter-count">{{ voters.from }}–{{ voters.to }} of {{ voters.total }}</span>
        </div>

        <!-- The register -->
        <div class="register-wrap">
          <table class="register">
            <thead>
              <tr>
                <th class="reg-th reg-th--no">#</th>
                <th class="reg-th">Voter</th>
                <th class="reg-th">Status</th>
                <th class="reg-th">Voted</th>
                <th class="reg-th">Assigned</th>
                <th class="reg-th reg-th--actions">Actions</th>
              </tr>
            </thead>
            <tbody>
              <template v-for="(membership, idx) in voters.data" :key="membership.id">
              <tr
                class="reg-row"
                :class="{ 'reg-row--voted': membership.has_voted, 'reg-row--removed': membership.status === 'removed', 'reg-row--suspension-pending': membership.suspension_status === 'proposed' }"
              >
                <!-- Row number -->
                <td class="reg-td reg-td--no">
                  {{ ((voters.current_page - 1) * voters.per_page) + idx + 1 }}
                </td>

                <!-- Voter -->
                <td class="reg-td">
                  <div class="voter-cell">
                    <div class="voter-avatar">{{ (membership.user?.name ?? '?').charAt(0).toUpperCase() }}</div>
                    <div>
                      <p class="voter-name">{{ membership.user?.name ?? '—' }}</p>
                      <p class="voter-email">{{ membership.user?.email ?? '—' }}</p>
                    </div>
                  </div>
                </td>

                <!-- Status -->
                <td class="reg-td">
                  <span
                    v-if="membership.suspension_status === 'proposed'"
                    class="status-pill status-pill--pending"
                  >
                    <span class="status-dot"></span>
                    Pending Suspension
                  </span>
                  <span v-else class="status-pill" :class="`status-pill--${membership.status}`">
                    <span class="status-dot"></span>
                    {{ statusLabel(membership.status) }}
                  </span>
                </td>

                <!-- Voted -->
                <td class="reg-td">
                  <span v-if="membership.has_voted" class="voted-mark" aria-label="Voted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Cast
                  </span>
                  <span v-else class="voted-none">—</span>
                </td>

                <!-- Assigned date -->
                <td class="reg-td">
                  <span class="date-mono">{{ formatDate(membership.assigned_at) }}</span>
                </td>

                <!-- Actions -->
                <td class="reg-td reg-td--actions">
                  <div class="action-row" v-if="!membership.has_voted">
                    <!-- Approve (invited → active) -->
                    <button
                      v-if="membership.status !== 'active' && membership.status !== 'removed'"
                      @click="approveVoter(membership)"
                      :disabled="loadingId === membership.id"
                      class="act-btn act-btn--approve"
                    >Approve</button>

                    <!-- Propose suspension (active, not yet proposed) -->
                    <button
                      v-if="membership.status === 'active' && membership.suspension_status !== 'proposed'"
                      @click="proposeSuspension(membership)"
                      :disabled="loadingId === membership.id"
                      class="act-btn act-btn--propose"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                      Propose
                    </button>

                    <!-- Confirm suspension (pending, not by current user) -->
                    <button
                      v-if="membership.suspension_status === 'proposed' && membership.suspension_proposed_by !== authUserName"
                      @click="confirmSuspension(membership)"
                      :disabled="loadingId === membership.id"
                      class="act-btn act-btn--confirm"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      Confirm
                    </button>

                    <!-- Cancel proposal (proposer only) -->
                    <button
                      v-if="membership.suspension_status === 'proposed' && membership.suspension_proposed_by === authUserName"
                      @click="cancelProposal(membership)"
                      :disabled="loadingId === membership.id"
                      class="act-btn act-btn--cancel"
                    >Cancel</button>

                    <!-- Remove -->
                    <button
                      v-if="membership.status !== 'removed' && membership.suspension_status !== 'proposed'"
                      @click="removeVoter(membership)"
                      :disabled="loadingId === membership.id"
                      class="act-btn act-btn--remove"
                    >Remove</button>
                  </div>
                  <span v-else class="act-voted-lock" title="Vote cast — no changes permitted">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  </span>
                </td>
              </tr>

              <!-- Suspension banner row -->
              <tr
                v-if="membership.suspension_status === 'proposed'"
                class="suspension-info-row"
              >
                <td colspan="6">
                  <div class="suspension-banner">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <span>Suspension proposed by <strong>{{ membership.suspension_proposed_by }}</strong> · {{ formatDate(membership.suspension_proposed_at) }}</span>
                    <span class="suspension-banner-warn">⚠ Awaiting confirmation from a second committee member</span>
                  </div>
                </td>
              </tr>
              </template>

              <!-- Empty -->
              <tr v-if="voters.data.length === 0">
                <td colspan="6" class="reg-empty">
                  <div class="empty-state">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p>{{ searchQuery || statusFilter ? 'No voters match your filters.' : 'The register is empty — assign voters using the panel.' }}</p>
                    <button v-if="searchQuery || statusFilter" @click="clearFilters" class="empty-clear">Clear filters</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="voters.links?.length > 3" class="pagination">
          <span class="pagination-info">{{ voters.from }}–{{ voters.to }} of {{ voters.total }}</span>
          <div class="pagination-links">
            <template v-for="link in voters.links" :key="link.label">
              <a
                v-if="link.url"
                :href="link.url"
                class="pag-btn"
                :class="{ 'pag-btn--active': link.active }"
                v-html="link.label"
              />
              <span v-else class="pag-btn pag-btn--disabled" v-html="link.label" />
            </template>
          </div>
        </div>

      </main>
    </div>

    <PublicDigitFooter />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

const props = defineProps({
  election:          { type: Object, required: true },
  organisation:      { type: Object, required: true },
  voters:            { type: Object, required: true },
  stats:             { type: Object, required: true },
  unassignedMembers: { type: Array,  default: () => [] },
  filters:           { type: Object, default: () => ({}) },
})

const authUserName      = computed(() => usePage().props.user?.name ?? usePage().props.auth?.user?.name)
const assignUserId      = ref('')
const assigning         = ref(false)
const loadingId         = ref(null)
const selectedMemberIds = ref([])
const memberSearch      = ref('')
const searchQuery       = ref(props.filters?.search ?? '')
const statusFilter      = ref(props.filters?.status ?? '')
const perPage           = ref(props.filters?.per_page ?? 50)

const exportUrl = computed(() =>
  route('elections.voters.export', { organisation: props.organisation.slug, election: props.election.slug })
)

const filteredMembers = computed(() => {
  const q = memberSearch.value.toLowerCase()
  if (!q) return props.unassignedMembers
  return props.unassignedMembers.filter(m =>
    m.name.toLowerCase().includes(q) || m.email.toLowerCase().includes(q)
  )
})

const statusLabel = (s) => ({ active: 'Active', inactive: 'Suspended', invited: 'Invited', removed: 'Removed' }[s] ?? s)

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—'

// Filters
let filterTimer = null
const debouncedFilter = () => { clearTimeout(filterTimer); filterTimer = setTimeout(applyFilters, 350) }
const applyFilters = () => {
  router.get(
    route('elections.voters.index', { organisation: props.organisation.slug, election: props.election.slug }),
    {
      search:    searchQuery.value || undefined,
      status:    statusFilter.value || undefined,
      per_page:  perPage.value !== 50 ? perPage.value : undefined,
    },
    { preserveScroll: true, replace: true }
  )
}
const clearFilters = () => { searchQuery.value = ''; statusFilter.value = ''; perPage.value = 50; applyFilters() }

// Assign
const assignSingle = () => {
  assigning.value = true
  router.post(
    route('elections.voters.store', { organisation: props.organisation.slug, election: props.election.slug }),
    { user_id: assignUserId.value.trim() },
    { preserveScroll: true, onSuccess: () => { assignUserId.value = '' }, onFinish: () => { assigning.value = false } }
  )
}

const bulkAssign = () => {
  if (!selectedMemberIds.value.length) return
  assigning.value = true
  router.post(
    route('elections.voters.bulk', { organisation: props.organisation.slug, election: props.election.slug }),
    { user_ids: selectedMemberIds.value },
    { preserveScroll: true, onSuccess: () => { selectedMemberIds.value = [] }, onFinish: () => { assigning.value = false } }
  )
}

// Row actions
const approveVoter = (m) => {
  loadingId.value = m.id
  router.post(route('elections.voters.approve', { organisation: props.organisation.slug, election: props.election.slug, membership: m.id }), {}, { preserveScroll: true, onFinish: () => { loadingId.value = null } })
}
const suspendVoter = (m) => {
  if (!confirm(`Suspend ${m.user?.name ?? 'this voter'}?`)) return
  loadingId.value = m.id
  router.post(route('elections.voters.suspend', { organisation: props.organisation.slug, election: props.election.slug, membership: m.id }), {}, { preserveScroll: true, onFinish: () => { loadingId.value = null } })
}
const removeVoter = (m) => {
  if (!confirm(`Remove ${m.user?.name ?? 'this voter'} from the election?`)) return
  loadingId.value = m.id
  router.delete(route('elections.voters.destroy', { organisation: props.organisation.slug, election: props.election.slug, membership: m.id }), { preserveScroll: true, onFinish: () => { loadingId.value = null } })
}

const proposeSuspension = (m) => {
  if (!confirm(`Propose to suspend ${m.user?.name ?? 'this voter'}? A second committee member must confirm before the suspension takes effect.`)) return
  loadingId.value = m.id
  router.post(route('elections.voters.propose-suspension', { organisation: props.organisation.slug, election: props.election.slug, membership: m.id }), {}, { preserveScroll: true, onFinish: () => { loadingId.value = null } })
}

const confirmSuspension = (m) => {
  if (!confirm(`Confirm suspension of ${m.user?.name ?? 'this voter'}? This is your second-committee-member confirmation.`)) return
  loadingId.value = m.id
  router.post(route('elections.voters.confirm-suspension', { organisation: props.organisation.slug, election: props.election.slug, membership: m.id }), {}, { preserveScroll: true, onFinish: () => { loadingId.value = null } })
}

const cancelProposal = (m) => {
  if (!confirm(`Cancel the suspension proposal for ${m.user?.name ?? 'this voter'}?`)) return
  loadingId.value = m.id
  router.post(route('elections.voters.cancel-proposal', { organisation: props.organisation.slug, election: props.election.slug, membership: m.id }), {}, { preserveScroll: true, onFinish: () => { loadingId.value = null } })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=JetBrains+Mono:wght@400;500&family=Outfit:wght@300;400;500;600&display=swap');

/* ── Shell ─────────────────────────────────────────────────────── */
.voters-shell {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f5f4f0;
  font-family: 'Outfit', sans-serif;
}

.voters-body {
  display: flex;
  flex: 1;
  min-height: 0;
}

/* ── Sidebar ────────────────────────────────────────────────────── */
.voters-sidebar {
  width: 300px;
  flex-shrink: 0;
  background: #0d1117;
  color: #e6e1d6;
  display: flex;
  flex-direction: column;
  min-height: calc(100vh - 64px);
  border-right: 1px solid #1e2530;
}

.sidebar-section {
  padding: 1.5rem 1.5rem;
}
.sidebar-section--grow { flex: 1; overflow: hidden; display: flex; flex-direction: column; }

.sidebar-eyebrow { margin-bottom: 0.5rem; }
.sidebar-back {
  font-size: 0.7rem;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #6b7280;
  text-decoration: none;
  transition: color 0.15s;
}
.sidebar-back:hover { color: #d4a847; }

.sidebar-title {
  font-family: 'DM Serif Display', serif;
  font-size: 1.35rem;
  line-height: 1.3;
  color: #f0ebe0;
  margin-bottom: 0.75rem;
}

.sidebar-badges { display: flex; gap: 0.5rem; flex-wrap: wrap; }

.badge-status, .badge-type {
  font-size: 0.65rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 0.2rem 0.65rem;
  border-radius: 999px;
}
.badge-type { background: #1e2530; color: #6b7280; }
.badge-active  { background: #14532d; color: #86efac; }
.badge-planned { background: #451a03; color: #fcd34d; }
.badge-completed { background: #1e2530; color: #94a3b8; }

.sidebar-rule {
  height: 1px;
  background: linear-gradient(to right, transparent, #1e2530 20%, #1e2530 80%, transparent);
  margin: 0 1rem;
}

.sidebar-label {
  font-size: 0.6rem;
  font-weight: 600;
  letter-spacing: 0.15em;
  color: #374151;
  text-transform: uppercase;
  margin-bottom: 1rem;
}

/* Stats */
.stat-stack { display: flex; flex-direction: column; gap: 0.5rem; }
.stat-row { display: flex; align-items: baseline; gap: 0.75rem; }
.stat-num {
  font-family: 'DM Serif Display', serif;
  font-size: 2rem;
  line-height: 1;
  min-width: 2.5rem;
}
.stat-blue  { color: #60a5fa; }
.stat-amber { color: #d4a847; }
.stat-muted { color: #9ca3af; }
.stat-dim   { color: #374151; }
.stat-desc  { font-size: 0.75rem; color: #4b5563; font-weight: 500; }

/* Member list */
.sidebar-input {
  width: 100%;
  background: #161b22;
  border: 1px solid #1e2530;
  border-radius: 6px;
  padding: 0.5rem 0.75rem;
  font-size: 0.8rem;
  color: #e6e1d6;
  outline: none;
  font-family: 'Outfit', sans-serif;
  margin-bottom: 0.75rem;
  transition: border-color 0.15s;
}
.sidebar-input:focus { border-color: #d4a847; }
.sidebar-input--mono { font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; }

.member-list {
  flex: 1;
  overflow-y: auto;
  max-height: 220px;
  border: 1px solid #1e2530;
  border-radius: 6px;
  margin-bottom: 0.75rem;
  scrollbar-width: thin;
  scrollbar-color: #1e2530 transparent;
}

.member-row {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.5rem 0.75rem;
  cursor: pointer;
  transition: background 0.12s;
  border-bottom: 1px solid #0d1117;
}
.member-row:last-child { border-bottom: none; }
.member-row:hover { background: #161b22; }
.member-row--checked { background: #1a2438; }

.member-check { accent-color: #d4a847; flex-shrink: 0; }

.member-avatar {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background: #1e2e4a;
  color: #60a5fa;
  font-size: 0.7rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.member-info { min-width: 0; }
.member-name { font-size: 0.75rem; font-weight: 500; color: #e6e1d6; truncate: true; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.member-email { font-size: 0.65rem; color: #4b5563; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.member-empty { font-size: 0.75rem; color: #374151; text-align: center; padding: 1rem; }

.btn-assign {
  width: 100%;
  background: #d4a847;
  color: #0d1117;
  border: none;
  border-radius: 6px;
  padding: 0.6rem 1rem;
  font-size: 0.8rem;
  font-weight: 700;
  font-family: 'Outfit', sans-serif;
  cursor: pointer;
  transition: background 0.15s, opacity 0.15s;
  letter-spacing: 0.03em;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}
.btn-assign:hover:not(:disabled) { background: #e6ba5a; }
.btn-assign:disabled { background: #1e2530; color: #374151; cursor: not-allowed; }
.btn-count {
  background: #0d1117;
  color: #d4a847;
  border-radius: 999px;
  padding: 0.1rem 0.5rem;
  font-size: 0.7rem;
  font-weight: 700;
}

.sidebar-empty { font-size: 0.75rem; color: #374151; font-style: italic; }

/* UUID fallback */
.uuid-details { margin-top: 1rem; }
.uuid-summary {
  font-size: 0.7rem;
  color: #374151;
  cursor: pointer;
  letter-spacing: 0.05em;
  user-select: none;
}
.uuid-summary:hover { color: #6b7280; }
.uuid-form { margin-top: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; }
.uuid-error { font-size: 0.7rem; color: #f87171; }
.btn-uuid {
  background: #1e2530;
  color: #9ca3af;
  border: 1px solid #374151;
  border-radius: 5px;
  padding: 0.45rem 0.75rem;
  font-size: 0.75rem;
  font-family: 'Outfit', sans-serif;
  cursor: pointer;
  transition: background 0.12s;
}
.btn-uuid:hover:not(:disabled) { background: #2d3748; color: #e6e1d6; }
.btn-uuid:disabled { opacity: 0.4; cursor: not-allowed; }

/* Export */
.btn-export {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.7rem;
  font-weight: 500;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  color: #4b5563;
  text-decoration: none;
  border: 1px solid #1e2530;
  border-radius: 5px;
  padding: 0.4rem 0.75rem;
  transition: color 0.15s, border-color 0.15s;
}
.btn-export:hover { color: #d4a847; border-color: #d4a847; }

/* ── Main content ────────────────────────────────────────────────── */
.voters-main {
  flex: 1;
  padding: 2rem 2.5rem;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

/* Flash */
.flash {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 500;
}
.flash--ok  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.flash--err { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.flash--info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

/* Filter bar */
.filter-bar {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}
.filter-search { position: relative; flex: 1; min-width: 200px; }
.filter-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #9ca3af; }
.filter-input {
  width: 100%;
  padding: 0.55rem 0.75rem 0.55rem 2.25rem;
  border: 1px solid #e2ddd6;
  border-radius: 7px;
  font-size: 0.85rem;
  background: #fff;
  color: #1a1a2e;
  font-family: 'Outfit', sans-serif;
  outline: none;
  transition: border-color 0.15s;
}
.filter-input:focus { border-color: #d4a847; }
.filter-select {
  padding: 0.55rem 0.75rem;
  border: 1px solid #e2ddd6;
  border-radius: 7px;
  font-size: 0.85rem;
  background: #fff;
  color: #374151;
  font-family: 'Outfit', sans-serif;
  outline: none;
  cursor: pointer;
}
.filter-select--narrow { min-width: 0; width: auto; }
.filter-clear { font-size: 0.8rem; color: #9ca3af; cursor: pointer; background: none; border: none; padding: 0.25rem; }
.filter-clear:hover { color: #374151; }
.filter-count { font-size: 0.75rem; color: #9ca3af; font-family: 'JetBrains Mono', monospace; margin-left: auto; }

/* Register table */
.register-wrap {
  background: #fff;
  border-radius: 10px;
  border: 1px solid #e8e3dc;
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.register { width: 100%; border-collapse: collapse; }

.reg-th {
  padding: 0.65rem 1rem;
  background: #f9f7f4;
  text-align: left;
  font-size: 0.65rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #9ca3af;
  border-bottom: 1px solid #e8e3dc;
}
.reg-th--no { width: 3rem; text-align: center; }
.reg-th--actions { text-align: right; }

.reg-row { border-bottom: 1px solid #f0ede8; transition: background 0.1s; }
.reg-row:last-child { border-bottom: none; }
.reg-row:hover { background: #faf9f6; }
.reg-row--voted { background: #f9fafb; }
.reg-row--removed { opacity: 0.5; }

.reg-td { padding: 0.85rem 1rem; vertical-align: middle; }
.reg-td--no { text-align: center; font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: #d1c9bd; font-weight: 500; }
.reg-td--actions { text-align: right; }

/* Voter cell */
.voter-cell { display: flex; align-items: center; gap: 0.75rem; }
.voter-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #1e2e4a, #0d2137);
  color: #60a5fa;
  font-size: 0.75rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-family: 'DM Serif Display', serif;
}
.voter-name { font-size: 0.85rem; font-weight: 500; color: #1a1a2e; }
.voter-email { font-size: 0.72rem; color: #9ca3af; font-family: 'JetBrains Mono', monospace; }

/* Status pill */
.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.25rem 0.65rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 600;
  letter-spacing: 0.03em;
}
.status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

.status-pill--active  { background: #f0fdf4; color: #166534; }
.status-pill--active .status-dot { background: #22c55e; }

.status-pill--inactive { background: #fffbeb; color: #92400e; }
.status-pill--inactive .status-dot { background: #f59e0b; }

.status-pill--invited { background: #eff6ff; color: #1e40af; }
.status-pill--invited .status-dot { background: #3b82f6; }

.status-pill--removed { background: #fef2f2; color: #991b1b; }
.status-pill--removed .status-dot { background: #ef4444; }

/* Voted */
.voted-mark {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  font-size: 0.72rem;
  font-weight: 600;
  color: #166534;
}
.voted-none { color: #d1c9bd; font-size: 0.8rem; }

/* Date */
.date-mono { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: #9ca3af; }

/* Actions */
.action-row { display: flex; align-items: center; justify-content: flex-end; gap: 0.375rem; }
.act-btn {
  padding: 0.3rem 0.65rem;
  border-radius: 5px;
  font-size: 0.72rem;
  font-weight: 600;
  font-family: 'Outfit', sans-serif;
  cursor: pointer;
  border: 1px solid transparent;
  transition: all 0.12s;
}
.act-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.act-btn--approve  { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
.act-btn--approve:hover:not(:disabled)  { background: #dcfce7; }
.act-btn--suspend  { background: #fffbeb; color: #92400e; border-color: #fde68a; }
.act-btn--suspend:hover:not(:disabled)  { background: #fef9c3; }
.act-btn--remove   { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
.act-btn--remove:hover:not(:disabled)   { background: #fee2e2; }
.act-btn--propose  { background: #fffbeb; color: #92400e; border-color: #fde68a; display: inline-flex; align-items: center; gap: 0.25rem; }
.act-btn--propose:hover:not(:disabled)  { background: #fef3c7; }
.act-btn--confirm  { background: #f0fdf4; color: #166534; border-color: #bbf7d0; display: inline-flex; align-items: center; gap: 0.25rem; }
.act-btn--confirm:hover:not(:disabled)  { background: #dcfce7; }
.act-btn--cancel   { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
.act-btn--cancel:hover:not(:disabled)   { background: #fee2e2; }

/* Pending suspension row highlight */
.reg-row--suspension-pending { background: #fffbeb !important; border-left: 3px solid #f59e0b; }
.status-pill--pending { background: #fffbeb; color: #92400e; }
.status-pill--pending .status-dot { background: #f59e0b; }

/* Suspension banner sub-row */
.suspension-info-row { background: #fffbeb; }
.suspension-banner {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  font-size: 0.72rem;
  color: #92400e;
  border-top: 1px solid #fde68a;
}
.suspension-banner strong { font-weight: 600; }
.suspension-banner-warn { margin-left: auto; font-size: 0.68rem; color: #b45309; }

.act-voted-lock { color: #d1c9bd; display: flex; justify-content: flex-end; }

/* Empty state */
.reg-empty { padding: 3rem 1rem; }
.empty-state { text-align: center; }
.empty-state p { font-size: 0.875rem; color: #9ca3af; margin-top: 0.5rem; }
.empty-clear { margin-top: 0.75rem; font-size: 0.8rem; color: #d4a847; background: none; border: none; cursor: pointer; text-decoration: underline; }

/* Pagination */
.pagination { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.pagination-info { font-size: 0.75rem; color: #9ca3af; font-family: 'JetBrains Mono', monospace; }
.pagination-links { display: flex; gap: 0.25rem; }
.pag-btn {
  padding: 0.35rem 0.65rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
  text-decoration: none;
  color: #6b7280;
  background: #fff;
  border: 1px solid #e8e3dc;
  transition: all 0.12s;
  font-family: 'Outfit', sans-serif;
}
.pag-btn:hover:not(.pag-btn--disabled):not(.pag-btn--active) { background: #f9f7f4; border-color: #d4a847; color: #374151; }
.pag-btn--active { background: #0d1117; border-color: #0d1117; color: #d4a847; }
.pag-btn--disabled { color: #d1c9bd; border-color: #f0ede8; cursor: default; }

/* Responsive */
@media (max-width: 900px) {
  .voters-body { flex-direction: column; }
  .voters-sidebar { width: 100%; min-height: auto; flex-direction: row; flex-wrap: wrap; }
  .sidebar-section--grow { flex-basis: 100%; }
  .voters-main { padding: 1.25rem 1rem; }
}
</style>
