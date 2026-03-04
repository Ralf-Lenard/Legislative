<template>
  <Head title="Resolutions" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <FlashMessage />

    <!-- <section
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
    </section> -->

    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">
          
          <Link 
            href="/citizens-charter" 
            class="relative z-20 inline-flex items-center text-yellow-400 hover:text-white mb-8 transition-colors font-bold text-xs uppercase tracking-[0.2em] cursor-pointer"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-5 w-5 mr-2" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Citizen Charter
          </Link>

          <div class="block">
            <span class="text-xs tracking-widest uppercase bg-yellow-400 text-green-900 px-4 py-1 rounded-full font-bold">
              Official Website
            </span>
          </div>

          <h1 class="text-5xl md:text-6xl font-extrabold mt-4 leading-tight">
            Municipal Resolutions
          </h1>

          <p class="text-lg mt-3 text-gray-100">
            Formal expressions of the will and opinion of the municipality.
          </p>
        </div>
        
        </div>
    </section>

    <section class="py-14 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white text-black p-6 rounded-xl shadow-lg border border-gray-200">
          <form
            @submit.prevent="applyFilters"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end"
          >
            <div class="lg:col-span-2">
              <label class="text-sm font-semibold text-gray-700 mb-2 block">
                Search Filter
              </label>
              <div class="relative">
                <input
                  v-model="form.search"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
                        bg-white text-black
                        focus:ring-2 focus:ring-green-800 focus:border-green-800 transition"
                  placeholder="Resolution No., Title, or Keyword..."
                />
              </div>
            </div>

            <div>
              <label class="text-sm font-semibold text-gray-700 mb-2 block">
                Year
              </label>
              <select
                v-model="form.year"
                class="w-full px-4 pr-10 py-2.5 border border-gray-300 rounded-lg 
                      bg-white text-black
                      focus:ring-2 focus:ring-green-800 focus:border-green-800 
                      appearance-none custom-select transition"
              >
                <option value="">All Years</option>
                <option v-for="year in years" :key="year">
                  {{ year }}
                </option>
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
        <nav class="flex items-center gap-4">
          <template v-if="resolutions.links[0]">
            <a
              v-if="resolutions.links[0].url"
              :href="resolutions.links[0].url"
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
            {{ resolutions.current_page }} of {{ resolutions.last_page }}
          </div>

          <template v-if="resolutions.links[resolutions.links.length - 1]">
            <a
              v-if="resolutions.links[resolutions.links.length - 1].url"
              :href="resolutions.links[resolutions.links.length - 1].url"
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
      <!-- CLOSE BUTTON -->
      <button
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
        @click="closeRequestModal"
      >
        ✕
      </button>

      <!-- HEADER -->
      <h2 class="text-2xl font-bold text-green-900">Request Access</h2>
      <p class="mt-2 text-gray-700">
        State your purpose and upload a valid government-issued ID.
      </p>

      <!-- FORM -->
      <form @submit.prevent="submitRequestForm" class="mt-6 space-y-5">
        <!-- PURPOSE -->
        <div>
          <label class="font-semibold block mb-1">Purpose of Request</label>
          <textarea
            v-model="requestForm.purpose"
            maxlength="500"
            required
            class="w-full h-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
            placeholder="Explain why you need this resolution..."
          ></textarea>
        </div>

        <!-- VALID ID TYPE -->
        <div>
          <label class="font-semibold block mb-1">Valid ID Type</label>
          <select
            v-model="requestForm.valid_id_type"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
          >
            <option value="" disabled>Select a valid ID</option>
            <option>PhilSys National ID</option>
            <option>Passport</option>
            <option>Driver’s License</option>
            <option>UMID</option>
            <option>Voter’s ID</option>
            <option>Postal ID</option>
            <option>PRC ID</option>
            <option>Senior Citizen ID</option>
            <option>PWD ID</option>
            <option>SSS ID</option>
            <option>GSIS ID</option>
            <option>TIN ID</option>
            <option>PhilHealth ID</option>
          </select>
        </div>

        <!-- VALID ID FILE -->
        <div>
          <label class="font-semibold block mb-1">Upload Valid ID</label>
          <input
            type="file"
            @change="handleValidIdUpload"
            accept=".jpg,.jpeg,.png,.pdf"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4
                   file:rounded-lg file:border-0 file:bg-green-800 file:text-white
                   hover:file:bg-green-900 transition"
          />
          <p class="text-sm text-gray-500 mt-1">
            Accepted formats: JPG, PNG, PDF (Max 20MB)
          </p>
        </div>

        <!-- SUBMIT -->
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
import { Head, router, usePage, Link } from '@inertiajs/vue3';

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
    router.get("/citizens-charter/resolutions", form, { 
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

const requestForm = reactive({
    purpose: '',
    valid_id_type: '',
    valid_id: null,
})

const submitRequestForm = async () => {
    if (!selectedResolution.value) return

    const currentResolutionId = selectedResolution.value.id

    // ✅ Use FormData to handle file uploads
    const formData = new FormData()
    formData.append('purpose', requestForm.purpose)
    formData.append('valid_id_type', requestForm.valid_id_type)
    formData.append('valid_id', requestForm.valid_id)

    router.post(
        `/resolutions/${currentResolutionId}/request-access`,
        formData,
        {
            forceFormData: true, // ✅ Required for file upload
            preserveScroll: true,

            onFinish: () => {
                // Reset form fields
                requestForm.purpose = ''
                requestForm.valid_id_type = ''
                requestForm.valid_id = null

                // Close modal
                showRequestModal.value = false

                // Optimistic UI update
                const resInList = props.resolutions.data.find(
                    (r) => r.id === currentResolutionId
                )
                if (resInList) {
                    resInList.status = 'pending'
                }
            },
        }
    )
}


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

const handleValidIdUpload = (event) => {
    requestForm.valid_id = event.target.files[0] || null;
}

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
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
  background-position: right 0.75rem center;
  background-repeat: no-repeat;
  background-size: 1rem;
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