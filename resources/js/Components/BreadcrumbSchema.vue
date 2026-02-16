<template>
  <div class="breadcrumb-container" v-if="breadcrumbs && breadcrumbs.length > 0">
    <!-- HTML Breadcrumbs for UX & Accessibility -->
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <ol class="breadcrumb-list">
        <li v-for="(item, index) in breadcrumbs" :key="index" class="breadcrumb-item">
          <a
            v-if="index < breadcrumbs.length - 1"
            :href="item.url"
            class="breadcrumb-link"
          >
            {{ item.label }}
          </a>
          <span v-else class="breadcrumb-current">
            {{ item.label }}
          </span>
        </li>
      </ol>
    </nav>
  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const page = usePage()

/**
 * Get breadcrumbs from Inertia props
 */
const breadcrumbs = computed(() => page.props.breadcrumbs || [])

/**
 * Generate JSON-LD BreadcrumbList schema
 */
const jsonLdSchema = computed(() => {
  if (!breadcrumbs.value || breadcrumbs.value.length === 0) return null

  const items = breadcrumbs.value.map((item, index) => ({
    '@type': 'ListItem',
    'position': index + 1,
    'name': item.label,
    'item': item.url
  }))

  const schema = {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    'itemListElement': items
  }

  return JSON.stringify(schema)
})

/**
 * Inject JSON-LD schema into document head
 */
const injectSchema = () => {
  if (!jsonLdSchema.value) return

  // Remove existing breadcrumb schema if present
  const existingScript = document.head.querySelector('script[data-breadcrumb-schema]')
  if (existingScript) {
    existingScript.remove()
  }

  // Create and inject new schema script
  const script = document.createElement('script')
  script.type = 'application/ld+json'
  script.setAttribute('data-breadcrumb-schema', 'true')
  script.innerHTML = jsonLdSchema.value
  document.head.appendChild(script)
}

/**
 * Watch for breadcrumb changes and update schema
 */
watch(jsonLdSchema, injectSchema)

/**
 * Initial schema injection
 */
onMounted(injectSchema)

/**
 * Navigate using Inertia (preserves Vue state)
 */
const navigate = (url) => {
  router.visit(url)
}
</script>

<style scoped>
.breadcrumb-container {
  margin-bottom: 1.5rem;
}

.breadcrumb-nav {
  font-size: 0.875rem;
}

.breadcrumb-list {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  padding: 0;
  margin: 0;
  gap: 0.25rem;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
}

.breadcrumb-item + .breadcrumb-item::before {
  content: '›';
  margin: 0 0.5rem;
  color: #999;
  opacity: 0.7;
}

.breadcrumb-link {
  color: #0066cc;
  text-decoration: none;
  transition: all 0.2s ease;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
}

.breadcrumb-link:hover {
  background-color: #f0f0f0;
  text-decoration: underline;
}

.breadcrumb-link:focus-visible {
  outline: 2px solid #0066cc;
  outline-offset: 2px;
}

.breadcrumb-current {
  color: #666;
  font-weight: 500;
  padding: 0.25rem 0.5rem;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .breadcrumb-link {
    color: #4da6ff;
  }

  .breadcrumb-link:hover {
    background-color: #2a2a2a;
  }

  .breadcrumb-current {
    color: #aaa;
  }

  .breadcrumb-item + .breadcrumb-item::before {
    color: #666;
  }
}

/* Responsive design */
@media (max-width: 640px) {
  .breadcrumb-nav {
    font-size: 0.8rem;
  }

  .breadcrumb-item + .breadcrumb-item::before {
    margin: 0 0.25rem;
  }
}
</style>
