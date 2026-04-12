<template>
  <!-- Loading skeleton -->
  <div v-if="!editor" class="rounded-lg border border-slate-200 bg-slate-50 h-64 animate-pulse" />

  <div v-else class="rounded-lg border-2 border-purple-300 focus-within:ring-2 focus-within:ring-purple-500 focus-within:border-purple-500 overflow-hidden">

    <!-- ── Toolbar ────────────────────────────────────────────────── -->
    <div class="flex flex-wrap items-center gap-1 border-b border-purple-200 bg-white px-3 py-2">

      <!-- Bold / Italic / Underline -->
      <button type="button" @click="editor.chain().focus().toggleBold().run()"
              :class="editor.isActive('bold') ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-base transition-colors" title="Bold">B</button>

      <button type="button" @click="editor.chain().focus().toggleItalic().run()"
              :class="editor.isActive('italic') ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center italic text-base transition-colors" title="Italic">I</button>

      <button type="button" @click="editor.chain().focus().toggleUnderline().run()"
              :class="editor.isActive('underline') ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center underline text-base transition-colors" title="Underline">U</button>

      <div class="w-px h-6 bg-slate-200 mx-1" />

      <!-- Headings -->
      <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
              :class="editor.isActive('heading', { level: 1 }) ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold transition-colors" title="Heading 1">H1</button>

      <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
              :class="editor.isActive('heading', { level: 2 }) ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold transition-colors" title="Heading 2">H2</button>

      <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
              :class="editor.isActive('heading', { level: 3 }) ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold transition-colors" title="Heading 3">H3</button>

      <div class="w-px h-6 bg-slate-200 mx-1" />

      <!-- Bullet / Numbered list -->
      <button type="button" @click="editor.chain().focus().toggleBulletList().run()"
              :class="editor.isActive('bulletList') ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors" title="Bullet list">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          <circle cx="1.5" cy="6" r="1.5" fill="currentColor" stroke="none"/>
          <circle cx="1.5" cy="12" r="1.5" fill="currentColor" stroke="none"/>
          <circle cx="1.5" cy="18" r="1.5" fill="currentColor" stroke="none"/>
        </svg>
      </button>

      <button type="button" @click="editor.chain().focus().toggleOrderedList().run()"
              :class="editor.isActive('orderedList') ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-mono font-bold transition-colors" title="Numbered list">1.</button>

      <div class="w-px h-6 bg-slate-200 mx-1" />

      <!-- Alignment -->
      <button type="button" @click="editor.chain().focus().setTextAlign('left').run()"
              :class="editor.isActive({ textAlign: 'left' }) ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors" title="Align left">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h10M4 14h16M4 18h10"/>
        </svg>
      </button>

      <button type="button" @click="editor.chain().focus().setTextAlign('center').run()"
              :class="editor.isActive({ textAlign: 'center' }) ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors" title="Align center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 10h10M4 14h16M7 18h10"/>
        </svg>
      </button>

      <button type="button" @click="editor.chain().focus().setTextAlign('right').run()"
              :class="editor.isActive({ textAlign: 'right' }) ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors" title="Align right">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 10h10M4 14h16M10 18h10"/>
        </svg>
      </button>

      <div class="w-px h-6 bg-slate-200 mx-1" />

      <!-- Link -->
      <button type="button" @click="setLink"
              :class="editor.isActive('link') ? 'bg-purple-100 text-purple-700' : 'text-slate-700 hover:bg-slate-100'"
              class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors" title="Insert link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
        </svg>
      </button>

      <button v-if="editor.isActive('link')" type="button" @click="editor.chain().focus().unsetLink().run()"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-slate-700 hover:bg-slate-100 transition-colors" title="Remove link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <div class="w-px h-6 bg-slate-200 mx-1" />

      <!-- Image -->
      <button type="button" @click="insertImage"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-slate-700 hover:bg-slate-100 transition-colors" title="Insert image">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </button>

      <div class="w-px h-6 bg-slate-200 mx-1" />

      <!-- Undo / Redo -->
      <button type="button" @click="editor.chain().focus().undo().run()"
              :disabled="!editor.can().undo()"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-slate-700 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors" title="Undo">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
        </svg>
      </button>

      <button type="button" @click="editor.chain().focus().redo().run()"
              :disabled="!editor.can().redo()"
              class="w-10 h-10 rounded-lg flex items-center justify-center text-slate-700 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors" title="Redo">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a8 8 0 00-8 8v2m18-10l-6 6m6-6l-6-6"/>
        </svg>
      </button>

    </div>

    <!-- ── Editor content area ────────────────────────────────────── -->
    <EditorContent :editor="editor"
                   class="min-h-[32rem] lg:min-h-[42rem] px-4 py-3 text-slate-900 text-sm leading-relaxed focus:outline-none" />

    <!-- ── Footer: word count ────────────────────────────────────── -->
    <div class="border-t border-slate-100 bg-slate-50 px-3 py-1 text-right text-xs text-slate-400 select-none">
      {{ wordCount }} words
    </div>

  </div>
</template>

<script setup>
import { computed, onBeforeUnmount, watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import TextAlign from '@tiptap/extension-text-align'
import Underline from '@tiptap/extension-underline'
import Placeholder from '@tiptap/extension-placeholder'

const props = defineProps({
  modelValue:  { type: String, default: '' },
  placeholder: { type: String, default: 'Write your newsletter content here…' },
})
const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    // TipTap v3 StarterKit includes Link + Underline — disable them here, add configured versions below
    StarterKit.configure({ link: false, underline: false }),
    Underline,
    Link.configure({ openOnClick: false }),
    Image.configure({ inline: false }),
    TextAlign.configure({ types: ['heading', 'paragraph'] }),
    Placeholder.configure({ placeholder: props.placeholder }),
  ],
  editorProps: {
    attributes: { class: 'focus:outline-none' },
  },
  onUpdate({ editor }) {
    emit('update:modelValue', editor.getHTML())
  },
})

watch(() => props.modelValue, (val) => {
  if (editor.value && editor.value.getHTML() !== val) {
    editor.value.commands.setContent(val, false)
  }
})

const wordCount = computed(() => {
  if (!editor.value) return 0
  const text = editor.value.getText().trim()
  return text ? text.split(/\s+/).length : 0
})

const setLink = () => {
  const prev = editor.value.getAttributes('link').href ?? ''
  const url = window.prompt('Enter URL:', prev)
  if (url === null) return
  if (url === '') { editor.value.chain().focus().unsetLink().run(); return }
  editor.value.chain().focus().setLink({ href: url }).run()
}

const insertImage = () => {
  const url = window.prompt('Enter image URL:')
  if (!url) return
  editor.value.chain().focus().setImage({ src: url }).run()
}

onBeforeUnmount(() => editor.value?.destroy())
</script>

<style>
/* Placeholder text */
.tiptap p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #94a3b8;
  pointer-events: none;
  height: 0;
}

/* Editor prose styles */
.tiptap { outline: none; }
.tiptap h1 { font-size: 1.5rem; font-weight: 700; margin: 1rem 0 0.5rem; line-height: 1.3; }
.tiptap h2 { font-size: 1.25rem; font-weight: 700; margin: 0.875rem 0 0.4rem; line-height: 1.35; }
.tiptap h3 { font-size: 1.05rem; font-weight: 600; margin: 0.75rem 0 0.35rem; }
.tiptap p  { margin: 0.35rem 0; line-height: 1.65; }
.tiptap ul { list-style: disc;    padding-left: 1.5rem; margin: 0.5rem 0; }
.tiptap ol { list-style: decimal; padding-left: 1.5rem; margin: 0.5rem 0; }
.tiptap li { margin: 0.2rem 0; }
.tiptap a  { color: #7c3aed; text-decoration: underline; cursor: pointer; }
.tiptap img { max-width: 100%; height: auto; border-radius: 0.375rem; margin: 0.75rem 0; display: block; }
.tiptap strong { font-weight: 700; }
.tiptap em { font-style: italic; }
</style>
