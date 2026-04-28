<template>
  <Head title="Ordinances" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <FlashMessage />

    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">

          <!-- Back Button -->
          <Link 
            href="/citizens-charter" 
            class="relative z-20 inline-flex items-center text-yellow-400 hover:text-white mb-8 transition-colors font-bold text-xs uppercase tracking-[0.2em]"
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

          <div class="text-left">
            <span class="inline-block text-[10px] md:text-xs tracking-[0.3em] uppercase bg-yellow-400 text-green-950 px-6 py-2 rounded-full font-black mb-8">
              Official Legislative Measures
            </span>

            <h1 class="text-4xl md:text-7xl font-black text-white leading-none tracking-tighter mb-8 uppercase">
              Municipal <span class="text-yellow-400 italic font-serif">Ordinances</span>
            </h1>

            <p class="max-w-xl text-green-50/80 text-lg md:text-xl font-medium leading-relaxed">
              Local laws enacted by the municipal council to regulate policies, services, and community development.
            </p>
          </div>

        </div>
      </div>
    </section>

    <section class="py-14 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
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
                  placeholder="Ordinance No., Title, or Keyword..."
                />
              </div>
            </div>

            <div>
              <label class="text-sm font-semibold text-gray-700 mb-2 block">
                Year
              </label>
              <select
                v-model="form.year"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg 
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
              'lg:col-span-1': true,
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

                <!-- Approved: Download PDF -->
                <button
                  v-if="ordinance.status === 'approved'"
                  @click="handleDownloadClick(ordinance)"
                  class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
                >
                  Download PDF
                </button>

                <!-- Pending: show disabled Request Pending -->
                <button
                  v-else-if="ordinance.status === 'pending'"
                  disabled
                  class="px-4 py-2 text-sm bg-gray-300 text-gray-600 font-bold rounded-lg cursor-not-allowed"
                >
                  Request Pending
                </button>

                <!-- Rejected or no request: Request Access -->
                <button
                  v-else
                  @click="openRequestModal(ordinance)"
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
        <nav class="flex items-center gap-4">
          <template v-if="ordinances.links[0]">
            <a
              v-if="ordinances.links[0].url"
              :href="ordinances.links[0].url"
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
            {{ ordinances.current_page }} of {{ ordinances.last_page }}
          </div>

          <template v-if="ordinances.links[ordinances.links.length - 1]">
            <a
              v-if="ordinances.links[ordinances.links.length - 1].url"
              :href="ordinances.links[ordinances.links.length - 1].url"
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
            <!-- Approved: Download PDF -->
            <button
              v-if="selectedOrdinance.status === 'approved'"
              @click="handleDownloadClick(selectedOrdinance)"
              class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
            >
              Download PDF
            </button>

            <!-- Pending: show disabled Request Pending -->
            <button
              v-else-if="selectedOrdinance.status === 'pending'"
              disabled
              class="px-4 py-2 text-sm bg-gray-300 text-gray-600 font-bold rounded-lg cursor-not-allowed"
            >
              Request Pending
            </button>

            <!-- Rejected or no request: Request Access -->
            <button
              v-else
              @click="openRequestModal(selectedOrdinance)"
              class="px-4 py-2 text-sm bg-green-800 text-white font-bold rounded-lg hover:bg-green-900 transition shadow-md"
            >
              Request Access
            </button>

            <!-- Close button always visible -->
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
            State your purpose and upload a valid government-issued ID.
          </p>

          <form @submit.prevent="submitRequestForm" class="mt-6 space-y-5">
            <!-- PURPOSE -->
            <div>
              <label class="font-semibold block mb-1">
                Purpose of Request
              </label>

              <textarea
                v-model="requestForm.purpose"
                maxlength="500"
                required
                class="w-full h-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
                placeholder="Explain why you need this ordinance..."
              ></textarea>
            </div>

            <!-- VALID ID TYPE -->
            <div>
              <label class="font-semibold block mb-1">
                Valid ID Type
              </label>

              <select
                v-model="requestForm.valid_id_type"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
              >
                <option value="" disabled>Select a valid ID</option>
                <option>PhilSys National ID</option>
                <option>Passport</option>
                <option>Driver's License</option>
                <option>UMID</option>
                <option>Voter's ID</option>
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
              <label class="font-semibold block mb-1">
                Upload Valid ID
              </label>

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
                Accepted formats: JPG, PNG, PDF (Max 5MB)
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
import { computed, ref, reactive, watch, nextTick, onMounted, onUnmounted } from 'vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';

import Navbar from '@/components/Home/Navbar.vue';
import Footer from '@/components/Home/Footer.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import { route } from 'ziggy-js';

// -------------------------
// PROPS
// -------------------------
const props = defineProps({
    ordinances: Object,
    filters: Object,
    years: Array,
    user: Object,
});

// -------------------------
// FILTER FORM LOGIC
// -------------------------
const form = reactive({
    search: props.filters.search || "",
    year: props.filters.year || "",
});

const applyFilters = () => {
    router.get("/citizens-charter/ordinances", form, { 
        preserveState: true, 
        replace: true, 
    });
};

const clearFilters = () => {
    form.search = "";
    form.year = "";
    applyFilters();
};

// -------------------------
// MODAL & REQUEST LOGIC
// -------------------------
const selectedOrdinance = ref(null);
const showRequestModal = ref(false);

const openModal = (ordinance) => { 
    selectedOrdinance.value = ordinance; 
};

const closeModal = () => { 
    selectedOrdinance.value = null; 
};

const openRequestModal = (ordinance) => {
    if (!props.user) {
        router.visit('/login');
        return;
    }
    selectedOrdinance.value = ordinance;
    showRequestModal.value = true;
};

const closeRequestModal = () => { 
    showRequestModal.value = false; 
};

// -------------------------
// REQUEST FORM & reCAPTCHA
// -------------------------
const requestForm = reactive({
    purpose: '',
    valid_id_type: '',
    valid_id: null,
});

const recaptchaSiteKey = import.meta.env.VITE_RECAPTCHA_SITE_KEY;

// FILE UPLOAD HANDLER
const handleValidIdUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        requestForm.valid_id = file;
    }
};

// reCAPTCHA v3 LOADING (Standard, not Enterprise)
const loadRecaptcha = () => {
    return new Promise((resolve, reject) => {
        if (!recaptchaSiteKey) {
            reject(new Error('reCAPTCHA site key is not configured'));
            return;
        }

        // ✅ Wait until grecaptcha AND ready() exist
        const waitForReady = () => {
            if (window.grecaptcha && typeof window.grecaptcha.ready === 'function') {
                window.grecaptcha.ready(() => resolve());
            } else {
                setTimeout(waitForReady, 100);
            }
        };

        // If script already added
        if (document.getElementById('recaptcha-script')) {
            waitForReady();
            return;
        }

        const script = document.createElement('script');
        script.id = 'recaptcha-script';
        script.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaSiteKey}`;
        script.async = true;
        script.defer = true;

        script.onload = () => {
            waitForReady();
        };

        script.onerror = () => {
            reject(new Error('Failed to load reCAPTCHA script'));
        };

        document.head.appendChild(script);
    });
};

onMounted(() => {
    loadRecaptcha().catch(err => {
        console.error('[v0] reCAPTCHA load error:', err.message);
    });
});

onUnmounted(() => {
    const badge = document.querySelector('.grecaptcha-badge');
    if (badge) badge.style.visibility = 'hidden';
});

// SUBMIT FORM WITH reCAPTCHA v3
const submitRequestForm = async () => {
    if (!selectedOrdinance.value) return;

    try {
        // Validate site key before proceeding
        if (!recaptchaSiteKey) {
            alert('Security configuration error. Please refresh the page and try again.');
            return;
        }

        // Ensure reCAPTCHA is ready
        await loadRecaptcha();

        // Standard reCAPTCHA v3 execute
        const token = await window.grecaptcha.execute(recaptchaSiteKey, {
            action: 'ordinance_request'
        });

        if (!token) {
            throw new Error('Failed to generate reCAPTCHA token');
        }

        const formData = new FormData();
        formData.append('purpose', requestForm.purpose);
        formData.append('valid_id_type', requestForm.valid_id_type);
        formData.append('valid_id', requestForm.valid_id);
        formData.append('recaptcha_token', token);

        router.post(`/ordinances/${selectedOrdinance.value.id}/request-access`, formData, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                requestForm.purpose = '';
                requestForm.valid_id_type = '';
                requestForm.valid_id = null;
                showRequestModal.value = false;
            },
            onError: (errors) => {
                if (errors.captcha) alert(errors.captcha);
                if (errors.valid_id) alert(errors.valid_id);
            }
        });
    } catch (error) {
        console.error('[v0] reCAPTCHA Error:', error.message);
        alert('Security check failed. Please refresh the page and try again.');
    }
};

// -------------------------
// DATE FORMAT UTILITY
// -------------------------
const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

// -------------------------
// DOWNLOAD HANDLER
// -------------------------
const handleDownloadClick = (ordinance) => {
    if (!props.user) {
        router.visit('/login');
        return;
    }

    if (ordinance.status === 'approved') {
        // Approved → download file
        window.location.href = `/ordinance/download/${ordinance.id}`;
    } else {
        // Pending / rejected / no request → show modal
        openRequestModal(ordinance);

        // Refresh flash messages
        router.reload({
            only: ['flash'],
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.25s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}

.custom-select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23374151' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
  background-position: right 0.75rem center;
  background-repeat: no-repeat;
  background-size: 1rem;
}
</style>
