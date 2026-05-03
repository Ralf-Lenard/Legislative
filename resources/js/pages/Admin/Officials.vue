<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import OfficialModal from '@/components/ModalOfficials/OfficialDialog.vue';
import DeleteModal from '@/components/ModalOfficials/DeleteDialog.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';

import { Eye, Edit, Plus, Search, Trash2, X, User, Users, Image, Zap, Briefcase,
 ChevronRight,
    ChevronLeft,
    MoreHorizontal
 } from 'lucide-vue-next';
import ViewOfficialModal from '@/components/ModalOfficials/ViewOfficialDialog.vue';

// Interfaces matching your controller's mapped data
interface Committee {
    id: number;
    name: string;
    focus?: string;
    pivot?: {
        role: string;
    };
}

interface Official {
    id: number;
    name: string;
    position: string;
    type: 'official' | 'employee';
    division: string | null;
    main_committee: string | null;
    image: string | null;
    bio: string | null;
    committees: Committee[];
    created_at: string;
}

interface PaginatedOfficials {
    data: Official[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}


// Accessing Inertia props
const page = usePage<{
    officials: PaginatedOfficials;
    filters: { search?: string; committee?: string; type?: string; division?: string };
    committeesList: Committee[];
    divisionOptions: string[];
    flash?: { success?: string };
}>();

const filteredLinks = computed(() => {
    const links = page.props.officials.links;
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

const officials = ref<Official[]>([...(page.props.officials?.data || [])]);
const search = ref(page.props.filters?.search || '');
const committeeFilter = ref(page.props.filters?.committee || '');
const typeFilter = ref(page.props.filters?.type || '');
const divisionFilter = ref(page.props.filters?.division || '');

// Modal states
const isModalOpen = ref(false);
const editingOfficial = ref<Official | null>(null);
const isDeleteDialogOpen = ref(false);
const deletingOfficial = ref<Official | null>(null);
const isViewModalOpen = ref(false);
const viewingOfficial = ref<Official | null>(null);

// Computed stats based on current paginated data
const uniqueCommittees = computed(() => page.props.committeesList || []);
const employeesCount = computed(() => officials.value.filter(o => o.type === 'employee').length);

// --- Filter and Pagination Logic ---

const paginate = (url: string) => {
    if (url) {
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }
};

const applyFilters = () => {
    // If switching type, clear irrelevant filters
    if (typeFilter.value === 'official') divisionFilter.value = '';
    if (typeFilter.value === 'employee') committeeFilter.value = '';

    router.get(
        '/admin-organizational-chart', 
        { 
            search: search.value, 
            committee: committeeFilter.value, 
            type: typeFilter.value,
            division: divisionFilter.value,
            page: 1 
        },
        { preserveState: true, replace: true },
    );
};

const clearFilters = () => {
    search.value = '';
    committeeFilter.value = '';
    typeFilter.value = '';
    divisionFilter.value = '';

    // Navigate to the base URL without any query strings
    router.get('/admin-organizational-chart', {}, { preserveState: false, replace: true });
};


// --- Modal & Utility Functions ---

const openModal = (official: Official | null = null) => {
    editingOfficial.value = official;
    isModalOpen.value = true;
};

const handleModalSubmit = () => {
    isModalOpen.value = false;
    // router.reload() is useful, but applyFilters() ensures state is synced
    router.reload({ only: ['officials'] });
};

const openDeleteDialog = (official: Official) => {
    deletingOfficial.value = official;
    isDeleteDialogOpen.value = true;
};

const openViewModal = (official: Official) => {
    viewingOfficial.value = official;
    isViewModalOpen.value = true;
};

const getCommitteeRole = (official: Official): string => {
    const mainName = official.main_committee;
    const found = official.committees?.find(c => c.name === mainName);
    return found?.pivot?.role || 'Member';
}

// Detect the Vice Mayor specifically
const viceMayor = computed(() => 
    officials.value.find(o => o.position === 'Vice Mayor')
);

// SB Members are Officials who are NOT the Vice Mayor
const sbMembersCount = computed(() => 
    officials.value.filter(o => o.type === 'official' && o.position !== 'Vice Mayor').length
);

// Total Legislative Body (Vice Mayor + SB Members)
const totalOfficials = computed(() => 
    officials.value.filter(o => o.type === 'official').length
);

// Watch for external prop changes (Inertia navigation)
watch(() => page.props.officials?.data, (newData) => {
    if (newData) officials.value = [...newData];
});

onMounted(() => {
    window.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            isModalOpen.value = false;
            isDeleteDialogOpen.value = false;
            isViewModalOpen.value = false;
        }
    });
});
</script>

<template>
    <Head title="Organizational Chart" />
    <div class="flex h-screen bg-slate-50 text-slate-900">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <!-- Header Section -->
            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Organizational Chart</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage Sangguniang Bayan Members and Support Staff.</p>
                    </div>
                </div>

                <!-- Search and Filters Bar -->
                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex flex-1 flex-wrap gap-3 items-center">
                            
                            <!-- Search -->
                            <div class="relative w-full max-w-xs">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
                                <input
                                    v-model="search"
                                    @keyup.enter="applyFilters"
                                    type="text"
                                    placeholder="Search name or position..."
                                    class="w-full h-11 rounded-xl border border-slate-300 pl-10 pr-4 bg-white text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition shadow-sm"
                                />
                            </div>

                            <!-- Personnel Type -->
                            <div class="relative w-full md:w-48">
                                <select
                                    v-model="typeFilter"
                                    @change="applyFilters"
                                    class="w-full h-11 rounded-xl border border-slate-300 px-4 pr-10 bg-white text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none transition shadow-sm"
                                >
                                    <option value="">All Personnel</option>
                                    <option value="official">Officials</option>
                                    <option value="employee">Employees</option>
                                </select>
                                <ChevronDown class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none" />
                            </div>

                            <!-- Dynamic Filter (Committee or Division) -->
                            <div v-if="typeFilter !== 'employee'" class="relative w-full md:w-48">
                                <select
                                    v-model="committeeFilter"
                                    @change="applyFilters"
                                    class="w-full h-11 rounded-xl border border-slate-300 px-4 pr-10 bg-white text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none transition shadow-sm"
                                >
                                    <option value="">All Committees</option>
                                    <option v-for="c in uniqueCommittees" :key="c.id" :value="c.name">{{ c.name }}</option>
                                </select>
                                <ChevronDown class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none" />
                            </div>

                            <div v-if="typeFilter === 'employee'" class="relative w-full md:w-48">
                                <select
                                    v-model="divisionFilter"
                                    @change="applyFilters"
                                    class="w-full h-11 rounded-xl border border-slate-300 px-4 pr-10 bg-white text-slate-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none transition shadow-sm"
                                >
                                    <option value="">All Divisions</option>
                                    <option v-for="opt in page.props.divisionOptions" :key="opt" :value="opt">{{ opt }}</option>
                                </select>
                                <ChevronDown class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500 pointer-events-none" />
                            </div>

                            <!-- Clear Button -->
                            <button
                                v-if="search || committeeFilter || typeFilter || divisionFilter"
                                @click="clearFilters"
                                class="flex items-center gap-2 h-11 rounded-xl border border-slate-300 px-4 font-medium text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm"
                            >
                                <X class="h-4 w-4" /> Clear
                            </button>
                        </div>

                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 font-bold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all active:scale-95">
                            <Plus class="h-5 w-5"/> Add Personnel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Vice Mayor Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 p-5 shadow-lg transition-all bg-white"
                    :class="viceMayor ? 'border-emerald-600' : 'border-slate-300 bg-slate-50'">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Executive</p>
                        <p class="text-sm font-medium text-slate-500">Vice Mayor</p>
                        <p class="mt-1 text-lg font-bold" :class="viceMayor ? 'text-slate-900' : 'text-slate-400 italic'">
                            {{ viceMayor ? viceMayor.name : 'Position Vacant' }}
                        </p>
                    </div>
                    <div class="rounded-full p-2" :class="viceMayor ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-200 text-slate-400'">
                        <User class="h-6 w-6"/>
                    </div>
                </div>

                <!-- SB Members Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 border-sky-600 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-sky-600">Legislative</p>
                        <p class="text-sm font-medium text-slate-500">SB Members</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">
                            {{ sbMembersCount }} <span class="text-sm font-normal text-slate-400">/ 8 Seats</span>
                        </p>
                    </div>
                    <div class="rounded-full bg-sky-50 text-sky-600 p-2">
                        <Users class="h-6 w-6"/>
                    </div>
                </div>

                <!-- Staff Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Support</p>
                        <p class="text-sm font-medium text-slate-500">Office Personnel</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ employeesCount }}</p>
                    </div>
                    <div class="rounded-full bg-indigo-50 text-indigo-500 p-2">
                        <Briefcase class="h-6 w-6"/>
                    </div>
                </div>

                <!-- Committee Card -->
                <div class="flex items-center justify-between rounded-xl border-l-4 border-amber-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600">Governance</p>
                        <p class="text-sm font-medium text-slate-500">Standing Committees</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ uniqueCommittees.length }}</p>
                    </div>
                    <div class="rounded-full bg-amber-50 text-amber-500 p-2">
                        <Zap class="h-6 w-6"/>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-8">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                    <div v-if="!officials.length" class="py-20 text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                            <UserX class="h-8 w-8"/>
                        </div>
                        <p class="text-lg font-bold text-slate-700">No personnel found</p>
                        <p class="text-sm text-slate-500">Try adjusting your search or filters.</p>
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Photo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Name & Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Position / Division</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-600">Committee Assignment</th>
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <tr v-for="official in officials" :key="official.id" class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-4">
                                    <div class="relative h-12 w-12 overflow-hidden rounded-full border-2 border-slate-100 shadow-sm">
                                        <img v-if="official.image" :src="`/storage/${official.image}`" class="h-full w-full object-cover" />
                                        <div v-else class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
                                            <User class="h-6 w-6" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-900">{{ official.name }}</div>
                                    <span :class="official.type === 'official' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-blue-600 bg-blue-50 border-blue-100'" 
                                          class="inline-block rounded-md border px-2 py-0.5 text-[10px] font-black uppercase tracking-tighter">
                                        {{ official.type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-700">{{ official.position }}</div>
                                    <div v-if="official.division" class="text-xs text-slate-500 font-medium italic">{{ official.division }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="official.type === 'official'" class="max-w-[200px]">
                                        <div class="truncate text-sm font-bold text-slate-800">{{ official.main_committee || 'General Member' }}</div>
                                        <div class="text-[11px] font-medium text-slate-500 uppercase">{{ getCommitteeRole(official) }}</div>
                                    </div>
                                    <div v-else class="text-slate-300">—</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openViewModal(official)" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="View Profile">
                                            <Eye class="h-4.5 w-4.5"/>
                                        </button>
                                        <button @click="openModal(official)" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-sky-600 hover:text-white transition-all shadow-sm" title="Edit Record">
                                            <Edit class="h-4.5 w-4.5"/>
                                        </button>
                                        <button @click="openDeleteDialog(official)" class="h-9 w-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Delete">
                                            <Trash2 class="h-4.5 w-4.5"/>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="page.props.officials.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-8 py-5">
                        <div class="text-sm font-medium text-slate-500">
                            Showing <span class="text-slate-900 font-bold">{{ page.props.officials.from }}</span> to 
                            <span class="text-slate-900 font-bold">{{ page.props.officials.to }}</span> of 
                            <span class="text-slate-900 font-bold">{{ page.props.officials.total }}</span> personnel
                        </div>
                        <nav class="flex items-center gap-1">
                            <template v-for="(link, key) in filteredLinks" :key="key">
                                <div v-if="link.label === '...'" class="px-3 text-slate-400"><MoreHorizontal class="h-4 w-4"/></div>
                                <button v-else
                                    :disabled="!link.url || link.active"
                                    @click="paginate(link.url)"
                                    class="h-10 min-w-[40px] rounded-lg px-3 text-sm font-bold transition-all border shadow-sm"
                                    :class="link.active ? 'bg-emerald-600 border-emerald-600 text-white shadow-emerald-200' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-500 hover:text-emerald-600'"
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
            <OfficialModal :is-open="isModalOpen" :official="editingOfficial" @close="isModalOpen = false" @submitted="handleModalSubmit" />
            <DeleteModal :is-open="isDeleteDialogOpen" :official="deletingOfficial" @close="isDeleteDialogOpen = false" :official-id="deletingOfficial?.id" />
            <ViewOfficialModal :is-open="isViewModalOpen" :official="viewingOfficial" @close="isViewModalOpen = false" />
        </main>
    </div>
</template>