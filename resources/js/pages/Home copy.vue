<template>
    <div class="home-page">
      <Navbar />
  
      <section class="hero">
        <div class="overlay">
          <div class="hero-content">
            <h2>Welcome to the Official Website of the Sangguniang Bayan</h2>
            <p class="motto">Transparency • Integrity • Public Service</p>
            <div class="buttons">
              <button class="btn-primary">Learn More</button>
              <button class="btn-secondary">View Ordinances</button>
            </div>
          </div>
        </div>
      </section>
  
      <section class="message">
        <div class="content">
          <img src="https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg" alt="Vice Mayor" />
          <div class="text">
            <h3>Message from the Presiding Officer</h3>
            <p>
              "As the Presiding Officer of the Sangguniang Bayan, I am proud to
              present this platform for open governance and active citizen
              participation. Together, we will build a stronger, greener, and more
              inclusive Concepcion."
            </p>
            <button class="btn-primary">Read Full Message</button>
          </div>
        </div>
      </section>
  
      <section class="about-sb">
        <div class="about-container">
          <div class="about-text">
            <h3>About the Sangguniang Bayan</h3>
            <p>
              The <strong>Sangguniang Bayan of Concepcion</strong> serves as the
              legislative body of the municipality, responsible for crafting
              ordinances, resolutions, and policies that promote transparency,
              good governance, and sustainable development. Guided by the values
              of <em>Integrity, Accountability, and Public Service</em>, we strive
              to uphold the trust of every Concepcionian.
            </p>
            <ul class="core-values">
              <li>✅ Transparency in governance</li>
              <li>✅ Inclusive community participation</li>
              <li>✅ Sustainable growth and innovation</li>
            </ul>
            <button class="btn-primary">Learn More About Us</button>
          </div>
          <div class="about-image">
            <img
              src="https://images.pexels.com/photos/3184644/pexels-photo-3184644.jpeg"
              alt="Sangguniang Bayan Building"
            />
          </div>
        </div>
      </section>
  
      <section class="featured">
        <h3>Explore</h3>
        <div class="cards">
          <div class="card" v-for="item in features" :key="item.title">
            <img :src="item.image" :alt="item.title" />
            <div class="card-content">
              <h4>{{ item.title }}</h4>
              <p>{{ item.desc }}</p>
              <button class="btn-secondary">View</button>
            </div>
          </div>
        </div>
      </section>
  
      <section class="programs">
        <h3>Programs and Initiatives</h3>
        <div class="program-list">
          <div class="program-card">
            <div class="icon">🌳</div>
            <h4>Green Concepcion</h4>
            <p>
              Environmental projects focused on tree planting, waste management,
              and clean-up drives.
            </p>
          </div>
          <div class="program-card">
            <div class="icon">💡</div>
            <h4>Transparency Portal</h4>
            <p>
              Access public records, ordinances, and budget reports to ensure open
              governance.
            </p>
          </div>
          <div class="program-card">
            <div class="icon">🤝</div>
            <h4>Community Engagement</h4>
            <p>
              Encouraging residents to participate in local governance and public
              consultations.
            </p>
          </div>
        </div>
      </section>
  
      <section class="gallery">
        <h3>Community Highlights</h3>
        <div class="gallery-grid">
          <img
            v-for="(img, index) in gallery"
            :key="index"
            :src="img"
            alt="Gallery Image"
          />
        </div>
      </section>
  
      <section class="contact">
        <div class="info">
          <h3>Contact Us</h3>
          <p>📍 Municipal Hall, Concepcion, Tarlac</p>
          <p>📞 (045) 923-4567</p>
          <p>📧 sangguniangbayan@concepcion.gov.ph</p>
        </div>
        <iframe
          class="map"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d492.365013920174!2d120.65524349999999!3d15.324464299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396c27808ce141f%3A0xff3616ce0f24c62c!2sConcepcion%20Legislative%20Building!5e1!3m2!1sen!2sph!4v1731084631234!5m2!1sen!2sph"
          style="border: 0; border-radius: 15px; box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
      </section>
  
      <FooterSection />
    </div>
  </template>
  
  <script>
  import Navbar from "@/components/Home/Navbar.vue";
  import FooterSection from "@/components/Home/Footer.vue";
  
  export default {
    name: "HomePage",
    components: {
      Navbar,
      FooterSection,
    },
    data() {
      return {
        features: [
          {
            title: "Ordinances",
            desc: "View all approved local ordinances.",
            image: "https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg",
          },
          {
            title: "Resolutions",
            desc: "Access official resolutions passed by the council.",
            image: "https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg",
          },
          {
            title: "Members",
            desc: "Meet the Sangguniang Bayan members.",
            image: "https://images.pexels.com/photos/1181395/pexels-photo-1181395.jpeg",
          },
          {
            title: "Public Sessions",
            desc: "See upcoming and past sessions.",
            image: "https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg",
          },
        ],
        gallery: [
          "https://images.pexels.com/photos/3184300/pexels-photo-3184300.jpeg",
          "https://images.pexels.com/photos/3184312/pexels-photo-3184312.jpeg",
          "https://images.pexels.com/photos/3184396/pexels-photo-3184396.jpeg",
          "https://images.pexels.com/photos/3184436/pexels-photo-3184436.jpeg",
          "https://images.pexels.com/photos/3184454/pexels-photo-3184454.jpeg",
          "https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg",
        ],
      };
    },
  };
  </script>
  
  <style>
  /* Global Styles */
  @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");
  
  :root {
    --primary-green: #1b5e20; /* Darker, formal green */
    --secondary-green: #388e3c; /* Medium green */
    --accent-gold: #ffc107; /* Bright gold for accent */
    --bg-light: #f9f9f9; /* Off-white for background */
    --text-dark: #212121;
    --shadow-color: rgba(27, 94, 32, 0.15);
  }
  
  .home-page {
    font-family: "Poppins", sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
    overflow-x: hidden;
    margin-top: 100px;
  }
  
  section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3.75rem 1.25rem;
  }
  
  h2,
  h3 {
    color: var(--primary-green);
    font-weight: 700;
  }
  
  h3 {
    font-size: 2.5rem;
    margin-bottom: 1.875rem;
    position: relative;
    display: inline-block;
  }
  
  /* Enhanced Section Title Underline */
  h3::after {
    content: "";
    display: block;
    width: 3.75rem;
    height: 4px;
    background-color: var(--accent-gold);
    margin: 0.625rem 0 0;
    border-radius: 2px;
  }
  
  /* --- Buttons --- */
  .btn-primary,
  .btn-secondary {
    padding: 0.875rem 2.1875rem;
    border-radius: 3.125rem;
    cursor: pointer;
    font-weight: 600;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
  }
  
  .btn-primary {
    background-color: var(--primary-green);
    color: white;
    border-color: var(--primary-green);
  }
  
  .btn-primary:hover {
    background-color: #104414;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    transform: translateY(-0.1875rem);
  }
  
  .btn-secondary {
    background-color: var(--accent-gold);
    color: var(--primary-green);
    border-color: var(--accent-gold);
    margin-left: 0.9375rem;
  }
  
  .btn-secondary:hover {
    background-color: #e6b000;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    transform: translateY(-0.1875rem);
  }
  
  /* ===== HERO SECTION STYLES ===== */
  .hero {
    position: relative;
    background-image: url("https://images.pexels.com/photos/3184299/pexels-photo-3184299.jpeg");
    background-size: cover;
    background-position: center;
    height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 1.875rem auto;
    max-width: 95%;
    border-radius: 1.25rem;
    overflow: hidden;
    animation: zoomHero 15s infinite alternate ease-in-out;
  }
/*   
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(27, 94, 32, 0.85) 0%, rgba(0, 0, 0, 0.7) 100%);
    display: flex;
    justify-content: center;
    align-items: center;
  } */
  
  .hero-content {
    max-width: 56.25rem;
    padding: 3.75rem;
    text-align: center;
    border: 3px solid var(--accent-gold);
    border-radius: 0.9375rem;
    background: rgba(0, 0, 0, 0.2);
    box-shadow: 0 0 40px rgba(27, 94, 32, 0.7);
  }
  
  .hero-content h2 {
    font-size: 3.5rem;
    margin-bottom: 1.25rem;
    color: white;
    font-weight: 800;
    text-transform: capitalize;
    line-height: 1.1;
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
  }
  
  .hero-content .motto {
    margin-bottom: 2.5rem;
    font-size: 1.8rem;
    color: var(--accent-gold);
    font-weight: 600;
    letter-spacing: 2px;
  }
  
  .buttons {
    display: flex;
    gap: 0.9375rem;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  /* --- MESSAGE SECTION --- */
  .message {
    padding: 5rem 1.25rem;
  }
  
  .message .content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 3.75rem;
    max-width: 62.5rem;
    margin: 0 auto;
    background: white;
    padding: 2.5rem;
    border-radius: 1.25rem;
    box-shadow: 0 15px 40px var(--shadow-color);
  }
  
  .message img {
    width: 25rem;
    height: 28.125rem;
    object-fit: cover;
    border-radius: 0.9375rem;
    border: 5px solid var(--primary-green);
    box-shadow: 10px 10px 0 0 var(--accent-gold);
  }
  
  .message .text {
    flex: 1;
    text-align: left;
  }
  
  .message .text h3 {
    font-size: 2.5rem;
    margin-bottom: 1.25rem;
  }
  
  .message .text p {
    font-style: italic;
    font-size: 1.15rem;
    line-height: 1.7;
    margin-bottom: 1.875rem;
    color: #444;
  }
  
  /* --- ABOUT SANGGUNIANG BAYAN --- */
  .about-sb {
    background: linear-gradient(180deg, #ffffff, #f1f8e9);
    padding: 5rem 1.25rem;
  }
  
  .about-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 3.75rem;
    max-width: 68.75rem;
    margin: 0 auto;
  }
  
  .about-text {
    flex: 1;
  }
  
  .about-text p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #444;
    margin-bottom: 1.25rem;
  }
  
  .core-values {
    list-style: none;
    padding: 0;
    margin-bottom: 1.5625rem;
  }
  
  .core-values li {
    background: #e8f5e9;
    border-left: 5px solid var(--primary-green);
    padding: 0.75rem 1.25rem;
    margin-bottom: 0.625rem;
    font-weight: 500;
    color: var(--text-dark);
    border-radius: 0.5rem;
  }
  
  .about-image {
    flex: 1;
  }
  
  .about-image img {
    width: 100%;
    border-radius: 0.9375rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    border: 4px solid var(--accent-gold);
  }
  
  /* --- FEATURED SECTIONS (Cards) --- */
  .featured {
    background: linear-gradient(180deg, var(--bg-light), #e8f5e9);
    text-align: center;
  }
  
  .featured .cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.875rem;
  }
  
  .card {
    background: white;
    border-radius: 0.9375rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: left;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-top: 8px solid var(--primary-green);
  }
  
  .card:hover {
    transform: translateY(-0.625rem);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    border-top-color: var(--accent-gold);
  }
  
  .card img {
    width: 100%;
    height: 11.25rem;
    object-fit: cover;
  }
  
  .card-content {
    padding: 1.5625rem;
  }
  
  .card-content h4 {
    color: var(--primary-green);
    font-weight: 700;
    margin-top: 0;
    margin-bottom: 0.5rem;
    font-size: 1.3rem;
  }
  
  .card-content p {
    font-size: 0.95rem;
    margin-bottom: 1.25rem;
    color: #555;
    height: 2.5rem;
    overflow: hidden;
  }
  
  /* --- PROGRAMS & INITIATIVES --- */
  .programs {
    text-align: center;
    background: #f9fbe7;
    padding: 5rem 1.25rem;
  }
  
  .program-list {
    display: flex;
    justify-content: center;
    gap: 1.875rem;
    flex-wrap: wrap;
    margin-top: 2.5rem;
  }
  
  .program-card {
    background: white;
    border-radius: 0.9375rem;
    padding: 2.5rem 1.875rem;
    width: 20rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    border-top: 6px solid var(--primary-green);
  }
  
  .program-card:hover {
    transform: translateY(-0.625rem);
    border-top-color: var(--accent-gold);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
  }
  
  .program-card .icon {
    font-size: 3rem;
    margin-bottom: 1.25rem;
  }
  
  .program-card h4 {
    color: var(--primary-green);
    font-size: 1.4rem;
    margin-bottom: 0.625rem;
  }
  
  .program-card p {
    color: #555;
    line-height: 1.6;
    font-size: 1rem;
  }
  
  /* --- GALLERY --- */
  .gallery {
    text-align: center;
    padding-bottom: 5rem;
  }
  
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: 17.5rem;
    gap: 0.9375rem;
  }
  
  .gallery-grid img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.625rem;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.4s ease-out;
    filter: brightness(0.95);
  }
  
  .gallery-grid img:hover {
    transform: scale(1.05) rotate(1deg);
    opacity: 1;
    filter: brightness(1);
    z-index: 10;
  }
  
  .gallery-grid img:nth-child(1) {
    grid-column: span 2;
  }
  
  .gallery-grid img:nth-child(4) {
    grid-row: span 2;
  }
  
  /* --- CONTACT --- */
  .contact {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    align-items: stretch;
    background: var(--primary-green);
    color: white;
    border-radius: 1.25rem;
    padding: 0;
    min-height: 28.125rem;
    margin-bottom: 6.25rem;
  }
  
  .contact .info {
    flex: 1;
    padding: 3.75rem;
    text-align: left;
  }
  
  .contact .info h3 {
    color: var(--accent-gold);
    margin-bottom: 1.875rem;
    font-size: 2.5rem;
  }
  
  .contact .info p {
    font-size: 1.15rem;
    margin-bottom: 1.25rem;
    font-weight: 400;
    line-height: 1.4;
  }
  
  .contact .map {
    flex: 1;
    width: 100%;
    border-radius: 0 1.25rem 1.25rem 0;
    filter: grayscale(0.2) invert(0.05);
  }
  
  /* Comprehensive mobile-first responsive design */
  @media (max-width: 1280px) {
    section {
      padding: 3rem 1.25rem;
    }
  
    h3 {
      font-size: 2.2rem;
    }
  
    .featured .cards {
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }
  
    .program-card {
      width: 18rem;
    }
  
    .hero-content h2 {
      font-size: 2.8rem;
    }
  
    .hero-content .motto {
      font-size: 1.5rem;
    }
  }
  
  @media (max-width: 1024px) {
    section {
      padding: 2.5rem 1.25rem;
    }
  
    h3 {
      font-size: 1.875rem;
    }
  
    .featured .cards {
      grid-template-columns: repeat(2, 1fr);
      gap: 1.25rem;
    }
  
    .program-list {
      gap: 1.25rem;
    }
  
    .program-card {
      width: 100%;
      max-width: 18rem;
    }
  
    .message .content {
      gap: 2rem;
      padding: 2rem;
    }
  
    .message img {
      width: 18rem;
      height: 22rem;
    }
  
    .message .text h3 {
      font-size: 1.875rem;
    }
  
    .about-container {
      gap: 2.5rem;
    }
  
    .hero-content {
      padding: 2.5rem;
      max-width: 90%;
      border: 2px solid var(--accent-gold);
    }
  
    .hero-content h2 {
      font-size: 2.2rem;
    }
  
    .hero-content .motto {
      font-size: 1.25rem;
    }
  }
  
  @media (max-width: 768px) {
    section {
      padding: 2.5rem 1rem;
    }
  
    h3 {
      font-size: 1.5rem;
    }
  
    h3::after {
      width: 2.5rem;
    }
  
    /* Hero */
    .hero {
      height: 60vh;
      margin: 1rem;
      border-radius: 1rem;
    }
  
    .hero-content {
      padding: 2rem;
      border: 2px solid var(--accent-gold);
    }
  
    .hero-content h2 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
  
    .hero-content .motto {
      font-size: 1rem;
      margin-bottom: 1.5rem;
    }
  
    .buttons {
      flex-direction: column;
      gap: 0.75rem;
    }
  
    .btn-primary,
    .btn-secondary {
      padding: 0.625rem 1.25rem;
      font-size: 0.8rem;
      margin-left: 0 !important;
      width: 100%;
    }
  
    /* Message */
    .message {
      padding: 2.5rem 1rem;
    }
  
    .message .content {
      flex-direction: column;
      text-align: center;
      gap: 2rem;
      padding: 1.5rem;
    }
  
    .message img {
      width: 100%;
      height: auto;
      max-width: 20rem;
      border: 3px solid var(--primary-green);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
  
    .message .text h3 {
      font-size: 1.25rem;
    }
  
    .message .text p {
      font-size: 1rem;
    }
  
    /* About */
    .about-sb {
      padding: 2.5rem 1rem;
    }
  
    .about-container {
      flex-direction: column;
      text-align: center;
      gap: 1.5rem;
    }
  
    .about-text p {
      font-size: 1rem;
    }
  
    /* Featured */
    .featured .cards {
      grid-template-columns: 1fr;
      gap: 1rem;
    }
  
    .card:hover {
      transform: translateY(-0.3rem);
    }
  
    /* Programs */
    .programs {
      padding: 2.5rem 1rem;
    }
  
    .program-list {
      gap: 1rem;
    }
  
    .program-card {
      width: 100%;
      padding: 2rem 1.5rem;
    }
  
    /* Gallery */
    .gallery {
      padding-bottom: 2.5rem;
    }
  
    .gallery-grid {
      grid-template-columns: repeat(2, 1fr);
      grid-auto-rows: 12.5rem;
      gap: 0.75rem;
    }
  
    .gallery-grid img:nth-child(1) {
      grid-column: span 1;
    }
  
    .gallery-grid img:nth-child(4) {
      grid-row: span 1;
    }
  
    /* Contact */
    .contact {
      flex-direction: column;
      border-radius: 1rem;
      min-height: auto;
      margin-bottom: 3rem;
    }
  
    .contact .map {
      order: -1;
      min-height: 15.625rem;
      border-radius: 1rem 1rem 0 0;
    }
  
    .contact .info {
      padding: 2rem;
      text-align: center;
    }
  
    .contact .info h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
  
    .contact .info p {
      font-size: 1rem;
      margin-bottom: 0.75rem;
    }
  }
  
  @media (max-width: 480px) {
    section {
      padding: 2rem 0.75rem;
    }
  
    h3 {
      font-size: 1.25rem;
      margin-bottom: 1.25rem;
    }
  
    h3::after {
      width: 2rem;
      height: 3px;
      margin-top: 0.5rem;
    }
  
    /* Hero */
    .hero {
      height: 50vh;
      margin: 0.75rem;
      border-radius: 0.75rem;
    }
  
    .hero-content {
      padding: 1.5rem;
    }
  
    .hero-content h2 {
      font-size: 1.25rem;
      line-height: 1.3;
    }
  
    .hero-content .motto {
      font-size: 0.9rem;
      margin-bottom: 1.25rem;
    }
  
    .buttons {
      gap: 0.5rem;
    }
  
    .btn-primary,
    .btn-secondary {
      padding: 0.5rem 1rem;
      font-size: 0.75rem;
    }
  
    /* Message */
    .message {
      padding: 2rem 0.75rem;
    }
  
    .message .content {
      padding: 1.25rem;
      gap: 1.25rem;
    }
  
    .message img {
      max-width: 18rem;
    }
  
    .message .text h3 {
      font-size: 1.1rem;
    }
  
    .message .text p {
      font-size: 0.9rem;
      line-height: 1.5;
    }
  
    /* Core values */
    .core-values li {
      padding: 0.625rem 1rem;
      font-size: 0.9rem;
    }
  
    /* Card */
    .card-content h4 {
      font-size: 1.1rem;
    }
  
    .card-content p {
      font-size: 0.85rem;
    }
  
    /* Program card */
    .program-card {
      padding: 1.5rem 1.25rem;
    }
  
    .program-card .icon {
      font-size: 2.5rem;
    }
  
    .program-card h4 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }
  
    .program-card p {
      font-size: 0.9rem;
    }
  
    /* Gallery */
    .gallery-grid {
      grid-template-columns: 1fr;
      grid-auto-rows: 12.5rem;
      gap: 0.5rem;
    }
  
    /* Contact */
    .contact .map {
      min-height: 12.5rem;
    }
  
    .contact .info {
      padding: 1.5rem;
    }
  
    .contact .info h3 {
      font-size: 1.25rem;
    }
  
    .contact .info p {
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }
  }
  
  @keyframes zoomHero {
    from {
      background-size: 110%;
    }
    to {
      background-size: 125%;
    }
  }
  </style>
  