<template>
    <div class="home-page">
        <Navbar />

        <section class="hero">
            <div class="overlay">
                <div class="hero-content">
                    <h2>
                        Welcome to the Official Website of the Sangguniang Bayan
                    </h2>
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
                <img
                    src="https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg"
                    alt="Vice Mayor"
                />
                <div class="text">
                    <h3>Message from the Presiding Officer</h3>
                    <p>
                        “As the Presiding Officer of the Sangguniang Bayan, I am
                        proud to present this platform for open governance and
                        active citizen participation. Together, we will build a
                        stronger, greener, and more inclusive Concepcion.”
                    </p>
                    <button class="btn-primary">Read Full Message</button>
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
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3857.0985292350467!2d120.650871!3d15.330221!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f7027e04c50b%3A0x19ee99413dc24d2b!2sConcepcion%2C%20Tarlac!5e0!3m2!1sen!2sph!4v1730702400000!5m2!1sen!2sph"
                allowfullscreen=""
                loading="lazy"
            ></iframe>
        </section>

        <footer>
            <p>
                © 2025 Municipality of Concepcion, Tarlac | All Rights Reserved
            </p>
        </footer>
    </div>
</template>

<script>
import Navbar from '@/components/Home/Navbar.vue';

export default {
    name: 'HomePage',
    components: {
        Navbar,
    },
    data() {
        return {
            features: [
                {
                    title: 'Ordinances',
                    desc: 'View all approved local ordinances.',
                    image: 'https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg',
                },
                {
                    title: 'Resolutions',
                    desc: 'Access official resolutions passed by the council.',
                    image: 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg',
                },
                {
                    title: 'Members',
                    desc: 'Meet the Sangguniang Bayan members.',
                    image: 'https://images.pexels.com/photos/1181395/pexels-photo-1181395.jpeg',
                },
                {
                    title: 'Public Sessions',
                    desc: 'See upcoming and past sessions.',
                    image: 'https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg',
                },
            ],
            gallery: [
                'https://images.pexels.com/photos/3184300/pexels-photo-3184300.jpeg',
                'https://images.pexels.com/photos/3184312/pexels-photo-3184312.jpeg',
                'https://images.pexels.com/photos/3184396/pexels-photo-3184396.jpeg',
                'https://images.pexels.com/photos/3184436/pexels-photo-3184436.jpeg',
                'https://images.pexels.com/photos/3184454/pexels-photo-3184454.jpeg',
                'https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg',
            ],
        };
    },
};
</script>

<style>
/* Global Styles */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root {
  --primary-green: #1b5e20; /* Darker, formal green */
  --secondary-green: #388e3c; /* Medium green */
  --accent-gold: #ffc107; /* Bright gold for accent */
  --bg-light: #f9f9f9; /* Off-white for background */
  --text-dark: #212121;
  --shadow-color: rgba(27, 94, 32, 0.15);
}

.home-page {
  font-family: 'Poppins', sans-serif;
  background-color: var(--bg-light);
  color: var(--text-dark);
  overflow-x: hidden;
}

section {
  max-width: 1200px;
  margin: 0 auto;
  padding: 60px 20px;
}

h2, h3 {
  color: var(--primary-green);
  font-weight: 700;
}

h3 {
  font-size: 2.5rem;
  margin-bottom: 30px;
  position: relative;
  display: inline-block;
}

/* Enhanced Section Title Underline */
h3::after {
  content: '';
  display: block;
  width: 60px;
  height: 4px;
  background-color: var(--accent-gold);
  margin: 10px 0 0;
  border-radius: 2px;
}

/* --- Buttons --- */
.btn-primary,
.btn-secondary {
  padding: 14px 35px;
  border-radius: 50px;
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
  transform: translateY(-3px);
}

.btn-secondary {
  background-color: var(--accent-gold);
  color: var(--primary-green);
  border-color: var(--accent-gold);
  margin-left: 15px;
}
.btn-secondary:hover {
  background-color: #e6b000;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
  transform: translateY(-3px);
}

/* =====================================
--- IMPROVED HERO SECTION STYLES --- 
=====================================
*/
.hero {
  position: relative;
  background-image: url('https://images.pexels.com/photos/3184299/pexels-photo-3184299.jpeg');
  background-size: cover;
  background-position: center;
  height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 30px auto;
  max-width: 95%;
  border-radius: 20px;
  overflow: hidden;
  animation: zoomHero 15s infinite alternate ease-in-out;
}

/* Full-coverage overlay with a powerful gradient */
.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* Gradient from dark green to black, enhancing depth */
  background: linear-gradient(
    135deg,
    rgba(27, 94, 32, 0.85) 0%,
    rgba(0, 0, 0, 0.7) 100%
  );
  display: flex;
  justify-content: center;
  align-items: center;
}

.hero-content {
    /* Content box for better focus */
    max-width: 900px;
    padding: 60px;
    text-align: center;
    border: 3px solid var(--accent-gold); /* Prominent gold border */
    border-radius: 15px;
    background: rgba(0, 0, 0, 0.2); /* Slight inner shadow/background */
    box-shadow: 0 0 40px rgba(27, 94, 32, 0.7); /* Green glow effect */
}

.hero-content h2 {
  font-size: 3.5rem; /* Larger and bolder */
  margin-bottom: 20px;
  color: white;
  font-weight: 800;
  text-transform: capitalize;
  line-height: 1.1;
  text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
}

.hero-content .motto {
  margin-bottom: 40px;
  font-size: 1.8rem;
  color: var(--accent-gold); /* Primary motto color */
  font-weight: 600;
  letter-spacing: 2px; /* Emphasize the motto */
}

/* =====================================
--- END IMPROVED HERO SECTION STYLES --- 
=====================================
*/


/* --- MESSAGE SECTION --- */
.message {
  padding: 80px 20px;
}
.message .content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 60px;
  max-width: 1000px;
  margin: 0 auto;
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 15px 40px var(--shadow-color);
}

.message img {
  width: 400px;
  height: 450px;
  object-fit: cover;
  border-radius: 15px;
  border: 5px solid var(--primary-green);
  box-shadow: 10px 10px 0 0 var(--accent-gold);
}

.message .text {
  flex: 1;
  text-align: left;
}
.message .text h3 {
  font-size: 2.5rem;
  margin-bottom: 20px;
}
.message .text p {
  font-style: italic;
  font-size: 1.15rem;
  line-height: 1.7;
  margin-bottom: 30px;
  color: #444;
}

/* --- FEATURED SECTIONS (Cards) --- */
.featured {
  background: linear-gradient(180deg, var(--bg-light), #e8f5e9);
  text-align: center;
}

.featured .cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
}

.card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  text-align: left;
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  border-top: 8px solid var(--primary-green);
}

.card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
  border-top-color: var(--accent-gold);
}

.card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.card-content {
  padding: 25px;
}
.card-content h4 {
  color: var(--primary-green);
  font-weight: 700;
  margin-top: 0;
  margin-bottom: 8px;
  font-size: 1.3rem;
}
.card-content p {
  font-size: 0.95rem;
  margin-bottom: 20px;
  color: #555;
  height: 40px;
  overflow: hidden;
}

/* --- GALLERY --- */
.gallery {
  text-align: center;
  padding-bottom: 80px;
}
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-auto-rows: 280px;
  gap: 15px;
}

.gallery-grid img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
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
  border-radius: 20px;
  padding: 0;
  min-height: 450px;
}

.contact .info {
  flex: 1;
  padding: 60px;
  text-align: left;
}
.contact .info h3 {
  color: var(--accent-gold);
  margin-bottom: 30px;
  font-size: 2.5rem;
}
.contact .info p {
  font-size: 1.15rem;
  margin-bottom: 20px;
  font-weight: 400;
  line-height: 1.4;
}
.contact .map {
  flex: 1;
  width: 100%;
  border-radius: 0 20px 20px 0;
  filter: grayscale(0.2) invert(0.05);
}

/* --- FOOTER --- */
footer {
  text-align: center;
  background-color: #104012;
  color: white;
  padding: 30px;
  margin-top: 50px;
  font-size: 1rem;
  letter-spacing: 0.5px;
}


/* --- RESPONSIVE ADJUSTMENTS --- */
@media (max-width: 1024px) {
  /* Hero */
  .hero-content { padding: 40px; }
  .hero-content h2 { font-size: 2.8rem; }
  .hero-content .motto { font-size: 1.5rem; }
  
  /* Featured */
  .featured .cards {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  section { padding: 40px 20px; }
  h3 { font-size: 2rem; }
  
  /* Hero */
  .hero { height: 65vh; margin: 15px; }
  .hero-content { 
      padding: 30px; 
      max-width: 100%; 
      border: 2px solid var(--accent-gold);
      box-shadow: none;
  }
  .hero-content h2 { font-size: 2rem; }
  .hero-content .motto { font-size: 1.2rem; margin-bottom: 25px; }
  .btn-primary, .btn-secondary { padding: 10px 20px; font-size: 0.85rem; }
  .btn-secondary { margin-left: 0; margin-top: 10px; }
  .buttons { display: flex; flex-direction: column; }

  /* Message */
  .message .content {
    flex-direction: column;
    text-align: center;
    gap: 30px;
  }
  .message img {
    width: 100%;
    height: 300px;
    max-width: 350px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border: 3px solid var(--primary-green);
  }
  .message .text {
    text-align: center;
  }
  .message .text h3 {
    font-size: 1.8rem;
  }
  
  /* Featured */
  .featured .cards {
    grid-template-columns: 1fr;
  }
  .card:hover { transform: translateY(-5px); }

  /* Gallery */
  .gallery-grid {
    grid-template-columns: 1fr 1fr;
    grid-auto-rows: 180px;
    gap: 10px;
  }
  .gallery-grid img:nth-child(1) { grid-column: span 1; }
  .gallery-grid img:nth-child(4) { grid-row: span 1; }

  /* Contact */
  .contact {
    flex-direction: column;
    border-radius: 15px;
    min-height: auto;
  }
  .contact .map {
    order: -1;
    min-height: 250px;
    border-radius: 15px 15px 0 0;
  }
  .contact .info {
    padding: 30px;
    text-align: center;
  }
  .contact .info h3::after { margin: 10px auto 0; }
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