<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import ResolutionModal from '@/components/ModalResolutions/NewDialog.vue'; // Assuming similar path
import DeleteModal from '@/components/ModalResolutions/Delete.vue';
import ViewModal from '@/components/ModalResolutions/ViewResolutionDialog.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    Edit,
    FileText,
    Image,
    Plus,
    Search,
    Trash2,
    X,
    Eye,
    ClipboardList
} from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

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

interface PaginatedResolutions {
    data: Resolution[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
}

const { props } = usePage<{
    resolutions: PaginatedResolutions;
    filters: { search?: string; year?: string };
    years: number[];
    totalResolutions: number;
    latestYearResolutionsCount: number;
    resolutionsWithPdfCount: number;
    resolutionsWithImageCount: number;
    flash?: { success?: string };
}>();

const resolutions = ref<Resolution[]>([...(props.resolutions?.data || [])]);
const search = ref(props.filters?.search || '');
const year = ref(props.filters?.year || '');
const isDeleteDialogOpen = ref(false);
const deletingResolution = ref<Resolution | null>(null);

// Modal state
const isModalOpen = ref(false);
const editingResolution = ref<Resolution | null>(null);

// Image viewer
const isImageViewerOpen = ref(false);
const viewerImageSrc = ref('');

// Description line clamp
const showFullDesc = ref<number | null>(null);
const descRefs = ref<Record<number, HTMLElement>>({});
const hasMoreThanTwoLines = ref<Record<number, boolean>>({});

const years = computed(() => props.years || []);
const paginationMeta = computed(() => props.resolutions?.meta || null);
const paginationLinks = computed(() => props.resolutions?.links || []);

const paginate = (url: string) => {
    if (url) {
        router.get(url, {}, { preserveScroll: true });
    }
};

const applyFilters = () => {
    router.get(
        '/admin-resolutions',
        { search: search.value, year: year.value, page: 1 },
        { preserveState: true, replace: true },
    );
};

const clearFilters = () => {
    search.value = '';
    year.value = '';
    router.get(
        '/admin-resolutions',
        { search: '', year: '', page: 1 },
        { preserveState: false, replace: true },
    );
};

const openModal = (resolution: Resolution | null = null) => {
    editingResolution.value = resolution;
    isModalOpen.value = true;
};

const handleModalSubmit = () => {
    isModalOpen.value = false;
};

const openDeleteDialog = (resolution: Resolution) => {
    deletingResolution.value = resolution;
    isDeleteDialogOpen.value = true;
};

const toggleShowMore = (id: number) => {
    showFullDesc.value = showFullDesc.value === id ? null : id;
};

const checkLineClamp = () => {
    Object.entries(descRefs.value).forEach(([id, el]) => {
        if (!el) return;
        const lineHeight = parseFloat(getComputedStyle(el).lineHeight);
        hasMoreThanTwoLines.value[Number(id)] = el.scrollHeight > lineHeight * 2 + 2;
    });
};

const isViewOpen = ref(false);
const selectedResolution = ref<Resolution | null>(null);

const openViewModal = (resolution: Resolution) => {
    selectedResolution.value = resolution;
    isViewOpen.value = true;
};

onMounted(() => {
    nextTick(() => checkLineClamp());
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeImageViewer();
    });
});

watch(
    () => props.resolutions?.data,
    (newData) => {
        if (newData) {
            resolutions.value = [...newData];
            nextTick(() => checkLineClamp());
        }
    }
);

const formatDate = (date: string) => {
    if (!date) return '—';
    const d = new Date(date);
    if (isNaN(d.getTime())) return '—';
    return d.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const openImageViewer = (src: string) => {
    viewerImageSrc.value = `/storage/${src}`;
    isImageViewerOpen.value = true;
};

const closeImageViewer = () => {
    isImageViewerOpen.value = false;
    viewerImageSrc.value = '';
};
</script>

<template>
    <Head title="Resolutions Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Legislative Resolutions</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage and track legislative resolutions effectively.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                            <div class="relative flex-1 max-w-md">
                                <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"/>
                                <input v-model="search"
                                    @keyup.enter="applyFilters"
                                    type="text"
                                    placeholder="Search by resolution number, title, or author..."
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"/>
                            </div>

                            <div class="w-full md:w-40">
                                <select v-model="year"
                                    @change="applyFilters"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    <option value="">All Years</option>
                                    <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                                </select>
                            </div>

                            <button v-if="search || year"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                                <X class="h-4 w-4"/>
                                Clear
                            </button>
                        </div>

                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl">
                            <Plus class="h-5 w-5"/>
                            New Resolution
                        </button>
                    </div>

                    <div v-if="search || year" class="flex flex-wrap gap-2">
                        <span v-if="search" class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-900">
                            Search: {{ search }}
                            <button @click="search = ''; applyFilters()" class="hover:text-emerald-700">
                                <X class="h-3 w-3"/>
                            </button>
                        </span>
                        <span v-if="year" class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-900">
                            Year: {{ year }}
                            <button @click="year = ''; applyFilters()" class="hover:text-blue-700">
                                <X class="h-3 w-3"/>
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="flex items-center justify-between rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Resolutions</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ props.totalResolutions }}</p>
                    </div>
                    <ClipboardList class="h-8 w-8 text-emerald-500 opacity-60"/>
                </div>
                <div class="flex items-center justify-between rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">In {{ years[0] || 'N/A' }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ props.latestYearResolutionsCount }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-500 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="flex items-center justify-between rounded-lg border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">With Official PDF</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ props.resolutionsWithPdfCount }}</p>
                    </div>
                    <FileText class="h-8 w-8 text-indigo-500 opacity-60"/>
                </div>
                <div class="flex items-center justify-between rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">With Media Image</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ props.resolutionsWithImageCount }}</p>
                    </div>
                    <Image class="h-8 w-8 text-purple-500 opacity-60"/>
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-hidden rounded-lg border border-slate-100 bg-white shadow-lg">
                    <div v-if="!resolutions || resolutions.length === 0" class="py-16">
                        <div class="flex flex-col items-center">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50">
                                <ClipboardList class="h-7 w-7 text-emerald-500"/>
                            </div>
                            <p class="text-lg font-semibold text-slate-700">No resolutions found</p>
                            <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or create a new resolution.</p>
                        </div>
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">#</th>
                                <th class="min-w-[160px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Res. No.</th>
                                <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Title</th>
                                <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Description</th>
                                <th class="min-w-[150px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Approved</th>
                                <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Author</th>
                                <th class="min-w-[100px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Image</th>
                                <th class="w-[100px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="(resolution, index) in resolutions" :key="resolution.id" class="transition-colors hover:bg-emerald-50/50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                    {{ (paginationMeta?.from || 1) + index }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ resolution.resolutions_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div class="line-clamp-1">{{ resolution.title_resolutions }}</div>
                                    <a v-if="resolution.file_path_resolutions" :href="`/storage/${resolution.file_path_resolutions}`" target="_blank" class="mt-1 flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-800 hover:underline">
                                        <FileText class="h-3 w-3"/>
                                        View PDF
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div class="group relative">
                                        <p :ref="el => descRefs[resolution.id] = (el as HTMLElement)" :class="showFullDesc === resolution.id ? '' : 'line-clamp-2'">
                                            {{ resolution.description_resolutions }}
                                        </p>
                                        <button v-if="hasMoreThanTwoLines[resolution.id]" @click="toggleShowMore(resolution.id)" class="mt-1 text-xs font-medium text-blue-600 underline hover:text-blue-800">
                                            {{ showFullDesc === resolution.id ? 'Show Less' : 'Show More' }}
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(resolution.date_approved_resolutions) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ resolution.author_resolutions || '—' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div v-if="resolution.image_resolutions">
                                        <button @click="openImageViewer(resolution.image_resolutions)" class="overflow-hidden rounded-md border border-slate-300 p-0 shadow-sm transition-all hover:border-emerald-500 hover:shadow-md">
                                            <img :src="`/storage/${resolution.image_resolutions}`" class="h-12 w-12 object-cover"/>
                                        </button>
                                    </div>
                                    <span v-else class="text-xs text-slate-400">—</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openViewModal(resolution)" class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 shadow-sm transition-all hover:bg-emerald-100">
                                            <Eye class="h-4 w-4"/>
                                        </button>
                                        <button @click="openModal(resolution)" class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 shadow-sm transition-all hover:bg-sky-100">
                                            <Edit class="h-4 w-4"/>
                                        </button>
                                        <button @click="openDeleteDialog(resolution)" class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-600 shadow-sm transition-all hover:bg-red-100">
                                            <Trash2 class="h-4 w-4"/>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="paginationLinks && paginationLinks.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6">
                        <div class="text-sm text-slate-600">
                            Showing <span class="font-semibold">{{ paginationMeta?.from }}</span> to
                            <span class="font-semibold">{{ paginationMeta?.to }}</span> of
                            <span class="font-semibold">{{ paginationMeta?.total }}</span> results
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <component v-for="(link, key) in paginationLinks"
                                :key="key"
                                :is="link.url ? 'button' : 'span'"
                                @click="link.url ? paginate(link.url) : null"
                                :disabled="!link.url"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset focus:z-20 transition-all',
                                    key === 0 ? 'rounded-l-md' : '',
                                    key === paginationLinks.length - 1 ? 'rounded-r-md' : '',
                                    link.active ? 'bg-emerald-600 text-white ring-emerald-600' : link.url ? 'text-slate-900 hover:bg-slate-50' : 'cursor-not-allowed text-slate-400 bg-slate-100'
                                ]"
                                v-html="link.label"/>
                        </nav>
                    </div>
                </div>
            </div>

            <ResolutionModal v-model:isOpen="isModalOpen" :resolution="editingResolution" @submitted="handleModalSubmit" @close="isModalOpen = false"/>
            <DeleteModal :is-open="isDeleteDialogOpen" :resolution="deletingResolution" @close="isDeleteDialogOpen = false"/>
            <ViewModal :is-open="isViewOpen" :resolution="selectedResolution" @close="isViewOpen = false" />

            <div v-if="isImageViewerOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="closeImageViewer">
                <div class="relative max-w-3xl w-full">
                    <button @click="closeImageViewer" class="absolute -top-3 -right-3 z-50 rounded-full bg-white p-2 shadow-lg hover:bg-slate-100">
                        <X class="h-5 w-5 text-slate-700" />
                    </button>
                    <img :src="viewerImageSrc" class="mx-auto max-h-[80vh] max-w-full rounded-lg shadow-2xl object-contain"/>
                </div>
            </div>
        </main>
    </div>
</template>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.fade-slide-enter-from { opacity: 0; transform: translateY(-20px); }
.fade-slide-enter-to { opacity: 1; transform: translateY(0); }
.fade-slide-leave-to { opacity: 0; transform: translateY(-20px); }
</style>