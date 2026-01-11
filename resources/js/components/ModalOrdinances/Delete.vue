<template>
    <div
      v-if="isOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
    >
      <div
        class="w-full max-w-md scale-100 transform rounded-xl border border-slate-100 bg-white p-6 text-center opacity-100 shadow-2xl transition-all duration-300"
      >
        <div class="flex flex-col items-center">
          <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
            <Trash2 class="h-6 w-6 text-red-600" />
          </div>
          <h2 class="mb-2 text-xl font-bold text-slate-900">Confirm Deletion</h2>
          <p class="mb-6 text-sm text-slate-600">
            Are you sure you want to delete ordinance
            "<span class="font-medium text-red-700">{{ ordinance?.title_ordinances }}</span>"?
            This action cannot be undone.
          </p>
        </div>
        <div class="flex justify-center gap-4">
          <button
            @click="closeModal"
            class="rounded-xl bg-slate-100 px-6 py-2 font-medium text-slate-700 transition-colors hover:bg-slate-200"
          >
            Cancel
          </button>
  
          <button
            @click="confirm"
            :disabled="isLoading"
            class="rounded-xl bg-red-600 px-6 py-2 font-semibold text-white shadow-md shadow-red-500/20 transition-colors hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
          >
            <span v-if="isLoading">Deleting...</span>
            <span v-else>Delete</span>
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { Trash2 } from 'lucide-vue-next';
  
  interface Ordinance {
    id: number;
    title_ordinances: string;
  }
  
  const props = defineProps<{
    isOpen: boolean;
    ordinance?: Ordinance | null;
  }>();
  
  const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'deleted'): void;
  }>();
  
  const isLoading = ref(false);
  
  const closeModal = () => emit('close');
  
  const confirm = async () => {
    if (!props.ordinance) return;

    isLoading.value = true;

    const data = new FormData();
    data.append('_method', 'DELETE');

    await router.visit(`/ordinances/${props.ordinance.id}`, {
        method: 'post',          // ✅ ALWAYS POST
        data,
        forceFormData: true,
        preserveState: false,    // ✅ FORCE RELOAD
        onFinish: () => {
            isLoading.value = false;
            closeModal();
        },
    });
};

  </script>
  