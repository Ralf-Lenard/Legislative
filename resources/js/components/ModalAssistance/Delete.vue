<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
    >
        <div
            class="w-full max-w-md scale-100 transform rounded-xl border border-slate-100 bg-white p-8 text-center opacity-100 shadow-2xl transition-all duration-300"
        >
            <div class="flex flex-col items-center">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                    <Trash2 class="h-7 w-7 text-red-600" />
                </div>
                
                <h2 class="mb-2 text-2xl font-extrabold text-slate-900">Confirm Deletion</h2>
                
                <p class="mb-6 text-sm text-slate-600 leading-relaxed">
                    Are you sure you want to delete the record for 
                    <span class="block mt-1 font-bold text-red-700 text-base">
                        "{{ assistance?.full_name }}"
                    </span>
                    This action is permanent and cannot be undone.
                </p>
            </div>

            <div class="flex justify-center gap-3">
                <button
                    type="button"
                    @click="closeModal"
                    class="rounded-xl bg-slate-100 px-6 py-2.5 font-medium text-slate-700 transition-all hover:bg-slate-200"
                >
                    Cancel
                </button>

                <button
                    @click="confirm"
                    :disabled="isLoading"
                    class="flex items-center gap-2 rounded-xl bg-red-600 px-6 py-2.5 font-semibold text-white shadow-md shadow-red-500/20 transition-all hover:bg-red-700 disabled:opacity-50"
                >
                    <span v-if="isLoading" class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Deleting...
                    </span>
                    <span v-else>Delete Record</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { Trash2 } from 'lucide-vue-next';

interface Assistance {
    id: number;
    full_name: string;
}

const props = defineProps<{
    isOpen: boolean;
    assistance?: Assistance | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const isLoading = ref(false);

const closeModal = () => emit('close');

const confirm = () => {
    if (!props.assistance) return;

    isLoading.value = true;

    // Adjust the URL to match your route: /admin-assistance/{id}
    router.delete(`/admin-assistance/${props.assistance.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>