<template>
  <div class="bg-white min-h-screen">
    <Navbar />

    <!-- HERO SECTION -->
    <section class="pt-28 pb-20 px-4 relative overflow-hidden bg-gray-50">
      <!-- Gradient decorations -->
      <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-yellow-400 to-transparent rounded-full blur-3xl opacity-20"></div>
      <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-green-900 to-transparent rounded-full blur-3xl opacity-10"></div>

      <div class="max-w-7xl mx-auto relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        
        <!-- LEFT SIDE -->
        <div class="space-y-6">
          <span class="text-xs font-bold uppercase tracking-widest text-green-900 bg-green-100 px-4 py-2 rounded-full shadow-sm inline-block">
            Official Website
          </span>

          <h1 class="text-6xl font-black text-gray-900 leading-tight">
            Sangguniang Bayan <span class="text-green-800">«Bayan»</span>
          </h1>

          <p class="text-xl text-gray-600 font-medium">
            Transparency • Integrity • Public Service
          </p>

          <div class="flex flex-col sm:flex-row gap-4 pt-6">
            <button class="px-8 py-3 bg-green-800 text-white font-bold rounded-lg hover:bg-green-900 transition shadow-lg">
              Explore
            </button>
            <button class="px-8 py-3 border-2 border-green-900 text-green-900 font-bold rounded-lg hover:bg-gray-50">
              Learn More
            </button>
          </div>

          <div class="grid grid-cols-3 gap-6 pt-10 border-t border-gray-200 mt-10">
            <div>
              <p class="text-4xl font-black text-green-900">120+</p>
              <p class="text-sm text-gray-600">Ordinances</p>
            </div>
            <div>
              <p class="text-4xl font-black text-green-900">8</p>
              <p class="text-sm text-gray-600">Council Members</p>
            </div>
            <div>
              <p class="text-4xl font-black text-green-900">24</p>
              <p class="text-sm text-gray-600">Annual Sessions</p>
            </div>
          </div>
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

    <!-- MESSAGE + ABOUT SECTION -->
    <section class="py-20 px-4">
      <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-8">

        <div class="lg:col-span-2 space-y-8">
          
          <div class="p-10 bg-gradient-to-br from-green-900 to-green-800 text-white rounded-xl shadow-2xl space-y-6">
            <div class="text-6xl text-yellow-400 font-serif leading-none">“</div>
            
            <span class="text-xs font-bold uppercase tracking-widest text-yellow-400">Leadership</span>
            <h2 class="text-3xl font-black mt-1">Message From Our Presiding Officer</h2>
            
            <p class="text-base text-white/80 italic leading-relaxed">
              "Yone tlaen nat al ahe te erne haea eagi waei merrite eaeaeitun thi enmne thowarwelaee ttaat ttae ttaai thaiik haathalaeneud dae btenacri eler salelotelee naeetae pae eeaw." 
              </p>

            <button class="px-8 py-3 bg-yellow-400 text-green-900 font-bold rounded-lg hover:bg-white transition shadow-lg">
              Read Full Message →
            </button>

            <div class="text-6xl text-yellow-400 font-serif leading-none text-right">”</div>
          </div>

          
        </div>

        <div class="lg:col-span-1 xl:col-span-2 space-y-8">
          
          <div class="p-6 border border-gray-100 rounded-xl shadow-md space-y-4 bg-white">
            <span class="text-xs font-bold uppercase tracking-widest text-green-800">About Us</span>
            <h3 class="text-2xl font-black text-gray-900">Sangguniang Bayan</h3>

            <div class="flex gap-4">
              <div class="w-1/2 space-y-2">
                <p class="text-gray-700 text-sm leading-relaxed">
                  The Sangguniang Bayan of Concepcion serves as the legislative body. Government's datal taeeuaet aebnered tb teredq qweas. Rteaeis wleaeae ttaet tyaea prateasramas. nteaee resepuael.
                </p>
              </div>
              <div class="w-1/2 h-28 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                 <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg/300px-Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg" alt="Municipal Hall" class="w-full h-full object-cover">
              </div>
            </div>
          </div>

          <div class="p-6 border border-gray-100 rounded-xl shadow-md space-y-6 bg-white">
            <h3 class="text-2xl font-extrabold text-gray-900 text-center">Mission & Vision</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div 
                v-for="item in missionVision" 
                :key="item.title" 
                class="p-4 border rounded-lg shadow-sm hover:shadow-md transition hover:bg-green-50"
              >
                <div class="text-4xl mb-2 text-green-800 text-center">{{ item.icon }}</div>
                <h4 class="text-lg font-bold text-gray-900 text-center">{{ item.title }}</h4>
                <p class="text-sm text-gray-600 mt-2 text-center">{{ item.desc }}</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- KEY SECTIONS -->
    <section class="py-20 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 space-y-4">
          <span class="text-xs font-bold uppercase tracking-widest text-green-900">Browse</span>
          <h2 class="text-5xl font-black">Key Sections</h2>
          <div class="h-1 w-24 bg-yellow-400 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="feature in features"
            :key="feature.title"
            class="bg-white rounded-xl p-8 border-t-4 border-yellow-400 shadow-lg hover:shadow-xl group transition"
          >
            <div class="text-5xl text-green-900 group-hover:scale-110 transition-transform">
              {{ feature.icon }}
            </div>
            <h3 class="text-xl font-extrabold mt-4">{{ feature.title }}</h3>
            <p class="text-gray-600 text-sm mt-2">{{ feature.desc }}</p>

            <a class="mt-6 inline-flex items-center text-green-900 font-bold group-hover:text-yellow-500">
              View Details → 
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- PROGRAMS -->
    <section class="py-20 px-4">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <span class="text-xs font-bold uppercase tracking-widest text-green-900">Initiatives</span>
          <h2 class="text-5xl font-black">Programs & Initiatives</h2>
          <div class="h-1 w-24 bg-yellow-400 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            v-for="program in programs"
            :key="program.title"
            class="bg-white rounded-xl p-8 border-2 border-green-900/10 hover:border-yellow-400 shadow-md transition group"
          >
            <div class="text-6xl text-yellow-400 mb-4 group-hover:scale-110 transition-transform">
              {{ program.icon }}
            </div>

            <h3 class="text-xl font-bold">{{ program.title }}</h3>
            <p class="text-gray-600 text-sm mt-2">{{ program.desc }}</p>

            <a class="text-green-900 font-semibold mt-4 inline-block group-hover:text-yellow-400">
              Learn More
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- GALLERY SECTION -->
    <section class="py-20 px-4 bg-gray-50">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <span class="text-xs font-bold uppercase tracking-widest text-green-900">Gallery</span>
          <h2 class="text-5xl font-black">Community & Events</h2>
          <div class="h-1 w-24 bg-yellow-400 mx-auto"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div
            v-for="(img, index) in galleryImages"
            :key="index"
            :class="[
              'rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition hover:scale-[1.02] cursor-pointer',
              { 'md:col-span-2 md:row-span-2 h-96': index === 0, 'aspect-square h-48 md:h-auto': index !== 0 }
            ]"
          >
            <img 
              :src="img.src" 
              :alt="img.alt" 
              class="w-full h-full object-cover"
            />
          </div>
        </div>

      </div>
    </section>

    <!-- CONTACT -->
    <section class="py-20 px-4">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        <div class="space-y-10">
          <div>
            <span class="text-xs font-bold uppercase tracking-widest text-green-900">Contact</span>
            <h2 class="text-4xl font-black mt-2">Get In Touch</h2>
            <p class="text-gray-600 mt-2">
              We are here to serve. Reach out for inquiries and assistance.
            </p>
          </div>

          <div class="space-y-6">
            <div
              v-for="contact in contactDetails"
              :key="contact.title"
              class="flex gap-4 items-start"
            >
              <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center shadow">
                <span class="text-xl text-green-900">{{ contact.icon }}</span>
              </div>
              <div>
                <p class="font-bold text-lg">{{ contact.title }}</p>
                <p class="text-gray-600 text-sm">{{ contact.detail }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="rounded-2xl overflow-hidden shadow-xl h-96 bg-gray-100 flex items-center justify-center border-4 border-gray-200">
          <div class="text-center text-gray-600">
            <p class="text-5xl mb-2">🗺️</p>
            <p class="font-bold">Municipal Hall Location</p>
            <p class="text-sm">Embed Map Placeholder</p>
          </div>
        </div>

      </div>
    </section>

    <Footer />
  </div>
</template>

<script setup>
import Navbar from "@/components/Home/Navbar.vue"
import Footer from "@/components/Home/Footer.vue"

const features = [
  { icon: '📜', title: 'Ordinances', desc: 'Browse all approved municipal ordinances.' },
  { icon: '✍️', title: 'Resolutions', desc: 'Access past resolutions and legislative decisions.' },
  { icon: '👥', title: 'Members', desc: 'Meet our elected council members.' },
  { icon: '📅', title: 'Sessions', desc: 'View schedules and minutes of past sessions.' }
]

const programs = [
  { icon: '🌳', title: 'Green Concepcion', desc: 'Environmental programs and sustainability efforts.' },
  { icon: '💡', title: 'Transparency Portal', desc: 'Access public records, budget, and governance data.' },
  { icon: '🤝', title: 'Community Engagement', desc: 'Citizen participation through town hall consultations.' }
]

const missionVision = [
  {
    icon: '🎯',
    title: 'Mission',
    desc: 'To promote transparency, integrity, and public service while ensuring inclusive development in Concepcion, Tarlac.'
  },
  {
    icon: '🌟',
    title: 'Vision',
    desc: 'To be a model legislative body that empowers citizens, enhances governance, and fosters sustainable community growth.'
  }
];


const galleryImages = [
  { 
    src: "https://images.pexels.com/photos/3184300/pexels-photo-3184300.jpeg", 
    alt: "Community event 1" 
  },
  { 
    src: "https://images.pexels.com/photos/3184312/pexels-photo-3184312.jpeg", 
    alt: "Community event 2" 
  },
  { 
    src: "https://images.pexels.com/photos/3184396/pexels-photo-3184396.jpeg", 
    alt: "Community event 3" 
  },
  { 
    src: "https://images.pexels.com/photos/3184436/pexels-photo-3184436.jpeg", 
    alt: "Community event 4" 
  },
  { 
    src: "https://images.pexels.com/photos/3184454/pexels-photo-3184454.jpeg", 
    alt: "Community event 5" 
  },
  { 
    src: "https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg", 
    alt: "Community event 6" 
  }
];


const contactDetails = [
  { icon: '📍', title: 'Address', detail: 'Municipal Hall, Concepcion, Tarlac' },
  { icon: '📞', title: 'Phone', detail: '(045) 923-4567' },
  { icon: '📧', title: 'Email', detail: 'sangguniangbayan@concepcion.gov.ph' }
]
</script>
