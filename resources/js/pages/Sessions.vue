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
            Legislative Sessions
          </h1>

          <p class="text-lg mt-3 text-gray-100">
            Records of official meetings and deliberations.
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
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="lg:col-span-2">
              <label class="text-sm font-semibold text-gray-700 mb-2 block">Search Filter</label>
              <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 transition" placeholder="Session No., Title, or Keyword..." />
            </div>

            <div>
              <label class="text-sm font-semibold text-gray-700 mb-2 block">Year</label>
              <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 appearance-none custom-select transition">
                <option value="">All Years</option>
              </select>
            </div>
            
             <div>
              <label class="text-sm font-semibold text-gray-700 mb-2 block">Type</label>
              <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-800 focus:border-green-800 appearance-none custom-select transition">
                <option value="">All Types</option>
              </select>
            </div>

            <button
              class="w-full h-11 bg-green-800 text-white font-bold rounded-lg shadow hover:bg-green-900 transition mt-4 sm:mt-0"
            >
              Apply Filters (Mock)
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-12">
          <div
            v-for="session in mockSessions.data"
            :key="session.id"
            class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300"
          >
            <div class="bg-green-800 text-white px-5 py-2 font-semibold text-sm">
              Session No. {{ session.session_number }}
            </div>

            <div class="p-5 space-y-4">
              <h2 class="text-lg font-bold text-gray-900 line-clamp-2">
                {{ session.session_title }}
              </h2>

              <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium pt-2 border-t border-gray-100">
                <span class="text-green-800 flex items-center gap-1">
                  <span class="text-sm">🗓️</span>
                  Date: {{ formatDate(session.date_of_session) }}
                </span>
                
                <span class="text-green-800 flex items-center gap-1">
                  <span class="text-sm">📌</span>
                  Type: {{ session.session_type }}
                </span>
              </div>

              <div class="flex justify-start items-center gap-3 pt-3">
                <a
                  :href="`/session-details/${session.id}`" 
                  class="px-6 py-2 text-sm text-white font-bold bg-green-800 rounded-lg hover:bg-green-700 transition shadow-md"
                >
                  View Session Details
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-16 flex justify-center">
          <nav class="flex gap-2">
            <span class="h-10 w-auto px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 cursor-default bg-white text-gray-400 flex items-center justify-center">← Prev</span>
            <a href="#" class="h-10 w-10 px-4 py-2 rounded-lg text-sm font-semibold border transition-colors duration-200 flex items-center justify-center bg-green-800 text-white border-green-800 shadow-md">1</a>
            <a href="#" class="h-10 w-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 flex items-center justify-center">2</a>
            <a href="#" class="h-10 w-10 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-700 hover:bg-yellow-400 hover:text-green-900 flex items-center justify-center">3</a>
            <span class="h-10 w-auto px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 cursor-default bg-white text-gray-400 flex items-center justify-center">Next →</span>
          </nav>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
// Assuming you have these components in your project structure
import Navbar from "@/components/Home/Navbar.vue"; 
import Footer from "@/components/Home/Footer.vue";

// --- MOCK DATA FOR DEMONSTRATION ---
const mockImageBaseUrl = 'https://picsum.photos/seed/';
const mockSessions = reactive({
  data: [
    {
      id: 1,
      session_number: '001',
      session_title: 'Discussion and Approval of the 2025 Annual Budget and Revenue Code Amendments',
      date_of_session: '2024-11-15',
      session_type: 'Regular',
      summary: "The Sangguniang Bayan held its regular session focusing on the approval of the 2025 Annual Budget and the discussion of Ordinance No. 2024-005 regarding local traffic regulations. The session was attended by all council members and included a public consultation segment. Key resolutions were passed concerning local infrastructure projects.",
      images: [
        { url: mockImageBaseUrl + 'mock1/800/600', alt: 'Council Members voting' },
        { url: mockImageBaseUrl + 'mock2/800/600', alt: 'Presiding Officer giving remarks' },
        { url: mockImageBaseUrl + 'mock3/800/600', alt: 'Public consultation attendees' },
        { url: mockImageBaseUrl + 'mock4/800/600', alt: 'Council Hall overview' },
        { url: mockImageBaseUrl + 'mock5/800/600', alt: 'Signing of Resolution' },
        { url: mockImageBaseUrl + 'mock6/800/600', alt: 'Council in attendance' },
        { url: mockImageBaseUrl + 'mock7/800/600', alt: 'Discussion group' },
        { url: mockImageBaseUrl + 'mock8/800/600', alt: 'Project briefing' },
        { url: mockImageBaseUrl + 'mock9/800/600', alt: 'Council Members at work' },
        { url: mockImageBaseUrl + 'mock10/800/600', alt: 'Council exterior view' },
      ],
    },
    { id: 2, session_number: '005-A', session_title: 'Special Session on Disaster Risk Reduction and Management Plan', date_of_session: '2024-10-28', session_type: 'Special', summary: 'Emergency session called to finalize the new municipal disaster response protocols before the typhoon season.', images: [] },
    { id: 3, session_number: '010', session_title: 'Regular Session: Barangay Clearance System Review', date_of_session: '2024-09-01', session_type: 'Regular', summary: 'Review of the efficiency of the new Barangay Clearance issuance system, with recommendations for digital integration.', images: [] },
    // Add more mock data entries for a full display
  ],
});
// --- END MOCK DATA ---


// ==========================
//      MODAL LOGIC (Mostly stubbed out now, as we use links)
// ==========================
// These refs are kept but will always be null since the links are now used instead of the modal.
const selectedSession = ref(null);
const fullImage = ref(null); 

// Helper functions for modal logic are now irrelevant/unused
const openModal = (session) => {
    // This is no longer called when using the <a> tag
    console.log("Navigation to Session Details page simulated for ID:", session.id);
};

const closeModal = () => {
  selectedSession.value = null;
};

const viewFullImage = (image) => {
    fullImage.value = {
        src: image.url.replace('/600', '/1200'), 
        alt: image.alt,
    };
}


// Date format
const formatDate = (dateString) => {
  if (!dateString) return "N/A";
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};
</script>

<style scoped>
/* Modal styles are now irrelevant but kept for completeness */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Custom select styling for the dropdown arrow */
.custom-select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none; 
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%234B5563%22%20d%3D%22M287%20197.8%20146.2%2057%205.4%20197.8z%22%2F%3E%3C%2Fsvg%3E');
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 0.65em auto;
}
</style>