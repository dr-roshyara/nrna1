<template>
  <!-- Component handles schema injection, no visible output -->
  <div style="display: none;"></div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'

/**
 * EventSchema Component
 *
 * Renders JSON-LD Event schema for elections
 * Makes elections discoverable in Google Events
 * Injects schema into document head when component mounts
 */
const props = defineProps({
  election: {
    type: Object,
    default: null
  },
  organisation: {
    type: Object,
    default: null
  }
})

/**
 * Generate JSON-LD Event schema
 */
const eventJsonLd = computed(() => {
  if (!props.election) return null

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
    'name': props.election.name || 'Election',
    'description': props.election.description || 'Election on Public Digit',
    'startDate': props.election.start_date,
    'endDate': props.election.end_date,
    'eventStatus': eventStatus,
    'eventAttendanceMode': 'OnlineEventAttendanceMode',
    'url': typeof window !== 'undefined' ? window.location.href : '',
    'image': props.organisation?.logo_url || '/images/og-default.jpg',
    'organizer': {
      '@type': 'organisation',
      'name': props.organisation?.name || 'Public Digit',
      'url': props.organisation
        ? `/organisations/${props.organisation.slug}`
        : '/'
    },
    'offers': {
      '@type': 'Offer',
      'url': typeof window !== 'undefined' ? window.location.href : '',
      'price': '0',
      'priceCurrency': 'USD'
    }
  }

  // Remove undefined values
  const cleanSchema = JSON.parse(JSON.stringify(schema))

  return JSON.stringify(cleanSchema)
})

/**
 * Inject JSON-LD schema into document head
 */
const injectSchema = () => {
  if (!eventJsonLd.value) return

  // Remove existing event schema if present
  const existingScript = document.head.querySelector('script[data-event-schema]')
  if (existingScript) {
    existingScript.remove()
  }

  // Create and inject new schema script
  const script = document.createElement('script')
  script.type = 'application/ld+json'
  script.setAttribute('data-event-schema', 'true')
  script.innerHTML = eventJsonLd.value
  document.head.appendChild(script)
}

/**
 * Watch for election changes and update schema
 */
watch(eventJsonLd, injectSchema)

/**
 * Initial schema injection
 */
onMounted(injectSchema)
</script>
