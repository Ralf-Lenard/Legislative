<template>
    <div class="home-page">
        <Navbar />

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="overlay">
                <div class="hero-content">
                    <div class="hero-accent-top"></div>
                    <h2>
                        Welcome to the Official Website of the Sangguniang Bayan
                    </h2>
                    <p class="motto">
                        Transparency • Integrity • Public Service
                    </p>
                    <div class="buttons">
                        <button class="btn-primary">Learn More</button>
                        <button class="btn-secondary">View Ordinances</button>
                    </div>
                    <div class="hero-accent-bottom"></div>
                </div>
            </div>
        </section>

        <!-- MESSAGE FROM PRESIDING OFFICER -->
        <section class="message">
            <div class="content">
                <div class="image-wrapper">
                    <img
                        src="https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg"
                        alt="Vice Mayor"
                    />
                    <div class="image-badge">Presiding Officer</div>
                </div>
                <div class="text">
                    <div class="section-label">Leadership Message</div>
                    <h3>Message from the Presiding Officer</h3>
                    <p>
                        "As the Presiding Officer of the Sangguniang Bayan, I am proud to present this platform for open governance and active citizen participation. Together, we will build a stronger, greener, and more inclusive Concepcion."
                    </p>
                    <button class="btn-primary">Read Full Message</button>
                </div>
            </div>
        </section>

        <!-- ABOUT SANGGUNIANG BAYAN -->
        <section class="about-sb">
            <div class="about-container">
                <div class="about-text">
                    <div class="section-label">Who We Are</div>
                    <h3>About the Sangguniang Bayan</h3>
                    <p>
                        The <strong>Sangguniang Bayan of Concepcion</strong> serves as the legislative body of the municipality, responsible for crafting ordinances, resolutions, and policies that promote transparency, good governance, and sustainable development. Guided by the values of <em>Integrity, Accountability, and Public Service</em>, we strive to uphold the trust of every Concepcionian.
                    </p>
                    <ul class="core-values">
                        <li><span class="check-icon">✓</span>Transparency in governance</li>
                        <li><span class="check-icon">✓</span>Inclusive community participation</li>
                        <li><span class="check-icon">✓</span>Sustainable growth and innovation</li>
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

        <!-- FEATURED SECTIONS (CARDS) -->
        <section class="featured">
            <div class="section-header">
                <div class="section-label">Explore</div>
                <h3>Key Resources</h3>
            </div>
            <div class="cards">
                <div class="card" v-for="item in features" :key="item.title">
                    <div class="card-image-wrapper">
                        <img :src="item.image" :alt="item.title" />
                        <div class="card-overlay"></div>
                    </div>
                    <div class="card-content">
                        <h4>{{ item.title }}</h4>
                        <p>{{ item.desc }}</p>
                        <button class="btn-secondary">View</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROGRAMS AND INITIATIVES -->
        <section class="programs">
            <div class="section-header">
                <div class="section-label">Initiatives</div>
                <h3>Programs & Initiatives</h3>
            </div>
            <div class="program-list">
                <div class="program-card">
                    <div class="icon-wrapper">
                        <div class="icon">🌳</div>
                    </div>
                    <h4>Green Concepcion</h4>
                    <p>
                        Environmental projects focused on tree planting, waste management, and clean-up drives.
                    </p>
                </div>
                <div class="program-card">
                    <div class="icon-wrapper">
                        <div class="icon">💡</div>
                    </div>
                    <h4>Transparency Portal</h4>
                    <p>
                        Access public records, ordinances, and budget reports to ensure open governance.
                    </p>
                </div>
                <div class="program-card">
                    <div class="icon-wrapper">
                        <div class="icon">🤝</div>
                    </div>
                    <h4>Community Engagement</h4>
                    <p>
                        Encouraging residents to participate in local governance and public consultations.
                    </p>
                </div>
            </div>
        </section>

        <!-- GALLERY -->
        <section class="gallery">
            <div class="section-header">
                <div class="section-label">Community</div>
                <h3>Community Highlights</h3>
            </div>
            <div class="gallery-grid">
                <img
                    v-for="(img, index) in gallery"
                    :key="index"
                    :src="img"
                    alt="Gallery Image"
                />
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section class="contact">
            <div class="info">
                <h3>Contact Us</h3>
                <div class="contact-item">
                    <span class="contact-icon">📍</span>
                    <p>Municipal Hall, Concepcion, Tarlac</p>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">📞</span>
                    <p>(045) 923-4567</p>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">📧</span>
                    <p>sangguniangbayan@concepcion.gov.ph</p>
                </div>
            </div>
            <iframe
                class="map"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d492.365013920174!2d120.65524349999999!3d15.324464299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396c27808ce141f%3A0xff3616ce0f24c62c!2sConcepcion%20Legislative%20Building!5e1!3m2!1sen!2sph!4v1731084631234!5m2!1sen!2sph"
                width="100%"
                height="450"
                style="
                    border: 0;
                    border-radius: 0 20px 20px 0;
                "
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </section>

        <FooterSection />
    </div>
</template>

<script>
import Navbar from '@/components/Home/Navbar.vue';
import FooterSection from '@/components/Home/Footer.vue';

export default {
    name: 'HomePage',
    components: {
        Navbar,
        FooterSection,
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

<style scoped>
/* ==========================================
   DESIGN SYSTEM & COLOR PALETTE
   ========================================== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Sora:wght@600;700;800&display=swap');

:root {
    /* Updated to vibrant apple green color system */
    --apple-green: #6DB33F;
    --apple-green-dark: #5a9c34;
    --apple-green-light: #8bc34a;
    --apple-green-lighter: #c8e6c9;
    --accent-gold: #FFB81C;
    --bg-light: #f8faf6;
    --bg-white: #ffffff;
    --text-dark: #1a1a1a;
    --text-gray: #666666;
    --border-light: #e8f0e3;
    --shadow-sm: 0 2px 8px rgba(109, 179, 63, 0.08);
    --shadow-md: 0 4px 16px rgba(109, 179, 63, 0.12);
    --shadow-lg: 0 10px 32px rgba(109, 179, 63, 0.15);
}

.home-page {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
    overflow-x: hidden;
}

section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 80px 20px;
}

/* ==========================================
   TYPOGRAPHY & HEADINGS
   ========================================== */
h2 {
    font-family: 'Sora', sans-serif;
    color: var(--text-dark);
    font-weight: 800;
    line-height: 1.15;
}

h3 {
    font-family: 'Sora', sans-serif;
    color: var(--apple-green);
    font-weight: 800;
    font-size: 2.5rem;
    margin-bottom: 30px;
    position: relative;
    display: inline-block;
}

h3::after {
    content: '';
    display: block;
    width: 80px;
    height: 5px;
    background: linear-gradient(90deg, var(--apple-green) 0%, var(--accent-gold) 100%);
    margin-top: 15px;
    border-radius: 3px;
}

h4 {
    color: var(--apple-green);
    font-weight: 700;
    margin-top: 0;
}

.section-label {
    display: inline-block;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--apple-green);
    margin-bottom: 12px;
    background: rgba(109, 179, 63, 0.1);
    padding: 6px 12px;
    border-radius: 20px;
}

.section-header {
    margin-bottom: 50px;
}

/* ==========================================
   BUTTONS
   ========================================== */
.btn-primary,
.btn-secondary {
    padding: 14px 36px;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 600;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
    display: inline-block;
}

.btn-primary {
    background-color: var(--apple-green);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-primary:hover {
    background-color: var(--apple-green-dark);
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: var(--accent-gold);
    color: var(--apple-green);
    box-shadow: var(--shadow-md);
}

.btn-secondary:hover {
    background-color: #ff9f1c;
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

/* ==========================================
   HERO SECTION
   ========================================== */
.hero {
    position: relative;
    background: linear-gradient(135deg, #f0f9eb 0%, #e8f5e9 100%);
    background-image: url('https://images.pexels.com/photos/3184299/pexels-photo-3184299.jpeg');
    background-size: cover;
    background-position: center;
    height: 85vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 40px 20px;
    max-width: calc(100% - 40px);
    border-radius: 25px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    animation: zoomHero 15s infinite alternate ease-in-out;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(90, 156, 52, 0.88) 0%, rgba(0, 0, 0, 0.75) 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(0px);
}

.hero-content {
    max-width: 800px;
    padding: 70px 60px;
    text-align: center;
    border: 3px solid var(--accent-gold);
    border-radius: 20px;
    background: rgba(0, 0, 0, 0.25);
    box-shadow: 0 0 50px rgba(109, 179, 63, 0.4);
    position: relative;
    backdrop-filter: blur(10px);
}

.hero-accent-top,
.hero-accent-bottom {
    position: absolute;
    width: 40px;
    height: 40px;
    border: 3px solid var(--accent-gold);
    border-radius: 10px;
}

.hero-accent-top {
    top: -15px;
    left: -15px;
    border-right: none;
    border-bottom: none;
}

.hero-accent-bottom {
    bottom: -15px;
    right: -15px;
    border-left: none;
    border-top: none;
}

.hero-content h2 {
    font-size: 3.8rem;
    margin-bottom: 25px;
    color: white;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
    line-height: 1.1;
}

.hero-content .motto {
    margin-bottom: 45px;
    font-size: 1.9rem;
    color: var(--accent-gold);
    font-weight: 700;
    letter-spacing: 3px;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
}

.buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

@keyframes zoomHero {
    from { background-size: 110%; }
    to { background-size: 125%; }
}

/* ==========================================
   MESSAGE SECTION
   ========================================== */
.message {
    padding: 90px 20px;
    background: white;
    border-radius: 25px;
    margin: 60px 20px;
    box-shadow: var(--shadow-md);
}

.message .content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 70px;
    max-width: 1100px;
    margin: 0 auto;
}

.image-wrapper {
    flex: 0 0 380px;
    position: relative;
}

.message img {
    width: 100%;
    height: 450px;
    object-fit: cover;
    border-radius: 20px;
    border: 6px solid var(--apple-green);
    box-shadow: 0 15px 40px rgba(109, 179, 63, 0.25);
    transition: transform 0.4s ease;
}

.message img:hover {
    transform: scale(1.02);
}

.image-badge {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background: var(--apple-green);
    color: white;
    padding: 12px 20px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: var(--shadow-lg);
}

.message .text {
    flex: 1;
    text-align: left;
}

.message .text h3 {
    font-size: 2.8rem;
    margin-bottom: 25px;
}

.message .text p {
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 30px;
    color: var(--text-gray);
    font-style: italic;
}

/* ==========================================
   ABOUT SECTION
   ========================================== */
.about-sb {
    background: linear-gradient(180deg, #f8faf6 0%, #f0f8eb 100%);
    padding: 90px 20px;
}

.about-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 80px;
    max-width: 1100px;
    margin: 0 auto;
}

.about-text {
    flex: 1;
}

.about-text p {
    font-size: 1.05rem;
    line-height: 1.8;
    color: var(--text-gray);
    margin-bottom: 25px;
}

.core-values {
    list-style: none;
    padding: 0;
    margin: 35px 0;
}

.core-values li {
    background: white;
    border-left: 5px solid var(--apple-green);
    padding: 16px 24px;
    margin-bottom: 12px;
    font-weight: 500;
    color: var(--text-dark);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.core-values li:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-md);
}

.check-icon {
    color: var(--apple-green);
    font-weight: 800;
    font-size: 1.2rem;
}

.about-image {
    flex: 1;
}

.about-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: var(--shadow-lg);
    border: 6px solid var(--accent-gold);
    transition: transform 0.4s ease;
}

.about-image img:hover {
    transform: scale(1.02);
}

/* ==========================================
   FEATURED CARDS SECTION
   ========================================== */
.featured {
    background: linear-gradient(180deg, #f8faf6 0%, #ffffff 100%);
    padding: 90px 20px;
}

.featured .cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 28px;
}

.card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    text-align: left;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-top: 6px solid var(--apple-green);
    position: relative;
}

.card:hover {
    transform: translateY(-12px);
    box-shadow: var(--shadow-lg);
    border-top-color: var(--accent-gold);
}

.card-image-wrapper {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.card-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.card:hover .card-image-wrapper img {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(109, 179, 63, 0.1);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card:hover .card-overlay {
    opacity: 1;
}

.card-content {
    padding: 28px;
}

.card-content h4 {
    font-size: 1.35rem;
    margin-bottom: 10px;
}

.card-content p {
    font-size: 0.95rem;
    margin-bottom: 22px;
    color: var(--text-gray);
    line-height: 1.6;
}

/* ==========================================
   PROGRAMS SECTION
   ========================================== */
.programs {
    background: linear-gradient(180deg, #f0f9eb 0%, #f8faf6 100%);
    padding: 90px 20px;
    text-align: center;
}

.program-list {
    display: flex;
    justify-content: center;
    gap: 32px;
    flex-wrap: wrap;
    margin-top: 50px;
}

.program-card {
    background: white;
    border-radius: 20px;
    padding: 45px 35px;
    width: 320px;
    box-shadow: var(--shadow-md);
    transition: all 0.4s ease;
    border-top: 6px solid var(--apple-green);
    text-align: center;
}

.program-card:hover {
    transform: translateY(-12px);
    border-top-color: var(--accent-gold);
    box-shadow: var(--shadow-lg);
}

.icon-wrapper {
    background: linear-gradient(135deg, rgba(109, 179, 63, 0.1), rgba(255, 184, 28, 0.05));
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    border: 3px solid var(--apple-green-lighter);
}

.icon {
    font-size: 2.5rem;
}

.program-card h4 {
    font-size: 1.4rem;
    margin-bottom: 15px;
}

.program-card p {
    color: var(--text-gray);
    line-height: 1.7;
    font-size: 1rem;
}

/* ==========================================
   GALLERY SECTION
   ========================================== */
.gallery {
    text-align: center;
    padding: 90px 20px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: 300px;
    gap: 18px;
}

.gallery-grid img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: var(--shadow-md);
    transition: all 0.4s ease;
    filter: brightness(0.95);
    cursor: pointer;
}

.gallery-grid img:hover {
    transform: scale(1.05) rotate(1deg);
    filter: brightness(1);
    box-shadow: var(--shadow-lg);
}

.gallery-grid img:nth-child(1) {
    grid-column: span 2;
}

.gallery-grid img:nth-child(4) {
    grid-row: span 2;
}

/* ==========================================
   CONTACT SECTION
   ========================================== */
.contact {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-between;
    align-items: stretch;
    background: linear-gradient(135deg, var(--apple-green) 0%, var(--apple-green-dark) 100%);
    color: white;
    border-radius: 25px;
    padding: 0;
    min-height: 450px;
    margin-bottom: 100px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.contact .info {
    flex: 1;
    padding: 70px 60px;
    text-align: left;
}

.contact .info h3 {
    color: var(--accent-gold);
    margin-bottom: 40px;
    font-size: 2.5rem;
}

.contact .info h3::after {
    background: var(--accent-gold);
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 28px;
}

.contact-icon {
    font-size: 1.8rem;
    min-width: 30px;
}

.contact-item p {
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.4;
}

.contact .map {
    flex: 1;
    width: 100%;
    border-radius: 0 25px 25px 0;
}

/* ==========================================
   RESPONSIVE DESIGN
   ========================================== */
@media (max-width: 1024px) {
    h3 {
        font-size: 2.2rem;
    }

    .hero-content h2 {
        font-size: 2.8rem;
    }

    .hero-content .motto {
        font-size: 1.5rem;
    }

    .featured .cards {
        grid-template-columns: repeat(2, 1fr);
    }

    .about-container {
        gap: 50px;
    }

    .message .content {
        gap: 50px;
    }

    .message img {
        width: 100%;
        height: 350px;
    }
}

@media (max-width: 768px) {
    section {
        padding: 60px 20px;
    }

    h3 {
        font-size: 1.8rem;
    }

    h3::after {
        width: 60px;
        height: 4px;
    }

    .hero {
        height: 60vh;
        margin: 20px;
    }

    .hero-content {
        padding: 40px 30px;
    }

    .hero-content h2 {
        font-size: 2rem;
    }

    .hero-content .motto {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }

    .buttons {
        flex-direction: column;
        gap: 15px;
    }

    .btn-primary,
    .btn-secondary {
        width: 100%;
    }

    .message {
        margin: 30px 0;
    }

    .message .content {
        flex-direction: column;
        text-align: center;
        gap: 30px;
    }

    .image-wrapper {
        flex: 1;
    }

    .message img {
        height: 300px;
    }

    .featured .cards {
        grid-template-columns: 1fr;
    }

    .about-container {
        flex-direction: column;
        text-align: center;
        gap: 40px;
    }

    .program-list {
        flex-direction: column;
        align-items: center;
    }

    .program-card {
        width: 100%;
        max-width: 380px;
    }

    .gallery-grid {
        grid-template-columns: 1fr 1fr;
        grid-auto-rows: 200px;
        gap: 12px;
    }

    .gallery-grid img:nth-child(1) {
        grid-column: span 1;
    }

    .gallery-grid img:nth-child(4) {
        grid-row: span 1;
    }

    .contact {
        flex-direction: column;
        border-radius: 20px;
        min-height: auto;
        margin-bottom: 60px;
    }

    .contact .map {
        order: -1;
        min-height: 300px;
        border-radius: 20px 20px 0 0;
    }

    .contact .info {
        padding: 40px 30px;
        text-align: center;
    }

    .contact-item {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .hero {
        height: 50vh;
    }

    .hero-content h2 {
        font-size: 1.6rem;
    }

    .hero-content .motto {
        font-size: 1rem;
    }

    h3 {
        font-size: 1.5rem;
    }

    .gallery-grid {
        grid-template-columns: 1fr;
        grid-auto-rows: 180px;
    }
}
</style>
