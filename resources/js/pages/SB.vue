<template>
    <div class="sangguniang-bayan">
      <Navbar />
  
      <section class="presiding-officer">
        <h2>Presiding Officer</h2>
        <div class="profile-card-container"> 
          <div class="profile-card" @click="showMemberInfo(presidingOfficer)">
            <div class="image-wrapper">
              <img :src="presidingOfficer.photo" alt="Vice Mayor" />
            </div>
            <h3>{{ presidingOfficer.name }}</h3>
            <p>{{ presidingOfficer.position }}</p>
          </div>
        </div>
      </section>
  
      <section class="councilors">
        <h2>Municipal Councilors</h2>
        <div class="council-grid">
          <div
            v-for="(member, index) in councilors"
            :key="index"
            class="council-card"
            @click="showMemberInfo(member)"
          >
            <div class="image-wrapper">
              <img :src="member.photo" :alt="member.name" />
            </div>
            <h4>{{ member.name }}</h4>
            <p>{{ member.position }}</p>
          </div>
        </div>
      </section>
  
      <section class="secretary">
        <h2>SB Secretary</h2>
        <div class="profile-card-container"> 
          <div class="profile-card" @click="showMemberInfo(secretary)">
            <div class="image-wrapper">
              <img :src="secretary.photo" alt="SB Secretary" />
            </div>
            <h3>{{ secretary.name }}</h3>
            <p>{{ secretary.position }}</p>
          </div>
        </div>
      </section>
  
      <div v-if="selectedMember" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <button class="close-btn" @click="closeModal">×</button>
          <img :src="selectedMember.photo" :alt="selectedMember.name" />
          <h2>{{ selectedMember.name }}</h2>
          <p class="position">{{ selectedMember.position }}</p>
          
          <template v-if="selectedMember.committees">
            <h3>Committee Assignments</h3>
            <ul>
              <li v-for="(committee, i) in selectedMember.committees" :key="i">
                {{ committee }}
              </li>
            </ul>
          </template>
          
          <p class="bio" v-if="selectedMember.bio">{{ selectedMember.bio }}</p>
          
          <p class="bio" v-else>No detailed information available for this member yet.</p>
  
        </div>
      </div>
  
      <FooterSection />
       
    </div>
  </template>
  
  <script>
  import Navbar from "@/components/Home/Navbar.vue";
  import FooterSection from '@/components/Home/Footer.vue';
  
  export default {
    name: "SangguniangBayan",
    components: { Navbar, FooterSection },
    data() {
      // Helper function to provide default committee/bio if missing
      const defaultMemberDetails = {
        committees: ["To be assigned", "Pending Legislation", "General Affairs"],
        bio: "Committed to serving the people of Concepcion through dedicated public service and effective policy-making.",
      };
  
      return {
        selectedMember: null,
        presidingOfficer: {
          name: "Hon. [Vice Mayor’s Name]",
          position: "Municipal Vice Mayor / Presiding Officer",
          photo:
            "https://upload.wikimedia.org/wikipedia/commons/a/a3/Vice_Mayor_Example.jpg",
          committees: [
            "Committee on Good Governance",
            "Committee on Public Safety",
            "Committee on Local Legislation",
          ],
          bio: "The Presiding Officer leads the Sangguniang Bayan, ensuring effective legislation and transparency in all municipal matters.",
        },
        secretary: {
          name: "Ms. [Secretary Name]",
          position: "Sangguniang Bayan Secretary",
          photo:
            "https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=800&q=60",
          committees: [
            "Administrative Support",
            "Documentation",
            "Legislative Records",
          ],
          bio: "Responsible for maintaining legislative records and supporting council operations efficiently.",
        },
        councilors: [
          {
            name: "Hon. Juan Dela Cruz",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails, // Use spread operator to add default info
          },
          {
            name: "Hon. Maria Santos",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1554151228-14d9def656e4?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails,
          },
          {
            name: "Hon. Roberto Garcia",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=800&q=60",
            committees: ["Committee on Finance", "Committee on Infrastructure"],
            bio: "A dedicated public servant with a focus on fiscal responsibility and development projects.",
          },
          {
            name: "Hon. Angela Ramos",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails,
          },
          {
            name: "Hon. Jose Manalo",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails,
          },
          {
            name: "Hon. Liza Mendoza",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails,
          },
          {
            name: "Hon. Carlo Reyes",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1527980965255-d3b416303d12?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails,
          },
          {
            name: "Hon. Regina Bautista",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=800&q=60",
            ...defaultMemberDetails,
          },
        ],
      };
    },
    methods: {
      showMemberInfo(member) {
        this.selectedMember = member;
      },
      closeModal() {
        this.selectedMember = null;
      },
    },
  };
  </script>
  
  <style>
  /* --- Design Improvements: Variables and General Styles --- */
  :root {
    --primary-color: #2e7d32; /* A slightly brighter, more vibrant green */
    --secondary-color: #ffb300; /* Gold/Yellow for accents and highlights */
    --background-light: #f4f8f4; /* Light background */
    --text-dark: #2c3e50; /* Darker text for better contrast */
    --shadow-light: rgba(0, 0, 0, 0.1);
    --shadow-medium: rgba(0, 0, 0, 0.2);
    
    /* Variables used in the component design: */
    --main-green: var(--primary-color);
    --accent-gold: var(--secondary-color);
    --bg-page: var(--background-light);
    --text-base: var(--text-dark);
    --card-shadow-style: 0 4px 10px rgba(0, 0, 0, 0.05); /* Lighter shadow for cards */
}

  .sangguniang-bayan {
    font-family: 'Poppins', sans-serif, 'Arial', sans-serif; /* Added better font stack */
    background-color: var(--background-light);
    color: var(--text-dark);
    text-align: center;
    padding: 0 0 0 0; /* Adjusted padding */
  }
  
  /* --- Headings --- */
  section {
    padding: 20px 0;
    max-width: 1200px;
    margin: 0 auto; /* Center sections */
  }
  
  section h2 {
    color: var(--primary-color);
    margin-top: 40px;
    margin-bottom: 30px; /* Increased bottom margin */
    font-weight: 700;
    font-size: 2.2rem;
    letter-spacing: 0.5px;
    position: relative;
  }
  
  /* Subtle separator under main headings */
  section h2::after {
      content: '';
      display: block;
      width: 60px;
      height: 3px;
      background-color: var(--secondary-color);
      margin: 10px auto 0;
      border-radius: 2px;
  }
  
  
  /* --- Profile Cards --- */
  
  /* Image container: Made circular with a primary color ring */
  .image-wrapper {
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 50%; 
    overflow: hidden;
    margin-bottom: 15px;
    box-shadow: 0 0 0 4px var(--primary-color); 
    transition: box-shadow 0.3s;
  }
  .image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
  }
  
  /* Card General Style */
  .profile-card,
  .council-card {
    cursor: pointer;
    background: #fff;
    border-radius: 25px; 
    padding: 25px 15px; 
    box-shadow: 0 8px 20px var(--shadow-light); 
    transition: transform 0.3s, box-shadow 0.3s;
    width: 100%;
    max-width: 280px; 
    margin: 0 auto; 
  }
  
  .council-card {
    max-width: 260px; 
    padding-bottom: 15px;
  }
  
  .profile-card h3, .council-card h4 {
    color: var(--primary-color);
    font-weight: 600;
    margin: 5px 0 3px 0;
  }
  
  .profile-card p, .council-card p {
    color: #7f8c8d; /* Muted gray for position */
    font-size: 0.95rem;
    font-weight: 500;
  }
  
  /* Hover Effect */
  .profile-card:hover,
  .council-card:hover {
    transform: translateY(-8px); /* Deeper lift */
    box-shadow: 0 12px 25px var(--shadow-medium);
  }
  .profile-card:hover .image-wrapper,
  .council-card:hover .image-wrapper {
    box-shadow: 0 0 0 4px var(--secondary-color); /* Highlight ring on hover */
  }
  
  
  /* Presiding Officer & Secretary Layout: Use a container to limit max-width */
  .profile-card-container {
      max-width: 300px; 
      margin: 0 auto;
  }
  .presiding-officer, .secretary {
    padding: 30px 20px;
  }
  
  
  /* --- Councilor Grid --- */
  .councilors {
    padding: 30px 20px;
  }
  .council-grid {
    display: grid;
    /* Auto-fit for flexible 4-column layout */
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
    gap: 30px;
    justify-items: center;
    max-width: 1200px;
    margin: 0 auto; 
  }
  
  /* --- MODAL --- */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(44, 62, 80, 0.9); /* Darker, sophisticated overlay */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  .modal-content {
    background: white;
    padding: 40px; 
    border-radius: 20px;
    width: 95%;
    max-width: 600px;
    text-align: center;
    position: relative;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
  }
  .modal-content img {
    width: 150px; 
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 20px;
    border: 5px solid var(--secondary-color); /* Gold border for focus */
  }
  .modal-content h2 {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin-bottom: 5px;
  }
  .modal-content .position {
    color: #7f8c8d;
    font-size: 1.1rem;
    font-style: italic;
    margin-bottom: 25px;
  }
  .modal-content h3 {
    color: var(--text-dark);
    font-size: 1.4rem;
    margin-top: 20px;
    padding-bottom: 5px;
    border-bottom: 2px solid var(--primary-color);
    display: inline-block;
  }
  .modal-content ul {
    list-style: none;
    padding: 0;
    text-align: left;
    max-width: 80%;
    margin: 15px auto 25px;
  }
  .modal-content li {
    background-color: #e8f5e9; /* Light green background for list items */
    padding: 8px 15px;
    border-radius: 10px;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-size: 0.95rem;
  }
  .modal-content .bio {
    font-style: italic;
    color: #555;
    margin-top: 20px;
    font-size: 1rem;
  }
  .close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: var(--primary-color);
    color: white;
    border: none;
    font-size: 20px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    cursor: pointer;
    transition: background 0.2s;
  }
  .close-btn:hover {
    background: var(--secondary-color);
  }
  
  /* --- Responsive Adjustments --- */
  @media (max-width: 768px) {
    section h2 {
      font-size: 1.8rem;
    }
    .council-grid {
      gap: 20px;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); 
      padding: 0 15px;
    }
  }
  @media (max-width: 480px) {
    .council-grid {
      grid-template-columns: 1fr 1fr;
    }
    
  }
  </style>