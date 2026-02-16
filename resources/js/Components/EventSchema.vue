<template>
  <!-- JSON-LD Event Schema (hidden from display) -->
  <script type="application/ld+json" v-html="eventJsonLd"></script>
</template>

<script setup>
import { computed } from 'vue'

/**
 * EventSchema Component
 *
 * Renders JSON-LD Event schema for elections
 * Makes elections discoverable in Google Events
 */
const props = defineProps({
  election: {
    type: Object,
    required: true,
    validator: (obj) =>
      obj &&
      typeof obj === 'object' &&
      'name' in obj
  },
  organization: {
    type: Object,
    default: null
  }
})

/**
 * Generate JSON-LD Event schema
 */
const eventJsonLd = computed(() => {
  // Determine event status
  let eventStatus = 'EventScheduled'

  if (props.election.end_date) {
    const endDate = new Date(props.election.end_date)
    if (endDate < new Date()) {
      eventStatus = 'EventCancelled' // Completed election
    }
  }

  const schema = {
    '@context': 'https://schema.org',
    '@type': 'Event',
    'name': props.election.name,
    'description': props.election.description || 'Election on Public Digit',
    'startDate': props.election.start_date,
    'endDate': props.election.end_date,
    'eventStatus': eventStatus,
    'eventAttendanceMode': 'OnlineEventAttendanceMode',
    'url': window.location.href,
    'image': props.organization?.logo_url || '/images/og-default.jpg',
    'organizer': {
      '@type': 'Organization',
      'name': props.organization?.name || 'Public Digit',
      'url': props.organization
        ? `/organizations/${props.organization.slug}`
        : '/'
    },
    'offers': {
      '@type': 'Offer',
      'url': window.location.href,
      'price': '0',
      'priceCurrency': 'USD'
    }
  }

  // Remove undefined values
  const cleanSchema = JSON.parse(JSON.stringify(schema))

  return JSON.stringify(cleanSchema)
})
</script>
