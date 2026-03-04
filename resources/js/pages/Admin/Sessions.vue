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
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Legislative Sessions</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage session numbers, titles, and legislative summaries.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                            <div class="relative max-w-md flex-1">
                                <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-400" />
                                <input
                                    v-model="search"
                                    @keydown.enter.prevent="handleEnter"
                                    type="text"
                                    placeholder="Search number, title, or summary..."
                                    class="w-full rounded-xl border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <div class="relative w-full md:w-48">
                                <select
                                    v-model="yearFilter"
                                    @change="applyFilters"
                                    class="w-full rounded-lg border border-slate-300 
                                            px-4 pr-10 py-2.5 outline-none
                                            focus:ring-2 focus:ring-emerald-500
                                            appearance-none bg-white"
                                >
                                    <option value="">All Years</option>
                                    <option v-for="year in yearsList" :key="year" :value="year">{{ year }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <button
                                v-if="search || yearFilter"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 font-medium text-slate-700 hover:bg-slate-50"
                            >
                                <X class="h-4 w-4" /> Clear
                            </button>
                        </div>

                        <button
                            @click="openModal()"
                            class="flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700"
                        >
                            <Plus class="h-5 w-5" /> New Session
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center justify-between rounded-xl border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Records</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ paginationMeta?.total || 0 }}</p>
                    </div>
                    <Layout class="h-8 w-8 text-emerald-500 opacity-60" />
                </div>
                <div class="flex items-center justify-between rounded-xl border-l-4 border-sky-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Regular Sessions</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ regularSessionsCount }}</p>
                    </div>
                    <Calendar class="h-8 w-8 text-sky-500 opacity-60" />
                </div>
                <div class="flex items-center justify-between rounded-xl border-l-4 border-purple-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Special Sessions</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ specialSessionsCount }}</p>
                    </div>
                    <Clock class="h-8 w-8 text-purple-500 opacity-60" />
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-x-auto rounded-xl border border-slate-100 bg-white shadow-lg">
                    <div v-if="!sessionsList.length" class="py-16 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
                            <FileText class="h-7 w-7 text-emerald-500" />
                        </div>
                        <p class="text-lg font-semibold text-slate-700">No sessions found</p>
                    </div>

                    <table v-else class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Number</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Session Title</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Type</th>
                                <th class="px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="session in sessionsList" :key="session.id" class="transition-colors hover:bg-emerald-50/50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ session.session_number }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                    <div class="max-w-xs truncate">{{ session.session_title }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ formatDate(session.date_of_session) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="session.session_type === 'Regular' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                                    >
                                        {{ session.session_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openViewModal(session)" class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 transition-all hover:bg-emerald-100" title="View Details">
                                            <Eye class="h-4 w-4" />
                                        </button>
                                        <button @click="openModal(session)" class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 transition-all hover:bg-sky-100">
                                            <Edit class="h-4 w-4" />
                                        </button>
                                        <button @click="openDeleteDialog(session)" class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-600 transition-all hover:bg-red-100">
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="page.props.sessions.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ page.props.sessions.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ page.props.sessions.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ page.props.sessions.total }}</span>
                        </div>

                        <nav class="inline-flex -space-x-px rounded-lg bg-white shadow-sm border border-slate-200" aria-label="Pagination">
                            <template v-for="(link, key) in filteredLinks" :key="key">
                                
                                <div v-if="link.label === '...'" 
                                     class="relative inline-flex items-center px-3 py-2 text-slate-400">
                                    <MoreHorizontal class="h-4 w-4" />
                                </div>

                                <button
                                    v-else
                                    :disabled="!link.url || link.active"
                                    @click="paginate(link.url)"
                                    class="relative inline-flex items-center justify-center min-w-[40px] h-10 px-3 text-sm font-semibold transition-all first:rounded-l-lg last:rounded-r-lg"
                                    :class="[
                                        link.active 
                                            ? 'z-10 bg-emerald-600 text-white' 
                                            : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-600',
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

            <SessionModal :is-open="isModalOpen" :session="editingSession" @close="isModalOpen = false" />
            <DeleteModal :is-open="isDeleteDialogOpen" :session="deletingSession" @close="isDeleteDialogOpen = false" :session-id="deletingSession?.id" />
            <ViewModal :is-open="isViewModalOpen" :session="viewingSession" @close="isViewModalOpen = false" />
        </main>
    </div>
</template>