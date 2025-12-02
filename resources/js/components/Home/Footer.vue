<template>
  <footer class="bg-[#1b5e20] text-white py-16 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Top Section -->
      <div
        class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-10 lg:gap-12 mb-12 pb-12 border-b border-white border-opacity-20"
      >
        <!-- Logo & Description -->
        <div class="space-y-4 col-span-2 sm:col-span-1">
          <div class="w-12 h-12 rounded-lg bg-[#ffc107] flex items-center justify-center">
            <span class="font-black text-[#1b5e20] text-lg">SB</span>
          </div>
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#ffc107]">
              Sangguniang Bayan
            </p>
            <h3 class="text-lg font-bold">Concepcion, Tarlac</h3>
          </div>
          <p class="text-sm text-white text-opacity-80 leading-relaxed">
            Committed to public service, transparency, and sustainable local governance.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">
            Quick Links
          </h4>
          <ul class="space-y-3">
            <li><a href="/home" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Home</a></li>
            <li><a href="/sb" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">About SB</a></li>
            <li><a href="/members" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Council Members</a></li>
            <li><a href="/sessions" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Sessions</a></li>
          </ul>
        </div>

        <!-- Legislation -->
        <div>
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">Legislation</h4>
          <ul class="space-y-3">
            <li><a href="/ordinances" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Ordinances</a></li>
            <li><a href="/resolutions" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Resolutions</a></li>
            <li><a href="/documents" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Public Records</a></li>
            <li><a href="/transparency" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Transparency Portal</a></li>
          </ul>
        </div>

        <!-- Stay Connected -->
        <div class="col-span-2 sm:col-span-1">
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">Stay Connected</h4>
          <div class="flex gap-4 mb-6">
            <a
  v-for="social in socials"
  :key="social.name"
  :href="social.url"
  target="_blank"
  :aria-label="social.name"
  class="w-10 h-10 bg-transparent hover:bg-[#ffc107] rounded-full flex items-center justify-center transition"
>
  <component
    :is="social.icon"
    class="w-5 h-5 text-white hover:text-[#1b5e20] transition"
  />
</a>

          </div>
          <p class="text-sm font-bold text-white mb-1">(045) 923-4567</p>
          <p class="text-sm text-white text-opacity-80">sangguniangbayan@concepcion.gov.ph</p>
        </div>
      </div>

      <!-- Bottom -->
      <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-white text-opacity-70">
        <p>© 2025 Sangguniang Bayan, Concepcion, Tarlac. All rights reserved.</p>
        <div class="flex gap-6">
          <a href="#" class="hover:text-[#ffc107] transition">Privacy Policy</a>
          <a href="#" class="hover:text-[#ffc107] transition">Disclaimer</a>
          <a href="#" class="hover:text-[#ffc107] transition">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Facebook, Twitter, Mail } from 'lucide-vue-next'

const socials = [
  { name: 'Facebook', icon: Facebook, url: 'https://facebook.com' },
  { name: 'TikTok', icon: Mail, url: 'https://tiktok.com' }, // Lucide doesn’t have TikTok, use custom SVG later if needed
  { name: 'Twitter', icon: Twitter, url: 'https://twitter.com' },
  { name: 'Email', icon: Mail, url: 'mailto:sangguniangbayan@concepcion.gov.ph' },
]
</script>
