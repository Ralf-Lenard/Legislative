<template>
    <transition name="modal-fade">
        <div
            v-if="isOpen && book"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
        >
            <div
                class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-xl border border-slate-100 bg-white p-8 shadow-2xl transition-all duration-300"
            >
                <!-- Header -->
                <div class="mb-6 border-b pb-6 text-center">
                    <!-- Large Cover Image -->

                    <!-- Title & Author -->
                    <h2 class="text-2xl font-extrabold text-slate-900">
                        {{ book.title }}
                    </h2>

                    <p class="mt-2 text-sm text-slate-600">
                        By
                        <span class="font-semibold text-emerald-600">
                            {{ book.author }}
                        </span>
                    </p>

                    <!-- Close Button -->
                    <button
                        @click="$emit('close')"
                        class="absolute top-6 right-6 rounded-full p-2 text-slate-600 transition-colors hover:bg-slate-100"
                    >
                        <X class="h-6 w-6" />
                    </button>
                </div>

                <!-- Body -->
                <div class="space-y-6">
                    <!-- Category -->
                    <div v-if="book.category">
                        <label
                            class="mb-1 block text-xs font-bold tracking-wider text-slate-400 uppercase"
                        >
                            Category
                        </label>
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700"
                        >
                            {{ book.category }}
                        </div>
                    </div>

                    <!-- Published Year -->
                    <div v-if="book.published_year">
                        <label
                            class="mb-1 block text-xs font-bold tracking-wider text-slate-400 uppercase"
                        >
                            Published Year
                        </label>
                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700"
                        >
                            {{ book.published_year }}
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-bold tracking-wider text-slate-400 uppercase"
                        >
                            Summary / Description
                        </label>

                        <div
                            class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-relaxed text-slate-600"
                        >
                            <p
                                v-if="book.description"
                                class="whitespace-pre-wrap"
                            >
                                {{ book.description }}
                            </p>
                            <p v-else class="text-slate-400 italic">
                                No description provided.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="mt-8 flex justify-end border-t border-slate-200 pt-6"
                >
                    <button
                        @click="$emit('close')"
                        class="rounded-xl bg-slate-900 px-6 py-2.5 font-semibold text-white shadow-md hover:bg-slate-800"
                    >
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup lang="ts">
import { Book as BookIcon, X } from 'lucide-vue-next';

defineProps<{
    isOpen: boolean;
    book: any | null;
}>();

defineEmits(['close']);
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

/* Hide scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    display: none;
}
.overflow-y-auto {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
