<template>
    <div class="ordinances-page">
        <Navbar />

        <section class="ordinance-header">
            <h1 class="header-title">Municipal Ordinances 🏛️</h1>
            <p class="header-subtitle">Explore the approved ordinances of the Municipality of Concepcion, Tarlac. We commit to **Transparency**, **Integrity**, and **Public Service**.</p>
        </section>

        <div class="search-bar">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search ordinance by title or number..."
                @input="debouncedSearch"
                aria-label="Search ordinances"
            />
            <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <section class="ordinance-list">
            <div v-if="isLoading" class="loading-state">
                <div class="spinner"></div>
                <p>Filtering ordinances...</p>
            </div>

            <div
                v-for="(ordinance, index) in filteredOrdinances"
                :key="index"
                class="ordinance-card"
                @click="showOrdinance(ordinance)"
                role="button"
                tabindex="0"
                @keyup.enter="showOrdinance(ordinance)"
            >
                <div class="ordinance-header-info">
                    <h3 class="ordinance-number">{{ ordinance.number }}</h3>
                    <span class="date"><i class="fas fa-calendar-alt"></i> Approved: {{ ordinance.date }}</span>
                </div>
                <h2 class="ordinance-title">{{ ordinance.title }}</h2>
                <p class="ordinance-description">{{ ordinance.description }}</p>
                <div class="read-more">View Details <i class="fas fa-arrow-right"></i></div>
            </div>

            <p v-if="!isLoading && filteredOrdinances.length === 0" class="no-results">
                No ordinances found matching "**{{ searchQuery }}**". Please try a different term.
            </p>
        </section>

        <transition name="modal">
            <div v-if="selectedOrdinance" class="modal-overlay" @click.self="closeModal" aria-modal="true" role="dialog" aria-labelledby="modal-title">
                <div class="modal-content" role="document" tabindex="-1">
                    <button class="close-btn" @click="closeModal" aria-label="Close Ordinance Details">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6 6L18 18" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <h2 class="modal-number" id="modal-title">{{ selectedOrdinance.number }}</h2>
                    <h3 class="modal-title-text">{{ selectedOrdinance.title }}</h3>
                    <p class="date">Date Approved: **{{ selectedOrdinance.date }}**</p>
                    <div class="detail-content-box">
                        <h4>Full Details:</h4>
                        <p class="details">{{ selectedOrdinance.details }}</p>
                    </div>
                    <a href="#" class="download-link" @click.prevent="">Download Full Text (PDF)</a>
                </div>
            </div>
        </transition>

        <footer>
            <p>© 2025 Municipality of Concepcion, Tarlac | All Rights Reserved</p>
        </footer>
    </div>
</template>

<script>
import Navbar from "@/components/Home/Navbar.vue";

// Simple debounce function for search
const debounce = (fn, delay) => {
    let timeoutID;
    return function (...args) {
        if (timeoutID) {
            clearTimeout(timeoutID);
        }
        timeoutID = setTimeout(() => {
            fn.apply(this, args);
        }, delay);
    };
};

export default {
    name: "OrdinancesPage",
    components: { Navbar },
    data() {
        return {
            searchQuery: "",
            selectedOrdinance: null,
            isLoading: false,
            ordinances: [
                {
                    number: "Ordinance No. 2025-001",
                    title: "An Ordinance Regulating the Use of Plastic Bags",
                    date: "January 15, 2025",
                    description:
                        "This ordinance promotes environmental protection by limiting the use of plastic bags within the municipality.",
                    details:
                        "This ordinance prohibits all commercial establishments from providing single-use plastic bags. Alternative eco-bags and paper bags are encouraged. Violators shall be fined ₱500 for the first offense, ₱1,000 for the second offense, and ₱2,500 or business permit revocation for subsequent offenses.",
                },
                {
                    number: "Ordinance No. 2024-015",
                    title: "An Ordinance Establishing Curfew Hours for Minors",
                    date: "October 10, 2024",
                    description:
                        "Imposes curfew hours for minors to ensure public safety and discipline.",
                    details:
                        "Curfew hours for minors (below 18 years old) are from 10:00 PM to 4:00 AM. Minors caught violating will be brought to the barangay hall, and their parents will be notified. Repeat violations may result in community service.",
                },
                {
                    number: "Ordinance No. 2023-009",
                    title: "An Ordinance Creating the Animal Welfare Council",
                    date: "July 20, 2023",
                    description:
                        "Establishes a local Animal Welfare Council to oversee protection and adoption of stray animals.",
                    details:
                        "The council will coordinate with local shelters such as Noah’s Ark to manage stray animals, promote adoption, and implement responsible pet ownership programs.",
                },
                {
                    number: "Ordinance No. 2022-004",
                    title: "An Ordinance on Proper Waste Segregation",
                    date: "March 12, 2022",
                    description:
                        "Mandates all households to segregate waste according to type for collection efficiency.",
                    details:
                        "Households are required to separate biodegradable, recyclable, and residual wastes. Failure to comply will result in warnings or fines as prescribed by the municipal environment office.",
                },
            ],
        };
    },
    computed: {
        filteredOrdinances() {
            if (this.isLoading) return [];

            const query = this.searchQuery.toLowerCase().trim();

            if (!query) {
                // If query is empty, return all ordinances sorted by date (newest first)
                return [...this.ordinances].sort((a, b) => new Date(b.date) - new Date(a.date));
            }

            return this.ordinances.filter((ord) => {
                return (
                    ord.title.toLowerCase().includes(query) ||
                    ord.number.toLowerCase().includes(query)
                );
            }).sort((a, b) => new Date(b.date) - new Date(a.date)); // Keep the sorting
        },
    },
    methods: {
        showOrdinance(ordinance) {
            this.selectedOrdinance = ordinance;
            // Focus on the modal for accessibility
            this.$nextTick(() => {
                const modal = document.querySelector('.modal-content');
                if (modal) modal.focus();
            });
        },
        closeModal() {
            this.selectedOrdinance = null;
        },
        // Debounce the search input to prevent excessive filtering on every keypress
        debouncedSearch: debounce(function() {
            this.isLoading = true;
            // Simulate a small delay for filtering feedback
            setTimeout(() => {
                this.isLoading = false;
            }, 300);
        }, 300),
    },
    // Add event listener to close modal on ESC key
    mounted() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.selectedOrdinance) {
                this.closeModal();
            }
        });
    },
    beforeUnmount() {
        document.removeEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.selectedOrdinance) {
                this.closeModal();
            }
        });
    }
};
</script>

<style>
/* --- Global Variables (Based on User's Request) --- */
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
    --card-shadow-style: 0 4px 10px rgba(0, 0, 0, 0.05); /* Lighter shadow for cards */
}

.ordinances-page {
    font-family: "Inter", "Poppins", sans-serif;
    background: var(--bg-page);
    color: var(--text-base);
    min-height: 100vh;
    text-align: center;
}

/* --- Header --- */
.ordinance-header {
    /* Uses the primary color with a slightly darker shade for depth */
    background: linear-gradient(135deg, var(--main-green), #1f6d23); 
    color: white;
    padding: 100px 20px 80px;
    border-radius: 0 0 50px 50px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    position: relative;
    overflow: hidden; 
}
.ordinance-header::before {
    content: '';
    position: absolute;
    bottom: -150px;
    left: 0;
    width: 100%;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50% / 100px 100px 0 0;
    transform: scaleX(1.5);
}
.header-title {
    font-size: 3rem;
    margin-bottom: 10px;
    font-weight: 900;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
    position: relative; 
}
.header-subtitle {
    font-size: 1.15rem;
    opacity: 0.95;
    font-weight: 400;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
}
.header-subtitle strong {
    font-weight: 700;
    color: var(--accent-gold);
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
}

/* --- Search (IMPROVED STYLES) --- */
.search-bar {
    margin: -40px auto 50px;
    max-width: 800px;
    padding: 0 20px;
    position: relative;
    z-index: 10;
}
.search-bar input {
    width: 100%;
    padding: 18px 30px 18px 60px; /* Adjust padding for icon placement */
    border: 2px solid #e0e0e0; 
    border-radius: 40px;
    font-size: 1.05rem;
    outline: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) inset; /* Lighter inset shadow */
    background-color: white;
    transition: all 0.3s;
}
.search-bar input:focus {
    border-color: var(--main-green);
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2); /* Clean focus ring */
}
.search-icon {
    position: absolute;
    left: 45px; /* Position icon inside */
    right: auto;
    top: 50%;
    transform: translateY(-50%);
    color: #9e9e9e; /* Muted gray icon */
    transition: color 0.3s;
    pointer-events: none;
}
.search-bar input:focus + .search-icon {
    color: var(--main-green); /* Change icon color on focus */
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
    background-color: #1b5e20; 
}

/* --- Footer --- */
footer {
    background-color: var(--primary-color);
    color: #f0f0f0;
    padding: 30px;
    margin-top: 60px;
    font-size: 0.9rem;
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
    .ordinance-header {
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
    .ordinance-list {
        grid-template-columns: 1fr;
        padding: 20px;
        gap: 25px;
    }
    .ordinance-card {
        padding: 20px;
    }
    .ordinance-title {
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