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
    ClipboardList
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
const paginationMeta = computed(() => page.props.ordinances?.meta || null);
const paginationLinks = computed(() => page.props.ordinances?.links || []);
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
    
    // Extract page number from the URL string
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
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-10 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Legislative Ordinances</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage legislative ordinances and regulations efficiently.</p>
                    </div>
                </div>

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
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <div class="w-full md:w-40">
                                <select v-model="year"
                                    @change="applyFilters"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    <option value="">All Years</option>
                                    <option v-for="y in yearsList" :key="y" :value="y">{{ y }}</option>
                                </select>
                            </div>

                            <button v-if="search || year"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50">
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

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Total</p><p class="text-3xl font-bold">{{ page.props.totalOrdinances }}</p></div>
                    <FileText class="h-8 w-8 text-emerald-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">In {{ yearsList[0] || 'N/A' }}</p><p class="text-3xl font-bold">{{ page.props.latestYearOrdinancesCount }}</p></div>
                    <FileText class="h-8 w-8 text-sky-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-indigo-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">PDFs</p><p class="text-3xl font-bold">{{ page.props.ordinancesWithPdfCount }}</p></div>
                    <FileText class="h-8 w-8 text-indigo-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Images</p><p class="text-3xl font-bold">{{ page.props.ordinancesWithImageCount }}</p></div>
                    <Image class="h-8 w-8 text-purple-500 opacity-60"/>
                </div>
            </div>

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
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="(ordinance, index) in ordinancesList" :key="ordinance.id" class="hover:bg-emerald-50/50">
                                <td class="px-6 py-4 text-sm">{{ (paginationMeta?.from || 1) + index }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">{{ ordinance.ordinance_number }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="line-clamp-1">{{ ordinance.title_ordinances }}</div>
                                    <a v-if="ordinance.file_path_ordinances" :href="`/storage/${ordinance.file_path_ordinances}`" target="_blank" class="text-xs text-emerald-600 hover:underline">View PDF</a>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <p :ref="el => descRefs[ordinance.id] = (el as HTMLElement)" :class="showFullDesc === ordinance.id ? '' : 'line-clamp-2'">
                                        {{ ordinance.description_ordinances }}
                                    </p>
                                    <button v-if="hasMoreThanTwoLines[ordinance.id]" @click="showFullDesc = showFullDesc === ordinance.id ? null : ordinance.id" class="text-xs text-blue-600 underline">
                                        {{ showFullDesc === ordinance.id ? 'Show Less' : 'Show More' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ formatDate(ordinance.date_approved_ordinances) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openViewModal(ordinance)" class="p-2 bg-emerald-50 text-emerald-600 rounded-full hover:bg-emerald-100"><Eye class="h-4 w-4"/></button>
                                        <button @click="openModal(ordinance)" class="p-2 bg-sky-50 text-sky-600 rounded-full hover:bg-sky-100"><Edit class="h-4 w-4"/></button>
                                        <button @click="openDeleteDialog(ordinance)" class="p-2 bg-red-50 text-red-600 rounded-full hover:bg-red-100"><Trash2 class="h-4 w-4"/></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="paginationLinks.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6">
                        <div class="text-sm text-slate-600">
                            Showing <span class="font-semibold">{{ paginationMeta?.from }}</span> to <span class="font-semibold">{{ paginationMeta?.to }}</span> of <span class="font-semibold">{{ paginationMeta?.total }}</span>
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                            <button
                                v-for="(link, key) in paginationLinks"
                                :key="key"
                                :disabled="!link.url"
                                @click="paginate(link.url)"
                                v-html="link.label"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset transition-all',
                                    key === 0 ? 'rounded-l-lg' : '',
                                    key === paginationLinks.length - 1 ? 'rounded-r-lg' : '',
                                    link.active ? 'z-10 bg-emerald-600 text-white ring-emerald-600' : link.url ? 'text-slate-700 hover:bg-slate-100' : 'text-slate-300 bg-slate-50 cursor-not-allowed'
                                ]"
                            />
                        </nav>
                    </div>
                </div>
            </div>

            <OrdinanceModal v-model:isOpen="isModalOpen" :ordinance="editingOrdinance" @close="isModalOpen = false"/>
            <DeleteModal :is-open="isDeleteDialogOpen" :ordinance="deletingOrdinance" @close="isDeleteDialogOpen = false"/>
            <ViewModal :is-open="isViewOpen" :ordinance="selectedOrdinance" @close="isViewOpen = false" />
            
            <div v-if="isImageViewerOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-4" @click.self="closeImageViewer">
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