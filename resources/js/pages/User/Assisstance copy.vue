<template>
  <Head title="Public Assistance Programs" />
  <div class="bg-slate-50 min-h-screen">
    <Navbar />

    <section class="pt-32 pb-20 bg-gradient-to-br from-green-900 to-green-800 relative overflow-hidden text-white">
      <div class="absolute top-0 right-0 w-80 h-80 bg-yellow-400/10 rounded-full blur-[100px]"></div>
      <div class="max-w-7xl mx-auto px-6 relative z-10">
        <nav class="flex mb-8 text-sm font-medium text-green-200/80 gap-2">
          <a href="/citizens-charter" class="hover:text-yellow-400 font-bold transition-colors">Citizen's Charter</a>
          <span>/</span>
          <span class="text-white">Public Assistance</span>
        </nav>
        <h1 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
          Public Assistance <br /><span class="text-yellow-400">& Welfare Programs</span>
        </h1>
        <p class="text-lg md:text-xl text-green-50/80 max-w-2xl leading-relaxed">
          We are committed to providing accessible social services. Explore our educational, medical, and emergency support programs designed to uplift every member of our community.
        </p>
      </div>
    </section>

    <section class="py-20 px-6">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 -mt-32 relative z-20">
          <div v-for="program in mainPrograms" :key="program.title" 
               class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 hover:scale-[1.02] transition-all duration-300">
            <div :class="[`w-14 h-14 rounded-2xl flex items-center justify-center mb-6 text-white shadow-lg`, program.bgClass]">
              <component :is="program.icon" class="w-8 h-8" />
            </div>
            <h3 class="text-2xl font-bold text-green-900 mb-4">{{ program.title }}</h3>
            <p class="text-gray-500 mb-6 leading-relaxed text-sm">{{ program.description }}</p>
            <ul class="space-y-3">
              <li v-for="benefit in program.benefits" :key="benefit" class="flex items-center gap-2 text-sm font-bold text-gray-700">
                <CheckCircleIcon class="w-5 h-5 text-green-600 shrink-0" />
                {{ benefit }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="py-16 px-6 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-black text-green-900 uppercase tracking-tight">Application Process</h2>
          <div class="w-20 h-2 bg-yellow-400 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12">
          <div v-for="(step, index) in steps" :key="index" class="relative text-center group">
            <div class="w-16 h-16 bg-green-900 text-yellow-400 rounded-2xl flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-green-900/20 group-hover:rotate-6 transition-transform">
              {{ index + 1 }}
            </div>
            <h4 class="font-bold text-lg text-green-900 mb-2">{{ step.title }}</h4>
            <p class="text-sm text-gray-500 leading-relaxed">{{ step.desc }}</p>
            <div v-if="index < 3" class="hidden md:block absolute top-8 left-[calc(50%+40px)] w-[calc(100%-80px)] h-[2px] bg-gray-100"></div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-24 px-6 bg-slate-50">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
          <h2 class="text-3xl md:text-4xl font-black text-green-900 uppercase leading-tight mb-6">
            Basic <span class="text-yellow-600">Requirements</span> <br />for Assistance
          </h2>
          <p class="text-gray-600 mb-10 text-lg">Ensure you have the following documents ready to expedite your application process during People's Day or walk-in visits.</p>
          
          <div class="space-y-4">
            <div v-for="req in requirements" :key="req.title" class="flex items-start gap-4 p-5 bg-white rounded-3xl shadow-sm border border-gray-100 hover:border-green-200 transition-colors">
              <div class="p-3 bg-yellow-100 rounded-xl shrink-0">
                <DocumentIcon class="w-6 h-6 text-yellow-700" />
              </div>
              <div>
                <p class="font-bold text-green-900">{{ req.title }}</p>
                <p class="text-xs text-gray-400 font-medium mt-1">{{ req.note }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-green-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl">
          <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-yellow-400 opacity-10 rounded-full"></div>
          <h3 class="text-2xl font-black mb-6">Need Immediate Assistance?</h3>
          <p class="text-green-100 mb-8 leading-relaxed">For emergency cases such as urgent medical procedures or burial assistance, please visit the Social Welfare Office directly at the Municipal Hall.</p>
          
          <div class="space-y-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                <PhoneIcon class="w-6 h-6 text-yellow-400" />
              </div>
              <div>
                <p class="text-[10px] uppercase tracking-widest text-green-300 font-bold">Hotline</p>
                <p class="font-bold text-lg">(044) 123-4567</p>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                <MapPinIcon class="w-6 h-6 text-yellow-400" />
              </div>
              <div>
                <p class="text-[10px] uppercase tracking-widest text-green-300 font-bold">Office Location</p>
                <p class="font-bold text-lg">1st Floor, Municipal Hall Building</p>
              </div>
            </div>
          </div>

          <button class="w-full mt-10 py-4 bg-yellow-400 text-green-950 font-black rounded-2xl uppercase tracking-widest hover:bg-white transition-all shadow-xl shadow-yellow-950/20 active:scale-95">
            Contact Support Office
          </button>
        </div>
      </div>
    </section>

    <section class="py-20 px-6 bg-white">
      <div class="max-w-3xl mx-auto">
        <h2 class="text-center text-3xl font-black text-green-900 mb-12 uppercase tracking-wide">Frequently Asked Questions</h2>
        <div class="space-y-4">
          <details v-for="faq in faqs" :key="faq.q" class="group border border-gray-100 rounded-3xl overflow-hidden transition-all">
            <summary class="flex justify-between items-center p-6 cursor-pointer bg-gray-50 group-open:bg-green-900 group-open:text-white list-none">
              <span class="font-bold pr-4">{{ faq.q }}</span>
              <PlusIcon class="w-5 h-5 group-open:rotate-45 transition-transform duration-300 shrink-0" />
            </summary>
            <div class="p-6 text-gray-600 leading-relaxed border-t border-gray-50 text-sm">
              {{ faq.a }}
            </div>
          </details>
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
  AcademicCapIcon, 
  HeartIcon, 
  ScaleIcon, 
  CheckCircleIcon,
  DocumentIcon,
  PhoneIcon,
  MapPinIcon,
  PlusIcon
} from '@heroicons/vue/24/outline';

const mainPrograms = [
  {
    title: 'Educational Support',
    description: 'Scholarships and financial aid for deserving students in public and private institutions.',
    bgClass: 'bg-blue-600',
    icon: AcademicCapIcon,
    benefits: ['Tuition Subsidies', 'Book Allowances', 'Merit Incentives']
  },
  {
    title: 'Medical Assistance',
    description: 'Support for hospital bills, laboratory fees, and essential medicine for indigent patients.',
    bgClass: 'bg-red-500',
    icon: HeartIcon,
    benefits: ['Hospital Bill Support', 'Medicine Vouchers', 'Diagnostic Aid']
  },
  {
    title: 'Legal Consultation',
    description: 'Free legal advice and notary services for municipal residents seeking documentation.',
    bgClass: 'bg-green-700',
    icon: ScaleIcon,
    benefits: ['Affidavit Preparation', 'Legal Counseling', 'Notary Services']
  }
];

const steps = [
  { title: 'Inquiry', desc: 'Visit our office or contact the hotline to check program availability.' },
  { title: 'Documentation', desc: 'Gather all required documents based on the program checklist.' },
  { title: 'Evaluation', desc: 'Submit files to the Social Welfare officer for verification and interview.' },
  { title: 'Granting', desc: 'Once approved, assistance will be released via check or voucher.' }
];

const requirements = [
  { title: 'Barangay Indigency', note: 'Must be issued within the last 3 months for the specific applicant.' },
  { title: 'Valid Government ID', note: 'Photocopy of any ID showing municipal residency (Voters, PhilSys, etc).' },
  { title: 'Specific Proof of Need', note: 'Medical Certificate, School Enrollment Form, or Burial Certificate.' }
];

const faqs = [
  { q: 'Who is eligible for assistance?', a: 'Any bona fide resident of the municipality who falls under the "indigent" category as defined by MSWDO guidelines and local social welfare standards.' },
  { q: 'When is People’s Day?', a: 'People’s Day is held every Tuesday, where the Sangguniang Bayan members personally cater to citizen requests and provide direct legislative assistance.' },
  { q: 'How long does the processing take?', a: 'Standard applications typically take 3-5 working days. Emergency medical cases are prioritized and can often be processed within 24 hours.' }
];
</script>

<style scoped>
/* Removes the default arrow from <details> in some browsers */
details summary::-webkit-details-marker { display: none; }
summary { list-style: none; }

/* Custom smooth scroll spacing */
section { scroll-margin-top: 100px; }
</style>