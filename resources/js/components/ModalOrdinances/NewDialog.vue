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
                        {{ ordinance ? 'Edit Ordinance' : 'New Ordinance' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-600">
                        {{
                            ordinance
                                ? 'Update ordinance details and files.'
                                : 'Enter details for a new municipal ordinance.'
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

            <!-- General/top-level error banner (e.g. server errors) -->
            <div
                v-if="generalError"
                class="mb-4 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ generalError }}</span>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-5">
                <!-- Ordinance Number -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-900"
                        >Ordinance Number
                        <span class="text-red-500">*</span></label
                    >
                    <input
                        v-model="form.ordinance_number"
                        type="text"
                        required
                        class="w-full rounded-xl border px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        :class="errors.ordinance_number ? 'border-red-400' : 'border-slate-300'"
                    />
                    <p v-if="errors.ordinance_number" class="mt-1 text-xs text-red-600">
                        {{ errors.ordinance_number }}
                    </p>
                </div>

                <!-- Title -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-900"
                        >Title <span class="text-red-500">*</span></label
                    >
                    <input
                        v-model="form.title_ordinances"
                        type="text"
                        required
                        class="w-full rounded-xl border px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        :class="errors.title_ordinances ? 'border-red-400' : 'border-slate-300'"
                    />
                    <p v-if="errors.title_ordinances" class="mt-1 text-xs text-red-600">
                        {{ errors.title_ordinances }}
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-slate-900"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description_ordinances"
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
                            v-model="form.date_approved_ordinances"
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
                            v-model="form.author_ordinances"
                            type="text"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                        />
                    </div>
                </div>

                <hr class="border-slate-200" />

                <!-- File Upload (PDF or Image) -->
                <div class="space-y-6">
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-slate-900"
                            >Upload Document (PDF or Image)</label
                        >
                        <div
                            @dragover.prevent="isPdfDragging = true"
                            @dragleave.prevent="isPdfDragging = false"
                            @drop.prevent="handleFileDrop($event, 'file')"
                            class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
                            :class="{
                                'border-emerald-500 bg-emerald-50':
                                    isPdfDragging,
                                'border-red-400': (errors.file_path_ordinances || fileNameError) && !isPdfDragging,
                                'border-slate-300 hover:border-emerald-400':
                                    !isPdfDragging && !errors.file_path_ordinances && !fileNameError,
                            }"
                        >
                            <input
                                id="file-upload"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/jpeg,image/png,image/webp"
                                @change="handleFileChange($event, 'file')"
                                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                            />
                            <div
                                class="flex flex-col items-center justify-center text-center"
                            >
                                <UploadCloud
                                    :class="{
                                        'text-emerald-600': isPdfDragging,
                                        'text-red-400': fileNameError && !isPdfDragging,
                                        'text-slate-400': !isPdfDragging && !fileNameError,
                                    }"
                                    class="mb-2 h-8 w-8"
                                />
                                <p class="text-sm font-medium text-slate-700">
                                    <label
                                        for="file-upload"
                                        class="cursor-pointer font-bold text-emerald-600 hover:underline"
                                        >Click to browse</label
                                    >
                                    or drag and drop your
                                    <strong>PDF or image file</strong> here.
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Accepted formats: .PDF, .JPG, .JPEG, .PNG, .WEBP — letters, numbers, spaces, hyphens and underscores only in the filename.
                                </p>
                            </div>
                        </div>

                        <!-- Selected file preview -->
                        <div
                            v-if="form.file_path_ordinances || oldFile"
                            class="mt-3 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <img
                                v-if="selectedFileIsImage && previewUrl"
                                :src="previewUrl"
                                alt="Preview"
                                class="h-12 w-12 rounded-lg border border-slate-200 object-cover"
                            />
                            <ImageIcon
                                v-else-if="selectedFileIsImage"
                                class="h-4 w-4 text-emerald-600"
                            />
                            <FileText v-else class="h-4 w-4 text-emerald-600" />

                            <span>
                                <span class="font-medium">Selected:</span>
                                {{
                                    form.file_path_ordinances
                                        ? form.file_path_ordinances.name
                                        : oldFile?.split('/').pop()
                                }}
                            </span>
                        </div>

                        <!-- Instant client-side filename validation error -->
                        <p v-if="fileNameError" class="mt-2 flex items-start gap-1.5 text-xs font-medium text-red-600">
                            <svg class="mt-0.5 h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ fileNameError }}</span>
                        </p>

                        <!-- Server-side validation error (from Laravel), only shown if no client error -->
                        <p v-else-if="errors.file_path_ordinances" class="mt-2 text-xs text-red-600">
                            {{ errors.file_path_ordinances }}
                        </p>
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
                        :disabled="isLoading || !!fileNameError"
                        class="rounded-xl bg-emerald-600 px-6 py-2.5 font-semibold text-white shadow-md shadow-emerald-500/20 hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50"
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
                            ordinance ? 'Update Ordinance' : 'Save Ordinance'
                        }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { FileText, Image as ImageIcon, UploadCloud } from 'lucide-vue-next';
import { computed, reactive, ref, watch } from 'vue';

interface Ordinance {
    id?: number;
    ordinance_number: string;
    title_ordinances: string;
    description_ordinances?: string;
    date_approved_ordinances?: string;
    author_ordinances?: string;
    file_path_ordinances?: string | null;
}

const props = defineProps<{
    isOpen: boolean;
    ordinance?: Ordinance | null;
}>();
const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submitted'): void;
}>();

const page = usePage();

const isLoading = ref(false);
const isPdfDragging = ref(false);

// Client-side filename validation error — blocks submit while set
const fileNameError = ref<string | null>(null);

// Inertia's field-level validation errors (e.g. from $request->validate())
const errors = computed(() => (page.props.errors || {}) as Record<string, string>);

// Flash-based general error (e.g. from ->with('error', '...') in the controller)
const generalError = computed(() => (page.props.flash as any)?.error || null);

const form = reactive({
    ordinance_number: '',
    title_ordinances: '',
    description_ordinances: '',
    date_approved_ordinances: '',
    author_ordinances: '',
    file_path_ordinances: null as File | null,
});

const oldFile = ref<string | null>(null);

// Object URL for previewing a freshly-selected image before upload
const previewUrl = ref<string | null>(null);

// Build proper URLs
const getFileUrl = (path: string | null) => (path ? `/storage/${path}` : null);

const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
const ALL_EXTENSIONS = ['pdf', ...IMAGE_EXTENSIONS];

const getExtension = (name: string) => {
    const lastDot = name.lastIndexOf('.');
    return lastDot === -1 ? '' : name.slice(lastDot + 1).toLowerCase();
};

// Whether the currently selected/existing file is an image (for preview + icon)
const selectedFileIsImage = computed(() => {
    const name = form.file_path_ordinances?.name || oldFile.value;
    if (!name) return false;
    return IMAGE_EXTENSIONS.includes(getExtension(name));
});

// Only letters, numbers, spaces, hyphens and underscores allowed in the
// filename (before the extension). This mirrors whatever the hosting
// provider's WAF/mod_security rule is rejecting, so we can catch it
// client-side before it ever reaches the server.
const SAFE_FILENAME_PATTERN = /^[a-zA-Z0-9\- _]+$/;

const ACCEPTED_MIME_TYPES = [
    'application/pdf',
    'image/jpeg',
    'image/png',
    'image/webp',
];

/**
 * Validates a filename/file. Returns an error message if invalid, or null if valid.
 * Does NOT modify the file in any way — validation only.
 */
const validateFilename = (file: File): string | null => {
    if (!ACCEPTED_MIME_TYPES.includes(file.type)) {
        return 'Only PDF or image files (JPG, JPEG, PNG, WEBP) are accepted.';
    }

    const originalName = file.name;
    const lastDot = originalName.lastIndexOf('.');

    if (lastDot === -1) {
        return 'The file must have a valid extension (.pdf, .jpg, .jpeg, .png, .webp).';
    }

    const namePart = originalName.slice(0, lastDot);
    const extPart = originalName.slice(lastDot + 1).toLowerCase();

    if (!ALL_EXTENSIONS.includes(extPart)) {
        return 'The file must be a .pdf, .jpg, .jpeg, .png, or .webp file.';
    }

    if (!namePart.trim()) {
        return 'The filename cannot be empty.';
    }

    if (!SAFE_FILENAME_PATTERN.test(namePart)) {
        return `"${originalName}" contains characters that aren't allowed. Please rename the file using only letters, numbers, spaces, hyphens and underscores, then re-upload.`;
    }

    return null;
};

/**
 * Resets the form. Runs ONLY when the modal opens or the target ordinance
 * changes — NOT on every internal state change (previewUrl, oldFile, etc).
 *
 * IMPORTANT: this used to be a `watchEffect`, which auto-tracks every ref it
 * reads — including `previewUrl.value` and `oldFile.value`. Since
 * `processFile()` writes to those same refs when you pick a file, the
 * watchEffect would immediately re-run and wipe out the file you just
 * selected (and any typed input) by resetting everything back to the
 * original `ordinance` prop. Using a scoped `watch` on just `isOpen` /
 * `ordinance` fixes that.
 */
watch(
    () => [props.isOpen, props.ordinance?.id],
    () => {
        if (!props.isOpen) return;

        fileNameError.value = null;

        if (previewUrl.value && previewUrl.value.startsWith('blob:')) {
            URL.revokeObjectURL(previewUrl.value);
        }
        previewUrl.value = null;

        if (props.ordinance) {
            form.ordinance_number = props.ordinance.ordinance_number || '';
            form.title_ordinances = props.ordinance.title_ordinances || '';
            form.description_ordinances = props.ordinance.description_ordinances || '';
            form.date_approved_ordinances = props.ordinance.date_approved_ordinances?.split('T')[0] || '';
            form.author_ordinances = props.ordinance.author_ordinances || '';
            form.file_path_ordinances = null;

            oldFile.value = getFileUrl(props.ordinance.file_path_ordinances);

            if (oldFile.value && IMAGE_EXTENSIONS.includes(getExtension(oldFile.value))) {
                previewUrl.value = oldFile.value;
            }
        } else {
            form.ordinance_number = '';
            form.title_ordinances = '';
            form.description_ordinances = '';
            form.date_approved_ordinances = '';
            form.author_ordinances = '';
            form.file_path_ordinances = null;
            oldFile.value = null;
        }
    },
    { immediate: true }
);

const closeModal = () => emit('close');

/**
 * Runs validation on a freshly-picked file. If invalid, sets the error
 * message and refuses to attach the file to the form (so it can never be
 * submitted). If valid, clears any previous error and attaches it.
 */
const processFile = (file: File) => {
    const error = validateFilename(file);

    if (previewUrl.value && previewUrl.value.startsWith('blob:')) {
        URL.revokeObjectURL(previewUrl.value);
    }
    previewUrl.value = null;

    if (error) {
        fileNameError.value = error;
        form.file_path_ordinances = null; // never let an invalid file into the form
        return;
    }

    fileNameError.value = null;
    form.file_path_ordinances = file;
    oldFile.value = null;

    if (IMAGE_EXTENSIONS.includes(getExtension(file.name))) {
        previewUrl.value = URL.createObjectURL(file);
    }
};

const handleFileDrop = (e: DragEvent, type: 'file') => {
    e.preventDefault();
    isPdfDragging.value = false;

    const file = e.dataTransfer?.files?.[0];
    if (!file) return;

    if (type === 'file') {
        processFile(file);
    }
};

const handleFileChange = (e: Event, type: 'file') => {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;

    if (type === 'file') {
        processFile(file);
    }

    // Reset the native input so picking the exact same (invalid) file again
    // still fires a change event and re-validates.
    input.value = '';
};

const submit = async () => {
    // Hard stop — should already be blocked by the disabled button, but this
    // protects against programmatic form submission too.
    if (fileNameError.value) return;

    isLoading.value = true;
    const data = new FormData();

    Object.entries(form).forEach(([k, v]) => {
        if (v instanceof File) {
            data.append(k, v);
        } else if (v !== null) {
            data.append(k, String(v));
        }
    });

    if (!form.file_path_ordinances && oldFile.value) {
        data.append('keep_file', '1');
    }

    const url = props.ordinance?.id
        ? `/admin-ordinances/${props.ordinance.id}`
        : `/admin-ordinances`;

    // ✅ METHOD SPOOFING FOR UPDATE
    if (props.ordinance?.id) {
        data.append('_method', 'PUT');
    }

    // ✅ ALWAYS POST (NEVER PUT)
    router.visit(url, {
        method: 'post',
        data,
        forceFormData: true,
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            // Only close the modal when the save actually succeeded
            closeModal();
            emit('submitted');
        },
        onError: () => {
            // Validation/server errors — keep the modal open so the user
            // can see the message(s) and fix the problem.
            isLoading.value = false;
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
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