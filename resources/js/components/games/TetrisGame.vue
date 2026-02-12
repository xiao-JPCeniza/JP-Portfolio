<template>
  <div class="tetris-game flex flex-col items-center gap-4">
    <div class="flex items-center justify-between w-full max-w-[320px]">
      <span class="text-slate-600 dark:text-slate-400 font-medium">Score: {{ score }} · Level {{ level }}</span>
      <button
        type="button"
        class="px-3 py-1.5 rounded-lg bg-cyan-600 text-white text-sm font-medium hover:bg-cyan-500 transition-colors"
        @click="reset"
      >
        {{ started ? 'Restart' : 'Start' }}
      </button>
    </div>

    <div
      ref="boardRef"
      class="tetris-board shrink-0 rounded-xl border-2 border-slate-600 dark:border-slate-500 overflow-hidden bg-slate-800 dark:bg-slate-900 select-none outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 dark:focus:ring-offset-slate-900"
      :style="boardStyle"
      tabindex="0"
      @keydown.prevent="onKeydown"
      @click="boardRef?.focus()"
    >
      <div
        v-for="(cell, i) in displayGrid"
        :key="i"
        class="absolute rounded-sm transition-colors duration-75"
        :style="cellStyle(i)"
      />
    </div>

    <p class="text-sm text-slate-500 dark:text-slate-400 text-center max-w-[320px]">
      ← → move · ↑ rotate · ↓ soft drop · Space hard drop. Click board then use keys.
    </p>

    <p v-if="gameOver" class="text-lg font-bold text-red-600 dark:text-red-400">Game over! Score: {{ score }}</p>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';

const COLS = 10;
const ROWS = 20;
const CELL_PX = 18;

const SHAPES = [
  { name: 'I', color: 'rgb(0 212 255)', matrix: [[1,1,1,1]] },
  { name: 'O', color: 'rgb(255 213 0)', matrix: [[1,1],[1,1]] },
  { name: 'T', color: 'rgb(168 85 247)', matrix: [[0,1,0],[1,1,1]] },
  { name: 'S', color: 'rgb(34 197 94)', matrix: [[0,1,1],[1,1,0]] },
  { name: 'Z', color: 'rgb(239 68 68)', matrix: [[1,1,0],[0,1,1]] },
  { name: 'J', color: 'rgb(59 130 246)', matrix: [[1,0,0],[1,1,1]] },
  { name: 'L', color: 'rgb(249 115 22)', matrix: [[0,0,1],[1,1,1]] },
];

const boardRef = ref(null);
const grid = ref([]);
const current = ref(null);
const pos = ref({ x: 0, y: 0 });
const score = ref(0);
const level = ref(1);
const lines = ref(0);
const started = ref(false);
const gameOver = ref(false);
const intervalId = ref(null);

function initGrid() {
  grid.value = Array(COLS * ROWS).fill(0);
}

function randomPiece() {
  const s = SHAPES[Math.floor(Math.random() * SHAPES.length)];
  return { ...s, matrix: s.matrix.map(row => [...row]) };
}

function rotateMatrix(m) {
  const rows = m.length;
  const cols = m[0].length;
  return Array.from({ length: cols }, (_, c) => Array.from({ length: rows }, (_, r) => m[rows - 1 - r][c]));
}

function collide(p, px, py) {
  const m = p.matrix;
  for (let r = 0; r < m.length; r++) {
    for (let c = 0; c < m[0].length; c++) {
      if (!m[r][c]) continue;
      const nx = px + c;
      const ny = py + r;
      if (nx < 0 || nx >= COLS || ny >= ROWS) return true;
      if (ny >= 0 && grid.value[ny * COLS + nx]) return true;
    }
  }
  return false;
}

function merge() {
  const p = current.value;
  const m = p.matrix;
  const px = pos.value.x;
  const py = pos.value.y;
  const color = p.color;
  const next = [...grid.value];
  for (let r = 0; r < m.length; r++) {
    for (let c = 0; c < m[0].length; c++) {
      if (!m[r][c]) continue;
      const ny = py + r;
      const nx = px + c;
      if (ny >= 0 && ny < ROWS && nx >= 0 && nx < COLS) {
        next[ny * COLS + nx] = color;
      }
    }
  }
  grid.value = next;
}

function clearFilledRows() {
  let cleared = 0;
  const newGrid = [];
  for (let r = ROWS - 1; r >= 0; r--) {
    const row = grid.value.slice(r * COLS, (r + 1) * COLS);
    if (row.every(Boolean)) {
      cleared++;
    } else {
      newGrid.unshift(row);
    }
  }
  for (let i = 0; i < cleared; i++) {
    newGrid.unshift(Array(COLS).fill(0));
  }
  grid.value = newGrid.flat();
  if (cleared > 0) {
    const points = [0, 100, 300, 500, 800];
    score.value += (points[cleared] ?? 800) * level.value;
    lines.value += cleared;
    level.value = Math.floor(lines.value / 10) + 1;
  }
}

function spawn() {
  const piece = randomPiece();
  const px = Math.floor((COLS - piece.matrix[0].length) / 2);
  if (collide(piece, px, 0)) {
    gameOver.value = true;
    clearInterval(intervalId.value);
    return;
  }
  current.value = piece;
  pos.value = { x: px, y: 0 };
}

function tick() {
  if (gameOver.value || !current.value) return;
  const py = pos.value.y + 1;
  if (collide(current.value, pos.value.x, py)) {
    merge();
    clearFilledRows();
    spawn();
  } else {
    pos.value = { ...pos.value, y: py };
  }
}

function move(dx) {
  if (gameOver.value || !current.value) return;
  const px = pos.value.x + dx;
  if (!collide(current.value, px, pos.value.y)) {
    pos.value = { ...pos.value, x: px };
  }
}

function rotate() {
  if (gameOver.value || !current.value) return;
  const rotated = { ...current.value, matrix: rotateMatrix(current.value.matrix) };
  if (!collide(rotated, pos.value.x, pos.value.y)) {
    current.value = rotated;
  }
}

function hardDrop() {
  if (gameOver.value || !current.value) return;
  let py = pos.value.y;
  let dropDistance = 0;
  while (!collide(current.value, pos.value.x, py + 1)) {
    py++;
    dropDistance++;
  }
  pos.value = { ...pos.value, y: py };
  score.value += dropDistance * 2;
  merge();
  clearFilledRows();
  spawn();
}

function softDrop() {
  if (gameOver.value || !current.value) return;
  const py = pos.value.y + 1;
  if (!collide(current.value, pos.value.x, py)) {
    pos.value = { ...pos.value, y: py };
    score.value += 1;
  }
}

function onKeydown(e) {
  if (gameOver.value) return;
  const key = e.key;
  if (key === 'ArrowLeft' || key === 'a') move(-1);
  else if (key === 'ArrowRight' || key === 'd') move(1);
  else if (key === 'ArrowUp' || key === 'w') rotate();
  else if (key === 'ArrowDown' || key === 's') softDrop();
  else if (key === ' ') hardDrop();
}

const displayGrid = computed(() => {
  const out = [...grid.value];
  const p = current.value;
  if (p) {
    const m = p.matrix;
    const px = pos.value.x;
    const py = pos.value.y;
    for (let r = 0; r < m.length; r++) {
      for (let c = 0; c < m[0].length; c++) {
        if (!m[r][c]) continue;
        const ny = py + r;
        const nx = px + c;
        if (ny >= 0 && ny < ROWS && nx >= 0 && nx < COLS) {
          out[ny * COLS + nx] = p.color;
        }
      }
    }
  }
  return out;
});

const boardStyle = computed(() => ({
  width: COLS * CELL_PX + 'px',
  height: ROWS * CELL_PX + 'px',
  position: 'relative',
}));

function cellStyle(i) {
  const color = displayGrid.value[i];
  const x = (i % COLS) * CELL_PX;
  const y = Math.floor(i / COLS) * CELL_PX;
  return {
    left: x + 1 + 'px',
    top: y + 1 + 'px',
    width: CELL_PX - 2 + 'px',
    height: CELL_PX - 2 + 'px',
    backgroundColor: color || 'rgb(51 65 85)',
  };
}

function reset() {
  clearInterval(intervalId.value);
  initGrid();
  current.value = null;
  pos.value = { x: 0, y: 0 };
  score.value = 0;
  level.value = 1;
  lines.value = 0;
  gameOver.value = false;
  started.value = true;
  spawn();
  intervalId.value = setInterval(tick, getSpeed());
  nextTick(() => boardRef.value?.focus());
}

function getSpeed() {
  return Math.max(80, 500 - (level.value - 1) * 40);
}

watch(level, () => {
  if (gameOver.value || !intervalId.value) return;
  clearInterval(intervalId.value);
  intervalId.value = setInterval(tick, getSpeed());
});

onMounted(() => {});
onUnmounted(() => clearInterval(intervalId.value));
</script>
