<template>
  <footer class="bg-[#1b5e20] text-white py-16 px-4">
    <div class="max-w-7xl mx-auto">
      <div
        class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-10 lg:gap-12 mb-12 pb-12 border-b border-white border-opacity-20"
      >
        <div class="space-y-4 col-span-2 sm:col-span-1">
          <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg">
            <img src="/images/logo.jpg" alt="Logo" class="w-full h-full object-cover" />
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

        <div>
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">
            Quick Links
          </h4>
          <ul class="space-y-3">
            <li><a href="/" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Home</a></li>
            <li><a href="/organizational-chart" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Organizational Chart</a></li>
            <li><a href="/legislative-sessions" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Sessions</a></li>
             <li><a href="/library" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Library</a></li>
          </ul>
        </div>

        <div>
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">Citizens Charter</h4>
          <ul class="space-y-3">
             <li><a href="/citizens-charter" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Citizens Charter</a></li>
            <li><a href="/citizens-charter/ordinances" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Ordinances</a></li>
            <li><a href="/citizens-charter/resolutions" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Resolutions</a></li>
          </ul>
        </div>

        <div class="col-span-2 sm:col-span-1">
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">Stay Connected</h4>
          <p class="text-sm font-bold text-white mb-1">(045) 628-2127</p>
          <p class="text-sm text-white text-opacity-80">sbocontar@gmail.com</p>
        </div>

         <div>
          <h4 class="font-extrabold text-[#ffc107] mb-5 text-sm uppercase tracking-wider">Other Links</h4>
          <ul class="space-y-3">
            <li><a href="https://concepciontarlac.org/" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">Municipal Concepcion, Tarlac</a></li>
            <li><a href="https://www.facebook.com/profile.php?id=100076454470296" class="text-white text-opacity-80 hover:text-[#ffc107] transition text-sm">DILG - Concepcion, Tarlac</a></li>
          </ul>
        </div>
      </div>

      <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-white text-opacity-70">
        <p>© 2026 Sangguniang Bayan, Concepcion, Tarlac. All rights reserved.</p>
        <div class="flex gap-6">
          <a href="/privacy-policy" class="hover:text-[#ffc107] transition">Privacy Policy</a>
          <a href="#" class="hover:text-[#ffc107] transition">Disclaimer</a>
          <a href="#" class="hover:text-[#ffc107] transition">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>
</template>
<script setup>
import { Facebook, Twitter, Mail } from 'lucide-vue-next'

// const socials = [
//   { name: 'Facebook', icon: Facebook, url: 'https://facebook.com' },
//   { name: 'TikTok', icon: Mail, url: 'https://tiktok.com' }, // Lucide doesn’t have TikTok, use custom SVG later if needed
//   { name: 'Twitter', icon: Twitter, url: 'https://twitter.com' },
//   { name: 'Email', icon: Mail, url: 'mailto:sangguniangbayan@concepcion.gov.ph' },
// ]
</script>
