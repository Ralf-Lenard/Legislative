<script setup lang="ts">
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import { Head, router } from '@inertiajs/vue3';
import {
    Calendar,
    CheckCircle2,
    CircleOff,
    Eye,
    Fingerprint,
    Mail,
    Search,
    ShieldAlert,
    ShieldCheck,
    Trash2,
    UserCircle,
    UserPlus,
    X,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    usertype: string;
    status: string;
    created_at: string;
    profile_photo: string | null;
}

interface PaginatedUsers {
    data: User[];
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

const props = defineProps<{
    users: PaginatedUsers;
    filters: { search?: string; role?: string };
    roles: string[];
    totalUsers: number;
    activeUsersCount: number;
    adminUsersCount: number;
    newUsersThisMonth: number;
    flash?: { success?: string; error?: string };
}>();

const users = ref<User[]>([...(props.users?.data || [])]);
const search = ref(props.filters?.search || '');
const role = ref(props.filters?.role || '');

// --- View Details Logic ---
const selectedUser = ref<User | null>(null);
const isModalOpen = ref(false);

const viewUserDetails = (user: User) => {
    selectedUser.value = user;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedUser.value = null;
};

// --- Action Methods ---

// --- Confirmation Modal Logic ---
const isConfirmModalOpen = ref(false);
const confirmAction = ref<{
    title: string;
    message: string;
    onConfirm: () => void;
    type: 'danger' | 'warning' | 'info';
} | null>(null);

const openConfirm = (
    title: string,
    message: string,
    onConfirm: () => void,
    type: 'danger' | 'warning' | 'info' = 'info',
) => {
    confirmAction.value = { title, message, onConfirm, type };
    isConfirmModalOpen.value = true;
};

const closeConfirm = () => {
    isConfirmModalOpen.value = false;
    setTimeout(() => (confirmAction.value = null), 300); // Clear after transition
};

const handleExecuteAction = () => {
    if (confirmAction.value) {
        confirmAction.value.onConfirm();
        closeConfirm();
    }
};

// --- Refactored Action Methods ---

const promoteToAdmin = (user: User) => {
    openConfirm(
        'Promote to Admin',
        `Are you sure you want to grant Administrative privileges to ${user.name}?`,
        () =>
            router.post(
                `/super-admin/promote/${user.id}`,
                {},
                { preserveScroll: true },
            ),
        'info',
    );
};

const promoteToUser = (user: User) => {
    openConfirm(
        'Demote to User',
        `Are you sure you want to demote ${user.name} to a regular User? They will lose admin access.`,
        () =>
            router.post(
                `/super-admin/demote/${user.id}`,
                {},
                { preserveScroll: true },
            ), // Added missing slash from your original code
        'warning',
    );
};

const toggleBanStatus = (user: User) => {
    const isBanned = user.status === 'banned';
    openConfirm(
        isBanned ? 'Unban User' : 'Ban User',
        `Are you sure you want to ${isBanned ? 'restore access for' : 'restrict access for'} ${user.name}?`,
        () => {
            const url = isBanned
                ? `/super-admin/unban-user/${user.id}`
                : `/super-admin/ban-user/${user.id}`;
            router.post(url, {}, { preserveScroll: true });
        },
        isBanned ? 'info' : 'warning',
    );
};

const deleteUser = (user: User) => {
    openConfirm(
        'Delete User Permanently',
        `WARNING: You are about to permanently delete ${user.name}. This action is irreversible and all associated data will be lost.`,
        () =>
            router.delete(`/super-admin-users/${user.id}`, {
                preserveScroll: true,
            }),
        'danger',
    );
};

// --- Filters & Pagination ---

const paginate = (url: string | null) => {
    if (url) {
        router.get(
            url,
            { search: search.value, role: role.value },
            { preserveScroll: true },
        );
    }
};

const applyFilters = () => {
    router.get(
        '/super-admin-users',
        { search: search.value, role: role.value, page: 1 },
        { preserveState: true, replace: true },
    );
};

const clearFilters = () => {
    search.value = '';
    role.value = '';
    router.get('/super-admin-users', {}, { preserveState: false });
};

const formatDate = (date: string) => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

watch(
    () => props.users?.data,
    (newData) => {
        if (newData) users.value = [...newData];
    },
);
</script>

<template>
    <Head title="User Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div
                class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md"
            >
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">
                            User Management
                        </h1>
                        <p class="mt-1 text-sm text-slate-600">
                            Manage system access, roles, and user profiles.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div
                        class="flex flex-col gap-4 md:flex-row md:items-center"
                    >
                        <div
                            class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3"
                        >
                            <div class="relative max-w-md flex-1">
                                <Search
                                    class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"
                                />
                                <input
                                    v-model="search"
                                    @keyup.enter="applyFilters"
                                    type="text"
                                    placeholder="Search by name or email..."
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <div class="w-full md:w-48">
                                <select
                                    v-model="role"
                                    @change="applyFilters"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                >
                                    <option value="">All Statuses</option>
                                    <option
                                        v-for="r in props.roles"
                                        :key="r"
                                        :value="r"
                                    >
                                        {{ r.toUpperCase() }}
                                    </option>
                                </select>
                            </div>

                            <button
                                v-if="search || role"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50"
                            >
                                <X class="h-4 w-4" /> Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4"
            >
                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Regular Users
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ props.totalUsers }}
                        </p>
                    </div>
                    <UserCircle class="h-8 w-8 text-emerald-500 opacity-60" />
                </div>
                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Active Status
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ props.activeUsersCount }}
                        </p>
                    </div>
                    <CheckCircle2 class="h-8 w-8 text-sky-500 opacity-60" />
                </div>
                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-indigo-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Admins
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ props.adminUsersCount }}
                        </p>
                    </div>
                    <ShieldCheck class="h-8 w-8 text-indigo-500 opacity-60" />
                </div>
                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            New This Month
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ props.newUsersThisMonth }}
                        </p>
                    </div>
                    <UserPlus class="h-8 w-8 text-purple-500 opacity-60" />
                </div>
            </div>

            <div class="p-8">
                <div
                    class="overflow-hidden rounded-lg border border-slate-100 bg-white shadow-lg"
                >
                    <table class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >
                                    User
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >
                                    Type
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >
                                    Joined Date
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-if="!users || users.length === 0">
                                <td colspan="5" class="py-16">
                                    <div
                                        class="flex flex-col items-center justify-center text-center"
                                    >
                                        <UserCircle
                                            class="h-14 w-14 text-slate-300"
                                        />
                                        <p
                                            class="mt-4 text-lg font-semibold text-slate-700"
                                        >
                                            No users found
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            Try adjusting your filters or add a
                                            new user.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="transition-colors hover:bg-emerald-50/50"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img
                                                v-if="user.profile_photo"
                                                :src="`/storage/${user.profile_photo}`"
                                                class="h-10 w-10 rounded-full border border-slate-200 object-cover"
                                            />
                                            <div
                                                v-else
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-500"
                                            >
                                                <UserCircle class="h-6 w-6" />
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-semibold text-slate-900"
                                            >
                                                {{ user.name }}
                                            </div>
                                            <div
                                                class="flex items-center gap-1 text-xs text-slate-500"
                                            >
                                                <Mail class="h-3 w-3" />
                                                {{ user.email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium uppercase',
                                            user.usertype === 'admin'
                                                ? 'bg-indigo-100 text-indigo-800'
                                                : 'bg-slate-100 text-slate-800',
                                        ]"
                                    >
                                        {{ user.usertype }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="[
                                            'inline-flex items-center gap-1 text-sm font-medium capitalize',
                                            user.status === 'active'
                                                ? 'text-emerald-600'
                                                : 'text-red-500',
                                        ]"
                                    >
                                        <CheckCircle2
                                            v-if="user.status === 'active'"
                                            class="h-4 w-4"
                                        />
                                        <CircleOff v-else class="h-4 w-4" />
                                        {{ user.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ formatDate(user.created_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="flex items-center justify-center gap-3"
                                    >
                                        <button
                                            @click="viewUserDetails(user)"
                                            title="View Details"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition-all hover:bg-slate-200"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </button>

                                        <button
                                            v-if="user.usertype === 'user'"
                                            @click="promoteToAdmin(user)"
                                            title="Promote to Admin"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 transition-all hover:bg-indigo-100"
                                        >
                                            <ShieldCheck class="h-4 w-4" />
                                        </button>
                                        <button
                                            v-else-if="
                                                user.usertype === 'admin'
                                            "
                                            @click="promoteToUser(user)"
                                            title="Demote to User"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-50 text-orange-600 transition-all hover:bg-orange-100"
                                        >
                                            <ShieldAlert class="h-4 w-4" />
                                        </button>

                                        <button
                                            v-if="user.usertype === 'user'"
                                            @click="toggleBanStatus(user)"
                                            :class="[
                                                'flex h-8 w-8 items-center justify-center rounded-full transition-all',
                                                user.status === 'active'
                                                    ? 'bg-amber-50 text-amber-600 hover:bg-amber-100'
                                                    : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100',
                                            ]"
                                        >
                                            <CircleOff
                                                v-if="user.status === 'active'"
                                                class="h-4 w-4"
                                            />
                                            <CheckCircle2
                                                v-else
                                                class="h-4 w-4"
                                            />
                                        </button>

                                        <button
                                            @click="deleteUser(user)"
                                            title="Delete User"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-600 transition-all hover:bg-red-100"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div
                        v-if="props.users.links && props.users.links.length > 3"
                        class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6"
                    >
                        <div class="text-sm text-slate-600">
                            Showing
                            <span class="font-semibold">{{
                                props.users.meta.from
                            }}</span>
                            to
                            <span class="font-semibold">{{
                                props.users.meta.to
                            }}</span>
                            of
                            <span class="font-semibold">{{
                                props.users.meta.total
                            }}</span>
                            users
                        </div>
                        <nav
                            class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                        >
                            <button
                                v-for="(link, key) in props.users.links"
                                :key="key"
                                @click="paginate(link.url)"
                                :disabled="!link.url"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 transition-all ring-inset',
                                    key === 0 ? 'rounded-l-md' : '',
                                    key === props.users.links.length - 1
                                        ? 'rounded-r-md'
                                        : '',
                                    link.active
                                        ? 'bg-emerald-600 text-white ring-emerald-600'
                                        : link.url
                                          ? 'text-slate-900 hover:bg-slate-50'
                                          : 'cursor-not-allowed bg-slate-100 text-slate-400',
                                ]"
                                v-html="link.label"
                            />
                        </nav>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <transition name="modal-fade">
        <div
            v-if="isModalOpen && selectedUser"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
        >
            <div
                class="relative max-h-[90vh] w-full max-w-2xl scale-100 transform overflow-y-auto rounded-2xl border border-slate-100 bg-white shadow-2xl transition-all"
            >
                <div
                    class="sticky top-0 z-10 flex items-start justify-between border-b bg-white/95 px-8 py-6 backdrop-blur-md"
                >
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 overflow-hidden rounded-full border-2 border-emerald-100 shadow-sm"
                        >
                            <img
                                v-if="selectedUser.profile_photo"
                                :src="`/storage/${selectedUser.profile_photo}`"
                                class="h-full w-full object-cover"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400"
                            >
                                <UserCircle class="h-8 w-8" />
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900">
                                {{ selectedUser.name }}
                            </h2>
                            <p
                                class="text-xs font-semibold tracking-wider text-emerald-600 uppercase"
                            >
                                {{ selectedUser.usertype }} Account
                            </p>
                        </div>
                    </div>
                    <button
                        @click="closeModal"
                        class="rounded-full p-2 text-slate-400 transition-colors hover:bg-slate-100"
                    >
                        <X class="h-6 w-6" />
                    </button>
                </div>

                <div class="space-y-8 p-8">
                    <div
                        class="rounded-xl border border-emerald-100 bg-emerald-50/30 p-5"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm"
                            >
                                <ShieldCheck class="h-6 w-6 text-emerald-600" />
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-bold tracking-tight text-emerald-600/70 uppercase"
                                >
                                    Current Account Status
                                </p>
                                <p
                                    class="text-sm font-bold text-slate-800 capitalize"
                                >
                                    {{ selectedUser.status }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3
                            class="mb-4 text-xs font-bold tracking-widest text-slate-400 uppercase"
                        >
                            Account Information
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div
                                class="rounded-xl border border-slate-100 bg-slate-50/50 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <Mail class="h-5 w-5 text-slate-400" />
                                    <div>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 uppercase"
                                        >
                                            Email Address
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-slate-700"
                                        >
                                            {{ selectedUser.email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-xl border border-slate-100 bg-slate-50/50 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <Calendar class="h-5 w-5 text-slate-400" />
                                    <div>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 uppercase"
                                        >
                                            Joined Date
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-slate-700"
                                        >
                                            {{
                                                formatDate(
                                                    selectedUser.created_at,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="rounded-xl border border-slate-100 bg-slate-50/50 p-4"
                            >
                                <div class="flex items-center gap-3">
                                    <Fingerprint
                                        class="h-5 w-5 text-slate-400"
                                    />
                                    <div>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 uppercase"
                                        >
                                            Unique ID
                                        </p>
                                        <p
                                            class="font-mono text-sm font-bold text-slate-700"
                                        >
                                            #{{ selectedUser.id }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="sticky bottom-0 flex justify-end border-t border-slate-100 bg-slate-50/80 px-8 py-5 backdrop-blur-sm"
                >
                    <button
                        @click="closeModal"
                        class="rounded-xl bg-slate-900 px-6 py-2.5 font-semibold text-white shadow-md transition-all hover:bg-slate-800"
                    >
                        Close Profile
                    </button>
                </div>
            </div>
        </div>
    </transition>

    <transition name="modal-fade">
        <div
            v-if="isConfirmModalOpen && confirmAction"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        >
            <div
                class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"
            >
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div
                            :class="[
                                'flex h-12 w-12 items-center justify-center rounded-full',
                                confirmAction.type === 'danger'
                                    ? 'bg-red-100 text-red-600'
                                    : confirmAction.type === 'warning'
                                      ? 'bg-amber-100 text-amber-600'
                                      : 'bg-indigo-100 text-indigo-600',
                            ]"
                        >
                            <ShieldAlert
                                v-if="
                                    confirmAction.type === 'danger' ||
                                    confirmAction.type === 'warning'
                                "
                                class="h-6 w-6"
                            />
                            <ShieldCheck v-else class="h-6 w-6" />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ confirmAction.title }}
                            </h3>
                            <p
                                class="mt-1 text-sm leading-relaxed text-slate-500"
                            >
                                {{ confirmAction.message }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="flex items-center justify-end gap-3 bg-slate-50 px-6 py-4"
                >
                    <button
                        @click="closeConfirm"
                        class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-200"
                    >
                        Cancel
                    </button>
                    <button
                        @click="handleExecuteAction"
                        :class="[
                            'rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all active:scale-95',
                            confirmAction.type === 'danger'
                                ? 'bg-red-600 hover:bg-red-700'
                                : confirmAction.type === 'warning'
                                  ? 'bg-orange-500 hover:bg-orange-600'
                                  : 'bg-emerald-600 hover:bg-emerald-700',
                        ]"
                    >
                        Confirm Action
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
