<template>
  <PublicDigitLayout>
    <main class="flex-1 relative">
      <!-- Article container with elevated card styling -->
      <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
        <div class="bg-white rounded-lg shadow-lg border border-brand-gold-200 p-8 sm:p-12 lg:p-16">
          <!-- Article header with metadata -->
          <header class="mb-12 sm:mb-16 pb-8 sm:pb-12 border-b-2 border-brand-gold-200">
            <!-- Main title -->
            <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl font-bold mb-4 leading-tight text-neutral-900">
              {{ article.title }}
            </h1>

            <!-- Subtitle -->
            <h2 class="font-serif text-2xl sm:text-3xl font-light mb-6 text-neutral-700 italic">
              {{ article.subtitle }}
            </h2>

            <!-- Author byline -->
            <p class="text-lg font-medium text-brand-gold-700 mb-6">
              {{ article.author }}
            </p>

            <!-- Divider -->
            <div class="flex items-center gap-3 text-neutral-400 text-sm">
              <span>✦</span>
              <span>{{ readingTime }} min read</span>
            </div>
          </header>

          <!-- Article content -->
          <div class="space-y-6 article-prose" v-html="processedContent"></div>

          <!-- Article footer -->
          <footer class="mt-16 sm:mt-20 pt-8 sm:pt-12 border-t border-neutral-200">
            <!-- About the project (editor's note) -->
            <div class="mb-12 p-6 sm:p-8 bg-neutral-50 border border-neutral-200 rounded-lg">
              <h3 class="text-sm uppercase tracking-widest text-neutral-500 font-semibold mb-3">About PolitPass</h3>
              <p class="text-neutral-700 leading-relaxed">
                PolitPass is an ongoing research initiative exploring responsibility-based political
                evaluation. Unlike traditional political ratings, it seeks to measure how effectively
                public offices fulfill their entrusted responsibilities rather than evaluating personal
                characteristics. The ideas presented in this article represent part of that continuing
                research.
              </p>
            </div>

            <!-- Companion article teaser -->
            <div class="mb-12 p-6 sm:p-8 bg-neutral-100 border border-brand-gold-300 rounded-lg">
              <p class="text-sm uppercase tracking-widest text-brand-gold-700 font-semibold mb-2">
                Related Reading
              </p>
              <h2 class="text-xl sm:text-2xl font-serif font-bold text-neutral-900 mb-2">
                Who Watches the Watchmen?
              </h2>
              <p class="text-neutral-600 mb-4">
                A simple explanation of a hard question about independent election oversight
                that appeared while building secure online elections for a worldwide diaspora organization.
              </p>
              <a href="/who-watch-the-watchmen" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium transition-colors group">
                Read the companion article
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </a>
            </div>

            <!-- Author bio -->
            <div class="mb-12 p-6 sm:p-8 bg-primary-50 border border-primary-200 rounded-lg">
              <h3 class="text-sm uppercase tracking-widest text-primary-700 font-semibold mb-3">About the Author</h3>
              <div class="flex items-start gap-4 mb-4">
                <div class="w-16 h-16 rounded-full bg-primary-200 flex items-center justify-center flex-shrink-0">
                  <span class="text-primary-700 font-serif font-bold text-2xl">NR</span>
                </div>
                <div>
                  <h4 class="text-lg font-semibold text-neutral-900 mb-3">{{ article.author }}</h4>
                  <p v-if="article.author_bio" class="text-neutral-700 leading-relaxed">
                    {{ article.author_bio }}
                  </p>
                </div>
              </div>
              <a href="/" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-sm px-1 py-0.5">
                Back to Home
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </a>
            </div>

            <!-- Share buttons -->
            <div class="flex items-center gap-3 flex-wrap">
              <p class="text-sm text-neutral-600">Share this article:</p>
              <a href="#" class="p-2 rounded-lg bg-neutral-200 hover:bg-neutral-300 text-neutral-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" title="Share on Twitter" aria-label="Share on Twitter">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417a9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
              </a>
              <a href="#" class="p-2 rounded-lg bg-neutral-200 hover:bg-neutral-300 text-neutral-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" title="Share on LinkedIn" aria-label="Share on LinkedIn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
              </a>
              <a href="#" class="p-2 rounded-lg bg-neutral-200 hover:bg-neutral-300 text-neutral-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" title="Copy link" aria-label="Copy link">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
              </a>
            </div>
          </footer>
        </div>
      </article>
    </main>
  </PublicDigitLayout>
</template>

<script setup>
import { computed } from 'vue'
import PublicDigitLayout from '@/Layouts/PublicDigitLayout.vue'

const props = defineProps({
  article: {
    type: Object,
    required: true,
  },
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
})

// Calculate reading time (assuming 200 words per minute)
const readingTime = computed(() => {
  const wordCount = props.article.content.split(/\s+/).length
  return Math.ceil(wordCount / 200)
})

// Process markdown content into formatted HTML
const processedContent = computed(() => {
  if (!props.article.content) return ''

  const lines = props.article.content.split('\n')
  const result = []
  let currentParagraph = []
  let listItems = []

  const flushParagraph = () => {
    if (currentParagraph.length > 0) {
      result.push(formatParagraph(currentParagraph.join('\n')))
      currentParagraph = []
    }
  }

  const flushList = () => {
    if (listItems.length > 0) {
      const items = listItems
        .map((item) => `<li class="text-neutral-700 text-lg leading-relaxed font-light">${applyMarkdownFormatting(item)}</li>`)
        .join('')
      result.push(`<ul class="list-disc pl-6 space-y-2 mb-6">${items}</ul>`)
      listItems = []
    }
  }

  for (let i = 0; i < lines.length; i++) {
    const line = lines[i]
    const trimmed = line.trim()

    // Unordered list items
    if (trimmed.match(/^[-*]\s+/)) {
      flushParagraph()
      listItems.push(trimmed.replace(/^[-*]\s+/, ''))
      continue
    }

    if (trimmed === '---') {
      flushParagraph()
      flushList()
      result.push('<div class="my-8 border-t-2 border-brand-gold-200"></div>')
      continue
    }

    if (trimmed.match(/^###\s+/)) {
      flushParagraph()
      flushList()
      const heading = trimmed.replace(/^###\s+(.+)$/, '$1')
      result.push(`<h3 class="text-2xl font-serif font-bold mt-8 mb-4 text-neutral-900">${heading}</h3>`)
      continue
    }

    if (trimmed.match(/^##\s+/)) {
      flushParagraph()
      flushList()
      const heading = trimmed.replace(/^##\s+(.+)$/, '$1')
      result.push(`<h2 class="text-3xl font-serif font-bold mt-10 mb-5 text-neutral-900">${heading}</h2>`)
      continue
    }

    if (trimmed.match(/^#\s+/)) {
      flushParagraph()
      flushList()
      const heading = trimmed.replace(/^#\s+(.+)$/, '$1')
      result.push(`<h1 class="text-4xl font-serif font-bold mt-12 mb-6 text-neutral-900">${heading}</h1>`)
      continue
    }

    if (trimmed === '') {
      flushParagraph()
      flushList()
      continue
    }

    currentParagraph.push(line)
  }

  flushParagraph()
  flushList()

  return result.join('')
})

const formatParagraph = (text) => {
  const trimmed = text.trim()
  if (!trimmed) return ''

  // Highlight "Separation of Concerns." style lead-ins as callout blocks
  const conceptMatch = trimmed.match(/^\*\*([A-Za-z][^*]*?\.)\*\*\s+(.+)$/s)
  if (conceptMatch) {
    const concept = conceptMatch[1]
    let description = conceptMatch[2]
    description = applyMarkdownFormatting(description)

    return `<div class="mb-6 pl-6 border-l-4 border-brand-gold-300"><p class="text-neutral-700 text-lg leading-relaxed font-light"><strong>${concept}</strong> ${description}</p></div>`
  }

  const content = applyMarkdownFormatting(trimmed)
  return `<p class="text-neutral-700 text-lg leading-relaxed font-light">${content}</p>`
}

const applyMarkdownFormatting = (text) => {
  return text
    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
    .replace(/__(.*?)__/g, '<strong>$1</strong>')
    .replace(/\*(.*?)\*/g, '<em>$1</em>')
    .replace(/_(.*?)_/g, '<em>$1</em>')
    .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-primary-600 hover:text-primary-700 underline transition-colors">$1</a>')
}
</script>

<style scoped>
/* Import serif font */
@import url('https://fonts.googleapis.com/css2?family=Crimson+Text:ital@0;1&display=swap');

/* Article typography */
.font-serif {
  font-family: 'Crimson Text', 'Georgia', serif;
}

/*
 * IMPORTANT: All prose rules below are confined to `.article-prose`.
 * Vue assigns this component's scoped id to the root element of the
 * child <PublicDigitLayout>, so an unscoped `:deep(a)` would leak into
 * the layout header/footer and recolor the nav links. Confining to
 * `.article-prose` keeps the styling on the article body only.
 */

/* Prose styles for article content */
:deep(.article-prose p) {
  margin-bottom: 1.5rem;
}

:deep(.article-prose h2) {
  margin-top: 2.5rem;
  margin-bottom: 1.25rem;
}

:deep(.article-prose h3) {
  margin-top: 2rem;
  margin-bottom: 1rem;
}

:deep(.article-prose strong) {
  font-weight: 600;
  color: rgb(23, 23, 23);
}

:deep(.article-prose em) {
  font-style: italic;
}

/* Link styling — article body only */
:deep(.article-prose a) {
  position: relative;
  transition: color 0.3s ease;
  color: rgb(37, 99, 235);
}

:deep(.article-prose a:hover) {
  color: rgb(29, 78, 216);
}

:deep(.article-prose a::after) {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background-color: rgb(37, 99, 235);
  transition: width 0.3s ease;
}

:deep(.article-prose a:hover::after) {
  width: 100%;
}

/* Code blocks if any */
:deep(.article-prose code) {
  background-color: rgb(245, 245, 245);
  border: 1px solid rgb(229, 231, 235);
  padding: 0.2em 0.4em;
  border-radius: 0.3em;
  font-family: monospace;
  font-size: 0.9em;
  color: rgb(23, 23, 23);
}

/* Improve readability on larger screens */
@media (min-width: 1024px) {
  :deep(.article-prose p) {
    max-width: 65ch;
  }
}
</style>
