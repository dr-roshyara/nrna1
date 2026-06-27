<template>
  <div class="portal-card portal-card--membership-detail">
    <div class="portal-card__icon-wrap portal-card__icon-wrap--membership">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
    </div>
    <div class="portal-card__body">
      <h3 class="portal-card__title">Membership &amp; Committees</h3>
      <p v-if="hasData" class="portal-card__desc">
        <template v-if="activeCount > 0">{{ activeCount }} active</template>
        <template v-if="pendingCount > 0"> · {{ pendingCount }} pending</template>
        <template v-if="eligibleCount > 0"> · {{ eligibleCount }} eligible to join</template>
      </p>
      <p v-else class="portal-card__desc">View your committee memberships and applications</p>
    </div>
    <div class="portal-card__arrow">→</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  membership: { type: Array, default: () => [] },
})

const activeCount   = computed(() => props.membership.filter(c => c.has_active_association).length)
const pendingCount  = computed(() => props.membership.filter(c => c.has_pending_application).length)
const eligibleCount = computed(() => props.membership.filter(c => c.can_apply).length)
const hasData       = computed(() => activeCount.value + pendingCount.value + eligibleCount.value > 0)
</script>
