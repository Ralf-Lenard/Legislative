<template>
    <div class="bg-gray-50 min-h-screen">
      <Navbar />
  
      <!-- HEADER -->
      <section class="pt-28 pb-10 bg-gradient-to-br from-green-900 to-green-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
          <div class="flex items-center space-x-2 text-sm mb-2">
            <a href="/" class="text-gray-300 hover:text-white transition">Home</a>
            <span class="text-gray-300">/</span>
            <a href="/legislative-sessions" class="text-gray-300 hover:text-white transition">
              Legislative Sessions
            </a>
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
  
      <!-- CONTENT -->
      <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-200">
  
          <!-- META -->
          <div class="flex flex-wrap gap-x-8 gap-y-4 text-sm font-medium border-b pb-4 mb-6">
            <span class="text-green-800 flex items-center gap-2">
              🗓️ Date:
              <strong>{{ formatDate(session.date_of_session) }}</strong>
            </span>
  
            <span class="text-green-800 flex items-center gap-2">
              📌 Type:
              <strong>{{ session.session_type }}</strong>
            </span>
          </div>
  
          <!-- SUMMARY -->
          <div class="mb-8">
            <h2 class="font-bold text-xl mb-3 text-green-900 border-b pb-1">
              Agenda / Summary of Discussion
            </h2>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
              {{ session.summary }}
            </p>
          </div>
  
          <!-- GALLERY -->
          <div class="mt-10 pt-6 border-t border-gray-200">
            <h2 class="font-bold text-xl mb-6 text-green-900">
              Session Photo Gallery ({{ session.images.length }})
            </h2>
  
            <div
              v-if="session.images.length"
              class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4"
            >
              <div
                v-for="(image, index) in session.images"
                :key="index"
                class="relative aspect-video rounded-lg overflow-hidden shadow-md cursor-pointer group"
                @click="viewFullImage(image)"
              >
                <img
                  :src="image.url"
                  :alt="image.alt ?? 'Session Image'"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                />
  
                <div
                  class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center"
                >
                  <span class="text-white text-3xl">🔍</span>
                </div>
              </div>
            </div>
  
            <div
              v-else
              class="text-gray-500 italic p-4 bg-gray-50 rounded-lg"
            >
              No photos uploaded for this session.
            </div>
          </div>
        </div>
  
        <!-- BACK -->
        <div class="max-w-7xl mx-auto mt-8 flex justify-end">
          <a
            href="/legislative-sessions"
            class="flex items-center text-sm font-semibold text-gray-700 hover:text-green-800 transition"
          >
            ← Back to All Sessions
          </a>
        </div>
      </section>
  
      <!-- IMAGE MODAL -->
      <transition name="modal">
        <div
          v-if="fullImage"
          class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm z-50 p-4"
          @click.self="fullImage = null"
        >
          <div class="relative max-w-6xl max-h-screen">
            <img
              :src="fullImage.src"
              :alt="fullImage.alt"
              class="w-full h-full object-contain rounded-lg"
            />
  
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
  import { computed, ref } from "vue";
  import { usePage } from "@inertiajs/vue3";
  
  import Navbar from "@/components/Home/Navbar.vue";
  import Footer from "@/components/Home/Footer.vue";
  
  // Inertia props
  const page = usePage();
  const session = computed(() => page.props.session);
  
  // Fullscreen image viewer
  const fullImage = ref(null);
  
  const viewFullImage = (image) => {
    fullImage.value = {
      src: image.url,
      alt: image.alt ?? "Session Image",
    };
  };
  
  // Date formatter
  const formatDate = (date) => {
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  };
  </script>
  
  <style scoped>
  .modal-enter-active,
  .modal-leave-active {
    transition: opacity 0.25s ease;
  }
  
  .modal-enter-from,
  .modal-leave-to {
    opacity: 0;
  }
  </style>
  