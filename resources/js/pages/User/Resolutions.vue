<template>
  <Head title="Resolutions" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <FlashMessage />

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
            Municipal Resolutions
          </h1>

          <p class="text-lg mt-3 text-gray-100">
            Formal expressions of the will and opinion of the municipality.
          </p>
        </div>

          <div class="hidden lg:block">
            <div class="relative w-full h-96 rounded-2xl overflow-hidden shadow-2xl">
              <picture>
                <source media="(min-width:1024px)" srcset="https://upload.wikimedia.org/wikipedia/commons/7/78/Concepcion_Municipal_Hall%2C_Tarlac%2C_Oct_2023.jpg">
                <source media="(min-width:640px)" srcset="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg/1024px-Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c0/Concepcion_Tarlac_Municipal_Hall_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg" 
                  alt="Concepcion Municipal Hall" 
                  class="w-full h-full object-cover">
              </picture>
              <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-semibold">
                Concepcion, Tarlac
              </div>
            </div>
          </div>
      </div>
    </section>

    <section class="py-14 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
          <form
            @submit.prevent="applyFilters"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end"
          >
            <div class="lg:col-span-2">
              <label class="text-sm font-semibold text-gray-700 mb-2 block"
                >Search Filter</label
              >
              <div class="relative">
                <input
                  v-model="form.search"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 transition"
                  placeholder="Resolution No., Title, or Keyword..."
                />
              </div>
            </div>

            <div>
              <label class="text-sm font-semibold text-gray-700 mb-2 block">Year</label>
              <select
                v-model="form.year"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 appearance-none custom-select transition"
              >
                <option value="">All Years</option>
                <option v-for="year in years" :key="year">{{ year }}</option>
              </select>
            </div>

            <button
              type="submit"
              class="w-full h-11 bg-green-800 text-white font-bold rounded-lg shadow hover:bg-green-900 transition mt-4 sm:mt-0"
            >
              Apply Filters
            </button>
          </form>
        </div>

        <div
          v-if="resolutions.data.length"
          class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12"
        >
          <div
            v-for="resolution in resolutions.data"
            :key="resolution.id"
            class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
          >
            <div class="bg-green-800 text-white px-5 py-2 font-semibold text-sm">
              Resolution No. {{ resolution.resolutions_number }}
            </div>

            <div class="p-5 space-y-4">
              <h2 class="text-lg font-bold text-gray-900 line-clamp-2">
                {{ resolution.title_resolutions }}
              </h2>

              <p class="text-gray-600 text-sm line-clamp-3">
                {{ resolution.description_resolutions }}
              </p>

              <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium pt-2 border-t border-gray-100">
                <span class="text-green-800 flex items-center gap-1">
                  <span class="text-sm">🗓️</span>
                    Series: {{ new Date(resolution.date_approved_resolutions).getFullYear() }}
                </span>
              </div>

              <div class="flex justify-between items-center gap-3 pt-3">
                <button
                  @click="openModal(resolution)"
                  class="px-4 py-2 text-sm text-green-800 font-bold border border-green-800 rounded-lg hover:bg-green-50 transition"
                >
                  View Details
                </button>

                <button
                  v-if="resolution.status === 'approved'"
                  @click="handleDownloadClick(resolution)"
                  class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
                >
                  Download PDF
                </button>

                <button
                  v-else-if="resolution.status === 'pending'"
                  disabled
                  class="px-4 py-2 text-sm bg-gray-300 text-gray-600 font-bold rounded-lg cursor-not-allowed"
                >
                  Request Pending
                </button>

                <button
                  v-else
                  @click="openRequestModal(resolution)"
                  class="px-4 py-2 text-sm bg-green-800 text-white font-bold rounded-lg hover:bg-green-900 transition shadow-md"
                >
                  Request Access
                </button>
              </div>
            </div>
          </div>
        </div>

        <div
          v-else
          class="text-center mt-16 bg-white p-10 rounded-xl shadow border border-gray-200"
        >
          <h3 class="text-2xl font-bold text-gray-700">
            No Resolutions Found
          </h3>
          <p class="mt-2 text-gray-500">
            Try adjusting your searches or filters.
          </p>

          <button
            @click="clearFilters"
            class="mt-6 bg-green-800 text-white px-5 py-2.5 rounded-lg hover:bg-green-900 font-semibold"
          >
            Clear Filters
          </button>
        </div>

        <div
          v-if="resolutions.data.length"
          class="mt-16 flex justify-center"
        >
          <nav class="flex gap-2">
            <template
              v-for="(link, i) in resolutions.links"
              :key="i"
            >
              <a
                v-if="link.url"
                :href="link.url"
                :class="[
                  'h-10 w-10 sm:w-auto px-4 py-2 rounded-lg text-sm font-semibold border transition-colors duration-200 flex items-center justify-center',
                  link.active
                    ? 'bg-green-800 text-white border-green-800 shadow-md'
                    : 'border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900'
                ]"
                v-html="link.label.replace('Previous', '← Prev').replace('Next', 'Next →')"
              ></a>
              
              <span
                v-else
                :class="[
                  'h-10 w-10 sm:w-auto px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 cursor-default flex items-center justify-center',
                  link.active ? 'bg-green-800 text-white border-green-800' : 'bg-white text-gray-400'
                ]"
                v-html="link.label.replace('Previous', '← Prev').replace('Next', 'Next →')"
              ></span>
            </template>
          </nav>
        </div>
      </div>
    </section>

    <transition name="modal">
      <div
        v-if="selectedResolution"
        class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 p-4"
        @click.self="closeModal"
      >
        <div
          class="bg-white w-full max-w-2xl rounded-xl shadow-xl border border-gray-200 relative flex flex-col overflow-hidden"
          style="max-height: 90vh;"
        >
          <div class="overflow-y-auto px-6 py-6 flex-1 custom-scrollbar">
            <button
              class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
              @click="closeModal"
            >
              ✕
            </button>

            <h2 class="text-2xl font-bold text-green-900">
              Resolution No. {{ selectedResolution.resolutions_number }}
            </h2>

            <h3 class="text-lg font-semibold mt-2">
              {{ selectedResolution.title_resolutions }}
            </h3>

            <div class="flex flex-col gap-1 mt-2">
                <p class="text-sm text-gray-600">
                    Date Approved:
                    <strong>{{ formatDate(selectedResolution.date_approved_resolutions) }}</strong>
                </p>
                <p v-if="selectedResolution.author_resolutions" class="text-sm text-gray-600">
                    Author: <strong>{{ selectedResolution.author_resolutions }}</strong>
                </p>
            </div>

            <div
              class="mt-6 rounded-lg overflow-hidden border border-gray-200 shadow"
              v-if="selectedResolution.image_resolutions"
            >
              <img
                :src="`/storage/${selectedResolution.image_resolutions}`"
                class="w-full"
              />
            </div>

            <div class="mt-6">
              <h4 class="font-bold mb-1">Description:</h4>
              <p class="text-gray-700 leading-relaxed">
                {{ selectedResolution.description_resolutions }}
              </p>
            </div>
          </div>

          <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
            <button
                  v-if="resolution.status === 'approved'"
                  @click="handleDownloadClick(resolution)"
                  class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
                >
                  Download PDF
                </button>

            <button
              v-else-if="selectedResolution.status === 'pending'"
              disabled
              class="px-4 py-2 text-sm bg-gray-300 text-gray-600 font-bold rounded-lg cursor-not-allowed"
            >
              Request Pending
            </button>

            <button
              v-else
              @click="openRequestModal(selectedResolution)"
              class="px-4 py-2 text-sm bg-green-800 text-white font-bold rounded-lg hover:bg-green-900 transition shadow-md"
            >
              Request Access
            </button>

            <button
              @click="closeModal"
              class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </transition>

    <transition name="modal">
      <div
        v-if="showRequestModal"
        class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50"
        @click.self="closeRequestModal"
      >
        <div
          class="bg-white w-full max-w-lg p-8 rounded-xl shadow-xl relative border border-gray-200"
        >
          <button
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            @click="closeRequestModal"
          >
            ✕
          </button>

          <h2 class="text-2xl font-bold text-green-900">Request Access</h2>

          <p class="mt-2 text-gray-700">
            State your purpose for requesting this resolution.
          </p>

          <form @submit.prevent="submitRequestForm" class="mt-6 space-y-4">
            <label class="font-semibold">Purpose of Request:</label>

            <textarea
              v-model="requestForm.purpose"
              maxlength="500"
              required
              class="w-full h-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
              placeholder="Explain why you need this resolution..."
            ></textarea>

            <button
              type="submit"
              class="w-full bg-green-800 text-white py-3 rounded-lg font-bold hover:bg-green-900 transition"
            >
              Submit Request
            </button>
          </form>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';

import Navbar from '@/components/Home/Navbar.vue';
import Footer from '@/components/Home/Footer.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import resolution from '@/routes/resolution';

const props = defineProps({
    resolutions: Object,
    filters: Object,
    years: Array,
    user: Object,
});

const form = reactive({
    search: props.filters.search || "",
    year: props.filters.year || "",
});

const applyFilters = () => {
    router.get("/resolutions", form, { 
        preserveState: true, 
        replace: true, 
    });
};

const clearFilters = () => {
    form.search = "";
    form.year = "";
    applyFilters();
};

const selectedResolution = ref(null);
const showRequestModal = ref(false);

const openModal = (resolution) => { 
    selectedResolution.value = resolution; 
};

const closeModal = () => { 
    selectedResolution.value = null; 
};

const openRequestModal = (resolution) => {
    if (!props.user) {
        router.visit('/login');
        return;
    }
    selectedResolution.value = resolution;
    showRequestModal.value = true;
};

const closeRequestModal = () => { 
    showRequestModal.value = false; 
};

const requestForm = reactive({ purpose: "" });

const submitRequestForm = async () => {
    if (!selectedResolution.value) return;

    const currentResolutionId = selectedResolution.value.id;

    router.post(
        `/resolutions/${currentResolutionId}/request-access`,
        { purpose: requestForm.purpose },
        {
            onFinish: () => {
                requestForm.purpose = '';
                showRequestModal.value = false;

                const resInList = props.resolutions.data.find(
                    (r) => r.id === currentResolutionId
                );
                if (resInList) {
                    resInList.status = 'pending';
                }
            },
            preserveScroll: true,
        }
    );
};

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const handleDownloadClick = (resolution) => {
    if (!props.user) {
        router.visit('/login');
        return;
    }

    if (resolution.status === 'approved') {
        window.location.href = `/resolution/download/${resolution.id}`;
    } else {
        openRequestModal(resolution);
        router.reload({
            only: ['flash'],
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.custom-select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none; 
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234B5563%22%20d%3D%22M287%20197.8%20146.2%2057%205.4%20197.8z%22%2F%3E%3C%2Fsvg%3E');
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 0.65em auto;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>