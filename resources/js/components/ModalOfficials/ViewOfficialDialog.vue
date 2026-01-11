<template>
    <transition name="modal-fade">
      <div v-if="isOpen && official" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="relative max-h-[90vh] w-full max-w-2xl scale-100 transform overflow-y-auto rounded-2xl border border-slate-100 bg-white shadow-2xl transition-all">
          
          <div class="sticky top-0 z-10 flex items-start justify-between border-b bg-white/95 px-8 py-6 backdrop-blur-md">
            <div class="flex items-center gap-4">
              <div class="h-16 w-16 overflow-hidden rounded-full border-2 border-emerald-100 shadow-sm">
                <img v-if="official.image" :src="`/storage/${official.image}`" class="h-full w-full object-cover" />
                <div v-else class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
                  <User class="h-8 w-8" />
                </div>
              </div>
              <div>
                <h2 class="text-2xl font-extrabold text-slate-900">{{ official.name }}</h2>
                <p class="text-emerald-600 font-semibold uppercase tracking-wider text-xs">{{ official.position }}</p>
              </div>
            </div>
            <button @click="$emit('close')" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
              <X class="h-6 w-6" />
            </button>
          </div>
  
          <div class="p-8 space-y-8">
            <div v-if="official.main_committee" class="rounded-xl border border-emerald-100 bg-emerald-50/30 p-5">
              <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm">
                  <ShieldCheck class="h-6 w-6 text-emerald-600" />
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-tight text-emerald-600/70">Main Committee Assignment</p>
                  <p class="text-sm font-bold text-slate-800">{{ official.main_committee }}</p>
                </div>
              </div>
            </div>
  
            <div>
              <h3 class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Official Biography</h3>
              <div class="rounded-xl border border-slate-100 bg-slate-50/30 p-5 leading-relaxed text-slate-600 shadow-sm">
                <p v-if="official.bio" class="whitespace-pre-wrap text-sm italic">"{{ official.bio }}"</p>
                <p v-else class="text-sm italic text-slate-400 text-center py-4">No biography provided for this official.</p>
              </div>
            </div>
  
            <div>
              <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-slate-400">Committee Memberships</h3>
              <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div v-for="committee in official.committees" :key="committee.id" 
                  class="flex items-center justify-between rounded-lg border border-slate-100 bg-white p-3 shadow-sm hover:border-emerald-200 transition-colors">
                  <span class="text-sm font-medium text-slate-700">{{ committee.name }}</span>
                  <span class="rounded-md bg-sky-50 px-2 py-0.5 text-[10px] font-bold text-sky-700 uppercase">
                    {{ committee.pivot?.role || 'Member' }}
                  </span>
                </div>
              </div>
            </div>
          </div>
  
          <div class="sticky bottom-0 border-t border-slate-100 bg-slate-50/80 px-8 py-5 backdrop-blur-sm flex justify-end">
            <button @click="$emit('close')" class="rounded-xl bg-slate-900 px-6 py-2.5 font-semibold text-white shadow-md hover:bg-slate-800 transition-all">
              Close Profile
            </button>
          </div>
        </div>
      </div>
    </transition>
  </template>
  
  <script setup lang="ts">
  import { X, User, ShieldCheck } from 'lucide-vue-next';
  
  defineProps<{
    isOpen: boolean;
    official: any | null;
  }>();
  
  defineEmits(['close']);
  </script>
  
  <style scoped>
  .modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
  .modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
  </style>