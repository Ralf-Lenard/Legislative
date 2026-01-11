<template>
  <header class="fixed top-0 left-0 w-full z-50 bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16 md:h-20">

        <div class="flex items-center gap-3 cursor-pointer group" @click="goToHome">
          <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-[#1b5e20] to-[#0d3d1a] flex items-center justify-center shadow-md group-hover:scale-105 transition-transform shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3zm-1 15.02L6.98 13.01l1.41-1.41L11 14.2l5.61-5.61 1.41 1.41L11 17.02z"/>
            </svg>
          </div>
          <h1 class="text-base md:text-lg font-bold text-gray-900 tracking-tight hidden xs:block">Sangguniang Bayan</h1>
        </div>

        <div class="flex items-center gap-2 md:gap-6">

          <nav class="hidden lg:flex items-center gap-8">
            <Link
              v-for="link in navLinks"
              :key="link.path"
              :href="link.path"
              @click="setActive(link.path)"
              :class="[
                'text-sm font-semibold transition-all duration-200 pb-1 border-b-2',
                activeLink === link.path
                  ? 'text-[#1b5e20] border-[#ffc107]'
                  : 'text-gray-500 border-transparent hover:text-[#1b5e20] hover:border-[#ffc107]'
              ]"
            >
              {{ link.label }}
            </Link>
          </nav>

          <div class="flex items-center gap-1 md:gap-4">

            <div v-if="user" class="relative">
              <button
                @click="toggleNotifications"
                class="p-2 text-gray-500 hover:bg-gray-100 rounded-full transition-colors relative"
                :class="{'bg-gray-100 text-[#1b5e20]': notificationsOpen}"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 flex h-4 w-4">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 text-[10px] text-white items-center justify-center font-bold">{{ unreadCount }}</span>
                </span>
              </button>

              <transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="translate-y-full lg:translate-y-2 lg:opacity-0"
                enter-to-class="translate-y-0 lg:translate-y-0 lg:opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="translate-y-0 lg:opacity-100"
                leave-to-class="translate-y-full lg:translate-y-2 lg:opacity-0"
              >
                <div
                  v-if="notificationsOpen"
                  class="fixed inset-0 z-[100] bg-white flex flex-col lg:absolute lg:inset-auto lg:right-0 lg:top-full lg:mt-3 lg:w-[400px] lg:h-auto lg:max-h-[600px] lg:rounded-2xl lg:shadow-2xl lg:border lg:border-gray-200 lg:overflow-hidden"
                >
                  <div class="px-5 py-4 border-b flex justify-between items-center bg-white sticky top-0 z-10 lg:bg-gray-50/90 lg:backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <button @click="notificationsOpen = false" class="lg:hidden p-2 -ml-2 text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <h3 class="font-black text-gray-900 text-xl lg:text-base tracking-tight">Notifications</h3>
                    </div>
                    <div class="flex gap-4">
                      <button @click.stop="markAllAsRead" v-if="unreadCount > 0" class="text-sm lg:text-xs font-bold text-[#1b5e20] hover:underline">Mark read</button>
                      <button @click.stop="clearAll" v-if="notifications.length > 0" class="text-sm lg:text-xs font-bold text-red-500 hover:underline">Clear all</button>
                    </div>
                  </div>

                  <div class="flex-1 overflow-y-auto bg-gray-50/20">
                    <div v-if="notifications.length === 0" class="py-24 flex flex-col items-center justify-center text-center px-10">
                      <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                      </div>
                      <p class="text-gray-900 font-bold">No notifications</p>
                      <p class="text-gray-500 text-sm mt-1">When you have updates, they will appear here.</p>
                    </div>
                    
                    <div
                      v-for="notif in notifications"
                      :key="notif.id"
                      :class="['group relative px-5 py-5 border-b last:border-0 hover:bg-white flex gap-4 transition-all duration-300 cursor-default', !notif.is_read ? 'bg-blue-50/40 border-l-4 border-l-[#1b5e20]' : 'bg-white border-l-4 border-l-transparent']"
                    >
                      <div v-if="!notif.is_read" class="mt-1.5 w-2.5 h-2.5 rounded-full bg-[#1b5e20] shrink-0 shadow-[0_0_8px_rgba(27,94,32,0.4)]"></div>
                      
                      <div class="flex-1 min-w-0">
                        <p class="text-gray-800 text-sm md:text-[15px] leading-relaxed mb-2" :class="{'font-bold': !notif.is_read}">
                          {{ notif.message ?? notif.data?.message }}
                        </p>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">
                                {{ timeAgo(notif.created_at) }}
                            </span>
                        </div>
                      </div>

                      <button @click.stop="deleteNotification(notif.id)" class="text-gray-300 hover:text-red-500 p-1 self-start transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 lg:h-4 lg:w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                      </button>
                    </div>
                  </div>
                </div>
              </transition>
            </div>

            <template v-if="user">
              <div class="relative hidden md:block">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 px-4 py-2 bg-[#1b5e20] text-white rounded-xl hover:shadow-lg transition-all font-bold">
                  <div class="w-7 h-7 rounded-lg bg-[#ffc107] text-[#1b5e20] flex items-center justify-center border-2 border-white shrink-0 overflow-hidden text-xs">
                    <img v-if="profilePhotoUrl" :src="profilePhotoUrl" class="w-full h-full object-cover" @error="handleImageError" />
                    <span v-else>{{ user.name.charAt(0) }}</span>
                  </div>
                  <span class="truncate max-w-[100px]">{{ user.name }}</span>
                </button>
                <transition enter-active-class="transition duration-100" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100">
                  <div v-if="profileOpen" class="absolute right-0 top-full mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden">
                    <div class="p-4 border-b bg-gray-50/50">
                      <p class="text-sm font-bold truncate">{{ user.name }}</p>
                      <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                    </div>
                    <div class="p-2">
                      <Link href="/user/profile" @click="profileOpen = false" class="block px-3 py-2 text-sm hover:bg-yellow-50 rounded-lg font-medium">Profile Settings</Link>
                      <Link href="/logout" method="post" as="button" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium">Log Out</Link>
                    </div>
                  </div>
                </transition>
              </div>
            </template>

            <template v-else>
              <div class="hidden md:flex items-center gap-2">
                <Link :href="login()" class="px-3 py-2 text-sm font-bold text-gray-600">Log in</Link>
                <Link v-if="canRegister" :href="register()" class="px-5 py-2.5 text-sm font-bold bg-[#ffc107] text-[#1b5e20] rounded-xl shadow-sm">Register</Link>
              </div>
            </template>

            <button class="lg:hidden p-2.5 text-gray-600 hover:bg-gray-100 rounded-xl transition-all" @click="toggleMenu">
              <svg v-if="!isOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
              <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="-translate-y-full opacity-0"
      enter-to-class="translate-y-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="translate-y-0 opacity-100"
      leave-to-class="-translate-y-full opacity-0"
    >
      <div v-if="isOpen" class="lg:hidden bg-white border-b border-gray-100 absolute w-full left-0 z-40 shadow-2xl overflow-y-auto max-h-[calc(100vh-64px)]">
        <div class="px-4 py-6 space-y-1">
          <Link
            v-for="link in navLinks"
            :key="link.path"
            :href="link.path"
            @click="isOpen = false; setActive(link.path)"
            :class="[
              'block px-5 py-4 rounded-2xl text-lg font-black transition-all',
              activeLink === link.path ? 'bg-yellow-50 text-[#1b5e20]' : 'text-gray-600 hover:bg-gray-50'
            ]"
          >
            {{ link.label }}
          </Link>

          <div class="pt-6 mt-4 border-t border-gray-100">
            <template v-if="user">
                <div class="flex items-center gap-4 px-5 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-[#ffc107] text-[#1b5e20] flex items-center justify-center font-black text-xl shadow-inner">{{ user.name.charAt(0) }}</div>
                    <div class="min-w-0">
                        <p class="font-black text-gray-900 text-lg leading-none truncate">{{ user.name }}</p>
                        <p class="text-sm text-gray-500 mt-1 truncate">{{ user.email }}</p>
                    </div>
                </div>
                <Link href="/user/profile" @click="isOpen = false" class="block px-5 py-4 text-gray-700 font-bold">⚙️ Profile Settings</Link>
                <Link href="/logout" method="post" as="button" class="w-full text-left px-5 py-4 text-red-600 font-black">🚪 Logout</Link>
            </template>
            <template v-else>
                <div class="grid grid-cols-2 gap-4 p-2">
                    <Link :href="login()" class="py-4 text-center font-black text-gray-600 bg-gray-100 rounded-2xl">Log in</Link>
                    <Link :href="register()" class="py-4 text-center font-black bg-[#ffc107] text-[#1b5e20] rounded-2xl">Register</Link>
                </div>
            </template>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { ref, onMounted, watch, computed, onUnmounted } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { login, register } from '@/routes'
import Pusher from 'pusher-js'

const isOpen = ref(false)
const activeLink = ref('/')
const profileOpen = ref(false)
const notificationsOpen = ref(false)
const notifications = ref([])
const unreadCount = ref(0)

const pageProps = usePage().props
const user = computed(() => pageProps.auth?.user ?? null)
const canRegister = pageProps.canRegister ?? false

const navLinks = [
  { label: 'Home', path: '/' },
  { label: 'Members', path: '/sb' },
  { label: 'Ordinances', path: '/ordinances' },
  { label: 'Resolutions', path: '/resolutions' },
  { label: 'Sessions', path: '/sessions' },
  { label: 'News', path: '/announcement-&-news' },
]

const profilePhotoUrl = computed(() => {
  const photo = user.value?.profile_photo || user.value?.profile_photo_url
  return photo ? `/storage/${photo.replace('/storage/', '')}` : null
})

// Helper to format "2 days ago" style timestamps
const timeAgo = (date) => {
  const seconds = Math.floor((new Date() - new Date(date)) / 1000);
  let interval = Math.floor(seconds / 31536000);
  if (interval > 1) return interval + " years ago";
  interval = Math.floor(seconds / 2592000);
  if (interval > 1) return interval + " months ago";
  interval = Math.floor(seconds / 86400);
  if (interval >= 1) return interval === 1 ? "1 day ago" : interval + " days ago";
  interval = Math.floor(seconds / 3600);
  if (interval >= 1) return interval === 1 ? "1 hour ago" : interval + " hours ago";
  interval = Math.floor(seconds / 60);
  if (interval >= 1) return interval === 1 ? "1 minute ago" : interval + " minutes ago";
  return "Just now";
}

const getHeaders = () => ({
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
})

const goToHome = () => {
    activeLink.value = '/';
    router.visit('/');
}

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) notificationsOpen.value = false;
}

const toggleNotifications = () => {
  notificationsOpen.value = !notificationsOpen.value
  if (notificationsOpen.value) {
    isOpen.value = false 
    if (unreadCount.value > 0) markAllAsRead()
  }
}

// Mobile Scroll Lock Logic
watch(notificationsOpen, (val) => {
    if (window.innerWidth < 1024) {
        document.body.style.overflow = val ? 'hidden' : 'auto';
    }
})

const setActive = (path) => {
    activeLink.value = path;
    isOpen.value = false;
}

const handleImageError = e => (e.target.style.display = 'none')

async function fetchNotifications() {
  try {
    const res = await fetch('/notifications', { headers: getHeaders() })
    const data = await res.json()
    notifications.value = data.notifications
    unreadCount.value = notifications.value.filter(n => !n.is_read).length
  } catch (e) { console.error(e) }
}

async function markAllAsRead() {
  try {
    await fetch('/notifications/mark-as-read', { method: 'POST', headers: getHeaders() })
    notifications.value = notifications.value.map(n => ({ ...n, is_read: new Date() }))
    unreadCount.value = 0
  } catch (e) { console.error(e) }
}

async function clearAll() {
  if (!confirm('Clear all notifications?')) return
  try {
    await fetch('/notifications/clear-all', { method: 'POST', headers: getHeaders() })
    notifications.value = []
    unreadCount.value = 0
  } catch (e) { console.error(e) }
}

async function deleteNotification(id) {
  try {
    const res = await fetch(`/notifications/${id}`, { method: 'DELETE', headers: getHeaders() })
    if (res.ok) {
        notifications.value = notifications.value.filter(n => n.id !== id)
        unreadCount.value = notifications.value.filter(n => !n.is_read).length
    }
  } catch (e) { console.error(e) }
}

let pusher = null; let channel = null
onMounted(() => {
  activeLink.value = window.location.pathname
  if (user.value) {
    fetchNotifications()
    pusher = new Pusher('35f7bda4ed8796a8e071', { cluster: 'ap1', encrypted: true })
    channel = pusher.subscribe(`user.${user.value.id}`)
    channel.bind('ordinance.download.status.updated', () => fetchNotifications())
  }
})

onUnmounted(() => {
  if (channel) pusher.unsubscribe(`user.${user.value.id}`)
  if (pusher) pusher.disconnect()
  document.body.style.overflow = 'auto';
})
</script>