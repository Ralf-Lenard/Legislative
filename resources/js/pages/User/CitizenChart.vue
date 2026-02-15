<template>
    <Head title="Citizen's Charter" />
    <div class="bg-white min-h-screen">
      <Navbar />
  
      <section class="pt-24 pb-16 md:pt-32 md:pb-24 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-yellow-300/10 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 flex flex-col lg:flex-row items-center gap-8">
          <div class="flex-1 text-center lg:text-left text-white relative z-10">
            <span class="text-[10px] md:text-xs tracking-widest uppercase bg-yellow-400 text-green-900 px-4 py-1 rounded-full font-bold inline-block">
              Public Service & Transparency
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold mt-4 leading-tight">
              Citizen's Charter
            </h1>
            <p class="text-base md:text-lg mt-3 text-gray-100 max-w-2xl mx-auto lg:mx-0">
              Your guide to the services, legislative records, and public assistance programs of the Sangguniang Bayan.
            </p>
          </div>
        </div>
      </section>
  
      <nav class="sticky top-16 md:top-20 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
          <div class="flex overflow-x-auto no-scrollbar py-4 gap-6 md:justify-center">
            <a href="#charter" class="flex-shrink-0 text-sm font-black text-green-900 uppercase hover:text-yellow-600 transition-colors">Service Charter</a>
            <a href="/citizens-charter/ordinances" class="flex-shrink-0 text-sm font-black text-green-900 uppercase hover:text-yellow-600 transition-colors">Ordinances</a>
            <a href="/citizens-charter/resolutions" class="flex-shrink-0 text-sm font-black text-green-900 uppercase hover:text-yellow-600 transition-colors">Resolutions</a>
            <a href="/citizens-charter/assistance" class="flex-shrink-0 text-sm font-black text-green-900 uppercase hover:text-yellow-600 transition-colors">Public Assistance</a>
          </div>
        </div>
      </nav>
  
      <section id="charter" class="py-16 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-12">
              <h2 class="text-3xl font-black text-green-900 uppercase">Service Standards</h2>
              <div class="w-20 h-1.5 bg-yellow-400 mx-auto mt-3 rounded-full"></div>
              <p class="mt-4 text-gray-600">Step-by-step guide to accessing municipal legislative services.</p>
          </div>
  
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="service in services" :key="service.title" class="bg-white p-8 rounded-3xl shadow-lg border-b-8 border-green-800 transition-transform hover:-translate-y-2">
              <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                  <component :is="service.icon" class="w-6 h-6 text-green-800" />
              </div>
              <h4 class="text-xl font-bold text-green-900 mb-2">{{ service.title }}</h4>
              <p class="text-sm text-gray-500 mb-4">{{ service.description }}</p>
              <button class="text-xs font-black text-green-800 uppercase tracking-widest border-b-2 border-yellow-400 pb-1">View Procedure</button>
            </div>
          </div>
        </div>
      </section>
  
      <section id="ordinances" class="py-16 px-4 bg-white">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12">
          <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="w-2 h-10 bg-green-800 rounded-full"></div>
                <h2 class="text-2xl font-black text-green-900 uppercase">Latest Ordinances</h2>
            </div>
            <div class="space-y-4">
              <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-green-400 transition-all cursor-pointer">
                <span class="text-[10px] font-bold text-yellow-600 uppercase">Series of 2024</span>
                <h4 class="font-bold text-green-900 mt-1">Market Operations & Regulation Code</h4>
              </div>
            </div>
            <button class="mt-6 w-full py-3 bg-green-900 text-white font-bold rounded-xl">View All Ordinances</button>
          </div>
  
          <div id="resolutions">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-2 h-10 bg-yellow-400 rounded-full"></div>
                <h2 class="text-2xl font-black text-green-900 uppercase">Recent Resolutions</h2>
            </div>
            <div class="space-y-4">
              <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-green-400 transition-all cursor-pointer">
                <span class="text-[10px] font-bold text-yellow-600 uppercase">Res. No. 102-2024</span>
                <h4 class="font-bold text-green-900 mt-1">Resolution Commending Local Youth Leaders</h4>
              </div>
            </div>
            <button class="mt-6 w-full py-3 bg-green-900 text-white font-bold rounded-xl">View All Resolutions</button>
          </div>
        </div>
      </section>
  
      <section id="assistance" class="py-20 px-4 bg-gray-50 border-t">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <h2 class="text-3xl font-black text-green-900 uppercase">Public Assistance Programs</h2>
            <div class="w-20 h-1.5 bg-yellow-400 mx-auto mt-3 rounded-full"></div>
          </div>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
              <div class="bg-green-800 p-6 text-white">
                  <h3 class="text-xl font-bold uppercase tracking-tight">Educational Assistance</h3>
                  <p class="text-green-100 text-xs mt-1">List of Active Municipal Scholars</p>
              </div>
              <div class="p-6">
                 <div class="overflow-hidden rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                      <thead class="bg-gray-50 text-green-900 font-bold uppercase text-[10px]">
                          <tr>
                              <th class="p-4">Name</th>
                              <th class="p-4">Institution</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100">
                          <tr v-for="i in 5" :key="i" class="hover:bg-green-50 transition-colors">
                              <td class="p-4 font-medium text-gray-700">Sample Student Name {{ i }}</td>
                              <td class="p-4 text-gray-500">Concepcion State College</td>
                          </tr>
                      </tbody>
                    </table>
                 </div>
                 <button class="w-full mt-6 py-3 border-2 border-green-800 text-green-800 font-black rounded-xl hover:bg-green-800 hover:text-white transition-all">Download Full List</button>
              </div>
            </div>
  
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
              <div class="bg-yellow-400 p-6 text-green-900">
                  <h3 class="text-xl font-bold uppercase tracking-tight">People's Day Catered Assistance</h3>
                  <p class="text-green-800/70 text-xs mt-1">Weekly Assistance Tracking</p>
              </div>
              <div class="p-6">
                  <div class="space-y-4">
                      <div v-for="item in assistanceItems" :key="item.category" class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                          <div class="flex items-center gap-4">
                              <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm">
                                  <component :is="item.icon" class="w-5 h-5 text-green-800" />
                              </div>
                              <span class="font-bold text-green-900">{{ item.category }}</span>
                          </div>
                          <span class="text-xs font-black bg-yellow-200 text-green-900 px-3 py-1 rounded-full">{{ item.count }} Beneficiaries</span>
                      </div>
                  </div>
                  <button class="w-full mt-8 py-3 bg-green-900 text-white font-black rounded-xl shadow-lg shadow-green-950/20">Check Schedule</button>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <Footer />
    </div>
  </template>
 <script setup>
 import { Head } from '@inertiajs/vue3';
 import Navbar from "@/components/Home/Navbar.vue";
 import Footer from "@/components/Home/Footer.vue";
 
 // Corrected Imports for Heroicons Vue 3
 import { 
     BookOpenIcon, 
     DocumentTextIcon, 
     UserGroupIcon, 
     HeartIcon, 
     AcademicCapIcon,
     ScaleIcon 
 } from '@heroicons/vue/24/outline'; 
 
 const services = [
     { 
         title: 'Securing Certified Copies', 
         description: 'Request process for certified copies of Ordinances or Resolutions.', 
         icon: DocumentTextIcon 
     },
     { 
         title: 'Legislative Research', 
         description: 'Accessing archived legislative records for research purposes.', 
         icon: BookOpenIcon 
     },
     { 
         title: 'Public Hearing Participation', 
         description: 'Guidelines for attending and speaking at committee hearings.', 
         icon: UserGroupIcon 
     },
 ];
 
 const assistanceItems = [
     { category: 'Medical Assistance', count: '142', icon: HeartIcon },
     { category: 'Educational Support', count: '85', icon: AcademicCapIcon },
     { category: 'Legal Consultation', count: '12', icon: ScaleIcon },
 ];
 </script>
  
  <style scoped>
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  
  /* Smooth scroll for the navigation */
  html { scroll-behavior: smooth; }
  
  /* Ensure section padding accounts for sticky nav */
  section { scroll-margin-top: 100px; }
  </style>