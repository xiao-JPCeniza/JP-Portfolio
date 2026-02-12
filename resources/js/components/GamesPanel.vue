<template>
  <Teleport to="body">
    <Transition name="panel">
      <div
        v-if="open"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md"
        @click.self="close"
      >
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full max-h-[88vh] overflow-hidden border border-slate-200/80 dark:border-slate-700">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 tracking-tight">🎮 Games</h2>
            <button
              type="button"
              aria-label="Close"
              class="p-2.5 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors"
              @click="close"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="p-6 overflow-y-auto max-h-[calc(88vh-4.5rem)]">
            <div class="grid gap-4 sm:grid-cols-2">
              <button
                v-for="game in games"
                :key="game.id"
                type="button"
                class="flex flex-col items-center gap-3 p-5 rounded-xl bg-slate-50 dark:bg-slate-800/80 hover:bg-violet-50 dark:hover:bg-violet-900/25 border border-slate-200 dark:border-slate-700 hover:border-violet-400 dark:hover:border-violet-500 transition-all duration-200 text-center group shadow-sm hover:shadow-md"
                @click="selectGame(game.id)"
              >
                <span class="flex items-center justify-center w-14 h-14 rounded-xl bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform shadow-inner">
                  <GameIcon :game-id="game.id" class="w-7 h-7" />
                </span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ game.name }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 leading-tight">{{ game.desc }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Game modal overlay -->
    <Transition name="game">
      <div
        v-if="activeGame"
        class="fixed inset-0 z-[101] flex items-center justify-center p-3 sm:p-4 bg-black/70 backdrop-blur-md"
        @click.self="activeGame = null"
      >
        <div class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 w-full max-w-lg max-h-[95vh] min-h-[320px] flex flex-col">
          <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700 shrink-0 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2">
              <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-400 shrink-0">
                <GameIcon v-if="currentGame" :game-id="currentGame.id" class="w-5 h-5" />
              </span>
              <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ currentGame?.name }}</h3>
            </div>
            <button
              type="button"
              aria-label="Close game"
              class="p-2.5 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors"
              @click="activeGame = null"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="flex-1 min-h-0 overflow-auto p-4 flex justify-center">
            <BlockBlastGame v-if="activeGame === 'blockblast'" @close="activeGame = null" />
            <SnakeGame v-else-if="activeGame === 'snake'" @close="activeGame = null" />
            <MemoryGame v-else-if="activeGame === 'memory'" @close="activeGame = null" />
            <TetrisGame v-else-if="activeGame === 'tetris'" @close="activeGame = null" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue';
import BlockBlastGame from './games/BlockBlastGame.vue';
import SnakeGame from './games/SnakeGame.vue';
import MemoryGame from './games/MemoryGame.vue';
import TetrisGame from './games/TetrisGame.vue';
import GameIcon from './GameIcon.vue';

const props = defineProps({ open: Boolean });
const emit = defineEmits(['close']);

const activeGame = ref(null);

const games = [
  { id: 'blockblast', name: 'Block Blast', desc: 'Place blocks, clear lines' },
  { id: 'snake', name: 'Snake', desc: 'Eat apples, don\'t hit walls' },
  { id: 'memory', name: 'Memory', desc: 'Match pairs of cards' },
  { id: 'tetris', name: 'Tetris', desc: 'Drop blocks, clear lines' },
];

const currentGame = computed(() => games.find(g => g.id === activeGame.value));

function close() {
  emit('close');
}

function selectGame(id) {
  activeGame.value = id;
}
</script>

<style scoped>
.panel-enter-active,
.panel-leave-active {
  transition: opacity 0.2s ease;
}
.panel-enter-from,
.panel-leave-to {
  opacity: 0;
}
.panel-enter-active > div,
.panel-leave-active > div {
  transition: transform 0.2s ease;
}
.panel-enter-from > div,
.panel-leave-to > div {
  transform: scale(0.95);
}
.game-enter-active,
.game-leave-active {
  transition: opacity 0.2s ease;
}
.game-enter-from,
.game-leave-to {
  opacity: 0;
}
.game-enter-active > div,
.game-leave-active > div {
  transition: transform 0.2s ease;
}
.game-enter-from > div,
.game-leave-to > div {
  transform: scale(0.96);
}
</style>
