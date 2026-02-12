<template>
  <button
    type="button"
    aria-label="Open games"
    class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-2xl bg-violet-600 hover:bg-violet-500 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 active:scale-95 border-2 border-violet-400/60 dark:border-violet-500/50 dark:bg-violet-600 dark:hover:bg-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
    @click="$emit('open')"
  >
    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M21.58 16.09l-1.09-7.66A3.996 3.996 0 0 0 16.53 5H7.47C6.27 5 5.21 5.84 4.97 7.02L3.42 16.09c-.14.99.99 1.79 1.99 1.79H10v2.93c0 .37.21.71.53.88l.53.29c.42.23.95.12 1.22-.32.27-.44.27-.99 0-1.43l-.53-.29V19.6h3v.68l-.53.29c-.27.44-.27.99 0 1.43.27.44.8.55 1.22.32l.53-.29c.32-.17.53-.51.53-.88v-2.93h5.59c1 0 2.13-.8 1.99-1.79zM7.47 7h9.06c.55 0 1 .45 1 1l.6 4.22-1.2-.4c-.56-.19-1.17-.29-1.8-.29H8.8c-.63 0-1.24.1-1.8.29l-1.2.4.6-4.22c0-.55.45-1 1-1z"/>
    </svg>
  </button>
</template>

<script setup>
defineEmits(['open']);
</script>
