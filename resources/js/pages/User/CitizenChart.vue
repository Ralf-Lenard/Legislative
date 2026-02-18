<template>
  <Head title="Citizen's Charter" />
  <div class="bg-slate-50 min-h-screen font-sans selection:bg-yellow-200">
    <Navbar />

    <section class="relative pt-32 pb-20 md:pt-48 md:pb-32 bg-gradient-to-br from-green-900 via-green-800 to-green-900 overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400/10 rounded-full blur-[100px] -mr-20 -mt-20"></div>
      <div class="absolute bottom-0 left-0 w-72 h-72 bg-green-400/5 rounded-full blur-[80px] -ml-10 -mb-10"></div>
      
      <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="max-w-3xl">
          <span class="inline-flex items-center gap-2 text-[10px] md:text-xs tracking-[0.2em] uppercase bg-yellow-400 text-green-950 px-4 py-1.5 rounded-full font-black mb-6 shadow-xl shadow-yellow-900/20">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-900 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-800"></span>
            </span>
            Transparency Portal
          </span>
          <h1 class="text-4xl md:text-7xl font-black text-white leading-[1.1] tracking-tight">
            Citizen's <span class="text-yellow-400">Charter</span>
          </h1>
          <p class="text-lg md:text-xl mt-6 text-green-50/80 leading-relaxed font-medium">
            Your comprehensive guide to municipal services, legislative transparency, and public welfare programs.
          </p>
        </div>
      </div>
    </section>

    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
      <div class="max-w-7xl mx-auto px-4">
        <div class="flex overflow-x-auto no-scrollbar py-1 gap-2 md:justify-center">
          <a v-for="link in navLinks" :key="link.href" :href="link.href" 
             class="flex-shrink-0 px-5 py-4 text-xs font-bold text-green-900 uppercase tracking-widest hover:text-yellow-600 border-b-2 border-transparent hover:border-yellow-400 transition-all">
            {{ link.name }}
          </a>
        </div>
      </div>
    </nav>

    <section id="charter" class="py-24 px-6">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
          <div class="max-w-2xl">
            <h2 class="text-3xl md:text-4xl font-black text-green-900 uppercase tracking-tight">Service Standards</h2>
            <div class="w-24 h-2 bg-yellow-400 mt-4 rounded-full"></div>
            <p class="mt-6 text-gray-600 text-lg">Clear, predictable, and efficient procedures for every citizen request.</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="service in services" :key="service.title" 
               class="group bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 hover:shadow-2xl hover:shadow-green-900/10 transition-all duration-500">
            <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:bg-yellow-400 transition-all duration-500">
                <component :is="service.icon" class="w-8 h-8 text-green-800 group-hover:text-green-950" />
            </div>
            <h4 class="text-2xl font-bold text-green-900 mb-4">{{ service.title }}</h4>
            <p class="text-gray-500 leading-relaxed mb-8">{{ service.description }}</p>
            <button class="inline-flex items-center gap-2 text-xs font-black text-green-800 uppercase tracking-[0.15em] group/btn">
              View Procedure 
              <span class="w-8 h-[2px] bg-yellow-400 group-hover/btn:w-12 transition-all"></span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <section id="ordinances" class="py-24 px-6 bg-white border-y border-gray-100">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
        <div class="space-y-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="w-3 h-12 bg-green-800 rounded-full"></div>
              <h2 class="text-3xl font-black text-green-900 uppercase">Latest Ordinances</h2>
            </div>
          </div>
          <div class="space-y-4">
            <div v-for="i in 3" :key="i" class="group p-6 bg-gray-50 rounded-3xl border border-transparent hover:border-green-200 hover:bg-white hover:shadow-xl transition-all cursor-pointer">
              <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black bg-green-100 text-green-800 px-3 py-1 rounded-md uppercase tracking-tighter">Series of 2024</span>
                <DocumentTextIcon class="w-5 h-5 text-gray-300 group-hover:text-green-600" />
              </div>
              <h4 class="font-bold text-xl text-green-900">Market Operations & Regulation Code</h4>
              <p class="text-sm text-gray-500 mt-2 line-clamp-2">An ordinance establishing the comprehensive rules for local vendors and market spaces...</p>
            </div>
          </div>
          <button class="w-full py-5 bg-green-900 hover:bg-green-800 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg transition-all active:scale-[0.98]">
            Browse All Ordinances
          </button>
        </div>

        <div id="resolutions" class="space-y-8">
          <div class="flex items-center gap-4">
            <div class="w-3 h-12 bg-yellow-400 rounded-full"></div>
            <h2 class="text-3xl font-black text-green-900 uppercase">Recent Resolutions</h2>
          </div>
          <div class="space-y-4">
            <div v-for="i in 3" :key="i" class="group p-6 bg-gray-50 rounded-3xl border border-transparent hover:border-yellow-200 hover:bg-white hover:shadow-xl transition-all cursor-pointer">
              <div class="flex justify-between items-start mb-2">
                <span class="text-[10px] font-black bg-yellow-100 text-yellow-700 px-3 py-1 rounded-md uppercase tracking-tighter">Res. No. 102-2024</span>
                <ScaleIcon class="w-5 h-5 text-gray-300 group-hover:text-yellow-500" />
              </div>
              <h4 class="font-bold text-xl text-green-900">Resolution Commending Local Youth Leaders</h4>
              <p class="text-sm text-gray-500 mt-2 line-clamp-2">Recognizing the outstanding contribution of the Sangguniang Kabataan in recent activities...</p>
            </div>
          </div>
          <button class="w-full py-5 bg-white border-2 border-green-900 text-green-900 hover:bg-green-50 font-black uppercase tracking-widest rounded-2xl transition-all active:scale-[0.98]">
            Browse All Resolutions
          </button>
        </div>
      </div>
    </section>

    <section id="assistance" class="py-24 px-6 bg-slate-50">
      <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-20">
          <h2 class="text-4xl font-black text-green-900 uppercase tracking-tight">Public Assistance Programs</h2>
          <div class="w-24 h-2 bg-yellow-400 mx-auto mt-6 rounded-full"></div>
          <p class="mt-6 text-gray-600 text-lg font-medium">Empowering our community through transparent support and direct legislative assistance.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <div class="bg-white rounded-[3rem] shadow-2xl shadow-green-900/5 overflow-hidden border border-gray-100 flex flex-col">
            <div class="bg-green-900 p-8 text-white">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-2xl font-bold uppercase">Educational Assistance</h3>
                  <p class="text-green-300 text-xs mt-1 font-bold tracking-widest">MUNICIPAL SCHOLARS REGISTRY</p>
                </div>
                <AcademicCapIcon class="w-12 h-12 text-yellow-400 opacity-80" />
              </div>
            </div>
            <div class="p-8 flex-grow">
              <div class="overflow-x-auto">
                <table class="w-full text-left">
                  <thead class="text-green-900 font-black uppercase text-[10px] tracking-widest">
                    <tr class="border-b border-gray-100">
                      <th class="pb-4 px-2">Scholar Name</th>
                      <th class="pb-4 px-2">Institution</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-50">
                    <tr v-for="i in 5" :key="i" class="group hover:bg-green-50/50 transition-colors">
                      <td class="py-4 px-2 font-bold text-gray-700">Sample Student Name {{ i }}</td>
                      <td class="py-4 px-2 text-gray-500 font-medium">Concepcion State College</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button class="w-full mt-8 py-4 bg-gray-50 text-green-900 font-black rounded-2xl hover:bg-green-900 hover:text-white transition-all duration-300 border border-gray-200">
                Download Official List (PDF)
              </button>
            </div>
          </div>

          <div class="bg-white rounded-[3rem] shadow-2xl shadow-yellow-900/5 overflow-hidden border border-gray-100">
            <div class="bg-yellow-400 p-8 text-green-950">
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="text-2xl font-bold uppercase">People's Day Tracking</h3>
                  <p class="text-green-900/60 text-xs mt-1 font-bold tracking-widest">WEEKLY SERVICES RENDERED</p>
                </div>
                <UserGroupIcon class="w-12 h-12 text-green-900/20" />
              </div>
            </div>
            <div class="p-8">
              <div class="grid gap-4">
                <div v-for="item in assistanceItems" :key="item.category" 
                     class="flex items-center justify-between p-5 bg-gray-50 rounded-[2rem] border border-transparent hover:border-yellow-200 transition-all">
                  <div class="flex items-center gap-5">
                    <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center shadow-sm text-green-800">
                      <component :is="item.icon" class="w-6 h-6" />
                    </div>
                    <span class="font-black text-green-900 uppercase text-sm tracking-tight">{{ item.category }}</span>
                  </div>
                  <div class="text-right">
                    <span class="block text-xl font-black text-green-900">{{ item.count }}</span>
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Beneficiaries</span>
                  </div>
                </div>
              </div>
              <button class="w-full mt-8 py-5 bg-green-950 text-yellow-400 font-black rounded-2xl shadow-xl shadow-green-950/20 hover:-translate-y-1 transition-all">
                Check Availability Schedule
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
import { Head } from '@inertiajs/vue3';
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { 
  BookOpenIcon, 
  DocumentTextIcon, 
  UserGroupIcon, 
  HeartIcon, 
  AcademicCapIcon,
  ScaleIcon 
} from '@heroicons/vue/24/outline'; 

const navLinks = [
  { name: 'Service Charter', href: '/citizens-charter' },
  { name: 'Ordinances', href: '/citizens-charter/ordinances' },
  { name: 'Resolutions', href: '/citizens-charter/resolutions' },
  { name: 'Assistance', href: '/citizens-charter/public-assistance' },
];

const services = [
  { 
    title: 'Securing Certified Copies', 
    description: 'Requesting authenticated copies of municipal ordinances and resolutions for legal or personal use.', 
    icon: DocumentTextIcon 
  },
  { 
    title: 'Legislative Research', 
    description: 'In-depth access to our digital and physical archives for academic, professional, or legal research.', 
    icon: BookOpenIcon 
  },
  { 
    title: 'Public Consultation', 
    description: 'Guidelines on how to participate in committee hearings and voice your concerns to the council.', 
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

html { scroll-behavior: smooth; }

section { scroll-margin-top: 80px; }

/* Custom animation for entry */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

#charter .group {
  animation: fadeInUp 0.6s ease forwards;
}
</style>