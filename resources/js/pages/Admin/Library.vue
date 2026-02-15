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

// --- Props ---
const props = defineProps<{
    books: PaginatedBooks;
    filters: { search?: string; category?: string };
    categoriesList: { category: string }[];
    recentlyAddedCount: number;
    totalBooksCount: number; // <--- Add this line
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

// --- Computed (THE FIX) ---
// By using computed, the UI will automatically react when props.books changes
const booksList = computed(() => props.books?.data || []);
const paginationMeta = computed(() => props.books?.meta || null);
const paginationLinks = computed(() => props.books?.links || []);
const uniqueCategories = computed(() => props.categoriesList || []);
const recentlyAdded = computed(() => props.recentlyAddedCount || 0);
const totalBooksCount = computed(() => props.totalBooksCount ?? props.books?.meta?.total ?? 0);

// --- FILTER FUNCTION ---
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

// --- PAGINATION ---
const paginate = (url: string) => {
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

// --- MODALS ---
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

                            <div class="w-full md:w-56">
                                <select 
                                    v-model="categoryFilter"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm transition-all focus:border-transparent focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                >
                                    <option value="">All Categories</option>
                                    <option v-for="c in uniqueCategories" :key="c.category" :value="c.category">
                                        {{ c.category }}
                                    </option>
                                </select>
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
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ totalBooksCount }}</p>
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
                            <tr v-for="(book, index) in booksList" :key="book.id" class="transition-colors hover:bg-indigo-50/30">
                                <td class="px-6 py-4 text-sm text-slate-600">{{ (paginationMeta?.from || 1) + index }}</td>
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
                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
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

                    <div v-if="paginationLinks.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6">
                        <div class="text-sm text-slate-600">
                            Showing <span class="font-semibold">{{ paginationMeta?.from }}</span> to <span class="font-semibold">{{ paginationMeta?.to }}</span> of <span class="font-semibold">{{ paginationMeta?.total }}</span> results
                        </div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                            <button v-for="(link, key) in paginationLinks"
                                :key="key"
                                @click="link.url ? paginate(link.url) : null"
                                v-html="link.label"
                                :disabled="!link.url"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset transition-all',
                                    key === 0 ? 'rounded-l-lg' : '',
                                    key === paginationLinks.length - 1 ? 'rounded-r-lg' : '',
                                    link.active ? 'z-10 bg-emerald-600 text-white ring-emerald-600' : link.url ? 'text-slate-700 hover:bg-slate-100' : 'text-slate-300 bg-slate-50 cursor-not-allowed'
                                ]"
                            />
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