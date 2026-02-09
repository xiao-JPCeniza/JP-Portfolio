<template>
  <nav class="fixed top-0 left-0 right-0 z-40 py-4 px-6 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md border-b border-slate-200/50 dark:border-slate-800/50 transition-colors duration-300">
    <div class="max-w-6xl mx-auto flex items-center justify-between">
      <a href="#hero" class="text-lg font-semibold bg-gradient-to-r from-indigo-600 to-cyan-500 dark:from-indigo-400 dark:to-cyan-400 bg-clip-text text-transparent">
        Portfolio
      </a>
      <ul class="hidden md:flex items-center gap-8">
        <li><a href="#about" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200">About</a></li>
        <li><a href="#skills" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200">Skills</a></li>
        <li><a href="#projects" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200">Projects</a></li>
        <li><a href="#experience" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200">Experience</a></li>
        <li><a href="#contact" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200">Contact</a></li>
      </ul>
      <button
        type="button"
        class="md:hidden p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800"
        aria-label="Menu"
        @click="mobileOpen = !mobileOpen"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <Transition name="slide">
      <ul v-if="mobileOpen" class="md:hidden mt-4 flex flex-col gap-2 pb-4">
        <li><a href="#about" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200 block py-2" @click="mobileOpen = false">About</a></li>
        <li><a href="#skills" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200 block py-2" @click="mobileOpen = false">Skills</a></li>
        <li><a href="#projects" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200 block py-2" @click="mobileOpen = false">Projects</a></li>
        <li><a href="#experience" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200 block py-2" @click="mobileOpen = false">Experience</a></li>
        <li><a href="#contact" class="text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition-colors duration-200 block py-2" @click="mobileOpen = false">Contact</a></li>
      </ul>
    </Transition>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
const mobileOpen = ref(false);
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.2s ease;
}
.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
