<template>
  <span class="inline-flex items-center justify-center shrink-0">
    <img
      v-if="src"
      :src="src"
      :alt="alt"
      class="w-full h-full object-contain"
      draggable="false"
      loading="lazy"
    />
    <svg v-else fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" class="w-full h-full">
      <path d="M21.58 16.09l-1.09-7.66A3.996 3.996 0 0 0 16.53 5H7.47C6.27 5 5.21 5.84 4.97 7.02L3.42 16.09c-.14.99.99 1.79 1.99 1.79H10v2.93c0 .37.21.71.53.88l.53.29c.42.23.95.12 1.22-.32.27-.44.27-.99 0-1.43l-.53-.29V19.6h3v.68l-.53.29c-.27.44-.27.99 0 1.43.27.44.8.55 1.22.32l.53-.29c.32-.17.53-.51.53-.88v-2.93h5.59c1 0 2.13-.8 1.99-1.79z" />
    </svg>
  </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  gameId: { type: String, required: true },
});

const src = computed(() => {
  switch (props.gameId) {
    case 'blockblast':
      return '/icons/blockblast.svg';
    case 'snake':
      return '/icons/memory.svg';
    case 'memory':
      return '/icons/snake.svg';
    case 'tetris':
      return '/icons/tetris.svg';
    default:
      return '';
  }
});

const alt = computed(() => {
  switch (props.gameId) {
    case 'blockblast':
      return 'Block Blast icon';
    case 'snake':
      return 'Snake icon';
    case 'memory':
      return 'Memory icon';
    case 'tetris':
      return 'Tetris icon';
    default:
      return 'Game icon';
  }
});
</script>
