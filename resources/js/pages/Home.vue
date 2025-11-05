<template>
    <div class="home-page">
        <!-- HEADER -->
    
        <Navbar />

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="overlay">
                <h2>
                    Welcome to the Official Website of the Sangguniang Bayan
                </h2>
                <p>Transparency • Integrity • Public Service</p>
                <div class="buttons">
                    <button class="btn-primary">Learn More</button>
                    <button class="btn-secondary">View Ordinances</button>
                </div>
            </div>
        </section>

        <!-- MESSAGE SECTION -->
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

        <!-- FEATURED SECTIONS -->
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

        <!-- GALLERY -->
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

        <!-- CONTACT -->
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

        <!-- FOOTER -->
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
/* Global Variables for the page */
:root {
  --primary-green: #1b5e20;
  --secondary-green: #388e3c;
  --accent-gold: #FFC107;
  --bg-light: #F5F5F5;
  --text-dark: #212121;
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
    font-size: 2rem;
    margin-bottom: 30px;
    position: relative;
    display: inline-block;
}

h3::after {
    content: '';
    display: block;
    width: 40%;
    height: 3px;
    background-color: var(--accent-gold);
    margin: 5px auto 0;
    border-radius: 2px;
}

/* --- Buttons --- */
.btn-primary,
.btn-secondary {
    padding: 12px 30px; /* Larger hit area */
    border-radius: 50px; /* Pill shape */
    cursor: pointer;
    font-weight: 600;
    border: 2px solid transparent; /* Add border for consistent sizing */
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.95rem;
}

.btn-primary {
    background-color: var(--primary-green);
    color: white;
    border-color: var(--primary-green);
}
.btn-primary:hover {
    background-color: #0f4012; /* Darker hover */
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: var(--accent-gold);
    color: var(--primary-green);
    border-color: var(--accent-gold);
    margin-left: 15px;
}
.btn-secondary:hover {
    background-color: #ffaa00;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

.hero {
position: relative;
background-image: url('https://images.pexels.com/photos/3184299/pexels-photo-3184299.jpeg');
background-size: cover;
background-position: center;
height: 70vh;
display: flex; /* ADDED/CONFIRMED */
justify-content: center; /* ADDED/CONFIRMED */
align-items: center; /* ADDED/CONFIRMED */
/* Reduced margin to maximize space, added horizontal auto margin for centering */
margin: 30px auto; 
max-width: 95%; /* Use a percentage for better scaling */
border-radius: 20px;
animation: zoomHero 15s infinite alternate ease-in-out;
}

.overlay {
background: rgba(0, 0, 0, 0.65); /* Darker overlay for better text visibility */
padding: 60px;
border-radius: 20px;
max-width: 90%;
    /* Ensure content is centered inside the overlay as well */
    display: flex; 
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.overlay h2 {
font-size: 2.5rem; /* Larger, more impactful heading */
margin-bottom: 15px;
color: white;
font-weight: 800;
 text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
}

.overlay p {
margin-bottom: 30px;
font-size: 1.25rem;
color: var(--accent-gold); /* Accent color for motto */
 font-weight: 500;
}

/* --- MESSAGE SECTION --- */
.message {
    padding: 80px 20px;
}
.message .content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 50px;
    margin: 0;
    max-width: 1000px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.message img {
    width: 350px; /* Slightly larger image */
    height: 400px;
    object-fit: cover;
    border-radius: 15px;
    /* Unique border effect */
    border: 5px solid var(--primary-green);
    box-shadow: 10px 10px 0 0 var(--accent-gold); /* Offset gold shadow */
}

.message .text {
    flex: 1;
    text-align: left;
}
.message .text h3 {
    color: var(--primary-green);
    font-size: 2.2rem;
    margin-bottom: 20px;
}
.message .text p {
    font-style: italic;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 30px;
    color: #555;
}

/* --- FEATURED SECTIONS (Cards) --- */
.featured {
    background: linear-gradient(180deg, var(--bg-light), #e0f2f1); /* Subtle gradient background */
    text-align: center;
}

.featured .cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Fixed 4 columns on desktop */
    gap: 30px;
}

.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: left;
    transition: all 0.3s;
    border-bottom: 5px solid var(--primary-green); /* Accent line */
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
    border-bottom-color: var(--accent-gold);
}

.card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.card-content {
    padding: 20px;
}
.card-content h4 {
    color: var(--primary-green);
    font-weight: 700;
    margin-top: 0;
    margin-bottom: 5px;
}
.card-content p {
    font-size: 0.9rem;
    margin-bottom: 15px;
    color: #666;
    height: 36px; /* Ensure consistent height for description */
    overflow: hidden;
}

/* --- GALLERY --- */
.gallery {
    text-align: center;
}
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3-column layout */
    grid-auto-rows: 250px; /* Uniform height for images */
    gap: 20px;
}

.gallery-grid img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, opacity 0.3s;
}

.gallery-grid img:hover {
    transform: scale(1.02);
    opacity: 0.9;
}

.gallery-grid img:nth-child(1) {
    grid-column: span 2; /* Make the first image span 2 columns */
}
.gallery-grid img:nth-child(4) {
    grid-row: span 2; /* Make the fourth image span 2 rows */
}


/* --- CONTACT --- */
.contact {
    display: flex;
    flex-wrap: nowrap; /* Prevent wrapping on desktop */
    justify-content: space-between;
    align-items: stretch;
    background: linear-gradient(135deg, var(--secondary-green), #a5d6a7);
    color: white;
    border-radius: 20px;
    padding: 0; /* Remove section padding */
    min-height: 400px;
}

.contact .info {
    flex: 1;
    padding: 50px;
    text-align: left;
}
.contact .info h3 {
    color: white;
    margin-bottom: 25px;
    font-size: 2.2rem;
}
.contact .info p {
    font-size: 1.1rem;
    margin-bottom: 15px;
    font-weight: 300;
}
.contact .map {
    flex: 1;
    width: 100%;
    border-radius: 0 20px 20px 0;
}

/* --- FOOTER --- */
footer {
    text-align: center;
    background-color: var(--primary-green);
    color: white;
    padding: 20px;
    margin-top: 50px;
    font-size: 0.9rem;
}


/* --- RESPONSIVE ADJUSTMENTS --- */
@media (max-width: 1024px) {
    .featured .cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    /* Hero */
    .hero { height: 50vh; margin: 20px; }
    .overlay h2 { font-size: 1.8rem; }
    .overlay p { font-size: 1rem; }
    .btn-secondary { margin-left: 0; margin-top: 10px; }
    .buttons { display: flex; flex-direction: column; }

    /* Message */
    .message .content {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }
    .message img {
        width: 100%;
        height: auto;
        max-width: 350px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }
    .message .text {
        text-align: center;
    }
    .message .text h3 {
        font-size: 1.8rem;
    }
    
    /* Gallery */
    .gallery-grid {
        grid-template-columns: 1fr 1fr;
        grid-auto-rows: 150px;
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
        order: -1; /* Puts map on top */
        min-height: 200px;
        border-radius: 15px 15px 0 0;
    }
    .contact .info {
        padding: 30px;
    }
}
@keyframes zoomHero {
    from {
        background-size: 110%; /* Adjusted starting zoom */
    }
    to {
        background-size: 120%; /* Adjusted ending zoom */
    }
}
</style>