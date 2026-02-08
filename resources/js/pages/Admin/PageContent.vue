<script setup lang="ts">
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    CheckCircle2,
    Image as ImageIcon,
    Info,
    Layout,
    Plus,
    Save,
    Trash2,
    UploadCloud,
    X,
} from 'lucide-vue-next';
import { ref } from 'vue';

const isLoading = ref(false);

interface PageContent {
    id: number;
    welcome_image: string | null;
    about_us_image: string | null;
    vice_mayor_message: string;
    about_us: string;
    mission: string;
    vision: string;
    gallery_images: string[];
}

const { props } = usePage<{ pageContent: PageContent | null }>();

const resolveImagePath = (path: string | null) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

// Helper to strip the URL and just get the relative path for the DB
const cleanPath = (url: string) => {
    return url
        .replace(`${window.location.origin}/storage/`, '')
        .replace('/storage/', '');
};

const form = ref({
    welcome_image: null as File | null,
    about_us_image: null as File | null,
    gallery_images: [] as File[],
    vice_mayor_message: props.pageContent?.vice_mayor_message || '',
    about_us: props.pageContent?.about_us || '',
    mission: props.pageContent?.mission || '',
    vision: props.pageContent?.vision || '',
    delete_welcome_image: false,
    delete_about_us_image: false,
    delete_gallery_images: [] as string[],
});

const previews = ref({
    welcome: resolveImagePath(props.pageContent?.welcome_image),
    about: resolveImagePath(props.pageContent?.about_us_image),
});

const getFilePreview = (file: File) => URL.createObjectURL(file);

const handleFileChange = (e: Event, type: 'welcome' | 'about') => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        if (type === 'about') {
            form.value.about_us_image = file;
            form.value.delete_about_us_image = false;
        } else {
            form.value.welcome_image = file;
            form.value.delete_welcome_image = false;
        }
        previews.value[type] = URL.createObjectURL(file);
    }
};

const handleGalleryChange = (e: Event) => {
    const files = Array.from((e.target as HTMLInputElement).files || []);
    form.value.gallery_images = [...form.value.gallery_images, ...files];
};

const removeNewGalleryItem = (index: number) => {
    form.value.gallery_images.splice(index, 1);
};

const toggleDeleteExistingGallery = (path: string) => {
    const index = form.value.delete_gallery_images.indexOf(path);
    if (index > -1) {
        form.value.delete_gallery_images.splice(index, 1);
    } else {
        form.value.delete_gallery_images.push(path);
    }
};

const submit = async () => {
    isLoading.value = true;
    const data = new FormData();

    // Text fields
    data.append('vice_mayor_message', form.value.vice_mayor_message || '');
    data.append('about_us', form.value.about_us || '');
    data.append('mission', form.value.mission || '');
    data.append('vision', form.value.vision || '');

    // Single images
    if (form.value.welcome_image)
        data.append('welcome_image', form.value.welcome_image);
    if (form.value.about_us_image)
        data.append('about_us_image', form.value.about_us_image);

    // Delete flags
    data.append(
        'delete_welcome_image',
        form.value.delete_welcome_image ? '1' : '0',
    );
    data.append(
        'delete_about_us_image',
        form.value.delete_about_us_image ? '1' : '0',
    );

    // New Gallery uploads
    form.value.gallery_images.forEach((file) => {
        data.append('gallery_images[]', file);
    });

    // Cleaned paths for deletion
    form.value.delete_gallery_images.forEach((path) => {
        data.append('delete_gallery_images[]', cleanPath(path));
    });

    const url = props.pageContent?.id
        ? `/page-content/${props.pageContent.id}`
        : `/page-content`;

    if (props.pageContent?.id) {
        data.append('_method', 'PUT');
    }

    router.post(url, data, {
        forceFormData: true,
        // These two settings ensure the page "refreshes" its data
        preserveScroll: false,
        preserveState: false,
        onSuccess: () => {
            // Manually reset local state just in case
            form.value.delete_gallery_images = [];
            form.value.gallery_images = [];
            console.log('Content updated and state refreshed.');
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <Head title="CMS Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div
                class="sticky top-0 z-20 border-b border-slate-200 bg-white shadow-md"
            >
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">
                            Page Content Management
                        </h1>
                        <p class="mt-1 text-sm text-slate-600">
                            Update your website's landing page images and text
                            content.
                        </p>
                    </div>
                    <button
                        @click="submit"
                        :disabled="isLoading"
                        class="flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl disabled:opacity-50"
                    >
                        <Save v-if="!isLoading" class="h-5 w-5" />
                        <div
                            v-else
                            class="h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent"
                        ></div>
                        {{
                            props.pageContent
                                ? 'Update Changes'
                                : 'Save Content'
                        }}
                    </button>
                </div>
            </div>

            <div class="w-full p-8">
                <form @submit.prevent="submit" class="space-y-8">
                    <div
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                    >
                        <div
                            class="flex items-center gap-2 border-b border-slate-100 bg-slate-50/50 px-6 py-4"
                        >
                            <ImageIcon class="h-5 w-5 text-emerald-600" />
                            <h2 class="font-bold text-slate-800">
                                Primary Images
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">
                            <div
                                v-for="type in ['welcome', 'about'] as const"
                                :key="type"
                                class="group"
                            >
                                <label
                                    class="mb-3 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >{{ type.replace('_', ' ') }} Image</label
                                >
                                <div
                                    class="relative aspect-video overflow-hidden rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 transition-all group-hover:border-emerald-300"
                                >
                                    <img
                                        v-if="previews[type]"
                                        :src="previews[type]!"
                                        class="h-full w-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-full flex-col items-center justify-center text-slate-400"
                                    >
                                        <UploadCloud class="mb-2 h-8 w-8" />
                                        <span class="text-xs"
                                            >No image selected</span
                                        >
                                    </div>
                                    <label
                                        class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100"
                                    >
                                        <input
                                            type="file"
                                            class="hidden"
                                            @change="
                                                (e) => handleFileChange(e, type)
                                            "
                                            accept="image/*"
                                        />
                                        <span
                                            class="rounded-lg bg-white px-4 py-2 text-sm font-bold text-slate-900 shadow-xl"
                                            >Change Photo</span
                                        >
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                        >
                            <div class="mb-6 flex items-center gap-2">
                                <span class="h-5 w-5 text-sky-600"
                                    ><Info
                                /></span>
                                <h2 class="font-bold text-slate-800">
                                    General Information
                                </h2>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="text-sm font-bold text-slate-700"
                                        >About Us Content</label
                                    >
                                    <textarea
                                        v-model="form.about_us"
                                        rows="6"
                                        placeholder="Enter company description..."
                                        class="mt-2 min-h-[150px] w-full rounded-xl border-slate-200 bg-slate-50/50 p-4 text-slate-900 transition-all focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    ></textarea>
                                </div>
                                <div>
                                    <label
                                        class="text-sm font-bold text-slate-700"
                                        >Vice Mayor's Message</label
                                    >
                                    <textarea
                                        v-model="form.vice_mayor_message"
                                        rows="6"
                                        placeholder="Enter formal message..."
                                        class="mt-2 min-h-[150px] w-full rounded-xl border-slate-200 bg-slate-50/50 p-4 text-slate-900 transition-all focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                        >
                            <div class="mb-6 flex items-center gap-2">
                                <span class="h-5 w-5 text-indigo-600"
                                    ><Layout
                                /></span>
                                <h2 class="font-bold text-slate-800">
                                    Mandate & Core Values
                                </h2>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="text-sm font-bold text-slate-700"
                                        >Our Mission</label
                                    >
                                    <textarea
                                        v-model="form.mission"
                                        rows="6"
                                        placeholder="What is our purpose?"
                                        class="mt-2 min-h-[150px] w-full rounded-xl border-slate-200 bg-slate-50/50 p-4 text-slate-900 transition-all focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    ></textarea>
                                </div>
                                <div>
                                    <label
                                        class="text-sm font-bold text-slate-700"
                                        >Our Vision</label
                                    >
                                    <textarea
                                        v-model="form.vision"
                                        rows="6"
                                        placeholder="What is our future goal?"
                                        class="mt-2 min-h-[150px] w-full rounded-xl border-slate-200 bg-slate-50/50 p-4 text-slate-900 transition-all focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                    >
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <ImageIcon class="h-5 w-5 text-purple-600" />
                                <h2 class="font-bold text-slate-800">
                                    Image Gallery
                                </h2>
                            </div>
                            <label
                                class="flex cursor-pointer items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 transition-colors hover:bg-slate-200"
                            >
                                <Plus class="h-4 w-4" />
                                <span class="text-sm font-bold"
                                    >Add Images</span
                                >
                                <input
                                    type="file"
                                    multiple
                                    class="hidden"
                                    @change="handleGalleryChange"
                                />
                            </label>
                        </div>

                        <div
                            class="grid grid-cols-2 gap-4 sm:grid-cols-4 md:grid-cols-6"
                        >
                            <!-- NEW IMAGES -->
                            <div
                                v-for="(file, idx) in form.gallery_images"
                                :key="'new-' + idx"
                                class="group relative aspect-square overflow-hidden rounded-xl border-2 border-emerald-400"
                            >
                                <img
                                    :src="getFilePreview(file)"
                                    class="h-full w-full object-cover"
                                />

                                <!-- Delete button (hover only) -->
                                <button
                                    type="button"
                                    @click="removeNewGalleryItem(idx)"
                                    class="absolute top-1 right-1 scale-75 rounded-full bg-red-500 p-1 text-white opacity-0 shadow-md transition-all duration-200 group-hover:scale-100 group-hover:opacity-100"
                                >
                                    <X class="h-3 w-3" />
                                </button>

                                <!-- New badge (optional: always visible) -->
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-emerald-500 py-0.5 text-center text-[8px] font-bold text-white uppercase"
                                >
                                    New
                                </div>
                            </div>

                            <!-- EXISTING IMAGES -->
                            <div
                                v-for="(img, idx) in props.pageContent
                                    ?.gallery_images"
                                :key="'old-' + idx"
                                class="group relative aspect-square overflow-hidden rounded-xl border bg-slate-100 transition-all"
                                :class="
                                    form.delete_gallery_images.includes(img)
                                        ? 'border-red-500 opacity-60 grayscale'
                                        : 'border-slate-200'
                                "
                            >
                                <img
                                    :src="
                                        img.startsWith('http')
                                            ? img
                                            : '/storage/' + img
                                    "
                                    class="h-full w-full object-cover"
                                />

                                <!-- Delete / Restore toggle (hover only) -->
                                <button
                                    type="button"
                                    @click="toggleDeleteExistingGallery(img)"
                                    class="absolute top-1 right-1 scale-75 rounded-full bg-white p-1 opacity-0 shadow-md transition-all duration-200 group-hover:scale-100 group-hover:opacity-100"
                                    :class="
                                        form.delete_gallery_images.includes(img)
                                            ? 'text-emerald-500'
                                            : 'text-slate-400 hover:text-red-500'
                                    "
                                >
                                    <Trash2
                                        v-if="
                                            !form.delete_gallery_images.includes(
                                                img,
                                            )
                                        "
                                        class="h-3 w-3"
                                    />
                                    <Plus v-else class="h-3 w-3 rotate-45" />
                                </button>

                                <!-- Status badge (only show on hover) -->
                                <div
                                    v-if="
                                        !form.delete_gallery_images.includes(
                                            img,
                                        )
                                    "
                                    class="absolute top-1 left-1 rounded-full bg-emerald-500 p-0.5 text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100"
                                >
                                    <CheckCircle2 class="h-3 w-3" />
                                </div>

                                <div
                                    v-else
                                    class="absolute inset-x-0 bottom-0 bg-red-500 py-0.5 text-center text-[8px] font-bold text-white uppercase"
                                >
                                    To Delete
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>
