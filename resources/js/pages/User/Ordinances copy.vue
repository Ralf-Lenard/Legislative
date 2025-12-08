<template>
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
              Municipal Ordinances
            </h1>
  
            <p class="text-lg mt-3 text-gray-100">
              Crafting laws for a better future.
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
                    placeholder="Ordinance No., Title, or Keyword..."
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
            v-if="ordinances.data.length"
            class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12"
          >
            <div
              v-for="ordinance in ordinances.data"
              :key="ordinance.id"
              class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
              :class="{
                'lg:col-span-1': true, // Standard column size for most items
                // 'lg:col-span-2': ordinance.id % 5 === 1 // Making some cards wider to match the visual variety in the image
              }"
            >
              <div class="bg-green-800 text-white px-5 py-2 font-semibold text-sm">
                Ordinance No. {{ ordinance.ordinance_number }}
              </div>
  
              <div class="p-5 space-y-4">
                <h2 class="text-lg font-bold text-gray-900 line-clamp-2">
                  {{ ordinance.title_ordinances }}
                </h2>
  
                <p class="text-gray-600 text-sm line-clamp-3">
                  {{ ordinance.description_ordinances }}
                </p>
  
                <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium pt-2 border-t border-gray-100">
                  <span class="text-green-800 flex items-center gap-1">
                    <span class="text-sm">🗓️</span>
                      Series: {{ new Date(ordinance.date_approved_ordinances).getFullYear() }}
  
                      </span>
                  
                  </div>
  
                <div class="flex justify-between items-center gap-3 pt-3">
                  <button
                    @click="openModal(ordinance)"
                    class="px-4 py-2 text-sm text-green-800 font-bold border border-green-800 rounded-lg hover:bg-green-50 transition"
                  >
                    View Details
                  </button>
                  
                  <button
                    @click="handleDownloadClick(ordinance)"
                    class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
                  >
                    Download PDF
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
              No Ordinances Found
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
            v-if="ordinances.data.length"
            class="mt-16 flex justify-center"
          >
            <nav class="flex gap-2">
              <template
                v-for="(link, i) in ordinances.links"
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
          v-if="selectedOrdinance"
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
                Ordinance No. {{ selectedOrdinance.ordinance_number }}
              </h2>
  
              <h3 class="text-lg font-semibold mt-2">
                {{ selectedOrdinance.title_ordinances }}
              </h3>
  
              <p class="text-sm text-gray-600 mt-1">
                Date Approved:
                <strong>{{ formatDate(selectedOrdinance.date_approved_ordinances) }}</strong>
              </p>
  
              <div
                class="mt-6 rounded-lg overflow-hidden border border-gray-200 shadow"
                v-if="selectedOrdinance.image_ordinances"
              >
                <img
                  :src="`/storage/${selectedOrdinance.image_ordinances}`"
                  class="w-full"
                />
              </div>
  
              <div class="mt-6">
                <h4 class="font-bold mb-1">Description:</h4>
                <p class="text-gray-700 leading-relaxed">
                  {{ selectedOrdinance.description_ordinances }}
                </p>
              </div>
            </div>
  
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-2">
              <button
                @click="handleDownloadClick(selectedOrdinance)"
                class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
              >
                Download PDF
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
              State your purpose for requesting this ordinance.
            </p>
  
            <form @submit.prevent="submitRequestForm" class="mt-6 space-y-4">
              <label class="font-semibold">Purpose of Request:</label>
  
              <textarea
                v-model="requestForm.purpose"
                maxlength="500"
                required
                class="w-full h-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
                placeholder="Explain why you need this ordinance..."
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
  import { reactive, ref, watch } from "vue"; // <-- Import 'watch'
  import { router, usePage } from "@inertiajs/vue3"; // <-- Import 'usePage'
  
  import Navbar from "@/components/Home/Navbar.vue";
  import Footer from "@/components/Home/Footer.vue";
  
  // 1. Import Toastify
  import Toastify from 'toastify-js';
  // Don't forget to link the Toastify CSS file in your main HTML file or entry point
  
  // 2. Access Inertia Page Props
  const page = usePage(); // This allows watching for flash messages from the server
  
  const props = defineProps({
    ordinances: Object,
    filters: Object,
    years: Array,
    user: Object, // null if not logged in
  });
  
  // 3. Helper function to show toasts
  const showToast = (message, type = 'success') => {
    let backgroundColor = '#166534'; // default to success (green-800)
    let className = 'toastify-custom-success';
    if (type === 'error') {
      backgroundColor = '#DC2626'; // Red
      className = 'toastify-custom-error';
    } else if (type === 'warning') {
      backgroundColor = '#FBBF24'; // Yellow-400
      className = 'toastify-custom-warning';
    }
  
    Toastify({
      text: message,
      duration: 3000,
      newWindow: true,
      close: true,
      gravity: "top", // `top` or `bottom`
      position: "right", // `left`, `center` or `right`
      stopOnFocus: true, // Prevents dismissing of toast on hover
      className: className, // Use custom class for matching design
      style: {
        background: backgroundColor,
      },
      onClick: function(){} // Callback after click
    }).showToast();
  };
  
  
  // 4. WATCHER: Connect Vue to Laravel's Flash Messages
  watch(
    () => page.props.flash,
    (flash) => {
      if (flash.success) {
        showToast(flash.success, 'success');
      }
      if (flash.error) {
        showToast(flash.error, 'error');
      }
      if (flash.warning) {
        showToast(flash.warning, 'warning');
      }
      // Important: Inertia automatically clears flash messages, 
      // but a subsequent check/reload might be needed for the UI status update
    },
    { deep: true } // Watch for changes inside the flash object
  );
  
  
  // FILTER FORM
  const form = reactive({
    search: props.filters.search || "",
    year: props.filters.year || "",
  });
  
  // APPLY FILTERS
  const applyFilters = () => {
    router.get("/ordinances", form, { 
      preserveState: true, 
      replace: true, 
    });
  };
  
  const clearFilters = () => {
    form.search = "";
    form.year = "";
    applyFilters();
  };
  
  // ==========================
  //      MODAL LOGIC
  // ==========================
  const selectedOrdinance = ref(null);
  
  const openModal = (ordinance) => {
    selectedOrdinance.value = ordinance;
  };
  
  const closeModal = () => {
    selectedOrdinance.value = null;
  };
  
  // ==========================
  //  REQUEST DOWNLOAD MODAL
  // ==========================
  const showRequestModal = ref(false);
  
  const openRequestModal = (ordinance) => {
    if (!props.user) {
      // Client-side toast for immediate feedback before redirect
      showToast('Please log in to request access to ordinances.', 'warning'); 
      router.visit('/login');
      return;
    }
    
    selectedOrdinance.value = ordinance;
    showRequestModal.value = true;
  };
  
  const closeRequestModal = () => {
    showRequestModal.value = false;
  };
  
  const requestForm = reactive({
    purpose: "",
  });
  
  // Submit form
  const submitRequestForm = async () => {
    if (!selectedOrdinance.value) return;
  
    // 5. REMOVED onSuccess/onError Handlers: 
    // Now, the successful/failed notification relies entirely on the server
    router.post(
      `/ordinances/${selectedOrdinance.value.id}/request-access`,
      {
        purpose: requestForm.purpose,
      },
      {
        onFinish: () => {
          // We only close the modal after the server response is received
          requestForm.purpose = '';
          showRequestModal.value = false;
        },
        preserveScroll: true,
      }
    );
  };
  
  // Date format
  const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  };
  
  const handleDownloadClick = (ordinance) => {
    if (!props.user) {
      showToast('Please log in to download ordinances.', 'warning');
      router.visit('/login'); // redirect if not logged in
      return;
    }
  
    if (ordinance.user_request_status === 'approved') {
      // Download the PDF
      window.location.href = `/ordinance/download/${ordinance.id}`;
      showToast('Download started!', 'success'); // Client-side toast for immediate download feedback
    } else if (ordinance.user_request_status === 'pending') {
      showToast('Your request is currently pending approval. Thank you for your patience.', 'warning');
    } else {
      // Open the request form modal
      openRequestModal(ordinance);
    }
  };
  </script>
  
  <style scoped>
  /* (Your existing scoped styles remain here) */
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
  
  /* Custom Toastify Styles */
  .toastify-custom-success {
    color: white !important;
    border-radius: 0.5rem !important;
    font-weight: 600 !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  }
  
  .toastify-custom-warning {
    color: #166534 !important;
    border-radius: 0.5rem !important;
    font-weight: 600 !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  }
  
  .toastify-custom-error {
    color: white !important;
    border-radius: 0.5rem !important;
    font-weight: 600 !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  }
  </style>