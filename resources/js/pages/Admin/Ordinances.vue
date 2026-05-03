<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import OrdinanceModal from '@/components/ModalOrdinances/NewDialog.vue';
import DeleteModal from '@/components/ModalOrdinances/Delete.vue';
import ViewModal from '@/components/ModalOrdinances/ViewDialog.vue';
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
    ClipboardList,
    ChevronRight,
    ChevronLeft,
    MoreHorizontal
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
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}


// PROPS - Access directly from usePage
const page = usePage<{
    ordinances: PaginatedOrdinances;
    filters: { search?: string; year?: string };
    years: number[];
    totalOrdinances: number;
    latestYearOrdinancesCount: number;
    ordinancesWithPdfCount: number;
    ordinancesWithImageCount: number;
}>();

const filteredLinks = computed(() => {
    const links = page.props.ordinances.links;
    if (links.length <= 10) return links;

    const total = links.length;
    const current = links.findIndex(l => l.active);
    const result = [];

    // Always include Previous (index 0)
    result.push(links[0]);

    // Logic for pages and ellipsis
    for (let i = 1; i < total - 1; i++) {
        // Always show first and last page
        if (i === 1 || i === total - 2) {
            result.push(links[i]);
            continue;
        }

        // Show range around active page
        if (i >= current - 1 && i <= current + 1) {
            result.push(links[i]);
            continue;
        }

        // Add ellipsis if we haven't just added one
        if (result[result.length - 1].label !== '...') {
            result.push({ url: null, label: '...', active: false });
        }
    }

    // Always include Next (last index)
    result.push(links[total - 1]);

    return result;
});

// STATE - Bound to inputs
const search = ref(page.props.filters?.search || '');
const year = ref(page.props.filters?.year || '');

// MODAL STATES
const isDeleteDialogOpen = ref(false);
const deletingOrdinance = ref<Ordinance | null>(null);
const isModalOpen = ref(false);
const editingOrdinance = ref<Ordinance | null>(null);
const isViewOpen = ref(false);
const selectedOrdinance = ref<Ordinance | null>(null);
const isImageViewerOpen = ref(false);
const viewerImageSrc = ref('');

// UI LOGIC STATES
const showFullDesc = ref<number | null>(null);
const descRefs = ref<Record<number, HTMLElement>>({});
const hasMoreThanTwoLines = ref<Record<number, boolean>>({});

// ✅ COMPUTED: This is the secret sauce. 
// It automatically updates whenever page.props.ordinances changes.
const ordinancesList = computed(() => page.props.ordinances?.data || []);
const yearsList = computed(() => page.props.years || []);

// ✅ REFACTORED NAVIGATION/FILTER FUNCTION
const runNavigation = (pageNumber: string | number | null = 1) => {
    router.get(
        '/admin-ordinances',
        {
            search: search.value,
            year: year.value,
            page: pageNumber
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['ordinances', 'filters'], // Only reload the data and filters
        }
    );
};

// HANDLERS
const handleEnter = () => runNavigation(1);
const applyFilters = () => runNavigation(1);
const clearFilters = () => {
    search.value = '';
    year.value = '';
    router.get('/admin-ordinances', {}, { replace: true });
};

// YEAR CHANGE - Auto Trigger
watch(year, () => applyFilters());

// ✅ FIXED PAGINATION LOGIC
const paginate = (url: string | null) => {
    if (!url) return;
    const urlObj = new URL(url, window.location.origin);
    const pageParam = urlObj.searchParams.get('page');
    runNavigation(pageParam);
};

// MODAL METHODS
const openModal = (ordinance: Ordinance | null = null) => {
    editingOrdinance.value = ordinance;
    isModalOpen.value = true;
};
const openDeleteDialog = (ordinance: Ordinance) => {
    deletingOrdinance.value = ordinance;
    isDeleteDialogOpen.value = true;
};
const openViewModal = (ordinance: Ordinance) => {
    selectedOrdinance.value = ordinance;
    isViewOpen.value = true;
};

// IMAGE VIEWING
const openImageViewer = (src: string) => {
    viewerImageSrc.value = `/storage/${src}`;
    isImageViewerOpen.value = true;
};
const closeImageViewer = () => {
    isImageViewerOpen.value = false;
    viewerImageSrc.value = '';
};

// HELPERS
const formatDate = (date: string) => {
    if (!date) return '—';
    const d = new Date(date);
    return isNaN(d.getTime()) ? '—' : d.toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    });
};

const checkLineClamp = () => {
    Object.entries(descRefs.value).forEach(([id, el]) => {
        if (!el) return;
        const lineHeight = parseFloat(getComputedStyle(el).lineHeight);
        hasMoreThanTwoLines.value[Number(id)] = el.scrollHeight > lineHeight * 2 + 2;
    });
};

// Sync Line Clamp on data change
watch(() => page.props.ordinances.data, () => {
    nextTick(() => checkLineClamp());
}, { deep: true });

onMounted(() => {
    nextTick(() => checkLineClamp());
    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeImageViewer(); });
});
</script>

<template>
    <Head title="Ordinances Management" />
    <!-- Explicitly set light background to prevent system-level dark mode overrides -->
    <div class="flex h-screen bg-slate-50 text-slate-900">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <!-- Header Section -->
            <div class="sticky top-0 z-10 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Legislative Ordinances</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage legislative ordinances and regulations efficiently.</p>
                    </div>
                </div>

                <!-- Filters and Actions -->
                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                            <div class="relative flex-1 max-w-md">
                                <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"/>
                                <input
                                    v-model="search"
                                    @keydown.enter.prevent="handleEnter"
                                    type="text"
                                    placeholder="Search by ordinance number or title..."
                                    class="w-full h-11 rounded-xl border border-slate-300 
                                        pl-10 pr-4 bg-white text-slate-900
                                        focus:ring-2 focus:ring-emerald-500 
                                        focus:outline-none transition"
                                />
                            </div>

                            <div class="relative w-full md:w-48">
                                <select v-model="year"
                                    @change="applyFilters"
                                    class="w-full h-11 rounded-xl border border-slate-300 
                                    px-4 pr-10 bg-white text-slate-700
                                    focus:ring-2 focus:ring-emerald-500 
                                    focus:outline-none appearance-none transition">
                                    <option value="">All Years</option>
                                    <option v-for="y in yearsList" :key="y" :value="y">{{ y }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <button v-if="search || year"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 bg-white shadow-sm transition-all hover:bg-slate-50">
                                <X class="h-4 w-4"/> Clear
                            </button>
                        </div>

                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl">
                            <Plus class="h-5 w-5"/> New Ordinance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Total</p><p class="text-3xl font-bold text-slate-900">{{ page.props.totalOrdinances }}</p></div>
                    <FileText class="h-8 w-8 text-emerald-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">In {{ yearsList[0] || 'N/A' }}</p><p class="text-3xl font-bold text-slate-900">{{ page.props.latestYearOrdinancesCount }}</p></div>
                    <FileText class="h-8 w-8 text-sky-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-indigo-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">PDFs</p><p class="text-3xl font-bold text-slate-900">{{ page.props.ordinancesWithPdfCount }}</p></div>
                    <FileText class="h-8 w-8 text-indigo-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">With Image</p><p class="text-3xl font-bold text-slate-900">{{ page.props.ordinancesWithImageCount }}</p></div>
                    <Image class="h-8 w-8 text-purple-500 opacity-60"/>
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-8">
                <div class="overflow-hidden rounded-lg border border-slate-100 bg-white shadow-lg">
                    <div v-if="ordinancesList.length === 0" class="py-16 text-center">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50">
                            <ClipboardList class="h-7 w-7 text-emerald-500"/>
                        </div>
                        <p class="text-lg font-semibold text-slate-700">No ordinances found</p>
                        <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or create a new resolution.</p>
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">#</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Ordinance No.</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Title</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Description</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Approved</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <tr v-for="(ordinance, index) in ordinancesList" :key="ordinance.id" class="hover:bg-emerald-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-600">{{ (page.props.ordinances?.from || 1) + index }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ ordinance.ordinance_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-900">
                                    <div class="line-clamp-1">{{ ordinance.title_ordinances }}</div>
                                    <a v-if="ordinance.file_path_ordinances" :href="`/storage/${ordinance.file_path_ordinances}`" target="_blank" class="text-xs text-emerald-600 hover:underline">View PDF</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <p :ref="el => descRefs[ordinance.id] = (el as HTMLElement)" :class="showFullDesc === ordinance.id ? '' : 'line-clamp-2'">
                                        {{ ordinance.description_ordinances }}
                                    </p>
                                    <button v-if="hasMoreThanTwoLines[ordinance.id]" @click="showFullDesc = showFullDesc === ordinance.id ? null : ordinance.id" class="text-xs text-blue-600 underline">
                                        {{ showFullDesc === ordinance.id ? 'Show Less' : 'Show More' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(ordinance.date_approved_ordinances) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openViewModal(ordinance)" class="p-2 bg-emerald-50 text-emerald-600 rounded-full hover:bg-emerald-100 transition-colors"><Eye class="h-4 w-4"/></button>
                                        <button @click="openModal(ordinance)" class="p-2 bg-sky-50 text-sky-600 rounded-full hover:bg-sky-100 transition-colors"><Edit class="h-4 w-4"/></button>
                                        <button @click="openDeleteDialog(ordinance)" class="p-2 bg-red-50 text-red-600 rounded-full hover:bg-red-100 transition-colors"><Trash2 class="h-4 w-4"/></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="page.props.ordinances.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ page.props.ordinances.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ page.props.ordinances.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ page.props.ordinances.total }}</span>
                        </div>

                        <nav class="inline-flex -space-x-px rounded-lg bg-white shadow-sm border border-slate-200" aria-label="Pagination">
                            <template v-for="(link, key) in filteredLinks" :key="key">
                                <div v-if="link.label === '...'" 
                                     class="relative inline-flex items-center px-3 py-2 text-slate-400 bg-white">
                                    <MoreHorizontal class="h-4 w-4" />
                                </div>
                                <button
                                    v-else
                                    :disabled="!link.url || link.active"
                                    @click="paginate(link.url)"
                                    class="relative inline-flex items-center justify-center min-w-[40px] h-10 px-3 text-sm font-semibold transition-all first:rounded-l-lg last:rounded-r-lg"
                                    :class="[
                                        link.active 
                                            ? 'z-10 bg-emerald-600 text-white border-emerald-600' 
                                            : 'bg-white text-slate-500 hover:bg-slate-50 hover:text-emerald-600',
                                        !link.url ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer',
                                        key !== 0 ? 'border-l border-slate-200' : ''
                                    ]"
                                >
                                    <ChevronLeft v-if="link.label.includes('Previous')" class="h-4 w-4" />
                                    <ChevronRight v-else-if="link.label.includes('Next')" class="h-4 w-4" />
                                    <span v-else>{{ link.label }}</span>
                                </button>
                            </template>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Modals -->
            <OrdinanceModal v-model:isOpen="isModalOpen" :ordinance="editingOrdinance" @close="isModalOpen = false"/>
            <DeleteModal :is-open="isDeleteDialogOpen" :ordinance="deletingOrdinance" @close="isDeleteDialogOpen = false"/>
            <ViewModal :is-open="isViewOpen" :ordinance="selectedOrdinance" @close="isViewOpen = false" />
            
            <!-- Image Viewer Overlay -->
            <div v-if="isImageViewerOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-4" @click.self="closeImageViewer">
                <div class="relative max-w-3xl w-full">
                    <img :src="viewerImageSrc" class="mx-auto max-h-[80vh] rounded-lg shadow-2xl"/>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>