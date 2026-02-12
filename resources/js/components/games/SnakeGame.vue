<template>
  <div class="snake-game flex flex-col items-center gap-4">
    <div class="flex items-center justify-between w-full max-w-[320px]">
      <span class="text-slate-600 dark:text-slate-400 font-medium">Score: {{ score }}</span>
      <button
        type="button"
        class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-500 transition-colors"
        @click="reset"
      >
        {{ started ? 'Restart' : 'Start' }}
      </button>
    </div>

    <div
      ref="gridRef"
      class="snake-board shrink-0 rounded-xl border-2 border-slate-600 dark:border-slate-500 overflow-hidden bg-slate-700 dark:bg-slate-800 select-none outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
      :style="boardStyle"
      tabindex="0"
      @keydown.prevent="onKeydown"
      @click="gridRef?.focus()"
    >
      <div
        v-for="(cell, i) in gridCells"
        :key="i"
        class="snake-cell absolute rounded-sm transition-colors duration-75"
        :style="cellStyles[i]"
      />
    </div>

    <p class="text-sm text-slate-500 dark:text-slate-400 text-center max-w-[320px]">
      Use arrow keys or WASD to move. Click the board then press a key to start.
    </p>

    <p v-if="dead" class="text-lg font-bold text-red-600 dark:text-red-400">Game over! Score: {{ score }}</p>
  </div>
</template>

<script setup>
import { ref, computed, onUnmounted, nextTick } from 'vue';

const emit = defineEmits(['close']);
const gridRef = ref(null);

const cols = 16;
const rows = 16;
const cellSizePx = 18;

const snake = ref([]);
const direction = ref({ dx: 1, dy: 0 });
const nextDirection = ref({ dx: 1, dy: 0 });
const apple = ref(-1);
const score = ref(0);
const started = ref(false);
const dead = ref(false);
const gameLoop = ref(null);

const boardStyle = computed(() => ({
  width: cols * cellSizePx + 'px',
  height: rows * cellSizePx + 'px',
  position: 'relative',
}));

const gridCells = computed(() => {
  const arr = Array(cols * rows).fill('empty');
  if (apple.value >= 0) arr[apple.value] = 'apple';
  snake.value.forEach((idx, i) => {
    arr[idx] = i === snake.value.length - 1 ? 'head' : 'body';
  });
  return arr;
});

const cellStyles = computed(() => {
  const colors = {
    empty: 'rgb(51 65 85)',
    body: 'rgb(34 197 94)',
    head: 'rgb(22 163 74)',
    apple: 'rgb(239 68 68)',
  };
  return gridCells.value.map((type, i) => {
    const x = (i % cols) * cellSizePx;
    const y = Math.floor(i / cols) * cellSizePx;
    return {
      left: x + 1 + 'px',
      top: y + 1 + 'px',
      width: cellSizePx - 2 + 'px',
      height: cellSizePx - 2 + 'px',
      backgroundColor: colors[type],
    };
  });
});

function idxToPos(i) {
  return { x: i % cols, y: Math.floor(i / cols) };
}
function posToIdx(x, y) {
  if (x < 0 || x >= cols || y < 0 || y >= rows) return -1;
  return y * cols + x;
}

function spawnApple() {
  const empty = [];
  for (let i = 0; i < cols * rows; i++) {
    if (!snake.value.includes(i)) empty.push(i);
  }
  if (empty.length) apple.value = empty[Math.floor(Math.random() * empty.length)];
}

function reset() {
  clearInterval(gameLoop.value);
  const head = posToIdx(Math.floor(cols / 2), Math.floor(rows / 2));
  snake.value = [head];
  direction.value = { dx: 1, dy: 0 };
  nextDirection.value = { dx: 1, dy: 0 };
  score.value = 0;
  dead.value = false;
  started.value = true;
  spawnApple();
  gameLoop.value = setInterval(tick, 120);
  nextTick(() => gridRef.value?.focus());
}

function onKeydown(e) {
  if (dead.value) return;
  const d = direction.value;
  const key = e.key.toLowerCase();
  if (['arrowup', 'w'].includes(key) && d.dy !== 1) nextDirection.value = { dx: 0, dy: -1 };
  else if (['arrowdown', 's'].includes(key) && d.dy !== -1) nextDirection.value = { dx: 0, dy: 1 };
  else if (['arrowleft', 'a'].includes(key) && d.dx !== 1) nextDirection.value = { dx: -1, dy: 0 };
  else if (['arrowright', 'd'].includes(key) && d.dx !== -1) nextDirection.value = { dx: 1, dy: 0 };
}

function tick() {
  if (dead.value) return;
  direction.value = { ...nextDirection.value };
  const head = snake.value[snake.value.length - 1];
  const { x, y } = idxToPos(head);
  const nx = x + direction.value.dx;
  const ny = y + direction.value.dy;
  const nidx = posToIdx(nx, ny);

  if (nidx < 0 || snake.value.includes(nidx)) {
    dead.value = true;
    clearInterval(gameLoop.value);
    return;
  }

  snake.value = [...snake.value.slice(1), nidx];

  if (nidx === apple.value) {
    score.value += 10;
    snake.value = [snake.value[0], ...snake.value];
    spawnApple();
  }
}

onUnmounted(() => {
  clearInterval(gameLoop.value);
});
</script>
