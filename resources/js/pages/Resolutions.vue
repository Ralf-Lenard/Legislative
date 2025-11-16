<template>
    <div class="resolutions-page">
        <Navbar />

        <section class="resolution-header">
            <h1 class="header-title">Municipal Resolutions</h1>
            <p class="header-subtitle">
                Discover the approved resolutions of the Municipality of Concepcion, Tarlac.
                Promoting <strong>good governance</strong> and <strong>transparency</strong>.
            </p>
        </section>

        <div class="search-bar">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search resolution by title or number..."
                @input="debouncedSearch"
                aria-label="Search resolutions"
            />
            <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <section class="resolution-list">
            <div v-if="isLoading" class="loading-state">
                <div class="spinner"></div>
                <p>Filtering resolutions...</p>
            </div>

            <div
                v-for="(resolution, index) in filteredResolutions"
                :key="index"
                class="resolution-card"
                @click="showResolution(resolution)"
                role="button"
                tabindex="0"
                @keyup.enter="showResolution(resolution)"
            >
                <div class="resolution-header-info">
                    <h3 class="resolution-number">{{ resolution.number }}</h3>
                    <span class="date"><i class="fas fa-calendar-alt"></i> Date Approved: {{ resolution.date }}</span>
                </div>
                <h2 class="resolution-title">{{ resolution.title }}</h2>
                <p class="resolution-summary">{{ resolution.summary }}</p>
                <div class="read-more">View Details <i class="fas fa-arrow-right"></i></div>
            </div>

            <p v-if="!isLoading && filteredResolutions.length === 0" class="no-results">
                No resolutions found for “**{{ searchQuery }}**”.
            </p>
        </section>

        <transition name="modal">
            <div
                v-if="selectedResolution"
                class="modal-overlay"
                @click.self="closeModal"
                aria-modal="true"
                role="dialog"
                aria-labelledby="modal-title"
            >
                <div class="modal-content" ref="modalContent" tabindex="-1">
                    <button class="close-btn" @click="closeModal" aria-label="Close Resolution Details">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 6L18 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <h2 class="modal-number" id="modal-title">{{ selectedResolution.number }}</h2>
                    <h3 class="modal-title-text">{{ selectedResolution.title }}</h3>
                    <p class="date">Date Approved: **{{ selectedResolution.date }}**</p>
                    <div class="detail-content-box">
                        <h4>Full Details:</h4>
                        <p class="details">{{ selectedResolution.details }}</p>
                    </div>
                    <a href="#" class="download-link" @click.prevent="">Download Full Text (PDF)</a>
                </div>
            </div>
        </transition>

        <FooterSection />
    </div>
</template>

<script>
import Navbar from "@/components/Home/Navbar.vue";
import FooterSection from '@/components/Home/Footer.vue';

const debounce = (fn, delay) => {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn.apply(this, args), delay);
    };
};

export default {
    name: "ResolutionsPage",
    components: { Navbar, FooterSection },
    data() {
        return {
            searchQuery: "",
            selectedResolution: null,
            isLoading: false,
            resolutions: [
                {
                    number: "Resolution No. 2025-010",
                    title: "A Resolution Commending Barangay Volunteers for Their Public Service",
                    date: "March 8, 2025",
                    summary:
                        "Recognizes the efforts of barangay volunteers for their continuous support during community development programs.",
                    details:
                        "This resolution expresses the municipality’s appreciation to barangay volunteers for their commitment and dedication in assisting with local projects and disaster response efforts.",
                },
                {
                    number: "Resolution No. 2024-022",
                    title: "A Resolution Supporting Environmental Cleanup Drives",
                    date: "December 5, 2024",
                    summary:
                        "Encourages all barangays to participate in monthly cleanup activities to maintain a cleaner and healthier environment.",
                    details:
                        "The resolution aims to institutionalize monthly community cleanup drives led by barangay councils, youth groups, and environmental offices.",
                },
                {
                    number: "Resolution No. 2024-015",
                    title: "A Resolution Authorizing the Mayor to Enter into a MOA with DENR",
                    date: "September 20, 2024",
                    summary:
                        "Grants authority to the Mayor to sign a Memorandum of Agreement with the Department of Environment and Natural Resources.",
                    details:
                        "This resolution allows the Mayor of Concepcion to formalize a partnership with DENR for reforestation and solid waste management programs.",
                },
                {
                    number: "Resolution No. 2023-009",
                    title: "A Resolution to Allocate Budget for Animal Shelter Renovation",
                    date: "April 25, 2023",
                    summary:
                        "Allocates municipal funds to improve facilities at the local animal shelter for rescued animals.",
                    details:
                        "This resolution approves a ₱200,000 budget allocation for shelter repairs, improved animal cages, and proper sanitation systems.",
                },
            ],
        };
    },
    computed: {
        filteredResolutions() {
            if (this.isLoading) return [];

            const query = this.searchQuery.toLowerCase().trim();

            if (!query) {
                // Return all resolutions sorted by date (newest first)
                return [...this.resolutions].sort((a, b) => new Date(b.date) - new Date(a.date));
            }
            
            return this.resolutions.filter(
                (res) =>
                    res.title.toLowerCase().includes(query) ||
                    res.number.toLowerCase().includes(query)
            ).sort((a, b) => new Date(b.date) - new Date(a.date));
        },
    },
    methods: {
        showResolution(resolution) {
            this.selectedResolution = resolution;
            this.$nextTick(() => {
                const modal = this.$refs.modalContent;
                if (modal) modal.focus();
            });
        },
        closeModal() {
            this.selectedResolution = null;
        },
        debouncedSearch: debounce(function () {
            this.isLoading = true;
            // Simulate a small delay for filtering feedback
            setTimeout(() => (this.isLoading = false), 300);
        }, 300),
    },
    // Add event listener to close modal on ESC key
    mounted() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.selectedResolution) {
                this.closeModal();
            }
        });
    },
    beforeUnmount() {
        document.removeEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.selectedResolution) {
                this.closeModal();
            }
        });
    }
};
</script>

<style>
/* --- Global Variables (Matching Ordinance Page) --- */
:root {
    --primary-color: #2e7d32; /* A slightly brighter, more vibrant green */
    --secondary-color: #ffb300; /* Gold/Yellow for accents and highlights */
    --background-light: #f4f8f4; /* Light background */
    --text-dark: #2c3e50; /* Darker text for better contrast */
    --shadow-light: rgba(0, 0, 0, 0.1);
    --shadow-medium: rgba(0, 0, 0, 0.2);
    
    /* Variables used in the component design: */
    --main-green: var(--primary-color);
    --accent-gold: var(--secondary-color);
    --bg-page: var(--background-light);
    --text-base: var(--text-dark);
    --card-shadow-style: 0 4px 10px rgba(0, 0, 0, 0.05);
}

/* --- Header (Elegant Photo Overlay Design like Ordinances) --- */
.resolution-header {
    position: relative;
    color: white;
    padding: 120px 20px 100px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    text-align: center;
    background: 
        linear-gradient(rgba(0, 60, 0, 0.65), rgba(0, 0, 0, 0.6)),
        url('images/lg.jpg') center/cover no-repeat;
    background-attachment: fixed;
    overflow: hidden;
}

/* Decorative curved fade at the bottom */
.resolution-header::after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 100px;
    background: radial-gradient(circle at 50% 0, transparent 60%, var(--bg-page) 100%);
    z-index: 1;
}

/* Title & Subtitle Styling (consistent with Ordinances) */
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

.header-subtitle strong {
    color: var(--accent-gold);
    font-weight: 700;
}


/* --- Search (IMPROVED) --- */
.search-bar {
    margin: -40px auto 50px;
    max-width: 800px;
    padding: 0 20px;
    position: relative;
    z-index: 10;
}
.search-bar input {
    width: 100%;
    padding: 18px 30px 18px 60px; /* Adjusted padding for icon placement */
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
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2); 
}
.search-icon {
    position: absolute;
    left: 45px; 
    right: auto;
    top: 50%;
    transform: translateY(-50%);
    color: #9e9e9e; 
    transition: color 0.3s;
    pointer-events: none;
}
.search-bar input:focus + .search-icon {
    color: var(--main-green); 
}


/* --- Resolution Cards (IMPROVED) --- */
.resolution-list {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    padding: 40px 20px;
}
.resolution-card {
    background: white;
    border-radius: 18px;
    padding: 30px;
    text-align: left;
    box-shadow: var(--card-shadow-style);
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-left: 8px solid var(--main-green); /* Primary Green border */
    display: flex;
    flex-direction: column;
}
.resolution-card:hover, .resolution-card:focus {
    transform: translateY(-10px) scale(1.015);
    box-shadow: 0 20px 40px var(--shadow-medium);
    border-left-color: var(--accent-gold); /* Accent Gold on hover */
    outline: none;
}
.resolution-header-info {
    padding-bottom: 10px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.resolution-number {
    font-size: 1.1rem;
    color: var(--main-green);
    font-weight: 800;
    margin: 0;
}
.resolution-card .date {
    font-size: 0.85rem;
    color: #666;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
}
.resolution-title {
    font-size: 1.5rem;
    color: var(--text-base);
    margin: 0;
    font-weight: 700;
    line-height: 1.3;
    flex-grow: 1; 
}
.resolution-summary {
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
.resolution-card:hover .read-more {
    color: var(--accent-gold);
}


/* --- Loading State & Spinner --- */
.loading-state {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 0;
    color: var(--main-green);
    font-size: 1.1rem;
}
.spinner {
    border: 5px solid rgba(46, 125, 50, 0.2);
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


/* --- Modal (IMPROVED) --- */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85); 
    backdrop-filter: blur(5px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
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
    background: #e8f5e9; /* Lightened green background */
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
    background-color: var(--main-green); /* Primary Green Button */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 600;
    transition: background-color 0.3s;
}
.download-link:hover {
    background-color: #1b5e20; /* Slightly darker green on hover */
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


/* --- Responsive --- */
@media (max-width: 768px) {
    .resolution-header {
        padding: 70px 20px 50px;
        border-radius: 0 0 30px 30px;
    }
    .header-title {
        font-size: 2.2rem;
    }
    .search-bar {
        margin: -30px auto 40px;
    }
    .search-bar input {
        padding: 15px 20px 15px 50px; /* Adjusted padding for mobile */
    }
    .search-icon {
        left: 35px; /* Adjusted position for mobile */
    }
    .resolution-list {
        grid-template-columns: 1fr;
        padding: 20px;
        gap: 25px;
    }
    .resolution-card {
        padding: 20px;
    }
    .resolution-title {
        font-size: 1.3rem;
    }
    .modal-content {
        padding: 30px 20px;
    }
    .modal-number {
        font-size: 1.5rem;
    }
    .modal-title-text {
        font-size: 1.2rem;
    }
    .detail-content-box {
        padding: 15px;
    }
    .close-btn {
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
    }
}
</style>