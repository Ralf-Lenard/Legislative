<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import BookDialog from '@/components/Library/BookDialog.vue';
import DeleteBookDialog from '@/components/Library/DeleteBookDialog.vue';
import ViewBook from '@/components/Library/ViewBook.vue';
import FlashMessage from '@/components/Admin/FlashMessage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';
import { 
    Eye, Edit, Plus, Search, Trash2, X, 
    Book as BookIcon, BookOpen, Layers, 
    ChevronRight,
    ChevronLeft,
    MoreHorizontal,
    Image as ImageIcon 
} from 'lucide-vue-next';

// --- Interfaces ---
interface Book {
    id: number;
    title: string;
    author: string;
    category: string | null;
    published_year: number | null;
    image: string | null; 
    description?: string;
    created_at: string;
}

interface PaginatedBooks {
    data: Book[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}

// Defining the props received from Inertia
const props = defineProps<{
    books: PaginatedBooks;
    filters: { search?: string; category?: string };
    categoriesList: { category: string }[];
    recentlyAddedCount: number;
    totalBooksCount: number;
}>();

// --- Reactive State ---
const search = ref(props.filters?.search || '');
const categoryFilter = ref(props.filters?.category || '');

// Modal states
const isModalOpen = ref(false);
const editingBook = ref<Book | null>(null);
const isDeleteDialogOpen = ref(false);
const deletingBook = ref<Book | null>(null);
const isViewOpen = ref(false);
const selectedBook = ref<Book | null>(null);

// --- Computed Values ---
const booksList = computed(() => props.books?.data || []);
const uniqueCategories = computed(() => props.categoriesList || []);
const recentlyAdded = computed(() => props.recentlyAddedCount || 0);
const totalBooksCountDisplay = computed(() => props.totalBooksCount || 0);

/**
 * PAGINATION LOGIC: Handles 100+ pages by showing ellipsis
 */
const filteredLinks = computed(() => {
    const links = props.books.links;
    if (links.length <= 10) return links;

    const total = links.length;
    const current = links.findIndex(l => l.active);
    const result = [];

    // Always include Previous (index 0)
    result.push(links[0]);

    for (let i = 1; i < total - 1; i++) {
        // Always show first and last page number
        if (i === 1 || i === total - 2) {
            result.push(links[i]);
            continue;
        }

        // Show window around active page
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

// --- FILTER & NAVIGATION ---
const runFilter = () => {
    router.get(
        '/admin-library',
        {
            search: search.value,
            category: categoryFilter.value,
            page: 1
        },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true
        }
    );
};

const paginate = (url: string | null) => {
    if (url) {
        router.get(url, {}, {
            preserveScroll: true,
            preserveState: true,
        });
    }
};

const handleEnter = () => runFilter();
watch(categoryFilter, () => runFilter());

const clearFilters = () => {
    search.value = '';
    categoryFilter.value = '';
    router.get('/admin-library', {}, { replace: true });
};

// --- MODAL HANDLERS ---
const openModal = (book: Book | null = null) => {
    editingBook.value = book;
    isModalOpen.value = true;
};

const openViewModal = (book: Book) => {
    selectedBook.value = book;
    isViewOpen.value = true;
};

const openDeleteDialog = (book: Book) => {
    deletingBook.value = book;
    isDeleteDialogOpen.value = true;
};

const handleModalSubmit = () => {
    isModalOpen.value = false;
    router.reload({ preserveScroll: true });
};

onMounted(() => {
    window.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            isModalOpen.value = false;
            isDeleteDialogOpen.value = false;
            isViewOpen.value = false;
        }
    });
});
</script>

<template>
    <Head title="Library Management" />
    <div class="flex h-screen bg-slate-50">
        <AppSidebar />
        <main class="relative flex-1 overflow-auto">
            <FlashMessage />

            <div class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-md">
                <div class="flex items-center justify-between px-8 py-6">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Library Management</h1>
                        <p class="mt-1 text-sm text-slate-600">Manage your collection of books, references, and categories.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 px-8 pb-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-1 flex-col gap-4 md:flex-row md:items-center md:gap-3">
                            <div class="relative flex-1 max-w-md">
                                <Search class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 transform text-slate-400"/>
                                <input 
                                    v-model="search"
                                    type="text"
                                    placeholder="Search title or author..."
                                    @keydown.enter.prevent="handleEnter"
                                    class="w-full rounded-xl border border-slate-300 py-2.5 pr-4 pl-10 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>

                            <div class="relative w-full md:w-58">
                                <select 
                                    v-model="categoryFilter"
                                    class="w-full rounded-lg border border-slate-300 
                                            px-4 pr-10 py-2.5 outline-none
                                            focus:ring-2 focus:ring-emerald-500
                                            appearance-none bg-white">
                                    <option value="">All Categories</option>
                                    <option v-for="c in uniqueCategories" :key="c.category" :value="c.category">
                                        {{ c.category }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>

                            <button v-if="search || categoryFilter"
                                @click="clearFilters"
                                class="flex items-center gap-2 rounded-xl border border-slate-300 px-4 py-2.5 font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50">
                                <X class="h-4 w-4"/> Clear
                            </button>
                        </div>

                        <button @click="openModal()"
                            class="flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl">
                            <Plus class="h-5 w-5"/> Add New Book
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 px-8 pt-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center justify-between rounded-xl border-l-4 border-indigo-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Books</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ totalBooksCountDisplay }}</p>
                    </div>
                    <div class="rounded-full bg-indigo-50 p-3">
                        <BookIcon class="h-6 w-6 text-indigo-600"/>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-xl border-l-4 border-emerald-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Categories</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ uniqueCategories.length }}</p>
                    </div>
                    <div class="rounded-full bg-emerald-50 p-3">
                        <Layers class="h-6 w-6 text-emerald-600"/>
                    </div>
                </div>

                <div class="flex items-center justify-between rounded-xl border-l-4 border-amber-500 bg-white p-5 shadow-lg">
                    <div>
                        <p class="text-sm font-medium text-slate-500">New (Last 7 Days)</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ recentlyAdded }}</p>
                    </div>
                    <div class="rounded-full bg-amber-50 p-3">
                        <BookOpen class="h-6 w-6 text-amber-600"/>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="overflow-x-auto rounded-xl border border-slate-100 bg-white shadow-lg">
                    <div v-if="booksList.length === 0" class="py-16 text-center">
                        <BookIcon class="mx-auto h-12 w-12 text-slate-300 mb-4" />
                        <p class="text-lg font-semibold text-slate-700">No books found</p>
                        <p class="text-sm text-slate-500">Try changing your search or adding a new entry.</p>
                    </div>

                    <table v-else class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-100/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">#</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Cover</th>
                                <th class="min-w-[250px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Title</th>
                                <th class="min-w-[180px] px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Author</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-bold tracking-wider text-slate-700 uppercase">Year</th>
                                <th class="w-[120px] px-6 py-4 text-center text-xs font-bold tracking-wider text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr v-for="(book, index) in booksList" :key="book.id" class="transition-colors hover:bg-emerald-50/30">
                                <td class="px-6 py-4 text-sm text-slate-600">{{ (props.books.from || 1) + index }}</td>
                                <td class="px-6 py-4">
                                    <div class="h-12 w-10 overflow-hidden rounded-md border border-slate-200 bg-slate-50 shadow-sm">
                                        <img v-if="book.image" :src="book.image" class="h-full w-full object-cover" />
                                        <div v-else class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
                                            <ImageIcon class="h-4 w-4" />
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ book.title }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ book.author }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                        {{ book.category || 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ book.published_year || '—' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="openViewModal(book)" class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all shadow-sm">
                                            <Eye class="h-4 w-4"/>
                                        </button>
                                        <button @click="openModal(book)" class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-50 text-sky-600 hover:bg-sky-100 transition-all shadow-sm">
                                            <Edit class="h-4 w-4"/>
                                        </button>
                                        <button @click="openDeleteDialog(book)" class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm">
                                            <Trash2 class="h-4 w-4"/>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="props.books.links.length > 3" 
                         class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-200 bg-slate-50 px-6 py-4">
                        
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-900">{{ props.books.from }}</span> to 
                            <span class="font-bold text-slate-900">{{ props.books.to }}</span> of 
                            <span class="font-bold text-slate-900">{{ props.books.total }}</span>
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
                                            ? 'z-10 bg-emerald-600 text-white' 
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

            <ViewBook :is-open="isViewOpen" :book="selectedBook" @close="isViewOpen = false" />
            <BookDialog :is-open="isModalOpen" :book="editingBook" @close="isModalOpen = false" @submitted="handleModalSubmit" />
            <DeleteBookDialog :is-open="isDeleteDialogOpen" :book="deletingBook" @close="isDeleteDialogOpen = false" />
        </main>
    </div>
</template>