<template>
  <section id="experience" class="py-24 px-6 bg-slate-50 dark:bg-slate-950">
    <div class="max-w-4xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-12 flex items-center gap-3">
        <span class="w-1 h-10 rounded-full bg-gradient-to-b from-indigo-500 to-cyan-500" />
        Experience
      </h2>
      <div class="relative">
        <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 -translate-x-px bg-gradient-to-b from-indigo-300 via-cyan-300 to-slate-200 dark:from-indigo-700 dark:via-cyan-700 dark:to-slate-800" />
        <div
          v-for="(job, i) in experience"
          :key="job.title"
          class="relative pl-12 md:pl-0 md:flex md:odd:flex-row-reverse md:gap-8 mb-12 last:mb-0"
        >
          <div class="md:w-1/2 md:pr-8 md:odd:pr-0 md:odd:pl-8 md:odd:text-right">
            <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ job.period }}</span>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ job.title }}</h3>
            <p class="text-slate-600 dark:text-slate-400 font-medium">{{ job.company }}</p>
          </div>
          <div class="absolute left-4 md:left-1/2 top-1 w-4 h-4 -translate-x-1/2 rounded-full bg-indigo-500 dark:bg-indigo-400 border-4 border-white dark:border-slate-900 shadow" />
          <div class="md:w-1/2 mt-2 md:mt-0 md:pl-8 md:odd:pl-0 md:odd:pr-8 md:odd:text-left">
            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
              {{ job.description }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const experience = [
  {
    title: 'System Engineer',
    company: 'Current Role',
    period: 'Present',
    description: 'Designing and maintaining infrastructure, automating deployments, and supporting development teams. Focus on reliability, security, and continuous improvement of systems and processes.',
  },
  {
    title: 'Computer Engineering Graduate',
    company: 'University',
    period: 'Graduated',
    description: 'Completed degree with focus on software development, networking, and system design. Built several academic and personal projects involving full-stack development and system administration.',
  },
];
</script>
