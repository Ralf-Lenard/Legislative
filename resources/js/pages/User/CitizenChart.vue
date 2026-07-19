<template>
  <Head title="Citizen's Charter" />

  <div class="bg-slate-50 min-h-screen font-sans selection:bg-yellow-200 overflow-x-hidden">
    <Navbar />

    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">
          <div class="text-left">
            <span class="inline-block text-[10px] md:text-xs tracking-[0.3em] uppercase bg-yellow-400 text-green-950 px-6 py-2 rounded-full font-black mb-8">
              Service Standards & Transparency
            </span>
            <h1 class="text-4xl md:text-7xl font-black text-white leading-none tracking-tighter mb-8 uppercase">
              Citizen's <span class="text-yellow-400 italic font-serif">Charter</span>
            </h1>
            <p class="max-w-xl text-green-50/80 text-lg md:text-xl font-medium leading-relaxed">
              Official guide on the legislative process and service standards provided by the Sangguniang Bayan of Concepcion.
            </p>
          </div>
        </div>
      </div>
    </section>

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100 shadow-sm">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex overflow-x-auto no-scrollbar gap-8 justify-start md:justify-center py-5">
          <a v-for="link in navLinks" :key="link.href" :href="link.href"
             class="flex-shrink-0 text-[10px] font-black text-green-900 uppercase tracking-[0.2em] hover:text-yellow-600 transition-colors">
            {{ link.name }}
          </a>
        </div>
      </div>
    </nav>

    <section id="issuance" class="py-16 md:py-24 px-6 bg-white relative">
      <div class="max-w-7xl mx-auto">
        <div class="mb-12 md:mb-16 text-center md:text-left">
          <div class="flex items-center justify-center md:justify-start gap-4 mb-6">
            <div class="w-12 h-[2px] bg-yellow-500"></div>
            <h2 class="text-xs font-black text-green-900 uppercase tracking-[0.4em]">Government to Citizen (G2C)</h2>
          </div>
          <h3 class="text-3xl md:text-6xl font-black text-green-950 uppercase tracking-tighter mb-6">
            Issuance of <span class="text-green-700 italic font-serif">Documents</span>
          </h3>
          <p class="max-w-3xl text-gray-500 font-medium text-lg leading-relaxed mx-auto md:mx-0">
            Facilitating access to official records including resolutions, ordinances, and minutes of meetings to promote transparency.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-12">
          <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:bg-green-900 transition-all duration-300">
            <h4 class="text-[10px] font-black text-green-600 group-hover:text-yellow-400 uppercase tracking-widest mb-2">Office / Division</h4>
            <p class="text-green-950 group-hover:text-white font-bold text-sm">Office of the Sangguniang Bayan</p>
          </div>
          <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:bg-green-900 transition-all duration-300">
            <h4 class="text-[10px] font-black text-green-600 group-hover:text-yellow-400 uppercase tracking-widest mb-2">Who may avail</h4>
            <p class="text-green-950 group-hover:text-white font-bold text-sm">Citizenry of Concepcion – 18+ years old</p>
          </div>
          <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:bg-green-900 transition-all duration-300">
            <h4 class="text-[10px] font-black text-green-600 group-hover:text-yellow-400 uppercase tracking-widest mb-2">Classification</h4>
            <p class="text-green-950 group-hover:text-white font-bold text-sm">Simple Transaction</p>
          </div>
          <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group hover:bg-green-900 transition-all duration-300">
            <h4 class="text-[10px] font-black text-green-600 group-hover:text-yellow-400 uppercase tracking-widest mb-2">Requirements</h4>
            <p class="text-green-950 group-hover:text-white font-bold text-sm">Request Form (from SB Office)</p>
          </div>
        </div>

        <div class="bg-white rounded-[2rem] md:rounded-[3rem] border border-gray-100 shadow-xl overflow-hidden">
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
              <thead>
                <tr class="bg-green-900 text-white uppercase text-[10px] tracking-widest font-black">
                  <th class="px-8 py-6">Client Steps</th>
                  <th class="px-8 py-6">Agency Action</th>
                  <th class="px-8 py-6 text-center">Fees</th>
                  <th class="px-8 py-6 text-center">Processing Time</th>
                  <th class="px-8 py-6">Responsible</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="(step, idx) in documentSteps" :key="idx" class="hover:bg-green-50/50 transition-colors">
                  <td class="px-8 py-8 align-top">
                    <span class="text-xl font-black text-yellow-500">0{{ idx + 1 }}</span>
                    <p class="text-green-950 font-bold text-sm mt-1 leading-tight">{{ step.client }}</p>
                  </td>
                  <td class="px-8 py-8 align-top">
                    <p v-for="action in step.actions" :key="action" class="text-sm text-gray-600 mb-2 font-medium">
                      {{ action }}
                    </p>
                  </td>
                  <td class="px-8 py-8 align-top text-center font-black text-green-900 text-sm">
                    {{ step.fee }}
                  </td>
                  <td class="px-8 py-8 align-top text-center">
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-black text-[10px]">
                      {{ step.time }}
                    </span>
                  </td>
                  <td class="px-8 py-8 align-top text-[10px] text-green-800 font-black uppercase tracking-tighter leading-tight whitespace-pre-line">
                    {{ step.person }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="md:hidden divide-y divide-gray-100">
            <div v-for="(step, idx) in documentSteps" :key="idx" class="p-6">
              <div class="flex items-center gap-3 mb-4">
                <span class="w-8 h-8 rounded-full bg-green-900 text-yellow-400 flex items-center justify-center font-black text-sm">0{{ idx+1 }}</span>
                <h4 class="text-green-950 font-black uppercase text-xs leading-tight">{{ step.client }}</h4>
              </div>
              <div class="space-y-4 ml-11">
                <div>
                  <h5 class="text-[10px] font-black text-gray-400 uppercase mb-1">Agency Action</h5>
                  <p v-for="action in step.actions" :key="action" class="text-sm text-gray-600 font-medium leading-snug">{{ action }}</p>
                </div>
                <div class="flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-100">
                  <div>
                    <h5 class="text-[10px] font-black text-gray-400 uppercase">Fee</h5>
                    <p class="text-xs font-black text-green-900">{{ step.fee }}</p>
                  </div>
                  <div class="text-right">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase">Time</h5>
                    <p class="text-xs font-black text-green-900">{{ step.time }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-yellow-400 p-6 md:px-12 md:py-8 flex flex-col md:flex-row justify-between items-center gap-6">
             <span class="font-black text-green-950 uppercase tracking-widest text-xs">Total Service Profile</span>
             <div class="flex items-center gap-10">
                <div class="text-center md:text-right">
                  <p class="text-[10px] font-black text-green-900/60 uppercase mb-1">Total Fee</p>
                  <p class="text-xl font-black text-green-950">PHP 25.00</p>
                </div>
                <div class="w-[1px] h-10 bg-green-900/10"></div>
                <div class="text-center md:text-right">
                  <p class="text-[10px] font-black text-green-900/60 uppercase mb-1">Total Processing Time</p>
                  <p class="text-xl font-black text-green-950">35 Minutes</p>
                </div>
             </div>
          </div>
        </div>
      </div>
    </section>

    <section id="ordinances" class="py-24 px-6 bg-slate-50 border-t border-gray-100">
      <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-20">
        <div>
          <div class="flex items-center gap-4 mb-12">
              <div class="w-12 h-[2px] bg-yellow-500"></div>
              <h2 class="text-2xl font-black text-green-950 uppercase tracking-tighter">Latest Ordinances</h2>
          </div>
          <div class="space-y-6">
            <div v-for="ordinance in latestOrdinances" :key="ordinance.id" class="group p-8 rounded-[2rem] bg-white border border-transparent hover:border-green-200 hover:shadow-2xl transition-all duration-500 cursor-pointer">
              <div class="flex justify-between items-center mb-4">
                <span class="text-[10px] font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full uppercase tracking-widest">
                  No. {{ ordinance.ordinance_number }}
                </span>
                <DocumentTextIcon class="w-5 h-5 text-gray-300 group-hover:text-green-600" />
              </div>
              <h4 class="text-xl font-bold text-green-950 mb-3 group-hover:text-green-700 transition-colors">{{ ordinance.title }}</h4>
              <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">{{ ordinance.description }}</p>
            </div>
            <div v-if="latestOrdinances.length === 0" class="text-center py-10 text-gray-400 font-bold uppercase text-xs">No ordinances found.</div>
          </div>
          <Link href="/citizens-charter/ordinances" class="mt-10 group relative inline-flex items-center justify-center px-10 py-4 font-black text-white transition-all duration-200 bg-green-800 rounded-2xl hover:bg-green-900 uppercase text-[10px] tracking-[0.2em] w-full md:w-auto">
              View All Ordinances
          </Link>
        </div>

        <div id="resolutions">
          <div class="flex items-center gap-4 mb-12">
              <div class="w-12 h-[2px] bg-yellow-500"></div>
              <h2 class="text-2xl font-black text-green-950 uppercase tracking-tighter">Recent Resolutions</h2>
          </div>
          <div class="space-y-6">
            <div v-for="resolution in latestResolutions" :key="resolution.id" class="group p-8 rounded-[2rem] bg-white border border-transparent hover:border-yellow-200 hover:shadow-2xl transition-all duration-500 cursor-pointer">
              <div class="flex justify-between items-center mb-4">
                <span class="text-[10px] font-bold text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full uppercase tracking-widest">
                   No. {{ resolution.resolutions_number }}
                </span>
                <ScaleIcon class="w-5 h-5 text-gray-300 group-hover:text-yellow-600" />
              </div>
              <h4 class="text-xl font-bold text-green-950 mb-3 group-hover:text-yellow-700 transition-colors">{{ resolution.title }}</h4>
              <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">{{ resolution.description_resolutions }}</p>
            </div>
            <div v-if="latestResolutions.length === 0" class="text-center py-10 text-gray-400 font-bold uppercase text-xs">No resolutions found.</div>
          </div>
          <Link href="/citizens-charter/resolutions" class="mt-10 group relative inline-flex items-center justify-center px-10 py-4 font-black text-white transition-all duration-200 bg-green-800 rounded-2xl hover:bg-green-900 uppercase text-[10px] tracking-[0.2em] w-full md:w-auto">
              View All Resolutions
          </Link>
        </div>
      </div>
    </section>

   <section id="assistance" class="py-12 md:py-24 px-4 sm:px-6 bg-slate-50 border-t border-gray-100">
  <div class="max-w-7xl mx-auto">
    <!-- <div class="mb-10 md:mb-16 text-center lg:text-left">
      <div class="flex items-center justify-center lg:justify-start gap-4 mb-4 md:mb-6">
        <div class="w-12 h-[2px] bg-yellow-500"></div>
            <h2 class="text-xs font-black text-green-900 uppercase tracking-[0.4em]">Operational Hours</h2>
      </div>
      <h3 class="text-3xl sm:text-4xl md:text-6xl font-black text-green-950 uppercase tracking-tighter mb-4 md:mb-6">
        Service <span class="text-green-700 italic font-serif">Schedule</span>
      </h3>
    </div> -->
     <div class="mb-12 md:mb-16 text-center md:text-left">
          <div class="flex items-center justify-center md:justify-start gap-4 mb-6">
            <div class="w-12 h-[2px] bg-yellow-500"></div>
            <h2 class="text-xs font-black text-green-900 uppercase tracking-[0.4em]">Operational Hours</h2>
          </div>
          <h3 class="text-3xl md:text-6xl font-black text-green-950 uppercase tracking-tighter mb-6">
            Service <span class="text-green-700 italic font-serif">Schedule</span>
          </h3>

        </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
      
      <div class="lg:col-span-5 w-full">
        <div class="h-full bg-yellow-400 rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 shadow-xl shadow-yellow-200/50 flex flex-col justify-between relative overflow-hidden group min-h-[400px] lg:min-h-0">
          <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-950/10 rounded-full mb-6 md:mb-8">
              <span class="w-2 h-2 rounded-full bg-green-950 animate-pulse"></span>
              <span class="text-[9px] md:text-[10px] font-black text-green-950 uppercase tracking-widest">Weekly Community Event</span>
            </div>
            <h4 class="text-4xl md:text-5xl font-black text-green-950 uppercase tracking-tighter leading-none mb-4">
              People's <br class="hidden md:block"/> Day
            </h4>
            <p class="text-green-950/70 font-bold text-base md:text-lg leading-tight uppercase tracking-tight mb-6 md:mb-8">
              Direct consultations & <br class="hidden sm:block"/> community assistance.
            </p>
            
            <div class="flex items-baseline gap-2">
              <span class="text-5xl md:text-6xl font-black text-green-950 uppercase">Tuesday</span>
            </div>
          </div>
          
          <div class="relative z-10 mt-8 md:mt-12 pt-6 md:pt-8 border-t border-green-950/10">
            <p class="text-[9px] md:text-[10px] font-black text-green-950 uppercase tracking-[0.2em]">Location</p>
            <p class="text-green-950 font-bold text-sm md:text-base">Municipal Legislative / SB Office</p>
          </div>

          <div class="absolute -bottom-6 -right-4 text-8xl md:text-[12rem] font-black text-green-950/5 pointer-events-none select-none uppercase tracking-tighter">
            TUE
          </div>
        </div>
      </div>

      <div class="lg:col-span-7 w-full">
        <div class="bg-white rounded-[2rem] md:rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden h-full flex flex-col">
          <div class="bg-green-900 px-6 md:px-8 py-5 md:py-6 flex flex-col sm:flex-row justify-between items-center gap-2">
            <h4 class="text-white font-black uppercase text-[10px] md:text-xs tracking-widest text-center">Legislative Office Hours</h4>
            <span class="text-yellow-400 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Active Mon-Fri</span>
          </div>
          
          <div class="p-8 md:p-12 flex-grow flex flex-col justify-center">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 md:gap-8 mb-8 md:mb-10 text-center sm:text-left">
              <div class="w-14 h-14 md:w-16 md:h-16 bg-green-50 rounded-2xl flex items-center justify-center shrink-0">
                <BuildingOfficeIcon class="w-7 h-7 md:w-8 md:h-8 text-green-900" />
              </div>
              <div class="w-full">
                <span class="block text-[9px] md:text-[10px] font-black text-green-600 uppercase tracking-[0.2em] mb-2">Standard Operations</span>
                <h5 class="text-2xl md:text-3xl font-black text-green-950 uppercase tracking-tight leading-none mb-4 md:mb-6">Monday to Friday</h5>
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 justify-center sm:justify-start">
                  <span class="text-3xl md:text-4xl font-black text-green-900">8:00 AM</span>
                  <span class="hidden sm:block text-xl font-bold text-gray-300">—</span>
                  <span class="text-3xl md:text-4xl font-black text-green-900">5:00 PM</span>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
              <div class="p-5 md:p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center sm:items-start">
                <span class="block text-[9px] md:text-[10px] font-black text-gray-400 uppercase mb-1 md:mb-2">Policy</span>
                <p class="text-xs md:text-sm font-black text-green-950 uppercase">No Lunch Break</p>
              </div>
              <div class="p-5 md:p-6 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col items-center sm:items-start">
                <span class="block text-[9px] md:text-[10px] font-black text-gray-400 uppercase mb-1 md:mb-2">Status</span>
                <p class="text-xs md:text-sm font-black text-green-950 uppercase">Open Thru Noon</p>
              </div>
            </div>
          </div>

          <div class="p-6 md:p-8 bg-green-50/50 border-t border-green-100 mt-auto">
            <p class="text-[9px] md:text-[10px] text-green-800/60 font-bold uppercase tracking-widest italic text-center">
              * Providing uninterrupted legislative services to the people of Concepcion.
            </p>
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
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { 
  DocumentTextIcon, 
  HeartIcon, 
  AcademicCapIcon,
  ScaleIcon,
  BuildingOfficeIcon
} from '@heroicons/vue/24/outline'; 

// Props connected to Controller
const props = defineProps({
  latestOrdinances: Array,
  latestResolutions: Array,
  latestAssistances: Array,
  assistanceTotals: Object,
  canRegister: Boolean
});

const isScheduleModalOpen = ref(false);

const navLinks = [
  { name: 'Document Issuance', href: '#issuance' },
  { name: 'Ordinances', href: '#ordinances' },
  { name: 'Resolutions', href: '#resolutions' },
  
];

const weeklySchedule = [
  { day: 'Mon-Fri', title: 'Regular SB Services', time: '8:00 AM - 5:00 PM', location: 'SB Secretariat Office' },
  { day: 'Tuesday', title: 'Regular Session', time: '9:00 AM Onwards', location: 'Session Hall' },
  { day: 'Wednesday', title: 'People\'s Day Proper', time: '1:00 PM - 5:00 PM', location: 'Municipal Plaza' },
];

const documentSteps = [
  {
    client: "Properly accomplish and submit request form",
    actions: [
      "1.0 Receive and review submitted request form", 
      "1.1 Search for the requested documents"
    ],
    fee: "None",
    time: "10 Minutes",
    person: "Flordeliza F. Lumandas (LLSO III)\nYolanda P. Yumul (Admin Officer III)\nSB Secretariat Office"
  },
  {
    client: "Pay to the Cashier",
    actions: ["2.0 Receive payment and Issue Official Receipt"],
    fee: "PHP 25.00/page",
    time: "20 Minutes",
    person: "Municipal Treasury"
  },
  {
    client: "Present Official Receipt and Receive requested documents",
    actions: [
      "3.0 Check the official receipt", 
      "3.1 Retrieve and Authenticate requested data", 
      "3.2 Release requested data/documents"
    ],
    fee: "None",
    time: "5 Minutes",
    person: "Flordeliza F. Lumandas (LLSO III)\nYolanda P. Yumul (Admin Officer III)\nSB Secretariat Office"
  }
];
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
html { scroll-behavior: smooth; }
section { scroll-margin-top: 80px; }
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>