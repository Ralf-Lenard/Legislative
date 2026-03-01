<template>
  <Head title="Public Assistance & Records" />
  <div class="bg-slate-50 min-h-screen selection:bg-yellow-200 overflow-x-hidden">
    <Navbar />

    <!-- <section class="relative pt-28 pb-16 md:pt-48 md:pb-32 bg-[#064e3b] overflow-hidden text-white">
      <div class="absolute top-0 right-0 w-64 h-64 md:w-96 md:h-96 bg-yellow-400/10 rounded-full blur-[80px] md:blur-[120px]"></div>
      <div class="max-w-7xl mx-auto px-4 md:px-6 relative z-10 text-center md:text-left">
        <h1 class="text-3xl sm:text-4xl md:text-7xl font-black mb-4 md:mb-6 leading-none uppercase tracking-tighter">
          Public Assistance <br class="hidden md:block"/><span class="text-yellow-400 italic font-serif text-2xl sm:text-4xl md:text-6xl">Registry & Records</span>
        </h1>
        <p class="text-sm md:text-lg text-green-50/80 max-w-2xl mx-auto md:mx-0 leading-relaxed font-medium">
          Real-time transparency for municipal assistance programs. Browse verified beneficiaries for the current quarter.
        </p>
      </div>
    </section> -->

     <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">
          
          <Link 
            href="/citizens-charter" 
            class="relative z-20 inline-flex items-center text-yellow-400 hover:text-white mb-8 transition-colors font-bold text-xs uppercase tracking-[0.2em] cursor-pointer"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-5 w-5 mr-2" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Citizen Charter
          </Link>

          <div class="block">
            <span class="text-xs tracking-widest uppercase bg-yellow-400 text-green-900 px-4 py-1 rounded-full font-bold">
              Official Website
            </span>
          </div>

           <h1 class="text-3xl sm:text-4xl md:text-7xl font-black mb-4 md:mb-6 leading-none uppercase tracking-tighter">
          Public Assistance <br class="hidden md:block"/><span class="text-yellow-400 italic font-serif text-2xl sm:text-4xl md:text-6xl">Registry & Records</span>
            </h1>

          <p class="text-lg mt-3 text-gray-100">
             Real-time transparency for municipal assistance programs. Browse verified beneficiaries for the current quarter.
          </p>
        </div>
        
        </div>
    </section>

    <section class="py-8 md:py-16 px-4 md:px-6">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12">
        
        <div class="lg:col-span-4 space-y-6 order-2 lg:order-1">
          <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden lg:sticky lg:top-24">
            <div class="bg-yellow-400 p-6 text-green-950">
              <div class="flex items-center gap-3">
                <UserGroupIcon class="w-7 h-7 md:w-8 md:h-8" />
                <h3 class="text-lg md:text-xl font-black uppercase tracking-tight">Catered Today</h3>
              </div>
              <p class="text-[9px] md:text-[10px] font-black mt-1 uppercase tracking-widest opacity-70">Live Assistance Counter</p>
            </div>
            
            <div class="p-4 md:p-6 space-y-3 md:space-y-4">
              <div v-for="item in assistanceItems" :key="item.category" 
                   class="flex items-center justify-between p-3 md:p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-yellow-200 transition-all">
                <div class="flex items-center gap-3 md:gap-4">
                  <div class="w-9 h-9 md:w-10 md:h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <component :is="item.icon" class="w-4 h-4 md:w-5 md:h-5 text-green-800" />
                  </div>
                  <span class="font-bold text-green-900 text-xs md:text-sm uppercase tracking-tight">{{ item.category }}</span>
                </div>
                <div class="text-right leading-none">
                  <span class="text-base md:text-lg font-black text-green-900 block">{{ item.count }}</span>
                  <span class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase">Total</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-8 space-y-6 order-1 lg:order-2">
          
          <div class="flex bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm gap-1 overflow-x-auto no-scrollbar">
            <button 
              v-for="tab in ['Scholars', 'Medical Aid', 'Legal Aid']" 
              :key="tab"
              @click="activeTab = tab"
              :class="[
                'flex-1 min-w-[100px] py-3 px-2 md:px-6 rounded-xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap',
                activeTab === tab ? 'bg-green-950 text-white shadow-lg' : 'text-gray-400 hover:bg-green-50'
              ]"
            >
              {{ tab }}
            </button>
          </div>

          <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl border border-gray-100 p-5 md:p-8 min-h-[400px]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 md:mb-8 gap-4">
              <div>
                <h2 class="text-xl md:text-2xl font-black text-green-900 uppercase tracking-tight">{{ activeTab }} Registry</h2>
                <p class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-wider">Official Beneficiaries</p>
              </div>
              
              <div class="relative w-full sm:w-64 group">
                <MagnifyingGlassIcon class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-300" />
                <input 
                  v-model="searchQuery" 
                  type="text" 
                  placeholder="Search names..." 
                  class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-xs md:text-sm focus:ring-2 focus:ring-yellow-400 font-medium" 
                />
              </div>
            </div>

            <div class="overflow-x-auto -mx-5 md:mx-0">
              <div class="inline-block min-w-full align-middle px-5 md:px-0">
                <table class="w-full text-left">
                  <thead>
                    <tr class="text-green-900 text-[8px] md:text-[10px] font-black uppercase tracking-widest border-b border-gray-100">
                      <th class="px-2 md:px-4 py-4">Full Name</th>
                      <th class="px-2 md:px-4 py-4">{{ activeTab === 'Scholars' ? 'Institution' : 'Barangay' }}</th>
                      <th class="px-2 md:px-4 py-4 text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-50">
                    <tr v-for="person in filteredData" :key="person.name" class="hover:bg-green-50/50 transition-colors">
                      <td class="px-2 md:px-4 py-4 md:py-5">
                          <p class="font-bold text-green-950 text-xs md:text-sm uppercase leading-tight">{{ person.name }}</p>
                          <p class="text-[8px] md:text-[9px] text-gray-400 font-black tracking-widest mt-0.5">{{ person.id }}</p>
                      </td>
                      <td class="px-2 md:px-4 py-4 md:py-5 text-[11px] md:text-sm text-gray-500 font-medium whitespace-nowrap">
                          {{ activeTab === 'Scholars' ? person.school : person.barangay }}
                      </td>
                      <td class="px-2 md:px-4 py-4 md:py-5 text-center">
                        <span :class="[
                          'px-2 md:px-3 py-1 text-[8px] md:text-[9px] font-black rounded-full uppercase inline-block',
                          person.status === 'Released' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'
                        ]">
                          {{ person.status }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="filteredData.length === 0">
                      <td colspan="3" class="py-16 text-center text-gray-400 text-xs italic">No records found.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { 
  UserGroupIcon, HeartIcon, AcademicCapIcon, ScaleIcon, 
  MagnifyingGlassIcon 
} from '@heroicons/vue/24/outline';

const searchQuery = ref('');
const activeTab = ref('Scholars');

// DATA REGISTRIES (Mocked)
const medicalList = [
  { name: 'ALFONSO, REYNALDO T.', barangay: 'Poblacion', id: 'MED-2024-881', status: 'Released' },
  { name: 'DIZON, CARMELITA S.', barangay: 'San Nicolas', id: 'MED-2024-902', status: 'Released' },
  { name: 'MANALILI, EDUARDO P.', barangay: 'San Jose', id: 'MED-2024-745', status: 'Released' },
];

const legalList = [
  { name: 'ESTACIO, GREGORIO B.', barangay: 'Alfonso', id: 'LEG-2024-012', status: 'Consulted' },
  { name: 'VALENCIA, ROSA M.', barangay: 'Sto. Niño', id: 'LEG-2024-009', status: 'Consulted' },
];

const scholarList = [
  { name: 'DELA CRUZ, JUAN A.', school: 'Bulacan State Univ', id: 'SCH-2024-001', status: 'Active' },
  { name: 'SANTOS, MARIA CLARA', school: 'UP Diliman', id: 'SCH-2024-042', status: 'Active' },
];

const assistanceItems = [
  { category: 'Medical Aid', count: '142', icon: HeartIcon },
  { category: 'Educational', count: '85', icon: AcademicCapIcon },
  { category: 'Legal Aid', count: '12', icon: ScaleIcon },
];

const filteredData = computed(() => {
  let list = [];
  if (activeTab.value === 'Scholars') list = scholarList;
  if (activeTab.value === 'Medical Aid') list = medicalList;
  if (activeTab.value === 'Legal Aid') list = legalList;

  return list.filter(item => 
    item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});
</script>

<style scoped>
/* Scannable layout utilities */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Adjustments for smaller mobile devices */
@media (max-width: 380px) {
  /* Changed 'tracking-normal' to 'letter-spacing: normal' */
  .tracking-widest { 
    letter-spacing: normal !important; 
  }
  h1 { 
    font-size: 1.75rem !important; 
  }
}
</style>