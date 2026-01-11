<template>
  <div class="relative flex items-center justify-center">
    <button
      @click="toggleNotifications"
      class="relative p-2 rounded-xl transition-all duration-300 focus:outline-none z-[110]"
      :class="isOpen 
        ? 'bg-emerald-100 text-emerald-800 shadow-sm' 
        : 'text-slate-500 hover:bg-slate-100 hover:text-slate-700'"
    >
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
        class="w-6 h-6 transition-transform duration-300" :class="{ 'rotate-12': isOpen }">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
      </svg>

      <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 flex h-4 w-4">
        <span class="absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75 animate-ping"></span>
        <span class="relative inline-flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-2 ring-white">
          {{ unreadCount > 9 ? '9+' : unreadCount }}
        </span>
      </span>
    </button>

    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-y-4 opacity-0 scale-95"
      enter-to-class="translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        class="fixed inset-0 z-[100] flex flex-col bg-white 
               lg:absolute lg:inset-auto lg:top-full lg:left-1/2 lg:-translate-x-1/2 
               lg:mt-4 lg:w-[450px] lg:max-h-[600px] lg:rounded-2xl lg:border-2 
               lg:border-slate-300 lg:shadow-2xl overflow-hidden"
      >
        <div class="hidden lg:block absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white rotate-45 border-l-2 border-t-2 border-slate-300"></div>

        <div class="sticky top-0 z-10 px-6 py-4 flex items-center justify-between bg-white border-b-2 border-slate-100">
          <div>
            <h3 class="text-base font-black text-slate-900 leading-none">Notifications</h3>
            <p class="text-[11px] font-black text-emerald-700 mt-1 uppercase tracking-widest">
              {{ unreadCount }} Unread
            </p>
          </div>

          <div class="flex items-center gap-2">
            <button v-if="unreadCount" @click.stop="markAllAsRead"
              class="px-3 py-1.5 text-[10px] font-black uppercase text-slate-900 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition-colors border border-slate-300">
              Mark all read
            </button>
            <button @click="isOpen = false" class="lg:hidden p-2 text-slate-900">✕</button>
          </div>
        </div>

        <div class="flex-1 overflow-y-auto bg-slate-50">
          <div v-if="!notifications.length" class="flex flex-col items-center justify-center py-24 px-8 text-center">
             <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-400 mb-4 border border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" /></svg>
             </div>
             <p class="font-black text-slate-900">No new notifications</p>
          </div>

          <div
            v-for="notif in notifications"
            :key="notif.id"
            class="group relative px-6 py-5 border-b-2 border-slate-100 transition-all hover:bg-white"
            :class="!notif.is_read ? 'bg-white' : 'bg-slate-50'"
          >
            <div class="flex gap-4">
              <div class="shrink-0">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm border border-black/10" :class="getDocIconBg(notif)">
                  <component :is="getDocIconComponent(notif)" class="w-6 h-6" />
                </div>
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start mb-1.5">
                  <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-800 bg-slate-200 px-1.5 py-0.5 rounded">
                      {{ getDocType(notif) }}
                    </span>
                    <span v-if="hasStatus(notif)" class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase border" :class="getStatusColor(notif, 'badge')">
                      {{ getStatusLabel(notif) }}
                    </span>
                  </div>
                  <span class="text-[10px] font-black text-slate-800">
                    {{ timeAgo(notif.created_at) }}
                  </span>
                </div>

                <p class="text-[13.5px] leading-relaxed text-slate-900" :class="!notif.is_read ? 'font-black' : 'font-bold'">
                  {{ notif.message }}
                </p>

                <div v-if="!notif.is_read" class="flex items-center gap-1.5 mt-2">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                  <span class="text-[11px] font-black text-emerald-800 uppercase">New Update</span>
                </div>
              </div>

              <button @click.stop="deleteNotification(notif.id)"
                class="opacity-0 group-hover:opacity-100 p-1.5 text-slate-900 hover:text-red-600 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div v-if="notifications.length" class="p-3 bg-white border-t-2 border-slate-100">
          <button @click="clearAll" class="w-full py-3 text-[11px] font-black text-red-700 bg-red-50 hover:bg-red-100 rounded-xl transition-colors border border-red-200 uppercase tracking-[0.2em]">
            Clear All Notifications
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, h } from 'vue'
import Pusher from 'pusher-js'

const props = defineProps({ user: Object })
const emit = defineEmits(['toggle'])

const isOpen = ref(false)
const notifications = ref([])
const unreadCount = ref(0)

/* --- Document Type Logic --- */
const getDocType = (n) => {
  const m = (n.message ?? '').toLowerCase()
  if (m.includes('ordinance')) return 'Ordinance'
  if (m.includes('resolution')) return 'Resolution'
  if (m.includes('session')) return 'SB Session'
  return 'System'
}

const getDocIconBg = (n) => ({
  Ordinance: 'bg-blue-600 text-white',
  Resolution: 'bg-emerald-600 text-white',
  'SB Session': 'bg-purple-600 text-white',
}[getDocType(n)] || 'bg-slate-800 text-white')

const getDocIconComponent = (n) => {
  const type = getDocType(n)
  const paths = {
    Ordinance: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    Resolution: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    'SB Session': 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
  }
  return h('svg', { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': 2.5 }, [
    h('path', { strokeLinecap: 'round', strokeLinejoin: 'round', d: paths[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })
  ])
}

/* --- Status Logic --- */
const hasStatus = (n) => {
  const m = n.message.toLowerCase()
  return m.includes('approved') || m.includes('rejected')
}

const getStatusLabel = (n) => {
  if (n.message.toLowerCase().includes('approved')) return 'Approved'
  if (n.message.toLowerCase().includes('rejected')) return 'Rejected'
  return ''
}

const getStatusColor = (notif, part) => {
  const label = getStatusLabel(notif)
  if (label === 'Approved') {
    return 'bg-emerald-50 text-emerald-900 border-emerald-300'
  }
  if (label === 'Rejected') {
    return 'bg-red-50 text-red-900 border-red-300'
  }
  return 'hidden'
}

/* --- Core Helpers --- */
const timeAgo = (d) => {
  const s = Math.floor((Date.now() - new Date(d)) / 1000)
  if (s < 60) return 'Just now'
  const m = Math.floor(s / 60)
  if (m < 60) return `${m}m ago`
  const h = Math.floor(m / 60)
  if (h < 24) return `${h}h ago`
  return `${Math.floor(h / 24)}d ago`
}

const toggleNotifications = () => {
  isOpen.value = !isOpen.value
  emit('toggle', isOpen.value)
  if (isOpen.value && unreadCount.value) markAllAsRead()
}

watch(isOpen, v => {
  if (window.innerWidth < 1024) document.body.style.overflow = v ? 'hidden' : 'auto'
})

const getHeaders = () => ({
  Accept: 'application/json',
  'Content-Type': 'application/json',
  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
})

const fetchNotifications = async () => {
  try {
    const res = await fetch('/notifications', { headers: getHeaders() })
    const data = await res.json()
    notifications.value = data.notifications
    unreadCount.value = notifications.value.filter(n => !n.is_read).length
  } catch (e) { console.error("Fetch error", e) }
}

const markAllAsRead = async () => {
  await fetch('/notifications/mark-as-read', { method: 'POST', headers: getHeaders() })
  notifications.value.forEach(n => n.is_read = true)
  unreadCount.value = 0
}

const clearAll = async () => {
  await fetch('/notifications/clear-all', {
    method: 'POST',
    headers: getHeaders(),
  })

  notifications.value = []
  unreadCount.value = 0
}


const deleteNotification = async (id) => {
  await fetch(`/notifications/${id}`, { method: 'DELETE', headers: getHeaders() })
  notifications.value = notifications.value.filter(n => n.id !== id)
  unreadCount.value = notifications.value.filter(n => !n.is_read).length
}

/* --- Realtime --- */
let pusher, channel
onMounted(() => {
  if (!props.user) return
  fetchNotifications()
  pusher = new Pusher('35f7bda4ed8796a8e071', { cluster: 'ap1' })
  channel = pusher.subscribe(`user.${props.user.id}`)
  channel.bind('ordinance.download.status.updated', fetchNotifications)
  channel.bind('resolution.download.status.updated', fetchNotifications)
})

onUnmounted(() => {
  if (channel) pusher.unsubscribe(`user.${props.user.id}`)
  if (pusher) pusher.disconnect()
})
</script>