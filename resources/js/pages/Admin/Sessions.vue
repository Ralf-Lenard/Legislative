<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import DeleteModal from '@/components/ModalSession/DeleteDialog.vue';
import SessionModal from '@/components/ModalSession/SessionDialog.vue';
import ViewModal from '@/components/ModalSession/ViewDialog.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';

import { Head, router, usePage } from '@inertiajs/vue3';
import {
    Calendar,
    Clock,
    Edit,
    Eye,
    FileText,
    Layout,
    Plus,
    Search,
    Trash2,
    X,
    ChevronRight,
    ChevronLeft,
    MoreHorizontal
} from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

/* =======================
       TYPES
    ======================= */

interface LegislativeSession {
    id: number;
    session_number: string;
    session_title: string;
    date_of_session: string;
    session_type: 'Regular' | 'Special';
    summary: string;
    images: any[] | null;
    created_at: string;
}

// interface PaginatedSessions {
//     data: LegislativeSession[];
//     links: { url: string | null; label: string; active: boolean; }[];
//     meta: {
//         current_page: number;
//         from: number | null;
//         last_page: number;
//         per_page: number;
//         to: number | null;
//         total: number;
//     };
// }

interface PaginatedSessions {
    data: LegislativeSession[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}


/* =======================
       INERTIA PROPS
    ======================= */

const page = usePage<{
    sessions: PaginatedSessions;
    filters: { search?: string; year?: string };
    years: string[];
}>();

const filteredLinks = computed(() => {
    const links = page.props.sessions.links;
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

/* =======================
       STATE
    ======================= */

// Sync state with props filters
const search = ref(page.props.filters?.search || '');
const yearFilter = ref(page.props.filters?.year || '');

// Modals
const isModalOpen = ref(false);
const editingSession = ref<LegislativeSession | null>(null);
const isDeleteDialogOpen = ref(false);
const deletingSession = ref<LegislativeSession | null>(null);
const isViewModalOpen = ref(false);
const viewingSession = ref<LegislativeSession | null>(null);

/* =======================
       COMPUTED (The Ordinance Logic)
    ======================= */

// Automatically reactive to page.props changes
const sessionsList = computed(() => page.props.sessions?.data || []);
const paginationMeta = computed(() => page.props.sessions?.meta);
const paginationLinks = computed(() => page.props.sessions?.links || []);
const yearsList = computed(() => page.props.years || []);

// Stats logic based on current list
const regularSessionsCount = computed(
    () => sessionsList.value.filter((s) => s.session_type === 'Regular').length,
);

const specialSessionsCount = computed(
    () => sessionsList.value.filter((s) => s.session_type === 'Special').length,
);

/* =======================
       METHODS (Refactored to Ordinance Style)
    ======================= */

const runNavigation = (pageNumber: string | number | null = 1) => {
    router.get(
        '/admin-sessions',
        {
            search: search.value,
            year: yearFilter.value,
            page: pageNumber
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['sessions', 'filters'], 
        }
    );
};

const handleEnter = () => runNavigation(1);
const applyFilters = () => runNavigation(1);

const clearFilters = () => {
    search.value = '';
    yearFilter.value = '';
    router.get('/admin-sessions', {}, { replace: true });
};

const paginate = (url: string | null) => {
    if (!url) return;
    const urlObj = new URL(url, window.location.origin);
    const pageParam = urlObj.searchParams.get('page');
    runNavigation(pageParam);
};

// Modal Handlers
const openModal = (session: LegislativeSession | null = null) => {
    editingSession.value = session;
    isModalOpen.value = true;
};

const openDeleteDialog = (session: LegislativeSession) => {
    deletingSession.value = session;
    isDeleteDialogOpen.value = true;
};

const openViewModal = (session: LegislativeSession) => {
    viewingSession.value = session;
    isViewModalOpen.value = true;
};

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

/* =======================
       LIFECYCLE
    ======================= */

onMounted(() => {
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            isModalOpen.value = false;
            isDeleteDialogOpen.value = false;
            isViewModalOpen.value = false;
        }
    });
});

// Watch year change to auto-trigger like in Ordinance page
watch(yearFilter, () => applyFilters());
</script>
<template>
    <Head title="Sessions Management" />
    <div class="flex h-screen bg-slate-50 text-slate-900">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <!-- Header Section -->
            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Legislative Sessions</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage session numbers, titles, and legislative summaries.</p>
                    </div>
                </div>

                <!-- Search and Filters Bar -->
                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex flex-1 flex-wrap gap-3 items-center">
                            
                            <!-- Search Input -->
                            <div class="relative w-full max-w-md">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
                                <input
                                    v-model="search"
                                    @keydown.enter.prevent="handleEnter"
                                    type="text"
                                    placeholder="Search number, title, or summary..."
                                    class="w-full h-11 rounded-xl border border-slate-300 pl-10 pr-4 bg-white text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-sm"
                                />
                            </div>

                            <!-- Year Filter -->
                            <div class="relative w-full md:w-44">
                                <select
                                    v-model="yearFilter"
                                    @change="applyFilters"
                                    class="w-full h-11 rounded-xl border border-slate-300 px-4 pr-10 bg-white text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none transition shadow-sm font-medium"
                                >
                                    <option value="">All Years</option>
                                    <option v-for="year in yearsList" :key="year" :value="year">{{ year }}</option>
                                </select>
                                <ChevronDown class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none" />
                            </div>

                            <!-- Clear Button -->
                            <button
                                v-if="search || yearFilter"
                                @click="clearFilters"
                                class="flex items-center gap-2 h-11 rounded-xl border border-slate-300 px-4 font-medium text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm"
                            >
                                <X class="h-4 w-4" /> Clear
                            </button>
                        </div>

                        <!-- Add Action -->
                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 font-bold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all active:scale-95">
                            <Plus class="h-5 w-5"/> New Session
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Total Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 border-emerald-600 bg-white p-5 shadow-lg transition-all">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Database</p>
                        <p class="text-sm font-medium text-slate-500">Total Records</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ paginationMeta?.total || 0 }}</p>
                    </div>
                    <div class="rounded-full bg-emerald-50 p-2.5 text-emerald-600">
                        <Layout class="h-6 w-6"/>
                    </div>
                </div>

                <!-- Regular Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 border-sky-600 bg-white p-5 shadow-lg transition-all">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-sky-600">Scheduled</p>
                        <p class="text-sm font-medium text-slate-500">Regular Sessions</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ regularSessionsCount }}</p>
                    </div>
                    <div class="rounded-full bg-sky-50 p-2.5 text-sky-600">
                        <Calendar class="h-6 w-6"/>
                    </div>
                </div>

                <!-- Special Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 border-purple-600 bg-white p-5 shadow-lg transition-all">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600">Urgent</p>
                        <p class="text-sm font-medium text-slate-500">Special Sessions</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ specialSessionsCount }}</p>
                    </div>
                    <div class="rounded-full bg-purple-50 p-2.5 text-purple-600">
                        <Clock class="h-6 w-6"/>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-8">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                    <div v-if="!sessionsList.length" class="py-20 text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                            <FileX class="h-8 w-8"/>
                        </div>
                        <p class="text-lg font-bold text-slate-700">No sessions found</p>
                        <p class="text-sm text-slate-500">Adjust your filters or add a new session record.</p>
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Number</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Session Title</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Type</th>
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <tr v-for="session in sessionsList" :key="session.id" class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-slate-900">{{ session.session_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs truncate text-sm font-bold text-slate-800" :title="session.session_title">
                                        {{ session.session_title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                    {{ formatDate(session.date_of_session) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="session.session_type === 'Regular' 
                                        ? 'text-sky-700 bg-sky-50 border-sky-100' 
                                        : 'text-purple-700 bg-purple-50 border-purple-100'" 
                                        class="inline-block rounded-md border px-2.5 py-1 text-[10px] font-black uppercase tracking-tight">
                                        {{ session.session_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openViewModal(session)" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="View Details">
                                            <Eye class="h-4.5 w-4.5"/>
                                        </button>
                                        <button @click="openModal(session)" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm" title="Edit Session">
                                            <Edit class="h-4.5 w-4.5"/>
                                        </button>
                                        <button @click="openDeleteDialog(session)" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Delete">
                                            <Trash2 class="h-4.5 w-4.5"/>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="page.props.sessions.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-8 py-5">
                        <div class="text-sm font-medium text-slate-500">
                            Showing <span class="text-slate-900 font-bold">{{ page.props.sessions.from }}</span> to 
                            <span class="text-slate-900 font-bold">{{ page.props.sessions.to }}</span> of 
                            <span class="text-slate-900 font-bold">{{ page.props.sessions.total }}</span> sessions
                        </div>
                        
                        <nav class="flex items-center gap-1">
                            <template v-for="(link, key) in filteredLinks" :key="key">
                                <div v-if="link.label === '...'" class="px-3 text-slate-400">
                                    <MoreHorizontal class="h-4 w-4"/>
                                </div>
                                <button v-else
                                    :disabled="!link.url || link.active"
                                    @click="paginate(link.url)"
                                    class="h-10 min-w-[40px] rounded-lg px-3 text-sm font-bold transition-all border shadow-sm"
                                    :class="link.active 
                                        ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-200' 
                                        : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-500 hover:text-emerald-600'"
                                >
                                    <ChevronLeft v-if="link.label.includes('Previous')" class="h-4 w-4 mx-auto" />
                                    <ChevronRight v-else-if="link.label.includes('Next')" class="h-4 w-4 mx-auto" />
                                    <span v-else>{{ link.label }}</span>
                                </button>
                            </template>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Modals -->
            <SessionModal :is-open="isModalOpen" :session="editingSession" @close="isModalOpen = false" />
            <DeleteModal :is-open="isDeleteDialogOpen" :session="deletingSession" @close="isDeleteDialogOpen = false" :session-id="deletingSession?.id" />
            <ViewModal :is-open="isViewModalOpen" :session="viewingSession" @close="isViewModalOpen = false" />
        </main>
    </div>
</template>