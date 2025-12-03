<template>
  <div class="bg-gray-50 min-h-screen">
    <Navbar />

    <section class="pt-28 pb-10 bg-gradient-to-br from-green-900 to-green-700 text-white">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center space-x-2 text-sm mb-2">
            <a href="#" class="text-gray-300 hover:text-white transition">Home</a>
            <span class="text-gray-300">/</span>
            <a href="/sessions" class="text-gray-300 hover:text-white transition">Legislative Sessions</a>
            <span class="text-gray-300">/</span>
            <span class="font-semibold text-yellow-400">Details</span>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-extrabold mt-2 leading-tight">
          Session No. {{ session.session_number }}
        </h1>
        <p class="text-lg mt-1 text-gray-100 font-medium">
          {{ session.session_title }}
        </p>
      </div>
    </section>

    <section class="py-12 px-4">
      <div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">

        <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-medium border-b pb-4 mb-6">
          <span class="text-green-800 flex items-center gap-2">
            <span class="text-lg">🗓️</span>
            Date: <strong>{{ formatDate(session.date_of_session) }}</strong>
          </span>
          
          <span class="text-green-800 flex items-center gap-2">
            <span class="text-lg">📌</span>
            Type: <strong>{{ session.session_type }}</strong>
          </span>
        </div>

        <div class="mb-8">
          <h2 class="font-bold text-xl mb-3 text-green-900 border-b pb-1">
            Agenda/Summary of Discussion:
          </h2>
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">
            {{ session.summary }}
          </p>
        </div>
        
        <div class="mt-10 pt-6 border-t border-gray-200">
          <h2 class="font-bold text-xl mb-6 text-green-900">
            Session Photo Gallery ({{ session.images.length }})
          </h2>
          
          <div v-if="session.images.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <div 
              v-for="(image, index) in session.images" 
              :key="index"
              class="relative aspect-video rounded-lg overflow-hidden shadow-md cursor-pointer group"
              @click="viewFullImage(image)"
            >
              <img
                :src="image.url" 
                :alt="image.alt" 
                class="w-full h-full object-cover transform transition duration-300 group-hover:scale-105"
              />
              <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                <span class="text-white text-3xl">🔍</span>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500 italic p-4 bg-gray-50 rounded-lg">
            No photos uploaded for this session.
          </div>
        </div>

      </div>

      <div class="max-w-7xl mx-auto mt-8 flex justify-end">
         <a href="/sessions" class="flex items-center text-sm font-semibold text-gray-700 hover:text-green-800 transition">
             ← Back to All Sessions
         </a>
      </div>
    </section>

    <transition name="modal">
        <div
            v-if="fullImage"
            class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm z-50 p-4"
            @click.self="fullImage = null"
        >
            <div class="max-w-screen-lg max-h-screen-lg">
                <img :src="fullImage.src" :alt="fullImage.alt" class="w-full h-full object-contain">
                <button
                    class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300"
                    @click="fullImage = null"
                >
                    ✕
                </button>
            </div>
        </div>
    </transition>


    <Footer />
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from "vue";
// Assuming you have these components in your project structure
import Navbar from "@/components/Home/Navbar.vue"; 
import Footer from "@/components/Home/Footer.vue";

// --- MOCK DATA FOR DEMONSTRATION ---
const mockImageBaseUrl = 'https://picsum.photos/seed/';

// We define the session data here, pretending it was fetched based on an ID
const session = reactive({
  id: 1,
  session_number: '001',
  session_title: 'Discussion and Approval of the 2025 Annual Budget and Revenue Code Amendments',
  date_of_session: '2024-11-15',
  session_type: 'Regular',
  summary: "The Sangguniang Bayan held its regular session focusing on the approval of the 2025 Annual Budget and the discussion of Ordinance No. 2024-005 regarding local traffic regulations.\n\nThe session was attended by all council members and included a public consultation segment. Key resolutions were passed concerning local infrastructure projects, including the new drainage system phase 3 and the procurement of new sanitation vehicles.\n\nThe Presiding Officer emphasized the need for prompt action on the budget to ensure uninterrupted service delivery for the next fiscal year.",
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
    { url: mockImageBaseUrl + 'mock11/800/600', alt: 'Council exterior view' },
    { url: mockImageBaseUrl + 'mock12/800/600', alt: 'Council exterior view' },
    { url: mockImageBaseUrl + 'mock13/800/600', alt: 'Council exterior view' },
  ],
});
// --- END MOCK DATA ---


// ==========================
//      IMAGE VIEWER LOGIC
// ==========================
const fullImage = ref(null); // For the full-screen image viewer

const viewFullImage = (image) => {
    fullImage.value = {
        src: image.url.replace('/600', '/1200'), // Use a higher resolution for full view
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
/* Smooth modal fade animation */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>