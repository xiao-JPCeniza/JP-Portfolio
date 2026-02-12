<template>
  <div class="memory-game flex flex-col items-center gap-4">
    <div class="flex items-center justify-between w-full max-w-[320px]">
      <span class="text-slate-600 dark:text-slate-400 font-medium">Moves: {{ moves }}</span>
      <button
        type="button"
        class="px-3 py-1.5 rounded-lg bg-amber-600 text-white text-sm font-medium hover:bg-amber-500"
        @click="reset"
      >
        New game
      </button>
    </div>

    <div class="grid gap-2 max-w-[320px]" :style="{ gridTemplateColumns: `repeat(${cols}, 1fr)` }">
      <button
        v-for="(card, i) in cards"
        :key="i"
        type="button"
        class="aspect-square rounded-xl text-2xl font-bold flex items-center justify-center border-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-400 disabled:pointer-events-none"
        :class="card.revealed || card.matched
          ? 'bg-white dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-800 dark:text-slate-100'
          : 'bg-amber-100 dark:bg-amber-900/40 border-amber-300 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-900/60'"
        :disabled="card.matched || card.revealed || lock"
        @click="flip(i)"
      >
        <span v-if="card.revealed || card.matched">{{ card.label }}</span>
        <span v-else>?</span>
      </button>
    </div>

    <p v-if="allMatched" class="text-lg font-bold text-amber-600 dark:text-amber-400">You won in {{ moves }} moves!</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const emit = defineEmits(['close']);

const cols = 4;
const pairs = 8;
const cards = ref([]);
const moves = ref(0);
const lock = ref(false);
const lastFlipped = ref(-1);

const allMatched = computed(() => cards.value.length > 0 && cards.value.every(c => c.matched));

const SYMBOLS = ['🍎', '🍕', '🌟', '🎮', '🎵', '🚀', '💡', '🎯', '🌈', '⚡'];

function shuffle(arr) {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

function reset() {
  const symbols = SYMBOLS.slice(0, pairs);
  const doubled = symbols.concat(symbols).map((label, id) => ({
    id,
    label,
    revealed: false,
    matched: false,
  }));
  cards.value = shuffle(doubled);
  moves.value = 0;
  lock.value = false;
  lastFlipped.value = -1;
}

function flip(index) {
  if (lock.value || cards.value[index].revealed || cards.value[index].matched) return;

  cards.value[index].revealed = true;

  if (lastFlipped.value === -1) {
    lastFlipped.value = index;
    return;
  }

  moves.value++;
  const first = cards.value[lastFlipped.value];
  const second = cards.value[index];

  if (first.label === second.label) {
    first.matched = true;
    second.matched = true;
    lastFlipped.value = -1;
    return;
  }

  lock.value = true;
  setTimeout(() => {
    first.revealed = false;
    second.revealed = false;
    lastFlipped.value = -1;
    lock.value = false;
  }, 600);
}

onMounted(reset);
</script>
