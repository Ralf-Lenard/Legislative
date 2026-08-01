<template>
  <Head title="Ordinances" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <FlashMessage />

    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">

          <Link
            href="/citizens-charter"
            class="relative z-20 inline-flex items-center text-yellow-400 hover:text-white mb-8 transition-colors font-bold text-xs uppercase tracking-[0.2em]"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Search Filter</label>
                <input
                  v-model="form.search"
                  type="text"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-black focus:ring-2 focus:ring-green-800 focus:border-green-800 transition"
                  placeholder="Ordinance No., Title, or Keyword..."
                />
              </div>

              <div>
                <label class="text-sm font-semibold text-gray-700 mb-2 block">Year</label>
                <select
                  v-model="form.year"
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white text-black focus:ring-2 focus:ring-green-800 focus:border-green-800 appearance-none custom-select transition"
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
        </div>

        <div v-if="ordinances.data.length" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12 items-stretch">
          <div
            v-for="ordinance in ordinances.data"
            :key="ordinance.id"
            class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden lg:col-span-1 flex flex-col h-full"
          >
            <div class="bg-green-800 text-white px-5 py-2 font-semibold text-sm">
              Ordinance No. {{ ordinance.ordinance_number }}
            </div>

            <div class="p-5 flex flex-col flex-1 space-y-4">
              <h2 class="text-lg font-bold text-gray-900 line-clamp-2">{{ ordinance.title_ordinances }}</h2>
              <p class="text-gray-600 text-sm line-clamp-3">{{ ordinance.description_ordinances }}</p>

              <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium pt-2 border-t border-gray-100">
                <span class="text-green-800 flex items-center gap-1">
                  <span class="text-sm">🗓️</span>
                  Series: {{ new Date(ordinance.date_approved_ordinances).getFullYear() }}
                </span>
              </div>

              <div class="flex justify-between items-center gap-3 pt-3 mt-auto">
                <button
                  @click="openModal(ordinance)"
                  class="px-4 py-2 text-sm text-green-800 font-bold border border-green-800 rounded-lg hover:bg-green-50 transition"
                >
                  View Details
                </button>

                <button
                  v-if="ordinance.status === 'approved'"
                  @click="handleDownloadClick(ordinance)"
                  class="px-4 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition shadow-md"
                >Download PDF</button>

                <button
                  v-else-if="ordinance.status === 'pending'"
                  disabled
                  class="px-4 py-2 text-sm bg-gray-300 text-gray-600 font-bold rounded-lg cursor-not-allowed"
                >Request Pending</button>

                <button
                  v-else
                  @click="openRequestModal(ordinance)"
                  class="px-4 py-2 text-sm bg-green-800 text-white font-bold rounded-lg hover:bg-green-900 transition shadow-md"
                >Request Access</button>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center mt-16 bg-white p-10 rounded-xl shadow border border-gray-200">
          <h3 class="text-2xl font-bold text-gray-700">No Ordinances Found</h3>
          <p class="mt-2 text-gray-500">Try adjusting your searches or filters.</p>
          <button
            @click="clearFilters"
            class="mt-6 bg-green-800 text-white px-5 py-2.5 rounded-lg hover:bg-green-900 font-semibold"
          >Clear Filters</button>
        </div>

        <div v-if="ordinances.data.length" class="mt-16 flex justify-center">
          <nav class="flex items-center gap-4">
            <template v-if="ordinances.links[0]">
              <a
                v-if="ordinances.links[0].url"
                :href="ordinances.links[0].url"
                class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 transition-colors flex items-center justify-center"
              >← Prev</a>
              <span v-else class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 bg-white text-gray-400 cursor-default flex items-center justify-center">← Prev</span>
            </template>

            <div class="text-sm font-bold text-gray-700">
              {{ ordinances.current_page }} of {{ ordinances.last_page }}
            </div>

            <template v-if="ordinances.links[ordinances.links.length - 1]">
              <a
                v-if="ordinances.links[ordinances.links.length - 1].url"
                :href="ordinances.links[ordinances.links.length - 1].url"
                class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 transition-colors flex items-center justify-center"
              >Next →</a>
              <span v-else class="h-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 bg-white text-gray-400 cursor-default flex items-center justify-center">Next →</span>
            </template>
          </nav>
        </div>
      </div>
    </section>

    <!-- View Details Modal -->
    <transition name="modal">
      <div
        v-if="selectedOrdinance && !showRequestModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-500/30 z-50 p-4"
        @click.self="closeModal"
      >
        <div
          class="relative flex flex-col w-full max-w-2xl overflow-hidden bg-white border border-gray-200 rounded-xl shadow-xl"
          style="max-height: 90vh;"
        >
          <div class="flex-1 px-6 py-6 overflow-y-auto custom-scrollbar">
            <button
              class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
              @click="closeModal"
            >
              ✕
            </button>

            <h2 class="text-2xl font-bold text-green-900">
              Ordinance No. {{ selectedOrdinance.ordinance_number }}
            </h2>

            <h3 class="mt-2 text-lg font-semibold text-gray-900">
              {{ selectedOrdinance.title_ordinances }}
            </h3>

            <p class="mt-1 text-sm text-gray-600">
              Date Approved:
              <strong>{{ formatDate(selectedOrdinance.date_approved_ordinances) }}</strong>
            </p>

            <div
              v-if="selectedOrdinance.image_ordinances"
              class="mt-6 overflow-hidden border border-gray-200 rounded-lg shadow"
            >
              <img
                :src="`/storage/${selectedOrdinance.image_ordinances}`"
                class="w-full"
              />
            </div>

            <div class="mt-6">
              <h4 class="mb-1 font-bold text-gray-900">Description:</h4>
              <p class="leading-relaxed text-gray-700">
                {{ selectedOrdinance.description_ordinances }}
              </p>
            </div>
          </div>

          <div class="flex justify-end gap-2 px-6 py-4 border-t border-gray-200">
            <button
              v-if="selectedOrdinance.status === 'approved'"
              @click="handleDownloadClick(selectedOrdinance)"
              class="px-4 py-2 text-sm font-bold text-green-900 transition bg-yellow-400 rounded-lg shadow-md hover:bg-yellow-500"
            >
              Download PDF
            </button>

            <button
              v-else-if="selectedOrdinance.status === 'pending'"
              disabled
              class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-300 rounded-lg cursor-not-allowed"
            >
              Request Pending
            </button>

            <button
              v-else
              @click="openRequestModal(selectedOrdinance)"
              class="px-4 py-2 text-sm font-bold text-white transition bg-green-800 rounded-lg shadow-md hover:bg-green-900"
            >
              Request Access
            </button>

            <button
              @click="closeModal"
              class="px-6 py-2 text-gray-700 transition bg-gray-200 rounded-lg hover:bg-gray-300"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Request Access Modal -->
    <transition name="modal">
      <div
        v-if="showRequestModal"
        class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50"
        @click.self="closeRequestModal"
      >
        <div class="bg-white w-full max-w-lg p-8 rounded-xl shadow-xl relative border border-gray-200">
          <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" @click="closeRequestModal">✕</button>

          <h2 class="text-2xl font-bold text-green-900">Request Access</h2>
          <p class="mt-2 text-gray-700">State your purpose and upload a valid government-issued ID.</p>

          <div v-if="formError" class="mt-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
            {{ formError }}
          </div>

          <form @submit.prevent="submitRequestForm" class="mt-6 space-y-5">
            <div>
              <label class="font-semibold block mb-1">Purpose of Request</label>
              <textarea
                v-model="requestForm.purpose"
                maxlength="500"
                required
                class="w-full h-32 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800"
                placeholder="Explain why you need this ordinance..."
              ></textarea>
            </div>

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

            <div>
              <label class="font-semibold block mb-1">Upload Valid ID</label>
              <input
                type="file"
                @change="handleValidIdUpload"
                accept=".jpg,.jpeg,.png,.pdf"
                required
                class="w-full px-3 py-2 border rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-800 file:text-white hover:file:bg-green-900 transition"
                :class="validIdError ? 'border-red-400' : 'border-gray-300'"
              />
              <p class="text-sm text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (Max 5MB) — letters, numbers, spaces, hyphens and underscores only in the filename.</p>

              <p v-if="validIdError" class="mt-1 flex items-start gap-1.5 text-xs font-medium text-red-600">
                <svg class="mt-0.5 h-3.5 w-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ validIdError }}</span>
              </p>

              <div v-if="requestForm.valid_id" class="mt-2 text-sm text-gray-600">
                Selected: <span class="font-medium">{{ requestForm.valid_id.name }}</span>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isSubmitting || !!validIdError"
              class="w-full bg-green-800 text-white py-3 rounded-lg font-bold hover:bg-green-900 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isSubmitting">Submitting...</span>
              <span v-else>Submit Request</span>
            </button>
          </form>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>

<script setup>
import { computed, ref, reactive, onMounted, onUnmounted } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';

import Navbar from '@/components/Home/Navbar.vue';
import Footer from '@/components/Home/Footer.vue';
import FlashMessage from '@/components/FlashMessage.vue';

// -------------------------
// PROPS
// -------------------------
const props = defineProps({
    ordinances: Object,
    filters: Object,
    years: Array,
    user: Object,
    recaptchaSiteKey: String,
    appEnv: String, // 'local', 'production', etc — passed from controller
});

// Key comes exclusively from the controller prop (never from VITE_ at runtime).
const siteKey = computed(() => props.recaptchaSiteKey ?? null);

// Only enable reCAPTCHA in production so localhost never hits Google's domain check.
const recaptchaEnabled = computed(() => !!siteKey.value && props.appEnv === 'production');

// -------------------------
// FILTER FORM
// -------------------------
const form = reactive({
    search: props.filters?.search || '',
    year: props.filters?.year || '',
});

const applyFilters = () => {
    router.get('/citizens-charter/ordinances', form, { preserveState: true, replace: true });
};

const clearFilters = () => {
    form.search = '';
    form.year = '';
    applyFilters();
};

// -------------------------
// MODALS
// -------------------------
const selectedOrdinance = ref(null);
const showRequestModal = ref(false);

const openModal = (ordinance) => { selectedOrdinance.value = ordinance; };
const closeModal = () => { selectedOrdinance.value = null; };

const openRequestModal = (ordinance) => {
    if (!props.user) { router.visit('/login'); return; }
    selectedOrdinance.value = ordinance;
    showRequestModal.value = true;
    formError.value = null;
    validIdError.value = null;
};

const closeRequestModal = () => {
    showRequestModal.value = false;
    formError.value = null;
    validIdError.value = null;
};

// -------------------------
// REQUEST FORM
// -------------------------
const requestForm = reactive({ purpose: '', valid_id_type: '', valid_id: null });
const formError = ref(null);
const validIdError = ref(null);
const isSubmitting = ref(false);

// Same character rule used on the ordinance/resolution upload forms — only
// letters, numbers, spaces, hyphens and underscores survive, to avoid
// tripping a hosting-provider WAF/mod_security rule on the filename.
const SAFE_FILENAME_PATTERN = /^[a-zA-Z0-9\- _]+$/;
const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf'];
const MAX_SIZE_BYTES = 5 * 1024 * 1024; // 5MB

/**
 * Validates the Valid ID file's name/type/size. Returns an error message
 * if invalid, or null if valid. Never modifies the file.
 */
const validateValidIdFilename = (file) => {
    const originalName = file.name;
    const lastDot = originalName.lastIndexOf('.');

    if (lastDot === -1) {
        return 'The file must have a valid extension (.jpg, .jpeg, .png, .pdf).';
    }

    const namePart = originalName.slice(0, lastDot);
    const extPart = originalName.slice(lastDot + 1).toLowerCase();

    if (!ALLOWED_EXTENSIONS.includes(extPart)) {
        return 'Only JPG, PNG, or PDF files are accepted.';
    }

    if (!namePart.trim()) {
        return 'The filename cannot be empty.';
    }

    if (!SAFE_FILENAME_PATTERN.test(namePart)) {
        return `"${originalName}" contains characters that aren't allowed. Please rename the file using only letters, numbers, spaces, hyphens and underscores, then re-upload.`;
    }

    if (file.size > MAX_SIZE_BYTES) {
        return 'File is too large. Maximum size is 5MB.';
    }

    return null;
};

const handleValidIdUpload = (e) => {
    const input = e.target;
    const file = input.files?.[0];
    if (!file) return;

    const error = validateValidIdFilename(file);

    if (error) {
        validIdError.value = error;
        requestForm.valid_id = null; // never let an invalid file into the form
        input.value = ''; // reset so re-picking the same bad file re-triggers validation
        return;
    }

    validIdError.value = null;
    requestForm.valid_id = file;
};

// -------------------------
// reCAPTCHA — single shared promise
// -------------------------
let recaptchaReadyPromise = null;

const ensureRecaptchaReady = () => {
    if (recaptchaReadyPromise) return recaptchaReadyPromise;

    recaptchaReadyPromise = new Promise((resolve, reject) => {
        const key = siteKey.value;
        if (!key) return reject(new Error('reCAPTCHA site key is missing.'));

        if (window.grecaptcha?.execute) {
            window.grecaptcha.ready(resolve);
            return;
        }

        if (document.querySelector('script[src*="recaptcha/api.js"]')) {
            const poll = setInterval(() => {
                if (window.grecaptcha?.execute) {
                    clearInterval(poll);
                    window.grecaptcha.ready(resolve);
                }
            }, 100);
            setTimeout(() => { clearInterval(poll); reject(new Error('reCAPTCHA timed out.')); }, 15000);
            return;
        }

        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${key}`;
        script.async = true;
        script.defer = true;
        script.onload = () => {
            if (window.grecaptcha?.ready) window.grecaptcha.ready(resolve);
            else reject(new Error('grecaptcha unavailable after load.'));
        };
        script.onerror = () => reject(new Error('Failed to load reCAPTCHA script.'));
        document.head.appendChild(script);
    });

    return recaptchaReadyPromise;
};

onMounted(() => {
    if (recaptchaEnabled.value) {
        ensureRecaptchaReady().catch(err => console.warn('reCAPTCHA pre-load:', err.message));
    }
});

onUnmounted(() => {
    const badge = document.querySelector('.grecaptcha-badge');
    if (badge) badge.style.visibility = 'hidden';
});

// -------------------------
// SUBMIT
// -------------------------
const submitRequestForm = async () => {
    if (!selectedOrdinance.value) return;

    // Hard stop — mirrors the disabled submit button, protects against
    // programmatic submission too.
    if (validIdError.value) return;

    isSubmitting.value = true;
    formError.value = null;

    const currentOrdinanceId = selectedOrdinance.value.id;

    try {
        let token = 'local-bypass';

        if (recaptchaEnabled.value) {
            await ensureRecaptchaReady();
            token = await window.grecaptcha.execute(siteKey.value, { action: 'ordinance_request' });
            if (!token) throw new Error('Could not generate reCAPTCHA token.');
        }

        // Use router.post with the data object directly.
        // Inertia automatically converts this to FormData if it detects a File object.
        router.post(`/ordinances/${currentOrdinanceId}/request-access`, {
            purpose: requestForm.purpose,
            valid_id_type: requestForm.valid_id_type,
            valid_id: requestForm.valid_id, // Ensure this is the actual File object
            recaptcha_token: token,
        }, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                // Only clear and close on success
                requestForm.purpose = '';
                requestForm.valid_id_type = '';
                requestForm.valid_id = null;
                validIdError.value = null;
                showRequestModal.value = false;
            },
            onError: (errors) => {
                // Pick the first available error message
                formError.value = Object.values(errors)[0];
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        });

    } catch (err) {
        isSubmitting.value = false;
        formError.value = err.message;
    }
};

// -------------------------
// HELPERS
// -------------------------
const formatDate = (dateStr) => {
    if (!dateStr) return 'N/A';
    return new Date(dateStr).toLocaleDateString('en-PH', { year: 'numeric', month: 'long', day: 'numeric' });
};

const handleDownloadClick = (ordinance) => {
    if (ordinance.file_ordinances) window.open(`/storage/${ordinance.file_ordinances}`, '_blank');
};
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.3s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a1a1a1; }

.custom-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
}
</style>