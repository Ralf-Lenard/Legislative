<template>
  <header class="navbar" id="main-navbar" :class="{ open: isOpen }">
    <div class="logo">
      <img src="images/logo.jpg" alt="Concepcion Tarlac Seal" />
      <div>
        <h1>Sangguniang Bayan</h1>
        <span class="municipality">Municipality of Concepcion, Tarlac</span>
      </div>
    </div>

    <nav>
      <ul class="nav-links">
        <li>
          <a
            href="/home"
            :class="{ active: activeLink === '/home' }"
            @click="setActive('/home')"
            >Home</a
          >
        </li>
        <li>
          <a
            href="/sb"
            :class="{ active: activeLink === '/sb' }"
            @click="setActive('/sb')"
            >Sangguniang Bayan</a
          >
        </li>
        <li>
          <a
            href="/ordinances"
            :class="{ active: activeLink === '/ordinances' }"
            @click="setActive('/ordinances')"
            >Ordinances</a
          >
        </li>
        <li>
          <a
            href="/resolutions"
            :class="{ active: activeLink === '/resolutions' }"
            @click="setActive('/resolutions')"
            >Resolutions</a
          >
        </li>
     
      </ul>
    </nav>

    <button class="menu-toggle" @click="toggleMenu">
      {{ isOpen ? "✖" : "☰" }}
    </button>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";

const isOpen = ref(false);
const activeLink = ref(window.location.pathname);

function toggleMenu() {
  isOpen.value = !isOpen.value;
}

function setActive(path) {
  activeLink.value = path;
}

onMounted(() => {
  activeLink.value = window.location.pathname;
});
</script>

<style scoped>
/* Navbar Colors */
.navbar {
  --primary-green: #1b5e20;
  --accent-gold: #ffc107;

  background-color: var(--primary-green);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 60px;
  /* border-radius: 0 0 20px 20px; */
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
  font-family: "Poppins", sans-serif;

  /* ✅ FIXED STICKY NAVBAR */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 9999;
}

/* Add padding-top to body to prevent overlap */
:global(body) {
  padding-top: 100px; /* Adjust to your navbar height */
}

.logo {
  display: flex;
  align-items: center;
  gap: 20px;
}

.logo img {
  width: 60px;
  border-radius: 50%;
  border: 3px solid var(--accent-gold);
  box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}

.logo h1 {
  font-size: 1.6rem;
  font-weight: 700;
  margin: 0;
  letter-spacing: 0.5px;
}

.logo .municipality {
  font-size: 0.9rem;
  font-weight: 300;
  opacity: 0.9;
  border-left: 2px solid rgba(255, 255, 255, 0.4);
  padding-left: 20px;
  line-height: 1.2;
}

nav {
  display: flex;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 40px;
  margin: 0;
  padding: 0;
}

.navbar a {
  text-decoration: none;
  color: white;
  font-weight: 500;
  position: relative;
  transition: all 0.3s ease;
  padding: 5px 0;
}

/* Underline effect */
.navbar a::after {
  content: "";
  position: absolute;
  width: 0;
  height: 3px;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  background: var(--accent-gold);
  transition: width 0.3s ease, background-color 0.3s ease;
}

.navbar a:hover::after {
  width: 100%;
  background: white;
}

.navbar a:hover {
  color: var(--accent-gold);
}

/* ✅ Active Link Highlight */
.navbar a.active {
  color: var(--accent-gold);
  font-weight: 700;
}
.navbar a.active::after {
  width: 100%;
  background: white;
}

/* Hamburger Button */
.menu-toggle {
  display: none;
  background: var(--accent-gold);
  border: none;
  color: var(--primary-green);
  font-size: 1.5rem;
  cursor: pointer;
  align-self: center;
  width: 40px;
  height: 40px;
  border-radius: 5px;
  font-weight: 700;
  transition: transform 0.3s ease, background-color 0.3s;
}

.menu-toggle:hover {
  background-color: #e6b000;
}

/* Responsive Styles */
@media (max-width: 1024px) {
  .logo .municipality {
    display: none;
  }
}

@media (max-width: 768px) {
  .navbar {
    flex-wrap: wrap;
    padding: 15px 20px;
    border-radius: 0 0 10px 10px;
  }

  .logo {
    width: 70%;
    gap: 10px;
  }
  .logo img {
    width: 45px;
  }
  .logo h1 {
    font-size: 1.3rem;
  }

  .menu-toggle {
    display: block;
  }

  nav {
    width: 100%;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease-in-out;
    flex-direction: column;
    order: 3;
  }

  .nav-links {
    flex-direction: column;
    width: 100%;
    gap: 0;
    padding: 10px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
  }

  .nav-links li {
    text-align: center;
    padding: 10px 0;
  }

  .navbar a {
    display: block;
  }

  .navbar a:hover::after {
    width: 30%;
  }

  /* Show menu when open */
  .navbar.open nav {
    max-height: 400px;
  }
}
</style>
