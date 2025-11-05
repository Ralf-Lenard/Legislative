<template>
    <div class="home-page">
      <!-- HEADER -->
      <header class="navbar">
        <div class="logo">
          <img
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Concepcion_Tarlac_Seal.png/640px-Concepcion_Tarlac_Seal.png"
            alt="Concepcion Tarlac Seal"
          />
          <h1>Municipality of Concepcion, Tarlac</h1>
        </div>
        <nav>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="/sb">Sangguniang Bayan</a></li>
            <li><a href="#">Ordinances</a></li>
            <li><a href="#">Resolutions</a></li>
            <li><a href="#">About</a></li>
          </ul>
        </nav>
      </header>
  
      <!-- HERO SECTION -->
      <section class="hero">
        <div class="overlay">
          <h2>Welcome to the Official Website of the Sangguniang Bayan</h2>
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
              “As the Presiding Officer of the Sangguniang Bayan, I am proud to
              present this platform for open governance and active citizen
              participation. Together, we will build a stronger, greener, and more
              inclusive Concepcion.”
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
        <p>© 2025 Municipality of Concepcion, Tarlac | All Rights Reserved</p>
      </footer>
    </div>
  </template>
  
  <script>

import Navbar from "@/components/Home/Navbar.vue";
  export default {
    name: "HomePage",
    data() {
      return {
        features: [
          {
            title: "Ordinances",
            desc: "View all approved local ordinances.",
            image:
              "https://images.pexels.com/photos/3184419/pexels-photo-3184419.jpeg",
          },
          {
            title: "Resolutions",
            desc: "Access official resolutions passed by the council.",
            image:
              "https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg",
          },
          {
            title: "Members",
            desc: "Meet the Sangguniang Bayan members.",
            image:
              "https://images.pexels.com/photos/1181395/pexels-photo-1181395.jpeg",
          },
          {
            title: "Public Sessions",
            desc: "See upcoming and past sessions.",
            image:
              "https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg",
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
  
  <style scoped>
  /* General Styles */
  .home-page {
    background-color: #f4fdf5;
    color: #222;
    overflow-x: hidden;
  }
  
  /* Header */
  .navbar {
    background: linear-gradient(90deg, #1b5e20, #388e3c);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 40px;
    border-radius: 0 0 25px 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }
  
  .logo {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .logo img {
    width: 60px;
    border-radius: 50%;
  }
  
  .navbar ul {
    list-style: none;
    display: flex;
    gap: 25px;
  }
  
  .navbar a {
    text-decoration: none;
    color: white;
    font-weight: 500;
    transition: color 0.3s;
  }
  
  .navbar a:hover {
    color: #ffd700;
  }
  
  /* Hero */
  .hero {
    position: relative;
    background-image: url("https://images.pexels.com/photos/3184299/pexels-photo-3184299.jpeg");
    background-size: cover;
    background-position: center;
    height: 65vh;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 25px;
    margin: 25px;
    animation: zoomHero 15s infinite alternate ease-in-out;
  }
  
  @keyframes zoomHero {
    from {
      background-size: 100%;
    }
    to {
      background-size: 110%;
    }
  }
  
  .overlay {
    background: rgba(0, 0, 0, 0.5);
    padding: 50px;
    border-radius: 25px;
    text-align: center;
    color: #fff;
  }
  
  .overlay h2 {
    font-size: 2rem;
    margin-bottom: 10px;
  }
  
  .overlay p {
    margin-bottom: 20px;
    font-size: 1.1rem;
  }
  
  .btn-primary,
  .btn-secondary {
    padding: 10px 22px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
  }
  
  .btn-primary {
    background-color: #2e8b57;
    color: white;
  }
  
  .btn-secondary {
    background-color: #ffd700;
    color: #1b5e20;
  }
  
  .btn-primary:hover,
  .btn-secondary:hover {
    transform: scale(1.05);
  }
  
  /* Message */
  .message .content {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 30px;
    margin: 50px 30px;
  }
  
  .message img {
    width: 320px;
    border-radius: 25px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
  
  .message .text {
    flex: 1;
  }
  
  /* Featured */
  .featured {
    background-color: #e8f5e9;
    padding: 50px 20px;
    border-radius: 25px;
    text-align: center;
    margin: 20px;
  }
  
  .featured .cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 25px;
  }
  
  .card {
    background: white;
    border-radius: 25px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    transition: transform 0.3s;
  }
  
  .card:hover {
    transform: translateY(-5px);
  }
  
  .card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }
  
  .card-content {
    padding: 15px;
  }
  
  /* Gallery */
  .gallery {
    text-align: center;
    padding: 50px 20px;
  }
  
  .gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 25px;
  }
  
  .gallery-grid img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 25px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
  }
  
  .gallery-grid img:hover {
    transform: scale(1.05);
  }
  
  /* Contact */
  .contact {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #c8e6c9, #a5d6a7);
    border-radius: 25px;
    margin: 25px;
  }
  
  .contact .map {
    width: 400px;
    height: 250px;
    border: none;
    border-radius: 25px;
  }
  
  /* Footer */
  footer {
    text-align: center;
    background: linear-gradient(90deg, #1b5e20, #388e3c);
    color: white;
    padding: 18px;
    border-radius: 25px 25px 0 0;
    margin-top: 20px;
  }
  
  @media (max-width: 768px) {
    .navbar {
      flex-direction: column;
    }
  
    .message .content {
      flex-direction: column;
    }
  
    .contact {
      flex-direction: column;
    }
  
    .map {
      width: 100%;
    }
  }
  </style>
  