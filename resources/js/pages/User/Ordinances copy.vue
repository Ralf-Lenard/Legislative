<template>
    <div class="ordinances-page">
      <Navbar />
  
      <!-- Header -->
      <section class="ordinance-header">
        <div class="header-overlay"></div>
        <h1 class="header-title">Municipal Ordinances</h1>
        <p class="header-subtitle">
          Explore the approved ordinances of the Municipality of Concepcion, Tarlac.
          We commit to <strong>Transparency</strong>, <strong>Integrity</strong>, and <strong>Public Service</strong>.
        </p>
      </section>
  
      <!-- Search & Filter -->
      <div class="controls-bar">
        <div class="search-bar">
          <input
            type="text"
            v-model="filters.search"
            placeholder="Search ordinance by title or number..."
            @input="debouncedSearch"
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
  
      <!-- Ordinances List -->
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
  
        <!-- Pagination -->
        <div v-if="ordinances?.links && ordinances.links.length > 3" class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-4 sm:px-6">
  <!-- Results info -->
  <div class="text-sm text-slate-600">
    Showing <span class="font-semibold">{{ ordinances?.meta?.from }}</span> to
    <span class="font-semibold">{{ ordinances?.meta?.to }}</span> of
    <span class="font-semibold">{{ ordinances?.meta?.total }}</span> results
  </div>

  <!-- Pagination links -->
  <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
    <component
      v-for="(link, index) in ordinances?.links"
      :key="index"
      :is="link.url ? 'button' : 'span'"
      @click="link.url ? paginate(link.url) : null"
      :disabled="!link.url"
      :class="[
        'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-slate-300 ring-inset focus:z-20 transition-all',
        index === 0 ? 'rounded-l-md' : '',
        index === ordinances.links.length - 1 ? 'rounded-r-md' : '',
        link.active
          ? 'bg-emerald-600 text-white ring-emerald-600'
          : link.url
          ? 'text-slate-900 hover:bg-slate-50'
          : 'cursor-not-allowed text-slate-400 bg-slate-100'
      ]"
      v-html="link.label"
    />
  </nav>
</div>

      </section>
  
      <!-- Modal -->
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
            <div class="detail-content-box">
              <h4>Full Details:</h4>
              <p class="details">{{ selectedOrdinance.description_ordinances }}</p>
            </div>
            <a :href="selectedOrdinance.file_path_ordinances" target="_blank" class="download-link" v-if="selectedOrdinance.file_path_ordinances">
              Download Full Text (PDF)
            </a>
          </div>
        </div>
      </transition>
  
      <FooterSection />
    </div>
  </template>
  
  <script setup lang="ts">
  import Navbar from "@/components/Home/Navbar.vue";
  import FooterSection from '@/components/Home/Footer.vue';
  import { usePage } from '@inertiajs/vue3';
  import { ref } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import _ from 'lodash';
  
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
  
  interface PaginatedOrdinances {
    data: Ordinance[];
    links: { url: string | null; label: string; active: boolean }[];
    meta: { current_page: number; from: number; last_page: number; per_page: number; to: number; total: number };
  }
  
  const { props } = usePage<{
    ordinances: PaginatedOrdinances;
    filters: { search?: string; year?: string };
    years: number[];
    flash?: { success?: string };
  }>();
  
  const ordinances = ref(props.ordinances);
  const filters = ref({ ...props.filters });
  const years = props.years;
  
  const selectedOrdinance = ref<Ordinance | null>(null);
  const isLoading = ref(false);
  
  const debouncedSearch = _.debounce(() => {
    applyFilter();
  }, 500);

  const paginate = (url: string) => {
  if (!url) return;
  isLoading.value = true;

  // Parse the page number from the URL
  const pageParam = new URL(url, window.location.origin).searchParams.get('page') || 1;

  Inertia.get('/ordinances', { ...filters.value, page: pageParam }, {
    preserveState: true,
    onFinish: () => { isLoading.value = false; }
  });
};

  
  const applyFilter = () => {
    isLoading.value = true;
    Inertia.get('/ordinances', filters.value, {
      preserveState: true,
      onFinish: () => (isLoading.value = false),
    });
  };
  
  const showOrdinance = (ordinance: Ordinance) => {
    selectedOrdinance.value = ordinance;
  };
  
  const closeModal = () => {
    selectedOrdinance.value = null;
  };
  
  const changePage = (page: number) => {
    isLoading.value = true;
    Inertia.get('/ordinances', { ...filters.value, page }, {
      preserveState: true,
      onFinish: () => (isLoading.value = false),
    });
  };
  
  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
  };
  </script>
  
<style>
/* --- Global Variables (Synced with user's requested roots) --- */
:root {
    --primary-green: #1b5e20; /* Darker, formal green */
    --secondary-green: #388e3c; /* Medium green */
    --accent-gold: #ffc107; /* Bright gold for accent */
    --bg-light: #f9f9f9; /* Off-white for background */
    --text-dark: #212121;
    --shadow-color: rgba(27, 94, 32, 0.15);
    
    /* Added all missing shadow variables for consistency */
    --shadow-light: rgba(27, 94, 32, 0.1);
    --shadow-medium: rgba(27, 94, 32, 0.4);
    
    /* Mapped variables used throughout the component: */
    --main-green: var(--primary-green);
    --bg-page: var(--bg-light);
    --text-base: var(--text-dark);
    --card-shadow-style: 0 4px 10px var(--shadow-color);
}

.ordinances-page {
    font-family: "Poppins", sans-serif;
    background: var(--bg-page);
    color: var(--text-base);
    min-height: 100vh;
    text-align: center;
}

/* --- Header (Elegant Photo Overlay Design) --- */
.ordinance-header {
    position: relative;
    color: white;
    padding: 120px 20px 100px;
    border-radius: 0 0 60px 60px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    text-align: center;
    background: 
        linear-gradient(rgba(27, 94, 32, 0.8), rgba(0, 0, 0, 0.6)),
        url('images/lg.jpg') center/cover no-repeat;
    background-attachment: fixed;
}

.ordinance-header::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 100px;
    background: radial-gradient(circle at 50% 0, transparent 60%, var(--bg-page) 100%);
    z-index: 1;
}

.header-title {
    font-size: 3.2rem;
    margin-bottom: 15px;
    font-weight: 900;
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.8);
    position: relative;
    z-index: 2;
}

.header-subtitle {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 400;
    line-height: 1.6;
    color: #f1f1f1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.6);
    position: relative;
    z-index: 2;
}

/* --- Controls Bar (Search and Filter) --- */
.controls-bar {
    margin: -40px auto 50px;
    max-width: 1000px;
    padding: 0 20px;
    position: relative;
    z-index: 10;
    display: flex;
    gap: 20px;
}

/* --- Search --- */
.search-bar {
    flex-grow: 1;
    position: relative;
}

.search-bar input {
    width: 100%;
    padding: 18px 30px 18px 60px;
    border: 2px solid #e0e0e0; 
    border-radius: 40px;
    font-size: 1.05rem;
    outline: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) inset;
    background-color: white;
    transition: all 0.3s;
}

.search-bar input:focus {
    border-color: var(--main-green);
    box-shadow: 0 0 0 3px var(--shadow-color);
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #9e9e9e;
    transition: color 0.3s;
    pointer-events: none;
}

.search-bar input:focus + .search-icon {
    color: var(--main-green);
}

/* --- Filter Dropdown --- */
.filter-dropdown {
    flex-shrink: 0;
    min-width: 180px;
    position: relative;
}

.filter-dropdown select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    padding: 18px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 40px;
    font-size: 1.05rem;
    outline: none;
    background-color: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) inset;
    cursor: pointer;
    color: var(--text-base);
    transition: all 0.3s;
}

.filter-dropdown select:hover {
    border-color: #c0c0c0;
}

.filter-dropdown select:focus {
    border-color: var(--accent-gold);
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
}

/* --- Ordinance Cards --- */
.ordinance-list {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    padding: 40px 20px;
}

.ordinance-card {
    background: white;
    border-radius: 18px;
    padding: 30px;
    text-align: left;
    box-shadow: var(--card-shadow-style);
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-left: 8px solid var(--main-green); 
    display: flex;
    flex-direction: column;
    min-height: 250px;
}

.ordinance-card:hover, .ordinance-card:focus {
    transform: translateY(-10px) scale(1.015);
    box-shadow: 0 20px 40px var(--shadow-medium); 
    border-left-color: var(--accent-gold); 
    outline: none;
}

.ordinance-header-info {
    padding-bottom: 10px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.ordinance-number {
    font-size: 1.1rem;
    color: var(--main-green);
    font-weight: 800;
    margin: 0;
}

.ordinance-card .date {
    font-size: 0.85rem;
    color: #666;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ordinance-title {
    font-size: 1.5rem;
    color: var(--text-base);
    margin: 0;
    font-weight: 700;
    line-height: 1.3;
    flex-grow: 1; 
}

.ordinance-description {
    color: #555;
    font-size: 0.95rem;
    margin-top: 15px;
    min-height: 40px; 
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3; 
    -webkit-box-orient: vertical;
}

.read-more {
    margin-top: 20px;
    color: var(--main-green); 
    font-size: 0.95rem;
    font-weight: 700;
    text-align: right;
    transition: color 0.2s;
}

.ordinance-card:hover .read-more {
    color: var(--accent-gold);
}

/* --- Loading State & No Results --- */
.loading-state, .no-results {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 0;
    color: var(--main-green);
    font-size: 1.1rem;
}

.no-results {
    color: var(--text-base);
}

.no-results strong {
    color: var(--accent-gold);
}

.spinner {
    border: 5px solid rgba(27, 94, 32, 0.2);
    border-top: 5px solid var(--accent-gold);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* --- Modal --- */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85); 
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    padding: 40px;
    border-radius: 25px;
    width: 90%;
    max-width: 800px;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7);
    text-align: left;
    outline: none;
    position: relative;
    border-top: 5px solid var(--main-green);
    max-height: 90vh;
    overflow-y: auto;
}

.modal-number {
    color: var(--main-green);
    font-size: 2rem;
    margin-bottom: 5px;
    font-weight: 800;
}

.modal-title-text {
    color: var(--text-base);
    font-size: 1.5rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.modal-content .date {
    color: #666;
    font-size: 0.95rem;
    font-style: normal;
    padding-bottom: 15px;
    margin-bottom: 20px;
    border-bottom: 2px solid var(--bg-page);
}

.detail-content-box {
    background: #e8f5e9;
    border: 1px solid #c8e6c9;
    border-left: 5px solid var(--accent-gold);
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.detail-content-box h4 {
    color: var(--main-green);
    font-size: 1.2rem;
    margin-top: 0;
    margin-bottom: 15px;
    border-bottom: 1px dashed #a5d6a7;
    padding-bottom: 10px;
}

.modal-content .details {
    font-size: 1rem;
    line-height: 1.8;
    color: #333;
    white-space: pre-wrap;
}

.close-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--bg-page);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 2px 5px var(--shadow-light);
}

.close-btn:hover {
    background: var(--accent-gold);
    transform: rotate(90deg);
}

.close-btn svg path {
    stroke: var(--main-green);
}

.download-link {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: var(--main-green); 
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.download-link:hover {
    background-color: var(--secondary-green);
}

/* --- Vue Transitions (Modal) --- */
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.4s ease;
}

.modal-enter-from, .modal-leave-to {
    opacity: 0;
}

.modal-enter-active .modal-content, .modal-leave-active .modal-content {
    transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}

.modal-enter-from .modal-content {
    transform: scale(0.8) translateY(100px);
}

.modal-leave-to .modal-content {
    transform: scale(0.8) translateY(-100px);
}

/* --- Responsive: Large Desktop (1200px and up) --- */
@media (max-width: 1200px) {
    .ordinance-list {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}

/* --- Responsive: Medium Devices (992px and below) --- */
@media (max-width: 992px) {
    .header-title {
        font-size: 2.8rem;
    }

    .header-subtitle {
        font-size: 1.1rem;
    }

    .ordinance-list {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        padding: 30px 20px;
    }

    .ordinance-card {
        padding: 25px;
        min-height: 240px;
    }
}

/* --- Responsive: Tablet (768px and below) --- */
@media (max-width: 768px) {
    .ordinance-header {
        padding: 70px 20px 50px;
        border-radius: 0 0 30px 30px;
        background-attachment: scroll;
    }

    .ordinance-header::after {
        height: 60px;
    }

    .header-title {
        font-size: 2.2rem;
        margin-bottom: 12px;
    }

    .header-subtitle {
        font-size: 1rem;
        line-height: 1.5;
    }

    /* stack controls vertically for better mobile touch targets */
    .controls-bar {
        flex-direction: column;
        gap: 15px;
        margin: -30px auto 40px;
        max-width: 100%;
    }

    .search-bar input {
        padding: 15px 20px 15px 50px;
        font-size: 1rem;
    }

    .search-icon {
        left: 18px;
    }

    .filter-dropdown {
        min-width: 100%;
    }

    .filter-dropdown select {
        padding: 15px 20px;
        font-size: 1rem;
    }

    .ordinance-list {
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        padding: 20px 15px;
        gap: 20px;
    }

    .ordinance-card {
        padding: 20px;
        min-height: 220px;
        border-left-width: 6px;
    }

    .ordinance-number {
        font-size: 1rem;
    }

    .ordinance-title {
        font-size: 1.3rem;
    }

    .ordinance-description {
        font-size: 0.9rem;
    }

    .modal-content {
        padding: 30px 20px;
        width: 95%;
        border-radius: 20px;
    }

    .modal-number {
        font-size: 1.5rem;
    }

    .modal-title-text {
        font-size: 1.2rem;
    }

    .detail-content-box {
        padding: 15px;
        border-left-width: 4px;
    }

    .detail-content-box h4 {
        font-size: 1.1rem;
    }

    .close-btn {
        top: 15px;
        right: 15px;
        width: 35px;
        height: 35px;
    }

    .loading-state, .no-results {
        padding: 60px 0;
        font-size: 1rem;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(27, 94, 32, 0.2);
        border-top: 4px solid var(--accent-gold);
    }
}

/* --- Responsive: Small Mobile (480px and below) --- */
@media (max-width: 480px) {
    .ordinance-header {
        padding: 50px 15px 40px;
        border-radius: 0 0 20px 20px;
        margin-top: 40px;
    }

    .ordinance-header::after {
        height: 50px;
    }

    .header-title {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .header-subtitle {
        font-size: 0.95rem;
        line-height: 1.4;
        max-width: 90%;
    }

    .controls-bar {
        margin: -25px auto 30px;
        padding: 0 15px;
        gap: 12px;
    }

    .search-bar input,
    .filter-dropdown select {
        padding: 12px 15px;
        font-size: 16px;
        border-radius: 30px;
    }

    .filter-dropdown select {
        text-align: center;
    }

    .search-bar input{
        padding-left: 50px;
    }

    .search-icon {
        left: 15px;
    }

    .ordinance-list {
        grid-template-columns: 1fr;
        padding: 15px;
        gap: 15px;
    }

    .ordinance-card {
        padding: 15px;
        min-height: auto;
        border-left-width: 5px;
    }

    .ordinance-header-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .ordinance-number {
        font-size: 0.95rem;
    }

    .ordinance-card .date {
        font-size: 0.8rem;
        width: 100%;
    }

    .ordinance-title {
        font-size: 1.15rem;
        line-height: 1.2;
    }

    .ordinance-description {
        font-size: 0.85rem;
        margin-top: 10px;
        min-height: 30px;
        max-height: 50px;
        -webkit-line-clamp: 2;
    }

    .read-more {
        font-size: 0.85rem;
        margin-top: 12px;
    }

    .modal-overlay {
        padding: 15px;
    }

    .modal-content {
        width: 100%;
        max-width: none;
        padding: 20px;
        border-radius: 15px;
    }

    .modal-number {
        font-size: 1.3rem;
        margin-bottom: 8px;
    }

    .modal-title-text {
        font-size: 1.1rem;
        margin-bottom: 12px;
    }

    .modal-content .date {
        font-size: 0.9rem;
        padding-bottom: 12px;
        margin-bottom: 15px;
    }

    .detail-content-box {
        padding: 12px;
        margin-bottom: 15px;
        border-left-width: 3px;
    }

    .detail-content-box h4 {
        font-size: 1rem;
        margin-bottom: 10px;
        padding-bottom: 8px;
    }

    .modal-content .details {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .close-btn {
        top: 12px;
        right: 12px;
        width: 30px;
        height: 30px;
    }

    .download-link {
        padding: 8px 16px;
        font-size: 0.9rem;
        margin-top: 15px;
    }

    .loading-state, .no-results {
        padding: 40px 15px;
        font-size: 0.95rem;
    }

    .spinner {
        width: 35px;
        height: 35px;
        border: 3px solid rgba(27, 94, 32, 0.2);
        border-top: 3px solid var(--accent-gold);
        margin-bottom: 15px;
    }
}

/* --- Responsive: Extra Small Mobile (320px and below) --- */
@media (max-width: 320px) {
    .ordinance-header {
        padding: 40px 10px 30px;
    }

    .header-title {
        font-size: 1.5rem;
    }

    .header-subtitle {
        font-size: 0.9rem;
    }

    .controls-bar {
        margin: -20px auto 25px;
        padding: 0 10px;
        gap: 10px;
    }

    .ordinance-list {
        padding: 10px;
    }

    .ordinance-card {
        padding: 12px;
    }

    .ordinance-title {
        font-size: 1rem;
    }

    .modal-content {
        padding: 15px;
    }
}
</style>
