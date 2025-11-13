<template>
    <footer class="site-footer">
      <div class="footer-content">
        <!-- Left Section: LGU Info -->
        <div class="footer-about">
          <h3>Municipality of Concepcion, Tarlac</h3>
          <p>
            Committed to transparency, integrity, and public service — building a stronger,
            greener, and more inclusive community.
          </p>
        </div>
  
        <!-- Middle Section: Quick Links -->
        <div class="footer-links">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="/home">Home</a></li>
            <li><a href="/ordinances">Ordinances</a></li>
            <li><a href="/resolutions">Resolutions</a></li>
            <li><a href="/sb">Members</a></li>
           
          </ul>
        </div>
  
        <!-- Right Section: Social Media -->
        <div class="footer-social">
          <h4>Follow Us</h4>
          <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.tiktok.com" target="_blank" aria-label="TikTok">
              <i class="fab fa-tiktok"></i>
            </a>
            <a href="https://twitter.com" target="_blank" aria-label="Twitter">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="mailto:sangguniangbayan@concepcion.gov.ph" aria-label="Email">
              <i class="fas fa-envelope"></i>
            </a>
          </div>
        </div>
      </div>
  
      <!-- Bottom Bar -->
      <div class="footer-bottom">
        <p>© 2025 Municipality of Concepcion, Tarlac | All Rights Reserved</p>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: "FooterSection",
  };
  </script>
  
  <style scoped>
  /* --- FOOTER STYLES --- */
  .site-footer {
    background: #104012;
    color: #fff;
    padding: 60px 20px 20px;
    font-family: "Poppins", sans-serif;
  }
  
  .footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    gap: 40px;
  }
  
  /* Left */
  .footer-about {
    flex: 1;
    min-width: 250px;
  }
  .footer-about h3 {
    color: var(--accent-gold);
    margin-bottom: 15px;
  }
  .footer-about p {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #e0e0e0;
  }
  
  /* Middle */
  .footer-links {
    flex: 1;
    min-width: 200px;
  }
  .footer-links h4 {
    color: var(--accent-gold);
    margin-bottom: 15px;
  }
  .footer-links ul {
    list-style: none;
    padding: 0;
  }
  .footer-links li {
    margin-bottom: 10px;
  }
  .footer-links a {
    color: #e0e0e0;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  .footer-links a:hover {
    color: var(--accent-gold);
  }
  
  /* Right */
  .footer-social {
    flex: 1;
    min-width: 200px;
  }
  .footer-social h4 {
    color: var(--accent-gold);
    margin-bottom: 15px;
  }
  .social-icons {
    display: flex;
    gap: 15px;
  }
  .social-icons a {
    color: white;
    font-size: 1.5rem;
    transition: transform 0.3s, color 0.3s;
  }
  .social-icons a:hover {
    color: var(--accent-gold);
    transform: translateY(-5px);
  }
  
  /* Bottom */
  .footer-bottom {
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    margin-top: 40px;
    padding-top: 20px;
    font-size: 0.9rem;
    color: #e0e0e0;
  }
  
  @media (max-width: 768px) {
    .footer-content {
      flex-direction: column;
      text-align: center;
    }
    .social-icons {
      justify-content: center;
    }
  }
  </style>
  