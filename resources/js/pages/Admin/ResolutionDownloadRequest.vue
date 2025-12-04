<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { router, usePage } from '@inertiajs/vue3';
import { Search, X, Trash2, Eye } from 'lucide-vue-next';
import { computed, ref, nextTick, onMounted, watch } from 'vue';

interface User {
  id: number;
  name: string;
  email: string;
}

interface Resolution {
  id: number;
  resolutions_number: string;
  title_resolutions: string;
}

interface ResolutionDownloadRequest {
  id: number;
  purpose: string;
  status: string;
  created_at: string;
  user: User;
  resolution: Resolution;
  // **FIX: Added rejection_reason for local state updates**
  rejection_reason?: string | null; 
}

interface PaginatedRequests {
  data: ResolutionDownloadRequest[];
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
  flash?: { success?: string; error?: string };
}>();

const flashMessage = ref<{ type: 'success' | 'error'; text: string } | null>(null);
const requests = ref<ResolutionDownloadRequest[]>([...(props.requests?.data || [])]);
const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
// **FIX: Removed the incomplete line here: st | null>(null);** const isDetailsModalOpen = ref(false);
const viewingRequest = ref<ResolutionDownloadRequest | null>(null);

const paginationMeta = computed(() => props.requests?.meta || null);
const paginationLinks = computed(() => props.requests?.links || []);

const paginate = (url: string) => {
  if (url) router.get(url, {}, { preserveScroll: true });
};

const applyFilters = () => {
  router.get(
    '/resolution-request',
    { search: search.value, status: statusFilter.value, page: 1 },
    { preserveState: false, replace: true }
  );
};

const clearFilters = () => {
  search.value = '';
  statusFilter.value = '';
  router.get('/resolution-request', { search: '', status: '', page: 1 }, { preserveState: false, replace: true });
};

const openDetailsModal = (request: ResolutionDownloadRequest) => {
  viewingRequest.value = request;
  isDetailsModalOpen.value = true;
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
  router.post(
    `/resolution-request/${id}/approve`,
    {},
    {
      preserveState: false,
      onSuccess: () => {
        // Use nextTick to ensure the DOM is updated before state changes, although not strictly necessary for this logic
        // For a more robust update, consider using Inertia's automatic prop refresh if preserveState is false.
        // Since preserveState is false, the page should reload and requests will be fetched fresh. 
        // The local state update below is for a non-page-reloading scenario, but is kept for local responsiveness.
        flashMessage.value = { type: 'success', text: 'Request approved.' };
        // The following line is mostly redundant if `preserveState: false` is used, as the page will reload.
        requests.value = requests.value.map((r) => (r.id === id ? { ...r, status: 'approved' } : r));
        setTimeout(() => (flashMessage.value = null), 4000);
      },
      onError: () => {
        flashMessage.value = { type: 'error', text: 'Failed to approve request.' };
        setTimeout(() => (flashMessage.value = null), 4000);
      }
    }
  );
};

const isRejectModalOpen = ref(false);
const rejectingRequest = ref<ResolutionDownloadRequest | null>(null);
const rejectionReason = ref('');

const openRejectModal = (request: ResolutionDownloadRequest) => {
  rejectingRequest.value = request;
  rejectionReason.value = '';
  isRejectModalOpen.value = true;
};

const submitRejection = () => {
  if (!rejectingRequest.value || !rejectionReason.value.trim()) return;
  router.post(
    `/resolution-request/${rejectingRequest.value.id}/reject`,
    { reason: rejectionReason.value },
    {
      preserveState: false,
      onSuccess: () => {
        // Similar note: this update is for local responsiveness but page reload via preserveState: false is the primary mechanism.
        flashMessage.value = { type: 'success', text: 'Request rejected.' };
        requests.value = requests.value.map((r) =>
          r.id === rejectingRequest.value!.id
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
    }
  );
};
</script>

<template>
    <div class="flex h-screen bg-slate-50">
      <AppSidebar />
      <main class="relative flex-1 overflow-auto">
  
        <transition name="fade-slide">
          <div
            v-if="flashMessage"
            :class="[
              'fixed top-4 right-4 z-[9999] flex items-center gap-2 rounded-lg px-5 py-3 text-sm font-medium text-white shadow-xl',
              flashMessage.type === 'success' ? 'bg-emerald-600' : 'bg-red-600'
            ]"
          >
            <svg
              v-if="flashMessage.type === 'success'"
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 24 24"
              stroke="currentColor"
              fill="none"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 24 24"
              stroke="currentColor"
              fill="none"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ flashMessage.text }}
          </div>
        </transition>
  
        <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
          <div class="flex items-center justify-between px-8 py-6">
            <h1 class="text-3xl font-extrabold text-slate-900">Resolution Requests</h1>
          </div>
  
          <div class="flex flex-col gap-4 px-8 pb-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                <div class="relative flex-1 max-w-sm">
                  <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"/>
                  <input
                    v-model="search"
                    @keyup.enter="applyFilters"
                    type="text"
                    placeholder="Search by user, resolution, or purpose..."
                    class="w-full rounded-lg border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                  />
                </div>
  
                <div class="relative">
                  <select
                    v-model="statusFilter"
                    @change="applyFilters"
                    class="w-full md:w-40 rounded-lg border border-slate-300 py-2.5 pr-4 pl-4 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-emerald-500 focus:outline-none appearance-none cursor-pointer"
                  >
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                  </select>
                  <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
  
                <button
                  v-if="search || statusFilter"
                  @click="clearFilters"
                  class="flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50"
                >
                  <X class="h-4 w-4"/>
                  Clear Filters
                </button>
              </div>
            </div>
          </div>
        </div>
  
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
                  <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase">Resolution</th>
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
                  <td class="px-6 py-4 text-sm text-slate-700">{{ request.resolution.title_resolutions }}</td>
                  <td class="px-6 py-4 text-sm text-slate-700">{{ request.purpose }}</td>
                  <td class="px-6 py-4 text-sm text-slate-600">
                    <span :class="[
                      'inline-flex items-center rounded-full px-3 py-0.5 text-xs font-medium',
                      request.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                      request.status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                      'bg-red-100 text-red-800'
                    ]">
                      {{ request.status.charAt(0).toUpperCase() + request.status.slice(1) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-600">{{ formatDate(request.created_at) }}</td>
                  <td class="px-6 py-4 text-center flex justify-center gap-2">
                    <button 
                      @click="openDetailsModal(request)"
                      class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600 shadow-sm hover:bg-blue-100"
                    >
                      <Eye class="h-4 w-4"/>
                    </button>
  
                    <button 
                      v-if="request.status === 'pending'" 
                      @click="approveRequest(request.id)" 
                      class="flex h-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 px-3 text-xs font-semibold shadow-sm hover:bg-emerald-100"
                    >
                      Approve
                    </button>
  
                    <button 
                      v-if="request.status === 'pending'" 
                      @click="openRejectModal(request)" 
                      class="flex h-8 items-center justify-center rounded-full bg-red-50 text-red-600 px-3 text-xs font-semibold shadow-sm hover:bg-red-100"
                    >
                      Reject
                    </button>
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
                  v-html="link.label"
                />
              </nav>
            </div>
          </div>
        </div>
  
      </main>
    </div>
  
    <div v-if="isRejectModalOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
      <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Reject Request</h2>
        <p class="text-sm text-slate-600 mb-4">
          Provide a reason for rejecting the request by <strong>{{ rejectingRequest?.user.name }}</strong> for resolution <strong>{{ rejectingRequest?.resolution.title_resolutions }}</strong>.
        </p>
        <textarea
          v-model="rejectionReason"
          rows="4"
          class="w-full rounded-lg border border-slate-300 p-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500"
          placeholder="Enter rejection reason"
        ></textarea>
        <div class="mt-4 flex justify-end gap-3">
          <button @click="isRejectModalOpen = false" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
          <button @click="submitRejection" class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white hover:bg-red-700">Reject</button>
        </div>
      </div>
      <div @click.self="isRejectModalOpen = false" class="fixed inset-0 z-[9998]"></div>
    </div>
  
    <div v-if="isDetailsModalOpen" @click.self="isDetailsModalOpen = false" class="fixed inset-0 z-[9998] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
      <div class="w-full max-w-3xl rounded-lg bg-white p-8 shadow-2xl relative">
        <div class="flex items-center justify-between border-b pb-3 mb-6">
          <h2 class="text-2xl font-bold text-slate-900">Request Details (ID: {{ viewingRequest?.id }})</h2>
          <button @click="isDetailsModalOpen = false" class="text-slate-400 hover:text-slate-600 transition">
            <X class="h-6 w-6"/>
          </button>
        </div>
  
        <div v-if="viewingRequest" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
          <div>
            <h3 class="text-lg font-semibold text-emerald-700 border-b mb-3">Request Summary</h3>
            <dl class="space-y-2 text-sm">
              <div class="flex">
                <dt class="font-medium text-slate-600 w-24">Status:</dt>
                <dd class="text-slate-800">
                  <span :class="[
                    'inline-flex items-center rounded-full px-3 py-0.5 text-xs font-medium',
                    viewingRequest.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                    viewingRequest.status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                    'bg-red-100 text-red-800'
                  ]">
                    {{ viewingRequest.status.charAt(0).toUpperCase() + viewingRequest.status.slice(1) }}
                  </span>
                </dd>
              </div>
              <div class="flex">
                <dt class="font-medium text-slate-600 w-24">Requested:</dt>
                <dd class="text-slate-800">{{ formatDate(viewingRequest.created_at) }}</dd>
              </div>
              <div v-if="viewingRequest.status === 'rejected' && viewingRequest.rejection_reason" class="col-span-2">
                <dt class="font-medium text-slate-600">Rejection Reason:</dt>
                <dd class="text-red-700 text-sm leading-relaxed whitespace-pre-line bg-red-50 p-3 rounded-lg border border-red-200 mt-1">
                  {{ viewingRequest.rejection_reason }}
                </dd>
              </div>
            </dl>
  
            <h3 class="text-lg font-semibold text-emerald-700 border-b mb-3 mt-6">Requesting User</h3>
            <dl class="space-y-2 text-sm">
              <div class="flex">
                <dt class="font-medium text-slate-600 w-24">Name:</dt>
                <dd class="text-slate-800">{{ viewingRequest.user.name }}</dd>
              </div>
              <div class="flex">
                <dt class="font-medium text-slate-600 w-24">Email:</dt>
                <dd class="text-slate-800">{{ viewingRequest.user.email }}</dd>
              </div>
            </dl>
          </div>
  
          <div>
            <h3 class="text-lg font-semibold text-emerald-700 border-b mb-3">Resolution Details</h3>
            <dl class="space-y-2 text-sm mb-6">
              <div class="flex">
                <dt class="font-medium text-slate-600 w-32">Resolution No.:</dt>
                <dd class="text-slate-800 font-mono">{{ viewingRequest.resolution.resolutions_number }}</dd>
              </div>
              <div>
                <dt class="font-medium text-slate-600">Title:</dt>
                <dd class="text-slate-800 leading-relaxed">{{ viewingRequest.resolution.title_resolutions }}</dd>
              </div>
            </dl>
  
            <h3 class="text-lg font-semibold text-emerald-700 border-b mb-3">Purpose for Download</h3>
            <p class="text-slate-800 text-sm leading-relaxed whitespace-pre-line bg-slate-50 p-3 rounded-lg border border-slate-200">
              {{ viewingRequest.purpose }}
            </p>
          </div>
        </div>
  
        <div class="mt-8 pt-4 border-t border-slate-200 flex justify-end gap-3">
          <button 
            v-if="viewingRequest.status === 'pending'" 
            @click="approveRequest(viewingRequest.id); isDetailsModalOpen = false;" 
            class="rounded-lg bg-emerald-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-emerald-700"
          >
            Approve Request
          </button>
  
          <button 
            v-if="viewingRequest.status === 'pending'" 
            @click="openRejectModal(viewingRequest); isDetailsModalOpen = false;" 
            class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white shadow-sm hover:bg-red-700"
          >
            Reject Request
          </button>
  
          <button @click="isDetailsModalOpen = false" class="rounded-lg bg-slate-100 px-4 py-2 font-medium text-slate-700 hover:bg-slate-200">Close</button>
        </div>
      </div>
    </div>
  </template>
  
<style>
/* CSS for slide transition */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>