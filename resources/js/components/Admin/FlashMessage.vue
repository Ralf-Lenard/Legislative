<script setup lang="ts">
  import { ref, watch, onMounted, onUnmounted } from 'vue'
  import { usePage } from '@inertiajs/vue3'
  import { CheckCircle2, AlertCircle, X, Info } from 'lucide-vue-next'
  
  const page = usePage()
  const message = ref<string | null>(null)
  const type = ref<'success' | 'error' | 'info'>('success')
  const progress = ref(100)
  let timer: number | null = null
  let interval: number | null = null
  
  const close = () => {
    message.value = null
    if (timer) clearTimeout(timer)
    if (interval) clearInterval(interval)
  }
  
  const startTimeout = () => {
    if (timer) clearTimeout(timer)
    if (interval) clearInterval(interval)
    
    progress.value = 100
    const duration = 5000 // 5 seconds
    
    // Decrease progress bar
    interval = window.setInterval(() => {
      progress.value -= 100 / (duration / 100)
    }, 100)
  
    timer = window.setTimeout(() => {
      close()
    }, duration)
  }
  
  onMounted(() => {
    const flash = page.props.flash as { success?: string; error?: string }
    if (flash?.success || flash?.error) {
      message.value = flash.success || flash.error || null
      type.value = flash.success ? 'success' : 'error'
      startTimeout()
    }
  })
  
  watch(() => page.props.flash, (newVal: any) => {
    if (newVal?.success || newVal?.error) {
      message.value = newVal.success || newVal.error
      type.value = newVal.success ? 'success' : 'error'
      startTimeout()
    }
  }, { deep: true })
  
  onUnmounted(() => close())
  </script>
  
  <template>
    <transition name="toast">
      <div
        v-if="message"
        class="fixed top-6 right-6 z-[100] flex min-w-[320px] max-w-md items-center gap-4 overflow-hidden rounded-xl border border-white/20 bg-white/90 p-4 shadow-2xl backdrop-blur-md"
      >
        <div :class="[
          'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg shadow-sm',
          type === 'success' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'
        ]">
          <CheckCircle2 v-if="type === 'success'" :size="20" />
          <AlertCircle v-else :size="20" />
        </div>
  
        <div class="flex-1">
          <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
            {{ type === 'success' ? 'Success' : 'System Alert' }}
          </p>
          <p class="text-[14px] font-semibold text-slate-800 leading-tight">
            {{ message }}
          </p>
        </div>
  
        <button @click="close" class="rounded-md p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600">
          <X :size="18" />
        </button>
  
        <div class="absolute bottom-0 left-0 h-[3px] w-full bg-slate-100">
          <div 
            :class="['h-full transition-all duration-100 ease-linear', type === 'success' ? 'bg-emerald-500' : 'bg-red-500']"
            :style="{ width: `${progress}%` }"
          ></div>
        </div>
      </div>
    </transition>
  </template>
  
  <style scoped>
  .toast-enter-active {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .toast-leave-active {
    transition: all 0.3s ease-in;
  }
  .toast-enter-from {
    transform: translateX(100%) scale(0.9);
    opacity: 0;
  }
  .toast-leave-to {
    transform: translateX(20px);
    opacity: 0;
  }
  </style>