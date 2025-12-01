<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Search, X, Trash2 } from 'lucide-vue-next';
import { computed, ref, nextTick, onMounted, watch } from 'vue';

interface User {
    id: number;
    name: string;
}

interface Ordinance {
    id: number;
    ordinance_number: string;
    title_ordinances: string;
}

interface OrdinanceDownloadRequest {
    id: number;
    purpose: string;
    status: string;
    created_at: string;
    user: User;
    ordinance: Ordinance;
}

interface PaginatedRequests {
    data: OrdinanceDownloadRequest[];
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

const { props } = usePage<{
    requests: PaginatedRequests;
    filters: { search?: string };
    flash?: { success?: string; error?: string };
}>();

const flashMessage = ref<{ type: 'success' | 'error'; text: string } | null>(null);
const requests = ref<OrdinanceDownloadRequest[]>([...(props.requests?.data || [])]);
const search = ref(props.filters?.search || '');
const isDeleteDialogOpen = ref(false);
const deletingRequest = ref<OrdinanceDownloadRequest | null>(null);

const paginationMeta = computed(() => props.requests?.meta || null);
const paginationLinks = computed(() => props.requests?.links || []);

const paginate = (url: string) => {
    if (url) {
        router.get(url, {}, { preserveScroll: true });
    }
};

const applyFilters = () => {
    router.get(
        '/ordinance-request',
        { search: search.value, page: 1 },
        { preserveState: false, replace: true },
    );
};

const clearFilters = () => {
    search.value = '';
    router.get('/ordinance-request', { search: '', page: 1 }, { preserveState: false, replace: true });
};

const openDeleteDialog = (request: OrdinanceDownloadRequest) => {
    deletingRequest.value = request;
    isDeleteDialogOpen.value = true;
};

const formatDate = (date: string) => {
    const d = new Date(date);
    return isNaN(d.getTime()) ? '—' : d.toLocaleDateString();
};

onMounted(() => {
    watch(
        () => props.requests?.data,
        (newData) => {
            if (newData) requests.value = [...newData];
        }
    );

    watch(
        () => props.flash,
        (newVal) => {
            if (newVal?.success) {
                flashMessage.value = { type: 'success', text: newVal.success };
                setTimeout(() => (flashMessage.value = null), 4000);
            } else if (newVal?.error) {
                flashMessage.value = { type: 'error', text: newVal.error };
                setTimeout(() => (flashMessage.value = null), 4000);
            }
        },
        { deep: true }
    );
});

const approveRequest = (id: number) => {
    if (!confirm('Are you sure you want to approve this request?')) return;

    router.post(`/ordinance-request/${id}/approve`, {}, {
        preserveState: false,
        onSuccess: () => {
            flashMessage.value = { type: 'success', text: 'Request approved.' };
            // Reload or remove approved request from table
            requests.value = requests.value.map(r => r.id === id ? { ...r, status: 'approved' } : r);
            setTimeout(() => (flashMessage.value = null), 4000);
        },
        onError: () => {
            flashMessage.value = { type: 'error', text: 'Failed to approve request.' };
            setTimeout(() => (flashMessage.value = null), 4000);
        }
    });
};

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

    router.post(`/ordinance-request/${rejectingRequest.value.id}/reject`, { reason: rejectionReason.value }, {
        preserveState: false,
        onSuccess: () => {
            flashMessage.value = { type: 'success', text: 'Request rejected.' };
            // Update local state
            requests.value = requests.value.map(r => r.id === rejectingRequest.value!.id 
                ? { ...r, status: 'rejected', rejection_reason: rejectionReason.value } 
                : r
            );
            isRejectModalOpen.value = false;
            setTimeout(() => (flashMessage.value = null), 4000);
        },
        onError: () => {
            flashMessage.value = { type: 'error', text: 'Failed to reject request.' };
            setTimeout(() => (flashMessage.value = null), 4000);
        }
    });
};

</script>

<template>
<div class="flex h-screen bg-slate-50">
    <AppSidebar />
    <main class="relative flex-1 overflow-auto">

        <!-- Flash Message -->
        <transition name="fade-slide">
            <div v-if="flashMessage"
                class="fixed top-4 right-4 z-[9999] flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ flashMessage.text }}
            </div>
        </transition>

        <!-- Header + Filters -->
        <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
            <div class="flex items-center justify-between px-8 py-6">
                <h1 class="text-3xl font-extrabold text-slate-900">Ordinance Download Requests</h1>
            </div>

            <!-- Filters Section -->
            <div class="flex flex-col gap-4 px-8 pb-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                        <!-- Search Input -->
                        <div class="relative flex-1 max-w-md">
                            <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"/>
                            <input v-model="search"
                                @keyup.enter="applyFilters"
                                type="text"
                                placeholder="Search by user, ordinance, or purpose..."
                                class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"/>
                        </div>

                        <button v-if="search"
                            @click="clearFilters"
                            class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                            <X class="h-4 w-4"/>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="p-8">
            <div class="overflow-hidden rounded-lg border border-slate-100 bg-white shadow-lg">
                <div v-if="!requests || requests.length === 0" class="py-16 text-center text-slate-600">
                    No download requests found.
                </div>

                <table v-else class="w-full divide-y divide-slate-200">
                    <thead class="bg-slate-100/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">#</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">User</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Ordinance</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Purpose</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Requested At</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="(request, index) in requests" :key="request.id" class="transition-colors hover:bg-emerald-50/50">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ (paginationMeta?.from || 1) + index }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ request.user.name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ request.ordinance.title_ordinances }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ request.purpose }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ request.status }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(request.created_at) }}</td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <!-- Approve Button -->
                            <button 
                                v-if="request.status === 'pending'" 
                                @click="approveRequest(request.id)" 
                                class="flex h-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 px-3 text-xs font-semibold shadow-sm hover:bg-emerald-100">
                                Approve
                            </button>

                            <!-- Reject Button -->
                            <button 
                                v-if="request.status === 'pending'" 
                                @click="openRejectModal(request)" 
                                class="flex h-8 items-center justify-center rounded-full bg-red-50 text-red-600 px-3 text-xs font-semibold shadow-sm hover:bg-red-100">
                                Reject
                            </button>

                            <!-- Delete Button -->
                            <button 
                                @click="openDeleteDialog(request)" 
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-50 text-gray-600 shadow-sm hover:bg-gray-100">
                                <Trash2 class="h-4 w-4"/>
                            </button>
                        </td>


                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="paginationLinks && paginationLinks.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6">
                    <div class="text-sm text-slate-600">
                        Showing <span class="font-semibold">{{ paginationMeta?.from }}</span> to
                        <span class="font-semibold">{{ paginationMeta?.to }}</span> of
                        <span class="font-semibold">{{ paginationMeta?.total }}</span> results
                    </div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <component v-for="(link, key) in paginationLinks"
                            :key="key"
                            :is="link.url ? 'button' : 'span'"
                            @click="link.url ? paginate(link.url) : null"
                            :disabled="!link.url"
                            :class="[
                                'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset focus:z-20 transition-all',
                                key === 0 ? 'rounded-l-md' : '',
                                key === paginationLinks.length - 1 ? 'rounded-r-md' : '',
                                link.active ? 'bg-emerald-600 text-white ring-emerald-600' : link.url ? 'text-slate-900 hover:bg-slate-50' : 'cursor-not-allowed text-slate-400 bg-slate-100'
                            ]"
                            v-html="link.label"/>
                    </nav>
                </div>
            </div>
        </div>

       
    </main>
</div>

<!-- Rejection Modal -->
<div v-if="isRejectModalOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Reject Request</h2>
        <p class="text-sm text-slate-600 mb-4">
            Provide a reason for rejecting the request by <strong>{{ rejectingRequest?.user.name }}</strong> for ordinance <strong>{{ rejectingRequest?.ordinance.title_ordinances }}</strong>.
        </p>
        <textarea v-model="rejectionReason" rows="4" class="w-full rounded-lg border border-slate-300 p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter rejection reason"></textarea>

        <div class="mt-4 flex justify-end gap-3">
            <button @click="isRejectModalOpen = false" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
            <button @click="submitRejection" class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">Reject</button>
        </div>
    </div>
</div>

</template>

<style>
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
