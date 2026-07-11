<template>
    <Head title="Library" />
    <div class="bg-white min-h-screen">
      <Navbar />
  
       <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">
          <div class="text-left">
            <span class="inline-block text-[10px] md:text-xs tracking-[0.3em] uppercase bg-yellow-400 text-green-950 px-6 py-2 rounded-full font-black mb-8">
               Digital Resources
            </span>
            <h1 class="text-4xl md:text-7xl font-black text-white leading-none tracking-tighter mb-8 uppercase">
              Municipal <span class="text-yellow-400 italic font-serif">Library</span>
            </h1>
            <p class="max-w-xl text-green-50/80 text-lg md:text-xl font-medium leading-relaxed">
             Explore our collection of books and research materials.
            </p>
          </div>
        </div>
      </div>
    </section>
  
      <section class="py-10 sm:py-14 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
  
          <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-white text-black">
  
              <div class="lg:col-span-2">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                  Search Books
                </label>
                <input
                  v-model="filters.search"
                  type="text"
                  @keyup.enter="applyFilters"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                        bg-white text-black
                        focus:ring-2 focus:ring-green-800 focus:border-green-800 transition"
                  placeholder="Title, Author, Category..."
                />
              </div>

              <div>
                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                  Year
                </label>
                <select
                  v-model="filters.year"
                  class="w-full px-4 pr-10 py-2.5 border border-gray-300 rounded-lg 
                          bg-white text-black
                          focus:ring-2 focus:ring-green-800 focus:border-green-800 
                          appearance-none custom-select transition"
                >
                  <option value="">All Years</option>
                  <option v-for="year in years" :key="year" :value="year">
                    {{ year }}
                  </option>
                </select>
              </div>

              <div class="flex items-end">
                <button
                  @click="applyFilters"
                  class="w-full h-11 bg-green-800 text-white font-bold rounded-lg shadow hover:bg-green-900 transition"
                >
                  Apply Filters
                </button>
              </div>

            </div>
          </div>
  
          <div
            v-if="books.data.length"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-10 sm:mt-12"
          >
            <div
              v-for="book in books.data"
              :key="book.id"
              class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-2xl transition duration-300 flex flex-col justify-between h-full group"
            >
              <!-- Content Section wraps top elements -->
              <div class="p-5 flex flex-col flex-grow">
                <span class="text-[10px] font-bold text-green-700 uppercase tracking-wider">
                  {{ book.category }}
                </span>
  
                <h2 class="text-sm sm:text-md font-bold text-gray-900 line-clamp-2 mt-1">
                  {{ book.title }}
                </h2>
  
                <p class="text-[11px] text-gray-500 font-medium mt-2">
                  👤 {{ book.author }}
                </p>
              </div>

              <!-- Button Container locks neatly to the bottom -->
              <div class="px-5 pb-5 pt-0">
                <button
                  @click="openModal(book)"
                  class="w-full py-2 text-xs font-bold uppercase tracking-wider text-white bg-green-800 rounded-lg hover:bg-yellow-500 hover:text-green-900 transition"
                >
                  View Details
                </button>
              </div>
            </div>
          </div>
  
          <div
            v-if="books.data.length"
            class="mt-16 flex justify-center"
          >
            <nav class="flex items-center gap-4">
              <template v-if="books.links[0]">
                <Link
                  v-if="books.links[0].url"
                  :href="books.links[0].url"
                  class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 transition-colors flex items-center justify-center"
                >
                  ← Prev
                </Link>
                <span
                  v-else
                  class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 bg-white text-gray-400 cursor-default flex items-center justify-center"
                >
                  ← Prev
                </span>
              </template>
  
              <div class="text-sm font-bold text-gray-700">
                {{ books.current_page }} of {{ books.last_page }}
              </div>
  
              <template v-if="books.links[books.links.length - 1]">
                <Link
                  v-if="books.links[books.links.length - 1].url"
                  :href="books.links[books.links.length - 1].url"
                  class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 transition-colors flex items-center justify-center"
                >
                  Next →
                </Link>
                <span
                  v-else
                  class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 bg-white text-gray-400 cursor-default flex items-center justify-center"
                >
                  Next →
                </span>
              </template>
            </nav>
          </div>
  
          <div v-else class="mt-16 text-center text-gray-500 font-semibold">
            No books found in the library.
          </div>
  
        </div>
      </section>
  
      <!-- RESPONSIVE MODAL -->
      <div
        v-if="isModalOpen"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
      >
        <!-- Neutral Background Backdrop (Removed Green Color) -->
        <div
          class="fixed inset-0 bg-black/65 backdrop-blur-md"
          @click="closeModal"
        ></div>
  
        <div
          class="relative bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto flex flex-col"
        >
          <!-- CLOSE BUTTON -->
          <button
            @click="closeModal"
            class="absolute top-4 right-4 z-50 w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-red-500 hover:text-white transition"
          >
            ✕
          </button>
  
          <!-- CONTENT -->
          <div class="w-full p-6 sm:p-10 flex flex-col">
            
            <span class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full uppercase tracking-widest w-fit">
              {{ selectedBook.category }}
            </span>
  
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 mt-4">
              {{ selectedBook.title }}
            </h2>
  
            <p class="text-md sm:text-lg text-green-800 font-semibold mt-2">
              By {{ selectedBook.author }}
            </p>
  
            <div class="flex gap-8 border-y border-gray-100 py-4 my-6 text-sm">
              <div>
                <p class="text-gray-400 uppercase text-[10px] font-bold">Released</p>
                <p class="font-bold text-gray-800">
                  {{ selectedBook.published_year }}
                </p>
              </div>
            </div>
  
            <div class="flex-grow">
              <p class="text-sm text-gray-400 uppercase font-bold mb-3">
                Summary
              </p>
              <p class="text-gray-600 text-sm sm:text-base leading-relaxed italic">
                "{{ selectedBook.description }}"
              </p>
            </div>
  
            <!-- BUTTON -->
            <div class="mt-8 flex justify-center md:justify-end">
              <button
                @click="closeModal"
                class="w-full md:w-auto px-8 py-3 bg-green-800 text-white font-bold rounded-xl hover:bg-green-700 transition"
              >
                Close Preview
              </button>
            </div>
  
          </div>
        </div>
      </div>
  
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Footer from '@/components/Home/Footer.vue'
  import Navbar from '@/components/Home/Navbar.vue'
  import { Head, router, usePage, Link } from '@inertiajs/vue3'
  import { computed, reactive, ref } from 'vue'
  
  const page = usePage()
  const books = computed(() => page.props.books)
  const years = computed(() => page.props.years)
  
  const filters = reactive({
    search: page.props.filters?.search ?? '',
    year: page.props.filters?.year ?? '',
  })
  
  const isModalOpen = ref(false)
  const selectedBook = ref(null)
  
  const openModal = (book) => {
    selectedBook.value = book
    isModalOpen.value = true
    document.body.style.overflow = 'hidden'
  }
  
  const closeModal = () => {
    isModalOpen.value = false
    selectedBook.value = null
    document.body.style.overflow = 'auto'
  }
  
  const applyFilters = () => {
    router.get("/library", filters, { 
        preserveState: true, 
        replace: true, 
    });
  };
  </script>


<style scoped>
.custom-select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
  background-position: right 0.75rem center;
  background-repeat: no-repeat;
  background-size: 1rem;
}
</style>