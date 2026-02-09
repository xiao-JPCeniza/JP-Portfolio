<template>
  <section id="contact" class="py-24 px-6 bg-white dark:bg-slate-900/50">
    <div class="max-w-2xl mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-3">
        <span class="w-1 h-10 rounded-full bg-gradient-to-b from-indigo-500 to-cyan-500" />
        Get in Touch
      </h2>
      <p class="text-slate-600 dark:text-slate-400 mb-10">
        Have a question or want to work together? Send me a message.
      </p>
      <form @submit.prevent="onSubmit" class="space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Name</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            placeholder="Your name"
          />
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            placeholder="your@email.com"
          />
        </div>
        <div>
          <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message</label>
          <textarea
            id="message"
            v-model="form.message"
            required
            rows="5"
            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"
            placeholder="Your message..."
          />
        </div>
        <button
          type="submit"
          class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-medium shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:scale-[1.02] transition-all duration-300"
        >
          Send Message
        </button>
      </form>
      <div class="mt-12 flex items-center justify-center gap-6">
        <a href="mailto:cenizajp12@gmail.com" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" aria-label="Email">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
          <span>Email</span>
        </a>
        <a href="https://www.linkedin.com/in/engr-jp-ceniza112" target="_blank" rel="noopener" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" aria-label="LinkedIn">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          <span>LinkedIn</span>
        </a>
        <a href="https://github.com/xiao-JPCeniza" target="_blank" rel="noopener" class="flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" aria-label="GitHub">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
          <span>GitHub</span>
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive } from 'vue';

const form = reactive({
  name: '',
  email: '',
  message: '',
});

function onSubmit() {
  // In a real app, POST to Laravel route (e.g. /api/contact)
  console.log('Submit:', form);
  alert('Message sent! (Demo – wire this to your Laravel backend.)');
  form.name = '';
  form.email = '';
  form.message = '';
}
</script>
