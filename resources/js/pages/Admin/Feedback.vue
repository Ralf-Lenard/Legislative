<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import FeedbackDetailsModal from '@/components/FeedbackModal/FeedbackDetailsModal.vue';
import FeedbackDeleteModal from '@/components/FeedbackModal/Delete.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    MessageSquare,
    Search,
    Trash2,
    X,
    ChevronRight,
    ChevronLeft,
    MoreHorizontal,
    Mail,
    Filter,
    Inbox,
    CheckCircle,
    UserRound,
    UserX,
    Eye
} from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

/* =======================
       TYPES
    ======================= */

interface Feedback {
    id: number;
    user_id: number | null;
    category: 'suggestion' | 'concern' | 'commendation' | 'inquiry' | 'other';
    message: string;
    created_at: string;
    user?: {
        name: string;
        email: string;
    };
}

interface PaginatedFeedback {
    data: Feedback[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}

/* =======================
       INERTIA PROPS
    ======================= */

const page = usePage<{
    feedback: PaginatedFeedback;
    filters: { search?: string; category?: string };
}>();

/* =======================
       STATE & LOGIC
    ======================= */

const search = ref(page.props.filters?.search || '');
const categoryFilter = ref(page.props.filters?.category || '');

const feedbackList = computed(() => page.props.feedback?.data || []);

// Pagination Logic (Matches your Session style)
const filteredLinks = computed(() => {
    const links = page.props.feedback.links;
    if (links.length <= 10) return links;
    const total = links.length;
    const current = links.findIndex(l => l.active);
    const result = [];
    result.push(links[0]);
    for (let i = 1; i < total - 1; i++) {
        if (i === 1 || i === total - 2) { result.push(links[i]); continue; }
        if (i >= current - 1 && i <= current + 1) { result.push(links[i]); continue; }
        if (result[result.length - 1].label !== '...') {
            result.push({ url: null, label: '...', active: false });
        }
    }
    result.push(links[total - 1]);
    return result;
});

/* =======================
       METHODS
    ======================= */

const runNavigation = (pageNumber: string | number | null = 1) => {
    router.get('/admin/feedback', 
        { search: search.value, category: categoryFilter.value, page: pageNumber },
        { preserveState: true, preserveScroll: true, only: ['feedback', 'filters'] }
    );
};

const clearFilters = () => {
    search.value = '';
    categoryFilter.value = '';
    router.get('/admin/feedback', {}, { replace: true });
};

const paginate = (url: string | null) => {
    if (!url) return;
    const urlObj = new URL(url, window.location.origin);
    runNavigation(urlObj.searchParams.get('page'));
};

const deleteFeedback = (id: number) => {
    if (confirm('Are you sure you want to delete this feedback?')) {
        router.delete(`/admin/feedback/${id}`, { preserveScroll: true });
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
    });
};

const getCategoryClass = (cat: string) => {
    const map: Record<string, string> = {
        suggestion: 'bg-blue-100 text-blue-700',
        concern: 'bg-red-100 text-red-700',
        commendation: 'bg-emerald-100 text-emerald-700',
        inquiry: 'bg-amber-100 text-amber-700',
        other: 'bg-slate-100 text-slate-700'
    };
    return map[cat] || map.other;
};

watch(categoryFilter, () => runNavigation(1));

const isModalOpen = ref(false);
const selectedFeedback = ref<Feedback | null>(null);

const openDetails = (item: Feedback) => {
    selectedFeedback.value = item;
    isModalOpen.value = true;
};

const isDeleteModalOpen = ref(false);
const feedbackToDelete = ref(null);

const openDeleteModal = (item) => {
    feedbackToDelete.value = item;
    isDeleteModalOpen.value = true;
};
</script>
<template>
    <Head title="Feedback Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <!-- Header Section -->
            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Citizen Feedback</h1>
                        <p class="mt-1 text-sm text-slate-600">Review and manage anonymous or user-submitted messages.</p>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center">
                        <div class="relative max-w-md flex-1">
                            <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-400" />
                            <input v-model="search" @keydown.enter="runNavigation(1)" type="text"
                                placeholder="Search messages..."
                                class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm bg-white text-slate-900 placeholder-slate-400" />
                        </div>

                        <select v-model="categoryFilter" 
                            class="w-full md:w-48 rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none bg-white text-slate-900 shadow-sm">
                            <option value="">All Categories</option>
                            <option value="suggestion">Suggestions</option>
                            <option value="concern">Concerns</option>
                            <option value="commendation">Commendations</option>
                            <option value="inquiry">Inquiries</option>
                        </select>

                        <button v-if="search || categoryFilter" @click="clearFilters"
                            class="flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                            <X class="h-4 w-4" /> Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center justify-between rounded-xl border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Inbox</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ page.props.feedback.total }}</p>
                    </div>
                    <Inbox class="h-8 w-8 text-indigo-500 opacity-60" />
                </div>
                <div class="flex items-center justify-between rounded-xl border-l-4 border-rose-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Critical Concerns</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ feedbackList.filter(f => f.category === 'concern').length }}
                        </p>
                    </div>
                    <Filter class="h-8 w-8 text-rose-500 opacity-60" />
                </div>
                <div class="flex items-center justify-between rounded-xl border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Commendations</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ feedbackList.filter(f => f.category === 'commendation').length }}
                        </p>
                    </div>
                    <CheckCircle class="h-8 w-8 text-emerald-500 opacity-60" />
                </div>
            </div>

            <!-- Table Section -->
            <div class="p-8">
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
                    <div v-if="!feedbackList.length" class="py-20 text-center">
                        <MessageSquare class="mx-auto h-12 w-12 text-slate-300 mb-4" />
                        <p class="text-lg font-semibold text-slate-700">No feedback found</p>
                    </div>

                    <table v-else class="min-w-full divide-y divide-slate-200 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Sender</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Category</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Message</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-700">Date</th>
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="item in feedbackList" :key="item.id" class="hover:bg-indigo-50/30 transition-colors bg-white">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border shadow-sm"
                                            :class="item.user ? 'bg-indigo-50 border-indigo-100 text-indigo-600' : 'bg-slate-100 border-slate-200 text-slate-400'">
                                            
                                            <template v-if="item.user">
                                                <span class="text-sm font-bold uppercase">{{ item.user.name.charAt(0) }}</span>
                                            </template>
                                            <template v-else>
                                                <UserX class="h-5 w-5" />
                                            </template>
                                        </div>

                                        <div class="flex flex-col">
                                            <template v-if="item.user">
                                                <span class="text-sm font-bold text-slate-900 line-clamp-1">{{ item.user.name }}</span>
                                                <span class="text-xs text-slate-500 line-clamp-1">{{ item.user.email }}</span>
                                            </template>
                                            <template v-else>
                                                <span class="text-sm font-bold text-slate-400 italic">Anonymous Citizen</span>
                                                <span class="text-[10px] text-slate-400 uppercase tracking-tighter">No Account Linked</span>
                                            </template>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="getCategoryClass(item.category)" class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                        {{ item.category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <p class="max-w-md line-clamp-2 leading-relaxed">{{ item.message }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                    {{ formatDate(item.created_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openDetails(item)"
                                            class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors shadow-sm"
                                            title="View Details">
                                            <Eye class="h-4 w-4" />
                                        </button>
                                        <button @click="openDeleteModal(item)" 
                                            class="h-9 w-9 inline-flex items-center justify-center rounded-full bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors shadow-sm">
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="page.props.feedback.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ page.props.feedback.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ page.props.feedback.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ page.props.feedback.total }}</span>
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
                                            : 'text-slate-500 bg-white hover:bg-slate-50 hover:text-emerald-600',
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

    <!-- Modals -->
    <FeedbackDetailsModal 
        :is-open="isModalOpen" 
        :feedback="selectedFeedback" 
        @close="isModalOpen = false"
        @delete="(id) => { isModalOpen = false; deleteFeedback(id); }"
    />

    <FeedbackDeleteModal 
        :is-open="isDeleteModalOpen" 
        :feedback="feedbackToDelete" 
        @close="isDeleteModalOpen = false" 
    />
</template>