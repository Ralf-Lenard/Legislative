<script setup lang="ts">
import {
    Calendar,
    ExternalLink,
    FileText,
    Hash,
    ScrollText,
    User,
    X,
} from 'lucide-vue-next';

interface Resolution {
    id: number;
    resolutions_number: string;
    title_resolutions: string;
    description_resolutions: string;
    date_approved_resolutions: string;
    author_resolutions: string;
    file_path_resolutions: string | null;
    image_resolutions: string | null;
}

const props = defineProps<{
    isOpen: boolean;
    resolution: Resolution | null;
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
            v-if="isOpen && resolution"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            @click.self="emit('close')"
        >
            <div
                class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all"
            >
                <div
                    class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-6 py-4"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600"
                        >
                            <ScrollText class="h-5 w-5" />
                        </div>
                        <div>
                            <span
                                class="text-xs font-bold tracking-widest text-emerald-700 uppercase"
                                >Resolution Detail</span
                            >
                            <div
                                class="flex items-center gap-1 text-sm font-medium text-slate-500"
                            >
                                <Hash class="h-3.5 w-3.5" />
                                {{ resolution.resolutions_number }}
                            </div>
                        </div>
                    </div>
                    <button
                        @click="emit('close')"
                        class="rounded-full p-1.5 text-slate-400 transition-colors hover:bg-slate-200 hover:text-slate-600"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div class="max-h-[75vh] overflow-y-auto p-8">
                    <div class="mb-8 border-l-4 border-emerald-500 pl-4">
                        <h2
                            class="text-2xl leading-tight font-extrabold text-slate-900"
                        >
                            {{ resolution.title_resolutions }}
                        </h2>
                    </div>

                    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div
                            class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-4"
                        >
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm"
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
                                            resolution.date_approved_resolutions,
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-4"
                        >
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm"
                            >
                                <User class="h-5 w-5 text-blue-600" />
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-bold tracking-widest text-slate-400 uppercase"
                                >
                                    Author / Proponent
                                </p>
                                <p class="text-sm font-semibold text-slate-700">
                                    {{
                                        resolution.author_resolutions ||
                                        'Not Specified'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3
                            class="mb-3 flex items-center gap-2 text-sm font-bold tracking-wider text-slate-900 uppercase"
                        >
                            Summary of Resolution
                        </h3>
                        <div
                            class="rounded-xl border border-slate-100 bg-slate-50 p-6 leading-relaxed whitespace-pre-line text-slate-600"
                        >
                            {{ resolution.description_resolutions }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div
                            v-if="resolution.file_path_resolutions"
                            class="group flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 p-6 transition-all hover:border-emerald-300 hover:bg-emerald-50/30"
                        >
                            <FileText class="mb-2 h-10 w-10 text-emerald-500" />
                            <p class="text-sm font-bold text-slate-900">
                                Official PDF
                            </p>
                            <a
                                :href="`/storage/${resolution.file_path_resolutions}`"
                                target="_blank"
                                class="mt-4 flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2 text-xs font-bold text-white shadow-md transition-all hover:bg-emerald-700"
                            >
                                <ExternalLink class="h-3.5 w-3.5" /> View
                                Document
                            </a>
                        </div>

                        <div
                            v-if="resolution.image_resolutions"
                            class="group relative h-40 overflow-hidden rounded-2xl border border-slate-200"
                        >
                            <img
                                :src="`/storage/${resolution.image_resolutions}`"
                                class="h-full w-full object-cover"
                            />
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition-opacity group-hover:opacity-100"
                            >
                                <span
                                    class="rounded-full bg-slate-900/60 px-3 py-1.5 text-xs font-bold text-white"
                                    >Reference Image</span
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
                        class="rounded-xl bg-slate-100 px-8 py-2.5 text-sm font-bold text-slate-600 transition-all hover:bg-slate-200"
                    >
                        Close
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
