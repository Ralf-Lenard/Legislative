<template>
    <div class="sangguniang-bayan">
      <!-- Header -->
      <section class="header">
        <h1>Sangguniang Bayan of Concepcion, Tarlac</h1>
        <p>Meet the honorable members serving the people with dedication and integrity.</p>
      </section>
  
      <!-- Presiding Officer -->
      <section class="presiding-officer">
        <h2>Presiding Officer</h2>
        <div class="profile-card" @click="showMemberInfo(presidingOfficer)">
          <img :src="presidingOfficer.photo" alt="Vice Mayor" />
          <h3>{{ presidingOfficer.name }}</h3>
          <p>{{ presidingOfficer.position }}</p>
        </div>
      </section>
  
      <!-- Councilors -->
      <section class="councilors">
        <h2>Municipal Councilors</h2>
        <div class="council-grid">
          <div
            v-for="(member, index) in councilors"
            :key="index"
            class="council-card"
            @click="showMemberInfo(member)"
          >
            <img :src="member.photo" :alt="member.name" />
            <h4>{{ member.name }}</h4>
            <p>{{ member.position }}</p>
          </div>
        </div>
      </section>
  
      <!-- Secretary -->
      <section class="secretary">
        <h2>SB Secretary</h2>
        <div class="profile-card" @click="showMemberInfo(secretary)">
          <img :src="secretary.photo" alt="SB Secretary" />
          <h3>{{ secretary.name }}</h3>
          <p>{{ secretary.position }}</p>
        </div>
      </section>
  
      <!-- Modal (Details on Click) -->
      <div v-if="selectedMember" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <button class="close-btn" @click="closeModal">×</button>
          <img :src="selectedMember.photo" :alt="selectedMember.name" />
          <h2>{{ selectedMember.name }}</h2>
          <p class="position">{{ selectedMember.position }}</p>
          <h3>Committee Assignments</h3>
          <ul>
            <li v-for="(committee, i) in selectedMember.committees" :key="i">
              {{ committee }}
            </li>
          </ul>
          <p class="bio">{{ selectedMember.bio }}</p>
        </div>
      </div>
  
      <!-- Footer -->
      <footer>
        <p>© 2025 Sangguniang Bayan of Concepcion, Tarlac | All Rights Reserved</p>
      </footer>
    </div>
  </template>
  
  <script>
  export default {
    name: "SangguniangBayan",
    data() {
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
          committees: ["Administrative Support", "Documentation", "Legislative Records"],
          bio: "Responsible for maintaining legislative records and supporting council operations efficiently.",
        },
        councilors: [
          {
            name: "Hon. Juan Dela Cruz",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1595152772835-219674b2a8a6?auto=format&fit=crop&w=800&q=60",
            committees: [
              "Committee on Health and Sanitation",
              "Committee on Environment",
              "Committee on Agriculture",
            ],
            bio: "Passionate about sustainable farming and public health.",
          },
          {
            name: "Hon. Maria Santos",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1554151228-14d9def656e4?auto=format&fit=crop&w=800&q=60",
            committees: [
              "Committee on Education",
              "Committee on Women and Family",
              "Committee on Youth Development",
            ],
            bio: "Advocate for women empowerment and education programs.",
          },
          {
            name: "Hon. Roberto Garcia",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=800&q=60",
            committees: [
              "Committee on Finance",
              "Committee on Budget and Appropriations",
            ],
            bio: "Focuses on transparent budgeting and fiscal responsibility.",
          },
          {
            name: "Hon. Angela Ramos",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=800&q=60",
            committees: [
              "Committee on Tourism",
              "Committee on Culture and Heritage",
            ],
            bio: "Committed to promoting Concepcion’s culture and tourism.",
          },
          {
            name: "Hon. Jose Manalo",
            position: "Municipal Councilor",
            photo:
              "https://images.unsplash.com/photo-1599566150163-29194dcaad36?auto=format&fit=crop&w=800&q=60",
            committees: ["Committee on Transportation", "Committee on Public Works"],
            bio: "Dedicated to improving road safety and infrastructure.",
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
  
  <style scoped>
  .sangguniang-bayan {
    font-family: "Poppins", sans-serif;
    background-color: #f4f8f4;
    color: #222;
    text-align: center;
    padding-bottom: 60px;
  }
  
  /* Header */
  .header {
    background: linear-gradient(90deg, #1b5e20, #43a047);
    color: white;
    padding: 80px 20px;
    border-radius: 0 0 30px 30px;
  }
  
  .header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
  }
  
  /* Cards */
  .profile-card,
  .council-card {
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  .profile-card:hover,
  .council-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  }
  
  .profile-card img,
  .council-card img {
    border-radius: 20px;
  }
  
  /* Councilors */
  .council-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    justify-items: center;
    margin: 30px;
  }
  
  .council-card {
    background: #fff;
    border-radius: 20px;
    padding: 15px;
    width: 220px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  
  /* Modal */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  
  .modal-content {
    background: white;
    padding: 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    text-align: center;
    position: relative;
    animation: fadeIn 0.3s ease;
  }
  
  .modal-content img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 15px;
  }
  
  .modal-content h2 {
    color: #2e7d32;
    margin-top: 20px;
  }
  
  .modal-content h3 {
    margin-top: 15px;
    color: #1b5e20;
  }
  
  .modal-content ul {
    text-align: left;
    margin: 10px auto;
    display: inline-block;
    padding-left: 20px;
  }
  
  .close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #333;
  }
  
  footer {
    background-color: #1b5e20;
    color: white;
    padding: 20px;
    border-radius: 30px 30px 0 0;
    margin-top: 40px;
  }
  </style>
  