<script setup lang="ts">
    import { ref, onMounted, onUnmounted, h, computed } from 'vue'
    import { Bell, CheckCheck, Trash2, Calendar, BellOff } from 'lucide-vue-next'
    import Pusher from 'pusher-js'
    
    const props = defineProps<{ user: any }>()
    
    const isOpen = ref(false)
    const notifications = ref<any[]>([])
    const unreadCount = ref(0)
    const dropdownRef = ref<HTMLElement | null>(null)
    const triggerRect = ref<DOMRect | null>(null)
    
    /* ---------------- POSITIONING (Responsive) ---------------- */
    const updatePosition = () => {
      const btn = dropdownRef.value?.querySelector('button')
      if (btn) triggerRect.value = btn.getBoundingClientRect()
    }
    
    const toggleDropdown = () => {
      updatePosition()
      isOpen.value = !isOpen.value
    }
    
    const fixedStyles = computed(() => {
      if (!triggerRect.value) return {}
    
      const isMobile = window.innerWidth < 768
    
      return {
        position: 'fixed',
        top: isMobile ? '50%' : `${triggerRect.value.top - 30}px`,
        left: isMobile ? '50%' : `${triggerRect.value.right + 25}px`,
        transform: isMobile ? 'translate(-50%, -50%)' : 'none',
        width: isMobile ? '92vw' : '450px',
        maxWidth: isMobile ? 'none' : '600px',
        maxHeight: '100vh',
        zIndex: 9999999,
        pointerEvents: 'auto' as const,
      }
    })
    
    /* ---------------- HELPERS ---------------- */
    const getDocType = (n: any) => {
      if (n.type?.includes('Ordinance')) return 'Ordinance'
      if (n.type?.includes('Resolution')) return 'Resolution'
      if (n.type?.includes('Session')) return 'SB Session'
      return 'System'
    }
    
    const getIconStyles = (n: any) => {
      const type = getDocType(n)
      return {
        Ordinance: 'bg-blue-50 text-blue-600',
        Resolution: 'bg-emerald-50 text-emerald-600',
        'SB Session': 'bg-purple-50 text-purple-600',
        System: 'bg-slate-50 text-slate-500',
      }[type] || 'bg-slate-50 text-slate-500'
    }
    
    const getIconComponent = (n: any) => {
      const type = getDocType(n)
      const paths: Record<string, string> = {
        Ordinance: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        Resolution: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'SB Session': 'M17 20h5v-2a3 3 0 00-5.356-1.857',
      }
      return h('svg',
        { fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor', 'stroke-width': 2, class: 'w-6 h-6' },
        [h('path', { strokeLinecap: 'round', strokeLinejoin: 'round', d: paths[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' })]
      )
    }
    
    const timeAgo = (d: string) => {
      if (!d) return '...'
      const s = Math.floor((Date.now() - new Date(d).getTime()) / 1000)
      if (s < 60) return 'Just now'
      const m = Math.floor(s / 60)
      if (m < 60) return `${m}m ago`
      const h = Math.floor(m / 60)
      if (h < 24) return `${h}h ago`
      return `${Math.floor(h / 24)}d ago`
    }
    
    /* ---------------- API ---------------- */
    const headers = () => ({
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
    })
    
    const fetchNotifications = async () => {
      try {
        const res = await fetch('/notifications', { headers: headers() })
        const data = await res.json()
        notifications.value = data.notifications
        unreadCount.value = notifications.value.filter(n => !n.is_read).length
      } catch (e) {
        console.error("Failed to fetch notifications", e)
      }
    }
    
    const markAllRead = async () => {
      if (!unreadCount.value) return
      await fetch('/notifications/mark-as-read', { method: 'POST', headers: headers() })
      notifications.value.forEach(n => n.is_read = true)
      unreadCount.value = 0
    }
    
    const deleteNotif = async (id: number) => {
      await fetch(`/notifications/${id}`, { method: 'DELETE', headers: headers() })
      notifications.value = notifications.value.filter(n => n.id !== id)
      unreadCount.value = notifications.value.filter(n => !n.is_read).length
    }
    
    /* ---------------- REALTIME ---------------- */
    let pusher: Pusher
    let channel: any
    
    onMounted(() => {
      fetchNotifications()
      window.addEventListener('resize', updatePosition)
      
      // FIXED: Logic to only close if clicking outside the dropdown AND the teleported panel
      window.addEventListener('click', e => {
        const target = e.target as HTMLElement;
        const isInsideTrigger = dropdownRef.value?.contains(target);
        const isInsidePanel = target.closest('.fixed-notif-panel');
        
        if (!isInsideTrigger && !isInsidePanel) {
          isOpen.value = false
        }
      })
    
      if (!props.user?.id) return
      pusher = new Pusher('35f7bda4ed8796a8e071', { cluster: 'ap1', forceTLS: true })
      channel = pusher.subscribe(`user.${props.user.id}`)
      channel.bind('ordinance.download.request.submitted', fetchNotifications)
      channel.bind('resolution.download.request.submitted', fetchNotifications)
    })
    
    onUnmounted(() => {
      window.removeEventListener('resize', updatePosition)
      if (channel) pusher.unsubscribe(`user.${props.user.id}`)
      if (pusher) pusher.disconnect()
    })
    </script>
    
    <template>
      <div class="relative" ref="dropdownRef">
        <button 
          @click="toggleDropdown"
          class="group relative flex h-11 w-11 items-center justify-center rounded-xl bg-white text-slate-500 shadow-sm ring-1 ring-slate-200 transition-all duration-300 hover:shadow-md"
          :class="{ 'ring-2 ring-emerald-500 bg-emerald-50/30': isOpen }"
        >
          <Bell class="h-5 w-5 transition-transform duration-500 group-hover:rotate-[15deg]" :class="{'text-emerald-600': isOpen}" />
          <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-lg bg-red-600 text-[10px] font-bold text-white ring-4 ring-white shadow-sm animate-bounce-short">
            {{ unreadCount }}
          </span>
        </button>
    
        <Teleport to="body">
          <transition name="pop-right">
            <div v-if="isOpen" 
              class="fixed-notif-panel overflow-hidden rounded-3xl border border-slate-200/60 bg-white/95 backdrop-blur-xl shadow-[0_20px_70px_-10px_rgba(0,0,0,0.15)] flex flex-col"
              :style="fixedStyles">
    
              <div class="flex items-center justify-between bg-gradient-to-r from-slate-50 to-white px-8 py-6 border-b border-slate-100 shrink-0">
                <div>
                  <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Information Center</p>
                  <div class="flex items-center gap-2">
                    <span class="text-lg font-bold text-slate-800">System Notifications</span>
                    <span v-if="unreadCount > 0" class="bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter">
                      {{ unreadCount }} New
                    </span>
                  </div>
                </div>
    
                <button @click.stop="markAllRead" class="flex items-center gap-2 px-4 py-2 rounded-xl text-[11px] font-bold text-emerald-600 hover:bg-emerald-50 transition-colors">
                  <CheckCheck class="h-4 w-4" />
                  Mark all as read
                </button>
              </div>
    
              <div class="overflow-y-auto custom-scrollbar bg-white flex-1">
                <div v-if="notifications.length > 0" class="divide-y divide-slate-50 px-4">
                  <div v-for="n in notifications" :key="n.id"
                    class="group relative flex gap-6 p-6 transition-all duration-300 hover:bg-slate-50/70"
                  >
                    <div :class="['flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl shadow-sm ring-1 ring-slate-200/60 transition-all group-hover:scale-110', getIconStyles(n)]">
                      <component :is="getIconComponent(n)" class="h-6 w-6" />
                    </div>
    
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between mb-2">
                        <span :class="['text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded', n.is_read ? 'text-slate-400 bg-slate-100' : 'text-emerald-600 bg-emerald-50']">
                          {{ getDocType(n) }}
                        </span>
                        <div class="flex items-center gap-1.5 text-slate-400 font-medium text-[11px] group-hover:opacity-0 transition-opacity">
                          <Calendar class="h-3 w-3" />
                          {{ timeAgo(n.created_at) }}
                        </div>
                      </div>
                      
                      <h4 v-html="n.message" :class="['text-[15px] leading-relaxed transition-colors', n.is_read ? 'text-slate-500 font-medium' : 'text-slate-900 font-bold group-hover:text-emerald-700']">
                      </h4>
                    </div>
    
                    <div class="absolute right-0 top-0 bottom-0 flex items-center pr-6 pl-12 opacity-0 group-hover:opacity-100 transition-all duration-300 bg-gradient-to-l from-white via-white/80 to-transparent pointer-events-none group-hover:pointer-events-auto">
                      <button 
                        @click.stop="deleteNotif(n.id)" 
                        class="flex items-center gap-2 px-3 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors shadow-sm"
                      >
                        <Trash2 class="h-4 w-4" />
                      </button>
                    </div>
    
                    <div v-if="!n.is_read" class="pt-2 group-hover:opacity-0 transition-opacity">
                      <div class="h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.45)] animate-pulse"></div>
                    </div>
                  </div>
                </div>
    
                <div v-else class="flex flex-col items-center justify-center py-32 text-center px-10">
                  <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                    <BellOff class="h-10 w-10 text-slate-200" />
                  </div>
                  <h3 class="text-slate-400 font-black uppercase tracking-[0.2em] text-xs">All caught up</h3>
                </div>
              </div>
            </div>
          </transition>
        </Teleport>
      </div>
    </template>
    
    <style scoped>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    .pop-right-enter-active { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .pop-right-leave-active { transition: all 0.3s cubic-bezier(0.7, 0, 0.84, 0); }
    
    .pop-right-enter-from { 
      opacity: 0; 
      transform: translate(var(--tw-translate-x), var(--tw-translate-y)) scale(0.95) !important; 
    }
    .pop-right-leave-to { 
      opacity: 0; 
      transform: translate(var(--tw-translate-x), var(--tw-translate-y)) scale(0.98) !important; 
    }
    
    @keyframes bounce-short { 
        0%, 100% { transform: translateY(0); } 
        50% { transform: translateY(-4px); } 
    }
    .animate-bounce-short { animation: bounce-short 2s infinite ease-in-out; }
    </style>