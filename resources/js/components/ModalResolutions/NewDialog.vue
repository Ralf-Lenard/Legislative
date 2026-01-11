<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
    >
        <div
            class="relative max-h-[90vh] w-full max-w-xl scale-100 transform overflow-y-auto rounded-xl border border-slate-100 bg-white p-8 opacity-100 shadow-2xl transition-all duration-300"
        >
            <!-- Header -->
            <div class="mb-6 flex items-start justify-between border-b pb-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">
                        {{ resolution ? 'Edit Resolution' : 'New Resolution' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{
                            resolution
                                ? 'Update resolution details and files.'
                                : 'Enter details for a new municipal resolution.'
                        }}
                    </p>
                </div>
                <button
                    @click="closeModal"
                    class="ml-4 rounded-full p-2 text-slate-600 transition-colors hover:bg-slate-100"
                >
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Resolution Number -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-900"
                        >Resolution Number
                        <span class="text-red-500">*</span></label
                    >
                    <input
                        v-model="form.resolutions_number"
                        type="text"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    />
                </div>

                <!-- Title -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-900"
                        >Title <span class="text-red-500">*</span></label
                    >
                    <input
                        v-model="form.title_resolutions"
                        type="text"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    />
                </div>

                <!-- Description -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-900"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description_resolutions"
                        rows="3"
                        class="w-full resize-none rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                    ></textarea>
                </div>

                <!-- Date and Author -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-900"
                            >Date Approved</label
                        >
                        <input
                            v-model="form.date_approved_resolutions"
                            type="date"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        />
                    </div>
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-900"
                            >Author</label
                        >
                        <input
                            v-model="form.author_resolutions"
                            type="text"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        />
                    </div>
                </div>

                <hr class="border-slate-200" />

                <!-- File Uploads -->
                <div class="space-y-6">
                    <!-- PDF -->
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-900"
                            >Upload PDF (Official Document)</label
                        >
                        <div
                            @dragover.prevent="isPdfDragging = true"
                            @dragleave.prevent="isPdfDragging = false"
                            @drop.prevent="handleFileDrop($event, 'pdf')"
                            class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
                            :class="{
                                'border-emerald-500 bg-emerald-50':
                                    isPdfDragging,
                                'border-slate-300 hover:border-emerald-400':
                                    !isPdfDragging,
                            }"
                        >
                            <input
                                id="pdf-upload"
                                type="file"
                                accept="application/pdf"
                                @change="handleFileChange($event, 'pdf')"
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                            />
                            <div
                                class="flex flex-col items-center justify-center text-center"
                            >
                                <UploadCloud
                                    :class="{
                                        'text-emerald-600': isPdfDragging,
                                        'text-slate-400': !isPdfDragging,
                                    }"
                                    class="mb-2 h-8 w-8"
                                />
                                <p class="text-sm font-medium text-slate-700">
                                    <label
                                        for="pdf-upload"
                                        class="cursor-pointer font-bold text-emerald-600 hover:underline"
                                        >Click to browse</label
                                    >
                                    or drag and drop your
                                    <strong>PDF file</strong> here.
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Accepted format: .PDF
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="form.file_path_resolutions || oldPdf"
                            class="mt-3 flex items-center gap-2 text-sm text-slate-700"
                        >
                            <FileText class="h-4 w-4 text-emerald-600" />
                            <span class="font-medium">Selected:</span>
                            {{
                                form.file_path_resolutions
                                    ? form.file_path_resolutions.name
                                    : oldPdf.split('/').pop()
                            }}
                        </div>
                    </div>

                    <!-- Image -->
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-900"
                            >Upload Image (For Preview/Media)</label
                        >
                        <div
                            @dragover.prevent="isImageDragging = true"
                            @dragleave.prevent="isImageDragging = false"
                            @drop.prevent="handleFileDrop($event, 'image')"
                            class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
                            :class="{
                                'border-sky-500 bg-sky-50': isImageDragging,
                                'border-slate-300 hover:border-sky-400':
                                    !isImageDragging,
                            }"
                        >
                            <input
                                id="image-upload"
                                type="file"
                                accept="image/*"
                                @change="handleFileChange($event, 'image')"
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                            />
                            <div
                                class="flex flex-col items-center justify-center text-center"
                            >
                                <Image
                                    :class="{
                                        'text-sky-600': isImageDragging,
                                        'text-slate-400': !isImageDragging,
                                    }"
                                    class="mb-2 h-8 w-8"
                                />
                                <p class="text-sm font-medium text-slate-700">
                                    <label
                                        for="image-upload"
                                        class="cursor-pointer font-bold text-sky-600 hover:underline"
                                        >Click to browse</label
                                    >
                                    or drag and drop your
                                    <strong>Image file</strong> here.
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Accepted formats: JPG, PNG, GIF
                                </p>
                            </div>
                        </div>

                        <!-- Image preview -->
                        <div
                            v-if="form.image_resolutions || oldImage"
                            class="mt-4"
                        >
                            <p class="mb-2 text-xs text-slate-500">
                                Current Image Preview:
                            </p>
                            <img
                              v-if="form.image_resolutions || oldImage"
                              :src="getImagePreview()"
                              class="h-20 w-20 rounded-lg border-2 border-slate-200 object-cover shadow-md"
                            />
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div
                    class="mt-8 flex justify-end gap-3 border-t border-slate-200 pt-6"
                >
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
                            <svg
                                class="h-5 w-5 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Saving...
                        </span>
                        <span v-else>{{
                            resolution ? 'Update Resolution' : 'Save Resolution'
                        }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { FileText, Image, UploadCloud } from 'lucide-vue-next';
import { reactive, ref, watchEffect } from 'vue';

// Safely access URL API for createObjectURL
const browserURL = typeof window !== 'undefined' ? window.URL : null;

interface Resolution {
    id?: number;
    resolutions_number: string;
    title_resolutions: string;
    description_resolutions?: string;
    date_approved_resolutions?: string;
    author_resolutions?: string;
    file_path_resolutions?: string | null;
    image_resolutions?: string | null;
}

const props = defineProps<{
    isOpen: boolean;
    resolution?: Resolution | null;
}>();
const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submitted'): void;
}>();

const isLoading = ref(false);
const isPdfDragging = ref(false);
const isImageDragging = ref(false);

const form = reactive({
    resolutions_number: '',
    title_resolutions: '',
    description_resolutions: '',
    date_approved_resolutions: '',
    author_resolutions: '',
    file_path_resolutions: null as File | null,
    image_resolutions: null as File | null,
});

const oldPdf = ref<string | null>(null);
const oldImage = ref<string | null>(null);

// Build proper URLs
const getImageUrl = (path: string | null) => (path ? `/storage/${path}` : null);
const getPdfUrl = (path: string | null) => (path ? `/storage/${path}` : null);

// Reset form on open
watchEffect(() => {
    if (!props.isOpen) return;

    if (props.resolution) {
        form.resolutions_number = props.resolution.resolutions_number || '';
        form.title_resolutions = props.resolution.title_resolutions || '';
        form.description_resolutions = props.resolution.description_resolutions || '';
        form.date_approved_resolutions = props.resolution.date_approved_resolutions?.split('T')[0] || '';
        form.author_resolutions = props.resolution.author_resolutions || '';
        form.file_path_resolutions = null;
        form.image_resolutions = null;

        oldPdf.value = getPdfUrl(props.resolution.file_path_resolutions);
        oldImage.value = getImageUrl(props.resolution.image_resolutions);
    } else {
        form.resolutions_number = '';
        form.title_resolutions = '';
        form.description_resolutions = '';
        form.date_approved_resolutions = '';
        form.author_resolutions = '';
        form.file_path_resolutions = null;
        form.image_resolutions = null;
        oldPdf.value = null;
        oldImage.value = null;
    }
});

const closeModal = () => emit('close');

const handleFileDrop = (e: DragEvent, type: 'pdf' | 'image') => {
    e.preventDefault();
    const file = e.dataTransfer?.files?.[0];
    if (!file) return;

    if (type === 'pdf') {
        if (file.type !== 'application/pdf') return alert('Please drop a valid PDF file.');
        form.file_path_resolutions = file;
        oldPdf.value = null;
        isPdfDragging.value = false;
    }

    if (type === 'image') {
        if (!file.type.startsWith('image/')) return alert('Please drop a valid image file.');
        form.image_resolutions = file;
        oldImage.value = null;
        isImageDragging.value = false;
    }
};

const handleFileChange = (e: Event, type: 'pdf' | 'image') => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    if (type === 'pdf') {
        form.file_path_resolutions = file;
        oldPdf.value = null;
    }

    if (type === 'image') {
        form.image_resolutions = file;
        oldImage.value = null;
    }
};

const submit = async () => {
    isLoading.value = true;
    const data = new FormData();

    Object.entries(form).forEach(([k, v]) => {
        if (v instanceof File) {
            data.append(k, v);
        } else if (v !== null) {
            data.append(k, String(v));
        }
    });

    if (!form.file_path_resolutions && oldPdf.value) {
        data.append('keep_pdf', '1');
    }

    if (!form.image_resolutions && oldImage.value) {
        data.append('keep_image', '1');
    }

    const url = props.resolution?.id
        ? `/admin-resolutions/${props.resolution.id}` // update
        : `/admin-resolutions`;                      // create

    // ✅ METHOD SPOOFING FOR UPDATE
    if (props.resolution?.id) {
        data.append('_method', 'PUT');
    }

    // ✅ POST request with Inertia, full reload
    await router.visit(url, {
        method: 'post',          // ALWAYS POST
        data,
        forceFormData: true,
        preserveState: false,    // FORCE REFRESH
        onFinish: () => {
            isLoading.value = false;
            closeModal();
        },
    });
};

// Helper for image preview
const getImagePreview = () => {
    if (form.image_resolutions && browserURL) return browserURL.createObjectURL(form.image_resolutions);
    if (oldImage.value) return oldImage.value;
    return '';
};
</script>

<style scoped>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .overflow-y-auto::-webkit-scrollbar {
        display: none;
    }
    
    /* Hide scrollbar for IE, Edge and Firefox */
    .overflow-y-auto {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    </style>