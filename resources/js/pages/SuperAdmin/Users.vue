<script setup>
import { ref, computed } from 'vue';
import { router, Head, usePage } from '@inertiajs/vue3';
import { 
    Search, X, UserCircle, CheckCircle2, ShieldCheck, 
    UserPlus, Mail, Eye, ShieldAlert, Trash2, 
    CircleOff, Calendar, Fingerprint,
    ChevronRight, ChevronLeft, MoreHorizontal
} from 'lucide-vue-next';
import AppSidebar from '@/components/AppSidebar.vue';
import FlashMessage from '@/components/FlashMessage.vue';

const props = defineProps({
    users: Object, // Paginator object
    roles: Array,
    totalUsers: Number,
    activeUsersCount: Number,
    adminUsersCount: Number,
    newUsersThisMonth: Number,
    filters: Object,
});

// --- Pagination Logic ---
const filteredLinks = computed(() => {
    const links = props.users.links;
    if (links.length <= 10) return links;

    const total = links.length;
    const current = links.findIndex(l => l.active);
    const result = [];

    result.push(links[0]); // Previous

    for (let i = 1; i < total - 1; i++) {
        if (i === 1 || i === total - 2 || (i >= current - 1 && i <= current + 1)) {
            result.push(links[i]);
            continue;
        }
        if (result[result.length - 1].label !== '...') {
            result.push({ url: null, label: '...', active: false });
        }
    }

    result.push(links[total - 1]); // Next
    return result;
});

// --- State Management ---
const search = ref(props.filters?.search || '');
const role = ref(props.filters?.role || '');
const isModalOpen = ref(false);
const selectedUser = ref(null);
const isConfirmModalOpen = ref(false);
const confirmAction = ref(null);

const usersData = computed(() => props.users?.data || []);

// --- Methods ---
const applyFilters = () => {
    router.get('/super-admin-users', { 
        search: search.value, 
        role: role.value 
    }, {
        preserveState: true,
        replace: true
    });
};

const clearFilters = () => {
    search.value = '';
    role.value = '';
    applyFilters();
};

const paginate = (url) => {
    if (url) router.visit(url);
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric',
    });
};

// --- Action Handlers ---
const viewUserDetails = (user) => {
    selectedUser.value = user;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedUser.value = null;
};

const openConfirmModal = (title, message, type, onConfirm) => {
    confirmAction.value = { title, message, type, execute: onConfirm };
    isConfirmModalOpen.value = true;
};

const handleExecuteAction = () => {
    if (confirmAction.value?.execute) confirmAction.value.execute();
    isConfirmModalOpen.value = false;
};

const promoteToAdmin = (user) => {
    openConfirmModal('Promote User', `Promote ${user.name} to Admin?`, 'info', 
        () => router.post(`/super-admin/promote/${user.id}`));
};

const promoteToUser = (user) => {
    openConfirmModal('Demote Admin', `Remove admin privileges from ${user.name}?`, 'warning', 
        () => router.post(`/super-admin/demote/${user.id}`));
};

const toggleBanStatus = (user) => {
    const isBanning = user.status === 'active';
    openConfirmModal(
        isBanning ? 'Deactivate User' : 'Activate User',
        `Are you sure you want to ${isBanning ? 'deactivate' : 'activate'} ${user.name}?`,
        isBanning ? 'warning' : 'success',
        () => router.post(isBanning ? `/super-admin/ban-user/${user.id}` : `/super-admin/unban-user/${user.id}`)
    );
};

const deleteUser = (user) => {
    openConfirmModal('Delete User', `Permanently delete ${user.name}?`, 'danger', 
        () => router.delete(`/super-admin-users/${user.id}`));
};
</script>

<template>
    <Head title="User Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">User Management</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage system access and roles.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center">
                        <div class="relative max-w-md flex-1">
                            <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-400" />
                            <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search name or email..." class="w-full rounded-lg border border-slate-300 py-2.5 pl-10 focus:ring-2 focus:ring-emerald-500 outline-none" />
                        </div>
                        <select v-model="role" @change="applyFilters" class="w-full md:w-48 rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">All Roles</option>
                            <option v-for="r in props.roles" :key="r" :value="r">{{ r.toUpperCase() }}</option>
                        </select>
                        <button v-if="search || role" @click="clearFilters" class="flex items-center gap-2 px-4 py-2.5 text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50">
                            <X class="h-4 w-4" /> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="flex items-center justify-between rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                    <div><p class="text-xs font-bold text-slate-500 uppercase">Users</p><p class="text-2xl font-bold">{{ props.totalUsers }}</p></div>
                    <UserCircle class="h-8 w-8 text-emerald-500 opacity-40" />
                </div>
                <div class="flex items-center justify-between rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg">
                    <div><p class="text-xs font-bold text-slate-500 uppercase">Active</p><p class="text-2xl font-bold">{{ props.activeUsersCount }}</p></div>
                    <CheckCircle2 class="h-8 w-8 text-sky-500 opacity-40" />
                </div>
                <div class="flex items-center justify-between rounded-lg border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                    <div><p class="text-xs font-bold text-slate-500 uppercase">Admins</p><p class="text-2xl font-bold">{{ props.adminUsersCount }}</p></div>
                    <ShieldCheck class="h-8 w-8 text-indigo-500 opacity-40" />
                </div>
                <div class="flex items-center justify-between rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-lg">
                    <div><p class="text-xs font-bold text-slate-500 uppercase">New/Month</p><p class="text-2xl font-bold">{{ props.newUsersThisMonth }}</p></div>
                    <UserPlus class="h-8 w-8 text-purple-500 opacity-40" />
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                    <table class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">User</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="user in usersData" :key="user.id" class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center">
                                            <UserCircle class="h-5 w-5 text-slate-500" />
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-slate-900">{{ user.name }}</div>
                                            <div class="text-xs text-slate-500">{{ user.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['px-2 py-0.5 rounded-full text-[10px] font-bold uppercase', user.usertype === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600']">
                                        {{ user.usertype }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="['text-sm font-medium', user.status === 'active' ? 'text-emerald-600' : 'text-red-500']">
                                        {{ user.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button @click="viewUserDetails(user)" class="p-1.5 hover:bg-slate-100 rounded-full text-slate-500"><Eye class="h-4 w-4"/></button>
                                        <button v-if="user.usertype === 'user'" @click="promoteToAdmin(user)" class="p-1.5 hover:bg-indigo-50 rounded-full text-indigo-600"><ShieldCheck class="h-4 w-4"/></button>
                                        <button v-else @click="promoteToUser(user)" class="p-1.5 hover:bg-orange-50 rounded-full text-orange-600"><ShieldAlert class="h-4 w-4"/></button>
                                        <button @click="toggleBanStatus(user)" :class="['p-1.5 rounded-full', user.status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50']">
                                            <CircleOff v-if="user.status === 'active'" class="h-4 w-4" />
                                            <CheckCircle2 v-else class="h-4 w-4" />
                                        </button>
                                        <button @click="deleteUser(user)" class="p-1.5 hover:bg-red-50 rounded-full text-red-600"><Trash2 class="h-4 w-4"/></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="props.users.links.length > 3" class="flex items-center justify-between px-6 py-4 bg-slate-50 border-t">
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ props.users.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ props.users.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ props.users.total }}</span>
                        </div>
                        <nav class="flex rounded-md shadow-sm bg-white border divide-x">
                            <button v-for="(link, k) in filteredLinks" :key="k" 
                                @click="paginate(link.url)" 
                                :disabled="!link.url || link.active"
                                :class="['px-3 py-2 text-sm font-semibold transition-colors', link.active ? 'bg-emerald-600 text-white' : 'text-slate-500 hover:bg-slate-50', !link.url && 'opacity-30']"
                            >
                                <span v-if="link.label === '...'">...</span>
                                <ChevronLeft v-else-if="link.label.includes('Prev')" class="h-4 w-4" />
                                <ChevronRight v-else-if="link.label.includes('Next')" class="h-4 w-4" />
                                <span v-else>{{ link.label }}</span>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </main>
    </div>
 <transition name="modal-fade">
        <div v-if="isModalOpen && selectedUser" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
            <div class="relative max-h-[90vh] w-full max-w-2xl scale-100 transform overflow-y-auto rounded-2xl border border-slate-100 bg-white shadow-2xl transition-all">
                <div class="sticky top-0 z-10 flex items-start justify-between border-b bg-white/95 px-8 py-6 backdrop-blur-md">
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 overflow-hidden rounded-full border-2 border-emerald-100 shadow-sm">
                            <img v-if="selectedUser.profile_photo" :src="`/storage/${selectedUser.profile_photo}`" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
                                <UserCircle class="h-8 w-8" />
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900">{{ selectedUser.name }}</h2>
                            <p class="text-xs font-semibold tracking-wider text-emerald-600 uppercase">{{ selectedUser.usertype }} Account</p>
                        </div>
                    </div>
                    <button @click="closeModal" class="rounded-full p-2 text-slate-400 transition-colors hover:bg-slate-100">
                        <X class="h-6 w-6" />
                    </button>
                </div>

                <div class="space-y-8 p-8">
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/30 p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm">
                                <ShieldCheck class="h-6 w-6 text-emerald-600" />
                            </div>
                            <div>
                                <p class="text-[10px] font-bold tracking-tight text-emerald-600/70 uppercase">Current Account Status</p>
                                <p class="text-sm font-bold text-slate-800 capitalize">{{ selectedUser.status }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-bold tracking-widest text-slate-400 uppercase">Account Information</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <div class="flex items-center gap-3">
                                    <Mail class="h-5 w-5 text-slate-400" />
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Email Address</p>
                                        <p class="text-sm font-semibold text-slate-700">{{ selectedUser.email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <div class="flex items-center gap-3">
                                    <Calendar class="h-5 w-5 text-slate-400" />
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Joined Date</p>
                                        <p class="text-sm font-semibold text-slate-700">{{ formatDate(selectedUser.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <div class="flex items-center gap-3">
                                    <Fingerprint class="h-5 w-5 text-slate-400" />
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Unique ID</p>
                                        <p class="font-mono text-sm font-bold text-slate-700">#{{ selectedUser.id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sticky bottom-0 flex justify-end border-t border-slate-100 bg-slate-50/80 px-8 py-5 backdrop-blur-sm">
                    <button @click="closeModal" class="rounded-xl bg-slate-900 px-6 py-2.5 font-semibold text-white shadow-md transition-all hover:bg-slate-800">Close Profile</button>
                </div>
            </div>
        </div>
    </transition>

    <transition name="modal-fade">
        <div v-if="isConfirmModalOpen && confirmAction" class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div :class="['flex h-12 w-12 items-center justify-center rounded-full', confirmAction.type === 'danger' ? 'bg-red-100 text-red-600' : confirmAction.type === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600']">
                            <ShieldAlert v-if="confirmAction.type === 'danger' || confirmAction.type === 'warning'" class="h-6 w-6" />
                            <ShieldCheck v-else class="h-6 w-6" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">{{ confirmAction.title }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ confirmAction.message }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 bg-slate-50 px-6 py-4">
                    <button @click="closeConfirm" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-200">Cancel</button>
                    <button @click="handleExecuteAction" :class="['rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all active:scale-95', confirmAction.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : confirmAction.type === 'warning' ? 'bg-orange-600 text-white hover:bg-orange-700' : 'bg-emerald-600 hover:bg-emerald-700']">
                        Confirm Action
                    </button>
                </div>
            </div>
        </div>
    </transition>
    </template>

    
<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
</style>