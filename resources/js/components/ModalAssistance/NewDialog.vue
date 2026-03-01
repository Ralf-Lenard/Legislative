<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
    >
        <div
            class="relative max-h-[90vh] w-full max-w-xl scale-100 transform overflow-y-auto rounded-xl border border-slate-100 bg-white p-8 opacity-100 shadow-2xl transition-all duration-300"
        >
            <div class="mb-6 flex items-start justify-between border-b pb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">
                        {{ assistance ? 'Update Assistance Record' : 'New Assistance Request' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ assistance ? 'Update details...' : 'Enter details...' }}
                    </p>
                    <span>{{ assistance ? 'Update Record' : 'Save Record' }}</span>
                </div>
                <button
                    @click="closeModal"
                    class="ml-4 rounded-full p-2 text-slate-600 transition-colors hover:bg-slate-100"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-900">
                        Type of Assistance <span class="text-red-500">*</span>
                    </label>
                    <select
                        v-model="form.type"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <option value="" disabled>Select a type</option>
                        <option value="medical">Medical Assistance</option>
                        <option value="legal">Legal Assistance</option>
                        <option value="scholar">Scholarship Program</option>
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-900">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.full_name"
                        type="text"
                        placeholder="John Doe"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-900">Barangay</label>
                        <input
                            v-model="form.barangay"
                            type="text"
                            placeholder="Optional"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-900">School</label>
                        <input
                            v-model="form.school"
                            type="text"
                            placeholder="If applicable"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        />
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 pt-6">
                    <button
                        type="button"
                        @click="closeModal"
                        class="rounded-xl bg-slate-100 px-6 py-2.5 font-medium text-slate-700 hover:bg-slate-200"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="rounded-xl bg-emerald-600 px-6 py-2.5 font-semibold text-white shadow-md shadow-emerald-500/20 hover:bg-emerald-700 disabled:opacity-50"
                    >
                        <span v-if="isLoading" class="flex items-center gap-2">
                            <svg class="h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                        <span v-else>{{ record ? 'Update Record' : 'Save Record' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue'; // Switched to 'watch'

interface Assistance {
    id?: number;
    type: 'medical' | 'legal' | 'scholar' | '';
    full_name: string;
    barangay?: string;
    school?: string;
}

const props = defineProps<{
    isOpen: boolean;
    assistance?: Assistance | null; 
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const isLoading = ref(false);

const form = useForm({
    type: '' as 'medical' | 'legal' | 'scholar' | '',
    full_name: '',
    barangay: '',
    school: '',
});

// Watch the 'isOpen' prop. When it becomes true, fill the form.
watch(() => props.isOpen, (open) => {
    if (open) {
        if (props.assistance) {
            form.type = props.assistance.type;
            form.full_name = props.assistance.full_name;
            form.barangay = props.assistance.barangay || '';
            form.school = props.assistance.school || '';
        } else {
            form.reset(); // Built-in helper to clear the form
        }
    }
});

const closeModal = () => emit('close');

const submit = () => {
    isLoading.value = true;

    const url = props.assistance?.id  // Changed from props.record
        ? `/admin-assistance/${props.assistance.id}` 
        : `/admin-assistance`;
    
    // Note: Some Laravel configurations prefer POST with _method spoofing for PUT
    // but standard 'put' works if your route is Route::put()
    const method = props.assistance?.id ? 'put' : 'post';

    router.visit(url, {
        method: method,
        data: { ...form },
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

<style scoped>
.overflow-y-auto::-webkit-scrollbar { display: none; }
.overflow-y-auto { -ms-overflow-style: none; scrollbar-width: none; }
</style>