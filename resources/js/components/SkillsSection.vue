<template>
  <section id="skills" class="py-24 px-6 bg-slate-50 dark:bg-slate-950">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-12 flex items-center gap-3">
        <span class="w-1 h-10 rounded-full bg-gradient-to-b from-indigo-500 to-cyan-500" />
        Skills
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(skill, i) in skills"
          :key="skill.category"
          class="group p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-lg hover:shadow-xl hover:border-indigo-200 dark:hover:border-indigo-800 transition-all duration-300"
          :style="{ animationDelay: `${i * 50}ms` }"
        >
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="text-2xl">{{ skill.icon }}</span>
            {{ skill.category }}
          </h3>
          <div class="space-y-3">
            <div v-for="item in skill.items" :key="item.name" class="flex items-center justify-between gap-2">
              <span class="text-slate-600 dark:text-slate-400 text-sm">{{ item.name }}</span>
              <div class="flex-1 max-w-[120px] h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                <div
                  class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 transition-all duration-700"
                  :style="{ width: item.percent + '%' }"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-12">
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Tools & Technologies</h3>
        <div class="flex flex-wrap gap-3">
          <span
            v-for="tool in tools"
            :key="tool"
            class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-200"
          >
            {{ tool }}
          </span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const skills = [
  {
    category: 'Laravel & Frontend',
    icon: '🌐',
    items: [
      { name: 'Laravel Livewire', percent: 80 },
      { name: 'Laravel Vue', percent: 65 },
    ],
  },
  {
    category: 'Infrastructure',
    icon: '🖥️',
    items: [
      { name: 'System Administration', percent: 45 },
      { name: 'Networking', percent: 55 },
    ],
  },
  {
    category: 'Data & Programming',
    icon: '💻',
    items: [
      { name: 'Database', percent: 45 },
      { name: 'Python Programming', percent: 65 },
    ],
  },
];

const tools = [
  'Laravel', 'Livewire', 'Vue.js', 'PHP', 'Tailwind CSS',
  'Python', 'MySQL', 'Git', 'VS Code', 'Linux', 'Windows Server',
];
</script>
