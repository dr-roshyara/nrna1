<template>
  <div class="breadcrumb-container">
    <!-- HTML Breadcrumbs for UX & Accessibility -->
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <ol class="breadcrumb-list">
        <li v-for="(item, index) in breadcrumbs" :key="index" class="breadcrumb-item">
          <a
            v-if="index < breadcrumbs.length - 1"
            :href="item.url"
            class="breadcrumb-link"
            @click.prevent="navigate(item.url)"
          >
            {{ item.label }}
          </a>
          <span v-else class="breadcrumb-current" itemscope itemtype="https://schema.org/Thing">
            <span itemprop="name">{{ item.label }}</span>
          </span>
        </li>
      </ol>
    </nav>

    <!-- JSON-LD Schema (hidden from display) -->
    <script type="application/ld+json" v-html="jsonLdSchema"></script>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/inertia'

const props = defineProps({
  breadcrumbs: {
    type: Array,
    required: true,
    validator: (arr) =>
      Array.isArray(arr) &&
      arr.every((item) => item.label && item.url)
  }
})

/**
 * Generate JSON-LD BreadcrumbList schema
 */
const jsonLdSchema = computed(() => {
  const items = props.breadcrumbs.map((item, index) => ({
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
