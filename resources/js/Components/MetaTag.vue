<template>
  <!--
    MetaTag Component - SEO Debugging & Display

    This component displays the current SEO meta tags for the page.
    Useful for development and debugging SEO implementation.

    Automatically hidden in production (check showDebug prop).

    USAGE:
    ------
    // Simple usage - shows meta tags in dev mode
    <MetaTag />

    // With custom title
    <MetaTag
      :seoData="{
        title: 'Custom Title',
        description: 'Custom Description'
      }"
    />

    // Visible in production (careful!)
    <MetaTag :showAlways="true" />
  -->

  <div v-if="showDebug" class="meta-tag-debug">
    <div class="meta-container">
      <h3>📋 Current Page SEO Meta Tags</h3>

      <div class="meta-item">
        <label>Title:</label>
        <code>{{ seoData.title }}</code>
        <span class="char-count" :class="{ warning: seoData.title.length > 60 }">
          {{ seoData.title.length }} / 60 characters
        </span>
      </div>

      <div class="meta-item">
        <label>Description:</label>
        <code>{{ seoData.description }}</code>
        <span class="char-count" :class="{ warning: seoData.description.length > 160 }">
          {{ seoData.description.length }} / 160 characters
        </span>
      </div>

      <div class="meta-item">
        <label>Keywords:</label>
        <code>{{ seoData.keywords }}</code>
      </div>

      <div class="meta-item">
        <label>URL:</label>
        <code class="url">{{ seoData.url }}</code>
      </div>

      <div class="meta-item">
        <label>OG Image:</label>
        <code class="url">{{ seoData.image }}</code>
        <img v-if="seoData.image" :src="seoData.image" :alt="seoData.title" class="og-preview">
      </div>

      <div class="meta-item">
        <label>Robots:</label>
        <code>
          {{ seoData.noindex ? 'noindex' : 'index' }},
          {{ seoData.nofollow ? 'nofollow' : 'follow' }}
        </code>
      </div>

      <div class="meta-item">
        <label>Type:</label>
        <code>{{ seoData.type }}</code>
      </div>

      <div class="meta-item">
        <label>Locale:</label>
        <code>{{ seoData.locale }}</code>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  /**
   * SEO data object to display
   * If not provided, reads from document meta tags
   */
  seoData: {
    type: Object,
    default: null
  },

  /**
   * Show debug info in production (default: false)
   * Only shows in development if false
   */
  showAlways: {
    type: Boolean,
    default: false
  }
})

/**
 * Determine if debug panel should be visible
 */
const showDebug = computed(() => {
  // Always show if showAlways is true
  if (props.showAlways) return true

  // Show in development only
  return import.meta.env.DEV
})

/**
 * Get SEO data from props or document head
 */
const seoData = computed(() => {
  if (props.seoData) {
    return props.seoData
  }

  // Fallback: read from document meta tags
  return {
    title: document.title || 'N/A',
    description: getMetaContent('description') || 'N/A',
    keywords: getMetaContent('keywords') || 'N/A',
    url: getMetaContent('og:url') || window.location.href,
    image: getMetaContent('og:image') || 'N/A',
    type: getMetaContent('og:type') || 'website',
    locale: getMetaContent('og:locale') || 'N/A',
    noindex: getMetaContent('robots')?.includes('noindex') || false,
    nofollow: getMetaContent('robots')?.includes('nofollow') || false
  }
})

/**
 * Helper to get meta tag content
 */
function getMetaContent(name) {
  const tag = document.querySelector(`meta[name="${name}"], meta[property="${name}"]`)
  return tag ? tag.getAttribute('content') : null
}
</script>

<style scoped>
.meta-tag-debug {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 9999;
  max-width: 500px;
  max-height: 80vh;
  overflow-y: auto;
  background: white;
  border: 2px solid #4b5563;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  font-family: 'Monaco', 'Courier New', monospace;
  font-size: 12px;
}

.meta-container {
  padding: 16px;
}

.meta-container h3 {
  margin: 0 0 12px 0;
  font-size: 14px;
  font-weight: 600;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
}

.meta-item {
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid #e5e7eb;
}

.meta-item:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}

.meta-item label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 4px;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.meta-item code {
  display: block;
  background: #f3f4f6;
  padding: 8px;
  border-radius: 4px;
  word-break: break-word;
  color: #1f2937;
  margin-bottom: 4px;
}

.meta-item code.url {
  color: #0066cc;
  text-decoration: underline;
}

.char-count {
  display: block;
  font-size: 11px;
  color: #6b7280;
  margin-top: 4px;
}

.char-count.warning {
  color: #dc2626;
  font-weight: 600;
}

.og-preview {
  max-width: 100%;
  max-height: 200px;
  margin-top: 8px;
  border-radius: 4px;
  border: 1px solid #d1d5db;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
  .meta-tag-debug {
    bottom: 10px;
    right: 10px;
    left: 10px;
    max-width: none;
    max-height: 60vh;
  }

  .meta-container {
    padding: 12px;
  }

  .meta-container h3 {
    font-size: 12px;
  }

  .meta-item code {
    padding: 6px;
    font-size: 11px;
  }
}
</style>
