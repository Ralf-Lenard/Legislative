<template>
  <header class="fixed top-0 left-0 w-full z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">

        <div class="flex items-center gap-3 cursor-pointer">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#1b5e20] to-[#0d3d1a] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm-1 15.02L6.98 13.01l1.41-1.41L11 14.2l5.61-5.61 1.41 1.41L11 17.02z"/>
            </svg>
          </div>
          <h1 class="text-lg font-bold text-gray-900 tracking-tight">
            Sangguniang Bayan
          </h1>
        </div>

        <div class="flex items-center gap-6">

          <nav class="hidden lg:flex items-center gap-8">
            <a
              v-for="link in navLinks"
              :key="link.path"
              :href="link.path"
              @click="setActive(link.path)"
              :class="[
                'text-sm font-semibold transition-colors duration-200 pb-1 border-b-2',
                activeLink === link.path
                  ? 'text-[#1b5e20] border-[#ffc107]'
                  : 'text-gray-600 border-transparent hover:text-[#1b5e20] hover:border-[#ffc107]'
              ]"
            >
              {{ link.label }}
            </a>
          </nav>

          <div class="hidden md:flex items-center gap-3 relative">
            <template v-if="user">
                <button
                @click="profileOpen = !profileOpen"
                class="flex items-center gap-3 px-5 py-2 bg-[#1b5e20] text-white rounded-lg hover:bg-[#0d3d1a] transition font-bold shadow-md relative"
              >
                <div class="w-6 h-6 rounded-full bg-[#ffc107] text-[#1b5e20] flex items-center justify-center font-bold uppercase text-xs overflow-hidden border-2 border-white">
                  <img v-if="user.profile_photo_url" :src="user.profile_photo_url" :alt="user.name" class="w-full h-full object-cover"/>
                  <span v-else>{{ user.name.charAt(0) }}</span>
                </div>
                {{ user.name }}
                <svg :class="['w-4 h-4 transition-transform duration-200', profileOpen ? 'transform rotate-180' : '']" 
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>

              <div v-if="profileOpen" 
                class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden z-50">
                
                <div class="p-3 border-b border-gray-100 bg-gray-50">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ user.name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                </div>

                <Link href="/profile" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-[#1b5e20] transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A5 5 0 0112 15a5 5 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Profile Settings
                </Link>
                <Link href="/logout" method="post" as="button" 
                  class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                  </svg>
                  Log Out
                </Link>
              </div>
            </template>

            <template v-else>
                <Link :href="login()" class="inline-block rounded-lg px-4 py-2 text-sm font-semibold border border-[#1b5e20] text-[#1b5e20] hover:bg-[#1b5e20] hover:text-white transition-colors">
                Log in
              </Link>
              <Link v-if="canRegister" :href="register()" class="inline-block rounded-lg px-4 py-2 text-sm font-semibold bg-[#ffc107] text-[#1b5e20] hover:bg-[#ffb300] shadow-md transition-colors">
                Register
              </Link>
            </template>
          </div>

          <button
            class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-900 hover:bg-gray-100 rounded-lg transition"
            @click="isOpen = !isOpen"
          >
            {{ isOpen ? '✕' : '☰' }}
          </button>
        </div>
      </div>

      <nav v-if="isOpen" class="lg:hidden pb-4 pt-2 space-y-2">
        <a
          v-for="link in navLinks"
          :key="link.path"
          :href="link.path"
          @click="setActive(link.path)"
          :class="[
            'block px-4 py-2 rounded-lg text-sm font-medium transition-colors',
            activeLink === link.path
              ? 'bg-[#ffc107] text-[#1b5e20]'
              : 'text-gray-700 hover:bg-gray-100'
          ]"
        >
          {{ link.label }}
        </a>

        <div class="px-4 mt-3 space-y-2">
          <template v-if="user">
            <button @click="profileOpenMobile = !profileOpenMobile"
              class="w-full px-4 py-2 text-left rounded-lg bg-gray-100 font-semibold hover:bg-gray-200 flex items-center gap-2">
              <div class="w-6 h-6 rounded-full bg-[#ffc107] text-[#1b5e20] flex items-center justify-center font-bold uppercase text-xs overflow-hidden">
                <img v-if="user.profile_photo_url" :src="user.profile_photo_url" alt="Profile" class="w-full h-full object-cover"/>
                <span v-else>{{ user.name.charAt(0) }}</span>
              </div>
              {{ user.name }}
            </button>
            <div v-if="profileOpenMobile" class="space-y-1 pl-4 border-l-2 border-gray-200">
              <Link href="/profile" class="block px-4 py-2 text-sm hover:bg-gray-100 rounded-lg">Profile Settings</Link>
              <Link href="/logout" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">Log Out</Link>
            </div>
          </template>
          <template v-else>
            <Link :href="login()" class="block w-full px-4 py-2 text-center rounded-lg border border-[#1b5e20] text-sm font-semibold text-[#1b5e20] hover:bg-[#1b5e20] hover:text-white transition-colors">
              Log in
            </Link>
            <Link v-if="canRegister" :href="register()" class="block w-full px-4 py-2 text-center rounded-lg bg-[#ffc107] text-[#1b5e20] text-sm font-semibold hover:bg-[#ffb300] transition-colors">
              Register
            </Link>
          </template>
        </div>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { login, register } from '@/routes' // ✅ Make sure index.ts exports login/register

const isOpen = ref(false)
const activeLink = ref('/home')
const profileOpen = ref(false)
const profileOpenMobile = ref(false)

const navLinks = [
  { label: 'Home', path: '/homes' },
  { label: 'Members', path: '/sb' },
  { label: 'Ordinances', path: '/ordinances' },
  { label: 'Resolutions', path: '/resolutions' },
  { label: 'Sessions', path: '/sessions' },
]

function setActive(path) {
  activeLink.value = path
  isOpen.value = false
}

onMounted(() => {
  activeLink.value = window.location.pathname === '/' ? '/home' : window.location.pathname
})

const pageProps = usePage().props
const user = pageProps.auth?.user ?? null
const canRegister = pageProps.canRegister ?? false

watch(
  () => user,
  (newUser) => {
    if (!newUser) {
      profileOpen.value = false
      profileOpenMobile.value = false
    }
  }
)
</script>
