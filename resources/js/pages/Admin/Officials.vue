<script setup lang="ts">
    import AppSidebar from '@/components/AppSidebar.vue';
    import OfficialModal from '@/components/ModalOfficials/OfficialDialog.vue';
    import DeleteModal from '@/components/ModalOfficials/DeleteDialog.vue';
    import FlashMessage from '@/components/Admin/FlashMessage.vue';
    import { Head, router, usePage } from '@inertiajs/vue3';
    import { computed, ref, watch, onMounted, nextTick } from 'vue';

    import { Eye, Edit, Plus, Search, Trash2, X, User, Users, Image, Zap } from 'lucide-vue-next';
    import ViewOfficialModal from '@/components/ModalOfficials/ViewOfficialDialog.vue'; // New Import
    
    interface Committee {
        id: number;
        name: string;
        pivot?: {
            role: string;
        };
    }
    
    interface Official {
        id: number;
        name: string;
        position: string;
        main_committee: string | null;
        image: string | null;
        bio: string | null;
        committees: Committee[];
        created_at: string;
    }
    
    // NOTE: We assume the backend now returns paginated data, similar to the Ordinance component
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
    
    // NOTE: We assume the backend passes filters and a list of unique committees
    const { props } = usePage<{
        officials: PaginatedOfficials;
        filters: { search?: string; committee?: string };
        committeesList: Committee[];
        flash?: { success?: string };
    }>();
    
    
    const officials = ref<Official[]>([...(props.officials?.data || [])]);
    const search = ref(props.filters?.search || '');
    const committeeFilter = ref(props.filters?.committee || ''); // New filter for committee
    const flashMessage = ref<{ type: 'success' | 'error'; text: string } | null>(null);
    
    // Modal states
    const isModalOpen = ref(false);
    const editingOfficial = ref<Official | null>(null);
    const isDeleteDialogOpen = ref(false);
    const deletingOfficial = ref<Official | null>(null);
    
    // Computed stats and pagination data
    const paginationMeta = computed(() => props.officials?.meta || null);
    const paginationLinks = computed(() => props.officials?.links || []);
    const uniqueCommittees = computed(() => props.committeesList || []);
    
    const officialsWithImageCount = computed(() => officials.value.filter(o => o.image).length);
    const officialsWithoutCommitteeCount = computed(() => officials.value.filter(o => !o.main_committee).length);
    
    
    // --- Filter and Pagination Logic (Copied from Ordinances.vue) ---
    
    const paginate = (url: string) => {
        if (url) {
            // NOTE: Preserve scroll to stay at the top of the table/page on navigation
            router.get(url, {}, { preserveScroll: true });
        }
    };
    
    const applyFilters = () => {
        // Reset to the first page on filter change
        router.get(
            '/admin-officials', // Adjust this to your actual route
            { search: search.value, committee: committeeFilter.value, page: 1 },
            { preserveState: false, replace: true },
        );
    };
    
    const clearFilters = () => {
        search.value = '';
        committeeFilter.value = '';
        router.get(
            '/admin-officials', // Adjust this to your actual route
            { search: '', committee: '', page: 1 },
            { preserveState: false, replace: true },
        );
    };
    
    
    // --- Modal & Utility Functions ---
    
    const openModal = (official: Official | null = null) => {
        editingOfficial.value = official;
        isModalOpen.value = true;
    };
    
    const handleModalSubmit = () => {
        isModalOpen.value = false;
        router.reload(); // Reload to fetch fresh data after create/update
    };
    
    const openDeleteDialog = (official: Official) => {
        deletingOfficial.value = official;
        isDeleteDialogOpen.value = true;
    };
    
    // Committees helper
    const formatCommittees = (committees: Committee[]): string => {
        if (!committees.length) return 'No committees';
        // Show only the names, roles might make the table too wide
        return committees.map(c => c.name).join(', ');
    };
    
    const getCommitteeRole = (official: Official): string => {
        const mainCommittee = official.committees.find(c => c.name === official.main_committee);
        return mainCommittee?.pivot?.role || 'Member';
    }

    const isViewModalOpen = ref(false);
    const viewingOfficial = ref<Official | null>(null);

    // 3. Add the open function
    const openViewModal = (official: Official) => {
        viewingOfficial.value = official;
        isViewModalOpen.value = true;
    };
    
    
    // --- Watchers & Lifecycle ---
    
    // Update local data when props change (after Inertia visit)
    watch(
        () => props.officials?.data,
        (newData) => {
            if (newData) officials.value = [...newData];
        }
    );
    
    
    // Handle ESC key to close dialogs
    onMounted(() => {
        window.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                isModalOpen.value = false;
                isDeleteDialogOpen.value = false;
            }
        });
    });
    </script>
    
    <template>
        <Head title="Officials Management" />
        <div class="flex h-screen bg-slate-50">
            <AppSidebar />
            <main class="relative flex-1 overflow-auto">
           
            <FlashMessage />

    
                <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                    <div class="flex items-center justify-between px-8 py-6">
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900">Sangguniang Bayan Members</h1>
                            <p class="mt-1 text-sm text-slate-600">Manage current Members, positions, and committees.</p>
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
                                        placeholder="Search name, position, or committee..."
                                        class="w-full rounded-xl border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"/>
                                </div>
    
                                <div class="w-full md:w-56">
                                    <select v-model="committeeFilter"
                                        @change="applyFilters"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                        <option value="">All Committees</option>
                                        <option v-for="c in uniqueCommittees" :key="c.id" :value="c.name">{{ c.name }}</option>
                                    </select>
                                </div>
    
                                <button v-if="search || committeeFilter"
                                    @click="clearFilters"
                                    class="flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                                    <X class="h-4 w-4"/>
                                    Clear
                                </button>
                            </div>
    
                            <button @click="openModal()"
                                class="flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl">
                                <Plus class="h-5 w-5"/>
                                New Official
                            </button>
                        </div>
    
                        <div v-if="search || committeeFilter" class="flex flex-wrap gap-2">
                            <span v-if="search" class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-900">
                                Search: {{ search }}
                                <button @click="search = ''; applyFilters()" class="hover:text-emerald-700">
                                    <X class="h-3 w-3"/>
                                </button>
                            </span>
                            <span v-if="committeeFilter" class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-900">
                                Committee: {{ committeeFilter }}
                                <button @click="committeeFilter = ''; applyFilters()" class="hover:text-blue-700">
                                    <X class="h-3 w-3"/>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
    
                <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="flex items-center justify-between rounded-xl border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Officials</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ paginationMeta?.total || officials.length }}</p>
                        </div>
                        <User class="h-8 w-8 text-emerald-500 opacity-60"/>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border-l-4 border-sky-500 bg-white p-5 shadow-lg">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Committees</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ uniqueCommittees.length }}</p>
                        </div>
                        <Users class="h-8 w-8 text-sky-500 opacity-60"/>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Profiles With Image</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ officialsWithImageCount }}</p>
                        </div>
                        <Image class="h-8 w-8 text-indigo-500 opacity-60"/>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border-l-4 border-yellow-500 bg-white p-5 shadow-lg">
                        <div>
                            <p class="text-sm font-medium text-slate-500">No Main Assignment</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ officialsWithoutCommitteeCount }}</p>
                        </div>
                        <Zap class="h-8 w-8 text-yellow-500 opacity-60"/>
                    </div>
                </div>
    
                <div class="p-8">
                    <div class="overflow-x-auto rounded-xl border border-slate-100 bg-white shadow-lg">
                        <div v-if="!officials || officials.length === 0" class="py-16">
                            <div class="flex flex-col items-center">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50">
                                    <User class="h-7 w-7 text-emerald-500"/>
                                </div>
                                <p class="text-lg font-semibold text-slate-700">No officials found</p>
                                <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or add a new official.</p>
                            </div>
                        </div>
    
                        <table v-else class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-100/80">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Photo</th>
                                    <th class="min-w-[180px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Name</th>
                                    <th class="min-w-[180px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Position</th>
                                    <th class="min-w-[180px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Main Committee</th>
                                    <th class="min-w-[120px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Main Role</th>
                                    <th class="min-w-[280px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Other Committees</th>
                                    <th class="w-[100px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr v-for="(official, index) in officials" :key="official.id" class="transition-colors hover:bg-emerald-50/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-600">
                                        {{ (paginationMeta?.from || 1) + index }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <img 
                                            v-if="official.image" 
                                            :src="`/storage/${official.image}`" 
                                            alt="Official Photo" 
                                            class="h-10 w-10 rounded-full object-cover border border-slate-200"
                                        />
                                        <span v-else class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-slate-200 text-slate-400 text-xs">
                                            <User class="h-5 w-5" />
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-slate-900">{{ official.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ official.position }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ official.main_committee || '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <span v-if="official.main_committee" class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-medium text-sky-800">
                                            {{ getCommitteeRole(official) }}
                                        </span>
                                        <span v-else class="text-xs text-slate-400">—</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <div class="line-clamp-2 max-w-xs">{{ formatCommittees(official.committees) }}</div>
                                    </td>
                                    
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="openViewModal(official)" 
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 shadow-sm transition-all hover:bg-emerald-100" 
                                                title="View Profile">
                                                <Eye class="h-4 w-4"/>
                                            </button>
                                            <button @click="openModal(official)" class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 shadow-sm transition-all hover:bg-sky-100" title="Edit Official">
                                                <Edit class="h-4 w-4"/>
                                            </button>
                                            <button @click="openDeleteDialog(official)" class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-600 shadow-sm transition-all hover:bg-red-100" title="Delete Official">
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
                            <nav class="isolate inline-flex -space-x-px rounded-xl shadow-sm" aria-label="Pagination">
                                <component v-for="(link, key) in paginationLinks"
                                    :key="key"
                                    :is="link.url ? 'button' : 'span'"
                                    @click="link.url ? paginate(link.url) : null"
                                    :disabled="!link.url"
                                    :class="[
                                        'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset focus:z-20 transition-all',
                                        key === 0 ? 'rounded-l-xl' : '',
                                        key === paginationLinks.length - 1 ? 'rounded-r-xl' : '',
                                        link.active ? 'bg-emerald-600 text-white ring-emerald-600' : link.url ? 'text-slate-900 hover:bg-slate-100' : 'cursor-not-allowed text-slate-400 bg-slate-100'
                                    ]"
                                    v-html="link.label"/>
                            </nav>
                        </div>
                    </div>
                </div>
    
                <OfficialModal 
                    :is-open="isModalOpen" 
                    :official="editingOfficial" 
                    @close="isModalOpen = false"
                    @submitted="handleModalSubmit" 
                />
                <DeleteModal 
                    :is-open="isDeleteDialogOpen" 
                    :official="deletingOfficial" 
                    @close="isDeleteDialogOpen = false"
                    :official-id="deletingOfficial?.id"
                />
                <ViewOfficialModal 
                    :is-open="isViewModalOpen" 
                    :official="viewingOfficial" 
                    @close="isViewModalOpen = false"
                />
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
        
        /* Utility for two line clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
        </style>