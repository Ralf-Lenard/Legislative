<template>
  <footer class="site-footer">
    <div class="footer-content">
      
      <!-- Left Section: LGU Info -->
      <div class="footer-section footer-about">
        <h3 class="h33">Municipality of Concepcion, Tarlac</h3>
        <p>
          Committed to transparency, integrity, and public service — building a
          stronger, greener, and more inclusive community.
        </p>
      </div>

      <!-- Middle Section: Quick Links -->
      <div class="footer-section footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="/home">Home</a></li>
          <li><a href="/ordinances">Ordinances</a></li>
          <li><a href="/resolutions">Resolutions</a></li>
          <li><a href="/sb">Members</a></li>
        </ul>
      </div>

      <!-- Right Section: Social Media -->
      <div class="footer-section footer-social">
        <h4>Follow Us</h4>
        <div class="social-icons">
          <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.tiktok.com" target="_blank"><i class="fab fa-tiktok"></i></a>
          <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
          <a href="mailto:sangguniangbayan@concepcion.gov.ph"><i class="fas fa-envelope"></i></a>
        </div>
      </div>

    </div>

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
/* --------------------------------------------------
   FOOTER MAIN STYLE
-------------------------------------------------- */
.site-footer {
  background: #104012;
  color: #fff;
  padding: 3rem 1rem;
  font-family: "Poppins", sans-serif;
}

/* Desktop layout (3 equal columns) */
.footer-content {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  max-width: 1200px;
  margin: 0 auto;
  gap: 3rem;
}

/* All sections */
.footer-section {
  flex: 1;
  min-width: 260px;
  text-align: center;
}

/* ----------------------------
   HEADERS
----------------------------- */
.footer-about h3,
.footer-links h4,
.footer-social h4 {
  color: var(--accent-gold);
  font-weight: 700;
  margin-bottom: 1rem;
}

/* ----------------------------
   ABOUT
----------------------------- */
.footer-about p {
  color: #e0e0e0;
  font-size: 0.95rem;
  line-height: 1.6;
}

/* ----------------------------
   QUICK LINKS
----------------------------- */
.footer-links ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  align-items: center; /* Center items */
  gap: 0.6rem;
  width: 100%;
}

.footer-links a {
  color: #e0e0e0;
  text-decoration: none;
  font-size: 0.95rem;
  transition: color 0.3s;
}

.footer-links a:hover {
  color: var(--accent-gold);
}

/* ----------------------------
   SOCIAL ICONS
----------------------------- */
.social-icons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.social-icons a {
  color: white;
  font-size: 1.4rem;
  width: 2.6rem;
  height: 2.6rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.1);
  transition: 0.3s;
}

.social-icons a:hover {
  transform: translateY(-5px);
  color: var(--accent-gold);
}

/* ----------------------------
   BOTTOM COPYRIGHT
----------------------------- */
.footer-bottom {
  text-align: center;
  margin-top: 2.5rem;
  font-size: 0.85rem;
  color: #e0e0e0;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  padding-top: 1rem;
}

/* --------------------------------------------------
   MOBILE FIX — FULL CENTER
-------------------------------------------------- */
@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    align-items: center;      /* ensures full center */
    text-align: center;       /* center all text */
    gap: 2rem;
  }

  .footer-section {
    width: 100%;
    max-width: 400px;         /* keeps centered block width */
  }

  .footer-links ul {
    align-items: center;      /* center list items */
    text-align: center;
    width: 100%;
  }

  .social-icons {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .footer-section {
    max-width: 340px;
  }

  .social-icons a {
    width: 2.3rem;
    height: 2.3rem;
    font-size: 1.2rem;
  }
}
</style>
