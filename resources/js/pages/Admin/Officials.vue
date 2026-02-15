<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import OfficialModal from '@/components/ModalOfficials/OfficialDialog.vue';
import DeleteModal from '@/components/ModalOfficials/DeleteDialog.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';

import { Eye, Edit, Plus, Search, Trash2, X, User, Users, Image, Zap, Briefcase } from 'lucide-vue-next';
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
    links: { url: string | null; label: string; active: boolean }[];
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        per_page: number;
        to: number;
        total: number;
    };
}

// Accessing Inertia props
const page = usePage<{
    officials: PaginatedOfficials;
    filters: { search?: string; committee?: string; type?: string; division?: string };
    committeesList: Committee[];
    divisionOptions: string[];
    flash?: { success?: string };
}>();

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
const paginationMeta = computed(() => page.props.officials?.meta || null);
const paginationLinks = computed(() => page.props.officials?.links || []);
const uniqueCommittees = computed(() => page.props.committeesList || []);

const officialsWithImageCount = computed(() => officials.value.filter(o => o.image).length);
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
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Organizational Chart</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage Sangguniang Bayan Members and Support Staff.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex flex-1 flex-wrap gap-3 items-center">
                            <div class="relative w-full max-w-xs">
                                <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-400"/>
                                <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search name or position..."
                                    class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 focus:ring-2 focus:ring-emerald-500 focus:outline-none"/>
                            </div>

                            <select v-model="typeFilter" @change="applyFilters" 
                                class="rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
                                <option value="">All Personnel</option>
                                <option value="official">Officials</option>
                                <option value="employee">Employees</option>
                            </select>

                            <select v-if="typeFilter !== 'employee'" v-model="committeeFilter" @change="applyFilters"
                                class="rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
                                <option value="">All Committees</option>
                                <option v-for="c in uniqueCommittees" :key="c.id" :value="c.name">{{ c.name }}</option>
                            </select>

                            <select v-if="typeFilter === 'employee'" v-model="divisionFilter" @change="applyFilters"
                                class="rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500">
                                <option value="">All Divisions</option>
                                <option v-for="opt in page.props.divisionOptions" :key="opt" :value="opt">{{ opt }}</option>
                            </select>

                            <button v-if="search || committeeFilter || typeFilter || divisionFilter" @click="clearFilters"
                                class="flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 font-medium text-slate-700 hover:bg-slate-50">
                                <X class="h-4 w-4"/> Clear
                            </button>
                        </div>

                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700">
                            <Plus class="h-5 w-5"/> Add Official/Employee
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
    <div 
        class="flex items-center justify-between rounded-xl border-l-4 p-5 shadow-lg transition-all bg-white"
        :class="viceMayor ? 'border-emerald-600' : 'border-slate-300 bg-slate-50'"
    >
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Official</p>
            <p class="text-sm font-medium text-slate-500">Vice Mayor</p>
            <p class="mt-1 text-lg font-bold" :class="viceMayor ? 'text-slate-900' : 'text-slate-400 italic'">
                {{ viceMayor ? viceMayor.name : 'Position Vacant' }}
            </p>
        </div>
        <div class="rounded-full p-2" :class="viceMayor ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-200 text-slate-400'">
            <User class="h-6 w-6"/>
        </div>
    </div>

    <div class="flex items-center justify-between rounded-xl border-l-4 border-sky-600 bg-white p-5 shadow-lg">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-sky-600">Official</p>
            <p class="text-sm font-medium text-slate-500">SB Members</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">
                {{ sbMembersCount }} <span class="text-sm font-normal text-slate-400">/ 8 Seats</span>
            </p>
        </div>
        <div class="rounded-full bg-sky-50 text-sky-600 p-2">
            <Users class="h-6 w-6"/>
        </div>
    </div>

    <div class="flex items-center justify-between rounded-xl border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Staff</p>
            <p class="text-sm font-medium text-slate-500">Office Personnel</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ employeesCount }}</p>
        </div>
        <div class="rounded-full bg-indigo-50 text-indigo-500 p-2">
            <Briefcase class="h-6 w-6"/>
        </div>
    </div>

    <div class="flex items-center justify-between rounded-xl border-l-4 border-yellow-500 bg-white p-5 shadow-lg">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-yellow-600">Governance</p>
            <p class="text-sm font-medium text-slate-500">Standing Committees</p>
            <p class="mt-1 text-3xl font-bold text-slate-900">{{ uniqueCommittees.length }}</p>
        </div>
        <div class="rounded-full bg-yellow-50 text-yellow-500 p-2">
            <Zap class="h-6 w-6"/>
        </div>
    </div>
</div>

            <div class="p-8">
                <div class="overflow-x-auto rounded-xl border border-slate-100 bg-white shadow-lg">
                    <div v-if="!officials.length" class="py-16 text-center">
                        <User class="mx-auto h-12 w-12 text-slate-300 mb-4"/>
                        <p class="text-lg font-semibold text-slate-700">No records found</p>
                    </div>

                    <table v-else class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">#</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Photo</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Name & Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Position / Division</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Assignment</th>
                                <th class="w-[120px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="(official, index) in officials" :key="official.id" class="transition-colors hover:bg-emerald-50/50">
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ (paginationMeta?.from || 1) + index }}
                                </td>
                                <td class="px-6 py-4">
                                    <img v-if="official.image" :src="`/storage/${official.image}`" class="h-10 w-10 rounded-full object-cover border border-slate-200" />
                                    <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-400">
                                        <User class="h-5 w-5" />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ official.name }}</div>
                                    <div class="text-[10px] font-bold uppercase tracking-tight" :class="official.type === 'official' ? 'text-emerald-600' : 'text-blue-600'">
                                        {{ official.type }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-700">{{ official.position }}</div>
                                    <div v-if="official.division" class="text-xs text-slate-500 italic">{{ official.division }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <div v-if="official.type === 'official'">
                                        <span class="font-medium">{{ official.main_committee || 'No Main Committee' }}</span>
                                        <div class="text-xs text-slate-500">{{ getCommitteeRole(official) }}</div>
                                    </div>
                                    <div v-else class="text-slate-400">—</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openViewModal(official)" class="h-8 w-8 flex items-center justify-center rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100" title="View"><Eye class="h-4 w-4"/></button>
                                        <button @click="openModal(official)" class="h-8 w-8 flex items-center justify-center rounded-full bg-sky-50 text-sky-600 hover:bg-sky-100" title="Edit"><Edit class="h-4 w-4"/></button>
                                        <button @click="openDeleteDialog(official)" class="h-8 w-8 flex items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-100" title="Delete"><Trash2 class="h-4 w-4"/></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="paginationLinks.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4">
                        <div class="text-sm text-slate-600">
                            Showing <span class="font-semibold">{{ paginationMeta?.from }}</span> to <span class="font-semibold">{{ paginationMeta?.to }}</span> of <span class="font-semibold">{{ paginationMeta?.total }}</span>
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-xl shadow-sm">
                            <button v-for="(link, key) in paginationLinks" :key="key"
                                @click="link.url ? paginate(link.url) : null"
                                :disabled="!link.url"
                                :class="['px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-slate-300 transition-all', 
                                    key === 0 ? 'rounded-l-xl' : '', 
                                    key === paginationLinks.length - 1 ? 'rounded-r-xl' : '',
                                    link.active ? 'bg-emerald-600 text-white z-10' : link.url ? 'text-slate-900 hover:bg-slate-100' : 'text-slate-400 bg-slate-50 cursor-not-allowed']"
                                v-html="link.label" />
                        </nav>
                    </div>
                </div>
            </div>

            <OfficialModal :is-open="isModalOpen" :official="editingOfficial" @close="isModalOpen = false" @submitted="handleModalSubmit" />
            <DeleteModal :is-open="isDeleteDialogOpen" :official="deletingOfficial" @close="isDeleteDialogOpen = false" :official-id="deletingOfficial?.id" />
            <ViewOfficialModal :is-open="isViewModalOpen" :official="viewingOfficial" @close="isViewModalOpen = false" />
        </main>
    </div>
</template>