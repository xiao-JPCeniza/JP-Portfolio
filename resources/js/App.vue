<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300" :class="{ 'dark': isDark }">
    <ThemeToggle :is-dark="isDark" @toggle="isDark = !isDark" />
    <NavBar />
    <main>
      <HeroSection />
      <AboutSection />
      <SkillsSection />
      <ProjectsSection />
      <ExperienceSection />
      <ContactSection />
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import ThemeToggle from './components/ThemeToggle.vue';
import NavBar from './components/NavBar.vue';
import HeroSection from './components/HeroSection.vue';
import AboutSection from './components/AboutSection.vue';
import SkillsSection from './components/SkillsSection.vue';
import ProjectsSection from './components/ProjectsSection.vue';
import ExperienceSection from './components/ExperienceSection.vue';
import ContactSection from './components/ContactSection.vue';
import AppFooter from './components/AppFooter.vue';

const isDark = ref(false);

onMounted(() => {
  const saved = localStorage.getItem('theme');
  if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    isDark.value = true;
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
});

watch(isDark, (val) => {
  document.documentElement.classList.toggle('dark', val);
  localStorage.setItem('theme', val ? 'dark' : 'light');
});
</script>
