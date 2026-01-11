<script setup lang="ts">
    import { ref, computed } from 'vue';
    import Navbar from "@/components/Home/Navbar.vue";
    import Footer from "@/components/Home/Footer.vue";
    import { 
      Calendar, 
      ArrowRight, 
      Search, 
      Newspaper, 
      Megaphone, 
      Clock
    } from 'lucide-vue-next';
    
    // 1. Types & Interfaces
    interface Announcement {
      id: number;
      title: string;
      category: 'Public Hearing' | 'Urgent Advisory' | 'Community Event';
      summary: string;
      content: string;
      date: string;
      image: string;
      isFeatured?: boolean;
    }
    
    // 2. Mock Data (Sessions Removed)
    const announcements = ref<Announcement[]>([
      {
        id: 1,
        title: "Typhoon Pepito: Emergency Preparedness & Evacuation Protocol",
        category: "Urgent Advisory",
        summary: "Mandatory evacuation notice for low-lying areas and emergency contact numbers for all barangays in Concepcion. Please stay indoors and monitor local radio stations.",
        content: "Detailed content here...",
        date: "2025-12-24",
        image: "https://images.pexels.com/photos/1118873/pexels-photo-1118873.jpeg",
        isFeatured: true
      },
      {
        id: 2,
        title: "Public Consultation on New Business Tax Ordinance",
        category: "Public Hearing",
        summary: "Local business owners are invited to discuss the proposed amendments to the local revenue code regarding commercial permits and regulatory fees.",
        content: "Detailed content here...",
        date: "2025-12-20",
        image: "https://images.pexels.com/photos/3184300/pexels-photo-3184300.jpeg",
      },
      {
        id: 3,
        title: "Annual Community Christmas Tree Lighting Ceremony",
        category: "Community Event",
        summary: "Join us at the Municipal Plaza for our annual tradition. Special performances by local artists and a grand fireworks display for the family.",
        content: "Detailed content here...",
        date: "2025-12-15",
        image: "https://images.pexels.com/photos/1303081/pexels-photo-1303081.jpeg",
      },
      {
        id: 4,
        title: "New Traffic Route Implementation for Poblacion Area",
        category: "Urgent Advisory",
        summary: "Effective immediately: New one-way traffic scheme for the downtown area to ease holiday congestion and improve pedestrian safety.",
        content: "Detailed content here...",
        date: "2025-12-10",
        image: "https://images.pexels.com/photos/1034662/pexels-photo-1034662.jpeg",
      },
      {
        id: 5,
        title: "Town Hall Meeting: Sustainable Green Concepcion",
        category: "Public Hearing",
        summary: "A community discussion on environmental programs and upcoming sustainability efforts for the year 2026.",
        content: "Detailed content here...",
        date: "2025-12-05",
        image: "https://images.pexels.com/photos/3184396/pexels-photo-3184396.jpeg",
      }
    ]);
    
    // 3. Logic: Filtering
    const categories = ['All', 'Public Hearing', 'Urgent Advisory', 'Community Event'];
    const selectedCategory = ref('All');
    const searchQuery = ref('');
    
    const filteredAnnouncements = computed(() => {
      return announcements.value.filter(item => {
        const matchesCategory = selectedCategory.value === 'All' || item.category === selectedCategory.value;
        const matchesSearch = item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                              item.summary.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesCategory && matchesSearch && !item.isFeatured;
      });
    });
    
    const featuredPost = computed(() => announcements.value.find(a => a.isFeatured));
    
    const formatDate = (dateStr: string) => {
      return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    };
    
    const getCategoryColor = (cat: string) => {
      const colors: Record<string, string> = {
        'Urgent Advisory': 'bg-red-100 text-red-700 border-red-200',
        'Public Hearing': 'bg-blue-100 text-blue-700 border-blue-200',
        'Community Event': 'bg-yellow-100 text-yellow-800 border-yellow-200'
      };
      return colors[cat] || 'bg-gray-100 text-gray-700';
    };
    </script>
    
    <template>
      <div class="bg-white min-h-screen">
        <Navbar />
    
        <section class="pt-32 pb-16 px-4 relative overflow-hidden bg-slate-50">
          <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-yellow-400 to-transparent rounded-full blur-3xl opacity-10"></div>
          <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-green-900 to-transparent rounded-full blur-3xl opacity-5"></div>
    
          <div class="max-w-7xl mx-auto relative z-10 text-center space-y-4">
            <span class="text-xs font-bold uppercase tracking-[0.3em] text-green-800 bg-green-100 px-4 py-2 rounded-full inline-block">
              Official Updates
            </span>
            <h1 class="text-5xl md:text-6xl font-black text-gray-900">
              Announcements & <span class="text-green-800">News</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
              Stay informed with the latest public advisories, hearings, and community news from the Sangguniang Bayan of Concepcion.
            </p>
          </div>
        </section>
    
        <main class="max-w-7xl mx-auto px-4 py-12">
          
          <div v-if="featuredPost" class="mb-16">
            <div class="group relative bg-white rounded-3xl overflow-hidden shadow-2xl border border-gray-100 flex flex-col lg:flex-row transition-all hover:shadow-emerald-900/10">
              <div class="lg:w-1/2 h-80 lg:h-auto overflow-hidden">
                <img :src="featuredPost.image" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" />
              </div>
              <div class="lg:w-1/2 p-8 md:p-12 flex flex-col justify-center space-y-6">
                <div class="flex items-center gap-3">
                  <span class="bg-red-600 text-white px-3 py-1 rounded text-xs font-bold uppercase tracking-wider shadow-sm">Hot Update</span>
                  <span class="text-sm text-gray-500 font-medium flex items-center gap-1">
                    <Clock class="w-4 h-4" /> {{ formatDate(featuredPost.date) }}
                  </span>
                </div>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                  {{ featuredPost.title }}
                </h2>
                <p class="text-gray-600 leading-relaxed text-lg">
                  {{ featuredPost.summary }}
                </p>
                <div>
                  <button class="inline-flex items-center gap-2 px-8 py-3 bg-green-900 text-white font-bold rounded-xl hover:bg-green-800 transition shadow-lg">
                    Read Full Advisory <ArrowRight class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
    
          <div class="sticky top-24 z-30 bg-white/80 backdrop-blur-md py-6 border-b border-gray-100 mb-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
              <div class="flex flex-wrap justify-center gap-2">
                <button 
                  v-for="cat in categories" 
                  :key="cat"
                  @click="selectedCategory = cat"
                  :class="[
                    'px-5 py-2 rounded-full text-sm font-bold transition-all border',
                    selectedCategory === cat 
                      ? 'bg-green-900 text-white border-green-900 shadow-md' 
                      : 'bg-white text-gray-600 border-gray-200 hover:border-green-800 hover:text-green-800'
                  ]"
                >
                  {{ cat }}
                </button>
              </div>
              
              <div class="relative w-full md:w-80">
                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Search news..." 
                  class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-800 focus:border-transparent outline-none transition"
                />
              </div>
            </div>
          </div>
    
          <div v-if="filteredAnnouncements.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <article 
              v-for="item in filteredAnnouncements" 
              :key="item.id"
              class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col"
            >
              <div class="relative h-56 overflow-hidden rounded-t-2xl">
                <img :src="item.image" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" />
                <div class="absolute top-4 left-4">
                  <span :class="['px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border shadow-sm', getCategoryColor(item.category)]">
                    {{ item.category }}
                  </span>
                </div>
              </div>
              
              <div class="p-6 flex flex-col flex-grow space-y-4">
                <div class="flex items-center text-xs text-gray-400 font-bold uppercase tracking-widest">
                  <Calendar class="w-3.5 h-3.5 mr-2 text-yellow-500" /> {{ formatDate(item.date) }}
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 group-hover:text-green-800 transition">
                  {{ item.title }}
                </h3>
                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                  {{ item.summary }}
                </p>
                <div class="pt-4 mt-auto">
                  <button class="text-green-900 font-bold text-sm inline-flex items-center group/btn">
                    Read More 
                    <ArrowRight class="ml-2 w-4 h-4 transition-transform group-hover/btn:translate-x-1" />
                  </button>
                </div>
              </div>
            </article>
          </div>
    
          <div v-else class="py-20 text-center space-y-4">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-4">
              <Newspaper class="w-10 h-10 text-gray-300" />
            </div>
            <h3 class="text-2xl font-bold text-gray-900">No news items found</h3>
            <p class="text-gray-500">Try adjusting your filters or search keywords.</p>
          </div>
    
          <section class="mt-24 rounded-3xl bg-green-900 p-8 md:p-16 relative overflow-hidden text-center text-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-400/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
            <div class="relative z-10 space-y-6 max-w-2xl mx-auto">
              <Megaphone class="w-12 h-12 text-yellow-400 mx-auto" />
              <h2 class="text-3xl md:text-4xl font-black text-white">Never Miss an Update</h2>
              <p class="text-green-100/80">
                Sign up for our newsletter to receive the latest public advisories and hearing notices directly.
              </p>
              <form class="flex flex-col sm:flex-row gap-3 pt-4">
                <input 
                  type="email" 
                  placeholder="Enter your email" 
                  class="flex-grow px-6 py-4 rounded-xl text-gray-900 outline-none focus:ring-4 focus:ring-yellow-400/30"
                />
                <button class="px-10 py-4 bg-yellow-400 text-green-900 font-bold rounded-xl hover:bg-white transition shadow-lg">
                  Join List
                </button>
              </form>
            </div>
          </section>
    
        </main>
    
        <Footer />
      </div>
    </template>
    
    <style scoped>
    .line-clamp-3 {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    </style>