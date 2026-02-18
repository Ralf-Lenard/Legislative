<template>
  <Head title="Public Assistance & Records" />
  <div class="bg-slate-50 min-h-screen">
    <Navbar />

    <section class="pt-32 pb-20 bg-gradient-to-br from-green-900 to-green-800 relative overflow-hidden text-white">
      <div class="absolute top-0 right-0 w-80 h-80 bg-yellow-400/10 rounded-full blur-[100px]"></div>
      <div class="max-w-7xl mx-auto px-6 relative z-10 text-center md:text-left">
        <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
          Public Assistance <br /><span class="text-yellow-400">Programs & Records</span>
        </h1>
        <p class="text-lg text-green-50/80 max-w-2xl leading-relaxed">
          Transparent monitoring of our municipal scholarships and the weekly "People's Day" assistance programs.
        </p>
      </div>
    </section>

    <section class="py-12 px-6">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
          <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden sticky top-24">
            <div class="bg-yellow-400 p-6 text-green-950">
              <div class="flex items-center gap-3">
                <UserGroupIcon class="w-8 h-8" />
                <h3 class="text-xl font-black uppercase tracking-tight">People's Day</h3>
              </div>
              <p class="text-xs font-bold mt-1 opacity-70 italic text-green-900">Weekly Assistance Tracking</p>
            </div>
            
            <div class="p-6 space-y-4">
              <div v-for="item in assistanceItems" :key="item.category" 
                   class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-yellow-50 transition-colors border border-transparent hover:border-yellow-200">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <component :is="item.icon" class="w-5 h-5 text-green-800" />
                  </div>
                  <span class="font-bold text-green-900 text-sm uppercase tracking-tight">{{ item.category }}</span>
                </div>
                <div class="text-right leading-none">
                  <span class="text-lg font-black text-green-900 block">{{ item.count }}</span>
                  <span class="text-[9px] font-black text-gray-400 uppercase">Catered</span>
                </div>
              </div>

              <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="flex items-center gap-3 text-green-800">
                  <CalendarIcon class="w-5 h-5" />
                  <span class="text-sm font-black uppercase">Schedule</span>
                </div>
                <p class="text-sm text-gray-500 mt-2 font-medium">Every Tuesday | 8:00 AM - 5:00 PM</p>
                <p class="text-xs text-gray-400 mt-1 italic">Walk-ins are catered based on priority numbers.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
              <div>
                <h2 class="text-2xl font-black text-green-900 uppercase tracking-tight">List of Municipal Scholars</h2>
                <p class="text-sm text-gray-500 font-medium mt-1">Academic Year 2024-2025 (1st Semester)</p>
              </div>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <MagnifyingGlassIcon class="h-5 w-5 text-gray-300 group-focus-within:text-green-600 transition-colors" />
                </div>
                <input 
                  type="text" 
                  v-model="searchQuery" 
                  placeholder="Search by name or school..." 
                  class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-yellow-400 transition-all font-medium"
                />
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead>
                  <tr class="text-green-900 text-[10px] font-black uppercase tracking-widest border-b border-gray-100">
                    <th class="px-4 py-4">Beneficiary Name</th>
                    <th class="px-4 py-4">School/Institution</th>
                    <th class="px-4 py-4 text-center">Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                  <tr v-for="scholar in filteredScholars" :key="scholar.name" class="hover:bg-green-50/50 transition-colors group">
                    <td class="px-4 py-5">
                      <p class="font-bold text-green-900 leading-none">{{ scholar.name }}</p>
                      <span class="text-[10px] text-gray-400 font-medium uppercase tracking-tight">{{ scholar.id }}</span>
                    </td>
                    <td class="px-4 py-5">
                      <p class="text-sm font-medium text-gray-600">{{ scholar.school }}</p>
                    </td>
                    <td class="px-4 py-5 text-center">
                      <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-tighter">
                        Active
                      </span>
                    </td>
                  </tr>
                  <tr v-if="filteredScholars.length === 0">
                    <td colspan="3" class="py-20 text-center text-gray-400 italic">No records found matching your search.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">
              <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Showing {{ filteredScholars.length }} Beneficiaries</span>
              <button class="px-6 py-2 bg-green-900 text-white text-[10px] font-black rounded-full uppercase tracking-widest hover:bg-green-800 transition-all">
                Download PDF
              </button>
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
import { Head } from '@inertiajs/vue3';
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { 
  UserGroupIcon, 
  HeartIcon, 
  AcademicCapIcon, 
  ScaleIcon, 
  CalendarIcon,
  MagnifyingGlassIcon,
  HomeIcon
} from '@heroicons/vue/24/outline';

const searchQuery = ref('');

// List of Scholars Data
const scholars = [
  { name: 'DELA CRUZ, JUAN A.', id: 'SCH-2024-001', school: 'Bulacan State University' },
  { name: 'SANTOS, MARIA CLARA', id: 'SCH-2024-042', school: 'University of the Philippines' },
  { name: 'GARCIA, RICARDO L.', id: 'SCH-2024-115', school: 'La Consolacion University' },
  { name: 'REYES, BEA M.', id: 'SCH-2024-098', school: 'Baliuag University' },
  { name: 'PASCUAL, KENNETH S.', id: 'SCH-2024-056', school: 'STI College Malolos' },
  { name: 'MENDOZA, ELENA P.', id: 'SCH-2024-211', school: 'Bulacan State University' },
  { name: 'RAMOS, ARNEL J.', id: 'SCH-2024-077', school: 'Concepcion State College' },
  { name: 'BAUTISTA, CHLOE V.', id: 'SCH-2024-012', school: 'Bulacan Agricultural College' },
];

// People's Day Statistics
const assistanceItems = [
  { category: 'Medical Aid', count: '142', icon: HeartIcon },
  { category: 'Burial Aid', count: '24', icon: HomeIcon },
  { category: 'Educational', count: '85', icon: AcademicCapIcon },
  { category: 'Legal Aid', count: '12', icon: ScaleIcon },
];

// Search Logic
const filteredScholars = computed(() => {
  return scholars.filter(scholar => {
    return scholar.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
           scholar.school.toLowerCase().includes(searchQuery.value.toLowerCase());
  });
});
</script>

<style scoped>
/* Scannable layout styles */
section { scroll-margin-top: 100px; }

/* Custom table spacing */
table { border-collapse: separate; border-spacing: 0; }
</style>