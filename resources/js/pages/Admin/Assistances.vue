<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import AssistanceModal from '@/components/ModalAssistance/NewDialog.vue';
import DeleteModal from '@/components/ModalAssistance/Delete.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    Edit,
    Heart,
    GraduationCap,
    Scale,
    Plus,
    Search,
    Trash2,
    X,
    Users,
    ClipboardList,
    ChevronLeft,
    ChevronRight,
    MoreHorizontal
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Assistance {
    id: number;
    full_name: string;
    barangay: string;
    school: string | null;
    type: string;
    created_at: string;
}

interface PaginatedAssistances {
    data: Assistance[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}

const page = usePage<{
    assistances: PaginatedAssistances;
    filters: { search?: string; type?: string };
    types: string[];
    totalAssistances: number;
    medicalCount: number;
    legalCount: number;
    scholarCount: number;
}>();

// STATE
const search = ref(page.props.filters?.search || '');
const type = ref(page.props.filters?.type || '');

// MODAL STATES
const isModalOpen = ref(false);
const isDeleteDialogOpen = ref(false);
const editingAssistance = ref<Assistance | null>(null);
const deletingAssistance = ref<Assistance | null>(null);

// COMPUTED
const assistanceList = computed(() => page.props.assistances?.data || []);
const typesList = computed(() => page.props.types || []);

/**
 * PAGINATION LOGIC: Handles 100+ pages by showing ellipsis
 */
const filteredLinks = computed(() => {
    const links = page.props.assistances.links;
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

// NAVIGATION
const runNavigation = (pageNumber: any = 1) => {
    router.get(
        '/admin-assistances',
        {
            search: search.value,
            type: type.value,
            page: pageNumber
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['assistances', 'filters'],
        }
    );
};

const handleEnter = () => runNavigation(1);
const applyFilters = () => runNavigation(1);
const clearFilters = () => {
    search.value = '';
    type.value = '';
    router.get('/admin-assistances', {}, { replace: true });
};

watch(type, () => applyFilters());

const paginate = (url: string | null) => {
    if (!url) return;
    const urlObj = new URL(url, window.location.origin);
    const pageParam = urlObj.searchParams.get('page');
    runNavigation(pageParam);
};

// MODAL HANDLERS
const openModal = (item: Assistance | null = null) => {
    editingAssistance.value = item;
    isModalOpen.value = true;
};

const openDeleteDialog = (item: Assistance) => {
    deletingAssistance.value = item;
    isDeleteDialogOpen.value = true;
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    });
};
</script>

<template>
    <Head title="Public Assistance Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-10 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Public Assistance Registry</h1>
                        <p class="mt-1 text-sm text-slate-600">Track and manage beneficiaries for medical, legal, and educational aid.</p>
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
                                    placeholder="Search name, barangay or school..."
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <div class="relative w-full md:w-60">
                                <select v-model="type"
                                    @change="applyFilters"
                                    class="w-full rounded-lg border border-slate-300 
                                            px-4 pr-10 py-2.5 outline-none
                                            focus:ring-2 focus:ring-emerald-500
                                            appearance-none bg-white">
                                    <option value="">All Assistance Types</option>
                                    <option v-for="t in typesList" :key="t" :value="t" class="capitalize">{{ t }}</option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <button v-if="search || type"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                                <X class="h-4 w-4"/> Clear
                            </button>
                        </div>

                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl">
                            <Plus class="h-5 w-5"/> Record Assistance
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Total Beneficiaries</p><p class="text-3xl font-bold">{{ page.props.totalAssistances }}</p></div>
                    <Users class="h-8 w-8 text-emerald-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-rose-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Medical Aid</p><p class="text-3xl font-bold">{{ page.props.medicalCount }}</p></div>
                    <Heart class="h-8 w-8 text-rose-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-amber-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Legal Aid</p><p class="text-3xl font-bold">{{ page.props.legalCount }}</p></div>
                    <Scale class="h-8 w-8 text-amber-500 opacity-60"/>
                </div>
                <div class="rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg flex justify-between items-center">
                    <div><p class="text-sm font-medium text-slate-500">Scholars</p><p class="text-3xl font-bold">{{ page.props.scholarCount }}</p></div>
                    <GraduationCap class="h-8 w-8 text-sky-500 opacity-60"/>
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                    <div v-if="assistanceList.length === 0" class="py-16 text-center">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50">
                            <ClipboardList class="h-7 w-7 text-emerald-500"/>
                        </div>
                        <p class="text-lg font-semibold text-slate-700">No records found</p>
                        <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or add a new beneficiary.</p>
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Beneficiary</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Barangay</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">School/Details</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="item in assistanceList" :key="item.id" class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-900">{{ item.full_name }}</div>
                                    <div class="text-xs text-slate-400">#{{ item.id }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ item.barangay }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ item.school || '—' }}</td>
                                <td class="px-6 py-4">
                                    <span :class="{
                                        'bg-rose-50 text-rose-600 border-rose-100': item.type === 'medical',
                                        'bg-amber-50 text-amber-600 border-amber-100': item.type === 'legal',
                                        'bg-sky-50 text-sky-600 border-sky-100': item.type === 'scholar',
                                    }" class="rounded-full border px-2.5 py-0.5 text-[11px] font-bold uppercase">
                                        {{ item.type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(item.created_at) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="openModal(item)" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors"><Edit class="h-4 w-4"/></button>
                                        <button @click="openDeleteDialog(item)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"><Trash2 class="h-4 w-4"/></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="page.props.assistances.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ page.props.assistances.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ page.props.assistances.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ page.props.assistances.total }}</span>
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

            <AssistanceModal 
                v-model:isOpen="isModalOpen" 
                :assistance="editingAssistance" 
                @close="isModalOpen = false"
            />
            <DeleteModal 
                :is-open="isDeleteDialogOpen" 
                :assistance="deletingAssistance" 
                @close="isDeleteDialogOpen = false"
            />
        </main>
    </div>
</template>