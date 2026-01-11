<script setup lang="ts">
import {
    Calendar,
    ExternalLink,
    FileText,
    Hash,
    User,
    X,
} from 'lucide-vue-next';

interface Ordinance {
    id: number;
    ordinance_number: string;
    title_ordinances: string;
    description_ordinances: string;
    date_approved_ordinances: string;
    author_ordinances: string;
    file_path_ordinances: string | null;
    image_ordinances: string | null;
}

const props = defineProps<{
    isOpen: boolean;
    ordinance: Ordinance | null;
}>();

const emit = defineEmits(['close']);

const formatDate = (date: string) => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <transition name="modal-fade">
        <div
            v-if="isOpen && ordinance"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        >
            <div
                class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all"
                @click.stop
            >
                <div
                    class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-6 py-4"
                >
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold tracking-wider text-emerald-700 uppercase"
                        >
                            Official Ordinance
                        </span>
                        <span
                            class="flex items-center gap-1 text-sm font-medium text-slate-500"
                        >
                            <Hash class="h-3.5 w-3.5" />
                            {{ ordinance.ordinance_number }}
                        </span>
                    </div>
                    <button
                        @click="emit('close')"
                        class="rounded-full p-1.5 text-slate-400 transition-colors hover:bg-slate-200 hover:text-slate-600"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div class="max-h-[85vh] overflow-y-auto p-8">
                    <div class="mb-8">
                        <h2
                            class="text-2xl leading-tight font-extrabold text-slate-900"
                        >
                            {{ ordinance.title_ordinances }}
                        </h2>
                    </div>

                    <div
                        class="mb-8 grid grid-cols-1 gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-5 sm:grid-cols-2"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm"
                            >
                                <Calendar class="h-5 w-5 text-emerald-600" />
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-bold tracking-widest text-slate-400 uppercase"
                                >
                                    Date Approved
                                </p>
                                <p class="text-sm font-semibold text-slate-700">
                                    {{
                                        formatDate(
                                            ordinance.date_approved_ordinances,
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm"
                            >
                                <User class="h-5 w-5 text-blue-600" />
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-bold tracking-widest text-slate-400 uppercase"
                                >
                                    Proponent / Author
                                </p>
                                <p class="text-sm font-semibold text-slate-700">
                                    {{
                                        ordinance.author_ordinances ||
                                        'Not Specified'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3
                            class="mb-3 text-sm font-bold tracking-wider text-slate-900 uppercase"
                        >
                            Summary & Provisions
                        </h3>
                        <p
                            class="leading-relaxed whitespace-pre-line text-slate-600"
                        >
                            {{ ordinance.description_ordinances }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div
                            v-if="ordinance.file_path_ordinances"
                            class="group relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 p-6 transition-all hover:border-emerald-300 hover:bg-emerald-50/30"
                        >
                            <FileText class="mb-3 h-10 w-10 text-emerald-500" />
                            <p class="mb-1 text-sm font-bold text-slate-900">
                                Official Document
                            </p>
                            <p class="mb-4 text-xs text-slate-500">
                                PDF Format
                            </p>
                            <a
                                :href="`/storage/${ordinance.file_path_ordinances}`"
                                target="_blank"
                                class="flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-md transition-all hover:scale-105 hover:bg-emerald-700"
                            >
                                <ExternalLink class="h-3.5 w-3.5" />
                                View Full PDF
                            </a>
                        </div>

                        <div
                            v-if="ordinance.image_ordinances"
                            class="group relative overflow-hidden rounded-2xl border border-slate-200"
                        >
                            <img
                                :src="`/storage/${ordinance.image_ordinances}`"
                                class="h-full max-h-[160px] w-full object-cover"
                            />
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition-opacity group-hover:opacity-100"
                            >
                                <span class="text-xs font-bold text-white"
                                    >Press Image to Enlarge</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex justify-end border-t border-slate-100 bg-white p-4"
                >
                    <button
                        @click="emit('close')"
                        class="rounded-xl bg-slate-100 px-6 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-slate-200"
                    >
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

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
