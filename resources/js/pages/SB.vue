<template>
  <Head title="Organizational Chart" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <!-- Hero Section -->
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
            Leadership
          </span>

          <h1 class="text-5xl md:text-6xl font-extrabold mt-4 leading-tight">
            The Council Members
          </h1>

          <p class="text-lg mt-3 text-gray-100">
            Meet the elected officials of the Sangguniang Bayan (Municipal Council), dedicated to effective legislation and public service.
          </p>
        </div>

        <!-- RIGHT IMAGE -->
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

    <!-- Presiding Officer Section -->
    <section class="py-16 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-green-900 text-center mb-10">
          Presiding Officer
        </h2>

        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-2xl border-4 border-yellow-400 overflow-hidden">
          <div class="flex flex-col md:flex-row items-center p-8 gap-8">
            <div class="w-65 h-96 flex-shrink-0 rounded-2xl overflow-hidden border-4 border-green-800 shadow-lg">
              <img 
                :src="imageUrl(presidingOfficer?.image)" 
                :alt="presidingOfficer?.name || 'No Presiding Officer'" 
                class="w-full h-full object-cover"
              >
            </div>
            <div class="text-center md:text-left">
              <p class="text-lg font-semibold text-gray-500 uppercase tracking-wider">
                {{ presidingOfficer?.position || 'Vice Mayor' }}
              </p>
              <h3 class="text-3xl font-extrabold text-green-900 mt-1">
                {{ presidingOfficer?.name || 'Not Appointed' }}
              </h3>
              <p class="text-lg mt-3 text-gray-700 italic">
                "Leading the Sangguniang Bayan toward responsive and effective governance."
              </p>
              <button 
                v-if="presidingOfficer"
                @click="openBio(presidingOfficer)" 
                class="inline-block mt-4 px-5 py-2 text-sm bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-yellow-500 transition"
              >
                View Bio
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Council Members Section -->
    <section class="py-16 px-4 bg-white">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-green-900 text-center mb-12">
          Municipal Councilors ({{ councilMembers.length }} Members)
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-8">
          <div 
            v-for="member in councilMembers" 
            :key="member.id"
            class="bg-gray-50 rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition duration-300"
          >
            <div class="h-72 bg-gray-200 overflow-hidden relative">
              <img 
                :src="imageUrl(member.image)" 
                :alt="member.name" 
                class="w-full h-full object-cover grayscale hover:grayscale-0 transition duration-500"
              >
              <div class="absolute inset-0 bg-green-900/10"></div>
            </div>
            <div class="p-4 text-center">
              <h3 class="text-lg font-bold text-green-900 leading-tight">
                {{ member.name }}
              </h3>
              <p class="text-sm text-gray-600 mt-1 line-clamp-2 h-10">
                {{ member.main_committee }}
              </p>
              <button 
                @click="openBio(member)" 
                class="inline-block mt-3 text-xs font-semibold text-yellow-700 hover:text-yellow-500 transition cursor-pointer"
              >
                View Bio →
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Section -->
    <transition name="modal-fade">
      <div
        v-if="showBioModal"
        class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-[100]"
        @click.self="closeBio"
      >
        <div
          class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl border-t-8 border-green-800 relative flex flex-col overflow-hidden"
          style="max-height: 90vh;"
        >
          <button
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition z-10"
            @click="closeBio"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>

          <div class="overflow-y-auto custom-scrollbar p-8 sm:p-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div class="md:col-span-1 flex flex-col items-center">
                <div class="w-64 h-96 rounded-xl overflow-hidden border-8 border-yellow-400 shadow-xl mb-4">
                  <img :src="imageUrl(modalMemberData.image)" :alt="modalMemberData.name" class="w-full h-full object-cover"/>
                </div>

                <h2 class="text-2xl font-extrabold text-green-900 text-center">
                  {{ modalMemberData.name }}
                </h2>
                <p class="text-lg font-semibold text-gray-600 mt-1 mb-4 text-center">
                  {{ modalMemberData.position }}
                </p>

                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold border border-green-300">
                    Active Term (2022-2025)
                </span>
              </div>

              <div class="md:col-span-2 space-y-8">
                <div>
                  <h3 class="text-xl font-bold text-green-800 border-b-2 border-yellow-400 pb-2 mb-4">Biography & Platform</h3>
                  <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ modalMemberData.bio || 'No detailed biography provided yet.' }}
                  </p>
                </div>

                <div>
                  <h3 class="text-xl font-bold text-green-800 border-b-2 border-yellow-400 pb-2 mb-4">Committee Assignments</h3>
                  <div v-if="modalMemberData.committees && modalMemberData.committees.length" class="space-y-4">
                    <div 
                        v-for="committee in modalMemberData.committees" 
                        :key="committee.name" 
                        class="bg-gray-100 p-4 rounded-lg border-l-4"
                        :class="committee.role === 'Chairperson' ? 'border-yellow-500' : 'border-green-500'"
                    >
                      <p class="text-sm font-bold" :class="committee.role === 'Chairperson' ? 'text-yellow-800' : 'text-green-800'">
                        {{ committee.role }}
                      </p>
                      <h4 class="text-lg font-extrabold text-gray-900 mt-0.5">
                        {{ committee.name }}
                      </h4>
                      <p class="text-xs text-gray-600 mt-1">
                        Focus: {{ committee.focus }}
                      </p>
                    </div>
                  </div>
                  <p v-else class="text-gray-500 italic text-sm">No specific committee assignments listed.</p>
                </div>

              </div>
            </div>
          </div>

          <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end">
              <button 
                  @click="closeBio"
                  class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition"
              >
                  Close Profile
              </button>
          </div>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>

<script setup>
import { ref } from "vue";
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { Head } from '@inertiajs/vue3';

// Props from Laravel Inertia
const props = defineProps({
  presidingOfficer: {
    type: Object,
    default: null,
  },
  councilMembers: {
    type: Array,
    default: () => [],
  },
});

// Destructure for easier access
const presidingOfficer = props.presidingOfficer;
const councilMembers = props.councilMembers;

// Image helper
const imageUrl = (path) => {
  return path ? `/storage/${path}` : '/images/placeholder-male.jpg';
};

// Modal logic
const showBioModal = ref(false);
const modalMemberData = ref({});

const openBio = (member) => {
  modalMemberData.value = {
    ...member,
    committees: member.committees?.map((c) => ({
      name: c.name,
      focus: c.focus,
      role: c.pivot?.role,
    })) || [],
  };
  showBioModal.value = true;
};

const closeBio = () => {
  showBioModal.value = false;
  setTimeout(() => modalMemberData.value = {}, 300);
};
</script>

<style scoped>
/* Modal Transition Styles */
.modal-fade-enter-active,
.modal-fade-leave-active { transition: opacity 0.3s ease; }
.modal-fade-enter-from,
.modal-fade-leave-to { opacity: 0; }

/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 8px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #a5a5a5; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #737373; }
</style>
