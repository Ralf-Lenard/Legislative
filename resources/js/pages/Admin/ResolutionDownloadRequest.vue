<script setup lang="ts">
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import RequestDetailsModal from '@/components/ModalRequest/RequestDetailsModalResolutions.vue'; 
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    Eye,
    Search,
    X,
    ChevronRight,
    ChevronLeft,
    MoreHorizontal
} from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

/* ================= TYPES ================= */

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

interface Resolution {
    id: number;
    resolution_number: string;
    title_resolutions: string;
}

interface ResolutionDownloadRequest {
    id: number;
    purpose: string;
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;
    valid_id_type: string;
    valid_id_path: string;
    valid_id_url?: string;
    user: User;
    resolution: Resolution;
}

interface PaginatedRequests {
    data: ResolutionDownloadRequest[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}

/* ================= PROPS (Modeled after Ordinances Page) ================= */

const page = usePage<{
    requests: PaginatedRequests;
    filters: { search?: string; status?: string };
    counts: {
        total: number;
        pending: number;
        approved: number;
        rejected: number;
    };
}>();

// STATE - Bound to inputs
const search = ref(page.props.filters?.search || '');
const statusFilter = ref(page.props.filters?.status || '');

// COMPUTED - These update automatically when page.props changes
const requestsList = computed(() => page.props.requests?.data || []);
const stats = computed(() => page.props.counts);

/* ================= SMART PAGINATION LOGIC ================= */

const filteredLinks = computed(() => {
    const links = page.props.requests.links;
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

/* ================= NAVIGATION LOGIC ================= */

const runNavigation = (pageNumber: string | number | null = 1) => {
    router.get(
        '/resolution-request',
        {
            search: search.value,
            status: statusFilter.value,
            page: pageNumber
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['requests', 'filters', 'counts'], 
        }
    );
};

// HANDLERS
const handleEnter = () => runNavigation(1);
const applyFilters = () => runNavigation(1);

const clearFilters = () => {
    search.value = '';
    statusFilter.value = '';
    router.get('/resolution-request', {}, { replace: true });
};

// Auto-trigger when status dropdown changes
watch(statusFilter, () => applyFilters());

const paginate = (url: string | null) => {
    if (!url) return;
    const urlObj = new URL(url, window.location.origin);
    const pageParam = urlObj.searchParams.get('page');
    runNavigation(pageParam);
};

/* ================= MODAL STATES ================= */

const isApproveModalOpen = ref(false);
const approvingRequest = ref<ResolutionDownloadRequest | null>(null);
const isRejectModalOpen = ref(false);
const rejectingRequest = ref<ResolutionDownloadRequest | null>(null);
const rejectionReason = ref('');
const isDetailsModalOpen = ref(false);
const selectedRequest = ref<ResolutionDownloadRequest | null>(null);

/* ================= ACTIONS ================= */

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    });
};

const handleOpenDetails = (request: ResolutionDownloadRequest) => {
    selectedRequest.value = request;
    isDetailsModalOpen.value = true;
};

const openApproveModal = (request: ResolutionDownloadRequest) => {
    approvingRequest.value = request;
    isApproveModalOpen.value = true;
    isDetailsModalOpen.value = false;
};

const submitApproval = () => {
    if (!approvingRequest.value) return;
    router.post(`/resolution-request/${approvingRequest.value.id}/approve`, {}, {
        onFinish: () => {
            isApproveModalOpen.value = false;
            approvingRequest.value = null;
        },
    });
};

const openRejectModal = (request: ResolutionDownloadRequest) => {
    rejectingRequest.value = request;
    rejectionReason.value = '';
    isRejectModalOpen.value = true;
    isDetailsModalOpen.value = false;
};

const submitRejection = () => {
    if (!rejectingRequest.value || !rejectionReason.value.trim()) return;
    router.post(`/resolution-request/${rejectingRequest.value.id}/reject`, 
    { reason: rejectionReason.value }, 
    {
        onFinish: () => {
            isRejectModalOpen.value = false;
            rejectingRequest.value = null;
        },
    });
};
</script>

<template>
    <Head title="Resolution Requests" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <h1 class="text-3xl font-extrabold text-slate-900">Resolution Requests</h1>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                            <div class="relative max-w-sm flex-1">
                                <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400" />
                                <input
                                    v-model="search"
                                    @keyup.enter="handleEnter"
                                    type="text"
                                    placeholder="Search by user or resolution..."
                                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <select
                                v-model="statusFilter"
                                class="cursor-pointer rounded-lg border border-slate-300 py-2.5 px-4 shadow-sm focus:ring-2 focus:ring-emerald-500 md:w-40"
                            >
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>

                            <button
                                v-if="search || statusFilter"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 hover:bg-slate-50"
                            >
                                <X class="h-4 w-4" />
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="(val, label) in stats" :key="label" 
                     class="rounded-lg border-l-4 bg-white p-5 shadow-lg capitalize"
                     :class="label === 'pending' ? 'border-sky-500' : label === 'approved' ? 'border-indigo-500' : label === 'rejected' ? 'border-purple-500' : 'border-emerald-500'">
                    <p class="text-sm font-medium text-slate-500">{{ label }} Requests</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ val }}</p>
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-hidden rounded-lg border border-slate-100 bg-white shadow-lg">
                    <div v-if="requestsList.length === 0" class="py-16 text-center text-slate-600">
                        No resolution requests found.
                    </div>

                    <table v-else class="w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">#</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">User</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Resolution</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Requested At</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="(request, index) in requestsList" :key="request.id" class="hover:bg-emerald-50/50">
                                <td class="px-6 py-4 text-sm">{{ (page.props.requests.from || 1) + index }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ request.user.name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ request.resolution.title_resolutions }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span :class="['rounded-full px-3 py-0.5 text-xs font-medium', 
                                        request.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        request.status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800']">
                                        {{ request.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ formatDate(request.created_at) }}</td>
                                <td class="flex justify-center gap-2 px-6 py-4">
                                    <button @click="handleOpenDetails(request)" class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100">
                                        <Eye class="h-4 w-4" />
                                    </button>
                                    <template v-if="request.status === 'pending'">
                                        <button @click="openApproveModal(request)" class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-100">Approve</button>
                                        <button @click="openRejectModal(request)" class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Reject</button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="page.props.requests.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ page.props.requests.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ page.props.requests.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ page.props.requests.total }}</span>
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
                                            ? 'z-10 bg-emerald-600 text-white border-emerald-600' 
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
        </main>
    </div>

    <div v-if="isApproveModalOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isApproveModalOpen = false"></div>
        <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl" @click.stop>
            <h2 class="mb-4 text-lg font-semibold">Confirm Approval</h2>
            <p class="mb-6 text-sm text-slate-600">Approve request for <strong>{{ approvingRequest?.user.name }}</strong>?</p>
            <div class="flex justify-end gap-3">
                <button @click="isApproveModalOpen = false" class="rounded-lg bg-slate-100 px-4 py-2 text-slate-700 font-medium">Cancel</button>
                <button @click="submitApproval" class="rounded-lg bg-emerald-600 px-4 py-2 font-semibold text-white hover:bg-emerald-700">Yes, Approve</button>
            </div>
        </div>
    </div>

    <div v-if="isRejectModalOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isRejectModalOpen = false"></div>
        <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl" @click.stop>
            <h2 class="mb-4 text-lg font-semibold">Reject Request</h2>
            <textarea v-model="rejectionReason" rows="4" class="w-full rounded-lg border p-2 focus:ring-2 focus:ring-red-500 focus:outline-none" placeholder="Enter reason for rejection..."></textarea>
            <div class="mt-4 flex justify-end gap-3">
                <button @click="isRejectModalOpen = false" class="rounded-lg bg-slate-100 px-4 py-2 font-medium">Cancel</button>
                <button @click="submitRejection" class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">Reject Request</button>
            </div>
        </div>
    </div>

    <RequestDetailsModal 
        :is-open="isDetailsModalOpen"
        :viewing-request="selectedRequest"
        @close="isDetailsModalOpen = false"
        @approve="openApproveModal"
        @reject="openRejectModal"
    />
</template>