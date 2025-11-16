<template>
  <div class="sb-sangguniang-bayan">
    <Navbar />

    <section class="sb-hero">
      <div class="sb-hero-overlay"></div>
      <div class="sb-hero-content">
        <h2>Sangguniang Bayan Members</h2>
        <p class="sb-motto">Meet the Legislative Leaders of Concepcion</p>
        <div class="sb-hero-accent"></div>
      </div>
    </section>

    <section class="sb-presiding-section">
      <div class="sb-section-header">
        <h3 class="animated-header">Presiding Officer</h3>
      </div>
      <div class="sb-presiding-container">
        <div class="sb-featured-card card-shadow-hover" @click="showMemberInfo(presidingOfficer)">
          <div class="sb-image-wrapper sb-featured-image-wrapper">
            <img :src="presidingOfficer.photo" :alt="presidingOfficer.name" loading="lazy" />
          </div>
          <h4 class="sb-card-title">{{ presidingOfficer.name }}</h4>
          <p class="sb-card-position">{{ presidingOfficer.position }}</p>
          <button class="sb-view-btn">View Profile</button>
        </div>
      </div>
    </section>

    <section class="sb-councilors-section">
      <div class="sb-section-header">
        <h3 class="animated-header">Municipal Councilors</h3>
      </div>
      <div class="sb-council-grid">
        <div
          v-for="(member, index) in councilors"
          :key="index"
          class="sb-council-card card-shadow-hover"
          @click="showMemberInfo(member)"
        >
          <div class="sb-image-wrapper sb-councilor-image-wrapper">
            <img :src="member.photo" :alt="member.name" loading="lazy" />
          </div>
          <h4 class="sb-card-title">{{ member.name }}</h4>
          <p class="sb-card-position">{{ member.position }}</p>
          <button class="sb-view-btn">View Profile</button>
        </div>
      </div>
    </section>

    <section class="sb-secretary-section">
      <div class="sb-section-header">
        <h3 class="animated-header">SB Secretary</h3>
      </div>
      <div class="sb-presiding-container">
        <div class="sb-featured-card card-shadow-hover" @click="showMemberInfo(secretary)">
          <div class="sb-image-wrapper sb-featured-image-wrapper">
            <img :src="secretary.photo" :alt="secretary.name" loading="lazy" />
          </div>
          <h4 class="sb-card-title">{{ secretary.name }}</h4>
          <p class="sb-card-position">{{ secretary.position }}</p>
          <button class="sb-view-btn">View Profile</button>
        </div>
      </div>
    </section>

    <transition 
      name="sb-modal-fade"
      @enter="onModalEnter"
      @leave="onModalLeave"
    >
      <div v-if="selectedMember" class="sb-modal-overlay" @click.self="closeModal">
        <div class="sb-modal-content">

          <!-- Close Button -->
          <button class="sb-close-btn" @click="closeModal" aria-label="Close modal">×</button>

          <!-- Top Section -->
          <div class="sb-modal-main-info">
            <div class="sb-modal-image-container">
              <img :src="selectedMember.photo" :alt="selectedMember.name" class="sb-modal-image" />
            </div>

            <div class="sb-modal-header-text">
              <h2>{{ selectedMember.name }}</h2>
              <p class="sb-modal-position">{{ selectedMember.position }}</p>
            </div>
          </div>

          <!-- Body: ONE COLUMN -->
          <div class="sb-modal-body">

            <!-- BIOGRAPHY -->
            <div class="sb-modal-section">
              <h3><i class="fas fa-info-circle"></i> Biography</h3>
              <p class="sb-bio" v-if="selectedMember.bio">{{ selectedMember.bio }}</p>
              <p class="sb-bio" v-else>No biography available.</p>
            </div>

            <!-- COMMITTEES -->
            <div 
              class="sb-modal-section"
              v-if="selectedMember.committees && selectedMember.committees.length > 0"
            >
              <h3><i class="fas fa-gavel"></i> Committee Assignments</h3>

              <ul class="sb-committees-list">
                <li v-for="(committee, i) in selectedMember.committees" :key="i">
                  <span class="sb-committee-icon"><i class="far fa-check-circle"></i></span>
                  {{ committee }}
                </li>
              </ul>
            </div>

          </div>

        </div>
      </div>
    </transition>

    <FooterSection />
  </div>
</template>

<script>
import Navbar from "@/components/Home/Navbar.vue";
import FooterSection from "@/components/Home/Footer.vue";

export default {
  name: "SangguniangBayan",
  components: { Navbar, FooterSection },
  data() {
    return {
      selectedMember: null,
      presidingOfficer: {
        name: "Hon. [Vice Mayor's Name]",
        position: "Presiding Officer",
        badgePosition: "Presiding Officer",
        photo: "https://upload.wikimedia.org/wikipedia/commons/a/a3/Vice_Mayor_Example.jpg",
        committees: [
          "Committee on Good Governance",
          "Committee on Public Safety",
          "Committee on Local Legislation",
        ],
        bio: "The Presiding Officer leads the Sangguniang Bayan, ensuring effective legislation and transparency in all municipal matters and is the second-highest elected official in the local government unit. He or she is responsible for signing all approved ordinances, resolutions, and measures.",
      },
      secretary: {
        name: "Ms. [Secretary Name]",
        position: "SB Secretary",
        badgePosition: "Secretary",
        photo: "https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=800&q=60",
        committees: [], 
        bio: "Responsible for maintaining legislative records, handling official documents, and supporting council operations efficiently and impartially. The Secretary prepares minutes of meetings and attests to the authenticity of resolutions and ordinances.",
      },
      councilors: [
        {
          name: "Hon. Juan Dela Cruz",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1595151228-14d9def656e4?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Education", "Committee on Health"],
          bio: "Championing educational reforms and public health initiatives to uplift the lives of every constituent. Dedicated to securing quality learning environments for the youth of Concepcion.",
        },
        {
          name: "Hon. Maria Santos",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-155415122814d9def654761-15a19d654956?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Women and Family", "Committee on Social Welfare"],
          bio: "Advocating for gender equality and providing robust social welfare programs for vulnerable sectors. A strong voice for maternal and child health care.",
        },
        {
          name: "Hon. Roberto Garcia",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Finance", "Committee on Infrastructure"],
          bio: "A dedicated public servant with a focus on fiscal responsibility and fast-tracking development projects across the municipality. He ensures public funds are utilized efficiently and transparently.",
        },
        {
          name: "Hon. Angela Ramos",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Youth and Sports", "Committee on Tourism"],
          bio: "Focused on empowering the youth through sports and cultural activities, and promoting local tourism. She actively organizes inter-barangay sports leagues and local festivals.",
        },
        {
          name: "Hon. Jose Manalo",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1599566150163-29194ca60f91?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Environment", "Committee on Agriculture"],
          bio: "Spearheading environmental protection policies and supporting local farmers for agricultural sustainability. His priority is the long-term ecological balance of the municipality.",
        },
        {
          name: "Hon. Liza Mendoza",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Trade and Industry", "Committee on Human Resources"],
          bio: "Working to create a business-friendly environment and develop the skills of the municipal workforce. She believes a strong local economy is key to prosperity.",
        },
        {
          name: "Hon. Carlo Reyes",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1527980965255-d3b416303d12?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Rules", "Committee on Public Works"],
          bio: "Ensuring adherence to legislative rules and overseeing the quality and efficiency of public infrastructure projects. He is known for his strict oversight on construction timelines and budgets.",
        },
        {
          name: "Hon. Regina Bautista",
          position: "Municipal Councilor",
          photo: "https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=60",
          committees: ["Committee on Disaster Management", "Committee on Ways and Means"],
          bio: "Dedicated to enhancing disaster preparedness and managing revenue generation for the municipality. She leads initiatives for community training on calamity response.",
        },
      ],
    };
  },
  methods: {
    showMemberInfo(member) {
      this.selectedMember = member;
      setTimeout(() => {
        document.body.style.overflow = "hidden";
      }, 50);
    },
    closeModal() {
      this.selectedMember = null;
      document.body.style.overflow = "auto";
    },
    onModalEnter(el) {
      el.style.opacity = "0";
      setTimeout(() => {
        el.style.transition = "opacity 0.4s ease";
        el.style.opacity = "1";
      }, 100);

      const content = el.querySelector('.sb-modal-content');
      if (content) {
        content.style.transform = "scale(0.95) translateY(20px)";
        setTimeout(() => {
          content.style.transition = "transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1)";
          content.style.transform = "scale(1) translateY(0)";
        }, 150);
      }
    },
    onModalLeave(el) {
      el.style.transition = "opacity 0.3s ease";
      el.style.opacity = "0";

      const content = el.querySelector('.sb-modal-content');
      if (content) {
        content.style.transition = "transform 0.3s ease-out";
        content.style.transform = "scale(0.98) translateY(10px)";
      }
    },
  },
};
</script>

<style>
/* --- Global Reset and Font --- */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

/* Updated color palette to match standardized design system */
:root {
  --primary-green: #1b5e20; /* Darker, formal green */
  --secondary-green: #388e3c; /* Medium green */
  --accent-gold: #ffc107; /* Bright gold for accent */
  --bg-light: #f9f9f9; /* Off-white for background */
  --text-dark: #212121;
  --shadow-color: rgba(27, 94, 32, 0.15);
  --shadow-color-hover: rgba(27, 94, 32, 0.3);
}

.sb-sangguniang-bayan {
  font-family: "Poppins", sans-serif;
  background-color: var(--bg-light);
  color: var(--text-dark);
  overflow-x: hidden;
  margin-top: 100px;
}

/* --- Hero Section --- */
.sb-hero {
  position: relative;
  background-image: url("https://images.pexels.com/photos/3184299/pexels-photo-3184299.jpeg");
  background-size: cover;
  background-position: center;
  height: 70vh; 
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 3rem auto; 
  max-width: 90%; 
  border-radius: 1.5rem; 
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
  animation: zoomHero 15s infinite alternate ease-in-out;
}

.sb-hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* Updated gradient to use new primary green */
  background: linear-gradient(135deg, rgba(27, 94, 32, 0.95) 0%, rgba(30, 30, 30, 0.85) 100%);
  background-image: url("data:image/svg+xml,%3Csvg width='6' height='6' viewBox='0 0 6 6' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M5 0h1L0 6V5zm1 5v1H5z'/%3E%3C/g%3E%3C/svg%3E");
}

.sb-hero-content {
  max-width: 60rem;
  padding: 4rem;
  text-align: center;
  border: 4px solid var(--accent-gold); 
  border-radius: 1.25rem;
  background: rgba(0, 0, 0, 0.2);
  box-shadow: 0 0 50px rgba(27, 94, 32, 0.8);
  position: relative;
  z-index: 10;
}

.sb-hero-content h2 {
  font-size: clamp(3rem, 6vw, 4.5rem);
  margin-bottom: 1rem;
  color: white;
  font-weight: 900; 
  text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.9);
}

.sb-motto {
  margin-bottom: 2rem;
  font-size: clamp(1.4rem, 3vw, 2.2rem);
  color: var(--accent-gold);
  font-weight: 700;
  letter-spacing: 3px;
  text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
}

.sb-hero-accent {
  width: 100px; 
  height: 5px; 
  background-color: var(--accent-gold);
  margin: 0 auto;
  border-radius: 3px;
}

/* --- Sections and Header --- */
.sb-presiding-section,
.sb-councilors-section,
.sb-secretary-section {
  max-width: 1200px;
  margin: 0 auto;
  padding: 6rem 1.5rem; 
  text-align: center;
}

.sb-section-header h3 {
  color: var(--primary-green);
  font-weight: 800;
  display: inline-block;
  position: relative;
  margin-bottom: 3.5rem; 
  font-size: clamp(2.2rem, 5vw, 3.2rem); 
  text-transform: uppercase;
  letter-spacing: 2px;
}

.sb-section-header h3::after {
  content: "";
  display: block;
  width: 6rem; 
  height: 6px; 
  background-color: var(--accent-gold);
  margin: 0.75rem auto 0;
  border-radius: 3px;
}

/* --- Card Styles --- */
.sb-presiding-container {
  display: flex;
  justify-content: center;
  max-width: 480px; 
  margin: 0 auto;
}

.sb-featured-card,
.sb-council-card {
  background: white;
  border-radius: 1.25rem; 
  box-shadow: 0 8px 25px var(--shadow-color); 
  overflow: hidden;
  text-align: center;
  transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
  border-bottom: 6px solid var(--secondary-green); 
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 3rem 2rem;
  position: relative;
  cursor: pointer;
}

.sb-featured-card {
  max-width: 450px;
}

.card-shadow-hover:hover {
  transform: translateY(-1rem); 
  box-shadow: 0 25px 50px var(--shadow-color-hover);
  border-bottom-color: var(--accent-gold);
}

/* Image wrapper size and effect */
.sb-image-wrapper {
  border-radius: 50%;
  overflow: hidden;
  margin-bottom: 1.8rem;
  border: 4px solid var(--accent-gold); 
  transition: all 0.4s ease;
  box-shadow: 0 0 0 10px rgba(27, 94, 32, 0.1); 
}

.sb-featured-image-wrapper {
  width: 180px; 
  height: 180px;
}

.sb-councilor-image-wrapper {
  width: 190px;
  height: 190px;
}

.card-shadow-hover:hover .sb-image-wrapper {
  border-color: var(--primary-green);
  box-shadow: 0 0 0 10px rgba(255, 193, 7, 0.2); 
}

.sb-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.card-shadow-hover:hover .sb-image-wrapper img {
  transform: scale(1.05); 
}

/* Card text styling */
.sb-card-title {
  color: var(--primary-green);
  font-weight: 800;
  margin-top: 0;
  font-size: clamp(1.3rem, 2.5vw, 1.6rem); 
  line-height: 1.2;
}

.sb-card-position {
  color: #666;
  font-size: 0.95rem;
  font-weight: 500;
  margin: 0.4rem 0 1.5rem 0;
}

/* Button styling */
.sb-view-btn {
  background-color: var(--primary-green);
  color: white;
  padding: 0.8rem 2rem;
  border-radius: 50px;
  cursor: pointer;
  font-weight: 700;
  border: 2px solid var(--primary-green);
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 0.9rem;
  margin-top: auto;
}

.sb-view-btn:hover {
  background-color: transparent;
  color: var(--primary-green);
  border-color: var(--accent-gold);
  box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
  transform: translateY(-3px);
}

/* Council grid */
.sb-council-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(260px, 1fr));
  gap: 1rem;
}

/* --- MODAL STYLES --- */
.sb-modal-overlay {
  position: fixed;
  inset: 0;
  /* Updated background color to match design system */
  background: rgba(0, 0, 0, 0.85); 
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
  z-index: 1000;
  backdrop-filter: blur(10px);
}

.sb-modal-content {
  background: white;
  border-radius: 1.5rem;
  padding: 2rem;
  width: 100%;
  max-width: 700px;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
  border-top: 10px solid var(--primary-green);
}

/* Close Button */
.sb-close-btn {
  position: absolute;
  top: 1.2rem;
  right: 1.2rem;
  background: var(--primary-green);
  color: white;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  font-size: 30px;
  border: none;
  cursor: pointer;
  transition: 0.3s ease;
}

.sb-close-btn:hover {
  background: var(--accent-gold);
  color: var(--primary-green);
  transform: rotate(360deg) scale(1.08);
  box-shadow: 0 6px 15px rgba(255, 193, 7, 0.5);
}

/* HEADER – Image + Name */
.sb-modal-main-info {
  text-align: center;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 2px solid #eee;
}

.sb-modal-image-container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 1rem;
}

.sb-modal-image {
  width: 180px;
  height: 180px;
  border-radius: 50%;
  object-fit: cover;
  border: 6px solid var(--accent-gold);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.sb-modal-header-text h2 {
  font-size: 2rem;
  color: var(--primary-green);
  margin: 1rem 0 0.2rem;
  font-weight: 800;
}

.sb-modal-position {
  font-size: 1.1rem;
  color: #666;
}

/* ONE COLUMN BODY */
.sb-modal-body {
  display: flex;
  flex-direction: column;
  gap: 2.2rem;
}

/* Section Titles */
.sb-modal-section h3 {
  color: var(--primary-green);
  font-size: 1.3rem;
  margin-bottom: 1rem;
  font-weight: 800;
  border-bottom: 3px solid var(--accent-gold);
  padding-bottom: 0.5rem;
}

/* Biography */
.sb-bio {
  color: var(--text-dark);
  line-height: 1.7;
  font-size: 1rem;
  text-align: justify;
}

/* Committees */
.sb-committees-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.7rem;
}

.sb-committees-list li {
  background: rgba(27, 94, 32, 0.05);
  padding: 0.8rem 1rem;
  border-radius: 8px;
  border-left: 4px solid var(--primary-green);
  transition: 0.3s ease;
}

.sb-committees-list li:hover {
  background: rgba(27, 94, 32, 0.09);
  border-left-color: var(--accent-gold);
}

.sb-committee-icon {
  color: var(--accent-gold);
  margin-right: 0.6rem;
}

/* --- Responsive Design --- */
@media (max-width: 1200px) {
  .sb-council-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .sb-presiding-section,
  .sb-councilors-section,
  .sb-secretary-section {
    padding: 4rem 1.5rem;
  }
}

@media (max-width: 992px) {
  .sb-sangguniang-bayan {
    margin-top: 80px;
  }
  
  .sb-hero {
    height: 50vh;
    margin: 2rem auto;
    border-radius: 1rem;
  }
  
  .sb-hero-content {
    padding: 3rem 2rem;
    border: 3px solid var(--accent-gold);
  }
  
  .sb-hero-content h2 {
    font-size: 2.8rem;
  }
  
  .sb-motto {
    font-size: 1.4rem;
  }

  .sb-council-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .sb-featured-image-wrapper {
    width: 160px;
    height: 160px;
  }
  
  .sb-councilor-image-wrapper {
    width: 150px;
    height: 150px;
  }
  
  .sb-presiding-section,
  .sb-councilors-section,
  .sb-secretary-section {
    padding: 3.5rem 1.5rem;
  }
  
  .sb-section-header h3 {
    font-size: 2.2rem;
    margin-bottom: 2.5rem;
  }

  .sb-modal-content {
    max-width: 90%;
    padding: 1.5rem;
  }
}

@media (max-width: 768px) {
  .sb-sangguniang-bayan {
    margin-top: 70px;
  }
  
  .sb-hero {
    height: 45vh;
    margin: 1.5rem auto;
    border-radius: 0.8rem;
  }
  
  .sb-hero-content {
    padding: 2.5rem 1.5rem;
    border: 2px solid var(--accent-gold);
  }
  
  .sb-hero-content h2 {
    font-size: 2rem;
  }
  
  .sb-motto {
    font-size: 1.1rem;
  }

  .sb-council-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.2rem;
  }
  
  .sb-featured-image-wrapper {
    width: 140px;
    height: 140px;
  }
  
  .sb-councilor-image-wrapper {
    width: 120px;
    height: 120px;
  }

  .sb-card-title {
    font-size: 1.1rem;
  }

  .sb-modal-image {
    width: 140px;
    height: 140px;
  }
  
  .sb-modal-header-text h2 {
    font-size: 1.6rem;
  }
  
  .sb-modal-position {
    font-size: 0.95rem;
  }
  
  .sb-presiding-section,
  .sb-councilors-section,
  .sb-secretary-section {
    padding: 2.5rem 1rem;
  }
  
  .sb-section-header h3 {
    font-size: 1.8rem;
    margin-bottom: 2rem;
  }
  
  .sb-modal-section h3 {
    font-size: 1.1rem;
  }
  
  .sb-modal-content {
    max-width: 95%;
    padding: 1.2rem;
    border-radius: 1rem;
    max-height: 95vh;
  }
  
  .sb-close-btn {
    width: 40px;
    height: 40px;
    font-size: 24px;
    top: 1rem;
    right: 1rem;
  }
}

@media (max-width: 480px) {
  .sb-sangguniang-bayan {
    margin-top: 60px;
  }
  
  .sb-hero {
    height: 35vh;
    margin: 1rem auto;
    border-radius: 0.5rem;
  }
  
  .sb-hero-content {
    padding: 1.5rem 1rem;
    border: 2px solid var(--accent-gold);
  }
  
  .sb-hero-content h2 {
    font-size: 1.5rem;
  }
  
  .sb-motto {
    font-size: 0.95rem;
    letter-spacing: 1px;
  }
  
  .sb-hero-accent {
    width: 60px;
    height: 4px;
  }

  .sb-council-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .sb-featured-card,
  .sb-council-card {
    padding: 2rem 1.5rem;
  }
  
  .sb-featured-image-wrapper {
    width: 120px;
    height: 120px;
  }
  
  .sb-councilor-image-wrapper {
    width: 110px;
    height: 110px;
  }
  
  .sb-image-wrapper {
    margin-bottom: 1.2rem;
  }

  .sb-card-title {
    font-size: 1rem;
  }
  
  .sb-card-position {
    font-size: 0.85rem;
  }

  .sb-view-btn {
    padding: 0.7rem 1.5rem;
    font-size: 0.85rem;
  }

  .sb-presiding-section,
  .sb-councilors-section,
  .sb-secretary-section {
    padding: 2rem 0.8rem;
  }
  
  .sb-section-header h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
  }
  
  .sb-section-header h3::after {
    width: 4rem;
    height: 4px;
  }
  
  .sb-modal-overlay {
    padding: 0.5rem;
  }
  
  .sb-modal-content {
    max-width: 100%;
    padding: 1rem;
    border-radius: 0.8rem;
    max-height: 100vh;
  }
  
  .sb-modal-main-info {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
  }
  
  .sb-modal-image {
    width: 120px;
    height: 120px;
  }
  
  .sb-modal-header-text h2 {
    font-size: 1.4rem;
    margin: 0.8rem 0 0.2rem;
  }
  
  .sb-modal-position {
    font-size: 0.9rem;
  }
  
  .sb-modal-body {
    gap: 1.5rem;
  }
  
  .sb-modal-section h3 {
    font-size: 1rem;
  }
  
  .sb-bio {
    font-size: 0.9rem;
    line-height: 1.6;
    text-align: left;
  }
  
  .sb-committees-list li {
    padding: 0.7rem 0.8rem;
    font-size: 0.9rem;
  }
  
  .sb-close-btn {
    width: 36px;
    height: 36px;
    font-size: 20px;
    top: 0.8rem;
    right: 0.8rem;
  }
}

@media (max-width: 320px) {
  .sb-hero-content h2 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
  }
  
  .sb-motto {
    font-size: 0.85rem;
  }
  
  .sb-featured-card,
  .sb-council-card {
    padding: 1.5rem 1rem;
  }
  
  .sb-card-title {
    font-size: 0.95rem;
  }
  
  .sb-view-btn {
    padding: 0.6rem 1rem;
    font-size: 0.8rem;
  }
  
  .sb-section-header h3 {
    font-size: 1.3rem;
  }
}

</style>
