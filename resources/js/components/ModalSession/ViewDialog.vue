<template>
    <transition name="modal-fade">
      <div v-if="isOpen && session" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="relative max-h-[90vh] w-full max-w-2xl scale-100 transform overflow-y-auto rounded-xl border border-slate-100 bg-white shadow-2xl transition-all duration-300">
          
          <div class="sticky top-0 z-10 flex items-start justify-between border-b bg-white/95 px-8 py-6 backdrop-blur-md">
            <div>
              <div class="flex items-center gap-2 mb-1">
                 <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">
                  Session Details
                </span>
                <span :class="session.session_type === 'Regular' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700'" 
                      class="rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                  {{ session.session_type }}
                </span>
              </div>
              <h2 class="text-2xl font-extrabold text-slate-900">
                #{{ session.session_number }}: {{ session.session_title }}
              </h2>
            </div>
            <button @click="$emit('close')" class="ml-4 rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
              <X class="h-6 w-6" />
            </button>
          </div>
  
          <div class="p-8 space-y-8">
            <div class="flex items-center gap-6 rounded-xl border border-slate-100 bg-slate-50/50 p-4">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm">
                  <Calendar class="h-5 w-5 text-emerald-500" />
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-tight text-slate-400">Date of Session</p>
                  <p class="text-sm font-semibold text-slate-700">{{ formattedDate }}</p>
                </div>
              </div>
              <div class="h-8 w-px bg-slate-200"></div>
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm">
                  <FileText class="h-5 w-5 text-emerald-500" />
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-tight text-slate-400">ID Reference</p>
                  <p class="text-sm font-semibold text-slate-700">SES-{{ session.id }}</p>
                </div>
              </div>
            </div>
  
            <div>
              <h3 class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Legislative Summary</h3>
              <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
                <p class="whitespace-pre-wrap text-sm leading-relaxed text-slate-600">
                  {{ session.summary }}
                </p>
              </div>
            </div>
  
            <div v-if="session.images && session.images.length > 0">
              <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-slate-400">Session Gallery</h3>
              <div class="grid grid-cols-2 gap-4">
                <div v-for="(img, idx) in session.images" :key="idx" class="group relative aspect-video overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                  <img 
                    :src="'/storage/' + img.file_path" 
                    :alt="img.alt" 
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                  />
                  <div v-if="img.alt" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-slate-900/80 to-transparent p-3 opacity-0 transition-opacity group-hover:opacity-100">
                    <p class="text-[10px] font-medium text-white">{{ img.alt }}</p>
                  </div>
                </div>
              </div>
            </div>
  
            <div v-else class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 py-10 text-slate-400">
              <ImageIcon class="mb-2 h-8 w-8 opacity-20" />
              <p class="text-xs italic">No images attached to this session.</p>
            </div>
          </div>
  
          <div class="mt-4 flex justify-end gap-3 border-t border-slate-200 bg-slate-50/50 px-8 py-6">
            <button @click="$emit('close')" class="rounded-xl bg-slate-900 px-6 py-2.5 font-semibold text-white shadow-md hover:bg-slate-800 transition-all">
              Close View
            </button>
          </div>
        </div>
      </div>
    </transition>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue';
  import { X, Calendar, FileText, Image as ImageIcon } from 'lucide-vue-next';
  
  const props = defineProps<{
    isOpen: boolean;
    session: any | null;
  }>();
  
  defineEmits(['close']);
  
  // Exact format requested: December 24, 2025
  const formattedDate = computed(() => {
    if (!props.session?.date_of_session) return '';
    
    return new Date(props.session.date_of_session).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  });
  </script>
  
  <style scoped>
  .modal-fade-enter-active,
  .modal-fade-leave-active {
    transition: opacity 0.3s ease;
  }
  
  .modal-fade-enter-from,
  .modal-fade-leave-to {
    opacity: 0;
  }
  </style>