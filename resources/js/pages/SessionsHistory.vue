<template>
    <div class="session-detail-page">
      <Navbar />
  
      <section class="detail-hero">
        <div class="detail-header-content">
          <button @click="goBack" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Session History
          </button>
          <h1 class="session-title">{{ session.title }}</h1>
          <div class="session-meta">
            <span :class="['type-tag', session.type.toLowerCase().split(' ')[0]]">
              <i :class="session.type.includes('Regular') ? 'fas fa-calendar-alt' : 'fas fa-star'"></i> 
              {{ session.type }}
            </span>
            <span class="date-tag">
              <i class="far fa-calendar-alt"></i> {{ session.date }}
            </span>
            <span class="venue-tag">
              <i class="fas fa-map-marker-alt"></i> {{ session.venue }}
            </span>
          </div>
        </div>
      </section>
  
      <section class="detail-main-content">
        
        <div class="description-section">
          <h2><i class="fas fa-book-open"></i> Session Summary</h2>
          <p v-html="formattedDescription"></p>
        </div>
  
        <div class="resolutions-section">
          <h2><i class="fas fa-gavel"></i> Key Actions & Resolutions</h2>
          <div class="resolutions-grid">
            <div v-for="(res, index) in session.resolutions" :key="index" class="resolution-item">
              <i class="fas fa-check-circle"></i>
              <p>{{ res }}</p>
            </div>
          </div>
          <div class="note-box">
              <i class="fas fa-info-circle"></i>
              All final approved documents are available for public viewing at the Sangguniang Bayan Secretary's Office during business hours.
          </div>
        </div>
      </section>
  
      <hr class="section-divider" />
  
      <section class="photo-gallery-section">
        <div class="sb-section-header">
          <h3 class="animated-header gallery-header"><i class="fas fa-camera"></i> Photo Gallery</h3>
        </div>
  
        <div class="gallery-grid">
          <div 
            v-for="(photo, index) in session.photos" 
            :key="index" 
            class="photo-item"
            @click="openModal(index)"
          >
            <img :src="photo.url" :alt="photo.caption" loading="lazy" />
            <div class="photo-caption">{{ photo.caption }}</div>
          </div>
        </div>
      </section>
  
      <transition name="modal-fade">
        <div v-if="showModal" class="lightbox-modal" @click.self="closeModal">
          <div class="modal-content-wrapper">
            
            <button class="close-btn" @click="closeModal"><i class="fas fa-times"></i></button>
  
            <button class="nav-btn prev-btn" @click.stop="prevImage" :disabled="currentImageIndex === 0">
              <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="image-container">
              <img :src="currentImage.url" :alt="currentImage.caption" class="modal-image" />
              <div class="image-info">
                <p class="image-caption-text">{{ currentImage.caption }}</p>
                <span class="image-counter">
                  {{ currentImageIndex + 1 }} / {{ session.photos.length }}
                </span>
              </div>
            </div>
            
            <button class="nav-btn next-btn" @click.stop="nextImage" :disabled="currentImageIndex === session.photos.length - 1">
              <i class="fas fa-chevron-right"></i>
            </button>
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
    name: "SessionDetail",
    components: { Navbar, FooterSection },
    data() {
      return {
        showModal: false,
        currentImageIndex: 0,
        session: {
          title: "Ordinance No. 2024-001 Adoption: Annual Budget",
          date: "Monday, January 15, 2024",
          type: "Regular Session",
          venue: "Sangguniang Bayan Session Hall",
          fullDescription: `
            The inaugural regular session of the year was a landmark event, primarily focused on the meticulous review and subsequent **unanimous approval** of the Annual Municipal Budget (Ordinance No. 2024-001).
            <br><br>
            The P200 Million budget allocation saw councilors prioritizing significant funds towards enhancing local health services, expanding educational facilities, and initiating three new major infrastructure projects, including a critical farm-to-market road extension in Barangay San Jose.
            <br><br>
            The discussion included detailed presentations from the Municipal Planning Officer and the Local Finance Committee, ensuring fiscal transparency. The council also allocated a contingency fund for immediate response to unforeseen climate events.
          `,
          resolutions: [
            "Ordinance No. 2024-001: Adoption of the Annual Municipal Budget (P200M)",
            "Resolution No. 2024-005: Prioritizing Three New Infrastructure Projects (Farm-to-Market Roads)",
            "Resolution No. 2024-006: Approval of New Health and Sanitation Programs",
            "Resolution No. 2024-007: Allocation of Climate Change Adaptation Fund"
          ],
          photos: [
            { url: "https://images.pexels.com/photos/3184457/pexels-photo-3184457.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "The Presiding Officer opens the floor for discussions." },
            { url: "https://images.pexels.com/photos/1036329/pexels-photo-1036329.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "Council members reviewing the draft of the Annual Budget." },
            { url: "https://images.pexels.com/photos/4050319/pexels-photo-4050319.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "Municipal Planning Officer presents the infrastructure blueprints." },
            { url: "https://images.pexels.com/photos/3184306/pexels-photo-3184306.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "The Secretary records the unanimous vote on Ordinance 2024-001." },
            { url: "https://images.pexels.com/photos/4050315/pexels-photo-4050315.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "A member provides input during the budget deliberation." },
            { url: "https://images.pexels.com/photos/3184454/pexels-photo-3184454.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "The public viewing area observed during the legislative session." },
            { url: "https://images.pexels.com/photos/5926390/pexels-photo-5926390.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "Close-up of the official agenda on the table." },
            { url: "https://images.pexels.com/photos/3220379/pexels-photo-3220379.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "A ceremonial signing of the adopted resolution." },
            { url: "https://images.pexels.com/photos/3184333/pexels-photo-3184333.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "Wide view of the session hall setup." },
            { url: "https://images.pexels.com/photos/3184192/pexels-photo-3184192.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "The Local Chief Executive giving his closing remarks." },
            { url: "https://images.pexels.com/photos/3184288/pexels-photo-3184288.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "Focus on the legal counsel during the reading." },
            { url: "https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2", caption: "Staff distributing copies of the new ordinance." },
          ],
        },
      };
    },
    computed: {
      formattedDescription() {
          return this.session.fullDescription.trim();
      },
      currentImage() {
          return this.session.photos[this.currentImageIndex];
      }
    },
    methods: {
      goBack() {
        console.log('Navigating back to history page...');
      },
      openModal(index) {
          this.currentImageIndex = index;
          this.showModal = true;
          // Optional: Disable scrolling on the main body
          document.body.style.overflow = 'hidden'; 
      },
      closeModal() {
          this.showModal = false;
          // Optional: Re-enable scrolling
          document.body.style.overflow = '';
      },
      nextImage() {
          if (this.currentImageIndex < this.session.photos.length - 1) {
              this.currentImageIndex++;
          }
      },
      prevImage() {
          if (this.currentImageIndex > 0) {
              this.currentImageIndex--;
          }
      },
      handleKeydown(event) {
          if (!this.showModal) return;
          
          if (event.key === 'Escape') {
              this.closeModal();
          } else if (event.key === 'ArrowRight') {
              this.nextImage();
          } else if (event.key === 'ArrowLeft') {
              this.prevImage();
          }
      }
    },
    mounted() {
      window.addEventListener('keydown', this.handleKeydown);
    },
    beforeUnmount() {
      window.removeEventListener('keydown', this.handleKeydown);
      // Ensure body scrolling is re-enabled if component is destroyed while modal is open
      document.body.style.overflow = ''; 
    }
  };
  </script>
  
  <style>
  /* --- FONT & BASE STYLES --- */
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap");
  @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
  
  :root {
    --primary-green: #0a4f10;
    --secondary-green: #388e3c;
    --accent-gold: #ffc107;
    --bg-light: #f4f6f9;
    --text-dark: #1f1f1f;
    --text-light: #6a6a6a;
    --card-bg: #ffffff;
  }
  
  .session-detail-page {
    font-family: "Poppins", sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
    overflow-x: hidden;
    margin-top: 100px;
  }
  
  /* --- 1. HEADER SECTION --- */
  .detail-hero {
    background: var(--primary-green);
    color: white;
    padding: 3rem 2rem 4rem;
    margin-bottom: 3rem;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  }
  
  .detail-header-content {
    max-width: 1100px;
    margin: 0 auto;
  }
  
  .back-btn {
    background: none;
    border: 2px solid var(--accent-gold);
    color: var(--accent-gold);
    padding: 0.5rem 1.5rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 2rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .back-btn:hover {
    background: var(--accent-gold);
    color: var(--primary-green);
  }
  
  .session-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 900;
    margin-bottom: 1.5rem;
    line-height: 1.2;
  }
  
  .session-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    font-size: 1rem;
    font-weight: 500;
  }
  
  .session-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
  }
  
  .session-meta i {
    color: var(--accent-gold);
  }
  
  .type-tag.regular {
    background: var(--secondary-green);
    color: white;
  }
  .type-tag.special {
    background: var(--accent-gold);
    color: var(--primary-green);
    font-weight: 700;
  }
  
  /* --- 2. MAIN CONTENT & RESOLUTIONS --- */
  .detail-main-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 2rem 3rem;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
  }
  
  .description-section h2,
  .resolutions-section h2 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--primary-green);
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid var(--accent-gold);
  }
  
  .description-section h2 i,
  .resolutions-section h2 i {
    color: var(--accent-gold);
    margin-right: 0.75rem;
  }
  
  .description-section p {
    line-height: 1.8;
    font-size: 1.05rem;
    color: var(--text-dark);
  }
  
  .resolutions-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
  }
  
  .resolution-item {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    background: var(--card-bg);
    border-left: 5px solid var(--secondary-green);
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  }
  
  .resolution-item i {
    color: var(--secondary-green);
    margin-right: 1rem;
    font-size: 1.2rem;
    margin-top: 0.2rem;
  }
  
  .resolution-item p {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-dark);
  }
  
  .note-box {
      padding: 1.5rem;
      background: #fdf5e6;
      border: 1px solid var(--accent-gold);
      border-radius: 8px;
      color: var(--text-dark);
      font-size: 0.9rem;
      font-weight: 500;
      display: flex;
      gap: 1rem;
  }
  .note-box i {
      color: var(--accent-gold);
      font-size: 1.2rem;
  }
  
  
  /* --- 3. PHOTO GALLERY --- */
  .section-divider {
      max-width: 1000px;
      margin: 5rem auto;
      border: none;
      border-top: 1px solid #ddd;
  }
  
  .photo-gallery-section {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 2rem 5rem;
  }
  
  .sb-section-header h3.gallery-header {
      font-size: clamp(2rem, 4vw, 3rem);
      margin-bottom: 3.5rem;
      color: var(--primary-green);
  }
  
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .photo-item {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer; /* Makes it look clickable */
  }
  
  .photo-item:hover {
      transform: scale(1.02);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  }
  
  .photo-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
  }
  
  .photo-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    font-size: 0.85rem;
    font-weight: 500;
  }
  
  /* --- 4. LIGHTBOX MODAL STYLES --- */
  .lightbox-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.95);
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: center;
  }
  
  .modal-content-wrapper {
      position: relative;
      max-width: 90%;
      max-height: 90%;
      display: flex;
      align-items: center;
      justify-content: center;
  }
  
  .image-container {
      display: flex;
      flex-direction: column;
      align-items: center;
  }
  
  .modal-image {
      max-width: 100%;
      max-height: 80vh; /* Limits image height */
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
      transition: opacity 0.3s ease;
  }
  
  .close-btn {
      position: absolute;
      top: -40px;
      right: 0;
      background: none;
      border: none;
      color: white;
      font-size: 2.5rem;
      cursor: pointer;
      z-index: 1010;
      opacity: 0.8;
      transition: opacity 0.2s;
  }
  
  .close-btn:hover {
      opacity: 1;
      color: var(--accent-gold);
  }
  
  .nav-btn {
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border: 2px solid white;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      font-size: 1.5rem;
      cursor: pointer;
      position: absolute;
      transition: background 0.3s, color 0.3s;
      z-index: 1005;
  }
  .nav-btn:hover:not(:disabled) {
      background: var(--primary-green);
      border-color: var(--accent-gold);
      color: var(--accent-gold);
  }
  
  .nav-btn:disabled {
      opacity: 0.3;
      cursor: not-allowed;
      border-color: rgba(255, 255, 255, 0.3);
  }
  
  .prev-btn {
      left: -70px;
  }
  .next-btn {
      right: -70px;
  }
  
  .image-info {
      width: 100%;
      margin-top: 1rem;
      padding: 1rem;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
  }
  .image-caption-text {
      margin: 0;
      font-size: 1rem;
      font-weight: 400;
      max-width: 80%;
  }
  .image-counter {
      font-size: 0.9rem;
      color: var(--accent-gold);
      font-weight: 600;
  }
  
  /* Transition for Modal */
  .modal-fade-enter-active, .modal-fade-leave-active {
    transition: opacity 0.5s ease;
  }
  .modal-fade-enter-from, .modal-fade-leave-to {
    opacity: 0;
  }
  
  
  /* --- Responsive Design --- */
  @media (max-width: 992px) {
    .detail-main-content {
      grid-template-columns: 1fr;
      gap: 4rem;
    }
  }
  
  @media (max-width: 768px) {
      .nav-btn {
          width: 40px;
          height: 40px;
          font-size: 1.2rem;
      }
      .prev-btn {
          left: 10px;
      }
      .next-btn {
          right: 10px;
      }
      .close-btn {
          top: 10px;
          right: 10px;
          font-size: 2rem;
      }
      .image-info {
          padding: 0.75rem;
          flex-direction: column;
          align-items: flex-start;
          gap: 0.5rem;
      }
      .image-caption-text {
          max-width: 100%;
      }
      .image-counter {
          align-self: flex-end;
      }
  }
  @media (max-width: 600px) {
    .detail-hero {
      padding: 2rem 1.5rem 3rem;
    }
    .detail-main-content,
    .photo-gallery-section {
      padding: 0 1.5rem 4rem;
    }
    .gallery-grid {
      grid-template-columns: 1fr;
    }
  }
  </style>