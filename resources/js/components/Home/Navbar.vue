<template>
  <header class="fixed top-0 left-0 w-full z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16 md:h-20">

        <!-- LOGO -->
        <div class="flex items-center gap-3 cursor-pointer group" @click="goToHome">
          <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-[#1b5e20] to-[#0d3d1a] flex items-center justify-center shadow-md group-hover:scale-105 transition-transform shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm-1 15.02L6.98 13.01l1.41-1.41L11 14.2l5.61-5.61 1.41 1.41L11 17.02z"/>
            </svg>
          </div>
          <h1 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Sangguniang Bayan</h1>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-2 md:gap-6">

          <!-- DESKTOP NAV -->
          <nav class="hidden lg:flex items-center gap-8">
            <Link
              v-for="link in navLinks"
              :key="link.path"
              :href="link.path"
              @click="setActive(link.path)"
              :class="[
                activeLink === link.path
                  ? 'text-[#1b5e20] border-[#ffc107]'
                  : 'text-gray-500 border-transparent',
                'text-sm font-semibold transition-all duration-200 pb-1 border-b-2 hover:text-[#1b5e20] hover:border-[#ffc107]'
              ]"
            >
              {{ link.label }}
            </Link>
          </nav>

          <div class="flex items-center gap-1 md:gap-4">

            <!-- NOTIFICATIONS -->
            <NotificationComponent
              v-if="user"
              ref="notifRef"
              :user="user"
              @toggle="handleNotifToggle"
            />

            <!-- DESKTOP USER -->
            <template v-if="user">
              <div class="relative hidden md:block">
                <button
                  @click="profileOpen = !profileOpen"
                  class="flex items-center gap-2 px-4 py-2 bg-[#1b5e20] text-white rounded-xl hover:shadow-lg transition-all font-bold"
                >
                  <div class="w-7 h-7 rounded-lg bg-[#ffc107] text-[#1b5e20] flex items-center justify-center border-2 border-white shrink-0 overflow-hidden text-xs">
                    <img v-if="profilePhotoUrl" :src="profilePhotoUrl" class="w-full h-full object-cover" />
                    <span v-else>{{ user.name.charAt(0) }}</span>
                  </div>
                  <span class="truncate max-w-[100px]">{{ user.name }}</span>
                </button>

                <transition enter-active-class="transition duration-100" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100">
                  <div
                    v-if="profileOpen"
                    class="absolute right-0 top-full mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden"
                  >
                    <div class="p-4 border-b bg-gray-50/50">
                      <p class="text-sm font-bold truncate">{{ user.name }}</p>
                      <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                    </div>
                    <div class="p-2">
                      <Link href="/profile" class="block px-3 py-2 text-sm hover:bg-yellow-50 rounded-lg font-medium">
                        Profile Settings
                      </Link>
                     <Link
                      href="/document-requests"
                      class="block px-3 py-2 text-sm hover:bg-yellow-50 rounded-lg font-medium"
                    >
                      My Requests
                    </Link>
                      <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium"
                      >
                        Log Out
                      </Link>
                    </div>
                  </div>
                </transition>
              </div>
            </template>

            <!-- DESKTOP AUTH -->
            <template v-else>
              <div class="hidden md:flex items-center gap-2">
                <Link :href="login()" class="px-3 py-2 text-sm font-bold text-gray-600">
                  Log in
                </Link>
                <Link
                  v-if="canRegister"
                  :href="register()"
                  class="px-5 py-2.5 text-sm font-bold bg-[#ffc107] text-[#1b5e20] rounded-xl shadow-sm"
                >
                  Register
                </Link>
              </div>
            </template>

            <!-- MOBILE TOGGLE -->
            <button
              class="lg:hidden p-2.5 text-gray-600 hover:bg-gray-100 rounded-xl transition-all"
              @click="toggleMenu"
            >
              <svg v-if="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
              </svg>
              <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

          </div>
        </div>
      </div>
    </div>

    <!-- MOBILE MENU -->
    <transition enter-active-class="transition duration-300 ease-out" enter-from-class="-translate-y-full opacity-0" enter-to-class="translate-y-0 opacity-100">
      <div
        v-if="isOpen"
        class="lg:hidden bg-white border-b border-gray-100 absolute w-full left-0 z-40 shadow-2xl overflow-y-auto max-h-[calc(100vh-64px)]"
      >
        <div class="px-4 py-6 space-y-1">

          <!-- NAV LINKS -->
          <Link
            v-for="link in navLinks"
            :key="link.path"
            :href="link.path"
            @click="isOpen = false; setActive(link.path)"
            :class="[
              activeLink === link.path
                ? 'bg-yellow-50 text-[#1b5e20]'
                : 'text-gray-600',
              'block px-5 py-4 rounded-2xl text-lg font-black'
            ]"
          >
            {{ link.label }}
          </Link>

          <!-- AUTH / USER -->
          <div class="pt-6 mt-4 border-t border-gray-100">

            <!-- MOBILE USER -->
            <template v-if="user">
              <Link href="/profile" @click="isOpen = false" class="block px-5 py-4 text-gray-700 font-bold">
                 Profile Settings
              </Link>
              <Link href="/document-requests" @click="isOpen = false" class="block px-5 py-4 text-gray-700 font-bold">
                  My Requests
              </Link>
              <Link
                href="/logout"
                method="post"
                as="button"
                class="w-full text-left px-5 py-4 text-red-600 font-black"
              >
                 Logout
              </Link>
            </template>

            <!-- MOBILE LOGIN / REGISTER -->
            <template v-else>
              <Link
                :href="login()"
                @click="isOpen = false"
                class="block px-5 py-4 text-gray-700 font-black"
              >
                🔐 Log in
              </Link>
              <Link
                v-if="canRegister"
                :href="register()"
                @click="isOpen = false"
                class="block px-5 py-4 mt-2 bg-[#ffc107] text-[#1b5e20] rounded-2xl text-center font-black shadow-sm"
              >
                ✍️ Register
              </Link>
            </template>

          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { login, register } from '@/routes'
import NotificationComponent from '@/components/Home/NotificationComponent.vue'

const isOpen = ref(false)
const activeLink = ref('/')
const profileOpen = ref(false)
const notifRef = ref(null)

const pageProps = usePage().props
const user = computed(() => pageProps.auth?.user ?? null)
const canRegister = pageProps.canRegister ?? false

const navLinks = [
  { label: 'Home', path: '/' },
  { label: 'Organizational Chart', path: '/organizational-chart' },
  // { label: 'Ordinances', path: '/ordinances' },
  // { label: 'Resolutions', path: '/resolutions' },
  { label: 'Citizen\'s Charter', path: '/citizens-charter' },
  { label: 'Sessions', path: '/legislative-sessions' },
  { label: 'Library', path: '/library' },
]

const profilePhotoUrl = computed(() => {
  const photo = user.value?.profile_photo || user.value?.profile_photo_url
  return photo ? `/storage/${photo.replace('/storage/', '')}` : null
})

const goToHome = () => router.visit('/')

const toggleMenu = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value && notifRef.value) notifRef.value.close()
}

const handleNotifToggle = (notifState) => {
  if (notifState) isOpen.value = false
}

const setActive = (path) => {
  activeLink.value = path
  isOpen.value = false
}

onMounted(() => {
  activeLink.value = window.location.pathname
})
</script>
