<template>
  <div class="block-blast flex flex-col items-center gap-5">
    <div class="flex items-center justify-between w-full max-w-[320px]">
      <span class="text-slate-600 dark:text-slate-400 font-semibold">Score: {{ score }}</span>
      <button
        type="button"
        class="px-4 py-2 rounded-xl bg-violet-600 text-white text-sm font-medium hover:bg-violet-500 active:scale-[0.98] transition-all shadow-md"
        @click="reset"
      >
        New game
      </button>
    </div>

    <!-- Board: drop zone -->
    <div class="relative inline-block">
      <div
        ref="boardRef"
        class="inline-grid gap-0.5 p-2 rounded-xl bg-slate-300 dark:bg-slate-600 border-2 border-slate-400 dark:border-slate-500"
        :style="{ gridTemplateColumns: `repeat(${cols}, ${cellSize}px)`, gridTemplateRows: `repeat(${rows}, ${cellSize}px)` }"
        @dragover.prevent="onDragOver"
        @dragleave="dragOverCell = -1"
        @drop.prevent="onDrop"
      >
        <div
          v-for="(cell, i) in grid"
          :key="i"
          class="rounded border-2 transition-colors select-none"
          :class="[
            cell ? 'bg-violet-500 dark:bg-violet-600 border-violet-600 dark:border-violet-500' : 'bg-slate-100 dark:bg-slate-800 border-slate-300 dark:border-slate-600',
            dragOverCell === i && canPlaceAt(i) ? 'ring-2 ring-violet-400 ring-offset-1 bg-violet-200/50 dark:bg-violet-900/30' : '',
          ]"
          :style="{ width: cellSize + 'px', height: cellSize + 'px' }"
        />
      </div>
      <!-- Drop preview overlay when dragging -->
      <div
        v-if="currentPiece && dragOverCell >= 0 && canPlaceAt(dragOverCell)"
        class="absolute inset-0 pointer-events-none p-2"
        aria-hidden="true"
      >
        <div
          v-for="(filled, i) in currentPiece.shape"
          :key="'preview-' + i"
          class="absolute rounded border-2 border-violet-400 bg-violet-400/50 dark:bg-violet-500/50"
          :class="{ 'invisible': !filled }"
          :style="previewBlockStyle(i)"
        />
      </div>
    </div>

    <p class="text-sm text-slate-500 dark:text-slate-400 text-center">Drag the piece onto the board to place it</p>

    <!-- Current piece: draggable -->
    <div class="flex flex-col items-center gap-2">
      <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Current piece — drag to board</p>
      <div
        v-if="currentPiece && !gameOver"
        draggable="true"
        class="inline-grid gap-0.5 p-2 rounded-xl bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-violet-400 cursor-grab active:cursor-grabbing select-none touch-none"
        :style="{ gridTemplateColumns: `repeat(${currentPiece.w}, ${cellSize}px)`, gridTemplateRows: `repeat(${currentPiece.h}, ${cellSize}px)` }"
        @dragstart="onDragStart"
        @dragend="onDragEnd"
      >
        <div
          v-for="(filled, i) in currentPiece.shape"
          :key="i"
          class="rounded border-2 bg-violet-500 dark:bg-violet-600 border-violet-600 dark:border-violet-500 transition-shadow"
          :class="{ 'invisible': !filled, 'shadow-lg': filled }"
          :style="{ width: cellSize + 'px', height: cellSize + 'px' }"
        />
      </div>
      <div v-else-if="gameOver" class="py-4 text-slate-500 dark:text-slate-400 text-sm">No more moves</div>
    </div>

    <!-- Next pieces (preview only) -->
    <div class="flex flex-wrap justify-center gap-3">
      <p class="text-sm font-medium text-slate-700 dark:text-slate-300 w-full text-center">Next pieces</p>
      <div
        v-for="(piece, pIdx) in nextPieces"
        :key="pIdx"
        class="inline-grid gap-0.5 p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700"
        :style="{ gridTemplateColumns: `repeat(${piece.w}, ${cellSize - 6}px)`, gridTemplateRows: `repeat(${piece.h}, ${cellSize - 6}px)` }"
      >
        <div
          v-for="(filled, i) in piece.shape"
          :key="i"
          class="rounded bg-slate-400 dark:bg-slate-500"
          :class="{ 'invisible': !filled }"
          :style="{ width: (cellSize - 6) + 'px', height: (cellSize - 6) + 'px' }"
        />
      </div>
    </div>

    <p v-if="gameOver" class="text-lg font-bold text-red-600 dark:text-red-400">No moves left! Final score: {{ score }}</p>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const emit = defineEmits(['close']);
const boardRef = ref(null);

const cols = 10;
const rows = 10;
const cellSize = 28;
const grid = ref([]);
const score = ref(0);
const currentPiece = ref(null);
const nextPieces = ref([]);
const gameOver = ref(false);
const canPlace = ref(true);
const isDragging = ref(false);
const dragOverCell = ref(-1);

const PIECES = [
  { w: 1, h: 1, shape: [1] },
  { w: 2, h: 1, shape: [1, 1] },
  { w: 1, h: 2, shape: [1, 1] },
  { w: 3, h: 1, shape: [1, 1, 1] },
  { w: 1, h: 3, shape: [1, 1, 1] },
  { w: 2, h: 2, shape: [1, 1, 1, 1] },
  { w: 2, h: 2, shape: [1, 1, 0, 1] },
  { w: 2, h: 2, shape: [1, 0, 1, 1] },
  { w: 3, h: 2, shape: [1, 1, 1, 1, 0, 0] },
  { w: 3, h: 2, shape: [0, 0, 1, 1, 1, 1] },
  { w: 2, h: 3, shape: [1, 1, 1, 0, 1, 0] },
  { w: 2, h: 3, shape: [1, 0, 1, 1, 1, 0] },
  { w: 3, h: 2, shape: [1, 0, 0, 1, 1, 1] },
  { w: 3, h: 2, shape: [0, 0, 1, 1, 1, 1] },
  { w: 2, h: 2, shape: [1, 1, 1, 1] },
  { w: 3, h: 1, shape: [1, 1, 1] },
  { w: 1, h: 2, shape: [1, 1] },
  { w: 2, h: 1, shape: [1, 1] },
];

function initGrid() {
  grid.value = Array(cols * rows).fill(0);
}

function randomPiece() {
  return { ...PIECES[Math.floor(Math.random() * PIECES.length)] };
}

function fillNextPieces() {
  while (nextPieces.value.length < 3) {
    nextPieces.value.push(randomPiece());
  }
}

function takeNextPiece() {
  if (!nextPieces.value.length) fillNextPieces();
  currentPiece.value = nextPieces.value.shift();
  fillNextPieces();
}

function getRow(i) {
  return Math.floor(i / cols);
}
function getCol(i) {
  return i % cols;
}

function canPlaceAt(originIndex) {
  const piece = currentPiece.value;
  if (!piece) return false;
  const row0 = getRow(originIndex);
  const col0 = getCol(originIndex);
  for (let r = 0; r < piece.h; r++) {
    for (let c = 0; c < piece.w; c++) {
      const cellIdx = piece.shape[r * piece.w + c];
      if (!cellIdx) continue;
      const nr = row0 + r;
      const nc = col0 + c;
      if (nr < 0 || nr >= rows || nc < 0 || nc >= cols) return false;
      const idx = nr * cols + nc;
      if (grid.value[idx]) return false;
    }
  }
  return true;
}

function placeOnGrid(originIndex) {
  if (gameOver.value || !currentPiece.value || !canPlace.value) return;
  if (!canPlaceAt(originIndex)) return;

  const piece = currentPiece.value;
  const row0 = getRow(originIndex);
  const col0 = getCol(originIndex);
  const next = [...grid.value];

  for (let r = 0; r < piece.h; r++) {
    for (let c = 0; c < piece.w; c++) {
      if (piece.shape[r * piece.w + c]) {
        const idx = (row0 + r) * cols + (col0 + c);
        next[idx] = 1;
      }
    }
  }
  grid.value = next;
  clearLines();
  score.value += piece.shape.filter(Boolean).length;
  takeNextPiece();
  checkGameOver();
}

function clearLines() {
  const g = grid.value;
  if (g.length !== cols * rows) return;
  const fullRows = [];
  for (let r = 0; r < rows; r++) {
    let full = true;
    for (let c = 0; c < cols; c++) {
      if (!g[r * cols + c]) {
        full = false;
        break;
      }
    }
    if (full) fullRows.push(r);
  }
  if (fullRows.length === 0) return;
  // Remove full rows and add empty rows at top so blocks "fall"
  const keptRows = [];
  for (let r = 0; r < rows; r++) {
    if (fullRows.includes(r)) continue;
    keptRows.push(g.slice(r * cols, (r + 1) * cols));
  }
  const emptyRows = Array.from({ length: fullRows.length }, () => Array.from({ length: cols }, () => 0));
  grid.value = [...emptyRows.flat(), ...keptRows.flat()];
  score.value += fullRows.length * cols * 10;
}

function checkGameOver() {
  const piece = currentPiece.value;
  if (!piece) return;
  for (let i = 0; i < cols * rows; i++) {
    if (canPlaceAt(i)) return;
  }
  gameOver.value = true;
  canPlace.value = false;
}

function reset() {
  initGrid();
  score.value = 0;
  gameOver.value = false;
  canPlace.value = true;
  nextPieces.value = [];
  dragOverCell.value = -1;
  takeNextPiece();
}

// Drag and drop
function onDragStart(e) {
  if (gameOver.value || !currentPiece.value) return;
  isDragging.value = true;
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/plain', 'block');
  e.dataTransfer.setDragImage(e.target, 0, 0);
}

function onDragEnd() {
  isDragging.value = false;
  dragOverCell.value = -1;
}

function getCellIndexFromEvent(e) {
  if (!boardRef.value) return -1;
  const rect = boardRef.value.getBoundingClientRect();
  const padding = 8;
  const x = (e.clientX ?? e.touches?.[0]?.clientX) - rect.left - padding;
  const y = (e.clientY ?? e.touches?.[0]?.clientY) - rect.top - padding;
  const col = Math.floor(x / (cellSize + 2));
  const row = Math.floor(y / (cellSize + 2));
  if (col < 0 || col >= cols || row < 0 || row >= rows) return -1;
  return row * cols + col;
}

function onDragOver(e) {
  const idx = getCellIndexFromEvent(e);
  dragOverCell.value = idx >= 0 ? idx : -1;
}

function onDrop(e) {
  const idx = getCellIndexFromEvent(e);
  if (idx >= 0) placeOnGrid(idx);
  dragOverCell.value = -1;
}

function previewBlockStyle(shapeIndex) {
  if (!currentPiece.value || dragOverCell.value < 0) return { visibility: 'hidden' };
  const piece = currentPiece.value;
  const originRow = getRow(dragOverCell.value);
  const originCol = getCol(dragOverCell.value);
  const r = Math.floor(shapeIndex / piece.w);
  const c = shapeIndex % piece.w;
  if (!piece.shape[shapeIndex]) return { visibility: 'hidden' };
  return {
    left: (8 + (originCol + c) * (cellSize + 2) + 2) + 'px',
    top: (8 + (originRow + r) * (cellSize + 2) + 2) + 'px',
    width: (cellSize - 2) + 'px',
    height: (cellSize - 2) + 'px',
    position: 'absolute',
  };
}

onMounted(() => {
  initGrid();
  fillNextPieces();
  takeNextPiece();
});
</script>

<style scoped>
.block-blast [draggable="true"] {
  -webkit-user-drag: element;
}
</style>
