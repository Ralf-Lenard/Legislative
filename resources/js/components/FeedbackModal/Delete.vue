<template>
    <transition name="modal-fade">
        <div v-if="isOpen" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
            <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all" @click.stop>
                
                <div class="h-2 bg-red-600"></div>

                <div class="p-8">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100">
                            <AlertTriangle class="h-6 w-6 text-red-600" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Delete Feedback?</h3>
                            <p class="text-sm text-slate-500">This action is permanent.</p>
                        </div>
                    </div>

                    <div v-if="feedback" class="mt-6 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Message Content</p>
                        <div class="mt-2">
                            <p class="text-sm font-semibold text-slate-700 capitalize">{{ feedback.category }}</p>
                            <p class="text-sm text-slate-600 truncate italic">"{{ feedback.message }}"</p>
                        </div>
                    </div>

                    <p class="mt-6 text-sm text-slate-600 leading-relaxed">
                        Are you sure you want to remove this citizen feedback? This record will be erased from the database and cannot be recovered.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button 
                            type="button" 
                            @click="$emit('close')"
                            class="rounded-xl px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            @click="confirmDelete"
                            :disabled="isProcessing"
                            class="flex items-center justify-center gap-2 rounded-xl bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-500/30 hover:bg-red-700 disabled:opacity-50 transition-all"
                        >
                            <Trash2 v-if="!isProcessing" class="h-4 w-4" />
                            <Loader2 v-else class="h-4 w-4 animate-spin" />
                            {{ isProcessing ? 'Deleting...' : 'Confirm Delete' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { AlertTriangle, Trash2, Loader2 } from 'lucide-vue-next'

const props = defineProps<{
    isOpen: boolean
    feedback: any | null
}>()

const emit = defineEmits(['close'])
const isProcessing = ref(false)

const confirmDelete = () => {
    if (!props.feedback?.id) return

    isProcessing.value = true

    // Note: I used the endpoint /admin/feedback/ to match your existing logic
    router.delete(`/admin/feedback/${props.feedback.id}`, {
        preserveScroll: true,
        onFinish: () => {
            isProcessing.value = false
            emit('close')
        },
    })
}
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
</style>