<script setup lang="ts">
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    CheckCircle,
    Eye,
    FileText,
    IdCard,
    Maximize2,
    MessageSquare,
    Search,
    X,
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    address: string | null;
    usertype: string;
    birthdate: string | null;
    contact_number: string | null;
    profile_photo: string | null;
}

interface Ordinance {
    id: number;
    ordinance_number: string;
    title_ordinances: string;
}

interface OrdinanceDownloadRequest {
    id: number;
    purpose: string;
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;

    // ✅ Valid ID fields
    valid_id_type: string;
    valid_id_path: string;
    valid_id_url?: string; // if you added the accessor

    // Relations
    user: User;
    ordinance: Ordinance;
}

interface PaginatedRequests {
    data: OrdinanceDownloadRequest[];
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
const { props } = usePage<{
    requests: PaginatedRequests;
    filters: { search?: string; status?: string };
    counts: {
        // Added this
        total: number;
        pending: number;
        approved: number;
        rejected: number;
    };
}>();

// Access them easily via a computed property
const stats = computed(() => props.counts);

/* ================= STATE ================= */

const requests = ref<OrdinanceDownloadRequest[]>(props.requests.data);
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');

const isDetailsModalOpen = ref(false);
const viewingRequest = ref<OrdinanceDownloadRequest | null>(null);

/* ================= PAGINATION ================= */

const paginationMeta = computed(() => props.requests.meta);
const paginationLinks = computed(() => props.requests.links);

const paginate = (url: string) => {
    router.get(url, {}, { preserveScroll: true });
};

/* ================= FILTERS ================= */

const applyFilters = () => {
    router.get(
        '/ordinance-request',
        { search: search.value, status: statusFilter.value, page: 1 },
        { preserveState: false },
    );
};

const clearFilters = () => {
    search.value = '';
    statusFilter.value = '';
    applyFilters();
};

/* ================= DETAILS ================= */

const openDetailsModal = (request: OrdinanceDownloadRequest) => {
    viewingRequest.value = request;
    isDetailsModalOpen.value = true;
};

/* ================= FORMAT ================= */

const formatDate = (date: string) => new Date(date).toLocaleDateString();

/* ================= WATCH ================= */

watch(
    () => props.requests.data,
    (val) => (requests.value = val),
);

/* =========================================================
     APPROVE LOGIC — FIXED (MATCHES ORDINANCES)
     ========================================================= */

const isApproveModalOpen = ref(false);
const approvingRequest = ref<OrdinanceDownloadRequest | null>(null);

const openApproveModal = (request: OrdinanceDownloadRequest) => {
    approvingRequest.value = request;
    isApproveModalOpen.value = true;
};

const submitApproval = () => {
    if (!approvingRequest.value) return;

    router.visit(`/ordinance-request/${approvingRequest.value.id}/approve`, {
        method: 'post',
        preserveState: false, // ✅ FORCE RELOAD
        onFinish: () => {
            isApproveModalOpen.value = false;
            approvingRequest.value = null;
        },
    });
};

/* =========================================================
     REJECT LOGIC — FIXED (NO AUTO-CLOSE)
     ========================================================= */

const isRejectModalOpen = ref(false);
const rejectingRequest = ref<OrdinanceDownloadRequest | null>(null);
const rejectionReason = ref('');

const openRejectModal = (request: OrdinanceDownloadRequest) => {
    rejectingRequest.value = request;
    rejectionReason.value = '';
    isRejectModalOpen.value = true;
};

const submitRejection = () => {
    if (!rejectingRequest.value || !rejectionReason.value.trim()) return;

    router.visit(`/ordinance-request/${rejectingRequest.value.id}/reject`, {
        method: 'post',
        data: { reason: rejectionReason.value },
        preserveState: false,
        onFinish: () => {
            isRejectModalOpen.value = false;
            rejectingRequest.value = null;
            rejectionReason.value = '';
        },
    });
};

const computeAge = (birthdate: string | null) => {
    if (!birthdate) return 'N/A';

    const birth = new Date(birthdate);
    const today = new Date();

    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
        age--;
    }

    return age;
};

const getInitials = (name: string) => {
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
};

const isImage = (url) => {
    return /\.(jpg|jpeg|png)$/i.test(url);
};

const isPreviewModalOpen = ref(false);

// Optional: Prevent background scrolling when image preview is open
watch(isPreviewModalOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : 'auto';
});

// In your data() or ref()
const isIdModalOpen = ref(false);
</script>

<template>
    <Head title="Ordinance Requests" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div
                class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md"
            >
                <div class="flex items-center justify-between px-8 py-6">
                    <h1 class="text-3xl font-extrabold text-slate-900">
                        Ordinance Requests
                    </h1>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div
                        class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                    >
                        <div
                            class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3"
                        >
                            <div class="relative max-w-sm flex-1">
                                <Search
                                    class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"
                                />
                                <input
                                    v-model="search"
                                    @keyup.enter="applyFilters"
                                    type="text"
                                    placeholder="Search by user, ordinance, or purpose..."
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <div class="relative">
                                <select
                                    v-model="statusFilter"
                                    @change="applyFilters"
                                    class="w-full cursor-pointer appearance-none rounded-lg border border-slate-300 py-2.5 pr-4 pl-4 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none md:w-40"
                                >
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                                <svg
                                    class="pointer-events-none absolute top-1/2 right-3 h-5 w-5 -translate-y-1/2 text-slate-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </div>

                            <button
                                v-if="search || statusFilter"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50"
                            >
                                <X class="h-4 w-4" />
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div
                class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4"
            >
                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-emerald-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Requests
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ stats.total }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-sky-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Pending
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ stats.pending }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-indigo-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Approved
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ stats.approved }}
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between rounded-lg border-l-4 border-purple-500 bg-white p-5 shadow-lg"
                >
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Rejected
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ stats.rejected }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div
                    class="overflow-hidden rounded-lg border border-slate-100 bg-white shadow-lg"
                >
                    <div
                        v-if="!requests || requests.length === 0"
                        class="py-16 text-center text-slate-600"
                    >
                        No download requests found.
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase"
                                >
                                    #
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase"
                                >
                                    User
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase"
                                >
                                    Ordinance
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase"
                                >
                                    Purpose
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase"
                                >
                                    Requested At
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr
                                v-for="(request, index) in requests"
                                :key="request.id"
                                class="transition-colors hover:bg-emerald-50/50"
                            >
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ (paginationMeta?.from || 1) + index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ request.user.name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ request.ordinance.title_ordinances }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ request.purpose }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <span
                                        :class="[
                                            'inline-flex items-center rounded-full px-3 py-0.5 text-xs font-medium',
                                            request.status === 'pending'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : request.status === 'approved'
                                                  ? 'bg-emerald-100 text-emerald-800'
                                                  : 'bg-red-100 text-red-800',
                                        ]"
                                    >
                                        {{
                                            request.status
                                                .charAt(0)
                                                .toUpperCase() +
                                            request.status.slice(1)
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ formatDate(request.created_at) }}
                                </td>
                                <td
                                    class="flex justify-center gap-2 px-6 py-4 text-center"
                                >
                                    <button
                                        @click="openDetailsModal(request)"
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 shadow-sm hover:bg-blue-100"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </button>

                                    <button
                                        v-if="request.status === 'pending'"
                                        @click="openApproveModal(request)"
                                        class="flex h-8 items-center justify-center rounded-full bg-emerald-50 px-3 text-xs font-semibold text-emerald-600 shadow-sm hover:bg-emerald-100"
                                    >
                                        Approve
                                    </button>

                                    <button
                                        v-if="request.status === 'pending'"
                                        @click="openRejectModal(request)"
                                        class="flex h-8 items-center justify-center rounded-full bg-red-50 px-3 text-xs font-semibold text-red-600 shadow-sm hover:bg-red-100"
                                    >
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div
                        v-if="paginationLinks && paginationLinks.length > 3"
                        class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6"
                    >
                        <div class="text-sm text-slate-600">
                            Showing
                            <span class="font-semibold">{{
                                paginationMeta?.from
                            }}</span>
                            to
                            <span class="font-semibold">{{
                                paginationMeta?.to
                            }}</span>
                            of
                            <span class="font-semibold">{{
                                paginationMeta?.total
                            }}</span>
                            results
                        </div>
                        <nav
                            class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                            aria-label="Pagination"
                        >
                            <component
                                v-for="(link, key) in paginationLinks"
                                :key="key"
                                :is="link.url ? 'button' : 'span'"
                                @click="link.url ? paginate(link.url) : null"
                                :disabled="!link.url"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 transition-all ring-inset focus:z-20',
                                    key === 0 ? 'rounded-l-md' : '',
                                    key === paginationLinks.length - 1
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

    <div
        v-if="isApproveModalOpen"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
    >
        <!-- OVERLAY -->
        <div
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            @click="isApproveModalOpen = false"
        ></div>

        <!-- MODAL -->
        <div
            class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
            @click.stop
        >
            <h2 class="mb-4 text-lg font-semibold text-slate-900">
                Confirm Approval
            </h2>

            <p class="mb-6 text-sm text-slate-600">
                Are you sure you want to approve the request by
                <strong>{{ approvingRequest?.user.name }}</strong>
                for ordinance
                <strong>{{
                    approvingRequest?.ordinance.title_ordinances
                }}</strong
                >? This action will allow the user to download the document.
            </p>

            <div class="flex justify-end gap-3">
                <button
                    @click="isApproveModalOpen = false"
                    class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 hover:bg-slate-200"
                >
                    Cancel
                </button>

                <button
                    @click="submitApproval"
                    class="rounded-lg bg-emerald-600 px-4 py-2 font-semibold text-white hover:bg-emerald-700"
                >
                    Yes, Approve
                </button>
            </div>
        </div>
    </div>

    <div
        v-if="isRejectModalOpen"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
    >
        <!-- OVERLAY (clicking this closes modal) -->
        <div
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            @click="isRejectModalOpen = false"
        ></div>

        <!-- MODAL (clicks inside are SAFE) -->
        <div
            class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
            @click.stop
        >
            <h2 class="mb-4 text-lg font-semibold text-slate-900">
                Reject Request
            </h2>

            <p class="mb-4 text-sm text-slate-600">
                Provide a reason for rejecting the request by
                <strong>{{ rejectingRequest?.user.name }}</strong>
                for ordinance
                <strong>{{
                    rejectingRequest?.ordinance.title_ordinances
                }}</strong
                >.
            </p>

            <textarea
                v-model="rejectionReason"
                rows="4"
                class="w-full rounded-lg border border-slate-300 p-2 shadow-sm focus:ring-2 focus:ring-red-500 focus:outline-none"
                placeholder="Enter rejection reason"
            ></textarea>

            <div class="mt-4 flex justify-end gap-3">
                <button
                    @click="isRejectModalOpen = false"
                    class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 hover:bg-slate-200"
                >
                    Cancel
                </button>

                <button
                    @click="submitRejection"
                    class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700"
                >
                    Reject
                </button>
            </div>
        </div>
    </div>

    <div
        v-if="isDetailsModalOpen"
        @click.self="isDetailsModalOpen = false"
        class="fixed inset-0 z-[9998] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity"
    >
        <div
            class="relative flex h-auto max-h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div
                class="flex items-center justify-between border-b border-slate-100 px-8 py-5"
            >
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        Request Details
                    </h2>
                    <p class="text-sm text-slate-500">
                        Reference ID: #{{ viewingRequest?.id }}
                    </p>
                </div>
                <button
                    @click="isDetailsModalOpen = false"
                    class="rounded-full p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                >
                    <X class="h-6 w-6" />
                </button>
            </div>

            <div
                v-if="viewingRequest"
                class="flex flex-1 flex-col overflow-y-auto md:flex-row"
            >
                <div
                    class="w-full border-r border-slate-100 bg-slate-50/50 p-8 md:w-80"
                >
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-4">
                            <img
                                v-if="viewingRequest.user.profile_photo"
                                :src="`/storage/${viewingRequest.user.profile_photo}`"
                                class="h-32 w-32 rounded-2xl border-4 border-white object-cover shadow-md"
                            />
                            <div
                                v-else
                                class="flex h-32 w-32 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-emerald-500 to-teal-600 text-3xl font-bold text-white shadow-md"
                            >
                                {{ getInitials(viewingRequest.user.name) }}
                            </div>

                            <span
                                :class="[
                                    'absolute -right-2 -bottom-2 rounded-lg px-2 py-1 text-[10px] font-bold tracking-wider uppercase shadow-sm',
                                    viewingRequest.user.usertype === 'admin'
                                        ? 'bg-purple-600 text-white'
                                        : 'bg-blue-600 text-white',
                                ]"
                            >
                                {{ viewingRequest.user.usertype }}
                            </span>
                        </div>

                        <h3
                            class="text-lg leading-tight font-bold text-slate-900"
                        >
                            {{ viewingRequest.user.name }}
                        </h3>
                        <p class="text-sm text-slate-500">
                            {{ viewingRequest.user.email }}
                        </p>
                    </div>

                    <div class="mt-8 space-y-5">
                        <div class="flex flex-col">
                            <span
                                class="text-[11px] font-bold tracking-widest text-slate-400 uppercase"
                                >Contact</span
                            >
                            <span class="text-sm font-medium text-slate-700">{{
                                viewingRequest.user.contact_number || 'None'
                            }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[11px] font-bold tracking-widest text-slate-400 uppercase"
                                >Age / Birthdate</span
                            >
                            <span class="text-sm font-medium text-slate-700">
                                {{
                                    computeAge(viewingRequest.user.birthdate)
                                }}
                                yrs old ({{
                                    viewingRequest.user.birthdate || 'N/A'
                                }})
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[11px] font-bold tracking-widest text-slate-400 uppercase"
                                >Address</span
                            >
                            <span
                                class="text-sm leading-relaxed font-medium text-slate-700"
                                >{{
                                    viewingRequest.user.address ||
                                    'No address provided'
                                }}</span
                            >
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-8">
                    <div
                        :class="[
                            'mb-8 flex items-center justify-between rounded-xl border-l-4 px-6 py-4',
                            viewingRequest.status === 'pending'
                                ? 'border-yellow-400 bg-yellow-50 text-yellow-800'
                                : viewingRequest.status === 'approved'
                                  ? 'border-emerald-400 bg-emerald-50 text-emerald-800'
                                  : 'border-red-400 bg-red-50 text-red-800',
                        ]"
                    >
                        <div>
                            <p
                                class="text-[11px] font-bold tracking-widest uppercase opacity-70"
                            >
                                Current Status
                            </p>
                            <p class="text-lg font-bold capitalize">
                                {{ viewingRequest.status }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-[11px] font-bold tracking-widest uppercase opacity-70"
                            >
                                Requested Date
                            </p>
                            <p class="font-semibold">
                                {{ formatDate(viewingRequest.created_at) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                        <div>
                            <h4
                                class="mb-3 flex items-center gap-2 text-sm font-bold tracking-widest text-slate-400 uppercase"
                            >
                                <FileText class="h-4 w-4" /> Target Ordinance
                            </h4>
                            <div
                                class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-emerald-200 hover:bg-emerald-50/30"
                            >
                                <p
                                    class="mb-1 text-xs font-bold text-emerald-600 uppercase"
                                >
                                    {{
                                        viewingRequest.ordinance
                                            .ordinance_number
                                    }}
                                </p>
                                <h5
                                    class="text-base leading-snug font-bold text-slate-800"
                                >
                                    {{
                                        viewingRequest.ordinance
                                            .title_ordinances
                                    }}
                                </h5>
                            </div>

                            <h4
                                class="mt-6 mb-3 flex items-center gap-2 text-sm font-bold tracking-widest text-slate-400 uppercase"
                            >
                                <MessageSquare class="h-4 w-4" /> Reason
                            </h4>
                            <div
                                class="relative rounded-xl bg-slate-100 p-5 text-slate-700 italic"
                            >
                                <p
                                    class="text-sm leading-relaxed whitespace-pre-line"
                                >
                                    {{ viewingRequest.purpose }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h4
                                class="mb-3 flex items-center gap-2 text-sm font-bold tracking-widest text-slate-400 uppercase"
                            >
                                <IdCard class="h-4 w-4" /> Submitted Valid ID
                            </h4>
                            <div
                                class="rounded-xl border border-slate-200 bg-slate-50 p-4"
                            >
                                <p
                                    class="mb-3 text-sm font-semibold text-slate-700"
                                >
                                    ID Type:
                                    <span class="font-bold text-emerald-600">{{
                                        viewingRequest.valid_id_type
                                    }}</span>
                                </p>

                                <div
                                    v-if="
                                        viewingRequest.valid_id_url &&
                                        isImage(viewingRequest.valid_id_url)
                                    "
                                    class="group relative cursor-pointer overflow-hidden rounded-lg border border-slate-300 bg-white shadow-sm"
                                    @click="isIdModalOpen = true"
                                >
                                    <img
                                        :src="viewingRequest.valid_id_url"
                                        class="h-40 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition-opacity group-hover:opacity-100"
                                    >
                                        <div
                                            class="flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-slate-900 shadow-lg"
                                        >
                                            <Maximize2 class="h-3 w-3" /> Click
                                            to view full
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-else-if="viewingRequest.valid_id_url"
                                    class="flex h-40 flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-600"
                                >
                                    <FileText
                                        class="mb-2 h-8 w-8 text-slate-300"
                                    />
                                    <span
                                        >PDF file submitted. Preview not
                                        available.</span
                                    >
                                </div>

                                <div
                                    v-else
                                    class="flex h-40 items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-400"
                                >
                                    No valid ID uploaded.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-8 py-5"
            >
                <button
                    @click="isDetailsModalOpen = false"
                    class="rounded-lg px-6 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-200"
                >
                    Close
                </button>
                <template v-if="viewingRequest?.status === 'pending'">
                    <button
                        @click="
                            openRejectModal(viewingRequest);
                            isDetailsModalOpen = false;
                        "
                        class="rounded-lg bg-red-50 px-6 py-2.5 text-sm font-bold text-red-600 transition hover:bg-red-100"
                    >
                        Deny Access
                    </button>
                    <button
                        @click="
                            openApproveModal(viewingRequest);
                            isDetailsModalOpen = false;
                        "
                        class="flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 active:scale-95"
                    >
                        <CheckCircle class="h-4 w-4" /> Approve Request
                    </button>
                </template>
            </div>
        </div>
    </div>

    <div
        v-if="isIdModalOpen"
        @click.self="isIdModalOpen = false"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/90 p-4 backdrop-blur-sm"
    >
        <div class="relative max-h-[90vh] max-w-[90vw]">
            <button
                @click="isIdModalOpen = false"
                class="absolute -top-12 -right-4 rounded-full p-2 text-white hover:bg-white/10"
            >
                <X class="h-8 w-8" />
            </button>
            <img
                :src="viewingRequest?.valid_id_url"
                alt="Full ID Preview"
                class="h-auto max-h-[80vh] w-auto rounded-xl shadow-2xl ring-4 ring-white/10"
            />
            <div class="mt-4 text-center">
                <p
                    class="text-lg font-bold tracking-widest text-white uppercase"
                >
                    {{ viewingRequest?.valid_id_type }}
                </p>
            </div>
        </div>
    </div>
</template>
