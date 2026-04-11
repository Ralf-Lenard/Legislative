<script setup lang="ts">
import {
    Calendar,
    Mail,
    MessageSquare,
    Trash2,
    UserRound,
    UserX,
    X,
    Tag,
} from 'lucide-vue-next';

interface Feedback {
    id: number;
    user_id: number | null;
    category: 'suggestion' | 'concern' | 'commendation' | 'inquiry' | 'other';
    message: string;
    created_at: string;
    user?: {
        name: string;
        email: string;
    };
}

const props = defineProps<{
    isOpen: boolean;
    feedback: Feedback | null;
}>();

const emit = defineEmits(['close', 'delete']);

const formatDate = (date: string) => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getCategoryClass = (cat: string) => {
    const map: Record<string, string> = {
        suggestion: 'bg-blue-100 text-blue-700 border-blue-200',
        concern: 'bg-red-100 text-red-700 border-red-200',
        commendation: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        inquiry: 'bg-amber-100 text-amber-700 border-amber-200',
        other: 'bg-slate-100 text-slate-700 border-slate-200'
    };
    return map[cat] || map.other;
};
</script>

<template>
    <transition name="modal-fade">
        <div
            v-if="isOpen && feedback"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            @click="emit('close')"
        >
            <div
                class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all"
                @click.stop
            >
                <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full border shadow-sm"
                             :class="feedback.user ? 'bg-white border-indigo-100 text-indigo-600' : 'bg-white border-slate-200 text-slate-400'">
                            <span v-if="feedback.user" class="text-sm font-bold uppercase">{{ feedback.user.name.charAt(0) }}</span>
                            <UserX v-else class="h-5 w-5" />
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">{{ feedback.user?.name || 'Anonymous Citizen' }}</h2>
                            <p class="text-xs text-slate-500">{{ feedback.user?.email || 'No email provided' }}</p>
                        </div>
                    </div>
                    <button @click="emit('close')" class="rounded-full p-1.5 text-slate-400 transition-colors hover:bg-slate-200 hover:text-slate-600">
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div class="max-h-[70vh] overflow-y-auto p-8">
                    <div class="mb-6 flex flex-wrap gap-4">
                        <div class="flex items-center gap-2 rounded-lg border px-3 py-1.5" :class="getCategoryClass(feedback.category)">
                            <Tag class="h-4 w-4" />
                            <span class="text-xs font-bold uppercase tracking-wider">{{ feedback.category }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-slate-600">
                            <Calendar class="h-4 w-4" />
                            <span class="text-xs font-medium">{{ formatDate(feedback.created_at) }}</span>
                        </div>
                    </div>

                    <div class="relative rounded-2xl bg-slate-50 p-6">
                        <MessageSquare class="absolute top-4 right-4 h-12 w-12 text-slate-200 opacity-50" />
                        <h3 class="mb-4 text-xs font-bold tracking-widest text-slate-400 uppercase">Message Body</h3>
                        <p class="relative z-10 whitespace-pre-line text-base leading-relaxed text-slate-700">
                            {{ feedback.message }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50 px-6 py-4">
                    <button
                        @click="emit('delete', feedback.id)"
                        class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-rose-600 transition-all hover:bg-rose-50"
                    >
                        <Trash2 class="h-4 w-4" />
                        Delete Feedback
                    </button>
                    <button
                        @click="emit('close')"
                        class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-slate-200 transition-all hover:bg-slate-800 active:scale-95"
                    >
                        Mark as Read
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
</style>