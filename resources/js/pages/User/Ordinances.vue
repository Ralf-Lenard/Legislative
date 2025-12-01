<template>
  <div class="ordinances-page">
    <Navbar />
    <section class="ordinance-header">
      <div class="header-overlay"></div>
      <h1 class="header-title">Municipal Ordinances</h1>
      <p class="header-subtitle">
        Explore the approved ordinances of the Municipality of Concepcion, Tarlac.
        We commit to <strong>Transparency</strong>, <strong>Integrity</strong>, and <strong>Public Service</strong>.
      </p>
    </section>

    <div class="controls-bar">
      <div class="search-bar">
        <input
          type="text"
          v-model="filters.search"
          placeholder="Search ordinance by title or number..."
          aria-label="Search ordinances"
        />
        <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="filter-dropdown">
        <select v-model="filters.year" @change="applyFilter" aria-label="Filter by year">
          <option value="">All Years</option>
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
        </select>
      </div>
    </div>

    <section class="ordinance-list">
      <div v-if="isLoading" class="loading-state">
        <div class="spinner"></div>
        <p>Filtering ordinances...</p>
      </div>
      <div
        v-for="ordinance in ordinances.data"
        :key="ordinance.id"
        class="ordinance-card"
        @click="showOrdinance(ordinance)"
        role="button"
        tabindex="0"
        @keyup.enter="showOrdinance(ordinance)"
      >
        <div class="ordinance-header-info">
          <h3 class="ordinance-number">{{ ordinance.ordinance_number }}</h3>
          <span class="date"><i class="fas fa-calendar-alt"></i> Approved: <strong>{{ formatDate(ordinance.date_approved_ordinances) }}</strong></span>
        </div>
        <h2 class="ordinance-title">{{ ordinance.title_ordinances }}</h2>
        <p class="ordinance-description">{{ ordinance.description_ordinances }}</p>
        <div class="read-more">View Details <i class="fas fa-arrow-right"></i></div>
      </div>
      <p v-if="!isLoading && ordinances.data.length === 0" class="no-results">
        No ordinances found matching "<strong>{{ filters.search }}</strong>" for the year <strong>{{ filters.year || 'All Years' }}</strong>.
      </p>
    </section>

    <div v-if="ordinances?.links && ordinances.links.length > 3" class="pagination-bar">
      <div class="results-info">
        Showing <span class="font-semibold">{{ ordinances?.meta?.from }}</span> to
        <span class="font-semibold">{{ ordinances?.meta?.to }}</span> of
        <span class="font-semibold">{{ ordinances?.meta?.total }}</span> results
      </div>
      <nav class="pagination-links" aria-label="Pagination">
        <button
          v-for="(link, index) in paginationLinks"
          :key="index"
          @click="paginate(link.url)"
          :disabled="!link.url"
          :class="['pagination-button', {
            'is-active': link.active,
            'is-disabled': !link.url
          }]"
          v-html="link.label"
        />
      </nav>
    </div>

    <transition name="modal">
      <div v-if="selectedOrdinance" class="modal-overlay" @click.self="closeModal" aria-modal="true" role="dialog" aria-labelledby="modal-title">
        <div class="modal-content" role="document" tabindex="-1">
          <button class="close-btn" @click="closeModal" aria-label="Close Ordinance Details">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M18 6L6 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M6 6L18 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <h2 class="modal-number" id="modal-title">{{ selectedOrdinance.ordinance_number }}</h2>
          <h3 class="modal-title-text">{{ selectedOrdinance.title_ordinances }}</h3>
          <p class="date">Date Approved: <strong>{{ formatDate(selectedOrdinance.date_approved_ordinances) }}</strong></p>
          <div class="image-preview-box" v-if="selectedOrdinance.image_ordinances">
            <img
              :src="`/storage/${selectedOrdinance.image_ordinances}`"
              :alt="`First page screenshot of Ordinance ${selectedOrdinance.ordinance_number}`"
              class="ordinance-image-preview"
            />
          </div>
          <div class="detail-content-box">
            <h4>Description:</h4>
            <p class="details">{{ selectedOrdinance.description_ordinances }}</p>
          </div>
          <a
            href="#"
            class="download-link"
            v-if="selectedOrdinance.file_path_ordinances"
            @click.prevent="handleDownloadClick"
          >
            Download Full Text (PDF)
          </a>
        </div>
      </div>
    </transition>

    <transition name="modal">
      <div
        v-if="showRequestModal"
        class="modal-overlay"
        @click.self="closeRequestModal"
        aria-modal="true"
        role="dialog"
      >
        <div class="modal-content request-modal-content" role="document" tabindex="-1">
          <button class="close-btn" @click="closeRequestModal" aria-label="Close">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M18 6L6 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M6 6L18 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
          <h2 class="modal-number">Request Access</h2>
          <p class="modal-title-text">
            State your purpose for requesting this ordinance.
          </p>
          <form @submit.prevent="submitRequestForm" class="request-form">
            <label for="purpose-textarea" class="form-label">Purpose of Request:</label>
            <textarea
              id="purpose-textarea"
              v-model="requestForm.purpose"
              class="form-textarea"
              placeholder="Explain why you need this ordinance (e.g., academic research, legal reference, personal interest)..."
              required
              maxlength="500"
            ></textarea>
            <button type="submit" class="download-link submit-button">
              Submit Request
            </button>
          </form>
        </div>
      </div>
    </transition>

    <FooterSection />
  </div>
</template>

<script setup lang="ts">
import Navbar from "@/components/Home/Navbar.vue";
import FooterSection from '@/components/Home/Footer.vue';
import { router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import _ from 'lodash';

const page = usePage();

interface Ordinance {
  id: number;
  ordinance_number: string;
  title_ordinances: string;
  description_ordinances: string;
  date_approved_ordinances: string;
  author_ordinances: string;
  file_path_ordinances: string | null;
  image_ordinances: string | null;
  details_ordinances?: string | null;
}

interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

interface PaginatedOrdinances {
  data: Ordinance[];
  links: PaginationLink[];
  meta: {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
  };
}

const ordinances = ref(page.props.ordinances as PaginatedOrdinances);
const filters = ref({ ...page.props.filters });
const years = page.props.years as number[];
const selectedOrdinance = ref<Ordinance | null>(null);
const isLoading = ref(false);
const showRequestModal = ref(false);
const requestForm = ref({
  purpose: "",
});

const debouncedSearch = _.debounce(() => {
  applyFilter();
}, 500);

const paginationLinks = computed(() => {
  return ordinances.value.links.map(link => {
    let label = link.label;
    if (label.includes('Previous')) label = 'Prev';
    else if (label.includes('Next')) label = 'Next';
    return { ...link, label };
  });
});

const paginate = (url: string | null) => {
  if (!url) return;
  isLoading.value = true;
  router.get(url, filters.value, {
    preserveState: true,
    preserveScroll: true,
    onFinish: (page) => {
      if (page.props.ordinances) {
        ordinances.value = page.props.ordinances as PaginatedOrdinances;
      }
      isLoading.value = false;
    }
  });
};

const applyFilter = () => {
  isLoading.value = true;
  router.get('/ordinances', filters.value, {
    preserveState: false,
    onFinish: (page) => {
      if (page.props.ordinances) {
        ordinances.value = page.props.ordinances as PaginatedOrdinances;
      }
      isLoading.value = false;
    }
  });
};

const showOrdinance = (ordinance: Ordinance) => {
  selectedOrdinance.value = ordinance;
};

const closeModal = () => {
  selectedOrdinance.value = null;
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const closeRequestModal = () => {
  showRequestModal.value = false;
  requestForm.value.purpose = "";
};

const handleDownloadClick = () => {
  const user = page.props.auth?.user;
  if (!user) {
    router.get('/login');
    return;
  }
  showRequestModal.value = true;
};

const submitRequestForm = () => {
  if (!selectedOrdinance.value) return;
  router.post(`/ordinances/${selectedOrdinance.value.id}/request-access`,
    { purpose: requestForm.value.purpose },
    {
      preserveScroll: true,
      onSuccess: () => {
        alert("Your request has been submitted! Please wait for admin approval.");
        closeRequestModal();
      }
    }
  );
};
</script>

<style>

/* ========================================================
   🎯 RESPONSIVE DESIGN - MOBILE-FIRST APPROACH
   ======================================================== */

:root {
  --primary-green: #1b5e20;
  --secondary-green: #388e3c;
  --accent-gold: #ffc107;
  --bg-light: #f9f9f9;
  --text-dark: #212121;
  --shadow-color: rgba(27, 94, 32, 0.15);
  --shadow-light: rgba(27, 94, 32, 0.1);
  --shadow-medium: rgba(27, 94, 32, 0.4);
  --main-green: var(--primary-green);
  --bg-page: var(--bg-light);
  --text-base: var(--text-dark);
  --card-shadow-style: 0 4px 10px var(--shadow-color);
  --border-color: #e0e0e0;
}

.ordinances-page {
  font-family: "Poppins", sans-serif;
  background: var(--bg-page);
  color: var(--text-base);
  min-height: 100vh;
  text-align: center;
}

/* ========================================================
   HEADER SECTION - MOBILE OPTIMIZED
   ======================================================== */

.ordinance-header {
  position: relative;
  color: white;
  padding: 60px 16px 80px;
  border-radius: 0 0 40px 40px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
  text-align: center;
  background:
    linear-gradient(rgba(27, 94, 32, 0.8), rgba(0, 0, 0, 0.6)),
    url('/images/lg.jpg') center/cover no-repeat;
  background-attachment: fixed;
}

.ordinance-header::after {
  content: "";
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
  height: 60px;
  background: radial-gradient(circle at 50% 0, transparent 60%, var(--bg-page) 100%);
  z-index: 1;
}

.header-title {
  font-size: 1.75rem;
  margin-bottom: 12px;
  font-weight: 900;
  text-shadow: 0 4px 15px rgba(0, 0, 0, 0.8);
  position: relative;
  z-index: 2;
  line-height: 1.2;
}

.header-subtitle {
  font-size: 0.95rem;
  max-width: 100%;
  margin: 0 auto;
  font-weight: 400;
  line-height: 1.5;
  color: #f1f1f1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
  position: relative;
  z-index: 2;
}

/* Tablet header adjustments */
@media (min-width: 640px) {
  .ordinance-header {
    padding: 80px 20px 100px;
    border-radius: 0 0 50px 50px;
  }

  .header-title {
    font-size: 2.2rem;
    margin-bottom: 15px;
  }

  .header-subtitle {
    font-size: 1rem;
    max-width: 600px;
  }
}

/* Desktop header adjustments */
@media (min-width: 1024px) {
  .ordinance-header {
    padding: 120px 20px 100px;
    border-radius: 0 0 60px 60px;
  }

  .header-title {
    font-size: 3.2rem;
  }

  .header-subtitle {
    font-size: 1.2rem;
    max-width: 700px;
  }
}

/* ========================================================
   CONTROLS BAR - RESPONSIVE LAYOUT
   ======================================================== */

.controls-bar {
  margin: -30px auto 30px;
  max-width: 1200px;
  padding: 0 16px;
  position: relative;
  z-index: 10;
  display: flex;
  flex-direction: column;
  gap: 12px;
  width: 100%;
}

/* Tablet and desktop controls in row layout */
@media (min-width: 640px) {
  .controls-bar {
    margin: -40px auto 50px;
    flex-direction: row;
    gap: 16px;
    padding: 0 20px;
  }
}

/* SEARCH BAR */
.search-bar {
  flex: 1;
  position: relative;
  min-width: 0;
}

.search-bar input {
  width: 100%;
  padding: 14px 24px 14px 50px;
  border: 2px solid var(--border-color);
  border-radius: 30px;
  font-size: 0.95rem;
  outline: none;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) inset;
  background-color: white;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.search-bar input:focus {
  border-color: var(--main-green);
  box-shadow: 0 0 0 3px var(--shadow-color);
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #9e9e9e;
  transition: color 0.3s;
  pointer-events: none;
  flex-shrink: 0;
}

.search-bar input:focus + .search-icon {
  color: var(--main-green);
}

/* FILTER DROPDOWN */
.filter-dropdown {
  flex-shrink: 0;
  width: 100%;
  min-width: 0;
}

/* Set minimum width for filter on larger screens */
@media (min-width: 640px) {
  .filter-dropdown {
    width: auto;
    min-width: 160px;
  }
}

.filter-dropdown select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  width: 100%;
  padding: 14px 16px;
  border: 2px solid var(--border-color);
  border-radius: 30px;
  font-size: 0.95rem;
  outline: none;
  background-color: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) inset;
  cursor: pointer;
  color: var(--text-base);
  transition: all 0.3s ease;
  box-sizing: border-box;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 8px center;
  background-size: 20px;
  padding-right: 36px;
}

.filter-dropdown select:hover {
  border-color: #c0c0c0;
}

.filter-dropdown select:focus {
  border-color: var(--accent-gold);
  box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
}

/* ========================================================
   ORDINANCE LIST - RESPONSIVE GRID
   ======================================================== */

.ordinance-list {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  padding: 20px 16px;
}

/* Two columns on tablet */
@media (min-width: 640px) {
  .ordinance-list {
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    padding: 30px 20px;
  }
}

/* Three columns on desktop */
@media (min-width: 1024px) {
  .ordinance-list {
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    padding: 40px 20px;
  }
}

/* ORDINANCE CARD */
.ordinance-card {
  background: white;
  border-radius: 16px;
  padding: 20px;
  text-align: left;
  box-shadow: var(--card-shadow-style);
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  border-left: 6px solid var(--main-green);
  display: flex;
  flex-direction: column;
  min-height: 240px;
}

/* Larger padding on tablet and desktop */
@media (min-width: 640px) {
  .ordinance-card {
    padding: 24px;
    border-left: 8px solid var(--main-green);
    border-radius: 18px;
  }
}

.ordinance-card:hover,
.ordinance-card:focus {
  transform: translateY(-8px) scale(1.015);
  box-shadow: 0 20px 40px var(--shadow-medium);
  border-left-color: var(--accent-gold);
  outline: none;
}

.ordinance-header-info {
  padding-bottom: 8px;
  margin-bottom: 12px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  gap: 4px;
}

.ordinance-number {
  font-size: 1rem;
  color: var(--main-green);
  font-weight: 800;
  margin: 0;
}

/* Larger number on tablet */
@media (min-width: 640px) {
  .ordinance-number {
    font-size: 1.1rem;
  }
}

.ordinance-card .date {
  font-size: 0.8rem;
  color: #666;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Slightly larger date text on larger screens */
@media (min-width: 640px) {
  .ordinance-card .date {
    font-size: 0.85rem;
  }
}

.ordinance-title {
  font-size: 1.2rem;
  color: var(--text-base);
  margin: 0;
  font-weight: 700;
  line-height: 1.3;
  flex-grow: 1;
}

/* Larger title on tablet */
@media (min-width: 640px) {
  .ordinance-title {
    font-size: 1.4rem;
  }
}

/* Even larger on desktop */
@media (min-width: 1024px) {
  .ordinance-title {
    font-size: 1.5rem;
  }
}

.ordinance-description {
  color: #555;
  font-size: 0.9rem;
  margin-top: 12px;
  min-height: 35px;
  max-height: 54px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

/* Three lines of description on tablet */
@media (min-width: 640px) {
  .ordinance-description {
    font-size: 0.95rem;
    -webkit-line-clamp: 3;
    max-height: 72px;
    margin-top: 14px;
  }
}

.read-more {
  margin-top: 16px;
  color: var(--main-green);
  font-size: 0.9rem;
  font-weight: 700;
  text-align: right;
  transition: color 0.2s;
}

/* Larger read-more text on tablet */
@media (min-width: 640px) {
  .read-more {
    font-size: 0.95rem;
  }
}

.ordinance-card:hover .read-more {
  color: var(--accent-gold);
}

/* ========================================================
   PAGINATION - RESPONSIVE
   ======================================================== */

.pagination-bar {
  max-width: 1200px;
  margin: 30px auto 40px;
  padding: 12px 16px;
  border-top: 1px solid var(--border-color);
  background-color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-direction: column;
  gap: 16px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

/* Row layout on tablet and desktop */
@media (min-width: 640px) {
  .pagination-bar {
    flex-direction: row;
    margin: 40px auto 60px;
    padding: 16px 20px;
    gap: 20px;
  }
}

.results-info {
  font-size: 0.85rem;
  color: #555;
  text-align: center;
  order: 2;
}

/* Normal size on tablet and desktop */
@media (min-width: 640px) {
  .results-info {
    font-size: 0.95rem;
    text-align: left;
    order: 1;
  }
}

.results-info .font-semibold {
  font-weight: 700;
  color: var(--text-base);
}

.pagination-links {
  display: inline-flex;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  order: 1;
}

/* Natural order on tablet */
@media (min-width: 640px) {
  .pagination-links {
    order: 2;
  }
}

.pagination-button {
  position: relative;
  padding: 10px 12px;
  font-size: 0.85rem;
  font-weight: 600;
  border: none;
  background-color: white;
  color: var(--main-green);
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  border-right: 1px solid var(--border-color);
  min-width: 36px;
}

/* Better spacing on tablet */
@media (min-width: 640px) {
  .pagination-button {
    padding: 10px 16px;
    font-size: 0.9rem;
    min-width: auto;
  }
}

.pagination-links button:last-child {
  border-right: none;
}

.pagination-button:hover:not(.is-active):not(.is-disabled) {
  background-color: var(--bg-light);
  color: var(--secondary-green);
}

.pagination-button.is-active {
  background-color: var(--main-green);
  color: white;
  pointer-events: none;
  box-shadow: 0 0 10px rgba(27, 94, 32, 0.4);
  z-index: 10;
}

.pagination-button.is-disabled {
  cursor: not-allowed;
  color: #a0a0a0;
  background-color: #f1f1f1;
}

/* ========================================================
   LOADING & NO RESULTS STATES
   ======================================================== */

.loading-state,
.no-results {
  grid-column: 1 / -1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 50px 16px;
  color: var(--main-green);
  font-size: 1rem;
}

/* More padding on larger screens */
@media (min-width: 640px) {
  .loading-state,
  .no-results {
    padding: 80px 20px;
    font-size: 1.1rem;
  }
}

.no-results {
  color: var(--text-base);
}

.no-results strong {
  color: var(--accent-gold);
}

.spinner {
  border: 4px solid rgba(27, 94, 32, 0.2);
  border-top: 4px solid var(--accent-gold);
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

/* Larger spinner on tablet */
@media (min-width: 640px) {
  .spinner {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(27, 94, 32, 0.2);
    border-top: 5px solid var(--accent-gold);
  }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* ========================================================
   MODAL STYLES - FULLY RESPONSIVE
   ======================================================== */

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(5px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 16px;
  overflow-y: auto;
}

.modal-content {
  background: white;
  padding: 24px;
  border-radius: 20px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7);
  text-align: left;
  outline: none;
  position: relative;
  border-top: 5px solid var(--main-green);
  max-height: 90vh;
  overflow-y: auto;
  box-sizing: border-box;
}

/* More padding on larger screens */
@media (min-width: 640px) {
  .modal-content {
    padding: 32px;
    border-radius: 24px;
    max-width: 700px;
  }
}

/* Even more padding on desktop */
@media (min-width: 1024px) {
  .modal-content {
    padding: 40px;
    max-width: 800px;
  }
}

.modal-number {
  color: var(--main-green);
  font-size: 1.6rem;
  margin-bottom: 8px;
  font-weight: 800;
  word-break: break-word;
}

/* Larger number on larger screens */
@media (min-width: 640px) {
  .modal-number {
    font-size: 1.8rem;
  }
}

/* Even larger on desktop */
@media (min-width: 1024px) {
  .modal-number {
    font-size: 2rem;
  }
}

.modal-title-text {
  color: var(--text-base);
  font-size: 1.2rem;
  margin-bottom: 12px;
  font-weight: 600;
  line-height: 1.3;
}

/* Larger title on larger screens */
@media (min-width: 640px) {
  .modal-title-text {
    font-size: 1.4rem;
    margin-bottom: 14px;
  }
}

/* Even larger on desktop */
@media (min-width: 1024px) {
  .modal-title-text {
    font-size: 1.5rem;
  }
}

.modal-content .date {
  color: #666;
  font-size: 0.9rem;
  font-style: normal;
  padding-bottom: 12px;
  margin-bottom: 16px;
  border-bottom: 2px solid var(--bg-page);
}

/* Larger date on larger screens */
@media (min-width: 640px) {
  .modal-content .date {
    font-size: 0.95rem;
    padding-bottom: 14px;
    margin-bottom: 20px;
  }
}

/* IMAGE PREVIEW */
.image-preview-box {
  border: 1px solid var(--border-color);
  border-radius: 12px;
  margin: 12px 0 18px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  background: #fff;
}

/* More margin on larger screens */
@media (min-width: 640px) {
  .image-preview-box {
    margin: 16px 0 22px;
    border-radius: 14px;
  }
}

.ordinance-image-preview {
  display: block;
  width: 100%;
  height: auto;
  max-height: 300px;
  object-fit: contain;
  padding: 4px;
}

/* Larger image on tablet */
@media (min-width: 640px) {
  .ordinance-image-preview {
    max-height: 380px;
    padding: 6px;
  }
}

/* Even larger on desktop */
@media (min-width: 1024px) {
  .ordinance-image-preview {
    max-height: 400px;
    padding: 8px;
  }
}

/* DETAIL CONTENT BOX */
.detail-content-box {
  background: #e8f5e9;
  border: 1px solid #c8e6c9;
  border-left: 5px solid var(--accent-gold);
  padding: 18px;
  border-radius: 12px;
  margin-bottom: 16px;
}

/* More padding on larger screens */
@media (min-width: 640px) {
  .detail-content-box {
    padding: 22px;
    margin-bottom: 20px;
    border-radius: 14px;
  }
}

/* Even more on desktop */
@media (min-width: 1024px) {
  .detail-content-box {
    padding: 25px;
    margin-bottom: 24px;
  }
}

.detail-content-box h4 {
  color: var(--main-green);
  font-size: 1rem;
  margin-top: 0;
  margin-bottom: 12px;
  border-bottom: 1px dashed #a5d6a7;
  padding-bottom: 8px;
}

/* Larger heading on larger screens */
@media (min-width: 640px) {
  .detail-content-box h4 {
    font-size: 1.1rem;
    margin-bottom: 14px;
    padding-bottom: 10px;
  }
}

.modal-content .details {
  font-size: 0.95rem;
  line-height: 1.7;
  color: #333;
  white-space: pre-wrap;
  word-break: break-word;
}

/* Larger text on larger screens */
@media (min-width: 640px) {
  .modal-content .details {
    font-size: 1rem;
    line-height: 1.8;
  }
}

/* CLOSE BUTTON */
.close-btn {
  position: absolute;
  top: 16px;
  right: 16px;
  background: var(--bg-page);
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 2px 5px var(--shadow-light);
  z-index: 10;
  flex-shrink: 0;
}

/* Larger close button on larger screens */
@media (min-width: 640px) {
  .close-btn {
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
  }
}

.close-btn:hover {
  background: var(--accent-gold);
  transform: rotate(90deg);
}

.close-btn svg path {
  stroke: var(--main-green);
}

/* DOWNLOAD/SUBMIT BUTTON */
.download-link {
  display: inline-block;
  margin-top: 16px;
  padding: 12px 20px;
  background-color: var(--main-green);
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: 600;
  transition: background-color 0.3s, transform 0.1s;
  text-align: center;
  border: none;
  cursor: pointer;
  font-size: 0.95rem;
  width: 100%;
}

/* Better padding on larger screens */
@media (min-width: 640px) {
  .download-link {
    margin-top: 20px;
    padding: 14px 28px;
    font-size: 1rem;
    width: auto;
  }
}

.download-link:hover {
  background-color: var(--secondary-green);
  transform: translateY(-1px);
}

/* ========================================================
   REQUEST MODAL SPECIFIC STYLES
   ======================================================== */

.request-modal-content {
  max-width: 500px;
  border-top: 5px solid var(--accent-gold);
  text-align: center;
}

/* Slightly larger on tablet */
@media (min-width: 640px) {
  .request-modal-content {
    max-width: 550px;
  }
}

.request-form {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  margin-top: 20px;
}

.form-label {
  align-self: flex-start;
  margin-bottom: 8px;
  font-weight: 600;
  color: var(--text-base);
  font-size: 0.9rem;
}

/* Larger label on larger screens */
@media (min-width: 640px) {
  .form-label {
    font-size: 0.95rem;
    margin-bottom: 10px;
  }
}

.form-textarea {
  width: 100%;
  padding: 12px;
  border: 2px solid var(--border-color);
  border-radius: 10px;
  font-size: 0.9rem;
  min-height: 120px;
  resize: vertical;
  outline: none;
  transition: border-color 0.3s, box-shadow 0.3s;
  font-family: inherit;
  box-sizing: border-box;
}

/* Larger textarea on larger screens */
@media (min-width: 640px) {
  .form-textarea {
    padding: 14px;
    border-radius: 12px;
    font-size: 0.95rem;
    min-height: 140px;
  }
}

/* Even larger on desktop */
@media (min-width: 1024px) {
  .form-textarea {
    padding: 15px;
    font-size: 1rem;
    min-height: 150px;
  }
}

.form-textarea:focus {
  border-color: var(--main-green);
  box-shadow: 0 0 0 3px rgba(27, 94, 32, 0.1);
}

.request-form .submit-button {
  margin-top: 20px;
  width: 100%;
}

/* Constrained width on larger screens */
@media (min-width: 640px) {
  .request-form .submit-button {
    margin-top: 24px;
    width: auto;
    align-self: center;
    max-width: 280px;
  }
}

/* Wider on desktop */
@media (min-width: 1024px) {
  .request-form .submit-button {
    max-width: 320px;
  }
}

/* ========================================================
   TRANSITIONS
   ======================================================== */

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.4s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .modal-content,
.modal-leave-active .modal-content {
  transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.modal-enter-from .modal-content {
  transform: scale(0.8) translateY(50px);
}

.modal-leave-to .modal-content {
  transform: scale(0.8) translateY(-50px);
}
</style>
