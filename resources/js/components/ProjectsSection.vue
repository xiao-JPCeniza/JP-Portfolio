<template>
  <section id="projects" class="py-24 px-6 bg-white dark:bg-slate-900/50">
    <div class="max-w-6xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-12 flex items-center gap-3">
        <span class="w-1 h-10 rounded-full bg-gradient-to-b from-indigo-500 to-cyan-500" />
        Projects & Portfolio
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="(project, i) in projects"
          :key="project.title"
          class="group rounded-2xl overflow-hidden shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border"
          :class="[
            project.theme.card,
            project.theme.border,
          ]"
        >
          <!-- Header: design from description (color + gradient only) -->
          <div
            class="h-40 flex flex-col items-center justify-center gap-2"
            :class="project.theme.header"
          >
            <span class="text-4xl" :class="project.theme.icon">{{ project.icon }}</span>
            <span class="text-xs font-semibold uppercase tracking-widest opacity-90" :class="project.theme.badge">
              {{ project.theme.badgeText }}
            </span>
          </div>
          <div class="p-6" :class="project.theme.body">
            <h3 class="text-xl font-semibold mb-2" :class="project.theme.title">{{ project.title }}</h3>
            <p class="text-sm mb-4 line-clamp-3" :class="project.theme.description">{{ project.description }}</p>
            <div class="flex flex-wrap gap-2 mb-4">
              <span
                v-for="tech in project.tech"
                :key="tech"
                class="px-2 py-1 rounded-lg text-xs font-medium"
                :class="project.theme.tech"
              >
                {{ tech }}
              </span>
            </div>
            <div class="flex gap-3">
              <a
                v-if="project.link && project.link !== '#'"
                :href="project.link"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-medium transition-colors"
                :class="project.theme.button"
              >
                View live
              </a>
              <a
                v-if="project.repo"
                :href="project.repo"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
              >
                Source
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const projects = [
  {
    title: 'OBO-IS',
    description: 'Office of the Building Official Information System — a digital platform that streamlines and automates permit applications, billing, inspections, and document management. It centralizes workflows, improves accuracy and transparency, and delivers faster public service within the LGU.',
    tech: ['Laravel', 'Livewire', 'MySQL', 'LGU'],
    icon: '🏢',
    link: 'https://obo-is.manolofortich.gov.ph',
    theme: {
      card: 'bg-amber-50/80 dark:bg-slate-800/80',
      border: 'border-amber-200/80 dark:border-amber-800/60',
      header: 'bg-gradient-to-br from-amber-100 via-amber-50 to-amber-200/80 dark:from-amber-900/60 dark:via-amber-950/40 dark:to-amber-800/50',
      icon: 'text-amber-700 dark:text-amber-400',
      badge: 'text-amber-700/90 dark:text-amber-400/90',
      badgeText: 'LGU Platform',
      body: 'bg-white/60 dark:bg-slate-800/40',
      title: 'text-slate-900 dark:text-white',
      description: 'text-slate-600 dark:text-slate-400',
      tech: 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200',
      button: 'bg-amber-600 hover:bg-amber-700',
    },
  },
  {
    title: 'MISO360',
    description: 'Management Information System Office 360 — an integrated digital platform that streamlines office operations, data management, and service delivery. It centralizes records, requests, and reporting into one system for better workflow, accuracy, and a 360-degree view of operations.',
    tech: ['Laravel', 'Vue.js', 'MySQL', 'Reporting'],
    icon: '📊',
    link: 'https://miso360.manolofortich.gov.ph/',
    theme: {
      card: 'bg-slate-50/80 dark:bg-slate-800/80',
      border: 'border-teal-200/80 dark:border-teal-800/60',
      header: 'bg-gradient-to-br from-teal-100 via-cyan-50 to-cyan-200/80 dark:from-teal-900/60 dark:via-cyan-950/40 dark:to-cyan-800/50',
      icon: 'text-teal-700 dark:text-teal-300',
      badge: 'text-teal-700/90 dark:text-teal-400/90',
      badgeText: '360° System',
      body: 'bg-white/60 dark:bg-slate-800/40',
      title: 'text-slate-900 dark:text-white',
      description: 'text-slate-600 dark:text-slate-400',
      tech: 'bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-200',
      button: 'bg-teal-600 hover:bg-teal-700',
    },
  },
  {
    title: 'API Text Blaster Module',
    description: 'Python-based module for fast, automated bulk SMS sending using the SM800C hardware module. Supports group messaging, scheduled sending, and delivery tracking for timely communication.',
    tech: ['Python', 'SM800C', 'Scheduling', 'Bulk Messaging'],
    icon: '📱',
    link: '#',
    theme: {
      card: 'bg-slate-50/80 dark:bg-slate-800/80',
      border: 'border-violet-200/80 dark:border-violet-800/60',
      header: 'bg-gradient-to-br from-violet-100 via-purple-50 to-emerald-200/80 dark:from-violet-900/60 dark:via-purple-950/40 dark:to-emerald-800/50',
      icon: 'text-violet-700 dark:text-violet-300',
      badge: 'text-violet-700/90 dark:text-violet-400/90',
      badgeText: 'SMS Module',
      body: 'bg-white/60 dark:bg-slate-800/40',
      title: 'text-slate-900 dark:text-white',
      description: 'text-slate-600 dark:text-slate-400',
      tech: 'bg-violet-100 text-violet-800 dark:bg-violet-900/50 dark:text-violet-200',
      button: 'bg-violet-600 hover:bg-violet-700',
    },
  },
];
</script>
