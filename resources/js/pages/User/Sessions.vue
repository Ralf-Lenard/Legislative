<template>
  <Head title="Sessions" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <section
      class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden"
    >
      <div
        class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl"
      ></div>

      <div
        class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10"
      >
        <div class="flex-1 text-white relative z-10">
          <span
            class="text-xs tracking-widest uppercase bg-yellow-400 text-green-900 px-4 py-1 rounded-full font-bold"
          >
            Official Website
          </span>

          <h1 class="text-5xl md:text-6xl font-extrabold mt-4 leading-tight">
            Legislative Sessions
          </h1>

          <p class="text-lg mt-3 text-gray-100">
            Records of official meetings and deliberations.
          </p>
        </div>

        <div class="hidden lg:block">
          <div class="relative w-full h-96 rounded-2xl overflow-hidden shadow-2xl">
            <img
              src="https://upload.wikimedia.org/wikipedia/commons/c/c0/Concepcion_Tarlac_Municipal_Hall_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg"
              alt="Concepcion Municipal Hall"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-semibold"
            >
              Concepcion, Tarlac
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-14 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
          <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end"
          >
            <div class="lg:col-span-2">
              <label class="text-sm font-semibold text-gray-700 mb-2 block">
                Search
              </label>
              <input
                v-model="filters.search"
                type="text"
                @keyup.enter="applyFilters"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 transition"
                placeholder="Session No., Title, Keyword..."
              />
            </div>

            <div>
              <label class="text-sm font-semibold text-gray-700 mb-2 block">
                Year
              </label>
              <select
                v-model="filters.year"
                @change="applyFilters"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 appearance-none custom-select transition"
              >
                <option value="">All Years</option>
                <option
                  v-for="year in years"
                  :key="year"
                  :value="year"
                >
                  {{ year }}
                </option>
              </select>
            </div>

            <button
              @click="applyFilters"
              class="w-full h-11 bg-green-800 text-white font-bold rounded-lg shadow hover:bg-green-900 transition mt-4 sm:mt-0"
            >
              Apply Filters
            </button>
          </div>
        </div>

        <div
          v-if="sessions.data.length"
          class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12"
        >
          <div
            v-for="session in sessions.data"
            :key="session.id"
            class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition"
          >
            <div class="bg-green-800 text-white px-5 py-2 font-semibold text-sm">
              Session No. {{ session.session_number }}
            </div>

            <div class="p-5 space-y-4">
              <h2 class="text-lg font-bold text-gray-900 line-clamp-2">
                {{ session.session_title }}
              </h2>

              <div
                class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium pt-2 border-t border-gray-100"
              >
                <span class="text-green-800 flex items-center gap-1">
                  🗓️ {{ formatDate(session.date_of_session) }}
                </span>

                <span class="text-green-800 flex items-center gap-1">
                  📌 {{ session.session_type }}
                </span>
              </div>

              <a
                :href="`/session-details/${session.id}`"
                class="inline-block px-6 py-2 text-sm text-white font-bold bg-green-800 rounded-lg hover:bg-green-700 transition shadow-md"
              >
                View Session Details
              </a>
            </div>
          </div>
        </div>

        <div
          v-else
          class="mt-20 text-center text-gray-500 font-semibold"
        >
          No legislative sessions found.
        </div>

        <div class="mt-16 flex justify-center">
          <nav class="flex items-center gap-4">
          <template v-if="sessions.links[0]">
            <a
              v-if="sessions.links[0].url"
              :href="sessions.links[0].url"
              class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 transition-colors flex items-center justify-center"
            >
              ← Prev
            </a>
            <span
              v-else
              class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 bg-white text-gray-400 cursor-default flex items-center justify-center"
            >
              ← Prev
            </span>
          </template>

          <div class="text-sm font-bold text-gray-700">
            {{ sessions.current_page }} of {{ sessions.last_page }}
          </div>

          <template v-if="sessions.links[sessions.links.length - 1]">
            <a
              v-if="sessions.links[sessions.links.length - 1].url"
              :href="sessions.links[sessions.links.length - 1].url"
              class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 transition-colors flex items-center justify-center"
            >
              Next →
            </a>
            <span
              v-else
              class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 bg-white text-gray-400 cursor-default flex items-center justify-center"
            >
              Next →
            </span>
          </template>
        </nav>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import { computed, reactive } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";

import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";

const page = usePage();

const sessions = computed(() => page.props.sessions);
const years = computed(() => page.props.years);

const filters = reactive({
  search: page.props.filters?.search ?? "",
  year: page.props.filters?.year ?? "",
});

const applyFilters = () => {
  router.get('/sessions', filters, {
    preserveScroll: true,
    preserveState: true,
  });
};

const goTo = (url) => {
  router.get(url, {}, { preserveScroll: true });
};

const formatDate = (date) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};
</script>

<style scoped>
.custom-select {
  appearance: none;
  background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" fill="%234B5563" viewBox="0 0 20 20"><path d="M5.5 7l4.5 4.5L14.5 7z"/></svg>');
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 0.75em;
}
</style>