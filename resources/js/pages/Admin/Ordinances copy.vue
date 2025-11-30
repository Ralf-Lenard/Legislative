<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import OrdinanceModal from '@/components/ModalOrdinances/NewDialog.vue';
import DeleteModal from '@/components/ModalOrdinances/Delete.vue';
import { router, usePage } from '@inertiajs/vue3';
import {
    Edit,
    FileText,
    Image,
    Plus,
    Search,
    Trash2,
} from 'lucide-vue-next';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

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

interface PaginatedOrdinances {
    data: Ordinance[];
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
    ordinances: PaginatedOrdinances;
    filters: { search?: string; year?: string };
    years: number[];
    flash?: { success?: string };
}>();

const flashMessage = ref<string | null>(props.flash?.success || null);
const ordinances = ref<Ordinance[]>([...props.ordinances.data]);
const search = ref(props.filters.search || '');
const year = ref(props.filters.year || '');
const isDeleteDialogOpen = ref(false);
const isLoading = ref(false);
const deletingOrdinance = ref<Ordinance | null>(null);

// Modal state
const isModalOpen = ref(false);
const editingOrdinance = ref<Ordinance | null>(null);

// Image viewer
const isImageViewerOpen = ref(false);
const viewerImageSrc = ref('');

// Description line clamp
const showFullDesc = ref<number | null>(null);
const descRefs = ref<Record<number, HTMLElement>>({});
const hasMoreThanTwoLines = ref<Record<number, boolean>>({});

const filteredOrdinances = computed(() =>
    ordinances.value.filter((o) => {
        const matchesSearch =
            o.ordinance_number.toLowerCase().includes(search.value.toLowerCase()) ||
            o.title_ordinances.toLowerCase().includes(search.value.toLowerCase()) ||
            o.description_ordinances.toLowerCase().includes(search.value.toLowerCase());

        const dateApproved = new Date(o.date_approved_ordinances);
        const matchesYear = year.value
            ? dateApproved.getFullYear() === +year.value && !isNaN(dateApproved.getTime())
            : true;

        return matchesSearch && matchesYear;
    })
);

const years = computed(() => props.years);
const ordinancesWithPdfCount = computed(() => ordinances.value.filter(o => o.file_path_ordinances).length);
const ordinancesWithImageCount = computed(() => ordinances.value.filter(o => o.image_ordinances).length);
const latestYearOrdinancesCount = computed(() => {
    const latestYear = years.value[0];
    if (!latestYear) return 0;
    return ordinances.value.filter(o => new Date(o.date_approved_ordinances).getFullYear() === latestYear).length;
});

const paginate = (url: string) => {
    router.get(url, {}, { preserveScroll: true });
};

const applyFilters = () => {
    router.get(
        '/admin-ordinances',
        { search: search.value, year: year.value },
        { preserveState: true, replace: true },
    );
};

const openModal = (ordinance: Ordinance | null = null) => {
    editingOrdinance.value = ordinance;
    isModalOpen.value = true;
};

const handleModalSubmit = () => {
  isModalOpen.value = false;
  router.reload(); 
};

const openDeleteDialog = (ordinance: Ordinance) => {
  deletingOrdinance.value = ordinance;
  isDeleteDialogOpen.value = true;
};

const toggleShowMore = (id: number) => {
    showFullDesc.value = showFullDesc.value === id ? null : id;
};

const checkLineClamp = () => {
    Object.entries(descRefs.value).forEach(([id, el]) => {
        if (!el) return;
        const lineHeight = parseFloat(getComputedStyle(el).lineHeight);
        hasMoreThanTwoLines.value[id] = el.scrollHeight > lineHeight * 2 + 2;
    });
};

onMounted(() => {
    nextTick(() => checkLineClamp());
    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeImageViewer();
    });
});

watch(
  () => props.flash?.success,
  (newVal) => {
    if (newVal) {
      flashMessage.value = newVal;
      setTimeout(() => (flashMessage.value = null), 3000);
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
<div class="flex h-screen bg-slate-50">
    <AppSidebar />

    <main class="relative flex-1 overflow-auto">
        <!-- Flash Message -->
        <transition name="fade-slide">
            <div v-if="flashMessage"
                class="fixed top-4 right-4 z-[9999] flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ flashMessage }}
            </div>
        </transition>

        <!-- Header + Filters -->
        <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
            <div class="flex items-center justify-between px-8 py-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">Municipal Ordinances</h1>
                    <p class="mt-1 text-sm text-slate-600">Manage municipal ordinances and regulations efficiently.</p>
                </div>
            </div>

            <div class="flex flex-col gap-4 px-8 pb-6 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-4">
                    <div class="relative max-w-md flex-1">
                        <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"/>
                        <input v-model="search" @input="applyFilters" type="text"
                               placeholder="Search by ordinance number or title..."
                               class="w-full rounded-xl border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"/>
                    </div>
                    <div class="w-40">
                        <select v-model="year" @change="applyFilters"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="">All Years</option>
                            <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>

                <button @click="openModal()"
                        class="flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl">
                    <Plus class="h-5 w-5"/> New Ordinance
                </button>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex items-center justify-between rounded-xl border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Ordinances</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ ordinances.length }}</p>
                </div>
                <FileText class="h-8 w-8 text-emerald-500 opacity-60"/>
            </div>
            <div class="flex items-center justify-between rounded-xl border-l-4 border-sky-500 bg-white p-5 shadow-lg">
                <div>
                    <p class="text-sm font-medium text-slate-500">In {{ years[0] || 'N/A' }}</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ latestYearOrdinancesCount }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-sky-500 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="flex items-center justify-between rounded-xl border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                <div>
                    <p class="text-sm font-medium text-slate-500">With Official PDF</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ ordinancesWithPdfCount }}</p>
                </div>
                <FileText class="h-8 w-8 text-indigo-500 opacity-60"/>
            </div>
            <div class="flex items-center justify-between rounded-xl border-l-4 border-purple-500 bg-white p-5 shadow-lg">
                <div>
                    <p class="text-sm font-medium text-slate-500">With Media Image</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ ordinancesWithImageCount }}</p>
                </div>
                <Image class="h-8 w-8 text-purple-500 opacity-60"/>
            </div>
        </div>

        <!-- Ordinances Table -->
        <div class="p-8">
            <div class="overflow-x-auto rounded-2xl border border-slate-100 bg-white shadow-xl">
                <table class="w-full min-w-[900px] divide-y divide-slate-200 overflow-hidden rounded-lg border border-slate-200 shadow-sm">
                    <thead class="border-b border-slate-200 bg-slate-100/80 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">#</th>
                            <th class="min-w-[160px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Ordinance No.</th>
                            <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Title</th>
                            <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Description</th>
                            <th class="min-w-[150px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Approved</th>
                            <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Author</th>
                            <th class="min-w-[100px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Image</th>
                            <th class="w-[100px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-if="filteredOrdinances.length === 0">
                            <td colspan="10" class="py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50">
                                        <FileText class="h-7 w-7 text-emerald-500"/>
                                    </div>
                                    <p class="text-lg font-semibold text-slate-700">No ordinances found</p>
                                    <p class="mt-1 text-sm text-slate-500">Try adjusting your filters.</p>
                                </div>
                            </td>
                        </tr>

                        <tr v-for="(ordinance, index) in filteredOrdinances" :key="ordinance.id" class="transition-all hover:bg-emerald-50/40">
                            <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ ordinance.ordinance_number }}</td>
                            <td class="line-clamp-1 max-w-xs px-6 py-4 text-sm text-slate-700">
                                {{ ordinance.title_ordinances }}
                                <a v-if="ordinance.file_path_ordinances" :href="`/storage/${ordinance.file_path_ordinances}`" target="_blank" class="mt-1 flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-800 hover:underline">
                                    <FileText class="h-3 w-3"/> View PDF
                                </a>
                            </td>
                            <td class="px-2 py-2 text-sm text-slate-900">
                                <div class="group relative">
                                    <p :ref="el => descRefs[ordinance.id] = el" :class="showFullDesc === ordinance.id ? '' : 'line-clamp-2 overflow-hidden'">
                                        {{ ordinance.description_ordinances }}
                                    </p>
                                    <button v-if="hasMoreThanTwoLines[ordinance.id]" @click="toggleShowMore(ordinance.id)" class="mt-1 text-xs font-medium text-blue-600 underline hover:text-blue-800">
                                        {{ showFullDesc === ordinance.id ? 'Show Less' : 'Show More' }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(ordinance.date_approved_ordinances) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ ordinance.author_ordinances || '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div v-if="ordinance.image_ordinances">
                                    <button @click="openImageViewer(ordinance.image_ordinances)" class="overflow-hidden rounded-md border border-slate-300 p-0 shadow-sm transition-all hover:border-emerald-500">
                                        <img :src="`/storage/${ordinance.image_ordinances}`" class="h-12 w-12 object-cover"/>
                                    </button>
                                </div>
                                <span v-else class="text-xs text-slate-400">—</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openModal(ordinance)" class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 shadow-sm transition-all hover:bg-sky-100">
                                        <Edit class="h-4 w-4"/>
                                    </button>
                                    <button @click="openDeleteDialog(ordinance)" class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-600 shadow-sm transition-all hover:bg-red-100">
                                        <Trash2 class="h-4 w-4"/>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="props.ordinances.links.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-3 sm:px-6">
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <component :is="link.url ? 'button' : 'span'" v-for="(link, key) in props.ordinances.links" :key="key"
                                   @click="link.url ? paginate(link.url) : null" :disabled="!link.url"
                                   :class="[
                                     'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset focus:z-20',
                                     key === 0 ? 'rounded-l-md' : '',
                                     key === props.ordinances.links.length - 1 ? 'rounded-r-md' : '',
                                     link.active ? 'bg-emerald-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600' : link.url ? 'text-slate-900 ring-slate-300 hover:bg-slate-50' : 'cursor-default text-slate-400 ring-slate-300'
                                   ]" v-html="link.label"/>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <OrdinanceModal v-model:isOpen="isModalOpen" :ordinance="editingOrdinance" @submitted="handleModalSubmit" @close="isModalOpen = false"/>
        <DeleteModal :is-open="isDeleteDialogOpen" :ordinance="deletingOrdinance" @close="isDeleteDialogOpen = false"/>

        <!-- Image Viewer -->
        <div v-if="isImageViewerOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" @click.self="closeImageViewer">
            <div class="relative max-w-3xl w-full">
                <button @click="closeImageViewer" class="absolute -top-3 -right-3 z-50 rounded-full bg-white p-2 shadow-lg hover:bg-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <img :src="viewerImageSrc" class="mx-auto max-h-[80vh] max-w-full rounded-lg shadow-2xl object-contain"/>
            </div>
        </div>

    </main>
</div>
</template>

<style>
/* Utility for single line clamp */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom file input style */
input[type='file']::file-selector-button {
    cursor: pointer;
}

/* FLASH ANIMATION */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-20px);
}

.fade-slide-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
