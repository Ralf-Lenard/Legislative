<template>
  <div class="sb-sessions">
    <Navbar />

    <!-- Hero Section -->
    <section class="sb-hero sb-sessions-hero">
      <div class="sb-hero-overlay"></div>
      <div class="sb-hero-content">
        <h2>Sangguniang Bayan Sessions</h2>
        <p class="sb-motto">Official Schedule for Legislative Proceedings</p>
        <div class="sb-hero-accent"></div>
      </div>
    </section>

    <!-- Session Schedule Section -->
    <section class="sessions-main-content">
      <div class="sb-section-header">
        <h3 class="animated-header">Session Schedule</h3>
      </div>

      <div class="session-tabs">
        <button
          :class="{ 'active-tab': activeTab === 'regular' }"
          @click="activeTab = 'regular'"
          class="tab-btn"
        >
          <i class="fas fa-calendar-alt"></i> Regular Sessions
        </button>
        <button
          :class="{ 'active-tab': activeTab === 'special' }"
          @click="activeTab = 'special'"
          class="tab-btn"
        >
          <i class="fas fa-star"></i> Special Sessions
        </button>
      </div>

      <transition name="fade" mode="out-in">
        <div v-if="activeTab === 'regular'" key="regular" class="session-container">
          <div class="session-card">
            <div class="session-icon-box">
              <i class="fas fa-clock"></i>
            </div>
            <div class="session-details">
              <h4>Regular Session Schedule</h4>
              <p class="detail-label">Frequency:</p>
              <p class="detail-value">{{ regularSession.frequency }}</p>
              <p class="detail-label">Day:</p>
              <p class="detail-value">{{ regularSession.day }}</p>
              <p class="detail-label">Time:</p>
              <p class="detail-value">{{ regularSession.time }}</p>
              <p class="detail-label">Venue:</p>
              <p class="detail-value">{{ regularSession.venue }}</p>
            </div>
          </div>
          <div class="session-note-card">
            <h5 class="note-title"><i class="fas fa-info-circle"></i> Note on Regular Sessions</h5>
            <p>
              Regular Sessions are held every <strong>{{ regularSession.day }}</strong> to discuss and enact ordinances and resolutions for the municipality. These sessions are generally open to the public, subject to safety protocols and available seating.
            </p>
          </div>
        </div>
      </transition>

      <transition name="fade" mode="out-in">
        <div v-if="activeTab === 'special'" key="special" class="session-container">
          <div class="session-card">
            <div class="session-icon-box">
              <i class="fas fa-bolt"></i>
            </div>
            <div class="session-details">
              <h4>Special Session Procedures</h4>
              <p class="detail-label">Called By:</p>
              <p class="detail-value">{{ specialSession.calledBy }}</p>
              <p class="detail-label">Purpose:</p>
              <p class="detail-value">{{ specialSession.purpose }}</p>
              <p class="detail-label">Notice:</p>
              <p class="detail-value">{{ specialSession.notice }}</p>
              <p class="detail-label">Attendance:</p>
              <p class="detail-value">{{ specialSession.attendance }}</p>
            </div>
          </div>
          <div class="session-note-card">
            <h5 class="note-title"><i class="fas fa-exclamation-triangle"></i> Note on Special Sessions</h5>
            <p>
              Special Sessions are held only when necessary to urgently consider important legislative matters, particularly those requiring immediate action like disaster response or budget recalibration. Public notice is issued immediately upon call.
            </p>
          </div>
        </div>
      </transition>
    </section>

    <hr class="section-divider" />

    <!-- Session History Section -->
    <section class="session-history-section">
      <div class="sb-section-header">
        <h3 class="animated-header history-header"><i class="fas fa-history"></i> Session History Log</h3>
      </div>

      <div class="history-grid-container simplified-grid">
        <div 
          v-for="(item, index) in sessionHistory" 
          :key="index" 
          :class="['history-summary-card', item.type.toLowerCase().split(' ')[0]]"
        >
          <div class="summary-image-box">
            <img :src="item.photos[0]" :alt="item.title" loading="lazy" />
            <div :class="['history-session-type', item.type.toLowerCase().split(' ')[0]]">
              <i :class="item.type.includes('Regular') ? 'fas fa-calendar-alt' : 'fas fa-star'"></i>
              {{ item.type }}
            </div>
          </div>

          <div class="summary-details">
            <h4 class="summary-title">{{ item.title }}</h4>
            <p class="history-date"><i class="far fa-calendar-alt"></i> {{ item.date }}</p>
            <p class="history-description-short">
              {{ item.fullDescription.substring(0, 150) }}...
            </p>

            <div class="btn-wrapper">
              <button class="view-session-btn" @click="goToSessionDetail(item.title)">
                View Session Details <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <FooterSection />
  </div>
</template>

<script>
import Navbar from "@/components/Home/Navbar.vue";
import FooterSection from "@/components/Home/Footer.vue";

export default {
  name: "Sessions",
  components: { Navbar, FooterSection },
  data() {
    return {
      activeTab: "regular",
      regularSession: {
        frequency: "Once a week (unless postponed)",
        day: "Every Monday",
        time: "9:00 AM",
        venue: "Sangguniang Bayan Session Hall",
      },
      specialSession: {
        calledBy: "The Local Chief Executive or Majority of the Sangguniang Bayan Members",
        purpose: "Urgent legislative matters, budget, or emergencies",
        notice: "24 hours written notice to all members",
        attendance: "Majority of members required for quorum",
      },
      sessionHistory: [
        {
          title: "Ordinance No. 2024-001 Adoption",
          date: "Jan 15, 2024",
          type: "Regular Session",
          fullDescription: "The first regular session of the year was focused on the Annual Municipal Budget...",
          photos: [
            "https://images.pexels.com/photos/1036329/pexels-photo-1036329.jpeg?auto=compress&cs=tinysrgb&w=800",
            "https://images.pexels.com/photos/4050319/pexels-photo-4050319.jpeg?auto=compress&cs=tinysrgb&w=800",
          ]
        },
        {
          title: "Emergency Disaster Resolution",
          date: "Oct 28, 2023",
          type: "Special Session",
          fullDescription: "Special Session called after Super Typhoon Yolanda to approve emergency resolutions...",
          photos: [
            "https://images.pexels.com/photos/168938/pexels-photo-168938.jpeg?auto=compress&cs=tinysrgb&w=800",
          ]
        },
      ]
    };
  },
  methods: {
    goToSessionDetail(title) {
      alert(`Loading full details for: "${title}"`);
    }
  }
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

:root {
  --primary-green: #0a4f10;
  --secondary-green: #388e3c;
  --accent-gold: #ffc107;
  --bg-light: #fbfcfb;
  --text-dark: #1f1f1f;
  --text-light: #6a6a6a;
}

/* --- Main Container --- */
.sb-sessions {
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
  height: 75vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 2rem auto;
  max-width: 95%;
  border-radius: 1.5rem;
  overflow: hidden;
  animation: zoomHero 15s infinite alternate ease-in-out;
}

/* .sb-hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 79, 16, 0.5); 
} */

.sb-hero-content {
  max-width: 60rem;
  padding: 2.5rem 3rem;
  text-align: center;
  border: 4px solid var(--accent-gold); 
  border-radius: 1.5rem;
  background: rgba(0, 0, 0, 0.4);
  box-shadow: 0 0 50px rgba(10, 79, 16, 0.8);
  position: relative;
  z-index: 10;
}

.sb-hero-content h2 {
  font-size: clamp(2.5rem, 5vw, 3.5rem);
  margin-bottom: 1rem;
  color: white;
  font-weight: 900;
  text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
}

.sb-motto {
  margin-bottom: 1.5rem;
  font-size: clamp(1.2rem, 2.5vw, 1.8rem);
  color: var(--accent-gold);
  font-weight: 700;
  letter-spacing: 2px;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}

.sb-hero-accent {
  width: 80px; 
  height: 4px; 
  background-color: var(--accent-gold);
  margin: 0.75rem auto 0;
  border-radius: 3px;
}

/* --- Sections and Headers --- */
.sessions-main-content,
.session-history-section {
  max-width: 1100px;
  margin: 0 auto;
  padding: 4rem 2rem 0;
  margin-bottom: 50px;
}

.sb-section-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.sb-section-header h3 {
  color: var(--primary-green);
  font-weight: 800;
  display: inline-block;
  position: relative;
  margin-bottom: 3rem;
  font-size: clamp(2.2rem, 5vw, 3.2rem);
  text-transform: uppercase;
  letter-spacing: 2px;
}

.sb-section-header h3.history-header {
    font-size: clamp(2rem, 4vw, 2.8rem);
    margin-top: 3rem;
}

.sb-section-header h3 i {
    color: var(--accent-gold);
    margin-right: 0.5rem;
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

/* --- Session Tabs --- */
.session-tabs {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-bottom: 3.5rem;
  border-bottom: 2px solid #e0e0e0;
  padding-bottom: 0;
}

.tab-btn {
  background: none;
  border: none;
  padding: 1rem 2.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--text-light);
  cursor: pointer;
  position: relative;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.tab-btn:hover {
  color: var(--primary-green);
}

.tab-btn i {
  margin-right: 0.75rem;
  color: var(--accent-gold);
}

.active-tab {
  color: var(--primary-green);
  border-bottom: 4px solid var(--primary-green);
  padding-bottom: 1rem;
}

.active-tab i {
  color: var(--primary-green);
}

/* --- Session Cards --- */
.session-container {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 3.5rem;
  align-items: stretch;
}

.session-card {
  background: white;
  border-radius: 1.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  padding: 2.75rem;
  display: flex;
  flex-direction: column;
  border-top: 8px solid var(--accent-gold);
}

.session-icon-box {
  background: var(--primary-green);
  color: white;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 2.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 8px 20px rgba(10, 79, 16, 0.4);
}

.session-details h4 {
  color: var(--primary-green);
  font-size: 1.8rem;
  font-weight: 800;
  margin-bottom: 2rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #f0f0f0;
}

.detail-label {
  font-weight: 700;
  color: var(--text-dark);
  font-size: 1rem;
  margin: 1.2rem 0 0.2rem 0;
}

.detail-value {
  color: var(--text-light);
  font-weight: 500;
  font-size: 0.95rem;
  margin: 0;
  padding-left: 0.5rem;
  border-left: 3px solid var(--accent-gold);
}

.session-note-card {
  background: var(--primary-green);
  color: white;
  border-radius: 1.5rem;
  padding: 2.75rem;
  box-shadow: 0 10px 30px rgba(10, 79, 16, 0.3);
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.note-title {
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  border-bottom: 2px solid var(--accent-gold);
  padding-bottom: 0.75rem;
}

.note-title i {
  color: var(--accent-gold);
  margin-right: 0.75rem;
}

.session-note-card p {
  line-height: 1.7;
  font-weight: 400;
  font-size: 0.95rem;
}

/* --- Divider --- */
.section-divider {
    max-width: 900px;
    margin: 6rem auto;
    border: none;
    border-top: 1px solid #ddd;
}

/* --- History Cards --- */
.history-grid-container.simplified-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 3rem;
}

.history-summary-card {
  background: white;
  border-radius: 1.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: all 0.3s ease;
  cursor: pointer;
  border: 3px solid transparent;
}

.history-summary-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
  border-color: var(--accent-gold);
}

.history-summary-card.regular { border-left: 8px solid var(--secondary-green); }
.history-summary-card.special { border-left: 8px solid var(--accent-gold); }

.summary-image-box { position: relative; height: 200px; overflow: hidden; }
.summary-image-box img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
.history-summary-card:hover .summary-image-box img { transform: scale(1.05); }

.history-session-type { 
  position: absolute; top: 15px; right: 15px; 
  padding: 0.5rem 1rem; border-radius: 30px; 
  font-weight: 700; font-size: 0.8rem; text-transform: uppercase; z-index: 5; 
}
.history-session-type.regular { background-color: var(--secondary-green); color: white; }
.history-session-type.special { background-color: var(--accent-gold); color: var(--primary-green); }

.summary-details { padding: 1.75rem; display: flex; flex-direction: column; }
.history-description-short { flex-grow: 1; margin-bottom: 1rem; }

.btn-wrapper { margin-top: 1rem; }
.view-session-btn {
  background: var(--primary-green);
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 50px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  transition: all 0.3s ease;
}
.view-session-btn:hover {
  background: var(--accent-gold);
  color: var(--text-dark);
}

/* --- Animations --- */
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s ease, transform 0.5s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; transform: translateY(10px); }

/* --- Responsive --- */
@media (max-width: 768px) { 
  .session-container { grid-template-columns: 1fr; gap: 2rem; } 
  .history-grid-container.simplified-grid { grid-template-columns: 1fr; gap: 2rem; }
  .sb-hero-content { padding: 2rem 2.5rem; }
}
@media (max-width: 480px) { 
  .sb-hero-content h2 { font-size: 2rem; } 
  .sb-motto { font-size: 1rem; } 
  .tab-btn { padding: 0.75rem 1rem; font-size: 1rem; } 
}

</style>