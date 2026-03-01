<template>
  <Head title="Public Assistance & Records" />
  <div class="bg-slate-50 min-h-screen selection:bg-yellow-200 overflow-x-hidden">
    <Navbar />
<!-- 
    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>
      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">
          <Link href="/citizens-charter" class="relative z-20 inline-flex items-center text-yellow-400 hover:text-white mb-8 transition-colors font-bold text-xs uppercase tracking-[0.2em]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Citizen Charter
          </Link>
          <div class="block">
            <span class="text-xs tracking-widest uppercase bg-yellow-400 text-green-900 px-4 py-1 rounded-full font-bold">Official Registry</span>
          </div>
          <h1 class="text-3xl sm:text-4xl md:text-7xl font-black mb-4 md:mb-6 leading-none uppercase tracking-tighter">
            Public Assistance <br class="hidden md:block"/><span class="text-yellow-400 italic font-serif text-2xl sm:text-4xl md:text-6xl">Registry & Records</span>
          </h1>
          <p class="text-lg mt-3 text-gray-100 max-w-2xl">Real-time transparency for municipal assistance programs. Browse verified beneficiaries for the current quarter.</p>
        </div>
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

          <h1 class="text-5xl md:text-6xl font-extrabold mt-4 leading-tight">
            Public Assistance
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
                <h3 class="text-lg md:text-xl font-black uppercase tracking-tight">Catered Total</h3>
              </div>
              <p class="text-[9px] md:text-[10px] font-black mt-1 uppercase tracking-widest opacity-70">Live Assistance Counter</p>
            </div>
            
            <div class="p-4 md:p-6 space-y-3 md:space-y-4">
              <div v-for="stat in stats" :key="stat.label" class="flex items-center justify-between p-3 md:p-4 bg-gray-50 rounded-2xl border border-transparent">
                <div class="flex items-center gap-3 md:gap-4">
                  <div class="w-9 h-9 md:w-10 md:h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <component :is="stat.icon" class="w-4 h-4 md:w-5 md:h-5 text-green-800" />
                  </div>
                  <span class="font-bold text-green-900 text-xs md:text-sm uppercase tracking-tight">{{ stat.label }}</span>
                </div>
                <div class="text-right leading-none">
                  <span class="text-base md:text-lg font-black text-green-900 block">{{ stat.value }}</span>
                  <span class="text-[8px] md:text-[9px] font-black text-gray-400 uppercase">Total</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-8 space-y-6 order-1 lg:order-2">
          <div class="flex bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm gap-1 overflow-x-auto no-scrollbar">
            <button 
              v-for="tab in tabs" :key="tab.value"
              @click="switchTab(tab.value)"
              :class="[
                'flex-1 min-w-[100px] py-3 px-2 md:px-6 rounded-xl text-[9px] md:text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap',
                filters.type === tab.value ? 'bg-green-950 text-white shadow-lg' : 'text-gray-400 hover:bg-green-50'
              ]"
            >
              {{ tab.name }}
            </button>
          </div>

          <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl border border-gray-100 p-5 md:p-8 min-h-[400px]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 md:mb-8 gap-4">
              <div>
                <h2 class="text-xl md:text-2xl font-black text-green-900 uppercase tracking-tight">{{ currentTabLabel }} Registry</h2>
                <p class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-wider">Verified Records</p>
              </div>
              
              <div class="relative w-full sm:w-64 group">
                <MagnifyingGlassIcon class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-300" />
                <input 
                  v-model="search" 
                  @input="handleSearch"
                  type="text" 
                  placeholder="Search full name..." 
                  class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-xs md:text-sm focus:ring-2 focus:ring-yellow-400 font-medium" 
                />
              </div>
            </div>

            <div class="overflow-x-auto -mx-5 md:mx-0">
              <div class="inline-block min-w-full align-middle px-5 md:px-0">
                <table class="w-full text-left">
                  <thead>
                    <tr class="text-green-900 text-[8px] md:text-[10px] font-black uppercase tracking-widest border-b border-gray-100">
                      <th class="px-2 md:px-4 py-4 w-2/3">Beneficiary Full Name</th>
                      <th class="px-2 md:px-4 py-4">Assistance Category</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-50">
                    <tr v-for="item in assistances.data" :key="item.id" class="hover:bg-green-50/50 transition-colors">
                      <td class="px-2 md:px-4 py-5">
                          <p class="font-bold text-green-950 text-sm md:text-base uppercase leading-tight">{{ item.full_name }}</p>
                          <p class="text-[10px] text-gray-400 font-black tracking-tighter mt-1 uppercase italic">Verified Beneficiary</p>
                      </td>
                      <td class="px-2 md:px-4 py-5">
                        <span class="text-xs md:text-sm font-medium text-gray-600 uppercase tracking-tight">
                            {{ item.type }}
                        </span>
                      </td>
                    </tr>
                    <tr v-if="assistances.data.length === 0">
                      <td colspan="2" class="py-16 text-center text-gray-400 text-xs italic">No records found for this category.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="mt-8 flex justify-center gap-2">
                <Link 
                    v-for="link in assistances.links" :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    :class="[
                        'px-4 py-2 rounded-lg text-xs font-bold transition-all',
                        link.active ? 'bg-green-950 text-white shadow-md' : 'bg-gray-50 text-gray-400 hover:bg-yellow-400 hover:text-green-950',
                        !link.url ? 'opacity-30 cursor-not-allowed' : ''
                    ]"
                />
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
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from "lodash/debounce";
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { 
  UserGroupIcon, 
  HeartIcon, 
  AcademicCapIcon, 
  ScaleIcon, 
  MagnifyingGlassIcon 
} from '@heroicons/vue/24/outline';

const props = defineProps({
  assistances: Object,
  filters: Object,
  counts: Object
});

const search = ref(props.filters.search);

const tabs = [
  { name: 'Scholars', value: 'scholar' },
  { name: 'Medical Aid', value: 'medical' },
  { name: 'Legal Aid', value: 'legal' },
];

const stats = computed(() => [
  { label: 'Medical Aid', value: props.counts.medical, icon: HeartIcon },
  { label: 'Scholars', value: props.counts.scholar, icon: AcademicCapIcon },
  { label: 'Legal Aid', value: props.counts.legal, icon: ScaleIcon },
]);

const currentTabLabel = computed(() => 
  tabs.find(t => t.value === props.filters.type)?.name || 'Assistance'
);

const switchTab = (type) => {
  router.get(window.location.pathname, { type, search: search.value }, {
    preserveState: true,
    replace: true
  });
};

const handleSearch = debounce(() => {
  router.get(window.location.pathname, { 
    type: props.filters.type, 
    search: search.value 
  }, {
    preserveState: true,
    replace: true
  });
}, 300);
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
@media (max-width: 380px) {
  .tracking-widest { letter-spacing: normal !important; }
  h1 { font-size: 1.75rem !important; }
}
</style>