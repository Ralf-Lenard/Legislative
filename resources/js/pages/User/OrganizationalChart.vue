<template>
  <Head title="Organizational Chart" />
  <div class="bg-white min-h-screen">
    <Navbar />

    <section class="pt-28 pb-20 bg-gradient-to-br from-green-900 to-green-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300/10 rounded-full blur-3xl z-0"></div>

      <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 relative z-10">
        <div class="flex-1 text-white">
          <div class="text-left">
            
            <span class="inline-block text-[10px] md:text-xs tracking-[0.3em] uppercase bg-yellow-400 text-green-950 px-6 py-2 rounded-full font-black mb-8">
              Municipal Leadership Structure
            </span>

            <h1 class="text-4xl md:text-7xl font-black text-white leading-none tracking-tighter mb-8 uppercase">
              Organizational <span class="text-yellow-400 italic font-serif">Chart</span>
            </h1>

            <p class="max-w-xl text-green-50/80 text-lg md:text-xl font-medium leading-relaxed">
              Overview of the municipal government structure, including elected officials and employees responsible for delivering public service.
            </p>

          </div>
        </div>
      </div>
    </section>

    <section class="py-16 bg-white border-b border-gray-100">
      <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-black text-green-900 uppercase">Legislative Process</h2>
            <div class="w-24 h-1.5 bg-yellow-400 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="flex flex-col items-center space-y-4">
          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg text-center bg-gray-50 shadow-sm">
            <h4 class="font-black text-green-900 uppercase">Introduction or Sponsorship</h4>
            <p class="text-sm text-gray-600 mt-1">(proposed ordinance/resolution from the Office of the Local Chief Executive and Sangguniang Bayan Member/s)</p>
          </div>
          <div class="text-green-800">
            <svg class="w-8 h-8 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
          </div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg bg-gray-50 shadow-sm">
             <h4 class="font-black text-green-900 uppercase text-center mb-4">Action on the Proposed Legislative Measure</h4>
             <ul class="text-sm text-gray-700 space-y-2 list-disc pl-5">
                <li>Endorsement of the proposed legislative measure from the Office of the Local Chief Executive or filing by the sangguniang bayan member/s</li>
                <li>Recording of the proposed legislative measure by the sangguniang bayan secretariat indicating therein all pertinent information</li>
                <li>Inclusion in the Order of Business</li>
             </ul>
          </div>
          <div class="text-green-800"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg text-center bg-gray-50 shadow-sm">
            <h4 class="font-black text-green-900 uppercase">First Reading and Committee Referral/s</h4>
            <p class="text-sm text-gray-600 mt-1">(reading of the title of the proposed ordinance/resolution and subsequent referral to the appropriate standing committee/s)</p>
          </div>
          <div class="text-green-800"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg bg-gray-50 shadow-sm">
            <h4 class="font-black text-green-900 uppercase text-center mb-4">Committee Deliberations and Action</h4>
            <p class="text-sm text-gray-700 text-center">concerned standing committee/s conduct/s meeting/hearing on the proposed ordinance/resolution and reported out in accordance with the sanggunian internal rules of procedure</p>
          </div>
          <div class="text-green-800"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg bg-gray-50 shadow-sm text-center">
            <h4 class="font-black text-green-900 uppercase">Second Reading</h4>
            <p class="text-xs text-gray-500 mb-2">(plenary deliberations and approval)</p>
            <ul class="text-sm text-gray-700 font-medium">
                <li>Sponsorship, period of debate & period of amendments</li>
                <li>Voting</li>
            </ul>
          </div>
          <div class="text-green-800"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg bg-gray-50 shadow-sm text-center">
            <h4 class="font-black text-green-900 uppercase">Third Reading</h4>
            <p class="text-xs text-gray-500 mb-2">(in the case of an ordinance)</p>
            <ul class="text-sm text-gray-700 font-medium">
                <li>Reading of the title of the proposed ordinance</li>
                <li>Voting</li>
            </ul>
          </div>
          <div class="text-green-800"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg bg-gray-50 shadow-sm">
            <h4 class="font-black text-green-900 uppercase text-center mb-4">Transmittal</h4>
            <ul class="text-sm text-gray-700 space-y-2 list-disc pl-5">
                <li>Signed copies of the ordinance/resolution transmitted to the Office of the Local Chief Executive for approval</li>
                <li>Approved ordinance/resolution transmitted to the Sangguniang Panlalawigan for review</li>
            </ul>
          </div>
          <div class="text-green-800"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>

          <div class="w-full max-w-2xl p-6 border-2 border-green-800 rounded-lg bg-gray-50 shadow-sm text-center">
            <h4 class="font-black text-green-900 uppercase">Effectivity, Posting and Publication</h4>
            <p class="text-xs text-gray-600 mt-2 font-bold uppercase italic">(in accordance with the provisions of Sec. 59 and Sec. 511 of RA 7160 - Local Government Code)</p>
          </div>
        </div>
      </div>
    </section>

    <section v-if="organizationalChart" class="py-16 bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-green-900 uppercase">Organizational Chart</h2>
                <div class="w-24 h-1.5 bg-yellow-400 mx-auto mt-4 rounded-full"></div>
                <p class="text-gray-500 mt-4">Visual representation of our office hierarchy and departments.</p>
            </div>
            
            <div class="bg-white p-4 rounded-3xl shadow-2xl border border-gray-100 overflow-hidden group flex items-center justify-center">
              <img 
                  :src="organizationalChart" 
                  alt="Sangguniang Bayan Organizational Chart"
                  class="w-full h-[90vh] object-contain mx-auto rounded-2xl group-hover:scale-[1.01] transition-transform duration-500"
              />

            </div>
        </div>
    </section>

   <section v-if="presidingOfficer" class="py-12 md:py-20 px-4 bg-white">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-green-900 text-center mb-8 md:mb-12">Office of the Vice Mayor</h2>
    
    <!-- Card wrapper with click event -->
    <div 
      @click="openBio(presidingOfficer)" 
      class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl border-t-4 md:border-t-0 md:border-l-8 border-yellow-400 overflow-hidden transition-transform hover:scale-[1.01] cursor-pointer"
    >
      <div class="flex flex-col md:flex-row items-center p-6 md:p-10 gap-6 md:gap-10">
        <div class="w-48 h-72 md:w-64 md:h-96 flex-shrink-0 rounded-xl overflow-hidden border-4 border-green-800 shadow-lg">
          <img :src="imageUrl(presidingOfficer.image)" :alt="presidingOfficer.name" class="w-full h-full object-cover">
        </div>
        <div class="text-center md:text-left">
          <p class="text-sm md:text-base font-bold text-yellow-600 uppercase tracking-widest">{{ presidingOfficer.position }}</p>
          <h3 class="text-2xl md:text-4xl font-black text-green-900 mt-2">{{ presidingOfficer.name }}</h3>
          <p class="text-base md:text-lg mt-4 text-gray-600 italic leading-relaxed">"Dedicated to transparent legislation and responsive governance for all Concepcionenses."</p>
        </div>
      </div>
    </div>
  </div>
</section>

    <section v-if="councilMembers.length" class="py-16 px-4 bg-gray-50">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col items-center mb-12 text-center">
      <h2 class="text-2xl md:text-3xl font-bold text-green-900">Municipal Councilors</h2>
      <div class="w-20 h-1.5 bg-yellow-400 mt-3 rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
      <div 
        v-for="member in filteredCouncil" 
        :key="member.id" 
        @click="openBio(member)"
        class="group bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer"
      >
        <div class="aspect-[4/5] overflow-hidden relative">
          <img :src="imageUrl(member.image)" :alt="member.name" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
          <div class="absolute inset-0 bg-gradient-to-t from-green-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
            <span class="text-white text-xs font-bold uppercase tracking-widest">Sangguniang Bayan</span>
          </div>
        </div>
        <div class="p-5 text-center">
          <h3 class="text-lg font-bold text-green-900 leading-tight group-hover:text-green-700 transition-colors">{{ member.name }}</h3>
          <p class="text-xs text-gray-500 mt-2 font-medium uppercase tracking-tighter h-8 flex items-center justify-center">{{ member.main_committee || member.position }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

   <section v-if="employees.length" class="py-20 px-4 bg-white border-t border-gray-200">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16">
      <h2 class="text-2xl md:text-3xl font-bold text-green-900">Administrative Support Staff</h2>
      <p class="text-gray-500 mt-2 text-sm md:text-base">The professional team behind the Sangguniang Bayan operations.</p>
    </div>

    <!-- Secretary Group -->
    <div v-if="secretaryGroup.length" class="mb-16 flex flex-col items-center">
      <div v-for="emp in secretaryGroup" :key="emp.id" 
           @click="openEmployee(emp)"
           class="bg-white p-6 md:p-8 rounded-3xl shadow-xl border-b-8 border-green-800 w-full max-w-sm text-center cursor-pointer hover:shadow-2xl transition-all hover:-translate-y-2 group">
        <div class="w-32 h-32 md:w-40 md:h-40 mx-auto rounded-2xl overflow-hidden border-4 border-yellow-400 mb-6 rotate-3 group-hover:rotate-0 transition-transform">
          <img :src="imageUrl(emp.image)" :alt="emp.name" class="w-full h-full object-cover">
        </div>
        <h4 class="text-xl md:text-2xl font-black text-green-900">{{ emp.name }}</h4>
        <p class="text-xs md:text-sm font-bold text-yellow-600 uppercase tracking-widest mt-1">{{ emp.position }}</p>
      </div>
      <div class="hidden md:block w-0.5 h-16 bg-gradient-to-b from-gray-300 to-transparent mt-4"></div>
    </div>

    <!-- Division Groups -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12">
      <div v-for="(members, divName) in divisionGroups" :key="divName" class="flex flex-col">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-8 bg-yellow-400 rounded-full"></div>
            <h3 class="text-sm font-black text-green-900 uppercase tracking-widest">{{ divName }}</h3>
        </div>
        <div class="space-y-4">
          <div v-for="emp in members" :key="emp.id" 
               @click="openEmployee(emp)"
               class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-green-400 hover:shadow-lg transition-all cursor-pointer group">
            <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-gray-100 flex-shrink-0 group-hover:border-green-200">
              <img :src="imageUrl(emp.image)" :alt="emp.name" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0 text-left">
              <h4 class="font-bold text-green-900 text-sm md:text-base truncate">{{ emp.name }}</h4>
              <p class="text-[10px] md:text-xs text-gray-500 uppercase font-bold mt-1">{{ emp.position }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<transition name="modal-fade">
  <div v-if="showBioModal" class="fixed inset-0 flex items-center justify-center p-4 md:p-6 bg-green-950/40 backdrop-blur-md z-[100]" @click.self="closeBio">
    <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl border border-white/20 relative flex flex-col overflow-hidden animate-modal-in" style="max-height: 90vh;">

      <!-- Close X button -->
      <button @click="closeBio" 
              class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-3xl font-bold z-10">
        &times;
      </button>

      <div class="overflow-y-auto custom-scrollbar p-6 md:p-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12">

          <!-- Left column: Image and basic info -->
          <div class="md:col-span-4 flex flex-col items-center">
            <div class="w-full aspect-[3/4] md:aspect-[4/5] rounded-2xl overflow-hidden border-[6px] border-yellow-400 shadow-2xl mb-6">
             <img 
                :src="imageUrl(modalMemberData.image)" 
                :alt="modalMemberData.name" 
                class="w-full h-full object-cover object-[50%_10%]"
              />
            </div>
            <div class="text-center">
              <h2 class="text-3xl md:text-4xl font-black text-green-900 leading-tight">{{ modalMemberData.name }}</h2>
              <p class="text-sm md:text-base font-bold text-yellow-600 uppercase">{{ modalMemberData.position }}</p>
            </div>
          </div>

          <!-- Right column: Profile details and committees -->
          <div class="md:col-span-8">
            <h3 class="text-xl md:text-2xl font-black text-green-900 uppercase border-b-2 border-yellow-400 pb-2 mb-4">Profile Details</h3>
            
            <p class="text-gray-600 leading-relaxed text-sm md:text-base text-justify whitespace-pre-line">
              {{ modalMemberData.bio || getDefaultText(modalMemberData) }}
            </p>

            <!-- Committees section -->
            <div v-if="modalMemberData.main_committee || (modalMemberData.committees && modalMemberData.committees.length)" class="mt-8 grid grid-cols-1 sm:grid-cols-1 gap-4">
              
              <!-- Main Committee -->
              <div v-if="modalMemberData.main_committee" class="bg-yellow-50 p-4 rounded-xl border-l-4 border-yellow-400">
                <p class="text-[10px] font-black uppercase text-yellow-700">Main Committee</p>
                <h4 class="text-sm font-bold text-gray-900 leading-snug">{{ modalMemberData.main_committee }}</h4>
              </div>

              <!-- Other Committees -->
              <div v-for="committee in modalMemberData.committees" :key="committee.name" 
                   class="bg-gray-50 p-4 rounded-xl border-l-4 border-green-800">
                <p class="text-[10px] font-black uppercase text-green-700">{{ committee.pivot?.role }}</p>
                <h4 class="text-sm font-bold text-gray-900 leading-snug">{{ committee.name }}</h4>
              </div>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</transition>

<transition name="modal-fade">
  <div v-if="showEmployeeModal" class="fixed inset-0 flex items-center justify-center p-4 md:p-6 bg-green-950/40 backdrop-blur-md z-[100]" @click.self="closeEmployeeBio">
    <div class="bg-white w-full max-w-3xl rounded-3xl shadow-2xl border border-white/20 relative flex flex-col overflow-hidden animate-modal-in">

      <!-- Close X button -->
      <button @click="closeEmployeeBio" 
              class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold z-10">
        &times;
      </button>

      <!-- Content -->
      <div class="p-8 md:p-16 text-center">

        <!-- Profile Image as Bigger Box -->
        <div class="w-64 h-80 md:w-96 md:h-[28rem] mx-auto rounded-2xl overflow-hidden border-4 border-yellow-400 mb-8 shadow-xl">
          <img :src="imageUrl(modalEmployeeData.image)" :alt="modalEmployeeData.name" class="w-full h-full object-cover object-[50%_10%]">
        </div>

        <!-- Name -->
        <h2 class="text-3xl md:text-5xl font-black text-green-900 leading-tight mb-4">{{ modalEmployeeData.name }}</h2>

        <!-- Division -->
        <p v-if="modalEmployeeData.division" class="text-lg md:text-2xl font-bold text-yellow-600 uppercase tracking-widest">
          {{ modalEmployeeData.division }}
        </p>
      </div>

    </div>
  </div>
</transition>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import Navbar from "@/components/Home/Navbar.vue";
import Footer from "@/components/Home/Footer.vue";
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  presidingOfficer: { type: Object, default: null },
  councilMembers: { type: Array, default: () => [] },
  employees: { type: Array, default: () => [] },
  organizationalChart: { type: String, default: null }, // Added prop from controller
});

const filteredCouncil = computed(() => props.councilMembers.filter(m => m.type === 'official'));

const secretaryGroup = computed(() => {
  return props.employees.filter(e => 
    e.division?.toLowerCase().includes('secretary') || 
    e.position?.toLowerCase() === 'secretary'
  );
});

const divisionGroups = computed(() => {
  const others = props.employees.filter(e => 
    !e.division?.toLowerCase().includes('secretary') && 
    e.position?.toLowerCase() !== 'secretary'
  );

  return others.reduce((acc, emp) => {
    const div = emp.division || 'General Staff';
    if (!acc[div]) acc[div] = [];
    acc[div].push(emp);
    return acc;
  }, {});
});

const imageUrl = (path) => path ? `/storage/${path}` : '/images/placeholder-male.jpg';

const showBioModal = ref(false);
const modalMemberData = ref({});

const openBio = (member) => {
  modalMemberData.value = { ...member };
  showBioModal.value = true;
  document.body.style.overflow = 'hidden';
};

const closeBio = () => {
  showBioModal.value = false;
  document.body.style.overflow = 'auto';
  setTimeout(() => modalMemberData.value = {}, 300);
};

const showEmployeeModal = ref(false);
const modalEmployeeData = ref({});

// Open employee modal
const openEmployee = (employee) => {
  modalEmployeeData.value = { ...employee };
  showEmployeeModal.value = true;
  document.body.style.overflow = 'hidden'; // Prevent background scroll
};

// Close employee modal
const closeEmployeeBio = () => {
  showEmployeeModal.value = false;
  document.body.style.overflow = 'auto'; // Restore scrolling
  setTimeout(() => modalEmployeeData.value = {}, 300); // Clear data after animation
};

const getDefaultText = (member) => {
    if (member.type === 'official') return 'No detailed biography provided yet.';
    return `${member.name} serves as the ${member.position} within the Sangguniang Bayan office. They are part of the dedicated workforce ensuring efficient public service delivery in Concepcion, Tarlac.`;
}
</script>

<style scoped>
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.4s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }

.animate-modal-in { animation: modalIn 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) forwards; }

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.9) translateY(30px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

@media (max-width: 480px) {
    .xs\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
</style>