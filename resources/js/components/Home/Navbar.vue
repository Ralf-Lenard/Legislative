<template>
  <header
    class="navbar"
    id="main-navbar"
    :class="{ open: isOpen, 'navbar--hidden': isHidden }"
  >
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

        <li>
          <a
            href="/sessions"
            :class="{ active: activeLink === '/sessions' }"
            @click="setActive('/sessions')"
            >Sessions</a
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
import { ref, onMounted, onBeforeUnmount } from "vue";

const isOpen = ref(false);
const activeLink = ref(window.location.pathname);

// --- New Scroll Logic State ---
const isHidden = ref(false);
const lastScrollPosition = ref(0);
const scrollOffset = 60; // Pixels threshold to prevent flickering on small scrolls
// -----------------------------

function toggleMenu() {
  isOpen.value = !isOpen.value;
}

function setActive(path) {
  activeLink.value = path;
}

// --- New Scroll Logic Method ---
function onScroll() {
  const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;

  // Stop if scrolling up when already at the top
  if (currentScrollPosition < 0) {
    return;
  }

  // Stop executing if the difference between current and last position is less than the offset
  if (Math.abs(currentScrollPosition - lastScrollPosition.value) < scrollOffset) {
    return;
  }

  // Scroll Down: current > last -> isHidden = true
  // Scroll Up: current < last -> isHidden = false
  isHidden.value = currentScrollPosition > lastScrollPosition.value;

  // Update the last scroll position for the next check
  lastScrollPosition.value = currentScrollPosition;
}
// -----------------------------

onMounted(() => {
  activeLink.value = window.location.pathname;
  // Initialize lastScrollPosition and add the event listener
  lastScrollPosition.value = window.pageYOffset;
  window.addEventListener("scroll", onScroll);
});

// Cleanup the event listener when the component is unmounted
onBeforeUnmount(() => {
  window.removeEventListener("scroll", onScroll);
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
  padding: 1.25rem 3.75rem;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
  font-family: "Poppins", sans-serif;

  /* Fixed sticky navbar */
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 100;
  
  /* ADD: Smooth transition for the hide/show effect */
  transition: all 0.3s ease, transform 0.3s ease-out;
}

/* NEW: Class to hide the navbar on scroll down */
.navbar--hidden {
  /* Move the entire navbar up by its full height */
  transform: translateY(-100%);
  box-shadow: none;
}

:global(body) {
  padding-top: 6.25rem;
}

.logo {
  display: flex;
  align-items: center;
  gap: 1.25rem;
}

.logo img {
  width: 3.75rem;
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
  padding-left: 1.25rem;
  line-height: 1.2;
}

nav {
  display: flex;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 2.5rem;
  margin: 0;
  padding: 0;
}

.navbar a {
  text-decoration: none;
  color: white;
  font-weight: 500;
  position: relative;
  transition: all 0.3s ease;
  padding: 0.3125rem 0;
  font-size: 0.95rem;
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

/* Active Link Highlight */
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
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 5px;
  font-weight: 700;
  transition: transform 0.3s ease, background-color 0.3s;
}

.menu-toggle:hover {
  background-color: #e6b000;
}

/* Enhanced responsive breakpoints for tablet and below */
@media (max-width: 1280px) {
  .navbar {
    padding: 1rem 2.5rem;
  }
  
  .logo h1 {
    font-size: 1.4rem;
  }
  
  .nav-links {
    gap: 2rem;
  }
}

@media (max-width: 1024px) {
  .logo .municipality {
    display: none;
  }
  
  .logo {
    gap: 0.75rem;
  }
  
  .logo h1 {
    font-size: 1.2rem;
  }
  
  .nav-links {
    gap: 1.5rem;
  }
  
  .navbar a {
    font-size: 0.9rem;
  }
}

@media (max-width: 768px) {
  .navbar {
    flex-wrap: wrap;
    padding: 0.9375rem 1.25rem;
  }

  .logo {
    width: 70%;
    gap: 0.625rem;
  }
  
  .logo img {
    width: 2.8125rem;
  }
  
  .logo h1 {
    font-size: 1rem;
  }

  .menu-toggle {
    display: block;
    width: 2.25rem;
    height: 2.25rem;
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
    padding: 0.625rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
  }

  .nav-links li {
    text-align: center;
    padding: 0.625rem 0;
  }

  .navbar a {
    display: block;
    font-size: 0.9rem;
  }

  .navbar a:hover::after {
    width: 30%;
  }

  /* Show menu when open */
  .navbar.open nav {
    max-height: 400px;
  }
}

@media (max-width: 480px) {
  .navbar {
    padding: 0.75rem 1rem;
  }
  
  .logo {
    width: 65%;
  }
  
  .logo h1 {
    font-size: 0.9rem;
  }
  
  .logo img {
    width: 2.5rem;
  }
  
  .menu-toggle {
    width: 2rem;
    height: 2rem;
    font-size: 1.25rem;
  }
  
  :global(body) {
    padding-top: 5rem;
  }
}
</style>