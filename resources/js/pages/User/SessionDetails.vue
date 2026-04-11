<template>
  <div class="bg-gray-50 min-h-screen">
    <Navbar />

    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden text-white">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>
      <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex items-center space-x-2 text-sm mb-6">
          <a href="/" class="text-gray-300 hover:text-white transition">Home</a>
          <span class="text-gray-300">/</span>
          <a href="/legislative-sessions" class="text-gray-300 hover:text-white transition">Legislative Sessions</a>
          <span class="text-gray-300">/</span>
          <span class="font-semibold text-yellow-400">Session Details</span>
        </div>
        <span class="inline-block text-[10px] md:text-xs tracking-[0.3em] uppercase bg-yellow-400 text-green-950 px-6 py-2 rounded-full font-black mb-8">
          Official Session Record
        </span>
        <h1 class="text-4xl md:text-6xl font-black leading-none tracking-tight mb-4 uppercase">
          Session No. <span class="text-yellow-400 italic font-serif">{{ session.session_number }}</span>
        </h1>
        <p class="text-lg md:text-xl text-green-50/80 font-medium max-w-3xl">
          {{ session.session_title }}
        </p>
      </div>
    </section>

    <section class="py-12 px-4">
      <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        <div class="p-8">
          <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-medium border-b pb-4 mb-6">
            <span class="text-green-800 flex items-center gap-2">🗓️ Date: <strong>{{ formatDate(session.date_of_session) }}</strong></span>
            <span class="text-green-800 flex items-center gap-2">📌 Type: <strong>{{ session.session_type }}</strong></span>
          </div>

          <div class="mb-8">
            <h2 class="font-bold text-xl mb-3 text-green-900 border-b pb-1">Agenda / Summary of Discussion</h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ session.summary }}</p>
          </div>
        </div>

        <div v-if="combinedMedia.length > 0" class="border-t border-gray-100 bg-gray-50 p-1">
          <div :class="['grid gap-1', gridLayoutClass]">
            <div 
              v-for="(media, index) in visibleMedia" 
              :key="index"
              class="relative group cursor-pointer overflow-hidden bg-slate-200"
              :class="{'h-[500px]': combinedMedia.length === 1, 'h-[300px] md:h-[350px]': combinedMedia.length > 1}"
              @click="openLightbox(index)"
            >
              <img 
                v-if="media.type === 'image'" 
                :src="media.url" 
                :alt="media.description"
                class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
              />
              
              <div v-else class="w-full h-full relative">
                <video :src="media.url" class="w-full h-full object-cover"></video>
                <div class="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/30 transition-colors">
                  <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/40 shadow-xl">
                    <Play class="text-white fill-current w-6 h-6 ml-1" />
                  </div>
                </div>
              </div>

              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-4 translate-y-1 group-hover:translate-y-0 transition-transform duration-300">
                <p class="text-white text-xs md:text-sm font-medium line-clamp-2 drop-shadow-lg">
                   {{ media.description }}
                </p>
              </div>

              <div v-if="index === 3 && combinedMedia.length > 4" class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex flex-col items-center justify-center">
                <span class="text-white text-4xl font-black">+{{ combinedMedia.length - 4 }}</span>
                <span class="text-white/80 text-[10px] uppercase tracking-widest font-bold mt-2">More Media</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="max-w-5xl mx-auto mt-8 flex justify-end">
        <a href="/legislative-sessions" class="flex items-center text-sm font-semibold text-gray-500 hover:text-green-800 transition group">
          <ArrowLeft class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" />
          Back to All Sessions
        </a>
      </div>
    </section>

    <transition name="modal">
      <div v-if="activeMedia" class="fixed inset-0 flex flex-col items-center justify-center bg-black/98 backdrop-blur-xl z-50 p-4" @click.self="activeMedia = null">
        
        <div class="absolute top-0 left-0 right-0 p-6 flex justify-between items-center z-[60] bg-gradient-to-b from-black/70 to-transparent">
          <div class="text-white">
            <p class="text-xs uppercase tracking-[0.2em] text-yellow-400 font-bold mb-1">{{ activeMedia.type }} {{ currentIndex + 1 }} of {{ combinedMedia.length }}</p>
            <h3 class="text-lg font-semibold">{{ activeMedia.description }}</h3>
          </div>
          <button class="text-white/70 hover:text-white transition-all duration-300" @click="activeMedia = null">
            <X class="w-8 h-8" />
          </button>
        </div>
        
        <div class="relative w-full h-full flex items-center justify-center">
          <img v-if="activeMedia.type === 'image'" :src="activeMedia.url" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl object-contain" />
          
          <video v-else :src="activeMedia.url" controls autoplay class="max-w-full max-h-[80vh] shadow-2xl rounded-lg"></video>

          <button v-if="currentIndex > 0" @click="prevMedia" class="absolute left-0 md:left-4 p-4 text-white/50 hover:text-white transition transform hover:scale-125">
            <ChevronLeft class="w-12 h-12" />
          </button>
          <button v-if="currentIndex < combinedMedia.length - 1" @click="nextMedia" class="absolute right-0 md:right-4 p-4 text-white/50 hover:text-white transition transform hover:scale-125">
            <ChevronRight class="w-12 h-12" />
          </button>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { X, Play, ChevronLeft, ChevronRight, ArrowLeft } from "lucide-vue-next";
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";

const page = usePage();
const session = computed(() => page.props.session);

// Unified Media Logic - Fixed the URL mapping to match your Controller
const combinedMedia = computed(() => {
  // Use the 'url' directly provided by your controller asset() function
  const images = (session.value.images || []).map(img => ({ 
    type: 'image',
    url: img.url,
    description: img.alt || 'Session Image'
  }));
  
  const videos = (session.value.videos || []).map(vid => ({ 
    type: 'video', 
    url: vid.url, 
    description: vid.title || 'Session Video'
  }));

  return [...images, ...videos];
});

const visibleMedia = computed(() => combinedMedia.value.slice(0, 4));

const gridLayoutClass = computed(() => {
  const count = combinedMedia.value.length;
  if (count === 1) return 'grid-cols-1';
  if (count === 2) return 'grid-cols-2';
  if (count === 3) return 'grid-cols-2 grid-rows-2 [&>*:first-child]:row-span-2 [&>*:first-child]:h-full';
  return 'grid-cols-2 grid-rows-2';
});

const activeMedia = ref(null);
const currentIndex = ref(0);

const openLightbox = (index) => {
  currentIndex.value = index;
  activeMedia.value = combinedMedia.value[index];
};

const nextMedia = () => {
  if (currentIndex.value < combinedMedia.value.length - 1) openLightbox(currentIndex.value + 1);
};

const prevMedia = () => {
  if (currentIndex.value > 0) openLightbox(currentIndex.value - 1);
};

const formatDate = (date) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric", month: "long", day: "numeric",
  });
};
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.4s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>