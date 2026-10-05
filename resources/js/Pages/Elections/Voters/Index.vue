<template>
  <PublicDigitLayout>
    <div class="voters-body">

      <!-- PBDIGIT-220: the left column (context, stats, member assignment, export) was removed.
           Export CSV moved to the header actions; assigning members as voters is PBDIGIT-221. -->

      <!-- ═══════════════════════════════════════════════════════════
           REGISTER TABLE — the official voter list
      ═══════════════════════════════════════════════════════════ -->
      <main class="voters-main">

        <!-- Page Header with Guide Link -->
        <div class="voters-page-header">
          <div class="voters-page-title-block">
            <h2 class="voters-page-title">Voter Management</h2>
            <p class="voters-page-subtitle">{{ election.name }} — verify and manage election voters</p>
          </div>
          <div class="voters-page-actions">
            <a :href="exportUrl" class="voters-guide-link">
              <svg class="voters-guide-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
              <span>Export CSV</span>
            </a>
            <a
              :href="route('organisations.membership.newsletters.create', organisation.slug) + '?election_id=' + election.id"
              class="voters-notify-link"
              aria-label="Send an email notice to this election's voters"
            >
              <svg class="voters-guide-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>Notify Voters</span>
            </a>
            <a
              href="/help/voters-verification_guide"
              target="_blank"
              rel="noopener noreferrer"
              class="voters-guide-link"
              aria-label="Open Voter Management Guide in new window"
            >
              <svg class="voters-guide-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>View Voter Guide</span>
            </a>
          </div>
        </div>

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

        <!-- Verify All (PBDIGIT-213 C4-C): bulk evidence recording, independent of the policy;
             the server recomputes the C4-D1 set and decides -->
        <div
          v-if="admission.available && admission.bulkVerifiableCount > 0"
          class="mb-4 flex items-center justify-between gap-4 rounded-lg border border-neutral-200 bg-white px-4 py-3"
        >
          <p class="text-sm text-neutral-700">
            {{ admission.bulkVerifiableCount }} voters have never had identity verification.
          </p>
          <Button variant="primary" size="sm" data-testid="open-verify-all" @click="showBulkVerify = true">
            Verify All Voters
          </Button>
        </div>

        <!-- The register -->
        <div class="register-wrap">
          <table class="register">
            <thead>
              <tr>
                <th class="reg-th reg-th--no">#</th>
                <th class="reg-th">Voter</th>
                <th class="reg-th">Status</th>
                <th v-if="admission.required" class="reg-th">Identity Verification</th>
                <th class="reg-th">Proposed by</th>
                <th class="reg-th">Suspended by</th>
                <th class="reg-th">Removed by</th>
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
                    <div class="flex-1">
                      <div class="flex items-center gap-2">
                        <p class="voter-name">{{ membership.user?.name ?? '—' }}</p>
                        <!-- Identity verified badge (PBDIGIT-213): identity-verification EVIDENCE for one
                             admission requirement — NOT the final admission decision (that stays the
                             server's AdmissionDecision / VotingEntitlement). From the identity state only,
                             never from the generic verifications map. -->
                        <span
                          v-if="identityVerification[membership.user_id] === 'verified'"
                          class="verification-badge verification-badge--active"
                          title="Identity-verification evidence recorded — not the complete admission decision"
                        >
                          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>
                          Identity verified
                        </span>
                      </div>
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

                <!-- Identity Verification (PBDIGIT-213 C4-B): server display state; shown only while the
                     Voter Admission Policy requires it. Verify opens IdentityAttestationDialog. -->
                <td v-if="admission.required" class="reg-td">
                  <IdentityVerificationCell
                    :state="identityVerification[membership.user_id] ?? 'not_required'"
                    @verify="openIdentityDialog(membership)"
                  />
                </td>

                <!-- Proposed by — transparency: who proposed the pending/most recent suspension -->
                <td class="reg-td">
                  <span class="date-mono">{{ membership.suspension_proposed_by ?? '—' }}</span>
                </td>

                <!-- Suspended by — transparency: the officer whose confirmation actually
                     caused the suspension (distinct from the proposer above) -->
                <td class="reg-td">
                  <span class="date-mono">{{ membership.metadata?.suspended_by_email ?? '—' }}</span>
                </td>

                <!-- Removed by — transparency: who performed the removal -->
                <td class="reg-td">
                  <span class="date-mono">{{ membership.metadata?.removed_by_email ?? '—' }}</span>
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
                    <!-- Verify Identity (PBDIGIT-213 C4): records identity-verification EVIDENCE — available
                         whether the policy is ON or OFF (OFF = not required, not "cannot be recorded").
                         Same action as the Identity Verification column. The legacy "Verify IP Address"
                         entry point is removed from this page (the IP backend is untouched legacy, PBDIGIT-222). -->
                    <button
                      data-testid="actions-verify-identity"
                      @click="openIdentityDialog(membership)"
                      :disabled="loadingId === membership.id"
                      class="act-btn act-btn--verify"
                      :title="identityVerification[membership.user_id] === 'verified' ? 'Re-verify the voter\'s identity' : 'Verify the voter\'s identity'"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                      </svg>
                      {{ identityVerification[membership.user_id] === 'verified' ? 'Re-verify Identity' : 'Verify Identity' }}
                    </button>

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
                      <!-- PBDIGIT-213 R3-3: same two-person proposal; business label when identity verification applies -->
                      {{ admission.required ? 'Suspend Identity' : 'Propose' }}
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
                <td :colspan="columnCount">
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
                <td :colspan="columnCount" class="reg-empty">
                  <div class="empty-state">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p>{{ searchQuery || statusFilter ? 'No voters match your filters.' : 'The register is empty.' }}</p>
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

  </PublicDigitLayout>

  <!-- Verify Identity (PBDIGIT-213 C4): both identity entry points open this dialog.
       IP/device data is audit-only metadata inside it; the attestation is the evidence. -->
  <IdentityAttestationDialog
    :open="showIdentityDialog"
    :voter="identityDialogVoter"
    :reverify="identityDialogVoter?.reverify ?? false"
    :organisation-slug="organisation.slug"
    :election-slug="election.slug"
    @close="showIdentityDialog = false"
  />

  <!-- Verify All (PBDIGIT-213 C4-C) -->
  <BulkVerifyDialog
    :open="showBulkVerify"
    :count="admission.bulkVerifiableCount"
    :organisation-slug="organisation.slug"
    :election-slug="election.slug"
    @close="showBulkVerify = false"
  />
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'
import IdentityAttestationDialog from '@/Components/Election/IdentityAttestationDialog.vue'
import IdentityVerificationCell from '@/Components/Election/IdentityVerificationCell.vue'
import BulkVerifyDialog from '@/Components/Election/BulkVerifyDialog.vue'
import Button from '@/Components/Button.vue'
import { ElectionLifecycleStates } from '@/Constants/ElectionLifecycleStates'

const props = defineProps({
  election:          { type: Object, required: true },
  organisation:      { type: Object, required: true },
  voters:            { type: Object, required: true },
  stats:             { type: Object, required: true },
  unassignedMembers: { type: Array,  default: () => [] },
  filters:           { type: Object, default: () => ({}) },
  verifications:     { type: Object, default: () => ({}) },
  // PBDIGIT-213 C4-B — display read-models from ElectionVoterController (never an authorisation source)
  admission:            { type: Object, default: () => ({ required: false, available: false, bulkVerifiableCount: 0 }) },
  identityVerification: { type: Object, default: () => ({}) },
})

const showBulkVerify = ref(false)

const authUserName           = computed(() => usePage().props.user?.name ?? usePage().props.auth?.user?.name)
const loadingId              = ref(null)
const searchQuery            = ref(props.filters?.search ?? '')
const statusFilter           = ref(props.filters?.status ?? '')
const perPage                = ref(props.filters?.per_page ?? 50)

// Verify Identity (attestation dialog)
const showIdentityDialog   = ref(false)
const identityDialogVoter  = ref(null)

// Table width follows the optional Identity Verification column (shown only while required)
const columnCount = computed(() => (props.admission.required ? 10 : 9))

const exportUrl = computed(() =>
  route('elections.voters.export', { organisation: props.organisation.slug, election: props.election.slug })
)

const statusLabel = (s) => ({ active: 'Active', inactive: 'Suspended', invited: 'Invited', removed: 'Removed' }[s] ?? s)

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—'

// Identity state is the ONLY source for identity UI (never the generic verifications map)
const openIdentityDialog = (membership) => {
  const state = props.identityVerification[membership.user_id]
  identityDialogVoter.value = {
    id: membership.user_id,
    name: membership.user?.name ?? 'Unknown',
    email: membership.user?.email ?? '',
    lastLoginIp: membership.user?.current_ip ?? null,   // audit-only metadata, never identity proof
    reverify: state === 'verified' || state === 'reverification_required',
  }
  showIdentityDialog.value = true
}

// Filters
let filterTimer = null
const debouncedFilter = () => { clearTimeout(filterTimer); filterTimer = setTimeout(applyFilters, 350) }
const applyFilters = () => {
  router.get(
    route('organisations.elections.voters', { organisation: props.organisation.slug, election: props.election.slug }),
    {
      search:    searchQuery.value || undefined,
      status:    statusFilter.value || undefined,
      per_page:  perPage.value !== 50 ? perPage.value : undefined,
    },
    { preserveScroll: true, replace: true }
  )
}
const clearFilters = () => { searchQuery.value = ''; statusFilter.value = ''; perPage.value = 50; applyFilters() }

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

/* ── Main content ────────────────────────────────────────────────── */
.voters-main {
  flex: 1;
  padding: 2rem 2.5rem;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

/* Page Header with Guide Link */
.voters-page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 2rem;
  margin-bottom: 1rem;
  padding-bottom: 1.5rem;
  border-bottom: 2px solid #d4a847;
}

.voters-page-title-block {
  flex: 1;
}

.voters-page-title {
  font-family: 'DM Serif Display', serif;
  font-size: 1.75rem;
  font-weight: 400;
  color: #0d1117;
  margin: 0 0 0.25rem 0;
  letter-spacing: -0.01em;
}

.voters-page-subtitle {
  font-size: 0.85rem;
  color: #6b7280;
  margin: 0;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.voters-guide-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #d4a847 0%, #c9992d 100%);
  color: white;
  font-weight: 500;
  font-size: 0.85rem;
  text-decoration: none;
  border-radius: 4px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(212, 168, 71, 0.3);
}

.voters-guide-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(212, 168, 71, 0.4);
}

.voters-page-actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.voters-notify-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #4f7cf6 0%, #3b5fe0 100%);
  color: white;
  font-weight: 500;
  font-size: 0.85rem;
  text-decoration: none;
  border-radius: 4px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(79, 124, 246, 0.3);
}

.voters-notify-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(79, 124, 246, 0.4);
}

.voters-guide-icon {
  width: 1.1rem;
  height: 1.1rem;
  stroke-width: 2;
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
.act-btn--verify   { background: #dbeafe; color: #0c4a6e; border-color: #bfdbfe; display: inline-flex; align-items: center; gap: 0.25rem; }
.act-btn--verify:hover:not(:disabled)   { background: #bfdbfe; }

/* Verification badge */
.verification-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.25rem 0.625rem;
  border-radius: 9999px;
  font-size: 0.7rem;
  font-weight: 600;
  white-space: nowrap;
}

.verification-badge--active {
  background: #d1fae5;
  color: #065f46;
}

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
  .voters-main { padding: 1.25rem 1rem; }

  .voters-page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .voters-guide-link {
    width: 100%;
    justify-content: center;
  }

  .voters-page-title {
    font-size: 1.35rem;
  }
}
</style>

